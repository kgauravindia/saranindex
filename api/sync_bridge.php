<?php
/**
 * SaranIndex.com - Secure Data Sync Bridge API
 * 
 * This endpoint allows authorized local/offline instances to synchronize
 * database tables and media uploads from the online production server.
 */

// Disable output buffering & set JSON header
if (ob_get_level()) ob_end_clean();
header('Content-Type: application/json; charset=utf-8');

// Enable error logging but disable display to keep JSON output clean
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

// Helper function to send JSON response and terminate
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// 1. Authentication Check
$providedKey = '';

// Check Authorization header (Bearer token)
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
    $providedKey = trim($matches[1]);
}

// Check custom header X-Sync-Key
if (empty($providedKey) && isset($_SERVER['HTTP_X_SYNC_KEY'])) {
    $providedKey = trim($_SERVER['HTTP_X_SYNC_KEY']);
}

// Check POST or GET parameter
if (empty($providedKey)) {
    $providedKey = $_POST['sync_key'] ?? $_GET['sync_key'] ?? $_GET['token'] ?? '';
}

$validKey = defined('SYNC_SECRET_KEY') ? SYNC_SECRET_KEY : '';

if (empty($validKey) || empty($providedKey) || !hash_equals($validKey, $providedKey)) {
    sendResponse([
        'status' => 'error',
        'code' => 401,
        'message' => 'Unauthorized: Invalid or missing Sync Secret Key. Check SYNC_SECRET_KEY in config.php.'
    ], 401);
}

// 2. Database Connection
$db = getDB();
if (!$db) {
    sendResponse([
        'status' => 'error',
        'code' => 500,
        'message' => 'Database connection failed on server.'
    ], 500);
}

// 3. Action Dispatcher
$action = $_GET['action'] ?? $_POST['action'] ?? 'ping';

try {
    switch ($action) {
        case 'ping':
            sendResponse([
                'status' => 'success',
                'message' => 'Saran Index Sync Bridge is active and reachable.',
                'app_name' => defined('APP_NAME') ? APP_NAME : 'Saran Index',
                'app_version' => defined('APP_VERSION') ? APP_VERSION : '1.0.0',
                'php_version' => PHP_VERSION,
                'server_time' => date('Y-m-d H:i:s'),
                'server_os' => PHP_OS,
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize')
            ]);
            break;

        case 'table_stats':
            // Get all tables in database
            $stmt = $db->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $stats = [];
            $totalRecords = 0;

            foreach ($tables as $tbl) {
                // Table row count
                $countStmt = $db->query("SELECT COUNT(*) FROM `{$tbl}`");
                $rowCount = (int)$countStmt->fetchColumn();
                $stats[$tbl] = [
                    'table_name' => $tbl,
                    'row_count' => $rowCount
                ];
                $totalRecords += $rowCount;
            }

            sendResponse([
                'status' => 'success',
                'tables' => $stats,
                'total_tables' => count($stats),
                'total_records' => $totalRecords,
                'server_time' => date('Y-m-d H:i:s')
            ]);
            break;

        case 'fetch_table':
            $tableName = trim($_GET['table'] ?? $_POST['table'] ?? '');
            $offset = max(0, (int)($_GET['offset'] ?? $_POST['offset'] ?? 0));
            $limit = max(1, min(2000, (int)($_GET['limit'] ?? $_POST['limit'] ?? 500)));

            if (empty($tableName)) {
                sendResponse(['status' => 'error', 'message' => 'Table name parameter is required.'], 400);
            }

            // Whitelist validation for table name
            $stmt = $db->query("SHOW TABLES");
            $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array($tableName, $existingTables, true)) {
                sendResponse(['status' => 'error', 'message' => "Table '{$tableName}' does not exist on server."], 404);
            }

            // Get Table Structure
            $colStmt = $db->query("SHOW COLUMNS FROM `{$tableName}`");
            $columns = $colStmt->fetchAll(PDO::FETCH_ASSOC);

            // Get Primary Key and Unique Keys
            $keyStmt = $db->query("SHOW KEYS FROM `{$tableName}` WHERE Non_unique = 0");
            $uniqueKeys = $keyStmt->fetchAll(PDO::FETCH_ASSOC);
            $primaryKeys = [];
            foreach ($uniqueKeys as $k) {
                if ($k['Key_name'] === 'PRIMARY') {
                    $primaryKeys[] = $k['Column_name'];
                }
            }

            // Get Total Rows
            $countStmt = $db->query("SELECT COUNT(*) FROM `{$tableName}`");
            $totalRows = (int)$countStmt->fetchColumn();

            // Fetch chunk of rows
            $dataStmt = $db->prepare("SELECT * FROM `{$tableName}` LIMIT :offset, :limit");
            $dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $dataStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $dataStmt->execute();
            $rows = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

            sendResponse([
                'status' => 'success',
                'table' => $tableName,
                'primary_keys' => $primaryKeys,
                'columns' => $columns,
                'offset' => $offset,
                'limit' => $limit,
                'total_rows' => $totalRows,
                'fetched_rows' => count($rows),
                'rows' => $rows
            ]);
            break;

        case 'list_uploads':
            $uploadsDir = realpath(__DIR__ . '/../uploads');
            $filesList = [];

            if ($uploadsDir && is_dir($uploadsDir)) {
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($uploadsDir, RecursiveDirectoryIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        $fullPath = $file->getRealPath();
                        $relativePath = ltrim(str_replace('\\', '/', substr($fullPath, strlen($uploadsDir))), '/');
                        
                        // Ignore hidden / system files
                        if (str_starts_with(basename($relativePath), '.')) continue;

                        $filesList[] = [
                            'path' => $relativePath,
                            'size' => $file->getSize(),
                            'mtime' => $file->getMTime(),
                            'md5' => md5_file($fullPath)
                        ];
                    }
                }
            }

            sendResponse([
                'status' => 'success',
                'total_files' => count($filesList),
                'files' => $filesList
            ]);
            break;

        case 'fetch_upload':
            $requestedFile = trim($_GET['file'] ?? $_POST['file'] ?? '');
            if (empty($requestedFile)) {
                sendResponse(['status' => 'error', 'message' => 'File path parameter is required.'], 400);
            }

            // Directory traversal prevention
            $uploadsDir = realpath(__DIR__ . '/../uploads');
            $targetPath = realpath($uploadsDir . '/' . $requestedFile);

            if (!$targetPath || !file_exists($targetPath) || !str_starts_with($targetPath, $uploadsDir)) {
                sendResponse(['status' => 'error', 'message' => 'File not found or access denied.'], 404);
            }

            // Stream raw file
            $mime = mime_content_type($targetPath) ?: 'application/octet-stream';
            header('Content-Type: ' . $mime);
            header('Content-Length: ' . filesize($targetPath));
            header('Content-Disposition: inline; filename="' . basename($targetPath) . '"');
            readfile($targetPath);
            exit;

        case 'export_sql':
            // Generate standard SQL dump for selected or all tables
            $tablesToExport = isset($_GET['tables']) ? explode(',', $_GET['tables']) : [];
            $allTablesStmt = $db->query("SHOW TABLES");
            $allTables = $allTablesStmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($tablesToExport) || in_array('*', $tablesToExport)) {
                $tablesToExport = $allTables;
            } else {
                $tablesToExport = array_intersect($tablesToExport, $allTables);
            }

            header('Content-Type: text/plain; charset=utf-8');
            header('Content-Disposition: attachment; filename="saranindex_sync_dump_' . date('Ymd_His') . '.sql"');

            echo "-- SaranIndex Live Database Sync Dump\n";
            echo "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
            echo "-- Host: " . ($_SERVER['HTTP_HOST'] ?? 'saranindex.com') . "\n\n";
            echo "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tablesToExport as $tbl) {
                // Create table statement
                $createStmt = $db->query("SHOW CREATE TABLE `{$tbl}`");
                $createRow = $createStmt->fetch(PDO::FETCH_NUM);
                echo "-- Table structure for `{$tbl}`\n";
                echo "DROP TABLE IF EXISTS `{$tbl}`;\n";
                echo $createRow[1] . ";\n\n";

                // Table data
                $rowsStmt = $db->query("SELECT * FROM `{$tbl}`");
                $rows = $rowsStmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($rows)) {
                    echo "-- Dumping data for `{$tbl}`\n";
                    foreach (array_chunk($rows, 100) as $chunk) {
                        $keys = array_keys($chunk[0]);
                        $fields = implode('`, `', $keys);
                        echo "INSERT INTO `{$tbl}` (`{$fields}`) VALUES\n";
                        $valuesArr = [];
                        foreach ($chunk as $row) {
                            $escapedValues = array_map(function($val) use ($db) {
                                if (is_null($val)) return 'NULL';
                                return $db->quote($val);
                            }, array_values($row));
                            $valuesArr[] = "(" . implode(', ', $escapedValues) . ")";
                        }
                        echo implode(",\n", $valuesArr) . ";\n";
                    }
                    echo "\n";
                }
            }

            echo "SET FOREIGN_KEY_CHECKS=1;\n";
            exit;

        default:
            sendResponse(['status' => 'error', 'message' => "Unknown action '{$action}'."], 400);
    }
} catch (Exception $e) {
    sendResponse([
        'status' => 'error',
        'code' => 500,
        'message' => 'Internal Bridge Error: ' . $e->getMessage()
    ], 500);
}

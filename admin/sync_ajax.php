<?php
/**
 * SaranIndex.com - Admin Data Sync AJAX Handler
 */

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/auth.php';
checkAdminAuth();

require_once __DIR__ . '/includes/sync_engine.php';

function jsonOut($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$remoteUrl = trim($_POST['remote_url'] ?? $_GET['remote_url'] ?? '');
$syncKey = trim($_POST['sync_key'] ?? $_GET['sync_key'] ?? '');

$engine = new SyncEngine($remoteUrl, $syncKey);

try {
    switch ($action) {
        case 'test_connection':
            $res = $engine->testConnection();
            jsonOut($res);
            break;

        case 'fetch_stats':
            $res = $engine->getTableComparison();
            jsonOut($res);
            break;

        case 'sync_table_batch':
            $table = trim($_POST['table'] ?? '');
            $offset = max(0, (int)($_POST['offset'] ?? 0));
            $limit = max(1, min(2000, (int)($_POST['limit'] ?? 500)));
            $mode = ($_POST['mode'] ?? 'upsert') === 'replace' ? 'replace' : 'upsert';

            if (empty($table)) {
                jsonOut(['success' => false, 'error' => 'Table name is required.'], 400);
            }

            $res = $engine->syncTableBatch($table, $offset, $limit, $mode);
            jsonOut($res);
            break;

        case 'get_media_list':
            $res = $engine->getMediaList();
            jsonOut($res);
            break;

        case 'download_media_file':
            $file = trim($_POST['file'] ?? '');
            if (empty($file)) {
                jsonOut(['success' => false, 'error' => 'File path is required.'], 400);
            }
            $res = $engine->downloadMediaFile($file);
            jsonOut($res);
            break;

        case 'direct_db_sync':
            $host = trim($_POST['db_host'] ?? 'localhost');
            $user = trim($_POST['db_user'] ?? '');
            $pass = $_POST['db_pass'] ?? '';
            $dbname = trim($_POST['db_name'] ?? '');
            $mode = ($_POST['mode'] ?? 'upsert') === 'replace' ? 'replace' : 'upsert';
            $tables = !empty($_POST['tables']) ? (is_array($_POST['tables']) ? $_POST['tables'] : explode(',', $_POST['tables'])) : [];

            $res = $engine->syncDirectPdo($host, $user, $pass, $dbname, $tables, $mode);
            jsonOut($res);
            break;

        case 'import_sql_file':
            if (!isset($_FILES['sql_file']) || $_FILES['sql_file']['error'] !== UPLOAD_ERR_OK) {
                jsonOut(['success' => false, 'error' => 'No valid SQL file was uploaded.'], 400);
            }

            $fileTmp = $_FILES['sql_file']['tmp_name'];
            $fileName = $_FILES['sql_file']['name'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if ($fileExt !== 'sql' && $fileExt !== 'txt') {
                jsonOut(['success' => false, 'error' => 'Only .sql files are accepted.'], 400);
            }

            $sqlContent = file_get_contents($fileTmp);
            if (empty($sqlContent)) {
                jsonOut(['success' => false, 'error' => 'The uploaded SQL file is empty.'], 400);
            }

            $db = getDB();
            if (!$db) {
                jsonOut(['success' => false, 'error' => 'Local database connection failed.'], 500);
            }

            $db->exec("SET FOREIGN_KEY_CHECKS = 0");
            $db->exec($sqlContent);
            $db->exec("SET FOREIGN_KEY_CHECKS = 1");

            jsonOut([
                'success' => true,
                'message' => 'SQL file successfully imported into local database.'
            ]);
            break;

        default:
            jsonOut(['success' => false, 'error' => "Unknown action '{$action}'."], 400);
    }
} catch (Exception $e) {
    jsonOut([
        'success' => false,
        'error' => 'AJAX Handler Error: ' . $e->getMessage()
    ], 500);
}

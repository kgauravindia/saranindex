<?php
/**
 * SaranIndex.com - Data Sync Engine
 * 
 * Handles bidirectional synchronization logic between live online server
 * and local offline environment.
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

class SyncEngine {
    private $remoteUrl;
    private $syncKey;
    private $db;
    private $timeout;

    public function __construct($remoteUrl = null, $syncKey = null, $timeout = 30) {
        $this->remoteUrl = rtrim($remoteUrl ?: (defined('REMOTE_LIVE_URL') ? REMOTE_LIVE_URL : 'https://saranindex.com/'), '/') . '/';
        $this->syncKey = $syncKey ?: (defined('SYNC_SECRET_KEY') ? SYNC_SECRET_KEY : '');
        $this->timeout = $timeout;
        $this->db = getDB();
    }

    /**
     * Send HTTP request to Remote Sync Bridge API
     */
    public function makeApiRequest($action, $params = [], $method = 'GET') {
        $endpoint = $this->remoteUrl . 'api/sync_bridge.php';
        
        $queryParams = [
            'action' => $action,
            'sync_key' => $this->syncKey,
            'token' => $this->syncKey
        ];

        if ($method === 'GET') {
            $queryParams = array_merge($queryParams, $params);
            $url = $endpoint . '?' . http_build_query($queryParams);
        } else {
            $url = $endpoint . '?' . http_build_query($queryParams);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SaranIndex-Offline-Sync-Engine/1.3');

        // Set Authentication Headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-Sync-Key: ' . $this->syncKey,
            'Authorization: Bearer ' . $this->syncKey,
            'Accept: application/json'
        ]);

        if ($method === 'POST') {
            $postData = array_merge(['sync_key' => $this->syncKey], $params);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return [
                'success' => false,
                'code' => 0,
                'error' => "cURL Network Error: {$curlError}. Please check your internet connection."
            ];
        }

        $decoded = json_decode($response, true);
        if ($decoded === null && !empty($response)) {
            if ($httpCode === 404) {
                return [
                    'success' => false,
                    'code' => 404,
                    'error' => "Remote Bridge Not Found (HTTP 404). The file 'api/sync_bridge.php' is not yet uploaded to {$this->remoteUrl}. Please upload 'api/sync_bridge.php' to your online server or push via Git.",
                    'raw_response' => substr($response, 0, 500)
                ];
            }
            if ($httpCode === 403) {
                return [
                    'success' => false,
                    'code' => 403,
                    'error' => "Access Forbidden (HTTP 403) on {$this->remoteUrl}. Server firewall or Cloudflare is blocking the request.",
                    'raw_response' => substr($response, 0, 500)
                ];
            }
            return [
                'success' => false,
                'code' => $httpCode,
                'error' => "Invalid response format from remote server (HTTP {$httpCode}). Server returned HTML instead of JSON.",
                'raw_response' => substr($response, 0, 500)
            ];
        }

        if ($httpCode === 401) {
            return [
                'success' => false,
                'code' => 401,
                'error' => "Authentication Failed (HTTP 401). The Sync Secret Key provided does not match SYNC_SECRET_KEY configured on {$this->remoteUrl}.",
                'data' => $decoded
            ];
        }

        return [
            'success' => ($httpCode >= 200 && $httpCode < 300 && ($decoded['status'] ?? '') === 'success'),
            'code' => $httpCode,
            'data' => $decoded,
            'error' => $decoded['message'] ?? ($httpCode >= 400 ? "HTTP Error {$httpCode}" : null)
        ];
    }

    /**
     * Test connection to remote sync bridge
     */
    public function testConnection() {
        $result = $this->makeApiRequest('ping');
        if (!$result['success']) {
            return [
                'connected' => false,
                'code' => $result['code'] ?? 0,
                'message' => $result['error'] ?? 'Unable to connect to remote server.',
                'details' => $result
            ];
        }

        return [
            'connected' => true,
            'code' => 200,
            'message' => $result['data']['message'] ?? 'Connected successfully.',
            'server_info' => $result['data']
        ];
    }

    /**
     * Get comparison stats between Local and Remote database tables
     */
    public function getTableComparison() {
        if (!$this->db) {
            return ['success' => false, 'error' => 'Local database connection unavailable.'];
        }

        // 1. Fetch Remote Table Stats First
        $remoteResult = $this->makeApiRequest('table_stats');
        if (!$remoteResult['success']) {
            return [
                'success' => false,
                'remote_connected' => false,
                'code' => $remoteResult['code'] ?? 0,
                'error' => $remoteResult['error'] ?? 'Failed to connect to online server.',
                'total_tables' => 0,
                'total_local_rows' => 0,
                'total_remote_rows' => 0,
                'tables' => []
            ];
        }

        $remoteStats = [];
        $remoteTablesData = $remoteResult['data']['tables'] ?? [];
        foreach ($remoteTablesData as $tbl => $data) {
            $remoteStats[$tbl] = (int)($data['row_count'] ?? 0);
        }

        // 2. Get Local Table Stats
        $localStats = [];
        $localTablesStmt = $this->db->query("SHOW TABLES");
        $localTables = $localTablesStmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($localTables as $tbl) {
            $cntStmt = $this->db->query("SELECT COUNT(*) FROM `{$tbl}`");
            $localStats[$tbl] = (int)$cntStmt->fetchColumn();
        }

        // Combine table list
        $allTableNames = array_unique(array_merge(array_keys($localStats), array_keys($remoteStats)));
        sort($allTableNames);

        $comparison = [];
        $totalLocalRows = 0;
        $totalRemoteRows = 0;

        foreach ($allTableNames as $tbl) {
            $locCount = $localStats[$tbl] ?? 0;
            $remCount = $remoteStats[$tbl] ?? 0;
            $diff = $remCount - $locCount;

            $totalLocalRows += $locCount;
            $totalRemoteRows += $remCount;

            $status = 'synced';
            if (!isset($localStats[$tbl])) {
                $status = 'missing_local';
            } elseif (!isset($remoteStats[$tbl])) {
                $status = 'missing_remote';
            } elseif ($diff !== 0) {
                $status = 'out_of_sync';
            }

            // Categorize table
            $category = 'System';
            if (in_array($tbl, ['listings', 'categories', 'subcategories', 'reviews', 'claims', 'sources'])) {
                $category = 'Directory & Content';
            } elseif (in_array($tbl, ['users', 'admins', 'deleted_users', 'people'])) {
                $category = 'Users & Accounts';
            } elseif (in_array($tbl, ['blocks', 'panchayats', 'villages', 'halka', 'census', 'lgd_village', 'op_sdb'])) {
                $category = 'Master & Geo Data';
            } elseif (in_array($tbl, ['payments', 'payment_orders', 'payment_transactions'])) {
                $category = 'Finance & Transactions';
            } elseif (in_array($tbl, ['contact'])) {
                $category = 'Messages & Inquiries';
            }

            $comparison[$tbl] = [
                'table_name' => $tbl,
                'category' => $category,
                'local_rows' => $locCount,
                'remote_rows' => $remCount,
                'difference' => $diff,
                'status' => $status
            ];
        }

        return [
            'success' => true,
            'remote_connected' => $remoteResult['success'],
            'remote_error' => $remoteResult['error'] ?? null,
            'total_tables' => count($comparison),
            'total_local_rows' => $totalLocalRows,
            'total_remote_rows' => $totalRemoteRows,
            'tables' => $comparison
        ];
    }

    /**
     * Sync a single batch of a specific table
     */
    public function syncTableBatch($tableName, $offset = 0, $limit = 500, $mode = 'upsert') {
        if (!$this->db) {
            return ['success' => false, 'error' => 'Local database connection unavailable.'];
        }

        // Fetch chunk from remote
        $res = $this->makeApiRequest('fetch_table', [
            'table' => $tableName,
            'offset' => $offset,
            'limit' => $limit
        ]);

        if (!$res['success']) {
            return [
                'success' => false,
                'error' => $res['error'] ?? "Failed to fetch data for table {$tableName}."
            ];
        }

        $data = $res['data'];
        $rows = $data['rows'] ?? [];
        $totalRows = (int)($data['total_rows'] ?? 0);
        $primaryKeys = $data['primary_keys'] ?? ['id'];
        $columns = $data['columns'] ?? [];

        // Check if table exists locally, create if missing
        $checkStmt = $this->db->query("SHOW TABLES LIKE '{$tableName}'");
        if ($checkStmt->rowCount() === 0) {
            // Create table from column definitions
            $colDefs = [];
            foreach ($columns as $c) {
                $nullDef = ($c['Null'] === 'NO') ? 'NOT NULL' : 'NULL';
                $defaultDef = ($c['Default'] !== null) ? "DEFAULT " . $this->db->quote($c['Default']) : '';
                $extraDef = $c['Extra'] ?? '';
                $colDefs[] = "`{$c['Field']}` {$c['Type']} {$nullDef} {$defaultDef} {$extraDef}";
            }
            if (!empty($primaryKeys)) {
                $pkCols = implode('`, `', $primaryKeys);
                $colDefs[] = "PRIMARY KEY (`{$pkCols}`)";
            }
            $createSql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (\n  " . implode(",\n  ", $colDefs) . "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $this->db->exec($createSql);
        }

        // If offset == 0 and mode == replace, truncate table
        if ($offset === 0 && $mode === 'replace') {
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
            $this->db->exec("TRUNCATE TABLE `{$tableName}`");
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
        }

        $importedCount = 0;
        if (!empty($rows)) {
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
            $this->db->beginTransaction();

            try {
                $firstRow = $rows[0];
                $colNames = array_keys($firstRow);
                $escapedCols = implode('`, `', $colNames);
                $placeholders = implode(', ', array_fill(0, count($colNames), '?'));

                if ($mode === 'upsert') {
                    $updatePairs = [];
                    foreach ($colNames as $col) {
                        if (!in_array($col, $primaryKeys, true)) {
                            $updatePairs[] = "`{$col}` = VALUES(`{$col}`)";
                        }
                    }
                    if (empty($updatePairs)) {
                        $updatePairs[] = "`{$colNames[0]}` = VALUES(`{$colNames[0]}`)" ;
                    }
                    $sql = "INSERT INTO `{$tableName}` (`{$escapedCols}`) VALUES ({$placeholders}) ON DUPLICATE KEY UPDATE " . implode(', ', $updatePairs);
                } else {
                    $sql = "INSERT INTO `{$tableName}` (`{$escapedCols}`) VALUES ({$placeholders})";
                }

                $stmt = $this->db->prepare($sql);

                foreach ($rows as $row) {
                    $values = array_values($row);
                    $stmt->execute($values);
                    $importedCount++;
                }

                $this->db->commit();
                $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
            } catch (Exception $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
                return [
                    'success' => false,
                    'error' => "DB Write Error in {$tableName} at offset {$offset}: " . $e->getMessage()
                ];
            }
        }

        $nextOffset = $offset + count($rows);
        $isCompleted = ($nextOffset >= $totalRows || count($rows) === 0);

        return [
            'success' => true,
            'table' => $tableName,
            'offset' => $offset,
            'imported' => $importedCount,
            'next_offset' => $nextOffset,
            'total_rows' => $totalRows,
            'completed' => $isCompleted
        ];
    }

    /**
     * Get list of upload files to sync
     */
    public function getMediaList() {
        $res = $this->makeApiRequest('list_uploads');
        if (!$res['success']) {
            return ['success' => false, 'error' => $res['error'] ?? 'Failed to list remote uploads.'];
        }

        $remoteFiles = $res['data']['files'] ?? [];
        $localUploadsDir = realpath(__DIR__ . '/../../uploads') ?: (__DIR__ . '/../../uploads');

        $toDownload = [];
        $syncedCount = 0;

        foreach ($remoteFiles as $rf) {
            $relPath = $rf['path'];
            $localFilePath = $localUploadsDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relPath);

            $needsDownload = false;
            if (!file_exists($localFilePath)) {
                $needsDownload = true;
            } elseif (filesize($localFilePath) !== (int)$rf['size']) {
                $needsDownload = true;
            } elseif (isset($rf['md5']) && md5_file($localFilePath) !== $rf['md5']) {
                $needsDownload = true;
            }

            if ($needsDownload) {
                $toDownload[] = $rf;
            } else {
                $syncedCount++;
            }
        }

        return [
            'success' => true,
            'total_remote' => count($remoteFiles),
            'already_synced' => $syncedCount,
            'pending_download' => count($toDownload),
            'files' => $toDownload
        ];
    }

    /**
     * Download a single media file from remote
     */
    public function downloadMediaFile($relativePath) {
        $endpoint = $this->remoteUrl . 'api/sync_bridge.php?action=fetch_upload&file=' . urlencode($relativePath);
        $localUploadsDir = __DIR__ . '/../../uploads';
        $destPath = $localUploadsDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

        // Ensure parent directory exists
        $parentDir = dirname($destPath);
        if (!is_dir($parentDir)) {
            mkdir($parentDir, 0777, true);
        }

        $fp = fopen($destPath, 'w+');
        if (!$fp) {
            return ['success' => false, 'error' => "Cannot open local file for writing: {$destPath}"];
        }

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-Sync-Key: ' . $this->syncKey,
            'Authorization: Bearer ' . $this->syncKey
        ]);

        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        fclose($fp);

        if ($httpCode !== 200) {
            if (file_exists($destPath)) {
                unlink($destPath);
            }
            return ['success' => false, 'error' => "Download failed (HTTP {$httpCode}): {$curlError}"];
        }

        return [
            'success' => true,
            'file' => $relativePath,
            'size' => filesize($destPath)
        ];
    }

    /**
     * Direct Host-to-Host PDO Sync
     */
    public function syncDirectPdo($host, $user, $pass, $dbname, $tables = [], $mode = 'upsert') {
        try {
            $remoteDsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
            $remotePdo = new PDO($remoteDsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Remote DB Connection Failed: ' . $e->getMessage()];
        }

        if (empty($tables)) {
            $stmt = $remotePdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        $totalSynced = 0;
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");

        foreach ($tables as $tbl) {
            if ($mode === 'replace') {
                $this->db->exec("TRUNCATE TABLE `{$tbl}`");
            }

            $remStmt = $remotePdo->query("SELECT * FROM `{$tbl}`");
            $rows = $remStmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                $firstRow = $rows[0];
                $colNames = array_keys($firstRow);
                $escapedCols = implode('`, `', $colNames);
                $placeholders = implode(', ', array_fill(0, count($colNames), '?'));

                $updatePairs = [];
                foreach ($colNames as $c) {
                    $updatePairs[] = "`{$c}` = VALUES(`{$c}`)";
                }

                $sql = "INSERT INTO `{$tbl}` (`{$escapedCols}`) VALUES ({$placeholders}) ON DUPLICATE KEY UPDATE " . implode(', ', $updatePairs);
                $insertStmt = $this->db->prepare($sql);

                foreach ($rows as $r) {
                    $insertStmt->execute(array_values($r));
                    $totalSynced++;
                }
            }
        }

        $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");

        return [
            'success' => true,
            'tables_synced' => count($tables),
            'records_synced' => $totalSynced
        ];
    }
}

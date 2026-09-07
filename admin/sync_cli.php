<?php
/**
 * SaranIndex.com - Command Line Interface (CLI) Data Sync Tool
 * 
 * Usage:
 *   php admin/sync_cli.php --tables=all
 *   php admin/sync_cli.php --tables=listings,users,categories --mode=upsert
 *   php admin/sync_cli.php --tables=all --mode=replace --media
 *   php admin/sync_cli.php --test
 *   php admin/sync_cli.php --help
 */

if (php_sapi_name() !== 'cli') {
    die("Error: This script can only be run from the command line.\n");
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/includes/sync_engine.php';

// Helper for colored console output
function cliLog($msg, $type = 'info') {
    $colors = [
        'info' => "\033[0m",
        'success' => "\033[32m",
        'warn' => "\033[33m",
        'error' => "\033[31m",
        'bold' => "\033[1m",
        'cyan' => "\033[36m"
    ];
    $reset = "\033[0m";
    $prefix = date('[H:i:s] ');
    
    // Windows 10/11 supports ANSI escape codes
    echo ($colors[$type] ?? '') . $prefix . $msg . $reset . "\n";
}

// Parse Command Line Arguments
$options = getopt('', [
    'url::',
    'key::',
    'tables::',
    'mode::',
    'batch::',
    'media',
    'test',
    'help'
]);

if (isset($options['help'])) {
    echo "========================================================\n";
    echo "  SaranIndex.com - Online to Offline Data Sync CLI Tool\n";
    echo "========================================================\n\n";
    echo "Options:\n";
    echo "  --url=<url>         Remote server URL (Default: " . (defined('REMOTE_LIVE_URL') ? REMOTE_LIVE_URL : 'https://saranindex.com/') . ")\n";
    echo "  --key=<token>       Sync Secret Key (Default: from config.php)\n";
    echo "  --tables=<list>     Comma-separated tables to sync, or 'all' (Default: all)\n";
    echo "  --mode=<mode>       Sync mode: 'upsert' (merge/update) or 'replace' (clean wipe) (Default: upsert)\n";
    echo "  --batch=<size>      Batch chunk size (Default: 500)\n";
    echo "  --media             Flag to enable media/uploads synchronization\n";
    echo "  --test              Test connection and list remote stats without modifying local database\n";
    echo "  --help              Display this help manual\n\n";
    echo "Examples:\n";
    echo "  php admin/sync_cli.php --test\n";
    echo "  php admin/sync_cli.php --tables=all --mode=upsert\n";
    echo "  php admin/sync_cli.php --tables=listings,users --media\n\n";
    exit(0);
}

$remoteUrl = $options['url'] ?? (defined('REMOTE_LIVE_URL') ? REMOTE_LIVE_URL : 'https://saranindex.com/');
$syncKey = $options['key'] ?? (defined('SYNC_SECRET_KEY') ? SYNC_SECRET_KEY : '');
$mode = in_array(strtolower($options['mode'] ?? ''), ['replace', 'clean']) ? 'replace' : 'upsert';
$batchSize = max(10, min(2000, (int)($options['batch'] ?? 500)));
$includeMedia = isset($options['media']);
$isTestOnly = isset($options['test']);
$tableOption = $options['tables'] ?? 'all';

cliLog("=== Saran Index Online to Offline Data Sync CLI ===", 'bold');
cliLog("Target Remote URL: {$remoteUrl}", 'cyan');
cliLog("Sync Mode: " . strtoupper($mode), 'cyan');
cliLog("Connecting to remote bridge...", 'info');

$engine = new SyncEngine($remoteUrl, $syncKey);

// 1. Connection Test
$testRes = $engine->testConnection();
if (!$testRes['connected']) {
    cliLog("Connection Failed: " . ($testRes['message'] ?? 'Unknown error'), 'error');
    exit(1);
}

cliLog("Connected successfully to remote server!", 'success');
if (isset($testRes['server_info'])) {
    cliLog("Remote App: " . ($testRes['server_info']['app_name'] ?? 'Saran Index') . " | Version: " . ($testRes['server_info']['app_version'] ?? 'N/A'), 'info');
}

// 2. Fetch Comparison Stats
cliLog("Fetching table comparison stats...", 'info');
$comparison = $engine->getTableComparison();

if (!$comparison['success']) {
    cliLog("Failed to fetch table stats: " . ($comparison['error'] ?? 'Unknown error'), 'error');
    exit(1);
}

$tables = $comparison['tables'] ?? [];
cliLog(sprintf("%-25s | %-12s | %-12s | %-10s | %-15s", "Table Name", "Local Rows", "Remote Rows", "Diff", "Status"), 'bold');
cliLog(str_repeat("-", 85), 'info');

foreach ($tables as $tbl => $info) {
    $diffStr = ($info['difference'] > 0 ? "+{$info['difference']}" : "{$info['difference']}");
    cliLog(sprintf("%-25s | %-12d | %-12d | %-10s | %-15s", $tbl, $info['local_rows'], $info['remote_rows'], $diffStr, $info['status']), ($info['difference'] != 0 ? 'warn' : 'info'));
}
cliLog(str_repeat("-", 85), 'info');
cliLog("Total Tables: " . count($tables) . " | Local Records: " . number_format($comparison['total_local_rows']) . " | Remote Records: " . number_format($comparison['total_remote_rows']), 'cyan');

if ($isTestOnly) {
    cliLog("Test completed successfully (--test flag provided). No changes made.", 'success');
    exit(0);
}

// 3. Determine tables to sync
$targetTables = [];
if (strtolower($tableOption) === 'all') {
    $targetTables = array_keys($tables);
} else {
    $requested = array_map('trim', explode(',', $tableOption));
    foreach ($requested as $req) {
        if (isset($tables[$req])) {
            $targetTables[] = $req;
        } else {
            cliLog("Warning: Table '{$req}' was requested but not found on server.", 'warn');
        }
    }
}

if (empty($targetTables) && !$includeMedia) {
    cliLog("No valid tables selected for synchronization.", 'error');
    exit(1);
}

cliLog("Beginning sync of " . count($targetTables) . " tables in [{$mode}] mode...", 'bold');
$startTime = microtime(true);
$totalRecordsSynced = 0;

foreach ($targetTables as $index => $tbl) {
    $num = $index + 1;
    $total = count($targetTables);
    cliLog("[{$num}/{$total}] Syncing table `{$tbl}`...", 'cyan');

    $offset = 0;
    $tblFinished = false;

    while (!$tblFinished) {
        $batchRes = $engine->syncTableBatch($tbl, $offset, $batchSize, $mode);
        if (!$batchRes['success']) {
            cliLog("  Error syncing {$tbl} at offset {$offset}: " . ($batchRes['error'] ?? 'Unknown error'), 'error');
            break;
        }

        $imported = $batchRes['imported'];
        $totalRows = $batchRes['total_rows'];
        $totalRecordsSynced += $imported;

        cliLog("  -> Synced {$imported} rows (Offset: {$offset} / Total: {$totalRows})", 'info');

        if ($batchRes['completed'] || $imported === 0) {
            $tblFinished = true;
            cliLog("  [Completed] `{$tbl}` synchronized successfully.", 'success');
        } else {
            $offset = $batchRes['next_offset'];
        }
    }
}

// 4. Media Synchronization
if ($includeMedia) {
    cliLog("Scanning remote `uploads/` directory for missing files...", 'bold');
    $mediaRes = $engine->getMediaList();

    if ($mediaRes['success']) {
        $files = $mediaRes['files'] ?? [];
        cliLog("Found " . count($files) . " media files requiring download.", 'info');

        $mediaCount = 0;
        foreach ($files as $idx => $f) {
            $fnum = $idx + 1;
            $dlRes = $engine->downloadMediaFile($f['path']);
            if ($dlRes['success']) {
                $mediaCount++;
                cliLog("  [{$fnum}/" . count($files) . "] Downloaded: {$f['path']} (" . number_format($f['size'] / 1024, 1) . " KB)", 'success');
            } else {
                cliLog("  [{$fnum}/" . count($files) . "] Failed: {$f['path']} - " . ($dlRes['error'] ?? 'Unknown error'), 'error');
            }
        }
        cliLog("Media synchronization completed. Total files downloaded: {$mediaCount}", 'success');
    } else {
        cliLog("Media scan failed: " . ($mediaRes['error'] ?? 'Unknown error'), 'error');
    }
}

$duration = round(microtime(true) - $startTime, 2);
cliLog("========================================================", 'bold');
cliLog("  SYNCHRONIZATION COMPLETE in {$duration} seconds!", 'success');
cliLog("  Total Records Synced: " . number_format($totalRecordsSynced), 'success');
cliLog("========================================================", 'bold');
exit(0);

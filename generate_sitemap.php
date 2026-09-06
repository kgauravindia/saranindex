<?php
/**
 * XML Sitemap Generator CLI Script for SaranIndex.com
 * Usage: php generate_sitemap.php [https://saranindex.com/]
 */

if (php_sapi_name() !== 'cli' && (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true)) {
    header('HTTP/1.1 403 Forbidden');
    die("Access denied. CLI or authenticated Admin only.\n");
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_full_name'] = 'CLI Generator';

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// Include sitemap builder function
ob_start();
require_once __DIR__ . '/admin/sitemap.php';
ob_end_clean();

$targetDomain = !empty($argv[1]) ? trim($argv[1]) : 'https://saranindex.com/';
$startTime = microtime(true);

echo "====================================================\n";
echo " SaranIndex.com - XML Sitemap Generator\n";
echo " Canonical Domain: {$targetDomain}\n";
echo "====================================================\n";

$data = buildSitemapPayload($targetDomain);

$xmlFile = __DIR__ . '/sitemap.xml';
$gzFile = __DIR__ . '/sitemap.xml.gz';

$written = file_put_contents($xmlFile, $data['xml']);
if ($written === false) {
    echo "ERROR: Failed to write to {$xmlFile}\n";
    exit(1);
}

$gzWritten = false;
if (function_exists('gzencode')) {
    $gzWritten = file_put_contents($gzFile, gzencode($data['xml'], 9));
}

$timeTaken = round((microtime(true) - $startTime) * 1000, 2);

echo "✓ Generation completed in {$timeTaken} ms\n\n";
echo "Total URLs Indexed: " . number_format($data['total']) . "\n";
echo "Breakdown by Type:\n";
foreach ($data['counts'] as $type => $count) {
    echo "  • " . str_pad(ucfirst($type) . ":", 18) . number_format($count) . "\n";
}

echo "\nFiles Written:\n";
echo "  • sitemap.xml     : " . number_format(filesize($xmlFile) / 1024, 2) . " KB\n";
if ($gzWritten !== false) {
    echo "  • sitemap.xml.gz  : " . number_format(filesize($gzFile) / 1024, 2) . " KB\n";
}
echo "====================================================\n";
exit(0);

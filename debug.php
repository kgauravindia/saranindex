<?php
/**
 * Deep Diagnostic Debug Page
 * Saran Index - Upload this to live server and open in browser
 * DELETE AFTER USE for security
 */
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8">
<title>Debug – Saran Index Live</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="py-4">
<div class="container">
<h4 class="text-primary fw-bold mb-4">🔍 Deep Diagnostic — Saran Index Live Server</h4>
<?php
$db = getDB();
if (!$db) { echo "<div class='alert alert-danger'>❌ DB Connection FAILED</div>"; exit; }

echo "<div class='alert alert-success py-2'>✓ Database Connected</div>";

// 1. Show PHP version
echo "<div class='alert alert-info py-2'>PHP Version: <strong>" . phpversion() . "</strong></div>";

// 2. Show which functions.php is loaded
echo "<div class='alert alert-secondary py-2'>functions.php path: <strong>" . realpath(__DIR__ . '/includes/functions.php') . "</strong></div>";

// 3. Check if getUserListings exists and show its source hash
echo "<div class='alert alert-secondary py-2'>getUserListings function exists: <strong>" . (function_exists('getUserListings') ? 'YES' : 'NO') . "</strong></div>";
$funcSrc = (new ReflectionFunction('getUserListings'))->getFileName();
echo "<div class='alert alert-secondary py-2'>getUserListings file: <strong>{$funcSrc}</strong> (md5: " . md5_file($funcSrc) . ")</div>";

// 4. Show listings table columns
echo "<h5 class='mt-4 mb-2'>Listings Table Columns</h5>";
$cols = $db->query("SHOW COLUMNS FROM listings")->fetchAll(PDO::FETCH_ASSOC);
$colNames = array_column($cols, 'Field');
echo "<div class='alert " . (in_array('user_id', $colNames) ? 'alert-success' : 'alert-danger') . " py-2'>";
echo "user_id column: <strong>" . (in_array('user_id', $colNames) ? '✓ EXISTS' : '❌ MISSING') . "</strong><br>";
echo "All columns: <code>" . implode(', ', $colNames) . "</code></div>";

// 5. Find user with 8102930609
echo "<h5 class='mt-4 mb-2'>User Account Query</h5>";
$user = $db->query("SELECT id, full_name, mobile, email FROM users WHERE mobile LIKE '%8102930609%' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if ($user) {
    echo "<div class='alert alert-success py-2'>User Found: ID=#{$user['id']} | Name={$user['full_name']} | Mobile={$user['mobile']}</div>";
    $uid = $user['id'];
} else {
    echo "<div class='alert alert-danger'>User with 8102930609 NOT FOUND in database</div>";
    $uid = 0;
}

// 6. Direct listings query
echo "<h5 class='mt-4 mb-2'>Listings Direct Query (user_id = {$uid})</h5>";
if ($uid > 0) {
    try {
        $stmt = $db->query("SELECT id, title, mobile, user_id, status FROM listings WHERE user_id = {$uid} OR mobile LIKE '%8102930609%'");
        $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<div class='alert " . (count($listings) > 0 ? 'alert-success' : 'alert-warning') . " py-2'>Found " . count($listings) . " listings</div>";
        foreach ($listings as $l) {
            echo "<div class='card mb-2 p-2'><small>ID: {$l['id']} | user_id: {$l['user_id']} | mobile: {$l['mobile']} | title: " . htmlspecialchars($l['title']) . " | status: {$l['status']}</small></div>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Query Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

// 7. Test getUserListings() function
echo "<h5 class='mt-4 mb-2'>getUserListings({$uid}) Function Test</h5>";
if ($uid > 0) {
    try {
        $result = getUserListings($uid);
        echo "<div class='alert " . (count($result) > 0 ? 'alert-success' : 'alert-danger') . " py-2'>";
        echo "getUserListings returned <strong>" . count($result) . " listings</strong></div>";
        foreach ($result as $l) {
            echo "<div class='card mb-2 p-2'><small>ID: {$l['id']} | title: " . htmlspecialchars($l['title']) . " | user_id: {$l['user_id']} | claim_status: " . ($l['claim_status'] ?? 'N/A') . "</small></div>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>getUserListings ERROR: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

// 8. Claims table
echo "<h5 class='mt-4 mb-2'>Claims Table</h5>";
try {
    $claims = $db->query("SELECT * FROM claims ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    echo "<div class='alert alert-info py-2'>Total claims rows: " . count($claims) . "</div>";
    foreach ($claims as $c) {
        echo "<div class='card mb-2 p-2'><small>claim_id: {$c['id']} | listing_id: {$c['listing_id']} | user_id: {$c['user_id']} | mobile: {$c['claimant_mobile']} | status: {$c['status']}</small></div>";
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Claims error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

// 9. If user_id column missing, auto-fix it
if (!in_array('user_id', $colNames)) {
    echo "<h5 class='mt-4 mb-2'>🔧 Auto-Fix</h5>";
    try {
        $db->exec("ALTER TABLE `listings` ADD COLUMN `user_id` INT DEFAULT NULL AFTER `id`");
        $db->exec("ALTER TABLE `listings` ADD KEY `idx_listing_user_id` (`user_id`)");
        echo "<div class='alert alert-success py-2'>✓ user_id column CREATED. Refresh the page and then open dashboard.</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Fix failed: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

// 10. Auto-link listings
if ($uid > 0 && in_array('user_id', $colNames)) {
    echo "<h5 class='mt-4 mb-2'>🔧 Auto-Link Listings</h5>";
    $upd = $db->exec("UPDATE listings SET user_id = {$uid} WHERE mobile LIKE '%8102930609%' AND (user_id IS NULL OR user_id = 0)");
    echo "<div class='alert alert-success py-2'>✓ Linked {$upd} listings to user #{$uid}</div>";
}
?>
<a href="dashboard.php" class="btn btn-success mt-4 rounded-pill px-5 fw-bold">Open Dashboard →</a>
</div></body></html>

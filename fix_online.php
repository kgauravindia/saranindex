<?php
/**
 * Standalone Hostinger Live Server Database Repair & Auto-Migration Script
 * Saran Index - Digital Directory
 */

require_once __DIR__ . '/includes/functions.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Server Repair Tool – Saran Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', system-ui, sans-serif; }
        .card { border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
    </style>
</head>
<body class="py-5">
    <div class="container" style="max-width: 680px;">
        <div class="card border-0 p-4">
            <h3 class="fw-bold text-primary mb-2">⚡ Hostinger Live Database Repair</h3>
            <p class="text-muted small">Auto-syncs online MySQL database schema with local environment</p>
            <hr class="my-3">

            <?php
            $db = getDB();
            if (!$db) {
                echo "<div class='alert alert-danger'>❌ Database Connection Failed! Check config/config.php.</div>";
                exit;
            }

            echo "<div class='alert alert-success py-2 mb-3'>✓ Connected to Hostinger MySQL Database cleanly.</div>";

            // 1. Add user_id column to listings table if missing
            try {
                $checkCol = $db->query("SHOW COLUMNS FROM `listings` LIKE 'user_id'")->fetch();
                if (!$checkCol) {
                    $db->exec("ALTER TABLE `listings` ADD COLUMN `user_id` INT DEFAULT NULL AFTER `id`, ADD KEY `idx_listing_user_id` (`user_id`);");
                    echo "<div class='alert alert-info py-2 mb-2'>✓ <strong>Success:</strong> Created missing <code>user_id</code> column in live <strong>listings</strong> table.</div>";
                } else {
                    echo "<div class='alert alert-secondary py-2 mb-2'>✓ <code>user_id</code> column already exists in <strong>listings</strong> table.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-warning py-2 mb-2'>Listings user_id note: " . htmlspecialchars($e->getMessage()) . "</div>";
            }

            // 2. Add reset token columns to users table
            try {
                $checkReset = $db->query("SHOW COLUMNS FROM `users` LIKE 'reset_token'")->fetch();
                if (!$checkReset) {
                    $db->exec("ALTER TABLE `users` ADD COLUMN `reset_token` VARCHAR(100) DEFAULT NULL, ADD COLUMN `reset_expiry` DATETIME DEFAULT NULL;");
                    echo "<div class='alert alert-info py-2 mb-2'>✓ <strong>Success:</strong> Created reset token columns in live <strong>users</strong> table.</div>";
                }
            } catch (Exception $e) {}

            // 3. Ensure claims table exists
            try {
                ensureClaimsTable();
                echo "<div class='alert alert-success py-2 mb-2'>✓ Verified <strong>claims</strong> table schema.</div>";
            } catch (Exception $e) {
                echo "<div class='alert alert-warning py-2 mb-2'>Claims table note: " . htmlspecialchars($e->getMessage()) . "</div>";
            }

            // 4. Auto-link listings and claims to account +91 8102930609 / Kumar Gaurav
            try {
                $uStmt = $db->query("SELECT * FROM users WHERE mobile LIKE '%8102930609%' LIMIT 1");
                $user = $uStmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $uid = intval($user['id']);
                    echo "<div class='p-3 bg-light border rounded-3 my-3'>";
                    echo "<h6 class='fw-bold mb-1 text-dark'>Matched Account: " . htmlspecialchars($user['full_name']) . "</h6>";
                    echo "<p class='small text-muted mb-0'>User ID: <strong>#{$uid}</strong> | Phone: <strong>" . htmlspecialchars($user['mobile']) . "</strong> | Email: " . htmlspecialchars($user['email'] ?? 'N/A') . "</p>";
                    echo "</div>";

                    // Update listings with matching phone number
                    $upListings = $db->exec("UPDATE listings SET user_id = {$uid} WHERE (user_id IS NULL OR user_id = 0) AND (mobile LIKE '%8102930609%')");
                    echo "<div class='alert alert-success py-2 mb-2'>✓ Linked <strong>{$upListings}</strong> directory listings directly to User ID #{$uid}.</div>";

                    // Update claims with matching phone number
                    $upClaims = $db->exec("UPDATE claims SET user_id = {$uid} WHERE (user_id IS NULL OR user_id = 0) AND (claimant_mobile LIKE '%8102930609%')");
                    echo "<div class='alert alert-success py-2 mb-2'>✓ Linked <strong>{$upClaims}</strong> business claims directly to User ID #{$uid}.</div>";

                    // Query final active counts
                    $totListings = $db->query("SELECT COUNT(*) FROM listings WHERE user_id = {$uid} OR mobile LIKE '%8102930609%'")->fetchColumn();
                    $totClaims = $db->query("SELECT COUNT(*) FROM claims WHERE user_id = {$uid} OR claimant_mobile LIKE '%8102930609%'")->fetchColumn();

                    echo "<div class='p-3 bg-primary text-white rounded-3 my-3 text-center shadow-sm'>";
                    echo "<h4 class='fw-bold mb-0'>🎉 Online Live Repair Successful!</h4>";
                    echo "<p class='small mb-0 mt-1 opacity-90'>Your account now has {$totListings} Directory Listings & {$totClaims} Claims linked.</p>";
                    echo "</div>";
                } else {
                    echo "<div class='alert alert-warning py-2 mb-2'>User account with mobile +91 8102930609 not found in online database.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger py-2 mb-2'>Linking error: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            ?>

            <div class="mt-4 text-center">
                <a href="dashboard.php" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm">
                    🚀 Open Online Dashboard Now
                </a>
            </div>
        </div>
    </div>
</body>
</html>

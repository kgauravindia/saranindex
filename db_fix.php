<?php
/**
 * Standalone Online Database Auto-Fix & Diagnostics Script
 * Saran Index - Digital Directory
 */

require_once __DIR__ . '/includes/functions.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Auto-Fix – Saran Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container" style="max-width: 650px;">
        <div class="card shadow border-0 rounded-4 p-4">
            <h3 class="fw-bold text-primary mb-3">🛠️ Saran Index DB Auto-Fix</h3>
            <p class="text-muted small">Diagnostic & Migration Tool for Online Database Sync</p>
            <hr>

            <?php
            $db = getDB();
            if (!$db) {
                echo "<div class='alert alert-danger'>❌ Database Connection Failed! Check config.php.</div>";
                exit;
            }

            echo "<div class='alert alert-success py-2'>✓ Connected to Database cleanly.</div>";

            // 1. Check and add user_id column to listings table
            try {
                $checkCol = $db->query("SHOW COLUMNS FROM `listings` LIKE 'user_id'")->fetch();
                if (!$checkCol) {
                    $db->exec("ALTER TABLE `listings` ADD COLUMN `user_id` INT DEFAULT NULL AFTER `id`, ADD KEY `idx_listing_user_id` (`user_id`);");
                    echo "<div class='alert alert-info py-2'>✓ Created missing <code>user_id</code> column in <strong>listings</strong> table.</div>";
                } else {
                    echo "<div class='alert alert-secondary py-2'>✓ <code>user_id</code> column already exists in <strong>listings</strong> table.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-warning py-2'>Note on user_id column: " . htmlspecialchars($e->getMessage()) . "</div>";
            }

            // 2. Ensure claims table exists
            try {
                ensureClaimsTable();
                echo "<div class='alert alert-success py-2'>✓ Verified <strong>claims</strong> table schema.</div>";
            } catch (Exception $e) {
                echo "<div class='alert alert-warning py-2'>Claims check: " . htmlspecialchars($e->getMessage()) . "</div>";
            }

            // 3. Auto-link listings and claims for +91 8102930609 / Kumar Gaurav
            try {
                $uStmt = $db->query("SELECT * FROM users WHERE mobile LIKE '%8102930609%' LIMIT 1");
                $user = $uStmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $uid = intval($user['id']);
                    echo "<div class='p-3 bg-white border rounded mb-3'>";
                    echo "<h6 class='fw-bold mb-1'>Target Account: " . htmlspecialchars($user['full_name']) . "</h6>";
                    echo "<p class='small text-muted mb-0'>User ID: #{$uid} | Mobile: " . htmlspecialchars($user['mobile']) . "</p>";
                    echo "</div>";

                    // Update listings matching phone number
                    $upListings = $db->exec("UPDATE listings SET user_id = {$uid} WHERE (user_id IS NULL OR user_id = 0) AND (mobile LIKE '%8102930609%')");
                    echo "<div class='alert alert-success py-2'>✓ Linked <strong>{$upListings}</strong> directory listings to User ID #{$uid}.</div>";

                    // Update claims matching phone number
                    $upClaims = $db->exec("UPDATE claims SET user_id = {$uid} WHERE (user_id IS NULL OR user_id = 0) AND (claimant_mobile LIKE '%8102930609%')");
                    echo "<div class='alert alert-success py-2'>✓ Linked <strong>{$upClaims}</strong> claims to User ID #{$uid}.</div>";

                    // Total linked listings count
                    $totListings = $db->query("SELECT COUNT(*) FROM listings WHERE user_id = {$uid} OR mobile LIKE '%8102930609%'")->fetchColumn();
                    $totClaims = $db->query("SELECT COUNT(*) FROM claims WHERE user_id = {$uid} OR claimant_mobile LIKE '%8102930609%'")->fetchColumn();

                    echo "<div class='p-3 bg-primary text-white rounded-3 my-3 text-center'>";
                    echo "<h4 class='fw-bold mb-0'>Status: {$totListings} Listings & {$totClaims} Claims Active!</h4>";
                    echo "</div>";
                } else {
                    echo "<div class='alert alert-warning'>User +91 8102930609 not found in database.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger'>Error linking listings: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            ?>

            <div class="mt-4 text-center">
                <a href="dashboard.php" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                    🚀 Open Dashboard Now
                </a>
            </div>
        </div>
    </div>
</body>
</html>

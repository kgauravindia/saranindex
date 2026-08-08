<?php
// Dynamic XML Sitemap Generator for SaranIndex.com
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

header("Content-Type: application/xml; charset=utf-8");

$baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/' : 'https://saranindex.com/';

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

function addSitemapUrl($loc, $lastmod = null, $changefreq = 'weekly', $priority = '0.7') {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
    if ($lastmod) {
        $date = date('Y-m-d', strtotime($lastmod));
        echo "    <lastmod>{$date}</lastmod>\n";
    }
    echo "    <changefreq>{$changefreq}</changefreq>\n";
    echo "    <priority>{$priority}</priority>\n";
    echo "  </url>\n";
}

$today = date('Y-m-d');

// ─────────────────────────────────────────────
// 1. Static Core Pages (English & Hindi)
// ─────────────────────────────────────────────
$staticPages = [
    // English
    ''                  => ['freq' => 'daily',   'prio' => '1.0'],
    'search'            => ['freq' => 'daily',   'prio' => '0.9'],
    'blocks'            => ['freq' => 'weekly',  'prio' => '0.9'],
    'panchayats'        => ['freq' => 'weekly',  'prio' => '0.9'],
    'villages'          => ['freq' => 'daily',   'prio' => '0.8'],
    'categories'        => ['freq' => 'weekly',  'prio' => '0.8'],
    'category/politics' => ['freq' => 'daily',   'prio' => '0.9'],
    'emergency'         => ['freq' => 'monthly', 'prio' => '0.8'],
    'university'        => ['freq' => 'monthly', 'prio' => '0.7'],
    'add-listing'       => ['freq' => 'monthly', 'prio' => '0.7'],
    'pricing.php'       => ['freq' => 'monthly', 'prio' => '0.7'],
    'login.php'         => ['freq' => 'monthly', 'prio' => '0.5'],
    'register.php'      => ['freq' => 'monthly', 'prio' => '0.5'],
    'sources'           => ['freq' => 'monthly', 'prio' => '0.6'],
    'about'             => ['freq' => 'monthly', 'prio' => '0.5'],
    'contact'           => ['freq' => 'monthly', 'prio' => '0.5'],
    'privacy-policy'    => ['freq' => 'yearly',  'prio' => '0.3'],
    'terms'             => ['freq' => 'yearly',  'prio' => '0.3'],
    'refund-policy'     => ['freq' => 'yearly',  'prio' => '0.3'],

    // Hindi equivalents
    'hindi/'                  => ['freq' => 'daily',   'prio' => '1.0'],
    'hindi/search'            => ['freq' => 'daily',   'prio' => '0.9'],
    'hindi/blocks'            => ['freq' => 'weekly',  'prio' => '0.8'],
    'hindi/panchayats'        => ['freq' => 'weekly',  'prio' => '0.8'],
    'hindi/villages'          => ['freq' => 'daily',   'prio' => '0.8'],
    'hindi/categories'        => ['freq' => 'weekly',  'prio' => '0.8'],
    'hindi/category/politics' => ['freq' => 'daily',   'prio' => '0.9'],
    'hindi/emergency'         => ['freq' => 'monthly', 'prio' => '0.7'],
    'hindi/add-listing'       => ['freq' => 'monthly', 'prio' => '0.6'],
];

foreach ($staticPages as $path => $meta) {
    addSitemapUrl($baseUrl . $path, $today, $meta['freq'], $meta['prio']);
}

$db = getDB();

if ($db) {

    // ─────────────────────────────────────────────
    // 2. Blocks (English + Hindi)
    // ─────────────────────────────────────────────
    try {
        $stmt = $db->query("SELECT slug FROM blocks WHERE slug IS NOT NULL AND slug != '' ORDER BY name ASC");
        while ($row = $stmt->fetch()) {
            addSitemapUrl($baseUrl . 'blocks/' . rawurlencode($row['slug']), $today, 'weekly', '0.9');
            addSitemapUrl($baseUrl . 'hindi/blocks/' . rawurlencode($row['slug']), $today, 'weekly', '0.8');
        }
    } catch (Exception $e) {}

    // ─────────────────────────────────────────────
    // 3. Panchayats (English + Hindi)
    // ─────────────────────────────────────────────
    try {
        $stmt = $db->query("SELECT slug FROM panchayats WHERE slug IS NOT NULL AND slug != ''");
        while ($row = $stmt->fetch()) {
            addSitemapUrl($baseUrl . 'panchayat/' . rawurlencode($row['slug']), $today, 'weekly', '0.7');
            addSitemapUrl($baseUrl . 'hindi/panchayat/' . rawurlencode($row['slug']), $today, 'weekly', '0.7');
        }
    } catch (Exception $e) {}

    // ─────────────────────────────────────────────
    // 4. Villages
    // ─────────────────────────────────────────────
    try {
        $stmt = $db->query("SELECT name, town_village_code FROM census WHERE level = 'VILLAGE' AND name IS NOT NULL AND name != '' ORDER BY name ASC");
        while ($row = $stmt->fetch()) {
            $vSlug = getVillageUniqueSlug($row['name'], $row['town_village_code']);
            addSitemapUrl($baseUrl . 'villages/' . rawurlencode($vSlug), $today, 'weekly', '0.7');
            addSitemapUrl($baseUrl . 'hindi/villages/' . rawurlencode($vSlug), $today, 'weekly', '0.7');
        }
    } catch (Exception $e) {}

    // ─────────────────────────────────────────────
    // 5. Pincodes
    // ─────────────────────────────────────────────
    try {
        $stmt = $db->query("SELECT DISTINCT pincode FROM listings WHERE pincode IS NOT NULL AND pincode != '' AND status = 'ACTIVE' ORDER BY pincode ASC");
        while ($row = $stmt->fetch()) {
            addSitemapUrl($baseUrl . 'pincode/' . rawurlencode($row['pincode']), $today, 'weekly', '0.6');
        }
    } catch (Exception $e) {}

    // ─────────────────────────────────────────────
    // 6. Categories (English + Hindi)
    // ─────────────────────────────────────────────
    try {
        $stmt = $db->query("SELECT slug FROM categories WHERE status='ACTIVE' AND slug IS NOT NULL AND slug != '' ORDER BY name ASC");
        while ($row = $stmt->fetch()) {
            addSitemapUrl($baseUrl . 'category/' . rawurlencode($row['slug']), $today, 'weekly', '0.8');
            addSitemapUrl($baseUrl . 'hindi/category/' . rawurlencode($row['slug']), $today, 'weekly', '0.8');
        }
    } catch (Exception $e) {}

    // ─────────────────────────────────────────────
    // 7. Subcategories (English + Hindi)
    // ─────────────────────────────────────────────
    try {
        $stmt = $db->query("
            SELECT s.slug AS sub_slug, c.slug AS cat_slug
            FROM subcategories s
            JOIN categories c ON s.category_id = c.id
            WHERE c.status = 'ACTIVE'
              AND s.slug IS NOT NULL AND s.slug != ''
              AND c.slug IS NOT NULL AND c.slug != ''
            ORDER BY c.name ASC, s.name ASC
        ");
        while ($row = $stmt->fetch()) {
            addSitemapUrl($baseUrl . 'category/' . rawurlencode($row['cat_slug']) . '/' . rawurlencode($row['sub_slug']), $today, 'weekly', '0.7');
            addSitemapUrl($baseUrl . 'hindi/category/' . rawurlencode($row['cat_slug']) . '/' . rawurlencode($row['sub_slug']), $today, 'weekly', '0.7');
        }
    } catch (Exception $e) {}

    // ─────────────────────────────────────────────
    // 8. Active Listings (Profile Pages) — highest priority
    // ─────────────────────────────────────────────
    try {
        $stmt = $db->query("
            SELECT slug, updated_at, created_at
            FROM listings
            WHERE status = 'ACTIVE'
              AND slug IS NOT NULL AND slug != ''
            ORDER BY updated_at DESC
        ");
        while ($row = $stmt->fetch()) {
            $mod = !empty($row['updated_at']) ? $row['updated_at'] : $row['created_at'];
            addSitemapUrl($baseUrl . 'profile/' . rawurlencode($row['slug']), $mod, 'weekly', '0.9');
        }
    } catch (Exception $e) {}

    // ─────────────────────────────────────────────
    // 9. User Public Profiles (visible profiles)
    // ─────────────────────────────────────────────
    try {
        $stmt = $db->query("
            SELECT username_handle, updated_at, created_at
            FROM users
            WHERE status = 'ACTIVE'
              AND profile_visibility = 'PUBLIC'
              AND username_handle IS NOT NULL AND username_handle != ''
            ORDER BY updated_at DESC
        ");
        while ($row = $stmt->fetch()) {
            $mod = !empty($row['updated_at']) ? $row['updated_at'] : $row['created_at'];
            addSitemapUrl($baseUrl . 'u/' . rawurlencode($row['username_handle']), $mod, 'weekly', '0.7');
        }
    } catch (Exception $e) {}
}

echo '</urlset>';

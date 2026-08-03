<?php
// Dynamic XML Sitemap Generator for SaranIndex.com
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

header("Content-Type: application/xml; charset=utf-8");

$baseUrl = defined('BASE_URL') ? BASE_URL : 'https://saranindex.com/';

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

// Static Core Pages (English & Hindi)
$staticPages = [
    ''               => ['freq' => 'daily',   'prio' => '1.0'],
    'hindi/'         => ['freq' => 'daily',   'prio' => '1.0'],
    'blocks'         => ['freq' => 'weekly',  'prio' => '0.9'],
    'panchayats'     => ['freq' => 'weekly',  'prio' => '0.9'],
    'villages'       => ['freq' => 'daily',   'prio' => '0.9'],
    'categories'     => ['freq' => 'weekly',  'prio' => '0.8'],
    'emergency'      => ['freq' => 'monthly', 'prio' => '0.8'],
    'university'     => ['freq' => 'monthly', 'prio' => '0.7'],
    'sources'        => ['freq' => 'monthly', 'prio' => '0.6'],
    'about'          => ['freq' => 'monthly', 'prio' => '0.5'],
    'contact'        => ['freq' => 'monthly', 'prio' => '0.5'],
    'privacy-policy' => ['freq' => 'yearly',  'prio' => '0.3'],
    'terms'          => ['freq' => 'yearly',  'prio' => '0.3'],
    'refund-policy'  => ['freq' => 'yearly',  'prio' => '0.3'],
];

foreach ($staticPages as $path => $meta) {
    addSitemapUrl($baseUrl . $path, $today, $meta['freq'], $meta['prio']);
}

$db = getDB();

if ($db) {
    // 1. Blocks
    try {
        $stmt = $db->query("SELECT slug FROM blocks WHERE slug IS NOT NULL AND slug != ''");
        while ($row = $stmt->fetch()) {
            addSitemapUrl($baseUrl . 'blocks/' . rawurlencode($row['slug']), $today, 'weekly', '0.8');
            addSitemapUrl($baseUrl . 'hindi/blocks/' . rawurlencode($row['slug']), $today, 'weekly', '0.8');
        }
    } catch (Exception $e) {}

    // 2. Panchayats
    try {
        $stmt = $db->query("SELECT slug FROM panchayats WHERE slug IS NOT NULL AND slug != ''");
        while ($row = $stmt->fetch()) {
            addSitemapUrl($baseUrl . 'panchayat/' . rawurlencode($row['slug']), $today, 'weekly', '0.7');
            addSitemapUrl($baseUrl . 'hindi/panchayat/' . rawurlencode($row['slug']), $today, 'weekly', '0.7');
        }
    } catch (Exception $e) {}

    // 3. Villages
    try {
        $stmt = $db->query("SELECT slug, town_village_code FROM villages WHERE (slug IS NOT NULL AND slug != '') OR (town_village_code IS NOT NULL AND town_village_code != '')");
        while ($row = $stmt->fetch()) {
            $vSlug = !empty($row['slug']) ? $row['slug'] : $row['town_village_code'];
            addSitemapUrl($baseUrl . 'villages/' . rawurlencode($vSlug), $today, 'weekly', '0.7');
            addSitemapUrl($baseUrl . 'hindi/villages/' . rawurlencode($vSlug), $today, 'weekly', '0.7');
        }
    } catch (Exception $e) {}

    // 4. Categories
    try {
        $stmt = $db->query("SELECT slug FROM categories WHERE status='ACTIVE'");
        while ($row = $stmt->fetch()) {
            addSitemapUrl($baseUrl . 'category/' . rawurlencode($row['slug']), $today, 'weekly', '0.8');
        }
    } catch (Exception $e) {}

    // 5. Subcategories
    try {
        $stmt = $db->query("SELECT s.slug as sub_slug, c.slug as cat_slug FROM subcategories s JOIN categories c ON s.category_id = c.id WHERE c.status='ACTIVE'");
        while ($row = $stmt->fetch()) {
            addSitemapUrl($baseUrl . 'category/' . rawurlencode($row['cat_slug']) . '/' . rawurlencode($row['sub_slug']), $today, 'weekly', '0.7');
        }
    } catch (Exception $e) {}

    // 6. Active Listings
    try {
        $stmt = $db->query("SELECT slug, updated_at, created_at FROM listings WHERE status='ACTIVE' AND slug IS NOT NULL AND slug != ''");
        while ($row = $stmt->fetch()) {
            $mod = !empty($row['updated_at']) ? $row['updated_at'] : $row['created_at'];
            addSitemapUrl($baseUrl . 'listing/' . rawurlencode($row['slug']), $mod, 'daily', '0.9');
        }
    } catch (Exception $e) {}
}

echo '</urlset>';

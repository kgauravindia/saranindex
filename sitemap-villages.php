<?php
// Dedicated Village XML Sitemap Generator for SaranIndex.com
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

header("Content-Type: application/xml; charset=utf-8");

$baseUrl = defined('BASE_URL') ? BASE_URL : 'https://saranindex.com/';

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

function addVillageSitemapUrl($loc, $lastmod = null, $changefreq = 'weekly', $priority = '0.7') {
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
$db = getDB();

if ($db) {
    try {
        // Query all 1800+ census villages
        $stmt = $db->query("SELECT name, town_village_code FROM census WHERE level = 'VILLAGE' AND name IS NOT NULL AND name != '' ORDER BY name ASC");
        while ($row = $stmt->fetch()) {
            $vSlug = getVillageUniqueSlug($row['name'], $row['town_village_code']);
            // English Village URL: villages/abdul-hai-234535
            addVillageSitemapUrl($baseUrl . 'villages/' . rawurlencode($vSlug), $today, 'weekly', '0.7');
            // Hindi Village URL: hindi/villages/abdul-hai-234535
            addVillageSitemapUrl($baseUrl . 'hindi/villages/' . rawurlencode($vSlug), $today, 'weekly', '0.7');
        }
    } catch (Exception $e) {
        error_log("Village sitemap generation error: " . $e->getMessage());
    }
}

echo '</urlset>';

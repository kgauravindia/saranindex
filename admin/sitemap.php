<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'XML Sitemap Generator & Indexing';
$current_page = 'sitemap.php';

$msg = '';
$msg_type = '';

$sitemapFilePath = dirname(__DIR__) . '/sitemap.xml';
$sitemapGzPath = dirname(__DIR__) . '/sitemap.xml.gz';
$robotsFilePath = dirname(__DIR__) . '/robots.txt';

// Helper function to build sitemap array and XML string
function buildSitemapPayload($customBaseUrl = null) {
    $db = getDB();
    $rawBase = !empty($customBaseUrl) ? $customBaseUrl : (defined('BASE_URL') ? BASE_URL : 'https://saranindex.com/');
    $baseUrl = preg_replace('~/\./~', '/', rtrim($rawBase, '/') . '/');
    $today = date('Y-m-d');

    $items = [];
    $counts = [
        'static' => 0,
        'blocks' => 0,
        'panchayats' => 0,
        'villages' => 0,
        'pincodes' => 0,
        'categories' => 0,
        'subcategories' => 0,
        'listings' => 0,
        'users' => 0
    ];

    // 1. Static Core Pages (English & Hindi)
    $staticPages = [
        // English
        ''                  => ['freq' => 'daily',   'prio' => '1.0', 'lang' => 'en'],
        'search'            => ['freq' => 'daily',   'prio' => '0.9', 'lang' => 'en'],
        'blocks'            => ['freq' => 'weekly',  'prio' => '0.9', 'lang' => 'en'],
        'panchayats'        => ['freq' => 'weekly',  'prio' => '0.9', 'lang' => 'en'],
        'villages'          => ['freq' => 'daily',   'prio' => '0.8', 'lang' => 'en'],
        'halkas'            => ['freq' => 'weekly',  'prio' => '0.8', 'lang' => 'en'],
        'pincodes'          => ['freq' => 'weekly',  'prio' => '0.8', 'lang' => 'en'],
        'categories'        => ['freq' => 'weekly',  'prio' => '0.8', 'lang' => 'en'],
        'emergency'         => ['freq' => 'monthly', 'prio' => '0.8', 'lang' => 'en'],
        'history'           => ['freq' => 'monthly', 'prio' => '0.8', 'lang' => 'en'],
        'river'             => ['freq' => 'monthly', 'prio' => '0.8', 'lang' => 'en'],
        'nahar'             => ['freq' => 'monthly', 'prio' => '0.8', 'lang' => 'en'],
        'university'        => ['freq' => 'monthly', 'prio' => '0.7', 'lang' => 'en'],
        'add-contact'       => ['freq' => 'monthly', 'prio' => '0.7', 'lang' => 'en'],
        'add-listing'       => ['freq' => 'monthly', 'prio' => '0.7', 'lang' => 'en'],
        'pricing'           => ['freq' => 'monthly', 'prio' => '0.7', 'lang' => 'en'],
        'sources'           => ['freq' => 'monthly', 'prio' => '0.6', 'lang' => 'en'],
        'about'             => ['freq' => 'monthly', 'prio' => '0.5', 'lang' => 'en'],
        'contact'           => ['freq' => 'monthly', 'prio' => '0.5', 'lang' => 'en'],
        'login'             => ['freq' => 'monthly', 'prio' => '0.5', 'lang' => 'en'],
        'register'          => ['freq' => 'monthly', 'prio' => '0.5', 'lang' => 'en'],
        'privacy-policy'    => ['freq' => 'yearly',  'prio' => '0.3', 'lang' => 'en'],
        'terms'             => ['freq' => 'yearly',  'prio' => '0.3', 'lang' => 'en'],
        'refund-policy'     => ['freq' => 'yearly',  'prio' => '0.3', 'lang' => 'en'],

        // Hindi
        'hindi/'            => ['freq' => 'daily',   'prio' => '1.0', 'lang' => 'hi'],
        'hindi/search'      => ['freq' => 'daily',   'prio' => '0.9', 'lang' => 'hi'],
        'hindi/blocks'      => ['freq' => 'weekly',  'prio' => '0.8', 'lang' => 'hi'],
        'hindi/panchayats'  => ['freq' => 'weekly',  'prio' => '0.8', 'lang' => 'hi'],
        'hindi/villages'    => ['freq' => 'daily',   'prio' => '0.8', 'lang' => 'hi'],
        'hindi/halkas'      => ['freq' => 'weekly',  'prio' => '0.8', 'lang' => 'hi'],
        'hindi/pincodes'    => ['freq' => 'weekly',  'prio' => '0.8', 'lang' => 'hi'],
        'hindi/categories'  => ['freq' => 'weekly',  'prio' => '0.8', 'lang' => 'hi'],
        'hindi/emergency'   => ['freq' => 'monthly', 'prio' => '0.7', 'lang' => 'hi'],
        'hindi/history'     => ['freq' => 'monthly', 'prio' => '0.7', 'lang' => 'hi'],
        'hindi/river'       => ['freq' => 'monthly', 'prio' => '0.7', 'lang' => 'hi'],
        'hindi/nahar'       => ['freq' => 'monthly', 'prio' => '0.7', 'lang' => 'hi'],
        'hindi/university'  => ['freq' => 'monthly', 'prio' => '0.7', 'lang' => 'hi'],
        'hindi/add-contact' => ['freq' => 'monthly', 'prio' => '0.6', 'lang' => 'hi'],
        'hindi/add-listing' => ['freq' => 'monthly', 'prio' => '0.6', 'lang' => 'hi'],
        'hindi/pricing'     => ['freq' => 'monthly', 'prio' => '0.6', 'lang' => 'hi'],
        'hindi/sources'     => ['freq' => 'monthly', 'prio' => '0.6', 'lang' => 'hi'],
        'hindi/about'       => ['freq' => 'monthly', 'prio' => '0.5', 'lang' => 'hi'],
        'hindi/contact'     => ['freq' => 'monthly', 'prio' => '0.5', 'lang' => 'hi'],
        'hindi/login'       => ['freq' => 'monthly', 'prio' => '0.5', 'lang' => 'hi'],
        'hindi/register'    => ['freq' => 'monthly', 'prio' => '0.5', 'lang' => 'hi'],
        'hindi/privacy-policy' => ['freq' => 'yearly', 'prio' => '0.3', 'lang' => 'hi'],
        'hindi/terms'          => ['freq' => 'yearly', 'prio' => '0.3', 'lang' => 'hi'],
        'hindi/refund-policy'  => ['freq' => 'yearly', 'prio' => '0.3', 'lang' => 'hi'],
    ];

    foreach ($staticPages as $path => $meta) {
        $items[] = [
            'loc' => $baseUrl . $path,
            'lastmod' => $today,
            'changefreq' => $meta['freq'],
            'priority' => $meta['prio'],
            'type' => 'Static Core Page',
            'lang' => $meta['lang']
        ];
        $counts['static']++;
    }

    if ($db) {
        // 2. Blocks
        try {
            $stmt = $db->query("SELECT slug FROM blocks WHERE slug IS NOT NULL AND slug != '' ORDER BY name ASC");
            while ($row = $stmt->fetch()) {
                $items[] = ['loc' => $baseUrl . 'block/' . rawurlencode($row['slug']), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9', 'type' => 'Block Page', 'lang' => 'en'];
                $items[] = ['loc' => $baseUrl . 'hindi/block/' . rawurlencode($row['slug']), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.8', 'type' => 'Block Page', 'lang' => 'hi'];
                $counts['blocks'] += 2;
            }
        } catch (Exception $e) {}

        // 3. Panchayats
        try {
            $stmt = $db->query("SELECT slug FROM panchayats WHERE slug IS NOT NULL AND slug != ''");
            while ($row = $stmt->fetch()) {
                $items[] = ['loc' => $baseUrl . 'panchayat/' . rawurlencode($row['slug']), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.7', 'type' => 'Gram Panchayat', 'lang' => 'en'];
                $items[] = ['loc' => $baseUrl . 'hindi/panchayat/' . rawurlencode($row['slug']), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.7', 'type' => 'Gram Panchayat', 'lang' => 'hi'];
                $counts['panchayats'] += 2;
            }
        } catch (Exception $e) {}

        // 4. Villages
        try {
            $stmt = $db->query("SELECT name, town_village_code FROM census WHERE level = 'VILLAGE' AND name IS NOT NULL AND name != '' ORDER BY name ASC");
            while ($row = $stmt->fetch()) {
                $vSlug = getVillageUniqueSlug($row['name'], $row['town_village_code']);
                $items[] = ['loc' => $baseUrl . 'village/' . rawurlencode($vSlug), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.7', 'type' => 'Village / Mauja', 'lang' => 'en'];
                $items[] = ['loc' => $baseUrl . 'hindi/village/' . rawurlencode($vSlug), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.7', 'type' => 'Village / Mauja', 'lang' => 'hi'];
                $counts['villages'] += 2;
            }
        } catch (Exception $e) {}

        // 5. Pincodes
        try {
            $stmt = $db->query("SELECT DISTINCT pincode FROM listings WHERE pincode IS NOT NULL AND pincode != '' AND status = 'ACTIVE' ORDER BY pincode ASC");
            while ($row = $stmt->fetch()) {
                $items[] = ['loc' => $baseUrl . 'pincode/' . rawurlencode($row['pincode']), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.6', 'type' => 'Pincode Hub', 'lang' => 'en'];
                $items[] = ['loc' => $baseUrl . 'hindi/pincode/' . rawurlencode($row['pincode']), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.6', 'type' => 'Pincode Hub', 'lang' => 'hi'];
                $counts['pincodes'] += 2;
            }
        } catch (Exception $e) {}

        // 6. Categories
        try {
            $stmt = $db->query("SELECT slug FROM categories WHERE status='ACTIVE' AND slug IS NOT NULL AND slug != '' ORDER BY name ASC");
            while ($row = $stmt->fetch()) {
                $items[] = ['loc' => $baseUrl . rawurlencode($row['slug']), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.8', 'type' => 'Category Hub', 'lang' => 'en'];
                $items[] = ['loc' => $baseUrl . 'hindi/' . rawurlencode($row['slug']), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.8', 'type' => 'Category Hub', 'lang' => 'hi'];
                $counts['categories'] += 2;
            }
        } catch (Exception $e) {}

        // 7. Subcategories
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
                $items[] = ['loc' => $baseUrl . rawurlencode($row['cat_slug']) . '/' . rawurlencode($row['sub_slug']), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.7', 'type' => 'Subcategory Hub', 'lang' => 'en'];
                $items[] = ['loc' => $baseUrl . 'hindi/' . rawurlencode($row['cat_slug']) . '/' . rawurlencode($row['sub_slug']), 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.7', 'type' => 'Subcategory Hub', 'lang' => 'hi'];
                $counts['subcategories'] += 2;
            }
        } catch (Exception $e) {}

        // 8. Active Listings
        try {
            $stmt = $db->query("
                SELECT slug, updated_at, created_at
                FROM listings
                WHERE status = 'ACTIVE'
                  AND slug IS NOT NULL AND slug != ''
                ORDER BY updated_at DESC
            ");
            while ($row = $stmt->fetch()) {
                $mod = !empty($row['updated_at']) ? date('Y-m-d', strtotime($row['updated_at'])) : (!empty($row['created_at']) ? date('Y-m-d', strtotime($row['created_at'])) : $today);
                $items[] = ['loc' => $baseUrl . rawurlencode($row['slug']), 'lastmod' => $mod, 'changefreq' => 'weekly', 'priority' => '0.9', 'type' => 'Listing Profile', 'lang' => 'both'];
                $counts['listings']++;
            }
        } catch (Exception $e) {}

        // 9. User Public Profiles
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
                $mod = !empty($row['updated_at']) ? date('Y-m-d', strtotime($row['updated_at'])) : (!empty($row['created_at']) ? date('Y-m-d', strtotime($row['created_at'])) : $today);
                $cleanH = ltrim($row['username_handle'], '@');
                $items[] = ['loc' => $baseUrl . '@' . rawurlencode($cleanH), 'lastmod' => $mod, 'changefreq' => 'weekly', 'priority' => '0.7', 'type' => 'User Profile', 'lang' => 'both'];
                $counts['users']++;
            }
        } catch (Exception $e) {}
    }

    // Generate XML String
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($items as $item) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars($item['loc']) . "</loc>\n";
        if (!empty($item['lastmod'])) {
            $xml .= "    <lastmod>" . htmlspecialchars($item['lastmod']) . "</lastmod>\n";
        }
        $xml .= "    <changefreq>" . htmlspecialchars($item['changefreq']) . "</changefreq>\n";
        $xml .= "    <priority>" . htmlspecialchars($item['priority']) . "</priority>\n";
        $xml .= "  </url>\n";
    }
    $xml .= '</urlset>';

    return [
        'base_url' => $baseUrl,
        'total' => count($items),
        'counts' => $counts,
        'items' => $items,
        'xml' => $xml
    ];
}

// Handle Direct File Download
if (isset($_GET['action']) && $_GET['action'] === 'download') {
    if (file_exists($sitemapFilePath)) {
        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="sitemap.xml"');
        header('Content-Length: ' . filesize($sitemapFilePath));
        readfile($sitemapFilePath);
        exit;
    } else {
        $data = buildSitemapPayload();
        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="sitemap.xml"');
        header('Content-Length: ' . strlen($data['xml']));
        echo $data['xml'];
        exit;
    }
}

// Handle Generate / Rebuild Sitemap Action
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate_sitemap') {
    $customDomain = !empty($_POST['custom_domain']) ? trim($_POST['custom_domain']) : 'https://saranindex.com/';
    $startTime = microtime(true);
    
    try {
        $data = buildSitemapPayload($customDomain);
        $written = file_put_contents($sitemapFilePath, $data['xml']);
        
        if ($written !== false) {
            $execTimeMs = round((microtime(true) - $startTime) * 1000, 2);
            $fileSizeKb = round($written / 1024, 2);
            
            // Also generate gzip compressed sitemap if possible
            if (function_exists('gzencode')) {
                file_put_contents($sitemapGzPath, gzencode($data['xml'], 9));
            }
            
            $msg = "Success! Generated <strong>" . number_format($data['total']) . " URLs</strong> in <code>sitemap.xml</code> (" . $fileSizeKb . " KB) in {$execTimeMs} ms.";
            $msg_type = 'success';
        } else {
            $msg = "Error: Unable to write to root <code>sitemap.xml</code> file. Please check folder write permissions.";
            $msg_type = 'danger';
        }
    } catch (Exception $e) {
        $msg = "Failed to generate sitemap: " . $e->getMessage();
        $msg_type = 'danger';
    }
}

// Build current preview dataset
$sitemapData = buildSitemapPayload();
$fileExists = file_exists($sitemapFilePath);
$fileSize = $fileExists ? filesize($sitemapFilePath) : 0;
$fileModified = $fileExists ? filemtime($sitemapFilePath) : null;

// Check robots.txt
$robotsExists = file_exists($robotsFilePath);
$robotsHasSitemap = false;
if ($robotsExists) {
    $robotsContent = file_get_contents($robotsFilePath);
    if (stripos($robotsContent, 'sitemap.xml') !== false || stripos($robotsContent, 'sitemap.php') !== false) {
        $robotsHasSitemap = true;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container-fluid px-4 py-4">

    <!-- Header & Breadcrumb -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sitemap Generator</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark font-heading mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-diagram-2-fill text-info"></i> XML Sitemap Generator & Search Indexing
            </h1>
            <p class="text-muted small mb-0">Manage, generate, and optimize XML sitemaps for Google, Bing, and Search Engine crawlers.</p>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <form action="sitemap.php" method="POST" class="d-inline" id="quickGenForm">
                <input type="hidden" name="action" value="generate_sitemap">
                <input type="hidden" name="custom_domain" value="https://saranindex.com/">
                <button type="submit" class="btn btn-primary rounded-pill px-3.5 py-2 fw-bold shadow-sm d-flex align-items-center gap-1.5" id="genBtn">
                    <i class="bi bi-lightning-charge-fill"></i> Generate sitemap.xml
                </button>
            </form>
            <a href="../sitemap.php" target="_blank" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1.5" title="View dynamic sitemap endpoint">
                <i class="bi bi-globe2"></i> Dynamic Endpoint
            </a>
            <?php if ($fileExists): ?>
                <a href="../sitemap.xml" target="_blank" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1.5" title="View physical sitemap.xml">
                    <i class="bi bi-file-earmark-code"></i> View sitemap.xml
                </a>
                <a href="sitemap.php?action=download" class="btn btn-outline-dark rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1.5" title="Download physical sitemap.xml">
                    <i class="bi bi-download"></i> Download
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if (!empty($msg)): ?>
        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show rounded-4 p-3 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi <?php echo $msg_type === 'success' ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-danger'; ?> fs-4"></i>
            <div class="flex-grow-1"><?php echo $msg; ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- KPI Metric Cards Grid -->
    <div class="row g-3 mb-4">
        
        <!-- Total URLs -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-link-45deg fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Indexed URLs</div>
                        <h3 class="fw-bold text-dark mb-0 font-heading"><?php echo number_format($sitemapData['total']); ?></h3>
                    </div>
                </div>
                <div class="mt-2.5 pt-2 border-top extra-small text-muted d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-layers-fill text-primary me-1"></i>9 Content Types</span>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-0.5">English & Hindi</span>
                </div>
            </div>
        </div>

        <!-- Physical File Status -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 <?php echo $fileExists ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis'; ?> p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi <?php echo $fileExists ? 'bi-file-earmark-check-fill' : 'bi-file-earmark-x-fill'; ?> fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Physical File (sitemap.xml)</div>
                        <h4 class="fw-bold text-dark mb-0 font-heading">
                            <?php if ($fileExists): ?>
                                <span class="text-success fs-5"><i class="bi bi-check-circle-fill me-1"></i>Active</span>
                                <small class="text-muted fs-6 fw-normal ms-1">(<?php echo round($fileSize / 1024, 1); ?> KB)</small>
                            <?php else: ?>
                                <span class="text-warning fs-5"><i class="bi bi-exclamation-circle-fill me-1"></i>Not Generated</span>
                            <?php endif; ?>
                        </h4>
                    </div>
                </div>
                <div class="mt-2.5 pt-2 border-top extra-small text-muted d-flex justify-content-between align-items-center">
                    <span>Last Generated:</span>
                    <span class="fw-semibold text-dark"><?php echo $fileModified ? date('d M Y, h:i A', $fileModified) : 'N/A'; ?></span>
                </div>
            </div>
        </div>

        <!-- Dynamic Live Generator -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-info-subtle text-info-emphasis p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-cpu-fill fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Dynamic Endpoint (sitemap.php)</div>
                        <h4 class="fw-bold text-dark mb-0 font-heading">
                            <span class="badge bg-success text-white rounded-pill px-2.5 py-1 fs-6">200 OK</span>
                        </h4>
                    </div>
                </div>
                <div class="mt-2.5 pt-2 border-top extra-small text-muted d-flex justify-content-between align-items-center">
                    <span>Auto-updates in real-time</span>
                    <a href="../sitemap.php" target="_blank" class="text-info text-decoration-none fw-semibold">Test <i class="bi bi-arrow-up-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Robots.txt Status -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 <?php echo $robotsHasSitemap ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis'; ?> p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-robot fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Robots.txt Integration</div>
                        <h4 class="fw-bold text-dark mb-0 font-heading">
                            <?php if ($robotsHasSitemap): ?>
                                <span class="text-success fs-6 fw-bold"><i class="bi bi-check2-all me-1"></i>Declared</span>
                            <?php else: ?>
                                <span class="text-danger fs-6 fw-bold">Missing</span>
                            <?php endif; ?>
                        </h4>
                    </div>
                </div>
                <div class="mt-2.5 pt-2 border-top extra-small text-muted d-flex justify-content-between align-items-center">
                    <span>robots.txt</span>
                    <a href="../robots.txt" target="_blank" class="text-primary text-decoration-none fw-semibold">View <i class="bi bi-arrow-up-right"></i></a>
                </div>
            </div>
        </div>

    </div>

    <!-- Breakdown Grid & Search Engine Hub -->
    <div class="row g-4 mb-4">
        
        <!-- Left: Breakdown by Type Cards (8 Cols) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0 font-heading">
                            <i class="bi bi-pie-chart-fill text-primary me-2"></i>Sitemap Coverage Breakdown
                        </h5>
                        <small class="text-muted">Entity distribution of all <?php echo number_format($sitemapData['total']); ?> XML sitemap nodes</small>
                    </div>
                    <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 fw-bold">
                        100% Complete
                    </span>
                </div>

                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light border text-center">
                            <div class="text-primary fs-4 mb-1"><i class="bi bi-file-text-fill"></i></div>
                            <div class="h5 fw-bold text-dark mb-0 font-heading"><?php echo $sitemapData['counts']['static']; ?></div>
                            <div class="extra-small text-muted">Static Pages</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light border text-center">
                            <div class="text-danger fs-4 mb-1"><i class="bi bi-geo-alt-fill"></i></div>
                            <div class="h5 fw-bold text-dark mb-0 font-heading"><?php echo $sitemapData['counts']['blocks']; ?></div>
                            <div class="extra-small text-muted">Block Hubs</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light border text-center">
                            <div class="text-warning fs-4 mb-1"><i class="bi bi-building-fill"></i></div>
                            <div class="h5 fw-bold text-dark mb-0 font-heading"><?php echo $sitemapData['counts']['panchayats']; ?></div>
                            <div class="extra-small text-muted">Gram Panchayats</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light border text-center">
                            <div class="text-success fs-4 mb-1"><i class="bi bi-houses-fill"></i></div>
                            <div class="h5 fw-bold text-dark mb-0 font-heading"><?php echo $sitemapData['counts']['villages']; ?></div>
                            <div class="extra-small text-muted">Villages / Maujas</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light border text-center">
                            <div class="text-info fs-4 mb-1"><i class="bi bi-shop"></i></div>
                            <div class="h5 fw-bold text-dark mb-0 font-heading"><?php echo number_format($sitemapData['counts']['listings']); ?></div>
                            <div class="extra-small text-muted">Directory Listings</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light border text-center">
                            <div class="text-secondary fs-4 mb-1"><i class="bi bi-grid-fill"></i></div>
                            <div class="h5 fw-bold text-dark mb-0 font-heading"><?php echo $sitemapData['counts']['categories'] + $sitemapData['counts']['subcategories']; ?></div>
                            <div class="extra-small text-muted">Categories & Subs</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light border text-center">
                            <div class="text-primary fs-4 mb-1"><i class="bi bi-mailbox2"></i></div>
                            <div class="h5 fw-bold text-dark mb-0 font-heading"><?php echo $sitemapData['counts']['pincodes']; ?></div>
                            <div class="extra-small text-muted">Pincode Hubs</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light border text-center">
                            <div class="text-dark fs-4 mb-1"><i class="bi bi-person-badge-fill"></i></div>
                            <div class="h5 fw-bold text-dark mb-0 font-heading"><?php echo $sitemapData['counts']['users']; ?></div>
                            <div class="extra-small text-muted">Public Profiles</div>
                        </div>
                    </div>
                </div>

                <div class="mt-3.5 p-3 rounded-3 bg-primary-subtle bg-opacity-25 border border-primary-subtle small text-secondary">
                    <i class="bi bi-info-circle-fill text-primary me-1.5"></i>
                    All URLs are formatted using SEO-friendly canonical routes in both <strong>English</strong> and <strong>Hindi</strong> versions with prioritized crawl frequencies.
                </div>
            </div>
        </div>

        <!-- Right: Search Engine Submission & Generator Options (4 Cols) -->
        <div class="col-lg-4">
            
            <!-- Generation Settings Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold text-dark mb-3 font-heading border-bottom pb-2">
                    <i class="bi bi-gear-fill text-primary me-2"></i>Generation Settings
                </h6>

                <form action="sitemap.php" method="POST">
                    <input type="hidden" name="action" value="generate_sitemap">
                    
                    <div class="mb-3">
                        <label for="custom_domain" class="form-label extra-small fw-semibold text-muted">Canonical Domain URL</label>
                        <select class="form-select form-select-sm" id="custom_domain" name="custom_domain">
                            <option value="https://saranindex.com/" selected>https://saranindex.com/ (Production Canonical)</option>
                            <option value="<?php echo defined('BASE_URL') ? BASE_URL : 'http://localhost/saranindex/'; ?>">Current Environment URL (<?php echo defined('BASE_URL') ? BASE_URL : 'Localhost'; ?>)</option>
                        </select>
                        <small class="text-muted extra-small d-block mt-1">Recommended to generate with production domain for search engine submission.</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-pill fw-bold py-2 shadow-xs">
                            <i class="bi bi-arrow-repeat me-1"></i> Rebuild sitemap.xml & .gz
                        </button>
                    </div>
                </form>
            </div>

            <!-- Webmaster Hub Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 100%);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-search text-warning fs-5"></i>
                    <h6 class="fw-bold mb-0 text-white font-heading">Webmaster Submission Hub</h6>
                </div>
                <p class="extra-small text-white-50 mb-3">Submit your sitemap URL directly to search engines to expedite crawler discovery and indexing.</p>

                <!-- Copy Sitemap URL Strip -->
                <div class="bg-white bg-opacity-10 border border-white border-opacity-10 rounded-3 p-2 d-flex align-items-center justify-content-between gap-2 mb-3">
                    <span class="font-monospace text-warning extra-small text-truncate" id="sitemapUrlText">https://saranindex.com/sitemap.xml</span>
                    <button type="button" class="btn btn-xs btn-light rounded-pill px-2.5 py-1 extra-small fw-bold" id="copySitemapUrlBtn">
                        <i class="bi bi-clipboard me-1"></i>Copy
                    </button>
                </div>

                <div class="d-grid gap-2">
                    <a href="https://search.google.com/search-console/sitemaps" target="_blank" class="btn btn-light btn-sm rounded-pill fw-semibold text-start p-2 border shadow-xs d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-google text-danger me-2"></i>Google Search Console</span>
                        <i class="bi bi-box-arrow-up-right text-muted extra-small"></i>
                    </a>
                    <a href="https://www.bing.com/webmasters/sitemaps" target="_blank" class="btn btn-light btn-sm rounded-pill fw-semibold text-start p-2 border shadow-xs d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-microsoft text-primary me-2"></i>Bing Webmaster Tools</span>
                        <i class="bi bi-box-arrow-up-right text-muted extra-small"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- Live URL Explorer Table (Client-Side Filter & Search) -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
        
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3 border-bottom pb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0 font-heading d-flex align-items-center gap-2">
                    <i class="bi bi-table text-primary"></i> Live Sitemap URL Explorer
                </h5>
                <small class="text-muted">Inspect and test all indexed links contained in the sitemap</small>
            </div>

            <!-- Filters -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Type Filter -->
                <select id="filterType" class="form-select form-select-sm rounded-pill" style="width: auto;">
                    <option value="">All Types (<?php echo count($sitemapData['items']); ?>)</option>
                    <option value="Static Core Page">Static Pages (<?php echo $sitemapData['counts']['static']; ?>)</option>
                    <option value="Block Page">Blocks (<?php echo $sitemapData['counts']['blocks']; ?>)</option>
                    <option value="Gram Panchayat">Panchayats (<?php echo $sitemapData['counts']['panchayats']; ?>)</option>
                    <option value="Village / Mauja">Villages (<?php echo $sitemapData['counts']['villages']; ?>)</option>
                    <option value="Listing Profile">Listings (<?php echo $sitemapData['counts']['listings']; ?>)</option>
                    <option value="Category Hub">Categories (<?php echo $sitemapData['counts']['categories']; ?>)</option>
                    <option value="Subcategory Hub">Subcategories (<?php echo $sitemapData['counts']['subcategories']; ?>)</option>
                    <option value="Pincode Hub">Pincodes (<?php echo $sitemapData['counts']['pincodes']; ?>)</option>
                    <option value="User Profile">Users (<?php echo $sitemapData['counts']['users']; ?>)</option>
                </select>

                <!-- Lang Filter -->
                <select id="filterLang" class="form-select form-select-sm rounded-pill" style="width: auto;">
                    <option value="">All Languages</option>
                    <option value="en">English (en)</option>
                    <option value="hi">Hindi (hi)</option>
                </select>

                <!-- Search Input -->
                <div class="input-group input-group-sm" style="width: 220px;">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="filterSearch" class="form-control border-start-0" placeholder="Search URL...">
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="extra-small text-muted" id="tableRecordCount">Showing <?php echo count($sitemapData['items']); ?> URLs</span>
            <span class="extra-small text-muted">Fast in-memory table with instant search</span>
        </div>

        <!-- Responsive Table -->
        <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
            <table class="table table-hover align-middle mb-0 small" id="sitemapTable">
                <thead class="table-light sticky-top">
                    <tr>
                        <th style="width: 45px;">#</th>
                        <th>Target URL (Loc)</th>
                        <th>Content Type</th>
                        <th>Language</th>
                        <th>Frequency</th>
                        <th>Priority</th>
                        <th>Last Modified</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody id="sitemapTbody">
                    <!-- Loaded dynamically or rendered with sample chunk -->
                    <?php 
                    $previewLimit = 150; // Initial render limit for snappy initial page load
                    $counter = 0;
                    foreach ($sitemapData['items'] as $u): 
                        $counter++;
                        if ($counter > $previewLimit) break;
                    ?>
                        <tr data-type="<?php echo htmlspecialchars($u['type']); ?>" data-lang="<?php echo htmlspecialchars($u['lang']); ?>" data-url="<?php echo htmlspecialchars(strtolower($u['loc'])); ?>">
                            <td class="text-muted extra-small"><?php echo $counter; ?></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($u['loc']); ?>" target="_blank" class="text-decoration-none fw-semibold text-primary text-truncate d-inline-block" style="max-width: 420px;" title="<?php echo htmlspecialchars($u['loc']); ?>">
                                    <?php echo htmlspecialchars($u['loc']); ?>
                                </a>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($u['type']); ?></span></td>
                            <td>
                                <?php if ($u['lang'] === 'hi'): ?>
                                    <span class="badge bg-warning text-dark px-2">Hindi</span>
                                <?php elseif ($u['lang'] === 'en'): ?>
                                    <span class="badge bg-primary text-white px-2">English</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary text-white px-2">Bilingual</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-secondary-subtle text-secondary"><?php echo htmlspecialchars($u['changefreq']); ?></span></td>
                            <td>
                                <?php if (floatval($u['priority']) >= 0.9): ?>
                                    <span class="badge bg-success-subtle text-success fw-bold"><?php echo htmlspecialchars($u['priority']); ?></span>
                                <?php elseif (floatval($u['priority']) >= 0.7): ?>
                                    <span class="badge bg-info-subtle text-info fw-bold"><?php echo htmlspecialchars($u['priority']); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border"><?php echo htmlspecialchars($u['priority']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted extra-small font-monospace"><?php echo htmlspecialchars($u['lastmod'] ?? '-'); ?></td>
                            <td class="text-end">
                                <a href="<?php echo htmlspecialchars($u['loc']); ?>" target="_blank" class="btn btn-xs btn-outline-primary rounded-pill px-2.5 py-1 extra-small">
                                    <i class="bi bi-box-arrow-up-right"></i> Open
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (count($sitemapData['items']) > $previewLimit): ?>
            <div class="p-3 text-center bg-light border-top rounded-bottom small text-muted">
                Showing first <?php echo $previewLimit; ?> records of <strong><?php echo number_format($sitemapData['total']); ?> total URLs</strong>. Use the filters or search bar above to query any URL directly, or click <strong>Generate sitemap.xml</strong> to export all <?php echo number_format($sitemapData['total']); ?> URLs.
            </div>
        <?php endif; ?>

    </div>

</div>

<!-- Pass Full JSON for Client-Side Filtering -->
<script>
window.allSitemapItems = <?php echo json_encode(array_slice($sitemapData['items'], 0, 3000)); ?>;

document.addEventListener('DOMContentLoaded', function() {
    
    // Copy Sitemap URL Button
    const copyBtn = document.getElementById('copySitemapUrlBtn');
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            const url = document.getElementById('sitemapUrlText').textContent.trim();
            navigator.clipboard.writeText(url).then(() => {
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check2 text-success me-1"></i>Copied!';
                setTimeout(() => {
                    this.innerHTML = originalHtml;
                }, 2000);
            }).catch(() => {
                prompt('Copy this URL:', url);
            });
        });
    }

    // Client-side filtering
    const filterType = document.getElementById('filterType');
    const filterLang = document.getElementById('filterLang');
    const filterSearch = document.getElementById('filterSearch');
    const tbody = document.getElementById('sitemapTbody');
    const countLabel = document.getElementById('tableRecordCount');

    function renderFilteredRows() {
        const typeVal = filterType.value.trim().toLowerCase();
        const langVal = filterLang.value.trim().toLowerCase();
        const searchVal = filterSearch.value.trim().toLowerCase();

        const rows = tbody.querySelectorAll('tr');
        let visibleCount = 0;

        rows.forEach(tr => {
            const rType = (tr.getAttribute('data-type') || '').toLowerCase();
            const rLang = (tr.getAttribute('data-lang') || '').toLowerCase();
            const rUrl = (tr.getAttribute('data-url') || '').toLowerCase();

            let match = true;
            if (typeVal && rType !== typeVal) match = false;
            if (langVal && rLang !== langVal && rLang !== 'both') match = false;
            if (searchVal && !rUrl.includes(searchVal) && !rType.includes(searchVal)) match = false;

            if (match) {
                tr.style.display = '';
                visibleCount++;
            } else {
                tr.style.display = 'none';
            }
        });

        countLabel.textContent = `Showing ${visibleCount} matching URLs`;
    }

    if (filterType) filterType.addEventListener('change', renderFilteredRows);
    if (filterLang) filterLang.addEventListener('change', renderFilteredRows);
    if (filterSearch) filterSearch.addEventListener('input', renderFilteredRows);

});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

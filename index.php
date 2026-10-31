<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "Saran Index – Connecting Saran Digitally | Verified Directory of Saran District (Bihar)";
$meta_description = "The trusted, secure digital directory for Saran District (Chapra, Bihar). Discover 15,000+ verified listings across 20 Blocks, 318 Gram Panchayats, 1,800+ Villages, healthcare, schools, advocates, and governance.";

require_once __DIR__ . '/includes/header.php';

// Safe Data Fetching with Exception Safety
$district_stats = getDistrictFullStats() ?: [];
$total_listings = intval($district_stats['total_listings'] ?? 15161);
$total_blocks = intval($district_stats['total_blocks'] ?? 20);
$total_panchayats = intval($district_stats['total_panchayats'] ?? 318);
$total_villages = intval($district_stats['total_villages'] ?? 1876);
$total_reps = intval($district_stats['total_representatives'] ?? 8726);

$blocks = getBlocks();
$featured_listings = getListings('', '', '', 6, 0);
$recent_listings = getRecentListings(6);

// Top 8 Populated Categories for fast, visual discovery
$top_categories = [];
$db = getDB();
if ($db) {
    try {
        $stmt = $db->query("SELECT c.id, c.name, c.hindi_name, c.slug, c.icon, COUNT(l.id) as listing_count 
            FROM categories c 
            INNER JOIN listings l ON l.category_id = c.id AND l.status = 'ACTIVE'
            GROUP BY c.id 
            ORDER BY listing_count DESC 
            LIMIT 8");
        $top_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $top_categories = [];
    }
}
if (empty($top_categories)) {
    $top_categories = array_slice(getCategories(), 0, 8);
}
?>

<!-- Clean, Modern & Secure Hero Section -->
<section class="hero-wrapper position-relative text-center py-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 65%, #2563eb 100%);">
    <div class="container position-relative z-2 py-3 py-md-4">
        <div class="d-inline-flex align-items-center mb-3 hero-tag-pill">
            <i class="bi bi-patch-check-fill text-warning me-2 fs-6"></i>
            <span>Verified Public Directory • Saran District, Bihar</span>
        </div>

        <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
            Connecting Saran Digitally
        </h1>
        <p class="lead text-white-50 mx-auto mb-4" style="max-width: 720px; font-size: 1.15rem; color: #cbd5e1 !important; line-height: 1.6;">
            The trusted digital directory and civic portal for <strong>Saran District (Chapra)</strong>. Discover 15,000+ verified listings across all 20 blocks, 318 Gram Panchayats, and 1,800+ villages.
        </p>

        <!-- Prominent Unified Search Bar -->
        <div class="row justify-content-center mb-3">
            <div class="col-lg-9 col-md-11 position-relative">
                <form action="search" method="GET" class="search-card d-flex align-items-center gap-2 shadow-lg mx-auto" style="max-width: 840px;">
                    <button type="button" class="btn mic-btn flex-shrink-0" id="micButton" title="Voice Search (बोलकर खोजें)" aria-label="Voice Search">
                        <i class="bi bi-mic-fill fs-5"></i>
                    </button>
                    <input type="text" name="q" id="search_box" class="form-control search-input flex-grow-1" placeholder="Search businesses, doctors, schools, services, representatives..." maxlength="100" autocomplete="off" required>
                    
                    <select name="block" class="form-select border-0 bg-light rounded-pill px-3 fw-medium d-none d-md-block" style="max-width: 175px;" aria-label="Filter by Block">
                        <option value="">All 20 Blocks</option>
                        <?php foreach ($blocks as $blk): ?>
                            <option value="<?php echo sanitizeInput($blk['slug']); ?>"><?php echo sanitizeInput($blk['block_name']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn search-submit-btn flex-shrink-0" id="searchSubmitBtn">
                        <i class="bi bi-search me-1"></i>Search
                    </button>
                </form>
                
                <!-- Live Autocomplete Suggest Container -->
                <div id="autocomplete_results" class="position-absolute start-0 end-0 text-start z-3 px-3 mx-auto" style="max-width: 840px; display: none; top: 100%; margin-top: 6px;"></div>
            </div>
        </div>

        <!-- Popular Quick Search Suggestions -->
        <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 pt-2">
            <span class="text-white-50 small fw-semibold me-1"><i class="bi bi-lightning-charge-fill text-warning me-1"></i>Popular:</span>
            <a href="search?q=Doctor" class="hero-tag-pill"><i class="bi bi-heart-pulse text-danger me-1"></i> Doctors</a>
            <a href="search?q=School" class="hero-tag-pill"><i class="bi bi-mortarboard text-warning me-1"></i> Schools</a>
            <a href="panchayat" class="hero-tag-pill"><i class="bi bi-building text-info me-1"></i> 318 Panchayats</a>
            <a href="search?q=Ward+Member" class="hero-tag-pill"><i class="bi bi-people text-success me-1"></i> Ward Members & Panch</a>
            <a href="emergency" class="hero-tag-pill"><i class="bi bi-shield-exclamation text-danger me-1"></i> Emergency 24x7</a>
            <a href="category/legal" class="hero-tag-pill"><i class="bi bi-briefcase text-primary me-1"></i> Advocates</a>
            <a href="bus" class="hero-tag-pill"><i class="bi bi-bus-front text-info me-1"></i> Bus Timetable</a>
        </div>
    </div>
</section>

<!-- Sleek Master Statistics Strip (Light, Clean & Balanced) -->
<section class="py-4 bg-white border-bottom shadow-xs">
    <div class="container">
        <div class="row g-3 text-center align-items-stretch">
            <div class="col-6 col-md-4 col-lg">
                <div class="p-3 rounded-4 bg-light border h-100 hover-lift">
                    <div class="h3 fw-bold text-primary mb-1 font-heading"><?php echo number_format($total_listings); ?>+</div>
                    <div class="text-secondary small fw-semibold">Verified Listings</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="p-3 rounded-4 bg-light border h-100 hover-lift">
                    <div class="h3 fw-bold text-info mb-1 font-heading"><?php echo $total_blocks; ?></div>
                    <div class="text-secondary small fw-semibold">CD Blocks</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="p-3 rounded-4 bg-light border h-100 hover-lift">
                    <div class="h3 fw-bold text-success mb-1 font-heading"><?php echo $total_panchayats; ?></div>
                    <div class="text-secondary small fw-semibold">Gram Panchayats</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="p-3 rounded-4 bg-light border h-100 hover-lift">
                    <div class="h3 fw-bold text-warning mb-1 font-heading"><?php echo number_format($total_villages); ?>+</div>
                    <div class="text-secondary small fw-semibold">LGD Villages</div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg">
                <div class="p-3 rounded-4 bg-light border h-100 hover-lift">
                    <div class="h3 fw-bold text-dark mb-1 font-heading"><?php echo number_format($total_reps); ?>+</div>
                    <div class="text-secondary small fw-semibold">Elected Leaders</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Directory Verticals (Clean 8-Card Grid) -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">
                    <i class="bi bi-grid-3x3-gap-fill me-1"></i>Core Verticals
                </span>
                <h2 class="fw-bold font-heading text-dark mt-2 mb-0 fs-2">Explore Directory Services</h2>
                <p class="text-muted small mb-0">Find verified phone numbers, addresses, and local services across Saran District.</p>
            </div>
            <a href="categories" class="btn btn-outline-primary rounded-pill px-4 fw-semibold shadow-sm align-self-start align-self-md-auto">
                View All Categories <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3 g-md-4 justify-content-center">
            <?php foreach ($top_categories as $cat): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="<?php echo getCategoryUrl($cat['slug']); ?>" class="category-card hover-lift">
                        <div class="category-icon-wrapper">
                            <i class="bi <?php echo sanitizeInput($cat['icon'] ?: 'bi-folder2-open'); ?>"></i>
                        </div>
                        <div class="category-title text-truncate w-100"><?php echo sanitizeInput($cat['name']); ?></div>
                        <?php if (!empty($cat['hindi_name'])): ?>
                            <div class="category-hindi text-truncate w-100 mb-1"><?php echo sanitizeInput($cat['hindi_name']); ?></div>
                        <?php endif; ?>
                        <?php if (isset($cat['listing_count']) && $cat['listing_count'] > 0): ?>
                            <span class="badge bg-light text-primary border rounded-pill mt-2 small fw-semibold px-2.5 py-1">
                                <?php echo number_format($cat['listing_count']); ?> Listings
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Saran Civic & Local Governance Hub (Grassroots Democracy Spotlight) -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-1.5 rounded-pill small uppercase tracking-wider">
                <i class="bi bi-shield-check me-1"></i>Grassroots Democracy
            </span>
            <h2 class="fw-bold font-heading text-dark mt-2 fs-2">Saran Local Governance Directory</h2>
            <p class="text-muted mx-auto" style="max-width: 680px;">
                Complete digital directory of elected representatives, administrative offices, and revenue circles sourced from State Election Commission Bihar (<a href="https://sec.bihar.gov.in" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none fw-semibold">sec.bihar.gov.in</a>).
            </p>
        </div>

        <div class="row g-4">
            <!-- 1. Gram Panchayats -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-light hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle bg-primary-subtle text-primary p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                            <i class="bi bi-building fs-4"></i>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1 fs-5">318 Gram Panchayats</h4>
                        <p class="text-secondary small mb-3">Verified profiles of Mukhiyas, Sarpanches, Up-Mukhiyas, and Up-Sarpanches across all 20 blocks.</p>
                    </div>
                    <div>
                        <a href="panchayat" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold w-100">
                            Explore Panchayats <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Ward Members & Panch -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-light hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle bg-success-subtle text-success p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1 fs-5">8,274 Ward Leaders</h4>
                        <p class="text-secondary small mb-3">4,492 Ward Members and 3,782 Gram Kacheri Panchs covering every village ward in Saran.</p>
                    </div>
                    <div>
                        <a href="search?q=Ward" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-semibold w-100">
                            Search Ward Members <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Panchayat Samiti (BDC) & Zila Parishad -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-light hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle bg-info-subtle text-info p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                            <i class="bi bi-award-fill fs-4"></i>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1 fs-5">Panchayat Samiti</h4>
                        <p class="text-secondary small mb-3">452 elected Panchayat Samiti (BDC) Members and 47 Zila Parishad representatives.</p>
                    </div>
                    <div>
                        <a href="search?q=Panchayat+Samiti" class="btn btn-outline-info btn-sm rounded-pill px-3 fw-semibold w-100">
                            View Samiti Members <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Blocks & Villages (Maujas) -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-light hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle bg-warning-subtle text-warning-emphasis p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                            <i class="bi bi-map-fill fs-4"></i>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1 fs-5">20 Blocks & 1,876 Villages</h4>
                        <p class="text-secondary small mb-3">Sub-divisional headquarters, Halka circles, PIN codes, census data, and revenue maujas.</p>
                    </div>
                    <div>
                        <a href="blocks" class="btn btn-outline-warning text-dark btn-sm rounded-pill px-3 fw-semibold w-100">
                            Explore All Blocks <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Top Verified Listings Section -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
            <div>
                <span class="badge bg-success-subtle text-success fw-bold px-3 py-1.5 rounded-pill small uppercase tracking-wider">
                    <i class="bi bi-patch-check-fill me-1"></i>Verified Directory
                </span>
                <h2 class="fw-bold font-heading text-dark mt-2 mb-0 fs-2">Top Verified Listings</h2>
                <p class="text-muted small mb-0">Recommended institutions, healthcare clinics, verified businesses, and established services in Saran.</p>
            </div>
            <a href="search" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold">
                Explore All Verified <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($featured_listings as $item): ?>
                <div class="col-lg-6">
                    <?php echo renderListingCard($item); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest Joined Listings Section -->
<section class="py-5 bg-light border-top border-bottom">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill small uppercase tracking-wider">
                    <i class="bi bi-clock-history me-1"></i>Latest Joined
                </span>
                <h2 class="fw-bold font-heading text-dark mt-2 mb-0 fs-2">Latest Joined Listings</h2>
                <p class="text-muted small mb-0">Newly registered local businesses, public services, clinics, and community members in Saran.</p>
            </div>
            <a href="search" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold">
                View All Directory Listings <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($recent_listings as $item): ?>
                <div class="col-lg-6">
                    <?php echo renderListingCard($item); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="search" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                <i class="bi bi-search me-1"></i> Browse All <?php echo number_format($total_listings); ?> Directory Listings <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- 24/7 Emergency Helplines Quick Access Banner -->
<section class="py-4 bg-danger text-white">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white text-danger rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px;">
                    <i class="bi bi-shield-exclamation fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 font-heading text-white">24/7 Emergency Services in Saran</h5>
                    <p class="mb-0 text-white-50 small">Police (112) • Ambulance (108) • Fire (101) • Sadar Hospital Chapra • Blood Bank</p>
                </div>
            </div>
            <a href="emergency" class="btn btn-light text-danger fw-bold rounded-pill px-4 py-2 flex-shrink-0 shadow-sm">
                <i class="bi bi-telephone-fill me-1"></i> View Emergency Directory <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- Call to Action for Business Owners & Citizens -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="p-4 p-md-5 rounded-4 shadow-sm border" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-primary text-white fw-bold px-3 py-1 rounded-pill mb-2 small">Free Public Directory</span>
                    <h3 class="fw-bold font-heading text-dark mb-2">Own a Business or Service in Saran?</h3>
                    <p class="text-secondary mb-0" style="max-width: 650px; line-height: 1.6;">
                        List your shop, healthcare clinic, school, advocate practice, or service on <strong>Saran Index</strong> for free. Connect with thousands of local citizens searching daily across Chapra and all 20 blocks.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="add-contact" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add Free Listing
                        </a>
                        <a href="dashboard" class="btn btn-outline-secondary rounded-pill px-4 py-2.5 fw-semibold">
                            <i class="bi bi-shield-check me-1"></i> Claim Business
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php require_once __DIR__ . '/includes/footer.php'; ?>

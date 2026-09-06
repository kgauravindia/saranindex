<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = "Saran Index – Connecting Saran Digitally | Saran District Statistics & Directory";
require_once __DIR__ . '/includes/header.php';

// Fetch comprehensive data & statistics
$district_stats = getDistrictFullStats();
$blocks_stats = $district_stats['blocks_stats'] ?? [];
$categories = $district_stats['categories'] ?? getCategories();
$census = $district_stats['census'] ?? [];
$listings = getListings('', '', '', 6, 0);
$recent_listings = getRecentListings(6);
$blocks = getBlocks();

// Featured 3 Sub-Divisional Headquarters Blocks (Chapra, Madhaura, Sonpur)
$featured_slugs = ['chapra', 'madhaurah', 'sonpur'];
$featured_blocks = [];
foreach ($featured_slugs as $fSlug) {
    foreach ($blocks_stats as $blk) {
        if ($blk['slug'] === $fSlug) {
            $featured_blocks[] = $blk;
            break;
        }
    }
}
if (empty($featured_blocks)) {
    $featured_blocks = array_slice($blocks_stats, 0, 3);
}
?>

<!-- Top Hero Work-Related Photo Slider Section -->
<section class="hero-slider-wrapper position-relative text-center">
    <div id="heroWorkCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <!-- Carousel Indicators -->
        <div class="carousel-indicators hero-carousel-indicators">
            <button type="button" data-bs-target="#heroWorkCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroWorkCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroWorkCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <!-- Slide 1: Businesses & Commerce -->
            <div class="carousel-item active" style="background-image: url('assets/img/slider1.webp');">
                <div class="hero-slider-overlay"></div>
                <div class="container hero-slider-content">
                    <div class="d-inline-flex align-items-center mb-3 hero-badge-pill">
                        <i class="bi bi-shop text-warning me-2 fs-6"></i>
                        <span>Connecting Businesses, Shops & Retail Stores • Saran District</span>
                    </div>

                    <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
                        Saran Index
                    </h1>
                    <p class="lead text-white-50 font-heading fw-semibold mb-3 fs-3" style="color: #cbd5e1 !important;">
                        Connecting Saran Digitally
                    </p>
                    <p class="text-white-50 mx-auto mb-4" style="max-width: 720px; font-size: 1.05rem; line-height: 1.6;">
                        The single trusted digital directory and comprehensive statistical portal for <strong>Saran District (Chapra, Bihar)</strong>. Discover verified local businesses, representatives, doctors, advocates, schools, and government offices across all 20 blocks.
                    </p>
                </div>
            </div>

            <!-- Slide 2: Healthcare & Emergency Services -->
            <div class="carousel-item" style="background-image: url('assets/img/slider2.webp');">
                <div class="hero-slider-overlay"></div>
                <div class="container hero-slider-content">
                    <div class="d-inline-flex align-items-center mb-3 hero-badge-pill">
                        <i class="bi bi-hospital-fill text-warning me-2 fs-6"></i>
                        <span>24/7 Healthcare, Doctors & Emergency Helplines</span>
                    </div>

                    <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
                        Healthcare & Emergency Services
                    </h1>
                    <p class="lead text-white-50 font-heading fw-semibold mb-3 fs-3" style="color: #cbd5e1 !important;">
                        Instant Access to Medical Directory in Chapra & Saran
                    </p>
                    <p class="text-white-50 mx-auto mb-4" style="max-width: 720px; font-size: 1.05rem; line-height: 1.6;">
                        Find verified hospital contacts, specialist doctors, blood banks, town police stations, fire services, and emergency 24x7 helplines in your block.
                    </p>
                </div>
            </div>

            <!-- Slide 3: Advocates, Education & Administration -->
            <div class="carousel-item" style="background-image: url('assets/img/slider3.webp');">
                <div class="hero-slider-overlay"></div>
                <div class="container hero-slider-content">
                    <div class="d-inline-flex align-items-center mb-3 hero-badge-pill">
                        <i class="bi bi-briefcase-fill text-warning me-2 fs-6"></i>
                        <span>Verified Advocates, Schools & Government Offices</span>
                    </div>

                    <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
                        Professional Services Directory
                    </h1>
                    <p class="lead text-white-50 font-heading fw-semibold mb-3 fs-3" style="color: #cbd5e1 !important;">
                        Empowering Citizens & Institutions across Chapra & Saran
                    </p>
                    <p class="text-white-50 mx-auto mb-4" style="max-width: 720px; font-size: 1.05rem; line-height: 1.6;">
                        Connect with legal advocates, educational institutes, coaching centers, revenue offices (Halka/Panchayat), and district administration.
                    </p>
                </div>
            </div>
        </div>

        <!-- Carousel Prev/Next Buttons -->
        <button class="carousel-control-prev hero-carousel-control ms-3" type="button" data-bs-target="#heroWorkCarousel" data-bs-slide="prev">
            <i class="bi bi-chevron-left text-white fs-5"></i>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next hero-carousel-control me-3" type="button" data-bs-target="#heroWorkCarousel" data-bs-slide="next">
            <i class="bi bi-chevron-right text-white fs-5"></i>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Search Bar Component Overlay (Floats Over Hero Slider) -->
    <div class="container position-relative z-3" style="margin-top: -55px; margin-bottom: 25px;">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11 position-relative">
                <form action="search.php" method="GET" class="search-card d-flex align-items-center gap-2 shadow-lg">
                    <button type="button" class="btn mic-btn flex-shrink-0" id="micButton" title="Voice Search">
                        <i class="bi bi-mic-fill fs-5"></i>
                    </button>
                    <input type="text" name="q" id="search_box" class="form-control search-input flex-grow-1" placeholder="Search businesses, doctors, advocates, schools in Saran..." autocomplete="off" required>
                    
                    <select name="block" class="form-select border-0 bg-light rounded-pill px-3 fw-medium d-none d-md-block" style="max-width: 180px;">
                        <option value="">All 20 Blocks</option>
                        <?php foreach ($blocks as $blk): ?>
                            <option value="<?php echo sanitizeInput($blk['slug']); ?>"><?php echo sanitizeInput($blk['block_name']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn search-submit-btn flex-shrink-0">
                        <i class="bi bi-search me-1"></i>Search
                    </button>
                </form>
                
                <!-- Live Autocomplete Suggest Container -->
                <div id="autocomplete_results" class="position-absolute start-0 end-0 text-start z-3 px-3" style="display: none; top: 100%; margin-top: 6px;"></div>
            </div>
        </div>
    </div>
</section>

<!-- Master Live Statistics Counter Strip -->
<section class="py-4 bg-dark text-white shadow-sm border-top border-bottom border-secondary">
    <div class="container">
        <div class="row g-3 text-center align-items-stretch">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur h-100 d-flex flex-column justify-content-center">
                    <div class="h3 fw-bolder text-warning mb-0 font-heading"><?php echo number_format($district_stats['total_listings']); ?>+</div>
                    <div class="text-white-50 small fw-semibold">Verified Listings</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur h-100 d-flex flex-column justify-content-center">
                    <div class="h3 fw-bolder text-info mb-0 font-heading"><?php echo intval($district_stats['total_blocks']); ?></div>
                    <div class="text-white-50 small fw-semibold">CD Blocks</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur h-100 d-flex flex-column justify-content-center">
                    <div class="h3 fw-bolder text-success mb-0 font-heading"><?php echo intval($district_stats['total_panchayats']); ?></div>
                    <div class="text-white-50 small fw-semibold">Gram Panchayats</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur h-100 d-flex flex-column justify-content-center">
                    <div class="h3 fw-bolder text-light mb-0 font-heading"><?php echo number_format($district_stats['total_villages']); ?>+</div>
                    <div class="text-white-50 small fw-semibold">LGD Villages</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur h-100 d-flex flex-column justify-content-center">
                    <div class="h3 fw-bolder text-warning mb-0 font-heading"><?php echo number_format($district_stats['total_representatives']); ?>+</div>
                    <div class="text-white-50 small fw-semibold">Elected Leaders</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur h-100 d-flex flex-column justify-content-center">
                    <div class="h3 fw-bolder text-info mb-0 font-heading"><?php echo intval($district_stats['kendra_count']); ?></div>
                    <div class="text-white-50 small fw-semibold">Jan Aushadhi Kendras</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     COMPREHENSIVE DISTRICT STATISTICS DASHBOARD SECTION
     ========================================================================= -->
<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill small uppercase tracking-wider">
                <i class="bi bi-bar-chart-fill me-1"></i>Official Data & Insights
            </span>
            <h2 class="fw-bold font-heading text-dark mt-2 fs-2">Saran District at a Glance: Complete Statistics</h2>
            <p class="text-muted mx-auto" style="max-width: 680px;">
                Comprehensive census metrics, administrative infrastructure, elected leadership records, and local business density across all 20 blocks of Saran (Chapra).
            </p>
        </div>

        <!-- 4 Primary Demographic & Administrative Metric Cards -->
        <div class="row g-4 mb-5">
            <!-- 1. Population Demographics -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-counter-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-counter-icon bg-primary-subtle text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <span class="badge bg-light text-secondary border small">Census 2011</span>
                    </div>
                    <div class="stat-number text-dark"><?php echo number_format($census['total_population'] ?? 3610022); ?></div>
                    <div class="stat-label text-muted mb-2">Total District Population</div>
                    <div class="small text-secondary mb-2">
                        <span class="fw-semibold text-primary"><i class="bi bi-gender-male"></i> <?php echo number_format($census['male_population'] ?? 1843926); ?></span> | 
                        <span class="fw-semibold text-danger"><i class="bi bi-gender-female"></i> <?php echo number_format($census['female_population'] ?? 1766096); ?></span>
                    </div>
                    <div class="stat-progress mt-1">
                        <div class="stat-progress-bar bg-primary" style="width: 51%;"></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mt-1">
                        <span>Sex Ratio: <strong><?php echo $census['sex_ratio'] ?? 958; ?></strong></span>
                        <span><?php echo number_format($census['total_households'] ?? 580000); ?> Households</span>
                    </div>
                </div>
            </div>

            <!-- 2. Literacy & Education -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-counter-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-counter-icon bg-success-subtle text-success">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success small">Literacy Index</span>
                    </div>
                    <div class="stat-number text-dark"><?php echo $census['literacy_rate'] ?? 53.7; ?>%</div>
                    <div class="stat-label text-muted mb-2">District Literacy Rate</div>
                    <div class="small text-secondary mb-2">
                        <span class="fw-semibold text-success"><?php echo number_format($census['literate_population'] ?? 1940149); ?></span> Literate Citizens
                    </div>
                    <div class="stat-progress mt-1">
                        <div class="stat-progress-bar bg-success" style="width: <?php echo $census['literacy_rate'] ?? 53.7; ?>%;"></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mt-1">
                        <span>JP University Chapra</span>
                        <span><a href="university.php" class="text-decoration-none fw-semibold">View Colleges</a></span>
                    </div>
                </div>
            </div>

            <!-- 3. Administrative Hierarchy -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-counter-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-counter-icon bg-info-subtle text-info">
                            <i class="bi bi-building-fill"></i>
                        </div>
                        <span class="badge bg-info-subtle text-info small">Administrative</span>
                    </div>
                    <div class="stat-number text-dark">3 Subdivisions</div>
                    <div class="stat-label text-muted mb-2">Chapra, Marhaura, Sonpur</div>
                    <div class="small text-secondary mb-2">
                        <strong>20</strong> Blocks • <strong><?php echo $district_stats['total_panchayats']; ?></strong> Panchayats
                    </div>
                    <div class="stat-progress mt-1">
                        <div class="stat-progress-bar bg-info" style="width: 100%;"></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mt-1">
                        <span><strong><?php echo $district_stats['total_halkas']; ?></strong> Halka Circles</span>
                        <span><a href="halka.php" class="text-decoration-none fw-semibold">Halka Portal</a></span>
                    </div>
                </div>
            </div>

            <!-- 4. Civic Representation & Leadership -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-counter-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-counter-icon bg-warning-subtle text-warning">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <span class="badge bg-warning-subtle text-dark small">Local Governance</span>
                    </div>
                    <div class="stat-number text-dark"><?php echo number_format($district_stats['total_representatives']); ?>+</div>
                    <div class="stat-label text-muted mb-2">Public Representatives</div>
                    <div class="small text-secondary mb-2">
                        <span class="fw-semibold text-dark"><?php echo $district_stats['mukhiya_count']; ?> Mukhiyas</span> • 
                        <span class="fw-semibold text-dark"><?php echo $district_stats['sarpanch_count']; ?> Sarpanches</span>
                    </div>
                    <div class="stat-progress mt-1">
                        <div class="stat-progress-bar bg-warning" style="width: 90%;"></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mt-1">
                        <span>Ward & PS Members</span>
                        <span><a href="panchayat.php" class="text-decoration-none fw-semibold">Explore Directory</a></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- =====================================================================
             BLOCK-BY-BLOCK INTERACTIVE STATISTICAL MATRIX & DIRECTORY
             ===================================================================== -->
        <div class="bg-light p-4 p-md-5 rounded-4 border">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
                <div>
                    <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1 rounded-pill small">Geographical Distribution</span>
                    <h3 class="fw-bold font-heading text-dark mt-1 mb-0">All 20 Blocks: Detailed Data Matrix</h3>
                    <p class="text-muted small mb-0">Explore listings count, population, panchayats, and literacy rate for every block in Saran. Showing 3 major sub-divisions (Chapra, Madhaura, Sonpur).</p>
                </div>
                
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="blocks" class="btn btn-outline-primary rounded-pill px-3 py-1.5 fw-semibold shadow-sm small">
                        <i class="bi bi-grid-3x3-gap-fill me-1"></i> View All 20 Blocks <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary active" id="btnViewGrid" title="Grid View"><i class="bi bi-grid-fill"></i> Grid</button>
                        <button type="button" class="btn btn-outline-primary" id="btnViewTable" title="Table View"><i class="bi bi-table"></i> Table</button>
                    </div>
                </div>
            </div>

            <!-- Block Cards Grid View (3 Featured Blocks) -->
            <div id="blockGridView" class="row g-4 justify-content-center">
                <?php foreach ($featured_blocks as $blk): ?>
                    <div class="col-lg-4 col-md-6 block-item" data-name="<?php echo strtolower($blk['name'] . ' ' . $blk['hindi_name'] . ' ' . $blk['pincode']); ?>">
                        <a href="<?php echo getBlockUrl($blk['slug']); ?>" class="block-stat-card shadow-sm h-100">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1 rounded-pill small">
                                        <i class="bi bi-geo-alt-fill me-1"></i>PIN: <?php echo sanitizeInput($blk['pincode']); ?>
                                    </span>
                                    <span class="badge bg-light text-dark border small fw-bold">
                                        <?php echo intval($blk['listing_count']); ?> Listings
                                    </span>
                                </div>
                                <div class="block-title mb-1"><?php echo sanitizeInput($blk['name']); ?></div>
                                <div class="block-sub mb-3"><i class="bi bi-translate me-1"></i><?php echo sanitizeInput($blk['hindi_name']); ?></div>
                            </div>
                            
                            <div class="border-top pt-2">
                                <div class="d-flex justify-content-between text-muted small mb-1">
                                    <span>Panchayats:</span>
                                    <strong class="text-dark"><?php echo intval($blk['actual_panchayats']); ?></strong>
                                </div>
                                <div class="d-flex justify-content-between text-muted small mb-1">
                                    <span>Population:</span>
                                    <strong class="text-dark"><?php echo !empty($blk['population']) ? number_format($blk['population']) : 'N/A'; ?></strong>
                                </div>
                                <div class="d-flex justify-content-between text-muted small">
                                    <span>Literacy:</span>
                                    <strong class="text-success"><?php echo $blk['literacy_pct']; ?>%</strong>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Block Table Matrix View (Initially Hidden) -->
            <div id="blockTableView" class="stats-table-wrapper d-none">
                <div class="table-responsive">
                    <table class="table stats-table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Block Name</th>
                                <th>Hindi Name</th>
                                <th>Pincode</th>
                                <th>Panchayats</th>
                                <th>Population (2011)</th>
                                <th>Literacy Rate</th>
                                <th>Directory Listings</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $idx = 1; foreach ($featured_blocks as $blk): ?>
                                <tr class="block-row" data-name="<?php echo strtolower($blk['name'] . ' ' . $blk['hindi_name'] . ' ' . $blk['pincode']); ?>">
                                    <td class="text-muted small"><?php echo $idx++; ?></td>
                                    <td class="fw-bold text-dark"><?php echo sanitizeInput($blk['name']); ?></td>
                                    <td class="text-secondary"><?php echo sanitizeInput($blk['hindi_name']); ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo sanitizeInput($blk['pincode']); ?></span></td>
                                    <td class="fw-semibold"><?php echo intval($blk['actual_panchayats']); ?></td>
                                    <td><?php echo !empty($blk['population']) ? number_format($blk['population']) : 'N/A'; ?></td>
                                    <td><span class="text-success fw-semibold"><?php echo $blk['literacy_pct']; ?>%</span></td>
                                    <td><span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1 rounded-pill"><?php echo intval($blk['listing_count']); ?> Listings</span></td>
                                    <td class="text-end">
                                        <a href="<?php echo getBlockUrl($blk['slug']); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Block</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="blocks" class="btn btn-outline-primary rounded-pill px-4 fw-semibold shadow-sm">
                    <i class="bi bi-map-fill me-1"></i> View All 20 Blocks & Panchayat Full Directory <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Sector & Category Breakdown Grid (Top 6 Featured Categories) -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">Explore Verticals</span>
                <h2 class="fw-bold font-heading text-dark mt-2 mb-0 fs-2">Core Directory Services & Sectors</h2>
                <p class="text-muted small mb-0">Find verified listings, phone numbers, WhatsApp contacts, and maps across Saran District.</p>
            </div>
            <a href="categories" class="btn btn-outline-primary rounded-pill px-4 fw-semibold shadow-sm align-self-start align-self-md-auto">
                Browse All <?php echo count($categories); ?> Categories <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4 justify-content-center">
            <?php 
            $top_categories = array_slice($categories, 0, 6);
            foreach ($top_categories as $cat): 
            ?>
                <div class="col-lg-4 col-md-6 col-6">
                    <a href="<?php echo getCategoryUrl($cat['slug']); ?>" class="category-card position-relative">
                        <div class="category-icon-wrapper">
                            <i class="bi <?php echo sanitizeInput($cat['icon']); ?>"></i>
                        </div>
                        <div class="category-title"><?php echo sanitizeInput($cat['name']); ?></div>
                        <?php if (isset($cat['listing_count']) && $cat['listing_count'] > 0): ?>
                            <span class="badge bg-light text-primary border rounded-pill mt-2 small fw-semibold px-2.5 py-1">
                                <?php echo number_format($cat['listing_count']); ?> Verified Listings
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4 pt-2">
            <a href="categories" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i>View All <?php echo count($categories); ?> Categories & Sectors <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- Emergency Services Banner -->
<section class="py-4 bg-danger text-white">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white text-danger rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                    <i class="bi bi-shield-exclamation fs-2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1 font-heading text-white">24/7 Emergency Services Directory</h5>
                    <p class="mb-0 text-white-50 small">Town Police Station, Sadar Hospital, Blood Banks, Fire Brigade & Helpline numbers in Saran.</p>
                </div>
            </div>
            <a href="emergency" class="btn btn-light text-danger fw-bold rounded-pill px-4 py-2 flex-shrink-0 shadow-sm">
                View Emergency Contacts <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- Verified Listings Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill small">Verified Information</span>
            <h2 class="fw-bold font-heading text-dark mt-2">Popular Listings in Saran</h2>
            <p class="text-muted">Top recommended institutions, public offices, healthcare centers, and legal hubs.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($listings as $item): ?>
                <div class="col-lg-6">
                    <?php echo renderListingCard($item); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Recently Registered Listings Section (Below Popular) -->
<section class="py-5 bg-light border-top border-bottom">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-2">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill small">Newly Registered</span>
                <h2 class="fw-bold font-heading text-dark mt-2 mb-0 fs-3">Recently Added Listings</h2>
                <p class="text-muted small mb-0">Latest verified businesses, healthcare Kendras, and public offices in Saran.</p>
            </div>
            <a href="search.php" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold">Browse All Directory Listings <i class="bi bi-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($recent_listings as $item): ?>
                <div class="col-lg-6">
                    <div class="listing-card p-4 h-100 d-flex flex-column justify-content-between shadow-sm rounded-4 border">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                                        <?php echo sanitizeInput($item['category_name']); ?>
                                    </span>
                                    <?php if (!empty($item['subcategory_name'])): ?>
                                        <span class="badge bg-secondary-subtle text-secondary fw-medium px-2 py-1 rounded-pill small">
                                            <?php echo sanitizeInput($item['subcategory_name']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    <span class="badge bg-info-subtle text-info-emphasis fw-semibold px-2.5 py-1 rounded-pill small">
                                        <i class="bi bi-clock-history me-1"></i>Newly Registered
                                    </span>
                                    <?php if ($item['is_verified'] === 'YES'): ?>
                                        <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Verified</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <h4 class="fw-bold text-dark mb-1 font-heading fs-5">
                                <a href="<?php echo getListingUrl($item['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                    <?php echo sanitizeInput($item['title']); ?>
                                </a>
                            </h4>

                            <div class="text-muted small mb-3">
                                <i class="bi bi-geo-alt me-1 text-primary"></i><?php echo sanitizeInput(formatListingLocation($item)); ?>
                            </div>

                            <p class="small text-secondary mb-3" style="line-height: 1.5;">
                                <?php echo sanitizeInput($item['description']); ?>
                            </p>
                        </div>

                        <div class="border-top pt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <?php echo renderStarRating($item['star_rating']); ?>
                            </div>
                            <div class="d-flex gap-2">
                                <?php if (isMobileNumberVisibleToVisitor($item)): ?>
                                    <?php if (!empty($item['whatsapp'])): ?>
                                        <a href="https://wa.me/91<?php echo sanitizeInput($item['whatsapp']); ?>" target="_blank" class="btn-whatsapp">
                                            <i class="bi bi-whatsapp"></i> Chat
                                        </a>
                                    <?php endif; ?>
                                    <a href="tel:<?php echo sanitizeInput($item['mobile']); ?>" class="btn-call">
                                        <i class="bi bi-telephone-fill"></i> Call
                                    </a>
                                <?php else: ?>
                                    <a href="login.php?redirect=<?php echo urlencode('listing/' . $item['slug']); ?>" class="btn-call bg-warning-subtle text-dark border-warning-subtle text-decoration-none" title="Log in to view full mobile number">
                                        <i class="bi bi-lock-fill text-warning me-1"></i><?php echo sanitizeInput(maskPhoneNumber($item['mobile'])); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Geographic, Waterways & Public Infrastructure Connect Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-info-subtle text-info-emphasis fw-bold px-3 py-1.5 rounded-pill small mb-2">District Infrastructure</span>
                <h2 class="fw-bold font-heading text-dark mb-3">Rivers, Irrigation & Public Utilities of Saran</h2>
                <p class="text-secondary mb-4">
                    Saran district is bounded by three sacred rivers — the Ganga, Gandak, and Ghaghara. Discover detailed guides on local canals (Nahar system), university colleges, and revenue maujas.
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="fw-bold text-dark mb-1"><i class="bi bi-water text-primary me-1"></i> 3 Major Rivers</div>
                            <div class="small text-muted">Ganga, Gandak & Ghaghara River basins</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="fw-bold text-dark mb-1"><i class="bi bi-diagram-3-fill text-success me-1"></i> Saran Canals</div>
                            <div class="small text-muted">Saran Canal Irrigation network</div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="river.php" class="btn btn-outline-primary rounded-pill px-3 py-2 btn-sm fw-semibold"><i class="bi bi-water me-1"></i> Rivers Directory</a>
                    <a href="nahar.php" class="btn btn-outline-success rounded-pill px-3 py-2 btn-sm fw-semibold"><i class="bi bi-diagram-3 me-1"></i> Nahar Canal System</a>
                    <a href="university.php" class="btn btn-outline-info rounded-pill px-3 py-2 btn-sm fw-semibold"><i class="bi bi-mortarboard me-1"></i> JP University Chapra</a>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); color: #ffffff;">
                    <div class="card-body p-4 p-md-5">
                        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-3">Saran District Highlights</span>
                        <h3 class="fw-bold text-white mb-3">Digital Governance for Every Village</h3>
                        <p class="text-white-50 mb-4" style="color: #cbd5e1 !important;">
                            Access verified contacts of Gram Panchayats, Mukhiyas, Sarpanches, Halka Karamcharis, and Revenue Officers across all 20 blocks.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="panchayat.php" class="btn btn-light fw-bold rounded-pill px-4 py-2 text-dark shadow-sm">
                                <i class="bi bi-people me-1"></i> Panchayats Portal
                            </a>
                            <a href="halka.php" class="btn btn-outline-light fw-bold rounded-pill px-4 py-2">
                                <i class="bi bi-file-earmark-text me-1"></i> Halka Mauja Search
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action for Business Owners -->
<section class="py-5 bg-light border-top">
    <div class="container">
        <div class="cta-banner text-white p-4 p-md-5 shadow-lg">
            <div class="row align-items-center g-4 position-relative z-1">
                <div class="col-lg-7">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-3">For Business Owners & Professionals</span>
                    <h2 class="fw-bold font-heading text-white display-6 mb-3">Grow Your Business Across Saran District</h2>
                    <p class="text-white-50 lead mb-0" style="font-size: 1.1rem; color: #cbd5e1 !important;">
                        List your business, clinic, school, or legal practice on <strong>Saran Index</strong> for free. Reach thousands of local customers in Chapra, Marhaura, Sonepur, and all 20 blocks.
                    </p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="dashboard.php" class="btn btn-warning btn-lg rounded-pill px-4 py-3 fw-bold text-dark shadow">
                            <i class="bi bi-shield-check me-1"></i>Claim Business
                        </a>
                        <a href="add-contact.php" class="btn btn-outline-light btn-lg rounded-pill px-4 py-3 fw-bold shadow">
                            <i class="bi bi-plus-circle-fill me-1"></i>Add Listing Free
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Block Filter & Toggle JS Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterInput = document.getElementById('blockFilterInput');
    const btnGrid = document.getElementById('btnViewGrid');
    const btnTable = document.getElementById('btnViewTable');
    const gridView = document.getElementById('blockGridView');
    const tableView = document.getElementById('blockTableView');

    if (btnGrid && btnTable && gridView && tableView) {
        btnGrid.addEventListener('click', function() {
            btnGrid.classList.add('active');
            btnTable.classList.remove('active');
            gridView.classList.remove('d-none');
            tableView.classList.add('d-none');
        });

        btnTable.addEventListener('click', function() {
            btnTable.classList.add('active');
            btnGrid.classList.remove('active');
            tableView.classList.remove('d-none');
            gridView.classList.add('d-none');
        });
    }

    if (filterInput) {
        filterInput.addEventListener('input', function() {
            const query = filterInput.value.toLowerCase().trim();
            
            // Filter grid cards
            const gridItems = document.querySelectorAll('.block-item');
            gridItems.forEach(function(item) {
                const name = item.getAttribute('data-name') || '';
                if (name.includes(query)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });

            // Filter table rows
            const tableRows = document.querySelectorAll('.block-row');
            tableRows.forEach(function(row) {
                const name = row.getAttribute('data-name') || '';
                if (name.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = "Saran Index – Connecting Saran Digitally | Saran District Directory";
require_once __DIR__ . '/includes/header.php';

$blocks = getBlocks();
$categories = getCategories();
$listings = getListings('', '', '', 6, 0);
$recent_listings = getRecentListings(6);

$db_stat = getDB();
$total_listings_count = $db_stat ? intval($db_stat->query("SELECT COUNT(*) FROM listings WHERE status = 'ACTIVE'")->fetchColumn() ?: 1500) : 1500;
$kendra_count = $db_stat ? intval($db_stat->query("SELECT COUNT(*) FROM listings WHERE title LIKE '%Jan Aushadhi%' OR title LIKE '%PMBJK%'")->fetchColumn() ?: 58) : 58;
$panchayat_count = $db_stat ? intval($db_stat->query("SELECT COUNT(*) FROM panchayats")->fetchColumn() ?: 322) : 322;
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
            <div class="carousel-item active" style="background-image: url('assets/img/slider1.png');">
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
                    <p class="text-white-50 mx-auto mb-4" style="max-width: 700px; font-size: 1.05rem; line-height: 1.6;">
                        The single trusted digital directory for <strong>Saran District, Bihar</strong>. Discover verified local businesses, merchants, advocates, doctors, schools, and government offices across all 20 blocks.
                    </p>
                </div>
            </div>

            <!-- Slide 2: Healthcare & Emergency Services -->
            <div class="carousel-item" style="background-image: url('assets/img/slider2.png');">
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
                    <p class="text-white-50 mx-auto mb-4" style="max-width: 700px; font-size: 1.05rem; line-height: 1.6;">
                        Find verified hospital contacts, specialist doctors, blood banks, town police stations, fire services, and emergency 24x7 helplines in your block.
                    </p>
                </div>
            </div>

            <!-- Slide 3: Advocates, Education & Administration -->
            <div class="carousel-item" style="background-image: url('assets/img/slider3.png');">
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
                    <p class="text-white-50 mx-auto mb-4" style="max-width: 700px; font-size: 1.05rem; line-height: 1.6;">
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

<!-- Live Directory Statistics Counter Strip -->
<section class="py-4 bg-dark text-white shadow-sm border-top border-bottom border-secondary">
    <div class="container">
        <div class="row g-3 text-center align-items-center">
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h2 fw-bolder text-warning mb-0 font-heading"><?php echo number_format($total_listings_count); ?>+</div>
                    <div class="text-white-50 small fw-semibold">Verified Directory Listings</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h2 fw-bolder text-info mb-0 font-heading"><?php echo intval($kendra_count); ?></div>
                    <div class="text-white-50 small fw-semibold">Jan Aushadhi Kendras</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h2 fw-bolder text-success mb-0 font-heading"><?php echo count($blocks); ?></div>
                    <div class="text-white-50 small fw-semibold">Saran Blocks Covered</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h2 fw-bolder text-light mb-0 font-heading"><?php echo intval($panchayat_count); ?>+</div>
                    <div class="text-white-50 small fw-semibold">Gram Panchayats</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9 Core Verticals Grid Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill uppercase tracking-wider small">Explore Verticals</span>
            <h2 class="fw-bold font-heading text-dark mt-2 fs-2">Core Directory Services</h2>
            <p class="text-muted mx-auto" style="max-width: 540px;">Find verified listings, phone numbers, WhatsApp contacts, and maps across Saran District.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($categories as $cat): ?>
                <div class="col-lg-4 col-md-6 col-6">
                    <a href="<?php echo getCategoryUrl($cat['slug']); ?>" class="category-card">
                        <div class="category-icon-wrapper">
                            <i class="bi <?php echo sanitizeInput($cat['icon']); ?>"></i>
                        </div>
                        <div class="category-title"><?php echo sanitizeInput($cat['name']); ?></div>
                    </a>
                </div>
            <?php endforeach; ?>
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

<!-- 20 Saran Blocks Directory Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-2">
            <div>
                <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1 rounded-pill small">Geographic Directory</span>
                <h3 class="fw-bold font-heading text-dark mt-1 mb-0">All 20 Blocks of Saran District</h3>
            </div>
            <a href="blocks" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold">View All Blocks & Panchayats</a>
        </div>

        <div class="row g-3">
            <?php foreach ($blocks as $blk): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="<?php echo getBlockUrl($blk['slug']); ?>" class="block-pill">
                        <div>
                            <div><?php echo sanitizeInput($blk['block_name']); ?></div>
                        </div>
                        <i class="bi bi-geo-alt-fill text-primary ms-2"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Verified Listings Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill small">Verified Information</span>
            <h2 class="fw-bold font-heading text-dark mt-2">Popular Listings in Saran</h2>
            <p class="text-muted">Top recommended institutions, public offices, healthcare centers, and legal hubs.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($listings as $item): ?>
                <div class="col-lg-6">
                    <div class="listing-card p-4 h-100 d-flex flex-column justify-content-between">
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
                                    <?php if (isset($item['plan_type']) && $item['plan_type'] === 'PLATINUM'): ?>
                                        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill small shadow-xs">
                                            <i class="bi bi-crown-fill me-1 text-danger"></i> VIP Platinum
                                        </span>
                                    <?php elseif (isset($item['plan_type']) && $item['plan_type'] === 'GOLD'): ?>
                                        <span class="badge bg-primary text-white fw-bold px-2.5 py-1 rounded-pill small shadow-xs">
                                            <i class="bi bi-patch-check-fill me-1"></i> Gold Business
                                        </span>
                                    <?php elseif ($item['is_verified'] === 'YES'): ?>
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

<!-- Recently Registered Listings Section (Below Popular) -->
<section class="py-5 bg-white border-top border-bottom">
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

<!-- Call to Action for Business Owners -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="cta-banner text-white p-4 p-md-5 shadow-lg">
            <div class="row align-items-center g-4 position-relative z-1">
                <div class="col-lg-8">
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>

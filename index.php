<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = "Saran Index – Connecting Saran Digitally | Saran District Directory";
require_once __DIR__ . '/includes/header.php';

$blocks = getBlocks();
$categories = getCategories();
$listings = getListings('', '', '', 6, 0);
?>

<!-- Hero Section -->
<section class="hero-wrapper position-relative text-center">
    <div class="container position-relative z-1">
        <div class="d-inline-flex align-items-center mb-3 brand-badge">
            <i class="bi bi-patch-check-fill text-warning me-2 fs-6"></i>
            <span>Launching 26 July 2026 • OfferPlant 9th Incorporation Anniversary</span>
        </div>

        <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
            Saran Index
        </h1>
        <p class="lead text-white-50 font-heading fw-semibold mb-4 fs-3" style="color: #cbd5e1 !important;">
            Connecting Saran Digitally
        </p>
        <p class="text-white-50 mx-auto mb-4" style="max-width: 680px; font-size: 1.05rem;">
            The unified digital directory for <strong>Saran District, Bihar</strong>. Discover businesses, advocates, doctors, schools, government offices, and emergency services across all 20 blocks.
        </p>

        <!-- Search Bar Component -->
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11">
                <form action="search.php" method="GET" class="search-card d-flex align-items-center gap-2">
                    <button type="button" class="btn mic-btn flex-shrink-0" id="micButton" title="Voice Search in Hindi/English">
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
                <div id="autocomplete_results" class="position-absolute w-100 text-start z-3" style="max-width: 780px; display: none;"></div>
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
                        <div class="category-hindi"><?php echo sanitizeInput($cat['hindi_name']); ?></div>
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
                            <small class="text-muted fw-normal"><?php echo sanitizeInput($blk['hindi_name']); ?></small>
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
                                <?php if ($item['is_verified'] === 'YES'): ?>
                                    <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Verified</span>
                                <?php endif; ?>
                            </div>

                            <h4 class="fw-bold text-dark mb-1 font-heading fs-5">
                                <a href="<?php echo getListingUrl($item['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                    <?php echo sanitizeInput($item['title']); ?>
                                </a>
                            </h4>
                            <?php if (!empty($item['hindi_title'])): ?>
                                <div class="text-muted small fw-medium mb-2"><?php echo sanitizeInput($item['hindi_title']); ?></div>
                            <?php endif; ?>

                            <div class="text-muted small mb-3">
                                <i class="bi bi-geo-alt me-1 text-primary"></i><?php echo sanitizeInput($item['address']); ?>
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
                                <?php if (!empty($item['whatsapp'])): ?>
                                    <a href="https://wa.me/91<?php echo sanitizeInput($item['whatsapp']); ?>" target="_blank" class="btn-whatsapp">
                                        <i class="bi bi-whatsapp"></i> Chat
                                    </a>
                                <?php endif; ?>
                                <a href="tel:<?php echo sanitizeInput($item['mobile']); ?>" class="btn-call">
                                    <i class="bi bi-telephone-fill"></i> Call
                                </a>
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
        <div class="bg-primary text-white rounded-4 p-4 p-md-5 position-relative overflow-hidden shadow-lg">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-3">For Business Owners & Professionals</span>
                    <h2 class="fw-bold font-heading text-white display-6 mb-3">Grow Your Business Across Saran District</h2>
                    <p class="text-white-50 lead mb-0" style="font-size: 1.1rem;">
                        List your business, clinic, school, or legal practice on <strong>Saran Index</strong> for free. Reach thousands of local customers in Chapra, Marhaura, Sonepur, and all 20 blocks.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="add-listing" class="btn btn-warning btn-lg rounded-pill px-5 py-3 fw-bold text-dark shadow">
                        <i class="bi bi-plus-circle-fill me-2"></i>Add Listing Free
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

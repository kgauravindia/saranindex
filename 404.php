<?php
http_response_code(404);
require_once __DIR__ . '/includes/functions.php';

$page_title = "404 Page Not Found – Saran Index";
$meta_description = "The page or profile you requested was not found. Search Saran Index directory or explore businesses, blocks, and emergency services in Saran District.";

$blocks = getBlocks();
$categories = getCategories();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section (Identical UI to index.php hero-wrapper) -->
<section class="hero-wrapper position-relative text-center">
    <div class="container position-relative z-1">
        <div class="d-inline-flex align-items-center mb-3 brand-badge">
            <i class="bi bi-exclamation-triangle-fill text-warning me-2 fs-6"></i>
            <span>Error 404 • Page Not Found / पृष्ठ नहीं मिला</span>
        </div>

        <h1 class="display-3 fw-bolder font-heading text-white mb-2 tracking-tight">
            404
        </h1>
        <p class="lead text-white-50 font-heading fw-semibold mb-3 fs-3" style="color: #cbd5e1 !important;">
            Oops! Page Not Found / पृष्ठ नहीं मिला
        </p>
        <p class="text-white-50 mx-auto mb-4" style="max-width: 680px; font-size: 1.05rem;">
            The page, business listing, or user profile handle you requested does not exist or may have been moved. Search <strong>Saran Index</strong> directory below:
        </p>

        <!-- Search Bar Component (Identical to index.php search-card) -->
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11 position-relative">
                <form action="search.php" method="GET" class="search-card d-flex align-items-center gap-2">
                    <button type="button" class="btn mic-btn flex-shrink-0" id="micButton" title="Voice Search">
                        <i class="bi bi-mic-fill fs-5"></i>
                    </button>
                    <input type="text" name="q" id="search_box" class="form-control search-input flex-grow-1" placeholder="Search businesses, doctors, advocates, schools in Saran..." autocomplete="off" required autofocus>
                    
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

<!-- Core Directory Services (Identical UI to index.php category cards) -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill uppercase tracking-wider small">Directory Shortcuts</span>
            <h2 class="fw-bold font-heading text-dark mt-2 fs-2">Explore Core Categories</h2>
            <p class="text-muted mx-auto" style="max-width: 540px;">Choose a section below to navigate back to verified listings across Saran District.</p>
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

<!-- Emergency Services Banner (Identical UI to index.php emergency banner) -->
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

<!-- 20 Saran Blocks Directory Section (Identical UI to index.php blocks section) -->
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

        <div class="text-center mt-5 pt-3">
            <a href="index.php" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                <i class="bi bi-house-door-fill me-1.5"></i> Return to Homepage
            </a>
            <a href="contact.php" class="btn btn-outline-secondary rounded-pill px-4 py-2.5 fw-semibold ms-2">
                <i class="bi bi-headset me-1.5"></i> Contact Support
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

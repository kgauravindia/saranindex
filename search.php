<?php
require_once __DIR__ . '/includes/functions.php';

$q = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
$category_slug = isset($_GET['category']) ? sanitizeInput($_GET['category']) : '';
$sub_slug = isset($_GET['sub']) ? sanitizeInput($_GET['sub']) : '';
$block_slug = isset($_GET['block']) ? sanitizeInput($_GET['block']) : '';

$page_title = "Search Directory – Saran Index";
if (!empty($q)) {
    $page_title = "Search results for '$q' – Saran Index";
}

require_once __DIR__ . '/includes/header.php';

$blocks = getBlocks();
$categories = getCategories();
$listings = getListings($q, $category_slug, $block_slug, 50, 0, $sub_slug);
$censusVillages = !empty($q) ? getCensusVillages($block_slug, $q, 6, 0) : [];
?>

<div class="bg-dark text-white py-4">
    <div class="container">
        <h1 class="fw-bold font-heading mb-2 fs-3">Directory Search</h1>
        <p class="text-white-50 small mb-0">Search local businesses, advocates, doctors, schools, and government offices in Saran District.</p>
    </div>
</div>

<div class="container py-4">
    <!-- Filter Bar -->
    <div class="bg-white p-3 rounded-4 shadow-sm border mb-4">
        <form action="search.php" method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted mb-1">Search Keywords</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0 bg-light" placeholder="e.g. Doctor, Advocate, Hospital, School..." value="<?php echo sanitizeInput($q); ?>">
                </div>
            </div>
            
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">Category Vertical</label>
                <select name="category" class="form-select bg-light">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo sanitizeInput($cat['slug']); ?>" <?php echo ($category_slug === $cat['slug']) ? 'selected' : ''; ?>>
                            <?php echo sanitizeInput($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">Select Block</label>
                <select name="block" class="form-select bg-light">
                    <option value="">All 20 Saran Blocks</option>
                    <?php foreach ($blocks as $blk): ?>
                        <option value="<?php echo sanitizeInput($blk['slug']); ?>" <?php echo ($block_slug === $blk['slug']) ? 'selected' : ''; ?>>
                            <?php echo sanitizeInput($blk['block_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold"><i class="bi bi-funnel-fill me-1"></i>Filter</button>
            </div>
        </form>
    </div>

    <!-- Search Results Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold font-heading mb-0 text-dark">
            Found <?php echo count($listings); ?> verified listings
            <?php if (!empty($q)): ?> for "<span class="text-primary"><?php echo sanitizeInput($q); ?></span>"<?php endif; ?>
        </h5>
        <a href="search.php" class="btn btn-sm btn-link text-muted text-decoration-none">Clear Filters</a>
    </div>

    <!-- Census 2011 Villages Match Section -->
    <?php if (!empty($censusVillages)): ?>
        <div class="mb-5 p-4 bg-primary-subtle rounded-4 border border-primary-subtle">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold text-primary mb-0 font-heading">
                    <i class="bi bi-geo-alt-fill me-2"></i> Matching Census 2011 Villages (<?php echo count($censusVillages); ?>)
                </h5>
                <a href="village.php?search=<?php echo urlencode($q); ?>" class="btn btn-sm btn-primary rounded-pill px-3">
                    View All Village Results <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-3">
                <?php foreach ($censusVillages as $cv): 
                    $cvSlug = !empty($cv['unique_slug']) ? $cv['unique_slug'] : $cv['town_village_code'];
                ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="badge bg-primary text-white rounded-pill fs-7"><?php echo sanitizeInput($cv['block_name']); ?> Block</span>
                                <span class="text-muted fs-7">Code: <?php echo sanitizeInput($cv['town_village_code']); ?></span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">
                                <a href="<?php echo getVillageUrl($cvSlug); ?>" class="text-dark text-decoration-none hover-primary">
                                    <?php echo sanitizeInput($cv['name']); ?>
                                </a>
                                <?php if (!empty($cv['name_hindi'])): ?>
                                    <span class="text-muted font-hindi fw-normal"> (<?php echo sanitizeInput($cv['name_hindi']); ?>)</span>
                                <?php endif; ?>
                            </h6>
                            <div class="d-flex justify-content-between text-muted fs-7 mt-2">
                                <span>Population: <strong><?php echo number_format($cv['pop_tot']); ?></strong></span>
                                <span>Households: <strong><?php echo number_format($cv['households']); ?></strong></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Listings Grid -->
    <div class="row g-4">
        <?php if (!empty($listings)): ?>
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
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 border">
                    <i class="bi bi-search text-muted display-3 mb-3 d-block"></i>
                    <h4 class="fw-bold text-dark mb-2">No Directory Listings Found</h4>
                    <p class="text-muted mb-4" style="max-width: 460px; margin: 0 auto;">We couldn't find any listings matching your search filters. Try searching for a broader term like 'Doctor' or 'Chapra'.</p>
                    <a href="add-contact.php" class="btn btn-warning rounded-pill px-4 py-2 fw-bold text-dark">
                        <i class="bi bi-plus-circle me-1"></i>Be the First to Add a Listing
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

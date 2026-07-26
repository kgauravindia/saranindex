<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : 'schools-education';
$sub_slug = isset($_GET['sub']) ? sanitizeInput($_GET['sub']) : '';

$categories = getCategories();
$selected_category = null;

foreach ($categories as $cat) {
    if ($cat['slug'] === $slug) {
        $selected_category = $cat;
        break;
    }
}

if (!$selected_category) {
    $selected_category = $categories[0];
}

$subcategories = getSubcategoriesByCategoryId($selected_category['id']);
$selected_subcategory = null;
if (!empty($sub_slug)) {
    foreach ($subcategories as $sc) {
        if ($sc['slug'] === $sub_slug) {
            $selected_subcategory = $sc;
            break;
        }
    }
}

$title_suffix = $selected_subcategory ? " - " . $selected_subcategory['name'] : "";
$page_title = $selected_category['name'] . $title_suffix . " in Saran District – Saran Index";
$meta_description = "Find verified " . $selected_category['name'] . " (" . $selected_category['hindi_name'] . ") across Chapra and all 20 blocks of Saran District.";

require_once __DIR__ . '/includes/header.php';

$listings = getListings('', $selected_category['slug'], '', 50, 0, $sub_slug);
$blocks = getBlocks();
?>

<div class="bg-primary text-white py-5 text-center">
    <div class="container">
        <div class="d-inline-flex align-items-center bg-white text-primary fw-bold px-3 py-1 rounded-pill mb-2 shadow-sm">
            <i class="bi <?php echo sanitizeInput($selected_category['icon']); ?> me-1"></i><?php echo sanitizeInput($selected_category['section']); ?> VERTICAL
        </div>
        <h1 class="fw-bolder font-heading text-white display-5 mb-1"><?php echo sanitizeInput($selected_category['name']); ?></h1>
        <div class="lead text-white-50 mb-0"><?php echo sanitizeInput($selected_category['hindi_name']); ?> in Saran District (Chapra)</div>
    </div>
</div>

<div class="container py-4">
    <!-- Category Selector Navigation -->
    <div class="row g-2 mb-4">
        <div class="col-12">
            <div class="d-flex gap-2 overflow-x-auto pb-2">
                <?php foreach ($categories as $cat): ?>
                    <a href="<?php echo getCategoryUrl($cat['slug']); ?>" class="btn btn-sm <?php echo ($cat['slug'] === $selected_category['slug']) ? 'btn-primary' : 'btn-outline-secondary'; ?> rounded-pill px-3 flex-shrink-0 fw-semibold">
                        <i class="bi <?php echo sanitizeInput($cat['icon']); ?> me-1"></i><?php echo sanitizeInput($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Subcategories Filter Pills -->
    <?php if (!empty($subcategories)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-light">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="fw-bold text-dark small text-uppercase" style="letter-spacing: 0.05em;"><i class="bi bi-diagram-3-fill me-1 text-primary"></i>Subcategories in <?php echo sanitizeInput($selected_category['name']); ?>:</span>
                <?php if ($selected_subcategory): ?>
                    <a href="<?php echo getCategoryUrl($selected_category['slug']); ?>" class="badge bg-secondary text-decoration-none"><i class="bi bi-x-circle me-1"></i>Clear Filter</a>
                <?php endif; ?>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo getCategoryUrl($selected_category['slug']); ?>" class="badge rounded-pill <?php echo empty($sub_slug) ? 'bg-primary text-white' : 'bg-white text-dark border'; ?> px-3 py-2 text-decoration-none fw-semibold shadow-xs">
                    All Subcategories (<?php echo count($subcategories); ?>)
                </a>
                <?php foreach ($subcategories as $sub): ?>
                    <a href="<?php echo getCategoryUrl($selected_category['slug'], $sub['slug']); ?>" class="badge rounded-pill <?php echo ($sub_slug === $sub['slug']) ? 'bg-primary text-white' : 'bg-white text-dark border'; ?> px-3 py-2 text-decoration-none fw-medium shadow-xs">
                        <?php echo sanitizeInput($sub['name']); ?>
                        <?php if (!empty($sub['hindi_name'])): ?>
                            <span class="text-muted fw-normal"> (<?php echo sanitizeInput($sub['hindi_name']); ?>)</span>
                        <?php endif; ?>
                    </a>
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
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 border">
                    <i class="bi bi-search text-muted display-4 mb-3 d-block"></i>
                    <h4 class="fw-bold text-dark">No Listings Found</h4>
                    <p class="text-muted mb-4">Be the first to list your entity in <?php echo sanitizeInput($selected_category['name']); ?><?php echo $selected_subcategory ? ' (' . sanitizeInput($selected_subcategory['name']) . ')' : ''; ?>.</p>
                    <a href="add_contact.php" class="btn btn-warning rounded-pill px-4 py-2 fw-bold text-dark">
                        <i class="bi bi-plus-circle me-1"></i>Add Listing Free
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : 'business-services';
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

// Split subcategories into Professionals and Business groups
$prof_subcategories = [];
$biz_subcategories = [];

foreach ($subcategories as $sc) {
    if (isset($sc['type']) && $sc['type'] === 'BUSINESS') {
        $biz_subcategories[] = $sc;
    } else {
        $prof_subcategories[] = $sc;
    }
}

$catHindiName = !empty($selected_category['hindi_name']) ? $selected_category['hindi_name'] : $selected_category['name'];
$subTitle_suffix = $selected_subcategory ? " - " . (!empty($selected_subcategory['hindi_name']) ? $selected_subcategory['hindi_name'] : $selected_subcategory['name']) : "";
$page_title = $catHindiName . $subTitle_suffix . " सारण जिला में – सारण इंडेक्स";
$meta_description = "सारण जिले (छपरा) के सभी 20 प्रखंडों में सत्यापित " . $catHindiName . " की सूची प्राप्त करें।";
$meta_keywords = getCategoryMetaKeywords($selected_category, $subcategories);

require_once __DIR__ . '/includes/header.php';

$listings = getListings('', $selected_category['slug'], '', 50, 0, $sub_slug);
$blocks = getBlocks();
?>

<!-- SEO Schema.org Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "<?php echo sanitizeInput($catHindiName); ?> in Saran District",
  "description": "<?php echo sanitizeInput($meta_description); ?>",
  "numberOfItems": <?php echo count($listings); ?>
}
</script>

<!-- Category Hero Banner -->
<div class="bg-primary text-white py-4 py-md-5 text-center position-relative shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%) !important;">
    <div class="container px-3">
        <div class="d-inline-flex align-items-center bg-white text-primary fw-bold px-3 py-1 rounded-pill mb-2 shadow-sm fs-7">
            <i class="bi <?php echo sanitizeInput($selected_category['icon']); ?> me-1.5"></i><?php echo sanitizeInput($selected_category['section']); ?> मुख्य श्रेणी
        </div>
        <h1 class="fw-bolder font-heading text-white display-6 mb-1 text-wrap"><?php echo sanitizeInput($catHindiName); ?></h1>
        <div class="lead text-white-50 fs-6 mb-0">सारण जिला (छपरा) डायरेक्टरी</div>
    </div>
</div>

<div class="container py-3 py-md-4">
    <!-- Subcategories Section - Pure Hindi Badges -->
    <?php if (!empty($subcategories)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4 bg-white">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-dark fs-6 text-uppercase" style="letter-spacing: 0.03em;">
                        <i class="bi bi-grid-3x3-gap-fill me-1 text-primary"></i><?php echo sanitizeInput($catHindiName); ?> में उप-श्रेणियां
                    </span>
                    <span class="badge bg-primary-subtle text-primary rounded-pill fw-semibold">कुल <?php echo count($subcategories); ?></span>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <a href="<?php echo '../' . getCategoryUrl($selected_category['slug'], $sub_slug); ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-bold fs-7">
                        <i class="bi bi-globe me-1"></i>English
                    </a>
                    <?php if ($selected_subcategory): ?>
                        <a href="<?php echo getCategoryUrl($selected_category['slug']); ?>" class="badge bg-danger text-white text-decoration-none px-2.5 py-1.5 rounded-pill"><i class="bi bi-x-circle me-1"></i>फ़िल्टर हटाएं</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reset Filter Button -->
            <div class="mb-3">
                <a href="<?php echo getCategoryUrl($selected_category['slug']); ?>" class="btn btn-sm rounded-pill <?php echo empty($sub_slug) ? 'btn-primary shadow-xs' : 'btn-light border'; ?> px-3 py-1.5 fw-semibold me-2 mb-2">
                    <i class="bi bi-collection me-1"></i>सभी <?php echo sanitizeInput($catHindiName); ?> सूचियां
                </a>
            </div>

            <!-- SECTION 1: Professionals & Skilled Workers (Pure Hindi) -->
            <?php if (!empty($prof_subcategories)): ?>
                <div class="subcat-group mb-3">
                    <div class="fw-bold text-primary small text-uppercase mb-2 d-flex align-items-center gap-1.5" style="letter-spacing: 0.04em;">
                        <i class="bi bi-person-workspace text-primary"></i>
                        <span>1. व्यवसायी व कार्यकर्ता</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($prof_subcategories as $sub): 
                            $hName = !empty($sub['hindi_name']) ? $sub['hindi_name'] : $sub['name'];
                        ?>
                            <a href="<?php echo getCategoryUrl($selected_category['slug'], $sub['slug']); ?>" 
                               class="badge rounded-pill <?php echo ($sub_slug === $sub['slug']) ? 'bg-primary text-white shadow-sm' : 'bg-light text-dark border hover-border-primary'; ?> px-3 py-2 text-decoration-none fw-medium transition-all">
                                <?php echo sanitizeInput($hName); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- SECTION 2: Businesses & Establishments (Pure Hindi) -->
            <?php if (!empty($biz_subcategories)): ?>
                <div class="subcat-group">
                    <div class="fw-bold text-dark small text-uppercase mb-2 d-flex align-items-center gap-1.5" style="letter-spacing: 0.04em;">
                        <i class="bi bi-building-gear text-secondary"></i>
                        <span>2. प्रतिष्ठान व संस्थान</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($biz_subcategories as $sub): 
                            $hName = !empty($sub['hindi_name']) ? $sub['hindi_name'] : $sub['name'];
                        ?>
                            <a href="<?php echo getCategoryUrl($selected_category['slug'], $sub['slug']); ?>" 
                               class="badge rounded-pill <?php echo ($sub_slug === $sub['slug']) ? 'bg-primary text-white shadow-sm' : 'bg-light text-dark border hover-border-primary'; ?> px-3 py-2 text-decoration-none fw-medium transition-all">
                                <?php echo sanitizeInput($hName); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Category Description Paragraph Card (Dynamic Database Synthesis - Hindi SEO) -->
    <?php $catParagraphHindi = generateCategoryParagraph($selected_category, $subcategories, true); ?>
    <?php if (!empty($catParagraphHindi)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4 bg-light border-start border-primary border-4">
            <div class="d-flex align-items-start gap-3">
                <div class="bg-primary text-white rounded-circle p-2 d-none d-sm-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark fs-6 mb-1 font-heading">
                        सारण जिले में <?php echo sanitizeInput($catHindiName); ?> के बारे में
                    </h3>
                    <div class="text-secondary small mb-0" style="line-height: 1.6;">
                        <?php echo $catParagraphHindi; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Selected Filter Title Bar -->
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <h2 class="fw-bold text-dark fs-5 mb-0">
            <?php if ($selected_subcategory): ?>
                <?php echo sanitizeInput(!empty($selected_subcategory['hindi_name']) ? $selected_subcategory['hindi_name'] : $selected_subcategory['name']); ?>
            <?php else: ?>
                सभी <?php echo sanitizeInput($catHindiName); ?> सूचियां
            <?php endif; ?>
        </h2>
        <span class="text-muted small fw-medium"><?php echo count($listings); ?> सत्यापित प्रविष्टियां</span>
    </div>

    <!-- Listings Grid -->
    <div class="row g-3 g-md-4">
        <?php if (!empty($listings)): ?>
            <?php foreach ($listings as $item): 
                $itemTitle = !empty($item['hindi_title']) ? $item['hindi_title'] : $item['title'];
            ?>
                <div class="col-12 col-md-6">
                    <div class="listing-card p-3 p-md-4 h-100 d-flex flex-column justify-content-between rounded-4 shadow-sm border bg-white">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                                        <?php echo sanitizeInput(!empty($item['category_hindi_name']) ? $item['category_hindi_name'] : $item['category_name']); ?>
                                    </span>
                                    <?php if (!empty($item['subcategory_name'])): ?>
                                        <span class="badge bg-secondary-subtle text-secondary fw-medium px-2 py-1 rounded-pill small">
                                            <?php echo sanitizeInput(!empty($item['subcategory_hindi_name']) ? $item['subcategory_hindi_name'] : $item['subcategory_name']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                    <?php if (isset($item['plan_type']) && $item['plan_type'] === 'PLATINUM'): ?>
                                        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill small shadow-xs">
                                            <i class="bi bi-crown-fill me-1 text-danger"></i> वीआईपी प्लैटिनम
                                        </span>
                                    <?php elseif (isset($item['plan_type']) && $item['plan_type'] === 'GOLD'): ?>
                                        <span class="badge bg-primary text-white fw-bold px-2.5 py-1 rounded-pill small shadow-xs">
                                            <i class="bi bi-patch-check-fill me-1"></i> गोल्ड बिजनेस
                                        </span>
                                    <?php elseif ($item['is_verified'] === 'YES'): ?>
                                        <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> सत्यापित</span>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <h3 class="fw-bold text-dark mb-1 font-heading fs-5">
                                <a href="<?php echo getListingUrl($item['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                    <?php echo sanitizeInput($itemTitle); ?>
                                </a>
                            </h3>

                            <div class="text-muted small mb-2">
                                <i class="bi bi-geo-alt me-1 text-primary"></i><?php echo sanitizeInput(formatListingLocation($item, 'hi')); ?>
                            </div>

                            <?php if (!empty($item['description'])): ?>
                                <p class="small text-secondary mb-3 text-truncate-2" style="line-height: 1.5;">
                                    <?php echo sanitizeInput($item['description']); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="border-top pt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <?php echo renderStarRating($item['star_rating']); ?>
                            </div>
                            <div class="d-flex gap-2 w-100-mobile">
                                <?php if (isMobileNumberVisibleToVisitor($item)): ?>
                                    <?php if (!empty($item['whatsapp'])): ?>
                                        <a href="https://wa.me/91<?php echo sanitizeInput($item['whatsapp']); ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 py-1.5 flex-grow-1 flex-md-grow-0">
                                            <i class="bi bi-whatsapp me-1"></i> व्हाट्सएप
                                        </a>
                                    <?php endif; ?>
                                    <a href="tel:<?php echo sanitizeInput($item['mobile']); ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-1.5 flex-grow-1 flex-md-grow-0">
                                        <i class="bi bi-telephone-fill me-1"></i> कॉल करें
                                    </a>
                                <?php else: ?>
                                    <a href="login.php?redirect=<?php echo urlencode('listing/' . $item['slug']); ?>" class="btn btn-sm btn-outline-warning rounded-pill px-3 py-1.5 flex-grow-1 flex-md-grow-0 text-dark fw-semibold" title="पूरा नंबर देखने के लिए लॉग इन करें">
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
                    <i class="bi bi-search text-muted display-4 mb-3 d-block"></i>
                    <h4 class="fw-bold text-dark">कोई सूची नहीं मिली</h4>
                    <p class="text-muted mb-4"><?php echo sanitizeInput($catHindiName); ?> श्रेणी में अपनी प्रविष्टि दर्ज करने वाले पहले व्यक्ति बनें।</p>
                    <a href="add-contact.php" class="btn btn-warning rounded-pill px-4 py-2 fw-bold text-dark">
                        <i class="bi bi-plus-circle me-1"></i>मुफ्त लिस्टिंग जोड़ें
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
@media (max-width: 576px) {
    .w-100-mobile {
        width: 100%;
    }
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

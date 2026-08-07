<?php
require_once __DIR__ . '/includes/functions.php';

$q = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
$category_slug = isset($_GET['category']) ? sanitizeInput($_GET['category']) : '';
$sub_slug = isset($_GET['sub']) ? sanitizeInput($_GET['sub']) : '';
$block_slug = isset($_GET['block']) ? sanitizeInput($_GET['block']) : '';

$page_title = "निर्देशिका खोजें – सारण इंडेक्स";
if (!empty($q)) {
    $page_title = "'$q' के लिए खोज परिणाम – सारण इंडेक्स";
}

require_once __DIR__ . '/includes/header.php';

$blocks = getBlocks();
$categories = getCategories();
$listings = getListings($q, $category_slug, $block_slug, 50, 0, $sub_slug);
$censusVillages = !empty($q) ? getCensusVillages($block_slug, $q, 6, 0) : [];
?>

<div class="bg-dark text-white py-4">
    <div class="container">
        <h1 class="fw-bold font-heading mb-2 fs-3">निर्देशिका खोजें</h1>
        <p class="text-white-50 small mb-0">सारण जिले (छपरा) में स्थानीय व्यवसायों, वकीलों, डॉक्टरों, स्कूलों और सरकारी कार्यालयों की खोज करें।</p>
    </div>
</div>

<div class="container py-4">
    <!-- Filter Bar -->
    <div class="bg-white p-3 rounded-4 shadow-sm border mb-4">
        <form action="search.php" method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted mb-1">खोज कीवर्ड</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0 bg-light" placeholder="उदा. डॉक्टर, वकील, अस्पताल, स्कूल..." value="<?php echo sanitizeInput($q); ?>">
                </div>
            </div>
            
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">मुख्य श्रेणी</label>
                <select name="category" class="form-select bg-light">
                    <option value="">सभी श्रेणियां</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo sanitizeInput($cat['slug']); ?>" <?php echo ($category_slug === $cat['slug']) ? 'selected' : ''; ?>>
                            <?php echo sanitizeInput(!empty($cat['hindi_name']) ? $cat['hindi_name'] : $cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">प्रखंड चुनें</label>
                <select name="block" class="form-select bg-light">
                    <option value="">सभी 20 सारण प्रखंड</option>
                    <?php foreach ($blocks as $blk): ?>
                        <option value="<?php echo sanitizeInput($blk['slug']); ?>" <?php echo ($block_slug === $blk['slug']) ? 'selected' : ''; ?>>
                            <?php echo sanitizeInput(!empty($blk['hindi_name']) ? $blk['hindi_name'] : $blk['block_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 text-end">
                <label class="form-label d-none d-md-block opacity-0 mb-1">खोजें</label>
                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                    <i class="bi bi-search me-1"></i>खोजें
                </button>
            </div>
        </form>
    </div>

    <!-- Census Villages Matching Results Section -->
    <?php if (!empty($censusVillages)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-light">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold text-dark font-heading mb-0">
                    <i class="bi bi-houses-fill text-primary me-2"></i>मिलते-जुलते 2011 जनगणना गाँव (<?php echo count($censusVillages); ?>)
                </h5>
                <a href="village.php?search=<?php echo urlencode($q); ?>" class="btn btn-sm btn-outline-primary rounded-pill">सभी गाँव देखें</a>
            </div>

            <div class="row g-3">
                <?php foreach ($censusVillages as $cv): 
                    $cvTitle = !empty($cv['name_hindi']) ? $cv['name_hindi'] : $cv['name'];
                ?>
                    <div class="col-md-4">
                        <div class="p-3 bg-white rounded-3 border h-100 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-bold text-dark mb-0 font-heading">
                                    <a href="village/<?php echo sanitizeInput($cv['town_village_code']); ?>" class="text-dark text-decoration-none hover-primary">
                                        <?php echo sanitizeInput($cvTitle); ?>
                                    </a>
                                </h6>
                                <span class="text-muted fs-7"><?php echo sanitizeInput($cv['block_name']); ?> प्रखंड • कोड: <?php echo sanitizeInput($cv['town_village_code']); ?></span>
                            </div>
                            <a href="village/<?php echo sanitizeInput($cv['town_village_code']); ?>" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-chevron-right"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Results Section -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold font-heading text-dark mb-0">
            खोज परिणाम (<?php echo count($listings); ?> परिणाम मिले)
        </h5>
        <?php if (!empty($q) || !empty($category_slug) || !empty($block_slug)): ?>
            <a href="search.php" class="btn btn-sm btn-outline-secondary rounded-pill">
                <i class="bi bi-x-circle me-1"></i>फ़िल्टर हटाएं
            </a>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php if (!empty($listings)): ?>
            <?php foreach ($listings as $item): 
                $itemTitle = !empty($item['hindi_title']) ? $item['hindi_title'] : $item['title'];
                $itemSubTitle = !empty($item['hindi_title']) ? $item['title'] : '';
            ?>
                <div class="col-lg-6">
                    <div class="listing-card p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                                        <?php echo sanitizeInput($item['category_name']); ?>
                                    </span>
                                </div>
                                <?php if ($item['is_verified'] === 'YES'): ?>
                                    <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> सत्यापित</span>
                                <?php endif; ?>
                            </div>

                            <h4 class="fw-bold text-dark mb-1 font-heading fs-5">
                                <a href="<?php echo getListingUrl($item['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                    <?php echo sanitizeInput($itemTitle); ?>
                                </a>
                            </h4>
                            <?php if (!empty($itemSubTitle)): ?>
                                <div class="text-muted small fw-medium mb-2"><?php echo sanitizeInput($itemSubTitle); ?></div>
                            <?php endif; ?>

                            <div class="text-muted small mb-3">
                                <i class="bi bi-geo-alt me-1 text-primary"></i><?php echo sanitizeInput(formatListingLocation($item, 'hi')); ?>
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
                                            <i class="bi bi-whatsapp"></i> व्हाट्सएप
                                        </a>
                                    <?php endif; ?>
                                    <a href="tel:<?php echo sanitizeInput($item['mobile']); ?>" class="btn-call">
                                        <i class="bi bi-telephone-fill"></i> कॉल करें
                                    </a>
                                <?php else: ?>
                                    <a href="login.php?redirect=<?php echo urlencode('listing/' . $item['slug']); ?>" class="btn-call bg-warning-subtle text-dark border-warning-subtle text-decoration-none" title="पूरा नंबर देखने के लिए लॉग इन करें">
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
                    <h4 class="fw-bold text-dark">कोई परिणाम नहीं मिला</h4>
                    <p class="text-muted mb-4">आपकी खोज के अनुसार कोई लिस्टिंग नहीं मिली। कृपया फ़िल्टर बदलें या दूसरा कीवर्ड खोजें।</p>
                    <a href="search.php" class="btn btn-primary rounded-pill px-4 me-2">सभी लिस्टिंग देखें</a>
                    <a href="add-contact.php" class="btn btn-warning rounded-pill px-4 fw-bold text-dark">निःशुल्क लिस्टिंग जोड़ें</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

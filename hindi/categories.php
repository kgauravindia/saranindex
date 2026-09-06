<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "सारण जिले की सभी श्रेणियां व व्यवसायिक क्षेत्र – सारण इंडेक्स";
$meta_description = "सारण (छपरा) जिले के सभी 20 प्रखंडों में सभी 30 श्रेणियों एवं 270+ उप-श्रेणियों की सत्यापित निर्देशिका देखें।";
require_once __DIR__ . '/includes/header.php';

$db = getDB();
$categories_data = [];
if ($db) {
    try {
        $categories_data = $db->query("SELECT c.*, COUNT(l.id) as listing_count 
            FROM categories c 
            LEFT JOIN listings l ON c.id = l.category_id AND l.status = 'ACTIVE' 
            WHERE c.status = 'ACTIVE'
            GROUP BY c.id, c.name, c.hindi_name, c.icon, c.slug, c.section, c.status
            ORDER BY listing_count DESC, c.name ASC")->fetchAll();
    } catch (Exception $e) {
        $categories_data = getCategories();
    }
} else {
    $categories_data = getCategories();
}
?>

<!-- Category Hero Banner (Hindi) -->
<div class="bg-primary text-white py-4 py-md-5 text-center position-relative shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%) !important;">
    <div class="container px-3">
        <div class="d-inline-flex align-items-center bg-white text-primary fw-bold px-3 py-1 rounded-pill mb-2 shadow-sm fs-7">
            <i class="bi bi-grid-fill me-1.5"></i>सम्पूर्ण निर्देशिका
        </div>
        <h1 class="fw-bolder font-heading text-white display-6 mb-1">सभी श्रेणियां एवं व्यावसायिक क्षेत्र</h1>
        <p class="lead text-white-50 fs-6 mb-0 mx-auto" style="max-width: 650px;">
            सारण जिले के सभी <?php echo count($categories_data); ?> व्यापारिक, नागरिक, शैक्षणिक व स्वास्थ्य क्षेत्रों की सत्यापित निर्देशिका।
        </p>
    </div>
</div>

<div class="container py-4 py-md-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold font-heading text-dark fs-4 mb-0">सभी <?php echo count($categories_data); ?> श्रेणियां देखें</h2>
            <p class="text-muted small mb-0">प्रत्येक श्रेणी में सत्यापित स्थानीय संपर्क, फोन नंबर और पते प्राप्त करें।</p>
        </div>
        <a href="categories" class="btn btn-outline-primary rounded-pill btn-sm px-3 fw-bold">
            <i class="bi bi-globe me-1"></i>View in English
        </a>
    </div>

    <div class="row g-4">
        <?php foreach ($categories_data as $cat): ?>
            <?php 
            $subcats = getSubcategoriesByCategoryId($cat['id']); 
            $count = intval($cat['listing_count'] ?? 0);
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border rounded-4 shadow-sm p-4 d-flex flex-column justify-content-between hover-shadow transition-all bg-white">
                    <div>
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="category-icon-wrapper" style="width: 52px; height: 52px; background: rgba(37, 99, 235, 0.1); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #2563eb;">
                                <i class="bi <?php echo sanitizeInput($cat['icon']); ?>"></i>
                            </div>
                            <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1.5 rounded-pill small">
                                <?php echo $count; ?> निर्देशिकाएं
                            </span>
                        </div>

                        <h3 class="fw-bold font-heading fs-5 mb-1">
                            <a href="<?php echo 'category/' . rawurlencode($cat['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                <?php echo sanitizeInput(!empty($cat['hindi_name']) ? $cat['hindi_name'] : $cat['name']); ?>
                            </a>
                        </h3>
                        <div class="text-secondary small mb-3">
                            <i class="bi bi-globe me-1"></i><?php echo sanitizeInput($cat['name']); ?>
                        </div>

                        <?php if (!empty($subcats)): ?>
                            <div class="mb-3">
                                <div class="small text-muted fw-semibold mb-1 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.03em;">प्रमुख उप-श्रेणियां:</div>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php $sub_limit = 0; foreach ($subcats as $sc): if (++$sub_limit > 4) break; ?>
                                        <a href="<?php echo 'category/' . rawurlencode($cat['slug']) . '/' . rawurlencode($sc['slug']); ?>" class="badge bg-light text-dark border text-decoration-none small py-1 px-2 rounded-pill hover-bg-primary">
                                            <?php echo sanitizeInput(!empty($sc['hindi_name']) ? $sc['hindi_name'] : $sc['name']); ?>
                                        </a>
                                    <?php endforeach; ?>
                                    <?php if (count($subcats) > 4): ?>
                                        <span class="badge bg-light text-muted small py-1 px-2 rounded-pill">+<?php echo count($subcats) - 4; ?> अन्य</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="border-top pt-3 mt-2 d-flex align-items-center justify-content-between">
                        <span class="text-muted small"><?php echo count($subcats); ?> उप-श्रेणियां</span>
                        <a href="<?php echo 'category/' . rawurlencode($cat['slug']); ?>" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                            देखें <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : '';
$page_title = "ग्राम पंचायत निर्देशिका – सारण इंडेक्स";
$meta_description = "सारण जिले (बिहार) की ग्राम पंचायतों, स्थानीय सेवाओं, स्कूलों और स्वास्थ्य केंद्रों की निर्देशिका।";

$db = getDB();
$panchayat = null;
if (!empty($slug) && $db) {
    try {
        $stmt = $db->prepare("SELECT p.*, b.block_name, b.hindi_name as block_hindi FROM panchayats p JOIN blocks b ON p.block_id = b.id WHERE p.slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $panchayat = $stmt->fetch();
    } catch (PDOException $e) {}
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-dark text-white py-5 text-center">
    <div class="container">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2">ग्राम पंचायत निर्देशिका</span>
        <h1 class="fw-bolder font-heading text-white display-5 mb-2">
            <?php 
                if ($panchayat) {
                    $pName = !empty($panchayat['hindi_name']) ? $panchayat['hindi_name'] : $panchayat['panchayat_name'];
                    echo sanitizeInput($pName) . " ग्राम पंचायत";
                } else {
                    echo "सारण ग्राम पंचायत निर्देशिका";
                }
            ?>
        </h1>
        <?php if ($panchayat): 
            $bName = !empty($panchayat['block_hindi']) ? $panchayat['block_hindi'] : $panchayat['block_name'];
        ?>
            <p class="text-white-50 lead mb-0">प्रखंड: <?php echo sanitizeInput($bName); ?> • सारण जिला</p>
        <?php else: ?>
            <p class="text-white-50 lead mb-0">सारण की ग्राम पंचायतों एवं स्थानीय निर्देशिका की खोज करें।</p>
        <?php endif; ?>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-12 text-center">
            <h4 class="fw-bold text-dark font-heading">स्थानीय सेवाएं एवं मुख्य संस्थान</h4>
            <p class="text-muted">सत्यापित स्थानीय संपर्क, पुलिस थाने, स्वास्थ्य केंद्र और स्कूल खोजें।</p>
        </div>
        <?php 
        $listings = getListings('', '', '', 20, 0);
        foreach ($listings as $item): 
            $itemTitle = !empty($item['hindi_title']) ? $item['hindi_title'] : $item['title'];
            $itemSubTitle = !empty($item['hindi_title']) ? $item['title'] : '';
        ?>
            <div class="col-lg-6">
                <div class="listing-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                                <?php echo sanitizeInput($item['category_name']); ?>
                            </span>
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
                            <?php if (!empty($item['whatsapp']) && isMobileNumberVisibleToVisitor($item)): ?>
                                <a href="https://wa.me/91<?php echo sanitizeInput($item['whatsapp']); ?>" target="_blank" class="btn-whatsapp">
                                    <i class="bi bi-whatsapp"></i> व्हाट्सएप
                                </a>
                            <?php endif; ?>
                            <?php if (isMobileNumberVisibleToVisitor($item)): ?>
                                <a href="tel:<?php echo sanitizeInput($item['mobile']); ?>" class="btn-call">
                                    <i class="bi bi-telephone-fill"></i> कॉल करें
                                </a>
                            <?php else: ?>
                                <a href="../login.php?redirect=<?php echo urlencode('hindi/panchayat.php?slug=' . $panchayat['slug']); ?>" class="btn-call text-muted" title="नंबर देखने के लिए लॉग इन करें">
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>

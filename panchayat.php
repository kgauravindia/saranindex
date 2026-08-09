<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : '';
$page_title = "Panchayat Directory – Saran Index";
$meta_description = "Directory of Panchayats, local government, schools, health centers, and services in Saran District, Bihar.";

$db = getDB();
$panchayat = null;
if (!empty($slug) && $db) {
    try {
        $stmt = $db->prepare("SELECT p.*, b.block_name FROM panchayats p JOIN blocks b ON p.block_id = b.id WHERE p.slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $panchayat = $stmt->fetch();
    } catch (PDOException $e) {}
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-dark text-white py-5 text-center">
    <div class="container">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2">Gram Panchayat Directory</span>
        <h1 class="fw-bolder font-heading text-white display-5 mb-2">
            <?php echo $panchayat ? sanitizeInput($panchayat['panchayat_name']) . " Gram Panchayat" : "Saran Panchayats Directory"; ?>
        </h1>
        <?php if ($panchayat): ?>
            <p class="text-white-50 lead mb-0">Block: <?php echo sanitizeInput($panchayat['block_name']); ?> • Saran District</p>
        <?php else: ?>
            <p class="text-white-50 lead mb-0">Explore Panchayats and localized directory listings in Saran.</p>
        <?php endif; ?>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-12 text-center">
            <h4 class="fw-bold text-dark font-heading">Local Services & Key Institutions</h4>
            <p class="text-muted">Find verified local contacts, police stations, health centers, and schools.</p>
        </div>
        <?php 
        $listings = getListings('', '', '', 20, 0);
        foreach ($listings as $item): 
        ?>
            <div class="col-lg-6">
                <div class="listing-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                                <?php echo sanitizeInput($item['category_name']); ?>
                            </span>
                            <?php if ($item['is_verified'] === 'YES'): ?>
                                <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Verified</span>
                            <?php endif; ?>
                        </div>

                        <h4 class="fw-bold text-dark mb-1 font-heading fs-5">
                            <a href="<?php echo getListingUrl($item['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                <?php echo sanitizeInput($item['title']); ?>
                            </a>
                        </h4>
                        <div class="text-muted small mb-3">
                            <i class="bi bi-geo-alt me-1 text-primary"></i><?php echo sanitizeInput(formatListingLocation($item)); ?>
                        </div>
                    </div>

                    <div class="border-top pt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <?php echo renderStarRating($item['star_rating']); ?>
                        </div>
                        <?php if (isMobileNumberVisibleToVisitor($item)): ?>
                            <a href="tel:<?php echo sanitizeInput($item['mobile']); ?>" class="btn-call">
                                <i class="bi bi-telephone-fill"></i> Call
                            </a>
                        <?php else: ?>
                            <a href="login.php?redirect=<?php echo urlencode('panchayat.php?slug=' . $panchayat['slug']); ?>" class="btn-call text-muted" title="Log in to view mobile number">
                                <i class="bi bi-lock-fill text-warning me-1"></i><?php echo sanitizeInput(maskPhoneNumber($item['mobile'])); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

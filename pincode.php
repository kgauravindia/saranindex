<?php
require_once __DIR__ . '/includes/functions.php';

$code = isset($_GET['code']) ? sanitizeInput($_GET['code']) : '841301';
$page_title = "PIN Code " . $code . " Directory – Saran Index";
$meta_description = "Businesses, doctors, advocates, schools, and emergency numbers in PIN Code " . $code . ", Saran District, Chapra.";

require_once __DIR__ . '/includes/header.php';
$listings = getListings('', '', '', 20, 0);
?>

<div class="bg-dark text-white py-5 text-center">
    <div class="container">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2">Postal Code Directory</span>
        <h1 class="fw-bolder font-heading text-white display-5 mb-2">PIN Code: <?php echo sanitizeInput($code); ?></h1>
        <p class="text-white-50 lead mb-0">Verified listings, institutions, and emergency services in Saran (Chapra).</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($listings as $item): ?>
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
                            <i class="bi bi-geo-alt me-1 text-primary"></i><?php echo sanitizeInput(formatListingLocation($item)); ?> (PIN: <?php echo sanitizeInput($code); ?>)
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
                            <a href="login.php?redirect=<?php echo urlencode('pincode.php?code=' . $code); ?>" class="btn-call text-muted" title="Log in to view mobile number">
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

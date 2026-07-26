<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : '';
$page_title = "Village Directory – Saran Index";
$meta_description = "Village listings, local shops, emergency services, and PIN codes in Saran District, Bihar.";

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-primary text-white py-5 text-center">
    <div class="container">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2">Village & Grassroot Directory</span>
        <h1 class="fw-bolder font-heading text-white display-5 mb-2">Village Directory of Saran</h1>
        <p class="text-white-50 lead mb-0">Connecting every village in Chapra, Marhaura, Sonepur, and across Saran District digitally.</p>
    </div>
</div>

<div class="container py-5">
    <div class="text-center mb-4">
        <h4 class="fw-bold text-dark font-heading">Key Local Contacts</h4>
        <p class="text-muted">Find verified phone numbers, emergency helplines, and local services.</p>
    </div>

    <div class="row g-4">
        <?php 
        $listings = getListings('', '', '', 10, 0);
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
                            <a href="profile.php?slug=<?php echo sanitizeInput($item['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                <?php echo sanitizeInput($item['title']); ?>
                            </a>
                        </h4>
                        <div class="text-muted small mb-3">
                            <i class="bi bi-geo-alt me-1 text-primary"></i><?php echo sanitizeInput($item['address']); ?>
                        </div>
                    </div>

                    <div class="border-top pt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <?php echo renderStarRating($item['star_rating']); ?>
                        </div>
                        <a href="tel:<?php echo sanitizeInput($item['mobile']); ?>" class="btn-call">
                            <i class="bi bi-telephone-fill"></i> Call
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

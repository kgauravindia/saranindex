<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = "All 20 Blocks of Saran District – Saran Index";
require_once __DIR__ . '/includes/header.php';

$blocks = getBlocks();
?>

<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill uppercase mb-2">Administrative Directory</span>
        <h1 class="fw-bolder font-heading text-white display-5 mb-2">All 20 Blocks of Saran District</h1>
        <p class="text-white-50 lead mx-auto mb-0" style="max-width: 600px;">Explore local businesses, panchayats, advocates, doctors, schools, and emergency contacts in every block of Saran (Chapra).</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($blocks as $blk): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-3 hover-lift">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-primary-subtle text-primary p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-geo-alt-fill fs-4"></i>
                        </div>
                        <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">PIN: <?php echo sanitizeInput($blk['pincode']); ?></span>
                    </div>

                    <h4 class="fw-bold text-dark font-heading mb-1"><?php echo sanitizeInput($blk['block_name']); ?></h4>
                    <div class="text-muted small fw-medium mb-3"><?php echo sanitizeInput($blk['hindi_name']); ?></div>

                    <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto">
                        <span class="text-muted small"><i class="bi bi-diagram-3 me-1"></i><?php echo sanitizeInput($blk['total_panchayats']); ?> Panchayats</span>
                        <a href="search.php?block=<?php echo sanitizeInput($blk['slug']); ?>" class="btn btn-sm btn-primary rounded-pill fw-semibold px-3">
                            View Directory <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

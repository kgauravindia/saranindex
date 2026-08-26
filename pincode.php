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
        <p class="text-white-50 lead mb-0">Verified listings, institutions, and emergency services in Saran District.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($listings as $item): ?>
            <div class="col-lg-6">
                <?php echo renderListingCard($item, ['pincode' => $code]); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

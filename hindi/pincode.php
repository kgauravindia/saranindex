<?php
require_once __DIR__ . '/includes/functions.php';

$code = isset($_GET['code']) ? sanitizeInput($_GET['code']) : '841301';
$page_title = "पिन कोड " . $code . " निर्देशिका – सारण इंडेक्स";
$meta_description = "पिन कोड " . $code . " (सारण जिला, छपरा) में दुकानें, डॉक्टर, वकील, स्कूल एवं आपातकालीन नंबर।";

require_once __DIR__ . '/includes/header.php';
$listings = getListings('', '', '', 20, 0);
?>

<div class="bg-dark text-white py-5 text-center">
    <div class="container">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2">पिन कोड निर्देशिका</span>
        <h1 class="fw-bolder font-heading text-white display-5 mb-2">पिन कोड: <?php echo sanitizeInput($code); ?></h1>
        <p class="text-white-50 lead mb-0">सारण (छपरा) में सत्यापित लिस्टिंग, संस्थान एवं आपातकालीन सेवाएं।</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($listings as $item): ?>
            <div class="col-lg-6">
                <?php echo renderListingCard($item, ['lang' => 'hi', 'pincode' => $code]); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

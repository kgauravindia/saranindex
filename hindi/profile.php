<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : 'sadar-hospital-chapra';
$listing = getListingBySlug($slug);

if (!$listing) {
    $listings = getListings('', '', '', 1);
    $listing = !empty($listings) ? $listings[0] : null;
}

$review_success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_review') {
    $reviewer_name = isset($_POST['reviewer_name']) ? sanitizeInput($_POST['reviewer_name']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
    $comment = isset($_POST['comment']) ? sanitizeInput($_POST['comment']) : '';

    if (!empty($reviewer_name) && !empty($comment)) {
        addReview($listing['id'], $reviewer_name, $rating, $comment);
        $review_success = true;
    }
}

$reviews = getReviewsByListingId($listing['id']);

$listingTitle = !empty($listing['hindi_title']) ? $listing['hindi_title'] : $listing['title'];
$listingSubTitle = !empty($listing['hindi_title']) ? $listing['title'] : '';

$page_title = $listingTitle . " – सारण इंडेक्स निर्देशिका";
$meta_description = $listingTitle . " (" . $listing['block_name'] . ", सारण जिला) के संपर्क विवरण, फोन नंबर, पता और सेवाएं।";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Profile Hero Header -->
<div class="bg-dark text-white py-5 position-relative">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none">मुख्य पृष्ठ</a></li>
                <li class="breadcrumb-item"><a href="search.php" class="text-white-50 text-decoration-none">निर्देशिका</a></li>
                <li class="breadcrumb-item text-warning active" aria-current="page"><?php echo sanitizeInput($listing['block_name']); ?></li>
            </ol>
        </nav>

        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="badge bg-primary px-3 py-1.5 rounded-pill fw-semibold"><?php echo sanitizeInput($listing['category_name']); ?></span>
                    <?php if ($listing['is_verified'] === 'YES'): ?>
                        <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> सत्यापित प्रविष्टि</span>
                    <?php endif; ?>
                </div>

                <h1 class="fw-bolder font-heading text-white display-5 mb-2">
                    <?php echo sanitizeInput($listingTitle); ?>
                </h1>
                <?php if (!empty($listingSubTitle)): ?>
                    <h4 class="text-white-50 fw-normal mb-3"><?php echo sanitizeInput($listingSubTitle); ?></h4>
                <?php endif; ?>

                <div class="d-flex align-items-center gap-3 text-white-50 flex-wrap">
                    <div><i class="bi bi-geo-alt me-1 text-primary"></i><?php echo sanitizeInput($listing['address']); ?></div>
                    <div>•</div>
                    <div><i class="bi bi-building me-1 text-warning"></i>प्रखंड: <?php echo sanitizeInput($listing['block_name']); ?></div>
                </div>
            </div>

            <div class="col-lg-4 text-lg-end">
                <div class="d-flex gap-2 justify-content-lg-end">
                    <?php if (!empty($listing['whatsapp'])): ?>
                        <a href="https://wa.me/91<?php echo sanitizeInput($listing['whatsapp']); ?>" target="_blank" class="btn btn-success btn-lg rounded-pill px-4 fw-bold shadow">
                            <i class="bi bi-whatsapp me-2"></i>व्हाट्सएप
                        </a>
                    <?php endif; ?>
                    <a href="tel:<?php echo sanitizeInput($listing['mobile']); ?>" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow">
                        <i class="bi bi-telephone-fill me-2"></i>कॉल करें
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold font-heading text-dark mb-3">विवरण एवं सेवाएं</h4>
                <p class="text-secondary" style="line-height: 1.8;">
                    <?php echo nl2br(sanitizeInput($listing['description'])); ?>
                </p>

                <?php if (!empty($listing['services'])): ?>
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold text-dark mb-3">मुख्य सेवाएं एवं सुविधाएं</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach (explode(',', $listing['services']) as $srv): ?>
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-normal fs-7">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i><?php echo sanitizeInput(trim($srv)); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Customer Reviews Section -->
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h4 class="fw-bold font-heading text-dark mb-4">समीक्षाएं एवं रेटिंग (Reviews)</h4>

                <?php if ($review_success): ?>
                    <div class="alert alert-success rounded-3 mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i>आपकी समीक्षा जमा कर दी गई है!
                    </div>
                <?php endif; ?>

                <?php if (!empty($reviews)): ?>
                    <div class="d-flex flex-column gap-3 mb-4">
                        <?php foreach ($reviews as $rev): ?>
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <strong class="text-dark"><?php echo sanitizeInput($rev['reviewer_name']); ?></strong>
                                    <div><?php echo renderStarRating($rev['rating']); ?></div>
                                </div>
                                <p class="mb-0 text-secondary small"><?php echo sanitizeInput($rev['comment']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-4">अभी तक कोई समीक्षा नहीं है। अपनी समीक्षा सबसे पहले दें!</p>
                <?php endif; ?>

                <!-- Add Review Form -->
                <h5 class="fw-bold text-dark mb-3">समीक्षा लिखें</h5>
                <form action="" method="POST">
                    <input type="hidden" name="action" value="add_review">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">आपका नाम</label>
                            <input type="text" name="reviewer_name" class="form-control bg-light" placeholder="पूरा नाम" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">रेटिंग</label>
                            <select name="rating" class="form-select bg-light">
                                <option value="5">⭐⭐⭐⭐⭐ (5/5 श्रेष्ठ)</option>
                                <option value="4">⭐⭐⭐⭐ (4/5 बहुत अच्छा)</option>
                                <option value="3">⭐⭐⭐ (3/5 अच्छा)</option>
                                <option value="2">⭐⭐ (2/5 औसत)</option>
                                <option value="1">⭐ (1/5 खराब)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">टिप्पणी</label>
                            <textarea name="comment" class="form-control bg-light" rows="3" placeholder="अपना अनुभव साझा करें..." required></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">समीक्षा जमा करें</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px;">
                <h5 class="fw-bold font-heading text-dark mb-4">संपर्क जानकारी</h5>

                <div class="d-flex mb-3">
                    <i class="bi bi-telephone-fill text-primary fs-5 me-3"></i>
                    <div>
                        <strong class="d-block text-dark small">मोबाइल नंबर</strong>
                        <a href="tel:<?php echo sanitizeInput($listing['mobile']); ?>" class="text-primary fw-bold text-decoration-none"><?php echo sanitizeInput($listing['mobile']); ?></a>
                    </div>
                </div>

                <?php if (!empty($listing['whatsapp'])): ?>
                    <div class="d-flex mb-3">
                        <i class="bi bi-whatsapp text-success fs-5 me-3"></i>
                        <div>
                            <strong class="d-block text-dark small">व्हाट्सएप संपर्क</strong>
                            <a href="https://wa.me/91<?php echo sanitizeInput($listing['whatsapp']); ?>" target="_blank" class="text-success fw-bold text-decoration-none"><?php echo sanitizeInput($listing['whatsapp']); ?></a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($listing['email'])): ?>
                    <div class="d-flex mb-3">
                        <i class="bi bi-envelope-fill text-muted fs-5 me-3"></i>
                        <div>
                            <strong class="d-block text-dark small">ईमेल</strong>
                            <span class="text-muted small"><?php echo sanitizeInput($listing['email']); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="d-flex mb-3">
                    <i class="bi bi-geo-alt-fill text-danger fs-5 me-3"></i>
                    <div>
                        <strong class="d-block text-dark small">पता एवं पिन कोड</strong>
                        <span class="text-secondary small"><?php echo sanitizeInput($listing['address']); ?><?php echo !empty($listing['pincode']) ? ' - ' . sanitizeInput($listing['pincode']) : ''; ?></span>
                    </div>
                </div>
            </div>

            <!-- Share & Claim Box -->
            <div class="card border-0 shadow-sm rounded-4 bg-light text-center p-3 mt-3">
                <div class="small text-muted mb-2">क्या यह आपका व्यवसाय या संगठन है?</div>
                <a href="add-contact.php" class="btn btn-outline-primary rounded-pill btn-sm fw-bold">लिस्टिंग पर दावा करें और जानकारी अपडेट करें</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : 'sadar-hospital-chapra';
$listing = getListingBySlug($slug);

if (!$listing) {
    // Fallback to first listing if slug not found
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

$page_title = $listing['title'] . " – Saran Index Directory";
$meta_description = "Contact details, phone number, address, services, and map for " . $listing['title'] . " in " . $listing['block_name'] . ", Saran District.";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Profile Hero Header -->
<div class="bg-dark text-white py-5 position-relative">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="search.php" class="text-white-50 text-decoration-none">Directory</a></li>
                <li class="breadcrumb-item text-warning active" aria-current="page"><?php echo sanitizeInput($listing['block_name']); ?></li>
            </ol>
        </nav>

        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="badge bg-primary px-3 py-1.5 rounded-pill fw-semibold"><?php echo sanitizeInput($listing['category_name']); ?></span>
                    <?php if (isset($listing['plan_type']) && $listing['plan_type'] === 'PLATINUM'): ?>
                        <span class="vip-platinum-badge"><i class="bi bi-crown-fill me-1"></i> VIP Platinum</span>
                    <?php elseif (isset($listing['plan_type']) && $listing['plan_type'] === 'GOLD'): ?>
                        <span class="gold-business-badge"><i class="bi bi-patch-check-fill me-1"></i> Gold Business</span>
                    <?php elseif ($listing['is_verified'] === 'YES'): ?>
                        <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Verified Listing</span>
                    <?php endif; ?>
                </div>


                <h1 class="fw-bolder font-heading text-white display-5 mb-2">
                    <?php echo sanitizeInput($listing['title']); ?>
                </h1>
                <?php if (!empty($listing['hindi_title'])): ?>
                    <h4 class="text-white-50 fw-normal mb-3"><?php echo sanitizeInput($listing['hindi_title']); ?></h4>
                <?php endif; ?>

                <div class="d-flex align-items-center gap-3 text-white-50 flex-wrap">
                    <div><i class="bi bi-geo-alt text-warning me-1"></i><?php echo sanitizeInput($listing['block_name']); ?>, Saran District</div>
                    <div><i class="bi bi-star-fill text-warning me-1"></i><?php echo number_format($listing['star_rating'], 1); ?> Rating</div>
                </div>
            </div>

            <div class="col-lg-4 text-lg-end">
                <div class="d-flex flex-column gap-2 justify-content-lg-end">
                    <a href="tel:<?php echo sanitizeInput($listing['mobile']); ?>" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                        <i class="bi bi-telephone-fill me-2"></i>Call <?php echo sanitizeInput($listing['mobile']); ?>
                    </a>
                    <?php if (!empty($listing['whatsapp'])): ?>
                        <a href="https://wa.me/91<?php echo sanitizeInput($listing['whatsapp']); ?>" target="_blank" class="btn btn-success btn-lg rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-whatsapp me-2"></i>WhatsApp Direct Message
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Main Details Column -->
        <div class="col-lg-8">
            <!-- About Section -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold font-heading text-dark mb-3">Overview & Description</h4>
                    <p class="text-secondary" style="line-height: 1.7; font-size: 1.05rem;">
                        <?php echo sanitizeInput($listing['description']); ?>
                    </p>

                    <?php if (!empty($listing['services'])): ?>
                        <h5 class="fw-bold font-heading text-dark mt-4 mb-3">Services & Key Features</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <?php 
                            $servicesList = explode(',', $listing['services']);
                            foreach ($servicesList as $srv): 
                            ?>
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill font-body fw-medium" style="font-size: 0.9rem;">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i><?php echo sanitizeInput(trim($srv)); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Customer Reviews Section -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="fw-bold font-heading text-dark mb-0">Ratings & Reviews</h4>
                        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill">
                            <i class="bi bi-star-fill me-1"></i><?php echo number_format($listing['star_rating'], 1); ?> / 5.0
                        </span>
                    </div>

                    <?php if ($review_success): ?>
                        <div class="alert alert-success rounded-3 p-3 small mb-4">
                            <i class="bi bi-check-circle-fill me-1"></i>Thank you for submitting your review!
                        </div>
                    <?php endif; ?>

                    <!-- Add Review Form -->
                    <form action="profile.php?slug=<?php echo sanitizeInput($listing['slug']); ?>" method="POST" class="bg-light p-3 rounded-4 border mb-4">
                        <input type="hidden" name="action" value="add_review">
                        <h6 class="fw-bold text-dark mb-2">Write a Customer Review</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" name="reviewer_name" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <select name="rating" class="form-select">
                                    <option value="5">5 Stars - Excellent</option>
                                    <option value="4">4 Stars - Very Good</option>
                                    <option value="3">3 Stars - Good</option>
                                    <option value="2">2 Stars - Average</option>
                                    <option value="1">1 Star - Poor</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <textarea name="comment" class="form-control" rows="2" placeholder="Write your experience or feedback..." required></textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary rounded-pill btn-sm fw-bold px-4">Submit Review</button>
                            </div>
                        </div>
                    </form>

                    <!-- Render Customer Reviews -->
                    <?php if (!empty($reviews)): ?>
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold text-dark mb-3">Recent Customer Reviews</h6>
                            <div class="d-flex flex-column gap-3">
                                <?php foreach ($reviews as $rev): ?>
                                    <div class="p-3 bg-white border rounded-3">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <strong class="text-dark small"><?php echo sanitizeInput($rev['reviewer_name']); ?></strong>
                                            <span class="text-warning small"><?php echo renderStarRating($rev['rating']); ?></span>
                                        </div>
                                        <p class="text-secondary small mb-1"><?php echo sanitizeInput($rev['comment']); ?></p>
                                        <div class="text-muted" style="font-size: 0.75rem;"><?php echo date('d M Y', strtotime($rev['created_at'])); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Info Column -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold font-heading text-dark mb-3">Contact Details</h5>
                    
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex mb-3">
                            <i class="bi bi-geo-alt-fill text-primary fs-5 me-3"></i>
                            <div>
                                <strong class="d-block text-dark small">Address</strong>
                                <span class="text-muted small"><?php echo sanitizeInput($listing['address']); ?>, PIN: <?php echo sanitizeInput($listing['pincode']); ?></span>
                            </div>
                        </li>

                        <li class="d-flex mb-3">
                            <i class="bi bi-telephone-fill text-primary fs-5 me-3"></i>
                            <div>
                                <strong class="d-block text-dark small">Phone Number</strong>
                                <a href="tel:<?php echo sanitizeInput($listing['mobile']); ?>" class="text-primary fw-bold text-decoration-none small"><?php echo sanitizeInput($listing['mobile']); ?></a>
                            </div>
                        </li>

                        <?php if (!empty($listing['email'])): ?>
                            <li class="d-flex mb-3">
                                <i class="bi bi-envelope-fill text-primary fs-5 me-3"></i>
                                <div>
                                    <strong class="d-block text-dark small">Email Address</strong>
                                    <a href="mailto:<?php echo sanitizeInput($listing['email']); ?>" class="text-muted text-decoration-none small"><?php echo sanitizeInput($listing['email']); ?></a>
                                </div>
                            </li>
                        <?php endif; ?>

                        <?php if (!empty($listing['website'])): ?>
                            <li class="d-flex mb-3">
                                <i class="bi bi-globe text-primary fs-5 me-3"></i>
                                <div>
                                    <strong class="d-block text-dark small">Website</strong>
                                    <a href="<?php echo sanitizeInput($listing['website']); ?>" target="_blank" class="text-primary text-decoration-none small">Visit Website <i class="bi bi-box-arrow-up-right ms-1"></i></a>
                                </div>
                            </li>
                        <?php endif; ?>

                        <li class="d-flex">
                            <i class="bi bi-clock-fill text-primary fs-5 me-3"></i>
                            <div>
                                <strong class="d-block text-dark small">Operating Hours</strong>
                                <span class="text-muted small"><?php echo sanitizeInput($listing['business_hours'] ?? '9:00 AM - 8:00 PM'); ?></span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Share & Claim Box -->
            <div class="card border-0 shadow-sm rounded-4 bg-light text-center p-3">
                <div class="small text-muted mb-2">Is this your business or organization?</div>
                <a href="add-contact.php" class="btn btn-outline-primary rounded-pill btn-sm fw-bold">Claim Listing & Update Info</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

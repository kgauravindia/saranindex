<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : 'sadar-hospital-chapra';
$listing = getListingBySlug($slug);

if (!$listing) {
    // Check if $slug matches a Category (e.g. /classifieds, /doctors-healthcare)
    $categories = getCategories();
    if (!empty($categories)) {
        foreach ($categories as $cat) {
            if (strcasecmp($cat['slug'], $slug) === 0) {
                $_GET['slug'] = $cat['slug'];
                require __DIR__ . '/category.php';
                exit;
            }
        }
    }

    // Show 404 Page if invalid slug / non-existent URL
    require __DIR__ . '/404.php';
    exit;
}

$review_success = false;
$review_updated = false;
$review_error = '';
$claim_success = false;
$claim_error = '';
$existing_user_review = null;

if (isUserLoggedIn()) {
    $loggedInUser = getLoggedInUser();
    $existing_user_review = hasUserReviewedListing($loggedInUser['id'], $listing['id'], $loggedInUser['mobile'], $loggedInUser['full_name']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_review') {
        if (!isUserLoggedIn()) {
            $review_error = "Only registered and logged-in users can write a review. Please log in first.";
        } elseif (($loggedInUser['mobile_status'] ?? 'UNVERIFIED') !== 'VERIFIED') {
            $review_error = "Your mobile number is unverified. Please verify your mobile number via OTP before submitting a review.";
        } elseif ($existing_user_review) {
            $review_error = "You have already submitted a review for this listing. You can edit your existing review below.";
        } else {
            $reviewer_name = !empty($loggedInUser['full_name']) ? $loggedInUser['full_name'] : (isset($_POST['reviewer_name']) ? sanitizeInput($_POST['reviewer_name']) : 'User');
            $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
            $comment = isset($_POST['comment']) ? sanitizeInput($_POST['comment']) : '';

            if (!empty($comment)) {
                addReview($listing['id'], $reviewer_name, $rating, $comment, $loggedInUser['id'] ?? null, $loggedInUser['mobile'] ?? null);
                $review_success = true;
                $existing_user_review = hasUserReviewedListing($loggedInUser['id'], $listing['id'], $loggedInUser['mobile'], $loggedInUser['full_name']);
            } else {
                $review_error = "Please enter your review experience or comment.";
            }
        }
    } elseif ($_POST['action'] === 'update_review') {
        if (!isUserLoggedIn()) {
            $review_error = "Only registered and logged-in users can update reviews.";
        } elseif (($loggedInUser['mobile_status'] ?? 'UNVERIFIED') !== 'VERIFIED') {
            $review_error = "Your mobile number is unverified. Please verify your mobile number via OTP before updating your review.";
        } elseif (!$existing_user_review) {
            $review_error = "No existing review found to update.";
        } else {
            $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
            $comment = isset($_POST['comment']) ? sanitizeInput($_POST['comment']) : '';

            if (!empty($comment)) {
                updateReview($existing_user_review['id'], $rating, $comment);
                $review_updated = true;
                $existing_user_review = hasUserReviewedListing($loggedInUser['id'], $listing['id'], $loggedInUser['mobile'], $loggedInUser['full_name']);
            } else {
                $review_error = "Please enter your updated review comment.";
            }
        }
    } elseif ($_POST['action'] === 'claim_business') {
        $c_name = sanitizeInput($_POST['claimant_name'] ?? '');
        $c_mobile = sanitizeInput($_POST['claimant_mobile'] ?? '');
        $c_role = sanitizeInput($_POST['role_title'] ?? 'Owner / Manager');
        $c_proof = sanitizeInput($_POST['verification_proof'] ?? '');

        if (empty($c_name) || empty($c_mobile)) {
            $claim_error = "Please fill in your Name and Mobile Number to submit a business claim.";
        } else {
            $c_uid = isUserLoggedIn() ? getLoggedInUser()['id'] : null;
            if (submitBusinessClaim($listing['id'], $c_uid, $c_name, $c_mobile, $c_role, $c_proof)) {
                $claim_success = true;
            } else {
                $claim_error = "Failed to submit business claim. Please try again.";
            }
        }
    }
}

// Increment listing view count
incrementViewCount($listing['id']);
// Re-fetch to get updated view_count
$listing['view_count'] = ($listing['view_count'] ?? 0) + 1;

$user_claim = hasUserClaimedListing($listing['id'], isUserLoggedIn() ? getLoggedInUser()['id'] : null, isUserLoggedIn() ? getLoggedInUser()['mobile'] : null);

$reviews = getReviewsByListingId($listing['id']);

$page_title = $listing['title'] . " – Saran Index Directory";
$meta_description = "Contact details, phone number, address, services, and map for " . $listing['title'] . " in " . $listing['block_name'] . ", Saran District.";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Profile Hero Header -->
<div class="bg-dark text-white py-5 position-relative">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 align-items-center">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill me-1"></i>Home</a></li>
                <?php if (!empty($listing['block_name'])): ?>
                    <li class="breadcrumb-item"><a href="search.php?block=<?php echo urlencode($listing['block_name']); ?>" class="text-white-50 text-decoration-none"><?php echo sanitizeInput($listing['block_name']); ?></a></li>
                <?php endif; ?>
                <?php if (!empty($listing['category_name'])): ?>
                    <li class="breadcrumb-item text-warning active" aria-current="page"><?php echo sanitizeInput($listing['category_name']); ?></li>
                <?php endif; ?>
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
                    <div><i class="bi bi-eye-fill text-info me-1"></i><?php echo number_format($listing['view_count']); ?> Views</div>
                </div>
            </div>

            <div class="col-lg-4 text-lg-end">
                <div class="d-flex flex-column gap-2 justify-content-lg-end">
                    <?php if (isMobileNumberVisibleToVisitor($listing)): ?>
                        <a href="tel:<?php echo sanitizeInput($listing['mobile']); ?>" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                            <i class="bi bi-telephone-fill me-2"></i>Call <?php echo sanitizeInput($listing['mobile']); ?>
                        </a>
                        <?php if (!empty($listing['whatsapp'])): ?>
                            <a href="https://wa.me/91<?php echo sanitizeInput($listing['whatsapp']); ?>" target="_blank" class="btn btn-success btn-lg rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-whatsapp me-2"></i>WhatsApp Direct Message
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="login.php?redirect=<?php echo urlencode(getListingUrl($listing['slug'])); ?>" class="btn btn-primary btn-lg rounded-pill fw-bold shadow" title="Log in to view full mobile number">
                            <i class="bi bi-lock-fill me-2"></i>Call <?php echo sanitizeInput(maskPhoneNumber($listing['mobile'])); ?>
                        </a>
                        <small class="text-white-50 small mt-1">
                            <i class="bi bi-info-circle me-1"></i>Mobile number is masked for guests. <a href="login.php" class="text-warning fw-bold text-decoration-underline">Log in</a> to view full number.
                        </small>
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

                    <?php if (!empty($listing['services']) || !empty($listing['products'])): ?>
                        <h5 class="fw-bold font-heading text-dark mt-4 mb-3">
                            <i class="bi bi-box-seam-fill text-primary me-2"></i>Products, Services & Key Features
                        </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <?php 
                            if (!empty($listing['products'])):
                                $productsList = explode(',', $listing['products']);
                                foreach ($productsList as $prod): 
                                    if (empty(trim($prod))) continue;
                            ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill font-body fw-medium" style="font-size: 0.9rem;">
                                    <i class="bi bi-box-seam me-1"></i><?php echo sanitizeInput(trim($prod)); ?>
                                </span>
                            <?php 
                                endforeach; 
                            endif; 
                            ?>

                            <?php 
                            if (!empty($listing['services'])):
                                $servicesList = explode(',', $listing['services']);
                                foreach ($servicesList as $srv): 
                                    if (empty(trim($srv))) continue;
                            ?>
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill font-body fw-medium" style="font-size: 0.9rem;">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i><?php echo sanitizeInput(trim($srv)); ?>
                                </span>
                            <?php 
                                endforeach; 
                            endif; 
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($listing['gst_no']) || !empty($listing['udyam_no']) || !empty($listing['cin_no']) || !empty($listing['local_reg_no'])): ?>
                        <div class="card border-0 bg-light rounded-4 p-4 mt-4 border border-secondary-subtle">
                            <h5 class="fw-bold font-heading text-dark mb-3">
                                <i class="bi bi-shield-check text-success me-2"></i>Business Registration & Legal Details
                            </h5>
                            <div class="row g-3">
                                <?php if (!empty($listing['gst_no'])): ?>
                                    <div class="col-sm-6">
                                        <div class="small text-muted mb-1">GSTIN / GST Number</div>
                                        <div class="fw-bold text-dark font-monospace"><i class="bi bi-patch-check-fill text-success me-1"></i><?php echo sanitizeInput($listing['gst_no']); ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($listing['udyam_no'])): ?>
                                    <div class="col-sm-6">
                                        <div class="small text-muted mb-1">Udyam / Udyog Aadhaar</div>
                                        <div class="fw-bold text-dark font-monospace"><i class="bi bi-patch-check-fill text-success me-1"></i><?php echo sanitizeInput($listing['udyam_no']); ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($listing['cin_no'])): ?>
                                    <div class="col-sm-6">
                                        <div class="small text-muted mb-1">CIN / Corp. Registration</div>
                                        <div class="fw-bold text-dark font-monospace"><i class="bi bi-building-check text-primary me-1"></i><?php echo sanitizeInput($listing['cin_no']); ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($listing['local_reg_no'])): ?>
                                    <div class="col-sm-6">
                                        <div class="small text-muted mb-1">Local License / Trade Reg.</div>
                                        <div class="fw-bold text-dark"><i class="bi bi-award-fill text-warning me-1"></i><?php echo sanitizeInput($listing['local_reg_no']); ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
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

                    <?php if ($review_updated): ?>
                        <div class="alert alert-success rounded-3 p-3 small mb-4">
                            <i class="bi bi-check-circle-fill me-1"></i>Your customer review has been updated successfully!
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($review_error)): ?>
                        <div class="alert alert-danger rounded-3 p-3 small mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i><?php echo sanitizeInput($review_error); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Add or Edit Review Form (Registered Users Only) -->
                    <?php if (isUserLoggedIn()): 
                        $current_user = getLoggedInUser();
                        $is_editing = !empty($existing_user_review);
                    ?>
                        <?php if (($current_user['mobile_status'] ?? 'UNVERIFIED') !== 'VERIFIED'): ?>
                            <div class="card border-0 bg-warning-subtle text-dark p-4 rounded-4 text-center border border-warning-subtle mb-4">
                                <i class="bi bi-shield-exclamation fs-2 text-warning mb-2"></i>
                                <h6 class="fw-bold mb-1 text-dark">Mobile Number Verification Required</h6>
                                <p class="small text-muted mb-3">Your registered mobile number (<strong><?php echo sanitizeInput($current_user['mobile']); ?></strong>) is not verified yet. Please verify your mobile number via OTP to post reviews on Saran Index.</p>
                                <a href="verify-mobile.php" class="btn btn-warning text-dark btn-sm fw-bold px-4 rounded-pill d-inline-flex align-items-center justify-content-center gap-1 mx-auto">
                                    <i class="bi bi-patch-check-fill me-1"></i> Verify Mobile Number Now
                                </a>
                            </div>
                        <?php else: ?>
                            <form action="<?php echo getListingUrl($listing['slug']); ?>" method="POST" class="bg-light p-4 rounded-4 border mb-4">
                                <input type="hidden" name="action" value="<?php echo $is_editing ? 'update_review' : 'add_review'; ?>">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <h6 class="fw-bold text-dark mb-0">
                                        <i class="bi <?php echo $is_editing ? 'bi-pencil-square text-info' : 'bi-plus-circle text-primary'; ?> me-1"></i>
                                        <?php echo $is_editing ? 'Edit Your Submitted Review' : 'Write a Customer Review'; ?>
                                    </h6>
                                    <span class="badge <?php echo $is_editing ? 'bg-info-subtle text-info border-info-subtle' : 'bg-success-subtle text-success border-success-subtle'; ?> border rounded-pill px-2.5 py-1 small">
                                        <i class="bi <?php echo $is_editing ? 'bi-pencil-fill' : 'bi-shield-check'; ?> me-1"></i>
                                        <?php echo $is_editing ? 'Previously Reviewed' : 'Logged in as ' . sanitizeInput($current_user['full_name']); ?>
                                    </span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-dark">Your Registered Name</label>
                                        <input type="text" name="reviewer_name" class="form-control bg-white" value="<?php echo sanitizeInput($current_user['full_name']); ?>" readonly required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-dark">Star Rating</label>
                                        <select name="rating" class="form-select bg-white">
                                            <?php 
                                            $currRating = $is_editing ? intval($existing_user_review['rating']) : 5;
                                            ?>
                                            <option value="5" <?php echo $currRating === 5 ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ 5 Stars - Excellent</option>
                                            <option value="4" <?php echo $currRating === 4 ? 'selected' : ''; ?>>⭐⭐⭐⭐ 4 Stars - Very Good</option>
                                            <option value="3" <?php echo $currRating === 3 ? 'selected' : ''; ?>>⭐⭐⭐ 3 Stars - Good</option>
                                            <option value="2" <?php echo $currRating === 2 ? 'selected' : ''; ?>>⭐⭐ 2 Stars - Average</option>
                                            <option value="1" <?php echo $currRating === 1 ? 'selected' : ''; ?>>⭐ 1 Star - Poor</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold text-dark">Your Experience / Review Comment</label>
                                        <textarea name="comment" class="form-control bg-white" rows="3" required placeholder="Share your detailed feedback, service quality, pricing experience, or recommendations..."><?php echo $is_editing ? sanitizeInput($existing_user_review['comment']) : ''; ?></textarea>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn <?php echo $is_editing ? 'btn-info text-white' : 'btn-primary'; ?> rounded-pill btn-sm fw-bold px-4 py-2">
                                            <i class="bi <?php echo $is_editing ? 'bi-arrow-repeat' : 'bi-send-fill'; ?> me-1"></i>
                                            <?php echo $is_editing ? 'Update My Review' : 'Submit Customer Review'; ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Guest Login Prompt -->
                        <div class="card border-0 bg-primary-subtle text-dark p-4 rounded-4 text-center border-dashed mb-4">
                            <i class="bi bi-lock-fill fs-2 text-primary mb-2"></i>
                            <h6 class="fw-bold mb-1 text-dark">Only Registered Users Can Leave Reviews</h6>
                            <p class="small text-muted mb-3">Please log in to your Saran Index account to post a star rating and customer review for this business.</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="login.php?redirect=<?php echo urlencode('listing/' . $listing['slug']); ?>" class="btn btn-primary btn-sm fw-bold px-4 rounded-pill">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Log In to Review
                                </a>
                                <a href="register.php" class="btn btn-outline-primary btn-sm fw-bold px-4 rounded-pill">
                                    <i class="bi bi-person-plus me-1"></i> Register Account
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

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

            <!-- Listed By User / Professional Profile Card -->
            <?php if (!empty($listing['owner_name']) || !empty($listing['user_id'])): 
                $ownerHandle = !empty($listing['owner_handle']) ? $listing['owner_handle'] : '';
                $ownerProfileUrl = !empty($listing['owner_handle']) ? '@' . ltrim($listing['owner_handle'], '@') : 'user_profile.php?id=' . $listing['user_id'];
            ?>
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden border">
                    <div class="card-body p-3.5 d-flex align-items-center gap-3">
                        <a href="<?php echo $ownerProfileUrl; ?>" class="text-decoration-none flex-shrink-0">
                            <?php if (!empty($listing['owner_image']) && file_exists(__DIR__ . '/' . $listing['owner_image'])): ?>
                                <img src="<?php echo sanitizeInput($listing['owner_image']); ?>" 
                                     alt="<?php echo sanitizeInput($listing['owner_name']); ?>" 
                                     class="rounded-circle img-thumbnail shadow-xs" 
                                     style="width: 55px; height: 55px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold shadow-xs border" 
                                     style="width: 55px; height: 55px; font-size: 1.25rem; background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                                    <?php echo strtoupper(substr(trim($listing['owner_name'] ?: 'U'), 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="flex-grow-1 min-w-0">
                            <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Listed By Professional</small>
                            <a href="<?php echo $ownerProfileUrl; ?>" class="text-decoration-none text-dark fw-bold fs-6 text-truncate d-block">
                                <?php echo sanitizeInput($listing['owner_name']); ?>
                                <i class="bi bi-patch-check-fill text-primary ms-1" title="Verified Member"></i>
                            </a>
                            <small class="text-primary fw-medium" style="font-size: 0.8rem;">
                                <?php echo !empty($listing['owner_handle']) ? '@' . ltrim(sanitizeInput($listing['owner_handle']), '@') : sanitizeInput($listing['owner_designation'] ?: 'Verified Directory Member'); ?>
                            </small>
                        </div>
                        <a href="<?php echo $ownerProfileUrl; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold flex-shrink-0">
                            <i class="bi bi-person-lines-fill me-1"></i>Profile
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php 
            $dataSource = null;
            if (!empty($listing['source_id'])) {
                $dataSource = getSourceById($listing['source_id']);
            }
            if (!$dataSource && !empty($listing['source'])) {
                $dataSource = getSourceByName($listing['source']);
            }
            if ($dataSource):
            ?>
                <!-- Official Data Source & Verification Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4 border-start border-4 border-primary" style="background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge <?php echo !empty($dataSource['badge_color_class']) ? $dataSource['badge_color_class'] : 'bg-primary-subtle text-primary'; ?> fw-semibold px-2.5 py-1 rounded-pill small">
                                <i class="bi <?php echo !empty($dataSource['badge_icon']) ? $dataSource['badge_icon'] : 'bi-patch-check-fill'; ?> me-1"></i>
                                <?php echo sanitizeInput($dataSource['badge_text'] ?? 'Verified Data Source'); ?>
                            </span>
                        </div>
                        <span class="badge bg-secondary-subtle text-secondary small px-2 py-1 rounded-pill">
                            <?php echo sanitizeInput($dataSource['authority_badge'] ?? 'Government Data'); ?>
                        </span>
                    </div>

                    <h6 class="fw-bold text-dark font-heading mb-1">
                        <?php echo sanitizeInput($dataSource['title']); ?>
                    </h6>
                    <p class="text-muted small mb-3">
                        <?php echo sanitizeInput($dataSource['subtitle']); ?>
                    </p>

                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <a href="<?php echo sanitizeInput($dataSource['url']); ?>" target="_blank" rel="noopener" class="text-primary fw-semibold small text-decoration-none hover-underline">
                            <i class="bi bi-box-arrow-up-right me-1"></i><?php echo sanitizeInput($dataSource['domain']); ?>
                        </a>
                        <a href="sources" class="text-muted small text-decoration-none hover-primary">
                            View All Sources <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Claim Business Widget -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4 border border-warning-subtle" style="background: linear-gradient(135deg, #fffdf5 0%, #ffffff 100%); border-left: 4px solid #ffc107 !important;">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="badge bg-warning text-dark rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="bi bi-patch-question-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Is this your business or organization?</h6>
                        <small class="text-muted">Claim ownership to update details & manage reviews.</small>
                    </div>
                </div>

                <?php if ($user_claim && $user_claim['status'] === 'PENDING'): ?>
                    <div class="alert alert-warning rounded-3 p-3 small mb-0 border border-warning-subtle">
                        <i class="bi bi-clock-history me-1"></i><strong>Claim Pending Review:</strong> Your ownership claim was submitted on <?php echo date('d M Y', strtotime($user_claim['created_at'])); ?>. Our admin team will verify and contact you shortly.
                    </div>
                <?php elseif ($user_claim && $user_claim['status'] === 'APPROVED'): ?>
                    <div class="alert alert-success rounded-3 p-3 small mb-0 border border-success-subtle">
                        <i class="bi bi-patch-check-fill me-1"></i><strong>Claim Approved:</strong> You are the verified owner of this listing.
                    </div>
                <?php elseif ($claim_success): ?>
                    <div class="alert alert-success rounded-3 p-3 small mb-0">
                        <i class="bi bi-check-circle-fill me-1"></i>Your business claim has been submitted successfully! Our admin team will verify and contact you shortly.
                    </div>
                <?php else: ?>
                    <?php if (!empty($claim_error)): ?>
                        <div class="alert alert-danger rounded-3 p-3 small mb-2">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i><?php echo sanitizeInput($claim_error); ?>
                        </div>
                    <?php endif; ?>
                    <button class="btn btn-warning text-dark fw-bold rounded-pill w-100 py-2.5 shadow-xs d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#claimBusinessModal">
                        <i class="bi bi-shield-check fs-6"></i>
                        <span>Claim This Business</span>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Claim Business Modal -->
            <div class="modal fade" id="claimBusinessModal" tabindex="-1" aria-labelledby="claimBusinessModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <div class="modal-header border-bottom bg-warning-subtle">
                            <h5 class="modal-title fw-bold text-dark" id="claimBusinessModalLabel">
                                <i class="bi bi-shield-lock-fill text-warning me-2"></i>Claim Listing: <?php echo sanitizeInput($listing['title']); ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?php echo getListingUrl($listing['slug']); ?>" method="POST">
                            <input type="hidden" name="action" value="claim_business">
                            <div class="modal-body p-4">
                                <p class="small text-muted mb-3">Are you the authorized owner, manager, or representative of <strong><?php echo sanitizeInput($listing['title']); ?></strong>? Submit your details to claim ownership of this listing.</p>
                                
                                <?php 
                                $claimUser = isUserLoggedIn() ? getLoggedInUser() : null;
                                ?>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-dark">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" name="claimant_name" class="form-control" value="<?php echo sanitizeInput($claimUser['full_name'] ?? ''); ?>" required placeholder="e.g. Ramesh Kumar">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-dark">Contact Mobile Number <span class="text-danger">*</span></label>
                                    <input type="tel" name="claimant_mobile" class="form-control" value="<?php echo sanitizeInput($claimUser['mobile'] ?? ''); ?>" required placeholder="10-digit mobile number" maxlength="10">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-dark">Your Relationship / Role</label>
                                    <select name="role_title" class="form-select">
                                        <option value="Owner / Proprietor">Owner / Proprietor</option>
                                        <option value="General Manager">General Manager</option>
                                        <option value="Authorized Representative">Authorized Representative</option>
                                        <option value="Employee">Employee</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-dark">Verification Proof / Note <span class="text-muted fw-normal">(Optional)</span></label>
                                    <textarea name="verification_proof" class="form-control" rows="3" placeholder="Provide GSTIN, Trade License No, Visiting Card details, or short explanation..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-top bg-light">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill px-4">
                                    <i class="bi bi-send-fill me-1"></i>Submit Business Claim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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
                                <?php if (isMobileNumberVisibleToVisitor($listing)): ?>
                                    <a href="tel:<?php echo sanitizeInput($listing['mobile']); ?>" class="text-primary fw-bold text-decoration-none small"><?php echo sanitizeInput($listing['mobile']); ?></a>
                                <?php else: ?>
                                    <span class="text-muted small fw-bold"><i class="bi bi-lock-fill me-1 text-warning"></i><?php echo sanitizeInput(maskPhoneNumber($listing['mobile'])); ?></span>
                                    <?php if (!isUserLoggedIn()): ?>
                                        <div class="mt-1"><a href="login.php?redirect=<?php echo urlencode(getListingUrl($listing['slug'])); ?>" class="text-primary small text-decoration-underline">Log in to view</a></div>
                                    <?php endif; ?>
                                <?php endif; ?>
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

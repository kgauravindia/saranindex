<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : 'sadar-hospital-chapra';
$listing = getListingBySlug($slug);

if (!$listing) {
    $listings = getListings('', '', '', 1);
    $listing = !empty($listings) ? $listings[0] : null;
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
            $review_error = "केवल पंजीकृत एवं लॉगिन किए गए उपयोगकर्ता ही समीक्षा दे सकते हैं। कृपया पहले लॉगिन करें।";
        } elseif (($loggedInUser['mobile_status'] ?? 'UNVERIFIED') !== 'VERIFIED') {
            $review_error = "आपका मोबाइल नंबर असत्यापित (Unverified) है। समीक्षा पोस्ट करने के लिए कृपया पहले ओटीपी द्वारा अपना मोबाइल नंबर सत्यापित करें।";
        } elseif ($existing_user_review) {
            $review_error = "आप पहले ही इस सूची के लिए समीक्षा दे चुके हैं। आप नीचे अपनी समीक्षा अपडेट कर सकते हैं।";
        } else {
            $reviewer_name = !empty($loggedInUser['full_name']) ? $loggedInUser['full_name'] : (isset($_POST['reviewer_name']) ? sanitizeInput($_POST['reviewer_name']) : 'User');
            $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
            $comment = isset($_POST['comment']) ? sanitizeInput($_POST['comment']) : '';

            if (!empty($comment)) {
                addReview($listing['id'], $reviewer_name, $rating, $comment, $loggedInUser['id'] ?? null, $loggedInUser['mobile'] ?? null);
                $review_success = true;
                $existing_user_review = hasUserReviewedListing($loggedInUser['id'], $listing['id'], $loggedInUser['mobile'], $loggedInUser['full_name']);
            } else {
                $review_error = "कृपया अपनी प्रतिक्रिया या टिप्पणी दर्ज करें।";
            }
        }
    } elseif ($_POST['action'] === 'update_review') {
        if (!isUserLoggedIn()) {
            $review_error = "केवल पंजीकृत उपयोगकर्ता ही समीक्षा अपडेट कर सकते हैं।";
        } elseif (($loggedInUser['mobile_status'] ?? 'UNVERIFIED') !== 'VERIFIED') {
            $review_error = "आपका मोबाइल नंबर असत्यापित (Unverified) है। समीक्षा अपडेट करने के लिए कृपया पहले ओटीपी द्वारा अपना मोबाइल नंबर सत्यापित करें।";
        } elseif (!$existing_user_review) {
            $review_error = "अपडेट करने के लिए कोई पुरानी समीक्षा नहीं मिली।";
        } else {
            $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
            $comment = isset($_POST['comment']) ? sanitizeInput($_POST['comment']) : '';

            if (!empty($comment)) {
                updateReview($existing_user_review['id'], $rating, $comment);
                $review_updated = true;
                $existing_user_review = hasUserReviewedListing($loggedInUser['id'], $listing['id'], $loggedInUser['mobile'], $loggedInUser['full_name']);
            } else {
                $review_error = "कृपया अपनी अपडेट की गई टिप्पणी दर्ज करें।";
            }
        }
    } elseif ($_POST['action'] === 'claim_business') {
        $c_name = sanitizeInput($_POST['claimant_name'] ?? '');
        $c_mobile = sanitizeInput($_POST['claimant_mobile'] ?? '');
        $c_role = sanitizeInput($_POST['role_title'] ?? 'Owner / Manager');
        $c_proof = sanitizeInput($_POST['verification_proof'] ?? '');

        if (empty($c_name) || empty($c_mobile)) {
            $claim_error = "दावा प्रस्तुत करने के लिए कृपया अपना नाम और मोबाइल नंबर भरें।";
        } else {
            $c_uid = isUserLoggedIn() ? getLoggedInUser()['id'] : null;
            if (submitBusinessClaim($listing['id'], $c_uid, $c_name, $c_mobile, $c_role, $c_proof)) {
                $claim_success = true;
            } else {
                $claim_error = "व्यवसाय दावा सबमिट करने में विफल। कृपया पुनः प्रयास करें।";
            }
        }
    }
}

$user_claim = hasUserClaimedListing($listing['id'], isUserLoggedIn() ? getLoggedInUser()['id'] : null, isUserLoggedIn() ? getLoggedInUser()['mobile'] : null);

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
            <ol class="breadcrumb mb-0 align-items-center">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill me-1"></i>मुख्य पृष्ठ</a></li>
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
                        <span class="vip-platinum-badge"><i class="bi bi-crown-fill me-1"></i> वीआईपी प्लैटिनम</span>
                    <?php elseif (isset($listing['plan_type']) && $listing['plan_type'] === 'GOLD'): ?>
                        <span class="gold-business-badge"><i class="bi bi-patch-check-fill me-1"></i> गोल्ड बिजनेस</span>
                    <?php elseif ($listing['is_verified'] === 'YES'): ?>
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

                <?php if ($review_updated): ?>
                    <div class="alert alert-success rounded-3 mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i>आपकी समीक्षा सफलतापूर्वक अपडेट कर दी गई है!
                    </div>
                <?php endif; ?>

                <?php if (!empty($review_error)): ?>
                    <div class="alert alert-danger rounded-3 mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo sanitizeInput($review_error); ?>
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

                <!-- Add or Edit Review Form (Registered Users Only) -->
                <?php if (isUserLoggedIn()): 
                    $current_user = getLoggedInUser();
                    $is_editing = !empty($existing_user_review);
                ?>
                    <?php if (($current_user['mobile_status'] ?? 'UNVERIFIED') !== 'VERIFIED'): ?>
                        <div class="card border-0 bg-warning-subtle text-dark p-4 rounded-4 text-center border border-warning-subtle mb-4">
                            <i class="bi bi-shield-exclamation fs-2 text-warning mb-2"></i>
                            <h6 class="fw-bold mb-1 text-dark">मोबाइल नंबर का सत्यापन आवश्यक है</h6>
                            <p class="small text-muted mb-3">आपका पंजीकृत मोबाइल नंबर (<strong><?php echo sanitizeInput($current_user['mobile']); ?></strong>) अभी तक सत्यापित नहीं है। समीक्षा पोस्ट करने के लिए कृपया अपने मोबाइल नंबर का ओटीपी से सत्यापन करें।</p>
                            <a href="../verify-mobile.php" class="btn btn-warning text-dark btn-sm fw-bold px-4 rounded-pill d-inline-flex align-items-center justify-content-center gap-1 mx-auto">
                                <i class="bi bi-patch-check-fill me-1"></i> मोबाइल नंबर सत्यापित करें
                            </a>
                        </div>
                    <?php else: ?>
                        <form action="" method="POST" class="bg-light p-4 rounded-4 border mb-4">
                        <input type="hidden" name="action" value="<?php echo $is_editing ? 'update_review' : 'add_review'; ?>">
                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="bi <?php echo $is_editing ? 'bi-pencil-square text-info' : 'bi-plus-circle text-primary'; ?> me-1"></i>
                                <?php echo $is_editing ? 'अपनी समीक्षा संशोधित / अपडेट करें' : 'समीक्षा लिखें'; ?>
                            </h5>
                            <span class="badge <?php echo $is_editing ? 'bg-info-subtle text-info border-info-subtle' : 'bg-success-subtle text-success border-success-subtle'; ?> border rounded-pill px-2.5 py-1 small">
                                <i class="bi <?php echo $is_editing ? 'bi-pencil-fill' : 'bi-shield-check'; ?> me-1"></i>
                                <?php echo $is_editing ? 'पूर्व में दी गई समीक्षा' : 'लॉगिन खाता: ' . sanitizeInput($current_user['full_name']); ?>
                            </span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">आपका पंजीकृत नाम</label>
                                <input type="text" name="reviewer_name" class="form-control bg-white" value="<?php echo sanitizeInput($current_user['full_name']); ?>" readonly required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">रेटिंग चुनें</label>
                                <select name="rating" class="form-select bg-white">
                                    <?php 
                                    $currRating = $is_editing ? intval($existing_user_review['rating']) : 5;
                                    ?>
                                    <option value="5" <?php echo $currRating === 5 ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ (5/5 श्रेष्ठ)</option>
                                    <option value="4" <?php echo $currRating === 4 ? 'selected' : ''; ?>>⭐⭐⭐⭐ (4/5 बहुत अच्छा)</option>
                                    <option value="3" <?php echo $currRating === 3 ? 'selected' : ''; ?>>⭐⭐⭐ (3/5 अच्छा)</option>
                                    <option value="2" <?php echo $currRating === 2 ? 'selected' : ''; ?>>⭐⭐ (2/5 औसत)</option>
                                    <option value="1" <?php echo $currRating === 1 ? 'selected' : ''; ?>>⭐ (1/5 खराब)</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">अपनी प्रतिक्रिया / अनुभव लिखें</label>
                                <textarea name="comment" class="form-control bg-white" rows="3" placeholder="इस संस्था/व्यवसाय के साथ अपना अनुभव साझा करें..." required><?php echo $is_editing ? sanitizeInput($existing_user_review['comment']) : ''; ?></textarea>
                            </div>
                            <div class="col-12 text-end d-flex align-items-center justify-content-between">
                                <?php if ($is_editing): ?>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-clock-history me-1"></i>समीक्षा तिथि: <?php echo date('d M Y', strtotime($existing_user_review['created_at'])); ?>
                                    </small>
                                <?php else: ?>
                                    <span></span>
                                <?php endif; ?>
                                <button type="submit" class="btn <?php echo $is_editing ? 'btn-info text-white' : 'btn-primary'; ?> rounded-pill btn-sm fw-bold px-4 py-2">
                                    <i class="bi <?php echo $is_editing ? 'bi-arrow-repeat' : 'bi-send-fill'; ?> me-1"></i>
                                    <?php echo $is_editing ? 'समीक्षा अपडेट करें' : 'समीक्षा जमा करें'; ?>
                                </button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                    <!-- Guest Login Prompt -->
                    <div class="card border-0 bg-primary-subtle text-dark p-4 rounded-4 text-center border-dashed mb-4">
                        <i class="bi bi-lock-fill fs-2 text-primary mb-2"></i>
                        <h6 class="fw-bold mb-1 text-dark">केवल पंजीकृत उपयोगकर्ता ही समीक्षा दे सकते हैं</h6>
                        <p class="small text-muted mb-3">सारण इंडेक्स पर रेटिंग एवं समीक्षा पोस्ट करने के लिए कृपया अपने खाते में लॉगिन करें या नया खाता बनाएं।</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="../login.php?redirect=<?php echo urlencode('hindi/profile.php?slug=' . $listing['slug']); ?>" class="btn btn-primary btn-sm fw-bold px-4 rounded-pill">
                                <i class="bi bi-box-arrow-in-right me-1"></i> लॉगिन करें
                            </a>
                            <a href="../register.php" class="btn btn-outline-primary btn-sm fw-bold px-4 rounded-pill">
                                <i class="bi bi-person-plus me-1"></i> नया खाता बनाएं
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Listed By User / Professional Profile Card -->
            <?php if (!empty($listing['owner_name']) || !empty($listing['user_id'])): 
                $ownerHandle = !empty($listing['owner_handle']) ? $listing['owner_handle'] : '';
                $ownerProfileUrl = !empty($listing['owner_handle']) ? '../@' . ltrim($listing['owner_handle'], '@') : '../user_profile.php?id=' . $listing['user_id'];
            ?>
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden border">
                    <div class="card-body p-3.5 d-flex align-items-center gap-3">
                        <a href="<?php echo $ownerProfileUrl; ?>" class="text-decoration-none flex-shrink-0">
                            <?php if (!empty($listing['owner_image']) && file_exists(__DIR__ . '/../' . $listing['owner_image'])): ?>
                                <img src="../<?php echo sanitizeInput($listing['owner_image']); ?>" 
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
                            <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">प्रविष्टकर्ता (Listed By)</small>
                            <a href="<?php echo $ownerProfileUrl; ?>" class="text-decoration-none text-dark fw-bold fs-6 text-truncate d-block">
                                <?php echo sanitizeInput($listing['owner_name']); ?>
                                <i class="bi bi-patch-check-fill text-primary ms-1" title="सत्यापित सदस्य"></i>
                            </a>
                            <small class="text-primary fw-medium" style="font-size: 0.8rem;">
                                <?php echo !empty($listing['owner_handle']) ? '@' . ltrim(sanitizeInput($listing['owner_handle']), '@') : sanitizeInput($listing['owner_designation'] ?: 'सत्यापित सदस्य'); ?>
                            </small>
                        </div>
                        <a href="<?php echo $ownerProfileUrl; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold flex-shrink-0">
                            <i class="bi bi-person-lines-fill me-1"></i>प्रोफाइल
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
                        <h6 class="fw-bold text-dark mb-0">क्या यह आपका व्यवसाय या संस्था है?</h6>
                        <small class="text-muted">स्वामित्व का दावा करें, विवरण अपडेट करें और समीक्षाएं प्रबंधित करें।</small>
                    </div>
                </div>

                <?php if ($user_claim && $user_claim['status'] === 'PENDING'): ?>
                    <div class="alert alert-warning rounded-3 p-3 small mb-0 border border-warning-subtle">
                        <i class="bi bi-clock-history me-1"></i><strong>दावा समीक्षाधीन है:</strong> स्वामित्व का दावा <?php echo date('d M Y', strtotime($user_claim['created_at'])); ?> को सबमिट किया गया था। हमारी टीम जल्द ही आपसे संपर्क करेगी।
                    </div>
                <?php elseif ($user_claim && $user_claim['status'] === 'APPROVED'): ?>
                    <div class="alert alert-success rounded-3 p-3 small mb-0 border border-success-subtle">
                        <i class="bi bi-patch-check-fill me-1"></i><strong>दावा स्वीकृत:</strong> आप इस सूची के सत्यापित स्वामी हैं।
                    </div>
                <?php elseif ($claim_success): ?>
                    <div class="alert alert-success rounded-3 p-3 small mb-0">
                        <i class="bi bi-check-circle-fill me-1"></i>व्यवसाय दावा सफलतापूर्वक सबमिट किया गया! हमारी एडमिन टीम सत्यापन के बाद संपर्क करेगी।
                    </div>
                <?php else: ?>
                    <?php if (!empty($claim_error)): ?>
                        <div class="alert alert-danger rounded-3 p-3 small mb-2">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i><?php echo sanitizeInput($claim_error); ?>
                        </div>
                    <?php endif; ?>
                    <button class="btn btn-warning text-dark fw-bold rounded-pill w-100 py-2.5 shadow-xs d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#claimBusinessModal">
                        <i class="bi bi-shield-check fs-6"></i>
                        <span>व्यवसाय का दावा करें (Claim Business)</span>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Claim Business Modal -->
            <div class="modal fade" id="claimBusinessModal" tabindex="-1" aria-labelledby="claimBusinessModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <div class="modal-header border-bottom bg-warning-subtle">
                            <h5 class="modal-title fw-bold text-dark" id="claimBusinessModalLabel">
                                <i class="bi bi-shield-lock-fill text-warning me-2"></i>दावा प्रस्तुत करें: <?php echo sanitizeInput($listingTitle); ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST">
                            <input type="hidden" name="action" value="claim_business">
                            <div class="modal-body p-4">
                                <p class="small text-muted mb-3">क्या आप <strong><?php echo sanitizeInput($listingTitle); ?></strong> के अधिकृत स्वामी, प्रबंधक या प्रतिनिधि हैं? स्वामित्व का दावा करने के लिए अपना विवरण दर्ज करें।</p>
                                
                                <?php 
                                $claimUser = isUserLoggedIn() ? getLoggedInUser() : null;
                                ?>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-dark">आपका नाम <span class="text-danger">*</span></label>
                                    <input type="text" name="claimant_name" class="form-control" value="<?php echo sanitizeInput($claimUser['full_name'] ?? ''); ?>" required placeholder="उदा. रमेश कुमार">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-dark">संपर्क मोबाइल नंबर <span class="text-danger">*</span></label>
                                    <input type="tel" name="claimant_mobile" class="form-control" value="<?php echo sanitizeInput($claimUser['mobile'] ?? ''); ?>" required placeholder="10 अंकों का मोबाइल नंबर" maxlength="10">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-dark">पद / संबंध</label>
                                    <select name="role_title" class="form-select">
                                        <option value="Owner / Proprietor">मालिक / प्रोपराइटर</option>
                                        <option value="General Manager">प्रबंधक (Manager)</option>
                                        <option value="Authorized Representative">अधिकृत प्रतिनिधि</option>
                                        <option value="Employee">कर्मचारी</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-dark">सत्यापन विवरण / टिप्पणी <span class="text-muted fw-normal">(ऐच्छिक)</span></label>
                                    <textarea name="verification_proof" class="form-control" rows="3" placeholder="जीएसटीआईएन, ट्रेड लाइसेंस, विजिटिंग कार्ड विवरण या संक्षिप्त टिप्पणी दर्ज करें..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-top bg-light">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">रद्द करें</button>
                                <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill px-4">
                                    <i class="bi bi-send-fill me-1"></i>दावा सबमिट करें
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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

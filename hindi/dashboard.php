<?php
require_once __DIR__ . '/includes/functions.php';

// Auth Guard: Require Login
if (!isUserLoggedIn()) {
    header('Location: login.php?redirect=dashboard.php');
    exit;
}

$user = getLoggedInUser();
if (!$user) {
    logoutPublicUser();
    header('Location: login.php');
    exit;
}

$msg = '';
$msg_type = '';

// Handle Profile Update POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $fullName = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $whatsapp = $_POST['whatsapp'] ?? '';
    $businessName = $_POST['business_name'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $blockId = $_POST['block_id'] ?? null;
    $address = $_POST['address'] ?? '';
    $pincode = $_POST['pincode'] ?? '';
    $bio = $_POST['bio'] ?? '';

    $res = updateUserProfile($user['id'], $fullName, $email, $blockId, $address, '', $whatsapp, $businessName, $designation, $pincode, null, null, $bio);
    if ($res['success']) {
        $msg = $res['message'];
        $msg_type = 'success';
        $user = getLoggedInUser(); // Refresh user data
    } else {
        $msg = $res['message'];
        $msg_type = 'danger';
    }
}

// Handle Delete User Profile POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && ($_POST['action'] === 'delete_profile' || $_POST['action'] === 'delete_account')) {
    $confirm_input = trim($_POST['confirm_delete'] ?? '');
    if (strtoupper($confirm_input) === 'DELETE') {
        $userIdToDelete = $user['id'];
        if (deleteUser($userIdToDelete)) {
            header("Location: login.php?msg=profile_deleted");
            exit;
        } else {
            $msg = "आपकी उपयोगकर्ता प्रोफ़ाइल डिलीट करने में त्रुटि हुई। कृपया पुनः प्रयास करें।";
            $msg_type = 'danger';
        }
    } else {
        $msg = "प्रोफ़ाइल डिलीट की पुष्टि करने के लिए कृपया बड़े अक्षरों में 'DELETE' टाइप करें।";
        $msg_type = 'danger';
    }
}

// Handle Plan Upgrade & Online Payment Request Logging
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upgrade_plan') {
    $listingId = intval($_POST['listing_id'] ?? 0);
    $newPlan = isset($_POST['plan_type']) && in_array($_POST['plan_type'], ['FREE', 'GOLD', 'PLATINUM']) ? $_POST['plan_type'] : 'FREE';

    if ($listingId > 0) {
        $db = getDB();
        if ($db) {
            try {
                if ($newPlan === 'FREE') {
                    $stmtU = $db->prepare("UPDATE listings SET plan_type = 'FREE', plan_expires_at = NULL, is_featured = 'NO', is_verified = 'NO' WHERE id = :id");
                    $stmtU->execute(['id' => $listingId]);
                    $msg = "मेंबरशिप प्लान बुनियादी मुफ्त (FREE) में सेट कर दिया गया!";
                    $msg_type = 'success';
                } else {
                    $amount = ($newPlan === 'GOLD') ? 499.00 : 1499.00;
                    $payment = createOnlinePayment($user['id'], $listingId, $newPlan, $amount, 'RAZORPAY_UPI');
                    if ($payment) {
                        $msg = "रेजरपे ऑनलाइन भुगतान का अनुरोध दर्ज किया गया। कृपया भुगतान पूरा करें।";
                        $msg_type = 'info';
                    }
                }
            } catch (PDOException $e) {
                $msg = "प्ला अपग्रेड करने में त्रुटि: " . $e->getMessage();
                $msg_type = 'danger';
            }
        }
    }
}

// Handle Business Claim POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'claim_business') {
    $listingId = intval($_POST['listing_id'] ?? 0);
    $c_name = sanitizeInput($_POST['claimant_name'] ?? '');
    $c_mobile = sanitizeInput($_POST['claimant_mobile'] ?? '');
    $c_role = sanitizeInput($_POST['role_title'] ?? 'Owner / Manager');
    $c_proof = sanitizeInput($_POST['verification_proof'] ?? '');

    if ($listingId > 0 && !empty($c_name) && !empty($c_mobile)) {
        if (submitBusinessClaim($listingId, $user['id'], $c_name, $c_mobile, $c_role, $c_proof)) {
            $msg = "व्यवसाय का दावा सफलतापूर्वक सबमिट किया गया! हमारी एडमिन टीम सत्यापन के बाद इसे आपके खाते से जोड़ देगी।";
            $msg_type = 'success';
        } else {
            $msg = "व्यवसाय दावा सबमिट करने में विफल। कृपया पुनः प्रयास करें।";
            $msg_type = 'danger';
        }
    } else {
        $msg = "कृपया एक वैध व्यवसाय सूची चुनें और अपना नाम तथा संपर्क मोबाइल नंबर भरें।";
        $msg_type = 'warning';
    }
}

$userListings = getUserListings($user['id']);
$userPayments = getUserPayments($user['id']);
$blocks = getBlocks();

$page_title = "मेरा खाता एवं डैशबोर्ड – सारण इंडेक्स";
$meta_description = "सारण इंडेक्स पर आपका उपयोगकर्ता खाता डैशबोर्ड। अपनी लिस्टिंग, प्रोफ़ाइल, ऑनलाइन भुगतान और व्यवसाय निर्देशिका प्रविष्टियों को प्रबंधित करें।";

require_once __DIR__ . '/includes/header.php';
?>

<style>
.dashboard-hero-banner {
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0F172A 100%);
    color: #ffffff;
    border-radius: 24px;
    padding: 35px 30px;
    box-shadow: 0 15px 35px rgba(15, 23, 42, 0.25);
    position: relative;
    overflow: hidden;
}
.dashboard-hero-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, transparent 70%);
    pointer-events: none;
}
.dashboard-stat-card {
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 22px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    transition: all 0.25s ease;
}
.dashboard-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.07);
}
.dashboard-stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.listing-compact-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
    padding: 16px 20px;
}
.listing-compact-card:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    border-color: #cbd5e1;
}
</style>

<div class="container py-4">
    <!-- Redesigned Hero Banner -->
    <div class="dashboard-hero-banner mb-4">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4 position-relative z-1">
            <div class="d-flex align-items-center gap-3.5">
                <div class="flex-shrink-0">
                    <?php if (!empty($user['profile_image']) && file_exists(__DIR__ . '/../' . $user['profile_image'])): ?>
                        <img src="../<?php echo sanitizeInput($user['profile_image']); ?>" alt="<?php echo sanitizeInput($user['full_name']); ?>" class="rounded-circle img-thumbnail shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-2 shadow-sm border border-2 border-white" style="width: 80px; height: 80px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                            <?php echo strtoupper(substr($user['full_name'] ?: 'U', 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill extra-small shadow-xs">
                            <i class="bi bi-person-badge-fill me-1"></i>खाता डैशबोर्ड
                        </span>
                        <?php if (!empty($user['username_handle'])): ?>
                            <a href="../@<?php echo sanitizeInput(ltrim($user['username_handle'], '@')); ?>" target="_blank" class="badge bg-warning text-dark text-decoration-none fw-bold px-3 py-1.5 rounded-pill shadow-sm">
                                <i class="bi bi-at text-dark"></i><?php echo sanitizeInput(ltrim($user['username_handle'], '@')); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <h1 class="display-6 fw-bold text-warning mb-1 font-heading">
                        <?php echo htmlspecialchars($user['full_name']); ?>
                    </h1>
                    <div class="text-white-50 small d-flex flex-wrap align-items-center gap-3">
                        <span><i class="bi bi-phone me-1 text-primary"></i>+91 <?php echo htmlspecialchars($user['mobile']); ?></span>
                        <span><i class="bi bi-person-circle me-1 text-info"></i>यूजर ID: #<?php echo intval($user['id']); ?></span>
                        <span><i class="bi bi-geo-alt me-1 text-warning"></i><?php echo htmlspecialchars($user['block_name'] ?? 'सारण जिला'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Header Quick Action Buttons -->
            <div class="d-flex flex-wrap gap-2">
                <a href="edit-profile.php" class="btn btn-light btn-sm rounded-pill px-3.5 py-2 fw-bold text-dark shadow-sm">
                    <i class="bi bi-pencil-square me-1.5 text-primary"></i>प्रोफ़ाइल संपादित करें
                </a>
                <button type="button" class="btn btn-warning btn-sm rounded-pill px-3.5 py-2 fw-bold text-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#upgradeProfileModal">
                    <i class="bi bi-lightning-charge-fill me-1.5 text-danger"></i>प्रोफ़ाइल प्लान अपग्रेड करें
                </button>
                <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3.5 py-2 fw-bold text-white shadow-sm opacity-90" data-bs-toggle="modal" data-bs-target="#claimSearchModal">
                    <i class="bi bi-shield-check me-1.5 text-warning"></i>व्यवसाय क्लेम करें
                </button>
                <a href="../add-contact.php" class="btn btn-primary btn-sm rounded-pill px-3.5 py-2 fw-bold shadow-sm">
                    <i class="bi bi-plus-circle me-1.5"></i>मुफ़्त लिस्टिंग जोड़ें
                </a>
                <a href="../logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 fw-semibold opacity-75">
                    <i class="bi bi-box-arrow-right me-1"></i>लॉगआउट
                </a>
            </div>
        </div>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show rounded-4 p-3.5 shadow-sm mb-4 small" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-6"></i><?php echo htmlspecialchars($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Stat Summary Cards Row -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="dashboard-stat-card d-flex align-items-center gap-3">
                <div class="dashboard-stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div>
                    <div class="h4 fw-bold text-dark mb-0 font-heading"><?php echo count($userListings); ?></div>
                    <div class="text-muted extra-small fw-semibold">कुल लिस्टिंग</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="dashboard-stat-card d-flex align-items-center gap-3">
                <div class="dashboard-stat-icon bg-warning-subtle text-warning-emphasis">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <div class="h4 fw-bold text-dark mb-0 font-heading">
                        <?php 
                            $claimedCount = 0;
                            foreach ($userListings as $l) {
                                if (($l['claimed_by_user_id'] ?? 0) == $user['id']) $claimedCount++;
                            }
                            echo $claimedCount;
                        ?>
                    </div>
                    <div class="text-muted extra-small fw-semibold">सत्यापित क्लेम</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="dashboard-stat-card d-flex align-items-center gap-3">
                <div class="dashboard-stat-icon bg-success-subtle text-success">
                    <i class="bi bi-credit-card-fill"></i>
                </div>
                <div>
                    <div class="h4 fw-bold text-dark mb-0 font-heading"><?php echo count($userPayments); ?></div>
                    <div class="text-muted extra-small fw-semibold">भुगतान लेनदेन</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="dashboard-stat-card d-flex align-items-center gap-3">
                <div class="dashboard-stat-icon bg-info-subtle text-info">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div>
                    <?php if (($user['plan_type'] ?? '') === 'PLATINUM'): ?>
                        <div class="h5 fw-bold text-danger mb-0 font-heading"><i class="bi bi-crown-fill me-1"></i>वीआईपी प्लैटिनम</div>
                    <?php elseif (($user['plan_type'] ?? '') === 'GOLD'): ?>
                        <div class="h5 fw-bold text-primary mb-0 font-heading"><i class="bi bi-patch-check-fill me-1"></i>गोल्ड सत्यापित</div>
                    <?php else: ?>
                        <div class="h5 fw-bold text-muted mb-0 font-heading">बेसिक फ्री</div>
                    <?php endif; ?>
                    <div class="text-muted extra-small fw-semibold">प्रोफ़ाइल सदस्यता स्थिति</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Column (4 Cols) -->
        <div class="col-lg-4">
            <!-- User Profile Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="text-center mb-3">
                    <h5 class="fw-bold text-primary mb-1 font-heading"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                    <span class="badge bg-light text-secondary border rounded-pill px-3 py-1 extra-small">
                        <i class="bi bi-person-workspace me-1 text-primary"></i><?php echo htmlspecialchars($user['designation'] ?: 'पेशेवर सदस्य'); ?>
                    </span>
                </div>

                <div class="bg-light p-3 rounded-3 small mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2.5">
                        <span class="text-muted"><i class="bi bi-phone me-2 text-primary"></i>मोबाइल</span>
                        <span class="fw-semibold text-dark">+91 <?php echo htmlspecialchars($user['mobile']); ?></span>
                    </div>
                    <?php if (!empty($user['whatsapp'])): ?>
                        <div class="d-flex align-items-center justify-content-between mb-2.5">
                            <span class="text-muted"><i class="bi bi-whatsapp me-2 text-success"></i>व्हाट्सएप</span>
                            <span class="fw-semibold text-dark">+91 <?php echo htmlspecialchars($user['whatsapp']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($user['email'])): ?>
                        <div class="d-flex align-items-center justify-content-between mb-2.5">
                            <span class="text-muted"><i class="bi bi-envelope me-2 text-primary"></i>ईमेल</span>
                            <span class="fw-medium text-dark text-truncate" style="max-width: 160px;"><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <hr class="text-secondary opacity-25 my-3">

                <!-- Quick Action Buttons -->
                <div class="d-flex flex-column gap-2">
                    <a href="edit-profile.php" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold shadow-xs py-2">
                        <i class="bi bi-pencil-square me-1"></i> प्रोफ़ाइल एवं सेटिंग्स संपादित करें
                    </a>
                    <button type="button" class="btn btn-warning btn-sm w-100 rounded-pill fw-bold text-dark shadow-xs py-2" data-bs-toggle="modal" data-bs-target="#upgradeProfileModal">
                        <i class="bi bi-lightning-charge-fill me-1 text-danger"></i> प्रोफ़ाइल प्लान अपग्रेड करें
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm w-100 rounded-pill fw-bold text-dark shadow-xs py-2" data-bs-toggle="modal" data-bs-target="#claimSearchModal">
                        <i class="bi bi-shield-check me-1"></i> मौजूद व्यवसाय क्लेम करें
                    </button>
                    <a href="../add-contact.php" class="btn btn-success btn-sm w-100 rounded-pill fw-bold shadow-xs py-2">
                        <i class="bi bi-plus-circle me-1"></i> नया व्यवसाय मुफ़्त में जोड़ें
                    </a>
                    <button type="button" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-semibold py-2" data-bs-toggle="modal" data-bs-target="#deleteProfileModal">
                        <i class="bi bi-trash me-1"></i> प्रोफ़ाइल डिलीट करें
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Listings Column (8 Cols) -->
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fw-bold font-heading mb-0 text-dark">
                    <i class="bi bi-journal-bookmark-fill text-primary me-2"></i>आपकी प्रविष्टियां (Listings)
                </h4>
                <a href="../add-contact.php" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                    <i class="bi bi-plus-lg me-1"></i>नई लिस्टिंग
                </a>
            </div>

            <?php if (empty($userListings)): ?>
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                    <i class="bi bi-journal-plus display-3 text-muted opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark">कोई लिस्टिंग नहीं मिली</h5>
                    <p class="text-muted small mb-4">आपके खाते से जुड़ी कोई प्रविष्टि नहीं पाई गई।</p>
                    <div>
                        <a href="../add-contact.php" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i>नई लिस्टिंग जोड़ें
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($userListings as $l): ?>
                        <div class="listing-compact-card border-start border-4 border-primary">
                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                <div>
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                        <h5 class="fw-bold text-dark mb-0 font-heading">
                                            <a href="../listing_detail.php?id=<?php echo $l['id']; ?>" class="text-dark text-decoration-none hover-primary">
                                                <?php echo htmlspecialchars($l['title']); ?>
                                            </a>
                                        </h5>
                                        <?php if (($l['status'] ?? 'ACTIVE') === 'ACTIVE'): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill extra-small">सक्रिय</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill extra-small">निष्क्रिय</span>
                                        <?php endif; ?>
                                        
                                        <?php if (($l['plan_type'] ?? '') === 'PLATINUM'): ?>
                                            <span class="badge bg-warning text-dark fw-bold rounded-pill extra-small"><i class="bi bi-crown-fill text-danger me-1"></i>वीआईपी प्लैटिनम</span>
                                        <?php elseif (($l['plan_type'] ?? '') === 'GOLD'): ?>
                                            <span class="badge bg-primary text-white rounded-pill extra-small"><i class="bi bi-patch-check-fill me-1"></i>गोल्ड</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-muted small d-flex flex-wrap align-items-center gap-3">
                                        <span><i class="bi bi-telephone me-1 text-primary"></i>+91 <?php echo htmlspecialchars($l['mobile']); ?></span>
                                        <span><i class="bi bi-folder me-1 text-info"></i><?php echo htmlspecialchars($l['category_name'] ?? 'सामान्य'); ?></span>
                                        <span><i class="bi bi-geo-alt me-1 text-warning"></i><?php echo htmlspecialchars($l['block_name'] ?? 'सारण जिला'); ?></span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                    <a href="../listing_detail.php?id=<?php echo $l['id']; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">देखें</a>
                                    <a href="../add-contact.php?edit=<?php echo $l['id']; ?>" class="btn btn-light btn-sm rounded-pill px-3 fw-semibold border">संपादित करें</a>
                                    <button type="button" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#upgradePlanModal<?php echo $l['id']; ?>">
                                        <i class="bi bi-lightning-charge-fill me-1"></i>अपग्रेड
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Listing Upgrade Plan Modal -->
                        <div class="modal fade" id="upgradePlanModal<?php echo $l['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header bg-dark text-white p-4">
                                        <div>
                                            <h5 class="modal-title fw-bold font-heading text-white mb-1"><i class="bi bi-credit-card-fill text-warning me-2"></i>सदस्यता प्लान अपग्रेड करें</h5>
                                            <p class="text-white-50 extra-small mb-0"><?php echo htmlspecialchars($l['title']); ?></p>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <form action="dashboard.php" method="POST" class="rzp-upgrade-form">
                                            <input type="hidden" name="action" value="upgrade_plan">
                                            <input type="hidden" name="listing_id" value="<?php echo $l['id']; ?>">

                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">टार्गेट प्लान चुनें</label>
                                                <div class="form-check border p-3 rounded-3 mb-2 bg-white">
                                                    <input class="form-check-input ms-0 me-2" type="radio" name="plan_type" id="planGold<?php echo $l['id']; ?>" value="GOLD" checked>
                                                    <label class="form-check-label w-100" for="planGold<?php echo $l['id']; ?>">
                                                        <strong class="text-primary">गोल्ड बिजनेस मेंबरशिप</strong> – ₹499.00
                                                        <small class="d-block text-muted">शीर्ष श्रेणी रैंकिंग, सत्यापित बैज एवं प्राथमिकता सहायता</small>
                                                    </label>
                                                </div>
                                                <div class="form-check border p-3 rounded-3 mb-2 bg-white">
                                                    <input class="form-check-input ms-0 me-2" type="radio" name="plan_type" id="planPlat<?php echo $l['id']; ?>" value="PLATINUM">
                                                    <label class="form-check-label w-100" for="planPlat<?php echo $l['id']; ?>">
                                                        <strong class="text-warning-emphasis"><i class="bi bi-crown-fill text-danger me-1"></i>वीआईपी प्लैटिनम मेंबरशिप</strong> – ₹1,499.00
                                                        <small class="d-block text-muted">होमपेज विशेष स्थान, वीआईपी क्राउन बैज एवं समर्पित हेल्पलाइन</small>
                                                    </label>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold py-2.5 text-dark shadow-sm">
                                                ऑनलाइन भुगतान के साथ आगे बढ़ें
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal 1: Business Claim Search Modal -->
<div class="modal fade" id="claimSearchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white p-4">
                <div>
                    <h5 class="modal-title fw-bold font-heading text-white mb-1"><i class="bi bi-search text-warning me-2"></i>मौजूद व्यवसाय खोजें एवं क्लेम करें</h5>
                    <p class="text-white-50 extra-small mb-0">अपनी कंपनी या पेशेवर लिस्टिंग खोजें और अपना स्वामित्व क्लेम करें।</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label for="claim_search_input" class="form-label small fw-semibold">व्यवसाय / दुकान / वकील का नाम दर्ज करें</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="claim_search_input" placeholder="जैसे: गुप्ता मेडिकल, अधिवक्ता कुमार, छपरा">
                    </div>
                </div>

                <div id="claimSearchResults" class="mb-3" style="max-height: 220px; overflow-y: auto;">
                    <div class="text-center py-4 text-muted extra-small">व्यवसाय खोजने के लिए नाम टाइप करना शुरू करें।</div>
                </div>

                <!-- Claim Form (Hidden until listing is selected) -->
                <div id="claimSubmitFormWrapper" style="display: none;" class="bg-light p-3.5 rounded-3 border">
                    <h6 class="fw-bold text-dark mb-2.5 small">चयनित लिस्टिंग: <span id="claimTargetTitle" class="text-primary"></span></h6>
                    <form action="dashboard.php" method="POST">
                        <input type="hidden" name="action" value="claim_business">
                        <input type="hidden" name="listing_id" id="claim_target_id" value="">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="claimant_name" class="form-label small fw-semibold">आपका पूरा नाम *</label>
                                <input type="text" class="form-control" id="claimant_name" name="claimant_name" value="<?php echo sanitizeInput($user['full_name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="claimant_mobile" class="form-label small fw-semibold">संपर्क मोबाइल नंबर *</label>
                                <input type="text" class="form-control" id="claimant_mobile" name="claimant_mobile" value="<?php echo sanitizeInput($user['mobile']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="role_title" class="form-label small fw-semibold">व्यवसाय में आपका पद / भूमिका</label>
                                <input type="text" class="form-control" id="role_title" name="role_title" placeholder="जैसे: मालिक / मैनेजर / पार्टनर">
                            </div>
                            <div class="col-md-6">
                                <label for="verification_proof" class="form-label small fw-semibold">सत्यापन विवरण</label>
                                <input type="text" class="form-control" id="verification_proof" name="verification_proof" placeholder="जैसे: विजिटिंग कार्ड / बिल">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold py-2 mt-3 text-dark shadow-xs">
                            स्वामित्व दावा सबमिट करें
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Profile Delete Modal -->
<div class="modal fade" id="deleteProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="bg-danger text-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0 text-white"><i class="bi bi-exclamation-triangle-fill me-2"></i> प्रोफ़ाइल डिलीट करें</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <form action="dashboard.php" method="POST">
                <input type="hidden" name="action" value="delete_profile">
                <div class="modal-body p-4 bg-light">
                    <div class="alert alert-danger border-danger-subtle rounded-3 small mb-3">
                        <i class="bi bi-shield-exclamation me-1"></i> <strong>चेतावनी:</strong> प्रोफ़ाइल हटाना स्थायी है।
                    </div>
                    <p class="small text-muted mb-3">आपकी प्रोफ़ाइल जानकारी और खाता हमेशा के लिए डिलीट हो जाएंगे।</p>
                    <div class="mb-3">
                        <label for="confirm_delete_hi" class="form-label fw-bold small text-dark">पुष्टि के लिए <span class="text-danger">DELETE</span> टाइप करें:</label>
                        <input type="text" name="confirm_delete" id="confirm_delete_hi" class="form-control rounded-3" placeholder="बड़े अक्षरों में DELETE टाइप करें" required autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer bg-white p-3 border-top">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">रद्द करें</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold"><i class="bi bi-trash-fill me-1"></i> प्रोफ़ाइल हमेशा के लिए डिलीट करें</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 3: Upgrade Profile Membership Modal -->
<div class="modal fade" id="upgradeProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white p-4">
                <div>
                    <h5 class="modal-title fw-bold font-heading text-white mb-1"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>पेशेवर प्रोफ़ाइल मेंबरशिप अपग्रेड करें</h5>
                    <p class="text-white-50 extra-small mb-0">सारण निर्देशिका खोज में अपनी प्रोफ़ाइल दृश्यता बढ़ाएं।</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="bg-light p-3 rounded-3 mb-3 border">
                    <strong class="d-block text-dark mb-1"><?php echo htmlspecialchars($user['full_name']); ?></strong>
                    <span class="extra-small text-muted">वर्तमान प्रोफ़ाइल प्लान: </span>
                    <span class="badge bg-secondary"><?php echo htmlspecialchars($user['plan_type'] ?? 'FREE'); ?></span>
                </div>

                <form action="dashboard.php" method="POST" class="rzp-upgrade-form">
                    <input type="hidden" name="action" value="upgrade_plan">
                    <input type="hidden" name="listing_id" value="0">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">टार्गेट मेंबरशिप प्लान चुनें</label>
                        <div class="form-check border p-3 rounded-3 mb-2 bg-white">
                            <input class="form-check-input ms-0 me-2" type="radio" name="plan_type" id="profilePlanGold" value="GOLD" checked>
                            <label class="form-check-label w-100" for="profilePlanGold">
                                <strong class="text-primary">गोल्ड सत्यापित पेशेवर</strong> – ₹499.00 / वर्ष
                                <small class="d-block text-muted">प्रोफ़ाइल पर गोल्ड सत्यापित बैज, प्राथमिकता खोज इंडेक्सिंग एवं सीधा संपर्क एक्सेस</small>
                            </label>
                        </div>
                        <div class="form-check border p-3 rounded-3 mb-2 bg-white">
                            <input class="form-check-input ms-0 me-2" type="radio" name="plan_type" id="profilePlanPlat" value="PLATINUM">
                            <label class="form-check-label w-100" for="profilePlanPlat">
                                <strong class="text-warning-emphasis"><i class="bi bi-crown-fill text-danger me-1"></i>वीआईपी प्लैटिनम पेशेवर</strong> – ₹1,499.00 / वर्ष
                                <small class="d-block text-muted">सार्वजनिक प्रोफ़ाइल पर वीआईपी क्राउन बैज, सारण निर्देशिका में शीर्ष रैंकिंग एवं विशेष स्थान</small>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold py-2.5 text-dark shadow-sm">
                        ऑनलाइन भुगतान के साथ आगे बढ़ें
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Live AJAX Claim Business Search Script
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('claim_search_input');
    const resultsBox = document.getElementById('claimSearchResults');
    const formWrapper = document.getElementById('claimSubmitFormWrapper');
    const listingIdInput = document.getElementById('claim_target_id');
    const listingTitleBox = document.getElementById('claimTargetTitle');

    let debounceTimer;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length < 2) {
                resultsBox.innerHTML = '<div class="text-center py-4 text-muted extra-small">व्यवसाय खोजने के लिए नाम टाइप करना शुरू करें।</div>';
                return;
            }

            resultsBox.innerHTML = '<div class="text-center py-4 text-muted extra-small"><span class="spinner-border spinner-border-sm me-2"></span>खोज जारी है...</div>';

            debounceTimer = setTimeout(() => {
                fetch('../ajax_claim_search.php?q=' + encodeURIComponent(query))
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            resultsBox.innerHTML = '<div class="text-center py-4 text-muted extra-small">कोई मेल खाता हुआ व्यवसाय नहीं मिला।</div>';
                            return;
                        }

                        let html = '<div class="list-group list-group-flush border rounded-3">';
                        data.forEach(item => {
                            html += `
                                <button type="button" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between p-3 claim-select-btn" data-id="${item.id}" data-title="${item.title}">
                                    <div>
                                        <div class="fw-bold text-dark mb-0">${item.title}</div>
                                        <small class="text-muted"><i class="bi bi-telephone me-1"></i>+91 ${item.mobile} | <i class="bi bi-geo-alt me-1"></i>${item.block_name}</small>
                                    </div>
                                    <span class="btn btn-outline-primary btn-sm rounded-pill px-3">इसे चुनें</span>
                                </button>
                            `;
                        });
                        html += '</div>';
                        resultsBox.innerHTML = html;

                        document.querySelectorAll('.claim-select-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const id = this.getAttribute('data-id');
                                const title = this.getAttribute('data-title');

                                listingIdInput.value = id;
                                listingTitleBox.textContent = title;
                                formWrapper.style.display = 'block';
                                formWrapper.scrollIntoView({ behavior: 'smooth' });
                            });
                        });
                    })
                    .catch(err => {
                        resultsBox.innerHTML = '<div class="text-center py-4 text-danger extra-small">खोजते समय एक त्रुटि हुई।</div>';
                    });
            }, 300);
        });
    }

    // Razorpay Online Payment Checkout Handler (AdvocateIndex Pattern)
    document.querySelectorAll('.rzp-upgrade-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            const originalBtnText = btn.innerHTML;

            const listingId = form.querySelector('input[name="listing_id"]').value;
            const selectedPlan = form.querySelector('input[name="plan_type"]:checked') ? form.querySelector('input[name="plan_type"]:checked').value : 'GOLD';

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>सुरक्षित भुगतान शुरू हो रहा है...';

            const formData = new FormData();
            formData.append('action', 'create_order');
            formData.append('listing_id', listingId);
            formData.append('plan_type', selectedPlan);

            fetch('../api/process_payment_api.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalBtnText;

                if (data.status === 'success' && typeof Razorpay !== 'undefined') {
                    const options = {
                        "key": data.key,
                        "amount": data.amount,
                        "currency": data.currency,
                        "name": "Saran Index",
                        "description": "मेंबरशिप अपग्रेड - " + selectedPlan + " प्लान",
                        "image": "../assets/img/logo.png",
                        "order_id": data.order_id,
                        "prefill": {
                            "name": data.user.name,
                            "contact": data.user.mobile,
                            "email": data.user.email
                        },
                        "theme": {
                            "color": "#1e3a8a"
                        },
                        "handler": function (response) {
                            const verifyData = new FormData();
                            verifyData.append('action', 'verify_payment');
                            verifyData.append('transaction_id', data.transaction_id);
                            verifyData.append('razorpay_payment_id', response.razorpay_payment_id || '');
                            verifyData.append('razorpay_order_id', response.razorpay_order_id || data.order_id);
                            verifyData.append('razorpay_signature', response.razorpay_signature || '');

                            fetch('../api/process_payment_api.php', {
                                method: 'POST',
                                body: verifyData
                            })
                            .then(res => res.json())
                            .then(vData => {
                                if (vData.status === 'success') {
                                    alert('रेजरपे भुगतान सफल रहा! आपका ' + selectedPlan + ' प्लान सक्रिय कर दिया गया है।');
                                    window.location.reload();
                                } else {
                                    alert('रेजरपे सत्यापन त्रुटि: ' + vData.message);
                                }
                            });
                        }
                    };
                    const rzp = new Razorpay(options);
                    rzp.open();
                } else {
                    alert('ऑर्डर निर्माण में त्रुटि: ' + (data.message || 'कृपया पुनः प्रयास करें।'));
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
                console.error('Razorpay Error:', err);
                alert('रेजरपे भुगतान शुरू करते समय त्रुटि हुई।');
            });
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

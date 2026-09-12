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
    $post_data = [
        'full_name'           => $_POST['full_name'] ?? '',
        'username_handle'     => $_POST['username_handle'] ?? '',
        'email'               => $_POST['email'] ?? '',
        'whatsapp'            => $_POST['whatsapp'] ?? '',
        'business_name'       => $_POST['business_name'] ?? '',
        'designation'         => $_POST['designation'] ?? '',
        'profession_category' => $_POST['profession_category'] ?? '',
        'category_id'         => $_POST['category_id'] ?? null,
        'subcategory_id'      => $_POST['subcategory_id'] ?? null,
        'specialization'      => $_POST['specialization'] ?? '',
        'education'           => $_POST['education'] ?? '',
        'experience_years'    => $_POST['experience_years'] ?? '',
        'office_hours'        => $_POST['office_hours'] ?? '',
        'block_id'            => $_POST['block_id'] ?? null,
        'address'             => $_POST['address'] ?? '',
        'pincode'             => $_POST['pincode'] ?? '',
        'bio'                 => $_POST['bio'] ?? '',
        'about'               => $_POST['about'] ?? '',
        'profile_visibility'  => $_POST['profile_visibility'] ?? 'PUBLIC',
        'mobile_visibility'   => $_POST['mobile_visibility'] ?? 'PUBLIC',
        'email_visibility'    => $_POST['email_visibility'] ?? 'PUBLIC',
        'address_visibility'  => $_POST['address_visibility'] ?? 'PUBLIC'
    ];

    if (!empty($_FILES['profile_image_file']['tmp_name'])) {
        $uploaded = uploadUserProfilePhoto($_FILES['profile_image_file'], $user['id']);
        if ($uploaded) {
            $post_data['profile_image'] = $uploaded;
        }
    }

    if (updateProfessionalUserProfile($user['id'], $post_data)) {
        if (!empty($_POST['new_password']) && strlen($_POST['new_password']) >= 6) {
            $passHash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $db = getDB();
            if ($db) {
                $db->prepare("UPDATE users SET password_hash = :p WHERE id = :id")->execute(['p' => $passHash, 'id' => $user['id']]);
            }
        }
        $msg = "प्रोफ़ाइल जानकारी सफलतापूर्वक अपडेट कर दी गई!";
        $msg_type = 'success';
        $user = getLoggedInUser(); // Refresh user data
    } else {
        $msg = $_SESSION['profile_update_error'] ?? "प्रोफ़ाइल विवरण अपडेट करने में विफल।";
        unset($_SESSION['profile_update_error']);
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
                        $msg = "ऑनलाइन भुगतान अनुरोध शुरू किया गया (Txn: " . $payment['transaction_id'] . ")। भुगतान पुष्टि पर प्लान सक्रिय हो जाएगा।";
                        $msg_type = 'info';
                    } else {
                        $msg = "भुगतान प्रक्रिया विफल रही। कृपया पुनः प्रयास करें।";
                        $msg_type = 'danger';
                    }
                }
            } catch (PDOException $e) {
                $msg = "प्लान अपग्रेड करने में त्रुटि: " . $e->getMessage();
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
$profileCompletion = getUserProfileCompletionDetails($user);

$active_listings_count = 0;
$pending_claims_count = 0;
$total_user_listing_views = 0;
foreach ($userListings as $ul) {
    if (($ul['status'] ?? '') === 'ACTIVE') $active_listings_count++;
    if (($ul['claim_status'] ?? '') === 'PENDING') $pending_claims_count++;
    $total_user_listing_views += intval($ul['view_count'] ?? 0);
}
$user_profile_views = intval($user['counter'] ?? 0);

$page_title = "मेरा खाता एवं डैशबोर्ड – सारण इंडेक्स";
$meta_description = "सारण इंडेक्स पर आपका उपयोगकर्ता खाता डैशबोर्ड। अपनी लिस्टिंग, प्रोफ़ाइल, ऑनलाइन भुगतान और व्यवसाय निर्देशिका प्रविष्टियों को प्रबंधित करें।";

require_once __DIR__ . '/includes/header.php';
?>

<style>
:root {
    --dash-primary: #1e40af;
    --dash-primary-hover: #1d4ed8;
    --dash-accent: #f59e0b;
    --dash-bg-card: #ffffff;
    --dash-border: #e2e8f0;
    --dash-text-dark: #0f172a;
    --dash-text-muted: #64748b;
}

/* Premium Dashboard Container */
.dashboard-wrapper {
    background-color: #f8fafc;
    min-height: 85vh;
}

/* Hero Header Banner */
.dash-hero {
    background: linear-gradient(135deg, #0b1329 0%, #1e293b 60%, #0f172a 100%);
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.dash-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 380px;
    height: 380px;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, transparent 70%);
    pointer-events: none;
}
.dash-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: 20%;
    width: 250px;
    height: 250px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
    pointer-events: none;
}

/* Avatar Styling */
.dash-avatar-wrapper {
    position: relative;
    display: inline-block;
    flex-shrink: 0;
}
.dash-avatar-img {
    width: 76px;
    height: 76px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid rgba(255, 255, 255, 0.9);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}
.dash-avatar-placeholder {
    width: 76px;
    height: 76px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.85rem;
    font-weight: 700;
    color: #ffffff;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border: 3px solid rgba(255, 255, 255, 0.9);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}
.dash-status-dot {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    background-color: #10b981;
    border: 2px solid #ffffff;
    border-radius: 50%;
}

/* Stat Cards */
.stat-card-modern {
    background: #ffffff;
    border: 1px solid var(--dash-border);
    border-radius: 16px;
    padding: 16px 14px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    height: 100%;
}
.stat-card-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    border-color: #cbd5e1;
}
.stat-icon-pill {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

/* Custom Tabs Bar */
.dash-nav-pills {
    display: flex;
    flex-wrap: nowrap !important;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
    gap: 8px;
    padding: 6px !important;
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--dash-border);
}
.dash-nav-pills::-webkit-scrollbar {
    display: none;
}
.dash-nav-pills .nav-link {
    color: var(--dash-text-muted);
    font-weight: 600;
    font-size: 0.88rem;
    padding: 10px 18px;
    border-radius: 12px;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    white-space: nowrap;
    flex-shrink: 0;
}
.dash-nav-pills .nav-link:hover {
    color: var(--dash-primary);
    background-color: #f1f5f9;
}
.dash-nav-pills .nav-link.active {
    color: #ffffff;
    background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}

/* Listing Modern Card */
.listing-entry-card {
    background: #ffffff;
    border: 1px solid var(--dash-border);
    border-radius: 16px;
    padding: 20px;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
.listing-entry-card:hover {
    border-color: #93c5fd;
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
}
.listing-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

/* Public URL Badge Strip */
.public-url-strip {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 6px 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8rem;
}
.public-url-strip a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
}
.public-url-strip a:hover {
    text-decoration: underline;
}

/* Action Button Pills */
.btn-pill-action {
    border-radius: 50rem;
    padding: 7px 16px;
    font-size: 0.825rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s ease;
    white-space: nowrap;
}

/* Table Style */
.dash-table th {
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
    color: #64748b;
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 16px;
    white-space: nowrap;
}
.dash-table td {
    padding: 14px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    white-space: nowrap;
}

/* ==========================================================
   MOBILE-FIRST RESPONSIVE OPTIMIZATIONS
   ========================================================== */
@media (max-width: 991.98px) {
    .dash-hero-actions {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }
    .dash-hero-actions .btn-pill-action {
        width: 100%;
    }
}

@media (max-width: 767.98px) {
    .dashboard-wrapper {
        padding-top: 14px !important;
        padding-bottom: 24px !important;
    }
    .dash-hero {
        padding: 18px 14px !important;
        border-radius: 16px;
    }
    .dash-avatar-img,
    .dash-avatar-placeholder {
        width: 58px;
        height: 58px;
        font-size: 1.45rem;
    }
    .dash-hero h3 {
        font-size: 1.2rem;
    }
    .dash-hero-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        width: 100%;
    }
    .dash-hero-actions .btn-pill-action {
        padding: 8px 10px;
        font-size: 0.78rem;
        width: 100%;
    }
    .profile-circle-stat {
        width: 54px !important;
        height: 54px !important;
        font-size: 1.1rem !important;
    }
    .stat-card-modern {
        padding: 12px 10px;
        border-radius: 14px;
        min-height: 68px;
    }
    .stat-card-modern .h4 {
        font-size: 1.2rem !important;
    }
    .stat-icon-pill {
        width: 36px;
        height: 36px;
        font-size: 1.05rem;
        border-radius: 10px;
    }
    .dash-nav-pills .nav-link {
        font-size: 0.8rem;
        padding: 8px 12px;
    }
    .listing-entry-card {
        padding: 14px;
        border-radius: 14px;
    }
    .listing-icon-box {
        width: 38px;
        height: 38px;
        font-size: 1.15rem;
        border-radius: 10px;
    }
    .public-url-strip {
        flex-direction: column;
        align-items: stretch;
        gap: 6px;
        padding: 8px 10px;
    }
    .public-url-strip a {
        word-break: break-all;
        white-space: normal;
        font-size: 0.75rem;
    }
    .public-url-strip .copy-url-btn {
        align-self: flex-end;
        width: auto;
    }
    .listing-mobile-actions {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 6px;
        margin-top: 10px;
    }
    .listing-mobile-actions .btn {
        width: 100%;
        justify-content: center;
        padding: 6px 4px;
        font-size: 0.78rem;
    }
}

@media (max-width: 575.98px) {
    .dash-hero .d-flex.align-items-center.gap-3\.5 {
        gap: 10px !important;
    }
    .listing-mobile-actions {
        grid-template-columns: 1fr 1fr 1fr;
        gap: 4px;
    }
    .listing-mobile-actions .btn {
        font-size: 0.74rem;
        padding: 6px 2px;
    }
}
</style>

<div class="dashboard-wrapper py-4">
    <div class="container">

        <!-- Top Hero Profile Banner -->
        <div class="dash-hero p-4 p-md-4 mb-4">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4 position-relative" style="z-index: 1;">
                
                <!-- Left: Profile Identity -->
                <div class="d-flex align-items-center gap-3.5 flex-wrap flex-sm-nowrap">
                    <div class="dash-avatar-wrapper">
                        <?php if (!empty($user['profile_image']) && file_exists(__DIR__ . '/../' . $user['profile_image'])): ?>
                            <img src="../<?php echo sanitizeInput($user['profile_image']); ?>" alt="<?php echo sanitizeInput($user['full_name']); ?>" class="dash-avatar-img">
                        <?php else: ?>
                            <div class="dash-avatar-placeholder">
                                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        <span class="dash-status-dot" title="सक्रिय खाता"></span>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h3 class="fw-bold text-white mb-0 font-heading">
                                <?php echo htmlspecialchars($user['full_name']); ?>
                            </h3>
                            <?php if (!empty($user['username_handle'])): 
                                $cleanHandle = ltrim($user['username_handle'], '@');
                            ?>
                                <a href="../@<?php echo sanitizeInput($cleanHandle); ?>" target="_blank" class="badge bg-warning text-dark text-decoration-none fw-bold px-2.5 py-1 rounded-pill small">
                                    <i class="bi bi-at"></i><?php echo sanitizeInput($cleanHandle); ?>
                                </a>
                            <?php endif; ?>
                            <a href="edit-profile.php" class="badge <?php echo $profileCompletion['badge_class']; ?> text-decoration-none border rounded-pill px-2.5 py-1 small" title="प्रोफ़ाइल मजबूती: <?php echo $profileCompletion['percentage']; ?>%">
                                <i class="bi bi-speedometer2 me-1"></i>प्रोफ़ाइल <?php echo $profileCompletion['percentage']; ?>% (<?php echo $profileCompletion['level_hi']; ?>)
                            </a>
                        </div>

                        <div class="d-flex align-items-center gap-3 text-white-50 small flex-wrap">
                            <span><i class="bi bi-telephone-fill me-1 text-warning"></i>+91 <?php echo htmlspecialchars($user['mobile']); ?></span>
                            <?php if (!empty($user['email'])): ?>
                                <span><i class="bi bi-envelope-fill me-1 text-info"></i><?php echo htmlspecialchars($user['email']); ?></span>
                            <?php endif; ?>
                            <span><i class="bi bi-person-badge me-1 text-white"></i>ID: #<?php echo intval($user['id']); ?></span>
                            <?php if (!empty($user['business_name'])): ?>
                                <span><i class="bi bi-building me-1 text-warning"></i><?php echo htmlspecialchars($user['business_name']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Right: Quick Action Controls -->
                <div class="dash-hero-actions d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-warning text-dark btn-pill-action shadow-sm" data-bs-toggle="modal" data-bs-target="#claimSearchModal">
                        <i class="bi bi-shield-check"></i> व्यवसाय क्लेम करें
                    </button>
                    <a href="../add-contact.php" class="btn btn-primary btn-pill-action shadow-sm">
                        <i class="bi bi-plus-circle"></i> नई लिस्टिंग
                    </a>
                    <a href="edit-profile.php" class="btn btn-outline-light btn-pill-action">
                        <i class="bi bi-pencil-square"></i> प्रोफ़ाइल बदलें
                    </a>
                    <a href="../logout.php" class="btn btn-outline-light btn-pill-action opacity-75" title="लॉगआउट">
                        <i class="bi bi-box-arrow-right"></i> लॉगआउट
                    </a>
                </div>
            </div>
        </div>

        <!-- Alert Notification -->
        <?php if ($msg): ?>
            <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show rounded-4 p-3 shadow-sm mb-4 d-flex align-items-center gap-2 small" role="alert">
                <i class="bi <?php echo $msg_type === 'success' ? 'bi-check-circle-fill text-success' : 'bi-info-circle-fill text-primary'; ?> fs-5"></i>
                <div class="flex-grow-1"><?php echo htmlspecialchars($msg); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- VIP Profile Upgrade Spotlight Banner (Payment Option & VIP Links Perks) -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #1e3a8a 100%); border: 1px solid rgba(255, 255, 255, 0.1) !important;">
            <div class="card-body p-3.5 p-md-4 position-relative">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                            <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill extra-small shadow-xs">
                                <i class="bi bi-crown-fill text-danger me-1"></i>वीआईपी प्रो मेंबरशिप
                            </span>
                            <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 rounded-pill px-2.5 py-1 extra-small">
                                <i class="bi bi-shield-check text-success me-1"></i>तत्काल ऑनलाइन एक्टिवेशन
                            </span>
                        </div>
                        <h4 class="fw-bold text-white mb-2 font-heading fs-5">
                            वीआईपी लिंक एवं गूगल मैप्स अनलॉक करने के लिए प्रोफ़ाइल अपग्रेड करें
                        </h4>
                        <p class="text-white text-opacity-80 small mb-3">
                            गोल्ड और वीआईपी प्लैटिनम प्रोफ़ाइल सदस्य अपने सार्वजनिक पेज पर गूगल मैप्स लोकेशन लिंक, वेबसाइट पोर्टफोलियो, भाषाएं, सोशल मीडिया प्रोफाइल जोड़ सकते हैं और आधिकारिक सत्यापित बैज प्रदर्शित कर सकते हैं।
                        </p>
                        <div class="d-flex flex-wrap gap-1.5 text-white-50 extra-small">
                            <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 py-1.5 px-2 rounded-pill"><i class="bi bi-geo-alt-fill text-danger me-1"></i>गूगल मैप्स लिंक</span>
                            <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 py-1.5 px-2 rounded-pill"><i class="bi bi-globe2 text-info me-1"></i>वेबसाइट एवं पोर्टफोलियो</span>
                            <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 py-1.5 px-2 rounded-pill"><i class="bi bi-whatsapp text-success me-1"></i>सोशल मीडिया</span>
                            <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 py-1.5 px-2 rounded-pill"><i class="bi bi-translate text-warning me-1"></i>भाषाएं</span>
                            <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 py-1.5 px-2 rounded-pill"><i class="bi bi-patch-check-fill text-primary me-1"></i>सत्यापित बैज</span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end text-center mt-3 mt-lg-0">
                        <div class="d-inline-flex flex-column align-items-lg-end align-items-center w-100">
                            <div class="text-warning fw-bold fs-5 mb-1 font-heading">मात्र ₹499<small class="text-white-50 fs-6 fw-normal">/वर्ष से</small></div>
                            <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2.5 shadow-sm d-inline-flex align-items-center justify-content-center gap-2 w-100" style="max-width: 260px;" data-bs-toggle="modal" data-bs-target="#upgradeProfileModal">
                                <i class="bi bi-lightning-charge-fill text-danger"></i> प्रोफ़ाइल प्लान अपग्रेड करें
                            </button>
                            <span class="text-white-50 extra-small mt-1.5 d-block"><i class="bi bi-qr-code me-1"></i>UPI, GPay, कार्ड्स एवं नेटबैंकिंग</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Strength & Completion Percentage Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
            <div class="card-body p-3.5 p-md-4">
                <div class="row align-items-center g-3">
                    <div class="col-lg-7">
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative flex-shrink-0">
                                <div class="profile-circle-stat d-flex align-items-center justify-content-center rounded-circle border border-3 border-<?php echo $profileCompletion['color']; ?> bg-<?php echo $profileCompletion['color']; ?>-subtle text-<?php echo $profileCompletion['color']; ?> fw-bold fs-4 shadow-xs" style="width: 68px; height: 68px;">
                                    <?php echo $profileCompletion['percentage']; ?>%
                                </div>
                            </div>
                            <div class="min-w-0">
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    <h5 class="fw-bold text-dark mb-0 font-heading fs-6">प्रोफ़ाइल मजबूती स्थिति: <?php echo $profileCompletion['level_hi']; ?></h5>
                                    <span class="badge <?php echo $profileCompletion['badge_class']; ?> rounded-pill px-2.5 py-0.5 extra-small">
                                        <?php echo $profileCompletion['percentage']; ?>% पूर्ण (<?php echo $profileCompletion['completed_count']; ?>/<?php echo $profileCompletion['total_count']; ?>)
                                    </span>
                                </div>
                                <p class="text-muted small mb-0">
                                    <?php if ($profileCompletion['percentage'] >= 100): ?>
                                        <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>आपकी प्रोफ़ाइल 100% पूर्ण है!</span> आपको सारण इंडेक्स डायरेक्टरी में शीर्ष प्राथमिकता और दृश्यता प्राप्त है।
                                    <?php else: ?>
                                        अपनी प्रोफ़ाइल के शेष विवरण पूरे करें ताकि डायरेक्टरी सर्च में आपकी रैंकिंग बढ़े और अधिक ग्राहक कॉल प्राप्त हों।
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="progress rounded-pill mt-3 shadow-xs" style="height: 8px; background-color: #e2e8f0;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated <?php echo $profileCompletion['progress_class']; ?>" role="progressbar" style="width: <?php echo $profileCompletion['percentage']; ?>%" aria-valuenow="<?php echo $profileCompletion['percentage']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <?php if (!empty($profileCompletion['missing_items'])): ?>
                            <div class="bg-light p-3 rounded-3 border">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="extra-small fw-bold text-uppercase text-muted letter-spacing-1">
                                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i>बाकी विवरण (+% प्राप्त करें):
                                    </span>
                                    <a href="edit-profile.php" class="extra-small text-primary fw-bold text-decoration-none">सभी पूरा करें <i class="bi bi-arrow-right"></i></a>
                                </div>
                                <div class="d-flex flex-wrap gap-1.5">
                                    <?php 
                                    $missingSlice = array_slice($profileCompletion['missing_items'], 0, 3);
                                    foreach ($missingSlice as $mItem): ?>
                                        <a href="edit-profile.php#<?php echo $mItem['field_id']; ?>" class="badge bg-white text-dark border text-decoration-none py-1.5 px-2.5 rounded-pill extra-small shadow-xs d-inline-flex align-items-center gap-1">
                                            <i class="bi <?php echo $mItem['icon']; ?> text-primary"></i>
                                            <span><?php echo $mItem['title_hi']; ?></span>
                                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill">+<?php echo $mItem['weight']; ?>%</span>
                                        </a>
                                    <?php endforeach; ?>
                                    <?php if (count($profileCompletion['missing_items']) > 3): ?>
                                        <a href="edit-profile.php" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none py-1.5 px-2.5 rounded-pill extra-small">
                                            +<?php echo count($profileCompletion['missing_items']) - 3; ?> अन्य
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="bg-success-subtle p-3 rounded-3 border border-success-subtle text-center">
                                <i class="bi bi-patch-check-fill text-success fs-3 mb-1 d-block"></i>
                                <div class="fw-bold text-success-emphasis small">100% पूर्ण एवं सत्यापित प्रोफ़ाइल!</div>
                                <div class="text-muted extra-small">आपकी व्यावसायिक प्रोफ़ाइल उच्च विश्वसनीयता स्कोर के साथ सक्रिय है।</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat Metrics Grid Strip -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="stat-icon-pill bg-primary-subtle text-primary">
                        <i class="bi bi-shop"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold text-dark mb-0 font-heading"><?php echo count($userListings); ?></div>
                        <div class="text-muted extra-small fw-semibold">कुल लिस्टिंग्स</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="stat-icon-pill bg-success-subtle text-success">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold text-dark mb-0 font-heading"><?php echo $active_listings_count; ?></div>
                        <div class="text-muted extra-small fw-semibold">सक्रिय एवं लाइव</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="stat-icon-pill bg-info-subtle text-info">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold text-dark mb-0 font-heading"><?php echo number_format($total_user_listing_views); ?></div>
                        <div class="text-muted extra-small fw-semibold">लिस्टिंग दृश्य</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="stat-icon-pill bg-primary-subtle text-primary">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold text-dark mb-0 font-heading"><?php echo number_format($user_profile_views); ?></div>
                        <div class="text-muted extra-small fw-semibold">प्रोफ़ाइल दृश्य</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="stat-icon-pill bg-warning-subtle text-warning-emphasis">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold text-dark mb-0 font-heading"><?php echo $pending_claims_count; ?></div>
                        <div class="text-muted extra-small fw-semibold">क्लेम समीक्षा में</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="stat-icon-pill bg-secondary-subtle text-secondary">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold text-dark mb-0 font-heading"><?php echo count($userPayments); ?></div>
                        <div class="text-muted extra-small fw-semibold">लेनदेन रसीदें</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Layout: 4 Cols Sidebar + 8 Cols Content -->
        <div class="row g-4">
            
            <!-- Left Sidebar (4 Cols) -->
            <div class="col-lg-4">
                
                <!-- Account Profile Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h6 class="fw-bold text-dark mb-0 font-heading">
                            <i class="bi bi-person-badge text-primary me-2"></i>प्रोफ़ाइल विवरण
                        </h6>
                        <a href="edit-profile.php" class="small text-primary text-decoration-none fw-semibold">
                            <i class="bi bi-pencil me-1"></i>संपादित करें
                        </a>
                    </div>

                    <div class="mb-3 p-2.5 bg-light rounded-3 border">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="extra-small fw-semibold text-muted">प्रोफ़ाइल मजबूती</span>
                            <span class="extra-small fw-bold text-<?php echo $profileCompletion['color']; ?>"><?php echo $profileCompletion['percentage']; ?>% (<?php echo $profileCompletion['level_hi']; ?>)</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 6px; background-color: #e2e8f0;">
                            <div class="progress-bar <?php echo $profileCompletion['progress_class']; ?>" role="progressbar" style="width: <?php echo $profileCompletion['percentage']; ?>%"></div>
                        </div>
                    </div>

                    <div class="small">
                        <div class="d-flex align-items-center justify-content-between mb-2.5 py-1 border-bottom border-light">
                            <span class="text-muted"><i class="bi bi-person me-2 text-primary"></i>नाम</span>
                            <span class="fw-bold text-dark"><?php echo htmlspecialchars($user['full_name']); ?></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2.5 py-1 border-bottom border-light">
                            <span class="text-muted"><i class="bi bi-telephone me-2 text-primary"></i>मोबाइल</span>
                            <span class="fw-semibold text-dark">+91 <?php echo htmlspecialchars($user['mobile']); ?></span>
                        </div>
                        <?php if (!empty($user['whatsapp'])): ?>
                            <div class="d-flex align-items-center justify-content-between mb-2.5 py-1 border-bottom border-light">
                                <span class="text-muted"><i class="bi bi-whatsapp me-2 text-success"></i>व्हाट्सएप</span>
                                <span class="fw-medium text-dark">+91 <?php echo htmlspecialchars($user['whatsapp']); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($user['email'])): ?>
                            <div class="d-flex align-items-center justify-content-between mb-2.5 py-1 border-bottom border-light">
                                <span class="text-muted"><i class="bi bi-envelope me-2 text-primary"></i>ईमेल</span>
                                <span class="fw-medium text-dark text-truncate" style="max-width: 170px;" title="<?php echo htmlspecialchars($user['email']); ?>"><?php echo htmlspecialchars($user['email']); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($user['designation'])): ?>
                            <div class="d-flex align-items-center justify-content-between mb-2.5 py-1 border-bottom border-light">
                                <span class="text-muted"><i class="bi bi-briefcase me-2 text-primary"></i>पद/व्यवसाय</span>
                                <span class="fw-medium text-dark"><?php echo htmlspecialchars($user['designation']); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($user['business_name'])): ?>
                            <div class="d-flex align-items-center justify-content-between mb-2 py-1">
                                <span class="text-muted"><i class="bi bi-building me-2 text-primary"></i>फर्म/दुकान</span>
                                <span class="fw-semibold text-dark text-truncate" style="max-width: 160px;"><?php echo htmlspecialchars($user['business_name']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2 mt-3 pt-2">
                        <a href="edit-profile.php" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold py-2">
                            <i class="bi bi-pencil-square me-1.5"></i>प्रोफ़ाइल एवं बायो अपडेट करें
                        </a>
                        <button type="button" class="btn btn-warning text-dark btn-sm rounded-pill fw-bold py-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#upgradeProfileModal">
                            <i class="bi bi-lightning-charge-fill text-danger me-1"></i>प्रोफ़ाइल प्लान अपग्रेड करें
                        </button>
                    </div>
                </div>

                <!-- Account Security & Settings Box -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h6 class="fw-bold text-dark mb-3 font-heading border-bottom pb-2">
                        <i class="bi bi-shield-lock text-primary me-2"></i>सुरक्षा एवं सेटिंग्स
                    </h6>

                    <div class="d-grid gap-2">
                        <a href="../change-password.php" class="btn btn-light btn-sm text-start rounded-3 fw-semibold p-2.5 border">
                            <i class="bi bi-key text-primary me-2 fs-6"></i>पासवर्ड बदलें
                        </a>
                        <button type="button" class="btn btn-light btn-sm text-start rounded-3 fw-semibold p-2.5 border text-danger" data-bs-toggle="modal" data-bs-target="#deleteProfileModal">
                            <i class="bi bi-trash text-danger me-2 fs-6"></i>खाता हमेशा के लिए डिलीट करें
                        </button>
                    </div>
                </div>

                <!-- Helpdesk & Support Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-headset fs-4 text-warning"></i>
                        <h6 class="fw-bold mb-0 text-white">ऑनलाइन सहायता केंद्र</h6>
                    </div>
                    <p class="small text-white-50 mb-3">लिस्टिंग क्लेम, टैक्स रसीद या मेंबरशिप अपग्रेड में सहायता चाहिए?</p>
                    <div>
                        <a href="../contact.php" class="btn btn-warning text-dark btn-sm rounded-pill w-100 fw-bold py-2 shadow-xs">
                            <i class="bi bi-envelope-fill me-1"></i>सपोर्ट टीम से संपर्क करें
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Content Area (8 Cols) -->
            <div class="col-lg-8">

                <!-- Navigation Tabs -->
                <ul class="nav dash-nav-pills gap-2 mb-4 bg-white p-2 rounded-4 shadow-sm border" id="dashboardTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="listings-tab" data-bs-toggle="pill" data-bs-target="#tab-listings" type="button" role="tab">
                            <i class="bi bi-shop me-1.5"></i>मेरी लिस्टिंग्स एवं क्लेम्स
                            <span class="badge bg-white text-primary rounded-pill ms-1.5 px-2"><?php echo count($userListings); ?></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payments-tab" data-bs-toggle="pill" data-bs-target="#tab-payments" type="button" role="tab">
                            <i class="bi bi-receipt me-1.5"></i>भुगतान एवं रसीदें
                            <span class="badge bg-secondary rounded-pill ms-1.5 px-2"><?php echo count($userPayments); ?></span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="dashboardTabContent">
                    
                    <!-- TAB 1: My Listings & Claims -->
                    <div class="tab-pane fade show active" id="tab-listings" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <h5 class="fw-bold font-heading text-dark mb-0 fs-6">आपके खाते से जुड़ी डायरेक्टरी प्रविष्टियां</h5>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-warning text-dark btn-sm rounded-pill fw-bold shadow-xs px-3" data-bs-toggle="modal" data-bs-target="#claimSearchModal">
                                    <i class="bi bi-shield-check me-1"></i>व्यवसाय क्लेम
                                </button>
                                <a href="../add-contact.php" class="btn btn-primary btn-sm rounded-pill fw-bold px-3 shadow-xs">
                                    <i class="bi bi-plus-circle me-1"></i>मुफ़्त लिस्टिंग
                                </a>
                            </div>
                        </div>

                        <?php if (empty($userListings)): ?>
                            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                                <div class="mb-3">
                                    <span class="bg-primary-subtle text-primary rounded-circle p-3 d-inline-flex">
                                        <i class="bi bi-shop-window fs-2"></i>
                                    </span>
                                </div>
                                <h5 class="fw-bold text-dark mb-1 font-heading">कोई लिस्टिंग नहीं जुड़ी है</h5>
                                <p class="text-muted small mx-auto mb-4" style="max-width: 440px;">
                                    आपके खाते से अभी कोई डायरेक्टरी प्रविष्टि नहीं जुड़ी है। आप मुफ़्त में नई लिस्टिंग जोड़ सकते हैं या सारण जिले में अपनी मौजूद दुकान/व्यवसाय क्लेम कर सकते हैं।
                                </p>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-xs" data-bs-toggle="modal" data-bs-target="#claimSearchModal">
                                        <i class="bi bi-shield-check me-1.5"></i>मौजूद व्यवसाय क्लेम करें
                                    </button>
                                    <a href="../add-contact.php" class="btn btn-primary fw-bold rounded-pill px-4 shadow-xs">
                                        <i class="bi bi-plus-circle me-1.5"></i>मुफ़्त नई लिस्टिंग जोड़ें
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-3">
                                <?php foreach ($userListings as $l): 
                                    $cStatus = $l['claim_status'] ?? null;
                                    $publicUrl = !empty($l['slug']) ? rtrim(BASE_URL, '/') . '/listing/' . htmlspecialchars($l['slug']) : '';
                                ?>
                                    <div class="listing-entry-card">
                                        <div class="d-flex flex-column flex-md-row align-items-md-start justify-content-between gap-3">
                                            
                                            <!-- Left Info Block -->
                                            <div class="d-flex align-items-start gap-3 flex-grow-1 min-w-0">
                                                <div class="listing-icon-box">
                                                    <i class="bi bi-building"></i>
                                                </div>

                                                <div class="flex-grow-1 min-w-0">
                                                    <!-- Title & Badges -->
                                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                                        <h5 class="fw-bold text-dark mb-0 font-heading fs-6 text-truncate" style="max-width: 320px;">
                                                            <a href="../<?php echo getListingUrl($l['slug']); ?>" target="_blank" class="text-dark text-decoration-none hover-primary" title="<?php echo htmlspecialchars($l['title']); ?>">
                                                                <?php echo htmlspecialchars($l['title']); ?>
                                                            </a>
                                                        </h5>

                                                        <!-- Status Pill -->
                                                        <?php if ($cStatus === 'PENDING'): ?>
                                                            <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill extra-small"><i class="bi bi-hourglass-split me-1"></i>क्लेम समीक्षा में</span>
                                                        <?php elseif ($cStatus === 'APPROVED'): ?>
                                                            <span class="badge bg-success text-white px-2.5 py-1 rounded-pill extra-small"><i class="bi bi-shield-check me-1"></i>क्लेम स्वीकृत</span>
                                                        <?php elseif ($l['status'] === 'ACTIVE'): ?>
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill extra-small"><i class="bi bi-check-circle me-1"></i>सक्रिय</span>
                                                        <?php elseif ($l['status'] === 'PENDING'): ?>
                                                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2.5 py-1 rounded-pill extra-small"><i class="bi bi-hourglass-split me-1"></i>समीक्षाधीन</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill extra-small">निष्क्रिय</span>
                                                        <?php endif; ?>

                                                        <!-- Plan Type Badge -->
                                                        <?php if (isset($l['plan_type']) && $l['plan_type'] === 'PLATINUM'): ?>
                                                            <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill extra-small shadow-xs">
                                                                <i class="bi bi-crown-fill text-danger me-1"></i>वीआईपी प्लैटिनम
                                                            </span>
                                                        <?php elseif (isset($l['plan_type']) && $l['plan_type'] === 'GOLD'): ?>
                                                            <span class="badge bg-primary text-white fw-bold px-2.5 py-1 rounded-pill extra-small shadow-xs">
                                                                <i class="bi bi-patch-check-fill me-1"></i>गोल्ड बिजनेस
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Metadata Row -->
                                                    <div class="d-flex align-items-center gap-3 flex-wrap extra-small text-muted mb-2">
                                                        <span><i class="bi bi-telephone-fill me-1 text-primary"></i>+91 <?php echo htmlspecialchars($l['mobile']); ?></span>
                                                        <span><i class="bi bi-folder me-1 text-secondary"></i><?php echo htmlspecialchars($l['category_name'] ?? 'सामान्य'); ?></span>
                                                        <?php if (!empty($l['block_name'])): ?>
                                                            <span><i class="bi bi-geo-alt me-1 text-danger"></i><?php echo htmlspecialchars($l['block_name']); ?></span>
                                                        <?php endif; ?>
                                                        <span><i class="bi bi-eye-fill me-1 text-info"></i><?php echo number_format($l['view_count'] ?? 0); ?> दृश्य</span>
                                                    </div>

                                                    <!-- Public URL Strip -->
                                                    <?php if ($publicUrl): ?>
                                                        <div class="public-url-strip">
                                                            <i class="bi bi-link-45deg text-primary fs-6"></i>
                                                            <a href="<?php echo $publicUrl; ?>" target="_blank" class="text-truncate flex-grow-1" title="<?php echo $publicUrl; ?>">
                                                                <?php echo $publicUrl; ?>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0 copy-url-btn" data-url="<?php echo $publicUrl; ?>" title="लिंक कॉपी करें" style="font-size:0.75rem;">
                                                                <i class="bi bi-clipboard me-1"></i>कॉपी
                                                            </button>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Right Action Buttons -->
                                            <div class="d-flex align-items-center gap-2 flex-shrink-0 align-self-start pt-1 listing-mobile-actions">
                                                <a href="../<?php echo getListingUrl($l['slug']); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold" title="सार्वजनिक पेज देखें">
                                                    <i class="bi bi-eye me-1"></i>देखें
                                                </a>
                                                <?php if ($cStatus === 'PENDING'): ?>
                                                    <span class="btn btn-sm btn-light border disabled rounded-pill px-3 extra-small" title="सत्यापन तक संपादन लॉक है">
                                                        <i class="bi bi-lock-fill me-1"></i>लॉक्ड
                                                    </span>
                                                <?php else: ?>
                                                    <a href="../add-contact.php?edit=<?php echo $l['id']; ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">
                                                        <i class="bi bi-pencil me-1"></i>एडिट
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-bold shadow-xs btn-upgrade" data-bs-toggle="modal" data-bs-target="#upgradeModal<?php echo $l['id']; ?>">
                                                        <i class="bi bi-lightning-charge-fill me-1"></i>अपग्रेड
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Upgrade Plan Modal -->
                                    <div class="modal fade" id="upgradeModal<?php echo $l['id']; ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                                <div class="modal-header bg-dark text-white p-4">
                                                    <div>
                                                        <h5 class="modal-title fw-bold font-heading text-white mb-1"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>लिस्टिंग प्लान अपग्रेड करें</h5>
                                                        <p class="text-white-50 extra-small mb-0">सारण निर्देशिका में अपनी रैंकिंग और दृश्यता बढ़ाएं।</p>
                                                    </div>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="bg-light p-3 rounded-3 mb-3 border">
                                                        <strong class="d-block text-dark mb-1"><?php echo htmlspecialchars($l['title']); ?></strong>
                                                        <span class="extra-small text-muted">वर्तमान प्लान: </span>
                                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($l['plan_type'] ?? 'FREE'); ?></span>
                                                    </div>

                                                    <form action="dashboard.php" method="POST" class="rzp-upgrade-form">
                                                        <input type="hidden" name="action" value="upgrade_plan">
                                                        <input type="hidden" name="listing_id" value="<?php echo $l['id']; ?>">

                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">टार्गेट मेंबरशिप प्लान चुनें</label>
                                                            <div class="form-check border p-3 rounded-3 mb-2 bg-white">
                                                                <input class="form-check-input ms-0 me-2" type="radio" name="plan_type" id="planGold<?php echo $l['id']; ?>" value="GOLD" checked>
                                                                <label class="form-check-label w-100" for="planGold<?php echo $l['id']; ?>">
                                                                    <strong class="text-primary">गोल्ड बिजनेस मेंबरशिप</strong> – ₹499.00 / वर्ष
                                                                    <small class="d-block text-muted">शीर्ष श्रेणी रैंकिंग, सत्यापित बैज एवं सीधा व्हाट्सएप इन्क्वायरी बटन</small>
                                                                </label>
                                                            </div>
                                                            <div class="form-check border p-3 rounded-3 mb-2 bg-white">
                                                                <input class="form-check-input ms-0 me-2" type="radio" name="plan_type" id="planPlat<?php echo $l['id']; ?>" value="PLATINUM">
                                                                <label class="form-check-label w-100" for="planPlat<?php echo $l['id']; ?>">
                                                                    <strong class="text-warning-emphasis"><i class="bi bi-crown-fill text-danger me-1"></i>वीआईपी प्लैटिनम मेंबरशिप</strong> – ₹1,499.00 / वर्ष
                                                                    <small class="d-block text-muted">होमपेज विशेष स्थान, वीआईपी क्राउन बैज एवं प्राथमिकता हेल्पलाइन</small>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold py-2.5 text-dark shadow-sm">
                                                            <i class="bi bi-lock-fill me-1"></i> सुरक्षित ऑनलाइन भुगतान के साथ आगे बढ़ें
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

                    <!-- TAB 2: Payments & Tax Receipts -->
                    <div class="tab-pane fade" id="tab-payments" role="tabpanel">
                        
                        <!-- Subscription & Active Membership Overview (AdvocateIndex Pattern) -->
                        <div class="card border-0 shadow-sm rounded-4 p-3.5 p-md-4 bg-white mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2 flex-wrap gap-2">
                                <h5 class="fw-bold font-heading text-dark mb-0 fs-6">
                                    <i class="bi bi-credit-card-2-front-fill text-primary me-2"></i>मेंबरशिप सब्सक्रिप्शन एवं भुगतान विकल्प
                                </h5>
                                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill extra-small">
                                    सक्रिय: <?php echo htmlspecialchars($user['plan_type'] ?? 'FREE'); ?>
                                </span>
                            </div>

                            <div class="p-3.5 rounded-3 border bg-light mb-3">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-7">
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                                <i class="bi bi-crown-fill fs-5 text-warning"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($user['plan_type'] ?? 'FREE'); ?> मेंबर टीयर</h6>
                                                <small class="text-muted">खाता ID: #<?php echo intval($user['id']); ?> • स्थिति: <span class="text-success fw-bold">सक्रिय</span></small>
                                            </div>
                                        </div>
                                        <p class="text-muted extra-small mb-0">
                                            <i class="bi bi-shield-check text-success me-1"></i>
                                            <?php if (($user['plan_type'] ?? 'FREE') === 'FREE'): ?>
                                                गूगल मैप्स लोकेशन लिंक, सोशल मीडिया, वेबसाइट पोर्टफोलियो और सत्यापित बैज अनलॉक करने के लिए गोल्ड या प्लैटिनम में अपग्रेड करें।
                                            <?php else: ?>
                                                आपकी वीआईपी प्रोफ़ाइल शीर्ष डायरेक्टरी रैंकिंग और पूर्ण ट्रस्ट बैज के साथ सक्रिय है।
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="col-md-5 text-md-end text-start">
                                        <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-xs w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#upgradeProfileModal">
                                            <i class="bi bi-lightning-charge-fill text-danger me-1"></i> प्लान अपग्रेड / रिन्यू करें
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Supported Payment Methods -->
                            <div class="p-3 bg-white rounded-3 border d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-lock-fill text-success fs-5"></i>
                                    <div>
                                        <strong class="d-block text-dark extra-small">256-Bit SSL सुरक्षित भुगतान विकल्प</strong>
                                        <span class="text-muted extra-small">UPI (GPay, PhonePe, Paytm), क्रेडिट/डेबिट कार्ड्स एवं नेटबैंकिंग रेजरपे द्वारा</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    <span class="badge bg-light text-dark border extra-small"><i class="bi bi-qr-code me-1 text-primary"></i>UPI QR</span>
                                    <span class="badge bg-light text-dark border extra-small"><i class="bi bi-phone me-1 text-success"></i>GPay/PhonePe</span>
                                    <span class="badge bg-light text-dark border extra-small"><i class="bi bi-credit-card me-1 text-info"></i>Cards</span>
                                    <span class="badge bg-light text-dark border extra-small"><i class="bi bi-bank me-1 text-secondary"></i>Netbanking</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Transactions History Card -->
                        <div class="card border-0 shadow-sm rounded-4 p-3.5 p-md-4 bg-white">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3 flex-wrap gap-2">
                                <div>
                                    <h5 class="fw-bold font-heading text-dark mb-0 fs-6">ऑनलाइन भुगतान एवं टैक्स रसीदें</h5>
                                    <small class="text-muted extra-small">मेंबरशिप अपग्रेड के लिए लेनदेन रसीदें एवं GST इनवॉइस</small>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1.5 rounded-pill extra-small">
                                    <?php echo count($userPayments); ?> लेनदेन
                                </span>
                            </div>

                            <?php if (empty($userPayments)): ?>
                                <div class="text-center py-5 text-muted small">
                                    <i class="bi bi-receipt fs-1 d-block mb-2 opacity-50"></i>
                                    अभी तक कोई भुगतान लेनदेन दर्ज नहीं है।
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table dash-table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>ट्रांजैक्शन ID</th>
                                                <th>लिस्टिंग / उद्देश्य</th>
                                                <th>राशि</th>
                                                <th>स्थिति</th>
                                                <th class="text-end">रसीद / इनवॉइस</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($userPayments as $p): ?>
                                                <tr>
                                                    <td>
                                                        <span class="font-monospace fw-bold text-dark small"><?php echo sanitizeInput($p['transaction_id']); ?></span>
                                                        <small class="d-block text-muted extra-small"><?php echo date('d M Y, h:i A', strtotime($p['created_at'])); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="fw-semibold text-dark small"><?php echo sanitizeInput($p['listing_title'] ?? 'प्रोफ़ाइल अपग्रेड'); ?></span>
                                                        <span class="badge bg-light text-dark border extra-small ms-1"><?php echo sanitizeInput($p['plan_type'] ?? 'PLAN'); ?></span>
                                                    </td>
                                                    <td class="fw-bold text-dark">₹<?php echo number_format($p['amount'], 2); ?></td>
                                                    <td>
                                                        <?php if ($p['payment_status'] === 'SUCCESS'): ?>
                                                            <span class="badge bg-success-subtle text-success rounded-pill px-2.5"><i class="bi bi-check-circle me-1"></i>सफल</span>
                                                        <?php elseif ($p['payment_status'] === 'PENDING'): ?>
                                                            <span class="badge bg-warning-subtle text-dark rounded-pill px-2.5">प्रक्रियाधीन</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5">विफल</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="../generate_receipt.php?id=<?php echo $p['id']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-3 small rounded-pill fw-semibold">
                                                            <i class="bi bi-file-earmark-text me-1"></i>रसीद डाउनलोड
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 1: Business Claim Search Modal -->
<div class="modal fade" id="claimSearchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-4">
                <div>
                    <h5 class="modal-title fw-bold font-heading text-white mb-1"><i class="bi bi-shield-check text-warning me-2"></i>मौजूद व्यवसाय खोजें एवं क्लेम करें</h5>
                    <p class="text-white-50 extra-small mb-0">सारण निर्देशिका में अपनी दुकान या कंपनी खोजें और स्वामित्व क्लेम करें।</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label for="claim_search_input" class="form-label small fw-semibold">व्यवसाय / दुकान / डॉक्टर का नाम दर्ज करें</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="claim_search_input" class="form-control fs-6" placeholder="जैसे: गुप्ता मेडिकल, शर्मा इलेक्ट्रॉनिक्स, छपरा..." autocomplete="off">
                    </div>
                    <small class="text-muted extra-small mt-1 d-block"><i class="bi bi-info-circle me-1"></i>सारण डायरेक्टरी में सर्च करने के लिए टाइप करना शुरू करें।</small>
                </div>

                <div id="claim_search_results" class="mb-3" style="max-height: 280px; overflow-y: auto;">
                    <div class="text-center py-4 text-muted extra-small">
                        व्यवसाय खोजने के लिए ऊपर नाम टाइप करें।
                    </div>
                </div>

                <!-- Claim Form (Hidden until listing is selected) -->
                <div id="claim_form_wrapper" class="bg-light p-3.5 rounded-3 border" style="display: none;">
                    <h6 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-file-earmark-check text-primary me-2"></i>स्वामित्व सत्यापन सबमिट करें</h6>
                    <form action="dashboard.php" method="POST">
                        <input type="hidden" name="action" value="claim_business">
                        <input type="hidden" name="listing_id" id="selected_claim_listing_id" value="">

                        <div class="mb-3 p-2.5 bg-white rounded-3 border">
                            <span class="extra-small text-muted d-block">चयनित व्यवसाय:</span>
                            <strong id="selected_claim_listing_title" class="text-primary font-heading fs-6"></strong>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="claimant_name" class="form-label extra-small fw-semibold">आपका पूरा नाम <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="claimant_name" name="claimant_name" value="<?php echo sanitizeInput($user['full_name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="claimant_mobile" class="form-label extra-small fw-semibold">संपर्क मोबाइल नंबर <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="claimant_mobile" name="claimant_mobile" value="<?php echo sanitizeInput($user['mobile']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="role_title" class="form-label extra-small fw-semibold">व्यवसाय में आपका पद / भूमिका</label>
                                <select class="form-select form-select-sm" id="role_title" name="role_title">
                                    <option value="मालिक / प्रोप्राइटर">मालिक / प्रोप्राइटर</option>
                                    <option value="मैनेजर / संचालक">मैनेजर / संचालक</option>
                                    <option value="अधिकृत प्रतिनिधि">अधिकृत प्रतिनिधि</option>
                                    <option value="कर्मचारी">कर्मचारी</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="verification_proof" class="form-label extra-small fw-semibold">सत्यापन विवरण (विजिटिंग कार्ड / बिल / नोट)</label>
                                <input type="text" class="form-control form-control-sm" id="verification_proof" name="verification_proof" placeholder="जैसे: जीएसटी / दुकान लाइसेंस / विजिटिंग कार्ड विवरण">
                            </div>
                        </div>

                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-warning text-dark btn-sm rounded-pill px-4 fw-bold shadow-xs">
                                <i class="bi bi-send-fill me-1"></i> स्वामित्व दावा सबमिट करें
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Delete Profile Confirmation Modal -->
<div class="modal fade" id="deleteProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="bg-danger text-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0 text-white"><i class="bi bi-exclamation-triangle-fill me-2"></i> खाता हमेशा के लिए हटाएं</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <form action="dashboard.php" method="POST">
                <input type="hidden" name="action" value="delete_account">
                <div class="modal-body p-4 bg-light">
                    <div class="alert alert-danger border-danger-subtle rounded-3 small mb-3">
                        <i class="bi bi-shield-exclamation me-1"></i> <strong>चेतावनी:</strong> यह कार्रवाई स्थायी है और वापस नहीं ली जा सकती।
                    </div>
                    <p class="small text-muted mb-3">क्या आप सुनिश्चित हैं कि आप अपना उपयोगकर्ता खाता हटाना चाहते हैं?</p>
                    <div class="mb-3">
                        <label for="confirm_delete" class="form-label small fw-semibold">पुष्टि करने के लिए बड़े अक्षरों में <strong>DELETE</strong> टाइप करें:</label>
                        <input type="text" class="form-control rounded-3" id="confirm_delete" name="confirm_delete" placeholder="DELETE" required autocomplete="off">
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
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-4">
                <div>
                    <h5 class="modal-title fw-bold font-heading text-white mb-1"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>पेशेवर प्रोफ़ाइल मेंबरशिप अपग्रेड करें</h5>
                    <p class="text-white-50 extra-small mb-0">सारण निर्देशिका खोज में अपनी दृश्यता एवं विश्वसनीयता बढ़ाएं।</p>
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
                                <small class="d-block text-muted">गोल्ड सत्यापित बैज, प्राथमिकता सर्च इंडेक्सिंग एवं सीधा संपर्क एक्सेस</small>
                            </label>
                        </div>
                        <div class="form-check border p-3 rounded-3 mb-2 bg-white">
                            <input class="form-check-input ms-0 me-2" type="radio" name="plan_type" id="profilePlanPlat" value="PLATINUM">
                            <label class="form-check-label w-100" for="profilePlanPlat">
                                <strong class="text-warning-emphasis"><i class="bi bi-crown-fill text-danger me-1"></i>वीआईपी प्लैटिनम पेशेवर</strong> – ₹1,499.00 / वर्ष
                                <small class="d-block text-muted">वीआईपी क्राउन बैज, सारण डायरेक्टरी में शीर्ष रैंकिंग एवं होमपेज विशेष स्थान</small>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold py-2.5 text-dark shadow-sm">
                        <i class="bi bi-lock-fill me-1"></i> सुरक्षित ऑनलाइन भुगतान के साथ आगे बढ़ें
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Copy Public URL to Clipboard
    document.querySelectorAll('.copy-url-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="bi bi-clipboard-check text-success me-1"></i>कॉपी हो गया!';
                this.classList.add('btn-success', 'text-white');
                this.classList.remove('btn-outline-secondary');
                setTimeout(() => {
                    this.innerHTML = originalHtml;
                    this.classList.remove('btn-success', 'text-white');
                    this.classList.add('btn-outline-secondary');
                }, 2000);
            }).catch(() => {
                prompt('इस URL को कॉपी करें:', url);
            });
        });
    });

    // Live AJAX Claim Business Search
    const searchInput = document.getElementById('claim_search_input');
    const resultsBox = document.getElementById('claim_search_results');
    const formWrapper = document.getElementById('claim_form_wrapper');
    const listingIdInput = document.getElementById('selected_claim_listing_id');
    const listingTitleBox = document.getElementById('selected_claim_listing_title');

    let debounceTimer;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length < 2) {
                resultsBox.innerHTML = '<div class="text-center py-4 text-muted extra-small">व्यवसाय खोजने के लिए ऊपर नाम टाइप करें।</div>';
                return;
            }

            resultsBox.innerHTML = '<div class="text-center py-4 text-muted extra-small"><div class="spinner-border spinner-border-sm text-primary me-2"></div>डायरेक्टरी खोजी जा रही है...</div>';

            debounceTimer = setTimeout(function() {
                fetch('../ajax_claim_search.php?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        if (!Array.isArray(data) || data.length === 0) {
                            resultsBox.innerHTML = '<div class="text-center py-4 text-muted extra-small"><i class="bi bi-info-circle me-1"></i>कोई मेल खाता हुआ व्यवसाय नहीं मिला।</div>';
                            return;
                        }

                        let html = '<div class="list-group list-group-flush border rounded-3">';
                        data.forEach(item => {
                            html += `<button type="button" class="list-group-item list-group-item-action p-3 d-flex align-items-center justify-content-between text-start select-claim-btn" data-id="${item.id}" data-title="${item.title}">
                                        <div>
                                            <strong class="d-block text-dark small font-heading">${item.title}</strong>
                                            <span class="text-muted extra-small"><i class="bi bi-geo-alt me-1"></i>${item.block_name || 'सारण'} • फोन: +91 ${item.mobile || 'N/A'}</span>
                                        </div>
                                        <span class="btn btn-xs btn-outline-warning text-dark fw-bold rounded-pill px-3 py-1 extra-small">इसे चुनें</span>
                                    </button>`;
                        });
                        html += '</div>';
                        resultsBox.innerHTML = html;

                        document.querySelectorAll('.select-claim-btn').forEach(btn => {
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
                        resultsBox.innerHTML = '<div class="text-center py-4 text-danger extra-small">खोजते समय त्रुटि हुई।</div>';
                    });
            }, 300);
        });
    }

    // Razorpay Online Payment Checkout Handler
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
                        "description": "मेंबरशिप अपग्रेड - " + selectedPlan + " प्लान (1 वर्ष)",
                        "image": "../assets/img/logo.png",
                        "order_id": data.order_id,
                        "prefill": {
                            "name": data.user.name,
                            "contact": data.user.mobile,
                            "email": data.user.email
                        },
                        "theme": {
                            "color": "#1e40af"
                        },
                        "handler": function (response) {
                            btn.disabled = true;
                            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>भुगतान सत्यापित किया जा रहा है...';

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
                                    alert('भुगतान सफल रहा! आपका ' + selectedPlan + ' प्लान 1 वर्ष के लिए सक्रिय कर दिया गया है।');
                                    window.location.reload();
                                } else {
                                    alert('भुगतान सत्यापन सूचना: ' + vData.message);
                                    window.location.reload();
                                }
                            })
                            .catch(() => {
                                window.location.reload();
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
                alert('भुगतान गेटवे से कनेक्ट करते समय त्रुटि हुई।');
            });
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

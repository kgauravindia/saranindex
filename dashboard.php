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
        $msg = "Profile information updated successfully!";
        $msg_type = 'success';
        $user = getLoggedInUser(); // Refresh user data
    } else {
        $msg = $_SESSION['profile_update_error'] ?? "Failed to update profile details.";
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
            $msg = "An error occurred while deleting your profile. Please try again.";
            $msg_type = 'danger';
        }
    } else {
        $msg = "Please type 'DELETE' in capital letters to confirm profile deletion.";
        $msg_type = 'danger';
    }
}

// Handle Plan Upgrade & Online Payment POST
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
                    $msg = "Membership plan updated to FREE successfully!";
                    $msg_type = 'success';
                } else {
                    $amount = ($newPlan === 'GOLD') ? 499.00 : 1499.00;
                    $payment = createOnlinePayment($user['id'], $listingId, $newPlan, $amount, 'RAZORPAY_UPI');
                    if ($payment) {
                        $msg = "Payment request initiated for ₹" . number_format($amount, 0) . " (Txn: " . $payment['transaction_id'] . "). Plan will activate upon payment confirmation.";
                        $msg_type = 'info';
                    } else {
                        $msg = "Payment processing failed. Please try again.";
                        $msg_type = 'danger';
                    }
                }
            } catch (PDOException $e) {
                $msg = "Failed to process plan upgrade: " . $e->getMessage();
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
            $msg = "Business claim submitted successfully! Our team will verify and activate your ownership access.";
            $msg_type = 'success';
        } else {
            $msg = "Failed to submit business claim. Please try again or contact support.";
            $msg_type = 'danger';
        }
    } else {
        $msg = "Please select a valid business listing and fill in your name and contact mobile number.";
        $msg_type = 'warning';
    }
}

$userListings = getUserListings($user['id']);
$userPayments = getUserPayments($user['id']);
$blocks = getBlocks();
$all_categories = getCategoriesList();

$active_listings_count = 0;
$pending_claims_count = 0;
foreach ($userListings as $ul) {
    if (($ul['status'] ?? '') === 'ACTIVE') $active_listings_count++;
    if (($ul['claim_status'] ?? '') === 'PENDING') $pending_claims_count++;
}

$page_title = "My Account Dashboard – Saran Index";
$meta_description = "User account dashboard on Saran Index. Manage your listings, profile, online payments, and business directory submissions.";

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
}
.dash-avatar-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid rgba(255, 255, 255, 0.9);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}
.dash-avatar-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
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
    padding: 20px;
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
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}

/* Custom Tabs Bar */
.dash-nav-pills .nav-link {
    color: var(--dash-text-muted);
    font-weight: 600;
    font-size: 0.9rem;
    padding: 10px 18px;
    border-radius: 12px;
    transition: all 0.2s ease;
    border: 1px solid transparent;
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
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
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
    padding: 6px 16px;
    font-size: 0.825rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
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
}
.dash-table td {
    padding: 14px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
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
                        <?php if (!empty($user['profile_image']) && file_exists(__DIR__ . '/' . $user['profile_image'])): ?>
                            <img src="<?php echo sanitizeInput($user['profile_image']); ?>" alt="<?php echo sanitizeInput($user['full_name']); ?>" class="dash-avatar-img">
                        <?php else: ?>
                            <div class="dash-avatar-placeholder">
                                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        <span class="dash-status-dot" title="Account Active"></span>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h3 class="fw-bold text-white mb-0 font-heading">
                                <?php echo htmlspecialchars($user['full_name']); ?>
                            </h3>
                            <?php if (!empty($user['username_handle'])): 
                                $cleanHandle = ltrim($user['username_handle'], '@');
                            ?>
                                <a href="@<?php echo sanitizeInput($cleanHandle); ?>" target="_blank" class="badge bg-warning text-dark text-decoration-none fw-bold px-2.5 py-1 rounded-pill small">
                                    <i class="bi bi-at"></i><?php echo sanitizeInput($cleanHandle); ?>
                                </a>
                            <?php endif; ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small">
                                <i class="bi bi-patch-check-fill me-1"></i>Verified Member
                            </span>
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
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-warning text-dark btn-pill-action shadow-sm" data-bs-toggle="modal" data-bs-target="#claimSearchModal">
                        <i class="bi bi-shield-check"></i> Claim Business
                    </button>
                    <a href="add-contact.php" class="btn btn-primary btn-pill-action shadow-sm">
                        <i class="bi bi-plus-circle"></i> Add Listing
                    </a>
                    <a href="edit-profile.php" class="btn btn-outline-light btn-pill-action">
                        <i class="bi bi-pencil-square"></i> Edit Profile
                    </a>
                    <a href="logout.php" class="btn btn-outline-light btn-pill-action opacity-75" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
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

        <!-- Stat Metrics Grid Strip -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="stat-icon-pill bg-primary-subtle text-primary">
                        <i class="bi bi-shop"></i>
                    </div>
                    <div>
                        <div class="h3 fw-bold text-dark mb-0 font-heading"><?php echo count($userListings); ?></div>
                        <div class="text-muted extra-small fw-semibold">Total Listings</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="stat-icon-pill bg-success-subtle text-success">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div>
                        <div class="h3 fw-bold text-dark mb-0 font-heading"><?php echo $active_listings_count; ?></div>
                        <div class="text-muted extra-small fw-semibold">Active & Live</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="stat-icon-pill bg-warning-subtle text-warning-emphasis">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="h3 fw-bold text-dark mb-0 font-heading"><?php echo $pending_claims_count; ?></div>
                        <div class="text-muted extra-small fw-semibold">Claims in Review</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="stat-icon-pill bg-info-subtle text-info">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <div class="h3 fw-bold text-dark mb-0 font-heading"><?php echo count($userPayments); ?></div>
                        <div class="text-muted extra-small fw-semibold">Transactions</div>
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
                            <i class="bi bi-person-badge text-primary me-2"></i>Profile Details
                        </h6>
                        <a href="edit-profile.php" class="small text-primary text-decoration-none fw-semibold">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                    </div>

                    <div class="small">
                        <div class="d-flex align-items-center justify-content-between mb-2.5 py-1 border-bottom border-light">
                            <span class="text-muted"><i class="bi bi-person me-2 text-primary"></i>Name</span>
                            <span class="fw-bold text-dark"><?php echo htmlspecialchars($user['full_name']); ?></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2.5 py-1 border-bottom border-light">
                            <span class="text-muted"><i class="bi bi-telephone me-2 text-primary"></i>Mobile</span>
                            <span class="fw-semibold text-dark">+91 <?php echo htmlspecialchars($user['mobile']); ?></span>
                        </div>
                        <?php if (!empty($user['whatsapp'])): ?>
                            <div class="d-flex align-items-center justify-content-between mb-2.5 py-1 border-bottom border-light">
                                <span class="text-muted"><i class="bi bi-whatsapp me-2 text-success"></i>WhatsApp</span>
                                <span class="fw-medium text-dark">+91 <?php echo htmlspecialchars($user['whatsapp']); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($user['email'])): ?>
                            <div class="d-flex align-items-center justify-content-between mb-2.5 py-1 border-bottom border-light">
                                <span class="text-muted"><i class="bi bi-envelope me-2 text-primary"></i>Email</span>
                                <span class="fw-medium text-dark text-truncate" style="max-width: 170px;" title="<?php echo htmlspecialchars($user['email']); ?>"><?php echo htmlspecialchars($user['email']); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($user['designation'])): ?>
                            <div class="d-flex align-items-center justify-content-between mb-2.5 py-1 border-bottom border-light">
                                <span class="text-muted"><i class="bi bi-briefcase me-2 text-primary"></i>Designation</span>
                                <span class="fw-medium text-dark"><?php echo htmlspecialchars($user['designation']); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($user['business_name'])): ?>
                            <div class="d-flex align-items-center justify-content-between mb-2 py-1">
                                <span class="text-muted"><i class="bi bi-building me-2 text-primary"></i>Firm/Shop</span>
                                <span class="fw-semibold text-dark text-truncate" style="max-width: 160px;"><?php echo htmlspecialchars($user['business_name']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2 mt-3 pt-2">
                        <a href="edit-profile.php" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold py-2">
                            <i class="bi bi-pencil-square me-1.5"></i>Update Profile & Bio
                        </a>
                        <button type="button" class="btn btn-warning text-dark btn-sm rounded-pill fw-bold py-2 shadow-xs" data-bs-toggle="modal" data-bs-target="#upgradeProfileModal">
                            <i class="bi bi-lightning-charge-fill text-danger me-1"></i>Upgrade Profile Plan
                        </button>
                    </div>
                </div>

                <!-- Account Security & Settings Box -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h6 class="fw-bold text-dark mb-3 font-heading border-bottom pb-2">
                        <i class="bi bi-shield-lock text-primary me-2"></i>Security & Actions
                    </h6>

                    <div class="d-grid gap-2">
                        <a href="change-password.php" class="btn btn-light btn-sm text-start rounded-3 fw-semibold p-2.5 border">
                            <i class="bi bi-key text-primary me-2 fs-6"></i>Change Password
                        </a>
                        <button type="button" class="btn btn-light btn-sm text-start rounded-3 fw-semibold p-2.5 border text-danger" data-bs-toggle="modal" data-bs-target="#deleteProfileModal">
                            <i class="bi bi-trash text-danger me-2 fs-6"></i>Delete Account
                        </button>
                    </div>
                </div>

                <!-- Helpdesk & Support Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-headset fs-4 text-warning"></i>
                        <h6 class="fw-bold mb-0 text-white">Online Helpdesk</h6>
                    </div>
                    <p class="small text-white-50 mb-3">Need assistance with listing claims, tax receipts, or membership upgrades?</p>
                    <div class="d-flex gap-2">
                        <a href="https://wa.me/919934220000" target="_blank" class="btn btn-success btn-sm rounded-pill flex-fill fw-bold py-2 shadow-xs">
                            <i class="bi bi-whatsapp me-1"></i>WhatsApp
                        </a>
                        <a href="contact.php" class="btn btn-outline-light btn-sm rounded-pill flex-fill fw-semibold py-2">
                            Contact Us
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
                            <i class="bi bi-shop me-1.5"></i>My Listings & Claims
                            <span class="badge bg-white text-primary rounded-pill ms-1.5 px-2"><?php echo count($userListings); ?></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payments-tab" data-bs-toggle="pill" data-bs-target="#tab-payments" type="button" role="tab">
                            <i class="bi bi-receipt me-1.5"></i>Payments & Receipts
                            <span class="badge bg-secondary rounded-pill ms-1.5 px-2"><?php echo count($userPayments); ?></span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="dashboardTabContent">
                    
                    <!-- TAB 1: My Listings & Claims -->
                    <div class="tab-pane fade show active" id="tab-listings" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <h5 class="fw-bold font-heading text-dark mb-0">Directory Entries Linked to Your Account</h5>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-warning text-dark btn-sm rounded-pill fw-bold shadow-xs px-3" data-bs-toggle="modal" data-bs-target="#claimSearchModal">
                                    <i class="bi bi-shield-check me-1"></i>Claim Business
                                </button>
                                <a href="add-contact.php" class="btn btn-primary btn-sm rounded-pill fw-bold px-3 shadow-xs">
                                    <i class="bi bi-plus-circle me-1"></i>Add Listing Free
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
                                <h5 class="fw-bold text-dark mb-1 font-heading">No Listings Linked Yet</h5>
                                <p class="text-muted small mx-auto mb-4" style="max-width: 440px;">
                                    No business directory listings are associated with your account yet. You can submit a new listing for free or claim an existing verified entry in Saran District.
                                </p>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-xs" data-bs-toggle="modal" data-bs-target="#claimSearchModal">
                                        <i class="bi bi-shield-check me-1.5"></i>Claim Existing Business
                                    </button>
                                    <a href="add-contact.php" class="btn btn-primary fw-bold rounded-pill px-4 shadow-xs">
                                        <i class="bi bi-plus-circle me-1.5"></i>Submit New Listing Free
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
                                                            <a href="<?php echo getListingUrl($l['slug']); ?>" target="_blank" class="text-dark text-decoration-none hover-primary" title="<?php echo htmlspecialchars($l['title']); ?>">
                                                                <?php echo htmlspecialchars($l['title']); ?>
                                                            </a>
                                                        </h5>

                                                        <!-- Status Pill -->
                                                        <?php if ($cStatus === 'PENDING'): ?>
                                                            <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill extra-small"><i class="bi bi-hourglass-split me-1"></i>Claim Under Review</span>
                                                        <?php elseif ($cStatus === 'APPROVED'): ?>
                                                            <span class="badge bg-success text-white px-2.5 py-1 rounded-pill extra-small"><i class="bi bi-shield-check me-1"></i>Claim Approved</span>
                                                        <?php elseif ($l['status'] === 'ACTIVE'): ?>
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill extra-small"><i class="bi bi-check-circle me-1"></i>Active</span>
                                                        <?php elseif ($l['status'] === 'PENDING'): ?>
                                                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2.5 py-1 rounded-pill extra-small"><i class="bi bi-hourglass-split me-1"></i>Under Review</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill extra-small">Inactive</span>
                                                        <?php endif; ?>

                                                        <!-- Plan Type Badge -->
                                                        <?php if (isset($l['plan_type']) && $l['plan_type'] === 'PLATINUM'): ?>
                                                            <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill extra-small shadow-xs">
                                                                <i class="bi bi-crown-fill text-danger me-1"></i>VIP Platinum
                                                            </span>
                                                        <?php elseif (isset($l['plan_type']) && $l['plan_type'] === 'GOLD'): ?>
                                                            <span class="badge bg-primary text-white fw-bold px-2.5 py-1 rounded-pill extra-small shadow-xs">
                                                                <i class="bi bi-patch-check-fill me-1"></i>Gold Business
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Metadata Row -->
                                                    <div class="d-flex align-items-center gap-3 flex-wrap extra-small text-muted mb-2">
                                                        <span><i class="bi bi-telephone-fill me-1 text-primary"></i>+91 <?php echo htmlspecialchars($l['mobile']); ?></span>
                                                        <span><i class="bi bi-folder me-1 text-secondary"></i><?php echo htmlspecialchars($l['category_name'] ?? 'General'); ?></span>
                                                        <?php if (!empty($l['block_name'])): ?>
                                                            <span><i class="bi bi-geo-alt me-1 text-danger"></i><?php echo htmlspecialchars($l['block_name']); ?></span>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Public URL Strip -->
                                                    <?php if ($publicUrl): ?>
                                                        <div class="public-url-strip">
                                                            <i class="bi bi-link-45deg text-primary fs-6"></i>
                                                            <a href="<?php echo $publicUrl; ?>" target="_blank" class="text-truncate flex-grow-1" title="<?php echo $publicUrl; ?>">
                                                                <?php echo $publicUrl; ?>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0 copy-url-btn" data-url="<?php echo $publicUrl; ?>" title="Copy Public URL" style="font-size:0.75rem;">
                                                                <i class="bi bi-clipboard me-1"></i>Copy
                                                            </button>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Right Action Buttons -->
                                            <div class="d-flex align-items-center gap-2 flex-shrink-0 align-self-start pt-1">
                                                <a href="<?php echo getListingUrl($l['slug']); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold" title="View Public Page">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </a>
                                                <?php if ($cStatus === 'PENDING'): ?>
                                                    <span class="btn btn-sm btn-light border disabled rounded-pill px-3 extra-small" title="Editing locked until claim approval">
                                                        <i class="bi bi-lock-fill me-1"></i>Locked
                                                    </span>
                                                <?php else: ?>
                                                    <a href="edit-listing.php?id=<?php echo $l['id']; ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">
                                                        <i class="bi bi-pencil me-1"></i>Edit
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-bold shadow-xs" data-bs-toggle="modal" data-bs-target="#upgradeModal<?php echo $l['id']; ?>">
                                                        <i class="bi bi-lightning-charge-fill me-1"></i>Upgrade
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
                                                        <h5 class="modal-title fw-bold font-heading text-white mb-1"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Upgrade Listing Plan</h5>
                                                        <p class="text-white-50 extra-small mb-0">Boost your directory ranking and reach thousands of customers in Saran.</p>
                                                    </div>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="bg-light p-3 rounded-3 mb-3 border">
                                                        <strong class="d-block text-dark mb-1"><?php echo htmlspecialchars($l['title']); ?></strong>
                                                        <span class="extra-small text-muted">Current Plan: </span>
                                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($l['plan_type'] ?? 'FREE'); ?></span>
                                                    </div>

                                                    <form action="dashboard.php" method="POST" class="rzp-upgrade-form">
                                                        <input type="hidden" name="action" value="upgrade_plan">
                                                        <input type="hidden" name="listing_id" value="<?php echo $l['id']; ?>">

                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold">Select Target Membership Plan</label>
                                                            <div class="form-check border p-3 rounded-3 mb-2 bg-white">
                                                                <input class="form-check-input ms-0 me-2" type="radio" name="plan_type" id="planGold<?php echo $l['id']; ?>" value="GOLD" checked>
                                                                <label class="form-check-label w-100" for="planGold<?php echo $l['id']; ?>">
                                                                    <strong class="text-primary">Gold Business Membership</strong> – ₹499.00 / year
                                                                    <small class="d-block text-muted">Top category ranking, verified badge & direct customer enquiry access</small>
                                                                </label>
                                                            </div>
                                                            <div class="form-check border p-3 rounded-3 mb-2 bg-white">
                                                                <input class="form-check-input ms-0 me-2" type="radio" name="plan_type" id="planPlat<?php echo $l['id']; ?>" value="PLATINUM">
                                                                <label class="form-check-label w-100" for="planPlat<?php echo $l['id']; ?>">
                                                                    <strong class="text-warning-emphasis"><i class="bi bi-crown-fill text-danger me-1"></i>VIP Platinum Membership</strong> – ₹1,499.00 / year
                                                                    <small class="d-block text-muted">Homepage featured banner placement, VIP crown badge & priority support</small>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold py-2.5 text-dark shadow-sm">
                                                            <i class="bi bi-lock-fill me-1"></i> Proceed to Secure Payment
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
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                <div>
                                    <h5 class="fw-bold font-heading text-dark mb-0">Online Payments & Invoices</h5>
                                    <small class="text-muted">Transaction receipts and GST tax invoices for membership upgrades</small>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1.5 rounded-pill">
                                    <?php echo count($userPayments); ?> Transaction<?php echo count($userPayments) === 1 ? '' : 's'; ?>
                                </span>
                            </div>

                            <?php if (empty($userPayments)): ?>
                                <div class="text-center py-5 text-muted small">
                                    <i class="bi bi-receipt fs-1 d-block mb-2 opacity-50"></i>
                                    No payment transactions recorded yet.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table dash-table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Listing / Purpose</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th class="text-end">Invoice / Receipt</th>
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
                                                        <span class="fw-semibold text-dark small"><?php echo sanitizeInput($p['listing_title'] ?? 'Listing Upgrade'); ?></span>
                                                        <span class="badge bg-light text-dark border extra-small ms-1"><?php echo sanitizeInput($p['plan_type'] ?? 'PLAN'); ?></span>
                                                    </td>
                                                    <td class="fw-bold text-dark">₹<?php echo number_format($p['amount'], 2); ?></td>
                                                    <td>
                                                        <?php if ($p['payment_status'] === 'SUCCESS'): ?>
                                                            <span class="badge bg-success-subtle text-success rounded-pill px-2.5"><i class="bi bi-check-circle me-1"></i>SUCCESS</span>
                                                        <?php elseif ($p['payment_status'] === 'PENDING'): ?>
                                                            <span class="badge bg-warning-subtle text-dark rounded-pill px-2.5">PENDING</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5">FAILED</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="generate_receipt.php?id=<?php echo $p['id']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-3 small rounded-pill fw-semibold">
                                                            <i class="bi bi-file-earmark-text me-1"></i>Receipt
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

<!-- Modal 1: Claim Existing Business Modal -->
<div class="modal fade" id="claimSearchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-4">
                <div>
                    <h5 class="modal-title fw-bold font-heading text-white mb-1"><i class="bi bi-shield-check text-warning me-2"></i>Claim Ownership of Existing Business</h5>
                    <p class="text-white-50 extra-small mb-0">Search directory listings in Saran District to request owner management access.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label for="claim_search_input" class="form-label small fw-semibold">Search Business Name, Mobile, or Category</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="claim_search_input" class="form-control fs-6" placeholder="Type business title, shop name, or phone number in Saran..." autocomplete="off">
                    </div>
                    <small class="text-muted extra-small mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Start typing to search across verified Saran directory entries.</small>
                </div>

                <div id="claim_search_results" class="mb-3" style="max-height: 280px; overflow-y: auto;">
                    <div class="text-center py-4 text-muted extra-small">
                        Type business name above to search listings.
                    </div>
                </div>

                <!-- Claim Request Form (Hidden until listing selected) -->
                <div id="claim_form_wrapper" class="bg-light p-3.5 rounded-3 border" style="display: none;">
                    <h6 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-file-earmark-check text-primary me-2"></i>Submit Business Claim Verification</h6>
                    <form action="dashboard.php" method="POST">
                        <input type="hidden" name="action" value="claim_business">
                        <input type="hidden" name="listing_id" id="selected_claim_listing_id" value="">

                        <div class="mb-3 p-2.5 bg-white rounded-3 border">
                            <span class="extra-small text-muted d-block">Selected Business:</span>
                            <strong id="selected_claim_listing_title" class="text-primary font-heading fs-6"></strong>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="claimant_name" class="form-label extra-small fw-semibold">Your Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="claimant_name" name="claimant_name" value="<?php echo sanitizeInput($user['full_name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="claimant_mobile" class="form-label extra-small fw-semibold">Contact Mobile <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="claimant_mobile" name="claimant_mobile" value="<?php echo sanitizeInput($user['mobile']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="role_title" class="form-label extra-small fw-semibold">Role / Designation</label>
                                <select class="form-select form-select-sm" id="role_title" name="role_title">
                                    <option value="Owner / Proprietor">Owner / Proprietor</option>
                                    <option value="General Manager">General Manager</option>
                                    <option value="Authorized Representative">Authorized Representative</option>
                                    <option value="Employee">Employee</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="verification_proof" class="form-label extra-small fw-semibold">Verification Proof (GST / Visiting Card / Note)</label>
                                <input type="text" class="form-control form-control-sm" id="verification_proof" name="verification_proof" placeholder="e.g. GSTIN, Shop License, or Visiting Card details">
                            </div>
                        </div>

                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-warning text-dark btn-sm rounded-pill px-4 fw-bold shadow-xs">
                                <i class="bi bi-send-fill me-1"></i> Submit Claim Request
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
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold font-heading text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Delete User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="small text-muted mb-3">Are you sure you want to delete your account? This action is permanent and cannot be undone.</p>
                <form action="dashboard.php" method="POST">
                    <input type="hidden" name="action" value="delete_account">
                    <div class="mb-3">
                        <label for="confirm_delete" class="form-label small fw-semibold">Type <strong>DELETE</strong> in capital letters to confirm</label>
                        <input type="text" class="form-control" id="confirm_delete" name="confirm_delete" placeholder="DELETE" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 rounded-pill fw-bold py-2 shadow-xs">
                        Permanently Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3: Upgrade Profile Membership Modal -->
<div class="modal fade" id="upgradeProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-4">
                <div>
                    <h5 class="modal-title fw-bold font-heading text-white mb-1"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Upgrade Profile Membership</h5>
                    <p class="text-white-50 extra-small mb-0">Enhance your professional profile visibility across Saran District directory search.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="bg-light p-3 rounded-3 mb-3 border">
                    <strong class="d-block text-dark mb-1"><?php echo htmlspecialchars($user['full_name']); ?></strong>
                    <span class="extra-small text-muted">Current Profile Plan: </span>
                    <span class="badge bg-secondary"><?php echo htmlspecialchars($user['plan_type'] ?? 'FREE'); ?></span>
                </div>

                <form action="dashboard.php" method="POST" class="rzp-upgrade-form">
                    <input type="hidden" name="action" value="upgrade_plan">
                    <input type="hidden" name="listing_id" value="0">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Select Target Membership Plan</label>
                        <div class="form-check border p-3 rounded-3 mb-2 bg-white">
                            <input class="form-check-input ms-0 me-2" type="radio" name="plan_type" id="profilePlanGold" value="GOLD" checked>
                            <label class="form-check-label w-100" for="profilePlanGold">
                                <strong class="text-primary">Gold Verified Professional</strong> – ₹499.00 / year
                                <small class="d-block text-muted">Gold Verified Professional Badge on profile, priority search indexing & direct contact access</small>
                            </label>
                        </div>
                        <div class="form-check border p-3 rounded-3 mb-2 bg-white">
                            <input class="form-check-input ms-0 me-2" type="radio" name="plan_type" id="profilePlanPlat" value="PLATINUM">
                            <label class="form-check-label w-100" for="profilePlanPlat">
                                <strong class="text-warning-emphasis"><i class="bi bi-crown-fill text-danger me-1"></i>VIP Platinum Professional</strong> – ₹1,499.00 / year
                                <small class="d-block text-muted">VIP Platinum Crown Badge on public profile, top ranking across Saran directory & featured placement</small>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold py-2.5 text-dark shadow-sm">
                        <i class="bi bi-lock-fill me-1"></i> Proceed with Online Payment
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
                this.innerHTML = '<i class="bi bi-clipboard-check text-success me-1"></i>Copied!';
                this.classList.add('btn-success', 'text-white');
                this.classList.remove('btn-outline-secondary');
                setTimeout(() => {
                    this.innerHTML = originalHtml;
                    this.classList.remove('btn-success', 'text-white');
                    this.classList.add('btn-outline-secondary');
                }, 2000);
            }).catch(() => {
                prompt('Copy this URL:', url);
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
                resultsBox.innerHTML = '<div class="text-center py-4 text-muted extra-small">Type business name above to search listings.</div>';
                return;
            }

            resultsBox.innerHTML = '<div class="text-center py-4 text-muted extra-small"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Searching directory...</div>';

            debounceTimer = setTimeout(function() {
                fetch('ajax_claim_search.php?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        if (!Array.isArray(data) || data.length === 0) {
                            resultsBox.innerHTML = '<div class="text-center py-4 text-muted extra-small"><i class="bi bi-info-circle me-1"></i>No matching business listings found in Saran Index.</div>';
                            return;
                        }

                        let html = '<div class="list-group list-group-flush border rounded-3">';
                        data.forEach(item => {
                            html += `<button type="button" class="list-group-item list-group-item-action p-3 d-flex align-items-center justify-content-between text-start select-claim-btn" data-id="${item.id}" data-title="${item.title}">
                                        <div>
                                            <strong class="d-block text-dark small font-heading">${item.title}</strong>
                                            <span class="text-muted extra-small"><i class="bi bi-geo-alt me-1"></i>${item.block_name || 'Saran'} • Phone: +91 ${item.mobile || 'N/A'}</span>
                                        </div>
                                        <span class="btn btn-xs btn-outline-warning text-dark fw-bold rounded-pill px-3 py-1 extra-small">Select</span>
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
                        resultsBox.innerHTML = '<div class="text-center py-4 text-danger extra-small">An error occurred while searching listings.</div>';
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
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Initializing Secure Payment...';

            const formData = new FormData();
            formData.append('action', 'create_order');
            formData.append('listing_id', listingId);
            formData.append('plan_type', selectedPlan);

            fetch('api/process_payment_api.php', {
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
                        "description": "Membership Upgrade - " + selectedPlan + " Plan",
                        "image": "assets/img/logo.png",
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
                            const verifyData = new FormData();
                            verifyData.append('action', 'verify_payment');
                            verifyData.append('transaction_id', data.transaction_id);
                            verifyData.append('razorpay_payment_id', response.razorpay_payment_id || '');
                            verifyData.append('razorpay_order_id', response.razorpay_order_id || data.order_id);
                            verifyData.append('razorpay_signature', response.razorpay_signature || '');

                            fetch('api/process_payment_api.php', {
                                method: 'POST',
                                body: verifyData
                            })
                            .then(res => res.json())
                            .then(vData => {
                                if (vData.status === 'success') {
                                    alert('Payment Verified! Your ' + selectedPlan + ' membership has been activated.');
                                    window.location.reload();
                                } else {
                                    alert('Payment verification failed: ' + vData.message);
                                }
                            });
                        }
                    };
                    const rzp = new Razorpay(options);
                    rzp.open();
                } else {
                    alert('Order creation failed: ' + (data.message || 'Please try again.'));
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
                console.error('Razorpay Error:', err);
                alert('Error initializing payment. Please try again.');
            });
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

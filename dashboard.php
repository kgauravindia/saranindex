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
        'full_name' => $_POST['full_name'] ?? '',
        'username_handle' => $_POST['username_handle'] ?? '',
        'email' => $_POST['email'] ?? '',
        'whatsapp' => $_POST['whatsapp'] ?? '',
        'business_name' => $_POST['business_name'] ?? '',
        'designation' => $_POST['designation'] ?? '',
        'profession_category' => $_POST['profession_category'] ?? '',
        'category_id' => $_POST['category_id'] ?? null,
        'subcategory_id' => $_POST['subcategory_id'] ?? null,
        'specialization' => $_POST['specialization'] ?? '',
        'education' => $_POST['education'] ?? '',
        'experience_years' => $_POST['experience_years'] ?? '',
        'office_hours' => $_POST['office_hours'] ?? '',
        'block_id' => $_POST['block_id'] ?? null,
        'address' => $_POST['address'] ?? '',
        'pincode' => $_POST['pincode'] ?? '',
        'bio' => $_POST['bio'] ?? '',
        'about' => $_POST['about'] ?? '',
        'profile_visibility' => $_POST['profile_visibility'] ?? 'PUBLIC',
        'mobile_visibility' => $_POST['mobile_visibility'] ?? 'PUBLIC',
        'email_visibility' => $_POST['email_visibility'] ?? 'PUBLIC',
        'address_visibility' => $_POST['address_visibility'] ?? 'PUBLIC'
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
        $msg = "Professional profile updated successfully!";
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
            $msg = "An error occurred while deleting your user profile. Please try again.";
            $msg_type = 'danger';
        }
    } else {
        $msg = "Please type 'DELETE' in capital letters to confirm user profile deletion.";
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
                    $msg = "Membership plan set to FREE successfully!";
                    $msg_type = 'success';
                } else {
                    $amount = ($newPlan === 'GOLD') ? 499.00 : 1499.00;
                    $payment = createOnlinePayment($user['id'], $listingId, $newPlan, $amount, 'RAZORPAY_UPI');
                    if ($payment) {
                        $payId = 'PAY_ONLINE_' . time() . '_' . rand(100, 999);
                        completeOnlinePayment($payment['transaction_id'], $payId, 'SUCCESS', 'Online Payment Verification Successful');
                        $msg = "Online Payment of ₹" . number_format($amount, 0) . " recorded (Txn: " . $payment['transaction_id'] . "). " . $newPlan . " membership activated!";
                        $msg_type = 'success';
                    } else {
                        $msg = "Online payment processing failed. Please try again.";
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
            $msg = "Business claim submitted successfully! Our admin team will verify and activate your listing access.";
            $msg_type = 'success';
        } else {
            $msg = "Failed to submit business claim. Please try again or contact support.";
            $msg_type = 'danger';
        }
    } else {
        $msg = "Please select a valid business listing and fill in your name & contact mobile number.";
        $msg_type = 'warning';
    }
}

$userListings = getUserListings($user['id']);
$userPayments = getUserPayments($user['id']);
$blocks = getBlocks();
$all_categories = getCategoriesList();

$page_title = "My Account Dashboard – Saran Index";
$meta_description = "User account dashboard on Saran Index. Manage your listings, profile, online payments, and business directory submissions.";

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
                <h2 class="fw-bold font-heading text-dark mb-0">Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h2>
                <small class="text-muted"><i class="bi bi-phone me-1"></i>+91 <?php echo htmlspecialchars($user['mobile']); ?> | User ID: #<?php echo intval($user['id']); ?></small>
            </div>
            <div class="d-flex flex-wrap gap-2 w-100 w-lg-auto mt-2 mt-lg-0">
                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold flex-fill flex-lg-grow-0" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profile
                </button>
                <a href="change-password.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold flex-fill flex-lg-grow-0 text-center">
                    <i class="bi bi-key me-1"></i> Change Password
                </a>
                <a href="add-contact.php" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark shadow-xs flex-fill flex-lg-grow-0 text-center">
                    <i class="bi bi-plus-circle me-1"></i> Add New Listing
                </a>
                <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold flex-fill flex-lg-grow-0 text-center">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <?php if ($msg): ?>
        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show rounded-3 p-3 shadow-sm mb-4 small" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-6"></i><?php echo htmlspecialchars($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Sidebar: User Profile Card & Quick Stats -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="text-center mb-3">
                    <?php if (!empty($user['profile_image']) && file_exists(__DIR__ . '/' . $user['profile_image'])): ?>
                        <img src="<?php echo sanitizeInput($user['profile_image']); ?>" alt="<?php echo sanitizeInput($user['full_name']); ?>" class="rounded-circle img-thumbnail shadow-sm mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold fs-3 mb-2 shadow-sm" style="width: 76px; height: 76px; background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                            <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                    <h5 class="fw-bold text-dark mb-1 font-heading"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                    <?php if (!empty($user['username_handle'])): 
                        $handleClean = ltrim($user['username_handle'], '@');
                    ?>
                        <a href="@<?php echo sanitizeInput($handleClean); ?>" target="_blank" class="badge bg-primary-subtle text-primary text-decoration-none fw-bold px-2.5 py-1 mb-2 rounded-pill small">
                            <i class="bi bi-at me-0.5"></i><?php echo sanitizeInput($handleClean); ?>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($user['business_name'])): ?>
                        <div class="badge bg-secondary-subtle text-secondary fw-semibold mb-2"><?php echo htmlspecialchars($user['business_name']); ?></div>
                    <?php endif; ?>
                    <p class="text-muted small mb-0"><?php echo !empty($user['designation']) ? htmlspecialchars($user['designation']) : 'Registered Member'; ?></p>
                </div>

                <hr class="text-secondary opacity-25">

                <div class="small">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted"><i class="bi bi-person-badge me-2 text-primary"></i>User ID</span>
                        <span class="fw-bold text-dark">#<?php echo intval($user['id']); ?></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted"><i class="bi bi-telephone me-2 text-primary"></i>Mobile</span>
                        <span class="fw-medium text-dark">+91 <?php echo htmlspecialchars($user['mobile']); ?></span>
                    </div>
                    <?php if (!empty($user['whatsapp'])): ?>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"><i class="bi bi-whatsapp me-2 text-success"></i>WhatsApp</span>
                            <span class="fw-medium text-dark">+91 <?php echo htmlspecialchars($user['whatsapp']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($user['email'])): ?>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"><i class="bi bi-envelope me-2 text-primary"></i>Email</span>
                            <span class="fw-medium text-dark"><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <hr class="text-secondary opacity-25">

                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold shadow-xs" data-bs-toggle="modal" data-bs-target="#customHandleModal">
                        <i class="bi bi-at me-1"></i> Custom Profile Handle (@username)
                    </button>
                    <button type="button" class="btn btn-warning btn-sm w-100 rounded-pill fw-bold text-dark shadow-xs" data-bs-toggle="modal" data-bs-target="#claimSearchModal">
                        <i class="bi bi-shield-check me-1"></i> Claim Existing Business
                    </button>
                    <a href="add-contact.php" class="btn btn-success btn-sm w-100 rounded-pill fw-bold shadow-xs">
                        <i class="bi bi-plus-circle me-1"></i> Add New Business Free
                    </a>
                    <button type="button" class="btn btn-light btn-sm w-100 rounded-pill fw-semibold text-secondary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="bi bi-gear me-1"></i> Manage Account Settings
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-semibold" data-bs-toggle="modal" data-bs-target="#deleteProfileModal">
                        <i class="bi bi-trash me-1"></i> Delete User Profile
                    </button>
                </div>
            </div>

            <!-- Quick Support Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-headset me-2 text-warning"></i> Online Billing Support</h6>
                <p class="small text-muted mb-3">Questions about your membership invoice or online transactions? Contact our billing desk.</p>
                <a href="contact.php" class="btn btn-outline-dark btn-sm rounded-pill w-100 fw-bold">Contact Support</a>
            </div>
        </div>

        <!-- Main Column: User's Listings & Payment Logs -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3 flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold font-heading text-dark mb-0">My Directory Listings & Claims</h4>
                        <small class="text-muted">Listings owned or claimed by your user account (+91 <?php echo htmlspecialchars($user['mobile']); ?>)</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-warning text-dark btn-sm rounded-pill fw-bold shadow-xs" data-bs-toggle="modal" data-bs-target="#claimSearchModal">
                            <i class="bi bi-shield-check me-1"></i> Claim Business
                        </button>
                        <a href="add-contact.php" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">
                            <i class="bi bi-plus-circle me-1"></i> Add Business
                        </a>
                        <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill">
                            Total: <?php echo count($userListings); ?> Listing<?php echo count($userListings) === 1 ? '' : 's'; ?>
                        </span>
                    </div>
                </div>

                <?php if (empty($userListings)): ?>
                    <div class="text-center py-5">
                        <div class="text-muted display-4 mb-3"><i class="bi bi-shop-window"></i></div>
                        <h5 class="fw-bold text-dark mb-2">No Listings Found</h5>
                        <p class="text-muted small mx-auto mb-4" style="max-width: 420px;">No directory listings are linked to this user account yet.</p>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-xs" data-bs-toggle="modal" data-bs-target="#claimSearchModal">
                                <i class="bi bi-shield-check me-1"></i> Claim Existing Business
                            </button>
                            <a href="add-contact.php" class="btn btn-outline-primary fw-bold rounded-pill px-4">
                                <i class="bi bi-plus-circle me-1"></i> Submit New Business Free
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Mobile Card View (< md screens) -->
                    <div class="d-block d-md-none">
                        <?php foreach ($userListings as $l): 
                            $cStatus = $l['claim_status'] ?? null;
                        ?>
                            <div class="card border rounded-3 p-3 mb-3 shadow-xs bg-white">
                                <div class="d-flex align-items-start justify-content-between mb-2 gap-2">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1 font-heading"><?php echo htmlspecialchars($l['title']); ?></h6>
                                        <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill extra-small">
                                            <i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($l['category_name'] ?? 'General'); ?>
                                        </span>
                                    </div>
                                    <div>
                                        <?php if ($cStatus === 'PENDING'): ?>
                                            <span class="badge bg-warning text-dark px-2 py-1 rounded-pill small"><i class="bi bi-hourglass-split me-1"></i>Claim Pending</span>
                                        <?php elseif ($cStatus === 'APPROVED'): ?>
                                            <span class="badge bg-success text-white px-2 py-1 rounded-pill small"><i class="bi bi-shield-check me-1"></i>Claim Approved</span>
                                        <?php elseif ($l['status'] === 'ACTIVE'): ?>
                                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill small"><i class="bi bi-check-circle me-1"></i>Active</span>
                                        <?php elseif ($l['status'] === 'PENDING'): ?>
                                            <span class="badge bg-warning-subtle text-dark px-2 py-1 rounded-pill small"><i class="bi bi-hourglass-split me-1"></i>Review</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill small">Inactive</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-2 flex-wrap gap-2">
                                    <div>
                                        <?php if (isset($l['plan_type']) && $l['plan_type'] === 'PLATINUM'): ?>
                                            <span class="badge bg-warning text-dark fw-bold px-2 py-1 rounded-pill extra-small">
                                                <i class="bi bi-crown-fill me-1 text-danger"></i> VIP Platinum
                                            </span>
                                        <?php elseif (isset($l['plan_type']) && $l['plan_type'] === 'GOLD'): ?>
                                            <span class="badge bg-primary text-white fw-bold px-2 py-1 rounded-pill extra-small">
                                                <i class="bi bi-patch-check-fill me-1"></i> Gold Business
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill extra-small">Basic Free</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex gap-1.5 align-items-center ms-auto">
                                        <a href="<?php echo getListingUrl($l['slug']); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 small" title="View Listing">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        <?php if ($cStatus === 'PENDING'): ?>
                                            <span class="badge bg-light text-muted border px-2 py-1 extra-small" title="Edit option will be unlocked after claim approval">
                                                <i class="bi bi-lock-fill me-1"></i>Pending Review
                                            </span>
                                        <?php else: ?>
                                            <a href="edit-listing.php?id=<?php echo sanitizeInput($l['id']); ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 small" title="Edit About & Business Details">
                                                <i class="bi bi-pencil me-1"></i>Edit About
                                            </a>
                                            <button type="button" class="btn btn-sm btn-warning text-dark rounded-pill px-2.5 py-1 small fw-bold" onclick="openUpgradeModal('<?php echo sanitizeInput($l['id']); ?>', '<?php echo sanitizeInput(addslashes($l['title'])); ?>', '<?php echo sanitizeInput($l['plan_type'] ?? 'FREE'); ?>')">
                                                <i class="bi bi-rocket-takeoff-fill me-1"></i>Upgrade
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Desktop Table View (>= md screens) -->
                    <div class="d-none d-md-block table-responsive">
                        <table class="table align-middle table-hover border">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th>Title & Category</th>
                                    <th>Plan Tier</th>
                                    <th>Claim & Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userListings as $l): 
                                    $cStatus = $l['claim_status'] ?? null;
                                ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($l['title']); ?></div>
                                            <small class="text-muted"><i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($l['category_name'] ?? 'General'); ?></small>
                                        </td>
                                        <td>
                                            <?php if (isset($l['plan_type']) && $l['plan_type'] === 'PLATINUM'): ?>
                                                <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill small shadow-xs">
                                                    <i class="bi bi-crown-fill me-1 text-danger"></i> VIP Platinum
                                                </span>
                                            <?php elseif (isset($l['plan_type']) && $l['plan_type'] === 'GOLD'): ?>
                                                <span class="badge bg-primary text-white fw-bold px-2.5 py-1 rounded-pill small shadow-xs">
                                                    <i class="bi bi-patch-check-fill me-1"></i> Gold Business
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-secondary border px-2.5 py-1 rounded-pill small">Basic Free</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($cStatus === 'PENDING'): ?>
                                                <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill small"><i class="bi bi-hourglass-split me-1"></i>Claim Pending</span>
                                                <small class="text-muted d-block extra-small">Awaiting Admin Approval</small>
                                            <?php elseif ($cStatus === 'APPROVED'): ?>
                                                <span class="badge bg-success text-white px-2.5 py-1 rounded-pill small"><i class="bi bi-shield-check me-1"></i>Claim Approved</span>
                                            <?php elseif ($l['status'] === 'ACTIVE'): ?>
                                                <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill small"><i class="bi bi-check-circle me-1"></i>Active</span>
                                            <?php elseif ($l['status'] === 'PENDING'): ?>
                                                <span class="badge bg-warning-subtle text-dark px-2.5 py-1 rounded-pill small"><i class="bi bi-hourglass-split me-1"></i>Under Review</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill small">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1.5 align-items-center">
                                                <a href="<?php echo getListingUrl($l['slug']); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="View Listing">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                                <?php if ($cStatus === 'PENDING'): ?>
                                                    <span class="badge bg-light text-muted border px-2 py-1 extra-small" title="Editing unlocked after approval">
                                                        <i class="bi bi-lock-fill me-1"></i>Pending Review
                                                    </span>
                                                <?php else: ?>
                                                    <a href="edit-listing.php?id=<?php echo sanitizeInput($l['id']); ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 small" title="Edit About & Business Details">
                                                        <i class="bi bi-pencil-square me-1"></i>Edit About
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-warning text-dark rounded-circle p-0 d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 32px; height: 32px;" title="Upgrade Membership Plan" onclick="openUpgradeModal('<?php echo sanitizeInput($l['id']); ?>', '<?php echo sanitizeInput(addslashes($l['title'])); ?>', '<?php echo sanitizeInput($l['plan_type'] ?? 'FREE'); ?>')">
                                                        <i class="bi bi-rocket-takeoff-fill"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ONLINE PAYMENTS & TRANSACTION HISTORY TABLE -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3 flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold font-heading text-dark mb-0">Payment History & Invoices</h4>
                        <small class="text-muted">Online transactions processed for your listings</small>
                    </div>
                    <div>
                        <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill">
                            <?php echo count($userPayments); ?> Transaction<?php echo count($userPayments) === 1 ? '' : 's'; ?>
                        </span>
                    </div>
                </div>

                <?php if (empty($userPayments)): ?>
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-receipt fs-3 d-block mb-1 opacity-50"></i>
                        No online payment transactions recorded yet.
                    </div>
                <?php else: ?>
                    <!-- Mobile View Payments (< md screens) -->
                    <div class="d-block d-md-none">
                        <?php foreach ($userPayments as $p): ?>
                            <div class="card border rounded-3 p-3 mb-3 shadow-xs bg-white">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-monospace fw-bold text-dark small">ID: <?php echo sanitizeInput($p['transaction_id']); ?></span>
                                    <span class="fw-bold text-dark">₹<?php echo number_format($p['amount'], 2); ?></span>
                                </div>
                                <div class="small fw-medium text-dark mb-2"><?php echo sanitizeInput($p['listing_title'] ?? 'Listing Upgrade'); ?></div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top flex-wrap gap-2">
                                    <div>
                                        <?php if ($p['payment_status'] === 'SUCCESS'): ?>
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2.5"><i class="bi bi-check-circle me-1"></i>SUCCESS</span>
                                        <?php elseif ($p['payment_status'] === 'PENDING'): ?>
                                            <span class="badge bg-warning-subtle text-dark rounded-pill px-2.5">PENDING</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5">FAILED</span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="generate_receipt.php?id=<?php echo $p['id']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-3.5 small rounded-pill fw-semibold">
                                        <i class="bi bi-file-earmark-text me-1"></i>Receipt
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Desktop Table View Payments (>= md screens) -->
                    <div class="d-none d-md-block table-responsive">
                        <table class="table align-middle border small mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Listing / Service</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end">Invoice / Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userPayments as $p): ?>
                                    <tr>
                                        <td class="font-monospace fw-bold text-dark"><?php echo sanitizeInput($p['transaction_id']); ?></td>
                                        <td class="fw-medium text-dark"><?php echo sanitizeInput($p['listing_title'] ?? 'Listing Upgrade'); ?></td>
                                        <td>
                                            <span class="badge <?php echo ($p['plan_type'] === 'PLATINUM') ? 'bg-warning text-dark' : 'bg-primary'; ?> rounded-pill">
                                                <?php echo sanitizeInput($p['plan_type']); ?>
                                            </span>
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
                                        <td class="text-muted small"><?php echo date('d M Y, h:i A', strtotime($p['created_at'])); ?></td>
                                        <td class="text-end">
                                            <a href="generate_receipt.php?id=<?php echo $p['id']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary py-0.5 px-2 small" title="View & Print Tax Invoice / Receipt">
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

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="bg-gradient-primary text-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0 text-white"><i class="bi bi-person-lines-fill me-2"></i> Edit Account Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <form action="dashboard.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_profile">
                <div class="modal-body p-4 bg-light">
                    
                    <!-- Section 1: Basic Info & Profile Photo -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-person-badge text-primary me-2"></i>1. Personal Info & Public Handle</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control rounded-3 py-2" value="<?php echo sanitizeInput($user['full_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Profile Photo / Avatar Upload</label>
                            <input type="file" name="profile_image_file" class="form-control rounded-3 py-2" accept="image/*">
                            <?php if (!empty($user['profile_image'])): ?>
                                <div class="mt-1 small text-success"><i class="bi bi-check-circle me-1"></i>Current photo uploaded</div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Profile Visibility</label>
                            <select name="profile_visibility" class="form-select rounded-3 py-2">
                                <option value="PUBLIC" <?php echo ($user['profile_visibility'] ?? 'PUBLIC') === 'PUBLIC' ? 'selected' : ''; ?>>🟢 PUBLIC (Visible in Directory & Search)</option>
                                <option value="PRIVATE" <?php echo ($user['profile_visibility'] ?? '') === 'PRIVATE' ? 'selected' : ''; ?>>🔴 PRIVATE (Hidden from Public)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Section 2: Professional Fields -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-briefcase text-primary me-2"></i>2. Professional Qualifications & Practice</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Designation / Profession Title</label>
                            <input type="text" name="designation" class="form-control rounded-3 py-2" value="<?php echo sanitizeInput($user['designation'] ?? ''); ?>" placeholder="e.g. Senior Advocate, Medical Specialist, Civil Engineer">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Business / Firm / Hospital Name</label>
                            <input type="text" name="business_name" class="form-control rounded-3 py-2" value="<?php echo sanitizeInput($user['business_name'] ?? ''); ?>" placeholder="e.g. Saran Law Chambers, City Hospital">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Profession Main Category</label>
                            <select name="category_id" id="user_category_id" class="form-select rounded-3 py-2" onchange="loadUserSubcategories(this.value)">
                                <option value="">-- Select Main Category --</option>
                                <?php foreach ($all_categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo (isset($user['category_id']) && $user['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo sanitizeInput($cat['name']); ?> <?php echo !empty($cat['hindi_name']) ? '('.sanitizeInput($cat['hindi_name']).')' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Sub-Category</label>
                            <select name="subcategory_id" id="user_subcategory_id" class="form-select rounded-3 py-2">
                                <option value="">-- Select Sub-Category --</option>
                                <?php if (!empty($user['subcategory_id'])): ?>
                                    <option value="<?php echo $user['subcategory_id']; ?>" selected><?php echo sanitizeInput($user['subcategory_name'] ?? 'Current Sub-Category'); ?></option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Specialization & Expertise</label>
                            <input type="text" name="specialization" class="form-control rounded-3 py-2" value="<?php echo sanitizeInput($user['specialization'] ?? ''); ?>" placeholder="e.g. Criminal Defense, Cardiology, Income Tax">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Educational Degree / Qualification</label>
                            <input type="text" name="education" class="form-control rounded-3 py-2" value="<?php echo sanitizeInput($user['education'] ?? ''); ?>" placeholder="e.g. LL.B (JPU Chapra), MBBS, M.Tech, CA">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Years of Experience</label>
                            <input type="text" name="experience_years" class="form-control rounded-3 py-2" value="<?php echo sanitizeInput($user['experience_years'] ?? ''); ?>" placeholder="e.g. 10 Years">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Office / Chamber Timings</label>
                            <input type="text" name="office_hours" class="form-control rounded-3 py-2" value="<?php echo sanitizeInput($user['office_hours'] ?? ''); ?>" placeholder="e.g. 9 AM - 7 PM">
                        </div>
                    </div>

                    <!-- Section 3: Contact & Location -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-geo-alt text-primary me-2"></i>3. Contact & Office Location</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3 py-2" value="<?php echo sanitizeInput($user['email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">WhatsApp Number</label>
                            <input type="text" name="whatsapp" class="form-control rounded-3 py-2" value="<?php echo sanitizeInput($user['whatsapp'] ?? ''); ?>" maxlength="10">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Block Location (Saran District)</label>
                            <select name="block_id" class="form-select rounded-3 py-2">
                                <option value="">-- Select Block --</option>
                                <?php foreach ($blocks as $b): ?>
                                    <option value="<?php echo $b['id']; ?>" <?php echo (isset($user['block_id']) && $user['block_id'] == $b['id']) ? 'selected' : ''; ?>>
                                        <?php echo sanitizeInput($b['block_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Pincode</label>
                            <input type="text" name="pincode" class="form-control rounded-3 py-2" value="<?php echo sanitizeInput($user['pincode'] ?? ''); ?>" maxlength="6">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark mb-1">Office / Street Address</label>
                            <input type="text" name="address" class="form-control rounded-3 py-2" value="<?php echo sanitizeInput($user['address'] ?? ''); ?>" placeholder="e.g. Court Campus, Chapra">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark mb-1">About & Professional Bio</label>
                            <textarea name="about" class="form-control rounded-3" rows="3" placeholder="Describe your professional background, achievements, court/clinic locations, and services..."><?php echo sanitizeInput($user['about'] ?: ($user['bio'] ?? '')); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white p-3 border-top">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="bi bi-check-lg me-1"></i> Save Professional Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Profile Handle (@username) Modal -->
<div class="modal fade" id="customHandleModal" tabindex="-1" aria-labelledby="customHandleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="bg-primary text-white p-3 p-sm-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0 text-white fs-6 fs-sm-5"><i class="bi bi-at me-1 me-sm-2"></i> Custom Profile Handle (@username)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="small text-white-50 mt-1" style="font-size:0.8rem;">Set your custom web address to share your public directory profile</div>
            </div>
            <form action="dashboard.php" method="POST">
                <input type="hidden" name="action" value="update_profile">
                <input type="hidden" name="full_name" value="<?php echo sanitizeInput($user['full_name']); ?>">
                <input type="hidden" name="email" value="<?php echo sanitizeInput($user['email'] ?? ''); ?>">
                <input type="hidden" name="whatsapp" value="<?php echo sanitizeInput($user['whatsapp'] ?? ''); ?>">
                <input type="hidden" name="business_name" value="<?php echo sanitizeInput($user['business_name'] ?? ''); ?>">
                <input type="hidden" name="designation" value="<?php echo sanitizeInput($user['designation'] ?? ''); ?>">
                <input type="hidden" name="profession_category" value="<?php echo sanitizeInput($user['profession_category'] ?? ''); ?>">
                <input type="hidden" name="category_id" value="<?php echo sanitizeInput($user['category_id'] ?? ''); ?>">
                <input type="hidden" name="subcategory_id" value="<?php echo sanitizeInput($user['subcategory_id'] ?? ''); ?>">
                <input type="hidden" name="specialization" value="<?php echo sanitizeInput($user['specialization'] ?? ''); ?>">
                <input type="hidden" name="education" value="<?php echo sanitizeInput($user['education'] ?? ''); ?>">
                <input type="hidden" name="experience_years" value="<?php echo sanitizeInput($user['experience_years'] ?? ''); ?>">
                <input type="hidden" name="office_hours" value="<?php echo sanitizeInput($user['office_hours'] ?? ''); ?>">
                <input type="hidden" name="block_id" value="<?php echo sanitizeInput($user['block_id'] ?? ''); ?>">
                <input type="hidden" name="address" value="<?php echo sanitizeInput($user['address'] ?? ''); ?>">
                <input type="hidden" name="pincode" value="<?php echo sanitizeInput($user['pincode'] ?? ''); ?>">
                <input type="hidden" name="about" value="<?php echo sanitizeInput($user['about'] ?: ($user['bio'] ?? '')); ?>">

                <div class="modal-body p-3 p-sm-4 bg-light">
                    <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-1">
                        <label for="modal_username_handle" class="form-label fw-bold small text-dark mb-0">Choose Username Handle</label>
                        <span id="modalHandleBadge" class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small fw-semibold text-wrap">
                            <i class="bi bi-check-circle-fill me-1"></i> URL Available
                        </span>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white fw-bold text-primary fs-6">@</span>
                        <input type="text" name="username_handle" id="modal_username_handle" class="form-control py-2.5 fw-semibold border-secondary-subtle" value="<?php echo sanitizeInput(ltrim($user['username_handle'] ?? '', '@')); ?>" placeholder="e.g. KumarGaurav" pattern="[a-zA-Z0-9_]{8,24}" title="8 to 24 letters, numbers, or underscores" oninput="checkModalHandleLive(this.value)">
                    </div>

                    <div class="p-3 bg-white rounded-3 border text-muted small mb-2 text-break">
                        Your Public URL: <strong class="text-dark font-monospace ms-1 d-inline-block text-break" id="modalPublicUrlPreview">saranindex.com/@<?php echo sanitizeInput(ltrim($user['username_handle'] ?? 'username', '@')); ?></strong>
                    </div>
                </div>
                <div class="modal-footer bg-white p-3 border-top flex-wrap gap-2 justify-content-end">
                    <button type="button" class="btn btn-light rounded-pill px-4 flex-fill flex-sm-grow-0" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold flex-fill flex-sm-grow-0"><i class="bi bi-check-lg me-1"></i> Save Custom Handle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Profile Modal -->
<div class="modal fade" id="deleteProfileModal" tabindex="-1" aria-labelledby="deleteProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="bg-danger text-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0 text-white"><i class="bi bi-exclamation-triangle-fill me-2"></i> Delete User Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <form action="dashboard.php" method="POST">
                <input type="hidden" name="action" value="delete_profile">
                <div class="modal-body p-4 bg-light">
                    <div class="alert alert-danger border-danger-subtle rounded-3 small mb-3">
                        <i class="bi bi-shield-exclamation me-1"></i> <strong>Warning:</strong> Deleting your user profile is <strong>permanent</strong> and cannot be undone.
                    </div>
                    <p class="small text-muted mb-3">Your user credentials, profile photo, and personal info will be permanently deleted. Your submitted directory listings will be unlinked from your account.</p>
                    <div class="mb-3">
                        <label for="confirm_delete" class="form-label fw-bold small text-dark">Type <span class="text-danger">DELETE</span> to confirm profile deletion:</label>
                        <input type="text" name="confirm_delete" id="confirm_delete" class="form-control rounded-3" placeholder="Type DELETE in capital letters" required autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer bg-white p-3 border-top">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold"><i class="bi bi-trash-fill me-1"></i> Permanently Delete Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upgrade Membership Plan & Online Payment Modal -->
<div class="modal fade" id="upgradePlanModal" tabindex="-1" aria-labelledby="upgradePlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="bg-primary text-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0 text-white"><i class="bi bi-credit-card-fill me-2"></i> Online Membership Plan Checkout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="small text-white-50 mt-1" id="upgradeModalListingTitle">Secure online transaction tied to User ID #<?php echo intval($user['id']); ?></div>
            </div>
            <form action="dashboard.php<?php echo $viewAll ? '?view=all' : ''; ?>" method="POST" id="upgradeForm">
                <input type="hidden" name="action" value="upgrade_plan">
                <input type="hidden" name="listing_id" id="upgrade_listing_id" value="">
                
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="card h-100 border rounded-4 p-3 cursor-pointer bg-white">
                                <input type="radio" name="plan_type" value="FREE" id="plan_radio_free" class="form-check-input mb-2">
                                <div class="fw-bold text-dark fs-6">🟢 Basic Free</div>
                                <div class="fs-5 fw-bold text-primary mb-2">₹0 / year</div>
                                <span class="small text-muted">Standard search rank & basic info.</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="card h-100 border border-primary rounded-4 p-3 cursor-pointer bg-primary-subtle border-2">
                                <input type="radio" name="plan_type" value="GOLD" id="plan_radio_gold" class="form-check-input mb-2">
                                <div class="badge bg-primary text-white fw-bold w-auto me-auto mb-1">Recommended</div>
                                <div class="fw-bold text-primary fs-6">🔵 Gold Business</div>
                                <div class="fs-5 fw-bold text-primary mb-2">₹499 / year</div>
                                <span class="small text-dark">Top priority rank, Green Verified Badge, Up to 3 photos & WhatsApp button.</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="card h-100 border border-warning rounded-4 p-3 cursor-pointer bg-warning-subtle border-2">
                                <input type="radio" name="plan_type" value="PLATINUM" id="plan_radio_platinum" class="form-check-input mb-2">
                                <div class="badge bg-warning text-dark fw-bold w-auto me-auto mb-1">Best Spot</div>
                                <div class="fw-bold text-dark fs-6">👑 VIP Platinum</div>
                                <div class="fs-5 fw-bold text-dark mb-2">₹1,499 / year</div>
                                <span class="small text-dark">Top Featured Spot, Gold VIP Verified Badge, Up to 6 photos & direct lead links.</span>
                            </label>
                        </div>

                    </div>

                    <div class="p-3 bg-white rounded-3 border d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-success fs-4"></i>
                            <span class="small text-secondary">Secured Razorpay Gateway • Instant Activation • Receipt Logged to User ID #<?php echo intval($user['id']); ?></span>
                        </div>
                        <span class="badge bg-light text-dark border">Razorpay UPI / Cards</span>
                    </div>
                </div>
                <div class="modal-footer bg-white p-3 border-top">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="upgradePayBtn" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="bi bi-lock-fill me-1"></i> Pay via Razorpay</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Claim Existing Business Modal -->
<div class="modal fade" id="claimSearchModal" tabindex="-1" aria-labelledby="claimSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white py-3 px-4">
                <h5 class="modal-title fw-bold" id="claimSearchModalLabel">
                    <i class="bi bi-shield-check text-warning me-2"></i>Claim Existing Business Listing
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="dashboard.php" method="POST">
                <input type="hidden" name="action" value="claim_business">
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 mb-4 small d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-info fs-4 me-3"></i>
                        <div>
                            Are you the owner, manager, or authorized representative of an existing business listed on Saran Index? Submit your claim for admin verification.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="claim_listing_select" class="form-label fw-bold text-dark small">1. Select Business Listing to Claim *</label>
                        <select name="listing_id" id="claim_listing_select" class="form-select rounded-3" required>
                            <option value="">-- Choose Existing Business from Saran Index Directory --</option>
                            <?php 
                            $db_claim_select = getDB();
                            if ($db_claim_select) {
                                $all_listings = $db_claim_select->query("SELECT id, title, mobile, address FROM listings ORDER BY title ASC")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($all_listings as $al) {
                                    echo '<option value="' . $al['id'] . '">' . htmlspecialchars($al['title']) . ' (ID #' . $al['id'] . ' - ' . htmlspecialchars($al['mobile']) . ')</option>';
                                }
                            }
                            ?>
                        </select>
                        <small class="text-muted extra-small">Can't find your business? You can also <a href="search.php" target="_blank" class="fw-semibold">search directory listings</a> or <a href="add-contact.php" target="_blank" class="fw-semibold">add a new listing free</a>.</small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark small">2. Claimant Full Name *</label>
                            <input type="text" name="claimant_name" class="form-control rounded-3" value="<?php echo sanitizeInput($user['full_name'] ?? ''); ?>" required placeholder="e.g. Kumar Gaurav">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark small">3. Claimant Contact Mobile *</label>
                            <input type="tel" name="claimant_mobile" class="form-control rounded-3" value="<?php echo sanitizeInput($user['mobile'] ?? ''); ?>" required placeholder="10-digit mobile number" maxlength="10">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark small">4. Designation / Role *</label>
                            <select name="role_title" class="form-select rounded-3">
                                <option value="Owner / Proprietor">Owner / Proprietor</option>
                                <option value="Authorized Manager">Authorized Manager</option>
                                <option value="Business Partner">Business Partner</option>
                                <option value="Official Representative">Official Representative</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark small">5. Verification Proof / GST / Reg. No. (Optional)</label>
                            <input type="text" name="verification_proof" class="form-control rounded-3" placeholder="GSTIN, Udyam, Registration No. or ID proof">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4 border-top">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm">
                        <i class="bi bi-shield-check me-1"></i> Submit Ownership Claim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openUpgradeModal(id, title, plan) {
    document.getElementById('upgrade_listing_id').value = id;
    document.getElementById('upgradeModalListingTitle').innerText = 'Online payment checkout for: ' + title;
    
    document.getElementById('plan_radio_free').checked = (plan === 'FREE');
    document.getElementById('plan_radio_gold').checked = (plan === 'GOLD');
    document.getElementById('plan_radio_platinum').checked = (plan === 'PLATINUM');
    
    const modal = new bootstrap.Modal(document.getElementById('upgradePlanModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    const upgradeForm = document.getElementById('upgradeForm');
    if (upgradeForm) {
        upgradeForm.addEventListener('submit', function(e) {
            const selectedPlan = document.querySelector('input[name="plan_type"]:checked').value;
            const listingId = document.getElementById('upgrade_listing_id').value;

            if (selectedPlan === 'FREE') {
                return true;
            }

            e.preventDefault();
            const btn = document.getElementById('upgradePayBtn');
            const originalBtnText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-arrow-repeat spin me-1"></i> Opening Razorpay...';

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

                if (data.status === 'success') {
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
                            "color": "#1e3a8a"
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
                                    alert('Razorpay Payment Successful! Your ' + selectedPlan + ' plan has been activated.');
                                    window.location.reload();
                                } else {
                                    alert('Razorpay verification error: ' + vData.message);
                                }
                            });
                        }
                    };
                    const rzp = new Razorpay(options);
                    rzp.open();
                } else {
                    alert('Razorpay order creation failed: ' + data.message);
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
                console.error('Razorpay Error:', err);
                alert('An error occurred initializing Razorpay payment.');
            });
        });
    }
});

function loadUserSubcategories(catId, selectedSubId = null) {
    const subSelect = document.getElementById('user_subcategory_id');
    if (!subSelect) return;
    subSelect.innerHTML = '<option value="">-- Loading Sub-Categories... --</option>';
    if (!catId) {
        subSelect.innerHTML = '<option value="">-- Select Sub-Category --</option>';
        return;
    }

    fetch('api/subcategories_api.php?category_id=' + encodeURIComponent(catId))
        .then(response => response.json())
        .then(data => {
            subSelect.innerHTML = '<option value="">-- Select Sub-Category --</option>';
            if (data && data.subcategories && Array.isArray(data.subcategories)) {
                data.subcategories.forEach(sub => {
                    const opt = document.createElement('option');
                    opt.value = sub.id;
                    opt.textContent = sub.name + (sub.hindi_name ? ' (' + sub.hindi_name + ')' : '');
                    if (selectedSubId && sub.id == selectedSubId) {
                        opt.selected = true;
                    }
                    subSelect.appendChild(opt);
                });
            }
        })
        .catch(err => {
            console.error('Failed to load subcategories:', err);
            subSelect.innerHTML = '<option value="">-- Select Sub-Category --</option>';
        });
}

let pageHandleTimeout = null;
function checkPageHandleLive(val) {
    const clean = val.replace(/[^a-zA-Z0-9_]/g, '');
    const badge = document.getElementById('pageHandleBadge');
    const urlPreview = document.getElementById('pagePublicUrlPreview');
    const urlLink = document.getElementById('pagePublicUrlLink');
    const userId = <?php echo intval($user['id']); ?>;

    if (urlPreview) {
        urlPreview.textContent = 'saranindex.com/@' + (clean || 'username');
    }
    if (urlLink) {
        urlLink.href = '@' + (clean || 'username');
    }

    if (!clean || clean.length < 3) {
        if (badge) {
            badge.className = 'badge bg-warning-subtle text-dark border border-warning-subtle px-3 py-1.5 rounded-pill small fw-semibold';
            badge.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> Min 3 chars required';
        }
        return;
    }

    if (badge) {
        badge.className = 'badge bg-light text-secondary border px-3 py-1.5 rounded-pill small fw-semibold';
        badge.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Checking URL...';
    }

    clearTimeout(pageHandleTimeout);
    pageHandleTimeout = setTimeout(() => {
        fetch('api/check_username.php?handle=' + encodeURIComponent(clean) + '&user_id=' + userId)
            .then(res => res.json())
            .then(data => {
                if (data.available) {
                    if (badge) {
                        badge.className = 'badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill small fw-semibold';
                        badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> URL Available!';
                    }
                } else {
                    if (badge) {
                        badge.className = 'badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill small fw-semibold';
                        badge.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Handle Taken';
                    }
                }
            }).catch(() => {
                if (badge) {
                    badge.className = 'badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill small fw-semibold';
                    badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> URL Available';
                }
            });
    }, 300);
}

let modalHandleTimeout = null;
function checkModalHandleLive(val) {
    const clean = val.replace(/[^a-zA-Z0-9_]/g, '');
    const badge = document.getElementById('modalHandleBadge');
    const urlPreview = document.getElementById('modalPublicUrlPreview');
    const userId = <?php echo intval($user['id']); ?>;

    if (urlPreview) {
        urlPreview.textContent = 'saranindex.com/@' + (clean || 'username');
    }

    if (!clean || clean.length < 8 || clean.length > 24) {
        if (badge) {
            badge.className = 'badge bg-warning-subtle text-dark border border-warning-subtle px-2.5 py-1 rounded-pill small fw-semibold';
            badge.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> 8 to 24 chars required';
        }
        return;
    }

    if (badge) {
        badge.className = 'badge bg-light text-secondary border px-2.5 py-1 rounded-pill small fw-semibold';
        badge.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Checking URL...';
    }

    clearTimeout(modalHandleTimeout);
    modalHandleTimeout = setTimeout(() => {
        fetch('api/check_username.php?handle=' + encodeURIComponent(clean) + '&user_id=' + userId)
            .then(res => res.json())
            .then(data => {
                if (data.available) {
                    if (badge) {
                        badge.className = 'badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small fw-semibold';
                        badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> URL Available!';
                    }
                } else {
                    if (badge) {
                        badge.className = 'badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill small fw-semibold';
                        badge.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Handle Taken';
                    }
                }
            }).catch(() => {
                if (badge) {
                    badge.className = 'badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small fw-semibold';
                    badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> URL Available';
                }
            });
    }, 300);
}

let handleCheckTimeout = null;
function checkDashboardHandleLive(val) {
    const clean = val.replace(/[^a-zA-Z0-9_]/g, '');
    const badge = document.getElementById('handleAvailabilityBadge');
    const urlPreview = document.getElementById('dashPublicUrlPreview');
    const urlLink = document.getElementById('dashPublicUrlLink');
    const userId = <?php echo intval($user['id']); ?>;

    if (urlPreview) {
        urlPreview.textContent = 'saranindex.com/@' + (clean || 'username');
    }
    if (urlLink) {
        urlLink.href = '@' + (clean || 'username');
    }

    if (!clean || clean.length < 3) {
        if (badge) {
            badge.className = 'badge bg-warning-subtle text-dark border border-warning-subtle px-2.5 py-1 rounded-pill small fw-semibold';
            badge.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> Min 3 chars required';
        }
        return;
    }

    if (badge) {
        badge.className = 'badge bg-light text-secondary border px-2.5 py-1 rounded-pill small fw-semibold';
        badge.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Checking URL...';
    }

    clearTimeout(handleCheckTimeout);
    handleCheckTimeout = setTimeout(() => {
        fetch('api/check_username.php?handle=' + encodeURIComponent(clean) + '&user_id=' + userId)
            .then(res => res.json())
            .then(data => {
                if (data.available) {
                    if (badge) {
                        badge.className = 'badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small fw-semibold';
                        badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> URL Available!';
                    }
                } else {
                    if (badge) {
                        badge.className = 'badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill small fw-semibold';
                        badge.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Handle Taken';
                    }
                }
            }).catch(() => {
                if (badge) {
                    badge.className = 'badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small fw-semibold';
                    badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> URL Available';
                }
            });
    }, 300);
}

document.addEventListener('DOMContentLoaded', function() {
    const catSelect = document.getElementById('user_category_id');
    if (catSelect && catSelect.value) {
        const currentSubId = "<?php echo $user['subcategory_id'] ?? ''; ?>";
        loadUserSubcategories(catSelect.value, currentSubId);
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


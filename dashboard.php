<?php

require_once __DIR__ . '/includes/functions.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = getLoggedInUser();
if (!$user) {
    logoutPublicUser();
    header("Location: login.php");
    exit;
}

if (empty($user['mobile_status']) || strtoupper($user['mobile_status']) !== 'VERIFIED') {
    header("Location: verify-mobile.php");
    exit;
}

$msg = '';
$msg_type = '';

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
    $newPassword = $_POST['new_password'] ?? '';

    $res = updateUserProfile($user['id'], $fullName, $email, $blockId, $address, $newPassword, $whatsapp, $businessName, $designation, $pincode, null, null, $bio);
    if ($res['success']) {
        $msg = $res['message'];
        $msg_type = 'success';
        $user = getLoggedInUser(); // Refresh user data
    } else {
        $msg = $res['message'];
        $msg_type = 'danger';
    }
}

$userListings = getUserListings($user['mobile']);
$blocks = getBlocks();

$page_title = "My Account Dashboard – Saran Index";
$meta_description = "User account dashboard on Saran Index. Manage your listings, profile, and business directory submissions.";

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <span class="badge bg-success-subtle text-success fw-bold px-3 py-1 rounded-pill small mb-1">
                    <i class="bi bi-shield-check me-1"></i> Active Member Account
                </span>
                <h2 class="fw-bold font-heading text-dark mb-0">Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h2>
                <small class="text-muted"><i class="bi bi-telephone me-1"></i>+91 <?php echo htmlspecialchars($user['mobile']); ?> <?php echo !empty($user['email']) ? '• <i class="bi bi-envelope me-1"></i>' . htmlspecialchars($user['email']) : ''; ?></small>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary rounded-pill fw-bold px-3" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profile
                </button>
                <a href="add-listing.php" class="btn btn-warning text-dark fw-bold rounded-pill shadow-sm px-4">
                    <i class="bi bi-plus-circle-fill me-1"></i> Add Business Free
                </a>
                <a href="logout.php" class="btn btn-outline-danger rounded-pill fw-semibold px-3">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <?php if (!empty($msg)): ?>
        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show rounded-3 small py-2.5 mb-4" role="alert">
            <i class="bi bi-info-circle-fill me-1"></i> <?php echo $msg; ?>
            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <!-- Sidebar Profile Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="text-center pb-3 border-bottom mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold fs-2 mb-3" style="width: 72px; height: 72px;">
                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                    </div>
                    <h5 class="fw-bold text-dark font-heading mb-1"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                    <?php if (!empty($user['designation'])): ?>
                        <div class="text-muted small mb-1 fw-semibold"><i class="bi bi-briefcase me-1"></i><?php echo htmlspecialchars($user['designation']); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($user['business_name'])): ?>
                        <div class="badge bg-warning-subtle text-dark border px-3 py-1 rounded-pill small mb-1"><i class="bi bi-building me-1"></i><?php echo htmlspecialchars($user['business_name']); ?></div>
                    <?php else: ?>
                        <span class="badge bg-light text-secondary border px-3 py-1 rounded-pill small">Saran Directory User</span>
                    <?php endif; ?>
                    <?php if (!empty($user['bio'])): ?>
                        <p class="small text-muted fst-italic mt-2 mb-0"><?php echo htmlspecialchars($user['bio']); ?></p>
                    <?php endif; ?>
                </div>

                <ul class="list-unstyled small text-secondary mb-3">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span><i class="bi bi-telephone text-primary me-2"></i>Mobile Number</span>
                        <strong class="text-dark">+91 <?php echo htmlspecialchars($user['mobile']); ?></strong>
                    </li>
                    <?php if (!empty($user['whatsapp'])): ?>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span><i class="bi bi-whatsapp text-success me-2"></i>WhatsApp</span>
                        <strong class="text-dark">+91 <?php echo htmlspecialchars($user['whatsapp']); ?></strong>
                    </li>
                    <?php endif; ?>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span><i class="bi bi-envelope text-primary me-2"></i>Email Address</span>
                        <strong class="text-dark"><?php echo !empty($user['email']) ? htmlspecialchars($user['email']) : 'Not Provided'; ?></strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span><i class="bi bi-geo-alt text-primary me-2"></i>Saran Block</span>
                        <strong class="text-dark"><?php echo !empty($user['block_name']) ? htmlspecialchars($user['block_name']) : 'Saran District'; ?></strong>
                    </li>
                    <?php if (!empty($user['pincode'])): ?>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span><i class="bi bi-pin-map text-primary me-2"></i>PIN Code</span>
                        <strong class="text-dark"><?php echo htmlspecialchars($user['pincode']); ?></strong>
                    </li>
                    <?php endif; ?>
                    <li class="d-flex justify-content-between py-2">
                        <span><i class="bi bi-calendar-event text-primary me-2"></i>Member Since</span>
                        <strong class="text-dark"><?php echo date('d M Y', strtotime($user['created_at'])); ?></strong>
                    </li>
                </ul>

                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-pencil me-1"></i> Update Profile Data
                </button>
            </div>

            <!-- Quick Help Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-headset me-2 text-warning"></i> Need Support?</h6>
                <p class="small text-muted mb-3">If you need help editing your business listing or adding custom categories, feel free to contact our support team.</p>
                <a href="contact.php" class="btn btn-outline-dark btn-sm rounded-pill w-100 fw-bold">Contact Support</a>
            </div>
        </div>

        <!-- Main Column: User's Listings -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <div>
                        <h4 class="fw-bold font-heading text-dark mb-0">My Submitted Listings</h4>
                        <small class="text-muted">Directory listings associated with your registered mobile number (+91 <?php echo htmlspecialchars($user['mobile']); ?>)</small>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-1.5 fw-bold"><?php echo count($userListings); ?> Listed</span>
                </div>

                <?php if (empty($userListings)): ?>
                    <div class="text-center py-5">
                        <div class="text-muted display-4 mb-3"><i class="bi bi-shop-window"></i></div>
                        <h5 class="fw-bold text-dark mb-2">No Listings Found</h5>
                        <p class="text-muted small mx-auto mb-4" style="max-width: 420px;">You have not submitted any business or professional listings under this mobile number yet.</p>
                        <a href="add-listing.php" class="btn btn-warning text-dark fw-bold rounded-pill px-4">
                            <i class="bi bi-plus-circle me-1"></i> Submit Business Free
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover border">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th>Title & Category</th>
                                    <th>Block</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userListings as $l): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($l['title']); ?></div>
                                            <small class="text-muted"><i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($l['category_name'] ?? 'General'); ?></small>
                                        </td>
                                        <td class="small text-dark">
                                            <i class="bi bi-geo-alt me-1 text-muted"></i><?php echo htmlspecialchars($l['block_name'] ?? 'Saran'); ?>
                                        </td>
                                        <td>
                                            <?php if ($l['status'] === 'ACTIVE'): ?>
                                                <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill small"><i class="bi bi-check-circle me-1"></i>Active</span>
                                            <?php elseif ($l['status'] === 'PENDING'): ?>
                                                <span class="badge bg-warning-subtle text-dark px-2.5 py-1 rounded-pill small"><i class="bi bi-hourglass-split me-1"></i>Under Review</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill small">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo getListingUrl($l['slug']); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                View <i class="bi bi-box-arrow-up-right ms-1"></i>
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                <h5 class="modal-title fw-bold font-heading text-white" id="editProfileModalLabel">
                    <i class="bi bi-person-gear text-warning me-2"></i> Update User Profile & Business Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="action" value="update_profile">
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label fw-bold small text-dark">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control" required value="<?php echo htmlspecialchars($user['full_name']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="mobile_readonly" class="form-label fw-bold small text-muted">Registered Mobile</label>
                            <input type="text" id="mobile_readonly" class="form-control bg-light" readonly value="+91 <?php echo htmlspecialchars($user['mobile']); ?>">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="whatsapp" class="form-label fw-bold small text-dark">WhatsApp Number</label>
                            <input type="tel" name="whatsapp" id="whatsapp" class="form-control" placeholder="10-digit WhatsApp" maxlength="10" value="<?php echo htmlspecialchars($user['whatsapp'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold small text-dark">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="yourname@gmail.com" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="business_name" class="form-label fw-bold small text-dark">Business / Shop Name</label>
                            <input type="text" name="business_name" id="business_name" class="form-control" placeholder="e.g. OfferPlant Technologies" value="<?php echo htmlspecialchars($user['business_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="designation" class="form-label fw-bold small text-dark">Profession / Designation</label>
                            <input type="text" name="designation" id="designation" class="form-control" placeholder="e.g. Business Owner / Doctor / Advocate" value="<?php echo htmlspecialchars($user['designation'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="block_id" class="form-label fw-bold small text-dark">Saran Block</label>
                            <select name="block_id" id="block_id" class="form-select">
                                <option value="">-- Select Block --</option>
                                <?php foreach ($blocks as $b): ?>
                                    <option value="<?php echo $b['id']; ?>" <?php echo ($user['block_id'] == $b['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($b['block_name']); ?> (<?php echo htmlspecialchars($b['hindi_name']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="pincode" class="form-label fw-bold small text-dark">PIN Code</label>
                            <input type="text" name="pincode" id="pincode" class="form-control" placeholder="e.g. 841301" maxlength="6" value="<?php echo htmlspecialchars($user['pincode'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-bold small text-dark">Address / Location</label>
                        <textarea name="address" id="address" class="form-control" rows="2" placeholder="Village / Town, Landmark..."><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label fw-bold small text-dark">About / Profile Bio</label>
                        <textarea name="bio" id="bio" class="form-control" rows="2" placeholder="Brief description of your business or profession..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>

                    <hr class="my-3">

                    <div class="mb-2">
                        <label for="new_password" class="form-label fw-bold small text-dark">Change Password <span class="text-muted font-normal">(Leave blank to keep current)</span></label>
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter new password (min 6 chars)">
                    </div>
                </div>

                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

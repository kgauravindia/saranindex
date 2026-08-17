<?php
/**
 * Admin Profile & Password Settings
 * Saran Index - Digital Directory
 */

require_once __DIR__ . '/includes/auth.php';
checkAdminAuth();

require_once __DIR__ . '/../includes/functions.php';

$admin_id = $_SESSION['admin_user_id'] ?? 0;
$admin = getAdminById($admin_id);

if (!$admin) {
    die("Admin account not found.");
}

$msg = '';
$msg_type = 'success';

// Handle Profile Update POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $post_data = [
        'username' => trim($_POST['username'] ?? ''),
        'full_name' => trim($_POST['full_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'mobile' => trim($_POST['mobile'] ?? ''),
        'role' => $admin['role'], // Preserve current role
        'scope_type' => $admin['scope_type'] ?? 'DISTRICT',
        'state' => sanitizeInput($_POST['state'] ?? 'Bihar'),
        'district' => sanitizeInput($_POST['district'] ?? 'Saran'),
        'block_id' => !empty($_POST['block_id']) ? intval($_POST['block_id']) : null,
        'designation' => sanitizeInput($_POST['designation'] ?? ''),
        'address' => sanitizeInput($_POST['address'] ?? ''),
        'about' => sanitizeInput($_POST['about'] ?? ''),
        'password' => '' // Password updated separately in change password section
    ];

    if (empty($post_data['username']) || empty($post_data['full_name'])) {
        $msg = "Full Name and Username are required fields.";
        $msg_type = "danger";
    } else {
        if (updateAdminAccount($admin_id, $post_data)) {
            $msg = "Profile details updated successfully!";
            $msg_type = "success";
            $_SESSION['admin_full_name'] = $post_data['full_name'];
            $_SESSION['admin_username'] = $post_data['username'];
            $admin = getAdminById($admin_id); // Refresh admin data
        } else {
            $msg = "Failed to update profile. Username may already be in use.";
            $msg_type = "danger";
        }
    }
}

// Handle Change Password POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = trim($_POST['current_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $msg = "Please fill in all password fields.";
        $msg_type = "danger";
    } elseif ($new_password !== $confirm_password) {
        $msg = "New password and Confirm password do not match.";
        $msg_type = "danger";
    } elseif (strlen($new_password) < 6) {
        $msg = "New password must be at least 6 characters long.";
        $msg_type = "danger";
    } else {
        $res = changeAdminPassword($admin_id, $current_password, $new_password);
        $msg = $res['message'];
        $msg_type = $res['success'] ? 'success' : 'danger';
    }
}

$blocks = getAllBlocks();
$header_title = "Admin Profile & Password Settings";
require_once __DIR__ . '/includes/header.php';
?>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Header Bar -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">My Admin Account</h4>
        <p class="text-muted small mb-0">Manage your profile details, designation, contact info, and change your password.</p>
    </div>
    <div>
        <a href="admins.php" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Admins List
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Profile Card & Overview -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 mb-4 text-center p-4">
            <div class="mx-auto bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3 mb-3 shadow" style="width: 80px; height: 80px;">
                <?php echo strtoupper(substr($admin['full_name'] ?? 'AD', 0, 2)); ?>
            </div>
            <h5 class="fw-bold mb-1"><?php echo sanitizeInput($admin['full_name']); ?></h5>
            <p class="text-muted small mb-2">@<?php echo sanitizeInput($admin['username']); ?></p>
            <div class="mb-3">
                <span class="badge bg-primary px-3 py-1.5 rounded-pill fs-7"><?php echo sanitizeInput($admin['role']); ?></span>
                <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill fs-7 ms-1"><?php echo sanitizeInput($admin['scope_type'] ?? 'DISTRICT'); ?></span>
            </div>
            <?php if (!empty($admin['designation'])): ?>
                <p class="small text-secondary mb-1"><i class="bi bi-briefcase me-1"></i><?php echo sanitizeInput($admin['designation']); ?></p>
            <?php endif; ?>
            <?php if (!empty($admin['email'])): ?>
                <p class="small text-secondary mb-1"><i class="bi bi-envelope me-1"></i><?php echo sanitizeInput($admin['email']); ?></p>
            <?php endif; ?>
            <?php if (!empty($admin['mobile'])): ?>
                <p class="small text-secondary mb-0"><i class="bi bi-telephone me-1"></i><?php echo sanitizeInput($admin['mobile']); ?></p>
            <?php endif; ?>
        </div>

        <!-- Quick Info Box -->
        <div class="card border-0 shadow-sm rounded-3 p-3">
            <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-shield-lock me-2 text-primary"></i>Account Information</h6>
            <div class="small mb-2">
                <span class="text-muted">Registered State/District:</span>
                <span class="fw-semibold text-dark float-end"><?php echo sanitizeInput(($admin['district'] ?? 'Saran') . ', ' . ($admin['state'] ?? 'Bihar')); ?></span>
            </div>
            <div class="small mb-2">
                <span class="text-muted">Assigned Block:</span>
                <span class="fw-semibold text-dark float-end"><?php echo sanitizeInput($admin['block_name'] ?? 'All Saran District'); ?></span>
            </div>
            <div class="small mb-2">
                <span class="text-muted">Last Active Login:</span>
                <span class="fw-semibold text-dark float-end"><?php echo !empty($admin['last_login']) ? date('d M Y, h:i A', strtotime($admin['last_login'])) : 'Now'; ?></span>
            </div>
            <div class="small">
                <span class="text-muted">Account Created:</span>
                <span class="fw-semibold text-dark float-end"><?php echo !empty($admin['created_at']) ? date('d M Y', strtotime($admin['created_at'])) : '-'; ?></span>
            </div>
        </div>
    </div>

    <!-- Right Column: Edit Profile Form & Change Password -->
    <div class="col-12 col-lg-8">
        <!-- Edit Profile Form -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Profile Details</h5>
            </div>
            <div class="card-body p-4">
                <form action="profile.php" method="POST">
                    <input type="hidden" name="update_profile" value="1">

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="full_name" class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo sanitizeInput($admin['full_name']); ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="username" class="form-label small fw-semibold">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">@</span>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo sanitizeInput($admin['username']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label small fw-semibold">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo sanitizeInput($admin['email'] ?? ''); ?>" placeholder="admin@saranindex.com">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="mobile" class="form-label small fw-semibold">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo sanitizeInput($admin['mobile'] ?? ''); ?>" placeholder="10-digit mobile number">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="designation" class="form-label small fw-semibold">Designation / Title</label>
                            <input type="text" class="form-control" id="designation" name="designation" value="<?php echo sanitizeInput($admin['designation'] ?? ''); ?>" placeholder="e.g. Senior Admin / Editor">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="block_id" class="form-label small fw-semibold">Assigned Block Jurisdiction</label>
                            <select class="form-select" id="block_id" name="block_id">
                                <option value="">Entire District (All Blocks)</option>
                                <?php foreach ($blocks as $blk): ?>
                                    <option value="<?php echo $blk['id']; ?>" <?php echo ($admin['block_id'] == $blk['id']) ? 'selected' : ''; ?>>
                                        <?php echo sanitizeInput($blk['name']); ?> (<?php echo sanitizeInput($blk['hindi_name'] ?? ''); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label small fw-semibold">Office Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo sanitizeInput($admin['address'] ?? ''); ?>" placeholder="District Administrative Building, Chapra, Saran">
                    </div>

                    <div class="mb-4">
                        <label for="about" class="form-label small fw-semibold">About / Bio Notes</label>
                        <textarea class="form-control" id="about" name="about" rows="3" placeholder="Brief description of admin responsibilities..."><?php echo sanitizeInput($admin['about'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold px-4 rounded-3 shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Save Profile Changes
                    </button>
                </form>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-key-fill me-2 text-warning"></i>Change Admin Password</h5>
            </div>
            <div class="card-body p-4">
                <form action="profile.php" method="POST">
                    <input type="hidden" name="change_password" value="1">

                    <div class="mb-3">
                        <label for="current_password" class="form-label small fw-semibold">Current Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter your existing password" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="new_password" class="form-label small fw-semibold">New Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="At least 6 characters" minlength="6" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="confirm_password" class="form-label small fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-type new password" minlength="6" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning text-dark fw-bold px-4 rounded-3 shadow-sm">
                        <i class="bi bi-shield-lock me-1"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

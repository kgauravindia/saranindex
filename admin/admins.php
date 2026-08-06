<?php
$header_title = "Manage Admin & Sub-Admin Accounts";
require_once __DIR__ . '/includes/header.php';

$msg = '';
$msg_type = 'success';

// Handle Delete Admin Action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $target_id = intval($_GET['id']);
    
    // Prevent admin from deleting their own logged-in account
    if (isset($_SESSION['admin_user_id']) && intval($_SESSION['admin_user_id']) === $target_id) {
        $msg = "You cannot delete your own currently logged-in admin account.";
        $msg_type = "danger";
    } else {
        if (deleteAdminAccount($target_id)) {
            $msg = "Admin account #{$target_id} removed successfully.";
            $msg_type = "warning";
        } else {
            $msg = "Failed to delete admin account.";
            $msg_type = "danger";
        }
    }
}

// Handle Add / Appoint New Admin / Sub-Admin POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $post_data = [
        'username' => trim($_POST['username'] ?? ''),
        'password' => trim($_POST['password'] ?? ''),
        'full_name' => trim($_POST['full_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'mobile' => trim($_POST['mobile'] ?? ''),
        'role' => sanitizeInput($_POST['role'] ?? 'SUB_ADMIN'),
        'scope_type' => sanitizeInput($_POST['scope_type'] ?? 'DISTRICT'),
        'state' => sanitizeInput($_POST['state'] ?? 'Bihar'),
        'district' => sanitizeInput($_POST['district'] ?? 'Saran'),
        'block_id' => !empty($_POST['block_id']) ? intval($_POST['block_id']) : null,
        'designation' => sanitizeInput($_POST['designation'] ?? ''),
        'address' => sanitizeInput($_POST['address'] ?? ''),
        'about' => sanitizeInput($_POST['about'] ?? '')
    ];

    if (empty($post_data['username']) || empty($post_data['password']) || empty($post_data['full_name'])) {
        $msg = "Please fill in all required fields: Full Name, Username, and Password.";
        $msg_type = "danger";
    } elseif (strlen($post_data['password']) < 6) {
        $msg = "Password must be at least 6 characters long.";
        $msg_type = "danger";
    } else {
        if (saveAdminAccount($post_data)) {
            $msg = "New Sub-Admin account '{$post_data['full_name']}' (@{$post_data['username']}) appointed successfully!";
            $msg_type = "success";
        } else {
            $msg = "Failed to appoint admin account. Username '{$post_data['username']}' may already exist.";
            $msg_type = "danger";
        }
    }
}

$admins_list = getAllAdmins();
$blocks_list = getBlocks();
?>

<!-- Header Actions Bar -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Administrator & Sub-Admin Accounts</h4>
        <p class="text-muted small mb-0">Super Admin can appoint Sub-Admins for Full District or specific Blocks with custom designations, address, and scope.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary fw-bold px-3 py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addAdminModal">
            <i class="bi bi-person-plus-fill me-1"></i> Appoint Sub-Admin / Admin
        </button>
    </div>
</div>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Admins Data Table -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th style="width: 50px;">#ID</th>
                    <th>Administrator & Designation</th>
                    <th>Jurisdiction Scope</th>
                    <th>State & Address</th>
                    <th>Contact Details</th>
                    <th>Access Role</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($admins_list)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No administrator accounts found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($admins_list as $a): ?>
                        <tr>
                            <td class="fw-bold text-muted">#<?php echo $a['id']; ?></td>
                            <td>
                                <div class="fw-bold text-dark mb-0.5"><?php echo sanitizeInput($a['full_name']); ?></div>
                                <div class="small text-primary fw-medium mb-1">
                                    <i class="bi bi-person-badge me-1"></i><?php echo sanitizeInput($a['designation'] ?: 'Administrator'); ?>
                                </div>
                                <div class="small text-muted">@<?php echo sanitizeInput($a['username']); ?></div>
                            </td>

                            <!-- Scope / Jurisdiction -->
                            <td>
                                <?php if (($a['scope_type'] ?? 'DISTRICT') === 'BLOCK' && !empty($a['block_name'])): ?>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 small">
                                        <i class="bi bi-geo-alt-fill me-1"></i>Block: <?php echo sanitizeInput($a['block_name']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 small">
                                        <i class="bi bi-globe2 me-1"></i>Full District (Saran)
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- State, District & Address -->
                            <td>
                                <div class="small fw-semibold text-dark"><i class="bi bi-building me-1 text-secondary"></i><?php echo sanitizeInput($a['district'] ?: 'Saran'); ?>, <?php echo sanitizeInput($a['state'] ?: 'Bihar'); ?></div>
                                <?php if (!empty($a['address'])): ?>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 200px;" title="<?php echo sanitizeInput($a['address']); ?>">
                                        <i class="bi bi-geo me-1"></i><?php echo sanitizeInput($a['address']); ?>
                                    </small>
                                <?php endif; ?>
                            </td>

                            <!-- Contact Info -->
                            <td>
                                <?php if (!empty($a['mobile'])): ?>
                                    <div class="small text-dark"><i class="bi bi-telephone me-1 text-primary"></i><?php echo sanitizeInput($a['mobile']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($a['email'])): ?>
                                    <div class="small text-muted"><i class="bi bi-envelope me-1"></i><?php echo sanitizeInput($a['email']); ?></div>
                                <?php endif; ?>
                            </td>

                            <!-- Access Role -->
                            <td>
                                <?php if ($a['role'] === 'SUPER_ADMIN'): ?>
                                    <span class="badge bg-primary px-3 py-1.5 rounded-pill"><i class="bi bi-shield-lock-fill me-1"></i>SUPER ADMIN</span>
                                <?php elseif ($a['role'] === 'SUB_ADMIN'): ?>
                                    <span class="badge bg-success px-3 py-1.5 rounded-pill"><i class="bi bi-person-check-fill me-1"></i>SUB ADMIN</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary text-white px-3 py-1.5 rounded-pill"><i class="bi bi-shield-check me-1"></i>MODERATOR</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewAdminModal<?php echo $a['id']; ?>" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <?php if (isset($_SESSION['admin_user_id']) && intval($_SESSION['admin_user_id']) === intval($a['id'])): ?>
                                        <button type="button" class="btn btn-outline-secondary disabled" title="Current Logged-in User">Current</button>
                                    <?php else: ?>
                                        <a href="admins.php?action=delete&id=<?php echo $a['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to remove this admin account?');" title="Delete Admin">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>

                        <!-- View Admin Details Modal -->
                        <div class="modal fade" id="viewAdminModal<?php echo $a['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-3">
                                    <div class="modal-header bg-dark text-white py-3">
                                        <h5 class="modal-title fw-bold"><i class="bi bi-person-badge me-2"></i>Admin Profile: <?php echo sanitizeInput($a['full_name']); ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <small class="text-muted d-block fw-semibold">Username</small>
                                                <span class="fw-bold text-dark">@<?php echo sanitizeInput($a['username']); ?></span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block fw-semibold">Designation</small>
                                                <span class="fw-bold text-primary"><?php echo sanitizeInput($a['designation'] ?: 'Administrator'); ?></span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block fw-semibold">Access Level</small>
                                                <span class="badge bg-primary rounded-pill"><?php echo sanitizeInput($a['role']); ?></span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block fw-semibold">Jurisdiction Scope</small>
                                                <span class="fw-semibold text-dark"><?php echo $a['scope_type'] === 'BLOCK' ? 'Block: ' . sanitizeInput($a['block_name']) : 'Full District (Saran)'; ?></span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block fw-semibold">Mobile Number</small>
                                                <span><?php echo sanitizeInput($a['mobile'] ?: 'Not provided'); ?></span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block fw-semibold">Email Address</small>
                                                <span><?php echo sanitizeInput($a['email'] ?: 'Not provided'); ?></span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block fw-semibold">State & District</small>
                                                <span><?php echo sanitizeInput($a['district'] ?: 'Saran'); ?>, <?php echo sanitizeInput($a['state'] ?: 'Bihar'); ?></span>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted d-block fw-semibold">Office Address</small>
                                                <span><?php echo sanitizeInput($a['address'] ?: 'Not specified'); ?></span>
                                            </div>
                                            <?php if (!empty($a['about'])): ?>
                                                <div class="col-12">
                                                    <small class="text-muted d-block fw-semibold">About / Responsibilities</small>
                                                    <div class="bg-light p-2.5 rounded border small text-dark"><?php echo nl2br(sanitizeInput($a['about'])); ?></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary btn-sm rounded-3" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Appoint Sub-Admin / Admin -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold" id="addAdminModalLabel"><i class="bi bi-person-plus-fill me-2"></i>Appoint New Sub-Admin / Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="admins.php" method="POST">
                <input type="hidden" name="add_admin" value="1">
                <div class="modal-body p-4">
                    
                    <!-- Section 1: Account Credentials -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-key text-primary me-2"></i>1. Account Credentials & Contact</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="full_name" class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="e.g. Vikash Kumar Singh" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="username" class="form-label small fw-semibold">Admin Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="e.g. vikash_admin" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="password" class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="At least 6 characters" required minlength="6">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="mobile" class="form-label small fw-semibold">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="e.g. 9876543210">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="email" class="form-label small fw-semibold">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="admin@saranindex.com">
                        </div>
                    </div>

                    <!-- Section 2: Jurisdiction Scope & Role -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-geo-alt text-primary me-2"></i>2. Jurisdiction Scope & Access Role</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-4">
                            <label for="role" class="form-label small fw-semibold">Admin Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="SUB_ADMIN" selected>SUB ADMIN</option>
                                <option value="SUPER_ADMIN">SUPER ADMIN</option>
                                <option value="MODERATOR">MODERATOR</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="scope_type" class="form-label small fw-semibold">Jurisdiction Coverage</label>
                            <select class="form-select" id="scope_type" name="scope_type">
                                <option value="DISTRICT">Full District (All Saran District)</option>
                                <option value="BLOCK">Specific Block Jurisdiction</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4" id="block_select_wrapper" style="display: none;">
                            <label for="block_id" class="form-label small fw-semibold">Assigned Block</label>
                            <select class="form-select" id="block_id" name="block_id">
                                <option value="">Select Block</option>
                                <?php foreach ($blocks_list as $blk): ?>
                                    <option value="<?php echo $blk['id']; ?>"><?php echo sanitizeInput($blk['block_name']); ?> (<?php echo sanitizeInput($blk['hindi_name']); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Section 3: Designation, Location & About -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-card-heading text-primary me-2"></i>3. Designation, Address & Profile Notes</h6>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="designation" class="form-label small fw-semibold">Designation / Title</label>
                            <input type="text" class="form-control" id="designation" name="designation" placeholder="e.g. Chapra Block Sub-Admin / District Nodal Officer">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="state" class="form-label small fw-semibold">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="Bihar">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="district" class="form-label small fw-semibold">District</label>
                            <input type="text" class="form-control" id="district" name="district" value="Saran">
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label small fw-semibold">Office / Street Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="e.g. Block Office Campus, Marhaura, Saran">
                        </div>
                        <div class="col-12">
                            <label for="about" class="form-label small fw-semibold">About / Responsibilities Note</label>
                            <textarea class="form-control" id="about" name="about" rows="3" placeholder="Enter notes about responsibilities, contact hours, or special administrative permissions..."></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4 rounded-3"><i class="bi bi-check-lg me-1"></i> Appoint Sub-Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scopeSelect = document.getElementById('scope_type');
    const blockWrapper = document.getElementById('block_select_wrapper');

    if (scopeSelect && blockWrapper) {
        scopeSelect.addEventListener('change', function() {
            if (this.value === 'BLOCK') {
                blockWrapper.style.display = 'block';
            } else {
                blockWrapper.style.display = 'none';
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

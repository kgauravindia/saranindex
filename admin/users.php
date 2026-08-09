<?php
require_once __DIR__ . '/includes/auth.php';
checkAdminAuth();
require_once __DIR__ . '/../includes/functions.php';

$msg = '';
$msg_type = 'success';

// Handle action parameters
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $target_id = intval($_GET['id']);

    if ($action === 'login_as') {
        $target_user = getUserById($target_id);
        if ($target_user) {
            $_SESSION['impersonated_by_admin'] = true;
            $_SESSION['admin_backup_id'] = $_SESSION['admin_user_id'] ?? 1;
            $_SESSION['user_id'] = $target_user['id'];
            $_SESSION['user_name'] = $target_user['full_name'];
            $_SESSION['user_mobile'] = $target_user['mobile'];

            header("Location: ../dashboard.php");
            exit;
        }
    } elseif ($action === 'activate') {
        if (updateUserStatus($target_id, 'ACTIVE')) {
            $msg = "User #{$target_id} activated successfully!";
        }
    } elseif ($action === 'suspend') {
        if (updateUserStatus($target_id, 'SUSPENDED')) {
            $msg = "User #{$target_id} suspended.";
            $msg_type = "warning";
        }
    } elseif ($action === 'toggle_mobile_verify') {
        if (toggleUserMobileVerification($target_id)) {
            $msg = "Updated Mobile OTP verification status for user #{$target_id}.";
        }
    } elseif ($action === 'toggle_email_verify') {
        if (toggleUserEmailVerification($target_id)) {
            $msg = "Updated Email verification status for user #{$target_id}.";
        }
    } elseif ($action === 'delete') {
        $admin_user = $_SESSION['admin_username'] ?? 'ADMIN';
        if (deleteUser($target_id, $admin_user)) {
            $msg = "User #{$target_id} deleted and safely archived to deleted_users table.";
            $msg_type = "danger";
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'exit_impersonation') {
    unset($_SESSION['impersonated_by_admin'], $_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_mobile']);
    $msg = "Exited user impersonation mode. Welcome back to Admin Panel.";
    $msg_type = "info";
}

$status_filter = $_GET['status'] ?? null;
$search_query = trim($_GET['search'] ?? '');

$users = getAllAdminUsers($status_filter, $search_query);

$header_title = "Manage User Accounts";
require_once __DIR__ . '/includes/header.php';
?>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Header Actions Bar -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">User Accounts</h4>
        <p class="text-muted small mb-0">View, search, filter, manage Mobile OTP & Email verification status, and bulk import users in Saran district.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="verification_settings.php" class="btn btn-outline-primary fw-bold px-3 py-2 rounded-3 shadow-sm">
            <i class="bi bi-shield-check me-1"></i> OTP & Verification Settings
        </a>
        <a href="users_bulk_upload.php" class="btn btn-outline-success fw-bold px-3 py-2 rounded-3 shadow-sm">
            <i class="bi bi-cloud-upload me-1"></i> Bulk Upload Users
        </a>
    </div>
</div>

<!-- Filters & Search Bar -->
<div class="card border-0 shadow-sm rounded-3 mb-4 p-3">
    <div class="row g-3 align-items-center">
        <!-- Status Filter Nav Pills -->
        <div class="col-12 col-md-7">
            <div class="nav nav-pills small gap-1">
                <a href="users.php" class="nav-link px-3 py-1.5 rounded-pill <?php echo empty($status_filter) ? 'active bg-primary' : 'bg-light text-dark border'; ?>">All Users</a>
                <a href="users.php?status=ACTIVE" class="nav-link px-3 py-1.5 rounded-pill <?php echo $status_filter === 'ACTIVE' ? 'active bg-success' : 'bg-light text-dark border'; ?>">Active Users</a>
                <a href="users.php?status=SUSPENDED" class="nav-link px-3 py-1.5 rounded-pill <?php echo $status_filter === 'SUSPENDED' ? 'active bg-danger' : 'bg-light text-dark border'; ?>">Suspended Users</a>
            </div>
        </div>

        <!-- Search Form -->
        <div class="col-12 col-md-5">
            <form action="users.php" method="GET">
                <?php if ($status_filter): ?>
                    <input type="hidden" name="status" value="<?php echo sanitizeInput($status_filter); ?>">
                <?php endif; ?>
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm bg-light" placeholder="Search by name, mobile, email..." value="<?php echo sanitizeInput($search_query); ?>">
                    <button class="btn btn-primary btn-sm px-3" type="submit"><i class="bi bi-search"></i> Search</button>
                    <?php if ($search_query): ?>
                        <a href="users.php<?php echo $status_filter ? '?status='.$status_filter : ''; ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-circle"></i> Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Users Data Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th style="width: 50px;">#ID</th>
                    <th>User Full Name & Contact</th>
                    <th>Mobile OTP Ver.</th>
                    <th>Email Ver.</th>
                    <th>Business / Designation</th>
                    <th>Block</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                            No users match your filter criteria.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="fw-bold text-muted">#<?php echo $u['id']; ?></td>
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-1 flex-wrap">
                                    <span><?php echo sanitizeInput($u['full_name']); ?></span>
                                    <?php if (!empty($u['username_handle'])): ?>
                                        <span class="badge bg-light text-primary border rounded-pill small fw-normal"><?php echo sanitizeInput($u['username_handle']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="small text-muted">
                                    <i class="bi bi-telephone me-1 text-primary"></i><?php echo sanitizeInput($u['mobile']); ?>
                                    <?php if (!empty($u['email'])): ?>
                                        <span class="ms-2"><i class="bi bi-envelope me-1"></i><?php echo sanitizeInput($u['email']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Mobile OTP Verification Status -->
                            <td>
                                <?php if (($u['mobile_status'] ?? 'UNVERIFIED') === 'VERIFIED'): ?>
                                    <a href="users.php?action=toggle_mobile_verify&id=<?php echo $u['id']; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>" class="badge bg-success text-decoration-none" title="Click to mark Mobile as Unverified">
                                        <i class="bi bi-patch-check-fill me-1"></i>VERIFIED
                                    </a>
                                <?php else: ?>
                                    <a href="users.php?action=toggle_mobile_verify&id=<?php echo $u['id']; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>" class="badge bg-secondary text-decoration-none" title="Click to mark Mobile as Verified">
                                        <i class="bi bi-dash-circle me-1"></i>UNVERIFIED
                                    </a>
                                <?php endif; ?>
                            </td>

                            <!-- Email Verification Status -->
                            <td>
                                <?php if (($u['email_status'] ?? 'UNVERIFIED') === 'VERIFIED'): ?>
                                    <a href="users.php?action=toggle_email_verify&id=<?php echo $u['id']; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>" class="badge bg-success text-decoration-none" title="Click to mark Email as Unverified">
                                        <i class="bi bi-check-circle-fill me-1"></i>VERIFIED
                                    </a>
                                <?php else: ?>
                                    <a href="users.php?action=toggle_email_verify&id=<?php echo $u['id']; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>" class="badge bg-secondary text-decoration-none" title="Click to mark Email as Verified">
                                        <i class="bi bi-dash-circle me-1"></i>UNVERIFIED
                                    </a>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if (!empty($u['business_name'])): ?>
                                    <div class="small fw-semibold text-dark"><?php echo sanitizeInput($u['business_name']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($u['designation'])): ?>
                                    <small class="text-muted"><?php echo sanitizeInput($u['designation']); ?></small>
                                <?php else: ?>
                                    <small class="text-muted">-</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border small">
                                    <i class="bi bi-geo-alt me-1 text-primary"></i><?php echo sanitizeInput($u['block_name'] ?? 'Chapra Sadar'); ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                    $tp = strtoupper($u['type'] ?? 'USER');
                                    if ($tp === 'ADMIN') {
                                        echo '<span class="badge bg-primary">ADMIN</span>';
                                    } elseif ($tp === 'AGENT') {
                                        echo '<span class="badge bg-info text-dark">AGENT</span>';
                                    } else {
                                        echo '<span class="badge bg-light text-dark border">USER</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <?php 
                                    $st = strtoupper($u['status'] ?? 'ACTIVE');
                                    if ($st === 'ACTIVE') {
                                        echo '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1"><i class="bi bi-check-circle me-1"></i>Active</span>';
                                    } else {
                                        echo '<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1"><i class="bi bi-dash-circle me-1"></i>Suspended</span>';
                                    }
                                ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="users.php?action=login_as&id=<?php echo $u['id']; ?>" class="btn btn-outline-primary" onclick="return confirm('Do you want to log in as user <?php echo sanitizeInput(addslashes($u['full_name'])); ?>?');" title="Login As User (Impersonate)">
                                        <i class="bi bi-box-arrow-in-right"></i>
                                    </a>

                                    <a href="user_edit.php?id=<?php echo $u['id']; ?>" class="btn btn-outline-secondary" title="Edit User Profile">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <?php if (strtoupper($u['status'] ?? 'ACTIVE') === 'ACTIVE'): ?>
                                        <a href="users.php?action=suspend&id=<?php echo $u['id']; ?>" class="btn btn-outline-warning" title="Suspend User">
                                            <i class="bi bi-slash-circle"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="users.php?action=activate&id=<?php echo $u['id']; ?>" class="btn btn-outline-success" title="Activate User">
                                            <i class="bi bi-check-circle"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="users.php?action=delete&id=<?php echo $u['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete user #<?php echo $u['id']; ?>? User data will be archived to deleted_users table.');" title="Delete User (Archive to deleted_users)">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

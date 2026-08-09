<?php
require_once __DIR__ . '/../includes/functions.php';

// Check admin authentication
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $claim_id = intval($_POST['claim_id'] ?? 0);
    if ($_POST['action'] === 'approve') {
        if (approveClaim($claim_id)) {
            $success = "Business claim #{$claim_id} approved! Ownership assigned and listing marked as verified.";
        } else {
            $error = "Failed to approve claim #{$claim_id}.";
        }
    } elseif ($_POST['action'] === 'reject') {
        if (rejectClaim($claim_id)) {
            $success = "Business claim #{$claim_id} rejected.";
        } else {
            $error = "Failed to reject claim #{$claim_id}.";
        }
    }
}

$status_filter = sanitizeInput($_GET['status'] ?? '');
$claims = getClaimsList($status_filter);

$page_title = "Business Claims Management";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-shield-lock-fill text-warning me-2"></i>Business Claims Management</h3>
            <p class="text-muted small mb-0">Review and approve ownership claims submitted by business owners and representatives.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="claims.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 <?php echo empty($status_filter) ? 'active' : ''; ?>">All Claims</a>
            <a href="claims.php?status=PENDING" class="btn btn-outline-warning btn-sm rounded-pill px-3 <?php echo $status_filter === 'PENDING' ? 'active' : ''; ?>">Pending</a>
            <a href="claims.php?status=APPROVED" class="btn btn-outline-success btn-sm rounded-pill px-3 <?php echo $status_filter === 'APPROVED' ? 'active' : ''; ?>">Approved</a>
            <a href="claims.php?status=REJECTED" class="btn btn-outline-danger btn-sm rounded-pill px-3 <?php echo $status_filter === 'REJECTED' ? 'active' : ''; ?>">Rejected</a>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success rounded-3 p-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo sanitizeInput($success); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger rounded-3 p-3 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo sanitizeInput($error); ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Claim ID</th>
                            <th>Target Listing</th>
                            <th>Claimant Details</th>
                            <th>Role & Proof Note</th>
                            <th>Claim Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($claims)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>No business claims found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($claims as $cl): ?>
                                <tr>
                                    <td class="fw-bold">#<?php echo $cl['id']; ?></td>
                                    <td>
                                        <a href="../<?php echo getListingUrl($cl['listing_slug']); ?>" target="_blank" class="fw-bold text-dark text-decoration-none">
                                            <?php echo sanitizeInput($cl['listing_title']); ?> <i class="bi bi-box-arrow-up-right small ms-1 text-primary"></i>
                                        </a>
                                        <div class="small text-muted"><?php echo sanitizeInput($cl['category_name']); ?></div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo sanitizeInput($cl['claimant_name']); ?></div>
                                        <div class="small text-muted"><i class="bi bi-telephone me-1"></i><?php echo sanitizeInput($cl['claimant_mobile']); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border me-1"><?php echo sanitizeInput($cl['role_title']); ?></span>
                                        <?php if (!empty($cl['verification_proof'])): ?>
                                            <div class="small text-secondary mt-1 text-truncate" style="max-width: 250px;" title="<?php echo sanitizeInput($cl['verification_proof']); ?>">
                                                "<?php echo sanitizeInput($cl['verification_proof']); ?>"
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small text-muted">
                                        <?php echo date('d M Y, h:i A', strtotime($cl['created_at'])); ?>
                                    </td>
                                    <td>
                                        <?php if ($cl['status'] === 'APPROVED'): ?>
                                            <span class="badge badge-status-active"><i class="bi bi-check-circle-fill me-1"></i>APPROVED</span>
                                        <?php elseif ($cl['status'] === 'PENDING'): ?>
                                            <span class="badge badge-status-pending"><i class="bi bi-clock-history me-1"></i>PENDING</span>
                                        <?php else: ?>
                                            <span class="badge badge-status-rejected"><i class="bi bi-x-circle-fill me-1"></i>REJECTED</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if ($cl['status'] === 'PENDING'): ?>
                                            <form action="claims.php" method="POST" class="d-inline">
                                                <input type="hidden" name="claim_id" value="<?php echo $cl['id']; ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-success btn-sm rounded-pill fw-bold px-3">
                                                    <i class="bi bi-check-lg me-1"></i>Approve
                                                </button>
                                            </form>
                                            <form action="claims.php" method="POST" class="d-inline ms-1">
                                                <input type="hidden" name="claim_id" value="<?php echo $cl['id']; ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill fw-bold px-3" onclick="return confirm('Reject this business claim?')">
                                                    Reject
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">Processed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

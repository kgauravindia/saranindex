<?php
require_once __DIR__ . '/includes/auth.php';
checkAdminAuth();
require_once __DIR__ . '/../includes/functions.php';

$msg = '';
$msg_type = 'success';

// Handle Action parameters
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $target_id = intval($_GET['id']);

    if ($action === 'mark_success') {
        if (updatePaymentStatus($target_id, 'SUCCESS')) {
            $msg = "Payment transaction #{$target_id} marked as SUCCESS and listing subscription activated!";
            $msg_type = "success";
        }
    } elseif ($action === 'mark_failed') {
        if (updatePaymentStatus($target_id, 'FAILED')) {
            $msg = "Payment transaction #{$target_id} marked as FAILED.";
            $msg_type = "warning";
        }
    }
}

$status_filter = $_GET['status'] ?? null;
$search_query = trim($_GET['search'] ?? '');

$payments = getAllAdminPayments($status_filter, $search_query);
$stats = getPaymentSummaryStats();

$header_title = "Online Payments & Billing Transactions";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Admin</a></li>
                <li class="breadcrumb-item active fw-semibold text-primary" aria-current="page">Payments & Revenue</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0 text-dark">Online Payments & Subscription Transactions</h4>
        <p class="text-muted small mb-0">Track online Razorpay transactions, memberships, featured listings, and total platform revenue.</p>
    </div>
</div>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Summary Stat Cards Row -->
<div class="row g-3 mb-4">
    <!-- Total Revenue Card -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-primary text-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="small text-white-50 fw-semibold text-uppercase d-block mb-1">Total Revenue</span>
                    <h3 class="fw-bold mb-0">₹<?php echo number_format($stats['total_revenue'], 2); ?></h3>
                </div>
                <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center p-3">
                    <i class="bi bi-currency-rupee fs-3 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Successful Transactions Card -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="small text-muted fw-semibold text-uppercase d-block mb-1">Successful Payments</span>
                    <h3 class="fw-bold text-success mb-0"><?php echo number_format($stats['successful_count']); ?></h3>
                </div>
                <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center p-3">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gold Plan Revenue Card -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="small text-muted fw-semibold text-uppercase d-block mb-1">Gold Plan Revenue</span>
                    <h3 class="fw-bold text-warning mb-0">₹<?php echo number_format($stats['gold_revenue'], 0); ?></h3>
                </div>
                <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center p-3">
                    <i class="bi bi-star-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Platinum VIP Plan Revenue Card -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="small text-muted fw-semibold text-uppercase d-block mb-1">Platinum VIP Revenue</span>
                    <h3 class="fw-bold text-primary mb-0">₹<?php echo number_format($stats['platinum_revenue'], 0); ?></h3>
                </div>
                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center p-3">
                    <i class="bi bi-gem fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search Bar -->
<div class="card border-0 shadow-sm rounded-3 mb-4 p-3">
    <div class="row g-3 align-items-center">
        <!-- Status Filter Nav Pills -->
        <div class="col-12 col-md-7">
            <div class="nav nav-pills small gap-1">
                <a href="payments.php" class="nav-link px-3 py-1.5 rounded-pill <?php echo empty($status_filter) ? 'active bg-primary' : 'bg-light text-dark border'; ?>">All Transactions</a>
                <a href="payments.php?status=SUCCESS" class="nav-link px-3 py-1.5 rounded-pill <?php echo $status_filter === 'SUCCESS' ? 'active bg-success' : 'bg-light text-dark border'; ?>">Successful</a>
                <a href="payments.php?status=PENDING" class="nav-link px-3 py-1.5 rounded-pill <?php echo $status_filter === 'PENDING' ? 'active bg-warning text-dark' : 'bg-light text-dark border'; ?>">Pending</a>
                <a href="payments.php?status=FAILED" class="nav-link px-3 py-1.5 rounded-pill <?php echo $status_filter === 'FAILED' ? 'active bg-danger' : 'bg-light text-dark border'; ?>">Failed</a>
            </div>
        </div>

        <!-- Search Form -->
        <div class="col-12 col-md-5">
            <form action="payments.php" method="GET">
                <?php if ($status_filter): ?>
                    <input type="hidden" name="status" value="<?php echo sanitizeInput($status_filter); ?>">
                <?php endif; ?>
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm bg-light" placeholder="Search by Txn ID, Razorpay ID, User, Mobile..." value="<?php echo sanitizeInput($search_query); ?>">
                    <button class="btn btn-primary btn-sm px-3" type="submit"><i class="bi bi-search"></i> Search</button>
                    <?php if ($search_query): ?>
                        <a href="payments.php<?php echo $status_filter ? '?status='.$status_filter : ''; ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-circle"></i> Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payments Data Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th style="width: 50px;">#ID</th>
                    <th>Transaction & Payment ID</th>
                    <th>Customer / User</th>
                    <th>Listing Subscription Title</th>
                    <th>Plan</th>
                    <th>Amount Paid</th>
                    <th>Gateway & Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-1 d-block mb-2 text-secondary"></i>
                            No payment transactions recorded matching your filter criteria.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($payments as $p): ?>
                        <tr>
                            <td class="fw-bold text-muted">#<?php echo $p['id']; ?></td>
                            <td>
                                <div class="fw-bold font-monospace text-primary small"><?php echo sanitizeInput($p['transaction_id']); ?></div>
                                <?php if (!empty($p['payment_id'])): ?>
                                    <div class="small text-muted font-monospace"><i class="bi bi-credit-card me-1"></i><?php echo sanitizeInput($p['payment_id']); ?></div>
                                <?php endif; ?>
                            </td>

                            <!-- Customer Info -->
                            <td>
                                <div class="fw-bold text-dark small"><?php echo sanitizeInput($p['user_name'] ?: 'Guest / Customer'); ?></div>
                                <?php if (!empty($p['user_mobile'])): ?>
                                    <div class="small text-muted"><i class="bi bi-telephone me-1"></i><?php echo sanitizeInput($p['user_mobile']); ?></div>
                                <?php endif; ?>
                            </td>

                            <!-- Listing Title -->
                            <td>
                                <?php if (!empty($p['listing_title'])): ?>
                                    <a href="listing_edit.php?id=<?php echo $p['listing_id']; ?>" class="fw-semibold text-decoration-none text-dark small">
                                        <?php echo sanitizeInput($p['listing_title']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="small text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Plan Package -->
                            <td>
                                <?php
                                    $pl = strtoupper($p['plan_type'] ?? 'FREE');
                                    if ($pl === 'PLATINUM') {
                                        echo '<span class="badge bg-primary px-2.5 py-1 rounded-pill"><i class="bi bi-gem me-1"></i>PLATINUM</span>';
                                    } elseif ($pl === 'GOLD') {
                                        echo '<span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill"><i class="bi bi-star-fill me-1"></i>GOLD</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary px-2.5 py-1 rounded-pill">BASIC</span>';
                                    }
                                ?>
                            </td>

                            <!-- Amount -->
                            <td>
                                <span class="fw-bold text-dark">₹<?php echo number_format($p['amount'], 2); ?></span>
                            </td>

                            <!-- Gateway & Date -->
                            <td>
                                <div class="small text-dark font-monospace fw-medium"><i class="bi bi-wallet2 me-1 text-primary"></i><?php echo sanitizeInput($p['payment_gateway'] ?: 'RAZORPAY'); ?></div>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i><?php echo date('M d, Y h:i A', strtotime($p['created_at'])); ?></small>
                            </td>

                            <!-- Status -->
                            <td>
                                <?php
                                    $st = strtoupper($p['payment_status'] ?? 'PENDING');
                                    if ($st === 'SUCCESS') {
                                        echo '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1"><i class="bi bi-check-circle-fill me-1"></i>SUCCESS</span>';
                                    } elseif ($st === 'PENDING') {
                                        echo '<span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1"><i class="bi bi-clock-history me-1"></i>PENDING</span>';
                                    } else {
                                        echo '<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1"><i class="bi bi-x-circle-fill me-1"></i>FAILED</span>';
                                    }
                                ?>
                            </td>

                            <!-- Actions -->
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="../generate_receipt.php?id=<?php echo $p['id']; ?>" target="_blank" class="btn btn-outline-secondary" title="View & Print Tax Invoice / Receipt">
                                        <i class="bi bi-file-earmark-text me-1"></i> Receipt
                                    </a>

                                    <?php if (!empty($p['payment_response'])): ?>
                                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#payModal<?php echo $p['id']; ?>" title="View Gateway Response Payload">
                                            <i class="bi bi-code-square"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if (strtoupper($p['payment_status'] ?? '') !== 'SUCCESS'): ?>
                                        <a href="payments.php?action=mark_success&id=<?php echo $p['id']; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?><?php echo $search_query ? '&search='.urlencode($search_query) : ''; ?>" class="btn btn-outline-success" onclick="return confirm('Mark transaction #<?php echo $p['id']; ?> as SUCCESS and activate listing plan?');" title="Approve & Mark as Success">
                                            <i class="bi bi-check-lg me-1"></i> Approve
                                        </a>
                                    <?php endif; ?>

                                    <?php if (strtoupper($p['payment_status'] ?? '') !== 'FAILED'): ?>
                                        <a href="payments.php?action=mark_failed&id=<?php echo $p['id']; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?><?php echo $search_query ? '&search='.urlencode($search_query) : ''; ?>" class="btn btn-outline-danger" onclick="return confirm('Mark transaction #<?php echo $p['id']; ?> as FAILED / Rejected?');" title="Mark Transaction as Failed">
                                            <i class="bi bi-x-circle me-1"></i> Mark Failed
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>

                        <!-- Gateway Response Modal -->
                        <?php if (!empty($p['payment_response'])): ?>
                            <div class="modal fade" id="payModal<?php echo $p['id']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-3">
                                        <div class="modal-header bg-dark text-white py-3">
                                            <h6 class="modal-title fw-bold font-monospace"><i class="bi bi-code-slash me-2 text-warning"></i>Razorpay Gateway Response Payload (#<?php echo $p['id']; ?>)</h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 bg-dark text-success font-monospace small overflow-x-auto">
                                            <pre class="mb-0 text-white"><?php echo sanitizeInput($p['payment_response']); ?></pre>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary btn-sm rounded-3" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

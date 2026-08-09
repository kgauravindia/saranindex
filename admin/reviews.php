<?php
$header_title = "Review & Rating Moderation";
require_once __DIR__ . '/includes/header.php';

$msg = '';
$msg_type = 'success';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $rev_id = intval($_GET['id']);

    if ($action === 'approve') {
        if (approveReview($rev_id)) {
            $msg = "Review #{$rev_id} approved and published!";
        } else {
            $msg = "Failed to approve review.";
            $msg_type = "danger";
        }
    } elseif ($action === 'reject') {
        if (rejectReview($rev_id)) {
            $msg = "Review #{$rev_id} marked as rejected.";
            $msg_type = "warning";
        }
    } elseif ($action === 'delete') {
        if (deleteReview($rev_id)) {
            $msg = "Review #{$rev_id} deleted permanently.";
            $msg_type = "danger";
        }
    }
}

$status_filter = sanitizeInput($_GET['status'] ?? '');
$reviews = getAllAdminReviews($status_filter);
?>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-star-half text-warning me-2"></i>User Reviews & Ratings Moderation</h4>
        <p class="text-muted small mb-0">View all customer ratings, feedback, reviewer details, and control approval status across Saran Directory.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="reviews.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 <?php echo empty($status_filter) ? 'active' : ''; ?>">All Reviews</a>
        <a href="reviews.php?status=APPROVED" class="btn btn-outline-success btn-sm rounded-pill px-3 <?php echo $status_filter === 'APPROVED' ? 'active' : ''; ?>">Approved</a>
        <a href="reviews.php?status=PENDING" class="btn btn-outline-warning btn-sm rounded-pill px-3 <?php echo $status_filter === 'PENDING' ? 'active' : ''; ?>">Pending</a>
        <a href="reviews.php?status=REJECTED" class="btn btn-outline-danger btn-sm rounded-pill px-3 <?php echo $status_filter === 'REJECTED' ? 'active' : ''; ?>">Rejected</a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 70px;">#ID</th>
                        <th>Directory Listing</th>
                        <th>Reviewer Details</th>
                        <th>Rating</th>
                        <th>User Experience / Comment</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reviews)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-star fs-1 d-block mb-2 text-secondary"></i>
                                No reviews found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reviews as $rev): ?>
                            <tr>
                                <td class="fw-bold text-muted">#<?php echo $rev['id']; ?></td>
                                <td>
                                    <?php if (!empty($rev['listing_slug'])): ?>
                                        <a href="../<?php echo getListingUrl($rev['listing_slug']); ?>" target="_blank" class="fw-bold text-dark text-decoration-none">
                                            <?php echo sanitizeInput($rev['listing_title']); ?> <i class="bi bi-box-arrow-up-right small ms-1 text-primary"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="fw-bold text-dark"><?php echo sanitizeInput($rev['listing_title'] ?? 'Listing #' . $rev['listing_id']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo sanitizeInput($rev['reviewer_name']); ?></div>
                                    <?php if (!empty($rev['user_id'])): ?>
                                        <small class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle me-1">
                                            <i class="bi bi-person-fill me-1"></i>User #<?php echo $rev['user_id']; ?>
                                        </small>
                                    <?php endif; ?>
                                    <small class="text-muted d-block"><i class="bi bi-telephone me-1"></i><?php echo sanitizeInput($rev['reviewer_mobile'] ?: ($rev['user_registered_mobile'] ?: 'N/A')); ?></small>
                                </td>
                                <td>
                                    <div class="text-nowrap"><?php echo renderStarRating($rev['rating']); ?></div>
                                </td>
                                <td class="small text-secondary" style="max-width: 300px;">
                                    "<?php echo nl2br(sanitizeInput($rev['comment'])); ?>"
                                </td>
                                <td class="small text-muted text-nowrap">
                                    <?php echo !empty($rev['created_at']) ? date('d M Y, h:i A', strtotime($rev['created_at'])) : 'N/A'; ?>
                                </td>
                                <td>
                                    <?php if (($rev['status'] ?? 'APPROVED') === 'APPROVED'): ?>
                                        <span class="badge badge-status-active"><i class="bi bi-check-circle-fill me-1"></i>APPROVED</span>
                                    <?php elseif ($rev['status'] === 'REJECTED'): ?>
                                        <span class="badge badge-status-rejected"><i class="bi bi-x-circle-fill me-1"></i>REJECTED</span>
                                    <?php else: ?>
                                        <span class="badge badge-status-pending"><i class="bi bi-clock-history me-1"></i>PENDING</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end text-nowrap">
                                    <?php if (($rev['status'] ?? 'APPROVED') !== 'APPROVED'): ?>
                                        <a href="reviews.php?action=approve&id=<?php echo $rev['id']; ?>" class="btn btn-success btn-sm rounded-pill px-3 me-1 fw-bold" title="Approve">
                                            <i class="bi bi-check-lg me-1"></i>Approve
                                        </a>
                                    <?php endif; ?>
                                    <?php if (($rev['status'] ?? 'APPROVED') !== 'REJECTED'): ?>
                                        <a href="reviews.php?action=reject&id=<?php echo $rev['id']; ?>" class="btn btn-outline-warning btn-sm rounded-pill px-3 me-1" title="Reject">
                                            Reject
                                        </a>
                                    <?php endif; ?>
                                    <a href="reviews.php?action=delete&id=<?php echo $rev['id']; ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Delete this review permanently?');" title="Delete">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

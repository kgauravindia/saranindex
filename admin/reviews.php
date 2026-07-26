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
            $msg = "Review #{$rev_id} approved!";
        }
    } elseif ($action === 'delete') {
        if (deleteReview($rev_id)) {
            $msg = "Review #{$rev_id} removed.";
            $msg_type = "danger";
        }
    }
}

$reviews = getAllAdminReviews();
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
        <h4 class="fw-bold mb-1">User Reviews & Ratings</h4>
        <p class="text-muted small mb-0">Moderate ratings and customer comments for directory entries.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#ID</th>
                    <th>Listing Name</th>
                    <th>Reviewer</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-star fs-1 d-block mb-2 text-secondary"></i>
                            No user reviews submitted yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reviews as $rev): ?>
                        <tr>
                            <td class="fw-bold text-muted">#<?php echo $rev['id']; ?></td>
                            <td class="fw-bold text-dark"><?php echo sanitizeInput($rev['listing_title'] ?? 'Listing #' . $rev['listing_id']); ?></td>
                            <td>
                                <div class="fw-semibold text-dark mb-0"><?php echo sanitizeInput($rev['reviewer_name']); ?></div>
                                <small class="text-muted"><?php echo sanitizeInput($rev['reviewer_mobile'] ?? ''); ?></small>
                            </td>
                            <td>
                                <?php echo renderStarRating($rev['rating']); ?>
                            </td>
                            <td class="small text-secondary" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                "<?php echo sanitizeInput($rev['comment']); ?>"
                            </td>
                            <td>
                                <?php if (($rev['status'] ?? 'APPROVED') === 'APPROVED'): ?>
                                    <span class="badge bg-success bg-opacity-15 text-success font-weight-semibold">Approved</span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-opacity-15 text-warning font-weight-semibold">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if (($rev['status'] ?? 'APPROVED') === 'PENDING'): ?>
                                    <a href="reviews.php?action=approve&id=<?php echo $rev['id']; ?>" class="btn btn-success btn-sm me-1" title="Approve">
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="reviews.php?action=delete&id=<?php echo $rev['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this review?');" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

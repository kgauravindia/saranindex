<?php
$header_title = "Dashboard Overview";
require_once __DIR__ . '/includes/header.php';

// Handle quick inline action requests (Approve, Reject, Toggle Verified)
$msg = '';
$msg_type = 'success';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $target_id = intval($_GET['id']);

    if ($action === 'approve') {
        $reason = '';
        if (!isListingUserMobileActive($target_id, $reason)) {
            $msg = "Cannot approve Listing #{$target_id}: " . $reason;
            $msg_type = "danger";
        } elseif (updateListingStatus($target_id, 'ACTIVE')) {
            $msg = "Listing #{$target_id} approved and published successfully!";
        } else {
            $msg = "Failed to update listing status.";
            $msg_type = "danger";
        }
    } elseif ($action === 'reject') {
        if (updateListingStatus($target_id, 'REJECTED')) {
            $msg = "Listing #{$target_id} has been marked as rejected.";
            $msg_type = "warning";
        }
    } elseif ($action === 'toggle_verified') {
        if (toggleListingVerified($target_id)) {
            $msg = "Updated verification status for listing #{$target_id}.";
        }
    } elseif ($action === 'delete') {
        if (deleteListing($target_id)) {
            $msg = "Listing #{$target_id} deleted successfully.";
            $msg_type = "danger";
        }
    }
}

$stats = getAdminStats();
$pending_claims_count = count(getClaimsList('PENDING'));
$recentListings = getAllAdminListings(null, null);
// Limit to top 8 recent listings for dashboard summary
$recentListings = array_slice($recentListings, 0, 8);
?>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Welcome & Overview Banner -->
<div class="card border-0 bg-primary text-white rounded-3 p-4 mb-4 shadow-sm" style="background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
        <div class="mb-3 mb-md-0">
            <h4 class="fw-bold mb-1">Welcome back, <?php echo sanitizeInput($_SESSION['admin_full_name'] ?? 'Administrator'); ?> 👋</h4>
            <p class="mb-0 text-white-50 small">Saran District Digital Directory Command Center. Manage listings, verifications, and user requests.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="bulk_upload.php" class="btn btn-warning text-dark fw-bold btn-sm px-3 shadow-sm">
                <i class="bi bi-cloud-upload me-1"></i> Bulk Upload CSV
            </a>
            <a href="listing_edit.php" class="btn btn-light text-primary fw-bold btn-sm px-3 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Listing
            </a>
            <a href="categories.php" class="btn btn-outline-light btn-sm px-3 fw-medium">
                <i class="bi bi-folder-plus me-1"></i> Add Category
            </a>
        </div>
    </div>
</div>

<!-- Metrics & Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Total Listings</div>
                    <h3 class="fw-bold text-dark my-1"><?php echo number_format($stats['total_listings']); ?></h3>
                    <small class="text-success fw-medium"><i class="bi bi-check-circle me-1"></i>All Directory Entities</small>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-collection"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Pending Approvals</div>
                    <h3 class="fw-bold text-warning my-1"><?php echo number_format($stats['pending_listings']); ?></h3>
                    <small class="text-muted">Requires moderator review</small>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <a href="claims.php?status=PENDING" class="text-decoration-none">
            <div class="stat-card p-3 border border-warning">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Pending Claims</div>
                        <h3 class="fw-bold text-warning my-1"><?php echo number_format($pending_claims_count); ?></h3>
                        <small class="text-warning fw-medium"><i class="bi bi-shield-lock me-1"></i>Business claims waiting</small>
                    </div>
                    <div class="stat-icon bg-warning text-dark">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Verified Entities</div>
                    <h3 class="fw-bold text-success my-1"><?php echo number_format($stats['verified_listings']); ?></h3>
                    <small class="text-success fw-medium"><i class="bi bi-shield-check me-1"></i>Verified badge active</small>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-patch-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Categories & Blocks</div>
                    <h3 class="fw-bold text-dark my-1"><?php echo number_format($stats['total_categories']); ?> <span class="fs-6 text-muted font-weight-normal">cats / 20 blocks</span></h3>
                    <small class="text-muted">District wide coverage</small>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-grid-3x3-gap"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Listings Section -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Directory Submissions</h6>
            <small class="text-muted">Listings added or submitted by community users</small>
        </div>
        <a href="listings.php" class="btn btn-outline-primary btn-sm fw-semibold rounded-pill px-3">
            View All Listings <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th>Title & Category</th>
                    <th>Block / Contact</th>
                    <th>Status</th>
                    <th>Verified</th>
                    <th>Featured</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentListings)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No listings found in the system.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentListings as $item): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark"><?php echo sanitizeInput($item['title']); ?></div>
                                <?php if (!empty($item['hindi_title'])): ?>
                                    <small class="text-muted me-2"><?php echo sanitizeInput($item['hindi_title']); ?></small>
                                <?php endif; ?>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    <span class="badge bg-light text-secondary border small" title="Category ID: #<?php echo $item['category_id']; ?>">
                                        <i class="bi bi-folder me-1"></i>Cat #<?php echo $item['category_id']; ?>: <?php echo sanitizeInput($item['category_name'] ?? 'General'); ?>
                                    </span>
                                    <?php if (!empty($item['subcategory_name'])): ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle small" title="Subcategory ID: #<?php echo $item['subcategory_id']; ?>">
                                            <i class="bi bi-diagram-3 me-1"></i>Sub #<?php echo $item['subcategory_id']; ?>: <?php echo sanitizeInput($item['subcategory_name']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark"><i class="bi bi-geo-alt text-primary me-1"></i><?php echo sanitizeInput($item['block_name'] ?? 'Chapra Sadar'); ?></div>
                                <div class="small text-muted"><i class="bi bi-telephone me-1"></i><?php echo sanitizeInput($item['mobile']); ?></div>
                            </td>
                            <td>
                                <?php 
                                    $st = strtoupper($item['status'] ?? 'ACTIVE');
                                    if ($st === 'ACTIVE') {
                                        echo '<span class="badge badge-status-active"><i class="bi bi-check-circle me-1"></i>Active</span>';
                                    } elseif ($st === 'PENDING') {
                                        echo '<span class="badge badge-status-pending"><i class="bi bi-clock me-1"></i>Pending</span>';
                                    } else {
                                        echo '<span class="badge badge-status-rejected"><i class="bi bi-x-circle me-1"></i>Rejected</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <?php if (($item['is_verified'] ?? 'NO') === 'YES'): ?>
                                    <a href="index.php?action=toggle_verified&id=<?php echo $item['id']; ?>" class="badge bg-success text-decoration-none" title="Click to unverify"><i class="bi bi-patch-check-fill me-1"></i>YES</a>
                                <?php else: ?>
                                    <a href="index.php?action=toggle_verified&id=<?php echo $item['id']; ?>" class="badge bg-secondary text-decoration-none" title="Click to verify"><i class="bi bi-dash-circle me-1"></i>NO</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (($item['is_featured'] ?? 'NO') === 'YES'): ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Featured</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border">Normal</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <?php if (strtoupper($item['status'] ?? 'ACTIVE') === 'PENDING'): ?>
                                        <a href="index.php?action=approve&id=<?php echo $item['id']; ?>" class="btn btn-success" title="Approve Listing">
                                            <i class="bi bi-check-lg"></i>
                                        </a>
                                        <a href="index.php?action=reject&id=<?php echo $item['id']; ?>" class="btn btn-outline-warning" title="Reject Listing">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="listing_edit.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="../profile.php?slug=<?php echo $item['slug']; ?>" target="_blank" class="btn btn-outline-info" title="View Public Profile">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="index.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this listing?');" title="Delete">
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

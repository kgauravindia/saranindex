<?php
$header_title = "Manage Directory Listings";
require_once __DIR__ . '/includes/header.php';

$msg = '';
$msg_type = 'success';

// Handle action parameters
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $target_id = intval($_GET['id']);

    if ($action === 'approve') {
        if (updateListingStatus($target_id, 'ACTIVE')) {
            $msg = "Listing #{$target_id} approved successfully!";
        }
    } elseif ($action === 'reject') {
        if (updateListingStatus($target_id, 'REJECTED')) {
            $msg = "Listing #{$target_id} rejected.";
            $msg_type = "warning";
        }
    } elseif ($action === 'toggle_verified') {
        if (toggleListingVerified($target_id)) {
            $msg = "Toggled verification status for listing #{$target_id}.";
        }
    } elseif ($action === 'toggle_featured') {
        if (toggleListingFeatured($target_id)) {
            $msg = "Toggled featured status for listing #{$target_id}.";
        }
    } elseif ($action === 'delete') {
        if (deleteListing($target_id)) {
            $msg = "Listing #{$target_id} deleted successfully.";
            $msg_type = "danger";
        }
    }
}

$status_filter = $_GET['status'] ?? null;
$search_query = trim($_GET['search'] ?? '');

$listings = getAllAdminListings($status_filter, $search_query);
?>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Header Actions Bar -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Directory Listings</h4>
        <p class="text-muted small mb-0">View, search, filter, approve, and edit all listings in Saran district.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="bulk_upload.php" class="btn btn-outline-success fw-bold px-3 py-2 rounded-3 shadow-sm">
            <i class="bi bi-cloud-upload me-1"></i> Bulk Upload CSV
        </a>
        <a href="listing_edit.php" class="btn btn-primary fw-bold px-3 py-2 rounded-3 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add New Listing
        </a>
    </div>
</div>

<!-- Filters & Search Bar -->
<div class="card border-0 shadow-sm rounded-3 mb-4 p-3">
    <div class="row g-3 align-items-center">
        <!-- Status Filter Nav Pills -->
        <div class="col-12 col-md-7">
            <div class="nav nav-pills small gap-1">
                <a href="listings.php" class="nav-link px-3 py-1.5 rounded-pill <?php echo empty($status_filter) ? 'active bg-primary' : 'bg-light text-dark border'; ?>">All</a>
                <a href="listings.php?status=ACTIVE" class="nav-link px-3 py-1.5 rounded-pill <?php echo $status_filter === 'ACTIVE' ? 'active bg-success' : 'bg-light text-dark border'; ?>">Active</a>
                <a href="listings.php?status=PENDING" class="nav-link px-3 py-1.5 rounded-pill <?php echo $status_filter === 'PENDING' ? 'active bg-warning text-dark' : 'bg-light text-dark border'; ?>">Pending Approvals</a>
                <a href="listings.php?status=REJECTED" class="nav-link px-3 py-1.5 rounded-pill <?php echo $status_filter === 'REJECTED' ? 'active bg-danger' : 'bg-light text-dark border'; ?>">Rejected</a>
            </div>
        </div>

        <!-- Search Form -->
        <div class="col-12 col-md-5">
            <form action="listings.php" method="GET">
                <?php if ($status_filter): ?>
                    <input type="hidden" name="status" value="<?php echo sanitizeInput($status_filter); ?>">
                <?php endif; ?>
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm bg-light" placeholder="Search by title, mobile, contact person..." value="<?php echo sanitizeInput($search_query); ?>">
                    <button class="btn btn-primary btn-sm px-3" type="submit"><i class="bi bi-search"></i> Search</button>
                    <?php if ($search_query): ?>
                        <a href="listings.php<?php echo $status_filter ? '?status='.$status_filter : ''; ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-circle"></i> Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Listings Data Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#ID</th>
                    <th>Title & Category</th>
                    <th>Block & Contact</th>
                    <th>Status</th>
                    <th>Verified</th>
                    <th>Featured</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($listings)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-1 d-block mb-2 text-secondary"></i>
                            No listings match your query.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($listings as $item): ?>
                        <tr>
                            <td class="fw-bold text-muted">#<?php echo $item['id']; ?></td>
                            <td>
                                <div class="fw-bold text-dark mb-0.5">
                                    <a href="../profile.php?slug=<?php echo $item['slug']; ?>" target="_blank" class="text-dark text-decoration-none hover-primary">
                                        <?php echo sanitizeInput($item['title']); ?>
                                    </a>
                                </div>
                                <?php if (!empty($item['hindi_title'])): ?>
                                    <div class="small text-muted mb-1"><?php echo sanitizeInput($item['hindi_title']); ?></div>
                                <?php endif; ?>
                                <span class="badge bg-light text-secondary border small"><?php echo sanitizeInput($item['category_name'] ?? 'General'); ?></span>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark"><i class="bi bi-geo-alt text-primary me-1"></i><?php echo sanitizeInput($item['block_name'] ?? 'Chapra Sadar'); ?></div>
                                <div class="small text-muted"><i class="bi bi-telephone me-1"></i><?php echo sanitizeInput($item['mobile']); ?></div>
                                <?php if (!empty($item['contact_person'])): ?>
                                    <div class="small text-muted"><i class="bi bi-person me-1"></i><?php echo sanitizeInput($item['contact_person']); ?></div>
                                <?php endif; ?>
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
                                    <a href="listings.php?action=toggle_verified&id=<?php echo $item['id']; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>" class="badge bg-success text-decoration-none" title="Toggle verification"><i class="bi bi-patch-check-fill me-1"></i>YES</a>
                                <?php else: ?>
                                    <a href="listings.php?action=toggle_verified&id=<?php echo $item['id']; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>" class="badge bg-secondary text-decoration-none" title="Toggle verification"><i class="bi bi-dash-circle me-1"></i>NO</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (($item['is_featured'] ?? 'NO') === 'YES'): ?>
                                    <a href="listings.php?action=toggle_featured&id=<?php echo $item['id']; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>" class="badge bg-warning text-dark text-decoration-none" title="Toggle featured"><i class="bi bi-star-fill me-1"></i>Featured</a>
                                <?php else: ?>
                                    <a href="listings.php?action=toggle_featured&id=<?php echo $item['id']; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>" class="badge bg-light text-muted border text-decoration-none" title="Toggle featured">Normal</a>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <?php if (strtoupper($item['status'] ?? 'ACTIVE') === 'PENDING'): ?>
                                        <a href="listings.php?action=approve&id=<?php echo $item['id']; ?>" class="btn btn-success" title="Approve Listing">
                                            <i class="bi bi-check-lg"></i>
                                        </a>
                                        <a href="listings.php?action=reject&id=<?php echo $item['id']; ?>" class="btn btn-outline-warning" title="Reject Listing">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="listing_edit.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="../profile.php?slug=<?php echo $item['slug']; ?>" target="_blank" class="btn btn-outline-info" title="View Profile">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="listings.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this listing?');" title="Delete">
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

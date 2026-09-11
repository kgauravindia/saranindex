<?php
$header_title = "Manage Blog Articles & Guides";
require_once __DIR__ . '/includes/header.php';

$msg = '';
$msg_type = 'success';
$db = getDB();

// Handle Actions (Delete, Status Toggle)
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0 && $db) {
        if ($action === 'delete') {
            try {
                $stmt = $db->prepare("DELETE FROM blogs WHERE id = ?");
                $stmt->execute([$id]);
                $msg = "Blog article #{$id} deleted successfully!";
                $msg_type = 'danger';
            } catch (Exception $e) {
                $msg = "Could not delete article: " . $e->getMessage();
                $msg_type = 'danger';
            }
        } elseif ($action === 'toggle_status') {
            try {
                $current = $db->prepare("SELECT status FROM blogs WHERE id = ?");
                $current->execute([$id]);
                $cur_status = $current->fetchColumn();
                $new_status = ($cur_status === 'PUBLISHED') ? 'DRAFT' : 'PUBLISHED';
                
                $stmt = $db->prepare("UPDATE blogs SET status = ? WHERE id = ?");
                $stmt->execute([$new_status, $id]);
                $msg = "Article #{$id} status changed to {$new_status}!";
            } catch (Exception $e) {
                $msg = "Error updating status.";
                $msg_type = 'danger';
            }
        }
    }
}

// Search & Filter
$search = sanitizeInput($_GET['q'] ?? '');
$filter_status = sanitizeInput($_GET['status'] ?? '');
$filter_cat = sanitizeInput($_GET['category'] ?? '');

$where = ["1=1"];
$params = [];

if (!empty($search)) {
    $where[] = "(title LIKE ? OR title_hindi LIKE ? OR summary LIKE ? OR author LIKE ?)";
    $like = "%{$search}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if (!empty($filter_status)) {
    $where[] = "status = ?";
    $params[] = $filter_status;
}

if (!empty($filter_cat)) {
    $where[] = "category = ?";
    $params[] = $filter_cat;
}

$where_sql = implode(' AND ', $where);

$blogs = [];
$total_count = 0;
$published_count = 0;
$draft_count = 0;

if ($db) {
    try {
        $total_count = $db->query("SELECT COUNT(*) FROM blogs")->fetchColumn();
        $published_count = $db->query("SELECT COUNT(*) FROM blogs WHERE status = 'PUBLISHED'")->fetchColumn();
        $draft_count = $db->query("SELECT COUNT(*) FROM blogs WHERE status = 'DRAFT'")->fetchColumn();

        $stmt = $db->prepare("SELECT * FROM blogs WHERE {$where_sql} ORDER BY id DESC");
        $stmt->execute($params);
        $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $blogs = [];
    }
}
?>

<div class="container-fluid py-4">
    <!-- Top Action Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="bi bi-journal-richtext text-primary me-2"></i>Blog & Article Management
            </h4>
            <p class="text-muted small mb-0">Create, edit, publish, and manage educational articles, district news, and local guides.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="../blog/" target="_blank" class="btn btn-outline-secondary rounded-pill btn-sm px-3 fw-semibold">
                <i class="bi bi-box-arrow-up-right me-1"></i> View Public Blog
            </a>
            <a href="blog_edit.php" class="btn btn-primary rounded-pill btn-sm px-4 fw-bold shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add New Blog Post
            </a>
        </div>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show rounded-3 shadow-xs" role="alert">
            <i class="bi <?php echo ($msg_type === 'success') ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'; ?> me-2"></i>
            <?php echo htmlspecialchars($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Total Articles</span>
                        <h3 class="fw-bold mb-0 text-dark"><?php echo intval($total_count); ?></h3>
                    </div>
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Published Live</span>
                        <h3 class="fw-bold mb-0 text-success"><?php echo intval($published_count); ?></h3>
                    </div>
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Drafts & Scheduled</span>
                        <h3 class="fw-bold mb-0 text-warning"><?php echo intval($draft_count); ?></h3>
                    </div>
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-6 col-lg-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control form-control-sm border-start-0" placeholder="Search by title, author, keyword..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            <div class="col-md-3 col-lg-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="PUBLISHED" <?php echo ($filter_status === 'PUBLISHED') ? 'selected' : ''; ?>>Published</option>
                    <option value="DRAFT" <?php echo ($filter_status === 'DRAFT') ? 'selected' : ''; ?>>Draft</option>
                    <option value="ARCHIVED" <?php echo ($filter_status === 'ARCHIVED') ? 'selected' : ''; ?>>Archived</option>
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100 fw-semibold">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
            </div>
            <?php if (!empty($search) || !empty($filter_status) || !empty($filter_cat)): ?>
                <div class="col-md-12 col-lg-2 text-end">
                    <a href="blogs.php" class="btn btn-outline-secondary btn-sm rounded-pill w-100">
                        <i class="bi bi-x-circle me-1"></i> Reset
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Blogs Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Article Details</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end" style="width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($blogs)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No blog articles found matching your criteria.
                                <div class="mt-2">
                                    <a href="blog_edit.php" class="btn btn-sm btn-primary rounded-pill px-3">Create First Blog Post</a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($blogs as $b): ?>
                            <tr>
                                <td class="fw-bold text-muted">#<?php echo $b['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 bg-light text-primary p-2 me-3 fs-5 flex-shrink-0" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi <?php echo htmlspecialchars($b['icon'] ?? 'bi-file-text'); ?>"></i>
                                        </div>
                                        <div>
                                            <a href="blog_edit.php?id=<?php echo $b['id']; ?>" class="fw-bold text-dark text-decoration-none hover-text-primary d-block">
                                                <?php echo htmlspecialchars($b['title']); ?>
                                            </a>
                                            <?php if (!empty($b['title_hindi'])): ?>
                                                <small class="text-muted d-block"><?php echo htmlspecialchars($b['title_hindi']); ?></small>
                                            <?php endif; ?>
                                            <small class="text-muted">Slug: <code><?php echo htmlspecialchars($b['slug']); ?></code></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge <?php echo htmlspecialchars($b['badge_class'] ?? 'bg-primary'); ?> rounded-pill px-2.5 py-1">
                                        <?php echo htmlspecialchars($b['category'] ?? 'General'); ?>
                                    </span>
                                </td>
                                <td class="small text-secondary fw-semibold">
                                    <?php echo htmlspecialchars($b['author'] ?? 'Admin'); ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-eye me-1"></i><?php echo intval($b['views']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="blogs.php?action=toggle_status&id=<?php echo $b['id']; ?>" class="badge rounded-pill text-decoration-none <?php echo ($b['status'] === 'PUBLISHED') ? 'badge-status-active' : 'badge-status-pending'; ?> px-2.5 py-1.5" title="Click to toggle status">
                                        <?php echo htmlspecialchars($b['status']); ?>
                                    </a>
                                </td>
                                <td class="small text-muted">
                                    <?php echo date('M d, Y', strtotime($b['published_at'] ?? $b['created_at'])); ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="../blog/post.php?slug=<?php echo urlencode($b['slug']); ?>" target="_blank" class="btn btn-light border text-primary" title="View Public Post">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                        <a href="blog_edit.php?id=<?php echo $b['id']; ?>" class="btn btn-light border text-dark" title="Edit Article">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="blogs.php?action=delete&id=<?php echo $b['id']; ?>" onclick="return confirm('Are you sure you want to delete this blog post?');" class="btn btn-light border text-danger" title="Delete Article">
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
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

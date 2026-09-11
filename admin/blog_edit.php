<?php
$editing_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$header_title = $editing_id ? "Edit Blog Article #{$editing_id}" : "Add New Blog Article";
require_once __DIR__ . '/includes/header.php';

$error = '';
$success = '';
$db = getDB();

$blog = [
    'title' => '',
    'title_hindi' => '',
    'slug' => '',
    'category' => 'General',
    'category_hindi' => '',
    'summary' => '',
    'summary_hindi' => '',
    'content' => '',
    'content_hindi' => '',
    'author' => 'Saran Index Editorial',
    'featured_image' => '',
    'icon' => 'bi-journal-text',
    'badge_class' => 'bg-primary',
    'read_time' => '5 min read',
    'status' => 'PUBLISHED'
];

if ($editing_id && $db) {
    try {
        $stmt = $db->prepare("SELECT * FROM blogs WHERE id = ?");
        $stmt->execute([$editing_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existing) {
            $blog = array_merge($blog, $existing);
        } else {
            $error = "Blog article #{$editing_id} not found.";
        }
    } catch (Exception $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title'] ?? '');
    $title_hindi = sanitizeInput($_POST['title_hindi'] ?? '');
    $slug = sanitizeInput($_POST['slug'] ?? '');
    $category = sanitizeInput($_POST['category'] ?? 'General');
    $category_hindi = sanitizeInput($_POST['category_hindi'] ?? '');
    $summary = sanitizeInput($_POST['summary'] ?? '');
    $summary_hindi = sanitizeInput($_POST['summary_hindi'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $content_hindi = trim($_POST['content_hindi'] ?? '');
    $author = sanitizeInput($_POST['author'] ?? 'Saran Index Editorial');
    $icon = sanitizeInput($_POST['icon'] ?? 'bi-journal-text');
    $badge_class = sanitizeInput($_POST['badge_class'] ?? 'bg-primary');
    $read_time = sanitizeInput($_POST['read_time'] ?? '5 min read');
    $status = sanitizeInput($_POST['status'] ?? 'PUBLISHED');
    $featured_image = sanitizeInput($_POST['featured_image'] ?? '');

    // Auto-generate slug if empty
    if (empty($slug) && !empty($title)) {
        $slug = slugify($title);
    }

    if (empty($title)) {
        $error = "Article Title is required.";
    } elseif ($db) {
        try {
            // Check for duplicate slug
            $checkStmt = $db->prepare("SELECT id FROM blogs WHERE slug = ? AND id != ?");
            $checkStmt->execute([$slug, $editing_id ?: 0]);
            if ($checkStmt->fetch()) {
                $slug = $slug . '-' . time();
            }

            if ($editing_id) {
                // Update
                $stmt = $db->prepare("
                    UPDATE blogs SET 
                        title = ?, title_hindi = ?, slug = ?, category = ?, category_hindi = ?,
                        summary = ?, summary_hindi = ?, content = ?, content_hindi = ?,
                        author = ?, icon = ?, badge_class = ?, read_time = ?, status = ?,
                        featured_image = ?, updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([
                    $title, $title_hindi, $slug, $category, $category_hindi,
                    $summary, $summary_hindi, $content, $content_hindi,
                    $author, $icon, $badge_class, $read_time, $status,
                    $featured_image, $editing_id
                ]);
                $success = "Blog article #{$editing_id} updated successfully!";
            } else {
                // Insert
                $stmt = $db->prepare("
                    INSERT INTO blogs 
                        (title, title_hindi, slug, category, category_hindi, summary, summary_hindi, content, content_hindi, author, icon, badge_class, read_time, status, featured_image, published_at, created_at)
                    VALUES 
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                ");
                $stmt->execute([
                    $title, $title_hindi, $slug, $category, $category_hindi,
                    $summary, $summary_hindi, $content, $content_hindi,
                    $author, $icon, $badge_class, $read_time, $status,
                    $featured_image
                ]);
                $editing_id = $db->lastInsertId();
                $success = "New blog article published successfully! (ID #{$editing_id})";
            }

            // Reload updated data
            $stmt = $db->prepare("SELECT * FROM blogs WHERE id = ?");
            $stmt->execute([$editing_id]);
            $blog = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            $error = "Database error while saving: " . $e->getMessage();
        }
    }
}
?>

<div class="container-fluid py-4">
    <!-- Header bar -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="blogs.php" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-bold mb-0 text-dark">
                    <?php echo $editing_id ? "Edit Blog Article #{$editing_id}" : "Write New Blog Article"; ?>
                </h4>
            </div>
            <p class="text-muted small mb-0 ms-4 ps-2">Author and publish articles for the Saran Index digital blog directory.</p>
        </div>
        <div class="d-flex gap-2">
            <?php if ($editing_id && !empty($blog['slug'])): ?>
                <a href="../blog/post.php?slug=<?php echo urlencode($blog['slug']); ?>" target="_blank" class="btn btn-outline-primary rounded-pill btn-sm px-3 fw-semibold">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Preview Live Post
                </a>
            <?php endif; ?>
            <a href="blogs.php" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
                <i class="bi bi-list me-1"></i> Back to Blog List
            </a>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" class="row g-4">
        <!-- Main Form Left Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Article Information
                </h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Article Title (English) <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. Top 10 Historical Places in Saran" value="<?php echo htmlspecialchars($blog['title'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Article Title (Hindi / हिंदी शीर्षक)</label>
                    <input type="text" name="title_hindi" class="form-control" placeholder="e.g. सारण के 10 प्रमुख ऐतिहासिक स्थल" value="<?php echo htmlspecialchars($blog['title_hindi'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">URL Slug (SEO-friendly)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light small text-muted">/blog/</span>
                        <input type="text" name="slug" class="form-control" placeholder="top-10-historical-places-saran" value="<?php echo htmlspecialchars($blog['slug'] ?? ''); ?>">
                    </div>
                    <small class="text-muted">Leave blank to auto-generate from the title.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Short Summary / Excerpt (English)</label>
                    <textarea name="summary" class="form-control" rows="3" placeholder="Brief summary of the article (displayed on listing cards and meta tags)..."><?php echo htmlspecialchars($blog['summary'] ?? ''); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Short Summary / Excerpt (Hindi / सारांश)</label>
                    <textarea name="summary_hindi" class="form-control" rows="3" placeholder="संक्षिप्त सारांश (हिंदी)..."><?php echo htmlspecialchars($blog['summary_hindi'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Content Area -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                    <i class="bi bi-file-text text-primary me-2"></i>Full Article Content
                </h5>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Content (English / HTML or Text)</label>
                    <textarea name="content" class="form-control font-monospace" rows="12" placeholder="Write full article content here. HTML tags like <p>, <h3>, <ul>, <li> are supported..."><?php echo htmlspecialchars($blog['content'] ?? ''); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Content (Hindi / संपूर्ण लेख)</label>
                    <textarea name="content_hindi" class="form-control" rows="10" placeholder="हिंदी में संपूर्ण लेख सामग्री..."><?php echo htmlspecialchars($blog['content_hindi'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Settings Right Sidebar -->
        <div class="col-lg-4">
            <!-- Publishing Controls -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                    <i class="bi bi-send-check text-success me-2"></i>Publishing
                </h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="PUBLISHED" <?php echo ($blog['status'] === 'PUBLISHED') ? 'selected' : ''; ?>>Published (Live)</option>
                        <option value="DRAFT" <?php echo ($blog['status'] === 'DRAFT') ? 'selected' : ''; ?>>Draft</option>
                        <option value="ARCHIVED" <?php echo ($blog['status'] === 'ARCHIVED') ? 'selected' : ''; ?>>Archived</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Author Name</label>
                    <input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($blog['author'] ?? 'Saran Index Editorial'); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Read Time Estimate</label>
                    <input type="text" name="read_time" class="form-control" placeholder="e.g. 5 min read" value="<?php echo htmlspecialchars($blog['read_time'] ?? '5 min read'); ?>">
                </div>

                <div class="d-grid gap-2 pt-2">
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold py-2 shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> <?php echo $editing_id ? 'Update Article' : 'Publish Article'; ?>
                    </button>
                    <a href="blogs.php" class="btn btn-light border rounded-pill">Cancel</a>
                </div>
            </div>

            <!-- Taxonomy & Visual Badges -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                    <i class="bi bi-tags text-warning me-2"></i>Categorization & Theme
                </h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Category Name (English)</label>
                    <input type="text" name="category" class="form-control" placeholder="e.g. Culture & Heritage, Healthcare" value="<?php echo htmlspecialchars($blog['category'] ?? 'General'); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Category Name (Hindi)</label>
                    <input type="text" name="category_hindi" class="form-control" placeholder="e.g. संस्कृति एवं विरासत" value="<?php echo htmlspecialchars($blog['category_hindi'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Bootstrap Icon Class</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi <?php echo htmlspecialchars($blog['icon'] ?? 'bi-journal-text'); ?>"></i></span>
                        <input type="text" name="icon" class="form-control" placeholder="bi-journal-text" value="<?php echo htmlspecialchars($blog['icon'] ?? 'bi-journal-text'); ?>">
                    </div>
                    <small class="text-muted">Bootstrap icons like <code>bi-bank</code>, <code>bi-hospital</code>, <code>bi-geo-alt</code>.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Badge Styling Class</label>
                    <select name="badge_class" class="form-select">
                        <option value="bg-primary" <?php echo ($blog['badge_class'] === 'bg-primary') ? 'selected' : ''; ?>>Primary (Blue)</option>
                        <option value="bg-success" <?php echo ($blog['badge_class'] === 'bg-success') ? 'selected' : ''; ?>>Success (Green)</option>
                        <option value="bg-warning text-dark" <?php echo ($blog['badge_class'] === 'bg-warning text-dark') ? 'selected' : ''; ?>>Warning (Yellow)</option>
                        <option value="bg-danger" <?php echo ($blog['badge_class'] === 'bg-danger') ? 'selected' : ''; ?>>Danger (Red)</option>
                        <option value="bg-info text-dark" <?php echo ($blog['badge_class'] === 'bg-info text-dark') ? 'selected' : ''; ?>>Info (Cyan)</option>
                        <option value="bg-secondary" <?php echo ($blog['badge_class'] === 'bg-secondary') ? 'selected' : ''; ?>>Secondary (Gray)</option>
                        <option value="bg-dark text-white" <?php echo ($blog['badge_class'] === 'bg-dark text-white') ? 'selected' : ''; ?>>Dark</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Featured Image Path (Optional)</label>
                    <input type="text" name="featured_image" class="form-control" placeholder="assets/img/hero-bg.jpg" value="<?php echo htmlspecialchars($blog['featured_image'] ?? ''); ?>">
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

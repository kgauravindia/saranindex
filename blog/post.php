<?php
require_once dirname(__DIR__) . '/includes/functions.php';

$db = getDB();
$slug = sanitizeInput($_GET['slug'] ?? '');
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$blog = null;

if ($db) {
    try {
        if (!empty($slug)) {
            $stmt = $db->prepare("SELECT * FROM blogs WHERE slug = ? AND (status = 'PUBLISHED' OR ? != '') LIMIT 1");
            $isAdmin = !empty($_SESSION['admin_logged_in']) ? '1' : '';
            $stmt->execute([$slug, $isAdmin]);
            $blog = $stmt->fetch(PDO::FETCH_ASSOC);
        } elseif ($id > 0) {
            $stmt = $db->prepare("SELECT * FROM blogs WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            $blog = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Increment view count if published
        if ($blog && empty($_SESSION['admin_logged_in'])) {
            $db->prepare("UPDATE blogs SET views = views + 1 WHERE id = ?")->execute([$blog['id']]);
        }
    } catch (Exception $e) {
        $blog = null;
    }
}

// 404 if not found
if (!$blog) {
    header("HTTP/1.0 404 Not Found");
    $page_title = "Article Not Found – Saran Index";
    require_once dirname(__DIR__) . '/includes/header.php';
    ?>
    <div class="container py-5 text-center my-5">
        <div class="display-1 text-muted fw-bold mb-3">404</div>
        <h2 class="fw-bold text-dark mb-3">Blog Article Not Found</h2>
        <p class="text-muted mb-4">The blog post or local guide you are looking for might have been moved or unpublished.</p>
        <a href="blog/" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Back to Blog Directory
        </a>
    </div>
    <?php
    require_once dirname(__DIR__) . '/includes/footer.php';
    exit;
}

// Fetch related articles
$related_blogs = [];
if ($db) {
    try {
        $relStmt = $db->prepare("SELECT * FROM blogs WHERE id != ? AND status = 'PUBLISHED' ORDER BY id DESC LIMIT 3");
        $relStmt->execute([$blog['id']]);
        $related_blogs = $relStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $related_blogs = [];
    }
}

$page_title = $blog['title'] . " – Saran Index";
$meta_description = !empty($blog['summary']) ? $blog['summary'] : substr(strip_tags($blog['content'] ?? ''), 0, 160);
$canonical_url = (defined('BASE_URL') ? BASE_URL : 'https://saranindex.com/') . 'blog/' . urlencode($blog['slug']);
$og_image = !empty($blog['featured_image']) ? (BASE_URL . $blog['featured_image']) : (BASE_URL . 'assets/logo.png');

require_once dirname(__DIR__) . '/includes/header.php';
?>

<!-- JSON-LD Article Schema for SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": <?php echo json_encode($blog['title']); ?>,
  "description": <?php echo json_encode($meta_description); ?>,
  "image": <?php echo json_encode($og_image); ?>,
  "author": {
    "@type": "Person",
    "name": <?php echo json_encode($blog['author'] ?? 'Saran Index Editorial'); ?>
  },
  "publisher": {
    "@type": "Organization",
    "name": "Saran Index",
    "logo": {
      "@type": "ImageObject",
      "url": "<?php echo BASE_URL; ?>assets/logo.png"
    }
  },
  "datePublished": "<?php echo date('c', strtotime($blog['published_at'] ?? $blog['created_at'])); ?>",
  "dateModified": "<?php echo date('c', strtotime($blog['updated_at'] ?? $blog['created_at'])); ?>",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?php echo htmlspecialchars($canonical_url); ?>"
  }
}
</script>

<!-- Breadcrumb & Header Hero -->
<div class="bg-primary text-white py-4 position-relative shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%) !important;">
    <div class="container px-3">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb small text-white-50 mb-0">
                <li class="breadcrumb-item"><a href="./" class="text-white-50 text-decoration-none"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="blog/" class="text-white-50 text-decoration-none">Blog</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?php echo htmlspecialchars($blog['category'] ?? 'Article'); ?></li>
            </ol>
        </nav>
        <div class="d-inline-flex align-items-center bg-white text-primary fw-bold px-3 py-1 rounded-pill mb-2 shadow-sm fs-7">
            <i class="bi <?php echo htmlspecialchars($blog['icon'] ?? 'bi-journal-text'); ?> me-1.5"></i><?php echo htmlspecialchars($blog['category'] ?? 'Local Guide'); ?>
        </div>
        <h1 class="fw-bolder font-heading text-white display-6 mb-2">
            <?php echo htmlspecialchars($blog['title']); ?>
        </h1>
        <?php if (!empty($blog['title_hindi'])): ?>
            <p class="text-warning fw-semibold fs-6 mb-3 font-heading">
                <?php echo htmlspecialchars($blog['title_hindi']); ?>
            </p>
        <?php endif; ?>

        <div class="d-flex align-items-center flex-wrap gap-3 text-white-50 small mt-2">
            <span><i class="bi bi-person-fill text-warning me-1"></i><?php echo htmlspecialchars($blog['author'] ?? 'Saran Index Team'); ?></span>
            <span><i class="bi bi-calendar3 text-warning me-1"></i><?php echo date('M d, Y', strtotime($blog['published_at'] ?? $blog['created_at'])); ?></span>
            <span><i class="bi bi-clock text-warning me-1"></i><?php echo htmlspecialchars($blog['read_time'] ?? '4 min read'); ?></span>
            <span><i class="bi bi-eye text-warning me-1"></i><?php echo intval($blog['views']); ?> Views</span>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- Main Article Content Area -->
        <div class="col-lg-8">
            <article class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <!-- Summary Callout -->
                <?php if (!empty($blog['summary'])): ?>
                    <div class="p-3.5 mb-4 rounded-3 border-start border-4 border-primary bg-primary-subtle text-primary-emphasis fs-6" style="line-height: 1.7;">
                        <strong><i class="bi bi-info-circle-fill me-1"></i> Overview:</strong> <?php echo htmlspecialchars($blog['summary']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($blog['summary_hindi'])): ?>
                    <div class="p-3 mb-4 rounded-3 border-start border-4 border-warning bg-warning-subtle text-dark small" style="line-height: 1.7;">
                        <strong><i class="bi bi-translate me-1"></i> सारांश (हिंदी):</strong> <?php echo htmlspecialchars($blog['summary_hindi']); ?>
                    </div>
                <?php endif; ?>

                <!-- Full Article Body -->
                <div class="article-content text-secondary" style="font-size: 1.05rem; line-height: 1.85;">
                    <?php 
                    $content = $blog['content'] ?? '';
                    if (!empty($content)) {
                        // If content has HTML tags, render safely, otherwise convert newlines to paragraphs
                        if ($content !== strip_tags($content)) {
                            echo $content;
                        } else {
                            echo '<p>' . nl2br(htmlspecialchars($content)) . '</p>';
                        }
                    } else {
                        echo '<p class="text-muted">Full content is being updated by our editorial team.</p>';
                    }
                    ?>

                    <?php if (!empty($blog['content_hindi'])): ?>
                        <hr class="my-5">
                        <div class="p-4 bg-light rounded-4 border">
                            <h4 class="fw-bold font-heading text-dark mb-3">
                                <i class="bi bi-translate text-primary me-2"></i>हिंदी में संपूर्ण लेख
                            </h4>
                            <div class="text-secondary" style="line-height: 1.8;">
                                <?php 
                                $contentHindi = $blog['content_hindi'];
                                if ($contentHindi !== strip_tags($contentHindi)) {
                                    echo $contentHindi;
                                } else {
                                    echo '<p>' . nl2br(htmlspecialchars($contentHindi)) . '</p>';
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Social Share & Tags Section -->
                <hr class="my-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold text-dark small"><i class="bi bi-share-fill text-primary me-1"></i>Share Article:</span>
                        <?php 
                        $shareUrl = urlencode($canonical_url);
                        $shareTitle = urlencode($blog['title']);
                        ?>
                        <a href="https://api.whatsapp.com/send?text=<?php echo $shareTitle . '%20' . $shareUrl; ?>" target="_blank" class="btn btn-sm btn-success rounded-circle shadow-xs" title="Share on WhatsApp" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $shareUrl; ?>" target="_blank" class="btn btn-sm btn-primary rounded-circle shadow-xs" title="Share on Facebook" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $shareTitle; ?>&url=<?php echo $shareUrl; ?>" target="_blank" class="btn btn-sm btn-dark rounded-circle shadow-xs" title="Share on X" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                    </div>

                    <div>
                        <a href="blog/" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                            <i class="bi bi-arrow-left me-1"></i> All Articles
                        </a>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar Area -->
        <div class="col-lg-4">
            <!-- Author Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary text-white p-3 me-3 fs-4 shadow-sm" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-pen-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($blog['author'] ?? 'Saran Index Editorial'); ?></h6>
                        <small class="text-muted">Digital Desk • Saran Index</small>
                    </div>
                </div>
                <p class="text-muted small mb-0">
                    Publishing verified community guides, district updates, business insights, and historical highlights for Saran District, Bihar.
                </p>
            </div>

            <!-- List Your Business Promotion Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);">
                <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2 align-self-start">Grow Your Reach</span>
                <h5 class="fw-bold font-heading text-white mb-2">Have a business in Saran?</h5>
                <p class="text-white-50 small mb-3">
                    Get listed on the Saran Index directory and reach thousands of local customers across 20 blocks.
                </p>
                <a href="add-contact" class="btn btn-warning text-dark fw-bold rounded-pill w-100 py-2">
                    <i class="bi bi-plus-circle me-1"></i> List Business Free
                </a>
            </div>

            <!-- Related Articles Widget -->
            <?php if (!empty($related_blogs)): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold font-heading text-dark mb-3">Related Stories</h5>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($related_blogs as $rb): ?>
                            <div class="border-bottom pb-2.5">
                                <a href="blog/post.php?slug=<?php echo urlencode($rb['slug']); ?>" class="text-decoration-none">
                                    <span class="badge <?php echo htmlspecialchars($rb['badge_class'] ?? 'bg-primary'); ?> rounded-pill small mb-1" style="font-size: 0.72rem;">
                                        <?php echo htmlspecialchars($rb['category'] ?? 'Guide'); ?>
                                    </span>
                                    <h6 class="fw-bold text-dark mb-1 hover-text-primary fs-7" style="line-height: 1.4;">
                                        <?php echo htmlspecialchars($rb['title']); ?>
                                    </h6>
                                    <span class="text-muted small" style="font-size: 0.75rem;">
                                        <i class="bi bi-calendar3 me-1"></i><?php echo date('M d, Y', strtotime($rb['published_at'] ?? $rb['created_at'])); ?>
                                    </span>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

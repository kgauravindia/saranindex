<?php
require_once dirname(__DIR__) . '/includes/functions.php';

$page_title = "Blog & Local Guides – Saran Index | Stories, Updates & Insights";
$meta_description = "Read the latest news, district updates, travel guides, business spotlights, and cultural stories across Chapra and Saran District, Bihar.";
$canonical_url = (defined('BASE_URL') ? BASE_URL : 'https://saranindex.com/') . 'blog/';

require_once dirname(__DIR__) . '/includes/header.php';

$db = getDB();
$blogs = [];

if ($db) {
    try {
        // Attempt to fetch from blogs table if it exists
        $stmt = $db->query("SELECT * FROM blogs WHERE status = 'PUBLISHED' ORDER BY published_at DESC, created_at DESC LIMIT 12");
        if ($stmt) {
            $raw_blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($raw_blogs as $rb) {
                $blogs[] = [
                    'id' => $rb['id'],
                    'title' => $rb['title'],
                    'title_hindi' => $rb['title_hindi'] ?? '',
                    'slug' => $rb['slug'],
                    'category' => $rb['category'] ?? 'General',
                    'category_hindi' => $rb['category_hindi'] ?? '',
                    'author' => $rb['author'] ?? 'Saran Index Editorial',
                    'date' => !empty($rb['published_at']) ? date('M d, Y', strtotime($rb['published_at'])) : date('M d, Y'),
                    'read_time' => $rb['read_time'] ?? '4 min read',
                    'image' => $rb['featured_image'] ?? 'assets/img/hero-bg.jpg',
                    'icon' => $rb['icon'] ?? 'bi-journal-text',
                    'excerpt' => $rb['summary'] ?? $rb['summary_hindi'] ?? '',
                    'badge_class' => $rb['badge_class'] ?? 'bg-primary'
                ];
            }
        }
    } catch (Exception $e) {
        $blogs = [];
    }
}

// Default curated blog articles when table is empty or not yet seeded
if (empty($blogs)) {
    $blogs = [
        [
            'id' => 1,
            'title' => 'Top 10 Historical Places to Visit in Saran (Chapra)',
            'slug' => 'top-10-historical-places-saran-chapra',
            'category' => 'Culture & Heritage',
            'author' => 'Saran Index Editorial',
            'date' => 'July 26, 2026',
            'read_time' => '5 min read',
            'image' => 'assets/img/hero-bg.jpg',
            'icon' => 'bi-bank',
            'excerpt' => 'From the sacred Ambika Bhavani Temple in Ami to the ancient Dhorh Ashram and Gautam Sthan, discover the timeless cultural wonders of Saran district.',
            'badge_class' => 'bg-primary'
        ],
        [
            'id' => 2,
            'title' => 'Complete Guide to Healthcare & Top Hospitals in Chapra',
            'slug' => 'guide-to-healthcare-hospitals-chapra',
            'category' => 'Health & Wellness',
            'author' => 'Dr. Saran Health Desk',
            'date' => 'July 20, 2026',
            'read_time' => '4 min read',
            'image' => 'assets/img/hero-bg.jpg',
            'icon' => 'bi-hospital',
            'excerpt' => 'A comprehensive directory of government hospitals, private nursing homes, 24x7 emergency contacts, and blood banks in Chapra.',
            'badge_class' => 'bg-success'
        ],
        [
            'id' => 3,
            'title' => 'How to Register & Grow Your Local Business on Saran Index',
            'slug' => 'grow-your-local-business-saran-index',
            'category' => 'Business Guide',
            'author' => 'OfferPlant Team',
            'date' => 'July 15, 2026',
            'read_time' => '3 min read',
            'image' => 'assets/img/hero-bg.jpg',
            'icon' => 'bi-graph-up-arrow',
            'excerpt' => 'Learn step-by-step how to list your shop, service, clinic, or firm on Saran Index to reach thousands of local customers across 20 blocks.',
            'badge_class' => 'bg-warning text-dark'
        ],
        [
            'id' => 4,
            'title' => 'Exploring the 20 Administrative Blocks of Saran District',
            'slug' => 'exploring-20-blocks-of-saran-district',
            'category' => 'Civic & Administration',
            'author' => 'Saran Index Team',
            'date' => 'July 10, 2026',
            'read_time' => '6 min read',
            'image' => 'assets/img/hero-bg.jpg',
            'icon' => 'bi-geo-alt',
            'excerpt' => 'An overview of Saran’s geography, administrative blocks from Chapra Sadar, Marhaura, Sonpur, to Baniapur, along with key block contact details.',
            'badge_class' => 'bg-info text-dark'
        ],
        [
            'id' => 5,
            'title' => 'Educational Hub: Colleges & University under JPU Chapra',
            'slug' => 'educational-colleges-jpu-chapra',
            'category' => 'Education',
            'author' => 'Academic Desk',
            'date' => 'July 05, 2026',
            'read_time' => '4 min read',
            'image' => 'assets/img/hero-bg.jpg',
            'icon' => 'bi-mortarboard',
            'excerpt' => 'Everything you need to know about Jai Prakash University (JPU), constituent colleges, admission processes, and academic milestones in Saran.',
            'badge_class' => 'bg-danger'
        ],
        [
            'id' => 6,
            'title' => 'The Legendary Sonpur Mela: Asia’s Largest Cattle Fair',
            'slug' => 'sonpur-mela-asias-largest-cattle-fair',
            'category' => 'Festivals & Events',
            'author' => 'Culture Desk',
            'date' => 'June 28, 2026',
            'read_time' => '5 min read',
            'image' => 'assets/img/hero-bg.jpg',
            'icon' => 'bi-ticket-perforated',
            'excerpt' => 'History, cultural significance, travel tips, and attractions at the world-renowned Harihar Kshetra Sonpur Cattle Fair held annually in Saran.',
            'badge_class' => 'bg-secondary'
        ]
    ];
}
?>

<!-- Blog Hero Section -->
<div class="bg-primary text-white py-5 position-relative shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%) !important;">
    <div class="container px-3 text-center">
        <div class="d-inline-flex align-items-center bg-white text-primary fw-bold px-3 py-1 rounded-pill mb-3 shadow-sm fs-7">
            <i class="bi bi-journal-text me-1.5"></i>SARAN INDEX BLOG & ARTICLES
        </div>
        <h1 class="fw-bolder font-heading text-white display-5 mb-2">Stories, Guides & Insights from Saran</h1>
        <p class="lead text-white-50 fs-6 mb-0 mx-auto" style="max-width: 700px;">
            Stay updated with local news, business growth tips, district history, healthcare guides, and administrative information across all 20 blocks of Saran (Chapra).
        </p>
    </div>
</div>

<div class="container py-5">
    <!-- Featured Article Banner -->
    <?php if (!empty($blogs)): $featured = $blogs[0]; ?>
    <div class="card border-0 rounded-4 shadow-sm mb-5 overflow-hidden bg-light">
        <div class="row g-0 align-items-center">
            <div class="col-lg-5 p-4 p-md-5 d-flex flex-column justify-content-center bg-primary text-white" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;">
                <div class="mb-3">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill">
                        <i class="bi bi-star-fill me-1"></i> Featured Post
                    </span>
                    <span class="badge bg-white bg-opacity-25 text-white ms-2 px-2.5 py-1 rounded-pill">
                        <?php echo htmlspecialchars($featured['category'] ?? 'General'); ?>
                    </span>
                </div>
                <h2 class="fw-bold font-heading text-white mb-3">
                    <a href="blog/post.php?slug=<?php echo urlencode($featured['slug']); ?>" class="text-white text-decoration-none hover-underline">
                        <?php echo htmlspecialchars($featured['title']); ?>
                    </a>
                </h2>
                <p class="text-white-50 mb-4" style="line-height: 1.6;">
                    <?php echo htmlspecialchars($featured['excerpt'] ?? ''); ?>
                </p>
                <div class="d-flex align-items-center justify-content-between text-white-50 small mb-4">
                    <span><i class="bi bi-person-fill me-1"></i><?php echo htmlspecialchars($featured['author'] ?? 'Admin'); ?></span>
                    <span><i class="bi bi-clock me-1"></i><?php echo htmlspecialchars($featured['read_time'] ?? '4 min read'); ?></span>
                </div>
                <div>
                    <a href="blog/post.php?slug=<?php echo urlencode($featured['slug']); ?>" class="btn btn-warning text-dark fw-bold rounded-pill px-4">
                        Read Full Story <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-7 p-4 p-md-5">
                <div class="p-4 bg-white rounded-4 border shadow-xs">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3 fs-3">
                            <i class="bi <?php echo htmlspecialchars($featured['icon'] ?? 'bi-journal-bookmark'); ?>"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Highlight of the Week</h5>
                            <span class="text-muted small">Published on <?php echo htmlspecialchars($featured['date'] ?? date('F d, Y')); ?></span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">
                        Discover in-depth analysis and authentic local narratives verified by local contributors and researchers across Saran district.
                    </p>
                    <div class="row g-2 pt-2 border-top">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded text-center">
                                <div class="fw-bold text-dark fs-6">20 Blocks</div>
                                <div class="text-muted small">Coverage</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded text-center">
                                <div class="fw-bold text-dark fs-6">Verified</div>
                                <div class="text-muted small">Information</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Articles Grid Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold font-heading text-dark fs-4 mb-0">Latest Articles & Guides</h3>
            <p class="text-muted small mb-0">Browse through stories, civic updates, and local directory insights.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="categories" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                <i class="bi bi-grid-fill me-1"></i> Browse Directory
            </a>
            <a href="add-contact" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> Add Listing
            </a>
        </div>
    </div>

    <!-- Blog Posts Grid -->
    <div class="row g-4">
        <?php foreach ($blogs as $post): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border rounded-4 shadow-sm hover-shadow transition-all bg-white d-flex flex-column justify-content-between">
                    <div class="p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge <?php echo htmlspecialchars($post['badge_class'] ?? 'bg-primary'); ?> rounded-pill px-3 py-1">
                                <?php echo htmlspecialchars($post['category'] ?? 'Updates'); ?>
                            </span>
                            <span class="text-muted small">
                                <i class="bi bi-clock me-1"></i><?php echo htmlspecialchars($post['read_time'] ?? '3 min read'); ?>
                            </span>
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <div class="rounded-3 bg-light text-primary p-2 me-2 fs-4">
                                <i class="bi <?php echo htmlspecialchars($post['icon'] ?? 'bi-file-earmark-text'); ?>"></i>
                            </div>
                            <h5 class="card-title fw-bold text-dark mb-0 fs-6">
                                <a href="blog/post.php?slug=<?php echo urlencode($post['slug']); ?>" class="text-dark text-decoration-none hover-text-primary">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h5>
                        </div>

                        <p class="card-text text-muted small" style="line-height: 1.6;">
                            <?php echo htmlspecialchars($post['excerpt'] ?? ''); ?>
                        </p>
                    </div>

                    <div class="border-top px-4 py-3 bg-light rounded-bottom-4 d-flex align-items-center justify-content-between">
                        <div class="small text-muted">
                            <i class="bi bi-calendar3 me-1"></i><?php echo htmlspecialchars($post['date'] ?? date('M d, Y')); ?>
                        </div>
                        <a href="blog/post.php?slug=<?php echo urlencode($post['slug']); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                            Read More <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Newsletter & Quick Connect Box -->
    <div class="card border-0 rounded-4 shadow-sm mt-5 text-white overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);">
        <div class="card-body p-4 p-md-5 text-center">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-3">Stay Connected</span>
            <h3 class="fw-bold font-heading text-white mb-2">Want to submit an article or local story?</h3>
            <p class="text-white-50 mx-auto mb-4" style="max-width: 600px;">
                Are you a writer, researcher, journalist, or local business owner in Saran? Share your story with our community.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="contact" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2">
                    <i class="bi bi-envelope-fill me-1"></i> Contact Editorial Team
                </a>
                <a href="add-contact" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">
                    <i class="bi bi-building-add me-1"></i> List Your Business
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

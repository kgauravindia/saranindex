<?php
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';

$page_title = "ब्लॉग एवं स्थानीय मार्गदर्शिका – सारण इंडेक्स | इतिहास, स्वास्थ्य व व्यापार";
$meta_description = "सारण (छपरा) जिले के 20 प्रखंडों के ऐतिहासिक स्थल, आयुष्मान भारत अस्पताल, जन औषधि केंद्र, जेपीयू कॉलेज, सोनपुर मेला व व्यापार वृद्धि पर प्रमाणिक लेख व गाइड्स।";
$canonical_url = (defined('BASE_URL') ? BASE_URL : 'https://saranindex.com/') . 'hindi/blog/';

require_once dirname(__DIR__) . '/includes/header.php';

$db = getDB();
$blogs = [];
$categories = [];

if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM blogs WHERE status = 'PUBLISHED' ORDER BY published_at DESC, created_at DESC");
        if ($stmt) {
            $raw_blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($raw_blogs as $rb) {
                $cat = !empty($rb['category_hindi']) ? trim($rb['category_hindi']) : (!empty($rb['category']) ? trim($rb['category']) : 'सामान्य');
                if (!in_array($cat, $categories)) {
                    $categories[] = $cat;
                }
                $blogs[] = [
                    'id' => $rb['id'],
                    'title' => !empty($rb['title_hindi']) ? $rb['title_hindi'] : $rb['title'],
                    'title_en' => $rb['title'],
                    'slug' => $rb['slug'],
                    'category' => $cat,
                    'author' => 'सारण इंडेक्स संपादकीय टीम',
                    'date' => !empty($rb['published_at']) ? date('d M, Y', strtotime($rb['published_at'])) : date('d M, Y'),
                    'read_time' => $rb['read_time'] ?? '5 मिनट पठन',
                    'icon' => $rb['icon'] ?? 'bi-journal-text',
                    'excerpt' => !empty($rb['summary_hindi']) ? $rb['summary_hindi'] : ($rb['summary'] ?? ''),
                    'badge_class' => $rb['badge_class'] ?? 'bg-primary',
                    'views' => intval($rb['views'] ?? 0)
                ];
            }
        }
    } catch (Exception $e) {
        $blogs = [];
    }
}
?>

<style>
.blog-hero-gradient {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 45%, #0284c7 100%);
    color: #ffffff;
    position: relative;
    overflow: hidden;
}
.blog-card {
    transition: transform 0.25s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.25s ease;
    border: 1px solid rgba(0, 0, 0, 0.07);
}
.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 32px rgba(15, 23, 42, 0.1) !important;
    border-color: rgba(37, 99, 235, 0.3);
}
.blog-cat-pill {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid rgba(15, 23, 42, 0.12);
}
.blog-cat-pill.active, .blog-cat-pill:hover {
    background-color: var(--bs-primary) !important;
    color: #ffffff !important;
    border-color: var(--bs-primary) !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<!-- Hero Section -->
<div class="blog-hero-gradient py-5 position-relative">
    <div class="container py-4 text-center position-relative z-1">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="<?php echo defined('BASE_URL') ? BASE_URL : './'; ?>hindi/" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill"></i> मुख्य पृष्ठ</a></li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">ब्लॉग एवं स्थानीय मार्गदर्शिका</li>
            </ol>
        </nav>

        <div class="d-inline-flex align-items-center bg-white bg-opacity-10 border border-white border-opacity-25 text-white fw-bold px-3.5 py-1.5 rounded-pill mb-3 shadow-sm fs-7">
            <i class="bi bi-journal-richtext text-warning me-2"></i>सारण जिला ज्ञान केंद्र एवं लेख
        </div>

        <h1 class="fw-bolder font-heading text-white display-5 mb-3">
            सारण की कहानियां, इतिहास एवं उपयोगी मार्गदर्शिका
        </h1>
        <p class="lead text-white text-opacity-75 fs-6 mb-4 mx-auto" style="max-width: 720px;">
            छपरा एवं सारण जिले के 20 प्रखंडों के प्राचीन इतिहास, धार्मिक तीर्थ, आयुष्मान भारत अस्पताल, कॉलेज और व्यापारिक विकास पर आधारित शोधपरक आलेख।
        </p>

        <!-- Live Instant Search Bar -->
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="input-group shadow-lg rounded-pill overflow-hidden p-1.5 bg-white">
                    <span class="input-group-text bg-white border-0 ps-3 text-muted">
                        <i class="bi bi-search fs-5 text-primary"></i>
                    </span>
                    <input type="text" id="blogSearchInput" class="form-control border-0 shadow-none ps-2 fs-6 font-body" placeholder="इतिहास, चिरांद, अस्पताल, कॉलेज या प्रखंड खोजें..." autocomplete="off">
                    <button class="btn btn-primary rounded-pill px-4 fw-semibold" type="button" id="blogSearchBtn">
                        खोजें
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Category Filter Tabs -->
    <div class="d-flex align-items-center gap-2 mb-4 overflow-auto pb-2 flex-nowrap flex-md-wrap justify-content-start justify-content-md-center">
        <button type="button" class="btn btn-sm rounded-pill px-3.5 py-2 fw-semibold blog-cat-pill bg-white text-dark active" data-category="ALL">
            <i class="bi bi-grid-fill me-1.5"></i> सभी आलेख (<?php echo count($blogs); ?>)
        </button>
        <?php foreach ($categories as $cat): ?>
            <button type="button" class="btn btn-sm rounded-pill px-3.5 py-2 fw-semibold blog-cat-pill bg-white text-dark" data-category="<?php echo htmlspecialchars($cat); ?>">
                <?php echo htmlspecialchars($cat); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Featured Post Hero Card (First Article) -->
    <?php if (!empty($blogs)): $featured = $blogs[0]; ?>
    <div id="featuredPostContainer" class="card border-0 rounded-4 shadow-sm mb-5 overflow-hidden bg-light blog-card">
        <div class="row g-0 align-items-stretch">
            <div class="col-lg-6 p-4 p-md-5 d-flex flex-column justify-content-between text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill">
                            <i class="bi bi-star-fill me-1"></i> प्रमुख आलेख (Featured)
                        </span>
                        <span class="badge bg-primary px-3 py-1 rounded-pill">
                            <?php echo htmlspecialchars($featured['category']); ?>
                        </span>
                    </div>
                    <h2 class="fw-bold font-heading text-white mb-3 display-6 fs-3">
                        <a href="<?php echo getBlogUrl($featured['slug'], true); ?>" class="text-white text-decoration-none">
                            <?php echo htmlspecialchars($featured['title']); ?>
                        </a>
                    </h2>
                    <p class="text-white-50 mb-4 line-clamp-3" style="line-height: 1.7;">
                        <?php echo htmlspecialchars($featured['excerpt']); ?>
                    </p>
                </div>

                <div>
                    <div class="d-flex align-items-center justify-content-between text-white-50 small mb-4 pt-3 border-top border-secondary">
                        <div><i class="bi bi-person-fill text-warning me-1"></i><?php echo htmlspecialchars($featured['author']); ?></div>
                        <div><i class="bi bi-calendar3 text-warning me-1"></i><?php echo htmlspecialchars($featured['date']); ?></div>
                    </div>
                    <a href="<?php echo getBlogUrl($featured['slug'], true); ?>" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm">
                        पूरा आलेख पढ़ें <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-6 p-4 p-md-5 d-flex flex-column justify-content-center bg-white">
                <div class="p-4 bg-light rounded-4 border">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3 fs-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi <?php echo htmlspecialchars($featured['icon']); ?>"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">प्रमाणित स्थानीय सामग्री</h5>
                            <span class="text-muted small">सारण इंडेक्स संपादकीय मंडल द्वारा शोधित</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        सारण जिले के सभी 20 प्रखंडों के इतिहास, संस्कृति, नागरिक सुविधाओं और विकास से जुड़े विस्तृत तथ्य।
                    </p>
                    <div class="row g-2 pt-2 border-top">
                        <div class="col-6">
                            <div class="p-2 bg-white rounded border text-center">
                                <div class="fw-bold text-dark fs-6">20 प्रखंड</div>
                                <div class="text-muted small">कवरेज</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-white rounded border text-center">
                                <div class="fw-bold text-success fs-6"><i class="bi bi-check-circle-fill me-1"></i>100%</div>
                                <div class="text-muted small">सत्यापित</div>
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
            <h3 class="fw-bold font-heading text-dark fs-4 mb-1">
                <i class="bi bi-grid text-primary me-2"></i>सभी आलेख एवं मार्गदर्शिकाएं
            </h3>
            <p class="text-muted small mb-0">सारण जिले की प्रामाणिक जानकारियां एवं नागरिक गाइड पढ़ें।</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo defined('BASE_URL') ? BASE_URL : './'; ?>hindi/categories" class="btn btn-outline-primary btn-sm rounded-pill px-3.5 py-1.5 fw-semibold">
                <i class="bi bi-collection-fill me-1"></i> डायरेक्टरी
            </a>
            <a href="<?php echo defined('BASE_URL') ? BASE_URL : './'; ?>hindi/add-contact" class="btn btn-primary btn-sm rounded-pill px-3.5 py-1.5 fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> लिस्टिंग जोड़ें
            </a>
        </div>
    </div>

    <!-- Articles Grid -->
    <div class="row g-4" id="blogCardsGrid">
        <?php foreach ($blogs as $post): ?>
            <div class="col-lg-4 col-md-6 blog-post-col" 
                 data-category="<?php echo htmlspecialchars($post['category']); ?>"
                 data-title="<?php echo htmlspecialchars(strtolower($post['title'] . ' ' . $post['title_en'] . ' ' . $post['category'] . ' ' . $post['excerpt'])); ?>">
                <div class="card h-100 rounded-4 shadow-sm blog-card bg-white d-flex flex-column justify-content-between overflow-hidden">
                    <div class="p-4">
                        <!-- Card Header Meta -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge <?php echo htmlspecialchars($post['badge_class']); ?> rounded-pill px-3 py-1 fw-semibold fs-8">
                                <?php echo htmlspecialchars($post['category']); ?>
                            </span>
                        </div>

                        <!-- Title & Icon -->
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="rounded-3 bg-primary-subtle text-primary p-2.5 fs-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                <i class="bi <?php echo htmlspecialchars($post['icon']); ?>"></i>
                            </div>
                            <div>
                                <h5 class="card-title fw-bold text-dark mb-1 fs-6 line-clamp-2">
                                    <a href="<?php echo getBlogUrl($post['slug'], true); ?>" class="text-dark text-decoration-none hover-text-primary">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h5>
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <p class="card-text text-secondary small line-clamp-3 mb-0" style="line-height: 1.65;">
                            <?php echo htmlspecialchars($post['excerpt']); ?>
                        </p>
                    </div>

                    <!-- Card Footer -->
                    <div class="border-top px-4 py-3 bg-light bg-opacity-50 d-flex align-items-center justify-content-between">
                        <div class="small text-muted fs-8">
                            <i class="bi bi-calendar3 me-1 text-primary"></i><?php echo htmlspecialchars($post['date']); ?>
                        </div>
                        <a href="<?php echo getBlogUrl($post['slug'], true); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold fs-8">
                            पूरा पढ़ें <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- No Results State -->
    <div id="noResultsBox" class="text-center py-5 d-none">
        <div class="display-6 text-muted mb-3"><i class="bi bi-journal-x"></i></div>
        <h4 class="fw-bold text-dark mb-2">कोई आलेख नहीं मिला</h4>
        <p class="text-muted small mb-3">कृपया अन्य शब्द खोजें या अन्य श्रेणी चुनें।</p>
        <button type="button" id="resetFiltersBtn" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold">
            <i class="bi bi-arrow-counterclockwise me-1"></i> सभी फिल्टर रीसेट करें
        </button>
    </div>

    <!-- Submit Story & Business CTA Box -->
    <div class="card border-0 rounded-4 shadow-sm mt-5 text-white overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0284c7 100%);">
        <div class="card-body p-4 p-md-5 text-center">
            <div class="d-inline-flex align-items-center bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-3 fs-7">
                <i class="bi bi-pen-fill me-1.5"></i> आलेख प्रकाशन एवं व्यापार संवर्धन
            </div>
            <h3 class="fw-bold font-heading text-white mb-2">क्या आपके पास सारण से जुड़ी कोई ऐतिहासिक जानकारी या व्यवसाय है?</h3>
            <p class="text-white text-opacity-80 mx-auto mb-4" style="max-width: 640px;">
                सारण जिले के इतिहास, उत्सवों से जुड़े अपने लेख साझा करें या अपनी दुकान, फर्म व सेवा को 20 प्रखंडों के डायरेक्टरी में निःशुल्क जोड़ें।
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="<?php echo defined('BASE_URL') ? BASE_URL : './'; ?>hindi/contact" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm">
                    <i class="bi bi-envelope-fill me-1"></i> संपादकीय टीम से संपर्क करें
                </a>
                <a href="<?php echo defined('BASE_URL') ? BASE_URL : './'; ?>hindi/add-contact" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">
                    <i class="bi bi-building-add me-1"></i> व्यवसाय निःशुल्क जोड़ें
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('blogSearchInput');
    const searchBtn = document.getElementById('blogSearchBtn');
    const catPills = document.querySelectorAll('.blog-cat-pill');
    const postCols = document.querySelectorAll('.blog-post-col');
    const featuredCard = document.getElementById('featuredPostContainer');
    const noResults = document.getElementById('noResultsBox');
    const resetBtn = document.getElementById('resetFiltersBtn');

    let currentCategory = 'ALL';
    let searchQuery = '';

    function filterPosts() {
        let matchCount = 0;

        postCols.forEach(col => {
            const postCat = col.getAttribute('data-category');
            const postTitle = col.getAttribute('data-title') || '';

            const matchesCat = (currentCategory === 'ALL' || postCat === currentCategory);
            const matchesSearch = (!searchQuery || postTitle.includes(searchQuery));

            if (matchesCat && matchesSearch) {
                col.classList.remove('d-none');
                matchCount++;
            } else {
                col.classList.add('d-none');
            }
        });

        if (featuredCard) {
            if (currentCategory !== 'ALL' || searchQuery !== '') {
                featuredCard.classList.add('d-none');
            } else {
                featuredCard.classList.remove('d-none');
            }
        }

        if (matchCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }

    catPills.forEach(pill => {
        pill.addEventListener('click', function() {
            catPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.getAttribute('data-category');
            filterPosts();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchQuery = this.value.trim().toLowerCase();
            filterPosts();
        });
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            searchQuery = searchInput.value.trim().toLowerCase();
            filterPosts();
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            searchQuery = '';
            currentCategory = 'ALL';
            catPills.forEach(p => p.classList.remove('active'));
            if (catPills[0]) catPills[0].classList.add('active');
            filterPosts();
        });
    }
});
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

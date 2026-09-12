<?php
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';

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

        // Increment view count if published visitor
        if ($blog && empty($_SESSION['admin_logged_in'])) {
            $db->prepare("UPDATE blogs SET views = views + 1 WHERE id = ?")->execute([$blog['id']]);
            $blog['views'] = intval($blog['views']) + 1;
        }
    } catch (Exception $e) {
        $blog = null;
    }
}

// 404 if not found
if (!$blog) {
    header("HTTP/1.0 404 Not Found");
    $page_title = "आलेख नहीं मिला – सारण इंडेक्स";
    require_once dirname(__DIR__) . '/includes/header.php';
    ?>
    <div class="container py-5 text-center my-5">
        <div class="display-1 text-muted fw-bold mb-3">404</div>
        <h2 class="fw-bold text-dark mb-3">ब्लॉग आलेख नहीं मिला</h2>
        <p class="text-muted mb-4">आप जिस आलेख या मार्गदर्शिका को खोज रहे हैं वह उपलब्ध नहीं है या हटा दी गई है।</p>
        <a href="<?php echo defined('BASE_URL') ? BASE_URL : './'; ?>hindi/blog/" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> ब्लॉग डायरेक्टरी पर वापस जाएं
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
        $relStmt = $db->prepare("SELECT * FROM blogs WHERE id != ? AND status = 'PUBLISHED' ORDER BY id DESC LIMIT 4");
        $relStmt->execute([$blog['id']]);
        $related_blogs = $relStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $related_blogs = [];
    }
}

$display_title = !empty($blog['title_hindi']) ? $blog['title_hindi'] : $blog['title'];
$page_title = $display_title . " – सारण इंडेक्स";
$meta_description = !empty($blog['summary_hindi']) ? $blog['summary_hindi'] : (!empty($blog['summary']) ? $blog['summary'] : substr(strip_tags($blog['content_hindi'] ?? ''), 0, 160));
$canonical_url = (defined('BASE_URL') ? BASE_URL : 'https://saranindex.com/') . 'hindi/blog/post.php?slug=' . urlencode($blog['slug']);
$og_image = !empty($blog['featured_image']) ? (BASE_URL . $blog['featured_image']) : ((defined('BASE_URL') ? BASE_URL : 'https://saranindex.com/') . 'assets/logo.png');

require_once dirname(__DIR__) . '/includes/header.php';
?>

<!-- Reading Progress Bar -->
<div id="readingProgressBar" style="position: fixed; top: 0; left: 0; height: 4px; background: linear-gradient(90deg, #2563eb, #0284c7, #10b981); width: 0%; z-index: 9999; transition: width 0.1s ease;"></div>

<style>
.article-hero-gradient {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0369a1 100%);
    color: #ffffff;
}
.article-body h2 {
    font-weight: 700;
    color: #0f172a;
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-size: 1.45rem;
    position: relative;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e2e8f0;
}
.article-body h3 {
    font-weight: 600;
    color: #1e293b;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    font-size: 1.2rem;
}
.article-body p {
    font-size: 1.08rem;
    line-height: 1.85;
    color: #334155;
    margin-bottom: 1.25rem;
}
.article-body ul, .article-body ol {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #334155;
    margin-bottom: 1.25rem;
    padding-left: 1.5rem;
}
.article-body li {
    margin-bottom: 0.4rem;
}
.toc-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
}
.toc-box a {
    color: #475569;
    text-decoration: none;
    font-size: 0.88rem;
    display: block;
    padding: 0.25rem 0;
    transition: color 0.15s ease;
}
.toc-box a:hover {
    color: #2563eb;
    text-decoration: underline;
}
.lang-tab-btn.active {
    background: #2563eb !important;
    color: #ffffff !important;
    border-color: #2563eb !important;
}
</style>

<!-- JSON-LD Article Schema for SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": <?php echo json_encode($display_title); ?>,
  "description": <?php echo json_encode($meta_description); ?>,
  "image": <?php echo json_encode($og_image); ?>,
  "author": {
    "@type": "Person",
    "name": "सारण इंडेक्स संपादकीय टीम"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Saran Index",
    "logo": {
      "@type": "ImageObject",
      "url": "<?php echo defined('BASE_URL') ? BASE_URL : 'https://saranindex.com/'; ?>assets/logo.png"
    }
  },
  "datePublished": "<?php echo date('c', strtotime($blog['published_at'] ?? $blog['created_at'])); ?>",
  "dateModified": "<?php echo date('c', strtotime($blog['updated_at'] ?? $blog['created_at'])); ?>",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": <?php echo json_encode($canonical_url); ?>
  }
}
</script>

<!-- Breadcrumb & Header Hero -->
<div class="article-hero-gradient py-5 position-relative shadow-sm">
    <div class="container px-3">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small text-white-50 mb-0">
                <li class="breadcrumb-item"><a href="<?php echo defined('BASE_URL') ? BASE_URL : './'; ?>hindi/" class="text-white-50 text-decoration-none"><i class="bi bi-house-door me-1"></i>मुख्य पृष्ठ</a></li>
                <li class="breadcrumb-item"><a href="<?php echo defined('BASE_URL') ? BASE_URL : './'; ?>hindi/blog/" class="text-white-50 text-decoration-none">ब्लॉग एवं गाइड्स</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?php echo htmlspecialchars($blog['category_hindi'] ?? $blog['category']); ?></li>
            </ol>
        </nav>

        <div class="d-inline-flex align-items-center bg-white bg-opacity-15 border border-white border-opacity-25 text-white fw-bold px-3 py-1 rounded-pill mb-3 shadow-sm fs-7">
            <i class="bi <?php echo htmlspecialchars($blog['icon'] ?? 'bi-journal-text'); ?> text-warning me-1.5"></i><?php echo htmlspecialchars($blog['category_hindi'] ?? $blog['category']); ?>
        </div>

        <h1 class="fw-bolder font-heading text-white display-6 mb-3" style="line-height: 1.25;">
            <?php echo htmlspecialchars($display_title); ?>
        </h1>

        <?php if (!empty($blog['title']) && $blog['title'] !== $display_title): ?>
            <h4 class="text-warning text-opacity-95 fw-normal mb-3 fs-5 font-heading">
                <?php echo htmlspecialchars($blog['title']); ?>
            </h4>
        <?php endif; ?>

        <div class="d-flex align-items-center flex-wrap gap-3 text-white-50 small mt-3 pt-3 border-top border-white border-opacity-15">
            <span><i class="bi bi-person-fill text-warning me-1"></i>सारण इंडेक्स टीम</span>
            <span>•</span>
            <span><i class="bi bi-calendar3 text-warning me-1"></i><?php echo date('d F, Y', strtotime($blog['published_at'] ?? $blog['created_at'])); ?></span>
            <span>•</span>
            <span><i class="bi bi-eye text-warning me-1"></i><?php echo number_format(intval($blog['views'])); ?> व्यूज</span>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 g-lg-5">
        <!-- Main Article Content -->
        <div class="col-lg-8">
            <article class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <!-- Summary Key Takeaways Callout -->
                <?php if (!empty($blog['summary_hindi']) || !empty($blog['summary'])): ?>
                    <div class="p-3.5 mb-4 rounded-3 border-start border-4 border-warning bg-warning-subtle text-dark fs-6" style="line-height: 1.7;">
                        <div class="fw-bold mb-1 text-dark d-flex align-items-center">
                            <i class="bi bi-info-circle-fill text-warning me-2 fs-5"></i> मुख्य बिंदु एवं सारांश
                        </div>
                        <div><?php echo htmlspecialchars(!empty($blog['summary_hindi']) ? $blog['summary_hindi'] : $blog['summary']); ?></div>
                    </div>
                <?php endif; ?>

                <!-- Language Selector Toggle Tabs -->
                <?php if (!empty($blog['content']) && !empty($blog['content_hindi'])): ?>
                    <div class="d-flex align-items-center justify-content-between p-2.5 mb-4 bg-light rounded-3 border">
                        <div class="small fw-semibold text-dark">
                            <i class="bi bi-translate text-primary me-1.5"></i> भाषा बदलें:
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary lang-tab-btn active px-3" id="btnShowHindi">हिंदी</button>
                            <button type="button" class="btn btn-outline-primary lang-tab-btn px-3" id="btnShowEnglish">English</button>
                            <button type="button" class="btn btn-outline-primary lang-tab-btn px-3" id="btnShowBoth">दोनों / Both</button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Hindi Article Body (Default in Hindi page) -->
                <div id="hindiContentSection" class="article-body">
                    <?php 
                    $contentHindi = $blog['content_hindi'] ?? '';
                    if (!empty($contentHindi)) {
                        echo $contentHindi;
                    } elseif (!empty($blog['content'])) {
                        echo $blog['content'];
                    } else {
                        echo '<p class="text-muted">संपादकीय टीम द्वारा आलेख अपडेट किया जा रहा है।</p>';
                    }
                    ?>
                </div>

                <!-- English Article Body -->
                <?php if (!empty($blog['content'])): ?>
                    <div id="englishContentSection" class="article-body <?php echo !empty($blog['content_hindi']) ? 'd-none' : ''; ?>">
                        <div class="p-3 mb-4 rounded-3 border-start border-4 border-primary bg-primary-subtle text-primary-emphasis">
                            <strong><i class="bi bi-info-circle-fill me-1"></i> English Version:</strong> <?php echo htmlspecialchars($blog['summary'] ?? ''); ?>
                        </div>
                        <div>
                            <?php echo $blog['content']; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Social Share & Tags Section -->
                <hr class="my-5">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 p-3 bg-light rounded-3 border">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="fw-bold text-dark small"><i class="bi bi-share-fill text-primary me-1.5"></i>शेयर करें:</span>
                        <?php 
                        $shareUrl = urlencode($canonical_url);
                        $shareTitle = urlencode($display_title . ' – सारण इंडेक्स');
                        ?>
                        <a href="https://api.whatsapp.com/send?text=<?php echo $shareTitle . '%20' . $shareUrl; ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold shadow-xs" title="WhatsApp पर शेयर करें">
                            <i class="bi bi-whatsapp me-1"></i> व्हाट्सएप
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $shareUrl; ?>" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold shadow-xs" title="Facebook पर शेयर करें">
                            <i class="bi bi-facebook me-1"></i> फेसबुक
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $shareTitle; ?>&url=<?php echo $shareUrl; ?>" target="_blank" class="btn btn-sm btn-dark rounded-pill px-3 fw-semibold shadow-xs" title="X पर शेयर करें">
                            <i class="bi bi-twitter-x me-1"></i> पोस्ट
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold" id="copyLinkBtn" title="लिंक कॉपी करें">
                            <i class="bi bi-link-45deg me-1"></i> लिंक कॉपी
                        </button>
                    </div>

                    <div>
                        <a href="<?php echo defined('BASE_URL') ? BASE_URL : './'; ?>hindi/blog/" class="btn btn-outline-primary btn-sm rounded-pill px-3.5 fw-semibold">
                            <i class="bi bi-arrow-left me-1"></i> सभी आलेख
                        </a>
                    </div>
                </div>

                <!-- Toast Notification for Copy Link -->
                <div id="copyToast" class="alert alert-success small py-2 px-3 rounded-pill mt-3 d-none text-center">
                    <i class="bi bi-check-circle-fill me-1"></i> आलेख का लिंक सफलतापूर्वक कॉपी हो गया!
                </div>
            </article>
        </div>

        <!-- Sidebar Area -->
        <div class="col-lg-4">
            <!-- Table of Contents Widget (Auto-Generated) -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white toc-box sticky-top" style="top: 20px; z-index: 10;">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-list-nested text-primary me-2 fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark">विषय सूची (अनुक्रम)</h6>
                </div>
                <div id="tocList" class="ps-1">
                    <span class="text-muted small">लोड हो रहा है...</span>
                </div>
            </div>

            <!-- Author Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3 fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-feather"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">सारण इंडेक्स संपादकीय टीम</h6>
                        <small class="text-muted"><i class="bi bi-patch-check-fill text-primary me-1"></i>प्रमाणित स्थानीय ज्ञान केंद्र</small>
                    </div>
                </div>
                <p class="text-muted small mb-0" style="line-height: 1.6;">
                    सारण जिले के 20 प्रखंडों के इतिहास, संस्कृति, नागरिक सेवाओं, स्वास्थ्य और व्यापार पर आधारित प्रामाणिक जानकारियों का प्रकाशन।
                </p>
            </div>

            <!-- List Your Business Promotion Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);">
                <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2 align-self-start">व्यापार बढ़ाएं</span>
                <h5 class="fw-bold font-heading text-white mb-2">क्या आपका सारण में व्यवसाय है?</h5>
                <p class="text-white text-opacity-75 small mb-3">
                    सारण इंडेक्स पर आज ही अपनी दुकान, क्लिनिक या फर्म को जोड़ें और 20 प्रखंडों के ग्राहकों से जुड़ें।
                </p>
                <a href="<?php echo defined('BASE_URL') ? BASE_URL : './'; ?>hindi/add-contact" class="btn btn-warning text-dark fw-bold rounded-pill w-100 py-2 shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> निःशुल्क लिस्टिंग जोड़ें
                </a>
            </div>

            <!-- Related Articles Widget -->
            <?php if (!empty($related_blogs)): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold font-heading text-dark mb-0">सारण से जुड़े अन्य आलेख</h6>
                        <a href="<?php echo defined('BASE_URL') ? BASE_URL : './'; ?>hindi/blog/" class="small text-primary text-decoration-none fw-semibold">सभी देखें</a>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($related_blogs as $rb): ?>
                            <div class="border-bottom pb-2.5">
                                <a href="<?php echo getBlogUrl($rb['slug'], true); ?>" class="text-decoration-none">
                                    <span class="badge <?php echo htmlspecialchars($rb['badge_class'] ?? 'bg-primary'); ?> rounded-pill small mb-1" style="font-size: 0.7rem;">
                                        <?php echo htmlspecialchars($rb['category_hindi'] ?? $rb['category']); ?>
                                    </span>
                                    <h6 class="fw-bold text-dark mb-1 hover-text-primary fs-7" style="line-height: 1.45;">
                                        <?php echo htmlspecialchars(!empty($rb['title_hindi']) ? $rb['title_hindi'] : $rb['title']); ?>
                                    </h6>
                                    <span class="text-muted small" style="font-size: 0.75rem;">
                                        <i class="bi bi-calendar3 me-1"></i><?php echo date('d M, Y', strtotime($rb['published_at'] ?? $rb['created_at'])); ?>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Reading Progress Bar
    window.addEventListener('scroll', function() {
        const docElem = document.documentElement;
        const docBody = document.body;
        const scrollTop = docElem['scrollTop'] || docBody['scrollTop'];
        const scrollBottom = (docElem['scrollHeight'] || docBody['scrollHeight']) - window.innerHeight;
        const scrollPercent = scrollBottom > 0 ? (scrollTop / scrollBottom) * 100 : 0;
        const progressBar = document.getElementById('readingProgressBar');
        if (progressBar) {
            progressBar.style.width = scrollPercent + '%';
        }
    });

    // 2. Dynamic Table of Contents (TOC)
    const tocList = document.getElementById('tocList');
    const articleHeadings = document.querySelectorAll('.article-body h2');
    if (tocList && articleHeadings.length > 0) {
        tocList.innerHTML = '';
        const ol = document.createElement('ol');
        ol.className = 'ps-3 mb-0 small text-muted';
        
        articleHeadings.forEach((heading, idx) => {
            const anchorId = 'section-' + (idx + 1);
            heading.id = anchorId;
            const li = document.createElement('li');
            li.className = 'mb-1';
            const a = document.createElement('a');
            a.href = '#' + anchorId;
            a.textContent = heading.textContent.replace(/[0-9]+\.\s*/, '');
            li.appendChild(a);
            ol.appendChild(li);
        });
        tocList.appendChild(ol);
    } else if (tocList) {
        const tocBox = document.querySelector('.toc-box');
        if (tocBox) tocBox.classList.add('d-none');
    }

    // 3. Language Switcher Tabs
    const btnEn = document.getElementById('btnShowEnglish');
    const btnHi = document.getElementById('btnShowHindi');
    const btnBoth = document.getElementById('btnShowBoth');
    const secEn = document.getElementById('englishContentSection');
    const secHi = document.getElementById('hindiContentSection');

    if (btnEn && btnHi && secEn && secHi) {
        btnEn.addEventListener('click', function() {
            btnEn.classList.add('active');
            btnHi.classList.remove('active');
            if (btnBoth) btnBoth.classList.remove('active');
            secEn.classList.remove('d-none');
            secHi.classList.add('d-none');
        });

        btnHi.addEventListener('click', function() {
            btnHi.classList.add('active');
            btnEn.classList.remove('active');
            if (btnBoth) btnBoth.classList.remove('active');
            secHi.classList.remove('d-none');
            secEn.classList.add('d-none');
        });

        if (btnBoth) {
            btnBoth.addEventListener('click', function() {
                btnBoth.classList.add('active');
                btnEn.classList.remove('active');
                btnHi.classList.remove('active');
                secEn.classList.remove('d-none');
                secHi.classList.remove('d-none');
            });
        }
    }

    // 4. Copy Link with Toast Notification
    const copyBtn = document.getElementById('copyLinkBtn');
    const copyToast = document.getElementById('copyToast');
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                if (copyToast) {
                    copyToast.classList.remove('d-none');
                    setTimeout(() => {
                        copyToast.classList.add('d-none');
                    }, 3000);
                }
            }).catch(function(err) {
                prompt("Copy link manually:", window.location.href);
            });
        });
    }
});
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

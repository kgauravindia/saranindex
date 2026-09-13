<?php
require_once __DIR__ . '/includes/functions.php';

// Get query name if passed
$queryName = isset($_GET['name']) ? sanitizeInput($_GET['name']) : (isset($_GET['q']) ? sanitizeInput($_GET['q']) : '');
$cleanName = strtolower(trim(preg_replace('/[^a-zA-Z0-9_-]/', '', ltrim($queryName, '@'))));

$page_title = !empty($cleanName) 
    ? "Check '$cleanName' Username & Domain Availability – Name Check | Saran Index" 
    : "Business Name, Domain & Social Media Username Availability Checker – Saran Index";

$meta_description = "Check your business or brand name availability across 10 top domain extensions (.com, .in, .org, .co.in, etc.), 10 major social media networks (FB, X, Instagram, YouTube, Telegram, etc.), and claim your Saran Index @handle.";

require_once __DIR__ . '/includes/header.php';

// 10 Selected Top Domain Extensions
$domainExtensions = [
    'com' => ['tld' => '.com', 'label' => 'Commercial (Global)', 'badge' => 'Most Popular', 'color' => 'primary'],
    'in' => ['tld' => '.in', 'label' => 'India Official', 'badge' => 'Top in India', 'color' => 'success'],
    'org' => ['tld' => '.org', 'label' => 'Organization / NGO', 'badge' => 'Global Trust', 'color' => 'info'],
    'co_in' => ['tld' => '.co.in', 'label' => 'Commercial India', 'badge' => 'Indian Business', 'color' => 'success'],
    'net' => ['tld' => '.net', 'label' => 'Network & Technology', 'badge' => 'Tech Standard', 'color' => 'primary'],
    'info' => ['tld' => '.info', 'label' => 'Information Portal', 'badge' => 'Informational', 'color' => 'warning'],
    'biz' => ['tld' => '.biz', 'label' => 'Business & Enterprise', 'badge' => 'Enterprise', 'color' => 'dark'],
    'online' => ['tld' => '.online', 'label' => 'Modern Online Brand', 'badge' => 'Trendy', 'color' => 'purple'],
    'store' => ['tld' => '.store', 'label' => 'E-Commerce & Retail', 'badge' => 'Shopping', 'color' => 'danger'],
    'org_in' => ['tld' => '.org.in', 'label' => 'Indian Organizations', 'badge' => 'India Non-Profit', 'color' => 'secondary'],
    'ai' => ['tld' => '.ai', 'label' => 'Artificial Intelligence & Tech', 'badge' => 'Trending AI', 'color' => 'indigo'],
    'ai_in' => ['tld' => '.ai.in', 'label' => 'Indian AI & Innovation', 'badge' => 'India AI Brand', 'color' => 'success']
];

// 10 Major Social Media Platforms
$socialPlatforms = [
    'facebook' => [
        'name' => 'Facebook',
        'icon' => 'bi-facebook',
        'brand_color' => '#1877f2',
        'url_pattern' => 'https://www.facebook.com/{name}',
        'register_url' => 'https://www.facebook.com/'
    ],
    'x' => [
        'name' => 'X (Twitter)',
        'icon' => 'bi-twitter-x',
        'brand_color' => '#000000',
        'url_pattern' => 'https://x.com/{name}',
        'register_url' => 'https://x.com/signup'
    ],
    'instagram' => [
        'name' => 'Instagram',
        'icon' => 'bi-instagram',
        'brand_color' => '#e1306c',
        'url_pattern' => 'https://www.instagram.com/{name}/',
        'register_url' => 'https://www.instagram.com/accounts/emailsignup/'
    ],
    'youtube' => [
        'name' => 'YouTube',
        'icon' => 'bi-youtube',
        'brand_color' => '#ff0000',
        'url_pattern' => 'https://www.youtube.com/@{name}',
        'register_url' => 'https://www.youtube.com/'
    ],
    'telegram' => [
        'name' => 'Telegram',
        'icon' => 'bi-telegram',
        'brand_color' => '#229ed9',
        'url_pattern' => 'https://t.me/{name}',
        'register_url' => 'https://telegram.org/'
    ],
    'linkedin' => [
        'name' => 'LinkedIn',
        'icon' => 'bi-linkedin',
        'brand_color' => '#0a66c2',
        'url_pattern' => 'https://www.linkedin.com/in/{name}',
        'register_url' => 'https://www.linkedin.com/signup'
    ],
    'github' => [
        'name' => 'GitHub',
        'icon' => 'bi-github',
        'brand_color' => '#24292e',
        'url_pattern' => 'https://github.com/{name}',
        'register_url' => 'https://github.com/join'
    ],
    'pinterest' => [
        'name' => 'Pinterest',
        'icon' => 'bi-pinterest',
        'brand_color' => '#e60023',
        'url_pattern' => 'https://www.pinterest.com/{name}/',
        'register_url' => 'https://www.pinterest.com/'
    ],
    'reddit' => [
        'name' => 'Reddit',
        'icon' => 'bi-reddit',
        'brand_color' => '#ff4500',
        'url_pattern' => 'https://www.reddit.com/user/{name}',
        'register_url' => 'https://www.reddit.com/register/'
    ],
    'threads' => [
        'name' => 'Threads',
        'icon' => 'bi-threads',
        'brand_color' => '#000000',
        'url_pattern' => 'https://www.threads.net/@{name}',
        'register_url' => 'https://www.threads.net/'
    ],
    'blogger' => [
        'name' => 'Blogger',
        'icon' => 'bi-newspaper',
        'brand_color' => '#f57c00',
        'url_pattern' => 'https://{name}.blogspot.com',
        'register_url' => 'https://www.blogger.com/'
    ],
    'medium' => [
        'name' => 'Medium',
        'icon' => 'bi-medium',
        'brand_color' => '#12100e',
        'url_pattern' => 'https://medium.com/@{name}',
        'register_url' => 'https://medium.com/m/signin'
    ]
];
?>

<style>
/* Custom NameCheck Theme Styles */
.namechk-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #1e3a8a 100%);
    position: relative;
    overflow: hidden;
}
.namechk-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, rgba(0,0,0,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.namechk-search-card {
    background: #ffffff;
    border-radius: 24px;
    box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.3s ease;
}
.namechk-input-wrap {
    position: relative;
}
.namechk-input {
    font-size: 1.25rem;
    font-weight: 600;
    padding: 16px 20px 16px 52px;
    border-radius: 16px;
    border: 2px solid #e2e8f0;
    transition: all 0.25s ease;
    letter-spacing: -0.01em;
}
.namechk-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
    background: #ffffff;
}
.namechk-input-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.35rem;
    color: #94a3b8;
}
.item-card {
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    padding: 16px 18px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.item-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
}
.item-card.state-available {
    border-color: #86efac;
    background: #f0fdf4;
}
.item-card.state-taken {
    border-color: #fecdd3;
    background: #fff1f2;
}
.item-card.state-checking {
    border-color: #93c5fd;
    background: #eff6ff;
}
.state-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
}
.state-indicator.available {
    background-color: #22c55e;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.25);
}
.state-indicator.taken {
    background-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25);
}
.state-indicator.pending {
    background-color: #94a3b8;
}
.state-indicator.checking {
    background-color: #3b82f6;
    animation: pulse 1s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.3); }
}
.saran-vip-card {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    border-radius: 20px;
    color: #ffffff;
    box-shadow: 0 15px 30px -10px rgba(37, 99, 235, 0.35);
}
.nav-pill-custom {
    border-radius: 50rem;
    padding: 8px 18px;
    font-weight: 600;
    font-size: 0.88rem;
    color: #64748b;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
}
.nav-pill-custom.active {
    background-color: #2563eb;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}
.nav-pill-custom:hover:not(.active) {
    background-color: #f1f5f9;
    color: #1e293b;
}
.filter-tabs-container {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    max-width: 100%;
    scrollbar-width: none;
}
.filter-tabs-container::-webkit-scrollbar {
    display: none;
}
.domain-name-text {
    word-break: break-all;
}

/* Mobile Friendly Optimization Rules */
@media (max-width: 767.98px) {
    .namechk-hero {
        padding-top: 1.75rem !important;
        padding-bottom: 2rem !important;
    }
    .namechk-hero h1 {
        font-size: 1.55rem !important;
        line-height: 1.3;
    }
    .namechk-hero p.lead {
        font-size: 0.88rem !important;
    }
    .namechk-search-card {
        padding: 1rem !important;
        border-radius: 18px;
    }
    .namechk-input {
        font-size: 1.05rem;
        padding: 12px 14px 12px 42px;
        border-radius: 12px;
    }
    .namechk-input-icon {
        left: 14px;
        font-size: 1.15rem;
    }
    #checkBtn {
        padding: 12px 16px !important;
        border-radius: 12px !important;
        font-size: 0.95rem !important;
    }
    .saran-vip-card {
        padding: 1.15rem !important;
        border-radius: 16px;
    }
    .saran-vip-card .fs-5 {
        font-size: 1.05rem !important;
    }
    .nav-pill-custom {
        padding: 6px 13px;
        font-size: 0.78rem;
        white-space: nowrap;
    }
    .item-card {
        padding: 14px;
        border-radius: 14px;
    }
    #saranUrlPreview {
        word-break: break-all;
        white-space: normal;
        display: inline-block;
        margin-top: 4px;
        font-size: 0.8rem;
    }
    .example-pills-wrap {
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .example-pills-wrap::-webkit-scrollbar {
        display: none;
    }
}
</style>

<!-- Name Check Hero Section -->
<div class="namechk-hero py-4 py-md-5 text-white">
    <div class="container py-2 py-md-3">
        <div class="text-center max-w-2xl mx-auto mb-3 mb-md-4">
            <div class="d-inline-flex align-items-center bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2 mb-md-3 shadow-sm fs-7">
                <i class="bi bi-shield-check me-1.5 fs-6"></i>INSTANT BRAND & USERNAME CHECKER
            </div>
            <h1 class="fw-bolder font-heading text-white display-5 mb-2">Check Name, Domain & Social Media Availability</h1>
            <p class="lead text-white-50 fs-6 mb-0 mx-auto" style="max-width: 680px;">
                Find out if your business name or personal brand is available across <strong>10 Top Domain Extensions</strong>, <strong>10 Major Social Media Platforms</strong>, and claim your verified <strong>Saran Index @Handle</strong>.
            </p>
        </div>

        <!-- Central Search Bar Form -->
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="namechk-search-card p-3 p-md-4">
                    <form id="nameCheckForm" onsubmit="handleNameSearch(event)" class="row g-2 align-items-center">
                        <div class="col-md-8 col-lg-9">
                            <div class="namechk-input-wrap">
                                <i class="bi bi-at namechk-input-icon"></i>
                                <input type="text" id="nameInput" name="name" 
                                       class="form-control namechk-input" 
                                       placeholder="Enter your brand or username (e.g. yourbrand)" 
                                       value="<?php echo htmlspecialchars($cleanName); ?>" 
                                       autocomplete="off" required autofocus>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <button type="submit" id="checkBtn" class="btn btn-primary w-100 py-3 rounded-4 fw-bold fs-6 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-search"></i>
                                <span>Check Now</span>
                            </button>
                        </div>
                    </form>

                    <!-- Popular Presets / Suggestions (Mobile Friendly Touch Scroll) -->
                    <div class="d-flex align-items-center gap-1.5 mt-2.5 pt-2 border-top text-muted extra-small example-pills-wrap">
                        <span class="fw-bold text-secondary flex-shrink-0"><i class="bi bi-lightbulb me-1 text-warning"></i>Try Example:</span>
                        <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill flex-shrink-0" style="cursor: pointer;" onclick="setAndCheck('bharatbazaar')">bharatbazaar</span>
                        <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill flex-shrink-0" style="cursor: pointer;" onclick="setAndCheck('saranhospital')">saranhospital</span>
                        <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill flex-shrink-0" style="cursor: pointer;" onclick="setAndCheck('chapraadvocate')">chapraadvocate</span>
                        <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill flex-shrink-0" style="cursor: pointer;" onclick="setAndCheck('patliputratech')">patliputratech</span>
                        <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill flex-shrink-0" style="cursor: pointer;" onclick="setAndCheck('biharmart')">biharmart</span>
                        <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill flex-shrink-0" style="cursor: pointer;" onclick="setAndCheck('desikart')">desikart</span>
                        <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill flex-shrink-0" style="cursor: pointer;" onclick="setAndCheck('offerplant')">offerplant</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Results Container -->
<div class="container py-4 py-md-5" id="resultsContainer">

    <!-- Active Search Info Bar & Filter Tabs -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold font-heading text-dark fs-4 mb-1" id="currentSearchTitle">
                <?php if (!empty($cleanName)): ?>
                    Availability for "<span class="text-primary"><?php echo htmlspecialchars($cleanName); ?></span>"
                <?php else: ?>
                    Ready to Search Indian Brands & Domains
                <?php endif; ?>
            </h2>
            <p class="text-muted small mb-0" id="currentSearchSubtitle">
                Real-time DNS & platform lookup across 10 domains + 10 social networks + Saran Index.
            </p>
        </div>

        <!-- Filter Tabs (Horizontal touch scrollable on mobile) -->
        <div class="filter-tabs-container">
            <div class="d-inline-flex align-items-center gap-1.5 p-1 bg-white border rounded-pill shadow-xs">
                <button type="button" class="nav-pill-custom active" data-filter="all" onclick="filterResults('all', this)">All (<?php echo count($domainExtensions) + count($socialPlatforms) + 1; ?>)</button>
                <button type="button" class="nav-pill-custom" data-filter="domain" onclick="filterResults('domain', this)">Domains (<?php echo count($domainExtensions); ?>)</button>
                <button type="button" class="nav-pill-custom" data-filter="social" onclick="filterResults('social', this)">Social (<?php echo count($socialPlatforms); ?>)</button>
                <button type="button" class="nav-pill-custom text-success" data-filter="available" onclick="filterResults('available', this)"><i class="bi bi-check-circle me-1"></i>Available Only</button>
            </div>
        </div>
    </div>

    <!-- 1. VIP Saran Index Handle Availability Card -->
    <div class="saran-vip-card p-3 p-md-4 mb-4 mb-md-5 shadow-sm" id="saranIndexVipCard">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2.5 gap-md-3">
                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold fs-3 shadow-xs flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h3 class="fw-bold text-white fs-5 mb-0 font-heading">
                                Saran Index Handle: <span id="saranHandlePreview" class="text-warning">@<?php echo htmlspecialchars($cleanName ?: 'yourbrand'); ?></span>
                            </h3>
                            <span id="saranHandleBadge" class="badge bg-warning text-dark fw-bold rounded-pill px-2.5 py-1 extra-small">
                                Checking...
                            </span>
                        </div>
                        <div class="text-white small mt-1">
                            <span class="text-white-75 fw-medium">Your official public profile URL:</span> 
                            <code class="text-warning bg-black bg-opacity-40 border border-warning border-opacity-50 px-2.5 py-1 rounded-pill fw-bold fs-7 shadow-xs ms-1 d-inline-block" id="saranUrlPreview">saranindex.com/@<?php echo htmlspecialchars($cleanName ?: 'yourbrand'); ?></code>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="register<?php echo !empty($cleanName) ? '?handle=' . urlencode($cleanName) : ''; ?>" id="saranClaimBtn" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2.5 shadow-sm w-100 w-lg-auto d-inline-block text-center">
                    <i class="bi bi-patch-check-fill me-1"></i>Claim on Saran Index
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Selected Top Domain Extensions (.com, .in, .ai, .ai.in, .org, .co.in, etc.) -->
    <div class="mb-5 section-group" data-group="domain">
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="bi bi-globe2"></i>
                </div>
                <div>
                    <h3 class="fw-bold font-heading fs-5 mb-0 text-dark">Top <?php echo count($domainExtensions); ?> Domain Extensions (TLDs)</h3>
                    <div class="text-muted extra-small">Instant DNS check & 1-click domain booking</div>
                </div>
            </div>
            <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1 small fw-semibold"><?php echo count($domainExtensions); ?> Extensions</span>
        </div>

        <div class="row g-3" id="domainsGrid">
            <?php foreach ($domainExtensions as $key => $d): 
                $fullDomain = (!empty($cleanName) ? $cleanName : 'yourbrand') . $d['tld'];
            ?>
                <div class="col-lg-4 col-md-6 domain-item-col" data-type="domain" id="domain_col_<?php echo $key; ?>">
                    <div class="item-card d-flex flex-column justify-content-between h-100" id="card_domain_<?php echo $key; ?>">
                        <div>
                            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="state-indicator pending" id="ind_domain_<?php echo $key; ?>"></span>
                                    <span class="fw-bold fs-5 text-dark font-heading" id="name_domain_<?php echo $key; ?>">
                                        <?php echo htmlspecialchars($fullDomain); ?>
                                    </span>
                                </div>
                                <span class="badge bg-light text-muted border rounded-pill extra-small">
                                    <?php echo htmlspecialchars($d['badge']); ?>
                                </span>
                            </div>
                            <div class="text-muted extra-small mb-3">
                                <?php echo htmlspecialchars($d['label']); ?>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-2 border-top gap-2">
                            <span class="badge bg-light text-secondary border rounded-pill extra-small fw-semibold" id="status_domain_<?php echo $key; ?>">
                                Ready to check
                            </span>
                            <div class="d-flex align-items-center gap-1.5" id="actions_domain_<?php echo $key; ?>">
                                <a href="https://www.godaddy.com/domainsearch/find?checkAvail=1&domainToCheck=<?php echo urlencode($fullDomain); ?>" 
                                   target="_blank" rel="noopener" 
                                   class="btn btn-sm btn-outline-primary rounded-pill px-3 extra-small fw-bold" 
                                   title="Book on GoDaddy">
                                    <i class="bi bi-cart-plus me-1"></i>Book
                                </a>
                                <a href="https://www.namecheap.com/domains/registration/results/?domain=<?php echo urlencode($fullDomain); ?>" 
                                   target="_blank" rel="noopener" 
                                   class="btn btn-sm btn-light border rounded-pill px-2.5 extra-small text-muted" 
                                   title="Check on Namecheap">
                                    Namecheap
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 3. Selected Major Social Media & Content Platforms -->
    <div class="mb-5 section-group" data-group="social">
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="bi bi-share-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold font-heading fs-5 mb-0 text-dark">Top <?php echo count($socialPlatforms); ?> Social Media & Content Platforms</h3>
                    <div class="text-muted extra-small">Direct profile lookup & account reservation</div>
                </div>
            </div>
            <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 small fw-semibold"><?php echo count($socialPlatforms); ?> Platforms</span>
        </div>

        <div class="row g-3" id="socialsGrid">
            <?php foreach ($socialPlatforms as $key => $s): 
                $profileUrl = str_replace('{name}', !empty($cleanName) ? urlencode($cleanName) : 'yourbrand', $s['url_pattern']);
            ?>
                <div class="col-lg-4 col-md-6 social-item-col" data-type="social" id="social_col_<?php echo $key; ?>">
                    <div class="item-card d-flex flex-column justify-content-between h-100" id="card_social_<?php echo $key; ?>">
                        <div>
                            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 34px; height: 34px; background-color: <?php echo $s['brand_color']; ?>;">
                                        <i class="bi <?php echo $s['icon']; ?> fs-6"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark font-heading fs-6 mb-0"><?php echo htmlspecialchars($s['name']); ?></div>
                                        <a href="<?php echo htmlspecialchars($profileUrl); ?>" 
                                            target="_blank" rel="noopener" 
                                            class="text-decoration-none text-muted extra-small d-inline-flex align-items-center gap-1 hover-primary" 
                                            id="handle_link_social_<?php echo $key; ?>" 
                                            title="Click to manually verify @<?php echo htmlspecialchars(!empty($cleanName) ? $cleanName : 'yourbrand'); ?> on <?php echo htmlspecialchars($s['name']); ?>">
                                            <span id="handle_social_<?php echo $key; ?>">@<?php echo htmlspecialchars(!empty($cleanName) ? $cleanName : 'yourbrand'); ?></span>
                                            <i class="bi bi-box-arrow-up-right" style="font-size: 10px;"></i>
                                        </a>
                                    </div>
                                </div>
                                <span class="state-indicator pending mt-1" id="ind_social_<?php echo $key; ?>"></span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-2 border-top gap-2 mt-2">
                            <span class="badge bg-light text-secondary border rounded-pill extra-small fw-semibold" id="status_social_<?php echo $key; ?>">
                                Checking...
                            </span>
                            <div class="d-flex align-items-center gap-1.5" id="actions_social_<?php echo $key; ?>">
                                <a href="<?php echo htmlspecialchars($profileUrl); ?>" 
                                   target="_blank" rel="noopener" 
                                   class="btn btn-sm btn-outline-primary rounded-pill px-3 extra-small fw-semibold" 
                                   id="link_social_<?php echo $key; ?>" title="Open on <?php echo htmlspecialchars($s['name']); ?>">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Visit / Check
                                </a>
                                <a href="<?php echo htmlspecialchars($s['register_url']); ?>" 
                                   target="_blank" rel="noopener" 
                                   class="btn btn-sm btn-light border rounded-pill px-2.5 extra-small text-muted" 
                                   id="reg_social_<?php echo $key; ?>"
                                   title="Register on <?php echo htmlspecialchars($s['name']); ?>">
                                    Sign Up
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 4. Smart Name Variations Generator -->
    <div class="card border rounded-4 shadow-sm p-4 bg-white mb-4">
        <h3 class="fw-bold font-heading text-dark fs-5 mb-2 d-flex align-items-center gap-2">
            <i class="bi bi-magic text-primary"></i> Smart Indian Name Ideas & Regional Variations
        </h3>
        <p class="text-muted small mb-3">
            If your preferred exact name is taken, consider these localized variations for Saran District, Bihar & India:
        </p>

        <div class="d-flex flex-wrap gap-2" id="variationsBox">
            <?php 
            $baseForVars = !empty($cleanName) ? $cleanName : 'yourbrand';
            $suffixes = ['saran', 'chapra', 'bihar', 'india', 'bazaar', 'mart', 'official', 'hub', 'care', 'udyog', 'store'];
            foreach ($suffixes as $sfx): 
                $varName = $baseForVars . $sfx;
            ?>
                <button type="button" class="btn btn-light border btn-sm rounded-pill px-3 fw-semibold text-dark shadow-2xs hover-primary" onclick="setAndCheck('<?php echo $varName; ?>')">
                    <?php echo htmlspecialchars($varName); ?> <i class="bi bi-arrow-right-short text-primary"></i>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Register on Saran Index Call To Action -->
    <div class="card border-0 rounded-4 shadow-sm overflow-hidden text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2 extra-small">
                        <i class="bi bi-shield-lock-fill me-1"></i>OFFICIAL DISTRICT REPERTORY
                    </div>
                    <h2 class="fw-bolder font-heading text-white fs-3 mb-2">Secure Your Brand on Saran Index Today</h2>
                    <p class="text-white-50 small mb-0" style="max-width: 650px;">
                        Lock in your unique @username handle on Saran Index so clients, patients, and residents in Chapra can find your verified profile, phone number, and address.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-column flex-sm-row flex-lg-column gap-2">
                        <a href="register<?php echo !empty($cleanName) ? '?handle=' . urlencode($cleanName) : ''; ?>" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                            <i class="bi bi-person-plus-fill me-1"></i>Register Free @Handle
                        </a>
                        <a href="add-contact" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold extra-small">
                            <i class="bi bi-building-add me-1"></i>Add Directory Business Listing
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Interactive JavaScript Check Engine -->
<script>
const DOMAIN_KEYS = <?php echo json_encode(array_keys($domainExtensions)); ?>;
const SOCIAL_KEYS = <?php echo json_encode(array_keys($socialPlatforms)); ?>;
const SOCIAL_PATTERNS = {
    facebook: 'https://www.facebook.com/{name}',
    x: 'https://x.com/{name}',
    instagram: 'https://www.instagram.com/{name}/',
    youtube: 'https://www.youtube.com/@{name}',
    telegram: 'https://t.me/{name}',
    linkedin: 'https://www.linkedin.com/in/{name}',
    github: 'https://github.com/{name}',
    pinterest: 'https://www.pinterest.com/{name}/',
    reddit: 'https://www.reddit.com/user/{name}',
    threads: 'https://www.threads.net/@{name}',
    blogger: 'https://{name}.blogspot.com',
    medium: 'https://medium.com/@{name}'
};
let currentSearchName = "<?php echo htmlspecialchars($cleanName); ?>";

function setAndCheck(name) {
    document.getElementById('nameInput').value = name;
    runAvailabilityCheck(name);
}

function handleNameSearch(e) {
    if (e) e.preventDefault();
    const rawVal = document.getElementById('nameInput').value.trim();
    const cleanVal = rawVal.replace(/[^a-zA-Z0-9_-]/g, '').toLowerCase();
    if (!cleanVal) {
        alert('Please enter a valid brand or username.');
        return;
    }
    runAvailabilityCheck(cleanVal);
}

function runAvailabilityCheck(name) {
    currentSearchName = name;
    
    // Update URL query string without reloading page
    const newUrl = window.location.pathname + '?name=' + encodeURIComponent(name);
    window.history.pushState({ path: newUrl }, '', newUrl);

    // Update Titles and Previews
    document.getElementById('currentSearchTitle').innerHTML = 'Availability for "<span class="text-primary">' + escapeHtml(name) + '</span>"';
    document.getElementById('saranHandlePreview').textContent = '@' + name;
    document.getElementById('saranUrlPreview').textContent = 'saranindex.com/@' + name;
    document.getElementById('saranClaimBtn').href = 'register?handle=' + encodeURIComponent(name);

    // Update Variations Box
    updateVariationsBox(name);

    // Reset all status badges to checking...
    resetUIForChecking(name);

    // 1. Check Saran Index DB Handle
    checkSaranIndexHandle(name);

    // 2. Check 10 Domains Asynchronously
    DOMAIN_KEYS.forEach(key => {
        checkDomainItem(name, key);
    });

    // 3. Check 10 Social Media Platforms Asynchronously
    SOCIAL_KEYS.forEach(key => {
        checkSocialItem(name, key);
    });
}

function resetUIForChecking(name) {
    // Domains
    DOMAIN_KEYS.forEach(key => {
        const fullDomain = name + '.' + key.replace('_', '.');
        const nameEl = document.getElementById('name_domain_' + key);
        if (nameEl) nameEl.textContent = fullDomain;

        const card = document.getElementById('card_domain_' + key);
        if (card) card.className = 'item-card d-flex flex-column justify-content-between h-100 state-checking';

        const ind = document.getElementById('ind_domain_' + key);
        if (ind) ind.className = 'state-indicator checking';

        const status = document.getElementById('status_domain_' + key);
        if (status) {
            status.className = 'badge bg-primary-subtle text-primary border rounded-pill extra-small fw-semibold';
            status.textContent = 'Checking DNS...';
        }

        const actions = document.getElementById('actions_domain_' + key);
        if (actions) {
            actions.innerHTML = `
                <a href="https://www.godaddy.com/domainsearch/find?checkAvail=1&domainToCheck=${encodeURIComponent(fullDomain)}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary rounded-pill px-3 extra-small fw-bold">Book</a>
                <a href="https://www.namecheap.com/domains/registration/results/?domain=${encodeURIComponent(fullDomain)}" target="_blank" rel="noopener" class="btn btn-sm btn-light border rounded-pill px-2.5 extra-small text-muted">Namecheap</a>
            `;
        }
    });

    // Socials
    SOCIAL_KEYS.forEach(key => {
        const pattern = SOCIAL_PATTERNS[key] || ('https://' + key + '.com/' + encodeURIComponent(name));
        const profileUrl = pattern.replace('{name}', encodeURIComponent(name));

        const handleEl = document.getElementById('handle_social_' + key);
        if (handleEl) handleEl.textContent = '@' + name;

        const handleLink = document.getElementById('handle_link_social_' + key);
        if (handleLink) {
            handleLink.href = profileUrl;
            handleLink.title = 'Click to manually verify @' + name + ' on this platform';
        }

        const link = document.getElementById('link_social_' + key);
        if (link) link.href = profileUrl;

        const card = document.getElementById('card_social_' + key);
        if (card) card.className = 'item-card d-flex flex-column justify-content-between h-100 state-checking';

        const ind = document.getElementById('ind_social_' + key);
        if (ind) ind.className = 'state-indicator checking';

        const status = document.getElementById('status_social_' + key);
        if (status) {
            status.className = 'badge bg-primary-subtle text-primary border rounded-pill extra-small fw-semibold';
            status.textContent = 'Checking...';
        }
    });
}

function checkSaranIndexHandle(name) {
    const badge = document.getElementById('saranHandleBadge');
    if (badge) {
        badge.className = 'badge bg-light text-dark fw-bold rounded-pill px-2.5 py-1 extra-small';
        badge.textContent = 'Checking...';
    }

    fetch('api/check_name_availability.php?name=' + encodeURIComponent(name) + '&item=saranindex')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (data.handle_available) {
                    badge.className = 'badge bg-success text-white fw-bold rounded-pill px-3 py-1 extra-small shadow-xs';
                    badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>AVAILABLE ON SARAN INDEX';
                } else {
                    badge.className = 'badge bg-danger text-white fw-bold rounded-pill px-3 py-1 extra-small shadow-xs';
                    badge.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i>ALREADY CLAIMED';
                }
            }
        })
        .catch(err => {
            console.error(err);
        });
}

function checkDomainItem(name, key) {
    const fullDomain = name + '.' + key.replace('_', '.');
    fetch('api/check_name_availability.php?name=' + encodeURIComponent(name) + '&item=' + encodeURIComponent(key))
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const card = document.getElementById('card_domain_' + key);
                const ind = document.getElementById('ind_domain_' + key);
                const status = document.getElementById('status_domain_' + key);
                const actions = document.getElementById('actions_domain_' + key);
                const domainStr = data.name || fullDomain;

                if (data.available) {
                    if (card) card.className = 'item-card d-flex flex-column justify-content-between h-100 state-available';
                    if (ind) ind.className = 'state-indicator available';
                    if (status) {
                        status.className = 'badge bg-success text-white border-0 rounded-pill extra-small fw-bold px-2.5';
                        status.innerHTML = '<i class="bi bi-check-lg me-1"></i>AVAILABLE';
                    }
                    if (actions) {
                        const godaddyUrl = data.buy_url_godaddy || ('https://www.godaddy.com/domainsearch/find?checkAvail=1&domainToCheck=' + encodeURIComponent(domainStr));
                        const namecheapUrl = data.buy_url_namecheap || ('https://www.namecheap.com/domains/registration/results/?domain=' + encodeURIComponent(domainStr));
                        actions.innerHTML = `
                            <a href="${godaddyUrl}" target="_blank" rel="noopener" class="btn btn-sm btn-success rounded-pill px-3 extra-small fw-bold shadow-xs">
                                <i class="bi bi-cart-plus me-1"></i>Book Domain
                            </a>
                            <a href="${namecheapUrl}" target="_blank" rel="noopener" class="btn btn-sm btn-light border rounded-pill px-2.5 extra-small text-muted">
                                Namecheap
                            </a>
                        `;
                    }
                } else {
                    if (card) card.className = 'item-card d-flex flex-column justify-content-between h-100 state-taken';
                    if (ind) ind.className = 'state-indicator taken';
                    if (status) {
                        status.className = 'badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill extra-small fw-semibold px-2.5';
                        status.innerHTML = '<i class="bi bi-lock-fill me-1"></i>TAKEN / ACTIVE';
                    }
                    if (actions) {
                        const siteUrl = data.website_url || ('https://' + domainStr);
                        const whoisUrl = data.whois_url || ('https://www.whois.com/whois/' + encodeURIComponent(domainStr));
                        actions.innerHTML = `
                            <a href="${siteUrl}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-danger rounded-pill px-3 extra-small fw-semibold" title="Visit active site ${domainStr}">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Visit Website
                            </a>
                            <a href="${whoisUrl}" target="_blank" rel="noopener" class="btn btn-sm btn-light border rounded-pill px-2.5 extra-small text-muted" title="View WHOIS records">
                                <i class="bi bi-search me-1"></i>WHOIS
                            </a>
                        `;
                    }
                }
            }
        })
        .catch(err => {
            console.error(err);
        });
}

function checkSocialItem(name, key) {
    const pattern = SOCIAL_PATTERNS[key] || ('https://' + key + '.com/' + encodeURIComponent(name));
    const fallbackProfileUrl = pattern.replace('{name}', encodeURIComponent(name));

    fetch('api/check_name_availability.php?name=' + encodeURIComponent(name) + '&item=' + encodeURIComponent(key))
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const card = document.getElementById('card_social_' + key);
                const ind = document.getElementById('ind_social_' + key);
                const status = document.getElementById('status_social_' + key);
                const link = document.getElementById('link_social_' + key);
                const reg = document.getElementById('reg_social_' + key);
                const handleLink = document.getElementById('handle_link_social_' + key);
                const profileUrl = data.profile_url || fallbackProfileUrl;

                if (handleLink) {
                    handleLink.href = profileUrl;
                }
                if (link) {
                    link.href = profileUrl;
                }
                if (reg && data.register_url) {
                    reg.href = data.register_url;
                }

                if (data.state === 'available') {
                    if (card) card.className = 'item-card d-flex flex-column justify-content-between h-100 state-available';
                    if (ind) ind.className = 'state-indicator available';
                    if (status) {
                        status.className = 'badge bg-success text-white border-0 rounded-pill extra-small fw-bold px-2.5';
                        status.innerHTML = '<i class="bi bi-check-lg me-1"></i>AVAILABLE';
                    }
                    if (link) {
                        link.className = 'btn btn-sm btn-outline-success rounded-pill px-3 extra-small fw-semibold';
                        link.innerHTML = '<i class="bi bi-plus-circle me-1"></i>Claim';
                        link.href = data.register_url || data.profile_url;
                    }
                } else if (data.state === 'taken') {
                    if (card) card.className = 'item-card d-flex flex-column justify-content-between h-100 state-taken';
                    if (ind) ind.className = 'state-indicator taken';
                    if (status) {
                        status.className = 'badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill extra-small fw-semibold px-2.5';
                        status.innerHTML = '<i class="bi bi-lock-fill me-1"></i>TAKEN';
                    }
                    if (link) {
                        link.className = 'btn btn-sm btn-outline-danger rounded-pill px-3 extra-small fw-semibold';
                        link.innerHTML = '<i class="bi bi-box-arrow-up-right me-1"></i>View Profile';
                        link.href = data.profile_url;
                    }
                } else {
                    if (card) card.className = 'item-card d-flex flex-column justify-content-between h-100';
                    if (ind) ind.className = 'state-indicator pending';
                    if (status) {
                        status.className = 'badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill extra-small fw-semibold';
                        status.innerHTML = '<i class="bi bi-box-arrow-up-right me-1"></i>Direct Verify';
                    }
                    if (link) {
                        link.className = 'btn btn-sm btn-outline-primary rounded-pill px-3 extra-small fw-semibold';
                        link.innerHTML = '<i class="bi bi-box-arrow-up-right me-1"></i>Open & Check';
                        link.href = data.profile_url;
                    }
                }
            }
        })
        .catch(err => {
            console.error(err);
            const status = document.getElementById('status_social_' + key);
            if (status) {
                status.className = 'badge bg-light text-secondary border rounded-pill extra-small fw-semibold';
                status.textContent = 'Direct Check';
            }
        });
}

function updateVariationsBox(name) {
    const box = document.getElementById('variationsBox');
    if (!box) return;
    const suffixes = ['saran', 'chapra', 'bihar', 'official', 'hub', 'mart', 'india', 'online', 'care'];
    let html = '';
    suffixes.forEach(sfx => {
        const v = name + sfx;
        html += `<button type="button" class="btn btn-light border btn-sm rounded-pill px-3 fw-semibold text-dark shadow-2xs hover-primary" onclick="setAndCheck('${v}')">${escapeHtml(v)} <i class="bi bi-arrow-right-short text-primary"></i></button>`;
    });
    box.innerHTML = html;
}

function filterResults(filter, btn) {
    document.querySelectorAll('.nav-pill-custom').forEach(el => el.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const domainCols = document.querySelectorAll('.domain-item-col');
    const socialCols = document.querySelectorAll('.social-item-col');
    const domainGroup = document.querySelector('.section-group[data-group="domain"]');
    const socialGroup = document.querySelector('.section-group[data-group="social"]');

    if (filter === 'all') {
        if (domainGroup) domainGroup.style.display = 'block';
        if (socialGroup) socialGroup.style.display = 'block';
        domainCols.forEach(col => col.style.display = 'block');
        socialCols.forEach(col => col.style.display = 'block');
    } else if (filter === 'domain') {
        if (domainGroup) domainGroup.style.display = 'block';
        if (socialGroup) socialGroup.style.display = 'none';
        domainCols.forEach(col => col.style.display = 'block');
    } else if (filter === 'social') {
        if (domainGroup) domainGroup.style.display = 'none';
        if (socialGroup) socialGroup.style.display = 'block';
        socialCols.forEach(col => col.style.display = 'block');
    } else if (filter === 'available') {
        if (domainGroup) domainGroup.style.display = 'block';
        if (socialGroup) socialGroup.style.display = 'block';
        domainCols.forEach(col => {
            const card = col.querySelector('.item-card');
            col.style.display = (card && card.classList.contains('state-available')) ? 'block' : 'none';
        });
        socialCols.forEach(col => {
            const card = col.querySelector('.item-card');
            col.style.display = (card && card.classList.contains('state-available')) ? 'block' : 'none';
        });
    }
}

function escapeHtml(text) {
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Automatically trigger check on load if query name is present
document.addEventListener('DOMContentLoaded', function() {
    if (currentSearchName) {
        runAvailabilityCheck(currentSearchName);
    }
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : '';
$db = getDB();

$panchayat = null;
if (!empty($slug) && $db) {
    try {
        $stmt = $db->prepare("SELECT p.*, COALESCE(b.name_english, b.name) as block_name, b.hindi_name as block_hindi, b.slug as block_slug FROM panchayats p JOIN blocks b ON p.block_id = b.id WHERE p.slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $panchayat = $stmt->fetch();
    } catch (PDOException $e) {}
}

if ($panchayat) {
    $page_title = sanitizeInput($panchayat['panchayat_name']) . " Gram Panchayat - " . sanitizeInput($panchayat['block_name']) . " Block | Saran Index";
    $meta_description = "Gram Panchayat " . $panchayat['panchayat_name'] . " (" . $panchayat['hindi_name'] . ") in " . $panchayat['block_name'] . " Block, Saran District, Bihar. Explore villages (maujas), local contacts, and directory listings.";
} else {
    $page_title = "Gram Panchayat Directory - All 318 Panchayats of Saran District | Saran Index";
    $meta_description = "Complete digital directory of all 318 Gram Panchayats and 1,800+ villages across 20 blocks in Saran District (Chapra), Bihar.";
}

require_once __DIR__ . '/includes/header.php';
$blocks = getBlocks();
?>

<!-- Hero Section (matching index.php UI) -->
<section class="hero-wrapper position-relative text-center">
    <div class="container position-relative z-1">
        <?php if ($panchayat): ?>
            <!-- Single Panchayat Hero -->
            <div class="d-flex align-items-center justify-content-center gap-2 mb-3 flex-wrap">
                <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill shadow-sm">
                    <i class="bi bi-patch-check-fill me-1"></i> Verified Gram Panchayat
                </span>
                <?php if (!empty($panchayat['panchayat_samiti_no'])): ?>
                    <span class="badge bg-white text-dark fw-bold px-3 py-1.5 rounded-pill shadow-sm border border-warning">
                        <i class="bi bi-award-fill text-warning me-1"></i> Panchayat Samiti No: <?php echo sanitizeInput($panchayat['panchayat_samiti_no']); ?>
                    </span>
                <?php endif; ?>
            </div>

            <nav aria-label="breadcrumb" class="d-flex justify-content-center mb-3">
                <ol class="breadcrumb bg-white bg-opacity-10 px-3 py-1.5 rounded-pill mb-0 small border border-white border-opacity-10">
                    <li class="breadcrumb-item"><a href="./" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="blocks" class="text-white-50 text-decoration-none">Blocks</a></li>
                    <li class="breadcrumb-item"><a href="block/<?php echo urlencode($panchayat['block_slug']); ?>" class="text-white-50 text-decoration-none"><?php echo sanitizeInput($panchayat['block_name']); ?></a></li>
                    <li class="breadcrumb-item active text-warning fw-semibold" aria-current="page"><?php echo sanitizeInput($panchayat['panchayat_name']); ?></li>
                </ol>
            </nav>

            <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
                <?php echo sanitizeInput($panchayat['panchayat_name']); ?>
                <?php if (!empty($panchayat['hindi_name'])): ?>
                    <span class="text-white-50 fs-3 ms-1">(<?php echo sanitizeInput($panchayat['hindi_name']); ?>)</span>
                <?php endif; ?>
            </h1>

            <p class="lead text-white-50 font-heading fw-semibold mb-4 fs-4" style="color: #cbd5e1 !important;">
                Gram Panchayat • <?php echo sanitizeInput($panchayat['block_name']); ?> Block (<?php echo sanitizeInput($panchayat['block_hindi']); ?>)
            </p>

            <!-- Villages / Maujas Badges -->
            <?php if (!empty($panchayat['village']) || !empty($panchayat['village_hindi'])): 
                $villageListEn = !empty($panchayat['village']) ? explode(',', $panchayat['village']) : [];
                $villageListHi = !empty($panchayat['village_hindi']) ? explode(',', $panchayat['village_hindi']) : [];
                $vCount = max(count($villageListEn), count($villageListHi));
            ?>
                <div class="p-4 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur max-w-900 mx-auto shadow-lg text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-white border-opacity-10 pb-2">
                        <span class="fw-bold text-warning small text-uppercase tracking-wider">
                            <i class="bi bi-houses-fill me-1"></i> Villages (Maujas) under this Panchayat (<?php echo $vCount; ?>)
                        </span>
                        <span class="badge bg-warning text-dark rounded-pill"><?php echo $vCount; ?> Villages</span>
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <?php 
                        for ($i = 0; $i < $vCount; $i++):
                            $vEn = isset($villageListEn[$i]) ? trim($villageListEn[$i]) : '';
                            $vHi = isset($villageListHi[$i]) ? trim($villageListHi[$i]) : '';
                            if (empty($vEn) && empty($vHi)) continue;
                            $displayName = !empty($vEn) ? $vEn : $vHi;
                            if (!empty($vHi) && !empty($vEn) && strcasecmp($vEn, $vHi) !== 0) {
                                $displayName .= " ({$vHi})";
                            }
                        ?>
                            <span class="badge bg-dark bg-opacity-50 text-light border border-white border-opacity-20 px-3 py-2 rounded-pill font-monospace small shadow-sm">
                                <i class="bi bi-geo-alt-fill text-warning me-1"></i><?php echo sanitizeInput($displayName); ?>
                            </span>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="mt-4">
                <a href="panchayat" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> All 318 Gram Panchayats
                </a>
            </div>

        <?php else: ?>
            <!-- All Panchayats Index Hero -->
            <div class="d-inline-flex align-items-center mb-3 brand-badge">
                <i class="bi bi-patch-check-fill text-warning me-2 fs-6"></i>
                <span>Official Local Governance Directory • Saran District</span>
            </div>

            <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
                Saran Gram Panchayats
            </h1>
            
            <p class="lead text-white-50 font-heading fw-semibold mb-3 fs-3" style="color: #cbd5e1 !important;">
                Digital Governance & Local Directory
            </p>
            
            <p class="text-white-50 mx-auto mb-4" style="max-width: 680px; font-size: 1.05rem;">
                Explore all <strong>318 Gram Panchayats</strong> and <strong>1,800+ Villages (Maujas)</strong> across all 20 blocks in Saran District (Chapra), Bihar.
            </p>

            <!-- Search Bar Component (matching index.php search-card style) -->
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-11">
                    <div class="search-card d-flex align-items-center gap-2">
                        <button type="button" class="btn mic-btn flex-shrink-0" id="micButtonPanchayat" title="Voice Search">
                            <i class="bi bi-mic-fill fs-5"></i>
                        </button>
                        
                        <input type="text" id="panchayatSearchInput" class="form-control search-input flex-grow-1" placeholder="Search by Panchayat name, Hindi name, or Village (Mauja)..." autocomplete="off" onkeyup="filterPanchayats()">
                        
                        <select id="blockFilterSelect" class="form-select border-0 bg-light rounded-pill px-3 fw-medium d-none d-md-block" style="max-width: 200px;" onchange="filterPanchayats()">
                            <option value="">All 20 Blocks</option>
                            <?php foreach ($blocks as $blk): ?>
                                <option value="<?php echo sanitizeInput($blk['slug']); ?>">
                                    <?php echo sanitizeInput($blk['block_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button type="button" class="btn search-submit-btn flex-shrink-0" onclick="filterPanchayats()">
                            <i class="bi bi-search me-1"></i>Search
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if (!$panchayat): 
    $allPanchayats = getPanchayats();
?>
    <!-- 20 Saran Blocks Quick Filters Section (matching index.php UI) -->
    <section class="py-4 bg-white border-bottom">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1 rounded-pill small">Filter by Block</span>
                <button type="button" class="btn btn-link text-decoration-none text-muted small p-0" onclick="clearSearch()"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter</button>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold block-filter-btn active" data-block="" onclick="selectBlockFilter('', this)">
                    All 318 Panchayats
                </button>
                <?php foreach ($blocks as $blk): ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-medium block-filter-btn" data-block="<?php echo sanitizeInput($blk['slug']); ?>" onclick="selectBlockFilter('<?php echo sanitizeInput($blk['slug']); ?>', this)">
                        <?php echo sanitizeInput($blk['block_name']); ?> (<?php echo $blk['total_panchayats']; ?>)
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Gram Panchayats Grid Section (matching index.php UI layout) -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill uppercase tracking-wider small">Local Governance</span>
                    <h2 class="fw-bold font-heading text-dark mt-2 fs-2 mb-0">Gram Panchayats of Saran</h2>
                </div>
                <div class="badge bg-primary text-white fw-bold fs-6 px-3 py-2 rounded-pill shadow-sm" id="panchayatCountBadge">
                    Showing <?php echo count($allPanchayats); ?> Panchayats
                </div>
            </div>

            <div class="row g-4" id="panchayatsGrid">
                <?php foreach ($allPanchayats as $p): 
                    $vEn = !empty($p['village']) ? explode(',', $p['village']) : [];
                    $vHi = !empty($p['village_hindi']) ? explode(',', $p['village_hindi']) : [];
                    $vCount = max(count($vEn), count($vHi));
                    $vSearchStr = strtolower(($p['panchayat_name'] ?? '') . ' ' . ($p['hindi_name'] ?? '') . ' ' . ($p['village'] ?? '') . ' ' . ($p['village_hindi'] ?? '') . ' ' . ($p['mukhiya_name'] ?? '') . ' ' . ($p['sarpanch_name'] ?? ''));
                ?>
                    <div class="col-lg-4 col-md-6 panchayat-card-item" 
                         data-block="<?php echo sanitizeInput($p['block_slug']); ?>" 
                         data-search="<?php echo htmlspecialchars($vSearchStr, ENT_QUOTES); ?>">
                        <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition-all overflow-hidden bg-white">
                            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex align-items-start justify-content-between">
                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle fw-semibold px-2.5 py-1 rounded-pill small">
                                    <i class="bi bi-geo-alt-fill text-warning me-1"></i><?php echo sanitizeInput($p['block_name']); ?> Block
                                </span>
                                <span class="badge bg-secondary-subtle text-secondary fw-medium px-2.5 py-1 rounded-pill small" title="<?php echo $vCount; ?> Villages under this Panchayat">
                                    <i class="bi bi-houses me-1"></i><?php echo $vCount; ?> Villages
                                </span>
                            </div>

                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h4 class="fw-bold text-dark font-heading mb-1 fs-5">
                                        <a href="panchayat/<?php echo urlencode($p['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                            <?php echo sanitizeInput($p['panchayat_name']); ?>
                                        </a>
                                    </h4>
                                    <?php if (!empty($p['hindi_name'])): ?>
                                        <div class="text-muted fw-semibold small mb-2"><?php echo sanitizeInput($p['hindi_name']); ?> ग्राम पंचायत</div>
                                    <?php endif; ?>
                                    <?php if (!empty($p['panchayat_samiti_no'])): ?>
                                        <div class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold px-2.5 py-1 rounded-pill small mb-2">
                                            <i class="bi bi-award me-1"></i>Samiti No: <?php echo sanitizeInput($p['panchayat_samiti_no']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Elected Representatives Snippet -->
                                    <?php if (!empty($p['mukhiya_name']) || !empty($p['sarpanch_name'])): ?>
                                        <div class="mb-3 p-2.5 rounded-3 bg-light border small">
                                            <?php if (!empty($p['mukhiya_name'])): ?>
                                                <div class="d-flex align-items-center justify-content-between <?php echo !empty($p['sarpanch_name']) ? 'mb-1 pb-1 border-bottom border-secondary-subtle border-opacity-25' : ''; ?>">
                                                    <span class="text-muted fw-bold" style="font-size: 0.76rem;"><i class="bi bi-person-badge-fill text-primary me-1"></i>मुखिया:</span>
                                                    <span class="fw-bold text-dark" style="font-size: 0.84rem;"><?php echo sanitizeInput($p['mukhiya_name']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($p['sarpanch_name'])): ?>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="text-muted fw-bold" style="font-size: 0.76rem;"><i class="bi bi-bank2 text-warning me-1"></i>सरपंच:</span>
                                                    <span class="fw-bold text-dark" style="font-size: 0.84rem;"><?php echo sanitizeInput($p['sarpanch_name']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Villages List Snippet -->
                                    <?php if ($vCount > 0): ?>
                                        <div class="mb-3 p-2.5 rounded-3 bg-light border small">
                                            <div class="text-muted fw-bold mb-1" style="font-size: 0.75rem;">
                                                <i class="bi bi-pin-map text-warning me-1"></i> Key Villages / Maujas:
                                            </div>
                                            <div class="text-secondary lh-sm" style="font-size: 0.82rem;">
                                                <?php 
                                                $shownVillages = [];
                                                for ($i = 0; $i < min(4, $vCount); $i++) {
                                                    $val = !empty($vEn[$i]) ? trim($vEn[$i]) : (!empty($vHi[$i]) ? trim($vHi[$i]) : '');
                                                    if ($val) $shownVillages[] = $val;
                                                }
                                                echo sanitizeInput(implode(', ', $shownVillages));
                                                if ($vCount > 4) {
                                                    echo ' <span class="fw-bold text-primary">+' . ($vCount - 4) . ' more</span>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="pt-3 border-top d-flex align-items-center justify-content-between mt-2">
                                    <a href="panchayat/<?php echo urlencode($p['slug']); ?>" class="btn btn-outline-primary rounded-pill px-3 py-1.5 btn-sm fw-semibold w-100">
                                        View Panchayat & Leadership <i class="bi bi-chevron-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="noResultsAlert" class="alert alert-info rounded-4 text-center py-5 d-none mt-4 shadow-sm">
                <i class="bi bi-search fs-1 text-primary mb-2 d-block"></i>
                <h5 class="fw-bold text-dark mb-1">No Gram Panchayats found</h5>
                <p class="text-muted mb-3">Try adjusting your search query or select another block filter.</p>
                <button class="btn btn-primary rounded-pill px-4 py-2 fw-semibold" onclick="clearSearch()">Clear Filters</button>
            </div>
        </div>
    </section>

    <script>
    function filterPanchayats() {
        const query = document.getElementById('panchayatSearchInput').value.toLowerCase().trim();
        const block = document.getElementById('blockFilterSelect').value.toLowerCase().trim();
        
        const items = document.querySelectorAll('.panchayat-card-item');
        let visibleCount = 0;

        items.forEach(item => {
            const itemBlock = item.getAttribute('data-block').toLowerCase();
            const itemSearch = item.getAttribute('data-search').toLowerCase();

            const matchesQuery = query === '' || itemSearch.includes(query);
            const matchesBlock = block === '' || itemBlock === block;

            if (matchesQuery && matchesBlock) {
                item.classList.remove('d-none');
                visibleCount++;
            } else {
                item.classList.add('d-none');
            }
        });

        document.getElementById('panchayatCountBadge').innerText = 'Showing ' + visibleCount + ' Panchayats';
        
        const noResults = document.getElementById('noResultsAlert');
        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }

    function selectBlockFilter(blockSlug, btnElement) {
        document.getElementById('blockFilterSelect').value = blockSlug;
        document.querySelectorAll('.block-filter-btn').forEach(btn => {
            btn.classList.remove('btn-primary', 'active');
            btn.classList.add('btn-outline-secondary');
        });
        if (btnElement) {
            btnElement.classList.remove('btn-outline-secondary');
            btnElement.classList.add('btn-primary', 'active');
        }
        filterPanchayats();
    }

    function clearSearch() {
        document.getElementById('panchayatSearchInput').value = '';
        document.getElementById('blockFilterSelect').value = '';
        document.querySelectorAll('.block-filter-btn').forEach((btn, idx) => {
            if (idx === 0) {
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-primary', 'active');
            } else {
                btn.classList.remove('btn-primary', 'active');
                btn.classList.add('btn-outline-secondary');
            }
        });
        filterPanchayats();
    }
    </script>

<?php else: ?>
    <!-- Single Panchayat View (matching index.php UI) -->
    <section class="py-5 bg-light">
        <div class="container">

            <!-- Elected Representatives: Mukhiya & Sarpanch -->
            <?php if (!empty($panchayat['mukhiya_name']) || !empty($panchayat['sarpanch_name'])): 
                $isLoggedIn = function_exists('isUserLoggedIn') && isUserLoggedIn();
                $loginRedirectUrl = 'login?redirect=' . urlencode('panchayat/' . ($panchayat['slug'] ?? ''));
            ?>
                <div class="mb-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small shadow-xs">
                                <i class="bi bi-people-fill me-1"></i> Elected Local Governance
                            </span>
                            <span class="badge bg-primary text-white fw-bold px-3 py-1.5 rounded-pill small shadow-xs">
                                <i class="bi bi-calendar-check me-1"></i> Tenure: 2021 - 2026
                            </span>
                        </div>
                        <h2 class="fw-bold font-heading text-dark mt-1">Panchayat Leadership & Representatives</h2>
                        <p class="text-muted mx-auto" style="max-width: 580px;">Official elected representatives of <?php echo sanitizeInput($panchayat['panchayat_name']); ?> Gram Panchayat & Gram Kacheri (SEC Bihar Public Election Record • 2021 - 2026 Tenure).</p>
                    </div>

                    <div class="row g-4 justify-content-center">
                        <!-- Mukhiya Card -->
                        <?php if (!empty($panchayat['mukhiya_name'])): 
                            $mMob = preg_replace('/[^0-9]/', '', $panchayat['mukhiya_mobile'] ?? '');
                        ?>
                            <div class="col-lg-6">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-lift transition-all">
                                    <div class="p-4 border-bottom bg-primary-subtle bg-opacity-25 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-primary text-white p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 52px; height: 52px;">
                                                <i class="bi bi-person-badge-fill fs-3"></i>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                    <span class="badge bg-primary text-white fw-semibold rounded-pill px-2.5 py-1 small">ग्राम पंचायत के मुखिया</span>
                                                    <span class="badge bg-warning text-dark fw-semibold rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">2021 - 2026</span>
                                                </div>
                                                <h4 class="fw-bold font-heading text-dark mb-0 mt-1"><?php echo sanitizeInput($panchayat['mukhiya_name']); ?></h4>
                                            </div>
                                        </div>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold small">
                                            <i class="bi bi-check-circle-fill me-1"></i>Elected
                                        </span>
                                    </div>
                                    <div class="card-body p-4">
                                        <ul class="list-unstyled mb-4 small text-secondary">
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-hourglass-split text-primary me-2 mt-0.5"></i>
                                                <div><strong>Elected Tenure:</strong> <span class="badge bg-primary-subtle text-primary fw-bold">2021 - 2026</span> (Current Term)</div>
                                            </li>
                                            <?php if (!empty($panchayat['mukhiya_father_husband'])): ?>
                                                <li class="mb-2 d-flex align-items-start">
                                                    <i class="bi bi-person-fill text-muted me-2 mt-0.5"></i>
                                                    <div><strong>Father / Husband:</strong> <?php echo sanitizeInput($panchayat['mukhiya_father_husband']); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (!empty($panchayat['mukhiya_category']) || !empty($panchayat['mukhiya_reservation'])): ?>
                                                <li class="mb-2 d-flex align-items-start">
                                                    <i class="bi bi-tag-fill text-muted me-2 mt-0.5"></i>
                                                    <div><strong>Category:</strong> <?php echo sanitizeInput($panchayat['mukhiya_category'] ?? ''); ?> <?php if (!empty($panchayat['mukhiya_reservation'])): ?>(<?php echo sanitizeInput($panchayat['mukhiya_reservation']); ?>)<?php endif; ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (!empty($panchayat['mukhiya_gender']) || !empty($panchayat['mukhiya_age'])): ?>
                                                <li class="mb-2 d-flex align-items-start">
                                                    <i class="bi bi-info-circle-fill text-muted me-2 mt-0.5"></i>
                                                    <div><strong>Profile:</strong> <?php echo sanitizeInput($panchayat['mukhiya_gender'] ?? ''); ?><?php if (!empty($panchayat['mukhiya_age'])): ?>, Age: <?php echo sanitizeInput($panchayat['mukhiya_age']); ?> yrs<?php endif; ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (!empty($panchayat['mukhiya_address'])): ?>
                                                <li class="mb-2 d-flex align-items-start">
                                                    <i class="bi bi-geo-alt-fill text-muted me-2 mt-0.5"></i>
                                                    <div><strong>Address:</strong> <?php echo sanitizeInput($panchayat['mukhiya_address']); ?></div>
                                                </li>
                                            <?php endif; ?>
                                        </ul>

                                        <?php if (!empty($mMob)): ?>
                                            <?php if ($isLoggedIn): ?>
                                                <div class="d-flex gap-2">
                                                    <a href="tel:<?php echo $mMob; ?>" class="btn btn-primary rounded-pill px-3 py-2 flex-grow-1 fw-semibold btn-sm shadow-xs">
                                                        <i class="bi bi-telephone-fill me-1"></i> Call <?php echo $mMob; ?>
                                                    </a>
                                                    <a href="https://wa.me/91<?php echo $mMob; ?>" target="_blank" rel="noopener" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold btn-sm">
                                                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="p-2.5 rounded-3 bg-light border text-center">
                                                    <div class="text-muted small mb-2">
                                                        <i class="bi bi-shield-lock text-warning me-1"></i> Mobile: <span class="font-monospace text-secondary fw-semibold">+91 XXXXX •••••</span>
                                                    </div>
                                                    <a href="<?php echo $loginRedirectUrl; ?>" class="btn btn-outline-primary rounded-pill px-3 py-1.5 w-100 fw-semibold btn-sm">
                                                        <i class="bi bi-person-check-fill me-1"></i> Log In to View Number & Call
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Sarpanch Card -->
                        <?php if (!empty($panchayat['sarpanch_name'])): 
                            $sMob = preg_replace('/[^0-9]/', '', $panchayat['sarpanch_mobile'] ?? '');
                        ?>
                            <div class="col-lg-6">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-lift transition-all">
                                    <div class="p-4 border-bottom bg-warning-subtle bg-opacity-25 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-warning text-dark p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 52px; height: 52px;">
                                                <i class="bi bi-bank2 fs-3"></i>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                    <span class="badge bg-warning text-dark fw-semibold rounded-pill px-2.5 py-1 small">ग्राम कचहरी के सरपंच</span>
                                                    <span class="badge bg-dark text-white fw-semibold rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">2021 - 2026</span>
                                                </div>
                                                <h4 class="fw-bold font-heading text-dark mb-0 mt-1"><?php echo sanitizeInput($panchayat['sarpanch_name']); ?></h4>
                                            </div>
                                        </div>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold small">
                                            <i class="bi bi-check-circle-fill me-1"></i>Elected
                                        </span>
                                    </div>
                                    <div class="card-body p-4">
                                        <ul class="list-unstyled mb-4 small text-secondary">
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-hourglass-split text-warning me-2 mt-0.5"></i>
                                                <div><strong>Elected Tenure:</strong> <span class="badge bg-warning-subtle text-dark fw-bold">2021 - 2026</span> (Current Term)</div>
                                            </li>
                                            <?php if (!empty($panchayat['sarpanch_father_husband'])): ?>
                                                <li class="mb-2 d-flex align-items-start">
                                                    <i class="bi bi-person-fill text-muted me-2 mt-0.5"></i>
                                                    <div><strong>Father / Husband:</strong> <?php echo sanitizeInput($panchayat['sarpanch_father_husband']); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (!empty($panchayat['sarpanch_category']) || !empty($panchayat['sarpanch_reservation'])): ?>
                                                <li class="mb-2 d-flex align-items-start">
                                                    <i class="bi bi-tag-fill text-muted me-2 mt-0.5"></i>
                                                    <div><strong>Category:</strong> <?php echo sanitizeInput($panchayat['sarpanch_category'] ?? ''); ?> <?php if (!empty($panchayat['sarpanch_reservation'])): ?>(<?php echo sanitizeInput($panchayat['sarpanch_reservation']); ?>)<?php endif; ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (!empty($panchayat['sarpanch_gender']) || !empty($panchayat['sarpanch_age'])): ?>
                                                <li class="mb-2 d-flex align-items-start">
                                                    <i class="bi bi-info-circle-fill text-muted me-2 mt-0.5"></i>
                                                    <div><strong>Profile:</strong> <?php echo sanitizeInput($panchayat['sarpanch_gender'] ?? ''); ?><?php if (!empty($panchayat['sarpanch_age'])): ?>, Age: <?php echo sanitizeInput($panchayat['sarpanch_age']); ?> yrs<?php endif; ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (!empty($panchayat['sarpanch_address'])): ?>
                                                <li class="mb-2 d-flex align-items-start">
                                                    <i class="bi bi-geo-alt-fill text-muted me-2 mt-0.5"></i>
                                                    <div><strong>Address:</strong> <?php echo sanitizeInput($panchayat['sarpanch_address']); ?></div>
                                                </li>
                                            <?php endif; ?>
                                        </ul>

                                        <?php if (!empty($sMob)): ?>
                                            <?php if ($isLoggedIn): ?>
                                                <div class="d-flex gap-2">
                                                    <a href="tel:<?php echo $sMob; ?>" class="btn btn-warning text-dark rounded-pill px-3 py-2 flex-grow-1 fw-semibold btn-sm shadow-xs">
                                                        <i class="bi bi-telephone-fill me-1"></i> Call <?php echo $sMob; ?>
                                                    </a>
                                                    <a href="https://wa.me/91<?php echo $sMob; ?>" target="_blank" rel="noopener" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold btn-sm">
                                                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="p-2.5 rounded-3 bg-light border text-center">
                                                    <div class="text-muted small mb-2">
                                                        <i class="bi bi-shield-lock text-warning me-1"></i> Mobile: <span class="font-monospace text-secondary fw-semibold">+91 XXXXX •••••</span>
                                                    </div>
                                                    <a href="<?php echo $loginRedirectUrl; ?>" class="btn btn-outline-primary rounded-pill px-3 py-1.5 w-100 fw-semibold btn-sm">
                                                        <i class="bi bi-person-check-fill me-1"></i> Log In to View Number & Call
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php 
            $listings = getListings('', '', $panchayat['block_slug'], 20, 0);
            if (!empty($listings)): 
            ?>
                <div class="text-center mb-5">
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill uppercase tracking-wider small">Local Directory</span>
                    <h2 class="fw-bold font-heading text-dark mt-2">Verified Services & Listings in <?php echo sanitizeInput($panchayat['block_name']); ?> Block</h2>
                    <p class="text-muted mx-auto" style="max-width: 540px;">Local contacts, schools, healthcare, and services serving <?php echo sanitizeInput($panchayat['panchayat_name']); ?> Gram Panchayat.</p>
                </div>

                <div class="row g-4">
                    <?php foreach ($listings as $item): ?>
                        <div class="col-lg-6">
                            <?php echo renderListingCard($item); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white max-w-700 mx-auto">
                    <div class="rounded-circle bg-primary-subtle text-primary p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-building-add fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-dark font-heading mb-2">No Directory Listings Added Yet</h4>
                    <p class="text-muted mb-4">There are currently no directory listings registered for <?php echo sanitizeInput($panchayat['panchayat_name']); ?> Gram Panchayat in <?php echo sanitizeInput($panchayat['block_name']); ?> Block.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="add-listing" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2.5 shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> List Business or Contact Free
                        </a>
                        <a href="block/<?php echo urlencode($panchayat['block_slug']); ?>" class="btn btn-outline-primary rounded-pill px-4 py-2.5 fw-semibold">
                            Explore <?php echo sanitizeInput($panchayat['block_name']); ?> Block <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Official Data Sources Attribution Banner -->
    <section class="py-4 bg-white border-top">
        <div class="container">
            <div class="p-4 rounded-4 bg-light border d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark font-heading mb-1">Standardized Public & Government Data Sources</h6>
                        <p class="text-muted small mb-0">Local Gram Panchayat boundaries, village (mauja) codes, census stats, and representative data reference LGD Portal, SEC Bihar, Bihar Bhumi, and Census of India.</p>
                    </div>
                </div>
                <a href="sources" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold text-nowrap">
                    <i class="bi bi-link-45deg me-1"></i>View Official Sources
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

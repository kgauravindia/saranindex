<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : '';
$db = getDB();

$panchayat = null;
$samitiMembers = [];
$jilaParishadMembers = [];

if (!empty($slug) && $db) {
    try {
        $stmt = $db->prepare("SELECT p.*, COALESCE(b.name_english, b.name) as block_name, b.hindi_name as block_hindi, b.slug as block_slug FROM panchayats p JOIN blocks b ON p.block_id = b.id WHERE p.slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $panchayat = $stmt->fetch();

        if ($panchayat) {
            // Fetch Panchayat Samiti Members for this panchayat
            $stmtSamiti = $db->prepare("SELECT * FROM listings WHERE subcategory_id = 248 AND panchayat_id = :pid ORDER BY id ASC");
            $stmtSamiti->execute(['pid' => $panchayat['id']]);
            $samitiMembers = $stmtSamiti->fetchAll();

            // Fetch Zila Parishad Member(s) representing this block
            $stmtZP = $db->prepare("SELECT * FROM listings WHERE subcategory_id = 249 AND block_id = :bid ORDER BY id ASC");
            $stmtZP->execute(['bid' => $panchayat['block_id']]);
            $jilaParishadMembers = $stmtZP->fetchAll();
        }
    } catch (PDOException $e) {}
}

if ($panchayat) {
    $pName = !empty($panchayat['hindi_name']) ? $panchayat['hindi_name'] : $panchayat['panchayat_name'];
    $bName = !empty($panchayat['block_hindi']) ? $panchayat['block_hindi'] : $panchayat['block_name'];
    $page_title = sanitizeInput($pName) . " ग्राम पंचायत - " . sanitizeInput($bName) . " प्रखंड | सारण इंडेक्स";
    $meta_description = sanitizeInput($pName) . " ग्राम पंचायत, " . sanitizeInput($bName) . " प्रखंड, सारण जिला (बिहार)। गाँव (मौजा), स्थानीय संपर्क और निर्देशिका सेवाएं देखें।";
} else {
    $page_title = "ग्राम पंचायत निर्देशिका - सारण जिले की सभी 318 पंचायतें | सारण इंडेक्स";
    $meta_description = "सारण जिले (छपरा) के सभी 20 प्रखंडों की 318 ग्राम पंचायतों एवं 1,800+ गांवों (मौजा) की संपूर्ण डिजिटल निर्देशिका।";
}

require_once __DIR__ . '/includes/header.php';
$blocks = getBlocks();
?>

<!-- Hero Section (matching hindi/index.php UI) -->
<section class="hero-wrapper position-relative text-center">
    <div class="container position-relative z-1">
        <?php if ($panchayat): ?>
            <!-- Single Panchayat Hero -->
            <div class="d-flex align-items-center justify-content-center gap-2 mb-3 flex-wrap">
                <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill shadow-sm">
                    <i class="bi bi-patch-check-fill me-1"></i> सत्यापित ग्राम पंचायत
                </span>
                <?php if (!empty($panchayat['panchayat_samiti_no'])): ?>
                    <span class="badge bg-white text-dark fw-bold px-3 py-1.5 rounded-pill shadow-sm border border-warning">
                        <i class="bi bi-award-fill text-warning me-1"></i> पंचायत समिति क्षेत्र संख्या: <?php echo sanitizeInput($panchayat['panchayat_samiti_no']); ?>
                    </span>
                <?php endif; ?>
            </div>

            <nav aria-label="breadcrumb" class="d-flex justify-content-center mb-3">
                <ol class="breadcrumb bg-white bg-opacity-10 px-3 py-1.5 rounded-pill mb-0 small border border-white border-opacity-10">
                    <li class="breadcrumb-item"><a href="./" class="text-white-50 text-decoration-none">होम</a></li>
                    <li class="breadcrumb-item"><a href="blocks" class="text-white-50 text-decoration-none">प्रखंड</a></li>
                    <li class="breadcrumb-item"><a href="block/<?php echo urlencode($panchayat['block_slug']); ?>" class="text-white-50 text-decoration-none"><?php echo sanitizeInput($bName); ?></a></li>
                    <li class="breadcrumb-item active text-warning fw-semibold" aria-current="page"><?php echo sanitizeInput($pName); ?></li>
                </ol>
            </nav>

            <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
                <?php echo sanitizeInput($pName); ?> ग्राम पंचायत
                <?php if (!empty($panchayat['panchayat_name']) && strcasecmp($pName, $panchayat['panchayat_name']) !== 0): ?>
                    <span class="text-white-50 fs-3 ms-1">(<?php echo sanitizeInput($panchayat['panchayat_name']); ?>)</span>
                <?php endif; ?>
            </h1>

            <p class="lead text-white-50 font-heading fw-semibold mb-4 fs-4" style="color: #cbd5e1 !important;">
                ग्राम पंचायत • <?php echo sanitizeInput($bName); ?> प्रखंड (<?php echo sanitizeInput($panchayat['block_name']); ?>)
            </p>

            <!-- Villages / Maujas Badges -->
            <?php if (!empty($panchayat['village_hindi']) || !empty($panchayat['village'])): 
                $villageListHi = !empty($panchayat['village_hindi']) ? explode(',', $panchayat['village_hindi']) : [];
                $villageListEn = !empty($panchayat['village']) ? explode(',', $panchayat['village']) : [];
                $vCount = max(count($villageListHi), count($villageListEn));
            ?>
                <div class="p-4 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur max-w-900 mx-auto shadow-lg text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-white border-opacity-10 pb-2">
                        <span class="fw-bold text-warning small text-uppercase tracking-wider">
                            <i class="bi bi-houses-fill me-1"></i> इस ग्राम पंचायत के अंतर्गत आने वाले मौजा / गाँव (<?php echo $vCount; ?>)
                        </span>
                        <span class="badge bg-warning text-dark rounded-pill"><?php echo $vCount; ?> गाँव</span>
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <?php 
                        for ($i = 0; $i < $vCount; $i++):
                            $vHi = isset($villageListHi[$i]) ? trim($villageListHi[$i]) : '';
                            $vEn = isset($villageListEn[$i]) ? trim($villageListEn[$i]) : '';
                            if (empty($vHi) && empty($vEn)) continue;
                            $displayName = !empty($vHi) ? $vHi : $vEn;
                            if (!empty($vEn) && !empty($vHi) && strcasecmp($vEn, $vHi) !== 0) {
                                $displayName .= " ({$vEn})";
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
                    <i class="bi bi-arrow-left me-1"></i> सभी 318 ग्राम पंचायतों की सूची देखें
                </a>
            </div>

        <?php else: ?>
            <!-- All Panchayats Index Hero -->
            <div class="d-inline-flex align-items-center mb-3 brand-badge">
                <i class="bi bi-patch-check-fill text-warning me-2 fs-6"></i>
                <span>आधिकारिक पंचायती राज निर्देशिका • सारण जिला</span>
            </div>

            <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
                सारण ग्राम पंचायतें
            </h1>
            
            <p class="lead text-white-50 font-heading fw-semibold mb-3 fs-3" style="color: #cbd5e1 !important;">
                डिजिटल शासन एवं स्थानीय निर्देशिका
            </p>
            
            <p class="text-white-50 mx-auto mb-4" style="max-width: 680px; font-size: 1.05rem;">
                सारण जिले (छपरा) के सभी 20 प्रखंडों की <strong>318 ग्राम पंचायतों</strong> एवं <strong>1,800+ गांवों (मौजा)</strong> की डिजिटल खोज करें।
            </p>

            <!-- Search Bar Component (matching index.php search-card style) -->
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-11">
                    <div class="search-card d-flex align-items-center gap-2">
                        <button type="button" class="btn mic-btn flex-shrink-0" id="micButtonPanchayatHi" title="वॉइस सर्च">
                            <i class="bi bi-mic-fill fs-5"></i>
                        </button>
                        
                        <input type="text" id="panchayatSearchInput" class="form-control search-input flex-grow-1" placeholder="पंचायत का नाम, हिंदी नाम या गाँव (मौजा) खोजें..." autocomplete="off" onkeyup="filterPanchayats()">
                        
                        <select id="blockFilterSelect" class="form-select border-0 bg-light rounded-pill px-3 fw-medium d-none d-md-block" style="max-width: 200px;" onchange="filterPanchayats()">
                            <option value="">सभी 20 प्रखंड</option>
                            <?php foreach ($blocks as $blk): ?>
                                <option value="<?php echo sanitizeInput($blk['slug']); ?>">
                                    <?php echo sanitizeInput(!empty($blk['hindi_name']) ? $blk['hindi_name'] : $blk['block_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button type="button" class="btn search-submit-btn flex-shrink-0" onclick="filterPanchayats()">
                            <i class="bi bi-search me-1"></i>खोजें
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
                <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1 rounded-pill small">प्रखंड अनुसार देखें</span>
                <button type="button" class="btn btn-link text-decoration-none text-muted small p-0" onclick="clearSearch()"><i class="bi bi-arrow-counterclockwise me-1"></i>फ़िल्टर हटाएं</button>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold block-filter-btn active" data-block="" onclick="selectBlockFilter('', this)">
                    सभी 318 पंचायतें
                </button>
                <?php foreach ($blocks as $blk): ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-medium block-filter-btn" data-block="<?php echo sanitizeInput($blk['slug']); ?>" onclick="selectBlockFilter('<?php echo sanitizeInput($blk['slug']); ?>', this)">
                        <?php echo sanitizeInput(!empty($blk['hindi_name']) ? $blk['hindi_name'] : $blk['block_name']); ?> (<?php echo $blk['total_panchayats']; ?>)
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
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill uppercase tracking-wider small">पंचायती राज</span>
                    <h2 class="fw-bold font-heading text-dark mt-2 fs-2 mb-0">सारण की ग्राम पंचायतें</h2>
                </div>
                <div class="badge bg-primary text-white fw-bold fs-6 px-3 py-2 rounded-pill shadow-sm" id="panchayatCountBadge">
                    कुल <?php echo count($allPanchayats); ?> पंचायतें
                </div>
            </div>

            <div class="row g-4" id="panchayatsGrid">
                <?php foreach ($allPanchayats as $p): 
                    $pTitle = !empty($p['hindi_name']) ? $p['hindi_name'] : $p['panchayat_name'];
                    $pSubTitle = !empty($p['hindi_name']) ? $p['panchayat_name'] : '';
                    $blkTitle = !empty($p['block_hindi']) ? $p['block_hindi'] : $p['block_name'];
                    
                    $vHi = !empty($p['village_hindi']) ? explode(',', $p['village_hindi']) : [];
                    $vEn = !empty($p['village']) ? explode(',', $p['village']) : [];
                    $vCount = max(count($vHi), count($vEn));
                    $vSearchStr = strtolower(($p['panchayat_name'] ?? '') . ' ' . ($p['hindi_name'] ?? '') . ' ' . ($p['village'] ?? '') . ' ' . ($p['village_hindi'] ?? '') . ' ' . ($p['mukhiya_name'] ?? '') . ' ' . ($p['sarpanch_name'] ?? ''));
                ?>
                    <div class="col-lg-4 col-md-6 panchayat-card-item" 
                         data-block="<?php echo sanitizeInput($p['block_slug']); ?>" 
                         data-search="<?php echo htmlspecialchars($vSearchStr, ENT_QUOTES); ?>">
                        <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift transition-all overflow-hidden bg-white">
                            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex align-items-start justify-content-between">
                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle fw-semibold px-2.5 py-1 rounded-pill small">
                                    <i class="bi bi-geo-alt-fill text-warning me-1"></i><?php echo sanitizeInput($blkTitle); ?> प्रखंड
                                </span>
                                <span class="badge bg-secondary-subtle text-secondary fw-medium px-2.5 py-1 rounded-pill small" title="<?php echo $vCount; ?> गाँव (मौजा)">
                                    <i class="bi bi-houses me-1"></i><?php echo $vCount; ?> गाँव
                                </span>
                            </div>

                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h4 class="fw-bold text-dark font-heading mb-1 fs-5">
                                        <a href="panchayat/<?php echo urlencode($p['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                            <?php echo sanitizeInput($pTitle); ?>
                                        </a>
                                    </h4>
                                    <?php if (!empty($pSubTitle)): ?>
                                        <div class="text-muted fw-semibold small mb-2"><?php echo sanitizeInput($pSubTitle); ?> Gram Panchayat</div>
                                    <?php endif; ?>
                                    <?php if (!empty($p['panchayat_samiti_no'])): ?>
                                        <div class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold px-2.5 py-1 rounded-pill small mb-2">
                                            <i class="bi bi-award me-1"></i>पं.सं. क्षेत्र: <?php echo sanitizeInput($p['panchayat_samiti_no']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Elected Representatives Snippet (Hindi) -->
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
                                                <i class="bi bi-pin-map text-warning me-1"></i> मुख्य गाँव / मौजा:
                                            </div>
                                            <div class="text-secondary lh-sm" style="font-size: 0.82rem;">
                                                <?php 
                                                $shownVillages = [];
                                                for ($i = 0; $i < min(4, $vCount); $i++) {
                                                    $val = !empty($vHi[$i]) ? trim($vHi[$i]) : (!empty($vEn[$i]) ? trim($vEn[$i]) : '');
                                                    if ($val) $shownVillages[] = $val;
                                                }
                                                echo sanitizeInput(implode(', ', $shownVillages));
                                                if ($vCount > 4) {
                                                    echo ' <span class="fw-bold text-primary">+' . ($vCount - 4) . ' अन्य</span>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="pt-3 border-top d-flex align-items-center justify-content-between mt-2">
                                    <a href="panchayat/<?php echo urlencode($p['slug']); ?>" class="btn btn-outline-primary rounded-pill px-3 py-1.5 btn-sm fw-semibold w-100">
                                        पंचायत व जनप्रतिनिधि देखें <i class="bi bi-chevron-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="noResultsAlert" class="alert alert-info rounded-4 text-center py-5 d-none mt-4 shadow-sm">
                <i class="bi bi-search fs-1 text-primary mb-2 d-block"></i>
                <h5 class="fw-bold text-dark mb-1">कोई ग्राम पंचायत नहीं मिली</h5>
                <p class="text-muted mb-3">कृपया अपनी खोज बदले या कोई अन्य प्रखंड फ़िल्टर चुनें।</p>
                <button class="btn btn-primary rounded-pill px-4 py-2 fw-semibold" onclick="clearSearch()">फ़िल्टर हटाएं</button>
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

        document.getElementById('panchayatCountBadge').innerText = 'कुल ' + visibleCount + ' पंचायतें';
        
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

            <!-- Elected Representatives: 3-Tier Democratic Governance (Hindi) -->
            <?php 
            $hasGovernanceData = !empty($panchayat['mukhiya_name']) || !empty($panchayat['sarpanch_name']) || !empty($samitiMembers) || !empty($jilaParishadMembers);
            if ($hasGovernanceData): 
                $isLoggedIn = function_exists('isUserLoggedIn') && isUserLoggedIn();
                $loginRedirectUrl = '../login?redirect=' . urlencode('hindi/panchayat/' . ($panchayat['slug'] ?? ''));
            ?>
                <div class="mb-5">
                    <div class="text-center mb-5">
                        <div class="d-inline-flex align-items-center gap-2 mb-2 flex-wrap justify-content-center">
                            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small shadow-xs">
                                <i class="bi bi-people-fill me-1"></i> निर्वाचित त्रिस्तरीय पंचायती राज
                            </span>
                            <span class="badge bg-primary text-white fw-bold px-3 py-1.5 rounded-pill small shadow-xs">
                                <i class="bi bi-calendar-check me-1"></i> कार्यकाल: 2021 - 2026
                            </span>
                        </div>
                        <h2 class="fw-bold font-heading text-dark mt-1">पंचायत एवं प्रखंड के निर्वाचित जनप्रतिनिधि</h2>
                        <p class="text-muted mx-auto" style="max-width: 680px;"><?php echo sanitizeInput($pName); ?> ग्राम पंचायत के त्रिस्तरीय पंचायती राज व्यवस्था (ग्राम पंचायत, पंचायत समिति एवं जिला परिषद) के निर्वाचित प्रतिनिधि। आधिकारिक डेटा स्रोत: राज्य निर्वाचन आयोग बिहार (<a href="https://sec.bihar.gov.in" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none fw-semibold">sec.bihar.gov.in</a> • कार्यकाल: 2021 - 2026)।</p>
                    </div>

                    <!-- 1. Gram Panchayat & Gram Kacheri Leadership -->
                    <?php if (!empty($panchayat['mukhiya_name']) || !empty($panchayat['sarpanch_name'])): ?>
                        <div class="mb-5">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 small fw-semibold">स्तर 1</span>
                                <h4 class="fw-bold font-heading text-dark mb-0 fs-5">ग्राम पंचायत एवं ग्राम कचहरी नेतृत्व</h4>
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
                                                    <i class="bi bi-check-circle-fill me-1"></i>निर्वाचित
                                                </span>
                                            </div>
                                            <div class="card-body p-4">
                                                <ul class="list-unstyled mb-4 small text-secondary">
                                                    <li class="mb-2 d-flex align-items-start">
                                                        <i class="bi bi-hourglass-split text-primary me-2 mt-0.5"></i>
                                                        <div><strong>निर्वाचित कार्यकाल:</strong> <span class="badge bg-primary-subtle text-primary fw-bold">2021 - 2026</span> (वर्तमान सत्र)</div>
                                                    </li>
                                                    <?php if (!empty($panchayat['mukhiya_father_husband'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-person-fill text-muted me-2 mt-0.5"></i>
                                                            <div><strong>पिता / पति:</strong> <?php echo sanitizeInput($panchayat['mukhiya_father_husband']); ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($panchayat['mukhiya_category']) || !empty($panchayat['mukhiya_reservation'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-tag-fill text-muted me-2 mt-0.5"></i>
                                                            <div><strong>आरक्षण / वर्ग:</strong> <?php echo sanitizeInput($panchayat['mukhiya_category'] ?? ''); ?> <?php if (!empty($panchayat['mukhiya_reservation'])): ?>(<?php echo sanitizeInput($panchayat['mukhiya_reservation']); ?>)<?php endif; ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($panchayat['mukhiya_gender'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-person-circle text-muted me-2 mt-0.5"></i>
                                                            <div><strong>लिंग (Gender):</strong> <?php echo sanitizeInput($panchayat['mukhiya_gender']); ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($panchayat['mukhiya_address'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-geo-alt-fill text-muted me-2 mt-0.5"></i>
                                                            <div><strong>पता:</strong> <?php echo sanitizeInput($panchayat['mukhiya_address']); ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>

                                                <?php if (!empty($mMob)): ?>
                                                    <?php if ($isLoggedIn): ?>
                                                        <div class="d-flex gap-2">
                                                            <a href="tel:<?php echo $mMob; ?>" class="btn btn-primary rounded-pill px-3 py-2 flex-grow-1 fw-semibold btn-sm shadow-xs">
                                                                <i class="bi bi-telephone-fill me-1"></i> कॉल करें: <?php echo $mMob; ?>
                                                            </a>
                                                            <a href="https://wa.me/91<?php echo $mMob; ?>" target="_blank" rel="noopener" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold btn-sm">
                                                                <i class="bi bi-whatsapp me-1"></i> व्हाट्सऐप
                                                            </a>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="p-2.5 rounded-3 bg-light border text-center">
                                                            <div class="text-muted small mb-2">
                                                                <i class="bi bi-shield-lock text-warning me-1"></i> मोबाइल: <span class="font-monospace text-secondary fw-semibold">+91 XXXXX •••••</span>
                                                            </div>
                                                            <a href="<?php echo $loginRedirectUrl; ?>" class="btn btn-outline-primary rounded-pill px-3 py-1.5 w-100 fw-semibold btn-sm">
                                                                <i class="bi bi-person-check-fill me-1"></i> नंबर व संपर्क देखने के लिए लॉगिन करें
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
                                                    <i class="bi bi-check-circle-fill me-1"></i>निर्वाचित
                                                </span>
                                            </div>
                                            <div class="card-body p-4">
                                                <ul class="list-unstyled mb-4 small text-secondary">
                                                    <li class="mb-2 d-flex align-items-start">
                                                        <i class="bi bi-hourglass-split text-warning me-2 mt-0.5"></i>
                                                        <div><strong>निर्वाचित कार्यकाल:</strong> <span class="badge bg-warning-subtle text-dark fw-bold">2021 - 2026</span> (वर्तमान सत्र)</div>
                                                    </li>
                                                    <?php if (!empty($panchayat['sarpanch_father_husband'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-person-fill text-muted me-2 mt-0.5"></i>
                                                            <div><strong>पिता / पति:</strong> <?php echo sanitizeInput($panchayat['sarpanch_father_husband']); ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($panchayat['sarpanch_category']) || !empty($panchayat['sarpanch_reservation'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-tag-fill text-muted me-2 mt-0.5"></i>
                                                            <div><strong>आरक्षण / वर्ग:</strong> <?php echo sanitizeInput($panchayat['sarpanch_category'] ?? ''); ?> <?php if (!empty($panchayat['sarpanch_reservation'])): ?>(<?php echo sanitizeInput($panchayat['sarpanch_reservation']); ?>)<?php endif; ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($panchayat['sarpanch_gender'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-person-circle text-muted me-2 mt-0.5"></i>
                                                            <div><strong>लिंग (Gender):</strong> <?php echo sanitizeInput($panchayat['sarpanch_gender']); ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($panchayat['sarpanch_address'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-geo-alt-fill text-muted me-2 mt-0.5"></i>
                                                            <div><strong>पता:</strong> <?php echo sanitizeInput($panchayat['sarpanch_address']); ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>

                                                <?php if (!empty($sMob)): ?>
                                                    <?php if ($isLoggedIn): ?>
                                                        <div class="d-flex gap-2">
                                                            <a href="tel:<?php echo $sMob; ?>" class="btn btn-warning text-dark rounded-pill px-3 py-2 flex-grow-1 fw-semibold btn-sm shadow-xs">
                                                                <i class="bi bi-telephone-fill me-1"></i> कॉल करें: <?php echo $sMob; ?>
                                                            </a>
                                                            <a href="https://wa.me/91<?php echo $sMob; ?>" target="_blank" rel="noopener" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold btn-sm">
                                                                <i class="bi bi-whatsapp me-1"></i> व्हाट्सऐप
                                                            </a>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="p-2.5 rounded-3 bg-light border text-center">
                                                            <div class="text-muted small mb-2">
                                                                <i class="bi bi-shield-lock text-warning me-1"></i> मोबाइल: <span class="font-monospace text-secondary fw-semibold">+91 XXXXX •••••</span>
                                                            </div>
                                                            <a href="<?php echo $loginRedirectUrl; ?>" class="btn btn-outline-primary rounded-pill px-3 py-1.5 w-100 fw-semibold btn-sm">
                                                                <i class="bi bi-person-check-fill me-1"></i> नंबर व संपर्क देखने के लिए लॉगिन करें
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

                    <!-- 2. Panchayat Samiti Members (BDC Tier - Hindi) -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-info text-dark rounded-pill px-3 py-1.5 small fw-semibold">स्तर 2</span>
                                <h4 class="fw-bold font-heading text-dark mb-0 fs-5">पंचायत समिति सदस्य (BDC Members)</h4>
                            </div>
                            <?php if (!empty($panchayat['panchayat_samiti_no'])): ?>
                                <span class="badge bg-info-subtle text-dark border border-info-subtle rounded-pill px-3 py-1.5 fw-semibold small">
                                    <i class="bi bi-award-fill text-info me-1"></i>प्रादेशिक निर्वाचन क्षेत्र सं०: <?php echo sanitizeInput($panchayat['panchayat_samiti_no']); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($samitiMembers)): ?>
                            <div class="row g-4 justify-content-center">
                                <?php foreach ($samitiMembers as $sm): 
                                    $sDetails = function_exists('parseRepresentativeDetails') ? parseRepresentativeDetails($sm['description']) : [];
                                    $smMob = preg_replace('/[^0-9]/', '', $sm['mobile'] ?? '');
                                    $smName = !empty($sm['contact_person']) ? $sm['contact_person'] : (!empty($sm['hindi_title']) ? $sm['hindi_title'] : $sm['title']);
                                ?>
                                    <div class="col-lg-6">
                                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-lift transition-all">
                                            <div class="p-4 border-bottom bg-info-subtle bg-opacity-25 d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="rounded-circle bg-info text-dark p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 52px; height: 52px;">
                                                        <i class="bi bi-person-lines-fill fs-3"></i>
                                                    </div>
                                                    <div>
                                                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                            <span class="badge bg-info text-dark fw-semibold rounded-pill px-2.5 py-1 small">पंचायत समिति सदस्य</span>
                                                            <?php if (!empty($sDetails['constituency'])): ?>
                                                                <span class="badge bg-dark text-white fw-semibold rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">क्षेत्र सं० <?php echo sanitizeInput($sDetails['constituency']); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <h4 class="fw-bold font-heading text-dark mb-0 mt-1"><?php echo sanitizeInput($smName); ?></h4>
                                                    </div>
                                                </div>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold small">
                                                    <i class="bi bi-check-circle-fill me-1"></i>निर्वाचित
                                                </span>
                                            </div>
                                            <div class="card-body p-4">
                                                <ul class="list-unstyled mb-4 small text-secondary">
                                                    <li class="mb-2 d-flex align-items-start">
                                                        <i class="bi bi-hourglass-split text-info me-2 mt-0.5"></i>
                                                        <div><strong>निर्वाचित कार्यकाल:</strong> <span class="badge bg-info-subtle text-dark fw-bold">2021 - 2026</span> (वर्तमान सत्र)</div>
                                                    </li>
                                                    <?php if (!empty($sDetails['father_husband'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-person-fill text-muted me-2 mt-0.5"></i>
                                                            <div><strong>पिता / पति:</strong> <?php echo sanitizeInput($sDetails['father_husband']); ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($sDetails['category']) || !empty($sDetails['reservation'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-tag-fill text-muted me-2 mt-0.5"></i>
                                                            <div><strong>आरक्षण / वर्ग:</strong> <?php echo sanitizeInput($sDetails['category'] ?? ''); ?> <?php if (!empty($sDetails['reservation'])): ?>(<?php echo sanitizeInput($sDetails['reservation']); ?>)<?php endif; ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($sDetails['gender'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-person-circle text-muted me-2 mt-0.5"></i>
                                                            <div><strong>लिंग (Gender):</strong> <?php echo sanitizeInput($sDetails['gender']); ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($sDetails['address']) || !empty($sm['address'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-geo-alt-fill text-muted me-2 mt-0.5"></i>
                                                            <div><strong>पता:</strong> <?php echo sanitizeInput(!empty($sDetails['address']) ? $sDetails['address'] : $sm['address']); ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>

                                                <?php if (!empty($smMob)): ?>
                                                    <?php if ($isLoggedIn): ?>
                                                        <div class="d-flex gap-2">
                                                            <a href="tel:<?php echo $smMob; ?>" class="btn btn-info text-dark rounded-pill px-3 py-2 flex-grow-1 fw-semibold btn-sm shadow-xs">
                                                                <i class="bi bi-telephone-fill me-1"></i> कॉल करें: <?php echo $smMob; ?>
                                                            </a>
                                                            <a href="https://wa.me/91<?php echo $smMob; ?>" target="_blank" rel="noopener" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold btn-sm">
                                                                <i class="bi bi-whatsapp me-1"></i> व्हाट्सऐप
                                                            </a>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="p-2.5 rounded-3 bg-light border text-center">
                                                            <div class="text-muted small mb-2">
                                                                <i class="bi bi-shield-lock text-warning me-1"></i> मोबाइल: <span class="font-monospace text-secondary fw-semibold">+91 XXXXX •••••</span>
                                                            </div>
                                                            <a href="<?php echo $loginRedirectUrl; ?>" class="btn btn-outline-primary rounded-pill px-3 py-1.5 w-100 fw-semibold btn-sm">
                                                                <i class="bi bi-person-check-fill me-1"></i> नंबर व संपर्क देखने के लिए लॉगिन करें
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="p-3 rounded-4 bg-white border text-center text-muted small">
                                <i class="bi bi-info-circle text-primary me-1"></i> इस पंचायत के अंतर्गत आने वाले पंचायत समिति प्रादेशिक निर्वाचन क्षेत्र <strong><?php echo sanitizeInput($panchayat['panchayat_samiti_no'] ?? 'N/A'); ?></strong> (<?php echo sanitizeInput($bName); ?> प्रखंड पंचायत समिति) हैं।
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- 3. Zila Parishad Members (District Council Tier - Hindi) -->
                    <?php if (!empty($jilaParishadMembers)): ?>
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success text-white rounded-pill px-3 py-1.5 small fw-semibold">स्तर 3</span>
                                    <h4 class="fw-bold font-heading text-dark mb-0 fs-5">जिला परिषद सदस्य (<?php echo sanitizeInput($bName); ?> प्रखंड क्षेत्र)</h4>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-semibold small">
                                    <i class="bi bi-shield-fill-check me-1"></i>सारण जिला परिषद (2021 - 2026)
                                </span>
                            </div>

                            <div class="row g-4 justify-content-center">
                                <?php foreach ($jilaParishadMembers as $zp): 
                                    $zpDetails = function_exists('parseRepresentativeDetails') ? parseRepresentativeDetails($zp['description']) : [];
                                    $zpMob = preg_replace('/[^0-9]/', '', $zp['mobile'] ?? '');
                                    $zpName = !empty($zp['contact_person']) ? $zp['contact_person'] : (!empty($zp['hindi_title']) ? $zp['hindi_title'] : $zp['title']);
                                ?>
                                    <div class="col-lg-<?php echo count($jilaParishadMembers) > 2 ? '4' : '6'; ?>">
                                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-lift transition-all">
                                            <div class="p-4 border-bottom bg-success-subtle bg-opacity-25 d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="rounded-circle bg-success text-white p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 52px; height: 52px;">
                                                        <i class="bi bi-building fs-3"></i>
                                                    </div>
                                                    <div>
                                                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                            <span class="badge bg-success text-white fw-semibold rounded-pill px-2.5 py-1 small">जिला परिषद सदस्य</span>
                                                            <?php if (!empty($zpDetails['constituency'])): ?>
                                                                <span class="badge bg-dark text-white fw-semibold rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">क्षेत्र सं० <?php echo sanitizeInput($zpDetails['constituency']); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <h4 class="fw-bold font-heading text-dark mb-0 mt-1 fs-5"><?php echo sanitizeInput($zpName); ?></h4>
                                                    </div>
                                                </div>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold small">
                                                    <i class="bi bi-check-circle-fill me-1"></i>निर्वाचित
                                                </span>
                                            </div>
                                            <div class="card-body p-4">
                                                <ul class="list-unstyled mb-4 small text-secondary">
                                                    <li class="mb-2 d-flex align-items-start">
                                                        <i class="bi bi-hourglass-split text-success me-2 mt-0.5"></i>
                                                        <div><strong>निर्वाचित कार्यकाल:</strong> <span class="badge bg-success-subtle text-success fw-bold">2021 - 2026</span> (वर्तमान सत्र)</div>
                                                    </li>
                                                    <?php if (!empty($zpDetails['father_husband'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-person-fill text-muted me-2 mt-0.5"></i>
                                                            <div><strong>पिता / पति:</strong> <?php echo sanitizeInput($zpDetails['father_husband']); ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($zpDetails['category']) || !empty($zpDetails['reservation'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-tag-fill text-muted me-2 mt-0.5"></i>
                                                            <div><strong>आरक्षण / वर्ग:</strong> <?php echo sanitizeInput($zpDetails['category'] ?? ''); ?> <?php if (!empty($zpDetails['reservation'])): ?>(<?php echo sanitizeInput($zpDetails['reservation']); ?>)<?php endif; ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($zpDetails['gender'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-person-circle text-muted me-2 mt-0.5"></i>
                                                            <div><strong>लिंग (Gender):</strong> <?php echo sanitizeInput($zpDetails['gender']); ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (!empty($zpDetails['address']) || !empty($zp['address'])): ?>
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-geo-alt-fill text-muted me-2 mt-0.5"></i>
                                                            <div><strong>पता:</strong> <?php echo sanitizeInput(!empty($zpDetails['address']) ? $zpDetails['address'] : $zp['address']); ?></div>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>

                                                <?php if (!empty($zpMob)): ?>
                                                    <?php if ($isLoggedIn): ?>
                                                        <div class="d-flex gap-2">
                                                            <a href="tel:<?php echo $zpMob; ?>" class="btn btn-success text-white rounded-pill px-3 py-2 flex-grow-1 fw-semibold btn-sm shadow-xs">
                                                                <i class="bi bi-telephone-fill me-1"></i> कॉल करें: <?php echo $zpMob; ?>
                                                            </a>
                                                            <a href="https://wa.me/91<?php echo $zpMob; ?>" target="_blank" rel="noopener" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold btn-sm">
                                                                <i class="bi bi-whatsapp me-1"></i> व्हाट्सऐप
                                                            </a>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="p-2.5 rounded-3 bg-light border text-center">
                                                            <div class="text-muted small mb-2">
                                                                <i class="bi bi-shield-lock text-warning me-1"></i> मोबाइल: <span class="font-monospace text-secondary fw-semibold">+91 XXXXX •••••</span>
                                                            </div>
                                                            <a href="<?php echo $loginRedirectUrl; ?>" class="btn btn-outline-primary rounded-pill px-3 py-1.5 w-100 fw-semibold btn-sm">
                                                                <i class="bi bi-person-check-fill me-1"></i> नंबर व संपर्क देखने के लिए लॉगिन करें
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php 
            $listings = getListings('', '', $panchayat['block_slug'], 20, 0);
            if (!empty($listings)): 
            ?>
                <div class="text-center mb-5">
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill uppercase tracking-wider small">स्थानीय जानकारी</span>
                    <h2 class="fw-bold font-heading text-dark mt-2"><?php echo sanitizeInput($pName); ?> में सत्यापित सेवाएं एवं संस्थान</h2>
                    <p class="text-muted mx-auto" style="max-width: 540px;"><?php echo sanitizeInput($bName); ?> प्रखंड के अंतर्गत आने वाले स्थानीय संपर्क, स्कूल व स्वास्थ्य केंद्र।</p>
                </div>

                <div class="row g-4">
                    <?php foreach ($listings as $item): ?>
                        <div class="col-lg-6">
                            <?php echo renderListingCard($item, ['lang' => 'hi']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white max-w-700 mx-auto">
                    <div class="rounded-circle bg-primary-subtle text-primary p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-building-add fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-dark font-heading mb-2">अभी कोई निर्देशिका सूची उपलब्ध नहीं है</h4>
                    <p class="text-muted mb-4"><?php echo sanitizeInput($bName); ?> प्रखंड की <?php echo sanitizeInput($pName); ?> ग्राम पंचायत के लिए वर्तमान में कोई निर्देशिका सूची पंजीकृत नहीं है।</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="../add-listing" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2.5 shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> अपनी दुकान या सेवा मुफ्त जोड़ें
                        </a>
                        <a href="block/<?php echo urlencode($panchayat['block_slug']); ?>" class="btn btn-outline-primary rounded-pill px-4 py-2.5 fw-semibold">
                            <?php echo sanitizeInput($bName); ?> प्रखंड देखें <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Official Data Sources Attribution Banner (Hindi) -->
    <section class="py-4 bg-white border-top">
        <div class="container">
            <div class="p-4 rounded-4 bg-light border d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark font-heading mb-1">मानकीकृत सार्वजनिक एवं सरकारी डेटा स्रोत</h6>
                        <p class="text-muted small mb-0">स्थानीय ग्राम पंचायत सीमाएं, गाँव (मौजा) कोड, जनगणना आंकड़े और प्रतिनिधि डेटा एलजीडी पोर्टल, राज्य निर्वाचन आयोग बिहार (<a href="https://sec.bihar.gov.in" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none fw-semibold">sec.bihar.gov.in</a>), बिहार भूमि व जनगणना भारत से संदर्भित हैं।</p>
                    </div>
                </div>
                <a href="sources" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold text-nowrap">
                    <i class="bi bi-link-45deg me-1"></i>आधिकारिक डेटा स्रोत देखें
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

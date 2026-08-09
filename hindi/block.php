<?php
require_once __DIR__ . '/includes/functions.php';

$slug = $_GET['slug'] ?? '';
$block = null;

if (!empty($slug)) {
    $block = getBlockBySlug($slug);
}

if ($block) {
    // Single Block Detail Page (Hindi)
    $popTotal = intval($block['pop_tot'] ?? 0);
    $popMale = intval($block['pop_male'] ?? 0);
    $popFemale = intval($block['pop_female'] ?? 0);
    $popRural = intval($block['pop_rural'] ?? 0);
    $popUrban = intval($block['pop_urban'] ?? 0);
    $litTotal = intval($block['lit_tot'] ?? 0);
    $totWork = intval($block['tot_work_tot'] ?? 0);
    $households = intval($block['households'] ?? 0);

    $litRate = $popTotal > 0 ? round(($litTotal / $popTotal) * 100, 1) : 0;
    $ruralPct = $popTotal > 0 ? round(($popRural / $popTotal) * 100, 1) : 0;
    $urbanPct = $popTotal > 0 ? round(($popUrban / $popTotal) * 100, 1) : 0;

    $panchayats = getPanchayats($block['id']);

    $bTitle = !empty($block['hindi_name']) ? $block['hindi_name'] : $block['block_name'];
    $bEnTitle = !empty($block['hindi_name']) ? $block['block_name'] : '';

    $page_title = sanitizeInput($bTitle) . " प्रखंड - जनगणना 2011 आंकड़े एवं ग्राम पंचायतें | सारण इंडेक्स";
    $meta_description = sanitizeInput($bTitle) . " प्रखंड (सारण), जनसंख्या (" . number_format($popTotal) . "), साक्षरता दर (" . $litRate . "%), " . count($panchayats) . " ग्राम पंचायतें एवं गाँव निर्देशिका।";
    require_once __DIR__ . '/includes/header.php';
    ?>

    <!-- Hero Section (matching hindi/index.php UI) -->
    <section class="hero-wrapper position-relative text-center">
        <div class="container position-relative z-1">
            <div class="d-inline-flex align-items-center mb-3 brand-badge">
                <i class="bi bi-patch-check-fill text-warning me-2 fs-6"></i>
                <span>प्रखंड / अंचल (CD Block) • सारण जिला, बिहार</span>
            </div>

            <nav aria-label="breadcrumb" class="d-flex justify-content-center mb-3">
                <ol class="breadcrumb bg-white bg-opacity-10 px-3 py-1.5 rounded-pill mb-0 small border border-white border-opacity-10">
                    <li class="breadcrumb-item"><a href="./" class="text-white-50 text-decoration-none">होम</a></li>
                    <li class="breadcrumb-item"><a href="blocks" class="text-white-50 text-decoration-none">प्रखंड</a></li>
                    <li class="breadcrumb-item active text-warning fw-semibold" aria-current="page"><?php echo sanitizeInput($bTitle); ?></li>
                </ol>
            </nav>

            <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
                <?php echo sanitizeInput($bTitle); ?> प्रखंड
                <?php if (!empty($bEnTitle)): ?>
                    <span class="text-white-50 fs-3 ms-1">(<?php echo sanitizeInput($bEnTitle); ?> Block)</span>
                <?php endif; ?>
            </h1>

            <p class="lead text-white-50 font-heading fw-semibold mb-4 fs-4" style="color: #cbd5e1 !important;">
                पिन कोड: <?php echo sanitizeInput($block['pincode']); ?> • <?php echo count($panchayats); ?> ग्राम पंचायतें • कुल जनसंख्या: <?php echo number_format($popTotal); ?>
            </p>

            <div class="d-flex flex-wrap justify-content-center gap-2 mb-2">
                <a href="#gramPanchayatsSection" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2.5 shadow-sm hover-lift">
                    <i class="bi bi-building-check me-1.5"></i> सभी <?php echo count($panchayats); ?> पंचायतें देखें
                </a>
                <a href="../search.php?block=<?php echo sanitizeInput($block['slug']); ?>" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-bold hover-lift">
                    <i class="bi bi-search me-1.5"></i> निर्देशिका देखें
                </a>
                <a href="villages?block=<?php echo urlencode($block['name']); ?>" class="btn btn-light text-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm hover-lift">
                    <i class="bi bi-houses-fill me-1.5"></i> गाँव देखें
                </a>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Top Demographic Summary Cards -->
        <div class="row g-4 mb-5">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 hover-lift position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 text-primary fs-1"><i class="bi bi-people-fill"></i></div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold uppercase tracking-wider">कुल जनसंख्या</div>
                            <div class="fw-bolder fs-3 text-dark lh-sm"><?php echo number_format($popTotal); ?></div>
                        </div>
                    </div>
                    <div class="progress rounded-pill bg-light" style="height: 6px;">
                        <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: 100%"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2 small text-muted">
                        <span>पुरुष: <strong><?php echo number_format($popMale); ?></strong></span>
                        <span>महिला: <strong><?php echo number_format($popFemale); ?></strong></span>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 hover-lift position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 text-success fs-1"><i class="bi bi-building-check"></i></div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                            <i class="bi bi-building-check fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold uppercase tracking-wider">ग्राम पंचायतें</div>
                            <div class="fw-bolder fs-3 text-dark lh-sm"><?php echo count($panchayats); ?></div>
                        </div>
                    </div>
                    <div class="progress rounded-pill bg-light" style="height: 6px;">
                        <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: 100%"></div>
                    </div>
                    <div class="mt-2 small text-muted d-flex align-items-center justify-content-between">
                        <span>परिवार: <strong><?php echo number_format($households); ?></strong></span>
                        <a href="#gramPanchayatsSection" class="text-success fw-bold text-decoration-none">पंचायतें <i class="bi bi-arrow-down"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 hover-lift position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 text-info fs-1"><i class="bi bi-journal-bookmark-fill"></i></div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                            <i class="bi bi-journal-bookmark-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold uppercase tracking-wider">साक्षरता दर</div>
                            <div class="fw-bolder fs-3 text-dark lh-sm"><?php echo $litRate; ?>%</div>
                        </div>
                    </div>
                    <div class="progress rounded-pill bg-light" style="height: 6px;">
                        <div class="progress-bar bg-info rounded-pill" role="progressbar" style="width: <?php echo min(100, $litRate); ?>%"></div>
                    </div>
                    <div class="mt-2 small text-muted">
                        कुल साक्षर: <strong><?php echo number_format($litTotal); ?></strong>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 hover-lift position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 text-warning fs-1"><i class="bi bi-pie-chart-fill"></i></div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                            <i class="bi bi-pie-chart-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold uppercase tracking-wider">ग्रामीण / शहरी</div>
                            <div class="fw-bolder fs-5 text-dark lh-sm"><?php echo $ruralPct; ?>% / <?php echo $urbanPct; ?>%</div>
                        </div>
                    </div>
                    <div class="progress rounded-pill bg-light overflow-hidden d-flex" style="height: 6px;">
                        <div class="bg-primary" style="width: <?php echo $ruralPct; ?>%"></div>
                        <div class="bg-warning" style="width: <?php echo $urbanPct; ?>%"></div>
                    </div>
                    <div class="mt-2 small text-muted d-flex justify-content-between">
                        <span>ग्रामीण: <strong><?php echo number_format($popRural); ?></strong></span>
                        <span>शहरी: <strong><?php echo number_format($popUrban); ?></strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Real Gram Panchayats Section for this Block (Hindi) -->
        <div id="gramPanchayatsSection" class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-dark text-white p-4 d-flex flex-wrap align-items-center justify-content-between gap-2 border-0">
                <div>
                    <h4 class="fw-bold font-heading mb-1 text-white">
                        <i class="bi bi-building-check text-warning me-2"></i><?php echo sanitizeInput($bTitle); ?> प्रखंड की ग्राम पंचायतें (<?php echo count($panchayats); ?>)
                    </h4>
                    <p class="text-white-50 small mb-0"><?php echo sanitizeInput($bTitle); ?> प्रखंड के अंतर्गत आने वाली सभी सत्यापित ग्राम पंचायतें एवं उनके मौजा (गाँव)</p>
                </div>
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill"><?php echo count($panchayats); ?> ग्राम पंचायतें</span>
            </div>

            <div class="card-body p-4 bg-light">
                <?php if (empty($panchayats)): ?>
                    <div class="alert alert-info rounded-3 text-center my-3">
                        <i class="bi bi-info-circle me-1"></i> इस प्रखंड में अभी कोई ग्राम पंचायत सूचीबद्ध नहीं है।
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($panchayats as $p): 
                            $pNameHi = !empty($p['hindi_name']) ? $p['hindi_name'] : $p['panchayat_name'];
                            $pSubTitleEn = !empty($p['hindi_name']) ? $p['panchayat_name'] : '';
                            $vHi = !empty($p['village_hindi']) ? explode(',', $p['village_hindi']) : [];
                            $vEn = !empty($p['village']) ? explode(',', $p['village']) : [];
                            $vCount = max(count($vHi), count($vEn));
                        ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 hover-lift d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                                                <i class="bi bi-houses me-1"></i><?php echo $vCount; ?> गाँव
                                            </span>
                                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle small px-2.5 py-1 rounded-pill">
                                                <?php echo sanitizeInput($bTitle); ?>
                                            </span>
                                        </div>
                                        <h4 class="fw-bold text-dark font-heading mb-1 fs-5">
                                            <a href="panchayat/<?php echo urlencode($p['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                                <?php echo sanitizeInput($pNameHi); ?>
                                            </a>
                                        </h4>
                                        <?php if (!empty($pSubTitleEn)): ?>
                                            <div class="text-muted small fw-semibold mb-2"><?php echo sanitizeInput($pSubTitleEn); ?> Gram Panchayat</div>
                                        <?php endif; ?>
                                        <?php if (!empty($p['panchayat_samiti_no'])): ?>
                                            <div class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold px-2.5 py-1 rounded-pill small mb-3">
                                                <i class="bi bi-award me-1"></i>पं.सं. क्षेत्र: <?php echo sanitizeInput($p['panchayat_samiti_no']); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($vCount > 0): ?>
                                            <div class="small text-secondary mb-3 p-2.5 rounded-3 bg-light border" style="font-size: 0.82rem;">
                                                <strong class="text-dark"><i class="bi bi-pin-map text-warning me-1"></i> मुख्य गाँव / मौजा:</strong> 
                                                <div class="mt-1">
                                                    <?php 
                                                    $shownV = [];
                                                    for ($i = 0; $i < min(4, $vCount); $i++) {
                                                        $val = !empty($vHi[$i]) ? trim($vHi[$i]) : (!empty($vEn[$i]) ? trim($vEn[$i]) : '');
                                                        if ($val) $shownV[] = $val;
                                                    }
                                                    echo sanitizeInput(implode(', ', $shownV));
                                                    if ($vCount > 4) echo ' <span class="text-primary fw-bold">+' . ($vCount - 4) . ' अन्य</span>';
                                                    ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="pt-3 border-top mt-2">
                                        <a href="panchayat/<?php echo urlencode($p['slug']); ?>" class="btn btn-outline-primary rounded-pill w-100 fw-semibold btn-sm py-2">
                                            पंचायत व गाँव देखें <i class="bi bi-chevron-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center pt-2">
            <a href="blocks" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-bold transition-all">
                <i class="bi bi-arrow-left me-1.5"></i> सभी 20 प्रखंड देखें
            </a>
        </div>
    </div>

    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// All 20 Blocks Overview Page (Hindi)
$page_title = "सारण जिले के सभी 20 प्रखंड (अंचल) – सारण इंडेक्स";
$meta_description = "सारण जिले (छपरा) के सभी 20 प्रखंडों की 318 ग्राम पंचायतों, जनसंख्या आंकड़ों एवं गांवों की संपूर्ण डिजिटल निर्देशिका।";
require_once __DIR__ . '/includes/header.php';

$blocks = getBlocks();
?>

<!-- Banner Hero Section (matching hindi/index.php UI) -->
<section class="hero-wrapper position-relative text-center">
    <div class="container position-relative z-1">
        <div class="d-inline-flex align-items-center mb-3 brand-badge">
            <i class="bi bi-patch-check-fill text-warning me-2 fs-6"></i>
            <span>प्रशासनिक एवं भौगोलिक निर्देशिका • सारण जिला</span>
        </div>

        <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
            सारण जिले के सभी 20 प्रखंड (अंचल)
        </h1>
        
        <p class="lead text-white-50 font-heading fw-semibold mb-3 fs-3" style="color: #cbd5e1 !important;">
            जनगणना 2011 आंकड़े एवं 318 ग्राम पंचायतें
        </p>
        
        <p class="text-white-50 mx-auto mb-4" style="max-width: 680px; font-size: 1.05rem;">
            सारण जिले (छपरा) के सभी 20 प्रखंडों में आधिकारिक जनगणना 2011 आंकड़े, जनसंख्या, साक्षरता दर, व्यवसायों, <strong>318 ग्राम पंचायतों</strong> एवं स्कूलों की खोज करें।
        </p>

        <!-- Search Bar Component (matching index.php search-card style) -->
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11">
                <div class="search-card d-flex align-items-center gap-2">
                    <button type="button" class="btn mic-btn flex-shrink-0" id="micButtonBlockHi" title="वॉइस सर्च">
                        <i class="bi bi-mic-fill fs-5"></i>
                    </button>
                    
                    <input type="text" id="blockSearchInput" class="form-control search-input flex-grow-1" placeholder="प्रखंड का नाम, हिंदी नाम या पिन कोड खोजें..." autocomplete="off" onkeyup="filterBlocks()">

                    <button type="button" class="btn search-submit-btn flex-shrink-0" onclick="filterBlocks()">
                        <i class="bi bi-search me-1"></i>खोजें
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 20 Blocks Grid Section (matching index.php UI layout) -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill uppercase tracking-wider small">प्रशासनिक निर्देशिका</span>
                <h2 class="fw-bold font-heading text-dark mt-2 fs-2 mb-0">सारण के 20 प्रखंड (अंचल)</h2>
            </div>
            <div class="badge bg-primary text-white fw-bold fs-6 px-3 py-2 rounded-pill shadow-sm" id="blockCountBadge">
                कुल <?php echo count($blocks); ?> प्रखंड
            </div>
        </div>

        <div class="row g-4" id="blocksGrid">
            <?php foreach ($blocks as $blk): 
                $bTitle = !empty($blk['hindi_name']) ? $blk['hindi_name'] : $blk['block_name'];
                $bSubTitle = !empty($blk['hindi_name']) ? $blk['block_name'] : '';
                $bSearchStr = strtolower(($blk['block_name'] ?? '') . ' ' . ($blk['hindi_name'] ?? '') . ' ' . ($blk['pincode'] ?? ''));
            ?>
                <div class="col-lg-4 col-md-6 block-card-item" data-search="<?php echo htmlspecialchars($bSearchStr, ENT_QUOTES); ?>">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 hover-lift transition-all bg-white d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="bg-primary-subtle text-primary p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                    <i class="bi bi-geo-alt-fill fs-4"></i>
                                </div>
                                <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill small fw-semibold">
                                    पिन कोड: <?php echo sanitizeInput($blk['pincode']); ?>
                                </span>
                            </div>

                            <h4 class="fw-bold text-dark font-heading mb-1 fs-4">
                                <a href="block/<?php echo urlencode($blk['slug']); ?>" class="text-dark text-decoration-none hover-primary transition-all">
                                    <?php echo sanitizeInput($bTitle); ?>
                                </a>
                            </h4>
                            <?php if ($bSubTitle): ?>
                                <div class="text-muted small fw-semibold mb-3"><?php echo sanitizeInput($bSubTitle); ?> Block</div>
                            <?php endif; ?>

                            <!-- Census 2011 Summary Badges -->
                            <?php if (!empty($blk['pop_tot'])): ?>
                                <div class="bg-light rounded-3 p-3 mb-3 border border-light small">
                                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                                        <span class="text-muted"><i class="bi bi-people-fill me-1.5 text-primary"></i> कुल जनसंख्या:</span>
                                        <span class="fw-bold text-dark"><?php echo number_format($blk['pop_tot']); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                                        <span class="text-muted"><i class="bi bi-building-check me-1.5 text-success"></i> ग्राम पंचायतें:</span>
                                        <span class="fw-bold text-success"><?php echo number_format($blk['total_panchayats']); ?> पंचायतें</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted"><i class="bi bi-house-door-fill me-1.5 text-secondary"></i> परिवार:</span>
                                        <span class="fw-bold text-dark"><?php echo number_format($blk['households']); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto gap-2">
                            <a href="block/<?php echo urlencode($blk['slug']); ?>#gramPanchayatsSection" class="btn btn-sm btn-outline-primary rounded-pill fw-semibold px-3 py-1.5">
                                पंचायतें (<?php echo $blk['total_panchayats']; ?>)
                            </a>
                            <a href="block/<?php echo urlencode($blk['slug']); ?>" class="btn btn-sm btn-primary rounded-pill fw-semibold px-3 py-1.5 shadow-sm">
                                प्रखंड देखें <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="noBlocksAlert" class="alert alert-info rounded-4 text-center py-5 d-none mt-4 shadow-sm">
            <i class="bi bi-search fs-1 text-primary mb-2 d-block"></i>
            <h5 class="fw-bold text-dark mb-1">कोई प्रखंड नहीं मिला</h5>
            <p class="text-muted mb-3">कृपया प्रखंड का दूसरा नाम या पिन कोड खोजें।</p>
            <button class="btn btn-primary rounded-pill px-4 py-2 fw-semibold" onclick="clearBlockSearch()">खोज हटाएं</button>
        </div>
    </div>
</section>

<script>
function filterBlocks() {
    const query = document.getElementById('blockSearchInput').value.toLowerCase().trim();
    const items = document.querySelectorAll('.block-card-item');
    let visibleCount = 0;

    items.forEach(item => {
        const itemSearch = item.getAttribute('data-search').toLowerCase();
        if (query === '' || itemSearch.includes(query)) {
            item.classList.remove('d-none');
            visibleCount++;
        } else {
            item.classList.add('d-none');
        }
    });

    document.getElementById('blockCountBadge').innerText = 'कुल ' + visibleCount + ' प्रखंड';
    
    const noResults = document.getElementById('noBlocksAlert');
    if (visibleCount === 0) {
        noResults.classList.remove('d-none');
    } else {
        noResults.classList.add('d-none');
    }
}

function clearBlockSearch() {
    document.getElementById('blockSearchInput').value = '';
    filterBlocks();
}
</script>

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
                    <p class="text-muted small mb-0">स्थानीय प्रखंड सीमाएं, ग्राम पंचायत सूची, जनगणना आंकड़े और राजस्व अंचल डेटा एलजीडी पोर्टल, बिहार भूमि, जनगणना 2011 व एनआईसी सारण से संदर्भित हैं।</p>
                </div>
            </div>
            <a href="sources" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold text-nowrap">
                <i class="bi bi-link-45deg me-1"></i>आधिकारिक डेटा स्रोत देखें
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

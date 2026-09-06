<?php
require_once __DIR__ . '/includes/functions.php';

$slug = $_GET['slug'] ?? '';
$block = null;

if (!empty($slug)) {
    $block = getBlockBySlug($slug);
}

if ($block) {
    // Single Block Detail Page
    $popTotal = intval($block['pop_tot'] ?? 0);
    $popMale = intval($block['pop_male'] ?? 0);
    $popFemale = intval($block['pop_female'] ?? 0);
    $popRural = intval($block['pop_rural'] ?? 0);
    $popUrban = intval($block['pop_urban'] ?? 0);
    $litTotal = intval($block['lit_tot'] ?? 0);
    $totWork = intval($block['tot_work_tot'] ?? 0);
    $households = intval($block['households'] ?? 0);

    $litRate = $popTotal > 0 ? round(($litTotal / $popTotal) * 100, 1) : 0;
    $workRate = $popTotal > 0 ? round(($totWork / $popTotal) * 100, 1) : 0;
    $ruralPct = $popTotal > 0 ? round(($popRural / $popTotal) * 100, 1) : 0;
    $urbanPct = $popTotal > 0 ? round(($popUrban / $popTotal) * 100, 1) : 0;

    $panchayats = getPanchayats($block['id']);

    $isLoggedIn = function_exists('isUserLoggedIn') && isUserLoggedIn();
    $db = getDB();
    $pramukh = null;
    $upPramukh = null;

    if ($db && $block) {
        try {
            $stmtP = $db->prepare("SELECT * FROM listings WHERE subcategory_id = 265 AND block_id = :bid AND status = 'ACTIVE' LIMIT 1");
            $stmtP->execute(['bid' => $block['id']]);
            $pramukh = $stmtP->fetch();

            $stmtUP = $db->prepare("SELECT * FROM listings WHERE subcategory_id = 266 AND block_id = :bid AND status = 'ACTIVE' LIMIT 1");
            $stmtUP->execute(['bid' => $block['id']]);
            $upPramukh = $stmtUP->fetch();
        } catch (PDOException $e) {}
    }

    $page_title = sanitizeInput($block['block_name']) . " Block ({$block['hindi_name']}) Census 2011 Data & Gram Panchayats – Saran Index";
    $meta_description = "Official Census 2011 demographics, population (" . number_format($popTotal) . "), households, " . count($panchayats) . " Gram Panchayats, villages, literacy rate ({$litRate}%) for " . $block['block_name'] . " Block, Saran District (Chapra, Bihar).";
    require_once __DIR__ . '/includes/header.php';
    ?>

    <!-- Hero Section (matching index.php UI) -->
    <section class="hero-wrapper position-relative text-center">
        <div class="container position-relative z-1">
            <div class="d-inline-flex align-items-center mb-3 brand-badge">
                <i class="bi bi-patch-check-fill text-warning me-2 fs-6"></i>
                <span>CD Block / Sub-District • Saran District, Bihar</span>
            </div>

            <nav aria-label="breadcrumb" class="d-flex justify-content-center mb-3">
                <ol class="breadcrumb bg-white bg-opacity-10 px-3 py-1.5 rounded-pill mb-0 small border border-white border-opacity-10">
                    <li class="breadcrumb-item"><a href="./" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="blocks" class="text-white-50 text-decoration-none">Blocks</a></li>
                    <li class="breadcrumb-item active text-warning fw-semibold" aria-current="page"><?php echo sanitizeInput($block['block_name']); ?></li>
                </ol>
            </nav>

            <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
                <?php echo sanitizeInput($block['block_name']); ?> Block
                <?php if (!empty($block['hindi_name'])): ?>
                    <span class="text-white-50 fs-3 ms-1">(<?php echo sanitizeInput($block['hindi_name']); ?>)</span>
                <?php endif; ?>
            </h1>

            <p class="lead text-white-50 font-heading fw-semibold mb-4 fs-4" style="color: #cbd5e1 !important;">
                PIN Code: <?php echo sanitizeInput($block['pincode']); ?> • <?php echo count($panchayats); ?> Gram Panchayats • Population: <?php echo number_format($popTotal); ?>
            </p>

            <div class="d-flex flex-wrap justify-content-center gap-2 mb-2">
                <a href="#gramPanchayatsSection" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2.5 shadow-sm hover-lift">
                    <i class="bi bi-building-check me-1.5"></i> View <?php echo count($panchayats); ?> Panchayats
                </a>
                <a href="search.php?block=<?php echo sanitizeInput($block['slug']); ?>" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-bold hover-lift">
                    <i class="bi bi-search me-1.5"></i> Local Directory
                </a>
                <a href="villages?block=<?php echo urlencode($block['name']); ?>" class="btn btn-light text-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm hover-lift">
                    <i class="bi bi-houses-fill me-1.5"></i> View Villages
                </a>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Top Demographic Summary Cards (matching index.php UI) -->
        <div class="row g-4 mb-5">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 hover-lift position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 text-primary fs-1"><i class="bi bi-people-fill"></i></div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold uppercase tracking-wider">Population</div>
                            <div class="fw-bolder fs-3 text-dark lh-sm"><?php echo number_format($popTotal); ?></div>
                        </div>
                    </div>
                    <div class="progress rounded-pill bg-light" style="height: 6px;">
                        <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: 100%"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2 small text-muted">
                        <span>Male: <strong><?php echo number_format($popMale); ?></strong></span>
                        <span>Female: <strong><?php echo number_format($popFemale); ?></strong></span>
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
                            <div class="text-muted small fw-semibold uppercase tracking-wider">Gram Panchayats</div>
                            <div class="fw-bolder fs-3 text-dark lh-sm"><?php echo count($panchayats); ?></div>
                        </div>
                    </div>
                    <div class="progress rounded-pill bg-light" style="height: 6px;">
                        <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: 100%"></div>
                    </div>
                    <div class="mt-2 small text-muted d-flex align-items-center justify-content-between">
                        <span>Households: <strong><?php echo number_format($households); ?></strong></span>
                        <a href="#gramPanchayatsSection" class="text-success fw-bold text-decoration-none">View All <i class="bi bi-arrow-down"></i></a>
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
                            <div class="text-muted small fw-semibold uppercase tracking-wider">Literacy Rate</div>
                            <div class="fw-bolder fs-3 text-dark lh-sm"><?php echo $litRate; ?>%</div>
                        </div>
                    </div>
                    <div class="progress rounded-pill bg-light" style="height: 6px;">
                        <div class="progress-bar bg-info rounded-pill" role="progressbar" style="width: <?php echo min(100, $litRate); ?>%"></div>
                    </div>
                    <div class="mt-2 small text-muted">
                        Total Literates: <strong><?php echo number_format($litTotal); ?></strong>
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
                            <div class="text-muted small fw-semibold uppercase tracking-wider">Rural / Urban</div>
                            <div class="fw-bolder fs-5 text-dark lh-sm"><?php echo $ruralPct; ?>% / <?php echo $urbanPct; ?>%</div>
                        </div>
                    </div>
                    <div class="progress rounded-pill bg-light overflow-hidden d-flex" style="height: 6px;">
                        <div class="bg-primary" style="width: <?php echo $ruralPct; ?>%"></div>
                        <div class="bg-warning" style="width: <?php echo $urbanPct; ?>%"></div>
                    </div>
                    <div class="mt-2 small text-muted d-flex justify-content-between">
                        <span>Rural: <strong><?php echo number_format($popRural); ?></strong></span>
                        <span>Urban: <strong><?php echo number_format($popUrban); ?></strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Block Panchayati Raj & Governance Leadership Section (Pramukh & Up Pramukh) -->
        <?php if (!empty($pramukh) || !empty($upPramukh)): ?>
            <div class="mb-5">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                    <div>
                        <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">
                            Block Governance & Panchayati Raj
                        </span>
                        <h3 class="fw-bold font-heading text-dark mt-2 mb-0 fs-3">
                            Panchayat Samiti Leadership: Pramukh & Up Pramukh
                        </h3>
                        <p class="text-muted small mb-0">Elected Block Panchayat Samiti leadership representing <?php echo sanitizeInput($block['block_name']); ?> Block (2021 - 2026 tenure).</p>
                    </div>
                    <span class="badge bg-primary text-white fw-bold px-3 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-shield-check me-1"></i>Tenure: 2021 - 2026
                    </span>
                </div>

                <div class="row g-4">
                    <!-- Pramukh Card -->
                    <?php if (!empty($pramukh)): 
                        $pDetails = function_exists('parseRepresentativeDetails') ? parseRepresentativeDetails($pramukh['description']) : [];
                        $pName = !empty($pDetails['name']) ? $pDetails['name'] : (!empty($pramukh['hindi_title']) ? preg_replace('/\s*-\s*प्रमुख.*$/u', '', $pramukh['hindi_title']) : preg_replace('/^Pramukh\s+/i', '', $pramukh['title']));
                        $pName = preg_replace('/\s*\(.*$/', '', $pName);
                        $pMob = preg_replace('/[^0-9]/', '', $pramukh['mobile'] ?? '');
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
                                                <span class="badge bg-primary text-white fw-semibold rounded-pill px-2.5 py-1 small">Block Pramukh / प्रमुख</span>
                                                <span class="badge bg-dark text-white fw-semibold rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">2021 - 2026</span>
                                            </div>
                                            <h4 class="fw-bold font-heading text-dark mb-0 mt-1"><?php echo sanitizeInput($pName); ?></h4>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold small">
                                        <i class="bi bi-patch-check-fill me-1"></i>Elected
                                    </span>
                                </div>
                                <div class="card-body p-4">
                                    <ul class="list-unstyled mb-4 small text-secondary">
                                        <li class="mb-2 d-flex align-items-start">
                                            <i class="bi bi-award text-primary me-2 mt-0.5"></i>
                                            <div><strong>Position:</strong> <span class="fw-semibold text-dark">Pramukh (Block Panchayat Samiti President)</span></div>
                                        </li>
                                        <li class="mb-2 d-flex align-items-start">
                                            <i class="bi bi-hourglass-split text-primary me-2 mt-0.5"></i>
                                            <div><strong>Elected Tenure:</strong> <span class="badge bg-primary-subtle text-primary fw-bold">2021 - 2026</span> (Current Term)</div>
                                        </li>
                                        <?php if (!empty($pDetails['father_husband'])): ?>
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-person-fill text-muted me-2 mt-0.5"></i>
                                                <div><strong>Father / Husband:</strong> <?php echo sanitizeInput($pDetails['father_husband']); ?></div>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (!empty($pDetails['category']) || !empty($pDetails['reservation'])): ?>
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-tag-fill text-muted me-2 mt-0.5"></i>
                                                <div><strong>Category:</strong> <?php echo sanitizeInput($pDetails['category'] ?? ''); ?> <?php if (!empty($pDetails['reservation'])): ?>(<?php echo sanitizeInput($pDetails['reservation']); ?>)<?php endif; ?></div>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (!empty($pDetails['gender'])): ?>
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-person-circle text-muted me-2 mt-0.5"></i>
                                                <div><strong>Gender:</strong> <?php echo sanitizeInput($pDetails['gender']); ?></div>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (!empty($pDetails['address']) || !empty($pramukh['address'])): ?>
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-geo-alt-fill text-muted me-2 mt-0.5"></i>
                                                <div><strong>Address:</strong> <?php echo sanitizeInput(!empty($pDetails['address']) ? $pDetails['address'] : $pramukh['address']); ?></div>
                                            </li>
                                        <?php endif; ?>
                                    </ul>

                                    <?php if (!empty($pMob)): ?>
                                        <?php if ($isLoggedIn): ?>
                                            <div class="d-flex gap-2">
                                                <a href="tel:<?php echo $pMob; ?>" class="btn btn-primary rounded-pill px-3 py-2 flex-grow-1 fw-semibold btn-sm shadow-xs">
                                                    <i class="bi bi-telephone-fill me-1"></i> Call <?php echo $pMob; ?>
                                                </a>
                                                <?php if (!empty($pramukh['whatsapp'])): ?>
                                                    <a href="https://wa.me/91<?php echo preg_replace('/[^0-9]/', '', $pramukh['whatsapp']); ?>" target="_blank" rel="noopener" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold btn-sm">
                                                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="p-2.5 rounded-3 bg-light border text-center">
                                                <div class="text-muted small mb-2">
                                                    <i class="bi bi-shield-lock text-warning me-1"></i> Mobile: <span class="font-monospace text-secondary fw-semibold">+91 XXXXX •••••</span>
                                                </div>
                                                <a href="login.php?redirect=<?php echo urlencode('block/' . $block['slug']); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                                    <i class="bi bi-box-arrow-in-right me-1"></i> Log In to View Mobile Number
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="text-muted small fst-italic p-2 bg-light rounded-3 text-center">
                                            <i class="bi bi-info-circle me-1"></i> Direct contact available through Block Panchayat Samiti Office.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Up Pramukh Card -->
                    <?php if (!empty($upPramukh)): 
                        $upDetails = function_exists('parseRepresentativeDetails') ? parseRepresentativeDetails($upPramukh['description']) : [];
                        $upName = !empty($upDetails['name']) ? $upDetails['name'] : (!empty($upPramukh['hindi_title']) ? preg_replace('/\s*-\s*उप प्रमुख.*$/u', '', $upPramukh['hindi_title']) : preg_replace('/^Up Pramukh\s+/i', '', $upPramukh['title']));
                        $upName = preg_replace('/\s*\(.*$/', '', $upName);
                        $upMob = preg_replace('/[^0-9]/', '', $upPramukh['mobile'] ?? '');
                    ?>
                        <div class="col-lg-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-lift transition-all">
                                <div class="p-4 border-bottom bg-info-subtle bg-opacity-25 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-info text-dark p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 52px; height: 52px;">
                                            <i class="bi bi-person-check-fill fs-3"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                <span class="badge bg-info text-dark fw-semibold rounded-pill px-2.5 py-1 small">Block Up Pramukh / उप प्रमुख</span>
                                                <span class="badge bg-dark text-white fw-semibold rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">2021 - 2026</span>
                                            </div>
                                            <h4 class="fw-bold font-heading text-dark mb-0 mt-1"><?php echo sanitizeInput($upName); ?></h4>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold small">
                                        <i class="bi bi-patch-check-fill me-1"></i>Elected
                                    </span>
                                </div>
                                <div class="card-body p-4">
                                    <ul class="list-unstyled mb-4 small text-secondary">
                                        <li class="mb-2 d-flex align-items-start">
                                            <i class="bi bi-award text-info me-2 mt-0.5"></i>
                                            <div><strong>Position:</strong> <span class="fw-semibold text-dark">Up Pramukh (Vice Block President)</span></div>
                                        </li>
                                        <li class="mb-2 d-flex align-items-start">
                                            <i class="bi bi-hourglass-split text-info me-2 mt-0.5"></i>
                                            <div><strong>Elected Tenure:</strong> <span class="badge bg-info-subtle text-dark fw-bold">2021 - 2026</span> (Current Term)</div>
                                        </li>
                                        <?php if (!empty($upDetails['father_husband'])): ?>
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-person-fill text-muted me-2 mt-0.5"></i>
                                                <div><strong>Father / Husband:</strong> <?php echo sanitizeInput($upDetails['father_husband']); ?></div>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (!empty($upDetails['category']) || !empty($upDetails['reservation'])): ?>
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-tag-fill text-muted me-2 mt-0.5"></i>
                                                <div><strong>Category:</strong> <?php echo sanitizeInput($upDetails['category'] ?? ''); ?> <?php if (!empty($upDetails['reservation'])): ?>(<?php echo sanitizeInput($upDetails['reservation']); ?>)<?php endif; ?></div>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (!empty($upDetails['gender'])): ?>
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-person-circle text-muted me-2 mt-0.5"></i>
                                                <div><strong>Gender:</strong> <?php echo sanitizeInput($upDetails['gender']); ?></div>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (!empty($upDetails['address']) || !empty($upPramukh['address'])): ?>
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-geo-alt-fill text-muted me-2 mt-0.5"></i>
                                                <div><strong>Address:</strong> <?php echo sanitizeInput(!empty($upDetails['address']) ? $upDetails['address'] : $upPramukh['address']); ?></div>
                                            </li>
                                        <?php endif; ?>
                                    </ul>

                                    <?php if (!empty($upMob)): ?>
                                        <?php if ($isLoggedIn): ?>
                                            <div class="d-flex gap-2">
                                                <a href="tel:<?php echo $upMob; ?>" class="btn btn-info text-dark rounded-pill px-3 py-2 flex-grow-1 fw-semibold btn-sm shadow-xs">
                                                    <i class="bi bi-telephone-fill me-1"></i> Call <?php echo $upMob; ?>
                                                </a>
                                                <?php if (!empty($upPramukh['whatsapp'])): ?>
                                                    <a href="https://wa.me/91<?php echo preg_replace('/[^0-9]/', '', $upPramukh['whatsapp']); ?>" target="_blank" rel="noopener" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold btn-sm">
                                                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="p-2.5 rounded-3 bg-light border text-center">
                                                <div class="text-muted small mb-2">
                                                    <i class="bi bi-shield-lock text-warning me-1"></i> Mobile: <span class="font-monospace text-secondary fw-semibold">+91 XXXXX •••••</span>
                                                </div>
                                                <a href="login.php?redirect=<?php echo urlencode('block/' . $block['slug']); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                                    <i class="bi bi-box-arrow-in-right me-1"></i> Log In to View Mobile Number
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="text-muted small fst-italic p-2 bg-light rounded-3 text-center">
                                            <i class="bi bi-info-circle me-1"></i> Direct contact available through Block Panchayat Samiti Office.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Real Gram Panchayats Section for this Block -->
        <div id="gramPanchayatsSection" class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-dark text-white p-4 d-flex flex-wrap align-items-center justify-content-between gap-2 border-0">
                <div>
                    <h4 class="fw-bold font-heading mb-1 text-white">
                        <i class="bi bi-building-check text-warning me-2"></i>Gram Panchayats in <?php echo sanitizeInput($block['block_name']); ?> Block (<?php echo count($panchayats); ?>)
                    </h4>
                    <p class="text-white-50 small mb-0">Official Gram Panchayats and constituent villages (maujas) under <?php echo sanitizeInput($block['block_name']); ?></p>
                </div>
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill"><?php echo count($panchayats); ?> Gram Panchayats</span>
            </div>

            <div class="card-body p-4 bg-light">
                <?php if (empty($panchayats)): ?>
                    <div class="alert alert-info rounded-3 text-center my-3">
                        <i class="bi bi-info-circle me-1"></i> No Gram Panchayats listed for this block yet.
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($panchayats as $p): 
                            $vEn = !empty($p['village']) ? explode(',', $p['village']) : [];
                            $vHi = !empty($p['village_hindi']) ? explode(',', $p['village_hindi']) : [];
                            $vCount = max(count($vEn), count($vHi));
                        ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 hover-lift d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                                                <i class="bi bi-houses me-1"></i><?php echo $vCount; ?> Villages
                                            </span>
                                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle small px-2.5 py-1 rounded-pill">
                                                <?php echo sanitizeInput($block['block_name']); ?>
                                            </span>
                                        </div>
                                        <h4 class="fw-bold text-dark font-heading mb-1 fs-5">
                                            <a href="panchayat/<?php echo urlencode($p['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                                <?php echo sanitizeInput($p['panchayat_name']); ?>
                                            </a>
                                        </h4>
                                        <?php if (!empty($p['hindi_name'])): ?>
                                            <div class="text-muted small fw-semibold mb-2"><?php echo sanitizeInput($p['hindi_name']); ?> ग्राम पंचायत</div>
                                        <?php endif; ?>
                                        <?php if (!empty($p['panchayat_samiti_no'])): ?>
                                            <div class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold px-2.5 py-1 rounded-pill small mb-3">
                                                <i class="bi bi-award me-1"></i>Samiti No: <?php echo sanitizeInput($p['panchayat_samiti_no']); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($vCount > 0): ?>
                                            <div class="small text-secondary mb-3 p-2.5 rounded-3 bg-light border" style="font-size: 0.82rem;">
                                                <strong class="text-dark"><i class="bi bi-pin-map text-warning me-1"></i> Key Villages (Mauja):</strong> 
                                                <div class="mt-1">
                                                    <?php 
                                                    $shownV = [];
                                                    for ($i = 0; $i < min(4, $vCount); $i++) {
                                                        $val = !empty($vEn[$i]) ? trim($vEn[$i]) : (!empty($vHi[$i]) ? trim($vHi[$i]) : '');
                                                        if ($val) $shownV[] = $val;
                                                    }
                                                    echo sanitizeInput(implode(', ', $shownV));
                                                    if ($vCount > 4) echo ' <span class="text-primary fw-bold">+' . ($vCount - 4) . ' more</span>';
                                                    ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="pt-3 border-top mt-2">
                                        <a href="panchayat/<?php echo urlencode($p['slug']); ?>" class="btn btn-outline-primary rounded-pill w-100 fw-semibold btn-sm py-2">
                                            View Panchayat & Villages <i class="bi bi-chevron-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detailed Census Table Card -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-dark text-white p-4 d-flex flex-wrap align-items-center justify-content-between gap-2 border-0">
                <div>
                    <h4 class="fw-bold font-heading mb-1 text-white"><i class="bi bi-bar-chart-line-fill text-warning me-2"></i>Official Census 2011 Demographic Breakdown</h4>
                    <p class="text-white-50 small mb-0">Sub-District Demographic Data sourced from the Registrar General & Census Commissioner of India</p>
                </div>
                <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-20 px-3 py-2 rounded-pill small">CD Block Code: <?php echo sanitizeInput($block['cd_block_code'] ?? 'N/A'); ?></span>
            </div>

            <div class="card-body p-0 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="bg-light text-muted small uppercase">
                            <tr>
                                <th class="text-start ps-4 py-3 fw-bold">Demographic Indicator</th>
                                <th class="py-3 text-primary fw-bold fs-6"><i class="bi bi-globe me-1"></i> Total</th>
                                <th class="py-3 text-success fw-bold fs-6"><i class="bi bi-tree me-1"></i> Rural</th>
                                <th class="py-3 text-info fw-bold fs-6"><i class="bi bi-building me-1"></i> Urban</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start ps-4 py-3.5 fw-semibold text-dark"><i class="bi bi-house-door me-2 text-primary opacity-75"></i>Total Households</td>
                                <td class="fw-bold text-dark fs-6"><?php echo number_format($households); ?></td>
                                <td class="text-secondary"><?php echo number_format($block['households_rural'] ?? 0); ?></td>
                                <td class="text-secondary"><?php echo number_format($block['households_urban'] ?? 0); ?></td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4 py-3.5 fw-semibold text-dark"><i class="bi bi-people me-2 text-primary opacity-75"></i>Total Population</td>
                                <td class="fw-bold text-primary fs-5"><?php echo number_format($popTotal); ?></td>
                                <td class="fw-semibold text-success"><?php echo number_format($popRural); ?></td>
                                <td class="fw-semibold text-info"><?php echo number_format($popUrban); ?></td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4 py-3.5 fw-semibold text-dark"><i class="bi bi-gender-male me-2 text-muted"></i>Male Population</td>
                                <td class="fw-semibold text-dark"><?php echo number_format($popMale); ?></td>
                                <td><?php echo number_format($block['pop_male_rural'] ?? 0); ?></td>
                                <td><?php echo number_format($block['pop_male_urban'] ?? 0); ?></td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4 py-3.5 fw-semibold text-dark"><i class="bi bi-gender-female me-2 text-muted"></i>Female Population</td>
                                <td class="fw-semibold text-dark"><?php echo number_format($popFemale); ?></td>
                                <td><?php echo number_format($block['pop_female_rural'] ?? 0); ?></td>
                                <td><?php echo number_format($block['pop_female_urban'] ?? 0); ?></td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4 py-3.5 fw-semibold text-dark"><i class="bi bi-book me-2 text-success opacity-75"></i>Literate Population</td>
                                <td class="fw-bold text-success fs-6"><?php echo number_format($litTotal); ?></td>
                                <td><?php echo number_format($block['lit_rural'] ?? 0); ?></td>
                                <td><?php echo number_format($block['lit_urban'] ?? 0); ?></td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4 py-3.5 fw-semibold text-dark"><i class="bi bi-briefcase me-2 text-info opacity-75"></i>Total Workers</td>
                                <td class="fw-bold text-info fs-6"><?php echo number_format($totWork); ?></td>
                                <td><?php echo number_format($block['tot_work_rural'] ?? 0); ?></td>
                                <td><?php echo number_format($block['tot_work_urban'] ?? 0); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer bg-light p-3.5 text-center border-top">
                <span class="small text-muted me-2"><i class="bi bi-info-circle me-1"></i>Looking for specific village data in <?php echo sanitizeInput($block['block_name']); ?>?</span>
                <a href="villages?block=<?php echo urlencode($block['name']); ?>" class="fw-bold text-primary text-decoration-none small hover-underline">
                    Explore all villages in <?php echo sanitizeInput($block['block_name']); ?> <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="text-center pt-2">
            <a href="blocks" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-bold transition-all">
                <i class="bi bi-arrow-left me-1.5"></i> Back to All 20 Blocks
            </a>
        </div>
    </div>

    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Default: All 20 Blocks Overview Page
$page_title = "All 20 Blocks of Saran District – Saran Index";
$meta_description = "Explore official Census 2011 demographic data, population, households, literacy stats, and local directory for all 20 blocks of Saran District.";
require_once __DIR__ . '/includes/header.php';

$blocks = getBlocks();
?>

<!-- Banner Hero Section (matching index.php UI) -->
<section class="hero-wrapper position-relative text-center">
    <div class="container position-relative z-1">
        <div class="d-inline-flex align-items-center mb-3 brand-badge">
            <i class="bi bi-patch-check-fill text-warning me-2 fs-6"></i>
            <span>Administrative & Geographic Directory • Saran District</span>
        </div>

        <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
            All 20 Blocks of Saran District
        </h1>
        
        <p class="lead text-white-50 font-heading fw-semibold mb-3 fs-3" style="color: #cbd5e1 !important;">
            Census 2011 Demographics & 318 Panchayats
        </p>
        
        <p class="text-white-50 mx-auto mb-4" style="max-width: 680px; font-size: 1.05rem;">
            Explore official Census 2011 demographics, population, literacy stats, businesses, <strong>318 Gram Panchayats</strong>, and schools in every block of Saran District (Chapra), Bihar.
        </p>

        <!-- Search Bar Component (matching index.php search-card style) -->
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11">
                <div class="search-card d-flex align-items-center gap-2">
                    <button type="button" class="btn mic-btn flex-shrink-0" id="micButtonBlock" title="Voice Search">
                        <i class="bi bi-mic-fill fs-5"></i>
                    </button>
                    
                    <input type="text" id="blockSearchInput" class="form-control search-input flex-grow-1" placeholder="Search block by name, Hindi name, or PIN code..." autocomplete="off" onkeyup="filterBlocks()">

                    <button type="button" class="btn search-submit-btn flex-shrink-0" onclick="filterBlocks()">
                        <i class="bi bi-search me-1"></i>Search
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
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill uppercase tracking-wider small">Geographic Directory</span>
                <h2 class="fw-bold font-heading text-dark mt-2 fs-2 mb-0">Saran Sub-Districts (CD Blocks)</h2>
            </div>
            <div class="badge bg-primary text-white fw-bold fs-6 px-3 py-2 rounded-pill shadow-sm" id="blockCountBadge">
                Showing <?php echo count($blocks); ?> Blocks
            </div>
        </div>

        <div class="row g-4" id="blocksGrid">
            <?php foreach ($blocks as $blk): 
                $pDetails = function_exists('parseRepresentativeDetails') ? parseRepresentativeDetails($blk['pramukh_desc'] ?? '') : [];
                $pName = !empty($pDetails['name']) ? $pDetails['name'] : (!empty($blk['pramukh_hindi']) ? preg_replace('/\s*-\s*प्रमुख.*$/u', '', $blk['pramukh_hindi']) : (!empty($blk['pramukh_title']) ? preg_replace('/\s*\(.*$/', '', preg_replace('/^Pramukh\s+/i', '', $blk['pramukh_title'])) : ''));

                $upDetails = function_exists('parseRepresentativeDetails') ? parseRepresentativeDetails($blk['up_pramukh_desc'] ?? '') : [];
                $upName = !empty($upDetails['name']) ? $upDetails['name'] : (!empty($blk['up_pramukh_hindi']) ? preg_replace('/\s*-\s*उप प्रमुख.*$/u', '', $blk['up_pramukh_hindi']) : (!empty($blk['up_pramukh_title']) ? preg_replace('/\s*\(.*$/', '', preg_replace('/^Up Pramukh\s+/i', '', $blk['up_pramukh_title'])) : ''));

                $bSearchStr = strtolower(($blk['block_name'] ?? '') . ' ' . ($blk['hindi_name'] ?? '') . ' ' . ($blk['pincode'] ?? '') . ' ' . $pName . ' ' . $upName);
            ?>
                <div class="col-lg-4 col-md-6 block-card-item" data-search="<?php echo htmlspecialchars($bSearchStr, ENT_QUOTES); ?>">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 hover-lift transition-all bg-white d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="bg-primary-subtle text-primary p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                    <i class="bi bi-geo-alt-fill fs-4"></i>
                                </div>
                                <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill small fw-semibold">
                                    PIN: <?php echo sanitizeInput($blk['pincode']); ?>
                                </span>
                            </div>

                            <h4 class="fw-bold text-dark font-heading mb-1 fs-4">
                                <a href="<?php echo getBlockUrl($blk['slug']); ?>" class="text-dark text-decoration-none hover-primary transition-all">
                                    <?php echo sanitizeInput($blk['block_name']); ?>
                                </a>
                            </h4>
                            <div class="text-muted small fw-semibold mb-3"><?php echo sanitizeInput($blk['hindi_name']); ?> अंचल</div>

                            <!-- Elected Leadership Snippet (Pramukh & Up Pramukh) -->
                            <?php if (!empty($pName) || !empty($upName)): ?>
                                <div class="mb-3 p-2.5 rounded-3 bg-light border small">
                                    <?php if (!empty($pName)): ?>
                                        <div class="d-flex align-items-center justify-content-between mb-1 pb-1 <?php echo !empty($upName) ? 'border-bottom border-secondary-subtle border-opacity-25' : ''; ?>">
                                            <span class="text-muted fw-bold" style="font-size: 0.76rem;"><i class="bi bi-person-badge-fill text-primary me-1"></i>Pramukh:</span>
                                            <span class="fw-bold text-dark" style="font-size: 0.84rem;"><?php echo sanitizeInput($pName); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($upName)): ?>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-muted fw-bold" style="font-size: 0.76rem;"><i class="bi bi-person-check text-info me-1"></i>Up Pramukh:</span>
                                            <span class="fw-bold text-dark" style="font-size: 0.84rem;"><?php echo sanitizeInput($upName); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Census 2011 Summary Badges -->
                            <?php if (!empty($blk['pop_tot'])): ?>
                                <div class="bg-light rounded-3 p-3 mb-3 border border-light small">
                                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                                        <span class="text-muted"><i class="bi bi-people-fill me-1.5 text-primary"></i> Population:</span>
                                        <span class="fw-bold text-dark"><?php echo number_format($blk['pop_tot']); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                                        <span class="text-muted"><i class="bi bi-building-check me-1.5 text-success"></i> Gram Panchayats:</span>
                                        <span class="fw-bold text-success"><?php echo number_format($blk['total_panchayats']); ?> Panchayats</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted"><i class="bi bi-house-door-fill me-1.5 text-secondary"></i> Households:</span>
                                        <span class="fw-bold text-dark"><?php echo number_format($blk['households']); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto gap-2">
                            <a href="<?php echo getBlockUrl($blk['slug']); ?>#gramPanchayatsSection" class="btn btn-sm btn-outline-primary rounded-pill fw-semibold px-3 py-1.5">
                                Panchayats (<?php echo $blk['total_panchayats']; ?>)
                            </a>
                            <a href="<?php echo getBlockUrl($blk['slug']); ?>" class="btn btn-sm btn-primary rounded-pill fw-semibold px-3 py-1.5 shadow-sm">
                                View Block <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="noBlocksAlert" class="alert alert-info rounded-4 text-center py-5 d-none mt-4 shadow-sm">
            <i class="bi bi-search fs-1 text-primary mb-2 d-block"></i>
            <h5 class="fw-bold text-dark mb-1">No Block found</h5>
            <p class="text-muted mb-3">Try searching for another block name or PIN code.</p>
            <button class="btn btn-primary rounded-pill px-4 py-2 fw-semibold" onclick="clearBlockSearch()">Clear Search</button>
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

    document.getElementById('blockCountBadge').innerText = 'Showing ' + visibleCount + ' Blocks';
    
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
                    <p class="text-muted small mb-0">Local Block boundaries, Gram Panchayat lists, census stats, and revenue circle data reference LGD Portal, Bihar Bhumi, Census 2011, and NIC Saran.</p>
                </div>
            </div>
            <a href="sources" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold text-nowrap">
                <i class="bi bi-link-45deg me-1"></i>View Official Sources
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

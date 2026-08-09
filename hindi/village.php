<?php
require_once __DIR__ . '/includes/functions.php';

// Detect parameter
$param = '';
if (isset($_GET['code']) && !empty($_GET['code'])) {
    $param = sanitizeInput($_GET['code']);
} elseif (isset($_GET['id']) && !empty($_GET['id'])) {
    $param = sanitizeInput($_GET['id']);
} elseif (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $param = sanitizeInput($_GET['slug']);
}

$village = null;
if (!empty($param)) {
    $village = getCensusVillageByCodeOrId($param);
}

// If viewing specific village
if ($village): 
    $vName = sanitizeInput($village['name']);
    $vHindi = !empty($village['name_hindi']) ? sanitizeInput($village['name_hindi']) : '';
    $blockName = !empty($village['block_name']) ? sanitizeInput($village['block_name']) : 'सारण';
    $blockSlug = !empty($village['block_slug']) ? sanitizeInput($village['block_slug']) : '';
    $pincode = !empty($village['pincode']) ? sanitizeInput($village['pincode']) : '841301';
    
    // Census Calculations
    $popTotal = intval($village['pop_tot']);
    $popMale = intval($village['pop_male']);
    $popFemale = intval($village['pop_female']);
    $households = intval($village['households']);
    
    $p06 = intval($village['p_06']);
    $m06 = intval($village['m_06']);
    $f06 = intval($village['f_06']);
    
    $litTot = intval($village['lit_tot']);
    $litMale = intval($village['lit_male']);
    $litFemale = intval($village['lit_female']);
    
    $illTot = intval($village['ill_tot']);
    
    // Calculate Literacy Rate (excluding 0-6 age)
    $effectivePop = $popTotal - $p06;
    $litRate = $effectivePop > 0 ? round(($litTot / $effectivePop) * 100, 1) : 0;
    $maleLitRate = ($popMale - $m06) > 0 ? round(($litMale / ($popMale - $m06)) * 100, 1) : 0;
    $femaleLitRate = ($popFemale - $f06) > 0 ? round(($litFemale / ($popFemale - $f06)) * 100, 1) : 0;
    
    // Sex Ratio (Females per 1,000 Males)
    $sexRatio = $popMale > 0 ? round(($popFemale / $popMale) * 1000) : 0;
    
    // SC / ST
    $scTot = intval($village['sc_tot']);
    $scPercent = $popTotal > 0 ? round(($scTot / $popTotal) * 100, 1) : 0;
    $stTot = intval($village['st_tot']);
    $stPercent = $popTotal > 0 ? round(($stTot / $popTotal) * 100, 1) : 0;
    
    // Workers
    $totWork = intval($village['tot_work_tot']);
    $mainWork = intval($village['main_work_tot']);
    $margWork = intval($village['marg_work_tot']);
    $nonWork = intval($village['non_work_tot']);
    $workPercent = $popTotal > 0 ? round(($totWork / $popTotal) * 100, 1) : 0;
    
    // Workers Category Breakdown
    $mainCl = intval($village['main_cl_tot']); // Cultivators
    $mainAl = intval($village['main_al_tot']); // Agricultural Labourers
    $mainHh = intval($village['main_hh_tot']); // Household Industry
    $mainOt = intval($village['main_ot_tot']); // Other Workers

    $displayVName = $vHindi ? $vHindi . " ({$vName})" : $vName;
    $page_title = "गाँव {$displayVName} जनगणना 2011 डेटा – सारण इंडेक्स";
    $meta_description = "सारण जिला (बिहार) के प्रखंड {$blockName} में स्थित गाँव {$displayVName} की कुल जनसंख्या, साक्षरता दर एवं परिवार संख्या का जनगणना 2011 विवरण।";
    
    require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header -->
<div class="bg-gradient-primary text-white py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-3">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill"></i> मुख्य पृष्ठ</a></li>
                <li class="breadcrumb-item"><a href="village.php" class="text-white-50 text-decoration-none">गाँव</a></li>
                <?php if ($blockSlug): ?>
                    <li class="breadcrumb-item"><a href="block.php?slug=<?php echo $blockSlug; ?>" class="text-white-50 text-decoration-none"><?php echo $blockName; ?> प्रखंड</a></li>
                <?php else: ?>
                    <li class="breadcrumb-item text-white-50"><?php echo $blockName; ?></li>
                <?php endif; ?>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page"><?php echo $vHindi ?: $vName; ?></li>
            </ol>
        </nav>

        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill fs-7">
                        <i class="bi bi-geo-alt-fill me-1"></i> प्रखंड: <?php echo $blockName; ?>
                    </span>
                    <span class="badge px-3 py-1.5 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35);">
                        जनगणना कोड: <?php echo sanitizeInput($village['town_village_code']); ?>
                    </span>
                    <?php if (!empty($village['village_lgd_code'])): ?>
                        <span class="badge px-3 py-1.5 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35);">
                            LGD कोड: <?php echo sanitizeInput($village['village_lgd_code']); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <h1 class="fw-bold font-heading text-white display-6 mb-2">
                    <?php echo $vHindi ?: $vName; ?>
                    <?php if ($vHindi && $vName): ?>
                        <span class="fs-4 text-warning fw-normal ms-2">(<?php echo $vName; ?>)</span>
                    <?php endif; ?>
                </h1>

                <p class="text-white-50 lead mb-0">
                    सारण जिला (बिहार) के <strong><?php echo $blockName; ?> प्रखंड</strong> के अंतर्गत <strong><?php echo $vHindi ?: $vName; ?> गाँव</strong> का आधिकारिक 2011 जनगणना विवरण प्रोफाइल।
                </p>
            </div>

            <div class="col-lg-4 text-lg-end">
                <a href="#demographics" class="btn btn-light text-primary fw-bold px-4 py-2.5 rounded-pill shadow-sm">
                    <i class="bi bi-bar-chart-fill me-2"></i> जनगणना डेटा देखें
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Stats Dashboard -->
<div class="container py-5" id="demographics">
    
    <!-- Top 4 KPI Cards -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-blue">
                <div class="text-primary fs-1 mb-2"><i class="bi bi-people-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo number_format($popTotal); ?></h3>
                <p class="text-muted small mb-0 fw-semibold">कुल जनसंख्या</p>
                <div class="mt-2 text-muted small fs-7">
                    <span class="text-primary me-1 fw-bold"><i class="bi bi-gender-male"></i> <?php echo number_format($popMale); ?> पुरुष</span> | 
                    <span class="text-danger ms-1 fw-bold"><i class="bi bi-gender-female"></i> <?php echo number_format($popFemale); ?> महिला</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-green">
                <div class="text-success fs-1 mb-2"><i class="bi bi-house-door-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo number_format($households); ?></h3>
                <p class="text-muted small mb-0 fw-semibold">कुल परिवार संख्या</p>
                <div class="mt-2 text-muted small fs-7 fw-semibold text-success">
                    औसत परिवार: <?php echo $households > 0 ? round($popTotal / $households, 1) : 0; ?> सदस्य
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-cyan">
                <div class="text-info fs-1 mb-2"><i class="bi bi-book-half"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo $litRate; ?>%</h3>
                <p class="text-muted small mb-0 fw-semibold">साक्षरता दर</p>
                <div class="mt-2 text-muted small fs-7 fw-semibold text-info">
                    <?php echo number_format($litTot); ?> साक्षर नागरिक
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-amber">
                <div class="text-warning fs-1 mb-2"><i class="bi bi-briefcase-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo $workPercent; ?>%</h3>
                <p class="text-muted small mb-0 fw-semibold">कार्यशील कार्यबल</p>
                <div class="mt-2 text-muted small fs-7 fw-semibold text-warning">
                    <?php echo number_format($totWork); ?> कुल श्रमिक
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Detailed Demographics & Charts -->
        <div class="col-lg-8">
            
            <!-- 1. Gender & Sex Ratio -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="fw-bold text-dark mb-0 font-heading">
                        <i class="bi bi-gender-ambiguous text-primary me-2 fs-4"></i> लिंग अनुपात एवं जनसंख्या विवरण (Sex Ratio)
                    </h5>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1.5 fs-7 fw-bold">
                        लिंग अनुपात: <?php echo $sexRatio; ?> महिला प्रति 1,000 पुरुष
                    </span>
                </div>

                <div class="row align-items-center g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold text-dark"><i class="bi bi-gender-male text-primary me-1"></i> पुरुष जनसंख्या</span>
                                <span class="fw-bold text-primary"><?php echo number_format($popMale); ?> (<?php echo $popTotal > 0 ? round(($popMale/$popTotal)*100, 1) : 0; ?>%)</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 12px; background-color: #e2e8f0;">
                                <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: <?php echo $popTotal > 0 ? ($popMale/$popTotal)*100 : 0; ?>%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold text-dark"><i class="bi bi-gender-female text-danger me-1"></i> महिला जनसंख्या</span>
                                <span class="fw-bold text-danger"><?php echo number_format($popFemale); ?> (<?php echo $popTotal > 0 ? round(($popFemale/$popTotal)*100, 1) : 0; ?>%)</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 12px; background-color: #e2e8f0;">
                                <div class="progress-bar bg-danger rounded-pill" role="progressbar" style="width: <?php echo $popTotal > 0 ? ($popFemale/$popTotal)*100 : 0; ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3.5 bg-light rounded-4 border text-center">
                            <div class="text-muted small fw-bold text-uppercase mb-1">बाल जनसंख्या (0 - 6 वर्ष)</div>
                            <h4 class="fw-bolder text-dark mb-2"><?php echo number_format($p06); ?> <span class="fs-6 text-muted font-normal">(कुल का <?php echo $popTotal > 0 ? round(($p06/$popTotal)*100, 1) : 0; ?>%)</span></h4>
                            <div class="d-flex justify-content-center gap-3 small text-muted">
                                <span><i class="bi bi-person-fill text-primary"></i> बालक: <strong><?php echo number_format($m06); ?></strong></span>
                                <span><i class="bi bi-person-fill text-danger"></i> बालिका: <strong><?php echo number_format($f06); ?></strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Literacy & Education Breakdown -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="fw-bold text-dark mb-0 font-heading">
                        <i class="bi bi-journal-bookmark-fill text-success me-2 fs-4"></i> साक्षरता एवं शिक्षा प्रोफाइल
                    </h5>
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1.5 fs-7 fw-bold">
                        साक्षरता दर: <?php echo $litRate; ?>%
                    </span>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold text-dark"><i class="bi bi-check-circle-fill text-success me-1"></i> कुल साक्षर नागरिक</span>
                        <span class="fw-bold text-success"><?php echo number_format($litTot); ?> व्यक्ति</span>
                    </div>
                    <div class="progress rounded-pill mb-3" style="height: 14px; background-color: #e2e8f0;">
                        <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: <?php echo $litRate; ?>%"></div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-semibold text-dark"><i class="bi bi-gender-male text-primary me-1"></i> पुरुष साक्षरता दर</span>
                                <span class="small fw-bold text-primary"><?php echo $maleLitRate; ?>%</span>
                            </div>
                            <div class="text-muted small"><?php echo number_format($litMale); ?> साक्षर पुरुष</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-semibold text-dark"><i class="bi bi-gender-female text-danger me-1"></i> महिला साक्षरता दर</span>
                                <span class="small fw-bold text-danger"><?php echo $femaleLitRate; ?>%</span>
                            </div>
                            <div class="text-muted small"><?php echo number_format($litFemale); ?> साक्षर महिला</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Economic & Worker Classification -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="fw-bold text-dark mb-0 font-heading">
                        <i class="bi bi-briefcase-fill text-warning me-2 fs-4"></i> अर्थव्यवस्था एवं श्रमिक विवरण
                    </h5>
                    <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-1.5 fs-7 fw-bold">
                        कुल श्रमिक: <?php echo number_format($totWork); ?>
                    </span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="p-3 text-center rounded-3 bg-success-subtle border border-success-subtle">
                            <div class="text-success small fw-semibold">कृषक (किसान)</div>
                            <h4 class="fw-bold text-success mb-0 mt-1"><?php echo number_format($mainCl); ?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 text-center rounded-3 bg-primary-subtle border border-primary-subtle">
                            <div class="text-primary small fw-semibold">कृषि मजदूर</div>
                            <h4 class="fw-bold text-primary mb-0 mt-1"><?php echo number_format($mainAl); ?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 text-center rounded-3 bg-purple-subtle border border-purple-subtle">
                            <div class="small fw-semibold">पारिवारिक उद्योग</div>
                            <h4 class="fw-bold mb-0 mt-1"><?php echo number_format($mainHh); ?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 text-center rounded-3 bg-warning-subtle border border-warning-subtle">
                            <div class="text-dark small fw-semibold">अन्य श्रमिक</div>
                            <h4 class="fw-bold text-dark mb-0 mt-1"><?php echo number_format($mainOt); ?></h4>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-semibold text-dark"><i class="bi bi-pie-chart-fill me-1 text-primary"></i> मुख्य श्रमिक बनाम सीमांत श्रमिक</span>
                    </div>
                    <div class="d-flex gap-3 small text-muted flex-wrap">
                        <span>मुख्य श्रमिक: <strong class="text-dark"><?php echo number_format($mainWork); ?></strong></span> | 
                        <span>सीमांत श्रमिक (3-6 माह): <strong class="text-dark"><?php echo number_format($margWork); ?></strong></span> | 
                        <span>गैर-कार्यशील जनसंख्या: <strong class="text-dark"><?php echo number_format($nonWork); ?></strong></span>
                    </div>
                </div>
            </div>

            <!-- 4. Social Category Breakdown (SC / ST) -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark mb-3 font-heading border-bottom pb-3">
                    <i class="bi bi-people-fill text-info me-2 fs-4"></i> सामाजिक श्रेणी जनसंख्या विवरण
                </h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold text-dark">अनुसूचित जाति (SC)</span>
                                <span class="fw-bold text-primary"><?php echo number_format($scTot); ?> (<?php echo $scPercent; ?>%)</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 10px; background-color: #e2e8f0;">
                                <div class="progress-bar bg-primary rounded-pill" style="width: <?php echo $scPercent; ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold text-dark">अनुसूचित जनजाति (ST)</span>
                                <span class="fw-bold text-info"><?php echo number_format($stTot); ?> (<?php echo $stPercent; ?>%)</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 10px; background-color: #e2e8f0;">
                                <div class="progress-bar bg-info rounded-pill" style="width: <?php echo $stPercent; ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar Info & Nearby Villages -->
        <div class="col-lg-4">
            
            <!-- Village Quick Meta Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark mb-3 font-heading border-bottom pb-3">
                    <i class="bi bi-info-circle-fill text-primary me-2"></i> गाँव की त्वरित जानकारी
                </h5>

                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">गाँव का नाम</span>
                        <strong class="text-dark"><?php echo $vHindi ?: $vName; ?></strong>
                    </li>
                    <?php if ($vHindi && $vName): ?>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">अंग्रेजी नाम</span>
                            <strong class="text-dark"><?php echo $vName; ?></strong>
                        </li>
                    <?php endif; ?>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">प्रखंड (अंचल)</span>
                        <strong class="text-dark"><?php echo $blockName; ?></strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">जिला</span>
                        <strong class="text-dark">सारण</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">राज्य</span>
                        <strong class="text-dark">बिहार</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">जनगणना 2011 कोड</span>
                        <strong class="text-dark"><?php echo sanitizeInput($village['town_village_code']); ?></strong>
                    </li>
                    <?php if (!empty($village['village_lgd_code'])): ?>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">LGD गाँव कोड</span>
                            <strong class="text-dark"><?php echo sanitizeInput($village['village_lgd_code']); ?></strong>
                        </li>
                    <?php endif; ?>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">पिन कोड</span>
                        <strong class="text-dark"><?php echo $pincode; ?></strong>
                    </li>
                </ul>
            </div>

            <!-- Add Listing Callout -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-gradient-primary text-white text-center">
                <div class="fs-1 mb-2 text-warning"><i class="bi bi-shop me-1"></i></div>
                <h5 class="fw-bold mb-2 font-heading">क्या आप <?php echo $vHindi ?: $vName; ?> गाँव से हैं?</h5>
                <p class="text-white-50 small mb-3">सारण इंडेक्स पर अपने व्यवसाय, दुकान, क्लीनिक या सेवा की मुफ़्त प्रोफ़ाइल जोड़ें!</p>
                <a href="add-contact.php" class="btn btn-warning text-dark fw-bold px-4 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-plus-circle-fill me-1"></i> मुफ़्त प्रोफ़ाइल जोड़ें
                </a>
            </div>

            <!-- Other Villages in Block -->
            <?php 
            $nearby = getNearbyCensusVillages($blockName, $village['town_village_code'], 6);
            if (!empty($nearby)):
            ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3 font-heading border-bottom pb-3">
                        <i class="bi bi-geo-fill text-danger me-2"></i> <?php echo $blockName; ?> प्रखंड के अन्य गाँव
                    </h5>

                    <div class="list-group list-group-flush">
                        <?php foreach ($nearby as $nv): 
                            $nvTitle = !empty($nv['name_hindi']) ? $nv['name_hindi'] : $nv['name'];
                            $nvSubTitle = !empty($nv['name_hindi']) ? $nv['name'] : '';
                            $nvSlug = !empty($nv['unique_slug']) ? $nv['unique_slug'] : $nv['town_village_code'];
                        ?>
                            <a href="<?php echo '../' . getVillageUrl($nvSlug); ?>" class="list-group-item list-group-item-action px-0 py-2.5 d-flex align-items-center justify-content-between border-bottom">
                                <div>
                                    <div class="fw-semibold text-dark"><?php echo sanitizeInput($nvTitle); ?></div>
                                    <?php if ($nvSubTitle): ?>
                                        <small class="text-muted"><?php echo sanitizeInput($nvSubTitle); ?></small>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-light text-primary border rounded-pill">
                                    जनसंख्या: <?php echo number_format($nv['pop_tot']); ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php 
    require_once __DIR__ . '/includes/footer.php';
    exit;
endif; // End of Village Detail View
?>

<?php
// Directory View for all Census Villages
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$blockFilter = isset($_GET['block']) ? sanitizeInput($_GET['block']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 24;
$offset = ($page - 1) * $limit;

$records = getCensusVillages($blockFilter, $search, $limit, $offset);
$totalRecords = getTotalCensusVillagesCount($blockFilter, $search);
$totalPages = ceil($totalRecords / $limit);
$halkaBlocks = getHalkaBlocks();

$page_title = "सारण जिला जनगणना 2011 गाँव निर्देशिका – सारण इंडेक्स";
$meta_description = "सारण जिले (छपरा) के सभी 1,764 गांवों का जनगणना 2011 विवरण।";

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-gradient-primary text-white py-5 text-center">
    <div class="container py-3">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
            <i class="bi bi-houses-fill me-1"></i> जनगणना 2011 गाँव निर्देशिका
        </span>
        <h1 class="fw-bold font-heading text-white display-6 mb-2">
            सारण जिला के सभी 1,764 जनगणना गाँव
        </h1>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 780px;">
            सारण जिले (छपरा) के सभी 20 प्रखंडों में गांवों की जनसंख्या, परिवार संख्या, साक्षरता दर और जनगणना कोड खोजें।
        </p>

        <!-- Search Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="village.php" method="GET" class="card border-0 shadow-lg p-2 rounded-4 bg-white">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-0 shadow-none py-2" placeholder="गांव का नाम या जनगणना कोड खोजें..." value="<?php echo sanitizeInput($search); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="block" class="form-select border-0 shadow-none py-2 text-dark fw-semibold">
                                <option value="">सभी 20 प्रखंड</option>
                                <?php foreach ($halkaBlocks as $b): ?>
                                    <option value="<?php echo sanitizeInput($b); ?>" <?php echo ($blockFilter === $b) ? 'selected' : ''; ?>>
                                        <?php echo sanitizeInput($b); ?> प्रखंड
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-warning w-100 fw-bold py-2 rounded-3 text-dark">
                                <i class="bi bi-filter"></i> खोजें
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Results Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark font-heading mb-1">
                <?php if ($blockFilter): ?>
                    <?php echo sanitizeInput($blockFilter); ?> प्रखंड में गाँव
                <?php elseif ($search): ?>
                    "<?php echo sanitizeInput($search); ?>" के लिए गाँव खोज परिणाम
                <?php else: ?>
                    सारण जिले के सभी जनगणना गाँव
                <?php endif; ?>
            </h4>
            <p class="text-muted small mb-0">कुल <strong><?php echo number_format($totalRecords); ?></strong> गांवों में से <?php echo number_format(min($totalRecords, $offset + 1)); ?> - <?php echo number_format(min($totalRecords, $offset + count($records))); ?> दिखाए जा रहे हैं</p>
        </div>

        <?php if ($search || $blockFilter): ?>
            <a href="village.php" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="bi bi-x-circle-fill me-1"></i> फ़िल्टर हटाएं
            </a>
        <?php endif; ?>
    </div>

    <!-- Grid List -->
    <?php if (!empty($records)): ?>
        <div class="row g-4 mb-5">
            <?php foreach ($records as $item): 
                $vTitle = !empty($item['name_hindi']) ? $item['name_hindi'] : $item['name'];
                $vSubTitle = !empty($item['name_hindi']) ? $item['name'] : '';
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-shadow transition-all border-top border-4 border-primary">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                                <i class="bi bi-geo-alt-fill me-1"></i> <?php echo sanitizeInput($item['block_name']); ?> प्रखंड
                            </span>
                            <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill fs-7">
                                कोड: <?php echo sanitizeInput($item['town_village_code']); ?>
                            </span>
                        </div>

                        <h3 class="fw-bold text-dark mb-1 font-heading fs-4">
                            <a href="village/<?php echo sanitizeInput($item['town_village_code']); ?>" class="text-dark text-decoration-none hover-primary">
                                <?php echo sanitizeInput($vTitle); ?>
                            </a>
                        </h3>
                        <?php if ($vSubTitle): ?>
                            <div class="text-muted small mb-3"><?php echo sanitizeInput($vSubTitle); ?></div>
                        <?php endif; ?>

                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <div class="row g-2 text-center">
                                <div class="col-6 border-end">
                                    <div class="text-muted fs-7">जनसंख्या</div>
                                    <div class="fw-bold text-dark"><?php echo number_format($item['pop_tot']); ?></div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted fs-7">परिवार</div>
                                    <div class="fw-bold text-dark"><?php echo number_format($item['households']); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto pt-2 border-top text-end">
                            <a href="village/<?php echo sanitizeInput($item['town_village_code']); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                विवरण देखें <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <div class="text-muted fs-1 mb-3"><i class="bi bi-houses"></i></div>
            <h4 class="fw-bold text-dark">कोई गाँव नहीं मिला</h4>
            <p class="text-muted">आपकी खोज के अनुसार कोई परिणाम नहीं मिला।</p>
            <a href="village.php" class="btn btn-primary rounded-pill px-4">सभी गाँव देखें</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

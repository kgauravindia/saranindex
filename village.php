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
    $blockName = !empty($village['block_name']) ? sanitizeInput($village['block_name']) : 'Saran';
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

    $page_title = "Village {$vName}" . ($vHindi ? " ({$vHindi})" : "") . " Census 2011 Data & Information – Saran Index";
    $meta_description = "Complete Census 2011 demographic data for village {$vName} in Block {$blockName}, Saran District, Bihar. Population {$popTotal}, Literacy Rate {$litRate}%, Households {$households}.";
    
    require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header -->
<div class="bg-gradient-primary text-white py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-3">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="village.php" class="text-white-50 text-decoration-none">Villages</a></li>
                <?php if ($blockSlug): ?>
                    <li class="breadcrumb-item"><a href="block.php?slug=<?php echo $blockSlug; ?>" class="text-white-50 text-decoration-none"><?php echo $blockName; ?> Block</a></li>
                <?php else: ?>
                    <li class="breadcrumb-item text-white-50"><?php echo $blockName; ?></li>
                <?php endif; ?>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page"><?php echo $vName; ?></li>
            </ol>
        </nav>

        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill fs-7">
                        <i class="bi bi-geo-alt-fill me-1"></i> Block: <?php echo $blockName; ?>
                    </span>
                    <span class="badge px-3 py-1.5 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35);">
                        Census Code: <?php echo sanitizeInput($village['town_village_code']); ?>
                    </span>
                    <?php if (!empty($village['village_lgd_code'])): ?>
                        <span class="badge px-3 py-1.5 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35);">
                            LGD Code: <?php echo sanitizeInput($village['village_lgd_code']); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <h1 class="fw-bold font-heading text-white display-6 mb-2">
                    <?php echo $vName; ?>
                    <?php if ($vHindi): ?>
                        <span class="fs-4 text-warning font-hindi fw-normal ms-2">(<?php echo $vHindi; ?>)</span>
                    <?php endif; ?>
                </h1>

                <p class="text-white-50 lead mb-0">
                    Official Census & Demographic Directory Profile of <strong><?php echo $vName; ?> Village</strong> in <?php echo $blockName; ?> Sub-District (Block), Saran District, Bihar.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end">
                <a href="#demographics" class="btn btn-light text-primary fw-bold px-4 py-2.5 rounded-pill shadow-sm">
                    <i class="bi bi-bar-chart-fill me-2"></i> View Full Census Data
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
                <p class="text-muted small mb-0 fw-semibold">Total Population</p>
                <div class="mt-2 text-muted small fs-7">
                    <span class="text-primary me-1 fw-bold"><i class="bi bi-gender-male"></i> <?php echo number_format($popMale); ?></span> | 
                    <span class="text-danger ms-1 fw-bold"><i class="bi bi-gender-female"></i> <?php echo number_format($popFemale); ?></span>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-green">
                <div class="text-success fs-1 mb-2"><i class="bi bi-house-door-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo number_format($households); ?></h3>
                <p class="text-muted small mb-0 fw-semibold">Total Households</p>
                <div class="mt-2 text-muted small fs-7 fw-semibold text-success">
                    Avg Family: <?php echo $households > 0 ? round($popTotal / $households, 1) : 0; ?> Persons
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-cyan">
                <div class="text-info fs-1 mb-2"><i class="bi bi-book-half"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo $litRate; ?>%</h3>
                <p class="text-muted small mb-0 fw-semibold">Literacy Rate</p>
                <div class="mt-2 text-muted small fs-7 fw-semibold text-info">
                    <?php echo number_format($litTot); ?> Literates
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-amber">
                <div class="text-warning fs-1 mb-2"><i class="bi bi-briefcase-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo $workPercent; ?>%</h3>
                <p class="text-muted small mb-0 fw-semibold">Working Workforce</p>
                <div class="mt-2 text-muted small fs-7 fw-semibold text-warning">
                    <?php echo number_format($totWork); ?> Total Workers
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
                        <i class="bi bi-gender-ambiguous text-primary me-2 fs-4"></i> Gender Distribution & Sex Ratio
                    </h5>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1.5 fs-7 fw-bold">
                        Sex Ratio: <?php echo $sexRatio; ?> Females / 1,000 Males
                    </span>
                </div>

                <div class="row align-items-center g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold text-dark"><i class="bi bi-gender-male text-primary me-1"></i> Male Population</span>
                                <span class="fw-bold text-primary"><?php echo number_format($popMale); ?> (<?php echo $popTotal > 0 ? round(($popMale/$popTotal)*100, 1) : 0; ?>%)</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 12px; background-color: #e2e8f0;">
                                <div class="progress-bar progress-bar-male rounded-pill" role="progressbar" style="width: <?php echo $popTotal > 0 ? ($popMale/$popTotal)*100 : 0; ?>%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold text-dark"><i class="bi bi-gender-female text-danger me-1"></i> Female Population</span>
                                <span class="fw-bold text-danger"><?php echo number_format($popFemale); ?> (<?php echo $popTotal > 0 ? round(($popFemale/$popTotal)*100, 1) : 0; ?>%)</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 12px; background-color: #e2e8f0;">
                                <div class="progress-bar progress-bar-female rounded-pill" role="progressbar" style="width: <?php echo $popTotal > 0 ? ($popFemale/$popTotal)*100 : 0; ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3.5 bg-light rounded-4 border text-center">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Child Population (0 - 6 Years)</div>
                            <h4 class="fw-bolder text-dark mb-2"><?php echo number_format($p06); ?> <span class="fs-6 text-muted font-normal">(<?php echo $popTotal > 0 ? round(($p06/$popTotal)*100, 1) : 0; ?>% of Total)</span></h4>
                            <div class="d-flex justify-content-center gap-3 small text-muted">
                                <span><i class="bi bi-person-fill text-primary"></i> Male: <strong><?php echo number_format($m06); ?></strong></span>
                                <span><i class="bi bi-person-fill text-danger"></i> Female: <strong><?php echo number_format($f06); ?></strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Literacy & Education Breakdown -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="fw-bold text-dark mb-0 font-heading">
                        <i class="bi bi-journal-bookmark-fill text-success me-2 fs-4"></i> Literacy & Education Profile
                    </h5>
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1.5 fs-7 fw-bold">
                        Literacy Rate: <?php echo $litRate; ?>%
                    </span>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold text-dark"><i class="bi bi-check-circle-fill text-success me-1"></i> Total Literates</span>
                        <span class="fw-bold text-success"><?php echo number_format($litTot); ?> Persons</span>
                    </div>
                    <div class="progress rounded-pill mb-3" style="height: 14px; background-color: #e2e8f0;">
                        <div class="progress-bar progress-bar-lit rounded-pill" role="progressbar" style="width: <?php echo $litRate; ?>%"></div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-semibold text-dark"><i class="bi bi-gender-male text-primary me-1"></i> Male Literacy Rate</span>
                                <span class="small fw-bold text-primary"><?php echo $maleLitRate; ?>%</span>
                            </div>
                            <div class="text-muted small"><?php echo number_format($litMale); ?> Male Literates</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-semibold text-dark"><i class="bi bi-gender-female text-danger me-1"></i> Female Literacy Rate</span>
                                <span class="small fw-bold text-danger"><?php echo $femaleLitRate; ?>%</span>
                            </div>
                            <div class="text-muted small"><?php echo number_format($litFemale); ?> Female Literates</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Economic & Worker Classification -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="fw-bold text-dark mb-0 font-heading">
                        <i class="bi bi-briefcase-fill text-warning me-2 fs-4"></i> Economy & Workers Breakdown
                    </h5>
                    <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-1.5 fs-7 fw-bold">
                        Total Workers: <?php echo number_format($totWork); ?>
                    </span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="p-3 text-center rounded-3 bg-success-subtle border border-success-subtle">
                            <div class="text-success small fw-semibold">Cultivators (Farmers)</div>
                            <h4 class="fw-bold text-success mb-0 mt-1"><?php echo number_format($mainCl); ?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 text-center rounded-3 bg-primary-subtle border border-primary-subtle">
                            <div class="text-primary small fw-semibold">Agri Labourers</div>
                            <h4 class="fw-bold text-primary mb-0 mt-1"><?php echo number_format($mainAl); ?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 text-center rounded-3 bg-purple-subtle border border-purple-subtle">
                            <div class="small fw-semibold">Household Industry</div>
                            <h4 class="fw-bold mb-0 mt-1"><?php echo number_format($mainHh); ?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 text-center rounded-3 bg-warning-subtle border border-warning-subtle">
                            <div class="text-dark small fw-semibold">Other Workers</div>
                            <h4 class="fw-bold text-dark mb-0 mt-1"><?php echo number_format($mainOt); ?></h4>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-semibold text-dark"><i class="bi bi-pie-chart-fill me-1 text-primary"></i> Main Workers vs Marginal Workers</span>
                    </div>
                    <div class="d-flex gap-3 small text-muted flex-wrap">
                        <span>Main Workers: <strong class="text-dark"><?php echo number_format($mainWork); ?></strong></span> | 
                        <span>Marginal Workers (3-6 Months): <strong class="text-dark"><?php echo number_format($margWork); ?></strong></span> | 
                        <span>Non-Working Population: <strong class="text-dark"><?php echo number_format($nonWork); ?></strong></span>
                    </div>
                </div>
            </div>

            <!-- 4. Social Category Breakdown (SC / ST) -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark mb-3 font-heading border-bottom pb-3">
                    <i class="bi bi-people-fill text-info me-2 fs-4"></i> Social Category Demographics
                </h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold text-dark">Scheduled Castes (SC)</span>
                                <span class="fw-bold text-primary"><?php echo number_format($scTot); ?> (<?php echo $scPercent; ?>%)</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 10px; background-color: #e2e8f0;">
                                <div class="progress-bar progress-bar-sc rounded-pill" style="width: <?php echo $scPercent; ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold text-dark">Scheduled Tribes (ST)</span>
                                <span class="fw-bold text-info"><?php echo number_format($stTot); ?> (<?php echo $stPercent; ?>%)</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 10px; background-color: #e2e8f0;">
                                <div class="progress-bar progress-bar-st rounded-pill" style="width: <?php echo $stPercent; ?>%"></div>
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
                    <i class="bi bi-info-circle-fill text-primary me-2"></i> Quick Village Information
                </h5>

                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Village Name</span>
                        <strong class="text-dark"><?php echo $vName; ?></strong>
                    </li>
                    <?php if ($vHindi): ?>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Hindi Name</span>
                            <strong class="text-dark font-hindi"><?php echo $vHindi; ?></strong>
                        </li>
                    <?php endif; ?>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Block (Tehsil)</span>
                        <strong class="text-dark"><?php echo $blockName; ?></strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">District</span>
                        <strong class="text-dark">Saran</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">State</span>
                        <strong class="text-dark">Bihar</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Census 2011 Code</span>
                        <strong class="text-dark"><?php echo sanitizeInput($village['town_village_code']); ?></strong>
                    </li>
                    <?php if (!empty($village['village_lgd_code'])): ?>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">LGD Village Code</span>
                            <strong class="text-dark"><?php echo sanitizeInput($village['village_lgd_code']); ?></strong>
                        </li>
                    <?php endif; ?>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Pin Code</span>
                        <strong class="text-dark"><?php echo $pincode; ?></strong>
                    </li>
                </ul>
            </div>

            <!-- Add Listing Callout -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-gradient-primary text-white text-center">
                <div class="fs-1 mb-2 text-warning"><i class="bi bi-shop me-1"></i></div>
                <h5 class="fw-bold mb-2 font-heading">Are you from <?php echo $vName; ?>?</h5>
                <p class="text-white-50 small mb-3">Add your business, shop, clinic, or service profile in Saran Index free of cost!</p>
                <a href="add-contact.php" class="btn btn-warning text-dark fw-bold px-4 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-plus-circle-fill me-1"></i> Add My Business / Profile
                </a>
            </div>

            <!-- Other Villages in Block -->
            <?php 
            $nearby = getNearbyCensusVillages($blockName, $village['town_village_code'], 6);
            if (!empty($nearby)):
            ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3 font-heading border-bottom pb-3">
                        <i class="bi bi-geo-fill text-danger me-2"></i> Other Villages in <?php echo $blockName; ?> Block
                    </h5>

                    <div class="list-group list-group-flush">
                        <?php foreach ($nearby as $nv): 
                            $nvSlug = !empty($nv['unique_slug']) ? $nv['unique_slug'] : $nv['town_village_code'];
                        ?>
                            <a href="<?php echo getVillageUrl($nvSlug); ?>" class="list-group-item list-group-item-action px-0 py-2.5 d-flex align-items-center justify-content-between border-bottom">
                                <div>
                                    <div class="fw-semibold text-dark"><?php echo sanitizeInput($nv['name']); ?></div>
                                    <?php if (!empty($nv['name_hindi'])): ?>
                                        <small class="text-muted font-hindi"><?php echo sanitizeInput($nv['name_hindi']); ?></small>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-light text-primary border rounded-pill">
                                    Pop: <?php echo number_format($nv['pop_tot']); ?>
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
    require_once __DIR__ . '/includes/header.php';
    require_once __DIR__ . '/includes/footer.php';
    exit;
endif; // End of Village Detail View
?>

<?php
// ==========================================
// VILLAGE DIRECTORY INDEX & SEARCH PAGE
// ==========================================

$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$blockFilter = isset($_GET['block']) ? sanitizeInput($_GET['block']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 24;
$offset = ($page - 1) * $limit;

$villages = getCensusVillages($blockFilter, $search, $limit, $offset);
$totalVillages = getTotalCensusVillagesCount($blockFilter, $search);
$totalPages = ceil($totalVillages / $limit);

$page_title = "Saran District Village Directory – All Villages Census Data";
$meta_description = "Browse all 1,764 villages in Saran District (Chapra, Bihar). View official Census 2011 population, households, literacy rates, and village codes.";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Banner -->
<div class="bg-gradient-primary text-white py-5 text-center">
    <div class="container py-2">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">Saran District Master Village Directory</span>
        <h1 class="fw-bolder font-heading text-white display-4 mb-3">Villages of Saran District</h1>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 750px;">
            Explore official Census 2011 demographic data, population figures, literacy stats, and local information for all 1,764 villages across Saran district.
        </p>

        <!-- Search & Filter Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="village.php" method="GET" class="card border-0 shadow-lg p-2 rounded-4 bg-white">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-0 shadow-none py-2" placeholder="Search village by name or census code..." value="<?php echo sanitizeInput($search); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="block" class="form-select border-0 shadow-none py-2 text-dark fw-semibold">
                                <option value="">All 20 Blocks (Sub-Districts)</option>
                                <?php 
                                $allBlocks = ['Amnour', 'Baniapur', 'Chapra', 'Dariapur', 'Dighwara', 'Ekma', 'Garkha', 'Ishupur', 'Jalalpur', 'Lahladpur', 'Maker', 'Manjhi', 'Marhaura', 'Mashrakh', 'Nagra', 'Panapur', 'Parsa', 'Revelganj', 'Sonepur', 'Taraiya'];
                                foreach ($allBlocks as $b):
                                ?>
                                    <option value="<?php echo $b; ?>" <?php echo $blockFilter === $b ? 'selected' : ''; ?>><?php echo $b; ?> Block</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-warning w-100 fw-bold py-2 rounded-3 text-dark">
                                <i class="bi bi-filter"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Village List Grid -->
<div class="container py-5">
    
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark font-heading mb-1">
                <?php if ($blockFilter): ?>
                    Villages in <?php echo sanitizeInput($blockFilter); ?> Block
                <?php elseif ($search): ?>
                    Search Results for "<?php echo sanitizeInput($search); ?>"
                <?php else: ?>
                    All Villages Directory
                <?php endif; ?>
            </h4>
            <p class="text-muted small mb-0">Showing <?php echo number_format(min($totalVillages, $offset + 1)); ?> - <?php echo number_format(min($totalVillages, $offset + count($villages))); ?> of <strong><?php echo number_format($totalVillages); ?></strong> total villages</p>
        </div>

        <?php if ($search || $blockFilter): ?>
            <a href="village.php" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="bi bi-x-circle-fill me-1"></i> Clear Filters
            </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($villages)): ?>
        <div class="row g-4 mb-5">
            <?php foreach ($villages as $v): 
                $vCode = sanitizeInput($v['town_village_code']);
                $vSlug = !empty($v['unique_slug']) ? sanitizeInput($v['unique_slug']) : $vCode;
                $vName = sanitizeInput($v['name']);
                $vHindi = !empty($v['name_hindi']) ? sanitizeInput($v['name_hindi']) : '';
                $vBlock = !empty($v['block_name']) ? sanitizeInput($v['block_name']) : 'Saran';
                $pop = intval($v['pop_tot']);
                $house = intval($v['households']);
                $p06 = intval($v['p_06']);
                $lit = intval($v['lit_tot']);
                $effPop = $pop - $p06;
                $vLitRate = $effPop > 0 ? round(($lit / $effPop) * 100, 1) : 0;
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-shadow transition-all border-top border-4 border-primary">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                                <i class="bi bi-geo-alt-fill me-1"></i> <?php echo $vBlock; ?> Block
                            </span>
                            <span class="text-muted fs-7">Code: <?php echo $vCode; ?></span>
                        </div>

                        <h3 class="fw-bold text-dark mb-1 font-heading fs-5">
                            <a href="<?php echo getVillageUrl($vSlug); ?>" class="text-dark text-decoration-none hover-primary">
                                <?php echo $vName; ?>
                            </a>
                            <?php if ($vHindi): ?>
                                <span class="fs-6 text-muted font-hindi fw-normal"> (<?php echo $vHindi; ?>)</span>
                            <?php endif; ?>
                        </h3>

                        <div class="row g-2 text-center my-3 py-2 bg-light rounded-3">
                            <div class="col-4 border-end">
                                <div class="text-muted fs-7 fw-semibold">Population</div>
                                <div class="fw-bold text-dark small"><?php echo number_format($pop); ?></div>
                            </div>
                            <div class="col-4 border-end">
                                <div class="text-muted fs-7 fw-semibold">Households</div>
                                <div class="fw-bold text-dark small"><?php echo number_format($house); ?></div>
                            </div>
                            <div class="col-4">
                                <div class="text-muted fs-7 fw-semibold">Literacy</div>
                                <div class="fw-bold text-success small"><?php echo $vLitRate; ?>%</div>
                            </div>
                        </div>

                        <div class="mt-auto pt-2 d-flex align-items-center justify-content-between">
                            <span class="text-muted small"><i class="bi bi-people-fill me-1"></i> Workers: <?php echo number_format($v['tot_work_tot']); ?></span>
                            <a href="<?php echo getVillageUrl($vSlug); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                View Profile <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Village Pagination" class="d-flex justify-content-center">
                <ul class="pagination pagination-md gap-1">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link rounded-circle" href="village.php?search=<?php echo urlencode($search); ?>&block=<?php echo urlencode($blockFilter); ?>&page=<?php echo $page - 1; ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php 
                    $startP = max(1, $page - 2);
                    $endP = min($totalPages, $page + 2);
                    for ($p = $startP; $p <= $endP; $p++):
                    ?>
                        <li class="page-item <?php echo $p === $page ? 'active' : ''; ?>">
                            <a class="page-link rounded-circle fw-bold" href="village.php?search=<?php echo urlencode($search); ?>&block=<?php echo urlencode($blockFilter); ?>&page=<?php echo $p; ?>">
                                <?php echo $p; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link rounded-circle" href="village.php?search=<?php echo urlencode($search); ?>&block=<?php echo urlencode($blockFilter); ?>&page=<?php echo $page + 1; ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-5">
            <div class="text-muted fs-1 mb-3"><i class="bi bi-geo-alt"></i></div>
            <h4 class="fw-bold text-dark">No Villages Found</h4>
            <p class="text-muted">No villages matched your search criteria. Try clearing filters or searching for another village name.</p>
            <a href="village.php" class="btn btn-primary rounded-pill px-4">Browse All Villages</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

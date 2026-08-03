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

    $page_title = sanitizeInput($block['block_name']) . " Block ({$block['hindi_name']}) Census 2011 Data & Directory – Saran Index";
    $meta_description = "Official Census 2011 demographics, population (" . number_format($popTotal) . "), households, literacy rate ({$litRate}%), rural & urban stats for " . $block['block_name'] . " Block, Saran District (Chapra, Bihar).";
    require_once __DIR__ . '/includes/header.php';
    ?>

    <!-- Hero Header -->
    <div class="bg-gradient-primary text-white py-5 position-relative overflow-hidden">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 60%); pointer-events: none;"></div>
        <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at bottom right, rgba(59,130,246,0.3) 0%, rgba(0,0,0,0) 50%); pointer-events: none;"></div>
        
        <div class="container position-relative z-index-1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-3 small">
                    <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none hover-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="blocks" class="text-white-50 text-decoration-none hover-white">Blocks</a></li>
                    <li class="breadcrumb-item text-warning active" aria-current="page"><?php echo sanitizeInput($block['block_name']); ?></li>
                </ol>
            </nav>
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">CD Block / Sub-District</span>
                        <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-20 px-3 py-1.5 rounded-pill small">PIN: <?php echo sanitizeInput($block['pincode']); ?></span>
                    </div>
                    <h1 class="fw-bolder font-heading text-white display-4 mb-1 lh-sm"><?php echo sanitizeInput($block['block_name']); ?> Block</h1>
                    <p class="text-white-50 lead mb-0 fs-5"><?php echo sanitizeInput($block['hindi_name']); ?> • Saran District, Bihar</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="search.php?block=<?php echo sanitizeInput($block['slug']); ?>" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2.5 shadow-sm hover-lift">
                        <i class="bi bi-search me-1.5"></i> Browse Local Directory
                    </a>
                    <a href="villages?block=<?php echo urlencode($block['name']); ?>" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold shadow-sm hover-lift">
                        <i class="bi bi-houses-fill me-1.5 text-primary"></i> View Villages
                    </a>
                </div>
            </div>
        </div>
    </div>

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
                    <div class="position-absolute top-0 end-0 p-3 opacity-10 text-success fs-1"><i class="bi bi-house-door-fill"></i></div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                            <i class="bi bi-house-door-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold uppercase tracking-wider">Households</div>
                            <div class="fw-bolder fs-3 text-dark lh-sm"><?php echo number_format($households); ?></div>
                        </div>
                    </div>
                    <div class="progress rounded-pill bg-light" style="height: 6px;">
                        <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: 100%"></div>
                    </div>
                    <div class="mt-2 small text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-diagram-3-fill text-success me-1"></i>
                        <span>Panchayats: <strong><?php echo sanitizeInput($block['total_panchayats']); ?></strong></span>
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
$meta_description = "Explore official Census 2011 demographic data, population, households, literacy stats, and local directory for all 20 blocks of Saran (Chapra).";
require_once __DIR__ . '/includes/header.php';

$blocks = getBlocks();
?>

<!-- Banner Section -->
<div class="bg-gradient-primary text-white py-5 position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 60%); pointer-events: none;"></div>
    <div class="container text-center position-relative z-index-1">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider mb-2">Administrative Directory</span>
        <h1 class="fw-bolder font-heading text-white display-4 mb-2">All 20 Blocks of Saran District</h1>
        <p class="text-white-50 lead mx-auto mb-0" style="max-width: 650px;">Explore official Census 2011 demographic data, population, literacy stats, businesses, panchayats, and schools in every block of Saran (Chapra).</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($blocks as $blk): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4 hover-lift bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-primary-subtle text-primary p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-geo-alt-fill fs-4"></i>
                        </div>
                        <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small fw-medium">PIN: <?php echo sanitizeInput($blk['pincode']); ?></span>
                    </div>

                    <h4 class="fw-bold text-dark font-heading mb-1">
                        <a href="<?php echo getBlockUrl($blk['slug']); ?>" class="text-dark text-decoration-none hover-primary transition-all">
                            <?php echo sanitizeInput($blk['block_name']); ?>
                        </a>
                    </h4>
                    <div class="text-muted small fw-semibold mb-3"><?php echo sanitizeInput($blk['hindi_name']); ?></div>

                    <!-- Census 2011 Summary Badges -->
                    <?php if (!empty($blk['pop_tot'])): ?>
                        <div class="bg-light rounded-3 p-3 mb-3 border border-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted"><i class="bi bi-people-fill me-1.5 text-primary"></i> Total Population:</span>
                                <span class="fw-bold small text-dark"><?php echo number_format($blk['pop_tot']); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted"><i class="bi bi-house-door-fill me-1.5 text-secondary"></i> Households:</span>
                                <span class="fw-bold small text-dark"><?php echo number_format($blk['households']); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted"><i class="bi bi-pie-chart-fill me-1.5 text-info"></i> Rural / Urban:</span>
                                <span class="badge bg-white text-dark border small fw-semibold">
                                    <?php echo number_format($blk['pop_rural']); ?> / <?php echo number_format($blk['pop_urban']); ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto gap-2">
                        <a href="<?php echo getBlockUrl($blk['slug']); ?>" class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3">
                            Block Details <i class="bi bi-info-circle ms-1"></i>
                        </a>
                        <a href="search.php?block=<?php echo sanitizeInput($blk['slug']); ?>" class="btn btn-sm btn-primary rounded-pill fw-bold px-3 shadow-sm">
                            Directory <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 2.5rem rgba(0, 0, 0, 0.1) !important;
}
.hover-primary:hover {
    color: var(--primary-color) !important;
}
.hover-white:hover {
    color: #ffffff !important;
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

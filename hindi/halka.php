<?php
require_once __DIR__ . '/includes/functions.php';

$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$blockFilter = isset($_GET['block']) ? sanitizeInput($_GET['block']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 24;
$offset = ($page - 1) * $limit;

$records = getHalkaRecords($blockFilter, $search, $limit, $offset);
$totalRecords = getTotalHalkaCount($blockFilter, $search);
$totalPages = ceil($totalRecords / $limit);

$halkaBlocks = getHalkaBlocks();
$stats = getHalkaStats();

$page_title = "हलका एवं राजस्व गांव (मौजा) निर्देशिका – सारण इंडेक्स";
$meta_description = "सारण जिले (छपरा) के सभी 1,807 हलका एवं राजस्व गांवों (मौजा) की खोज करें। आधिकारिक हलका नंबर, मौजा कोड और अंचल की जानकारी देखें।";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header -->
<div class="bg-gradient-primary text-white py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-3 text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
            <i class="bi bi-geo-alt-fill me-1"></i> राजस्व निर्देशिका (Revenue Circle Directory)
        </span>
        <h1 class="fw-bold font-heading text-white display-6 mb-2">
            हलका एवं राजस्व गांव निर्देशिका
        </h1>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 780px;">
            सारण (छपरा) के सभी 20 अंचलों (प्रखंडों) में आधिकारिक हलका नंबर एवं मौजा / राजस्व गांवों की खोज करें।
        </p>

        <!-- Search Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="halka.php" method="GET" class="card border-0 shadow-lg p-2 rounded-4 bg-white">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-0 shadow-none py-2" placeholder="मौजा नाम, हलका नाम या कोड खोजें..." value="<?php echo sanitizeInput($search); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="block" class="form-select border-0 shadow-none py-2 text-dark fw-semibold">
                                <option value="">सभी 20 प्रखंड (अंचल)</option>
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

<!-- Main Content Area -->
<div class="container py-5">
    
    <!-- KPI Summary Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-blue">
                <div class="text-primary fs-1 mb-2"><i class="bi bi-map-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo number_format($stats['maujas']); ?></h3>
                <p class="text-muted small mb-0 fw-semibold">कुल राजस्व गांव (मौजा)</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-green">
                <div class="text-success fs-1 mb-2"><i class="bi bi-diagram-3-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo number_format($stats['halkas']); ?></h3>
                <p class="text-muted small mb-0 fw-semibold">कुल हलका (Halka Circles)</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-amber">
                <div class="text-warning fs-1 mb-2"><i class="bi bi-building-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo number_format($stats['blocks']); ?></h3>
                <p class="text-muted small mb-0 fw-semibold">राजस्व अंचल (प्रखंड)</p>
            </div>
        </div>
    </div>

    <!-- Results Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark font-heading mb-1">
                <?php if ($blockFilter): ?>
                    <?php echo sanitizeInput($blockFilter); ?> प्रखंड में हलका एवं मौजा रिकॉर्ड
                <?php elseif ($search): ?>
                    "<?php echo sanitizeInput($search); ?>" के लिए खोज परिणाम
                <?php else: ?>
                    सभी हलका एवं राजस्व गांव रिकॉर्ड
                <?php endif; ?>
            </h4>
            <p class="text-muted small mb-0">कुल <strong><?php echo number_format($totalRecords); ?></strong> रिकॉर्ड में से <?php echo number_format(min($totalRecords, $offset + 1)); ?> - <?php echo number_format(min($totalRecords, $offset + count($records))); ?> दिखाए जा रहे हैं</p>
        </div>

        <?php if ($search || $blockFilter): ?>
            <a href="halka.php" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="bi bi-x-circle-fill me-1"></i> फ़िल्टर हटाएं
            </a>
        <?php endif; ?>
    </div>

    <!-- Grid List -->
    <?php if (!empty($records)): ?>
        <div class="row g-4 mb-5">
            <?php foreach ($records as $item): 
                $mName = sanitizeInput($item['mauja_name']);
                $mEng = sanitizeInput($item['mauja_english'] ?? '');
                $mCode = sanitizeInput($item['mauja_code']);
                $hName = sanitizeInput($item['halka_name']);
                $hEng = sanitizeInput($item['halka_english'] ?? '');
                $hCode = intval($item['halka_code']);
                $bName = sanitizeInput($item['block']);
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-shadow transition-all border-top border-4 border-primary">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                                <i class="bi bi-geo-alt-fill me-1"></i> <?php echo $bName; ?> प्रखंड
                            </span>
                            <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill fs-7">
                                मौजा कोड: <?php echo $mCode; ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <span class="text-muted fs-7 text-uppercase fw-bold">राजस्व गांव / मौजा</span>
                            <h3 class="fw-bold text-dark mb-0 font-heading fs-4">
                                <?php echo $mName; ?>
                            </h3>
                            <?php if (!empty($mEng)): ?>
                                <span class="text-secondary small fw-medium"><?php echo $mEng; ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted fs-7 fw-semibold">हलका वृत्त</div>
                                    <div class="fw-bold text-dark fs-6">
                                        हलका <?php echo $hCode; ?>: <?php echo $hName; ?>
                                    </div>
                                    <?php if (!empty($hEng)): ?>
                                        <div class="text-muted small fs-7"><?php echo $hEng; ?></div>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-warning text-dark fw-bold rounded-circle p-2 fs-7">
                                    H-<?php echo $hCode; ?>
                                </span>
                            </div>
                        </div>

                        <div class="mt-auto pt-2 border-top">
                            <span class="text-muted small"><i class="bi bi-building me-1"></i> अंचल (प्रखंड): <strong><?php echo $bName; ?></strong></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Halka Pagination" class="d-flex justify-content-center">
                <ul class="pagination pagination-md gap-1">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link rounded-circle" href="halka.php?search=<?php echo urlencode($search); ?>&block=<?php echo urlencode($blockFilter); ?>&page=<?php echo $page - 1; ?>">
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
                            <a class="page-link rounded-circle fw-bold" href="halka.php?search=<?php echo urlencode($search); ?>&block=<?php echo urlencode($blockFilter); ?>&page=<?php echo $p; ?>">
                                <?php echo $p; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link rounded-circle" href="halka.php?search=<?php echo urlencode($search); ?>&block=<?php echo urlencode($blockFilter); ?>&page=<?php echo $page + 1; ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-5">
            <div class="text-muted fs-1 mb-3"><i class="bi bi-geo"></i></div>
            <h4 class="fw-bold text-dark">कोई हलका या मौजा रिकॉर्ड नहीं मिला</h4>
            <p class="text-muted">आपकी खोज के अनुसार कोई परिणाम नहीं मिला। कृपया फ़िल्टर बदलें या दूसरा गांव/हलका नाम खोजें।</p>
            <a href="halka.php" class="btn btn-primary rounded-pill px-4">सभी हलका रिकॉर्ड देखें</a>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

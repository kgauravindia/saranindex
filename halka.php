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

$page_title = "Halka & Revenue Village (Mauja) Directory – Saran Index";
$meta_description = "Search all 1,807 Halka and Revenue Villages (मौजा) in Saran District (Chapra). View official Halka numbers, Mauja codes, and Block revenue circles.";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header -->
<div class="bg-gradient-primary text-white py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-3 text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
            <i class="bi bi-geo-alt-fill me-1"></i> Revenue Circle Directory (राजस्व निर्देशिका)
        </span>
        <h1 class="fw-bold font-heading text-white display-6 mb-2">
            Halka & Revenue Village Directory
        </h1>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 780px;">
            Search and explore official Halka numbers (हलका) and Mauja/Revenue Villages (मौजा / राजस्व गांव) across all 20 Sub-Districts in Saran (Chapra), Bihar.
        </p>

        <!-- Search Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="halka.php" method="GET" class="card border-0 shadow-lg p-2 rounded-4 bg-white">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-0 shadow-none py-2" placeholder="Search Mauja name, Halka name, or Code..." value="<?php echo sanitizeInput($search); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="block" class="form-select border-0 shadow-none py-2 text-dark fw-semibold">
                                <option value="">All 20 Blocks</option>
                                <?php foreach ($halkaBlocks as $b): ?>
                                    <option value="<?php echo sanitizeInput($b); ?>" <?php echo ($blockFilter === $b) ? 'selected' : ''; ?>>
                                        <?php echo sanitizeInput($b); ?> Block
                                    </option>
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

<!-- Main Content Area -->
<div class="container py-5">
    
    <!-- KPI Summary Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-blue">
                <div class="text-primary fs-1 mb-2"><i class="bi bi-map-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo number_format($stats['maujas']); ?></h3>
                <p class="text-muted small mb-0 fw-semibold">Total Revenue Villages (मौजा)</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-green">
                <div class="text-success fs-1 mb-2"><i class="bi bi-diagram-3-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo number_format($stats['halkas']); ?></h3>
                <p class="text-muted small mb-0 fw-semibold">Total Halka Circles (हलका)</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-amber">
                <div class="text-warning fs-1 mb-2"><i class="bi bi-building-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2"><?php echo number_format($stats['blocks']); ?></h3>
                <p class="text-muted small mb-0 fw-semibold">Revenue Sub-Districts (अंचल)</p>
            </div>
        </div>
    </div>

    <!-- Results Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark font-heading mb-1">
                <?php if ($blockFilter): ?>
                    Halka & Mauja Records in <?php echo sanitizeInput($blockFilter); ?> Block
                <?php elseif ($search): ?>
                    Search Results for "<?php echo sanitizeInput($search); ?>"
                <?php else: ?>
                    All Halka & Revenue Village Records
                <?php endif; ?>
            </h4>
            <p class="text-muted small mb-0">Showing <?php echo number_format(min($totalRecords, $offset + 1)); ?> - <?php echo number_format(min($totalRecords, $offset + count($records))); ?> of <strong><?php echo number_format($totalRecords); ?></strong> total records</p>
        </div>

        <?php if ($search || $blockFilter): ?>
            <a href="halka.php" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="bi bi-x-circle-fill me-1"></i> Clear Filters
            </a>
        <?php endif; ?>
    </div>

    <!-- Grid List -->
    <?php if (!empty($records)): ?>
        <div class="row g-4 mb-5">
            <?php foreach ($records as $item): 
                $mName = sanitizeInput($item['mauja_name']);
                $mCode = sanitizeInput($item['mauja_code']);
                $hName = sanitizeInput($item['halka_name']);
                $hCode = intval($item['halka_code']);
                $bName = sanitizeInput($item['block']);
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-shadow transition-all border-top border-4 border-primary">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                                <i class="bi bi-geo-alt-fill me-1"></i> <?php echo $bName; ?> Block
                            </span>
                            <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill fs-7">
                                Mauja Code: <?php echo $mCode; ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <span class="text-muted fs-7 text-uppercase fw-bold">Revenue Village / Mauja</span>
                            <h3 class="fw-bold text-dark mb-0 font-heading font-hindi fs-4">
                                <?php echo $mName; ?>
                            </h3>
                        </div>

                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted fs-7 fw-semibold">Halka Circle (हलका)</div>
                                    <div class="fw-bold text-dark font-hindi fs-6">
                                        Halka <?php echo $hCode; ?>: <?php echo $hName; ?>
                                    </div>
                                </div>
                                <span class="badge bg-warning text-dark fw-bold rounded-circle p-2 fs-7">
                                    H-<?php echo $hCode; ?>
                                </span>
                            </div>
                        </div>

                        <div class="mt-auto pt-2 border-top">
                            <span class="text-muted small"><i class="bi bi-building me-1"></i> Revenue Circle (Block): <strong><?php echo $bName; ?></strong></span>
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
            <h4 class="fw-bold text-dark">No Halka or Mauja Records Found</h4>
            <p class="text-muted">No records matched your search filters. Try clearing filters or searching for another village or halka name.</p>
            <a href="halka.php" class="btn btn-primary rounded-pill px-4">Browse All Halka Records</a>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
$header_title = "Dashboard Overview";
require_once __DIR__ . '/includes/header.php';

// Handle quick inline action requests (Approve, Reject, Toggle Verified)
$msg = '';
$msg_type = 'success';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $target_id = intval($_GET['id']);

    if ($action === 'approve') {
        $reason = '';
        if (!isListingUserMobileActive($target_id, $reason)) {
            $msg = "Cannot approve Listing #{$target_id}: " . $reason;
            $msg_type = "danger";
        } elseif (updateListingStatus($target_id, 'ACTIVE')) {
            $msg = "Listing #{$target_id} approved and published successfully!";
        } else {
            $msg = "Failed to update listing status.";
            $msg_type = "danger";
        }
    } elseif ($action === 'reject') {
        if (updateListingStatus($target_id, 'REJECTED')) {
            $msg = "Listing #{$target_id} has been marked as rejected.";
            $msg_type = "warning";
        }
    } elseif ($action === 'toggle_verified') {
        if (toggleListingVerified($target_id)) {
            $msg = "Updated verification status for listing #{$target_id}.";
        }
    } elseif ($action === 'delete') {
        if (deleteListing($target_id)) {
            $msg = "Listing #{$target_id} deleted successfully.";
            $msg_type = "danger";
        }
    }
}

$stats = getAdminStats();
$dailyAnalytics = getMultiPeriodAnalyticsData();
$pending_claims_count = count(getClaimsList('PENDING'));
$recentListings = getAllAdminListings(null, null);
// Limit to top 8 recent listings for dashboard summary
$recentListings = array_slice($recentListings, 0, 8);
?>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Welcome & Overview Banner -->
<div class="card border-0 bg-primary text-white rounded-3 p-4 mb-4 shadow-sm" style="background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
        <div class="mb-3 mb-md-0">
            <h4 class="fw-bold mb-1">Welcome back, <?php echo sanitizeInput($_SESSION['admin_full_name'] ?? 'Administrator'); ?> 👋</h4>
            <p class="mb-0 text-white-50 small">Saran District Digital Directory Command Center. Manage listings, verifications, and user requests.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="bulk_upload.php" class="btn btn-warning text-dark fw-bold btn-sm px-3 shadow-sm">
                <i class="bi bi-cloud-upload me-1"></i> Bulk Upload CSV
            </a>
            <a href="listing_edit.php" class="btn btn-outline-light text-white fw-bold btn-sm px-3 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Listing
            </a>
            <a href="categories.php" class="btn btn-outline-light btn-sm px-3 fw-medium">
                <i class="bi bi-folder-plus me-1"></i> Add Category
            </a>
        </div>
    </div>
</div>

<!-- Metrics & Stat Cards Grid -->
<div class="row g-3 mb-4">
    <!-- Card 1: Total Listings -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="listings.php" class="text-decoration-none">
            <div class="stat-card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Total Listings</span>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-5">
                        <i class="bi bi-collection"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-dark mb-1"><?php echo number_format($stats['total_listings']); ?></h2>
                <div class="d-flex align-items-center gap-2 small">
                    <span class="badge bg-success-subtle text-success fw-bold px-2 py-0.5 rounded-pill"><i class="bi bi-check-circle me-1"></i><?php echo number_format($stats['active_listings']); ?> Active</span>
                    <span class="badge bg-warning-subtle text-dark fw-semibold px-2 py-0.5 rounded-pill"><?php echo number_format($stats['pending_listings']); ?> Pending</span>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 2: Pending Approvals -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="listings.php?status=PENDING" class="text-decoration-none">
            <div class="stat-card p-3 h-100 shadow-sm border <?php echo ($stats['pending_listings'] > 0) ? 'border-warning bg-warning-subtle bg-opacity-10' : 'border-0'; ?>">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Pending Approvals</span>
                    <div class="stat-icon bg-warning bg-opacity-20 text-warning rounded-circle p-2 fs-5">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-warning mb-1"><?php echo number_format($stats['pending_listings']); ?></h2>
                <small class="text-muted"><i class="bi bi-exclamation-circle me-1 text-warning"></i>Requires moderator review</small>
            </div>
        </a>
    </div>

    <!-- Card 3: Pending Claims -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="claims.php?status=PENDING" class="text-decoration-none">
            <div class="stat-card p-3 h-100 shadow-sm border <?php echo ($pending_claims_count > 0) ? 'border-warning bg-warning-subtle bg-opacity-15' : 'border-0'; ?>">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Business Claims</span>
                    <div class="stat-icon bg-warning text-dark rounded-circle p-2 fs-5">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-dark mb-1"><?php echo number_format($pending_claims_count); ?></h2>
                <small class="text-warning-emphasis fw-semibold"><i class="bi bi-shield-exclamation me-1"></i>Ownership claims waiting</small>
            </div>
        </a>
    </div>

    <!-- Card 4: Verified Entities -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="listings.php?search=verified" class="text-decoration-none">
            <div class="stat-card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Verified Entities</span>
                    <div class="stat-icon bg-success bg-opacity-10 text-success rounded-circle p-2 fs-5">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-success mb-1"><?php echo number_format($stats['verified_listings']); ?></h2>
                <small class="text-success fw-semibold">
                    <i class="bi bi-shield-check me-1"></i>
                    <?php echo ($stats['total_listings'] > 0) ? round(($stats['verified_listings'] / $stats['total_listings']) * 100, 1) . '% verified' : 'Verified badge active'; ?>
                </small>
            </div>
        </a>
    </div>

    <!-- Card 5: Paid Plans (Platinum & Gold) -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="payments.php" class="text-decoration-none">
            <div class="stat-card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">VIP & Gold Plans</span>
                    <div class="stat-icon bg-warning bg-opacity-20 text-dark rounded-circle p-2 fs-5">
                        <i class="bi bi-crown-fill text-warning"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-dark mb-1"><?php echo number_format($stats['platinum_listings'] + $stats['gold_listings']); ?></h2>
                <div class="d-flex align-items-center gap-1.5 small">
                    <span class="badge bg-warning text-dark fw-bold px-2 py-0.5 rounded-pill"><i class="bi bi-crown-fill me-1 text-danger"></i><?php echo number_format($stats['platinum_listings']); ?> VIP</span>
                    <span class="badge bg-primary text-white fw-semibold px-2 py-0.5 rounded-pill"><?php echo number_format($stats['gold_listings']); ?> Gold</span>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 6: Registered Users -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="users.php" class="text-decoration-none">
            <div class="stat-card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Registered Users</span>
                    <div class="stat-icon bg-info bg-opacity-10 text-info rounded-circle p-2 fs-5">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-dark mb-1"><?php echo number_format($stats['total_users']); ?></h2>
                <small class="text-muted"><i class="bi bi-person-check me-1 text-info"></i>Community accounts</small>
            </div>
        </a>
    </div>

    <!-- Card 7: Verticals & Subcategories -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="categories.php" class="text-decoration-none">
            <div class="stat-card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Categories</span>
                    <div class="stat-icon bg-indigo bg-opacity-10 text-primary rounded-circle p-2 fs-5">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-dark mb-1"><?php echo number_format($stats['total_categories']); ?></h2>
                <small class="text-muted"><i class="bi bi-diagram-3 me-1 text-primary"></i><?php echo number_format($stats['total_subcategories']); ?> subcategories</small>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="blocks.php" class="text-decoration-none">
            <div class="stat-card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Saran Blocks</span>
                    <div class="stat-icon bg-secondary bg-opacity-10 text-secondary rounded-circle p-2 fs-5">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-dark mb-1">20 <span class="fs-6 text-muted font-weight-normal">Blocks</span></h2>
                <small class="text-muted"><i class="bi bi-houses me-1"></i><?php echo number_format($stats['total_panchayats']); ?> Panchayats • <?php echo number_format($stats['total_halkas']); ?> Mouzas</small>
            </div>
        </a>
    </div>

    <!-- Card 10: Total Listing Views -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="listings.php" class="text-decoration-none">
            <div class="stat-card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Listing Views</span>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-5">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-primary mb-1"><?php echo number_format($stats['total_listing_views'] ?? 0); ?></h2>
                <small class="text-muted"><i class="bi bi-graph-up-arrow me-1 text-primary"></i>Total business impressions</small>
            </div>
        </a>
    </div>

    <!-- Card 11: Total Profile Views -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="users.php" class="text-decoration-none">
            <div class="stat-card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Profile Views</span>
                    <div class="stat-icon bg-info bg-opacity-10 text-info rounded-circle p-2 fs-5">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-info mb-1"><?php echo number_format($stats['total_profile_views'] ?? 0); ?></h2>
                <small class="text-muted"><i class="bi bi-people me-1 text-info"></i>Professional profile views</small>
            </div>
        </a>
    </div>
</div>

<!-- Daily Analytics Trends Graph Section -->
<div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1 rounded-pill">
                        <i class="bi bi-graph-up-arrow me-1"></i> Live Analytics
                    </span>
                    <h5 class="mb-0 fw-bold text-dark">Daily Directory Activity & Growth Trends</h5>
                </div>
                <p class="text-muted small mb-0">Daily breakdown of newly registered listings, user onboarding, and verification activity across Saran district.</p>
            </div>
            
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Metric Dataset Switcher -->
                <div class="btn-group btn-group-sm" role="group" aria-label="Metric Filters" id="analyticsMetricToggle">
                    <button type="button" class="btn btn-outline-secondary active" data-metric="all">
                        <i class="bi bi-layers me-1"></i>All Metrics
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-metric="listings">
                        <i class="bi bi-collection text-primary me-1"></i>Listings
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-metric="users">
                        <i class="bi bi-people text-success me-1"></i>Users
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-metric="verified">
                        <i class="bi bi-patch-check text-purple me-1"></i>Verified
                    </button>
                </div>

                <!-- Timeframe Switcher (7D, 14D, 30D, 60D) -->
                <div class="btn-group btn-group-sm" role="group" aria-label="Timeframe" id="analyticsTimeframeToggle">
                    <button type="button" class="btn btn-outline-primary" data-days="7">7D</button>
                    <button type="button" class="btn btn-outline-primary" data-days="14">14D</button>
                    <button type="button" class="btn btn-outline-primary active" data-days="30">30D</button>
                    <button type="button" class="btn btn-outline-primary" data-days="60">60D</button>
                </div>

                <a href="analytics.php" class="btn btn-sm btn-light border text-dark fw-semibold" title="Open Deep Dive Analytics">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Full Report
                </a>
            </div>
        </div>
    </div>

    <!-- Quick KPI Highlights Ribbon -->
    <div class="card-body bg-light bg-opacity-50 py-2.5 px-4 border-bottom">
        <div class="row g-3 text-center text-md-start align-items-center">
            <div class="col-6 col-md-3 border-end">
                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Today's Listings</small>
                <div class="d-flex align-items-baseline gap-1.5 justify-content-center justify-content-md-start">
                    <span class="fs-5 fw-bold text-primary" id="kpiTodayListings">+<?php echo number_format($dailyAnalytics['30']['summary']['today_listings']); ?></span>
                    <span class="badge bg-primary-subtle text-primary small">Today</span>
                </div>
            </div>
            <div class="col-6 col-md-3 border-end">
                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Today's Users</small>
                <div class="d-flex align-items-baseline gap-1.5 justify-content-center justify-content-md-start">
                    <span class="fs-5 fw-bold text-success" id="kpiTodayUsers">+<?php echo number_format($dailyAnalytics['30']['summary']['today_users']); ?></span>
                    <span class="badge bg-success-subtle text-success small">Today</span>
                </div>
            </div>
            <div class="col-6 col-md-3 border-end">
                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Period Total Growth</small>
                <div class="d-flex align-items-baseline gap-1.5 justify-content-center justify-content-md-start">
                    <span class="fs-5 fw-bold text-dark" id="kpiPeriodTotal"><?php echo number_format($dailyAnalytics['30']['summary']['total_listings']); ?> listings</span>
                    <small class="text-muted" id="kpiPeriodAvg">(<?php echo $dailyAnalytics['30']['summary']['avg_daily_listings']; ?>/day)</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Peak Velocity Day</small>
                <div class="d-flex align-items-baseline gap-1.5 justify-content-center justify-content-md-start">
                    <span class="fs-5 fw-bold text-indigo" id="kpiPeakCount"><?php echo number_format($dailyAnalytics['30']['summary']['peak_count']); ?></span>
                    <small class="text-muted" id="kpiPeakDate">on <?php echo $dailyAnalytics['30']['summary']['peak_date']; ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Canvas Area -->
    <div class="card-body p-3 p-md-4">
        <div style="position: relative; width: 100%; height: 320px;">
            <canvas id="dailyAnalyticsCanvas"></canvas>
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3 pt-2 border-top text-muted small">
            <div class="d-flex align-items-center gap-3">
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #2563EB;">&nbsp;</span> New Listings</span>
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #10B981;">&nbsp;</span> User Signups</span>
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #8B5CF6;">&nbsp;</span> Verified Listings</span>
            </div>
            <div class="text-end">
                <i class="bi bi-info-circle me-1"></i> Data aggregates automatically from Saran Index directory records.
            </div>
        </div>
    </div>
</div>

<!-- Detailed Analytics & Breakdown Section -->
<div class="row g-4 mb-4">
    <!-- Block Geographic Distribution Breakdown -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-geo-alt-fill me-2 text-danger"></i>Listings by Block (All 20 Blocks)</h6>
                <a href="../blocks" target="_blank" class="badge bg-light text-primary border text-decoration-none">View Public Directory <i class="bi bi-box-arrow-up-right ms-1"></i></a>
            </div>
            <div class="card-body p-3 overflow-auto" style="max-height: 420px;">
                <?php if (!empty($stats['block_breakdown'])): ?>
                    <div class="d-flex flex-column gap-3">
                        <?php 
                        $maxBlockCount = max(array_column($stats['block_breakdown'], 'listing_count'));
                        $maxBlockCount = max(1, $maxBlockCount);
                        foreach ($stats['block_breakdown'] as $blkItem): 
                            $bPct = round(($blkItem['listing_count'] / $maxBlockCount) * 100);
                        ?>
                            <a href="listings.php?search=<?php echo urlencode($blkItem['block_name']); ?>" class="text-decoration-none">
                                <div class="d-flex align-items-center justify-content-between small mb-1">
                                    <span class="fw-semibold text-dark">
                                        <i class="bi bi-pin-map text-primary me-1"></i><?php echo sanitizeInput($blkItem['block_name']); ?> Block
                                    </span>
                                    <span class="fw-bold text-primary"><?php echo number_format($blkItem['listing_count']); ?> listings</span>
                                </div>
                                <div class="progress" style="height: 7px; background-color: #f1f5f9;">
                                    <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: <?php echo max(4, $bPct); ?>%;" aria-valuenow="<?php echo $bPct; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4 my-0">No block breakdown data available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Category Verticals Breakdown -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-grid-fill me-2 text-warning"></i>Listings by Core Category</h6>
                <a href="categories.php" class="badge bg-light text-primary border text-decoration-none">Manage Categories <i class="bi bi-gear ms-1"></i></a>
            </div>
            <div class="card-body p-3 overflow-auto" style="max-height: 420px;">
                <?php if (!empty($stats['category_breakdown'])): ?>
                    <div class="d-flex flex-column gap-3">
                        <?php 
                        $maxCatCount = max(array_column($stats['category_breakdown'], 'listing_count'));
                        $maxCatCount = max(1, $maxCatCount);
                        foreach ($stats['category_breakdown'] as $catItem): 
                            $cPct = round(($catItem['listing_count'] / $maxCatCount) * 100);
                            $icon = !empty($catItem['icon']) ? $catItem['icon'] : 'bi-folder';
                        ?>
                            <a href="listings.php?search=<?php echo urlencode($catItem['category_name']); ?>" class="text-decoration-none">
                                <div class="d-flex align-items-center justify-content-between small mb-1">
                                    <span class="fw-semibold text-dark">
                                        <i class="bi <?php echo sanitizeInput($icon); ?> text-warning me-1.5"></i><?php echo sanitizeInput($catItem['category_name']); ?>
                                    </span>
                                    <span class="fw-bold text-dark"><?php echo number_format($catItem['listing_count']); ?> listings</span>
                                </div>
                                <div class="progress" style="height: 7px; background-color: #f1f5f9;">
                                    <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: <?php echo max(4, $cPct); ?>%;" aria-valuenow="<?php echo $cPct; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4 my-0">No category breakdown data available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Listings Section -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Directory Submissions</h6>
            <small class="text-muted">Listings added or submitted by community users</small>
        </div>
        <a href="listings.php" class="btn btn-outline-primary btn-sm fw-semibold rounded-pill px-3">
            View All Listings <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th>Title & Category</th>
                    <th>Block / Contact</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Verified</th>
                    <th>Featured</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentListings)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No listings found in the system.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentListings as $item): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark"><?php echo sanitizeInput($item['title']); ?></div>
                                <?php if (!empty($item['hindi_title'])): ?>
                                    <small class="text-muted me-2"><?php echo sanitizeInput($item['hindi_title']); ?></small>
                                <?php endif; ?>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    <span class="badge bg-light text-secondary border small" title="Category ID: #<?php echo $item['category_id']; ?>">
                                        <i class="bi bi-folder me-1"></i>Cat #<?php echo $item['category_id']; ?>: <?php echo sanitizeInput($item['category_name'] ?? 'General'); ?>
                                    </span>
                                    <?php if (!empty($item['subcategory_name'])): ?>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle small" title="Subcategory ID: #<?php echo $item['subcategory_id']; ?>">
                                            <i class="bi bi-diagram-3 me-1"></i>Sub #<?php echo $item['subcategory_id']; ?>: <?php echo sanitizeInput($item['subcategory_name']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark"><i class="bi bi-geo-alt text-primary me-1"></i><?php echo sanitizeInput($item['block_name'] ?? 'Chapra Sadar'); ?></div>
                                <div class="small text-muted"><i class="bi bi-telephone me-1"></i><?php echo sanitizeInput($item['mobile']); ?></div>
                            </td>
                            <td>
                                <?php 
                                    $st = strtoupper($item['status'] ?? 'ACTIVE');
                                    if ($st === 'ACTIVE') {
                                        echo '<span class="badge badge-status-active"><i class="bi bi-check-circle me-1"></i>Active</span>';
                                    } elseif ($st === 'PENDING') {
                                        echo '<span class="badge badge-status-pending"><i class="bi bi-clock me-1"></i>Pending</span>';
                                    } else {
                                        echo '<span class="badge badge-status-rejected"><i class="bi bi-x-circle me-1"></i>Rejected</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    <i class="bi bi-eye text-primary me-1"></i><?php echo number_format($item['view_count'] ?? 0); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (($item['is_verified'] ?? 'NO') === 'YES'): ?>
                                    <a href="index.php?action=toggle_verified&id=<?php echo $item['id']; ?>" class="badge bg-success text-decoration-none" title="Click to unverify"><i class="bi bi-patch-check-fill me-1"></i>YES</a>
                                <?php else: ?>
                                    <a href="index.php?action=toggle_verified&id=<?php echo $item['id']; ?>" class="badge bg-secondary text-decoration-none" title="Click to verify"><i class="bi bi-dash-circle me-1"></i>NO</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (($item['is_featured'] ?? 'NO') === 'YES'): ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Featured</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border">Normal</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <?php if (strtoupper($item['status'] ?? 'ACTIVE') === 'PENDING'): ?>
                                        <a href="index.php?action=approve&id=<?php echo $item['id']; ?>" class="btn btn-success" title="Approve Listing">
                                            <i class="bi bi-check-lg"></i>
                                        </a>
                                        <a href="index.php?action=reject&id=<?php echo $item['id']; ?>" class="btn btn-outline-warning" title="Reject Listing">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="listing_edit.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="../<?php echo getListingUrl($item['slug']); ?>" target="_blank" class="btn btn-outline-info" title="View Public Profile">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="index.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this listing?');" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Daily Analytics Graph Client Controller Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rawData = <?php echo json_encode($dailyAnalytics, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    let currentDays = '30';
    let currentMetric = 'all';
    let chartInstance = null;

    const ctx = document.getElementById('dailyAnalyticsCanvas');
    if (!ctx) return;

    // Helper for linear gradient creation
    function createGradients(chart) {
        const { ctx: chartCtx, chartArea } = chart;
        if (!chartArea) return null;

        const blueGrad = chartCtx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        blueGrad.addColorStop(0, 'rgba(37, 99, 235, 0.30)');
        blueGrad.addColorStop(1, 'rgba(37, 99, 235, 0.00)');

        const greenGrad = chartCtx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        greenGrad.addColorStop(0, 'rgba(16, 185, 129, 0.28)');
        greenGrad.addColorStop(1, 'rgba(16, 185, 129, 0.00)');

        const purpleGrad = chartCtx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        purpleGrad.addColorStop(0, 'rgba(139, 92, 246, 0.25)');
        purpleGrad.addColorStop(1, 'rgba(139, 92, 246, 0.00)');

        return { blueGrad, greenGrad, purpleGrad };
    }

    function buildDatasets(periodData, metricType, gradients) {
        const datasets = [];

        const listingsSet = {
            label: 'New Listings',
            data: periodData.chart.listings,
            borderColor: '#2563EB',
            backgroundColor: gradients ? gradients.blueGrad : 'rgba(37, 99, 235, 0.1)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#2563EB',
            pointBorderColor: '#FFFFFF',
            pointBorderWidth: 2,
            pointRadius: periodData.chart.labels.length > 30 ? 2 : 4,
            pointHoverRadius: 6
        };

        const usersSet = {
            label: 'User Signups',
            data: periodData.chart.users,
            borderColor: '#10B981',
            backgroundColor: gradients ? gradients.greenGrad : 'rgba(16, 185, 129, 0.1)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#10B981',
            pointBorderColor: '#FFFFFF',
            pointBorderWidth: 2,
            pointRadius: periodData.chart.labels.length > 30 ? 2 : 4,
            pointHoverRadius: 6
        };

        const verifiedSet = {
            label: 'Verified Listings',
            data: periodData.chart.verified,
            borderColor: '#8B5CF6',
            backgroundColor: gradients ? gradients.purpleGrad : 'rgba(139, 92, 246, 0.08)',
            borderWidth: 2,
            borderDash: [4, 4],
            fill: false,
            tension: 0.35,
            pointBackgroundColor: '#8B5CF6',
            pointBorderColor: '#FFFFFF',
            pointBorderWidth: 1.5,
            pointRadius: periodData.chart.labels.length > 30 ? 2 : 3.5,
            pointHoverRadius: 5
        };

        if (metricType === 'all') {
            datasets.push(listingsSet, usersSet, verifiedSet);
        } else if (metricType === 'listings') {
            datasets.push(listingsSet);
        } else if (metricType === 'users') {
            datasets.push(usersSet);
        } else if (metricType === 'verified') {
            datasets.push(verifiedSet);
        }

        return datasets;
    }

    function updateKPIs(periodKey) {
        const summary = rawData[periodKey]?.summary;
        if (!summary) return;

        const kpiTodayListings = document.getElementById('kpiTodayListings');
        const kpiTodayUsers = document.getElementById('kpiTodayUsers');
        const kpiPeriodTotal = document.getElementById('kpiPeriodTotal');
        const kpiPeriodAvg = document.getElementById('kpiPeriodAvg');
        const kpiPeakCount = document.getElementById('kpiPeakCount');
        const kpiPeakDate = document.getElementById('kpiPeakDate');

        if (kpiTodayListings) kpiTodayListings.textContent = '+' + Number(summary.today_listings).toLocaleString();
        if (kpiTodayUsers) kpiTodayUsers.textContent = '+' + Number(summary.today_users).toLocaleString();
        if (kpiPeriodTotal) kpiPeriodTotal.textContent = Number(summary.total_listings).toLocaleString() + ' listings';
        if (kpiPeriodAvg) kpiPeriodAvg.textContent = '(' + summary.avg_daily_listings + '/day)';
        if (kpiPeakCount) kpiPeakCount.textContent = Number(summary.peak_count).toLocaleString();
        if (kpiPeakDate) kpiPeakDate.textContent = 'on ' + (summary.peak_date || 'N/A');
    }

    function initChart() {
        const initialPeriod = rawData[currentDays];
        if (!initialPeriod) return;

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: initialPeriod.chart.labels,
                datasets: buildDatasets(initialPeriod, currentMetric, null)
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: '700' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        padding: 12,
                        cornerRadius: 8,
                        boxPadding: 4,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y.toLocaleString();
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            color: '#64748B',
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: currentDays > 30 ? 12 : 10
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F1F5F9'
                        },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            color: '#64748B',
                            precision: 0
                        }
                    }
                }
            },
            plugins: [{
                id: 'customGradients',
                afterLayout: function(chart) {
                    const grads = createGradients(chart);
                    if (grads) {
                        const curData = rawData[currentDays];
                        chart.data.datasets = buildDatasets(curData, currentMetric, grads);
                    }
                }
            }]
        });
    }

    function refreshChart() {
        if (!chartInstance) return;
        const periodData = rawData[currentDays];
        if (!periodData) return;

        chartInstance.data.labels = periodData.chart.labels;
        const grads = createGradients(chartInstance);
        chartInstance.data.datasets = buildDatasets(periodData, currentMetric, grads);
        chartInstance.options.scales.x.ticks.maxTicksLimit = currentDays > 30 ? 12 : 10;
        chartInstance.update();
        updateKPIs(currentDays);
    }

    // Timeframe toggle handlers
    const timeframeButtons = document.querySelectorAll('#analyticsTimeframeToggle button');
    timeframeButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            timeframeButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentDays = this.getAttribute('data-days');
            refreshChart();
        });
    });

    // Metric filter handlers
    const metricButtons = document.querySelectorAll('#analyticsMetricToggle button');
    metricButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            metricButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentMetric = this.getAttribute('data-metric');
            refreshChart();
        });
    });

    initChart();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

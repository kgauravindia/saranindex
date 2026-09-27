<?php
$header_title = "Analytics & Growth Trends";
require_once __DIR__ . '/includes/header.php';

$stats = getAdminStats();
$dailyAnalytics = getMultiPeriodAnalyticsData();
$searchAnalytics = getMultiPeriodSearchAnalyticsData();
$search30 = $searchAnalytics['30'];
?>

<!-- Analytics Header Banner -->
<div class="card border-0 bg-primary text-white rounded-4 p-4 mb-4 shadow-sm" style="background: linear-gradient(135deg, #0F172A 0%, #1E3A8A 50%, #0369A1 100%);">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span class="badge bg-info text-dark fw-bold px-2.5 py-1 rounded-pill">
                    <i class="bi bi-graph-up-arrow me-1"></i> Intelligence & Reports
                </span>
                <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">
                    <i class="bi bi-search me-1"></i> Live Search Analytics
                </span>
                <h4 class="fw-bold mb-0">Directory Analytics & Daily Search Intelligence</h4>
            </div>
            <p class="mb-0 text-white-50 small" style="max-width: 820px;">
                Comprehensive real-time tracking of daily user searches, business onboarding velocity, registration trends, unmet search demands, and demographic engagement across Saran district (Chapra).
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="#dailySearchSection" class="btn btn-warning text-dark fw-bold btn-sm px-3 shadow-sm rounded-pill">
                <i class="bi bi-search me-1"></i> Daily Searched Report
            </a>
            <a href="index.php" class="btn btn-outline-light btn-sm px-3 fw-medium rounded-pill">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <a href="listings.php" class="btn btn-light text-dark fw-bold btn-sm px-3 shadow-sm rounded-pill">
                <i class="bi bi-list-stars me-1"></i> Listings
            </a>
        </div>
    </div>

    <!-- Quick Navigation Sub-bar -->
    <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top border-white border-opacity-10 overflow-x-auto flex-nowrap pb-1">
        <span class="text-white-50 small fw-bold text-uppercase me-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">Jump To:</span>
        <a href="#kpiOverview" class="badge bg-white bg-opacity-10 text-white text-decoration-none px-3 py-1.5 rounded-pill hover-badge">
            <i class="bi bi-speedometer me-1"></i> KPI Ribbon
        </a>
        <a href="#growthVelocitySection" class="badge bg-white bg-opacity-10 text-white text-decoration-none px-3 py-1.5 rounded-pill hover-badge">
            <i class="bi bi-activity me-1"></i> Registration Velocity
        </a>
        <a href="#dailySearchSection" class="badge bg-warning text-dark fw-bold text-decoration-none px-3 py-1.5 rounded-pill shadow-sm">
            <i class="bi bi-search me-1"></i> Daily Searched Report
        </a>
        <a href="#searchDemandSection" class="badge bg-white bg-opacity-10 text-white text-decoration-none px-3 py-1.5 rounded-pill hover-badge">
            <i class="bi bi-fire me-1"></i> Top Keywords & Missing Demand
        </a>
        <a href="#geoDistributionSection" class="badge bg-white bg-opacity-10 text-white text-decoration-none px-3 py-1.5 rounded-pill hover-badge">
            <i class="bi bi-geo-alt me-1"></i> Geographic Share
        </a>
        <a href="#activityTableSection" class="badge bg-white bg-opacity-10 text-white text-decoration-none px-3 py-1.5 rounded-pill hover-badge">
            <i class="bi bi-table me-1"></i> Historical Activity Table
        </a>
    </div>
</div>

<!-- Key Performance Metric Ribbon (6-Card Grid) -->
<div class="row g-3 mb-4" id="kpiOverview">
    <!-- Today's Searches -->
    <div class="col-12 col-sm-6 col-xl-2">
        <div class="stat-card p-3 h-100 shadow-sm border-0 rounded-3 position-relative overflow-hidden">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Today's Searches</span>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning rounded-circle p-2 fs-6">
                    <i class="bi bi-search"></i>
                </div>
            </div>
            <h3 class="fw-bold text-warning mb-1"><?php echo number_format($search30['summary']['today_searches']); ?></h3>
            <small class="text-muted d-block text-truncate">
                <?php if ($search30['summary']['search_growth_pct'] >= 0): ?>
                    <span class="text-success fw-bold"><i class="bi bi-arrow-up-right"></i> +<?php echo $search30['summary']['search_growth_pct']; ?>%</span> vs y'day
                <?php else: ?>
                    <span class="text-danger fw-bold"><i class="bi bi-arrow-down-right"></i> <?php echo $search30['summary']['search_growth_pct']; ?>%</span> vs y'day
                <?php endif; ?>
            </small>
        </div>
    </div>

    <!-- 30-Day Search Volume -->
    <div class="col-12 col-sm-6 col-xl-2">
        <div class="stat-card p-3 h-100 shadow-sm border-0 rounded-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">30-Day Searches</span>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-6">
                    <i class="bi bi-binoculars-fill"></i>
                </div>
            </div>
            <h3 class="fw-bold text-primary mb-1"><?php echo number_format($search30['summary']['total_searches']); ?></h3>
            <small class="text-muted d-block text-truncate"><i class="bi bi-speedometer me-1 text-primary"></i>Avg <?php echo $search30['summary']['avg_daily_searches']; ?> / day</small>
        </div>
    </div>

    <!-- Today's Listings -->
    <div class="col-12 col-sm-6 col-xl-2">
        <div class="stat-card p-3 h-100 shadow-sm border-0 rounded-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Today's Listings</span>
                <div class="stat-icon bg-info bg-opacity-10 text-info rounded-circle p-2 fs-6">
                    <i class="bi bi-plus-circle-fill"></i>
                </div>
            </div>
            <h3 class="fw-bold text-info mb-1">+<?php echo number_format($dailyAnalytics['30']['summary']['today_listings']); ?></h3>
            <small class="text-muted d-block text-truncate"><i class="bi bi-calendar-event me-1 text-info"></i>Last 24 hours</small>
        </div>
    </div>

    <!-- 30-Day Listings Growth -->
    <div class="col-12 col-sm-6 col-xl-2">
        <div class="stat-card p-3 h-100 shadow-sm border-0 rounded-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">30-Day Listings</span>
                <div class="stat-icon bg-success bg-opacity-10 text-success rounded-circle p-2 fs-6">
                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                </div>
            </div>
            <h3 class="fw-bold text-success mb-1"><?php echo number_format($dailyAnalytics['30']['summary']['total_listings']); ?></h3>
            <small class="text-muted d-block text-truncate"><i class="bi bi-graph-up me-1 text-success"></i>Avg <?php echo $dailyAnalytics['30']['summary']['avg_daily_listings']; ?> / day</small>
        </div>
    </div>

    <!-- Unmet Search Demand (Zero Results) -->
    <div class="col-12 col-sm-6 col-xl-2">
        <div class="stat-card p-3 h-100 shadow-sm border-0 rounded-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Unmet Demand</span>
                <div class="stat-icon bg-danger bg-opacity-10 text-danger rounded-circle p-2 fs-6">
                    <i class="bi bi-question-circle-fill"></i>
                </div>
            </div>
            <h3 class="fw-bold text-danger mb-1"><?php echo number_format($search30['summary']['total_zero_results']); ?></h3>
            <small class="text-muted d-block text-truncate"><i class="bi bi-exclamation-triangle me-1 text-danger"></i><?php echo $search30['summary']['zero_results_pct']; ?>% 0-results</small>
        </div>
    </div>

    <!-- Total Impressions -->
    <div class="col-12 col-sm-6 col-xl-2">
        <div class="stat-card p-3 h-100 shadow-sm border-0 rounded-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Total Views</span>
                <div class="stat-icon bg-purple bg-opacity-10 text-purple rounded-circle p-2 fs-6" style="background-color: rgba(139, 92, 246, 0.1); color: #8B5CF6;">
                    <i class="bi bi-eye-fill"></i>
                </div>
            </div>
            <h3 class="fw-bold text-dark mb-1"><?php echo number_format($stats['total_views'] ?? 0); ?></h3>
            <small class="text-muted d-block text-truncate"><i class="bi bi-globe me-1 text-primary"></i>Listing impressions</small>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: DEDICATED DAILY SEARCHED REPORT & SEARCH INTELLIGENCE SUITE -->
<!-- ========================================================================= -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" id="dailySearchSection">
    <div class="card-header bg-white py-3.5 px-4 border-bottom">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">
                        <i class="bi bi-search me-1"></i> Daily Searches
                    </span>
                    <h5 class="mb-0 fw-bold text-dark">Daily Searched Report & Search Velocity</h5>
                </div>
                <p class="text-muted small mb-0 mt-1">Day-by-day frequency of search terms queried by Saran residents, keyword volume, and zero-result queries.</p>
            </div>
            
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Search Metric Filter -->
                <div class="btn-group btn-group-sm" role="group" aria-label="Search Metrics" id="searchMetricToggle">
                    <button type="button" class="btn btn-outline-secondary active" data-smetric="all">
                        <i class="bi bi-layers me-1"></i>All Search Metrics
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-smetric="searches">
                        <i class="bi bi-search text-warning me-1"></i>Total Searches
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-smetric="unique">
                        <i class="bi bi-fingerprint text-primary me-1"></i>Unique Keywords
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-smetric="zero">
                        <i class="bi bi-x-circle text-danger me-1"></i>Zero Results
                    </button>
                </div>

                <!-- Timeframe Switcher -->
                <div class="btn-group btn-group-sm" role="group" aria-label="Search Timeframe" id="searchTimeframeToggle">
                    <button type="button" class="btn btn-outline-warning text-dark" data-sdays="7">7 Days</button>
                    <button type="button" class="btn btn-outline-warning text-dark" data-sdays="14">14 Days</button>
                    <button type="button" class="btn btn-outline-warning text-dark active" data-sdays="30">30 Days</button>
                    <button type="button" class="btn btn-outline-warning text-dark" data-sdays="60">60 Days</button>
                </div>

                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="exportDailySearchedReportToCSV()">
                    <i class="bi bi-download me-1"></i> Export Search CSV
                </button>
            </div>
        </div>
    </div>

    <div class="card-body p-3 p-md-4">
        <!-- Daily Searches Main Line Chart -->
        <div style="position: relative; width: 100%; height: 350px;">
            <canvas id="dailySearchesCanvas"></canvas>
        </div>

        <!-- Dynamic Chart Sub-Summary Strip -->
        <div class="row g-3 mt-3 pt-3 border-top">
            <div class="col-6 col-md-3">
                <div class="p-2.5 bg-light rounded-3 text-center border">
                    <small class="text-muted d-block fw-semibold" style="font-size: 0.78rem;">Period Total Searches</small>
                    <span class="fs-5 fw-bold text-warning" id="searchSummaryTotal"><?php echo number_format($search30['summary']['total_searches']); ?></span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-2.5 bg-light rounded-3 text-center border">
                    <small class="text-muted d-block fw-semibold" style="font-size: 0.78rem;">Avg Daily Searches</small>
                    <span class="fs-5 fw-bold text-primary" id="searchSummaryAvg"><?php echo $search30['summary']['avg_daily_searches']; ?> / day</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-2.5 bg-light rounded-3 text-center border">
                    <small class="text-muted d-block fw-semibold" style="font-size: 0.78rem;">Peak Search Day</small>
                    <span class="fs-6 fw-bold text-dark" id="searchSummaryPeak"><?php echo number_format($search30['summary']['peak_searches']); ?> on <?php echo $search30['summary']['peak_searches_date']; ?></span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-2.5 bg-light rounded-3 text-center border">
                    <small class="text-muted d-block fw-semibold" style="font-size: 0.78rem;">Most Searched Query</small>
                    <span class="fs-6 fw-bold text-success text-truncate d-block" id="searchSummaryTopQuery">"<?php echo sanitizeInput($search30['summary']['top_query']); ?>" (<?php echo number_format($search30['summary']['top_query_count']); ?>)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: TOP KEYWORDS, UNMET SEARCH DEMAND & SOURCE BREAKDOWN -->
<!-- ========================================================================= -->
<div class="row g-4 mb-4" id="searchDemandSection">
    <!-- Top 15 Most Searched Terms -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning bg-opacity-20 text-dark fw-bold p-1.5 rounded-circle">
                        <i class="bi bi-fire text-warning"></i>
                    </span>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">Top 15 Most Searched Terms</h6>
                        <small class="text-muted">High-intent searches made by users in Saran</small>
                    </div>
                </div>
                <span class="badge bg-light text-secondary border">30-Day Window</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="topSearchTermsTable">
                        <thead class="bg-light sticky-top">
                            <tr class="small text-uppercase text-muted" style="font-size: 0.75rem;">
                                <th class="ps-3">#</th>
                                <th>Search Keyword</th>
                                <th>Volume</th>
                                <th>Avg Results</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($search30['top_terms'])): ?>
                                <?php 
                                $rank = 1;
                                $maxCount = $search30['top_terms'][0]['search_count'] ?? 1;
                                foreach ($search30['top_terms'] as $term): 
                                    $pct = $maxCount > 0 ? round(($term['search_count'] / $maxCount) * 100) : 0;
                                ?>
                                    <tr>
                                        <td class="ps-3 text-muted fw-bold small">
                                            <?php if ($rank === 1): ?>
                                                <span class="badge bg-warning text-dark rounded-circle p-1">🥇</span>
                                            <?php elseif ($rank === 2): ?>
                                                <span class="badge bg-secondary text-white rounded-circle p-1">🥈</span>
                                            <?php elseif ($rank === 3): ?>
                                                <span class="badge bg-secondary-subtle text-dark rounded-circle p-1">🥉</span>
                                            <?php else: ?>
                                                <?php echo $rank; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark"><?php echo sanitizeInput($term['query']); ?></div>
                                            <div class="progress mt-1" style="height: 4px; width: 120px;">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $pct; ?>%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2.5 py-1 fw-bold">
                                                <i class="bi bi-search me-1"></i><?php echo number_format($term['search_count']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($term['avg_results'] > 0): ?>
                                                <span class="badge bg-success-subtle text-success px-2 py-1">
                                                    <i class="bi bi-check2-circle me-1"></i><?php echo $term['avg_results']; ?> listings
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2 py-1">
                                                    <i class="bi bi-x-circle me-1"></i>0 results
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <a href="../search.php?q=<?php echo urlencode($term['query']); ?>" target="_blank" class="btn btn-outline-primary btn-sm py-0.5 px-2 rounded-pill" title="Test search as visitor">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php 
                                    $rank++;
                                endforeach; 
                                ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No search queries recorded yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Zero-Result Queries (Unmet Demand / Content Opportunities) -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger bg-opacity-20 text-danger fw-bold p-1.5 rounded-circle">
                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                    </span>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">Unmet Search Demand (0 Results)</h6>
                        <small class="text-muted">Keywords users searched for that have 0 matching listings</small>
                    </div>
                </div>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold">
                    <i class="bi bi-lightbulb me-1"></i>High Opportunity
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="zeroResultSearchTable">
                        <thead class="bg-light sticky-top">
                            <tr class="small text-uppercase text-muted" style="font-size: 0.75rem;">
                                <th class="ps-3">Missing Keyword / Service</th>
                                <th>Search Hits</th>
                                <th>Location / Block</th>
                                <th class="pe-3 text-end">Onboard Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($search30['zero_result_terms'])): ?>
                                <?php foreach ($search30['zero_result_terms'] as $zTerm): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-danger">
                                                <i class="bi bi-search me-1 text-muted"></i><?php echo sanitizeInput($zTerm['query']); ?>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.72rem;">Last requested: <?php echo date('d M, h:i A', strtotime($zTerm['last_searched_at'])); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger px-2.5 py-1 fw-bold">
                                                <?php echo number_format($zTerm['search_count']); ?> requests
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-geo-alt me-1 text-primary"></i><?php echo !empty($zTerm['block_slug']) ? ucfirst($zTerm['block_slug']) : 'Saran District'; ?>
                                            </span>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <a href="edit-listing.php?prefill_title=<?php echo urlencode($zTerm['query']); ?>" class="btn btn-sm btn-primary py-1 px-2.5 rounded-pill fw-semibold" title="Add listing for this missing term">
                                                <i class="bi bi-plus-lg me-1"></i>Add Listing
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-check-circle text-success fs-4 d-block mb-1"></i>
                                        All recent searches found matching listings!
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: GRANULAR DAY-BY-DAY SEARCH REPORT TABLE -->
<!-- ========================================================================= -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3.5 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary text-white fw-bold px-2 py-0.5 rounded-pill">
                    <i class="bi bi-calendar3 me-1"></i> Day-by-Day Log
                </span>
                <h6 class="mb-0 fw-bold text-dark">Daily Searched Activity Breakdown</h6>
            </div>
            <small class="text-muted">Granular day-by-day record of search requests, unique keywords, top searched term of the day, and zero-result queries</small>
        </div>
        
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <input type="text" id="dailySearchTableFilter" class="form-control form-control-sm rounded-pill" placeholder="Filter by date, day, or keyword..." style="max-width: 240px;">
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="exportDailySearchedReportToCSV()">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Daily Search CSV
            </button>
        </div>
    </div>

    <div class="table-responsive" style="max-height: 440px; overflow-y: auto;">
        <table class="table table-hover table-custom align-middle mb-0" id="dailySearchedDataTable">
            <thead class="bg-light sticky-top">
                <tr class="small text-uppercase text-muted" style="font-size: 0.75rem;">
                    <th class="ps-4">Date</th>
                    <th>Day</th>
                    <th>Total Searches</th>
                    <th>Unique Keywords</th>
                    <th>Top Searched Term of Day</th>
                    <th>Zero-Result Queries</th>
                    <th class="pe-4 text-end">Search Velocity</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $searchTimelineReversed = array_reverse($search30['timeline']);
                foreach ($searchTimelineReversed as $dayItem): 
                    $hasSearches = ($dayItem['total_searches'] > 0);
                    $zeroPct = $dayItem['total_searches'] > 0 ? round(($dayItem['zero_results'] / $dayItem['total_searches']) * 100) : 0;
                ?>
                    <tr class="<?php echo $hasSearches ? 'table-light' : ''; ?>">
                        <td class="ps-4 fw-bold text-dark"><?php echo sanitizeInput($dayItem['date']); ?></td>
                        <td><span class="badge bg-light text-secondary border px-2 py-1"><?php echo sanitizeInput($dayItem['day_name']); ?></span></td>
                        <td>
                            <span class="badge bg-warning text-dark px-2.5 py-1.5 fs-6 fw-bold">
                                <i class="bi bi-search me-1"></i><?php echo number_format($dayItem['total_searches']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-bold">
                                <i class="bi bi-fingerprint me-1"></i><?php echo number_format($dayItem['unique_queries']); ?> unique
                            </span>
                        </td>
                        <td>
                            <?php if ($dayItem['top_term'] !== '-'): ?>
                                <span class="fw-semibold text-dark">"<?php echo sanitizeInput($dayItem['top_term']); ?>"</span>
                                <small class="text-muted">(<?php echo number_format($dayItem['top_term_count']); ?>x)</small>
                            <?php else: ?>
                                <span class="text-muted small">No searches</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($dayItem['zero_results'] > 0): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                    <i class="bi bi-exclamation-circle me-1"></i><?php echo number_format($dayItem['zero_results']); ?> (<?php echo $zeroPct; ?>%)
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success px-2 py-1"><i class="bi bi-check-lg me-1"></i>0</span>
                            <?php endif; ?>
                        </td>
                        <td class="pe-4 text-end">
                            <?php if ($dayItem['total_searches'] >= 70): ?>
                                <span class="badge bg-danger text-white"><i class="bi bi-lightning-charge-fill me-1"></i>High Surge</span>
                            <?php elseif ($dayItem['total_searches'] >= 40): ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-arrow-up-right me-1"></i>Active</span>
                            <?php elseif ($dayItem['total_searches'] > 0): ?>
                                <span class="badge bg-info-subtle text-info-emphasis"><i class="bi bi-activity me-1"></i>Steady</span>
                            <?php else: ?>
                                <span class="badge bg-light text-muted border">Quiet</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: REAL-TIME SEARCH STREAM & CHANNEL SOURCES -->
<!-- ========================================================================= -->
<div class="row g-4 mb-4">
    <!-- Live Search Query Stream Feed -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                    <h6 class="mb-0 fw-bold text-dark">Live Real-Time Search Stream</h6>
                </div>
                <small class="text-muted">Last 30 search queries on Saran Index</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 360px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light sticky-top">
                            <tr class="small text-uppercase text-muted" style="font-size: 0.75rem;">
                                <th class="ps-3">Time</th>
                                <th>Keyword Queried</th>
                                <th>Source / Channel</th>
                                <th>Location</th>
                                <th class="pe-3 text-end">Results</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($search30['recent_logs'])): ?>
                                <?php foreach ($search30['recent_logs'] as $log): ?>
                                    <tr>
                                        <td class="ps-3 text-muted small" style="white-space: nowrap;">
                                            <i class="bi bi-clock me-1"></i><?php echo date('h:i:s A', strtotime($log['created_at'])); ?>
                                            <div class="text-muted" style="font-size: 0.7rem;"><?php echo date('d M', strtotime($log['created_at'])); ?></div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark"><?php echo sanitizeInput($log['query']); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($log['source'] === 'hindi_web'): ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">सारण खोज (Hindi)</span>
                                            <?php elseif ($log['source'] === 'suggest_api'): ?>
                                                <span class="badge bg-info-subtle text-info border border-info-subtle">Autocomplete API</span>
                                            <?php elseif ($log['source'] === 'claim_search'): ?>
                                                <span class="badge bg-purple-subtle text-purple border" style="background-color: rgba(139,92,246,0.1); color: #8B5CF6;">Claim Search</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Web Search</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="small text-muted">
                                                <?php echo !empty($log['block_slug']) ? ucfirst($log['block_slug']) : 'District-wide'; ?>
                                            </span>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <?php if ($log['results_count'] > 0): ?>
                                                <span class="badge bg-success-subtle text-success"><?php echo $log['results_count']; ?> found</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger text-white">0 found</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No live queries recorded.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Source Channels & Popular Categories Intent -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Search Channels Breakdown</h6>
            </div>
            <div class="card-body p-3">
                <div style="position: relative; height: 210px;">
                    <canvas id="searchSourceChart"></canvas>
                </div>

                <div class="mt-3 pt-3 border-top">
                    <h6 class="small fw-bold text-uppercase text-muted mb-2" style="font-size: 0.75rem;">Top Search Categories Intent</h6>
                    <div class="d-flex flex-wrap gap-1.5">
                        <?php if (!empty($search30['top_categories'])): ?>
                            <?php foreach ($search30['top_categories'] as $cat): ?>
                                <span class="badge bg-light text-dark border px-2.5 py-1.5">
                                    <i class="bi bi-tag-fill text-primary me-1"></i><?php echo ucfirst(str_replace('-', ' ', $cat['category_slug'])); ?>: <strong><?php echo number_format($cat['count']); ?></strong>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="badge bg-light text-muted border px-2.5 py-1.5">General Directory Search</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: MAIN DIRECTORY REGISTRATION & ONBOARDING VELOCITY CHART -->
<!-- ========================================================================= -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" id="growthVelocitySection">
    <div class="card-header bg-white py-3.5 px-4 border-bottom">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h5 class="mb-1 fw-bold text-dark"><i class="bi bi-activity text-primary me-2"></i>Directory Registration, Views & Growth Velocity</h5>
                <p class="text-muted small mb-0">Interactive timeline of directory growth, impressions, user onboarding, and searches across daily intervals.</p>
            </div>
            
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Metric Dataset Switcher -->
                <div class="btn-group btn-group-sm" role="group" aria-label="Metric Filters" id="analyticsMetricToggle">
                    <button type="button" class="btn btn-outline-secondary active" data-metric="all">
                        <i class="bi bi-layers me-1"></i>All Metrics
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-metric="searches">
                        <i class="bi bi-search text-warning me-1"></i>Searches
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-metric="impressions">
                        <i class="bi bi-eye text-info me-1"></i>Impressions
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-metric="listings">
                        <i class="bi bi-collection text-primary me-1"></i>Listings
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-metric="users">
                        <i class="bi bi-people text-success me-1"></i>Users
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-metric="verified">
                        <i class="bi bi-patch-check text-purple me-1" style="color: #8B5CF6;"></i>Verified
                    </button>
                </div>

                <!-- Timeframe Switcher -->
                <div class="btn-group btn-group-sm" role="group" aria-label="Timeframe" id="analyticsTimeframeToggle">
                    <button type="button" class="btn btn-outline-primary" data-days="7">7 Days</button>
                    <button type="button" class="btn btn-outline-primary" data-days="14">14 Days</button>
                    <button type="button" class="btn btn-outline-primary active" data-days="30">30 Days</button>
                    <button type="button" class="btn btn-outline-primary" data-days="60">60 Days</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="card-body p-3 p-md-4">
        <div style="position: relative; width: 100%; height: 360px;">
            <canvas id="dailyAnalyticsCanvas"></canvas>
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3 pt-2 border-top text-muted small">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #F59E0B;">&nbsp;</span> Daily Searches</span>
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #0EA5E9;">&nbsp;</span> Total Impressions</span>
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #2563EB;">&nbsp;</span> New Listings</span>
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #10B981;">&nbsp;</span> User Signups</span>
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #8B5CF6;">&nbsp;</span> Verified Listings</span>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-secondary border"><i class="bi bi-clock-history me-1"></i>Live SQL Aggregations</span>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: GEOGRAPHIC (BLOCKS) & CATEGORY SHARE CHARTS -->
<!-- ========================================================================= -->
<div class="row g-4 mb-4" id="geoDistributionSection">
    <!-- Block Geographic Chart -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-geo-alt-fill me-2 text-danger"></i>Top Blocks by Listing Volume</h6>
                <a href="blocks.php" class="badge bg-light text-primary border text-decoration-none">All 20 Blocks <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            <div class="card-body p-3">
                <div style="position: relative; height: 320px;">
                    <canvas id="blockDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Distribution Chart -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Category Share Distribution</h6>
                <a href="categories.php" class="badge bg-light text-primary border text-decoration-none">Categories <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            <div class="card-body p-3">
                <div style="position: relative; height: 320px;">
                    <canvas id="categoryDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SECTION: DAY-BY-DAY OVERALL ACTIVITY LOG TABLE -->
<!-- ========================================================================= -->
<div class="card border-0 shadow-sm rounded-4 mb-4" id="activityTableSection">
    <div class="card-header bg-white py-3.5 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-table me-2 text-primary"></i>Day-by-Day Historical Overall Activity Log</h6>
            <small class="text-muted">Consolidated day-by-day record of views, searches, listings, verifications, and registrations</small>
        </div>
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="exportAnalyticsTableToCSV()">
            <i class="bi bi-download me-1"></i> Export Overall CSV
        </button>
    </div>

    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-hover table-custom align-middle mb-0" id="analyticsDataTable">
            <thead class="bg-light sticky-top">
                <tr class="small text-uppercase text-muted" style="font-size: 0.75rem;">
                    <th class="ps-4">Date</th>
                    <th>Day</th>
                    <th>Daily Searches</th>
                    <th>Total Impressions</th>
                    <th>New Listings Added</th>
                    <th>Verified Listings</th>
                    <th>User Registrations</th>
                    <th class="pe-4 text-end">Activity Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $timeline30 = array_reverse($dailyAnalytics['30']['timeline']);
                foreach ($timeline30 as $dayItem): 
                    $hasActivity = ($dayItem['listings'] > 0 || $dayItem['users'] > 0 || $dayItem['impressions'] > 0 || ($dayItem['searches'] ?? 0) > 0);
                ?>
                    <tr class="<?php echo $hasActivity ? 'table-light' : ''; ?>">
                        <td class="ps-4 fw-bold text-dark"><?php echo sanitizeInput($dayItem['date']); ?></td>
                        <td><span class="badge bg-light text-secondary border"><?php echo sanitizeInput($dayItem['day_name']); ?></span></td>
                        <td>
                            <span class="badge bg-warning text-dark px-2 py-1 fw-bold">
                                <i class="bi bi-search me-1"></i><?php echo number_format($dayItem['searches'] ?? 0); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info-emphasis border px-2.5 py-1 fw-bold">
                                <i class="bi bi-eye text-info me-1"></i><?php echo number_format($dayItem['impressions'] ?? 0); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($dayItem['listings'] > 0): ?>
                                <span class="badge bg-primary px-2.5 py-1.5 fs-6 fw-bold">+<?php echo number_format($dayItem['listings']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($dayItem['verified_listings'] > 0): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-patch-check-fill me-1"></i><?php echo number_format($dayItem['verified_listings']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($dayItem['users'] > 0): ?>
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1"><i class="bi bi-person-plus me-1"></i>+<?php echo number_format($dayItem['users']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">0</span>
                            <?php endif; ?>
                        </td>
                        <td class="pe-4 text-end">
                            <?php if ($dayItem['listings'] > 100 || ($dayItem['searches'] ?? 0) > 75): ?>
                                <span class="badge bg-danger text-white"><i class="bi bi-lightning-charge-fill me-1"></i>Peak Surge</span>
                            <?php elseif ($hasActivity): ?>
                                <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                            <?php else: ?>
                                <span class="badge bg-light text-muted border">Quiet</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- JAVASCRIPT: CHART CONTROLLERS & EXPORTERS -->
<!-- ========================================================================= -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rawData = <?php echo json_encode($dailyAnalytics, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    const searchRawData = <?php echo json_encode($searchAnalytics, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    const blockData = <?php echo json_encode(array_slice($stats['block_breakdown'] ?? [], 0, 10)); ?>;
    const categoryData = <?php echo json_encode(array_slice($stats['category_breakdown'] ?? [], 0, 8)); ?>;

    // ------------------------------------------------------------------------
    // 1. DEDICATED DAILY SEARCHES CHART
    // ------------------------------------------------------------------------
    let curSearchDays = '30';
    let curSearchMetric = 'all';
    let searchChartInstance = null;
    const searchCanvas = document.getElementById('dailySearchesCanvas');

    function buildSearchDatasets(periodData, metricType) {
        const datasets = [];

        const totalSet = {
            label: 'Total Searches',
            data: periodData.chart.searches,
            borderColor: '#F59E0B',
            backgroundColor: 'rgba(245, 158, 11, 0.15)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#F59E0B',
            pointBorderColor: '#FFFFFF',
            pointBorderWidth: 2,
            pointRadius: periodData.chart.labels.length > 30 ? 2 : 4,
            pointHoverRadius: 6
        };

        const uniqueSet = {
            label: 'Unique Keywords',
            data: periodData.chart.unique_queries,
            borderColor: '#2563EB',
            backgroundColor: 'rgba(37, 99, 235, 0.10)',
            borderWidth: 2,
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#2563EB',
            pointBorderColor: '#FFFFFF',
            pointBorderWidth: 1.5,
            pointRadius: periodData.chart.labels.length > 30 ? 2 : 3.5,
            pointHoverRadius: 5
        };

        const zeroSet = {
            label: 'Zero-Result Queries',
            data: periodData.chart.zero_results,
            borderColor: '#EF4444',
            backgroundColor: 'rgba(239, 68, 68, 0.08)',
            borderWidth: 2,
            borderDash: [4, 4],
            fill: false,
            tension: 0.35,
            pointBackgroundColor: '#EF4444',
            pointBorderColor: '#FFFFFF',
            pointBorderWidth: 1.5,
            pointRadius: periodData.chart.labels.length > 30 ? 2 : 3.5,
            pointHoverRadius: 5
        };

        if (metricType === 'all') {
            datasets.push(totalSet, uniqueSet, zeroSet);
        } else if (metricType === 'searches') {
            datasets.push(totalSet);
        } else if (metricType === 'unique') {
            datasets.push(uniqueSet);
        } else if (metricType === 'zero') {
            datasets.push(zeroSet);
        }

        return datasets;
    }

    if (searchCanvas) {
        const initSearchPeriod = searchRawData[curSearchDays];
        searchChartInstance = new Chart(searchCanvas, {
            type: 'line',
            data: {
                labels: initSearchPeriod.chart.labels,
                datasets: buildSearchDatasets(initSearchPeriod, curSearchMetric)
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 12 }, boxWidth: 14 }
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: '700' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748B' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748B', precision: 0 }
                    }
                }
            }
        });
    }

    function refreshSearchChart() {
        if (!searchChartInstance) return;
        const periodData = searchRawData[curSearchDays];
        if (!periodData) return;

        searchChartInstance.data.labels = periodData.chart.labels;
        searchChartInstance.data.datasets = buildSearchDatasets(periodData, curSearchMetric);
        searchChartInstance.update();

        // Update Dynamic Strip
        const sumTotal = document.getElementById('searchSummaryTotal');
        const sumAvg = document.getElementById('searchSummaryAvg');
        const sumPeak = document.getElementById('searchSummaryPeak');
        const sumTopQuery = document.getElementById('searchSummaryTopQuery');

        if (sumTotal) sumTotal.textContent = Number(periodData.summary.total_searches || 0).toLocaleString();
        if (sumAvg) sumAvg.textContent = (periodData.summary.avg_daily_searches || 0) + ' / day';
        if (sumPeak) sumPeak.textContent = (periodData.summary.peak_searches || 0) + ' on ' + (periodData.summary.peak_searches_date || '-');
        if (sumTopQuery) sumTopQuery.textContent = '"' + (periodData.summary.top_query || '-') + '" (' + (periodData.summary.top_query_count || 0) + ')';
    }

    const sTimeframeBtns = document.querySelectorAll('#searchTimeframeToggle button');
    sTimeframeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            sTimeframeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            curSearchDays = this.getAttribute('data-sdays');
            refreshSearchChart();
        });
    });

    const sMetricBtns = document.querySelectorAll('#searchMetricToggle button');
    sMetricBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            sMetricBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            curSearchMetric = this.getAttribute('data-smetric');
            refreshSearchChart();
        });
    });

    // ------------------------------------------------------------------------
    // 2. SEARCH SOURCES DOUGHNUT CHART
    // ------------------------------------------------------------------------
    const sourceCanvas = document.getElementById('searchSourceChart');
    if (sourceCanvas && searchRawData['30'].sources.length > 0) {
        const srcData = searchRawData['30'].sources;
        const srcLabels = {
            'web': 'English Web Search',
            'hindi_web': 'Hindi Directory (सारण खोज)',
            'suggest_api': 'Autocomplete Suggest API',
            'claim_search': 'Business Claim Search'
        };

        new Chart(sourceCanvas, {
            type: 'doughnut',
            data: {
                labels: srcData.map(s => srcLabels[s.source] || s.source),
                datasets: [{
                    data: srcData.map(s => s.count),
                    backgroundColor: ['#2563EB', '#DC2626', '#0EA5E9', '#8B5CF6', '#F59E0B'],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 11 }, boxWidth: 12 }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // ------------------------------------------------------------------------
    // 3. MAIN DIRECTORY REGISTRATION VELOCITY CHART (WITH SEARCHES DATASET)
    // ------------------------------------------------------------------------
    let currentDays = '30';
    let currentMetric = 'all';
    let chartInstance = null;
    const ctx = document.getElementById('dailyAnalyticsCanvas');

    function createGradients(chart) {
        const { ctx: chartCtx, chartArea } = chart;
        if (!chartArea) return null;

        const amberGrad = chartCtx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        amberGrad.addColorStop(0, 'rgba(245, 158, 11, 0.35)');
        amberGrad.addColorStop(1, 'rgba(245, 158, 11, 0.00)');

        const cyanGrad = chartCtx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        cyanGrad.addColorStop(0, 'rgba(14, 165, 233, 0.35)');
        cyanGrad.addColorStop(1, 'rgba(14, 165, 233, 0.00)');

        const blueGrad = chartCtx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        blueGrad.addColorStop(0, 'rgba(37, 99, 235, 0.32)');
        blueGrad.addColorStop(1, 'rgba(37, 99, 235, 0.00)');

        const greenGrad = chartCtx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        greenGrad.addColorStop(0, 'rgba(16, 185, 129, 0.28)');
        greenGrad.addColorStop(1, 'rgba(16, 185, 129, 0.00)');

        const purpleGrad = chartCtx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        purpleGrad.addColorStop(0, 'rgba(139, 92, 246, 0.25)');
        purpleGrad.addColorStop(1, 'rgba(139, 92, 246, 0.00)');

        return { amberGrad, cyanGrad, blueGrad, greenGrad, purpleGrad };
    }

    function buildMainDatasets(periodData, metricType, gradients) {
        const datasets = [];

        const searchesSet = {
            label: 'Daily Searches',
            data: periodData.chart.searches || [],
            borderColor: '#F59E0B',
            backgroundColor: gradients ? gradients.amberGrad : 'rgba(245, 158, 11, 0.12)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#F59E0B',
            pointBorderColor: '#FFFFFF',
            pointBorderWidth: 2,
            pointRadius: periodData.chart.labels.length > 30 ? 2 : 4,
            pointHoverRadius: 6
        };

        const impressionsSet = {
            label: 'Total Impressions',
            data: periodData.chart.impressions,
            borderColor: '#0EA5E9',
            backgroundColor: gradients ? gradients.cyanGrad : 'rgba(14, 165, 233, 0.1)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#0EA5E9',
            pointBorderColor: '#FFFFFF',
            pointBorderWidth: 2,
            pointRadius: periodData.chart.labels.length > 30 ? 2 : 4,
            pointHoverRadius: 6
        };

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
            datasets.push(searchesSet, impressionsSet, listingsSet, usersSet, verifiedSet);
        } else if (metricType === 'searches') {
            datasets.push(searchesSet);
        } else if (metricType === 'impressions') {
            datasets.push(impressionsSet);
        } else if (metricType === 'listings') {
            datasets.push(listingsSet);
        } else if (metricType === 'users') {
            datasets.push(usersSet);
        } else if (metricType === 'verified') {
            datasets.push(verifiedSet);
        }

        return datasets;
    }

    if (ctx) {
        const initialPeriod = rawData[currentDays];
        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: initialPeriod.chart.labels,
                datasets: buildMainDatasets(initialPeriod, currentMetric, null)
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: '700' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748B' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748B', precision: 0 }
                    }
                }
            },
            plugins: [{
                id: 'customGradients',
                afterLayout: function(chart) {
                    const grads = createGradients(chart);
                    if (grads) {
                        const curData = rawData[currentDays];
                        chart.data.datasets = buildMainDatasets(curData, currentMetric, grads);
                    }
                }
            }]
        });
    }

    function refreshMainChart() {
        if (!chartInstance) return;
        const periodData = rawData[currentDays];
        if (!periodData) return;

        chartInstance.data.labels = periodData.chart.labels;
        const grads = createGradients(chartInstance);
        chartInstance.data.datasets = buildMainDatasets(periodData, currentMetric, grads);
        chartInstance.update();
    }

    const timeframeButtons = document.querySelectorAll('#analyticsTimeframeToggle button');
    timeframeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            timeframeButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentDays = this.getAttribute('data-days');
            refreshMainChart();
        });
    });

    const metricButtons = document.querySelectorAll('#analyticsMetricToggle button');
    metricButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            metricButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentMetric = this.getAttribute('data-metric');
            refreshMainChart();
        });
    });

    // ------------------------------------------------------------------------
    // 4. BLOCK & CATEGORY BREAKDOWN CHARTS
    // ------------------------------------------------------------------------
    const blockCanvas = document.getElementById('blockDistributionChart');
    if (blockCanvas && blockData.length > 0) {
        new Chart(blockCanvas, {
            type: 'bar',
            data: {
                labels: blockData.map(b => b.block_name),
                datasets: [{
                    label: 'Listings',
                    data: blockData.map(b => b.listing_count),
                    backgroundColor: 'rgba(37, 99, 235, 0.85)',
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: '#F1F5F9' }, ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } } },
                    y: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' } } }
                }
            }
        });
    }

    const catCanvas = document.getElementById('categoryDistributionChart');
    if (catCanvas && categoryData.length > 0) {
        new Chart(catCanvas, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(c => c.category_name),
                datasets: [{
                    data: categoryData.map(c => c.listing_count),
                    backgroundColor: [
                        '#2563EB', '#3B82F6', '#60A5FA', '#93C5FD',
                        '#10B981', '#F59E0B', '#8B5CF6', '#EC4899'
                    ],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 11 }, boxWidth: 14 }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // ------------------------------------------------------------------------
    // 5. LIVE SEARCH TABLE FILTER
    // ------------------------------------------------------------------------
    const searchFilterInput = document.getElementById('dailySearchTableFilter');
    if (searchFilterInput) {
        searchFilterInput.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            const table = document.getElementById('dailySearchedDataTable');
            if (!table) return;
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(r => {
                const text = r.innerText.toLowerCase();
                r.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }
});

// ----------------------------------------------------------------------------
// EXPORTERS TO CSV
// ----------------------------------------------------------------------------
function exportDailySearchedReportToCSV() {
    const table = document.getElementById('dailySearchedDataTable');
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');
    for (let i = 0; i < rows.length; i++) {
        if (rows[i].style.display === 'none') continue;
        const row = [], cols = rows[i].querySelectorAll('td, th');
        for (let j = 0; j < cols.length; j++) {
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s+)/gm, ' ');
            data = data.replace(/"/g, '""');
            row.push('"' + data + '"');
        }
        csv.push(row.join(','));
    }
    
    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const downloadLink = document.createElement('a');
    downloadLink.download = 'saran_daily_searched_report_' + new Date().toISOString().slice(0, 10) + '.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

function exportAnalyticsTableToCSV() {
    const table = document.getElementById('analyticsDataTable');
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        for (let j = 0; j < cols.length; j++) {
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s+)/gm, ' ');
            data = data.replace(/"/g, '""');
            row.push('"' + data + '"');
        }
        csv.push(row.join(','));
    }
    
    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const downloadLink = document.createElement('a');
    downloadLink.download = 'saran_overall_daily_analytics_' + new Date().toISOString().slice(0, 10) + '.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>

<style>
.hover-badge {
    transition: all 0.2s ease;
}
.hover-badge:hover {
    background: rgba(255, 255, 255, 0.25) !important;
    transform: translateY(-1px);
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

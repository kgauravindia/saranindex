<?php
$header_title = "Analytics & Growth Trends";
require_once __DIR__ . '/includes/header.php';

$stats = getAdminStats();
$dailyAnalytics = getMultiPeriodAnalyticsData();
?>

<!-- Analytics Header Banner -->
<div class="card border-0 bg-primary text-white rounded-3 p-4 mb-4 shadow-sm" style="background: linear-gradient(135deg, #0F172A 0%, #1E40AF 100%);">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-info text-dark fw-bold px-2.5 py-1 rounded-pill">
                    <i class="bi bi-graph-up-arrow me-1"></i> Performance Metrics
                </span>
                <h4 class="fw-bold mb-0">Directory Analytics & Growth Intelligence</h4>
            </div>
            <p class="mb-0 text-white-50 small">Track daily directory registrations, user onboarding trends, geographic distribution, and engagement metrics across Saran district.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="index.php" class="btn btn-outline-light btn-sm px-3 fw-medium">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard Overview
            </a>
            <a href="listings.php" class="btn btn-warning text-dark fw-bold btn-sm px-3 shadow-sm">
                <i class="bi bi-list-stars me-1"></i> View All Listings
            </a>
        </div>
    </div>
</div>

<!-- Key Performance Metric Ribbon -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card p-3 h-100 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase">Today's Listings</span>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-5">
                    <i class="bi bi-plus-circle-fill"></i>
                </div>
            </div>
            <h2 class="fw-bold text-primary mb-1">+<?php echo number_format($dailyAnalytics['30']['summary']['today_listings']); ?></h2>
            <small class="text-muted"><i class="bi bi-calendar-event me-1 text-primary"></i>Added in last 24 hours</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card p-3 h-100 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase">30-Day Growth</span>
                <div class="stat-icon bg-success bg-opacity-10 text-success rounded-circle p-2 fs-5">
                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                </div>
            </div>
            <h2 class="fw-bold text-success mb-1"><?php echo number_format($dailyAnalytics['30']['summary']['total_listings']); ?></h2>
            <small class="text-muted"><i class="bi bi-speedometer me-1 text-success"></i>Avg <?php echo $dailyAnalytics['30']['summary']['avg_daily_listings']; ?> listings / day</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card p-3 h-100 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase">Peak Day Record</span>
                <div class="stat-icon bg-purple bg-opacity-10 text-purple rounded-circle p-2 fs-5" style="background-color: rgba(139, 92, 246, 0.1); color: #8B5CF6;">
                    <i class="bi bi-trophy-fill"></i>
                </div>
            </div>
            <h2 class="fw-bold text-dark mb-1"><?php echo number_format($dailyAnalytics['30']['summary']['peak_count']); ?></h2>
            <small class="text-muted"><i class="bi bi-star-fill me-1 text-warning"></i>Highest daily addition on <?php echo $dailyAnalytics['30']['summary']['peak_date']; ?></small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card p-3 h-100 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-bold text-uppercase">Total Impressions</span>
                <div class="stat-icon bg-info bg-opacity-10 text-info rounded-circle p-2 fs-5">
                    <i class="bi bi-eye-fill"></i>
                </div>
            </div>
            <h2 class="fw-bold text-info mb-1"><?php echo number_format($stats['total_views'] ?? 0); ?></h2>
            <small class="text-muted"><i class="bi bi-graph-up me-1 text-info"></i>Listing & profile views combined</small>
        </div>
    </div>
</div>

<!-- Main Daily Analytics Interactive Chart Section -->
<div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h5 class="mb-1 fw-bold text-dark"><i class="bi bi-activity text-primary me-2"></i>Daily Registration & Onboarding Velocity</h5>
                <p class="text-muted small mb-0">Interactive timeline of directory growth across daily intervals.</p>
            </div>
            
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Metric Dataset Switcher -->
                <div class="btn-group btn-group-sm" role="group" aria-label="Metric Filters" id="analyticsMetricToggle">
                    <button type="button" class="btn btn-outline-secondary active" data-metric="all">
                        <i class="bi bi-layers me-1"></i>All Metrics
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
            <div class="d-flex align-items-center gap-3">
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #0EA5E9;">&nbsp;</span> Total Impressions</span>
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #2563EB;">&nbsp;</span> New Listings</span>
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #10B981;">&nbsp;</span> User Signups</span>
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #8B5CF6;">&nbsp;</span> Verified Listings</span>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-secondary border"><i class="bi bi-clock-history me-1"></i>Live Dynamic SQL Query</span>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Charts: Category Breakdown & Block Velocity -->
<div class="row g-4 mb-4">
    <!-- Block Geographic Chart -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
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
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
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

<!-- Daily Activity Log Table -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-table me-2 text-primary"></i>Day-by-Day Historical Activity Breakdown</h6>
            <small class="text-muted">Granular day-by-day record of views, submissions, and verifications</small>
        </div>
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="exportAnalyticsTableToCSV()">
            <i class="bi bi-download me-1"></i> Export to CSV
        </button>
    </div>

    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-hover table-custom align-middle mb-0" id="analyticsDataTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Total Impressions</th>
                    <th>New Listings Added</th>
                    <th>Verified Listings</th>
                    <th>User Registrations</th>
                    <th>Activity Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $timeline30 = array_reverse($dailyAnalytics['30']['timeline']);
                foreach ($timeline30 as $dayItem): 
                    $hasActivity = ($dayItem['listings'] > 0 || $dayItem['users'] > 0 || $dayItem['impressions'] > 0);
                ?>
                    <tr class="<?php echo $hasActivity ? 'table-light' : ''; ?>">
                        <td class="fw-bold text-dark"><?php echo sanitizeInput($dayItem['date']); ?></td>
                        <td><span class="badge bg-light text-secondary border"><?php echo sanitizeInput($dayItem['day_name']); ?></span></td>
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
                        <td>
                            <?php if ($dayItem['listings'] > 100): ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-lightning-charge-fill me-1"></i>Peak Surge</span>
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

<!-- Chart.js Controller for Analytics Suite -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rawData = <?php echo json_encode($dailyAnalytics, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    const blockData = <?php echo json_encode(array_slice($stats['block_breakdown'] ?? [], 0, 10)); ?>;
    const categoryData = <?php echo json_encode(array_slice($stats['category_breakdown'] ?? [], 0, 8)); ?>;

    let currentDays = '30';
    let currentMetric = 'all';
    let chartInstance = null;

    const ctx = document.getElementById('dailyAnalyticsCanvas');

    function createGradients(chart) {
        const { ctx: chartCtx, chartArea } = chart;
        if (!chartArea) return null;

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

        return { cyanGrad, blueGrad, greenGrad, purpleGrad };
    }

    function buildDatasets(periodData, metricType, gradients) {
        const datasets = [];

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
            datasets.push(impressionsSet, listingsSet, usersSet, verifiedSet);
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
                datasets: buildDatasets(initialPeriod, currentMetric, null)
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
        chartInstance.update();
    }

    const timeframeButtons = document.querySelectorAll('#analyticsTimeframeToggle button');
    timeframeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            timeframeButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentDays = this.getAttribute('data-days');
            refreshChart();
        });
    });

    const metricButtons = document.querySelectorAll('#analyticsMetricToggle button');
    metricButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            metricButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentMetric = this.getAttribute('data-metric');
            refreshChart();
        });
    });

    // Block Horizontal Bar Chart
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
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { grid: { color: '#F1F5F9' }, ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } } },
                    y: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' } } }
                }
            }
        });
    }

    // Category Doughnut Chart
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
});

// CSV Export Helper
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
    
    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.download = 'saran_daily_analytics_' + new Date().toISOString().slice(0, 10) + '.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
$page_title = "Online to Offline Database Sync";
$header_title = "Database Sync (Online to Offline)";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sync_engine.php';

$defaultRemoteUrl = defined('REMOTE_LIVE_URL') ? REMOTE_LIVE_URL : 'https://saranindex.com/';
$defaultSyncKey = defined('SYNC_SECRET_KEY') ? SYNC_SECRET_KEY : '';
?>

<div class="container-fluid px-0">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Database Sync</li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-0 text-dark">
                <i class="bi bi-database-down text-primary me-2"></i>Online to Offline Database Sync
            </h3>
            <p class="text-muted small mb-0">Synchronize MySQL database tables, directory listings, census/panchayat data, and users from online production into your local offline database.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill d-flex align-items-center">
                <span class="spinner-grow spinner-grow-sm text-success me-2" role="status" style="width: 8px; height: 8px;"></span>
                DB Sync Engine Ready
            </span>
            <a href="sync.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills custom-sync-tabs mb-4 bg-white p-2 rounded-3 border shadow-sm" id="syncTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill fw-semibold" id="api-sync-tab" data-bs-toggle="pill" data-bs-target="#api-sync-pane" type="button" role="tab">
                <i class="bi bi-lightning-charge-fill me-1 text-warning"></i> Live Cloud Sync (HTTPS Bridge)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold" id="direct-db-tab" data-bs-toggle="pill" data-bs-target="#direct-db-pane" type="button" role="tab">
                <i class="bi bi-database-fill-gear me-1 text-primary"></i> Direct MySQL Sync
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold" id="file-import-tab" data-bs-toggle="pill" data-bs-target="#file-import-pane" type="button" role="tab">
                <i class="bi bi-file-earmark-arrow-up-fill me-1 text-info"></i> SQL File Import & Export
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold" id="cli-guide-tab" data-bs-toggle="pill" data-bs-target="#cli-guide-pane" type="button" role="tab">
                <i class="bi bi-terminal-fill me-1 text-dark"></i> CLI & Automation
            </button>
        </li>
    </ul>

    <div class="tab-content" id="syncTabContent">
        <!-- TAB 1: LIVE HTTPS CLOUD SYNC -->
        <div class="tab-pane fade show active" id="api-sync-pane" role="tabpanel">
            <!-- Connection & Configuration Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-subtle text-primary p-2 rounded-3 me-3">
                                <i class="bi bi-hdd-network-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Step 1: Production Server Connection</h6>
                                <small class="text-muted">Configure the target remote Saran Index domain and secret authorization token.</small>
                            </div>
                        </div>
                        <div id="connectionStatusBadge">
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill border">
                                <i class="bi bi-circle-fill me-1 fs-xs"></i> Not Connected Yet
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-semibold text-dark small mb-0">Online Server Base URL</label>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-link p-0 text-decoration-none small text-primary" id="btnSetLiveUrl" style="font-size: 0.78rem;">Live (saranindex.com)</button>
                                    <span class="text-muted" style="font-size: 0.78rem;">|</span>
                                    <button type="button" class="btn btn-link p-0 text-decoration-none small text-secondary" id="btnSetLocalUrl" style="font-size: 0.78rem;">Localhost (Demo)</button>
                                </div>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-globe2"></i></span>
                                <input type="url" class="form-control" id="remoteUrlInput" value="<?php echo htmlspecialchars($defaultRemoteUrl); ?>" placeholder="https://saranindex.com/">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark small">Sync Secret Key (SYNC_SECRET_KEY)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-key-fill"></i></span>
                                <input type="password" class="form-control" id="syncKeyInput" value="<?php echo htmlspecialchars($defaultSyncKey); ?>" placeholder="Secret Token">
                                <button class="btn btn-outline-secondary" type="button" id="toggleKeyVisibilityBtn" title="Show/Hide Key">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm" id="testConnectionBtn">
                                <i class="bi bi-plug-fill me-1"></i> Test & Load Stats
                            </button>
                        </div>
                    </div>

                    <div id="serverInfoAlert" class="alert alert-success d-none mt-3 mb-0 rounded-3 border">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                            <div>
                                <strong id="serverInfoTitle">Connected to Saran Index Production!</strong>
                                <div id="serverInfoDetails" class="small text-muted"></div>
                            </div>
                        </div>
                    </div>

                    <div id="serverErrorAlert" class="alert alert-warning d-none mt-3 mb-0 rounded-3 border">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning flex-shrink-0 mt-1"></i>
                            <div class="w-100">
                                <strong class="text-dark" id="serverErrorTitle">Connection Alert</strong>
                                <div id="serverErrorDetails" class="small text-dark mt-1"></div>
                                <div id="serverErrorTips" class="mt-2 p-2 bg-white rounded border small">
                                    <strong>Troubleshooting Guide:</strong>
                                    <div id="serverErrorTipsContent" class="mt-1 text-muted"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Comparison & Selection Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success-subtle text-success p-2 rounded-3 me-3">
                                <i class="bi bi-table fs-5"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Step 2: Table Selection & Row Count Comparison</h6>
                                <small class="text-muted">Compare online vs offline records and choose tables to synchronize.</small>
                            </div>
                        </div>
                        
                        <!-- Presets -->
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="small text-muted fw-semibold me-1">Presets:</span>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" id="presetSelectAll">
                                <i class="bi bi-check2-all me-1"></i> All
                            </button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" id="presetContentOnly">
                                <i class="bi bi-newspaper me-1"></i> Content & Listings
                            </button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" id="presetMasterGeo">
                                <i class="bi bi-geo-alt me-1"></i> Geo / Master
                            </button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" id="presetDeselectAll">
                                <i class="bi bi-dash-circle me-1"></i> Clear
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Filter and Search -->
                    <div class="row g-3 mb-3 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0" id="tableFilterInput" placeholder="Filter tables by name or category...">
                            </div>
                        </div>
                        <div class="col-md-8 text-md-end d-flex flex-wrap align-items-center justify-content-md-end gap-2">
                            <span class="badge bg-light text-dark border p-2 rounded-3">
                                Selected: <strong id="selectedTablesCount" class="text-primary">0</strong> / <span id="totalTablesCount">0</span> tables (<strong id="selectedOnlineRecords" class="text-primary">0</strong> rows)
                            </span>
                            <span class="badge bg-light text-secondary border p-2 rounded-3">
                                Total Local: <strong id="totalLocalRecords" class="text-dark">0</strong>
                            </span>
                            <span class="badge bg-light text-success border p-2 rounded-3">
                                Total Online: <strong id="totalOnlineRecords" class="text-success">0</strong>
                            </span>
                            <span class="badge bg-light border p-2 rounded-3" id="totalDiffBadge">
                                Net Diff: <strong id="totalDiffRecords" class="text-primary">0</strong>
                            </span>
                        </div>
                    </div>

                    <!-- Comparison Table Container -->
                    <div class="table-responsive rounded-3 border" style="max-height: 440px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0" id="tablesComparisonTable">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th width="40" class="text-center">
                                        <input class="form-check-input" type="checkbox" id="masterCheckbox" title="Select / Deselect All">
                                    </th>
                                    <th>Table Name</th>
                                    <th>Category</th>
                                    <th class="text-end">Local Records</th>
                                    <th class="text-end">Online Records</th>
                                    <th class="text-center">Difference</th>
                                    <th class="text-center">Sync Status</th>
                                </tr>
                            </thead>
                            <tbody id="tablesTableBody">
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                        Click <strong>"Test & Load Stats"</strong> above to load and compare online vs offline database tables.
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot id="tablesTableFoot" class="table-light sticky-bottom fw-bold border-top border-2">
                                <tr class="bg-light">
                                    <td class="text-center"><i class="bi bi-calculator text-primary"></i></td>
                                    <td><span class="text-uppercase small text-muted">TOTAL:</span> <strong id="footTotalTables">0</strong> tables</td>
                                    <td><span class="badge bg-white text-primary border" id="footSelectedSummary">0 selected</span></td>
                                    <td class="text-end fw-bold text-secondary" id="footTotalLocal">0</td>
                                    <td class="text-end fw-bold text-dark" id="footTotalOnline">0</td>
                                    <td class="text-center font-monospace fw-bold" id="footTotalDiff">0</td>
                                    <td class="text-center" id="footOverallStatus">-</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sync Options & Start Execution Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning-subtle text-warning p-2 rounded-3 me-3">
                            <i class="bi bi-sliders fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Step 3: Sync Mode & Execution</h6>
                            <small class="text-muted">Configure how records should be synchronized and initiate the sync pipeline.</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <!-- Mode Selector -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark small">Sync Strategy / Mode</label>
                            <div class="form-check p-3 border rounded-3 mb-2 bg-light">
                                <input class="form-check-input" type="radio" name="syncMode" id="modeUpsert" value="upsert" checked>
                                <label class="form-check-label fw-semibold text-dark ms-2" for="modeUpsert">
                                    Safe Upsert (Merge & Update)
                                    <div class="text-muted small fw-normal">Inserts new online records and updates modified records by Primary Key. Preserves unique local entries.</div>
                                </label>
                            </div>
                            <div class="form-check p-3 border rounded-3 bg-light">
                                <input class="form-check-input" type="radio" name="syncMode" id="modeReplace" value="replace">
                                <label class="form-check-label fw-semibold text-danger ms-2" for="modeReplace">
                                    Full Clean Replace (Truncate & Insert)
                                    <div class="text-muted small fw-normal">Wipes selected local tables first, then loads exact fresh replica from online.</div>
                                </label>
                            </div>
                        </div>

                        <!-- Batching & Options -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark small">Batch Chunk Size</label>
                            <select class="form-select mb-3" id="batchSizeSelect">
                                <option value="250">250 records / batch (Slow connection)</option>
                                <option value="500" selected>500 records / batch (Recommended)</option>
                                <option value="1000">1,000 records / batch (Fast)</option>
                            </select>

                            <div class="form-check form-switch p-3 border rounded-3 bg-light">
                                <input class="form-check-input ms-0 me-3" type="checkbox" role="switch" id="includeMediaCheckbox">
                                <label class="form-check-label fw-semibold text-dark" for="includeMediaCheckbox">
                                    Include Media Files (Optional)
                                    <div class="text-muted small fw-normal">Leave unchecked to sync <strong>only database tables</strong>.</div>
                                </label>
                            </div>
                        </div>

                        <!-- Summary & Trigger Button -->
                        <div class="col-md-4 d-flex flex-column justify-content-between">
                            <div class="p-3 bg-primary-subtle border border-primary-subtle rounded-3">
                                <h6 class="fw-bold text-primary mb-1"><i class="bi bi-info-circle-fill me-1"></i> Ready to Synchronize</h6>
                                <p class="small text-muted mb-0">The system executes chunked transfers with foreign key safety to prevent timeouts or data corruption.</p>
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button type="button" class="btn btn-success btn-lg py-3 fw-bold shadow-sm rounded-3" id="startSyncBtn">
                                    <i class="bi bi-cloud-arrow-down-fill me-2 fs-5"></i> Start Online to Offline Sync
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill d-none" id="cancelSyncBtn">
                                    <i class="bi bi-x-circle me-1"></i> Cancel Sync Operation
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Live Progress Container -->
                    <div id="syncProgressSection" class="d-none mt-4 pt-3 border-top">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <span class="fw-bold text-dark fs-6" id="progressStatusText">Starting synchronization pipeline...</span>
                                <div class="small text-muted" id="progressSubText">Preparing batches...</div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill" id="progressPercentageBadge">0%</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress mb-3" style="height: 16px; border-radius: 8px;">
                            <div id="syncProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;"></div>
                        </div>

                        <!-- Live Metrics -->
                        <div class="row g-2 mb-3 text-center">
                            <div class="col-6 col-md-3">
                                <div class="p-2 border rounded-3 bg-light">
                                    <small class="text-muted d-block">Current Table</small>
                                    <strong class="text-dark" id="metricCurrentTable">-</strong>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-2 border rounded-3 bg-light">
                                    <small class="text-muted d-block">Records Synced</small>
                                    <strong class="text-primary" id="metricRecordsSynced">0</strong>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-2 border rounded-3 bg-light">
                                    <small class="text-muted d-block">Media Downloaded</small>
                                    <strong class="text-success" id="metricMediaSynced">0</strong>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-2 border rounded-3 bg-light">
                                    <small class="text-muted d-block">Elapsed Time</small>
                                    <strong class="text-dark" id="metricElapsedTime">0s</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Live Terminal / Console -->
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="small fw-bold text-muted text-uppercase" style="letter-spacing: 0.05em;">Live Event Stream / Log</span>
                            <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 text-muted" id="clearConsoleBtn">Clear Console</button>
                        </div>
                        <div class="bg-dark text-light p-3 rounded-3 font-monospace small" id="syncConsoleLog" style="height: 220px; overflow-y: auto; font-size: 0.825rem; line-height: 1.5;">
                            <div class="text-secondary">[Ready] Click "Start Online to Offline Sync" to begin.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: DIRECT MYSQL (HOST TO HOST) -->
        <div class="tab-pane fade" id="direct-db-pane" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-database-fill-gear text-primary me-2"></i>Direct MySQL Database Synchronization</h6>
                    <small class="text-muted">Connect directly to a remote MySQL host if remote MySQL access (Port 3306) is allowed in your hosting cPanel/firewall.</small>
                </div>
                <div class="card-body p-4">
                    <form id="directDbSyncForm">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Remote DB Host / IP</label>
                                <input type="text" class="form-control" name="db_host" placeholder="e.g. 195.35.x.x or mysql.saranindex.com" value="<?php echo DB_HOST; ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Remote DB Name</label>
                                <input type="text" class="form-control" name="db_name" placeholder="e.g. u305984835_saranindex" value="<?php echo DB_NAME; ?>" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small">Remote DB User</label>
                                <input type="text" class="form-control" name="db_user" placeholder="Username" value="<?php echo DB_USER; ?>" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small">Remote DB Password</label>
                                <input type="password" class="form-control" name="db_pass" placeholder="Password" value="<?php echo DB_PASS; ?>">
                            </div>
                        </div>

                        <div class="alert alert-info rounded-3 small">
                            <i class="bi bi-info-circle-fill me-1"></i> <strong>Tip:</strong> If using Hostinger or shared cPanel hosting where port 3306 is restricted, use the <strong>Live Cloud Sync (HTTPS Bridge)</strong> tab instead.
                        </div>

                        <button type="submit" class="btn btn-primary fw-semibold px-4 py-2" id="directSyncSubmitBtn">
                            <i class="bi bi-arrow-repeat me-1"></i> Connect & Sync Direct DB
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TAB 3: SQL FILE IMPORT & EXPORT -->
        <div class="tab-pane fade" id="file-import-pane" role="tabpanel">
            <div class="row g-4">
                <!-- Import SQL Snapshot -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-arrow-up-fill text-info me-2"></i>Import Offline SQL Snapshot</h6>
                            <small class="text-muted">Upload a <code>.sql</code> database dump file to restore or synchronize locally.</small>
                        </div>
                        <div class="card-body p-4">
                            <form id="sqlUploadForm" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Select .SQL Backup File</label>
                                    <input type="file" class="form-control" name="sql_file" accept=".sql,.txt" required>
                                </div>
                                <div class="alert alert-warning rounded-3 small">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Uploading a full SQL backup may overwrite existing table structures and data.
                                </div>
                                <button type="submit" class="btn btn-info text-white fw-semibold px-4 py-2" id="sqlUploadBtn">
                                    <i class="bi bi-upload me-1"></i> Upload & Import SQL
                                </button>
                            </form>
                            <div id="sqlUploadResult" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Export Live Snapshot -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-download text-success me-2"></i>Download Online SQL Dump</h6>
                            <small class="text-muted">Generate a live database export directly from the production server.</small>
                        </div>
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <div>
                                <p class="text-muted small">You can download a clean SQL snapshot from production via the bridge API anytime without opening phpMyAdmin.</p>
                                <ul class="small text-muted mb-4">
                                    <li>Includes full table schemas and UTF-8 data.</li>
                                    <li>Foreign key checks safely disabled for quick restoration.</li>
                                    <li>Suitable for cold offline backups.</li>
                                </ul>
                            </div>
                            <button type="button" class="btn btn-success fw-semibold px-4 py-2" id="downloadOnlineDumpBtn">
                                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download Online SQL Dump (.sql)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 4: CLI & AUTOMATION -->
        <div class="tab-pane fade" id="cli-guide-pane" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-terminal-fill text-dark me-2"></i>Command Line Interface (CLI) & Cron Automation</h6>
                    <small class="text-muted">Run sync operations in headless terminal mode or schedule automated nightly syncs.</small>
                </div>
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-2">1. Running CLI Sync in Laragon / Terminal</h6>
                    <div class="bg-dark text-light p-3 rounded-3 mb-3 font-monospace small">
                        # Run full sync with default settings<br>
                        php admin/sync_cli.php<br><br>
                        # Run sync for specific tables only<br>
                        php admin/sync_cli.php --tables=listings,users,categories<br><br>
                        # Include media/uploads sync in clean replace mode<br>
                        php admin/sync_cli.php --mode=replace --media<br><br>
                        # Specify custom remote URL and Key<br>
                        php admin/sync_cli.php --url=https://saranindex.com/ --key=YOUR_SECRET_KEY
                    </div>

                    <h6 class="fw-bold text-dark mb-2">2. Scheduling Nightly Auto-Sync in Windows (Task Scheduler)</h6>
                    <p class="small text-muted mb-0">Create a Basic Task in Windows Task Scheduler pointing to your PHP binary:</p>
                    <code class="d-block p-2 bg-light border rounded-2 small text-dark mt-1">
                        "D:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" "d:\laragon\www\saranindex\admin\sync_cli.php" --tables=all --mode=upsert
                    </code>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.custom-sync-tabs .nav-link {
    color: #64748b;
    padding: 0.6rem 1.25rem;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}
.custom-sync-tabs .nav-link.active {
    background-color: var(--admin-accent, #2563eb) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
}
.fs-xs { font-size: 0.55rem; }
.badge-synced { background-color: #dcfce7; color: #15803d; }
.badge-out-of-sync { background-color: #fef3c7; color: #b45309; }
.badge-missing { background-color: #fee2e2; color: #b91c1c; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let comparisonData = null;
    let isSyncing = false;
    let cancelRequested = false;
    let syncStartTime = null;
    let timerInterval = null;

    const remoteUrlInput = document.getElementById('remoteUrlInput');
    const syncKeyInput = document.getElementById('syncKeyInput');
    const toggleKeyVisibilityBtn = document.getElementById('toggleKeyVisibilityBtn');
    const testConnectionBtn = document.getElementById('testConnectionBtn');
    const connectionStatusBadge = document.getElementById('connectionStatusBadge');
    const serverInfoAlert = document.getElementById('serverInfoAlert');
    const serverInfoTitle = document.getElementById('serverInfoTitle');
    const serverInfoDetails = document.getElementById('serverInfoDetails');

    const tablesTableBody = document.getElementById('tablesTableBody');
    const masterCheckbox = document.getElementById('masterCheckbox');
    const tableFilterInput = document.getElementById('tableFilterInput');
    const selectedTablesCount = document.getElementById('selectedTablesCount');
    const selectedOnlineRecords = document.getElementById('selectedOnlineRecords');
    const totalTablesCount = document.getElementById('totalTablesCount');
    const totalLocalRecords = document.getElementById('totalLocalRecords');
    const totalOnlineRecords = document.getElementById('totalOnlineRecords');
    const totalDiffRecords = document.getElementById('totalDiffRecords');
    const totalDiffBadge = document.getElementById('totalDiffBadge');

    const footTotalTables = document.getElementById('footTotalTables');
    const footSelectedSummary = document.getElementById('footSelectedSummary');
    const footTotalLocal = document.getElementById('footTotalLocal');
    const footTotalOnline = document.getElementById('footTotalOnline');
    const footTotalDiff = document.getElementById('footTotalDiff');
    const footOverallStatus = document.getElementById('footOverallStatus');

    const presetSelectAll = document.getElementById('presetSelectAll');
    const presetContentOnly = document.getElementById('presetContentOnly');
    const presetMasterGeo = document.getElementById('presetMasterGeo');
    const presetDeselectAll = document.getElementById('presetDeselectAll');

    const startSyncBtn = document.getElementById('startSyncBtn');
    const cancelSyncBtn = document.getElementById('cancelSyncBtn');
    const syncProgressSection = document.getElementById('syncProgressSection');
    const syncProgressBar = document.getElementById('syncProgressBar');
    const progressStatusText = document.getElementById('progressStatusText');
    const progressSubText = document.getElementById('progressSubText');
    const progressPercentageBadge = document.getElementById('progressPercentageBadge');
    const metricCurrentTable = document.getElementById('metricCurrentTable');
    const metricRecordsSynced = document.getElementById('metricRecordsSynced');
    const metricMediaSynced = document.getElementById('metricMediaSynced');
    const metricElapsedTime = document.getElementById('metricElapsedTime');
    const syncConsoleLog = document.getElementById('syncConsoleLog');
    const clearConsoleBtn = document.getElementById('clearConsoleBtn');

    const serverErrorAlert = document.getElementById('serverErrorAlert');
    const serverErrorTitle = document.getElementById('serverErrorTitle');
    const serverErrorDetails = document.getElementById('serverErrorDetails');
    const btnSetLiveUrl = document.getElementById('btnSetLiveUrl');
    const btnSetLocalUrl = document.getElementById('btnSetLocalUrl');

    if (btnSetLiveUrl) {
        btnSetLiveUrl.addEventListener('click', () => {
            remoteUrlInput.value = 'https://saranindex.com/';
            loadTableStats();
        });
    }

    if (btnSetLocalUrl) {
        btnSetLocalUrl.addEventListener('click', () => {
            remoteUrlInput.value = window.location.origin + window.location.pathname.replace(/\/admin\/.*$/, '') + '/';
            loadTableStats();
        });
    }

    // Toggle Secret Key Visibility
    toggleKeyVisibilityBtn.addEventListener('click', function() {
        const type = syncKeyInput.getAttribute('type') === 'password' ? 'text' : 'password';
        syncKeyInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });

    // Logging helper
    function logMessage(msg, type = 'info') {
        const time = new Date().toLocaleTimeString();
        let colorClass = 'text-light';
        let prefix = '[INFO]';
        if (type === 'success') { colorClass = 'text-success'; prefix = '[SUCCESS]'; }
        if (type === 'warn') { colorClass = 'text-warning'; prefix = '[WARN]'; }
        if (type === 'error') { colorClass = 'text-danger'; prefix = '[ERROR]'; }
        if (type === 'step') { colorClass = 'text-info'; prefix = '[STEP]'; }

        const line = document.createElement('div');
        line.className = colorClass;
        line.innerHTML = `<span class="text-secondary">[${time}]</span> ${prefix} ${msg}`;
        syncConsoleLog.appendChild(line);
        syncConsoleLog.scrollTop = syncConsoleLog.scrollHeight;
    }

    clearConsoleBtn.addEventListener('click', () => {
        syncConsoleLog.innerHTML = '<div class="text-secondary">[Console Cleared]</div>';
    });

    // Step 1: Test Connection & Load Stats
    testConnectionBtn.addEventListener('click', function() {
        loadTableStats();
    });

    function loadTableStats() {
        const remoteUrl = remoteUrlInput.value.trim();
        const syncKey = syncKeyInput.value.trim();

        if (!remoteUrl) {
            alert('Please enter a valid remote server URL.');
            return;
        }

        testConnectionBtn.disabled = true;
        testConnectionBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Testing Connection...';
        connectionStatusBadge.innerHTML = '<span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill border"><i class="bi bi-hourglass-split me-1"></i> Testing...</span>';
        serverInfoAlert.classList.add('d-none');
        if (serverErrorAlert) serverErrorAlert.classList.add('d-none');

        const formData = new FormData();
        formData.append('action', 'fetch_stats');
        formData.append('remote_url', remoteUrl);
        formData.append('sync_key', syncKey);

        fetch('sync_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            testConnectionBtn.disabled = false;
            testConnectionBtn.innerHTML = '<i class="bi bi-plug-fill me-1"></i> Test & Load Stats';

            if (data.success) {
                comparisonData = data;
                connectionStatusBadge.innerHTML = '<span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill border"><i class="bi bi-check-circle-fill me-1"></i> Connected & Ready</span>';
                serverInfoAlert.classList.remove('d-none');
                if (serverErrorAlert) serverErrorAlert.classList.add('d-none');
                serverInfoTitle.textContent = `Connected to ${remoteUrl}`;
                serverInfoDetails.textContent = `Total Tables: ${data.total_tables} | Total Online Records: ${data.total_remote_rows.toLocaleString()} | Local Records: ${data.total_local_rows.toLocaleString()}`;

                renderComparisonTable(data.tables);
                updateSelectedCount();
                logMessage(`Loaded stats for ${data.total_tables} tables from ${remoteUrl}`, 'success');
            } else {
                connectionStatusBadge.innerHTML = '<span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill border"><i class="bi bi-x-circle-fill me-1"></i> Connection Failed</span>';
                serverInfoAlert.classList.add('d-none');
                displayErrorGuide(data.code || 0, data.error || 'Unable to connect to remote server.');
                tablesTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> ${data.error || 'Connection failed'}</td></tr>`;
                logMessage(`Connection failed: ${data.error || 'Unknown error'}`, 'error');
            }
        })
        .catch(err => {
            testConnectionBtn.disabled = false;
            testConnectionBtn.innerHTML = '<i class="bi bi-plug-fill me-1"></i> Test & Load Stats';
            connectionStatusBadge.innerHTML = '<span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill border"><i class="bi bi-x-circle-fill me-1"></i> Network Error</span>';
            serverInfoAlert.classList.add('d-none');
            displayErrorGuide(0, `Network error: ${err.message}`);
            tablesTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Network error: ${err.message}</td></tr>`;
            logMessage(`Network error during test: ${err.message}`, 'error');
        });
    }

    function displayErrorGuide(code, msg) {
        if (!serverErrorAlert) return;
        serverErrorAlert.classList.remove('d-none');
        if (serverErrorDetails) serverErrorDetails.textContent = msg;

        const tipsBox = document.getElementById('serverErrorTipsContent');
        if (!tipsBox) return;

        if (code === 429 || (msg && msg.includes('429'))) {
            serverErrorTitle.textContent = 'Hostinger / Cloudflare Rate Limit (HTTP 429 Too Many Requests)';
            tipsBox.innerHTML = `
                <ul class="mb-0 ps-3">
                    <li class="mb-1"><strong>What happened:</strong> Your live hosting server (<strong>Hostinger / Cloudflare</strong>) is temporarily rate-limiting automated HTTPS requests from your IP.</li>
                    <li class="mb-1"><strong>Instant Fix (Tab 3):</strong> Switch to the <strong><a href="#" onclick="document.getElementById('file-import-tab').click(); return false;">SQL File Import & Export</a></strong> tab above to import your live database SQL backup file directly.</li>
                    <li class="mb-1"><strong>Direct Connection (Tab 2):</strong> Switch to the <strong><a href="#" onclick="document.getElementById('direct-db-tab').click(); return false;">Direct MySQL Sync</a></strong> tab if remote MySQL (Port 3306) is allowed in your Hostinger panel.</li>
                    <li><strong>Hosting Reset:</strong> Wait 5–15 minutes for the Hostinger rate limit window to expire, or whitelist your IP in <em>Hostinger hPanel &rarr; Security</em> or <em>Cloudflare Dashboard</em>.</li>
                </ul>
            `;
        } else if (code === 404 || (msg && msg.includes('404'))) {
            serverErrorTitle.textContent = 'Online Bridge Endpoint Pending Upload (HTTP 404)';
            tipsBox.innerHTML = `
                <ol class="mb-0 ps-3">
                    <li>Upload <code>api/sync_bridge.php</code> from your local project to your live server at <code>public_html/api/sync_bridge.php</code>.</li>
                    <li>Or push changes to your GitHub / Git repository: <code>git add api/sync_bridge.php && git commit -m "Add sync bridge" && git push</code></li>
                    <li>Ensure <code>SYNC_SECRET_KEY</code> matches in both <code>config/config.php</code> files.</li>
                </ol>
            `;
        } else if (code === 401 || (msg && msg.includes('401'))) {
            serverErrorTitle.textContent = 'Authentication Failed (HTTP 401 Unauthorized)';
            tipsBox.innerHTML = `
                <ul class="mb-0 ps-3">
                    <li>The Secret Key does not match the <code>SYNC_SECRET_KEY</code> defined in your live server's <code>config/config.php</code>.</li>
                    <li>Please copy the secret key from your production server into the input field above.</li>
                </ul>
            `;
        } else if (code === 403 || (msg && msg.includes('403'))) {
            serverErrorTitle.textContent = 'Access Forbidden (HTTP 403)';
            tipsBox.innerHTML = `
                <ul class="mb-0 ps-3">
                    <li>Server firewall, ModSecurity, or Cloudflare blocked the request.</li>
                    <li>Ensure file permissions on server for <code>api/sync_bridge.php</code> are <code>0644</code>.</li>
                    <li>Add a Cloudflare WAF rule to allow access to <code>/api/sync_bridge.php</code>.</li>
                </ul>
            `;
        } else {
            serverErrorTitle.textContent = 'Remote Server Connection Failed';
            tipsBox.innerHTML = `
                <ul class="mb-0 ps-3">
                    <li>Ensure the target domain <code>${remoteUrlInput.value}</code> is online and accessible.</li>
                    <li>Check your internet connection or use the <strong>SQL File Import</strong> tab.</li>
                </ul>
            `;
        }
    }

    // Render Table Rows in Step 2
    function renderComparisonTable(tables) {
        tablesTableBody.innerHTML = '';
        let totalLocal = 0;
        let totalOnline = 0;
        let count = 0;
        let hasOutOfSync = false;

        for (const [tblName, info] of Object.entries(tables)) {
            count++;
            totalLocal += (info.local_rows || 0);
            totalOnline += (info.remote_rows || 0);

            let statusBadge = '<span class="badge badge-synced px-2 py-1 rounded-pill"><i class="bi bi-check2 me-1"></i> In Sync</span>';
            if (info.status === 'out_of_sync') {
                hasOutOfSync = true;
                const diffStr = info.difference > 0 ? `+${info.difference}` : `${info.difference}`;
                statusBadge = `<span class="badge badge-out-of-sync px-2 py-1 rounded-pill"><i class="bi bi-exclamation-circle me-1"></i> Out of Sync (${diffStr})</span>`;
            } else if (info.status === 'missing_local') {
                hasOutOfSync = true;
                statusBadge = '<span class="badge badge-missing px-2 py-1 rounded-pill"><i class="bi bi-x-circle me-1"></i> Missing Locally</span>';
            }

            const tr = document.createElement('tr');
            tr.dataset.tableName = tblName;
            tr.dataset.category = info.category;

            const isDefaultChecked = info.remote_rows > 0;

            tr.innerHTML = `
                <td class="text-center">
                    <input class="form-check-input table-select-chk" type="checkbox" value="${tblName}" data-rows="${info.remote_rows || 0}" ${isDefaultChecked ? 'checked' : ''}>
                </td>
                <td>
                    <strong class="text-dark font-monospace">${tblName}</strong>
                </td>
                <td>
                    <span class="badge bg-light text-muted border">${info.category}</span>
                </td>
                <td class="text-end fw-semibold text-secondary">
                    ${(info.local_rows || 0).toLocaleString()}
                </td>
                <td class="text-end fw-bold text-dark">
                    ${(info.remote_rows || 0).toLocaleString()}
                </td>
                <td class="text-center font-monospace small ${info.difference > 0 ? 'text-success fw-bold' : (info.difference < 0 ? 'text-danger' : 'text-muted')}">
                    ${info.difference > 0 ? '+' + info.difference.toLocaleString() : info.difference.toLocaleString()}
                </td>
                <td class="text-center">
                    ${statusBadge}
                </td>
            `;

            tablesTableBody.appendChild(tr);
        }

        const totalDiff = totalOnline - totalLocal;

        // Top Summary Badges
        totalTablesCount.textContent = count;
        totalLocalRecords.textContent = totalLocal.toLocaleString();
        totalOnlineRecords.textContent = totalOnline.toLocaleString();
        totalDiffRecords.textContent = (totalDiff > 0 ? `+${totalDiff.toLocaleString()}` : totalDiff.toLocaleString());

        if (totalDiff > 0) {
            totalDiffBadge.className = 'badge bg-warning-subtle text-warning border p-2 rounded-3';
        } else if (totalDiff < 0) {
            totalDiffBadge.className = 'badge bg-danger-subtle text-danger border p-2 rounded-3';
        } else {
            totalDiffBadge.className = 'badge bg-success-subtle text-success border p-2 rounded-3';
        }

        // Table Footer Row
        if (footTotalTables) footTotalTables.textContent = count;
        if (footTotalLocal) footTotalLocal.textContent = totalLocal.toLocaleString();
        if (footTotalOnline) footTotalOnline.textContent = totalOnline.toLocaleString();
        if (footTotalDiff) {
            footTotalDiff.textContent = (totalDiff > 0 ? `+${totalDiff.toLocaleString()}` : totalDiff.toLocaleString());
            footTotalDiff.className = `text-center font-monospace fw-bold ${totalDiff > 0 ? 'text-success' : (totalDiff < 0 ? 'text-danger' : 'text-muted')}`;
        }
        if (footOverallStatus) {
            if (hasOutOfSync) {
                footOverallStatus.innerHTML = '<span class="badge badge-out-of-sync px-2 py-1 rounded-pill"><i class="bi bi-exclamation-triangle me-1"></i> Differences Found</span>';
            } else {
                footOverallStatus.innerHTML = '<span class="badge badge-synced px-2 py-1 rounded-pill"><i class="bi bi-check-all me-1"></i> Fully Synced</span>';
            }
        }

        // Attach checkbox change listeners
        document.querySelectorAll('.table-select-chk').forEach(chk => {
            chk.addEventListener('change', updateSelectedCount);
        });
    }

    function updateSelectedCount() {
        const checked = Array.from(document.querySelectorAll('.table-select-chk:checked'));
        const all = document.querySelectorAll('.table-select-chk');
        
        let selectedRows = 0;
        checked.forEach(chk => {
            selectedRows += parseInt(chk.dataset.rows || 0);
        });

        selectedTablesCount.textContent = checked.length;
        if (selectedOnlineRecords) selectedOnlineRecords.textContent = selectedRows.toLocaleString();
        if (footSelectedSummary) footSelectedSummary.textContent = `${checked.length} selected (${selectedRows.toLocaleString()} rows)`;
        masterCheckbox.checked = (checked.length === all.length && all.length > 0);
    }

    // Master Checkbox
    masterCheckbox.addEventListener('change', function() {
        document.querySelectorAll('.table-select-chk').forEach(chk => {
            chk.checked = masterCheckbox.checked;
        });
        updateSelectedCount();
    });

    // Presets
    presetSelectAll.addEventListener('click', () => {
        document.querySelectorAll('.table-select-chk').forEach(chk => chk.checked = true);
        updateSelectedCount();
    });

    presetDeselectAll.addEventListener('click', () => {
        document.querySelectorAll('.table-select-chk').forEach(chk => chk.checked = false);
        updateSelectedCount();
    });

    presetContentOnly.addEventListener('click', () => {
        const contentTables = ['listings', 'categories', 'subcategories', 'reviews', 'claims', 'sources', 'users', 'payments'];
        document.querySelectorAll('.table-select-chk').forEach(chk => {
            chk.checked = contentTables.includes(chk.value);
        });
        updateSelectedCount();
    });

    presetMasterGeo.addEventListener('click', () => {
        const geoTables = ['blocks', 'panchayats', 'villages', 'halka', 'census', 'lgd_village', 'op_sdb'];
        document.querySelectorAll('.table-select-chk').forEach(chk => {
            chk.checked = geoTables.includes(chk.value);
        });
        updateSelectedCount();
    });

    // Filter tables input
    tableFilterInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        document.querySelectorAll('#tablesTableBody tr').forEach(tr => {
            const tblName = (tr.dataset.tableName || '').toLowerCase();
            const category = (tr.dataset.category || '').toLowerCase();
            if (tblName.includes(query) || category.includes(query)) {
                tr.style.display = '';
            } else {
                tr.style.display = 'none';
            }
        });
    });

    // Step 3: Start Execution Pipeline
    startSyncBtn.addEventListener('click', async function() {
        const selectedCheckboxes = Array.from(document.querySelectorAll('.table-select-chk:checked'));
        const tablesToSync = selectedCheckboxes.map(chk => chk.value);
        const includeMedia = document.getElementById('includeMediaCheckbox').checked;
        const syncMode = document.querySelector('input[name="syncMode"]:checked').value;
        const batchSize = parseInt(document.getElementById('batchSizeSelect').value) || 500;
        const remoteUrl = remoteUrlInput.value.trim();
        const syncKey = syncKeyInput.value.trim();

        if (tablesToSync.length === 0 && !includeMedia) {
            alert('Please select at least one table or enable Media Sync to start.');
            return;
        }

        if (syncMode === 'replace' && !confirm('WARNING: "Clean Replace" will truncate selected local tables before importing fresh records from online. Do you want to proceed?')) {
            return;
        }

        // Initialize UI for Sync Execution
        isSyncing = true;
        cancelRequested = false;
        startSyncBtn.disabled = true;
        cancelSyncBtn.classList.remove('d-none');
        syncProgressSection.classList.remove('d-none');
        syncProgressBar.style.width = '0%';
        progressPercentageBadge.textContent = '0%';
        metricRecordsSynced.textContent = '0';
        metricMediaSynced.textContent = '0';
        metricCurrentTable.textContent = 'Initializing';

        syncStartTime = Date.now();
        if (timerInterval) clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            const elapsedSec = Math.floor((Date.now() - syncStartTime) / 1000);
            metricElapsedTime.textContent = elapsedSec + 's';
        }, 1000);

        logMessage(`Starting sync pipeline in [${syncMode.toUpperCase()}] mode...`, 'step');
        logMessage(`Selected ${tablesToSync.length} tables. Chunk size: ${batchSize}. Media sync: ${includeMedia ? 'YES' : 'NO'}.`, 'info');

        let totalRecordsSynced = 0;
        let totalMediaSynced = 0;
        const totalSteps = tablesToSync.length + (includeMedia ? 1 : 0);
        let completedSteps = 0;

        // Process Tables sequentially
        for (let i = 0; i < tablesToSync.length; i++) {
            if (cancelRequested) {
                logMessage('Sync operation cancelled by user.', 'warn');
                break;
            }

            const tbl = tablesToSync[i];
            metricCurrentTable.textContent = tbl;
            progressStatusText.textContent = `Syncing Table (${i + 1}/${tablesToSync.length}): ${tbl}`;
            logMessage(`--- Syncing table: ${tbl} ---`, 'step');

            let offset = 0;
            let isTableCompleted = false;

            while (!isTableCompleted && !cancelRequested) {
                progressSubText.textContent = `Fetching records at offset ${offset.toLocaleString()}...`;

                const formData = new FormData();
                formData.append('action', 'sync_table_batch');
                formData.append('remote_url', remoteUrl);
                formData.append('sync_key', syncKey);
                formData.append('table', tbl);
                formData.append('offset', offset);
                formData.append('limit', batchSize);
                formData.append('mode', syncMode);

                try {
                    const response = await fetch('sync_ajax.php', { method: 'POST', body: formData });
                    const result = await response.json();

                    if (!result.success) {
                        logMessage(`Error in table ${tbl}: ${result.error}`, 'error');
                        isTableCompleted = true; // Move to next table on failure
                    } else {
                        totalRecordsSynced += result.imported;
                        metricRecordsSynced.textContent = totalRecordsSynced.toLocaleString();
                        logMessage(`[${tbl}] Synced ${result.imported} records (Offset: ${offset}, Total: ${result.total_rows})`, 'info');

                        if (result.completed || result.imported === 0) {
                            isTableCompleted = true;
                            logMessage(`[${tbl}] Finished synchronizing table.`, 'success');
                        } else {
                            offset = result.next_offset;
                        }
                    }
                } catch (e) {
                    logMessage(`HTTP/Network Error on ${tbl}: ${e.message}`, 'error');
                    isTableCompleted = true;
                }
            }

            completedSteps++;
            const pct = Math.round((completedSteps / totalSteps) * 100);
            syncProgressBar.style.width = pct + '%';
            progressPercentageBadge.textContent = pct + '%';
        }

        // Process Media Sync if enabled
        if (includeMedia && !cancelRequested) {
            metricCurrentTable.textContent = 'Media & Uploads';
            progressStatusText.textContent = 'Checking and downloading uploaded media...';
            logMessage('--- Scanning remote media files in uploads/ ---', 'step');

            const mediaForm = new FormData();
            mediaForm.append('action', 'get_media_list');
            mediaForm.append('remote_url', remoteUrl);
            mediaForm.append('sync_key', syncKey);

            try {
                const mediaRes = await fetch('sync_ajax.php', { method: 'POST', body: mediaForm });
                const mediaData = await mediaRes.json();

                if (mediaData.success) {
                    const filesToDownload = mediaData.files || [];
                    logMessage(`Found ${mediaData.total_remote} remote files. Missing/Updated locally: ${filesToDownload.length}`, 'info');

                    for (let m = 0; m < filesToDownload.length; m++) {
                        if (cancelRequested) break;
                        const f = filesToDownload[m];
                        progressSubText.textContent = `Downloading file (${m + 1}/${filesToDownload.length}): ${f.path}`;

                        const dlForm = new FormData();
                        dlForm.append('action', 'download_media_file');
                        dlForm.append('remote_url', remoteUrl);
                        dlForm.append('sync_key', syncKey);
                        dlForm.append('file', f.path);

                        const dlRes = await fetch('sync_ajax.php', { method: 'POST', body: dlForm });
                        const dlData = await dlRes.json();

                        if (dlData.success) {
                            totalMediaSynced++;
                            metricMediaSynced.textContent = totalMediaSynced.toLocaleString();
                            logMessage(`Downloaded: ${f.path} (${(f.size / 1024).toFixed(1)} KB)`, 'info');
                        } else {
                            logMessage(`Failed to download ${f.path}: ${dlData.error}`, 'warn');
                        }
                    }
                    logMessage('Media synchronization complete.', 'success');
                } else {
                    logMessage(`Media scan failed: ${mediaData.error}`, 'error');
                }
            } catch (err) {
                logMessage(`Media sync error: ${err.message}`, 'error');
            }

            completedSteps++;
            syncProgressBar.style.width = '100%';
            progressPercentageBadge.textContent = '100%';
        }

        // Finish Sync
        clearInterval(timerInterval);
        isSyncing = false;
        startSyncBtn.disabled = false;
        cancelSyncBtn.classList.add('d-none');
        progressStatusText.textContent = 'Synchronization Completed Successfully!';
        progressSubText.textContent = `Completed in ${metricElapsedTime.textContent}. Total records synced: ${totalRecordsSynced.toLocaleString()}. Media files: ${totalMediaSynced}.`;
        logMessage(`=== SYNC COMPLETE === Total Records: ${totalRecordsSynced}, Media Files: ${totalMediaSynced}`, 'success');

        // Reload stats in table view
        loadTableStats();
    });

    cancelSyncBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to stop the ongoing sync operation?')) {
            cancelRequested = true;
            logMessage('Cancellation requested. Halting next batch...', 'warn');
        }
    });

    // Download Online Dump Button in Tab 3
    document.getElementById('downloadOnlineDumpBtn').addEventListener('click', function() {
        const remoteUrl = remoteUrlInput.value.trim() || 'https://saranindex.com/';
        const syncKey = syncKeyInput.value.trim();
        const exportUrl = `${remoteUrl.replace(/\/+$/, '')}/api/sync_bridge.php?action=export_sql&sync_key=${encodeURIComponent(syncKey)}`;
        window.open(exportUrl, '_blank');
    });

    // SQL File Upload in Tab 3
    document.getElementById('sqlUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const fileInput = this.querySelector('input[type="file"]');
        if (!fileInput.files.length) {
            alert('Please select a .sql file.');
            return;
        }

        const btn = document.getElementById('sqlUploadBtn');
        const resDiv = document.getElementById('sqlUploadResult');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Importing SQL...';

        const formData = new FormData(this);
        formData.append('action', 'import_sql_file');

        fetch('sync_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-upload me-1"></i> Upload & Import SQL';
            if (data.success) {
                resDiv.innerHTML = `<div class="alert alert-success small mb-0"><i class="bi bi-check-circle-fill me-1"></i> ${data.message}</div>`;
            } else {
                resDiv.innerHTML = `<div class="alert alert-danger small mb-0"><i class="bi bi-x-circle-fill me-1"></i> ${data.error}</div>`;
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-upload me-1"></i> Upload & Import SQL';
            resDiv.innerHTML = `<div class="alert alert-danger small mb-0"><i class="bi bi-x-circle-fill me-1"></i> Error: ${err.message}</div>`;
        });
    });

    // Direct DB Sync in Tab 2
    document.getElementById('directDbSyncForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('directSyncSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Connecting & Syncing...';

        const formData = new FormData(this);
        formData.append('action', 'direct_db_sync');

        fetch('sync_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Connect & Sync Direct DB';
            if (data.success) {
                alert(`Direct Sync Successful! Tables synced: ${data.tables_synced}, Records synced: ${data.records_synced}`);
            } else {
                alert(`Direct Sync Failed: ${data.error}`);
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Connect & Sync Direct DB';
            alert(`Error: ${err.message}`);
        });
    });

    // Auto-load stats on initial page open
    setTimeout(() => {
        loadTableStats();
    }, 400);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

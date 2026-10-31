<?php
$header_title = "Manage Bus Permits & Routes";
require_once __DIR__ . '/includes/header.php';

$msg = '';
$msg_type = 'success';
$db = getDB();

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $target_id = intval($_GET['id']);
    if ($target_id > 0 && $db) {
        try {
            $stmt = $db->prepare("SELECT registration_number, application_no FROM vehicle WHERE id = ?");
            $stmt->execute([$target_id]);
            $busItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($busItem) {
                // vehicle_timetable rows will cascade delete automatically per foreign key
                $del = $db->prepare("DELETE FROM vehicle WHERE id = ?");
                $del->execute([$target_id]);
                $msg = "Bus permit for registration <strong>" . sanitizeInput($busItem['registration_number']) . "</strong> (App #" . intval($busItem['application_no']) . ") deleted successfully!";
                $msg_type = 'danger';
            } else {
                $msg = "Bus record not found.";
                $msg_type = 'warning';
            }
        } catch (Exception $e) {
            $msg = "Database error deleting bus: " . $e->getMessage();
            $msg_type = 'danger';
        }
    }
}

// Handle Status Change
if (isset($_GET['action']) && $_GET['action'] === 'set_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $target_id = intval($_GET['id']);
    $new_status = sanitizeInput($_GET['status']);
    $allowed_statuses = ['PROPOSED', 'PENDING_OBJECTION', 'APPROVED', 'REJECTED'];

    if ($target_id > 0 && in_array($new_status, $allowed_statuses) && $db) {
        try {
            $up = $db->prepare("UPDATE vehicle SET status = ? WHERE id = ?");
            $up->execute([$new_status, $target_id]);
            $msg = "Permit #{$target_id} status updated to <strong>{$new_status}</strong>!";
            $msg_type = 'success';
        } catch (Exception $e) {
            $msg = "Error updating status: " . $e->getMessage();
            $msg_type = 'danger';
        }
    }
}

// Search and Filter variables
$search_query = trim($_GET['search'] ?? '');
$status_filter = trim($_GET['status'] ?? '');
$origin_filter = trim($_GET['origin'] ?? '');
$dest_filter = trim($_GET['destination'] ?? '');

$page = max(1, intval($_GET['page'] ?? 1));
$per_page_options = [15, 25, 50, 100];
$per_page = intval($_GET['per_page'] ?? 20);
if (!in_array($per_page, $per_page_options)) {
    $per_page = 20;
}

// Build WHERE conditions
$where = ["1=1"];
$params = [];

if (!empty($search_query)) {
    $where[] = "(v.registration_number LIKE ? 
                OR v.applicant_name LIKE ? 
                OR v.father_husband_name LIKE ? 
                OR v.route_no LIKE ? 
                OR v.route_name LIKE ? 
                OR v.origin LIKE ? 
                OR v.destination LIKE ? 
                OR v.via LIKE ? 
                OR v.application_no = ?)";
    $like = "%{$search_query}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = is_numeric($search_query) ? intval($search_query) : -1;
}

if (!empty($status_filter)) {
    $where[] = "v.status = ?";
    $params[] = $status_filter;
}

if (!empty($origin_filter)) {
    $where[] = "v.origin = ?";
    $params[] = $origin_filter;
}

if (!empty($dest_filter)) {
    $where[] = "v.destination = ?";
    $params[] = $dest_filter;
}

$where_sql = implode(' AND ', $where);

// Overall Statistics
$total_permits = 0;
$approved_permits = 0;
$proposed_permits = 0;
$total_stops = 0;
$origins_list = [];
$dest_list = [];
$vehicles = [];
$filtered_count = 0;

if ($db) {
    try {
        $total_permits = (int)$db->query("SELECT COUNT(*) FROM vehicle")->fetchColumn();
        $approved_permits = (int)$db->query("SELECT COUNT(*) FROM vehicle WHERE status = 'APPROVED'")->fetchColumn();
        $proposed_permits = (int)$db->query("SELECT COUNT(*) FROM vehicle WHERE status IN ('PROPOSED', 'PENDING_OBJECTION')")->fetchColumn();
        $total_stops = (int)$db->query("SELECT COUNT(*) FROM vehicle_timetable")->fetchColumn();

        // Distinct origins and destinations for dropdown filters
        $origins_list = $db->query("SELECT DISTINCT origin FROM vehicle WHERE origin IS NOT NULL AND origin != '' ORDER BY origin ASC")->fetchAll(PDO::FETCH_COLUMN);
        $dest_list = $db->query("SELECT DISTINCT destination FROM vehicle WHERE destination IS NOT NULL AND destination != '' ORDER BY destination ASC")->fetchAll(PDO::FETCH_COLUMN);

        // Count filtered rows
        $countStmt = $db->prepare("SELECT COUNT(*) FROM vehicle v WHERE {$where_sql}");
        $countStmt->execute($params);
        $filtered_count = (int)$countStmt->fetchColumn();

        // Calculate pagination
        $total_pages = max(1, ceil($filtered_count / $per_page));
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $offset = ($page - 1) * $per_page;

        // Fetch vehicle rows with timetable stop counts
        $listSql = "
            SELECT v.*, COUNT(tt.id) AS stop_count
            FROM vehicle v
            LEFT JOIN vehicle_timetable tt ON v.id = tt.vehicle_id
            WHERE {$where_sql}
            GROUP BY v.id
            ORDER BY v.application_no ASC, v.id ASC
            LIMIT {$per_page} OFFSET {$offset}
        ";
        $listStmt = $db->prepare($listSql);
        $listStmt->execute($params);
        $vehicles = $listStmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        $msg = "Query error: " . $e->getMessage();
        $msg_type = 'danger';
    }
}

// Pagination URL builder
if (!function_exists('getBusPageUrl')) {
    function getBusPageUrl($p, $search = '', $status = '', $orig = '', $dest = '', $limit = 20) {
        $params = [];
        if (!empty($search)) $params['search'] = $search;
        if (!empty($status)) $params['status'] = $status;
        if (!empty($orig)) $params['origin'] = $orig;
        if (!empty($dest)) $params['destination'] = $dest;
        if ($limit != 20) $params['per_page'] = $limit;
        if ($p > 1) $params['page'] = $p;
        return 'buses.php' . (!empty($params) ? '?' . http_build_query($params) : '');
    }
}

$from_record = $filtered_count > 0 ? ($offset + 1) : 0;
$to_record = min($offset + $per_page, $filtered_count);
?>

<div class="container-fluid py-4">
    <!-- Top Action Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="bi bi-bus-front-fill text-warning me-2"></i>Bus Permits & Route Management
            </h4>
            <p class="text-muted small mb-0">Regional Transport Authority (RTA) Saran Division Bus Permits, Vehicle Details, Stoppages & Timetables.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="../bus.php" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                <i class="bi bi-box-arrow-up-right me-1"></i> Live Bus Directory
            </a>
            <a href="bus_edit.php" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                <i class="bi bi-plus-circle me-1"></i> Add New Bus Permit
            </a>
        </div>
    </div>

    <!-- Alert / Toast Messages -->
    <?php if (!empty($msg)): ?>
        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-<?php echo ($msg_type === 'success') ? 'check-circle-fill' : (($msg_type === 'danger') ? 'exclamation-octagon-fill' : 'info-circle-fill'); ?> fs-5 me-2"></i>
                <div><?php echo $msg; ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Stat Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Permits -->
        <div class="col-6 col-lg-3">
            <div class="stat-card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Total Bus Permits</span>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-circle p-2 fs-5">
                        <i class="bi bi-bus-front"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-1"><?php echo number_format($total_permits); ?></h3>
                <small class="text-muted"><i class="bi bi-shield-check me-1 text-primary"></i>RTA Saran Division</small>
            </div>
        </div>

        <!-- Card 2: Approved -->
        <div class="col-6 col-lg-3">
            <div class="stat-card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Approved Permits</span>
                    <div class="stat-icon bg-success bg-opacity-10 text-success rounded-circle p-2 fs-5">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-success mb-1"><?php echo number_format($approved_permits); ?></h3>
                <small class="text-success fw-semibold"><i class="bi bi-check2-all me-1"></i>Active Permits</small>
            </div>
        </div>

        <!-- Card 3: Proposed / In Review -->
        <div class="col-6 col-lg-3">
            <div class="stat-card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Proposed / Review</span>
                    <div class="stat-icon bg-warning bg-opacity-20 text-warning rounded-circle p-2 fs-5">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-warning mb-1"><?php echo number_format($proposed_permits); ?></h3>
                <small class="text-muted"><i class="bi bi-clock-history me-1 text-warning"></i>Notice / Objections</small>
            </div>
        </div>

        <!-- Card 4: Total Stoppages -->
        <div class="col-6 col-lg-3">
            <div class="stat-card p-3 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Timetable Stops</span>
                    <div class="stat-icon bg-info bg-opacity-10 text-info rounded-circle p-2 fs-5">
                        <i class="bi bi-signpost-split-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-1"><?php echo number_format($total_stops); ?></h3>
                <small class="text-muted"><i class="bi bi-geo-alt me-1 text-info"></i>Scheduled Stoppages</small>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="buses.php" class="row g-2 align-items-center">
                <!-- Search Box -->
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search Reg No, Name, Route, City..." value="<?php echo sanitizeInput($search_query); ?>">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select form-select-sm bg-light">
                        <option value="">All Statuses</option>
                        <option value="PROPOSED" <?php echo ($status_filter === 'PROPOSED') ? 'selected' : ''; ?>>Proposed</option>
                        <option value="PENDING_OBJECTION" <?php echo ($status_filter === 'PENDING_OBJECTION') ? 'selected' : ''; ?>>Pending Objection</option>
                        <option value="APPROVED" <?php echo ($status_filter === 'APPROVED') ? 'selected' : ''; ?>>Approved</option>
                        <option value="REJECTED" <?php echo ($status_filter === 'REJECTED') ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                </div>

                <!-- Origin Filter -->
                <div class="col-6 col-md-2">
                    <select name="origin" class="form-select form-select-sm bg-light">
                        <option value="">All Origins</option>
                        <?php foreach ($origins_list as $orig): ?>
                            <option value="<?php echo sanitizeInput($orig); ?>" <?php echo ($origin_filter === $orig) ? 'selected' : ''; ?>>
                                <?php echo sanitizeInput($orig); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Destination Filter -->
                <div class="col-6 col-md-2">
                    <select name="destination" class="form-select form-select-sm bg-light">
                        <option value="">All Destinations</option>
                        <?php foreach ($dest_list as $dst): ?>
                            <option value="<?php echo sanitizeInput($dst); ?>" <?php echo ($dest_filter === $dst) ? 'selected' : ''; ?>>
                                <?php echo sanitizeInput($dst); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Submit & Clear Buttons -->
                <div class="col-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100 fw-semibold">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <?php if (!empty($search_query) || !empty($status_filter) || !empty($origin_filter) || !empty($dest_filter)): ?>
                        <a href="buses.php" class="btn btn-outline-secondary btn-sm rounded-pill" title="Reset Filters">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Bus Permits Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0 fw-bold text-dark">Permit Listings</h6>
                <span class="badge bg-primary-subtle text-primary fw-bold rounded-pill">
                    <?php echo number_format($filtered_count); ?> <?php echo ($filtered_count === 1) ? 'Permit' : 'Permits'; ?>
                </span>
            </div>
            <div class="small text-muted">
                Showing <?php echo $from_record; ?> to <?php echo $to_record; ?> of <?php echo number_format($filtered_count); ?> entries
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 70px;">App #</th>
                        <th>Vehicle & Reg No</th>
                        <th>Permit Holder / Applicant</th>
                        <th>Route & Endpoints</th>
                        <th>Trips & Distance</th>
                        <th>Schedule</th>
                        <th>Status</th>
                        <th class="text-end" style="min-width: 130px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($vehicles)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-bus-front fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <div class="fw-semibold">No bus permits found matching criteria.</div>
                                <small>Try adjusting filters or search keywords, or add a new bus permit.</small>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($vehicles as $v): ?>
                            <tr>
                                <!-- App # -->
                                <td>
                                    <span class="badge bg-light text-dark border fw-bold fs-6">
                                        #<?php echo intval($v['application_no']); ?>
                                    </span>
                                </td>

                                <!-- Vehicle & Reg No -->
                                <td>
                                    <div class="fw-bold text-dark font-monospace fs-6">
                                        <a href="bus_edit.php?id=<?php echo $v['id']; ?>" class="text-decoration-none text-dark hover-primary">
                                            <?php echo sanitizeInput($v['registration_number']); ?>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center gap-1.5 mt-1 small text-muted">
                                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">
                                            <?php echo sanitizeInput($v['vehicle_type'] ?: 'Bus'); ?>
                                        </span>
                                        <?php if (!empty($v['seating_capacity'])): ?>
                                            <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">
                                                <i class="bi bi-person-wheelchair me-0.5"></i><?php echo sanitizeInput($v['seating_capacity']); ?> seats
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($v['model_year'])): ?>
                                            <span class="text-muted" style="font-size: 0.75rem;">(Yr: <?php echo sanitizeInput($v['model_year']); ?>)</span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Applicant / Owner -->
                                <td>
                                    <div class="fw-semibold text-dark">
                                        <?php echo sanitizeInput($v['applicant_name'] ?: 'N/A'); ?>
                                    </div>
                                    <?php if (!empty($v['father_husband_name'])): ?>
                                        <div class="small text-muted">
                                            <span class="text-secondary opacity-75">S/o:</span> <?php echo sanitizeInput($v['father_husband_name']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($v['address'])): ?>
                                        <div class="text-muted text-truncate" style="max-width: 220px; font-size: 0.75rem;" title="<?php echo sanitizeInput($v['address']); ?>">
                                            <i class="bi bi-geo-alt me-0.5"></i><?php echo sanitizeInput($v['address']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <!-- Route & Endpoints -->
                                <td>
                                    <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                        <?php if (!empty($v['route_no'])): ?>
                                            <span class="badge bg-primary text-white fw-bold" style="font-size: 0.75rem;">
                                                Route <?php echo sanitizeInput($v['route_no']); ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="fw-bold text-dark small">
                                            <?php echo sanitizeInput($v['origin'] ?: '-'); ?>
                                            <i class="bi bi-arrow-right text-primary mx-1"></i>
                                            <?php echo sanitizeInput($v['destination'] ?: '-'); ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($v['via'])): ?>
                                        <div class="small text-muted text-truncate mt-1" style="max-width: 260px; font-size: 0.75rem;" title="<?php echo sanitizeInput($v['via']); ?>">
                                            <span class="text-secondary fw-semibold">Via:</span> <?php echo sanitizeInput($v['via']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <!-- Trips & Distance -->
                                <td>
                                    <div class="small fw-semibold text-dark">
                                        <?php echo sanitizeInput($v['trips_per_day'] ?: '-'); ?>
                                    </div>
                                    <div class="small text-muted mt-0.5">
                                        <?php if (!empty($v['total_distance_km']) && $v['total_distance_km'] > 0): ?>
                                            <i class="bi bi-speedometer2 me-1"></i><?php echo number_format($v['total_distance_km'], 1); ?> km
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Schedule / Stops -->
                                <td>
                                    <a href="bus_edit.php?id=<?php echo $v['id']; ?>#timetable-card" class="text-decoration-none">
                                        <?php if ($v['stop_count'] > 0): ?>
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle fw-semibold px-2 py-1">
                                                <i class="bi bi-signpost-split me-1"></i><?php echo intval($v['stop_count']); ?> Stops
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border px-2 py-1">
                                                <i class="bi bi-dash-circle me-1"></i>No Stops
                                            </span>
                                        <?php endif; ?>
                                    </a>
                                </td>

                                <!-- Status Badge & Quick Change -->
                                <td>
                                    <div class="dropdown">
                                        <?php 
                                        $badge_class = 'bg-secondary';
                                        if ($v['status'] === 'APPROVED') $badge_class = 'bg-success';
                                        elseif ($v['status'] === 'PROPOSED') $badge_class = 'bg-primary';
                                        elseif ($v['status'] === 'PENDING_OBJECTION') $badge_class = 'bg-warning text-dark';
                                        elseif ($v['status'] === 'REJECTED') $badge_class = 'bg-danger';
                                        ?>
                                        <button class="btn btn-sm badge <?php echo $badge_class; ?> dropdown-toggle border-0 px-2 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.75rem;">
                                            <?php echo sanitizeInput($v['status']); ?>
                                        </button>
                                        <ul class="dropdown-menu shadow-sm border-0 dropdown-menu-end small">
                                            <li><h6 class="dropdown-header text-uppercase" style="font-size: 0.65rem;">Change Permit Status</h6></li>
                                            <li><a class="dropdown-item d-flex align-items-center" href="buses.php?action=set_status&id=<?php echo $v['id']; ?>&status=APPROVED"><i class="bi bi-check-circle-fill text-success me-2"></i>Approved</a></li>
                                            <li><a class="dropdown-item d-flex align-items-center" href="buses.php?action=set_status&id=<?php echo $v['id']; ?>&status=PROPOSED"><i class="bi bi-hourglass-top text-primary me-2"></i>Proposed</a></li>
                                            <li><a class="dropdown-item d-flex align-items-center" href="buses.php?action=set_status&id=<?php echo $v['id']; ?>&status=PENDING_OBJECTION"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Pending Objection</a></li>
                                            <li><a class="dropdown-item d-flex align-items-center text-danger" href="buses.php?action=set_status&id=<?php echo $v['id']; ?>&status=REJECTED"><i class="bi bi-x-circle-fill text-danger me-2"></i>Rejected</a></li>
                                        </ul>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <!-- Edit -->
                                        <a href="bus_edit.php?id=<?php echo $v['id']; ?>" class="btn btn-outline-primary" title="Edit Permit & Timetable">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Public Live View -->
                                        <a href="../bus.php?reg=<?php echo urlencode($v['registration_number']); ?>" target="_blank" class="btn btn-outline-secondary" title="View Public Page">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>

                                        <!-- Delete -->
                                        <a href="buses.php?action=delete&id=<?php echo $v['id']; ?>" class="btn btn-outline-danger" title="Delete Bus Permit" onclick="return confirm('Are you sure you want to delete bus permit <?php echo addslashes($v['registration_number']); ?> (App #<?php echo intval($v['application_no']); ?>)? All timetable records will also be deleted.')">
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

        <!-- Pagination Footer -->
        <?php if ($total_pages > 1): ?>
            <div class="card-footer bg-white border-top py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="small text-muted">
                    Page <strong><?php echo $page; ?></strong> of <strong><?php echo $total_pages; ?></strong>
                </div>

                <nav aria-label="Permits pagination">
                    <ul class="pagination pagination-sm mb-0">
                        <!-- Previous Page -->
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo getBusPageUrl($page - 1, $search_query, $status_filter, $origin_filter, $dest_filter, $per_page); ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>

                        <!-- Page Numbers -->
                        <?php
                        $start_p = max(1, $page - 2);
                        $end_p = min($total_pages, $page + 2);
                        for ($p = $start_p; $p <= $end_p; $p++):
                        ?>
                            <li class="page-item <?php echo ($p === $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo getBusPageUrl($p, $search_query, $status_filter, $origin_filter, $dest_filter, $per_page); ?>">
                                    <?php echo $p; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next Page -->
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo getBusPageUrl($page + 1, $search_query, $status_filter, $origin_filter, $dest_filter, $per_page); ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

<?php
require_once __DIR__ . '/includes/auth.php';
checkAdminAuth();

$editing_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$error = '';
$success = '';
$db = getDB();

if (isset($_GET['saved']) && $_GET['saved'] === '1') {
    $success = "Bus permit saved successfully!";
}

// Default Vehicle Structure
$vehicle = [
    'application_no' => 1,
    'applicant_name' => '',
    'father_husband_name' => '',
    'address' => '',
    'registration_number' => '',
    'vehicle_type' => 'बस',
    'seating_capacity' => '41',
    'model_year' => '',
    'route_no' => '',
    'route_name' => '',
    'route_description' => '',
    'origin' => '',
    'destination' => '',
    'via' => '',
    'trips_per_day' => 'प्रतिदिन दो खेप आना जाना',
    'total_distance_km' => '',
    'speed_range' => '20 से 60 कि० मी० प्रति घंटा',
    'is_nationalized' => '40 कि.मी. के अन्तर्गत',
    'authority' => 'कार्यालय क्षेत्रीय परिवहन प्राधिकार, सारण, छपरा',
    'notice_date' => '2026-09-09',
    'pdf_page' => '',
    'status' => 'PROPOSED'
];

$timetables = [];

// If editing, load existing vehicle and timetables
if ($editing_id && $db) {
    try {
        $stmt = $db->prepare("SELECT * FROM vehicle WHERE id = ?");
        $stmt->execute([$editing_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $vehicle = array_merge($vehicle, $existing);

            // Load timetables
            $ttStmt = $db->prepare("SELECT * FROM vehicle_timetable WHERE vehicle_id = ? ORDER BY trip_number ASC, stop_order ASC, id ASC");
            $ttStmt->execute([$editing_id]);
            $timetables = $ttStmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $error = "Bus permit with ID #{$editing_id} not found.";
        }
    } catch (Exception $e) {
        $error = "Error loading bus permit: " . $e->getMessage();
    }
} elseif ($db) {
    // If creating a new permit, calculate next application_no
    try {
        $maxApp = (int)$db->query("SELECT COALESCE(MAX(application_no), 0) FROM vehicle")->fetchColumn();
        $vehicle['application_no'] = $maxApp + 1;
    } catch (Exception $e) {
        // fallback
    }
}

// Process POST Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $db) {
    $application_no = intval($_POST['application_no'] ?? 0);
    $applicant_name = sanitizeInput($_POST['applicant_name'] ?? '');
    $father_husband_name = sanitizeInput($_POST['father_husband_name'] ?? '');
    $address = sanitizeInput($_POST['address'] ?? '');
    $registration_number = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $_POST['registration_number'] ?? '')));
    $vehicle_type = sanitizeInput($_POST['vehicle_type'] ?? 'बस');
    $seating_capacity = sanitizeInput($_POST['seating_capacity'] ?? '');
    $model_year = sanitizeInput($_POST['model_year'] ?? '');
    $route_no = sanitizeInput($_POST['route_no'] ?? '');
    $route_name = sanitizeInput($_POST['route_name'] ?? '');
    $route_description = trim($_POST['route_description'] ?? '');
    $origin = sanitizeInput($_POST['origin'] ?? '');
    $destination = sanitizeInput($_POST['destination'] ?? '');
    $via = sanitizeInput($_POST['via'] ?? '');
    $trips_per_day = sanitizeInput($_POST['trips_per_day'] ?? '');
    $total_distance_km = !empty($_POST['total_distance_km']) ? floatval($_POST['total_distance_km']) : null;
    $speed_range = sanitizeInput($_POST['speed_range'] ?? '20 से 60 कि० मी० प्रति घंटा');
    $is_nationalized = sanitizeInput($_POST['is_nationalized'] ?? '40 कि.मी. के अन्तर्गत');
    $authority = sanitizeInput($_POST['authority'] ?? 'कार्यालय क्षेत्रीय परिवहन प्राधिकार, सारण, छपरा');
    $notice_date = !empty($_POST['notice_date']) ? sanitizeInput($_POST['notice_date']) : '2026-09-09';
    $pdf_page = !empty($_POST['pdf_page']) ? intval($_POST['pdf_page']) : null;
    $status = sanitizeInput($_POST['status'] ?? 'PROPOSED');

    // Validation
    if (empty($registration_number)) {
        $error = "Bus Registration Number is required (e.g. BR06PE0950).";
    } elseif (empty($applicant_name)) {
        $error = "Applicant / Permit Holder Name is required.";
    } elseif ($application_no <= 0) {
        $error = "Valid Application Serial Number is required.";
    } else {
        try {
            // Check for duplicate registration number on another permit
            $checkStmt = $db->prepare("SELECT id FROM vehicle WHERE registration_number = ? AND id != ?");
            $checkStmt->execute([$registration_number, $editing_id ?: 0]);
            $dup = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($dup) {
                $error = "Another bus permit (#{$dup['id']}) is already registered with registration number <strong>{$registration_number}</strong>.";
            } else {
                $db->beginTransaction();

                if ($editing_id) {
                    // UPDATE
                    $upSql = "
                        UPDATE vehicle SET 
                            application_no = ?,
                            applicant_name = ?,
                            father_husband_name = ?,
                            address = ?,
                            registration_number = ?,
                            vehicle_type = ?,
                            seating_capacity = ?,
                            model_year = ?,
                            route_no = ?,
                            route_name = ?,
                            route_description = ?,
                            origin = ?,
                            destination = ?,
                            via = ?,
                            trips_per_day = ?,
                            total_distance_km = ?,
                            speed_range = ?,
                            is_nationalized = ?,
                            authority = ?,
                            notice_date = ?,
                            pdf_page = ?,
                            status = ?
                        WHERE id = ?
                    ";
                    $upStmt = $db->prepare($upSql);
                    $upStmt->execute([
                        $application_no, $applicant_name, $father_husband_name, $address,
                        $registration_number, $vehicle_type, $seating_capacity, $model_year,
                        $route_no, $route_name, $route_description, $origin, $destination,
                        $via, $trips_per_day, $total_distance_km, $speed_range, $is_nationalized,
                        $authority, $notice_date, $pdf_page, $status, $editing_id
                    ]);
                    $target_vehicle_id = $editing_id;
                } else {
                    // INSERT
                    $insSql = "
                        INSERT INTO vehicle (
                            application_no, applicant_name, father_husband_name, address,
                            registration_number, vehicle_type, seating_capacity, model_year,
                            route_no, route_name, route_description, origin, destination,
                            via, trips_per_day, total_distance_km, speed_range, is_nationalized,
                            authority, notice_date, pdf_page, status
                        ) VALUES (
                            ?, ?, ?, ?,
                            ?, ?, ?, ?,
                            ?, ?, ?, ?, ?,
                            ?, ?, ?, ?, ?,
                            ?, ?, ?, ?
                        )
                    ";
                    $insStmt = $db->prepare($insSql);
                    $insStmt->execute([
                        $application_no, $applicant_name, $father_husband_name, $address,
                        $registration_number, $vehicle_type, $seating_capacity, $model_year,
                        $route_no, $route_name, $route_description, $origin, $destination,
                        $via, $trips_per_day, $total_distance_km, $speed_range, $is_nationalized,
                        $authority, $notice_date, $pdf_page, $status
                    ]);
                    $target_vehicle_id = (int)$db->lastInsertId();
                }

                // Process Timetable Stoppages
                $submittedStops = $_POST['stops'] ?? [];
                
                // Delete existing timetable stops for this vehicle
                $delStops = $db->prepare("DELETE FROM vehicle_timetable WHERE vehicle_id = ?");
                $delStops->execute([$target_vehicle_id]);

                if (!empty($submittedStops) && is_array($submittedStops)) {
                    $insertStopStmt = $db->prepare("
                        INSERT INTO vehicle_timetable (
                            vehicle_id, trip_number, trip_direction, stop_order,
                            stop_name, arrival_time, departure_time,
                            arrival_time_formatted, departure_time_formatted,
                            halt_duration_minutes, distance_km, travel_time,
                            travel_time_minutes, speed_kmph, remarks
                        ) VALUES (
                            ?, ?, ?, ?,
                            ?, ?, ?,
                            ?, ?,
                            ?, ?, ?,
                            ?, ?, ?
                        )
                    ");

                    $seqOrder = 1;
                    foreach ($submittedStops as $s) {
                        $stop_name = trim(sanitizeInput($s['stop_name'] ?? ''));
                        if (empty($stop_name)) {
                            continue; // skip blank row
                        }

                        $trip_number = !empty($s['trip_number']) ? max(1, intval($s['trip_number'])) : 1;
                        $trip_direction = in_array(strtoupper(trim($s['trip_direction'] ?? '')), ['UP', 'DOWN', 'RETURN']) ? strtoupper(trim($s['trip_direction'])) : 'UP';
                        $stop_order = !empty($s['stop_order']) ? intval($s['stop_order']) : $seqOrder;

                        // Times
                        $rawArr = trim($s['arrival_time'] ?? '');
                        $rawDep = trim($s['departure_time'] ?? '');

                        $arrTime = null;
                        $arrFmt = null;
                        if (!empty($rawArr)) {
                            $cleanArr = substr($rawArr, 0, 8);
                            if (strlen($cleanArr) === 5) {
                                $arrTime = $cleanArr . ':00';
                                $arrFmt = $cleanArr;
                            } else {
                                $arrTime = $cleanArr;
                                $arrFmt = substr($cleanArr, 0, 5);
                            }
                        }

                        $depTime = null;
                        $depFmt = null;
                        if (!empty($rawDep)) {
                            $cleanDep = substr($rawDep, 0, 8);
                            if (strlen($cleanDep) === 5) {
                                $depTime = $cleanDep . ':00';
                                $depFmt = $cleanDep;
                            } else {
                                $depTime = $cleanDep;
                                $depFmt = substr($cleanDep, 0, 5);
                            }
                        }

                        // Halt Duration
                        $haltMin = isset($s['halt_duration_minutes']) && $s['halt_duration_minutes'] !== '' ? intval($s['halt_duration_minutes']) : 0;
                        if ($haltMin === 0 && $arrTime && $depTime) {
                            $diffSec = strtotime($depTime) - strtotime($arrTime);
                            if ($diffSec > 0) {
                                $haltMin = (int)round($diffSec / 60);
                            }
                        }

                        $distKm = !empty($s['distance_km']) ? floatval($s['distance_km']) : 0.00;
                        $travelTime = sanitizeInput($s['travel_time'] ?? '');
                        $travelMin = !empty($s['travel_time_minutes']) ? intval($s['travel_time_minutes']) : 0;
                        $speedKmph = sanitizeInput($s['speed_kmph'] ?? '');
                        $remarks = sanitizeInput($s['remarks'] ?? '');

                        $insertStopStmt->execute([
                            $target_vehicle_id, $trip_number, $trip_direction, $stop_order,
                            $stop_name, $arrTime, $depTime,
                            $arrFmt, $depFmt,
                            $haltMin, $distKm, $travelTime,
                            $travelMin, $speedKmph, $remarks
                        ]);

                        $seqOrder++;
                    }
                }

                $db->commit();

                if (!$editing_id) {
                    header("Location: bus_edit.php?id={$target_vehicle_id}&saved=1");
                    exit;
                } else {
                    $success = "Bus permit #{$target_vehicle_id} ({$registration_number}) and timetable schedule updated successfully!";
                    // Refresh data
                    $stmt = $db->prepare("SELECT * FROM vehicle WHERE id = ?");
                    $stmt->execute([$editing_id]);
                    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

                    $ttStmt = $db->prepare("SELECT * FROM vehicle_timetable WHERE vehicle_id = ? ORDER BY trip_number ASC, stop_order ASC, id ASC");
                    $ttStmt->execute([$editing_id]);
                    $timetables = $ttStmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $error = "Database Error: " . $e->getMessage();
        }
    }
}

// Known Places in Saran Division for datalist autocomplete
$suggestedPlaces = [
    'छपरा', 'सिवान', 'गोपालगंज', 'मोतिहारी', 'बेतिया', 'मशरक', 'मढ़ौरा', 'तरैया', 'बसंतपुर', 
    'डेरनी', 'बाबा बाजार', 'मलमलिया', 'जगदीशपुर', 'राजापट्टी', 'माँझी', 'परसा', 'महमदपुर', 
    'बनियापुर', 'अमनौर', 'खैरा', 'गड़खा', 'एकमा', 'दाउदपुर', 'रसुलपुर', 'मकेर', 'भेल्दी', 
    'मीरगंज', 'हथुआ', 'बरौली', 'बरहिमा', 'दिघवा दुबौली', 'तरवारा', 'भगवानपुर', 'बड़हरियाँ', 
    'नौतन', 'खजूरिया', 'पटना', 'हाजीपुर', 'सोनपुर', 'दिघवारा', 'रिविलगंज', 'जलालपुर', 'इसुआपुर', 'पनापुर'
];

$header_title = $editing_id ? "Edit Bus Permit #{$editing_id}" : "Add New Bus Permit";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Top Action Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="buses.php" class="text-secondary text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Back to Bus Permits
                </a>
                <span class="text-muted small">/</span>
                <span class="badge bg-primary-subtle text-primary fw-bold">
                    <?php echo $editing_id ? "App #" . intval($vehicle['application_no']) : "New Permit"; ?>
                </span>
            </div>
            <h4 class="fw-bold mb-0 text-dark">
                <?php echo $editing_id ? '<i class="bi bi-pencil-square text-primary me-2"></i>Edit Bus Permit: ' . sanitizeInput($vehicle['registration_number']) : '<i class="bi bi-plus-circle text-primary me-2"></i>Add New Bus Permit'; ?>
            </h4>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($editing_id && !empty($vehicle['registration_number'])): ?>
                <a href="../bus.php?reg=<?php echo urlencode($vehicle['registration_number']); ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                    <i class="bi bi-box-arrow-up-right me-1"></i> View Live Page
                </a>
            <?php endif; ?>
            <a href="buses.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div><?php echo $error; ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div><?php echo $success; ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Main Permit Form -->
    <form method="POST" action="bus_edit.php<?php echo $editing_id ? '?id=' . $editing_id : ''; ?>" id="busPermitForm">

        <div class="row g-4 mb-4">
            <!-- Left Column: Vehicle & Permit Identity -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-bus-front text-primary me-2"></i>Vehicle & Permit Identification
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Registration Number -->
                            <div class="col-12 col-sm-6">
                                <label for="registration_number" class="form-label small fw-bold text-dark">
                                    Registration Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-uppercase font-monospace fw-bold" id="registration_number" name="registration_number" value="<?php echo sanitizeInput($vehicle['registration_number']); ?>" placeholder="e.g. BR06PE0950" required>
                                <div class="form-text small">Standard state vehicle number.</div>
                            </div>

                            <!-- Application Serial No -->
                            <div class="col-12 col-sm-6">
                                <label for="application_no" class="form-label small fw-bold text-dark">
                                    Application Serial No <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control fw-bold" id="application_no" name="application_no" value="<?php echo intval($vehicle['application_no']); ?>" min="1" required>
                                <div class="form-text small">RTA Notice serial position (e.g. 1 to 42).</div>
                            </div>

                            <!-- Vehicle Type -->
                            <div class="col-12 col-sm-6">
                                <label for="vehicle_type" class="form-label small fw-semibold text-dark">Vehicle Type / Make</label>
                                <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" value="<?php echo sanitizeInput($vehicle['vehicle_type']); ?>" placeholder="बस / सवाड़ी गाड़ी">
                            </div>

                            <!-- Seating Capacity -->
                            <div class="col-12 col-sm-6">
                                <label for="seating_capacity" class="form-label small fw-semibold text-dark">Seating Capacity</label>
                                <input type="text" class="form-control" id="seating_capacity" name="seating_capacity" value="<?php echo sanitizeInput($vehicle['seating_capacity']); ?>" placeholder="e.g. 41, 32+1, 28+1">
                            </div>

                            <!-- Model Year -->
                            <div class="col-12 col-sm-6">
                                <label for="model_year" class="form-label small fw-semibold text-dark">Model Year</label>
                                <input type="text" class="form-control" id="model_year" name="model_year" value="<?php echo sanitizeInput($vehicle['model_year']); ?>" placeholder="e.g. 2024, 2026">
                            </div>

                            <!-- Status -->
                            <div class="col-12 col-sm-6">
                                <label for="status" class="form-label small fw-bold text-dark">Permit Status</label>
                                <select class="form-select fw-semibold" id="status" name="status">
                                    <option value="PROPOSED" <?php echo ($vehicle['status'] === 'PROPOSED') ? 'selected' : ''; ?>>Proposed (प्रस्तावित)</option>
                                    <option value="PENDING_OBJECTION" <?php echo ($vehicle['status'] === 'PENDING_OBJECTION') ? 'selected' : ''; ?>>Pending Objection (आपत्ति हेतु)</option>
                                    <option value="APPROVED" <?php echo ($vehicle['status'] === 'APPROVED') ? 'selected' : ''; ?>>Approved (स्वीकृत)</option>
                                    <option value="REJECTED" <?php echo ($vehicle['status'] === 'REJECTED') ? 'selected' : ''; ?>>Rejected (अस्वीकृत)</option>
                                </select>
                            </div>

                            <!-- Notice Date -->
                            <div class="col-12 col-sm-6">
                                <label for="notice_date" class="form-label small fw-semibold text-dark">RTA Notice Date</label>
                                <input type="date" class="form-control" id="notice_date" name="notice_date" value="<?php echo sanitizeInput($vehicle['notice_date']); ?>">
                            </div>

                            <!-- PDF Source Page -->
                            <div class="col-12 col-sm-6">
                                <label for="pdf_page" class="form-label small fw-semibold text-dark">Gazette / Notice PDF Page</label>
                                <input type="number" class="form-control" id="pdf_page" name="pdf_page" value="<?php echo intval($vehicle['pdf_page']); ?>" placeholder="Page # in official notification">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Applicant / Owner Information -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-person-badge text-success me-2"></i>Applicant & Authority Information
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Applicant Name -->
                            <div class="col-12">
                                <label for="applicant_name" class="form-label small fw-bold text-dark">
                                    Applicant / Owner Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control fw-bold" id="applicant_name" name="applicant_name" value="<?php echo sanitizeInput($vehicle['applicant_name']); ?>" placeholder="e.g. आलोक कुमार पाण्डेय" required>
                            </div>

                            <!-- Father / Husband Name -->
                            <div class="col-12">
                                <label for="father_husband_name" class="form-label small fw-semibold text-dark">Father / Husband Name</label>
                                <input type="text" class="form-control" id="father_husband_name" name="father_husband_name" value="<?php echo sanitizeInput($vehicle['father_husband_name']); ?>" placeholder="e.g. श्री रामएकबाल पाण्डेय">
                            </div>

                            <!-- Full Address -->
                            <div class="col-12">
                                <label for="address" class="form-label small fw-semibold text-dark">Full Residential / Office Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="ग्राम+पोस्ट, थाना, जिला..."><?php echo sanitizeInput($vehicle['address']); ?></textarea>
                            </div>

                            <!-- Issuing Authority -->
                            <div class="col-12">
                                <label for="authority" class="form-label small fw-semibold text-dark">Issuing Transport Authority</label>
                                <input type="text" class="form-control" id="authority" name="authority" value="<?php echo sanitizeInput($vehicle['authority']); ?>" placeholder="कार्यालय क्षेत्रीय परिवहन प्राधिकार, सारण, छपरा">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Route & Operating Specifications Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-map-fill text-warning me-2"></i>Route & Operational Specifications
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <!-- Route No -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="route_no" class="form-label small fw-bold text-dark">Route Number</label>
                        <input type="text" class="form-control fw-bold text-primary" id="route_no" name="route_no" value="<?php echo sanitizeInput($vehicle['route_no']); ?>" placeholder="e.g. 2199, 4077 DN">
                    </div>

                    <!-- Origin -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="origin" class="form-label small fw-bold text-dark">Origin Place</label>
                        <input type="text" list="placesDatalist" class="form-control" id="origin" name="origin" value="<?php echo sanitizeInput($vehicle['origin']); ?>" placeholder="e.g. सिवान / छपरा">
                    </div>

                    <!-- Destination -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="destination" class="form-label small fw-bold text-dark">Destination Place</label>
                        <input type="text" list="placesDatalist" class="form-control" id="destination" name="destination" value="<?php echo sanitizeInput($vehicle['destination']); ?>" placeholder="e.g. मोतीहारी / पटना">
                    </div>

                    <!-- Route Name -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="route_name" class="form-label small fw-semibold text-dark">Route Name Title</label>
                        <input type="text" class="form-control" id="route_name" name="route_name" value="<?php echo sanitizeInput($vehicle['route_name']); ?>" placeholder="e.g. सिवान से मोतीहारी">
                    </div>

                    <!-- Via Points -->
                    <div class="col-12 col-md-6">
                        <label for="via" class="form-label small fw-semibold text-dark">Intermediate Via Points</label>
                        <input type="text" class="form-control" id="via" name="via" value="<?php echo sanitizeInput($vehicle['via']); ?>" placeholder="e.g. मीरगंज, गोपालगंज, मोहम्मदपुर, खजुरिया">
                        <div class="form-text small">Comma-separated towns or major junctions on the route.</div>
                    </div>

                    <!-- Trips Per Day -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="trips_per_day" class="form-label small fw-semibold text-dark">Trips Per Day</label>
                        <input type="text" class="form-control" id="trips_per_day" name="trips_per_day" value="<?php echo sanitizeInput($vehicle['trips_per_day']); ?>" placeholder="e.g. प्रतिदिन दो खेप आना जाना">
                    </div>

                    <!-- Total Distance in KM -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="total_distance_km" class="form-label small fw-semibold text-dark">Total Distance (KM)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" class="form-control" id="total_distance_km" name="total_distance_km" value="<?php echo sanitizeInput($vehicle['total_distance_km']); ?>" placeholder="484">
                            <span class="input-group-text bg-light text-muted small">KM</span>
                        </div>
                    </div>

                    <!-- Speed Range -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="speed_range" class="form-label small fw-semibold text-dark">Permitted Speed Range</label>
                        <input type="text" class="form-control" id="speed_range" name="speed_range" value="<?php echo sanitizeInput($vehicle['speed_range']); ?>" placeholder="20 से 60 कि० मी० प्रति घंटा">
                    </div>

                    <!-- Nationalized Status -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="is_nationalized" class="form-label small fw-semibold text-dark">Nationalized Route Status</label>
                        <input type="text" class="form-control" id="is_nationalized" name="is_nationalized" value="<?php echo sanitizeInput($vehicle['is_nationalized']); ?>" placeholder="40 कि.मी. के अन्तर्गत">
                    </div>

                    <!-- Route Description -->
                    <div class="col-12 col-md-4">
                        <label for="route_description" class="form-label small fw-semibold text-dark">Full Route Description</label>
                        <textarea class="form-control" id="route_description" name="route_description" rows="2" placeholder="परिचालित मार्ग संख्या, भाया एवं खेप विवरण..."><?php echo sanitizeInput($vehicle['route_description']); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timetable & Stoppages Interactive Card -->
        <div class="card border-0 shadow-sm mb-4" id="timetable-card">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-clock-history text-info me-2"></i>Timetable Stoppages & Schedules
                    </h6>
                    <small class="text-muted">Manage sequence of stops, arrival and departure timings for this bus service.</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-info btn-sm rounded-pill fw-semibold" id="btnGenReturnTrip" title="Generate reverse sequence for return trip">
                        <i class="bi bi-arrow-repeat me-1"></i> Add Return Trip Stops
                    </button>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill fw-bold" id="btnAddStopRow">
                        <i class="bi bi-plus-lg me-1"></i> Add Stop
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0" id="stopsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">#</th>
                                <th style="min-width: 170px;">Stop / Station Name <span class="text-danger">*</span></th>
                                <th style="width: 90px;">Trip #</th>
                                <th style="width: 105px;">Direction</th>
                                <th style="width: 110px;">Arrival Time</th>
                                <th style="width: 110px;">Departure Time</th>
                                <th style="width: 90px;">Halt (Min)</th>
                                <th style="width: 95px;">Dist (KM)</th>
                                <th style="width: 100px;">Travel Time</th>
                                <th style="width: 50px;" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="stopsTableBody">
                            <?php if (empty($timetables)): ?>
                                <!-- Empty default row -->
                                <tr class="stop-row">
                                    <td class="text-center fw-bold text-muted row-index">1</td>
                                    <td>
                                        <input type="text" list="placesDatalist" name="stops[0][stop_name]" class="form-control form-control-sm stop-name-input fw-semibold" placeholder="e.g. सिवान" required>
                                        <input type="hidden" name="stops[0][stop_order]" value="1" class="stop-order-input">
                                    </td>
                                    <td>
                                        <input type="number" name="stops[0][trip_number]" class="form-control form-control-sm trip-num-input" value="1" min="1">
                                    </td>
                                    <td>
                                        <select name="stops[0][trip_direction]" class="form-select form-select-sm trip-dir-select">
                                            <option value="UP" selected>UP</option>
                                            <option value="DOWN">DOWN</option>
                                            <option value="RETURN">RETURN</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="stops[0][arrival_time]" class="form-control form-control-sm arr-time-input font-monospace" placeholder="HH:MM">
                                    </td>
                                    <td>
                                        <input type="text" name="stops[0][departure_time]" class="form-control form-control-sm dep-time-input font-monospace" placeholder="HH:MM">
                                    </td>
                                    <td>
                                        <input type="number" name="stops[0][halt_duration_minutes]" class="form-control form-control-sm halt-input" placeholder="0">
                                    </td>
                                    <td>
                                        <input type="number" step="0.1" name="stops[0][distance_km]" class="form-control form-control-sm dist-input" placeholder="0.0">
                                    </td>
                                    <td>
                                        <input type="text" name="stops[0][travel_time]" class="form-control form-control-sm travel-input" placeholder="0:20">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm p-1 border-0 btn-remove-stop" title="Remove stop">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($timetables as $idx => $tt): ?>
                                    <tr class="stop-row">
                                        <td class="text-center fw-bold text-muted row-index"><?php echo $idx + 1; ?></td>
                                        <td>
                                            <input type="text" list="placesDatalist" name="stops[<?php echo $idx; ?>][stop_name]" class="form-control form-control-sm stop-name-input fw-semibold" value="<?php echo sanitizeInput($tt['stop_name']); ?>" required>
                                            <input type="hidden" name="stops[<?php echo $idx; ?>][stop_order]" value="<?php echo intval($tt['stop_order']); ?>" class="stop-order-input">
                                        </td>
                                        <td>
                                            <input type="number" name="stops[<?php echo $idx; ?>][trip_number]" class="form-control form-control-sm trip-num-input" value="<?php echo intval($tt['trip_number']); ?>" min="1">
                                        </td>
                                        <td>
                                            <select name="stops[<?php echo $idx; ?>][trip_direction]" class="form-select form-select-sm trip-dir-select">
                                                <option value="UP" <?php echo ($tt['trip_direction'] === 'UP') ? 'selected' : ''; ?>>UP</option>
                                                <option value="DOWN" <?php echo ($tt['trip_direction'] === 'DOWN') ? 'selected' : ''; ?>>DOWN</option>
                                                <option value="RETURN" <?php echo ($tt['trip_direction'] === 'RETURN') ? 'selected' : ''; ?>>RETURN</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="stops[<?php echo $idx; ?>][arrival_time]" class="form-control form-control-sm arr-time-input font-monospace" value="<?php echo sanitizeInput($tt['arrival_time_formatted'] ?: ($tt['arrival_time'] ? substr($tt['arrival_time'], 0, 5) : '')); ?>" placeholder="HH:MM">
                                        </td>
                                        <td>
                                            <input type="text" name="stops[<?php echo $idx; ?>][departure_time]" class="form-control form-control-sm dep-time-input font-monospace" value="<?php echo sanitizeInput($tt['departure_time_formatted'] ?: ($tt['departure_time'] ? substr($tt['departure_time'], 0, 5) : '')); ?>" placeholder="HH:MM">
                                        </td>
                                        <td>
                                            <input type="number" name="stops[<?php echo $idx; ?>][halt_duration_minutes]" class="form-control form-control-sm halt-input" value="<?php echo intval($tt['halt_duration_minutes']); ?>" placeholder="0">
                                        </td>
                                        <td>
                                            <input type="number" step="0.1" name="stops[<?php echo $idx; ?>][distance_km]" class="form-control form-control-sm dist-input" value="<?php echo floatval($tt['distance_km']); ?>" placeholder="0.0">
                                        </td>
                                        <td>
                                            <input type="text" name="stops[<?php echo $idx; ?>][travel_time]" class="form-control form-control-sm travel-input" value="<?php echo sanitizeInput($tt['travel_time']); ?>" placeholder="0:20">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm p-1 border-0 btn-remove-stop" title="Remove stop">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light py-2 d-flex align-items-center justify-content-between small text-muted">
                <div>
                    <i class="bi bi-info-circle me-1"></i>Format time as <code>HH:MM</code> (24-hr, e.g. <code>06:09</code>, <code>14:30</code>). Empty arrival means origin, empty departure means destination.
                </div>
                <div>
                    Total stops: <strong id="stopCountDisplay"><?php echo count($timetables); ?></strong>
                </div>
            </div>
        </div>

        <!-- Submit & Save Actions Bottom Bar -->
        <div class="card border-0 shadow-sm p-3 mb-5 sticky-bottom bg-white border-top">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="small text-muted">
                    <?php if ($editing_id): ?>
                        Last updated permit #<strong><?php echo $editing_id; ?></strong> (<?php echo sanitizeInput($vehicle['registration_number']); ?>)
                    <?php else: ?>
                        Creating new regional transport permit record.
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="buses.php" class="btn btn-outline-secondary px-4 rounded-pill">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Save Bus Permit
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

<!-- Autocomplete datalist for known Saran Division towns/stops -->
<datalist id="placesDatalist">
    <?php foreach ($suggestedPlaces as $pl): ?>
        <option value="<?php echo sanitizeInput($pl); ?>"></option>
    <?php endforeach; ?>
</datalist>

<!-- JavaScript to add, remove, and re-sequence timetable rows -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('stopsTableBody');
    const addBtn = document.getElementById('btnAddStopRow');
    const genReturnBtn = document.getElementById('btnGenReturnTrip');
    const stopCountDisplay = document.getElementById('stopCountDisplay');

    function updateRowIndices() {
        const rows = tbody.querySelectorAll('.stop-row');
        rows.forEach((row, idx) => {
            const indexCell = row.querySelector('.row-index');
            if (indexCell) indexCell.textContent = idx + 1;

            // Update input names for clean POST indexing
            const inputs = row.querySelectorAll('input, select');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name && name.startsWith('stops[')) {
                    input.setAttribute('name', name.replace(/stops\[\d+\]/, 'stops[' + idx + ']'));
                }
            });

            // Update stop order input
            const orderInput = row.querySelector('.stop-order-input');
            if (orderInput) orderInput.value = idx + 1;
        });

        if (stopCountDisplay) {
            stopCountDisplay.textContent = rows.length;
        }
    }

    // Attach remove event listener
    tbody.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.btn-remove-stop');
        if (removeBtn) {
            const row = removeBtn.closest('.stop-row');
            if (tbody.querySelectorAll('.stop-row').length > 1) {
                row.remove();
                updateRowIndices();
            } else {
                // Clear row values instead of deleting last row
                row.querySelectorAll('input').forEach(i => i.value = '');
                updateRowIndices();
            }
        }
    });

    // Add Stop Row
    addBtn.addEventListener('click', function() {
        const rows = tbody.querySelectorAll('.stop-row');
        const lastRow = rows[rows.length - 1];
        let nextTrip = 1;
        let nextDir = 'UP';

        if (lastRow) {
            const tripInput = lastRow.querySelector('.trip-num-input');
            const dirSelect = lastRow.querySelector('.trip-dir-select');
            if (tripInput) nextTrip = tripInput.value || 1;
            if (dirSelect) nextDir = dirSelect.value || 'UP';
        }

        const newIndex = rows.length;
        const tr = document.createElement('tr');
        tr.className = 'stop-row';
        tr.innerHTML = `
            <td class="text-center fw-bold text-muted row-index">${newIndex + 1}</td>
            <td>
                <input type="text" list="placesDatalist" name="stops[${newIndex}][stop_name]" class="form-control form-control-sm stop-name-input fw-semibold" placeholder="Stop Name" required>
                <input type="hidden" name="stops[${newIndex}][stop_order]" value="${newIndex + 1}" class="stop-order-input">
            </td>
            <td>
                <input type="number" name="stops[${newIndex}][trip_number]" class="form-control form-control-sm trip-num-input" value="${nextTrip}" min="1">
            </td>
            <td>
                <select name="stops[${newIndex}][trip_direction]" class="form-select form-select-sm trip-dir-select">
                    <option value="UP" ${nextDir === 'UP' ? 'selected' : ''}>UP</option>
                    <option value="DOWN" ${nextDir === 'DOWN' ? 'selected' : ''}>DOWN</option>
                    <option value="RETURN" ${nextDir === 'RETURN' ? 'selected' : ''}>RETURN</option>
                </select>
            </td>
            <td>
                <input type="text" name="stops[${newIndex}][arrival_time]" class="form-control form-control-sm arr-time-input font-monospace" placeholder="HH:MM">
            </td>
            <td>
                <input type="text" name="stops[${newIndex}][departure_time]" class="form-control form-control-sm dep-time-input font-monospace" placeholder="HH:MM">
            </td>
            <td>
                <input type="number" name="stops[${newIndex}][halt_duration_minutes]" class="form-control form-control-sm halt-input" placeholder="0">
            </td>
            <td>
                <input type="number" step="0.1" name="stops[${newIndex}][distance_km]" class="form-control form-control-sm dist-input" placeholder="0.0">
            </td>
            <td>
                <input type="text" name="stops[${newIndex}][travel_time]" class="form-control form-control-sm travel-input" placeholder="0:20">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm p-1 border-0 btn-remove-stop" title="Remove stop">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        updateRowIndices();
        tr.querySelector('.stop-name-input').focus();
    });

    // Auto-Generate Return Trip Helper (Reverses existing Trip 1 sequence)
    genReturnBtn.addEventListener('click', function() {
        const rows = Array.from(tbody.querySelectorAll('.stop-row'));
        const trip1Stops = [];

        rows.forEach(row => {
            const nameInput = row.querySelector('.stop-name-input');
            const tripInput = row.querySelector('.trip-num-input');
            const val = nameInput ? nameInput.value.trim() : '';
            const tNum = tripInput ? parseInt(tripInput.value) || 1 : 1;
            if (val && tNum === 1) {
                trip1Stops.push(val);
            }
        });

        if (trip1Stops.length === 0) {
            alert('Please enter at least 2 stops for Trip #1 first to generate the return trip sequence.');
            return;
        }

        if (confirm(\`Generate reverse return trip for \${trip1Stops.length} stops as Trip #2 (DOWN)?\`)) {
            const reversedStops = [...trip1Stops].reverse();
            reversedStops.forEach((stopName, idx) => {
                const newRowIndex = tbody.querySelectorAll('.stop-row').length;
                const tr = document.createElement('tr');
                tr.className = 'stop-row';
                tr.innerHTML = \`
                    <td class="text-center fw-bold text-muted row-index">\${newRowIndex + 1}</td>
                    <td>
                        <input type="text" list="placesDatalist" name="stops[\${newRowIndex}][stop_name]" class="form-control form-control-sm stop-name-input fw-semibold" value="\${stopName}" required>
                        <input type="hidden" name="stops[\${newRowIndex}][stop_order]" value="\${newRowIndex + 1}" class="stop-order-input">
                    </td>
                    <td>
                        <input type="number" name="stops[\${newRowIndex}][trip_number]" class="form-control form-control-sm trip-num-input" value="2" min="1">
                    </td>
                    <td>
                        <select name="stops[\${newRowIndex}][trip_direction]" class="form-select form-select-sm trip-dir-select">
                            <option value="UP">UP</option>
                            <option value="DOWN" selected>DOWN</option>
                            <option value="RETURN">RETURN</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="stops[\${newRowIndex}][arrival_time]" class="form-control form-control-sm arr-time-input font-monospace" placeholder="HH:MM">
                    </td>
                    <td>
                        <input type="text" name="stops[\${newRowIndex}][departure_time]" class="form-control form-control-sm dep-time-input font-monospace" placeholder="HH:MM">
                    </td>
                    <td>
                        <input type="number" name="stops[\${newRowIndex}][halt_duration_minutes]" class="form-control form-control-sm halt-input" placeholder="0">
                    </td>
                    <td>
                        <input type="number" step="0.1" name="stops[\${newRowIndex}][distance_km]" class="form-control form-control-sm dist-input" placeholder="0.0">
                    </td>
                    <td>
                        <input type="text" name="stops[\${newRowIndex}][travel_time]" class="form-control form-control-sm travel-input" placeholder="0:20">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm p-1 border-0 btn-remove-stop" title="Remove stop">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                \`;
                tbody.appendChild(tr);
            });
            updateRowIndices();
        }
    });

    updateRowIndices();
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

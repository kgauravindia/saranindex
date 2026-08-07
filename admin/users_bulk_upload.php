<?php
// Handle CSV Download before sending any HTML output
if (isset($_GET['download_sample']) && $_GET['download_sample'] == '1') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="saran_directory_users_bulk_sample.csv"');
    
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    fputcsv($output, [
        'full_name', 'mobile', 'email', 'password', 'whatsapp',
        'business_name', 'designation', 'block_name', 'address', 'pincode', 'status', 'type'
    ]);
    
    fputcsv($output, [
        'Ramesh Kumar Sharma', '9876500001', 'ramesh.sharma@example.com', 'Pass@1234',
        '9876500001', 'Sharma Traders', 'Proprietor', 'Chapra Sadar', 'Station Road, Chapra',
        '841301', 'ACTIVE', 'USER'
    ]);

    fputcsv($output, [
        'Dr. Anjali Verma', '9876500002', 'dr.anjali@example.com', 'Doctor@2026',
        '9876500002', 'Verma Health Clinic', 'Chief Medical Officer', 'Marhaura', 'Main Bazaar, Marhaura',
        '841415', 'ACTIVE', 'USER'
    ]);

    fclose($output);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['reset']) && $_GET['reset'] == '1') {
    unset($_SESSION['bulk_csv_users_preview']);
    header("Location: users_bulk_upload.php");
    exit;
}

$header_title = "Bulk Upload Users";
require_once __DIR__ . '/includes/header.php';

$success_count = 0;
$error_count = 0;
$skipped_count = 0;
$log_results = [];
$processed = false;
$preview_mode = false;
$preview_list = [];
$error_msg = '';

// Step 1: Parse CSV File for Preview & Selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext === 'csv') {
            $handle = fopen($file['tmp_name'], 'r');
            if ($handle !== false) {
                $bom = fread($handle, 3);
                if ($bom !== "\xEF\xBB\xBF") {
                    rewind($handle);
                }

                $raw_headers = fgetcsv($handle, 2000, ",");
                if ($raw_headers) {
                    $headers = array_map(function($h) {
                        return strtolower(trim(str_replace([' ', '-'], '_', $h)));
                    }, $raw_headers);

                    $row_number = 1;
                    $parsed_rows = [];
                    while (($row = fgetcsv($handle, 2000, ",")) !== false) {
                        $row_number++;
                        if (empty(array_filter($row))) {
                            continue;
                        }

                        $data_row = [];
                        foreach ($headers as $idx => $header_name) {
                            $data_row[$header_name] = isset($row[$idx]) ? trim($row[$idx]) : '';
                        }

                        $full_name = sanitizeInput($data_row['full_name'] ?? $data_row['name'] ?? $data_row['user_name'] ?? '');
                        $mobile = sanitizeInput($data_row['mobile'] ?? $data_row['phone'] ?? $data_row['contact'] ?? '');

                        $is_valid = (!empty($full_name) && !empty($mobile) && strlen($mobile) >= 10);
                        $validation_msg = $is_valid ? 'Valid record' : 'Full Name and 10-digit Mobile number are required';

                        $parsed_rows[] = [
                            'index' => count($parsed_rows),
                            'row_number' => $row_number,
                            'full_name' => $full_name,
                            'mobile' => $mobile,
                            'email' => sanitizeInput($data_row['email'] ?? ''),
                            'business_name' => sanitizeInput($data_row['business_name'] ?? ''),
                            'block_name' => sanitizeInput($data_row['block_name'] ?? ''),
                            'status' => sanitizeInput($data_row['status'] ?? 'ACTIVE'),
                            'type' => sanitizeInput($data_row['type'] ?? 'USER'),
                            'is_valid' => $is_valid,
                            'validation_msg' => $validation_msg,
                            'raw' => $data_row
                        ];
                    }
                    fclose($handle);

                    if (!empty($parsed_rows)) {
                        $_SESSION['bulk_csv_users_preview'] = $parsed_rows;
                        $preview_list = $parsed_rows;
                        $preview_mode = true;
                    } else {
                        $error_msg = "CSV file contains no data rows.";
                    }
                } else {
                    $error_msg = "CSV file is empty or missing header line.";
                }
            } else {
                $error_msg = "Unable to read uploaded CSV file.";
            }
        } else {
            $error_msg = "Invalid file format. Please upload a .csv file.";
        }
    } else {
        $error_msg = "Upload failed with error code: " . $file['error'];
    }
}

// Step 2: Import Selected User Rows
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_selected'])) {
    $preview_list = $_SESSION['bulk_csv_users_preview'] ?? [];
    $selected_indices = isset($_POST['selected_rows']) ? (array)$_POST['selected_rows'] : [];

    if (empty($preview_list)) {
        $error_msg = "No CSV preview data found. Please upload a CSV file first.";
    } elseif (empty($selected_indices)) {
        $error_msg = "No rows were selected! Please check at least one row checkbox to import.";
        $preview_mode = true;
    } else {
        // Pre-fetch Block maps
        $all_blocks = getBlocks();
        $block_map = [];
        foreach ($all_blocks as $b) {
            $block_map[strtolower(trim($b['block_name']))] = $b['id'];
            if (!empty($b['hindi_name'])) {
                $block_map[strtolower(trim($b['hindi_name']))] = $b['id'];
            }
            if (!empty($b['name_english'])) {
                $block_map[strtolower(trim($b['name_english']))] = $b['id'];
            }
        }

        $db = getDB();
        $selected_map = array_flip($selected_indices);

        foreach ($preview_list as $item) {
            if (!isset($selected_map[$item['index']])) {
                continue;
            }

            $data_row = $item['raw'];
            $row_number = $item['row_number'];

            $full_name = sanitizeInput($data_row['full_name'] ?? $data_row['name'] ?? $data_row['user_name'] ?? '');
            $mobile = sanitizeInput($data_row['mobile'] ?? $data_row['phone'] ?? $data_row['contact'] ?? '');

            if (empty($full_name) || empty($mobile) || strlen($mobile) < 10) {
                $error_count++;
                $log_results[] = [
                    'row' => $row_number,
                    'name' => $full_name ?: '(Empty Name)',
                    'mobile' => $mobile ?: '(Missing Mobile)',
                    'status' => 'ERROR',
                    'message' => 'Full Name and 10-digit Mobile number are required.'
                ];
                continue;
            }

            // Determine Block ID
            $block_val = $data_row['block_name'] ?? $data_row['block'] ?? $data_row['block_id'] ?? '';
            $block_id = null;
            if (is_numeric($block_val) && intval($block_val) > 0) {
                $block_id = intval($block_val);
            } else if (!empty($block_val) && isset($block_map[strtolower($block_val)])) {
                $block_id = $block_map[strtolower($block_val)];
            }

            $password = !empty($data_row['password']) ? $data_row['password'] : 'Saran@' . substr($mobile, -4);
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $email = sanitizeInput($data_row['email'] ?? '');
            $whatsapp = sanitizeInput($data_row['whatsapp'] ?? $mobile);
            $business_name = sanitizeInput($data_row['business_name'] ?? '');
            $designation = sanitizeInput($data_row['designation'] ?? '');
            $address = sanitizeInput($data_row['address'] ?? '');
            $pincode = sanitizeInput($data_row['pincode'] ?? '841301');
            
            $status = strtoupper(sanitizeInput($data_row['status'] ?? 'ACTIVE'));
            if (!in_array($status, ['ACTIVE', 'INACTIVE', 'SUSPENDED'])) {
                $status = 'ACTIVE';
            }

            $type = strtoupper(sanitizeInput($data_row['type'] ?? 'USER'));
            if (!in_array($type, ['USER', 'AGENT', 'ADMIN'])) {
                $type = 'USER';
            }

            try {
                $chkStmt = $db->prepare("SELECT id FROM users WHERE mobile = :mob LIMIT 1");
                $chkStmt->execute(['mob' => $mobile]);
                $existing_user = $chkStmt->fetch();

                if ($existing_user) {
                    $upStmt = $db->prepare("UPDATE users SET full_name = :fn, email = :em, whatsapp = :wa, business_name = :bn, designation = :desig, block_id = :blk, address = :addr, pincode = :pin, status = :st, type = :tp WHERE id = :id");
                    $upStmt->execute([
                        'fn' => $full_name,
                        'em' => $email ?: null,
                        'wa' => $whatsapp,
                        'bn' => $business_name ?: null,
                        'desig' => $designation ?: null,
                        'blk' => $block_id,
                        'addr' => $address ?: null,
                        'pin' => $pincode,
                        'st' => $status,
                        'tp' => $type,
                        'id' => $existing_user['id']
                    ]);

                    $skipped_count++;
                    $log_results[] = [
                        'row' => $row_number,
                        'name' => $full_name,
                        'mobile' => $mobile,
                        'status' => 'UPDATED',
                        'message' => "Mobile already registered. Updated existing user profile (#{$existing_user['id']})."
                    ];
                } else {
                    $insStmt = $db->prepare("INSERT INTO users (full_name, name, mobile, whatsapp, email, password_hash, password, business_name, designation, block_id, address, pincode, status, type, mobile_status) VALUES (:fn, :name, :mob, :wa, :em, :ph, :p, :bn, :desig, :blk, :addr, :pin, :st, :tp, 'VERIFIED')");
                    $insStmt->execute([
                        'fn' => $full_name,
                        'name' => $full_name,
                        'mob' => $mobile,
                        'wa' => $whatsapp,
                        'em' => $email ?: null,
                        'ph' => $password_hash,
                        'p' => $password,
                        'bn' => $business_name ?: null,
                        'desig' => $designation ?: null,
                        'blk' => $block_id,
                        'addr' => $address ?: null,
                        'pin' => $pincode,
                        'st' => $status,
                        'tp' => $type
                    ]);

                    $success_count++;
                    $log_results[] = [
                        'row' => $row_number,
                        'name' => $full_name,
                        'mobile' => $mobile,
                        'status' => 'SUCCESS',
                        'message' => 'New user account created successfully.'
                    ];
                }
            } catch (PDOException $e) {
                $error_count++;
                $log_results[] = [
                    'row' => $row_number,
                    'name' => $full_name,
                    'mobile' => $mobile,
                    'status' => 'ERROR',
                    'message' => 'Database operation error: ' . $e->getMessage()
                ];
            }
        }
        unset($_SESSION['bulk_csv_users_preview']);
        $processed = true;
    }
}

// Fallback preview mode from active session
if (!$processed && !$preview_mode && !empty($_SESSION['bulk_csv_users_preview'])) {
    $preview_list = $_SESSION['bulk_csv_users_preview'];
    $preview_mode = true;
}

$blocks_list = getBlocks();
?>

<!-- Header Banner -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Admin</a></li>
                <li class="breadcrumb-item"><a href="users.php" class="text-decoration-none text-muted">User Accounts</a></li>
                <li class="breadcrumb-item active fw-semibold text-primary" aria-current="page">Bulk Upload Users</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0 text-dark">Bulk Upload User Accounts</h4>
        <p class="text-muted small mb-0">Import multiple community users, business owners, or district representatives via CSV spreadsheet.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="users_bulk_upload.php?download_sample=1" class="btn btn-outline-success fw-bold btn-sm px-3 rounded-3 shadow-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Download Sample CSV
        </a>
        <a href="users.php" class="btn btn-outline-secondary btn-sm px-3 rounded-3 fw-medium">
            <i class="bi bi-people me-1"></i> View All Users
        </a>
    </div>
</div>

<?php if (!empty($error_msg)): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($error_msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($preview_mode && !empty($preview_list)): ?>
    <!-- SELECTABLE CSV USER PREVIEW CARD -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-primary">
        <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 border-bottom">
            <div>
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-person-check-fill me-2"></i>Select Users to Import
                </h5>
                <p class="text-muted small mb-0">Select/deselect individual user accounts below before executing the bulk import into database.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span id="selected_user_count_badge" class="badge bg-primary fs-7 px-3 py-2 rounded-pill shadow-xs">
                    <?php echo count($preview_list); ?> of <?php echo count($preview_list); ?> Selected
                </span>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="selectAllUserRows()">
                    <i class="bi bi-check-all me-1"></i>Select All
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="selectValidUsersOnly()">
                    <i class="bi bi-shield-check me-1"></i>Select Valid Only
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="deselectAllUserRows()">
                    <i class="bi bi-x-circle me-1"></i>Deselect All
                </button>
            </div>
        </div>

        <form action="users_bulk_upload.php" method="POST" id="importSelectedUsersForm">
            <input type="hidden" name="import_selected" value="1">
            <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-light sticky-top shadow-xs">
                        <tr>
                            <th style="width: 45px;" class="text-center">
                                <input type="checkbox" id="master_user_cb" class="form-check-input cursor-pointer" checked title="Select / Deselect All">
                            </th>
                            <th style="width: 75px;">Row</th>
                            <th>Full Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Business Name</th>
                            <th>Block</th>
                            <th>Role & Status</th>
                            <th>Validation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($preview_list as $item): ?>
                            <tr class="<?php echo $item['is_valid'] ? '' : 'table-warning'; ?>">
                                <td class="text-center">
                                    <input type="checkbox" name="selected_rows[]" value="<?php echo $item['index']; ?>" class="form-check-input user-row-select-cb cursor-pointer" data-valid="<?php echo $item['is_valid'] ? '1' : '0'; ?>" <?php echo $item['is_valid'] ? 'checked' : ''; ?>>
                                </td>
                                <td class="fw-bold text-muted">#<?php echo $item['row_number']; ?></td>
                                <td class="fw-semibold text-dark">
                                    <?php echo !empty($item['full_name']) ? sanitizeInput($item['full_name']) : '<em class="text-danger">(Missing Name)</em>'; ?>
                                </td>
                                <td>
                                    <?php echo !empty($item['mobile']) ? sanitizeInput($item['mobile']) : '<em class="text-danger">(Missing Mobile)</em>'; ?>
                                </td>
                                <td class="text-muted"><?php echo sanitizeInput($item['email'] ?: '—'); ?></td>
                                <td class="text-dark"><?php echo sanitizeInput($item['business_name'] ?: '—'); ?></td>
                                <td><span class="badge bg-light text-primary border"><?php echo sanitizeInput($item['block_name'] ?: 'Chapra Sadar'); ?></span></td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary small me-1"><?php echo sanitizeInput($item['type']); ?></span>
                                    <span class="badge bg-success-subtle text-success small"><?php echo sanitizeInput($item['status']); ?></span>
                                </td>
                                <td>
                                    <?php if ($item['is_valid']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                            <i class="bi bi-check-circle-fill me-1"></i>Ready
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1" title="<?php echo htmlspecialchars($item['validation_msg']); ?>">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Invalid Row
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-light p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <a href="users_bulk_upload.php?reset=1" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Cancel & Upload Different File
                </a>
                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm py-2">
                    <i class="bi bi-person-plus-fill me-1"></i>Import Selected Users (<span id="user_btn_submit_count"><?php echo count($preview_list); ?></span>)
                </button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const masterCb = document.getElementById('master_user_cb');
        const checkboxes = document.querySelectorAll('.user-row-select-cb');
        const badge = document.getElementById('selected_user_count_badge');
        const btnCount = document.getElementById('user_btn_submit_count');

        function updateCounts() {
            let selected = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) selected++;
            });
            if (badge) badge.textContent = selected + ' of ' + checkboxes.length + ' Selected';
            if (btnCount) btnCount.textContent = selected;
        }

        if (masterCb) {
            masterCb.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = masterCb.checked);
                updateCounts();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (!cb.checked && masterCb) masterCb.checked = false;
                updateCounts();
            });
        });

        window.selectAllUserRows = function() {
            checkboxes.forEach(cb => cb.checked = true);
            if (masterCb) masterCb.checked = true;
            updateCounts();
        };

        window.deselectAllUserRows = function() {
            checkboxes.forEach(cb => cb.checked = false);
            if (masterCb) masterCb.checked = false;
            updateCounts();
        };

        window.selectValidUsersOnly = function() {
            checkboxes.forEach(cb => {
                cb.checked = (cb.getAttribute('data-valid') === '1');
            });
            if (masterCb) masterCb.checked = false;
            updateCounts();
        };

        updateCounts();
    });
    </script>
<?php endif; ?>

<?php if ($processed): ?>
    <!-- Import Results Summary Card -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-person-check-fill text-primary me-2"></i>User Import Summary Report</h6>
            <div>
                <span class="badge bg-success px-3 py-1.5 rounded-pill me-1"><i class="bi bi-person-plus me-1"></i><?php echo $success_count; ?> Created</span>
                <span class="badge bg-info px-3 py-1.5 rounded-pill me-1"><i class="bi bi-arrow-repeat me-1"></i><?php echo $skipped_count; ?> Updated</span>
                <?php if ($error_count > 0): ?>
                    <span class="badge bg-danger px-3 py-1.5 rounded-pill"><i class="bi bi-x-circle me-1"></i><?php echo $error_count; ?> Failed</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="bg-light sticky-top">
                    <tr>
                        <th style="width: 80px;">CSV Row</th>
                        <th>User Full Name</th>
                        <th>Mobile Number</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($log_results as $res): ?>
                        <tr>
                            <td class="fw-bold text-muted">Row #<?php echo $res['row']; ?></td>
                            <td class="fw-semibold text-dark"><?php echo sanitizeInput($res['name']); ?></td>
                            <td class="text-muted"><?php echo sanitizeInput($res['mobile']); ?></td>
                            <td>
                                <?php if ($res['status'] === 'SUCCESS'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1"><i class="bi bi-check-circle-fill me-1"></i>Created</span>
                                <?php elseif ($res['status'] === 'UPDATED'): ?>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1"><i class="bi bi-arrow-repeat me-1"></i>Updated</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1"><i class="bi bi-exclamation-octagon-fill me-1"></i>Error</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted"><?php echo sanitizeInput($res['message']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light p-3 text-end">
            <a href="users.php" class="btn btn-primary btn-sm rounded-3 px-3 fw-bold"><i class="bi bi-people me-1"></i> View All Users</a>
        </div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Form Column (8 columns) -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-cloud-upload text-primary me-2"></i>Select & Upload User CSV File
            </h6>

            <form action="users_bulk_upload.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="csv_file" class="form-label fw-semibold small">Choose CSV File (.csv) <span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-lg fs-6" id="csv_file" name="csv_file" accept=".csv" required>
                    <div class="form-text text-muted small mt-2">
                        Upload your prepared CSV file containing user headers such as <code>full_name</code>, <code>mobile</code>, <code>email</code>, <code>block_name</code>, etc.
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2.5 shadow-sm rounded-3">
                        <i class="bi bi-cloud-upload me-1"></i> Import User Accounts
                    </button>
                    <a href="users_bulk_upload.php?download_sample=1" class="btn btn-light border fw-medium rounded-3 py-2.5">
                        <i class="bi bi-download me-1"></i> Download Sample CSV
                    </a>
                </div>
            </form>
        </div>

        <!-- CSV Column Specification Guide -->
        <div class="card border-0 shadow-sm rounded-3 p-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-info-square text-primary me-2"></i>CSV Header Format Guide</h6>

            <div class="table-responsive">
                <table class="table table-bordered align-middle small mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Column Header</th>
                            <th>Required?</th>
                            <th>Description / Accepted Values</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>full_name</code></td>
                            <td><span class="badge bg-danger">REQUIRED</span></td>
                            <td>User's full name (e.g. Ramesh Kumar)</td>
                        </tr>
                        <tr>
                            <td><code>mobile</code></td>
                            <td><span class="badge bg-danger">REQUIRED</span></td>
                            <td>10-digit mobile number (used for login)</td>
                        </tr>
                        <tr>
                            <td><code>email</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>User email address</td>
                        </tr>
                        <tr>
                            <td><code>password</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>Login password (Auto-generated if empty: <code>Saran@<last-4-digits></code>)</td>
                        </tr>
                        <tr>
                            <td><code>whatsapp</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>WhatsApp number</td>
                        </tr>
                        <tr>
                            <td><code>business_name</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>Business or shop name</td>
                        </tr>
                        <tr>
                            <td><code>designation</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>Designation / Title (e.g. Proprietor, Doctor, Director)</td>
                        </tr>
                        <tr>
                            <td><code>block_name</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>Block location name (e.g. Chapra Sadar, Marhaura, Sonpur)</td>
                        </tr>
                        <tr>
                            <td><code>address</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>Full address / Street address</td>
                        </tr>
                        <tr>
                            <td><code>pincode</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>6-digit Pincode (Default: 841301)</td>
                        </tr>
                        <tr>
                            <td><code>status</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td><code>ACTIVE</code>, <code>INACTIVE</code>, or <code>SUSPENDED</code></td>
                        </tr>
                        <tr>
                            <td><code>type</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td><code>USER</code>, <code>AGENT</code>, or <code>ADMIN</code></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column Sidebar (4 columns) -->
    <div class="col-12 col-lg-4">
        <!-- Blocks Reference Card -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-geo-alt text-primary me-2"></i>Saran District Blocks</h6>
            <div class="d-flex flex-wrap gap-1" style="max-height: 350px; overflow-y: auto;">
                <?php foreach ($blocks_list as $blk): ?>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle small p-2"><?php echo sanitizeInput($blk['block_name']); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
// Handle CSV Download before sending HTML output
if (isset($_GET['download_sample']) && $_GET['download_sample'] == '1') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="saran_directory_bulk_upload_sample.csv"');
    
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    fputcsv($output, [
        'title', 'hindi_title', 'entity_type', 'category_name', 'subcategory_name',
        'block_name', 'mobile', 'whatsapp', 'contact_person', 'email', 'website',
        'address', 'pincode', 'business_hours', 'services', 'products',
        'gst_no', 'udyam_no', 'cin_no', 'local_reg_no', 'description',
        'is_verified', 'is_featured', 'status', 'plan_type'
    ]);
    
    fputcsv($output, [
        'Saran Eye & General Care Clinic', 'सारण नेत्र व सामान्य चिकित्सा केंद्र', 'HEALTHCARE',
        'Health & Medical', 'Doctors & Clinics', 'Chapra Sadar', '9876543210', '9876543210',
        'Dr. A. K. Singh', 'info@saraneye.com', 'https://saraneye.com',
        'Station Road, Near Bus Stand, Chapra', '841301', '9:00 AM - 7:00 PM',
        'Eye Checkup, Cataract Surgery, OPD, Glasses', 'Leading eye clinic in Saran',
        'YES', 'YES', 'ACTIVE', 'GOLD'
    ]);

    fputcsv($output, [
        'Chapra Electronics & Home Appliances', 'छपरा इलेक्ट्रॉनिक्स एवं होम एप्लायंसेज', 'BUSINESS',
        'Shops & Retail', 'Retail Shops', 'Chapra Sadar', '9812345678', '9812345678',
        'Rajesh Kumar Gupta', 'chapraelectronics@gmail.com', '',
        'Main Bazaar, Katchahry Chowk, Chapra', '841301', '10:00 AM - 8:30 PM',
        'Smart TVs, Refrigerators, AC, Washing Machines', 'Home appliances store',
        'NO', 'NO', 'ACTIVE', 'FREE'
    ]);

    fclose($output);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['reset']) && $_GET['reset'] == '1') {
    unset($_SESSION['bulk_csv_listings_preview']);
    header("Location: bulk_upload.php");
    exit;
}

$header_title = "Bulk Upload Listings";
require_once __DIR__ . '/includes/header.php';

$success_count = 0;
$error_count = 0;
$log_results = [];
$processed = false;
$preview_mode = false;
$preview_list = [];
$error_msg = '';

// Step 1: Upload CSV and Parse for Preview & Selection
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

                        $title = sanitizeInput($data_row['title'] ?? $data_row['listing_title'] ?? $data_row['name'] ?? '');
                        $mobile = sanitizeInput($data_row['mobile'] ?? $data_row['phone'] ?? $data_row['contact'] ?? '');

                        $is_valid = (!empty($title) && !empty($mobile));
                        $validation_msg = $is_valid ? 'Valid record' : 'Missing required Title or Mobile Number';

                        if ($is_valid) {
                            $dupCheck = checkDuplicateListing($title, $mobile);
                            if ($dupCheck) {
                                $is_valid = false;
                                $validation_msg = "Duplicate: Title & Mobile already exists (Listing ID #" . $dupCheck['id'] . ")";
                            }
                        }

                        $parsed_rows[] = [
                            'index' => count($parsed_rows),
                            'row_number' => $row_number,
                            'title' => $title,
                            'mobile' => $mobile,
                            'category_name' => sanitizeInput($data_row['category_name'] ?? $data_row['category'] ?? ''),
                            'block_name' => sanitizeInput($data_row['block_name'] ?? $data_row['block'] ?? ''),
                            'entity_type' => sanitizeInput($data_row['entity_type'] ?? 'BUSINESS'),
                            'plan_type' => sanitizeInput($data_row['plan_type'] ?? 'FREE'),
                            'is_valid' => $is_valid,
                            'validation_msg' => $validation_msg,
                            'raw' => $data_row
                        ];
                    }
                    fclose($handle);

                    if (!empty($parsed_rows)) {
                        $_SESSION['bulk_csv_listings_preview'] = $parsed_rows;
                        $preview_list = $parsed_rows;
                        $preview_mode = true;
                    } else {
                        $error_msg = "CSV file contains no valid data rows.";
                    }
                } else {
                    $error_msg = "CSV file is empty or contains no headers.";
                }
            } else {
                $error_msg = "Unable to read uploaded CSV file.";
            }
        } else {
            $error_msg = "Invalid file type. Please upload a CSV (.csv) file.";
        }
    } else {
        $error_msg = "File upload failed with error code: " . $file['error'];
    }
}

// Step 2: Import Selected CSV Rows
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_selected'])) {
    $preview_list = $_SESSION['bulk_csv_listings_preview'] ?? [];
    $selected_indices = isset($_POST['selected_rows']) ? (array)$_POST['selected_rows'] : [];

    if (empty($preview_list)) {
        $error_msg = "No CSV preview data found. Please upload a CSV file first.";
    } elseif (empty($selected_indices)) {
        $error_msg = "No rows were selected! Please check at least one row checkbox to import.";
        $preview_mode = true;
    } else {
        // Pre-fetch Category maps
        $all_cats = getAllAdminCategories();
        $cat_map = [];
        foreach ($all_cats as $c) {
            $cat_map[strtolower(trim($c['name']))] = $c['id'];
            if (!empty($c['hindi_name'])) {
                $cat_map[strtolower(trim($c['hindi_name']))] = $c['id'];
            }
        }

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

        // Pre-fetch Subcategories
        $all_subs = getAllSubcategories();
        $sub_map = [];
        foreach ($all_subs as $s) {
            $sub_map[$s['category_id'] . '_' . strtolower(trim($s['name']))] = $s['id'];
            if (!empty($s['hindi_name'])) {
                $sub_map[$s['category_id'] . '_' . strtolower(trim($s['hindi_name']))] = $s['id'];
            }
        }

        $selected_map = array_flip($selected_indices);

        foreach ($preview_list as $item) {
            if (!isset($selected_map[$item['index']])) {
                continue;
            }

            $data_row = $item['raw'];
            $row_number = $item['row_number'];
            $title = sanitizeInput($data_row['title'] ?? $data_row['listing_title'] ?? $data_row['name'] ?? '');
            $mobile = sanitizeInput($data_row['mobile'] ?? $data_row['phone'] ?? $data_row['contact'] ?? '');

            if (empty($title) || empty($mobile)) {
                $error_count++;
                $log_results[] = [
                    'row' => $row_number,
                    'title' => $title ?: '(Empty Title)',
                    'status' => 'ERROR',
                    'message' => 'Missing required field: Title or Mobile Number.'
                ];
                continue;
            }

            // Determine Category ID
            $cat_val = $data_row['category_name'] ?? $data_row['category'] ?? $data_row['category_id'] ?? '';
            $category_id = 1;
            if (is_numeric($cat_val) && intval($cat_val) > 0) {
                $category_id = intval($cat_val);
            } else if (!empty($cat_val) && isset($cat_map[strtolower($cat_val)])) {
                $category_id = $cat_map[strtolower($cat_val)];
            }

            // Determine Subcategory ID
            $sub_val = $data_row['subcategory_name'] ?? $data_row['subcategory'] ?? $data_row['subcategory_id'] ?? '';
            $subcategory_id = null;
            if (is_numeric($sub_val) && intval($sub_val) > 0) {
                $subcategory_id = intval($sub_val);
            } else if (!empty($sub_val) && isset($sub_map[$category_id . '_' . strtolower($sub_val)])) {
                $subcategory_id = $sub_map[$category_id . '_' . strtolower($sub_val)];
            }

            // Determine Block ID
            $block_val = $data_row['block_name'] ?? $data_row['block'] ?? $data_row['block_id'] ?? '';
            $block_id = 1;
            if (is_numeric($block_val) && intval($block_val) > 0) {
                $block_id = intval($block_val);
            } else if (!empty($block_val) && isset($block_map[strtolower($block_val)])) {
                $block_id = $block_map[strtolower($block_val)];
            }

            // Entity classification
            $entity_type = strtoupper(sanitizeInput($data_row['entity_type'] ?? 'BUSINESS'));
            $valid_entities = ['BUSINESS','PROFESSIONAL','GOVT_OFFICE','SCHOOL_COLLEGE','HEALTHCARE','EMERGENCY','BANK','HOTEL'];
            if (!in_array($entity_type, $valid_entities)) {
                $entity_type = 'BUSINESS';
            }

            $services_input = sanitizeInput($data_row['services'] ?? '');
            $products_input = sanitizeInput($data_row['products'] ?? $data_row['product'] ?? $data_row['items'] ?? '');
            if (empty($products_input) && !empty($services_input)) {
                if (preg_match('/(Urea|DAP|MOP|NPKS|SSP|Seeds|Fertilizer|Pesticide|Medicine|Hardware)/i', $services_input)) {
                    $products_input = $services_input;
                    $services_input = 'Retail Sales & Supply';
                }
            }

            $listing_data = [
                'entity_type' => $entity_type,
                'category_id' => $category_id,
                'subcategory_id' => $subcategory_id,
                'block_id' => $block_id,
                'panchayat_id' => null,
                'village_id' => null,
                'title' => $title,
                'hindi_title' => sanitizeInput($data_row['hindi_title'] ?? ''),
                'slug' => slugify($title) . '-' . rand(100, 999),
                'contact_person' => sanitizeInput($data_row['contact_person'] ?? ''),
                'mobile' => $mobile,
                'whatsapp' => sanitizeInput($data_row['whatsapp'] ?? ''),
                'email' => sanitizeInput($data_row['email'] ?? ''),
                'website' => sanitizeInput($data_row['website'] ?? ''),
                'address' => sanitizeInput($data_row['address'] ?? ''),
                'pincode' => sanitizeInput($data_row['pincode'] ?? '841301'),
                'map_link' => sanitizeInput($data_row['map_link'] ?? ''),
                'business_hours' => sanitizeInput($data_row['business_hours'] ?? '9:00 AM - 8:00 PM'),
                'services' => $services_input,
                'products' => $products_input,
                'gst_no' => sanitizeInput($data_row['gst_no'] ?? $data_row['gst'] ?? $data_row['gstin'] ?? ''),
                'udyam_no' => sanitizeInput($data_row['udyam_no'] ?? $data_row['udyam'] ?? $data_row['udyog_aadhaar'] ?? ''),
                'cin_no' => sanitizeInput($data_row['cin_no'] ?? $data_row['cin'] ?? ''),
                'local_reg_no' => sanitizeInput($data_row['local_reg_no'] ?? $data_row['trade_license'] ?? $data_row['local_registration'] ?? ''),
                'description' => sanitizeInput($data_row['description'] ?? ''),
                'cover_image' => sanitizeInput($data_row['cover_image'] ?? ''),
                'is_verified' => (strtoupper($data_row['is_verified'] ?? '') === 'YES') ? 'YES' : 'NO',
                'is_featured' => (strtoupper($data_row['is_featured'] ?? '') === 'YES') ? 'YES' : 'NO',
                'status' => in_array(strtoupper($data_row['status'] ?? ''), ['ACTIVE','PENDING','REJECTED']) ? strtoupper($data_row['status']) : 'ACTIVE',
                'plan_type' => in_array(strtoupper($data_row['plan_type'] ?? ''), ['FREE','GOLD','PLATINUM']) ? strtoupper($data_row['plan_type']) : 'FREE',
                'plan_expires_at' => null
            ];

            $dupImp = checkDuplicateListing($title, $mobile);
            if ($dupImp) {
                $error_count++;
                $log_results[] = [
                    'row' => $row_number,
                    'title' => $title,
                    'status' => 'DUPLICATE',
                    'message' => "Duplicate Entry: Listing with title '{$title}' and mobile '{$mobile}' already exists in database (ID #{$dupImp['id']})."
                ];
            } else if (saveListing($listing_data)) {
                $success_count++;
                $log_results[] = [
                    'row' => $row_number,
                    'title' => $title,
                    'status' => 'SUCCESS',
                    'message' => 'Listing created successfully.'
                ];
            } else {
                $error_count++;
                $log_results[] = [
                    'row' => $row_number,
                    'title' => $title,
                    'status' => 'ERROR',
                    'message' => 'Failed to insert row into database.'
                ];
            }
        }
        unset($_SESSION['bulk_csv_listings_preview']);
        $processed = true;
    }
}

// Fallback to active preview session if available and not processed
if (!$processed && !$preview_mode && !empty($_SESSION['bulk_csv_listings_preview'])) {
    $preview_list = $_SESSION['bulk_csv_listings_preview'];
    $preview_mode = true;
}

$categories_list = getAllAdminCategories();
$blocks_list = getBlocks();
?>

<!-- Top Banner Header -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Admin</a></li>
                <li class="breadcrumb-item"><a href="listings.php" class="text-decoration-none text-muted">Directory Listings</a></li>
                <li class="breadcrumb-item active fw-semibold text-primary" aria-current="page">Bulk Upload</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0 text-dark">Bulk Upload Directory Listings</h4>
        <p class="text-muted small mb-0">Upload multiple shops, clinics, schools, or offices via CSV spreadsheet at once.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="bulk_upload.php?download_sample=1" class="btn btn-outline-success fw-bold btn-sm px-3 rounded-3 shadow-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Download Sample CSV
        </a>
        <a href="listings.php" class="btn btn-outline-secondary btn-sm px-3 rounded-3 fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Back to Listings
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
    <!-- SELECTABLE CSV PREVIEW CARD -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-primary">
        <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 border-bottom">
            <div>
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-check-square-fill me-2"></i>Select Rows to Import
                </h5>
                <p class="text-muted small mb-0">Check/uncheck rows below to control exactly which records get saved into the database.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span id="selected_count_badge" class="badge bg-primary fs-7 px-3 py-2 rounded-pill shadow-xs">
                    <?php echo count($preview_list); ?> of <?php echo count($preview_list); ?> Selected
                </span>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="selectAllRows()">
                    <i class="bi bi-check-all me-1"></i>Select All
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="selectValidOnly()">
                    <i class="bi bi-shield-check me-1"></i>Select Valid Only
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="deselectAllRows()">
                    <i class="bi bi-x-circle me-1"></i>Deselect All
                </button>
            </div>
        </div>

        <form action="bulk_upload.php" method="POST" id="importSelectedForm">
            <input type="hidden" name="import_selected" value="1">
            <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-light sticky-top shadow-xs">
                        <tr>
                            <th style="width: 45px;" class="text-center">
                                <input type="checkbox" id="master_cb" class="form-check-input cursor-pointer" checked title="Select / Deselect All">
                            </th>
                            <th style="width: 75px;">Row</th>
                            <th>Listing Title</th>
                            <th>Mobile</th>
                            <th>Category</th>
                            <th>Block</th>
                            <th>Type / Plan</th>
                            <th>Validation Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($preview_list as $item): ?>
                            <tr class="<?php echo $item['is_valid'] ? '' : 'table-warning'; ?>">
                                <td class="text-center">
                                    <input type="checkbox" name="selected_rows[]" value="<?php echo $item['index']; ?>" class="form-check-input row-select-cb cursor-pointer" data-valid="<?php echo $item['is_valid'] ? '1' : '0'; ?>" <?php echo $item['is_valid'] ? 'checked' : ''; ?>>
                                </td>
                                <td class="fw-bold text-muted">#<?php echo $item['row_number']; ?></td>
                                <td class="fw-semibold text-dark">
                                    <?php echo !empty($item['title']) ? sanitizeInput($item['title']) : '<em class="text-danger">(Empty Title)</em>'; ?>
                                </td>
                                <td>
                                    <?php echo !empty($item['mobile']) ? sanitizeInput($item['mobile']) : '<em class="text-danger">(Missing Phone)</em>'; ?>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo sanitizeInput($item['category_name'] ?: 'General'); ?></span></td>
                                <td><span class="badge bg-light text-primary border"><?php echo sanitizeInput($item['block_name'] ?: 'Chapra Sadar'); ?></span></td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary small me-1"><?php echo sanitizeInput($item['entity_type']); ?></span>
                                    <span class="badge bg-warning-subtle text-dark small"><?php echo sanitizeInput($item['plan_type']); ?></span>
                                </td>
                                <td>
                                    <?php if ($item['is_valid']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                            <i class="bi bi-check-circle-fill me-1"></i>Ready to Import
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1" title="<?php echo htmlspecialchars($item['validation_msg']); ?>">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Missing Title/Mobile
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-light p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <a href="bulk_upload.php?reset=1" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Cancel & Upload Different File
                </a>
                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm py-2">
                    <i class="bi bi-cloud-arrow-up-fill me-1"></i>Import Selected Rows (<span id="btn_submit_count"><?php echo count($preview_list); ?></span>)
                </button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const masterCb = document.getElementById('master_cb');
        const checkboxes = document.querySelectorAll('.row-select-cb');
        const badge = document.getElementById('selected_count_badge');
        const btnCount = document.getElementById('btn_submit_count');

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

        window.selectAllRows = function() {
            checkboxes.forEach(cb => cb.checked = true);
            if (masterCb) masterCb.checked = true;
            updateCounts();
        };

        window.deselectAllRows = function() {
            checkboxes.forEach(cb => cb.checked = false);
            if (masterCb) masterCb.checked = false;
            updateCounts();
        };

        window.selectValidOnly = function() {
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

<?php
$subcategories_list = getAllSubcategories();
?>

<?php if ($processed): ?>
    <!-- Bulk Upload Summary Card -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-card-checklist text-primary me-2"></i>Bulk Import Results Summary</h6>
            <div>
                <span class="badge bg-success px-3 py-1.5 rounded-pill me-1"><i class="bi bi-check-circle me-1"></i><?php echo $success_count; ?> Inserted</span>
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
                        <th>Listing Title</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($log_results as $res): ?>
                        <tr>
                            <td class="fw-bold text-muted">Row #<?php echo $res['row']; ?></td>
                            <td class="fw-semibold text-dark"><?php echo sanitizeInput($res['title']); ?></td>
                            <td>
                                <?php if ($res['status'] === 'SUCCESS'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1"><i class="bi bi-check-circle-fill me-1"></i>Success</span>
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
            <a href="listings.php" class="btn btn-primary btn-sm rounded-3 px-3 fw-bold"><i class="bi bi-list-stars me-1"></i> View All Listings</a>
        </div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- CSV Upload Form Card (8 Columns) -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-upload text-primary me-2"></i>Select & Upload CSV Spreadsheet
            </h6>

            <form action="bulk_upload.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="csv_file" class="form-label fw-semibold small">Choose CSV File (.csv) <span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-lg fs-6" id="csv_file" name="csv_file" accept=".csv" required>
                    <div class="form-text text-muted small mt-2">
                        Upload your prepared CSV file containing column headers such as <code>title</code>, <code>mobile</code>, <code>category_name</code>, <code>block_name</code>, etc.
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2.5 shadow-sm rounded-3">
                        <i class="bi bi-cloud-upload me-1"></i> Process & Import Listings
                    </button>
                    <a href="bulk_upload.php?download_sample=1" class="btn btn-light border fw-medium rounded-3 py-2.5">
                        <i class="bi bi-download me-1"></i> Download Sample CSV Template
                    </a>
                </div>
            </form>
        </div>

        <!-- Format Guidelines Card -->
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
                            <td><code>title</code></td>
                            <td><span class="badge bg-danger">REQUIRED</span></td>
                            <td>Listing name in English (e.g. Saran Hospital)</td>
                        </tr>
                        <tr>
                            <td><code>mobile</code></td>
                            <td><span class="badge bg-danger">REQUIRED</span></td>
                            <td>10-digit mobile or phone number</td>
                        </tr>
                        <tr>
                            <td><code>hindi_title</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>Listing name in Hindi (हिंदी नाम)</td>
                        </tr>
                        <tr>
                            <td><code>entity_type</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td><code>BUSINESS</code>, <code>PROFESSIONAL</code>, <code>HEALTHCARE</code>, <code>GOVT_OFFICE</code>, <code>SCHOOL_COLLEGE</code>, <code>EMERGENCY</code>, <code>BANK</code>, <code>HOTEL</code></td>
                        </tr>
                        <tr>
                            <td><code>category_name</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>Category name (e.g. Health & Medical) or Category ID</td>
                        </tr>
                        <tr>
                            <td><code>subcategory_name</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>Subcategory name (e.g. Doctors & Clinics) or Subcategory ID</td>
                        </tr>
                        <tr>
                            <td><code>block_name</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>Block name (e.g. Chapra Sadar, Marhaura, Sonpur) or Block ID</td>
                        </tr>
                        <tr>
                            <td><code>whatsapp</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>WhatsApp phone number</td>
                        </tr>
                        <tr>
                            <td><code>contact_person</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>Owner / Doctor / Principal name</td>
                        </tr>
                        <tr>
                            <td><code>address</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>Street / Area address in Saran district</td>
                        </tr>
                        <tr>
                            <td><code>pincode</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td>6-digit PIN code (Defaults to 841301)</td>
                        </tr>
                        <tr>
                            <td><code>is_verified</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td><code>YES</code> or <code>NO</code></td>
                        </tr>
                        <tr>
                            <td><code>is_featured</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td><code>YES</code> or <code>NO</code></td>
                        </tr>
                        <tr>
                            <td><code>status</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td><code>ACTIVE</code>, <code>PENDING</code>, or <code>REJECTED</code></td>
                        </tr>
                        <tr>
                            <td><code>plan_type</code></td>
                            <td><span class="badge bg-secondary">Optional</span></td>
                            <td><code>FREE</code>, <code>GOLD</code>, or <code>PLATINUM</code></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Reference Sidebar (4 Columns) -->
    <div class="col-12 col-lg-4">
        <!-- Categories Reference -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-grid text-primary me-2"></i>Valid Categories in System</h6>
            <div class="d-flex flex-wrap gap-1" style="max-height: 250px; overflow-y: auto;">
                <?php foreach ($categories_list as $cat): ?>
                    <span class="badge bg-light text-dark border small p-2"><?php echo sanitizeInput($cat['name']); ?> <strong class="text-primary">(ID: #<?php echo $cat['id']; ?>)</strong></span>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Subcategories Reference -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-diagram-3 text-primary me-2"></i>Valid Subcategories in System</h6>
            <div class="d-flex flex-wrap gap-1" style="max-height: 250px; overflow-y: auto;">
                <?php foreach ($subcategories_list as $sub): ?>
                    <span class="badge bg-light text-dark border small p-2">
                        <?php echo sanitizeInput($sub['name']); ?>
                        <strong class="text-primary">(Sub ID: #<?php echo $sub['id']; ?>)</strong>
                        <span class="text-muted small">(Cat ID: #<?php echo $sub['category_id']; ?>)</span>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Blocks Reference -->
        <div class="card border-0 shadow-sm rounded-3 p-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-geo-alt text-primary me-2"></i>20 Blocks in Saran District</h6>
            <div class="d-flex flex-wrap gap-1" style="max-height: 300px; overflow-y: auto;">
                <?php foreach ($blocks_list as $blk): ?>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle small p-2"><?php echo sanitizeInput($blk['block_name']); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

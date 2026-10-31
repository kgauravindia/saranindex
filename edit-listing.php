<?php
require_once __DIR__ . '/includes/functions.php';

if (!isUserLoggedIn()) {
    header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$currentUser = getLoggedInUser();

$listing_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($listing_id <= 0) {
    header("Location: dashboard.php");
    exit;
}

$listing = getListingById($listing_id);
if (!$listing || $listing['user_id'] != $currentUser['id']) {
    header("Location: dashboard.php");
    exit;
}

$page_title = "Edit Listing – Saran Index";
require_once __DIR__ . '/includes/header.php';

$blocks = getBlocks();
$categories = getCategories();
$success_msg = false;
$error_msg = false;

// Pre-fill values from $listing if not POSTed
$title = $_POST['title'] ?? $listing['title'];
$hindi_title = $_POST['hindi_title'] ?? $listing['hindi_title'];
$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : $listing['category_id'];
$subcategory_id = !empty($_POST['subcategory_id']) ? intval($_POST['subcategory_id']) : $listing['subcategory_id'];
$block_id = isset($_POST['block_id']) ? intval($_POST['block_id']) : $listing['block_id'];
$contact_person = $_POST['contact_person'] ?? $listing['contact_person'];
$mobile = $_POST['mobile'] ?? $listing['mobile'];
$mobile_visibility = $_POST['mobile_visibility'] ?? ($listing['mobile_visibility'] ?? 'PUBLIC');
$whatsapp = $_POST['whatsapp'] ?? $listing['whatsapp'];
$email = $_POST['email'] ?? $listing['email'];
$address = $_POST['address'] ?? $listing['address'];
$pincode = $_POST['pincode'] ?? $listing['pincode'];
$services = $_POST['services'] ?? ($listing['services'] ?? '');
$products = $_POST['products'] ?? ($listing['products'] ?? '');
$gst_no = $_POST['gst_no'] ?? ($listing['gst_no'] ?? '');
$udyam_no = $_POST['udyam_no'] ?? ($listing['udyam_no'] ?? '');
$cin_no = $_POST['cin_no'] ?? ($listing['cin_no'] ?? '');
$local_reg_no = $_POST['local_reg_no'] ?? ($listing['local_reg_no'] ?? '');
$description = $_POST['description'] ?? ($listing['description'] ?? '');

// Determine mauja_code for pre-filling
$db = getDB();
$listing_mauja_code = '';
if (!empty($listing['village_id'])) {
    $stmtM = $db->prepare("SELECT mauja_code FROM halka WHERE id = :id1 OR mauja_code = :id2 LIMIT 1");
    $stmtM->execute(['id1' => $listing['village_id'], 'id2' => $listing['village_id']]);

    $h = $stmtM->fetch(PDO::FETCH_ASSOC);
    if ($h) {
        $listing_mauja_code = $h['mauja_code'];
    } else {
        $vInfo = getCensusVillageByCodeOrId($listing['village_id']);
        if ($vInfo) {
            $listing_mauja_code = $vInfo['town_village_code'];
        }
    }
}
$mauja_code = isset($_POST['mauja_code']) ? sanitizeInput($_POST['mauja_code']) : (isset($_POST['census_village_code']) ? sanitizeInput($_POST['census_village_code']) : $listing_mauja_code);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $hindi_title = sanitizeInput($_POST['hindi_title'] ?? '');
    $contact_person = sanitizeInput($_POST['contact_person'] ?? '');
    $mobile = sanitizeInput($_POST['mobile']);
    $mobile_visibility = sanitizeInput($_POST['mobile_visibility'] ?? 'PUBLIC');
    $whatsapp = sanitizeInput($_POST['whatsapp'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $address = sanitizeInput($_POST['address']);
    $pincode = sanitizeInput($_POST['pincode'] ?? '');
    $services = sanitizeInput($_POST['services'] ?? '');
    $products = sanitizeInput($_POST['products'] ?? '');
    $gst_no = sanitizeInput($_POST['gst_no'] ?? '');
    $udyam_no = sanitizeInput($_POST['udyam_no'] ?? '');
    $cin_no = sanitizeInput($_POST['cin_no'] ?? '');
    $local_reg_no = sanitizeInput($_POST['local_reg_no'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');

    if (empty($title) || empty($mobile) || $category_id <= 0 || $block_id <= 0) {
        $error_msg = "Please fill in all required fields marked with * (Title, Mobile, Category, and Block)";
    } else {
        // Duplicate Entry Validation (Title + Mobile) excluding current listing ID
        $duplicate = checkDuplicateListing($title, $mobile, $listing_id);
        if ($duplicate) {
            $error_msg = "Duplicate Listing Detected: Another listing with the title '" . htmlspecialchars($title) . "' and mobile number '" . htmlspecialchars($mobile) . "' already exists in the directory (Listing ID #" . $duplicate['id'] . ").";
        } else {
            $db = getDB();
            if ($db) {
                try {
                    $base_slug = slugify($title);
                    $slug = $base_slug . '-' . rand(100, 999);
                
                    $village_id_val = 0;
                    if (!empty($mauja_code)) {
                        $stmtM = $db->prepare("SELECT * FROM halka WHERE mauja_code = :mcode LIMIT 1");
                        $stmtM->execute(['mcode' => $mauja_code]);
                        $maujaInfo = $stmtM->fetch(PDO::FETCH_ASSOC);
                        if ($maujaInfo) {
                            $village_id_val = intval($maujaInfo['id']);
                            $mEngTitle = !empty($maujaInfo['mauja_english']) ? $maujaInfo['mauja_english'] : $maujaInfo['mauja_name'];
                            if (empty($address)) {
                                $address = "Mauja: " . $mEngTitle . " (" . $maujaInfo['mauja_name'] . ", Code: " . $maujaInfo['mauja_code'] . ")";
                            }
                        } else {
                            $vInfo = getCensusVillageByCodeOrId($mauja_code);
                            if ($vInfo) {
                                $village_id_val = intval($vInfo['id']);
                                if (empty($address)) {
                                    $address = "Village: " . $vInfo['name'] . " (Code: " . $vInfo['town_village_code'] . ")";
                                }
                            }
                        }
                    }

                    $currentPlan = strtoupper($listing['plan_type'] ?? 'FREE');
                    $planInfo = getPlanDetails($currentPlan);
                    $cover_image_url = $listing['cover_image'] ?? '';

                    // Remove cover photo if requested
                    if (!empty($_POST['remove_cover_image'])) {
                        $cover_image_url = '';
                    }

                    // Handle Cover Photo Upload (For Gold and Platinum plans)
                    if (isset($_FILES['cover_image_file']) && $_FILES['cover_image_file']['error'] === UPLOAD_ERR_OK) {
                        $uploaded = uploadListingImage($_FILES['cover_image_file'], $listing_id);
                        if ($uploaded) {
                            $cover_image_url = $uploaded;
                        }
                    }

                    // Handle Gallery Photos
                    $existing_gallery = [];
                    if (!empty($_POST['existing_gallery']) && is_array($_POST['existing_gallery'])) {
                        $remove_indices = isset($_POST['remove_gallery']) && is_array($_POST['remove_gallery']) ? $_POST['remove_gallery'] : [];
                        foreach ($_POST['existing_gallery'] as $idx => $gUrl) {
                            $gUrl = sanitizeInput($gUrl);
                            if (!empty($gUrl) && !in_array($idx, $remove_indices)) {
                                $existing_gallery[] = $gUrl;
                            }
                        }
                    }

                    if (isset($_FILES['gallery_files']) && is_array($_FILES['gallery_files']['name'])) {
                        $maxG = ($currentPlan === 'PLATINUM') ? 5 : (($currentPlan === 'GOLD') ? 2 : 0);
                        $fileCount = count($_FILES['gallery_files']['name']);
                        for ($i = 0; $i < $fileCount && count($existing_gallery) < $maxG; $i++) {
                            if ($_FILES['gallery_files']['error'][$i] === UPLOAD_ERR_OK) {
                                $singleFile = [
                                    'name' => $_FILES['gallery_files']['name'][$i],
                                    'type' => $_FILES['gallery_files']['type'][$i],
                                    'tmp_name' => $_FILES['gallery_files']['tmp_name'][$i],
                                    'error' => $_FILES['gallery_files']['error'][$i],
                                    'size' => $_FILES['gallery_files']['size'][$i],
                                ];
                                $uploadedG = uploadListingImage($singleFile, $listing_id);
                                if ($uploadedG && !in_array($uploadedG, $existing_gallery)) {
                                    $existing_gallery[] = $uploadedG;
                                }
                            }
                        }
                    }
                    $gallery_json = !empty($existing_gallery) ? json_encode(array_values($existing_gallery)) : null;

                    $stmt = $db->prepare("UPDATE listings SET category_id = :cat, subcategory_id = :sub, block_id = :blk, village_id = :vid, title = :title, hindi_title = :htitle, contact_person = :cp, mobile = :mob, mobile_visibility = :mvis, whatsapp = :wa, email = :email, address = :addr, pincode = :pin, services = :srv, products = :prod, gst_no = :gst, udyam_no = :udyam, cin_no = :cin, local_reg_no = :lreg, description = :desc, cover_image = :cover, gallery_images = :gallery WHERE id = :id AND user_id = :uid");
                    $stmt->execute([
                        'cat' => $category_id,
                        'sub' => $subcategory_id,
                        'blk' => $block_id,
                        'vid' => $village_id_val,
                        'title' => $title,
                        'htitle' => $hindi_title,
                        'cp' => $contact_person,
                        'mob' => $mobile,
                        'mvis' => $mobile_visibility,
                        'wa' => $whatsapp,
                        'email' => $email,
                        'addr' => $address,
                        'pin' => $pincode,
                        'srv' => $services,
                        'prod' => $products,
                        'gst' => $gst_no,
                        'udyam' => $udyam_no,
                        'cin' => $cin_no,
                        'lreg' => $local_reg_no,
                        'desc' => $description,
                        'cover' => $cover_image_url,
                        'gallery' => $gallery_json,
                        'id' => $listing_id,
                        'uid' => $currentUser['id']
                    ]);
                    $success_msg = true;
                    // Refresh listing data
                    $stmtR = $db->prepare("SELECT * FROM listings WHERE id = :id");
                    $stmtR->execute(['id' => $listing_id]);
                    $listing = $stmtR->fetch(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    error_log("Listing update failed: " . $e->getMessage());
                    $error_msg = "Database error while updating listing: " . $e->getMessage();
                }
            } else {
                $error_msg = "Database connection failed. Please try again.";
            }
        }
    }
}
?>

<!-- Hero Header -->
<div class="bg-gradient-primary text-white py-4 py-md-5 position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.12) 0%, transparent 60%); pointer-events: none;"></div>
    <div class="container position-relative z-1 text-center">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill me-1"></i>Home</a></li>
                <li class="breadcrumb-item text-white-50">Directory</li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">Add Listing</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill fs-7 shadow-sm">
                <i class="bi bi-star-fill me-1"></i> 100% Free Registration
            </span>
            <span class="badge px-3 py-1.5 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.3);">
                <i class="bi bi-geo-alt-fill me-1"></i> 20 Blocks & 1,764 Villages
            </span>
        </div>

        <h1 class="h2 fw-bold font-heading text-white mb-2">
            Update Your Listing on Saran Index
        </h1>
        <p class="text-white-50 fs-6 mx-auto mb-0" style="max-width: 680px;">
            Reach citizens across Chapra and all 20 blocks of Saran District. Connect your shop, clinic, school, or service instantly.
        </p>

    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            <?php if ($success_msg): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-success-subtle border-start border-4 border-success">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-success text-white p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                            <i class="bi bi-check-lg fs-4"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold text-success mb-1">Listing Updated Successfully!</h5>
                            <p class="text-secondary small mb-3">Thank you for updating your entity on <strong>Saran Index</strong>. Your listing has been updated successfully.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="dashboard.php" class="btn btn-sm btn-success rounded-pill px-3 fw-bold"><i class="bi bi-speedometer2 me-1"></i> Go to Dashboard</a>
                                <a href="dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($error_msg): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3 shadow-sm mb-4 small border-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i><?php echo sanitizeInput($error_msg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                
                <!-- Card Header Banner -->
                <div class="bg-white p-4 p-md-4.5 border-bottom">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bi bi-file-earmark-plus-fill fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold font-heading text-dark mb-0 fs-5">Update Business & Service Entry Form</h4>
                                <p class="text-muted small mb-0">Update the details below to modify your entity on Saran Index</p>
                            </div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill fs-7">
                            <i class="bi bi-shield-check me-1"></i> Free Entry
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="" method="POST" id="addListingForm" enctype="multipart/form-data">
                        
                        <!-- SECTION 1: ENTITY & CATEGORY -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge rounded-circle bg-primary text-white p-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">1</span>
                                <h6 class="fw-bold font-heading text-dark mb-0 fs-6">Entity & Category Details</h6>
                            </div>

                            <div class="row g-3">
                                <!-- Entity Name English -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Entity / Business Name (English) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-building"></i></span>
                                        <input type="text" name="title" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="e.g. Rajendra College, Chapra Diagnostics" required value="<?php echo htmlspecialchars($title); ?>">
                                    </div>
                                </div>

                                <!-- Entity Name Hindi -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Entity Name in Hindi <span class="text-muted fw-normal">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-translate"></i></span>
                                        <input type="text" name="hindi_title" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="e.g. राजेंद्र कॉलेज, छपरा डायग्नोस्टिक्स" value="<?php echo htmlspecialchars($hindi_title); ?>">
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Category Vertical <span class="text-danger">*</span>
                                    </label>
                                    <select name="category_id" id="category_select" class="form-select border-secondary-subtle rounded-3 py-2.5" required>
                                        <option value="">-- Choose Category --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?php echo sanitizeInput($cat['id']); ?>" <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                                                <?php echo sanitizeInput($cat['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Subcategory -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Subcategory <span class="text-muted fw-normal">(Optional)</span>
                                    </label>
                                    <select name="subcategory_id" id="subcategory_select" class="form-select border-secondary-subtle rounded-3 py-2.5">
                                        <option value="">Select Category First</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 2: LOCATION & CENSUS VILLAGE -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge rounded-circle bg-danger text-white p-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">2</span>
                                <h6 class="fw-bold font-heading text-dark mb-0 fs-6">Location & Village Information</h6>
                            </div>

                            <div class="row g-3">
                                <!-- Saran Block -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Select Block <span class="text-danger">*</span>
                                    </label>
                                    <select name="block_id" id="block_select" class="form-select border-secondary-subtle rounded-3 py-2.5" required>
                                        <option value="">-- Choose Block --</option>
                                        <?php foreach ($blocks as $blk): ?>
                                            <option value="<?php echo sanitizeInput($blk['id']); ?>" data-block-name="<?php echo sanitizeInput($blk['block_name']); ?>" <?php echo ($block_id == $blk['id']) ? 'selected' : ''; ?>>
                                                <?php echo sanitizeInput($blk['block_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Revenue Mauja Selection -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Select Revenue Mauja (Name & Code) <span class="text-muted fw-normal">(Optional)</span>
                                    </label>
                                    <select name="mauja_code" id="village_select" class="form-select border-secondary-subtle rounded-3 py-2.5">
                                        <option value="">Choose Block First</option>
                                    </select>
                                </div>

                                <!-- Full Address -->
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Full Address <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="address" id="address_input" class="form-control border-secondary-subtle rounded-3" rows="2.5" placeholder="Building, Street, Landmark, Panchayat / Ward (e.g. Near Thana Chowk, Main Road)" required><?php echo htmlspecialchars($address); ?></textarea>
                                </div>

                                <!-- PIN Code -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1 d-flex align-items-center justify-content-between">
                                        <span>PIN Code</span>
                                        <span id="pinLookupStatus" class="text-muted small" style="font-size: 0.72rem;"></span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="pincode" id="pincode_input" class="form-control border-secondary-subtle font-monospace fw-bold py-2.5" placeholder="841301" maxlength="6" value="<?php echo htmlspecialchars($pincode); ?>">
                                        <button class="btn btn-outline-secondary border-secondary-subtle" type="button" id="btnPincodeLookup" title="Lookup PIN Code API">
                                            <i class="bi bi-geo-alt-fill text-primary" id="pinIcon"></i>
                                            <span class="spinner-border spinner-border-sm text-primary d-none" id="pinSpinner"></span>
                                        </button>
                                    </div>
                                    <div id="pincodeFeedback" class="mt-1.5" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 3: CONTACT INFORMATION -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge rounded-circle bg-success text-white p-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">3</span>
                                <h6 class="fw-bold font-heading text-dark mb-0 fs-6">Contact & Representative Details</h6>
                            </div>

                            <div class="row g-3">
                                <!-- Contact Person -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Contact Person Name
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-person"></i></span>
                                        <input type="text" name="contact_person" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="Owner, Principal, or Manager Name" value="<?php echo htmlspecialchars($contact_person); ?>">
                                    </div>
                                </div>

                                <!-- Mobile Number -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Mobile Number <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-primary"><i class="bi bi-phone-fill"></i></span>
                                        <input type="tel" name="mobile" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="10-digit Mobile Number" required maxlength="10" value="<?php echo htmlspecialchars($mobile); ?>">
                                    </div>
                                </div>

                                <!-- WhatsApp Number -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        WhatsApp Number <span class="text-muted fw-normal">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-success"><i class="bi bi-whatsapp"></i></span>
                                        <input type="tel" name="whatsapp" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="10-digit WhatsApp Number" maxlength="10" value="<?php echo htmlspecialchars($whatsapp); ?>">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Email Address <span class="text-muted fw-normal">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="contact@example.com" value="<?php echo htmlspecialchars($email); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 4: PRODUCTS, SERVICES & REGISTRATION NUMBERS -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge rounded-circle bg-warning text-dark p-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">4</span>
                                <h6 class="fw-bold font-heading text-dark mb-0 fs-6">Products, Services & Legal Registrations</h6>
                            </div>

                            <div class="row g-3">
                                <!-- Services Offered -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Key Services / Facilities Offered
                                    </label>
                                    <input type="text" name="services" class="form-control border-secondary-subtle rounded-3 py-2.5" placeholder="e.g. OPD, ICU, 24x7 Ambulance, Consultation (comma separated)" value="<?php echo htmlspecialchars($services); ?>">
                                </div>

                                <!-- Products Sold -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Products / Items Offered
                                    </label>
                                    <input type="text" name="products" class="form-control border-secondary-subtle rounded-3 py-2.5" placeholder="e.g. Medicines, Surgical Items, Hardware Goods (comma separated)" value="<?php echo htmlspecialchars($products); ?>">
                                </div>

                                <!-- Business Registration Numbers Box -->
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded-3 border border-secondary-subtle">
                                        <div class="fw-bold text-dark fs-7 text-uppercase mb-2">
                                            <i class="bi bi-shield-check text-success me-1"></i> Business Registration & Govt Numbers <span class="text-muted fw-normal">(Optional)</span>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-6 col-lg-3">
                                                <label class="form-label fs-8 text-muted mb-1">GSTIN / GST No.</label>
                                                <input type="text" name="gst_no" class="form-control form-control-sm text-uppercase font-monospace" placeholder="10AAAAA0000A1Z5" value="<?php echo htmlspecialchars($gst_no); ?>">
                                            </div>
                                            <div class="col-md-6 col-lg-3">
                                                <label class="form-label fs-8 text-muted mb-1">Udyam / Udyog Aadhaar</label>
                                                <input type="text" name="udyam_no" class="form-control form-control-sm text-uppercase font-monospace" placeholder="UDYAM-BR-28-001234" value="<?php echo htmlspecialchars($udyam_no); ?>">
                                            </div>
                                            <div class="col-md-6 col-lg-3">
                                                <label class="form-label fs-8 text-muted mb-1">CIN / Corp. Reg. No.</label>
                                                <input type="text" name="cin_no" class="form-control form-control-sm text-uppercase font-monospace" placeholder="U72900BR2017PTC0" value="<?php echo htmlspecialchars($cin_no); ?>">
                                            </div>
                                            <div class="col-md-6 col-lg-3">
                                                <label class="form-label fs-8 text-muted mb-1">Local Trade License No.</label>
                                                <input type="text" name="local_reg_no" class="form-control form-control-sm" placeholder="License / Reg #12345" value="<?php echo htmlspecialchars($local_reg_no); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        About & Working Hours
                                    </label>
                                    <textarea name="description" class="form-control border-secondary-subtle rounded-3" rows="3" placeholder="Enter brief overview, specialization, business timings, or key highlights..."><?php echo htmlspecialchars($description); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 5: PHOTOS & MEDIA MANAGEMENT -->
                        <?php
                        $listingPlan = strtoupper($listing['plan_type'] ?? 'FREE');
                        $listingPlanInfo = getPlanDetails($listingPlan);
                        $currentCover = $listing['cover_image'] ?? '';
                        $currentGallery = [];
                        if (!empty($listing['gallery_images'])) {
                            $currentGallery = is_array($listing['gallery_images']) ? $listing['gallery_images'] : json_decode($listing['gallery_images'], true);
                            if (!is_array($currentGallery)) {
                                $currentGallery = array_filter(array_map('trim', explode(',', $listing['gallery_images'])));
                            }
                        }
                        ?>
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">5</span>
                                    <h5 class="fw-bold font-heading text-dark mb-0">Business Photos & Media</h5>
                                </div>
                                <div>
                                    <span class="badge <?php echo $listingPlanInfo['badge_class']; ?> px-3 py-1.5 rounded-pill fw-semibold">
                                        <?php echo $listingPlanInfo['name']; ?> (Limit: <?php echo $listingPlanInfo['max_photos']; ?> Photos)
                                    </span>
                                </div>
                            </div>

                            <?php if ($listingPlan === 'FREE'): ?>
                                <!-- Free Plan Upgrade Box -->
                                <div class="card border border-warning-subtle bg-warning-subtle rounded-4 p-4 mb-3">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                            <i class="bi bi-camera-fill fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold text-dark mb-1">Photo Showcase is exclusive to Gold & Platinum Members</h6>
                                            <p class="text-dark small mb-3">
                                                Your listing is currently on the <strong>Basic Free Plan</strong> (0 Photos allowed). Upgrade to <strong>Gold Business</strong> (Up to 3 Photos, Verified Badge) or <strong>VIP Platinum</strong> (Up to 6 Photos, Top Featured) to upload and showcase your facility photos to visitors across Saran.
                                            </p>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="pay.php?listing_id=<?php echo $listing['id']; ?>&plan=GOLD" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-xs">
                                                    <i class="bi bi-rocket-takeoff-fill me-1"></i>Upgrade to Gold (₹499/yr)
                                                </a>
                                                <a href="pay.php?listing_id=<?php echo $listing['id']; ?>&plan=PLATINUM" class="btn btn-warning text-dark btn-sm rounded-pill px-4 fw-bold shadow-xs">
                                                    <i class="bi bi-crown-fill me-1"></i>Upgrade to VIP Platinum (₹1,499/yr)
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Gold & Platinum Plan Photo Management -->
                                <div class="alert alert-info border border-info-subtle rounded-3 p-3 mb-3 small">
                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-info-circle-fill text-info me-1"></i>Recommended Photo Guidelines:</div>
                                    <ul class="mb-0 ps-3 text-secondary" style="line-height: 1.5;">
                                        <li><strong>Dimensions:</strong> 1200 × 800 px (3:2 Landscape) or 800 × 600 px (4:3). Minimum 600 × 400 px.</li>
                                        <li><strong>File Size:</strong> Under 2 MB per image (Max 5 MB). High resolution photos are auto-optimized.</li>
                                        <li><strong>Supported Formats:</strong> JPG, JPEG, PNG, WEBP.</li>
                                        <li><strong>Allowed Photos:</strong> <?php echo $listingPlanInfo['max_photos']; ?> Photos (1 Cover + <?php echo $listingPlanInfo['max_photos'] - 1; ?> Gallery Photos).</li>
                                    </ul>
                                </div>

                                <div class="row g-3">
                                    <!-- Main Cover Photo -->
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-4 border h-100">
                                            <label class="form-label small fw-bold text-dark mb-1 d-block">
                                                <i class="bi bi-card-image me-1 text-primary"></i>Main Cover Photo
                                            </label>
                                            <?php if (!empty($currentCover)): ?>
                                                <div class="mb-2 text-center position-relative">
                                                    <img src="<?php echo sanitizeInput($currentCover); ?>" alt="Cover Photo" class="rounded-3 border shadow-xs object-fit-cover w-100" style="max-height: 130px;" onerror="this.src='assets/img/logo.png';">
                                                    <div class="form-check mt-1 text-start">
                                                        <input class="form-check-input" type="checkbox" id="remove_cover_image" name="remove_cover_image" value="1">
                                                        <label class="form-check-label text-danger small fw-semibold" for="remove_cover_image">
                                                            <i class="bi bi-trash3 me-1"></i>Remove current cover photo
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" name="cover_image_file" class="form-control rounded-3 form-control-sm mb-1" accept="image/jpeg,image/png,image/webp">
                                            <small class="text-muted d-block" style="font-size: 0.72rem;">Shown as the primary hero image on your public listing profile.</small>
                                        </div>
                                    </div>

                                    <!-- Gallery Photos -->
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-4 border h-100">
                                            <label class="form-label small fw-bold text-dark mb-1 d-block">
                                                <i class="bi bi-images me-1 text-primary"></i>Additional Gallery Photos
                                            </label>

                                            <?php if (!empty($currentGallery)): ?>
                                                <div class="mb-2">
                                                    <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Current Gallery Photos (<?php echo count($currentGallery); ?>):</small>
                                                    <div class="row g-2 mb-2">
                                                        <?php foreach ($currentGallery as $gIdx => $gPath): ?>
                                                            <div class="col-6">
                                                                <div class="border rounded-2 p-1 bg-white text-center shadow-xs">
                                                                    <input type="hidden" name="existing_gallery[<?php echo $gIdx; ?>]" value="<?php echo sanitizeInput($gPath); ?>">
                                                                    <img src="<?php echo sanitizeInput($gPath); ?>" class="rounded object-fit-cover w-100 mb-1" style="height: 60px;" onerror="this.src='assets/img/logo.png';">
                                                                    <div class="form-check text-start ps-4">
                                                                        <input class="form-check-input" type="checkbox" name="remove_gallery[]" value="<?php echo $gIdx; ?>" id="user_rm_g_<?php echo $gIdx; ?>">
                                                                        <label class="form-check-label text-danger" for="user_rm_g_<?php echo $gIdx; ?>" style="font-size: 0.68rem;">Remove</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <input type="file" name="gallery_files[]" class="form-control rounded-3 form-control-sm mb-1" multiple accept="image/jpeg,image/png,image/webp">
                                            <small class="text-muted d-block" style="font-size: 0.72rem;">Upload up to <?php echo $listingPlanInfo['max_photos'] - 1; ?> additional photos.</small>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Info Banner & Submit Button -->
                        <div class="p-3 bg-light rounded-3 mb-4 border border-secondary-subtle text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2 text-muted small">
                                <i class="bi bi-shield-lock-fill text-primary"></i>
                                <span>Your contact information is kept secure and published on Saran Index directory.</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm fs-6 d-flex align-items-center justify-content-center gap-2 transition-all">
                            <span>Update Listing</span>
                            <i class="bi bi-pencil-square"></i>
                        </button>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.form-control:focus, .form-select:focus {
    border-color: var(--primary-light, #3b82f6) !important;
    box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.15) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const catSelect = document.getElementById('category_select');
    const subSelect = document.getElementById('subcategory_select');
    const blockSelect = document.getElementById('block_select');
    const villageSelect = document.getElementById('village_select');

    const preSelectedSubcategory = "<?php echo $subcategory_id; ?>";
    const preSelectedVillage = "<?php echo $mauja_code; ?>";

    let isInitialLoad = true;

    if (catSelect && subSelect) {
        catSelect.addEventListener('change', function() {
            const catId = this.value;
            subSelect.innerHTML = '<option value="">Loading Subcategories...</option>';
            if (!catId) {
                subSelect.innerHTML = '<option value="">Select Category First</option>';
                return;
            }

            fetch(`${BASE_URL}api/subcategories_api.php?category_id=${catId}`)
                .then(response => response.json())
                .then(data => {
                    subSelect.innerHTML = '<option value="">Choose Subcategory (Optional)</option>';
                    const subList = Array.isArray(data) ? data : (data.subcategories || []);
                    if (subList.length > 0) {
                        subList.forEach(sub => {
                            const opt = document.createElement('option');
                            opt.value = sub.id;
                            opt.textContent = sub.name + (sub.hindi_name ? ' (' + sub.hindi_name + ')' : '');
                            if (isInitialLoad && preSelectedSubcategory == sub.id) {
                                opt.selected = true;
                            }
                            subSelect.appendChild(opt);
                        });
                    }
                })
                .catch(() => {
                    subSelect.innerHTML = '<option value="">All Subcategories</option>';
                });
        });
    }

    if (blockSelect && villageSelect) {
        blockSelect.addEventListener('change', function() {
            const blockId = this.value;
            villageSelect.innerHTML = '<option value="">Loading Revenue Maujas...</option>';
            if (!blockId) {
                villageSelect.innerHTML = '<option value="">Choose Block First</option>';
                return;
            }

            fetch(`${BASE_URL}api/villages_api.php?block_id=${blockId}`)
                .then(response => response.json())
                .then(data => {
                    villageSelect.innerHTML = '<option value="">-- Select Revenue Mauja (Optional) --</option>';
                    const villageList = Array.isArray(data) ? data : (data.villages || []);
                    if (villageList.length > 0) {
                        villageList.forEach(v => {
                            const opt = document.createElement('option');
                            const code = v.mauja_code || v.code;
                            opt.value = code;
                            opt.textContent = v.display_name || v.name || ('Mauja ' + code);
                            if (isInitialLoad && preSelectedVillage == code) {
                                opt.selected = true;
                            }
                            villageSelect.appendChild(opt);
                        });
                    } else {
                        villageSelect.innerHTML = '<option value="">No Maujas found for this block</option>';
                    }
                })
                .catch(() => {
                    villageSelect.innerHTML = '<option value="">Select Revenue Mauja (Optional)</option>';
                });
        });
    }


    if (catSelect && catSelect.value) {
        catSelect.dispatchEvent(new Event('change'));
    }
    if (blockSelect && blockSelect.value) {
        blockSelect.dispatchEvent(new Event('change'));
    }
    
    // After a short delay, disable the initial load flag so subsequent changes are normal
    setTimeout(() => { isInitialLoad = false; }, 1000);

    // Pincode API Auto-lookup & Saran Block matching
    const pinInput = document.getElementById('pincode_input');
    const btnPin = document.getElementById('btnPincodeLookup');
    const pinFeedback = document.getElementById('pincodeFeedback');
    const pinStatus = document.getElementById('pinLookupStatus');
    const pinIcon = document.getElementById('pinIcon');
    const pinSpinner = document.getElementById('pinSpinner');

    async function checkPincode(pin) {
        const clean = pin.replace(/[^0-9]/g, '');
        if (clean.length !== 6) {
            if (pinFeedback) pinFeedback.style.display = 'none';
            if (pinStatus) pinStatus.textContent = '';
            return;
        }

        if (pinIcon) pinIcon.classList.add('d-none');
        if (pinSpinner) pinSpinner.classList.remove('d-none');
        if (pinStatus) pinStatus.textContent = 'Verifying...';

        try {
            const res = await fetch(`${BASE_URL}api/pincode_lookup.php?pincode=${clean}`);
            const data = await res.json();

            if (data.success) {
                let district = data.district || '';
                let state = data.state || '';
                let offices = (data.offices && data.offices.length) ? data.offices.slice(0, 3).join(', ') : '';
                if (data.offices && data.offices.length > 3) offices += ` +${data.offices.length - 3} more`;

                let blockSuggestion = '';
                if (data.matched_blocks && data.matched_blocks.length > 0) {
                    const matched = data.matched_blocks[0];
                    if (blockSelect && (!blockSelect.value || blockSelect.value == '')) {
                        blockSelect.value = matched.id;
                        blockSelect.dispatchEvent(new Event('change'));
                        blockSuggestion = `<div class="mt-1 text-primary fw-bold small"><i class="bi bi-magic me-1"></i> Auto-selected Block: ${matched.block_name}</div>`;
                    }
                }

                if (pinFeedback) {
                    pinFeedback.innerHTML = `
                        <div class="p-2 rounded-2 bg-success-subtle border border-success-subtle text-success-emphasis small">
                            <div class="d-flex align-items-center justify-content-between mb-0.5">
                                <span class="fw-bold"><i class="bi bi-check-circle-fill text-success me-1"></i> ${district}, ${state}</span>
                                <span class="badge bg-success text-white">Verified PIN</span>
                            </div>
                            ${offices ? `<div class="text-muted extra-small" style="font-size: 0.72rem;"><strong>Offices:</strong> ${offices}</div>` : ''}
                            ${blockSuggestion}
                        </div>
                    `;
                    pinFeedback.style.display = 'block';
                }
                if (pinStatus) pinStatus.innerHTML = `<span class="text-success font-monospace">✓ ${district}</span>`;
            } else {
                if (pinFeedback) {
                    pinFeedback.innerHTML = `
                        <div class="p-2 rounded-2 bg-warning-subtle border border-warning-subtle text-dark small">
                            <i class="bi bi-info-circle text-warning me-1"></i> ${data.message || 'No postal records found.'}
                        </div>
                    `;
                    pinFeedback.style.display = 'block';
                }
                if (pinStatus) pinStatus.textContent = '';
            }
        } catch (e) {
            if (pinFeedback) pinFeedback.style.display = 'none';
            if (pinStatus) pinStatus.textContent = '';
        } finally {
            if (pinIcon) pinIcon.classList.remove('d-none');
            if (pinSpinner) pinSpinner.classList.add('d-none');
        }
    }

    if (pinInput) {
        let pinTimer = null;
        pinInput.addEventListener('input', function() {
            clearTimeout(pinTimer);
            pinTimer = setTimeout(() => checkPincode(this.value), 400);
        });
        if (pinInput.value && pinInput.value.length === 6) {
            checkPincode(pinInput.value);
        }
    }

    if (btnPin && pinInput) {
        btnPin.addEventListener('click', function() {
            checkPincode(pinInput.value);
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

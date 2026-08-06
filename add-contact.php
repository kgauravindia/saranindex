<?php
require_once __DIR__ . '/includes/functions.php';

// Check if user is logged in
$currentUser = null;
if (function_exists('isUserLoggedIn') && isUserLoggedIn()) {
    $currentUser = getLoggedInUser();
}

$categories = getCategories();
$blocks = getBlocks();

$success_msg = false;
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title'] ?? '');
    $hindi_title = sanitizeInput($_POST['hindi_title'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $subcategory_id = !empty($_POST['subcategory_id']) ? intval($_POST['subcategory_id']) : null;
    $block_id = !empty($_POST['block_id']) ? intval($_POST['block_id']) : null;
    $mauja_code = sanitizeInput($_POST['mauja_code'] ?? '');
    $contact_person = sanitizeInput($_POST['contact_person'] ?? '');
    $mobile = sanitizeInput($_POST['mobile'] ?? '');
    $whatsapp = sanitizeInput($_POST['whatsapp'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $address = sanitizeInput($_POST['address'] ?? '');
    $pincode = sanitizeInput($_POST['pincode'] ?? '');
    $services = sanitizeInput($_POST['services'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $plan_type = isset($_POST['plan_type']) && in_array($_POST['plan_type'], ['FREE', 'GOLD', 'PLATINUM']) ? $_POST['plan_type'] : 'FREE';

    if (empty($title) || empty($category_id) || empty($mobile)) {
        $error_msg = "Please fill in all mandatory fields (Title, Category, and Mobile Number).";
    } else {
        $db = getDB();
        if ($db) {
            try {
                $base_slug = slugify($title);
                $slug = $base_slug;
                $stmtCheck = $db->prepare("SELECT id FROM listings WHERE slug = :slug LIMIT 1");
                $stmtCheck->execute(['slug' => $slug]);
                if ($stmtCheck->fetch()) {
                    $slug = $base_slug . '-' . rand(100, 999);
                }
                
                // Fetch Mauja details if selected
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

                $is_featured_val = ($plan_type === 'PLATINUM') ? 'YES' : 'NO';
                $is_verified_val = ($plan_type === 'PLATINUM' || $plan_type === 'GOLD') ? 'YES' : 'NO';
                $plan_expires_val = ($plan_type !== 'FREE') ? date('Y-m-d H:i:s', strtotime('+1 year')) : null;

                $stmt = $db->prepare("INSERT INTO listings (user_id, category_id, subcategory_id, block_id, village_id, title, hindi_title, slug, contact_person, mobile, whatsapp, email, address, pincode, services, description, plan_type, plan_expires_at, is_featured, is_verified, status) VALUES (:uid, :cat, :sub, :blk, :vid, :title, :htitle, :slug, :cp, :mob, :wa, :email, :addr, :pin, :srv, :desc, :plan, :plan_exp, :feat, :ver, 'ACTIVE')");
                $stmt->execute([
                    'uid' => $currentUser['id'] ?? null,
                    'cat' => $category_id,
                    'sub' => $subcategory_id,
                    'blk' => $block_id,
                    'vid' => $village_id_val,
                    'title' => $title,
                    'htitle' => $hindi_title,
                    'slug' => $slug,
                    'cp' => $contact_person,
                    'mob' => $mobile,
                    'wa' => $whatsapp,
                    'email' => $email,
                    'addr' => $address,
                    'pin' => $pincode,
                    'srv' => $services,
                    'desc' => $description,
                    'plan' => $plan_type,
                    'plan_exp' => $plan_expires_val,
                    'feat' => $is_featured_val,
                    'ver' => $is_verified_val
                ]);
                $success_msg = true;
            } catch (PDOException $e) {
                error_log("Listing insert failed: " . $e->getMessage());
                $error_msg = "Database error while creating listing: " . $e->getMessage();
            }
        } else {
            $error_msg = "Database connection failed. Please try again.";
        }
    }
}

$page_title = "Add Listing Free – Submit Business in Saran District | Saran Index";
$meta_description = "List your business, clinic, shop, school, or service on Saran Index. Connect with citizens across Chapra and all 20 blocks of Saran District.";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Hero Banner -->
<div class="bg-primary text-white py-4 py-md-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%) !important;">
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
                <i class="bi bi-star-fill me-1"></i> Select Your Preferred Plan
            </span>
            <span class="badge px-3 py-1.5 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.3);">
                <i class="bi bi-geo-alt-fill me-1"></i> 20 Blocks & 1,764 Villages
            </span>
        </div>

        <h1 class="h2 fw-bold font-heading text-white mb-2">
            List Your Business or Entity on Saran Index
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
                            <h5 class="fw-bold text-success mb-1">Listing Submitted Successfully!</h5>
                            <p class="text-secondary small mb-3">Thank you for adding your entity on <strong>Saran Index</strong>. Your listing has been created and will be reviewed by our moderation team shortly.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="dashboard.php" class="btn btn-sm btn-success rounded-pill px-3 fw-bold"><i class="bi bi-speedometer2 me-1"></i> Go to Dashboard</a>
                                <a href="add-contact.php" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold"><i class="bi bi-plus-lg me-1"></i> Add Another Listing</a>
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
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-journal-plus fs-3"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold font-heading text-dark mb-0">Business Directory Registration</h4>
                            <p class="text-muted small mb-0">Fill in the official details of your business or service in Saran District.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-4.5 bg-white">
                    <form action="add-contact.php" method="POST" id="addListingForm">

                        <!-- SECTION 1: BASIC INFORMATION -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">1</span>
                                <h5 class="fw-bold font-heading text-dark mb-0">Basic Details</h5>
                            </div>

                            <div class="row g-3">
                                <!-- Title / Entity Name -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Entity / Business Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-building"></i></span>
                                        <input type="text" name="title" class="form-control border-secondary-subtle rounded-end-3 py-2.5" required>
                                    </div>
                                </div>

                                <!-- Hindi Title -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Entity Name in Hindi <span class="text-muted fw-normal">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-translate"></i></span>
                                        <input type="text" name="hindi_title" class="form-control border-secondary-subtle rounded-end-3 py-2.5">
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
                                            <option value="<?php echo sanitizeInput($cat['id']); ?>">
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
                                <span class="badge bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">2</span>
                                <h5 class="fw-bold font-heading text-dark mb-0">Location & Village Address</h5>
                            </div>

                            <div class="row g-3">
                                <!-- Block -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        CD Block <span class="text-muted fw-normal">(Saran District)</span>
                                    </label>
                                    <select name="block_id" id="block_select" class="form-select border-secondary-subtle rounded-3 py-2.5">
                                        <option value="">-- Select Block --</option>
                                        <?php foreach ($blocks as $blk): ?>
                                            <option value="<?php echo sanitizeInput($blk['id']); ?>">
                                                <?php echo sanitizeInput($blk['block_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Mauja / Census Village -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Mauja / Census Village <span class="text-muted fw-normal">(Searchable)</span>
                                    </label>
                                    <select name="mauja_code" id="village_select" class="form-select border-secondary-subtle rounded-3 py-2.5">
                                        <option value="">Select Block First</option>
                                    </select>
                                </div>

                                <!-- Full Address -->
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Street Address / Landmark
                                    </label>
                                    <input type="text" name="address" class="form-control border-secondary-subtle rounded-3 py-2.5">
                                </div>

                                <!-- Pincode -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Pincode
                                    </label>
                                    <input type="text" name="pincode" class="form-control border-secondary-subtle rounded-3 py-2.5" maxlength="6">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 3: CONTACT & COMMUNICATION -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">3</span>
                                <h5 class="fw-bold font-heading text-dark mb-0">Contact & Communication</h5>
                            </div>

                            <div class="row g-3">
                                <!-- Contact Person -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Contact Person / Owner Name
                                    </label>
                                    <input type="text" name="contact_person" class="form-control border-secondary-subtle rounded-3 py-2.5">
                                </div>

                                <!-- Mobile Number -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Calling Mobile Number <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted">+91</span>
                                        <input type="tel" name="mobile" class="form-control border-secondary-subtle rounded-end-3 py-2.5" required maxlength="10">
                                    </div>
                                </div>

                                <!-- WhatsApp Number -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        WhatsApp Business Number <span class="text-muted fw-normal">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-success"><i class="bi bi-whatsapp"></i></span>
                                        <input type="tel" name="whatsapp" class="form-control border-secondary-subtle rounded-end-3 py-2.5" maxlength="10">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Official Email Address <span class="text-muted fw-normal">(Optional)</span>
                                    </label>
                                    <input type="email" name="email" class="form-control border-secondary-subtle rounded-3 py-2.5">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 4: SERVICES & DESCRIPTION -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">4</span>
                                <h5 class="fw-bold font-heading text-dark mb-0">Services & Overview</h5>
                            </div>

                            <div class="row g-3">
                                <!-- Services -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        Key Services / Facilities Offered
                                    </label>
                                    <input type="text" name="services" class="form-control border-secondary-subtle rounded-3 py-2.5">
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        About & Working Hours
                                    </label>
                                    <textarea name="description" class="form-control border-secondary-subtle rounded-3" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 5: MEMBERSHIP TIER SELECTION -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">5</span>
                                <h5 class="fw-bold font-heading text-dark mb-0">Select Membership Plan</h5>
                            </div>

                            <div class="row g-3">
                                <!-- FREE PLAN -->
                                <div class="col-md-4">
                                    <label class="card h-100 border rounded-4 p-3 cursor-pointer shadow-xs position-relative hover-border-primary">
                                        <input type="radio" name="plan_type" value="FREE" class="form-check-input position-absolute top-0 end-0 m-3" checked>
                                        <div class="fw-bold text-dark fs-6 mb-1">🟢 Basic Free</div>
                                        <div class="display-7 fw-bolder text-primary mb-2">₹0 <small class="fs-7 text-muted fw-normal">/ forever</small></div>
                                        <ul class="list-unstyled small text-secondary mb-0" style="line-height: 1.6;">
                                            <li><i class="bi bi-check2 text-success me-1"></i> Standard Search Rank</li>
                                            <li><i class="bi bi-check2 text-success me-1"></i> Phone Call Button</li>
                                            <li><i class="bi bi-check2 text-success me-1"></i> Basic Address Info</li>
                                        </ul>
                                    </label>
                                </div>

                                <!-- GOLD BUSINESS PLAN -->
                                <div class="col-md-4">
                                    <label class="card h-100 border border-primary rounded-4 p-3 cursor-pointer shadow-sm position-relative bg-primary-subtle border-2">
                                        <input type="radio" name="plan_type" value="GOLD" class="form-check-input position-absolute top-0 end-0 m-3">
                                        <div class="badge bg-primary text-white fw-bold w-auto me-auto mb-1">Recommended</div>
                                        <div class="fw-bold text-primary fs-6 mb-1">🔵 Gold Business</div>
                                        <div class="display-7 fw-bolder text-primary mb-2">₹499 <small class="fs-7 text-muted fw-normal">/ year</small></div>
                                        <ul class="list-unstyled small text-dark mb-0" style="line-height: 1.6;">
                                            <li><i class="bi bi-check-circle-fill text-primary me-1"></i> <strong>Top Priority Search Rank</strong></li>
                                            <li><i class="bi bi-check-circle-fill text-primary me-1"></i> <strong>Green Verified Badge</strong></li>
                                            <li><i class="bi bi-check-circle-fill text-primary me-1"></i> Direct WhatsApp Button</li>
                                            <li><i class="bi bi-check-circle-fill text-primary me-1"></i> Up to 3 Business Photos</li>
                                        </ul>
                                    </label>
                                </div>

                                <!-- PLATINUM VIP PLAN -->
                                <div class="col-md-4">
                                    <label class="card h-100 border border-warning rounded-4 p-3 cursor-pointer shadow-sm position-relative bg-warning-subtle border-2">
                                        <input type="radio" name="plan_type" value="PLATINUM" class="form-check-input position-absolute top-0 end-0 m-3">
                                        <div class="badge bg-warning text-dark fw-bold w-auto me-auto mb-1">Best Visibility</div>
                                        <div class="fw-bold text-dark fs-6 mb-1">👑 VIP Platinum</div>
                                        <div class="display-7 fw-bolder text-dark mb-2">₹1,499 <small class="fs-7 text-muted fw-normal">/ year</small></div>
                                        <ul class="list-unstyled small text-dark mb-0" style="line-height: 1.6;">
                                            <li><i class="bi bi-crown-fill text-warning me-1"></i> <strong>Top Featured Spot</strong></li>
                                            <li><i class="bi bi-crown-fill text-warning me-1"></i> <strong>Gold VIP Verified Badge</strong></li>
                                            <li><i class="bi bi-crown-fill text-warning me-1"></i> Call + WhatsApp + Booking</li>
                                            <li><i class="bi bi-crown-fill text-warning me-1"></i> Up to 6 Business Photos</li>
                                        </ul>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <!-- Info Banner & Submit Button -->
                        <div class="p-3 bg-light rounded-3 mb-4 border border-secondary-subtle text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2 text-muted small">
                                <i class="bi bi-shield-lock-fill text-primary"></i>
                                <span>Your contact information is kept secure and published on Saran Index directory.</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm fs-6 d-flex align-items-center justify-content-center gap-2 transition-all">
                            <span>Register Directory Listing</span>
                            <i class="bi bi-rocket-takeoff-fill"></i>
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
.cursor-pointer {
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const catSelect = document.getElementById('category_select');
    const subSelect = document.getElementById('subcategory_select');
    const blockSelect = document.getElementById('block_select');
    const villageSelect = document.getElementById('village_select');

    if (catSelect && subSelect) {
        catSelect.addEventListener('change', function() {
            const catId = this.value;
            subSelect.innerHTML = '<option value="">Loading Subcategories...</option>';
            if (!catId) {
                subSelect.innerHTML = '<option value="">Select Category First</option>';
                return;
            }

            fetch('api/subcategories_api.php?category_id=' + encodeURIComponent(catId))
                .then(res => res.json())
                .then(data => {
                    const subList = Array.isArray(data) ? data : (data.subcategories || []);
                    if (subList.length > 0) {
                        const profList = subList.filter(s => s.type === 'PROFESSIONAL' || !s.type || s.type !== 'BUSINESS');
                        const bizList = subList.filter(s => s.type === 'BUSINESS');

                        let html = '<option value="">-- Choose Subcategory --</option>';
                        if (profList.length > 0) {
                            html += '<optgroup label="👨‍💼 Professional Services & Skilled Personnel">';
                            profList.forEach(sub => {
                                let displayName = sub.name + (sub.hindi_name ? ' (' + sub.hindi_name + ')' : '');
                                html += `<option value="${sub.id}">${displayName}</option>`;
                            });
                            html += '</optgroup>';
                        }
                        if (bizList.length > 0) {
                            html += '<optgroup label="🏪 Businesses & Establishments">';
                            bizList.forEach(sub => {
                                let displayName = sub.name + (sub.hindi_name ? ' (' + sub.hindi_name + ')' : '');
                                html += `<option value="${sub.id}">${displayName}</option>`;
                            });
                            html += '</optgroup>';
                        }
                        subSelect.innerHTML = html;
                    } else {

                        subSelect.innerHTML = '<option value="">No Subcategories Available</option>';
                    }
                })
                .catch(() => {
                    subSelect.innerHTML = '<option value="">Error Loading Subcategories</option>';
                });
        });
    }

    if (blockSelect && villageSelect) {
        blockSelect.addEventListener('change', function() {
            const blockId = this.value;
            villageSelect.innerHTML = '<option value="">Loading Villages / Mauja...</option>';
            if (!blockId) {
                villageSelect.innerHTML = '<option value="">Select Block First</option>';
                return;
            }

            fetch('api/villages_api.php?block_id=' + encodeURIComponent(blockId))
                .then(res => res.json())
                .then(data => {
                    const villageList = Array.isArray(data) ? data : (data.villages || []);
                    if (villageList.length > 0) {
                        let html = '<option value="">-- Choose Mauja / Village --</option>';
                        villageList.forEach(v => {
                            let code = v.mauja_code || v.code || '';
                            let displayName = v.display_name || v.name || 'Village ' + code;
                            html += `<option value="${code}">${displayName}</option>`;
                        });
                        villageSelect.innerHTML = html;
                    } else {
                        villageSelect.innerHTML = '<option value="">No Villages Found</option>';
                    }
                })
                .catch(() => {
                    villageSelect.innerHTML = '<option value="">Error Loading Villages</option>';
                });
        });
    }
});
</script>


<?php require_once __DIR__ . '/includes/footer.php'; ?>

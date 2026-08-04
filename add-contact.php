<?php
require_once __DIR__ . '/includes/functions.php';

if (!isUserLoggedIn()) {
    header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$currentUser = getLoggedInUser();

$page_title = "Add Free Listing – Saran Index";
require_once __DIR__ . '/includes/header.php';

$blocks = getBlocks();
$categories = getCategories();
$success_msg = false;
$error_msg = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? sanitizeInput($_POST['title']) : '';
    $hindi_title = isset($_POST['hindi_title']) ? sanitizeInput($_POST['hindi_title']) : '';
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $subcategory_id = !empty($_POST['subcategory_id']) ? intval($_POST['subcategory_id']) : null;
    $block_id = isset($_POST['block_id']) ? intval($_POST['block_id']) : 0;
    $census_village_code = isset($_POST['census_village_code']) ? sanitizeInput($_POST['census_village_code']) : '';
    $contact_person = isset($_POST['contact_person']) ? sanitizeInput($_POST['contact_person']) : '';
    $mobile = isset($_POST['mobile']) ? sanitizeInput($_POST['mobile']) : '';
    $whatsapp = isset($_POST['whatsapp']) ? sanitizeInput($_POST['whatsapp']) : '';
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
    $address = isset($_POST['address']) ? sanitizeInput($_POST['address']) : '';
    $pincode = isset($_POST['pincode']) ? sanitizeInput($_POST['pincode']) : '';
    $services = isset($_POST['services']) ? sanitizeInput($_POST['services']) : '';
    $description = isset($_POST['description']) ? sanitizeInput($_POST['description']) : '';

    if (!empty($title) && !empty($mobile) && $category_id > 0 && $block_id > 0) {
        $db = getDB();
        if ($db) {
            try {
                $base_slug = slugify($title);
                $slug = $base_slug . '-' . rand(100, 999);
                
                // Fetch village details if selected
                $village_id_val = 0;
                if (!empty($census_village_code)) {
                    $vInfo = getCensusVillageByCodeOrId($census_village_code);
                    if ($vInfo) {
                        $village_id_val = intval($vInfo['id']);
                        if (empty($address)) {
                            $address = "Village: " . $vInfo['name'] . " (Census Code: " . $vInfo['town_village_code'] . ")";
                        }
                    }
                }

                $stmt = $db->prepare("INSERT INTO listings (user_id, category_id, subcategory_id, block_id, village_id, title, hindi_title, slug, contact_person, mobile, whatsapp, email, address, pincode, services, description, is_verified, status) VALUES (:uid, :cat, :sub, :blk, :vid, :title, :htitle, :slug, :cp, :mob, :wa, :email, :addr, :pin, :srv, :desc, 'NO', 'ACTIVE')");
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
                    'desc' => $description
                ]);
                $success_msg = true;
            } catch (PDOException $e) {
                error_log("Listing insert failed: " . $e->getMessage());
                $error_msg = "Database error while creating listing: " . $e->getMessage();
            }
        } else {
            $error_msg = "Database connection failed. Please try again.";
        }
    } else {
        $error_msg = "Please fill in all required fields marked with * (Title, Mobile, Category, and Block)";
    }
}
?>

<!-- Hero Header -->
<div class="bg-gradient-primary text-white py-4 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-2 text-center">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb justify-content-center mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill"></i> Home</a></li>
                <li class="breadcrumb-item text-white-50">Registration</li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">Add Listing</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-center gap-2 mb-2 flex-wrap">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill fs-7">
                <i class="bi bi-star-fill me-1"></i> Free Directory Registration
            </span>
            <span class="badge px-3 py-1 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35);">
                <i class="bi bi-check-circle-fill me-1"></i> 20 Blocks & 1,764 Villages
            </span>
        </div>

        <h1 class="h3 fw-bold font-heading text-white mb-1">
            List Your Business or Entity on Saran Index
        </h1>
        <p class="text-white-50 small mx-auto mb-0" style="max-width: 700px;">
            Connect your shop, clinic, advocate chamber, school, or service with citizens across Saran District (Chapra). Instant submission & verification.
        </p>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <?php if ($success_msg): ?>
                <div class="alert alert-success rounded-3 p-3 shadow-sm mb-4 border-2 border-success">
                    <h5 class="fw-bold alert-heading mb-1"><i class="bi bi-check-circle-fill me-2 text-success"></i>Registration Submitted Successfully!</h5>
                    <p class="mb-0 small">Thank you for listing on <strong>Saran Index</strong>. Our verification team will review your information and activate your profile shortly.</p>
                </div>
            <?php endif; ?>

            <?php if ($error_msg): ?>
                <div class="alert alert-danger rounded-3 p-3 shadow-sm mb-4 small">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo sanitizeInput($error_msg); ?>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border-top border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <div>
                        <h5 class="fw-bold font-heading text-dark mb-0 fs-5">Entity & Business Registration Form</h5>
                    </div>
                    <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1 rounded-pill d-none d-md-inline-block fs-7">
                        <i class="bi bi-shield-check me-1"></i> Verified Entry
                    </span>
                </div>
                
                <form action="" method="POST">
                    <div class="row g-3">
                        
                        <!-- SECTION 1: ENTITY & CATEGORY -->
                        <div class="col-12">
                            <div class="px-3 py-2 bg-light rounded-3 border-start border-3 border-primary">
                                <span class="fw-bold text-dark font-heading small">
                                    <i class="bi bi-shop text-primary me-1"></i> 1. Entity & Category Information
                                </span>
                            </div>
                        </div>

                        <!-- Entity Name English -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                Name in English <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-building"></i></span>
                                <input type="text" name="title" class="form-control bg-light" placeholder="e.g. Rajendra College, Chapra Legal Chamber" required>
                            </div>
                        </div>

                        <!-- Entity Name Hindi -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                Name in Hindi (Optional)
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-translate"></i></span>
                                <input type="text" name="hindi_title" class="form-control bg-light" placeholder="e.g. Rajendra College Chapra, Dr. Kumar Hospital">
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                Category Vertical <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="category_select" class="form-select form-select-sm bg-light" required>
                                <option value="">Choose Category</option>
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
                                Subcategory
                            </label>
                            <select name="subcategory_id" id="subcategory_select" class="form-select form-select-sm bg-light">
                                <option value="">Select Category First</option>
                            </select>
                        </div>

                        <!-- SECTION 2: LOCATION & CENSUS VILLAGE -->
                        <div class="col-12 pt-1">
                            <div class="px-3 py-2 bg-light rounded-3 border-start border-3 border-danger">
                                <span class="fw-bold text-dark font-heading small">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> 2. Location & Census Village Selection
                                </span>
                            </div>
                        </div>

                        <!-- Saran Block -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                Select Block <span class="text-danger">*</span>
                            </label>
                            <select name="block_id" id="block_select" class="form-select form-select-sm bg-light" required>
                                <option value="">Choose Block</option>
                                <?php foreach ($blocks as $blk): ?>
                                    <option value="<?php echo sanitizeInput($blk['id']); ?>" data-block-name="<?php echo sanitizeInput($blk['block_name']); ?>">
                                        <?php echo sanitizeInput($blk['block_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Census 2011 Village Selection -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                Select Census Village (2011 Data)
                            </label>
                            <select name="census_village_code" id="village_select" class="form-select form-select-sm bg-light">
                                <option value="">Choose Block First</option>
                            </select>
                        </div>

                        <!-- Full Address -->
                        <div class="col-md-8">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                Full Address <span class="text-danger">*</span>
                            </label>
                            <textarea name="address" id="address_input" class="form-control form-control-sm bg-light" rows="2" placeholder="Address, Street, Village/Panchayat, Landmark (e.g. Near Thana Chowk, Main Road, Chapra Sadar)" required></textarea>
                        </div>

                        <!-- PIN Code -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                PIN Code
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-postcard"></i></span>
                                <input type="text" name="pincode" class="form-control bg-light" placeholder="e.g. 841301">
                            </div>
                        </div>

                        <!-- SECTION 3: CONTACT INFORMATION -->
                        <div class="col-12 pt-1">
                            <div class="px-3 py-2 bg-light rounded-3 border-start border-3 border-success">
                                <span class="fw-bold text-dark font-heading small">
                                    <i class="bi bi-person-vcard-fill text-success me-1"></i> 3. Contact & Representative Details
                                </span>
                            </div>
                        </div>

                        <!-- Contact Person -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                Contact Person Name
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" name="contact_person" class="form-control bg-light" placeholder="Owner, Principal, or Manager">
                            </div>
                        </div>

                        <!-- Mobile Number -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                Mobile Number <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone-fill text-primary"></i></span>
                                <input type="tel" name="mobile" class="form-control bg-light" placeholder="10-digit Mobile Number" required>
                            </div>
                        </div>

                        <!-- WhatsApp Number -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                WhatsApp Number
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-whatsapp text-success"></i></span>
                                <input type="tel" name="whatsapp" class="form-control bg-light" placeholder="WhatsApp Contact Number">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                Email Address (Optional)
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control bg-light" placeholder="contact@domain.com">
                            </div>
                        </div>

                        <!-- SECTION 4: SERVICES & DESCRIPTION -->
                        <div class="col-12 pt-1">
                            <div class="px-3 py-2 bg-light rounded-3 border-start border-3 border-warning">
                                <span class="fw-bold text-dark font-heading small">
                                    <i class="bi bi-file-text-fill text-warning me-1"></i> 4. Services & Facilities
                                </span>
                            </div>
                        </div>

                        <!-- Services & Facilities -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                Key Services / Facilities Offered
                            </label>
                            <input type="text" name="services" class="form-control form-control-sm bg-light" placeholder="e.g. B.A / B.Sc / B.Com Degree, OPD, ICU, 24x7 Ambulance, Legal Advice (comma separated)">
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                Overview & Working Hours
                            </label>
                            <textarea name="description" class="form-control form-control-sm bg-light" rows="3" placeholder="Enter brief details about courses, specialization, working timing..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-12 pt-2">
                            <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold text-dark shadow-sm py-2.5">
                                <i class="bi bi-rocket-takeoff-fill me-1"></i>Submit Free Registration
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

            fetch(`${BASE_URL}api/subcategories_api.php?category_id=${catId}`)
                .then(response => response.json())
                .then(data => {
                    subSelect.innerHTML = '<option value="">Choose Subcategory (Optional)</option>';
                    if (data && data.length > 0) {
                        data.forEach(sub => {
                            const opt = document.createElement('option');
                            opt.value = sub.id;
                            opt.textContent = sub.name;
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
            villageSelect.innerHTML = '<option value="">Loading Census Villages...</option>';
            if (!blockId) {
                villageSelect.innerHTML = '<option value="">Choose Block First</option>';
                return;
            }

            fetch(`${BASE_URL}api/villages_api.php?block_id=${blockId}`)
                .then(response => response.json())
                .then(data => {
                    villageSelect.innerHTML = '<option value="">Choose Census 2011 Village (Optional)</option>';
                    if (data && data.length > 0) {
                        data.forEach(v => {
                            const opt = document.createElement('option');
                            opt.value = v.code;
                            opt.textContent = `${v.name} - Code: ${v.code}`;
                            villageSelect.appendChild(opt);
                        });
                    } else {
                        villageSelect.innerHTML = '<option value="">No villages found for this block</option>';
                    }
                })
                .catch(() => {
                    villageSelect.innerHTML = '<option value="">Select Census Village (Optional)</option>';
                });
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

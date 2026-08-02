<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = "निःशुल्क लिस्टिंग जोड़ें (पंजीकरण) – सारण इंडेक्स";
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

                $stmt = $db->prepare("INSERT INTO listings (category_id, subcategory_id, block_id, village_id, title, hindi_title, slug, contact_person, mobile, whatsapp, email, address, pincode, services, description, is_verified, status) VALUES (:cat, :sub, :blk, :vid, :title, :htitle, :slug, :cp, :mob, :wa, :email, :addr, :pin, :srv, :desc, 'NO', 'ACTIVE')");
                $stmt->execute([
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
            } catch (PDOException $e) {
                error_log("Listing insert failed: " . $e->getMessage());
            }
        }
        $success_msg = true;
    } else {
        $error_msg = "कृपया * से चिह्नित सभी आवश्यक फ़ील्ड भरें।";
    }
}
?>

<!-- Hero Header -->
<div class="bg-gradient-primary text-white py-4 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-2 text-center">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb justify-content-center mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill"></i> मुख्य पृष्ठ</a></li>
                <li class="breadcrumb-item text-white-50">पंजीकरण</li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">निःशुल्क लिस्टिंग</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-center gap-2 mb-2 flex-wrap">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill fs-7">
                <i class="bi bi-star-fill me-1"></i> निःशुल्क निर्देशिका पंजीकरण
            </span>
            <span class="badge px-3 py-1 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35);">
                <i class="bi bi-check-circle-fill me-1"></i> 20 प्रखंड एवं 1,764 गाँव
            </span>
        </div>

        <h1 class="h3 fw-bold font-heading text-white mb-1">
            सारण इंडेक्स पर अपनी दुकान, संस्था या सेवा जोड़ें
        </h1>
        <p class="text-white-50 small mx-auto mb-0" style="max-width: 700px;">
            अपनी दुकान, क्लिनिक, वकील चैंबर, स्कूल या सेवा को सारण जिले (छपरा) के नागरिकों से जोड़ें। त्वरित पंजीकरण एवं सत्यापन।
        </p>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <?php if ($success_msg): ?>
                <div class="alert alert-success rounded-3 p-3 shadow-sm mb-4 border-2 border-success">
                    <h5 class="fw-bold alert-heading mb-1"><i class="bi bi-check-circle-fill me-2 text-success"></i>पंजीकरण सफलतापूर्वक जमा हुआ!</h5>
                    <p class="mb-0 small">सारण इंडेक्स पर पंजीकरण करने के लिए धन्यवाद। हमारी टीम जल्द ही आपकी जानकारी का सत्यापन करके प्रोफ़ाइल लाइव करेगी।</p>
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
                        <h5 class="fw-bold font-heading text-dark mb-0 fs-5">व्यापार, कॉलेज, अस्पताल, वकील एवं संस्थान पंजीकरण फॉर्म</h5>
                    </div>
                    <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1 rounded-pill d-none d-md-inline-block fs-7">
                        <i class="bi bi-shield-check me-1"></i> सत्यापित प्रविष्टि
                    </span>
                </div>
                
                <form action="add-contact.php" method="POST">
                    <div class="row g-3">
                        
                        <!-- SECTION 1: ENTITY & CATEGORY -->
                        <div class="col-12">
                            <div class="px-3 py-2 bg-light rounded-3 border-start border-3 border-primary">
                                <span class="fw-bold text-dark font-heading small">
                                    <i class="bi bi-shop text-primary me-1"></i> 1. संस्था एवं श्रेणी की जानकारी (Entity Information)
                                </span>
                            </div>
                        </div>

                        <!-- Entity Name English -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                नाम अंग्रेजी में (Name in English) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-building"></i></span>
                                <input type="text" name="title" class="form-control bg-light" placeholder="उदा. Rajendra College, Chapra Legal Chamber" required>
                            </div>
                        </div>

                        <!-- Entity Name Hindi -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                नाम हिंदी में (Name in Hindi - वैकल्पिक)
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-translate"></i></span>
                                <input type="text" name="hindi_title" class="form-control bg-light" placeholder="उदा. राजेंद्र कॉलेज छपरा, डॉ. कुमार अस्पताल">
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                मुख्य श्रेणी (Category Vertical) <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="category_select" class="form-select form-select-sm bg-light" required>
                                <option value="">श्रेणी चुनें (Choose Category)</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo sanitizeInput($cat['id']); ?>">
                                        <?php echo sanitizeInput(!empty($cat['hindi_name']) ? $cat['hindi_name'] . ' (' . $cat['name'] . ')' : $cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Subcategory -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                उप-श्रेणी (Subcategory)
                            </label>
                            <select name="subcategory_id" id="subcategory_select" class="form-select form-select-sm bg-light">
                                <option value="">पहले श्रेणी चुनें (Select Category First)</option>
                            </select>
                        </div>

                        <!-- SECTION 2: LOCATION & CENSUS VILLAGE -->
                        <div class="col-12 pt-1">
                            <div class="px-3 py-2 bg-light rounded-3 border-start border-3 border-danger">
                                <span class="fw-bold text-dark font-heading small">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> 2. स्थान एवं 2011 जनगणना गांव चयन (Location)
                                </span>
                            </div>
                        </div>

                        <!-- Saran Block -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                प्रखंड चुनें (Select Block) <span class="text-danger">*</span>
                            </label>
                            <select name="block_id" id="block_select" class="form-select form-select-sm bg-light" required>
                                <option value="">प्रखंड चुनें (Choose Block)</option>
                                <?php foreach ($blocks as $blk): ?>
                                    <option value="<?php echo sanitizeInput($blk['id']); ?>" data-block-name="<?php echo sanitizeInput($blk['block_name']); ?>">
                                        <?php echo sanitizeInput(!empty($blk['hindi_name']) ? $blk['hindi_name'] . ' (' . $blk['block_name'] . ')' : $blk['block_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Census 2011 Village Selection -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                जनगणना गांव चुनें (2011 डेटा)
                            </label>
                            <select name="census_village_code" id="village_select" class="form-select form-select-sm bg-light">
                                <option value="">पहले प्रखंड चुनें</option>
                            </select>
                        </div>

                        <!-- Full Address -->
                        <div class="col-md-8">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                पूरा पता (Full Address) <span class="text-danger">*</span>
                            </label>
                            <textarea name="address" id="address_input" class="form-control form-control-sm bg-light" rows="2" placeholder="पता, सड़क, गाँव/पंचायत, लैंडमार्क (उदा. थाना चौक के पास, मुख्य मार्ग, छपरा सदर)" required></textarea>
                        </div>

                        <!-- PIN Code -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                पिन कोड (PIN Code)
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-postcard"></i></span>
                                <input type="text" name="pincode" class="form-control bg-light" placeholder="उदा. 841301">
                            </div>
                        </div>

                        <!-- SECTION 3: CONTACT INFORMATION -->
                        <div class="col-12 pt-1">
                            <div class="px-3 py-2 bg-light rounded-3 border-start border-3 border-success">
                                <span class="fw-bold text-dark font-heading small">
                                    <i class="bi bi-person-vcard-fill text-success me-1"></i> 3. संपर्क एवं प्रतिनिधि विवरण (Contact Details)
                                </span>
                            </div>
                        </div>

                        <!-- Contact Person -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                संपर्क व्यक्ति का नाम (Contact Person)
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" name="contact_person" class="form-control bg-light" placeholder="मालिक, प्रिसिपल या प्रबंधक का नाम">
                            </div>
                        </div>

                        <!-- Mobile Number -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                मोबाइल नंबर (Mobile Number) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone-fill text-primary"></i></span>
                                <input type="tel" name="mobile" class="form-control bg-light" placeholder="10-अंकों का मोबाइल नंबर" required>
                            </div>
                        </div>

                        <!-- WhatsApp Number -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                व्हाट्सएप नंबर (WhatsApp Number)
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-whatsapp text-success"></i></span>
                                <input type="tel" name="whatsapp" class="form-control bg-light" placeholder="व्हाट्सएप नंबर">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                ईमेल पता (Email Address - वैकल्पिक)
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
                                    <i class="bi bi-file-text-fill text-warning me-1"></i> 4. सेवा विवरण एवं सुविधाएं (Services)
                                </span>
                            </div>
                        </div>

                        <!-- Services & Facilities -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                मुख्य सुविधाएं एवं सेवाएं (Key Services Offered)
                            </label>
                            <input type="text" name="services" class="form-control form-control-sm bg-light" placeholder="उदा. बी.ए / बी.एससी, ओपीडी, आईसीयू, 24x7 एम्बुलेंस, कानूनी सलाह (कॉमा से अलग करें)">
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                विवरण एवं समय की जानकारी (Overview & Timing)
                            </label>
                            <textarea name="description" class="form-control form-control-sm bg-light" rows="3" placeholder="समय, अनुभव एवं सेवाओं का विस्तृत विवरण दें..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-12 pt-2">
                            <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold text-dark shadow-sm py-2.5">
                                <i class="bi bi-rocket-takeoff-fill me-1"></i>निःशुल्क पंजीकरण जमा करें
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
            subSelect.innerHTML = '<option value="">उप-श्रेणी लोड हो रही है...</option>';
            if (!catId) {
                subSelect.innerHTML = '<option value="">पहले श्रेणी चुनें</option>';
                return;
            }

            fetch(`${BASE_URL}api/subcategories_api.php?category_id=${catId}`)
                .then(response => response.json())
                .then(data => {
                    subSelect.innerHTML = '<option value="">उप-श्रेणी चुनें (वैकल्पिक)</option>';
                    if (data && data.length > 0) {
                        data.forEach(sub => {
                            const opt = document.createElement('option');
                            opt.value = sub.id;
                            opt.textContent = sub.hindi_name ? `${sub.hindi_name} (${sub.name})` : sub.name;
                            subSelect.appendChild(opt);
                        });
                    }
                })
                .catch(() => {
                    subSelect.innerHTML = '<option value="">सभी उप-श्रेणियां</option>';
                });
        });
    }

    if (blockSelect && villageSelect) {
        blockSelect.addEventListener('change', function() {
            const blockId = this.value;
            villageSelect.innerHTML = '<option value="">जनगणना गांव लोड हो रहे हैं...</option>';
            if (!blockId) {
                villageSelect.innerHTML = '<option value="">पहले प्रखंड चुनें</option>';
                return;
            }

            fetch(`${BASE_URL}api/villages_api.php?block_id=${blockId}`)
                .then(response => response.json())
                .then(data => {
                    villageSelect.innerHTML = '<option value="">जनगणना 2011 गांव चुनें (वैकल्पिक)</option>';
                    if (data && data.length > 0) {
                        data.forEach(v => {
                            const opt = document.createElement('option');
                            opt.value = v.code;
                            const hName = v.name_hindi ? ` (${v.name_hindi})` : '';
                            opt.textContent = `${v.name}${hName} - कोड: ${v.code}`;
                            villageSelect.appendChild(opt);
                        });
                    } else {
                        villageSelect.innerHTML = '<option value="">इस प्रखंड के लिए कोई गांव नहीं मिला</option>';
                    }
                })
                .catch(() => {
                    villageSelect.innerHTML = '<option value="">जनगणना गांव चुनें (वैकल्पिक)</option>';
                });
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

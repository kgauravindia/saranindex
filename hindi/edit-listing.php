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

$page_title = "लिस्टिंग अपडेट करें – सारण इंडेक्स";
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
$whatsapp = $_POST['whatsapp'] ?? $listing['whatsapp'];
$email = $_POST['email'] ?? $listing['email'];
$address = $_POST['address'] ?? $listing['address'];
$pincode = $_POST['pincode'] ?? $listing['pincode'];
$services = $_POST['services'] ?? $listing['services'];
$description = $_POST['description'] ?? $listing['description'];

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
    $whatsapp = sanitizeInput($_POST['whatsapp'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $address = sanitizeInput($_POST['address']);
    $pincode = sanitizeInput($_POST['pincode'] ?? '');
    $services = sanitizeInput($_POST['services'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');

    if (!empty($title) && !empty($mobile) && $category_id > 0 && $block_id > 0) {
        $db = getDB();
        if ($db) {
            try {
                $base_slug = slugify($title);
                $slug = $base_slug . '-' . rand(100, 999);
                
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

                $stmt = $db->prepare("UPDATE listings SET category_id = :cat, subcategory_id = :sub, block_id = :blk, village_id = :vid, title = :title, hindi_title = :htitle, contact_person = :cp, mobile = :mob, whatsapp = :wa, email = :email, address = :addr, pincode = :pin, services = :srv, description = :desc WHERE id = :id AND user_id = :uid");
                $stmt->execute([
                    'cat' => $category_id,
                    'sub' => $subcategory_id,
                    'blk' => $block_id,
                    'vid' => $village_id_val,
                    'title' => $title,
                    'htitle' => $hindi_title,
                    'cp' => $contact_person,
                    'mob' => $mobile,
                    'wa' => $whatsapp,
                    'email' => $email,
                    'addr' => $address,
                    'pin' => $pincode,
                    'srv' => $services,
                    'desc' => $description,
                    'id' => $listing_id,
                    'uid' => $currentUser['id']
                ]);
                $success_msg = true;
            } catch (PDOException $e) {
                error_log("Listing update failed: " . $e->getMessage());
                $error_msg = "डेटाबेस में लिस्टिंग अपडेट करते समय त्रुटि: " . $e->getMessage();
            }
        } else {
            $error_msg = "डेटाबेस से संपर्क नहीं हो सका। कृपया पुनः प्रयास करें।";
        }
    } else {
        $error_msg = "कृपया * से चिह्नित सभी आवश्यक फ़ील्ड भरें (नाम, मोबाइल, श्रेणी, एवं प्रखंड)।";
    }
}
?>

<!-- Hero Header -->
<div class="bg-gradient-primary text-white py-4 py-md-5 position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.12) 0%, transparent 60%); pointer-events: none;"></div>
    <div class="container position-relative z-1 text-center">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill me-1"></i>मुख्य पृष्ठ</a></li>
                <li class="breadcrumb-item text-white-50">निर्देशिका</li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">निःशुल्क लिस्टिंग</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill fs-7 shadow-sm">
                <i class="bi bi-star-fill me-1"></i> 100% निःशुल्क पंजीकरण
            </span>
            <span class="badge px-3 py-1.5 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.3);">
                <i class="bi bi-geo-alt-fill me-1"></i> 20 प्रखंड एवं 1,764 गाँव
            </span>
        </div>

        <h1 class="h2 fw-bold font-heading text-white mb-2">
            सारण इंडेक्स पर अपनी लिस्टिंग अपडेट करें
        </h1>
        <p class="text-white-50 fs-6 mx-auto mb-0" style="max-width: 680px;">
            अपनी दुकान, क्लिनिक, स्कूल या सेवा को सारण जिले (छपरा) के नागरिकों से जोड़ें। त्वरित पंजीकरण एवं सत्यापन।
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
                            <h5 class="fw-bold text-success mb-1">लिस्टिंग सफलतापूर्वक अपडेट कर दी गई!</h5>
                            <p class="text-secondary small mb-3">सारण इंडेक्स पर अपनी लिस्टिंग अपडेट करने के लिए धन्यवाद। आपकी लिस्टिंग सफलतापूर्वक अपडेट कर दी गई है।</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="dashboard.php" class="btn btn-sm btn-success rounded-pill px-3 fw-bold"><i class="bi bi-speedometer2 me-1"></i> डैशबोर्ड पर जाएं</a>
                                <a href="dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold"><i class="bi bi-arrow-left me-1"></i> डैशबोर्ड पर वापस जाएं</a>
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
                                <h4 class="fw-bold font-heading text-dark mb-0 fs-5">व्यवसाय और सेवा अपडेट फॉर्म</h4>
                                <p class="text-muted small mb-0">अपनी लिस्टिंग को सारण इंडेक्स पर अपडेट करने के लिए नीचे विवरण बदलें</p>
                            </div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill fs-7">
                            <i class="bi bi-shield-check me-1"></i> निःशुल्क प्रविष्टि
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="" method="POST" id="addListingForm">
                        
                        <!-- SECTION 1: ENTITY & CATEGORY -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge rounded-circle bg-primary text-white p-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">1</span>
                                <h6 class="fw-bold font-heading text-dark mb-0 fs-6">संस्था एवं श्रेणी का विवरण</h6>
                            </div>

                            <div class="row g-3">
                                <!-- Entity Name English -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        संस्था / दुकान का नाम (अंग्रेजी में) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-building"></i></span>
                                        <input type="text" name="title" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="उदा. राजेंद्र कॉलेज, छपरा डायग्नोस्टिक्स" required value="<?php echo htmlspecialchars($title); ?>">
                                    </div>
                                </div>

                                <!-- Entity Name Hindi -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        संस्था का नाम (हिंदी में) <span class="text-muted fw-normal">(ऐच्छिक)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-translate"></i></span>
                                        <input type="text" name="hindi_title" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="उदा. राजेंद्र कॉलेज, छपरा डायग्नोस्टिक्स" value="<?php echo htmlspecialchars($hindi_title); ?>">
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        श्रेणी चुनें <span class="text-danger">*</span>
                                    </label>
                                    <select name="category_id" id="category_select" class="form-select border-secondary-subtle rounded-3 py-2.5" required>
                                        <option value="">-- श्रेणी चुनें --</option>
                                        <?php foreach ($categories as $cat): 
                                            $catLabel = !empty($cat['hindi_name']) ? $cat['hindi_name'] : $cat['name'];
                                        ?>
                                            <option value="<?php echo sanitizeInput($cat['id']); ?>" <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                                                <?php echo sanitizeInput($catLabel); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Subcategory -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        उपश्रेणी <span class="text-muted fw-normal">(ऐच्छिक)</span>
                                    </label>
                                    <select name="subcategory_id" id="subcategory_select" class="form-select border-secondary-subtle rounded-3 py-2.5">
                                        <option value="">पहले श्रेणी चुनें</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 2: LOCATION & CENSUS VILLAGE -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge rounded-circle bg-danger text-white p-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">2</span>
                                <h6 class="fw-bold font-heading text-dark mb-0 fs-6">स्थान एवं गाँव की जानकारी</h6>
                            </div>

                            <div class="row g-3">
                                <!-- Saran Block -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        प्रखंड चुनें <span class="text-danger">*</span>
                                    </label>
                                    <select name="block_id" id="block_select" class="form-select border-secondary-subtle rounded-3 py-2.5" required>
                                        <option value="">-- प्रखंड चुनें --</option>
                                        <?php foreach ($blocks as $blk): 
                                            $bLabel = !empty($blk['hindi_name']) ? $blk['hindi_name'] : (!empty($blk['name_english']) ? $blk['name_english'] : $blk['block_name']);
                                        ?>
                                            <option value="<?php echo sanitizeInput($blk['id']); ?>" data-block-name="<?php echo sanitizeInput($blk['block_name']); ?>" <?php echo ($block_id == $blk['id']) ? 'selected' : ''; ?>>
                                                <?php echo sanitizeInput($bLabel); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Revenue Mauja Selection -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        राजस्व मौजा चुनें (नाम एवं कोड) <span class="text-muted fw-normal">(ऐच्छिक)</span>
                                    </label>
                                    <select name="mauja_code" id="village_select" class="form-select border-secondary-subtle rounded-3 py-2.5">
                                        <option value="">पहले प्रखंड चुनें</option>
                                    </select>
                                </div>

                                <!-- Full Address -->
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        पूरा पता (Address) <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="address" id="address_input" class="form-control border-secondary-subtle rounded-3" rows="2.5" placeholder="भवन, गली, लैंडमार्क, पंचायत/वार्ड (उदा. थाना चौक के पास, मेन रोड)" required><?php echo htmlspecialchars($address); ?></textarea>
                                </div>

                                <!-- PIN Code -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        पिन कोड <span class="text-muted fw-normal">(ऐच्छिक)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-geo"></i></span>
                                        <input type="text" name="pincode" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="उदा. 841301" maxlength="6" value="<?php echo htmlspecialchars($pincode); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 3: CONTACT INFORMATION -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge rounded-circle bg-success text-white p-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">3</span>
                                <h6 class="fw-bold font-heading text-dark mb-0 fs-6">संपर्क एवं प्रतिनिधि विवरण</h6>
                            </div>

                            <div class="row g-3">
                                <!-- Contact Person -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        संपर्क व्यक्ति का नाम
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-person"></i></span>
                                        <input type="text" name="contact_person" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="मालिक, प्रिंसिपल या मैनेजर का नाम" value="<?php echo htmlspecialchars($contact_person); ?>">
                                    </div>
                                </div>

                                <!-- Mobile Number -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        मोबाइल नंबर <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-primary"><i class="bi bi-phone-fill"></i></span>
                                        <input type="tel" name="mobile" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="10 अंकों का मोबाइल नंबर" required maxlength="10" value="<?php echo htmlspecialchars($mobile); ?>">
                                    </div>
                                </div>

                                <!-- WhatsApp Number -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        व्हाट्सएप नंबर <span class="text-muted fw-normal">(ऐच्छिक)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-success"><i class="bi bi-whatsapp"></i></span>
                                        <input type="tel" name="whatsapp" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="10 अंकों का व्हाट्सएप नंबर" maxlength="10" value="<?php echo htmlspecialchars($whatsapp); ?>">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        ईमेल आईडी <span class="text-muted fw-normal">(ऐच्छिक)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control border-secondary-subtle rounded-end-3 py-2.5" placeholder="संपर्क ईमेल (उदा. contact@example.com)" value="<?php echo htmlspecialchars($email); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 4: SERVICES & DESCRIPTION -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge rounded-circle bg-warning text-dark p-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">4</span>
                                <h6 class="fw-bold font-heading text-dark mb-0 fs-6">सेवाएं एवं विवरण</h6>
                            </div>

                            <div class="row g-3">
                                <!-- Services & Facilities -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        प्रमुख सेवाएं / सुविधाएं
                                    </label>
                                    <input type="text" name="services" class="form-control border-secondary-subtle rounded-3 py-2.5" placeholder="उदा. ओपीडी, आईसीयू, 24x7 एम्बुलेंस, कानूनी सलाह (अल्पविराम से अलग करें)" value="<?php echo htmlspecialchars($services); ?>">
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        संस्था के बारे में एवं कार्य समय
                                    </label>
                                    <textarea name="description" class="form-control border-secondary-subtle rounded-3" rows="3" placeholder="संक्षिप्त विवरण, विशेषज्ञता, व्यवसाय का समय या मुख्य विशेषताएं दर्ज करें..."><?php echo htmlspecialchars($description); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Info Banner & Submit Button -->
                        <div class="p-3 bg-light rounded-3 mb-4 border border-secondary-subtle text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2 text-muted small">
                                <i class="bi bi-shield-lock-fill text-primary"></i>
                                <span>आपकी संपर्क जानकारी सारण इंडेक्स निर्देशिका पर सुरक्षित एवं प्रकाशित की जाएगी।</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm fs-6 d-flex align-items-center justify-content-center gap-2 transition-all">
                            <span>लिस्टिंग अपडेट करें</span>
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

    if (catSelect && subSelect) {
        catSelect.addEventListener('change', function() {
            const catId = this.value;
            subSelect.innerHTML = '<option value="">उपश्रेणी लोड हो रही है...</option>';
            if (!catId) {
                subSelect.innerHTML = '<option value="">पहले श्रेणी चुनें</option>';
                return;
            }

            fetch(`${BASE_URL}api/subcategories_api.php?category_id=${catId}`)
                .then(response => response.json())
                .then(data => {
                    subSelect.innerHTML = '<option value="">उपश्रेणी चुनें (ऐच्छिक)</option>';
                    const subList = Array.isArray(data) ? data : (data.subcategories || []);
                    if (subList.length > 0) {
                        subList.forEach(sub => {
                            const opt = document.createElement('option');
                            opt.value = sub.id;
                            opt.textContent = sub.hindi_name ? sub.hindi_name : sub.name;
                            subSelect.appendChild(opt);
                        });
                    }
                })
                .catch(() => {
                    subSelect.innerHTML = '<option value="">सभी उपश्रेणियां</option>';
                });
        });
    }

    if (blockSelect && villageSelect) {
        blockSelect.addEventListener('change', function() {
            const blockId = this.value;
            villageSelect.innerHTML = '<option value="">राजस्व मौजा लोड हो रहे हैं...</option>';
            if (!blockId) {
                villageSelect.innerHTML = '<option value="">पहले प्रखंड चुनें</option>';
                return;
            }

            fetch(`${BASE_URL}api/villages_api.php?block_id=${blockId}`)
                .then(response => response.json())
                .then(data => {
                    villageSelect.innerHTML = '<option value="">-- राजस्व मौजा चुनें (ऐच्छिक) --</option>';
                    const villageList = Array.isArray(data) ? data : (data.villages || []);
                    if (villageList.length > 0) {
                        villageList.forEach(v => {
                            const opt = document.createElement('option');
                            const code = v.mauja_code || v.code;
                            opt.value = code;
                            opt.textContent = v.name_hindi || v.display_name || v.name || ('मौजा ' + code);
                            villageSelect.appendChild(opt);
                        });
                    } else {
                        villageSelect.innerHTML = '<option value="">इस प्रखंड के लिए कोई मौजा नहीं मिला</option>';
                    }
                })
                .catch(() => {
                    villageSelect.innerHTML = '<option value="">राजस्व मौजा चुनें (ऐच्छिक)</option>';
                });
        });
    }

});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

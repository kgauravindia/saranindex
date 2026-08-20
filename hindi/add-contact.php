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

    if (!empty($title) && !empty($category_id) && !empty($mobile) && !empty($block_id)) {
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

                $is_unregistered_submission = empty($currentUser);
                // सभी नई लिस्टिंग्स को एडमिन समीक्षा एवं स्वीकृति के बाद ही लाइव प्रकाशित किया जाएगा
                $initial_status = 'PENDING';

                $stmt = $db->prepare("INSERT INTO listings (user_id, category_id, subcategory_id, block_id, village_id, title, hindi_title, slug, contact_person, mobile, whatsapp, email, address, pincode, services, description, plan_type, plan_expires_at, is_featured, is_verified, status) VALUES (:uid, :cat, :sub, :blk, :vid, :title, :htitle, :slug, :cp, :mob, :wa, :email, :addr, :pin, :srv, :desc, :plan, :plan_exp, :feat, :ver, :status)");
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
                    'ver' => $is_verified_val,
                    'status' => $initial_status
                ]);
                $submitted_title = !empty($hindi_title) ? $hindi_title : $title;
                $success_msg = true;
            } catch (PDOException $e) {
                error_log("Listing insert failed: " . $e->getMessage());
                $error_msg = "डेटाबेस में लिस्टिंग जोड़ते समय त्रुटि: " . $e->getMessage();
            }
        } else {
            $error_msg = "डेटाबेस से संपर्क नहीं हो सका। कृपया पुनः प्रयास करें।";
        }
    } else {
        $error_msg = "कृपया * से चिह्नित सभी आवश्यक फ़ील्ड भरें (नाम, मोबाइल, श्रेणी, एवं प्रखंड)।";
    }
}

$catHindiName = "निःशुल्क लिस्टिंग जोड़ें";
$page_title = "मुफ्त लिस्टिंग जोड़ें – सारण जिला में व्यवसाय दर्ज करें | सारण इंडेक्स";
$meta_description = "सारण इंडेक्स पर अपना व्यवसाय, क्लीनिक, दुकान, स्कूल या सेवा दर्ज करें। छपरा और सारण जिले के सभी 20 प्रखंडों के नागरिकों से सीधे जुड़ें।";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header -->
<div class="bg-gradient-primary text-white py-4 py-md-5 position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.12) 0%, transparent 60%); pointer-events: none;"></div>
    <div class="container position-relative z-1 text-center">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill me-1"></i>मुख्य पृष्ठ</a></li>
                <li class="breadcrumb-item text-white-50">निर्देशिका</li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">लिस्टिंग जोड़ें</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill fs-7 shadow-sm">
                <i class="bi bi-star-fill me-1"></i> निःशुल्क व्यापार निर्देशिका
            </span>
            <span class="badge px-3 py-1.5 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.3);">
                <i class="bi bi-geo-alt-fill me-1"></i> 20 प्रखंड एवं 1,764 मौजा
            </span>
        </div>

        <h1 class="h2 fw-bold font-heading text-white mb-2">
            सारण इंडेक्स पर अपना व्यवसाय या सेवा दर्ज करें
        </h1>
        <p class="text-white-50 fs-6 mx-auto mb-0" style="max-width: 680px;">
            छपरा सहित सारण जिले के सभी 20 प्रखंडों के नागरिकों तक अपनी पहुँच बनाएं। अपनी दुकान, क्लीनिक या सेवा को तुरंत जोड़ें।
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
                            <h5 class="fw-bold text-success mb-1">लिस्टिंग सफलतापूर्वक दर्ज हो गई!</h5>
                            <p class="text-secondary small mb-2">
                                <strong>सारण इंडेक्स</strong> पर <strong><?php echo sanitizeInput($submitted_title ?? 'आपकी जानकारी'); ?></strong> दर्ज करने के लिए धन्यवाद।
                            </p>
                            <div class="p-3 bg-white rounded-3 border mb-3">
                                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fs-7 fw-bold mb-2 d-inline-block shadow-xs">
                                    <i class="bi bi-hourglass-split me-1"></i> एडमिन समीक्षा एवं स्वीकृति के लिए लंबित (Pending Admin Approval)
                                </span>
                                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                                    निर्देशिका की गुणवत्ता एवं प्रमाणिकता बनाए रखने हेतु सभी नई लिस्टिंग्स को एडमिन द्वारा सत्यापन एवं स्वीकृति के उपरांत ही लाइव प्रकाशित किया जाता है। आपकी लिस्टिंग समीक्षा कतार में है और एडमिन द्वारा स्वीकृत होते ही प्रकाशित कर दी जाएगी।
                                </p>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php if (!empty($currentUser)): ?>
                                    <a href="dashboard.php" class="btn btn-sm btn-success rounded-pill px-3 fw-bold"><i class="bi bi-speedometer2 me-1"></i> डैशबोर्ड पर जाएं</a>
                                <?php else: ?>
                                    <a href="register.php" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold"><i class="bi bi-person-plus me-1"></i> नया अकाउंट बनाएं</a>
                                    <a href="login.php" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold"><i class="bi bi-box-arrow-in-right me-1"></i> लॉगिन करें</a>
                                <?php endif; ?>
                                <a href="add-contact.php" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold"><i class="bi bi-plus-lg me-1"></i> दूसरी लिस्टिंग जोड़ें</a>
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
                
                <!-- Header Banner -->
                <div class="bg-white p-4 p-md-4.5 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-journal-plus fs-3"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold font-heading text-dark mb-0">व्यवसाय डायरेक्टरी पंजीकरण</h4>
                            <p class="text-muted small mb-0">सारण जिले में अपने व्यवसाय या सेवा की आधिकारिक जानकारी भरें।</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-4.5 bg-white">
                    <form action="add-contact.php" method="POST" id="addListingForm">

                        <!-- SECTION 1: बुनियादी जानकारी -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">1</span>
                                <h5 class="fw-bold font-heading text-dark mb-0">बुनियादी विवरण</h5>
                            </div>

                            <div class="row g-3">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        संस्था / व्यवसाय का नाम (अंग्रेजी में) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-building"></i></span>
                                        <input type="text" name="title" class="form-control border-secondary-subtle rounded-end-3 py-2.5" required>
                                    </div>
                                </div>

                                <!-- Hindi Name -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        संस्था / व्यवसाय का नाम (हिंदी में) <span class="text-muted fw-normal">(ऐच्छिक)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-translate"></i></span>
                                        <input type="text" name="hindi_title" class="form-control border-secondary-subtle rounded-end-3 py-2.5">
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        मुख्य श्रेणी (Category) <span class="text-danger">*</span>
                                    </label>
                                    <select name="category_id" id="category_select" class="form-select border-secondary-subtle rounded-3 py-2.5" required>
                                        <option value="">-- श्रेणी चुनें --</option>
                                        <?php foreach ($categories as $cat): 
                                            $cName = !empty($cat['hindi_name']) ? $cat['hindi_name'] . ' (' . $cat['name'] . ')' : $cat['name'];
                                        ?>
                                            <option value="<?php echo sanitizeInput($cat['id']); ?>">
                                                <?php echo sanitizeInput($cName); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Subcategory -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        उप-श्रेणी (Subcategory) <span class="text-muted fw-normal">(ऐच्छिक)</span>
                                    </label>
                                    <select name="subcategory_id" id="subcategory_select" class="form-select border-secondary-subtle rounded-3 py-2.5">
                                        <option value="">पहले श्रेणी चुनें</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 2: स्थान एवं प्रखंड -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">2</span>
                                <h5 class="fw-bold font-heading text-dark mb-0">स्थान एवं मौजा (ग्राम)</h5>
                            </div>

                            <div class="row g-3">
                                <!-- Block -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        प्रखंड (Block) <span class="text-danger">*</span>
                                    </label>
                                    <select name="block_id" id="block_select" class="form-select border-secondary-subtle rounded-3 py-2.5" required>
                                        <option value="">-- प्रखंड चुनें --</option>
                                        <?php foreach ($blocks as $blk): 
                                            $bName = !empty($blk['hindi_name']) ? $blk['hindi_name'] . ' (' . $blk['block_name'] . ')' : $blk['block_name'];
                                        ?>
                                            <option value="<?php echo sanitizeInput($blk['id']); ?>">
                                                <?php echo sanitizeInput($bName); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Mauja / Village -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        मौजा / ग्राम (Mauja Code) <span class="text-muted fw-normal">(खोजने योग्य)</span>
                                    </label>
                                    <select name="mauja_code" id="village_select" class="form-select border-secondary-subtle rounded-3 py-2.5">
                                        <option value="">पहले प्रखंड चुनें</option>
                                    </select>
                                </div>

                                <!-- Address -->
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        स्थानीय पता / लैंडमार्क
                                    </label>
                                    <input type="text" name="address" class="form-control border-secondary-subtle rounded-3 py-2.5">
                                </div>

                                <!-- Pincode -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        पिनकोड
                                    </label>
                                    <input type="text" name="pincode" class="form-control border-secondary-subtle rounded-3 py-2.5" maxlength="6">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 3: संपर्क विवरण -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">3</span>
                                <h5 class="fw-bold font-heading text-dark mb-0">संपर्क विवरण</h5>
                            </div>

                            <div class="row g-3">
                                <!-- Contact Person -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        संपर्क व्यक्ति / प्रोपराइटर नाम
                                    </label>
                                    <input type="text" name="contact_person" class="form-control border-secondary-subtle rounded-3 py-2.5">
                                </div>

                                <!-- Mobile -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        कॉल करने हेतु मोबाइल नंबर <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-muted">+91</span>
                                        <input type="tel" name="mobile" class="form-control border-secondary-subtle rounded-end-3 py-2.5" required maxlength="10">
                                    </div>
                                </div>

                                <!-- WhatsApp -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        व्हाट्सएप नंबर <span class="text-muted fw-normal">(ऐच्छिक)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle text-success"><i class="bi bi-whatsapp"></i></span>
                                        <input type="tel" name="whatsapp" class="form-control border-secondary-subtle rounded-end-3 py-2.5" maxlength="10">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        ईमेल पता <span class="text-muted fw-normal">(ऐच्छिक)</span>
                                    </label>
                                    <input type="email" name="email" class="form-control border-secondary-subtle rounded-3 py-2.5">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 4: सेवाएं एवं विवरण -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">4</span>
                                <h5 class="fw-bold font-heading text-dark mb-0">सेवाएं एवं विवरण</h5>
                            </div>

                            <div class="row g-3">
                                <!-- Services & Facilities -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        प्रमुख सेवाएं / सुविधाएं
                                    </label>
                                    <input type="text" name="services" class="form-control border-secondary-subtle rounded-3 py-2.5">
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold fs-7 text-dark mb-1">
                                        विवरण एवं समय
                                    </label>
                                    <textarea name="description" class="form-control border-secondary-subtle rounded-3" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-secondary-subtle opacity-25">

                        <!-- SECTION 5: मेंबरशिप प्लान चुनें -->
                        <div class="mb-4 pb-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">5</span>
                                <h5 class="fw-bold font-heading text-dark mb-0">मेंबरशिप प्लान चुनें</h5>
                            </div>

                            <div class="row g-3">
                                <!-- FREE PLAN -->
                                <div class="col-md-4">
                                    <label class="card h-100 border rounded-4 p-3 cursor-pointer shadow-xs position-relative hover-border-primary">
                                        <input type="radio" name="plan_type" value="FREE" class="form-check-input position-absolute top-0 end-0 m-3" checked>
                                        <div class="fw-bold text-dark fs-6 mb-1">🟢 बुनियादी मुफ्त</div>
                                        <div class="display-7 fw-bolder text-primary mb-2">₹0 <small class="fs-7 text-muted fw-normal">/ हमेशा</small></div>
                                        <ul class="list-unstyled small text-secondary mb-0" style="line-height: 1.6;">
                                            <li><i class="bi bi-check2 text-success me-1"></i> सामान्य खोज रैंक</li>
                                            <li><i class="bi bi-check2 text-success me-1"></i> फ़ोन कॉल बटन</li>
                                            <li><i class="bi bi-check2 text-success me-1"></i> बुनियादी पता</li>
                                        </ul>
                                    </label>
                                </div>

                                <!-- GOLD BUSINESS PLAN -->
                                <div class="col-md-4">
                                    <label class="card h-100 border border-primary rounded-4 p-3 cursor-pointer shadow-sm position-relative bg-primary-subtle border-2">
                                        <input type="radio" name="plan_type" value="GOLD" class="form-check-input position-absolute top-0 end-0 m-3">
                                        <div class="badge bg-primary text-white fw-bold w-auto me-auto mb-1">अनुशंसित</div>
                                        <div class="fw-bold text-primary fs-6 mb-1">🔵 गोल्ड बिजनेस</div>
                                        <div class="display-7 fw-bolder text-primary mb-2">₹499 <small class="fs-7 text-muted fw-normal">/ वर्ष</small></div>
                                        <ul class="list-unstyled small text-dark mb-0" style="line-height: 1.6;">
                                            <li><i class="bi bi-check-circle-fill text-primary me-1"></i> <strong>शीर्ष खोज प्राथमिकता</strong></li>
                                            <li><i class="bi bi-check-circle-fill text-primary me-1"></i> <strong>हरा वेरीफाइड बैज</strong></li>
                                            <li><i class="bi bi-check-circle-fill text-primary me-1"></i> डायरेक्ट व्हाट्सएप बटन</li>
                                            <li><i class="bi bi-check-circle-fill text-primary me-1"></i> 3 फ़ोटो तक अपलोड</li>
                                        </ul>
                                    </label>
                                </div>

                                <!-- PLATINUM VIP PLAN -->
                                <div class="col-md-4">
                                    <label class="card h-100 border border-warning rounded-4 p-3 cursor-pointer shadow-sm position-relative bg-warning-subtle border-2">
                                        <input type="radio" name="plan_type" value="PLATINUM" class="form-check-input position-absolute top-0 end-0 m-3">
                                        <div class="badge bg-warning text-dark fw-bold w-auto me-auto mb-1">सर्वोत्तम दृश्यता</div>
                                        <div class="fw-bold text-dark fs-6 mb-1">👑 वीआईपी प्लैटिनम</div>
                                        <div class="display-7 fw-bolder text-dark mb-2">₹1,499 <small class="fs-7 text-muted fw-normal">/ वर्ष</small></div>
                                        <ul class="list-unstyled small text-dark mb-0" style="line-height: 1.6;">
                                            <li><i class="bi bi-crown-fill text-warning me-1"></i> <strong>शीर्ष फीचर्ड स्थान</strong></li>
                                            <li><i class="bi bi-crown-fill text-warning me-1"></i> <strong>गोल्ड वीआईपी वेरीफाइड बैज</strong></li>
                                            <li><i class="bi bi-crown-fill text-warning me-1"></i> कॉल + व्हाट्सएप + बुकिंग</li>
                                            <li><i class="bi bi-crown-fill text-warning me-1"></i> 6 फ़ोटो तक अपलोड</li>
                                        </ul>
                                    </label>
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
                            <span>डायरेक्टरी लिस्टिंग पंजीकृत करें</span>
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
            subSelect.innerHTML = '<option value="">उप-श्रेणियां लोड हो रही हैं...</option>';
            if (!catId) {
                subSelect.innerHTML = '<option value="">पहले श्रेणी चुनें</option>';
                return;
            }

            fetch('../api/subcategories_api.php?category_id=' + encodeURIComponent(catId))
                .then(res => res.json())
                .then(data => {
                    const subList = Array.isArray(data) ? data : (data.subcategories || []);
                    if (subList.length > 0) {
                        const profList = subList.filter(s => s.type === 'PROFESSIONAL' || !s.type || s.type !== 'BUSINESS');
                        const bizList = subList.filter(s => s.type === 'BUSINESS');

                        let html = '<option value="">-- उप-श्रेणी चुनें --</option>';
                        if (profList.length > 0) {
                            html += '<optgroup label="👨‍💼 पेशेवर सेवा एवं कुशल कार्यकर्ता">';
                            profList.forEach(sub => {
                                let displayName = sub.hindi_name ? sub.hindi_name : sub.name;
                                html += `<option value="${sub.id}">${displayName}</option>`;
                            });
                            html += '</optgroup>';
                        }
                        if (bizList.length > 0) {
                            html += '<optgroup label="🏪 व्यापार एवं प्रतिष्ठान">';
                            bizList.forEach(sub => {
                                let displayName = sub.hindi_name ? sub.hindi_name : sub.name;
                                html += `<option value="${sub.id}">${displayName}</option>`;
                            });
                            html += '</optgroup>';
                        }
                        subSelect.innerHTML = html;
                    } else {

                        subSelect.innerHTML = '<option value="">कोई उप-श्रेणी उपलब्ध नहीं</option>';
                    }
                })
                .catch(() => {
                    subSelect.innerHTML = '<option value="">त्रुटि: उप-श्रेणी लोड नहीं हो सकी</option>';
                });
        });
    }

    if (blockSelect && villageSelect) {
        blockSelect.addEventListener('change', function() {
            const blockId = this.value;
            villageSelect.innerHTML = '<option value="">मौजा / ग्राम लोड हो रहे हैं...</option>';
            if (!blockId) {
                villageSelect.innerHTML = '<option value="">पहले प्रखंड चुनें</option>';
                return;
            }

            fetch('../api/villages_api.php?block_id=' + encodeURIComponent(blockId))
                .then(res => res.json())
                .then(data => {
                    const villageList = Array.isArray(data) ? data : (data.villages || []);
                    if (villageList.length > 0) {
                        let html = '<option value="">-- मौजा / ग्राम चुनें --</option>';
                        villageList.forEach(v => {
                            let code = v.mauja_code || v.code || '';
                            let displayName = v.name_hindi || v.display_name || v.name || 'मौजा ' + code;
                            html += `<option value="${code}">${displayName}</option>`;
                        });
                        villageSelect.innerHTML = html;
                    } else {
                        villageSelect.innerHTML = '<option value="">कोई ग्राम नहीं मिला</option>';
                    }
                })
                .catch(() => {
                    villageSelect.innerHTML = '<option value="">त्रुटि: ग्राम लोड नहीं हो सके</option>';
                });
        });
    }
});
</script>


<?php require_once __DIR__ . '/includes/footer.php'; ?>

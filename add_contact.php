<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = "Add Free Listing (मुफ़्त पंजीकरण) – Saran Index";
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
    $contact_person = isset($_POST['contact_person']) ? sanitizeInput($_POST['contact_person']) : '';
    $mobile = isset($_POST['mobile']) ? sanitizeInput($_POST['mobile']) : '';
    $whatsapp = isset($_POST['whatsapp']) ? sanitizeInput($_POST['whatsapp']) : '';
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
    $address = isset($_POST['address']) ? sanitizeInput($_POST['address']) : '';
    $services = isset($_POST['services']) ? sanitizeInput($_POST['services']) : '';
    $description = isset($_POST['description']) ? sanitizeInput($_POST['description']) : '';

    if (!empty($title) && !empty($mobile) && $category_id > 0 && $block_id > 0) {
        $db = getDB();
        if ($db) {
            try {
                $base_slug = slugify($title);
                $slug = $base_slug . '-' . rand(100, 999);
                $stmt = $db->prepare("INSERT INTO listings (category_id, subcategory_id, block_id, title, hindi_title, slug, contact_person, mobile, whatsapp, email, address, services, description, is_verified, status) VALUES (:cat, :sub, :blk, :title, :htitle, :slug, :cp, :mob, :wa, :email, :addr, :srv, :desc, 'NO', 'ACTIVE')");
                $stmt->execute([
                    'cat' => $category_id,
                    'sub' => $subcategory_id,
                    'blk' => $block_id,
                    'title' => $title,
                    'htitle' => $hindi_title,
                    'slug' => $slug,
                    'cp' => $contact_person,
                    'mob' => $mobile,
                    'wa' => $whatsapp,
                    'email' => $email,
                    'addr' => $address,
                    'srv' => $services,
                    'desc' => $description
                ]);
            } catch (PDOException $e) {
                error_log("Listing insert failed: " . $e->getMessage());
            }
        }
        $success_msg = true;
    } else {
        $error_msg = "Please fill in all required fields marked with * / कृपया * से चिह्नित सभी आवश्यक फ़ील्ड भरें।";
    }
}
?>

<!-- Bilingual Header Banner -->
<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-2 shadow-sm fs-6">
            Free Registration • मुफ़्त व्यापार पंजीकरण
        </span>
        <h1 class="fw-bolder font-heading text-white display-5 mb-2">
            List Your Entity on Saran Index
        </h1>
        <h2 class="h4 text-warning fw-bold mb-3 font-heading">
            सारण इंडेक्स पर अपनी दुकान, संस्था या सेवा जोड़ें
        </h2>
        <p class="text-white-50 lead mx-auto mb-0" style="max-width: 720px; font-size: 1.05rem;">
            Reach thousands of citizens across all 20 blocks of Saran District (Chapra). Instant submission & verification.
            <br><span class="text-white-50 small">सारण जिले के सभी 20 प्रखंडों के नागरिकों तक आसानी से पहुंचें।</span>
        </p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <?php if ($success_msg): ?>
                <div class="alert alert-success rounded-4 p-4 shadow-sm mb-4 border-2 border-success">
                    <h4 class="fw-bold alert-heading mb-2"><i class="bi bi-check-circle-fill me-2 text-success"></i>Registration Submitted Successfully! / पंजीकरण सफलतापूर्वक जमा हुआ!</h4>
                    <p class="mb-1">Thank you for listing on <strong>Saran Index</strong>. Our verification team will review your information and activate your profile shortly.</p>
                    <p class="mb-0 text-muted small">सारण इंडेक्स पर पंजीकरण करने के लिए धन्यवाद। हमारी टीम जल्द ही आपकी जानकारी का सत्यापन करके प्रोफ़ाइल लाइव करेगी।</p>
                </div>
            <?php endif; ?>

            <?php if ($error_msg): ?>
                <div class="alert alert-danger rounded-4 p-3 shadow-sm mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo sanitizeInput($error_msg); ?>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                    <div>
                        <h4 class="fw-bold font-heading text-dark mb-1">Entity & Business Registration Form</h4>
                        <div class="text-muted small fw-medium">व्यापार, कॉलेज, अस्पताल, वकील एवं संस्थान पंजीकरण फॉर्म</div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1 rounded-pill d-none d-md-inline-block">English + हिंदी</span>
                </div>
                
                <form action="add-listing" method="POST">
                    <div class="row g-3">
                        <!-- Entity Name English -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">
                                Name in English (अंग्रेजी में नाम) <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" class="form-control form-control-lg bg-light" placeholder="e.g. Rajendra College, Chapra Legal Chamber, Dr. Kumar Hospital" required>
                        </div>

                        <!-- Entity Name Hindi -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">
                                Name in Hindi (हिंदी में नाम - वैकल्पिक)
                            </label>
                            <input type="text" name="hindi_title" class="form-control form-control-lg bg-light" placeholder="उदा. राजेंद्र कॉलेज छपरा, डॉ. कुमार अस्पताल">
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">
                                Category Vertical (मुख्य श्रेणी) <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="category_select" class="form-select form-select-lg bg-light" required>
                                <option value="">Choose Category / श्रेणी चुनें</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo sanitizeInput($cat['id']); ?>">
                                        <?php echo sanitizeInput($cat['name']); ?> <?php echo !empty($cat['hindi_name']) ? '(' . sanitizeInput($cat['hindi_name']) . ')' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Subcategory -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">
                                Subcategory (उप-श्रेणी)
                            </label>
                            <select name="subcategory_id" id="subcategory_select" class="form-select form-select-lg bg-light">
                                <option value="">Select Category First / पहले श्रेणी चुनें</option>
                            </select>
                        </div>

                        <!-- Saran Block -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">
                                Select Block (सारण प्रखंड) <span class="text-danger">*</span>
                            </label>
                            <select name="block_id" class="form-select form-select-lg bg-light" required>
                                <option value="">Choose Block / प्रखंड चुनें</option>
                                <?php foreach ($blocks as $blk): ?>
                                    <option value="<?php echo sanitizeInput($blk['id']); ?>">
                                        <?php echo sanitizeInput($blk['block_name']); ?> (<?php echo sanitizeInput($blk['hindi_name']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Contact Person -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">
                                Contact Person Name (संपर्क व्यक्ति का नाम)
                            </label>
                            <input type="text" name="contact_person" class="form-control form-control-lg bg-light" placeholder="Owner, Principal, or Manager / स्वामी या प्रबंधक">
                        </div>

                        <!-- Mobile Number -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">
                                Mobile Number (मोबाइल नंबर) <span class="text-danger">*</span>
                            </label>
                            <input type="tel" name="mobile" class="form-control form-control-lg bg-light" placeholder="10-digit Mobile / 10 अंकों का मोबाइल नंबर" required>
                        </div>

                        <!-- WhatsApp Number -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">
                                WhatsApp Number (व्हाट्सएप नंबर)
                            </label>
                            <input type="tel" name="whatsapp" class="form-control form-control-lg bg-light" placeholder="WhatsApp Contact / व्हाट्सएप नंबर">
                        </div>

                        <!-- Email -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-dark">
                                Email Address (ईमेल पता - वैकल्पिक)
                            </label>
                            <input type="email" name="email" class="form-control form-control-lg bg-light" placeholder="contact@domain.com">
                        </div>

                        <!-- Full Address -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-dark">
                                Full Address & PIN Code (पूरा पता एवं पिन कोड) <span class="text-danger">*</span>
                            </label>
                            <textarea name="address" class="form-control bg-light" rows="2" placeholder="Address, Street, Village/Panchayat, Landmark, Pincode (उदा. थाना चौक के पास, मुख्य मार्ग, छपरा सदर, 841301)" required></textarea>
                        </div>

                        <!-- Services & Facilities -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-dark">
                                Key Services / Facilities Offered (प्रदत्त मुख्य सेवाएं एवं सुविधाएं)
                            </label>
                            <input type="text" name="services" class="form-control bg-light" placeholder="e.g. B.A / B.Sc / B.Com Degree, OPD, ICU, 24x7 Ambulance, Legal Advice (comma separated)">
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-dark">
                                Overview & Working Hours (विवरण एवं समय जानकारी)
                            </label>
                            <textarea name="description" class="form-control bg-light" rows="3" placeholder="Enter brief details about courses, specialization, working timing... / समय, अनुभव एवं सेवाओं का विस्तृत विवरण दें"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-12 pt-3">
                            <button type="submit" class="btn btn-warning btn-lg w-100 rounded-pill fw-bold text-dark shadow-sm py-3 fs-5">
                                <i class="bi bi-rocket-takeoff me-2"></i>Submit Free Listing Registration / मुफ़्त पंजीकरण जमा करें
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

    if (catSelect && subSelect) {
        catSelect.addEventListener('change', function() {
            const catId = this.value;
            subSelect.innerHTML = '<option value="">Loading Subcategories / उप-श्रेणी लोड हो रही है...</option>';
            if (!catId) {
                subSelect.innerHTML = '<option value="">Select Category First / पहले श्रेणी चुनें</option>';
                return;
            }

            fetch(`api/subcategories_api.php?category_id=${catId}`)
                .then(response => response.json())
                .then(data => {
                    subSelect.innerHTML = '<option value="">Choose Subcategory (Optional) / उप-श्रेणी चुनें</option>';
                    if (data && data.length > 0) {
                        data.forEach(sub => {
                            const opt = document.createElement('option');
                            opt.value = sub.id;
                            opt.textContent = sub.hindi_name ? `${sub.name} (${sub.hindi_name})` : sub.name;
                            subSelect.appendChild(opt);
                        });
                    }
                })
                .catch(() => {
                    subSelect.innerHTML = '<option value="">All Subcategories / सभी उप-श्रेणियां</option>';
                });
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

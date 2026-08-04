<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/functions.php';

if (isUserLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$blocks = getBlocks();
$prefillMobile = $_GET['mobile'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $blockId = $_POST['block_id'] ?? null;
    $address = $_POST['address'] ?? '';
    $otherState = $_POST['other_state'] ?? '';
    $otherDistrict = $_POST['other_district'] ?? '';
    $otherBlock = $_POST['other_block'] ?? '';
    $villageId = $_POST['village_id'] ?? null;
    $villageName = $_POST['village_name'] ?? '';

    if ($password !== $confirmPassword) {
        $error = "पासवर्ड मेल नहीं खाते। कृपया पुनः दर्ज करें।";
    } else {
        $finalAddress = $address;
        if ($blockId === 'other' && !empty($otherBlock)) {
            $finalAddress .= (!empty($finalAddress) ? ', ' : '') . 'प्रखंड: ' . sanitizeInput($otherBlock);
        } elseif (is_numeric($blockId) && !empty($villageName)) {
            $finalAddress .= (!empty($finalAddress) ? ', ' : '') . 'गाँव: ' . sanitizeInput($villageName);
        }
        $result = registerPublicUser($fullName, $mobile, $password, $email, $blockId, $finalAddress, $otherState, $otherDistrict, $villageId);
        if ($result['success']) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

$page_title = "निःशुल्क खाता बनाएं – सारण इंडेक्स";
$meta_description = "सारण इंडेक्स पर अपना निःशुल्क उपयोगकर्ता खाता पंजीकृत करें और अपनी व्यावसायिक लिस्टिंग प्रबंधित करें।";

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">
                    
                    <!-- Left Side: Branding / Showcase -->
                    <div class="col-md-5 col-lg-5 d-none d-md-flex flex-column justify-content-center bg-gradient-primary text-white p-5 position-relative">
                        <!-- Decorative elements -->
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 50%); pointer-events: none;"></div>
                        <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at bottom right, rgba(59,130,246,0.3) 0%, rgba(0,0,0,0) 50%); pointer-events: none;"></div>
                        
                        <div class="position-relative z-index-1">
                            <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Saran Index Logo" height="65" class="mb-4 rounded-3 shadow-sm bg-white p-2">
                            <h2 class="fw-bold font-heading mb-3 text-white lh-base">सारण नेटवर्क से<br><span class="text-warning">जुड़ें</span></h2>
                            <p class="text-white-50 mb-5 fs-6 lh-lg">अपने व्यवसाय को सूचीबद्ध करने, स्थानीय संपर्कों की खोज करने और जिले से जुड़ने के लिए अपना मुफ़्त खाता बनाएं।</p>
                            
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 backdrop-blur">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-white text-primary flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi bi-briefcase fs-5"></i>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold mb-1">व्यावसायिक लिस्टिंग</div>
                                        <div class="text-white-50 small">अपनी सेवाओं को पूरे जिले में प्रदर्शित करें।</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 backdrop-blur">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-white text-primary flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi bi-people fs-5"></i>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold mb-1">स्थानीय संपर्क</div>
                                        <div class="text-white-50 small">हजारों सत्यापित स्थानीय संपर्कों तक आसानी से पहुंचें।</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Registration Form -->
                    <div class="col-md-7 col-lg-7 bg-white p-4 p-md-5 d-flex flex-column justify-content-center">
                        
                        <!-- Mobile Header (Hidden on Desktop) -->
                        <div class="text-center d-md-none mb-4 pb-2 border-bottom">
                            <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Saran Index Logo" height="50" class="mb-3 rounded-3 shadow-sm">
                            <h4 class="fw-bold font-heading mb-1">खाता बनाएं</h4>
                            <p class="text-muted small">सारण के प्रमुख नेटवर्क से जुड़ें</p>
                        </div>
                        
                        <!-- Desktop Header -->
                        <div class="d-none d-md-block mb-4 pb-2">
                            <h3 class="fw-bold font-heading mb-1 text-dark">अपना निःशुल्क खाता बनाएं</h3>
                            <p class="text-muted small">तुरंत शुरू करने के लिए नीचे विवरण भरें।</p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2.5 shadow-sm border-0" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1 text-danger"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST" class="mt-2">
                            <!-- Full Name -->
                            <div class="form-floating mb-3">
                                <input type="text" name="full_name" id="full_name" class="form-control border-secondary-subtle rounded-3" placeholder="उदा. रमेश कुमार" required value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                                <label for="full_name" class="text-muted"><i class="bi bi-person me-2"></i>पूरा नाम <span class="text-danger">*</span></label>
                            </div>

                            <div class="row g-3 mb-3">
                                <!-- Mobile Number -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" name="mobile" id="mobile" class="form-control border-secondary-subtle rounded-3" placeholder="10-digit Mobile" maxlength="10" required value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : htmlspecialchars($prefillMobile); ?>">
                                        <label for="mobile" class="text-muted"><i class="bi bi-phone me-2"></i>मोबाइल नंबर (+91) <span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" name="email" id="email" class="form-control border-secondary-subtle rounded-3" placeholder="yourname@gmail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                        <label for="email" class="text-muted"><i class="bi bi-envelope me-2"></i>ईमेल (ऐच्छिक)</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Block Selection -->
                            <div class="form-floating mb-3">
                                <select name="block_id" id="block_id" class="form-select border-secondary-subtle rounded-3" onchange="onBlockSelectChange(this.value)">
                                    <option value="">-- प्रखंड चुनें (ऐच्छिक) --</option>
                                    <?php foreach ($blocks as $b): ?>
                                        <option value="<?php echo $b['id']; ?>" <?php echo (isset($_POST['block_id']) && $_POST['block_id'] == $b['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($b['hindi_name']); ?> (<?php echo htmlspecialchars($b['block_name']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="other" <?php echo (isset($_POST['block_id']) && $_POST['block_id'] === 'other') ? 'selected' : ''; ?>>अन्य (सारण से बाहर / अन्य स्थान)</option>
                                </select>
                                <label for="block_id" class="text-muted"><i class="bi bi-geo-alt me-2"></i>सारण में आपका प्रखंड</label>
                            </div>

                            <!-- Dynamic Saran Village Selection Dropdown -->
                            <div id="saran_village_fields" class="form-floating mb-3" style="display: none;">
                                <select name="village_id" id="village_id" class="form-select border-secondary-subtle rounded-3" onchange="updateVillageNameHidden(this)">
                                    <option value="">-- गाँव चुनें (जनगणना 2011) --</option>
                                </select>
                                <input type="hidden" name="village_name" id="village_name" value="<?php echo isset($_POST['village_name']) ? htmlspecialchars($_POST['village_name']) : ''; ?>">
                                <label for="village_id" class="text-muted"><i class="bi bi-houses me-2"></i>प्रखंड में अपना गाँव चुनें</label>
                            </div>

                            <!-- Dynamic 3 Dropdowns Container for Other Location (State, District, Block) -->
                            <div id="other_location_fields" class="bg-light p-3 rounded-3 mb-3 border border-secondary-subtle shadow-sm" style="display: <?php echo (isset($_POST['block_id']) && $_POST['block_id'] === 'other') ? 'block' : 'none'; ?>;">
                                <div class="fw-bold small text-dark mb-2"><i class="bi bi-geo-alt-fill text-primary me-1"></i>स्थान विवरण (सारण से बाहर)</div>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select name="other_state" id="other_state" class="form-select border-secondary-subtle rounded-3" onchange="loadDistricts(this.value)">
                                                <option value="">-- राज्य --</option>
                                            </select>
                                            <label for="other_state" class="text-muted">राज्य</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select name="other_district" id="other_district" class="form-select border-secondary-subtle rounded-3" onchange="loadOtherBlocks(document.getElementById('other_state').value, this.value)">
                                                <option value="">-- जिला --</option>
                                            </select>
                                            <label for="other_district" class="text-muted">जिला</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select name="other_block" id="other_block" class="form-select border-secondary-subtle rounded-3">
                                                <option value="">-- प्रखंड --</option>
                                            </select>
                                            <label for="other_block" class="text-muted">प्रखंड</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-floating position-relative">
                                        <input type="password" name="password" id="password" class="form-control border-secondary-subtle rounded-3" style="padding-right: 40px;" placeholder="At least 6 characters" required>
                                        <label for="password" class="text-muted"><i class="bi bi-lock me-2"></i>पासवर्ड <span class="text-danger">*</span></label>
                                        <button class="btn border-0 text-muted position-absolute end-0 top-0 h-100 px-3 d-flex align-items-center justify-content-center" type="button" id="togglePassword">
                                            <i class="bi bi-eye-slash-fill" id="togglePasswordIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control border-secondary-subtle rounded-3" placeholder="Re-enter Password" required>
                                        <label for="confirm_password" class="text-muted"><i class="bi bi-check2-circle me-2"></i>पुष्टि करें <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-4 shadow-sm fs-6 d-flex align-items-center justify-content-center gap-2">
                                <span>निःशुल्क खाता बनाएं</span>
                                <i class="bi bi-person-plus-fill"></i>
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="position-relative text-center my-4">
                            <hr class="text-secondary-subtle opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted fw-medium">या</span>
                        </div>

                        <!-- Login Action -->
                        <div class="text-center">
                            <p class="small text-muted mb-3">क्या आप पहले से पंजीकृत हैं?</p>
                            <a href="login.php" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-bold w-100 d-flex align-items-center justify-content-center gap-2 transition-all">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <span>सुरक्षित लॉगिन करें</span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            
            <!-- Terms Warning -->
            <div class="text-center mt-4">
                <p class="small text-muted">
                    पंजीकरण करके, आप हमारी <a href="terms.php" class="text-primary text-decoration-none fw-medium hover-underline">सेवा की शर्तों</a> और <a href="privacy-policy.php" class="text-primary text-decoration-none fw-medium hover-underline">गोपनीयता नीति</a> से सहमत होते हैं।
                </p>
            </div>
            
        </div>
    </div>
</div>

<style>
.form-floating > .form-control:focus,
.form-floating > .form-control:not(:placeholder-shown),
.form-floating > .form-select {
    padding-top: 1.625rem;
    padding-bottom: 0.625rem;
}
.form-floating > label {
    padding: 1rem 0.75rem;
}
.form-control:focus, .form-select:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.15);
}
.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
.hover-underline:hover {
    text-decoration: underline !important;
}
</style>

<script>
function onBlockSelectChange(val) {
    const villageFields = document.getElementById('saran_village_fields');
    const otherFields = document.getElementById('other_location_fields');
    
    if (val === 'other') {
        if (villageFields) villageFields.style.display = 'none';
        if (otherFields) {
            otherFields.style.display = 'block';
            if (document.getElementById('other_state').options.length <= 1) {
                loadStates('<?php echo isset($_POST['other_state']) ? htmlspecialchars($_POST['other_state']) : ''; ?>');
            }
        }
    } else if (val && val !== '') {
        if (otherFields) otherFields.style.display = 'none';
        if (villageFields) {
            villageFields.style.display = 'block';
            loadSaranVillages(val, '<?php echo isset($_POST['village_id']) ? htmlspecialchars($_POST['village_id']) : ''; ?>');
        }
    } else {
        if (villageFields) villageFields.style.display = 'none';
        if (otherFields) otherFields.style.display = 'none';
    }
}

function loadSaranVillages(blockId, selectedVillageId = '') {
    const villageSelect = document.getElementById('village_id');
    if (!villageSelect) return;
    villageSelect.innerHTML = '<option value="">-- गाँव चुनें (जनगणना 2011) --</option>';

    if (!blockId) return;

    fetch('<?php echo BASE_URL; ?>api/location_api.php?type=saran_villages&block_id=' + encodeURIComponent(blockId))
        .then(res => res.json())
        .then(villages => {
            villages.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v.id;
                opt.textContent = v.name + ' (कोड: ' + v.town_village_code + ')';
                opt.setAttribute('data-name', v.name);
                if (selectedVillageId && v.id == selectedVillageId) {
                    opt.selected = true;
                    document.getElementById('village_name').value = v.name;
                }
                villageSelect.appendChild(opt);
            });
        }).catch(err => console.error(err));
}

function updateVillageNameHidden(selectElem) {
    const selectedOpt = selectElem.options[selectElem.selectedIndex];
    const vName = selectedOpt ? (selectedOpt.getAttribute('data-name') || '') : '';
    document.getElementById('village_name').value = vName;
}

function loadStates(selectedStateCode = '') {
    fetch('<?php echo BASE_URL; ?>api/location_api.php?type=states')
        .then(res => res.json())
        .then(states => {
            const stateSelect = document.getElementById('other_state');
            if (!stateSelect) return;
            stateSelect.innerHTML = '<option value="">-- राज्य चुनें --</option>';
            states.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.state_code;
                opt.textContent = s.state;
                if (selectedStateCode && (s.state_code == selectedStateCode || s.state == selectedStateCode)) {
                    opt.selected = true;
                }
                stateSelect.appendChild(opt);
            });
            if (selectedStateCode) {
                loadDistricts(stateSelect.value || selectedStateCode, '<?php echo isset($_POST['other_district']) ? htmlspecialchars($_POST['other_district']) : ''; ?>');
            }
        }).catch(err => console.error(err));
}

function loadDistricts(stateCode, selectedDistrictCode = '') {
    const distSelect = document.getElementById('other_district');
    const blockSelect = document.getElementById('other_block');
    if (!distSelect || !blockSelect) return;
    distSelect.innerHTML = '<option value="">-- जिला चुनें --</option>';
    blockSelect.innerHTML = '<option value="">-- प्रखंड चुनें --</option>';

    if (!stateCode) return;

    fetch('<?php echo BASE_URL; ?>api/location_api.php?type=districts&state_code=' + encodeURIComponent(stateCode))
        .then(res => res.json())
        .then(districts => {
            districts.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.district_code;
                opt.textContent = d.district;
                if (selectedDistrictCode && (d.district_code == selectedDistrictCode || d.district == selectedDistrictCode)) {
                    opt.selected = true;
                }
                distSelect.appendChild(opt);
            });
            if (selectedDistrictCode) {
                loadOtherBlocks(stateCode, distSelect.value || selectedDistrictCode, '<?php echo isset($_POST['other_block']) ? htmlspecialchars($_POST['other_block']) : ''; ?>');
            }
        }).catch(err => console.error(err));
}

function loadOtherBlocks(stateCode, districtCode, selectedBlockName = '') {
    const blockSelect = document.getElementById('other_block');
    if (!blockSelect) return;
    blockSelect.innerHTML = '<option value="">-- प्रखंड चुनें --</option>';

    if (!stateCode || !districtCode) return;

    fetch('<?php echo BASE_URL; ?>api/location_api.php?type=blocks&state_code=' + encodeURIComponent(stateCode) + '&district_code=' + encodeURIComponent(districtCode))
        .then(res => res.json())
        .then(blocks => {
            blocks.forEach(b => {
                const opt = document.createElement('option');
                opt.value = b.block;
                opt.textContent = b.block;
                if (selectedBlockName && b.block === selectedBlockName) {
                    opt.selected = true;
                }
                blockSelect.appendChild(opt);
            });
        }).catch(err => console.error(err));
}

document.addEventListener('DOMContentLoaded', function() {
    const blockSelect = document.getElementById('block_id');
    if (blockSelect && blockSelect.value) {
        onBlockSelectChange(blockSelect.value);
    }
});

document.getElementById('togglePassword')?.addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    const icon = document.getElementById('togglePasswordIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        confirmInput.type = 'text';
        icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
        icon.classList.add('text-primary');
    } else {
        passwordInput.type = 'password';
        confirmInput.type = 'password';
        icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
        icon.classList.remove('text-primary');
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

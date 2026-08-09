<?php

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
    $usernameHandle = $_POST['username_handle'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $blockId = $_POST['block_id'] ?? null;
    $address = $_POST['address'] ?? '';
    $otherState = $_POST['other_state'] ?? '';
    $otherDistrict = $_POST['other_district'] ?? '';
    $otherBlock = $_POST['other_block'] ?? '';
    $maujaCode = $_POST['mauja_code'] ?? null;
    $maujaName = $_POST['mauja_name'] ?? '';

    if (empty($_POST['terms'])) {
        $error = "Please agree to the Terms of Service and Privacy Policy to proceed.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match. Please re-enter.";
    } else {
        $finalAddress = $address;
        if ($blockId === 'other' && !empty($otherBlock)) {
            $finalAddress .= (!empty($finalAddress) ? ', ' : '') . 'Block: ' . sanitizeInput($otherBlock);
        } elseif (is_numeric($blockId) && !empty($maujaCode)) {
            $villageId = null;
            $db = getDB();
            if ($db) {
                $stmtM = $db->prepare("SELECT * FROM halka WHERE mauja_code = :mcode LIMIT 1");
                $stmtM->execute(['mcode' => $maujaCode]);
                $maujaInfo = $stmtM->fetch(PDO::FETCH_ASSOC);
                if ($maujaInfo) {
                    $villageId = intval($maujaInfo['id']);
                    $mEngTitle = !empty($maujaInfo['mauja_english']) ? $maujaInfo['mauja_english'] : $maujaInfo['mauja_name'];
                    $finalAddress .= (!empty($finalAddress) ? ', ' : '') . 'Mauja: ' . sanitizeInput($mEngTitle) . ' (' . sanitizeInput($maujaInfo['mauja_name']) . ', Code: ' . sanitizeInput($maujaCode) . ')';
                } else {
                    $vInfo = getCensusVillageByCodeOrId($maujaCode);
                    if ($vInfo) {
                        $villageId = intval($vInfo['id']);
                        $finalAddress .= (!empty($finalAddress) ? ', ' : '') . 'Village: ' . sanitizeInput($vInfo['name']) . ' (Code: ' . sanitizeInput($maujaCode) . ')';
                    }
                }
            }
        }
        $result = registerPublicUser($fullName, $mobile, $password, $email, $blockId, $finalAddress, $otherState, $otherDistrict, $villageId ?? null, $usernameHandle);
        if ($result['success']) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

$page_title = "Create Free Account – Saran Index";
$meta_description = "Register your free user account on Saran Index to manage business listings, post updates, and connect with Saran District.";

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
                            <h2 class="fw-bold font-heading mb-3 text-white lh-base">Join the<br><span class="text-warning">Saran Network</span></h2>
                            <p class="text-white-50 mb-5 fs-6 lh-lg">Create your free account to list your business, discover local contacts, and connect with the district.</p>
                            
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 backdrop-blur">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-white text-primary flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi bi-briefcase fs-5"></i>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold mb-1">Business Listings</div>
                                        <div class="text-white-50 small">Showcase your services to the entire district.</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 backdrop-blur">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-white text-primary flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi bi-people fs-5"></i>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold mb-1">Local Connections</div>
                                        <div class="text-white-50 small">Access thousands of verified local contacts easily.</div>
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
                            <h4 class="fw-bold font-heading mb-1">Create Account</h4>
                            <p class="text-muted small">Join Saran's premier directory network</p>
                        </div>
                        
                        <!-- Desktop Header -->
                        <div class="d-none d-md-block mb-4 pb-2">
                            <h3 class="fw-bold font-heading mb-1 text-dark">Create Your Free Account</h3>
                            <p class="text-muted small">Fill in the details below to get started instantly.</p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2.5 shadow-sm border-0" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1 text-danger"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST" class="mt-2">
                            <div class="row g-3 mb-3">
                                <!-- Full Name -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="full_name" id="full_name" class="form-control border-secondary-subtle rounded-3" placeholder="e.g. Ramesh Kumar" required value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                                        <label for="full_name" class="text-muted"><i class="bi bi-person me-2"></i>Full Name <span class="text-danger">*</span></label>
                                    </div>
                                </div>

                            <div class="row g-3 mb-3">
                                <!-- Mobile Number -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" name="mobile" id="mobile" class="form-control border-secondary-subtle rounded-3" placeholder="10-digit Mobile" maxlength="10" required value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : htmlspecialchars($prefillMobile); ?>">
                                        <label for="mobile" class="text-muted"><i class="bi bi-phone me-2"></i>Mobile No. (+91) <span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" name="email" id="email" class="form-control border-secondary-subtle rounded-3" placeholder="yourname@gmail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                        <label for="email" class="text-muted"><i class="bi bi-envelope me-2"></i>Email (Optional)</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Block Selection -->
                            <div class="form-floating mb-3">
                                <select name="block_id" id="block_id" class="form-select border-secondary-subtle rounded-3" onchange="onBlockSelectChange(this.value)">
                                    <option value="">-- Select Block (Optional) --</option>
                                    <?php foreach ($blocks as $b): ?>
                                        <option value="<?php echo $b['id']; ?>" <?php echo (isset($_POST['block_id']) && $_POST['block_id'] == $b['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($b['block_name']); ?> (<?php echo htmlspecialchars($b['hindi_name']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="other" <?php echo (isset($_POST['block_id']) && $_POST['block_id'] === 'other') ? 'selected' : ''; ?>>Other (Outside Saran / Other Location)</option>
                                </select>
                                <label for="block_id" class="text-muted"><i class="bi bi-geo-alt me-2"></i>Your Block in Saran</label>
                            </div>

                            <!-- Dynamic Saran Mauja Selection Dropdown -->
                            <div id="saran_village_fields" class="form-floating mb-3" style="display: none;">
                                <select name="mauja_code" id="mauja_code" class="form-select border-secondary-subtle rounded-3" onchange="updateMaujaNameHidden(this)">
                                    <option value="">-- Select Revenue Mauja (Optional) --</option>
                                </select>
                                <input type="hidden" name="mauja_name" id="mauja_name" value="<?php echo isset($_POST['mauja_name']) ? htmlspecialchars($_POST['mauja_name']) : ''; ?>">
                                <label for="mauja_code" class="text-muted"><i class="bi bi-houses me-2"></i>Select Your Revenue Mauja</label>
                            </div>

                            <!-- Dynamic 3 Dropdowns Container for Other Location (State, District, Block) -->
                            <div id="other_location_fields" class="bg-light p-3 rounded-3 mb-3 border border-secondary-subtle shadow-sm" style="display: <?php echo (isset($_POST['block_id']) && $_POST['block_id'] === 'other') ? 'block' : 'none'; ?>;">
                                <div class="fw-bold small text-dark mb-2"><i class="bi bi-geo-alt-fill text-primary me-1"></i>Location Details (Outside Saran)</div>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select name="other_state" id="other_state" class="form-select border-secondary-subtle rounded-3" onchange="loadDistricts(this.value)">
                                                <option value="">-- State --</option>
                                            </select>
                                            <label for="other_state" class="text-muted">State</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select name="other_district" id="other_district" class="form-select border-secondary-subtle rounded-3" onchange="loadOtherBlocks(document.getElementById('other_state').value, this.value)">
                                                <option value="">-- District --</option>
                                            </select>
                                            <label for="other_district" class="text-muted">District</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select name="other_block" id="other_block" class="form-select border-secondary-subtle rounded-3">
                                                <option value="">-- Block --</option>
                                            </select>
                                            <label for="other_block" class="text-muted">Block</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-floating position-relative">
                                        <input type="password" name="password" id="password" class="form-control border-secondary-subtle rounded-3" style="padding-right: 40px;" placeholder="At least 6 characters" required>
                                        <label for="password" class="text-muted"><i class="bi bi-lock me-2"></i>Password <span class="text-danger">*</span></label>
                                        <button class="btn border-0 text-muted position-absolute end-0 top-0 h-100 px-3 d-flex align-items-center justify-content-center" type="button" id="togglePassword">
                                            <i class="bi bi-eye-slash-fill" id="togglePasswordIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control border-secondary-subtle rounded-3" placeholder="Re-enter Password" required>
                                        <label for="confirm_password" class="text-muted"><i class="bi bi-check2-circle me-2"></i>Confirm <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms & Privacy Checkbox -->
                            <div class="mb-4 form-check text-start">
                                <input class="form-check-input border-secondary-subtle" type="checkbox" name="terms" id="terms" required <?php echo isset($_POST['terms']) ? 'checked' : ''; ?>>
                                <label class="form-check-label small text-muted ms-1" for="terms">
                                    By registering, you agree to our <a href="terms.php" class="text-primary text-decoration-none fw-semibold hover-underline" target="_blank">Terms of Service</a> and <a href="privacy-policy.php" class="text-primary text-decoration-none fw-semibold hover-underline" target="_blank">Privacy Policy</a>. <span class="text-danger">*</span>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-4 shadow-sm fs-6 d-flex align-items-center justify-content-center gap-2">
                                <span>Create Free Account</span>
                                <i class="bi bi-person-plus-fill"></i>
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="position-relative text-center my-4">
                            <hr class="text-secondary-subtle opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted fw-medium">OR</span>
                        </div>

                        <!-- Login Action -->
                        <div class="text-center">
                            <p class="small text-muted mb-3">Already registered on Saran Index?</p>
                            <a href="login.php" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-bold w-100 d-flex align-items-center justify-content-center gap-2 transition-all">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <span>Log In Securely</span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            
            <!-- Terms Warning -->
            <div class="text-center mt-4">
                <p class="small text-muted">
                    By registering, you agree to our <a href="terms.php" class="text-primary text-decoration-none fw-medium hover-underline">Terms of Service</a> and <a href="privacy-policy.php" class="text-primary text-decoration-none fw-medium hover-underline">Privacy Policy</a>.
                </p>
            </div>
            
        </div>
    </div>
</div>

<style>
/* Custom overrides for this specific page */
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
            loadSaranMaujas(val, '<?php echo isset($_POST['mauja_code']) ? htmlspecialchars($_POST['mauja_code']) : ''; ?>');
        }
    } else {
        if (villageFields) villageFields.style.display = 'none';
        if (otherFields) otherFields.style.display = 'none';
    }
}

function loadSaranMaujas(blockId, selectedMaujaCode = '') {
    const maujaSelect = document.getElementById('mauja_code');
    if (!maujaSelect) return;
    maujaSelect.innerHTML = '<option value="">-- Select Revenue Mauja (Optional) --</option>';

    if (!blockId) return;

    fetch('<?php echo BASE_URL; ?>api/villages_api.php?block_id=' + encodeURIComponent(blockId))
        .then(res => res.json())
        .then(data => {
            const maujas = Array.isArray(data) ? data : (data.villages || data.data || []);
            maujas.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v.code || v.mauja_code;
                opt.textContent = v.name || v.display_name || v.mauja_english || v.mauja_name;
                opt.setAttribute('data-name', v.name || v.display_name || v.mauja_name);
                if (selectedMaujaCode && opt.value == selectedMaujaCode) {
                    opt.selected = true;
                    document.getElementById('mauja_name').value = opt.getAttribute('data-name');
                }
                maujaSelect.appendChild(opt);
            });
        }).catch(err => console.error(err));
}

function updateMaujaNameHidden(selectElem) {
    const selectedOpt = selectElem.options[selectElem.selectedIndex];
    const mName = selectedOpt ? (selectedOpt.getAttribute('data-name') || '') : '';
    document.getElementById('mauja_name').value = mName;
}

function loadStates(selectedStateCode = '') {
    fetch('<?php echo BASE_URL; ?>api/location_api.php?type=states')
        .then(res => res.json())
        .then(states => {
            const stateSelect = document.getElementById('other_state');
            if (!stateSelect) return;
            stateSelect.innerHTML = '<option value="">-- Select State --</option>';
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
    distSelect.innerHTML = '<option value="">-- Select District --</option>';
    blockSelect.innerHTML = '<option value="">-- Select Block --</option>';

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
    blockSelect.innerHTML = '<option value="">-- Select Block --</option>';

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

let regHandleTimeout = null;
function checkRegisterHandleLive(val) {
    const clean = val.replace(/[^a-zA-Z0-9_]/g, '');
    const badge = document.getElementById('regHandleBadge');
    const urlPreview = document.getElementById('regPublicUrlPreview');

    if (urlPreview) {
        urlPreview.textContent = 'saranindex.com/@' + (clean || 'username');
    }

    if (!clean || clean.length < 3) {
        if (badge) {
            badge.className = 'badge bg-warning-subtle text-dark border border-warning-subtle px-2.5 py-1 rounded-pill small fw-semibold';
            badge.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> Min 3 chars required';
        }
        return;
    }

    if (badge) {
        badge.className = 'badge bg-light text-secondary border px-2.5 py-1 rounded-pill small fw-semibold';
        badge.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Checking URL...';
    }

    clearTimeout(regHandleTimeout);
    regHandleTimeout = setTimeout(() => {
        fetch('api/check_username.php?handle=' + encodeURIComponent(clean))
            .then(res => res.json())
            .then(data => {
                if (data.available) {
                    if (badge) {
                        badge.className = 'badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small fw-semibold';
                        badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> URL Available!';
                    }
                } else {
                    if (badge) {
                        badge.className = 'badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill small fw-semibold';
                        badge.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Handle Taken';
                    }
                }
            }).catch(() => {
                if (badge) {
                    badge.className = 'badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small fw-semibold';
                    badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> URL Available';
                }
            });
    }, 300);
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

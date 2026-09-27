<?php
require_once __DIR__ . '/includes/functions.php';

if (isUserLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';
$not_registered_mobile = '';
$default_mode = isset($_GET['mode']) && $_GET['mode'] === 'otp' ? 'otp' : 'password';

// Standard POST fallback for Password Login or Non-JS OTP Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'password_login';

    if ($action === 'password_login') {
        $mobile   = trim($_POST['mobile'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($mobile) || empty($password)) {
            $error = "कृपया अपना मोबाइल नंबर/ईमेल और पासवर्ड दोनों दर्ज करें।";
            $default_mode = 'password';
        } else {
            $result = loginPublicUser($mobile, $password);
            if ($result['success']) {
                $redirectUrl = !empty($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'dashboard.php';
                unset($_SESSION['redirect_after_login']);
                header("Location: " . $redirectUrl);
                exit;
            } else {
                $error = $result['message'];
                $default_mode = 'password';
                $cleanMob = preg_replace('/[^0-9]/', '', $mobile);
                if (strlen($cleanMob) >= 10) {
                    $u = findUserByIdentifier($cleanMob);
                    if (!$u) {
                        $not_registered_mobile = substr($cleanMob, -10);
                    }
                }
            }
        }
    }
}

$page_title       = "स्मार्ट लॉगिन (ओटीपी या पासवर्ड) – सारण इंडेक्स";
$meta_description = "सारण इंडेक्स में 6-अंकीय मोबाइल ओटीपी या पासवर्ड से आसानी और सुरक्षा के साथ लॉगिन करें।";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">
                    
                    <!-- Left Side: Branding / Showcase (Desktop) -->
                    <div class="col-md-5 col-lg-5 d-none d-md-flex flex-column justify-content-between text-white p-4 p-lg-5 position-relative" style="background: linear-gradient(145deg, #0F172A 0%, #1E3A8A 55%, #0284C7 100%);">
                        <!-- Decorative glow -->
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 60%); pointer-events: none;"></div>
                        <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at bottom right, rgba(245,158,11,0.2) 0%, rgba(0,0,0,0) 50%); pointer-events: none;"></div>
                        
                        <div class="position-relative z-index-1">
                            <a href="index.php" class="d-inline-block mb-4">
                                <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Saran Index Logo" height="58" class="rounded-3 shadow-sm bg-white p-2">
                            </a>
                            <div class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3 d-inline-flex align-items-center gap-1.5 shadow-sm">
                                <i class="bi bi-lightning-charge-fill"></i> त्वरित एवं पासवर्ड-रहित
                            </div>
                            <h2 class="fw-bold font-heading mb-3 text-white lh-base">
                                सारण जिले की डिजिटल<br><span class="text-warning">निर्देशिका में स्वागत है</span>
                            </h2>
                            <p class="text-white-50 mb-4 small lh-lg">
                                अपनी व्यावसायिक लिस्टिंग प्रबंधित करें, ग्राहक इन्क्वायरी देखें और सारण (छपरा) के सभी 20 प्रखंडों के हजारों नागरिकों से सीधे जुड़ें।
                            </p>
                        </div>

                        <div class="position-relative z-index-1">
                            <div class="d-flex flex-column gap-2.5">
                                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 backdrop-blur">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-warning text-dark flex-shrink-0 fw-bold" style="width: 38px; height: 38px;">
                                        <i class="bi bi-shield-lock-fill fs-6"></i>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold text-white small mb-0.5">1-क्लिक ओटीपी लॉगिन</div>
                                        <div class="text-white-50" style="font-size: 0.76rem;">पासवर्ड याद रखने का कोई झंझट नहीं। सीधा एसएमएस ओटीपी।</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 backdrop-blur">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-info text-dark flex-shrink-0 fw-bold" style="width: 38px; height: 38px;">
                                        <i class="bi bi-patch-check-fill fs-6"></i>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold text-white small mb-0.5">सत्यापित सारण डायरेक्टरी</div>
                                        <div class="text-white-50" style="font-size: 0.76rem;">स्थानीय ग्राहकों से सीधे कॉल एवं व्हाट्सएप पर संपर्क।</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-top border-white border-opacity-10 text-white-50 small d-flex align-items-center justify-content-between">
                                <span>24x7 सत्यापित निर्देशिका</span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5">सक्रिय</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Login Smart Form -->
                    <div class="col-md-7 col-lg-7 bg-white p-4 p-md-5 d-flex flex-column justify-content-center">
                        
                        <!-- Mobile Header (Hidden on Desktop) -->
                        <div class="text-center d-md-none mb-3 pb-2 border-bottom">
                            <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Saran Index Logo" height="48" class="mb-2 rounded-3 shadow-sm">
                            <h4 class="fw-bold font-heading mb-0 text-dark">सारण इंडेक्स में लॉगिन</h4>
                            <p class="text-muted small mb-0">अपनी लिस्टिंग व प्रोफाइल प्रबंधित करने के लिए साइन इन करें</p>
                        </div>
                        
                        <!-- Desktop Header -->
                        <div class="d-none d-md-block mb-3">
                            <h3 class="fw-bold font-heading mb-1 text-dark">साइन इन करें</h3>
                            <p class="text-muted small mb-0">अपने सारण इंडेक्स डैशबोर्ड तक पहुँचें</p>
                        </div>

                        <!-- Global Alert Container -->
                        <div id="loginAlertBox">
                            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'profile_deleted'): ?>
                                <div class="alert alert-success alert-dismissible fade show rounded-3 small border-0 shadow-sm mb-3" role="alert">
                                    <i class="bi bi-check-circle-fill me-2 text-success"></i> आपका खाता और प्रोफाइल सफलतापूर्वक डिलीट कर दिया गया है।
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_GET['verified']) && $_GET['verified'] === '1'): ?>
                                <div class="alert alert-success alert-dismissible fade show rounded-3 small border-0 shadow-sm mb-3" role="alert">
                                    <i class="bi bi-patch-check-fill me-2 text-success"></i> मोबाइल नंबर सफलतापूर्वक सत्यापित हो गया है! कृपया लॉगिन करें।
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger alert-dismissible fade show rounded-3 small border-0 shadow-sm mb-3" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> <?php echo $error; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($not_registered_mobile)): ?>
                                <div class="alert alert-warning rounded-3 small mb-3 border-warning shadow-sm">
                                    <div class="fw-bold mb-1 text-dark"><i class="bi bi-person-plus-fill me-1"></i> मोबाइल अभी पंजीकृत नहीं है</div>
                                    <span class="text-dark">मोबाइल <strong>+91 <?php echo htmlspecialchars($not_registered_mobile); ?></strong> से कोई खाता नहीं मिला। मात्र 30 सेकंड में अपना निःशुल्क खाता बनाएं:</span>
                                    <div class="mt-2.5 mb-1">
                                        <a href="register.php?mobile=<?php echo htmlspecialchars($not_registered_mobile); ?>" class="btn btn-warning text-dark btn-sm fw-bold rounded-pill px-4 shadow-sm">
                                            अभी पंजीकरण करें <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Smart Login Mode Tabs -->
                        <div class="bg-light p-1 rounded-pill mb-4 d-flex border" role="tablist">
                            <button type="button" class="btn btn-sm rounded-pill flex-fill fw-bold py-2 transition-all <?php echo $default_mode === 'password' ? 'btn-white bg-white text-primary shadow-sm' : 'text-muted'; ?>" id="tabBtnPassword" onclick="switchLoginMode('password')">
                                <i class="bi bi-key-fill me-1 text-primary"></i> पासवर्ड से लॉगिन
                            </button>
                            <button type="button" class="btn btn-sm rounded-pill flex-fill fw-bold py-2 transition-all <?php echo $default_mode === 'otp' ? 'btn-white bg-white text-primary shadow-sm' : 'text-muted'; ?>" id="tabBtnOtp" onclick="switchLoginMode('otp')">
                                <i class="bi bi-shield-lock-fill me-1 text-warning"></i> ओटीपी (OTP) से लॉगिन
                            </button>
                        </div>

                        <!-- ========================================================= -->
                        <!-- MODE 1: CLASSIC PASSWORD LOGIN CONTAINER (1st Preference) -->
                        <!-- ========================================================= -->
                        <div id="passwordLoginSection" style="<?php echo $default_mode === 'password' ? 'display: block;' : 'display: none;'; ?>">
                            <form action="" method="POST" id="passwordLoginForm">
                                <input type="hidden" name="action" value="password_login">

                                <!-- Mobile / Email / Username Field -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="mobile" id="passwordMobileInput" class="form-control border-secondary-subtle rounded-3"
                                           placeholder="मोबाइल नंबर, ईमेल या @यूजरनेम"
                                           required
                                           <?php echo $default_mode === 'password' ? 'autofocus' : ''; ?>
                                           value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; ?>">
                                    <label for="passwordMobileInput" class="text-muted"><i class="bi bi-person-badge me-2"></i>मोबाइल, ईमेल या @यूजरनेम</label>
                                </div>
                                
                                <!-- Password Field -->
                                <div class="form-floating mb-2 position-relative">
                                    <input type="password" name="password" id="passwordField" class="form-control border-secondary-subtle rounded-3" style="padding-right: 50px;" placeholder="Enter Password" required>
                                    <label for="passwordField" class="text-muted"><i class="bi bi-lock me-2"></i>पासवर्ड</label>
                                    <button class="btn border-0 text-muted position-absolute end-0 top-0 h-100 px-3 d-flex align-items-center justify-content-center" type="button" id="togglePassword">
                                        <i class="bi bi-eye-slash-fill fs-5" id="togglePasswordIcon"></i>
                                    </button>
                                </div>

                                <!-- Caps Lock Warning -->
                                <div id="capsLockWarning" class="alert alert-warning py-1 px-2.5 small rounded-2 mb-2 d-none" style="font-size: 0.75rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i> Caps Lock चालू है
                                </div>

                                <!-- Options -->
                                <div class="d-flex align-items-center justify-content-between mb-4 px-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" checked>
                                        <label class="form-check-label small text-muted user-select-none" for="rememberMe">
                                            मुझे याद रखें
                                        </label>
                                    </div>
                                    <a href="forgot-password.php" class="small fw-semibold text-primary text-decoration-none hover-underline">पासवर्ड भूल गए?</a>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-3 search-submit-btn fs-6 d-flex align-items-center justify-content-center gap-2">
                                    <span>सुरक्षित लॉगिन करें</span>
                                    <i class="bi bi-arrow-right-circle-fill"></i>
                                </button>
                            </form>
                        </div>

                        <!-- ========================================================= -->
                        <!-- MODE 2: SMART OTP LOGIN CONTAINER (2nd Preference) -->
                        <!-- ========================================================= -->
                        <div id="otpLoginSection" style="<?php echo $default_mode === 'otp' ? 'display: block;' : 'display: none;'; ?>">
                            
                            <!-- STEP 1: Enter Mobile Number -->
                            <div id="otpStep1">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-dark mb-1">पंजीकृत मोबाइल नंबर दर्ज करें</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-secondary-subtle text-dark fw-bold fs-6">
                                            🇮🇳 +91
                                        </span>
                                        <input type="tel" id="otpMobileInput" class="form-control border-secondary-subtle fs-6 fw-semibold" 
                                               placeholder="10 अंकों का मोबाइल नंबर दर्ज करें" 
                                               maxlength="14" 
                                               autocomplete="tel" 
                                               <?php echo $default_mode === 'otp' ? 'autofocus' : ''; ?>
                                               value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; ?>">
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-shield-check text-success me-1"></i>हम आपके नंबर पर 6-अंकीय ओटीपी भेजेंगे।
                                    </small>
                                </div>

                                <button type="button" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-3 shadow-sm d-flex align-items-center justify-content-center gap-2" id="btnSendOtp" onclick="handleSendOtp()">
                                    <span id="btnSendOtpText">ओटीपी प्राप्त करें</span>
                                    <i class="bi bi-arrow-right-circle-fill fs-5" id="btnSendOtpIcon"></i>
                                    <div class="spinner-border spinner-border-sm text-light d-none" id="btnSendOtpSpinner" role="status"></div>
                                </button>
                            </div>

                            <!-- STEP 2: Enter 6-Digit OTP -->
                            <div id="otpStep2" style="display: none;">
                                <div class="bg-light p-3 rounded-4 border mb-3 text-center position-relative">
                                    <button type="button" class="btn btn-link btn-sm text-decoration-none position-absolute top-0 end-0 p-2 text-muted" onclick="resetOtpStep()" title="नंबर बदलें">
                                        <i class="bi bi-pencil-square me-1"></i>बदलें
                                    </button>
                                    <div class="text-muted small mb-0.5">ओटीपी इस नंबर पर भेजा गया:</div>
                                    <div class="fw-bold text-dark fs-6" id="otpDestinationDisplay">+91 •••••• 0000</div>
                                </div>

                                <div class="mb-3 text-center">
                                    <label class="form-label small fw-bold text-dark mb-2">6-अंकीय सुरक्षा कोड (OTP) दर्ज करें</label>
                                    
                                    <!-- 6 Individual Input Boxes -->
                                    <div class="d-flex justify-content-center gap-2 mb-2" id="otpBoxContainer">
                                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control text-center fw-bold fs-4 otp-digit-box rounded-3" maxlength="1" data-index="0" autocomplete="one-time-code">
                                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control text-center fw-bold fs-4 otp-digit-box rounded-3" maxlength="1" data-index="1">
                                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control text-center fw-bold fs-4 otp-digit-box rounded-3" maxlength="1" data-index="2">
                                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control text-center fw-bold fs-4 otp-digit-box rounded-3" maxlength="1" data-index="3">
                                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control text-center fw-bold fs-4 otp-digit-box rounded-3" maxlength="1" data-index="4">
                                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control text-center fw-bold fs-4 otp-digit-box rounded-3" maxlength="1" data-index="5">
                                    </div>
                                    <small class="text-muted" style="font-size: 0.74rem;">सुझाव: आप एसएमएस से कॉपी किया गया पूरा कोड यहाँ पेस्ट कर सकते हैं।</small>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="otpRememberMe" checked>
                                        <label class="form-check-label small text-muted user-select-none" for="otpRememberMe">
                                            लॉगिन याद रखें
                                        </label>
                                    </div>
                                    
                                    <!-- Resend Timer -->
                                    <div id="resendOtpWrapper">
                                        <button type="button" class="btn btn-link btn-sm text-primary p-0 fw-semibold text-decoration-none d-none" id="btnResendOtp" onclick="handleResendOtp()">
                                            <i class="bi bi-arrow-clockwise me-1"></i>ओटीपी पुनः भेजें
                                        </button>
                                        <span class="small text-muted" id="otpTimerDisplay">
                                            <i class="bi bi-clock me-1"></i>पुनः भेजें: <strong id="timerCountdown">60</strong>s
                                        </span>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-success w-100 rounded-pill py-3 fw-bold mb-3 shadow-sm d-flex align-items-center justify-content-center gap-2" id="btnVerifyOtp" onclick="handleVerifyOtp()">
                                    <span id="btnVerifyOtpText">सत्यापित करें एवं लॉगिन करें</span>
                                    <i class="bi bi-check-circle-fill fs-5" id="btnVerifyOtpIcon"></i>
                                    <div class="spinner-border spinner-border-sm text-light d-none" id="btnVerifyOtpSpinner" role="status"></div>
                                </button>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="position-relative text-center my-3">
                            <hr class="text-secondary-subtle opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted fw-medium">या</span>
                        </div>

                        <!-- Register Action -->
                        <div class="text-center">
                            <p class="small text-muted mb-2">क्या आपका खाता नहीं है?</p>
                            <a href="register.php" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-bold w-100 d-flex align-items-center justify-content-center gap-2 transition-all">
                                <i class="bi bi-person-plus"></i>
                                <span>30 सेकंड में नया खाता बनाएं</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Helper -->
            <div class="text-center mt-4">
                <div class="bg-white rounded-pill px-4 py-2 d-inline-block shadow-sm border border-light">
                    <span class="small text-muted fw-medium me-2">क्या आप सारण में व्यवसायी हैं?</span> 
                    <a href="add-contact.php" class="fw-bold text-primary text-decoration-none small">
                        निःशुल्क लिस्टिंग जोड़ें <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.otp-digit-box {
    width: 48px;
    height: 56px;
    font-size: 1.5rem !important;
    caret-color: #2563EB;
    border: 2px solid #E2E8F0;
    transition: all 0.15s ease-in-out;
}
.otp-digit-box:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.15);
    background-color: #F8FAFC;
    transform: scale(1.04);
}
.form-floating > .form-control:focus,
.form-floating > .form-control:not(:placeholder-shown) {
    padding-top: 1.625rem;
    padding-bottom: 0.625rem;
}
.form-floating > label {
    padding: 1rem 0.75rem;
}
.form-control:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.15);
}
.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
.hover-underline:hover {
    text-decoration: underline !important;
}
.shake {
    animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both;
}
@keyframes shake {
    10%, 90% { transform: translate3d(-1px, 0, 0); }
    20%, 80% { transform: translate3d(2px, 0, 0); }
    30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
    40%, 60% { transform: translate3d(4px, 0, 0); }
}
</style>

<script>
let currentLoginMode = '<?php echo $default_mode; ?>';
let resendTimerInterval = null;
let currentMobileTarget = '';

function switchLoginMode(mode) {
    currentLoginMode = mode;
    const otpSec = document.getElementById('otpLoginSection');
    const pwdSec = document.getElementById('passwordLoginSection');
    const tabOtp = document.getElementById('tabBtnOtp');
    const tabPwd = document.getElementById('tabBtnPassword');

    if (mode === 'otp') {
        otpSec.style.display = 'block';
        pwdSec.style.display = 'none';
        tabOtp.className = 'btn btn-sm rounded-pill flex-fill fw-bold py-2 transition-all btn-white bg-white text-primary shadow-sm';
        tabPwd.className = 'btn btn-sm rounded-pill flex-fill fw-bold py-2 transition-all text-muted';
        
        // Sync values if any
        const pwdMob = document.getElementById('passwordMobileInput').value;
        if (pwdMob && !document.getElementById('otpMobileInput').value) {
            document.getElementById('otpMobileInput').value = pwdMob;
        }
        document.getElementById('otpMobileInput').focus();
    } else {
        otpSec.style.display = 'none';
        pwdSec.style.display = 'block';
        tabPwd.className = 'btn btn-sm rounded-pill flex-fill fw-bold py-2 transition-all btn-white bg-white text-primary shadow-sm';
        tabOtp.className = 'btn btn-sm rounded-pill flex-fill fw-bold py-2 transition-all text-muted';
        
        // Sync values if any
        const otpMob = document.getElementById('otpMobileInput').value;
        if (otpMob && !document.getElementById('passwordMobileInput').value) {
            document.getElementById('passwordMobileInput').value = otpMob;
        }
        document.getElementById('passwordField').focus();
    }
}

function showLoginAlert(message, type = 'danger') {
    const box = document.getElementById('loginAlertBox');
    if (!box) return;

    const iconClass = type === 'success' ? 'bi-check-circle-fill text-success' : (type === 'warning' ? 'bi-exclamation-circle-fill text-warning' : 'bi-exclamation-triangle-fill text-danger');
    box.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show rounded-3 small border-0 shadow-sm mb-3 shake" role="alert">
            <i class="bi ${iconClass} me-2"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
}

function showNotRegisteredAlert(mobile) {
    const box = document.getElementById('loginAlertBox');
    if (!box) return;

    box.innerHTML = `
        <div class="alert alert-warning rounded-3 small mb-3 border-warning shadow-sm shake">
            <div class="fw-bold mb-1 text-dark"><i class="bi bi-person-plus-fill me-1"></i> मोबाइल नंबर पंजीकृत नहीं है</div>
            <span class="text-dark">मोबाइल <strong>+91 ${mobile}</strong> पंजीकृत नहीं है। अपना मुफ़्त खाता 30 सेकंड में बनाएं:</span>
            <div class="mt-2.5 mb-1">
                <a href="register.php?mobile=${encodeURIComponent(mobile)}" class="btn btn-warning text-dark btn-sm fw-bold rounded-pill px-4 shadow-sm">
                    अभी खाता बनाएं <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    `;
}

// ─── SEND OTP HANDLER ────────────────────────────────────────────────────────
async function handleSendOtp() {
    const input = document.getElementById('otpMobileInput');
    const btn = document.getElementById('btnSendOtp');
    const text = document.getElementById('btnSendOtpText');
    const icon = document.getElementById('btnSendOtpIcon');
    const spinner = document.getElementById('btnSendOtpSpinner');

    const identifier = input.value.trim();
    if (!identifier) {
        showLoginAlert('कृपया अपना 10-अंकीय मोबाइल नंबर दर्ज करें।', 'danger');
        input.focus();
        return;
    }

    // Loading UI
    btn.disabled = true;
    text.textContent = 'ओटीपी भेजा जा रहा है...';
    icon.classList.add('d-none');
    spinner.classList.remove('d-none');

    try {
        const response = await fetch('../api/auth_otp.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'send_otp', identifier: identifier })
        });

        const res = await response.json();

        if (res.success) {
            currentMobileTarget = res.mobile_raw || identifier;
            document.getElementById('otpDestinationDisplay').textContent = res.masked_mobile;
            document.getElementById('otpStep1').style.display = 'none';
            document.getElementById('otpStep2').style.display = 'block';

            showLoginAlert('ओटीपी आपके मोबाइल नंबर ' + res.masked_mobile + ' पर भेज दिया गया है।', 'success');
            startResendTimer(res.cooldown || 60);

            // Focus on first OTP box
            setTimeout(() => {
                const firstBox = document.querySelector('.otp-digit-box[data-index="0"]');
                if (firstBox) firstBox.focus();
            }, 200);
        } else {
            if (res.not_registered) {
                showNotRegisteredAlert(res.mobile || identifier);
            } else {
                showLoginAlert(res.message || 'ओटीपी भेजने में त्रुटि हुई।', 'danger');
            }
        }
    } catch (err) {
        showLoginAlert('नेटवर्क त्रुटि हुई। कृपया अपना कनेक्शन जांचें और पुनः प्रयास करें।', 'danger');
    } finally {
        btn.disabled = false;
        text.textContent = 'ओटीपी प्राप्त करें';
        icon.classList.remove('d-none');
        spinner.classList.add('d-none');
    }
}

// ─── VERIFY OTP HANDLER ──────────────────────────────────────────────────────
async function handleVerifyOtp() {
    const boxes = document.querySelectorAll('.otp-digit-box');
    let otpCode = '';
    boxes.forEach(b => otpCode += b.value.trim());

    if (otpCode.length !== 6) {
        showLoginAlert('कृपया पूरा 6-अंकीय ओटीपी दर्ज करें।', 'danger');
        boxes[0].focus();
        return;
    }

    const btn = document.getElementById('btnVerifyOtp');
    const text = document.getElementById('btnVerifyOtpText');
    const icon = document.getElementById('btnVerifyOtpIcon');
    const spinner = document.getElementById('btnVerifyOtpSpinner');
    const rememberMe = document.getElementById('otpRememberMe').checked;

    btn.disabled = true;
    text.textContent = 'सत्यापन हो रहा है...';
    icon.classList.add('d-none');
    spinner.classList.remove('d-none');

    try {
        const response = await fetch('../api/auth_otp.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'verify_otp', otp_code: otpCode, remember_me: rememberMe })
        });

        const res = await response.json();

        if (res.success) {
            showLoginAlert('लॉगिन सफल! डैशबोर्ड पर पुनर्निर्देशित किया जा रहा है...', 'success');
            setTimeout(() => {
                window.location.href = res.redirect || 'dashboard.php';
            }, 600);
        } else {
            showLoginAlert(res.message || 'अमान्य ओटीपी कोड दर्ज किया गया।', 'danger');
            boxes.forEach(b => b.value = '');
            boxes[0].focus();
        }
    } catch (err) {
        showLoginAlert('सत्यापन में त्रुटि हुई। कृपया पुनः प्रयास करें।', 'danger');
    } finally {
        btn.disabled = false;
        text.textContent = 'सत्यापित करें एवं लॉगिन करें';
        icon.classList.remove('d-none');
        spinner.classList.add('d-none');
    }
}

// ─── RESEND OTP HANDLER ──────────────────────────────────────────────────────
async function handleResendOtp() {
    const btnResend = document.getElementById('btnResendOtp');
    btnResend.disabled = true;

    try {
        const response = await fetch('../api/auth_otp.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'resend_otp' })
        });

        const res = await response.json();

        if (res.success) {
            showLoginAlert(res.message, 'success');
            startResendTimer(res.cooldown || 60);
        } else {
            showLoginAlert(res.message, 'danger');
            btnResend.disabled = false;
        }
    } catch (err) {
        showLoginAlert('ओटीपी पुनः भेजने में त्रुटि हुई।', 'danger');
        btnResend.disabled = false;
    }
}

function startResendTimer(seconds = 60) {
    clearInterval(resendTimerInterval);
    const btnResend = document.getElementById('btnResendOtp');
    const timerDisplay = document.getElementById('otpTimerDisplay');
    const countdown = document.getElementById('timerCountdown');

    btnResend.classList.add('d-none');
    timerDisplay.classList.remove('d-none');
    let remaining = seconds;
    countdown.textContent = remaining;

    resendTimerInterval = setInterval(() => {
        remaining--;
        countdown.textContent = remaining;
        if (remaining <= 0) {
            clearInterval(resendTimerInterval);
            timerDisplay.classList.add('d-none');
            btnResend.classList.remove('d-none');
            btnResend.disabled = false;
        }
    }, 1000);
}

function resetOtpStep() {
    clearInterval(resendTimerInterval);
    document.getElementById('otpStep2').style.display = 'none';
    document.getElementById('otpStep1').style.display = 'block';
    const boxes = document.querySelectorAll('.otp-digit-box');
    boxes.forEach(b => b.value = '');
    document.getElementById('otpMobileInput').focus();
}

// ─── 6-DIGIT OTP BOX INTERACTION & PASTE HANDLER ────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    const otpBoxes = document.querySelectorAll('.otp-digit-box');

    otpBoxes.forEach((box, index) => {
        box.addEventListener('input', function(e) {
            const val = this.value.replace(/[^0-9]/g, '');
            this.value = val ? val.slice(-1) : '';

            if (this.value && index < otpBoxes.length - 1) {
                otpBoxes[index + 1].focus();
            }

            // Check if all 6 filled -> auto trigger verify
            let fullCode = '';
            otpBoxes.forEach(b => fullCode += b.value.trim());
            if (fullCode.length === 6) {
                handleVerifyOtp();
            }
        });

        box.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                otpBoxes[index - 1].focus();
            } else if (e.key === 'ArrowLeft' && index > 0) {
                otpBoxes[index - 1].focus();
            } else if (e.key === 'ArrowRight' && index < otpBoxes.length - 1) {
                otpBoxes[index + 1].focus();
            } else if (e.key === 'Enter') {
                handleVerifyOtp();
            }
        });

        // Paste support
        box.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            if (pasteData) {
                for (let i = 0; i < otpBoxes.length; i++) {
                    if (i < pasteData.length) {
                        otpBoxes[i].value = pasteData[i];
                    }
                }
                const nextFocus = Math.min(pasteData.length, otpBoxes.length - 1);
                otpBoxes[nextFocus].focus();

                let fullCode = '';
                otpBoxes.forEach(b => fullCode += b.value.trim());
                if (fullCode.length === 6) {
                    handleVerifyOtp();
                }
            }
        });
    });

    // Mobile Input Enter key
    const mobInput = document.getElementById('otpMobileInput');
    if (mobInput) {
        mobInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleSendOtp();
            }
        });
    }

    // Toggle Password Visibility
    const toggleBtn = document.getElementById('togglePassword');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const pwdInput = document.getElementById('passwordField');
            const icon = document.getElementById('togglePasswordIcon');
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
                icon.classList.add('text-primary');
            } else {
                pwdInput.type = 'password';
                icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
                icon.classList.remove('text-primary');
            }
        });
    }

    // Caps Lock Detector
    const pwdField = document.getElementById('passwordField');
    const capsWarning = document.getElementById('capsLockWarning');
    if (pwdField && capsWarning) {
        pwdField.addEventListener('keyup', function(e) {
            if (e.getModifierState && e.getModifierState('CapsLock')) {
                capsWarning.classList.remove('d-none');
            } else {
                capsWarning.classList.add('d-none');
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

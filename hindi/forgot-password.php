<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/functions.php';

if (isset($_GET['restart']) || isset($_GET['reset'])) {
    unset($_SESSION['pwd_reset_step']);
    unset($_SESSION['reset_mobile']);
    unset($_SESSION['reset_user_name']);
    unset($_SESSION['otp_verified']);
    unset($_SESSION['otp_code']);
    header("Location: forgot-password.php");
    exit;
}

$error = '';
$success = '';
$step = $_SESSION['pwd_reset_step'] ?? 1; // Step 1: Mobile, Step 2: OTP Verification, Step 3: New Password, Step 4: Complete
$mobile = $_SESSION['reset_mobile'] ?? '';
$activeOtp = $_SESSION['otp_code'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'verify_mobile') {
        $inputMobile = trim($_POST['mobile'] ?? '');
        $cleanMobile = preg_replace('/[^0-9]/', '', $inputMobile);
        $m10 = (strlen($cleanMobile) >= 10) ? substr($cleanMobile, -10) : $cleanMobile;

        if (strlen($cleanMobile) < 10) {
            $error = "कृपया 10-अंकों का वैध मोबाइल नंबर दर्ज करें।";
            $step = 1;
        } else {
            $db = getDB();
            if ($db) {
                ensureUsersTable();
                $stmt = $db->prepare("SELECT id, full_name, mobile FROM users WHERE (mobile = :mobile OR mobile = :m10_1 OR RIGHT(mobile, 10) = :m10_2) LIMIT 1");
                $stmt->execute(['mobile' => $cleanMobile, 'm10_1' => $m10, 'm10_2' => $m10]);
                $user = $stmt->fetch();

                if ($user) {
                    $_SESSION['reset_mobile'] = $m10;
                    $_SESSION['reset_user_name'] = $user['full_name'];
                    $mobile = $m10;
                    
                    // Generate Mobile OTP
                    $otp = generateMobileOTP($m10, $user['full_name']);
                    $activeOtp = $otp;

                    $_SESSION['pwd_reset_step'] = 2;
                    $step = 2;
                    $success = "+91 " . htmlspecialchars($m10) . " पर सत्यापन ओटीपी भेजा गया। कृपया नीचे 6-अंकों का कोड दर्ज करें।";
                } else {
                    $error = "मोबाइल नंबर +91 " . htmlspecialchars($m10) . " के लिए कोई खाता नहीं मिला। कृपया नया खाता बनाएं।";
                    $step = 1;
                }
            }
        }
    } elseif ($action === 'verify_otp') {
        $inputOtp = trim($_POST['otp'] ?? '');
        $res = verifyMobileOTP($mobile, $inputOtp);

        if ($res['success']) {
            $_SESSION['pwd_reset_step'] = 3;
            $_SESSION['otp_verified'] = true;
            $step = 3;
            $success = "मोबाइल नंबर +91 " . htmlspecialchars($mobile) . " सफलतापूर्वक सत्यापित! नीचे अपना नया पासवर्ड दर्ज करें।";
        } else {
            $error = $res['message'];
            $step = 2;
        }
    } elseif ($action === 'resend_otp') {
        if (!empty($mobile)) {
            $otp = generateMobileOTP($mobile, $_SESSION['reset_user_name'] ?? 'User');
            $activeOtp = $otp;
            $step = 2;
            $success = "+91 " . htmlspecialchars($mobile) . " पर एक नया सत्यापन ओटीपी भेजा गया है।";
        }
    } elseif ($action === 'reset_password') {
        if (empty($_SESSION['otp_verified']) || empty($mobile)) {
            $error = "सत्र समाप्त हो गया या मोबाइल सत्यापित नहीं है। कृपया पुनः प्रयास करें।";
            $step = 1;
        } else {
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (strlen($newPassword) < 6) {
                $error = "नया पासवर्ड कम से कम 6 अक्षरों का होना चाहिए।";
                $step = 3;
            } elseif ($newPassword !== $confirmPassword) {
                $error = "पासवर्ड मेल नहीं खाते। कृपया पुनः प्रयास करें।";
                $step = 3;
            } else {
                $db = getDB();
                if ($db) {
                    $passHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET password_hash = :pass WHERE (mobile = :mobile OR mobile = :m10_1 OR RIGHT(mobile, 10) = :m10_2)");
                    $stmt->execute(['pass' => $passHash, 'mobile' => $mobile, 'm10_1' => $mobile, 'm10_2' => $mobile]);

                    unset($_SESSION['reset_mobile']);
                    unset($_SESSION['reset_user_name']);
                    unset($_SESSION['otp_verified']);
                    unset($_SESSION['pwd_reset_step']);

                    $success = "आपका पासवर्ड सफलतापूर्वक अपडेट हो गया है! अब आप लॉगिन कर सकते हैं।";
                    $step = 4; // Complete
                }
            }
        }
    }
}

$page_title = "मोबाइल ओटीपी पासवर्ड रीसेट – सारण इंडेक्स";
$meta_description = "6-अंकों के मोबाइल ओटीपी सत्यापन द्वारा अपना सारण इंडेक्स खाता पासवर्ड रीसेट करें।";

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">
                    
                    <!-- Left Side: Branding / Showcase -->
                    <div class="col-md-5 col-lg-6 d-none d-md-flex flex-column justify-content-center bg-gradient-primary text-white p-5 position-relative">
                        <!-- Decorative elements -->
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 50%); pointer-events: none;"></div>
                        <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at bottom right, rgba(59,130,246,0.3) 0%, rgba(0,0,0,0) 50%); pointer-events: none;"></div>
                        
                        <div class="position-relative z-index-1">
                            <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Saran Index Logo" height="65" class="mb-4 rounded-3 shadow-sm bg-white p-2">
                            <h2 class="fw-bold font-heading mb-3 text-white lh-base">अपना पासवर्ड<br><span class="text-warning">रीसेट करें</span></h2>
                            <p class="text-white-50 mb-5 fs-6 lh-lg">6-अंकों के मोबाइल ओटीपी सत्यापन के माध्यम से अपना सारण इंडेक्स खाता सुरक्षित रूप से पुनर्प्राप्त करें।</p>
                            
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 backdrop-blur">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-white text-primary flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi bi-phone-vibrate fs-5"></i>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold mb-1">मोबाइल ओटीपी सत्यापन</div>
                                        <div class="text-white-50 small">तेज और सुरक्षित खाता पुनर्प्राप्ति प्रक्रिया।</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 backdrop-blur">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-white text-primary flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi bi-shield-lock fs-5"></i>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold mb-1">सुरक्षित और संरक्षित</div>
                                        <div class="text-white-50 small">आपकी खाता सुरक्षा हमारी सर्वोच्च प्राथमिकता है।</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Reset Form -->
                    <div class="col-md-7 col-lg-6 bg-white p-4 p-md-5 d-flex flex-column justify-content-center">
                        
                        <!-- Mobile Header (Hidden on Desktop) -->
                        <div class="text-center d-md-none mb-4 pb-2 border-bottom">
                            <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Saran Index Logo" height="50" class="mb-3 rounded-3 shadow-sm">
                            <h4 class="fw-bold font-heading mb-1">पासवर्ड रीसेट</h4>
                            <p class="text-muted small">सुरक्षित मोबाइल ओटीपी सत्यापन</p>
                        </div>
                        
                        <!-- Desktop Header -->
                        <div class="d-none d-md-block mb-4 pb-2">
                            <h3 class="fw-bold font-heading mb-1 text-dark">पासवर्ड पुनर्प्राप्ति</h3>
                            <p class="text-muted small">अपना पासवर्ड रीसेट करने के लिए चरणों का पालन करें।</p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2.5 shadow-sm border-0" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1 text-danger"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show rounded-3 small py-2.5 shadow-sm border-0" role="alert">
                                <i class="bi bi-check-circle-fill me-1 text-success"></i> <?php echo $success; ?>
                                <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($step === 1): ?>
                            <!-- Step 1: Request Mobile -->
                            <form action="" method="POST" class="mt-2">
                                <input type="hidden" name="action" value="verify_mobile">
                                <div class="form-floating mb-3">
                                    <input type="tel" name="mobile" id="mobile" class="form-control border-secondary-subtle rounded-3" placeholder="10-digit Mobile Number" required maxlength="10" autofocus value="<?php echo htmlspecialchars($mobile); ?>">
                                    <label for="mobile" class="text-muted"><i class="bi bi-phone me-2"></i>10-अंकों का मोबाइल नंबर</label>
                                </div>
                                <small class="text-muted fs-7 mb-4 d-block px-1"><i class="bi bi-info-circle me-1"></i>हम आपकी पहचान की पुष्टि के लिए 6-अंकों का ओटीपी कोड भेजेंगे।</small>

                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-4 shadow-sm fs-6 d-flex align-items-center justify-content-center gap-2">
                                    <span>ओटीपी कोड भेजें</span>
                                    <i class="bi bi-arrow-right-circle-fill"></i>
                                </button>
                            </form>

                        <?php elseif ($step === 2): ?>
                            <!-- Step 2: Enter & Verify OTP -->
                            <div class="alert alert-light border rounded-3 small py-2 mb-4 text-center shadow-sm">
                                <span class="text-muted">ओटीपी कोड भेजा गया: </span>
                                <strong class="text-dark">+91 <?php echo htmlspecialchars($mobile); ?></strong>
                                <a href="forgot-password.php?restart=1" class="ms-2 small text-primary text-decoration-none fw-semibold hover-underline"><i class="bi bi-pencil-square me-1"></i>बदलें</a>
                            </div>

                            <form action="" method="POST" class="mt-2">
                                <input type="hidden" name="action" value="verify_otp">
                                <div class="mb-4 text-center">
                                    <label for="otp" class="form-label fw-bold small text-dark d-block mb-3">6-अंकों का ओटीपी कोड दर्ज करें</label>
                                    <input type="text" name="otp" id="otp" class="form-control form-control-lg text-center font-monospace tracking-widest fw-bold border-secondary-subtle rounded-3 shadow-sm" placeholder="000000" maxlength="6" required autofocus autocomplete="off" style="font-size: 1.6rem; letter-spacing: 0.5rem; padding: 1rem;">
                                    <small class="text-muted fs-7 mt-2 d-block"><i class="bi bi-clock me-1"></i>10 मिनट के लिए वैध</small>
                                </div>

                                <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-bold mb-4 shadow-sm fs-6 d-flex align-items-center justify-content-center gap-2">
                                    <span>ओटीपी सत्यापित करें और आगे बढ़ें</span>
                                    <i class="bi bi-check-circle-fill"></i>
                                </button>
                            </form>

                            <form action="" method="POST" class="text-center">
                                <input type="hidden" name="action" value="resend_otp">
                                <button type="submit" class="btn btn-link btn-sm text-primary text-decoration-none fw-semibold hover-underline">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> ओटीपी कोड पुनः भेजें
                                </button>
                            </form>

                        <?php elseif ($step === 3): ?>
                            <!-- Step 3: Set New Password -->
                            <form action="" method="POST" class="mt-2">
                                <input type="hidden" name="action" value="reset_password">
                                
                                <div class="form-floating mb-3 position-relative">
                                    <input type="password" name="new_password" id="new_password" class="form-control border-secondary-subtle rounded-3" style="padding-right: 50px;" placeholder="New Password" required autofocus>
                                    <label for="new_password" class="text-muted"><i class="bi bi-lock me-2"></i>नया पासवर्ड</label>
                                    <button class="btn border-0 text-muted position-absolute end-0 top-0 h-100 px-3 d-flex align-items-center justify-content-center" type="button" id="toggleNewPassword">
                                        <i class="bi bi-eye-slash-fill fs-5" id="toggleNewPasswordIcon"></i>
                                    </button>
                                </div>

                                <div class="form-floating mb-4 position-relative">
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control border-secondary-subtle rounded-3" style="padding-right: 50px;" placeholder="Confirm Password" required>
                                    <label for="confirm_password" class="text-muted"><i class="bi bi-check2-circle me-2"></i>नए पासवर्ड की पुष्टि करें</label>
                                </div>

                                <button type="submit" class="btn btn-warning text-dark w-100 rounded-pill py-3 fw-bold shadow-sm mb-3 fs-6 d-flex align-items-center justify-content-center gap-2">
                                    <span>नया पासवर्ड सहेजें</span>
                                    <i class="bi bi-check-circle-fill"></i>
                                </button>
                            </form>
                            
                            <script>
                            document.getElementById('toggleNewPassword')?.addEventListener('click', function () {
                                const passwordInput = document.getElementById('new_password');
                                const confirmInput = document.getElementById('confirm_password');
                                const icon = document.getElementById('toggleNewPasswordIcon');
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

                        <?php elseif ($step === 4): ?>
                            <!-- Step 4: Finished -->
                            <div class="text-center py-4">
                                <div class="text-success display-1 mb-4 animate__animated animate__bounceIn"><i class="bi bi-check-circle-fill drop-shadow"></i></div>
                                <h4 class="fw-bold text-dark mb-2">पासवर्ड अपडेट हो गया!</h4>
                                <p class="text-muted mb-4">आपका पासवर्ड सफलतापूर्वक बदल दिया गया है।</p>
                                <a href="login.php" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm fs-6 d-flex align-items-center justify-content-center gap-2">
                                    <span>अब लॉगिन करें</span>
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Divider and Login link (visible only on step 1 or 2) -->
                        <?php if (in_array($step, [1, 2])): ?>
                            <div class="position-relative text-center my-4">
                                <hr class="text-secondary-subtle opacity-25">
                                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted fw-medium">या</span>
                            </div>

                            <div class="text-center">
                                <p class="small text-muted mb-3">अपना पासवर्ड याद है?</p>
                                <a href="login.php" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-bold w-100 d-flex align-items-center justify-content-center gap-2 transition-all">
                                    <i class="bi bi-arrow-left"></i>
                                    <span>लॉगिन पेज पर वापस जाएं</span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($step === 3): ?>
                            <div class="text-center mt-4 pt-3 border-top">
                                <a href="login.php" class="fw-bold text-muted text-decoration-none small hover-underline">
                                    <i class="bi bi-x-circle me-1"></i> रीसेट रद्द करें
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-floating > .form-control:focus,
.form-floating > .form-control:not(:placeholder-shown) {
    padding-top: 1.625rem;
    padding-bottom: 0.625rem;
}
.form-floating > label {
    padding: 1rem 0.75rem;
}
.form-control:focus {
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>

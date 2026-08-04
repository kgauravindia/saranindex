<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/functions.php';

$error = '';
$success = '';
$step = $_SESSION['pwd_reset_step'] ?? 1;
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
                    $stmt = $db->prepare("UPDATE users SET password_hash = :pass WHERE mobile = :mobile");
                    $stmt->execute(['pass' => $passHash, 'mobile' => $mobile]);

                    unset($_SESSION['reset_mobile']);
                    unset($_SESSION['reset_user_name']);
                    unset($_SESSION['otp_verified']);
                    unset($_SESSION['pwd_reset_step']);

                    $success = "आपका पासवर्ड सफलतापूर्वक अपडेट हो गया है! अब आप लॉगिन कर सकते हैं।";
                    $step = 4;
                }
            }
        }
    }
}

$page_title = "मोबाइल ओटीपी पासवर्ड रीसेट – सारण इंडेक्स";
$meta_description = "6-अंकों के मोबाइल ओटीपी द्वारा अपना सारण इंडेक्स खाता पासवर्ड रीसेट करें।";

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white text-center py-4 px-4 border-0">
                    <h4 class="fw-bold font-heading mb-1 text-white"><i class="bi bi-shield-lock-fill text-warning me-2"></i>मोबाइल ओटीपी रीसेट</h4>
                    <p class="text-white-50 small mb-0">सुरक्षित 6-अंकों का मोबाइल ओटीपी सत्यापन</p>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2.5" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show rounded-3 small py-2.5" role="alert">
                            <i class="bi bi-check-circle-fill me-1"></i> <?php echo $success; ?>
                            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($step === 1): ?>
                        <!-- Step 1: Request Mobile -->
                        <form action="" method="POST">
                            <input type="hidden" name="action" value="verify_mobile">
                            <div class="mb-4">
                                <label for="mobile" class="form-label fw-bold small text-dark">पंजीकृत मोबाइल नंबर</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">+91</span>
                                    <input type="tel" name="mobile" id="mobile" class="form-control border-start-0 ps-0" placeholder="10-अंकों का मोबाइल दर्ज करें" required maxlength="10" autofocus value="<?php echo htmlspecialchars($mobile); ?>">
                                </div>
                                <small class="text-muted fs-7 mt-1 d-block">हम आपके मोबाइल नंबर को सत्यापित करने के लिए 6-अंकों का ओटीपी कोड भेजेंगे।</small>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-3">
                                मोबाइल सत्यापन ओटीपी भेजें <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </form>

                    <?php elseif ($step === 2): ?>
                        <!-- Step 2: Enter & Verify OTP -->
                        <div class="alert alert-light border rounded-3 small py-2 mb-3 text-center">
                            <span class="text-muted">ओटीपी कोड भेजा गया: </span>
                            <strong class="text-dark">+91 <?php echo htmlspecialchars($mobile); ?></strong>
                            <a href="forgot-password.php?restart=1" class="ms-2 small text-primary text-decoration-none fw-semibold"><i class="bi bi-pencil-square me-1"></i>बदलें</a>
                        </div>

                        <form action="" method="POST">
                            <input type="hidden" name="action" value="verify_otp">
                            <div class="mb-4 text-center">
                                <label for="otp" class="form-label fw-bold small text-dark d-block">6-अंकों का ओटीपी कोड दर्ज करें</label>
                                <input type="text" name="otp" id="otp" class="form-control form-control-lg text-center font-monospace tracking-widest fw-bold border-primary" placeholder="000000" maxlength="6" required autofocus autocomplete="off" style="font-size: 1.6rem; letter-spacing: 0.5rem;">
                                <small class="text-muted fs-7 mt-2 d-block">10 मिनट के लिए वैध</small>
                            </div>

                            <button type="submit" class="btn btn-success w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-3">
                                ओटीपी सत्यापित करें और आगे बढ़ें <i class="bi bi-check-circle ms-1"></i>
                            </button>
                        </form>

                        <form action="" method="POST" class="text-center">
                            <input type="hidden" name="action" value="resend_otp">
                            <button type="submit" class="btn btn-link btn-sm text-primary text-decoration-none fw-semibold">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> ओटीपी पुनः भेजें
                            </button>
                        </form>

                    <?php elseif ($step === 3): ?>
                        <!-- Step 3: Set New Password -->
                        <form action="" method="POST">
                            <input type="hidden" name="action" value="reset_password">
                            
                            <div class="mb-3">
                                <label for="new_password" class="form-label fw-bold small text-dark">नया पासवर्ड <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="new_password" id="new_password" class="form-control border-start-0 ps-0" placeholder="कम से कम 6 अक्षर" required autofocus>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="confirm_password" class="form-label fw-bold small text-dark">नए पासवर्ड की पुष्टि करें <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-check2-circle"></i></span>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control border-start-0 ps-0" placeholder="पुनः नया पासवर्ड दर्ज करें" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning text-dark w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-3">
                                <i class="bi bi-check-circle-fill me-1"></i> नया पासवर्ड सहेजें
                            </button>
                        </form>

                    <?php elseif ($step === 4): ?>
                        <!-- Step 4: Finished -->
                        <div class="text-center py-3">
                            <div class="text-success display-4 mb-3"><i class="bi bi-check-circle-fill"></i></div>
                            <h5 class="fw-bold text-dark mb-3">पासवर्ड सफलतापूर्वक अपडेट हो गया!</h5>
                            <a href="login" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                                अब लॉगिन करें <i class="bi bi-box-arrow-in-right ms-1"></i>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="text-center pt-3 border-top">
                        <a href="login" class="fw-bold text-muted text-decoration-none small">
                            <i class="bi bi-arrow-left me-1"></i> लॉगिन पेज पर वापस जाएं
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

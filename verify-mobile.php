<?php

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/sms_helper.php';

// Must be logged in
if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Fetch user
$db   = getDB();
$stmt = $db->prepare("SELECT id, full_name, mobile, mobile_status FROM users WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: login.php");
    exit;
}

// Already verified → go to dashboard
if ($user['mobile_status'] === 'VERIFIED') {
    header("Location: dashboard.php");
    exit;
}

$error   = '';
$success = '';
$otp_sent = !empty($_SESSION['otp_code']) && !empty($_SESSION['otp_expiry']) && time() < $_SESSION['otp_expiry'];

// ─── Send OTP ─────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_otp') {
    $cleanMob = preg_replace('/[^0-9]/', '', $user['mobile']);
    if (strlen($cleanMob) >= 10) {
        $cleanMob = substr($cleanMob, -10);
    }
    $otp = generateMobileOTP($cleanMob, $user['full_name']);
    $success  = "OTP sent to +91 {$cleanMob}. Valid for 10 minutes.";
    $otp_sent = true;
}

// ─── Verify OTP ───────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verify_otp') {
    $inputOtp = trim($_POST['otp_code'] ?? '');
    $res = verifyMobileOTP($user['mobile'], $inputOtp);

    if ($res['success']) {
        // ✅ OTP Correct → mark mobile as VERIFIED
        $upd = $db->prepare("UPDATE users SET mobile_status = 'VERIFIED' WHERE id = :id");
        $upd->execute(['id' => $user['id']]);

        unset($_SESSION['otp_code'], $_SESSION['otp_expiry'], $_SESSION['otp_mobile']);

        header("Location: dashboard.php?verified=1");
        exit;
    } else {
        $error    = $res['message'];
        $otp_sent = !empty($_SESSION['otp_code']) && !empty($_SESSION['otp_expiry']) && time() < $_SESSION['otp_expiry'];
    }
}

// ─── Skip / Verify Later ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'skip') {
    header("Location: dashboard.php");
    exit;
}

$page_title       = "Verify Mobile – Saran Index";
$meta_description = "Verify your mobile number to complete your Saran Index account setup.";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="card-header bg-dark text-white text-center py-4 border-0">
                    <div class="mb-2">
                        <span class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center" style="width:52px;height:52px;">
                            <i class="bi bi-phone-fill fs-4"></i>
                        </span>
                    </div>
                    <h4 class="fw-bold font-heading mb-1 text-white">Verify Your Mobile</h4>
                    <p class="text-white-50 small mb-0">One-time verification to secure your account</p>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">

                    <!-- Mobile display -->
                    <div class="text-center mb-4">
                        <div class="text-muted small mb-1">Registered Mobile</div>
                        <div class="fs-4 fw-bolder text-dark">+91 <?php echo htmlspecialchars($user['mobile']); ?></div>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 small" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success rounded-3 small" role="alert">
                            <i class="bi bi-check-circle-fill me-1"></i> <?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!$otp_sent): ?>
                        <!-- Step 1: Send OTP -->
                        <form action="" method="POST">
                            <input type="hidden" name="action" value="send_otp">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm mb-3">
                                <i class="bi bi-send-fill me-1"></i> Send OTP to My Mobile
                            </button>
                        </form>
                    <?php else: ?>
                        <!-- Step 2: Enter OTP -->
                        <form action="" method="POST">
                            <input type="hidden" name="action" value="verify_otp">
                            <div class="mb-4">
                                <label for="otp_code" class="form-label fw-bold small text-dark text-center d-block">Enter 6-digit OTP</label>
                                <input type="text" name="otp_code" id="otp_code"
                                       class="form-control text-center fw-bold fs-3 rounded-3 py-2 letter-spacing-wide"
                                       placeholder="— — — — — —"
                                       maxlength="6" required autofocus autocomplete="one-time-code"
                                       style="letter-spacing: 0.4em;">
                                <div class="form-text text-muted text-center mt-1">OTP expires in 10 minutes</div>
                            </div>
                            <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold shadow-sm mb-2">
                                <i class="bi bi-shield-check-fill me-1"></i> Verify Mobile
                            </button>
                        </form>

                        <!-- Resend OTP -->
                        <form action="" method="POST" class="text-center mt-1">
                            <input type="hidden" name="action" value="send_otp">
                            <button type="submit" class="btn btn-link text-muted small text-decoration-none">
                                <i class="bi bi-arrow-repeat me-1"></i> Resend OTP
                            </button>
                        </form>
                    <?php endif; ?>

                    <!-- Skip for now -->
                    <form action="" method="POST" class="text-center mt-3 pt-3 border-top">
                        <input type="hidden" name="action" value="skip">
                        <button type="submit" class="btn btn-link text-muted small text-decoration-none">
                            Skip for now, verify later
                        </button>
                    </form>

                </div>

                <div class="card-footer bg-light text-center py-3 border-0">
                    <span class="small text-muted"><i class="bi bi-shield-lock-fill text-success me-1"></i> Your number is only used for account security</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Allow digits only in OTP box
const otpInput = document.getElementById('otp_code');
if (otpInput) {
    otpInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
        // Auto-submit when 6 digits entered
        if (this.value.length === 6) {
            this.closest('form').submit();
        }
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

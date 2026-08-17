<?php
/**
 * Email Verification Gateway (Users & Business Listings)
 * Saran Index - Digital Directory
 */

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/email_helper.php';

$msg = '';
$msg_type = 'info';
$verified = false;
$target_type = $_GET['type'] ?? $_POST['type'] ?? 'user';
$token = trim($_GET['token'] ?? $_POST['token'] ?? '');
$email = trim($_GET['email'] ?? $_POST['email'] ?? '');

// Auto-verify if token is present in URL GET request
if (!empty($token)) {
    if ($target_type === 'listing') {
        $res = verifyListingEmailToken($token, $email);
    } else {
        $res = verifyUserEmailToken($token, $email);
    }

    if ($res['success']) {
        $msg = $res['message'];
        $msg_type = 'success';
        $verified = true;
    } else {
        $msg = $res['message'];
        $msg_type = 'danger';
    }
}

// Handle Manual Code Verification Form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_otp_verify'])) {
    $target_type = sanitizeInput($_POST['type'] ?? 'user');
    $otp_code = trim($_POST['otp_code'] ?? '');
    $user_email = trim($_POST['user_email'] ?? '');

    if (empty($otp_code)) {
        $msg = "Please enter your 6-digit verification code.";
        $msg_type = "danger";
    } else {
        if ($target_type === 'listing') {
            $res = verifyListingEmailToken($otp_code, $user_email);
        } else {
            $res = verifyUserEmailToken($otp_code, $user_email);
        }

        if ($res['success']) {
            $msg = $res['message'];
            $msg_type = 'success';
            $verified = true;
        } else {
            $msg = $res['message'];
            $msg_type = 'danger';
        }
    }
}

// Handle Resend Verification Email POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend_verification'])) {
    $resend_email = trim($_POST['resend_email'] ?? '');
    $resend_type = sanitizeInput($_POST['type'] ?? 'user');

    if (empty($resend_email)) {
        $msg = "Please enter your email address to receive a new verification email.";
        $msg_type = "danger";
    } else {
        if ($resend_type === 'listing') {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM listings WHERE email = :em LIMIT 1");
            $stmt->execute(['em' => $resend_email]);
            $item = $stmt->fetch();
            if ($item) {
                $sendRes = sendListingEmailVerification($item);
                $msg = "New listing verification email sent to {$resend_email}. Code: {$sendRes['otp']}";
                $msg_type = "success";
            } else {
                $msg = "No listing found matching email address '{$resend_email}'.";
                $msg_type = "danger";
            }
        } else {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM users WHERE email = :em LIMIT 1");
            $stmt->execute(['em' => $resend_email]);
            $item = $stmt->fetch();
            if ($item) {
                $sendRes = sendUserEmailVerification($item);
                $msg = "New user account verification email sent to {$resend_email}. Code: {$sendRes['otp']}";
                $msg_type = "success";
            } else {
                $msg = "No user account found matching email address '{$resend_email}'.";
                $msg_type = "danger";
            }
        }
    }
}

$page_title = "Email Verification - Saran Index";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-4 text-center">
                    <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 64px; height: 64px;">
                        <i class="bi bi-envelope-check-fill fs-2"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Email Verification Gateway</h4>
                    <p class="mb-0 small text-white-50">Verify User Accounts & Business Listings in Saran District</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if (!empty($msg)): ?>
                        <div class="alert alert-<?php echo $msg_type; ?> d-flex align-items-start p-3 rounded-3 mb-4 shadow-sm" role="alert">
                            <i class="bi bi-info-circle-fill me-2 fs-5 mt-0.5"></i>
                            <div><?php echo sanitizeInput($msg); ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if ($verified): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-patch-check-fill text-success display-1 mb-3 d-block"></i>
                            <h4 class="fw-bold text-dark mb-2">Email Address Verified!</h4>
                            <p class="text-muted small mb-4">Your email address has been confirmed and updated to <span class="badge bg-success">VERIFIED</span> status.</p>
                            
                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                                <a href="login.php" class="btn btn-primary btn-lg fw-bold px-4 rounded-3 shadow-sm">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Log In to Dashboard
                                </a>
                                <a href="index.php" class="btn btn-outline-secondary btn-lg fw-bold px-4 rounded-3">
                                    <i class="bi bi-house me-2"></i>Go to Homepage
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Manual Code Entry Form -->
                        <form action="verify_email.php" method="POST" class="mb-4">
                            <input type="hidden" name="submit_otp_verify" value="1">
                            
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Verification Target</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="type" id="type_user" value="user" <?php echo ($target_type !== 'listing') ? 'checked' : ''; ?>>
                                    <label class="btn btn-outline-primary fw-semibold" for="type_user"><i class="bi bi-person me-1"></i> User Account</label>
                                    
                                    <input type="radio" class="btn-check" name="type" id="type_listing" value="listing" <?php echo ($target_type === 'listing') ? 'checked' : ''; ?>>
                                    <label class="btn btn-outline-success fw-semibold" for="type_listing"><i class="bi bi-shop me-1"></i> Business Listing</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="otp_code" class="form-label small fw-semibold text-secondary">6-Digit Verification OTP Code</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-shield-lock"></i></span>
                                    <input type="text" class="form-control form-control-lg bg-light font-monospace fw-bold tracking-wider" id="otp_code" name="otp_code" placeholder="e.g. 682941" maxlength="32" required autofocus>
                                </div>
                                <div class="form-text text-muted small">Enter the 6-digit code or 32-char token sent to your email.</div>
                            </div>

                            <div class="mb-4">
                                <label for="user_email" class="form-label small fw-semibold text-secondary">Email Address (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control bg-light" id="user_email" name="user_email" value="<?php echo sanitizeInput($email); ?>" placeholder="name@example.com">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm mb-3">
                                <i class="bi bi-check-circle-fill me-2"></i>Verify Email Address Now
                            </button>
                        </form>

                        <div class="border-top pt-4 mt-4">
                            <h6 class="fw-bold small text-dark mb-2"><i class="bi bi-arrow-repeat me-1 text-primary"></i>Didn't receive the verification email?</h6>
                            <form action="verify_email.php" method="POST" class="row g-2">
                                <input type="hidden" name="resend_verification" value="1">
                                <input type="hidden" name="type" value="<?php echo sanitizeInput($target_type); ?>">
                                <div class="col-8">
                                    <input type="email" name="resend_email" class="form-control form-control-sm" placeholder="Enter registered email" required>
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm w-100 fw-semibold">Resend Email</button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
/**
 * Admin Password Recovery & Reset
 * Saran Index - Digital Directory
 */

require_once __DIR__ . '/../includes/functions.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$msg = '';
$msg_type = 'danger';
$step = 1;
$identifier_val = '';
$otp_generated = '';

// Step 1: Handle OTP Generation POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_otp'])) {
    $identifier = trim($_POST['identifier'] ?? '');
    $identifier_val = $identifier;

    if (empty($identifier)) {
        $msg = "Please enter your admin Username, Email address, or Mobile number.";
        $msg_type = "danger";
    } else {
        $res = generateAdminResetOTP($identifier);
        if ($res) {
            $otp_generated = $res['otp'];
            $admin_user = $res['admin'];
            $msg = "Security Recovery OTP code generated successfully! Enter the 6-digit OTP code below to reset your password.";
            $msg_type = "success";
            $step = 2;
        } else {
            $msg = "No admin account matching '{$identifier}' was found in the system.";
            $msg_type = "danger";
        }
    }
}

// Step 2: Handle Password Reset POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $identifier = trim($_POST['identifier'] ?? '');
    $otp_code = trim($_POST['otp_code'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $identifier_val = $identifier;
    $step = 2;

    if (empty($identifier) || empty($otp_code) || empty($new_password) || empty($confirm_password)) {
        $msg = "Please complete all required fields.";
        $msg_type = "danger";
    } elseif ($new_password !== $confirm_password) {
        $msg = "New password and Confirm password do not match.";
        $msg_type = "danger";
    } elseif (strlen($new_password) < 6) {
        $msg = "New password must be at least 6 characters long.";
        $msg_type = "danger";
    } else {
        $res = resetAdminPasswordWithOTP($identifier, $otp_code, $new_password);
        if ($res['success']) {
            $msg = $res['message'];
            $msg_type = "success";
            $step = 3; // Reset complete
        } else {
            $msg = $res['message'];
            $msg_type = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Admin Password - Saran Index</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .recovery-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1.25rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            max-width: 440px;
            width: 100%;
            padding: 2.5rem 2rem;
        }
        .brand-icon {
            width: 56px;
            height: 56px;
            background: #F59E0B;
            border-radius: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #FFF;
            font-size: 1.75rem;
            font-weight: 800;
            box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.4);
        }
    </style>
</head>
<body>

<div class="recovery-card">
    <div class="text-center mb-4">
        <div class="brand-icon mb-3">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <h4 class="fw-bold text-dark mb-1">Recover Admin Password</h4>
        <p class="text-muted small">Reset your administrator account credentials safely</p>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-<?php echo $msg_type; ?> d-flex align-items-start py-2.5 px-3 small rounded-3 mb-3" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-6 mt-0.5"></i>
            <div><?php echo sanitizeInput($msg); ?></div>
        </div>
    <?php endif; ?>

    <?php if (!empty($otp_generated)): ?>
        <!-- Display Generated OTP Box -->
        <div class="card border-warning bg-warning-subtle text-dark p-3 mb-3 text-center rounded-3">
            <small class="text-uppercase text-muted fw-bold" style="letter-spacing: 0.05em;">Your Security Recovery OTP Code</small>
            <div class="display-6 fw-bold text-dark my-1 tracking-wider"><?php echo $otp_generated; ?></div>
            <small class="text-muted fs-7"><i class="bi bi-clock me-1"></i>Valid for 30 minutes</small>
        </div>
    <?php endif; ?>

    <?php if ($step === 1): ?>
        <!-- Step 1 Form: Request Recovery Code -->
        <form action="forgot_password.php" method="POST">
            <input type="hidden" name="request_otp" value="1">
            <div class="mb-4">
                <label for="identifier" class="form-label small fw-semibold text-secondary">Username, Email or Mobile</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control bg-light border-start-0" id="identifier" name="identifier" value="<?php echo sanitizeInput($identifier_val); ?>" placeholder="e.g. admin or admin@saranindex.com" required autofocus>
                </div>
                <div class="form-text text-muted small mt-1">Enter any credential linked to your admin account.</div>
            </div>

            <button type="submit" class="btn btn-warning text-dark w-100 py-2.5 fw-bold rounded-3 shadow-sm mb-3">
                <i class="bi bi-send me-2"></i>Generate Recovery Code
            </button>
        </form>

    <?php elseif ($step === 2): ?>
        <!-- Step 2 Form: Enter OTP & New Password -->
        <form action="forgot_password.php" method="POST">
            <input type="hidden" name="reset_password" value="1">
            <input type="hidden" name="identifier" value="<?php echo sanitizeInput($identifier_val); ?>">

            <div class="mb-3">
                <label for="otp_code" class="form-label small fw-semibold text-secondary">6-Digit OTP Recovery Code</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-check"></i></span>
                    <input type="text" class="form-control bg-light border-start-0 fw-bold tracking-wider" id="otp_code" name="otp_code" value="<?php echo sanitizeInput($otp_generated); ?>" placeholder="e.g. 123456" maxlength="6" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label small fw-semibold text-secondary">New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control bg-light border-start-0" id="new_password" name="new_password" placeholder="At least 6 characters" minlength="6" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="form-label small fw-semibold text-secondary">Confirm New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control bg-light border-start-0" id="confirm_password" name="confirm_password" placeholder="Re-type new password" minlength="6" required>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2.5 fw-bold rounded-3 shadow-sm mb-3">
                <i class="bi bi-check-circle me-2"></i>Reset Password Now
            </button>
        </form>

    <?php elseif ($step === 3): ?>
        <!-- Step 3: Success View -->
        <div class="text-center py-3">
            <i class="bi bi-check-circle-fill text-success fs-1 mb-3 d-block"></i>
            <h5 class="fw-bold mb-2">Password Reset Successful!</h5>
            <p class="text-muted small mb-4">Your admin credentials have been safely updated.</p>
            <a href="login.php" class="btn btn-primary w-100 py-2.5 fw-bold rounded-3 shadow-sm">
                <i class="bi bi-box-arrow-in-right me-2"></i>Proceed to Admin Login
            </a>
        </div>
    <?php endif; ?>

    <div class="mt-4 pt-3 border-top text-center">
        <a href="login.php" class="text-secondary small text-decoration-none fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Back to Admin Login
        </a>
    </div>
</div>

</body>
</html>

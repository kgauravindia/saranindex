<?php
$header_title = "OTP & Verification Settings";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/sms_helper.php';

$msg = '';
$msg_type = 'success';

// Handle Test OTP Dispatch
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_sms_otp'])) {
    $test_mobile = trim($_POST['test_mobile'] ?? '');
    if (empty($test_mobile) || strlen($test_mobile) < 10) {
        $msg = "Please enter a valid 10-digit mobile number for testing.";
        $msg_type = "danger";
    } else {
        $test_code = rand(100000, 999999);
        $res = sendOTP($test_mobile, "Saran Citizen", $test_code);
        if (isset($res['status']) && $res['status'] === 'success') {
            $msg = "Test OTP SMS ({$test_code}) sent successfully to +91 {$test_mobile}! Response: " . ($res['msg'] ?? 'OK');
            $msg_type = "success";
        } else {
            $msg = "SMS Dispatch Failed: " . ($res['msg'] ?? 'Gateway connection error.');
            $msg_type = "danger";
        }
    }
}

// Handle Test Verification Email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_email_verify'])) {
    $test_email = trim($_POST['test_email'] ?? '');
    if (empty($test_email) || !filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Please enter a valid email address.";
        $msg_type = "danger";
    } else {
        require_once __DIR__ . '/../includes/email_helper.php';
        $dummyUser = [
            'id' => 0,
            'full_name' => 'Test Recipient',
            'email' => $test_email
        ];
        $res = sendUserEmailVerification($dummyUser);
        if ($res['status'] === 'success') {
            $msg = "Test verification email (Code: {$res['otp']}) dispatched successfully to {$test_email}!";
            $msg_type = "success";
        } else {
            $msg = "Failed to send test verification email: " . $res['msg'];
            $msg_type = "danger";
        }
    }
}

// Fetch recent SMS logs if log file exists
$sms_logs = [];
$log_path = __DIR__ . '/../sms_debug.log';
if (file_exists($log_path)) {
    $lines = file($log_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines) {
        $sms_logs = array_reverse(array_slice($lines, -15));
    }
}

// Fetch recent Email logs if log file exists
$email_logs = [];
$email_log_path = __DIR__ . '/../email_debug.log';
if (file_exists($email_log_path)) {
    $e_lines = file($email_log_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($e_lines) {
        $email_logs = array_reverse(array_slice($e_lines, -15));
    }
}
?>

<!-- Header Banner -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Admin</a></li>
                <li class="breadcrumb-item"><a href="users.php" class="text-decoration-none text-muted">Users</a></li>
                <li class="breadcrumb-item active fw-semibold text-primary" aria-current="page">Verification Settings</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0 text-dark">OTP & Email Verification Control Center</h4>
        <p class="text-muted small mb-0">Manage SMS OTP Gateway, DLT Template IDs, Email Verification, and test live OTP dispatch.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="users.php" class="btn btn-outline-secondary btn-sm px-3 rounded-3 fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Back to Users
        </a>
    </div>
</div>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- SMS OTP Gateway Card (6 Columns) -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-chat-left-text-fill text-primary me-2"></i>Mobile SMS OTP Gateway Settings</h6>
            
            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted mb-1">SMS API Provider Endpoint</label>
                <input type="text" class="form-control form-control-sm bg-light" value="http://msg.morg.in/rest/services/sendSMS/sendGroupSms" readonly>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12 col-md-7">
                    <label class="form-label small fw-semibold text-muted mb-1">SMS Auth Key</label>
                    <input type="text" class="form-control form-control-sm bg-light font-monospace" value="<?php echo defined('SMS_AUTH_KEY') ? SMS_AUTH_KEY : ''; ?>" readonly>
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label small fw-semibold text-muted mb-1">Sender ID (DLT Header)</label>
                    <input type="text" class="form-control form-control-sm bg-light font-monospace text-primary fw-bold" value="<?php echo defined('SMS_SENDER_ID') ? SMS_SENDER_ID : 'SARDEX'; ?>" readonly>
                </div>
            </div>

            <h6 class="fw-semibold text-dark small border-bottom pb-1 mb-2 mt-4"><i class="bi bi-file-earmark-code text-secondary me-1"></i>DLT Template Configurations</h6>

            <div class="mb-2">
                <label class="form-label small text-muted mb-0">OTP Template ID (SARDEX_OTP)</label>
                <input type="text" class="form-control form-control-sm bg-light font-monospace" value="<?php echo defined('SMS_TEMPLATE_OTP') ? SMS_TEMPLATE_OTP : ''; ?>" readonly>
            </div>
            <div class="mb-2">
                <label class="form-label small text-muted mb-0">Registration Welcome Template ID (SARDEX_REG)</label>
                <input type="text" class="form-control form-control-sm bg-light font-monospace" value="<?php echo defined('SMS_TEMPLATE_REG') ? SMS_TEMPLATE_REG : ''; ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label small text-muted mb-0">Listing Notification Template ID (SARDEX_POST)</label>
                <input type="text" class="form-control form-control-sm bg-light font-monospace" value="<?php echo defined('SMS_TEMPLATE_POST') ? SMS_TEMPLATE_POST : ''; ?>" readonly>
            </div>

            <!-- Instant Test OTP Dispatch Form -->
            <div class="bg-light p-3 rounded-3 border mt-4">
                <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-send-fill text-success me-1"></i>Send Test OTP SMS</h6>
                <form action="verification_settings.php" method="POST">
                    <input type="hidden" name="test_sms_otp" value="1">
                    <div class="input-group">
                        <span class="input-group-text bg-white small fw-bold">+91</span>
                        <input type="text" name="test_mobile" class="form-control form-control-sm" placeholder="Enter 10-digit mobile number" required>
                        <button type="submit" class="btn btn-success btn-sm fw-bold px-3"><i class="bi bi-cursor-fill me-1"></i> Dispatch OTP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Email Verification Settings Card (6 Columns) -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-envelope-check-fill text-primary me-2"></i>Email Verification & SMTP Settings</h6>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-muted mb-1">Sender Email Address</label>
                <input type="email" class="form-control form-control-sm bg-light font-monospace text-primary fw-bold" value="<?php echo defined('SYSTEM_FROM_EMAIL') ? SYSTEM_FROM_EMAIL : 'info@saranindex.com'; ?>" readonly>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12 col-md-7">
                    <label class="form-label small fw-semibold text-muted mb-1">SMTP Host Server</label>
                    <input type="text" class="form-control form-control-sm bg-light font-monospace" value="<?php echo defined('SMTP_HOST') ? SMTP_HOST : 'smtp.hostinger.com'; ?>" readonly>
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label small fw-semibold text-muted mb-1">SMTP User / Auth</label>
                    <input type="text" class="form-control form-control-sm bg-light font-monospace" value="<?php echo defined('SMTP_USER') ? SMTP_USER : 'info@saranindex.com'; ?>" readonly>
                </div>
            </div>

            <!-- Instant Test Email Form -->
            <div class="bg-light p-3 rounded-3 border mt-4">
                <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-send-fill text-primary me-1"></i>Send Test Verification Email</h6>
                <form action="verification_settings.php" method="POST">
                    <input type="hidden" name="test_email_verify" value="1">
                    <div class="input-group">
                        <input type="email" name="test_email" class="form-control form-control-sm" placeholder="Enter test email address" required>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3"><i class="bi bi-send me-1"></i> Send Test Email</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recent SMS Gateway Debug Logs -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-journal-code text-warning me-2"></i>Recent SMS Gateway Debug Logs</h6>

            <?php if (empty($sms_logs)): ?>
                <div class="text-muted small py-3 text-center">No SMS logs recorded yet.</div>
            <?php else: ?>
                <div class="bg-dark text-light p-3 rounded-3 font-monospace small overflow-x-auto" style="max-height: 220px; font-size: 0.78rem;">
                    <?php foreach ($sms_logs as $log): ?>
                        <div class="mb-1 text-nowrap"><?php echo sanitizeInput($log); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Email Dispatch Debug Logs -->
        <div class="card border-0 shadow-sm rounded-3 p-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-envelope-paper-fill text-info me-2"></i>Recent Email Verification Debug Logs</h6>

            <?php if (empty($email_logs)): ?>
                <div class="text-muted small py-3 text-center">No Email logs recorded yet.</div>
            <?php else: ?>
                <div class="bg-dark text-light p-3 rounded-3 font-monospace small overflow-x-auto" style="max-height: 220px; font-size: 0.78rem;">
                    <?php foreach ($email_logs as $log): ?>
                        <div class="mb-1 text-nowrap"><?php echo sanitizeInput($log); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

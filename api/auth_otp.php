<?php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/sms_helper.php';
require_once __DIR__ . '/../includes/email_helper.php';

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);
if (!is_array($data)) {
    $data = $_POST;
}

$action = trim($data['action'] ?? $_GET['action'] ?? '');

if (empty($action)) {
    echo json_encode(['success' => false, 'message' => 'Invalid action requested.']);
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// ACTION: SEND OTP
// ─────────────────────────────────────────────────────────────────────────────
if ($action === 'send_otp') {
    $identifier = trim($data['identifier'] ?? '');
    if (empty($identifier)) {
        echo json_encode(['success' => false, 'message' => 'Please enter your mobile number, email, or @username.']);
        exit;
    }

    $user = findUserByIdentifier($identifier);

    if (!$user) {
        $cleanMob = preg_replace('/[^0-9]/', '', $identifier);
        $mob10 = (strlen($cleanMob) >= 10) ? substr($cleanMob, -10) : '';

        echo json_encode([
            'success' => false,
            'not_registered' => true,
            'mobile' => $mob10,
            'message' => 'No account found with this mobile number or email. Please register to create your account.'
        ]);
        exit;
    }

    if (isset($user['status']) && strtoupper($user['status']) !== 'ACTIVE') {
        echo json_encode([
            'success' => false,
            'message' => 'Your account is currently ' . htmlspecialchars($user['status']) . '. Please contact Saran Index support.'
        ]);
        exit;
    }

    $userMobile = preg_replace('/[^0-9]/', '', (string)$user['mobile']);
    if (strlen($userMobile) >= 10) {
        $userMobile = substr($userMobile, -10);
    }

    if (empty($userMobile) || strlen($userMobile) < 10) {
        echo json_encode([
            'success' => false,
            'message' => 'No valid mobile number associated with this account. Please use password login.'
        ]);
        exit;
    }

    // Rate limiting: minimum 30 seconds between OTP requests
    $lastSent = $_SESSION['otp_last_sent'] ?? 0;
    if (time() - $lastSent < 25) {
        $waitSec = 30 - (time() - $lastSent);
        echo json_encode([
            'success' => false,
            'message' => "Please wait {$waitSec} seconds before requesting a new OTP."
        ]);
        exit;
    }

    $userName = !empty($user['full_name']) ? $user['full_name'] : ($user['name'] ?? 'User');
    $otp = generateMobileOTP($userMobile, $userName);

    $_SESSION['login_otp_user_id'] = $user['id'];
    $_SESSION['login_otp_mobile']  = $userMobile;
    $_SESSION['otp_last_sent']     = time();

    // Optionally send email OTP if user has email configured
    if (!empty($user['email']) && filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
        try {
            $emailSubject = "Your Login OTP for Saran Index: {$otp}";
            $emailBody = "
            <div style='font-family: Arial, sans-serif; max-width: 520px; margin: 0 auto; padding: 25px; border: 1px solid #E2E8F0; border-radius: 12px; background: #ffffff;'>
                <div style='text-align: center; margin-bottom: 20px;'>
                    <h2 style='color: #0F172A; margin: 0;'>Saran Index</h2>
                    <p style='color: #64748B; font-size: 14px; margin: 4px 0 0 0;'>Digital Directory of Saran District</p>
                </div>
                <div style='background: #F8FAFC; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0;'>
                    <p style='color: #334155; font-size: 15px; margin: 0 0 10px 0;'>Hello <strong>" . htmlspecialchars($userName) . "</strong>,</p>
                    <p style='color: #64748B; font-size: 14px; margin: 0 0 15px 0;'>Use the following 6-digit One-Time Password (OTP) to securely log in to your account:</p>
                    <div style='font-size: 32px; font-weight: bold; letter-spacing: 6px; color: #2563EB; padding: 10px 0;'>" . htmlspecialchars($otp) . "</div>
                    <p style='color: #94A3B8; font-size: 12px; margin: 15px 0 0 0;'>This code is valid for 10 minutes. Never share this code with anyone.</p>
                </div>
                <p style='color: #94A3B8; font-size: 12px; text-align: center; margin: 0;'>If you did not request this login, please secure your account immediately.</p>
            </div>";
            sendSystemEmail($user['email'], $userName, $emailSubject, $emailBody);
        } catch (Exception $e) {
            error_log("Login OTP Email dispatch error: " . $e->getMessage());
        }
    }

    $maskedMobile = '+91 •••••• ' . substr($userMobile, -4);

    echo json_encode([
        'success' => true,
        'message' => "OTP sent successfully to {$maskedMobile}",
        'masked_mobile' => $maskedMobile,
        'mobile_raw' => $userMobile,
        'user_name' => $userName,
        'cooldown' => 60
    ]);
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// ACTION: VERIFY OTP & LOG IN
// ─────────────────────────────────────────────────────────────────────────────
if ($action === 'verify_otp') {
    $otpCode = trim($data['otp_code'] ?? '');
    $rememberMe = !empty($data['remember_me']);

    if (empty($otpCode) || strlen($otpCode) !== 6) {
        echo json_encode(['success' => false, 'message' => 'Please enter the complete 6-digit OTP.']);
        exit;
    }

    $userId = $_SESSION['login_otp_user_id'] ?? null;
    $userMobile = $_SESSION['login_otp_mobile'] ?? ($_SESSION['otp_mobile'] ?? null);

    if (!$userId || !$userMobile) {
        echo json_encode(['success' => false, 'message' => 'Session expired. Please request a new OTP.']);
        exit;
    }

    $res = verifyMobileOTP($userMobile, $otpCode);
    if (!$res['success']) {
        echo json_encode(['success' => false, 'message' => $res['message']]);
        exit;
    }

    // Fetch user details
    $db = getDB();
    if (!$db) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
        exit;
    }

    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User account not found.']);
        exit;
    }

    // Mark mobile as verified if not yet
    if (isset($user['mobile_status']) && $user['mobile_status'] !== 'VERIFIED') {
        try {
            $db->prepare("UPDATE users SET mobile_status = 'VERIFIED' WHERE id = :id")->execute(['id' => $user['id']]);
        } catch (Exception $e) {}
    }

    // Clear temporary login OTP session variables
    unset($_SESSION['login_otp_user_id']);
    unset($_SESSION['login_otp_mobile']);
    unset($_SESSION['otp_code']);
    unset($_SESSION['otp_expiry']);

    // Set authenticated user session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = !empty($user['full_name']) ? $user['full_name'] : ($user['name'] ?? 'User');
    $_SESSION['user_mobile'] = $user['mobile'];
    $_SESSION['user_handle'] = $user['username_handle'] ?? '';

    // Remember me cookie setup if checked
    if ($rememberMe) {
        $token = bin2hex(random_bytes(32));
        $cookiePayload = base64_encode($user['id'] . ':' . $token);
        setcookie('si_remember_token', $cookiePayload, time() + (30 * 86400), '/', '', false, true);
    }

    $redirectUrl = !empty($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'dashboard.php';
    unset($_SESSION['redirect_after_login']);

    echo json_encode([
        'success' => true,
        'message' => 'Login successful! Redirecting to dashboard...',
        'redirect' => $redirectUrl,
        'user_name' => $_SESSION['user_name']
    ]);
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// ACTION: RESEND OTP
// ─────────────────────────────────────────────────────────────────────────────
if ($action === 'resend_otp') {
    $userId = $_SESSION['login_otp_user_id'] ?? null;
    $userMobile = $_SESSION['login_otp_mobile'] ?? null;

    if (!$userId || !$userMobile) {
        echo json_encode(['success' => false, 'message' => 'Session expired. Please enter your mobile number again.']);
        exit;
    }

    $lastSent = $_SESSION['otp_last_sent'] ?? 0;
    if (time() - $lastSent < 25) {
        $waitSec = 30 - (time() - $lastSent);
        echo json_encode([
            'success' => false,
            'message' => "Please wait {$waitSec} seconds before resending OTP."
        ]);
        exit;
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT full_name, name FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $userId]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    $userName = $u['full_name'] ?? ($u['name'] ?? 'User');

    $otp = generateMobileOTP($userMobile, $userName);
    $_SESSION['otp_last_sent'] = time();

    $maskedMobile = '+91 •••••• ' . substr($userMobile, -4);

    echo json_encode([
        'success' => true,
        'message' => "New OTP code sent to {$maskedMobile}",
        'cooldown' => 60
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action requested.']);

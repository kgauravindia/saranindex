<?php
/**
 * Email Helper & Verification Dispatcher
 * Saran Index - Digital Directory (Powered by PHPMailer & Hostinger SMTP)
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/functions.php';

// Global variable to store last email sending error
$email_last_error = '';

/**
 * Get the last error encountered during email sending.
 */
function getEmailLastError() {
    global $email_last_error;
    return $email_last_error;
}

if (!function_exists('sendSystemEmail')) {
    /**
     * Send HTML system email using PHPMailer with Hostinger SMTP and fallback
     */
    function sendSystemEmail($to_email, $to_name, $subject, $body_html) {
        global $email_last_error;
        $email_last_error = '';

        $to_email = trim($to_email);
        if (empty($to_email) || !filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
            $email_last_error = 'Invalid destination email address.';
            return [
                'status' => 'error',
                'msg' => $email_last_error
            ];
        }

        // Load config/email.php or fallback to defined constants
        $config_file = __DIR__ . '/../config/email.php';
        $config = file_exists($config_file) ? include($config_file) : [];

        $host       = $config['host'] ?? (defined('SMTP_HOST') ? SMTP_HOST : 'smtp.hostinger.com');
        $port       = intval($config['port'] ?? (defined('SMTP_PORT') ? SMTP_PORT : 587));
        $username   = $config['username'] ?? (defined('SMTP_USER') ? SMTP_USER : 'info@saranindex.com');
        $password   = $config['password'] ?? (defined('SMTP_PASS') ? SMTP_PASS : 'Index@@2026');
        $encryption = strtolower($config['encryption'] ?? (defined('SMTP_SECURE') ? SMTP_SECURE : 'tls'));
        $from_email = $config['from_email'] ?? (defined('SYSTEM_FROM_EMAIL') ? SYSTEM_FROM_EMAIL : 'info@saranindex.com');
        $from_name  = $config['from_name'] ?? (defined('SYSTEM_FROM_NAME') ? SYSTEM_FROM_NAME : 'Saran Index');

        $use_smtp = $config['use_smtp'] ?? true;

        if ($use_smtp && !empty($password) && $password !== 'YOUR_HOSTINGER_EMAIL_PASSWORD') {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = $host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $username;
                $mail->Password   = $password;

                if ($encryption === 'ssl' || $port === 465) {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = $port ?: 465;
                } elseif ($encryption === 'tls' || $port === 587) {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = $port ?: 587;
                } else {
                    $mail->Port       = $port ?: 25;
                }

                $mail->Timeout = 12;
                $mail->CharSet = 'UTF-8';

                $mail->setFrom($from_email, $from_name);
                $mail->addAddress($to_email, $to_name ?: '');
                $mail->addReplyTo($from_email, $from_name);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $body_html;

                $mail->send();

                $log_entry = date('Y-m-d H:i:s') . " | Recipient: {$to_email} | Sender: {$from_email} | Transport: PHPMailer (Hostinger SMTP) | Subject: {$subject} | Status: SUCCESS" . PHP_EOL;
                @file_put_contents(__DIR__ . '/../email_debug.log', $log_entry, FILE_APPEND);

                return [
                    'status' => 'success',
                    'msg' => 'Email dispatched successfully via Hostinger SMTP (' . $from_email . ')',
                    'sent' => true,
                    'sender' => $from_email,
                    'transport' => 'PHPMAILER_HOSTINGER'
                ];
            } catch (Exception $e) {
                $email_last_error = "PHPMailer SMTP Error: " . $mail->ErrorInfo;
                error_log("PHPMailer SMTP Error for {$to_email}: " . $mail->ErrorInfo);
            }
        }

        // Fallback to PHP mail()
        $headers = "From: {$from_name} <{$from_email}>\r\n";
        $headers .= "Reply-To: {$from_email}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "X-Mailer: SaranIndex-PHP/" . phpversion() . "\r\n";

        $mailSent = @mail($to_email, $subject, $body_html, $headers);
        $status = $mailSent ? 'SUCCESS' : 'FAILED';
        $log_entry = date('Y-m-d H:i:s') . " | Recipient: {$to_email} | Sender: {$from_email} | Transport: PHP_MAIL_FALLBACK | Subject: {$subject} | Status: {$status} | Error: {$email_last_error}" . PHP_EOL;
        @file_put_contents(__DIR__ . '/../email_debug.log', $log_entry, FILE_APPEND);

        if ($mailSent) {
            return [
                'status' => 'success',
                'msg' => 'Email dispatched successfully via Server Mail',
                'sent' => true,
                'sender' => $from_email,
                'transport' => 'PHP_MAIL_FALLBACK'
            ];
        }

        return [
            'status' => 'error',
            'msg' => 'Email delivery failed: ' . ($email_last_error ?: 'Mail server unavailable.'),
            'sent' => false
        ];
    }
}

if (!function_exists('sendEmail')) {
    /**
     * Send email wrapper matching AdvocateIndex sendEmail signature
     */
    function sendEmail($to, $subject, $message, $name = '') {
        $res = sendSystemEmail($to, $name, $subject, $message);
        return ($res['status'] === 'success');
    }
}

if (!function_exists('ensureUsersEmailColumns')) {
    /**
     * Auto-migrate missing email verification columns in users table
     */
    function ensureUsersEmailColumns() {
        $db = getDB();
        if (!$db) return;
        try {
            $cols = $db->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('email_token', $cols)) {
                $db->exec("ALTER TABLE `users` ADD COLUMN `email_token` VARCHAR(100) DEFAULT NULL");
            }
            if (!in_array('email_token_expiry', $cols)) {
                $db->exec("ALTER TABLE `users` ADD COLUMN `email_token_expiry` DATETIME DEFAULT NULL");
            }
            if (!in_array('email_status', $cols)) {
                $db->exec("ALTER TABLE `users` ADD COLUMN `email_status` ENUM('UNVERIFIED','VERIFIED') DEFAULT 'UNVERIFIED'");
            }
        } catch (Exception $e) {
            error_log("ensureUsersEmailColumns error: " . $e->getMessage());
        }
    }
}

if (!function_exists('ensureListingsEmailColumns')) {
    /**
     * Auto-migrate missing email verification columns in listings table
     */
    function ensureListingsEmailColumns() {
        $db = getDB();
        if (!$db) return;
        try {
            $cols = $db->query("SHOW COLUMNS FROM listings")->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('email_token', $cols)) {
                $db->exec("ALTER TABLE `listings` ADD COLUMN `email_token` VARCHAR(100) DEFAULT NULL");
            }
            if (!in_array('email_token_expiry', $cols)) {
                $db->exec("ALTER TABLE `listings` ADD COLUMN `email_token_expiry` DATETIME DEFAULT NULL");
            }
            if (!in_array('email_status', $cols)) {
                $db->exec("ALTER TABLE `listings` ADD COLUMN `email_status` ENUM('UNVERIFIED','VERIFIED') DEFAULT 'UNVERIFIED'");
            }
        } catch (Exception $e) {
            error_log("ensureListingsEmailColumns error: " . $e->getMessage());
        }
    }
}

if (!function_exists('getEmailVerificationBaseUrl')) {
    /**
     * Compute clean public URL for verification links (always routes to live domain for real emails)
     */
    function getEmailVerificationBaseUrl() {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (empty($host) || strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false || php_sapi_name() === 'cli') {
            if (defined('REMOTE_LIVE_URL') && !empty(REMOTE_LIVE_URL)) {
                return rtrim(REMOTE_LIVE_URL, '/') . '/';
            }
            return 'https://saranindex.com/';
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $baseUrl = $protocol . $host . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\') . '/';
        $baseUrl = str_replace('/admin/', '/', $baseUrl);
        return $baseUrl;
    }
}

if (!function_exists('sendUserEmailVerification')) {
    /**
     * Generate token & OTP, send user email verification link & code
     */
    function sendUserEmailVerification($user_id_or_array) {
        $user = is_array($user_id_or_array) ? $user_id_or_array : getUserById($user_id_or_array);
        if (!$user || empty($user['email'])) {
            return ['status' => 'error', 'msg' => 'User or email address not found.'];
        }

        $db = getDB();
        if (!$db) return ['status' => 'error', 'msg' => 'Database connection failed.'];

        try {
            ensureUsersEmailColumns();

            $otp = sprintf("%06d", mt_rand(100000, 999999));
            $token = bin2hex(random_bytes(16)); // 32 chars

            // Store in users table
            $stmt = $db->prepare("UPDATE users SET token = :t, email_token = :et, email_token_expiry = DATE_ADD(NOW(), INTERVAL 24 HOUR) WHERE id = :id");
            $stmt->execute([
                't' => $otp,
                'et' => $token,
                'id' => $user['id']
            ]);

            // Build site base URL (live canonical domain for external recipients)
            $baseUrl = getEmailVerificationBaseUrl();
            $verifyLink = $baseUrl . "verify_email.php?type=user&token=" . urlencode($token);
            $user_name = !empty($user['full_name']) ? sanitizeInput($user['full_name']) : 'User';

            $body = '
            <!DOCTYPE html>
            <html>
            <head><meta charset="UTF-8"></head>
            <body style="font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px;">
                <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <div style="background: #2563eb; color: #ffffff; padding: 25px; text-align: center;">
                        <h2 style="margin: 0; font-size: 24px;">Saran Index</h2>
                        <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Digital District Directory of Saran</p>
                    </div>
                    <div style="padding: 30px;">
                        <h3 style="color: #1e293b; margin-top: 0;">Verify Your Email Address</h3>
                        <p style="color: #475569; line-height: 1.6;">Hello <strong>' . $user_name . '</strong>,</p>
                        <p style="color: #475569; line-height: 1.6;">Thank you for registering on Saran Index! Please verify your email address to complete your account setup and gain full access to directory features.</p>
                        
                        <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 20px; text-align: center; margin: 25px 0;">
                            <span style="font-size: 13px; text-transform: uppercase; color: #64748b; font-weight: bold; letter-spacing: 1px;">Your 6-Digit Email Verification Code</span>
                            <div style="font-size: 32px; font-weight: bold; color: #2563eb; letter-spacing: 5px; margin: 10px 0;">' . $otp . '</div>
                            <small style="color: #94a3b8;">Code valid for 24 hours</small>
                        </div>

                        <div style="text-align: center; margin: 30px 0;">
                            <a href="' . $verifyLink . '" style="background: #2563eb; color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 6px; font-weight: bold; display: inline-block;">Verify Email Address Now &rarr;</a>
                        </div>

                        <p style="color: #94a3b8; font-size: 13px; line-height: 1.5; margin-top: 25px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                            If button doesn\'t work, copy and paste this link into your browser:<br>
                            <a href="' . $verifyLink . '" style="color: #2563eb;">' . $verifyLink . '</a>
                        </p>
                    </div>
                    <div style="background: #f1f5f9; color: #64748b; padding: 15px; text-align: center; font-size: 12px;">
                        &copy; ' . date('Y') . ' Saran Index, Chapra, Bihar. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ';

            $res = sendSystemEmail($user['email'], $user_name, "Verify Your Email Address - Saran Index", $body);
            $res['otp'] = $otp;
            $res['token'] = $token;
            return $res;
        } catch (Exception $e) {
            error_log("sendUserEmailVerification error: " . $e->getMessage());
            return ['status' => 'error', 'msg' => 'Failed to generate verification token: ' . $e->getMessage()];
        }
    }
}

if (!function_exists('sendListingEmailVerification')) {
    /**
     * Generate token & OTP, send listing email verification link & code
     */
    function sendListingEmailVerification($listing_id_or_array) {
        $listing = is_array($listing_id_or_array) ? $listing_id_or_array : getListingById($listing_id_or_array);
        if (!$listing || empty($listing['email'])) {
            return ['status' => 'error', 'msg' => 'Listing or listing email address not found.'];
        }

        $db = getDB();
        if (!$db) return ['status' => 'error', 'msg' => 'Database connection failed.'];

        try {
            ensureListingsEmailColumns();
            $otp = sprintf("%06d", mt_rand(100000, 999999));
            $token = bin2hex(random_bytes(16));

            $stmt = $db->prepare("UPDATE listings SET email_token = :et, email_token_expiry = DATE_ADD(NOW(), INTERVAL 24 HOUR) WHERE id = :id");
            $stmt->execute([
                'et' => $token,
                'id' => $listing['id']
            ]);

            // Build site base URL (live canonical domain for external recipients)
            $baseUrl = getEmailVerificationBaseUrl();
            $verifyLink = $baseUrl . "verify_email.php?type=listing&token=" . urlencode($token);
            $title = !empty($listing['title']) ? sanitizeInput($listing['title']) : 'Business Listing';

            $body = '
            <!DOCTYPE html>
            <html>
            <head><meta charset="UTF-8"></head>
            <body style="font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px;">
                <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <div style="background: #059669; color: #ffffff; padding: 25px; text-align: center;">
                        <h2 style="margin: 0; font-size: 24px;">Saran Index</h2>
                        <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Business Verification Service</p>
                    </div>
                    <div style="padding: 30px;">
                        <h3 style="color: #1e293b; margin-top: 0;">Verify Listing Email: ' . $title . '</h3>
                        <p style="color: #475569; line-height: 1.6;">Hello,</p>
                        <p style="color: #475569; line-height: 1.6;">Please verify the contact email address for your business listing <strong>"' . $title . '"</strong> on Saran Index to earn the Verified Business Badge.</p>
                        
                        <div style="background: #ecfdf5; border: 1px dashed #a7f3d0; border-radius: 8px; padding: 20px; text-align: center; margin: 25px 0;">
                            <span style="font-size: 13px; text-transform: uppercase; color: #047857; font-weight: bold; letter-spacing: 1px;">Listing Verification Code</span>
                            <div style="font-size: 32px; font-weight: bold; color: #059669; letter-spacing: 5px; margin: 10px 0;">' . $otp . '</div>
                            <small style="color: #065f46;">Code valid for 24 hours</small>
                        </div>

                        <div style="text-align: center; margin: 30px 0;">
                            <a href="' . $verifyLink . '" style="background: #059669; color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 6px; font-weight: bold; display: inline-block;">Verify Business Listing Email &rarr;</a>
                        </div>

                        <p style="color: #94a3b8; font-size: 13px; line-height: 1.5; margin-top: 25px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                            Verification link:<br>
                            <a href="' . $verifyLink . '" style="color: #059669;">' . $verifyLink . '</a>
                        </p>
                    </div>
                    <div style="background: #f1f5f9; color: #64748b; padding: 15px; text-align: center; font-size: 12px;">
                        &copy; ' . date('Y') . ' Saran Index, Chapra, Bihar. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ';

            $res = sendSystemEmail($listing['email'], $title, "Verify Business Listing Email - Saran Index", $body);
            $res['otp'] = $otp;
            $res['token'] = $token;
            return $res;
        } catch (PDOException $e) {
            error_log("sendListingEmailVerification error: " . $e->getMessage());
        }

        return ['status' => 'error', 'msg' => 'Failed to generate listing verification token.'];
    }
}

if (!function_exists('verifyUserEmailToken')) {
    /**
     * Validate user email verification token or OTP
     */
    function verifyUserEmailToken($token_or_otp, $email = null) {
        $db = getDB();
        if (!$db) return ['success' => false, 'message' => 'Database connection failed.'];

        try {
            ensureUsersEmailColumns();
            $input = trim($token_or_otp);
            if (empty($input)) {
                return ['success' => false, 'message' => 'Please provide a valid verification token or OTP code.'];
            }

            $sql = "SELECT * FROM users WHERE (token = :tok OR email_token = :etok)";
            $params = [
                'tok' => $input,
                'etok' => $input
            ];

            if (!empty($email)) {
                $sql .= " AND email = :em";
                $params['em'] = trim($email);
            }
            $sql .= " LIMIT 1";

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $user = $stmt->fetch();

            if (!$user) {
                return ['success' => false, 'message' => 'Invalid or expired user email verification code / link.'];
            }

            // Update user email status to VERIFIED
            $upStmt = $db->prepare("UPDATE users SET email_status = 'VERIFIED', token = NULL, email_token = NULL, email_token_expiry = NULL WHERE id = :id");
            if ($upStmt->execute(['id' => $user['id']])) {
                return [
                    'success' => true,
                    'message' => 'Your user account email address (' . sanitizeInput($user['email']) . ') has been verified successfully!',
                    'user' => $user
                ];
            }
        } catch (Throwable $e) {
            error_log("verifyUserEmailToken error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Verification error: ' . $e->getMessage()];
        }

        return ['success' => false, 'message' => 'An error occurred during user email verification.'];
    }
}

if (!function_exists('verifyListingEmailToken')) {
    /**
     * Validate listing email verification token or OTP
     */
    function verifyListingEmailToken($token_or_otp, $email = null) {
        $db = getDB();
        if (!$db) return ['success' => false, 'message' => 'Database connection failed.'];

        try {
            ensureListingsEmailColumns();
            $input = trim($token_or_otp);
            if (empty($input)) {
                return ['success' => false, 'message' => 'Please provide a valid verification token or OTP code.'];
            }

            $sql = "SELECT * FROM listings WHERE (email_token = :etok)";
            $params = ['etok' => $input];

            if (!empty($email)) {
                $sql .= " AND email = :em";
                $params['em'] = trim($email);
            }
            $sql .= " LIMIT 1";

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $listing = $stmt->fetch();

            if (!$listing) {
                return ['success' => false, 'message' => 'Invalid or expired listing email verification code / link.'];
            }

            // Update listing email status to VERIFIED and is_verified to YES
            $upStmt = $db->prepare("UPDATE listings SET email_status = 'VERIFIED', is_verified = 'YES', email_token = NULL, email_token_expiry = NULL WHERE id = :id");
            if ($upStmt->execute(['id' => $listing['id']])) {
                return [
                    'success' => true,
                    'message' => 'Listing "' . sanitizeInput($listing['title']) . '" email address (' . sanitizeInput($listing['email']) . ') has been verified successfully!',
                    'listing' => $listing
                ];
            }
        } catch (Throwable $e) {
            error_log("verifyListingEmailToken error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Verification error: ' . $e->getMessage()];
        }

        return ['success' => false, 'message' => 'An error occurred during listing email verification.'];
    }
}

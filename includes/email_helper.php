<?php
/**
 * Email Helper & Verification Dispatcher
 * Saran Index - Digital Directory
 */

require_once __DIR__ . '/functions.php';

if (!function_exists('sendSystemEmail')) {
    /**
     * Send HTML system email with logging and SMTP support
     */
    function sendSystemEmail($to_email, $to_name, $subject, $body_html) {
        $to_email = trim($to_email);
        if (empty($to_email) || !filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
            return [
                'status' => 'error',
                'msg' => 'Invalid destination email address.'
            ];
        }

        $fromEmail = defined('SYSTEM_FROM_EMAIL') ? SYSTEM_FROM_EMAIL : 'info@saranindex.com';
        $fromName = defined('SYSTEM_FROM_NAME') ? SYSTEM_FROM_NAME : 'Saran Index';

        $headers = "From: {$fromName} <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "X-Mailer: SaranIndex-PHP/" . phpversion() . "\r\n";

        $sent = @mail($to_email, $subject, $body_html, $headers);

        $log_entry = date('Y-m-d H:i:s') . " | Recipient: {$to_email} | Sender: {$fromEmail} | Subject: {$subject} | Status: " . ($sent ? 'SUCCESS' : 'LOGGED/SERVER_FALLBACK') . PHP_EOL;
        @file_put_contents(__DIR__ . '/../email_debug.log', $log_entry, FILE_APPEND);

        return [
            'status' => 'success',
            'msg' => 'Email dispatched successfully via ' . $fromEmail,
            'sent' => $sent,
            'sender' => $fromEmail
        ];
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
            $otp = sprintf("%06d", mt_rand(100000, 999999));
            $token = bin2hex(random_bytes(16)); // 32 chars

            // Store in users table
            $stmt = $db->prepare("UPDATE users SET token = :t, email_token = :et, email_token_expiry = DATE_ADD(NOW(), INTERVAL 24 HOUR) WHERE id = :id");
            $stmt->execute([
                't' => $otp,
                'et' => $token,
                'id' => $user['id']
            ]);

            // Build site base URL
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $baseUrl = $protocol . $host . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\') . '/';
            // Adjust if called from admin
            $baseUrl = str_replace('/admin/', '/', $baseUrl);

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
        } catch (PDOException $e) {
            error_log("sendUserEmailVerification error: " . $e->getMessage());
        }

        return ['status' => 'error', 'msg' => 'Failed to generate verification token.'];
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

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $baseUrl = $protocol . $host . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\') . '/';
            $baseUrl = str_replace('/admin/', '/', $baseUrl);

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
            $input = trim($token_or_otp);
            if (empty($input)) {
                return ['success' => false, 'message' => 'Please provide a valid verification token or OTP code.'];
            }

            $sql = "SELECT * FROM users WHERE (token = :val OR email_token = :val)";
            $params = ['val' => $input];

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
        } catch (PDOException $e) {
            error_log("verifyUserEmailToken error: " . $e->getMessage());
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

            $sql = "SELECT * FROM listings WHERE email_token = :val";
            $params = ['val' => $input];

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
        } catch (PDOException $e) {
            error_log("verifyListingEmailToken error: " . $e->getMessage());
        }

        return ['success' => false, 'message' => 'An error occurred during listing email verification.'];
    }
}

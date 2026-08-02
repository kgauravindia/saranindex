<?php
// SaranIndex.com Configuration Template for GitHub Public Setup
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Kolkata');

define('APP_NAME', 'Saran Index');
define('APP_TAGLINE', 'Connecting Saran Digitally');
define('APP_VERSION', '1.0.0');
define('LAUNCH_DATE', '2026-07-26');
define('PARENT_COMPANY', 'OfferPlant Technologies Private Limited');
define('PARENT_COMPANY_EMAIL', 'ask@offerplant.com');
define('PARENT_INCORPORATION_YEAR', '2017');

// Base URL calculation
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';
$dir = '/' . ltrim(str_replace('\\', '/', dirname($script_name)), '/');
$dir = preg_replace('~/(hindi|admin)(/.*)?$~i', '', $dir);
$base_path = rtrim($dir, '/');
define('BASE_URL', $protocol . "://" . $host . $base_path . "/");

// Database Configuration (Customize credentials for your local environment)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_NAME', getenv('DB_NAME') ?: 'saranindex');
define('DB_CHARSET', 'utf8mb4');

// SMS Template Configuration
define('SMS_SENDER_ID', 'CITYXN');
define('SMS_REGISTRATION_TEMPLATE', "Dear {#var#},\n Your registration is completed as {#var#}\n Your OTP / EVC / Password is {#var#}\n \n Regards\n CITYXN\n OfferPlant");

// Prime Social Media Handles (@saranindex)
define('SOCIAL_HANDLE', '@saranindex');
define('SOCIAL_FACEBOOK', 'https://facebook.com/saranindex');
define('SOCIAL_INSTAGRAM', 'https://instagram.com/saranindex');
define('SOCIAL_TWITTER', 'https://twitter.com/saranindex');
define('SOCIAL_THREADS', 'https://threads.net/@saranindex');
define('SOCIAL_YOUTUBE', 'https://youtube.com/@saranindex');
define('SOCIAL_WHATSAPP', 'https://whatsapp.com/channel/0029VbDJKIS4CrfaodCTmw1c');
?>

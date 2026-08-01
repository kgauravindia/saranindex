<?php
// SaranIndex.com Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('APP_NAME', 'Saran Index');
define('APP_TAGLINE', 'Connecting Saran Digitally');
define('APP_VERSION', '1.0.0');
define('LAUNCH_DATE', '2026-07-26');
define('PARENT_COMPANY', 'OfferPlant Technologies Private Limited');
define('PARENT_INCORPORATION_YEAR', '2017');

// Base URL calculation
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';
$dir = dirname($script_name);
$base_path = rtrim(str_replace('\\', '/', $dir), '/');
define('BASE_URL', $protocol . "://" . $host . $base_path . "/");

// Database Configuration (Environment variable driven for security)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_NAME', getenv('DB_NAME') ?: 'u305984835_saranindex');
define('DB_CHARSET', 'utf8mb4');

// SMS Template & Gateway Configuration
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


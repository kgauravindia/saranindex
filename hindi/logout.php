<?php
require_once __DIR__ . '/includes/functions.php';

logoutPublicUser();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_mobile']);

header("Location: login.php");
exit;

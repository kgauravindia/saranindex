<?php


require_once __DIR__ . '/../../includes/functions.php';

function checkAdminAuth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        $loginUrl = BASE_URL . 'admin/login.php';
        header("Location: " . $loginUrl);
        exit;
    }
}
?>

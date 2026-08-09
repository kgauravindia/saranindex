<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$rawHandle = isset($_GET['handle']) ? trim($_GET['handle']) : '';
$currentUserId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

$cleanHandle = ltrim(preg_replace('/[^a-zA-Z0-9_]/', '', $rawHandle), '@');

if (empty($cleanHandle)) {
    echo json_encode([
        'status' => 'error',
        'available' => false,
        'message' => 'Handle must contain between 8 and 24 letters, numbers, or underscores.'
    ]);
    exit;
}

if (strlen($cleanHandle) < 8 || strlen($cleanHandle) > 24) {
    echo json_encode([
        'status' => 'error',
        'available' => false,
        'message' => 'Handle must be between 8 and 24 characters long.'
    ]);
    exit;
}

$db = getDB();
if (!$db) {
    echo json_encode([
        'status' => 'error',
        'available' => false,
        'message' => 'Database connection failed.'
    ]);
    exit;
}

$formattedHandle = '@' . strtolower($cleanHandle);

try {
    $sql = "SELECT id FROM users WHERE (LOWER(username_handle) = :h1 OR LOWER(username_handle) = :h2)";
    $params = ['h1' => $formattedHandle, 'h2' => strtolower($cleanHandle)];

    if ($currentUserId > 0) {
        $sql .= " AND id != :uid";
        $params['uid'] = $currentUserId;
    }

    $sql .= " LIMIT 1";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $exists = $stmt->fetch();

    if ($exists) {
        echo json_encode([
            'status' => 'success',
            'available' => false,
            'handle' => $formattedHandle,
            'url' => 'saranindex.com/' . $formattedHandle,
            'message' => 'This handle is already taken by another user.'
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'available' => true,
            'handle' => $formattedHandle,
            'url' => 'saranindex.com/' . $formattedHandle,
            'message' => 'URL Available!'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'available' => false,
        'message' => 'Error checking handle availability.'
    ]);
}

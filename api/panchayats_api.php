<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$block_id = isset($_GET['block_id']) ? intval($_GET['block_id']) : 0;
$results = [];

$db = getDB();
if ($db && $block_id > 0) {
    try {
        $stmt = $db->prepare("SELECT id, panchayat_name, hindi_name, slug FROM panchayats WHERE block_id = :bid ORDER BY panchayat_name ASC");
        $stmt->execute(['bid' => $block_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Panchayats API Error: " . $e->getMessage());
    }
}
echo json_encode($results, JSON_UNESCAPED_UNICODE);

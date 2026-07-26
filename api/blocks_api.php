<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/functions.php';

$block_id = isset($_GET['block_id']) ? intval($_GET['block_id']) : 0;
$panchayat_id = isset($_GET['panchayat_id']) ? intval($_GET['panchayat_id']) : 0;
$type = isset($_GET['type']) ? sanitizeInput($_GET['type']) : '';

$results = [];
$db = getDB();

if ($block_id > 0 && ($type === 'panchayats' || empty($type))) {
    if ($db) {
        try {
            $stmt = $db->prepare("SELECT id, panchayat_name, hindi_name, slug FROM panchayats WHERE block_id = :block_id ORDER BY panchayat_name ASC");
            $stmt->execute(['block_id' => $block_id]);
            $results = $stmt->fetchAll();
        } catch (PDOException $e) {}
    }
    
    if (empty($results)) {
        // Fallback sample data if DB empty
        $results = [
            ['id' => 1, 'panchayat_name' => 'Dahiyawan', 'hindi_name' => 'दहियावां', 'slug' => 'dahiyawan'],
            ['id' => 2, 'panchayat_name' => 'Sahebganj', 'hindi_name' => 'साहिबगंज', 'slug' => 'sahebganj'],
            ['id' => 3, 'panchayat_name' => 'Bhagwan Bazar', 'hindi_name' => 'भगवान बाजार', 'slug' => 'bhagwan-bazar']
        ];
    }
} elseif ($panchayat_id > 0 && ($type === 'villages' || empty($type))) {
    if ($db) {
        try {
            $stmt = $db->prepare("SELECT id, village_name, hindi_name, slug, pincode FROM villages WHERE panchayat_id = :panchayat_id ORDER BY village_name ASC");
            $stmt->execute(['panchayat_id' => $panchayat_id]);
            $results = $stmt->fetchAll();
        } catch (PDOException $e) {}
    }
}

echo json_encode($results, JSON_UNESCAPED_UNICODE);
?>

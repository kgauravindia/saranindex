<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$subcategories = [];

if ($category_id > 0) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("SELECT id, name, hindi_name, slug, type FROM subcategories WHERE category_id = :cat_id ORDER BY CASE WHEN type = 'PROFESSIONAL' THEN 1 ELSE 2 END ASC, name ASC");
            $stmt->execute(['cat_id' => $category_id]);
            $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("subcategories_api error: " . $e->getMessage());
        }
    }
}

echo json_encode([
    'status' => 'success',
    'subcategories' => $subcategories
], JSON_UNESCAPED_UNICODE);

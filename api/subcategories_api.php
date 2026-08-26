<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$subcategories = [];

$db = getDB();
if ($db) {
    try {
        if ($category_id > 0) {
            $stmt = $db->prepare("SELECT s.id, s.name, s.hindi_name, s.slug, s.type, s.keywords, s.category_id, c.name as category_name, c.hindi_name as category_hindi_name 
                                  FROM subcategories s 
                                  LEFT JOIN categories c ON s.category_id = c.id 
                                  WHERE s.category_id = :cat_id 
                                  ORDER BY CASE WHEN s.type = 'PROFESSIONAL' THEN 1 ELSE 2 END ASC, s.name ASC");
            $stmt->execute(['cat_id' => $category_id]);
            $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $db->query("SELECT s.id, s.name, s.hindi_name, s.slug, s.type, s.keywords, s.category_id, c.name as category_name, c.hindi_name as category_hindi_name 
                                FROM subcategories s 
                                LEFT JOIN categories c ON s.category_id = c.id 
                                ORDER BY c.name ASC, CASE WHEN s.type = 'PROFESSIONAL' THEN 1 ELSE 2 END ASC, s.name ASC");
            $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (Exception $e) {
        error_log("subcategories_api error: " . $e->getMessage());
    }
}

echo json_encode([
    'status' => 'success',
    'subcategories' => $subcategories
], JSON_UNESCAPED_UNICODE);


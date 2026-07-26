<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/functions.php';

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$results = [];

if ($category_id > 0) {
    $results = getSubcategoriesByCategoryId($category_id);
}

echo json_encode($results, JSON_UNESCAPED_UNICODE);
?>

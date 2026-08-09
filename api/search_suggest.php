<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/functions.php';

$query = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
$results = [];

if (!empty($query)) {
    $listings = getListings($query, '', '', 10, 0);
    foreach ($listings as $item) {
        $results[] = [
            'id' => $item['id'],
            'title' => $item['title'],
            'hindi_title' => $item['hindi_title'] ?? '',
            'contact_person' => $item['contact_person'] ?? '',
            'category_name' => $item['category_name'] ?? 'Directory',
            'block_name' => $item['block_name'] ?? 'Saran',
            'url' => getListingUrl($item['slug'])
        ];
    }
}

echo json_encode($results, JSON_UNESCAPED_UNICODE);
?>

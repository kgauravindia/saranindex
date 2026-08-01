<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/functions.php';

$block_name = isset($_GET['block_name']) ? sanitizeInput($_GET['block_name']) : '';
$block_id = isset($_GET['block_id']) ? intval($_GET['block_id']) : 0;
$results = [];
$villages = [];

if ($block_id > 0) {
    $db = getDB();
    if ($db) {
        $stmt = $db->prepare("SELECT name, name_english FROM blocks WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $block_id]);
        $b = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($b) {
            $villages = getCensusVillages($b['name_english'], '', 500, 0);
            if (empty($villages)) {
                $villages = getCensusVillages($b['name'], '', 500, 0);
            }
        }
    }
} elseif (!empty($block_name)) {
    $villages = getCensusVillages($block_name, '', 500, 0);
}

if (!empty($villages)) {
    foreach ($villages as $v) {
        $results[] = [
            'code' => $v['town_village_code'],
            'name' => $v['name'],
            'name_hindi' => !empty($v['name_hindi']) ? $v['name_hindi'] : '',
            'unique_slug' => $v['unique_slug'],
            'population' => $v['pop_tot']
        ];
    }
}

echo json_encode($results, JSON_UNESCAPED_UNICODE);

<?php
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

$q = trim($_GET['q'] ?? '');
if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$db = getDB();
if (!$db) {
    echo json_encode([]);
    exit;
}

try {
    $cleanMobile = preg_replace('/[^0-9]/', '', $q);
    $cleanMobile10 = (strlen($cleanMobile) >= 10) ? substr($cleanMobile, -10) : $cleanMobile;

    $sql = "SELECT l.id, l.title, l.mobile, b.name as block_name 
            FROM listings l 
            LEFT JOIN blocks b ON l.block_id = b.id 
            WHERE (
                l.title LIKE :q1 OR 
                l.hindi_title LIKE :q2 OR 
                (:m != '' AND (l.mobile LIKE :m1 OR RIGHT(l.mobile, 10) = :m10))
            ) 
            AND l.status = 'ACTIVE' 
            ORDER BY l.title ASC 
            LIMIT 15";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        'q1' => '%' . $q . '%',
        'q2' => '%' . $q . '%',
        'm' => $cleanMobile,
        'm1' => '%' . $cleanMobile . '%',
        'm10' => !empty($cleanMobile10) ? $cleanMobile10 : '___NO_MATCH___'
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results ?: []);
} catch (Exception $e) {
    echo json_encode([]);
}

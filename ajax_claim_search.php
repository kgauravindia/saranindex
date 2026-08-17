<?php
/**
 * AJAX Claim Business Search Endpoint
 * Saran Index - Digital Directory
 */

require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

$q = trim($_GET['q'] ?? '');
if (strlen($q) < 1) {
    echo json_encode([]);
    exit;
}

$db = getDB();
if (!$db) {
    echo json_encode([]);
    exit;
}

try {
    $cleanSearch = trim($q);
    $idSearch = ltrim($cleanSearch, '#');
    $sVal = '%' . $cleanSearch . '%';

    $digitsOnly = preg_replace('/[^0-9]/', '', $cleanSearch);
    $m10In = (strlen($digitsOnly) >= 10) ? substr($digitsOnly, -10) : $digitsOnly;
    $mVal = !empty($m10In) ? '%' . $m10In . '%' : '___NO_MATCH___';

    $sql = "SELECT l.id, l.title, l.hindi_title, l.mobile, l.contact_person, b.name as block_name, c.name as category_name
            FROM listings l 
            LEFT JOIN blocks b ON l.block_id = b.id 
            LEFT JOIN categories c ON l.category_id = c.id 
            LEFT JOIN subcategories sc ON l.subcategory_id = sc.id 
            WHERE (
                l.title LIKE :s1 
                OR l.hindi_title LIKE :s2 
                OR l.contact_person LIKE :s3
                OR l.address LIKE :s4
                OR l.email LIKE :s5
                OR c.name LIKE :s6
                OR sc.name LIKE :s7
                OR b.name LIKE :s8
                OR l.mobile LIKE :s9";

    $params = [
        's1' => $sVal,
        's2' => $sVal,
        's3' => $sVal,
        's4' => $sVal,
        's5' => $sVal,
        's6' => $sVal,
        's7' => $sVal,
        's8' => $sVal,
        's9' => $sVal
    ];

    if (!empty($m10In)) {
        $sql .= " OR REPLACE(REPLACE(REPLACE(REPLACE(l.mobile, ' ', ''), '-', ''), '+', ''), '91', '') LIKE :m10";
        $params['m10'] = $mVal;
    }

    if (is_numeric($idSearch) && intval($idSearch) > 0) {
        $sql .= " OR l.id = :id_search";
        $params['id_search'] = intval($idSearch);
    }

    $sql .= ") ORDER BY l.id DESC LIMIT 20";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $results = [];
    foreach ($rows as $r) {
        $titleStr = !empty($r['title']) ? $r['title'] : (!empty($r['hindi_title']) ? $r['hindi_title'] : 'Business #' . $r['id']);
        $results[] = [
            'id' => (int)$r['id'],
            'title' => htmlspecialchars($titleStr, ENT_QUOTES, 'UTF-8'),
            'mobile' => htmlspecialchars($r['mobile'] ?? '', ENT_QUOTES, 'UTF-8'),
            'contact_person' => htmlspecialchars($r['contact_person'] ?? '', ENT_QUOTES, 'UTF-8'),
            'block_name' => htmlspecialchars($r['block_name'] ?? 'Saran', ENT_QUOTES, 'UTF-8'),
            'category_name' => htmlspecialchars($r['category_name'] ?? 'General', ENT_QUOTES, 'UTF-8')
        ];
    }

    echo json_encode($results);
} catch (Exception $e) {
    echo json_encode([]);
}

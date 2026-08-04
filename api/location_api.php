<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$stateCode = isset($_GET['state_code']) ? trim($_GET['state_code']) : '';
$districtCode = isset($_GET['district_code']) ? trim($_GET['district_code']) : '';
$blockId = isset($_GET['block_id']) ? intval($_GET['block_id']) : 0;

$db = getDB();
$results = [];

if (!$db) {
    echo json_encode([]);
    exit;
}

try {
    if ($type === 'states') {
        $stmt = $db->query("SELECT DISTINCT state_code, state FROM op_sdb WHERE status='ACTIVE' AND state IS NOT NULL AND state != '' ORDER BY state ASC");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($type === 'districts' && !empty($stateCode)) {
        $stmt = $db->prepare("SELECT DISTINCT district_code, district FROM op_sdb WHERE state_code = :sc AND status='ACTIVE' AND district IS NOT NULL AND district != '' ORDER BY district ASC");
        $stmt->execute(['sc' => $stateCode]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($type === 'blocks' && !empty($stateCode) && !empty($districtCode)) {
        $stmt = $db->prepare("SELECT DISTINCT block_code, block FROM op_sdb WHERE state_code = :sc AND district_code = :dc AND status='ACTIVE' AND block IS NOT NULL AND block != '' ORDER BY block ASC");
        $stmt->execute(['sc' => $stateCode, 'dc' => $districtCode]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($type === 'saran_villages' && $blockId > 0) {
        $stmtBlock = $db->prepare("SELECT id, name, name_english FROM blocks WHERE id = :bid LIMIT 1");
        $stmtBlock->execute(['bid' => $blockId]);
        $b = $stmtBlock->fetch(PDO::FETCH_ASSOC);

        if ($b) {
            $cdCodeStmt = $db->prepare("SELECT cd_block_code FROM census WHERE level = 'CD BLOCK' AND (LOWER(name) = LOWER(:n1) OR LOWER(name) = LOWER(:n2) OR SOUNDEX(name) = SOUNDEX(:n3)) LIMIT 1");
            $cdCodeStmt->execute(['n1' => $b['name'], 'n2' => $b['name_english'], 'n3' => $b['name']]);
            $code = $cdCodeStmt->fetchColumn();

            if ($code) {
                $vStmt = $db->prepare("SELECT id, name, town_village_code FROM census WHERE level = 'VILLAGE' AND cd_block_code = :code ORDER BY name ASC");
                $vStmt->execute(['code' => $code]);
                $results = $vStmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
} catch (PDOException $e) {
    error_log("Location API Error: " . $e->getMessage());
}

echo json_encode($results, JSON_UNESCAPED_UNICODE);

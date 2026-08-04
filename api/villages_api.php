<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$block_name = isset($_GET['block_name']) ? sanitizeInput($_GET['block_name']) : '';
$block_id = isset($_GET['block_id']) ? intval($_GET['block_id']) : 0;
$results = [];

$db = getDB();

if ($db) {
    try {
        $code = null;
        if ($block_id > 0) {
            $stmtBlock = $db->prepare("SELECT id, name, name_english FROM blocks WHERE id = :id LIMIT 1");
            $stmtBlock->execute(['id' => $block_id]);
            $b = $stmtBlock->fetch(PDO::FETCH_ASSOC);
            if ($b) {
                $cdCodeStmt = $db->prepare("SELECT cd_block_code FROM census WHERE level = 'CD BLOCK' AND (LOWER(name) = LOWER(:n1) OR LOWER(name) = LOWER(:n2) OR SOUNDEX(name) = SOUNDEX(:n3)) LIMIT 1");
                $cdCodeStmt->execute(['n1' => $b['name'], 'n2' => $b['name_english'], 'n3' => $b['name']]);
                $code = $cdCodeStmt->fetchColumn();
            }
        } elseif (!empty($block_name)) {
            $cdCodeStmt = $db->prepare("SELECT cd_block_code FROM census WHERE level = 'CD BLOCK' AND (LOWER(name) = LOWER(:n1) OR SOUNDEX(name) = SOUNDEX(:n2)) LIMIT 1");
            $cdCodeStmt->execute(['n1' => $block_name, 'n2' => $block_name]);
            $code = $cdCodeStmt->fetchColumn();
        }

        if ($code) {
            $vStmt = $db->prepare("SELECT id, name, town_village_code, pop_tot FROM census WHERE level = 'VILLAGE' AND cd_block_code = :code ORDER BY name ASC");
            $vStmt->execute(['code' => $code]);
            $villages = $vStmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($villages as $v) {
                $results[] = [
                    'code' => $v['town_village_code'],
                    'name' => $v['name'],
                    'unique_slug' => slugify($v['name']) . '-' . $v['town_village_code'],
                    'population' => $v['pop_tot']
                ];
            }
        }
    } catch (Exception $e) {
        error_log("villages_api error: " . $e->getMessage());
    }
}

// Fallback to getCensusVillages if direct census query returned empty
if (empty($results)) {
    $villages = getCensusVillages(!empty($block_name) ? $block_name : '', '', 500, 0);
    if (!empty($villages)) {
        foreach ($villages as $v) {
            $results[] = [
                'code' => $v['town_village_code'],
                'name' => $v['name'],
                'unique_slug' => $v['unique_slug'],
                'population' => $v['pop_tot']
            ];
        }
    }
}

echo json_encode($results, JSON_UNESCAPED_UNICODE);

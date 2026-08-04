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
        $bNames = [];
        if ($block_id > 0) {
            $stmtBlock = $db->prepare("SELECT name, name_english, hindi_name FROM blocks WHERE id = :id LIMIT 1");
            $stmtBlock->execute(['id' => $block_id]);
            $b = $stmtBlock->fetch(PDO::FETCH_ASSOC);
            if ($b) {
                if (!empty($b['name_english'])) $bNames[] = $b['name_english'];
                if (!empty($b['name'])) $bNames[] = $b['name'];
            }
        } elseif (!empty($block_name)) {
            $bNames[] = $block_name;
        }

        if (!empty($bNames)) {
            $whereBlock = [];
            $params = [];
            foreach ($bNames as $idx => $bn) {
                $whereBlock[] = "LOWER(block) = LOWER(:b{$idx}_1) OR LOWER(block) LIKE LOWER(:b{$idx}_2)";
                $params["b{$idx}_1"] = $bn;
                $params["b{$idx}_2"] = "%$bn%";
            }
            $whereSql = implode(' OR ', $whereBlock);

            // Fetch Mauja records from halka table for this block
            $stmtHalka = $db->prepare("SELECT DISTINCT mauja_code, mauja_name, mauja_english, halka_code, halka_name, halka_english FROM halka WHERE $whereSql ORDER BY mauja_english ASC, mauja_name ASC");
            $stmtHalka->execute($params);
            $maujas = $stmtHalka->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($maujas)) {
                foreach ($maujas as $m) {
                    $mHindi = !empty($m['mauja_name']) ? trim($m['mauja_name']) : '';
                    $mEng = !empty($m['mauja_english']) && trim($m['mauja_english']) !== '' ? trim($m['mauja_english']) : $mHindi;
                    $mCode = !empty($m['mauja_code']) ? trim($m['mauja_code']) : '';

                    $displayNameEng = $mEng . (!empty($mCode) ? ' (Code: ' . $mCode . ')' : '');
                    $displayNameHindi = (!empty($mHindi) ? $mHindi : $mEng) . (!empty($mCode) ? ' (कोड: ' . $mCode . ')' : '');

                    $results[] = [
                        'code' => $mCode,
                        'mauja_code' => $mCode,
                        'mauja_english' => $mEng,
                        'mauja_name' => $mHindi,
                        'halka_code' => $m['halka_code'],
                        'halka_name' => $m['halka_name'],
                        'halka_english' => $m['halka_english'],
                        'name' => $displayNameEng,
                        'name_hindi' => $displayNameHindi,
                        'display_name' => $displayNameEng
                    ];
                }
            }
        }

        // Fallback: Query census table if halka results are empty
        if (empty($results) && !empty($bNames)) {
            $firstName = $bNames[0];
            $cdCodeStmt = $db->prepare("SELECT cd_block_code FROM census WHERE level = 'CD BLOCK' AND (LOWER(name) = LOWER(:n1) OR SOUNDEX(name) = SOUNDEX(:n2)) LIMIT 1");
            $cdCodeStmt->execute(['n1' => $firstName, 'n2' => $firstName]);
            $code = $cdCodeStmt->fetchColumn();

            if ($code) {
                $vStmt = $db->prepare("SELECT id, name, town_village_code, pop_tot FROM census WHERE level = 'VILLAGE' AND cd_block_code = :code ORDER BY name ASC");
                $vStmt->execute(['code' => $code]);
                $villages = $vStmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($villages as $v) {
                    $results[] = [
                        'code' => $v['town_village_code'],
                        'mauja_code' => $v['town_village_code'],
                        'mauja_english' => $v['name'],
                        'mauja_name' => $v['name'],
                        'name' => $v['name'] . ' (Code: ' . $v['town_village_code'] . ')',
                        'name_hindi' => $v['name'] . ' (कोड: ' . $v['town_village_code'] . ')',
                        'display_name' => $v['name'] . ' (Code: ' . $v['town_village_code'] . ')'
                    ];
                }
            }
        }
    } catch (Exception $e) {
        error_log("villages_api error: " . $e->getMessage());
    }
}

echo json_encode($results, JSON_UNESCAPED_UNICODE);

<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/functions.php';

$pincode = trim($_REQUEST['pincode'] ?? '');
$ifsc    = trim($_REQUEST['ifsc'] ?? '');
$action  = trim($_REQUEST['action'] ?? '');

if (!empty($ifsc) || $action === 'bank-ifsc') {
    $res = lookupIfscApi($ifsc);
    echo json_encode($res);
    exit;
}

if (!empty($pincode) || $action === 'pincode') {
    $cleanPin = preg_replace('/[^0-9]/', '', $pincode);
    if (strlen($cleanPin) !== 6) {
        echo json_encode([
            'success' => false,
            'message' => 'Please enter a valid 6-digit PIN code.'
        ]);
        exit;
    }

    $res = lookupPincodeApi($cleanPin);

    // If successful, check if any sub_district matches Saran blocks
    if (!empty($res['success'])) {
        $db = getDB();
        $matchedBlocks = [];
        try {
            $blocks = $db->query("SELECT id, block_name, hindi_name, slug FROM blocks ORDER BY block_name ASC")->fetchAll();
            $subDistricts = array_map('strtolower', $res['sub_districts'] ?? []);
            
            foreach ($blocks as $blk) {
                $bName = strtolower($blk['block_name']);
                $bSlug = strtolower($blk['slug']);
                
                foreach ($subDistricts as $sub) {
                    if (strpos($bName, $sub) !== false || strpos($sub, $bName) !== false ||
                        strpos($bSlug, $sub) !== false || strpos($sub, $bSlug) !== false) {
                        $matchedBlocks[] = $blk;
                        break;
                    }
                }
            }
        } catch (Exception $e) {
            // Ignore DB block matching failure
        }
        $res['matched_blocks'] = $matchedBlocks;
    }

    echo json_encode($res);
    exit;
}

echo json_encode([
    'success' => false,
    'message' => 'Missing parameter: pincode or ifsc is required.'
]);

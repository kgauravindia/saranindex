<?php
/**
 * Export Users to Excel/CSV for Admin Panel
 * Saran Index - Digital Directory
 */

require_once __DIR__ . '/includes/auth.php';
checkAdminAuth();

require_once __DIR__ . '/../includes/functions.php';

$db = getDB();
if (!$db) {
    die("Database connection error.");
}

$export_action = $_POST['export_action'] ?? $_GET['export_action'] ?? 'all';
$selected_ids = $_POST['selected_ids'] ?? $_GET['selected_ids'] ?? [];
$status_filter = trim($_POST['status'] ?? $_GET['status'] ?? '');
$search_query = trim($_POST['search'] ?? $_GET['search'] ?? '');

$users = [];

if ($export_action === 'selected' && !empty($selected_ids)) {
    if (is_string($selected_ids)) {
        $selected_ids = explode(',', $selected_ids);
    }
    $ids = array_map('intval', (array)$selected_ids);
    $ids = array_filter($ids, function($v) { return $v > 0; });

    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT u.*, 
                       b.name AS block_name,
                       p.panchayat_name AS panchayat_name,
                       v.village_name AS village_name
                FROM users u 
                LEFT JOIN blocks b ON u.block_id = b.id 
                LEFT JOIN panchayats p ON u.panchayat_id = p.id
                LEFT JOIN villages v ON u.village_id = v.id
                WHERE u.id IN ($placeholders)
                ORDER BY u.id DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute(array_values($ids));
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // Export users matching active filters
    $sql = "SELECT u.*, 
                   b.name AS block_name,
                   p.panchayat_name AS panchayat_name,
                   v.village_name AS village_name
            FROM users u 
            LEFT JOIN blocks b ON u.block_id = b.id 
            LEFT JOIN panchayats p ON u.panchayat_id = p.id
            LEFT JOIN villages v ON u.village_id = v.id
            WHERE 1=1";
    $params = [];

    if (!empty($status_filter)) {
        $sql .= " AND u.status = :status";
        $params['status'] = strtoupper($status_filter);
    }

    if (!empty($search_query)) {
        $sVal = '%' . $search_query . '%';
        $sql .= " AND (
            u.full_name LIKE :s1 
            OR u.username_handle LIKE :s2 
            OR u.mobile LIKE :s3 
            OR u.email LIKE :s4 
            OR u.business_name LIKE :s5 
            OR u.designation LIKE :s6 
            OR u.address LIKE :s7 
            OR b.name LIKE :s8
        )";
        $params['s1'] = $sVal;
        $params['s2'] = $sVal;
        $params['s3'] = $sVal;
        $params['s4'] = $sVal;
        $params['s5'] = $sVal;
        $params['s6'] = $sVal;
        $params['s7'] = $sVal;
        $params['s8'] = $sVal;
    }

    $sql .= " ORDER BY u.id DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Set headers for Excel-compatible CSV download
$filename = "saran_users_export_" . date('Y-m-d_His') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$output = fopen('php://output', 'w');

// Write UTF-8 BOM so Excel renders Unicode text (Hindi & special characters) properly
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// CSV Header Row
fputcsv($output, [
    'User ID',
    'Full Name',
    'Username Handle',
    'Mobile Number',
    'Mobile Status',
    'Mobile Visibility',
    'WhatsApp Number',
    'Email Address',
    'Email Status',
    'Email Visibility',
    'Business / Organisation Name',
    'Designation / Role',
    'Block Name',
    'Panchayat Name',
    'Village Name',
    'Address',
    'Pincode',
    'User Account Type',
    'Account Status',
    'Subscription Plan',
    'Plan Expiry Date',
    'Bio / Description',
    'Date Registered'
]);

// Write User Rows
foreach ($users as $row) {
    fputcsv($output, [
        $row['id'],
        $row['full_name'] ?? '',
        $row['username_handle'] ?? '',
        $row['mobile'] ?? '',
        $row['mobile_status'] ?? 'UNVERIFIED',
        $row['mobile_visibility'] ?? 'PUBLIC',
        $row['whatsapp'] ?? '',
        $row['email'] ?? '',
        $row['email_status'] ?? 'UNVERIFIED',
        $row['email_visibility'] ?? 'PUBLIC',
        $row['business_name'] ?? '',
        $row['designation'] ?? '',
        $row['block_name'] ?? 'Chapra Sadar',
        $row['panchayat_name'] ?? '',
        $row['village_name'] ?? '',
        $row['address'] ?? '',
        $row['pincode'] ?? '',
        $row['type'] ?? 'USER',
        $row['status'] ?? 'ACTIVE',
        $row['plan_type'] ?? 'FREE',
        $row['plan_expiry'] ?? '',
        $row['bio'] ?? ($row['about'] ?? ''),
        $row['created_at'] ?? ''
    ]);
}

fclose($output);
exit;

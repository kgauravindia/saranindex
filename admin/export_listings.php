<?php
/**
 * Export Listings to Excel/CSV for Admin Panel
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

$listings = [];

if ($export_action === 'selected' && !empty($selected_ids)) {
    if (is_string($selected_ids)) {
        $selected_ids = explode(',', $selected_ids);
    }
    $ids = array_map('intval', (array)$selected_ids);
    $ids = array_filter($ids, function($v) { return $v > 0; });

    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT l.*, 
                       c.name AS category_name, 
                       sc.name AS subcategory_name, 
                       COALESCE(b.block_name, b.name) AS block_name,
                       p.panchayat_name AS panchayat_name,
                       v.village_name AS village_name
                FROM listings l 
                LEFT JOIN categories c ON l.category_id = c.id 
                LEFT JOIN subcategories sc ON l.subcategory_id = sc.id 
                LEFT JOIN blocks b ON l.block_id = b.id 
                LEFT JOIN panchayats p ON l.panchayat_id = p.id
                LEFT JOIN villages v ON l.village_id = v.id
                WHERE l.id IN ($placeholders)
                ORDER BY l.id DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($ids);
        $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // Export all listings matching active filters
    $sql = "SELECT l.*, 
                   c.name AS category_name, 
                   sc.name AS subcategory_name, 
                   COALESCE(b.block_name, b.name) AS block_name,
                   p.panchayat_name AS panchayat_name,
                   v.village_name AS village_name
            FROM listings l 
            LEFT JOIN categories c ON l.category_id = c.id 
            LEFT JOIN subcategories sc ON l.subcategory_id = sc.id 
            LEFT JOIN blocks b ON l.block_id = b.id 
            LEFT JOIN panchayats p ON l.panchayat_id = p.id
            LEFT JOIN villages v ON l.village_id = v.id
            WHERE 1=1";
    $params = [];

    if (!empty($status_filter)) {
        $sql .= " AND l.status = :status";
        $params['status'] = $status_filter;
    }

    if (!empty($search_query)) {
        $sql .= " AND (l.title LIKE :search OR l.hindi_title LIKE :search OR l.mobile LIKE :search OR l.contact_person LIKE :search)";
        $params['search'] = '%' . $search_query . '%';
    }

    $sql .= " ORDER BY l.id DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Set headers for Excel-compatible CSV download
$filename = "saran_listings_export_" . date('Y-m-d_His') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$output = fopen('php://output', 'w');

// Write UTF-8 BOM so Excel opens Hindi & English text properly formatted
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// CSV Header Row
fputcsv($output, [
    'ID',
    'Entity Type',
    'Category',
    'Subcategory',
    'Block',
    'Panchayat',
    'Village',
    'Title (English)',
    'Title (Hindi)',
    'Contact Person',
    'Mobile',
    'Mobile Visibility',
    'WhatsApp',
    'Email',
    'Website',
    'Address',
    'Pincode',
    'Business Hours',
    'Services / Level',
    'Products / Items',
    'GST No',
    'Udyam No',
    'CIN No',
    'Local Reg No',
    'Description',
    'Verified',
    'Featured',
    'Status',
    'Plan Type',
    'Created At'
]);

// Write Data Rows
foreach ($listings as $row) {
    fputcsv($output, [
        $row['id'],
        $row['entity_type'] ?? 'BUSINESS',
        $row['category_name'] ?? 'General',
        $row['subcategory_name'] ?? '',
        $row['block_name'] ?? 'Chapra Sadar',
        $row['panchayat_name'] ?? '',
        $row['village_name'] ?? '',
        $row['title'] ?? '',
        $row['hindi_title'] ?? '',
        $row['contact_person'] ?? '',
        $row['mobile'] ?? '',
        $row['mobile_visibility'] ?? 'PUBLIC',
        $row['whatsapp'] ?? '',
        $row['email'] ?? '',
        $row['website'] ?? '',
        $row['address'] ?? '',
        $row['pincode'] ?? '841301',
        $row['business_hours'] ?? '',
        $row['services'] ?? '',
        $row['products'] ?? '',
        $row['gst_no'] ?? '',
        $row['udyam_no'] ?? '',
        $row['cin_no'] ?? '',
        $row['local_reg_no'] ?? '',
        $row['description'] ?? '',
        $row['is_verified'] ?? 'NO',
        $row['is_featured'] ?? 'NO',
        $row['status'] ?? 'ACTIVE',
        $row['plan_type'] ?? 'FREE',
        $row['created_at'] ?? ''
    ]);
}

fclose($output);
exit;

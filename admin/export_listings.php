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
                       b.name AS block_name,
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
        $stmt->execute(array_values($ids));
        $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // Export all listings matching active filters
    $sql = "SELECT l.*, 
                   c.name AS category_name, 
                   sc.name AS subcategory_name, 
                   b.name AS block_name,
                   p.panchayat_name AS panchayat_name,
                   v.village_name AS village_name
            FROM listings l 
            LEFT JOIN categories c ON l.category_id = c.id 
            LEFT JOIN subcategories sc ON l.subcategory_id = sc.id 
            LEFT JOIN blocks b ON l.block_id = b.id 
            LEFT JOIN panchayats p ON l.panchayat_id = p.id
            LEFT JOIN villages v ON l.village_id = v.id
            LEFT JOIN users u ON l.user_id = u.id
            WHERE 1=1";
    $params = [];

    if (!empty($status_filter)) {
        $sql .= " AND l.status = :status";
        $params['status'] = $status_filter;
    }

    if (!empty($search_query)) {
        $cleanSearch = trim($search_query);
        if (strtolower($cleanSearch) === 'verified') {
            $sql .= " AND l.is_verified = 'YES'";
        } else {
            $idSearch = ltrim($cleanSearch, '#');
            $sVal = '%' . $cleanSearch . '%';
            
            $sql .= " AND (
                l.title LIKE :s1 
                OR l.hindi_title LIKE :s2 
                OR l.mobile LIKE :s3 
                OR l.whatsapp LIKE :s4
                OR l.contact_person LIKE :s5
                OR l.address LIKE :s6
                OR l.email LIKE :s7
                OR l.pincode LIKE :s8
                OR l.services LIKE :s9
                OR l.products LIKE :s10
                OR l.slug LIKE :s11
                OR c.name LIKE :s12
                OR c.hindi_name LIKE :s13
                OR sc.name LIKE :s14
                OR sc.hindi_name LIKE :s15
                OR b.name LIKE :s16
                OR b.hindi_name LIKE :s17
                OR u.full_name LIKE :s18
                OR u.designation LIKE :s19";

            if (is_numeric($idSearch)) {
                $sql .= " OR l.id = :id_search";
                $params['id_search'] = intval($idSearch);
            }

            $sql .= ")";

            for ($i = 1; $i <= 19; $i++) {
                $params['s' . $i] = $sVal;
            }
        }
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

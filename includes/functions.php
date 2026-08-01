<?php
require_once __DIR__ . '/../config/db.php';

function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    return strtolower($text);
}

function getCategoryUrl($cat_slug, $sub_slug = '') {
    if (!empty($sub_slug)) {
        return "category/" . rawurlencode($cat_slug) . "/" . rawurlencode($sub_slug);
    }
    return "category/" . rawurlencode($cat_slug);
}

function getListingUrl($slug) {
    return "listing/" . rawurlencode($slug);
}

function getBlockUrl($slug = '') {
    if (!empty($slug)) {
        return "block/" . rawurlencode($slug);
    }
    return "blocks";
}

function getPanchayatUrl($slug) {
    return "panchayat/" . rawurlencode($slug);
}

function getVillageUrl($slug) {
    return "village/" . rawurlencode($slug);
}



function getBlocks() {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->query("SELECT * FROM blocks ORDER BY block_name ASC");
            $results = $stmt->fetchAll();
            if ($results) return $results;
        } catch (PDOException $e) {}
    }
    return [];
}

function getCategories() {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->query("SELECT * FROM categories WHERE status='ACTIVE' ORDER BY id ASC");
            $results = $stmt->fetchAll();
            if ($results) return $results;
        } catch (PDOException $e) {}
    }
    return [];
}

function getSubcategoriesByCategoryId($category_id) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("SELECT * FROM subcategories WHERE category_id = :cat_id ORDER BY name ASC");
            $stmt->execute(['cat_id' => $category_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {}
    }
    return [];
}

function getAllSubcategories() {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->query("SELECT s.*, c.name as category_name FROM subcategories s LEFT JOIN categories c ON s.category_id = c.id ORDER BY c.name ASC, s.name ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {}
    }
    return [];
}

function getListings($search = '', $category_slug = '', $block_slug = '', $limit = 20, $offset = 0, $subcategory_slug = '') {
    $db = getDB();
    if ($db) {
        try {
            $sql = "SELECT l.*, c.name as category_name, sc.name as subcategory_name, sc.hindi_name as subcategory_hindi_name, b.block_name 
                    FROM listings l 
                    LEFT JOIN categories c ON l.category_id = c.id 
                    LEFT JOIN subcategories sc ON l.subcategory_id = sc.id
                    LEFT JOIN blocks b ON l.block_id = b.id 
                    WHERE l.status='ACTIVE'";
            $params = [];

            if (!empty($search)) {
                $sql .= " AND (l.title LIKE :q OR l.hindi_title LIKE :q OR l.description LIKE :q OR l.services LIKE :q OR l.address LIKE :q)";
                $params['q'] = '%' . $search . '%';
            }
            if (!empty($category_slug)) {
                $sql .= " AND c.slug = :cat_slug";
                $params['cat_slug'] = $category_slug;
            }
            if (!empty($subcategory_slug)) {
                $sql .= " AND sc.slug = :sub_slug";
                $params['sub_slug'] = $subcategory_slug;
            }
            if (!empty($block_slug)) {
                $sql .= " AND b.slug = :blk_slug";
                $params['blk_slug'] = $block_slug;
            }

            $sql .= " ORDER BY l.is_featured DESC, l.is_verified DESC, l.star_rating DESC LIMIT $limit OFFSET $offset";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll();
            if ($results) return $results;
        } catch (PDOException $e) {}
    }
    return [];
}

function getListingBySlug($slug) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("SELECT l.*, c.name as category_name, b.block_name FROM listings l LEFT JOIN categories c ON l.category_id = c.id LEFT JOIN blocks b ON l.block_id = b.id WHERE l.slug = :slug LIMIT 1");
            $stmt->execute(['slug' => $slug]);
            $res = $stmt->fetch();
            if ($res) return $res;
        } catch (PDOException $e) {}
    }
    return null;
}

function renderStarRating($rating) {
    $rating = floatval($rating);
    $full = floor($rating);
    $half = ($rating - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
    
    $html = '<span class="text-warning me-1">';
    for ($i = 0; $i < $full; $i++) {
        $html .= '<i class="bi bi-star-fill me-1"></i>';
    }
    if ($half) {
        $html .= '<i class="bi bi-star-half me-1"></i>';
    }
    for ($i = 0; $i < $empty; $i++) {
        $html .= '<i class="bi bi-star me-1"></i>';
    }
    $html .= '</span> <span class="fw-bold small">' . number_format($rating, 1) . '</span>';
    return $html;
}

function getReviewsByListingId($listing_id) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("SELECT * FROM reviews WHERE listing_id = :lid AND status = 'APPROVED' ORDER BY created_at DESC");
            $stmt->execute(['lid' => $listing_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {}
    }
    return [];
}

function addReview($listing_id, $reviewer_name, $rating, $comment) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("INSERT INTO reviews (listing_id, reviewer_name, rating, comment, status) VALUES (:lid, :rname, :rating, :comment, 'APPROVED')");
            return $stmt->execute([
                'lid' => $listing_id,
                'rname' => $reviewer_name,
                'rating' => $rating,
                'comment' => $comment
            ]);
        } catch (PDOException $e) {}
    }
    return false;
}

// --- ADMIN HELPER FUNCTIONS ---

function ensureAdminsTableExists() {
    $db = getDB();
    if (!$db) return false;
    try {
        $db->exec("CREATE TABLE IF NOT EXISTS `admins` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `password_hash` VARCHAR(255) NOT NULL,
            `full_name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(100),
            `role` ENUM('SUPER_ADMIN','MODERATOR') DEFAULT 'SUPER_ADMIN',
            `last_login` DATETIME NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Check if admin exists
        $stmt = $db->query("SELECT COUNT(*) FROM `admins`");
        if ($stmt->fetchColumn() == 0) {
            $passHash = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO `admins` (username, password_hash, full_name, email, role) VALUES ('admin', :hash, 'SaranIndex Administrator', 'admin@saranindex.com', 'SUPER_ADMIN')");
            $stmt->execute(['hash' => $passHash]);
        }
        return true;
    } catch (PDOException $e) {
        error_log("Error creating admins table: " . $e->getMessage());
        return false;
    }
}

function verifyAdminLogin($username, $password) {
    ensureAdminsTableExists();
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("SELECT * FROM admins WHERE username = :u LIMIT 1");
            $stmt->execute(['u' => $username]);
            $admin = $stmt->fetch();
            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Update last_login
                $up = $db->prepare("UPDATE admins SET last_login = NOW() WHERE id = :id");
                $up->execute(['id' => $admin['id']]);
                return $admin;
            }
        } catch (PDOException $e) {}
    }
    return false;
}

function getAdminStats() {
    $db = getDB();
    $stats = [
        'total_listings' => 0,
        'pending_listings' => 0,
        'verified_listings' => 0,
        'total_categories' => 0,
        'total_blocks' => 0,
        'total_reviews' => 0
    ];

    if ($db) {
        try {
            $stats['total_listings'] = (int)$db->query("SELECT COUNT(*) FROM listings")->fetchColumn();
            $stats['pending_listings'] = (int)$db->query("SELECT COUNT(*) FROM listings WHERE status = 'PENDING'")->fetchColumn();
            $stats['verified_listings'] = (int)$db->query("SELECT COUNT(*) FROM listings WHERE is_verified = 'YES'")->fetchColumn();
            $stats['total_categories'] = (int)$db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
            $stats['total_blocks'] = (int)$db->query("SELECT COUNT(*) FROM blocks")->fetchColumn();
            $stats['total_reviews'] = (int)$db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
            return $stats;
        } catch (PDOException $e) {}
    }

    // Fallback metrics if DB fails
    return $stats;
}

function getAllAdminListings($status = null, $search = null) {
    $db = getDB();
    if ($db) {
        try {
            $sql = "SELECT l.*, c.name as category_name, b.block_name 
                    FROM listings l 
                    LEFT JOIN categories c ON l.category_id = c.id 
                    LEFT JOIN blocks b ON l.block_id = b.id WHERE 1=1";
            $params = [];

            if ($status) {
                $sql .= " AND l.status = :status";
                $params['status'] = $status;
            }

            if ($search) {
                $sql .= " AND (l.title LIKE :search OR l.hindi_title LIKE :search OR l.mobile LIKE :search OR l.contact_person LIKE :search)";
                $params['search'] = '%' . $search . '%';
            }

            $sql .= " ORDER BY l.id DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {}
    }
    return [];
}

function getListingById($id) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("SELECT * FROM listings WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            $res = $stmt->fetch();
            if ($res) return $res;
        } catch (PDOException $e) {}
    }
    return null;
}

function updateListingStatus($id, $status) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("UPDATE listings SET status = :status WHERE id = :id");
            return $stmt->execute(['status' => $status, 'id' => $id]);
        } catch (PDOException $e) {}
    }
    return false;
}

function toggleListingVerified($id) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("UPDATE listings SET is_verified = IF(is_verified = 'YES', 'NO', 'YES') WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {}
    }
    return false;
}

function toggleListingFeatured($id) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("UPDATE listings SET is_featured = IF(is_featured = 'YES', 'NO', 'YES') WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {}
    }
    return false;
}

function deleteListing($id) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("DELETE FROM listings WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {}
    }
    return false;
}

function saveListing($data, $id = null) {
    $db = getDB();
    if (!$db) return false;

    $slug = !empty($data['slug']) ? slugify($data['slug']) : slugify($data['title']);

    try {
        if ($id) {
            $stmt = $db->prepare("UPDATE listings SET 
                entity_type = :entity_type,
                category_id = :category_id,
                subcategory_id = :subcategory_id,
                block_id = :block_id,
                panchayat_id = :panchayat_id,
                village_id = :village_id,
                title = :title,
                hindi_title = :hindi_title,
                slug = :slug,
                contact_person = :contact_person,
                mobile = :mobile,
                whatsapp = :whatsapp,
                email = :email,
                website = :website,
                address = :address,
                pincode = :pincode,
                services = :services,
                description = :description,
                is_verified = :is_verified,
                is_featured = :is_featured,
                status = :status
                WHERE id = :id");
            $data['id'] = $id;
            $data['slug'] = $slug;
            return $stmt->execute($data);
        } else {
            $stmt = $db->prepare("INSERT INTO listings (
                entity_type, category_id, subcategory_id, block_id, panchayat_id, village_id,
                title, hindi_title, slug, contact_person, mobile, whatsapp, email, website,
                address, pincode, services, description, is_verified, is_featured, status
            ) VALUES (
                :entity_type, :category_id, :subcategory_id, :block_id, :panchayat_id, :village_id,
                :title, :hindi_title, :slug, :contact_person, :mobile, :whatsapp, :email, :website,
                :address, :pincode, :services, :description, :is_verified, :is_featured, :status
            )");
            $data['slug'] = $slug;
            return $stmt->execute($data);
        }
    } catch (PDOException $e) {
        error_log("Error saving listing: " . $e->getMessage());
        return false;
    }
}

function getAllAdminCategories() {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {}
    }
    return [];
}

function saveCategory($name, $hindi_name, $icon, $section, $id = null) {
    $db = getDB();
    if (!$db) return false;
    $slug = slugify($name);
    try {
        if ($id) {
            $stmt = $db->prepare("UPDATE categories SET name = :name, hindi_name = :hname, icon = :icon, section = :sec, slug = :slug WHERE id = :id");
            return $stmt->execute(['name' => $name, 'hname' => $hindi_name, 'icon' => $icon, 'sec' => $section, 'slug' => $slug, 'id' => $id]);
        } else {
            $stmt = $db->prepare("INSERT INTO categories (name, hindi_name, icon, slug, section) VALUES (:name, :hname, :icon, :slug, :sec)");
            return $stmt->execute(['name' => $name, 'hname' => $hindi_name, 'icon' => $icon, 'slug' => $slug, 'sec' => $section]);
        }
    } catch (PDOException $e) {}
    return false;
}

function deleteCategory($id) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("DELETE FROM categories WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {}
    }
    return false;
}

function saveSubcategory($name, $hindi_name, $category_id, $keywords = '', $id = null) {
    $db = getDB();
    if (!$db) return false;
    $slug = slugify($name);
    try {
        if ($id) {
            $stmt = $db->prepare("UPDATE subcategories SET name = :name, hindi_name = :hname, category_id = :cat_id, slug = :slug, keywords = :kw WHERE id = :id");
            return $stmt->execute(['name' => $name, 'hname' => $hindi_name, 'cat_id' => $category_id, 'slug' => $slug, 'kw' => $keywords, 'id' => $id]);
        } else {
            $stmt = $db->prepare("INSERT INTO subcategories (name, hindi_name, category_id, slug, keywords) VALUES (:name, :hname, :cat_id, :slug, :kw)");
            return $stmt->execute(['name' => $name, 'hname' => $hindi_name, 'cat_id' => $category_id, 'slug' => $slug, 'kw' => $keywords]);
        }
    } catch (PDOException $e) {}
    return false;
}

function deleteSubcategory($id) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("DELETE FROM subcategories WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {}
    }
    return false;
}

function getAllAdminReviews() {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->query("SELECT r.*, l.title as listing_title FROM reviews r LEFT JOIN listings l ON r.listing_id = l.id ORDER BY r.created_at DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {}
    }
    return [];
}

function deleteReview($id) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("DELETE FROM reviews WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {}
    }
    return false;
}

function approveReview($id) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("UPDATE reviews SET status = 'APPROVED' WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {}
    }
    return false;
}

/**
 * Format registration SMS message using the CITYXN OfferPlant SMS Template:
 * Dear {#var#},
 *  Your registration is completed as {#var#}
 *  Your OTP / EVC / Password is {#var#}
 *  
 *  Regards
 *  CITYXN
 *  OfferPlant
 */
function formatRegistrationSMS($name, $roleOrType, $codeOrPassword) {
    $template = defined('SMS_REGISTRATION_TEMPLATE') 
        ? SMS_REGISTRATION_TEMPLATE 
        : "Dear {#var#},\n Your registration is completed as {#var#}\n Your OTP / EVC / Password is {#var#}\n \n Regards\n CITYXN\n OfferPlant";
    
    $vars = [$name, $roleOrType, $codeOrPassword];
    foreach ($vars as $var) {
        $template = preg_replace('/\{#var#\}/', $var, $template, 1);
    }
    return $template;
}

/**
 * Send registration SMS using CITYXN SMS gateway template
 */
function sendRegistrationSMS($mobile, $name, $roleOrType, $codeOrPassword) {
    $smsText = formatRegistrationSMS($name, $roleOrType, $codeOrPassword);
    
    // Log SMS dispatch
    error_log("SMS dispatched to $mobile via CITYXN: " . str_replace("\n", " ", $smsText));
    
    return [
        'success'  => true,
        'mobile'   => $mobile,
        'sms_text' => $smsText,
        'message'  => 'Registration SMS processed successfully'
    ];
}

/**
 * Fetch all active people entries in Saran District
 */
function getPeople($block_id = null, $limit = 50) {
    $db = getDB();
    if ($db) {
        try {
            $sql = "SELECT p.*, b.block_name FROM people p LEFT JOIN blocks b ON p.block_id = b.id WHERE p.status = 'ACTIVE'";
            $params = [];
            if ($block_id) {
                $sql .= " AND p.block_id = :bid";
                $params['bid'] = $block_id;
            }
            $sql .= " ORDER BY p.full_name ASC LIMIT " . intval($limit);
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $res = $stmt->fetchAll();
            if (!empty($res)) return $res;
        } catch (PDOException $e) {
            error_log("Error fetching people: " . $e->getMessage());
        }
    }
    return [];
}

/**
 * Fetch a single person profile by slug
 */
function getPersonBySlug($slug) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("SELECT p.*, b.block_name FROM people p LEFT JOIN blocks b ON p.block_id = b.id WHERE p.slug = :slug LIMIT 1");
            $stmt->execute(['slug' => $slug]);
            $res = $stmt->fetch();
            if ($res) return $res;
        } catch (PDOException $e) {
            error_log("Error fetching person: " . $e->getMessage());
        }
    }
    return null;
}

// --- CONTACT SUBMISSIONS FUNCTIONS ---

function ensureContactMessagesTableExists() {
    $db = getDB();
    if (!$db) return false;
    try {
        $db->exec("CREATE TABLE IF NOT EXISTS `contact` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(250) NOT NULL,
            `mobile` VARCHAR(20) NOT NULL,
            `email` VARCHAR(255) DEFAULT NULL,
            `subject` VARCHAR(254) DEFAULT NULL,
            `message` TEXT DEFAULT NULL,
            `mobile_status` VARCHAR(10) DEFAULT NULL,
            `status` VARCHAR(50) DEFAULT 'UNREAD',
            `profile_id` INT DEFAULT 0,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `created_by` INT DEFAULT 0,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `updated_by` INT DEFAULT 0,
            `reply` VARCHAR(1000) DEFAULT '',
            `reply_message` TEXT DEFAULT NULL,
            `replied_at` TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        try {
            $cols = $db->query("SHOW COLUMNS FROM `contact` LIKE 'email'")->fetchAll();
            if (empty($cols)) {
                $db->exec("ALTER TABLE `contact` ADD COLUMN `email` VARCHAR(100) DEFAULT NULL AFTER `mobile`");
            }
        } catch (PDOException $ex) {}

        return true;
    } catch (PDOException $e) {
        error_log("Error in ensureContactMessagesTableExists: " . $e->getMessage());
        return false;
    }
}

function saveContactMessage($name, $mobile, $email, $subject, $message) {
    ensureContactMessagesTableExists();
    $db = getDB();
    if (!$db) return false;
    try {
        $stmt = $db->prepare("INSERT INTO `contact` (`name`, `mobile`, `email`, `subject`, `message`, `status`, `profile_id`, `created_at`, `created_by`, `updated_by`, `reply`) VALUES (:name, :mobile, :email, :subject, :message, 'UNREAD', 0, NOW(), 0, 0, '')");
        return $stmt->execute([
            'name' => $name,
            'mobile' => $mobile,
            'email' => $email ?: null,
            'subject' => $subject,
            'message' => $message
        ]);
    } catch (PDOException $e) {
        error_log("Error saving contact message: " . $e->getMessage());
        return false;
    }
}

function getAllContactMessages($status = null) {
    ensureContactMessagesTableExists();
    $db = getDB();
    if (!$db) return [];
    try {
        if ($status) {
            $stmt = $db->prepare("SELECT * FROM `contact` WHERE status = :status ORDER BY created_at DESC");
            $stmt->execute(['status' => $status]);
        } else {
            $stmt = $db->query("SELECT * FROM `contact` ORDER BY created_at DESC");
        }
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching contact messages: " . $e->getMessage());
        return [];
    }
}

function updateContactMessageStatus($id, $status) {
    ensureContactMessagesTableExists();
    $db = getDB();
    if (!$db) return false;
    try {
        $stmt = $db->prepare("UPDATE `contact` SET `status` = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    } catch (PDOException $e) {
        return false;
    }
}

function deleteContactMessage($id) {
    ensureContactMessagesTableExists();
    $db = getDB();
    if (!$db) return false;
    try {
        $stmt = $db->prepare("DELETE FROM `contact` WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
        return false;
    }
}

// --- CENSUS VILLAGE FUNCTIONS ---

function getVillageUniqueSlug($name, $code) {
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug . '-' . trim($code);
}

function slugifyVillage($name) {
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    return trim($slug, '-');
}

function getCensusVillageByCodeOrId($val) {
    $db = getDB();
    if (!$db || empty($val)) return null;

    try {
        $code = null;
        if (preg_match('/-(\d+)$/', $val, $matches)) {
            $code = $matches[1];
        } elseif (is_numeric($val)) {
            $code = $val;
        }

        if ($code) {
            $stmt = $db->prepare("SELECT c.*, l.name_hindi, l.block AS block_name, l.village_lgd_code, b.id AS block_id, b.slug AS block_slug, b.pincode 
                FROM census c 
                LEFT JOIN lgd_village l ON (c.town_village_code = l.census_2011_code OR l.census_2011_code LIKE CONCAT('%', c.town_village_code, '%'))
                LEFT JOIN blocks b ON (l.block = b.name OR l.block = b.name_english OR l.block LIKE CONCAT(b.name, '%'))
                WHERE c.level = 'VILLAGE' AND (c.town_village_code = :val1 OR c.id = :val2) 
                LIMIT 1");
            $stmt->execute(['val1' => $code, 'val2' => is_numeric($code) ? intval($code) : 0]);
            $res = $stmt->fetch();

            if ($res) {
                $res['unique_slug'] = getVillageUniqueSlug($res['name'], $res['town_village_code']);
                $res['slug'] = $res['unique_slug'];
                return $res;
            }
        }

        $stmt2 = $db->query("SELECT c.*, l.name_hindi, l.block AS block_name, l.village_lgd_code, b.id AS block_id, b.slug AS block_slug, b.pincode 
            FROM census c 
            LEFT JOIN lgd_village l ON (c.town_village_code = l.census_2011_code OR l.census_2011_code LIKE CONCAT('%', c.town_village_code, '%'))
            LEFT JOIN blocks b ON (l.block = b.name OR l.block = b.name_english OR l.block LIKE CONCAT(b.name, '%'))
            WHERE c.level = 'VILLAGE'");
        $all = $stmt2->fetchAll();

        $searchSlug = strtolower(trim($val));
        foreach ($all as $v) {
            $uSlug = getVillageUniqueSlug($v['name'], $v['town_village_code']);
            $nSlug = slugifyVillage($v['name']);
            if ($uSlug === $searchSlug || $nSlug === $searchSlug) {
                $v['unique_slug'] = $uSlug;
                $v['slug'] = $uSlug;
                return $v;
            }
        }
        return null;
    } catch (PDOException $e) {
        error_log("Error fetching census village: " . $e->getMessage());
        return null;
    }
}

function getCensusVillages($block = null, $search = null, $limit = 24, $offset = 0) {
    $db = getDB();
    if (!$db) return [];

    try {
        $where = ["c.level = 'VILLAGE'"];
        $params = [];

        if (!empty($block)) {
            $where[] = "(l.block = :b1 OR l.block LIKE :b2 OR c.name LIKE :b3)";
            $params['b1'] = $block;
            $params['b2'] = "%$block%";
            $params['b3'] = "%$block%";
        }

        if (!empty($search)) {
            $where[] = "(c.name LIKE :s1 OR l.name_hindi LIKE :s2 OR c.town_village_code LIKE :s3 OR l.village_lgd_code LIKE :s4)";
            $params['s1'] = "%$search%";
            $params['s2'] = "%$search%";
            $params['s3'] = "%$search%";
            $params['s4'] = "%$search%";
        }

        $whereSql = implode(" AND ", $where);
        $sql = "SELECT c.*, l.name_hindi, l.block AS block_name, l.village_lgd_code, b.slug AS block_slug 
                FROM census c 
                LEFT JOIN lgd_village l ON (c.town_village_code = l.census_2011_code OR l.census_2011_code LIKE CONCAT('%', c.town_village_code, '%'))
                LEFT JOIN blocks b ON (l.block = b.name OR l.block = b.name_english OR l.block LIKE CONCAT(b.name, '%'))
                WHERE $whereSql 
                ORDER BY c.name ASC 
                LIMIT " . intval($limit) . " OFFSET " . intval($offset);

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$r) {
            $r['unique_slug'] = getVillageUniqueSlug($r['name'], $r['town_village_code']);
            $r['slug'] = $r['unique_slug'];
        }
        return $rows;
    } catch (PDOException $e) {
        error_log("Error fetching census villages: " . $e->getMessage());
        return [];
    }
}

function getTotalCensusVillagesCount($block = null, $search = null) {
    $db = getDB();
    if (!$db) return 0;

    try {
        $where = ["c.level = 'VILLAGE'"];
        $params = [];

        if (!empty($block)) {
            $where[] = "(l.block = :b1 OR l.block LIKE :b2 OR c.name LIKE :b3)";
            $params['b1'] = $block;
            $params['b2'] = "%$block%";
            $params['b3'] = "%$block%";
        }

        if (!empty($search)) {
            $where[] = "(c.name LIKE :s1 OR l.name_hindi LIKE :s2 OR c.town_village_code LIKE :s3 OR l.village_lgd_code LIKE :s4)";
            $params['s1'] = "%$search%";
            $params['s2'] = "%$search%";
            $params['s3'] = "%$search%";
            $params['s4'] = "%$search%";
        }

        $whereSql = implode(" AND ", $where);
        $sql = "SELECT COUNT(*) FROM census c 
                LEFT JOIN lgd_village l ON (c.town_village_code = l.census_2011_code OR l.census_2011_code LIKE CONCAT('%', c.town_village_code, '%')) 
                WHERE $whereSql";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return intval($stmt->fetchColumn());
    } catch (PDOException $e) {
        return 0;
    }
}

function getNearbyCensusVillages($blockName, $excludeCode, $limit = 6) {
    $db = getDB();
    if (!$db) return [];

    try {
        $stmt = $db->prepare("SELECT c.*, l.name_hindi, l.block AS block_name, l.village_lgd_code 
            FROM census c 
            LEFT JOIN lgd_village l ON c.town_village_code = l.census_2011_code 
            WHERE c.level = 'VILLAGE' AND (l.block = :block OR l.block LIKE :block_like) AND c.town_village_code != :exc 
            ORDER BY RAND() 
            LIMIT " . intval($limit));
        $stmt->execute([
            'block' => $blockName ?: 'Amnour',
            'block_like' => "%" . ($blockName ?: 'Amnour') . "%",
            'exc' => $excludeCode
        ]);
        $rows = $stmt->fetchAll();
        foreach ($rows as &$r) {
            $r['unique_slug'] = getVillageUniqueSlug($r['name'], $r['town_village_code']);
            $r['slug'] = $r['unique_slug'];
        }
        return $rows;
    } catch (PDOException $e) {
        return [];
    }
}

// --- HALKA & REVENUE VILLAGE FUNCTIONS ---

function getHalkaRecords($block = null, $search = null, $limit = 24, $offset = 0) {
    $db = getDB();
    if (!$db) return [];

    try {
        $where = ["1=1"];
        $params = [];

        if (!empty($block)) {
            $where[] = "(block = :b1 OR block LIKE :b2)";
            $params['b1'] = $block;
            $params['b2'] = "%$block%";
        }

        if (!empty($search)) {
            $where[] = "(mauja_name LIKE :s1 OR halka_name LIKE :s2 OR mauja_code LIKE :s3 OR halka_code = :s4 OR block LIKE :s5)";
            $params['s1'] = "%$search%";
            $params['s2'] = "%$search%";
            $params['s3'] = "%$search%";
            $params['s4'] = is_numeric($search) ? intval($search) : -1;
            $params['s5'] = "%$search%";
        }

        $whereSql = implode(" AND ", $where);
        $sql = "SELECT * FROM halka 
                WHERE $whereSql 
                ORDER BY block ASC, halka_code ASC, mauja_name ASC 
                LIMIT " . intval($limit) . " OFFSET " . intval($offset);

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching halka records: " . $e->getMessage());
        return [];
    }
}

function getTotalHalkaCount($block = null, $search = null) {
    $db = getDB();
    if (!$db) return 0;

    try {
        $where = ["1=1"];
        $params = [];

        if (!empty($block)) {
            $where[] = "(block = :b1 OR block LIKE :b2)";
            $params['b1'] = $block;
            $params['b2'] = "%$block%";
        }

        if (!empty($search)) {
            $where[] = "(mauja_name LIKE :s1 OR halka_name LIKE :s2 OR mauja_code LIKE :s3 OR halka_code = :s4 OR block LIKE :s5)";
            $params['s1'] = "%$search%";
            $params['s2'] = "%$search%";
            $params['s3'] = "%$search%";
            $params['s4'] = is_numeric($search) ? intval($search) : -1;
            $params['s5'] = "%$search%";
        }

        $whereSql = implode(" AND ", $where);
        $sql = "SELECT COUNT(*) FROM halka WHERE $whereSql";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return intval($stmt->fetchColumn());
    } catch (PDOException $e) {
        return 0;
    }
}

function getHalkaBlocks() {
    $db = getDB();
    if (!$db) return [];
    try {
        return $db->query("SELECT DISTINCT block FROM halka ORDER BY block ASC")->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        return [];
    }
}

function getHalkaStats() {
    $db = getDB();
    if (!$db) return ['maujas' => 0, 'halkas' => 0, 'blocks' => 0];
    try {
        $maujas = $db->query("SELECT COUNT(*) FROM halka")->fetchColumn();
        $halkas = $db->query("SELECT COUNT(DISTINCT CONCAT(block, '-', halka_code)) FROM halka")->fetchColumn();
        $blocks = $db->query("SELECT COUNT(DISTINCT block) FROM halka")->fetchColumn();
        return [
            'maujas' => intval($maujas),
            'halkas' => intval($halkas),
            'blocks' => intval($blocks)
        ];
    } catch (PDOException $e) {
        return ['maujas' => 0, 'halkas' => 0, 'blocks' => 0];
    }
}

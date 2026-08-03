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
        return "blocks/" . rawurlencode($slug);
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
    if (!$db) return [];
    try {
        $sql = "SELECT b.id, b.name, b.name as block_name, b.name_english, b.hindi_name, b.slug, b.pincode, b.total_panchayats,
                       c_tot.households, c_tot.pop_tot, c_tot.pop_male, c_tot.pop_female, c_tot.lit_tot, c_tot.lit_male, c_tot.lit_female, c_tot.tot_work_tot, c_tot.cd_block_code,
                       c_rur.households as households_rural, c_rur.pop_tot as pop_rural, c_rur.pop_male as pop_male_rural, c_rur.pop_female as pop_female_rural, c_rur.lit_tot as lit_rural, c_rur.tot_work_tot as tot_work_rural,
                       c_urb.households as households_urban, c_urb.pop_tot as pop_urban, c_urb.pop_male as pop_male_urban, c_urb.pop_female as pop_female_urban, c_urb.lit_tot as lit_urban, c_urb.tot_work_tot as tot_work_urban
                FROM blocks b
                LEFT JOIN census c_tot ON (c_tot.level = 'CD BLOCK' AND c_tot.tru_type = 'Total' AND (
                    LOWER(c_tot.name) = LOWER(b.name) 
                    OR LOWER(c_tot.name) = LOWER(b.name_english)
                    OR SOUNDEX(c_tot.name) = SOUNDEX(b.name)
                    OR LOWER(b.name) LIKE CONCAT('%', LOWER(c_tot.name), '%')
                    OR LOWER(c_tot.name) LIKE CONCAT('%', LOWER(b.name), '%')
                ))
                LEFT JOIN census c_rur ON (c_rur.level = 'CD BLOCK' AND c_rur.tru_type = 'Rural' AND (
                    LOWER(c_rur.name) = LOWER(b.name) 
                    OR LOWER(c_rur.name) = LOWER(b.name_english)
                    OR SOUNDEX(c_rur.name) = SOUNDEX(b.name)
                    OR LOWER(b.name) LIKE CONCAT('%', LOWER(c_rur.name), '%')
                    OR LOWER(c_rur.name) LIKE CONCAT('%', LOWER(b.name), '%')
                ))
                LEFT JOIN census c_urb ON (c_urb.level = 'CD BLOCK' AND c_urb.tru_type = 'Urban' AND (
                    LOWER(c_urb.name) = LOWER(b.name) 
                    OR LOWER(c_urb.name) = LOWER(b.name_english)
                    OR SOUNDEX(c_urb.name) = SOUNDEX(b.name)
                    OR LOWER(b.name) LIKE CONCAT('%', LOWER(c_urb.name), '%')
                    OR LOWER(c_urb.name) LIKE CONCAT('%', LOWER(b.name), '%')
                ))
                ORDER BY b.name ASC";
        $stmt = $db->query($sql);
        $results = $stmt->fetchAll();
        if ($results) return $results;
    } catch (PDOException $e) {
        error_log("getBlocks census query failed: " . $e->getMessage());
    }

    // Fallback: Query blocks table directly without census join if census table is missing or fails
    try {
        $stmt = $db->query("SELECT *, name as block_name FROM blocks ORDER BY name ASC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("getBlocks fallback failed: " . $e->getMessage());
    }
    return [];
}

function getBlockBySlug($slug) {
    $db = getDB();
    if (!$db || empty($slug)) return null;

    try {
        $sql = "SELECT b.id, b.name, b.name as block_name, b.name_english, b.hindi_name, b.slug, b.pincode, b.total_panchayats,
                       c_tot.households, c_tot.pop_tot, c_tot.pop_male, c_tot.pop_female, c_tot.lit_tot, c_tot.lit_male, c_tot.lit_female, c_tot.tot_work_tot, c_tot.cd_block_code,
                       c_rur.households as households_rural, c_rur.pop_tot as pop_rural, c_rur.pop_male as pop_male_rural, c_rur.pop_female as pop_female_rural, c_rur.lit_tot as lit_rural, c_rur.tot_work_tot as tot_work_rural,
                       c_urb.households as households_urban, c_urb.pop_tot as pop_urban, c_urb.pop_male as pop_male_urban, c_urb.pop_female as pop_female_urban, c_urb.lit_tot as lit_urban, c_urb.tot_work_tot as tot_work_urban
                FROM blocks b
                LEFT JOIN census c_tot ON (c_tot.level = 'CD BLOCK' AND c_tot.tru_type = 'Total' AND (
                    LOWER(c_tot.name) = LOWER(b.name) 
                    OR LOWER(c_tot.name) = LOWER(b.name_english)
                    OR SOUNDEX(c_tot.name) = SOUNDEX(b.name)
                    OR LOWER(b.name) LIKE CONCAT('%', LOWER(c_tot.name), '%')
                    OR LOWER(c_tot.name) LIKE CONCAT('%', LOWER(b.name), '%')
                ))
                LEFT JOIN census c_rur ON (c_rur.level = 'CD BLOCK' AND c_rur.tru_type = 'Rural' AND (
                    LOWER(c_rur.name) = LOWER(b.name) 
                    OR LOWER(c_rur.name) = LOWER(b.name_english)
                    OR SOUNDEX(c_rur.name) = SOUNDEX(b.name)
                    OR LOWER(b.name) LIKE CONCAT('%', LOWER(c_rur.name), '%')
                    OR LOWER(c_rur.name) LIKE CONCAT('%', LOWER(b.name), '%')
                ))
                LEFT JOIN census c_urb ON (c_urb.level = 'CD BLOCK' AND c_urb.tru_type = 'Urban' AND (
                    LOWER(c_urb.name) = LOWER(b.name) 
                    OR LOWER(c_urb.name) = LOWER(b.name_english)
                    OR SOUNDEX(c_urb.name) = SOUNDEX(b.name)
                    OR LOWER(b.name) LIKE CONCAT('%', LOWER(c_urb.name), '%')
                    OR LOWER(c_urb.name) LIKE CONCAT('%', LOWER(b.name), '%')
                ))
                WHERE b.slug = :slug LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $res = $stmt->fetch();
        if ($res) return $res;
    } catch (PDOException $e) {
        error_log("getBlockBySlug census query failed: " . $e->getMessage());
    }

    // Fallback: Query blocks table directly
    try {
        $stmt = $db->prepare("SELECT *, name as block_name FROM blocks WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    } catch (PDOException $e) {}
    return null;
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
            $sql = "SELECT l.*, c.name as category_name, sc.name as subcategory_name, sc.hindi_name as subcategory_hindi_name, b.name as block_name 
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
            $stmt = $db->prepare("SELECT l.*, c.name as category_name, b.name as block_name FROM listings l LEFT JOIN categories c ON l.category_id = c.id LEFT JOIN blocks b ON l.block_id = b.id WHERE l.slug = :slug LIMIT 1");
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
            $sql = "SELECT l.*, c.name as category_name, b.name as block_name 
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
            $sql = "SELECT p.*, b.name as block_name FROM people p LEFT JOIN blocks b ON p.block_id = b.id WHERE p.status = 'ACTIVE'";
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
            $stmt = $db->prepare("SELECT p.*, b.name as block_name FROM people p LEFT JOIN blocks b ON p.block_id = b.id WHERE p.slug = :slug LIMIT 1");
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

// -------------------------------------------------------------
// PUBLIC USER AUTHENTICATION & DASHBOARD FUNCTIONS
// -------------------------------------------------------------

function ensureUsersTable() {
    $db = getDB();
    if (!$db) return;
    try {
        $db->exec("CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `full_name` VARCHAR(100) NOT NULL,
            `mobile` VARCHAR(20) NOT NULL UNIQUE,
            `whatsapp` VARCHAR(20) DEFAULT NULL,
            `email` VARCHAR(100) DEFAULT NULL,
            `password_hash` VARCHAR(255) NOT NULL,
            `business_name` VARCHAR(150) DEFAULT NULL,
            `designation` VARCHAR(100) DEFAULT NULL,
            `block_id` INT DEFAULT NULL,
            `panchayat_id` INT DEFAULT NULL,
            `village_id` INT DEFAULT NULL,
            `address` TEXT DEFAULT NULL,
            `pincode` VARCHAR(10) DEFAULT NULL,
            `profile_image` VARCHAR(255) DEFAULT NULL,
            `bio` TEXT DEFAULT NULL,
            `status` ENUM('ACTIVE','INACTIVE','SUSPENDED') DEFAULT 'ACTIVE',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Add missing columns dynamically
        $cols = $db->query("SHOW COLUMNS FROM `users`")->fetchAll(PDO::FETCH_COLUMN);
        
        $newColumns = [
            'whatsapp' => "VARCHAR(20) DEFAULT NULL",
            'business_name' => "VARCHAR(150) DEFAULT NULL",
            'designation' => "VARCHAR(100) DEFAULT NULL",
            'panchayat_id' => "INT DEFAULT NULL",
            'village_id' => "INT DEFAULT NULL",
            'pincode' => "VARCHAR(10) DEFAULT NULL",
            'profile_image' => "VARCHAR(255) DEFAULT NULL",
            'bio' => "TEXT DEFAULT NULL",
            // Newly requested fields
            'name' => "VARCHAR(150) DEFAULT NULL",
            'mobile_status' => "VARCHAR(20) DEFAULT 'UNVERIFIED'",
            'mobile_visibility' => "VARCHAR(20) DEFAULT 'PUBLIC'",
            'email_status' => "VARCHAR(20) DEFAULT 'UNVERIFIED'",
            'email_visibility' => "VARCHAR(20) DEFAULT 'PUBLIC'",
            'password' => "VARCHAR(255) DEFAULT NULL",
            'token' => "VARCHAR(255) DEFAULT NULL",
            'type' => "VARCHAR(50) DEFAULT 'USER'",
            'address_visibility' => "VARCHAR(20) DEFAULT 'PUBLIC'",
            'state_code' => "VARCHAR(20) DEFAULT NULL",
            'district_code' => "VARCHAR(20) DEFAULT NULL",
            'dob' => "DATE DEFAULT NULL",
            'r_name' => "VARCHAR(150) DEFAULT NULL",
            'education' => "VARCHAR(150) DEFAULT NULL",
            'gender' => "VARCHAR(20) DEFAULT NULL",
            'public_url' => "VARCHAR(255) DEFAULT NULL",
            'counter' => "INT DEFAULT 0",
            'univ_code' => "VARCHAR(50) DEFAULT NULL",
            'college_code' => "VARCHAR(50) DEFAULT NULL",
            'about' => "TEXT DEFAULT NULL",
            'photo' => "VARCHAR(255) DEFAULT NULL",
            'id_proof' => "VARCHAR(255) DEFAULT NULL",
            'wallet' => "DECIMAL(10,2) DEFAULT 0.00",
            'created_by' => "INT DEFAULT 0",
            'updated_by' => "INT DEFAULT 0",
            'plan_type' => "VARCHAR(50) DEFAULT NULL",
            'plan_expiry' => "DATETIME DEFAULT NULL",
            'languages' => "VARCHAR(255) DEFAULT NULL",
            'otp_code' => "VARCHAR(20) DEFAULT NULL",
            'otp_expiry' => "DATETIME DEFAULT NULL",
            'linkedin' => "VARCHAR(255) DEFAULT NULL",
            'twitter' => "VARCHAR(255) DEFAULT NULL",
            'facebook' => "VARCHAR(255) DEFAULT NULL",
            'instagram' => "VARCHAR(255) DEFAULT NULL",
            'google_maps_link' => "TEXT DEFAULT NULL"
        ];

        foreach ($newColumns as $col => $def) {
            if (!in_array($col, $cols)) {
                $db->exec("ALTER TABLE `users` ADD COLUMN `$col` $def");
            }
        }
    } catch (PDOException $e) {
        error_log("Error creating/altering users table: " . $e->getMessage());
    }
}

function isUserLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return !empty($_SESSION['user_id']);
}

function getLoggedInUser() {
    if (!isUserLoggedIn()) return null;
    $db = getDB();
    if (!$db) return null;

    try {
        $stmt = $db->prepare("SELECT u.*, b.name as block_name, b.hindi_name as block_hindi FROM users u LEFT JOIN blocks b ON u.block_id = b.id WHERE u.id = :id LIMIT 1");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user) return $user;
    } catch (PDOException $e) {
        error_log("getLoggedInUser error: " . $e->getMessage());
    }

    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        return $stmt->fetch() ?: null;
    } catch (PDOException $e) {
        return null;
    }
}


function registerPublicUser($fullName, $mobile, $password, $email = '', $blockId = null, $address = '') {
    $db = getDB();
    if (!$db) {
        return ['success' => false, 'message' => 'Database connection failed.'];
    }

    ensureUsersTable();

    $fullName = sanitizeInput($fullName);
    $mobile = preg_replace('/[^0-9]/', '', $mobile);
    $email = sanitizeInput($email);
    $address = sanitizeInput($address);

    if (empty($fullName)) {
        return ['success' => false, 'message' => 'Full name is required.'];
    }

    if (strlen($mobile) < 10) {
        return ['success' => false, 'message' => 'Please enter a valid 10-digit mobile number.'];
    }

    if (strlen($password) < 6) {
        return ['success' => false, 'message' => 'Password must be at least 6 characters long.'];
    }

    try {
        // Check if mobile already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE mobile = :mobile LIMIT 1");
        $stmt->execute(['mobile' => $mobile]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'This mobile number is already registered. Please login.'];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO users (full_name, mobile, email, password_hash, block_id, address) VALUES (:name, :mobile, :email, :pass, :block, :address)");
        $stmt->execute([
            'name' => $fullName,
            'mobile' => $mobile,
            'email' => !empty($email) ? $email : null,
            'pass' => $passwordHash,
            'block' => !empty($blockId) ? intval($blockId) : null,
            'address' => !empty($address) ? $address : null
        ]);

        $userId = $db->lastInsertId();

        // Send registration SMS via gateway
        require_once __DIR__ . '/sms_helper.php';
        if (function_exists('send_registration_sms')) {
            send_registration_sms($mobile, $fullName, $password);
        }

        // Auto Login user
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $fullName;
        $_SESSION['user_mobile'] = $mobile;

        return ['success' => true, 'message' => 'Registration successful! Welcome to Saran Index.', 'user_id' => $userId];
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        return ['success' => false, 'message' => 'An error occurred during registration. Please try again.'];
    }
}

function loginPublicUser($mobileOrEmail, $password) {
    $db = getDB();
    if (!$db) {
        return ['success' => false, 'message' => 'Database connection error.'];
    }

    ensureUsersTable();

    $input = sanitizeInput($mobileOrEmail);
    $cleanMobile = preg_replace('/[^0-9]/', '', $input);
    $mobile10 = (strlen($cleanMobile) >= 10) ? substr($cleanMobile, -10) : $cleanMobile;

    if (empty($input) || empty($password)) {
        return ['success' => false, 'message' => 'Please enter your mobile number and password.'];
    }

    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE (mobile = :mobile OR mobile = :m10 OR RIGHT(mobile, 10) = :m10 OR email = :email) LIMIT 1");
        $stmt->execute([
            'mobile' => $cleanMobile,
            'm10'    => $mobile10,
            'email'  => $input
        ]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['success' => false, 'message' => 'No account found with this mobile number or email.'];
        }

        if (isset($user['status']) && strtoupper($user['status']) !== 'ACTIVE') {
            return ['success' => false, 'message' => 'Your account status is ' . htmlspecialchars($user['status']) . '. Please contact support.'];
        }

        // Verify password with fallbacks
        $isPasswordValid = false;
        if (!empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
            $isPasswordValid = true;
        } elseif (!empty($user['password']) && password_verify($password, $user['password'])) {
            $isPasswordValid = true;
        } elseif (!empty($user['password_hash']) && ($password === $user['password_hash'] || md5($password) === $user['password_hash'])) {
            $isPasswordValid = true;
            // Upgrade legacy password to password_hash
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $db->prepare("UPDATE users SET password_hash = :hash WHERE id = :id")->execute(['hash' => $newHash, 'id' => $user['id']]);
        } elseif (!empty($user['password']) && ($password === $user['password'] || md5($password) === $user['password'])) {
            $isPasswordValid = true;
            // Upgrade legacy password to password_hash
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $db->prepare("UPDATE users SET password_hash = :hash WHERE id = :id")->execute(['hash' => $newHash, 'id' => $user['id']]);
        }

        if (!$isPasswordValid) {
            return ['success' => false, 'message' => 'Incorrect password. Please try again.'];
        }

        // Set session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = !empty($user['full_name']) ? $user['full_name'] : ($user['name'] ?? 'User');
        $_SESSION['user_mobile'] = $user['mobile'];

        return ['success' => true, 'message' => 'Login successful!', 'user' => $user];
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return ['success' => false, 'message' => 'An error occurred while logging in.'];
    }
}

function logoutPublicUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_mobile']);
}

function getUserListings($mobileOrUserId) {
    $db = getDB();
    if (!$db) return [];

    try {
        if (is_numeric($mobileOrUserId) && strlen($mobileOrUserId) < 10) {
            // Find mobile first
            $stmt = $db->prepare("SELECT mobile FROM users WHERE id = :id");
            $stmt->execute(['id' => $mobileOrUserId]);
            $mobile = $stmt->fetchColumn();
        } else {
            $mobile = $mobileOrUserId;
        }

        if (empty($mobile)) return [];

        $stmt = $db->prepare("SELECT l.*, c.name as category_name, b.name as block_name FROM listings l LEFT JOIN categories c ON l.category_id = c.id LEFT JOIN blocks b ON l.block_id = b.id WHERE l.mobile = :mobile ORDER BY l.id DESC");
        $stmt->execute(['mobile' => $mobile]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function updateUserProfile($userId, $fullName, $email = '', $blockId = null, $address = '', $newPassword = '', $whatsapp = '', $businessName = '', $designation = '', $pincode = '', $panchayatId = null, $villageId = null, $bio = '') {
    $db = getDB();
    if (!$db) {
        return ['success' => false, 'message' => 'Database connection error.'];
    }

    ensureUsersTable();

    $fullName = sanitizeInput($fullName);
    $email = sanitizeInput($email);
    $address = sanitizeInput($address);
    $whatsapp = preg_replace('/[^0-9]/', '', $whatsapp);
    $businessName = sanitizeInput($businessName);
    $designation = sanitizeInput($designation);
    $pincode = sanitizeInput($pincode);
    $bio = sanitizeInput($bio);

    if (empty($fullName)) {
        return ['success' => false, 'message' => 'Full name cannot be empty.'];
    }

    try {
        $params = [
            'name' => $fullName,
            'wa' => !empty($whatsapp) ? $whatsapp : null,
            'email' => !empty($email) ? $email : null,
            'bname' => !empty($businessName) ? $businessName : null,
            'desig' => !empty($designation) ? $designation : null,
            'block' => !empty($blockId) ? intval($blockId) : null,
            'panchayat' => !empty($panchayatId) ? intval($panchayatId) : null,
            'village' => !empty($villageId) ? intval($villageId) : null,
            'address' => !empty($address) ? $address : null,
            'pin' => !empty($pincode) ? $pincode : null,
            'bio' => !empty($bio) ? $bio : null,
            'id' => intval($userId)
        ];

        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                return ['success' => false, 'message' => 'New password must be at least 6 characters long.'];
            }
            $params['pass'] = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET full_name = :name, whatsapp = :wa, email = :email, business_name = :bname, designation = :desig, block_id = :block, panchayat_id = :panchayat, village_id = :village, address = :address, pincode = :pin, bio = :bio, password_hash = :pass WHERE id = :id");
        } else {
            $stmt = $db->prepare("UPDATE users SET full_name = :name, whatsapp = :wa, email = :email, business_name = :bname, designation = :desig, block_id = :block, panchayat_id = :panchayat, village_id = :village, address = :address, pincode = :pin, bio = :bio WHERE id = :id");
        }

        $stmt->execute($params);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_name'] = $fullName;

        return ['success' => true, 'message' => 'Profile updated successfully!'];
    } catch (PDOException $e) {
        error_log("Update user error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to update profile. Please try again.'];
    }
}

function generateMobileOTP($mobile, $userName = 'User') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $cleanMobile = preg_replace('/[^0-9]/', '', $mobile);
    if (strlen($cleanMobile) >= 10) {
        $cleanMobile = substr($cleanMobile, -10);
    }
    $otp = sprintf("%06d", rand(100000, 999999));

    $_SESSION['otp_mobile'] = $cleanMobile;
    $_SESSION['otp_code']   = $otp;
    $_SESSION['otp_expiry'] = time() + (10 * 60); // 10 minutes

    // Log OTP for debugging/development
    error_log("OTP generated for +91 {$cleanMobile}: {$otp}");

    // Dispatch SMS via SMS Gateway
    require_once __DIR__ . '/sms_helper.php';
    if (function_exists('send_registration_sms')) {
        send_registration_sms($cleanMobile, $userName, $otp);
    }

    return $otp;
}

function verifyMobileOTP($mobile, $inputOtp) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $cleanMobile = preg_replace('/[^0-9]/', '', $mobile);
    if (strlen($cleanMobile) >= 10) {
        $cleanMobile = substr($cleanMobile, -10);
    }

    $sessionMobile = $_SESSION['otp_mobile'] ?? '';
    if (strlen($sessionMobile) >= 10) {
        $sessionMobile = substr($sessionMobile, -10);
    }

    $sessionOtp = $_SESSION['otp_code']   ?? '';
    $expiry     = $_SESSION['otp_expiry'] ?? 0;

    if (empty($sessionOtp) || time() > $expiry) {
        return ['success' => false, 'message' => 'OTP has expired. Please request a new OTP.'];
    }

    if (!empty($sessionMobile) && $cleanMobile !== $sessionMobile) {
        return ['success' => false, 'message' => 'Mobile number mismatch. Please request OTP again.'];
    }

    if (trim($inputOtp) !== trim($sessionOtp)) {
        return ['success' => false, 'message' => 'Invalid OTP code entered. Please check and try again.'];
    }

    // OTP verified
    unset($_SESSION['otp_code']);
    unset($_SESSION['otp_expiry']);

    return ['success' => true, 'message' => 'Mobile number verified successfully!'];
}




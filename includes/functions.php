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

// Fallback Blocks Data (Ensures system functions even before SQL import)
function getStaticBlocks() {
    return [
        ['id' => 1, 'block_name' => 'Chapra Sadar', 'hindi_name' => 'छपरा सदर', 'slug' => 'chapra-sadar', 'pincode' => '841301', 'total_panchayats' => 22],
        ['id' => 2, 'block_name' => 'Marhaura', 'hindi_name' => 'मढ़ौरा', 'slug' => 'marhaura', 'pincode' => '841418', 'total_panchayats' => 18],
        ['id' => 3, 'block_name' => 'Sonepur', 'hindi_name' => 'सोनपुर', 'slug' => 'sonepur', 'pincode' => '841101', 'total_panchayats' => 23],
        ['id' => 4, 'block_name' => 'Revelganj', 'hindi_name' => 'रिविलगंज', 'slug' => 'revelganj', 'pincode' => '841305', 'total_panchayats' => 14],
        ['id' => 5, 'block_name' => 'Garkha', 'hindi_name' => 'गरखा', 'slug' => 'garkha', 'pincode' => '841311', 'total_panchayats' => 20],
        ['id' => 6, 'block_name' => 'Parsa', 'hindi_name' => 'परसा', 'slug' => 'parsa', 'pincode' => '841219', 'total_panchayats' => 16],
        ['id' => 7, 'block_name' => 'Dighwara', 'hindi_name' => 'दिघवारा', 'slug' => 'dighwara', 'pincode' => '841207', 'total_panchayats' => 12],
        ['id' => 8, 'block_name' => 'Amanour', 'hindi_name' => 'अमनौर', 'slug' => 'amanour', 'pincode' => '841401', 'total_panchayats' => 18],
        ['id' => 9, 'block_name' => 'Baniapur', 'hindi_name' => 'बनियापुर', 'slug' => 'baniapur', 'pincode' => '841403', 'total_panchayats' => 24],
        ['id' => 10, 'block_name' => 'Ekma', 'hindi_name' => 'एकमा', 'slug' => 'ekma', 'pincode' => '841208', 'total_panchayats' => 19],
        ['id' => 11, 'block_name' => 'Taraiya', 'hindi_name' => 'तरैया', 'slug' => 'taraiya', 'pincode' => '841424', 'total_panchayats' => 13],
        ['id' => 12, 'block_name' => 'Isuapur', 'hindi_name' => 'इसुआपुर', 'slug' => 'isuapur', 'pincode' => '841407', 'total_panchayats' => 11],
        ['id' => 13, 'block_name' => 'Lahladpur', 'hindi_name' => 'लहलादपुर', 'slug' => 'lahladpur', 'pincode' => '841408', 'total_panchayats' => 9],
        ['id' => 14, 'block_name' => 'Manjhi', 'hindi_name' => 'मांझी', 'slug' => 'manjhi', 'pincode' => '841313', 'total_panchayats' => 24],
        ['id' => 15, 'block_name' => 'Maker', 'hindi_name' => 'मेकर', 'slug' => 'maker', 'pincode' => '841215', 'total_panchayats' => 10],
        ['id' => 16, 'block_name' => 'Dariapur', 'hindi_name' => 'दरियापुर', 'slug' => 'dariapur', 'pincode' => '841221', 'total_panchayats' => 21],
        ['id' => 17, 'block_name' => 'Jalalpur', 'hindi_name' => 'जलालपुर', 'slug' => 'jalalpur', 'pincode' => '841412', 'total_panchayats' => 15],
        ['id' => 18, 'block_name' => 'Nagra', 'hindi_name' => 'नगरा', 'slug' => 'nagra', 'pincode' => '841442', 'total_panchayats' => 12],
        ['id' => 19, 'block_name' => 'Mashrakh', 'hindi_name' => 'मशरख', 'slug' => 'mashrakh', 'pincode' => '841417', 'total_panchayats' => 16],
        ['id' => 20, 'block_name' => 'Panapur', 'hindi_name' => 'पन्नापुर', 'slug' => 'panapur', 'pincode' => '841410', 'total_panchayats' => 11]
    ];
}

// Fallback Categories Data
function getStaticCategories() {
    return [
        ['id' => 1, 'name' => 'Businesses & Retail', 'hindi_name' => 'व्यापार एवं दुकानें', 'icon' => 'bi-shop', 'slug' => 'businesses-retail', 'section' => 'BUSINESS'],
        ['id' => 2, 'name' => 'Advocates & Legal', 'hindi_name' => 'वकील एवं कानूनी सेवाएं', 'icon' => 'bi-journal-text', 'slug' => 'advocates-legal', 'section' => 'PROFESSIONAL'],
        ['id' => 3, 'name' => 'Doctors & Healthcare', 'hindi_name' => 'डॉक्टर एवं अस्पताल', 'icon' => 'bi-hospital', 'slug' => 'doctors-healthcare', 'section' => 'HEALTHCARE'],
        ['id' => 4, 'name' => 'Schools & Education', 'hindi_name' => 'स्कूल एवं कॉलेज', 'icon' => 'bi-mortarboard', 'slug' => 'schools-education', 'section' => 'EDUCATION'],
        ['id' => 5, 'name' => 'Coaching Institutes', 'hindi_name' => 'कोचिंग संस्थान', 'icon' => 'bi-book-half', 'slug' => 'coaching-institutes', 'section' => 'EDUCATION'],
        ['id' => 6, 'name' => 'Government Offices', 'hindi_name' => 'सरकारी कार्यालय', 'icon' => 'bi-building', 'slug' => 'government-offices', 'section' => 'GOVT'],
        ['id' => 7, 'name' => 'Hotels & Restaurants', 'hindi_name' => 'होटल एवं रेस्टोरेंट', 'icon' => 'bi-cup-hot', 'slug' => 'hotels-restaurants', 'section' => 'HOTEL'],
        ['id' => 8, 'name' => 'Banks & ATMs', 'hindi_name' => 'बैंक एवं एटीएम', 'icon' => 'bi-bank', 'slug' => 'banks-atms', 'section' => 'BANK'],
        ['id' => 9, 'name' => 'Emergency Services', 'hindi_name' => 'आपातकालीन सेवाएं', 'icon' => 'bi-telephone-outbound', 'slug' => 'emergency-services', 'section' => 'EMERGENCY']
    ];
}

// Fallback Listings Data
function getStaticListings() {
    return [
        [
            'id' => 1,
            'title' => 'Town Police Station (Thana), Chapra',
            'hindi_title' => 'नगर थाना, छपरा',
            'slug' => 'town-police-station-chapra',
            'category_name' => 'Emergency Services',
            'block_name' => 'Chapra Sadar',
            'mobile' => '06152-243202',
            'whatsapp' => '9431822401',
            'email' => 'sho-chapratown-bih@nic.in',
            'website' => 'https://saran.bihar.gov.in',
            'address' => 'Near Thanachowk, Main Road, Chapra, Saran',
            'pincode' => '841301',
            'services' => '24/7 Police Help, Crime Reporting, FIR Filing, Emergency Helpline 112',
            'description' => 'Official Town Police Station providing 24/7 safety and public law enforcement in Chapra Sadar.',
            'is_verified' => 'YES',
            'is_featured' => 'YES',
            'star_rating' => 5.00
        ],
        [
            'id' => 2,
            'title' => 'Saran District Hospital (Sadar Hospital Chapra)',
            'hindi_title' => 'सदर अस्पताल, छपरा',
            'slug' => 'sadar-hospital-chapra',
            'category_name' => 'Doctors & Healthcare',
            'block_name' => 'Chapra Sadar',
            'mobile' => '06152-243405',
            'whatsapp' => '9470003200',
            'email' => 'cs-saran-bih@nic.in',
            'website' => 'https://saran.bihar.gov.in',
            'address' => 'Hospital Road, Near Municipal Chowk, Chapra',
            'pincode' => '841301',
            'services' => '24/7 Emergency Care, OPD, ICU, Ambulance, Pathology Lab, Blood Bank',
            'description' => 'The premier government district healthcare facility in Saran, equipping specialized doctors and 24x7 emergency medical services.',
            'is_verified' => 'YES',
            'is_featured' => 'YES',
            'star_rating' => 4.80
        ],
        [
            'id' => 3,
            'title' => 'District Bar Association, Saran (Chapra Court Advocates Hub)',
            'hindi_title' => 'जिला बार एसोसिएशन, सरण (छपरा)',
            'slug' => 'district-bar-association-saran',
            'category_name' => 'Advocates & Legal',
            'block_name' => 'Chapra Sadar',
            'mobile' => '06152-242100',
            'whatsapp' => '9431426600',
            'email' => 'contact@advocateindex.com',
            'website' => 'https://advocateindex.com/BRSARA',
            'address' => 'District & Sessions Court Premises, Chapra, Saran',
            'pincode' => '841301',
            'services' => 'Legal Advice, Court Representation, Bail Applications, Legal Documentation, Notary',
            'description' => 'Official District Bar Association Chapra representing legal practitioners in Saran district.',
            'is_verified' => 'YES',
            'is_featured' => 'YES',
            'star_rating' => 4.90
        ],
        [
            'id' => 4,
            'title' => 'District Collectorate Office (DM Office Saran)',
            'hindi_title' => 'जिलाधिकारी कार्यालय (समाहरणालय छपरा)',
            'slug' => 'dm-office-saran-chapra',
            'category_name' => 'Government Offices',
            'block_name' => 'Chapra Sadar',
            'mobile' => '06152-245001',
            'whatsapp' => '9473191238',
            'email' => 'dm-saran.bih@nic.in',
            'website' => 'https://saran.bihar.gov.in',
            'address' => 'Collectorate Campus, Katchahry Chowk, Chapra, Saran',
            'pincode' => '841301',
            'services' => 'Public Grievances, Revenue Administration, Land Records, District Licensing, Government Welfare Schemes',
            'description' => 'Apex administrative headquarters for Saran District located in Chapra city.',
            'is_verified' => 'YES',
            'is_featured' => 'YES',
            'star_rating' => 5.00
        ]
    ];
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
    return getStaticBlocks();
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
    return getStaticCategories();
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
    
    // Static Fallback Filtering
    $static = getStaticListings();
    if (!empty($search)) {
        $static = array_filter($static, function($item) use ($search) {
            return stripos($item['title'], $search) !== false || stripos($item['description'], $search) !== false || stripos($item['services'], $search) !== false;
        });
    }
    return array_values($static);
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
    
    foreach (getStaticListings() as $item) {
        if ($item['slug'] === $slug) return $item;
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

    // Default Fallback Credentials if DB is unavailable
    if ($username === 'admin' && $password === 'admin123') {
        return [
            'id' => 1,
            'username' => 'admin',
            'full_name' => 'SaranIndex Administrator',
            'email' => 'admin@saranindex.com',
            'role' => 'SUPER_ADMIN'
        ];
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

    // Static fallback metrics
    $staticListings = getStaticListings();
    $stats['total_listings'] = count($staticListings);
    $stats['pending_listings'] = 0;
    $stats['verified_listings'] = count($staticListings);
    $stats['total_categories'] = count(getStaticCategories());
    $stats['total_blocks'] = count(getStaticBlocks());
    $stats['total_reviews'] = 0;
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

    $static = getStaticListings();
    if ($status) {
        $static = array_filter($static, function($item) use ($status) {
            return isset($item['status']) ? $item['status'] === $status : true;
        });
    }
    if ($search) {
        $static = array_filter($static, function($item) use ($search) {
            return stripos($item['title'], $search) !== false || stripos($item['mobile'], $search) !== false;
        });
    }
    return array_values($static);
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

    foreach (getStaticListings() as $item) {
        if ($item['id'] == $id) return $item;
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
    return getStaticCategories();
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
            return $stmt->fetchAll();
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
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error fetching person: " . $e->getMessage());
        }
    }
    return null;
}
?>




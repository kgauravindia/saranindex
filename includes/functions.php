<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';

function sanitizeInput($data) {
    if ($data === null) {
        return '';
    }
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    return strtolower($text);
}

function getCategoryUrl($cat_slug = '', $sub_slug = '') {
    if (empty($cat_slug)) return "categories";
    if (!empty($sub_slug)) {
        return rawurlencode($cat_slug) . "/" . rawurlencode($sub_slug);
    }
    return rawurlencode($cat_slug);
}

function getListingUrl($slug = '') {
    if (empty($slug)) return "";
    return rawurlencode($slug);
}

function getBlockUrl($slug = '') {
    if (!empty($slug)) {
        return "block/" . rawurlencode($slug);
    }
    return "blocks";
}

function getPanchayatUrl($slug = '') {
    if (!empty($slug)) {
        return "panchayat/" . rawurlencode($slug);
    }
    return "panchayats";
}

function getVillageUrl($slug = '') {
    if (!empty($slug)) {
        return "village/" . rawurlencode($slug);
    }
    return "villages";
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

    $cleanSlug = str_replace('-', ' ', strtolower($slug));

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
                WHERE (b.slug = :s1 OR LOWER(b.name) = :cs1 OR LOWER(b.name_english) = :cs2 OR LOWER(REPLACE(b.name, ' ', '-')) = :s2 OR LOWER(REPLACE(b.name_english, ' ', '-')) = :s3) LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            's1'  => $slug,
            'cs1' => $cleanSlug,
            'cs2' => $cleanSlug,
            's2'  => $slug,
            's3'  => $slug
        ]);
        $res = $stmt->fetch();
        if ($res) return $res;
    } catch (PDOException $e) {
        error_log("getBlockBySlug census query failed: " . $e->getMessage());
    }

    // Fallback: Query blocks table directly
    try {
        $stmt = $db->prepare("SELECT *, name as block_name FROM blocks WHERE (slug = :s1 OR LOWER(name) = :cs1 OR LOWER(name_english) = :cs2 OR LOWER(REPLACE(name, ' ', '-')) = :s2 OR LOWER(REPLACE(name_english, ' ', '-')) = :s3) LIMIT 1");
        $stmt->execute([
            's1'  => $slug,
            'cs1' => $cleanSlug,
            'cs2' => $cleanSlug,
            's2'  => $slug,
            's3'  => $slug
        ]);
        return $stmt->fetch();
    } catch (PDOException $e) {}
    return null;
}

function getCategories() {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->query("SELECT * FROM categories WHERE status='ACTIVE' ORDER BY name ASC");
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
            $stmt = $db->prepare("SELECT * FROM subcategories WHERE category_id = :cat_id ORDER BY CASE WHEN type = 'PROFESSIONAL' THEN 1 ELSE 2 END ASC, name ASC");
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
            $stmt = $db->query("SELECT s.*, c.name as category_name, c.slug as category_slug, COUNT(l.id) as listing_count 
                                FROM subcategories s 
                                LEFT JOIN categories c ON s.category_id = c.id 
                                LEFT JOIN listings l ON s.id = l.subcategory_id 
                                GROUP BY s.id 
                                ORDER BY c.name ASC, CASE WHEN s.type = 'PROFESSIONAL' THEN 1 ELSE 2 END ASC, s.name ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {}
    }
    return [];
}

function getSubcategoriesByCategorySlug($category_slug = '') {
    $db = getDB();
    if ($db) {
        try {
            if (!empty($category_slug)) {
                $stmt = $db->prepare("SELECT s.*, c.slug as category_slug, c.name as category_name FROM subcategories s JOIN categories c ON s.category_id = c.id WHERE c.slug = :cat_slug ORDER BY s.name ASC");
                $stmt->execute(['cat_slug' => $category_slug]);
            } else {
                $stmt = $db->query("SELECT s.*, c.slug as category_slug, c.name as category_name FROM subcategories s JOIN categories c ON s.category_id = c.id ORDER BY c.name ASC, s.name ASC");
            }
            return $stmt->fetchAll();
        } catch (PDOException $e) {}
    }
    return [];
}

function getDataSources($status = 'ACTIVE') {
    $db = getDB();
    if ($db) {
        try {
            $sql = "SELECT * FROM sources";
            if ($status) {
                $sql .= " WHERE status = :st";
            }
            $sql .= " ORDER BY sort_order ASC, id ASC";
            $stmt = $db->prepare($sql);
            if ($status) {
                $stmt->execute(['st' => $status]);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("getDataSources error: " . $e->getMessage());
        }
    }
    return [];
}

function getSourceById($id) {
    $db = getDB();
    if ($db && !empty($id)) {
        try {
            $stmt = $db->prepare("SELECT * FROM sources WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {}
    }
    return null;
}

function getSourceByName($name) {
    $db = getDB();
    if ($db && !empty($name)) {
        try {
            $stmt = $db->prepare("SELECT * FROM sources WHERE title LIKE :n OR domain LIKE :n OR authority_badge LIKE :n LIMIT 1");
            $stmt->execute(['n' => '%' . $name . '%']);
            return $stmt->fetch();
        } catch (PDOException $e) {}
    }
    return null;
}

function getPanchayats($block_id = null) {
    $db = getDB();
    if ($db) {
        try {
            $sql = "SELECT p.*, COALESCE(b.name_english, b.name) as block_name, b.hindi_name as block_hindi, b.slug as block_slug FROM panchayats p JOIN blocks b ON p.block_id = b.id";
            if ($block_id) {
                $sql .= " WHERE p.block_id = :bid";
            }
            $sql .= " ORDER BY b.id ASC, p.panchayat_name ASC";
            $stmt = $db->prepare($sql);
            if ($block_id) {
                $stmt->execute(['bid' => $block_id]);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll();
        } catch (PDOException $e) {}
    }
    return [];
}



function getListings($search = '', $category_slug = '', $block_slug = '', $limit = 20, $offset = 0, $subcategory_slug = '') {
    $db = getDB();
    if ($db) {
        try {
            $sql = "SELECT l.*, c.name as category_name, sc.name as subcategory_name, sc.hindi_name as subcategory_hindi_name, b.name as block_name, u.username_handle as owner_handle, u.full_name as owner_full_name 
                    FROM listings l 
                    LEFT JOIN categories c ON l.category_id = c.id 
                    LEFT JOIN subcategories sc ON l.subcategory_id = sc.id
                    LEFT JOIN blocks b ON l.block_id = b.id 
                    LEFT JOIN panchayats p ON l.panchayat_id = p.id
                    LEFT JOIN users u ON l.user_id = u.id
                    WHERE l.status='ACTIVE'";
            $params = [];

            if (!empty($search)) {
                $search = trim($search);
                $trans_map = [
                    'sinh' => 'singh', 'singh' => 'sinh',
                    'pndit' => 'pandit', 'pandit' => 'pndit',
                    'devee' => 'devi', 'devi' => 'devee',
                    'tivaree' => 'tiwari', 'tiwari' => 'tivaree',
                    'kumaree' => 'kumari', 'kumari' => 'kumaree',
                    'mohn' => 'mohan', 'mohan' => 'mohn',
                    'ranee' => 'rani', 'rani' => 'ranee'
                ];
                
                $search_lower = strtolower($search);
                $search_variants = [$search];
                foreach ($trans_map as $from => $to) {
                    if (strpos($search_lower, $from) !== false) {
                        $search_variants[] = str_replace($from, $to, $search_lower);
                    }
                }
                $search_variants = array_unique($search_variants);
                
                $fields = [
                    'l.title', 'l.hindi_title', 'l.contact_person', 'l.description', 
                    'l.services', 'l.products', 'l.address', 'l.mobile',
                    'c.name', 'c.hindi_name', 'sc.name', 'sc.hindi_name', 
                    'b.name', 'b.hindi_name', 'b.name_english', 
                    'p.panchayat_name', 'p.hindi_name', 'p.village', 'p.village_hindi',
                    'u.username_handle', 'u.full_name'
                ];
                
                $phrase_or = [];
                foreach ($search_variants as $v_idx => $variant) {
                    foreach ($fields as $f_idx => $field) {
                        $param_key = "v_{$v_idx}_{$f_idx}";
                        $phrase_or[] = "$field LIKE :$param_key";
                        $params[$param_key] = '%' . $variant . '%';
                    }
                }
                
                $words = array_filter(explode(' ', preg_replace('/\s+/', ' ', $search)));
                if (count($words) > 1) {
                    $token_and_group = [];
                    foreach ($words as $w_idx => $word) {
                        if (mb_strlen($word) < 2) continue;
                        $w_lower = strtolower($word);
                        $word_variants = [$word];
                        if (isset($trans_map[$w_lower])) {
                            $word_variants[] = $trans_map[$w_lower];
                        }
                        
                        $token_word_or = [];
                        foreach ($word_variants as $wv_idx => $w_var) {
                            foreach ($fields as $f_idx => $field) {
                                $param_key = "w_{$w_idx}_{$wv_idx}_{$f_idx}";
                                $token_word_or[] = "$field LIKE :$param_key";
                                $params[$param_key] = '%' . $w_var . '%';
                            }
                        }
                        if (!empty($token_word_or)) {
                            $token_and_group[] = '(' . implode(' OR ', $token_word_or) . ')';
                        }
                    }
                    
                    if (!empty($token_and_group)) {
                        $sql .= " AND ((" . implode(' OR ', $phrase_or) . ") OR (" . implode(' AND ', $token_and_group) . "))";
                    } else {
                        $sql .= " AND (" . implode(' OR ', $phrase_or) . ")";
                    }
                } else {
                    $sql .= " AND (" . implode(' OR ', $phrase_or) . ")";
                }
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

            $sql .= " ORDER BY (CASE WHEN l.plan_type = 'PLATINUM' THEN 1 WHEN l.plan_type = 'GOLD' THEN 2 ELSE 3 END) ASC, l.is_featured DESC, l.is_verified DESC, l.star_rating DESC LIMIT $limit OFFSET $offset";

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll();
            if ($results) return $results;
        } catch (PDOException $e) {
            error_log("getListings error: " . $e->getMessage());
        }
    }
    return [];
}

function getRecentListings($limit = 6) {
    $db = getDB();
    if ($db) {
        try {
            $sql = "SELECT l.*, c.name as category_name, sc.name as subcategory_name, sc.hindi_name as subcategory_hindi_name, b.name as block_name, u.username_handle as owner_handle, u.full_name as owner_full_name 
                    FROM listings l 
                    LEFT JOIN categories c ON l.category_id = c.id 
                    LEFT JOIN subcategories sc ON l.subcategory_id = sc.id
                    LEFT JOIN blocks b ON l.block_id = b.id 
                    LEFT JOIN users u ON l.user_id = u.id
                    WHERE l.status='ACTIVE'
                    ORDER BY l.id DESC LIMIT " . intval($limit);
            $stmt = $db->query($sql);
            return $stmt->fetchAll() ?: [];
        } catch (PDOException $e) {
            error_log("getRecentListings error: " . $e->getMessage());
        }
    }
    return [];
}


function getListingBySlug($slug) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("SELECT l.*, c.name as category_name, b.name as block_name, u.full_name as owner_name, u.name as owner_short_name, u.username_handle as owner_handle, u.profile_image as owner_image, u.designation as owner_designation, u.profile_visibility as owner_visibility FROM listings l LEFT JOIN categories c ON l.category_id = c.id LEFT JOIN blocks b ON l.block_id = b.id LEFT JOIN users u ON l.user_id = u.id WHERE l.slug = :slug LIMIT 1");
            $stmt->execute(['slug' => $slug]);
            $res = $stmt->fetch();
            if ($res) return $res;
        } catch (PDOException $e) {}
    }
    return null;
}

function incrementViewCount($listing_id) {
    $db = getDB();
    if ($db && $listing_id) {
        try {
            $stmt = $db->prepare("UPDATE listings SET view_count = view_count + 1 WHERE id = :id");
            $stmt->execute(['id' => $listing_id]);
        } catch (PDOException $e) {}
    }
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

function ensureReviewsTable() {
    $db = getDB();
    if (!$db) return;
    try {
        $cols = $db->query("SHOW COLUMNS FROM `reviews`")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('user_id', $cols)) {
            $db->exec("ALTER TABLE `reviews` ADD COLUMN `user_id` INT DEFAULT NULL");
        }
        backfillReviewsUserId();
    } catch (PDOException $e) {}
}

function backfillReviewsUserId() {
    $db = getDB();
    if (!$db) return;
    try {
        $db->exec("UPDATE reviews r JOIN users u ON (r.reviewer_mobile IS NOT NULL AND r.reviewer_mobile != '' AND r.reviewer_mobile = u.mobile) SET r.user_id = u.id WHERE r.user_id IS NULL");
        $db->exec("UPDATE reviews r JOIN users u ON (r.reviewer_name IS NOT NULL AND r.reviewer_name != '' AND (LOWER(r.reviewer_name) = LOWER(u.full_name) OR LOWER(r.reviewer_name) = LOWER(u.name))) SET r.user_id = u.id WHERE r.user_id IS NULL");
    } catch (PDOException $e) {}
}

function hasUserReviewedListing($userId, $listingId, $userMobile = '', $userName = '') {
    ensureReviewsTable();
    $db = getDB();
    if (!$db || empty($listingId)) return null;

    try {
        $stmt = $db->prepare("SELECT * FROM reviews WHERE listing_id = :lid AND ((user_id IS NOT NULL AND user_id = :uid) OR (reviewer_mobile IS NOT NULL AND reviewer_mobile = :mob AND reviewer_mobile != '') OR (reviewer_name = :rname AND reviewer_name != '')) ORDER BY id DESC LIMIT 1");
        $stmt->execute([
            'lid' => intval($listingId),
            'uid' => !empty($userId) ? intval($userId) : -1,
            'mob' => !empty($userMobile) ? $userMobile : 'NO_MOB_MATCH',
            'rname' => !empty($userName) ? $userName : 'NO_NAME_MATCH'
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("hasUserReviewedListing error: " . $e->getMessage());
        return null;
    }
}

function addReview($listing_id, $reviewer_name, $rating, $comment, $user_id = null, $user_mobile = null) {
    ensureReviewsTable();
    $db = getDB();
    if ($db) {
        // Auto-lookup user_id if null
        if (empty($user_id)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!empty($_SESSION['user_id'])) {
                $user_id = intval($_SESSION['user_id']);
            } elseif (!empty($user_mobile)) {
                $uStmt = $db->prepare("SELECT id FROM users WHERE mobile = :mob LIMIT 1");
                $uStmt->execute(['mob' => $user_mobile]);
                $user_id = $uStmt->fetchColumn() ?: null;
            } elseif (!empty($reviewer_name)) {
                $uStmt = $db->prepare("SELECT id FROM users WHERE full_name = :rname LIMIT 1");
                $uStmt->execute(['rname' => $reviewer_name]);
                $user_id = $uStmt->fetchColumn() ?: null;
            }
        }

        try {
            $stmt = $db->prepare("INSERT INTO reviews (listing_id, user_id, reviewer_name, reviewer_mobile, rating, comment, status) VALUES (:lid, :uid, :rname, :rmob, :rating, :comment, 'APPROVED')");
            return $stmt->execute([
                'lid' => intval($listing_id),
                'uid' => !empty($user_id) ? intval($user_id) : null,
                'rname' => $reviewer_name,
                'rmob' => $user_mobile,
                'rating' => intval($rating),
                'comment' => $comment
            ]);
        } catch (PDOException $e) {
            error_log("addReview error: " . $e->getMessage());
        }
    }
    return false;
}

// --- CLAIM BUSINESS HELPER FUNCTIONS ---

function ensureClaimsTable() {
    $db = getDB();
    if (!$db) return;
    try {
        $db->exec("CREATE TABLE IF NOT EXISTS `claims` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `listing_id` INT NOT NULL,
            `user_id` INT DEFAULT NULL,
            `claimant_name` VARCHAR(100) NOT NULL,
            `claimant_mobile` VARCHAR(20) NOT NULL,
            `role_title` VARCHAR(100) DEFAULT 'Owner / Manager',
            `verification_proof` TEXT,
            `status` ENUM('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    } catch (PDOException $e) {
        error_log("ensureClaimsTable error: " . $e->getMessage());
    }
}

function submitBusinessClaim($listingId, $userId, $name, $mobile, $role, $proof) {
    ensureClaimsTable();
    $db = getDB();
    if (!$db) return false;
    try {
        $stmt = $db->prepare("INSERT INTO claims (listing_id, user_id, claimant_name, claimant_mobile, role_title, verification_proof, status) VALUES (:lid, :uid, :cname, :cmob, :role, :proof, 'PENDING')");
        return $stmt->execute([
            'lid' => intval($listingId),
            'uid' => !empty($userId) ? intval($userId) : null,
            'cname' => sanitizeInput($name),
            'cmob' => sanitizeInput($mobile),
            'role' => sanitizeInput($role),
            'proof' => sanitizeInput($proof)
        ]);
    } catch (PDOException $e) {
        error_log("submitBusinessClaim error: " . $e->getMessage());
        return false;
    }
}

function hasUserClaimedListing($listingId, $userId = null, $mobile = null) {
    ensureClaimsTable();
    $db = getDB();
    if (!$db) return null;
    try {
        $stmt = $db->prepare("SELECT * FROM claims WHERE listing_id = :lid AND ((user_id IS NOT NULL AND user_id = :uid) OR (claimant_mobile IS NOT NULL AND claimant_mobile = :mob AND claimant_mobile != '')) ORDER BY id DESC LIMIT 1");
        $stmt->execute([
            'lid' => intval($listingId),
            'uid' => !empty($userId) ? intval($userId) : -1,
            'mob' => !empty($mobile) ? $mobile : 'NO_MOB_MATCH'
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return null;
    }
}

function getClaimsList($status = '') {
    ensureClaimsTable();
    $db = getDB();
    if (!$db) return [];
    try {
        $sql = "SELECT c.*, l.title as listing_title, l.slug as listing_slug, l.mobile as listing_mobile, cat.name as category_name FROM claims c LEFT JOIN listings l ON c.listing_id = l.id LEFT JOIN categories cat ON l.category_id = cat.id";
        if (!empty($status)) {
            $sql .= " WHERE c.status = :status";
        }
        $sql .= " ORDER BY c.id DESC";
        $stmt = $db->prepare($sql);
        if (!empty($status)) {
            $stmt->execute(['status' => $status]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function approveClaim($claimId) {
    ensureClaimsTable();
    $db = getDB();
    if (!$db) return false;
    try {
        $stmt = $db->prepare("SELECT * FROM claims WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => intval($claimId)]);
        $claim = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$claim) return false;

        $db->beginTransaction();
        $stmtApp = $db->prepare("UPDATE claims SET status = 'APPROVED' WHERE id = :id");
        $stmtApp->execute(['id' => intval($claimId)]);

        $userIdToAssign = !empty($claim['user_id']) ? intval($claim['user_id']) : null;
        if (empty($userIdToAssign) && !empty($claim['claimant_mobile'])) {
            $userByMob = getUserByMobile($claim['claimant_mobile']);
            if ($userByMob && !empty($userByMob['id'])) {
                $userIdToAssign = intval($userByMob['id']);
                $db->prepare("UPDATE claims SET user_id = :uid WHERE id = :cid")->execute(['uid' => $userIdToAssign, 'cid' => intval($claimId)]);
            }
        }

        if (!empty($userIdToAssign)) {
            $stmtL = $db->prepare("UPDATE listings SET user_id = :uid, is_verified = 'YES' WHERE id = :lid");
            $stmtL->execute(['uid' => $userIdToAssign, 'lid' => $claim['listing_id']]);
        } else {
            $stmtL = $db->prepare("UPDATE listings SET is_verified = 'YES' WHERE id = :lid");
            $stmtL->execute(['lid' => $claim['listing_id']]);
        }
        $db->commit();
        return true;
    } catch (PDOException $e) {
        if ($db->inTransaction()) $db->rollBack();
        error_log("approveClaim error: " . $e->getMessage());
        return false;
    }
}

function rejectClaim($claimId) {
    ensureClaimsTable();
    $db = getDB();
    if (!$db) return false;
    try {
        $stmt = $db->prepare("UPDATE claims SET status = 'REJECTED' WHERE id = :id");
        return $stmt->execute(['id' => intval($claimId)]);
    } catch (PDOException $e) {
        return false;
    }
}

function updateReview($reviewId, $rating, $comment) {
    ensureReviewsTable();
    $db = getDB();
    if (!$db || empty($reviewId)) return false;

    try {
        $stmt = $db->prepare("UPDATE reviews SET rating = :rating, comment = :comment WHERE id = :id");
        return $stmt->execute([
            'rating' => intval($rating),
            'comment' => sanitizeInput($comment),
            'id' => intval($reviewId)
        ]);
    } catch (PDOException $e) {
        error_log("updateReview error: " . $e->getMessage());
        return false;
    }
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
            `mobile` VARCHAR(20) DEFAULT NULL,
            `role` VARCHAR(50) DEFAULT 'SUPER_ADMIN',
            `scope_type` VARCHAR(20) DEFAULT 'DISTRICT',
            `state` VARCHAR(100) DEFAULT 'Bihar',
            `district` VARCHAR(100) DEFAULT 'Saran',
            `block_id` INT DEFAULT NULL,
            `designation` VARCHAR(150) DEFAULT NULL,
            `address` TEXT DEFAULT NULL,
            `about` TEXT DEFAULT NULL,
            `last_login` DATETIME NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Safely alter role column to VARCHAR(50) and add missing columns
        try {
            $db->exec("ALTER TABLE `admins` MODIFY COLUMN `role` VARCHAR(50) DEFAULT 'SUPER_ADMIN'");
        } catch (PDOException $ex) {}

        $existingCols = [];
        $colStmt = $db->query("SHOW COLUMNS FROM `admins`");
        if ($colStmt) {
            while ($c = $colStmt->fetch(PDO::FETCH_ASSOC)) {
                $existingCols[] = $c['Field'];
            }
        }

        $neededCols = [
            'mobile' => "VARCHAR(20) DEFAULT NULL",
            'scope_type' => "VARCHAR(20) DEFAULT 'DISTRICT'",
            'state' => "VARCHAR(100) DEFAULT 'Bihar'",
            'district' => "VARCHAR(100) DEFAULT 'Saran'",
            'block_id' => "INT DEFAULT NULL",
            'designation' => "VARCHAR(150) DEFAULT NULL",
            'address' => "TEXT DEFAULT NULL",
            'about' => "TEXT DEFAULT NULL"
        ];

        foreach ($neededCols as $col => $typeDef) {
            if (!in_array($col, $existingCols)) {
                try {
                    $db->exec("ALTER TABLE `admins` ADD COLUMN `{$col}` {$typeDef}");
                } catch (PDOException $ex) {}
            }
        }

        // Check if initial admin exists
        $stmt = $db->query("SELECT COUNT(*) FROM `admins`");
        if ($stmt->fetchColumn() == 0) {
            $passHash = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO `admins` (username, password_hash, full_name, email, role, scope_type, designation, about) VALUES ('admin', :hash, 'SaranIndex Administrator', 'admin@saranindex.com', 'SUPER_ADMIN', 'DISTRICT', 'Super Administrator', 'Chief system administrator for Saran District Directory.')");
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
        'active_listings' => 0,
        'pending_listings' => 0,
        'rejected_listings' => 0,
        'verified_listings' => 0,
        'featured_listings' => 0,
        'platinum_listings' => 0,
        'gold_listings' => 0,
        'total_users' => 0,
        'total_categories' => 0,
        'total_subcategories' => 0,
        'total_blocks' => 0,
        'total_panchayats' => 0,
        'total_halkas' => 0,
        'total_reviews' => 0,
        'pending_claims' => 0,
        'total_payments' => 0,
        'successful_payments' => 0,
        'total_revenue' => 0,
        'block_breakdown' => [],
        'category_breakdown' => []
    ];

    if ($db) {
        try {
            $stats['total_listings'] = (int)$db->query("SELECT COUNT(*) FROM listings")->fetchColumn();
            $stats['active_listings'] = (int)$db->query("SELECT COUNT(*) FROM listings WHERE status = 'ACTIVE'")->fetchColumn();
            $stats['pending_listings'] = (int)$db->query("SELECT COUNT(*) FROM listings WHERE status = 'PENDING'")->fetchColumn();
            $stats['rejected_listings'] = (int)$db->query("SELECT COUNT(*) FROM listings WHERE status = 'REJECTED'")->fetchColumn();
            $stats['verified_listings'] = (int)$db->query("SELECT COUNT(*) FROM listings WHERE is_verified = 'YES'")->fetchColumn();
            $stats['featured_listings'] = (int)$db->query("SELECT COUNT(*) FROM listings WHERE is_featured = 'YES'")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['platinum_listings'] = (int)$db->query("SELECT COUNT(*) FROM listings WHERE plan_type = 'PLATINUM'")->fetchColumn();
            $stats['gold_listings'] = (int)$db->query("SELECT COUNT(*) FROM listings WHERE plan_type = 'GOLD'")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['total_categories'] = (int)$db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['total_subcategories'] = (int)$db->query("SELECT COUNT(*) FROM subcategories")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['total_blocks'] = (int)$db->query("SELECT COUNT(*) FROM blocks")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['total_panchayats'] = (int)$db->query("SELECT COUNT(*) FROM panchayats")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['total_halkas'] = (int)$db->query("SELECT COUNT(*) FROM halka")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['total_users'] = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['total_reviews'] = (int)$db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['pending_claims'] = (int)$db->query("SELECT COUNT(*) FROM claims WHERE status = 'PENDING'")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stats['successful_payments'] = (int)$db->query("SELECT COUNT(*) FROM payments WHERE payment_status = 'SUCCESS'")->fetchColumn();
            $stats['total_revenue'] = (float)$db->query("SELECT SUM(amount) FROM payments WHERE payment_status = 'SUCCESS'")->fetchColumn();
        } catch (PDOException $e) {}

        try {
            $stmtB = $db->query("SELECT b.name as block_name, b.slug, COUNT(l.id) as listing_count 
                                 FROM blocks b 
                                 LEFT JOIN listings l ON b.id = l.block_id 
                                 GROUP BY b.id 
                                 ORDER BY listing_count DESC");
            $stats['block_breakdown'] = $stmtB->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}

        try {
            $stmtC = $db->query("SELECT c.name as category_name, c.icon, c.slug, COUNT(l.id) as listing_count 
                                 FROM categories c 
                                 LEFT JOIN listings l ON c.id = l.category_id 
                                 GROUP BY c.id 
                                 ORDER BY listing_count DESC");
            $stats['category_breakdown'] = $stmtC->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {}
    }

    return $stats;
}

function getAllAdminListings($status = null, $search = null, $category_id = null, $subcategory_id = null, $block_id = null) {
    $db = getDB();
    if ($db) {
        try {
            $sql = "SELECT l.*, c.name as category_name, c.id as cat_id, sc.name as subcategory_name, sc.id as sub_cat_id, b.name as block_name 
                    FROM listings l 
                    LEFT JOIN categories c ON l.category_id = c.id 
                    LEFT JOIN subcategories sc ON l.subcategory_id = sc.id 
                    LEFT JOIN blocks b ON l.block_id = b.id WHERE 1=1";
            $params = [];

            if (!empty($status)) {
                $sql .= " AND l.status = :status";
                $params['status'] = $status;
            }

            if (!empty($category_id)) {
                $sql .= " AND l.category_id = :category_id";
                $params['category_id'] = intval($category_id);
            }

            if (!empty($subcategory_id)) {
                $sql .= " AND l.subcategory_id = :subcategory_id";
                $params['subcategory_id'] = intval($subcategory_id);
            }

            if (!empty($block_id)) {
                $sql .= " AND l.block_id = :block_id";
                $params['block_id'] = intval($block_id);
            }

            if (!empty($search)) {
                $cleanSearch = trim($search);
                if (strtolower($cleanSearch) === 'verified') {
                    $sql .= " AND l.is_verified = 'YES'";
                } else {
                    $sql .= " AND (
                        l.title LIKE :s1 
                        OR l.hindi_title LIKE :s2 
                        OR l.mobile LIKE :s3 
                        OR l.whatsapp LIKE :s4
                        OR l.contact_person LIKE :s5
                        OR l.address LIKE :s6
                        OR l.designation LIKE :s7
                        OR c.name LIKE :s8
                        OR c.hindi_name LIKE :s9
                        OR sc.name LIKE :s10
                        OR sc.hindi_name LIKE :s11
                        OR b.name LIKE :s12
                        OR b.hindi_name LIKE :s13
                    )";
                    $sVal = '%' . $cleanSearch . '%';
                    $params['s1'] = $sVal;
                    $params['s2'] = $sVal;
                    $params['s3'] = $sVal;
                    $params['s4'] = $sVal;
                    $params['s5'] = $sVal;
                    $params['s6'] = $sVal;
                    $params['s7'] = $sVal;
                    $params['s8'] = $sVal;
                    $params['s9'] = $sVal;
                    $params['s10'] = $sVal;
                    $params['s11'] = $sVal;
                    $params['s12'] = $sVal;
                    $params['s13'] = $sVal;
                }
            }

            $sql .= " ORDER BY l.id DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("getAllAdminListings error: " . $e->getMessage());
        }
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
        if (strtoupper($status) === 'ACTIVE') {
            $reason = '';
            if (!isListingUserMobileActive($id, $reason)) {
                error_log("updateListingStatus blocked for listing #{$id}: " . $reason);
                return false;
            }
        }
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

    $params = [
        'entity_type' => $data['entity_type'] ?? 'BUSINESS',
        'category_id' => intval($data['category_id'] ?? 1),
        'subcategory_id' => !empty($data['subcategory_id']) ? intval($data['subcategory_id']) : null,
        'block_id' => !empty($data['block_id']) ? intval($data['block_id']) : null,
        'panchayat_id' => !empty($data['panchayat_id']) ? intval($data['panchayat_id']) : null,
        'village_id' => !empty($data['village_id']) ? intval($data['village_id']) : null,
        'title' => $data['title'] ?? '',
        'hindi_title' => $data['hindi_title'] ?? '',
        'slug' => $slug,
        'contact_person' => $data['contact_person'] ?? '',
        'mobile' => $data['mobile'] ?? '',
        'mobile_visibility' => in_array(strtoupper($data['mobile_visibility'] ?? ''), ['HIDDEN', 'PRIVATE', 'HIDE', 'NO']) ? 'HIDDEN' : 'PUBLIC',
        'whatsapp' => $data['whatsapp'] ?? '',
        'email' => $data['email'] ?? '',
        'website' => $data['website'] ?? '',
        'address' => $data['address'] ?? '',
        'pincode' => $data['pincode'] ?? '841301',
        'map_link' => $data['map_link'] ?? '',
        'business_hours' => $data['business_hours'] ?? '9:00 AM - 8:00 PM',
        'services' => $data['services'] ?? '',
        'products' => $data['products'] ?? '',
        'gst_no' => $data['gst_no'] ?? '',
        'udyam_no' => $data['udyam_no'] ?? '',
        'cin_no' => $data['cin_no'] ?? '',
        'local_reg_no' => $data['local_reg_no'] ?? '',
        'description' => $data['description'] ?? '',
        'cover_image' => $data['cover_image'] ?? '',
        'is_verified' => $data['is_verified'] ?? 'NO',
        'is_featured' => $data['is_featured'] ?? 'NO',
        'status' => $data['status'] ?? 'ACTIVE',
        'plan_type' => $data['plan_type'] ?? 'FREE',
        'plan_expires_at' => !empty($data['plan_expires_at']) ? $data['plan_expires_at'] : null
    ];

    if (strtoupper($params['status']) === 'ACTIVE') {
        $checkData = [
            'id' => $id,
            'user_id' => $data['user_id'] ?? null,
            'mobile' => $data['mobile'] ?? ''
        ];
        $mobReason = '';
        if (!isListingUserMobileActive($checkData, $mobReason)) {
            $params['status'] = 'PENDING';
        }
    }

    try {
        if ($id) {
            $sql = "UPDATE listings SET 
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
                mobile_visibility = :mobile_visibility,
                whatsapp = :whatsapp,
                email = :email,
                website = :website,
                address = :address,
                pincode = :pincode,
                map_link = :map_link,
                business_hours = :business_hours,
                services = :services,
                products = :products,
                gst_no = :gst_no,
                udyam_no = :udyam_no,
                cin_no = :cin_no,
                local_reg_no = :local_reg_no,
                description = :description,
                cover_image = :cover_image,
                is_verified = :is_verified,
                is_featured = :is_featured,
                status = :status,
                plan_type = :plan_type,
                plan_expires_at = :plan_expires_at
                WHERE id = :id";
            $params['id'] = intval($id);
            $stmt = $db->prepare($sql);
            return $stmt->execute($params);
        } else {
            $sql = "INSERT INTO listings (
                entity_type, category_id, subcategory_id, block_id, panchayat_id, village_id,
                title, hindi_title, slug, contact_person, mobile, mobile_visibility, whatsapp, email, website,
                address, pincode, map_link, business_hours, services, products, gst_no, udyam_no, cin_no, local_reg_no, description, cover_image,
                is_verified, is_featured, status, plan_type, plan_expires_at
            ) VALUES (
                :entity_type, :category_id, :subcategory_id, :block_id, :panchayat_id, :village_id,
                :title, :hindi_title, :slug, :contact_person, :mobile, :mobile_visibility, :whatsapp, :email, :website,
                :address, :pincode, :map_link, :business_hours, :services, :products, :gst_no, :udyam_no, :cin_no, :local_reg_no, :description, :cover_image,
                :is_verified, :is_featured, :status, :plan_type, :plan_expires_at
            )";
            $stmt = $db->prepare($sql);
            return $stmt->execute($params);
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
            $stmt = $db->query("SELECT c.*, COUNT(l.id) as listing_count 
                                FROM categories c 
                                LEFT JOIN listings l ON c.id = l.category_id 
                                GROUP BY c.id 
                                ORDER BY c.name ASC");
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

function getAllAdminReviews($status = '') {
    ensureReviewsTable();
    $db = getDB();
    if (!$db) return [];
    try {
        $sql = "SELECT r.*, l.title as listing_title, l.slug as listing_slug, u.full_name as user_full_name, u.mobile as user_registered_mobile FROM reviews r LEFT JOIN listings l ON r.listing_id = l.id LEFT JOIN users u ON r.user_id = u.id";
        if (!empty($status)) {
            $sql .= " WHERE r.status = :status";
        }
        $sql .= " ORDER BY r.id DESC";
        $stmt = $db->prepare($sql);
        if (!empty($status)) {
            $stmt->execute(['status' => $status]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("getAllAdminReviews error: " . $e->getMessage());
        return [];
    }
}

function deleteReview($id) {
    ensureReviewsTable();
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("DELETE FROM reviews WHERE id = :id");
            return $stmt->execute(['id' => intval($id)]);
        } catch (PDOException $e) {}
    }
    return false;
}

function approveReview($id) {
    ensureReviewsTable();
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("UPDATE reviews SET status = 'APPROVED' WHERE id = :id");
            return $stmt->execute(['id' => intval($id)]);
        } catch (PDOException $e) {}
    }
    return false;
}

function rejectReview($id) {
    ensureReviewsTable();
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("UPDATE reviews SET status = 'REJECTED' WHERE id = :id");
            return $stmt->execute(['id' => intval($id)]);
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
            $where[] = "(mauja_name LIKE :s1 OR halka_name LIKE :s2 OR mauja_english LIKE :s6 OR halka_english LIKE :s7 OR mauja_code LIKE :s3 OR halka_code = :s4 OR block LIKE :s5)";
            $params['s1'] = "%$search%";
            $params['s2'] = "%$search%";
            $params['s6'] = "%$search%";
            $params['s7'] = "%$search%";
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
            $where[] = "(mauja_name LIKE :s1 OR halka_name LIKE :s2 OR mauja_english LIKE :s6 OR halka_english LIKE :s7 OR mauja_code LIKE :s3 OR halka_code = :s4 OR block LIKE :s5)";
            $params['s1'] = "%$search%";
            $params['s2'] = "%$search%";
            $params['s6'] = "%$search%";
            $params['s7'] = "%$search%";
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
            'google_maps_link' => "TEXT DEFAULT NULL",
            'username_handle' => "VARCHAR(50) DEFAULT NULL",
            'profession_category' => "VARCHAR(100) DEFAULT NULL",
            'specialization' => "VARCHAR(255) DEFAULT NULL",
            'experience_years' => "VARCHAR(50) DEFAULT NULL",
            'office_hours' => "VARCHAR(150) DEFAULT NULL",
            'profile_visibility' => "VARCHAR(20) DEFAULT 'PUBLIC'",
            'category_id' => "INT DEFAULT NULL",
            'subcategory_id' => "INT DEFAULT NULL"
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


function registerPublicUser($fullName, $mobile, $password, $email = '', $blockId = null, $address = '', $stateCode = null, $districtCode = null, $villageId = null, $usernameHandle = '') {
    $db = getDB();
    if (!$db) {
        return ['success' => false, 'message' => 'Database connection failed.'];
    }

    ensureUsersTable();

    $fullName = sanitizeInput($fullName);
    $mobile = preg_replace('/[^0-9]/', '', $mobile);
    $email = sanitizeInput($email);
    $address = sanitizeInput($address);
    $stateCode = !empty($stateCode) ? sanitizeInput($stateCode) : null;
    $districtCode = !empty($districtCode) ? sanitizeInput($districtCode) : null;

    if (empty($fullName)) {
        return ['success' => false, 'message' => 'Full name is required.'];
    }

    if (strlen($mobile) < 10) {
        return ['success' => false, 'message' => 'Please enter a valid 10-digit mobile number.'];
    }

    if (strlen($password) < 6) {
        return ['success' => false, 'message' => 'Password must be at least 6 characters long.'];
    }

    // Process & validate Username / Handle (@username)
    $cleanHandle = ltrim(trim($usernameHandle), '@');
    if (!empty($cleanHandle)) {
        if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $cleanHandle)) {
            return ['success' => false, 'message' => 'Username handle must be 3-30 characters long and contain only letters, numbers, and underscores.'];
        }
        $chkHandle = $db->prepare("SELECT id FROM users WHERE LOWER(username_handle) = LOWER(:h1) OR LOWER(username_handle) = LOWER(:h2) LIMIT 1");
        $chkHandle->execute([
            'h1' => $cleanHandle,
            'h2' => '@' . $cleanHandle
        ]);
        if ($chkHandle->fetch()) {
            return ['success' => false, 'message' => 'The username @' . htmlspecialchars($cleanHandle) . ' is already taken. Please choose another username.'];
        }
        $finalHandle = '@' . strtolower($cleanHandle);
    } else {
        // Auto-generate candidate handle from full name
        $baseHandle = preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', strtolower($fullName)));
        if (empty($baseHandle)) {
            $baseHandle = 'user';
        }
        $candidate = substr($baseHandle, 0, 20);
        $chkHandle = $db->prepare("SELECT id FROM users WHERE LOWER(username_handle) = LOWER(:h1) OR LOWER(username_handle) = LOWER(:h2) LIMIT 1");
        $chkHandle->execute([
            'h1' => $candidate,
            'h2' => '@' . $candidate
        ]);
        if ($chkHandle->fetch()) {
            $candidate .= rand(100, 999);
        }
        $finalHandle = '@' . strtolower($candidate);
    }

    try {
        // Check if mobile already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE mobile = :mobile LIMIT 1");
        $stmt->execute(['mobile' => $mobile]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'This mobile number is already registered. Please login.'];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $numericBlockId = (is_numeric($blockId) && intval($blockId) > 0) ? intval($blockId) : null;
        $numericVillageId = (is_numeric($villageId) && intval($villageId) > 0) ? intval($villageId) : null;

        $stmt = $db->prepare("INSERT INTO users (full_name, name, username_handle, mobile, email, password_hash, block_id, village_id, address, state_code, district_code) VALUES (:name, :name_val, :handle, :mobile, :email, :pass, :block, :village, :address, :state, :district)");
        $stmt->execute([
            'name' => $fullName,
            'name_val' => $fullName,
            'handle' => $finalHandle,
            'mobile' => $mobile,
            'email' => !empty($email) ? $email : null,
            'pass' => $passwordHash,
            'block' => $numericBlockId,
            'village' => $numericVillageId,
            'address' => !empty($address) ? $address : null,
            'state' => $stateCode,
            'district' => $districtCode
        ]);

        $userId = $db->lastInsertId();

        // Send registration SMS via gateway
        require_once __DIR__ . '/sms_helper.php';
        if (function_exists('sendOTP')) {
            sendOTP($mobile, $fullName, $password);
        } elseif (function_exists('send_registration_sms')) {
            send_registration_sms($mobile, $fullName, $password);
        }

        // Auto Login user
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $fullName;
        $_SESSION['user_mobile'] = $mobile;
        $_SESSION['user_handle'] = $finalHandle;

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
    $cleanHandle = ltrim($input, '@');

    if (empty($input) || empty($password)) {
        return ['success' => false, 'message' => 'Please enter your mobile number, email, or username and password.'];
    }

    try {
        $sql = "SELECT * FROM users WHERE ";
        $where = [];
        $params = [];

        if (!empty($cleanMobile) && strlen($cleanMobile) >= 10) {
            $where[] = "mobile = :m_raw";
            $params['m_raw'] = $cleanMobile;

            $where[] = "mobile = :m_10";
            $params['m_10'] = $mobile10;

            $where[] = "RIGHT(mobile, 10) = :m_right";
            $params['m_right'] = $mobile10;
        }

        $where[] = "email = :email";
        $params['email'] = $input;

        $where[] = "LOWER(username_handle) = LOWER(:h_raw)";
        $params['h_raw'] = $input;

        $where[] = "LOWER(username_handle) = LOWER(:h_clean)";
        $params['h_clean'] = $cleanHandle;

        $where[] = "LOWER(username_handle) = LOWER(:h_at)";
        $params['h_at'] = '@' . $cleanHandle;

        $sql .= "(" . implode(" OR ", $where) . ") LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'No account found with this mobile number, email, or username.'];
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
    unset($_SESSION['otp_code']);
    unset($_SESSION['otp_expiry']);
    unset($_SESSION['otp_mobile']);
    unset($_SESSION['otp_verified']);
    unset($_SESSION['pwd_reset_step']);
    unset($_SESSION['reset_mobile']);
    unset($_SESSION['reset_user_name']);
}

function getUserListings($mobileOrUserId) {
    $db = getDB();
    if (!$db) return [];

    $userId = 0;
    $mobile = '';

    if (is_numeric($mobileOrUserId) && intval($mobileOrUserId) > 0 && strlen((string)$mobileOrUserId) < 10) {
        $userId = intval($mobileOrUserId);
        $uStmt = $db->prepare("SELECT mobile FROM users WHERE id = :id LIMIT 1");
        $uStmt->execute(['id' => $userId]);
        $mobile = $uStmt->fetchColumn() ?: '';
    } else {
        $mobile = preg_replace('/[^0-9]/', '', (string)$mobileOrUserId);
        if (!empty($mobile)) {
            $uStmt = $db->prepare("SELECT id FROM users WHERE mobile = :m OR RIGHT(mobile, 10) = :m10 LIMIT 1");
            $uStmt->execute([
                'm' => $mobile,
                'm10' => (strlen($mobile) >= 10) ? substr($mobile, -10) : $mobile
            ]);
            $userId = intval($uStmt->fetchColumn() ?: 0);
        }
    }

    $cleanMobile = (strlen($mobile) >= 10) ? substr($mobile, -10) : $mobile;
    if (empty($mobile) && $userId <= 0) return [];

    // Auto-heal orphaned claims & listings for user
    if ($userId > 0) {
        try {
            if (!empty($cleanMobile)) {
                $db->prepare("UPDATE claims SET user_id = :uid WHERE status IN ('APPROVED', 'PENDING') AND (user_id IS NULL OR user_id = 0) AND (claimant_mobile = :mob OR RIGHT(claimant_mobile, 10) = :m10 OR claimant_mobile LIKE :m_like)")
                   ->execute(['uid' => $userId, 'mob' => $mobile, 'm10' => $cleanMobile, 'm_like' => '%' . $cleanMobile]);

                $db->prepare("UPDATE listings SET user_id = :uid WHERE (user_id IS NULL OR user_id = 0) AND (mobile = :mob OR RIGHT(mobile, 10) = :m10 OR mobile LIKE :m_like)")
                   ->execute(['uid' => $userId, 'mob' => $mobile, 'm10' => $cleanMobile, 'm_like' => '%' . $cleanMobile]);
            }

            $db->prepare("UPDATE listings SET user_id = :uid, is_verified = 'YES' WHERE id IN (SELECT listing_id FROM claims WHERE user_id = :uid2 AND status = 'APPROVED') AND (user_id IS NULL OR user_id = 0)")
               ->execute(['uid' => $userId, 'uid2' => $userId]);
        } catch (Exception $e) {
            error_log("getUserListings autoheal error: " . $e->getMessage());
        }
    }

    try {
        ensureClaimsTable();
        $sql = "SELECT l.*, c.name as category_name, b.name as block_name,
                       (SELECT cl.id FROM claims cl WHERE cl.listing_id = l.id AND ((cl.user_id IS NOT NULL AND cl.user_id = :uid1) OR (cl.claimant_mobile = :mob1 OR RIGHT(cl.claimant_mobile, 10) = :mob2 OR cl.claimant_mobile LIKE :mob_like1)) ORDER BY FIELD(cl.status, 'APPROVED', 'PENDING', 'REJECTED') ASC, cl.id DESC LIMIT 1) as claim_id,
                       (SELECT cl.status FROM claims cl WHERE cl.listing_id = l.id AND ((cl.user_id IS NOT NULL AND cl.user_id = :uid3) OR (cl.claimant_mobile = :mob3 OR RIGHT(cl.claimant_mobile, 10) = :mob4 OR cl.claimant_mobile LIKE :mob_like2)) ORDER BY FIELD(cl.status, 'APPROVED', 'PENDING', 'REJECTED') ASC, cl.id DESC LIMIT 1) as claim_status,
                       (SELECT cl.role_title FROM claims cl WHERE cl.listing_id = l.id AND ((cl.user_id IS NOT NULL AND cl.user_id = :uid5) OR (cl.claimant_mobile = :mob5 OR RIGHT(cl.claimant_mobile, 10) = :mob6 OR cl.claimant_mobile LIKE :mob_like3)) ORDER BY FIELD(cl.status, 'APPROVED', 'PENDING', 'REJECTED') ASC, cl.id DESC LIMIT 1) as claim_role
                FROM listings l 
                LEFT JOIN categories c ON l.category_id = c.id 
                LEFT JOIN blocks b ON l.block_id = b.id 
                WHERE 
                (
                    (:uid7 > 0 AND l.user_id = :uid8) OR
                    (:mob7 != '' AND (l.mobile = :mob8 OR RIGHT(l.mobile, 10) = :mob9 OR l.mobile LIKE :mob_like4)) OR
                    l.id IN (
                        SELECT cl2.listing_id FROM claims cl2 
                        WHERE cl2.status IN ('APPROVED', 'PENDING') 
                        AND ((:uid10 > 0 AND cl2.user_id = :uid11) OR (:mob10 != '' AND (cl2.claimant_mobile = :mob11 OR RIGHT(cl2.claimant_mobile, 10) = :mob12 OR cl2.claimant_mobile LIKE :mob_like5)))
                    )
                )
                ORDER BY l.id DESC";

        $stmt = $db->prepare($sql);
        $cleanLike = !empty($cleanMobile) ? '%' . $cleanMobile : '___NO_MATCH___';
        $stmt->execute([
            'uid1' => $userId,
            'mob1' => $mobile,
            'mob2' => $cleanMobile,
            'mob_like1' => $cleanLike,
            'uid3' => $userId,
            'mob3' => $mobile,
            'mob4' => $cleanMobile,
            'mob_like2' => $cleanLike,
            'uid5' => $userId,
            'mob5' => $mobile,
            'mob6' => $cleanMobile,
            'mob_like3' => $cleanLike,
            'uid7' => $userId,
            'uid8' => $userId,
            'mob7' => $cleanMobile,
            'mob8' => $mobile,
            'mob9' => $cleanMobile,
            'mob_like4' => $cleanLike,
            'uid10' => $userId,
            'uid11' => $userId,
            'mob10' => $cleanMobile,
            'mob11' => $mobile,
            'mob12' => $cleanMobile,
            'mob_like5' => $cleanLike
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("getUserListings error: " . $e->getMessage());
        return [];
    }
}

function updateUserProfile($userId, $fullName, $email = '', $blockId = null, $address = '', $newPassword = '', $whatsapp = '', $businessName = '', $designation = '', $pincode = '', $panchayatId = null, $villageId = null, $bio = '') {
    if (is_array($fullName)) {
        return updateProfessionalUserProfile($userId, $fullName);
    }

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
    if (function_exists('sendOTP')) {
        sendOTP($cleanMobile, $userName, $otp);
    } elseif (function_exists('send_registration_sms')) {
        send_registration_sms($cleanMobile, $userName, $otp);
    }

    return $otp;
}

function verifyMobileOTP($mobile, $inputOtp) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $cleanMobile = preg_replace('/[^0-9]/', '', (string)$mobile);
    if (strlen($cleanMobile) >= 10) {
        $cleanMobile = substr($cleanMobile, -10);
    }

    $sessionMobile = preg_replace('/[^0-9]/', '', (string)($_SESSION['otp_mobile'] ?? ''));
    if (strlen($sessionMobile) >= 10) {
        $sessionMobile = substr($sessionMobile, -10);
    }

    $sessionOtp = (string)($_SESSION['otp_code'] ?? '');
    $expiry     = (int)($_SESSION['otp_expiry'] ?? 0);

    if (empty($sessionOtp) || time() > $expiry) {
        return ['success' => false, 'message' => 'OTP has expired. Please request a new OTP.'];
    }

    if (!empty($sessionMobile) && !empty($cleanMobile) && $cleanMobile !== $sessionMobile) {
        return ['success' => false, 'message' => 'Mobile number mismatch. Please request OTP again.'];
    }

    if (trim((string)$inputOtp) !== trim($sessionOtp)) {
        return ['success' => false, 'message' => 'Invalid OTP code entered. Please check and try again.'];
    }

    // OTP verified
    unset($_SESSION['otp_code']);
    unset($_SESSION['otp_expiry']);

    return ['success' => true, 'message' => 'Mobile number verified successfully!'];
}

function generateCategoryParagraph($category, $subcategories, $isHindi = false) {
    if (empty($category) || empty($subcategories)) return '';

    $catName = $isHindi ? (!empty($category['hindi_name']) ? $category['hindi_name'] : $category['name']) : $category['name'];

    $profNames = [];
    $bizNames = [];
    $allKeywords = [];

    foreach ($subcategories as $sc) {
        $sName = $isHindi ? (!empty($sc['hindi_name']) ? $sc['hindi_name'] : $sc['name']) : $sc['name'];
        if (isset($sc['type']) && $sc['type'] === 'BUSINESS') {
            $bizNames[] = $sName;
        } else {
            $profNames[] = $sName;
        }

        if (!empty($sc['keywords'])) {
            $kwList = explode(',', $sc['keywords']);
            foreach ($kwList as $kw) {
                $trimmed = trim($kw);
                if (!empty($trimmed) && !in_array(strtolower($trimmed), array_map('strtolower', $allKeywords))) {
                    $allKeywords[] = $trimmed;
                }
            }
        }
    }

    if (!$isHindi) {
        $paragraph = "Welcome to the <strong>" . sanitizeInput($catName) . "</strong> directory on Saran Index, your trusted digital guide for Saran District. ";
        
        if (!empty($profNames)) {
            $profStr = implode(', ', array_map('sanitizeInput', array_slice($profNames, 0, 6)));
            if (count($profNames) > 6) $profStr .= ' and more';
            $paragraph .= "Here, you can easily find and connect with local professionals, skilled personnel, and experts such as <strong>" . $profStr . "</strong>. ";
        }

        if (!empty($bizNames)) {
            $bizStr = implode(', ', array_map('sanitizeInput', array_slice($bizNames, 0, 6)));
            if (count($bizNames) > 6) $bizStr .= ' and more';
            $paragraph .= "Our verified database also features leading businesses, stores, and service centers including <strong>" . $bizStr . "</strong>. ";
        }

        if (!empty($allKeywords)) {
            $kwStr = implode(', ', array_map('sanitizeInput', array_slice($allKeywords, 0, 10)));
            $paragraph .= "<br><br><strong>Key Search Keywords & Services Covered:</strong> <span class='text-dark fw-medium'>" . $kwStr . "</span>. ";
        }

        $paragraph .= "<br>Whether you are located in Chapra town or across any of the 20 blocks of Saran District, explore verified contacts, addresses, phone numbers, and WhatsApp details for all your <strong>" . sanitizeInput($catName) . "</strong> needs.";
    } else {
        $paragraph = "सारण इंडेक्स की <strong>" . sanitizeInput($catName) . "</strong> निर्देशिका में आपका स्वागत है। यह सारण जिले (छपरा) की व्यापक एवं डिजिटल मार्गदर्शिका है। ";

        if (!empty($profNames)) {
            $profStr = implode(', ', array_map('sanitizeInput', array_slice($profNames, 0, 6)));
            if (count($profNames) > 6) $profStr .= ' इत्यादि';
            $paragraph .= "यहाँ आप स्थानीय कुशल कार्यबल, विशेषज्ञों एवं पेशेवरों जैसे <strong>" . $profStr . "</strong> से सीधे संपर्क स्थापित कर सकते हैं। ";
        }

        if (!empty($bizNames)) {
            $bizStr = implode(', ', array_map('sanitizeInput', array_slice($bizNames, 0, 6)));
            if (count($bizNames) > 6) $bizStr .= ' इत्यादि';
            $paragraph .= "इसके अतिरिक्त, हमारे सत्यापित डेटाबेस में <strong>" . $bizStr . "</strong> जैसे प्रमुख व्यावसायिक प्रतिष्ठान एवं संस्थान सूचीबद्ध हैं। ";
        }

        if (!empty($allKeywords)) {
            $kwStr = implode(', ', array_map('sanitizeInput', array_slice($allKeywords, 0, 10)));
            $paragraph .= "<br><br><strong>प्रमुख खोज शब्द एवं सेवाएं (Keywords):</strong> <span class='text-dark fw-medium'>" . $kwStr . "</span>। ";
        }

        $paragraph .= "<br>छपरा शहर से लेकर सारण जिले के सभी 20 प्रखंडों तक, <strong>" . sanitizeInput($catName) . "</strong> से संबंधित सभी आवश्यक संपर्क नंबर, पते और व्हाट्सएप जानकारी यहाँ आसानी से प्राप्त करें।";
    }

    return $paragraph;
}

function getCategoryMetaKeywords($category, $subcategories) {
    if (empty($category)) return '';
    $keywords = [$category['name'], $category['hindi_name'], 'Saran Index', 'Chapra Directory', 'Saran District'];
    if (!empty($subcategories)) {
        foreach ($subcategories as $sc) {
            $keywords[] = $sc['name'];
            if (!empty($sc['hindi_name'])) $keywords[] = $sc['hindi_name'];
            if (!empty($sc['keywords'])) {
                $kwList = explode(',', $sc['keywords']);
                foreach ($kwList as $k) {
                    $t = trim($k);
                    if (!empty($t)) $keywords[] = $t;
                }
            }
        }
    }
    return implode(', ', array_unique($keywords));
}

function getAllListings() {
    $db = getDB();
    if (!$db) return [];
    try {
        $stmt = $db->query("SELECT l.*, c.name as category_name, b.name as block_name FROM listings l LEFT JOIN categories c ON l.category_id = c.id LEFT JOIN blocks b ON l.block_id = b.id ORDER BY l.id DESC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function createOnlinePayment($userId, $listingId, $planType, $amount, $paymentGateway = 'ONLINE') {
    $db = getDB();
    if (!$db || empty($userId)) return null;

    $txnId = 'TXN_' . time() . '_' . rand(1000, 9999);
    try {
        $stmt = $db->prepare("INSERT INTO payments (user_id, listing_id, plan_type, amount, payment_gateway, transaction_id, payment_status) VALUES (:uid, :lid, :plan, :amt, :gw, :txnid, 'PENDING')");
        $stmt->execute([
            'uid' => intval($userId),
            'lid' => !empty($listingId) ? intval($listingId) : null,
            'plan' => $planType,
            'amt' => floatval($amount),
            'gw' => $paymentGateway,
            'txnid' => $txnId
        ]);
        $paymentId = $db->lastInsertId();
        return [
            'id' => $paymentId,
            'transaction_id' => $txnId,
            'amount' => $amount,
            'plan_type' => $planType
        ];
    } catch (PDOException $e) {
        error_log("createOnlinePayment error: " . $e->getMessage());
        return null;
    }
}

function completeOnlinePayment($transactionId, $paymentIdStr = '', $status = 'SUCCESS', $response = '') {
    $db = getDB();
    if (!$db || empty($transactionId)) return false;

    try {
        $stmt = $db->prepare("SELECT * FROM payments WHERE transaction_id = :txnid LIMIT 1");
        $stmt->execute(['txnid' => $transactionId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) return false;

        $upStmt = $db->prepare("UPDATE payments SET payment_id = :pid, payment_status = :st, payment_response = :resp WHERE id = :id");
        $upStmt->execute([
            'pid' => $paymentIdStr,
            'st' => $status,
            'resp' => is_array($response) ? json_encode($response) : $response,
            'id' => $payment['id']
        ]);

        if ($status === 'SUCCESS' && !empty($payment['listing_id'])) {
            $planType = $payment['plan_type'];
            $isFeatured = ($planType === 'PLATINUM') ? 'YES' : 'NO';
            $isVerified = ($planType === 'PLATINUM' || $planType === 'GOLD') ? 'YES' : 'NO';
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 year'));

            $upListing = $db->prepare("UPDATE listings SET plan_type = :plan, plan_expires_at = :exp, is_featured = :feat, is_verified = :ver WHERE id = :id");
            $upListing->execute([
                'plan' => $planType,
                'exp' => $expiresAt,
                'feat' => $isFeatured,
                'ver' => $isVerified,
                'id' => $payment['listing_id']
            ]);
        }

        return true;
    } catch (PDOException $e) {
        error_log("completeOnlinePayment error: " . $e->getMessage());
        return false;
    }
}

function getUserPayments($userId) {
    $db = getDB();
    if (!$db || empty($userId)) return [];

    try {
        $stmt = $db->prepare("SELECT p.*, l.title as listing_title FROM payments p LEFT JOIN listings l ON p.listing_id = l.id WHERE p.user_id = :uid ORDER BY p.id DESC");
        $stmt->execute(['uid' => intval($userId)]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("getUserPayments error: " . $e->getMessage());
        return [];
    }
}

function getAllAdminUsers($status = null, $search = null) {
    $db = getDB();
    if ($db) {
        try {
            $sql = "SELECT u.*, b.name as block_name FROM users u LEFT JOIN blocks b ON u.block_id = b.id WHERE 1=1";
            $params = [];

            if ($status) {
                $sql .= " AND u.status = :status";
                $params['status'] = $status;
            }

            if ($search) {
                $sql .= " AND (u.full_name LIKE :search OR u.mobile LIKE :search OR u.email LIKE :search OR u.business_name LIKE :search)";
                $params['search'] = '%' . $search . '%';
            }

            $sql .= " ORDER BY u.id DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("getAllAdminUsers error: " . $e->getMessage());
        }
    }
    return [];
}

function updateUserStatus($id, $status) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("UPDATE users SET status = :status WHERE id = :id");
            return $stmt->execute(['status' => $status, 'id' => intval($id)]);
        } catch (PDOException $e) {
            error_log("updateUserStatus error: " . $e->getMessage());
        }
    }
    return false;
}

function ensureDeletedUsersTableExists() {
    $db = getDB();
    if (!$db) return;
    try {
        $db->exec("CREATE TABLE IF NOT EXISTS `deleted_users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `original_user_id` INT DEFAULT NULL,
            `full_name` VARCHAR(100) DEFAULT NULL,
            `mobile` VARCHAR(20) DEFAULT NULL,
            `whatsapp` VARCHAR(20) DEFAULT NULL,
            `email` VARCHAR(100) DEFAULT NULL,
            `password_hash` VARCHAR(255) DEFAULT NULL,
            `business_name` VARCHAR(150) DEFAULT NULL,
            `designation` VARCHAR(100) DEFAULT NULL,
            `block_id` INT DEFAULT NULL,
            `panchayat_id` INT DEFAULT NULL,
            `village_id` INT DEFAULT NULL,
            `address` TEXT DEFAULT NULL,
            `pincode` VARCHAR(10) DEFAULT NULL,
            `profile_image` VARCHAR(255) DEFAULT NULL,
            `bio` TEXT DEFAULT NULL,
            `status` VARCHAR(50) DEFAULT NULL,
            `type` VARCHAR(50) DEFAULT NULL,
            `wallet` DECIMAL(10,2) DEFAULT 0.00,
            `user_data_json` LONGTEXT DEFAULT NULL,
            `deleted_by` VARCHAR(100) DEFAULT 'SYSTEM',
            `deleted_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    } catch (PDOException $e) {
        error_log("ensureDeletedUsersTableExists error: " . $e->getMessage());
    }
}

function deleteUser($id, $deleted_by = 'SYSTEM') {
    $db = getDB();
    if (!$db || empty($id)) return false;

    ensureDeletedUsersTableExists();
    $uid = intval($id);

    try {
        // Fetch user data to move to deleted_users table
        $stmtU = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmtU->execute(['id' => $uid]);
        $user = $stmtU->fetch(PDO::FETCH_ASSOC);

        if (!$user) return false;

        // Move user record to deleted_users archive table
        try {
            $insDeleted = $db->prepare("INSERT INTO deleted_users (
                original_user_id, full_name, mobile, whatsapp, email, password_hash,
                business_name, designation, block_id, panchayat_id, village_id, address,
                pincode, profile_image, bio, status, type, wallet, user_data_json, deleted_by
            ) VALUES (
                :orig_id, :full_name, :mobile, :whatsapp, :email, :password_hash,
                :business_name, :designation, :block_id, :panchayat_id, :village_id, :address,
                :pincode, :profile_image, :bio, :status, :type, :wallet, :user_data_json, :deleted_by
            )");
            $insDeleted->execute([
                'orig_id'        => $user['id'],
                'full_name'      => $user['full_name'] ?? $user['name'] ?? '',
                'mobile'         => $user['mobile'] ?? '',
                'whatsapp'       => $user['whatsapp'] ?? null,
                'email'          => $user['email'] ?? null,
                'password_hash'  => $user['password_hash'] ?? $user['password'] ?? '',
                'business_name'  => $user['business_name'] ?? null,
                'designation'    => $user['designation'] ?? null,
                'block_id'       => $user['block_id'] ?? null,
                'panchayat_id'   => $user['panchayat_id'] ?? null,
                'village_id'     => $user['village_id'] ?? null,
                'address'        => $user['address'] ?? null,
                'pincode'        => $user['pincode'] ?? null,
                'profile_image'  => $user['profile_image'] ?? $user['photo'] ?? null,
                'bio'            => $user['bio'] ?? $user['about'] ?? null,
                'status'         => $user['status'] ?? 'DELETED',
                'type'           => $user['type'] ?? 'USER',
                'wallet'         => $user['wallet'] ?? 0.00,
                'user_data_json' => json_encode($user, JSON_UNESCAPED_UNICODE),
                'deleted_by'     => $deleted_by
            ]);
        } catch (PDOException $ex) {
            error_log("Failed to insert into deleted_users: " . $ex->getMessage());
        }

        // Delete profile photo from disk if present
        if (!empty($user['profile_image'])) {
            $imgPath = __DIR__ . '/../' . ltrim($user['profile_image'], '/');
            if (file_exists($imgPath) && is_file($imgPath)) {
                @unlink($imgPath);
            }
        }
        if (!empty($user['photo'])) {
            $photoPath = __DIR__ . '/../' . ltrim($user['photo'], '/');
            if (file_exists($photoPath) && is_file($photoPath)) {
                @unlink($photoPath);
            }
        }

        // Nullify foreign key references in related tables to prevent constraint violations
        try {
            $db->prepare("UPDATE listings SET user_id = NULL WHERE user_id = :uid")->execute(['uid' => $uid]);
        } catch (PDOException $ex) {}

        try {
            $db->prepare("UPDATE reviews SET user_id = NULL WHERE user_id = :uid")->execute(['uid' => $uid]);
        } catch (PDOException $ex) {}

        try {
            $db->prepare("UPDATE claims SET user_id = NULL WHERE user_id = :uid")->execute(['uid' => $uid]);
        } catch (PDOException $ex) {}

        try {
            $db->prepare("UPDATE payments SET user_id = NULL WHERE user_id = :uid")->execute(['uid' => $uid]);
        } catch (PDOException $ex) {}

        // Delete user record from users table
        $delStmt = $db->prepare("DELETE FROM users WHERE id = :uid");
        $result = $delStmt->execute(['uid' => $uid]);

        // If the current logged-in user deleted their own profile, destroy session
        if ($result && session_status() !== PHP_SESSION_NONE && isset($_SESSION['user_id']) && intval($_SESSION['user_id']) === $uid) {
            logoutPublicUser();
        }

        return $result;
    } catch (PDOException $e) {
        error_log("deleteUser error: " . $e->getMessage());
        return false;
    }
}

function getAllAdmins() {
    ensureAdminsTableExists();
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->query("SELECT a.*, b.name as block_name, b.hindi_name as block_hindi_name 
                                FROM admins a 
                                LEFT JOIN blocks b ON a.block_id = b.id 
                                ORDER BY a.id ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("getAllAdmins error: " . $e->getMessage());
        }
    }
    return [];
}

function saveAdminAccount($data) {
    ensureAdminsTableExists();
    $db = getDB();
    if (!$db) return false;

    // Handle legacy calls with array vs positional args
    if (!is_array($data)) {
        $args = func_get_args();
        $data = [
            'username' => $args[0] ?? '',
            'password' => $args[1] ?? '',
            'full_name' => $args[2] ?? '',
            'email' => $args[3] ?? '',
            'role' => $args[4] ?? 'SUPER_ADMIN'
        ];
    }

    $username = trim($data['username'] ?? '');
    $password = trim($data['password'] ?? '');
    $full_name = trim($data['full_name'] ?? '');
    $email = trim($data['email'] ?? '');
    $mobile = trim($data['mobile'] ?? '');
    $role = sanitizeInput($data['role'] ?? 'SUB_ADMIN');
    $scope_type = sanitizeInput($data['scope_type'] ?? 'DISTRICT');
    $state = !empty($data['state']) ? sanitizeInput($data['state']) : 'Bihar';
    $district = !empty($data['district']) ? sanitizeInput($data['district']) : 'Saran';
    $block_id = (!empty($data['block_id']) && is_numeric($data['block_id'])) ? intval($data['block_id']) : null;
    $designation = sanitizeInput($data['designation'] ?? '');
    $address = sanitizeInput($data['address'] ?? '');
    $about = sanitizeInput($data['about'] ?? '');

    try {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO admins (
            username, password_hash, full_name, email, mobile, role, scope_type, 
            state, district, block_id, designation, address, about
        ) VALUES (
            :u, :h, :fn, :em, :mob, :r, :stype, 
            :st, :dst, :blk, :desig, :addr, :abt
        )");

        return $stmt->execute([
            'u' => $username,
            'h' => $hash,
            'fn' => $full_name,
            'em' => $email ?: null,
            'mob' => $mobile ?: null,
            'r' => $role,
            'stype' => $scope_type,
            'st' => $state,
            'dst' => $district,
            'blk' => $block_id,
            'desig' => $designation ?: null,
            'addr' => $address ?: null,
            'abt' => $about ?: null
        ]);
    } catch (PDOException $e) {
        error_log("saveAdminAccount error: " . $e->getMessage());
        return false;
    }
}

function deleteAdminAccount($id) {
    ensureAdminsTableExists();
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("DELETE FROM admins WHERE id = :id");
            return $stmt->execute(['id' => intval($id)]);
        } catch (PDOException $e) {
            error_log("deleteAdminAccount error: " . $e->getMessage());
        }
    }
    return false;
}

function updateAdminAccount($id, $data) {
    ensureAdminsTableExists();
    $db = getDB();
    if (!$db) return false;

    $id = intval($id);
    $username = trim($data['username'] ?? '');
    $full_name = trim($data['full_name'] ?? '');
    $email = trim($data['email'] ?? '');
    $mobile = trim($data['mobile'] ?? '');
    $role = sanitizeInput($data['role'] ?? 'SUB_ADMIN');
    $scope_type = sanitizeInput($data['scope_type'] ?? 'DISTRICT');
    $state = !empty($data['state']) ? sanitizeInput($data['state']) : 'Bihar';
    $district = !empty($data['district']) ? sanitizeInput($data['district']) : 'Saran';
    $block_id = (!empty($data['block_id']) && is_numeric($data['block_id'])) ? intval($data['block_id']) : null;
    $designation = sanitizeInput($data['designation'] ?? '');
    $address = sanitizeInput($data['address'] ?? '');
    $about = sanitizeInput($data['about'] ?? '');
    $password = trim($data['password'] ?? '');

    try {
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE admins SET
                username = :u,
                password_hash = :h,
                full_name = :fn,
                email = :em,
                mobile = :mob,
                role = :r,
                scope_type = :stype,
                state = :st,
                district = :dst,
                block_id = :blk,
                designation = :desig,
                address = :addr,
                about = :abt
                WHERE id = :id");
            return $stmt->execute([
                'u' => $username,
                'h' => $hash,
                'fn' => $full_name,
                'em' => $email ?: null,
                'mob' => $mobile ?: null,
                'r' => $role,
                'stype' => $scope_type,
                'st' => $state,
                'dst' => $district,
                'blk' => $block_id,
                'desig' => $designation ?: null,
                'addr' => $address ?: null,
                'abt' => $about ?: null,
                'id' => $id
            ]);
        } else {
            $stmt = $db->prepare("UPDATE admins SET
                username = :u,
                full_name = :fn,
                email = :em,
                mobile = :mob,
                role = :r,
                scope_type = :stype,
                state = :st,
                district = :dst,
                block_id = :blk,
                designation = :desig,
                address = :addr,
                about = :abt
                WHERE id = :id");
            return $stmt->execute([
                'u' => $username,
                'fn' => $full_name,
                'em' => $email ?: null,
                'mob' => $mobile ?: null,
                'r' => $role,
                'stype' => $scope_type,
                'st' => $state,
                'dst' => $district,
                'blk' => $block_id,
                'desig' => $designation ?: null,
                'addr' => $address ?: null,
                'abt' => $about ?: null,
                'id' => $id
            ]);
        }
    } catch (PDOException $e) {
        error_log("updateAdminAccount error: " . $e->getMessage());
        return false;
    }
}

function toggleUserMobileVerification($id) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("UPDATE users SET mobile_status = IF(mobile_status = 'VERIFIED', 'UNVERIFIED', 'VERIFIED') WHERE id = :id");
            return $stmt->execute(['id' => intval($id)]);
        } catch (PDOException $e) {
            error_log("toggleUserMobileVerification error: " . $e->getMessage());
        }
    }
    return false;
}

function toggleUserEmailVerification($id) {
    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->prepare("UPDATE users SET email_status = IF(email_status = 'VERIFIED', 'UNVERIFIED', 'VERIFIED') WHERE id = :id");
            return $stmt->execute(['id' => intval($id)]);
        } catch (PDOException $e) {
            error_log("toggleUserEmailVerification error: " . $e->getMessage());
        }
    }
    return false;
}

function getUserById($id) {
    $db = getDB();
    if ($db && !empty($id)) {
        try {
            $stmt = $db->prepare("SELECT u.*, b.name as block_name, c.name as category_name, c.hindi_name as category_hindi_name, sc.name as subcategory_name, sc.hindi_name as subcategory_hindi_name 
                                  FROM users u 
                                  LEFT JOIN blocks b ON u.block_id = b.id 
                                  LEFT JOIN categories c ON u.category_id = c.id 
                                  LEFT JOIN subcategories sc ON u.subcategory_id = sc.id 
                                  WHERE u.id = :id LIMIT 1");
            $stmt->execute(['id' => intval($id)]);
            $res = $stmt->fetch();
            if ($res) return $res;
        } catch (PDOException $e) {
            error_log("getUserById error: " . $e->getMessage());
        }
    }
    return null;
}

function getUserByMobile($mobile) {
    $db = getDB();
    if ($db && !empty($mobile)) {
        try {
            $cleanMobile = preg_replace('/[^0-9]/', '', $mobile);
            $m10 = (strlen($cleanMobile) >= 10) ? substr($cleanMobile, -10) : $cleanMobile;
            if (!empty($m10)) {
                $stmt = $db->prepare("SELECT u.*, b.name as block_name, c.name as category_name, c.hindi_name as category_hindi_name, sc.name as subcategory_name, sc.hindi_name as subcategory_hindi_name 
                                      FROM users u 
                                      LEFT JOIN blocks b ON u.block_id = b.id 
                                      LEFT JOIN categories c ON u.category_id = c.id 
                                      LEFT JOIN subcategories sc ON u.subcategory_id = sc.id 
                                      WHERE u.mobile = :mob OR u.mobile = :m10 OR RIGHT(u.mobile, 10) = :m10 LIMIT 1");
                $stmt->execute(['mob' => $mobile, 'm10' => $m10]);
            } else {
                $stmt = $db->prepare("SELECT u.*, b.name as block_name, c.name as category_name, c.hindi_name as category_hindi_name, sc.name as subcategory_name, sc.hindi_name as subcategory_hindi_name 
                                      FROM users u 
                                      LEFT JOIN blocks b ON u.block_id = b.id 
                                      LEFT JOIN categories c ON u.category_id = c.id 
                                      LEFT JOIN subcategories sc ON u.subcategory_id = sc.id 
                                      WHERE u.mobile = :mob LIMIT 1");
                $stmt->execute(['mob' => $mobile]);
            }
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($res) return $res;
        } catch (PDOException $e) {
            error_log("getUserByMobile error: " . $e->getMessage());
        }
    }
    return null;
}

function isAdminLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function isListingUserMobileActive($listingIdOrData, &$reason = '') {
    $listing = null;
    if (is_array($listingIdOrData)) {
        $listing = $listingIdOrData;
    } else {
        $listing = getListingById(intval($listingIdOrData));
    }

    if (!$listing) {
        $reason = "Listing record not found.";
        return false;
    }

    $user = null;
    if (!empty($listing['user_id'])) {
        $user = getUserById($listing['user_id']);
    }
    
    if (!$user && !empty($listing['mobile'])) {
        $user = getUserByMobile($listing['mobile']);
    }

    // If no registered user account is linked, this is an unregistered guest submission.
    // Unregistered guest submissions are allowed and can be approved/published by Admin.
    if (!$user) {
        $reason = "Unregistered guest submission (no user account). Admin approval allowed.";
        return true;
    }

    $userStatus = strtoupper($user['status'] ?? 'ACTIVE');
    if ($userStatus === 'SUSPENDED' || $userStatus === 'INACTIVE') {
        $reason = "The owner's user account (Mobile: " . htmlspecialchars($user['mobile'] ?? '') . ") is " . $userStatus . ". Listing cannot be approved until user account is ACTIVE.";
        return false;
    }

    return true;
}

function saveUserFromAdmin($data, $id) {
    $db = getDB();
    if (!$db || empty($id)) return false;

    $cleanHandle = !empty($data['username_handle']) ? ltrim(trim($data['username_handle']), '@') : null;

    $params = [
        'full_name' => sanitizeInput($data['full_name'] ?? ''),
        'name_val' => sanitizeInput($data['full_name'] ?? ''),
        'handle' => $cleanHandle ? ('@' . $cleanHandle) : null,
        'mobile' => sanitizeInput($data['mobile'] ?? ''),
        'whatsapp' => sanitizeInput($data['whatsapp'] ?? ''),
        'email' => sanitizeInput($data['email'] ?? ''),
        'business_name' => sanitizeInput($data['business_name'] ?? ''),
        'designation' => sanitizeInput($data['designation'] ?? ''),
        'pcat' => sanitizeInput($data['profession_category'] ?? ''),
        'cat_id' => (!empty($data['category_id']) && is_numeric($data['category_id'])) ? intval($data['category_id']) : null,
        'subcat_id' => (!empty($data['subcategory_id']) && is_numeric($data['subcategory_id'])) ? intval($data['subcategory_id']) : null,
        'spec' => sanitizeInput($data['specialization'] ?? ''),
        'edu' => sanitizeInput($data['education'] ?? ''),
        'exp' => sanitizeInput($data['experience_years'] ?? ''),
        'ohours' => sanitizeInput($data['office_hours'] ?? ''),
        'block_id' => (!empty($data['block_id']) && is_numeric($data['block_id'])) ? intval($data['block_id']) : null,
        'village_id' => (!empty($data['village_id']) && is_numeric($data['village_id'])) ? intval($data['village_id']) : null,
        'address' => sanitizeInput($data['address'] ?? ''),
        'pincode' => sanitizeInput($data['pincode'] ?? '841301'),
        'bio' => sanitizeInput($data['bio'] ?? ''),
        'about' => sanitizeInput($data['about'] ?? ''),
        'status' => in_array(strtoupper($data['status'] ?? ''), ['ACTIVE', 'INACTIVE', 'SUSPENDED']) ? strtoupper($data['status']) : 'ACTIVE',
        'type' => in_array(strtoupper($data['type'] ?? ''), ['USER', 'AGENT', 'ADMIN']) ? strtoupper($data['type']) : 'USER',
        'mobile_status' => (strtoupper($data['mobile_status'] ?? '') === 'VERIFIED') ? 'VERIFIED' : 'UNVERIFIED',
        'email_status' => (strtoupper($data['email_status'] ?? '') === 'VERIFIED') ? 'VERIFIED' : 'UNVERIFIED',
        'pvis' => in_array($data['profile_visibility'] ?? '', ['PUBLIC', 'PRIVATE']) ? $data['profile_visibility'] : 'PUBLIC',
        'id' => intval($id)
    ];

    try {
        $sql = "UPDATE users SET 
            full_name = :full_name,
            name = :name_val,
            username_handle = :handle,
            mobile = :mobile,
            whatsapp = :whatsapp,
            email = :email,
            business_name = :business_name,
            designation = :designation,
            profession_category = :pcat,
            category_id = :cat_id,
            subcategory_id = :subcat_id,
            specialization = :spec,
            education = :edu,
            experience_years = :exp,
            office_hours = :ohours,
            block_id = :block_id,
            village_id = :village_id,
            address = :address,
            pincode = :pincode,
            bio = :bio,
            about = :about,
            status = :status,
            type = :type,
            mobile_status = :mobile_status,
            email_status = :email_status,
            profile_visibility = :pvis";

        if (!empty($data['password'])) {
            $sql .= ", password_hash = :hash, password = :pass";
            $params['hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $params['pass'] = $data['password'];
        }

        if (!empty($data['profile_image'])) {
            $sql .= ", profile_image = :pimg";
            $params['pimg'] = $data['profile_image'];
        }

        $sql .= " WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        error_log("saveUserFromAdmin error: " . $e->getMessage());
        return false;
    }
}

function getAllAdminPayments($status_filter = null, $search = '') {
    $db = getDB();
    if (!$db) return [];

    try {
        $sql = "SELECT p.*, u.full_name as user_name, u.mobile as user_mobile, u.email as user_email, l.title as listing_title 
                FROM payments p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN listings l ON p.listing_id = l.id 
                WHERE 1=1";
        $params = [];

        if (!empty($status_filter)) {
            $sql .= " AND p.payment_status = :status";
            $params['status'] = strtoupper($status_filter);
        }

        if (!empty($search)) {
            $sql .= " AND (p.transaction_id LIKE :s OR p.payment_id LIKE :s OR u.full_name LIKE :s OR u.mobile LIKE :s OR l.title LIKE :s)";
            $params['s'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY p.id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("getAllAdminPayments error: " . $e->getMessage());
        return [];
    }
}

function getPaymentSummaryStats() {
    $db = getDB();
    $stats = [
        'total_revenue' => 0.00,
        'successful_count' => 0,
        'pending_count' => 0,
        'failed_count' => 0,
        'gold_revenue' => 0.00,
        'platinum_revenue' => 0.00
    ];

    if (!$db) return $stats;

    try {
        $stats['total_revenue'] = (float)$db->query("SELECT SUM(amount) FROM payments WHERE payment_status = 'SUCCESS'")->fetchColumn();
        $stats['successful_count'] = (int)$db->query("SELECT COUNT(*) FROM payments WHERE payment_status = 'SUCCESS'")->fetchColumn();
        $stats['pending_count'] = (int)$db->query("SELECT COUNT(*) FROM payments WHERE payment_status = 'PENDING'")->fetchColumn();
        $stats['failed_count'] = (int)$db->query("SELECT COUNT(*) FROM payments WHERE payment_status = 'FAILED'")->fetchColumn();
        $stats['gold_revenue'] = (float)$db->query("SELECT SUM(amount) FROM payments WHERE payment_status = 'SUCCESS' AND plan_type = 'GOLD'")->fetchColumn();
        $stats['platinum_revenue'] = (float)$db->query("SELECT SUM(amount) FROM payments WHERE payment_status = 'SUCCESS' AND plan_type = 'PLATINUM'")->fetchColumn();
    } catch (PDOException $e) {
        error_log("getPaymentSummaryStats error: " . $e->getMessage());
    }

    return $stats;
}

function updatePaymentStatus($paymentId, $status) {
    $db = getDB();
    if (!$db || empty($paymentId)) return false;

    try {
        $stmt = $db->prepare("UPDATE payments SET payment_status = :st WHERE id = :id");
        $res = $stmt->execute([
            'st' => strtoupper($status),
            'id' => intval($paymentId)
        ]);

        if ($res && strtoupper($status) === 'SUCCESS') {
            // Activate plan if listing ID is linked
            $pStmt = $db->prepare("SELECT * FROM payments WHERE id = :id LIMIT 1");
            $pStmt->execute(['id' => intval($paymentId)]);
            $payment = $pStmt->fetch(PDO::FETCH_ASSOC);

            if ($payment && !empty($payment['listing_id'])) {
                $planType = $payment['plan_type'];
                $isFeatured = ($planType === 'PLATINUM') ? 'YES' : 'NO';
                $isVerified = ($planType === 'PLATINUM' || $planType === 'GOLD') ? 'YES' : 'NO';
                $expiresAt = date('Y-m-d H:i:s', strtotime('+1 year'));

                $upListing = $db->prepare("UPDATE listings SET plan_type = :plan, plan_expires_at = :exp, is_featured = :feat, is_verified = :ver WHERE id = :id");
                $upListing->execute([
                    'plan' => $planType,
                    'exp' => $expiresAt,
                    'feat' => $isFeatured,
                    'ver' => $isVerified,
                    'id' => $payment['listing_id']
                ]);
            }
        }

        return $res;
    } catch (PDOException $e) {
        error_log("updatePaymentStatus error: " . $e->getMessage());
        return false;
    }
}

function getUserByHandle($handle) {
    ensureUsersTable();
    $db = getDB();
    if (!$db || empty($handle)) return null;

    $cleanHandle = ltrim(trim($handle), '@');

    try {
        $stmt = $db->prepare("SELECT u.*, b.name as block_name, b.hindi_name as block_hindi_name, c.name as category_name, c.hindi_name as category_hindi_name, sc.name as subcategory_name, sc.hindi_name as subcategory_hindi_name 
                              FROM users u 
                              LEFT JOIN blocks b ON u.block_id = b.id 
                              LEFT JOIN categories c ON u.category_id = c.id 
                              LEFT JOIN subcategories sc ON u.subcategory_id = sc.id 
                              WHERE (u.username_handle = :h1 OR u.username_handle = :h2 OR u.id = :id) LIMIT 1");
        $stmt->execute([
            'h1' => $cleanHandle,
            'h2' => '@' . $cleanHandle,
            'id' => is_numeric($cleanHandle) ? intval($cleanHandle) : 0
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) return $user;
    } catch (PDOException $e) {
        error_log("getUserByHandle error: " . $e->getMessage());
    }
    return null;
}

function searchUsersByHandleOrName($query, $limit = 5) {
    $db = getDB();
    if (!$db || empty(trim($query))) return [];
    
    $cleanQ = ltrim(trim($query), '@');
    if (empty($cleanQ)) return [];

    try {
        $stmt = $db->prepare("SELECT id, full_name, username_handle, business_name, designation, profile_image, block_id, profile_visibility 
                              FROM users 
                              WHERE (username_handle LIKE :q1 OR username_handle LIKE :q2 OR full_name LIKE :q3 OR business_name LIKE :q4) 
                              AND (profile_visibility IS NULL OR profile_visibility = 'PUBLIC')
                              ORDER BY id DESC LIMIT :lim");
        $stmt->bindValue(':q1', '@' . $cleanQ . '%', PDO::PARAM_STR);
        $stmt->bindValue(':q2', '%' . $cleanQ . '%', PDO::PARAM_STR);
        $stmt->bindValue(':q3', '%' . $cleanQ . '%', PDO::PARAM_STR);
        $stmt->bindValue(':q4', '%' . $cleanQ . '%', PDO::PARAM_STR);
        $stmt->bindValue(':lim', intval($limit), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("searchUsersByHandleOrName error: " . $e->getMessage());
        return [];
    }
}

function updateProfessionalUserProfile($userId, $data) {
    ensureUsersTable();
    $db = getDB();
    if (!$db || empty($userId)) return false;

    $cleanHandle = !empty($data['username_handle']) ? ltrim(trim($data['username_handle']), '@') : null;

    // Check handle length (8 to 24 chars) & uniqueness if provided
    if (!empty($cleanHandle)) {
        if (strlen($cleanHandle) < 8 || strlen($cleanHandle) > 24) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['profile_update_error'] = "Username handle must be between 8 and 24 characters long.";
            return false;
        }
        $formattedHandle = '@' . $cleanHandle;
        $chkHandle = $db->prepare("SELECT id FROM users WHERE (LOWER(username_handle) = :h1 OR LOWER(username_handle) = :h2) AND id != :uid LIMIT 1");
        $chkHandle->execute(['h1' => strtolower($formattedHandle), 'h2' => strtolower($cleanHandle), 'uid' => $userId]);
        if ($chkHandle->fetch()) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['profile_update_error'] = "The username handle '@" . htmlspecialchars($cleanHandle) . "' is already taken by another user.";
            return false;
        }
    }

    // Preserve existing mobile & email if omitted
    $existingUser = getUserById($userId);
    $mobile = !empty($data['mobile']) ? sanitizeInput($data['mobile']) : ($existingUser['mobile'] ?? '');
    $email = !empty($data['email']) ? sanitizeInput($data['email']) : ($existingUser['email'] ?? '');

    try {
        $stmt = $db->prepare("UPDATE users SET 
            full_name = :fn,
            name = :name_col,
            username_handle = :handle,
            designation = :desig,
            business_name = :bname,
            profession_category = :pcat,
            category_id = :cat_id,
            subcategory_id = :subcat_id,
            specialization = :spec,
            education = :edu,
            experience_years = :exp,
            office_hours = :ohours,
            bio = :bio,
            about = :about,
            mobile = :mob,
            email = :em,
            whatsapp = :wa,
            address = :addr,
            pincode = :pin,
            block_id = :blk,
            profile_visibility = :pvis,
            mobile_visibility = :mvis,
            email_visibility = :evis,
            address_visibility = :avis,
            linkedin = :link,
            twitter = :tw,
            facebook = :fb,
            instagram = :insta
            WHERE id = :id");

        $fullName = sanitizeInput($data['full_name'] ?? ($existingUser['full_name'] ?? ''));

        $res = $stmt->execute([
            'fn' => $fullName,
            'name_col' => $fullName,
            'handle' => $cleanHandle ? ('@' . $cleanHandle) : null,
            'desig' => sanitizeInput($data['designation'] ?? ''),
            'bname' => sanitizeInput($data['business_name'] ?? ''),
            'pcat' => sanitizeInput($data['profession_category'] ?? ''),
            'cat_id' => (!empty($data['category_id']) && is_numeric($data['category_id'])) ? intval($data['category_id']) : null,
            'subcat_id' => (!empty($data['subcategory_id']) && is_numeric($data['subcategory_id'])) ? intval($data['subcategory_id']) : null,
            'spec' => sanitizeInput($data['specialization'] ?? ''),
            'edu' => sanitizeInput($data['education'] ?? ''),
            'exp' => sanitizeInput($data['experience_years'] ?? ''),
            'ohours' => sanitizeInput($data['office_hours'] ?? ''),
            'bio' => sanitizeInput($data['bio'] ?? ''),
            'about' => sanitizeInput($data['about'] ?? ''),
            'mob' => $mobile,
            'em' => $email,
            'wa' => sanitizeInput($data['whatsapp'] ?? ''),
            'addr' => sanitizeInput($data['address'] ?? ''),
            'pin' => sanitizeInput($data['pincode'] ?? ''),
            'blk' => (!empty($data['block_id']) && is_numeric($data['block_id'])) ? intval($data['block_id']) : null,
            'pvis' => in_array($data['profile_visibility'] ?? '', ['PUBLIC', 'PRIVATE']) ? $data['profile_visibility'] : 'PUBLIC',
            'mvis' => in_array($data['mobile_visibility'] ?? '', ['PUBLIC', 'PRIVATE']) ? $data['mobile_visibility'] : 'PUBLIC',
            'evis' => in_array($data['email_visibility'] ?? '', ['PUBLIC', 'PRIVATE']) ? $data['email_visibility'] : 'PUBLIC',
            'avis' => in_array($data['address_visibility'] ?? '', ['PUBLIC', 'PRIVATE']) ? $data['address_visibility'] : 'PUBLIC',
            'link' => sanitizeInput($data['linkedin'] ?? ''),
            'tw' => sanitizeInput($data['twitter'] ?? ''),
            'fb' => sanitizeInput($data['facebook'] ?? ''),
            'insta' => sanitizeInput($data['instagram'] ?? ''),
            'id' => intval($userId)
        ]);

        if (!empty($data['profile_image'])) {
            $db->prepare("UPDATE users SET profile_image = :img WHERE id = :id")->execute([
                'img' => $data['profile_image'],
                'id' => intval($userId)
            ]);
        }

        return $res;
    } catch (PDOException $e) {
        error_log("updateUserProfile error: " . $e->getMessage());
        return false;
    }
}

function uploadUserProfilePhoto($file, $userId) {
    if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExts)) {
        return null;
    }

    $uploadDir = __DIR__ . '/../uploads/users/';
    if (!file_exists($uploadDir)) {
        @mkdir($uploadDir, 0777, true);
    }

    $filename = 'profile_' . intval($userId) . '_' . time() . '.' . $ext;
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return 'uploads/users/' . $filename;
    }

    return null;
}

function getCategoriesList() {
    $db = getDB();
    if (!$db) return [];
    try {
        $stmt = $db->query("SELECT * FROM categories WHERE status = 'ACTIVE' ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("getCategoriesList error: " . $e->getMessage());
        return [];
    }
}

function formatListingLocation($item, $lang = 'en') {
    $address = isset($item['address']) ? trim($item['address']) : '';
    $blockName = isset($item['block_name']) ? trim($item['block_name']) : '';

    if (!empty($address)) {
        return $address;
    }

    // Fallback if address is empty: Block Name, Saran, Bihar
    $distStr = ($lang === 'hi') ? 'सारण' : 'Saran';
    $stateStr = ($lang === 'hi') ? 'बिहार' : 'Bihar';

    $parts = [];
    if (!empty($blockName)) {
        $parts[] = $blockName;
    }
    $parts[] = $distStr;
    $parts[] = $stateStr;
    return implode(', ', $parts);
}

function isMobileNumberVisibleToVisitor($listing) {
    if (empty($listing['mobile'])) {
        return false;
    }

    $vis = strtoupper($listing['mobile_visibility'] ?? 'REGISTERED');
    $mobStatus = strtoupper($listing['mobile_status'] ?? '');

    // If explicitly set to HIDDEN / PRIVATE / BLOCKED / HIDE / NO / DISABLED, or mobile status is BLOCKED, hide for everyone
    if (in_array($vis, ['HIDDEN', 'PRIVATE', 'HIDE', 'NO', 'BLOCKED', 'DISABLED']) || $mobStatus === 'BLOCKED') {
        return false;
    }

    // Registered logged-in users can see the numbers after login
    if (isUserLoggedIn()) {
        return true;
    }

    // Default visibility is REGISTERED (means not public for guests)
    if ($vis !== 'PUBLIC') {
        return false;
    }

    // If mobile status is UNVERIFIED, hide for public guests
    if ($mobStatus === 'UNVERIFIED') {
        return false;
    }

    return true;
}

function isEmailVisibleToVisitor($listing) {
    // Registered logged-in users can always see emails after login
    if (isUserLoggedIn()) {
        return true;
    }

    // Default visibility is REGISTERED (means not public for guests)
    $vis = strtoupper($listing['email_visibility'] ?? 'REGISTERED');
    if ($vis !== 'PUBLIC') {
        return false;
    }

    return true;
}

function maskPhoneNumber($mobile) {
    $clean = preg_replace('/[^0-9]/', '', $mobile);
    if (strlen($clean) >= 10) {
        return substr($clean, 0, 5) . '*****';
    }
    if (strlen($clean) > 3) {
        return substr($clean, 0, 3) . '***';
    }
    return '******';
}

function maskEmailAddress($email) {
    if (empty($email)) return '';
    $parts = explode('@', $email);
    if (count($parts) < 2) return '*****@*****';
    $name = $parts[0];
    $domain = $parts[1];
    $maskedName = strlen($name) > 2 ? substr($name, 0, 2) . '*****' : '*****';
    return $maskedName . '@' . $domain;
}

function checkDuplicateListing($title, $mobile, $excludeId = null) {
    $db = getDB();
    if (!$db) return null;

    $cleanTitle = trim(preg_replace('/\s+/', ' ', $title));
    $cleanMobile = trim(preg_replace('/[^0-9]/', '', $mobile));

    if (empty($cleanTitle) || empty($cleanMobile)) {
        return null;
    }

    $sql = "SELECT id, title, mobile, status, slug FROM listings 
            WHERE LOWER(TRIM(title)) = LOWER(:title) 
            AND (RIGHT(mobile, 10) = RIGHT(:mobile, 10) OR mobile = :raw_mobile)";
    $params = [
        'title' => $cleanTitle,
        'mobile' => $cleanMobile,
        'raw_mobile' => trim($mobile)
    ];

    if (!empty($excludeId)) {
        $sql .= " AND id != :exclude_id";
        $params['exclude_id'] = intval($excludeId);
    }

    $sql .= " LIMIT 1";

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res : null;
    } catch (PDOException $e) {
        error_log("checkDuplicateListing error: " . $e->getMessage());
        return null;
    }
}

function getAllAdminBlocks($search = null) {
    $db = getDB();
    if (!$db) return [];
    try {
        $sql = "SELECT b.*, COUNT(l.id) as listing_count 
                FROM blocks b 
                LEFT JOIN listings l ON b.id = l.block_id";
        $params = [];
        if (!empty($search)) {
            $sql .= " WHERE (b.name LIKE :s1 OR b.hindi_name LIKE :s2 OR b.name_english LIKE :s3 OR b.slug LIKE :s4 OR b.pincode LIKE :s5)";
            $params['s1'] = "%$search%";
            $params['s2'] = "%$search%";
            $params['s3'] = "%$search%";
            $params['s4'] = "%$search%";
            $params['s5'] = "%$search%";
        }
        $sql .= " GROUP BY b.id ORDER BY b.name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("getAllAdminBlocks error: " . $e->getMessage());
        return [];
    }
}

function saveBlock($name, $hindi_name = '', $name_english = '', $slug = '', $pincode = '', $total_panchayats = 0, $id = null) {
    $db = getDB();
    if (!$db || empty($name)) return false;

    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    }

    try {
        if (!empty($id)) {
            $stmt = $db->prepare("UPDATE blocks SET name = :name, hindi_name = :hindi_name, name_english = :name_english, slug = :slug, pincode = :pincode, total_panchayats = :total_panchayats WHERE id = :id");
            return $stmt->execute([
                'name' => $name,
                'hindi_name' => $hindi_name,
                'name_english' => !empty($name_english) ? $name_english : $name,
                'slug' => $slug,
                'pincode' => $pincode,
                'total_panchayats' => intval($total_panchayats),
                'id' => intval($id)
            ]);
        } else {
            $stmt = $db->prepare("INSERT INTO blocks (name, hindi_name, name_english, slug, pincode, total_panchayats) VALUES (:name, :hindi_name, :name_english, :slug, :pincode, :total_panchayats)");
            return $stmt->execute([
                'name' => $name,
                'hindi_name' => $hindi_name,
                'name_english' => !empty($name_english) ? $name_english : $name,
                'slug' => $slug,
                'pincode' => $pincode,
                'total_panchayats' => intval($total_panchayats)
            ]);
        }
    } catch (PDOException $e) {
        error_log("saveBlock error: " . $e->getMessage());
        return false;
    }
}

function deleteBlock($id) {
    $db = getDB();
    if (!$db || empty($id)) return false;
    try {
        $stmt = $db->prepare("DELETE FROM blocks WHERE id = :id");
        return $stmt->execute(['id' => intval($id)]);
    } catch (PDOException $e) {
        error_log("deleteBlock error: " . $e->getMessage());
        return false;
    }
}

function saveHalka($block, $halka_code, $halka_name, $halka_english = '', $mauja_code = '', $mauja_name = '', $mauja_english = '', $id = null) {
    $db = getDB();
    if (!$db || empty($block) || empty($halka_name)) return false;

    try {
        if (!empty($id)) {
            $stmt = $db->prepare("UPDATE halka SET block = :block, halka_code = :halka_code, halka_name = :halka_name, halka_english = :halka_english, mauja_code = :mauja_code, mauja_name = :mauja_name, mauja_english = :mauja_english WHERE id = :id");
            return $stmt->execute([
                'block' => $block,
                'halka_code' => $halka_code,
                'halka_name' => $halka_name,
                'halka_english' => $halka_english,
                'mauja_code' => $mauja_code,
                'mauja_name' => $mauja_name,
                'mauja_english' => $mauja_english,
                'id' => intval($id)
            ]);
        } else {
            $stmt = $db->prepare("INSERT INTO halka (block, halka_code, halka_name, halka_english, mauja_code, mauja_name, mauja_english) VALUES (:block, :halka_code, :halka_name, :halka_english, :mauja_code, :mauja_name, :mauja_english)");
            return $stmt->execute([
                'block' => $block,
                'halka_code' => $halka_code,
                'halka_name' => $halka_name,
                'halka_english' => $halka_english,
                'mauja_code' => $mauja_code,
                'mauja_name' => $mauja_name,
                'mauja_english' => $mauja_english
            ]);
        }
    } catch (PDOException $e) {
        error_log("saveHalka error: " . $e->getMessage());
        return false;
    }
}

function deleteHalka($id) {
    $db = getDB();
    if (!$db || empty($id)) return false;
    try {
        $stmt = $db->prepare("DELETE FROM halka WHERE id = :id");
        return $stmt->execute(['id' => intval($id)]);
    } catch (PDOException $e) {
        error_log("deleteHalka error: " . $e->getMessage());
        return false;
    }
}

function getGitExecutablePath() {
    $output = [];
    $return_var = 1;
    @exec("git --version 2>&1", $output, $return_var);
    if ($return_var === 0) {
        return "git";
    }

    $candidate_paths = [
        'C:\\Program Files\\Git\\cmd\\git.exe',
        'D:\\laragon\\bin\\git\\bin\\git.exe',
        'C:\\laragon\\bin\\git\\bin\\git.exe',
        'C:\\Program Files (x86)\\Git\\cmd\\git.exe'
    ];

    foreach ($candidate_paths as $path) {
        if (file_exists($path)) {
            return '"' . $path . '"';
        }
    }

    return "git";
}

function getGitUpdateStatus() {
    $repo_dir = dirname(__DIR__);
    $git_cmd = getGitExecutablePath();
    $info = [
        'is_git' => false,
        'branch' => 'main',
        'remote_url' => 'https://github.com/kgauravindia/saranindex',
        'current_commit' => 'Unknown',
        'commit_msg' => 'Unknown',
        'commit_date' => 'Unknown'
    ];

    if (is_dir($repo_dir . '/.git')) {
        $info['is_git'] = true;
        
        $output = [];
        @exec("cd /d " . escapeshellarg($repo_dir) . " && " . $git_cmd . " rev-parse --abbrev-ref HEAD 2>&1", $output);
        if (!empty($output[0])) {
            $info['branch'] = trim($output[0]);
        }

        $output = [];
        @exec("cd /d " . escapeshellarg($repo_dir) . " && " . $git_cmd . " config --get remote.origin.url 2>&1", $output);
        if (!empty($output[0])) {
            $info['remote_url'] = trim($output[0]);
        }

        $output = [];
        @exec("cd /d " . escapeshellarg($repo_dir) . " && " . $git_cmd . " rev-parse --short HEAD 2>&1", $output);
        if (!empty($output[0])) {
            $info['current_commit'] = trim($output[0]);
        }

        $output = [];
        @exec("cd /d " . escapeshellarg($repo_dir) . " && " . $git_cmd . " log -1 --format=%s 2>&1", $output);
        if (!empty($output[0])) {
            $info['commit_msg'] = trim(implode(' ', $output));
        }

        $output = [];
        @exec("cd /d " . escapeshellarg($repo_dir) . " && " . $git_cmd . " log -1 --format=%cd --date=relative 2>&1", $output);
        if (!empty($output[0])) {
            $info['commit_date'] = trim($output[0]);
        }
    }

    return $info;
}

function performGitPull() {
    $repo_dir = dirname(__DIR__);
    $git_cmd = getGitExecutablePath();
    $output = [];
    $return_var = 0;
    
    $cmd = "cd /d " . escapeshellarg($repo_dir) . " && " . $git_cmd . " config --global --add safe.directory " . escapeshellarg($repo_dir) . " 2>&1 && " . $git_cmd . " pull origin main 2>&1";
    
    @exec($cmd, $output, $return_var);
    
    $result_text = implode("\n", $output);
    
    $is_success = ($return_var === 0) || (stripos($result_text, 'Already up to date') !== false) || (stripos($result_text, 'Updating') !== false);

    if ($is_success) {
        if (function_exists('ensureAdminsTableExists')) {
            ensureAdminsTableExists();
        }
    }

    return [
        'success' => $is_success,
        'output' => !empty($result_text) ? $result_text : "Git pull executed. Result code: {$return_var}."
    ];
}










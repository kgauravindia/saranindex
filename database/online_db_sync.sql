-- ==============================================================================
-- SARAN INDEX: ONLINE PRODUCTION DATABASE SYNC SCRIPT (2026 UPDATE)
-- Run this in phpMyAdmin or MySQL CLI on your online cPanel/Hosting database
-- ==============================================================================

-- 1. Ensure `user_id` column exists on `listings` table
ALTER TABLE `listings` ADD COLUMN `user_id` INT DEFAULT NULL AFTER `id`;

-- 2. Create `claims` table if missing
CREATE TABLE IF NOT EXISTS `claims` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `listing_id` INT NOT NULL,
    `user_id` INT DEFAULT NULL,
    `claimant_name` VARCHAR(100) NOT NULL,
    `claimant_mobile` VARCHAR(20) NOT NULL,
    `role_title` VARCHAR(100) DEFAULT 'Owner / Manager',
    `verification_proof` TEXT,
    `status` ENUM('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    KEY `idx_claim_listing_id` (`listing_id`),
    KEY `idx_claim_user_id` (`user_id`),
    KEY `idx_claim_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Create `payments` table if missing
CREATE TABLE IF NOT EXISTS `payments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT DEFAULT NULL,
    `listing_id` INT DEFAULT NULL,
    `plan_type` ENUM('GOLD','PLATINUM') NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `payment_gateway` VARCHAR(50) DEFAULT 'RAZORPAY',
    `transaction_id` VARCHAR(100) NOT NULL,
    `payment_id` VARCHAR(100) DEFAULT NULL,
    `payment_status` ENUM('PENDING','SUCCESS','FAILED') DEFAULT 'PENDING',
    `payment_response` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `idx_txn_id` (`transaction_id`),
    KEY `idx_pay_user_id` (`user_id`),
    KEY `idx_pay_listing_id` (`listing_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Add Panchayat Samiti & Jila Parishad columns to `panchayats` table
ALTER TABLE `panchayats` 
ADD COLUMN `samiti_member_name` VARCHAR(150) NULL DEFAULT NULL AFTER `mukhiya_mobile_visibility`,
ADD COLUMN `samiti_member_mobile` VARCHAR(50) NULL DEFAULT NULL AFTER `samiti_member_name`,
ADD COLUMN `jila_parishad_name` VARCHAR(150) NULL DEFAULT NULL AFTER `samiti_member_mobile`,
ADD COLUMN `jila_parishad_mobile` VARCHAR(50) NULL DEFAULT NULL AFTER `jila_parishad_name`;

-- 5. Auto-link and heal all existing business listings with registered users by mobile number
UPDATE listings l 
JOIN users u ON (l.mobile = u.mobile OR RIGHT(REPLACE(REPLACE(REPLACE(l.mobile, ' ', ''), '-', ''), '+91', ''), 10) = RIGHT(REPLACE(REPLACE(REPLACE(u.mobile, ' ', ''), '-', ''), '+91', ''), 10))
SET l.user_id = u.id
WHERE (l.user_id IS NULL OR l.user_id = 0);

-- 6. Specifically ensure User #1 (Kumar Gaurav) listings are linked
UPDATE listings SET user_id = 1 WHERE mobile LIKE '%8102930609%' OR email = 'kmrgvr@gmail.com' OR slug = 'advocate-index';
UPDATE listings SET user_id = 1, is_verified = 'YES', is_featured = 'YES', plan_type = 'PLATINUM' WHERE slug = 'offerplant-technologies-private-limited' OR id = 1;

-- SaranIndex.com Schema & Seed Data
CREATE DATABASE IF NOT EXISTS `u305984835_saranindex` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `u305984835_saranindex`;

-- 1. Blocks Table
CREATE TABLE IF NOT EXISTS `blocks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `block_name` VARCHAR(100) NOT NULL,
    `hindi_name` VARCHAR(100),
    `slug` VARCHAR(100) UNIQUE,
    `pincode` VARCHAR(10),
    `total_panchayats` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Panchayats Table
CREATE TABLE IF NOT EXISTS `panchayats` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `block_id` INT NOT NULL,
    `panchayat_name` VARCHAR(100) NOT NULL,
    `hindi_name` VARCHAR(100),
    `slug` VARCHAR(100) UNIQUE,
    FOREIGN KEY (`block_id`) REFERENCES `blocks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Villages Table
CREATE TABLE IF NOT EXISTS `villages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `panchayat_id` INT NOT NULL,
    `village_name` VARCHAR(100) NOT NULL,
    `hindi_name` VARCHAR(100),
    `slug` VARCHAR(100) UNIQUE,
    `pincode` VARCHAR(10),
    FOREIGN KEY (`panchayat_id`) REFERENCES `panchayats`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Categories Table
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `hindi_name` VARCHAR(100),
    `icon` VARCHAR(100),
    `slug` VARCHAR(100) UNIQUE,
    `section` ENUM('BUSINESS','PROFESSIONAL','GOVT','EDUCATION','HEALTHCARE','EMERGENCY','BANK','HOTEL') DEFAULT 'BUSINESS',
    `status` ENUM('ACTIVE','INACTIVE') DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Subcategories Table
CREATE TABLE IF NOT EXISTS `subcategories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `hindi_name` VARCHAR(100),
    `slug` VARCHAR(100) UNIQUE,
    `keywords` TEXT,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Core Listings Table
CREATE TABLE IF NOT EXISTS `listings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `entity_type` ENUM('BUSINESS','PROFESSIONAL','GOVT_OFFICE','SCHOOL_COLLEGE','HEALTHCARE','EMERGENCY','BANK','HOTEL') DEFAULT 'BUSINESS',
    `category_id` INT NOT NULL,
    `subcategory_id` INT DEFAULT NULL,
    `block_id` INT DEFAULT NULL,
    `panchayat_id` INT DEFAULT NULL,
    `village_id` INT DEFAULT NULL,
    `title` VARCHAR(200) NOT NULL,
    `hindi_title` VARCHAR(200),
    `slug` VARCHAR(220) UNIQUE,
    `contact_person` VARCHAR(100),
    `mobile` VARCHAR(20) NOT NULL,
    `whatsapp` VARCHAR(20),
    `email` VARCHAR(100),
    `website` VARCHAR(150),
    `address` TEXT,
    `pincode` VARCHAR(10),
    `map_link` TEXT,
    `business_hours` VARCHAR(150) DEFAULT '9:00 AM - 8:00 PM',
    `services` TEXT,
    `description` TEXT,
    `cover_image` VARCHAR(255),
    `is_verified` ENUM('YES','NO') DEFAULT 'NO',
    `is_featured` ENUM('YES','NO') DEFAULT 'NO',
    `status` ENUM('ACTIVE','PENDING','REJECTED') DEFAULT 'ACTIVE',
    `view_count` INT DEFAULT 0,
    `star_rating` DECIMAL(3,2) DEFAULT 5.00,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`),
    FOREIGN KEY (`block_id`) REFERENCES `blocks`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Reviews Table
CREATE TABLE IF NOT EXISTS `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `listing_id` INT NOT NULL,
    `reviewer_name` VARCHAR(100) NOT NULL,
    `reviewer_mobile` VARCHAR(20),
    `rating` INT CHECK (rating BETWEEN 1 AND 5),
    `comment` TEXT,
    `status` ENUM('APPROVED','PENDING') DEFAULT 'APPROVED',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`listing_id`) REFERENCES `listings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Admins Table
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100),
    `role` ENUM('SUPER_ADMIN','MODERATOR') DEFAULT 'SUPER_ADMIN',
    `last_login` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Contact Messages Table
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `mobile` VARCHAR(20) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('UNREAD','READ','REPLIED') DEFAULT 'UNREAD',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. People Table
CREATE TABLE IF NOT EXISTS `people` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(120) NOT NULL,
    `hindi_name` VARCHAR(120) DEFAULT NULL,
    `slug` VARCHAR(150) UNIQUE,
    `designation` VARCHAR(100) DEFAULT NULL,
    `profession` VARCHAR(100) DEFAULT NULL,
    `mobile` VARCHAR(20) NOT NULL,
    `whatsapp` VARCHAR(20) DEFAULT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `block_id` INT DEFAULT NULL,
    `panchayat_id` INT DEFAULT NULL,
    `village_id` INT DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `pincode` VARCHAR(10) DEFAULT NULL,
    `photo` VARCHAR(255) DEFAULT NULL,
    `bio` TEXT DEFAULT NULL,
    `status` ENUM('ACTIVE','PENDING','INACTIVE') DEFAULT 'ACTIVE',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`block_id`) REFERENCES `blocks`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




-- SEED DATA --

-- Seed 20 Blocks of Saran District
INSERT INTO `blocks` (`id`, `block_name`, `hindi_name`, `slug`, `pincode`, `total_panchayats`) VALUES
(1, 'Chapra Sadar', 'छपरा सदर', 'chapra-sadar', '841301', 22),
(2, 'Marhaura', 'मढ़ौरा', 'marhaura', '841418', 18),
(3, 'Sonepur', 'सोनपुर', 'sonepur', '841101', 23),
(4, 'Revelganj', 'रिविलगंज', 'revelganj', '841305', 14),
(5, 'Garkha', 'गरखा', 'garkha', '841311', 20),
(6, 'Parsa', 'परसा', 'parsa', '841219', 16),
(7, 'Dighwara', 'दिघवारा', 'dighwara', '841207', 12),
(8, 'Amanour', 'अमनौर', 'amanour', '841401', 18),
(9, 'Baniapur', 'बनियापुर', 'baniapur', '841403', 24),
(10, 'Ekma', 'एकमा', 'ekma', '841208', 19),
(11, 'Taraiya', 'तरैया', 'taraiya', '841424', 13),
(12, 'Isuapur', 'इसुआपुर', 'isuapur', '841407', 11),
(13, 'Lahladpur', 'लहलादपुर', 'lahladpur', '841408', 9),
(14, 'Manjhi', 'मांझी', 'manjhi', '841313', 24),
(15, 'Maker', 'मेकर', 'maker', '841215', 10),
(16, 'Dariapur', 'दरियापुर', 'dariapur', '841221', 21),
(17, 'Jalalpur', 'जलालपुर', 'jalalpur', '841412', 15),
(18, 'Nagra', 'नगरा', 'nagra', '841442', 12),
(19, 'Mashrakh', 'मशरख', 'mashrakh', '841417', 16),
(20, 'Panapur', 'पन्नापुर', 'panapur', '841410', 11)
ON DUPLICATE KEY UPDATE `block_name` = VALUES(`block_name`);

-- Seed Panchayats for Chapra Sadar
INSERT INTO `panchayats` (`id`, `block_id`, `panchayat_name`, `hindi_name`, `slug`) VALUES
(1, 1, 'Dahiyawan', 'दहियावां', 'dahiyawan'),
(2, 1, 'Sahebganj', 'साहिबगंज', 'sahebganj'),
(3, 1, 'Bhagwan Bazar', 'भगवान बाजार', 'bhagwan-bazar'),
(4, 1, 'Mouna', 'मौना', 'mouna'),
(5, 2, 'Marhaura North', 'मढ़ौरा उत्तर', 'marhaura-north'),
(6, 3, 'Govindpur Sonepur', 'गोविंदपुर सोनपुर', 'govindpur-sonepur')
ON DUPLICATE KEY UPDATE `panchayat_name` = VALUES(`panchayat_name`);

-- Seed Categories (Core Verticals)
INSERT INTO `categories` (`id`, `name`, `hindi_name`, `icon`, `slug`, `section`) VALUES
(1, 'Schools & Education', 'स्कूल एवं शिक्षा', 'bi-mortarboard', 'schools-education', 'EDUCATION'),
(2, 'Doctors & Healthcare', 'डॉक्टर एवं अस्पताल', 'bi-hospital', 'doctors-healthcare', 'HEALTHCARE'),
(3, 'Advocates & Legal', 'वकील एवं कानूनी सेवाएं', 'bi-journal-text', 'advocates-legal', 'PROFESSIONAL'),
(4, 'Government Offices', 'सरकारी कार्यालय', 'bi-building', 'government-offices', 'GOVT'),
(5, 'Businesses & Retail Stores', 'व्यापार एवं दुकानें', 'bi-shop', 'businesses-retail-stores', 'BUSINESS'),
(6, 'Banks & Finance', 'बैंक एवं एटीएम', 'bi-bank', 'banks-finance', 'BANK'),
(7, 'Emergency Services', 'आपातकालीन सेवाएं', 'bi-telephone-outbound', 'emergency-services', 'EMERGENCY'),
(8, 'Hotels & Restaurants', 'होटल एवं रेस्टोरेंट', 'bi-cup-hot', 'hotels-restaurants', 'HOTEL'),
(9, 'Transport & Logistics', 'परिवहन एवं यात्रा', 'bi-truck', 'transport-logistics', 'BUSINESS'),
(10, 'Local Services & Repairs', 'स्थानीय सेवाएं एवं मरम्मत', 'bi-tools', 'local-services-repairs', 'BUSINESS'),
(11, 'Agriculture & Dairy', 'कृषि एवं पशुपालन', 'bi-flower1', 'agriculture-dairy', 'BUSINESS'),
(12, 'Real Estate & Properties', 'प्रॉपर्टी एवं मकान', 'bi-house-check', 'real-estate-properties', 'BUSINESS'),
(13, 'Media & Entertainment', 'मीडिया एवं मनोरंजन', 'bi-newspaper', 'media-entertainment', 'BUSINESS')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Seed Subcategories
INSERT INTO `subcategories` (`id`, `category_id`, `name`, `hindi_name`, `slug`, `keywords`) VALUES
(1, 1, 'University', 'विश्वविद्यालय', 'university', 'university, jp university, chapra university, higher education'),
(2, 1, 'Degree Colleges', 'डिग्री कॉलेज', 'degree-colleges', 'degree college, ba, bsc, bcom, graduation, pg college, rajendra college, jagdam college'),
(3, 1, 'Inter Colleges & +2 Schools', 'इंटर कॉलेज एवं +2 विद्यालय', 'inter-colleges-plus-two', 'inter college, +2 school, intermediate, 11th 12th science arts commerce'),
(4, 1, 'High Schools & Secondary Schools', 'हाई स्कूल एवं माध्यमिक विद्यालय', 'high-schools', 'high school, matric, 9th 10th, zila school, saran high school'),
(5, 1, 'Middle Schools', 'मध्य विद्यालय', 'middle-schools', 'middle school, class 6 to 8, government middle school'),
(6, 1, 'Primary Schools', 'प्राथमिक विद्यालय', 'primary-schools', 'primary school, prathmik vidyalaya, class 1 to 5'),
(7, 1, 'Polytechnic & Engineering Colleges', 'पॉलिटेक्निक एवं इंजीनियरिंग कॉलेज', 'polytechnic-engineering', 'polytechnic, engineering college, diploma, govt polytechnic chapra, marhaura'),
(8, 1, 'ITI & Skill Training Institutes', 'आईटीआई एवं कौशल विकास केंद्र', 'iti-skill-training', 'iti, industrial training, fitter, electrician, skill development, kushal yuva program'),
(9, 1, 'B.Ed & Teachers Training Colleges', 'बी.एड. एवं शिक्षक प्रशिक्षण कॉलेज', 'bed-teachers-training', 'bed college, dled, teachers training, bted'),
(10, 1, 'Kendriya Vidyalaya (KV)', 'केंद्रीय विद्यालय (केवी)', 'kendriya-vidyalaya', 'kendriya vidyalaya, kv chapra, cbse central school'),
(11, 1, 'Jawahar Navodaya Vidyalaya (JNV)', 'जवाहर नवोदय विद्यालय', 'navodaya-vidyalaya', 'navodaya vidyalaya, jnv saran, residential central school'),
(12, 1, 'Private Schools (CBSE / ICSE / Board)', 'निजी विद्यालय (CBSE/ICSE)', 'private-schools', 'private school, cbse school, icse school, english medium, convent'),
(13, 1, 'Coaching Institutes & Tuition Centers', 'कोचिंग संस्थान एवं ट्यूशन', 'coaching-institutes', 'coaching, tuition, iit jee, neet, ssc, railway, bpsc, competitive exam coaching'),
(14, 1, 'Day Boarding & Residential Schools', 'डे बोर्डिंग एवं आवासीय स्कूल', 'day-boarding-residential-schools', 'day boarding, hostel school, residential school, hostel facility'),
(15, 1, 'Computer & IT Institutes', 'कंप्यूटर एवं आईटी संस्थान', 'computer-it-institutes', 'computer coaching, dca, adca, tally, coding, o level, kyps'),
(16, 1, 'Madrasa & Religious Schools', 'मदरसा एवं धार्मिक शिक्षण संस्थान', 'madrasa-religious-schools', 'madrasa, islamic education, religious school, urdu academy'),
(17, 1, 'Convent & Christian Mission Schools', 'कॉन्वेंट एवं मिशनरी स्कूल', 'christian-mission-schools', 'convent, st joseph, missionary school, christian school'),
(18, 1, 'Gurukul & Traditional Schools', 'गुरुकुल एवं सनातन विद्यालय', 'gurukul-traditional-schools', 'gurukul, vedic school, hindu school, sanskrit vidyalaya'),
(19, 1, 'Special Needs & Inclusive Schools', 'विशेष आवश्यकता वाले विद्यालय', 'special-needs-schools', 'special school, blind deaf mute school, inclusive education'),
(20, 1, 'Public Libraries & Study Rooms', 'सार्वजनिक पुस्तकालय एवं स्टडी रूम', 'public-libraries-study-rooms', 'library, pustakalaya, self study hall, reading room'),
(21, 2, 'Sadar Hospital & Govt Health Centers', 'सदर अस्पताल एवं पीएचसी', 'sadar-hospital-govt-health', 'sadar hospital, phc, chc, sub divisional hospital, govt hospital chapra'),
(22, 2, 'Multi-Specialty & Private Hospitals', 'मल्टी-स्पेशलिटी एवं प्राइवेट अस्पताल', 'private-hospitals', 'private hospital, multi specialty, icu, trauma center, surgical hospital'),
(23, 2, 'Nursing Homes & Maternity Clinics', 'नर्सिंग होम एवं प्रसूति गृह', 'nursing-homes-maternity', 'nursing home, maternity clinic, gynaecologist, delivery hospital, child care'),
(24, 2, 'Specialist Doctors & OPD Clinics', 'विशेषज्ञ डॉक्टर एवं क्लीनिक', 'specialist-doctors-clinics', 'doctor, physician, surgeon, paediatrician, cardiologist, dermatologist, opd'),
(25, 2, 'Pathology & Diagnostic Centers', 'पैथोलॉजी एवं जांच केंद्र', 'pathology-diagnostic-centers', 'pathology, blood test, x-ray, ultrasound, mri, ct scan, lab'),
(26, 2, 'Pharmacies & Medical Stores', 'दवा की दुकानें / मेडिकल स्टोर', 'pharmacies-medical-stores', 'medical store, pharmacy, dawa dukan, chemist, 24x7 medicine'),
(27, 2, 'Eye Hospitals & Opticians', 'नेत्र अस्पताल एवं चश्मे की दुकान', 'eye-hospitals-opticians', 'eye hospital, ophthalmologist, cataract surgery, optical shop, specs'),
(28, 2, 'Dental Clinics & Dentists', 'दंत चिकित्सालय एवं डेंटिस्ट', 'dental-clinics', 'dental clinic, dentist, root canal, teeth cleaning, rct'),
(29, 2, 'Homeopathy & Ayurvedic Clinics', 'होम्योपैथी एवं आयुर्वेदिक चिकित्सा', 'homeopathy-ayurvedic', 'homeopathy, ayurveda, herbal medicine, patanjali clinic, vaidya'),
(30, 2, 'Blood Banks & Dialysis Centers', 'ब्लड बैंक एवं डायलिसिस सेंटर', 'blood-banks-dialysis', 'blood bank, blood donation, kidney dialysis center'),
(31, 2, 'Ambulance Services (24x7)', 'एंबुलेंस सेवाएं (24x7)', 'ambulance-services-healthcare', 'ambulance, icu ambulance, 102 ambulance, emergency transport'),
(32, 2, 'Veterinary Hospitals & Animal Care', 'पशु अस्पताल एवं पशु चिकित्सा', 'veterinary-animal-care', 'veterinary hospital, pashu hospital, animal doctor, cattle care'),
(33, 3, 'Civil Court Advocates', 'सिविल कोर्ट वकील', 'civil-court-advocates', 'civil advocate, land lawyer, property dispute, civil court chapra'),
(34, 3, 'Criminal Lawyers & Bail Specialists', 'क्रिमिनल वकील एवं जमानत विशेषज्ञ', 'criminal-lawyers-bail', 'criminal advocate, bail lawyer, fir, police case lawyer, session court'),
(35, 3, 'Revenue & Land Dispute Lawyers', 'राजस्व एवं भूमि विवाद वकील', 'revenue-land-lawyers', 'revenue lawyer, dclr, sdo court lawyer, mutation dispute, land registry'),
(36, 3, 'Family & Matrimonial Advocates', 'पारिवारिक एवं वैवाहिक विवाद वकील', 'family-matrimonial-advocates', 'family court, divorce lawyer, maintenance, matrimonial dispute advocate'),
(37, 3, 'High Court & Session Court Advocates', 'सेशन कोर्ट एवं हाईकोर्ट वकील', 'high-court-session-advocates', 'high court advocate, senior lawyer, writ petition, session judge court'),
(38, 3, 'Tax, GST & Corporate Consultants', 'टैक्स एवं जीएसटी सलाहकार', 'tax-gst-corporate-consultants', 'tax consultant, gst advocate, income tax lawyer, company registration'),
(39, 3, 'Notary Public & Document Writers', 'नोटरी पब्लिक एवं कातिब / दस्तावेज लेखक', 'notary-public-document-writers', 'notary, affidavit, stamp paper, katib, document writer, court agreement'),
(40, 3, 'Legal Aid & Consumer Protection', 'कानूनी सहायता एवं उपभोक्ता फोरम', 'legal-aid-consumer-protection', 'legal aid, free legal help, consumer forum court lawyer, dlsa'),
(41, 4, 'District Magistrate & Collectorate', 'जिलाधिकारी / समाहरणालय', 'dm-collectorate', 'dm office, collectorate chapra, saran magistrate, samaharnalaya'),
(42, 4, 'SDO & BDO Block Offices', 'अनुमंडल (SDO) एवं प्रखंड (BDO) कार्यालय', 'sdo-bdo-block-offices', 'sdo office, bdo office, block office, prakhand karyalaya, circle officer co'),
(43, 4, 'Police Stations (Thana) & Outposts', 'पुलिस थाना एवं ओपी', 'police-stations-thana', 'police station, thana, op, sho, sp office, police control room'),
(44, 4, 'Courts & Judicial Offices', 'सिविल कोर्ट एवं न्यायिक कार्यालय', 'courts-judicial-offices', 'civil court, district judge court, munsif court, gram katchahry'),
(45, 4, 'Municipal Corporation & Nagar Parishad', 'नगर निगम एवं नगर परिषद', 'municipal-corporation-nagar-parishad', 'nagar nigam chapra, nagar parishad marhaura, sonepur, holding tax, ward councillor'),
(46, 4, 'Panchayat Raj & Village Offices', 'पंचायत राज एवं ग्राम विकास', 'panchayat-raj-offices', 'panchayat bhawan, mukhiya, sarpanch, panchayat secretary, vlw'),
(47, 4, 'Electricity Board (BSPHCL) & Power Stations', 'विद्युत बोर्ड एवं सब-स्टेशन', 'electricity-board-bsphcl', 'electricity board, bsphcl, bijli vibhag, power sub station, electric complaint'),
(48, 4, 'Water Supply & PHED Division', 'लोक स्वास्थ्य प्रमंडल (PHED)', 'water-supply-phed', 'phed, water supply, nal jal yojana, handpump repair, public health engineering'),
(49, 4, 'Post Offices & Speed Post', 'डाकघर एवं स्पीड पोस्ट', 'post-offices-speedpost', 'post office, head post office chapra, speed post, registry, dakghar'),
(50, 4, 'Transport Office (RTO / DTO)', 'जिला परिवहन कार्यालय (DTO)', 'transport-office-dto', 'dto office, rto chapra, driving license, vehicle registration, rc transfer'),
(51, 4, 'Land Registry & Stamp Office', 'निबंधन एवं भूमि रजिस्ट्री कार्यालय', 'land-registry-stamp-office', 'registry office, nibandhan office, land registration, deed writer, stamp office'),
(52, 4, 'Treasury & Commercial Tax Office', 'कोषागार एवं आयकर/जीएसटी कार्यालय', 'treasury-commercial-tax', 'treasury office, commercial tax, gst office chapra, income tax bhawan'),
(53, 4, 'Krishi Bhawan & Agriculture Department', 'कृषि भवन एवं उद्यान विभाग', 'krishi-bhawan-agriculture-dept', 'krishi bhawan, district agriculture officer, dao, kisan helpline, pm kisan'),
(54, 5, 'Kirana, Grocery & Supermarkets', 'किराना दुकान एवं सुपरमार्केट', 'grocery-supermarkets', 'kirana dukan, grocery store, ration shop, supermarket, daily essentials'),
(55, 5, 'Readymade Garments & Clothing', 'कपड़ा दुकान एवं रेडीमेड शो-रूम', 'garments-clothing', 'clothing store, saree showroom, mens wear, kids wear, readymade garments'),
(56, 5, 'Electronics, Mobiles & Computers', 'इलेक्ट्रॉनिक्स, मोबाइल एवं कंप्यूटर', 'electronics-mobiles-computers', 'mobile shop, laptop store, tv fridge ac, electronics showroom, smartphone'),
(57, 5, 'Jewelry & Gold Ornament Shops', 'आभूषण एवं ज्वैलरी शॉप', 'jewelry-gold-shops', 'jeweller, gold shop, silver ornaments, jewellers chapra, hallmark jewelry'),
(58, 5, 'Footwear & Leather Showrooms', 'जूते-चप्पल एवं लेदर शो-रूम', 'footwear-leather-stores', 'shoe shop, footwear, bata, relaxo, leather shoes, sandals'),
(59, 5, 'Furniture & Home Furnishing', 'फर्नीचर एवं होम डेकोर', 'furniture-home-furnishing', 'furniture shop, wooden bed, sofa set, almirah, home decor, mattress'),
(60, 5, 'Building Materials, Cement & Hardware', 'सीमेंट, छड़, हार्डवेयर व सेनेटरी', 'building-materials-hardware', 'hardware store, cement dealer, sariya steel, sanitaryware, paint shop'),
(61, 5, 'Automobile & Two-Wheeler Showrooms', 'ऑटोमोबाइल एवं बाइक शो-रूम', 'automobile-bike-showrooms', 'hero showroom, honda, tvs, bajaj, car dealership, two wheeler dealer'),
(62, 5, 'Tractor & Agri Machinery Outlets', 'ट्रैक्टर एवं कृषि यंत्र डीलर', 'tractor-agri-machinery-dealers', 'mahindra tractor, sonalika, john deere, thresher, rotavator, agri machinery'),
(63, 5, 'Books, Stationeries & Printing', 'किताबें, स्टेशनरी एवं प्रिंटिंग प्रेस', 'books-stationery-printing', 'book store, stationary, school books, flex printing, offset press, marriage card'),
(64, 5, 'Utensils & Kitchenware Stores', 'बर्तन एवं किचनवेयर दुकानें', 'utensils-kitchenware', 'utensil shop, bartan dukan, pressure cooker, mixer grinder, stainless steel'),
(65, 5, 'Cosmetics, Beauty & General Stores', 'कॉस्मेटिक्स एवं जनरल स्टोर', 'cosmetics-general-stores', 'cosmetic shop, beauty products, general store, gift shop'),
(66, 6, 'Public Sector & Nationalized Banks', 'राष्ट्रीयकृत एवं सरकारी बैंक', 'public-sector-banks', 'sbi, pnb, bank of baroda, canara bank, central bank of india, govt bank'),
(67, 6, 'Private Sector Banks', 'निजी बैंक', 'private-sector-banks', 'hdfc bank, icici bank, axis bank, kotak mahindra, bandhan bank'),
(68, 6, 'Regional Rural Banks (Gramin Bank)', 'दक्षिण बिहार ग्रामीण बैंक', 'gramin-rural-banks', 'dakshin bihar gramin bank, dbgb, rural bank branch'),
(69, 6, 'Cooperative Banks & Societies', 'सहकारी बैंक एवं सोसायटियां', 'cooperative-banks-societies', 'cooperative bank, central cooperative bank, pacs society'),
(70, 6, 'ATMs & Cash Recyclers', 'एटीएम एवं कैश मशीनें', 'atms-cash-recyclers', 'atm, cdm, cash deposit, 24 hour atm'),
(71, 6, 'Microfinance & Nidhi Companies', 'माइक्रोफाइनेंस एवं निधि कंपनियां', 'microfinance-nidhi', 'microfinance, gold loan, nidhi company, self help group loan'),
(72, 6, 'LIC & Insurance Agencies', 'एलआईसी एवं बीमा एजेंसियां', 'lic-insurance-agencies', 'lic office, life insurance, motor insurance, health insurance agent'),
(73, 6, 'Chartered Accountants & Tax Consultants', 'चार्टर्ड अकाउंटेंट (CA) व टैक्स एडवाइजर', 'ca-tax-consultants', 'ca, chartered accountant, audit, income tax filing, gst return'),
(74, 6, 'CSP & Digital Banking Outlets', 'सीएसपी पॉइंट एवं डिजिटल बैंकिंग', 'csp-digital-banking', 'csp point, customer service point, aeps, money transfer, mini bank'),
(75, 7, 'District Emergency Control Room (112)', 'जिला आपातकालीन कंट्रोल रूम', 'emergency-control-room', 'emergency 112, helpline, district control room, sp helpline'),
(76, 7, 'Police Emergency Patrol', 'पुलिस आपातकालीन गश्ती', 'police-emergency-patrol', 'dial 112, police patrol, quick response team, qrt'),
(77, 7, 'Fire Brigade Stations', 'अग्निशमन केंद्र / फायर ब्रिगेड', 'fire-brigade-stations', 'fire station, fire brigade, agnishaman, fire emergency 101'),
(78, 7, 'Disaster Management & Relief', 'आपदा प्रबंधन एवं बाढ़ राहत', 'disaster-management-relief', 'disaster management, flood helpline, ndrf, sdrf, rescue team'),
(79, 7, 'Women Helpline (1090 / 181)', 'महिला हेल्पलाइन एवं सुरक्षा केंद्र', 'women-helpline-safety', 'women helpline, 1090, 181, mahila thana, women safety'),
(80, 7, 'Childline (1098)', 'चाइल्डलाइन 1098', 'childline-1098', 'childline 1098, child protection, lost child helpline'),
(81, 7, 'Blood Emergency & Ambulance', 'ब्लड एवं एंबुलेंस आपातकालीन सेवा', 'blood-ambulance-emergency', 'blood helpline, emergency blood donor, red cross, 102 ambulance'),
(82, 8, 'Hotels, Lodges & Guest Houses', 'होटल, लॉज एवं गेस्ट हाउस', 'hotels-lodges-guesthouses', 'hotel, lodge, ac room, guest house, lodging chapra'),
(83, 8, 'Family Restaurants & Dining', 'फैमिली रेस्टोरेंट एवं भोजनशाला', 'family-restaurants-dining', 'restaurant, family dining, veg nonveg food, thali, dhaba'),
(84, 8, 'Marriage Lawns, Banquets & Halls', 'विवाह भवन, बैंक्वेट एवं मैरिज हॉल', 'marriage-lawns-banquets', 'marriage hall, vivah bhawan, banquet lawn, wedding venue'),
(85, 8, 'Sweet Shops & Halwai Services', 'मिठाई दुकानें एवं हलवाई', 'sweet-shops-halwai', 'sweet shop, mithai dukan, halwai, rasgulla, peda, gulab jamun'),
(86, 8, 'Cafes, Bakers & Fast Food', 'कैफे, बेकरी एवं फास्ट फूड', 'cafes-bakers-fastfood', 'cafe, bakery, cake shop, pizza, burger, fast food corner'),
(87, 8, 'Tent House, Light & DJ Sound', 'टेंट हाउस, लाइट एवं डीजे साउंड', 'tent-house-dj-sound', 'tent house, dj sound, light decoration, wedding planner, mandap'),
(88, 8, 'Catering & Food Event Services', 'केटरिंग एवं कैटरर्स सेवा', 'catering-food-services', 'caterers, catering service, wedding buffet, party food'),
(89, 9, 'Railway Stations & Inquiry', 'रेलवे स्टेशन एवं पूछताछ', 'railway-stations-inquiry', 'chapra junction, sonepur station, train inquiry, reservation counter'),
(90, 9, 'Govt & Private Bus Stands', 'सरकारी एवं निजी बस स्टैंड', 'bus-stands-routes', 'bus stand chapra, bsrtc bus, private bus service, patna bus'),
(91, 9, 'Auto Stand & E-Rickshaw Routes', 'ऑटो स्टैंड एवं ई-रिक्शा', 'auto-e-rickshaw-stands', 'auto stand, toto, e rickshaw, shared auto service'),
(92, 9, 'Goods Transport & Freight', 'गुड्स ट्रांसपोर्ट एवं ट्रक बुकिंग', 'goods-transport-freight', 'transport company, truck booking, goods carrier, loading vehicle'),
(93, 9, 'Courier & Express Parcel Delivery', 'कूरियर एवं पार्सल सेवाएं', 'courier-express-parcel', 'courier, dtcd, blue dart, delhivery, ekart, parcel service'),
(94, 9, 'Taxi, Car Rental & Travel Agencies', 'टैक्सी, कार रेंटल एवं ट्रैवल एजेंसी', 'taxi-car-rental-travel', 'taxi service, car rental, tempo traveller, tour package, airport cab'),
(95, 9, 'Petrol Pumps & Fuel Outlets', 'पेट्रोल पंप एवं ईंधन स्टेशन', 'petrol-pumps-fuel', 'petrol pump, indian oil, bharat petroleum, hp, cng station'),
(96, 9, 'Packers & Movers Services', 'पैकिंग एवं मूवर्स सेवाएं', 'packers-movers-services', 'packers movers, house shifting, office shifting, luggage transport'),
(97, 10, 'Electrician & House Wiring', 'इलेक्ट्रीशियन एवं हाउस वायरिंग', 'electrician-house-wiring', 'electrician, inverter repair, house wiring, electric fitting'),
(98, 10, 'Plumber & Pipe Fitters', 'प्लंबर एवं पाइप फिटिंग', 'plumber-pipe-fitters', 'plumber, pipe fitting, water tank installation, sanitary repair'),
(99, 10, 'Carpenter & Woodwork', 'कारपेंटर एवं वुडवर्क', 'carpenter-woodwork', 'carpenter, furniture maker, wooden door repair, interior carpenter'),
(100, 10, 'AC, Fridge & Home Appliance Repair', 'एसी, फ्रिज व रिपेयरिंग', 'ac-fridge-appliance-repair', 'ac repair, fridge mechanic, washing machine repair, geyser service'),
(101, 10, 'Mobile, Laptop & Computer Repair', 'मोबाइल एवं लैपटॉप रिपेयरिंग', 'mobile-laptop-repair', 'mobile repair, screen replacement, laptop servicing, software flashing'),
(102, 10, 'Motor Mechanic & Vehicle Garage', 'मोटर मैकेनिक एवं गैराज', 'motor-mechanic-garage', 'car mechanic, bike repair shop, automobile garage, denting painting'),
(103, 10, 'House Painters & Wall Decorators', 'पेंटर एवं पीओपी डेकोरेटर', 'house-painters-decorators', 'house painter, wall painting, asian paints contractor, pop false ceiling'),
(104, 10, 'CCTV Camera & Security Installation', 'सीसीटीवी कैमरा एवं सिक्योरिटी सिस्टम', 'cctv-security-installation', 'cctv camera, Security camera installation, biometric attendance'),
(105, 10, 'Salons, Beauty Parlours & Spas', 'सलून, ब्यूटी पार्लर एवं स्पा', 'salons-beauty-parlours', 'beauty parlour, mens salon, haircutting, bridal makeup, facial spa'),
(106, 11, 'Krishi Seva Kendra, Seeds & Fertilizer', 'कृषि सेवा केंद्र, बीज व खाद दुकान', 'krishi-seva-kendra-seeds', 'krishi seva kendra, khad beej dukan, fertilizer shop, pesticides, seeds'),
(107, 11, 'Cold Storages & Grain Warehouses', 'कोल्ड स्टोरेज एवं अनाज गोदाम', 'cold-storages-warehouses', 'cold storage, potato storage, grain warehouse, aalu godam'),
(108, 11, 'Dairy Farms & Milk Collection Centers', 'डेयरी फार्म एवं दुग्ध संग्रह केंद्र', 'dairy-farms-milk-centers', 'dairy farm, sudha milk booth, milk collection center, pashupalan'),
(109, 11, 'Poultry Farms, Hatcheries & Feed', 'पोल्ट्री फार्म एवं पशु आहार', 'poultry-farms-animal-feed', 'poultry farm, poultry feed, cattle feed, fish feed, murgi farm'),
(110, 11, 'Farm Machinery Rental (Harvester/Thresher)', 'कृषि यंत्र किराया (हार्वेस्टर/थ्रेशर)', 'farm-machinery-rental', 'harvester rental, thresher, tractor hire, rotavator, irrigation pump'),
(111, 11, 'Soil Testing & Bio-Fertilizer Labs', 'मृदा परीक्षण एवं जैविक खाद', 'soil-testing-bio-fertilizer', 'soil testing lab, organic farming, bio fertilizer, vermicompost'),
(112, 12, 'Property Brokers & Real Estate Agents', 'प्रॉपर्टी ब्रोकर एवं एजेंट', 'property-brokers-agents', 'property dealer, real estate broker, land agent chapra, plot broker'),
(113, 12, 'Residential Plots & Housing Schemes', 'रेजिडेंशियल प्लॉट एवं कॉलोनी', 'residential-plots-housing', 'residential plot, land for sale, housing colony, township'),
(114, 12, 'Houses & Flats for Rent', 'किराये का मकान, फ्लैट एवं रूम', 'houses-flats-for-rent', 'house for rent, flat on rent, 2bhk 3bhk, room for students, pg'),
(115, 12, 'Commercial Shops & Offices for Rent', 'किराये की दुकान एवं ऑफिस', 'commercial-shops-offices-rent', 'shop for rent, commercial space, office space on hire'),
(116, 12, 'Architects & Civil Engineers', 'वास्तुकार एवं सिविल इंजीनियर', 'architects-civil-engineers', 'architect, building plan, house map design, civil engineer, naksha'),
(117, 12, 'Interior Designers & Decorators', 'इंटीरियर डिजाइनर एवं डेकोर', 'interior-designers-decorators', 'interior designer, modular kitchen, home interior, false ceiling'),
(118, 13, 'Local News Channels & Portals', 'स्थानीय समाचार चैनल एवं डिजिटल न्यूज', 'local-news-portals', 'chapra news, saran news portal, local news channel, Youtube news'),
(119, 13, 'Daily Newspaper Offices & Journalists', 'दैनिक समाचार पत्र एवं पत्रकार', 'daily-newspapers-journalists', 'dainik jagran chapra, prabhat khabar, hindustan, amar ujala bureau office'),
(120, 13, 'Cinema Halls & Multiplexes', 'सिनेमा हॉल एवं टॉकीज', 'cinema-halls-multiplexes', 'cinema hall, talkies, movie theater, multiplex chapra'),
(121, 13, 'Parks, Eco-Tourism & Historical Sites', 'पार्क, पर्यटन एवं ऐतिहासिक स्थल', 'parks-tourism-sites', 'tourist spot, aami mandir, chirand, sonepur mela, public park')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Seed Initial Verified Listings
INSERT INTO `listings` (`id`, `entity_type`, `category_id`, `subcategory_id`, `block_id`, `title`, `hindi_title`, `slug`, `contact_person`, `mobile`, `whatsapp`, `email`, `website`, `address`, `pincode`, `map_link`, `services`, `description`, `is_verified`, `is_featured`, `status`, `star_rating`) VALUES
(1, 'EMERGENCY', 7, 76, 1, 'Town Police Station (Thana), Chapra', 'नगर थाना, छपरा', 'town-police-station-chapra', 'SHO Chapra Town', '06152-243202', '9431822401', 'sho-chapratown-bih@nic.in', 'https://saran.bihar.gov.in', 'Near Thanachowk, Main Road, Chapra, Saran', '841301', 'https://maps.google.com/?q=Chapra+Town+Police+Station', '24/7 Police Help, Crime Reporting, FIR Filing, Emergency Helpline 112', 'Official Town Police Station providing 24/7 safety and public law enforcement in Chapra Sadar.', 'YES', 'YES', 'ACTIVE', 5.00),

(2, 'HEALTHCARE', 2, 21, 1, 'Saran District Hospital (Sadar Hospital Chapra)', 'सदर अस्पताल, छपरा', 'sadar-hospital-chapra', 'Civil Surgeon Saran', '06152-243405', '9470003200', 'cs-saran-bih@nic.in', 'https://saran.bihar.gov.in', 'Hospital Road, Near Municipal Chowk, Chapra', '841301', 'https://maps.google.com/?q=Sadar+Hospital+Chapra', '24/7 Emergency Care, OPD, ICU, Ambulance, Pathology Lab, Blood Bank', 'The premier government district healthcare facility in Saran, equipping specialized doctors and 24x7 emergency medical services.', 'YES', 'YES', 'ACTIVE', 4.80),

(3, 'PROFESSIONAL', 3, 33, 1, 'District Bar Association, Saran (Chapra Court Advocates Hub)', 'जिला बार एसोसिएशन, सरण (छपरा)', 'district-bar-association-saran', 'President / General Secretary', '06152-242100', '9431426600', 'contact@advocateindex.com', 'https://advocateindex.com/BRSARA', 'District & Sessions Court Premises, Chapra, Saran', '841301', 'https://maps.google.com/?q=Chapra+District+Court', 'Legal Advice, Court Representation, Bail Applications, Legal Documentation, Notary', 'Official District Bar Association Chapra representing legal practitioners in Saran district.', 'YES', 'YES', 'ACTIVE', 4.90),

(4, 'GOVT_OFFICE', 4, 41, 1, 'District Collectorate Office (DM Office Saran)', 'जिलाधिकारी कार्यालय (समाहरणालय छपरा)', 'dm-office-saran-chapra', 'District Magistrate Saran', '06152-245001', '9473191238', 'dm-saran.bih@nic.in', 'https://saran.bihar.gov.in', 'Collectorate Campus, Katchahry Chowk, Chapra, Saran', '841301', 'https://maps.google.com/?q=Collectorate+Office+Chapra', 'Public Grievances, Revenue Administration, Land Records, District Licensing, Government Welfare Schemes', 'Apex administrative headquarters for Saran District located in Chapra city.', 'YES', 'YES', 'ACTIVE', 5.00)
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- Seed Initial People Directory Entries
INSERT INTO `people` (`id`, `full_name`, `hindi_name`, `slug`, `designation`, `profession`, `mobile`, `whatsapp`, `email`, `block_id`, `address`, `pincode`, `bio`, `status`) VALUES
(1, 'Aman Kumar', 'अमन कुमार', 'aman-kumar', 'District Magistrate', 'Government Official', '06152-245001', '9473191238', 'dm-saran.bih@nic.in', 1, 'Collectorate Campus, Chapra', '841301', 'District Magistrate & Collector, Saran (Chapra).', 'ACTIVE'),
(2, 'Dr. Rajiv Ranjan', 'डॉ. राजीव रंजन', 'dr-rajiv-ranjan', 'Senior Surgeon', 'Doctor', '9470003200', '9470003200', 'dr.rajiv@saranindex.com', 1, 'Hospital Road, Chapra', '841301', 'Leading general surgeon at Sadar Hospital Chapra.', 'ACTIVE'),
(3, 'Adv. Vijay Sharma', 'एडवोकेट विजय शर्मा', 'adv-vijay-sharma', 'Senior Advocate', 'Lawyer', '9431426600', '9431426600', 'vijay.legal@gmail.com', 1, 'Civil Court Premises, Chapra', '841301', 'Civil & criminal law advocate with 20+ years practice in Chapra Court.', 'ACTIVE')
ON DUPLICATE KEY UPDATE `full_name` = VALUES(`full_name`);


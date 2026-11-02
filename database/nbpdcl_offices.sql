-- =========================================================================
-- Saran District NBPDCL Electricity Supply Directory (North Bihar Power Distribution Company Ltd)
-- Official Source: https://nbpdcl.co.in/ | Subcategory ID: 272 | Source ID: 23
-- All mobiles masked: mobile_visibility = 'REGISTERED'
-- =========================================================================

-- 1. Subcategory #272 under Category 11 (Government)
INSERT INTO `subcategories` (`id`, `category_id`, `name`, `type`, `hindi_name`, `slug`, `keywords`) VALUES
(272, 11, 'Electricity Department (NBPDCL)', 'PROFESSIONAL', 'विद्युत विभाग व बिजली बोर्ड (NBPDCL)', 'electricity-department-nbpdcl', 'NBPDCL, electricity office, bijli vibhag, power distribution, junior engineer, executive engineer, chapra bijli, transformer complaint, electricity bill, suvidha app')
ON DUPLICATE KEY UPDATE
    `category_id` = VALUES(`category_id`),
    `name`        = VALUES(`name`),
    `type`        = VALUES(`type`),
    `hindi_name`  = VALUES(`hindi_name`),
    `slug`        = VALUES(`slug`),
    `keywords`    = VALUES(`keywords`);

-- 2. Source #23 for NBPDCL Official
INSERT INTO `sources` (`id`, `title`, `title_hindi`, `subtitle`, `subtitle_hindi`, `description`, `description_hindi`, `badge_text`, `badge_text_hindi`, `badge_icon`, `badge_color_class`, `authority_badge`, `authority_badge_hindi`, `domain`, `url`, `sort_order`, `status`) VALUES
(23, 'NBPDCL Official Electricity Directory & Helpline', 'उत्तर बिहार विद्युत वितरण कंपनी (NBPDCL) आधिकारिक डायरेक्टरी', 'North Bihar Power Distribution Company Limited (Govt. of Bihar)', 'नॉर्थ बिहार पावर डिस्ट्रीब्यूशन कंपनी लिमिटेड (बिहार सरकार)', 'Official administrative directory of Electric Supply Circle, Divisions, Sub-Divisions, and Section offices of NBPDCL serving consumers across Saran District.', 'सारण जिले में उपभोक्ताओं को निर्बाध विद्युत आपूर्ति प्रदान करने वाले एनबीपीडीसीएल (NBPDCL) के विद्युत आपूर्ति अंचल, प्रमंडल, अवर प्रमंडल एवं प्रशाखा कार्यालयों की आधिकारिक डायरेक्टरी।', 'NBPDCL Electricity Board', 'एनबीपीडीसीएल बिजली बोर्ड', 'bi-lightning-charge-fill', 'bg-warning-subtle text-warning-emphasis', 'Govt. of Bihar (NBPDCL)', 'बिहार सरकार (NBPDCL)', 'nbpdcl.co.in', 'https://nbpdcl.co.in/', 23, 'ACTIVE')
ON DUPLICATE KEY UPDATE
    `title`                 = VALUES(`title`),
    `title_hindi`           = VALUES(`title_hindi`),
    `subtitle`              = VALUES(`subtitle`),
    `subtitle_hindi`        = VALUES(`subtitle_hindi`),
    `description`           = VALUES(`description`),
    `description_hindi`     = VALUES(`description_hindi`),
    `badge_text`            = VALUES(`badge_text`),
    `badge_text_hindi`      = VALUES(`badge_text_hindi`),
    `badge_icon`            = VALUES(`badge_icon`),
    `badge_color_class`     = VALUES(`badge_color_class`),
    `authority_badge`       = VALUES(`authority_badge`),
    `authority_badge_hindi` = VALUES(`authority_badge_hindi`),
    `domain`                = VALUES(`domain`),
    `url`                   = VALUES(`url`),
    `sort_order`            = VALUES(`sort_order`),
    `status`                = VALUES(`status`);

-- 3. Official NBPDCL Listings across Saran District
INSERT INTO `listings` (
    `category_id`, `subcategory_id`, `block_id`, `user_id`,
    `title`, `hindi_title`, `slug`, `contact_person`,
    `mobile`, `mobile_visibility`, `email`, `email_visibility`,
    `website`, `address`, `pincode`, `business_hours`,
    `services`, `description`,
    `is_verified`, `status`, `plan_type`, `source`, `source_id`
) VALUES
(
    11, 272, 1, 1,
    'NBPDCL Electric Supply Circle - ESE Chapra', 'एनबीपीडीसीएल विद्युत आपूर्ति अंचल - अधीक्षण अभियंता छपरा', 'nbpdcl-electric-supply-circle-ese-chapra', 'Electrical Superintending Engineer (ESE)',
    '7763815361', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Office of the Electrical Superintending Engineer, Electric Supply Circle, Power House Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Apex administrative and technical supervision office for power distribution across Saran (Chapra) district under NBPDCL. Oversees Electric Supply Divisions of Chapra East and Chapra West, grid maintenance, high-tension distribution networks, and district power infrastructure.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Special Task Force (STF) - ESE STF Chapra', 'एनबीपीडीसीएल स्पेशल टास्क फोर्स (STF) - अधीक्षण अभियंता छपरा', 'nbpdcl-special-task-force-ese-stf-chapra', 'Electrical Superintending Engineer (STF)',
    '6287593384', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Special Task Force (STF) Office, Electric Supply Circle Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Dedicated enforcement and inspection wing of NBPDCL for Saran Circle. Conducts anti-power theft drives, load audits, loss reduction enforcement, and regulatory compliance across Saran district.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Consumer Grievance Redressal Forum (CGRF) - 1st Member Chapra', 'उपभोक्ता शिकायत निवारण मंच (CGRF) - प्रथम सदस्य छपरा', 'nbpdcl-cgrf-1st-member-chapra', '1st Member, CGRF Chapra',
    '7070995607', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Consumer Grievance Redressal Forum (CGRF), NBPDCL Circle Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Statutory consumer grievance tribunal established under Electricity Act 2003 for Saran Circle consumers. Handles unresolved billing disputes, faulty meter grievances, service deficiency appeals, and consumer dispute adjudications.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Consumer Grievance Redressal Forum (CGRF) - 2nd Member Chapra', 'उपभोक्ता शिकायत निवारण मंच (CGRF) - द्वितीय सदस्य छपरा', 'nbpdcl-cgrf-2nd-member-chapra', '2nd Member, CGRF Chapra',
    '7004218319', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Consumer Grievance Redressal Forum (CGRF), NBPDCL Circle Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Consumer dispute resolution bench of CGRF Chapra handling secondary hearings, technical assessment of power supply grievances, compensation claims, and consumer rights protection across Saran district.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL IT Cell - Manager (IT) Chapra Circle', 'एनबीपीडीसीएल आईटी सेल - प्रबंधक (सूचना प्रौद्योगिकी) छपरा', 'nbpdcl-it-cell-manager-it-chapra', 'Manager (IT), Chapra Circle',
    '7368800261', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Information Technology Cell, NBPDCL Circle Office, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Supervises digital consumer billing, Smart Prepaid Meter infrastructure, Suvidha Mobile App backend, online revenue collection systems, and IT network operations across both Chapra East and Chapra West divisions.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Meter Relay Testing (MRT) Division - EEE Chapra', 'एनबीपीडीसीएल मीटर रिले टेस्टिंग (MRT) प्रमंडल - कार्यपालक अभियंता छपरा', 'nbpdcl-meter-relay-testing-mrt-eee-chapra', 'Executive Electrical Engineer (MRT)',
    '7763815337', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'MRT Division Office, Power House Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Specialized technical division responsible for precision meter calibration, high-voltage relay testing, substation protective relaying systems, CT/PT accuracy testing, and energy audit metering across Saran district.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL MRT Sub-Division I - AEE Chapra', 'एनबीपीडीसीएल एमआरटी अवर प्रमंडल-1 - सहायक अभियंता छपरा', 'nbpdcl-mrt-sub-division-1-aee-chapra', 'Assistant Electrical Engineer (MRT S/Divn-I)',
    '7763815285', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'MRT Sub-Division I, Power House Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Field testing and inspection unit for commercial, industrial HT connections and primary 33/11 KV power transformer relays in Saran East sector.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL MRT Sub-Division II - AEE Chapra', 'एनबीपीडीसीएल एमआरटी अवर प्रमंडल-2 - सहायक अभियंता छपरा', 'nbpdcl-mrt-sub-division-2-aee-chapra', 'Assistant Electrical Engineer (MRT S/Divn-II)',
    '7763815286', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'MRT Sub-Division II, Power House Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Relay calibration, HT/LT smart meter audit unit, and power factor testing wing covering Saran West sector substations.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL MRT Sub-Division I - JEE Chapra', 'एनबीपीडीसीएल एमआरटी अवर प्रमंडल-1 - कनीय अभियंता छपरा', 'nbpdcl-mrt-sub-division-1-jee-chapra', 'Junior Electrical Engineer (MRT S/Divn-I)',
    '7763815169', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'MRT Sub-Division I Laboratory, Power House, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'On-site technical testing of energy meters, relay settings adjustments, and fault diagnostic assistance for Saran East.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL MRT Sub-Division II - JEE Chapra', 'एनबीपीडीसीएल एमआरटी अवर प्रमंडल-2 - कनीय अभियंता छपरा', 'nbpdcl-mrt-sub-division-2-jee-chapra', 'Junior Electrical Engineer (MRT S/Divn-II)',
    '7763815170', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'MRT Sub-Division II Laboratory, Power House, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Technical meter diagnostics, field seal verification, and substation relay health checking across Saran West.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Transformer Repair Workshop (TRW) - EEE Chapra', 'एनबीपीडीसीएल ट्रांसफार्मर मरम्मत कार्यशाला (TRW) - कार्यपालक अभियंता छपरा', 'nbpdcl-transformer-repair-workshop-trw-eee-chapra', 'Executive Electrical Engineer (TRW)',
    '7763815354', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Transformer Repair Workshop (TRW), Power House Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Central heavy workshop facility for overhaul, coil rewinding, transformer oil filtration, core repairs, and re-commissioning of distribution transformers (DTRs) across Saran district.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Transformer Repair Workshop (TRW) - AEE Chapra', 'एनबीपीडीसीएल ट्रांसफार्मर मरम्मत कार्यशाला (TRW) - सहायक अभियंता छपरा', 'nbpdcl-transformer-repair-workshop-trw-aee-chapra', 'Assistant Electrical Engineer (TRW)',
    '7763815268', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Transformer Repair Workshop (TRW), Power House Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Technical supervision of transformer testing, dielectric oil testing, load endurance trials, and rapid repair turnaround for burnt transformers in Saran.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Central Store - EEE Chapra', 'एनबीपीडीसीएल केंद्रीय भंडार - कार्यपालक अभियंता छपरा', 'nbpdcl-central-store-eee-chapra', 'Executive Electrical Engineer (Central Store)',
    '7763815344', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Central Materials Store, NBPDCL Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'District central inventory repository managing distribution transformers, conductors, cables, electric poles, smart meters, and safety switchgear for electrical works across Saran.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Central Store - AEE Chapra', 'एनबीपीडीसीएल केंद्रीय भंडार - सहायक अभियंता छपरा', 'nbpdcl-central-store-aee-chapra', 'Assistant Electrical Engineer (Central Store)',
    '7763815300', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Central Materials Store, NBPDCL Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Stock replenishment, field issuance verification, emergency material dispatches for line restorations across Saran divisions.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Civil Engineering Wing - AE Civil Chapra', 'एनबीपीडीसीएल सिविल इंजीनियरिंग विंग - सहायक अभियंता (सिविल) छपरा', 'nbpdcl-civil-wing-ae-civil-chapra', 'Assistant Engineer (Civil)',
    '7763815292', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Civil Engineering Section, NBPDCL Circle Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Oversees civil infrastructure construction and structural maintenance of 33/11 KV Power Sub-Stations (PSS), control rooms, office buildings, and staff quarters across Saran Circle.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Project Division - AEE Project Chapra', 'एनबीपीडीसीएल प्रोजेक्ट डिवीजन - सहायक अभियंता (प्रोजेक्ट) छपरा', 'nbpdcl-project-sub-division-aee-project-chapra', 'Assistant Electrical Engineer (Project)',
    '7763818700', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Project Sub-Division Office, Power House Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Execution and monitoring of central and state government power enhancement schemes including RDSS, agricultural feeder segregation, and rural network modernization in Saran.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Electric Supply Division - EEE Chapra East', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रमंडल - कार्यपालक अभियंता छपरा पूर्व', 'nbpdcl-electric-supply-division-eee-chapra-east', 'Executive Electrical Engineer (EEE, ESD Chapra East)',
    '7763815314', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Office of the Executive Electrical Engineer, ESD Chapra (East), Power House, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Administrative headquarters of Electric Supply Division Chapra East covering eastern and southern Saran sub-divisions of Sonepur, Marhaura, Shitalpur, and Mashrakh. Oversees power supply reliability, load shedding control, and customer operations.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Project Wing - EEE Project Chapra East', 'एनबीपीडीसीएल प्रोजेक्ट विंग - कार्यपालक अभियंता (प्रोजेक्ट) छपरा पूर्व', 'nbpdcl-project-wing-eee-project-chapra-east', 'Executive Electrical Engineer (Project, Chapra East)',
    '7763818392', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Project Wing, ESD Chapra (East), Power House Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Planning and implementation of distribution infrastructure strengthening, new 33/11 KV power substations, and AB-cabling projects in Chapra East division jurisdiction.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Revenue Office - Revenue Officer Chapra East', 'एनबीपीडीसीएल राजस्व कार्यालय - राजस्व अधिकारी छपरा पूर्व', 'nbpdcl-revenue-office-revenue-officer-chapra-east', 'Revenue Officer (ESD Chapra East)',
    '7763818424', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Revenue Section, ESD Chapra (East), Power House, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Supervises commercial electricity billing, revenue collections, commercial dispute resolutions, arrear recoveries, and billing reconciliation for Chapra East division consumers.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 3, 1,
    'NBPDCL Electric Supply Sub-Division - Sonepur (AEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति अवर प्रमंडल - सहायक अभियंता सोनपुर', 'nbpdcl-electric-supply-sub-division-sonepur-aee', 'Assistant Electrical Engineer (AEE, ESSD Sonepur)',
    '7763815208', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Office of the Assistant Electrical Engineer, Electric Supply Sub-Division, Near Railway Station, Sonepur, Saran, Bihar - 841101', '841101', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Administrative sub-division governing electricity distribution across Sonepur, Parsa, Maker, and Nayagaon sections. Manages power supply to Sonepur Mela, railway corridor, domestic and agricultural consumers.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 2, 1,
    'NBPDCL Electric Supply Sub-Division - Marhaura (AEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति अवर प्रमंडल - सहायक अभियंता मढ़ौरा', 'nbpdcl-electric-supply-sub-division-marhaura-aee', 'Assistant Electrical Engineer (AEE, ESSD Marhaura)',
    '7763815209', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Office of the Assistant Electrical Engineer, Electric Supply Sub-Division, Marhaura, Saran, Bihar - 841418', '841418', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Controls electricity supply across Marhaura Urban, Marhaura Rural, and Ishuapur sections including Marhaura industrial area (Rail Engine Factory), sugar factory town, and rural networks.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 16, 1,
    'NBPDCL Electric Supply Sub-Division - Shitalpur (AEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति अवर प्रमंडल - सहायक अभियंता शीतलपुर', 'nbpdcl-electric-supply-sub-division-shitalpur-aee', 'Assistant Electrical Engineer (AEE, ESSD Shitalpur)',
    '7763814838', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Office of the Assistant Electrical Engineer, Electric Supply Sub-Division, Shitalpur, Dariyapur Block, Saran, Bihar - 841221', '841221', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Governs power distribution across Dighwara and Dariapur sections, covering National Highway industrial corridors, rural feeder networks, and riverine agricultural tracts.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 19, 1,
    'NBPDCL Electric Supply Sub-Division - Mashrakh (AEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति अवर प्रमंडल - सहायक अभियंता मशरख', 'nbpdcl-electric-supply-sub-division-mashrakh-aee', 'Assistant Electrical Engineer (AEE, ESSD Mashrakh)',
    '7763814832', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Office of the Assistant Electrical Engineer, Electric Supply Sub-Division, Mashrakh, Saran, Bihar - 841417', '841417', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Administers northern Saran power distribution covering Mashrakh, Taraiya, Panapur, and Amnour sections. Oversees rural line maintenance, substation operation, and consumer complaint handling.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 3, 1,
    'NBPDCL Electric Supply Section - Sonepur (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता सोनपुर', 'nbpdcl-electric-supply-section-sonepur-jee', 'Junior Electrical Engineer (JEE, Section Sonepur)',
    '7763814835', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Power Sub-Station Campus, Sonepur, Saran, Bihar - 841101', '841101', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Primary grassroots operational office for domestic, commercial, and institutional consumers in Sonepur Nagar Panchayat and nearby rural areas. Handles power breakdown complaints, fuse call services, and new connection verifications.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 6, 1,
    'NBPDCL Electric Supply Section - Parsa (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता परसा', 'nbpdcl-electric-supply-section-parsa-jee', 'Junior Electrical Engineer (JEE, Section Parsa)',
    '7763814837', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Power Sub-Station, Parsa Bazar, Saran, Bihar - 841219', '841219', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Operational section managing power lines, local distribution transformers, agricultural pumps, and residential supply across Parsa block.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 15, 1,
    'NBPDCL Electric Supply Section - Maker (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता मकेर', 'nbpdcl-electric-supply-section-maker-jee', 'Junior Electrical Engineer (JEE, Section Maker)',
    '7763818921', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Maker Power Sub-Station, Maker, Saran, Bihar - 841215', '841215', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Field office for 11 KV line repairs, transformer maintenance, and electricity billing support in Maker block panchayats.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 3, 1,
    'NBPDCL Electric Supply Section - Nayagaon (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता नयागांव', 'nbpdcl-electric-supply-section-nayagaon-jee', 'Junior Electrical Engineer (JEE, Section Nayagaon)',
    '7763814836', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Nayagaon Bazar, Sonpur Block, Saran, Bihar - 841217', '841217', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Manages local power distribution networks, rural transformers, and consumer connections across Nayagaon and adjoining Ganga-diara belts.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 7, 1,
    'NBPDCL Electric Supply Section - Dighwara (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता दिघवारा', 'nbpdcl-electric-supply-section-dighwara-jee', 'Junior Electrical Engineer (JEE, Section Dighwara)',
    '7763814838', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Power Sub-Station, Dighwara Nagar Panchayat, Saran, Bihar - 841207', '841207', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Section headquarters responsible for power supply across Dighwara town, Ami Shaktipeeth temple area, railway station feeder, and surrounding rural panchayats.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 16, 1,
    'NBPDCL Electric Supply Section - Dariapur (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता दरियापुर', 'nbpdcl-electric-supply-section-dariapur-jee', 'Junior Electrical Engineer (JEE, Section Dariapur)',
    '7763818722', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Power Sub-Station Dariapur, Bela Rail Wheel Plant Area, Saran, Bihar - 841221', '841221', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Grassroots electrical section for Dariapur block including Bela industrial precinct, local markets, and rural feeders. Manages transformer repair requisitions and line maintenance.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 2, 1,
    'NBPDCL Electric Supply Section - Marhaura Urban (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता मढ़ौरा शहरी', 'nbpdcl-electric-supply-section-marhaura-urban-jee', 'Junior Electrical Engineer (JEE, Section Marhaura Urban)',
    '7763814831', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Power Sub-Station, Marhaura Town, Saran, Bihar - 841418', '841418', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Maintains urban electrical grid, smart meters, commercial installations, and municipal street lighting feeders for Marhaura town.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 2, 1,
    'NBPDCL Electric Supply Section - Marhaura Rural (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता मढ़ौरा ग्रामीण', 'nbpdcl-electric-supply-section-marhaura-rural-jee', 'Junior Electrical Engineer (JEE, Section Marhaura Rural)',
    '7368800552', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office (Rural), Marhaura PSS Campus, Saran, Bihar - 841418', '841418', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Provides power maintenance, agricultural feeder management, and distribution transformer services to rural villages across Marhaura block.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 12, 1,
    'NBPDCL Electric Supply Section - Ishuapur (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता इसुआपुर', 'nbpdcl-electric-supply-section-ishuapur-jee', 'Junior Electrical Engineer (JEE, Section Ishuapur)',
    '7763818721', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Power Sub-Station Ishuapur, Saran, Bihar - 841411', '841411', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Field operational office for electricity distribution, transformer replacement, and line breakdown service throughout Isuwapur block.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 19, 1,
    'NBPDCL Electric Supply Section - Mashrakh (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता मशरख', 'nbpdcl-electric-supply-section-mashrakh-jee', 'Junior Electrical Engineer (JEE, Section Mashrakh)',
    '7763814832', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Mashrakh Power Sub-Station, Mashrakh Bazar, Saran, Bihar - 841417', '841417', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Handles electrical supply, breakdown services, LT line maintenance, and new meter installations for Mashrakh town and block.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 11, 1,
    'NBPDCL Electric Supply Section - Taraiya (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता तरैया', 'nbpdcl-electric-supply-section-taraiya-jee', 'Junior Electrical Engineer (JEE, Section Taraiya)',
    '7763814833', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Taraiya Power Sub-Station, Taraiya, Saran, Bihar - 841424', '841424', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Operates local power lines, rural transformers, and handles breakdown calls across all panchayats of Taraiya block.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 20, 1,
    'NBPDCL Electric Supply Section - Panapur (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता पानापुर', 'nbpdcl-electric-supply-section-panapur-jee', 'Junior Electrical Engineer (JEE, Section Panapur)',
    '7763818922', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Panapur Power Sub-Station, Panapur, Saran, Bihar - 841410', '841410', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Responsible for reliable electricity supply, transformer repairs, and agricultural connections in Panapur block along the Gandak basin.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 8, 1,
    'NBPDCL Electric Supply Section - Amnour (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता अमनौर', 'nbpdcl-electric-supply-section-amnour-jee', 'Junior Electrical Engineer (JEE, Section Amnour)',
    '7763814834', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Amnour Power Sub-Station, Amnour, Saran, Bihar - 841401', '841401', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Field office managing electricity supply, transformer maintenance, and fault redressal in Amnour block.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Electric Supply Division - EEE Chapra West', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रमंडल - कार्यपालक अभियंता छपरा पश्चिम', 'nbpdcl-electric-supply-division-eee-chapra-west', 'Executive Electrical Engineer (EEE, ESD Chapra West)',
    '7763815315', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Office of the Executive Electrical Engineer, ESD Chapra (West), Power House, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Division headquarters controlling electricity distribution for western and municipal Saran, comprising Chapra Urban, Chapra Rural, Baniyapur, and Ekma sub-divisions. Oversees power grid continuity and high-demand urban load.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Revenue Office - Revenue Officer Chapra West', 'एनबीपीडीसीएल राजस्व कार्यालय - राजस्व अधिकारी छपरा पश्चिम', 'nbpdcl-revenue-office-revenue-officer-chapra-west', 'Revenue Officer (ESD Chapra West)',
    '7763818737', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Revenue Section, ESD Chapra (West), Power House, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Handles revenue realization, billing corrections, tariff adjustments, and commercial accounts reconciliation for Chapra West division consumers.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Electric Supply Sub-Division - Chapra Urban (AEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति अवर प्रमंडल - सहायक अभियंता छपरा शहरी', 'nbpdcl-electric-supply-sub-division-chapra-urban-aee', 'Assistant Electrical Engineer (AEE, ESSD Chapra Urban)',
    '7763815205', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Office of the Assistant Electrical Engineer, ESSD Chapra Urban, Power House, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Controls electricity supply for Chapra Municipal Corporation area including Kashi Bazar, Telpa, Power House, and commercial centers. Ensures uninterrupted power supply to district hospitals, government collectorate, and commercial markets.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Electric Supply Sub-Division - Chapra Rural (AEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति अवर प्रमंडल - सहायक अभियंता छपरा ग्रामीण', 'nbpdcl-electric-supply-sub-division-chapra-rural-aee', 'Assistant Electrical Engineer (AEE, ESSD Chapra Rural)',
    '7763815206', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Office of the Assistant Electrical Engineer, ESSD Chapra Rural, Power House Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Supervises power distribution across Chapra Sadar rural panchayats, Garkha, Basant, Revilganj, and Doriganj sections. Oversees rural transformers and agricultural energization.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 9, 1,
    'NBPDCL Electric Supply Sub-Division - Baniyapur (AEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति अवर प्रमंडल - सहायक अभियंता बनियापुर', 'nbpdcl-electric-supply-sub-division-baniyapur-aee', 'Assistant Electrical Engineer (AEE, ESSD Baniyapur)',
    '7763815207', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Office of the Assistant Electrical Engineer, Electric Supply Sub-Division, Baniyapur, Saran, Bihar - 841403', '841403', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Supervises electricity distribution across Baniyapur, Nagra, Jalalpur, and Lahladpur sections. Coordinates substation operations and rural electrification maintenance.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 10, 1,
    'NBPDCL Electric Supply Sub-Division - Ekma (AEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति अवर प्रमंडल - सहायक अभियंता एकमा', 'nbpdcl-electric-supply-sub-division-ekma-aee', 'Assistant Electrical Engineer (AEE, ESSD Ekma)',
    '7763814842', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Office of the Assistant Electrical Engineer, Electric Supply Sub-Division, Ekma, Saran, Bihar - 841208', '841208', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Governs power supply networks across Ekma, Manjhi, Daudpur, and Tajpur sections along the western Saran - UP border. Manages interstate grid connections and rural feeders.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Electric Supply Section - Kashi Bazar, Chapra (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता काशी बाजार छपरा', 'nbpdcl-electric-supply-section-kashi-bazar-chapra-jee', 'Junior Electrical Engineer (JEE, Section Kashi Bazar)',
    '7763814840', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Kashi Bazar, Chapra City, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Grassroots field office for densely populated commercial market areas of Kashi Bazar, Hathwa Market, and inner Chapra city. Attends to transformer loads, underground cables, and fuse calls.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Electric Supply Section - Telpa, Chapra (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता तेलपा छपरा', 'nbpdcl-electric-supply-section-telpa-chapra-jee', 'Junior Electrical Engineer (JEE, Section Telpa)',
    '7763814841', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Telpa, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Operates electricity distribution, consumer grievance redressal, and line maintenance in Telpa, Dahiyawan, and surrounding urban wards of Chapra.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Electric Supply Section - Chapra Power House (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता छपरा पावर हाउस', 'nbpdcl-electric-supply-section-chapra-power-house-jee', 'Junior Electrical Engineer (JEE, Section Chapra Power House)',
    '7763814839', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Main Power House Campus, Chapra, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Maintains urban feeders originating from the central Power House substation, supplying government offices, civil courts, and surrounding municipal wards.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Electric Supply Section - Chapra Sadar (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता छपरा सदर', 'nbpdcl-electric-supply-section-chapra-sadar-jee', 'Junior Electrical Engineer (JEE, Section Chapra Sadar)',
    '7763814844', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Chapra Sadar Block Campus, Saran, Bihar - 841301', '841301', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Responsible for rural and peri-urban electricity distribution, rural electrification schemes, and agricultural power supply across Chapra Sadar block.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 5, 1,
    'NBPDCL Electric Supply Section - Garkha (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता गरखा', 'nbpdcl-electric-supply-section-garkha-jee', 'Junior Electrical Engineer (JEE, Section Garkha)',
    '7763814846', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Power Sub-Station Garkha, Garkha Bazar, Saran, Bihar - 841311', '841311', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Operates power supply, transformer maintenance, and complaint redressal across Garkha market and rural panchayats.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 5, 1,
    'NBPDCL Electric Supply Section - Basant (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता बसंत', 'nbpdcl-electric-supply-section-basant-jee', 'Junior Electrical Engineer (JEE, Section Basant)',
    '9264443775', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Basant Power Sub-Station, Garkha Block, Saran, Bihar - 841311', '841311', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Manages electricity distribution network, rural feeders, and agricultural power for Basant and surrounding panchayats.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 4, 1,
    'NBPDCL Electric Supply Section - Revilganj (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता रिविलगंज', 'nbpdcl-electric-supply-section-revilganj-jee', 'Junior Electrical Engineer (JEE, Section Revilganj)',
    '7763814845', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Power Sub-Station, Gautam Sthan Road, Rivilganj, Saran, Bihar - 841305', '841305', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Maintains electricity grid across historic Revilganj town, Gautam Sthan temple area, Sarayu riverfront, and surrounding rural habitations.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 1, 1,
    'NBPDCL Electric Supply Section - Doriganj (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता डोरीगंज', 'nbpdcl-electric-supply-section-doriganj-jee', 'Junior Electrical Engineer (JEE, Section Doriganj)',
    '9264443773', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Doriganj Bazar, Chirand Archaeological Corridor, Saran, Bihar - 841211', '841211', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Manages power distribution along the Doriganj sand mining corridor, Chirand heritage zone, and southern Ganga bank rural feeders.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 10, 1,
    'NBPDCL Electric Supply Section - Ekma (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता एकमा', 'nbpdcl-electric-supply-section-ekma-jee', 'Junior Electrical Engineer (JEE, Section Ekma)',
    '7763814842', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Ekma Power Sub-Station, Ekma Bazar, Saran, Bihar - 841208', '841208', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Field section handling power supply, line fault restorations, smart meter connections, and commercial power for Ekma town and block.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 14, 1,
    'NBPDCL Electric Supply Section - Manjhi (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता मांझी', 'nbpdcl-electric-supply-section-manjhi-jee', 'Junior Electrical Engineer (JEE, Section Manjhi)',
    '7763818924', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Manjhi Power Sub-Station, Manjhi Bazar, Saran, Bihar - 841313', '841313', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Oversees electricity network across Manjhi block along the Bihar-UP border at the Ghaghara (Sarayu) river confluence, managing rural feeders and agricultural power lines.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 14, 1,
    'NBPDCL Electric Supply Section - Daudpur (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता दाउदपुर', 'nbpdcl-electric-supply-section-daudpur-jee', 'Junior Electrical Engineer (JEE, Section Daudpur)',
    '7763818917', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Daudpur Bazar, Manjhi Block, Saran, Bihar - 841205', '841205', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Provides power maintenance, breakdown response, and distribution transformer services to Daudpur market and surrounding rural villages.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 14, 1,
    'NBPDCL Electric Supply Section - Tajpur (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता ताजपुर', 'nbpdcl-electric-supply-section-tajpur-jee', 'Junior Electrical Engineer (JEE, Section Tajpur)',
    '7763814842', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Tajpur Power Sub-Station Area, Manjhi Block, Saran, Bihar - 841205', '841205', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Field operational unit for rural power distribution, agricultural tubewell connections, and breakdown services in Tajpur sector.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 9, 1,
    'NBPDCL Electric Supply Section - Baniyapur-1 (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता बनियापुर-१', 'nbpdcl-electric-supply-section-baniyapur-1-jee', 'Junior Electrical Engineer (JEE, Section Baniyapur-1)',
    '9264443777', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office (Section-1), Baniyapur Power Sub-Station, Saran, Bihar - 841403', '841403', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Controls northern sector electricity feeders, market transformers, and residential billing for Baniyapur block.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 9, 1,
    'NBPDCL Electric Supply Section - Baniyapur-2 (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता बनियापुर-२', 'nbpdcl-electric-supply-section-baniyapur-2-jee', 'Junior Electrical Engineer (JEE, Section Baniyapur-2)',
    '7763818915', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office (Section-2), Baniyapur PSS Campus, Saran, Bihar - 841403', '841403', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Manages southern sector rural feeders, agricultural connections, and power fault services across Baniyapur block panchayats.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 18, 1,
    'NBPDCL Electric Supply Section - Nagra (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता नगरा', 'nbpdcl-electric-supply-section-nagra-jee', 'Junior Electrical Engineer (JEE, Section Nagra)',
    '7763814853', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Power Sub-Station Nagra, Nagra Bazar, Saran, Bihar - 841442', '841442', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Grassroots electrical section for Nagra block, managing 11 KV lines, distribution transformers, and power breakdown complaints.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 17, 1,
    'NBPDCL Electric Supply Section - Jalalpur (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता जलालपुर', 'nbpdcl-electric-supply-section-jalalpur-jee', 'Junior Electrical Engineer (JEE, Section Jalalpur)',
    '7763814848', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Jalalpur Power Sub-Station, Jalalpur Bazar, Saran, Bihar - 841412', '841412', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Operational electricity section for Jalalpur block, handling consumer grievances, line breakdowns, and transformer replacements.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
),
(
    11, 272, 13, 1,
    'NBPDCL Electric Supply Section - Lahladpur (JEE)', 'एनबीपीडीसीएल विद्युत आपूर्ति प्रशाखा - कनीय अभियंता लहलादपुर', 'nbpdcl-electric-supply-section-lahladpur-jee', 'Junior Electrical Engineer (JEE, Section Lahladpur)',
    '7763818916', 'REGISTERED', '', 'REGISTERED',
    'https://nbpdcl.co.in/', 'Electric Supply Section Office, Lahladpur (Janta Bazar) PSS, Saran, Bihar - 841419', '841419', 'Mon-Sat: 10:00 AM - 05:00 PM (Emergency 24x7)',
    'New Electricity Connection, Bill Payment & Inquiries, Power Breakdown Redressal, Transformer Repair & Replacement, Meter Testing & Replacement, Smart Meter Support, Har Ghar Bijli Helpline', 'Handles power distribution across Lahladpur block and Janta Bazar trade center, attending to rural feeder breakdowns and transformer maintenance.',
    'YES', 'ACTIVE', 'FREE', 'NBPDCL Official', 23
)
ON DUPLICATE KEY UPDATE
    `category_id`       = VALUES(`category_id`),
    `subcategory_id`    = VALUES(`subcategory_id`),
    `block_id`          = VALUES(`block_id`),
    `title`             = VALUES(`title`),
    `hindi_title`       = VALUES(`hindi_title`),
    `contact_person`    = VALUES(`contact_person`),
    `mobile`            = VALUES(`mobile`),
    `mobile_visibility` = VALUES(`mobile_visibility`),
    `email`             = VALUES(`email`),
    `email_visibility`  = VALUES(`email_visibility`),
    `website`           = VALUES(`website`),
    `address`           = VALUES(`address`),
    `pincode`           = VALUES(`pincode`),
    `business_hours`    = VALUES(`business_hours`),
    `services`          = VALUES(`services`),
    `description`       = VALUES(`description`),
    `is_verified`       = VALUES(`is_verified`),
    `status`            = VALUES(`status`),
    `plan_type`         = VALUES(`plan_type`),
    `source`            = VALUES(`source`),
    `source_id`         = VALUES(`source_id`);

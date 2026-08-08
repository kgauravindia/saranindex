<?php
/**
 * Education.xlsx → Listings Import Script
 * Inserts CBSE-affiliated schools from Saran District into the listings table
 * Category: Education (ID: 6) | Subcategory: Schools (ID: 50)
 * 
 * Run via CLI: php database/import_education.php
 */

// Bootstrap
require_once __DIR__ . '/../includes/functions.php';

$db = getDB();
if (!$db) {
    echo "ERROR: Database connection failed.\n";
    exit(1);
}

// Block name → ID mapping
$blocks = getBlocks();
$blockMap = [];
foreach ($blocks as $b) {
    $blockMap[strtolower(trim($b['block_name']))] = $b['id'];
    if (!empty($b['hindi_name'])) {
        $blockMap[strtolower(trim($b['hindi_name']))] = $b['id'];
    }
}

// Address keyword → Block name mapping for Saran District
$addressBlockMap = [
    'BHAGWAN BAZAR' => 'Chapra Sadar',
    'DARSHAN NAGAR' => 'Chapra Sadar',
    'DAK BUNG'      => 'Chapra Sadar',
    'SARAI BOX'     => 'Garkha',
    'CHAPRA'     => 'Chapra Sadar',
    'CHHAPRA'    => 'Chapra Sadar',
    'SADAR'      => 'Chapra Sadar',
    'TARI'       => 'Chapra Sadar',
    'CHANDMARI'  => 'Chapra Sadar',
    'DAULATGANJ' => 'Chapra Sadar',
    'KATRA'      => 'Chapra Sadar',
    'MUFFASIL'   => 'Chapra Sadar',
    'MUFASSIL'   => 'Chapra Sadar',
    'DAUDPUR'    => 'Chapra Sadar',
    'KOHRA'      => 'Chapra Sadar',
    'MUKRERA'    => 'Chapra Sadar',
    'GOLAMBAR'   => 'Chapra Sadar',
    'SARHA'      => 'Chapra Sadar',
    'NEWAJI'     => 'Chapra Sadar',
    'HASANPURWA' => 'Chapra Sadar',
    'SANDHA'     => 'Chapra Sadar',
    'SIDHWALIA'  => 'Chapra Sadar',
    'FAKULI'     => 'Chapra Sadar',
    'BANGRA'     => 'Chapra Sadar',
    'MARHAURA'   => 'Marhaura',
    'MARHAURAH'  => 'Marhaura',
    'SONPUR'     => 'Sonepur',
    'SONEPUR'    => 'Sonepur',
    'BAIJALPUR'  => 'Sonepur',
    'SABALPUR'   => 'Sonepur',
    'REVELGANJ'  => 'Revelganj',
    'GARKHA'     => 'Garkha',
    'BHELDI'     => 'Garkha',
    'PARSA'      => 'Parsa',
    'DERNI'      => 'Parsa',
    'DEVATI'     => 'Parsa',
    'BARWEY'     => 'Parsa',
    'POJHI'      => 'Parsa',
    'DIGHWARA'   => 'Dighwara',
    'AMI'        => 'Dighwara',
    'AMNOUR'     => 'Amanour',
    'AMANOUR'    => 'Amanour',
    'SALAKHUAN'  => 'Amanour',
    'BANIYAPUR'  => 'Baniapur',
    'BANIAPUR'   => 'Baniapur',
    'PUCHARI'    => 'Baniapur',
    'KALA'       => 'Baniapur',
    'EKMA'       => 'Ekma',
    'RASULPUR'   => 'Ekma',
    'LAGUNI'     => 'Ekma',
    'ISUAPUR'    => 'Isuapur',
    'JALALPUR'   => 'Jalalpur',
    'APHAR'      => 'Jalalpur',
    'MASHRAK'    => 'Mashrakh',
    'MASHRAKH'   => 'Mashrakh',
    'NACHAP'     => 'Manjhi',
    'MANJHI'     => 'Manjhi',
    'DARIYAPUR'  => 'Dariapur',
    'VISHWAMBHARPUR' => 'Dariapur',
    'BELA'       => 'Dariapur',
    'PANAPUR'    => 'Panapur',
    'KHALPURA'   => 'Panapur',
    'GULTENGANJ' => 'Panapur',
    'GULTAINGANJ'=> 'Panapur',
];

function detectBlock($address, $addressBlockMap) {
    $addr = strtoupper($address);
    // Check longer keys first for better matching
    $keys = array_keys($addressBlockMap);
    usort($keys, function($a, $b) { return strlen($b) - strlen($a); });
    foreach ($keys as $keyword) {
        if (strpos($addr, $keyword) !== false) {
            return $addressBlockMap[$keyword];
        }
    }
    return 'Chapra Sadar'; // Default
}

function getBlockId($blockName, $blockMap) {
    return $blockMap[strtolower(trim($blockName))] ?? 1;
}

function extractPincode($address) {
    if (preg_match('/(\d{6})/', $address, $m)) {
        return $m[1];
    }
    return '841301';
}

function titleCase($str) {
    $str = mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
    // Fix common acronyms
    $str = preg_replace_callback('/\b(Dav|Kvs|Cbse|Nr|Nh|Po|Ps|Jn|Rsm|Bsm|Hr|Rn|Rnp|Jd|Dr|St|Pm|Shri)\b/i', function($m) {
        return strtoupper($m[1]);
    }, $str);
    return $str;
}

// Schools data from Education.xlsx (CBSE Saran District)
$schools = [
    ['name' => 'PM Shri Kendriya Vidyalaya Sonpur', 'aff' => '300023', 'code' => '69029', 'level' => 'Senior Secondary', 'address' => 'Sonpur (NE Railway), Distt Saran, Bihar', 'principal' => 'Rajesh Kumar', 'website' => 'https://sonpur.kvs.ac.in'],
    ['name' => 'PM Shri Kendriya Vidyalaya Mashrak', 'aff' => '300037', 'code' => '69063', 'level' => 'Senior Secondary', 'address' => 'Mashrak, PO Mashrak, Distt Saran, Bihar', 'principal' => 'Ranjana Jha', 'website' => 'http://www.mashrak.kvs.ac.in'],
    ['name' => 'Kendriya Vidyalaya Chapra', 'aff' => '300045', 'code' => '65041', 'level' => 'Secondary', 'address' => 'Zila School Hostel Campus, Near Bus Stand, Chapra, Distt. Saran, Bihar-841301', 'principal' => 'Radha Charan', 'website' => 'https://chapra.kvs.ac.in'],
    ['name' => 'Kendriya Vidyalaya Saran Bela', 'aff' => '300089', 'code' => '69051', 'level' => 'Senior Secondary', 'address' => 'Railwheel Plant Bela, PO Arvind Nagar, Via Sheetalpur, Dist Chapra Saran, Bihar', 'principal' => 'Ranjana Tiwari', 'website' => 'https://saranbela.kvs.ac.in/'],
    ['name' => 'Acharya Narender Deo Public School', 'aff' => '330009', 'code' => '65009', 'level' => 'Senior Secondary', 'address' => 'At & PO Khalpura, Via Gultainganj, Distt Saran, Chapra, Bihar', 'principal' => 'Anil Kumar Singh', 'website' => 'http://andeopublicschool.com'],
    ['name' => 'Chapra Central School', 'aff' => '330045', 'code' => '65044', 'level' => 'Senior Secondary', 'address' => 'Behind Ghosh Colony, Near Bazar Samiti, Sarha Road, Chapra, Bihar', 'principal' => 'Santosh Kumar', 'website' => 'https://www.chapracentralschool.co.in'],
    ['name' => 'Bhagwat Vidyapeeth', 'aff' => '330240', 'code' => '65239', 'level' => 'Senior Secondary', 'address' => 'Russi Chhawni, Bhagwan Bazar, Chhapra, Saran, Bihar', 'principal' => 'Amrendra Singh', 'website' => 'https://www.bhagwatvidyapeethcpr.com'],
    ['name' => 'Shakti Shanti Academy', 'aff' => '330247', 'code' => '65246', 'level' => 'Senior Secondary', 'address' => 'Ambika Asthan, Ami, Dighwara, Saran', 'principal' => 'Kundan Singh', 'website' => 'https://www.ssaami.ac.in'],
    ['name' => 'Central Public School Chapra', 'aff' => '330397', 'code' => '65394', 'level' => 'Senior Secondary', 'address' => 'Vikas Nagar, Chandmari Road, PO-Tari, PS-Muffasil, Chapra', 'principal' => 'Vikash Kumar', 'website' => 'https://www.cpschapra.com'],
    ['name' => 'Galaxy Residential Public School', 'aff' => '330418', 'code' => '65415', 'level' => 'Senior Secondary', 'address' => 'At+PO+PS Jalalpur, Distt Saran, Chapra', 'principal' => 'Kanchan Kumar Pankaj', 'website' => 'https://www.grpschool.in'],
    ['name' => 'JD Public School', 'aff' => '330422', 'code' => '65419', 'level' => 'Senior Secondary', 'address' => 'Bangra, Daudpur, Saran', 'principal' => 'Niranjan Kumar Singh', 'website' => 'https://www.jdpublicschool.com'],
    ['name' => 'Children Delight School', 'aff' => '330437', 'code' => '65434', 'level' => 'Secondary', 'address' => 'Dak Bunglaw Road, Chapra', 'principal' => 'Ranjeet Vijay', 'website' => 'https://www.childrendelightschool.com'],
    ['name' => 'HR Imperial Public School', 'aff' => '330495', 'code' => '65491', 'level' => 'Senior Secondary', 'address' => 'Hathwa Niketan, Dak Bunglow Road, Chapra', 'principal' => 'Arvind Kumar', 'website' => 'https://www.ips.ac.in'],
    ['name' => 'Holy Kids International School', 'aff' => '330515', 'code' => '65511', 'level' => 'Senior Secondary', 'address' => 'Katra, Near Satyanarayan Mandir, Gudari Rai Chowk, Chapra', 'principal' => 'Satyendra Kumar Sharma', 'website' => 'https://www.hkis.ac.in'],
    ['name' => 'Sharshwati Shishu Vidya Mandir Chapra', 'aff' => '330541', 'code' => '65537', 'level' => 'Secondary', 'address' => 'Darshan Nagar, Saran (Chapra), Bihar', 'principal' => 'Binod Kumar', 'website' => 'https://www.ssvmchapra.com'],
    ['name' => 'RNP Public School', 'aff' => '330564', 'code' => '65560', 'level' => 'Senior Secondary', 'address' => 'Mirchaiya Toal, Daulatganj, PS-Bhagwan Bazar, Saran (Chapra)', 'principal' => 'Umesh Kumar Pathak', 'website' => 'https://www.rnppublicschool.net'],
    ['name' => "St. Joseph's Academy", 'aff' => '330573', 'code' => '65569', 'level' => 'Senior Secondary', 'address' => 'At+PO Sarai Box, Via Garkha, P.S Bheldi', 'principal' => 'Dr Kalpana Chhetri', 'website' => 'https://www.stjosephsacademy.ac.in'],
    ['name' => 'Shukdeo Singh Senior Secondary School', 'aff' => '330579', 'code' => '65575', 'level' => 'Senior Secondary', 'address' => 'Newaji Tola (Katra), Revelganj, Chapra', 'principal' => 'Awadhesh Kumar Shrivastava', 'website' => 'https://www.sdsschool.ac.in'],
    ['name' => 'Jyoti Central High School', 'aff' => '330586', 'code' => '65582', 'level' => 'Senior Secondary', 'address' => 'Ekma, Saran (Bihar)', 'principal' => 'Manu Kumar Giri', 'website' => 'https://www.jchsekma.ac.in'],
    ['name' => 'Heritage Public School Sonpur', 'aff' => '330588', 'code' => '65584', 'level' => 'Senior Secondary', 'address' => 'At-Baijalpur Fakir Sonpur, PO+PS Sonpur, Dist-Saran', 'principal' => 'Maya Kumari', 'website' => 'https://www.heritagepublicschools.com'],
    ['name' => 'Maxwell High School', 'aff' => '330608', 'code' => '65604', 'level' => 'Senior Secondary', 'address' => 'Baijalpur Fakir, Sonpur, Saran', 'principal' => 'Sunita Yadav', 'website' => 'https://www.maxwell.org.in'],
    ['name' => 'Mount Litera Zee School', 'aff' => '330620', 'code' => '65616', 'level' => 'Senior Secondary', 'address' => 'Vill. Baijalpur, Sonpur, Saran', 'principal' => 'Sheelu Sinha', 'website' => 'https://www.mlzshajipur.org'],
    ['name' => 'Riddhi Siddhi Central School', 'aff' => '330640', 'code' => '65636', 'level' => 'Senior Secondary', 'address' => 'Nachap, Saran, Bihar', 'principal' => 'Dasarath Sah', 'website' => 'https://www.rscsnachap.edu.in'],
    ['name' => 'Vivekanand International Public School', 'aff' => '330692', 'code' => '65688', 'level' => 'Senior Secondary', 'address' => 'V.I.P. Golambar, Mukrera, Chapra Siwan Road, N.H. 85, Chapra-841301, Bihar', 'principal' => 'Brijnandan Singh', 'website' => 'https://www.vipschapra.com'],
    ['name' => 'Nutan Shiksha Niketan', 'aff' => '330790', 'code' => '65790', 'level' => 'Senior Secondary', 'address' => 'Kohra Bazar, Daudpur, Chapra, Saran', 'principal' => 'Om Prakash Prasad', 'website' => 'https://www.nutanshikshaniketan.com'],
    ['name' => 'Holy Family School Chapra', 'aff' => '330808', 'code' => '65805', 'level' => 'Senior Secondary', 'address' => 'Catholics Church, PO Box No 7, Chapra', 'principal' => 'Anjana Lakra', 'website' => 'https://www.hfschoolcpr.com'],
    ['name' => 'DAV Public School Chhapra', 'aff' => '330832', 'code' => '65828', 'level' => 'Secondary', 'address' => 'Sidhwalia, PO-Fakuli, PS-Mufassil, Chhapra, Saran', 'principal' => 'Shiv Pal Singh Bansi', 'website' => 'https://www.davpschhapra.in'],
    ['name' => 'Sri Bhagwan Chinta Mani School', 'aff' => '330886', 'code' => '65879', 'level' => 'Senior Secondary', 'address' => 'Vishwambharpur, Dariyapur, Saran (Bihar) - 841222', 'principal' => 'Priyanka', 'website' => 'https://www.sbcmschool.com'],
    ['name' => 'Sacred Heart Mission School', 'aff' => '330908', 'code' => '65906', 'level' => 'Senior Secondary', 'address' => 'Catholic Church, PO Box No.7, Chapra', 'principal' => 'Sr Surabhi Sona', 'website' => 'https://www.shmschoolcpr.com'],
    ['name' => 'Solanki International School', 'aff' => '330940', 'code' => '66660', 'level' => 'Senior Secondary', 'address' => 'Panapur, Khalpura, P.O.-Gultenganj, Chapra, District-Saran, Bihar', 'principal' => 'Shashi Bhushan Kumar', 'website' => 'https://www.solankischool.com'],
    ['name' => 'DAV Public School Baniyapur', 'aff' => '330951', 'code' => '66673', 'level' => 'Senior Secondary', 'address' => 'Puchari, Baniyapur, Saran, Bihar - 841403', 'principal' => 'Aman Kumar Singh', 'website' => 'https://www.davbaniyapur.com'],
    ['name' => 'Goverdhan Vidyapeeth', 'aff' => '330953', 'code' => '66679', 'level' => 'Senior Secondary', 'address' => 'NH 85, Vill/Thana/Post: Rasulpur, Block: Ekma, Dist: Chapra 841204', 'principal' => 'Rajesh Pandey', 'website' => 'https://www.govardhanvidyapeeth.com'],
    ['name' => 'Hazelwood School Chapra', 'aff' => '331019', 'code' => '66757', 'level' => 'Senior Secondary', 'address' => 'Hasanpurwa, Sandha, Newaji Tola, Bihar, Chapra, PIN 841301', 'principal' => 'Sumant Kumar', 'website' => 'https://www.hazelwood.ac.in'],
    ['name' => 'DR Vidyaniketan Senior Secondary School', 'aff' => '331027', 'code' => '66769', 'level' => 'Secondary', 'address' => 'Sabalpur Hastitola, Near Bambam Bricks, Sonepur', 'principal' => 'Ranjan Kumari', 'website' => 'https://www.drvnsss.com'],
    ['name' => 'Bishop Eastcott School', 'aff' => '331032', 'code' => '66773', 'level' => 'Senior Secondary', 'address' => 'Dahiyawan Tola Tari, Chapra (Saran), Bihar', 'principal' => 'Ranjay Kumar Srivastava', 'website' => 'https://www.bishopeastcottschool.in'],
    ['name' => 'Gurukul Public School Ekma', 'aff' => '331080', 'code' => '66826', 'level' => 'Senior Secondary', 'address' => 'PO + PS - Ekma, Chhapra', 'principal' => 'Abhishek Ranjan', 'website' => 'http://www.gurukulchhapra.com'],
    ['name' => 'Sanskar Deep International School', 'aff' => '331081', 'code' => '66829', 'level' => 'Secondary', 'address' => 'PO-Isuapur, PS-Isuapur, Bihar, Pin Code - 841411', 'principal' => 'Abhimanyu Mishra', 'website' => 'https://www.sanskardeep.co.in'],
    ['name' => 'Sant Jaleshwar Academy', 'aff' => '331084', 'code' => '66832', 'level' => 'Senior Secondary', 'address' => 'Kala, Baniyapur, Saran, Bihar. Pin - 841403', 'principal' => 'Ajit Kumar', 'website' => 'https://www.santjaleshwaracademy.in'],
    ['name' => 'Trident Public School Marhaura', 'aff' => '331090', 'code' => '66845', 'level' => 'Senior Secondary', 'address' => 'Marhaura, Chapra', 'principal' => 'Karmbir Singh', 'website' => 'http://marhaurah.tridentpublicschool.com/'],
    ['name' => 'School of Global Education', 'aff' => '331098', 'code' => '66859', 'level' => 'Senior Secondary', 'address' => 'Sonepur, Saran', 'principal' => 'Anandu Krishnan', 'website' => 'https://schoolofglobaleducation.com/'],
    ['name' => 'Swami Vivekanand High School Ekma', 'aff' => '331116', 'code' => '66888', 'level' => 'Senior Secondary', 'address' => 'PS-Ekma, Dist-Saran, Pin-841206', 'principal' => 'Neeru Pandey', 'website' => 'https://www.svhslaguni.org'],
    ['name' => 'Galaxy Radiant Public School', 'aff' => '331132', 'code' => '66908', 'level' => 'Senior Secondary', 'address' => 'Chhapra, Saran', 'principal' => 'Ashok Tiwari', 'website' => 'https://www.galaxyradiantpublicschool.com'],
    ['name' => 'JN Public School Mashrak', 'aff' => '331153', 'code' => '66937', 'level' => 'Secondary', 'address' => 'Mashrak, Saran', 'principal' => 'Rajeev Kumar Sinha', 'website' => 'https://jnpublicschool.co.in/'],
    ['name' => 'Himalayan International School Parsa', 'aff' => '331166', 'code' => '66957', 'level' => 'Senior Secondary', 'address' => 'Parsa, Saran (Chapra)', 'principal' => 'Kunal Gautam', 'website' => 'http://hisparsasaran.com/'],
    ['name' => 'Agastya World School', 'aff' => '331189', 'code' => '66991', 'level' => 'Senior Secondary', 'address' => 'Saran, Bihar', 'principal' => 'Sulekha Kumari', 'website' => 'https://www.agastyaworldschool.in'],
    ['name' => 'Reds Gayanodaya Public School', 'aff' => '331194', 'code' => '66999', 'level' => 'Secondary', 'address' => 'At Pojhi (Near Tula Brahma Sthan), PO-Parsa, PS-Derni', 'principal' => 'Sangita Kumari', 'website' => 'https://www.redsgayanodayaschool.in'],
    ['name' => 'Swami Vivekanand Public School Parsa', 'aff' => '331241', 'code' => '67062', 'level' => 'Senior Secondary', 'address' => 'Parsa, Saran, Bihar', 'principal' => 'Priyanka Kumari', 'website' => 'http://www.svpsparsa.in/'],
    ['name' => 'RSK Global School', 'aff' => '331282', 'code' => '67114', 'level' => 'Senior Secondary', 'address' => 'Salakhuan, Amnour, Saran', 'principal' => 'Akhilesh Kumar Gupta', 'website' => 'http://www.rskglobe.com'],
    ['name' => 'Petals Eternal Techno School', 'aff' => '331344', 'code' => '67197', 'level' => 'Secondary', 'address' => 'Jalalpur, Aphar, Amnour', 'principal' => 'Kundan Kumar Tiwari', 'website' => 'https://www.petsjalalpur.in'],
    ['name' => 'BSM Global School Mashrak', 'aff' => '331345', 'code' => '67198', 'level' => 'Senior Secondary', 'address' => 'Mashrak, Saran', 'principal' => 'Sayanti Singh', 'website' => 'https://bsmglobalschool.co.in'],
    ['name' => 'Kaushalya International School', 'aff' => '331365', 'code' => '67220', 'level' => 'Senior Secondary', 'address' => 'Bela, Dariyapur, Saran, Chapra', 'principal' => 'Mrityunjay Kumar Singh', 'website' => 'https://www.kaushalyainternationalschoolbela.com/'],
    ['name' => 'PM Shri Jawahar Navodaya Vidyalaya Saran', 'aff' => '340020', 'code' => '69039', 'level' => 'Senior Secondary', 'address' => 'Village Devati, PO Barwey, Via Parsa, Distt. Saran, Bihar', 'principal' => 'Sachidanand Sharma', 'website' => 'https://www.navodaya.gov.in/nvs/nvs-school/SARAN/en/home/'],
];

echo "=====================================================\n";
echo "  Education.xlsx → Listings Import\n";
echo "  Category: Education (6) | Subcategory: Schools (50)\n";
echo "=====================================================\n\n";

$success = 0;
$skipped = 0;
$errors = 0;

foreach ($schools as $idx => $school) {
    $num = $idx + 1;
    $title = $school['name'];
    
    // Detect block from address
    $blockName = detectBlock($school['address'], $addressBlockMap);
    $blockId = getBlockId($blockName, $blockMap);
    
    // Extract pincode
    $pincode = extractPincode($school['address']);
    
    // Service level description
    $services = ($school['level'] === 'Senior Secondary') 
        ? 'CBSE Senior Secondary (Class 1-12)' 
        : 'CBSE Secondary (Class 1-10)';
    
    // Build description
    $desc = "{$title} is a CBSE-affiliated {$school['level']} school (Affiliation No. {$school['aff']}) located at {$school['address']}, Saran District, Bihar.";
    
    // Unique slug
    $slug = slugify($title) . '-' . $school['code'];
    
    // Check for duplicate by title
    $dupCheck = $db->prepare("SELECT id FROM listings WHERE slug = :slug LIMIT 1");
    $dupCheck->execute(['slug' => $slug]);
    if ($dupCheck->fetch()) {
        echo "  [{$num}] SKIP (duplicate slug): {$title}\n";
        $skipped++;
        continue;
    }
    
    // Prepare listing data
    $listingData = [
        'entity_type'      => 'SCHOOL_COLLEGE',
        'category_id'      => 6,
        'subcategory_id'   => 50,
        'block_id'         => $blockId,
        'panchayat_id'     => null,
        'village_id'       => null,
        'title'            => $title,
        'hindi_title'      => '',
        'slug'             => $slug,
        'contact_person'   => $school['principal'],
        'mobile'           => '0000000000',
        'mobile_visibility'=> 'HIDDEN',
        'whatsapp'         => '',
        'email'            => '',
        'website'          => $school['website'],
        'address'          => $school['address'],
        'pincode'          => $pincode,
        'map_link'         => '',
        'business_hours'   => '8:00 AM - 3:00 PM',
        'services'         => $services,
        'products'         => '',
        'gst_no'           => '',
        'udyam_no'         => '',
        'cin_no'           => '',
        'local_reg_no'     => "CBSE Aff. {$school['aff']} / Sch. Code {$school['code']}",
        'description'      => $desc,
        'cover_image'      => '',
        'is_verified'      => 'YES',
        'is_featured'      => 'NO',
        'status'           => 'ACTIVE',
        'plan_type'        => 'FREE',
        'plan_expires_at'  => null,
    ];
    
    try {
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
        $result = $stmt->execute($listingData);
        
        if ($result) {
            $newId = $db->lastInsertId();
            echo "  [{$num}] OK  #{$newId} | {$title} | {$blockName} | {$services}\n";
            $success++;
        } else {
            echo "  [{$num}] ERR (insert failed): {$title}\n";
            $errors++;
        }
    } catch (PDOException $e) {
        echo "  [{$num}] ERR: {$title} → " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=====================================================\n";
echo "  RESULTS: {$success} inserted, {$skipped} skipped, {$errors} errors\n";
echo "  Total: " . count($schools) . " schools processed\n";
echo "=====================================================\n";

// Cleanup temp files
@unlink(__DIR__ . '/read_excel.ps1');
@unlink(__DIR__ . '/Education_listings.csv');

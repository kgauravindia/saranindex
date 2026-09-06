<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();
if (!$pdo) {
    echo "Database connection failed.\n";
    exit(1);
}

// 1. Urban Local Bodies (10 ULBs)
$ulbs = [
    [
        'title' => 'Municipal Corporation Chapra',
        'hindi_title' => 'नगर निगम छपरा',
        'slug' => 'municipal-corporation-chapra',
        'category_id' => 11,
        'subcategory_id' => 87,
        'block_id' => 1, // Chapra
        'contact_person' => 'Municipal Commissioner / Mayor, Chhapra',
        'mobile' => '06152-243202',
        'whatsapp' => '',
        'email' => 'ulbchapranagarnigam@gmail.com',
        'website' => 'https://nagarseva.bihar.gov.in/udhd/Home.html',
        'address' => 'Municipal Corporation Office, Dahiyawan, Thanachowk, Chapra, Saran, Bihar - 841301',
        'pincode' => '841301',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=Municipal+Corporation+Chapra+Saran',
        'business_hours' => '10:00 AM - 5:00 PM',
        'services' => 'Urban Governance, Holding Tax, Trade License, Sanitation & Solid Waste Management, Birth & Death Certificate, Water Supply, Street Lighting, Civil Infrastructure',
        'description' => 'Official Urban Local Body (ULB) - Municipal Corporation of Chhapra (नगर निगम छपरा), Saran District, Bihar. Provides civic administration, property tax assessment, birth & death registration, trade licenses, building plan approval, urban sanitation, and public infrastructure development under Bihar Urban Development & Housing Department (UDHD).',
        'source' => 'saran.nic.in'
    ],
    [
        'title' => 'Nagar Panchayat Ekma',
        'hindi_title' => 'नगर पंचायत एकमा',
        'slug' => 'nagar-panchayat-ekma',
        'category_id' => 11,
        'subcategory_id' => 87,
        'block_id' => 10, // Ekma
        'contact_person' => 'Executive Officer / Chairman, Ekma',
        'mobile' => '9934058554',
        'whatsapp' => '9934058554',
        'email' => 'ekma.ulb@gmail.com',
        'website' => 'https://nagarseva.bihar.gov.in/udhd/Home.html',
        'address' => 'Nagar Panchayat Office, Ekma Bazar, Ekma, Saran, Bihar - 841208',
        'pincode' => '841208',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=Nagar+Panchayat+Ekma+Saran',
        'business_hours' => '10:00 AM - 5:00 PM',
        'services' => 'Civic Administration, Property & Holding Tax, Sanitation, Waste Management, Street Lights, Water Supply, Civil Infrastructure',
        'description' => 'Official Urban Local Body (ULB) - Nagar Panchayat Ekma (नगर पंचायत एकमा), Saran District, Bihar. Governs municipal amenities, civic cleanliness, trade licenses, property tax, and infrastructure in Ekma urban area.',
        'source' => 'saran.nic.in'
    ],
    [
        'title' => 'Nagar Panchayat Dighwara',
        'hindi_title' => 'नगर पंचायत दिघवारा',
        'slug' => 'nagar-panchayat-dighwara',
        'category_id' => 11,
        'subcategory_id' => 87,
        'block_id' => 7, // Dighwara
        'contact_person' => 'Executive Officer / Chairman, Dighwara',
        'mobile' => '06158-281233',
        'whatsapp' => '9431822430',
        'email' => 'dighwaranagarpanchayat@gmail.com',
        'website' => 'https://nagarseva.bihar.gov.in/udhd/Home.html',
        'address' => 'Nagar Panchayat Office, Main Road, Dighwara, Saran, Bihar - 841207',
        'pincode' => '841207',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=Nagar+Panchayat+Dighwara+Saran',
        'business_hours' => '10:00 AM - 5:00 PM',
        'services' => 'Civic Administration, Property & Holding Tax, Sanitation, Waste Management, Street Lights, Water Supply, Civil Infrastructure',
        'description' => 'Official Urban Local Body (ULB) - Nagar Panchayat Dighwara (नगर पंचायत दिघवारा), Saran District, Bihar. Manages civic services, municipal cleanliness, town planning, and public utilities in Dighwara.',
        'source' => 'saran.nic.in'
    ],
    [
        'title' => 'Nagar Panchayat Kopa',
        'hindi_title' => 'नगर पंचायत कोपा',
        'slug' => 'nagar-panchayat-kopa',
        'category_id' => 11,
        'subcategory_id' => 87,
        'block_id' => 17, // Jalalpur
        'contact_person' => 'Executive Officer / Chairman, Kopa',
        'mobile' => '06152-274040',
        'whatsapp' => '9431822446',
        'email' => 'npkopa@gmail.com',
        'website' => 'https://nagarseva.bihar.gov.in/udhd/Home.html',
        'address' => 'Nagar Panchayat Office, Kopa Bazar, Jalalpur, Saran, Bihar - 841213',
        'pincode' => '841213',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=Nagar+Panchayat+Kopa+Jalalpur+Saran',
        'business_hours' => '10:00 AM - 5:00 PM',
        'services' => 'Civic Administration, Property & Holding Tax, Sanitation, Waste Management, Street Lights, Water Supply, Civil Infrastructure',
        'description' => 'Official Urban Local Body (ULB) - Nagar Panchayat Kopa (नगर पंचायत कोपा), Jalalpur / Saran District, Bihar. Providing municipal administration, sanitation, street lighting, and citizen services.',
        'source' => 'saran.nic.in'
    ],
    [
        'title' => 'Nagar Panchayat Manjhi',
        'hindi_title' => 'नगर पंचायत मांझी',
        'slug' => 'nagar-panchayat-manjhi',
        'category_id' => 11,
        'subcategory_id' => 87,
        'block_id' => 14, // Manjhi
        'contact_person' => 'Executive Officer / Chairman, Manjhi',
        'mobile' => '06155-272375',
        'whatsapp' => '9431822441',
        'email' => 'npmanjhi@gmail.com',
        'website' => 'https://nagarseva.bihar.gov.in/udhd/Home.html',
        'address' => 'Nagar Panchayat Office, Manjhi, Saran, Bihar - 841313',
        'pincode' => '841313',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=Nagar+Panchayat+Manjhi+Saran',
        'business_hours' => '10:00 AM - 5:00 PM',
        'services' => 'Civic Administration, Property & Holding Tax, Sanitation, Waste Management, Street Lights, Water Supply, Civil Infrastructure',
        'description' => 'Official Urban Local Body (ULB) - Nagar Panchayat Manjhi (नगर पंचायत मांझी), Saran District, Bihar. Administers municipal governance, civic amenities, cleanliness, and public utilities in Manjhi.',
        'source' => 'saran.nic.in'
    ],
    [
        'title' => 'Nagar Panchayat Marhaura',
        'hindi_title' => 'नगर पंचायत मढ़ौरा',
        'slug' => 'nagar-panchayat-marhaura',
        'category_id' => 11,
        'subcategory_id' => 87,
        'block_id' => 2, // Madhaurah
        'contact_person' => 'Executive Officer / Chairman, Marhaura',
        'mobile' => '06159-231942',
        'whatsapp' => '9939254871',
        'email' => 'npmarhowrah@gmail.com',
        'website' => 'https://nagarseva.bihar.gov.in/udhd/Home.html',
        'address' => 'Nagar Panchayat Office, Marhaura, Saran, Bihar - 841418',
        'pincode' => '841418',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=Nagar+Panchayat+Office+Marhaura+Saran',
        'business_hours' => '10:00 AM - 5:00 PM',
        'services' => 'Civic Administration, Property & Holding Tax, Sanitation, Waste Management, Street Lights, Water Supply, Civil Infrastructure',
        'description' => 'Official Urban Local Body (ULB) - Nagar Panchayat Marhaura (नगर पंचायत मढ़ौरा), Saran District, Bihar. Responsible for municipal administration, holding tax collection, civil infrastructure, and sanitation in Marhaura.',
        'source' => 'saran.nic.in'
    ],
    [
        'title' => 'Nagar Panchayat Mashrakh',
        'hindi_title' => 'नगर पंचायत मशरक',
        'slug' => 'nagar-panchayat-mashrakh',
        'category_id' => 11,
        'subcategory_id' => 87,
        'block_id' => 19, // Mashrakh
        'contact_person' => 'Executive Officer / Chairman, Mashrakh',
        'mobile' => '06159-225002',
        'whatsapp' => '9931927729',
        'email' => 'npmashrakh@gmail.com',
        'website' => 'https://nagarseva.bihar.gov.in/udhd/Home.html',
        'address' => 'Nagar Panchayat Office, Mashrakh, Saran, Bihar - 841417',
        'pincode' => '841417',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=Nagar+Panchayat+Mashrakh+Saran',
        'business_hours' => '10:00 AM - 5:00 PM',
        'services' => 'Civic Administration, Property & Holding Tax, Sanitation, Waste Management, Street Lights, Water Supply, Civil Infrastructure',
        'description' => 'Official Urban Local Body (ULB) - Nagar Panchayat Mashrakh (नगर पंचायत मशरक), Saran District, Bihar. Governs civic infrastructure, waste management, municipal services, and developmental schemes in Mashrakh.',
        'source' => 'saran.nic.in'
    ],
    [
        'title' => 'Nagar Panchayat Parsa Bazar',
        'hindi_title' => 'नगर पंचायत परसा बाजार',
        'slug' => 'nagar-panchayat-parsa-bazar',
        'category_id' => 11,
        'subcategory_id' => 87,
        'block_id' => 6, // Parsa
        'contact_person' => 'Executive Officer / Chairman, Parsa Bazar',
        'mobile' => '06152-270568',
        'whatsapp' => '9431822436',
        'email' => 'npparsabajar@gmail.com',
        'website' => 'https://nagarseva.bihar.gov.in/udhd/Home.html',
        'address' => 'Nagar Panchayat Office, Parsa Bazar, Parsa, Saran, Bihar - 841219',
        'pincode' => '841219',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=Nagar+Panchayat+Parsa+Bazar+Parsa+Saran',
        'business_hours' => '10:00 AM - 5:00 PM',
        'services' => 'Civic Administration, Property & Holding Tax, Sanitation, Waste Management, Street Lights, Water Supply, Civil Infrastructure',
        'description' => 'Official Urban Local Body (ULB) - Nagar Panchayat Parsa Bazar (नगर पंचायत परसा बाजार), Saran District, Bihar. Handles civic administration, sanitation, property tax, and public utilities for the Parsa Bazar urban area.',
        'source' => 'saran.nic.in'
    ],
    [
        'title' => 'Nagar Panchayat Rivilganj',
        'hindi_title' => 'नगर पंचायत रिविलगंज',
        'slug' => 'nagar-panchayat-rivilganj',
        'category_id' => 11,
        'subcategory_id' => 87,
        'block_id' => 4, // Rivilganj
        'contact_person' => 'Executive Officer / Chairman, Rivilganj',
        'mobile' => '06152-271468',
        'whatsapp' => '9431822447',
        'email' => 'nagarpanchayatrivilganj@gmail.com',
        'website' => 'https://nagarseva.bihar.gov.in/udhd/Home.html',
        'address' => 'Nagar Panchayat Office, Rivilganj, Saran, Bihar - 841305',
        'pincode' => '841305',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=Nagar+Panchayat+Rivilganj+Saran',
        'business_hours' => '10:00 AM - 5:00 PM',
        'services' => 'Civic Administration, Property & Holding Tax, Sanitation, Waste Management, Street Lights, Water Supply, Civil Infrastructure',
        'description' => 'Official Urban Local Body (ULB) - Nagar Panchayat Rivilganj (नगर पंचायत रिविलगंज), Saran District, Bihar. Provides local municipal governance, road and drainage maintenance, garbage disposal, and tax assessment.',
        'source' => 'saran.nic.in'
    ],
    [
        'title' => 'Nagar Panchayat Sonpur',
        'hindi_title' => 'नगर पंचायत सोनपुर',
        'slug' => 'nagar-panchayat-sonpur',
        'category_id' => 11,
        'subcategory_id' => 87,
        'block_id' => 3, // Sonpur
        'contact_person' => 'Executive Officer / Chairman, Sonpur',
        'mobile' => '9006689078',
        'whatsapp' => '9006689078',
        'email' => 'npsonpur@gmail.com',
        'website' => 'https://nagarseva.bihar.gov.in/udhd/Home.html',
        'address' => 'Nagar Panchayat Office, Sonpur, Saran, Bihar - 841101',
        'pincode' => '841101',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=Nagar+Panchayat+Sonpur+Saran',
        'business_hours' => '10:00 AM - 5:00 PM',
        'services' => 'Civic Administration, Property & Holding Tax, Sanitation, Waste Management, Street Lights, Water Supply, Civil Infrastructure',
        'description' => 'Official Urban Local Body (ULB) - Nagar Panchayat Sonpur (नगर पंचायत सोनपुर), Saran District, Bihar. Oversees civic amenities, holding tax, town planning, cleanliness, and public utilities in Sonpur.',
        'source' => 'saran.nic.in'
    ]
];

// 2. All 39 Police Stations from saran.nic.in/police/
$police = [
    [
        'title' => 'Mufassil Police Station Chapra',
        'hindi_title' => 'मुफस्सिल थाना छपरा',
        'slug' => 'mufassil-police-station-chapra',
        'block_id' => 1, // Chapra
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822450',
        'whatsapp' => '9431822450',
        'phone_landline' => '06152-293977',
        'address' => 'Mufassil Police Station, Chapra Sadar, Saran, Bihar - 841301',
        'pincode' => '841301',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response, law & order, public safety, dispute resolution and crime investigation in Mufassil Chapra jurisdiction.'
    ],
    [
        'title' => 'Nagar Town Police Station Chapra',
        'hindi_title' => 'नगर थाना छपरा',
        'slug' => 'nagar-town-police-station-chapra',
        'block_id' => 1, // Chapra
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822451',
        'whatsapp' => '9431822451',
        'phone_landline' => '06152-232008',
        'address' => 'Town Police Station, Near Thanachowk, Main Road, Chapra, Saran, Bihar - 841301',
        'pincode' => '841301',
        'description' => 'Official Town Police Station (नगर थाना) under Saran Police, Bihar Police. 24x7 emergency response, urban policing, law enforcement, and crime investigation across Chapra town.'
    ],
    [
        'title' => 'Mashrakh Police Station',
        'hindi_title' => 'मशरक थाना',
        'slug' => 'mashrakh-police-station',
        'block_id' => 19, // Mashrakh
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9931927729',
        'whatsapp' => '9931927729',
        'phone_landline' => '06159-225002',
        'address' => 'Mashrakh Police Station, Mashrakh Bazar, Saran, Bihar - 841417',
        'pincode' => '841417',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response, law and order, crime prevention, and public assistance in Mashrakh Block.'
    ],
    [
        'title' => 'Ekma Police Station',
        'hindi_title' => 'एकमा थाना',
        'slug' => 'ekma-police-station',
        'block_id' => 10, // Ekma
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9934058554',
        'whatsapp' => '9934058554',
        'phone_landline' => '06152-265000',
        'address' => 'Ekma Police Station, Near Railway Station, Ekma, Saran, Bihar - 841208',
        'pincode' => '841208',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response, citizen safety, and law enforcement in Ekma jurisdiction.'
    ],
    [
        'title' => 'Marhaurah Police Station',
        'hindi_title' => 'मढ़ौरा थाना',
        'slug' => 'marhaurah-police-station',
        'block_id' => 2, // Madhaurah
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9939254871',
        'whatsapp' => '9939254871',
        'phone_landline' => '06159-231942',
        'address' => 'Marhaura Police Station, Main Road, Marhaura, Saran, Bihar - 841418',
        'pincode' => '841418',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response, industrial area security, and law & order in Marhaura Sub-division.'
    ],
    [
        'title' => 'Sonpur Police Station',
        'hindi_title' => 'सोनपुर थाना',
        'slug' => 'sonpur-police-station',
        'block_id' => 3, // Sonpur
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9006689078',
        'whatsapp' => '9006689078',
        'phone_landline' => '06158-221181',
        'address' => 'Sonpur Police Station, Sonpur, Saran, Bihar - 841101',
        'pincode' => '841101',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response, fair security, pilgrim assistance, and law enforcement in Sonpur Sub-division.'
    ],
    [
        'title' => 'Bhagwan Bazar Police Station',
        'hindi_title' => 'भगवान बाजार थाना',
        'slug' => 'bhagwan-bazar-police-station',
        'block_id' => 1, // Chapra
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822449',
        'whatsapp' => '9431822449',
        'phone_landline' => '06152-232181',
        'address' => 'Bhagwan Bazar Police Station, Near Chapra Junction, Chapra, Saran, Bihar - 841301',
        'pincode' => '841301',
        'description' => 'Official Police Station under Saran Police, Bihar Police. Serving Bhagwan Bazar, Chapra Junction railway area, and commercial hubs in Chapra.'
    ],
    [
        'title' => 'Doriganj Police Station',
        'hindi_title' => 'डोरीगंज थाना',
        'slug' => 'doriganj-police-station',
        'block_id' => 1, // Chapra
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822448',
        'whatsapp' => '9431822448',
        'phone_landline' => '06152-273824',
        'address' => 'Doriganj Police Station, Doriganj, Chapra, Saran, Bihar - 841211',
        'pincode' => '841211',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response, NH-19 highway patrol, riverbank security, and law enforcement in Doriganj.'
    ],
    [
        'title' => 'Khaira Police Station',
        'hindi_title' => 'खैरा थाना',
        'slug' => 'khaira-police-station',
        'block_id' => 18, // Nagra / Chapra
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822423',
        'whatsapp' => '9431822423',
        'phone_landline' => '06152-273317',
        'address' => 'Khaira Police Station, Khaira Bazar, Saran, Bihar - 841414',
        'pincode' => '841414',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response, rural policing, and crime prevention in Khaira.'
    ],
    [
        'title' => 'Nagra Police Station',
        'hindi_title' => 'नगरा थाना',
        'slug' => 'nagra-police-station',
        'block_id' => 18, // Nagra
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9570874452',
        'whatsapp' => '9570874452',
        'phone_landline' => '06155-266119',
        'address' => 'Nagra Police Station, Nagra, Saran, Bihar - 841442',
        'pincode' => '841442',
        'description' => 'Official Police Station under Saran Police, Bihar Police. Serving Nagra block with 24x7 emergency response and rural community policing.'
    ],
    [
        'title' => 'Jalalpur Police Station',
        'hindi_title' => 'जलालपुर थाना',
        'slug' => 'jalalpur-police-station',
        'block_id' => 17, // Jalalpur
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822445',
        'whatsapp' => '9431822445',
        'phone_landline' => '06155-268661',
        'address' => 'Jalalpur Police Station, Jalalpur Bazar, Saran, Bihar - 841412',
        'pincode' => '841412',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency policing, investigation, and crime control in Jalalpur Block.'
    ],
    [
        'title' => 'Rivilganj Police Station',
        'hindi_title' => 'रिविलगंज थाना',
        'slug' => 'rivilganj-police-station',
        'block_id' => 4, // Rivilganj
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822447',
        'whatsapp' => '9431822447',
        'phone_landline' => '06152-271468',
        'address' => 'Rivilganj Police Station, Semaria / Rivilganj, Saran, Bihar - 841305',
        'pincode' => '841305',
        'description' => 'Official Police Station under Saran Police, Bihar Police. Providing 24x7 law enforcement, river security, and public safety across Rivilganj.'
    ],
    [
        'title' => 'Kopa Police Station',
        'hindi_title' => 'कोपा थाना',
        'slug' => 'kopa-police-station',
        'block_id' => 17, // Jalalpur
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822446',
        'whatsapp' => '9431822446',
        'phone_landline' => '06152-274040',
        'address' => 'Kopa Police Station, Kopa Bazar, Saran, Bihar - 841213',
        'pincode' => '841213',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency helpline and crime prevention in Kopa and surrounding areas.'
    ],
    [
        'title' => 'Baniyapur Police Station',
        'hindi_title' => 'बनियापुर थाना',
        'slug' => 'baniyapur-police-station',
        'block_id' => 9, // Baniyapur
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822444',
        'whatsapp' => '9431822444',
        'phone_landline' => '06155-267782',
        'address' => 'Baniyapur Police Station, Baniyapur, Saran, Bihar - 841403',
        'pincode' => '841403',
        'description' => 'Official Police Station under Saran Police, Bihar Police. Serving Baniyapur block with 24x7 emergency response and rural security.'
    ],
    [
        'title' => 'Janta Bazar Police Station',
        'hindi_title' => 'जनता बाजार थाना',
        'slug' => 'janta-bazar-police-station',
        'block_id' => 13, // Lahladpur
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822426',
        'whatsapp' => '9431822426',
        'phone_landline' => '06155-261384',
        'address' => 'Janta Bazar Police Station, Janta Bazar, Lahladpur, Saran, Bihar - 841415',
        'pincode' => '841415',
        'description' => 'Official Police Station under Saran Police, Bihar Police. Providing 24x7 security and emergency law enforcement for Janta Bazar and Lahladpur Block.'
    ],
    [
        'title' => 'Rasulpur Police Station',
        'hindi_title' => 'रसूलपुर थाना',
        'slug' => 'rasulpur-police-station',
        'block_id' => 10, // Ekma
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822440',
        'whatsapp' => '9431822440',
        'phone_landline' => '06155-265385',
        'address' => 'Rasulpur Police Station, Rasulpur, Ekma, Saran, Bihar - 841204',
        'pincode' => '841204',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response and security in Rasulpur and Ekma jurisdiction.'
    ],
    [
        'title' => 'Manjhi Police Station',
        'hindi_title' => 'मांझी थाना',
        'slug' => 'manjhi-police-station',
        'block_id' => 14, // Manjhi
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822441',
        'whatsapp' => '9431822441',
        'phone_landline' => '06155-272375',
        'address' => 'Manjhi Police Station, Near Manjhi Ghat, Manjhi, Saran, Bihar - 841313',
        'pincode' => '841313',
        'description' => 'Official Police Station under Saran Police, Bihar Police. Border checkpoint security, riverbank patrol, and law enforcement in Manjhi.'
    ],
    [
        'title' => 'Daudpur Police Station',
        'hindi_title' => 'दाउदपुर थाना',
        'slug' => 'daudpur-police-station',
        'block_id' => 14, // Manjhi
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822443',
        'whatsapp' => '9431822443',
        'phone_landline' => '06155-264960',
        'address' => 'Daudpur Police Station, Daudpur Bazar, Saran, Bihar - 841205',
        'pincode' => '841205',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency assistance, highway patrol, and crime investigation in Daudpur.'
    ],
    [
        'title' => 'Garkha Police Station',
        'hindi_title' => 'गड़खा थाना',
        'slug' => 'garkha-police-station',
        'block_id' => 5, // Garkha
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822437',
        'whatsapp' => '9431822437',
        'phone_landline' => '06152-272747',
        'address' => 'Garkha Police Station, Garkha Bazar, Saran, Bihar - 841311',
        'pincode' => '841311',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response and comprehensive law enforcement across Garkha Block.'
    ],
    [
        'title' => 'Maker Police Station',
        'hindi_title' => 'मकेर थाना',
        'slug' => 'maker-police-station',
        'block_id' => 15, // Maker
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822435',
        'whatsapp' => '9431822435',
        'phone_landline' => '06152-285151',
        'address' => 'Maker Police Station, Maker, Saran, Bihar - 841214',
        'pincode' => '841214',
        'description' => 'Official Police Station under Saran Police, Bihar Police. Providing 24x7 citizen safety, dispute resolution, and patrolling in Maker Block.'
    ],
    [
        'title' => 'Amnour Police Station',
        'hindi_title' => 'अमनौर थाना',
        'slug' => 'amnour-police-station',
        'block_id' => 8, // Amnour
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822438',
        'whatsapp' => '9431822438',
        'phone_landline' => '06155-278200',
        'address' => 'Amnour Police Station, Amnour Bazar, Saran, Bihar - 841401',
        'pincode' => '841401',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 community safety, crime prevention, and law & order in Amnour Block.'
    ],
    [
        'title' => 'Bheldi Police Station',
        'hindi_title' => 'भेलदी थाना',
        'slug' => 'bheldi-police-station',
        'block_id' => 6, // Parsa / Garkha
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822424',
        'whatsapp' => '9431822424',
        'phone_landline' => '06159-275201',
        'address' => 'Bheldi Police Station, Bheldi Bazar, Saran, Bihar - 841402',
        'pincode' => '841402',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response, highway patrolling, and public safety in Bheldi.'
    ],
    [
        'title' => 'Taraiya Police Station',
        'hindi_title' => 'तरैया थाना',
        'slug' => 'taraiya-police-station',
        'block_id' => 11, // Taraiya
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822433',
        'whatsapp' => '9431822433',
        'phone_landline' => '06159-273385',
        'address' => 'Taraiya Police Station, Taraiya, Saran, Bihar - 841424',
        'pincode' => '841424',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response, rural security, and law enforcement across Taraiya Block.'
    ],
    [
        'title' => 'Panapur Police Station',
        'hindi_title' => 'पानापुर थाना',
        'slug' => 'panapur-police-station',
        'block_id' => 20, // Panapur
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822429',
        'whatsapp' => '9431822429',
        'phone_landline' => '06159-272333',
        'address' => 'Panapur Police Station, Panapur, Saran, Bihar - 841410',
        'pincode' => '841410',
        'description' => 'Official Police Station under Saran Police, Bihar Police. Providing 24x7 policing, crime prevention, and public grievance redressal in Panapur Block.'
    ],
    [
        'title' => 'Isuwapur Police Station',
        'hindi_title' => 'इसुआपुर थाना',
        'slug' => 'isuwapur-police-station',
        'block_id' => 12, // Isuwapur
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822432',
        'whatsapp' => '9431822432',
        'phone_landline' => '06158-279258',
        'address' => 'Isuwapur Police Station, Isuwapur Bazar, Saran, Bihar - 841411',
        'pincode' => '841411',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response and rural community safety in Isuwapur Block.'
    ],
    [
        'title' => 'Pahleja Outpost (O.P. Pahleja)',
        'hindi_title' => 'पहलेजा ओपी (पुलिस चौकी)',
        'slug' => 'op-pahleja-police-outpost',
        'block_id' => 3, // Sonpur
        'contact_person' => 'Outpost In-Charge (OP Prabhari)',
        'mobile' => '7277586846',
        'whatsapp' => '7277586846',
        'phone_landline' => '06158-222253',
        'address' => 'Pahleja Outpost (OP), Near Pahleja Ghat, Sonpur, Saran, Bihar - 841101',
        'pincode' => '841101',
        'description' => 'Official Police Outpost (ओपी) under Saran Police, Sonpur Sub-division. Riverbank vigilance, Ganga-Gandak confluence safety, and rapid local response.'
    ],
    [
        'title' => 'Hariharnath Outpost (O.P. Hariharnath)',
        'hindi_title' => 'हरिहरनाथ ओपी (पुलिस चौकी)',
        'slug' => 'op-hariharnath-police-outpost',
        'block_id' => 3, // Sonpur
        'contact_person' => 'Outpost In-Charge (OP Prabhari)',
        'mobile' => '9955119744',
        'whatsapp' => '9955119744',
        'phone_landline' => '06158-222280',
        'address' => 'Hariharnath Police Outpost (OP), Near Hariharnath Mandir, Sonpur, Saran, Bihar - 841101',
        'pincode' => '841101',
        'description' => 'Official Police Outpost (ओपी) under Saran Police, Sonpur. Temple campus security, pilgrim crowd management, and emergency assistance.'
    ],
    [
        'title' => 'Dariyapur Police Station',
        'hindi_title' => 'दरियापुर थाना',
        'slug' => 'dariyapur-police-station',
        'block_id' => 16, // Dariyapur
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822428',
        'whatsapp' => '9431822428',
        'phone_landline' => '06152-272273',
        'address' => 'Dariyapur Police Station, Rail Wheel Plant Road, Dariyapur, Saran, Bihar - 841221',
        'pincode' => '841221',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response, industrial area protection, and public security in Dariyapur Block.'
    ],
    [
        'title' => 'Naya Gaon Police Station',
        'hindi_title' => 'नयागांव थाना',
        'slug' => 'naya-gaon-police-station',
        'block_id' => 3, // Sonpur
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822424',
        'whatsapp' => '9431822424',
        'phone_landline' => '06158-277389',
        'address' => 'Naya Gaon Police Station, NH-19, Nayagaon, Saran, Bihar - 841217',
        'pincode' => '841217',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 highway patrolling, law and order, and emergency policing in Nayagaon.'
    ],
    [
        'title' => 'Dighwara Police Station',
        'hindi_title' => 'दिघवारा थाना',
        'slug' => 'dighwara-police-station',
        'block_id' => 7, // Dighwara
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822430',
        'whatsapp' => '9431822430',
        'phone_landline' => '06158-281233',
        'address' => 'Dighwara Police Station, Main Road, Dighwara, Saran, Bihar - 841207',
        'pincode' => '841207',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency helpline, highway safety, and law & order in Dighwara Block.'
    ],
    [
        'title' => 'Awatarnagar Police Station',
        'hindi_title' => 'अवतार नगर थाना',
        'slug' => 'awatarnagar-police-station',
        'block_id' => 5, // Garkha / Dighwara
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822427',
        'whatsapp' => '9431822427',
        'phone_landline' => '06158-273825',
        'address' => 'Awatarnagar Police Station, Awatarnagar, Saran, Bihar - 841218',
        'pincode' => '841218',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 citizen safety, highway patrol, and crime investigation in Awatarnagar.'
    ],
    [
        'title' => 'Derni Police Station',
        'hindi_title' => 'डेरनी थाना',
        'slug' => 'derni-police-station',
        'block_id' => 16, // Dariyapur
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9507705005',
        'whatsapp' => '9507705005',
        'phone_landline' => '06158-283590',
        'address' => 'Derni Police Station, Derni Bazar, Dariyapur, Saran, Bihar - 841215',
        'pincode' => '841215',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency response and rural community policing across Derni jurisdiction.'
    ],
    [
        'title' => 'Parsa Police Station',
        'hindi_title' => 'परसा थाना',
        'slug' => 'parsa-police-station',
        'block_id' => 6, // Parsa
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9431822436',
        'whatsapp' => '9431822436',
        'phone_landline' => '06152-270568',
        'address' => 'Parsa Police Station, Parsa Bazar, Saran, Bihar - 841219',
        'pincode' => '841219',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency law enforcement and crime prevention in Parsa Block.'
    ],
    [
        'title' => 'Sahajitpur Police Station',
        'hindi_title' => 'शहाजीतपुर थाना',
        'slug' => 'sahajitpur-police-station',
        'block_id' => 9, // Baniyapur
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9955763441',
        'whatsapp' => '9955763441',
        'phone_landline' => '',
        'address' => 'Sahajitpur Police Station, Sahajitpur, Baniyapur, Saran, Bihar - 841422',
        'pincode' => '841422',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency assistance, rural patrolling, and crime prevention in Sahajitpur.'
    ],
    [
        'title' => 'Gaura Police Station',
        'hindi_title' => 'गौरा थाना',
        'slug' => 'gaura-police-station',
        'block_id' => 2, // Madhaurah
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9471466567',
        'whatsapp' => '9471466567',
        'phone_landline' => '',
        'address' => 'Gaura Police Station, Gaura, Marhaura, Saran, Bihar - 841413',
        'pincode' => '841413',
        'description' => 'Official Police Station under Saran Police, Bihar Police. 24x7 emergency policing and crime investigation in Gaura area.'
    ],
    [
        'title' => 'Mahila Police Station Chapra',
        'hindi_title' => 'महिला थाना छपरा',
        'slug' => 'mahila-police-station-chapra',
        'block_id' => 1, // Chapra
        'contact_person' => 'Station House Officer (SHO) Mahila Thana',
        'mobile' => '9473090718',
        'whatsapp' => '9473090718',
        'phone_landline' => '06152-242300',
        'address' => 'Mahila Police Station, Police Line Campus, Chapra, Saran, Bihar - 841301',
        'pincode' => '841301',
        'description' => 'Dedicated Women Police Station (महिला थाना) under Saran Police. Dedicated to women safety, domestic violence prevention, counseling, and legal assistance across Saran District.'
    ],
    [
        'title' => 'SC/ST Police Station Chapra',
        'hindi_title' => 'अनुसूचित जाति / जनजाति (SC/ST) थाना छपरा',
        'slug' => 'sc-st-police-station-chapra',
        'block_id' => 1, // Chapra
        'contact_person' => 'Station House Officer (SHO) SC/ST Thana',
        'mobile' => '9304424966',
        'whatsapp' => '9304424966',
        'phone_landline' => '06152-245000',
        'address' => 'SC/ST Police Station, Police Line Campus, Chapra, Saran, Bihar - 841301',
        'pincode' => '841301',
        'description' => 'Special Police Station dedicated to protecting SC/ST community rights and fast-tracking grievance redressal and investigations under the POA Act across Saran District.'
    ],
    [
        'title' => 'Akilpur Police Station',
        'hindi_title' => 'अकिलपुर थाना',
        'slug' => 'akilpur-police-station',
        'block_id' => 3, // Sonpur Diara
        'contact_person' => 'Station House Officer (SHO)',
        'mobile' => '9608815136',
        'whatsapp' => '9608815136',
        'phone_landline' => '',
        'address' => 'Akilpur Police Station, Akilpur Diara, Sonpur, Saran, Bihar - 841101',
        'pincode' => '841101',
        'description' => 'Official Police Station under Saran Police, Bihar Police. Specialised in riverine diara policing, border security, and crime prevention in Akilpur.'
    ],
    [
        'title' => 'Traffic Police Station Chapra',
        'hindi_title' => 'यातायात थाना छपरा',
        'slug' => 'traffic-police-station-chapra',
        'block_id' => 1, // Chapra
        'contact_person' => 'Traffic DSP / Station House Officer',
        'mobile' => '8051333138',
        'whatsapp' => '8051333138',
        'phone_landline' => '06152-242000',
        'address' => 'Traffic Police Station, Municipal Chowk, Chapra, Saran, Bihar - 841301',
        'pincode' => '841301',
        'description' => 'Dedicated Traffic Police Station for Chapra City and Saran District. Overseeing traffic regulation, road safety enforcement, helmet & seatbelt checks, and VIP movement.'
    ]
];

// Execute Inserts / Updates
$insertedCount = 0;
$updatedCount = 0;

$stmtCheck = $pdo->prepare("SELECT id FROM listings WHERE slug = ?");
$stmtInsert = $pdo->prepare("
    INSERT INTO listings (
        category_id, subcategory_id, block_id, title, hindi_title, slug,
        contact_person, mobile, mobile_visibility, whatsapp, email, email_visibility,
        website, address, pincode, map_link, business_hours, services,
        description, is_verified, is_featured, status, plan_type, view_count, star_rating,
        created_at, updated_at, source, source_id
    ) VALUES (
        :category_id, :subcategory_id, :block_id, :title, :hindi_title, :slug,
        :contact_person, :mobile, 'PUBLIC', :whatsapp, :email, 'PUBLIC',
        :website, :address, :pincode, :map_link, :business_hours, :services,
        :description, 'YES', 'NO', 'ACTIVE', 'FREE', 0, 5.00,
        NOW(), NOW(), :source, 0
    )
");

$stmtUpdate = $pdo->prepare("
    UPDATE listings SET
        category_id = :category_id,
        subcategory_id = :subcategory_id,
        block_id = :block_id,
        title = :title,
        hindi_title = :hindi_title,
        contact_person = :contact_person,
        mobile = :mobile,
        mobile_visibility = 'PUBLIC',
        whatsapp = :whatsapp,
        email = :email,
        email_visibility = 'PUBLIC',
        website = :website,
        address = :address,
        pincode = :pincode,
        map_link = :map_link,
        business_hours = :business_hours,
        services = :services,
        description = :description,
        is_verified = 'YES',
        status = 'ACTIVE',
        updated_at = NOW(),
        source = :source
    WHERE id = :id
");

// Process ULBs
echo "--- Inserting / Updating ULBs ---\n";
foreach ($ulbs as $u) {
    $stmtCheck->execute([$u['slug']]);
    $existing = $stmtCheck->fetch();

    $data = [
        'category_id' => $u['category_id'],
        'subcategory_id' => $u['subcategory_id'],
        'block_id' => $u['block_id'],
        'title' => $u['title'],
        'hindi_title' => $u['hindi_title'],
        'slug' => $u['slug'],
        'contact_person' => $u['contact_person'],
        'mobile' => $u['mobile'],
        'whatsapp' => $u['whatsapp'],
        'email' => $u['email'],
        'website' => $u['website'],
        'address' => $u['address'],
        'pincode' => $u['pincode'],
        'map_link' => $u['map_link'],
        'business_hours' => $u['business_hours'],
        'services' => $u['services'],
        'description' => $u['description'],
        'source' => $u['source']
    ];

    if ($existing) {
        $data['id'] = $existing['id'];
        unset($data['slug']);
        $stmtUpdate->execute($data);
        echo "Updated ULB: {$u['title']} (ID: {$existing['id']})\n";
        $updatedCount++;
    } else {
        $stmtInsert->execute($data);
        $newId = $pdo->lastInsertId();
        echo "Inserted ULB: {$u['title']} (ID: {$newId})\n";
        $insertedCount++;
    }
}

// Process Police Stations
echo "\n--- Inserting / Updating Police Stations ---\n";
foreach ($police as $p) {
    $stmtCheck->execute([$p['slug']]);
    $existing = $stmtCheck->fetch();

    $services = '24x7 Emergency Response, Law & Order Maintenance, FIR Registration, Crime Prevention, Beat Patrolling, Citizen Safety, Police Verification';
    $mapLink = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($p['title'] . ' ' . $p['address']);

    $data = [
        'category_id' => 11, // Government
        'subcategory_id' => 92, // Police Stations
        'block_id' => $p['block_id'],
        'title' => $p['title'],
        'hindi_title' => $p['hindi_title'],
        'slug' => $p['slug'],
        'contact_person' => $p['contact_person'],
        'mobile' => $p['mobile'],
        'whatsapp' => $p['whatsapp'],
        'email' => '',
        'website' => 'https://saran.nic.in/police/',
        'address' => $p['address'],
        'pincode' => $p['pincode'],
        'map_link' => $mapLink,
        'business_hours' => '24 Hours (24x7)',
        'services' => $services,
        'description' => $p['description'],
        'source' => 'saran.nic.in'
    ];

    if ($existing) {
        $data['id'] = $existing['id'];
        unset($data['slug']);
        $stmtUpdate->execute($data);
        echo "Updated Police Station: {$p['title']} (ID: {$existing['id']})\n";
        $updatedCount++;
    } else {
        $stmtInsert->execute($data);
        $newId = $pdo->lastInsertId();
        echo "Inserted Police Station: {$p['title']} (ID: {$newId})\n";
        $insertedCount++;
    }
}

echo "\n============================================\n";
echo "Total Inserted: $insertedCount | Total Updated: $updatedCount\n";
echo "============================================\n";

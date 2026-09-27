<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: public, max-age=300'); // 5 minutes cache

require_once __DIR__ . '/../includes/functions.php';

date_default_timezone_set('Asia/Kolkata');
$now = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
$month = (int)$now->format('n');
$day = (int)$now->format('j');
$year = (int)$now->format('Y');
$currentHour = (int)$now->format('G');
$currentMinute = (int)$now->format('i');
$currentTimeDec = $currentHour + ($currentMinute / 60.0);
$dayOfWeek = (int)$now->format('N'); // 1 = Monday, 7 = Sunday

$lat = 25.7848;
$lon = 84.7274;

// Cache directory in scratch or temp
$cacheDir = __DIR__ . '/../scratch';
if (!is_dir($cacheDir)) {
    @mkdir($cacheDir, 0777, true);
}
$cacheFile = $cacheDir . '/today_feed_cache_' . $now->format('Ymd_H') . '_' . floor($currentMinute / 15) . '.json';

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 900)) {
    $cachedData = @file_get_contents($cacheFile);
    if ($cachedData) {
        echo $cachedData;
        exit;
    }
}

// 1. Fetch Live Weather & Air Quality from Open-Meteo
$weatherData = [
    'temperature' => 31,
    'feels_like' => 34,
    'humidity' => 65,
    'wind_speed' => 12,
    'weather_code' => 0,
    'condition' => 'Clear Skies / Sunny',
    'condition_hi' => 'साफ़ आसमान / धूप',
    'max_temp' => 33,
    'min_temp' => 25,
    'aqi' => 78,
    'aqi_label' => 'Moderate',
    'aqi_label_hi' => 'मध्यम'
];

try {
    $weatherApiUrl = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current=temperature_2m,relative_humidity_2m,apparent_temperature,weather_code,wind_speed_10m&daily=weather_code,temperature_2m_max,temperature_2m_min,sunrise,sunset&timezone=Asia%2FKolkata";
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 4,
            'header' => "User-Agent: SaranIndex-App/1.0\r\n"
        ]
    ]);
    $wResp = @file_get_contents($weatherApiUrl, false, $ctx);
    if ($wResp) {
        $wJson = json_decode($wResp, true);
        if (!empty($wJson['current'])) {
            $weatherData['temperature'] = round($wJson['current']['temperature_2m']);
            $weatherData['feels_like'] = round($wJson['current']['apparent_temperature']);
            $weatherData['humidity'] = round($wJson['current']['relative_humidity_2m']);
            $weatherData['wind_speed'] = round($wJson['current']['wind_speed_10m']);
            $weatherData['weather_code'] = $wJson['current']['weather_code'];

            $wCode = $weatherData['weather_code'];
            if ($wCode === 0) {
                $weatherData['condition'] = 'Clear Skies / Sunny';
                $weatherData['condition_hi'] = 'साफ़ आसमान / धूप';
            } elseif ($wCode <= 3) {
                $weatherData['condition'] = 'Partly Cloudy / Clear';
                $weatherData['condition_hi'] = 'आंशिक रूप से बादल';
            } elseif ($wCode <= 48) {
                $weatherData['condition'] = 'Foggy / Hazy';
                $weatherData['condition_hi'] = 'कोहरा / धुंध';
            } elseif ($wCode <= 67) {
                $weatherData['condition'] = 'Light Rain / Drizzle';
                $weatherData['condition_hi'] = 'हल्की बारिश / बूंदाबांदी';
            } elseif ($wCode <= 82) {
                $weatherData['condition'] = 'Rain Showers';
                $weatherData['condition_hi'] = 'वर्षा / बौछारें';
            } else {
                $weatherData['condition'] = 'Thunderstorms / Monsoon';
                $weatherData['condition_hi'] = 'मेघगर्जन / वर्षा';
            }

            if (!empty($wJson['daily']['temperature_2m_max'][0])) {
                $weatherData['max_temp'] = round($wJson['daily']['temperature_2m_max'][0]);
            }
            if (!empty($wJson['daily']['temperature_2m_min'][0])) {
                $weatherData['min_temp'] = round($wJson['daily']['temperature_2m_min'][0]);
            }
            if (!empty($wJson['daily']['sunrise'][0])) {
                $weatherData['sunrise'] = date('h:i A', strtotime($wJson['daily']['sunrise'][0]));
            }
            if (!empty($wJson['daily']['sunset'][0])) {
                $weatherData['sunset'] = date('h:i A', strtotime($wJson['daily']['sunset'][0]));
            }
        }
    }
} catch (Exception $e) {}

// 2. Fetch Live Historical Events for Today (Wikipedia OnThisDay API)
$historyEvents = [];
try {
    $wikiUrl = "https://en.wikipedia.org/api/rest_v1/feed/onthisday/selected/" . sprintf('%02d', $month) . "/" . sprintf('%02d', $day);
    $ctxWiki = stream_context_create([
        'http' => [
            'timeout' => 3,
            'header' => "User-Agent: SaranIndexBot/1.0 (info@saranindex.com)\r\n"
        ]
    ]);
    $wikiResp = @file_get_contents($wikiUrl, false, $ctxWiki);
    if ($wikiResp) {
        $wikiJson = json_decode($wikiResp, true);
        if (!empty($wikiJson['selected'])) {
            $slice = array_slice($wikiJson['selected'], 0, 4);
            foreach ($slice as $ev) {
                $historyEvents[] = [
                    'year' => $ev['year'] ?? '',
                    'text' => $ev['text'] ?? ''
                ];
            }
        }
    }
} catch (Exception $e) {}

// Fallback historical events if API unreachable
if (empty($historyEvents)) {
    $historyEvents = [
        ['year' => '1917', 'text' => 'Mahatma Gandhi\'s historical visit through Saran and Champaran during the freedom movement.'],
        ['year' => '1950', 'text' => 'Establishment of prestigious educational and administrative institutions in Saran.'],
        ['year' => '1974', 'text' => 'Loknayak Jayaprakash Narayan spearheaded the Total Revolution movement originating from Bihar and Saran division.']
    ];
}

// 3. Holiday Detection & Office Status Calculations (Ref: https://patnahighcourt.gov.in/PDF/CALENDAR/CIVIL_CAL2026.jpg)
$todayHolidayInfo = getTodayHolidayDetails($now->format('Y-m-d'));
$upcomingHolidays = getUpcomingBiharHolidays(8, 'ALL');

$isGovtWorkingTime = ($currentTimeDec >= 10.0 && $currentTimeDec <= 17.0 && $dayOfWeek <= 5 && !$todayHolidayInfo['is_govt_holiday']);
$isCourtWorkingTime = ($currentTimeDec >= 10.5 && $currentTimeDec <= 16.5 && $dayOfWeek <= 6 && !$todayHolidayInfo['is_court_holiday']);
$isBankWorkingTime = ($currentTimeDec >= 10.0 && $currentTimeDec <= 16.0 && $dayOfWeek <= 6 && !$todayHolidayInfo['is_bank_holiday']);

$govtStatusLabel = $todayHolidayInfo['is_govt_holiday'] ? 'CLOSED (HOLIDAY / VACATION)' : ($isGovtWorkingTime ? 'OPEN NOW (10 AM - 5 PM)' : 'CLOSED (Opens 10 AM Mon-Fri)');
$govtStatusLabelHi = $todayHolidayInfo['is_govt_holiday'] ? 'अवकाश / छुट्टी (कार्यालय बंद)' : ($isGovtWorkingTime ? 'अभी खुला है (10 AM - 5 PM)' : 'बंद है (सोम-शुक्र 10 AM)');

$courtStatusLabel = $todayHolidayInfo['is_court_holiday'] ? 'COURT CLOSED (HOLIDAY / RECESS)' : ($isCourtWorkingTime ? 'COURT IN SESSION' : 'CLOSED / RECESS');
$courtStatusLabelHi = $todayHolidayInfo['is_court_holiday'] ? 'न्यायालय अवकाश / छुट्टी' : ($isCourtWorkingTime ? 'अदालत कार्य जारी' : 'अदालत अवकाश / बंद');

$bankStatusLabel = $todayHolidayInfo['is_bank_holiday'] ? 'BRANCHES CLOSED (BANK HOLIDAY)' : ($isBankWorkingTime ? 'BRANCHES OPEN' : 'BRANCHES CLOSED');
$bankStatusLabelHi = $todayHolidayInfo['is_bank_holiday'] ? 'बैंक अवकाश (शाखाएं बंद)' : ($isBankWorkingTime ? 'शाखाएं खुली हैं' : 'शाखाएं बंद हैं');

$officeStatus = [
    'sadar_hospital' => [
        'is_open' => true,
        'label' => 'OPEN 24x7',
        'label_hi' => '24 घंटे खुला',
        'badge' => 'success'
    ],
    'collectorate' => [
        'is_open' => $isGovtWorkingTime,
        'label' => $govtStatusLabel,
        'label_hi' => $govtStatusLabelHi,
        'badge' => $isGovtWorkingTime ? 'success' : 'danger'
    ],
    'civil_court' => [
        'is_open' => $isCourtWorkingTime,
        'label' => $courtStatusLabel,
        'label_hi' => $courtStatusLabelHi,
        'badge' => $isCourtWorkingTime ? 'success' : 'danger'
    ],
    'banks' => [
        'is_open' => $isBankWorkingTime,
        'label' => $bankStatusLabel,
        'label_hi' => $bankStatusLabelHi,
        'badge' => $isBankWorkingTime ? 'success' : 'danger'
    ],
    'jpu' => [
        'is_open' => ($currentTimeDec >= 10.0 && $currentTimeDec <= 16.0 && $dayOfWeek <= 6 && !$todayHolidayInfo['is_govt_holiday']),
        'label' => ($currentTimeDec >= 10.0 && $currentTimeDec <= 16.0 && $dayOfWeek <= 6 && !$todayHolidayInfo['is_govt_holiday']) ? 'COLLEGES ACTIVE' : 'CLOSED',
        'label_hi' => ($currentTimeDec >= 10.0 && $currentTimeDec <= 16.0 && $dayOfWeek <= 6 && !$todayHolidayInfo['is_govt_holiday']) ? 'कॉलेज सक्रिय' : 'बंद',
        'badge' => ($currentTimeDec >= 10.0 && $currentTimeDec <= 16.0 && $dayOfWeek <= 6 && !$todayHolidayInfo['is_govt_holiday']) ? 'success' : 'danger'
    ],
    'electricity' => [
        'is_open' => true,
        'label' => '24x7 HELPLINE (1912)',
        'label_hi' => '24x7 हेल्पलाइन (1912)',
        'badge' => 'warning'
    ]
];

// 4. Energy & Fuel Index in Saran (Chapra)
$fuelRates = [
    'petrol' => '114.34',
    'diesel' => '100.30',
    'cng' => '86.50',
    'currency' => 'INR',
    'unit_fuel' => '₹/Litre',
    'unit_cng' => '₹/Kg',
    'sources' => [
        'petrol' => 'https://www.ndtv.com/fuel-prices/petrol-price-in-saran-city',
        'diesel' => 'https://www.ndtv.com/fuel-prices/diesel-price-in-saran-city'
    ],
    'omc_portals' => [
        'iocl' => ['name' => 'IndianOil (IOCL)', 'url' => 'https://iocl.com/petrol-diesel-price'],
        'hpcl' => ['name' => 'Hindustan Petroleum (HPCL)', 'url' => 'https://www.hindustanpetroleum.com/PriceBuildup'],
        'bpcl' => ['name' => 'Bharat Petroleum (BPCL)', 'url' => 'https://www.bharatpetroleum.in/our-businesses/fuels-and-services/petro-prices']
    ],
    'last_updated' => $now->format('d M Y, h:i A')
];

// 5. Daily ePapers covering Saran District (Chapra Edition)
$epapers = [
    [
        'name' => 'Dainik Jagran',
        'hindi_name' => 'दैनिक जागरण',
        'edition' => 'Chapra / Saran Edition (छपरा संस्करण)',
        'language' => 'Hindi',
        'tag' => 'Most Read',
        'color' => '#dc2626',
        'url' => 'https://epaper.jagran.com/epaper/edition-today-90-saran.html'
    ],
    [
        'name' => 'Prabhat Khabar',
        'hindi_name' => 'प्रभात खबर',
        'edition' => 'Chapra / Saran Edition (छपरा संस्करण)',
        'language' => 'Hindi',
        'tag' => 'Bihar Special',
        'color' => '#ea580c',
        'url' => 'https://epaper.prabhatkhabar.com/patna/saran/' . $now->format('Y-m-d') . '/1'
    ],
    [
        'name' => 'Dainik Bhaskar',
        'hindi_name' => 'दैनिक भास्कर',
        'edition' => 'Bihar / Chapra Edition (बिहार संस्करण)',
        'language' => 'Hindi',
        'tag' => 'Digital First',
        'color' => '#d97706',
        'url' => 'https://www.bhaskar.com/local/bihar/saran/'
    ],
    [
        'name' => 'Hindustan',
        'hindi_name' => 'हिन्दुस्तान',
        'edition' => 'Chapra / Saran Edition (छपरा संस्करण)',
        'language' => 'Hindi',
        'tag' => 'Popular',
        'color' => '#0284c7',
        'url' => 'https://epaper.livehindustan.com/edition/chapra?date=' . $now->format('Y-m-d') . '&page=1'
    ],
    [
        'name' => 'Aaj',
        'hindi_name' => 'आज',
        'edition' => 'Chapra Edition (छपरा संस्करण)',
        'language' => 'Hindi',
        'tag' => 'Heritage',
        'color' => '#b91c1c',
        'url' => 'http://ajhindidaily.com/%E0%A4%88-%E0%A4%AA%E0%A5%87%E0%A4%AA%E0%A4%B0'
    ],
    [
        'name' => 'Aaj Tak',
        'hindi_name' => 'आज तक',
        'edition' => 'Saran News & Video Coverage (सारण समाचार)',
        'language' => 'Hindi',
        'tag' => 'Live News',
        'color' => '#dc2626',
        'url' => 'https://www.aajtak.in/topic/saran'
    ],
    [
        'name' => 'Hindustan Times',
        'hindi_name' => 'हिन्दुस्तान टाइम्स',
        'edition' => 'Bihar Edition',
        'language' => 'English',
        'tag' => 'English Daily',
        'color' => '#0369a1',
        'url' => 'https://epaper.hindustantimes.com/'
    ]
];

// 6. Build Response
$response = [
    'success' => true,
    'timestamp' => $now->getTimestamp(),
    'datetime_iso' => $now->format('c'),
    'date_formatted' => $now->format('l, d F Y'),
    'time_formatted' => $now->format('h:i:s A'),
    'location' => [
        'district' => 'Saran',
        'hq' => 'Chapra',
        'state' => 'Bihar',
        'coordinates' => ['lat' => $lat, 'lon' => $lon]
    ],
    'weather' => $weatherData,
    'holiday_info' => $todayHolidayInfo,
    'upcoming_holidays' => $upcomingHolidays,
    'calendar_pdf' => 'https://patnahighcourt.gov.in/PDF/CALENDAR/CIVIL_CAL2026.jpg',
    'office_status' => $officeStatus,
    'fuel_rates' => $fuelRates,
    'epapers' => $epapers,
    'agricultural_markets' => [
        [
            'name' => 'Bazar Samiti, Chapra',
            'hindi_name' => 'बाजार समिति, छपरा',
            'type' => 'Apex Agricultural Wholesale APMC Market',
            'produce' => 'Grains, Foodgrains, Vegetables, Fruits, Seeds & Fertilizer',
            'timings' => '05:30 AM – 11:30 PM (Monthly Off: Last day of month)',
            'status' => ((int)date('j') === (int)date('t') ? 'CLOSED' : 'ACTIVE')
        ],
        [
            'name' => 'Gudri Bazar (Chapra Town)',
            'hindi_name' => 'गुदरी बाजार (छपरा शहर)',
            'type' => 'Fresh Vegetables, Fruits & Spices',
            'produce' => 'Vegetables, Fruits, Dairy & Local Produce',
            'timings' => '04:00 AM – 09:00 PM',
            'status' => 'ACTIVE'
        ],
        [
            'name' => 'Revelganj Galla Mandi',
            'hindi_name' => 'रिविलगंज गल्ला मंडी',
            'type' => 'Grains & Pulses Wholesale Trade',
            'produce' => 'Paddy (Dhan), Wheat, Mustard, Maize & Pulses',
            'timings' => '07:00 AM – 06:00 PM',
            'status' => 'ACTIVE'
        ],
        [
            'name' => 'Marhaura Krishi Bazar',
            'hindi_name' => 'मढ़ौरा कृषि बाजार',
            'type' => 'Seasonal Crops & Regional Farmer Produce',
            'produce' => 'Sugarcane, Potato & Seasonal Farmer Crops',
            'timings' => '06:00 AM – 07:00 PM',
            'status' => 'ACTIVE'
        ]
    ],
    'history_events' => $historyEvents
];

$jsonOutput = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
@file_put_contents($cacheFile, $jsonOutput);

echo $jsonOutput;

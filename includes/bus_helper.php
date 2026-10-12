<?php
/**
 * Bus Helper Functions for Saran Index
 * Supports Outward & Return trip calculations, bilingual place transliteration,
 * and time formatting.
 */

global $busPlaceMap;
$busPlaceMap = [
    'छपरा' => 'Chapra',
    'छपरा नेहरू चौक' => 'Chapra Nehru Chowk',
    'CHAPRA' => 'Chapra',
    'CHHAPRA' => 'Chapra',
    'तरैया' => 'Taraiya',
    'सिवान' => 'Siwan',
    'सीवान' => 'Siwan',
    'गोपालगंज' => 'Gopalganj',
    'मोतिहारी' => 'Motihari',
    'मोतीहारी' => 'Motihari',
    'बेतिया' => 'Bettiah',
    'मशरक' => 'Mashrakh',
    'मढ़ौरा' => 'Marhaura',
    'बसंतपुर' => 'Basantpur',
    'बसन्तपुर' => 'Basantpur',
    'BASANTPUR' => 'Basantpur',
    'डेरनी' => 'Derni',
    'बाबा बाजार' => 'Baba Bazar',
    'मलमलिया' => 'Malmaliya',
    'MALMALIYA' => 'Malmaliya',
    'जगदीशपुर' => 'Jagdishpur',
    'राजापट्टी' => 'Rajapatti',
    'राजपट्टी' => 'Rajapatti',
    'बढ़ैया' => 'Badhaiya',
    'गौशाला रोड सिवान' => 'Gaushala Road Siwan',
    'बृत्ति टोला' => 'Britti Tola',
    'बैरिया' => 'Bairia',
    'माँझी' => 'Manjhi',
    'परसा' => 'Parsa',
    'MAHMADPUR' => 'Mahmadpur',
    'महमदपुर' => 'Mahmadpur',
    'महम्मदपुर' => 'Mahmadpur',
    'मोहम्मदपुर' => 'Mohammadpur',
    'MADARPUR' => 'Madarpur',
    'BANIYAPUR' => 'Baniyapur',
    'बनियापुर' => 'Baniyapur',
    'अमनौर' => 'Amnour',
    'खैरा' => 'Khaira',
    'गड़खा' => 'Garkha',
    'गरखा' => 'Garkha',
    'एकमा' => 'Ekma',
    'दाउदपुर' => 'Daudpur',
    'रसुलपुर' => 'Rasulpur',
    'रसूलपुर' => 'Rasulpur',
    'मकेर' => 'Maker',
    'भेल्दी' => 'Bheldi',
    'मीरगंज' => 'Mirganj',
    'मिरगंज' => 'Mirganj',
    'हथुआ' => 'Hathwa',
    'बरौली' => 'Barauli',
    'बरहिमा' => 'Barhima',
    'दिघवा दुबौली' => 'Dighwa Dubauli',
    'तरवारा' => 'Tarwara',
    'भगवानपुर' => 'Bhagwanpur',
    'बड़हरियाँ' => 'Barharia',
    'बड़हरियों' => 'Barharia',
    'जादोपुर' => 'Jadopur',
    'मंगलपुर' => 'Mangalpur',
    'नौतन' => 'Nautan',
    'खजुरिया' => 'Khajuria',
    'खजूरिया' => 'Khajuria',
    'सुदामा मोड़' => 'Sudama Mor',
    'अफराद' => 'Aphrad',
    'अम्बारा' => 'Ambara',
    'इमली चौक' => 'Imli Chowk',
    'कटसा' => 'Katsa',
    'कसौधी' => 'Kasaudhi',
    'कुसौधी' => 'Kusaudhi',
    'कोयला देवा' => 'Koyla Deva',
    'खैरटिया' => 'Khairatia',
    'जटहा मोड़' => 'Jatha Mor',
    'जनता बाजार' => 'Janta Bazar',
    'झरखड़ा' => 'Jharkhara',
    'झरखरा' => 'Jharkhara',
    'टेहटी' => 'Tehti',
    'धर्मपरसा' => 'Dharmparsa',
    'पटेढ़ा' => 'Patedha',
    'बखरा' => 'Bakhra',
    'बनपुरा' => 'Banpura',
    'बलडीहा' => 'Baldiha',
    'मनोरपुर' => 'Manorpur',
    'रेवती' => 'Reoti',
    'रेवाघाट' => 'Rewaghat',
    'विशुनपुरा' => 'Vishunpura',
    'श्यामपुर' => 'Shyampur',
    'सुतिहार' => 'Sutihar',
    'सेनुआरी' => 'Senduari',
    'सेन्दुआरी' => 'Senduari',
    'सोन्हो' => 'Sonho',
    'पटना' => 'Patna',
    'हाजीपुर' => 'Hajipur',
    'सोनपुर' => 'Sonpur',
    'दिघवारा' => 'Dighwara',
    'रिविलगंज' => 'Rivilganj',
    'जलालपुर' => 'Jalalpur',
    'इसुआपुर' => 'Isuapur',
    'पनापुर' => 'Panapur',
];

/**
 * Get clean English transliterated place name.
 */
function getBusPlaceEnglish($name) {
    global $busPlaceMap;
    $t = trim($name ?? '');
    if (empty($t)) return '';
    if (isset($busPlaceMap[$t])) {
        return $busPlaceMap[$t];
    }
    // Partial search
    foreach ($busPlaceMap as $hi => $en) {
        if (mb_stripos($t, $hi) !== false) {
            return $en;
        }
    }
    return ucwords(strtolower($t));
}

/**
 * Convert 24-hour HH:MM string to 12-hour AM/PM format
 */
function formatBusTimeAMPM($timeStr) {
    if (empty($timeStr)) return '';
    $clean = trim($timeStr);
    $parts = preg_split('/[:.]/', $clean);
    if (count($parts) >= 2) {
        $h = intval($parts[0]);
        $m = intval($parts[1]);
        $ampm = $h >= 12 ? 'PM' : 'AM';
        $displayH = $h % 12;
        if ($displayH === 0) $displayH = 12;
        return sprintf('%02d:%02d %s', $displayH, $m, $ampm);
    }
    return $timeStr;
}

/**
 * Segment a timetable list into Outward and Return legs.
 * Leg names formatted as:
 *   Outward: [Origin] to [Destination] Bus (or [Origin] से [Destination] बस)
 *   Return: Return [Destination] to [Origin] (or वापसी [Destination] से [Origin])
 */
function segmentBusTimetableLegs($timetables, $origin, $destination, $lang = 'en') {
    global $busPlaceMap;
    $legs = [];
    $currentLeg = [];
    $isReturn = false;
    
    $cleanOrigin = trim(mb_strtolower($origin ?? ''));
    $cleanDest = trim(mb_strtolower($destination ?? ''));
    
    $origEn = getBusPlaceEnglish($origin);
    $destEn = getBusPlaceEnglish($destination);
    
    $total = count($timetables);
    for ($i = 0; $i < $total; $i++) {
        $stop = $timetables[$i];
        $stopName = trim($stop['stop_name'] ?? '');
        $cleanStopName = trim(mb_strtolower($stopName));
        
        $hasArr = !empty($stop['arrival_time_formatted']);
        $hasDep = !empty($stop['departure_time_formatted']);
        
        // Enhance stop with English & AM/PM
        $stop['stop_name_en'] = getBusPlaceEnglish($stopName);
        $stop['stop_name_hi'] = $stopName;
        $stop['arr_ampm'] = formatBusTimeAMPM($stop['arrival_time_formatted'] ?? '');
        $stop['dep_ampm'] = formatBusTimeAMPM($stop['departure_time_formatted'] ?? '');
        
        $stopEn = $stop['stop_name_en'];
        
        // Check turnaround point (matches both Devanagari variations and normalized English names)
        $isTurnaround = ($hasArr && $hasDep && (
            $cleanStopName === $cleanDest || 
            $cleanStopName === $cleanOrigin || 
            stripos($cleanStopName, $cleanDest) !== false ||
            stripos($cleanStopName, $cleanOrigin) !== false ||
            $stopEn === $destEn ||
            $stopEn === $origEn
        ));
        
        if ($isTurnaround) {
            // End current leg at this arrival
            $arrStop = $stop;
            $arrStop['departure_time_formatted'] = null;
            $arrStop['dep_ampm'] = null;
            $currentLeg[] = $arrStop;
            
            $legOrig = $currentLeg[0]['stop_name'] ?? $origin;
            $legDest = $stopName;
            $legOrigEn = getBusPlaceEnglish($legOrig);
            $legDestEn = getBusPlaceEnglish($legDest);
            
            if ($lang === 'en') {
                $title = $isReturn ? "Return {$legOrigEn} to {$legDestEn}" : "{$legOrigEn} to {$legDestEn} Bus";
                $badge = $isReturn ? "Return Journey" : "Outward Journey";
            } else {
                $title = $isReturn ? "वापसी {$legOrig} से {$legDest}" : "{$legOrig} से {$legDest} बस";
                $badge = $isReturn ? "वापसी यात्रा" : "जाने की यात्रा";
            }
            
            $legs[] = [
                'type' => $isReturn ? 'RETURN' : 'OUTWARD',
                'title' => $title,
                'badge' => $badge,
                'origin' => $legOrig,
                'destination' => $legDest,
                'origin_en' => $legOrigEn,
                'destination_en' => $legDestEn,
                'stops' => $currentLeg
            ];
            
            $isReturn = !$isReturn;
            
            // Start next leg from this departure
            $depStop = $stop;
            $depStop['arrival_time_formatted'] = null;
            $depStop['arr_ampm'] = null;
            $currentLeg = [$depStop];
        } else {
            $currentLeg[] = $stop;
            
            // Reached destination without departure time
            if (!$hasDep && $hasArr) {
                $legOrig = $currentLeg[0]['stop_name'] ?? $origin;
                $legDest = $stopName;
                $legOrigEn = getBusPlaceEnglish($legOrig);
                $legDestEn = getBusPlaceEnglish($legDest);
                
                if ($lang === 'en') {
                    $title = $isReturn ? "Return {$legOrigEn} to {$legDestEn}" : "{$legOrigEn} to {$legDestEn} Bus";
                    $badge = $isReturn ? "Return Journey" : "Outward Journey";
                } else {
                    $title = $isReturn ? "वापसी {$legOrig} से {$legDest}" : "{$legOrig} से {$legDest} बस";
                    $badge = $isReturn ? "वापसी यात्रा" : "जाने की यात्रा";
                }
                
                $legs[] = [
                    'type' => $isReturn ? 'RETURN' : 'OUTWARD',
                    'title' => $title,
                    'badge' => $badge,
                    'origin' => $legOrig,
                    'destination' => $legDest,
                    'origin_en' => $legOrigEn,
                    'destination_en' => $legDestEn,
                    'stops' => $currentLeg
                ];
                $currentLeg = [];
                $isReturn = !$isReturn;
            }
        }
    }
    
    // Any remaining stops
    if (!empty($currentLeg)) {
        $first = $currentLeg[0]['stop_name'] ?? $origin;
        $last = end($currentLeg)['stop_name'] ?? $destination;
        $firstEn = getBusPlaceEnglish($first);
        $lastEn = getBusPlaceEnglish($last);
        
        if ($lang === 'en') {
            $title = $isReturn ? "Return {$firstEn} to {$lastEn}" : "{$firstEn} to {$lastEn} Bus";
            $badge = $isReturn ? "Return Journey" : "Outward Journey";
        } else {
            $title = $isReturn ? "वापसी {$first} से {$last}" : "{$first} से {$last} बस";
            $badge = $isReturn ? "वापसी यात्रा" : "जाने की यात्रा";
        }
        
        $legs[] = [
            'type' => $isReturn ? 'RETURN' : 'OUTWARD',
            'title' => $title,
            'badge' => $badge,
            'origin' => $first,
            'destination' => $last,
            'origin_en' => $firstEn,
            'destination_en' => $lastEn,
            'stops' => $currentLeg
        ];
    }
    
    return $legs;
}

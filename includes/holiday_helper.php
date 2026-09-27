<?php
/**
 * 2026 Bihar Official Holiday Calendar Helper
 * Covers Govt of Bihar, Civil Courts (Saran/Chapra), and Banks (NI Act).
 * Reference: https://patnahighcourt.gov.in/PDF/CALENDAR/CIVIL_CAL2026.jpg
 */

function getBiharHolidays2026() {
    return [
        // January 2026
        ['date' => '2026-01-01', 'name' => 'New Year\'s Day', 'name_hi' => 'नव वर्ष दिवस', 'type' => ['COURT', 'BANK'], 'desc' => 'Civil Courts & Bank Holiday'],
        ['date' => '2026-01-05', 'name' => 'Guru Govind Singh Jayanti', 'name_hi' => 'गुरु गोविन्द सिंह जयन्ती', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => '359th Prakash Parv'],
        ['date' => '2026-01-14', 'name' => 'Makar Sankranti', 'name_hi' => 'मकर संक्रांति', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Harvest festival & Doriganj Sangam Snan'],
        ['date' => '2026-01-23', 'name' => 'Basant Panchami / Saraswati Puja', 'name_hi' => 'बसंत पंचमी / सरस्वती पूजा', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Goddess Saraswati Puja'],
        ['date' => '2026-01-24', 'name' => 'Karpoori Thakur Jayanti', 'name_hi' => 'जननायक कर्पूरी ठाकुर जयन्ती', 'type' => ['GOVT', 'COURT'], 'desc' => 'Bihar State Holiday'],
        ['date' => '2026-01-26', 'name' => 'Republic Day', 'name_hi' => 'गणतंत्र दिवस', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'National Holiday (Gazetted)'],

        // February 2026
        ['date' => '2026-02-01', 'name' => 'Sant Ravidas Jayanti', 'name_hi' => 'संत रविदास जयन्ती', 'type' => ['GOVT', 'COURT'], 'desc' => 'Gazetted Holiday'],
        ['date' => '2026-02-15', 'name' => 'Maha Shivratri', 'name_hi' => 'महाशिवरात्रि', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Silhauri & Dharmnath Mandir Fair'],
        ['date' => '2026-02-19', 'name' => 'Shab-e-Barat', 'name_hi' => 'शब-ए-बरात', 'type' => ['GOVT', 'COURT'], 'desc' => 'State Holiday'],

        // March 2026
        ['date' => '2026-03-03', 'name' => 'Holika Dahan', 'name_hi' => 'होलिका दहन', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Holi Festival Eve'],
        ['date' => '2026-03-04', 'name' => 'Holi (Dhulandi)', 'name_hi' => 'होली', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Festival of Colors'],
        ['date' => '2026-03-05', 'name' => 'Holi Vacation (Courts)', 'name_hi' => 'होली अवकाश (न्यायालय)', 'type' => ['COURT'], 'desc' => 'Civil Court Holi Break'],
        ['date' => '2026-03-20', 'name' => 'Eid-ul-Fitr (Ramazan)', 'name_hi' => 'ईद-उल-फितर (अलविदा जुमा)', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Eid Celebration'],
        ['date' => '2026-03-22', 'name' => 'Bihar Diwas', 'name_hi' => 'बिहार दिवस', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Statehood Day of Bihar (1912)'],
        ['date' => '2026-03-27', 'name' => 'Ram Navami', 'name_hi' => 'रामनवमी', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Lord Rama Janmotsav'],
        ['date' => '2026-03-31', 'name' => 'Mahavir Jayanti', 'name_hi' => 'महावीर जयन्ती', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Jain Tirthankar Janma Kalyanak'],

        // April 2026
        ['date' => '2026-04-01', 'name' => 'Annual Bank Account Closing', 'name_hi' => 'वार्षिक बैंक लेखा बंदी', 'type' => ['BANK'], 'desc' => 'Commercial Banks Closing Day (NI Act)'],
        ['date' => '2026-04-03', 'name' => 'Good Friday', 'name_hi' => 'गुड फ्राइडे', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Christian Sacred Holiday'],
        ['date' => '2026-04-14', 'name' => 'Dr. B. R. Ambedkar Jayanti', 'name_hi' => 'डॉ. बी.आर. अम्बेडकर जयन्ती', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Architect of Indian Constitution'],
        ['date' => '2026-04-23', 'name' => 'Veer Kunwar Singh Vijayotsav', 'name_hi' => 'वीर कुंवर सिंह विजयोत्सव', 'type' => ['GOVT', 'COURT'], 'desc' => '1857 Freedom Hero Celebration'],

        // May 2026
        ['date' => '2026-05-01', 'name' => 'May Day / Labour Day', 'name_hi' => 'मई दिवस / मजदूर दिवस', 'type' => ['COURT', 'BANK'], 'desc' => 'International Workers Day'],
        ['date' => '2026-05-27', 'name' => 'Eid-ul-Zoha (Bakrid)', 'name_hi' => 'ईद-उल-जुहा (बकरीद)', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Islamic Festival of Sacrifice'],
        ['date' => '2026-05-31', 'name' => 'Buddha Purnima', 'name_hi' => 'बुद्ध पूर्णिमा', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Lord Buddha Enlightenment Day'],

        // June 2026
        ['date' => '2026-06-01', 'name' => 'Civil Courts Annual Summer Vacation Begins', 'name_hi' => 'व्यवहार न्यायालय ग्रीष्मकालीन अवकाश प्रारंभ', 'type' => ['COURT'], 'desc' => 'Annual Summer Recess for Subordinate Courts'],
        ['date' => '2026-06-25', 'name' => 'Muharram (Tazia)', 'name_hi' => 'मोहर्रम (ताजिया)', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Youm-e-Ashura'],

        // July 2026
        ['date' => '2026-07-26', 'name' => 'Saran Index & OfferPlant Foundation Day', 'name_hi' => 'सारण इंडेक्स एवं ऑफ़रप्लांट स्थापना दिवस', 'type' => ['COMMEMORATIVE'], 'desc' => 'Incorporation Milestone (26 July 2017)'],

        // August 2026
        ['date' => '2026-08-15', 'name' => 'Independence Day', 'name_hi' => 'स्वतंत्रता दिवस', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'National Holiday (Gazetted)'],
        ['date' => '2026-08-25', 'name' => 'Chehallum', 'name_hi' => 'चेहल्लुम', 'type' => ['GOVT', 'COURT'], 'desc' => 'State Holiday'],
        ['date' => '2026-08-28', 'name' => 'Raksha Bandhan', 'name_hi' => 'रक्षा बंधन', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Festival of Sibling Bond'],

        // September 2026
        ['date' => '2026-09-04', 'name' => 'Shri Krishna Janmashtami', 'name_hi' => 'श्री कृष्ण जन्माष्टमी', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Lord Krishna Birth Celebration'],
        ['date' => '2026-09-17', 'name' => 'Vishwakarma Puja', 'name_hi' => 'विश्वकर्मा पूजा / अनंत चतुर्दशी', 'type' => ['GOVT', 'COURT'], 'desc' => 'Divine Architect Worship'],

        // October 2026
        ['date' => '2026-10-02', 'name' => 'Mahatma Gandhi Jayanti', 'name_hi' => 'महात्मा गांधी जयन्ती', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Father of the Nation Birthday'],
        ['date' => '2026-10-18', 'name' => 'Durga Puja (Maha Saptami)', 'name_hi' => 'दुर्गा पूजा (महा सप्तमी)', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Navratri Grand Puja / Ami Mandir'],
        ['date' => '2026-10-19', 'name' => 'Maha Ashtami / Navami', 'name_hi' => 'महाअष्टमी / महानवमी', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Durga Puja Celebrations'],
        ['date' => '2026-10-20', 'name' => 'Vijaya Dashami (Dussehra)', 'name_hi' => 'विजयादशमी (दशहरा)', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Victory of Good over Evil (Dussehra Mela)'],
        ['date' => '2026-10-21', 'name' => 'Durga Puja Vacation (Courts)', 'name_hi' => 'दुर्गा पूजा न्यायालय अवकाश', 'type' => ['COURT'], 'desc' => 'Civil Courts Autumn Vacation'],

        // November 2026
        ['date' => '2026-11-08', 'name' => 'Diwali (Deepawali)', 'name_hi' => 'दीपावली (दिवाली)', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Festival of Lights & Lakshmi Puja'],
        ['date' => '2026-11-09', 'name' => 'Govardhan Puja / Chitragupta Puja', 'name_hi' => 'गोवर्धन पूजा / चित्रगुप्त पूजा', 'type' => ['GOVT', 'COURT'], 'desc' => 'Annakoot & Chitragupta Puja'],
        ['date' => '2026-11-10', 'name' => 'Bhai Dooj', 'name_hi' => 'भैया दूज', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Bhaiya Dooj Festival'],
        ['date' => '2026-11-14', 'name' => 'Chhath Puja (Kharna)', 'name_hi' => 'छठ पूजा (खरना)', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Maha Parv of Sun Worship in Bihar'],
        ['date' => '2026-11-15', 'name' => 'Chhath Puja (Evening Arghya)', 'name_hi' => 'छठ पूजा (संध्या अर्घ्य)', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Offerings at Ganga & Gandak Ghats'],
        ['date' => '2026-11-16', 'name' => 'Chhath Puja (Morning Arghya)', 'name_hi' => 'छठ पूजा (प्रातः अर्घ्य / पारण)', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Completion of Chhath Vrat'],
        ['date' => '2026-11-24', 'name' => 'Guru Nanak Jayanti / Kartik Purnima', 'name_hi' => 'गुरु नानक जयन्ती / कार्तिक पूर्णिमा', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Sonpur Mela Inauguration & Holy Snan'],

        // December 2026
        ['date' => '2026-12-03', 'name' => 'Dr. Rajendra Prasad Jayanti', 'name_hi' => 'देशरत्न डॉ. राजेन्द्र प्रसाद जयन्ती', 'type' => ['GOVT', 'COURT'], 'desc' => 'First President of India (Saran Pride)'],
        ['date' => '2026-12-25', 'name' => 'Christmas Day', 'name_hi' => 'क्रिसमस दिवस', 'type' => ['GOVT', 'COURT', 'BANK'], 'desc' => 'Birth of Jesus Christ (Gazetted)'],
        ['date' => '2026-12-26', 'name' => 'Civil Courts Winter Vacation Begins', 'name_hi' => 'व्यवहार न्यायालय शीतकालीन अवकाश प्रारंभ', 'type' => ['COURT'], 'desc' => 'Winter Recess till New Year']
    ];
}

/**
 * Check if a date is a 2nd or 4th Saturday (Bank Holiday)
 */
function isSecondOrFourthSaturday($dateStr) {
    $ts = strtotime($dateStr);
    if ((int)date('N', $ts) !== 6) return false;
    $day = (int)date('j', $ts);
    // 1st sat: 1-7, 2nd sat: 8-14, 3rd sat: 15-21, 4th sat: 22-28, 5th sat: 29-31
    return ($day >= 8 && $day <= 14) || ($day >= 22 && $day <= 28);
}

/**
 * Get active holiday details for today
 */
function getTodayHolidayDetails($dateStr = null) {
    if (!$dateStr) {
        $dateStr = date('Y-m-d');
    }
    $holidays = getBiharHolidays2026();
    $found = [];

    foreach ($holidays as $h) {
        if ($h['date'] === $dateStr) {
            $found[] = $h;
        }
    }

    $isSunday = ((int)date('N', strtotime($dateStr)) === 7);
    $is2nd4thSat = isSecondOrFourthSaturday($dateStr);

    return [
        'date' => $dateStr,
        'is_sunday' => $isSunday,
        'is_second_fourth_saturday' => $is2nd4thSat,
        'holidays' => $found,
        'is_govt_holiday' => ($isSunday || !empty(array_filter($found, fn($x) => in_array('GOVT', $x['type'])))),
        'is_court_holiday' => ($isSunday || !empty(array_filter($found, fn($x) => in_array('COURT', $x['type'])))),
        'is_bank_holiday' => ($isSunday || $is2nd4thSat || !empty(array_filter($found, fn($x) => in_array('BANK', $x['type']))))
    ];
}

/**
 * Get list of upcoming holidays
 */
function getUpcomingBiharHolidays($limit = 8, $typeFilter = 'ALL') {
    $today = date('Y-m-d');
    $holidays = getBiharHolidays2026();
    $upcoming = [];

    foreach ($holidays as $h) {
        if ($h['date'] >= $today) {
            if ($typeFilter === 'ALL' || in_array($typeFilter, $h['type'])) {
                $h['days_left'] = round((strtotime($h['date']) - strtotime($today)) / 86400);
                $upcoming[] = $h;
                if (count($upcoming) >= $limit) break;
            }
        }
    }

    return $upcoming;
}

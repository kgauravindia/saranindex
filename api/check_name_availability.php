<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$rawName = isset($_GET['name']) ? trim($_GET['name']) : (isset($_POST['name']) ? trim($_POST['name']) : '');
$checkType = isset($_GET['type']) ? trim($_GET['type']) : (isset($_POST['type']) ? trim($_POST['type']) : 'all');
$itemKey = isset($_GET['item']) ? trim($_GET['item']) : (isset($_POST['item']) ? trim($_POST['item']) : '');

// Clean name for usernames/domains (letters, numbers, hyphens/underscores)
$cleanName = strtolower(trim(preg_replace('/[^a-zA-Z0-9_-]/', '', ltrim($rawName, '@'))));

if (empty($cleanName)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please enter a valid brand or business name.'
    ]);
    exit;
}

// 10 Selected Top Domain TLDs
$domainList = [
    'com' => ['tld' => '.com', 'name' => 'Commercial (Global)', 'category' => 'Popular'],
    'in' => ['tld' => '.in', 'name' => 'India Official', 'category' => 'Country'],
    'org' => ['tld' => '.org', 'name' => 'Organization / NGO', 'category' => 'Popular'],
    'co_in' => ['tld' => '.co.in', 'name' => 'Commercial India', 'category' => 'Country'],
    'net' => ['tld' => '.net', 'name' => 'Network / Tech', 'category' => 'Popular'],
    'info' => ['tld' => '.info', 'name' => 'Information / Portal', 'category' => 'General'],
    'biz' => ['tld' => '.biz', 'name' => 'Business / Enterprise', 'category' => 'General'],
    'online' => ['tld' => '.online', 'name' => 'Modern Online Brand', 'category' => 'Modern'],
    'store' => ['tld' => '.store', 'name' => 'E-Commerce & Retail', 'category' => 'Modern'],
    'org_in' => ['tld' => '.org.in', 'name' => 'Indian Organizations', 'category' => 'Country'],
    'ai' => ['tld' => '.ai', 'name' => 'Artificial Intelligence & Tech', 'category' => 'Trending'],
    'ai_in' => ['tld' => '.ai.in', 'name' => 'Indian AI & Tech Brand', 'category' => 'Country']
];

// 10 Major Social Media Platforms
$socialList = [
    'facebook' => [
        'name' => 'Facebook',
        'icon' => 'bi-facebook',
        'color' => '#1877f2',
        'bg_subtle' => '#e7f3ff',
        'url_pattern' => 'https://www.facebook.com/{name}',
        'register_url' => 'https://www.facebook.com/'
    ],
    'x' => [
        'name' => 'X (Twitter)',
        'icon' => 'bi-twitter-x',
        'color' => '#000000',
        'bg_subtle' => '#f3f4f6',
        'url_pattern' => 'https://x.com/{name}',
        'register_url' => 'https://x.com/signup'
    ],
    'instagram' => [
        'name' => 'Instagram',
        'icon' => 'bi-instagram',
        'color' => '#e1306c',
        'bg_subtle' => '#fdf2f8',
        'url_pattern' => 'https://www.instagram.com/{name}/',
        'register_url' => 'https://www.instagram.com/accounts/emailsignup/'
    ],
    'youtube' => [
        'name' => 'YouTube',
        'icon' => 'bi-youtube',
        'color' => '#ff0000',
        'bg_subtle' => '#fef2f2',
        'url_pattern' => 'https://www.youtube.com/@{name}',
        'register_url' => 'https://www.youtube.com/'
    ],
    'telegram' => [
        'name' => 'Telegram',
        'icon' => 'bi-telegram',
        'color' => '#229ed9',
        'bg_subtle' => '#e0f2fe',
        'url_pattern' => 'https://t.me/{name}',
        'register_url' => 'https://telegram.org/'
    ],
    'linkedin' => [
        'name' => 'LinkedIn',
        'icon' => 'bi-linkedin',
        'color' => '#0a66c2',
        'bg_subtle' => '#eff6ff',
        'url_pattern' => 'https://www.linkedin.com/in/{name}',
        'register_url' => 'https://www.linkedin.com/signup'
    ],
    'github' => [
        'name' => 'GitHub',
        'icon' => 'bi-github',
        'color' => '#24292e',
        'bg_subtle' => '#f3f4f6',
        'url_pattern' => 'https://github.com/{name}',
        'register_url' => 'https://github.com/join'
    ],
    'pinterest' => [
        'name' => 'Pinterest',
        'icon' => 'bi-pinterest',
        'color' => '#e60023',
        'bg_subtle' => '#fef2f2',
        'url_pattern' => 'https://www.pinterest.com/{name}/',
        'register_url' => 'https://www.pinterest.com/'
    ],
    'reddit' => [
        'name' => 'Reddit',
        'icon' => 'bi-reddit',
        'color' => '#ff4500',
        'bg_subtle' => '#fff1ee',
        'url_pattern' => 'https://www.reddit.com/user/{name}',
        'register_url' => 'https://www.reddit.com/register/'
    ],
    'threads' => [
        'name' => 'Threads',
        'icon' => 'bi-threads',
        'color' => '#000000',
        'bg_subtle' => '#f3f4f6',
        'url_pattern' => 'https://www.threads.net/@{name}',
        'register_url' => 'https://www.threads.net/'
    ],
    'blogger' => [
        'name' => 'Blogger',
        'icon' => 'bi-newspaper',
        'color' => '#f57c00',
        'bg_subtle' => '#fff7ed',
        'url_pattern' => 'https://{name}.blogspot.com',
        'register_url' => 'https://www.blogger.com/'
    ],
    'medium' => [
        'name' => 'Medium',
        'icon' => 'bi-medium',
        'color' => '#12100e',
        'bg_subtle' => '#f3f4f6',
        'url_pattern' => 'https://medium.com/@{name}',
        'register_url' => 'https://medium.com/m/signin'
    ]
];

// Helper to check domain DNS status
function checkDomainDNS($domain) {
    if (empty($domain)) return 'error';
    
    // Check DNS Records (A, AAAA, MX, NS, CNAME)
    $hasA = @checkdnsrr($domain, 'A');
    $hasNS = @checkdnsrr($domain, 'NS');
    $hasMX = @checkdnsrr($domain, 'MX');
    $hasAAAA = @checkdnsrr($domain, 'AAAA');
    $hasCNAME = @checkdnsrr($domain, 'CNAME');

    if ($hasA || $hasNS || $hasMX || $hasAAAA || $hasCNAME) {
        return 'taken';
    }
    return 'available';
}

// Helper to check Social Media Username Availability
function checkSocialPlatform($key, $name) {
    $timeout = 2.5;

    // 1. Instagram Check
    if ($key === 'instagram') {
        $ch = curl_init("https://www.instagram.com/" . urlencode($name) . "/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $title = preg_match('/<title>(.*?)<\/title>/i', $res, $m) ? trim($m[1]) : '';
        $isTaken = ($code === 200 && (strpos($title, '@') !== false || strpos($title, 'photos and videos') !== false || (strcasecmp($title, 'Instagram') !== 0 && !empty($title))));
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 2. Facebook Check
    if ($key === 'facebook') {
        $ch = curl_init("https://www.facebook.com/" . urlencode($name));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $ogTitle = preg_match('/<meta property="og:title" content="(.*?)"/i', $res, $m) ? trim($m[1]) : '';
        $title = preg_match('/<title>(.*?)<\/title>/i', $res, $m) ? trim($m[1]) : '';
        $isTaken = (!empty($ogTitle) && strcasecmp($ogTitle, 'Facebook') !== 0 && strpos($ogTitle, 'Page Not Found') === false) 
                || (!empty($title) && strcasecmp($title, 'Facebook') !== 0 && strpos($title, 'Page Not Found') === false && strpos($title, 'Log in') === false);
        
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 3. LinkedIn Check (Personal Profile or Company Page)
    if ($key === 'linkedin') {
        // Test personal profile /in/
        $ch = curl_init("https://www.linkedin.com/in/" . urlencode($name));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $title = preg_match('/<title>(.*?)<\/title>/i', $res, $m) ? trim($m[1]) : '';
        $isTaken = ($code === 200 && strpos($title, 'LinkedIn') !== false && strpos($title, 'Page not found') === false && strpos($title, 'Join LinkedIn') === false && strcasecmp($title, 'LinkedIn') !== 0);

        // If not found in personal, check company page
        if (!$isTaken) {
            $chComp = curl_init("https://www.linkedin.com/company/" . urlencode($name));
            curl_setopt($chComp, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chComp, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($chComp, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
            curl_setopt($chComp, CURLOPT_TIMEOUT, 2);
            curl_setopt($chComp, CURLOPT_SSL_VERIFYPEER, false);
            $resComp = curl_exec($chComp);
            $codeComp = curl_getinfo($chComp, CURLINFO_HTTP_CODE);
            curl_close($chComp);

            $titleComp = preg_match('/<title>(.*?)<\/title>/i', $resComp, $m) ? trim($m[1]) : '';
            if ($codeComp === 200 && strpos($titleComp, 'LinkedIn') !== false && strpos($titleComp, 'Page not found') === false && strpos($titleComp, 'Join LinkedIn') === false && strcasecmp($titleComp, 'LinkedIn') !== 0) {
                $isTaken = true;
            }
        }

        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 4. Threads Check
    if ($key === 'threads') {
        $ch = curl_init("https://www.threads.net/@" . urlencode($name));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $rawOgTitle = preg_match('/<meta property="og:title" content="(.*?)"/i', $res, $m) ? trim($m[1]) : '';
        $rawTitle = preg_match('/<title>(.*?)<\/title>/i', $res, $m) ? trim($m[1]) : '';
        $ogTitle = html_entity_decode($rawOgTitle, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $title = html_entity_decode($rawTitle, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $isTaken = ($code === 200 && (
            strpos($ogTitle, 'Say more') !== false ||
            strpos($title, 'Say more') !== false ||
            strpos($ogTitle, '(@' . $name . ')') !== false ||
            strpos($title, '(@' . $name . ')') !== false ||
            strpos($res, '"username":"' . $name . '"') !== false ||
            strpos($res, '"user_id"') !== false
        ) && strpos($ogTitle, 'Log in') === false && strpos($title, 'Log in') === false);

        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 5. Reddit Check
    if ($key === 'reddit') {
        $ch = curl_init("https://www.reddit.com/user/" . urlencode($name) . "/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $ogTitle = preg_match('/<meta property="og:title" content="(.*?)"/i', $res, $m) ? trim($m[1]) : '';
        $title = preg_match('/<title>(.*?)<\/title>/i', $res, $m) ? trim($m[1]) : '';
        $isTaken = (strpos($ogTitle, 'Reddit profile') !== false || strpos($title, '(u/') !== false);
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 6. YouTube Check
    if ($key === 'youtube') {
        $ch = curl_init("https://www.youtube.com/@" . urlencode($name));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $title = preg_match('/<title>(.*?)<\/title>/i', $res, $m) ? trim($m[1]) : '';
        $isTaken = ($code === 200 && strpos($title, '- YouTube') !== false && strpos($title, '404') === false);
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 7. Telegram Check
    if ($key === 'telegram') {
        $ch = curl_init("https://t.me/" . urlencode($name));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $isTaken = ($res && strpos($res, '<div class="tgme_page_title"') !== false && strpos($res, 'tgme_page_extra') !== false);
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 8. Pinterest Check
    if ($key === 'pinterest') {
        $ch = curl_init("https://www.pinterest.com/" . urlencode($name) . "/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $title = preg_match('/<title>(.*?)<\/title>/i', $res, $m) ? trim($m[1]) : '';
        $isTaken = ($code === 200 && strpos($title, 'Pinterest') !== false && strpos($title, 'User not found') === false && !empty($title));
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 9. GitHub Check
    if ($key === 'github') {
        $ch = curl_init("https://api.github.com/users/" . urlencode($name));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SaranIndex-NameCheck/1.0');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $isTaken = ($code === 200);
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 10. X (Twitter) Check
    if ($key === 'x') {
        $ch = curl_init("https://twitter.com/" . urlencode($name));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $isTaken = ($code === 200);
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 11. Blogger Check
    if ($key === 'blogger') {
        $ch = curl_init("https://" . urlencode($name) . ".blogspot.com");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $title = preg_match('/<title>(.*?)<\/title>/i', $res, $m) ? trim($m[1]) : '';
        $isTaken = ($code === 200 || $code === 301 || $code === 302 || ($code !== 404 && strpos($title, 'Blog not found') === false && strpos($title, 'Blog has been removed') === false && !empty($title)));

        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 12. Medium Check
    if ($key === 'medium') {
        $ch = curl_init("https://medium.com/feed/@" . urlencode($name));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $isTaken = ($code === 200 && strpos($res, '<rss') !== false);
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    return ['state' => 'available', 'available' => true, 'message' => 'Available'];
}

// Helper to check Saran Index DB availability
function checkSaranIndexAvailability($name) {
    $db = getDB();
    if (!$db) return ['handle_available' => true, 'slug_available' => true];

    $formattedHandle = '@' . strtolower($name);
    $plainHandle = strtolower($name);
    
    $handleTaken = false;
    $slugTaken = false;

    try {
        $stmt = $db->prepare("SELECT id FROM users WHERE LOWER(username_handle) = :h1 OR LOWER(username_handle) = :h2 LIMIT 1");
        $stmt->execute(['h1' => $formattedHandle, 'h2' => $plainHandle]);
        if ($stmt->fetch()) {
            $handleTaken = true;
        }

        $lStmt = $db->prepare("SELECT id FROM listings WHERE LOWER(slug) = :s1 LIMIT 1");
        $lStmt->execute(['s1' => $plainHandle]);
        if ($lStmt->fetch()) {
            $slugTaken = true;
        }
    } catch (PDOException $e) {
        error_log("checkSaranIndexAvailability error: " . $e->getMessage());
    }

    return [
        'handle' => $formattedHandle,
        'handle_available' => !$handleTaken,
        'slug_available' => !$slugTaken,
        'saran_url' => 'saranindex.com/' . $formattedHandle
    ];
}

// Single item check (for progressive fast AJAX)
if (!empty($itemKey)) {
    // 1. Domain single item check
    if (isset($domainList[$itemKey])) {
        $tld = $domainList[$itemKey]['tld'];
        $domainName = $cleanName . $tld;
        $status = checkDomainDNS($domainName);
        
        echo json_encode([
            'status' => 'success',
            'type' => 'domain',
            'key' => $itemKey,
            'name' => $domainName,
            'tld' => $tld,
            'state' => $status,
            'available' => ($status === 'available'),
            'website_url' => 'https://' . $domainName,
            'whois_url' => 'https://www.whois.com/whois/' . urlencode($domainName),
            'buy_url_godaddy' => 'https://www.godaddy.com/domainsearch/find?checkAvail=1&domainToCheck=' . urlencode($domainName),
            'buy_url_namecheap' => 'https://www.namecheap.com/domains/registration/results/?domain=' . urlencode($domainName),
            'buy_url_hostinger' => 'https://www.hostinger.in/domain-name-search?domain=' . urlencode($domainName)
        ]);
        exit;
    }

    // 2. Social single item check
    if (isset($socialList[$itemKey])) {
        $soc = $socialList[$itemKey];
        $profileUrl = str_replace('{name}', urlencode($cleanName), $soc['url_pattern']);
        $socCheck = checkSocialPlatform($itemKey, $cleanName);

        echo json_encode([
            'status' => 'success',
            'type' => 'social',
            'key' => $itemKey,
            'name' => $soc['name'],
            'profile_url' => $profileUrl,
            'state' => $socCheck['state'],
            'available' => $socCheck['available'],
            'message' => $socCheck['message'],
            'register_url' => $soc['register_url']
        ]);
        exit;
    }

    // 3. Saran Index check
    if ($itemKey === 'saranindex') {
        $saranCheck = checkSaranIndexAvailability($cleanName);
        echo json_encode(array_merge(['status' => 'success', 'type' => 'saranindex'], $saranCheck));
        exit;
    }
}

// Bulk Check (Returns all initial structures for UI rendering)
$domainsResult = [];
foreach ($domainList as $key => $d) {
    $domainName = $cleanName . $d['tld'];
    $domainsResult[$key] = [
        'key' => $key,
        'domain' => $domainName,
        'tld' => $d['tld'],
        'category' => $d['category'],
        'label' => $d['name'],
        'state' => 'pending',
        'buy_url_godaddy' => 'https://www.godaddy.com/domainsearch/find?checkAvail=1&domainToCheck=' . urlencode($domainName),
        'buy_url_namecheap' => 'https://www.namecheap.com/domains/registration/results/?domain=' . urlencode($domainName),
        'buy_url_hostinger' => 'https://www.hostinger.in/domain-name-search?domain=' . urlencode($domainName)
    ];
}

$socialResult = [];
foreach ($socialList as $key => $s) {
    $profileUrl = str_replace('{name}', urlencode($cleanName), $s['url_pattern']);
    $socialResult[$key] = [
        'key' => $key,
        'name' => $s['name'],
        'icon' => $s['icon'],
        'color' => $s['color'],
        'bg_subtle' => $s['bg_subtle'],
        'profile_url' => $profileUrl,
        'register_url' => $s['register_url'],
        'state' => 'pending'
    ];
}

$saranResult = checkSaranIndexAvailability($cleanName);

echo json_encode([
    'status' => 'success',
    'search_name' => $cleanName,
    'saranindex' => $saranResult,
    'domains' => $domainsResult,
    'socials' => $socialResult
]);

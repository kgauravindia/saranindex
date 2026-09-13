<?php
// Suppress warnings/notices to prevent JSON payload corruption on production servers
@ini_set('display_errors', '0');
@error_reporting(0);
if (!ob_get_level()) {
    ob_start();
}

// Set CORS and JSON headers for universal browser compatibility
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

if (isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) === 'OPTIONS') {
    http_response_code(200);
    if (ob_get_length()) ob_clean();
    exit;
}

// Safely load configuration
try {
    @require_once __DIR__ . '/../config/config.php';
    @require_once __DIR__ . '/../config/db.php';
    @require_once __DIR__ . '/../includes/functions.php';
} catch (Throwable $e) {
    // Graceful fallback if database or config is partially unreachable
}

// Reset error display again in case config.php enabled it
@ini_set('display_errors', '0');
@error_reporting(0);

$rawName = isset($_GET['name']) ? trim($_GET['name']) : (isset($_POST['name']) ? trim($_POST['name']) : '');
$checkType = isset($_GET['type']) ? trim($_GET['type']) : (isset($_POST['type']) ? trim($_POST['type']) : 'all');
$itemKey = isset($_GET['item']) ? trim($_GET['item']) : (isset($_POST['item']) ? trim($_POST['item']) : '');

// Clean name for usernames/domains (letters, numbers, hyphens/underscores)
$cleanName = strtolower(trim(preg_replace('/[^a-zA-Z0-9_-]/', '', ltrim($rawName, '@'))));

if (empty($cleanName)) {
    if (ob_get_length()) ob_clean();
    echo json_encode([
        'status' => 'error',
        'message' => 'Please enter a valid brand or business name.'
    ]);
    exit;
}

// 12 Selected Top Domain TLDs
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

// 12 Major Social Media Platforms
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
        'url_pattern' => 'https://www.linkedin.com/company/{name}',
        'register_url' => 'https://www.linkedin.com/company/setup/new/'
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
        'url_pattern' => 'https://in.pinterest.com/{name}/',
        'register_url' => 'https://in.pinterest.com/'
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

// Robust Multi-tier Domain DNS Checker (works on local and online servers)
function checkDomainDNS($domain) {
    if (empty($domain)) return 'error';
    
    // Tier 1: Native PHP checkdnsrr
    if (function_exists('checkdnsrr')) {
        $hasNS = @checkdnsrr($domain, 'NS');
        $hasA = @checkdnsrr($domain, 'A');
        $hasMX = @checkdnsrr($domain, 'MX');
        $hasAAAA = @checkdnsrr($domain, 'AAAA');
        $hasSOA = @checkdnsrr($domain, 'SOA');
        if ($hasNS || $hasA || $hasMX || $hasAAAA || $hasSOA) {
            return 'taken';
        }
    }

    // Tier 2: gethostbyname
    $ip = @gethostbyname($domain);
    if ($ip && $ip !== $domain) {
        return 'taken';
    }

    // Tier 3: Fast Google DNS over HTTPS (DoH) fallback for online environments
    if (function_exists('curl_init')) {
        $ch = curl_init("https://dns.google/resolve?name=" . urlencode($domain) . "&type=A");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code === 200 && $res) {
            $json = @json_decode($res, true);
            if (isset($json['Status']) && $json['Status'] === 0 && !empty($json['Answer'])) {
                return 'taken';
            }
            if (isset($json['Status']) && $json['Status'] === 3) {
                // NXDOMAIN -> Domain definitely does not exist
                return 'available';
            }
        }

        // Tier 4: Cloudflare DoH Fallback
        $ch2 = curl_init("https://cloudflare-dns.com/dns-query?name=" . urlencode($domain) . "&type=A");
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, ['Accept: application/dns-json']);
        curl_setopt($ch2, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $res2 = curl_exec($ch2);
        $code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
        curl_close($ch2);

        if ($code2 === 200 && $res2) {
            $json2 = @json_decode($res2, true);
            if (isset($json2['Status']) && $json2['Status'] === 0 && !empty($json2['Answer'])) {
                return 'taken';
            }
            if (isset($json2['Status']) && $json2['Status'] === 3) {
                return 'available';
            }
        }
    }

    return 'available';
}

// Robust Social Media Username Checker
function checkSocialPlatform($key, $name) {
    $timeout = 3.5;

    // Helper cURL wrapper
    $execCurl = function($url, $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36') use ($timeout) {
        if (!function_exists('curl_init')) return ['code' => 0, 'body' => '', 'url' => ''];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (!ini_get('open_basedir')) {
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        @curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
        return ['code' => $code, 'body' => $body ?: '', 'url' => $finalUrl];
    };

    // 1. Instagram Check (Robust OpenGraph & Title Inspection)
    if ($key === 'instagram') {
        $res = $execCurl("https://www.instagram.com/" . urlencode($name) . "/", 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)');
        if ($res['code'] === 404) {
            return ['state' => 'available', 'available' => true, 'message' => 'Available'];
        }
        $title = preg_match('/<title>(.*?)<\/title>/i', $res['body'], $m) ? trim($m[1]) : '';
        $decoded = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $ogTitle = preg_match('/<meta property="og:title" content="(.*?)"/i', $res['body'], $mOg) ? trim($mOg[1]) : '';
        $ogDesc = preg_match('/<meta property="og:description" content="(.*?)"/i', $res['body'], $mDesc) ? trim($mDesc[1]) : '';
        
        $isTaken = ($res['code'] === 200 && (
            !empty($ogTitle) ||
            !empty($ogDesc) ||
            stripos($decoded, '@' . $name) !== false ||
            stripos($decoded, 'photos and videos') !== false ||
            stripos($decoded, 'Followers') !== false ||
            (stripos($decoded, 'Instagram') !== false && strcasecmp(trim($decoded), 'Instagram') !== 0 && stripos($decoded, 'Login') === false && stripos($decoded, 'Page not found') === false)
        ));
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 2. Facebook Check (Dual-tier: Public Graph & Web Hit)
    if ($key === 'facebook') {
        // Tier 1: Fast Graph Public Endpoint
        $gRes = $execCurl("https://graph.facebook.com/" . urlencode($name) . "/picture?type=normal");
        if ($gRes['code'] === 200 || $gRes['code'] === 302) {
            return ['state' => 'taken', 'available' => false, 'message' => 'Taken'];
        }

        // Tier 2: Public OpenGraph and Web Profile Hit
        $res = $execCurl("https://www.facebook.com/" . urlencode($name), 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)');
        if ($res['code'] === 404) {
            return ['state' => 'available', 'available' => true, 'message' => 'Available'];
        }
        $ogTitle = preg_match('/<meta property="og:title" content="(.*?)"/i', $res['body'], $m) ? trim($m[1]) : '';
        $title = preg_match('/<title>(.*?)<\/title>/i', $res['body'], $m) ? trim($m[1]) : '';
        $decoded = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $isTaken = ($res['code'] === 200 && (
            (!empty($ogTitle) && strcasecmp(trim($ogTitle), 'Facebook') !== 0 && stripos($ogTitle, 'Page Not Found') === false && stripos($ogTitle, 'Log in') === false) ||
            (!empty($decoded) && strcasecmp(trim($decoded), 'Facebook') !== 0 && stripos($decoded, 'Page Not Found') === false && stripos($decoded, 'Log in') === false)
        ));
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 3. LinkedIn Check (Company / Brand Page)
    if ($key === 'linkedin') {
        $res = $execCurl("https://www.linkedin.com/company/" . urlencode($name));
        if ($res['code'] === 404) {
            return ['state' => 'available', 'available' => true, 'message' => 'Available'];
        }
        $title = preg_match('/<title>(.*?)<\/title>/i', $res['body'], $m) ? trim($m[1]) : '';
        $isTaken = ($res['code'] === 200 && strpos($title, 'LinkedIn') !== false && strpos($title, 'Page not found') === false && strcasecmp($title, 'LinkedIn') !== 0 && !empty($title));
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 4. Threads Check
    if ($key === 'threads') {
        $res = $execCurl("https://www.threads.net/@" . urlencode($name), 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)');
        if ($res['code'] === 404) {
            return ['state' => 'available', 'available' => true, 'message' => 'Available'];
        }
        $title = preg_match('/<title>(.*?)<\/title>/i', $res['body'], $m) ? trim($m[1]) : '';
        $decodedTitle = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $isTaken = ($res['code'] === 200 && (
            stripos($decodedTitle, '(@' . $name . ')') !== false || 
            stripos($decodedTitle, 'Say more') !== false || 
            stripos($res['body'], '"username":"' . $name . '"') !== false ||
            (stripos($decodedTitle, 'Threads') !== false && stripos($decodedTitle, 'Log in') === false && stripos($decodedTitle, 'Page not found') === false && strcasecmp(trim($decodedTitle), 'Threads') !== 0)
        ));
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 5. Reddit Check
    if ($key === 'reddit') {
        $res = $execCurl("https://www.reddit.com/user/" . urlencode($name) . "/about.json");
        if ($res['code'] === 404) {
            return ['state' => 'available', 'available' => true, 'message' => 'Available'];
        }
        if ($res['code'] === 200 && strpos($res['body'], '"name"') !== false) {
            return ['state' => 'taken', 'available' => false, 'message' => 'Taken'];
        }
        return ['state' => 'available', 'available' => true, 'message' => 'Available'];
    }

    // 6. YouTube Check
    if ($key === 'youtube') {
        $res = $execCurl("https://www.youtube.com/@" . urlencode($name));
        if ($res['code'] === 404) {
            return ['state' => 'available', 'available' => true, 'message' => 'Available'];
        }
        $title = preg_match('/<title>(.*?)<\/title>/i', $res['body'], $m) ? trim($m[1]) : '';
        $isTaken = ($res['code'] === 200 && strpos($title, '- YouTube') !== false && strpos($title, '404') === false);
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 7. Telegram Check
    if ($key === 'telegram') {
        $res = $execCurl("https://t.me/" . urlencode($name));
        $isTaken = ($res['body'] && strpos($res['body'], '<div class="tgme_page_title"') !== false && strpos($res['body'], 'tgme_page_extra') !== false);
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 8. Pinterest Check (Fast & 100% Reliable RSS Endpoint)
    if ($key === 'pinterest') {
        $res = $execCurl("https://in.pinterest.com/" . urlencode($name) . "/feed.rss");
        if ($res['code'] === 404) {
            return ['state' => 'available', 'available' => true, 'message' => 'Available'];
        }
        $isTaken = ($res['code'] === 200 && strpos($res['body'], '<rss') !== false);
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 9. GitHub Check
    if ($key === 'github') {
        $res = $execCurl("https://api.github.com/users/" . urlencode($name), 'SaranIndex-NameChecker/1.0');
        if ($res['code'] === 404) {
            return ['state' => 'available', 'available' => true, 'message' => 'Available'];
        }
        return [
            'state' => ($res['code'] === 200) ? 'taken' : 'available',
            'available' => ($res['code'] !== 200),
            'message' => ($res['code'] === 200) ? 'Taken' : 'Available'
        ];
    }

    // 10. X (Twitter) Check
    if ($key === 'x') {
        $res = $execCurl("https://x.com/" . urlencode($name));
        if ($res['code'] === 404) {
            return ['state' => 'available', 'available' => true, 'message' => 'Available'];
        }
        $isTaken = ($res['code'] === 200);
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 11. Blogger Check (Direct blog response check)
    if ($key === 'blogger') {
        $res = $execCurl("https://" . urlencode($name) . ".blogspot.com");
        $isTaken = ($res['code'] === 200);
        return [
            'state' => $isTaken ? 'taken' : 'available',
            'available' => !$isTaken,
            'message' => $isTaken ? 'Taken' : 'Available'
        ];
    }

    // 12. Medium Check
    if ($key === 'medium') {
        $res = $execCurl("https://medium.com/feed/@" . urlencode($name));
        $isTaken = ($res['code'] === 200 && strpos($res['body'], '<rss') !== false);
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
    $formattedHandle = '@' . strtolower($name);
    $plainHandle = strtolower($name);
    $handleTaken = false;
    $slugTaken = false;

    try {
        if (function_exists('getDB')) {
            $db = getDB();
            if ($db) {
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
            }
        }
    } catch (Throwable $e) {
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
        
        if (ob_get_length()) ob_clean();
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

        if (ob_get_length()) ob_clean();
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
        if (ob_get_length()) ob_clean();
        echo json_encode(array_merge(['status' => 'success', 'type' => 'saranindex'], $saranCheck));
        exit;
    }
}

// Bulk check fallback
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

if (ob_get_length()) ob_clean();
echo json_encode([
    'status' => 'success',
    'search_name' => $cleanName,
    'saranindex' => $saranResult,
    'domains' => $domainsResult,
    'socials' => $socialResult
]);

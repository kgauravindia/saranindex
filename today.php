<?php
require_once __DIR__ . '/includes/functions.php';

// SEO Meta Information
$page_title = "Today in Saran District (आज सारण में क्या है) – Live Weather, Panchang, News, Mandi & Fuel Rates | Saran Index";
$meta_description = "Complete daily bulletin for Saran District (Chapra, Bihar) today: Live weather forecast, air quality (AQI), Hindu panchang & tithi, sunrise/sunset, fuel prices, emergency contacts, today in history, and block spotlight across all 20 blocks.";
$meta_keywords = "Today in Saran, What is today in Saran District, Chapra News Today, Saran Weather Today, Chapra Panchang Today, Saran Fuel Prices, Chapra Mandi Rates, Saran Emergency Numbers, 20 Blocks of Saran";
$canonical_url = BASE_URL . "today";

// Date & Time Variables (Asia/Kolkata)
date_default_timezone_set('Asia/Kolkata');
$currentTimestamp = time();
$currentDateFormatted = date('l, d F Y');
$currentDateHi = date('d') . ' ' . [
    'January' => 'जनवरी', 'February' => 'फ़रवरी', 'March' => 'मार्च', 'April' => 'अप्रैल',
    'May' => 'मई', 'June' => 'जून', 'July' => 'जुलाई', 'August' => 'अगस्त',
    'September' => 'सितंबर', 'October' => 'अक्टूबर', 'November' => 'नवंबर', 'December' => 'दिसंबर'
][date('F')] . ' ' . date('Y');

$currentDayHi = [
    'Monday' => 'सोमवार', 'Tuesday' => 'मंगलवार', 'Wednesday' => 'बुधवार',
    'Thursday' => 'गुरुवार', 'Friday' => 'शुक्रवार', 'Saturday' => 'शनिवार', 'Sunday' => 'रविवार'
][date('l')];

$dayOfMonth = (int)date('j');
$monthNum = (int)date('n');
$yearNum = (int)date('Y');
$currentHour = (int)date('G');
$currentMinute = (int)date('i');
$currentTimeDecimal = $currentHour + ($currentMinute / 60);

// Vikram & Saka Samvat estimations
$vikramSamvat = $yearNum + 57;
$sakaSamvat = $yearNum - 78;

// All 20 Blocks of Saran
$allBlocks = getBlocks();
if (empty($allBlocks)) {
    $allBlocks = [
        ['id' => 1, 'block_name' => 'Chapra Sadar', 'hindi_name' => 'छपरा सदर', 'slug' => 'chapra-sadar', 'pincode' => '841301', 'total_panchayats' => 22],
        ['id' => 2, 'block_name' => 'Marhaura', 'hindi_name' => 'मढ़ौरा', 'slug' => 'marhaura', 'pincode' => '841418', 'total_panchayats' => 18],
        ['id' => 3, 'block_name' => 'Sonepur', 'hindi_name' => 'सोनपुर', 'slug' => 'sonepur', 'pincode' => '841101', 'total_panchayats' => 23],
        ['id' => 4, 'block_name' => 'Revelganj', 'hindi_name' => 'रिविलगंज', 'slug' => 'revelganj', 'pincode' => '841305', 'total_panchayats' => 14],
        ['id' => 5, 'block_name' => 'Garkha', 'hindi_name' => 'गरखा', 'slug' => 'garkha', 'pincode' => '841311', 'total_panchayats' => 20],
        ['id' => 6, 'block_name' => 'Parsa', 'hindi_name' => 'परसा', 'slug' => 'parsa', 'pincode' => '841219', 'total_panchayats' => 16],
        ['id' => 7, 'block_name' => 'Dighwara', 'hindi_name' => 'दिघवारा', 'slug' => 'dighwara', 'pincode' => '841207', 'total_panchayats' => 12],
        ['id' => 8, 'block_name' => 'Amanour', 'hindi_name' => 'अमनौर', 'slug' => 'amanour', 'pincode' => '841401', 'total_panchayats' => 18],
        ['id' => 9, 'block_name' => 'Baniapur', 'hindi_name' => 'बनियापुर', 'slug' => 'baniapur', 'pincode' => '841403', 'total_panchayats' => 24],
        ['id' => 10, 'block_name' => 'Ekma', 'hindi_name' => 'एकमा', 'slug' => 'ekma', 'pincode' => '841208', 'total_panchayats' => 19],
        ['id' => 11, 'block_name' => 'Taraiya', 'hindi_name' => 'तरैया', 'slug' => 'taraiya', 'pincode' => '841424', 'total_panchayats' => 13],
        ['id' => 12, 'block_name' => 'Isuapur', 'hindi_name' => 'इसुआपुर', 'slug' => 'isuapur', 'pincode' => '841407', 'total_panchayats' => 11],
        ['id' => 13, 'block_name' => 'Lahladpur', 'hindi_name' => 'लहलादपुर', 'slug' => 'lahladpur', 'pincode' => '841408', 'total_panchayats' => 9],
        ['id' => 14, 'block_name' => 'Manjhi', 'hindi_name' => 'मांझी', 'slug' => 'manjhi', 'pincode' => '841313', 'total_panchayats' => 24],
        ['id' => 15, 'block_name' => 'Maker', 'hindi_name' => 'मेकर', 'slug' => 'maker', 'pincode' => '841215', 'total_panchayats' => 10],
        ['id' => 16, 'block_name' => 'Dariapur', 'hindi_name' => 'दरियापुर', 'slug' => 'dariapur', 'pincode' => '841221', 'total_panchayats' => 21],
        ['id' => 17, 'block_name' => 'Jalalpur', 'hindi_name' => 'जलालपुर', 'slug' => 'jalalpur', 'pincode' => '841412', 'total_panchayats' => 15],
        ['id' => 18, 'block_name' => 'Nagra', 'hindi_name' => 'नगरा', 'slug' => 'nagra', 'pincode' => '841442', 'total_panchayats' => 12],
        ['id' => 19, 'block_name' => 'Mashrakh', 'hindi_name' => 'मशरख', 'slug' => 'mashrakh', 'pincode' => '841417', 'total_panchayats' => 16],
        ['id' => 20, 'block_name' => 'Panapur', 'hindi_name' => 'पन्नापुर', 'slug' => 'panapur', 'pincode' => '841410', 'total_panchayats' => 11]
    ];
}

// Block of the day rotation
$dayOfYear = (int)date('z');
$blockOfTheDay = $allBlocks[$dayOfYear % count($allBlocks)];

// 2026 Official Holiday Calendar Data (Bihar Govt, Civil Court Saran, Banks)
// Reference: https://patnahighcourt.gov.in/PDF/CALENDAR/CIVIL_CAL2026.jpg
$todayHolidayInfo = getTodayHolidayDetails();
$upcomingHolidays = getUpcomingBiharHolidays(12, 'ALL');
$upcomingGovtHolidays = getUpcomingBiharHolidays(8, 'GOVT');
$upcomingCourtHolidays = getUpcomingBiharHolidays(8, 'COURT');
$upcomingBankHolidays = getUpcomingBiharHolidays(8, 'BANK');
$calendarPdfUrl = 'https://patnahighcourt.gov.in/PDF/CALENDAR/CIVIL_CAL2026.jpg';

// Query featured / verified listings from DB
$featuredListings = [];
$db = getDB();
if ($db) {
    try {
        $stmt = $db->query("SELECT l.*, c.name as category_name, b.block_name 
                            FROM listings l 
                            LEFT JOIN categories c ON l.category_id = c.id 
                            LEFT JOIN blocks b ON l.block_id = b.id 
                            WHERE l.status = 'ACTIVE' 
                            ORDER BY (l.is_featured = 'YES') DESC, l.id DESC 
                            LIMIT 6");
        $featuredListings = $stmt->fetchAll();
    } catch (PDOException $e) {
        $featuredListings = [];
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<style>
/* Custom Styles for Today in Saran Page */
.today-hero-bg {
    background: linear-gradient(135deg, #091e3a 0%, #1e3a8a 50%, #2563eb 100%);
    color: #ffffff;
    position: relative;
    overflow: hidden;
}
.today-hero-bg::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.15) 0%, transparent 50%);
    pointer-events: none;
}
.today-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    transition: all 0.3s ease;
    overflow: hidden;
}
.today-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 30px -8px rgba(30, 64, 175, 0.12);
    border-color: #93c5fd;
}
.live-pulse {
    display: inline-block;
    width: 10px;
    height: 10px;
    background-color: #ef4444;
    border-radius: 50%;
    margin-right: 6px;
    box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
    animation: pulse-red 1.8s infinite;
}
@keyframes pulse-red {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}
.clock-display {
    font-family: 'Outfit', monospace;
    letter-spacing: 1px;
}
.stat-pill {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 14px;
}
.status-badge-open {
    background: #dcfce7;
    color: #166534;
    font-weight: 700;
    border: 1px solid #bbf7d0;
}
.status-badge-closed {
    background: #fee2e2;
    color: #991b1b;
    font-weight: 700;
    border: 1px solid #fecaca;
}
.status-badge-24x7 {
    background: #e0f2fe;
    color: #0369a1;
    font-weight: 700;
    border: 1px solid #bae6fd;
}
.fuel-rate-badge {
    font-size: 1.35rem;
    font-weight: 800;
    font-family: 'Outfit', sans-serif;
}
.quick-action-btn {
    transition: all 0.2s ease;
    border-radius: 50px;
    font-weight: 600;
}
.quick-action-btn:hover {
    transform: scale(1.03);
}
.bg-purple-subtle {
    background-color: #f3e8ff !important;
}
.text-purple {
    color: #7e22ce !important;
}
</style>

<!-- Hero Section -->
<div class="today-hero-bg py-5">
    <div class="container position-relative py-2">
        <div class="row align-items-center g-4">
            <div class="col-lg-7 text-center text-lg-start">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-20 mb-3 backdrop-blur">
                    <span class="live-pulse"></span>
                    <span class="small fw-bold text-white tracking-wider text-uppercase">Live Saran District Bulletin</span>
                    <span class="badge bg-warning text-dark rounded-pill px-2.5 py-0.5 fw-bold" style="font-size: 0.72rem;">DAILY DISPATCH</span>
                </div>
                <h1 class="fw-bolder font-heading text-white display-5 mb-2">
                    What's Today in Saran District!
                </h1>
                <p class="fs-5 text-white-50 mb-4" style="line-height: 1.6;">
                    आज सारण जिला (छपरा, बिहार) का संपूर्ण दैनिक विवरण – लाइव मौसम, पंचांग, कार्यालय समय, आपातकालीन सेवाएं, पेट्रोल-डीजल दर एवं 20 प्रखंडों की महत्वपूर्ण जानकारियां।
                </p>
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
                    <a href="#weather-section" class="btn btn-warning rounded-pill px-4 py-2 fw-bold text-dark shadow-sm">
                        <i class="bi bi-cloud-sun-fill me-1"></i> Live Weather & AQI
                    </a>
                    <a href="#panchang-section" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">
                        <i class="bi bi-calendar-event me-1"></i> Today's Panchang
                    </a>
                    <a href="#duty-contacts" class="btn btn-danger rounded-pill px-4 py-2 fw-bold shadow-sm">
                        <i class="bi bi-telephone-fill me-1"></i> Emergency 24/7
                    </a>
                </div>
            </div>

            <!-- Live Digital Clock & Astronomical Box -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.15) !important;">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-center border-bottom border-secondary border-opacity-50 pb-3 mb-3">
                            <div>
                                <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill px-2.5 py-1 small fw-bold">
                                    <i class="bi bi-geo-alt-fill text-warning me-1"></i> Chapra (Saran), Bihar
                                </span>
                            </div>
                            <div class="text-end text-white-50 small">
                                <i class="bi bi-clock me-1 text-warning"></i> IST (UTC +5:30)
                            </div>
                        </div>

                        <!-- Real-time Clock Element -->
                        <div class="text-center my-3">
                            <div id="live-time" class="clock-display display-4 fw-bold text-warning mb-1">
                                <?php echo date('h:i:s A'); ?>
                            </div>
                            <div class="fs-5 fw-bold text-white mb-1">
                                <?php echo $currentDateFormatted; ?>
                            </div>
                            <div class="text-white-50 small">
                                <i class="bi bi-calendar2-check me-1 text-info"></i> <?php echo $currentDayHi . ', ' . $currentDateHi; ?>
                            </div>
                        </div>

                        <!-- Astronomical / Sun Timings -->
                        <div class="row g-2 text-center pt-3 border-top border-secondary border-opacity-50">
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-white bg-opacity-10">
                                    <div class="small text-warning fw-semibold"><i class="bi bi-sunrise-fill me-1"></i>Sunrise</div>
                                    <div id="live-sunrise" class="fw-bold fs-6">05:40 AM</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-white bg-opacity-10">
                                    <div class="small text-info fw-semibold"><i class="bi bi-sunset-fill me-1"></i>Sunset</div>
                                    <div id="live-sunset" class="fw-bold fs-6">05:46 PM</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-white bg-opacity-10">
                                    <div class="small text-light fw-semibold"><i class="bi bi-moon-stars-fill me-1"></i>Moonrise</div>
                                    <div class="fw-bold fs-6">04:15 PM</div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center pt-2 mt-2 border-top border-secondary border-opacity-25">
                            <span id="api-sync-indicator" class="extra-small text-white-50">
                                <i class="bi bi-broadcast text-success me-1"></i> Auto-synced via Live API
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Page Body -->
<div class="container py-5">

    <!-- Quick Navigation Anchor Pills -->
    <div class="d-flex flex-wrap gap-2 justify-content-center mb-5">
        <a href="#weather-section" class="btn btn-light rounded-pill border px-3.5 py-2 small fw-bold text-secondary shadow-2xs hover-shadow">
            <i class="bi bi-cloud-sun text-primary me-1"></i> Weather & Air
        </a>
        <a href="#panchang-section" class="btn btn-light rounded-pill border px-3.5 py-2 small fw-bold text-secondary shadow-2xs hover-shadow">
            <i class="bi bi-calendar3 text-warning me-1"></i> Vedic Panchang
        </a>
        <a href="#holidays-section" class="btn btn-light rounded-pill border px-3.5 py-2 small fw-bold text-secondary shadow-2xs hover-shadow">
            <i class="bi bi-calendar2-check-fill text-purple me-1"></i> 2026 Holidays & Court Calendar
        </a>
        <a href="#fuel-mandi" class="btn btn-light rounded-pill border px-3.5 py-2 small fw-bold text-secondary shadow-2xs hover-shadow">
            <i class="bi bi-fuel-pump text-danger me-1"></i> Fuel & Mandi Rates
        </a>
        <a href="#epapers-section" class="btn btn-light rounded-pill border px-3.5 py-2 small fw-bold text-secondary shadow-2xs hover-shadow">
            <i class="bi bi-newspaper text-danger me-1"></i> Saran ePapers
        </a>
        <a href="#today-history" class="btn btn-light rounded-pill border px-3.5 py-2 small fw-bold text-secondary shadow-2xs hover-shadow">
            <i class="bi bi-hourglass-split text-purple me-1"></i> Today in History
        </a>
        <a href="#duty-contacts" class="btn btn-light rounded-pill border px-3.5 py-2 small fw-bold text-secondary shadow-2xs hover-shadow">
            <i class="bi bi-telephone-plus text-danger me-1"></i> Emergency Directory
        </a>
    </div>

    <!-- Section 1: Live Weather & Environmental Report for Saran (Chapra) -->
    <section id="weather-section" class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded-pill small mb-1">
                    <i class="bi bi-broadcast me-1"></i> Real-Time Satellite Feed
                </span>
                <h2 class="fw-bold font-heading text-dark h3 mb-0">Today's Weather in Saran District (Chapra)</h2>
            </div>
            <div class="small text-muted">
                <i class="bi bi-geo-fill text-danger me-1"></i> Lat: 25.7848° N, Long: 84.7274° E
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Current Weather Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 text-white overflow-hidden h-100" style="background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%);">
                    <div class="card-body p-4 p-md-5">
                        <div class="row align-items-center">
                            <div class="col-md-7 mb-4 mb-md-0">
                                <div class="badge bg-white bg-opacity-20 rounded-pill px-3 py-1 mb-2 text-white small fw-bold">
                                    <i class="bi bi-geo-alt me-1"></i> Chapra Central Weather Station
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div id="weather-icon-wrapper" class="display-3">
                                        <i class="bi bi-sun-fill text-warning"></i>
                                    </div>
                                    <div>
                                        <div id="current-temp" class="display-3 fw-bolder font-heading text-white mb-0">31°C</div>
                                        <div id="weather-desc" class="fs-5 text-white-50 fw-semibold">Sunny / Mostly Clear</div>
                                    </div>
                                </div>
                                <p class="text-white-50 small mt-3 mb-0">
                                    Feels like <strong id="feels-like" class="text-white">34°C</strong> • Expected Max <strong id="max-temp" class="text-white">33°C</strong> / Min <strong id="min-temp" class="text-white">25°C</strong> across Chapra Sadar, Marhaura, and Sonpur.
                                </p>
                            </div>

                            <div class="col-md-5">
                                <div class="bg-black bg-opacity-20 rounded-4 p-3 border border-white border-opacity-10">
                                    <div class="d-flex justify-content-between py-1.5 border-bottom border-white border-opacity-10 small">
                                        <span class="text-white-50"><i class="bi bi-droplet-half text-info me-1"></i> Humidity</span>
                                        <strong id="weather-humidity" class="text-white">68%</strong>
                                    </div>
                                    <div class="d-flex justify-content-between py-1.5 border-bottom border-white border-opacity-10 small">
                                        <span class="text-white-50"><i class="bi bi-wind text-white me-1"></i> Wind Speed</span>
                                        <strong id="weather-wind" class="text-white">12 km/h ENE</strong>
                                    </div>
                                    <div class="d-flex justify-content-between py-1.5 border-bottom border-white border-opacity-10 small">
                                        <span class="text-white-50"><i class="bi bi-eye text-warning me-1"></i> Visibility</span>
                                        <strong id="weather-visibility" class="text-white">6.0 km</strong>
                                    </div>
                                    <div class="d-flex justify-content-between py-1.5 small">
                                        <span class="text-white-50"><i class="bi bi-speedometer text-success me-1"></i> Air Quality (AQI)</span>
                                        <strong id="weather-aqi" class="text-warning">78 (Moderate)</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3-Day Forecast Preview & Air Advisory -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <h5 class="fw-bold font-heading text-dark mb-3">
                        <i class="bi bi-calendar-week text-primary me-2"></i>3-Day Saran Forecast
                    </h5>
                    
                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-white border mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-sun-fill text-warning fs-4"></i>
                            <div>
                                <div class="fw-bold small text-dark">Tomorrow</div>
                                <div class="text-muted extra-small">Partly Cloudy</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold text-dark small">33°C</span>
                            <span class="text-muted small ms-1">/ 26°C</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-white border mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-cloud-sun-fill text-primary fs-4"></i>
                            <div>
                                <div class="fw-bold small text-dark"><?php echo date('l', strtotime('+2 days')); ?></div>
                                <div class="text-muted extra-small">Humid & Clear</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold text-dark small">34°C</span>
                            <span class="text-muted small ms-1">/ 25°C</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-white border mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-cloud-drizzle-fill text-info fs-4"></i>
                            <div>
                                <div class="fw-bold small text-dark"><?php echo date('l', strtotime('+3 days')); ?></div>
                                <div class="text-muted extra-small">Light Showers</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold text-dark small">31°C</span>
                            <span class="text-muted small ms-1">/ 24°C</span>
                        </div>
                    </div>

                    <div class="alert alert-info py-2 px-3 mb-0 rounded-3 small border-0 bg-info-subtle text-info-emphasis">
                        <i class="bi bi-info-circle-fill me-1"></i> <strong>Saran Diara Advisory:</strong> River Ganga and Gandak banks experience high morning humidity and pleasant evening breezes.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: Vedic Panchang & Tithi for Saran (Chapra) -->
    <section id="panchang-section" class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <span class="badge bg-warning-subtle text-warning-emphasis fw-bold px-3 py-1 rounded-pill small mb-1">
                    <i class="bi bi-calendar4-week me-1"></i> Vedic Calendar
                </span>
                <h2 class="fw-bold font-heading text-dark h3 mb-0">Today's Hindu Panchang for Chapra (Saran)</h2>
            </div>
            <div class="small text-muted">
                Vikram Samvat: <strong class="text-dark"><?php echo $vikramSamvat; ?></strong> | Saka Samvat: <strong class="text-dark"><?php echo $sakaSamvat; ?></strong>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-2xs rounded-4 p-3.5 h-100 bg-white border-start border-4 border-warning position-relative overflow-hidden hover-shadow transition-all">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2.5 py-0.5 extra-small fw-bold">वार / Day</span>
                        <i class="bi bi-sun-fill text-warning fs-5"></i>
                    </div>
                    <h4 class="fw-bold text-dark font-heading mb-0"><?php echo $currentDayHi; ?></h4>
                    <span class="text-muted extra-small fw-semibold"><?php echo date('l'); ?></span>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-2xs rounded-4 p-3.5 h-100 bg-white border-start border-4 border-primary position-relative overflow-hidden hover-shadow transition-all">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill px-2.5 py-0.5 extra-small fw-bold">पक्ष / Paksha</span>
                        <i class="bi bi-moon-stars-fill text-primary fs-5"></i>
                    </div>
                    <h4 class="fw-bold text-primary font-heading mb-0">शुक्ल / कृष्ण पक्ष</h4>
                    <span class="text-muted extra-small fw-semibold">Lunar Fortnight Phase</span>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-2xs rounded-4 p-3.5 h-100 bg-white border-start border-4 border-success position-relative overflow-hidden hover-shadow transition-all">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-0.5 extra-small fw-bold">शुभ / Auspicious</span>
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    </div>
                    <h5 class="fw-bold text-success font-heading mb-0 fs-6">11:45 AM – 12:35 PM</h5>
                    <span class="text-muted extra-small fw-semibold">अभिजित मुहूर्त (Best for Start)</span>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-2xs rounded-4 p-3.5 h-100 bg-white border-start border-4 border-danger position-relative overflow-hidden hover-shadow transition-all">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-0.5 extra-small fw-bold">अशुभ / Caution</span>
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    </div>
                    <h5 class="fw-bold text-danger font-heading mb-0 fs-6">12:15 PM – 01:45 PM</h5>
                    <span class="text-muted extra-small fw-semibold">राहुकाल (Avoid Registrations)</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Today's Fuel Rates & Mandi Highlights in Saran -->
    <section id="fuel-mandi" class="mb-5">
        <div class="row g-4">
            <!-- Fuel Prices in Saran -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-1 rounded-pill small mb-1">
                                <i class="bi bi-fuel-pump-fill me-1"></i> Daily Energy Index
                            </span>
                            <h3 class="fw-bold font-heading text-dark h4 mb-0">Today's Fuel Rates in Saran</h3>
                        </div>
                        <div class="text-end small text-muted">
                            Updated: <?php echo date('d M Y'); ?>
                        </div>
                    </div>

                    <div class="row g-3 text-center">
                        <div class="col-sm-4 col-12">
                            <a href="https://www.ndtv.com/fuel-prices/petrol-price-in-saran-city" target="_blank" rel="noopener noreferrer" class="text-decoration-none d-block h-100">
                                <div class="p-3 rounded-4 bg-light border border-secondary border-opacity-10 h-100 hover-shadow transition-all">
                                    <div class="text-muted extra-small fw-bold text-uppercase"><i class="bi bi-fuel-pump-fill text-danger me-1"></i>Petrol</div>
                                    <div class="fuel-rate-badge text-danger my-1">₹114.34</div>
                                    <div class="text-muted extra-small">/ Litre (Saran)</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-4 col-12">
                            <a href="https://www.ndtv.com/fuel-prices/diesel-price-in-saran-city" target="_blank" rel="noopener noreferrer" class="text-decoration-none d-block h-100">
                                <div class="p-3 rounded-4 bg-light border border-secondary border-opacity-10 h-100 hover-shadow transition-all">
                                    <div class="text-muted extra-small fw-bold text-uppercase"><i class="bi bi-fuel-pump text-primary me-1"></i>Diesel</div>
                                    <div class="fuel-rate-badge text-primary my-1">₹100.30</div>
                                    <div class="text-muted extra-small">/ Litre (Saran)</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-4 col-12">
                            <a href="category/automobile/petrol-pumps" class="text-decoration-none d-block h-100">
                                <div class="p-3 rounded-4 bg-light border border-secondary border-opacity-10 h-100 hover-shadow transition-all">
                                    <div class="text-muted extra-small fw-bold text-uppercase"><i class="bi bi-ev-station text-success me-1"></i>CNG</div>
                                    <div class="fuel-rate-badge text-success my-1">₹86.50</div>
                                    <div class="text-muted extra-small">/ Kg in Chapra</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="category/automobile/petrol-pumps" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-fuel-pump-fill me-1"></i> Explore All 87+ Verified Petrol Pumps & Fuel Stations in Saran →
                        </a>
                    </div>

                    <div class="mt-3 pt-3 border-top border-secondary border-opacity-10 extra-small text-muted text-start">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                            <span class="fw-bold text-dark"><i class="bi bi-shield-check text-success me-1"></i>Official OMC Reference Portals:</span>
                            <span class="text-muted">Retail Price & Price Build-Ups</span>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="https://iocl.com/petrol-diesel-price" target="_blank" rel="noopener noreferrer" class="badge bg-light text-primary border rounded-pill text-decoration-none px-2.5 py-1 fw-semibold">
                                IndianOil (IOCL) <i class="bi bi-box-arrow-up-right extra-small"></i>
                            </a>
                            <a href="https://www.hindustanpetroleum.com/PriceBuildup" target="_blank" rel="noopener noreferrer" class="badge bg-light text-primary border rounded-pill text-decoration-none px-2.5 py-1 fw-semibold">
                                Hindustan Petroleum (HPCL) <i class="bi bi-box-arrow-up-right extra-small"></i>
                            </a>
                            <a href="https://www.bharatpetroleum.in/our-businesses/fuels-and-services/petro-prices" target="_blank" rel="noopener noreferrer" class="badge bg-light text-primary border rounded-pill text-decoration-none px-2.5 py-1 fw-semibold">
                                Bharat Petroleum (BPCL) <i class="bi bi-box-arrow-up-right extra-small"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mandi Highlights in Saran -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <span class="badge bg-success-subtle text-success fw-bold px-3 py-1 rounded-pill small mb-1">
                                <i class="bi bi-basket-fill me-1"></i> Agricultural Markets
                            </span>
                            <h3 class="fw-bold font-heading text-dark h4 mb-0">Today's Mandi & Agriculture Hubs</h3>
                        </div>
                        <span class="badge bg-success text-white rounded-pill px-2.5 py-1 extra-small fw-bold">
                            <i class="bi bi-patch-check-fill me-1"></i> Saran Mandi Hub
                        </span>
                    </div>

                    <p class="text-muted small mb-3">Major wholesale APMC & agricultural market hubs active today in Saran District:</p>

                    <div class="d-flex flex-column gap-2.5">
                        <div class="p-3 rounded-3 bg-light border border-secondary border-opacity-10 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 hover-shadow transition-all">
                            <div>
                                <strong class="text-dark d-flex align-items-center gap-1.5 fs-6">
                                    <i class="bi bi-shop-window text-success fs-5"></i> Bazar Samiti, Chapra (बाजार समिति)
                                </strong>
                                <div class="text-muted extra-small mt-0.5">Apex APMC Market • Grains, Foodgrains, Vegetables, Fruits, Seeds & Fertilizer</div>
                                <div class="text-secondary extra-small fw-semibold mt-0.5"><i class="bi bi-calendar-x text-danger me-1"></i>Monthly Off: Last day of every month</div>
                            </div>
                            <div class="text-sm-end shrink-0">
                                <?php if ((int)date('j') === (int)date('t')): ?>
                                    <span class="badge bg-danger text-white rounded-pill px-2.5 py-1">Closed (Month-End Off)</span>
                                <?php else: ?>
                                    <span class="badge bg-success text-white rounded-pill px-2.5 py-1"><i class="bi bi-clock-fill me-1"></i>05:30 AM - 11:30 PM</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="p-3 rounded-3 bg-light border border-secondary border-opacity-10 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 hover-shadow transition-all">
                            <div>
                                <strong class="text-dark d-flex align-items-center gap-1.5 fs-6">
                                    <i class="bi bi-basket2 text-primary fs-5"></i> Gudri Bazar (Chapra Town)
                                </strong>
                                <div class="text-muted extra-small mt-0.5">Fresh green vegetables, fruits, spices, dairy & local produce wholesale</div>
                            </div>
                            <div class="text-sm-end shrink-0">
                                <span class="badge bg-success text-white rounded-pill px-2.5 py-1"><i class="bi bi-clock-fill me-1"></i>04 AM - 09 PM</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-3 bg-light border border-secondary border-opacity-10 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 hover-shadow transition-all">
                            <div>
                                <strong class="text-dark d-flex align-items-center gap-1.5 fs-6">
                                    <i class="bi bi-boxes text-warning-emphasis fs-5"></i> Revelganj Galla Mandi
                                </strong>
                                <div class="text-muted extra-small mt-0.5">Paddy (Dhan), Wheat (Gehun), Mustard, Maize (Makka) & Pulses trade</div>
                            </div>
                            <div class="text-sm-end shrink-0">
                                <span class="badge bg-primary text-white rounded-pill px-2.5 py-1"><i class="bi bi-clock-fill me-1"></i>07 AM - 06 PM</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-3 bg-light border border-secondary border-opacity-10 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 hover-shadow transition-all">
                            <div>
                                <strong class="text-dark d-flex align-items-center gap-1.5 fs-6">
                                    <i class="bi bi-flower1 text-info fs-5"></i> Marhaura Krishi Bazar
                                </strong>
                                <div class="text-muted extra-small mt-0.5">Sugarcane, seasonal agricultural crops, potato & regional farmer produce</div>
                            </div>
                            <div class="text-sm-end shrink-0">
                                <span class="badge bg-info text-dark rounded-pill px-2.5 py-1"><i class="bi bi-clock-fill me-1"></i>06 AM - 07 PM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 4.5: 2026 Official Holiday Calendar (Saran & Bihar) -->
    <!-- Reference: https://patnahighcourt.gov.in/PDF/CALENDAR/CIVIL_CAL2026.jpg -->
    <section id="holidays-section" class="mb-5">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0 !important;">
            <div class="p-4 p-lg-5">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-purple-subtle text-purple fw-bold px-3 py-1 rounded-pill small">
                                <i class="bi bi-calendar-check-fill me-1"></i> Official 2026 Calendar
                            </span>
                            <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-0.5 extra-small fw-semibold">
                                Patna High Court & Bihar Govt Gazette
                            </span>
                        </div>
                        <h2 class="fw-bold font-heading text-dark h3 mb-1">
                            Bihar Govt, Civil Court Saran & Bank Holidays 2026
                        </h2>
                        <p class="text-muted small mb-0">
                            Official holiday calendar for Saran Collectorate, Subordinate Courts (Chapra / Sonepur / Marhaura) and Commercial Banks.
                        </p>
                    </div>
                    <div>
                        <a href="https://patnahighcourt.gov.in/PDF/CALENDAR/CIVIL_CAL2026.jpg" target="_blank" rel="noopener noreferrer" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                            <i class="bi bi-calendar3 me-1 text-warning"></i> View Official 2026 Calendar (Patna High Court) <i class="bi bi-box-arrow-up-right small ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Today's Holiday Status Overview Bar -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3.5 rounded-4 bg-white border border-secondary border-opacity-10 h-100 shadow-2xs">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="extra-small fw-bold text-uppercase text-muted"><i class="bi bi-building me-1 text-primary"></i> Govt of Bihar Offices</span>
                                <span class="badge <?php echo $todayHolidayInfo['is_govt_holiday'] ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'; ?> rounded-pill px-2.5 py-1 extra-small fw-bold">
                                    <?php echo $todayHolidayInfo['is_govt_holiday'] ? 'HOLIDAY' : 'WORKING DAY'; ?>
                                </span>
                            </div>
                            <div class="fw-bold text-dark fs-6 mb-1">
                                <?php 
                                if (!empty($todayHolidayInfo['holidays'])) {
                                    echo htmlspecialchars($todayHolidayInfo['holidays'][0]['name']);
                                } elseif ($todayHolidayInfo['is_sunday']) {
                                    echo 'Sunday Weekly Off';
                                } else {
                                    echo 'All Administrative Offices Open';
                                }
                                ?>
                            </div>
                            <div class="extra-small text-muted">Collectorate, SDO, 20 Block & Circle Offices</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3.5 rounded-4 bg-white border border-secondary border-opacity-10 h-100 shadow-2xs">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="extra-small fw-bold text-uppercase text-muted"><i class="bi bi-bank me-1 text-dark"></i> Civil Court Saran (Chapra)</span>
                                <span class="badge <?php echo $todayHolidayInfo['is_court_holiday'] ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'; ?> rounded-pill px-2.5 py-1 extra-small fw-bold">
                                    <?php echo $todayHolidayInfo['is_court_holiday'] ? 'COURT CLOSED / RECESS' : 'COURT IN SESSION'; ?>
                                </span>
                            </div>
                            <div class="fw-bold text-dark fs-6 mb-1">
                                <?php 
                                if (!empty($todayHolidayInfo['holidays'])) {
                                    echo htmlspecialchars($todayHolidayInfo['holidays'][0]['name']);
                                } elseif ($todayHolidayInfo['is_sunday']) {
                                    echo 'Sunday Judicial Recess';
                                } else {
                                    echo 'District & Sessions Court Active';
                                }
                                ?>
                            </div>
                            <div class="extra-small text-muted">Subordinate Courts, Family Court & DLSA</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3.5 rounded-4 bg-white border border-secondary border-opacity-10 h-100 shadow-2xs">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="extra-small fw-bold text-uppercase text-muted"><i class="bi bi-cash-coin me-1 text-success"></i> Banks (NI Act 1881)</span>
                                <span class="badge <?php echo $todayHolidayInfo['is_bank_holiday'] ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'; ?> rounded-pill px-2.5 py-1 extra-small fw-bold">
                                    <?php echo $todayHolidayInfo['is_bank_holiday'] ? 'BRANCHES CLOSED' : 'BRANCHES OPEN'; ?>
                                </span>
                            </div>
                            <div class="fw-bold text-dark fs-6 mb-1">
                                <?php 
                                if ($todayHolidayInfo['is_second_fourth_saturday']) {
                                    echo '2nd / 4th Saturday Bank Holiday';
                                } elseif (!empty($todayHolidayInfo['holidays'])) {
                                    echo htmlspecialchars($todayHolidayInfo['holidays'][0]['name']);
                                } elseif ($todayHolidayInfo['is_sunday']) {
                                    echo 'Sunday Bank Holiday';
                                } else {
                                    echo 'Clearing & Branch Counters Active';
                                }
                                ?>
                            </div>
                            <div class="extra-small text-muted">Commercial & Regional Rural Banks (SBI, PNB, UBI, etc.)</div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Holidays List & Categorized Filters -->
                <div class="border-top border-secondary border-opacity-10 pt-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <h4 class="fw-bold text-dark h5 mb-0 font-heading">
                            <i class="bi bi-calendar3-event text-primary me-1"></i> Upcoming Gazetted Holidays & Vacations (2026)
                        </h4>
                        <div class="nav nav-pills small gap-1" id="holidayPills" role="tablist">
                            <button class="nav-link active rounded-pill px-3 py-1 extra-small fw-bold" id="tab-all-holidays" data-bs-toggle="pill" data-bs-target="#pills-all-holidays" type="button" role="tab">All Upcoming</button>
                            <button class="nav-link rounded-pill px-3 py-1 extra-small fw-bold" id="tab-govt-holidays" data-bs-toggle="pill" data-bs-target="#pills-govt-holidays" type="button" role="tab">Govt of Bihar</button>
                            <button class="nav-link rounded-pill px-3 py-1 extra-small fw-bold" id="tab-court-holidays" data-bs-toggle="pill" data-bs-target="#pills-court-holidays" type="button" role="tab">Civil Courts</button>
                            <button class="nav-link rounded-pill px-3 py-1 extra-small fw-bold" id="tab-bank-holidays" data-bs-toggle="pill" data-bs-target="#pills-bank-holidays" type="button" role="tab">Banks (NI Act)</button>
                        </div>
                    </div>

                    <div class="tab-content" id="holidayPillsContent">
                        <!-- All Holidays Tab -->
                        <div class="tab-pane fade show active" id="pills-all-holidays" role="tabpanel">
                            <div class="row g-3">
                                <?php foreach ($upcomingHolidays as $h): ?>
                                <div class="col-md-6 col-lg-3">
                                    <div class="card border-0 shadow-2xs rounded-4 p-3 bg-white h-100 hover-shadow transition-all">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                <i class="bi bi-calendar-event me-1 text-primary"></i> <?php echo date('d M Y', strtotime($h['date'])); ?>
                                            </span>
                                            <?php if ($h['days_left'] == 0): ?>
                                                <span class="badge bg-danger text-white rounded-pill px-2 py-0.5 extra-small fw-bold">TODAY</span>
                                            <?php elseif ($h['days_left'] == 1): ?>
                                                <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5 extra-small fw-bold">TOMORROW</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-0.5 extra-small fw-bold">In <?php echo $h['days_left']; ?> days</span>
                                            <?php endif; ?>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1 fs-6 font-heading"><?php echo htmlspecialchars($h['name']); ?></h6>
                                        <div class="text-secondary extra-small fw-semibold mb-2"><?php echo htmlspecialchars($h['name_hi']); ?></div>
                                        <p class="text-muted extra-small mb-3"><?php echo htmlspecialchars($h['desc']); ?></p>
                                        <div class="mt-auto d-flex flex-wrap gap-1">
                                            <?php if (in_array('GOVT', $h['type'])): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle extra-small py-0.5 px-2">Govt</span>
                                            <?php endif; ?>
                                            <?php if (in_array('COURT', $h['type'])): ?>
                                                <span class="badge bg-dark-subtle text-dark border border-dark-subtle extra-small py-0.5 px-2">Court</span>
                                            <?php endif; ?>
                                            <?php if (in_array('BANK', $h['type'])): ?>
                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle extra-small py-0.5 px-2">Bank</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Bihar Govt Holidays Tab -->
                        <div class="tab-pane fade" id="pills-govt-holidays" role="tabpanel">
                            <div class="row g-3">
                                <?php foreach ($upcomingGovtHolidays as $h): ?>
                                <div class="col-md-6 col-lg-3">
                                    <div class="card border-0 shadow-2xs rounded-4 p-3 bg-white h-100 hover-shadow transition-all">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                <?php echo date('d M Y', strtotime($h['date'])); ?>
                                            </span>
                                            <span class="badge bg-light text-secondary border rounded-pill px-2 py-0.5 extra-small">In <?php echo $h['days_left']; ?> days</span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1 fs-6"><?php echo htmlspecialchars($h['name']); ?></h6>
                                        <div class="text-secondary extra-small fw-semibold mb-2"><?php echo htmlspecialchars($h['name_hi']); ?></div>
                                        <p class="text-muted extra-small mb-0"><?php echo htmlspecialchars($h['desc']); ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Civil Court Saran Tab -->
                        <div class="tab-pane fade" id="pills-court-holidays" role="tabpanel">
                            <div class="row g-3">
                                <?php foreach ($upcomingCourtHolidays as $h): ?>
                                <div class="col-md-6 col-lg-3">
                                    <div class="card border-0 shadow-2xs rounded-4 p-3 bg-white h-100 hover-shadow transition-all">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-dark-subtle text-dark rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                <?php echo date('d M Y', strtotime($h['date'])); ?>
                                            </span>
                                            <span class="badge bg-light text-secondary border rounded-pill px-2 py-0.5 extra-small">In <?php echo $h['days_left']; ?> days</span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1 fs-6"><?php echo htmlspecialchars($h['name']); ?></h6>
                                        <div class="text-secondary extra-small fw-semibold mb-2"><?php echo htmlspecialchars($h['name_hi']); ?></div>
                                        <p class="text-muted extra-small mb-0"><?php echo htmlspecialchars($h['desc']); ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Bank Holidays Tab -->
                        <div class="tab-pane fade" id="pills-bank-holidays" role="tabpanel">
                            <div class="row g-3">
                                <?php foreach ($upcomingBankHolidays as $h): ?>
                                <div class="col-md-6 col-lg-3">
                                    <div class="card border-0 shadow-2xs rounded-4 p-3 bg-white h-100 hover-shadow transition-all">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-2.5 py-1 extra-small fw-bold">
                                                <?php echo date('d M Y', strtotime($h['date'])); ?>
                                            </span>
                                            <span class="badge bg-light text-secondary border rounded-pill px-2 py-0.5 extra-small">In <?php echo $h['days_left']; ?> days</span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1 fs-6"><?php echo htmlspecialchars($h['name']); ?></h6>
                                        <div class="text-secondary extra-small fw-semibold mb-2"><?php echo htmlspecialchars($h['name_hi']); ?></div>
                                        <p class="text-muted extra-small mb-0"><?php echo htmlspecialchars($h['desc']); ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 5: Today's Daily ePapers covering Saran District (Chapra Editions) -->
    <section id="epapers-section" class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-1 rounded-pill small mb-1">
                    <i class="bi bi-newspaper me-1"></i> Morning Editions • आज के ई-अखबार
                </span>
                <h2 class="fw-bold font-heading text-dark h3 mb-0">Today's Daily Newspapers & ePapers (Saran / Chapra)</h2>
            </div>
            <div class="small text-muted">
                <i class="bi bi-clock me-1 text-primary"></i> Daily Morning Updates • Direct Digital Access
            </div>
        </div>

        <div class="row g-3">
            <!-- Dainik Jagran -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-3.5 h-100 today-card border-top border-4 border-danger d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1 extra-small fw-bold">Hindi Daily</span>
                        <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 extra-small fw-semibold">05:00 AM</span>
                    </div>
                    <h5 class="fw-bold text-dark font-heading mb-1 fs-5">दैनिक जागरण</h5>
                    <div class="text-muted extra-small mb-2">Dainik Jagran (छपरा / सारण संस्करण)</div>
                    <p class="text-secondary extra-small mb-3">Leading Hindi daily offering comprehensive district news across all 20 blocks of Saran.</p>
                    <a href="https://epaper.jagran.com/epaper/edition-today-90-saran.html" target="_blank" rel="noopener noreferrer" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-bold mt-auto">
                        <i class="bi bi-book-half me-1"></i> Read Jagran ePaper →
                    </a>
                </div>
            </div>

            <!-- Prabhat Khabar -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-3.5 h-100 today-card border-top border-4 border-warning d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2.5 py-1 extra-small fw-bold">Bihar Special</span>
                        <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 extra-small fw-semibold">05:00 AM</span>
                    </div>
                    <h5 class="fw-bold text-dark font-heading mb-1 fs-5">प्रभात खबर</h5>
                    <div class="text-muted extra-small mb-2">Prabhat Khabar (सारण / छपरा संस्करण)</div>
                    <p class="text-secondary extra-small mb-3">Popular Bihar newspaper focusing on rural governance, agriculture, politics & Saran sports.</p>
                    <a href="https://epaper.prabhatkhabar.com/patna/saran/<?php echo date('Y-m-d'); ?>/1" target="_blank" rel="noopener noreferrer" class="btn btn-outline-warning btn-sm w-100 rounded-pill fw-bold text-dark mt-auto">
                        <i class="bi bi-book-half me-1"></i> Read Prabhat Khabar →
                    </a>
                </div>
            </div>

            <!-- Dainik Bhaskar -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-3.5 h-100 today-card border-top border-4 border-primary d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1 extra-small fw-bold">Digital First</span>
                        <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 extra-small fw-semibold">05:15 AM</span>
                    </div>
                    <h5 class="fw-bold text-dark font-heading mb-1 fs-5">दैनिक भास्कर</h5>
                    <div class="text-muted extra-small mb-2">Dainik Bhaskar (बिहार / छपरा)</div>
                    <p class="text-secondary extra-small mb-3">In-depth investigative journalism, local crime beat, administration updates & youth issues.</p>
                    <a href="https://www.bhaskar.com/local/bihar/saran/" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-bold mt-auto">
                        <i class="bi bi-book-half me-1"></i> Read Bhaskar News →
                    </a>
                </div>
            </div>

            <!-- Hindustan -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-3.5 h-100 today-card border-top border-4 border-info d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-2.5 py-1 extra-small fw-bold">Top Read</span>
                        <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 extra-small fw-semibold">05:30 AM</span>
                    </div>
                    <h5 class="fw-bold text-dark font-heading mb-1 fs-5">हिन्दुस्तान</h5>
                    <div class="text-muted extra-small mb-2">Hindustan (छपरा संस्करण)</div>
                    <p class="text-secondary extra-small mb-3">Extensive coverage of education, examinations, court cases, business & daily Saran lifestyle.</p>
                    <a href="https://epaper.livehindustan.com/edition/chapra?date=<?php echo date('Y-m-d'); ?>&page=1" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info btn-sm w-100 rounded-pill fw-bold text-info-emphasis mt-auto">
                        <i class="bi bi-book-half me-1"></i> Read Hindustan ePaper →
                    </a>
                </div>
            </div>

            <!-- Aaj Newspaper -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-3.5 h-100 today-card border-top border-4 border-dark d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill px-2.5 py-1 extra-small fw-bold">Heritage Daily</span>
                        <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 extra-small fw-semibold">06:00 AM</span>
                    </div>
                    <h5 class="fw-bold text-dark font-heading mb-1 fs-5">आज</h5>
                    <div class="text-muted extra-small mb-2">Aaj Daily (छपरा संस्करण)</div>
                    <p class="text-secondary extra-small mb-3">Historic Hindi publication known for traditional Bhojpuri region reporting & local public issues.</p>
                    <a href="http://ajhindidaily.com/%E0%A4%88-%E0%A4%AA%E0%A5%87%E0%A4%AA%E0%A4%B0" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm w-100 rounded-pill fw-bold mt-auto">
                        <i class="bi bi-book-half me-1"></i> Read Aaj Daily →
                    </a>
                </div>
            </div>

            <!-- Aaj Tak -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-3.5 h-100 today-card border-top border-4 border-danger d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1 extra-small fw-bold">Live News Hub</span>
                        <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 extra-small fw-semibold">24x7 Live</span>
                    </div>
                    <h5 class="fw-bold text-dark font-heading mb-1 fs-5">आज तक</h5>
                    <div class="text-muted extra-small mb-2">Aaj Tak (सारण विशेष समाचार)</div>
                    <p class="text-secondary extra-small mb-3">Leading national news channel & digital portal with dedicated Saran news, videos, ground reporting & analysis.</p>
                    <a href="https://www.aajtak.in/topic/saran" target="_blank" rel="noopener noreferrer" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-bold mt-auto">
                        <i class="bi bi-broadcast-pin me-1"></i> Read Aaj Tak Saran News →
                    </a>
                </div>
            </div>

            <!-- Hindustan Times -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-3.5 h-100 today-card border-top border-4 border-primary d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 extra-small fw-bold">English Daily</span>
                        <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 extra-small fw-semibold">05:00 AM</span>
                    </div>
                    <h5 class="fw-bold text-dark font-heading mb-1 fs-5">Hindustan Times</h5>
                    <div class="text-muted extra-small mb-2">HT (Bihar & National)</div>
                    <p class="text-secondary extra-small mb-3">National coverage, education trends, business analysis, policy reports & world news.</p>
                    <a href="https://epaper.hindustantimes.com/" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-bold mt-auto">
                        <i class="bi bi-book-half me-1"></i> Read HT ePaper →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 6: Today in History & Saran Heritage -->
    <section id="today-history" class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <span class="badge bg-purple-subtle text-purple fw-bold px-3 py-1 rounded-pill small mb-1" style="background: #f3e8ff; color: #6b21a8;">
                    <i class="bi bi-clock-history me-1"></i> On This Day in History
                </span>
                <h2 class="fw-bold font-heading text-dark h3 mb-0">Today in Saran & Bihar History</h2>
            </div>
            <a href="history" class="small text-primary fw-bold text-decoration-none">
                Full Saran District History Archive →
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 today-card">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-primary rounded-pill px-3 py-1 fw-bold"><?php echo date('d F'); ?></span>
                        <h5 class="fw-bold font-heading text-dark mb-0">Heritage Fact of Saran</h5>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        <strong>Chirand Archaeological Significance:</strong> Saran District holds one of the ancient world's most vital Neolithic, Chalcolithic, and Iron Age sites located at <strong>Chirand (Doriganj)</strong> along the holy banks of River Ganga and Ghaghra, where sophisticated bone tools dating back to 2500 BCE were excavated.
                    </p>
                    <div class="p-3 rounded-3 bg-light border small text-muted">
                        <i class="bi bi-lightbulb-fill text-warning me-1"></i> <strong>Did You Know?</strong> Saran has birthed and nurtured monumental national leaders including India's first President <strong>Dr. Rajendra Prasad</strong> (Ziradei / Saran division) and Total Revolution icon <strong>Loknayak Jayaprakash Narayan</strong> (Sitab Diara, Saran).
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 today-card">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold">Sacred Confluences</span>
                        <h5 class="fw-bold font-heading text-dark mb-0">The 3 Sacred Rivers of Saran</h5>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        Saran is famously blessed by three grand Himalayan and perennial river systems: the holy <strong>Ganga</strong>, the mighty <strong>Gandak (Narayani)</strong>, and the sacred <strong>Ghaghra (Sarayu)</strong>, forming the historical <em>Triveni</em> perimeter around the district.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="river" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-water me-1"></i> Rivers of Saran Guide
                        </a>
                        <a href="nahar" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-moisture me-1"></i> Canals & Nahar Network
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 7: Today's Featured Local Listings in Saran -->
    <?php if (!empty($featuredListings)): ?>
    <section class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded-pill small mb-1">
                    <i class="bi bi-check-circle-fill me-1"></i> Verified Local Directory
                </span>
                <h2 class="fw-bold font-heading text-dark h3 mb-0">Featured Local Listings Active in Saran Today</h2>
            </div>
            <a href="search" class="small text-primary fw-bold text-decoration-none">
                Browse All Directory Listings →
            </a>
        </div>

        <div class="row g-3">
            <?php foreach ($featuredListings as $item): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3.5 h-100 today-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1 extra-small fw-bold">
                                <?php echo htmlspecialchars($item['category_name'] ?? 'Directory'); ?>
                            </span>
                            <?php if (($item['is_verified'] ?? 'NO') === 'YES'): ?>
                                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-0.5 extra-small fw-bold">
                                    <i class="bi bi-patch-check-fill me-1"></i> Verified
                                </span>
                            <?php endif; ?>
                        </div>
                        <h5 class="fw-bold text-dark font-heading mb-1 fs-6 text-truncate">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </h5>
                        <div class="text-muted small mb-2 text-truncate">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> <?php echo htmlspecialchars($item['block_name'] ?? 'Saran'); ?>, Bihar
                        </div>
                        <?php if (!empty($item['address'])): ?>
                            <p class="text-secondary extra-small mb-3 text-truncate-2"><?php echo htmlspecialchars($item['address']); ?></p>
                        <?php endif; ?>
                        
                        <div class="mt-auto pt-2 border-top d-flex gap-2">
                            <?php if (!empty($item['mobile'])): ?>
                                <a href="tel:<?php echo htmlspecialchars($item['mobile']); ?>" class="btn btn-outline-primary btn-sm rounded-pill flex-grow-1 fw-bold">
                                    <i class="bi bi-telephone-fill me-1"></i> Call
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($item['whatsapp'])): ?>
                                <a href="https://wa.me/91<?php echo preg_replace('/[^0-9]/', '', $item['whatsapp']); ?>" target="_blank" rel="noopener" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Section 8: Today's 24x7 Emergency Helplines Hub -->
    <section id="duty-contacts" class="mb-5">
        <div class="card border-0 shadow-sm rounded-4 text-white p-4 p-md-5" style="background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%);">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill small mb-2">
                        <i class="bi bi-shield-fill-exclamation me-1"></i> 24x7 EMERGENCY DISPATCH
                    </span>
                    <h2 class="fw-bold font-heading text-white h3 mb-2">Emergency Contacts for Saran District</h2>
                    <p class="text-white-50 small mb-4">
                        Instant 1-click dial to reach police control rooms, hospitals, ambulances, fire brigade, childline, and disaster response teams across Saran.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="tel:112" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold text-danger shadow-sm">
                            <i class="bi bi-telephone-fill me-1"></i> National Emergency (Dial 112)
                        </a>
                        <a href="emergency" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-semibold">
                            <i class="bi bi-card-list me-1"></i> Complete Emergency Directory
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row g-2 text-dark">
                        <div class="col-6">
                            <a href="tel:112" class="card border-0 rounded-3 p-3 text-center text-decoration-none bg-white hover-shadow h-100">
                                <div class="fw-bold fs-5 text-danger">112</div>
                                <div class="text-muted extra-small">Police / Fire / Ambulance</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="tel:102" class="card border-0 rounded-3 p-3 text-center text-decoration-none bg-white hover-shadow h-100">
                                <div class="fw-bold fs-5 text-primary">102</div>
                                <div class="text-muted extra-small">24x7 Ambulance Service</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="tel:06152245000" class="card border-0 rounded-3 p-3 text-center text-decoration-none bg-white hover-shadow h-100">
                                <div class="fw-bold fs-6 text-dark text-truncate">06152-245000</div>
                                <div class="text-muted extra-small">Saran Police Control</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="tel:1912" class="card border-0 rounded-3 p-3 text-center text-decoration-none bg-white hover-shadow h-100">
                                <div class="fw-bold fs-5 text-warning">1912</div>
                                <div class="text-muted extra-small">Electricity Emergency</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Share & Social Action Box -->
    <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-light">
        <h4 class="fw-bold font-heading text-dark mb-2">Share Today's Saran District Bulletin</h4>
        <p class="text-muted small mx-auto mb-3" style="max-width: 600px;">
            Help your fellow citizens, family, and friends in Saran stay updated with today's weather, timings, and essential numbers.
        </p>
        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode("Check what's happening today in Saran District (Weather, Panchang, Office Timings, Fuel Rates & Emergency Contacts): " . BASE_URL . "today"); ?>" target="_blank" rel="noopener" class="btn btn-success rounded-pill px-3.5 py-2 btn-sm fw-bold">
                <i class="bi bi-whatsapp me-1"></i> Share on WhatsApp
            </a>
            <a href="https://t.me/share/url?url=<?php echo urlencode(BASE_URL . 'today'); ?>&text=<?php echo urlencode("What's Today in Saran District Bulletin"); ?>" target="_blank" rel="noopener" class="btn btn-info text-white rounded-pill px-3.5 py-2 btn-sm fw-bold">
                <i class="bi bi-telegram me-1"></i> Telegram
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(BASE_URL . 'today'); ?>" target="_blank" rel="noopener" class="btn btn-primary rounded-pill px-3.5 py-2 btn-sm fw-bold">
                <i class="bi bi-facebook me-1"></i> Facebook
            </a>
        </div>
    </div>

</div>

<!-- Client-side Dynamic Auto-Sync Script via api/today_data.php -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Live Digital Clock Ticker
    function updateClock() {
        const now = new Date();
        const options = { timeZone: 'Asia/Kolkata', hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' };
        const timeStr = now.toLocaleTimeString('en-US', options);
        const clockEl = document.getElementById('live-time');
        if (clockEl) {
            clockEl.textContent = timeStr;
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. Fetch Live Automated Data from Backend API (/api/today_data.php)
    function syncLiveData() {
        const syncBadge = document.getElementById('api-sync-indicator');
        if (syncBadge) {
            syncBadge.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Updating live...';
        }

        fetch('api/today_data.php')
            .then(res => res.json())
            .then(data => {
                if (data && data.success) {
                    // Update Weather
                    if (data.weather) {
                        const w = data.weather;
                        const tempEl = document.getElementById('current-temp');
                        const feelsEl = document.getElementById('feels-like');
                        const humEl = document.getElementById('weather-humidity');
                        const windEl = document.getElementById('weather-wind');
                        const descEl = document.getElementById('weather-desc');
                        const maxEl = document.getElementById('max-temp');
                        const minEl = document.getElementById('min-temp');
                        const iconWrapper = document.getElementById('weather-icon-wrapper');

                        if (tempEl) tempEl.textContent = `${w.temperature}°C`;
                        if (feelsEl) feelsEl.textContent = `${w.feels_like}°C`;
                        if (humEl) humEl.textContent = `${w.humidity}%`;
                        if (windEl) windEl.textContent = `${w.wind_speed} km/h`;
                        if (descEl) descEl.textContent = w.condition;
                        if (maxEl) maxEl.textContent = `${w.max_temp}°C`;
                        if (minEl) minEl.textContent = `${w.min_temp}°C`;

                        let iconClass = "bi-sun-fill text-warning";
                        if (w.weather_code >= 1 && w.weather_code <= 3) iconClass = "bi-cloud-sun-fill text-warning";
                        else if (w.weather_code >= 45 && w.weather_code <= 48) iconClass = "bi-cloud-haze2-fill text-light";
                        else if (w.weather_code >= 51 && w.weather_code <= 67) iconClass = "bi-cloud-drizzle-fill text-info";
                        else if (w.weather_code >= 71 && w.weather_code <= 82) iconClass = "bi-cloud-rain-heavy-fill text-info";
                        else if (w.weather_code >= 95) iconClass = "bi-cloud-lightning-rain-fill text-warning";

                        if (iconWrapper) iconWrapper.innerHTML = `<i class="bi ${iconClass}"></i>`;

                        // Update Sunrise / Sunset
                        if (w.sunrise) {
                            const sunriseEl = document.getElementById('live-sunrise');
                            if (sunriseEl) sunriseEl.textContent = w.sunrise;
                        }
                        if (w.sunset) {
                            const sunsetEl = document.getElementById('live-sunset');
                            if (sunsetEl) sunsetEl.textContent = w.sunset;
                        }
                    }

                    // Update Historical Events if container exists
                    if (data.history_events && data.history_events.length > 0) {
                        const histContainer = document.getElementById('live-history-events');
                        if (histContainer) {
                            let histHtml = '';
                            data.history_events.forEach(item => {
                                histHtml += `
                                    <div class="p-2.5 rounded-3 bg-white border mb-2 shadow-2xs">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-primary-subtle text-primary fw-bold rounded-pill px-2 py-0.5 extra-small">${item.year}</span>
                                        </div>
                                        <div class="small text-secondary" style="line-height: 1.5;">${item.text}</div>
                                    </div>
                                `;
                            });
                            histContainer.innerHTML = histHtml;
                        }
                    }

                    // Update Sync Badge
                    if (syncBadge) {
                        syncBadge.innerHTML = `<i class="bi bi-check2-circle text-success me-1"></i> Auto-synced via Live API (${data.time_formatted})`;
                    }
                }
            })
            .catch(err => {
                if (syncBadge) {
                    syncBadge.innerHTML = '<i class="bi bi-cloud-check text-warning me-1"></i> Cached Data Active';
                }
                console.log("API auto-sync fallback:", err);
            });
    }

    // Initial sync and recurring automatic sync every 60 seconds
    syncLiveData();
    setInterval(syncLiveData, 60000);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

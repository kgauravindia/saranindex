<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "Sonpur Mela (Sonepur Fair) – Asia's Largest Cattle & Cultural Fair | Harihar Kshetra Saran";
$meta_description = "Complete guide to the world-famous Sonpur Mela (Harihar Kshetra Mela) in Sonpur, Saran (Bihar). Explore the sacred Ganga-Gandak confluence, Harihar Nath Temple, horse & cattle bazaar, cultural events, Swiss cottage tents, travel routes, and 24x7 helplines.";
$meta_keywords = "Sonpur Mela, Sonepur Fair, Harihar Kshetra Mela, Asia largest cattle fair, Harihar Nath Temple Sonpur, Ganga Gandak Sangam, Sonepur mela dates, Sonepur tourism tents, JP Setu Sonpur, Chapra to Sonpur, Sonepur fair attractions";

// Fetch verified Sonpur block listings for practical visitor assistance
$db = getDB();
$sonepurListings = [];
$totalSonepurListings = 0;

if ($db) {
    try {
        $cntStmt = $db->query("SELECT count(*) FROM listings WHERE block_id = 3 AND status = 'ACTIVE'");
        $totalSonepurListings = intval($cntStmt->fetchColumn() ?: 1002);

        $stmt = $db->prepare("
            SELECT l.id, l.title, l.hindi_title, l.slug, l.contact_person, l.mobile, l.mobile_visibility,
                   l.address, l.pincode, c.name as category_name, c.icon as category_icon
            FROM listings l
            LEFT JOIN categories c ON l.category_id = c.id
            WHERE l.block_id = 3 AND l.status = 'ACTIVE'
            ORDER BY l.id ASC
            LIMIT 12
        ");
        $stmt->execute();
        $sonepurListings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching Sonpur listings: " . $e->getMessage());
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom Styling for Sonpur Mela Portal -->
<style>
.mela-hero-bg {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 45%, #4338ca 80%, #b45309 100%);
    color: #ffffff;
    position: relative;
    overflow: hidden;
}
.mela-hero-bg::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -15%;
    width: 650px;
    height: 650px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.22) 0%, rgba(217, 119, 6, 0.08) 50%, transparent 70%);
    pointer-events: none;
}
.mela-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    background: #ffffff;
}
.mela-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 18px 35px -10px rgba(67, 56, 202, 0.16) !important;
    border-color: #f59e0b;
}
.quick-mela-pill {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #334155;
    font-weight: 600;
    padding: 0.5rem 1.1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    cursor: pointer;
}
.quick-mela-pill:hover, .quick-mela-pill.active {
    background: #f59e0b;
    color: #0f172a !important;
    border-color: #f59e0b;
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
}
.badge-gold {
    background: #fef3c7;
    color: #92400e;
    font-weight: 700;
}
.badge-indigo {
    background: #e0e7ff;
    color: #3730a3;
    font-weight: 700;
}
.badge-emerald {
    background: #d1fae5;
    color: #065f46;
    font-weight: 700;
}
.legend-box {
    background: linear-gradient(135deg, #fdfbf7 0%, #fffbeb 100%);
    border-left: 5px solid #d97706;
    border-radius: 16px;
    padding: 1.5rem;
}
.helpline-box {
    border-radius: 16px;
    background: #ffffff;
    border: 1px solid #fecdd3;
    transition: all 0.25s ease;
}
.helpline-box:hover {
    border-color: #e11d48;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -5px rgba(225, 29, 72, 0.12);
}
</style>

<!-- Hero Section -->
<div class="mela-hero-bg py-5 position-relative">
    <div class="container position-relative z-2 py-4">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="./" class="text-white-50 text-decoration-none"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="block/sonpur" class="text-white-50 text-decoration-none">Sonpur Block</a></li>
                <li class="breadcrumb-item active text-warning fw-semibold" aria-current="page">Sonpur Mela Guide</li>
            </ol>
        </nav>

        <div class="row align-items-center g-4 mb-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                    <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-warning text-dark fw-bold small shadow-sm">
                        <i class="bi bi-fire fs-6"></i>
                        <span>Asia's Largest Historic Fair • Harihar Kshetra</span>
                    </span>
                    <a href="https://saran.nic.in/festival/sonepur-mela/" target="_blank" rel="noopener" class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 small fw-semibold text-decoration-none border border-white border-opacity-25">
                        <i class="bi bi-patch-check-fill text-warning me-1"></i> Official Ref: saran.nic.in <i class="bi bi-box-arrow-up-right ms-1" style="font-size:0.65rem;"></i>
                    </a>
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 small fw-semibold">
                        <i class="bi bi-geo-alt-fill text-warning me-1"></i> Sonpur, Saran (Bihar)
                    </span>
                    <span class="badge bg-success rounded-pill px-3 py-1.5 small fw-semibold">
                        <i class="bi bi-calendar-event me-1"></i> Kartik Purnima Annual Celebration
                    </span>
                </div>

                <h1 class="fw-bolder font-heading text-white display-5 mb-2">
                    Sonpur Mela <span class="text-warning">(हरिहर क्षेत्र सोनपुर मेला)</span>
                </h1>
                <p class="lead text-white-50 mb-4" style="max-width: 760px; line-height: 1.65;">
                    The world-renowned annual cultural, spiritual, and cattle fair held on the sacred confluence of the <strong>Ganga and Gandak (Narayani)</strong> rivers. Known historically as Asia's largest livestock market, Sonpur Mela is an extraordinary tapestry of Vedic spirituality, royal heritage, folklore, amusement, and vibrant Bihar culture.
                </p>

                <!-- Quick Action Buttons -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="#attractions" class="btn btn-warning text-dark fw-bold px-4 py-2.5 rounded-pill shadow">
                        <i class="bi bi-compass me-1"></i> Explore Fair Attractions
                    </a>
                    <a href="#how-to-reach" class="btn btn-light text-primary fw-bold px-4 py-2.5 rounded-pill shadow">
                        <i class="bi bi-geo-alt me-1"></i> How to Reach & Route Guide
                    </a>
                    <a href="#helpline" class="btn btn-outline-light fw-bold px-4 py-2.5 rounded-pill">
                        <i class="bi bi-telephone-fill text-warning me-1"></i> 24x7 Mela Helplines
                    </a>
                    <a href="hindi/sonpur-mela" class="btn btn-outline-warning fw-semibold px-3.5 py-2.5 rounded-pill" title="हिंदी में पढ़ें">
                        <i class="bi bi-translate me-1"></i> हिंदी संस्करण
                    </a>
                </div>
            </div>

            <!-- Hero Highlights Card -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 p-4 shadow-lg" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-warning text-dark p-2 rounded-circle fs-5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 font-heading">Key Fair Facts</h6>
                            <small class="text-muted">Sonpur, Saran District</small>
                        </div>
                    </div>

                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2.5 font-sans" style="font-size: 0.9rem;">
                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2">
                            <span class="text-muted"><i class="bi bi-calendar3 me-1.5 text-primary"></i>Period</span>
                            <strong class="text-dark">Kartik Purnima (~30 Days)</strong>
                        </li>
                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2">
                            <span class="text-muted"><i class="bi bi-water me-1.5 text-info"></i>Confluence</span>
                            <strong class="text-dark">Ganga & Gandak Sangam</strong>
                        </li>
                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2">
                            <span class="text-muted"><i class="bi bi-building me-1.5 text-danger"></i>Prime Deity</span>
                            <strong class="text-dark">Baba Harihar Nath (Hari & Har)</strong>
                        </li>
                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2">
                            <span class="text-muted"><i class="bi bi-train-front me-1.5 text-success"></i>Nearest Stn</span>
                            <strong class="text-dark">Sonpur Jn (SEE) / Hajipur</strong>
                        </li>
                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2">
                            <span class="text-muted"><i class="bi bi-signpost-split me-1.5 text-warning"></i>Distance from Patna</span>
                            <strong class="text-dark">15 km (JP Setu Bridge)</strong>
                        </li>
                        <li class="d-flex align-items-center justify-content-between pt-1">
                            <span class="text-muted"><i class="bi bi-building-check me-1.5 text-primary"></i>Local Directory</span>
                            <span class="badge bg-primary rounded-pill"><?php echo number_format($totalSonepurListings); ?>+ Verified Listings</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Main Content Area -->
<div class="container py-5">

    <!-- KPI Statistics Strip -->
    <div class="row g-3 mb-5">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-warning">
                <div class="text-warning fs-2 mb-1"><i class="bi bi-clock-history"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">2,000+</div>
                <div class="text-muted small fw-semibold">Years of Recorded History</div>
                <div class="badge bg-warning-subtle text-dark rounded-pill mt-2 px-2.5 py-1 small fw-bold">Ancient Mauryan Roots</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-primary">
                <div class="text-primary fs-2 mb-1"><i class="bi bi-people-fill"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">30+ Lakh</div>
                <div class="text-muted small fw-semibold">Pilgrims & Tourists</div>
                <div class="badge bg-primary-subtle text-primary rounded-pill mt-2 px-2.5 py-1 small fw-bold">Global Visitors</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-success">
                <div class="text-success fs-2 mb-1"><i class="bi bi-calendar-check"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">32 Days</div>
                <div class="text-muted small fw-semibold">Fair Duration</div>
                <div class="badge bg-success-subtle text-success rounded-pill mt-2 px-2.5 py-1 small fw-bold">Nov - Dec Annually</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-danger">
                <div class="text-danger fs-2 mb-1"><i class="bi bi-telephone-outbound"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">24x7</div>
                <div class="text-muted small fw-semibold">Mela Control Room</div>
                <div class="badge bg-danger-subtle text-danger rounded-pill mt-2 px-2.5 py-1 small fw-bold">06158-222201 / 112</div>
            </div>
        </div>
    </div>

    <!-- Official Administration Callout from saran.nic.in -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5 bg-white border-start border-5 border-danger">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-danger text-white fw-bold px-2.5 py-1 rounded-pill small">
                        <i class="bi bi-bank me-1"></i> Official Government Administration
                    </span>
                    <span class="text-muted small fw-semibold">District Administration Saran (Govt of Bihar / NIC)</span>
                </div>
                <h4 class="fw-bold font-heading text-dark mb-2">
                    Official Reference: Saran District Administration (<a href="https://saran.nic.in/festival/sonepur-mela/" target="_blank" rel="noopener" class="text-primary text-decoration-none">saran.nic.in</a>)
                </h4>
                <p class="text-secondary small mb-0 leading-relaxed">
                    Under the leadership of the <strong>District Magistrate & Collector, Saran</strong> and <strong>Superintendent of Police, Saran</strong>, the Mela grounds are governed through a dedicated Central Camp Office, Sub-Divisional Officer (SDO) Sonpur, Sector Magistrates, Sairat land distribution, public sanitation grids, 24x7 Lost & Found booths (खोया-पाया केंद्र), and riverfront safety managed by SDRF/NDRF.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end text-start">
                <div class="d-flex flex-column gap-2">
                    <a href="https://saran.nic.in/festival/sonepur-mela/" target="_blank" rel="noopener" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center justify-content-center gap-1.5 shadow-xs">
                        <i class="bi bi-box-arrow-up-right"></i> saran.nic.in Mela Portal
                    </a>
                    <a href="https://saran.nic.in/tourism/places-of-interest/" target="_blank" rel="noopener" class="btn btn-light btn-sm rounded-pill px-3 py-2 fw-semibold border d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-geo-alt-fill text-danger"></i> NIC Tourist Attractions
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mythological & Historical Origins Section -->
    <div class="row g-4 align-items-center mb-5 pb-3">
        <div class="col-lg-7">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-warning-subtle text-warning-emphasis fw-bold small mb-2">
                <i class="bi bi-book-half"></i>
                <span>Timeless Heritage & Legends</span>
            </div>
            <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                The Sacred Legend of <span class="text-primary">Gaj-Graha & Harihar Nath</span>
            </h2>
            <p class="text-secondary mb-3 leading-relaxed">
                The spiritual foundation of Sonpur Mela is deeply woven into the ancient scriptures of the <em>Srimad Bhagavata Purana</em>. According to the legend of <strong>Gajendra Moksha (Gaj-Graha)</strong>, King Indradyumna (cursed as the elephant king Gajendra) was attacked by King Huhu (cursed as the giant crocodile Graha) at the confluence of the sacred Gandak and Ganga.
            </p>
            <p class="text-secondary mb-3 leading-relaxed">
                When the elephant exhausted all mortal strength after a fierce millennium-long battle, he plucked a lotus flower with his trunk and surrendered to Lord Vishnu: <em>"Om Namo Bhagavate Vasudevaya"</em>. Pleased with his devotion, Lord Vishnu arrived mounted on Garuda and liberated both souls with His divine Sudarshana Chakra at this holy spot.
            </p>
            <div class="legend-box mb-3">
                <h6 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-flower1 text-warning fs-5"></i> Baba Harihar Nath: Divine Union of Hari & Har
                </h6>
                <p class="text-muted small mb-0">
                    The temple of Baba Harihar Nath is singularly unique in Hinduism because Lord Vishnu (Hari) and Lord Shiva (Har) are consecrated together in the same lingam. Legend holds that Lord Rama, accompanied by his brother Lakshmana and Sage Vishwamitra, halted here on their way to the royal court of Janakpur (Mithila) for Sita's Swayamvar and performed consecration of the temple.
                </p>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                <h5 class="fw-bold font-heading text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-shaded text-primary"></i> Historical Chronology
                </h5>
                <div class="timeline-simple">
                    <div class="border-start border-2 border-primary ps-3 pb-3 position-relative">
                        <span class="position-absolute top-0 start-0 translate-middle bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
                        <div class="fw-bold text-dark small">Mauryan Empire (c. 300 BCE)</div>
                        <p class="text-muted small mb-0">Emperor Chandragupta Maurya and later Mauryan generals purchased elephants and horses across the Gandak river to assemble the imperial army.</p>
                    </div>
                    <div class="border-start border-2 border-warning ps-3 pb-3 position-relative">
                        <span class="position-absolute top-0 start-0 translate-middle bg-warning rounded-circle" style="width: 12px; height: 12px;"></span>
                        <div class="fw-bold text-dark small">Mughal Era & Aurangzeb's Relocation (saran.nic.in record)</div>
                        <p class="text-muted small mb-0">As recorded on the official Saran District portal, the fair was originally convened across the river at Hajipur, with devotees crossing the Gandak only for puja at Harihar Nath. During the reign of Emperor Aurangzeb, the fair was permanently shifted to Sonepur.</p>
                    </div>
                    <div class="border-start border-2 border-success ps-3 pb-3 position-relative">
                        <span class="position-absolute top-0 start-0 translate-middle bg-success rounded-circle" style="width: 12px; height: 12px;"></span>
                        <div class="fw-bold text-dark small">Temple Reconstruction & Royal Endowments (18th c.)</div>
                        <p class="text-muted small mb-0">Raja Ram Narayan (Naib Subahdar of Bihar) reconstructed the stone sanctum, followed by generous historical additions by the Maharaja of Hathwa and Birla Trust.</p>
                    </div>
                    <div class="ps-3 position-relative">
                        <span class="position-absolute top-0 start-0 translate-middle bg-info rounded-circle" style="width: 12px; height: 12px;"></span>
                        <div class="fw-bold text-dark small">1857 Freedom Struggle & Veer Kunwar Singh</div>
                        <p class="text-muted small mb-0">Babu Veer Kunwar Singh secretly gathered regional chieftains, zamindars, and freedom fighters under the guise of attending Sonpur Mela to plan the rebellion against the British East India Company.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Major Fair Attractions & Zones Section -->
    <div id="attractions" class="pt-4 mb-5">
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">
                <i class="bi bi-grid-3x3-gap-fill me-1"></i> Fair Experience
            </span>
            <h2 class="fw-bold font-heading text-dark mt-2 mb-2 fs-1">Major Zones & Attractions</h2>
            <p class="text-muted mx-auto mb-0" style="max-width: 680px;">
                Spread across several square kilometers, Sonpur Mela is split into diverse vibrant zones combining traditional rural commerce, spirituality, and modern entertainment.
            </p>
        </div>

        <div class="row g-4">
            
            <!-- Card 1: Harihar Nath Mandir & Sangam Snan -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 mela-card p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="badge-indigo rounded-circle p-2.5 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-building fs-4"></i>
                        </div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill">Spiritual Center</span>
                    </div>
                    <h5 class="fw-bold font-heading text-dark mb-2">Harihar Nath Mandir & Sangam Ghat</h5>
                    <p class="text-muted small mb-3">
                        On Kartik Purnima, hundreds of thousands of devotees take a holy dip in the Ganga-Gandak confluence (Sangam) at Kali Ghat. Grand evening Maha Aartis rivaling Varanasi and Haridwar are organized daily by Bihar Tourism.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> Daily Maha Aarti at Sangam Ghat</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> 24x7 special darshan queue systems</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> SDRF & NDRF motorboat rescue cover</li>
                    </ul>
                </div>
            </div>

            <!-- Card 2: Animal & Horse Bazaar -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 mela-card p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="badge-gold rounded-circle p-2.5 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-trophy fs-4 text-warning"></i>
                        </div>
                        <span class="badge bg-warning-subtle text-dark rounded-pill">World Famous</span>
                    </div>
                    <h5 class="fw-bold font-heading text-dark mb-2">Ghora Bazar & Cattle Exhibition</h5>
                    <p class="text-muted small mb-3">
                        The historic hallmark of the fair. High-pedigree dancing horses, Marwari & Kathiawari breeds, pedigree cattle, and camels from Rajasthan. Elephants participate in ceremonial royal bathing exhibitions under Wildlife Board conservation.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> Horse races and obedience displays</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> High-yield Murrah buffaloes & dairy cows</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> Dogs, birds, and ornamental fowl bazaar</li>
                    </ul>
                </div>
            </div>

            <!-- Card 3: Cultural Pandal -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 mela-card p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="badge-emerald rounded-circle p-2.5 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-music-note-beamed fs-4 text-success"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill">Nightly Shows</span>
                    </div>
                    <h5 class="fw-bold font-heading text-dark mb-2">Harihar Mahotsav, Cultural Stage & Sonpur Idol</h5>
                    <p class="text-muted small mb-3">
                        As highlighted on saran.nic.in, the district hosts the official <strong>Harihar Mahotsav</strong> and state-level <strong>"Sonpur Idol"</strong> singing competition for budding vocalists across Bihar, alongside star-studded cultural concerts, traditional Dangal (wrestling), and Kavi Sammelans.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> "Sonpur Idol" state vocal talent hunt</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> All-India Kavi Sammelan & Mushaira</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> Traditional Dangal (wrestling) & boat races</li>
                    </ul>
                </div>
            </div>

            <!-- Card 4: Carnival & Thrills -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 mela-card p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-danger-subtle text-danger rounded-circle p-2.5 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-ticket-perforated fs-4"></i>
                        </div>
                        <span class="badge bg-danger-subtle text-danger rounded-pill">Amusement Park</span>
                    </div>
                    <h5 class="fw-bold font-heading text-dark mb-2">Giant Rides, Circus & Maut Ka Kuan</h5>
                    <p class="text-muted small mb-3">
                        The amusement zone is a child's dream and an adrenaline rush. Features giant Ferris wheels, high-speed Tora-Tora, roller coasters, multi-ring circus troupes, and the heart-stopping stunt motorcycle display of <em>Maut Ka Kuan</em> (Well of Death).
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> Mega Ferris Wheel & Ranger rides</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> International circus & magic pavilions</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> Gandak river recreational speedboating</li>
                    </ul>
                </div>
            </div>

            <!-- Card 5: Food Street & Delicacies -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 mela-card p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-warning-subtle text-dark rounded-circle p-2.5 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-cup-hot-fill fs-4 text-warning"></i>
                        </div>
                        <span class="badge bg-warning-subtle text-dark rounded-pill">Foodie Paradise</span>
                    </div>
                    <h5 class="fw-bold font-heading text-dark mb-2">Authentic Bihari Cuisine & Mela Sweets</h5>
                    <p class="text-muted small mb-3">
                        The aroma of sizzling ghee and earthen ovens fills the air. Taste authentic Champaran Ahuna Handi Meat cooked in clay pots, wood-fired Litti Chokha, hot Jalebi, Sonpur's signature Batasha sweets, and Khaja.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> Traditional Litti Chokha with pure desi ghee</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> World-famous Champaran Handi Mutton</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> Sonpur Batasha & Silao Khaja</li>
                    </ul>
                </div>
            </div>

            <!-- Card 6: Handlooms & Cottage Bazaar -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 mela-card p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-info-subtle text-info rounded-circle p-2.5 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-bag-check-fill fs-4"></i>
                        </div>
                        <span class="badge bg-info-subtle text-info rounded-pill">Handicrafts</span>
                    </div>
                    <h5 class="fw-bold font-heading text-dark mb-2">Artisans, Furniture & Khadi Pavilion</h5>
                    <p class="text-muted small mb-3">
                        Artisans from across India set up stalls. Carved teak and sheesham furniture from Saharanpur, brass and copper kitchen utensils, Mirzapur hand-knotted wool rugs, Kashmiri shawls, and Bihar Khadi village products.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> Saharanpur solid wood dining & bedroom sets</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> Brass puja sets & kitchenware</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> Agricultural tools, plows, and seed crushers</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <!-- Travel, Route & Accommodation Guide -->
    <div id="how-to-reach" class="pt-4 mb-5">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-dark text-white py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="fw-bold font-heading mb-0 text-white d-flex align-items-center gap-2">
                        <i class="bi bi-signpost-2 text-warning"></i> Travel Logistics & How to Reach Sonpur
                    </h4>
                    <small class="text-white-50">Smooth connectivity from Patna, Chapra, Hajipur, and across India.</small>
                </div>
                <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1.5">Saran District, Bihar</span>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    
                    <div class="col-lg-4">
                        <div class="p-3.5 rounded-4 bg-light border h-100">
                            <div class="d-flex align-items-center gap-2 mb-2 text-primary">
                                <i class="bi bi-train-front-fill fs-3"></i>
                                <h5 class="fw-bold font-heading mb-0 text-dark">By Railway</h5>
                            </div>
                            <p class="text-muted small mb-2">
                                <strong>Sonpur Junction (Station Code: SEE)</strong> is an A-grade railway division headquarters on East Central Railway (ECR) located just ~2 km from Harihar Nath Temple, boasting one of India's longest railway platforms (~736 m / 2,415 ft).
                            </p>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-primary"></i> Direct express trains from Delhi, Kolkata, Mumbai, Guwahati, Lucknow.</li>
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-primary"></i> <strong>Hajipur Junction (HJP)</strong>: Just 5 km away across the Gandak bridge.</li>
                                <li><i class="bi bi-arrow-right-short text-primary"></i> Special daily EMU local shuttle trains run round the clock between Patna, Patliputra, and Sonpur during Mela.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="p-3.5 rounded-4 bg-light border h-100">
                            <div class="d-flex align-items-center gap-2 mb-2 text-success">
                                <i class="bi bi-car-front-fill fs-3"></i>
                                <h5 class="fw-bold font-heading mb-0 text-dark">By Road & Air</h5>
                            </div>
                            <p class="text-muted small mb-2">
                                Seamless four-lane highway connectivity across major river bridges directly into the fairgrounds:
                            </p>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-success"></i> <strong>Patna Airport (PAT) (~25 km)</strong>: Jay Prakash Narayan Airport reached in 30–35 min via JP Setu.</li>
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-success"></i> <strong>From Patna City (15 km)</strong>: Drive across JP Setu (Digha-Sonpur Bridge) in 20–25 minutes.</li>
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-success"></i> <strong>From Chapra (45 km)</strong>: NH-19 expressway via Garkha & Dighwara directly into Sonpur.</li>
                                <li><i class="bi bi-arrow-right-short text-success"></i> Dedicated multi-acre visitor parking lots managed by District Traffic Police at Nakhas and Bajitpur.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="p-3.5 rounded-4 bg-light border h-100">
                            <div class="d-flex align-items-center gap-2 mb-2 text-warning">
                                <i class="bi bi-houses-fill fs-3 text-warning"></i>
                                <h5 class="fw-bold font-heading mb-0 text-dark">Where to Stay</h5>
                            </div>
                            <p class="text-muted small mb-2">
                                Options for families, tourists, and pilgrims range from luxury riverfront tents to city hotels:
                            </p>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-warning"></i> <strong>Bihar Tourism Swiss Cottages</strong>: Luxury AC tent city with dining & cultural lawn setup on the Gandak bank.</li>
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-warning"></i> <strong>BSTDC Tourist Bunglows & Dharamshalas</strong>: Economical pilgrim accommodations near Harihar Nath Temple.</li>
                                <li><i class="bi bi-arrow-right-short text-warning"></i> <strong>Patna / Hajipur Hotels</strong>: Commute daily in 20 minutes from top hotels in Patna via JP Setu.</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- 24x7 Helplines & Emergency Support Section -->
    <div id="helpline" class="pt-4 mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">
                    <i class="bi bi-shield-check me-1"></i> Public Safety & Assistance
                </span>
                <h3 class="fw-bold font-heading text-dark mt-2 mb-0">Sonpur Mela 24x7 Emergency Helplines</h3>
                <p class="text-muted small mb-0">Official phone numbers of administrative, police, medical, and river safety desks.</p>
            </div>
            <a href="emergency" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                <i class="bi bi-telephone-plus me-1"></i> All District Helplines
            </a>
        </div>

        <div class="row g-3">
            <div class="col-md-6 col-lg-3">
                <div class="helpline-box p-3.5 h-100 text-center">
                    <div class="text-danger fs-2 mb-1"><i class="bi bi-shield-fill-exclamation"></i></div>
                    <h6 class="fw-bold text-dark mb-1">Police Emergency (ERSS)</h6>
                    <div class="display-6 fw-bold text-danger font-monospace mb-1">112</div>
                    <small class="text-muted d-block mb-2">Toll-free 24x7 immediate police dispatch</small>
                    <a href="tel:112" class="btn btn-danger btn-sm w-100 rounded-pill fw-bold">Call 112</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="helpline-box p-3.5 h-100 text-center">
                    <div class="text-primary fs-2 mb-1"><i class="bi bi-headset"></i></div>
                    <h6 class="fw-bold text-dark mb-1">Mela Central Control Room</h6>
                    <div class="fs-5 fw-bold text-primary font-monospace mb-1">06158-222201</div>
                    <small class="text-muted d-block mb-2">Central Camp Office Sonpur (NIC Saran)</small>
                    <a href="tel:06158222201" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold">Call Control Room</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="helpline-box p-3.5 h-100 text-center">
                    <div class="text-success fs-2 mb-1"><i class="bi bi-hospital-fill"></i></div>
                    <h6 class="fw-bold text-dark mb-1">Medical & Ambulance Camp</h6>
                    <div class="fs-5 fw-bold text-success font-monospace mb-1">102 / 06158-222223</div>
                    <small class="text-muted d-block mb-2">Sonpur Sub-Divisional Hospital & Field ICU</small>
                    <a href="tel:102" class="btn btn-success btn-sm w-100 rounded-pill fw-bold">Call Ambulance 102</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="helpline-box p-3.5 h-100 text-center">
                    <div class="text-warning fs-2 mb-1"><i class="bi bi-lightning-charge-fill"></i></div>
                    <h6 class="fw-bold text-dark mb-1">Electricity Fault (NBPDCL)</h6>
                    <div class="display-6 fw-bold text-dark font-monospace mb-1">1912</div>
                    <small class="text-muted d-block mb-2">Sonpur ESSD: 7763815208 / 24x7 Helpline</small>
                    <a href="tel:1912" class="btn btn-warning text-dark btn-sm w-100 rounded-pill fw-bold">Call 1912</a>
                </div>
            </div>
        </div>

        <!-- Official Administrative Helplines Table/Strip -->
        <div class="mt-4 p-3.5 rounded-4 bg-white border shadow-xs">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger-subtle text-danger fw-bold rounded-pill px-2.5 py-1 small">
                        <i class="bi bi-person-badge-fill me-1"></i> District Administration Saran
                    </span>
                    <h6 class="fw-bold font-heading text-dark mb-0">Official Administrative & Police Officers Directory</h6>
                </div>
                <a href="https://saran.nic.in/festival/sonepur-mela/" target="_blank" rel="noopener" class="small text-decoration-none fw-semibold text-danger">
                    Source: saran.nic.in <i class="bi bi-box-arrow-up-right ms-0.5"></i>
                </a>
            </div>
            <div class="row g-2 text-center text-sm-start">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="p-2.5 rounded-3 bg-light border">
                        <div class="text-muted" style="font-size: 0.75rem;">DM Saran (Chapra)</div>
                        <a href="tel:06152245055" class="fw-bold font-monospace text-dark text-decoration-none small d-block">06152-245055</a>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="p-2.5 rounded-3 bg-light border">
                        <div class="text-muted" style="font-size: 0.75rem;">SP Saran (Chapra)</div>
                        <a href="tel:06152245058" class="fw-bold font-monospace text-dark text-decoration-none small d-block">06152-245058</a>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="p-2.5 rounded-3 bg-light border">
                        <div class="text-muted" style="font-size: 0.75rem;">SDO Sonpur</div>
                        <a href="tel:06158222202" class="fw-bold font-monospace text-primary text-decoration-none small d-block">06158-222202</a>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="p-2.5 rounded-3 bg-light border">
                        <div class="text-muted" style="font-size: 0.75rem;">SDPO Sonpur</div>
                        <a href="tel:06158222203" class="fw-bold font-monospace text-primary text-decoration-none small d-block">06158-222203</a>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="p-2.5 rounded-3 bg-light border">
                        <div class="text-muted" style="font-size: 0.75rem;">Sonpur Police Station</div>
                        <a href="tel:9431822851" class="fw-bold font-monospace text-danger text-decoration-none small d-block">9431822851</a>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="p-2.5 rounded-3 bg-light border">
                        <div class="text-muted" style="font-size: 0.75rem;">Fire Control Room</div>
                        <a href="tel:101" class="fw-bold font-monospace text-danger text-decoration-none small d-block">101 / 112</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verified Sonpur Local Directory Section -->
    <div class="pt-4 mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">
                    <i class="bi bi-check-circle-fill me-1"></i> Verified Local Services
                </span>
                <h3 class="fw-bold font-heading text-dark mt-2 mb-0">Essential Services & Contacts in Sonpur</h3>
                <p class="text-muted small mb-0">Browse shops, medical stores, hotels, transport, and utilities registered in Sonpur block.</p>
            </div>
            <a href="block/sonpur" class="btn btn-primary rounded-pill px-4 fw-bold">
                View All <?php echo number_format($totalSonepurListings); ?>+ Sonpur Listings <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            <?php if (!empty($sonepurListings)): ?>
                <?php foreach ($sonepurListings as $item): ?>
                    <?php 
                    $profileUrl = getListingUrl($item['slug']);
                    $maskedMobile = !empty($item['mobile']) ? maskPhoneNumber($item['mobile']) : 'Available on Profile';
                    ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border rounded-4 p-3.5 bg-white shadow-xs hover-lift d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-1.5 mb-2">
                                    <span class="badge bg-light text-secondary border rounded-pill small">
                                        <i class="bi bi-tag-fill me-1 text-primary"></i><?php echo htmlspecialchars($item['category_name'] ?? 'Local Service'); ?>
                                    </span>
                                </div>
                                <h6 class="fw-bold font-heading text-dark mb-1 text-truncate" title="<?php echo htmlspecialchars($item['title']); ?>">
                                    <?php echo htmlspecialchars($item['title']); ?>
                                </h6>
                                <p class="text-muted small mb-2 text-truncate">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i><?php echo htmlspecialchars($item['address'] ?: 'Sonpur, Saran'); ?>
                                </p>
                            </div>

                            <div class="pt-2 border-top mt-2">
                                <div class="d-flex align-items-center justify-content-between small mb-2">
                                    <span class="text-muted"><i class="bi bi-telephone me-1 text-success"></i>Phone:</span>
                                    <strong class="font-monospace text-dark"><?php echo htmlspecialchars($maskedMobile); ?></strong>
                                </div>
                                <a href="<?php echo $profileUrl; ?>" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-semibold">
                                    View Full Profile <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Practical Visitor Tips Box -->
    <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: linear-gradient(135deg, #eff6ff 0%, #fdf2f8 100%); border: 1px solid #bfdbfe !important;">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <h5 class="fw-bold font-heading text-dark mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-lightbulb-fill text-warning fs-4"></i> Visitor Tips for Sonpur Mela
                </h5>
                <ul class="mb-0 text-secondary small" style="line-height: 1.8;">
                    <li><strong>Best Hours:</strong> Visit early morning (5:00 AM – 9:00 AM) for holy Sangam Snan and the horse/cattle market. Visit evening (5:00 PM – 10:00 PM) for the Ganga Aarti, cultural concerts, and carnival rides.</li>
                    <li><strong>Footwear:</strong> Wear comfortable walking sneakers as the fairgrounds span across several square kilometers.</li>
                    <li><strong>Patna Commute:</strong> If staying in Patna, cross via JP Setu outside peak evening hours (6:00 PM – 8:00 PM) to avoid traffic on the bridge approach.</li>
                    <li><strong>River Safety:</strong> Always bathe only within the designated barricaded safety zones at Kali Ghat; listen to SDRF safety announcements.</li>
                </ul>
            </div>
            <div class="col-md-4 text-md-end text-center">
                <a href="block/sonpur" class="btn btn-primary fw-bold px-4 py-2.5 rounded-pill shadow-sm mb-2 w-100 w-md-auto">
                    <i class="bi bi-compass me-1"></i> Sonpur Civic Portal
                </a>
                <br>
                <a href="tel:06158222201" class="btn btn-outline-dark fw-semibold btn-sm px-3 py-2 rounded-pill w-100 w-md-auto">
                    <i class="bi bi-telephone-fill me-1"></i> Mela Control Room: 06158-222201
                </a>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

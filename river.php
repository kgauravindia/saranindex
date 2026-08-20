<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "Rivers of Saran District (Ganga, Ghaghra, Gandak & Tributaries) | Saran Index";
$meta_description = "Complete guide to all rivers of Saran District (Chapra, Bihar). Explore the holy Ganga, Ghaghra (Sarayu), Gandak (Narayani), Mahi, Khaura, Jharahi, Sondhi rivers, sacred river confluences (Sangams), Diara floodplains, and river bridges.";
$meta_keywords = "Rivers of Saran, Saran District Rivers, Ganga in Chapra, Ghaghra Sarayu River, Gandak Narayani River, Mahi River Saran, Sonpur Sangam, Doriganj Sangam, Arrah Chapra Bridge, JP Setu Sonpur, Saran Diara";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom Styles for River Page -->
<style>
.river-hero-bg {
    background: linear-gradient(135deg, #0c2d48 0%, #145da0 50%, #0e86d4 100%);
    color: #ffffff;
}
.river-card {
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    background: #ffffff;
    overflow: hidden;
}
.river-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 35px -10px rgba(14, 134, 212, 0.2) !important;
    border-color: #7dd3fc;
}
.river-badge-blue {
    background: #e0f2fe;
    color: #0369a1;
    font-weight: 700;
}
.river-badge-teal {
    background: #ccfbf1;
    color: #0f766e;
    font-weight: 700;
}
.river-badge-amber {
    background: #fef3c7;
    color: #b45309;
    font-weight: 700;
}
.river-stat-box {
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 0.75rem 1rem;
}
.quick-nav-pill {
    background: #ffffff;
    border: 1px solid #e2e8f0;
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
}
.quick-nav-pill:hover, .quick-nav-pill.active {
    background: #0284c7;
    color: #ffffff;
    border-color: #0284c7;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
}
.confluence-box {
    background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%);
    border-left: 5px solid #0ea5e9;
    border-radius: 16px;
    padding: 1.5rem;
}
.bridge-card {
    border-radius: 16px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    transition: all 0.25s ease;
}
.bridge-card:hover {
    border-color: #38bdf8;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.06);
}
</style>

<!-- Hero Section -->
<div class="river-hero-bg py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-4 text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
            <i class="bi bi-water me-1"></i> Riverine Lifeline of Saran (सारण की नदियां)
        </span>
        <h1 class="fw-bold font-heading text-white display-5 mb-2">
            Rivers of Saran District
        </h1>
        <div class="lead text-warning fw-semibold mb-3">
            गंगा, घाघरा (सरयू), गंडक (नारायणी) एवं उपनदियों की पावन जलधाराएं
        </div>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 840px;">
            Encircled by three of India’s most sacred and mighty rivers — the <strong>Ganga on the South</strong>, the <strong>Ghaghra (Sarayu) on the South-West</strong>, and the <strong>Gandak (Narayani) on the East</strong> — Saran is a fertile alluvial delta shaped by sacred confluences, expansive diara lands, and rich river networks.
        </p>

        <!-- Quick Jump Navigation -->
        <div class="d-flex justify-content-center gap-2 flex-wrap mb-2">
            <a href="#major-rivers" class="quick-nav-pill"><i class="bi bi-tsunami text-primary"></i> 3 Major Boundary Rivers</a>
            <a href="#tributaries" class="quick-nav-pill"><i class="bi bi-droplet-half text-info"></i> Tributaries & Streams</a>
            <a href="#confluences" class="quick-nav-pill"><i class="bi bi-arrows-collapse text-success"></i> Sacred Sangams</a>
            <a href="#bridges" class="quick-nav-pill"><i class="bi bi-bounding-box-circles text-warning"></i> River Bridges</a>
            <a href="#diara-ecology" class="quick-nav-pill"><i class="bi bi-tree-fill text-teal"></i> Diara Ecosystem</a>
            <a href="#sources" class="quick-nav-pill"><i class="bi bi-shield-check text-success"></i> Official Sources</a>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="container py-5">

    <!-- KPI Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-primary">
                <div class="text-primary fs-1 mb-2"><i class="bi bi-water"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">3 Great Rivers</h3>
                <p class="text-muted small mb-0 fw-semibold">District Natural Borders</p>
                <div class="mt-2 text-primary small fs-7 fw-bold">Ganga • Sarayu • Gandak</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-success">
                <div class="text-success fs-1 mb-2"><i class="bi bi-arrows-collapse"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">2 Holy Sangams</h3>
                <p class="text-muted small mb-0 fw-semibold">Sacred Confluences</p>
                <div class="mt-2 text-success small fs-7 fw-bold">Sonpur & Doriganj</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-info">
                <div class="text-info fs-1 mb-2"><i class="bi bi-droplet-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">6+ Rivers & Streams</h3>
                <p class="text-muted small mb-0 fw-semibold">Internal Irrigation Drainage</p>
                <div class="mt-2 text-info small fs-7 fw-bold">Ghoghari, Mahi, Khaura, Sondhi</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-warning">
                <div class="text-warning fs-1 mb-2"><i class="bi bi-layers-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">Diara Plains</h3>
                <p class="text-muted small mb-0 fw-semibold">Fertile Alluvial Soil</p>
                <div class="mt-2 text-warning small fs-7 fw-bold">Agricultural Powerhouse</div>
            </div>
        </div>
    </div>

    <!-- Section 1: 3 Major Boundary Rivers of Saran -->
    <div id="major-rivers" class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge river-badge-blue px-3 py-1.5 rounded-pill small mb-1">Perennial Lifelines</span>
                <h2 class="fw-bold font-heading text-dark display-6 mb-0">The Three Great Boundary Rivers</h2>
            </div>
            <span class="text-muted small fw-semibold">Defining the Geography of Saran</span>
        </div>

        <div class="row g-4">
            <!-- 1. Ganga River -->
            <div class="col-lg-4">
                <div class="river-card h-100 shadow-sm d-flex flex-column">
                    <div class="p-4 bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">Southern Border</span>
                            <i class="bi bi-water fs-3 opacity-75"></i>
                        </div>
                        <h3 class="fw-bold font-heading text-white mb-1">Ganga River (गंगा)</h3>
                        <p class="text-white-50 small mb-0">The Holiest River of India</p>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <p class="text-secondary small mb-3" style="line-height: 1.7;">
                            The holy <strong>Ganga</strong> defines the entire southern boundary of Saran district. It flows west to east, separating Saran from <strong>Bhojpur (Arrah)</strong> and <strong>Patna</strong> districts before receiving the Ghaghra at Doriganj and Gandak at Sonpur.
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">District Boundary:</span>
                                <strong class="text-dark small">Southern Frontier (~65 km river border)</strong>
                            </div>
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">Key Blocks Touched:</span>
                                <strong class="text-dark small">Chapra, Doriganj, Dighwara, Sonepur</strong>
                            </div>
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">Major Ghats & Landmarks:</span>
                                <strong class="text-dark small">Aami Ghat, Semaria Ghat, Sonpur Ghat, Chirand</strong>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <span class="badge bg-light text-primary border small"><i class="bi bi-check-circle-fill me-1"></i> Perennial & Navigable</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Ghaghra / Sarayu River -->
            <div class="col-lg-4">
                <div class="river-card h-100 shadow-sm d-flex flex-column">
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">South-Western Border</span>
                            <i class="bi bi-tsunami fs-3 opacity-75"></i>
                        </div>
                        <h3 class="fw-bold font-heading text-white mb-1">Ghaghra / Sarayu (घाघरा/सरयू)</h3>
                        <p class="text-white-50 small mb-0">The River of Ayodhya & Chapra City</p>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <p class="text-secondary small mb-3" style="line-height: 1.7;">
                            Entering Saran at <strong>Manjhi</strong> on the Bihar-UP border, the mighty <strong>Ghaghra (Sarayu)</strong> flows along the south-western periphery. <strong>Chapra town</strong> is situated right on its northern bank before it confluences with the Ganga near Revelganj / Doriganj.
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">Origin:</span>
                                <strong class="text-dark small">Tibetan Plateau (Mapchachungo Glacier)</strong>
                            </div>
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">Key Blocks Touched:</span>
                                <strong class="text-dark small">Manjhi, Revelganj, Chapra Sadar</strong>
                            </div>
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">Historical Landmarks:</span>
                                <strong class="text-dark small">Gautam Rishi Ashram (Godna), Manjhi Fort, Sitab Diara</strong>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <span class="badge bg-light text-info border small"><i class="bi bi-check-circle-fill me-1"></i> Highest Water Discharge in Bihar</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Gandak / Narayani River -->
            <div class="col-lg-4">
                <div class="river-card h-100 shadow-sm d-flex flex-column">
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">Eastern Border</span>
                            <i class="bi bi-gem fs-3 opacity-75"></i>
                        </div>
                        <h3 class="fw-bold font-heading text-white mb-1">Gandak / Narayani (गंडक)</h3>
                        <p class="text-white-50 small mb-0">The River of Shaligram Stones</p>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <p class="text-secondary small mb-3" style="line-height: 1.7;">
                            Forming the entire eastern boundary of Saran, the <strong>Gandak (Badi Gandak / Narayani)</strong> separates Saran from <strong>Muzaffarpur</strong> and <strong>Vaishali</strong> districts. It feeds the vast Saran Canal irrigation network and meets Ganga at Sonpur.
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">Origin:</span>
                                <strong class="text-dark small">Nepal Himalayas (Triveni Sangam Glacier)</strong>
                            </div>
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">Key Blocks Touched:</span>
                                <strong class="text-dark small">Panaur, Taraiya, Maker, Parsa, Dariapur, Sonpur</strong>
                            </div>
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">Historical Landmarks:</span>
                                <strong class="text-dark small">Rewa Ghat, Baba Hariharnath Temple, Sonpur Mela</strong>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <span class="badge bg-light text-success border small"><i class="bi bi-check-circle-fill me-1"></i> Major Canal Irrigation Source</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Minor Rivers, Streams & Internal Drainage -->
    <div id="tributaries" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge river-badge-teal px-3 py-1.5 rounded-pill small">Internal Drainage Network</span>
            <span class="text-muted small fw-semibold">Canals, Streams & Tributaries</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            Tributaries & Internal Rivers of Saran District
        </h2>
        <p class="text-secondary lead fs-6 mb-4" style="line-height: 1.8;">
            Beyond the three grand boundary rivers, the interior plains of Saran are irrigated and drained by several historic streams, rivers, and chour overflow channels:
        </p>

        <div class="row g-4">
            <!-- Mahi River -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-droplet-half"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">Mahi River (माही नदी)</h4>
                            <span class="text-muted small">Central Saran Drainage</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        The <strong>Mahi River</strong> originates in the north-western parts of the Saran-Siwan border and flows across the central heartland through <strong>Baniyapur, Jalalpur, Garkha, Marhowrah</strong>, and <strong>Dariapur</strong>. It provides vital surface irrigation to hundreds of agricultural villages before joining the Gandak/Ganga watershed.
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-primary me-1"></i> Benefited Blocks: Baniyapur, Jalalpur, Garkha, Marhowrah, Dariapur.
                    </div>
                </div>
            </div>

            <!-- Khaura / Khatsa River -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-success text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-water"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">Khaura River (खौरा नदी)</h4>
                            <span class="text-muted small">North-Central Seasonal Stream</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        The <strong>Khaura River</strong> is a natural rainwater drainage river flowing through <strong>Ekma, Lahladpur, Baniyapur</strong>, and adjoining areas. During monsoon months, it carries runoff into natural depressions (Chours) and feeds local paddy fields.
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-success me-1"></i> Benefited Blocks: Ekma, Lahladpur, Baniyapur, Nagra.
                    </div>
                </div>
            </div>

            <!-- Jharahi / Dhanai River -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-info text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-compass"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">Jharahi & Dhanai Rivers (झरही/धनई)</h4>
                            <span class="text-muted small">Western Saran Floodplain</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        Flowing across the western borders of Saran near <strong>Manjhi</strong> and <strong>Ekma</strong> from Siwan, these streams interconnect with the Sarayu drainage basin and replenish low-lying wetlands and local ponds.
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-info me-1"></i> Benefited Blocks: Manjhi, Ekma, Revelganj.
                    </div>
                </div>
            </div>

            <!-- Ghoghari River -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary-subtle text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-water"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">Ghoghari River (घोघारी नदी)</h4>
                            <span class="text-muted small">North & Central Saran Agricultural Lifeline</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        The <strong>Ghoghari River</strong> enters northern Saran and flows across <strong>Mashrakh, Panapur, Taraiya</strong>, and <strong>Marhowrah (Madhaurah)</strong> before coursing towards Amnour and joining the central drainage system. It serves as a vital natural irrigation stream for thousands of farmers and drains monsoon runoff from the northern plains.
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-primary me-1"></i> Benefited Blocks: Mashrakh, Panapur, Taraiya, Marhowrah, Amnour.
                    </div>
                </div>
            </div>

            <!-- Sondhi / Gandaki River -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-warning text-dark rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-bezier2"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">Sondhi / Gandaki River (सोंधी / गंडकी)</h4>
                            <span class="text-muted small">Eastern Spill Channel of Gandak</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        The <strong>Sondhi River</strong> (also known as old Gandaki spill) meanders through <strong>Taraiya, Amnour, Parsa</strong>, and <strong>Dariapur</strong>. It connects multiple lake-like water reservoirs (Chours) and is crucial for aquaculture and rabi crop irrigation.
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-warning me-1"></i> Benefited Blocks: Taraiya, Amnour, Parsa, Maker.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Sacred Confluences (Sangams) -->
    <div id="confluences" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-1.5 rounded-pill small">Sacred Confluences</span>
            <span class="text-muted small fw-semibold">Puranic & Cultural Significance</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-4">
            The Holy River Confluences (Sangams) in Saran
        </h2>

        <div class="row g-4">
            <!-- 1. Harihar Kshetra Sonpur Sangam -->
            <div class="col-lg-6">
                <div class="confluence-box h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary text-white rounded-circle p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                            <i class="bi bi-sun-fill fs-5"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">Gandak-Ganga Sangam</h4>
                            <span class="text-muted small">Harihar Kshetra, Sonepur (Sonpur)</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.8;">
                        The confluence of the <strong>Gandak (Narayani) and Ganga</strong> at Sonpur is one of the most sacred pilgrimage sites in Hinduism. According to the <em>Srimad Bhagavatam</em>, this is the legendary ground of <strong>Gajendra Moksha</strong> (where Lord Vishnu saved the Elephant King Gajendra from the Crocodile Grah).
                    </p>
                    <ul class="text-secondary small mb-0" style="line-height: 1.7;">
                        <li><strong>Kartik Purnima Snan:</strong> Millions of pilgrims take holy dip at the confluence every year.</li>
                        <li><strong>Sonepur Mela:</strong> Asia’s largest historical cattle and cultural fair held annually on its banks.</li>
                        <li><strong>Hariharnath Temple:</strong> Ancient temple embodying the divine harmony of Vishnu (Hari) and Shiva (Har).</li>
                    </ul>
                </div>
            </div>

            <!-- 2. Sarayu-Ganga Sangam Doriganj/Semaria -->
            <div class="col-lg-6">
                <div class="confluence-box h-100" style="background: linear-gradient(135deg, #eff6ff 0%, #fef3c7 100%); border-left-color: #f59e0b;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-warning text-dark rounded-circle p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                            <i class="bi bi-tsunami fs-5"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">Sarayu-Ganga Sangam</h4>
                            <span class="text-muted small">Doriganj / Semaria Ghat / Revelganj</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.8;">
                        The sacred confluence where the mighty <strong>Ghaghra (Sarayu)</strong> empties its vast waters into the <strong>Ganga</strong> near Doriganj and Semaria Ghat. This confluence area is steeped in Vedic and Ramayana history.
                    </p>
                    <ul class="text-secondary small mb-0" style="line-height: 1.7;">
                        <li><strong>Sage Gautama Connection:</strong> The hermitage of Maharishi Gautama at Godna (Revelganj) overlooks this confluence basin.</li>
                        <li><strong>Chirand Archaeological Mound:</strong> Just upstream on the north bank, where ancient humans established riverside settlements in 2500 BCE.</li>
                        <li><strong>Arrah-Chapra Link:</strong> The modern Veer Kunwar Singh Bridge spans across this wide confluence zone.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Major River Bridges Connecting Saran -->
    <div id="bridges" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill small">Engineering & Connectivity</span>
            <span class="text-muted small fw-semibold">Bridges Across Saran's Rivers</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-4">
            Major River Bridges Connecting Saran District
        </h2>

        <div class="row g-4">
            <!-- 1. Arrah-Chapra Bridge -->
            <div class="col-md-6 col-lg-3">
                <div class="bridge-card p-4 h-100 shadow-sm">
                    <div class="text-primary fs-2 mb-2"><i class="bi bi-bezier"></i></div>
                    <h5 class="fw-bold text-dark font-heading mb-1">Veer Kunwar Singh Setu</h5>
                    <span class="badge bg-primary-subtle text-primary small mb-3">Arrah – Chapra Bridge</span>
                    <p class="text-secondary small mb-2" style="line-height: 1.6;">
                        Spans across the Ganga/Ghaghra at <strong>Doriganj</strong>, directly connecting Saran with <strong>Bhojpur (Arrah)</strong> and South Bihar via 4-lane NH.
                    </p>
                    <span class="text-muted small fw-semibold d-block mt-auto"><i class="bi bi-water text-primary me-1"></i> Over Ganga River</span>
                </div>
            </div>

            <!-- 2. JP Setu Sonpur-Digha -->
            <div class="col-md-6 col-lg-3">
                <div class="bridge-card p-4 h-100 shadow-sm">
                    <div class="text-success fs-2 mb-2"><i class="bi bi-train-front"></i></div>
                    <h5 class="fw-bold text-dark font-heading mb-1">JP Ganga Setu</h5>
                    <span class="badge bg-success-subtle text-success small mb-3">Digha – Sonpur Rail-Road</span>
                    <p class="text-secondary small mb-2" style="line-height: 1.6;">
                        A massive 4.55 km rail-cum-road bridge over Ganga connecting <strong>Sonpur (Saran)</strong> directly with <strong>Patna (Digha)</strong>.
                    </p>
                    <span class="text-muted small fw-semibold d-block mt-auto"><i class="bi bi-water text-success me-1"></i> Over Ganga River</span>
                </div>
            </div>

            <!-- 3. Manjhi Bridge -->
            <div class="col-md-6 col-lg-3">
                <div class="bridge-card p-4 h-100 shadow-sm">
                    <div class="text-warning fs-2 mb-2"><i class="bi bi-signpost-2-fill"></i></div>
                    <h5 class="fw-bold text-dark font-heading mb-1">Manjhi Sarayu Setu</h5>
                    <span class="badge bg-warning-subtle text-warning-emphasis small mb-3">Bihar – UP Border Link</span>
                    <p class="text-secondary small mb-2" style="line-height: 1.6;">
                        Spans the Ghaghra (Sarayu) river at <strong>Manjhi</strong>, connecting Saran (Bihar) with <strong>Ballia (Uttar Pradesh)</strong> on NH-31.
                    </p>
                    <span class="text-muted small fw-semibold d-block mt-auto"><i class="bi bi-water text-warning me-1"></i> Over Sarayu River</span>
                </div>
            </div>

            <!-- 4. Rewa Ghat & Sonpur-Hajipur Bridges -->
            <div class="col-md-6 col-lg-3">
                <div class="bridge-card p-4 h-100 shadow-sm">
                    <div class="text-info fs-2 mb-2"><i class="bi bi-link-45deg"></i></div>
                    <h5 class="fw-bold text-dark font-heading mb-1">Gandak Bridges</h5>
                    <span class="badge bg-info-subtle text-info small mb-3">Sonpur – Hajipur & Rewa</span>
                    <p class="text-secondary small mb-2" style="line-height: 1.6;">
                        Bridges over the Gandak river connecting <strong>Sonpur with Hajipur</strong> (Vaishali) and <strong>Rewa Ghat Bridge</strong> connecting Saran with Muzaffarpur.
                    </p>
                    <span class="text-muted small fw-semibold d-block mt-auto"><i class="bi bi-water text-info me-1"></i> Over Gandak River</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 5: The Diara Ecosystem & Agriculture -->
    <div id="diara-ecology" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success text-white fw-bold px-3 py-1.5 rounded-pill small">Ecological Heritage</span>
                    <span class="text-muted small fw-semibold">Soil, Agriculture & Life</span>
                </div>
                <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                    The Diara Ecosystem & Agricultural Bounty
                </h2>
                <p class="text-secondary" style="line-height: 1.8;">
                    Because Saran is bounded by three sediment-rich rivers, a significant portion of its southern and western riverfront forms the famous <strong>Diara (दियारा)</strong> floodplains.
                </p>
                <ul class="text-secondary small mb-3" style="line-height: 1.8;">
                    <li><strong>Annual Silt Renewal:</strong> Monsoon inundation deposits nutrient-dense fresh silt (Alluvium) every year, eliminating the need for synthetic chemical fertilizers.</li>
                    <li><strong>Bumper Crops:</strong> The Diara belt is renowned for prolific production of <strong>Wheat, Maize, Mustards, Watermelons (तरबूज), Muskmelons (खरबूजा), Pointed Gourd (परवल), and vegetables</strong>.</li>
                    <li><strong>Famous Diaras:</strong> Sitab Diara (birthplace of Loknayak JP), Manjhi Diara, Revelganj Diara, Chapra Diara, Doriganj Diara, and Sonpur Diara.</li>
                </ul>

                <div class="p-3 bg-light rounded-3 border">
                    <strong class="text-dark small d-block mb-1"><i class="bi bi-shield-exclamation text-warning me-1"></i> Monsoon Flood Management:</strong>
                    <span class="text-muted small">
                        While rivers bring immense fertility, monsoon swells require robust flood management embankments (Tathbandh) maintained by the Water Resources Department, Government of Bihar.
                    </span>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-gradient-primary text-white p-4 rounded-4 shadow-sm text-center">
                    <div class="text-warning fs-1 mb-2"><i class="bi bi-flower2"></i></div>
                    <h4 class="fw-bold font-heading text-white mb-1">The Green Granary of Saran</h4>
                    <p class="text-white-50 small mb-3">River-Fed Agro Paradise</p>
                    <div class="border-top border-white border-opacity-25 pt-3 text-start small">
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>Soil Type:</strong> Rich Riverine Sandy Loam</div>
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>Key Crops:</strong> Maize, Vegetables, Melons, Wheat</div>
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>Canal Irrigation:</strong> Gandak Main & Saran Canals</div>
                        <div class="mb-0"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>Ecosystem:</strong> Chours, Oxbow Lakes & Wetlands</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 6: Official Sources & Hydrology References -->
    <div id="sources" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white border-top border-4 border-info">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-info-subtle text-info fw-bold px-3 py-1.5 rounded-pill small mb-1">Authenticity & Hydrology Data</span>
                <h3 class="fw-bold font-heading text-dark display-6 mb-0">Official Sources & Hydrology References</h3>
            </div>
            <a href="sources" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-bold">
                <i class="bi bi-shield-check me-1"></i> View All Reference Portals <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <p class="text-secondary small mb-4" style="line-height: 1.7;">
            The river basin data, canal irrigation systems, river gauging metrics, and floodplain geography presented above are referenced from authenticated government water resources and environmental agencies:
        </p>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-water text-info fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">Water Resources Department, Bihar (WRD)</strong>
                            <span class="text-muted small">Official data on Saran district river embankments, Gandak Project canal networks, and flood management. (<a href="https://wrd.bihar.gov.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">wrd.bihar.gov.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-droplet-half text-success fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">National Mission for Clean Ganga (NMCG)</strong>
                            <span class="text-muted small">Water quality monitoring, Namami Gange ghat development projects across Chapra, Doriganj, Dighwara, and Sonpur. (<a href="https://nmcg.nic.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">nmcg.nic.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-speedometer2 text-primary fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">Central Water Commission (CWC), Ministry of Jal Shakti</strong>
                            <span class="text-muted small">Hydrological observation stations, water discharge rates, and flood forecast data at Rewa Ghat, Chapra, and Gandak-Ganga confluence. (<a href="https://cwc.gov.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">cwc.gov.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-tsunami text-warning fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">Inland Waterways Authority of India (IWAI)</strong>
                            <span class="text-muted small">National Waterway 1 (Ganga) and NW-37 (Gandak) navigation terminals, river transport routes, and fairway development. (<a href="https://iwai.nic.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">iwai.nic.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Banner -->
    <div class="card border-0 shadow rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0c2d48 0%, #145da0 100%);">
        <div class="card-body p-4 p-md-5 text-white text-center">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
                <i class="bi bi-compass me-1"></i> Explore Saran District Digitally
            </span>
            <h3 class="fw-bold font-heading text-white display-6 mb-3">
                Connecting Every Block, River Ghat & Panchayat of Saran
            </h3>
            <p class="text-white-50 lead fs-6 mx-auto mb-4" style="max-width: 700px;">
                Explore all 20 blocks, emergency services, historical heritage sites, and local businesses across Saran District on Saran Index.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="blocks" class="btn btn-warning text-dark fw-bold px-4 py-2.5 rounded-pill shadow">
                    <i class="bi bi-geo-alt-fill me-1"></i> Explore All 20 Blocks
                </a>
                <a href="nahar" class="btn btn-light text-success fw-bold px-4 py-2.5 rounded-pill shadow">
                    <i class="bi bi-water me-1"></i> Canals & Irrigation
                </a>
                <a href="history" class="btn btn-outline-light fw-bold px-4 py-2.5 rounded-pill">
                    <i class="bi bi-hourglass-split me-1"></i> History & Heritage
                </a>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

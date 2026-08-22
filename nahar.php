<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "Canals & Irrigation Network of Saran District (Gandak Canal Project) | Saran Index";
$meta_description = "Complete guide to the Canal & Irrigation System of Saran District (Chapra, Bihar). Explore the Gandak Project Saran Main Canal, Marhowrah Branch Canal, Chapra Branch Canal, distributaries, irrigation command areas, and crop coverage.";
$meta_keywords = "Nahar in Saran, Canals in Chapra, Gandak Project Saran, Saran Main Canal, Marhowrah Branch Canal, Chapra Canal, Saran Irrigation, WRD Bihar Canal Chapra, Har Khet Ko Pani Saran";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom Styles for Nahar / Canal Page -->
<style>
.nahar-hero-bg {
    background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%);
    color: #ffffff;
}
.canal-card {
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    background: #ffffff;
    overflow: hidden;
}
.canal-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 35px -10px rgba(5, 150, 105, 0.2) !important;
    border-color: #6ee7b7;
}
.canal-badge-green {
    background: #d1fae5;
    color: #065f46;
    font-weight: 700;
}
.canal-badge-blue {
    background: #e0f2fe;
    color: #0369a1;
    font-weight: 700;
}
.canal-stat-box {
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
    background: #059669;
    color: #ffffff;
    border-color: #059669;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
}
.canal-feature-box {
    background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
    border-left: 5px solid #10b981;
    border-radius: 16px;
    padding: 1.5rem;
}
</style>

<!-- Hero Section -->
<div class="nahar-hero-bg py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-4 text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
            <i class="bi bi-water me-1"></i> Agricultural Lifeline of Saran (सारण की नहरें)
        </span>
        <h1 class="fw-bold font-heading text-white display-5 mb-2">
            Canals & Irrigation Network of Saran District
        </h1>
        <div class="lead text-warning fw-semibold mb-3">
            गंडक परियोजना, सारण मुख्य नहर, मढ़ौरा व छपरा शाखा नहरें एवं सिंचाई नेटवर्क
        </div>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 840px;">
            Supplied by the iconic <strong>Gandak Project (गंडक परियोजना)</strong> originating from Valmikinagar, Saran District's extensive canal irrigation network spans hundreds of kilometers, nourishing fertile agricultural fields across all 20 blocks with reliable gravity flow water.
        </p>

        <!-- Quick Jump Navigation -->
        <div class="d-flex justify-content-center gap-2 flex-wrap mb-2">
            <a href="#gandak-project" class="quick-nav-pill"><i class="bi bi-diagram-3-fill text-success"></i> Gandak Project Overview</a>
            <a href="#major-canals" class="quick-nav-pill"><i class="bi bi-water text-primary"></i> Main & Branch Canals</a>
            <a href="#distributaries" class="quick-nav-pill"><i class="bi bi-bezier2 text-info"></i> Distributaries & Minors</a>
            <a href="#crop-impact" class="quick-nav-pill"><i class="bi bi-flower2 text-warning"></i> Crop Impact & Seasons</a>
            <a href="#officers" class="quick-nav-pill"><i class="bi bi-telephone-fill text-danger"></i> Officer Contacts</a>
            <a href="#sources" class="quick-nav-pill"><i class="bi bi-shield-check text-success"></i> Official Sources</a>
        </div>
    </div>
</div>

<!-- Main Container -->
<div class="container py-5">

    <!-- KPI Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-success">
                <div class="text-success fs-1 mb-2"><i class="bi bi-water"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">Gandak Project</h3>
                <p class="text-muted small mb-0 fw-semibold">Primary Water Source</p>
                <div class="mt-2 text-success small fs-7 fw-bold">Valmikinagar Barrage Feeder</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-primary">
                <div class="text-primary fs-1 mb-2"><i class="bi bi-diagram-3"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">350+ km</h3>
                <p class="text-muted small mb-0 fw-semibold">Canal Network Length</p>
                <div class="mt-2 text-primary small fs-7 fw-bold">Main, Branch & Minors</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-warning">
                <div class="text-warning fs-1 mb-2"><i class="bi bi-geo-alt-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">20 Blocks</h3>
                <p class="text-muted small mb-0 fw-semibold">Irrigation Command</p>
                <div class="mt-2 text-warning small fs-7 fw-bold">Kharif & Rabi Coverage</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-info">
                <div class="text-info fs-1 mb-2"><i class="bi bi-droplet-half"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">Gravity Flow</h3>
                <p class="text-muted small mb-0 fw-semibold">Eco-Friendly Irrigation</p>
                <div class="mt-2 text-info small fs-7 fw-bold">Zero Power Canal Supply</div>
            </div>
        </div>
    </div>

    <!-- Section 1: The Gandak Project Overview -->
    <div id="gandak-project" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge canal-badge-green px-3 py-1.5 rounded-pill small">Mega Irrigation Project</span>
                    <span class="text-muted small fw-semibold">Valmikinagar to Saran Plains</span>
                </div>
                <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                    The Gandak Canal Project (गंडक परियोजना)
                </h2>
                <p class="text-secondary" style="line-height: 1.8;">
                    The <strong>Gandak Irrigation Project</strong> is one of the largest river valley projects in Northern India, originating from the <strong>Gandak Barrage at Valmikinagar</strong> (Bhainsalotan) on the India-Nepal border. 
                </p>
                <p class="text-secondary" style="line-height: 1.8;">
                    The barrage diverts waters into two massive canal networks: the <em>Tirhut Main Canal</em> on the eastern bank and the <strong>Saran Main Canal (सारण मुख्य नहर)</strong> on the western bank. The Saran Canal network carries Himalayan glacier meltwaters through Gopalganj and Siwan, culminating in the expansive plains of Saran district.
                </p>

                <div class="canal-feature-box">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-4 mt-n1 flex-shrink-0"></i>
                        <div class="small text-dark fw-medium">
                            <em>"Before the commissioning of the Gandak Canal network, Saran was heavily vulnerable to erratic monsoons. The canal system transformed Saran into one of Bihar's most productive granaries for paddy, wheat, maize, and sugarcane."</em>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="p-4 rounded-4 bg-light border">
                    <h5 class="fw-bold text-dark font-heading mb-3"><i class="bi bi-gear-wide-connected text-success me-2"></i>Technical Canal Highlights</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-3">
                            <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                <i class="bi bi-water"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">Headworks</strong>
                                <span class="text-muted small">Valmikinagar Barrage across Gandak River (Capacity: 8.5 Lakh Cusecs).</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                <i class="bi bi-bezier"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">Western Command System</strong>
                                <span class="text-muted small">Saran Main Canal feeds Gopalganj, Siwan, and Saran districts.</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                <i class="bi bi-building-gear"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">Administrative Management</strong>
                                <span class="text-muted small">Water Resources Department (WRD), Govt. of Bihar — Saran Canal Circle, Chapra.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Major Canals & Branches in Saran -->
    <div id="major-canals" class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge canal-badge-blue px-3 py-1.5 rounded-pill small mb-1">Primary Channels</span>
                <h2 class="fw-bold font-heading text-dark display-6 mb-0">Major Canals & Branches in Saran</h2>
            </div>
            <span class="text-muted small fw-semibold">Arterial Irrigation Corridors</span>
        </div>

        <div class="row g-4">
            <!-- 1. Saran Main Canal -->
            <div class="col-lg-4">
                <div class="canal-card h-100 shadow-sm d-flex flex-column">
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #065f46 0%, #047857 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">Main Trunk Line</span>
                            <i class="bi bi-water fs-3 opacity-75"></i>
                        </div>
                        <h3 class="fw-bold font-heading text-white mb-1">Saran Main Canal</h3>
                        <p class="text-white-50 small mb-0">सारण मुख्य नहर</p>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <p class="text-secondary small mb-3" style="line-height: 1.7;">
                            The main arterial canal that enters the northern borders of Saran district from Siwan. It carries the bulk flow of the Gandak river and splits into major branch canals supplying water to central, southern, and eastern blocks.
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">Source:</span>
                                <strong class="text-dark small">Gandak Barrage West Bank</strong>
                            </div>
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">Key Command Areas:</span>
                                <strong class="text-dark small">Mashrakh, Isuapur, Baniyapur, Marhowrah</strong>
                            </div>
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">Primary Purpose:</span>
                                <strong class="text-dark small">Trunk supply for branch canals & distributaries</strong>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <span class="badge bg-light text-success border small"><i class="bi bi-check-circle-fill me-1"></i> Major Trunk Waterway</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Marhowrah Branch Canal -->
            <div class="col-lg-4">
                <div class="canal-card h-100 shadow-sm d-flex flex-column">
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">Central Branch</span>
                            <i class="bi bi-diagram-2 fs-3 opacity-75"></i>
                        </div>
                        <h3 class="fw-bold font-heading text-white mb-1">Marhowrah Branch Canal</h3>
                        <p class="text-white-50 small mb-0">मढ़ौरा शाखा नहर</p>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <p class="text-secondary small mb-3" style="line-height: 1.7;">
                            Taking off from the main canal near Mashrakh, the <strong>Marhowrah Branch Canal</strong> traverses through <strong>Isuapur, Marhowrah, Amnour, and Dariapur</strong>, serving as the agricultural backbone for sugarcane, wheat, and paddy cultivation in industrial and rural Marhowrah.
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">Alignment:</span>
                                <strong class="text-dark small">North-to-South Central Corridor</strong>
                            </div>
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">Key Blocks Touched:</span>
                                <strong class="text-dark small">Mashrakh, Isuapur, Marhowrah, Dariapur</strong>
                            </div>
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">Benefited Crops:</span>
                                <strong class="text-dark small">Sugarcane, Paddy, Wheat, Vegetables</strong>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <span class="badge bg-light text-primary border small"><i class="bi bi-check-circle-fill me-1"></i> Perennial Command Area</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Chapra Branch Canal -->
            <div class="col-lg-4">
                <div class="canal-card h-100 shadow-sm d-flex flex-column">
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #b45309 0%, #d97706 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-light text-dark fw-bold rounded-pill px-3 py-1">Southern Branch</span>
                            <i class="bi bi-geo-alt fs-3 opacity-75"></i>
                        </div>
                        <h3 class="fw-bold font-heading text-white mb-1">Chapra Branch Canal</h3>
                        <p class="text-white-50 small mb-0">छपरा शाखा नहर</p>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <p class="text-secondary small mb-3" style="line-height: 1.7;">
                            Branching towards the south, the <strong>Chapra Branch Canal</strong> irrigates the agricultural belts of <strong>Jalalpur, Baniyapur, Nagra, and Chapra Sadar</strong>. It feeds numerous small distributaries that keep groundwater tables replenished near the district headquarters.
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">Alignment:</span>
                                <strong class="text-dark small">Central-to-Southward Corridor</strong>
                            </div>
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">Key Blocks Touched:</span>
                                <strong class="text-dark small">Jalalpur, Baniyapur, Nagra, Chapra Sadar</strong>
                            </div>
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">Benefited Crops:</span>
                                <strong class="text-dark small">Paddy, Maize, Mustard, Pulses</strong>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <span class="badge bg-light text-warning border small"><i class="bi bi-check-circle-fill me-1"></i> High Command Density</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Distributaries, Minors & Block Coverage -->
    <div id="distributaries" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge canal-badge-green px-3 py-1.5 rounded-pill small">Sub-Canal Networks</span>
            <span class="text-muted small fw-semibold">Distributaries (रजवाहा) & Minors (माइनर)</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            Distributaries & Canal Network Across Saran Blocks
        </h2>
        <p class="text-secondary lead fs-6 mb-4" style="line-height: 1.8;">
            From the main and branch canals, an intricate sub-network of distributaries (रजवाहा), sub-distributaries, and minors delivers water directly to village field channels (कूहल):
        </p>

        <div class="row g-4">
            <!-- Garkha Distributary -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-success text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-droplet-fill"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">Garkha Distributary (गरखा वितरणी)</h4>
                            <span class="text-muted small">Central-South Saran Canal</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        Supplies water to the vast paddy cultivation belts of <strong>Garkha</strong> and border villages of <strong>Nagra & Marhowrah</strong>. It connects with local drainage channels to maintain soil moisture during hot summer dry spells.
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-success me-1"></i> Command Areas: Garkha, Nagra, Mehiyan, Jalalpur border.
                    </div>
                </div>
            </div>

            <!-- Taraiya & Amnour Minors -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-bezier2"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">Taraiya & Amnour Canal Links</h4>
                            <span class="text-muted small">North-Eastern Command Network</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        Feeds the agricultural lowlands of <strong>Taraiya, Panapur, Isuapur, and Amnour</strong>. Operates in coordination with the Ghoghari and Sondhi river drainage basins to prevent waterlogging and maximize irrigation.
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-primary me-1"></i> Command Areas: Taraiya, Panapur, Amnour, Isuapur.
                    </div>
                </div>
            </div>

            <!-- Parsa & Dariapur Sub-Canals -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-warning text-dark rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-water"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">Parsa & Dariapur Minors</h4>
                            <span class="text-muted small">Eastern & South-Eastern Channels</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        Directs irrigation flows across <strong>Parsa, Maker, Dariapur</strong>, and downstream tracts towards <strong>Dighwara & Sonpur</strong>, filling village water tanks (Pokharas) and supporting intense vegetable farming.
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-warning me-1"></i> Command Areas: Parsa, Dariapur, Maker, Dighwara.
                    </div>
                </div>
            </div>

            <!-- Ekma & Western Saran Minors -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-info text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-compass"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">Ekma & Western Saran Channels</h4>
                            <span class="text-muted small">Western Border Irrigation Network</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        Originating from the Siwan-Saran border channels, these minors water <strong>Ekma, Lahladpur, Manjhi</strong>, and <strong>Revelganj</strong> highlands, safeguarding high-yielding wheat, maize, and pulse crops.
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-info me-1"></i> Command Areas: Ekma, Lahladpur, Manjhi, Revelganj.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Agricultural Impact & Seasonal Calendar -->
    <div id="crop-impact" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success text-white fw-bold px-3 py-1.5 rounded-pill small">Agricultural Impact</span>
                    <span class="text-muted small fw-semibold">Kharif, Rabi & Zaid Cycles</span>
                </div>
                <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                    Seasonal Water Supply & Crop Impact in Saran
                </h2>
                <p class="text-secondary" style="line-height: 1.8;">
                    Canal water release in Saran is regulated seasonally by the Water Resources Department to align with crop sowing and flowering calendars:
                </p>

                <div class="d-flex flex-column gap-3 mb-3">
                    <div class="border-start border-3 border-success ps-3">
                        <strong class="text-dark small d-block">Kharif Season (खरीफ फसल – July to October):</strong>
                        <span class="text-muted small">Full canal discharge provided for <strong>Paddy (धान)</strong> transplantation and vegetative growth across all 20 blocks.</span>
                    </div>
                    <div class="border-start border-3 border-warning ps-3">
                        <strong class="text-dark small d-block">Rabi Season (रबी फसल – November to March):</strong>
                        <span class="text-muted small">Regulated rotational canal supply for <strong>Wheat (गेहूं), Maize (मक्का), Mustard (सरसों), and Pulses (दलहन)</strong>.</span>
                    </div>
                    <div class="border-start border-3 border-primary ps-3">
                        <strong class="text-dark small d-block">Zaid / Summer Season (जायद – April to June):</strong>
                        <span class="text-muted small">Supply to Chours and lowlands supporting summer vegetables, watermelons, and fodder crops.</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-gradient-primary text-white p-4 rounded-4 shadow-sm text-center">
                    <div class="text-warning fs-1 mb-2"><i class="bi bi-flower1"></i></div>
                    <h4 class="fw-bold font-heading text-white mb-1">Har Khet Ko Pani</h4>
                    <p class="text-white-50 small mb-3">हर खेत को पानी (Bihar Govt Initiative)</p>
                    <div class="border-top border-white border-opacity-25 pt-3 text-start small">
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>Irrigation Efficiency:</strong> Concrete Canal Lining (पक्कीकरण)</div>
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>Tail-end Coverage:</strong> Restoring silted minors & distributaries</div>
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>Farmer Benefits:</strong> Reduced diesel pumping costs</div>
                        <div class="mb-0"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>Groundwater Recharge:</strong> Year-round aquifer stability</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 5: Irrigation Officers Contact Directory (Saran District) -->
    <div id="officers" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white border-top border-4 border-danger">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-1.5 rounded-pill small mb-1">
                    <i class="bi bi-telephone-fill me-1"></i> Official Directory
                </span>
                <h2 class="fw-bold font-heading text-dark display-6 mb-0">Saran District Irrigation & Canal Officers Contact</h2>
            </div>
            <a href="https://irrigation.befiqr.in/" target="_blank" rel="noopener" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1.5 fw-bold">
                <i class="bi bi-box-arrow-up-right me-1"></i> WRD BeFIQR Portal <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <p class="text-secondary small mb-4" style="line-height: 1.7;">
            Contact details of the Water Resources Department (WRD), Govt. of Bihar irrigation circle and division offices overseeing the canal network in <strong>Saran District</strong>:
        </p>

        <div class="row g-4 mb-4">
            <!-- 1. S.E., Saran Canal Circle, Chapra -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-white border h-100 d-flex flex-column shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="bg-danger-subtle text-danger rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-building-gear fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark font-heading mb-0 fs-6">Superintending Engineer (S.E.)</h5>
                                <span class="text-muted small">Saran Canal Circle, Chapra</span>
                            </div>
                        </div>
                        <span class="badge bg-danger-subtle text-danger fw-bold rounded-pill px-2.5 py-1 small">Circle HQ</span>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <!-- CUG Call Bar -->
                        <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger rounded-circle p-1.5 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-phone text-white" style="font-size: 0.75rem;"></i>
                                </span>
                                <div>
                                    <span class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;">CUG Mobile</span>
                                    <span class="fw-bold text-dark font-monospace small">+91 74638 89124</span>
                                </div>
                            </div>
                            <a href="tel:7463889124" class="btn btn-danger btn-sm rounded-pill px-3 py-1 fw-bold fs-7 shadow-sm">
                                <i class="bi bi-telephone-fill me-1"></i> Call
                            </a>
                        </div>

                        <!-- Secondary Contacts Row -->
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="tel:06152232492" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border text-decoration-none text-dark hover-shadow transition-all h-100">
                                    <i class="bi bi-telephone text-primary fs-6"></i>
                                    <div class="text-truncate">
                                        <span class="text-muted d-block" style="font-size: 0.68rem; line-height: 1;">Office Phone</span>
                                        <strong class="font-monospace small text-truncate d-block">06152-232492</strong>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="tel:7903124419" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border text-decoration-none text-dark hover-shadow transition-all h-100">
                                    <i class="bi bi-phone text-secondary fs-6"></i>
                                    <div class="text-truncate">
                                        <span class="text-muted d-block" style="font-size: 0.68rem; line-height: 1;">Alt. Mobile</span>
                                        <strong class="font-monospace small text-truncate d-block">7903124419</strong>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Email Bar -->
                        <a href="mailto:sesccchapra@gmail.com" class="d-flex align-items-center justify-content-between p-2 px-2.5 rounded-3 bg-light border text-decoration-none text-secondary hover-shadow transition-all">
                            <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                <i class="bi bi-envelope text-warning fs-6 flex-shrink-0"></i>
                                <span class="small font-monospace text-dark text-truncate">sesccchapra@gmail.com</span>
                            </div>
                            <span class="badge bg-white text-primary border small flex-shrink-0">Email <i class="bi bi-arrow-right-short"></i></span>
                        </a>
                    </div>

                    <div class="mt-auto pt-2 border-top small text-muted">
                        <i class="bi bi-geo-alt text-danger me-1"></i> Jurisdiction: Entire Saran Canal Circle (Chapra HQ)
                    </div>
                </div>
            </div>

            <!-- 2. E.E., Saran Canal Division, Chapra -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-white border h-100 d-flex flex-column shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="bg-primary-subtle text-primary rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-water fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark font-heading mb-0 fs-6">Executive Engineer (E.E.)</h5>
                                <span class="text-muted small">Saran Canal Division, Chapra</span>
                            </div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary fw-bold rounded-pill px-2.5 py-1 small">Division</span>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <!-- CUG Call Bar -->
                        <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-25">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary rounded-circle p-1.5 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-phone text-white" style="font-size: 0.75rem;"></i>
                                </span>
                                <div>
                                    <span class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;">CUG Mobile</span>
                                    <span class="fw-bold text-dark font-monospace small">+91 74638 89604</span>
                                </div>
                            </div>
                            <a href="tel:7463889604" class="btn btn-primary btn-sm rounded-pill px-3 py-1 fw-bold fs-7 shadow-sm">
                                <i class="bi bi-telephone-fill me-1"></i> Call
                            </a>
                        </div>

                        <!-- Secondary Contacts Row -->
                        <div class="row g-2">
                            <div class="col-12">
                                <a href="tel:7277073652" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border text-decoration-none text-dark hover-shadow transition-all">
                                    <i class="bi bi-phone text-secondary fs-6"></i>
                                    <div class="text-truncate">
                                        <span class="text-muted d-block" style="font-size: 0.68rem; line-height: 1;">Alt. Mobile</span>
                                        <strong class="font-monospace small text-truncate d-block">+91 72770 73652</strong>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Email Bar -->
                        <a href="mailto:eescdchapra@gmail.com" class="d-flex align-items-center justify-content-between p-2 px-2.5 rounded-3 bg-light border text-decoration-none text-secondary hover-shadow transition-all">
                            <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                <i class="bi bi-envelope text-warning fs-6 flex-shrink-0"></i>
                                <span class="small font-monospace text-dark text-truncate">eescdchapra@gmail.com</span>
                            </div>
                            <span class="badge bg-white text-primary border small flex-shrink-0">Email <i class="bi bi-arrow-right-short"></i></span>
                        </a>
                    </div>

                    <div class="mt-auto pt-2 border-top small text-muted">
                        <i class="bi bi-geo-alt text-primary me-1"></i> Jurisdiction: Chapra Branch Canal & Sadar Command Area
                    </div>
                </div>
            </div>

            <!-- 3. E.E., Saran Canal Division, Marhaura -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-white border h-100 d-flex flex-column shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="bg-success-subtle text-success rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-diagram-2 fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark font-heading mb-0 fs-6">Executive Engineer (E.E.)</h5>
                                <span class="text-muted small">Saran Canal Division, Marhaura</span>
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-2.5 py-1 small">Division</span>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <!-- CUG Call Bar -->
                        <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success rounded-circle p-1.5 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-phone text-white" style="font-size: 0.75rem;"></i>
                                </span>
                                <div>
                                    <span class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;">CUG Mobile</span>
                                    <span class="fw-bold text-dark font-monospace small">+91 74638 89600</span>
                                </div>
                            </div>
                            <a href="tel:7463889600" class="btn btn-success btn-sm rounded-pill px-3 py-1 fw-bold fs-7 shadow-sm">
                                <i class="bi bi-telephone-fill me-1"></i> Call
                            </a>
                        </div>

                        <!-- Secondary Contacts Row -->
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="tel:06159231630" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border text-decoration-none text-dark hover-shadow transition-all h-100">
                                    <i class="bi bi-telephone text-primary fs-6"></i>
                                    <div class="text-truncate">
                                        <span class="text-muted d-block" style="font-size: 0.68rem; line-height: 1;">Office Phone</span>
                                        <strong class="font-monospace small text-truncate d-block">06159-231630</strong>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="tel:9931209711" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border text-decoration-none text-dark hover-shadow transition-all h-100">
                                    <i class="bi bi-phone text-secondary fs-6"></i>
                                    <div class="text-truncate">
                                        <span class="text-muted d-block" style="font-size: 0.68rem; line-height: 1;">Alt. Mobile</span>
                                        <strong class="font-monospace small text-truncate d-block">9931209711</strong>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Email Bar -->
                        <a href="mailto:eescd.mrh@gmail.com" class="d-flex align-items-center justify-content-between p-2 px-2.5 rounded-3 bg-light border text-decoration-none text-secondary hover-shadow transition-all">
                            <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                <i class="bi bi-envelope text-warning fs-6 flex-shrink-0"></i>
                                <span class="small font-monospace text-dark text-truncate">eescd.mrh@gmail.com</span>
                            </div>
                            <span class="badge bg-white text-primary border small flex-shrink-0">Email <i class="bi bi-arrow-right-short"></i></span>
                        </a>
                    </div>

                    <div class="mt-auto pt-2 border-top small text-muted">
                        <i class="bi bi-geo-alt text-success me-1"></i> Jurisdiction: Marhowrah Branch Canal, Amnour, Isuapur, Dariapur
                    </div>
                </div>
            </div>

            <!-- 4. E.E., Saran Canal Division, Ekma -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-white border h-100 d-flex flex-column shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="bg-warning-subtle text-warning-emphasis rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-compass fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark font-heading mb-0 fs-6">Executive Engineer (E.E.)</h5>
                                <span class="text-muted small">Saran Canal Division, Ekma</span>
                            </div>
                        </div>
                        <span class="badge bg-warning-subtle text-warning-emphasis fw-bold rounded-pill px-2.5 py-1 small">Division</span>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <!-- CUG Call Bar -->
                        <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-warning text-dark rounded-circle p-1.5 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-phone" style="font-size: 0.75rem;"></i>
                                </span>
                                <div>
                                    <span class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;">CUG Mobile</span>
                                    <span class="fw-bold text-dark font-monospace small">+91 74638 89602</span>
                                </div>
                            </div>
                            <a href="tel:7463889602" class="btn btn-warning text-dark btn-sm rounded-pill px-3 py-1 fw-bold fs-7 shadow-sm">
                                <i class="bi bi-telephone-fill me-1"></i> Call
                            </a>
                        </div>

                        <!-- Secondary Contacts Row -->
                        <div class="row g-2">
                            <div class="col-12">
                                <a href="tel:06155231546" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border text-decoration-none text-dark hover-shadow transition-all">
                                    <i class="bi bi-telephone text-primary fs-6"></i>
                                    <div class="text-truncate">
                                        <span class="text-muted d-block" style="font-size: 0.68rem; line-height: 1;">Office Phone (Landline)</span>
                                        <strong class="font-monospace small text-truncate d-block">06155-231546</strong>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Email Bar -->
                        <a href="mailto:scdivekma@gmail.com" class="d-flex align-items-center justify-content-between p-2 px-2.5 rounded-3 bg-light border text-decoration-none text-secondary hover-shadow transition-all">
                            <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                <i class="bi bi-envelope text-warning fs-6 flex-shrink-0"></i>
                                <span class="small font-monospace text-dark text-truncate">scdivekma@gmail.com</span>
                            </div>
                            <span class="badge bg-white text-primary border small flex-shrink-0">Email <i class="bi bi-arrow-right-short"></i></span>
                        </a>
                    </div>

                    <div class="mt-auto pt-2 border-top small text-muted">
                        <i class="bi bi-geo-alt text-warning me-1"></i> Jurisdiction: Ekma, Manjhi, Lahladpur & Western Saran Command
                    </div>
                </div>
            </div>
        </div>

        <!-- 24x7 WRD Helpline Banner -->
        <div class="p-3.5 rounded-4 bg-danger-subtle border border-danger-subtle d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-danger text-white rounded-circle p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="bi bi-headset fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-danger mb-0">WRD 24x7 Flood & Irrigation Control Room Helpline (BeFIQR)</h6>
                    <span class="text-secondary small">Toll-Free Helpline for canal breaches, siltation, or irrigation supply issues</span>
                </div>
            </div>
            <a href="tel:18003456145" class="btn btn-danger btn-sm rounded-pill px-3 py-2 fw-bold text-nowrap align-self-start align-self-sm-center shadow-sm">
                <i class="bi bi-telephone-outbound me-1"></i> 1800 3456 145
            </a>
        </div>
    </div>

    <!-- Section 6: Official Sources & Hydrology References -->
    <div id="sources" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white border-top border-4 border-success">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-success-subtle text-success fw-bold px-3 py-1.5 rounded-pill small mb-1">Authenticity & Citations</span>
                <h3 class="fw-bold font-heading text-dark display-6 mb-0">Official Sources & Irrigation References</h3>
            </div>
            <a href="sources" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-bold">
                <i class="bi bi-shield-check me-1"></i> View All Reference Portals <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <p class="text-secondary small mb-4" style="line-height: 1.7;">
            The canal alignment, Gandak project data, irrigation command figures, and water release schedules are referenced from official government irrigation authorities:
        </p>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-water text-success fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">Water Resources Department, Bihar (WRD)</strong>
                            <span class="text-muted small">Official data on Saran Main Canal, Marhowrah Branch, Chapra Branch, and Gandak Project Circle Chapra. (<a href="https://wrd.bihar.gov.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">wrd.bihar.gov.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-diagram-3-fill text-primary fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">Pradhan Mantri Krishi Sinchayee Yojana (PMKSY)</strong>
                            <span class="text-muted small">District Irrigation Plan (DIP) for Saran District — Command area development and water use efficiency records.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-speedometer2 text-info fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">Central Water Commission (CWC)</strong>
                            <span class="text-muted small">Gandak Barrage discharge allocation, inter-state water sharing reports, and canal flow data. (<a href="https://cwc.gov.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">cwc.gov.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-building text-warning fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">Saran District Administration (saran.nic.in)</strong>
                            <span class="text-muted small">District agriculture and minor irrigation branch records across all 20 blocks. (<a href="https://saran.nic.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">saran.nic.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Banner -->
    <div class="card border-0 shadow rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #064e3b 0%, #047857 100%);">
        <div class="card-body p-4 p-md-5 text-white text-center">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
                <i class="bi bi-compass me-1"></i> Explore Saran District Digitally
            </span>
            <h3 class="fw-bold font-heading text-white display-6 mb-3">
                Connecting Every Block, Canal & Panchayat of Saran
            </h3>
            <p class="text-white-50 lead fs-6 mx-auto mb-4" style="max-width: 700px;">
                Explore all 20 blocks, rivers, historical sites, emergency helplines, and verified local businesses across Saran District on Saran Index.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="blocks" class="btn btn-warning text-dark fw-bold px-4 py-2.5 rounded-pill shadow">
                    <i class="bi bi-geo-alt-fill me-1"></i> Explore All 20 Blocks
                </a>
                <a href="river" class="btn btn-outline-light fw-bold px-4 py-2.5 rounded-pill">
                    <i class="bi bi-water me-1"></i> Rivers of Saran
                </a>
                <a href="history" class="btn btn-light text-success fw-bold px-4 py-2.5 rounded-pill shadow">
                    <i class="bi bi-hourglass-split me-1"></i> History & Heritage
                </a>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

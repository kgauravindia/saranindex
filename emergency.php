<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = "24/7 Emergency Helplines & Emergency Numbers Saran District – Saran Index";
$meta_description = "24x7 Emergency helpline numbers for Police, Sadar Hospital, Ambulance, Blood Bank, DM Control Room, Fire Station, and Women Helpline in Saran District, Bihar.";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header -->
<div class="bg-gradient-primary text-white py-4 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-2 text-center">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb justify-content-center mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill"></i> Home</a></li>
                <li class="breadcrumb-item text-white-50">Directory</li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">Emergency Helplines</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-center gap-2 mb-2 flex-wrap">
            <span class="badge bg-danger text-white fw-bold px-3 py-1 rounded-pill fs-7 shadow-sm">
                <i class="bi bi-shield-exclamation me-1"></i> 24x7 Emergency Helpline
            </span>
            <span class="badge px-3 py-1 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35);">
                <i class="bi bi-geo-alt-fill me-1"></i> District Saran
            </span>
        </div>

        <h1 class="fw-bold font-heading text-white display-6 mb-1">
            24/7 Emergency Contacts & Helplines
        </h1>
        <p class="text-white-50 small mx-auto mb-0" style="max-width: 720px;">
            Direct dial phone numbers for Police Stations, Sadar Hospital, Blood Banks, Ambulance, Fire Brigade, and District Administration Control Rooms in Saran District.
        </p>
    </div>
</div>

<!-- Main Content -->
<div class="container py-4">

    <!-- Top 4 National Emergency Quick Cards -->
    <div class="row g-3 mb-5">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 stat-card-red hover-shadow transition-all">
                <div class="badge bg-danger text-white rounded-pill px-2.5 py-1 mb-2 fs-7">
                    <i class="bi bi-shield-fill me-1"></i> Police / Fire / Medical
                </div>
                <div class="display-6 fw-bolder text-danger font-heading mb-1">112</div>
                <p class="text-dark fw-bold small mb-2">National Emergency Number</p>
                <a href="tel:112" class="btn btn-danger btn-sm w-100 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-telephone-fill me-1"></i> Call 112
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 stat-card-blue hover-shadow transition-all">
                <div class="badge bg-primary text-white rounded-pill px-2.5 py-1 mb-2 fs-7">
                    <i class="bi bi-ambulance me-1"></i> 24x7 Medical Service
                </div>
                <div class="display-6 fw-bolder text-primary font-heading mb-1">102 / 108</div>
                <p class="text-dark fw-bold small mb-2">Government Ambulance</p>
                <a href="tel:102" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-telephone-fill me-1"></i> Call 102
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 stat-card-pink hover-shadow transition-all">
                <div class="badge bg-pink-subtle rounded-pill px-2.5 py-1 mb-2 fs-7 fw-bold">
                    <i class="bi bi-person-fill-lock me-1"></i> Women Safety
                </div>
                <div class="display-6 fw-bolder font-heading mb-1" style="color: #be185d;">181</div>
                <p class="text-dark fw-bold small mb-2">Women Helpline</p>
                <a href="tel:181" class="btn btn-pink btn-sm w-100 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-telephone-fill me-1"></i> Call 181
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 stat-card-green hover-shadow transition-all">
                <div class="badge bg-success text-white rounded-pill px-2.5 py-1 mb-2 fs-7">
                    <i class="bi bi-heart-pulse-fill me-1"></i> Child Protection
                </div>
                <div class="display-6 fw-bolder text-success font-heading mb-1">1098</div>
                <p class="text-dark fw-bold small mb-2">Child Helpline</p>
                <a href="tel:1098" class="btn btn-success btn-sm w-100 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-telephone-fill me-1"></i> Call 1098
                </a>
            </div>
        </div>
    </div>

    <!-- 1. Police & Law Enforcement Section -->
    <?php
    $police_stations_list = getListings('', 'government', '', 100, 0, 'police-stations');
    ?>
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white border-top border-4 border-danger">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 border-bottom pb-3">
            <div>
                <h5 class="fw-bold text-dark mb-1 font-heading">
                    <i class="bi bi-shield-fill text-danger me-2 fs-4"></i> Police Stations & Law Enforcement (सारण पुलिस)
                </h5>
                <p class="text-muted small mb-0">Direct helpline & contact numbers for all 39 Police Stations and Outposts (ओपी) across Saran District.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fs-7 fw-bold">
                    <i class="bi bi-shield-check me-1"></i> <?php echo count($police_stations_list); ?> Police Stations
                </span>
                <a href="<?php echo BASE_URL; ?>government/police-stations" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">
                    View Full Directory <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- Quick Filter Input -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-danger"></i></span>
                <input type="text" id="policeSearchInput" class="form-control bg-light border-start-0 py-2" placeholder="Search police station by name, block, or area (e.g. Chapra, Sonpur, Marhaura, Ekma, Doriganj)..." onkeyup="filterPoliceStations()">
            </div>
        </div>

        <div class="row g-3" id="policeStationsGrid">
            <?php if (!empty($police_stations_list)): ?>
                <?php foreach ($police_stations_list as $ps): 
                    $callNum = !empty($ps['mobile']) ? preg_replace('/[^0-9]/', '', $ps['mobile']) : '';
                    if (strlen($callNum) == 10) {
                        $callNum = '91' . $callNum;
                    }
                ?>
                    <div class="col-md-6 col-lg-4 police-station-item" data-name="<?php echo strtolower(htmlspecialchars($ps['title'] . ' ' . $ps['hindi_title'] . ' ' . ($ps['block_name'] ?? '') . ' ' . $ps['address'])); ?>">
                        <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-start justify-content-between mb-1.5">
                                    <h6 class="fw-bold text-dark mb-0 fs-6">
                                        <a href="<?php echo BASE_URL . sanitizeInput($ps['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                            <?php echo sanitizeInput($ps['title']); ?>
                                        </a>
                                    </h6>
                                    <span class="badge bg-danger-subtle text-danger fs-8 rounded-pill ms-1 flex-shrink-0">24x7 Thana</span>
                                </div>
                                <?php if (!empty($ps['hindi_title'])): ?>
                                    <div class="text-secondary small fw-medium mb-1.5"><?php echo sanitizeInput($ps['hindi_title']); ?></div>
                                <?php endif; ?>
                                <p class="text-muted fs-8 mb-2">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i> <?php echo sanitizeInput($ps['address']); ?>
                                </p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top gap-2">
                                <span class="fw-bold text-dark fs-7 font-monospace"><?php echo sanitizeInput($ps['mobile']); ?></span>
                                <div class="d-flex gap-1.5">
                                    <?php if (!empty($callNum)): ?>
                                        <a href="tel:<?php echo $callNum; ?>" class="btn btn-danger btn-sm rounded-pill px-2.5 py-1 fw-bold fs-8" title="Call Police Station">
                                            <i class="bi bi-telephone-fill me-1"></i> Call
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo BASE_URL . sanitizeInput($ps['slug']); ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 py-1 fs-8" title="View Details">
                                        <i class="bi bi-info-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-3">No police stations found.</div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function filterPoliceStations() {
        const input = document.getElementById('policeSearchInput').value.toLowerCase();
        const items = document.querySelectorAll('.police-station-item');
        items.forEach(item => {
            const data = item.getAttribute('data-name');
            if (data.indexOf(input) > -1) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }
    </script>

    <!-- 2. Hospitals & Blood Banks Section -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white border-top border-4 border-success">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <h5 class="fw-bold text-dark mb-0 font-heading">
                <i class="bi bi-hospital-fill text-success me-2 fs-4"></i> Medical & Hospitals
            </h5>
            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fs-7 fw-bold">
                Health Services
            </span>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">Sadar Hospital Emergency</h6>
                            <span class="badge bg-success text-white fs-7 rounded-pill">24x7 ER</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-success"></i> Hospital Road, Municipal Chowk, Chapra</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-243405</span>
                        <a href="tel:06152243405" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> Call Emergency Room
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">Sadar Blood Bank Chapra</h6>
                            <span class="badge bg-danger-subtle text-danger fs-7 rounded-pill fw-bold">Blood Bank</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-danger"></i> Sadar Hospital Campus, Chapra</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-245100</span>
                        <a href="tel:06152245100" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-droplet-fill me-1"></i> Call Blood Bank
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">Red Cross Society Blood Bank</h6>
                            <span class="badge bg-info-subtle text-info fs-7 rounded-pill fw-bold">Red Cross</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-info"></i> Red Cross Building, Hospital Road, Chapra</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-242500</span>
                        <a href="tel:06152242500" class="btn btn-info btn-sm rounded-pill px-3 fw-bold text-white">
                            <i class="bi bi-telephone-fill me-1"></i> Call Red Cross
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">District Health Society Helpline</h6>
                            <span class="badge bg-success-subtle text-success fs-7 rounded-pill fw-bold">DHS Control</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-success"></i> Civil Surgeon Office Campus, Chapra</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-245005</span>
                        <a href="tel:06152245005" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> Call Health Control
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Administration & Fire Services Section -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white border-top border-4 border-warning">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <h5 class="fw-bold text-dark mb-0 font-heading">
                <i class="bi bi-building-fill-gear text-warning me-2 fs-4"></i> District Administration & Fire Station
            </h5>
            <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-1 fs-7 fw-bold">
                Admin Control Rooms
            </span>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">DM Saran Control Room</h6>
                            <span class="badge bg-primary text-white fs-7 rounded-pill">DM Office</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-primary"></i> Collectorate Campus, Katchahry Chowk, Chapra</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-245001</span>
                        <a href="tel:06152245001" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> Call DM Control
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">Fire Station Chapra</h6>
                            <span class="badge bg-danger text-white fs-7 rounded-pill">101 Fire</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-danger"></i> Fire Brigade Road, Near New Market, Chapra</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-242200 / 101</span>
                        <a href="tel:101" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-fire me-1"></i> Call Fire Station
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">Disaster Management Control Room</h6>
                            <span class="badge bg-warning text-dark fs-7 rounded-pill">Disaster Control</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-warning"></i> Collectorate Building, Chapra</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-245000</span>
                        <a href="tel:06152245000" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark">
                            <i class="bi bi-telephone-fill me-1"></i> Call Disaster Room
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">SDM Sadar Chapra</h6>
                            <span class="badge bg-secondary text-white fs-7 rounded-pill">SDM Office</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-secondary"></i> Sub-Divisional Office, Katchahry, Chapra</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-242401</span>
                        <a href="tel:06152242401" class="btn btn-secondary btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> Call SDM Office
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Flood, Irrigation & Embankment Helpline (WRD BeFIQR) -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white border-top border-4 border-info">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3 flex-wrap gap-2">
            <h5 class="fw-bold text-dark mb-0 font-heading">
                <i class="bi bi-water text-info me-2 fs-4"></i> Flood, Irrigation & Embankment Control (WRD)
            </h5>
            <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-1 fs-7 fw-bold">
                BeFIQR Quick Response
            </span>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">WRD 24x7 Flood & Irrigation Control Room (BeFIQR)</h6>
                            <span class="badge bg-danger text-white fs-7 rounded-pill">Toll Free 24x7</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-shield-check me-1 text-danger"></i> BeFIQR (Bihar eSystem for Flood & Irrigation Quick Response), Water Resources Dept</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-danger fs-6">1800 3456 145</span>
                        <a href="tel:18003456145" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> Call 1800 3456 145
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">Saran Canal Circle Office, Chapra</h6>
                            <span class="badge bg-info text-white fs-7 rounded-pill">Saran WRD</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-info"></i> Water Resources Department (Canals & Irrigation), Chapra</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-232492 / 7463889124</span>
                        <a href="tel:7463889124" class="btn btn-info btn-sm rounded-pill px-3 fw-bold text-white">
                            <i class="bi bi-telephone-fill me-1"></i> Call Canal Circle
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

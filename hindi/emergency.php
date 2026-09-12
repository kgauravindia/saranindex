<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = "24/7 आपातकालीन हेल्पलाइन एवं आपातकालीन नंबर सारण (छपरा) – सारण इंडेक्स";
$meta_description = "सारण (छपरा, बिहार) में पुलिस, सदर अस्पताल, एम्बुलेंस, ब्लड बैंक, डीएम कंट्रोल रूम, फायर स्टेशन और महिला हेल्पलाइन के 24x7 आपातकालीन हेल्पलाइन नंबर।";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header -->
<div class="bg-gradient-primary text-white py-4 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-2 text-center">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb justify-content-center mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill"></i> मुख्य पृष्ठ</a></li>
                <li class="breadcrumb-item text-white-50">निर्देशिका</li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">आपातकालीन हेल्पलाइन</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-center gap-2 mb-2 flex-wrap">
            <span class="badge bg-danger text-white fw-bold px-3 py-1 rounded-pill fs-7 shadow-sm">
                <i class="bi bi-shield-exclamation me-1"></i> 24x7 आपातकालीन हेल्पलाइन
            </span>
            <span class="badge px-3 py-1 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35);">
                <i class="bi bi-geo-alt-fill me-1"></i> जिला सारण (छपरा)
            </span>
        </div>

        <h1 class="fw-bold font-heading text-white display-6 mb-1">
            24/7 आपातकालीन संपर्क सूत्र एवं हेल्पलाइन
        </h1>
        <h2 class="h5 text-warning font-heading mb-2">
            सारण (छपरा) आपातकालीन हेल्पलाइन सूची
        </h2>
        <p class="text-white-50 small mx-auto mb-0" style="max-width: 720px;">
            सारण जिले में पुलिस थानों, सदर अस्पताल, ब्लड बैंक, एम्बुलेंस, दमकल और जिला प्रशासन नियंत्रण कक्ष के डायरेक्ट डायल फोन नंबर।
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
                    <i class="bi bi-shield-fill me-1"></i> पुलिस / फायर / मेडिकल
                </div>
                <div class="display-6 fw-bolder text-danger font-heading mb-1">112</div>
                <p class="text-dark fw-bold small mb-2">राष्ट्रीय आपातकालीन नंबर</p>
                <a href="tel:112" class="btn btn-danger btn-sm w-100 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-telephone-fill me-1"></i> 112 पर कॉल करें
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 stat-card-blue hover-shadow transition-all">
                <div class="badge bg-primary text-white rounded-pill px-2.5 py-1 mb-2 fs-7">
                    <i class="bi bi-ambulance me-1"></i> 24x7 मेडिकल सेवा
                </div>
                <div class="display-6 fw-bolder text-primary font-heading mb-1">102 / 108</div>
                <p class="text-dark fw-bold small mb-2">सरकारी एम्बुलेंस</p>
                <a href="tel:102" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-telephone-fill me-1"></i> 102 पर कॉल करें
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 stat-card-pink hover-shadow transition-all">
                <div class="badge bg-pink-subtle rounded-pill px-2.5 py-1 mb-2 fs-7 fw-bold">
                    <i class="bi bi-person-fill-lock me-1"></i> महिला सुरक्षा
                </div>
                <div class="display-6 fw-bolder font-heading mb-1" style="color: #be185d;">181</div>
                <p class="text-dark fw-bold small mb-2">महिला हेल्पलाइन</p>
                <a href="tel:181" class="btn btn-pink btn-sm w-100 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-telephone-fill me-1"></i> 181 पर कॉल करें
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 stat-card-green hover-shadow transition-all">
                <div class="badge bg-success text-white rounded-pill px-2.5 py-1 mb-2 fs-7">
                    <i class="bi bi-heart-pulse-fill me-1"></i> बाल सुरक्षा
                </div>
                <div class="display-6 fw-bolder text-success font-heading mb-1">1098</div>
                <p class="text-dark fw-bold small mb-2">चाइल्ड हेल्पलाइन</p>
                <a href="tel:1098" class="btn btn-success btn-sm w-100 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-telephone-fill me-1"></i> 1098 पर कॉल करें
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
                    <i class="bi bi-shield-fill text-danger me-2 fs-4"></i> पुलिस एवं सुरक्षा बल (Police Stations & Law Enforcement)
                </h5>
                <p class="text-muted small mb-0">सारण जिले के सभी 39 पुलिस थानों एवं पुलिस चौकियों (ओपी) के सीधे संपर्क व हेल्पलाइन नंबर।</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fs-7 fw-bold">
                    <i class="bi bi-shield-check me-1"></i> <?php echo count($police_stations_list); ?> पुलिस थाने
                </span>
                <a href="<?php echo BASE_URL; ?>hindi/government/police-stations" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">
                    पूरी सूची देखें <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- Quick Filter Input -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-danger"></i></span>
                <input type="text" id="policeSearchInputHi" class="form-control bg-light border-start-0 py-2" placeholder="थाने के नाम, प्रखंड या क्षेत्र से खोजें (उदा. छपरा, सोनपुर, मढ़ौरा, एकमा, डोरीगंज)..." onkeyup="filterPoliceStationsHi()">
            </div>
        </div>

        <div class="row g-3" id="policeStationsGridHi">
            <?php if (!empty($police_stations_list)): ?>
                <?php foreach ($police_stations_list as $ps): 
                    $callNum = !empty($ps['mobile']) ? preg_replace('/[^0-9]/', '', $ps['mobile']) : '';
                    if (strlen($callNum) == 10) {
                        $callNum = '91' . $callNum;
                    }
                    $displayName = !empty($ps['hindi_title']) ? $ps['hindi_title'] : $ps['title'];
                    $subName = !empty($ps['hindi_title']) ? $ps['title'] : '';
                ?>
                    <div class="col-md-6 col-lg-4 police-station-item-hi" data-name="<?php echo strtolower(htmlspecialchars($ps['title'] . ' ' . $ps['hindi_title'] . ' ' . ($ps['block_name'] ?? '') . ' ' . $ps['address'])); ?>">
                        <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-start justify-content-between mb-1.5">
                                    <h6 class="fw-bold text-dark mb-0 fs-6">
                                        <a href="<?php echo BASE_URL . 'hindi/' . sanitizeInput($ps['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                            <?php echo sanitizeInput($displayName); ?>
                                        </a>
                                    </h6>
                                    <span class="badge bg-danger-subtle text-danger fs-8 rounded-pill ms-1 flex-shrink-0">24x7 थाना</span>
                                </div>
                                <?php if (!empty($subName)): ?>
                                    <div class="text-secondary small fw-medium mb-1.5"><?php echo sanitizeInput($subName); ?></div>
                                <?php endif; ?>
                                <p class="text-muted fs-8 mb-2">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i> <?php echo sanitizeInput($ps['address']); ?>
                                </p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top gap-2">
                                <span class="fw-bold text-dark fs-7 font-monospace"><?php echo sanitizeInput($ps['mobile']); ?></span>
                                <div class="d-flex gap-1.5">
                                    <?php if (!empty($callNum)): ?>
                                        <a href="tel:<?php echo $callNum; ?>" class="btn btn-danger btn-sm rounded-pill px-2.5 py-1 fw-bold fs-8" title="थाना कॉल करें">
                                            <i class="bi bi-telephone-fill me-1"></i> कॉल
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo BASE_URL . 'hindi/' . sanitizeInput($ps['slug']); ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 py-1 fs-8" title="विवरण देखें">
                                        <i class="bi bi-info-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-3">कोई थाना नहीं मिला।</div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function filterPoliceStationsHi() {
        const input = document.getElementById('policeSearchInputHi').value.toLowerCase();
        const items = document.querySelectorAll('.police-station-item-hi');
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
                <i class="bi bi-hospital-fill text-success me-2 fs-4"></i> चिकित्सा एवं अस्पताल (Hospitals & Medical)
            </h5>
            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fs-7 fw-bold">
                स्वास्थ्य सेवाएं
            </span>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">सदर अस्पताल आपातकालीन (Sadar Hospital ER)</h6>
                            <span class="badge bg-success text-white fs-7 rounded-pill">24x7 आपातकालीन</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-success"></i> अस्पताल रोड, नगरपालिका चौक, छपरा</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-243405</span>
                        <a href="tel:06152243405" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> आपातकालीन कक्ष कॉल करें
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">सदर ब्लड बैंक छपरा (Sadar Blood Bank)</h6>
                            <span class="badge bg-danger-subtle text-danger fs-7 rounded-pill fw-bold">ब्लड बैंक</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-danger"></i> सदर अस्पताल परिसर, छपरा</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-245100</span>
                        <a href="tel:06152245100" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-droplet-fill me-1"></i> ब्लड बैंक कॉल करें
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">रेड क्रॉस सोसायटी ब्लड बैंक (Red Cross Blood Bank)</h6>
                            <span class="badge bg-info-subtle text-info fs-7 rounded-pill fw-bold">रेड क्रॉस</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-info"></i> रेड क्रॉस भवन, अस्पताल रोड, छपरा</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-242500</span>
                        <a href="tel:06152242500" class="btn btn-info btn-sm rounded-pill px-3 fw-bold text-white">
                            <i class="bi bi-telephone-fill me-1"></i> रेड क्रॉस कॉल करें
                        </a>
                    </div>
                </div>
            </div>

                        <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">अस्पताल एवं आयुष्मान भारत टोल-फ्री हेल्पलाइन (PM-JAY)</h6>
                            <span class="badge bg-danger text-white fs-7 rounded-pill fw-bold">24x7 टोल-फ्री</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-shield-plus me-1 text-danger"></i> राष्ट्रीय स्वास्थ्य प्राधिकरण (NHA) एवं आयुष्मान अस्पताल सहायता केंद्र</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-danger fs-5">14555 / 1800-11-4477</span>
                        <a href="tel:14555" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> 14555 पर कॉल करें
                        </a>
                    </div>
                </div>
            </div>
<div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">जिला स्वास्थ्य समिति हेल्पलाइन (DHS Control)</h6>
                            <span class="badge bg-success-subtle text-success fs-7 rounded-pill fw-bold">स्वास्थ्य समिति</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-success"></i> सिविल सर्जन कार्यालय परिसर, छपरा</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-245005</span>
                        <a href="tel:06152245005" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> स्वास्थ्य कंट्रोल कॉल करें
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
                <i class="bi bi-building-fill-gear text-warning me-2 fs-4"></i> जिला प्रशासन एवं अग्निशमन (District Admin & Fire)
            </h5>
            <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-1 fs-7 fw-bold">
                प्रशासनिक कंट्रोल रूम
            </span>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">डीएम सारण नियंत्रण कक्ष (DM Saran Control Room)</h6>
                            <span class="badge bg-primary text-white fs-7 rounded-pill">डीएम कार्यालय</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-primary"></i> समाहरणालय परिसर, कचहरी चौक, छपरा</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-245001</span>
                        <a href="tel:06152245001" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> डीएम कंट्रोल कॉल करें
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">अग्निशमन विभाग छपरा (Fire Station Chapra)</h6>
                            <span class="badge bg-danger text-white fs-7 rounded-pill">101 दमकल</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-danger"></i> फायर ब्रिगेड रोड, न्यू मार्केट के पास, छपरा</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-242200 / 101</span>
                        <a href="tel:101" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-fire me-1"></i> दमकल कॉल करें
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">आपदा प्रबंधन नियंत्रण कक्ष (Disaster Management)</h6>
                            <span class="badge bg-warning text-dark fs-7 rounded-pill">आपदा कंट्रोल</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-warning"></i> समाहरणालय भवन, छपरा</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-245000</span>
                        <a href="tel:06152245000" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark">
                            <i class="bi bi-telephone-fill me-1"></i> आपदा कक्ष कॉल करें
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">अनुमंडल पदाधिकारी सदर (SDM Sadar Chapra)</h6>
                            <span class="badge bg-secondary text-white fs-7 rounded-pill">एसडीएम कार्यालय</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-secondary"></i> अनुमंडल कार्यालय, कचहरी, छपरा</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-242401</span>
                        <a href="tel:06152242401" class="btn btn-secondary btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> एसडीएम कार्यालय कॉल करें
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
                <i class="bi bi-water text-info me-2 fs-4"></i> बाढ़, सिंचाई एवं तटबंध नियंत्रण कक्ष (जल संसाधन विभाग)
            </h5>
            <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-1 fs-7 fw-bold">
                BeFIQR क्विक रिस्पांस
            </span>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">जल संसाधन विभाग 24x7 बाढ़ व सिंचाई नियंत्रण कक्ष (BeFIQR)</h6>
                            <span class="badge bg-danger text-white fs-7 rounded-pill">टोल फ्री 24x7</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-shield-check me-1 text-danger"></i> BeFIQR (बाढ़ एवं सिंचाई त्वरित प्रतिक्रिया प्रणाली), जल संसाधन विभाग, बिहार</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-danger fs-6">1800 3456 145</span>
                        <a href="tel:18003456145" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> 1800 3456 145 पर कॉल करें
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light hover-shadow transition-all h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="fw-bold text-dark mb-0">सारण नहर अंचल कार्यालय, छपरा</h6>
                            <span class="badge bg-info text-white fs-7 rounded-pill">सारण WRD</span>
                        </div>
                        <p class="text-muted fs-7 mb-2"><i class="bi bi-geo-alt me-1 text-info"></i> जल संसाधन विभाग (नहर एवं सिंचाई प्रबंधन), छपरा</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="fw-bold text-dark fs-6">06152-232492 / 7463889124</span>
                        <a href="tel:7463889124" class="btn btn-info btn-sm rounded-pill px-3 fw-bold text-white">
                            <i class="bi bi-telephone-fill me-1"></i> नहर अंचल कॉल करें
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

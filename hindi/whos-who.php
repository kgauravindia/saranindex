<?php
require_once __DIR__ . '/../includes/functions.php';

$page_title = "सारण जिला प्रशासन: कौन-कौन हैं – प्रशासनिक अधिकारियों की आधिकारिक निर्देशिका | सारण इंडेक्स";
$meta_description = "सारण जिला प्रशासन की आधिकारिक अधिकारी निर्देशिका – जिलाधिकारी श्री वैभव श्रीवास्तव (भा.प्र.से.), पुलिस अधीक्षक श्री रौशन कुमार (भा.पु.से.), उप विकास आयुक्त, एसडीओ, बीडीओ, सीओ एवं अन्य विभागाध्यक्ष।";

$langSwitchUrl = '../whos-who';
$whosWhoDepts = require __DIR__ . '/../includes/whos_who_data.php';

// Calculate totals
$totalDepts = count($whosWhoDepts);
$totalOfficers = 0;
foreach ($whosWhoDepts as $d) {
    $totalOfficers += count($d['officers']);
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header Section -->
<div class="position-relative overflow-hidden text-white py-5 shadow-sm" style="background: linear-gradient(135deg, #091e3a 0%, #103766 50%, #1e4976 100%);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-20" style="background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.2) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(14,165,233,0.3) 0%, transparent 50%); pointer-events: none;"></div>
    
    <div class="container position-relative z-1 py-4 text-center">
        <div class="d-inline-flex align-items-center bg-white bg-opacity-10 text-white fw-semibold px-3.5 py-1.5 rounded-pill mb-3 border border-white border-opacity-20 shadow-sm small backdrop-blur">
            <i class="bi bi-patch-check-fill text-warning me-2 fs-6"></i> आधिकारिक जिला प्रशासन निर्देशिका • सारण (छपरा)
        </div>
        
        <h1 class="fw-bolder font-heading text-white display-5 mb-3 text-wrap">
            सारण जिला प्रशासन: कौन-कौन हैं (Who's Who)
        </h1>
        
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 820px; line-height: 1.7;">
            सारण जिले के शीर्ष प्रशासनिक अधिकारियों, पुलिस नेतृत्व, अनुमंडल पदाधिकारियों (SDO), प्रखंड विकास पदाधिकारियों (BDO), अंचलाधिकारियों (CO) एवं नागरिक विभागाध्यक्षों की संपूर्ण संपर्क सूची, आधिकारिक पोर्टल (<a href="https://saran.nic.in/hi/जिले-के-बारे-में/कौन-कौन-है/" target="_blank" rel="noopener noreferrer" class="text-warning text-decoration-none fw-semibold">saran.nic.in</a>) से सत्यापित।
        </p>

        <!-- Stats Counter Strip -->
        <div class="row justify-content-center g-3 mt-2">
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h3 fw-bold text-white mb-0"><?php echo number_format($totalOfficers); ?>+</div>
                    <div class="text-white-50 fs-7 fw-medium">अधिकारी सूचीबद्ध</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h3 fw-bold text-warning mb-0"><?php echo $totalDepts; ?></div>
                    <div class="text-white-50 fs-7 fw-medium">प्रमुख प्रशासनिक विभाग</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h3 fw-bold text-info mb-0">100%</div>
                    <div class="text-white-50 fs-7 fw-medium">सरकारी स्रोत से सत्यापित</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h3 fw-bold text-success mb-0">24x7</div>
                    <div class="text-white-50 fs-7 fw-medium">नियंत्रण कक्ष एवं हेल्पलाइन</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">

    <!-- Apex District Leadership Spotlight -->
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">
                    <i class="bi bi-star-fill me-1 text-warning"></i>शीर्ष प्रशासनिक नेतृत्व
                </span>
                <h2 class="fw-bold font-heading text-dark mt-2 mb-0 fs-3">सारण जिले के शीर्ष प्रशासनिक अधिकारी</h2>
            </div>
            <a href="https://saran.nic.in/hi/जिले-के-बारे-में/कौन-कौन-है/" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm rounded-pill px-3 d-none d-md-inline-flex align-items-center gap-1.5">
                <i class="bi bi-box-arrow-up-right"></i> आधिकारिक स्रोत
            </a>
        </div>

        <div class="row g-4">
            <!-- 1. District Magistrate -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border-top border-4 border-primary hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-bold rounded-pill px-3 py-1">जिलाधिकारी</span>
                            <span class="badge bg-light text-muted border rounded-pill">कक्ष: 240002</span>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1">श्री वैभव श्रीवास्तव, भा.प्र.से.</h4>
                        <div class="text-primary fw-semibold small mb-2">जिला दण्डाधिकारी - सह - समाहर्ता, सारण</div>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-geo-alt me-1 text-secondary"></i> समाहरणालय परिसर, कचहरी रोड, छपरा - 841301
                        </p>
                    </div>
                    <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                        <div>
                            <div class="extra-small text-muted text-uppercase fw-bold">कार्यालय दूरभाष</div>
                            <span class="fw-bold text-dark font-monospace fs-6">06152-240001</span>
                        </div>
                        <a href="tel:06152240001" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> कॉल करें
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Senior Superintendent of Police -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border-top border-4 border-danger hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-danger-subtle text-danger fw-bold rounded-pill px-3 py-1">विधि-व्यवस्था प्रमुख</span>
                            <span class="badge bg-light text-muted border rounded-pill">सारण पुलिस</span>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1">श्री रौशन कुमार, भा.पु.से.</h4>
                        <div class="text-danger fw-semibold small mb-2">वरीय पुलिस अधीक्षक (SSP), सारण</div>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-geo-alt me-1 text-secondary"></i> पुलिस अधीक्षक कार्यालय, समाहरणालय, छपरा - 841301
                        </p>
                    </div>
                    <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                        <div>
                            <div class="extra-small text-muted text-uppercase fw-bold">सीयूजी मोबाइल</div>
                            <span class="fw-bold text-dark font-monospace fs-6">9031827135</span>
                        </div>
                        <a href="tel:9031827135" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> कॉल करें
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Deputy Development Commissioner -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border-top border-4 border-success hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-3 py-1">ग्रामीण विकास</span>
                            <span class="badge bg-light text-muted border rounded-pill">कक्ष: 232638</span>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1">श्री लक्ष्मण तिवारी, भा.प्र.से.</h4>
                        <div class="text-success fw-semibold small mb-2">उप विकास आयुक्त (DDC), सारण</div>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-geo-alt me-1 text-secondary"></i> उप विकास आयुक्त कार्यालय, समाहरणालय परिसर, छपरा
                        </p>
                    </div>
                    <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                        <div>
                            <div class="extra-small text-muted text-uppercase fw-bold">सीयूजी मोबाइल</div>
                            <span class="fw-bold text-dark font-monospace fs-6">9031071904</span>
                        </div>
                        <a href="tel:9031071904" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> कॉल करें
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Divisional Commissioner -->
            <div class="col-md-6 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-light border-start border-4 border-info h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="badge bg-info-subtle text-info-emphasis fw-bold rounded-pill px-3 py-1 mb-2">प्रमंडलीय आयुक्त</span>
                            <h5 class="fw-bold text-dark mb-1">श्री मनीष कुमार, भा.प्र.से.</h5>
                            <div class="text-muted small mb-2">प्रमंडलीय आयुक्त, सारण प्रमंडल (सारण, सीवान एवं गोपालगंज)</div>
                            <div class="small text-secondary"><i class="bi bi-geo-alt me-1"></i> आयुक्त कार्यालय परिसर, डाकबंगला रोड, छपरा</div>
                        </div>
                        <a href="tel:06152232400" class="btn btn-outline-info btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone me-1"></i> 06152-232400
                        </a>
                    </div>
                </div>
            </div>

            <!-- 5. DIG Saran Range -->
            <div class="col-md-6 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-light border-start border-4 border-warning h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="badge bg-warning-subtle text-dark fw-bold rounded-pill px-3 py-1 mb-2">पुलिस उप-महानिरीक्षक (DIG)</span>
                            <h5 class="fw-bold text-dark mb-1">श्री नीलेश कुमार, भा.पु.से.</h5>
                            <div class="text-muted small mb-2">पुलिस उप-महानिरीक्षक (DIG), सारण क्षेत्र, छपरा</div>
                            <div class="small text-secondary"><i class="bi bi-geo-alt me-1"></i> डीआईजी कार्यालय, आयुक्त परिसर, छपरा</div>
                        </div>
                        <a href="tel:9013827134" class="btn btn-outline-warning text-dark btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone me-1"></i> 9013827134
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Search & Category Filter Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white sticky-top" style="top: 75px; z-index: 100;">
        <div class="row g-3 align-items-center">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-primary"><i class="bi bi-search"></i></span>
                    <input type="text" id="officerSearchInput" class="form-control bg-light border-start-0 py-2.5" placeholder="अधिकारी के नाम, पदनाम, प्रखंड या फोन से खोजें (उदा. एसडीओ, बीडीओ अमनौर, सीओ, ट्रैफिक, सिविल सर्जन)..." onkeyup="filterOfficers()">
                    <button class="btn btn-outline-secondary" type="button" onclick="clearOfficerSearch()"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex gap-2 flex-wrap justify-content-lg-end" id="deptFilterButtons">
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 active filter-pill" data-target="all" onclick="filterByDept('all', this)">
                        सभी विभाग (<?php echo $totalOfficers; ?>)
                    </button>
                    <?php foreach ($whosWhoDepts as $d): ?>
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 filter-pill" data-target="<?php echo $d['id']; ?>" onclick="filterByDept('<?php echo $d['id']; ?>', this)">
                            <i class="bi <?php echo $d['icon']; ?> me-1"></i><?php echo htmlspecialchars($d['name_hi']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Departments & Officers Registry -->
    <div id="departmentsContainer">
        <?php foreach ($whosWhoDepts as $dept): ?>
            <div class="dept-section mb-5" id="dept-<?php echo $dept['id']; ?>" data-dept="<?php echo $dept['id']; ?>">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi <?php echo $dept['icon']; ?> fs-5"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold font-heading text-dark mb-0 fs-4"><?php echo htmlspecialchars($dept['name_hi']); ?></h3>
                            <div class="text-muted extra-small"><?php echo htmlspecialchars($dept['desc_hi']); ?></div>
                        </div>
                    </div>
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 fs-7 fw-bold">
                        <?php echo count($dept['officers']); ?> अधिकारी
                    </span>
                </div>

                <div class="row g-3">
                    <?php foreach ($dept['officers'] as $officer): 
                        $searchStr = strtolower($officer['name_en'] . ' ' . $officer['name_hi'] . ' ' . $officer['designation_en'] . ' ' . $officer['designation_hi'] . ' ' . $officer['address_en'] . ' ' . $officer['address_hi'] . ' ' . $officer['phone'] . ' ' . $officer['email']);
                        $callPhone = preg_replace('/[^0-9]/', '', $officer['phone']);
                    ?>
                        <div class="col-md-6 col-lg-4 officer-card-item" data-search="<?php echo htmlspecialchars($searchStr); ?>">
                            <div class="card h-100 border rounded-4 p-3.5 bg-white shadow-sm hover-shadow transition-all d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-start justify-content-between mb-1.5">
                                        <span class="badge bg-light text-primary border rounded-pill extra-small fw-semibold"><?php echo htmlspecialchars($dept['badge_hi'] ?? $dept['badge']); ?></span>
                                        <?php if (!empty($officer['room'])): ?>
                                            <span class="badge bg-light text-muted border rounded-pill extra-small">कक्ष: <?php echo htmlspecialchars($officer['room']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="fw-bold font-heading text-dark mb-1 fs-6">
                                        <?php echo htmlspecialchars($officer['name_hi'] ?: $officer['name_en']); ?>
                                    </h5>
                                    <?php if (!empty($officer['name_en']) && $officer['name_en'] !== $officer['name_hi']): ?>
                                        <div class="text-muted extra-small mb-1"><?php echo htmlspecialchars($officer['name_en']); ?></div>
                                    <?php endif; ?>
                                    <div class="text-primary fw-semibold small mb-2">
                                        <?php echo htmlspecialchars($officer['designation_hi'] ?: $officer['designation_en']); ?>
                                    </div>
                                    <?php if (!empty($officer['address_hi']) || !empty($officer['address_en'])): ?>
                                        <p class="text-secondary extra-small mb-2">
                                            <i class="bi bi-geo-alt me-1 text-danger"></i><?php echo htmlspecialchars($officer['address_hi'] ?: $officer['address_en']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div class="pt-2 border-top">
                                    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                                        <?php if (!empty($officer['phone'])): ?>
                                            <div>
                                                <span class="extra-small text-muted d-block">फोन / मोबाइल</span>
                                                <a href="tel:<?php echo $callPhone; ?>" class="fw-bold text-dark font-monospace small text-decoration-none hover-primary">
                                                    <i class="bi bi-telephone-fill text-success me-1"></i><?php echo htmlspecialchars($officer['phone']); ?>
                                                </a>
                                            </div>
                                            <a href="tel:<?php echo $callPhone; ?>" class="btn btn-outline-success btn-sm rounded-pill px-2.5 py-1 extra-small fw-bold" title="कॉल करें">
                                                <i class="bi bi-telephone-outbound-fill"></i> कॉल
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted extra-small fst-italic">समाहरणालय नियंत्रण कक्ष से संपर्क करें</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($officer['email'])): ?>
                                        <div class="mt-1.5 pt-1.5 border-top border-light">
                                            <a href="mailto:<?php echo htmlspecialchars($officer['email']); ?>" class="text-muted extra-small text-truncate d-block text-decoration-none">
                                                <i class="bi bi-envelope-at me-1 text-primary"></i><?php echo htmlspecialchars($officer['email']); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- No Search Results Found Alert -->
    <div id="noResultsMsg" class="d-none text-center py-5">
        <div class="text-muted mb-2"><i class="bi bi-search display-4 text-secondary opacity-50"></i></div>
        <h5 class="fw-bold text-dark">आपकी खोज से मिलता-जुलता कोई अधिकारी नहीं मिला।</h5>
        <p class="text-muted small">कृपया वर्तनी जांचें अथवा विभाग के नाम से खोजें (उदा. एसडीओ, पुलिस, बीडीओ, सीओ, शिक्षा)।</p>
        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="clearOfficerSearch()">फ़िल्टर रीसेट करें</button>
    </div>

    <!-- Official Authority & Disclaimer Callout -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mt-5 bg-light border-start border-4 border-primary">
        <div class="d-flex align-items-start">
            <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3 flex-shrink-0">
                <i class="bi bi-shield-check fs-3"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-1">आधिकारिक संदर्भ एवं डेटा विश्वसनीयता</h5>
                <p class="text-muted small mb-2">
                    यह निर्देशिका <strong>सारण जिला प्रशासन, बिहार सरकार</strong> के आधिकारिक पोर्टल (<a href="https://saran.nic.in/hi/जिले-के-बारे-में/कौन-कौन-है/" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none fw-semibold">saran.nic.in/hi/जिले-के-बारे-में/कौन-कौन-है/</a>) से सत्यापित एवं व्यवस्थित की गई है। प्रशासनिक दूरभाष, सीयूजी संपर्क एवं पदनाम जनहित व नागरिक सुविधाओं हेतु प्रकाशित हैं।
                </p>
                <div class="extra-small text-muted">
                    <i class="bi bi-info-circle me-1"></i> किसी भी आपात स्थिति या अपराध की सूचना हेतु <strong>112 (बिहार पुलिस इमरजेंसी)</strong> डायल करें या हमारी <a href="emergency" class="text-danger fw-semibold text-decoration-none">24x7 आपातकालीन हेल्पलाइन निर्देशिका</a> देखें।
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Client-side Interactive Filter Script -->
<script>
let currentDept = 'all';

function filterOfficers() {
    const input = document.getElementById('officerSearchInput').value.toLowerCase().trim();
    const items = document.querySelectorAll('.officer-card-item');
    const deptSections = document.querySelectorAll('.dept-section');
    let visibleCount = 0;

    items.forEach(item => {
        const itemSearch = item.getAttribute('data-search') || '';
        const parentDept = item.closest('.dept-section').getAttribute('data-dept');

        const matchesDept = (currentDept === 'all' || parentDept === currentDept);
        const matchesQuery = (input === '' || itemSearch.includes(input));

        if (matchesDept && matchesQuery) {
            item.classList.remove('d-none');
            visibleCount++;
        } else {
            item.classList.add('d-none');
        }
    });

    deptSections.forEach(dept => {
        const hasVisible = dept.querySelectorAll('.officer-card-item:not(.d-none)').length > 0;
        if (hasVisible) {
            dept.classList.remove('d-none');
        } else {
            dept.classList.add('d-none');
        }
    });

    const noResults = document.getElementById('noResultsMsg');
    if (visibleCount === 0) {
        noResults.classList.remove('d-none');
    } else {
        noResults.classList.add('d-none');
    }
}

function filterByDept(deptId, btnElem) {
    currentDept = deptId;
    document.querySelectorAll('#deptFilterButtons .filter-pill').forEach(b => {
        b.classList.remove('btn-primary', 'active');
        b.classList.add('btn-outline-secondary');
    });
    btnElem.classList.remove('btn-outline-secondary');
    btnElem.classList.add('btn-primary', 'active');
    filterOfficers();
}

function clearOfficerSearch() {
    document.getElementById('officerSearchInput').value = '';
    currentDept = 'all';
    document.querySelectorAll('#deptFilterButtons .filter-pill').forEach(b => {
        if (b.getAttribute('data-target') === 'all') {
            b.classList.remove('btn-outline-secondary');
            b.classList.add('btn-primary', 'active');
        } else {
            b.classList.remove('btn-primary', 'active');
            b.classList.add('btn-outline-secondary');
        }
    });
    filterOfficers();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

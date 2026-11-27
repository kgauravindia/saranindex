<?php
require_once __DIR__ . '/includes/functions.php';

$code = isset($_GET['code']) ? preg_replace('/[^0-9]/', '', $_GET['code']) : (isset($_GET['pin']) ? preg_replace('/[^0-9]/', '', $_GET['pin']) : '841419');
if (strlen($code) !== 6) {
    $code = '841419';
}

$langSwitchUrl = 'pincode.php?code=' . urlencode($code);

// Fetch live PIN code information from API or local cached postal dataset
$pinData = lookupPincodeApi($code);

$district_name = !empty($pinData['district']) ? $pinData['district'] : 'SARAN';
$state_name    = !empty($pinData['state']) ? $pinData['state'] : 'BIHAR';
$sub_districts = !empty($pinData['sub_districts']) ? $pinData['sub_districts'] : [];
$post_offices  = !empty($pinData['offices']) ? $pinData['offices'] : [];
$localities    = !empty($pinData['localities']) ? $pinData['localities'] : [];

// Extract and alphabetically sort all raw localities for standard index matching
$rawLocalities = !empty($pinData['raw_data']) ? $pinData['raw_data'] : [];
usort($rawLocalities, function($a, $b) {
    return strcasecmp($a['locality_name'] ?? '', $b['locality_name'] ?? '');
});

// Fetch local listings matching this PIN code
$listings = getListingsByPincode($code, 30, 0);
$listing_count = count($listings);

$page_title = "पिन कोड " . htmlspecialchars($code) . " डाक निर्देशिका – डाकघर एवं इलाके (Post Offices & Localities) | " . htmlspecialchars(ucwords(strtolower($district_name))) . ", " . htmlspecialchars(ucwords(strtolower($state_name)));
$meta_description = "पिन कोड " . htmlspecialchars($code) . " (जिला " . htmlspecialchars($district_name) . ", " . htmlspecialchars($state_name) . ") के अंतर्गत डाकघर एवं इलाकों (Post Offices & Localities) की संपूर्ण डाक निर्देशिका। डाक शाखाएं, गांव और सत्यापित स्थानीय व्यवसाय।";

require_once __DIR__ . '/includes/header.php';

// Popular Saran Pincodes for fast navigation
$saran_pins = [
    '841419' => 'मिर्जापुर / अमनौर',
    '841301' => 'छपरा सदर',
    '841418' => 'मढ़ौरा',
    '841101' => 'सोनपुर',
    '841305' => 'रिविलगंज',
    '841311' => 'गरखा',
    '841219' => 'परसा',
    '841207' => 'दिघवारा',
    '841401' => 'अमनौर',
    '841403' => 'बनियापुर',
    '841208' => 'एकमा',
    '841424' => 'तरैया',
    '841417' => 'मशरक',
    '841412' => 'जलालपुर',
    '841224' => 'मकेर',
    '841411' => 'इसुआपुर',
    '841442' => 'नगरा',
    '841221' => 'दरियापुर',
    '841415' => 'पानापुर',
    '841214' => 'मांझी',
    '841405' => 'छपरा कचहरी'
];
?>

<!-- Schema.org JSON-LD -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "PostalAddress",
      "postalCode": "<?php echo htmlspecialchars($code); ?>",
      "addressLocality": "<?php echo htmlspecialchars($district_name); ?>",
      "addressRegion": "<?php echo htmlspecialchars($state_name); ?>",
      "addressCountry": "IN"
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "होम",
          "item": "https://saranindex.com/hindi/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "डाक निर्देशिका",
          "item": "https://saranindex.com/hindi/pincode.php"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": <?php echo json_encode("पिन " . $code); ?>,
          "item": <?php echo json_encode(getSiteCanonicalUrl('pincode.php?code=' . $code, true)); ?>
        }
      ]
    }
  ]
}
</script>

<!-- Hero Section -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(135deg, #0b1f3a 0%, #173866 50%, #1e4a86 100%);">
    <div class="container position-relative z-1 py-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>hindi/" class="text-white-50 text-decoration-none"><i class="bi bi-house-door me-1"></i>होम</a></li>
                <li class="breadcrumb-item"><a href="pincode.php" class="text-white-50 text-decoration-none">डाक निर्देशिका</a></li>
                <li class="breadcrumb-item active text-warning fw-semibold" aria-current="page">पिन <?php echo htmlspecialchars($code); ?></li>
            </ol>
        </nav>

        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                    <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-warning text-dark fw-bold small shadow-sm">
                        <i class="bi bi-mailbox2-flag fs-6"></i>
                        <span>आधिकारिक भारतीय पिन कोड निर्देशिका</span>
                    </span>
                    <a href="ifsc.php" class="btn btn-outline-light btn-sm rounded-pill fw-semibold" style="font-size: 0.8rem;">
                        <i class="bi bi-bank2 me-1"></i> बैंक आईएफएससी (IFSC) खोजें
                    </a>
                </div>
                <h1 class="fw-bolder font-heading text-white display-5 mb-2">
                    पिन कोड: <span class="text-warning font-monospace"><?php echo htmlspecialchars($code); ?></span>
                </h1>
                <p class="text-white-50 lead mb-4" style="max-width: 680px;">
                    पिन कोड <strong class="text-white"><?php echo htmlspecialchars($code); ?></strong> के अंतर्गत डाकघर एवं इलाके (Post Offices & Localities), जिला <strong class="text-white"><?php echo htmlspecialchars($district_name); ?></strong>, <strong class="text-white"><?php echo htmlspecialchars($state_name); ?></strong>।
                </p>

                <!-- Search PIN Code Form -->
                <form action="<?php echo BASE_URL; ?>hindi/pincode.php" method="GET" class="d-flex gap-2 max-w-lg" style="max-width: 480px;">
                    <div class="input-group shadow-lg rounded-3 overflow-hidden">
                        <span class="input-group-text bg-white border-0 text-primary px-3">
                            <i class="bi bi-geo-alt-fill fs-5"></i>
                        </span>
                        <input type="text" name="code" class="form-control form-control-lg border-0 font-monospace fw-bold" placeholder="6 अंकों का पिन कोड दर्ज करें..." value="<?php echo htmlspecialchars($code); ?>" maxlength="6" pattern="[0-9]{6}" required>
                        <button class="btn btn-warning fw-bold px-4 text-dark" type="submit">
                            <i class="bi bi-search me-1"></i> खोजें
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 bg-white bg-opacity-10 backdrop-blur rounded-4 p-4 text-white shadow-lg border-top border-white-20">
                    <h6 class="fw-bold text-warning text-uppercase letter-spacing-1 mb-3 small">
                        <i class="bi bi-info-circle-fill me-1.5"></i> डाक विवरण अवलोकन
                    </h6>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2.5 small">
                        <li class="d-flex justify-content-between align-items-center border-bottom border-white-10 pb-2">
                            <span class="text-white-50"><i class="bi bi-building me-1.5"></i> जिला:</span>
                            <strong class="text-white"><?php echo htmlspecialchars($district_name); ?></strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center border-bottom border-white-10 pb-2">
                            <span class="text-white-50"><i class="bi bi-map me-1.5"></i> राज्य:</span>
                            <strong class="text-white"><?php echo htmlspecialchars($state_name); ?></strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center border-bottom border-white-10 pb-2">
                            <span class="text-white-50"><i class="bi bi-postage me-1.5"></i> कुल डाकघर:</span>
                            <span class="badge bg-warning text-dark fw-bold rounded-pill"><?php echo count($post_offices); ?> डाकघर</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center border-bottom border-white-10 pb-2">
                            <span class="text-white-50"><i class="bi bi-signpost-2 me-1.5"></i> इलाके / गांव:</span>
                            <span class="badge bg-light text-dark fw-bold rounded-pill"><?php echo count($rawLocalities); ?> गांव / क्षेत्र</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-white-50"><i class="bi bi-shop me-1.5"></i> डायरेक्टरी लिस्टिंग:</span>
                            <span class="badge bg-success text-white fw-bold rounded-pill"><?php echo $listing_count; ?> सत्यापित</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Saran District Quick Pincodes Nav -->
<section class="py-3 bg-light border-bottom">
    <div class="container">
        <div class="d-flex align-items-center gap-2 overflow-auto py-1 scrollbar-thin">
            <span class="text-dark small fw-bold text-nowrap d-flex align-items-center gap-1 me-1">
                <i class="bi bi-pin-map-fill text-danger"></i> सारण के पिन कोड:
            </span>
            <?php foreach ($saran_pins as $spin => $slabel): ?>
                <a href="<?php echo BASE_URL; ?>hindi/pincode.php?code=<?php echo $spin; ?>" 
                   class="btn btn-sm text-nowrap rounded-pill px-3 py-1 font-monospace <?php echo $code === (string)$spin ? 'btn-primary text-white shadow-sm fw-bold' : 'btn-white bg-white text-secondary border shadow-xs'; ?>">
                    <?php echo $spin; ?> <small class="text-muted fw-normal font-sans">(<?php echo $slabel; ?>)</small>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Main Postal Details & Local Directory -->
<div class="container py-5">
    
    <!-- Top Summary: Post Offices & Sub-districts -->
    <?php if (!empty($pinData['success'])): ?>
    <div class="row g-4 mb-4">
        
        <!-- Post Offices Card -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-envelope-paper-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold font-heading text-dark mb-0">पिन कोड <?php echo htmlspecialchars($code); ?> के अंतर्गत डाकघर</h5>
                            <small class="text-muted"><?php echo count($post_offices); ?> डाक शाखाएं उपलब्ध</small>
                        </div>
                    </div>
                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-1">भारतीय डाक</span>
                </div>

                <div class="d-flex flex-wrap gap-2 max-h-350 overflow-y-auto pr-1">
                    <?php if (!empty($post_offices)): ?>
                        <?php foreach ($post_offices as $po): ?>
                            <div class="p-2.5 rounded-3 bg-light border border-secondary-subtle d-flex align-items-center gap-2 flex-grow-1" style="min-width: calc(50% - 8px);">
                                <i class="bi bi-building-check text-primary fs-5"></i>
                                <div>
                                    <div class="fw-bold text-dark small mb-0"><?php echo htmlspecialchars($po); ?></div>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        <?php 
                                            if (stripos($po, 'H.O') !== false || stripos($po, 'HO') !== false) echo 'प्रधान डाकघर (H.O)';
                                            elseif (stripos($po, 'S.O') !== false || stripos($po, 'SO') !== false) echo 'उप डाकघर (S.O)';
                                            else echo 'शाखा डाकघर (B.O)';
                                        ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-muted small py-3">डाकघर की जानकारी उपलब्ध नहीं है।</div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($sub_districts)): ?>
                <div class="mt-3 pt-3 border-top">
                    <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-diagram-3-fill text-primary me-1.5"></i>प्रखंड / तहसील (Sub-Districts):</h6>
                    <div class="d-flex flex-wrap gap-1.5">
                        <?php foreach ($sub_districts as $sd): ?>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis border px-2.5 py-1 rounded-pill small"><?php echo htmlspecialchars($sd); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Summary & Actions Card -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-3 bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold font-heading text-dark mb-0">डाक वितरण नेटवर्क</h5>
                                <small class="text-muted"><?php echo count($rawLocalities); ?> इलाके शामिल</small>
                            </div>
                        </div>
                        <span class="badge bg-success text-white rounded-pill px-2.5 py-1">सक्रिय नेटवर्क</span>
                    </div>

                    <p class="text-muted small mb-3">
                        भारतीय पिन कोड <strong><?php echo htmlspecialchars($code); ?></strong> के अंतर्गत जिला <strong><?php echo htmlspecialchars($district_name); ?></strong> (<?php echo htmlspecialchars($state_name); ?>) के सभी आधिकारिक गांव, वार्ड एवं वितरण इलाके।
                    </p>

                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="row g-2 text-center">
                            <div class="col-6 border-end">
                                <div class="text-muted extra-small text-uppercase fw-bold">कुल डाकघर</div>
                                <div class="fs-4 fw-bolder text-primary font-monospace"><?php echo count($post_offices); ?></div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted extra-small text-uppercase fw-bold">कुल इलाके / गांव</div>
                                <div class="fs-4 fw-bolder text-success font-monospace"><?php echo count($rawLocalities); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="<?php echo BASE_URL; ?>hindi/add-contact.php" class="btn btn-outline-primary btn-sm rounded-pill w-100 py-2 fw-semibold">
                        <i class="bi bi-plus-circle me-1.5"></i> पिन कोड <?php echo htmlspecialchars($code); ?> में व्यवसाय जोड़ें
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- PRIMARY INTERACTIVE POST OFFICES & LOCALITIES TABLE SECTION -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-5">
        
        <!-- Header & Title -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-3 border-bottom gap-3">
            <div>
                <div class="d-inline-flex align-items-center gap-1.5 text-primary fw-bold small text-uppercase letter-spacing-1 mb-1">
                    <i class="bi bi-table text-danger"></i> संपूर्ण डाक निर्देशिका तालिका
                </div>
                <h3 class="fw-bold font-heading text-dark mb-1 fs-4">
                    Post Offices & Localities under PIN Code <span class="text-primary font-monospace"><?php echo htmlspecialchars($code); ?></span>
                </h3>
                <p class="text-muted small mb-0">
                    पिन कोड <?php echo htmlspecialchars($code); ?> के अंतर्गत सभी <?php echo count($rawLocalities); ?> डाकघरों, इलाकों एवं गांवों की संपूर्ण सूची।
                </p>
            </div>
            <div>
                <span class="badge bg-light text-dark border rounded-pill px-3 py-1.5 fw-semibold small">
                    <i class="bi bi-patch-check-fill text-primary me-1"></i> आधिकारिक डाक डेटा
                </span>
            </div>
        </div>

        <!-- Controls: Show Entries & Live Search Filter -->
        <div class="row align-items-center g-3 mb-3">
            <div class="col-sm-6 col-md-4">
                <div class="d-flex align-items-center gap-2">
                    <label class="text-secondary small fw-semibold text-nowrap mb-0" for="tableEntriesSelect">Show</label>
                    <select id="tableEntriesSelect" class="form-select form-select-sm border-secondary-subtle rounded-3 py-1 px-2.5 fw-bold" style="width: auto; min-width: 85px;" onchange="changePageSize(this.value)">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="-1">All</option>
                    </select>
                    <label class="text-secondary small fw-semibold text-nowrap mb-0" for="tableEntriesSelect">entries</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-8 d-flex justify-content-sm-end">
                <div class="d-flex align-items-center gap-2 w-100 justify-content-sm-end" style="max-width: 340px;">
                    <label class="text-secondary small fw-semibold text-nowrap mb-0" for="localitySearchInput">
                        <i class="bi bi-search text-primary me-1"></i>Search:
                    </label>
                    <div class="input-group input-group-sm">
                        <input type="text" id="localitySearchInput" class="form-control form-control-sm border-secondary-subtle py-1.5 px-3" placeholder="Filter localities (e.g. mani)..." oninput="filterTable()">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn" onclick="clearSearch()" title="फ़िल्टर हटाएं">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsive Data Table -->
        <div class="table-responsive rounded-3 border">
            <table class="table table-hover table-striped align-middle mb-0" id="localityDataTable">
                <thead class="table-dark text-white text-uppercase" style="font-size: 0.78rem; letter-spacing: 0.05em;">
                    <tr>
                        <th class="py-3 px-3 text-center" style="width: 60px;">#</th>
                        <th class="py-3 px-3">Locality / Village</th>
                        <th class="py-3 px-3">Post Office Name</th>
                        <th class="py-3 px-3">Sub District</th>
                        <th class="py-3 px-3">District</th>
                        <th class="py-3 px-3">State / UT</th>
                        <th class="py-3 px-3 text-center" style="width: 110px;">PIN Code</th>
                    </tr>
                </thead>
                <tbody id="localityTableBody">
                    <!-- Rows populated dynamically by JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Table Footer: Info Text & Pagination Controls -->
        <div class="row align-items-center g-3 mt-3 pt-2">
            <div class="col-md-6 text-center text-md-start">
                <div id="tableInfoText" class="small text-muted fw-semibold">
                    Showing 1 to <?php echo min(25, count($rawLocalities)); ?> of <?php echo count($rawLocalities); ?> entries
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                <nav aria-label="Locality table pagination">
                    <ul class="pagination pagination-sm mb-0 gap-1" id="paginationControls">
                        <!-- Populated by JavaScript -->
                    </ul>
                </nav>
            </div>
        </div>

    </div>
    <?php endif; ?>

    <!-- Directory Listings in PIN Code -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-inline-flex align-items-center gap-1.5 text-primary fw-bold small text-uppercase letter-spacing-1 mb-1">
                <i class="bi bi-check2-circle"></i> स्थानीय डायरेक्टरी
            </div>
            <h2 class="fw-bold font-heading text-dark display-6 mb-1">
                पिन कोड <span class="text-primary font-monospace"><?php echo htmlspecialchars($code); ?></span> में सत्यापित व्यवसाय एवं सेवाएं
            </h2>
            <p class="text-muted small mb-0">
                इस पिन कोड क्षेत्र में स्थित डॉक्टर, आपातकालीन संपर्क, दुकानें, स्कूल एवं पेशेवर सेवाएं।
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>hindi/add-contact.php" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                <i class="bi bi-plus-lg me-1.5"></i> अपनी लिस्टिंग जोड़ें
            </a>
        </div>
    </div>

    <?php if (!empty($listings)): ?>
        <div class="row g-4">
            <?php foreach ($listings as $item): ?>
                <div class="col-lg-6">
                    <?php echo renderListingCard($item, ['lang' => 'hi', 'pincode' => $code]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm rounded-4 text-center p-5 bg-white">
            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 72px; height: 72px;">
                <i class="bi bi-geo-alt text-muted fs-1"></i>
            </div>
            <h4 class="fw-bold font-heading text-dark mb-2">पिन कोड <?php echo htmlspecialchars($code); ?> में अभी कोई लिस्टिंग नहीं है</h4>
            <p class="text-muted mx-auto mb-4" style="max-width: 500px;">
                पिन कोड <?php echo htmlspecialchars($code); ?> में अपनी दुकान, क्लीनिक या सेवा को पंजीकृत करने वाले पहले उद्यमी बनें!
            </p>
            <div>
                <a href="<?php echo BASE_URL; ?>hindi/add-contact.php" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm fw-bold">
                    <i class="bi bi-plus-circle me-2"></i> अभी मुफ्त लिस्टिंग जोड़ें
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- Interactive Client-side Script for Localities Table -->
<script>
const allLocalities = <?php echo json_encode(array_values(array_map(function($idx, $r) {
    return [
        'index' => $idx + 1,
        'locality_name' => $r['locality_name'] ?? '',
        'office_name' => $r['office_name'] ?? '',
        'sub_district_name' => $r['sub_district_name'] ?? '',
        'district_name' => $r['district_name'] ?? '',
        'state_name' => $r['state_name'] ?? '',
        'pincode' => $r['pincode'] ?? ''
    ];
}, array_keys($rawLocalities), $rawLocalities)), JSON_UNESCAPED_UNICODE); ?>;

let currentPage = 1;
let pageSize = 25;
let filteredData = [...allLocalities];

function escapeHtml(str) {
    return String(str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function renderTable() {
    const tbody = document.getElementById('localityTableBody');
    const tableInfo = document.getElementById('tableInfoText');
    if (!tbody || !tableInfo) return;

    const total = filteredData.length;
    const originalTotal = allLocalities.length;

    if (total === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-search fs-3 d-block mb-2 text-secondary opacity-50"></i>कोई मेल खाता इलाका या डाकघर नहीं मिला (No matching localities found).</td></tr>`;
        tableInfo.innerText = `Showing 0 to 0 of 0 entries (filtered from ${originalTotal} total entries)`;
        renderPagination(0);
        return;
    }

    const effectivePageSize = pageSize === -1 ? total : pageSize;
    const totalPages = Math.ceil(total / effectivePageSize);
    if (currentPage > totalPages) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;

    const startIndex = (currentPage - 1) * effectivePageSize;
    const endIndex = Math.min(startIndex + effectivePageSize, total);
    const pageData = filteredData.slice(startIndex, endIndex);

    let rowsHtml = '';
    pageData.forEach(row => {
        let poClass = 'bg-primary-subtle text-primary border border-primary-subtle';
        if (row.office_name.includes('S.O') || row.office_name.includes('SO')) {
            poClass = 'bg-info-subtle text-info-emphasis border border-info-subtle';
        } else if (row.office_name.includes('H.O') || row.office_name.includes('HO')) {
            poClass = 'bg-success-subtle text-success border border-success-subtle';
        }

        rowsHtml += `<tr>
            <td class="text-center fw-bold font-monospace text-secondary">${row.index}</td>
            <td>
                <div class="fw-bold text-dark">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>${escapeHtml(row.locality_name)}
                </div>
            </td>
            <td>
                <span class="badge ${poClass} rounded-pill px-2.5 py-1 fw-semibold">
                    ${escapeHtml(row.office_name)}
                </span>
            </td>
            <td>
                <span class="badge bg-light text-dark border rounded-pill px-2 py-1">
                    ${escapeHtml(row.sub_district_name)}
                </span>
            </td>
            <td class="fw-semibold text-secondary">${escapeHtml(row.district_name)}</td>
            <td class="text-muted small">${escapeHtml(row.state_name)}</td>
            <td class="text-center">
                <span class="badge bg-dark font-monospace text-warning px-2.5 py-1 rounded-pill">
                    ${escapeHtml(row.pincode)}
                </span>
            </td>
        </tr>`;
    });

    tbody.innerHTML = rowsHtml;

    // Update Info Text
    const searchInputVal = document.getElementById('localitySearchInput').value.trim();
    if (searchInputVal) {
        tableInfo.innerText = `Showing ${startIndex + 1} to ${endIndex} of ${total} entries (filtered from ${originalTotal} total entries)`;
    } else {
        tableInfo.innerText = `Showing ${startIndex + 1} to ${endIndex} of ${total} entries`;
    }

    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const paginationUl = document.getElementById('paginationControls');
    if (!paginationUl) return;

    if (totalPages <= 1) {
        paginationUl.innerHTML = '';
        return;
    }

    let html = '';
    // Previous
    html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
        <button class="page-link rounded-2" onclick="goToPage(${currentPage - 1})" aria-label="Previous">&laquo;</button>
    </li>`;

    let startP = Math.max(1, currentPage - 2);
    let endP = Math.min(totalPages, currentPage + 2);

    if (startP > 1) {
        html += `<li class="page-item"><button class="page-link rounded-2" onclick="goToPage(1)">1</button></li>`;
        if (startP > 2) {
            html += `<li class="page-item disabled"><span class="page-link border-0">...</span></li>`;
        }
    }

    for (let p = startP; p <= endP; p++) {
        html += `<li class="page-item ${p === currentPage ? 'active' : ''}">
            <button class="page-link rounded-2 fw-semibold" onclick="goToPage(${p})">${p}</button>
        </li>`;
    }

    if (endP < totalPages) {
        if (endP < totalPages - 1) {
            html += `<li class="page-item disabled"><span class="page-link border-0">...</span></li>`;
        }
        html += `<li class="page-item"><button class="page-link rounded-2" onclick="goToPage(${totalPages})">${totalPages}</button></li>`;
    }

    // Next
    html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
        <button class="page-link rounded-2" onclick="goToPage(${currentPage + 1})" aria-label="Next">&raquo;</button>
    </li>`;

    paginationUl.innerHTML = html;
}

function goToPage(p) {
    currentPage = p;
    renderTable();
    const tableCard = document.getElementById('localityDataTable');
    if (tableCard) {
        tableCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

function changePageSize(val) {
    pageSize = parseInt(val, 10);
    currentPage = 1;
    renderTable();
}

function filterTable() {
    const q = document.getElementById('localitySearchInput').value.toLowerCase().trim();
    if (!q) {
        filteredData = [...allLocalities];
    } else {
        filteredData = allLocalities.filter(row => {
            return (
                row.locality_name.toLowerCase().includes(q) ||
                row.office_name.toLowerCase().includes(q) ||
                row.sub_district_name.toLowerCase().includes(q) ||
                row.district_name.toLowerCase().includes(q) ||
                row.state_name.toLowerCase().includes(q) ||
                row.pincode.toLowerCase().includes(q) ||
                String(row.index).includes(q)
            );
        });
    }
    currentPage = 1;
    renderTable();
}

function clearSearch() {
    document.getElementById('localitySearchInput').value = '';
    filterTable();
}

document.addEventListener('DOMContentLoaded', () => {
    renderTable();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

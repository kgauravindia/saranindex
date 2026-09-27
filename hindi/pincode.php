<?php
require_once __DIR__ . '/includes/functions.php';

$code = isset($_GET['code']) ? preg_replace('/[^0-9]/', '', $_GET['code']) : '841301';
if (strlen($code) !== 6) {
    $code = '841301';
}

// Fetch live PIN code information from API
$pinData = lookupPincodeApi($code);

$district_name = !empty($pinData['district']) ? $pinData['district'] : 'SARAN';
$state_name    = !empty($pinData['state']) ? $pinData['state'] : 'BIHAR';
$sub_districts = !empty($pinData['sub_districts']) ? $pinData['sub_districts'] : [];
$post_offices  = !empty($pinData['offices']) ? $pinData['offices'] : [];
$localities    = !empty($pinData['localities']) ? $pinData['localities'] : [];

// Fetch local listings matching this PIN code
$listings = getListingsByPincode($code, 30, 0);
$listing_count = count($listings);

$page_title = "पिन कोड " . htmlspecialchars($code) . " डाक निर्देशिका – " . htmlspecialchars($district_name) . ", " . htmlspecialchars($state_name);
$meta_description = "पिन कोड " . htmlspecialchars($code) . " (जिला " . htmlspecialchars($district_name) . ", " . htmlspecialchars($state_name) . ") की संपूर्ण डाक जानकारी। डाकघर, गांव, दुकानें, डॉक्टर, स्कूल एवं आपातकालीन संपर्क।";

require_once __DIR__ . '/includes/header.php';

// Popular Saran Pincodes
$saran_pins = [
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
  "@type": "PostalAddress",
  "postalCode": "<?php echo htmlspecialchars($code); ?>",
  "addressLocality": "<?php echo htmlspecialchars($district_name); ?>",
  "addressRegion": "<?php echo htmlspecialchars($state_name); ?>",
  "addressCountry": "IN"
}
</script>

<!-- Hero Section -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(135deg, #0b1f3a 0%, #173866 50%, #1e4a86 100%);">
    <div class="container position-relative z-1 py-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>hindi/" class="text-white-50 text-decoration-none"><i class="bi bi-house-door me-1"></i>होम</a></li>
                <li class="breadcrumb-item text-white-50">डाक निर्देशिका</li>
                <li class="breadcrumb-item active text-warning fw-semibold" aria-current="page">पिन <?php echo htmlspecialchars($code); ?></li>
            </ol>
        </nav>

        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-warning text-dark fw-bold small mb-3 shadow-sm">
                    <i class="bi bi-mailbox2-flag fs-6"></i>
                    <span>आधिकारिक भारतीय पिन कोड निर्देशिका</span>
                </div>
                <h1 class="fw-bolder font-heading text-white display-5 mb-2">
                    पिन कोड: <span class="text-warning font-monospace"><?php echo htmlspecialchars($code); ?></span>
                </h1>
                <p class="text-white-50 lead mb-4" style="max-width: 650px;">
                    जिला <strong class="text-white"><?php echo htmlspecialchars($district_name); ?></strong>, <strong class="text-white"><?php echo htmlspecialchars($state_name); ?></strong> की संपूर्ण डाक जानकारी। डाकघर, गांव, इलाके एवं सत्यापित स्थानीय व्यवसाय।
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
                        <i class="bi bi-info-circle-fill me-1.5"></i> डाक विवरण
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
                            <span class="badge bg-light text-dark fw-bold rounded-pill"><?php echo count($localities); ?> क्षेत्र</span>
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
    
    <!-- Postal Data Summary -->
    <?php if (!empty($pinData['success'])): ?>
    <div class="row g-4 mb-5">
        
        <!-- Post Offices Card -->
        <div class="col-lg-6">
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
                                            if (stripos($po, 'H.O') !== false) echo 'प्रधान डाकघर (H.O)';
                                            elseif (stripos($po, 'S.O') !== false) echo 'उप डाकघर (S.O)';
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
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-diagram-3-fill text-primary me-1.5"></i>प्रखंड / अनुमंडल:</h6>
                    <div class="d-flex flex-wrap gap-1.5">
                        <?php foreach ($sub_districts as $sd): ?>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis border px-2.5 py-1 rounded-pill small"><?php echo htmlspecialchars($sd); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Localities / Coverage Areas -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-houses-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold font-heading text-dark mb-0">शामिल इलाके एवं गांव</h5>
                            <small class="text-muted"><?php echo count($localities); ?> वितरण क्षेत्र</small>
                        </div>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">वितरण क्षेत्र</span>
                </div>

                <div class="d-flex flex-wrap gap-1.5 overflow-y-auto" style="max-height: 280px;">
                    <?php if (!empty($localities)): ?>
                        <?php foreach ($localities as $loc): ?>
                            <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-3 fw-normal small d-inline-flex align-items-center gap-1">
                                <i class="bi bi-geo-alt text-danger small"></i> <?php echo htmlspecialchars($loc); ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-muted small py-3"><?php echo htmlspecialchars($district_name); ?> के अंतर्गत क्षेत्र।</div>
                    <?php endif; ?>
                </div>

                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <small class="text-muted">
                        <i class="bi bi-shield-check text-success me-1"></i> भारतीय डाक सेवा नेटवर्क से समन्वयित
                    </small>
                    <a href="<?php echo BASE_URL; ?>hindi/add-contact.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="bi bi-plus-circle me-1"></i> पिन कोड <?php echo htmlspecialchars($code); ?> में जोड़ें
                    </a>
                </div>
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>

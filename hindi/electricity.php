<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "सारण विद्युत विभाग एवं बिजली बोर्ड डायरेक्टरी | NBPDCL छपरा बिजली कार्यालय, अधिकारी एवं 24x7 हेल्पलाइन 1912";
$meta_description = "सारण जिले (छपरा) में नॉर्थ बिहार पावर डिस्ट्रीब्यूशन कंपनी लिमिटेड (NBPDCL) के विद्युत आपूर्ति अंचल, प्रमंडल (छपरा पूर्व व पश्चिम), अवर प्रमंडल (ESSD) एवं सभी 20 प्रखंडों की कनीय अभियंता (JEE) प्रशाखाओं की सत्यापित डायरेक्टरी, 24x7 हेल्पलाइन 1912 एवं ऑनलाइन बिल भुगतान।";
$meta_keywords = "सारण बिजली कार्यालय, एनबीपीडीसीएल छपरा, बिजली बोर्ड छपरा, विद्युत शिकायत 1912, कनीय अभियंता बिजली सारण, सोनपुर बिजली ऑफिस, मढ़ौरा विद्युत, दिघवारा बिजली, ऑनलाइन बिल भुगतान, सुविधा ऐप बिहार";

// Fetch official NBPDCL listings from DB
$db = getDB();
$nbpdclListings = [];
$blockCounts = [];

if ($db) {
    try {
        $stmt = $db->query("
            SELECT l.id, l.title, l.hindi_title, l.slug, l.contact_person, l.mobile, l.mobile_visibility,
                   l.address, l.pincode, l.business_hours, l.services, l.description, l.source,
                   l.block_id, b.name as block_name, b.hindi_name as block_hindi, b.slug as block_slug
            FROM listings l
            LEFT JOIN blocks b ON l.block_id = b.id
            WHERE l.subcategory_id = 272
            ORDER BY l.id ASC
        ");
        $nbpdclListings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($nbpdclListings as $item) {
            $bName = $item['block_hindi'] ?: ($item['block_name'] ?? 'सारण जिला');
            $blockCounts[$bName] = ($blockCounts[$bName] ?? 0) + 1;
        }
    } catch (PDOException $e) {
        error_log("Error fetching NBPDCL listings in Hindi: " . $e->getMessage());
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- कस्टम सीएसएस स्टाइल -->
<style>
.electricity-hero-bg {
    background: linear-gradient(135deg, #091a30 0%, #0f3460 50%, #164e87 100%);
    color: #ffffff;
    position: relative;
    overflow: hidden;
}
.electricity-hero-bg::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(234, 179, 8, 0.15) 0%, rgba(2, 132, 199, 0.08) 50%, transparent 70%);
    pointer-events: none;
}
.office-card {
    transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    background: #ffffff;
}
.office-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 30px -10px rgba(15, 52, 96, 0.15) !important;
    border-color: #38bdf8;
}
.quick-pill {
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
.quick-pill:hover, .quick-pill.active {
    background: #0f3460;
    color: #ffffff !important;
    border-color: #0f3460;
    box-shadow: 0 4px 12px rgba(15, 52, 96, 0.25);
}
.badge-circle { background: #fee2e2; color: #991b1b; }
.badge-division { background: #dbeafe; color: #1e40af; }
.badge-subdiv { background: #fef3c7; color: #92400e; }
.badge-section { background: #dcfce7; color: #166534; }
.badge-tech { background: #f3e8ff; color: #6b21a8; }
.guide-step-number {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #0f3460;
    color: #ffffff;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
</style>

<!-- हीरो हेडर सेक्शन -->
<div class="electricity-hero-bg py-5 position-relative">
    <div class="container position-relative z-1 py-3 text-center">
        <!-- ब्रेडक्रम्ब -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="hindi/" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill"></i> मुख्य पृष्ठ</a></li>
                <li class="breadcrumb-item text-white-50">सरकारी सेवाएं</li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">विद्युत विभाग (NBPDCL)</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill fs-7 shadow-sm">
                <i class="bi bi-lightning-charge-fill me-1"></i> नॉर्थ बिहार पावर डिस्ट्रीब्यूशन कंपनी लिमिटेड (NBPDCL)
            </span>
            <span class="badge px-3 py-1.5 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3);">
                <i class="bi bi-geo-alt-fill me-1"></i> सारण जिला (सभी 20 प्रखंड)
            </span>
            <span class="badge bg-info-subtle text-info-emphasis px-3 py-1.5 rounded-pill fs-7 fw-semibold">
                <i class="bi bi-shield-check me-1"></i> आधिकारिक डायरेक्टरी
            </span>
        </div>

        <h1 class="fw-bolder font-heading text-white display-5 mb-2">
            सारण विद्युत विभाग एवं बिजली बोर्ड डायरेक्टरी
        </h1>
        <div class="lead text-warning fw-semibold mb-3">
            विद्युत आपूर्ति अंचल, प्रमंडल (छपरा पूर्व व पश्चिम), अवर प्रमंडल (ESSD) एवं प्रशाखा (कनीय अभियंता) संपूर्ण सूची
        </div>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 820px; font-size: 1rem; line-height: 1.6;">
            सारण जिले के समस्त उपभोक्ताओं के लिए विद्युत आपूर्ति अंचल, प्रमंडल, अवर प्रमंडल, प्रशाखा (JEE) कार्यालयों, 24x7 आपातकालीन ब्रेकडाउन हेल्पलाइन <strong>1912</strong>, जले ट्रांसफार्मर प्रतिस्थापन नियमावली एवं ऑनलाइन बिल भुगतान की संपूर्ण व सत्यापित जानकारी।
        </p>

        <!-- त्वरित बटन -->
        <div class="d-flex justify-content-center gap-2.5 flex-wrap">
            <a href="tel:1912" class="btn btn-warning text-dark fw-bold px-4 py-2.5 rounded-pill shadow">
                <i class="bi bi-telephone-outbound-fill me-1"></i> 24x7 हेल्पलाइन: 1912
            </a>
            <a href="https://nbpdcl.co.in/" target="_blank" rel="noopener" class="btn btn-light text-primary fw-bold px-4 py-2.5 rounded-pill shadow">
                <i class="bi bi-credit-card-2-front-fill me-1"></i> ऑनलाइन बिजली बिल भुगतान (nbpdcl.co.in) <i class="bi bi-box-arrow-up-right ms-1"></i>
            </a>
            <a href="https://play.google.com/store/apps/details?id=com.csmart.biharbijli" target="_blank" rel="noopener" class="btn btn-outline-light fw-bold px-4 py-2.5 rounded-pill">
                <i class="bi bi-phone-fill me-1"></i> बिहार बिजली सुविधा ऐप <i class="bi bi-box-arrow-up-right ms-1"></i>
            </a>
            <a href="hindi/electricity.php#directory" class="btn btn-primary fw-bold px-4 py-2.5 rounded-pill">
                <i class="bi bi-building me-1"></i> कुल <?php echo count($nbpdclListings); ?> कार्यालय देखें
            </a>
        </div>
    </div>
</div>

<div class="container py-5">

    <!-- प्रमुख सांख्यिकी कार्ड्स -->
    <div class="row g-3 mb-5">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-warning">
                <div class="text-warning fs-2 mb-1"><i class="bi bi-headset"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">1912</div>
                <div class="text-muted small fw-semibold">टोल-फ्री हेल्पलाइन</div>
                <div class="badge bg-warning-subtle text-dark rounded-pill mt-2 px-2.5 py-1 small fw-bold">24x7 बिजली फॉल्ट शिकायत</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-primary">
                <div class="text-primary fs-2 mb-1"><i class="bi bi-diagram-3-fill"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">2 प्रमंडल</div>
                <div class="text-muted small fw-semibold">छपरा पूर्व एवं छपरा पश्चिम</div>
                <div class="badge bg-primary-subtle text-primary rounded-pill mt-2 px-2.5 py-1 small fw-bold">8 अवर प्रमंडल (ESSD)</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-success">
                <div class="text-success fs-2 mb-1"><i class="bi bi-geo-alt-fill"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">20 प्रखंड</div>
                <div class="text-muted small fw-semibold">100% जिला कवरेज</div>
                <div class="badge bg-success-subtle text-success rounded-pill mt-2 px-2.5 py-1 small fw-bold">30+ कनीय अभियंता प्रशाखाएं</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-info">
                <div class="text-info fs-2 mb-1"><i class="bi bi-shield-check"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">59 इकाइयां</div>
                <div class="text-muted small fw-semibold">प्रशासनिक व तकनीकी प्रभाग</div>
                <div class="badge bg-info-subtle text-info-emphasis rounded-pill mt-2 px-2.5 py-1 small fw-bold">CGRF, MRT, TRW, भंडार</div>
            </div>
        </div>
    </div>

    <!-- आपातकालीन हेल्पलाइन बैनर -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5 text-white" style="background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-white text-danger fw-bold rounded-pill px-2.5 py-1 fs-7">अत्यावश्यक आपातकालीन सेवा</span>
                    <span class="text-white-50 small">बिजली गुल • तार टूटना • ट्रांसफार्मर स्पार्क व दुर्घटना</span>
                </div>
                <h4 class="fw-bold mb-1 text-white">सारण में बिजली आपूर्ति बाधित या खतरा होने पर तुरंत संपर्क करें</h4>
                <p class="text-white-50 mb-0 small">
                    सारण जिले के किसी भी हिस्से से बिजली गुल होने, तार टूटने, ट्रांसफार्मर जलने या स्पार्किंग की शिकायत हेतु टोल-फ्री नंबर <strong>1912</strong> पर 24 घंटे कॉल करें। आपको तुरंत शिकायत संख्या (SMS) प्राप्त होगी।
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="tel:1912" class="btn btn-light btn-lg text-danger fw-bolder px-4 py-2.5 rounded-pill shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-telephone-fill fs-5"></i> 1912 पर कॉल करें
                </a>
            </div>
        </div>
    </div>

    <!-- डायरेक्टरी सेक्शन -->
    <div id="directory" class="mb-4 pt-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <div>
                <h2 class="fw-bold font-heading text-dark mb-1">
                    <i class="bi bi-building-fill-gear text-primary me-2"></i>एनबीपीडीसीएल (NBPDCL) कार्यालय एवं अधिकारी निर्देशिका
                </h2>
                <p class="text-muted small mb-0">
                    प्रमंडल, अवर प्रमंडल या अपने प्रखंड के अनुसार कनीय अभियंता (JEE) एवं सहायक अभियंता (AEE) खोजें।
                </p>
            </div>

            <!-- खोज बॉक्स -->
            <div class="position-relative" style="min-width: 280px; max-width: 400px;">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" id="officeSearch" class="form-control rounded-pill ps-5 pe-3 py-2 shadow-sm" placeholder="कार्यालय, पद या प्रखंड खोजें...">
            </div>
        </div>

        <!-- फ़िल्टर पिल्स -->
        <div class="d-flex flex-wrap gap-2 mb-4" id="filterPills">
            <button class="quick-pill active" data-filter="all">
                <i class="bi bi-grid-fill"></i> सभी कार्यालय (<?php echo count($nbpdclListings); ?>)
            </button>
            <button class="quick-pill" data-filter="circle">
                <i class="bi bi-star-fill text-warning"></i> अंचल व मंच (5)
            </button>
            <button class="quick-pill" data-filter="tech">
                <i class="bi bi-cpu-fill text-info"></i> तकनीकी प्रभाग (11)
            </button>
            <button class="quick-pill" data-filter="east">
                <i class="bi bi-arrow-right-circle text-primary"></i> छपरा पूर्व प्रमंडल (20)
            </button>
            <button class="quick-pill" data-filter="west">
                <i class="bi bi-arrow-left-circle text-success"></i> छपरा पश्चिम प्रमंडल (23)
            </button>
            <button class="quick-pill" data-filter="subdiv">
                <i class="bi bi-diagram-2 text-danger"></i> अवर प्रमंडल AEE (8)
            </button>
            <button class="quick-pill" data-filter="section">
                <i class="bi bi-geo-alt text-dark"></i> प्रशाखाएं JEE (30)
            </button>
        </div>

        <!-- प्रखंड चयन ड्रॉपडाउन -->
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-light">
            <div class="row align-items-center g-2">
                <div class="col-md-4">
                    <label class="fw-bold small text-dark mb-0 d-flex align-items-center gap-1.5">
                        <i class="bi bi-funnel-fill text-primary"></i> प्रखंड अनुसार छांटें:
                    </label>
                </div>
                <div class="col-md-8">
                    <select id="blockFilter" class="form-select rounded-pill shadow-sm small">
                        <option value="all">-- सारण के सभी 20 प्रखंड एवं अंचल कार्यालय --</option>
                        <?php foreach ($blockCounts as $bName => $bCount): ?>
                            <option value="<?php echo htmlspecialchars(strtolower($bName)); ?>"><?php echo htmlspecialchars($bName); ?> (<?php echo $bCount; ?> कार्यालय)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- कार्यालय ग्रिड -->
    <div class="row g-3 mb-5" id="officesGrid">
        <?php 
        $userCanSeeMobile = isUserLoggedIn();
        foreach ($nbpdclListings as $index => $item): 
            $titleSearch = strtolower($item['title'] . ' ' . ($item['hindi_title'] ?? '') . ' ' . $item['contact_person'] . ' ' . ($item['block_name'] ?? '') . ' ' . ($item['block_hindi'] ?? ''));
            
            // Assign filtering tags
            $tags = [];
            $levelBadge = ['text' => 'प्रशाखा (Section)', 'class' => 'badge-section'];
            
            if (stripos($item['title'], 'Circle') !== false || stripos($item['title'], 'CGRF') !== false || stripos($item['title'], 'STF') !== false || stripos($item['title'], 'IT Cell') !== false) {
                $tags[] = 'circle';
                $levelBadge = ['text' => 'अंचल स्तर', 'class' => 'badge-circle'];
            }
            if (stripos($item['title'], 'MRT') !== false || stripos($item['title'], 'TRW') !== false || stripos($item['title'], 'Store') !== false || stripos($item['title'], 'Civil') !== false || stripos($item['title'], 'Project Sub-Division') !== false) {
                $tags[] = 'tech';
                $levelBadge = ['text' => 'तकनीकी इकाई', 'class' => 'badge-tech'];
            }
            if (stripos($item['title'], 'Chapra East') !== false || in_array($item['block_id'], [3, 2, 16, 19, 6, 15, 7, 12, 11, 20, 8])) {
                $tags[] = 'east';
            }
            if (stripos($item['title'], 'Chapra West') !== false || in_array($item['block_id'], [1, 9, 10, 5, 4, 14, 18, 17, 13])) {
                $tags[] = 'west';
            }
            if (stripos($item['title'], 'Sub-Division') !== false) {
                $tags[] = 'subdiv';
                $levelBadge = ['text' => 'अवर प्रमंडल (AEE)', 'class' => 'badge-subdiv'];
            } elseif (stripos($item['title'], 'Section') !== false) {
                $tags[] = 'section';
                $levelBadge = ['text' => 'प्रशाखा (JEE)', 'class' => 'badge-section'];
            } elseif (stripos($item['title'], 'Division') !== false && stripos($item['title'], 'Sub-Division') === false) {
                $levelBadge = ['text' => 'प्रमंडल कार्यालय (EEE)', 'class' => 'badge-division'];
            }

            $tagAttr = implode(' ', array_unique($tags));
            $blockSlug = strtolower($item['block_hindi'] ?: ($item['block_name'] ?? 'छपरा'));
            $maskedMobile = maskPhoneNumber($item['mobile']);
            $profileUrl = getListingUrl($item['slug']);
            $displayTitle = !empty($item['hindi_title']) ? $item['hindi_title'] : $item['title'];
            $displaySub = !empty($item['hindi_title']) ? $item['title'] : '';
        ?>
            <div class="col-md-6 col-lg-4 office-item" data-tags="<?php echo $tagAttr; ?>" data-block="<?php echo $blockSlug; ?>" data-search="<?php echo htmlspecialchars($titleSearch); ?>">
                <div class="card office-card h-100 shadow-sm p-3.5 d-flex flex-column justify-content-between">
                    <div>
                        <!-- हेडर बैज -->
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                            <span class="badge <?php echo $levelBadge['class']; ?> rounded-pill px-2.5 py-1 small fw-bold">
                                <?php echo $levelBadge['text']; ?>
                            </span>
                            <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1 small">
                                <i class="bi bi-geo-alt me-0.5"></i><?php echo htmlspecialchars($item['block_hindi'] ?: ($item['block_name'] ?? 'सारण')); ?>
                            </span>
                        </div>

                        <!-- कार्यालय का नाम -->
                        <h5 class="fw-bold text-dark mb-1 fs-6 lh-base">
                            <a href="<?php echo $profileUrl; ?>" class="text-dark text-decoration-none hover-primary">
                                <?php echo htmlspecialchars($displayTitle); ?>
                            </a>
                        </h5>

                        <?php if (!empty($displaySub)): ?>
                            <div class="text-muted small mb-2 lh-sm">
                                <?php echo htmlspecialchars($displaySub); ?>
                            </div>
                        <?php endif; ?>

                        <!-- अधिकारी पदनाम -->
                        <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded-3 bg-light border border-light-subtle">
                            <i class="bi bi-person-badge-fill text-primary fs-5 flex-shrink-0"></i>
                            <div class="small">
                                <span class="text-muted d-block" style="font-size: 0.72rem;">अधिकारी पदनाम</span>
                                <strong class="text-dark"><?php echo htmlspecialchars($item['contact_person'] ?? 'विद्युत अभियंता'); ?></strong>
                            </div>
                        </div>

                        <!-- पता -->
                        <div class="d-flex align-items-start gap-2 small text-muted mb-3" style="font-size: 0.82rem; line-height: 1.45;">
                            <i class="bi bi-pin-map text-danger flex-shrink-0 mt-0.5"></i>
                            <span><?php echo htmlspecialchars($item['address'] ?? 'सारण जिला, बिहार'); ?></span>
                        </div>
                    </div>

                    <!-- निचला एक्शन एवं मोबाइल नंबर -->
                    <div class="pt-2 border-top">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small text-muted fw-semibold">संपर्क नंबर:</span>
                            <?php if ($userCanSeeMobile): ?>
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25 rounded-pill small">सत्यापित</span>
                            <?php else: ?>
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning border-opacity-50 rounded-pill small">
                                    <i class="bi bi-lock-fill me-0.5"></i>सुरक्षित (मास्क्ड)
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if ($userCanSeeMobile): ?>
                            <a href="tel:<?php echo htmlspecialchars($item['mobile']); ?>" class="btn btn-success btn-sm w-100 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-1.5 mb-2">
                                <i class="bi bi-telephone-fill"></i> कॉल करें <?php echo htmlspecialchars($item['mobile']); ?>
                            </a>
                        <?php else: ?>
                            <a href="../login.php?redirect=<?php echo urlencode('hindi/electricity.php'); ?>" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-1.5 mb-2" title="पूर्ण नंबर देखने हेतु लॉगिन करें">
                                <i class="bi bi-lock-fill text-warning"></i> कॉल करें <?php echo htmlspecialchars($maskedMobile); ?>
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo $profileUrl; ?>" class="btn btn-light btn-sm w-100 rounded-pill text-muted fw-semibold text-center small border">
                            कार्यालय का पूरा विवरण देखें <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- परिणाम न मिलने पर संदेश -->
    <div id="noResults" class="text-center py-5 d-none">
        <i class="bi bi-search text-muted display-4 mb-3 d-block"></i>
        <h4 class="fw-bold text-dark">कोई कार्यालय नहीं मिला</h4>
        <p class="text-muted small">कृपया अन्य नाम या प्रखंड चयन करके खोजें।</p>
        <button class="btn btn-primary rounded-pill px-4 py-2 small fw-bold" onclick="resetFilters()">सभी फ़िल्टर हटाएं</button>
    </div>

    <!-- उपभोक्ता सहायता एवं आवश्यक सेवाएं -->
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white border-start border-5 border-warning">
        <div class="row g-4">
            <div class="col-lg-6">
                <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-2">
                    <i class="bi bi-info-circle-fill me-1"></i> उपभोक्ता सहायता मार्गदर्शिका
                </span>
                <h3 class="fw-bold font-heading text-dark mb-3">
                    उपभोक्ता अधिकार एवं आवश्यक सेवाएं
                </h3>
                <p class="text-muted small mb-4" style="line-height: 1.7;">
                    नॉर्थ बिहार पावर डिस्ट्रीब्यूशन कंपनी लिमिटेड (NBPDCL) सारण जिले के उपभोक्ताओं को आधुनिक डिजिटल सेवाएं प्रदान करता है। जानिए बिजली बिल भुगतान, फॉल्ट शिकायत दर्ज करने और ट्रांसफार्मर बदलने की आधिकारिक प्रक्रिया:
                </p>

                <!-- चरणबद्ध विवरण -->
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex gap-3">
                        <div class="guide-step-number">1</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0.5">24x7 बिजली खराबी शिकायत दर्ज करना</h6>
                            <p class="text-muted small mb-0">टोल-फ्री नंबर <strong>1912</strong> पर कॉल करें या <strong>बिहार बिजली सुविधा ऐप</strong> का उपयोग करें। आपको एसएमएस के माध्यम से शिकायत संख्या प्राप्त होगी।</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="guide-step-number">2</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0.5">ऑनलाइन बिल भुगतान व स्मार्ट रिचार्ज</h6>
                            <p class="text-muted small mb-0">अपने 10 अंकों के सीए नंबर (CA Number) द्वारा <strong>nbpdcl.co.in</strong> पर बिल जमा करें अथवा फोनपे, गूगल पे, पेटीएम के माध्यम से स्मार्ट प्रीपेड मीटर रिचार्ज करें।</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="guide-step-number">3</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0.5">जले ट्रांसफार्मर प्रतिस्थापन समय-सीमा</h6>
                            <p class="text-muted small mb-0">बिहार राज्य विद्युत विनियामक आयोग के नियमों के तहत शहरी क्षेत्रों में <strong>24 घंटे</strong> और ग्रामीण क्षेत्रों में <strong>72 घंटे</strong> के भीतर जला हुआ ट्रांसफार्मर बदलना अनिवार्य है।</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="guide-step-number">4</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0.5">उपभोक्ता शिकायत निवारण मंच (CGRF छपरा)</h6>
                            <p class="text-muted small mb-0">यदि गलत बिलिंग, अधिक टैरिफ या मीटर संबंधी विवाद का सामान्य स्तर पर समाधान न हो, तो छपरा पावर हाउस परिसर स्थित उपभोक्ता शिकायत निवारण मंच (CGRF) में अपील दायर करें।</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-light h-100 border">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-link-45deg text-primary me-1"></i>आधिकारिक सरकारी पोर्टल एवं ऐप
                    </h5>
                    
                    <ul class="list-unstyled mb-4 d-flex flex-column gap-2.5 small">
                        <li class="p-2.5 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="d-block text-dark">NBPDCL आधिकारिक वेब पोर्टल</strong>
                                <span class="text-muted">nbpdcl.co.in (बिल देखें, रसीद, लेजर)</span>
                            </div>
                            <a href="https://nbpdcl.co.in/" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">खोलें <i class="bi bi-box-arrow-up-right ms-0.5"></i></a>
                        </li>

                        <li class="p-2.5 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="d-block text-dark">बिहार बिजली सुविधा मोबाइल ऐप</strong>
                                <span class="text-muted">नया कनेक्शन, मीटर रीडिंग, फॉल्ट शिकायत</span>
                            </div>
                            <a href="https://play.google.com/store/apps/details?id=com.csmart.biharbijli" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">डाउनलोड <i class="bi bi-download ms-0.5"></i></a>
                        </li>

                        <li class="p-2.5 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="d-block text-dark">हर घर बिजली पोर्टल</strong>
                                <span class="text-muted">बिहार सरकार ग्रामीण व शहरी विद्युतीकरण</span>
                            </div>
                            <a href="https://hargharbijli.bihar.gov.in/" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">देखें <i class="bi bi-box-arrow-up-right ms-0.5"></i></a>
                        </li>

                        <li class="p-2.5 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="d-block text-dark">बीएसपीएचसीएल (BSPHCL) होल्डिंग कंपनी</strong>
                                <span class="text-muted">बिहार स्टेट पावर होल्डिंग कंपनी लिमिटेड</span>
                            </div>
                            <a href="https://bsphcl.bih.nic.in/" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">देखें <i class="bi bi-box-arrow-up-right ms-0.5"></i></a>
                        </li>
                    </ul>

                    <div class="p-3 bg-white rounded-3 border border-warning-subtle">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-shield-check text-warning fs-5"></i>
                            <strong class="text-dark small">डेटा संदर्भ एवं प्रमाणिकता</strong>
                        </div>
                        <p class="text-muted small mb-0" style="font-size: 0.78rem;">
                            यह जानकारी <strong>नॉर्थ बिहार पावर डिस्ट्रीब्यूशन कंपनी लिमिटेड (NBPDCL)</strong> के आधिकारिक परिपत्रों एवं संपर्क निर्देशिका से संकलित है। सारण इंडेक्स में डेटा स्रोत #23 के रूप में पंजीकृत।
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- इंटरैक्टिव फ़िल्टर एवं खोज स्क्रिप्ट -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('officeSearch');
    const filterPills = document.querySelectorAll('#filterPills .quick-pill');
    const blockFilter = document.getElementById('blockFilter');
    const items = document.querySelectorAll('.office-item');
    const noResults = document.getElementById('noResults');

    let currentTag = 'all';
    let currentBlock = 'all';
    let searchQuery = '';

    function applyFilter() {
        let visibleCount = 0;

        items.forEach(function(item) {
            const tags = item.getAttribute('data-tags') || '';
            const block = item.getAttribute('data-block') || '';
            const text = item.getAttribute('data-search') || '';

            let matchTag = (currentTag === 'all') || tags.includes(currentTag);
            let matchBlock = (currentBlock === 'all') || (block === currentBlock);
            let matchSearch = (searchQuery === '') || text.includes(searchQuery);

            if (matchTag && matchBlock && matchSearch) {
                item.classList.remove('d-none');
                visibleCount++;
            } else {
                item.classList.add('d-none');
            }
        });

        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }

    // Tag pills click
    filterPills.forEach(function(pill) {
        pill.addEventListener('click', function() {
            filterPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            currentTag = this.getAttribute('data-filter');
            applyFilter();
        });
    });

    // Block dropdown change
    blockFilter.addEventListener('change', function() {
        currentBlock = this.value;
        applyFilter();
    });

    // Search input
    searchInput.addEventListener('input', function() {
        searchQuery = this.value.toLowerCase().trim();
        applyFilter();
    });

    window.resetFilters = function() {
        currentTag = 'all';
        currentBlock = 'all';
        searchQuery = '';
        searchInput.value = '';
        blockFilter.value = 'all';
        filterPills.forEach(p => p.classList.remove('active'));
        if (filterPills[0]) filterPills[0].classList.add('active');
        applyFilter();
    };
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

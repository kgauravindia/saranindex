<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "सारण इंडेक्स – सारण को डिजिटली जोड़ते हुए | सारण जिला निर्देशिका व सांख्यिकी पोर्टल";
$meta_description = "सारण जिले (छपरा, बिहार) की विश्वसनीय एवं सुरक्षित डिजिटल निर्देशिका। सभी 20 प्रखंडों, 318 ग्राम पंचायतों, 1,800+ गांवों में 15,000+ सत्यापित व्यवसायों, डॉक्टरों, वकीलों एवं जनप्रतिनिधियों की जानकारी प्राप्त करें।";

require_once __DIR__ . '/includes/header.php';

// Safe Data Fetching with Exception Safety
$district_stats = getDistrictFullStats() ?: [];
$total_listings = intval($district_stats['total_listings'] ?? 15161);
$total_blocks = intval($district_stats['total_blocks'] ?? 20);
$total_panchayats = intval($district_stats['total_panchayats'] ?? 318);
$total_villages = intval($district_stats['total_villages'] ?? 1876);
$total_reps = intval($district_stats['total_representatives'] ?? 8726);

$blocks = getBlocks();
$featured_listings = getListings('', '', '', 6, 0);
$recent_listings = getRecentListings(6);

// Top 8 Populated Categories for fast, visual discovery
$top_categories = [];
$db = getDB();
if ($db) {
    try {
        $stmt = $db->query("SELECT c.id, c.name, c.hindi_name, c.slug, c.icon, COUNT(l.id) as listing_count 
            FROM categories c 
            INNER JOIN listings l ON l.category_id = c.id AND l.status = 'ACTIVE'
            GROUP BY c.id 
            ORDER BY listing_count DESC 
            LIMIT 8");
        $top_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $top_categories = [];
    }
}
if (empty($top_categories)) {
    $top_categories = array_slice(getCategories(), 0, 8);
}
?>

<!-- Clean, Modern & Secure Hero Section (Hindi) -->
<section class="hero-wrapper position-relative text-center py-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 65%, #2563eb 100%);">
    <div class="container position-relative z-2 py-3 py-md-4">
        <div class="d-inline-flex align-items-center mb-3 hero-tag-pill">
            <i class="bi bi-patch-check-fill text-warning me-2 fs-6"></i>
            <span>सत्यापित सार्वजनिक निर्देशिका • सारण जिला, बिहार</span>
        </div>

        <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
            सारण को डिजिटली जोड़ते हुए
        </h1>
        <p class="lead text-white-50 mx-auto mb-4" style="max-width: 720px; font-size: 1.15rem; color: #cbd5e1 !important; line-height: 1.6;">
            <strong>सारण जिला (छपरा)</strong> का विश्वसनीय डिजिटल निर्देशिका एवं नागरिक पोर्टल। सभी 20 प्रखंडों, 318 ग्राम पंचायतों एवं 1,800+ गांवों में 15,000+ सत्यापित निर्देशिकाएं खोजें।
        </p>

        <!-- Prominent Unified Search Bar -->
        <div class="row justify-content-center mb-3">
            <div class="col-lg-9 col-md-11 position-relative">
                <form action="search.php" method="GET" class="search-card d-flex align-items-center gap-2 shadow-lg mx-auto" style="max-width: 840px;">
                    <button type="button" class="btn mic-btn flex-shrink-0" id="micButton" title="बोलकर खोजें (वॉइस सर्च)" aria-label="बोलकर खोजें">
                        <i class="bi bi-mic-fill fs-5"></i>
                    </button>
                    <input type="text" name="q" id="search_box" class="form-control search-input flex-grow-1" placeholder="दुकानें, डॉक्टर, वकील, स्कूल, सेवाएं या जनप्रतिनिधि खोजें..." maxlength="100" autocomplete="off" required>
                    
                    <select name="block" class="form-select border-0 bg-light rounded-pill px-3 fw-medium d-none d-md-block" style="max-width: 180px;" aria-label="प्रखंड अनुसार फिल्टर">
                        <option value="">सभी 20 प्रखंड</option>
                        <?php foreach ($blocks as $blk): ?>
                            <option value="<?php echo sanitizeInput($blk['slug']); ?>">
                                <?php echo sanitizeInput(!empty($blk['hindi_name']) ? $blk['hindi_name'] : $blk['block_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn search-submit-btn flex-shrink-0" id="searchSubmitBtn">
                        <i class="bi bi-search me-1"></i>खोजें
                    </button>
                </form>
                
                <!-- Live Autocomplete Suggest Container -->
                <div id="autocomplete_results" class="position-absolute start-0 end-0 text-start z-3 px-3 mx-auto" style="max-width: 840px; display: none; top: 100%; margin-top: 6px;"></div>
            </div>
        </div>

        <!-- Popular Quick Search Suggestions -->
        <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 pt-2">
            <span class="text-white-50 small fw-semibold me-1"><i class="bi bi-lightning-charge-fill text-warning me-1"></i>लोकप्रिय:</span>
            <a href="search.php?q=Doctor" class="hero-tag-pill"><i class="bi bi-heart-pulse text-danger me-1"></i> डॉक्टर</a>
            <a href="search.php?q=School" class="hero-tag-pill"><i class="bi bi-mortarboard text-warning me-1"></i> स्कूल एवं कॉलेज</a>
            <a href="panchayat" class="hero-tag-pill"><i class="bi bi-building text-info me-1"></i> 318 पंचायतें</a>
            <a href="search.php?q=Ward+Member" class="hero-tag-pill"><i class="bi bi-people text-success me-1"></i> वार्ड सदस्य एवं पंच</a>
            <a href="emergency" class="hero-tag-pill"><i class="bi bi-shield-exclamation text-danger me-1"></i> आपातकालीन 24x7</a>
            <a href="category/legal" class="hero-tag-pill"><i class="bi bi-briefcase text-primary me-1"></i> वकील एवं कानूनी सेवा</a>
            <a href="bus" class="hero-tag-pill"><i class="bi bi-bus-front text-info me-1"></i> बस समय सारणी</a>
        </div>
    </div>
</section>

<!-- Sleek Master Statistics Strip (Light, Clean & Balanced) -->
<section class="py-4 bg-white border-bottom shadow-xs">
    <div class="container">
        <div class="row g-3 text-center align-items-stretch">
            <div class="col-6 col-md-4 col-lg">
                <div class="p-3 rounded-4 bg-light border h-100 hover-lift">
                    <div class="h3 fw-bold text-primary mb-1 font-heading"><?php echo number_format($total_listings); ?>+</div>
                    <div class="text-secondary small fw-semibold">सत्यापित निर्देशिकाएं</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="p-3 rounded-4 bg-light border h-100 hover-lift">
                    <div class="h3 fw-bold text-info mb-1 font-heading"><?php echo $total_blocks; ?></div>
                    <div class="text-secondary small fw-semibold">सारण के प्रखंड</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="p-3 rounded-4 bg-light border h-100 hover-lift">
                    <div class="h3 fw-bold text-success mb-1 font-heading"><?php echo $total_panchayats; ?></div>
                    <div class="text-secondary small fw-semibold">ग्राम पंचायतें</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <div class="p-3 rounded-4 bg-light border h-100 hover-lift">
                    <div class="h3 fw-bold text-warning mb-1 font-heading"><?php echo number_format($total_villages); ?>+</div>
                    <div class="text-secondary small fw-semibold">राजस्व ग्राम (LGD)</div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg">
                <div class="p-3 rounded-4 bg-light border h-100 hover-lift">
                    <div class="h3 fw-bold text-dark mb-1 font-heading"><?php echo number_format($total_reps); ?>+</div>
                    <div class="text-secondary small fw-semibold">निर्वाचित जनप्रतिनिधि</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Directory Verticals (Clean 8-Card Grid) -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">
                    <i class="bi bi-grid-3x3-gap-fill me-1"></i>मुख्य श्रेणियां
                </span>
                <h2 class="fw-bold font-heading text-dark mt-2 mb-0 fs-2">निर्देशिका सेवाएं खोजें</h2>
                <p class="text-muted small mb-0">सारण जिले में सत्यापित फोन नंबर, पते और स्थानीय सेवाएं खोजें।</p>
            </div>
            <a href="categories" class="btn btn-outline-primary rounded-pill px-4 fw-semibold shadow-sm align-self-start align-self-md-auto">
                सभी श्रेणियां देखें <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3 g-md-4 justify-content-center">
            <?php foreach ($top_categories as $cat): 
                $catName = !empty($cat['hindi_name']) ? $cat['hindi_name'] : $cat['name'];
            ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="<?php echo getCategoryUrl($cat['slug']); ?>" class="category-card hover-lift">
                        <div class="category-icon-wrapper">
                            <i class="bi <?php echo sanitizeInput($cat['icon'] ?: 'bi-folder2-open'); ?>"></i>
                        </div>
                        <div class="category-title text-truncate w-100"><?php echo sanitizeInput($catName); ?></div>
                        <?php if (!empty($cat['name']) && $cat['name'] !== $catName): ?>
                            <div class="category-hindi text-truncate w-100 mb-1"><?php echo sanitizeInput($cat['name']); ?></div>
                        <?php endif; ?>
                        <?php if (isset($cat['listing_count']) && $cat['listing_count'] > 0): ?>
                            <span class="badge bg-light text-primary border rounded-pill mt-2 small fw-semibold px-2.5 py-1">
                                <?php echo number_format($cat['listing_count']); ?> निर्देशिकाएं
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Saran Civic & Local Governance Hub (Grassroots Democracy Spotlight) -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-1.5 rounded-pill small uppercase tracking-wider">
                <i class="bi bi-shield-check me-1"></i>जमीनी लोकतंत्र
            </span>
            <h2 class="fw-bold font-heading text-dark mt-2 fs-2">सारण स्थानीय स्वशासन निर्देशिका</h2>
            <p class="text-muted mx-auto" style="max-width: 680px;">
                राज्य निर्वाचन आयोग बिहार (<a href="https://sec.bihar.gov.in" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none fw-semibold">sec.bihar.gov.in</a>) से सत्यापित जनप्रतिनिधियों, प्रशासनिक कार्यालयों एवं हलकों की संपूर्ण डिजिटल निर्देशिका।
            </p>
        </div>

        <div class="row g-4">
            <!-- 1. Gram Panchayats -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-light hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle bg-primary-subtle text-primary p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                            <i class="bi bi-building fs-4"></i>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1 fs-5">318 ग्राम पंचायतें</h4>
                        <p class="text-secondary small mb-3">सभी 20 प्रखंडों के मुखिया, सरपंच, उप-मुखिया एवं उप-सरपंच के सत्यापित प्रोफाइल।</p>
                    </div>
                    <div>
                        <a href="panchayat" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold w-100">
                            पंचायतें देखें <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Ward Members & Panch -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-light hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle bg-success-subtle text-success p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1 fs-5">8,274 वार्ड नेतृत्व</h4>
                        <p class="text-secondary small mb-3">सारण के प्रत्येक गांव के 4,492 वार्ड सदस्य एवं 3,782 ग्राम कचहरी पंच।</p>
                    </div>
                    <div>
                        <a href="search.php?q=Ward" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-semibold w-100">
                            वार्ड सदस्य खोजें <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Panchayat Samiti (BDC) & Zila Parishad -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-light hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle bg-info-subtle text-info p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                            <i class="bi bi-award-fill fs-4"></i>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1 fs-5">पंचायत समिति (BDC)</h4>
                        <p class="text-secondary small mb-3">452 निर्वाचित पंचायत समिति सदस्य एवं 47 जिला परिषद प्रतिनिधि।</p>
                    </div>
                    <div>
                        <a href="search.php?q=Panchayat+Samiti" class="btn btn-outline-info btn-sm rounded-pill px-3 fw-semibold w-100">
                            समिति सदस्य देखें <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Blocks & Villages (Maujas) -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-light hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="rounded-circle bg-warning-subtle text-warning-emphasis p-3 d-inline-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                            <i class="bi bi-map-fill fs-4"></i>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1 fs-5">20 प्रखंड व 1,876 गांव</h4>
                        <p class="text-secondary small mb-3">अनुमंडल मुख्यालय, हलका वृत्त, पिन कोड, जनगणना आंकड़े और राजस्व मौजा।</p>
                    </div>
                    <div>
                        <a href="blocks" class="btn btn-outline-warning text-dark btn-sm rounded-pill px-3 fw-semibold w-100">
                            सभी प्रखंड देखें <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Top Verified Listings Section (Hindi) -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
            <div>
                <span class="badge bg-success-subtle text-success fw-bold px-3 py-1.5 rounded-pill small uppercase tracking-wider">
                    <i class="bi bi-patch-check-fill me-1"></i>सत्यापित निर्देशिका
                </span>
                <h2 class="fw-bold font-heading text-dark mt-2 mb-0 fs-2">शीर्ष सत्यापित निर्देशिकाएं</h2>
                <p class="text-muted small mb-0">सारण जिले में अनुशंसित संस्थाएं, स्वास्थ्य क्लिनिक, व्यवसाय और सरकारी कार्यालय।</p>
            </div>
            <a href="search.php" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold">
                सभी सत्यापित देखें <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($featured_listings as $item): ?>
                <div class="col-lg-6">
                    <?php echo renderListingCard($item, ['lang' => 'hi']); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest Joined Listings Section (Hindi) -->
<section class="py-5 bg-light border-top border-bottom">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill small uppercase tracking-wider">
                    <i class="bi bi-clock-history me-1"></i>नवीनतम जुड़े
                </span>
                <h2 class="fw-bold font-heading text-dark mt-2 mb-0 fs-2">हाल ही में जुड़े सदस्य एवं निर्देशिकाएं</h2>
                <p class="text-muted small mb-0">सारण जिले में हाल ही में पंजीकृत स्थानीय व्यवसाय, स्वास्थ्य केंद्र, नागरिक और जनप्रतिनिधि।</p>
            </div>
            <a href="search.php" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold">
                सभी निर्देशिकाएं देखें <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($recent_listings as $item): ?>
                <div class="col-lg-6">
                    <?php echo renderListingCard($item, ['lang' => 'hi']); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="search.php" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                <i class="bi bi-search me-1"></i> सभी <?php echo number_format($total_listings); ?>+ निर्देशिकाएं देखें <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- 24/7 Emergency Helplines Quick Access Banner (Hindi) -->
<section class="py-4 bg-danger text-white">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white text-danger rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px;">
                    <i class="bi bi-shield-exclamation fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 font-heading text-white">सारण में 24/7 आपातकालीन सेवाएं</h5>
                    <p class="mb-0 text-white-50 small">पुलिस (112) • एम्बुलेंस (108) • फायर (101) • सदर अस्पताल छपरा • ब्लड बैंक</p>
                </div>
            </div>
            <a href="emergency" class="btn btn-light text-danger fw-bold rounded-pill px-4 py-2 flex-shrink-0 shadow-sm">
                <i class="bi bi-telephone-fill me-1"></i> आपातकालीन निर्देशिका देखें <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- Call to Action for Business Owners & Citizens (Hindi) -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="p-4 p-md-5 rounded-4 shadow-sm border" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-primary text-white fw-bold px-3 py-1 rounded-pill mb-2 small">मुफ़्त सार्वजनिक निर्देशिका</span>
                    <h3 class="fw-bold font-heading text-dark mb-2">सारण में आपका व्यापार या सेवा है?</h3>
                    <p class="text-secondary mb-0" style="max-width: 650px; line-height: 1.6;">
                        <strong>सारण इंडेक्स</strong> पर अपनी दुकान, अस्पताल/क्लिनिक, स्कूल, वकालत या सेवा को मुफ़्त में पंजीकृत करें। छपरा और सभी 20 प्रखंडों से प्रतिदिन सर्च करने वाले हजारों नागरिकों से सीधे जुड़ें।
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="add-contact" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> मुफ़्त लिस्टिंग जोड़ें
                        </a>
                        <a href="dashboard" class="btn btn-outline-secondary rounded-pill px-4 py-2.5 fw-semibold">
                            <i class="bi bi-shield-check me-1"></i> व्यापार क्लेम करें
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

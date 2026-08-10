<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = "सारण इंडेक्स – सारण को डिजिटली जोड़ते हुए | सारण जिला निर्देशिका";
require_once __DIR__ . '/includes/header.php';

$blocks = getBlocks();
$categories = getCategories();
$listings = getListings('', '', '', 6, 0);
?>

<!-- Top Hero Work-Related Photo Slider Section (Hindi) -->
<section class="hero-slider-wrapper position-relative text-center">
    <div id="heroWorkCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <!-- Carousel Indicators -->
        <div class="carousel-indicators hero-carousel-indicators">
            <button type="button" data-bs-target="#heroWorkCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroWorkCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroWorkCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <!-- Slide 1: Businesses & Commerce -->
            <div class="carousel-item active" style="background-image: url('<?php echo BASE_URL; ?>assets/img/slider1.png');">
                <div class="hero-slider-overlay"></div>
                <div class="container hero-slider-content">
                    <div class="d-inline-flex align-items-center mb-3 hero-badge-pill">
                        <i class="bi bi-shop text-warning me-2 fs-6"></i>
                        <span>व्यापार, दुकानें एवं रिटेल स्टोर निर्देशिका • सारण जिला</span>
                    </div>

                    <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
                        सारण इंडेक्स
                    </h1>
                    <p class="lead text-white-50 font-heading fw-semibold mb-3 fs-3" style="color: #cbd5e1 !important;">
                        सारण को डिजिटली जोड़ते हुए
                    </p>
                    <p class="text-white-50 mx-auto mb-4" style="max-width: 700px; font-size: 1.05rem; line-height: 1.6;">
                        <strong>सारण जिला (बिहार)</strong> की एकीकृत डिजिटल निर्देशिका। सभी 20 प्रखंडों में व्यवसायों, व्यापारियों, डॉक्टरों, वकीलों, स्कूलों एवं सरकारी कार्यालयों की खोज करें।
                    </p>
                </div>
            </div>

            <!-- Slide 2: Healthcare & Emergency Services -->
            <div class="carousel-item" style="background-image: url('<?php echo BASE_URL; ?>assets/img/slider2.png');">
                <div class="hero-slider-overlay"></div>
                <div class="container hero-slider-content">
                    <div class="d-inline-flex align-items-center mb-3 hero-badge-pill">
                        <i class="bi bi-hospital-fill text-warning me-2 fs-6"></i>
                        <span>24/7 स्वास्थ्य सेवाएं, डॉक्टर एवं आपातकालीन हेल्पलाइन</span>
                    </div>

                    <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
                        स्वास्थ्य एवं आपातकालीन सेवाएं
                    </h1>
                    <p class="lead text-white-50 font-heading fw-semibold mb-3 fs-3" style="color: #cbd5e1 !important;">
                        छपरा एवं सारण में त्वरित चिकित्सा निर्देशिका
                    </p>
                    <p class="text-white-50 mx-auto mb-4" style="max-width: 700px; font-size: 1.05rem; line-height: 1.6;">
                        सत्यापित अस्पताल, विशेषज्ञ डॉक्टर, ब्लड बैंक, थाना, फायर ब्रिगेड एवं 24x7 आपातकालीन हेल्पलाइन नंबर अपने प्रखंड में तुरंत खोजें।
                    </p>
                </div>
            </div>

            <!-- Slide 3: Advocates, Education & Administration -->
            <div class="carousel-item" style="background-image: url('<?php echo BASE_URL; ?>assets/img/slider3.png');">
                <div class="hero-slider-overlay"></div>
                <div class="container hero-slider-content">
                    <div class="d-inline-flex align-items-center mb-3 hero-badge-pill">
                        <i class="bi bi-briefcase-fill text-warning me-2 fs-6"></i>
                        <span>वकील, स्कूल, कोचिंग एवं सरकारी कार्यालय</span>
                    </div>

                    <h1 class="display-4 fw-bolder font-heading text-white mb-2 tracking-tight">
                        पेशेवर सेवाएं एवं प्रशासन निर्देशिका
                    </h1>
                    <p class="lead text-white-50 font-heading fw-semibold mb-3 fs-3" style="color: #cbd5e1 !important;">
                        सारण के नागरिकों एवं संस्थानों का सशक्तिकरण
                    </p>
                    <p class="text-white-50 mx-auto mb-4" style="max-width: 700px; font-size: 1.05rem; line-height: 1.6;">
                        कानूनी वकीलों, शैक्षणिक संस्थानों, कोचिंग सेंटर, राजस्व कार्यालयों (हलका/पंचायत) एवं जिला प्रशासन से सीधे जुड़ें।
                    </p>
                </div>
            </div>
        </div>

        <!-- Carousel Prev/Next Buttons -->
        <button class="carousel-control-prev hero-carousel-control ms-3" type="button" data-bs-target="#heroWorkCarousel" data-bs-slide="prev">
            <i class="bi bi-chevron-left text-white fs-5"></i>
            <span class="visually-hidden">पिछला</span>
        </button>
        <button class="carousel-control-next hero-carousel-control me-3" type="button" data-bs-target="#heroWorkCarousel" data-bs-slide="next">
            <i class="bi bi-chevron-right text-white fs-5"></i>
            <span class="visually-hidden">अगला</span>
        </button>
    </div>

    <!-- Search Bar Component Overlay (Floats Over Hero Slider) -->
    <div class="container position-relative z-3" style="margin-top: -55px; margin-bottom: 25px;">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11 position-relative">
                <form action="search.php" method="GET" class="search-card d-flex align-items-center gap-2 shadow-lg">
                    <button type="button" class="btn mic-btn flex-shrink-0" id="micButton" title="वॉइस सर्च">
                        <i class="bi bi-mic-fill fs-5"></i>
                    </button>
                    <input type="text" name="q" id="search_box" class="form-control search-input flex-grow-1" placeholder="सारण में दुकानें, डॉक्टर, वकील, स्कूल या सेवाएं खोजें..." autocomplete="off" required>
                    
                    <select name="block" class="form-select border-0 bg-light rounded-pill px-3 fw-medium d-none d-md-block" style="max-width: 180px;">
                        <option value="">सभी 20 प्रखंड</option>
                        <?php foreach ($blocks as $blk): ?>
                            <option value="<?php echo sanitizeInput($blk['slug']); ?>">
                                <?php echo sanitizeInput(!empty($blk['hindi_name']) ? $blk['hindi_name'] : $blk['block_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn search-submit-btn flex-shrink-0">
                        <i class="bi bi-search me-1"></i>खोजें
                    </button>
                </form>
                
                <!-- Live Autocomplete Suggest Container -->
                <div id="autocomplete_results" class="position-absolute start-0 end-0 text-start z-3 px-3" style="display: none; top: 100%; margin-top: 6px;"></div>
            </div>
        </div>
    </div>
</section>

<!-- 9 Core Verticals Grid Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill uppercase tracking-wider small">श्रेणियां</span>
            <h2 class="fw-bold font-heading text-dark mt-2 fs-2">मुख्य निर्देशिका सेवाएं</h2>
            <p class="text-muted mx-auto" style="max-width: 540px;">सारण जिले भर में सत्यापित लिस्टिंग, फोन नंबर, व्हाट्सएप संपर्क और मानचित्र प्राप्त करें।</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($categories as $cat): 
                $cName = !empty($cat['hindi_name']) ? $cat['hindi_name'] : $cat['name'];
                $cSubName = !empty($cat['hindi_name']) ? $cat['name'] : '';
            ?>
                <div class="col-lg-4 col-md-6 col-6">
                    <a href="<?php echo getCategoryUrl($cat['slug']); ?>" class="category-card">
                        <div class="category-icon-wrapper">
                            <i class="bi <?php echo sanitizeInput($cat['icon']); ?>"></i>
                        </div>
                        <div class="category-title"><?php echo sanitizeInput($cName); ?></div>
                        <?php if ($cSubName): ?>
                            <div class="text-muted small"><?php echo sanitizeInput($cSubName); ?></div>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Emergency Services Banner -->
<section class="py-4 bg-danger text-white">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white text-danger rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                    <i class="bi bi-shield-exclamation fs-2"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1 font-heading text-white">24/7 आपातकालीन सेवा निर्देशिका</h5>
                    <p class="mb-0 text-white-50 small">नगर थाना, सदर अस्पताल, ब्लड बैंक, अग्निशमन एवं सारण जिला कंट्रोल रूम हेल्पलाइन नंबर।</p>
                </div>
            </div>
            <a href="emergency" class="btn btn-light text-danger fw-bold rounded-pill px-4 py-2 flex-shrink-0 shadow-sm">
                आपातकालीन नंबर देखें <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- 20 Saran Blocks Directory Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-2">
            <div>
                <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1 rounded-pill small">भौगोलिक निर्देशिका</span>
                <h3 class="fw-bold font-heading text-dark mt-1 mb-0">सारण जिले के सभी 20 प्रखंड (ब्लॉक)</h3>
            </div>
            <a href="blocks" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold">सभी प्रखंड एवं पंचायतें देखें</a>
        </div>

        <div class="row g-3">
            <?php foreach ($blocks as $blk): 
                $bTitle = !empty($blk['hindi_name']) ? $blk['hindi_name'] : $blk['block_name'];
                $bSubTitle = !empty($blk['hindi_name']) ? $blk['block_name'] : '';
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="<?php echo getBlockUrl($blk['slug']); ?>" class="block-pill">
                        <div>
                            <div class="fw-bold"><?php echo sanitizeInput($bTitle); ?></div>
                            <?php if ($bSubTitle): ?>
                                <small class="text-muted fw-normal"><?php echo sanitizeInput($bSubTitle); ?></small>
                            <?php endif; ?>
                        </div>
                        <i class="bi bi-geo-alt-fill text-primary ms-2"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Verified Listings Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill small">सत्यापित जानकारी</span>
            <h2 class="fw-bold font-heading text-dark mt-2">सारण की लोकप्रिय लिस्टिंग</h2>
            <p class="text-muted">शीर्ष अनुशंसित संस्थान, सार्वजनिक कार्यालय, स्वास्थ्य केंद्र और कानूनी विशेषज्ञ।</p>
        </div>

        <div class="row g-4">
            <?php foreach ($listings as $item): 
                $titleShow = !empty($item['hindi_title']) ? $item['hindi_title'] : $item['title'];
                $subTitleShow = !empty($item['hindi_title']) ? $item['title'] : '';
            ?>
                <div class="col-lg-6">
                    <div class="listing-card p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1 rounded-pill small">
                                        <?php echo sanitizeInput($item['category_name']); ?>
                                    </span>
                                    <?php if (!empty($item['subcategory_name'])): ?>
                                        <span class="badge bg-secondary-subtle text-secondary fw-medium px-2 py-1 rounded-pill small">
                                            <?php echo sanitizeInput($item['subcategory_name']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    <?php if (isset($item['plan_type']) && $item['plan_type'] === 'PLATINUM'): ?>
                                        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill small shadow-xs">
                                            <i class="bi bi-crown-fill me-1 text-danger"></i> वीआईपी प्लैटिनम
                                        </span>
                                    <?php elseif (isset($item['plan_type']) && $item['plan_type'] === 'GOLD'): ?>
                                        <span class="badge bg-primary text-white fw-bold px-2.5 py-1 rounded-pill small shadow-xs">
                                            <i class="bi bi-patch-check-fill me-1"></i> गोल्ड बिजनेस
                                        </span>
                                    <?php elseif ($item['is_verified'] === 'YES'): ?>
                                        <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> सत्यापित</span>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <h4 class="fw-bold text-dark mb-1 font-heading fs-5">
                                <a href="<?php echo getListingUrl($item['slug']); ?>" class="text-dark text-decoration-none hover-primary">
                                    <?php echo sanitizeInput($titleShow); ?>
                                </a>
                            </h4>
                            <?php if (!empty($subTitleShow)): ?>
                                <div class="text-muted small fw-medium mb-2"><?php echo sanitizeInput($subTitleShow); ?></div>
                            <?php endif; ?>

                            <div class="text-muted small mb-3">
                                <i class="bi bi-geo-alt me-1 text-primary"></i><?php echo sanitizeInput(formatListingLocation($item, 'hi')); ?>
                            </div>

                            <p class="small text-secondary mb-3" style="line-height: 1.5;">
                                <?php echo sanitizeInput($item['description']); ?>
                            </p>
                        </div>

                        <div class="border-top pt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <?php echo renderStarRating($item['star_rating']); ?>
                            </div>
                            <div class="d-flex gap-2">
                                <?php if (isMobileNumberVisibleToVisitor($item)): ?>
                                    <?php if (!empty($item['whatsapp'])): ?>
                                        <a href="https://wa.me/91<?php echo sanitizeInput($item['whatsapp']); ?>" target="_blank" class="btn-whatsapp">
                                            <i class="bi bi-whatsapp"></i> व्हाट्सएप
                                        </a>
                                    <?php endif; ?>
                                    <a href="tel:<?php echo sanitizeInput($item['mobile']); ?>" class="btn-call">
                                        <i class="bi bi-telephone-fill"></i> कॉल करें
                                    </a>
                                <?php else: ?>
                                    <a href="login.php?redirect=<?php echo urlencode('listing/' . $item['slug']); ?>" class="btn-call bg-warning-subtle text-dark border-warning-subtle text-decoration-none" title="पूरा नंबर देखने के लिए लॉग इन करें">
                                        <i class="bi bi-lock-fill text-warning me-1"></i><?php echo sanitizeInput(maskPhoneNumber($item['mobile'])); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Call to Action for Business Owners -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="cta-banner text-white p-4 p-md-5 shadow-lg">
            <div class="row align-items-center g-4 position-relative z-1">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-3">व्यापारियों एवं पेशेवरों के लिए</span>
                    <h2 class="fw-bold font-heading text-white display-6 mb-3">सारण जिले भर में अपना व्यवसाय बढ़ाएं</h2>
                    <p class="text-white-50 lead mb-0" style="font-size: 1.1rem; color: #cbd5e1 !important;">
                        अपनी दुकान, क्लिनिक या स्कूल को <strong>सारण इंडेक्स</strong> पर निःशुल्क पंजीकृत करें। छपरा, मढ़ौरा, सोनपुर और सभी 20 प्रखंडों के हजारों ग्राहकों तक पहुंचें।
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="add-listing" class="btn btn-warning btn-lg rounded-pill px-5 py-3 fw-bold text-dark shadow">
                        <i class="bi bi-plus-circle-fill me-2"></i>निःशुल्क लिस्टिंग जोड़ें
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

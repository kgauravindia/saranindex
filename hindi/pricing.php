<?php
require_once __DIR__ . '/includes/functions.php';

$currentUser = null;
if (isUserLoggedIn()) {
    $currentUser = getLoggedInUser();
}

$page_title = "मेंबरशिप प्लान एवं मूल्य – व्यवसाय बढ़ाएं | सारण इंडेक्स";
$meta_description = "सारण इंडेक्स मेंबरशिप प्लान: बुनियादी मुफ्त, गोल्ड बिजनेस (₹499/वर्ष), एवं वीआईपी प्लैटिनम (₹1,499/वर्ष)। छपरा एवं सारण के सभी 20 प्रखंडों में खोज रैंकिंग बढ़ाएं।";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Hero Banner -->
<div class="bg-primary text-white py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%) !important;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.12) 0%, transparent 60%); pointer-events: none;"></div>
    <div class="container position-relative z-1 text-center py-3">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3 shadow-sm">
            <i class="bi bi-rocket-takeoff-fill me-1"></i> सारण के 20 प्रखंडों में व्यवसाय बढ़ाएं
        </span>
        <h1 class="display-5 fw-bold font-heading text-white mb-3">
            सरल एवं पारदर्शी मेंबरशिप प्लान
        </h1>
        <p class="text-white-50 fs-5 mx-auto mb-0" style="max-width: 680px;">
            सारण जिले में अपनी दुकान, क्लीनिक, स्कूल या सेवा के लिए सही प्लान चुनें।
        </p>
    </div>
</div>

<div class="container py-5">

    <!-- PRICING CARDS ROW -->
    <div class="row g-4 align-items-stretch justify-content-center mb-5">

        <!-- 1. BASIC FREE PLAN -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white d-flex flex-column justify-content-between transition-all hover-border-primary">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill fw-semibold">🟢 शुरुआती</span>
                        <span class="text-muted small">1 लिस्टिंग</span>
                    </div>
                    <h3 class="fw-bold font-heading text-dark mb-2">बुनियादी मुफ्त</h3>
                    <p class="text-muted small mb-4">सारण इंडेक्स पर शुरुआत करने वाले छोटे स्थानीय व्यवसायों के लिए उपयुक्त।</p>

                    <div class="display-5 fw-bolder text-dark mb-4">
                        ₹0 <span class="fs-6 text-muted fw-normal">/ हमेशा</span>
                    </div>

                    <hr class="text-secondary opacity-25">

                    <ul class="list-unstyled mb-4 small" style="line-height: 2;">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> सामान्य खोज रैंकिंग</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> फ़ोन कॉल बटन (`tel:`)</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> पता एवं प्रखंड जानकारी</li>
                        <li class="text-muted"><i class="bi bi-x-circle me-2"></i> कोई प्राथमिकता रैंक नहीं</li>
                        <li class="text-muted"><i class="bi bi-x-circle me-2"></i> कोई वेरीफाइड ट्रस्ट बैज नहीं</li>
                        <li class="text-muted"><i class="bi bi-x-circle me-2"></i> कोई डायरेक्ट व्हाट्सएप नहीं</li>
                    </ul>
                </div>

                <a href="add-contact.php" class="btn btn-outline-primary rounded-pill py-3 fw-bold w-100">
                    मुफ्त लिस्टिंग दर्ज करें <i class="bi bi-arrow-right me-1"></i>
                </a>
            </div>
        </div>

        <!-- 2. GOLD BUSINESS PLAN (RECOMMENDED) -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border border-2 border-primary shadow-lg rounded-4 p-4 bg-white position-relative d-flex flex-column justify-content-between transition-all" style="background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%) !important;">
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge bg-primary text-white fw-bold px-3 py-1.5 rounded-pill shadow-xs">सर्वाधिक लोकप्रिय</span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-primary text-white px-3 py-1.5 rounded-pill fw-bold"><i class="bi bi-patch-check-fill me-1"></i> गोल्ड बिजनेस</span>
                    </div>
                    <h3 class="fw-bold font-heading text-primary mb-2">गोल्ड बिजनेस</h3>
                    <p class="text-dark small mb-4">बढ़ती दुकानों, क्लीनिकों एवं सेवा प्रदाताओं के लिए सर्वोत्तम।</p>


                    <div class="display-5 fw-bolder text-primary mb-1">
                        ₹499 <span class="fs-6 text-muted fw-normal">/ वर्ष</span>
                    </div>
                    <div class="small text-success fw-semibold mb-4"><i class="bi bi-check-lg me-1"></i> केवल ₹41 प्रति माह</div>

                    <hr class="text-secondary opacity-25">

                    <ul class="list-unstyled mb-4 small text-dark" style="line-height: 2;">
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> <strong>शीर्ष खोज प्राथमिकता रैंक</strong></li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> <strong>हरा वेरीफाइड ट्रस्ट बैज</strong></li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> <strong>डायरेक्ट व्हाट्सएप चैट बटन</strong></li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> प्रमुख सेवाओं की सूची</li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> 3 फ़ोटो तक अपलोड</li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> पूरे 1 वर्ष की वैधता (365 दिन)</li>
                    </ul>
                </div>

                <a href="<?php echo isUserLoggedIn() ? 'dashboard.php' : 'login.php?redirect=dashboard.php'; ?>" class="btn btn-primary rounded-pill py-3 fw-bold w-100 shadow-sm">
                    <i class="bi bi-rocket-takeoff-fill me-1"></i> गोल्ड प्लान चुनें (₹499)
                </a>
            </div>
        </div>

        <!-- 3. VIP PLATINUM PLAN -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border border-2 border-warning shadow-lg rounded-4 p-4 bg-white position-relative d-flex flex-column justify-content-between transition-all" style="background: linear-gradient(180deg, #fffbeb 0%, #ffffff 100%) !important;">
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill shadow-xs">सर्वोत्तम दृश्यता</span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold"><i class="bi bi-crown-fill text-danger me-1"></i> वीआईपी प्लैटिनम</span>
                    </div>
                    <h3 class="fw-bold font-heading text-dark mb-2">वीआईपी प्लैटिनम</h3>
                    <p class="text-dark small mb-4">बड़े ब्रांडों, अस्पतालों, स्कूलों और शीर्ष फर्मों के लिए शीर्ष स्थान।</p>

                    <div class="display-5 fw-bolder text-dark mb-1">
                        ₹1,499 <span class="fs-6 text-muted fw-normal">/ वर्ष</span>
                    </div>
                    <div class="small text-warning text-dark fw-semibold mb-4"><i class="bi bi-star-fill me-1"></i> शीर्ष श्रेणी स्थान</div>

                    <hr class="text-secondary opacity-25">

                    <ul class="list-unstyled mb-4 small text-dark" style="line-height: 2;">
                        <li><i class="bi bi-crown-fill text-warning me-2"></i> <strong>शीर्ष फीचर्ड स्थान</strong></li>
                        <li><i class="bi bi-crown-fill text-warning me-2"></i> <strong>गोल्ड वीआईपी क्राउन वेरीफाइड बैज</strong></li>
                        <li><i class="bi bi-crown-fill text-warning me-2"></i> कॉल + व्हाट्सएप + सीधी बुकिंग</li>
                        <li><i class="bi bi-crown-fill text-warning me-2"></i> 6 फ़ोटो तक अपलोड</li>
                        <li><i class="bi bi-crown-fill text-warning me-2"></i> पूरा बिजनेस कैटलॉग एवं बायो</li>
                        <li><i class="bi bi-crown-fill text-warning me-2"></i> 24x7 प्राथमिकता सहायता</li>
                    </ul>
                </div>

                <a href="<?php echo isUserLoggedIn() ? 'dashboard.php' : 'login.php?redirect=dashboard.php'; ?>" class="btn btn-warning text-dark rounded-pill py-3 fw-bold w-100 shadow-sm">
                    <i class="bi bi-crown-fill me-1"></i> वीआईपी प्लैटिनम चुनें (₹1,499)
                </a>
            </div>
        </div>

    </div>

    <!-- FEATURE COMPARISON MATRIX TABLE -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-5">
        <div class="text-center mb-4">
            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded-pill small">विस्तृत मैट्रिक्स</span>
            <h3 class="fw-bold font-heading text-dark mt-1">सुविधाओं की तुलना</h3>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-striped border">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 40%;">सुविधा / लाभ</th>
                        <th class="text-center" style="width: 20%;">🟢 बुनियादी मुफ्त</th>
                        <th class="text-center" style="width: 20%;">🔵 गोल्ड बिजनेस</th>
                        <th class="text-center" style="width: 20%;">👑 वीआईपी प्लैटिनम</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold text-dark">वार्षिक सदस्यता शुल्क</td>
                        <td class="text-center fw-bold">₹0 / हमेशा</td>
                        <td class="text-center fw-bold text-primary">₹499 / वर्ष</td>
                        <td class="text-center fw-bold text-warning text-dark">₹1,499 / वर्ष</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark">खोज रैंकिंग प्राथमिकता</td>
                        <td class="text-center text-muted">सामान्य क्रम</td>
                        <td class="text-center fw-bold text-primary">शीर्ष प्राथमिकता रैंक</td>
                        <td class="text-center fw-bold text-dark">शीर्ष फीचर्ड स्थान</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark">वेरीफाइड ट्रस्ट बैज</td>
                        <td class="text-center text-muted">कोई नहीं</td>
                        <td class="text-center"><span class="verified-badge"><i class="bi bi-patch-check-fill"></i> सत्यापित</span></td>
                        <td class="text-center"><span class="vip-platinum-badge"><i class="bi bi-crown-fill"></i> वीआईपी क्राउन</span></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark">डायरेक्ट व्हाट्सएप बटन</td>
                        <td class="text-center text-muted"><i class="bi bi-x-lg text-danger"></i></td>
                        <td class="text-center"><i class="bi bi-check-lg text-success fs-5"></i></td>
                        <td class="text-center"><i class="bi bi-check-lg text-success fs-5"></i></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark">सेवाएं एवं विशेषताएं सूची</td>
                        <td class="text-center text-muted"><i class="bi bi-x-lg text-danger"></i></td>
                        <td class="text-center"><i class="bi bi-check-lg text-success fs-5"></i></td>
                        <td class="text-center"><i class="bi bi-check-lg text-success fs-5"></i></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark">फ़ोटो गैलरी अपलोड</td>
                        <td class="text-center text-muted">कोई नहीं</td>
                        <td class="text-center fw-medium">3 फ़ोटो तक</td>
                        <td class="text-center fw-bold text-dark">6 फ़ोटो तक</td>
                    </tr>

                    <tr>
                        <td class="fw-semibold text-dark">ऑनलाइन रेजरपे भुगतान गेटवे</td>
                        <td class="text-center text-muted">लागू नहीं</td>
                        <td class="text-center"><i class="bi bi-shield-check text-success fs-5"></i> रेजरपे (Razorpay)</td>
                        <td class="text-center"><i class="bi bi-shield-check text-success fs-5"></i> रेजरपे (Razorpay)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FAQ SECTION -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
        <div class="text-center mb-4">
            <h4 class="fw-bold font-heading text-dark mb-1">अक्सर पूछे जाने वाले प्रश्न (FAQs)</h4>
            <p class="text-muted small">सारण इंडेक्स मेंबरशिप प्लान से जुड़े उत्तर।</p>
        </div>

        <div class="accordion" id="pricingFaq">
            <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-xs">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        रेजरपे (Razorpay) द्वारा ऑनलाइन भुगतान कैसे करें?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#pricingFaq">
                    <div class="accordion-body text-secondary small">
                        जब आप गोल्ड बिजनेस (₹499) या वीआईपी प्लैटिनम (₹1,499) चुनते हैं, तो हमारा सुरक्षित रेजरपे भुगतान गेटवे खुलता है। आप यूपीआई (GPay, PhonePe, Paytm), डेबिट/क्रेडिट कार्ड या नेटबैंकिंग से भुगतान कर सकते हैं।
                    </div>
                </div>
            </div>

            <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-xs">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        क्या मैं कभी भी अपना प्लान अपग्रेड कर सकता/सकती हूँ?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                    <div class="accordion-body text-secondary small">
                        हाँ! आप अपने खाता डैशबोर्ड से कभी भी बुनियादी मुफ्त से गोल्ड या वीआईपी प्लैटिनम में अपग्रेड कर सकते हैं।
                    </div>
                </div>
            </div>

            <div class="accordion-item border-0 rounded-3 overflow-hidden shadow-xs">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        बिलिंग या सहायता के लिए संपर्क कैसे करें?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                    <div class="accordion-body text-secondary small">
                        किसी भी रसीद या बिलिंग पूछताछ के लिए हमारे ईमेल <strong>ask@offerplant.com</strong> पर संपर्क करें।
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

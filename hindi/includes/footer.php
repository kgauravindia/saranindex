<?php
$social_fb = defined('SOCIAL_FACEBOOK') ? SOCIAL_FACEBOOK : 'https://facebook.com/saranindex';
$social_insta = defined('SOCIAL_INSTAGRAM') ? SOCIAL_INSTAGRAM : 'https://instagram.com/saranindex';
$social_twitter = defined('SOCIAL_TWITTER') ? SOCIAL_TWITTER : 'https://x.com/saranindex';
$social_threads = defined('SOCIAL_THREADS') ? SOCIAL_THREADS : 'https://threads.net/@saranindex';
$social_yt = defined('SOCIAL_YOUTUBE') ? SOCIAL_YOUTUBE : 'https://youtube.com/@saranindex';
$social_tele = defined('SOCIAL_TELEGRAM') ? SOCIAL_TELEGRAM : 'https://t.me/saranindex';
$social_wa = defined('SOCIAL_WHATSAPP') ? SOCIAL_WHATSAPP : 'https://whatsapp.com/channel/0029VbDJKIS4CrfaodCTmw1c';
?>
<!-- OfferPlant 9th Anniversary Footer Banner & Redesigned Footer (Hindi) -->
<footer class="anniversary-badge text-white mt-5">
    <div class="container relative-z">
        <!-- Pre-Footer CTA Banner -->
        <div class="footer-cta-card mb-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                        <span class="badge bg-warning text-dark fw-bold px-3 py-2 fs-6 rounded-pill shadow-sm"><i class="bi bi-award-fill me-1"></i> 9वीं वर्षगांठ</span>
                        <span class="text-white-50 font-heading fw-semibold small">26 जुलाई 2017 – 26 जुलाई 2026</span>
                        <span class="badge bg-info-subtle text-info border border-info rounded-pill px-3 py-1.5 ms-auto ms-sm-0 small">100% निःशुल्क सार्वजनिक निर्देशिका</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Saran Index Logo" height="48" class="bg-white p-1 rounded-3 shadow-sm flex-shrink-0" style="object-fit: contain;">
                        <h3 class="fw-bold mb-0 font-heading text-white fs-4">सारण इंडेक्स – सारण को डिजिटली जोड़ते हुए</h3>
                    </div>
                    <p class="text-white-50 mb-0" style="max-width: 600px; font-size: 0.95rem; line-height: 1.6;">
                        <strong>ऑफ़रप्लांट टेक्नोलॉजीज प्राइवेट लिमिटेड</strong> के 9वें स्थापना दिवस पर लॉन्च। सारण जिले (बिहार) के प्रत्येक प्रखंड, पंचायत, गाँव, व्यवसाय, अस्पताल, वकील एवं नागरिक को डिजिटल रूप से जोड़ने के लिए समर्पित।
                    </p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <div class="d-inline-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="add-contact" class="btn btn-warning rounded-pill px-4 py-2.5 fw-bold text-dark shadow d-inline-flex align-items-center gap-2">
                            <i class="bi bi-rocket-takeoff-fill fs-5"></i>मुफ़्त व्यवसाय जोड़ें
                        </a>
                        <a href="emergency" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-semibold d-inline-flex align-items-center gap-2">
                            <i class="bi bi-telephone-outbound-fill text-warning"></i>आपातकालीन 24x7
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main 4-Column Footer Grid -->
        <div class="row g-4 pt-2">
            <!-- Column 1: Brand & Social Hub -->
            <div class="col-lg-3 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Saran Index" height="36" class="bg-white p-1 rounded-2">
                    <h5 class="fw-bold text-white font-heading mb-0">सारण इंडेक्स</h5>
                </div>
                <p class="text-white-50 small mb-3" style="line-height: 1.6;">
                    सारण जिले (छपरा) की एकमात्र भरोसेमंद डिजिटल निर्देशिका, जो नागरिकों, व्यापारियों, वकीलों, डॉक्टरों, स्कूलों और स्थानीय प्रशासन को एक साथ लाती है।
                </p>
                <div class="small text-white-50 mb-4 p-2.5 rounded-3 bg-dark border border-secondary d-inline-flex align-items-center gap-2 w-100">
                    <i class="bi bi-building text-warning fs-5 flex-shrink-0"></i>
                    <div>
                        <span class="d-block text-white-50" style="font-size: 0.75rem;">एक पहल</span>
                        <strong class="text-white" style="font-size: 0.85rem;">ऑफ़रप्लांट टेक्नोलॉजीज प्रा. लि.</strong>
                    </div>
                </div>

                <div>
                    <h6 class="footer-heading">सोशल मीडिया पर जुड़ें <span class="badge bg-warning text-dark me-1" style="text-transform: none;">@saranindex</span></h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo $social_fb; ?>" target="_blank" class="footer-social-btn social-btn-facebook" title="Facebook @saranindex" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="<?php echo $social_insta; ?>" target="_blank" class="footer-social-btn social-btn-instagram" title="Instagram @saranindex" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="<?php echo $social_twitter; ?>" target="_blank" class="footer-social-btn social-btn-x" title="X (Twitter) @saranindex" aria-label="X Twitter">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="<?php echo $social_threads; ?>" target="_blank" class="footer-social-btn social-btn-threads" title="Threads @saranindex" aria-label="Threads">
                            <i class="bi bi-threads"></i>
                        </a>
                        <a href="<?php echo $social_yt; ?>" target="_blank" class="footer-social-btn social-btn-youtube" title="YouTube @saranindex" aria-label="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="<?php echo $social_tele; ?>" target="_blank" class="footer-social-btn social-btn-telegram" title="Telegram @saranindex" aria-label="Telegram">
                            <i class="bi bi-telegram"></i>
                        </a>
                        <a href="<?php echo $social_wa; ?>" target="_blank" class="footer-social-btn social-btn-whatsapp" title="WhatsApp Channel @saranindex" aria-label="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Column 2: Popular Categories -->
            <div class="col-lg-3 col-md-6 ps-lg-4">
                <h6 class="footer-heading">मुख्य श्रेणियां</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="category/businesses-retail-stores" class="footer-link">
                        <i class="bi bi-shop text-warning me-1"></i> व्यापार एवं दुकानें
                    </a>
                    <a href="category/advocates-legal" class="footer-link">
                        <i class="bi bi-briefcase-fill text-warning me-1"></i> वकील एवं कानूनी सेवाएं
                    </a>
                    <a href="category/doctors-healthcare" class="footer-link">
                        <i class="bi bi-hospital-fill text-warning me-1"></i> डॉक्टर एवं अस्पताल
                    </a>
                    <a href="category/schools-education" class="footer-link">
                        <i class="bi bi-book-half text-warning me-1"></i> स्कूल एवं कोचिंग
                    </a>
                    <a href="category/government-offices" class="footer-link">
                        <i class="bi bi-building-gear text-warning me-1"></i> सरकारी कार्यालय
                    </a>
                    <a href="category/hotels-restaurants" class="footer-link">
                        <i class="bi bi-cup-hot-fill text-warning me-1"></i> होटल एवं रेस्टोरेंट
                    </a>
                    <a href="emergency" class="footer-link">
                        <i class="bi bi-telephone-fill text-warning me-1"></i> आपातकालीन हेल्पलाइन
                    </a>
                    <a href="pricing" class="footer-link">
                        <i class="bi bi-star-fill text-warning me-1"></i> प्रमाणित व्यापार योजनाएं
                    </a>
                </div>
            </div>

            <!-- Column 3: Quick Navigation -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">त्वरित लिंक एवं कानूनी जानकारी</h6>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                    <li><a href="./" class="footer-link"><i class="bi bi-chevron-right text-warning"></i> मुख्य पृष्ठ (होम)</a></li>
                    <li><a href="blocks" class="footer-link"><i class="bi bi-chevron-right text-warning"></i> सारण के सभी 20 प्रखंड</a></li>
                    <li><a href="emergency" class="footer-link"><i class="bi bi-chevron-right text-warning"></i> 24x7 आपातकालीन सेवाएं</a></li>
                    <li><a href="about" class="footer-link"><i class="bi bi-chevron-right text-warning"></i> ऑफ़रप्लांट एवं सारण इंडेक्स के बारे में</a></li>
                    <li><a href="sources" class="footer-link"><i class="bi bi-chevron-right text-warning"></i> आधिकारिक डेटा स्रोत</a></li>
                    <li><a href="privacy-policy" class="footer-link"><i class="bi bi-chevron-right text-warning"></i> गोपनीयता नीति (Privacy Policy)</a></li>
                    <li><a href="terms" class="footer-link"><i class="bi bi-chevron-right text-warning"></i> नियम एवं शर्तें (Terms & Conditions)</a></li>
                    <li><a href="refund-policy" class="footer-link"><i class="bi bi-chevron-right text-warning"></i> रिफंड नीति (Refund Policy)</a></li>
                    <li><a href="contact" class="footer-link"><i class="bi bi-chevron-right text-warning"></i> संपर्क एवं सहायता</a></li>
                    <li><a href="../admin/login.php" class="footer-link"><i class="bi bi-shield-lock-fill text-warning"></i> एडमिन पोर्टल</a></li>
                </ul>
            </div>

            <!-- Column 4: Contact & Support Box -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">संपर्क एवं सहायता</h6>
                <div class="contact-info-card">
                    <div class="d-flex align-items-start gap-2.5 text-white-50 small mb-3">
                        <i class="bi bi-geo-alt-fill text-warning fs-5 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="d-block text-white">मुख्यालय</strong>
                            <span>छपरा, सारण, बिहार, भारत - 841301</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2.5 text-white-50 small mb-3">
                        <i class="bi bi-envelope-check-fill text-warning fs-5 flex-shrink-0"></i>
                        <div>
                            <strong class="d-block text-white">ईमेल सहायता</strong>
                            <a href="mailto:info@saranindex.com" class="text-white-50 text-decoration-none hover-white">info@saranindex.com</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2.5 text-white-50 small mb-3">
                        <i class="bi bi-clock-fill text-warning fs-5 flex-shrink-0"></i>
                        <div>
                            <strong class="d-block text-white">सहायता समय</strong>
                            <span>सोम – शनि: सुबह 9:00 – शाम 7:00</span>
                        </div>
                    </div>
                    <a href="<?php echo $social_wa; ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 py-2 w-100 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center gap-2 mt-1">
                        <i class="bi bi-whatsapp fs-6"></i> व्हाट्सएप चैनल से जुड़ें
                    </a>
                    <div class="mt-3 text-center">
                        <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-50 rounded-pill fw-semibold px-3 py-1.5" style="font-size: 0.78rem;">
                            <i class="bi bi-shield-check me-1"></i> निजी डिजिटल निर्देशिका
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business by Block Tag Cloud Section -->
        <div class="row pt-4 mt-4 border-top border-secondary">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <h6 class="footer-heading mb-0">
                        <i class="bi bi-geo-alt-fill me-1"></i>प्रखंड वार व्यावसायिक निर्देशिका (सारण के सभी 20 प्रखंड)
                    </h6>
                    <a href="blocks" class="small text-warning text-decoration-none hover-white"><i class="bi bi-grid-fill me-1"></i>प्रखंड सूची देखें</a>
                </div>
                <div class="d-flex flex-wrap gap-1.5 text-white-50 small">
                    <?php 
                    if (!function_exists('getBlocks')) {
                        @include_once __DIR__ . '/../../includes/functions.php';
                    }
                    $footer_blocks = function_exists('getBlocks') ? getBlocks() : [];
                    if (!empty($footer_blocks)):
                        foreach ($footer_blocks as $fblk):
                            $bName = preg_replace('/\s+Sadar$/i', '', $fblk['block_name'] ?? $fblk['name'] ?? '');
                    ?>
                        <a href="search.php?q=&category=&block=<?php echo urlencode($fblk['slug']); ?>" class="block-pill-chip">
                            <i class="bi bi-pin-map-fill text-warning"></i> Business in <?php echo sanitizeInput($bName); ?>
                        </a>
                    <?php 
                        endforeach;
                    else:
                        $default_blocks = [
                            'chapra-sadar' => 'Chapra', 'ekma' => 'Ekma', 'marhaura' => 'Marhaura',
                            'sonpur' => 'Sonepur', 'garkha' => 'Garkha', 'parsa' => 'Parsa',
                            'dighwara' => 'Dighwara', 'amanour' => 'Amanour', 'baniapur' => 'Baniapur',
                            'taraiya' => 'Taraiya', 'isuapur' => 'Isuapur', 'lahladpur' => 'Lahladpur',
                            'manjhi' => 'Manjhi', 'maker' => 'Maker', 'nagra' => 'Nagra',
                            'mashrakh' => 'Mashrakh', 'panapur' => 'Panapur', 'rivilganj' => 'Revelganj'
                        ];
                        foreach ($default_blocks as $bslug => $bname):
                    ?>
                        <a href="search.php?q=&category=&block=<?php echo $bslug; ?>" class="block-pill-chip">
                            <i class="bi bi-pin-map-fill text-warning"></i> Business in <?php echo $bname; ?>
                        </a>
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright Bar -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between pt-4 mt-4 border-top border-secondary text-white-50 small gap-3">
            <p class="mb-0 text-center text-md-start">
                &copy; <?php echo date('Y'); ?> <strong>सारण इंडेक्स</strong>. सर्वाधिकार सुरक्षित। डिज़ाइन एवं संचालित: 
                <a href="http://offerplant.com" target="_blank" class="text-warning text-decoration-none fw-bold">ऑफ़रप्लांट टेक्नोलॉजीज प्राइवेट लिमिटेड</a>
            </p>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-dark border border-secondary text-white-50 rounded-pill px-3 py-1.5">
                    <i class="bi bi-heart-fill text-danger me-1"></i> सारण जिला (बिहार) के लिए समर्पित
                </span>
            </div>
            <div class="d-flex gap-3">
                <a href="privacy-policy" class="text-white-50 text-decoration-none footer-link">गोपनीयता नीति</a>
                <span>•</span>
                <a href="terms" class="text-white-50 text-decoration-none footer-link">नियम एवं शर्तें</a>
                <span>•</span>
                <a href="refund-policy" class="text-white-50 text-decoration-none footer-link">रिफंड नीति</a>
            </div>
        </div>
    </div>
</footer>

<!-- Floating Back to Top Button -->
<button id="backToTopBtn" title="Back to Top" aria-label="Back to Top">
    <i class="bi bi-arrow-up-short fs-3"></i>
</button>

<!-- Non-Government Disclaimer Modal -->
<div class="modal fade" id="disclaimerModal" tabindex="-1" aria-labelledby="disclaimerModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                <div class="d-flex align-items-center">
                    <div class="bg-warning text-dark rounded-circle me-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi bi-shield-exclamation-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold font-heading mb-0 text-white" id="disclaimerModalLabel">सूचना एवं अस्वीकरण (Disclaimer)</h5>
                        <small class="text-white-50">सारण इंडेक्स डिजिटल निर्देशिका</small>
                    </div>
                </div>
            </div>
            <div class="modal-body p-4 text-dark" style="font-size: 0.95rem; line-height: 1.6;">
                <div class="alert bg-warning-subtle text-dark border-warning border-start border-4 rounded-3 mb-3 p-3">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-2 fs-5 flex-shrink-0 mt-1"></i>
                        <div>
                            <strong class="d-block text-dark">गैर-सरकारी एवं गैर-राजनीतिक घोषणा</strong>
                            <span class="small text-secondary">ऑफ़रप्लांट टेक्नोलॉजीज प्रा. लि. की एक निजी पहल।</span>
                        </div>
                    </div>
                </div>
                <p class="mb-3">
                    <strong>सारण इंडेक्स</strong> (<code>saranindex.com</code>) एक स्वतंत्र निजी डिजिटल निर्देशिका है जो सारण जिले (बिहार) के स्थानीय व्यवसायों, सेवाओं और नागरिकों को जोड़ने के लिए समर्पित है।
                </p>
                <div class="p-3 bg-light rounded-3 border text-secondary small">
                    <i class="bi bi-shield-x text-danger me-1 fs-6"></i>
                    कृपया ध्यान दें: यह वेबसाइट किसी भी <strong>सरकारी विभाग, सरकारी प्राधिकरण, या राजनीतिक संगठन से संबद्ध, समर्थित या प्रतिनिधित्व नहीं करती है।</strong>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-3 px-4">
                <button type="button" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm w-100" id="acceptDisclaimerBtn" data-bs-dismiss="modal">
                    <i class="bi bi-check-circle-fill me-2"></i>मैं समझ गया/समझ गई, आगे बढ़ें
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</body>
</html>


<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "आधिकारिक संदर्भ डेटा स्रोत एवं पोर्टल – सारण इंडेक्स";
$meta_description = "सारण इंडेक्स द्वारा स्थानीय प्रशासनिक, शैक्षणिक, राजस्व एवं व्यावसायिक जानकारी हेतु संदर्भित आधिकारिक सरकारी पोर्टलों एवं खुली निर्देशिकाओं की सूची।";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header -->
<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2">पारदर्शिता एवं डेटा स्रोत</span>
        <h1 class="fw-bolder font-heading text-white display-5 mb-2">आधिकारिक संदर्भ डेटा स्रोत</h1>
        <p class="text-white-50 lead mx-auto mb-0" style="max-width: 720px;">
            <strong>सारण इंडेक्स</strong> नागरिक सुविधा और डिजिटल कनेक्टिविटी के लिए आधिकारिक सरकारी पोर्टलों, खुली रजिस्ट्रियों और सत्यापित सार्वजनिक डेटाबेस से डेटा संकलित करता है।
        </p>
    </div>
</div>

<div class="container py-5">
    
    <!-- Introductory Callout -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5 bg-white border-start border-4 border-primary">
        <div class="d-flex align-items-start">
            <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3 flex-shrink-0">
                <i class="bi bi-info-circle-fill fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark font-heading mb-1">हमारी डेटा संकलन नीति</h5>
                <p class="text-secondary mb-0" style="line-height: 1.7;">
                    सारण जिले (छपरा) के सभी 20 प्रखंडों में डेटा अखंडता, मानकीकृत कोड और सटीक प्रशासनिक विवरण सुनिश्चित करने के लिए हमारी निर्देशिका खुले सार्वजनिक डेटा और आधिकारिक सरकारी पोर्टलों का संदर्भ लेती है। हमारे प्लेटफ़ॉर्म द्वारा उपयोग किए जाने वाले मुख्य संदर्भ स्रोतों की सूची नीचे दी गई है।
                </p>
            </div>
        </div>
    </div>

    <!-- Data Sources Grid -->
    <div class="row g-4 mb-5">
        
        <!-- 1. AISHE Portal -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-info-subtle text-info-emphasis fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-mortarboard-fill me-1"></i> शिक्षा एवं कॉलेज
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">भारत सरकार</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">AISHE पोर्टल (उच्च शिक्षा सर्वेक्षण)</h4>
                <p class="text-muted small mb-3">All India Survey on Higher Education (शिक्षा मंत्रालय)</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    सारण जिले में स्थित उच्च शैक्षणिक संस्थानों, कॉलेजों और विश्वविद्यालयों की निर्देशिका का आधिकारिक सरकारी पोर्टल।
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>aishe.gov.in</span>
                    <a href="https://aishe.gov.in" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        पोर्टल पर जाएं <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. GST Portal -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-shop me-1"></i> व्यापार एवं जीएसटी पंजीकरण
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">जीएसटी परिषद</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">आधिकारिक जीएसटी पोर्टल (GST Portal)</h4>
                <p class="text-muted small mb-3">Goods and Services Tax Network (GSTN)</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    सारण जिले में काम करने वाले व्यावसायिक प्रतिष्ठानों के नाम, व्यापार नाम और पंजीकरण स्थिति की जानकारी प्रदान करने वाला आधिकारिक पोर्टल।
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>gst.gov.in</span>
                    <a href="https://gst.gov.in" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        पोर्टल पर जाएं <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 3. LGD Portal -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-diagram-3-fill me-1"></i> स्थानीय प्रशासनिक कोड
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">पंचायती राज मंत्रालय</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">LGD डायरेक्टरी पोर्टल</h4>
                <p class="text-muted small mb-3">Local Government Directory Portal</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    सारण जिले के सभी 20 प्रखंडों, ग्राम पंचायतों और जनगणना गांवों के लिए मानकीकृत एलजीडी कोड प्रदान करने वाला आधिकारिक पोर्टल।
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>lgdirectory.gov.in</span>
                    <a href="https://lgdirectory.gov.in/" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        पोर्टल पर जाएं <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. Bihar Bhumi -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-warning-subtle text-dark fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-map-fill me-1"></i> राजस्व अंचल, हलका एवं मौजा
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">बिहार सरकार</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">बिहार भूमि पोर्टल (Bihar Bhumi)</h4>
                <p class="text-muted small mb-3">राजस्व एवं भूमि सुधार विभाग, बिहार सरकार</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    सारण जिले में राजस्व अंचल (प्रखंड), हलका नंबर, मौजा (राजस्व गांव) कोड और प्रशासनिक विवरण का आधिकारिक सरकारी पोर्टल।
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>biharbhumi.bihar.gov.in</span>
                    <a href="https://biharbhumi.bihar.gov.in/" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        पोर्टल पर जाएं <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 5. Census of India -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-people-fill me-1"></i> जनगणना एवं जनसंख्या डेटा
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">गृह मंत्रालय</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">भारत की जनगणना 2011 डेटा पोर्टल</h4>
                <p class="text-muted small mb-3">महारजिस्ट्रार एवं जनगणना आयुक्त का कार्यालय</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    सारण के गांवों की कुल जनसंख्या, साक्षरता दर, परिवार संख्या और श्रमिक श्रेणियों का प्राथमिक आधिकारिक जनगणना डेटा पोर्टल।
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>censusindia.gov.in</span>
                    <a href="https://censusindia.gov.in" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        पोर्टल पर जाएं <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 6. Saran NIC Portal -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white hover-lift">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1 rounded-pill small">
                        <i class="bi bi-building-fill me-1"></i> जिला प्रशासन
                    </span>
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">एनआईसी बिहार</span>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-2">सारण जिला आधिकारिक एनआईसी पोर्टल</h4>
                <p class="text-muted small mb-3">National Informatics Centre (NIC Saran, Chapra)</p>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    सारण जिले (छपरा) की आधिकारिक प्रशासन वेबसाइट जो सरकारी कार्यालयों, हेल्पलाइन और अधिकारियों की संपर्क निर्देशिका प्रदान करती है।
                </p>
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted small"><i class="bi bi-link-45deg me-1"></i>saran.nic.in</span>
                    <a href="https://saran.nic.in" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                        पोर्टल पर जाएं <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Non-Government Disclaimer Footer Box -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-light text-dark">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-shield-exclamation text-warning me-2 fs-4"></i>
            <h5 class="fw-bold mb-0 font-heading">डेटा अस्वीकरण (Data Disclaimer)</h5>
        </div>
        <p class="small text-secondary mb-0" style="line-height: 1.6;">
            <strong>सारण इंडेक्स</strong> <strong>ऑफ़रप्लांट टेक्नोलॉजीज प्रा. लि.</strong> द्वारा निर्मित एक स्वतंत्र डिजिटल निर्देशिका प्लेटफ़ॉर्म है। सरकारी पोर्टलों का संदर्भ केवल डेटा पारदर्शिता और जनसुविधा के लिए दिया गया है। सारण इंडेक्स किसी भी सरकारी एजेंसी से संबद्ध या प्रतिनिधि नहीं है।
        </p>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

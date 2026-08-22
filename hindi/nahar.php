<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "सारण जिले का नहर एवं सिंचाई नेटवर्क (गंडक परियोजना) – सारण इंडेक्स";
$meta_description = "सारण जिले (छपरा, बिहार) की संपूर्ण नहर एवं सिंचाई प्रणाली का विवरण। गंडक परियोजना की सारण मुख्य नहर, मढ़ौरा शाखा नहर, छपरा शाखा नहर, रजवाहा (वितरणी), माइनर्स एवं कृषि सिंचित क्षेत्र।";
$meta_keywords = "सारण की नहरें, छपरा नहर, गंडक परियोजना सारण, सारण मुख्य नहर, मढ़ौरा शाखा नहर, छपरा शाखा नहर, जल संसाधन विभाग छपरा, हर खेत को पानी सारण";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom Styles for Nahar / Canal Page -->
<style>
.nahar-hero-bg {
    background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%);
    color: #ffffff;
}
.canal-card {
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    background: #ffffff;
    overflow: hidden;
}
.canal-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 35px -10px rgba(5, 150, 105, 0.2) !important;
    border-color: #6ee7b7;
}
.canal-badge-green {
    background: #d1fae5;
    color: #065f46;
    font-weight: 700;
}
.canal-badge-blue {
    background: #e0f2fe;
    color: #0369a1;
    font-weight: 700;
}
.canal-stat-box {
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 0.75rem 1rem;
}
.quick-nav-pill {
    background: #ffffff;
    border: 1px solid #e2e8f0;
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
}
.quick-nav-pill:hover, .quick-nav-pill.active {
    background: #059669;
    color: #ffffff;
    border-color: #059669;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
}
.canal-feature-box {
    background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
    border-left: 5px solid #10b981;
    border-radius: 16px;
    padding: 1.5rem;
}
</style>

<!-- Hero Section -->
<div class="nahar-hero-bg py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-4 text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
            <i class="bi bi-water me-1"></i> सारण की कृषि जीवनधारा (Irrigation Lifeline)
        </span>
        <h1 class="fw-bold font-heading text-white display-5 mb-2">
            सारण जिले की नहरें एवं सिंचाई नेटवर्क
        </h1>
        <div class="lead text-warning fw-semibold mb-3">
            गंडक परियोजना, सारण मुख्य नहर, मढ़ौरा व छपरा शाखा नहरें एवं संपूर्ण वितरणी तंत्र
        </div>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 840px;">
            वाल्मीकिनगर से निकलने वाली ऐतिहासिक <strong>गंडक सिंचाई परियोजना (Gandak Project)</strong> के माध्यम से सारण जिले में सैकड़ों किलोमीटर लंबा नहर नेटवर्क फैला हुआ है, जो सभी 20 प्रखंडों के खेतों को गुरुत्वाकर्षण प्रवाह (Gravity Flow) से निरंतर सिंचित करता है।
        </p>

        <!-- Quick Jump Navigation -->
        <div class="d-flex justify-content-center gap-2 flex-wrap mb-2">
            <a href="#gandak-project" class="quick-nav-pill"><i class="bi bi-diagram-3-fill text-success"></i> गंडक परियोजना परिचय</a>
            <a href="#major-canals" class="quick-nav-pill"><i class="bi bi-water text-primary"></i> मुख्य व शाखा नहरें</a>
            <a href="#distributaries" class="quick-nav-pill"><i class="bi bi-bezier2 text-info"></i> रजवाहा (वितरणी) व माइनर</a>
            <a href="#crop-impact" class="quick-nav-pill"><i class="bi bi-flower2 text-warning"></i> मौसमी फसलें व लाभ</a>
            <a href="#officers" class="quick-nav-pill"><i class="bi bi-telephone-fill text-danger"></i> अधिकारी संपर्क नंबर</a>
            <a href="#sources" class="quick-nav-pill"><i class="bi bi-shield-check text-success"></i> आधिकारिक स्रोत</a>
        </div>
    </div>
</div>

<!-- Main Container -->
<div class="container py-5">

    <!-- KPI Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-success">
                <div class="text-success fs-1 mb-2"><i class="bi bi-water"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">गंडक परियोजना</h3>
                <p class="text-muted small mb-0 fw-semibold">प्रमुख जल स्रोत</p>
                <div class="mt-2 text-success small fs-7 fw-bold">वाल्मीकिनगर बराज फीडर</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-primary">
                <div class="text-primary fs-1 mb-2"><i class="bi bi-diagram-3"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">350+ किमी</h3>
                <p class="text-muted small mb-0 fw-semibold">नहर नेटवर्क लंबाई</p>
                <div class="mt-2 text-primary small fs-7 fw-bold">मुख्य, शाखा व माइनर नहरें</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-warning">
                <div class="text-warning fs-1 mb-2"><i class="bi bi-geo-alt-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">20 प्रखंड</h3>
                <p class="text-muted small mb-0 fw-semibold">सिंचाई कमान क्षेत्र</p>
                <div class="mt-2 text-warning small fs-7 fw-bold">खरीफ व रबी फसलों में जल आपूर्ति</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-info">
                <div class="text-info fs-1 mb-2"><i class="bi bi-droplet-half"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">गुरुत्वीय प्रवाह</h3>
                <p class="text-muted small mb-0 fw-semibold">पर्यावरण अनुकूल सिंचाई</p>
                <div class="mt-2 text-info small fs-7 fw-bold">प्राकृतिक ढलान से जल वितरण</div>
            </div>
        </div>
    </div>

    <!-- Section 1: The Gandak Project Overview -->
    <div id="gandak-project" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge canal-badge-green px-3 py-1.5 rounded-pill small">वृहद सिंचाई परियोजना</span>
                    <span class="text-muted small fw-semibold">वाल्मीकिनगर से सारण के मैदानों तक</span>
                </div>
                <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                    गंडक नहर परियोजना (Gandak Canal Project)
                </h2>
                <p class="text-secondary" style="line-height: 1.8;">
                    <strong>गंडक परियोजना</strong> उत्तर भारत की सबसे प्रमुख बहुउद्देश्यीय नदी घाटी सिंचाई परियोजनाओं में से एक है। इसका निर्माण भारत-नेपाल सीमा पर स्थित <strong>वाल्मीकिनगर बराज (भैंसालोटन)</strong> पर किया गया है।
                </p>
                <p class="text-secondary" style="line-height: 1.8;">
                    बराज से दो विशाल नहरें निकलती हैं—पूर्वी तट से <em>तिरहुत मुख्य नहर</em> तथा पश्चिमी तट से <strong>सारण मुख्य नहर (Saran Main Canal)</strong>। यह नहर प्रणाली हिमालयी हिमनदों का जल गोपालगंज व सीवान होते हुए सारण जिले के विस्तृत कृषि मैदानों तक पहुंचाती है।
                </p>

                <div class="canal-feature-box">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-4 mt-n1 flex-shrink-0"></i>
                        <div class="small text-dark fw-medium">
                            <em>"गंडक नहर प्रणाली के विकास से पूर्व सारण में खेती पूरी तरह अनिश्चित मानसूनी वर्षा पर निर्भर थी। इस नहर नेटवर्क ने सारण को धान, गेहूं, मक्का और गन्ने की बंपर पैदावार देने वाले संपन्न कृषि केंद्र में बदल दिया।"</em>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="p-4 rounded-4 bg-light border">
                    <h5 class="fw-bold text-dark font-heading mb-3"><i class="bi bi-gear-wide-connected text-success me-2"></i>तकनीकी एवं प्रशासनिक विशेषताएं</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-3">
                            <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                <i class="bi bi-water"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">हैडवर्क्स (Headworks)</strong>
                                <span class="text-muted small">गंडक नदी पर वाल्मीकिनगर बराज (क्षमता: 8.5 लाख क्यूसेक)।</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                <i class="bi bi-bezier"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">पश्चिमी कमान प्रणाली</strong>
                                <span class="text-muted small">सारण मुख्य नहर गोपालगंज, सीवान एवं सारण जिलों को जल देती है।</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                <i class="bi bi-building-gear"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">प्रशासनिक नियंत्रण</strong>
                                <span class="text-muted small">जल संसाधन विभाग, बिहार सरकार — सारण नहर अंचल, छपरा।</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Major Canals & Branches in Saran -->
    <div id="major-canals" class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge canal-badge-blue px-3 py-1.5 rounded-pill small mb-1">मुख्य जल प्रणालियां</span>
                <h2 class="fw-bold font-heading text-dark display-6 mb-0">सारण की प्रमुख मुख्य एवं शाखा नहरें</h2>
            </div>
            <span class="text-muted small fw-semibold">जिले की प्रमुख सिंचाई धमनियां</span>
        </div>

        <div class="row g-4">
            <!-- 1. Saran Main Canal -->
            <div class="col-lg-4">
                <div class="canal-card h-100 shadow-sm d-flex flex-column">
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #065f46 0%, #047857 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">मुख्य ट्रंक नहर</span>
                            <i class="bi bi-water fs-3 opacity-75"></i>
                        </div>
                        <h3 class="fw-bold font-heading text-white mb-1">सारण मुख्य नहर</h3>
                        <p class="text-white-50 small mb-0">Saran Main Canal</p>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <p class="text-secondary small mb-3" style="line-height: 1.7;">
                            यह मुख्य धमनी नहर है जो सीवान सीमा से सारण के उत्तरी भाग में प्रवेश करती है। यह गंडक नदी का विशाल जलप्रवाह लाकर आगे मढ़ौरा, छपरा और अन्य शाखा नहरों में विभाजित करती है।
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">जल स्रोत:</span>
                                <strong class="text-dark small">गंडक बराज पश्चिमी तट</strong>
                            </div>
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">प्रमुख कमान क्षेत्र:</span>
                                <strong class="text-dark small">मशरक, इसुआपुर, बनियापुर, मढ़ौरा</strong>
                            </div>
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">मुख्य कार्य:</span>
                                <strong class="text-dark small">शाखा नहरों एवं वितरणी रजवाहों को जल आपूर्ति</strong>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <span class="badge bg-light text-success border small"><i class="bi bi-check-circle-fill me-1"></i> मुख्य जलमार्ग वाहिका</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Marhowrah Branch Canal -->
            <div class="col-lg-4">
                <div class="canal-card h-100 shadow-sm d-flex flex-column">
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">मध्य सारण शाखा</span>
                            <i class="bi bi-diagram-2 fs-3 opacity-75"></i>
                        </div>
                        <h3 class="fw-bold font-heading text-white mb-1">मढ़ौरा शाखा नहर</h3>
                        <p class="text-white-50 small mb-0">Marhowrah Branch Canal</p>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <p class="text-secondary small mb-3" style="line-height: 1.7;">
                            मशरक के समीप मुख्य नहर से निकलकर <strong>मढ़ौरा शाखा नहर</strong> इसुआपुर, मढ़ौरा, अमनौर और दरियापुर प्रखंडों से होकर गुजरती है। यह गन्ने, गेहूं और धान की खेती के लिए अत्यंत लाभकारी है।
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">प्रवाह दिशा:</span>
                                <strong class="text-dark small">उत्तर से दक्षिण मध्य कॉरिडोर</strong>
                            </div>
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">प्रमुख प्रखंड:</span>
                                <strong class="text-dark small">मशरक, इसुआपुर, मढ़ौरा, दरियापुर</strong>
                            </div>
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">प्रमुख फसलें:</span>
                                <strong class="text-dark small">गन्ना, धान, गेहूं, हरी सब्जियां</strong>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <span class="badge bg-light text-primary border small"><i class="bi bi-check-circle-fill me-1"></i> वर्षभर सिंचित कमान क्षेत्र</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Chapra Branch Canal -->
            <div class="col-lg-4">
                <div class="canal-card h-100 shadow-sm d-flex flex-column">
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #b45309 0%, #d97706 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-light text-dark fw-bold rounded-pill px-3 py-1">दक्षिणी शाखा</span>
                            <i class="bi bi-geo-alt fs-3 opacity-75"></i>
                        </div>
                        <h3 class="fw-bold font-heading text-white mb-1">छपरा शाखा नहर</h3>
                        <p class="text-white-50 small mb-0">Chapra Branch Canal</p>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <p class="text-secondary small mb-3" style="line-height: 1.7;">
                            दक्षिण दिशा की ओर प्रवाहित होने वाली <strong>छपरा शाखा नहर</strong> जलालपुर, बनियापुर, नगरा और छपरा सदर के ग्रामीण अंचलों को सिंचित करती है तथा भूजल स्तर को समृद्ध बनाए रखती है।
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">प्रवाह दिशा:</span>
                                <strong class="text-dark small">मध्य से दक्षिणवर्ती क्षेत्र</strong>
                            </div>
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">प्रमुख प्रखंड:</span>
                                <strong class="text-dark small">जलालपुर, बनियापुर, नगरा, छपरा सदर</strong>
                            </div>
                            <div class="canal-stat-box">
                                <span class="text-muted small d-block">प्रमुख फसलें:</span>
                                <strong class="text-dark small">धान, मक्का, सरसों, दलहन</strong>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <span class="badge bg-light text-warning border small"><i class="bi bi-check-circle-fill me-1"></i> सघन कमान नेटवर्क</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Distributaries, Minors & Block Coverage -->
    <div id="distributaries" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge canal-badge-green px-3 py-1.5 rounded-pill small">उप-नहर प्रणाली</span>
            <span class="text-muted small fw-semibold">रजवाहा (वितरणी) एवं माइनर नेटवर्क</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            सारण के विभिन्न प्रखंडों में फैली वितरणी नहरें
        </h2>
        <p class="text-secondary lead fs-6 mb-4" style="line-height: 1.8;">
            मुख्य एवं शाखा नहरों से निकलकर उप-वितरणी (रजवाहा) और माइनर नहरें सीधे किसानों के खेतों (कूहल) तक पानी पहुंचाती हैं:
        </p>

        <div class="row g-4">
            <!-- Garkha Distributary -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-success text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-droplet-fill"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">गरखा वितरणी (Garkha Distributary)</h4>
                            <span class="text-muted small">मध्य-दक्षिणी सारण कमान</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        <strong>गरखा</strong> एवं सीमावर्ती <strong>नगरा व मढ़ौरा</strong> के विशाल धान बेल्ट को जल उपलब्ध कराती है। गर्मी के मौसम में यह स्थानीय तालाबों और आहर-पइन को भरकर नमी बनाए रखती है।
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-success me-1"></i> कमान क्षेत्र: गरखा, नगरा, मेहियां, जलालपुर सीमा।
                    </div>
                </div>
            </div>

            <!-- Taraiya & Amnour Minors -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-bezier2"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">तरैया एवं अमनौर नहर नेटवर्क</h4>
                            <span class="text-muted small">उत्तर-पूर्वी कमान प्रणाली</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        <strong>तरैया, पानापुर, इसुआपुर</strong> और <strong>अमनौर</strong> के कृषि क्षेत्रों को सिंचित करती है। यह घोघारी व सोंधी नदी जल निकासी के साथ समन्वय बनाकर जलभराव रोकती है।
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-primary me-1"></i> कमान क्षेत्र: तरैया, पानापुर, अमनौर, इसुआपुर।
                    </div>
                </div>
            </div>

            <!-- Parsa & Dariapur Sub-Canals -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-warning text-dark rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-water"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">परसा एवं दरियापुर माइनर्स</h4>
                            <span class="text-muted small">पूर्वी एवं दक्षिण-पूर्वी नहरें</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        <strong>परसा, मकेर, दरियापुर</strong> तथा नीचे <strong>दिघवारा व सोनपुर</strong> की ओर जल प्रवाह सुनिश्चित करती है, जिससे सब्जी और रबी की फसलों को भारी लाभ मिलता है।
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-warning me-1"></i> कमान क्षेत्र: परसा, दरियापुर, मकेर, दिघवारा।
                    </div>
                </div>
            </div>

            <!-- Ekma & Western Saran Minors -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-info text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-compass"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">एकमा एवं पश्चिमी सारण माइनर्स</h4>
                            <span class="text-muted small">पश्चिमी सीमावर्ती सिंचाई तंत्र</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        सीवान-सारण सीमावर्ती वाहिकाओं से <strong>एकमा, लहलादपुर, मांझी</strong> और <strong>रिविलगंज</strong> के ऊंचे खेतों को सिंचित कर गेहूं, मक्का और दलहन की फसलों को सुरक्षा देती है।
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-info me-1"></i> कमान क्षेत्र: एकमा, लहलादपुर, मांझी, रिविलगंज।
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Agricultural Impact & Seasonal Calendar -->
    <div id="crop-impact" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success text-white fw-bold px-3 py-1.5 rounded-pill small">कृषि एवं मौसमी चक्र</span>
                    <span class="text-muted small fw-semibold">खरीफ, रबी एवं जायद</span>
                </div>
                <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                    सारण में मौसमी नहर जल आपूर्ति एवं फसलों पर प्रभाव
                </h2>
                <p class="text-secondary" style="line-height: 1.8;">
                    जल संसाधन विभाग द्वारा फसलों की बुवाई और सिंचाई की आवश्यकताओं के अनुसार नहरों में पानी छोड़ा जाता है:
                </p>

                <div class="d-flex flex-column gap-3 mb-3">
                    <div class="border-start border-3 border-success ps-3">
                        <strong class="text-dark small d-block">खरीफ मौसम (जुलाई से अक्टूबर):</strong>
                        <span class="text-muted small"><strong>धान (Paddy)</strong> की रोपनी एवं फसल विकास हेतु सभी 20 प्रखंडों की नहरों में पूर्ण क्षमता से पानी आपूर्ति।</span>
                    </div>
                    <div class="border-start border-3 border-warning ps-3">
                        <strong class="text-dark small d-block">रबी मौसम (नवंबर से मार्च):</strong>
                        <span class="text-muted small"><strong>गेहूं, मक्का, सरसों एवं दलहन</strong> के लिए रोस्टर प्रणाली के तहत नियमित रोटेशनल जलापूर्ति।</span>
                    </div>
                    <div class="border-start border-3 border-primary ps-3">
                        <strong class="text-dark small d-block">जायद / ग्रीष्मकालीन फसलें (अप्रैल से जून):</strong>
                        <span class="text-muted small">सब्जी, तरबूज एवं चारे की फसलों हेतु चौड़ों और आहरों में जल संभरण।</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-gradient-primary text-white p-4 rounded-4 shadow-sm text-center">
                    <div class="text-warning fs-1 mb-2"><i class="bi bi-flower1"></i></div>
                    <h4 class="fw-bold font-heading text-white mb-1">हर खेत को पानी</h4>
                    <p class="text-white-50 small mb-3">बिहार सरकार की महत्वाकांक्षी पहल</p>
                    <div class="border-top border-white border-opacity-25 pt-3 text-start small">
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>नहर पक्कीकरण:</strong> रिसाव रोकने हेतु कंक्रीट लाइनिंग कार्य</div>
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>टेल-एंड तक पानी:</strong> अंतिम छोर के किसानों तक जलापूर्ति</div>
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>लागत में कमी:</strong> महंगे डीजल पंपों पर निर्भरता घटी</div>
                        <div class="mb-0"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>भूजल संवर्धन:</strong> वर्षभर कुओं और चापाकलों में जलस्तर स्थिर</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 5: Irrigation Officers Contact Directory (Saran District) -->
    <div id="officers" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white border-top border-4 border-danger">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-1.5 rounded-pill small mb-1">
                    <i class="bi bi-telephone-fill me-1"></i> आधिकारिक संपर्क निर्देशिका
                </span>
                <h2 class="fw-bold font-heading text-dark display-6 mb-0">सारण जिला जल संसाधन एवं नहर अधिकारी संपर्क नंबर</h2>
            </div>
            <a href="https://irrigation.befiqr.in/" target="_blank" rel="noopener" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1.5 fw-bold">
                <i class="bi bi-box-arrow-up-right me-1"></i> जल संसाधन विभाग पोर्टल <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <p class="text-secondary small mb-4" style="line-height: 1.7;">
            जल संसाधन विभाग (WRD), बिहार सरकार के अंतर्गत <strong>सारण जिले</strong> के नहर तंत्र, सिंचित कमान क्षेत्र एवं जल प्रबंधन से संबंधित अंचल व प्रमंडल कार्यालयों के आधिकारिक संपर्क नंबर:
        </p>

        <div class="row g-4 mb-4">
            <!-- 1. S.E., Saran Canal Circle, Chapra -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-white border h-100 d-flex flex-column shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="bg-danger-subtle text-danger rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-building-gear fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark font-heading mb-0 fs-6">अधीक्षण अभियंता (Superintending Engineer)</h5>
                                <span class="text-muted small">सारण नहर अंचल, छपरा</span>
                            </div>
                        </div>
                        <span class="badge bg-danger-subtle text-danger fw-bold rounded-pill px-2.5 py-1 small">अंचल मुख्यालय</span>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <!-- CUG Call Bar -->
                        <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger rounded-circle p-1.5 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-phone text-white" style="font-size: 0.75rem;"></i>
                                </span>
                                <div>
                                    <span class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;">CUG मोबाइल</span>
                                    <span class="fw-bold text-dark font-monospace small">+91 74638 89124</span>
                                </div>
                            </div>
                            <a href="tel:7463889124" class="btn btn-danger btn-sm rounded-pill px-3 py-1 fw-bold fs-7 shadow-sm">
                                <i class="bi bi-telephone-fill me-1"></i> कॉल करें
                            </a>
                        </div>

                        <!-- Secondary Contacts Row -->
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="tel:06152232492" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border text-decoration-none text-dark hover-shadow transition-all h-100">
                                    <i class="bi bi-telephone text-primary fs-6"></i>
                                    <div class="text-truncate">
                                        <span class="text-muted d-block" style="font-size: 0.68rem; line-height: 1;">कार्यालय फोन</span>
                                        <strong class="font-monospace small text-truncate d-block">06152-232492</strong>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="tel:7903124419" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border text-decoration-none text-dark hover-shadow transition-all h-100">
                                    <i class="bi bi-phone text-secondary fs-6"></i>
                                    <div class="text-truncate">
                                        <span class="text-muted d-block" style="font-size: 0.68rem; line-height: 1;">वैकल्पिक मोबाइल</span>
                                        <strong class="font-monospace small text-truncate d-block">7903124419</strong>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Email Bar -->
                        <a href="mailto:sesccchapra@gmail.com" class="d-flex align-items-center justify-content-between p-2 px-2.5 rounded-3 bg-light border text-decoration-none text-secondary hover-shadow transition-all">
                            <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                <i class="bi bi-envelope text-warning fs-6 flex-shrink-0"></i>
                                <span class="small font-monospace text-dark text-truncate">sesccchapra@gmail.com</span>
                            </div>
                            <span class="badge bg-white text-primary border small flex-shrink-0">ईमेल <i class="bi bi-arrow-right-short"></i></span>
                        </a>
                    </div>

                    <div class="mt-auto pt-2 border-top small text-muted">
                        <i class="bi bi-geo-alt text-danger me-1"></i> कार्यक्षेत्र: संपूर्ण सारण नहर अंचल (मुख्यालय छपरा)
                    </div>
                </div>
            </div>

            <!-- 2. E.E., Saran Canal Division, Chapra -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-white border h-100 d-flex flex-column shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="bg-primary-subtle text-primary rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-water fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark font-heading mb-0 fs-6">कार्यपालक अभियंता (Executive Engineer)</h5>
                                <span class="text-muted small">सारण नहर प्रमंडल, छपरा</span>
                            </div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary fw-bold rounded-pill px-2.5 py-1 small">प्रमंडल</span>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <!-- CUG Call Bar -->
                        <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-25">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary rounded-circle p-1.5 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-phone text-white" style="font-size: 0.75rem;"></i>
                                </span>
                                <div>
                                    <span class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;">CUG मोबाइल</span>
                                    <span class="fw-bold text-dark font-monospace small">+91 74638 89604</span>
                                </div>
                            </div>
                            <a href="tel:7463889604" class="btn btn-primary btn-sm rounded-pill px-3 py-1 fw-bold fs-7 shadow-sm">
                                <i class="bi bi-telephone-fill me-1"></i> कॉल करें
                            </a>
                        </div>

                        <!-- Secondary Contacts Row -->
                        <div class="row g-2">
                            <div class="col-12">
                                <a href="tel:7277073652" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border text-decoration-none text-dark hover-shadow transition-all">
                                    <i class="bi bi-phone text-secondary fs-6"></i>
                                    <div class="text-truncate">
                                        <span class="text-muted d-block" style="font-size: 0.68rem; line-height: 1;">वैकल्पिक मोबाइल</span>
                                        <strong class="font-monospace small text-truncate d-block">+91 72770 73652</strong>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Email Bar -->
                        <a href="mailto:eescdchapra@gmail.com" class="d-flex align-items-center justify-content-between p-2 px-2.5 rounded-3 bg-light border text-decoration-none text-secondary hover-shadow transition-all">
                            <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                <i class="bi bi-envelope text-warning fs-6 flex-shrink-0"></i>
                                <span class="small font-monospace text-dark text-truncate">eescdchapra@gmail.com</span>
                            </div>
                            <span class="badge bg-white text-primary border small flex-shrink-0">ईमेल <i class="bi bi-arrow-right-short"></i></span>
                        </a>
                    </div>

                    <div class="mt-auto pt-2 border-top small text-muted">
                        <i class="bi bi-geo-alt text-primary me-1"></i> कार्यक्षेत्र: छपरा शाखा नहर एवं छपरा सदर कमान क्षेत्र
                    </div>
                </div>
            </div>

            <!-- 3. E.E., Saran Canal Division, Marhaura -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-white border h-100 d-flex flex-column shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="bg-success-subtle text-success rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-diagram-2 fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark font-heading mb-0 fs-6">कार्यपालक अभियंता (Executive Engineer)</h5>
                                <span class="text-muted small">सारण नहर प्रमंडल, मढ़ौरा</span>
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-2.5 py-1 small">प्रमंडल</span>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <!-- CUG Call Bar -->
                        <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success rounded-circle p-1.5 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-phone text-white" style="font-size: 0.75rem;"></i>
                                </span>
                                <div>
                                    <span class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;">CUG मोबाइल</span>
                                    <span class="fw-bold text-dark font-monospace small">+91 74638 89600</span>
                                </div>
                            </div>
                            <a href="tel:7463889600" class="btn btn-success btn-sm rounded-pill px-3 py-1 fw-bold fs-7 shadow-sm">
                                <i class="bi bi-telephone-fill me-1"></i> कॉल करें
                            </a>
                        </div>

                        <!-- Secondary Contacts Row -->
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="tel:06159231630" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border text-decoration-none text-dark hover-shadow transition-all h-100">
                                    <i class="bi bi-telephone text-primary fs-6"></i>
                                    <div class="text-truncate">
                                        <span class="text-muted d-block" style="font-size: 0.68rem; line-height: 1;">कार्यालय फोन</span>
                                        <strong class="font-monospace small text-truncate d-block">06159-231630</strong>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="tel:9931209711" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border text-decoration-none text-dark hover-shadow transition-all h-100">
                                    <i class="bi bi-phone text-secondary fs-6"></i>
                                    <div class="text-truncate">
                                        <span class="text-muted d-block" style="font-size: 0.68rem; line-height: 1;">वैकल्पिक मोबाइल</span>
                                        <strong class="font-monospace small text-truncate d-block">9931209711</strong>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Email Bar -->
                        <a href="mailto:eescd.mrh@gmail.com" class="d-flex align-items-center justify-content-between p-2 px-2.5 rounded-3 bg-light border text-decoration-none text-secondary hover-shadow transition-all">
                            <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                <i class="bi bi-envelope text-warning fs-6 flex-shrink-0"></i>
                                <span class="small font-monospace text-dark text-truncate">eescd.mrh@gmail.com</span>
                            </div>
                            <span class="badge bg-white text-primary border small flex-shrink-0">ईमेल <i class="bi bi-arrow-right-short"></i></span>
                        </a>
                    </div>

                    <div class="mt-auto pt-2 border-top small text-muted">
                        <i class="bi bi-geo-alt text-success me-1"></i> कार्यक्षेत्र: मढ़ौरा शाखा नहर, अमनौर, इसुआपुर, दरियापुर कमान
                    </div>
                </div>
            </div>

            <!-- 4. E.E., Saran Canal Division, Ekma -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-white border h-100 d-flex flex-column shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="bg-warning-subtle text-warning-emphasis rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-compass fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark font-heading mb-0 fs-6">कार्यपालक अभियंता (Executive Engineer)</h5>
                                <span class="text-muted small">सारण नहर प्रमंडल, एकमा</span>
                            </div>
                        </div>
                        <span class="badge bg-warning-subtle text-warning-emphasis fw-bold rounded-pill px-2.5 py-1 small">प्रमंडल</span>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <!-- CUG Call Bar -->
                        <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-warning text-dark rounded-circle p-1.5 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-phone" style="font-size: 0.75rem;"></i>
                                </span>
                                <div>
                                    <span class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;">CUG मोबाइल</span>
                                    <span class="fw-bold text-dark font-monospace small">+91 74638 89602</span>
                                </div>
                            </div>
                            <a href="tel:7463889602" class="btn btn-warning text-dark btn-sm rounded-pill px-3 py-1 fw-bold fs-7 shadow-sm">
                                <i class="bi bi-telephone-fill me-1"></i> कॉल करें
                            </a>
                        </div>

                        <!-- Secondary Contacts Row -->
                        <div class="row g-2">
                            <div class="col-12">
                                <a href="tel:06155231546" class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light border text-decoration-none text-dark hover-shadow transition-all">
                                    <i class="bi bi-telephone text-primary fs-6"></i>
                                    <div class="text-truncate">
                                        <span class="text-muted d-block" style="font-size: 0.68rem; line-height: 1;">कार्यालय फोन (लैंडलाइन)</span>
                                        <strong class="font-monospace small text-truncate d-block">06155-231546</strong>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Email Bar -->
                        <a href="mailto:scdivekma@gmail.com" class="d-flex align-items-center justify-content-between p-2 px-2.5 rounded-3 bg-light border text-decoration-none text-secondary hover-shadow transition-all">
                            <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                <i class="bi bi-envelope text-warning fs-6 flex-shrink-0"></i>
                                <span class="small font-monospace text-dark text-truncate">scdivekma@gmail.com</span>
                            </div>
                            <span class="badge bg-white text-primary border small flex-shrink-0">ईमेल <i class="bi bi-arrow-right-short"></i></span>
                        </a>
                    </div>

                    <div class="mt-auto pt-2 border-top small text-muted">
                        <i class="bi bi-geo-alt text-warning me-1"></i> कार्यक्षेत्र: एकमा, मांझी, लहलादपुर एवं पश्चिमी सारण कमान
                    </div>
                </div>
            </div>
        </div>

        <!-- 24x7 WRD Helpline Banner -->
        <div class="p-3.5 rounded-4 bg-danger-subtle border border-danger-subtle d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-danger text-white rounded-circle p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="bi bi-headset fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-danger mb-0">जल संसाधन विभाग 24x7 बाढ़ व सिंचाई नियंत्रण कक्ष हेल्पलाइन (BeFIQR)</h6>
                    <span class="text-secondary small">नहर तटबंध रिसाव, गाद या जलापूर्ति संबंधी किसी भी समस्या की त्वरित सूचना हेतु</span>
                </div>
            </div>
            <a href="tel:18003456145" class="btn btn-danger btn-sm rounded-pill px-3 py-2 fw-bold text-nowrap align-self-start align-self-sm-center shadow-sm">
                <i class="bi bi-telephone-outbound me-1"></i> 1800 3456 145
            </a>
        </div>
    </div>

    <!-- Section 6: Official Sources & Hydrology References -->
    <div id="sources" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white border-top border-4 border-success">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-success-subtle text-success fw-bold px-3 py-1.5 rounded-pill small mb-1">प्रमाणिकता एवं संदर्भ</span>
                <h3 class="fw-bold font-heading text-dark display-6 mb-0">आधिकारिक स्रोत एवं सिंचाई संदर्भ</h3>
            </div>
            <a href="sources" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-bold">
                <i class="bi bi-shield-check me-1"></i> सभी संदर्भ पोर्टल देखें <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <p class="text-secondary small mb-4" style="line-height: 1.7;">
            सारण जिले का नहर नेटवर्क, गंडक परियोजना विवरण एवं सिंचाई कमान आंकड़े आधिकारिक सरकारी जल संसाधन एवं सिंचाई विभागों से संदर्भित हैं:
        </p>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-water text-success fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">जल संसाधन विभाग, बिहार सरकार (WRD)</strong>
                            <span class="text-muted small">सारण मुख्य नहर, मढ़ौरा शाखा नहर, छपरा शाखा नहर एवं गंडक नहर अंचल छपरा का आधिकारिक सिंचाई डेटा। (<a href="https://wrd.bihar.gov.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">wrd.bihar.gov.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-diagram-3-fill text-primary fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">प्रधानमंत्री कृषि सिंचाई योजना (PMKSY)</strong>
                            <span class="text-muted small">सारण जिले की जिला सिंचाई योजना (DIP) — कमान क्षेत्र विकास एवं जल उपयोग दक्षता रिकॉर्ड।</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-speedometer2 text-info fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">केंद्रीय जल आयोग (CWC)</strong>
                            <span class="text-muted small">गंडक बराज से जल आवंटन, अंतर्राज्यीय जल बंटवारा एवं नहर जलप्रवाह डेटा। (<a href="https://cwc.gov.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">cwc.gov.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-building text-warning fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">सारण जिला प्रशासन (saran.nic.in)</strong>
                            <span class="text-muted small">सभी 20 प्रखंडों में कृषि विभाग एवं लघु सिंचाई प्रभाग के आधिकारिक अभिलेख। (<a href="https://saran.nic.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">saran.nic.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Banner -->
    <div class="card border-0 shadow rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #064e3b 0%, #047857 100%);">
        <div class="card-body p-4 p-md-5 text-white text-center">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
                <i class="bi bi-compass me-1"></i> सारण जिले को डिजिटली जानिए
            </span>
            <h3 class="fw-bold font-heading text-white display-6 mb-3">
                सारण के सभी 20 प्रखंडों एवं नहर कमान क्षेत्रों से जुड़ें
            </h3>
            <p class="text-white-50 lead fs-6 mx-auto mb-4" style="max-width: 700px;">
                सारण इंडेक्स पर जिले के सभी 20 प्रखंड, नदियां, आपातकालीन नंबर, ऐतिहासिक धरोहर और स्थानीय व्यवसाय खोजें।
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="blocks" class="btn btn-warning text-dark fw-bold px-4 py-2.5 rounded-pill shadow">
                    <i class="bi bi-geo-alt-fill me-1"></i> सभी 20 प्रखंड देखें
                </a>
                <a href="river" class="btn btn-outline-light fw-bold px-4 py-2.5 rounded-pill">
                    <i class="bi bi-water me-1"></i> सारण की नदियां
                </a>
                <a href="history" class="btn btn-light text-success fw-bold px-4 py-2.5 rounded-pill shadow">
                    <i class="bi bi-hourglass-split me-1"></i> सारण का इतिहास
                </a>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

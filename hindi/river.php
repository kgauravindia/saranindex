<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "सारण जिले की प्रमुख नदियां (गंगा, घाघरा/सरयू, गंडक एवं उपनदियां) – सारण इंडेक्स";
$meta_description = "सारण जिले (छपरा, बिहार) की सभी नदियों का संपूर्ण विवरण। पवित्र गंगा, घाघरा (सरयू), गंडक (नारायणी), माही, खौरा, झरही, सोंधी नदियां, पावन संगम स्थल (सोनपुर व डोरीगंज), दियारा क्षेत्र एवं नदीय पुल।";
$meta_keywords = "सारण की नदियां, छपरा की नदियां, गंगा नदी छपरा, घाघरा सरयू नदी, गंडक नारायणी नदी, माही नदी सारण, सोनपुर संगम, डोरीगंज संगम, आरा छपरा पुल, जेपी सेतु सोनपुर, सारण दियारा";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom Styles for River Page -->
<style>
.river-hero-bg {
    background: linear-gradient(135deg, #0c2d48 0%, #145da0 50%, #0e86d4 100%);
    color: #ffffff;
}
.river-card {
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    background: #ffffff;
    overflow: hidden;
}
.river-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 35px -10px rgba(14, 134, 212, 0.2) !important;
    border-color: #7dd3fc;
}
.river-badge-blue {
    background: #e0f2fe;
    color: #0369a1;
    font-weight: 700;
}
.river-badge-teal {
    background: #ccfbf1;
    color: #0f766e;
    font-weight: 700;
}
.river-badge-amber {
    background: #fef3c7;
    color: #b45309;
    font-weight: 700;
}
.river-stat-box {
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
    background: #0284c7;
    color: #ffffff;
    border-color: #0284c7;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
}
.confluence-box {
    background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%);
    border-left: 5px solid #0ea5e9;
    border-radius: 16px;
    padding: 1.5rem;
}
.bridge-card {
    border-radius: 16px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    transition: all 0.25s ease;
}
.bridge-card:hover {
    border-color: #38bdf8;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.06);
}
</style>

<!-- Hero Section -->
<div class="river-hero-bg py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-4 text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
            <i class="bi bi-water me-1"></i> सारण की जीवनदायिनी जलधाराएं (Rivers of Saran)
        </span>
        <h1 class="fw-bold font-heading text-white display-5 mb-2">
            सारण जिले की प्रमुख नदियां
        </h1>
        <div class="lead text-warning fw-semibold mb-3">
            गंगा, घाघरा (सरयू), गंडक (नारायणी) एवं उपनदियों का पावन प्रवाह
        </div>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 840px;">
            भारत की तीन सबसे पवित्र और विशाल नदियों—<strong>दक्षिण में गंगा</strong>, <strong>दक्षिण-पश्चिम में घाघरा (सरयू)</strong> तथा <strong>पूर्व में गंडक (नारायणी)</strong> से घिरा सारण जिला उपजाऊ जलोढ़ मैदानों, पवित्र संगमों और समृद्ध दियारा संस्कृति की धरती है।
        </p>

        <!-- Quick Jump Navigation -->
        <div class="d-flex justify-content-center gap-2 flex-wrap mb-2">
            <a href="#major-rivers" class="quick-nav-pill"><i class="bi bi-tsunami text-primary"></i> 3 प्रमुख सीमावर्ती नदियां</a>
            <a href="#tributaries" class="quick-nav-pill"><i class="bi bi-droplet-half text-info"></i> उपनदियां व जलधाराएं</a>
            <a href="#confluences" class="quick-nav-pill"><i class="bi bi-arrows-collapse text-success"></i> पावन संगम स्थल</a>
            <a href="#bridges" class="quick-nav-pill"><i class="bi bi-bounding-box-circles text-warning"></i> प्रमुख नदीय पुल</a>
            <a href="#diara-ecology" class="quick-nav-pill"><i class="bi bi-tree-fill text-teal"></i> दियारा व कृषि संपदा</a>
            <a href="#sources" class="quick-nav-pill"><i class="bi bi-shield-check text-success"></i> आधिकारिक स्रोत</a>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="container py-5">

    <!-- KPI Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-primary">
                <div class="text-primary fs-1 mb-2"><i class="bi bi-water"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">3 महान नदियां</h3>
                <p class="text-muted small mb-0 fw-semibold">प्राकृतिक जिले की सीमाएं</p>
                <div class="mt-2 text-primary small fs-7 fw-bold">गंगा • सरयू • गंडक</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-success">
                <div class="text-success fs-1 mb-2"><i class="bi bi-arrows-collapse"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">2 पवित्र संगम</h3>
                <p class="text-muted small mb-0 fw-semibold">धार्मिक एवं ऐतिहासिक तीर्थ</p>
                <div class="mt-2 text-success small fs-7 fw-bold">सोनपुर व डोरीगंज संगम</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-info">
                <div class="text-info fs-1 mb-2"><i class="bi bi-droplet-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">6+ नदियां व जलधाराएं</h3>
                <p class="text-muted small mb-0 fw-semibold">आंतरिक सिंचाई व जल निकासी</p>
                <div class="mt-2 text-info small fs-7 fw-bold">घोघारी, माही, खौरा, सोंधी</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-warning">
                <div class="text-warning fs-1 mb-2"><i class="bi bi-layers-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">दियारा क्षेत्र</h3>
                <p class="text-muted small mb-0 fw-semibold">अति उपजाऊ जलोढ़ माटी</p>
                <div class="mt-2 text-warning small fs-7 fw-bold">कृषि व दलहन-सब्जी केंद्र</div>
            </div>
        </div>
    </div>

    <!-- Section 1: 3 Major Boundary Rivers of Saran -->
    <div id="major-rivers" class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge river-badge-blue px-3 py-1.5 rounded-pill small mb-1">सदाबहार जीवनधाराएं</span>
                <h2 class="fw-bold font-heading text-dark display-6 mb-0">सारण की तीन प्रमुख सीमावर्ती नदियां</h2>
            </div>
            <span class="text-muted small fw-semibold">सारण के भूगोल की पहचान</span>
        </div>

        <div class="row g-4">
            <!-- 1. Ganga River -->
            <div class="col-lg-4">
                <div class="river-card h-100 shadow-sm d-flex flex-column">
                    <div class="p-4 bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">दक्षिणी सीमा</span>
                            <i class="bi bi-water fs-3 opacity-75"></i>
                        </div>
                        <h3 class="fw-bold font-heading text-white mb-1">गंगा नदी (Ganga River)</h3>
                        <p class="text-white-50 small mb-0">भारत की सबसे पवित्र नदी</p>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <p class="text-secondary small mb-3" style="line-height: 1.7;">
                            पवित्र <strong>गंगा नदी</strong> सारण जिले की संपूर्ण दक्षिणी सीमा बनाती है। यह पश्चिम से पूर्व की ओर बहती हुई सारण को <strong>भोजपुर (आरा)</strong> और <strong>पटना</strong> जिलों से अलग करती है। डोरीगंज में सरयू और सोनपुर में गंडक नदी गंगा में समाहित होती हैं।
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">सीमा विस्तार:</span>
                                <strong class="text-dark small">सारण की दक्षिणी सीमा (~65 किमी)</strong>
                            </div>
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">प्रमुख तटीय प्रखंड:</span>
                                <strong class="text-dark small">छपरा, डोरीगंज, दिघवारा, सोनपुर</strong>
                            </div>
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">प्रमुख घाट एवं तीर्थ:</span>
                                <strong class="text-dark small">आमी अंबिका भवानी घाट, सेमरिया घाट, सोनपुर घाट, चिरांद</strong>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <span class="badge bg-light text-primary border small"><i class="bi bi-check-circle-fill me-1"></i> सदानीरा एवं नौगम्य जलमार्ग</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Ghaghra / Sarayu River -->
            <div class="col-lg-4">
                <div class="river-card h-100 shadow-sm d-flex flex-column">
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">दक्षिण-पश्चिमी सीमा</span>
                            <i class="bi bi-tsunami fs-3 opacity-75"></i>
                        </div>
                        <h3 class="fw-bold font-heading text-white mb-1">घाघरा / सरयू (Ghaghra River)</h3>
                        <p class="text-white-50 small mb-0">अयोध्या एवं छपरा नगर की जीवनधारा</p>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <p class="text-secondary small mb-3" style="line-height: 1.7;">
                            बिहार-यूपी सीमा पर <strong>मांझी</strong> के निकट सारण में प्रवेश करने वाली <strong>घाघरा (सरयू)</strong> दक्षिण-पश्चिमी सीमा पर बहती है। <strong>छपरा नगर</strong> इसी के उत्तरी तट पर स्थित है। यह रिविलगंज और डोरीगंज के समीप गंगा में विलीन होती है।
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">उद्गम स्थल:</span>
                                <strong class="text-dark small">तिब्बत का पठार (मापचाचुंगो हिमनद)</strong>
                            </div>
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">प्रमुख तटीय प्रखंड:</span>
                                <strong class="text-dark small">मांझी, रिविलगंज, छपरा सदर</strong>
                            </div>
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">ऐतिहासिक स्थल:</span>
                                <strong class="text-dark small">गौतम ऋषि आश्रम (गोदना), मांझी का प्राचीन किला, सिताब दियारा</strong>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <span class="badge bg-light text-info border small"><i class="bi bi-check-circle-fill me-1"></i> बिहार में विशालतम जलप्रवाह वाली नदी</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Gandak / Narayani River -->
            <div class="col-lg-4">
                <div class="river-card h-100 shadow-sm d-flex flex-column">
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">पूर्वी सीमा</span>
                            <i class="bi bi-gem fs-3 opacity-75"></i>
                        </div>
                        <h3 class="fw-bold font-heading text-white mb-1">गंडक / नारायणी (Gandak River)</h3>
                        <p class="text-white-50 small mb-0">शालीग्राम शिलाओं की पावन नदी</p>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <p class="text-secondary small mb-3" style="line-height: 1.7;">
                            सारण की संपूर्ण पूर्वी सीमा बनाने वाली <strong>गंडक नदी (बड़ी गंडक / नारायणी)</strong> सारण को <strong>मुजफ्फरपुर</strong> एवं <strong>वैशाली</strong> जिलों से पृथक करती है। यह सारण नहर प्रणाली को जल प्रदान कर सोनपुर में गंगा से मिलती है।
                        </p>

                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">उद्गम स्थल:</span>
                                <strong class="text-dark small">नेपाल हिमालय (त्रिवेणी संगम हिमनद)</strong>
                            </div>
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">प्रमुख तटीय प्रखंड:</span>
                                <strong class="text-dark small">पानापुर, तरैया, मकेर, परसा, दरियापुर, सोनपुर</strong>
                            </div>
                            <div class="river-stat-box">
                                <span class="text-muted small d-block">ऐतिहासिक स्थल:</span>
                                <strong class="text-dark small">रेवा घाट, बाबा हरिहरनाथ मंदिर, सोनपुर पशु मेला</strong>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <span class="badge bg-light text-success border small"><i class="bi bi-check-circle-fill me-1"></i> मुख्य नहर सिंचाई का प्रमुख स्रोत</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Minor Rivers, Streams & Internal Drainage -->
    <div id="tributaries" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge river-badge-teal px-3 py-1.5 rounded-pill small">आंतरिक जल निकासी तंत्र</span>
            <span class="text-muted small fw-semibold">उपनदियां, नहरें एवं चौर प्रवाह</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            सारण जिले की उपनदियां एवं आंतरिक जलधाराएं
        </h2>
        <p class="text-secondary lead fs-6 mb-4" style="line-height: 1.8;">
            तीन विशाल सीमावर्ती नदियों के अतिरिक्त सारण के भीतरी ग्रामीण अंचलों में कई ऐतिहासिक उपनदियां और बरसाती नदियां प्रवाहित होती हैं:
        </p>

        <div class="row g-4">
            <!-- Mahi River -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-droplet-half"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">माही नदी (Mahi River)</h4>
                            <span class="text-muted small">मध्य सारण की प्रमुख नदी</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        <strong>माही नदी</strong> सारण-सीवान सीमा से निकलकर मध्य सारण के <strong>बनियापुर, जलालपुर, गरखा, मढ़ौरा</strong> और <strong>दरियापुर</strong> प्रखंडों से होकर बहती है। यह सैकड़ों गांवों के खेतों की सिंचाई और भूजल पुनर्भरण का मुख्य आधार है।
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-primary me-1"></i> लाभान्वित प्रखंड: बनियापुर, जलालपुर, गरखा, मढ़ौरा, दरियापुर।
                    </div>
                </div>
            </div>

            <!-- Khaura / Khatsa River -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-success text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-water"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">खौरा नदी (Khaura River)</h4>
                            <span class="text-muted small">उत्तर-मध्य मौसमी जलधारा</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        <strong>खौरा नदी</strong> <strong>एकमा, लहलादपुर, बनियापुर</strong> और आसपास के क्षेत्रों से गुजरती है। मानसून में यह अतिरिक्त जल को प्राकृतिक चौड़ों (Chours) तक पहुंचाकर धान की फसलों को सिंचित करती है।
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-success me-1"></i> लाभान्वित प्रखंड: एकमा, लहलादपुर, बनियापुर, नगरा।
                    </div>
                </div>
            </div>

            <!-- Jharahi / Dhanai River -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-info text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-compass"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">झरही एवं धनई नदियां</h4>
                            <span class="text-muted small">पश्चिमी सारण कछार</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        सीवान से सारण के <strong>मांझी</strong> और <strong>एकमा</strong> सीमा में प्रवेश करने वाली ये जलधाराएं सरयू बेसिन के साथ मिलकर पश्चिमी सारण के तालाबों और आर्द्रभूमियों को समृद्ध करती हैं।
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-info me-1"></i> लाभान्वित प्रखंड: मांझी, एकमा, रिविलगंज।
                    </div>
                </div>
            </div>

            <!-- Ghoghari River -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary-subtle text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-water"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">घोघारी नदी (Ghoghari River)</h4>
                            <span class="text-muted small">उत्तर व मध्य सारण की प्रमुख कृषि जीवनधारा</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        <strong>घोघारी नदी</strong> उत्तर सारण में प्रवेश कर <strong>मशरक, पानापुर, तरैया</strong> और <strong>मढ़ौरा (Madhaurah)</strong> प्रखंडों से होकर बहती है तथा आगे अमनौर की ओर जल निकासी तंत्र से जुड़ती है। यह हजारों किसानों के लिए प्राकृतिक सिंचाई का मुख्य आधार है और मानसूनी जलभराव को चौड़ों तक ले जाती है।
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-primary me-1"></i> लाभान्वित प्रखंड: मशरक, पानापुर, तरैया, मढ़ौरा, अमनौर।
                    </div>
                </div>
            </div>

            <!-- Sondhi / Gandaki River -->
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-warning text-dark rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-bezier2"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">सोंधी / गंडकी नदी</h4>
                            <span class="text-muted small">गंडक की पुरानी शाखा</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.7;">
                        <strong>सोंधी नदी</strong> <strong>तरैया, अमनौर, परसा</strong> और <strong>दरियापुर</strong> से गुजरती है। यह गंडक की प्राचीन शाखा है जो हरदिया चौर सहित कई बड़े जलाशयों को जोड़ती है तथा मत्स्य पालन और रबी फसलों के लिए वरदान है।
                    </p>
                    <div class="border-top pt-2 small text-muted">
                        <i class="bi bi-geo-alt text-warning me-1"></i> लाभान्वित प्रखंड: तरैया, अमनौर, परसा, मकेर।
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Sacred Confluences (Sangams) -->
    <div id="confluences" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-1.5 rounded-pill small">पावन संगम तीर्थ</span>
            <span class="text-muted small fw-semibold">पौराणिक एवं आध्यात्मिक महत्व</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-4">
            सारण के दो महान पवित्र नदी संगम (Sangams)
        </h2>

        <div class="row g-4">
            <!-- 1. Harihar Kshetra Sonpur Sangam -->
            <div class="col-lg-6">
                <div class="confluence-box h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary text-white rounded-circle p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                            <i class="bi bi-sun-fill fs-5"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">गंडक-गंगा संगम (हरिहर क्षेत्र, सोनपुर)</h4>
                            <span class="text-muted small">सोनपुर (Sonpur)</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.8;">
                        सोनपुर में <strong>गंडक (नारायणी) और गंगा</strong> का संगम सनातन संस्कृति के सबसे पवित्र तीर्थों में से एक है। <em>श्रीमद्भागवत महापुराण</em> के अनुसार यह <strong>गजेंद्र मोक्ष</strong> की ऐतिहासिक पावन भूमि है।
                    </p>
                    <ul class="text-secondary small mb-0" style="line-height: 1.7;">
                        <li><strong>कार्तिक पूर्णिमा स्नान:</strong> प्रतिवर्ष लाखों श्रद्धालु इस संगम में आस्था की डुबकी लगाते हैं।</li>
                        <li><strong>विश्वप्रसिद्ध सोनपुर मेला:</strong> संगम तट पर एशिया का सबसे बड़ा ऐतिहासिक पशु एवं सांस्कृतिक मेला आयोजित होता है।</li>
                        <li><strong>बाबा हरिहरनाथ मंदिर:</strong> भगवान विष्णु (हरि) और भगवान शिव (हर) का अनूठा संयुक्त मंदिर।</li>
                    </ul>
                </div>
            </div>

            <!-- 2. Sarayu-Ganga Sangam Doriganj/Semaria -->
            <div class="col-lg-6">
                <div class="confluence-box h-100" style="background: linear-gradient(135deg, #eff6ff 0%, #fef3c7 100%); border-left-color: #f59e0b;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-warning text-dark rounded-circle p-2.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                            <i class="bi bi-tsunami fs-5"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark font-heading mb-0">सरयू-गंगा संगम (डोरीगंज व सेमरिया)</h4>
                            <span class="text-muted small">डोरीगंज / सेमरिया घाट / रिविलगंज</span>
                        </div>
                    </div>
                    <p class="text-secondary small mb-3" style="line-height: 1.8;">
                        अयोध्या से प्रवाहित होने वाली <strong>घाघरा (सरयू)</strong> डोरीगंज और सेमरिया घाट के समीप <strong>गंगा</strong> में समाहित होती है। यह संगम रामायण और वैदिक काल से जुड़ा है।
                    </p>
                    <ul class="text-secondary small mb-0" style="line-height: 1.7;">
                        <li><strong>महर्षि गौतम तपोभूमि:</strong> गोदना (रिविलगंज) का गौतम आश्रम इसी संगम बेसिन के तट पर स्थित है।</li>
                        <li><strong>चिरांद पुरातात्विक टीला:</strong> संगम से थोड़ी दूरी पर 4500 वर्ष पुरानी चिरांद सभ्यता के अवशेष स्थित हैं।</li>
                        <li><strong>आरा-छपरा संपर्क:</strong> वीर कुंवर सिंह सेतु इसी विशाल संगम क्षेत्र के ऊपर निर्मित है।</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Major River Bridges Connecting Saran -->
    <div id="bridges" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill small">इंजीनियरिंग एवं संपर्क मार्ग</span>
            <span class="text-muted small fw-semibold">सारण की नदियों पर बने प्रमुख पुल</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-4">
            सारण जिले को जोड़ने वाले प्रमुख नदीय सेतु (Bridges)
        </h2>

        <div class="row g-4">
            <!-- 1. Arrah-Chapra Bridge -->
            <div class="col-md-6 col-lg-3">
                <div class="bridge-card p-4 h-100 shadow-sm">
                    <div class="text-primary fs-2 mb-2"><i class="bi bi-bezier"></i></div>
                    <h5 class="fw-bold text-dark font-heading mb-1">वीर कुंवर सिंह सेतु</h5>
                    <span class="badge bg-primary-subtle text-primary small mb-3">आरा – छपरा 4-लेन पुल</span>
                    <p class="text-secondary small mb-2" style="line-height: 1.6;">
                        <strong>डोरीगंज</strong> के पास गंगा पर स्थित यह पुल सारण को सीधे <strong>भोजपुर (आरा)</strong> और दक्षिण बिहार से जोड़ता है।
                    </p>
                    <span class="text-muted small fw-semibold d-block mt-auto"><i class="bi bi-water text-primary me-1"></i> गंगा नदी पर निर्मित</span>
                </div>
            </div>

            <!-- 2. JP Setu Sonpur-Digha -->
            <div class="col-md-6 col-lg-3">
                <div class="bridge-card p-4 h-100 shadow-sm">
                    <div class="text-success fs-2 mb-2"><i class="bi bi-train-front"></i></div>
                    <h5 class="fw-bold text-dark font-heading mb-1">जेपी गंगा सेतु</h5>
                    <span class="badge bg-success-subtle text-success small mb-3">दीघा – सोनपुर रेल-सड़क पुल</span>
                    <p class="text-secondary small mb-2" style="line-height: 1.6;">
                        गंगा नदी पर 4.55 किमी लंबा विशाल रेल-सह-सड़क सेतु जो <strong>सोनपुर (सारण)</strong> को राजधानी <strong>पटना (दीघा)</strong> से जोड़ता है।
                    </p>
                    <span class="text-muted small fw-semibold d-block mt-auto"><i class="bi bi-water text-success me-1"></i> गंगा नदी पर निर्मित</span>
                </div>
            </div>

            <!-- 3. Manjhi Bridge -->
            <div class="col-md-6 col-lg-3">
                <div class="bridge-card p-4 h-100 shadow-sm">
                    <div class="text-warning fs-2 mb-2"><i class="bi bi-signpost-2-fill"></i></div>
                    <h5 class="fw-bold text-dark font-heading mb-1">मांझी सरयू सेतु</h5>
                    <span class="badge bg-warning-subtle text-warning-emphasis small mb-3">बिहार – यूपी सीमा पुल</span>
                    <p class="text-secondary small mb-2" style="line-height: 1.6;">
                        <strong>मांझी</strong> के पास घाघरा (सरयू) नदी पर बना पुल जो सारण (बिहार) को <strong>बलिया (उत्तर प्रदेश)</strong> से NH-31 पर जोड़ता है।
                    </p>
                    <span class="text-muted small fw-semibold d-block mt-auto"><i class="bi bi-water text-warning me-1"></i> सरयू नदी पर निर्मित</span>
                </div>
            </div>

            <!-- 4. Rewa Ghat & Sonpur-Hajipur Bridges -->
            <div class="col-md-6 col-lg-3">
                <div class="bridge-card p-4 h-100 shadow-sm">
                    <div class="text-info fs-2 mb-2"><i class="bi bi-link-45deg"></i></div>
                    <h5 class="fw-bold text-dark font-heading mb-1">गंडक नदी पुल</h5>
                    <span class="badge bg-info-subtle text-info small mb-3">सोनपुर-हाजीपुर व रेवा घाट</span>
                    <p class="text-secondary small mb-2" style="line-height: 1.6;">
                        गंडक नदी पर स्थित पुल जो <strong>सोनपुर को हाजीपुर (वैशाली)</strong> तथा <strong>रेवा घाट पुल</strong> सारण को मुजफ्फरपुर से जोड़ते हैं।
                    </p>
                    <span class="text-muted small fw-semibold d-block mt-auto"><i class="bi bi-water text-info me-1"></i> गंडक नदी पर निर्मित</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 5: The Diara Ecosystem & Agriculture -->
    <div id="diara-ecology" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success text-white fw-bold px-3 py-1.5 rounded-pill small">पारिस्थितिकी एवं कृषि</span>
                    <span class="text-muted small fw-semibold">मिट्टी, फसलें एवं जीवन</span>
                </div>
                <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                    सारण का दियारा पारिस्थितिकी तंत्र एवं कृषि संपदा
                </h2>
                <p class="text-secondary" style="line-height: 1.8;">
                    तीन विशाल गाद-युक्त नदियों से घिरे होने के कारण सारण का दक्षिणी व पश्चिमी भाग प्रसिद्ध <strong>दियारा (Diara)</strong> क्षेत्र का निर्माण करता है।
                </p>
                <ul class="text-secondary small mb-3" style="line-height: 1.8;">
                    <li><strong>प्राकृतिक गाद नवीनीकरण:</strong> मानसूनी बाढ़ हर वर्ष नई उपजाऊ जलोढ़ मिट्टी (Alluvial Silt) बिछाती है, जिससे रासायनिक खाद के बिना बंपर पैदावार होती है।</li>
                    <li><strong>प्रमुख फसलें:</strong> दियारा क्षेत्र में <strong>गेहूं, मक्का, सरसों, तरबूज, खरबूजा, परवल, ककड़ी एवं हरी सब्जियां</strong> प्रचुर मात्रा में उगाई जाती हैं।</li>
                    <li><strong>प्रसिद्ध दियारा क्षेत्र:</strong> सिताब दियारा (लोकनायक जेपी की जन्मस्थली), मांझी दियारा, रिविलगंज दियारा, छपरा दियारा, डोरीगंज दियारा एवं सोनपुर दियारा।</li>
                </ul>

                <div class="p-3 bg-light rounded-3 border">
                    <strong class="text-dark small d-block mb-1"><i class="bi bi-shield-exclamation text-warning me-1"></i> बाढ़ नियंत्रण एवं सुरक्षा तटबंध:</strong>
                    <span class="text-muted small">
                        नदियों की उपजाऊ मिट्टी के साथ मानसूनी जलभराव से सुरक्षा हेतु बिहार सरकार के जल संसाधन विभाग द्वारा सारण तटबंधों का संधारण किया जाता है।
                    </span>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-gradient-primary text-white p-4 rounded-4 shadow-sm text-center">
                    <div class="text-warning fs-1 mb-2"><i class="bi bi-flower2"></i></div>
                    <h4 class="fw-bold font-heading text-white mb-1">सारण का हरित अन्न भंडार</h4>
                    <p class="text-white-50 small mb-3">नदियों से सिंचित कृषि स्वर्ग</p>
                    <div class="border-top border-white border-opacity-25 pt-3 text-start small">
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>मृदा प्रकार:</strong> उपजाऊ बलुई दोमट जलोढ़ मिट्टी</div>
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>मुख्य फसलें:</strong> मक्का, तरबूज, परवल, गेहूं, सब्जियां</div>
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>नहर नेटवर्क:</strong> गंडक मुख्य नहर एवं सारण नहरें</div>
                        <div class="mb-0"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>जलाशय:</strong> हरदिया चौर, बसैठा चौर व झीलें</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 6: Official Sources & Hydrology References -->
    <div id="sources" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white border-top border-4 border-info">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-info-subtle text-info fw-bold px-3 py-1.5 rounded-pill small mb-1">प्रमाणिकता एवं जल संसाधन डेटा</span>
                <h3 class="fw-bold font-heading text-dark display-6 mb-0">आधिकारिक जल संसाधन एवं नदी डेटा स्रोत</h3>
            </div>
            <a href="sources" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-bold">
                <i class="bi bi-shield-check me-1"></i> सभी संदर्भ पोर्टल देखें <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <p class="text-secondary small mb-4" style="line-height: 1.7;">
            सारण जिले के नदी बेसिन, सिंचाई नहर नेटवर्क, जलस्तर आंकड़े और बाढ़ प्रबंधन संबंधी विवरण आधिकारिक सरकारी जल संसाधन एवं पर्यावरण एजेंसियों के संदर्भ पर आधारित हैं:
        </p>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-water text-info fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">जल संसाधन विभाग, बिहार सरकार (WRD)</strong>
                            <span class="text-muted small">सारण जिले के नदी तटबंधों, गंडक मुख्य व शाखा नहरों तथा बाढ़ सुरक्षा का आधिकारिक डेटा। (<a href="https://wrd.bihar.gov.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">wrd.bihar.gov.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-droplet-half text-success fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">राष्ट्रीय स्वच्छ गंगा मिशन (NMCG)</strong>
                            <span class="text-muted small">नमामि गंगे परियोजना के अंतर्गत छपरा, डोरीगंज, दिघवारा व सोनपुर में नदी जल गुणवत्ता एवं घाट विकास। (<a href="https://nmcg.nic.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">nmcg.nic.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-speedometer2 text-primary fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">केंद्रीय जल आयोग (CWC), जल शक्ति मंत्रालय</strong>
                            <span class="text-muted small">रेवा घाट (गंडक), छपरा (सरयू) एवं डोरीगंज में जलप्रवाह मापन, जलस्तर और बाढ़ पूर्वानुमान आंकड़े। (<a href="https://cwc.gov.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">cwc.gov.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-tsunami text-warning fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">भारतीय अंतर्देशीय जलमार्ग प्राधिकरण (IWAI)</strong>
                            <span class="text-muted small">राष्ट्रीय जलमार्ग 1 (गंगा) एवं राष्ट्रीय जलमार्ग 37 (गंडक) पर नौवहन टर्मिनल व जल परिवहन। (<a href="https://iwai.nic.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">iwai.nic.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Banner -->
    <div class="card border-0 shadow rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0c2d48 0%, #145da0 100%);">
        <div class="card-body p-4 p-md-5 text-white text-center">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
                <i class="bi bi-compass me-1"></i> सारण जिले को डिजिटली जानिए
            </span>
            <h3 class="fw-bold font-heading text-white display-6 mb-3">
                सारण के सभी 20 प्रखंडों एवं घाटों से जुड़ें
            </h3>
            <p class="text-white-50 lead fs-6 mx-auto mb-4" style="max-width: 700px;">
                सारण इंडेक्स पर जिले के सभी 20 प्रखंड, आपातकालीन नंबर, ऐतिहासिक धरोहर और स्थानीय व्यवसाय खोजें।
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="blocks" class="btn btn-warning text-dark fw-bold px-4 py-2.5 rounded-pill shadow">
                    <i class="bi bi-geo-alt-fill me-1"></i> सभी 20 प्रखंड देखें
                </a>
                <a href="nahar" class="btn btn-light text-success fw-bold px-4 py-2.5 rounded-pill shadow">
                    <i class="bi bi-water me-1"></i> सारण की नहरें
                </a>
                <a href="history" class="btn btn-outline-light fw-bold px-4 py-2.5 rounded-pill">
                    <i class="bi bi-hourglass-split me-1"></i> सारण का इतिहास
                </a>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

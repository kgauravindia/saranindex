<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "सारण जिले (छपरा) का गौरवशाली इतिहास एवं विरासत – सारण इंडेक्स";
$meta_description = "सारण जिले (छपरा, बिहार) का 4500 वर्षों का गौरवशाली इतिहास, प्राचीन चिरांद सभ्यता, गौतम ऋषि आश्रम (गोदना), लोकनायक जयप्रकाश नारायण, भिखारी ठाकुर, मुग़ल काल में सरकार सारण एवं स्वतंत्रता संग्राम का संपूर्ण विवरण।";
$meta_keywords = "सारण का इतिहास, छपरा का इतिहास, चिरांद पुरातात्विक स्थल, लोकनायक जयप्रकाश नारायण, भिखारी ठाकुर, आमी मंदिर, गौतम आश्रम, सरकार सारण, बिहार का इतिहास";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom Style for History Page Elements -->
<style>
.history-timeline {
    position: relative;
    padding-left: 2rem;
}
.history-timeline::before {
    content: '';
    position: absolute;
    top: 10px;
    bottom: 10px;
    left: 8px;
    width: 3px;
    background: linear-gradient(180deg, #1e40af 0%, #f59e0b 50%, #10b981 100%);
    border-radius: 3px;
}
.history-timeline-item {
    position: relative;
    margin-bottom: 2.5rem;
}
.history-timeline-item:last-child {
    margin-bottom: 0;
}
.history-timeline-dot {
    position: absolute;
    left: -2rem;
    top: 4px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ffffff;
    border: 4px solid #1e40af;
    box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.15);
    transition: all 0.3s ease;
}
.history-timeline-item:hover .history-timeline-dot {
    transform: scale(1.25);
    border-color: #f59e0b;
    box-shadow: 0 0 0 6px rgba(245, 158, 11, 0.25);
}
.heritage-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e2e8f0;
}
.heritage-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 30px -8px rgba(30, 64, 175, 0.15) !important;
    border-color: #93c5fd;
}
.personality-card {
    border-left: 4px solid var(--primary);
    background: #ffffff;
    transition: all 0.25s ease;
}
.personality-card:hover {
    border-left-color: var(--accent);
    transform: translateX(4px);
}
.quick-nav-pill {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #334155;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}
.quick-nav-pill:hover, .quick-nav-pill.active {
    background: #1e40af;
    color: #ffffff;
    border-color: #1e40af;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.2);
}
.quote-box {
    background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
    border-left: 4px solid #f59e0b;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
}
</style>

<!-- Hero Section -->
<div class="bg-gradient-primary text-white py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-4 text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
            <i class="bi bi-hourglass-split me-1"></i> 4,500+ वर्षों की गौरवशाली ऐतिहासिक विरासत
        </span>
        <h1 class="fw-bold font-heading text-white display-5 mb-2">
            सारण जिले (छपरा) का ऐतिहासिक एवं सांस्कृतिक गौरव
        </h1>
        <div class="lead text-warning fw-semibold mb-3">
            चिरांद की नवपाषाण कालीन सभ्यता से लेकर संपूर्ण क्रांति और आधुनिक सारण तक
        </div>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 820px;">
            पवित्र <strong>गंगा, घाघरा (सरयू) एवं गंडक</strong> नदियों के संगम पर बसा सारण जिला प्राचीन नवपाषाण कालीन सभ्यता का पालना, वैदिक ऋषियों की तपोभूमि, स्वाधीनता संग्राम का महाकेंद्र एवं भोजपुरी लोक-संस्कृति की अमर जन्मस्थली है।
        </p>

        <!-- Quick Jump Filter Badges -->
        <div class="d-flex justify-content-center gap-2 flex-wrap mb-2">
            <a href="#etymology" class="quick-nav-pill"><i class="bi bi-tag-fill text-warning"></i> नामकरण एवं पौराणिक पृष्ठभूमि</a>
            <a href="#chirand" class="quick-nav-pill"><i class="bi bi-gem text-info"></i> प्राचीन चिरांद सभ्यता</a>
            <a href="#mughal-colonial" class="quick-nav-pill"><i class="bi bi-bank text-success"></i> सरकार सारण एवं व्यापार</a>
            <a href="#freedom-movement" class="quick-nav-pill"><i class="bi bi-flag-fill text-danger"></i> स्वतंत्रता संग्राम एवं जे.पी.</a>
            <a href="#cultural-icons" class="quick-nav-pill"><i class="bi bi-music-note-beamed text-primary"></i> भिखारी ठाकुर एवं संस्कृति</a>
            <a href="#sacred-sites" class="quick-nav-pill"><i class="bi bi-geo-alt-fill text-warning"></i> प्रमुख ऐतिहासिक स्थल</a>
            <a href="#timeline" class="quick-nav-pill"><i class="bi bi-clock-history text-secondary"></i> ऐतिहासिक समय-रेखा</a>
            <a href="#sources" class="quick-nav-pill"><i class="bi bi-shield-check text-success"></i> आधिकारिक स्रोत</a>
        </div>
    </div>
</div>

<!-- Main Container -->
<div class="container py-5">

    <!-- KPI Highlight Cards -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-primary">
                <div class="text-primary fs-1 mb-2"><i class="bi bi-feather"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">2500 ई.पू.</h3>
                <p class="text-muted small mb-0 fw-semibold">प्राचीन चिरांद युग</p>
                <div class="mt-2 text-primary small fs-7 fw-bold">हड्डी के औजार एवं नवपाषाण संस्कृति</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-warning">
                <div class="text-warning fs-1 mb-2"><i class="bi bi-bank2"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">1582 ई.</h3>
                <p class="text-muted small mb-0 fw-semibold">सरकार सारण (मुग़ल काल)</p>
                <div class="mt-2 text-warning small fs-7 fw-bold">आईन-ए-अकबरी में उल्लेख (17 परगने)</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-success">
                <div class="text-success fs-1 mb-2"><i class="bi bi-flag-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">1942 व 1974</h3>
                <p class="text-muted small mb-0 fw-semibold">क्रांतियों की महाभूमि</p>
                <div class="mt-2 text-success small fs-7 fw-bold">भारत छोड़ो आंदोलन एवं संपूर्ण क्रांति</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-info">
                <div class="text-info fs-1 mb-2"><i class="bi bi-water"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">त्रिवेणी संगम</h3>
                <p class="text-muted small mb-0 fw-semibold">नदियों की पावन गोद</p>
                <div class="mt-2 text-info small fs-7 fw-bold">गंगा • सरयू (घाघरा) • गंडक</div>
            </div>
        </div>
    </div>

    <!-- Section 1: Origins & Etymology -->
    <div id="etymology" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill small">प्राचीन जड़े</span>
                    <span class="text-muted small fw-semibold">सारण नामकरण की उत्पत्ति</span>
                </div>
                <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                    सारण का नामकरण एवं पौराणिक पृष्ठभूमि
                </h2>
                <p class="text-secondary" style="line-height: 1.8;">
                    <strong>सारण</strong> नाम की उत्पत्ति प्राचीन संस्कृत साहित्य, वेदों और जनश्रुतियों में अत्यंत महत्वपूर्ण मानी जाती है:
                </p>
                <ul class="text-secondary mb-3" style="line-height: 1.8;">
                    <li><strong>सारंग-अरण्य (Saranga-Aranya):</strong> ऐतिहासिक मान्यताओं के अनुसार इस भूभाग को प्राचीन काल में <em>सारंग अरण्य</em> कहा जाता था, जिसका अर्थ है <em>"चित्तीदार हिरणों अथवा मोरों का वन"</em>। गंगा, सरयू और गंडक नदियों के कछारों में फैले घने वन वन्यजीवों और तपस्वियों का प्रमुख आश्रय स्थल थे।</li>
                    <li><strong>शरणम् (आश्रय / शांति स्थली):</strong> एक अन्य मान्यता के अनुसार यह शब्द <em>शरण</em> (आश्रय) से विकसित हुआ है, जहाँ प्राचीन काल में ऋषि-मुनियों और संतों के आश्रमों में लोग मानसिक व आध्यात्मिक शरण पाते थे।</li>
                    <li><strong>महर्षि गौतम की तपोभूमि:</strong> सारण के रिविलगंज (गोदना) को न्याय दर्शन के प्रणेता <strong>महर्षि गौतम</strong> की तपोभूमि माना जाता है। यहीं पर भगवान श्री राम द्वारा माता <em>अहिल्या का उद्धार</em> (अहिल्या उद्धार) हुआ था, जिसका वर्णन रामायण में मिलता है।</li>
                </ul>

                <div class="quote-box">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-quote text-warning fs-3 mt-n1"></i>
                        <div class="small text-dark fw-medium">
                            <em>"सारण वह पावन माटी है जहाँ वैदिक दर्शन, रामायण कालीन स्मृतियां और तीन महान नदियों का संगम एक साथ मिलकर भारतीय सभ्यता के आरंभिक पृष्ठों की रचना करते हैं।"</em>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="p-4 rounded-4 bg-light border">
                    <h5 class="fw-bold text-dark font-heading mb-3"><i class="bi bi-compass-fill text-primary me-2"></i>भौगोलिक एवं प्रशासनिक स्थिति</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-3">
                            <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">मुख्यालय (Headquarters)</strong>
                                <span class="text-muted small">छपरा (Chapra) — घाघरा (सरयू) नदी के उत्तरी तट पर स्थित।</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                <i class="bi bi-water"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">नदियों से घिरा भूभाग</strong>
                                <span class="text-muted small">दक्षिण में <strong>गंगा</strong>, दक्षिण-पश्चिम में <strong>घाघरा (सरयू)</strong> एवं पूर्व में <strong>गंडक</strong> नदी।</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">प्रमंडलीय मुख्यालय</strong>
                                <span class="text-muted small">1981 से सारण प्रमंडल का मुख्यालय (सारण, सीवान एवं गोपालगंज)।</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Archaeological Marvel of Chirand -->
    <div id="chirand" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill small">विश्व प्रसिद्ध पुरातात्विक धरोहर</span>
            <span class="text-muted small fw-semibold">4500 वर्ष प्राचीन इतिहास</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            चिरांद (Chirand): नवपाषाण काल का वैश्विक गौरव
        </h2>
        <p class="text-secondary lead fs-6" style="line-height: 1.8;">
            छपरा से लगभग 11 किलोमीटर दक्षिण-पूर्व में दिघवारा के निकट गंगा नदी के उत्तरी तट पर स्थित <strong>चिरांद</strong> भारत के सबसे महत्वपूर्ण और प्राचीन पुरातात्विक उत्खनन स्थलों में से एक है।
        </p>

        <div class="row g-4 mt-2">
            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <h5 class="fw-bold text-dark font-heading mb-3"><i class="bi bi-search text-primary me-2"></i>चिरांद की प्रमुख पुरातात्विक खोजें</h5>
                    <ul class="text-secondary small mb-0 d-flex flex-column gap-2" style="line-height: 1.7;">
                        <li><strong>हड्डियों और सींगों के औजार:</strong> चिरांद हिरण के सींगों और पशुओं की हड्डियों से बने सुइयों, बाणों, खुरचनियों और छेनी जैसे परिष्कृत औजारों के लिए पूरे विश्व में विख्यात है।</li>
                        <li><strong>गोलाकार घास-फूस की झोपड़ियां:</strong> उत्खनन में ईसा पूर्व 2500 से 1500 के मध्य की गोलाकार झोपड़ियां, मिट्टी के लेप वाले फर्श और चूल्हे प्राप्त हुए हैं।</li>
                        <li><strong>प्रारंभिक कृषि के साक्ष्य:</strong> चिरांद से धान (चावल), गेहूं, जौ, मसूर और मूंग के जले हुए दाने मिले हैं, जो यह सिद्ध करते हैं कि यहाँ एक संगठित कृषि समाज विकसित था।</li>
                        <li><strong>टेराकोटा मूर्तियां एवं मनके:</strong> पकी हुई मिट्टी के वृषभ, पक्षी, सर्प की आकृतियां तथा अगेट, जैस्पर, गोमेद के बने मनके (Beads) भारी संख्या में मिले हैं।</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <h5 class="fw-bold text-dark font-heading mb-3"><i class="bi bi-layers-fill text-success me-2"></i>चिरांद का सांस्कृतिक कालक्रम</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="border-start border-3 border-primary ps-3">
                            <strong class="text-dark small d-block">कालखण्ड I: नवपाषाण युग (लगभग 2500 ई.पू. – 1500 ई.पू.)</strong>
                            <span class="text-muted small">लाल व धूसर मृदभांड, हड्डी के औजार, लघु पाषाण उपकरण एवं व्यवस्थित पशुपालन-कृषि।</span>
                        </div>
                        <div class="border-start border-3 border-warning ps-3">
                            <strong class="text-dark small d-block">कालखण्ड II: ताम्रपाषाण युग (लगभग 1500 ई.पू. – 800 ई.पू.)</strong>
                            <span class="text-muted small">कृष्ण-लोहित मृदभांड (BRW), तांबे के औजार एवं विस्तृत नदीय व्यापारिक संपर्क।</span>
                        </div>
                        <div class="border-start border-3 border-success ps-3">
                            <strong class="text-dark small d-block">कालखण्ड III व IV: NBPW एवं ऐतिहासिक युग (800 ई.पू. – 300 ई.)</strong>
                            <span class="text-muted small">उत्तरी काली चमकीली मृदभांड परंपरा, लोहे के हथियार, आहत सिक्के (Punch-marked coins) एवं मौर्यकालीन बस्तियां।</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Medieval Era, Mughal Period & River Port Trade -->
    <div id="mughal-colonial" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-1.5 rounded-pill small">प्रशासन एवं व्यापार</span>
            <span class="text-muted small fw-semibold">16वीं से 19वीं शताब्दी</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            सरकार सारण, नदीय व्यापार एवं औपनिवेशिक दौर
        </h2>
        <div class="row g-4">
            <div class="col-lg-7">
                <p class="text-secondary" style="line-height: 1.8;">
                    मुगल बादशाह अकबर (1556–1605 ई.) के शासनकाल में अबुल फज़ल द्वारा रचित प्रसिद्ध ग्रंथ <strong>आईन-ए-अकबरी (1582 ई.)</strong> में बिहार सूबे के अंतर्गत <strong>सरकार सारण</strong> का उल्लेख मिलता है। उस समय सरकार सारण में 17 महाल (परगने) शामिल थे, जिनमें वर्तमान सारण, सीवान, गोपालगंज एवं चंपारण के हिस्से आते थे।
                </p>
                <p class="text-secondary" style="line-height: 1.8;">
                    <strong>नदीय व्यापार का स्वर्णिम युग:</strong> रेलवे के आगमन से पूर्व घाघरा एवं गंगा नदियाँ व्यापार का प्रमुख जलमार्ग थीं। छपरा और रिविलगंज (गोदना) बड़े नदीय बंदरगाह बने। डच (हॉलैंड), फ्रांसीसी, पुर्तगाली एवं ब्रिटिश ईस्ट इंडिया कंपनी ने छपरा में अपनी व्यापारिक कोठियां (Factories) स्थापित कीं।
                </p>
                <div class="p-3 bg-light rounded-3 border">
                    <strong class="text-dark small d-block mb-1"><i class="bi bi-box-seam text-warning me-1"></i> शोरा (Saltpetre) एवं वस्त्र व्यापार:</strong>
                    <p class="text-muted small mb-0">
                        सारण विश्व स्तर पर उच्च कोटि के <strong>शोरा (पोटैशियम नाइट्रेट)</strong> के उत्पादन का प्रमुख केंद्र था, जिसका उपयोग यूरोप में बारूद निर्माण के लिए किया जाता था। इसके अतिरिक्त अफीम, नील, सूती वस्त्र और अनाज का भारी व्यापार होता था।
                    </p>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 bg-dark text-white rounded-4 p-4 h-100">
                    <h5 class="fw-bold text-warning font-heading mb-3"><i class="bi bi-diagram-2 me-2"></i>प्रशासनिक पुनर्गठन का सफर</h5>
                    <div class="d-flex flex-column gap-3 small text-white-50">
                        <div>
                            <strong class="text-white d-block">1766: राजस्व जिला गठन</strong>
                            बक्सर के युद्ध (1764) व दीवानी अधिकार के बाद ब्रिटिश शासन द्वारा सारण को राजस्व जिले के रूप में गठित किया गया।
                        </div>
                        <div>
                            <strong class="text-white d-block">1866: चंपारण का पृथक्करण</strong>
                            चंपारण को सारण से अलग कर स्वतंत्र जिला बनाया गया।
                        </div>
                        <div>
                            <strong class="text-white d-block">1972: सीवान एवं गोपालगंज का निर्माण</strong>
                            अक्टूबर 1972 में पुराने सारण जिले से सीवान और गोपालगंज को स्वतंत्र जिले का दर्जा दिया गया।
                        </div>
                        <div>
                            <strong class="text-white d-block">1981: सारण प्रमंडल</strong>
                            सारण को प्रमंडल (Commissionery) बनाया गया जिसका मुख्यालय छपरा में स्थापित हुआ।
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Freedom Movement & Iconic Leaders -->
    <div id="freedom-movement" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-danger text-white fw-bold px-3 py-1.5 rounded-pill small">स्वतंत्रता समर</span>
            <span class="text-muted small fw-semibold">क्रांतिकारियों और राष्ट्रनायकों की भूमि</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            भारत के स्वतंत्रता संग्राम में सारण का ऐतिहासिक योगदान
        </h2>
        <p class="text-secondary lead fs-6 mb-4" style="line-height: 1.8;">
            1917 के चंपारण सत्याग्रह से लेकर 1942 के भारत छोड़ो आंदोलन और 1974 की संपूर्ण क्रांति तक, सारण की धरती ने ऐसे महान राष्ट्रनायकों को जन्म दिया जिन्होंने आधुनिक भारत के इतिहास की धारा बदल दी।
        </p>

        <div class="row g-4">
            <!-- JP -->
            <div class="col-md-6 col-lg-4">
                <div class="personality-card p-4 rounded-4 shadow-sm border h-100">
                    <span class="badge bg-warning text-dark fw-bold mb-2">लोकनायक</span>
                    <h4 class="fw-bold text-dark font-heading mb-1">जयप्रकाश नारायण</h4>
                    <p class="text-muted small mb-2">जन्म: सिताब दियारा, सारण (1902–1979)</p>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        1942 के भारत छोड़ो आंदोलन के महानायक (हजारीबाग जेल ब्रेक), आजाद दस्ता के संस्थापक एवं 1974 में भारतीय लोकतंत्र की रक्षा हेतु <strong>संपूर्ण क्रांति (Total Revolution)</strong> का शंखनाद करने वाले युगपुरुष। 1999 में <strong>भारत रत्न</strong> से सम्मानित।
                    </p>
                </div>
            </div>

            <!-- Dr. Rajendra Prasad -->
            <div class="col-md-6 col-lg-4">
                <div class="personality-card p-4 rounded-4 shadow-sm border h-100">
                    <span class="badge bg-primary text-white fw-bold mb-2">देशरत्न</span>
                    <h4 class="fw-bold text-dark font-heading mb-1">डॉ. राजेन्द्र प्रसाद</h4>
                    <p class="text-muted small mb-2">जन्म: जीरादेई (अविभाजित सारण, 1884–1963)</p>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        <strong>भारत के प्रथम राष्ट्रपति</strong>, भारतीय संविधान सभा के स्थायी अध्यक्ष एवं महात्मा गांधी के प्रमुख सहयोगी। सादगी और विद्वता की प्रतिमूर्ति, 1962 में भारत रत्न से सम्मानित।
                    </p>
                </div>
            </div>

            <!-- Maulana Mazharul Haque -->
            <div class="col-md-6 col-lg-4">
                <div class="personality-card p-4 rounded-4 shadow-sm border h-100">
                    <span class="badge bg-success text-white fw-bold mb-2">देशभक्त</span>
                    <h4 class="fw-bold text-dark font-heading mb-1">मौलाना मजहरुल हक</h4>
                    <p class="text-muted small mb-2">फरीदपुर / आशियाना (1866–1930)</p>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        हिंदू-मुस्लिम एकता के प्रतीक, बिहार विद्यापीठ व सदाकत आश्रम पटना के संस्थापक। सारण प्रमंडल में उनका निवास 'आशियाना' स्वतंत्रता सेनानियों का प्रमुख विचार केंद्र था।
                    </p>
                </div>
            </div>

            <!-- Babu Brajkishore Prasad & Prabhavati Devi -->
            <div class="col-md-6 col-lg-4">
                <div class="personality-card p-4 rounded-4 shadow-sm border h-100">
                    <span class="badge bg-info text-dark fw-bold mb-2">सत्याग्रह प्रणेता</span>
                    <h4 class="fw-bold text-dark font-heading mb-1">बाबू ब्रजकिशोर प्रसाद</h4>
                    <p class="text-muted small mb-2">सारण / चंपारण आंदोलन</p>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        गांधीजी के अभिन्न सहयोगी जिन्होंने चंपारण सत्याग्रह की नींव रखी। वे लोकनायक जयप्रकाश नारायण की धर्मपत्नी व प्रमुख स्वाधीनता सेनानी <strong>प्रभावती देवी</strong> के पिता थे।
                    </p>
                </div>
            </div>

            <!-- Mahendra Misir -->
            <div class="col-md-6 col-lg-4">
                <div class="personality-card p-4 rounded-4 shadow-sm border h-100">
                    <span class="badge bg-danger text-white fw-bold mb-2">पूर्वी सम्राट व क्रांतिकारी</span>
                    <h4 class="fw-bold text-dark font-heading mb-1">महेंद्र मिसिर</h4>
                    <p class="text-muted small mb-2">मिश्रवलिया, जलालपुर (1886–1946)</p>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        भोजपुरी <em>पूर्वी</em> गायन शैली के जनक एवं महान संगीतकार, जिन्होंने अंग्रेजों के विरुद्ध गुप्त रूप से जाली नोट छापकर भारतीय क्रांतिकारियों को हथियार व आर्थिक मदद उपलब्ध कराई।
                    </p>
                </div>
            </div>

            <!-- Rahul Sankrityayan & Peasant Movement -->
            <div class="col-md-6 col-lg-4">
                <div class="personality-card p-4 rounded-4 shadow-sm border h-100">
                    <span class="badge bg-secondary text-white fw-bold mb-2">किसान आंदोलन</span>
                    <h4 class="fw-bold text-dark font-heading mb-1">राहुल सांकृत्यायन</h4>
                    <p class="text-muted small mb-2">अमवारी सत्याग्रह, सारण (1939)</p>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        महापंडित राहुल सांकृत्यायन ने सारण के अमवारी गांव में किसानों के बकाश्त आंदोलन का नेतृत्व किया और लाठी चार्ज सहते हुए किसानों को जमींदारी शोषण से मुक्ति दिलाई।
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 5: Cultural Legacy & Bhojpuri Heritage -->
    <div id="cultural-icons" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-primary text-white fw-bold px-3 py-1.5 rounded-pill small">लोक नाट्य एवं कला</span>
            <span class="text-muted small fw-semibold">भोजपुरी साहित्य के अमर स्तम्भ</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            भिखारी ठाकुर: भोजपुरी के शेक्सपियर
        </h2>
        
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <p class="text-secondary" style="line-height: 1.8;">
                    सारण जिले के कुतुबपुर गांव में जन्मे <strong>भिखारी ठाकुर (18 दिसंबर 1887 – 10 जुलाई 1971)</strong> को भोजपुरी भाषा का सबसे महान नाटककार, गीतकार, अभिनेता और समाज सुधारक माना जाता है। उन्हें <em>"भोजपुरी का शेक्सपियर"</em> और <em>"राय बहादुर"</em> की उपाधि से नवाजा गया।
                </p>
                <p class="text-secondary" style="line-height: 1.8;">
                    उनके अमर नाटकों—विशेष रूप से <em>बिदेसिया</em>, <em>गबरघिचोर</em>, <em>बेटी बेचवा</em>, <em>राधेश्याम बहार</em> और <em>भाई बिरोध</em>—ने पलायन, गरीबी, नारी उत्पीड़न, बाल विवाह और जातिगत कुरीतियों पर गहरा प्रहार किया तथा मॉरीशस, फिजी, त्रिनिदाद और सूरीनाम तक भोजपुरी संस्कृति को पहुंचाया।
                </p>

                <div class="row g-3 mt-2">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <strong class="text-dark small d-block mb-1"><i class="bi bi-film text-danger me-1"></i> बिदेसिया (Bidesiya)</strong>
                            <span class="text-muted small">प्रवासी पतियों के परदेस जाने और गांव में पीछे छूट गई स्त्रियों के वियोग व सामाजिक दर्द का अमर नाट्य।</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <strong class="text-dark small d-block mb-1"><i class="bi bi-heart-pulse text-primary me-1"></i> बेटी बेचवा एवं समाज सुधार</strong>
                            <span class="text-muted small">बेमेल विवाह, कन्या विक्रय और दहेज प्रथा के विरुद्ध जनचेतना जगाने वाला ऐतिहासिक नाटक।</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bg-gradient-primary text-white p-4 rounded-4 shadow-sm text-center">
                    <div class="text-warning fs-1 mb-2"><i class="bi bi-star-fill"></i></div>
                    <h4 class="fw-bold font-heading text-white mb-1">भोजपुरी के शेक्सपियर</h4>
                    <p class="text-white-50 small mb-3">भिखारी ठाकुर (1887 – 1971)</p>
                    <div class="border-top border-white border-opacity-25 pt-3 text-start small">
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>जन्मस्थान:</strong> कुतुबपुर, सारण</div>
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>नाट्य विधा:</strong> बिदेसिया शैली</div>
                        <div class="mb-0"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>वैश्विक पहचान:</strong> भोजपुरी अस्मिता के प्रतीक</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 6: Sacred Heritage Sites of Saran -->
    <div id="sacred-sites" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill small">धार्मिक एवं पुरातात्विक स्थल</span>
            <span class="text-muted small fw-semibold">सारण के पावन तीर्थ</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            सारण के प्रमुख ऐतिहासिक एवं धार्मिक धरोहर स्थल
        </h2>
        <p class="text-secondary lead fs-6 mb-4">
            सारण के 20 प्रखंडों में फैले पौराणिक, ऐतिहासिक एवं आध्यात्मिक तीर्थ स्थलों का परिचय।
        </p>

        <div class="row g-4">
            <!-- Aami Mandir -->
            <div class="col-md-6 col-lg-4">
                <div class="heritage-card p-4 rounded-4 bg-white shadow-sm h-100 d-flex flex-column">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-danger-subtle text-danger rounded-3 p-2.5 fs-4">
                            <i class="bi bi-flower1"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0 font-heading">आमी अंबिका भवानी</h5>
                            <span class="text-muted small">दिघवारा प्रखंड</span>
                        </div>
                    </div>
                    <p class="text-secondary small flex-grow-1" style="line-height: 1.6;">
                        गंगा तट पर स्थित प्रसिद्ध शक्तिपीठ। दुर्गा सप्तशती के अनुसार राजा सुरथ और समाधि वैश्य की तपस्थली तथा माता सती के पावन यज्ञ कुण्ड से जुड़ा ऐतिहासिक तीर्थ।
                    </p>
                    <div class="pt-2 border-top">
                        <span class="badge bg-light text-dark border small"><i class="bi bi-calendar3 me-1"></i> नवरात्र महोत्सव</span>
                    </div>
                </div>
            </div>

            <!-- Hariharnath Mandir Sonepur -->
            <div class="col-md-6 col-lg-4">
                <div class="heritage-card p-4 rounded-4 bg-white shadow-sm h-100 d-flex flex-column">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-warning-subtle text-warning-emphasis rounded-3 p-2.5 fs-4">
                            <i class="bi bi-sun-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0 font-heading">बाबा हरिहरनाथ मंदिर</h5>
                            <span class="text-muted small">सोनपुर (Sonpur)</span>
                        </div>
                    </div>
                    <p class="text-secondary small flex-grow-1" style="line-height: 1.6;">
                        गंडक और गंगा के पावन संगम पर स्थित भगवान विष्णु (हरि) और भगवान शिव (हर) का अद्वितीय संयुक्त मंदिर। यहीं एशिया का सबसे बड़ा ऐतिहासिक सोनपुर पशु मेला लगता है।
                    </p>
                    <div class="pt-2 border-top">
                        <span class="badge bg-light text-dark border small"><i class="bi bi-geo-alt me-1"></i> हरिहर क्षेत्र संगम</span>
                    </div>
                </div>
            </div>

            <!-- Gautam Rishi Ashram -->
            <div class="col-md-6 col-lg-4">
                <div class="heritage-card p-4 rounded-4 bg-white shadow-sm h-100 d-flex flex-column">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary-subtle text-primary rounded-3 p-2.5 fs-4">
                            <i class="bi bi-tree-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0 font-heading">गौतम ऋषि आश्रम</h5>
                            <span class="text-muted small">गोदना (रिविलगंज)</span>
                        </div>
                    </div>
                    <p class="text-secondary small flex-grow-1" style="line-height: 1.6;">
                        सरयू नदी के तट पर स्थित महर्षि गौतम का प्राचीन आश्रम। मर्यादा पुरुषोत्तम श्री राम द्वारा माता अहिल्या के उद्धार की तपोभूमि।
                    </p>
                    <div class="pt-2 border-top">
                        <span class="badge bg-light text-dark border small"><i class="bi bi-water me-1"></i> सरयू तट घाट</span>
                    </div>
                </div>
            </div>

            <!-- Silhauri Shiva Temple -->
            <div class="col-md-6 col-lg-4">
                <div class="heritage-card p-4 rounded-4 bg-white shadow-sm h-100 d-flex flex-column">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-success-subtle text-success rounded-3 p-2.5 fs-4">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0 font-heading">सिल्हौरी (बाबा शीलनाथ)</h5>
                            <span class="text-muted small">मढ़ौरा प्रखंड</span>
                        </div>
                    </div>
                    <p class="text-secondary small flex-grow-1" style="line-height: 1.6;">
                        पौराणिक काल का प्राचीन शिव मंदिर जो राजा मोहिनी और नारद मुनि की कथा से जुड़ा है। यहां महाशिवरात्रि पर विशाल मेला लगता है।
                    </p>
                    <div class="pt-2 border-top">
                        <span class="badge bg-light text-dark border small"><i class="bi bi-calendar-event me-1"></i> महाशिवरात्रि मेला</span>
                    </div>
                </div>
            </div>

            <!-- Dhorh Ashram -->
            <div class="col-md-6 col-lg-4">
                <div class="heritage-card p-4 rounded-4 bg-white shadow-sm h-100 d-flex flex-column">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-info-subtle text-info rounded-3 p-2.5 fs-4">
                            <i class="bi bi-bank"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0 font-heading">ढोरह आश्रम</h5>
                            <span class="text-muted small">परसा प्रखंड</span>
                        </div>
                    </div>
                    <p class="text-secondary small flex-grow-1" style="line-height: 1.6;">
                        परसा प्रखंड में स्थित प्राचीन तपोस्थली और मंदिर परिसर, जो संतों की साधना, गुफाओं और वैदिक अनुष्ठानों के लिए प्रसिद्ध है।
                    </p>
                    <div class="pt-2 border-top">
                        <span class="badge bg-light text-dark border small"><i class="bi bi-shield-check me-1"></i> आध्यात्मिक केंद्र</span>
                    </div>
                </div>
            </div>

            <!-- Semaria Ghat & Sangam -->
            <div class="col-md-6 col-lg-4">
                <div class="heritage-card p-4 rounded-4 bg-white shadow-sm h-100 d-flex flex-column">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-secondary-subtle text-secondary rounded-3 p-2.5 fs-4">
                            <i class="bi bi-tsunami"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0 font-heading">सेमरिया घाट व डोरीगंज</h5>
                            <span class="text-muted small">छपरा / डोरीगंज</span>
                        </div>
                    </div>
                    <p class="text-secondary small flex-grow-1" style="line-height: 1.6;">
                        सरयू और गंगा नदी का पवित्र संगम स्थल। पवित्र स्नान, सूर्य ग्रहण पर्व और ऐतिहासिक जल परिवहन का महत्वपूर्ण केंद्र।
                    </p>
                    <div class="pt-2 border-top">
                        <span class="badge bg-light text-dark border small"><i class="bi bi-droplet-fill me-1"></i> पावन स्नान घाट</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 7: Chronological Timeline of Saran -->
    <div id="timeline" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-dark text-white fw-bold px-3 py-1.5 rounded-pill small">कालक्रम</span>
            <span class="text-muted small fw-semibold">ऐतिहासिक पड़ाव</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-4">
            सारण जिले की ऐतिहासिक समय-रेखा (Timeline)
        </h2>

        <div class="history-timeline">
            <!-- Timeline 1 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-primary text-white fw-bold mb-1">लगभग 2500 ई.पू. – 1000 ई.पू.</div>
                <h5 class="fw-bold text-dark font-heading mb-1">चिरांद में नवपाषाण व ताम्रपाषाण कालीन सभ्यता</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    गंगा के तट पर उन्नत कृषि, हिरण के सींग के औजार, मिट्टी के गोल मकान और नदीय व्यापार का विकास।
                </p>
            </div>

            <!-- Timeline 2 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-success text-white fw-bold mb-1">6वीं – 3री शताब्दी ई.पू.</div>
                <h5 class="fw-bold text-dark font-heading mb-1">महाजनपद एवं मौर्य काल</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    गौतम ऋषि द्वारा गोदना में आश्रम की स्थापना। मौर्य सम्राटों द्वारा सोनपुर (हरिहर क्षेत्र) में गज और अश्व व्यापार को संरक्षण।
                </p>
            </div>

            <!-- Timeline 3 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-warning text-dark fw-bold mb-1">1582 ई.</div>
                <h5 class="fw-bold text-dark font-heading mb-1">मुगल काल में 'सरकार सारण' का गठन</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    आईन-ए-अकबरी में 17 महालों के साथ सरकार सारण का उल्लेख, जो बिहार सूबे का प्रमुख उपजाऊ और राजस्व क्षेत्र बना।
                </p>
            </div>

            <!-- Timeline 4 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-info text-dark fw-bold mb-1">17वीं – 18वीं शताब्दी</div>
                <h5 class="fw-bold text-dark font-heading mb-1">डच, फ्रांसीसी व ब्रिटिश शोरा कोठियों की स्थापना</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    छपरा और रिविलगंज अंतरराष्ट्रीय नदी बंदरगाह के रूप में उभरे; बारूद निर्माण हेतु शोरा, अफीम और सूती कपड़ों का निर्यात।
                </p>
            </div>

            <!-- Timeline 5 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-secondary text-white fw-bold mb-1">1766 ई.</div>
                <h5 class="fw-bold text-dark font-heading mb-1">ईस्ट इंडिया कंपनी द्वारा राजस्व जिले का गठन</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    बक्सर के युद्ध के बाद सारण को प्रशासनिक जिला बनाया गया। 1866 में चंपारण को सारण से अलग किया गया।
                </p>
            </div>

            <!-- Timeline 6 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-danger text-white fw-bold mb-1">1917 – 1942 ई.</div>
                <h5 class="fw-bold text-dark font-heading mb-1">चंपारण सत्याग्रह, किसान आंदोलन एवं भारत छोड़ो क्रांति</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    ब्रजकिशोर प्रसाद, डॉ. राजेन्द्र प्रसाद, राहुल सांकृत्यायन और जयप्रकाश नारायण के नेतृत्व में अभूतपूर्व जनक्रांति।
                </p>
            </div>

            <!-- Timeline 7 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-primary text-white fw-bold mb-1">1972 व 1981 ई.</div>
                <h5 class="fw-bold text-dark font-heading mb-1">सीवान-गोपालगंज का पृथक्करण एवं सारण प्रमंडल गठन</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    1972 में सीवान और गोपालगंज पृथक जिले बने तथा 1981 में छपरा को सारण प्रमंडल का मुख्यालय बनाया गया।
                </p>
            </div>

            <!-- Timeline 8 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-success text-white fw-bold mb-1">1990 ई.</div>
                <h5 class="fw-bold text-dark font-heading mb-1">जयप्रकाश विश्वविद्यालय (JPU) की स्थापना</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    22 नवंबर 1990 को छपरा में जयप्रकाश विश्वविद्यालय की स्थापना कर उच्च शिक्षा का विस्तार किया गया।
                </p>
            </div>

            <!-- Timeline 9 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-warning text-dark fw-bold mb-1">26 जुलाई 2026</div>
                <h5 class="fw-bold text-dark font-heading mb-1">सारण इंडेक्स (डिजिटल डायरेक्टरी) का शुभारंभ</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    ऑफ़रप्लांट टेक्नोलॉजीज प्रा. लि. के 9वें स्थापना दिवस पर सारण के 20 प्रखंडों को डिजिटली जोड़ने हेतु सारण इंडेक्स का शुभारंभ।
                </p>
            </div>
        </div>
    </div>

    <!-- Section 8: Official Sources & Historical References -->
    <div id="sources" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white border-top border-4 border-warning">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill small mb-1">प्रमाणिकता एवं संदर्भ</span>
                <h3 class="fw-bold font-heading text-dark display-6 mb-0">आधिकारिक ऐतिहासिक स्रोत एवं संदर्भ</h3>
            </div>
            <a href="sources" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-bold">
                <i class="bi bi-shield-check me-1"></i> सभी संदर्भ पोर्टल देखें <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <p class="text-secondary small mb-4" style="line-height: 1.7;">
            सारण जिले का कालक्रम, पुरातात्विक उत्खनन तथ्य और प्रशासनिक इतिहास अधिकृत सरकारी अभिलेखों, पुरातात्विक रिपोर्टों एवं ऐतिहासिक ग्रंथों से संकलित किए गए हैं:
        </p>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-bank2 text-warning fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">भारतीय पुरातत्व सर्वेक्षण (ASI)</strong>
                            <span class="text-muted small">चिरांद के नवपाषाण व ताम्रपाषाण टीलों, हड्डी के औजारों एवं प्राचीन बस्तियों की आधिकारिक उत्खनन रिपोर्ट। (<a href="https://asi.nic.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">asi.nic.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-book-half text-primary fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">बिहार राज्य गजेटियर: सारण (1960)</strong>
                            <span class="text-muted small">राजस्व विभाग, बिहार सरकार द्वारा प्रकाशित प्रामाणिक जिला गजेटियर — सारण के नामकरण, भूगोल, संस्कृति व स्वाधीनता समर का संपूर्ण दस्तावेजीकरण।</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-journal-bookmark-fill text-success fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">आईन-ए-अकबरी (1582 ई.) – अबुल फज़ल</strong>
                            <span class="text-muted small">मुगलकालीन प्रामाणिक ग्रंथ जिसमें बिहार सूबे के अंतर्गत <em>सरकार सारण</em>, इसके 17 महालों, राजस्व एवं सीमा का उल्लेख है।</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-archive-fill text-danger fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">राष्ट्रीय अभिलेखागार एवं सारण जिला प्रशासन</strong>
                            <span class="text-muted small">1942 भारत छोड़ो आंदोलन, चंपारण सत्याग्रह एवं प्रशासनिक पुनर्गठन के आधिकारिक सरकारी दस्तावेज। (<a href="https://saran.nic.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">saran.nic.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Banner -->
    <div class="card border-0 shadow rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);">
        <div class="card-body p-4 p-md-5 text-white text-center">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
                <i class="bi bi-compass me-1"></i> आधुनिक सारण को डिजिटली जानिए
            </span>
            <h3 class="fw-bold font-heading text-white display-6 mb-3">
                सारण के सभी 20 प्रखंडों एवं 300+ पंचायतों से जुड़ें
            </h3>
            <p class="text-white-50 lead fs-6 mx-auto mb-4" style="max-width: 680px;">
                सारण इंडेक्स पर स्थानीय व्यापार, चिकित्सक, वकील, शैक्षणिक संस्थान, सरकारी कार्यालय एवं प्रमाणित जनसुविधाएं खोजें।
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="blocks" class="btn btn-warning text-dark fw-bold px-4 py-2.5 rounded-pill shadow">
                    <i class="bi bi-geo-alt-fill me-1"></i> सभी 20 प्रखंड देखें
                </a>
                <a href="university" class="btn btn-outline-light fw-bold px-4 py-2.5 rounded-pill">
                    <i class="bi bi-bank2 me-1"></i> उच्च शिक्षा व JPU
                </a>
                <a href="add-contact" class="btn btn-light text-primary fw-bold px-4 py-2.5 rounded-pill shadow">
                    <i class="bi bi-plus-circle-fill me-1"></i> मुफ़्त व्यापार जोड़ें
                </a>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

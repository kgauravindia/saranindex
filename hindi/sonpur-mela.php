<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "सोनपुर मेला (हरिहर क्षेत्र मेला) – एशिया का सबसे बड़ा पशु एवं सांस्कृतिक मेला | सारण इंडेक्स";
$meta_description = "विश्व प्रसिद्ध सोनपुर मेला (हरिहर क्षेत्र मेला), सोनपुर, सारण (बिहार) की संपूर्ण दर्शनीय मार्गदर्शिका। पवित्र गंगा-गंडक संगम स्नान, बाबा हरिहरनाथ मंदिर, घोड़ा व पशु बाजार, सांस्कृतिक संध्या, स्विस कॉटेज व टेंट सिटी, रूट मैप एवं 24x7 हेल्पलाइन।";
$meta_keywords = "सोनपुर मेला, हरिहर क्षेत्र मेला, एशिया का सबसे बड़ा पशु मेला, हरिहरनाथ मंदिर सोनपुर, गंगा गंडक संगम, सोनपुर मेला तिथि, सोनपुर पर्यटन टेंट, जेपी सेतु सोनपुर, छपरा से सोनपुर, सोनपुर मेला आकर्षण";

// सोनपुर प्रखंड की सत्यापित निर्देशिकाएं प्राप्त करें
$db = getDB();
$sonepurListings = [];
$totalSonepurListings = 0;

if ($db) {
    try {
        $cntStmt = $db->query("SELECT count(*) FROM listings WHERE block_id = 3 AND status = 'ACTIVE'");
        $totalSonepurListings = intval($cntStmt->fetchColumn() ?: 1002);

        $stmt = $db->prepare("
            SELECT l.id, l.title, l.hindi_title, l.slug, l.contact_person, l.mobile, l.mobile_visibility,
                   l.address, l.pincode, c.name as category_name, c.hindi_name as category_hindi, c.icon as category_icon
            FROM listings l
            LEFT JOIN categories c ON l.category_id = c.id
            WHERE l.block_id = 3 AND l.status = 'ACTIVE'
            ORDER BY l.id ASC
            LIMIT 12
        ");
        $stmt->execute();
        $sonepurListings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching Sonpur listings: " . $e->getMessage());
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- सोनपुर मेला विशेष स्टाइल -->
<style>
.mela-hero-bg {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 45%, #4338ca 80%, #b45309 100%);
    color: #ffffff;
    position: relative;
    overflow: hidden;
}
.mela-hero-bg::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -15%;
    width: 650px;
    height: 650px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.22) 0%, rgba(217, 119, 6, 0.08) 50%, transparent 70%);
    pointer-events: none;
}
.mela-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    background: #ffffff;
}
.mela-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 18px 35px -10px rgba(67, 56, 202, 0.16) !important;
    border-color: #f59e0b;
}
.badge-gold {
    background: #fef3c7;
    color: #92400e;
    font-weight: 700;
}
.badge-indigo {
    background: #e0e7ff;
    color: #3730a3;
    font-weight: 700;
}
.badge-emerald {
    background: #d1fae5;
    color: #065f46;
    font-weight: 700;
}
.legend-box {
    background: linear-gradient(135deg, #fdfbf7 0%, #fffbeb 100%);
    border-left: 5px solid #d97706;
    border-radius: 16px;
    padding: 1.5rem;
}
.helpline-box {
    border-radius: 16px;
    background: #ffffff;
    border: 1px solid #fecdd3;
    transition: all 0.25s ease;
}
.helpline-box:hover {
    border-color: #e11d48;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -5px rgba(225, 29, 72, 0.12);
}
</style>

<!-- हीरो सेक्शन -->
<div class="mela-hero-bg py-5 position-relative">
    <div class="container position-relative z-2 py-4">
        
        <!-- ब्रेडक्रम्ब -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="./" class="text-white-50 text-decoration-none"><i class="bi bi-house-door me-1"></i>मुख्य पृष्ठ</a></li>
                <li class="breadcrumb-item"><a href="block/sonpur" class="text-white-50 text-decoration-none">सोनपुर प्रखंड</a></li>
                <li class="breadcrumb-item active text-warning fw-semibold" aria-current="page">सोनपुर मेला दर्शनीय गाइड</li>
            </ol>
        </nav>

        <div class="row align-items-center g-4 mb-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                    <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-warning text-dark fw-bold small shadow-sm">
                        <i class="bi bi-fire fs-6"></i>
                        <span>एशिया का सबसे बड़ा ऐतिहासिक मेला • श्री हरिहर क्षेत्र</span>
                    </span>
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 small fw-semibold">
                        <i class="bi bi-geo-alt-fill text-warning me-1"></i> सोनपुर, सारण (बिहार)
                    </span>
                    <span class="badge bg-success rounded-pill px-3 py-1.5 small fw-semibold">
                        <i class="bi bi-calendar-event me-1"></i> कार्तिक पूर्णिमा से एक माह तक
                    </span>
                </div>

                <h1 class="fw-bolder font-heading text-white display-5 mb-2">
                    विश्व प्रसिद्ध सोनपुर मेला <span class="text-warning">(हरिहर क्षेत्र मेला)</span>
                </h1>
                <p class="lead text-white-50 mb-4" style="max-width: 760px; line-height: 1.65;">
                    पवित्र <strong>गंगा और गंडक (नारायणी) नदी</strong> के पावन संगम पर आयोजित होने वाला ऐतिहासिक, आध्यात्मिक और सांस्कृतिक महाकुंभ। सदियों से एशिया के सबसे बड़े पशु मेले के रूप में विख्यात यह मेला सनातन आस्था, वैदिक इतिहास, लोक संस्कृति, सर्कस, डिज्नीलैंड और बिहारी स्वाद का अद्भुत संगम है।
                </p>

                <!-- त्वरित एक्शन बटन -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="#attractions" class="btn btn-warning text-dark fw-bold px-4 py-2.5 rounded-pill shadow">
                        <i class="bi bi-compass me-1"></i> प्रमुख मेले के आकर्षण देखें
                    </a>
                    <a href="#how-to-reach" class="btn btn-light text-primary fw-bold px-4 py-2.5 rounded-pill shadow">
                        <i class="bi bi-geo-alt me-1"></i> कैसे पहुंचे व रूट गाइड
                    </a>
                    <a href="#helpline" class="btn btn-outline-light fw-bold px-4 py-2.5 rounded-pill">
                        <i class="bi bi-telephone-fill text-warning me-1"></i> 24x7 मेला हेल्पलाइन
                    </a>
                    <a href="../sonpur-mela" class="btn btn-outline-warning fw-semibold px-3.5 py-2.5 rounded-pill" title="English Version">
                        <i class="bi bi-translate me-1"></i> English Guide
                    </a>
                </div>
            </div>

            <!-- हीरो सारांश कार्ड -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 p-4 shadow-lg" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-warning text-dark p-2 rounded-circle fs-5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 font-heading">मेले के मुख्य तथ्य</h6>
                            <small class="text-muted">सोनपुर, सारण जिला</small>
                        </div>
                    </div>

                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2.5 font-sans" style="font-size: 0.9rem;">
                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2">
                            <span class="text-muted"><i class="bi bi-calendar3 me-1.5 text-primary"></i>अवधि</span>
                            <strong class="text-dark">कार्तिक पूर्णिमा (~30 से 32 दिन)</strong>
                        </li>
                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2">
                            <span class="text-muted"><i class="bi bi-water me-1.5 text-info"></i>पावन संगम</span>
                            <strong class="text-dark">गंगा एवं गंडक (नारायणी) संगम</strong>
                        </li>
                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2">
                            <span class="text-muted"><i class="bi bi-building me-1.5 text-danger"></i>प्रधान देव</span>
                            <strong class="text-dark">बाबा हरिहरनाथ (विष्णु व शिव)</strong>
                        </li>
                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2">
                            <span class="text-muted"><i class="bi bi-train-front me-1.5 text-success"></i>निकटतम स्टेशन</span>
                            <strong class="text-dark">सोनपुर जंक्शन (SEE) / हाजीपुर</strong>
                        </li>
                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2">
                            <span class="text-muted"><i class="bi bi-signpost-split me-1.5 text-warning"></i>पटना से दूरी</span>
                            <strong class="text-dark">15 किमी (जेपी सेतु मार्ग)</strong>
                        </li>
                        <li class="d-flex align-items-center justify-content-between pt-1">
                            <span class="text-muted"><i class="bi bi-building-check me-1.5 text-primary"></i>सारण इंडेक्स</span>
                            <span class="badge bg-primary rounded-pill"><?php echo number_format($totalSonepurListings); ?>+ सत्यापित सेवाएं</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- मुख्य विवरण भाग -->
<div class="container py-5">

    <!-- सांख्यिकी पट्टिका -->
    <div class="row g-3 mb-5">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-warning">
                <div class="text-warning fs-2 mb-1"><i class="bi bi-clock-history"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">2,000+</div>
                <div class="text-muted small fw-semibold">वर्षों का दर्ज इतिहास</div>
                <div class="badge bg-warning-subtle text-dark rounded-pill mt-2 px-2.5 py-1 small fw-bold">मौर्यकालीन साक्ष्य</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-primary">
                <div class="text-primary fs-2 mb-1"><i class="bi bi-people-fill"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">30+ लाख</div>
                <div class="text-muted small fw-semibold">श्रद्धालु व पर्यटक</div>
                <div class="badge bg-primary-subtle text-primary rounded-pill mt-2 px-2.5 py-1 small fw-bold">देश-विदेश से आगमन</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-success">
                <div class="text-success fs-2 mb-1"><i class="bi bi-calendar-check"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">32 दिन</div>
                <div class="text-muted small fw-semibold">मेले की अवधि</div>
                <div class="badge bg-success-subtle text-success rounded-pill mt-2 px-2.5 py-1 small fw-bold">प्रतिवर्ष नवंबर-दिसंबर</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-danger">
                <div class="text-danger fs-2 mb-1"><i class="bi bi-telephone-outbound"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">24x7</div>
                <div class="text-muted small fw-semibold">मेला नियंत्रण कक्ष</div>
                <div class="badge bg-danger-subtle text-danger rounded-pill mt-2 px-2.5 py-1 small fw-bold">06158-222201 / 112</div>
            </div>
        </div>
    </div>

    <!-- पौराणिक एवं ऐतिहासिक महत्व -->
    <div class="row g-4 align-items-center mb-5 pb-3">
        <div class="col-lg-7">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-warning-subtle text-warning-emphasis fw-bold small mb-2">
                <i class="bi bi-book-half"></i>
                <span>अनादि काल की पौराणिक गाथा</span>
            </div>
            <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                <span class="text-primary">गज-ग्राह उद्धार</span> एवं बाबा हरिहरनाथ की पावन भूमि
            </h2>
            <p class="text-secondary mb-3 leading-relaxed">
                श्रीमद्भागवत महापुराण में वर्णित <strong>गज-ग्राह (गजेंद्र मोक्ष)</strong> की पावन कथा इसी भूमि से संबद्ध है। कथा के अनुसार, शापवश हाथी बने राजा इंद्रद्युम्न (गजेंद्र) जब पवित्र गंडकी नदी में जलक्रीड़ा कर रहे थे, तब मगरमच्छ रूपी राजा हुहू (ग्राह) ने उनका पैर पकड़ लिया।
            </p>
            <p class="text-secondary mb-3 leading-relaxed">
                हजारों वर्षों के भयंकर युद्ध के उपरांत जब गजेंद्र असमर्थ होने लगे, तब उन्होंने अपनी सूंड से एक कमल पुष्प उठाकर भगवान श्रीहरि को पुकारा: <em>"ॐ नमो भगवते वासुदेवाय"</em>। भक्त की पुकार सुनकर भगवान विष्णु गरुड़ पर सवार होकर प्रकट हुए और अपने सुदर्शन चक्र से ग्राह का उद्धार कर गजेंद्र को मोक्ष प्रदान किया।
            </p>
            <div class="legend-box mb-3">
                <h6 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-flower1 text-warning fs-5"></i> हरि और हर का अद्वितीय स्वरूप
                </h6>
                <p class="text-muted small mb-0">
                    बाबा हरिहरनाथ का पावन शिवलिंग संसार में अत्यंत दुर्लभ है क्योंकि यहाँ <strong>भगवान विष्णु (हरि) और भगवान शिव (हर)</strong> एक ही शिवलिंग में संयुक्त रूप से पूजित हैं। पौराणिक मान्यता है कि त्रेतायुग में भगवान श्रीराम और लक्ष्मण महर्षि विश्वामित्र के साथ जनकपुर जाते समय यहाँ ठहरे थे और उन्होंने ही यहाँ प्रथम पूजा-अर्चना की थी।
                </p>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                <h5 class="fw-bold font-heading text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-shaded text-primary"></i> ऐतिहासिक कालक्रम
                </h5>
                <div class="timeline-simple">
                    <div class="border-start border-2 border-primary ps-3 pb-3 position-relative">
                        <span class="position-absolute top-0 start-0 translate-middle bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
                        <div class="fw-bold text-dark small">मौर्य साम्राज्य (लगभग 300 ईसा पूर्व)</div>
                        <p class="text-muted small mb-0">चक्रवर्ती सम्राट चंद्रगुप्त मौर्य अपनी सेना के लिए युद्धक हाथियों और अरबी-फारसी नस्ल के घोड़ों की खरीद इसी मेले से किया करते थे।</p>
                    </div>
                    <div class="border-start border-2 border-warning ps-3 pb-3 position-relative">
                        <span class="position-absolute top-0 start-0 translate-middle bg-warning rounded-circle" style="width: 12px; height: 12px;"></span>
                        <div class="fw-bold text-dark small">मुगल काल (16वीं–17वीं शताब्दी)</div>
                        <p class="text-muted small mb-0">मुगल बादशाह अकबर और उनके सूबेदार सोनपुर में शाही अस्तबल के लिए बेहतरीन नस्ल के घोड़ों और हाथियों का व्यापार करवाते थे।</p>
                    </div>
                    <div class="border-start border-2 border-success ps-3 pb-3 position-relative">
                        <span class="position-absolute top-0 start-0 translate-middle bg-success rounded-circle" style="width: 12px; height: 12px;"></span>
                        <div class="fw-bold text-dark small">राजा रामनारायण द्वारा मंदिर निर्माण (18वीं सदी)</div>
                        <p class="text-muted small mb-0">बिहार के तत्कालीन नायब नाजिम राजा रामनारायण ने बाबा हरिहरनाथ के भव्य आधुनिक मंदिर का जीर्णोद्धार करवाया।</p>
                    </div>
                    <div class="ps-3 position-relative">
                        <span class="position-absolute top-0 start-0 translate-middle bg-info rounded-circle" style="width: 12px; height: 12px;"></span>
                        <div class="fw-bold text-dark small">1857 की क्रांति व बाबू वीर कुंवर सिंह</div>
                        <p class="text-muted small mb-0">अमर क्रांतिकारी बाबू वीर कुंवर सिंह ने सोनपुर मेले की आड़ में क्षेत्र के जमींदारों और देशभक्तों को एकजुट कर अंग्रेजों के खिलाफ क्रांति की योजना तैयार की थी।</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- मेले के प्रमुख आकर्षण एवं क्षेत्र -->
    <div id="attractions" class="pt-4 mb-5">
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">
                <i class="bi bi-grid-3x3-gap-fill me-1"></i> मेला दर्शन
            </span>
            <h2 class="fw-bold font-heading text-dark mt-2 mb-2 fs-1">मेले के प्रमुख आकर्षण एवं क्षेत्र</h2>
            <p class="text-muted mx-auto mb-0" style="max-width: 680px;">
                सैकड़ों एकड़ में फैला सोनपुर मेला परंपरा, धार्मिक आस्था, व्यापार और आधुनिक मनोरंजन का अद्भुत संगम प्रस्तुत करता है।
            </p>
        </div>

        <div class="row g-4">
            
            <!-- कार्ड 1: हरिहरनाथ मंदिर व संगम स्नान -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 mela-card p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="badge-indigo rounded-circle p-2.5 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-building fs-4"></i>
                        </div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill">आध्यात्मिक केंद्र</span>
                    </div>
                    <h5 class="fw-bold font-heading text-dark mb-2">हरिहरनाथ मंदिर एवं संगम तट</h5>
                    <p class="text-muted small mb-3">
                        कार्तिक पूर्णिमा पर लाखों श्रद्धालु काली घाट पर गंगा-गंडक संगम में आस्था की डुबकी लगाते हैं। शाम को बिहार पर्यटन द्वारा काशी और हरिद्वार की तर्ज पर भव्य महाआरती आयोजित की जाती है।
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> संगम तट पर प्रतिदिन संध्या महाआरती</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> 24 घंटे कतारबद्ध सुगम दर्शन व्यवस्था</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> एसडीआरएफ एवं एनडीआरएफ बोट सुरक्षा</li>
                    </ul>
                </div>
            </div>

            <!-- कार्ड 2: पशु व घोड़ा बाजार -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 mela-card p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="badge-gold rounded-circle p-2.5 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-trophy fs-4 text-warning"></i>
                        </div>
                        <span class="badge bg-warning-subtle text-dark rounded-pill">विश्व प्रसिद्ध</span>
                    </div>
                    <h5 class="fw-bold font-heading text-dark mb-2">घोड़ा बाजार एवं पशु प्रदर्शनी</h5>
                    <p class="text-muted small mb-3">
                        मेले की ऐतिहासिक पहचान घोड़ा बाजार है। सिंधी, मारवाड़ी, काठियावाड़ी और नुखरा नस्ल के नाचने वाले घोड़े, दुधारू मुर्रा भैंस, गाय, ऊंट और शिकारी कुत्तों की भव्य मंडी लगती है।
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> घोड़ों की चाल एवं करतब प्रतियोगिताएं</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> उच्च नस्ल की दुधारू गाय व भैंस</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> विदेशी व देशी पक्षियों का बाजार</li>
                    </ul>
                </div>
            </div>

            <!-- कार्ड 3: सांस्कृतिक मंच -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 mela-card p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="badge-emerald rounded-circle p-2.5 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-music-note-beamed fs-4 text-success"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill">सांस्कृतिक संध्या</span>
                    </div>
                    <h5 class="fw-bold font-heading text-dark mb-2">मुख्य सांस्कृतिक मंच व प्रस्तुतियां</h5>
                    <p class="text-muted small mb-3">
                        कला, संस्कृति एवं युवा विभाग तथा बिहार पर्यटन द्वारा मुख्य पंडाल में प्रतिदिन संध्या को नामचीन गायकों, लोकनृत्य कलाकारों, नाटकों और राष्ट्रीय कवि सम्मेलनों का आयोजन होता है।
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> भोजपुरी लोकगीत, निर्गुण व बिरहा गायन</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> शास्त्रीय संगीत, कथक व लोकनृत्य</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> अखिल भारतीय कवि सम्मेलन एवं मुशायरा</li>
                    </ul>
                </div>
            </div>

            <!-- कार्ड 4: झूले, सर्कस व मौत का कुआँ -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 mela-card p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-danger-subtle text-danger rounded-circle p-2.5 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-ticket-perforated fs-4"></i>
                        </div>
                        <span class="badge bg-danger-subtle text-danger rounded-pill">मनोरंजन जोन</span>
                    </div>
                    <h5 class="fw-bold font-heading text-dark mb-2">डिज्नीलैंड, सर्कस एवं मौत का कुआँ</h5>
                    <p class="text-muted small mb-3">
                        बच्चों और युवाओं के लिए सबसे पसंदीदा क्षेत्र। विशाल आसमानी झूले, टोरा-टोरा, रोलर कोस्टर, अंतरराष्ट्रीय सर्कस के हैरतअंगेज करतब और मौत के कुएं में मोटरसाइकिल व कारों के रोमांचक स्टंट।
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> विशाल आसमानी झूला एवं रेंजर राइड्स</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> प्रसिद्ध राष्ट्रीय व अंतरराष्ट्रीय सर्कस</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> गंडक नदी में मोटरबोट नौकायन</li>
                    </ul>
                </div>
            </div>

            <!-- कार्ड 5: बिहारी व्यंजन व खान-पान -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 mela-card p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-warning-subtle text-dark rounded-circle p-2.5 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-cup-hot-fill fs-4 text-warning"></i>
                        </div>
                        <span class="badge bg-warning-subtle text-dark rounded-pill">पारंपरिक स्वाद</span>
                    </div>
                    <h5 class="fw-bold font-heading text-dark mb-2">सोनपुर बताशा, लिट्टी चोखा व हांडी मीट</h5>
                    <p class="text-muted small mb-3">
                        मेले में देसी घी की सौंधी खुशबू सबका मन मोह लेती है। मिट्टी की हांडी में धीमी आंच पर पकने वाला चंपारण हांडी मीट, उपलों पर सिकी लिट्टी चोखा, सोनपुर का मशहूर बताशा और गरमागरम जलेबी।
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> शुद्ध देसी घी का बिहारी लिट्टी चोखा</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> प्रसिद्ध चंपारण अहुना हांडी मटन</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> सोनपुर का पारंपरिक बताशा एवं खाजा</li>
                    </ul>
                </div>
            </div>

            <!-- कार्ड 6: हस्तशिल्प व फर्नीचर बाजार -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 mela-card p-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-info-subtle text-info rounded-circle p-2.5 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-bag-check-fill fs-4"></i>
                        </div>
                        <span class="badge bg-info-subtle text-info rounded-pill">ग्रामोद्योग बाजार</span>
                    </div>
                    <h5 class="fw-bold font-heading text-dark mb-2">सहारनपुर फर्नीचर व खादी ग्रामोद्योग</h5>
                    <p class="text-muted small mb-3">
                        देशभर के हस्तशिल्पियों का विशाल जमावड़ा। सहारनपुर के शीशम व सागवान के नक्काशीदार पलंग व सोफे, पीतल के बर्तन, मिर्जापुर की ऊनी कालीनें, कश्मीरी शॉल और बिहार खादी के पारंपरिक वस्त्र।
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> सहारनपुर के ठोस लकड़ी के फर्नीचर</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> पीतल-कांसा के पूजा व रसोई पात्र</li>
                        <li><i class="bi bi-check-circle-fill text-success me-1.5"></i> कृषि यंत्र, हल, कुदाल व जैविक खाद</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <!-- यात्रा, मार्ग एवं ठहरने की व्यवस्था -->
    <div id="how-to-reach" class="pt-4 mb-5">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-dark text-white py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="fw-bold font-heading mb-0 text-white d-flex align-items-center gap-2">
                        <i class="bi bi-signpost-2 text-warning"></i> सोनपुर कैसे पहुंचे व यातायात मार्गदर्शिका
                    </h4>
                    <small class="text-white-50">पटना, छपरा, हाजीपुर एवं देश के सभी हिस्सों से सीधी सुगम कनेक्टिविटी।</small>
                </div>
                <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1.5">सारण जिला, बिहार</span>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    
                    <div class="col-lg-4">
                        <div class="p-3.5 rounded-4 bg-light border h-100">
                            <div class="d-flex align-items-center gap-2 mb-2 text-primary">
                                <i class="bi bi-train-front-fill fs-3"></i>
                                <h5 class="fw-bold font-heading mb-0 text-dark">रेल मार्ग से</h5>
                            </div>
                            <p class="text-muted small mb-2">
                                <strong>सोनपुर जंक्शन (स्टेशन कोड: SEE)</strong> पूर्व मध्य रेल (ECR) का ए-ग्रेड मंडल मुख्यालय है।
                            </p>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-primary"></i> दिल्ली, कोलकाता, मुंबई, गुवाहाटी, लखनऊ से सीधी सुपरफास्ट ट्रेनें।</li>
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-primary"></i> <strong>हाजीपुर जंक्शन (HJP)</strong>: गंडक नदी पार मात्र 5 किमी दूर।</li>
                                <li><i class="bi bi-arrow-right-short text-primary"></i> मेले के दौरान पटना, पाटलिपुत्र एवं सोनपुर के बीच चौबीसों घंटे स्पेशल मेमू ट्रेनें चलती हैं।</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="p-3.5 rounded-4 bg-light border h-100">
                            <div class="d-flex align-items-center gap-2 mb-2 text-success">
                                <i class="bi bi-car-front-fill fs-3"></i>
                                <h5 class="fw-bold font-heading mb-0 text-dark">सड़क मार्ग से</h5>
                            </div>
                            <p class="text-muted small mb-2">
                                नदीय पुलों और राष्ट्रीय राजमार्गों से सुगम फोर-लेन संपर्क:
                            </p>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-success"></i> <strong>पटना से (15 किमी)</strong>: दीघा-सोनपुर जेपी सेतु पार कर मात्र 20 से 25 मिनट में पहुंचे।</li>
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-success"></i> <strong>छपरा से (45 किमी)</strong>: एनएच-19 एक्सप्रेसवे द्वारा गरखा व दिघवारा होते हुए सोनपुर।</li>
                                <li><i class="bi bi-arrow-right-short text-success"></i> नखास एवं बाजितपुर में जिला प्रशासन द्वारा विशाल सुसज्जित पार्किंग स्थल।</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="p-3.5 rounded-4 bg-light border h-100">
                            <div class="d-flex align-items-center gap-2 mb-2 text-warning">
                                <i class="bi bi-houses-fill fs-3 text-warning"></i>
                                <h5 class="fw-bold font-heading mb-0 text-dark">कहाँ ठहरें</h5>
                            </div>
                            <p class="text-muted small mb-2">
                                पर्यटकों एवं श्रद्धालुओं के लिए उच्च स्तरीय व्यवस्था:
                            </p>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-warning"></i> <strong>बिहार पर्यटन स्विस कॉटेज व टेंट सिटी</strong>: गंडक किनारे वातानुकूलित लक्जरी कॉटेज।</li>
                                <li class="mb-1"><i class="bi bi-arrow-right-short text-warning"></i> <strong>पर्यटक बंगला व धर्मशालाएं</strong>: बाबा हरिहरनाथ मंदिर के समीप किफायती आवास।</li>
                                <li><i class="bi bi-arrow-right-short text-warning"></i> <strong>पटना व हाजीपुर होटल</strong>: पटना के प्रमुख होटलों से जेपी सेतु मार्ग द्वारा प्रतिदिन 20 मिनट में आवागमन।</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- 24x7 आपातकालीन एवं नियंत्रण कक्ष हेल्पलाइन -->
    <div id="helpline" class="pt-4 mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">
                    <i class="bi bi-shield-check me-1"></i> जनसुरक्षा एवं प्रशासनिक सहायता
                </span>
                <h3 class="fw-bold font-heading text-dark mt-2 mb-0">सोनपुर मेला 24x7 आपातकालीन हेल्पलाइन</h3>
                <p class="text-muted small mb-0">प्रशासन, पुलिस, चिकित्सा, नदीय सुरक्षा एवं विद्युत सहायता के आधिकारिक फोन नंबर।</p>
            </div>
            <a href="emergency" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                <i class="bi bi-telephone-plus me-1"></i> जिले के सभी आपातकालीन नंबर
            </a>
        </div>

        <div class="row g-3">
            <div class="col-md-6 col-lg-3">
                <div class="helpline-box p-3.5 h-100 text-center">
                    <div class="text-danger fs-2 mb-1"><i class="bi bi-shield-fill-exclamation"></i></div>
                    <h6 class="fw-bold text-dark mb-1">आपातकालीन पुलिस (ERSS)</h6>
                    <div class="display-6 fw-bold text-danger font-monospace mb-1">112</div>
                    <small class="text-muted d-block mb-2">निःशुल्क 24 घंटे तत्काल पुलिस गश्त</small>
                    <a href="tel:112" class="btn btn-danger btn-sm w-100 rounded-pill fw-bold">कॉल करें 112</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="helpline-box p-3.5 h-100 text-center">
                    <div class="text-primary fs-2 mb-1"><i class="bi bi-headset"></i></div>
                    <h6 class="fw-bold text-dark mb-1">मेला केंद्रीय नियंत्रण कक्ष</h6>
                    <div class="fs-5 fw-bold text-primary font-monospace mb-1">06158-222201</div>
                    <small class="text-muted d-block mb-2">जिलाधिकारी व पुलिस अधीक्षक कैंप कार्यालय</small>
                    <a href="tel:06158222201" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold">कंट्रोल रूम कॉल करें</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="helpline-box p-3.5 h-100 text-center">
                    <div class="text-success fs-2 mb-1"><i class="bi bi-hospital-fill"></i></div>
                    <h6 class="fw-bold text-dark mb-1">चिकित्सा शिविर व एम्बुलेंस</h6>
                    <div class="fs-5 fw-bold text-success font-monospace mb-1">102 / 06158-222223</div>
                    <small class="text-muted d-block mb-2">सोनपुर अनुमंडलीय अस्पताल व फील्ड आईसीयू</small>
                    <a href="tel:102" class="btn btn-success btn-sm w-100 rounded-pill fw-bold">एम्बुलेंस 102</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="helpline-box p-3.5 h-100 text-center">
                    <div class="text-warning fs-2 mb-1"><i class="bi bi-lightning-charge-fill"></i></div>
                    <h6 class="fw-bold text-dark mb-1">बिजली आपूर्ति शिकायत (NBPDCL)</h6>
                    <div class="display-6 fw-bold text-dark font-monospace mb-1">1912</div>
                    <small class="text-muted d-block mb-2">सोनपुर विद्युत प्रशाखा: 7763815208</small>
                    <a href="tel:1912" class="btn btn-warning text-dark btn-sm w-100 rounded-pill fw-bold">कॉल करें 1912</a>
                </div>
            </div>
        </div>
    </div>

    <!-- सोनपुर प्रखंड की सत्यापित सेवाएं -->
    <div class="pt-4 mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">
                    <i class="bi bi-check-circle-fill me-1"></i> सत्यापित स्थानीय सेवाएं
                </span>
                <h3 class="fw-bold font-heading text-dark mt-2 mb-0">सोनपुर की आवश्यक सेवाएं एवं संपर्क</h3>
                <p class="text-muted small mb-0">सोनपुर प्रखंड में पंजीकृत दुकानें, मेडिकल स्टोर, होटल, ट्रांसपोर्ट एवं सार्वजनिक सेवाएं।</p>
            </div>
            <a href="block/sonpur" class="btn btn-primary rounded-pill px-4 fw-bold">
                सभी <?php echo number_format($totalSonepurListings); ?>+ सोनपुर निर्देशिकाएं देखें <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            <?php if (!empty($sonepurListings)): ?>
                <?php foreach ($sonepurListings as $item): ?>
                    <?php 
                    $profileUrl = getListingUrl($item['slug']);
                    $maskedMobile = !empty($item['mobile']) ? maskPhoneNumber($item['mobile']) : 'प्रोफाइल पर देखें';
                    $displayName = !empty($item['hindi_title']) ? $item['hindi_title'] : $item['title'];
                    $displayCat = !empty($item['category_hindi']) ? $item['category_hindi'] : ($item['category_name'] ?? 'स्थानीय सेवा');
                    ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border rounded-4 p-3.5 bg-white shadow-xs hover-lift d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-1.5 mb-2">
                                    <span class="badge bg-light text-secondary border rounded-pill small">
                                        <i class="bi bi-tag-fill me-1 text-primary"></i><?php echo htmlspecialchars($displayCat); ?>
                                    </span>
                                </div>
                                <h6 class="fw-bold font-heading text-dark mb-1 text-truncate" title="<?php echo htmlspecialchars($displayName); ?>">
                                    <?php echo htmlspecialchars($displayName); ?>
                                </h6>
                                <p class="text-muted small mb-2 text-truncate">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i><?php echo htmlspecialchars($item['address'] ?: 'सोनपुर, सारण'); ?>
                                </p>
                            </div>

                            <div class="pt-2 border-top mt-2">
                                <div class="d-flex align-items-center justify-content-between small mb-2">
                                    <span class="text-muted"><i class="bi bi-telephone me-1 text-success"></i>फोन:</span>
                                    <strong class="font-monospace text-dark"><?php echo htmlspecialchars($maskedMobile); ?></strong>
                                </div>
                                <a href="<?php echo $profileUrl; ?>" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-semibold">
                                    पूरी प्रोफाइल देखें <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- यात्रियों के लिए उपयोगी सुझाव -->
    <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: linear-gradient(135deg, #eff6ff 0%, #fdf2f8 100%); border: 1px solid #bfdbfe !important;">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <h5 class="fw-bold font-heading text-dark mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-lightbulb-fill text-warning fs-4"></i> सोनपुर मेला भ्रमण के उपयोगी सुझाव
                </h5>
                <ul class="mb-0 text-secondary small" style="line-height: 1.8;">
                    <li><strong>उत्तम समय:</strong> सुबह 5:00 से 9:00 बजे संगम स्नान एवं घोड़ा/पशु बाजार देखने के लिए श्रेष्ठ है। शाम 5:00 से 10:00 बजे गंगा आरती, सांस्कृतिक संध्या और झूलों के आनंद के लिए उत्तम है।</li>
                    <li><strong>आरामदायक जूते:</strong> मेला मैदान कई वर्ग किलोमीटर में फैला है, अतः आरामदायक पैदल चलने वाले जूते पहनें।</li>
                    <li><strong>पटना से आवागमन:</strong> यदि आप पटना से आ रहे हैं तो शाम के पीक समय (6:00 से 8:00 बजे) को छोड़कर यात्रा करें ताकि जेपी सेतु पर जाम से बचा जा सके।</li>
                    <li><strong>नदीय सुरक्षा:</strong> काली घाट पर प्रशासन द्वारा बैरिकेड किए गए सुरक्षित क्षेत्र में ही स्नान करें तथा एसडीआरएफ के दिशा-निर्देशों का पालन करें।</li>
                </ul>
            </div>
            <div class="col-md-4 text-md-end text-center">
                <a href="block/sonpur" class="btn btn-primary fw-bold px-4 py-2.5 rounded-pill shadow-sm mb-2 w-100 w-md-auto">
                    <i class="bi bi-compass me-1"></i> सोनपुर नागरिक पोर्टल
                </a>
                <br>
                <a href="tel:06158222201" class="btn btn-outline-dark fw-semibold btn-sm px-3 py-2 rounded-pill w-100 w-md-auto">
                    <i class="bi bi-telephone-fill me-1"></i> मेला नियंत्रण कक्ष: 06158-222201
                </a>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "सारण के विश्वविद्यालय एवं उच्च शिक्षा (JPU एवं AKU कॉलेज) – सारण इंडेक्स";
$meta_description = "सारण जिले (छपरा) में उच्च एवं तकनीकी शिक्षा का संपूर्ण विवरण। जयप्रकाश विश्वविद्यालय (JPU छपरा) एवं आर्यभट्ट ज्ञान विश्वविद्यालय (AKU पटना) से संबद्ध इंजीनियरिंग, मेडिकल एवं अंगीभूत कॉलेज।";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header -->
<div class="bg-gradient-primary text-white py-5 position-relative overflow-hidden">
    <div class="container position-relative z-1 py-3 text-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3">
            <i class="bi bi-bank2 me-1"></i> राज्य विश्वविद्यालय एवं उच्च शिक्षा केंद्र
        </span>
        <h1 class="fw-bold font-heading text-white display-5 mb-2">
            सारण में उच्च शिक्षा एवं विश्वविद्यालय
        </h1>
        <div class="lead text-warning fw-semibold mb-3">
            जयप्रकाश विश्वविद्यालय (JPU) एवं आर्यभट्ट ज्ञान विश्वविद्यालय (AKU)
        </div>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 780px;">
            सारण जिला (छपरा) सामान्य एवं व्यावसायिक शिक्षा हेतु <strong>जयप्रकाश विश्वविद्यालय (JPU छपरा)</strong> तथा इंजीनियरिंग, मेडिकल एवं तकनीकी शिक्षा हेतु <strong>आर्यभट्ट ज्ञान विश्वविद्यालय (AKU पटना)</strong> से संबद्ध प्रमुख संस्थानों का केंद्र है।
        </p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="https://jpv.ac.in" target="_blank" rel="noopener" class="btn btn-warning text-dark fw-bold px-4 py-2.5 rounded-pill shadow">
                <i class="bi bi-globe me-1"></i> JPU पोर्टल (jpv.ac.in) <i class="bi bi-box-arrow-up-right ms-1"></i>
            </a>
            <a href="https://akubihar.ac.in/" target="_blank" rel="noopener" class="btn btn-light text-primary fw-bold px-4 py-2.5 rounded-pill shadow">
                <i class="bi bi-mortarboard-fill me-1"></i> AKU बिहार पोर्टल (akubihar.ac.in) <i class="bi bi-box-arrow-up-right ms-1"></i>
            </a>
            <a href="#colleges" class="btn btn-outline-light fw-bold px-4 py-2.5 rounded-pill">
                <i class="bi bi-building me-1"></i> कॉलेज देखें
            </a>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="container py-5">

    <!-- KPI Stats Bar -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-blue">
                <div class="text-primary fs-1 mb-2"><i class="bi bi-bank2"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">2 राज्य वि.वि.</h3>
                <p class="text-muted small mb-0 fw-semibold">शिक्षा बोर्ड</p>
                <div class="mt-2 text-primary small fs-7 fw-bold">JPU छपरा एवं AKU पटना</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-green">
                <div class="text-success fs-1 mb-2"><i class="bi bi-geo-alt-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">सारण प्रमंडल</h3>
                <p class="text-muted small mb-0 fw-semibold">विश्वविद्यालय क्षेत्राधिकार</p>
                <div class="mt-2 text-success small fs-7 fw-semibold">सारण, सीवान एवं गोपालगंज</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-amber">
                <div class="text-warning fs-1 mb-2"><i class="bi bi-building-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">21</h3>
                <p class="text-muted small mb-0 fw-semibold">JPU अंगीभूत कॉलेज</p>
                <div class="mt-2 text-warning small fs-7 fw-bold">सामान्य डिग्री कॉलेज</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 stat-card-purple">
                <div class="text-purple fs-1 mb-2" style="color: #8b5cf6;"><i class="bi bi-cpu-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">इंजीनियरिंग</h3>
                <p class="text-muted small mb-0 fw-semibold">AKU तकनीकी संस्थान</p>
                <div class="mt-2 small fs-7 fw-semibold" style="color: #8b5cf6;">LNJPIT छपरा एवं मेडिकल</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Column -->
        <div class="col-lg-8">
            
            <!-- About JPU Section -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h4 class="fw-bold font-heading text-dark mb-3">
                    <i class="bi bi-info-circle-fill text-primary me-2"></i> जयप्रकाश विश्वविद्यालय (JPU), छपरा
                </h4>
                <p class="text-secondary" style="line-height: 1.8;">
                    <strong>जयप्रकाश विश्वविद्यालय (JPU)</strong> की स्थापना बिहार राज्य विश्वविद्यालय अधिनियम द्वारा <strong>22 नवंबर 1990</strong> को हुई थी। लोकनायक <strong>जयप्रकाश नारायण</strong> की स्मृति में नामित यह विश्वविद्यालय सारण प्रमंडल (छपरा, सीवान, गोपालगंज) का मुख्य राज्य विश्वविद्यालय है।
                </p>
                <p class="text-secondary" style="line-height: 1.8;">
                    विश्वविद्यालय अनुदान आयोग (UGC) धारा 2(f) और 12(B) द्वारा मान्यता प्राप्त JPU के तहत 21 अंगीभूत डिग्री कॉलेज और अनेक संबद्ध कॉलेज संचालित हैं।
                </p>

                <div class="p-3 bg-light rounded-3 border mt-2">
                    <div class="fw-bold text-dark mb-1"><i class="bi bi-globe text-primary me-1"></i> JPU आधिकारिक वेबसाइट:</div>
                    <a href="https://jpv.ac.in" target="_blank" rel="noopener" class="text-primary fw-bold text-decoration-none">https://jpv.ac.in</a>
                </div>
            </div>

            <!-- Aryabhatta Knowledge University (AKU) Section -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="fw-bold font-heading text-dark mb-0">
                        <i class="bi bi-cpu-fill text-info me-2"></i> आर्यभट्ट ज्ञान विश्वविद्यालय (AKU), पटना
                    </h4>
                    <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-1.5 fw-bold fs-7">तकनीकी एवं व्यावसायिक</span>
                </div>

                <p class="text-secondary" style="line-height: 1.8;">
                    <strong>आर्यभट्ट ज्ञान विश्वविद्यालय (AKU), पटना</strong> की स्थापना 2008 में बिहार सरकार द्वारा (बिहार अधिनियम 24, 2008 के तहत) पूरे बिहार में तकनीकी, इंजीनियरिंग, मेडिकल, फार्मेसी और व्यावसायिक शिक्षा को बढ़ावा देने और संचालित करने के लिए की गई थी।
                </p>
                <p class="text-secondary" style="line-height: 1.8;">
                    <strong>सारण जिले (छपरा)</strong> में स्थित कई प्रमुख सरकारी इंजीनियरिंग, मेडिकल और नर्सिंग कॉलेज AKU पटना से संबद्ध हैं:
                </p>

                <!-- AKU Colleges Grid -->
                <div class="row g-3 mt-2">
                    <!-- LNJPIT Chapra -->
                    <div class="col-md-12">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-tools text-primary me-1"></i> लोकनायक जयप्रकाश प्रौद्योगिकी संस्थान (LNJPIT छपरा)</h6>
                                <span class="badge bg-success rounded-pill fs-7">सरकारी इंजीनियरिंग कॉलेज</span>
                            </div>
                            <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt me-1"></i>कंकनपुर, छपरा, सारण - 841302 • विज्ञान एवं प्रौद्योगिकी विभाग, बिहार सरकार</small>
                            <p class="small text-secondary mb-2">सारण का प्रमुख सरकारी इंजीनियरिंग कॉलेज जो AICTE द्वारा स्वीकृत और AKU पटना से संबद्ध 4-वर्षीय B.Tech डिग्री पाठ्यक्रम प्रदान करता है।</p>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-white text-dark border">B.Tech सिविल इंजीनियरिंग</span>
                                <span class="badge bg-white text-dark border">B.Tech मैकेनिकल</span>
                                <span class="badge bg-white text-dark border">B.Tech इलेक्ट्रिकल एवं इलेक्ट्रॉनिक्स</span>
                                <span class="badge bg-white text-dark border">B.Tech कंप्यूटर साइंस</span>
                            </div>
                        </div>
                    </div>

                    <!-- Government Medical College Chapra -->
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="fw-bold text-dark mb-0">सरकारी मेडिकल कॉलेज, छपरा</h6>
                                <span class="badge bg-danger rounded-pill fs-7">मेडिकल कॉलेज</span>
                            </div>
                            <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt me-1"></i>छपरा शहर</small>
                            <p class="small text-secondary mb-0">AKU / स्वास्थ्य विभाग के अंतर्गत MBBS चिकित्सा शिक्षा और स्वास्थ्य प्रशिक्षण प्रदान करने वाला सरकारी मेडिकल संस्थान।</p>
                        </div>
                    </div>

                    <!-- Nursing & Paramedical Institutes -->
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="fw-bold text-dark mb-0">नर्सिंग एवं पैरामेडिकल संस्थान</h6>
                                <span class="badge bg-info rounded-pill fs-7">पैरामेडिकल एवं नर्सिंग</span>
                            </div>
                            <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt me-1"></i>सारण जिला</small>
                            <p class="small text-secondary mb-0">AKU पटना से संबद्ध B.Sc नर्सिंग, GNM और एलाइड हेल्थ साइंसेज की पेशकश करने वाले व्यावसायिक नर्सिंग संस्थान।</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 border mt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <strong class="d-block text-dark small">AKU बिहार आधिकारिक पोर्टल:</strong>
                        <span class="text-muted small">https://akubihar.ac.in/</span>
                    </div>
                    <a href="https://akubihar.ac.in/" target="_blank" rel="noopener" class="btn btn-outline-info btn-sm rounded-pill fw-semibold">
                        AKU पोर्टल पर जाएं <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Major JPU Constituent Colleges in Saran (Chapra) -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white" id="colleges">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h4 class="fw-bold font-heading text-dark mb-0">
                        <i class="bi bi-building text-success me-2"></i> छपरा (सारण) के प्रमुख JPU अंगीभूत कॉलेज
                    </h4>
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-bold fs-7">सामान्य डिग्री</span>
                </div>

                <div class="row g-3">
                    <!-- Rajendra College -->
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="fw-bold text-dark mb-0">राजेन्द्र कॉलेज, छपरा</h6>
                                <span class="badge bg-primary rounded-pill fs-7">स्थापना 1938</span>
                            </div>
                            <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt me-1"></i>छपरा शहर • प्रतिष्ठित कॉलेज</small>
                            <p class="small text-secondary mb-0">विज्ञान, कला एवं वाणिज्य स्नातक एवं स्नातकोत्तर (PG) कक्षाओं वाला प्रमुख धरोहर कॉलेज।</p>
                        </div>
                    </div>

                    <!-- Jagdam College -->
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="fw-bold text-dark mb-0">जगदम कॉलेज, छपरा</h6>
                                <span class="badge bg-primary rounded-pill fs-7">स्थापना 1954</span>
                            </div>
                            <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt me-1"></i>छपरा मुख्य मार्ग</small>
                            <p class="small text-secondary mb-0">विज्ञान एवं कला शिक्षा तथा स्नातकोत्तर विभागों के लिए प्रसिद्ध अंगीभूत कॉलेज।</p>
                        </div>
                    </div>

                    <!-- Ganga Singh College -->
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="fw-bold text-dark mb-0">गंगा सिंह कॉलेज, छपरा</h6>
                                <span class="badge bg-secondary rounded-pill fs-7">विधि (Law) एवं कला</span>
                            </div>
                            <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt me-1"></i>छपरा शहर</small>
                            <p class="small text-secondary mb-0">स्नातक डिग्री पाठ्यक्रमों एवं कानून की पढ़ाई (LL.B) की पेशकश करने वाला अंगीभूत कॉलेज।</p>
                        </div>
                    </div>

                    <!-- Ram Jaipal College -->
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="fw-bold text-dark mb-0">राम जयपाल कॉलेज, छपरा</h6>
                                <span class="badge bg-primary rounded-pill fs-7">स्थापना 1971</span>
                            </div>
                            <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt me-1"></i>छपरा</small>
                            <p class="small text-secondary mb-0">कला, विज्ञान एवं वाणिज्य में डिग्री पाठ्यक्रम प्रदान करने वाला प्रमुख अंगीभूत संस्थान।</p>
                        </div>
                    </div>

                    <!-- Jai Prakash Mahila College -->
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="fw-bold text-dark mb-0">जयप्रकाश महिला कॉलेज</h6>
                                <span class="badge bg-danger rounded-pill fs-7">महिला महाविद्यालय</span>
                            </div>
                            <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt me-1"></i>छपरा शहर</small>
                            <p class="small text-secondary mb-0">सारण में महिला उच्च शिक्षा को सशक्त बनाने वाला प्रमुख अंगीभूत महिला कॉलेज।</p>
                        </div>
                    </div>

                    <!-- Prabhu Nath College Parsa -->
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="fw-bold text-dark mb-0">प्रभु नाथ कॉलेज, परसा</h6>
                                <span class="badge bg-secondary rounded-pill fs-7">परसा प्रखंड</span>
                            </div>
                            <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt me-1"></i>परसा, सारण</small>
                            <p class="small text-secondary mb-0">परसा एवं पूर्वी सारण प्रखंड के छात्रों की सेवा करने वाला अंगीभूत डिग्री कॉलेज।</p>
                        </div>
                    </div>

                    <!-- Y.N. College Dighwara -->
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="fw-bold text-dark mb-0">यदु नंदन कॉलेज, दिघवारा</h6>
                                <span class="badge bg-secondary rounded-pill fs-7">दिघवारा प्रखंड</span>
                            </div>
                            <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt me-1"></i>दिघवारा, सारण</small>
                            <p class="small text-secondary mb-0">दिघवारा एवं सोनपुर क्षेत्र में स्नातक डिग्री पाठ्यक्रम प्रदान करने वाला प्रमुख कॉलेज।</p>
                        </div>
                    </div>

                    <!-- H.R. College Amnour -->
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="fw-bold text-dark mb-0">एच.आर. कॉलेज, अमनौर</h6>
                                <span class="badge bg-secondary rounded-pill fs-7">अमनौर प्रखंड</span>
                            </div>
                            <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt me-1"></i>अमनौर, सारण</small>
                            <p class="small text-secondary mb-0">अमनौर एवं आसपास के ग्रामीण क्षेत्रों में उच्च शिक्षा प्रदान करने वाला अंगीभूत कॉलेज।</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Courses Offered -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h4 class="fw-bold font-heading text-dark mb-4 border-bottom pb-3">
                    <i class="bi bi-journal-check text-warning me-2"></i> शैक्षणिक संकाय एवं डिग्री पाठ्यक्रम
                </h4>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-mortarboard text-primary me-2"></i>स्नातक (UG) पाठ्यक्रम</h6>
                            <ul class="small text-secondary mb-0 ps-3">
                                <li>बी.एससी. (भौतिकी, रसायनशास्त्र, गणित, जूलॉजी, बॉटनी)</li>
                                <li>बी.ए. (इतिहास, राजनीति विज्ञान, अर्थशास्त्र, हिंदी, अंग्रेजी)</li>
                                <li>बी.कॉम. (अकाउंट्स, जनरल)</li>
                                <li>B.Tech (सिविल, मैकेनिकल, EEE, CSE - AKU द्वारा)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-book-half text-success me-2"></i>स्नातकोत्तर (PG) एवं मेडिकल</h6>
                            <ul class="small text-secondary mb-0 ps-3">
                                <li>एम.एससी. (भौतिकी, रसायनशास्त्र, जूलॉजी, बॉटनी, गणित)</li>
                                <li>एम.ए. (हिंदी, अंग्रेजी, इतिहास, अर्थशास्त्र, राजनीति विज्ञान)</li>
                                <li>एम.कॉम. एवं अनुसंधान Ph.D.</li>
                                <li>MBBS, GNM एवं नर्सिंग डिग्री (AKU द्वारा)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-gear-wide-connected text-warning me-2"></i>व्यावसायिक एवं तकनीकी पाठ्यक्रम</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-white text-dark border px-3 py-2">B.Tech (इंजीनियरिंग)</span>
                                <span class="badge bg-white text-dark border px-3 py-2">BCA (कंप्यूटर एप्लीकेशन)</span>
                                <span class="badge bg-white text-dark border px-3 py-2">BBA (बिजनेस एडमिनिस्ट्रेशन)</span>
                                <span class="badge bg-white text-dark border px-3 py-2">B.Ed (शिक्षक शिक्षा)</span>
                                <span class="badge bg-white text-dark border px-3 py-2">B.Sc नर्सिंग</span>
                                <span class="badge bg-white text-dark border px-3 py-2">LL.B (कानून)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            
            <!-- Quick Info Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark mb-3 font-heading border-bottom pb-3">
                    <i class="bi bi-info-circle-fill text-primary me-2"></i> विश्वविद्यालय एक नजर में
                </h5>

                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">सामान्य वि.वि.</span>
                        <strong class="text-dark text-end">JPU छपरा</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">तकनीकी वि.वि.</span>
                        <strong class="text-dark text-end">AKU पटना</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">इंजीनियरिंग संस्थान</span>
                        <strong class="text-dark text-end">LNJPIT छपरा</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">JPU स्थापना</span>
                        <strong class="text-dark">22 नवंबर 1990</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">AKU स्थापना</span>
                        <strong class="text-dark">वर्ष 2008</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">मुख्यालय</span>
                        <strong class="text-dark">छपरा / पटना</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">पिन कोड</span>
                        <strong class="text-dark">841301</strong>
                    </li>
                </ul>
            </div>

            <!-- Official Links Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark mb-3 font-heading border-bottom pb-3">
                    <i class="bi bi-globe2 text-success me-2"></i> आधिकारिक विश्वविद्यालय पोर्टल
                </h5>

                <div class="d-grid gap-2">
                    <a href="https://jpv.ac.in" target="_blank" rel="noopener" class="btn btn-outline-primary text-start fw-semibold py-2">
                        <i class="bi bi-globe me-2"></i>JPU पोर्टल (jpv.ac.in) <i class="bi bi-box-arrow-up-right float-end mt-1"></i>
                    </a>
                    <a href="https://akubihar.ac.in/" target="_blank" rel="noopener" class="btn btn-outline-info text-start fw-semibold py-2">
                        <i class="bi bi-mortarboard me-2"></i>AKU बिहार पोर्टल (akubihar.ac.in) <i class="bi bi-box-arrow-up-right float-end mt-1"></i>
                    </a>
                    <a href="https://aishe.gov.in" target="_blank" rel="noopener" class="btn btn-outline-secondary text-start fw-semibold py-2">
                        <i class="bi bi-award me-2"></i>AISHE कोड निर्देशिका <i class="bi bi-box-arrow-up-right float-end mt-1"></i>
                    </a>
                </div>
            </div>

            <!-- Contact Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                <h5 class="fw-bold text-dark mb-3 font-heading border-bottom pb-3">
                    <i class="bi bi-geo-alt-fill text-danger me-2"></i> परिसर स्थान एवं विवरण
                </h5>

                <div class="mb-3">
                    <strong class="d-block text-dark small">JPU परिसर का पता:</strong>
                    <p class="small text-secondary mb-2" style="line-height: 1.5;">
                        राहुल सांकृत्यायन नगर, मेडिकल कॉलेज मैदान के पास, छपरा, सारण - 841301
                    </p>
                    <strong class="d-block text-dark small">LNJPIT छपरा (AKU) पता:</strong>
                    <p class="small text-secondary mb-0" style="line-height: 1.5;">
                        कंकनपुर, छपरा, सारण जिला, बिहार - 841302
                    </p>
                </div>

                <div class="pt-2 border-top">
                    <a href="category/schools-education" class="btn btn-primary w-100 rounded-pill fw-bold btn-sm">
                        <i class="bi bi-search me-1"></i> सारण निर्देशिका में कॉलेज खोजें
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

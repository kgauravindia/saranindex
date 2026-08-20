<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "History of Saran District (Chapra) – Ancient Chirand to Modern Era | Saran Index";
$meta_description = "Discover the glorious history and heritage of Saran District (Chapra, Bihar). Explore the 4,500-year-old Chirand Neolithic civilization, Gautam Rishi Ashram, Loknayak JP, Bhikhari Thakur, Sarkar Saran in Mughal era, and the freedom movement.";
$meta_keywords = "History of Saran, Saran District History, Chapra History, Chirand Archaeological Site, Loknayak Jai Prakash Narayan, Bhikhari Thakur, Gautam Ashram Revelganj, Hariharnath Sonepur, Sarkar Saran, Bihar History";

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
            <i class="bi bi-hourglass-split me-1"></i> 4,500+ Years of Glorious Heritage
        </span>
        <h1 class="fw-bold font-heading text-white display-5 mb-2">
            History & Heritage of Saran District
        </h1>
        <div class="lead text-warning fw-semibold mb-3">
            सारण का ऐतिहासिक एवं सांस्कृतिक गौरव – चिरांद सभ्यता से आधुनिक सारण तक
        </div>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 820px;">
            Situated between the sacred confluence of the <strong>Ganga, Ghaghra (Sarayu), and Gandak</strong> rivers, Saran is a cradle of ancient Neolithic civilization, the hermitage of legendary Vedic rishis, the epicenter of India’s freedom revolution, and the immortal land of Bhojpuri art and culture.
        </p>

        <!-- Quick Jump Filter Badges -->
        <div class="d-flex justify-content-center gap-2 flex-wrap mb-2">
            <a href="#etymology" class="quick-nav-pill"><i class="bi bi-tag-fill text-warning"></i> Origins & Etymology</a>
            <a href="#chirand" class="quick-nav-pill"><i class="bi bi-gem text-info"></i> Chirand Neolithic Site</a>
            <a href="#mughal-colonial" class="quick-nav-pill"><i class="bi bi-bank text-success"></i> Sarkar Saran & Colonial Era</a>
            <a href="#freedom-movement" class="quick-nav-pill"><i class="bi bi-flag-fill text-danger"></i> Freedom Struggle & JP</a>
            <a href="#cultural-icons" class="quick-nav-pill"><i class="bi bi-music-note-beamed text-primary"></i> Bhikhari Thakur & Culture</a>
            <a href="#sacred-sites" class="quick-nav-pill"><i class="bi bi-geo-alt-fill text-warning"></i> Sacred Heritage Sites</a>
            <a href="#timeline" class="quick-nav-pill"><i class="bi bi-clock-history text-secondary"></i> Chronology</a>
            <a href="#sources" class="quick-nav-pill"><i class="bi bi-shield-check text-success"></i> Official Sources</a>
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
                <h3 class="fw-bolder text-dark mb-1 fs-2">2500 BCE</h3>
                <p class="text-muted small mb-0 fw-semibold">Ancient Chirand Era</p>
                <div class="mt-2 text-primary small fs-7 fw-bold">Neolithic Bone Tool Culture</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-warning">
                <div class="text-warning fs-1 mb-2"><i class="bi bi-bank2"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">1582 CE</h3>
                <p class="text-muted small mb-0 fw-semibold">Sarkar Saran (Mughal)</p>
                <div class="mt-2 text-warning small fs-7 fw-bold">Recorded in Ain-i-Akbari</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-success">
                <div class="text-success fs-1 mb-2"><i class="bi bi-flag-fill"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">1942 & 1974</h3>
                <p class="text-muted small mb-0 fw-semibold">Epicenter of Revolutions</p>
                <div class="mt-2 text-success small fs-7 fw-bold">Quit India & Sampoorna Kranti</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white border-top border-4 border-info">
                <div class="text-info fs-1 mb-2"><i class="bi bi-water"></i></div>
                <h3 class="fw-bolder text-dark mb-1 fs-2">Triveni</h3>
                <p class="text-muted small mb-0 fw-semibold">River Confluence</p>
                <div class="mt-2 text-info small fs-7 fw-bold">Ganga • Sarayu • Gandak</div>
            </div>
        </div>
    </div>

    <!-- Section 1: Origins & Etymology -->
    <div id="etymology" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill small">Ancient Roots</span>
                    <span class="text-muted small fw-semibold">Origin of the Name "Saran"</span>
                </div>
                <h2 class="fw-bold font-heading text-dark display-6 mb-3">
                    The Origin & Mythological Roots of Saran
                </h2>
                <p class="text-secondary" style="line-height: 1.8;">
                    The name <strong>Saran</strong> carries deep etymological roots in ancient Sanskrit literature and traditions:
                </p>
                <ul class="text-secondary mb-3" style="line-height: 1.8;">
                    <li><strong>Saranga-Aranya (सारंग अरण्य):</strong> According to historical traditions, the region was originally known as <em>Saranga-aranya</em>, translating to the <em>"Forest of Spotted Deer"</em> or <em>"Forest of Peacocks"</em>. The dense riverine forests nourished by the Ganga, Sarayu, and Gandak were once rich sanctuaries of wildlife and ancient hermits.</li>
                    <li><strong>Sharanam (शरणम् – Asylum / Refuge):</strong> Another prevalent belief traces the name to <em>Sharan</em> (meaning refuge or sanctuary), referring to the hermitage of revered Vedic sages where spiritual seekers and kings sought divine peace and shelter.</li>
                    <li><strong>Association with Gautama Rishi:</strong> Godna (modern-day Revelganj in Saran) is traditionally recognized as the hermitage (Ashram) of <strong>Maharishi Gautama</strong>, the proponent of Nyaya philosophy, and the site of the legendary deliverance of Ahilya (<em>Ahilya Uddhar</em>) by Lord Rama during his journey with Maharishi Vishwamitra.</li>
                </ul>

                <div class="quote-box">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-quote text-warning fs-3 mt-n1"></i>
                        <div class="small text-dark fw-medium">
                            <em>"Saran represents that sacred tract where Vedic philosophy, Ramayana heritage, and the mighty confluence of three holy rivers converged to give birth to early Indian civilization."</em>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="p-4 rounded-4 bg-light border">
                    <h5 class="fw-bold text-dark font-heading mb-3"><i class="bi bi-compass-fill text-primary me-2"></i>Geographical Highlights</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-3">
                            <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">Headquarters</strong>
                                <span class="text-muted small">Chapra (छपरा) — situated on the northern bank of Ghaghra (Sarayu).</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                <i class="bi bi-water"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">River Boundaries</strong>
                                <span class="text-muted small">Flanked by <strong>Ganga</strong> on the south, <strong>Ghaghra</strong> on the south-west, and <strong>Gandak</strong> on the east.</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">Administrative Division</strong>
                                <span class="text-muted small">Division HQ since 1981, administering <strong>Saran, Siwan, and Gopalganj</strong> districts.</span>
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
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill small">Archaeological Landmark</span>
            <span class="text-muted small fw-semibold">Global Significance</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            Chirand: The 4,500-Year-Old Neolithic Wonder
        </h2>
        <p class="text-secondary lead fs-6" style="line-height: 1.8;">
            Located approximately 11 km south-east of Chapra near Dighwara on the northern bank of the Ganga river, <strong>Chirand</strong> is celebrated internationally as one of India's most extraordinary archaeological discoveries.
        </p>

        <div class="row g-4 mt-2">
            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <h5 class="fw-bold text-dark font-heading mb-3"><i class="bi bi-search text-primary me-2"></i>Archaeological Discoveries at Chirand</h5>
                    <ul class="text-secondary small mb-0 d-flex flex-column gap-2" style="line-height: 1.7;">
                        <li><strong>Neolithic Bone Tools:</strong> Chirand is world-famous for its massive collection of sophisticated antler and bone tools, needles, scrapers, arrowheads, and chisels — unique in northern India.</li>
                        <li><strong>Circular Dwelling Structures:</strong> Excavations revealed circular wattle-and-daub huts with paved clay floors and central hearths dating back to circa 2500–1500 BCE.</li>
                        <li><strong>Earliest Agriculture:</strong> Evidence of cultivated rice, wheat, barley, lentils, and mung bean, establishing Chirand as an advanced agrarian society.</li>
                        <li><strong>Terracotta Figurines & Beads:</strong> Beautiful baked clay figurines of bulls, birds, serpents, and beads of chalcedony, agate, jasper, and steatite.</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <h5 class="fw-bold text-dark font-heading mb-3"><i class="bi bi-layers-fill text-success me-2"></i>Stratigraphic Cultural Sequences</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="border-start border-3 border-primary ps-3">
                            <strong class="text-dark small d-block">Period I: Neolithic Era (c. 2500 BCE – 1500 BCE)</strong>
                            <span class="text-muted small">Red and grey ware pottery, microliths, deer-antler tools, and early pastoral-farming life.</span>
                        </div>
                        <div class="border-start border-3 border-warning ps-3">
                            <strong class="text-dark small d-block">Period II: Chalcolithic Era (c. 1500 BCE – 800 BCE)</strong>
                            <span class="text-muted small">Black-and-Red Ware pottery, copper tools, and expanded riverine trade.</span>
                        </div>
                        <div class="border-start border-3 border-success ps-3">
                            <strong class="text-dark small d-block">Period III & IV: NBPW & Early Historic (c. 800 BCE – 300 CE)</strong>
                            <span class="text-muted small">Northern Black Polished Ware, iron implements, punch-marked coins, and Mauryan/Kushan settlements.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Medieval Era, Mughal Period & River Port Trade -->
    <div id="mughal-colonial" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-1.5 rounded-pill small">Administration & Commerce</span>
            <span class="text-muted small fw-semibold">16th to 19th Century</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            Sarkar Saran, Riverine Trade & Colonial History
        </h2>
        <div class="row g-4">
            <div class="col-lg-7">
                <p class="text-secondary" style="line-height: 1.8;">
                    During the reign of Mughal Emperor Akbar (1556–1605), Saran was designated as a distinct revenue division called <strong>Sarkar Saran</strong> under the *Subah of Bihar*, as recorded in Abul Fazl’s famous <strong>Ain-i-Akbari (1582)</strong>. It comprised 17 mahals (parganas) encompassing modern Saran, Siwan, Gopalganj, and parts of Champaran.
                </p>
                <p class="text-secondary" style="line-height: 1.8;">
                    <strong>The Golden Era of River Trade:</strong> With the Ghaghra and Ganga serving as national waterways long before railways, Chapra and Revelganj (Godna) became buzzing inland ports. European merchants — including the Dutch, French, Portuguese, and British East India Company — established commercial factories in Chapra.
                </p>
                <div class="p-3 bg-light rounded-3 border">
                    <strong class="text-dark small d-block mb-1"><i class="bi bi-box-seam text-warning me-1"></i> Major Trade Commodities:</strong>
                    <p class="text-muted small mb-0">
                        Saran was India’s prime center for the manufacture and export of <strong>Saltpetre (शोरा - potassium nitrate)</strong>, crucial for gunpowder manufacturing in Europe, alongside indigo, opium, cotton fabrics, and food grains.
                    </p>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 bg-dark text-white rounded-4 p-4 h-100">
                    <h5 class="fw-bold text-warning font-heading mb-3"><i class="bi bi-diagram-2 me-2"></i>Administrative Evolution</h5>
                    <div class="d-flex flex-column gap-3 small text-white-50">
                        <div>
                            <strong class="text-white d-block">1766: Revenue District Formation</strong>
                            After the Battle of Buxar (1764) and Treaty of Allahabad (1765), Saran was constituted as a British revenue district.
                        </div>
                        <div>
                            <strong class="text-white d-block">1866: Separation of Champaran</strong>
                            Champaran was partitioned into a separate district, leaving Saran with Chapra, Siwan, and Gopalganj subdivisions.
                        </div>
                        <div>
                            <strong class="text-white d-block">1972: Creation of Siwan & Gopalganj</strong>
                            In October 1972, Siwan and Gopalganj were carved out as independent districts.
                        </div>
                        <div>
                            <strong class="text-white d-block">1981: Saran Commissionery</strong>
                            Saran Division was formally created with Chapra as its Divisional Headquarters.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Freedom Movement & Iconic Leaders -->
    <div id="freedom-movement" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-danger text-white fw-bold px-3 py-1.5 rounded-pill small">Struggle for Independence</span>
            <span class="text-muted small fw-semibold">The Land of Revolutionaries</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            Saran’s Glorious Role in India’s Freedom Struggle
        </h2>
        <p class="text-secondary lead fs-6 mb-4" style="line-height: 1.8;">
            From the Champaran Satyagraha in 1917 to the storm of the 1942 Quit India Movement and the 1974 Total Revolution, the soil of Saran has continuously birthed towering patriots who shaped modern India.
        </p>

        <div class="row g-4">
            <!-- JP -->
            <div class="col-md-6 col-lg-4">
                <div class="personality-card p-4 rounded-4 shadow-sm border h-100">
                    <span class="badge bg-warning text-dark fw-bold mb-2">Loknayak</span>
                    <h4 class="fw-bold text-dark font-heading mb-1">Jai Prakash Narayan</h4>
                    <p class="text-muted small mb-2">Born: Sitab Diara, Saran (1902–1979)</p>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        Legendary freedom fighter, hero of the 1942 Quit India movement (famed Hazaribagh jailbreak), and the supreme leader of the 1974 <strong>Sampoorna Kranti (Total Revolution)</strong> that defended Indian democracy. Honored with the <strong>Bharat Ratna</strong> in 1999.
                    </p>
                </div>
            </div>

            <!-- Dr. Rajendra Prasad -->
            <div class="col-md-6 col-lg-4">
                <div class="personality-card p-4 rounded-4 shadow-sm border h-100">
                    <span class="badge bg-primary text-white fw-bold mb-2">Desh Ratna</span>
                    <h4 class="fw-bold text-dark font-heading mb-1">Dr. Rajendra Prasad</h4>
                    <p class="text-muted small mb-2">Born: Ziradei, Old Saran (1884–1963)</p>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        The <strong>First President of the Republic of India</strong>, President of the Constituent Assembly that drafted the Indian Constitution, and key associate of Mahatma Gandhi in Champaran. Bharat Ratna recipient (1962).
                    </p>
                </div>
            </div>

            <!-- Maulana Mazharul Haque -->
            <div class="col-md-6 col-lg-4">
                <div class="personality-card p-4 rounded-4 shadow-sm border h-100">
                    <span class="badge bg-success text-white fw-bold mb-2">Deshbhakta</span>
                    <h4 class="fw-bold text-dark font-heading mb-1">Maulana Mazharul Haque</h4>
                    <p class="text-muted small mb-2">Faridpur / Ashiyana (1866–1930)</p>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        Champion of Hindu-Muslim unity, founder of Bihar Vidyapeeth and Sadaqat Ashram, Patna. Built his famous hermitage *Ashiyana* in Saran division where Mahatma Gandhi frequently stayed during the freedom movement.
                    </p>
                </div>
            </div>

            <!-- Babu Brajkishore Prasad & Prabhavati Devi -->
            <div class="col-md-6 col-lg-4">
                <div class="personality-card p-4 rounded-4 shadow-sm border h-100">
                    <span class="badge bg-info text-dark fw-bold mb-2">Satyagraha Leader</span>
                    <h4 class="fw-bold text-dark font-heading mb-1">Babu Brajkishore Prasad</h4>
                    <p class="text-muted small mb-2">Saran / Champaran Movement</p>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        Prominent nationalist lawyer, Gandhi's right hand during the 1917 Champaran Indigo Satyagraha, and father of freedom fighter <strong>Prabhavati Devi</strong> (wife of Loknayak JP).
                    </p>
                </div>
            </div>

            <!-- Mahendra Misir -->
            <div class="col-md-6 col-lg-4">
                <div class="personality-card p-4 rounded-4 shadow-sm border h-100">
                    <span class="badge bg-danger text-white fw-bold mb-2">Purvi Samrat & Rebel</span>
                    <h4 class="fw-bold text-dark font-heading mb-1">Mahendra Misir</h4>
                    <p class="text-muted small mb-2">Mishrawalia, Jalalpur (1886–1946)</p>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        Immortal maestro of Bhojpuri <em>Purvi</em> music who operated an underground counterfeit currency network to fund India's armed freedom revolutionaries against the British crown.
                    </p>
                </div>
            </div>

            <!-- Rahul Sankrityayan & Peasant Movement -->
            <div class="col-md-6 col-lg-4">
                <div class="personality-card p-4 rounded-4 shadow-sm border h-100">
                    <span class="badge bg-secondary text-white fw-bold mb-2">Kisan Andolan</span>
                    <h4 class="fw-bold text-dark font-heading mb-1">Rahul Sankrityayan</h4>
                    <p class="text-muted small mb-2">Amwari Satyagraha, Saran (1939)</p>
                    <p class="text-secondary small mb-0" style="line-height: 1.6;">
                        Mahapandit Rahul Sankrityayan led the historic peasant agitation (Bakhasht land movement) in Amwari village, Saran, facing brutal British police lathi charges alongside local peasants.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 5: Cultural Legacy & Bhojpuri Heritage -->
    <div id="cultural-icons" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-primary text-white fw-bold px-3 py-1.5 rounded-pill small">Folk Theater & Art</span>
            <span class="text-muted small fw-semibold">Soul of Bhojpuri Literature</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            Bhikhari Thakur: The Shakespeare of Bhojpuri
        </h2>
        
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <p class="text-secondary" style="line-height: 1.8;">
                    Born in Kutubpur village of Saran district, <strong>Bhikhari Thakur (18 Dec 1887 – 10 July 1971)</strong> is universally celebrated as the greatest dramatist, poet, actor, folk singer, and social reformer in the history of Bhojpuri language.
                </p>
                <p class="text-secondary" style="line-height: 1.8;">
                    His legendary folk plays — most notably <em>Bidesiya (बिदेसिया)</em>, <em>Gabarghichor</em>, <em>Beti Bechwa</em>, <em>Radheshyam Bahar</em>, and <em>Bhai Birodh</em> — exposed the deep social pain of migration, poverty, female oppression, child marriage, and caste taboos while captivating millions across eastern India and the indentured diaspora in Mauritius, Fiji, Trinidad, and Suriname.
                </p>

                <div class="row g-3 mt-2">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <strong class="text-dark small d-block mb-1"><i class="bi bi-film text-danger me-1"></i> Bidesiya (बिदेसिया)</strong>
                            <span class="text-muted small">Iconic folk drama capturing the emotional trauma of village women whose husbands migrate to distant cities for livelihood.</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <strong class="text-dark small d-block mb-1"><i class="bi bi-heart-pulse text-primary me-1"></i> Beti Bechwa & Social Reform</strong>
                            <span class="text-muted small">A bold theatrical campaign against child marriage and mismatched elderly bridegroom practices in rural Bihar.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bg-gradient-primary text-white p-4 rounded-4 shadow-sm text-center">
                    <div class="text-warning fs-1 mb-2"><i class="bi bi-star-fill"></i></div>
                    <h4 class="fw-bold font-heading text-white mb-1">Rai Bahadur of Bhojpuri</h4>
                    <p class="text-white-50 small mb-3">Bhikhari Thakur (1887 – 1971)</p>
                    <div class="border-top border-white border-opacity-25 pt-3 text-start small">
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>Birthplace:</strong> Kutubpur, Saran</div>
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>Folk Genre:</strong> Bidesiya Natya Shaili</div>
                        <div class="mb-0"><i class="bi bi-check-circle-fill text-warning me-2"></i><strong>Legacy:</strong> Global Bhojpuri Icon</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 6: Sacred Heritage Sites of Saran -->
    <div id="sacred-sites" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill small">Pilgrimage & Heritage</span>
            <span class="text-muted small fw-semibold">Sacred Landmarks</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-3">
            Famous Historical & Sacred Sites of Saran
        </h2>
        <p class="text-secondary lead fs-6 mb-4">
            Explore the spiritual and architectural wonders spread across the 20 blocks of Saran district.
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
                            <h5 class="fw-bold text-dark mb-0 font-heading">Aami Ambika Bhawani</h5>
                            <span class="text-muted small">Dighwara Block</span>
                        </div>
                    </div>
                    <p class="text-secondary small flex-grow-1" style="line-height: 1.6;">
                        Ancient and revered Shakti Peeth situated on the banks of Ganga. Associated with King Suratha and Samadhi Vaishya from the *Devi Mahatmya* / *Durga Saptashati*. Famous for its sacred Yagya Kunda.
                    </p>
                    <div class="pt-2 border-top">
                        <span class="badge bg-light text-dark border small"><i class="bi bi-calendar3 me-1"></i> Navratri Mahotsav</span>
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
                            <h5 class="fw-bold text-dark mb-0 font-heading">Hariharnath Temple</h5>
                            <span class="text-muted small">Sonepur (Sonpur)</span>
                        </div>
                    </div>
                    <p class="text-secondary small flex-grow-1" style="line-height: 1.6;">
                        World-renowned temple symbolizing the unity of Lord Vishnu (Hari) and Lord Shiva (Har) at the sacred confluence of Gandak and Ganga. Epicenter of Asia's largest historical cattle fair (Sonepur Mela).
                    </p>
                    <div class="pt-2 border-top">
                        <span class="badge bg-light text-dark border small"><i class="bi bi-geo-alt me-1"></i> Confluence of Gandak & Ganga</span>
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
                            <h5 class="fw-bold text-dark mb-0 font-heading">Gautam Rishi Ashram</h5>
                            <span class="text-muted small">Godna (Revelganj)</span>
                        </div>
                    </div>
                    <p class="text-secondary small flex-grow-1" style="line-height: 1.6;">
                        The sacred penance abode of Sage Gautama on the bank of Sarayu river. Revered as the site of <em>Ahilya Uddhar</em> during Lord Rama’s journey to Mithila with Sage Vishwamitra.
                    </p>
                    <div class="pt-2 border-top">
                        <span class="badge bg-light text-dark border small"><i class="bi bi-water me-1"></i> Sarayu River Ghat</span>
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
                            <h5 class="fw-bold text-dark mb-0 font-heading">Silhauri (Baba Shilanath)</h5>
                            <span class="text-muted small">Marhowrah Block</span>
                        </div>
                    </div>
                    <p class="text-secondary small flex-grow-1" style="line-height: 1.6;">
                        Ancient Shiva shrine associated with Puranic lore of King Mohini and Narada Muni. The annual Shivaratri Mela here draws lakhs of devotees from across Bihar and Uttar Pradesh.
                    </p>
                    <div class="pt-2 border-top">
                        <span class="badge bg-light text-dark border small"><i class="bi bi-calendar-event me-1"></i> Maha Shivaratri Mela</span>
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
                            <h5 class="fw-bold text-dark mb-0 font-heading">Dhorh Ashram</h5>
                            <span class="text-muted small">Parsa Block</span>
                        </div>
                    </div>
                    <p class="text-secondary small flex-grow-1" style="line-height: 1.6;">
                        Historic hermitage and temple complex situated in Parsa, connected with ancient ascetic traditions, saintly meditation caves, and Vedic rituals.
                    </p>
                    <div class="pt-2 border-top">
                        <span class="badge bg-light text-dark border small"><i class="bi bi-shield-check me-1"></i> Vedic Spiritual Center</span>
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
                            <h5 class="fw-bold text-dark mb-0 font-heading">Semaria Ghat & Doriganj</h5>
                            <span class="text-muted small">Chapra / Doriganj</span>
                        </div>
                    </div>
                    <p class="text-secondary small flex-grow-1" style="line-height: 1.6;">
                        Sacred river confluence point where the mighty Sarayu merges into the Ganga. A revered site for holy snan, solar eclipse rituals, and historical boat transportation.
                    </p>
                    <div class="pt-2 border-top">
                        <span class="badge bg-light text-dark border small"><i class="bi bi-droplet-fill me-1"></i> Holy Snan Ghat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 7: Chronological Timeline of Saran -->
    <div id="timeline" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-dark text-white fw-bold px-3 py-1.5 rounded-pill small">Chronological History</span>
            <span class="text-muted small fw-semibold">Key Milestones</span>
        </div>
        <h2 class="fw-bold font-heading text-dark display-6 mb-4">
            Chronological Timeline of Saran District
        </h2>

        <div class="history-timeline">
            <!-- Timeline 1 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-primary text-white fw-bold mb-1">c. 2500 BCE – 1000 BCE</div>
                <h5 class="fw-bold text-dark font-heading mb-1">Neolithic & Chalcolithic Civilization at Chirand</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    Flourishing of agrarian society, bone-tool craft, circular mud houses, and riverine trade at Chirand on the northern bank of Ganga.
                </p>
            </div>

            <!-- Timeline 2 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-success text-white fw-bold mb-1">6th – 3rd Century BCE</div>
                <h5 class="fw-bold text-dark font-heading mb-1">Epic & Mahajanapada Era</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    Gautam Rishi establishes his Ashram at Godna (Revelganj). Emperor Chandragupta Maurya and later Mauryan rulers patronize trade and horse fairs at Harihar Kshetra (Sonpur).
                </p>
            </div>

            <!-- Timeline 3 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-warning text-dark fw-bold mb-1">1582 CE</div>
                <h5 class="fw-bold text-dark font-heading mb-1">Creation of Sarkar Saran under Emperor Akbar</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    Abul Fazl records <em>Sarkar Saran</em> in the Ain-i-Akbari with 17 mahals, constituting one of Bihar Subah’s highest agricultural revenue regions.
                </p>
            </div>

            <!-- Timeline 4 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-info text-dark fw-bold mb-1">17th – 18th Century CE</div>
                <h5 class="fw-bold text-dark font-heading mb-1">Dutch, English & French Saltpetre Factories at Chapra</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    Chapra emerges as a global river-port hub for high-grade Saltpetre (*Shora* for gunpowder), opium, and textiles. European factories established along the riverfront.
                </p>
            </div>

            <!-- Timeline 5 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-secondary text-white fw-bold mb-1">1766 CE</div>
                <h5 class="fw-bold text-dark font-heading mb-1">British East India Company Constitutes Saran District</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    Following the 1764 Battle of Buxar, Saran is formalized as a British administrative district. In 1866, Champaran is separated from Saran.
                </p>
            </div>

            <!-- Timeline 6 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-danger text-white fw-bold mb-1">1917 – 1942 CE</div>
                <h5 class="fw-bold text-dark font-heading mb-1">Champaran Satyagraha, Kisan Andolan & Quit India Revolution</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    Brajkishore Prasad and Dr. Rajendra Prasad lead Champaran movement with Gandhi. In 1942, Saran witnesses massive public uprisings, Marhowrah railway sabotages, and JP’s historic prison break.
                </p>
            </div>

            <!-- Timeline 7 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-primary text-white fw-bold mb-1">1972 & 1981 CE</div>
                <h5 class="fw-bold text-dark font-heading mb-1">Bifurcation and Formation of Saran Division</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    Siwan and Gopalganj are established as separate districts in 1972. In 1981, Saran Division (Commissionery) is inaugurated with Chapra as administrative headquarters.
                </p>
            </div>

            <!-- Timeline 8 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-success text-white fw-bold mb-1">1990 CE</div>
                <h5 class="fw-bold text-dark font-heading mb-1">Establishment of Jai Prakash University (JPU)</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    JPU Chapra is established on 22 November 1990 to provide higher university education across the entire Saran division.
                </p>
            </div>

            <!-- Timeline 9 -->
            <div class="history-timeline-item">
                <div class="history-timeline-dot"></div>
                <div class="d-inline-block badge bg-warning text-dark fw-bold mb-1">26 July 2026</div>
                <h5 class="fw-bold text-dark font-heading mb-1">Launch of Saran Index (Digital Directory)</h5>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    Coinciding with the 9th Incorporation Day of OfferPlant Technologies Pvt. Ltd., Saran Index is launched to digitally connect all 20 blocks, panchayats, and citizens of Saran.
                </p>
            </div>
        </div>
    </div>

    <!-- Section 8: Official Sources & Historical References -->
    <div id="sources" class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white border-top border-4 border-warning">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill small mb-1">Authenticity & Citations</span>
                <h3 class="fw-bold font-heading text-dark display-6 mb-0">Official Sources & Historical References</h3>
            </div>
            <a href="sources" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-bold">
                <i class="bi bi-shield-check me-1"></i> View All Reference Portals <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <p class="text-secondary small mb-4" style="line-height: 1.7;">
            The historical chronology, archaeological discoveries, and administrative evolution presented above are compiled from authenticated government publications, archaeological excavation reports, and academic historical archives:
        </p>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-bank2 text-warning fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">Archaeological Survey of India (ASI)</strong>
                            <span class="text-muted small">Excavation reports on Chirand Neolithic & Chalcolithic mounds, antler bone tools, and stratigraphy. (<a href="https://asi.nic.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">asi.nic.in</a>)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-book-half text-primary fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">Bihar District Gazetteers: Saran (1960)</strong>
                            <span class="text-muted small">Edited by P.C. Roy Choudhury, Revenue Department, Government of Bihar – authoritative records on origin, geography, revenue, and freedom struggle.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-journal-bookmark-fill text-success fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">Ain-i-Akbari (1582 CE) by Abul Fazl</strong>
                            <span class="text-muted small">Mughal imperial gazetteer detailing <em>Sarkar Saran</em>, its 17 mahals, revenue yield, and administrative division under Subah Bihar.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light border h-100">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-archive-fill text-danger fs-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="text-dark small d-block">National Archives of India & Saran District Portal</strong>
                            <span class="text-muted small">Official freedom movement records of 1942 Quit India, Champaran Satyagraha, and administrative milestones. (<a href="https://saran.nic.in" target="_blank" rel="noopener" class="text-primary text-decoration-none">saran.nic.in</a>)</span>
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
                <i class="bi bi-compass me-1"></i> Explore Modern Saran Digitally
            </span>
            <h3 class="fw-bold font-heading text-white display-6 mb-3">
                Connect With Saran’s 20 Blocks & 300+ Panchayats
            </h3>
            <p class="text-white-50 lead fs-6 mx-auto mb-4" style="max-width: 680px;">
                Discover local businesses, doctors, advocates, schools, administrative offices, and verified public services across Saran district on Saran Index.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="blocks" class="btn btn-warning text-dark fw-bold px-4 py-2.5 rounded-pill shadow">
                    <i class="bi bi-geo-alt-fill me-1"></i> Explore 20 Blocks
                </a>
                <a href="university" class="btn btn-outline-light fw-bold px-4 py-2.5 rounded-pill">
                    <i class="bi bi-bank2 me-1"></i> Higher Education & JPU
                </a>
                <a href="add-contact" class="btn btn-light text-primary fw-bold px-4 py-2.5 rounded-pill shadow">
                    <i class="bi bi-plus-circle-fill me-1"></i> List Your Business Free
                </a>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

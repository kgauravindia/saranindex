<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "आधिकारिक संदर्भ डेटा स्रोत एवं पोर्टल – सारण इंडेक्स";
$meta_description = "सारण इंडेक्स द्वारा स्थानीय प्रशासनिक, शैक्षणिक, राजस्व एवं व्यावसायिक जानकारी हेतु संदर्भित आधिकारिक सरकारी पोर्टलों एवं खुली निर्देशिकाओं की सूची।";

$dataSources = getDataSources('ACTIVE');

require_once __DIR__ . '/includes/header.php';
?>

<!-- Premium Hero Header -->
<div class="position-relative overflow-hidden text-white py-5 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25" style="background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.2) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(59,130,246,0.3) 0%, transparent 50%); pointer-events: none;"></div>
    
    <div class="container position-relative z-1 py-4 text-center">
        <div class="d-inline-flex align-items-center bg-white bg-opacity-10 text-white fw-semibold px-3.5 py-1.5 rounded-pill mb-3 border border-white border-opacity-20 shadow-sm small backdrop-blur">
            <i class="bi bi-shield-check text-warning me-2 fs-6"></i> डेटा अखंडता एवं खुला संदर्भ मानक
        </div>
        
        <h1 class="fw-bolder font-heading text-white display-5 mb-3 text-wrap">
            आधिकारिक संदर्भ डेटा स्रोत
        </h1>
        
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 760px; line-height: 1.7;">
            <strong>सारण इंडेक्स</strong> नागरिक सुविधा और डिजिटल कनेक्टिविटी के लिए आधिकारिक सरकारी पोर्टलों, खुली रजिस्ट्रियों और सत्यापित सार्वजनिक डेटाबेस से डेटा संकलित करता है।
        </p>

        <!-- Stats Counter Strip -->
        <div class="row justify-content-center g-3 mt-2">
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h3 fw-bold text-white mb-0"><?php echo count($dataSources); ?>+</div>
                    <div class="text-white-50 fs-7 fw-medium">संदर्भ पोर्टल</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h3 fw-bold text-warning mb-0">100%</div>
                    <div class="text-white-50 fs-7 fw-medium">सार्वजनिक रजिस्ट्रियां</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h3 fw-bold text-info mb-0">20</div>
                    <div class="text-white-50 fs-7 fw-medium">प्रखंड आच्छादित</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">

    <!-- Introductory Philosophy Callout -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5 bg-white border-start border-5 border-primary">
        <div class="d-flex align-items-start">
            <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3 flex-shrink-0">
                <i class="bi bi-diagram-3-fill fs-3"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark font-heading mb-1">हमारी डेटा संकलन नीति</h5>
                <p class="text-secondary mb-0" style="line-height: 1.7;">
                    सारण जिले के सभी 20 प्रखंडों में डेटा अखंडता, मानकीकृत कोड और सटीक प्रशासनिक विवरण सुनिश्चित करने के लिए हमारी निर्देशिका खुले सार्वजनिक डेटा और आधिकारिक सरकारी पोर्टलों का संदर्भ लेती है। हमारे प्लेटफ़ॉर्म द्वारा उपयोग किए जाने वाले मुख्य संदर्भ स्रोतों की सूची नीचे दी गई है।
                </p>
            </div>
        </div>
    </div>

    <!-- Interactive Search & Filter Bar -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                    <input type="text" id="sourceSearchInput" class="form-control bg-light border-0 py-2.5 shadow-none" placeholder="नाम, श्रेणी या डोमेन द्वारा डेटा स्रोत खोजें...">
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex flex-wrap gap-1.5 justify-content-md-end">
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1.5 filter-btn active" data-filter="all">सभी स्रोत</button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 filter-btn" data-filter="इतिहास">इतिहास व पुरातत्व</button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 filter-btn" data-filter="नदियां">नदियां व जल संसाधन</button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 filter-btn" data-filter="शिक्षा">शिक्षा</button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 filter-btn" data-filter="व्यापार">व्यापार</button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 filter-btn" data-filter="प्रशासन">प्रशासन</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Sources Grid -->
    <div class="row g-4 mb-5" id="sourcesGrid">
        <?php foreach ($dataSources as $src): 
            $title = !empty($src['title_hindi']) ? $src['title_hindi'] : $src['title'];
            $subtitle = !empty($src['subtitle_hindi']) ? $src['subtitle_hindi'] : $src['subtitle'];
            $description = !empty($src['description_hindi']) ? $src['description_hindi'] : $src['description'];
            $badge_text = !empty($src['badge_text_hindi']) ? $src['badge_text_hindi'] : $src['badge_text'];
            $authority_badge = !empty($src['authority_badge_hindi']) ? $src['authority_badge_hindi'] : $src['authority_badge'];
        ?>
            <div class="col-lg-6 source-card-wrapper" data-category="<?php echo sanitizeInput($badge_text); ?>" data-text="<?php echo strtolower(sanitizeInput($title . ' ' . $subtitle . ' ' . $src['domain'] . ' ' . $badge_text)); ?>">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white hover-lift transition-all position-relative overflow-hidden border-top border-4 border-primary">
                    <div class="card-body p-4 d-flex flex-column">
                        
                        <!-- Header Badges -->
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <span class="badge <?php echo sanitizeInput($src['badge_color_class'] ?? 'bg-primary-subtle text-primary'); ?> fw-bold px-3 py-1.5 rounded-pill small d-inline-flex align-items-center">
                                <i class="bi <?php echo sanitizeInput($src['badge_icon'] ?? 'bi-link-45deg'); ?> me-1.5"></i> <?php echo sanitizeInput($badge_text); ?>
                            </span>
                            
                            <div class="d-flex align-items-center gap-1.5">
                                <?php if (!empty($authority_badge)): ?>
                                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small"><?php echo sanitizeInput($authority_badge); ?></span>
                                <?php endif; ?>
                                <span class="badge bg-secondary-subtle text-dark border rounded-pill px-2.5 py-1 small fw-semibold">आईडी #<?php echo $src['id']; ?></span>
                            </div>
                        </div>

                        <!-- Source Title & Subtitle -->
                        <h4 class="fw-bold text-dark font-heading mb-1 fs-5">
                            <?php echo sanitizeInput($title); ?>
                        </h4>
                        
                        <?php if (!empty($subtitle)): ?>
                            <div class="text-muted small mb-3 fw-medium">
                                <i class="bi bi-building-check me-1 text-primary"></i><?php echo sanitizeInput($subtitle); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Description -->
                        <p class="text-secondary small mb-4 flex-grow-1" style="line-height: 1.65;">
                            <?php echo sanitizeInput($description); ?>
                        </p>

                        <!-- Footer Links -->
                        <div class="pt-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2 mt-auto">
                            <span class="text-muted small fw-semibold">
                                <i class="bi bi-globe me-1 text-primary"></i><?php echo sanitizeInput($src['domain']); ?>
                            </span>
                            <a href="<?php echo sanitizeInput($src['url']); ?>" target="_blank" rel="noopener" class="btn btn-primary btn-sm rounded-pill px-3.5 py-1.5 fw-bold shadow-xs">
                                पोर्टल पर जाएं <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Non-Government Disclaimer Footer Box -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-light border text-dark">
        <div class="d-flex align-items-center mb-2">
            <div class="bg-warning-subtle text-warning-emphasis p-2 rounded-circle me-2.5 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="bi bi-shield-exclamation fs-5"></i>
            </div>
            <h5 class="fw-bold mb-0 font-heading">डेटा अस्वीकरण (Disclaimer)</h5>
        </div>
        <p class="small text-secondary mb-0 ps-md-5" style="line-height: 1.65;">
            <strong>सारण इंडेक्स</strong> (<code>saranindex.com</code>) <strong>ऑफ़रप्लांट टेक्नोलॉजीज प्रा. लि.</strong> द्वारा निर्मित एक स्वतंत्र डिजिटल निर्देशिका प्लेटफ़ॉर्म है। सरकारी पोर्टलों और सार्वजनिक रजिस्ट्रियों के संदर्भ केवल एट्रिब्यूशन, डेटा पारदर्शिता और सार्वजनिक संदर्भ के लिए प्रदान किए गए हैं। सारण इंडेक्स किसी भी सरकारी एजेंसी, विभाग या प्राधिकरण से संबद्ध, समर्थित या प्रतिनिधित्व नहीं करता है।
        </p>
    </div>

</div>

<!-- Client-side Interactive Search & Filter Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('sourceSearchInput');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.source-card-wrapper');

    function filterCards() {
        const query = searchInput.value.toLowerCase().trim();
        const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter').toLowerCase();

        cards.forEach(card => {
            const text = card.getAttribute('data-text');
            const cat = card.getAttribute('data-category').toLowerCase();
            
            const matchesQuery = !query || text.includes(query);
            const matchesCategory = (activeFilter === 'all') || cat.includes(activeFilter);

            if (matchesQuery && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterCards);
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('btn-primary', 'active'));
            filterBtns.forEach(b => b.classList.add('btn-light', 'border'));
            
            this.classList.remove('btn-light', 'border');
            this.classList.add('btn-primary', 'active');
            
            filterCards();
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

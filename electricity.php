<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "Saran Electricity Department & NBPDCL Directory | Chapra Bijli Office, Officers & 24x7 Helplines";
$meta_description = "Complete directory of NBPDCL electricity supply circle, divisions (Chapra East & West), sub-divisions (ESSD), and section offices (JEE) across all 20 blocks in Saran District. 24x7 emergency helpline 1912 and online bill pay.";
$meta_keywords = "Saran electricity office, NBPDCL Chapra, Bijli office Chapra, electricity complaint 1912, JEE electricity saran, Sonepur bijli office, Marhaura electricity, Dighwara bijli, NBPDCL bill payment, Suvidha App Bihar, Saran power distribution";

// Fetch official NBPDCL listings from DB
$db = getDB();
$nbpdclListings = [];
$blockCounts = [];

if ($db) {
    try {
        $stmt = $db->query("
            SELECT l.id, l.title, l.hindi_title, l.slug, l.contact_person, l.mobile, l.mobile_visibility,
                   l.address, l.pincode, l.business_hours, l.services, l.description, l.source,
                   l.block_id, b.name as block_name, b.hindi_name as block_hindi, b.slug as block_slug
            FROM listings l
            LEFT JOIN blocks b ON l.block_id = b.id
            WHERE l.subcategory_id = 272
            ORDER BY l.id ASC
        ");
        $nbpdclListings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($nbpdclListings as $item) {
            $bName = $item['block_name'] ?? 'Saran District';
            $blockCounts[$bName] = ($blockCounts[$bName] ?? 0) + 1;
        }
    } catch (PDOException $e) {
        error_log("Error fetching NBPDCL listings: " . $e->getMessage());
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom Styling for Electricity Portal -->
<style>
.electricity-hero-bg {
    background: linear-gradient(135deg, #091a30 0%, #0f3460 50%, #164e87 100%);
    color: #ffffff;
    position: relative;
    overflow: hidden;
}
.electricity-hero-bg::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(234, 179, 8, 0.15) 0%, rgba(2, 132, 199, 0.08) 50%, transparent 70%);
    pointer-events: none;
}
.office-card {
    transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    background: #ffffff;
}
.office-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 30px -10px rgba(15, 52, 96, 0.15) !important;
    border-color: #38bdf8;
}
.quick-pill {
    background: #ffffff;
    border: 1px solid #cbd5e1;
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
    cursor: pointer;
}
.quick-pill:hover, .quick-pill.active {
    background: #0f3460;
    color: #ffffff !important;
    border-color: #0f3460;
    box-shadow: 0 4px 12px rgba(15, 52, 96, 0.25);
}
.badge-circle { background: #fee2e2; color: #991b1b; }
.badge-division { background: #dbeafe; color: #1e40af; }
.badge-subdiv { background: #fef3c7; color: #92400e; }
.badge-section { background: #dcfce7; color: #166534; }
.badge-tech { background: #f3e8ff; color: #6b21a8; }
.guide-step-number {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #0f3460;
    color: #ffffff;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
</style>

<!-- Hero Section -->
<div class="electricity-hero-bg py-5 position-relative">
    <div class="container position-relative z-1 py-3 text-center">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="./" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill"></i> Home</a></li>
                <li class="breadcrumb-item text-white-50">Government Services</li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">Electricity (NBPDCL)</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill fs-7 shadow-sm">
                <i class="bi bi-lightning-charge-fill me-1"></i> North Bihar Power Distribution Co. Ltd (NBPDCL)
            </span>
            <span class="badge px-3 py-1.5 rounded-pill fs-7 text-white" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3);">
                <i class="bi bi-geo-alt-fill me-1"></i> Saran District (All 20 Blocks)
            </span>
            <span class="badge bg-info-subtle text-info-emphasis px-3 py-1.5 rounded-pill fs-7 fw-semibold">
                <i class="bi bi-shield-check me-1"></i> Official Directory
            </span>
        </div>

        <h1 class="fw-bolder font-heading text-white display-5 mb-2">
            Saran Electricity Department & NBPDCL Directory
        </h1>
        <div class="lead text-warning fw-semibold mb-3">
            विद्युत आपूर्ति अंचल, प्रमंडल (छपरा पूर्व व पश्चिम), अवर प्रमंडल एवं प्रशाखा (कनीय अभियंता) डायरेक्टरी
        </div>
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 820px; font-size: 1rem; line-height: 1.6;">
            Verified directory of Electric Supply Circle, Divisions, Sub-Divisions (ESSD), Section Offices (JEE), 24x7 breakdown helpline <strong>1912</strong>, transformer replacement protocols, and online bill payment for all consumers of Saran District.
        </p>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-center gap-2.5 flex-wrap">
            <a href="tel:1912" class="btn btn-warning text-dark fw-bold px-4 py-2.5 rounded-pill shadow">
                <i class="bi bi-telephone-outbound-fill me-1"></i> 24x7 Helpline: 1912
            </a>
            <a href="https://nbpdcl.co.in/" target="_blank" rel="noopener" class="btn btn-light text-primary fw-bold px-4 py-2.5 rounded-pill shadow">
                <i class="bi bi-credit-card-2-front-fill me-1"></i> Pay Bill Online (nbpdcl.co.in) <i class="bi bi-box-arrow-up-right ms-1"></i>
            </a>
            <a href="https://play.google.com/store/apps/details?id=com.csmart.biharbijli" target="_blank" rel="noopener" class="btn btn-outline-light fw-bold px-4 py-2.5 rounded-pill">
                <i class="bi bi-phone-fill me-1"></i> Bihar Bijli Suvidha App <i class="bi bi-box-arrow-up-right ms-1"></i>
            </a>
            <a href="#directory" class="btn btn-primary fw-bold px-4 py-2.5 rounded-pill">
                <i class="bi bi-building me-1"></i> Browse <?php echo count($nbpdclListings); ?> Offices
            </a>
        </div>
    </div>
</div>

<div class="container py-5">

    <!-- KPI Quick Facts Cards -->
    <div class="row g-3 mb-5">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-warning">
                <div class="text-warning fs-2 mb-1"><i class="bi bi-headset"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">1912</div>
                <div class="text-muted small fw-semibold">Toll-Free Helpline</div>
                <div class="badge bg-warning-subtle text-dark rounded-pill mt-2 px-2.5 py-1 small fw-bold">24x7 Power Breakdown</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-primary">
                <div class="text-primary fs-2 mb-1"><i class="bi bi-diagram-3-fill"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">2 Divisions</div>
                <div class="text-muted small fw-semibold">Chapra East & West</div>
                <div class="badge bg-primary-subtle text-primary rounded-pill mt-2 px-2.5 py-1 small fw-bold">8 Sub-Divisions (ESSD)</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-success">
                <div class="text-success fs-2 mb-1"><i class="bi bi-geo-alt-fill"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">20 Blocks</div>
                <div class="text-muted small fw-semibold">100% District Coverage</div>
                <div class="badge bg-success-subtle text-success rounded-pill mt-2 px-2.5 py-1 small fw-bold">30+ JEE Section Offices</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center h-100 bg-white border-top border-4 border-info">
                <div class="text-info fs-2 mb-1"><i class="bi bi-shield-check"></i></div>
                <div class="display-6 fw-bolder text-dark font-heading mb-0">59 Units</div>
                <div class="text-muted small fw-semibold">Admin & Technical Units</div>
                <div class="badge bg-info-subtle text-info-emphasis rounded-pill mt-2 px-2.5 py-1 small fw-bold">CGRF, MRT, TRW, Store</div>
            </div>
        </div>
    </div>

    <!-- Top Emergency Helpline Banner -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5 text-white" style="background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-white text-danger fw-bold rounded-pill px-2.5 py-1 fs-7">CRITICAL EMERGENCY</span>
                    <span class="text-white-50 small">Live Power Outage • Wire Fallen • Transformer Spark</span>
                </div>
                <h4 class="fw-bold mb-1 text-white">Power Supply Failure or Hazard in Saran?</h4>
                <p class="text-white-50 mb-0 small">
                    Dial <strong>1912</strong> (Toll-Free 24x7) from any mobile or landline across Saran district to register complaints regarding power cuts, low voltage, burnt transformers, or live wires. Complaints are tracked with an automated ticket number.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="tel:1912" class="btn btn-light btn-lg text-danger fw-bolder px-4 py-2.5 rounded-pill shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-telephone-fill fs-5"></i> Call 1912 Now
                </a>
            </div>
        </div>
    </div>

    <!-- Directory Section Anchor -->
    <div id="directory" class="mb-4 pt-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <div>
                <h2 class="fw-bold font-heading text-dark mb-1">
                    <i class="bi bi-building-fill-gear text-primary me-2"></i>NBPDCL Office & Officer Directory
                </h2>
                <p class="text-muted small mb-0">
                    Filter by division, operational hierarchy, or search by officer designation and block name.
                </p>
            </div>

            <!-- Global Search Box -->
            <div class="position-relative" style="min-width: 280px; max-width: 400px;">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" id="officeSearch" class="form-control rounded-pill ps-5 pe-3 py-2 shadow-sm" placeholder="Search office, designation, block...">
            </div>
        </div>

        <!-- Filter Navigation Pills -->
        <div class="d-flex flex-wrap gap-2 mb-4" id="filterPills">
            <button class="quick-pill active" data-filter="all">
                <i class="bi bi-grid-fill"></i> All Offices (<?php echo count($nbpdclListings); ?>)
            </button>
            <button class="quick-pill" data-filter="circle">
                <i class="bi bi-star-fill text-warning"></i> Circle & Forum (5)
            </button>
            <button class="quick-pill" data-filter="tech">
                <i class="bi bi-cpu-fill text-info"></i> Technical Units (11)
            </button>
            <button class="quick-pill" data-filter="east">
                <i class="bi bi-arrow-right-circle text-primary"></i> Chapra East Division (20)
            </button>
            <button class="quick-pill" data-filter="west">
                <i class="bi bi-arrow-left-circle text-success"></i> Chapra West Division (23)
            </button>
            <button class="quick-pill" data-filter="subdiv">
                <i class="bi bi-diagram-2 text-danger"></i> Sub-Divisions AEE (8)
            </button>
            <button class="quick-pill" data-filter="section">
                <i class="bi bi-geo-alt text-dark"></i> JEE Sections (30)
            </button>
        </div>

        <!-- Block Quick Jump Dropdown -->
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-light">
            <div class="row align-items-center g-2">
                <div class="col-md-4">
                    <label class="fw-bold small text-dark mb-0 d-flex align-items-center gap-1.5">
                        <i class="bi bi-funnel-fill text-primary"></i> Filter by Saran Block:
                    </label>
                </div>
                <div class="col-md-8">
                    <select id="blockFilter" class="form-select rounded-pill shadow-sm small">
                        <option value="all">-- All 20 Saran Blocks & Headquarter Offices --</option>
                        <?php foreach ($blockCounts as $bName => $bCount): ?>
                            <option value="<?php echo htmlspecialchars(strtolower($bName)); ?>"><?php echo htmlspecialchars($bName); ?> (<?php echo $bCount; ?> offices)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Offices Grid -->
    <div class="row g-3 mb-5" id="officesGrid">
        <?php 
        $userCanSeeMobile = isUserLoggedIn();
        foreach ($nbpdclListings as $index => $item): 
            $titleLower = strtolower($item['title'] . ' ' . ($item['hindi_title'] ?? '') . ' ' . $item['contact_person'] . ' ' . ($item['block_name'] ?? ''));
            
            // Assign filtering tags
            $tags = [];
            $levelBadge = ['text' => 'Section', 'class' => 'badge-section'];
            
            if (stripos($item['title'], 'Circle') !== false || stripos($item['title'], 'CGRF') !== false || stripos($item['title'], 'STF') !== false || stripos($item['title'], 'IT Cell') !== false) {
                $tags[] = 'circle';
                $levelBadge = ['text' => 'Circle Level', 'class' => 'badge-circle'];
            }
            if (stripos($item['title'], 'MRT') !== false || stripos($item['title'], 'TRW') !== false || stripos($item['title'], 'Store') !== false || stripos($item['title'], 'Civil') !== false || stripos($item['title'], 'Project Sub-Division') !== false) {
                $tags[] = 'tech';
                $levelBadge = ['text' => 'Technical Unit', 'class' => 'badge-tech'];
            }
            if (stripos($item['title'], 'Chapra East') !== false || in_array($item['block_id'], [3, 2, 16, 19, 6, 15, 7, 12, 11, 20, 8])) {
                // East Division or blocks under East jurisdiction
                $tags[] = 'east';
            }
            if (stripos($item['title'], 'Chapra West') !== false || in_array($item['block_id'], [1, 9, 10, 5, 4, 14, 18, 17, 13])) {
                // West Division or blocks under West jurisdiction
                $tags[] = 'west';
            }
            if (stripos($item['title'], 'Sub-Division') !== false) {
                $tags[] = 'subdiv';
                $levelBadge = ['text' => 'Sub-Division (AEE)', 'class' => 'badge-subdiv'];
            } elseif (stripos($item['title'], 'Section') !== false) {
                $tags[] = 'section';
                $levelBadge = ['text' => 'Section (JEE)', 'class' => 'badge-section'];
            } elseif (stripos($item['title'], 'Division') !== false && stripos($item['title'], 'Sub-Division') === false) {
                $levelBadge = ['text' => 'Divisional Office (EEE)', 'class' => 'badge-division'];
            }

            $tagAttr = implode(' ', array_unique($tags));
            $blockSlug = strtolower($item['block_name'] ?? 'chapra');
            $maskedMobile = maskPhoneNumber($item['mobile']);
            $profileUrl = getListingUrl($item['slug']);
        ?>
            <div class="col-md-6 col-lg-4 office-item" data-tags="<?php echo $tagAttr; ?>" data-block="<?php echo $blockSlug; ?>" data-search="<?php echo htmlspecialchars($titleLower); ?>">
                <div class="card office-card h-100 shadow-sm p-3.5 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Header Badges -->
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                            <span class="badge <?php echo $levelBadge['class']; ?> rounded-pill px-2.5 py-1 small fw-bold">
                                <?php echo $levelBadge['text']; ?>
                            </span>
                            <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1 small">
                                <i class="bi bi-geo-alt me-0.5"></i><?php echo htmlspecialchars($item['block_name'] ?? 'Saran'); ?>
                            </span>
                        </div>

                        <!-- Office Title -->
                        <h5 class="fw-bold text-dark mb-1 fs-6 lh-base">
                            <a href="<?php echo $profileUrl; ?>" class="text-dark text-decoration-none hover-primary">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </a>
                        </h5>

                        <?php if (!empty($item['hindi_title'])): ?>
                            <div class="text-primary small fw-semibold mb-2 lh-sm">
                                <?php echo htmlspecialchars($item['hindi_title']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Officer Designation -->
                        <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded-3 bg-light border border-light-subtle">
                            <i class="bi bi-person-badge-fill text-primary fs-5 flex-shrink-0"></i>
                            <div class="small">
                                <span class="text-muted d-block" style="font-size: 0.72rem;">OFFICER DESIGNATION</span>
                                <strong class="text-dark"><?php echo htmlspecialchars($item['contact_person'] ?? 'Electrical Engineer'); ?></strong>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="d-flex align-items-start gap-2 small text-muted mb-3" style="font-size: 0.82rem; line-height: 1.45;">
                            <i class="bi bi-pin-map text-danger flex-shrink-0 mt-0.5"></i>
                            <span><?php echo htmlspecialchars($item['address'] ?? 'Saran District, Bihar'); ?></span>
                        </div>
                    </div>

                    <!-- Bottom Action & Mobile -->
                    <div class="pt-2 border-top">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small text-muted fw-semibold">Official Contact:</span>
                            <?php if ($userCanSeeMobile): ?>
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25 rounded-pill small">Verified</span>
                            <?php else: ?>
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning border-opacity-50 rounded-pill small">
                                    <i class="bi bi-lock-fill me-0.5"></i>Masked
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if ($userCanSeeMobile): ?>
                            <a href="tel:<?php echo htmlspecialchars($item['mobile']); ?>" class="btn btn-success btn-sm w-100 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-1.5 mb-2">
                                <i class="bi bi-telephone-fill"></i> Call <?php echo htmlspecialchars($item['mobile']); ?>
                            </a>
                        <?php else: ?>
                            <a href="login.php?redirect=<?php echo urlencode('electricity.php'); ?>" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-1.5 mb-2" title="Log in to view complete contact number">
                                <i class="bi bi-lock-fill text-warning"></i> Call <?php echo htmlspecialchars($maskedMobile); ?>
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo $profileUrl; ?>" class="btn btn-light btn-sm w-100 rounded-pill text-muted fw-semibold text-center small border">
                            View Full Office Profile <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- No Results Fallback -->
    <div id="noResults" class="text-center py-5 d-none">
        <i class="bi bi-search text-muted display-4 mb-3 d-block"></i>
        <h4 class="fw-bold text-dark">No Matching NBPDCL Offices Found</h4>
        <p class="text-muted small">Please adjust your search terms or block filter.</p>
        <button class="btn btn-primary rounded-pill px-4 py-2 small fw-bold" onclick="resetFilters()">Reset All Filters</button>
    </div>

    <!-- Consumer Help & Essential Services Section -->
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white border-start border-5 border-warning">
        <div class="row g-4">
            <div class="col-lg-6">
                <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-2">
                    <i class="bi bi-info-circle-fill me-1"></i> Consumer Guide
                </span>
                <h3 class="fw-bold font-heading text-dark mb-3">
                    Consumer Rights & Online Services Guide
                </h3>
                <p class="text-muted small mb-4" style="line-height: 1.7;">
                    North Bihar Power Distribution Company Limited (NBPDCL) provides streamlined digital services for the residents of Saran district under the <em>Har Ghar Bijli</em> initiative. Learn how to pay bills, register complaints, and request transformer replacements.
                </p>

                <!-- Steps -->
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex gap-3">
                        <div class="guide-step-number">1</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0.5">24x7 Power Breakdown Reporting</h6>
                            <p class="text-muted small mb-0">Call toll-free <strong>1912</strong> or use the <strong>Bihar Bijli Suvidha App</strong>. You will receive an automated SMS complaint number.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="guide-step-number">2</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0.5">Online Bill Payment & Smart Recharges</h6>
                            <p class="text-muted small mb-0">Visit <strong>nbpdcl.co.in</strong> with your 10-digit CA Consumer Number or recharge prepaid smart meters via PhonePe, Google Pay, or Paytm.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="guide-step-number">3</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0.5">Burnt Transformer Replacement SLA</h6>
                            <p class="text-muted small mb-0">Under Bihar state electricity regulations, burnt transformers must be replaced within <strong>24 hours</strong> in urban areas and <strong>72 hours</strong> in rural areas.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="guide-step-number">4</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0.5">Consumer Forum (CGRF Chapra)</h6>
                            <p class="text-muted small mb-0">For unresolved disputes regarding erroneous billing, meter tampering allegations, or tariff errors, submit an appeal before CGRF at the Chapra Power House campus.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-light h-100 border">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-link-45deg text-primary me-1"></i>Official NBPDCL Portals & Apps
                    </h5>
                    
                    <ul class="list-unstyled mb-4 d-flex flex-column gap-2.5 small">
                        <li class="p-2.5 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="d-block text-dark">NBPDCL Official Web Portal</strong>
                                <span class="text-muted">nbpdcl.co.in (Bill View, Receipt, Ledger)</span>
                            </div>
                            <a href="https://nbpdcl.co.in/" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">Open <i class="bi bi-box-arrow-up-right ms-0.5"></i></a>
                        </li>

                        <li class="p-2.5 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="d-block text-dark">Bihar Bijli Suvidha Mobile App</strong>
                                <span class="text-muted">New Connections, Meter Reading, Outages</span>
                            </div>
                            <a href="https://play.google.com/store/apps/details?id=com.csmart.biharbijli" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">Install <i class="bi bi-download ms-0.5"></i></a>
                        </li>

                        <li class="p-2.5 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="d-block text-dark">Har Ghar Bijli Portal</strong>
                                <span class="text-muted">Government of Bihar Rural Electrification</span>
                            </div>
                            <a href="https://hargharbijli.bihar.gov.in/" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">Visit <i class="bi bi-box-arrow-up-right ms-0.5"></i></a>
                        </li>

                        <li class="p-2.5 bg-white rounded-3 border d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="d-block text-dark">BSPHCL Holding Company</strong>
                                <span class="text-muted">Bihar State Power Holding Company Ltd</span>
                            </div>
                            <a href="https://bsphcl.bih.nic.in/" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">Visit <i class="bi bi-box-arrow-up-right ms-0.5"></i></a>
                        </li>
                    </ul>

                    <div class="p-3 bg-white rounded-3 border border-warning-subtle">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-shield-check text-warning fs-5"></i>
                            <strong class="text-dark small">Data Reference & Authority</strong>
                        </div>
                        <p class="text-muted small mb-0" style="font-size: 0.78rem;">
                            Referenced from official circulars and contact registries of <strong>North Bihar Power Distribution Company Limited (NBPDCL)</strong>. Registered in Saran Index as Reference Source #23.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Interactive Filter & Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('officeSearch');
    const filterPills = document.querySelectorAll('#filterPills .quick-pill');
    const blockFilter = document.getElementById('blockFilter');
    const items = document.querySelectorAll('.office-item');
    const noResults = document.getElementById('noResults');

    let currentTag = 'all';
    let currentBlock = 'all';
    let searchQuery = '';

    function applyFilter() {
        let visibleCount = 0;

        items.forEach(function(item) {
            const tags = item.getAttribute('data-tags') || '';
            const block = item.getAttribute('data-block') || '';
            const text = item.getAttribute('data-search') || '';

            let matchTag = (currentTag === 'all') || tags.includes(currentTag);
            let matchBlock = (currentBlock === 'all') || (block === currentBlock);
            let matchSearch = (searchQuery === '') || text.includes(searchQuery);

            if (matchTag && matchBlock && matchSearch) {
                item.classList.remove('d-none');
                visibleCount++;
            } else {
                item.classList.add('d-none');
            }
        });

        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }

    // Tag pills click
    filterPills.forEach(function(pill) {
        pill.addEventListener('click', function() {
            filterPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            currentTag = this.getAttribute('data-filter');
            applyFilter();
        });
    });

    // Block dropdown change
    blockFilter.addEventListener('change', function() {
        currentBlock = this.value;
        applyFilter();
    });

    // Search input
    searchInput.addEventListener('input', function() {
        searchQuery = this.value.toLowerCase().trim();
        applyFilter();
    });

    window.resetFilters = function() {
        currentTag = 'all';
        currentBlock = 'all';
        searchQuery = '';
        searchInput.value = '';
        blockFilter.value = 'all';
        filterPills.forEach(p => p.classList.remove('active'));
        if (filterPills[0]) filterPills[0].classList.add('active');
        applyFilter();
    };
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


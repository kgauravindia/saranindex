<?php
require_once __DIR__ . '/includes/functions.php';

$page_title = "Who's Who in Saran District Administration – Official Officers Directory | Saran Index";
$meta_description = "Official directory of Saran District Administration officers including District Magistrate (DM) Shri Vaibhava Srivastava IAS, Senior SP Shri Roshan Kumar IPS, DDC, SDO, BDO, CO, Police, and Department Heads.";

$langSwitchUrl = 'hindi/whos-who';
$whosWhoDepts = require __DIR__ . '/includes/whos_who_data.php';

// Calculate totals
$totalDepts = count($whosWhoDepts);
$totalOfficers = 0;
foreach ($whosWhoDepts as $d) {
    $totalOfficers += count($d['officers']);
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header Section -->
<div class="position-relative overflow-hidden text-white py-5 shadow-sm" style="background: linear-gradient(135deg, #091e3a 0%, #103766 50%, #1e4976 100%);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-20" style="background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.2) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(14,165,233,0.3) 0%, transparent 50%); pointer-events: none;"></div>
    
    <div class="container position-relative z-1 py-4 text-center">
        <div class="d-inline-flex align-items-center bg-white bg-opacity-10 text-white fw-semibold px-3.5 py-1.5 rounded-pill mb-3 border border-white border-opacity-20 shadow-sm small backdrop-blur">
            <i class="bi bi-patch-check-fill text-warning me-2 fs-6"></i> Official District Administration Directory • Saran (Chapra)
        </div>
        
        <h1 class="fw-bolder font-heading text-white display-5 mb-3 text-wrap">
            Who's Who in Saran District
        </h1>
        
        <p class="text-white-50 lead mx-auto mb-4" style="max-width: 820px; line-height: 1.7;">
            Comprehensive contact registry of executive officers, police leadership, sub-divisional magistrates, block development officers, circle officers, and civic department heads sourced from the official District Administration portal (<a href="https://saran.nic.in/about-district/whos-who/" target="_blank" rel="noopener noreferrer" class="text-warning text-decoration-none fw-semibold">saran.nic.in</a>).
        </p>

        <!-- Stats Counter Strip -->
        <div class="row justify-content-center g-3 mt-2">
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h3 fw-bold text-white mb-0"><?php echo number_format($totalOfficers); ?>+</div>
                    <div class="text-white-50 fs-7 fw-medium">Officers Listed</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h3 fw-bold text-warning mb-0"><?php echo $totalDepts; ?></div>
                    <div class="text-white-50 fs-7 fw-medium">Core Departments</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h3 fw-bold text-info mb-0">100%</div>
                    <div class="text-white-50 fs-7 fw-medium">Official & Verified</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 backdrop-blur">
                    <div class="h3 fw-bold text-success mb-0">24x7</div>
                    <div class="text-white-50 fs-7 fw-medium">Emergency Control</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">

    <!-- Apex District Leadership Spotlight -->
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill uppercase tracking-wider small">
                    <i class="bi bi-star-fill me-1 text-warning"></i>Apex Governance
                </span>
                <h2 class="fw-bold font-heading text-dark mt-2 mb-0 fs-3">District Leadership & Apex Authorities</h2>
            </div>
            <a href="https://saran.nic.in/about-district/whos-who/" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm rounded-pill px-3 d-none d-md-inline-flex align-items-center gap-1.5">
                <i class="bi bi-box-arrow-up-right"></i> Official Source
            </a>
        </div>

        <div class="row g-4">
            <!-- 1. District Magistrate -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border-top border-4 border-primary hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-bold rounded-pill px-3 py-1">District Head</span>
                            <span class="badge bg-light text-muted border rounded-pill">Room 240002</span>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1">Shri Vaibhava Srivastava, IAS</h4>
                        <div class="text-primary fw-semibold small mb-2">District Magistrate & Collector, Saran</div>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-geo-alt me-1 text-secondary"></i> Collectorate Campus, Katchahry, Chapra - 841301
                        </p>
                    </div>
                    <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                        <div>
                            <div class="extra-small text-muted text-uppercase fw-bold">Office Phone</div>
                            <span class="fw-bold text-dark font-monospace fs-6">06152-240001</span>
                        </div>
                        <a href="tel:06152240001" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> Call Office
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Senior Superintendent of Police -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border-top border-4 border-danger hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-danger-subtle text-danger fw-bold rounded-pill px-3 py-1">Law & Order Head</span>
                            <span class="badge bg-light text-muted border rounded-pill">Saran Police</span>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1">Shri Roshan Kumar, IPS</h4>
                        <div class="text-danger fw-semibold small mb-2">Senior Superintendent of Police (SSP), Saran</div>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-geo-alt me-1 text-secondary"></i> SP Office, Collectorate Campus, Chapra - 841301
                        </p>
                    </div>
                    <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                        <div>
                            <div class="extra-small text-muted text-uppercase fw-bold">CUG Mobile</div>
                            <span class="fw-bold text-dark font-monospace fs-6">9031827135</span>
                        </div>
                        <a href="tel:9031827135" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> Call SSP
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Deputy Development Commissioner -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border-top border-4 border-success hover-lift transition-all d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-3 py-1">Rural Development</span>
                            <span class="badge bg-light text-muted border rounded-pill">Room 232638</span>
                        </div>
                        <h4 class="fw-bold font-heading text-dark mb-1">Shri Laxman Tiwari, IAS</h4>
                        <div class="text-success fw-semibold small mb-2">Deputy Development Commissioner (DDC), Saran</div>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-geo-alt me-1 text-secondary"></i> DDC Office, Collectorate Campus, Chapra
                        </p>
                    </div>
                    <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                        <div>
                            <div class="extra-small text-muted text-uppercase fw-bold">CUG Mobile</div>
                            <span class="fw-bold text-dark font-monospace fs-6">9031071904</span>
                        </div>
                        <a href="tel:9031071904" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i> Call DDC
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Divisional Commissioner -->
            <div class="col-md-6 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-light border-start border-4 border-info h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="badge bg-info-subtle text-info-emphasis fw-bold rounded-pill px-3 py-1 mb-2">Divisional Commissioner</span>
                            <h5 class="fw-bold text-dark mb-1">Shri Manish Kumar, IAS</h5>
                            <div class="text-muted small mb-2">Divisional Commissioner, Saran Division (Saran, Siwan & Gopalganj)</div>
                            <div class="small text-secondary"><i class="bi bi-geo-alt me-1"></i> Commissionary Office Campus, Chapra</div>
                        </div>
                        <a href="tel:06152232400" class="btn btn-outline-info btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone me-1"></i> 06152-232400
                        </a>
                    </div>
                </div>
            </div>

            <!-- 5. DIG Saran Range -->
            <div class="col-md-6 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-light border-start border-4 border-warning h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="badge bg-warning-subtle text-dark fw-bold rounded-pill px-3 py-1 mb-2">DIG Police Range</span>
                            <h5 class="fw-bold text-dark mb-1">Shri Nilesh Kumar, IPS</h5>
                            <div class="text-muted small mb-2">Deputy Inspector General of Police (DIG), Saran Range</div>
                            <div class="small text-secondary"><i class="bi bi-geo-alt me-1"></i> DIG Office, Commissionery Campus, Dakbungalow Road, Chapra</div>
                        </div>
                        <a href="tel:9013827134" class="btn btn-outline-warning text-dark btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-telephone me-1"></i> 9013827134
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Search & Category Filter Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white sticky-top" style="top: 75px; z-index: 100;">
        <div class="row g-3 align-items-center">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-primary"><i class="bi bi-search"></i></span>
                    <input type="text" id="officerSearchInput" class="form-control bg-light border-start-0 py-2.5" placeholder="Search by name, designation, block, or phone (e.g. SDO, BDO Amnour, Traffic, Civil Surgeon)..." onkeyup="filterOfficers()">
                    <button class="btn btn-outline-secondary" type="button" onclick="clearOfficerSearch()"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex gap-2 flex-wrap justify-content-lg-end" id="deptFilterButtons">
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 active filter-pill" data-target="all" onclick="filterByDept('all', this)">
                        All (<?php echo $totalOfficers; ?>)
                    </button>
                    <?php foreach ($whosWhoDepts as $d): ?>
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 filter-pill" data-target="<?php echo $d['id']; ?>" onclick="filterByDept('<?php echo $d['id']; ?>', this)">
                            <i class="bi <?php echo $d['icon']; ?> me-1"></i><?php echo htmlspecialchars($d['name_en']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Departments & Officers Registry -->
    <div id="departmentsContainer">
        <?php foreach ($whosWhoDepts as $dept): ?>
            <div class="dept-section mb-5" id="dept-<?php echo $dept['id']; ?>" data-dept="<?php echo $dept['id']; ?>">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi <?php echo $dept['icon']; ?> fs-5"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold font-heading text-dark mb-0 fs-4"><?php echo htmlspecialchars($dept['name_en']); ?></h3>
                            <div class="text-muted extra-small"><?php echo htmlspecialchars($dept['desc_en']); ?></div>
                        </div>
                    </div>
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 fs-7 fw-bold">
                        <?php echo count($dept['officers']); ?> Officers
                    </span>
                </div>

                <div class="row g-3">
                    <?php foreach ($dept['officers'] as $officer): 
                        $searchStr = strtolower($officer['name_en'] . ' ' . $officer['name_hi'] . ' ' . $officer['designation_en'] . ' ' . $officer['designation_hi'] . ' ' . $officer['address_en'] . ' ' . $officer['phone'] . ' ' . $officer['email']);
                        $callPhone = preg_replace('/[^0-9]/', '', $officer['phone']);
                    ?>
                        <div class="col-md-6 col-lg-4 officer-card-item" data-search="<?php echo htmlspecialchars($searchStr); ?>">
                            <div class="card h-100 border rounded-4 p-3.5 bg-white shadow-sm hover-shadow transition-all d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-start justify-content-between mb-1.5">
                                        <span class="badge bg-light text-primary border rounded-pill extra-small fw-semibold"><?php echo htmlspecialchars($dept['badge']); ?></span>
                                        <?php if (!empty($officer['room'])): ?>
                                            <span class="badge bg-light text-muted border rounded-pill extra-small">Room: <?php echo htmlspecialchars($officer['room']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="fw-bold font-heading text-dark mb-1 fs-6">
                                        <?php echo htmlspecialchars($officer['name_en']); ?>
                                    </h5>
                                    <?php if (!empty($officer['name_hi']) && $officer['name_hi'] !== $officer['name_en']): ?>
                                        <div class="text-muted extra-small mb-1"><?php echo htmlspecialchars($officer['name_hi']); ?></div>
                                    <?php endif; ?>
                                    <div class="text-primary fw-semibold small mb-2">
                                        <?php echo htmlspecialchars($officer['designation_en']); ?>
                                    </div>
                                    <?php if (!empty($officer['address_en'])): ?>
                                        <p class="text-secondary extra-small mb-2">
                                            <i class="bi bi-geo-alt me-1 text-danger"></i><?php echo htmlspecialchars($officer['address_en']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div class="pt-2 border-top">
                                    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                                        <?php if (!empty($officer['phone'])): ?>
                                            <div>
                                                <span class="extra-small text-muted d-block">Phone / Mobile</span>
                                                <a href="tel:<?php echo $callPhone; ?>" class="fw-bold text-dark font-monospace small text-decoration-none hover-primary">
                                                    <i class="bi bi-telephone-fill text-success me-1"></i><?php echo htmlspecialchars($officer['phone']); ?>
                                                </a>
                                            </div>
                                            <a href="tel:<?php echo $callPhone; ?>" class="btn btn-outline-success btn-sm rounded-pill px-2.5 py-1 extra-small fw-bold" title="Call Officer">
                                                <i class="bi bi-telephone-outbound-fill"></i> Call
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted extra-small fst-italic">Phone via Collectorate</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($officer['email'])): ?>
                                        <div class="mt-1.5 pt-1.5 border-top border-light">
                                            <a href="mailto:<?php echo htmlspecialchars($officer['email']); ?>" class="text-muted extra-small text-truncate d-block text-decoration-none">
                                                <i class="bi bi-envelope-at me-1 text-primary"></i><?php echo htmlspecialchars($officer['email']); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- No Search Results Found Alert -->
    <div id="noResultsMsg" class="d-none text-center py-5">
        <div class="text-muted mb-2"><i class="bi bi-search display-4 text-secondary opacity-50"></i></div>
        <h5 class="fw-bold text-dark">No officers found matching your search.</h5>
        <p class="text-muted small">Try checking for typos or searching by department name (e.g. SDO, Police, BDO, CO, Education).</p>
        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="clearOfficerSearch()">Reset Filters</button>
    </div>

    <!-- Official Authority & Disclaimer Callout -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mt-5 bg-light border-start border-4 border-primary">
        <div class="d-flex align-items-start">
            <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3 flex-shrink-0">
                <i class="bi bi-shield-check fs-3"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-1">Official Reference & Data Integrity</h5>
                <p class="text-muted small mb-2">
                    This directory information is mirrored and organized directly from the official portal of <strong>District Administration Saran, Government of Bihar</strong> (<a href="https://saran.nic.in/about-district/whos-who/" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none fw-semibold">saran.nic.in/about-district/whos-who/</a>). Official telephone numbers, CUG contacts, and designations are maintained for public interest, civic engagement, and grievance redressal under open government guidelines.
                </p>
                <div class="extra-small text-muted">
                    <i class="bi bi-info-circle me-1"></i> In case of emergency or crime reporting, please dial <strong>112 (Bihar Police Emergency)</strong> or visit our <a href="emergency" class="text-danger fw-semibold text-decoration-none">24x7 Emergency Helplines</a> directory.
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Client-side Interactive Filter Script -->
<script>
let currentDept = 'all';

function filterOfficers() {
    const input = document.getElementById('officerSearchInput').value.toLowerCase().trim();
    const items = document.querySelectorAll('.officer-card-item');
    const deptSections = document.querySelectorAll('.dept-section');
    let visibleCount = 0;

    items.forEach(item => {
        const itemSearch = item.getAttribute('data-search') || '';
        const parentDept = item.closest('.dept-section').getAttribute('data-dept');

        const matchesDept = (currentDept === 'all' || parentDept === currentDept);
        const matchesQuery = (input === '' || itemSearch.includes(input));

        if (matchesDept && matchesQuery) {
            item.classList.remove('d-none');
            visibleCount++;
        } else {
            item.classList.add('d-none');
        }
    });

    // Toggle department section headers based on whether any child is visible
    deptSections.forEach(dept => {
        const hasVisible = dept.querySelectorAll('.officer-card-item:not(.d-none)').length > 0;
        if (hasVisible) {
            dept.classList.remove('d-none');
        } else {
            dept.classList.add('d-none');
        }
    });

    const noResults = document.getElementById('noResultsMsg');
    if (visibleCount === 0) {
        noResults.classList.remove('d-none');
    } else {
        noResults.classList.add('d-none');
    }
}

function filterByDept(deptId, btnElem) {
    currentDept = deptId;
    document.querySelectorAll('#deptFilterButtons .filter-pill').forEach(b => {
        b.classList.remove('btn-primary', 'active');
        b.classList.add('btn-outline-secondary');
    });
    btnElem.classList.remove('btn-outline-secondary');
    btnElem.classList.add('btn-primary', 'active');
    filterOfficers();
}

function clearOfficerSearch() {
    document.getElementById('officerSearchInput').value = '';
    currentDept = 'all';
    document.querySelectorAll('#deptFilterButtons .filter-pill').forEach(b => {
        if (b.getAttribute('data-target') === 'all') {
            b.classList.remove('btn-outline-secondary');
            b.classList.add('btn-primary', 'active');
        } else {
            b.classList.remove('btn-primary', 'active');
            b.classList.add('btn-outline-secondary');
        }
    });
    filterOfficers();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/includes/auth.php';
checkAdminAuth();
require_once __DIR__ . '/../includes/functions.php';

$page_title = "Postal PIN Code & Bank IFSC Explorer";
require_once __DIR__ . '/includes/header.php';

$pincode_query = trim($_GET['pin'] ?? '841301');
$ifsc_query    = trim($_GET['ifsc'] ?? '');
$active_tab    = !empty($_GET['tab']) && $_GET['tab'] === 'ifsc' ? 'ifsc' : 'pincode';

$pincode_result = null;
$ifsc_result    = null;
$db_listing_count = 0;
$db_user_count    = 0;

if (!empty($pincode_query)) {
    $pincode_result = lookupPincodeApi($pincode_query);
    if (!empty($pincode_result['success'])) {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT COUNT(*) FROM listings WHERE pincode = ? AND status = 'ACTIVE'");
            $stmt->execute([$pincode_result['pincode']]);
            $db_listing_count = $stmt->fetchColumn();

            $stmt2 = $db->prepare("SELECT COUNT(*) FROM users WHERE pincode = ?");
            $stmt2->execute([$pincode_result['pincode']]);
            $db_user_count = $stmt2->fetchColumn();
        } catch (Exception $e) {}
    }
}

if (!empty($ifsc_query)) {
    $ifsc_result = lookupIfscApi($ifsc_query);
}

// Popular Saran Pincodes
$saran_pins = [
    '841301' => 'Chapra Sadar',
    '841418' => 'Marhaura',
    '841101' => 'Sonepur',
    '841305' => 'Revelganj',
    '841311' => 'Garkha',
    '841219' => 'Parsa',
    '841207' => 'Dighwara',
    '841401' => 'Amanour',
    '841403' => 'Baniapur',
    '841208' => 'Ekma',
    '841424' => 'Taraiya',
    '841417' => 'Mashrakh'
];
?>

<div class="container-fluid p-4">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small text-muted">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">PIN Code & IFSC API</li>
                </ol>
            </nav>
            <h3 class="fw-bold font-heading mb-0 text-dark">
                <i class="bi bi-geo-fill text-primary me-2"></i>Postal PIN Code & Bank IFSC API
            </h3>
            <p class="text-muted small mb-0">Live Postal PIN Code & Bank IFSC Directory API integration via Olaw Data Services</p>
        </div>
        <div class="d-flex gap-2">
            <a href="blocks.php" class="btn btn-outline-secondary btn-sm rounded-3">
                <i class="bi bi-geo-alt me-1"></i>Saran Blocks
            </a>
            <a href="listings.php" class="btn btn-primary btn-sm rounded-3">
                <i class="bi bi-list-stars me-1"></i>All Listings
            </a>
        </div>
    </div>

    <!-- Mode Selector Tabs -->
    <ul class="nav nav-pills mb-4 gap-2 bg-white p-2 rounded-3 border shadow-sm" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold rounded-2 <?php echo $active_tab === 'pincode' ? 'active' : ''; ?>" id="pills-pincode-tab" data-bs-toggle="pill" data-bs-target="#pills-pincode" type="button" role="tab">
                <i class="bi bi-mailbox2 me-1.5"></i> Postal PIN Code Search
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold rounded-2 <?php echo $active_tab === 'ifsc' ? 'active' : ''; ?>" id="pills-ifsc-tab" data-bs-toggle="pill" data-bs-target="#pills-ifsc" type="button" role="tab">
                <i class="bi bi-bank2 me-1.5"></i> Bank IFSC Code Search
            </button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <!-- ========================================== -->
        <!-- TAB 1: PIN CODE SEARCH                     -->
        <!-- ========================================== -->
        <div class="tab-pane fade <?php echo $active_tab === 'pincode' ? 'show active' : ''; ?>" id="pills-pincode" role="tabpanel">
            
            <!-- Quick Saran Pincode Bar -->
            <div class="card border-0 shadow-sm rounded-3 p-3 mb-4 bg-white">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="small fw-bold text-muted text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                        <i class="bi bi-pin-map-fill text-danger me-1"></i> Saran District PIN Codes:
                    </div>
                    <div class="d-flex flex-wrap gap-1.5">
                        <?php foreach ($saran_pins as $pin => $label): ?>
                            <a href="?pin=<?php echo $pin; ?>&tab=pincode" class="btn btn-sm <?php echo $pincode_query === (string)$pin ? 'btn-primary' : 'btn-light border'; ?> rounded-pill px-3 py-1 font-monospace" style="font-size: 0.8rem;">
                                <strong><?php echo $pin; ?></strong> <span class="opacity-75">(<?php echo $label; ?>)</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Search Form Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-white">
                <form method="GET" action="" class="row g-3 align-items-center">
                    <input type="hidden" name="tab" value="pincode">
                    <div class="col-12 col-md-7 col-lg-8">
                        <label for="pinInput" class="form-label small fw-bold text-dark mb-1">Enter 6-Digit Indian PIN Code</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-secondary-subtle text-muted">
                                <i class="bi bi-geo-alt-fill text-primary"></i>
                            </span>
                            <input type="text" class="form-control border-secondary-subtle fw-bold font-monospace fs-5" id="pinInput" name="pin" 
                                   placeholder="e.g. 841301, 841418, 110001" 
                                   value="<?php echo htmlspecialchars($pincode_query); ?>" 
                                   maxlength="6" required autofocus>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-search me-1"></i> Lookup PIN Code
                            </button>
                        </div>
                        <div class="form-text small">Connects directly to Live Postal Directory API.</div>
                    </div>
                    <div class="col-12 col-md-5 col-lg-4 text-md-end">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="small text-muted mb-1">API Status</div>
                            <div class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                <i class="bi bi-check-circle-fill me-1"></i> Olaw Postal API Active
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- PIN Code Results Container -->
            <?php if ($pincode_result): ?>
                <?php if ($pincode_result['success']): ?>
                    
                    <!-- Overview Stat Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                                <div class="text-muted small mb-1"><i class="bi bi-geo-alt me-1 text-primary"></i>PIN Code</div>
                                <div class="h3 fw-bold text-primary font-monospace mb-0"><?php echo htmlspecialchars($pincode_result['pincode']); ?></div>
                                <div class="small text-muted mt-1"><?php echo htmlspecialchars($pincode_result['state']); ?></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                                <div class="text-muted small mb-1"><i class="bi bi-building me-1 text-info"></i>District</div>
                                <div class="h4 fw-bold text-dark mb-0"><?php echo htmlspecialchars($pincode_result['district']); ?></div>
                                <div class="small text-muted mt-1"><?php echo count($pincode_result['sub_districts']); ?> Sub-districts (Tehsil/Block)</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                                <div class="text-muted small mb-1"><i class="bi bi-mailbox me-1 text-warning"></i>Post Offices</div>
                                <div class="h3 fw-bold text-warning mb-0"><?php echo count($pincode_result['offices']); ?></div>
                                <div class="small text-muted mt-1"><?php echo $pincode_result['total_records']; ?> Localities mapped</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                                <div class="text-muted small mb-1"><i class="bi bi-shop me-1 text-success"></i>Saran Index Listings</div>
                                <div class="h3 fw-bold text-success mb-0"><?php echo $db_listing_count; ?></div>
                                <div class="small mt-1">
                                    <a href="listings.php?search=<?php echo urlencode($pincode_result['pincode']); ?>" class="text-decoration-none fw-semibold">
                                        View in Directory <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Office & Locality Mapping Table -->
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white mb-4">
                        <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-table me-2 text-primary"></i>Post Offices & Localities under PIN Code <?php echo htmlspecialchars($pincode_result['pincode']); ?>
                            </h6>
                            <span class="badge bg-primary rounded-pill px-3 py-1.5"><?php echo count($pincode_result['raw_data']); ?> Records</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>Post Office Name</th>
                                        <th>Locality / Village / Mauja</th>
                                        <th>Sub-District / Block</th>
                                        <th>District</th>
                                        <th>State</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pincode_result['raw_data'] as $idx => $row): ?>
                                        <tr>
                                            <td class="ps-4 text-muted small"><?php echo $idx + 1; ?></td>
                                            <td>
                                                <div class="fw-bold text-dark">
                                                    <i class="bi bi-envelope-open me-1 text-primary"></i>
                                                    <?php echo htmlspecialchars($row['office_name'] ?? ''); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border fw-medium px-2.5 py-1">
                                                    <?php echo htmlspecialchars($row['locality_name'] ?? '-'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-secondary">
                                                    <?php echo htmlspecialchars($row['sub_district_name'] ?? '-'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['district_name'] ?? ''); ?></td>
                                            <td><span class="small text-muted"><?php echo htmlspecialchars($row['state_name'] ?? ''); ?></span></td>
                                            <td class="text-end pe-4">
                                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 py-0.5" 
                                                        onclick="copyText('<?php echo htmlspecialchars(addslashes($row['office_name'] . ', ' . $row['locality_name'] . ', ' . $row['sub_district_name'] . ', ' . $row['district_name'] . ' - ' . $row['pincode'])); ?>', this)"
                                                        title="Copy address string">
                                                    <i class="bi bi-clipboard me-1"></i> Copy
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="alert alert-danger rounded-3 shadow-sm border-0 d-flex align-items-center gap-3 p-3">
                        <i class="bi bi-exclamation-triangle-fill fs-3 text-danger"></i>
                        <div>
                            <div class="fw-bold">No Records Found</div>
                            <div class="small"><?php echo htmlspecialchars($pincode_result['message'] ?? 'Could not find details for this PIN code.'); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: BANK IFSC SEARCH                    -->
        <!-- ========================================== -->
        <div class="tab-pane fade <?php echo $active_tab === 'ifsc' ? 'show active' : ''; ?>" id="pills-ifsc" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-white">
                <form method="GET" action="" class="row g-3 align-items-center">
                    <input type="hidden" name="tab" value="ifsc">
                    <div class="col-12 col-md-7 col-lg-8">
                        <label for="ifscInput" class="form-label small fw-bold text-dark mb-1">Enter 11-Character Bank IFSC Code</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-secondary-subtle text-muted">
                                <i class="bi bi-bank2 text-primary"></i>
                            </span>
                            <input type="text" class="form-control border-secondary-subtle fw-bold font-monospace fs-5 text-uppercase" id="ifscInput" name="ifsc" 
                                   placeholder="e.g. SBIN0000001, PUNB0024200" 
                                   value="<?php echo htmlspecialchars($ifsc_query ?: 'SBIN0000001'); ?>" 
                                   maxlength="11" required>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-search me-1"></i> Lookup IFSC
                            </button>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 col-lg-4 text-md-end">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="small text-muted mb-1">API Service</div>
                            <div class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                <i class="bi bi-check-circle-fill me-1"></i> Live Bank IFSC Gateway
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <?php if ($ifsc_result): ?>
                <?php if ($ifsc_result['success'] && !empty($ifsc_result['data'])): ?>
                    <?php $b = $ifsc_result['data']; ?>
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                            <div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 font-monospace fs-6 mb-2">
                                    <?php echo htmlspecialchars($b['ifsc'] ?? ''); ?>
                                </span>
                                <h4 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($b['bank'] ?? ''); ?></h4>
                                <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>Branch: <?php echo htmlspecialchars($b['branch'] ?? ''); ?></div>
                            </div>
                            <span class="badge bg-success px-3 py-2 fs-6 rounded-pill">Active Branch</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="small text-muted fw-bold text-uppercase">Full Branch Address</label>
                                <div class="p-3 bg-light rounded-3 border fw-semibold text-dark">
                                    <?php echo htmlspecialchars($b['address'] ?? ''); ?>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="small text-muted fw-bold text-uppercase">City / District</label>
                                        <div class="p-2.5 bg-light rounded-3 border fw-bold text-dark"><?php echo htmlspecialchars($b['city1'] ?? ($b['city2'] ?? '')); ?></div>
                                    </div>
                                    <div class="col-6">
                                        <label class="small text-muted fw-bold text-uppercase">State</label>
                                        <div class="p-2.5 bg-light rounded-3 border fw-bold text-dark"><?php echo htmlspecialchars($b['state'] ?? ''); ?></div>
                                    </div>
                                    <div class="col-6">
                                        <label class="small text-muted fw-bold text-uppercase">MICR Code</label>
                                        <div class="p-2.5 bg-light rounded-3 border font-monospace"><?php echo htmlspecialchars($b['micr'] ?? 'N/A'); ?></div>
                                    </div>
                                    <div class="col-6">
                                        <label class="small text-muted fw-bold text-uppercase">STD Code / Contact</label>
                                        <div class="p-2.5 bg-light rounded-3 border font-monospace"><?php echo htmlspecialchars($b['stdcode'] ?? ($b['phone'] ?? 'N/A')); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger rounded-3 shadow-sm border-0 d-flex align-items-center gap-3 p-3">
                        <i class="bi bi-exclamation-triangle-fill fs-3 text-danger"></i>
                        <div>
                            <div class="fw-bold">Invalid / Not Found</div>
                            <div class="small"><?php echo htmlspecialchars($ifsc_result['message'] ?? 'Could not find details for this IFSC code.'); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const oldHtml = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2 text-success"></i> Copied!';
        setTimeout(() => { btn.innerHTML = oldHtml; }, 2000);
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

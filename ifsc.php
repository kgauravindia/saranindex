<?php
require_once __DIR__ . '/includes/functions.php';

$code = isset($_GET['code']) ? strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $_GET['code']))) : '';
if (empty($code) && isset($_GET['ifsc'])) {
    $code = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $_GET['ifsc'])));
}
if (empty($code)) {
    $code = 'SBIN0000054'; // Default to SBI Chapra Main
}

// Fetch live IFSC data from Olaw Banking API
$ifscData = lookupIfscApi($code);

$bank_name   = !empty($ifscData['data']['bank']) ? $ifscData['data']['bank'] : 'INDIAN BANK';
$branch_name = !empty($ifscData['data']['branch']) ? $ifscData['data']['branch'] : '';
$city        = !empty($ifscData['data']['city2']) ? $ifscData['data']['city2'] : (!empty($ifscData['data']['city1']) ? $ifscData['data']['city1'] : 'SARAN');
$state       = !empty($ifscData['data']['state']) ? $ifscData['data']['state'] : 'BIHAR';
$address     = !empty($ifscData['data']['address']) ? $ifscData['data']['address'] : '';
$micr        = !empty($ifscData['data']['micr']) ? $ifscData['data']['micr'] : 'N/A';
$phone       = !empty($ifscData['data']['phone']) && $ifscData['data']['phone'] !== '0' ? $ifscData['data']['phone'] : '';
$stdcode     = !empty($ifscData['data']['stdcode']) && $ifscData['data']['stdcode'] !== '0' ? $ifscData['data']['stdcode'] : '';

// Find related bank listings in Saran district
$db = getDB();
$relatedListings = [];
if ($db && $ifscData['success']) {
    try {
        $searchTerms = ['%' . $code . '%'];
        $firstWord = explode(' ', $bank_name)[0] ?? '';
        if (strlen($firstWord) >= 3) {
            $searchTerms[] = '%' . $firstWord . '%';
        }
        
        $sql = "SELECT id, title, hindi_title, slug, contact_person, mobile, mobile_visibility, address, pincode, local_reg_no 
                FROM listings 
                WHERE (local_reg_no LIKE ? OR description LIKE ? OR title LIKE ?) AND status = 'ACTIVE' 
                LIMIT 12";
        $stmt = $db->prepare($sql);
        $stmt->execute(['%' . $code . '%', '%' . $code . '%', '%' . $firstWord . '%']);
        $relatedListings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching related bank listings: " . $e->getMessage());
    }
}

$page_title = "IFSC Code " . htmlspecialchars($code) . " – " . htmlspecialchars($bank_name) . " (" . htmlspecialchars($branch_name) . ") | Saran Index";
$meta_description = "Live details for Bank IFSC Code " . htmlspecialchars($code) . " (" . htmlspecialchars($bank_name) . ", " . htmlspecialchars($branch_name) . " Branch). Branch address, MICR code, NEFT/RTGS/IMPS transfer details, and verified local bank branches in Saran District.";
$meta_keywords = "IFSC " . $code . ", " . $bank_name . " IFSC, " . $branch_name . " bank branch, Chapra bank IFSC, Saran bank branches, NEFT RTGS IMPS Saran, Olaw Banking API";

require_once __DIR__ . '/includes/header.php';

// Popular Saran District Bank IFSC Codes
$popular_ifsc = [
    'SBIN0000054' => 'SBI Chapra Main',
    'PUNB0MBGB06' => 'Bihar Gramin Bank (Saran)',
    'BARB0CHAPRA' => 'Bank of Baroda Chapra',
    'CBIN0280018' => 'Central Bank Chapra',
    'PUNB0007800' => 'PNB Chapra Main',
    'HDFC0002568' => 'HDFC Bank Chapra',
    'ICIC0001150' => 'ICICI Bank Chapra',
    'UTIB0001097' => 'Axis Bank Chapra',
    'UBIN0530018' => 'Union Bank Chapra',
    'CNRB0000216' => 'Canara Bank Chapra',
    'SBIN0001246' => 'SBI Sonepur Branch',
    'SBIN0002975' => 'SBI Marhaura Branch'
];
?>

<!-- Schema.org JSON-LD -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BankOrCreditUnion",
      "name": <?php echo json_encode($bank_name . " - " . $branch_name); ?>,
      "address": {
        "@type": "PostalAddress",
        "streetAddress": <?php echo json_encode($address); ?>,
        "addressLocality": <?php echo json_encode($city); ?>,
        "addressRegion": <?php echo json_encode($state); ?>,
        "addressCountry": "IN"
      }
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "<?php echo BASE_URL; ?>"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Bank IFSC Directory",
          "item": "<?php echo BASE_URL; ?>ifsc.php"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": <?php echo json_encode("IFSC " . $code); ?>,
          "item": <?php echo json_encode(getSiteCanonicalUrl('ifsc.php?code=' . $code)); ?>
        }
      ]
    }
  ]
}
</script>

<!-- Hero Section -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(135deg, #091a30 0%, #0f3460 50%, #164e87 100%);">
    <div class="container position-relative z-1 py-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>" class="text-white-50 text-decoration-none"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="ifsc.php" class="text-white-50 text-decoration-none">Bank IFSC Directory</a></li>
                <li class="breadcrumb-item active text-warning fw-semibold" aria-current="page">IFSC <?php echo htmlspecialchars($code); ?></li>
            </ol>
        </nav>

        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                    <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-warning text-dark fw-bold small shadow-sm">
                        <i class="bi bi-bank2 fs-6"></i>
                        <span>Live Bank IFSC & Branch Directory</span>
                    </span>
                    <a href="pincode.php" class="btn btn-outline-light btn-sm rounded-pill fw-semibold" style="font-size: 0.8rem;">
                        <i class="bi bi-mailbox2 me-1"></i> Postal PIN Code Search
                    </a>
                </div>

                <h1 class="fw-bolder font-heading text-white display-5 mb-2">
                    IFSC Code: <span class="text-warning font-monospace text-uppercase"><?php echo htmlspecialchars($code); ?></span>
                </h1>
                
                <p class="text-white-50 lead mb-4" style="max-width: 680px; font-size: 1.05rem;">
                    Official banking details for <strong class="text-white"><?php echo htmlspecialchars($bank_name); ?></strong><?php echo !empty($branch_name) ? ', <strong>' . htmlspecialchars($branch_name) . '</strong> Branch' : ''; ?>. Verified RTGS, NEFT, IMPS & UPI routing code via Olaw Data API.
                </p>

                <!-- Search IFSC Code Form -->
                <form action="<?php echo BASE_URL; ?>ifsc.php" method="GET" class="d-flex gap-2 max-w-lg" style="max-width: 480px;">
                    <div class="input-group shadow-lg rounded-3 overflow-hidden">
                        <span class="input-group-text bg-white border-0 text-primary px-3">
                            <i class="bi bi-search fs-5"></i>
                        </span>
                        <input type="text" name="code" class="form-control form-control-lg border-0 font-monospace fw-bold text-uppercase" placeholder="Enter 11-digit IFSC (e.g. SBIN0000054)..." value="<?php echo htmlspecialchars($code); ?>" maxlength="11" pattern="[A-Za-z0-9]{11}" required autocomplete="off">
                        <button class="btn btn-warning fw-bold px-4 text-dark" type="submit">
                            <i class="bi bi-check2-circle me-1"></i> Verify
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 bg-white bg-opacity-10 backdrop-blur rounded-4 p-4 text-white shadow-lg border-top border-white-20">
                    <h6 class="fw-bold text-warning text-uppercase letter-spacing-1 mb-3 small d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-shield-check me-1.5"></i> Branch Summary</span>
                        <?php if ($ifscData['success']): ?>
                            <span class="badge bg-success rounded-pill fw-bold" style="font-size: 0.7rem;">RBI ACTIVE</span>
                        <?php else: ?>
                            <span class="badge bg-danger rounded-pill fw-bold" style="font-size: 0.7rem;">NOT FOUND</span>
                        <?php endif; ?>
                    </h6>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2.5 small">
                        <li class="d-flex justify-content-between align-items-center border-bottom border-white-10 pb-2">
                            <span class="text-white-50"><i class="bi bi-bank me-1.5"></i> Bank:</span>
                            <strong class="text-white text-end text-truncate ms-2" style="max-width: 200px;"><?php echo htmlspecialchars($bank_name); ?></strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center border-bottom border-white-10 pb-2">
                            <span class="text-white-50"><i class="bi bi-geo-alt me-1.5"></i> Branch:</span>
                            <strong class="text-white text-end"><?php echo htmlspecialchars($branch_name ?: 'Main Branch'); ?></strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center border-bottom border-white-10 pb-2">
                            <span class="text-white-50"><i class="bi bi-upc-scan me-1.5"></i> MICR Code:</span>
                            <span class="badge bg-warning text-dark fw-bold rounded-pill font-monospace"><?php echo htmlspecialchars($micr); ?></span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center border-bottom border-white-10 pb-2">
                            <span class="text-white-50"><i class="bi bi-pin-map me-1.5"></i> District:</span>
                            <strong class="text-white"><?php echo htmlspecialchars($city); ?></strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-white-50"><i class="bi bi-lightning-charge me-1.5"></i> Transfer Modes:</span>
                            <span class="badge bg-success text-white fw-bold rounded-pill">NEFT / RTGS / IMPS</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Saran & National IFSC Quick Links -->
<section class="py-3 bg-light border-bottom">
    <div class="container">
        <div class="d-flex align-items-center gap-2 overflow-auto py-1 scrollbar-thin">
            <span class="text-dark small fw-bold text-nowrap d-flex align-items-center gap-1 me-1">
                <i class="bi bi-star-fill text-warning"></i> Popular Hubs:
            </span>
            <?php foreach ($popular_ifsc as $sifsc => $slabel): 
                $isActive = ($sifsc === $code);
            ?>
                <a href="<?php echo BASE_URL; ?>ifsc.php?code=<?php echo urlencode($sifsc); ?>" 
                   class="btn btn-sm text-nowrap rounded-pill px-3 py-1 fw-semibold <?php echo $isActive ? 'btn-primary' : 'btn-white bg-white border text-secondary shadow-2xs hover-primary'; ?>"
                   style="font-size: 0.8rem;">
                    <?php echo htmlspecialchars($slabel); ?> 
                    <span class="font-monospace small <?php echo $isActive ? 'text-white-50' : 'text-muted'; ?>">(<?php echo htmlspecialchars($sifsc); ?>)</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Main Details Section -->
<div class="container py-5">
    <?php if ($ifscData['success']): ?>
        <div class="row g-4 mb-5">
            <!-- Left: Branch Information Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 border-bottom pb-4 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary p-3 rounded-4 fs-2 flex-shrink-0">
                                <i class="bi bi-bank2"></i>
                            </div>
                            <div>
                                <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 mb-1 small fw-bold">RESERVE BANK OF INDIA (RBI) AUTHORIZED</span>
                                <h2 class="fw-bolder text-dark mb-0 font-heading fs-3">
                                    <?php echo htmlspecialchars($bank_name); ?>
                                </h2>
                                <div class="text-muted fw-semibold">
                                    <?php echo htmlspecialchars($branch_name); ?> Branch
                                </div>
                            </div>
                        </div>

                        <!-- Copy IFSC Button -->
                        <div>
                            <button type="button" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-bold d-flex align-items-center gap-2 shadow-2xs" onclick="copyIfscCode('<?php echo htmlspecialchars($code); ?>', this)">
                                <i class="bi bi-clipboard-check fs-5"></i>
                                <span>Copy IFSC</span>
                            </button>
                        </div>
                    </div>

                    <!-- Detailed Properties Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase w-30" style="width: 28%;">IFSC Code</th>
                                    <td class="fw-bold font-monospace text-primary fs-5">
                                        <?php echo htmlspecialchars($code); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">Bank Name</th>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($bank_name); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">Branch Name</th>
                                    <td class="text-dark fw-semibold"><?php echo htmlspecialchars($branch_name ?: 'Main Branch'); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">MICR Code</th>
                                    <td class="font-monospace fw-semibold text-secondary"><?php echo htmlspecialchars($micr); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">Branch Address</th>
                                    <td class="text-secondary"><?php echo htmlspecialchars($address ?: 'Not Available'); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">City / District</th>
                                    <td class="text-dark fw-semibold"><?php echo htmlspecialchars($city); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">State</th>
                                    <td class="text-dark fw-semibold"><?php echo htmlspecialchars($state); ?></td>
                                </tr>
                                <?php if (!empty($phone) || !empty($stdcode)): ?>
                                    <tr>
                                        <th class="bg-light text-muted small text-uppercase">Contact Number</th>
                                        <td>
                                            <?php if (!empty($stdcode)): ?>STD: <strong><?php echo htmlspecialchars($stdcode); ?></strong> <?php endif; ?>
                                            <?php if (!empty($phone)): ?>Phone: <strong><?php echo htmlspecialchars($phone); ?></strong><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">Electronic Clearing</th>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1.5">
                                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1">NEFT Supported</span>
                                            <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25 rounded-pill px-2.5 py-1">RTGS Supported</span>
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info border-opacity-25 rounded-pill px-2.5 py-1">IMPS 24x7</span>
                                            <span class="badge bg-warning-subtle text-dark border border-warning border-opacity-25 rounded-pill px-2.5 py-1">UPI Active</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Quick Transfer Note -->
                    <div class="p-3 bg-light rounded-3 mt-4 border border-light-subtle d-flex align-items-start gap-2.5 small">
                        <i class="bi bi-info-circle-fill text-primary fs-5 flex-shrink-0 mt-0.5"></i>
                        <div class="text-muted">
                            <strong class="text-dark">How to use this IFSC for money transfer:</strong> When transferring funds via Net Banking, Mobile Banking, Google Pay, PhonePe, or visiting a bank branch, provide Account Holder Name, Account Number, and IFSC Code <code class="text-primary fw-bold font-monospace"><?php echo htmlspecialchars($code); ?></code>.
                        </div>
                    </div>
                </div>

                <!-- Related Bank Directory Listings in Saran -->
                <?php if (!empty($relatedListings)): ?>
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h4 class="fw-bold font-heading text-dark mb-1">
                                    <i class="bi bi-buildings-fill text-primary me-2"></i>Bank Branches & Offices in Saran
                                </h4>
                                <p class="text-muted small mb-0">Verified bank listings from the Saran Index directory matching this network.</p>
                            </div>
                            <span class="badge bg-primary rounded-pill"><?php echo count($relatedListings); ?> Found</span>
                        </div>

                        <div class="row g-3">
                            <?php foreach ($relatedListings as $rel): 
                                $userCanSee = isUserLoggedIn();
                                $maskedRelMobile = maskPhoneNumber($rel['mobile'] ?? '');
                                $relUrl = getListingUrl($rel['slug']);
                            ?>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 border bg-light h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">
                                                <a href="<?php echo $relUrl; ?>" class="text-dark text-decoration-none hover-primary">
                                                    <?php echo htmlspecialchars($rel['title']); ?>
                                                </a>
                                            </h6>
                                            <?php if (!empty($rel['local_reg_no'])): ?>
                                                <div class="badge bg-warning-subtle text-dark border border-warning border-opacity-50 rounded-pill mb-2 small font-monospace">
                                                    <?php echo htmlspecialchars($rel['local_reg_no']); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="small text-muted mb-2 line-clamp-2" style="font-size: 0.8rem;">
                                                <i class="bi bi-geo-alt text-danger me-1"></i><?php echo htmlspecialchars($rel['address'] ?? 'Saran, Bihar'); ?>
                                            </div>
                                        </div>

                                        <div class="pt-2 border-top mt-2 d-flex justify-content-between align-items-center">
                                            <span class="small font-monospace text-muted">
                                                <?php if ($userCanSee): ?>
                                                    <i class="bi bi-telephone-fill text-success me-1"></i><?php echo htmlspecialchars($rel['mobile'] ?? ''); ?>
                                                <?php else: ?>
                                                    <i class="bi bi-lock-fill text-warning me-1"></i><?php echo htmlspecialchars($maskedRelMobile); ?>
                                                <?php endif; ?>
                                            </span>
                                            <a href="<?php echo $relUrl; ?>" class="btn btn-outline-primary btn-sm rounded-pill py-0.5 px-2.5 small fw-semibold">
                                                View <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Knowledge Base & Guide -->
            <div class="col-lg-4">
                <!-- Anatomy of an IFSC Code Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold text-dark font-heading mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-diagram-3-fill text-primary"></i> Anatomy of an IFSC Code
                    </h5>
                    <p class="text-muted small mb-3">
                        Indian Financial System Code (IFSC) is an 11-character alphanumeric code assigned by the Reserve Bank of India (RBI).
                    </p>

                    <div class="p-3 bg-light rounded-3 font-monospace text-center mb-3 border">
                        <div class="display-6 fw-bold text-primary mb-1 letter-spacing-1">
                            <span class="text-success"><?php echo substr($code, 0, 4); ?></span><span class="text-danger">0</span><span class="text-info"><?php echo substr($code, 5); ?></span>
                        </div>
                        <div class="small text-muted">11 Characters Structure</div>
                    </div>

                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                        <li class="d-flex align-items-start gap-2">
                            <span class="badge bg-success rounded-pill px-2 py-0.5 mt-0.5">1-4</span>
                            <div><strong>First 4 Characters:</strong> Bank name representation (e.g. <code><?php echo substr($code, 0, 4); ?></code> represents <?php echo htmlspecialchars(explode(' ', $bank_name)[0]); ?>).</div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <span class="badge bg-danger rounded-pill px-2 py-0.5 mt-0.5">5th</span>
                            <div><strong>5th Character:</strong> Always <code>0</code> (Zero), reserved for future expandability.</div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <span class="badge bg-info rounded-pill px-2 py-0.5 mt-0.5">6-11</span>
                            <div><strong>Last 6 Characters:</strong> Unique branch identification code (e.g. <code><?php echo substr($code, 5); ?></code>).</div>
                        </li>
                    </ul>
                </div>

                <!-- Difference IFSC vs MICR -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold text-dark font-heading mb-3">
                        <i class="bi bi-arrow-left-right text-warning me-1"></i> IFSC vs MICR Code
                    </h5>
                    
                    <div class="mb-3 small">
                        <strong class="d-block text-dark mb-1">IFSC Code (11 Chars):</strong>
                        <p class="text-muted mb-0">Used for online electronic money transfers (NEFT, RTGS, IMPS, and Net Banking). Real-time routing across banks.</p>
                    </div>

                    <div class="small">
                        <strong class="d-block text-dark mb-1">MICR Code (9 Digits):</strong>
                        <p class="text-muted mb-0">Magnetic Ink Character Recognition code printed on cheque leaves used for physical cheque clearing processing.</p>
                    </div>
                </div>

                <!-- Verified API Badge -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border-start border-4 border-success">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-shield-fill-check text-success fs-4"></i>
                        <h6 class="fw-bold text-dark mb-0">Official Data Integration</h6>
                    </div>
                    <p class="text-muted small mb-0">
                        Live Bank IFSC data is securely verified via <strong>Olaw Financial & Data Services API</strong>, cross-referenced with RBI banking databases for Saran Index.
                    </p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Error / Invalid IFSC Result -->
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-5">
            <div class="text-warning display-3 mb-3"><i class="bi bi-exclamation-triangle"></i></div>
            <h3 class="fw-bold text-dark mb-2 font-heading">IFSC Code Not Found</h3>
            <p class="text-muted mx-auto mb-4" style="max-width: 500px;">
                <?php echo htmlspecialchars($ifscData['message'] ?? 'Could not find details for the requested IFSC code.'); ?>
            </p>
            <div>
                <a href="ifsc.php?code=SBIN0000054" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                    <i class="bi bi-arrow-clockwise me-1"></i> Load Default (SBI Chapra)
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Copy to Clipboard Script -->
<script>
function copyIfscCode(text, btn) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-lg text-success fs-5"></i> <span class="text-success">Copied!</span>';
            setTimeout(() => { btn.innerHTML = orig; }, 2000);
        });
    } else {
        const ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg text-success fs-5"></i> <span class="text-success">Copied!</span>';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


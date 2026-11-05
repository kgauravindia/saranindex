<?php
require_once __DIR__ . '/includes/functions.php';

$code = isset($_GET['code']) ? strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $_GET['code']))) : '';
if (empty($code) && isset($_GET['ifsc'])) {
    $code = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $_GET['ifsc'])));
}
if (empty($code)) {
    $code = 'SBIN0000054'; // एसबीआई छपरा मुख्य शाखा
}

// Olaw Banking API से लाइव डेटा प्राप्त करें
$ifscData = lookupIfscApi($code);

$bank_name   = !empty($ifscData['data']['bank']) ? $ifscData['data']['bank'] : 'INDIAN BANK';
$branch_name = !empty($ifscData['data']['branch']) ? $ifscData['data']['branch'] : '';
$city        = !empty($ifscData['data']['city2']) ? $ifscData['data']['city2'] : (!empty($ifscData['data']['city1']) ? $ifscData['data']['city1'] : 'SARAN');
$state       = !empty($ifscData['data']['state']) ? $ifscData['data']['state'] : 'BIHAR';
$address     = !empty($ifscData['data']['address']) ? $ifscData['data']['address'] : '';
$micr        = !empty($ifscData['data']['micr']) ? $ifscData['data']['micr'] : 'N/A';
$phone       = !empty($ifscData['data']['phone']) && $ifscData['data']['phone'] !== '0' ? $ifscData['data']['phone'] : '';
$stdcode     = !empty($ifscData['data']['stdcode']) && $ifscData['data']['stdcode'] !== '0' ? $ifscData['data']['stdcode'] : '';

// सारण जिले में संबंधित बैंक शाखाएं खोजें
$db = getDB();
$relatedListings = [];
if ($db && $ifscData['success']) {
    try {
        $firstWord = explode(' ', $bank_name)[0] ?? '';
        $sql = "SELECT id, title, hindi_title, slug, contact_person, mobile, mobile_visibility, address, pincode, local_reg_no 
                FROM listings 
                WHERE (local_reg_no LIKE ? OR description LIKE ? OR title LIKE ?) AND status = 'ACTIVE' 
                LIMIT 12";
        $stmt = $db->prepare($sql);
        $stmt->execute(['%' . $code . '%', '%' . $code . '%', '%' . $firstWord . '%']);
        $relatedListings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching related bank listings in Hindi: " . $e->getMessage());
    }
}

$page_title = "आईएफएससी कोड " . htmlspecialchars($code) . " – " . htmlspecialchars($bank_name) . " (" . htmlspecialchars($branch_name) . ") | सारण इंडेक्स";
$meta_description = "बैंक आईएफएससी कोड " . htmlspecialchars($code) . " (" . htmlspecialchars($bank_name) . ", " . htmlspecialchars($branch_name) . " शाखा) की लाइव जानकारी। शाखा का पता, एमआईसीआर कोड, आरटीजीएस/एनईएफटी/आईएमपीएस विवरण एवं सारण जिले की बैंक शाखाएं।";
$meta_keywords = "आईएफएससी कोड " . $code . ", " . $bank_name . " IFSC, छपरा बैंक IFSC, सारण बैंक शाखाएं, Olaw बैंकिंग API";

require_once __DIR__ . '/includes/header.php';

// सारण जिले के प्रमुख बैंक आईएफएससी कोड
$popular_ifsc = [
    'SBIN0000054' => 'एसबीआई छपरा मुख्य',
    'PUNB0MBGB06' => 'बिहार ग्रामीण बैंक (सारण)',
    'BARB0CHAPRA' => 'बैंक ऑफ बड़ौदा छपरा',
    'CBIN0280018' => 'सेंट्रल बैंक छपरा',
    'PUNB0007800' => 'पीएनबी छपरा मुख्य',
    'HDFC0002568' => 'एचडीएफसी बैंक छपरा',
    'ICIC0001150' => 'आईसीआईसीआई बैंक छपरा',
    'UTIB0001097' => 'एक्सिस बैंक छपरा',
    'UBIN0530018' => 'यूनियन बैंक छपरा',
    'CNRB0000216' => 'केनरा बैंक छपरा',
    'SBIN0001246' => 'एसबीआई सोनपुर शाखा',
    'SBIN0002975' => 'एसबीआई मढ़ौरा शाखा'
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
          "name": "मुख्य पृष्ठ",
          "item": "<?php echo HINDI_BASE_URL; ?>"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "बैंक आईएफएससी निर्देशिका",
          "item": "<?php echo HINDI_BASE_URL; ?>ifsc.php"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": <?php echo json_encode("IFSC " . $code); ?>,
          "item": <?php echo json_encode(getSiteCanonicalUrl('ifsc.php?code=' . $code, true)); ?>
        }
      ]
    }
  ]
}
</script>

<!-- हीरो हेडर सेक्शन -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(135deg, #091a30 0%, #0f3460 50%, #164e87 100%);">
    <div class="container position-relative z-1 py-3">
        <!-- ब्रेडक्रम्ब -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 small text-white-50">
                <li class="breadcrumb-item"><a href="<?php echo HINDI_BASE_URL; ?>" class="text-white-50 text-decoration-none"><i class="bi bi-house-door me-1"></i>मुख्य पृष्ठ</a></li>
                <li class="breadcrumb-item"><a href="hindi/ifsc.php" class="text-white-50 text-decoration-none">बैंक आईएफएससी निर्देशिका</a></li>
                <li class="breadcrumb-item active text-warning fw-semibold" aria-current="page">IFSC <?php echo htmlspecialchars($code); ?></li>
            </ol>
        </nav>

        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                    <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-warning text-dark fw-bold small shadow-sm">
                        <i class="bi bi-bank2 fs-6"></i>
                        <span>लाइव बैंक आईएफएससी एवं शाखा डायरेक्टरी</span>
                    </span>
                    <a href="hindi/pincode.php" class="btn btn-outline-light btn-sm rounded-pill fw-semibold" style="font-size: 0.8rem;">
                        <i class="bi bi-mailbox2 me-1"></i> पिन कोड खोजें
                    </a>
                </div>

                <h1 class="fw-bolder font-heading text-white display-5 mb-2">
                    आईएफएससी कोड: <span class="text-warning font-monospace text-uppercase"><?php echo htmlspecialchars($code); ?></span>
                </h1>
                
                <p class="text-white-50 lead mb-4" style="max-width: 680px; font-size: 1.05rem;">
                    <strong class="text-white"><?php echo htmlspecialchars($bank_name); ?></strong><?php echo !empty($branch_name) ? ', <strong>' . htmlspecialchars($branch_name) . '</strong> शाखा' : ''; ?> की आधिकारिक जानकारी। ओला (Olaw) बैंकिंग एपीआई द्वारा सत्यापित एनईएफटी, आरटीजीएस, आईएमपीएस एवं यूपीआई रूटिंग कोड।
                </p>

                <!-- सर्च आईएफएससी फॉर्म -->
                <form action="<?php echo HINDI_BASE_URL; ?>ifsc.php" method="GET" class="d-flex gap-2 max-w-lg" style="max-width: 480px;">
                    <div class="input-group shadow-lg rounded-3 overflow-hidden">
                        <span class="input-group-text bg-white border-0 text-primary px-3">
                            <i class="bi bi-search fs-5"></i>
                        </span>
                        <input type="text" name="code" class="form-control form-control-lg border-0 font-monospace fw-bold text-uppercase" placeholder="11 अंकों का IFSC कोड दर्ज करें..." value="<?php echo htmlspecialchars($code); ?>" maxlength="11" pattern="[A-Za-z0-9]{11}" required autocomplete="off">
                        <button class="btn btn-warning fw-bold px-4 text-dark" type="submit">
                            <i class="bi bi-check2-circle me-1"></i> जांचें
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 bg-white bg-opacity-10 backdrop-blur rounded-4 p-4 text-white shadow-lg border-top border-white-20">
                    <h6 class="fw-bold text-warning text-uppercase letter-spacing-1 mb-3 small d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-shield-check me-1.5"></i> शाखा संक्षिप्त विवरण</span>
                        <?php if ($ifscData['success']): ?>
                            <span class="badge bg-success rounded-pill fw-bold" style="font-size: 0.7rem;">RBI सक्रिय</span>
                        <?php else: ?>
                            <span class="badge bg-danger rounded-pill fw-bold" style="font-size: 0.7rem;">अमान्य कोड</span>
                        <?php endif; ?>
                    </h6>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2.5 small">
                        <li class="d-flex justify-content-between align-items-center border-bottom border-white-10 pb-2">
                            <span class="text-white-50"><i class="bi bi-bank me-1.5"></i> बैंक:</span>
                            <strong class="text-white text-end text-truncate ms-2" style="max-width: 200px;"><?php echo htmlspecialchars($bank_name); ?></strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center border-bottom border-white-10 pb-2">
                            <span class="text-white-50"><i class="bi bi-geo-alt me-1.5"></i> शाखा:</span>
                            <strong class="text-white text-end"><?php echo htmlspecialchars($branch_name ?: 'मुख्य शाखा'); ?></strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center border-bottom border-white-10 pb-2">
                            <span class="text-white-50"><i class="bi bi-upc-scan me-1.5"></i> एमआईसीआर (MICR):</span>
                            <span class="badge bg-warning text-dark fw-bold rounded-pill font-monospace"><?php echo htmlspecialchars($micr); ?></span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center border-bottom border-white-10 pb-2">
                            <span class="text-white-50"><i class="bi bi-pin-map me-1.5"></i> जिला:</span>
                            <strong class="text-white"><?php echo htmlspecialchars($city); ?></strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-white-50"><i class="bi bi-lightning-charge me-1.5"></i> फंड ट्रांसफर:</span>
                            <span class="badge bg-success text-white fw-bold rounded-pill">NEFT / RTGS / IMPS</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- लोकप्रिय बैंक आईएफएससी त्वरित लिंक्स -->
<section class="py-3 bg-light border-bottom">
    <div class="container">
        <div class="d-flex align-items-center gap-2 overflow-auto py-1 scrollbar-thin">
            <span class="text-dark small fw-bold text-nowrap d-flex align-items-center gap-1 me-1">
                <i class="bi bi-star-fill text-warning"></i> प्रमुख बैंक:
            </span>
            <?php foreach ($popular_ifsc as $sifsc => $slabel): 
                $isActive = ($sifsc === $code);
            ?>
                <a href="<?php echo HINDI_BASE_URL; ?>ifsc.php?code=<?php echo urlencode($sifsc); ?>" 
                   class="btn btn-sm text-nowrap rounded-pill px-3 py-1 fw-semibold <?php echo $isActive ? 'btn-primary' : 'btn-white bg-white border text-secondary shadow-2xs hover-primary'; ?>"
                   style="font-size: 0.8rem;">
                    <?php echo htmlspecialchars($slabel); ?> 
                    <span class="font-monospace small <?php echo $isActive ? 'text-white-50' : 'text-muted'; ?>">(<?php echo htmlspecialchars($sifsc); ?>)</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- मुख्य विवरण सेक्शन -->
<div class="container py-5">
    <?php if ($ifscData['success']): ?>
        <div class="row g-4 mb-5">
            <!-- बायां भाग: शाखा विवरण तालिका -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 border-bottom pb-4 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary p-3 rounded-4 fs-2 flex-shrink-0">
                                <i class="bi bi-bank2"></i>
                            </div>
                            <div>
                                <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 mb-1 small fw-bold">भारतीय रिज़र्व बैंक (RBI) अधिकृत शाखा</span>
                                <h2 class="fw-bolder text-dark mb-0 font-heading fs-3">
                                    <?php echo htmlspecialchars($bank_name); ?>
                                </h2>
                                <div class="text-muted fw-semibold">
                                    <?php echo htmlspecialchars($branch_name); ?> शाखा
                                </div>
                            </div>
                        </div>

                        <!-- कॉपी आईएफएससी बटन -->
                        <div>
                            <button type="button" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-bold d-flex align-items-center gap-2 shadow-2xs" onclick="copyIfscCode('<?php echo htmlspecialchars($code); ?>', this)">
                                <i class="bi bi-clipboard-check fs-5"></i>
                                <span>IFSC कॉपी करें</span>
                            </button>
                        </div>
                    </div>

                    <!-- विस्तृत विवरण तालिका -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase w-30" style="width: 28%;">आईएफएससी कोड (IFSC)</th>
                                    <td class="fw-bold font-monospace text-primary fs-5">
                                        <?php echo htmlspecialchars($code); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">बैंक का नाम</th>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($bank_name); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">शाखा का नाम</th>
                                    <td class="text-dark fw-semibold"><?php echo htmlspecialchars($branch_name ?: 'मुख्य शाखा'); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">एमआईसीआर कोड (MICR)</th>
                                    <td class="font-monospace fw-semibold text-secondary"><?php echo htmlspecialchars($micr); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">शाखा का पता</th>
                                    <td class="text-secondary"><?php echo htmlspecialchars($address ?: 'उपलब्ध नहीं'); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">शहर / जिला</th>
                                    <td class="text-dark fw-semibold"><?php echo htmlspecialchars($city); ?></td>
                                </tr>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">राज्य</th>
                                    <td class="text-dark fw-semibold"><?php echo htmlspecialchars($state); ?></td>
                                </tr>
                                <?php if (!empty($phone) || !empty($stdcode)): ?>
                                    <tr>
                                        <th class="bg-light text-muted small text-uppercase">संपर्क नंबर</th>
                                        <td>
                                            <?php if (!empty($stdcode)): ?>एसटीडी कोड: <strong><?php echo htmlspecialchars($stdcode); ?></strong> <?php endif; ?>
                                            <?php if (!empty($phone)): ?>फोन: <strong><?php echo htmlspecialchars($phone); ?></strong><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <th class="bg-light text-muted small text-uppercase">इलेक्ट्रॉनिक फंड ट्रांसफर</th>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1.5">
                                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1">NEFT समर्थित</span>
                                            <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25 rounded-pill px-2.5 py-1">RTGS समर्थित</span>
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info border-opacity-25 rounded-pill px-2.5 py-1">IMPS 24x7</span>
                                            <span class="badge bg-warning-subtle text-dark border border-warning border-opacity-25 rounded-pill px-2.5 py-1">UPI सक्रिय</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- पैसा भेजने संबंधी नोट -->
                    <div class="p-3 bg-light rounded-3 mt-4 border border-light-subtle d-flex align-items-start gap-2.5 small">
                        <i class="bi bi-info-circle-fill text-primary fs-5 flex-shrink-0 mt-0.5"></i>
                        <div class="text-muted">
                            <strong class="text-dark">पैसे ट्रांसफर करने के लिए इस कोड का उपयोग कैसे करें:</strong> नेट बैंकिंग, मोबाइल बैंकिंग, फोनपे, गूगल पे या बैंक शाखा जाकर पैसे भेजने के लिए खाताधारक का नाम, खाता संख्या एवं आईएफएससी कोड <code class="text-primary fw-bold font-monospace"><?php echo htmlspecialchars($code); ?></code> दर्ज करें।
                        </div>
                    </div>
                </div>

                <!-- सारण जिले में संबंधित बैंक शाखाएं -->
                <?php if (!empty($relatedListings)): ?>
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h4 class="fw-bold font-heading text-dark mb-1">
                                    <i class="bi bi-buildings-fill text-primary me-2"></i>सारण जिले में बैंक शाखाएं
                                </h4>
                                <p class="text-muted small mb-0">सारण इंडेक्स डायरेक्टरी में सत्यापित संबंधित बैंक शाखाओं की सूची।</p>
                            </div>
                            <span class="badge bg-primary rounded-pill"><?php echo count($relatedListings); ?> शाखाएं</span>
                        </div>

                        <div class="row g-3">
                            <?php foreach ($relatedListings as $rel): 
                                $userCanSee = isUserLoggedIn();
                                $maskedRelMobile = maskPhoneNumber($rel['mobile'] ?? '');
                                $relUrl = getListingUrl($rel['slug']);
                                $relTitle = !empty($rel['hindi_title']) ? $rel['hindi_title'] : $rel['title'];
                            ?>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 border bg-light h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">
                                                <a href="<?php echo $relUrl; ?>" class="text-dark text-decoration-none hover-primary">
                                                    <?php echo htmlspecialchars($relTitle); ?>
                                                </a>
                                            </h6>
                                            <?php if (!empty($rel['local_reg_no'])): ?>
                                                <div class="badge bg-warning-subtle text-dark border border-warning border-opacity-50 rounded-pill mb-2 small font-monospace">
                                                    <?php echo htmlspecialchars($rel['local_reg_no']); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="small text-muted mb-2 line-clamp-2" style="font-size: 0.8rem;">
                                                <i class="bi bi-geo-alt text-danger me-1"></i><?php echo htmlspecialchars($rel['address'] ?? 'सारण, बिहार'); ?>
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
                                                विवरण <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- दायां भाग: जानकारी एवं मार्गदर्शिका -->
            <div class="col-lg-4">
                <!-- आईएफएससी कोड की संरचना -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold text-dark font-heading mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-diagram-3-fill text-primary"></i> आईएफएससी कोड की संरचना
                    </h5>
                    <p class="text-muted small mb-3">
                        भारतीय वित्तीय प्रणाली कोड (IFSC) 11 अक्षरों का अल्फान्यूमेरिक कोड होता है जो भारतीय रिजर्व बैंक (RBI) द्वारा प्रत्येक शाखा को प्रदान किया जाता है।
                    </p>

                    <div class="p-3 bg-light rounded-3 font-monospace text-center mb-3 border">
                        <div class="display-6 fw-bold text-primary mb-1 letter-spacing-1">
                            <span class="text-success"><?php echo substr($code, 0, 4); ?></span><span class="text-danger">0</span><span class="text-info"><?php echo substr($code, 5); ?></span>
                        </div>
                        <div class="small text-muted">11 अक्षरों की संरचना</div>
                    </div>

                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                        <li class="d-flex align-items-start gap-2">
                            <span class="badge bg-success rounded-pill px-2 py-0.5 mt-0.5">1-4</span>
                            <div><strong>प्रथम 4 अक्षर:</strong> बैंक का नाम दर्शाते हैं (जैसे <code><?php echo substr($code, 0, 4); ?></code>)।</div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <span class="badge bg-danger rounded-pill px-2 py-0.5 mt-0.5">5वां</span>
                            <div><strong>5वां अक्षर:</strong> हमेशा <code>0</code> (शून्य) होता है, जो भविष्य के उपयोग हेतु सुरक्षित है।</div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <span class="badge bg-info rounded-pill px-2 py-0.5 mt-0.5">6-11</span>
                            <div><strong>अंतिम 6 अक्षर:</strong> संबंधित विशिष्ट बैंक शाखा की पहचान कोड होते हैं।</div>
                        </li>
                    </ul>
                </div>

                <!-- IFSC बनाम MICR -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold text-dark font-heading mb-3">
                        <i class="bi bi-arrow-left-right text-warning me-1"></i> IFSC एवं MICR में अंतर
                    </h5>
                    
                    <div class="mb-3 small">
                        <strong class="d-block text-dark mb-1">आईएफएससी कोड (11 अक्षर):</strong>
                        <p class="text-muted mb-0">ऑनलाइन इलेक्ट्रॉनिक फंड ट्रांसफर (NEFT, RTGS, IMPS) हेतु प्रयुक्त होता है।</p>
                    </div>

                    <div class="small">
                        <strong class="d-block text-dark mb-1">एमआईसीआर कोड (9 अंक):</strong>
                        <p class="text-muted mb-0">चेक बुक के नीचे मुद्रित मैग्नेटिक इंक कोड होता है जो चेक क्लीयरिंग के लिए उपयोग किया जाता है।</p>
                    </div>
                </div>

                <!-- प्रमाणित एपीआई बैज -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border-start border-4 border-success">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-shield-fill-check text-success fs-4"></i>
                        <h6 class="fw-bold text-dark mb-0">आधिकारिक डेटा इंटीग्रेशन</h6>
                    </div>
                    <p class="text-muted small mb-0">
                        लाइव बैंक आईएफएससी विवरण <strong>ओला (Olaw) डेटा सर्विसेज एपीआई</strong> द्वारा सुरक्षित रूप से सत्यापित है, जो भारतीय रिज़र्व बैंक (RBI) बैंकिंग डेटाबेस से जुड़ा है।
                    </p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- परिणाम न मिलने पर त्रुटि कार्ड -->
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-5">
            <div class="text-warning display-3 mb-3"><i class="bi bi-exclamation-triangle"></i></div>
            <h3 class="fw-bold text-dark mb-2 font-heading">आईएफएससी कोड नहीं मिला</h3>
            <p class="text-muted mx-auto mb-4" style="max-width: 500px;">
                <?php echo htmlspecialchars($ifscData['message'] ?? 'अनुरोधित आईएफएससी कोड के लिए कोई रिकॉर्ड उपलब्ध नहीं है।'); ?>
            </p>
            <div>
                <a href="hindi/ifsc.php?code=SBIN0000054" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                    <i class="bi bi-arrow-clockwise me-1"></i> मुख्य शाखा लोड करें (SBI छपरा)
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- क्लिपबोर्ड पर कॉपी करने का जावास्क्रिप्ट -->
<script>
function copyIfscCode(text, btn) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-lg text-success fs-5"></i> <span class="text-success">कॉपी हुआ!</span>';
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
        btn.innerHTML = '<i class="bi bi-check-lg text-success fs-5"></i> <span class="text-success">कॉपी हुआ!</span>';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


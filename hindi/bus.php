<?php
require_once __DIR__ . '/includes/functions.php';

$db = getDB();
$rawReg = trim($_GET['reg'] ?? '');
$cleanReg = preg_replace('/[^a-zA-Z0-9]/', '', $rawReg);

$vehicle = null;
$timetables = [];
$relatedVehicles = [];

// Check if a single vehicle is requested
if (!empty($rawReg) && $db) {
    try {
        $vStmt = $db->prepare("
            SELECT * FROM vehicle 
            WHERE registration_number = :raw 
               OR REPLACE(REPLACE(registration_number, '-', ''), ' ', '') = :clean
               OR application_no = :app_no
            LIMIT 1
        ");
        $vStmt->execute([
            'raw' => $rawReg,
            'clean' => $cleanReg,
            'app_no' => is_numeric($rawReg) ? intval($rawReg) : 0
        ]);
        $vehicle = $vStmt->fetch(PDO::FETCH_ASSOC);

        if ($vehicle) {
            // Fetch timetable
            $ttStmt = $db->prepare("
                SELECT * FROM vehicle_timetable 
                WHERE vehicle_id = :vid 
                ORDER BY trip_number ASC, stop_order ASC
            ");
            $ttStmt->execute(['vid' => $vehicle['id']]);
            $timetables = $ttStmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch related vehicles
            $relStmt = $db->prepare("
                SELECT id, application_no, registration_number, route_no, route_name, origin, destination, trips_per_day
                FROM vehicle 
                WHERE (route_no = :rno OR (origin = :orig AND destination = :dest))
                  AND id != :vid 
                ORDER BY application_no ASC 
                LIMIT 4
            ");
            $relStmt->execute([
                'rno' => $vehicle['route_no'],
                'orig' => $vehicle['origin'],
                'dest' => $vehicle['destination'],
                'vid' => $vehicle['id']
            ]);
            $relatedVehicles = $relStmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        error_log("Hindi Vehicle Detail Query Error: " . $e->getMessage());
    }
}

// -------------------------------------------------------------
// CASE A: SINGLE VEHICLE DETAIL VIEW IN HINDI (e.g. /hindi/bus/BR04F5866)
// -------------------------------------------------------------
if (!empty($rawReg)) {
    if (!$vehicle) {
        // Not Found
        http_response_code(404);
        $page_title = "बस सेवा नहीं मिली | सारण इंडेक्स";
        $meta_description = "मांग की गई बस सेवा या पंजीकरण संख्या उपलब्ध नहीं है। सारण प्रमंडल की अन्य सभी बस सेवाएं देखें।";
        require_once __DIR__ . '/includes/header.php';
        ?>
        <div class="container py-5 text-center">
            <div class="py-5">
                <div class="text-danger fs-1 mb-3"><i class="bi bi-exclamation-triangle"></i></div>
                <h2 class="fw-bold font-heading mb-2">बस पंजीकरण संख्या नहीं मिली</h2>
                <p class="text-muted mb-4 lead">वाहन संख्या <code><?php echo htmlspecialchars($rawReg); ?></code> के लिए कोई रिकॉर्ड उपलब्ध नहीं है।</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="hindi/bus" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="bi bi-bus-front me-1"></i>सभी बसें देखें
                    </a>
                    <a href="hindi/" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-house-door me-1"></i>होम पेज
                    </a>
                </div>
            </div>
        </div>
        <?php
        require_once __DIR__ . '/includes/footer.php';
        exit;
    }

    $origEn = getBusPlaceEnglish($vehicle['origin']);
    $destEn = getBusPlaceEnglish($vehicle['destination']);
    $outwardTitle = "{$vehicle['origin']} से {$vehicle['destination']} बस";
    $returnTitle = "वापसी {$vehicle['destination']} से {$vehicle['origin']}";
    $outwardEn = "{$origEn} to {$destEn} Bus";
    $returnEn = "Return {$destEn} to {$origEn}";

    // Vehicle Found - Single Detail Page
    $page_title = "{$outwardTitle} | {$returnTitle} ({$vehicle['registration_number']}) – बस समय-सारणी | सारण इंडेक्स";
    $meta_description = "{$outwardTitle} ({$outwardEn}) और {$returnTitle} ({$returnEn}) की पूरी समय-सारणी, रूट {$vehicle['route_no']} भाया {$vehicle['via']}। बस नंबर {$vehicle['registration_number']}।";
    $canonical_url = BASE_URL . "hindi/bus/" . urlencode($vehicle['registration_number']);

    $legs = segmentBusTimetableLegs($timetables, $vehicle['origin'], $vehicle['destination'], 'hi');

    $viaList = !empty($vehicle['via']) ? array_filter(array_map('trim', explode(',', $vehicle['via']))) : [];
    $isDown = stripos($vehicle['route_no'] ?? '', 'डाउन') !== false || stripos($vehicle['route_no'] ?? '', 'down') !== false || stripos($vehicle['route_no'] ?? '', 'DN') !== false;

    require_once __DIR__ . '/includes/header.php';
    ?>

    <style>
    .bus-detail-hero {
        background: linear-gradient(135deg, #0b2545 0%, #133c55 50%, #134074 100%);
        color: #fff;
    }
    .badge-route-lg {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 50rem;
    }
    .badge-route-lg-alt {
        background: linear-gradient(135deg, #d97706, #b45309);
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 50rem;
    }
    .stat-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1rem;
        transition: all 0.2s ease;
    }
    .stat-box:hover {
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .card-leg-outward .card-header {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
    }
    .card-leg-return .card-header {
        background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
    }
    @media print {
        .no-print, header, footer, nav, .btn, #backToTopBtn {
            display: none !important;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
    }
    </style>

    <!-- Breadcrumb -->
    <div class="bg-light border-bottom py-2 small no-print">
        <div class="container d-flex align-items-center gap-2">
            <a href="hindi/" class="text-decoration-none text-muted"><i class="bi bi-house-door me-1"></i>होम</a>
            <span class="text-muted">/</span>
            <a href="hindi/bus" class="text-decoration-none text-muted"><i class="bi bi-bus-front me-1"></i>बस निर्देशिका</a>
            <span class="text-muted">/</span>
            <span class="text-dark fw-bold"><?php echo htmlspecialchars($outwardTitle . ' (' . $vehicle['registration_number'] . ')'); ?></span>
        </div>
    </div>

    <!-- Detail Hero -->
    <section class="bus-detail-hero py-4 py-md-5">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <span class="<?php echo $isDown ? 'badge-route-lg-alt' : 'badge-route-lg'; ?> shadow-sm">
                        <i class="bi bi-signpost-split-fill me-1"></i>रूट संख्या: <?php echo htmlspecialchars($vehicle['route_no'] ?? 'N/A'); ?>
                    </span>
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill font-monospace fs-6 shadow-sm">
                        <i class="bi bi-truck-front-fill me-1"></i><?php echo htmlspecialchars($vehicle['registration_number']); ?>
                    </span>
                    <span class="badge bg-white text-secondary rounded-pill px-3 py-2">
                        आवेदन क्र० #<?php echo $vehicle['application_no']; ?>
                    </span>
                </div>
                <div class="d-flex gap-2 no-print">
                    <button type="button" class="btn btn-outline-light rounded-pill px-3 py-1.5 fw-semibold btn-sm" onclick="window.print();">
                        <i class="bi bi-printer-fill me-1"></i>प्रिंट समय-सारणी
                    </button>
                    <a href="https://wa.me/?text=<?php echo urlencode($outwardTitle . ' (' . $returnTitle . ') - ' . $vehicle['registration_number'] . ' समय-सारणी: ' . $canonical_url); ?>" 
                       target="_blank" rel="noopener" 
                       class="btn btn-success rounded-pill px-3 py-1.5 fw-semibold btn-sm">
                        <i class="bi bi-whatsapp me-1"></i>शेयर करें
                    </a>
                </div>
            </div>

            <!-- Route Heading -->
            <div class="mb-3">
                <h1 class="display-6 fw-bold font-heading text-white mb-2">
                    <?php echo htmlspecialchars($outwardTitle); ?>
                </h1>
                <div class="h5 text-warning fw-semibold mb-2 d-flex align-items-center gap-2 flex-wrap">
                    <span><i class="bi bi-arrow-repeat me-1"></i><?php echo htmlspecialchars($returnTitle); ?></span>
                    <span class="text-white-50 fs-6 fw-normal">•</span>
                    <span class="text-white-50 fs-6 fw-normal"><?php echo htmlspecialchars($outwardEn); ?> (<?php echo htmlspecialchars($returnEn); ?>)</span>
                </div>
                <p class="text-white-50 lead mb-0" style="max-width: 820px; font-size: 1.02rem;">
                    <?php echo htmlspecialchars($vehicle['route_description'] ?? ($vehicle['route_name'] . ' मार्ग पर दैनिक बस सेवा।')); ?>
                </p>
            </div>

            <!-- Via Chips -->
            <?php if (!empty($viaList)): ?>
            <div class="d-flex align-items-center flex-wrap gap-1.5 pt-2">
                <span class="text-warning small fw-bold me-1"><i class="bi bi-diagram-3-fill me-1"></i>भाया (Via):</span>
                <?php foreach ($viaList as $vStop): 
                    $vEn = getBusPlaceEnglish($vStop);
                ?>
                    <span class="badge bg-white text-dark rounded-pill px-2.5 py-1.5 fw-semibold fs-7 shadow-xs">
                        <?php echo htmlspecialchars($vStop); ?> <?php if ($vEn !== $vStop): ?><span class="text-muted fw-normal">(<?php echo htmlspecialchars($vEn); ?>)</span><?php endif; ?>
                    </span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Main Content Container -->
    <div class="container py-4 py-md-5">
        <div class="row g-4">
            
            <!-- Left Column: Timetable Breakdown (Outward & Return Legs) -->
            <div class="col-lg-8">
                <!-- Journey Legs Header -->
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <h3 class="fw-bold font-heading text-dark mb-0 fs-4">
                        <i class="bi bi-clock-history text-primary me-2"></i>दैनिक यात्रा समय-सारणी
                    </h3>
                    <div class="d-flex gap-2 flex-wrap">
                        <?php foreach ($legs as $idx => $leg): ?>
                            <a href="#leg_<?php echo $idx; ?>" class="badge <?php echo $leg['type'] === 'RETURN' ? 'bg-warning text-dark' : 'bg-primary text-white'; ?> text-decoration-none rounded-pill px-3 py-1.5 small shadow-xs">
                                <i class="bi <?php echo $leg['type'] === 'RETURN' ? 'bi-arrow-left me-1' : 'bi-arrow-right me-1'; ?>"></i><?php echo htmlspecialchars($leg['title']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if (empty($legs)): ?>
                    <div class="card border-0 shadow-sm p-4 text-center rounded-4 text-muted">
                        समय सारणी उपलब्ध नहीं है।
                    </div>
                <?php else: ?>
                    <?php foreach ($legs as $idx => $leg): 
                        $firstStop = $leg['stops'][0] ?? null;
                        $lastStop = end($leg['stops']);
                        $isReturnLeg = ($leg['type'] === 'RETURN');
                        $depTimeStr = $firstStop['dep_ampm'] ?? '';
                        $arrTimeStr = $lastStop['arr_ampm'] ?? '';
                    ?>
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 <?php echo $isReturnLeg ? 'card-leg-return' : 'card-leg-outward'; ?>" id="leg_<?php echo $idx; ?>">
                        <div class="card-header text-white py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge <?php echo $isReturnLeg ? 'bg-dark text-warning' : 'bg-warning text-dark'; ?> fw-bold px-2.5 py-1.5 rounded-3">
                                    <i class="bi <?php echo $isReturnLeg ? 'bi-arrow-left-circle-fill' : 'bi-arrow-right-circle-fill'; ?> me-1"></i>
                                    <?php echo htmlspecialchars($leg['badge']); ?>
                                </span>
                                <span class="fw-bold fs-5">
                                    <?php echo htmlspecialchars($leg['title']); ?>
                                </span>
                            </div>
                            <div class="small text-white-50">
                                प्रस्थान: <strong class="text-white"><?php echo $depTimeStr ?: '–'; ?></strong> 
                                <span class="mx-1">•</span>
                                आगमन: <strong class="text-white"><?php echo $arrTimeStr ?: '–'; ?></strong>
                            </div>
                        </div>

                        <!-- Table View of this Leg -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 bg-white">
                                <thead class="table-light">
                                    <tr class="small text-muted text-uppercase">
                                        <th class="ps-4" style="width: 50px;">क्र०</th>
                                        <th>पड़ाव स्थल (Stop Point)</th>
                                        <th>आगमन समय (Arrival)</th>
                                        <th>प्रस्थान समय (Departure)</th>
                                        <th>दूरी (Distance)</th>
                                        <th>समय / गति</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($leg['stops'] as $sIdx => $s): 
                                        $isOrigin = empty($s['arrival_time_formatted']);
                                        $isDest = empty($s['departure_time_formatted']);
                                        $arrFormatted = $s['arr_ampm'] ?? '';
                                        $depFormatted = $s['dep_ampm'] ?? '';
                                    ?>
                                    <tr class="<?php echo $isOrigin ? 'bg-success-subtle bg-opacity-25' : ($isDest ? 'bg-danger-subtle bg-opacity-25' : ''); ?>">
                                        <td class="ps-4 fw-bold text-muted"><?php echo ($sIdx + 1); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if ($isOrigin): ?>
                                                    <span class="badge bg-success rounded-circle p-1"><i class="bi bi-geo-alt-fill text-white fs-8"></i></span>
                                                <?php elseif ($isDest): ?>
                                                    <span class="badge bg-danger rounded-circle p-1"><i class="bi bi-geo-alt-fill text-white fs-8"></i></span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary-subtle text-primary rounded-circle p-1"><i class="bi bi-dot fs-6"></i></span>
                                                <?php endif; ?>
                                                <div>
                                                    <span class="fw-bold text-dark fs-6"><?php echo htmlspecialchars($s['stop_name_hi']); ?></span>
                                                    <?php if ($s['stop_name_en'] !== $s['stop_name_hi']): ?>
                                                        <span class="text-muted small ms-1">(<?php echo htmlspecialchars($s['stop_name_en']); ?>)</span>
                                                    <?php endif; ?>
                                                    <?php if ($isOrigin): ?>
                                                        <span class="badge bg-success text-white ms-1 fs-9">प्रारंभिक स्थल</span>
                                                    <?php elseif ($isDest): ?>
                                                        <span class="badge bg-danger text-white ms-1 fs-9">अंतिम गंतव्य</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($arrFormatted)): ?>
                                                <span class="badge bg-light text-dark border px-2.5 py-1.5 font-monospace fw-semibold fs-7">
                                                    <i class="bi bi-clock me-1 text-muted"></i><?php echo htmlspecialchars($arrFormatted); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted small">–</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($depFormatted)): ?>
                                                <span class="badge bg-success text-white border border-success px-2.5 py-1.5 font-monospace fw-bold fs-7 shadow-xs">
                                                    <i class="bi bi-clock-fill me-1"></i><?php echo htmlspecialchars($depFormatted); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted small">–</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo $s['distance_km'] > 0 ? floatval($s['distance_km']) . ' कि०मी०' : '–'; ?>
                                        </td>
                                        <td>
                                            <?php 
                                                $dur = $s['travel_time'] ?? '';
                                                $spd = $s['speed_kmph'] ?? '';
                                                echo htmlspecialchars(trim("$dur $spd") ?: '–'); 
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="mt-4 p-3 bg-light rounded-4 border small text-muted">
                    <i class="bi bi-info-circle-fill text-primary me-1"></i>
                    <strong>नोट:</strong> उपरोक्त समय-सारणी क्षेत्रीय परिवहन प्राधिकार (RTA), सारण प्रमंडल, छपरा द्वारा 09.09.2026 को प्रकाशित स्टेज कैरिज परमिट आवेदन के आधार पर तैयार की गई है। सड़क व यातायात की स्थिति के अनुसार समय में आंशिक परिवर्तन संभव है।
                </div>
            </div>

            <!-- Right Column: Meta Info Card & Related Buses -->
            <div class="col-lg-4">
                <!-- Vehicle & Applicant Master Details -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold font-heading text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-card-checklist text-primary"></i>वाहन व स्वामी विवरण
                    </h5>

                    <!-- Applicant Name & Address -->
                    <div class="mb-3">
                        <small class="text-muted text-uppercase fw-bold fs-8">आवेदक / बस स्वामी</small>
                        <div class="fw-bold text-dark fs-6 mt-0.5"><?php echo htmlspecialchars($vehicle['applicant_name']); ?></div>
                        <?php if (!empty($vehicle['father_husband_name'])): ?>
                            <div class="small text-muted">पिता/पति: <?php echo htmlspecialchars($vehicle['father_husband_name']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($vehicle['address'])): ?>
                            <div class="small text-secondary mt-1"><i class="bi bi-geo-alt me-1 text-danger"></i><?php echo htmlspecialchars($vehicle['address']); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Specs Grid -->
                    <div class="row g-2 pt-2 border-top">
                        <div class="col-6">
                            <div class="stat-box text-center">
                                <small class="text-muted d-block fs-8">वाहन संख्या</small>
                                <strong class="text-primary font-monospace"><?php echo htmlspecialchars($vehicle['registration_number']); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box text-center">
                                <small class="text-muted d-block fs-8">दैनिक खेप</small>
                                <strong class="text-success"><?php echo htmlspecialchars($vehicle['trips_per_day'] ?? '–'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box text-center">
                                <small class="text-muted d-block fs-8">सीट क्षमता</small>
                                <strong class="text-dark"><?php echo htmlspecialchars($vehicle['seating_capacity'] ?? 'N/A'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box text-center">
                                <small class="text-muted d-block fs-8">मॉडल वर्ष</small>
                                <strong class="text-dark"><?php echo htmlspecialchars($vehicle['model_year'] ?? 'N/A'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box text-center">
                                <small class="text-muted d-block fs-8">कुल दूरी</small>
                                <strong class="text-dark"><?php echo floatval($vehicle['total_distance_km']); ?> किमी</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box text-center">
                                <small class="text-muted d-block fs-8">स्वीकृत गति</small>
                                <strong class="text-dark fs-8"><?php echo htmlspecialchars($vehicle['speed_range'] ?? '20-60 किमी/घंटा'); ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 mt-3 border-top small text-muted">
                        <div><strong>प्राधिकार:</strong> <?php echo htmlspecialchars($vehicle['authority']); ?></div>
                        <div class="mt-1"><strong>सूचना तिथि:</strong> <?php echo htmlspecialchars($vehicle['notice_date']); ?></div>
                    </div>
                </div>

                <!-- Related Buses -->
                <?php if (!empty($relatedVehicles)): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white no-print">
                    <h5 class="fw-bold font-heading text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-arrows-angle-expand text-success"></i>इस मार्ग पर अन्य बसें
                    </h5>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($relatedVehicles as $rel): ?>
                            <a href="hindi/bus/<?php echo urlencode($rel['registration_number']); ?>" 
                               class="text-decoration-none text-dark p-2.5 rounded-3 border bg-light d-flex align-items-center justify-content-between hover-shadow transition">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-primary rounded-pill small">रूट <?php echo htmlspecialchars($rel['route_no']); ?></span>
                                        <span class="font-monospace fw-bold small text-primary"><?php echo htmlspecialchars($rel['registration_number']); ?></span>
                                    </div>
                                    <div class="small fw-semibold text-secondary">
                                        <?php echo htmlspecialchars($rel['origin']); ?> से <?php echo htmlspecialchars($rel['destination']); ?> बस
                                    </div>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Back to All Buses Button -->
                <div class="no-print">
                    <a href="hindi/bus" class="btn btn-outline-primary rounded-pill w-100 py-2.5 fw-bold shadow-sm">
                        <i class="bi bi-arrow-left me-1"></i>सभी 30 बस सेवाएं देखें
                    </a>
                </div>

            </div>

        </div>
    </div>

    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// -------------------------------------------------------------
// CASE B: DIRECTORY LISTING IN HINDI (/hindi/bus)
// -------------------------------------------------------------
$page_title = "सारण प्रमंडल बस सेवा एवं समय-सारणी – छपरा, सिवान, गोपालगंज बस टाइम टेबल | सारण इंडेक्स";
$meta_description = "सारण प्रमंडल (छपरा, सिवान, गोपालगंज) बस सेवा एवं समय-सारणी। क्षेत्रीय परिवहन प्राधिकार (RTA) सारण द्वारा स्वीकृत रूट, वाहन नंबर, प्रस्थान व आगमन समय की पूरी सूची।";
$meta_keywords = "छपरा बस समय सारणी, सिवान बस समय सारणी, गोपालगंज बस रूट, RTA सारण छपरा, बिहार बस टाइम टेबल";

// Fetch all vehicles
$vehicles = [];
$uniqueStops = [];

if ($db) {
    try {
        $stmt = $db->query("
            SELECT v.*, 
                   COUNT(t.id) AS total_stops,
                   MIN(CASE WHEN t.stop_order = 1 THEN t.departure_time_formatted END) AS first_dep,
                   MAX(CASE WHEN t.departure_time_formatted IS NULL OR t.departure_time_formatted = '' THEN t.arrival_time_formatted END) AS last_arr
            FROM vehicle v
            LEFT JOIN vehicle_timetable t ON v.id = t.vehicle_id
            GROUP BY v.id
            ORDER BY v.application_no ASC
        ");
        $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch distinct stop names for filters
        $stopStmt = $db->query("
            SELECT DISTINCT stop_name 
            FROM vehicle_timetable 
            WHERE stop_name IS NOT NULL AND stop_name != '' 
            ORDER BY stop_name ASC
        ");
        $uniqueStops = $stopStmt->fetchAll(PDO::FETCH_COLUMN);

        // Grouped timetables
        $ttStmt = $db->query("
            SELECT * FROM vehicle_timetable 
            ORDER BY vehicle_id ASC, trip_number ASC, stop_order ASC
        ");
        $allTimetables = [];
        while ($row = $ttStmt->fetch(PDO::FETCH_ASSOC)) {
            $allTimetables[$row['vehicle_id']][] = $row;
        }
    } catch (PDOException $e) {
        error_log("Hindi Bus Page Query Error: " . $e->getMessage());
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<style>
/* Custom styling for Bus Directory */
.bus-hero {
    background: linear-gradient(135deg, #0d3b66 0%, #001e3d 50%, #0f172a 100%);
    color: #fff;
    position: relative;
    overflow: hidden;
}
.bus-hero::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.15), transparent 40%),
                radial-gradient(circle at 10% 80%, rgba(59, 130, 246, 0.15), transparent 40%);
    pointer-events: none;
}
.bus-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    border: 1px solid rgba(0, 0, 0, 0.08);
}
.bus-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.09) !important;
    border-color: #3b82f6;
}
.route-badge {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 0.35rem 0.75rem;
    border-radius: 50rem;
}
.route-badge-alt {
    background: linear-gradient(135deg, #d97706, #b45309);
    color: #fff;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 0.35rem 0.75rem;
    border-radius: 50rem;
}
.stat-pill {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 1rem;
    padding: 0.75rem 1.25rem;
}
.stop-chip {
    font-size: 0.78rem;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    padding: 0.2rem 0.6rem;
    border-radius: 50rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}
.stop-chip:hover {
    background: #e2e8f0;
}
.filter-chip-scroll {
    overflow-x: auto;
    white-space: nowrap;
    scrollbar-width: thin;
    padding-bottom: 0.5rem;
}
.filter-chip-scroll::-webkit-scrollbar {
    height: 4px;
}
.filter-chip-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.view-btn.active {
    background: #0d6efd !important;
    color: #fff !important;
    border-color: #0d6efd !important;
}
</style>

<!-- Hero Section -->
<section class="bus-hero py-5">
    <div class="container position-relative">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill shadow-sm">
                        <i class="bi bi-shield-check me-1"></i> RTA स्वीकृत बस सेवाएं
                    </span>
                    <span class="badge bg-white text-dark fw-bold px-3 py-1.5 rounded-pill shadow-sm">
                        <i class="bi bi-calendar-event me-1"></i> नोटिस तिथि: 09.09.2026
                    </span>
                    <span class="badge bg-info-subtle text-info border border-info rounded-pill px-3 py-1.5">
                        क्षेत्रीय परिवहन प्राधिकार, सारण प्रमंडल, छपरा
                    </span>
                </div>

                <h1 class="display-5 fw-bold font-heading mb-2 text-white">
                    सारण प्रमंडल बस सेवा एवं समय-सारणी
                </h1>
                <p class="lead text-white-50 mb-3" style="max-width: 680px;">
                    छपरा, सिवान, गोपालगंज, मोतिहारी, बेतिया एवं ग्रामीण मार्गों पर चलने वाली दैनिक बसों की आधिकारिक रूट-सूची, ठहराव स्थल, प्रस्थान व आगमन समय।
                </p>

                <!-- Search Input Box -->
                <div class="card border-0 shadow-lg rounded-4 p-2 bg-white mt-4" style="max-width: 640px;">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 ps-3 text-primary fs-5">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="busSearchInput" class="form-control border-0 shadow-none py-2 px-2 fs-6" 
                               placeholder="शहर, पड़ाव, बस नंबर (e.g. सिवान, मोतिहारी, BR06, मलमलिया)..." 
                               autocomplete="off">
                        <button class="btn btn-warning text-dark fw-bold px-4 rounded-pill m-1" type="button" id="clearSearchBtn">
                            रीसेट <i class="bi bi-x-circle ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Column -->
            <div class="col-lg-4">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-pill text-center">
                            <div class="fs-3 fw-bold text-warning mb-0"><?php echo count($vehicles); ?></div>
                            <div class="small text-white-50 fw-semibold">पंजीकृत बसें (Buses)</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-pill text-center">
                            <div class="fs-3 fw-bold text-info mb-0">680+</div>
                            <div class="small text-white-50 fw-semibold">समय पड़ाव (Stops)</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-pill text-center">
                            <div class="fs-3 fw-bold text-success mb-0">3+</div>
                            <div class="small text-white-50 fw-semibold">जिले (Districts)</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-pill text-center">
                            <div class="fs-3 fw-bold text-white mb-0">20-60</div>
                            <div class="small text-white-50 fw-semibold">किमी/घंटा औसत गति</div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 p-3 rounded-3" style="background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.1);">
                    <div class="small text-white-50 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill text-warning fs-5 flex-shrink-0"></i>
                        <span>आपत्ति या सुझाव हेतु सम्पर्क: <a href="mailto:rtasaranbihar@gmail.com" class="text-warning text-decoration-none fw-semibold">rtasaranbihar@gmail.com</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Route Quick Filter Chips -->
<section class="bg-light border-bottom py-3">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="small fw-bold text-muted text-uppercase tracking-wider">
                <i class="bi bi-funnel-fill text-primary me-1"></i>लोकप्रिय पड़ाव फ़िल्टर:
            </span>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-primary view-btn active" id="btnCardView" title="कार्ड दृश्य">
                    <i class="bi bi-grid-fill me-1"></i>कार्ड दृश्य
                </button>
                <button type="button" class="btn btn-outline-primary view-btn" id="btnTableView" title="तालिका दृश्य">
                    <i class="bi bi-table me-1"></i>तालिका दृश्य
                </button>
            </div>
        </div>
        <div class="filter-chip-scroll d-flex gap-2">
            <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 filter-btn active" data-filter="all">
                सभी रूट (<?php echo count($vehicles); ?>)
            </button>
            <?php
            $popularChips = ['छपरा', 'सिवान', 'गोपालगंज', 'मोतिहारी', 'बेतिया', 'मशरक', 'मढ़ौरा', 'बसंतपुर', 'डेरनी', 'मलमलिया'];
            foreach ($popularChips as $chip):
            ?>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 filter-btn" data-filter="<?php echo htmlspecialchars($chip); ?>">
                <?php echo htmlspecialchars($chip); ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Main Bus Directory Listing -->
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold font-heading mb-1 text-dark">स्वीकृत स्टेज कैरिज बस अनुसूची</h3>
            <p class="text-muted small mb-0">सारण प्रमंडल आरटीए द्वारा 09.09.2026 को प्रकाशित स्टेज कैरिज परमिट आवेदन सूची</p>
        </div>
        <div class="text-muted small fw-semibold" id="resultCountLabel">
            दिखाया जा रहा है: <?php echo count($vehicles); ?> बसें
        </div>
    </div>

    <!-- Cards Grid View -->
    <div class="row g-4" id="busCardsGrid">
        <?php foreach ($vehicles as $v): 
            $vId = $v['id'];
            $tt = $allTimetables[$vId] ?? [];
            $viaList = !empty($v['via']) ? array_filter(array_map('trim', explode(',', $v['via']))) : [];
            $isDown = stripos($v['route_no'] ?? '', 'डाउन') !== false || stripos($v['route_no'] ?? '', 'down') !== false || stripos($v['route_no'] ?? '', 'DN') !== false;
            $detailUrl = "hindi/bus/" . urlencode($v['registration_number']);
            
            $cardOrigEn = getBusPlaceEnglish($v['origin']);
            $cardDestEn = getBusPlaceEnglish($v['destination']);
            $cardOutward = "{$v['origin']} से {$v['destination']} बस";
            $cardReturn = "वापसी {$v['destination']} से {$v['origin']}";
            $cardOutwardEn = "{$cardOrigEn} to {$cardDestEn} Bus";
            $cardReturnEn = "Return {$cardDestEn} to {$cardOrigEn}";

            // Search corpus string for lightning client-side search
            $searchCorpus = strtolower(
                ($v['application_no'] ?? '') . ' ' .
                ($v['applicant_name'] ?? '') . ' ' .
                ($v['father_husband_name'] ?? '') . ' ' .
                ($v['address'] ?? '') . ' ' .
                ($v['registration_number'] ?? '') . ' ' .
                ($v['route_no'] ?? '') . ' ' .
                ($v['route_name'] ?? '') . ' ' .
                ($v['origin'] ?? '') . ' ' .
                ($v['destination'] ?? '') . ' ' .
                ($v['via'] ?? '') . ' ' .
                $cardOutward . ' ' .
                $cardReturn . ' ' .
                $cardOutwardEn . ' ' .
                $cardReturnEn
            );
        ?>
        <div class="col-lg-6 bus-item-col" data-search="<?php echo htmlspecialchars($searchCorpus); ?>">
            <div class="card bus-card rounded-4 shadow-sm h-100 bg-white overflow-hidden">
                <!-- Card Header -->
                <div class="card-header bg-light border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span class="<?php echo $isDown ? 'route-badge-alt' : 'route-badge'; ?> shadow-sm">
                            <i class="bi bi-signpost-split-fill me-1"></i>रूट <?php echo htmlspecialchars($v['route_no'] ?? 'N/A'); ?>
                        </span>
                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill">
                            आवेदन #<?php echo $v['application_no']; ?>
                        </span>
                    </div>
                    <div class="text-end">
                        <a href="<?php echo $detailUrl; ?>" class="badge bg-primary-subtle text-primary fw-bold font-monospace px-2.5 py-1.5 rounded-3 fs-7 text-decoration-none">
                            <i class="bi bi-truck-front-fill me-1"></i><?php echo htmlspecialchars($v['registration_number']); ?>
                        </a>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4">
                    <!-- Route Title -->
                    <div class="mb-3">
                        <h5 class="fw-bold text-dark font-heading mb-1">
                            <a href="<?php echo $detailUrl; ?>" class="text-decoration-none text-dark hover-primary d-inline-flex align-items-center gap-2 flex-wrap">
                                <span class="text-primary"><i class="bi bi-bus-front-fill me-1"></i><?php echo htmlspecialchars($cardOutward); ?></span>
                            </a>
                        </h5>
                        <div class="small text-muted d-flex align-items-center gap-2 flex-wrap mb-1">
                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2.5 py-0.5">
                                <i class="bi bi-arrow-repeat me-1 text-warning"></i><?php echo htmlspecialchars($cardReturn); ?>
                            </span>
                            <span class="text-secondary small">(<?php echo htmlspecialchars($cardOutwardEn); ?>)</span>
                        </div>
                        <?php if (!empty($v['route_description'])): ?>
                            <p class="text-muted small mb-0 line-clamp-2" style="font-size: 0.85rem; line-height: 1.5;">
                                <?php echo htmlspecialchars($v['route_description']); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Via Stops Chips -->
                    <?php if (!empty($viaList)): ?>
                    <div class="mb-3 p-2.5 bg-light rounded-3">
                        <div class="small fw-bold text-secondary mb-1">
                            <i class="bi bi-diagram-3 me-1 text-primary"></i>भाया (Via Points):
                        </div>
                        <div class="d-flex flex-wrap gap-1">
                            <?php foreach ($viaList as $stop): ?>
                                <span class="stop-chip shadow-xs"><i class="bi bi-pin-map text-primary me-0.5"></i><?php echo htmlspecialchars($stop); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Vehicle & Applicant Meta Grid -->
                    <div class="row g-2 small text-muted mb-3">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-1.5">
                                <i class="bi bi-person-circle text-primary"></i>
                                <span class="fw-semibold text-dark"><?php echo htmlspecialchars($v['applicant_name']); ?></span>
                            </div>
                            <?php if (!empty($v['father_husband_name'])): ?>
                                <div class="text-muted ps-3 fs-8">पिता/पति: <?php echo htmlspecialchars($v['father_husband_name']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-1.5">
                                <i class="bi bi-speedometer2 text-danger"></i>
                                <span>दैनिक खेप: <strong><?php echo htmlspecialchars($v['trips_per_day'] ?? '–'); ?></strong></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-1.5">
                                <i class="bi bi-people text-info"></i>
                                <span>क्षमता: <strong><?php echo htmlspecialchars($v['seating_capacity'] ?? 'N/A'); ?></strong></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-1.5">
                                <i class="bi bi-calendar3 text-secondary"></i>
                                <span>मॉडल: <strong><?php echo htmlspecialchars($v['model_year'] ?? 'N/A'); ?></strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Timetable Accordion / Preview -->
                    <?php if (!empty($tt)): ?>
                    <div class="accordion accordion-flush border rounded-3 overflow-hidden mb-3" id="ttAccordion<?php echo $vId; ?>">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?php echo $vId; ?>">
                                <button class="accordion-button collapsed py-2 px-3 bg-light text-primary small fw-semibold" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapse<?php echo $vId; ?>" 
                                        aria-expanded="false" 
                                        aria-controls="collapse<?php echo $vId; ?>">
                                    <i class="bi bi-clock-history me-1.5"></i>
                                    समय सारणी देखें (कुल <?php echo count($tt); ?> पड़ाव)
                                </button>
                            </h2>
                            <div id="collapse<?php echo $vId; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $vId; ?>">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive" style="max-height: 240px; overflow-y: auto;">
                                        <table class="table table-sm table-striped table-hover mb-0 font-monospace fs-8">
                                            <thead class="table-dark sticky-top">
                                                <tr>
                                                    <th>पड़ाव (Stop)</th>
                                                    <th>आगमन (Arr)</th>
                                                    <th>प्रस्थान (Dep)</th>
                                                    <th>दूरी</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($tt as $tRow): ?>
                                                <tr>
                                                    <td class="fw-semibold text-dark font-sans"><?php echo htmlspecialchars($tRow['stop_name']); ?></td>
                                                    <td><?php echo !empty($tRow['arrival_time_formatted']) ? htmlspecialchars($tRow['arrival_time_formatted']) : '–'; ?></td>
                                                    <td class="text-success fw-bold"><?php echo !empty($tRow['departure_time_formatted']) ? htmlspecialchars($tRow['departure_time_formatted']) : '–'; ?></td>
                                                    <td class="text-muted"><?php echo $tRow['distance_km'] > 0 ? floatval($tRow['distance_km']) . ' km' : '–'; ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Action Link to Full Detail Page -->
                    <div class="pt-2 border-top d-flex align-items-center justify-content-between">
                        <small class="text-muted">
                            <i class="bi bi-geo-alt text-danger me-1"></i><?php echo floatval($v['total_distance_km']); ?> किमी कुल दूरी
                        </small>
                        <a href="<?php echo $detailUrl; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                            पूरी समय-सारणी देखें <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Table View (Hidden by default, switched via button) -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-none" id="busTableView">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">आवेदन क्र०</th>
                        <th>पंजीकरण संख्या</th>
                        <th>रूट संख्या</th>
                        <th>रूट (प्रारंभ से गंतव्य)</th>
                        <th>आवेदक का नाम</th>
                        <th>प्रारंभिक प्रस्थान</th>
                        <th>अंतिम आगमन</th>
                        <th>पड़ाव</th>
                        <th class="text-end pe-4">विवरण</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicles as $v): 
                        $tt = $allTimetables[$v['id']] ?? [];
                        $detailUrl = "hindi/bus/" . urlencode($v['registration_number']);
                        $isDown = stripos($v['route_no'] ?? '', 'डाउन') !== false || stripos($v['route_no'] ?? '', 'down') !== false || stripos($v['route_no'] ?? '', 'DN') !== false;
                        $firstStop = $tt[0] ?? null;
                        $lastStop = end($tt);
                    ?>
                    <tr class="bus-table-row" data-search="<?php echo htmlspecialchars(strtolower($v['registration_number'] . ' ' . $v['route_no'] . ' ' . $v['origin'] . ' ' . $v['destination'] . ' ' . $v['applicant_name'])); ?>">
                        <td class="ps-4 fw-bold text-muted">#<?php echo $v['application_no']; ?></td>
                        <td>
                            <a href="<?php echo $detailUrl; ?>" class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace px-2.5 py-1.5 fs-7 text-decoration-none">
                                <i class="bi bi-truck-front-fill me-1"></i><?php echo htmlspecialchars($v['registration_number']); ?>
                            </a>
                        </td>
                        <td>
                            <span class="<?php echo $isDown ? 'route-badge-alt' : 'route-badge'; ?> px-2 py-0.5" style="font-size: 0.75rem;">
                                <?php echo htmlspecialchars($v['route_no'] ?? 'N/A'); ?>
                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">
                                <?php echo htmlspecialchars($v['origin']); ?> → <?php echo htmlspecialchars($v['destination']); ?>
                            </div>
                            <?php if (!empty($v['via'])): ?>
                                <small class="text-muted fs-8">भाया: <?php echo htmlspecialchars($v['via']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark small"><?php echo htmlspecialchars($v['applicant_name']); ?></div>
                            <small class="text-muted fs-8"><?php echo htmlspecialchars($v['address'] ?? ''); ?></small>
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success font-monospace">
                                <?php echo htmlspecialchars($firstStop['departure_time_formatted'] ?? '–'); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-danger-subtle text-danger font-monospace">
                                <?php echo htmlspecialchars($lastStop['arrival_time_formatted'] ?? '–'); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border"><?php echo count($tt); ?> पड़ाव</span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="<?php echo $detailUrl; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                विवरण <i class="bi bi-chevron-right"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- No Results Placeholder -->
    <div id="noResultsState" class="d-none text-center py-5">
        <div class="py-5">
            <div class="text-muted fs-1 mb-3"><i class="bi bi-bus-front"></i></div>
            <h4 class="fw-bold text-dark">कोई बस सेवा नहीं मिली</h4>
            <p class="text-muted mb-3">आपके खोजे गए शब्द या फ़िल्टर के लिए कोई बस रिकॉर्ड उपलब्ध नहीं है।</p>
            <button class="btn btn-primary rounded-pill px-4" id="resetFromEmptyBtn">
                सभी बसें देखें
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('busSearchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    const resetFromEmptyBtn = document.getElementById('resetFromEmptyBtn');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.bus-item-col');
    const tableRows = document.querySelectorAll('.bus-table-row');
    const resultCountLabel = document.getElementById('resultCountLabel');
    const noResultsState = document.getElementById('noResultsState');
    const cardsGrid = document.getElementById('busCardsGrid');
    const tableView = document.getElementById('busTableView');
    const btnCardView = document.getElementById('btnCardView');
    const btnTableView = document.getElementById('btnTableView');

    let currentFilter = 'all';

    function filterAndSearch() {
        const query = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach((card, index) => {
            const dataSearch = card.getAttribute('data-search') || '';
            const matchesQuery = !query || dataSearch.includes(query);
            const matchesFilter = (currentFilter === 'all') || dataSearch.includes(currentFilter.toLowerCase());

            const isVisible = matchesQuery && matchesFilter;
            card.style.display = isVisible ? '' : 'none';

            if (tableRows[index]) {
                tableRows[index].style.display = isVisible ? '' : 'none';
            }

            if (isVisible) visibleCount++;
        });

        resultCountLabel.textContent = `दिखाया जा रहा है: ${visibleCount} बसें`;
        noResultsState.classList.toggle('d-none', visibleCount > 0);
    }

    searchInput.addEventListener('input', filterAndSearch);

    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        currentFilter = 'all';
        filterBtns.forEach(btn => btn.classList.remove('active', 'btn-dark'));
        filterBtns.forEach(btn => btn.classList.add('btn-outline-secondary'));
        document.querySelector('.filter-btn[data-filter="all"]').classList.add('active', 'btn-dark');
        document.querySelector('.filter-btn[data-filter="all"]').classList.remove('btn-outline-secondary');
        filterAndSearch();
    });

    if (resetFromEmptyBtn) {
        resetFromEmptyBtn.addEventListener('click', function() {
            clearBtn.click();
        });
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => {
                b.classList.remove('active', 'btn-dark');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.add('active', 'btn-dark');
            this.classList.remove('btn-outline-secondary');
            currentFilter = this.getAttribute('data-filter');
            filterAndSearch();
        });
    });

    // View Switching
    btnCardView.addEventListener('click', function() {
        btnCardView.classList.add('active');
        btnTableView.classList.remove('active');
        cardsGrid.classList.remove('d-none');
        tableView.classList.add('d-none');
    });

    btnTableView.addEventListener('click', function() {
        btnTableView.classList.add('active');
        btnCardView.classList.remove('active');
        cardsGrid.classList.add('d-none');
        tableView.classList.remove('d-none');
    });
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

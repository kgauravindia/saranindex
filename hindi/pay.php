<?php
require_once __DIR__ . '/../includes/functions.php';

$listingId = intval($_GET['listing_id'] ?? 0);
$planParam = strtoupper(trim($_GET['plan'] ?? ''));

$db = getDB();
$listing = null;
if ($db && $listingId > 0) {
    $stmt = $db->prepare("SELECT l.*, c.name as category_name, b.name as block_name FROM listings l LEFT JOIN categories c ON l.category_id = c.id LEFT JOIN blocks b ON l.block_id = b.id WHERE l.id = :id LIMIT 1");
    $stmt->execute(['id' => $listingId]);
    $listing = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$listing) {
    header("Location: pricing.php");
    exit;
}

$selectedPlan = in_array($planParam, ['GOLD', 'PLATINUM']) ? $planParam : (in_array($listing['plan_type'], ['GOLD', 'PLATINUM']) ? $listing['plan_type'] : 'GOLD');
$planPrice = ($selectedPlan === 'PLATINUM') ? 1499 : 499;

$page_title = "सुरक्षित भुगतान – " . htmlspecialchars($listing['title']) . " | सारण इंडेक्स";
$meta_description = "सारण इंडेक्स बिज़नेस डायरेक्टरी मेंबरशिप के लिए सुरक्षित रेजरपे ऑनलाइन भुगतान।";

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-light py-5">
    <div class="container">
        
        <!-- Header Lock Bar -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center gap-2 bg-white px-3 py-1.5 rounded-pill shadow-xs border text-success fw-bold small mb-2">
                <i class="bi bi-shield-lock-fill"></i> 256-बिट एसएसएल सुरक्षित चेकआउट
            </div>
            <h2 class="fw-bold font-heading text-dark mb-1">अपने बिज़नेस प्लान का भुगतान पूरा करें</h2>
            <p class="text-muted small">सारण जिले भर के हजारों ग्राहकों तक पहुंचें और अपनी बिक्री बढ़ाएं।</p>
        </div>

        <div class="row g-4 justify-content-center">
            
            <!-- Left Column: Plan Benefits & Selector (7 Cols) -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    
                    <!-- Listing Info Badge -->
                    <div class="p-3 bg-light rounded-3 border mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 extra-small mb-1"><?php echo htmlspecialchars($listing['category_name'] ?? 'डायरेक्टरी लिस्टिंग'); ?></span>
                            <h5 class="fw-bold text-dark mb-0 font-heading"><?php echo htmlspecialchars($listing['title']); ?></h5>
                            <small class="text-muted"><i class="bi bi-geo-alt me-1 text-danger"></i><?php echo htmlspecialchars($listing['block_name'] ?? 'सारण जिला'); ?> • +91 <?php echo htmlspecialchars($listing['mobile']); ?></small>
                        </div>
                        <a href="../<?php echo getListingUrl($listing['slug']); ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="bi bi-box-arrow-up-right me-1"></i>प्रिव्यू देखें
                        </a>
                    </div>

                    <h5 class="fw-bold text-dark font-heading mb-3">मेंबरशिप प्लान चुनें</h5>

                    <!-- Plan Selection Radio Cards -->
                    <div class="row g-3 mb-4">
                        
                        <!-- Gold Plan Option -->
                        <div class="col-md-6">
                            <label class="plan-option-card border rounded-4 p-3.5 d-block position-relative cursor-pointer h-100 <?php echo $selectedPlan === 'GOLD' ? 'active-plan border-primary bg-primary-subtle bg-opacity-10' : ''; ?>" for="plan_gold" style="cursor: pointer;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="plan_type_select" id="plan_gold" value="GOLD" <?php echo $selectedPlan === 'GOLD' ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-bold text-dark" for="plan_gold">
                                            गोल्ड बिजनेस
                                        </label>
                                    </div>
                                    <span class="badge bg-primary rounded-pill px-2 py-1 extra-small">लोकप्रिय</span>
                                </div>
                                <div class="h3 fw-bold text-primary mb-1 font-heading">₹499 <small class="fs-6 text-muted fw-normal">/वर्ष</small></div>
                                <ul class="list-unstyled extra-small text-secondary mb-0 d-flex flex-column gap-1.5">
                                    <li><i class="bi bi-check-circle-fill text-success me-1.5"></i>सत्यापित व्यवसाय बैज</li>
                                    <li><i class="bi bi-check-circle-fill text-success me-1.5"></i>सीधा व्हाट्सएप लीड बटन</li>
                                    <li><i class="bi bi-check-circle-fill text-success me-1.5"></i>कैटेगरी खोज में प्राथमिकता</li>
                                    <li><i class="bi bi-check-circle-fill text-success me-1.5"></i>आधिकारिक जीएसटी टैक्स रसीद</li>
                                </ul>
                            </label>
                        </div>

                        <!-- Platinum Plan Option -->
                        <div class="col-md-6">
                            <label class="plan-option-card border rounded-4 p-3.5 d-block position-relative cursor-pointer h-100 <?php echo $selectedPlan === 'PLATINUM' ? 'active-plan border-warning bg-warning-subtle bg-opacity-25' : ''; ?>" for="plan_platinum" style="cursor: pointer;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="plan_type_select" id="plan_platinum" value="PLATINUM" <?php echo $selectedPlan === 'PLATINUM' ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-bold text-dark" for="plan_platinum">
                                            वीआईपी प्लैटिनम
                                        </label>
                                    </div>
                                    <span class="badge bg-warning text-dark fw-bold rounded-pill px-2 py-1 extra-small"><i class="bi bi-crown-fill text-danger me-1"></i>सर्वश्रेष्ठ</span>
                                </div>
                                <div class="h3 fw-bold text-danger mb-1 font-heading">₹1,499 <small class="fs-6 text-muted fw-normal">/वर्ष</small></div>
                                <ul class="list-unstyled extra-small text-secondary mb-0 d-flex flex-column gap-1.5">
                                    <li><i class="bi bi-check-circle-fill text-success me-1.5"></i><strong>होमपेज एवं टॉप रैंकिंग प्लेसमेंट</strong></li>
                                    <li><i class="bi bi-check-circle-fill text-success me-1.5"></i>वीआईपी प्लैटिनम क्राउन बैज</li>
                                    <li><i class="bi bi-check-circle-fill text-success me-1.5"></i>प्रोफ़ाइल पर कोई विज्ञापन नहीं</li>
                                    <li><i class="bi bi-check-circle-fill text-success me-1.5"></i>समर्पित प्राथमिकता सहायता</li>
                                </ul>
                            </label>
                        </div>

                    </div>

                    <!-- Payment Security Assurances -->
                    <div class="d-flex align-items-center justify-content-around p-3 bg-light rounded-3 text-muted extra-small flex-wrap gap-2 text-center">
                        <span><i class="bi bi-shield-check text-success fs-6 me-1"></i>सुरक्षित गेटवे</span>
                        <span><i class="bi bi-arrow-repeat text-primary fs-6 me-1"></i>तत्काल 1 वर्ष सक्रियता</span>
                        <span><i class="bi bi-receipt text-warning fs-6 me-1"></i>डाउनलोड करने योग्य टैक्स रसीद</span>
                    </div>

                </div>
            </div>

            <!-- Right Column: Order Summary & Pay Button (5 Cols) -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 2rem;">
                    
                    <h5 class="fw-bold text-dark font-heading mb-3 border-bottom pb-2">
                        <i class="bi bi-receipt-cutoff text-primary me-2"></i>ऑर्डर सारांश (Summary)
                    </h5>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">व्यवसाय का नाम</span>
                        <span class="fw-bold text-dark small text-truncate" style="max-width: 180px;"><?php echo htmlspecialchars($listing['title']); ?></span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">चुना गया प्लान</span>
                        <span class="badge bg-primary text-white rounded-pill px-2.5 py-1" id="summaryPlanName"><?php echo $selectedPlan === 'PLATINUM' ? 'वीआईपी प्लैटिनम' : 'गोल्ड बिजनेस'; ?></span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">वैधता अवधि</span>
                        <span class="text-dark fw-semibold small">1 पूर्ण वर्ष (365 दिन)</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">शुल्क एवं कर</span>
                        <span class="text-success small fw-semibold">जीएसटी (GST) सहित</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="h6 fw-bold text-dark mb-0">कुल देय राशि</span>
                        <span class="h3 fw-bold text-primary mb-0 font-heading" id="summaryAmountText">₹<?php echo number_format($planPrice); ?></span>
                    </div>

                    <!-- Payment Options Logos -->
                    <div class="mb-4 text-center">
                        <small class="text-muted extra-small d-block mb-2">स्वीकृत भुगतान विकल्प</small>
                        <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap text-muted small">
                            <span class="badge bg-light text-dark border"><i class="bi bi-qr-code me-1 text-primary"></i>UPI / GPay / PhonePe</span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-credit-card me-1 text-success"></i>डेबिट / क्रेडिट कार्ड</span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-bank me-1 text-info"></i>नेटबैंकिंग</span>
                        </div>
                    </div>

                    <!-- Pay Now Button -->
                    <button type="button" id="payNowBtn" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm py-3 mb-2.5 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-lock-fill"></i> रेजरपे द्वारा <span id="btnAmount">₹<?php echo number_format($planPrice); ?></span> का भुगतान करें
                    </button>

                    <!-- Skip to Free Link -->
                    <div class="text-center mt-2">
                        <a href="<?php echo isUserLoggedIn() ? 'dashboard.php' : ('../' . getListingUrl($listing['slug'])); ?>" class="text-muted extra-small text-decoration-none hover-primary">
                            <i class="bi bi-arrow-left me-1"></i>या मुफ़्त बेसिक प्लान के साथ जारी रखें
                        </a>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPlan = "<?php echo $selectedPlan; ?>";
    let listingId = <?php echo $listing['id']; ?>;

    const planRadios = document.querySelectorAll('input[name="plan_type_select"]');
    const summaryPlanName = document.getElementById('summaryPlanName');
    const summaryAmountText = document.getElementById('summaryAmountText');
    const btnAmount = document.getElementById('btnAmount');
    const payNowBtn = document.getElementById('payNowBtn');

    function updatePricing(plan) {
        currentPlan = plan;
        const isPlat = (plan === 'PLATINUM');
        const price = isPlat ? 1499 : 499;

        summaryPlanName.textContent = isPlat ? 'वीआईपी प्लैटिनम' : 'गोल्ड बिजनेस';
        summaryPlanName.className = 'badge ' + (isPlat ? 'bg-warning text-dark' : 'bg-primary text-white') + ' rounded-pill px-2.5 py-1';
        summaryAmountText.textContent = '₹' + price.toLocaleString();
        btnAmount.textContent = '₹' + price.toLocaleString();

        document.querySelectorAll('.plan-option-card').forEach(c => {
            c.classList.remove('border-primary', 'border-warning', 'bg-primary-subtle', 'bg-warning-subtle', 'bg-opacity-10', 'bg-opacity-25');
        });

        const activeCard = document.querySelector(`label[for="plan_${plan.toLowerCase()}"]`);
        if (activeCard) {
            if (isPlat) {
                activeCard.classList.add('border-warning', 'bg-warning-subtle', 'bg-opacity-25');
            } else {
                activeCard.classList.add('border-primary', 'bg-primary-subtle', 'bg-opacity-10');
            }
        }
    }

    planRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            updatePricing(this.value);
        });
    });

    // Handle Razorpay Checkout
    payNowBtn.addEventListener('click', function() {
        payNowBtn.disabled = true;
        payNowBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>सुरक्षित भुगतान शुरू हो रहा है...';

        const formData = new FormData();
        formData.append('action', 'create_order');
        formData.append('listing_id', listingId);
        formData.append('plan_type', currentPlan);

        fetch('../api/process_payment_api.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            payNowBtn.disabled = false;
            payNowBtn.innerHTML = `<i class="bi bi-lock-fill"></i> रेजरपे द्वारा <span id="btnAmount">₹${(currentPlan === 'PLATINUM' ? 1499 : 499).toLocaleString()}</span> का भुगतान करें`;

            if (data.status === 'success' && typeof Razorpay !== 'undefined') {
                const options = {
                    "key": data.key,
                    "amount": data.amount,
                    "currency": data.currency,
                    "name": "Saran Index",
                    "description": (currentPlan === 'PLATINUM' ? 'वीआईपी प्लैटिनम प्लान' : 'गोल्ड बिजनेस प्लान') + " (1 वर्ष)",
                    "image": "../assets/logo.png",
                    "order_id": data.order_id,
                    "prefill": {
                        "name": data.user.name,
                        "contact": data.user.mobile,
                        "email": data.user.email
                    },
                    "theme": {
                        "color": "#1e3a8a"
                    },
                    "handler": function (response) {
                        payNowBtn.disabled = true;
                        payNowBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>भुगतान सत्यापित हो रहा है...';

                        const verifyData = new FormData();
                        verifyData.append('action', 'verify_payment');
                        verifyData.append('transaction_id', data.transaction_id);
                        verifyData.append('razorpay_payment_id', response.razorpay_payment_id || '');
                        verifyData.append('razorpay_order_id', response.razorpay_order_id || data.order_id);
                        verifyData.append('razorpay_signature', response.razorpay_signature || '');

                        fetch('../api/process_payment_api.php', {
                            method: 'POST',
                            body: verifyData
                        })
                        .then(res => res.json())
                        .then(vData => {
                            if (vData.status === 'success') {
                                alert('भुगतान सफल रहा! आपका ' + currentPlan + ' प्लान 1 वर्ष के लिए सक्रिय कर दिया गया है।');
                                window.location.href = "../" + "<?php echo getListingUrl($listing['slug']); ?>";
                            } else {
                                alert('भुगतान सत्यापन सूचना: ' + vData.message);
                                window.location.reload();
                            }
                        })
                        .catch(() => {
                            window.location.reload();
                        });
                    }
                };
                const rzp = new Razorpay(options);
                rzp.open();
            } else {
                alert('भुगतान त्रुटि: ' + (data.message || 'रेजरपे लेनदेन शुरू करने में असमर्थ।'));
            }
        })
        .catch(err => {
            payNowBtn.disabled = false;
            payNowBtn.innerHTML = `<i class="bi bi-lock-fill"></i> रेजरपे द्वारा भुगतान करें`;
            console.error('Payment Error:', err);
            alert('भुगतान गेटवे से कनेक्ट करने में विफल। कृपया अपना इंटरनेट कनेक्शन जांचें।');
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

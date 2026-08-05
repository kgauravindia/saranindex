<?php
require_once __DIR__ . '/includes/functions.php';

$currentUser = null;
if (isUserLoggedIn()) {
    $currentUser = getLoggedInUser();
}

$page_title = "Membership Plans & Pricing – Grow Your Business | Saran Index";
$meta_description = "Compare Saran Index membership packages: Basic Free, Gold Business (₹499/yr), and VIP Platinum (₹1,499/yr). Boost search ranking and reach citizens across Chapra and 20 blocks.";

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Hero Banner -->
<div class="bg-primary text-white py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%) !important;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.12) 0%, transparent 60%); pointer-events: none;"></div>
    <div class="container position-relative z-1 text-center py-3">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-3 shadow-sm">
            <i class="bi bi-rocket-takeoff-fill me-1"></i> Grow Business Visibility Across 20 Blocks
        </span>
        <h1 class="display-5 fw-bold font-heading text-white mb-3">
            Simple, Transparent Membership Plans
        </h1>
        <p class="text-white-50 fs-5 mx-auto mb-0" style="max-width: 680px;">
            Choose the right visibility tier for your business, clinic, school, or service in Saran District.
        </p>
    </div>
</div>

<div class="container py-5">

    <!-- PRICING CARDS ROW -->
    <div class="row g-4 align-items-stretch justify-content-center mb-5">

        <!-- 1. BASIC FREE PLAN -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white d-flex flex-column justify-content-between transition-all hover-border-primary">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill fw-semibold">🟢 Starter</span>
                        <span class="text-muted small">1 Listing</span>
                    </div>
                    <h3 class="fw-bold font-heading text-dark mb-2">Basic Free</h3>
                    <p class="text-muted small mb-4">Ideal for small local entities getting started on Saran Index digital directory.</p>

                    <div class="display-5 fw-bolder text-dark mb-4">
                        ₹0 <span class="fs-6 text-muted fw-normal">/ forever</span>
                    </div>

                    <hr class="text-secondary opacity-25">

                    <ul class="list-unstyled mb-4 small" style="line-height: 2;">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Standard Search Ranking</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Phone Call Button (`tel:`)</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Address & Block Info</li>
                        <li class="text-muted"><i class="bi bi-x-circle me-2"></i> No Priority Rank</li>
                        <li class="text-muted"><i class="bi bi-x-circle me-2"></i> No Verified Trust Badge</li>
                        <li class="text-muted"><i class="bi bi-x-circle me-2"></i> No Direct WhatsApp Chat</li>
                    </ul>
                </div>

                <a href="add-contact.php" class="btn btn-outline-primary rounded-pill py-3 fw-bold w-100">
                    Submit Free Listing <i class="bi bi-arrow-right me-1"></i>
                </a>
            </div>
        </div>

        <!-- 2. GOLD BUSINESS PLAN (RECOMMENDED) -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border border-2 border-primary shadow-lg rounded-4 p-4 bg-white position-relative d-flex flex-column justify-content-between transition-all" style="background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%) !important;">
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge bg-primary text-white fw-bold px-3 py-1.5 rounded-pill shadow-xs">Most Popular</span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-primary text-white px-3 py-1.5 rounded-pill fw-bold"><i class="bi bi-patch-check-fill me-1"></i> Gold Business</span>
                    </div>
                    <h3 class="fw-bold font-heading text-primary mb-2">Gold Business</h3>
                    <p class="text-dark small mb-4">Perfect for growing stores, clinics, and service providers.</p>


                    <div class="display-5 fw-bolder text-primary mb-1">
                        ₹499 <span class="fs-6 text-muted fw-normal">/ year</span>
                    </div>
                    <div class="small text-success fw-semibold mb-4"><i class="bi bi-check-lg me-1"></i> Only ₹41 / month</div>

                    <hr class="text-secondary opacity-25">

                    <ul class="list-unstyled mb-4 small text-dark" style="line-height: 2;">
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> <strong>Top Priority Search Rank</strong></li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> <strong>Green Verified Trust Badge</strong></li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> <strong>Direct WhatsApp Chat Button</strong></li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> Key Services & Facilities List</li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> Up to 3 Business Photos</li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> Full 1 Year Validity (365 Days)</li>
                    </ul>
                </div>

                <a href="<?php echo isUserLoggedIn() ? 'dashboard.php' : 'login.php?redirect=dashboard.php'; ?>" class="btn btn-primary rounded-pill py-3 fw-bold w-100 shadow-sm">
                    <i class="bi bi-rocket-takeoff-fill me-1"></i> Select Gold Plan (₹499)
                </a>
            </div>
        </div>

        <!-- 3. VIP PLATINUM PLAN -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border border-2 border-warning shadow-lg rounded-4 p-4 bg-white position-relative d-flex flex-column justify-content-between transition-all" style="background: linear-gradient(180deg, #fffbeb 0%, #ffffff 100%) !important;">
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill shadow-xs">Maximum Visibility</span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold"><i class="bi bi-crown-fill text-danger me-1"></i> VIP Platinum</span>
                    </div>
                    <h3 class="fw-bold font-heading text-dark mb-2">VIP Platinum</h3>
                    <p class="text-dark small mb-4">Ultimate top placement for premier brands, hospitals, schools, and top firms.</p>

                    <div class="display-5 fw-bolder text-dark mb-1">
                        ₹1,499 <span class="fs-6 text-muted fw-normal">/ year</span>
                    </div>
                    <div class="small text-warning text-dark fw-semibold mb-4"><i class="bi bi-star-fill me-1"></i> Top Category Placement</div>

                    <hr class="text-secondary opacity-25">

                    <ul class="list-unstyled mb-4 small text-dark" style="line-height: 2;">
                        <li><i class="bi bi-crown-fill text-warning me-2"></i> <strong>Top Featured Position</strong></li>
                        <li><i class="bi bi-crown-fill text-warning me-2"></i> <strong>Gold VIP Crown Verified Badge</strong></li>
                        <li><i class="bi bi-crown-fill text-warning me-2"></i> Call + WhatsApp + Direct Booking</li>
                        <li><i class="bi bi-crown-fill text-warning me-2"></i> Up to 6 Business Photos</li>
                        <li><i class="bi bi-crown-fill text-warning me-2"></i> Full Business Catalog & Bio</li>
                        <li><i class="bi bi-crown-fill text-warning me-2"></i> Dedicated 24x7 Priority Support</li>
                    </ul>
                </div>

                <a href="<?php echo isUserLoggedIn() ? 'dashboard.php' : 'login.php?redirect=dashboard.php'; ?>" class="btn btn-warning text-dark rounded-pill py-3 fw-bold w-100 shadow-sm">
                    <i class="bi bi-crown-fill me-1"></i> Upgrade to VIP Platinum (₹1,499)
                </a>
            </div>
        </div>

    </div>

    <!-- FEATURE COMPARISON MATRIX TABLE -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-5">
        <div class="text-center mb-4">
            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded-pill small">Detailed Matrix</span>
            <h3 class="fw-bold font-heading text-dark mt-1">Comprehensive Feature Comparison</h3>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-striped border">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 40%;">Feature / Benefit</th>
                        <th class="text-center" style="width: 20%;">🟢 Basic Free</th>
                        <th class="text-center" style="width: 20%;">🔵 Gold Business</th>
                        <th class="text-center" style="width: 20%;">👑 VIP Platinum</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold text-dark">Annual Subscription Fee</td>
                        <td class="text-center fw-bold">₹0 / Forever</td>
                        <td class="text-center fw-bold text-primary">₹499 / Year</td>
                        <td class="text-center fw-bold text-warning text-dark">₹1,499 / Year</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark">Search Rank Priority</td>
                        <td class="text-center text-muted">Standard Order</td>
                        <td class="text-center fw-bold text-primary">Top Priority Rank</td>
                        <td class="text-center fw-bold text-dark">Top Featured Spot</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark">Trust Verification Badge</td>
                        <td class="text-center text-muted">None</td>
                        <td class="text-center"><span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Verified</span></td>
                        <td class="text-center"><span class="vip-platinum-badge"><i class="bi bi-crown-fill"></i> VIP Crown</span></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark">Direct WhatsApp Contact Button</td>
                        <td class="text-center text-muted"><i class="bi bi-x-lg text-danger"></i></td>
                        <td class="text-center"><i class="bi bi-check-lg text-success fs-5"></i></td>
                        <td class="text-center"><i class="bi bi-check-lg text-success fs-5"></i></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark">Business Services & Features List</td>
                        <td class="text-center text-muted"><i class="bi bi-x-lg text-danger"></i></td>
                        <td class="text-center"><i class="bi bi-check-lg text-success fs-5"></i></td>
                        <td class="text-center"><i class="bi bi-check-lg text-success fs-5"></i></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark">Photo Gallery Uploads</td>
                        <td class="text-center text-muted">None</td>
                        <td class="text-center fw-medium">Up to 3 Photos</td>
                        <td class="text-center fw-bold text-dark">Up to 6 Photos</td>
                    </tr>

                    <tr>
                        <td class="fw-semibold text-dark">Online Payment Gateway Integration</td>
                        <td class="text-center text-muted">N/A</td>
                        <td class="text-center"><i class="bi bi-shield-check text-success fs-5"></i> Razorpay</td>
                        <td class="text-center"><i class="bi bi-shield-check text-success fs-5"></i> Razorpay</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FAQ SECTION -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
        <div class="text-center mb-4">
            <h4 class="fw-bold font-heading text-dark mb-1">Frequently Asked Questions</h4>
            <p class="text-muted small">Everything you need to know about Saran Index membership tiers.</p>
        </div>

        <div class="accordion" id="pricingFaq">
            <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-xs">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        How does online payment work via Razorpay?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#pricingFaq">
                    <div class="accordion-body text-secondary small">
                        When you select Gold Business (₹499) or VIP Platinum (₹1,499), our secure Razorpay payment gateway opens. You can pay via UPI (GPay, PhonePe, Paytm), Debit/Credit Cards, or Netbanking. Upon payment completion, your plan activates automatically.
                    </div>
                </div>
            </div>

            <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-xs">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Can I upgrade my plan anytime?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                    <div class="accordion-body text-secondary small">
                        Yes! You can upgrade from Basic Free to Gold or VIP Platinum anytime directly from your account dashboard.
                    </div>
                </div>
            </div>

            <div class="accordion-item border-0 rounded-3 overflow-hidden shadow-xs">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        Who can I contact for billing or invoice support?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                    <div class="accordion-body text-secondary small">
                        For any invoice queries or custom enterprise plans, contact our desk at <strong>ask@offerplant.com</strong> or call our support team.
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/includes/functions.php';

// Authentication Guard
if (!isUserLoggedIn()) {
    header("Location: login.php?redirect=" . urlencode('my-listings.php'));
    exit;
}

$user = getLoggedInUser();

$msg = '';
$msg_type = '';

// Handle Business Claim POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'claim_business') {
    $listingId = intval($_POST['listing_id'] ?? 0);
    $c_name = sanitizeInput($_POST['claimant_name'] ?? '');
    $c_mobile = sanitizeInput($_POST['claimant_mobile'] ?? '');
    $c_role = sanitizeInput($_POST['role_title'] ?? 'Owner / Manager');
    $c_proof = sanitizeInput($_POST['verification_proof'] ?? '');

    if ($listingId > 0 && !empty($c_name) && !empty($c_mobile)) {
        if (submitBusinessClaim($listingId, $user['id'], $c_name, $c_mobile, $c_role, $c_proof)) {
            $msg = "Business claim submitted successfully! Our team will verify and link your ownership access.";
            $msg_type = 'success';
        } else {
            $msg = "Failed to submit business claim. Please try again or contact support.";
            $msg_type = 'danger';
        }
    } else {
        $msg = "Please select a valid business listing and fill in your name and contact mobile number.";
        $msg_type = 'warning';
    }
}

$userListings = getUserListings($user['id']);

$page_title = "My Business Listings – Saran Index";
$meta_description = "Manage, edit, and monitor your listed businesses, services, and profile pages on Saran Index.";

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-light py-4 py-md-5">
    <div class="container">
        
        <?php if (!empty($msg)): ?>
            <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                <i class="bi <?php echo $msg_type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'; ?> me-2"></i>
                <?php echo htmlspecialchars($msg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Header Banner & Breadcrumb -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Listings</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-dark font-heading mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-list-stars text-primary"></i> My Business Listings
                </h1>
                <p class="text-muted small mb-0">Manage, edit, upgrade, and monitor all your listings registered under <strong><?php echo htmlspecialchars($user['full_name']); ?></strong> (+91 <?php echo htmlspecialchars($user['mobile']); ?>).</p>
            </div>

            <!-- Quick Actions -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-warning text-dark rounded-pill px-3 py-2 fw-bold shadow-sm d-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#claimSearchModal">
                    <i class="bi bi-shield-check"></i> Claim Business
                </button>
                <a href="add-contact.php" class="btn btn-primary rounded-pill px-3.5 py-2 fw-bold shadow-sm d-flex align-items-center gap-1.5">
                    <i class="bi bi-plus-circle-fill"></i> Add New Listing
                </a>
            </div>
        </div>

        <!-- Metric KPI Summary Row -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-shop fs-3"></i>
                        </div>
                        <div>
                            <div class="text-muted extra-small fw-semibold text-uppercase">Total Listings</div>
                            <h3 class="fw-bold text-dark mb-0 font-heading"><?php echo count($userListings); ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success-subtle text-success p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-patch-check-fill fs-3"></i>
                        </div>
                        <div>
                            <div class="text-muted extra-small fw-semibold text-uppercase">Active & Live</div>
                            <h3 class="fw-bold text-dark mb-0 font-heading">
                                <?php 
                                $activeCount = 0;
                                foreach ($userListings as $l) {
                                    if (($l['status'] ?? 'ACTIVE') === 'ACTIVE') $activeCount++;
                                }
                                echo $activeCount;
                                ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning-subtle text-warning p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-crown-fill fs-3"></i>
                        </div>
                        <div>
                            <div class="text-muted extra-small fw-semibold text-uppercase">Paid Upgrades</div>
                            <h3 class="fw-bold text-dark mb-0 font-heading">
                                <?php 
                                $paidCount = 0;
                                foreach ($userListings as $l) {
                                    if (in_array($l['plan_type'] ?? '', ['GOLD', 'PLATINUM'])) $paidCount++;
                                }
                                echo $paidCount;
                                ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-info-subtle text-info p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-eye-fill fs-3"></i>
                        </div>
                        <div>
                            <div class="text-muted extra-small fw-semibold text-uppercase">Total Profile Views</div>
                            <h3 class="fw-bold text-dark mb-0 font-heading">
                                <?php 
                                $viewsCount = 0;
                                foreach ($userListings as $l) {
                                    $viewsCount += intval($l['view_count'] ?? 0);
                                }
                                echo number_format($viewsCount);
                                ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listings List Section -->
        <?php if (empty($userListings)): ?>
            <!-- Empty State -->
            <div class="card border-0 shadow-sm rounded-4 p-5 bg-white text-center">
                <div class="bg-primary-subtle text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                    <i class="bi bi-shop-window fs-1"></i>
                </div>
                <h4 class="fw-bold font-heading text-dark mb-2">No Business Listings Found</h4>
                <p class="text-muted small mx-auto mb-4" style="max-width: 500px;">
                    You haven't listed any business, clinic, shop, school, or service yet. Add your listing for free to reach thousands of potential customers across Saran District.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="add-contact.php" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add Your First Listing Free
                    </a>
                    <a href="pricing.php" class="btn btn-outline-warning text-dark rounded-pill px-4 py-2.5 fw-bold">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Explore Growth Plans
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($userListings as $l): 
                    $cStatus = $l['claim_status'] ?? null;
                    $publicUrl = getListingUrl($l['slug']);
                ?>
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white transition-hover">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                            
                            <!-- Left Info Section -->
                            <div class="flex-grow-1">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                    <h5 class="fw-bold text-dark font-heading mb-0">
                                        <a href="<?php echo $publicUrl; ?>" target="_blank" class="text-dark text-decoration-none hover-primary">
                                            <?php echo htmlspecialchars($l['title']); ?>
                                        </a>
                                    </h5>

                                    <!-- Status Badge -->
                                    <?php if (($l['status'] ?? 'ACTIVE') === 'ACTIVE'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill extra-small"><i class="bi bi-check-circle-fill me-1"></i>Active & Live</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill extra-small"><i class="bi bi-hourglass-split me-1"></i>Under Review</span>
                                    <?php endif; ?>

                                    <!-- Plan Badge -->
                                    <?php if (($l['plan_type'] ?? '') === 'PLATINUM'): ?>
                                        <span class="badge bg-warning text-dark fw-bold rounded-pill extra-small"><i class="bi bi-crown-fill text-danger me-1"></i>VIP Platinum</span>
                                    <?php elseif (($l['plan_type'] ?? '') === 'GOLD'): ?>
                                        <span class="badge bg-primary text-white rounded-pill extra-small"><i class="bi bi-patch-check-fill me-1"></i>Gold Business</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-secondary border rounded-pill extra-small">Basic Free</span>
                                    <?php endif; ?>

                                    <!-- Claim Status Badge -->
                                    <?php if ($cStatus === 'APPROVED'): ?>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill extra-small"><i class="bi bi-shield-check me-1"></i>Claim Verified</span>
                                    <?php elseif ($cStatus === 'PENDING'): ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill extra-small"><i class="bi bi-clock-history me-1"></i>Claim Pending</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Metadata Strip -->
                                <div class="text-muted small d-flex flex-wrap align-items-center gap-3 mb-2.5">
                                    <span><i class="bi bi-folder-fill me-1 text-primary"></i><?php echo htmlspecialchars($l['category_name'] ?? 'General Category'); ?></span>
                                    <span><i class="bi bi-geo-alt-fill me-1 text-danger"></i><?php echo htmlspecialchars($l['block_name'] ?? 'Saran District'); ?></span>
                                    <span><i class="bi bi-telephone-fill me-1 text-success"></i>+91 <?php echo htmlspecialchars($l['mobile']); ?></span>
                                    <?php if (!empty($l['whatsapp'])): ?>
                                        <span><i class="bi bi-whatsapp me-1 text-success"></i>+91 <?php echo htmlspecialchars($l['whatsapp']); ?></span>
                                    <?php endif; ?>
                                    <span><i class="bi bi-eye me-1 text-info"></i><?php echo number_format(intval($l['view_count'] ?? 0)); ?> views</span>
                                </div>

                                <?php if (!empty($l['address'])): ?>
                                    <div class="extra-small text-secondary mb-2">
                                        <i class="bi bi-signpost-2 me-1"></i><?php echo htmlspecialchars($l['address']); ?>
                                        <?php if (!empty($l['pincode'])) echo " - " . htmlspecialchars($l['pincode']); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Public URL Strip -->
                                <div class="bg-light p-2 rounded-3 d-inline-flex align-items-center gap-2 extra-small border">
                                    <span class="text-muted">Public Link:</span>
                                    <a href="<?php echo $publicUrl; ?>" target="_blank" class="text-primary text-decoration-none fw-semibold font-monospace" id="url_<?php echo $l['id']; ?>">
                                        <?php echo (defined('BASE_URL') ? BASE_URL : 'https://saranindex.com/') . $publicUrl; ?>
                                    </a>
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-0.5 btn-copy-url" data-target="url_<?php echo $l['id']; ?>">
                                        <i class="bi bi-clipboard me-1"></i>Copy
                                    </button>
                                </div>
                            </div>

                            <!-- Right Action Buttons -->
                            <div class="d-flex flex-wrap align-items-center gap-2 flex-shrink-0">
                                <a href="<?php echo $publicUrl; ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold" title="View Public Listing Page">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>View
                                </a>

                                <a href="edit-listing.php?id=<?php echo $l['id']; ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </a>

                                <a href="pay.php?listing_id=<?php echo $l['id']; ?>&plan=GOLD" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-bold shadow-xs">
                                    <i class="bi bi-lightning-charge-fill me-1"></i>Upgrade Plan
                                </a>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Claim Existing Business Modal -->
<div class="modal fade" id="claimSearchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-4">
                <div>
                    <h5 class="modal-title fw-bold font-heading text-white mb-1"><i class="bi bi-shield-check text-warning me-2"></i>Claim Ownership of Existing Business</h5>
                    <p class="text-white-50 extra-small mb-0">Search directory listings in Saran District to request owner management access.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label for="claim_search_input" class="form-label small fw-semibold">Search Business Name, Mobile, or Category</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="claim_search_input" class="form-control fs-6" placeholder="Type business title, shop name, or phone number in Saran..." autocomplete="off">
                    </div>
                    <small class="text-muted extra-small mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Start typing to search across verified Saran directory entries.</small>
                </div>

                <div id="claim_search_results" class="mb-3" style="max-height: 280px; overflow-y: auto;">
                    <div class="text-center py-4 text-muted extra-small">
                        Type business name above to search listings.
                    </div>
                </div>

                <!-- Claim Request Form (Hidden until listing selected) -->
                <div id="claim_form_wrapper" class="bg-light p-3.5 rounded-3 border" style="display: none;">
                    <h6 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-file-earmark-check text-primary me-2"></i>Submit Business Claim Verification</h6>
                    <form action="my-listings.php" method="POST">
                        <input type="hidden" name="action" value="claim_business">
                        <input type="hidden" name="listing_id" id="selected_claim_listing_id" value="">

                        <div class="mb-3 p-2.5 bg-white rounded-3 border">
                            <span class="extra-small text-muted d-block">Selected Business:</span>
                            <strong id="selected_claim_listing_title" class="text-primary font-heading fs-6"></strong>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="claimant_name" class="form-label extra-small fw-semibold">Your Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="claimant_name" name="claimant_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="claimant_mobile" class="form-label extra-small fw-semibold">Contact Mobile <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="claimant_mobile" name="claimant_mobile" value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="role_title" class="form-label extra-small fw-semibold">Role / Designation</label>
                                <select class="form-select form-select-sm" id="role_title" name="role_title">
                                    <option value="Owner / Proprietor">Owner / Proprietor</option>
                                    <option value="General Manager">General Manager</option>
                                    <option value="Authorized Representative">Authorized Representative</option>
                                    <option value="Employee">Employee</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="verification_proof" class="form-label extra-small fw-semibold">Verification Proof (GST / Visiting Card / Note)</label>
                                <input type="text" class="form-control form-control-sm" id="verification_proof" name="verification_proof" placeholder="e.g. GSTIN, Shop License, or Visiting Card details">
                            </div>
                        </div>

                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-warning text-dark btn-sm rounded-pill px-4 fw-bold shadow-xs">
                                <i class="bi bi-send-fill me-1"></i> Submit Claim Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy URL helper
    document.querySelectorAll('.btn-copy-url').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetEl = document.getElementById(targetId);
            if (targetEl) {
                const text = targetEl.textContent.trim();
                navigator.clipboard.writeText(text).then(() => {
                    const origHtml = this.innerHTML;
                    this.innerHTML = '<i class="bi bi-check2 text-success me-1"></i>Copied!';
                    setTimeout(() => {
                        this.innerHTML = origHtml;
                    }, 2000);
                }).catch(() => {
                    prompt('Copy Link:', text);
                });
            }
        });
    });

    // AJAX Business Claim Search
    const searchInput = document.getElementById('claim_search_input');
    const resultsBox = document.getElementById('claim_search_results');
    const formWrapper = document.getElementById('claim_form_wrapper');
    const listingIdInput = document.getElementById('selected_claim_listing_id');
    const listingTitleBox = document.getElementById('selected_claim_listing_title');

    let searchTimer = null;
    if (searchInput && resultsBox) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            const query = this.value.trim();
            if (query.length < 2) {
                resultsBox.innerHTML = '<div class="text-center py-4 text-muted extra-small">Type at least 2 characters to search listings.</div>';
                return;
            }

            resultsBox.innerHTML = '<div class="text-center py-4 text-muted extra-small"><span class="spinner-border spinner-border-sm me-2 text-primary"></span>Searching directory...</div>';

            searchTimer = setTimeout(() => {
                fetch('ajax_claim_search.php?q=' + encodeURIComponent(query))
                    .then(res => res.json())
                    .then(data => {
                        if (!data || data.length === 0) {
                            resultsBox.innerHTML = '<div class="text-center py-4 text-muted extra-small"><i class="bi bi-info-circle me-1"></i>No matching listings found in Saran. You can <a href="add-contact.php" class="text-primary fw-bold text-decoration-none">Add a New Listing</a> instead.</div>';
                            return;
                        }

                        let html = '<div class="list-group list-group-flush border rounded-3">';
                        data.forEach(item => {
                            html += `
                                <div class="list-group-item list-group-item-action p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1 font-heading">${item.title}</h6>
                                        <div class="extra-small text-muted">
                                            <span class="badge bg-light text-dark border me-1">${item.category_name || 'Business'}</span>
                                            <i class="bi bi-geo-alt text-danger me-1"></i>${item.block_name || 'Saran'} &bull; 
                                            <i class="bi bi-telephone text-success me-1"></i>+91 ${item.mobile || 'N/A'}
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold btn-select-claim" data-id="${item.id}" data-title="${item.title.replace(/"/g, '&quot;')}">
                                        <i class="bi bi-check-lg me-1"></i>Claim This
                                    </button>
                                </div>
                            `;
                        });
                        html += '</div>';
                        resultsBox.innerHTML = html;

                        document.querySelectorAll('.btn-select-claim').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const id = this.getAttribute('data-id');
                                const title = this.getAttribute('data-title');
                                listingIdInput.value = id;
                                listingTitleBox.textContent = title;
                                formWrapper.style.display = 'block';
                                formWrapper.scrollIntoView({ behavior: 'smooth' });
                            });
                        });
                    })
                    .catch(err => {
                        resultsBox.innerHTML = '<div class="text-center py-4 text-danger extra-small">An error occurred while searching.</div>';
                    });
            }, 300);
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


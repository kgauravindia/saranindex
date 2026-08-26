<?php
require_once __DIR__ . '/../includes/functions.php';

// Authentication Guard
if (!isUserLoggedIn()) {
    header("Location: login.php?redirect=" . urlencode('my-listings.php'));
    exit;
}

$user = getLoggedInUser();
$userListings = getUserListings($user['id']);

$page_title = "मेरी व्यावसायिक लिस्टिंग्स – सारण इंडेक्स";
$meta_description = "सारण इंडेक्स पर अपने सूचीबद्ध व्यवसायों, सेवाओं और प्रोफ़ाइल पृष्ठों को प्रबंधित और संपादित करें।";

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-light py-4 py-md-5">
    <div class="container">
        
        <!-- Header Banner & Breadcrumb -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">मुख्य पृष्ठ</a></li>
                        <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">डैशबोर्ड</a></li>
                        <li class="breadcrumb-item active" aria-current="page">मेरी लिस्टिंग्स</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-dark font-heading mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-list-stars text-primary"></i> मेरी व्यावसायिक लिस्टिंग्स
                </h1>
                <p class="text-muted small mb-0"><strong><?php echo htmlspecialchars($user['full_name']); ?></strong> (+91 <?php echo htmlspecialchars($user['mobile']); ?>) के तहत पंजीकृत सभी लिस्टिंग्स का प्रबंधन करें।</p>
            </div>

            <!-- Quick Actions -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="add-contact.php" class="btn btn-primary rounded-pill px-3.5 py-2 fw-bold shadow-sm d-flex align-items-center gap-1.5">
                    <i class="bi bi-plus-circle-fill"></i> नई लिस्टिंग जोड़ें
                </a>
                <a href="dashboard.php" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1.5">
                    <i class="bi bi-speedometer2"></i> डैशबोर्ड
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
                            <div class="text-muted extra-small fw-semibold text-uppercase">कुल लिस्टिंग्स</div>
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
                            <div class="text-muted extra-small fw-semibold text-uppercase">सक्रिय एवं लाइव</div>
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
                            <div class="text-muted extra-small fw-semibold text-uppercase">पेड अपग्रेड्स</div>
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
                            <div class="text-muted extra-small fw-semibold text-uppercase">कुल प्रोफ़ाइल व्यूज</div>
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
                <h4 class="fw-bold font-heading text-dark mb-2">कोई व्यावसायिक लिस्टिंग नहीं मिली</h4>
                <p class="text-muted small mx-auto mb-4" style="max-width: 500px;">
                    आपने अभी तक कोई व्यवसाय, क्लिनिक, दुकान, स्कूल या सेवा सूचीबद्ध नहीं की है। सारण जिले के ग्राहकों तक पहुंचने के लिए आज ही निःशुल्क लिस्टिंग जोड़ें।
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="add-contact.php" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> अपनी पहली लिस्टिंग मुफ़्त में जोड़ें
                    </a>
                    <a href="pricing.php" class="btn btn-outline-warning text-dark rounded-pill px-4 py-2.5 fw-bold">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i> प्लान देखें
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($userListings as $l): 
                    $cStatus = $l['claim_status'] ?? null;
                    $publicUrl = '../' . getListingUrl($l['slug']);
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
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill extra-small"><i class="bi bi-check-circle-fill me-1"></i>सक्रिय एवं लाइव</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill extra-small"><i class="bi bi-hourglass-split me-1"></i>समीक्षाधीन</span>
                                    <?php endif; ?>

                                    <!-- Plan Badge -->
                                    <?php if (($l['plan_type'] ?? '') === 'PLATINUM'): ?>
                                        <span class="badge bg-warning text-dark fw-bold rounded-pill extra-small"><i class="bi bi-crown-fill text-danger me-1"></i>वीआईपी प्लैटिनम</span>
                                    <?php elseif (($l['plan_type'] ?? '') === 'GOLD'): ?>
                                        <span class="badge bg-primary text-white rounded-pill extra-small"><i class="bi bi-patch-check-fill me-1"></i>गोल्ड बिजनेस</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-secondary border rounded-pill extra-small">मुफ़्त बेसिक</span>
                                    <?php endif; ?>

                                    <!-- Claim Status Badge -->
                                    <?php if ($cStatus === 'APPROVED'): ?>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill extra-small"><i class="bi bi-shield-check me-1"></i>क्लेम सत्यापित</span>
                                    <?php elseif ($cStatus === 'PENDING'): ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill extra-small"><i class="bi bi-clock-history me-1"></i>क्लेम लंबित</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Metadata Strip -->
                                <div class="text-muted small d-flex flex-wrap align-items-center gap-3 mb-2.5">
                                    <span><i class="bi bi-folder-fill me-1 text-primary"></i><?php echo htmlspecialchars($l['category_name'] ?? 'सामान्य'); ?></span>
                                    <span><i class="bi bi-geo-alt-fill me-1 text-danger"></i><?php echo htmlspecialchars($l['block_name'] ?? 'सारण जिला'); ?></span>
                                    <span><i class="bi bi-telephone-fill me-1 text-success"></i>+91 <?php echo htmlspecialchars($l['mobile']); ?></span>
                                    <?php if (!empty($l['whatsapp'])): ?>
                                        <span><i class="bi bi-whatsapp me-1 text-success"></i>+91 <?php echo htmlspecialchars($l['whatsapp']); ?></span>
                                    <?php endif; ?>
                                    <span><i class="bi bi-eye me-1 text-info"></i><?php echo number_format(intval($l['view_count'] ?? 0)); ?> व्यूज</span>
                                </div>

                                <?php if (!empty($l['address'])): ?>
                                    <div class="extra-small text-secondary mb-2">
                                        <i class="bi bi-signpost-2 me-1"></i><?php echo htmlspecialchars($l['address']); ?>
                                        <?php if (!empty($l['pincode'])) echo " - " . htmlspecialchars($l['pincode']); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Public URL Strip -->
                                <div class="bg-light p-2 rounded-3 d-inline-flex align-items-center gap-2 extra-small border">
                                    <span class="text-muted">पब्लिक लिंक:</span>
                                    <a href="<?php echo $publicUrl; ?>" target="_blank" class="text-primary text-decoration-none fw-semibold font-monospace" id="url_<?php echo $l['id']; ?>">
                                        <?php echo (defined('BASE_URL') ? BASE_URL : 'https://saranindex.com/') . getListingUrl($l['slug']); ?>
                                    </a>
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-0.5 btn-copy-url" data-target="url_<?php echo $l['id']; ?>">
                                        <i class="bi bi-clipboard me-1"></i>कॉपी
                                    </button>
                                </div>
                            </div>

                            <!-- Right Action Buttons -->
                            <div class="d-flex flex-wrap align-items-center gap-2 flex-shrink-0">
                                <a href="<?php echo $publicUrl; ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold" title="पब्लिक पेज देखें">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>देखें
                                </a>

                                <a href="edit-listing.php?id=<?php echo $l['id']; ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">
                                    <i class="bi bi-pencil me-1"></i>संपादित करें
                                </a>

                                <a href="pay.php?listing_id=<?php echo $l['id']; ?>&plan=GOLD" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-bold shadow-xs">
                                    <i class="bi bi-lightning-charge-fill me-1"></i>प्लान अपग्रेड करें
                                </a>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

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
                    this.innerHTML = '<i class="bi bi-check2 text-success me-1"></i>कॉपी किया गया!';
                    setTimeout(() => {
                        this.innerHTML = origHtml;
                    }, 2000);
                }).catch(() => {
                    prompt('लिंक कॉपी करें:', text);
                });
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

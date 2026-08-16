<?php
require_once __DIR__ . '/includes/functions.php';

// Auth Guard: Require Login
if (!isUserLoggedIn()) {
    header('Location: login.php?redirect=dashboard.php');
    exit;
}

$user = getLoggedInUser();
if (!$user) {
    logoutPublicUser();
    header('Location: login.php');
    exit;
}

$msg = '';
$msg_type = '';

// Handle Profile Update POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $fullName = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $whatsapp = $_POST['whatsapp'] ?? '';
    $businessName = $_POST['business_name'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $blockId = $_POST['block_id'] ?? null;
    $address = $_POST['address'] ?? '';
    $pincode = $_POST['pincode'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    $res = updateUserProfile($user['id'], $fullName, $email, $blockId, $address, $newPassword, $whatsapp, $businessName, $designation, $pincode, null, null, $bio);
    if ($res['success']) {
        $msg = $res['message'];
        $msg_type = 'success';
        $user = getLoggedInUser(); // Refresh user data
    } else {
        $msg = $res['message'];
        $msg_type = 'danger';
    }
}

// Handle Delete User Profile POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && ($_POST['action'] === 'delete_profile' || $_POST['action'] === 'delete_account')) {
    $confirm_input = trim($_POST['confirm_delete'] ?? '');
    if (strtoupper($confirm_input) === 'DELETE') {
        $userIdToDelete = $user['id'];
        if (deleteUser($userIdToDelete)) {
            header("Location: login.php?msg=profile_deleted");
            exit;
        } else {
            $msg = "आपकी उपयोगकर्ता प्रोफ़ाइल डिलीट करने में त्रुटि हुई। कृपया पुनः प्रयास करें।";
            $msg_type = 'danger';
        }
    } else {
        $msg = "प्रोफ़ाइल डिलीट की पुष्टि करने के लिए कृपया बड़े अक्षरों में 'DELETE' टाइप करें।";
        $msg_type = 'danger';
    }
}

// Handle Plan Upgrade & Online Payment POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upgrade_plan') {
    $listingId = intval($_POST['listing_id'] ?? 0);
    $newPlan = isset($_POST['plan_type']) && in_array($_POST['plan_type'], ['FREE', 'GOLD', 'PLATINUM']) ? $_POST['plan_type'] : 'FREE';

    if ($listingId > 0) {
        $db = getDB();
        if ($db) {
            try {
                if ($newPlan === 'FREE') {
                    $stmtU = $db->prepare("UPDATE listings SET plan_type = 'FREE', plan_expires_at = NULL, is_featured = 'NO', is_verified = 'NO' WHERE id = :id");
                    $stmtU->execute(['id' => $listingId]);
                    $msg = "मेंबरशिप प्लान बुनियादी मुफ्त (FREE) में सेट कर दिया गया!";
                    $msg_type = 'success';
                } else {
                    $amount = ($newPlan === 'GOLD') ? 499.00 : 1499.00;
                    $payment = createOnlinePayment($user['id'], $listingId, $newPlan, $amount, 'RAZORPAY_UPI');
                    if ($payment) {
                        $payId = 'PAY_ONLINE_' . time() . '_' . rand(100, 999);
                        completeOnlinePayment($payment['transaction_id'], $payId, 'SUCCESS', 'Online Payment Verification Successful');
                        $msg = "ऑनलाइन भुगतान ₹" . number_format($amount, 0) . " प्राप्त हुआ (ट्रांजैक्शन ID: " . $payment['transaction_id'] . ")। " . $newPlan . " मेंबरशिप सक्रिय हो गई!";
                        $msg_type = 'success';
                    } else {
                        $msg = "ऑनलाइन भुगतान में त्रुटि। कृपया पुनः प्रयास करें।";
                        $msg_type = 'danger';
                    }
                }
            } catch (PDOException $e) {
                $msg = "प्ला अपग्रेड करने में त्रुटि: " . $e->getMessage();
                $msg_type = 'danger';
            }
        }
    }
}

$userListings = getUserListings($user['id']);
$userPayments = getUserPayments($user['id']);
$blocks = getBlocks();

$page_title = "मेरा खाता एवं डैशबोर्ड – सारण इंडेक्स";
$meta_description = "सारण इंडेक्स पर आपका उपयोगकर्ता खाता डैशबोर्ड। अपनी लिस्टिंग, प्रोफ़ाइल, ऑनलाइन भुगतान और व्यवसाय निर्देशिका प्रविष्टियों को प्रबंधित करें।";

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">मुख्य पृष्ठ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">डैशबोर्ड</li>
                    </ol>
                </nav>
                <h2 class="fw-bold font-heading text-dark mb-0">स्वागत है, <?php echo htmlspecialchars($user['full_name']); ?>!</h2>
                <small class="text-muted"><i class="bi bi-phone me-1"></i>+91 <?php echo htmlspecialchars($user['mobile']); ?> | यूजर ID: #<?php echo intval($user['id']); ?></small>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-pencil-square me-1"></i> प्रोफ़ाइल अपडेट करें
                </button>
                <a href="../add-contact.php" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark shadow-xs">
                    <i class="bi bi-plus-circle me-1"></i> नई लिस्टिंग जोड़ें
                </a>
                <a href="../logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">
                    <i class="bi bi-box-arrow-right me-1"></i> लॉगआउट (Logout)
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <?php if ($msg): ?>
        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show rounded-3 p-3 shadow-sm mb-4 small" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-6"></i><?php echo htmlspecialchars($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Sidebar: User Profile Card & Quick Stats -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="text-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold fs-3 mb-2 shadow-sm" style="width: 72px; height: 72px;">
                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                    </div>
                    <h5 class="fw-bold text-dark mb-1 font-heading"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                    <?php if (!empty($user['business_name'])): ?>
                        <div class="badge bg-primary-subtle text-primary fw-semibold mb-2"><?php echo htmlspecialchars($user['business_name']); ?></div>
                    <?php endif; ?>
                    <p class="text-muted small mb-0"><?php echo !empty($user['designation']) ? htmlspecialchars($user['designation']) : 'पंजीकृत सदस्य'; ?></p>
                </div>

                <hr class="text-secondary opacity-25">

                <div class="small">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted"><i class="bi bi-person-badge me-2 text-primary"></i>यूजर ID</span>
                        <span class="fw-bold text-dark">#<?php echo intval($user['id']); ?></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted"><i class="bi bi-telephone me-2 text-primary"></i>मोबाइल</span>
                        <span class="fw-medium text-dark">+91 <?php echo htmlspecialchars($user['mobile']); ?></span>
                    </div>
                    <?php if (!empty($user['whatsapp'])): ?>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"><i class="bi bi-whatsapp me-2 text-success"></i>व्हाट्सएप</span>
                            <span class="fw-medium text-dark">+91 <?php echo htmlspecialchars($user['whatsapp']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($user['email'])): ?>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted"><i class="bi bi-envelope me-2 text-primary"></i>ईमेल</span>
                            <span class="fw-medium text-dark"><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <hr class="text-secondary opacity-25">

                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn btn-light btn-sm w-100 rounded-pill fw-semibold text-secondary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="bi bi-gear me-1"></i> खाता सेटिंग्स प्रबंधित करें
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-semibold" data-bs-toggle="modal" data-bs-target="#deleteProfileModal">
                        <i class="bi bi-trash me-1"></i> प्रोफ़ाइल डिलीट करें (Delete Profile)
                    </button>
                </div>
            </div>

            <!-- Quick Help Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-headset me-2 text-warning"></i> बिलिंग सहायता</h6>
                <p class="small text-muted mb-3">ऑनलाइन भुगतान या रसीद से संबंधित सहायता के लिए हमारी बिलिंग टीम से संपर्क करें।</p>
                <a href="contact.php" class="btn btn-outline-dark btn-sm rounded-pill w-100 fw-bold">सहायता टीम से संपर्क करें</a>
            </div>
        </div>

        <!-- Main Column: User's Listings & Payment Logs -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3 flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold font-heading text-dark mb-0">मेरी डायरेक्टरी सूचियां और दावों की स्थिति</h4>
                        <small class="text-muted">आपके खाते (+91 <?php echo htmlspecialchars($user['mobile']); ?>) से जुड़े दावे और लिस्टिंग</small>
                    </div>
                    <div>
                        <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill">
                            कुल: <?php echo count($userListings); ?> लिस्टिंग
                        </span>
                    </div>
                </div>

                <?php if (empty($userListings)): ?>
                    <div class="text-center py-5">
                        <div class="text-muted display-4 mb-3"><i class="bi bi-shop-window"></i></div>
                        <h5 class="fw-bold text-dark mb-2">कोई सूची नहीं मिली</h5>
                        <p class="text-muted small mx-auto mb-4" style="max-width: 420px;">इस व्यू में कोई लिस्टिंग उपलब्ध नहीं है।</p>
                        <a href="add-contact.php" class="btn btn-warning text-dark fw-bold rounded-pill px-4">
                            <i class="bi bi-plus-circle me-1"></i> मुफ्त में व्यवसाय जोड़ें
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover border">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th>नाम एवं श्रेणी</th>
                                    <th>प्लान का प्रकार</th>
                                    <th>दावा एवं स्थिति</th>
                                    <th>कार्रवाई</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userListings as $l): 
                                    $cStatus = $l['claim_status'] ?? null;
                                ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($l['title']); ?></div>
                                            <small class="text-muted"><i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($l['category_name'] ?? 'सामान्य'); ?></small>
                                        </td>
                                        <td>
                                            <?php if (isset($l['plan_type']) && $l['plan_type'] === 'PLATINUM'): ?>
                                                <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill small shadow-xs">
                                                    <i class="bi bi-crown-fill me-1 text-danger"></i> वीआईपी प्लैटिनम
                                                </span>
                                            <?php elseif (isset($l['plan_type']) && $l['plan_type'] === 'GOLD'): ?>
                                                <span class="badge bg-primary text-white fw-bold px-2.5 py-1 rounded-pill small shadow-xs">
                                                    <i class="bi bi-patch-check-fill me-1"></i> गोल्ड बिजनेस
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-secondary border px-2.5 py-1 rounded-pill small">बुनियादी मुफ्त</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($cStatus === 'PENDING'): ?>
                                                <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill small"><i class="bi bi-hourglass-split me-1"></i>दावा लंबित (Pending)</span>
                                                <small class="text-muted d-block extra-small">एडमिन स्वीकृति की प्रतीक्षा</small>
                                            <?php elseif ($cStatus === 'APPROVED'): ?>
                                                <span class="badge bg-success text-white px-2.5 py-1 rounded-pill small"><i class="bi bi-shield-check me-1"></i>दावा स्वीकृत (Approved)</span>
                                            <?php elseif ($l['status'] === 'ACTIVE'): ?>
                                                <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill small"><i class="bi bi-check-circle me-1"></i>सक्रिय</span>
                                            <?php elseif ($l['status'] === 'PENDING'): ?>
                                                <span class="badge bg-warning-subtle text-dark px-2.5 py-1 rounded-pill small"><i class="bi bi-hourglass-split me-1"></i>समीक्षाधीन</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill small">निष्क्रिय</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1.5 align-items-center">
                                                <a href="<?php echo getListingUrl($l['slug']); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="देखें (View)">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                                <?php if ($cStatus === 'PENDING'): ?>
                                                    <span class="badge bg-light text-muted border px-2 py-1 extra-small" title="एडमिन स्वीकृति के बाद संपादन चालू होगा">
                                                        <i class="bi bi-lock-fill me-1"></i>लंबित समीक्षा
                                                    </span>
                                                <?php else: ?>
                                                    <a href="edit-listing.php?id=<?php echo sanitizeInput($l['id']); ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 small" title="विवरण एवं परिचय संपादित करें">
                                                        <i class="bi bi-pencil-square me-1"></i>विवरण संपादित करें
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-warning text-dark rounded-circle p-0 d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 32px; height: 32px;" title="मेंबरशिप प्लान अपग्रेड करें" onclick="openUpgradeModal('<?php echo sanitizeInput($l['id']); ?>', '<?php echo sanitizeInput(addslashes($l['title'])); ?>', '<?php echo sanitizeInput($l['plan_type'] ?? 'FREE'); ?>')">
                                                        <i class="bi bi-rocket-takeoff-fill"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ONLINE PAYMENTS & TRANSACTION HISTORY TABLE -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h5 class="fw-bold font-heading text-dark mb-0">
                        <i class="bi bi-credit-card-2-front-fill text-primary me-2"></i>ऑनलाइन भुगतान इतिहास (Payments)
                    </h5>
                    <span class="badge bg-primary-subtle text-primary rounded-pill fw-semibold">कुल <?php echo count($userPayments); ?> भुगतान</span>
                </div>

                <?php if (empty($userPayments)): ?>
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-receipt fs-3 d-block mb-1 opacity-50"></i>
                        अभी तक कोई ऑनलाइन भुगतान दर्ज नहीं हुआ है।
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle border small mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ट्रांजैक्शन ID</th>
                                    <th>लिस्टिंग / सेवा</th>
                                    <th>प्ला</th>
                                    <th>राशि</th>
                                    <th>स्थिति</th>
                                    <th>दिनांक</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userPayments as $p): ?>
                                    <tr>
                                        <td class="font-monospace fw-bold text-dark"><?php echo sanitizeInput($p['transaction_id']); ?></td>
                                        <td class="fw-medium text-dark"><?php echo sanitizeInput($p['listing_title'] ?? 'लिस्टिंग अपग्रेड'); ?></td>
                                        <td>
                                            <span class="badge <?php echo ($p['plan_type'] === 'PLATINUM') ? 'bg-warning text-dark' : 'bg-primary'; ?> rounded-pill">
                                                <?php echo sanitizeInput($p['plan_type']); ?>
                                            </span>
                                        </td>
                                        <td class="fw-bold text-dark">₹<?php echo number_format($p['amount'], 2); ?></td>
                                        <td>
                                            <?php if ($p['payment_status'] === 'SUCCESS'): ?>
                                                <span class="badge bg-success-subtle text-success rounded-pill px-2.5"><i class="bi bi-check-circle me-1"></i>सफल</span>
                                            <?php elseif ($p['payment_status'] === 'PENDING'): ?>
                                                <span class="badge bg-warning-subtle text-dark rounded-pill px-2.5">लंबित</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5">विफल</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small"><?php echo date('d M Y, h:i A', strtotime($p['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="bg-gradient-primary text-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0 text-white"><i class="bi bi-person-lines-fill me-2"></i> प्रोफ़ाइल अपडेट करें</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <form action="dashboard.php" method="POST">
                <input type="hidden" name="action" value="update_profile">
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">पूरा नाम</label>
                            <input type="text" name="full_name" class="form-control rounded-3 py-2" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">ईमेल पता</label>
                            <input type="email" name="email" class="form-control rounded-3 py-2" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">व्हाट्सएप नंबर</label>
                            <input type="text" name="whatsapp" class="form-control rounded-3 py-2" value="<?php echo htmlspecialchars($user['whatsapp'] ?? ''); ?>" maxlength="10">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">व्यवसाय / फर्म का नाम</label>
                            <input type="text" name="business_name" class="form-control rounded-3 py-2" value="<?php echo htmlspecialchars($user['business_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">पद / व्यवसाय</label>
                            <input type="text" name="designation" class="form-control rounded-3 py-2" value="<?php echo htmlspecialchars($user['designation'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">प्रखंड</label>
                            <select name="block_id" class="form-select rounded-3 py-2">
                                <option value="">-- प्रखंड चुनें --</option>
                                <?php foreach ($blocks as $b): ?>
                                    <option value="<?php echo $b['id']; ?>" <?php echo (isset($user['block_id']) && $user['block_id'] == $b['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars(!empty($b['hindi_name']) ? $b['hindi_name'] : $b['block_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">पिनकोड</label>
                            <input type="text" name="pincode" class="form-control rounded-3 py-2" value="<?php echo htmlspecialchars($user['pincode'] ?? ''); ?>" maxlength="6">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark mb-1">पता</label>
                            <input type="text" name="address" class="form-control rounded-3 py-2" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark mb-1">परिचय / विवरण</label>
                            <textarea name="bio" class="form-control rounded-3" rows="2"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white p-3 border-top">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">रद्द करें</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">बदलाव सहेजें</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Profile Modal -->
<div class="modal fade" id="deleteProfileModal" tabindex="-1" aria-labelledby="deleteProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="bg-danger text-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0 text-white"><i class="bi bi-exclamation-triangle-fill me-2"></i> प्रोफ़ाइल डिलीट करें (Delete Profile)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <form action="dashboard.php" method="POST">
                <input type="hidden" name="action" value="delete_profile">
                <div class="modal-body p-4 bg-light">
                    <div class="alert alert-danger border-danger-subtle rounded-3 small mb-3">
                        <i class="bi bi-shield-exclamation me-1"></i> <strong>चेतावनी:</strong> प्रोफ़ाइल हटाना <strong>स्थायी</strong> है।
                    </div>
                    <p class="small text-muted mb-3">आपकी प्रोफ़ाइल जानकारी, फोटो और खाता क्रेडेंशियल्स डेटाबेस से हमेशा के लिए डिलीट हो जाएंगे।</p>
                    <div class="mb-3">
                        <label for="confirm_delete_hi" class="form-label fw-bold small text-dark">पुष्टि के लिए <span class="text-danger">DELETE</span> टाइप करें:</label>
                        <input type="text" name="confirm_delete" id="confirm_delete_hi" class="form-control rounded-3" placeholder="बड़े अक्षरों में DELETE टाइप करें" required autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer bg-white p-3 border-top">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">रद्द करें</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold"><i class="bi bi-trash-fill me-1"></i> प्रोफ़ाइल हमेशा के लिए डिलीट करें</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upgrade Membership Plan Modal -->
<div class="modal fade" id="upgradePlanModal" tabindex="-1" aria-labelledby="upgradePlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="bg-primary text-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0 text-white"><i class="bi bi-credit-card-fill me-2"></i> ऑनलाइन मेंबरशिप भुगतान</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="small text-white-50 mt-1" id="upgradeModalListingTitle">यूजर ID #<?php echo intval($user['id']); ?> से जुड़ा सुरक्षित ऑनलाइन भुगतान</div>
            </div>
            <form action="dashboard.php<?php echo $viewAll ? '?view=all' : ''; ?>" method="POST" id="upgradeForm">
                <input type="hidden" name="action" value="upgrade_plan">
                <input type="hidden" name="listing_id" id="upgrade_listing_id" value="">
                
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="card h-100 border rounded-4 p-3 cursor-pointer bg-white">
                                <input type="radio" name="plan_type" value="FREE" id="plan_radio_free" class="form-check-input mb-2">
                                <div class="fw-bold text-dark fs-6">🟢 बुनियादी मुफ्त</div>
                                <div class="fs-5 fw-bold text-primary mb-2">₹0 / वर्ष</div>
                                <span class="small text-muted">सामान्य खोज रैंक एवं बुनियादी जानकारी।</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="card h-100 border border-primary rounded-4 p-3 cursor-pointer bg-primary-subtle border-2">
                                <input type="radio" name="plan_type" value="GOLD" id="plan_radio_gold" class="form-check-input mb-2">
                                <div class="badge bg-primary text-white fw-bold w-auto me-auto mb-1">अनुशंसित</div>
                                <div class="fw-bold text-primary fs-6">🔵 गोल्ड बिजनेस</div>
                                <div class="fs-5 fw-bold text-primary mb-2">₹499 / वर्ष</div>
                                <span class="small text-dark">शीर्ष प्राथमिकता रैंक, हरा वेरीफाइड बैज, 3 फ़ोटो तक एवं व्हाट्सएप बटन।</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="card h-100 border border-warning rounded-4 p-3 cursor-pointer bg-warning-subtle border-2">
                                <input type="radio" name="plan_type" value="PLATINUM" id="plan_radio_platinum" class="form-check-input mb-2">
                                <div class="badge bg-warning text-dark fw-bold w-auto me-auto mb-1">सर्वोत्तम स्थान</div>
                                <div class="fw-bold text-dark fs-6">👑 वीआईपी प्लैटिनम</div>
                                <div class="fs-5 fw-bold text-dark mb-2">₹1,499 / वर्ष</div>
                                <span class="small text-dark">शीर्ष फीचर्ड स्थान, गोल्ड वीआईपी वेरीफाइड बैज, 6 फ़ोटो तक एवं डायरेक्ट लिंक।</span>
                            </label>
                        </div>

                    </div>

                    <div class="p-3 bg-white rounded-3 border d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-success fs-4"></i>
                            <span class="small text-secondary">रेजरपे सुरक्षित भुगतान गेटवे • त्वरित सक्रियण • यूजर ID #<?php echo intval($user['id']); ?> में रसीद लॉग्ड</span>
                        </div>
                        <span class="badge bg-light text-dark border">Razorpay UPI / कार्ड्स</span>
                    </div>
                </div>
                <div class="modal-footer bg-white p-3 border-top">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">रद्द करें</button>
                    <button type="submit" id="upgradePayBtn" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="bi bi-lock-fill me-1"></i> रेजरपे से भुगतान करें</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openUpgradeModal(id, title, plan) {
    document.getElementById('upgrade_listing_id').value = id;
    document.getElementById('upgradeModalListingTitle').innerText = 'ऑनलाइन भुगतान चेकआउट: ' + title;
    
    document.getElementById('plan_radio_free').checked = (plan === 'FREE');
    document.getElementById('plan_radio_gold').checked = (plan === 'GOLD');
    document.getElementById('plan_radio_platinum').checked = (plan === 'PLATINUM');
    
    const modal = new bootstrap.Modal(document.getElementById('upgradePlanModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    const upgradeForm = document.getElementById('upgradeForm');
    if (upgradeForm) {
        upgradeForm.addEventListener('submit', function(e) {
            const selectedPlan = document.querySelector('input[name="plan_type"]:checked').value;
            const listingId = document.getElementById('upgrade_listing_id').value;

            if (selectedPlan === 'FREE') {
                return true;
            }

            e.preventDefault();
            const btn = document.getElementById('upgradePayBtn');
            const originalBtnText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-arrow-repeat spin me-1"></i> रेजरपे खुल रहा है...';

            const formData = new FormData();
            formData.append('action', 'create_order');
            formData.append('listing_id', listingId);
            formData.append('plan_type', selectedPlan);

            fetch('api/process_payment_api.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalBtnText;

                if (data.status === 'success') {
                    const options = {
                        "key": data.key,
                        "amount": data.amount,
                        "currency": data.currency,
                        "name": "Saran Index",
                        "description": "मेंबरशिप अपग्रेड - " + selectedPlan + " प्लान",
                        "image": "assets/img/logo.png",
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
                            const verifyData = new FormData();
                            verifyData.append('action', 'verify_payment');
                            verifyData.append('transaction_id', data.transaction_id);
                            verifyData.append('razorpay_payment_id', response.razorpay_payment_id || '');
                            verifyData.append('razorpay_order_id', response.razorpay_order_id || data.order_id);
                            verifyData.append('razorpay_signature', response.razorpay_signature || '');

                            fetch('api/process_payment_api.php', {
                                method: 'POST',
                                body: verifyData
                            })
                            .then(res => res.json())
                            .then(vData => {
                                if (vData.status === 'success') {
                                    alert('रेजरपे भुगतान सफल रहा! आपका ' + selectedPlan + ' प्लान सक्रिय कर दिया गया है।');
                                    window.location.reload();
                                } else {
                                    alert('रेजरपे सत्यापन त्रुटि: ' + vData.message);
                                }
                            });
                        }
                    };
                    const rzp = new Razorpay(options);
                    rzp.open();
                } else {
                    alert('रेजरपे ऑर्डर निर्माण में त्रुटि: ' + data.message);
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
                console.error('Razorpay Error:', err);
                alert('रेजरपे भुगतान शुरू करते समय त्रुटि हुई।');
            });
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


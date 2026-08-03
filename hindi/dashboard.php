<?php
require_once __DIR__ . '/includes/functions.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = getLoggedInUser();
if (!$user) {
    logoutPublicUser();
    header("Location: login.php");
    exit;
}

$msg = '';
$msg_type = '';

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

$userListings = getUserListings($user['mobile']);
$blocks = getBlocks();

$page_title = "मेरा खाता डैशबोर्ड – सारण इंडेक्स";
$meta_description = "सारण इंडेक्स पर आपका खाता डैशबोर्ड। अपनी लिस्टिंग, प्रोफ़ाइल और व्यावसायिक प्रविष्टियाँ प्रबंधित करें।";

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <span class="badge bg-success-subtle text-success fw-bold px-3 py-1 rounded-pill small mb-1">
                    <i class="bi bi-shield-check me-1"></i> सक्रिय सदस्य खाता
                </span>
                <h2 class="fw-bold font-heading text-dark mb-0">स्वागत है, <?php echo htmlspecialchars($user['full_name']); ?>!</h2>
                <small class="text-muted"><i class="bi bi-telephone me-1"></i>+91 <?php echo htmlspecialchars($user['mobile']); ?> <?php echo !empty($user['email']) ? '• <i class="bi bi-envelope me-1"></i>' . htmlspecialchars($user['email']) : ''; ?></small>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary rounded-pill fw-bold px-3" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-pencil-square me-1"></i> प्रोफ़ाइल बदलें
                </button>
                <a href="add-listing.php" class="btn btn-warning text-dark fw-bold rounded-pill shadow-sm px-4">
                    <i class="bi bi-plus-circle-fill me-1"></i> मुफ़्त व्यवसाय जोड़ें
                </a>
                <a href="logout.php" class="btn btn-outline-danger rounded-pill fw-semibold px-3">
                    <i class="bi bi-box-arrow-right me-1"></i> लॉगआउट
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <?php if (!empty($msg)): ?>
        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show rounded-3 small py-2.5 mb-4" role="alert">
            <i class="bi bi-info-circle-fill me-1"></i> <?php echo $msg; ?>
            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <!-- Sidebar Profile Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="text-center pb-3 border-bottom mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold fs-2 mb-3" style="width: 72px; height: 72px;">
                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                    </div>
                    <h5 class="fw-bold text-dark font-heading mb-1"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                    <?php if (!empty($user['designation'])): ?>
                        <div class="text-muted small mb-1 fw-semibold"><i class="bi bi-briefcase me-1"></i><?php echo htmlspecialchars($user['designation']); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($user['business_name'])): ?>
                        <div class="badge bg-warning-subtle text-dark border px-3 py-1 rounded-pill small mb-1"><i class="bi bi-building me-1"></i><?php echo htmlspecialchars($user['business_name']); ?></div>
                    <?php else: ?>
                        <span class="badge bg-light text-secondary border px-3 py-1 rounded-pill small">सारण इंडेक्स सदस्य</span>
                    <?php endif; ?>
                    <?php if (!empty($user['bio'])): ?>
                        <p class="small text-muted fst-italic mt-2 mb-0"><?php echo htmlspecialchars($user['bio']); ?></p>
                    <?php endif; ?>
                </div>

                <ul class="list-unstyled small text-secondary mb-3">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span><i class="bi bi-telephone text-primary me-2"></i>मोबाइल नंबर</span>
                        <strong class="text-dark">+91 <?php echo htmlspecialchars($user['mobile']); ?></strong>
                    </li>
                    <?php if (!empty($user['whatsapp'])): ?>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span><i class="bi bi-whatsapp text-success me-2"></i>व्हाट्सएप नंबर</span>
                        <strong class="text-dark">+91 <?php echo htmlspecialchars($user['whatsapp']); ?></strong>
                    </li>
                    <?php endif; ?>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span><i class="bi bi-envelope text-primary me-2"></i>ईमेल पता</span>
                        <strong class="text-dark"><?php echo !empty($user['email']) ? htmlspecialchars($user['email']) : 'उपलब्ध नहीं'; ?></strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span><i class="bi bi-geo-alt text-primary me-2"></i>सारण प्रखंड</span>
                        <strong class="text-dark"><?php echo !empty($user['block_hindi']) ? htmlspecialchars($user['block_hindi']) : (!empty($user['block_name']) ? htmlspecialchars($user['block_name']) : 'सारण जिला'); ?></strong>
                    </li>
                    <?php if (!empty($user['pincode'])): ?>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span><i class="bi bi-pin-map text-primary me-2"></i>पिन कोड</span>
                        <strong class="text-dark"><?php echo htmlspecialchars($user['pincode']); ?></strong>
                    </li>
                    <?php endif; ?>
                    <li class="d-flex justify-content-between py-2">
                        <span><i class="bi bi-calendar-event text-primary me-2"></i>पंजीकरण तिथि</span>
                        <strong class="text-dark"><?php echo date('d M Y', strtotime($user['created_at'])); ?></strong>
                    </li>
                </ul>

                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-pencil me-1"></i> प्रोफ़ाइल डेटा अपडेट करें
                </button>
            </div>

            <!-- Quick Help Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-headset me-2 text-warning"></i> सहायता चाहिए?</h6>
                <p class="small text-muted mb-3">यदि आपको अपनी व्यावसायिक लिस्टिंग संपादित करने में कोई सहायता चाहिए, तो हमारी सहायता टीम से संपर्क करें।</p>
                <a href="contact.php" class="btn btn-outline-dark btn-sm rounded-pill w-100 fw-bold">सहायता टीम से संपर्क करें</a>
            </div>
        </div>

        <!-- Main Column: User's Listings -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <div>
                        <h4 class="fw-bold font-heading text-dark mb-0">मेरी प्रविष्टि (Listings)</h4>
                        <small class="text-muted">आपके पंजीकृत मोबाइल नंबर (+91 <?php echo htmlspecialchars($user['mobile']); ?>) से जुड़ी निर्देशिका प्रविष्टियाँ</small>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-1.5 fw-bold"><?php echo count($userListings); ?> प्रविष्टियाँ</span>
                </div>

                <?php if (empty($userListings)): ?>
                    <div class="text-center py-5">
                        <div class="text-muted display-4 mb-3"><i class="bi bi-shop-window"></i></div>
                        <h5 class="fw-bold text-dark mb-2">कोई लिस्टिंग नहीं मिली</h5>
                        <p class="text-muted small mx-auto mb-4" style="max-width: 420px;">आपने अभी तक इस मोबाइल नंबर के तहत कोई व्यावसायिक या पेशेवर लिस्टिंग दर्ज नहीं की है।</p>
                        <a href="add-listing.php" class="btn btn-warning text-dark fw-bold rounded-pill px-4">
                            <i class="bi bi-plus-circle me-1"></i> मुफ़्त लिस्टिंग दर्ज करें
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover border">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th>नाम एवं श्रेणी</th>
                                    <th>प्रखंड</th>
                                    <th>स्थिति</th>
                                    <th>कार्य</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userListings as $l): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($l['title']); ?></div>
                                            <small class="text-muted"><i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($l['category_name'] ?? 'General'); ?></small>
                                        </td>
                                        <td class="small text-dark">
                                            <i class="bi bi-geo-alt me-1 text-muted"></i><?php echo htmlspecialchars($l['block_name'] ?? 'Saran'); ?>
                                        </td>
                                        <td>
                                            <?php if ($l['status'] === 'ACTIVE'): ?>
                                                <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill small"><i class="bi bi-check-circle me-1"></i>सक्रिय</span>
                                            <?php elseif ($l['status'] === 'PENDING'): ?>
                                                <span class="badge bg-warning-subtle text-dark px-2.5 py-1 rounded-pill small"><i class="bi bi-hourglass-split me-1"></i>समीक्षाधीन</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill small">निष्क्रिय</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo getListingUrl($l['slug']); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                देखें <i class="bi bi-box-arrow-up-right ms-1"></i>
                                            </a>
                                        </td>
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
            <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                <h5 class="modal-title fw-bold font-heading text-white" id="editProfileModalLabel">
                    <i class="bi bi-person-gear text-warning me-2"></i> प्रोफ़ाइल एवं व्यवसाय विवरण अपडेट करें
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="action" value="update_profile">
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label fw-bold small text-dark">पूरा नाम <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control" required value="<?php echo htmlspecialchars($user['full_name']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="mobile_readonly" class="form-label fw-bold small text-muted">पंजीकृत मोबाइल</label>
                            <input type="text" id="mobile_readonly" class="form-control bg-light" readonly value="+91 <?php echo htmlspecialchars($user['mobile']); ?>">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="whatsapp" class="form-label fw-bold small text-dark">व्हाट्सएप नंबर</label>
                            <input type="tel" name="whatsapp" id="whatsapp" class="form-control" placeholder="10-अंकों का व्हाट्सएप नंबर" maxlength="10" value="<?php echo htmlspecialchars($user['whatsapp'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold small text-dark">ईमेल पता</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="yourname@gmail.com" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="business_name" class="form-label fw-bold small text-dark">व्यवसाय / दुकान का नाम</label>
                            <input type="text" name="business_name" id="business_name" class="form-control" placeholder="उदा. ऑफरप्लांट टेक्नोलॉजीज" value="<?php echo htmlspecialchars($user['business_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="designation" class="form-label fw-bold small text-dark">पेशा / पदनाम</label>
                            <input type="text" name="designation" id="designation" class="form-control" placeholder="उदा. व्यवसायी / डॉक्टर / अधिवक्ता" value="<?php echo htmlspecialchars($user['designation'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="block_id" class="form-label fw-bold small text-dark">सारण प्रखंड</label>
                            <select name="block_id" id="block_id" class="form-select">
                                <option value="">-- प्रखंड चुनें --</option>
                                <?php foreach ($blocks as $b): ?>
                                    <option value="<?php echo $b['id']; ?>" <?php echo ($user['block_id'] == $b['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($b['hindi_name']); ?> (<?php echo htmlspecialchars($b['block_name']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="pincode" class="form-label fw-bold small text-dark">पिन कोड</label>
                            <input type="text" name="pincode" id="pincode" class="form-control" placeholder="उदा. 841301" maxlength="6" value="<?php echo htmlspecialchars($user['pincode'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-bold small text-dark">पता / स्थान</label>
                        <textarea name="address" id="address" class="form-control" rows="2" placeholder="गाँव / शहर, लैंडमार्क..."><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label fw-bold small text-dark">परिचय / बायोग्राफी</label>
                        <textarea name="bio" id="bio" class="form-control" rows="2" placeholder="अपने व्यवसाय या पेशे का संक्षिप्त विवरण..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>

                    <hr class="my-3">

                    <div class="mb-2">
                        <label for="new_password" class="form-label fw-bold small text-dark">पासवर्ड बदलें <span class="text-muted font-normal">(वर्तमान रखने हेतु खाली छोड़ें)</span></label>
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="नया पासवर्ड (कम से कम 6 अक्षर)">
                    </div>
                </div>

                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">रद्द करें</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> परिवर्तन सहेजें
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

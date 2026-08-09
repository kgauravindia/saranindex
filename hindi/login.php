<?php

require_once __DIR__ . '/includes/functions.php';

if (isUserLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$not_registered_mobile = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile   = trim($_POST['mobile'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($mobile) || empty($password)) {
        $error = "कृपया मोबाइल नंबर/ईमेल और पासवर्ड दोनों दर्ज करें।";
    } else {
        $result = loginPublicUser($mobile, $password);
        if ($result['success']) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = $result['message'];
            $cleanMob = preg_replace('/[^0-9]/', '', $mobile);
            if (strlen($cleanMob) >= 10) {
                $db = getDB();
                if ($db) {
                    $chkUser = $db->prepare("SELECT id FROM users WHERE mobile = :m LIMIT 1");
                    $chkUser->execute(['m' => $cleanMob]);
                    if (!$chkUser->fetch()) {
                        $not_registered_mobile = $cleanMob;
                    }
                }
            }
        }
    }
}

$page_title       = "उपयोगकर्ता लॉगिन – सारण इंडेक्स";
$meta_description = "सारण इंडेक्स में अपने खाते में लॉगिन करें और अपनी व्यावसायिक लिस्टिंग, संपर्क और प्रोफ़ाइल प्रबंधित करें।";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">
                    
                    <!-- Left Side: Branding / Showcase -->
                    <div class="col-md-5 col-lg-6 d-none d-md-flex flex-column justify-content-center bg-gradient-primary text-white p-5 position-relative">
                        <!-- Decorative elements -->
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 50%); pointer-events: none;"></div>
                        <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at bottom right, rgba(59,130,246,0.3) 0%, rgba(0,0,0,0) 50%); pointer-events: none;"></div>
                        
                        <div class="position-relative z-index-1">
                            <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Saran Index Logo" height="65" class="mb-4 rounded-3 shadow-sm bg-white p-2">
                            <h2 class="fw-bold font-heading mb-3 text-white lh-base">सारण इंडेक्स में<br><span class="text-warning">पुनः स्वागत है</span></h2>
                            <p class="text-white-50 mb-5 fs-6 lh-lg">सारण जिले के लिए आपकी केंद्रीय डिजिटल निर्देशिका। अपनी व्यावसायिक लिस्टिंग प्रबंधित करें, स्थानीय संसाधनों तक पहुंचें और समुदाय से जुड़ें।</p>
                            
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 backdrop-blur">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-white text-primary flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi bi-shield-check fs-5"></i>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold mb-1">सत्यापित निर्देशिका</div>
                                        <div class="text-white-50 small">हजारों विश्वसनीय स्थानीय संपर्कों तक पहुंचें।</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 backdrop-blur">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-white text-primary flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi bi-graph-up-arrow fs-5"></i>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold mb-1">अपनी पहुंच बढ़ाएं</div>
                                        <div class="text-white-50 small">पूरे जिले में अपना व्यवसाय प्रदर्शित करें।</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Login Form -->
                    <div class="col-md-7 col-lg-6 bg-white p-4 p-md-5 d-flex flex-column justify-content-center">
                        
                        <!-- Mobile Header (Hidden on Desktop) -->
                        <div class="text-center d-md-none mb-4 pb-2 border-bottom">
                            <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Saran Index Logo" height="50" class="mb-3 rounded-3 shadow-sm">
                            <h4 class="fw-bold font-heading mb-1">पुनः स्वागत है</h4>
                            <p class="text-muted small">अपनी लिस्टिंग प्रबंधित करने के लिए लॉगिन करें</p>
                        </div>
                        
                        <!-- Desktop Header -->
                        <div class="d-none d-md-block mb-4 pb-2">
                            <h3 class="fw-bold font-heading mb-1 text-dark">लॉग इन करें</h3>
                            <p class="text-muted small">अपने खाते तक पहुँचने के लिए अपना विवरण दर्ज करें।</p>
                        </div>

                        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'profile_deleted'): ?>
                            <div class="alert alert-success alert-dismissible fade show rounded-3 small border-0 shadow-sm mb-4" role="alert">
                                <i class="bi bi-check-circle-fill me-2 text-success"></i> आपकी उपयोगकर्ता प्रोफ़ाइल और खाता `users` तालिका से सफलतापूर्वक डिलीट कर दिया गया है।
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 small border-0 shadow-sm" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($not_registered_mobile)): ?>
                            <div class="alert alert-warning rounded-3 small mb-4 border-warning shadow-sm">
                                <div class="fw-bold mb-1 text-dark"><i class="bi bi-person-plus-fill me-1"></i> खाता अभी पंजीकृत नहीं है</div>
                                <span class="text-dark">मोबाइल <strong>+91 <?php echo htmlspecialchars($not_registered_mobile); ?></strong> पंजीकृत नहीं है। अपना मुफ़्त खाता बनाने के लिए नीचे क्लिक करें:</span>
                                <div class="mt-3 mb-1">
                                    <a href="register.php?mobile=<?php echo htmlspecialchars($not_registered_mobile); ?>" class="btn btn-warning text-dark btn-sm fw-bold rounded-pill px-4 shadow-sm">
                                        अभी पंजीकृत करें <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST" class="mt-2">
                            <!-- Mobile / Email Field -->
                            <div class="form-floating mb-3">
                                <input type="text" name="mobile" id="mobile" class="form-control border-secondary-subtle rounded-3"
                                       placeholder="10-digit Mobile Number or Email"
                                       required autofocus
                                       value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; ?>">
                                <label for="mobile" class="text-muted"><i class="bi bi-person me-2"></i>मोबाइल नंबर या ईमेल</label>
                            </div>
                            
                            <!-- Password Field -->
                            <div class="form-floating mb-4 position-relative">
                                <input type="password" name="password" id="password" class="form-control border-secondary-subtle rounded-3" style="padding-right: 50px;" placeholder="Enter Password" required>
                                <label for="password" class="text-muted"><i class="bi bi-lock me-2"></i>पासवर्ड</label>
                                <button class="btn border-0 text-muted position-absolute end-0 top-0 h-100 px-3 d-flex align-items-center justify-content-center" type="button" id="togglePassword">
                                    <i class="bi bi-eye-slash-fill fs-5" id="togglePasswordIcon"></i>
                                </button>
                            </div>

                            <!-- Options -->
                            <div class="d-flex align-items-center justify-content-between mb-4 px-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="rememberMe" checked>
                                    <label class="form-check-label small text-muted user-select-none" for="rememberMe">
                                        मुझे याद रखें
                                    </label>
                                </div>
                                <a href="forgot-password.php" class="small fw-semibold text-primary text-decoration-none hover-underline">पासवर्ड भूल गए?</a>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-4 search-submit-btn fs-6 d-flex align-items-center justify-content-center gap-2">
                                <span>सुरक्षित लॉगिन करें</span>
                                <i class="bi bi-arrow-right-circle-fill"></i>
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="position-relative text-center my-4">
                            <hr class="text-secondary-subtle opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted fw-medium">या</span>
                        </div>

                        <!-- Register Action -->
                        <div class="text-center">
                            <p class="small text-muted mb-3">क्या आपका खाता नहीं है?</p>
                            <a href="register.php" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-bold w-100 d-flex align-items-center justify-content-center gap-2 transition-all">
                                <i class="bi bi-person-plus"></i>
                                <span>निःशुल्क नया खाता बनाएं</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Helper -->
            <div class="text-center mt-4">
                <div class="bg-white rounded-pill px-4 py-2 d-inline-block shadow-sm border border-light">
                    <span class="small text-muted fw-medium me-2">क्या आप सारण में व्यवसायी हैं?</span> 
                    <a href="add-contact.php" class="fw-bold text-primary text-decoration-none small">
                        निःशुल्क लिस्टिंग जोड़ें <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-floating > .form-control:focus,
.form-floating > .form-control:not(:placeholder-shown) {
    padding-top: 1.625rem;
    padding-bottom: 0.625rem;
}
.form-floating > label {
    padding: 1rem 0.75rem;
}
.form-control:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.15);
}
.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
.hover-underline:hover {
    text-decoration: underline !important;
}
</style>

<script>
document.getElementById('togglePassword')?.addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('togglePasswordIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
        icon.classList.add('text-primary');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
        icon.classList.remove('text-primary');
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/functions.php';

if (isUserLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$not_registered_mobile = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile = trim($_POST['mobile'] ?? '');
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

$page_title = "उपयोगकर्ता लॉगिन – सारण इंडेक्स";
$meta_description = "सारण इंडेक्स में अपने खाते में लॉगिन करें और अपनी व्यावसायिक लिस्टिंग, संपर्क और प्रोफ़ाइल प्रबंधित करें।";

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white text-center py-4 px-4 border-0">
                    <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Saran Index Logo" height="42" class="mb-2 rounded-2">
                    <h4 class="fw-bold font-heading mb-1 text-white">वापसी पर स्वागत है</h4>
                    <p class="text-white-50 small mb-0">सारण जिले में अपनी लिस्टिंग प्रबंधित करने के लिए लॉगिन करें</p>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2.5" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($not_registered_mobile)): ?>
                        <div class="alert alert-warning rounded-3 small py-2.5 mb-4 border-warning">
                            <div class="fw-bold mb-1"><i class="bi bi-person-plus-fill me-1"></i> खाता अभी पंजीकृत नहीं है</div>
                            मोबाइल <strong>+91 <?php echo htmlspecialchars($not_registered_mobile); ?></strong> पंजीकृत नहीं है। अपना मुफ़्त खाता बनाने के लिए नीचे क्लिक करें:
                            <div class="mt-2">
                                <a href="register.php?mobile=<?php echo htmlspecialchars($not_registered_mobile); ?>" class="btn btn-warning text-dark btn-sm fw-bold rounded-pill px-3">
                                    अभी पंजीकृत करें (10 सेकंड) <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="mobile" class="form-label fw-bold small text-dark">मोबाइल नंबर या ईमेल</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-phone-fill"></i></span>
                                <input type="text" name="mobile" id="mobile" class="form-control border-start-0 ps-0" placeholder="10-अंकों का मोबाइल नंबर या ईमेल" required autofocus value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label for="password" class="form-label fw-bold small text-dark mb-0">पासवर्ड</label>
                                <a href="forgot-password.php" class="small fw-semibold text-primary text-decoration-none">पासवर्ड भूल गए?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password" id="password" class="form-control border-start-0 border-end-0 ps-0" placeholder="पासवर्ड दर्ज करें" required>
                                <button class="btn btn-light border border-start-0 text-muted" type="button" id="togglePassword">
                                    <i class="bi bi-eye-slash-fill" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-3">
                            <i class="bi bi-box-arrow-in-right me-1"></i> लॉगिन करें
                        </button>
                    </form>

                    <div class="text-center pt-3 border-top">
                        <p class="small text-muted mb-2">क्या आपका खाता नहीं है?</p>
                        <a href="register.php" class="btn btn-outline-dark rounded-pill btn-sm px-4 fw-bold">
                            <i class="bi bi-person-plus me-1"></i> निःशुल्क नया खाता बनाएं
                        </a>
                    </div>
                </div>

                <div class="card-footer bg-light text-center py-3 border-0">
                    <span class="small text-muted">क्या आप सारण में व्यवसायी हैं? <a href="add-listing.php" class="fw-bold text-primary text-decoration-none">मुफ़्त लिस्टिंग जोड़ें</a></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('togglePasswordIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye-slash-fill');
        icon.classList.add('bi-eye-fill');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye-fill');
        icon.classList.add('bi-eye-slash-fill');
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/functions.php';

if (isUserLoggedIn()) {
    header("Location: dashboard");
    exit;
}

$error = '';
$blocks = getBlocks();
$prefillMobile = $_GET['mobile'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $blockId = $_POST['block_id'] ?? null;
    $address = $_POST['address'] ?? '';

    if ($password !== $confirmPassword) {
        $error = "पासवर्ड मेल नहीं खाते। कृपया पुनः दर्ज करें।";
    } else {
        $result = registerPublicUser($fullName, $mobile, $password, $email, $blockId, $address);
        if ($result['success']) {
            header("Location: dashboard");
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

$page_title = "निःशुल्क खाता बनाएं – सारण इंडेक्स";
$meta_description = "सारण इंडेक्स पर अपना निःशुल्क उपयोगकर्ता खाता पंजीकृत करें और अपनी व्यावसायिक लिस्टिंग प्रबंधित करें।";

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white text-center py-4 px-4 border-0">
                    <h4 class="fw-bold font-heading mb-1 text-white"><i class="bi bi-person-plus-fill text-warning me-2"></i>निःशुल्क खाता बनाएं</h4>
                    <p class="text-white-50 small mb-0">सारण जिले के प्रमुख डिजिटल निर्देशिका नेटवर्क से जुड़ें</p>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2.5" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="full_name" class="form-label fw-bold small text-dark">पूरा नाम <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" name="full_name" id="full_name" class="form-control border-start-0 ps-0" placeholder="उदा. रमेश कुमार" required value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="mobile" class="form-label fw-bold small text-dark">मोबाइल नंबर <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">+91</span>
                                    <input type="tel" name="mobile" id="mobile" class="form-control border-start-0 ps-0" placeholder="10-अंकों का मोबाइल" maxlength="10" required value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : htmlspecialchars($prefillMobile); ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold small text-dark">ईमेल पता <span class="text-muted font-normal">(ऐच्छिक)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" id="email" class="form-control border-start-0 ps-0" placeholder="yourname@gmail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="block_id" class="form-label fw-bold small text-dark">सारण में अपना प्रखंड चुनें</label>
                            <select name="block_id" id="block_id" class="form-select bg-light">
                                <option value="">-- प्रखंड चुनें (ऐच्छिक) --</option>
                                <?php foreach ($blocks as $b): ?>
                                    <option value="<?php echo $b['id']; ?>" <?php echo (isset($_POST['block_id']) && $_POST['block_id'] == $b['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($b['hindi_name']); ?> (<?php echo htmlspecialchars($b['block_name']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-bold small text-dark">पासवर्ड <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="password" class="form-control border-start-0 ps-0" placeholder="कम से कम 6 अक्षर" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label fw-bold small text-dark">पासवर्ड की पुष्टि करें <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-check2-circle"></i></span>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control border-start-0 ps-0" placeholder="पुनः पासवर्ड दर्ज करें" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold shadow-sm mb-3">
                            <i class="bi bi-check-circle-fill me-1"></i> नया खाता पंजीकृत करें
                        </button>
                    </form>

                    <div class="text-center pt-3 border-top">
                        <p class="small text-muted mb-0">क्या आप पहले से पंजीकृत हैं?</p>
                        <a href="login" class="fw-bold text-primary text-decoration-none small">लॉगिन करने के लिए यहाँ क्लिक करें</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

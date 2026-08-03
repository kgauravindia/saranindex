<?php

require_once __DIR__ . '/includes/functions.php';

if (isUserLoggedIn()) {
    header("Location: dashboard.php");
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
        $error = "Passwords do not match. Please re-enter.";
    } else {
        $result = registerPublicUser($fullName, $mobile, $password, $email, $blockId, $address);
        if ($result['success']) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

$page_title = "Create Free Account – Saran Index";
$meta_description = "Register your free user account on Saran Index to manage business listings, post updates, and connect with Saran District.";

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">
                    
                    <!-- Left Side: Branding / Showcase -->
                    <div class="col-md-5 col-lg-5 d-none d-md-flex flex-column justify-content-center bg-gradient-primary text-white p-5 position-relative">
                        <!-- Decorative elements -->
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top left, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 50%); pointer-events: none;"></div>
                        <div class="position-absolute bottom-0 end-0 w-100 h-100" style="background: radial-gradient(circle at bottom right, rgba(59,130,246,0.3) 0%, rgba(0,0,0,0) 50%); pointer-events: none;"></div>
                        
                        <div class="position-relative z-index-1">
                            <img src="assets/logo.png" alt="Saran Index Logo" height="65" class="mb-4 rounded-3 shadow-sm bg-white p-2">
                            <h2 class="fw-bold font-heading mb-3 text-white lh-base">Join the<br><span class="text-warning">Saran Network</span></h2>
                            <p class="text-white-50 mb-5 fs-6 lh-lg">Create your free account to list your business, discover local contacts, and connect with the district.</p>
                            
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 backdrop-blur">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-white text-primary flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi bi-briefcase fs-5"></i>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold mb-1">Business Listings</div>
                                        <div class="text-white-50 small">Showcase your services to the entire district.</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 backdrop-blur">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-white text-primary flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi bi-people fs-5"></i>
                                    </div>
                                    <div class="lh-sm">
                                        <div class="fw-bold mb-1">Local Connections</div>
                                        <div class="text-white-50 small">Access thousands of verified local contacts easily.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Registration Form -->
                    <div class="col-md-7 col-lg-7 bg-white p-4 p-md-5 d-flex flex-column justify-content-center">
                        
                        <!-- Mobile Header (Hidden on Desktop) -->
                        <div class="text-center d-md-none mb-4 pb-2 border-bottom">
                            <img src="assets/logo.png" alt="Saran Index Logo" height="50" class="mb-3 rounded-3 shadow-sm">
                            <h4 class="fw-bold font-heading mb-1">Create Account</h4>
                            <p class="text-muted small">Join Saran's premier directory network</p>
                        </div>
                        
                        <!-- Desktop Header -->
                        <div class="d-none d-md-block mb-4 pb-2">
                            <h3 class="fw-bold font-heading mb-1 text-dark">Create Your Free Account</h3>
                            <p class="text-muted small">Fill in the details below to get started instantly.</p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2.5 shadow-sm border-0" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1 text-danger"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST" class="mt-2">
                            <!-- Full Name -->
                            <div class="form-floating mb-3">
                                <input type="text" name="full_name" id="full_name" class="form-control border-secondary-subtle rounded-3" placeholder="e.g. Ramesh Kumar" required value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                                <label for="full_name" class="text-muted"><i class="bi bi-person me-2"></i>Full Name <span class="text-danger">*</span></label>
                            </div>

                            <div class="row g-3 mb-3">
                                <!-- Mobile Number -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" name="mobile" id="mobile" class="form-control border-secondary-subtle rounded-3" placeholder="10-digit Mobile" maxlength="10" required value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : htmlspecialchars($prefillMobile); ?>">
                                        <label for="mobile" class="text-muted"><i class="bi bi-phone me-2"></i>Mobile No. (+91) <span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" name="email" id="email" class="form-control border-secondary-subtle rounded-3" placeholder="yourname@gmail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                        <label for="email" class="text-muted"><i class="bi bi-envelope me-2"></i>Email (Optional)</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Block Selection -->
                            <div class="form-floating mb-3">
                                <select name="block_id" id="block_id" class="form-select border-secondary-subtle rounded-3">
                                    <option value="">-- Select Block (Optional) --</option>
                                    <?php foreach ($blocks as $b): ?>
                                        <option value="<?php echo $b['id']; ?>" <?php echo (isset($_POST['block_id']) && $_POST['block_id'] == $b['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($b['block_name']); ?> (<?php echo htmlspecialchars($b['hindi_name']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="block_id" class="text-muted"><i class="bi bi-geo-alt me-2"></i>Your Block in Saran</label>
                            </div>

                            <div class="row g-3 mb-4">
                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-floating position-relative">
                                        <input type="password" name="password" id="password" class="form-control border-secondary-subtle rounded-3" style="padding-right: 40px;" placeholder="At least 6 characters" required>
                                        <label for="password" class="text-muted"><i class="bi bi-lock me-2"></i>Password <span class="text-danger">*</span></label>
                                        <button class="btn border-0 text-muted position-absolute end-0 top-0 h-100 px-3 d-flex align-items-center justify-content-center" type="button" id="togglePassword">
                                            <i class="bi bi-eye-slash-fill" id="togglePasswordIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control border-secondary-subtle rounded-3" placeholder="Re-enter Password" required>
                                        <label for="confirm_password" class="text-muted"><i class="bi bi-check2-circle me-2"></i>Confirm <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-4 shadow-sm fs-6 d-flex align-items-center justify-content-center gap-2">
                                <span>Create Free Account</span>
                                <i class="bi bi-person-plus-fill"></i>
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="position-relative text-center my-4">
                            <hr class="text-secondary-subtle opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted fw-medium">OR</span>
                        </div>

                        <!-- Login Action -->
                        <div class="text-center">
                            <p class="small text-muted mb-3">Already registered on Saran Index?</p>
                            <a href="login.php" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-bold w-100 d-flex align-items-center justify-content-center gap-2 transition-all">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <span>Log In Securely</span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            
            <!-- Terms Warning -->
            <div class="text-center mt-4">
                <p class="small text-muted">
                    By registering, you agree to our <a href="terms.php" class="text-primary text-decoration-none fw-medium hover-underline">Terms of Service</a> and <a href="privacy-policy.php" class="text-primary text-decoration-none fw-medium hover-underline">Privacy Policy</a>.
                </p>
            </div>
            
        </div>
    </div>
</div>

<style>
/* Custom overrides for this specific page */
.form-floating > .form-control:focus,
.form-floating > .form-control:not(:placeholder-shown),
.form-floating > .form-select {
    padding-top: 1.625rem;
    padding-bottom: 0.625rem;
}
.form-floating > label {
    padding: 1rem 0.75rem;
}
.form-control:focus, .form-select:focus {
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
    const confirmInput = document.getElementById('confirm_password');
    const icon = document.getElementById('togglePasswordIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        confirmInput.type = 'text';
        icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
        icon.classList.add('text-primary');
    } else {
        passwordInput.type = 'password';
        confirmInput.type = 'password';
        icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
        icon.classList.remove('text-primary');
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

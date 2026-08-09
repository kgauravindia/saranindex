<?php
require_once __DIR__ . '/includes/functions.php';

// Auth Guard: User must be logged in
if (!isUserLoggedIn()) {
    header("Location: login.php?redirect=change-password.php");
    exit;
}

$user = getLoggedInUser();
if (!$user) {
    logoutPublicUser();
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error = "Please fill in all required password fields.";
    } elseif (strlen($newPassword) < 6) {
        $error = "New password must be at least 6 characters long.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "New password and confirmation password do not match.";
    } else {
        // Verify current password against database
        $db = getDB();
        if ($db) {
            $stmt = $db->prepare("SELECT password_hash FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $user['id']]);
            $hashInDb = $stmt->fetchColumn();

            if (!empty($hashInDb) && !password_verify($currentPassword, $hashInDb)) {
                $error = "Your current password is incorrect. Please re-enter.";
            } else {
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updStmt = $db->prepare("UPDATE users SET password_hash = :hash, updated_at = NOW() WHERE id = :id");
                if ($updStmt->execute(['hash' => $newHash, 'id' => $user['id']])) {
                    $success = "Your password has been changed successfully!";
                } else {
                    $error = "Failed to update password. Please try again.";
                }
            }
        } else {
            $error = "Database connection error. Please try again.";
        }
    }
}

$page_title = "Change Password – Saran Index";
$meta_description = "Change your account password securely on Saran Index user dashboard.";

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-light py-4 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Change Password</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-primary text-white p-4 text-center position-relative">
                    <div class="rounded-circle bg-white bg-opacity-20 d-inline-flex align-items-center justify-content-center p-3 mb-2 shadow-sm" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-lock-fill text-warning fs-3"></i>
                    </div>
                    <h3 class="fw-bold font-heading mb-1 text-white">Change Account Password</h3>
                    <p class="text-white-50 small mb-0">Keep your account secure with a strong password</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2.5 shadow-sm border-0 mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show rounded-3 small py-2.5 shadow-sm border-0 mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2 text-success"></i> <?php echo htmlspecialchars($success); ?>
                            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" class="mt-2">
                        <!-- Current Password -->
                        <div class="form-floating mb-3 position-relative">
                            <input type="password" name="current_password" id="current_password" class="form-control border-secondary-subtle rounded-3" style="padding-right: 45px;" placeholder="Current Password" required autofocus>
                            <label for="current_password" class="text-muted"><i class="bi bi-lock me-2"></i>Current Password <span class="text-danger">*</span></label>
                            <button type="button" class="btn border-0 text-muted position-absolute end-0 top-0 h-100 px-3 d-flex align-items-center justify-content-center" onclick="togglePass('current_password', 'toggleCurrIcon')">
                                <i class="bi bi-eye-slash-fill" id="toggleCurrIcon"></i>
                            </button>
                        </div>

                        <!-- New Password -->
                        <div class="form-floating mb-3 position-relative">
                            <input type="password" name="new_password" id="new_password" class="form-control border-secondary-subtle rounded-3" style="padding-right: 45px;" placeholder="New Password (min 6 chars)" required pattern=".{6,}" title="At least 6 characters">
                            <label for="new_password" class="text-muted"><i class="bi bi-key me-2"></i>New Password <span class="text-danger">*</span></label>
                            <button type="button" class="btn border-0 text-muted position-absolute end-0 top-0 h-100 px-3 d-flex align-items-center justify-content-center" onclick="togglePass('new_password', 'toggleNewIcon')">
                                <i class="bi bi-eye-slash-fill" id="toggleNewIcon"></i>
                            </button>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="form-floating mb-4 position-relative">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control border-secondary-subtle rounded-3" style="padding-right: 45px;" placeholder="Re-enter New Password" required>
                            <label for="confirm_password" class="text-muted"><i class="bi bi-check2-circle me-2"></i>Confirm New Password <span class="text-danger">*</span></label>
                            <button type="button" class="btn border-0 text-muted position-absolute end-0 top-0 h-100 px-3 d-flex align-items-center justify-content-center" onclick="togglePass('confirm_password', 'toggleConfIcon')">
                                <i class="bi bi-eye-slash-fill" id="toggleConfIcon"></i>
                            </button>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-3 shadow-sm fs-6 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-shield-check fs-5"></i>
                            <span>Update Password Now</span>
                        </button>
                    </form>

                    <div class="text-center mt-4 border-top pt-3">
                        <a href="dashboard.php" class="text-decoration-none text-muted small fw-medium">
                            <i class="bi bi-arrow-left me-1"></i> Return to Account Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
        icon.classList.add('text-primary');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
        icon.classList.remove('text-primary');
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

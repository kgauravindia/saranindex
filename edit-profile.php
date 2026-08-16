<?php
require_once __DIR__ . '/includes/functions.php';

// Auth Guard: Require Login
if (!isUserLoggedIn()) {
    header('Location: login.php?redirect=edit-profile.php');
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
    $post_data = [
        'full_name' => $_POST['full_name'] ?? '',
        'username_handle' => $_POST['username_handle'] ?? '',
        'email' => $_POST['email'] ?? '',
        'whatsapp' => $_POST['whatsapp'] ?? '',
        'business_name' => $_POST['business_name'] ?? '',
        'designation' => $_POST['designation'] ?? '',
        'profession_category' => $_POST['profession_category'] ?? '',
        'category_id' => !empty($_POST['category_id']) ? intval($_POST['category_id']) : null,
        'subcategory_id' => !empty($_POST['subcategory_id']) ? intval($_POST['subcategory_id']) : null,
        'specialization' => $_POST['specialization'] ?? '',
        'education' => $_POST['education'] ?? '',
        'experience_years' => $_POST['experience_years'] ?? '',
        'office_hours' => $_POST['office_hours'] ?? '',
        'block_id' => !empty($_POST['block_id']) ? intval($_POST['block_id']) : null,
        'address' => $_POST['address'] ?? '',
        'pincode' => $_POST['pincode'] ?? '',
        'bio' => $_POST['bio'] ?? '',
        'about' => $_POST['about'] ?? '',
        'profile_visibility' => $_POST['profile_visibility'] ?? 'PUBLIC',
        'mobile_visibility' => $_POST['mobile_visibility'] ?? 'PUBLIC',
        'email_visibility' => $_POST['email_visibility'] ?? 'PUBLIC',
        'address_visibility' => $_POST['address_visibility'] ?? 'PUBLIC',
        'linkedin' => $_POST['linkedin'] ?? '',
        'twitter' => $_POST['twitter'] ?? '',
        'facebook' => $_POST['facebook'] ?? '',
        'instagram' => $_POST['instagram'] ?? '',
        'google_maps_link' => $_POST['google_maps_link'] ?? '',
        'public_url' => $_POST['public_url'] ?? '',
        'languages' => $_POST['languages'] ?? ''
    ];

    if (!empty($_FILES['profile_image_file']['tmp_name'])) {
        $uploaded = uploadUserProfilePhoto($_FILES['profile_image_file'], $user['id']);
        if ($uploaded) {
            $post_data['profile_image'] = $uploaded;
        }
    }

    if (updateProfessionalUserProfile($user['id'], $post_data)) {
        $msg = "Professional profile updated successfully!";
        $msg_type = 'success';
        $user = getLoggedInUser(); // Refresh user data
    } else {
        $msg = $_SESSION['profile_update_error'] ?? "Failed to update profile details.";
        unset($_SESSION['profile_update_error']);
        $msg_type = 'danger';
    }
}

$blocks = getBlocks();
$all_categories = getCategoriesList();
$prof_subcategories = getProfessionalSubcategories();

$page_title = "Edit Profile & Settings – Saran Index";
$meta_description = "Edit your professional profile, contact details, business information, and privacy settings on Saran Index.";

require_once __DIR__ . '/includes/header.php';
?>

<style>
/* Matching index.php & dashboard.php Hero Theme */
.edit-profile-hero-banner {
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0F172A 100%);
    color: #ffffff;
    border-radius: 24px;
    padding: 35px 30px;
    box-shadow: 0 15px 35px rgba(15, 23, 42, 0.25);
    position: relative;
    overflow: hidden;
}
.edit-profile-hero-banner::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -15%;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.3) 0%, transparent 70%);
    pointer-events: none;
}
</style>

<div class="container py-4">
    <!-- Hero Banner Styled as index.php -->
    <div class="edit-profile-hero-banner mb-4">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 position-relative z-1">
            <div>
                <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-2 small shadow-xs">
                    <i class="bi bi-person-gear me-1"></i>Account Settings & Profile Management
                </span>
                <h1 class="display-6 fw-bold text-white mb-1 font-heading">Edit Professional Profile</h1>
                <p class="text-white-50 lead mb-0" style="font-size: 1.05rem; color: #cbd5e1 !important;">
                    Update your contact details, specialization, office location, and privacy settings on <strong>Saran Index</strong>.
                </p>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-outline-light btn-sm rounded-pill px-4 py-2 fw-semibold opacity-90 shadow-sm">
                    <i class="bi bi-arrow-left me-1.5"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show rounded-4 p-3.5 shadow-sm mb-4 small" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-6"></i><?php echo htmlspecialchars($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="edit-profile.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_profile">

        <div class="row g-4">
            <!-- Left Column: Avatar & Account Settings Card (4 Cols) -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white text-center">
                    <h5 class="fw-bold text-dark mb-3 font-heading border-bottom pb-2">
                        <i class="bi bi-person-bounding-box text-primary me-2"></i>Profile Avatar
                    </h5>
                    
                    <div class="mb-3 position-relative d-inline-block">
                        <?php if (!empty($user['profile_image']) && file_exists(__DIR__ . '/' . $user['profile_image'])): ?>
                            <img src="<?php echo sanitizeInput($user['profile_image']); ?>" alt="Profile Avatar" class="rounded-circle img-thumbnail shadow-sm mb-2" id="avatarPreview" style="width: 115px; height: 115px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold fs-1 shadow-sm mb-2" id="avatarPlaceholder" style="width: 115px; height: 115px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 text-start">
                        <label for="profile_image_file" class="form-label small fw-semibold">Upload Photo</label>
                        <input type="file" class="form-control form-control-sm rounded-3" id="profile_image_file" name="profile_image_file" accept="image/*">
                        <small class="text-muted extra-small d-block mt-1">JPG, PNG, WEBP (Max 60KB / Auto-Resized)</small>
                    </div>

                    <hr class="text-secondary opacity-25 my-3">

                    <div class="text-start">
                        <label class="form-label small fw-semibold text-muted mb-1">Registered Phone (Read Only)</label>
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text bg-light">+91</span>
                            <input type="text" class="form-control bg-light fw-medium" value="<?php echo sanitizeInput($user['mobile']); ?>" readonly>
                            <span class="input-group-text text-success bg-light"><i class="bi bi-patch-check-fill"></i></span>
                        </div>

                        <label for="username_handle" class="form-label small fw-semibold">Custom Handle (@username)</label>
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text bg-light">@</span>
                            <input type="text" class="form-control" id="username_handle" name="username_handle" value="<?php echo sanitizeInput(ltrim($user['username_handle'] ?? '', '@')); ?>">
                        </div>
                        <small class="text-muted extra-small d-block mb-3">Public Link: saranindex.com/@username</small>

                        <div class="bg-light p-3 rounded-3 border">
                            <h6 class="fw-bold text-dark mb-2.5 small"><i class="bi bi-shield-lock text-primary me-1.5"></i>Privacy Controls</h6>
                            <div class="mb-2.5">
                                <label class="form-label extra-small text-muted mb-1">Profile Visibility</label>
                                <select name="profile_visibility" class="form-select form-select-sm">
                                    <option value="PUBLIC" <?php echo ($user['profile_visibility'] ?? 'PUBLIC') === 'PUBLIC' ? 'selected' : ''; ?>>Public (Visible in Directory)</option>
                                    <option value="PRIVATE" <?php echo ($user['profile_visibility'] ?? '') === 'PRIVATE' ? 'selected' : ''; ?>>Private (Hidden from Search)</option>
                                </select>
                            </div>
                            <div class="mb-1">
                                <label class="form-label extra-small text-muted mb-1">Mobile Number Visibility</label>
                                <select name="mobile_visibility" class="form-select form-select-sm">
                                    <option value="PUBLIC" <?php echo ($user['mobile_visibility'] ?? 'PUBLIC') === 'PUBLIC' ? 'selected' : ''; ?>>Public (Show Number)</option>
                                    <option value="HIDDEN" <?php echo ($user['mobile_visibility'] ?? '') === 'HIDDEN' ? 'selected' : ''; ?>>Hidden (Masked for Visitors)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Main Profile Form Cards (8 Cols) -->
            <div class="col-lg-8">
                <!-- Card 1: Personal & Contact Details -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3 font-heading border-bottom pb-2">
                        <i class="bi bi-person-vcard text-primary me-2"></i>Personal & Contact Details
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo sanitizeInput($user['full_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label small fw-semibold">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo sanitizeInput($user['email']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="whatsapp" class="form-label small fw-semibold">WhatsApp Mobile</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">+91</span>
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?php echo sanitizeInput($user['whatsapp']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="pincode" class="form-label small fw-semibold">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo sanitizeInput($user['pincode']); ?>">
                        </div>
                    </div>
                </div>

                <!-- Card 2: Professional & Practice Details -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3 font-heading border-bottom pb-2">
                        <i class="bi bi-briefcase text-primary me-2"></i>Professional & Practice Details
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="business_name" class="form-label small fw-semibold">Firm / Clinic / Practice Name</label>
                            <input type="text" class="form-control" id="business_name" name="business_name" value="<?php echo sanitizeInput($user['business_name']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="designation" class="form-label small fw-semibold">Designation / Role Title</label>
                            <input type="text" class="form-control" id="designation" name="designation" value="<?php echo sanitizeInput($user['designation']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="subcategory_id" class="form-label small fw-semibold">Professional Subcategory / Specialization Role</label>
                            <select name="subcategory_id" class="form-select" id="subcategory_id">
                                <option value="">Select Professional Subcategory</option>
                                <?php foreach ($prof_subcategories as $scat): ?>
                                    <option value="<?php echo $scat['id']; ?>" <?php echo (intval($user['subcategory_id'] ?? 0) === intval($scat['id'])) ? 'selected' : ''; ?>>
                                        <?php echo sanitizeInput($scat['name']); ?><?php echo !empty($scat['category_name']) ? ' (' . sanitizeInput($scat['category_name']) . ')' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="specialization" class="form-label small fw-semibold">Specialization Keywords</label>
                            <input type="text" class="form-control" id="specialization" name="specialization" value="<?php echo sanitizeInput($user['specialization']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="education" class="form-label small fw-semibold">Education / Qualifications</label>
                            <input type="text" class="form-control" id="education" name="education" value="<?php echo sanitizeInput($user['education']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="experience_years" class="form-label small fw-semibold">Experience (Years)</label>
                            <input type="text" class="form-control" id="experience_years" name="experience_years" value="<?php echo sanitizeInput($user['experience_years']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="office_hours" class="form-label small fw-semibold">Office / Visiting Hours</label>
                            <input type="text" class="form-control" id="office_hours" name="office_hours" value="<?php echo sanitizeInput($user['office_hours']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="block_id" class="form-label small fw-semibold">Saran Block</label>
                            <select name="block_id" class="form-select" id="block_id">
                                <option value="">Select Block</option>
                                <?php foreach ($blocks as $blk): ?>
                                    <option value="<?php echo $blk['id']; ?>" <?php echo (intval($user['block_id'] ?? 0) === intval($blk['id'])) ? 'selected' : ''; ?>>
                                        <?php echo sanitizeInput($blk['block_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label small fw-semibold">Office Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2"><?php echo sanitizeInput($user['address']); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Bio & Detailed Summary -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3 font-heading border-bottom pb-2">
                        <i class="bi bi-file-text text-primary me-2"></i>Bio & About Summary
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="bio" class="form-label small fw-semibold">Short Bio (1-2 sentences)</label>
                            <input type="text" class="form-control" id="bio" name="bio" value="<?php echo sanitizeInput($user['bio']); ?>">
                        </div>
                        <div class="col-12">
                            <label for="about" class="form-label small fw-semibold">Detailed Professional Overview / About</label>
                            <textarea class="form-control" id="about" name="about" rows="4"><?php echo sanitizeInput($user['about'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Paid Profile Features & Social Links (Gold & Platinum Members) -->
                <?php 
                $is_paid_profile = isset($user['plan_type']) && in_array($user['plan_type'], ['GOLD', 'PLATINUM']);
                ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                        <h5 class="fw-bold text-dark mb-0 font-heading">
                            <i class="bi bi-crown-fill text-warning me-2"></i>Paid VIP Profile Features & Links
                        </h5>
                        <?php if ($is_paid_profile): ?>
                            <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill">
                                <i class="bi bi-patch-check-fill me-1"></i><?php echo sanitizeInput($user['plan_type']); ?> Active
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary fw-semibold px-3 py-1 rounded-pill">
                                <i class="bi bi-lock-fill me-1"></i>Unlocked with Paid Membership
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($is_paid_profile): ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="public_url" class="form-label small fw-semibold">Website / Portfolio Link</label>
                                <input type="url" class="form-control" id="public_url" name="public_url" value="<?php echo sanitizeInput($user['public_url'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="google_maps_link" class="form-label small fw-semibold">Google Maps Office Link</label>
                                <input type="url" class="form-control" id="google_maps_link" name="google_maps_link" value="<?php echo sanitizeInput($user['google_maps_link'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="languages" class="form-label small fw-semibold">Languages Spoken</label>
                                <input type="text" class="form-control" id="languages" name="languages" value="<?php echo sanitizeInput($user['languages'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="linkedin" class="form-label small fw-semibold">LinkedIn Profile URL</label>
                                <input type="url" class="form-control" id="linkedin" name="linkedin" value="<?php echo sanitizeInput($user['linkedin'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="facebook" class="form-label small fw-semibold">Facebook Page URL</label>
                                <input type="url" class="form-control" id="facebook" name="facebook" value="<?php echo sanitizeInput($user['facebook'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="instagram" class="form-label small fw-semibold">Instagram Profile URL</label>
                                <input type="text" class="form-control" id="instagram" name="instagram" value="<?php echo sanitizeInput($user['instagram'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="twitter" class="form-label small fw-semibold">Twitter / X Handle</label>
                                <input type="text" class="form-control" id="twitter" name="twitter" value="<?php echo sanitizeInput($user['twitter'] ?? ''); ?>">
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-light p-4 rounded-3 text-center border">
                            <i class="bi bi-crown-fill text-warning display-5 mb-2 d-block"></i>
                            <h6 class="fw-bold text-dark mb-1">Upgrade Profile to Unlock VIP Links & Google Maps</h6>
                            <p class="text-muted small mx-auto mb-3" style="max-width: 450px;">
                                Gold and VIP Platinum profile members can add Google Maps location link, website portfolio, languages, social media profiles, and display the official verified badge on their public page.
                            </p>
                            <a href="dashboard.php" class="btn btn-warning rounded-pill px-4 py-2 fw-bold text-dark shadow-sm">
                                <i class="bi bi-lightning-charge-fill me-1 text-danger"></i>Upgrade Profile Plan
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons Bar -->
                <div class="d-flex align-items-center justify-content-between pt-2">
                    <a href="dashboard.php" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2.5 fw-bold shadow-sm">
                        <i class="bi bi-check-circle-fill me-1.5"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

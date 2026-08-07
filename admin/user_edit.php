<?php
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($user_id <= 0) {
    header("Location: users.php");
    exit;
}

$header_title = "Edit User Account #{$user_id}";
require_once __DIR__ . '/includes/header.php';

$error = '';
$success = '';

$user = getUserById($user_id);
if (!$user) {
    echo '<div class="alert alert-danger my-4">User with ID #' . $user_id . ' not found. <a href="users.php">Back to Users</a></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Process Form POST Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_data = [
        'full_name' => sanitizeInput($_POST['full_name'] ?? ''),
        'username_handle' => sanitizeInput($_POST['username_handle'] ?? ''),
        'mobile' => sanitizeInput($_POST['mobile'] ?? ''),
        'whatsapp' => sanitizeInput($_POST['whatsapp'] ?? ''),
        'email' => sanitizeInput($_POST['email'] ?? ''),
        'business_name' => sanitizeInput($_POST['business_name'] ?? ''),
        'designation' => sanitizeInput($_POST['designation'] ?? ''),
        'profession_category' => sanitizeInput($_POST['profession_category'] ?? ''),
        'category_id' => !empty($_POST['category_id']) ? intval($_POST['category_id']) : null,
        'subcategory_id' => !empty($_POST['subcategory_id']) ? intval($_POST['subcategory_id']) : null,
        'specialization' => sanitizeInput($_POST['specialization'] ?? ''),
        'education' => sanitizeInput($_POST['education'] ?? ''),
        'experience_years' => sanitizeInput($_POST['experience_years'] ?? ''),
        'office_hours' => sanitizeInput($_POST['office_hours'] ?? ''),
        'block_id' => !empty($_POST['block_id']) ? intval($_POST['block_id']) : null,
        'village_id' => !empty($_POST['village_id']) ? intval($_POST['village_id']) : null,
        'address' => sanitizeInput($_POST['address'] ?? ''),
        'pincode' => sanitizeInput($_POST['pincode'] ?? '841301'),
        'bio' => sanitizeInput($_POST['bio'] ?? ''),
        'about' => sanitizeInput($_POST['about'] ?? ''),
        'password' => trim($_POST['password'] ?? ''),
        'status' => sanitizeInput($_POST['status'] ?? 'ACTIVE'),
        'type' => sanitizeInput($_POST['type'] ?? 'USER'),
        'profile_visibility' => sanitizeInput($_POST['profile_visibility'] ?? 'PUBLIC'),
        'mobile_status' => isset($_POST['mobile_status']) && $_POST['mobile_status'] === 'VERIFIED' ? 'VERIFIED' : 'UNVERIFIED',
        'email_status' => isset($_POST['email_status']) && $_POST['email_status'] === 'VERIFIED' ? 'VERIFIED' : 'UNVERIFIED'
    ];

    if (!empty($_FILES['profile_image_file']['tmp_name'])) {
        $uploaded = uploadUserProfilePhoto($_FILES['profile_image_file'], $user_id);
        if ($uploaded) {
            $post_data['profile_image'] = $uploaded;
        }
    }

    if (empty($post_data['full_name']) || empty($post_data['mobile'])) {
        $error = "Please fill in all required fields: Full Name and Mobile Number.";
        $user = array_merge($user, $post_data);
    } else {
        if (saveUserFromAdmin($post_data, $user_id)) {
            $success = "User account #{$user_id} updated successfully!";
            $user = getUserById($user_id);
        } else {
            $error = "Failed to update user account in database.";
            $user = array_merge($user, $post_data);
        }
    }
}

$blocks_list = getBlocks();
$all_categories = getCategoriesList();
?>

<!-- Page Header & Action Bar -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Admin</a></li>
                <li class="breadcrumb-item"><a href="users.php" class="text-decoration-none text-muted">User Accounts</a></li>
                <li class="breadcrumb-item active fw-semibold text-primary" aria-current="page">Edit User #<?php echo $user_id; ?></li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <h4 class="fw-bold mb-0 text-dark">Edit User Account: <?php echo sanitizeInput($user['full_name']); ?></h4>
            <?php if (($user['status'] ?? 'ACTIVE') === 'ACTIVE'): ?>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small"><i class="bi bi-check-circle-fill me-1"></i>Active</span>
            <?php else: ?>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 small"><i class="bi bi-slash-circle-fill me-1"></i>Suspended</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="d-flex gap-2">
        <?php if (!empty($user['username_handle'])): ?>
            <a href="../@<?php echo sanitizeInput(ltrim($user['username_handle'], '@')); ?>" target="_blank" class="btn btn-outline-primary fw-bold btn-sm px-3 rounded-3 shadow-sm">
                <i class="bi bi-person-badge me-1"></i> View Public Profile (@<?php echo sanitizeInput(ltrim($user['username_handle'], '@')); ?>)
            </a>
        <?php endif; ?>
        <a href="users.php?action=login_as&id=<?php echo $user_id; ?>" class="btn btn-warning text-dark fw-bold btn-sm px-3 rounded-3 shadow-sm" onclick="return confirm('Do you want to log in as user <?php echo sanitizeInput(addslashes($user['full_name'])); ?>?');">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login As This User
        </a>
        <a href="users.php" class="btn btn-outline-secondary btn-sm px-3 rounded-3 fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Back to Users
        </a>
    </div>
</div>

<!-- Alert Notifications -->
<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($error); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($success); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="user_edit.php?id=<?php echo $user_id; ?>" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <!-- Main Form Column (8 columns) -->
        <div class="col-12 col-lg-8">
            
            <!-- Basic Information Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-person-lines-fill text-primary me-2"></i>Personal Info & Custom Handle</h6>
                
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="full_name" class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo sanitizeInput($user['full_name']); ?>" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="username_handle" class="form-label small fw-semibold">Custom Profile Handle (@username)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white fw-bold">@</span>
                            <input type="text" class="form-control" id="username_handle" name="username_handle" value="<?php echo sanitizeInput(ltrim($user['username_handle'] ?? '', '@')); ?>" placeholder="e.g. KumarGaurav">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="profile_image_file" class="form-label small fw-semibold">Profile Photo / Avatar Upload</label>
                        <input type="file" class="form-control" id="profile_image_file" name="profile_image_file" accept="image/*">
                        <?php if (!empty($user['profile_image'])): ?>
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img src="../<?php echo sanitizeInput($user['profile_image']); ?>" alt="Profile Photo" class="rounded-circle img-thumbnail" style="width: 45px; height: 45px; object-fit: cover;">
                                <small class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>Current photo active</small>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="mobile" class="form-label small fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo sanitizeInput($user['mobile']); ?>" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="whatsapp" class="form-label small fw-semibold">WhatsApp Number</label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?php echo sanitizeInput($user['whatsapp']); ?>">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label small fw-semibold">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo sanitizeInput($user['email']); ?>">
                    </div>
                </div>
            </div>

            <!-- Business & Professional Fields Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-briefcase-fill text-primary me-2"></i>Professional Qualifications & Practice Fields</h6>
                
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label for="designation" class="form-label small fw-semibold">Designation / Role Title</label>
                        <input type="text" class="form-control" id="designation" name="designation" value="<?php echo sanitizeInput($user['designation']); ?>" placeholder="e.g. Senior Advocate, Medical Specialist, Civil Engineer">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="business_name" class="form-label small fw-semibold">Business / Shop / Organization Name</label>
                        <input type="text" class="form-control" id="business_name" name="business_name" value="<?php echo sanitizeInput($user['business_name']); ?>" placeholder="e.g. Saran Law Chambers, City Clinic">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="category_id" class="form-label small fw-semibold">Profession Main Category</label>
                        <select class="form-select" id="user_category_id" name="category_id">
                            <option value="">-- Select Category --</option>
                            <?php foreach ($all_categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($user['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo sanitizeInput($cat['name']); ?> (ID: #<?php echo $cat['id']; ?>) <?php echo !empty($cat['hindi_name']) ? '('.sanitizeInput($cat['hindi_name']).')' : ''; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="subcategory_id" class="form-label small fw-semibold">Sub-Category</label>
                        <select class="form-select" id="user_subcategory_id" name="subcategory_id">
                            <option value="">-- Select Sub-Category --</option>
                            <?php if (!empty($user['subcategory_id'])): ?>
                                <option value="<?php echo $user['subcategory_id']; ?>" selected><?php echo sanitizeInput($user['subcategory_name'] ?? 'Current Sub-Category'); ?> (ID: #<?php echo $user['subcategory_id']; ?>)</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="specialization" class="form-label small fw-semibold">Specialization & Expertise</label>
                        <input type="text" class="form-control" id="specialization" name="specialization" value="<?php echo sanitizeInput($user['specialization'] ?? ''); ?>" placeholder="e.g. Criminal Defense, Cardiology, Income Tax">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="education" class="form-label small fw-semibold">Educational Degree / Qualification</label>
                        <input type="text" class="form-control" id="education" name="education" value="<?php echo sanitizeInput($user['education'] ?? ''); ?>" placeholder="e.g. LL.B (JPU Chapra), MBBS, M.Tech, CA">
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="experience_years" class="form-label small fw-semibold">Years of Experience</label>
                        <input type="text" class="form-control" id="experience_years" name="experience_years" value="<?php echo sanitizeInput($user['experience_years'] ?? ''); ?>" placeholder="e.g. 10 Years">
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="office_hours" class="form-label small fw-semibold">Office Timings</label>
                        <input type="text" class="form-control" id="office_hours" name="office_hours" value="<?php echo sanitizeInput($user['office_hours'] ?? ''); ?>" placeholder="e.g. 9 AM - 7 PM">
                    </div>
                </div>

                <div>
                    <label for="about" class="form-label small fw-semibold">About / Professional Bio Summary</label>
                    <textarea class="form-control" id="about" name="about" rows="3" placeholder="Detailed professional background, achievements, court/clinic locations, and services..."><?php echo sanitizeInput($user['about'] ?: ($user['bio'] ?? '')); ?></textarea>
                </div>
            </div>

            <!-- District Geographic Location Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Location & Address</h6>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="block_id" class="form-label small fw-semibold">Block Location (Saran District)</label>
                        <select class="form-select" id="block_id" name="block_id">
                            <option value="">Select Block</option>
                            <?php foreach ($blocks_list as $blk): ?>
                                <option value="<?php echo $blk['id']; ?>" <?php echo $user['block_id'] == $blk['id'] ? 'selected' : ''; ?>>
                                    <?php echo sanitizeInput($blk['block_name']); ?> (<?php echo sanitizeInput($blk['hindi_name']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="village_id" class="form-label small fw-semibold">Village / Mauja Location</label>
                        <select class="form-select" id="village_id" name="village_id">
                            <option value="">Choose Village / Mauja (Optional)</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-8">
                        <label for="address" class="form-label small fw-semibold">Street / Full Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo sanitizeInput($user['address']); ?>" placeholder="e.g. Main Road, Chapra">
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="pincode" class="form-label small fw-semibold">Pincode</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo sanitizeInput($user['pincode']); ?>" placeholder="841301">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column Sidebar (4 columns) -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 position-sticky" style="top: 20px;">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-shield-lock-fill text-primary me-2"></i>Account Controls & Verifications</h6>

                <div class="mb-3">
                    <label for="status" class="form-label small fw-semibold">Account Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="ACTIVE" <?php echo ($user['status'] ?? 'ACTIVE') === 'ACTIVE' ? 'selected' : ''; ?>>🟢 ACTIVE</option>
                        <option value="INACTIVE" <?php echo ($user['status'] ?? '') === 'INACTIVE' ? 'selected' : ''; ?>>🟡 INACTIVE</option>
                        <option value="SUSPENDED" <?php echo ($user['status'] ?? '') === 'SUSPENDED' ? 'selected' : ''; ?>>🔴 SUSPENDED</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label small fw-semibold">User Role / Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="USER" <?php echo ($user['type'] ?? 'USER') === 'USER' ? 'selected' : ''; ?>>USER (Standard Community User)</option>
                        <option value="AGENT" <?php echo ($user['type'] ?? '') === 'AGENT' ? 'selected' : ''; ?>>AGENT (Field Agent / Listing Partner)</option>
                        <option value="ADMIN" <?php echo ($user['type'] ?? '') === 'ADMIN' ? 'selected' : ''; ?>>ADMIN (Directory Admin)</option>
                    </select>
                </div>

                <hr class="my-3 text-muted">

                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-patch-check text-success me-2"></i>Verifications</h6>

                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="mobile_status" name="mobile_status" value="VERIFIED" <?php echo ($user['mobile_status'] ?? 'UNVERIFIED') === 'VERIFIED' ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-semibold small text-dark" for="mobile_status">
                        <i class="bi bi-patch-check-fill text-success me-1"></i> Mobile OTP Verified
                    </label>
                </div>

                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="email_status" name="email_status" value="VERIFIED" <?php echo ($user['email_status'] ?? 'UNVERIFIED') === 'VERIFIED' ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-semibold small text-dark" for="email_status">
                        <i class="bi bi-check-circle-fill text-success me-1"></i> Email Address Verified
                    </label>
                </div>

                <hr class="my-3 text-muted">

                <div class="mb-4">
                    <label for="password" class="form-label small fw-semibold">Reset Password (Optional)</label>
                    <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Leave blank to keep existing password" minlength="6">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-bold py-2.5 shadow-sm rounded-3">
                        <i class="bi bi-check-circle-fill me-1"></i> Save User Changes
                    </button>

                    <a href="users.php?action=login_as&id=<?php echo $user_id; ?>" class="btn btn-warning text-dark fw-bold py-2 rounded-3" onclick="return confirm('Do you want to log in as user <?php echo sanitizeInput(addslashes($user['full_name'])); ?>?');">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login As This User
                    </a>

                    <a href="users.php" class="btn btn-light border fw-medium rounded-3">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockSelect = document.getElementById('block_id');
    const villageSelect = document.getElementById('village_id');
    const currentVillageId = <?php echo !empty($user['village_id']) ? intval($user['village_id']) : 0; ?>;

    function loadVillages(blockId, selectedVillageId) {
        if (!blockId) {
            villageSelect.innerHTML = '<option value="">Choose Village / Mauja (Optional)</option>';
            return;
        }
        fetch(`../api/villages_api.php?block_id=${blockId}`)
            .then(res => res.json())
            .then(data => {
                villageSelect.innerHTML = '<option value="">Choose Village / Mauja (Optional)</option>';
                const villages = data.villages || data;
                if (Array.isArray(villages) && villages.length > 0) {
                    villages.forEach(v => {
                        const opt = document.createElement('option');
                        opt.value = v.code || v.id || v.mauja_code;
                        opt.textContent = v.name_hindi ? `${v.display_name || v.name}` : (v.name || v.mauja_english);
                        if (selectedVillageId && (parseInt(v.id) === parseInt(selectedVillageId) || v.code == selectedVillageId)) {
                            opt.selected = true;
                        }
                        villageSelect.appendChild(opt);
                    });
                }
            })
            .catch(err => {
                console.error("Villages fetch error:", err);
                villageSelect.innerHTML = '<option value="">Choose Village / Mauja (Optional)</option>';
            });
    }

    if (blockSelect) {
        loadVillages(blockSelect.value, currentVillageId);
        blockSelect.addEventListener('change', function() {
            loadVillages(this.value, 0);
        });
    }

    const catSelect = document.getElementById('user_category_id');
    const currentSubId = "<?php echo $user['subcategory_id'] ?? ''; ?>";
    if (catSelect && catSelect.value) {
        loadUserSubcategories(catSelect.value, currentSubId);
    }
});

function loadUserSubcategories(catId, selectedSubId = null) {
    const subSelect = document.getElementById('user_subcategory_id');
    if (!subSelect) return;
    subSelect.innerHTML = '<option value="">-- Loading Sub-Categories... --</option>';
    if (!catId) {
        subSelect.innerHTML = '<option value="">-- Select Sub-Category --</option>';
        return;
    }

    fetch('../api/subcategories_api.php?category_id=' + encodeURIComponent(catId))
        .then(response => response.json())
        .then(data => {
            subSelect.innerHTML = '<option value="">-- Select Sub-Category --</option>';
            if (data && data.subcategories && Array.isArray(data.subcategories)) {
                data.subcategories.forEach(sub => {
                    const opt = document.createElement('option');
                    opt.value = sub.id;
                    opt.textContent = sub.name + ' (ID: #' + sub.id + ')' + (sub.hindi_name ? ' (' + sub.hindi_name + ')' : '');
                    if (selectedSubId && sub.id == selectedSubId) {
                        opt.selected = true;
                    }
                    subSelect.appendChild(opt);
                });
            }
        })
        .catch(err => {
            console.error('Failed to load subcategories:', err);
            subSelect.innerHTML = '<option value="">-- Select Sub-Category --</option>';
        });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

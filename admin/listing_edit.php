<?php
$editing_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$header_title = $editing_id ? "Edit Listing #{$editing_id}" : "Add New Directory Listing";
require_once __DIR__ . '/includes/header.php';

$error = '';
$success = '';

// Complete structure covering all columns in listings table
$listing = [
    'user_id' => null,
    'category_id' => 1,
    'subcategory_id' => null,
    'block_id' => 1,
    'panchayat_id' => null,
    'village_id' => null,
    'title' => '',
    'hindi_title' => '',
    'slug' => '',
    'contact_person' => '',
    'mobile' => '',
    'mobile_visibility' => 'PUBLIC',
    'whatsapp' => '',
    'email' => '',
    'email_visibility' => 'PUBLIC',
    'website' => '',
    'address' => '',
    'pincode' => '841301',
    'map_link' => '',
    'business_hours' => '9:00 AM - 8:00 PM',
    'services' => '',
    'products' => '',
    'gst_no' => '',
    'udyam_no' => '',
    'cin_no' => '',
    'local_reg_no' => '',
    'description' => '',
    'cover_image' => '',
    'is_verified' => 'NO',
    'is_featured' => 'NO',
    'status' => 'ACTIVE',
    'plan_type' => 'FREE',
    'plan_expires_at' => null,
    'view_count' => 0,
    'star_rating' => 5.00,
    'source' => 'manual',
    'source_id' => '',
    'created_at' => date('Y-m-d H:i:s')
];

// Load existing data if editing
if ($editing_id) {
    $existing = getListingById($editing_id);
    if ($existing) {
        $listing = array_merge($listing, $existing);
    } else {
        $error = "Listing with ID #{$editing_id} not found.";
    }
}

// Process POST Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle File Upload for cover image if provided
    $cover_image_url = sanitizeInput($_POST['cover_image'] ?? '');
    if (isset($_FILES['cover_image_file']) && $_FILES['cover_image_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['cover_image_file'];
        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_exts)) {
            $upload_dir = __DIR__ . '/../uploads/listings/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $filename = 'listing_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $target_filepath = $upload_dir . $filename;
            if (move_uploaded_file($file['tmp_name'], $target_filepath)) {
                $cover_image_url = 'uploads/listings/' . $filename;
            } else {
                $error = "Failed to upload image file.";
            }
        } else {
            $error = "Invalid image file type. Allowed formats: JPG, PNG, WEBP, GIF.";
        }
    }

    $post_data = [
        'user_id' => !empty($_POST['user_id']) ? intval($_POST['user_id']) : null,
        'category_id' => intval($_POST['category_id'] ?? 1),
        'subcategory_id' => !empty($_POST['subcategory_id']) ? intval($_POST['subcategory_id']) : null,
        'block_id' => !empty($_POST['block_id']) ? intval($_POST['block_id']) : null,
        'panchayat_id' => !empty($_POST['panchayat_id']) ? intval($_POST['panchayat_id']) : null,
        'village_id' => !empty($_POST['village_id']) ? intval($_POST['village_id']) : null,
        'title' => sanitizeInput($_POST['title'] ?? ''),
        'hindi_title' => sanitizeInput($_POST['hindi_title'] ?? ''),
        'slug' => sanitizeInput($_POST['slug'] ?? ''),
        'contact_person' => sanitizeInput($_POST['contact_person'] ?? ''),
        'mobile' => sanitizeInput($_POST['mobile'] ?? ''),
        'mobile_visibility' => sanitizeInput($_POST['mobile_visibility'] ?? 'PUBLIC'),
        'whatsapp' => sanitizeInput($_POST['whatsapp'] ?? ''),
        'email' => sanitizeInput($_POST['email'] ?? ''),
        'email_visibility' => sanitizeInput($_POST['email_visibility'] ?? 'PUBLIC'),
        'website' => sanitizeInput($_POST['website'] ?? ''),
        'address' => sanitizeInput($_POST['address'] ?? ''),
        'pincode' => sanitizeInput($_POST['pincode'] ?? '841301'),
        'map_link' => sanitizeInput($_POST['map_link'] ?? ''),
        'business_hours' => sanitizeInput($_POST['business_hours'] ?? '9:00 AM - 8:00 PM'),
        'services' => sanitizeInput($_POST['services'] ?? ''),
        'products' => sanitizeInput($_POST['products'] ?? ''),
        'gst_no' => sanitizeInput($_POST['gst_no'] ?? ''),
        'udyam_no' => sanitizeInput($_POST['udyam_no'] ?? ''),
        'cin_no' => sanitizeInput($_POST['cin_no'] ?? ''),
        'local_reg_no' => sanitizeInput($_POST['local_reg_no'] ?? ''),
        'description' => sanitizeInput($_POST['description'] ?? ''),
        'cover_image' => $cover_image_url,
        'is_verified' => isset($_POST['is_verified']) && $_POST['is_verified'] === 'YES' ? 'YES' : 'NO',
        'is_featured' => isset($_POST['is_featured']) && $_POST['is_featured'] === 'YES' ? 'YES' : 'NO',
        'status' => sanitizeInput($_POST['status'] ?? 'ACTIVE'),
        'plan_type' => sanitizeInput($_POST['plan_type'] ?? 'FREE'),
        'plan_expires_at' => !empty($_POST['plan_expires_at']) ? sanitizeInput($_POST['plan_expires_at']) : null,
        'view_count' => isset($_POST['view_count']) ? max(0, intval($_POST['view_count'])) : ($listing['view_count'] ?? 0),
        'star_rating' => isset($_POST['star_rating']) ? max(1.0, min(5.0, floatval($_POST['star_rating']))) : 5.00,
        'source' => sanitizeInput($_POST['source'] ?? 'manual'),
        'source_id' => sanitizeInput($_POST['source_id'] ?? ''),
        'created_at' => !empty($_POST['created_at']) ? sanitizeInput($_POST['created_at']) : ($listing['created_at'] ?? date('Y-m-d H:i:s'))
    ];

    if ($post_data['status'] === 'ACTIVE' && !empty($post_data['user_id'])) {
        $checkData = [
            'id' => $editing_id,
            'user_id' => $post_data['user_id'],
            'mobile' => $post_data['mobile'] ?? ($listing['mobile'] ?? '')
        ];
        $mob_err = '';
        if (!isListingUserMobileActive($checkData, $mob_err)) {
            $error = "Cannot set status to ACTIVE: " . $mob_err;
            $post_data['status'] = 'PENDING';
            $listing = array_merge($listing, $post_data);
        }
    }

    if (empty($error)) {
        if (empty($post_data['title'])) {
            $error = "Listing Title is mandatory.";
            $listing = array_merge($listing, $post_data);
        } else {
            // Duplicate check only if mobile is provided and not a dummy number
            $cleanMob = preg_replace('/[^0-9]/', '', $post_data['mobile'] ?? '');
            if (!empty($cleanMob) && $cleanMob !== '0000000000') {
                $dup = checkDuplicateListing($post_data['title'], $post_data['mobile'], $editing_id);
                if ($dup) {
                    $error = "Duplicate Entry Detected: A listing with the title '" . htmlspecialchars($post_data['title']) . "' and mobile number '" . htmlspecialchars($post_data['mobile']) . "' already exists in the directory (Listing ID #" . $dup['id'] . ").";
                    $listing = array_merge($listing, $post_data);
                }
            }

            if (empty($error)) {
                if (saveListing($post_data, $editing_id)) {
                    $success = $editing_id ? "Listing #{$editing_id} updated successfully with all fields!" : "New directory listing created successfully!";
                    if (!$editing_id) {
                        // Reset form fields for fresh entry
                        $listing['title'] = '';
                        $listing['hindi_title'] = '';
                        $listing['mobile'] = '';
                        $listing['contact_person'] = '';
                        $listing['address'] = '';
                        $listing['services'] = '';
                        $listing['products'] = '';
                        $listing['description'] = '';
                        $listing['cover_image'] = '';
                    } else {
                        // Refresh listing array with updated data
                        $updated = getListingById($editing_id);
                        if ($updated) {
                            $listing = array_merge($listing, $updated);
                        }
                    }
                } else {
                    $error = "Database save operation failed. Please ensure the custom URL slug is unique.";
                    $listing = array_merge($listing, $post_data);
                }
            }
        }
    } else {
        $listing = array_merge($listing, $post_data);
    }
}

$categories = getAllAdminCategories();
$blocks = getBlocks();
$initial_subcategories = [];
if (!empty($listing['category_id'])) {
    $initial_subcategories = getSubcategoriesByCategoryId($listing['category_id']);
}
$initial_panchayats = [];
if (!empty($listing['block_id'])) {
    $initial_panchayats = getPanchayats($listing['block_id']);
}

// Fetch owner user details if linked
$owner_user = null;
if (!empty($listing['user_id'])) {
    $owner_user = getUserById($listing['user_id']);
}
?>

<!-- Top Page Banner & Actions -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Admin</a></li>
                <li class="breadcrumb-item"><a href="listings.php" class="text-decoration-none text-muted">Directory Listings</a></li>
                <li class="breadcrumb-item active fw-semibold text-primary" aria-current="page"><?php echo $editing_id ? "Edit Listing #{$editing_id}" : "Add New Listing"; ?></li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <h4 class="fw-bold mb-0 text-dark"><?php echo $editing_id ? "Edit Listing #{$editing_id}" : "Add New Listing"; ?></h4>
            <?php if ($editing_id): ?>
                <?php
                    $st = strtoupper($listing['status'] ?? 'ACTIVE');
                    if ($st === 'ACTIVE') {
                        echo '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small"><i class="bi bi-check-circle-fill me-1"></i>Active</span>';
                    } elseif ($st === 'PENDING') {
                        echo '<span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 small"><i class="bi bi-clock-fill me-1"></i>Pending Review</span>';
                    } else {
                        echo '<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 small"><i class="bi bi-x-circle-fill me-1"></i>Rejected</span>';
                    }
                ?>
                <?php if (($listing['is_verified'] ?? '') === 'YES'): ?>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 small"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                <?php endif; ?>
                <?php if (($listing['is_featured'] ?? '') === 'YES'): ?>
                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2.5 py-1 small"><i class="bi bi-star-fill text-warning me-1"></i>Featured</span>
                <?php endif; ?>
                <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1 small"><i class="bi bi-award me-1"></i><?php echo htmlspecialchars($listing['plan_type'] ?? 'FREE'); ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <?php if ($editing_id && !empty($listing['slug'])): ?>
            <a href="../<?php echo getListingUrl($listing['slug']); ?>" target="_blank" class="btn btn-outline-primary btn-sm px-3 rounded-3 fw-semibold">
                <i class="bi bi-box-arrow-up-right me-1"></i> View Public Profile
            </a>
        <?php endif; ?>
        <a href="listings.php" class="btn btn-outline-secondary btn-sm px-3 rounded-3 fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Back to Listings
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

<form action="listing_edit.php<?php echo $editing_id ? '?id='.$editing_id : ''; ?>" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <!-- Main Details Column (8 columns) -->
        <div class="col-12 col-lg-8">
            
            <!-- SECTION 1: Basic Information Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-info-circle text-primary me-2"></i>1. Basic Listing Information</h6>
                    <span class="badge bg-light text-muted border small">Section 1</span>
                </div>
                
                <div class="row g-3">
                    <!-- Title (English) -->
                    <div class="col-12 col-md-8">
                        <label for="title" class="form-label small fw-semibold">Listing Title (English) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg fs-6" id="title" name="title" value="<?php echo sanitizeInput($listing['title']); ?>" required>
                    </div>

                    <!-- Owner User ID -->
                    <div class="col-12 col-md-4">
                        <label for="user_id" class="form-label small fw-semibold d-flex align-items-center justify-content-between">
                            <span>Owner User Account ID</span>
                            <?php if ($owner_user): ?>
                                <a href="user_edit.php?id=<?php echo $owner_user['id']; ?>" target="_blank" class="text-primary small text-decoration-none">View User <i class="bi bi-box-arrow-up-right"></i></a>
                            <?php endif; ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person-fill text-muted"></i></span>
                            <input type="number" class="form-control" id="user_id" name="user_id" placeholder="Optional User ID" value="<?php echo !empty($listing['user_id']) ? intval($listing['user_id']) : ''; ?>">
                        </div>
                        <?php if ($owner_user): ?>
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                Linked: <strong><?php echo sanitizeInput($owner_user['full_name']); ?></strong> (<?php echo sanitizeInput($owner_user['mobile']); ?>)
                            </small>
                        <?php else: ?>
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Leave empty for guest / public institutional listing.</small>
                        <?php endif; ?>
                    </div>

                    <!-- Hindi Title -->
                    <div class="col-12 col-md-6">
                        <label for="hindi_title" class="form-label small fw-semibold">Hindi Name (हिंदी नाम)</label>
                        <input type="text" class="form-control" id="hindi_title" name="hindi_title" value="<?php echo sanitizeInput($listing['hindi_title']); ?>">
                    </div>

                    <!-- Custom URL Slug -->
                    <div class="col-12 col-md-6">
                        <label for="slug" class="form-label small fw-semibold">Custom URL Slug</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted small">listing/</span>
                            <input type="text" class="form-control" id="slug" name="slug" value="<?php echo sanitizeInput($listing['slug']); ?>">
                            <?php if (!empty($listing['slug'])): 
                                $publicUrl = rtrim(BASE_URL, '/') . '/' . getListingUrl($listing['slug']);
                            ?>
                                <button type="button" class="btn btn-outline-secondary copy-url-btn" data-url="<?php echo $publicUrl; ?>" title="Copy Full Public URL">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                                <a href="../<?php echo getListingUrl($listing['slug']); ?>" target="_blank" class="btn btn-outline-primary" title="Open Public Page">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Contact & Communication Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-telephone text-primary me-2"></i>2. Contact Details & Visibility</h6>
                    <span class="badge bg-light text-muted border small">Section 2</span>
                </div>
                
                <div class="row g-3">
                    <!-- Mobile Number -->
                    <div class="col-12 col-md-4">
                        <label for="mobile" class="form-label small fw-semibold">Calling Mobile Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-telephone-fill text-muted"></i></span>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo sanitizeInput($listing['mobile']); ?>" placeholder="10-digit mobile">
                        </div>
                    </div>

                    <!-- Mobile Visibility -->
                    <div class="col-12 col-md-4">
                        <label for="mobile_visibility" class="form-label small fw-semibold">Mobile Visibility</label>
                        <select class="form-select" id="mobile_visibility" name="mobile_visibility">
                            <option value="PUBLIC" <?php echo ($listing['mobile_visibility'] ?? '') === 'PUBLIC' ? 'selected' : ''; ?>>🌐 PUBLIC (Visible to Everyone)</option>
                            <option value="REGISTERED" <?php echo ($listing['mobile_visibility'] ?? '') === 'REGISTERED' ? 'selected' : ''; ?>>👤 REGISTERED (Logged-in Users Only)</option>
                            <option value="HIDDEN" <?php echo ($listing['mobile_visibility'] ?? '') === 'HIDDEN' ? 'selected' : ''; ?>>🔒 HIDDEN (Private)</option>
                        </select>
                    </div>

                    <!-- WhatsApp Number -->
                    <div class="col-12 col-md-4">
                        <label for="whatsapp" class="form-label small fw-semibold">WhatsApp Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-success"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?php echo sanitizeInput($listing['whatsapp']); ?>" placeholder="WhatsApp number">
                        </div>
                    </div>

                    <!-- Contact Person -->
                    <div class="col-12 col-md-4">
                        <label for="contact_person" class="form-label small fw-semibold">Contact Person / Owner Name</label>
                        <input type="text" class="form-control" id="contact_person" name="contact_person" value="<?php echo sanitizeInput($listing['contact_person']); ?>">
                    </div>

                    <!-- Email Address -->
                    <div class="col-12 col-md-4">
                        <label for="email" class="form-label small fw-semibold">Official Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo sanitizeInput($listing['email']); ?>" placeholder="info@example.com">
                    </div>

                    <!-- Email Visibility -->
                    <div class="col-12 col-md-4">
                        <label for="email_visibility" class="form-label small fw-semibold">Email Visibility</label>
                        <select class="form-select" id="email_visibility" name="email_visibility">
                            <option value="PUBLIC" <?php echo ($listing['email_visibility'] ?? '') === 'PUBLIC' ? 'selected' : ''; ?>>🌐 PUBLIC (Visible to Everyone)</option>
                            <option value="REGISTERED" <?php echo ($listing['email_visibility'] ?? '') === 'REGISTERED' ? 'selected' : ''; ?>>👤 REGISTERED (Logged-in Only)</option>
                            <option value="HIDDEN" <?php echo ($listing['email_visibility'] ?? '') === 'HIDDEN' ? 'selected' : ''; ?>>🔒 HIDDEN (Private)</option>
                        </select>
                    </div>

                    <!-- Website URL -->
                    <div class="col-12 col-md-6">
                        <label for="website" class="form-label small fw-semibold">Website URL</label>
                        <input type="url" class="form-control" id="website" name="website" value="<?php echo sanitizeInput($listing['website']); ?>" placeholder="https://example.com">
                    </div>

                    <!-- Business / Opening Hours -->
                    <div class="col-12 col-md-6">
                        <label for="business_hours" class="form-label small fw-semibold">Business / Opening Hours</label>
                        <input type="text" class="form-control" id="business_hours" name="business_hours" value="<?php echo sanitizeInput($listing['business_hours']); ?>" placeholder="9:00 AM - 8:00 PM">
                    </div>
                </div>
            </div>

            <!-- SECTION 3: District Geographic Location Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-geo-alt-fill text-primary me-2"></i>3. Saran District Geographic Location</h6>
                    <span class="badge bg-light text-muted border small">Section 3</span>
                </div>

                <div class="row g-3">
                    <!-- Block -->
                    <div class="col-12 col-md-4">
                        <label for="block_id" class="form-label small fw-semibold">CD Block (Saran District)</label>
                        <select class="form-select" id="block_id" name="block_id">
                            <option value="">-- Choose Block --</option>
                            <?php foreach ($blocks as $blk): ?>
                                <option value="<?php echo $blk['id']; ?>" <?php echo $listing['block_id'] == $blk['id'] ? 'selected' : ''; ?>>
                                    <?php echo sanitizeInput($blk['block_name']); ?> (<?php echo sanitizeInput($blk['hindi_name']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Gram Panchayat -->
                    <div class="col-12 col-md-4">
                        <label for="panchayat_id" class="form-label small fw-semibold">Gram Panchayat</label>
                        <select class="form-select" id="panchayat_id" name="panchayat_id">
                            <option value="">Choose Panchayat (Optional)</option>
                            <?php foreach ($initial_panchayats as $p): ?>
                                <option value="<?php echo $p['id']; ?>" <?php echo (!empty($listing['panchayat_id']) && intval($listing['panchayat_id']) === intval($p['id'])) ? 'selected' : ''; ?>>
                                    <?php echo sanitizeInput($p['panchayat_name']); ?> <?php echo !empty($p['hindi_name']) ? '('.sanitizeInput($p['hindi_name']).')' : ''; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Village / Mauja -->
                    <div class="col-12 col-md-4">
                        <label for="village_id" class="form-label small fw-semibold">Village / Mauja</label>
                        <select class="form-select" id="village_id" name="village_id">
                            <option value="">Choose Village / Mauja (Optional)</option>
                        </select>
                    </div>

                    <!-- Street / Full Address -->
                    <div class="col-12 col-md-8">
                        <label for="address" class="form-label small fw-semibold">Street / Full Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo sanitizeInput($listing['address']); ?>" placeholder="Full street address, landmark...">
                    </div>

                    <!-- Pincode -->
                    <div class="col-12 col-md-4">
                        <label for="pincode" class="form-label small fw-semibold">Postal PIN Code</label>
                        <div class="input-group">
                            <input type="text" class="form-control font-monospace fw-bold" id="pincode" name="pincode" value="<?php echo sanitizeInput($listing['pincode']); ?>" placeholder="841301" maxlength="6">
                            <button class="btn btn-outline-primary" type="button" id="btnLookupPin" title="Lookup PIN Code API">
                                <i class="bi bi-search" id="pinLookupIcon"></i>
                                <span class="spinner-border spinner-border-sm d-none" id="pinLookupSpinner"></span>
                            </button>
                        </div>
                        <div id="pincodeApiFeedback" class="mt-1.5" style="display: none;"></div>
                    </div>

                    <!-- Map Link -->
                    <div class="col-12">
                        <label for="map_link" class="form-label small fw-semibold">Google Maps Link / Embed URL</label>
                        <input type="text" class="form-control" id="map_link" name="map_link" value="<?php echo sanitizeInput($listing['map_link']); ?>" placeholder="https://maps.google.com/?q=...">
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Overview, Services & Products Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-card-text text-primary me-2"></i>4. Overview, Services & Products</h6>
                    <span class="badge bg-light text-muted border small">Section 4</span>
                </div>

                <div class="mb-3">
                    <label for="services" class="form-label small fw-semibold text-dark">
                        Key Services Offered / Features <span class="text-muted fw-normal">(Pipe | or Comma separated)</span>
                    </label>
                    <textarea class="form-control" id="services" name="services" rows="3" placeholder="e.g. Classes: 1 To 8 | Category: Primary | Type: Co-educational | Management: Pvt. Unaided"><?php echo sanitizeInput($listing['services']); ?></textarea>
                    <small class="text-muted" style="font-size: 0.76rem;">You can separate features using pipe (<code>|</code>) or comma (<code>,</code>). They will be formatted as badges on desktop views.</small>
                </div>

                <div class="mb-3">
                    <label for="products" class="form-label small fw-semibold text-dark">
                        Products / Items Sold <span class="text-muted fw-normal">(Comma or Pipe separated)</span>
                    </label>
                    <textarea class="form-control" id="products" name="products" rows="2" placeholder="e.g. Uniforms, Books, Stationery"><?php echo sanitizeInput($listing['products']); ?></textarea>
                </div>

                <div class="mb-0">
                    <label for="description" class="form-label small fw-semibold">Detailed Business / Entity Overview</label>
                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="Complete details about the business or organization..."><?php echo sanitizeInput($listing['description']); ?></textarea>
                </div>
            </div>

            <!-- SECTION 5: Legal & Reference Data Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-shield-check text-success me-2"></i>5. Legal Registrations & Source Reference</h6>
                    <span class="badge bg-light text-muted border small">Section 5</span>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="gst_no" class="form-label small fw-semibold text-muted">GSTIN / GST Number</label>
                        <input type="text" class="form-control form-control-sm text-uppercase font-monospace" id="gst_no" name="gst_no" value="<?php echo sanitizeInput($listing['gst_no']); ?>">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="udyam_no" class="form-label small fw-semibold text-muted">Udyam / Udyog Aadhaar</label>
                        <input type="text" class="form-control form-control-sm text-uppercase font-monospace" id="udyam_no" name="udyam_no" value="<?php echo sanitizeInput($listing['udyam_no']); ?>">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="cin_no" class="form-label small fw-semibold text-muted">CIN / Corporate Registration</label>
                        <input type="text" class="form-control form-control-sm text-uppercase font-monospace" id="cin_no" name="cin_no" value="<?php echo sanitizeInput($listing['cin_no']); ?>">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="local_reg_no" class="form-label small fw-semibold text-muted">Local License / Trade Reg / UDISE Code</label>
                        <input type="text" class="form-control form-control-sm" id="local_reg_no" name="local_reg_no" value="<?php echo sanitizeInput($listing['local_reg_no']); ?>" placeholder="e.g. UDISE: 10170304507">
                    </div>

                    <!-- Source & Source ID -->
                    <div class="col-12 col-md-6">
                        <label for="source" class="form-label small fw-semibold text-muted">Data Source Name</label>
                        <input type="text" class="form-control form-control-sm font-monospace" id="source" name="source" value="<?php echo sanitizeInput($listing['source'] ?? 'manual'); ?>" placeholder="e.g. manual, udiseplus.gov.in, offerplant">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="source_id" class="form-label small fw-semibold text-muted">Source Record / Registry ID</label>
                        <input type="text" class="form-control form-control-sm font-monospace" id="source_id" name="source_id" value="<?php echo sanitizeInput($listing['source_id'] ?? ''); ?>" placeholder="e.g. 21">
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar Options Column (4 columns) -->
        <div class="col-12 col-lg-4">
            
            <!-- Publishing Controls Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white position-sticky" style="top: 20px;">
                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-sliders text-primary me-2"></i>Publishing & Badges</h6>

                <!-- Listing Status -->
                <div class="mb-3">
                    <label for="status" class="form-label small fw-semibold">Listing Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="ACTIVE" <?php echo ($listing['status'] ?? 'ACTIVE') === 'ACTIVE' ? 'selected' : ''; ?>>🟢 ACTIVE (Published Live)</option>
                        <option value="PENDING" <?php echo ($listing['status'] ?? '') === 'PENDING' ? 'selected' : ''; ?>>🟡 PENDING (Needs Review)</option>
                        <option value="REJECTED" <?php echo ($listing['status'] ?? '') === 'REJECTED' ? 'selected' : ''; ?>>🔴 REJECTED (Hidden)</option>
                    </select>
                </div>

                <!-- Main Category -->
                <div class="mb-3">
                    <label for="category_id" class="form-label small fw-semibold">Main Category <span class="text-danger">*</span></label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $listing['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo sanitizeInput($cat['name']); ?> (ID: #<?php echo $cat['id']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Subcategory -->
                <div class="mb-3">
                    <label for="subcategory_id" class="form-label small fw-semibold">Subcategory</label>
                    <select class="form-select" id="subcategory_id" name="subcategory_id">
                        <option value="">Choose Subcategory (Optional)</option>
                        <?php foreach ($initial_subcategories as $sub): ?>
                            <option value="<?php echo $sub['id']; ?>" <?php echo (!empty($listing['subcategory_id']) && intval($listing['subcategory_id']) === intval($sub['id'])) ? 'selected' : ''; ?>>
                                <?php echo sanitizeInput($sub['name']); ?> (ID: #<?php echo $sub['id']; ?>) <?php echo !empty($sub['hindi_name']) ? '('.sanitizeInput($sub['hindi_name']).')' : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr class="my-3 text-muted">

                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-award text-warning me-2"></i>Subscription Plan</h6>

                <div class="mb-3">
                    <label for="plan_type" class="form-label small fw-semibold">Plan Tier</label>
                    <select class="form-select" id="plan_type" name="plan_type">
                        <option value="FREE" <?php echo ($listing['plan_type'] ?? 'FREE') === 'FREE' ? 'selected' : ''; ?>>FREE Plan</option>
                        <option value="GOLD" <?php echo ($listing['plan_type'] ?? '') === 'GOLD' ? 'selected' : ''; ?>>GOLD Plan (Verified Badge)</option>
                        <option value="PLATINUM" <?php echo ($listing['plan_type'] ?? '') === 'PLATINUM' ? 'selected' : ''; ?>>PLATINUM Plan (Featured & Verified)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="plan_expires_at" class="form-label small fw-semibold">Plan Expiry Date</label>
                    <input type="date" class="form-control form-control-sm" id="plan_expires_at" name="plan_expires_at" value="<?php echo !empty($listing['plan_expires_at']) ? date('Y-m-d', strtotime($listing['plan_expires_at'])) : ''; ?>">
                </div>

                <hr class="my-3 text-muted">

                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-check2-circle text-success me-2"></i>Badges & Metrics</h6>

                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="YES" <?php echo ($listing['is_verified'] ?? 'NO') === 'YES' ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-semibold small text-dark" for="is_verified">
                        <i class="bi bi-patch-check-fill text-success me-1"></i> Verified Badge (YES)
                    </label>
                </div>

                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="YES" <?php echo ($listing['is_featured'] ?? 'NO') === 'YES' ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-semibold small text-dark" for="is_featured">
                        <i class="bi bi-star-fill text-warning me-1"></i> Featured Listing (YES)
                    </label>
                </div>

                <!-- Star Rating -->
                <div class="mb-3">
                    <label for="star_rating" class="form-label small fw-semibold text-dark d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-star-fill text-warning me-1"></i>Star Rating (1.0 - 5.0)</span>
                        <span class="badge bg-warning text-dark fw-bold"><?php echo number_format(floatval($listing['star_rating'] ?? 5.0), 2); ?> ★</span>
                    </label>
                    <input type="number" step="0.01" min="1.0" max="5.0" class="form-control form-control-sm" id="star_rating" name="star_rating" value="<?php echo htmlspecialchars($listing['star_rating'] ?? '5.00'); ?>">
                </div>

                <!-- Listing Views Count -->
                <div class="mb-3">
                    <label for="view_count" class="form-label small fw-semibold text-dark"><i class="bi bi-eye-fill text-primary me-1"></i>Listing Views Count</label>
                    <input type="number" min="0" class="form-control form-control-sm" id="view_count" name="view_count" value="<?php echo intval($listing['view_count'] ?? 0); ?>">
                </div>

                <!-- Registration / Creation Timestamp -->
                <div class="mb-4">
                    <label for="created_at" class="form-label small fw-semibold text-dark"><i class="bi bi-calendar-event me-1"></i>Registered Date & Time</label>
                    <input type="text" class="form-control form-control-sm font-monospace" id="created_at" name="created_at" value="<?php echo htmlspecialchars($listing['created_at'] ?? date('Y-m-d H:i:s')); ?>">
                    <small class="text-muted" style="font-size: 0.72rem;">Format: YYYY-MM-DD HH:MM:SS</small>
                </div>

                <!-- Cover Image & Media Preview -->
                <div class="mb-4 p-3 bg-light rounded-3 border">
                    <label class="form-label small fw-semibold text-dark d-block"><i class="bi bi-image me-1"></i>Cover Photo</label>
                    <?php if (!empty($listing['cover_image'])): ?>
                        <div class="mb-2 text-center">
                            <img src="../<?php echo sanitizeInput($listing['cover_image']); ?>" alt="Cover Preview" class="rounded-3 border shadow-sm object-fit-cover w-100" style="max-height: 120px;" onerror="this.src='../assets/img/logo.png';">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control form-control-sm mb-1.5" id="cover_image_file" name="cover_image_file" accept="image/*">
                    <input type="text" class="form-control form-control-sm" id="cover_image" name="cover_image" value="<?php echo sanitizeInput($listing['cover_image']); ?>" placeholder="Or relative path / URL">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-bold py-2.5 shadow-sm rounded-3">
                        <i class="bi bi-check-circle-fill me-1"></i> <?php echo $editing_id ? 'Save All Changes' : 'Publish Listing'; ?>
                    </button>
                    <a href="listings.php" class="btn btn-light border fw-medium rounded-3">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const catSelect = document.getElementById('category_id');
    const subSelect = document.getElementById('subcategory_id');
    const blockSelect = document.getElementById('block_id');
    const panchayatSelect = document.getElementById('panchayat_id');
    const villageSelect = document.getElementById('village_id');

    const currentSubId = <?php echo !empty($listing['subcategory_id']) ? intval($listing['subcategory_id']) : 0; ?>;
    const currentPanchayatId = <?php echo !empty($listing['panchayat_id']) ? intval($listing['panchayat_id']) : 0; ?>;
    const currentVillageId = <?php echo !empty($listing['village_id']) ? intval($listing['village_id']) : 0; ?>;

    // Function to load subcategories dynamically
    function loadSubcategories(catId, selectedSubId) {
        if (!catId || !subSelect) return;
        fetch(`../api/subcategories_api.php?category_id=${catId}`)
            .then(res => res.json())
            .then(data => {
                subSelect.innerHTML = '<option value="">Choose Subcategory (Optional)</option>';
                const subs = Array.isArray(data) ? data : (data.subcategories || []);
                if (Array.isArray(subs) && subs.length > 0) {
                    subs.forEach(sub => {
                        const opt = document.createElement('option');
                        opt.value = sub.id;
                        opt.textContent = sub.hindi_name ? `${sub.name} (${sub.hindi_name})` : sub.name;
                        if (selectedSubId && parseInt(sub.id) === parseInt(selectedSubId)) {
                            opt.selected = true;
                        }
                        subSelect.appendChild(opt);
                    });
                }
            })
            .catch(err => {
                console.error("Subcategories fetch error:", err);
            });
    }

    // Function to load panchayats dynamically for chosen block
    function loadPanchayats(blockId, selectedPanchayatId) {
        if (!panchayatSelect) return;
        if (!blockId) {
            panchayatSelect.innerHTML = '<option value="">Choose Panchayat (Optional)</option>';
            return;
        }
        fetch(`../api/panchayats_api.php?block_id=${blockId}`)
            .then(res => res.json())
            .then(data => {
                panchayatSelect.innerHTML = '<option value="">Choose Panchayat (Optional)</option>';
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.textContent = p.hindi_name ? `${p.panchayat_name} (${p.hindi_name})` : p.panchayat_name;
                        if (selectedPanchayatId && parseInt(p.id) === parseInt(selectedPanchayatId)) {
                            opt.selected = true;
                        }
                        panchayatSelect.appendChild(opt);
                    });
                }
            })
            .catch(err => {
                console.error("Panchayats fetch error:", err);
            });
    }

    // Function to load villages dynamically for chosen block
    function loadVillages(blockId, selectedVillageId) {
        if (!villageSelect) return;
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
            });
    }

    // Initialize category change handler
    if (catSelect) {
        if (!subSelect.options || subSelect.options.length <= 1) {
            loadSubcategories(catSelect.value, currentSubId);
        }
        catSelect.addEventListener('change', function() {
            loadSubcategories(this.value, 0);
        });
    }

    // Initialize block change handler
    if (blockSelect) {
        if (!panchayatSelect || panchayatSelect.options.length <= 1) {
            loadPanchayats(blockSelect.value, currentPanchayatId);
        }
        loadVillages(blockSelect.value, currentVillageId);
        blockSelect.addEventListener('change', function() {
            loadPanchayats(this.value, 0);
            loadVillages(this.value, 0);
        });
    }

    // Copy URL Button Handler
    document.querySelectorAll('.copy-url-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                const icon = this.querySelector('i');
                icon.className = 'bi bi-clipboard-check text-success';
                setTimeout(() => {
                    icon.className = 'bi bi-clipboard';
                }, 2000);
            }).catch(() => {
                prompt('Copy this URL:', url);
            });
        });
    });

    // ─── PIN CODE API LOOKUP ────────────────────────────────────────────────
    const pinInput = document.getElementById('pincode');
    const btnLookupPin = document.getElementById('btnLookupPin');
    const pinIcon = document.getElementById('pinLookupIcon');
    const pinSpinner = document.getElementById('pinLookupSpinner');
    const pinFeedback = document.getElementById('pincodeApiFeedback');

    async function performPinLookup(pincode) {
        const clean = pincode.replace(/[^0-9]/g, '');
        if (clean.length !== 6) return;

        pinIcon.classList.add('d-none');
        pinSpinner.classList.remove('d-none');
        pinFeedback.style.display = 'block';
        pinFeedback.innerHTML = '<span class="text-muted small"><i class="bi bi-hourglass-split me-1"></i>Checking Postal API...</span>';

        try {
            const res = await fetch(`../api/pincode_lookup.php?pincode=${clean}`);
            const data = await res.json();

            if (data.success) {
                const officesList = data.offices ? data.offices.slice(0, 3).join(', ') : '';
                const moreCount = data.offices && data.offices.length > 3 ? ` +${data.offices.length - 3} more` : '';
                
                pinFeedback.innerHTML = `
                    <div class="p-2 bg-success-subtle border border-success-subtle rounded-3 small">
                        <div class="fw-bold text-success"><i class="bi bi-check-circle-fill me-1"></i>${data.district || 'Verified'}, ${data.state || 'India'}</div>
                        <div class="text-dark" style="font-size: 0.76rem;">PO: <strong>${officesList}${moreCount}</strong></div>
                    </div>
                `;

                // Auto match block if found and not already selected
                if (data.matched_blocks && data.matched_blocks.length > 0 && blockSelect) {
                    const matchedId = data.matched_blocks[0].id;
                    if (blockSelect.value != matchedId) {
                        blockSelect.value = matchedId;
                        loadPanchayats(matchedId, 0);
                        loadVillages(matchedId, 0);
                    }
                }
            } else {
                pinFeedback.innerHTML = `<span class="text-danger small"><i class="bi bi-exclamation-circle me-1"></i>${data.message || 'PIN not found'}</span>`;
            }
        } catch (e) {
            pinFeedback.innerHTML = '<span class="text-muted small">Could not connect to PIN API.</span>';
        } finally {
            pinIcon.classList.remove('d-none');
            pinSpinner.classList.add('d-none');
        }
    }

    if (btnLookupPin && pinInput) {
        btnLookupPin.addEventListener('click', () => performPinLookup(pinInput.value));
        pinInput.addEventListener('keyup', function(e) {
            if (this.value.replace(/[^0-9]/g, '').length === 6) {
                performPinLookup(this.value);
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

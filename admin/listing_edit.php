<?php
$editing_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$header_title = $editing_id ? "Edit Listing #{$editing_id}" : "Add New Directory Listing";
require_once __DIR__ . '/includes/header.php';

$error = '';
$success = '';

// Default structure for listing
$listing = [
    'entity_type' => 'BUSINESS',
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
    'plan_expires_at' => null
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
        'entity_type' => sanitizeInput($_POST['entity_type'] ?? 'BUSINESS'),
        'category_id' => intval($_POST['category_id'] ?? 1),
        'subcategory_id' => !empty($_POST['subcategory_id']) ? intval($_POST['subcategory_id']) : null,
        'block_id' => intval($_POST['block_id'] ?? 1),
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
        'plan_expires_at' => !empty($_POST['plan_expires_at']) ? sanitizeInput($_POST['plan_expires_at']) : null
    ];

    if ($post_data['status'] === 'ACTIVE') {
        $checkData = [
            'id' => $editing_id,
            'user_id' => $listing['user_id'] ?? null,
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
        $dup = checkDuplicateListing($post_data['title'], $post_data['mobile'], $editing_id);
        if ($dup) {
            $error = "Duplicate Entry Detected: A listing with the title '" . htmlspecialchars($post_data['title']) . "' and mobile number '" . htmlspecialchars($post_data['mobile']) . "' already exists in the directory (Listing ID #" . $dup['id'] . ").";
            $listing = array_merge($listing, $post_data);
        } else if (empty($post_data['title']) || empty($post_data['mobile'])) {
            $error = "Please fill in all required fields: Title and Mobile Number.";
            $listing = array_merge($listing, $post_data);
        } else {
            if (saveListing($post_data, $editing_id)) {
                $success = $editing_id ? "Listing #{$editing_id} updated successfully!" : "New directory listing created successfully!";
                if (!$editing_id) {
                    // Reset form fields for fresh entry
                    $listing['title'] = '';
                    $listing['hindi_title'] = '';
                    $listing['mobile'] = '';
                    $listing['contact_person'] = '';
                    $listing['address'] = '';
                    $listing['services'] = '';
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
                $error = "Database save operation failed. Please try again.";
                $listing = array_merge($listing, $post_data);
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
        <div class="d-flex align-items-center gap-2">
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
                <?php if ($listing['is_verified'] === 'YES'): ?>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 small"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="d-flex gap-2">
        <?php if ($editing_id && !empty($listing['slug'])): ?>
            <a href="../profile.php?slug=<?php echo urlencode($listing['slug']); ?>" target="_blank" class="btn btn-outline-primary btn-sm px-3 rounded-3 fw-semibold">
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
            
            <!-- Basic Information Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-info-circle text-primary me-2"></i>Basic Listing Info</h6>
                    <span class="badge bg-light text-muted border small">Section 1</span>
                </div>
                
                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <label for="title" class="form-label small fw-semibold">Listing Title (English) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg fs-6" id="title" name="title" value="<?php echo sanitizeInput($listing['title']); ?>" placeholder="e.g. Saran District Hospital / Chapra Mobile Store" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="entity_type" class="form-label small fw-semibold">Entity Classification</label>
                        <select class="form-select" id="entity_type" name="entity_type">
                            <option value="BUSINESS" <?php echo $listing['entity_type'] === 'BUSINESS' ? 'selected' : ''; ?>>Business / Retail Shop</option>
                            <option value="PROFESSIONAL" <?php echo $listing['entity_type'] === 'PROFESSIONAL' ? 'selected' : ''; ?>>Professional / Legal / Advocate</option>
                            <option value="HEALTHCARE" <?php echo $listing['entity_type'] === 'HEALTHCARE' ? 'selected' : ''; ?>>Healthcare / Hospital / Clinic</option>
                            <option value="GOVT_OFFICE" <?php echo $listing['entity_type'] === 'GOVT_OFFICE' ? 'selected' : ''; ?>>Government Office</option>
                            <option value="SCHOOL_COLLEGE" <?php echo $listing['entity_type'] === 'SCHOOL_COLLEGE' ? 'selected' : ''; ?>>School / College / Coaching</option>
                            <option value="EMERGENCY" <?php echo $listing['entity_type'] === 'EMERGENCY' ? 'selected' : ''; ?>>Emergency Service</option>
                            <option value="BANK" <?php echo $listing['entity_type'] === 'BANK' ? 'selected' : ''; ?>>Bank / ATM</option>
                            <option value="HOTEL" <?php echo $listing['entity_type'] === 'HOTEL' ? 'selected' : ''; ?>>Hotel / Restaurant</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="hindi_title" class="form-label small fw-semibold">Hindi Name (हिंदी नाम)</label>
                        <input type="text" class="form-control" id="hindi_title" name="hindi_title" value="<?php echo sanitizeInput($listing['hindi_title']); ?>" placeholder="उदा. सदर अस्पताल, छपरा">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="slug" class="form-label small fw-semibold">Custom URL Slug</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted small">saranindex.com/listing/</span>
                            <input type="text" class="form-control" id="slug" name="slug" value="<?php echo sanitizeInput($listing['slug']); ?>" placeholder="auto-generated-from-title">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Address Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-telephone text-primary me-2"></i>Contact Details & Address</h6>
                    <span class="badge bg-light text-muted border small">Section 2</span>
                </div>
                
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label for="mobile" class="form-label small fw-semibold">Mobile / Phone Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-telephone-fill text-muted"></i></span>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo sanitizeInput($listing['mobile']); ?>" placeholder="e.g. 9876543210" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="mobile_visibility" class="form-label small fw-semibold">Mobile Visibility</label>
                        <select class="form-select" id="mobile_visibility" name="mobile_visibility">
                            <option value="REGISTERED" <?php echo ($listing['mobile_visibility'] ?? 'REGISTERED') === 'REGISTERED' ? 'selected' : ''; ?>>👤 REGISTERED (Log In Required - Default)</option>
                            <option value="PUBLIC" <?php echo ($listing['mobile_visibility'] ?? '') === 'PUBLIC' ? 'selected' : ''; ?>>🌐 PUBLIC (Visible to Everyone)</option>
                            <option value="HIDDEN" <?php echo ($listing['mobile_visibility'] ?? '') === 'HIDDEN' ? 'selected' : ''; ?>>🔒 HIDDEN (Private)</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="whatsapp" class="form-label small fw-semibold">WhatsApp Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-success"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?php echo sanitizeInput($listing['whatsapp']); ?>" placeholder="e.g. 9876543210">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="contact_person" class="form-label small fw-semibold">Contact Person / Owner</label>
                        <input type="text" class="form-control" id="contact_person" name="contact_person" value="<?php echo sanitizeInput($listing['contact_person']); ?>" placeholder="e.g. Dr. Rajesh Kumar">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label small fw-semibold">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo sanitizeInput($listing['email']); ?>" placeholder="contact@example.com">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="website" class="form-label small fw-semibold">Website URL</label>
                        <input type="url" class="form-control" id="website" name="website" value="<?php echo sanitizeInput($listing['website']); ?>" placeholder="https://www.example.com">
                    </div>

                    <div class="col-12 col-md-8">
                        <label for="address" class="form-label small fw-semibold">Street / Full Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo sanitizeInput($listing['address']); ?>" placeholder="e.g. Main Market, Near Railway Station">
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="pincode" class="form-label small fw-semibold">Pincode</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo sanitizeInput($listing['pincode']); ?>" placeholder="841301">
                    </div>
                </div>
            </div>

            <!-- District Geographic Location Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Saran District Location</h6>
                    <span class="badge bg-light text-muted border small">Section 3</span>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="block_id" class="form-label small fw-semibold">Block (Saran District)</label>
                        <select class="form-select" id="block_id" name="block_id">
                            <?php foreach ($blocks as $blk): ?>
                                <option value="<?php echo $blk['id']; ?>" <?php echo $listing['block_id'] == $blk['id'] ? 'selected' : ''; ?>>
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
                        <div class="form-text small">Select village to pinpoint exact location in Saran district.</div>
                    </div>
                </div>
            </div>

            <!-- Operational & Media Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-card-image text-primary me-2"></i>Media, Timing & Description</h6>
                    <span class="badge bg-light text-muted border small">Section 4</span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label for="business_hours" class="form-label small fw-semibold">Business / Opening Hours</label>
                        <input type="text" class="form-control" id="business_hours" name="business_hours" value="<?php echo sanitizeInput($listing['business_hours']); ?>" placeholder="e.g. 9:00 AM - 8:00 PM (Mon-Sat)">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="map_link" class="form-label small fw-semibold">Google Maps Link</label>
                        <input type="text" class="form-control" id="map_link" name="map_link" value="<?php echo sanitizeInput($listing['map_link']); ?>" placeholder="e.g. https://maps.google.com/?q=...">
                    </div>
                </div>

                <!-- Cover Image Section -->
                <div class="mb-4 bg-light p-3 rounded-3 border">
                    <label class="form-label small fw-semibold text-dark d-block">Listing Photo / Cover Image</label>
                    <div class="row align-items-center g-3">
                        <?php if (!empty($listing['cover_image'])): ?>
                            <div class="col-auto">
                                <div class="position-relative">
                                    <img src="../<?php echo sanitizeInput($listing['cover_image']); ?>" alt="Cover Preview" class="rounded-3 border shadow-sm object-fit-cover" style="width: 80px; height: 80px;" onerror="this.src='../assets/img/logo.png';">
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col">
                            <div class="mb-2">
                                <label for="cover_image_file" class="form-label small text-muted mb-1">Upload New Image File</label>
                                <input type="file" class="form-control form-control-sm" id="cover_image_file" name="cover_image_file" accept="image/*">
                            </div>
                            <div>
                                <label for="cover_image" class="form-label small text-muted mb-1">Or Image Relative URL / Path</label>
                                <input type="text" class="form-control form-control-sm" id="cover_image" name="cover_image" value="<?php echo sanitizeInput($listing['cover_image']); ?>" placeholder="uploads/listings/example.jpg">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label for="services" class="form-label small fw-semibold">Key Services Offered (Comma Separated)</label>
                        <input type="text" class="form-control" id="services" name="services" value="<?php echo sanitizeInput($listing['services']); ?>" placeholder="e.g. OPD, ICU, 24x7 Emergency, Ambulance">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="products" class="form-label small fw-semibold">Products / Items Sold (Comma Separated)</label>
                        <input type="text" class="form-control" id="products" name="products" value="<?php echo sanitizeInput($listing['products']); ?>" placeholder="e.g. Medicines, Surgical Supplies, Medical Devices">
                    </div>
                </div>

                <!-- Business Registration & Legal Numbers Card -->
                <div class="p-3 bg-light rounded-3 border mb-3">
                    <h6 class="fw-bold text-dark mb-2 small text-uppercase"><i class="bi bi-shield-check text-success me-1"></i>Legal & Registration Numbers</h6>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="gst_no" class="form-label small fw-semibold text-muted">GSTIN / GST Number</label>
                            <input type="text" class="form-control form-control-sm text-uppercase font-monospace" id="gst_no" name="gst_no" value="<?php echo sanitizeInput($listing['gst_no']); ?>" placeholder="e.g. 10AAAAA0000A1Z5">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="udyam_no" class="form-label small fw-semibold text-muted">Udyam / Udyog Aadhaar</label>
                            <input type="text" class="form-control form-control-sm text-uppercase font-monospace" id="udyam_no" name="udyam_no" value="<?php echo sanitizeInput($listing['udyam_no']); ?>" placeholder="e.g. UDYAM-BR-28-0012345">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="cin_no" class="form-label small fw-semibold text-muted">CIN / Corporate Registration</label>
                            <input type="text" class="form-control form-control-sm text-uppercase font-monospace" id="cin_no" name="cin_no" value="<?php echo sanitizeInput($listing['cin_no']); ?>" placeholder="e.g. U72900BR2017PTC034567">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="local_reg_no" class="form-label small fw-semibold text-muted">Local License / Trade Reg. No.</label>
                            <input type="text" class="form-control form-control-sm" id="local_reg_no" name="local_reg_no" value="<?php echo sanitizeInput($listing['local_reg_no']); ?>" placeholder="e.g. Chapra Municipal License #841301">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="description" class="form-label small fw-semibold">Detailed Business / Entity Overview</label>
                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="Enter full details about services, history, specialties, facilities, etc."><?php echo sanitizeInput($listing['description']); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar Options Column (4 columns) -->
        <div class="col-12 col-lg-4">
            
            <!-- Publishing Controls Card -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 position-sticky" style="top: 20px;">
                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-sliders text-primary me-2"></i>Publishing & Status</h6>

                <div class="mb-3">
                    <label for="status" class="form-label small fw-semibold">Listing Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="ACTIVE" <?php echo $listing['status'] === 'ACTIVE' ? 'selected' : ''; ?>>🟢 ACTIVE (Published Live)</option>
                        <option value="PENDING" <?php echo $listing['status'] === 'PENDING' ? 'selected' : ''; ?>>🟡 PENDING (Needs Review)</option>
                        <option value="REJECTED" <?php echo $listing['status'] === 'REJECTED' ? 'selected' : ''; ?>>🔴 REJECTED (Hidden)</option>
                    </select>
                </div>

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

                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-check2-circle text-success me-2"></i>Badges & Visibility</h6>

                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="YES" <?php echo ($listing['is_verified'] ?? 'NO') === 'YES' ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-semibold small text-dark" for="is_verified">
                        <i class="bi bi-patch-check-fill text-success me-1"></i> Verified Badge (YES)
                    </label>
                </div>

                <div class="mb-4 form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="YES" <?php echo ($listing['is_featured'] ?? 'NO') === 'YES' ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-semibold small text-dark" for="is_featured">
                        <i class="bi bi-star-fill text-warning me-1"></i> Featured Listing (YES)
                    </label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-bold py-2.5 shadow-sm rounded-3">
                        <i class="bi bi-check-circle-fill me-1"></i> <?php echo $editing_id ? 'Save Changes' : 'Publish Listing'; ?>
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
    const villageSelect = document.getElementById('village_id');

    const currentSubId = <?php echo !empty($listing['subcategory_id']) ? intval($listing['subcategory_id']) : 0; ?>;
    const currentVillageId = <?php echo !empty($listing['village_id']) ? intval($listing['village_id']) : 0; ?>;

    // Function to load subcategories dynamically
    function loadSubcategories(catId, selectedSubId) {
        if (!catId) {
            subSelect.innerHTML = '<option value="">Choose Subcategory (Optional)</option>';
            return;
        }
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

    // Function to load villages dynamically for chosen block
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

    // Initialize category change handler
    if (catSelect) {
        loadSubcategories(catSelect.value, currentSubId);
        catSelect.addEventListener('change', function() {
            loadSubcategories(this.value, 0);
        });
    }

    // Initialize block change handler
    if (blockSelect) {
        loadVillages(blockSelect.value, currentVillageId);
        blockSelect.addEventListener('change', function() {
            loadVillages(this.value, 0);
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

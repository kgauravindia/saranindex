<?php
$editing_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$header_title = $editing_id ? "Edit Listing #{$editing_id}" : "Add New Directory Listing";
require_once __DIR__ . '/includes/header.php';

$error = '';
$success = '';

// Load existing data if editing
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
    'whatsapp' => '',
    'email' => '',
    'website' => '',
    'address' => '',
    'pincode' => '841301',
    'services' => '',
    'description' => '',
    'is_verified' => 'NO',
    'is_featured' => 'NO',
    'status' => 'ACTIVE'
];

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
        'whatsapp' => sanitizeInput($_POST['whatsapp'] ?? ''),
        'email' => sanitizeInput($_POST['email'] ?? ''),
        'website' => sanitizeInput($_POST['website'] ?? ''),
        'address' => sanitizeInput($_POST['address'] ?? ''),
        'pincode' => sanitizeInput($_POST['pincode'] ?? '841301'),
        'services' => sanitizeInput($_POST['services'] ?? ''),
        'description' => sanitizeInput($_POST['description'] ?? ''),
        'is_verified' => sanitizeInput($_POST['is_verified'] ?? 'NO'),
        'is_featured' => sanitizeInput($_POST['is_featured'] ?? 'NO'),
        'status' => sanitizeInput($_POST['status'] ?? 'ACTIVE')
    ];

    if (empty($post_data['title']) || empty($post_data['mobile'])) {
        $error = "Please fill in the required fields: Title and Mobile Number.";
        $listing = array_merge($listing, $post_data);
    } else {
        if (saveListing($post_data, $editing_id)) {
            $success = $editing_id ? "Listing updated successfully!" : "New listing added successfully!";
            if (!$editing_id) {
                // Clear form for fresh entry if added
                $listing['title'] = '';
                $listing['hindi_title'] = '';
                $listing['mobile'] = '';
                $listing['contact_person'] = '';
                $listing['address'] = '';
                $listing['services'] = '';
                $listing['description'] = '';
            }
        } else {
            $error = "Database save failed. Please verify database connection.";
            $listing = array_merge($listing, $post_data);
        }
    }
}

$categories = getAllAdminCategories();
$blocks = getStaticBlocks();
?>

<!-- Header Title Bar -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><?php echo $editing_id ? "Edit Listing #{$editing_id}" : "Add New Listing"; ?></h4>
        <p class="text-muted small mb-0">Fill out details for directory entry in Saran District.</p>
    </div>
    <a href="listings.php" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Back to Listings
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($error); ?></div>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($success); ?></div>
    </div>
<?php endif; ?>

<form action="listing_edit.php<?php echo $editing_id ? '?id='.$editing_id : ''; ?>" method="POST">
    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-info-circle text-primary me-2"></i>Basic Information</h6>
                
                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <label for="title" class="form-label small fw-semibold">Listing Title (English) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo sanitizeInput($listing['title']); ?>" placeholder="e.g. Saran District Hospital" required>
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
                        <label for="slug" class="form-label small fw-semibold">Custom URL Slug (Optional)</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="<?php echo sanitizeInput($listing['slug']); ?>" placeholder="auto-generated-if-empty">
                    </div>
                </div>
            </div>

            <!-- Contact & Location Details -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-geo-alt text-primary me-2"></i>Contact & Location</h6>
                
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label for="mobile" class="form-label small fw-semibold">Mobile / Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo sanitizeInput($listing['mobile']); ?>" placeholder="e.g. 9876543210" required>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="whatsapp" class="form-label small fw-semibold">WhatsApp Number</label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?php echo sanitizeInput($listing['whatsapp']); ?>" placeholder="e.g. 9876543210">
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="contact_person" class="form-label small fw-semibold">Contact Person Name</label>
                        <input type="text" class="form-control" id="contact_person" name="contact_person" value="<?php echo sanitizeInput($listing['contact_person']); ?>" placeholder="e.g. Dr. Rajesh Kumar">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label small fw-semibold">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo sanitizeInput($listing['email']); ?>" placeholder="contact@domain.com">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="website" class="form-label small fw-semibold">Website URL</label>
                        <input type="url" class="form-control" id="website" name="website" value="<?php echo sanitizeInput($listing['website']); ?>" placeholder="https://example.com">
                    </div>

                    <div class="col-12 col-md-8">
                        <label for="address" class="form-label small fw-semibold">Full Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo sanitizeInput($listing['address']); ?>" placeholder="e.g. Near Main Market, Chapra Sadar">
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="pincode" class="form-label small fw-semibold">Pincode</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo sanitizeInput($listing['pincode']); ?>" placeholder="841301">
                    </div>
                </div>
            </div>

            <!-- Description & Services -->
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-file-text text-primary me-2"></i>Services & Description</h6>
                
                <div class="mb-3">
                    <label for="services" class="form-label small fw-semibold">Key Services Offered (Comma Separated)</label>
                    <input type="text" class="form-control" id="services" name="services" value="<?php echo sanitizeInput($listing['services']); ?>" placeholder="e.g. OPD, ICU, 24x7 Emergency, Ambulance">
                </div>

                <div>
                    <label for="description" class="form-label small fw-semibold">Detailed Business / Entity Overview</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter full details about services, history, timing, etc."><?php echo sanitizeInput($listing['description']); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar Options Column -->
        <div class="col-12 col-lg-4">
            <!-- Publishing & Status -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-sliders text-primary me-2"></i>Publish Settings</h6>

                <div class="mb-3">
                    <label for="status" class="form-label small fw-semibold">Listing Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="ACTIVE" <?php echo $listing['status'] === 'ACTIVE' ? 'selected' : ''; ?>>ACTIVE (Published Live)</option>
                        <option value="PENDING" <?php echo $listing['status'] === 'PENDING' ? 'selected' : ''; ?>>PENDING (Needs Moderator Review)</option>
                        <option value="REJECTED" <?php echo $listing['status'] === 'REJECTED' ? 'selected' : ''; ?>>REJECTED (Hidden)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label small fw-semibold">Main Category</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $listing['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo sanitizeInput($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subcategory_id" class="form-label small fw-semibold">Subcategory</label>
                    <select class="form-select" id="subcategory_id" name="subcategory_id">
                        <option value="">Choose Subcategory (Optional)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="block_id" class="form-label small fw-semibold">Block Location (Saran District)</label>
                    <select class="form-select" id="block_id" name="block_id">
                        <?php foreach ($blocks as $blk): ?>
                            <option value="<?php echo $blk['id']; ?>" <?php echo $listing['block_id'] == $blk['id'] ? 'selected' : ''; ?>>
                                <?php echo sanitizeInput($blk['block_name']); ?> (<?php echo sanitizeInput($blk['hindi_name']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="YES" <?php echo $listing['is_verified'] === 'YES' ? 'checked' : ''; ?>>
                    <label class="form-check-input-label fw-semibold small" for="is_verified">
                        <i class="bi bi-patch-check-fill text-success me-1"></i> Verified Badge (YES)
                    </label>
                </div>

                <div class="mb-4 form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="YES" <?php echo $listing['is_featured'] === 'YES' ? 'checked' : ''; ?>>
                    <label class="form-check-input-label fw-semibold small" for="is_featured">
                        <i class="bi bi-star-fill text-warning me-1"></i> Featured Listing (YES)
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm mb-2">
                    <i class="bi bi-check-lg me-1"></i> <?php echo $editing_id ? 'Save Changes' : 'Publish Listing'; ?>
                </button>

                <a href="listings.php" class="btn btn-light w-100 fw-medium border">Cancel</a>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const catSelect = document.getElementById('category_id');
    const subSelect = document.getElementById('subcategory_id');
    const currentSubId = <?php echo !empty($listing['subcategory_id']) ? intval($listing['subcategory_id']) : 0; ?>;

    function loadSubcategories(catId, selectedSubId) {
        if (!catId) {
            subSelect.innerHTML = '<option value="">Choose Subcategory (Optional)</option>';
            return;
        }
        fetch(`../api/subcategories_api.php?category_id=${catId}`)
            .then(res => res.json())
            .then(data => {
                subSelect.innerHTML = '<option value="">Choose Subcategory (Optional)</option>';
                if (data && data.length > 0) {
                    data.forEach(sub => {
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
            .catch(() => {
                subSelect.innerHTML = '<option value="">Choose Subcategory (Optional)</option>';
            });
    }

    if (catSelect) {
        loadSubcategories(catSelect.value, currentSubId);
        catSelect.addEventListener('change', function() {
            loadSubcategories(this.value, 0);
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
$header_title = "Manage Directory Categories & Subcategories";
require_once __DIR__ . '/includes/header.php';

$msg = '';
$msg_type = 'success';

// Handle Category/Subcategory Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action_type'] ?? 'category';

    if ($action === 'subcategory') {
        $sub_id = !empty($_POST['sub_id']) ? intval($_POST['sub_id']) : null;
        $cat_id = intval($_POST['category_id'] ?? 0);
        $name = sanitizeInput($_POST['name'] ?? '');
        $hindi_name = sanitizeInput($_POST['hindi_name'] ?? '');
        $keywords = sanitizeInput($_POST['keywords'] ?? '');

        if (!empty($name) && $cat_id > 0) {
            if (saveSubcategory($name, $hindi_name, $cat_id, $keywords, $sub_id)) {
                $msg = "Subcategory saved successfully!";
            } else {
                $msg = "Could not save subcategory.";
                $msg_type = "danger";
            }
        }
    } else {
        $cat_id = !empty($_POST['cat_id']) ? intval($_POST['cat_id']) : null;
        $name = sanitizeInput($_POST['name'] ?? '');
        $hindi_name = sanitizeInput($_POST['hindi_name'] ?? '');
        $icon = sanitizeInput($_POST['icon'] ?? 'bi-folder');
        $section = sanitizeInput($_POST['section'] ?? 'BUSINESS');

        if (!empty($name)) {
            if (saveCategory($name, $hindi_name, $icon, $section, $cat_id)) {
                $msg = $cat_id ? "Category updated successfully!" : "New category added successfully!";
            } else {
                $msg = "Could not save category.";
                $msg_type = "danger";
            }
        }
    }
}

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
        $del_id = intval($_GET['id']);
        if (deleteCategory($del_id)) {
            $msg = "Category #{$del_id} deleted successfully!";
            $msg_type = "danger";
        }
    } elseif ($_GET['action'] === 'delete_sub' && isset($_GET['sub_id'])) {
        $sub_del_id = intval($_GET['sub_id']);
        if (deleteSubcategory($sub_del_id)) {
            $msg = "Subcategory #{$sub_del_id} deleted successfully!";
            $msg_type = "danger";
        }
    }
}

$categories = getAllAdminCategories();
$all_subcategories = getAllSubcategories();

// Group subcategories by category_id
$sub_by_cat = [];
foreach ($all_subcategories as $sub) {
    $sub_by_cat[$sub['category_id']][] = $sub;
}
?>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Core Verticals</div>
                    <h3 class="fw-bold text-dark my-1"><?php echo number_format(count($categories)); ?></h3>
                    <small class="text-muted">Main Categories</small>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-grid-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Subcategories</div>
                    <h3 class="fw-bold text-info my-1"><?php echo number_format(count($all_subcategories)); ?></h3>
                    <small class="text-info fw-medium"><i class="bi bi-diagram-3 me-1"></i>Detailed Niche Verticals</small>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Directory Listings</div>
                    <h3 class="fw-bold text-success my-1"><?php echo number_format(array_sum(array_column($categories, 'listing_count'))); ?></h3>
                    <small class="text-success fw-medium"><i class="bi bi-collection me-1"></i>Mapped to categories</small>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-collection-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories & Subcategories Accordion Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">#ID</th>
                    <th style="width: 50px;">Icon</th>
                    <th>Category Name</th>
                    <th>Hindi Name</th>
                    <th>Listings Count</th>
                    <th>Subcategories</th>
                    <th>Section</th>
                    <th>URL Slug</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): 
                    $subs = $sub_by_cat[$cat['id']] ?? [];
                    $sub_count = count($subs);
                    $cat_listings = intval($cat['listing_count'] ?? 0);
                ?>
                    <tr>
                        <td class="fw-bold text-muted">#<?php echo $cat['id']; ?></td>
                        <td>
                            <div class="bg-primary bg-opacity-10 text-primary rounded-2 p-2 text-center" style="width: 38px; height: 38px;">
                                <i class="bi <?php echo sanitizeInput($cat['icon']); ?> fs-5"></i>
                            </div>
                        </td>
                        <td class="fw-bold text-dark">
                            <?php echo sanitizeInput($cat['name']); ?>
                            <span class="badge bg-light text-secondary border small ms-1" title="Category ID">ID: #<?php echo $cat['id']; ?></span>
                        </td>
                        <td class="text-muted"><?php echo sanitizeInput($cat['hindi_name'] ?? ''); ?></td>
                        <td>
                            <a href="listings.php?category=<?php echo $cat['id']; ?>&search=<?php echo urlencode($cat['name']); ?>" class="badge bg-success-subtle text-success border border-success-subtle fw-bold px-2.5 py-1.5 text-decoration-none" title="Click to view all listings in <?php echo sanitizeInput($cat['name']); ?>">
                                <i class="bi bi-collection-fill me-1"></i><?php echo number_format($cat_listings); ?> Listings
                            </a>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-light border rounded-pill fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#subs-cat-<?php echo $cat['id']; ?>">
                                <i class="bi bi-diagram-3 text-primary me-1"></i><?php echo $sub_count; ?> Subcategories <i class="bi bi-chevron-down ms-1 small"></i>
                            </button>
                        </td>
                        <td><span class="badge bg-light text-primary border fw-semibold"><?php echo sanitizeInput($cat['section'] ?? 'BUSINESS'); ?></span></td>
                        <td><code class="small text-secondary"><?php echo sanitizeInput($cat['slug']); ?></code></td>
                        <td class="text-end">
                            <a href="categories.php?action=delete&id=<?php echo $cat['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <!-- Expandable Subcategory List Row -->
                    <tr class="collapse bg-light" id="subs-cat-<?php echo $cat['id']; ?>">
                        <td colspan="9" class="p-3">
                            <div class="p-3 bg-white rounded-3 border">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <span class="fw-bold text-primary small text-uppercase"><i class="bi bi-diagram-3-fill me-1"></i>Subcategories in <?php echo sanitizeInput($cat['name']); ?> (Cat ID: #<?php echo $cat['id']; ?>):</span>
                                    <button class="btn btn-sm btn-primary py-1 px-2.5 rounded-2 text-white fw-medium" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal" onclick="document.getElementById('sub_cat_select').value='<?php echo $cat['id']; ?>';">
                                        <i class="bi bi-plus-lg me-1"></i>Add Subcategory
                                    </button>
                                </div>
                                <?php if (!empty($subs)): ?>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php foreach ($subs as $s): 
                                            $sub_listings = intval($s['listing_count'] ?? 0);
                                        ?>
                                            <div class="badge bg-light text-dark border p-2 d-inline-flex align-items-center gap-2 rounded-2 font-body font-normal shadow-sm">
                                                <span class="badge bg-primary text-white">Sub ID: #<?php echo $s['id']; ?></span>
                                                <span><strong><?php echo sanitizeInput($s['name']); ?></strong> <?php if (!empty($s['hindi_name'])): ?><span class="text-muted">(<?php echo sanitizeInput($s['hindi_name']); ?>)</span><?php endif; ?></span>
                                                <a href="listings.php?subcategory=<?php echo $s['id']; ?>&search=<?php echo urlencode($s['name']); ?>" class="badge bg-warning text-dark text-decoration-none ms-1" title="View listings in subcategory">
                                                    <i class="bi bi-collection me-1"></i><?php echo number_format($sub_listings); ?> Listings
                                                </a>
                                                <a href="categories.php?action=delete_sub&sub_id=<?php echo $s['id']; ?>" class="text-danger text-decoration-none ms-1" onclick="return confirm('Delete subcategory <?php echo sanitizeInput($s['name']); ?>?');"><i class="bi bi-x-circle-fill"></i></a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-muted small">No subcategories created yet for this category.</div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-folder-plus text-primary me-2"></i>Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="categories.php" method="POST">
                <input type="hidden" name="action_type" value="category">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-semibold">Category Name (English) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Real Estate & Builders" required>
                    </div>

                    <div class="mb-3">
                        <label for="hindi_name" class="form-label small fw-semibold">Hindi Name (हिंदी नाम)</label>
                        <input type="text" class="form-control" id="hindi_name" name="hindi_name" placeholder="उदा. प्रॉपर्टी एवं बिल्डर्स">
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label small fw-semibold">Bootstrap Icon Class</label>
                        <input type="text" class="form-control" id="icon" name="icon" value="bi-shop" placeholder="e.g. bi-house, bi-hospital">
                    </div>

                    <div class="mb-3">
                        <label for="section" class="form-label small fw-semibold">Directory Section</label>
                        <select class="form-select" id="section" name="section">
                            <option value="BUSINESS">BUSINESS & Retail</option>
                            <option value="PROFESSIONAL">PROFESSIONAL Services</option>
                            <option value="HEALTHCARE">HEALTHCARE & Medical</option>
                            <option value="EDUCATION">EDUCATION & Colleges</option>
                            <option value="GOVT">GOVERNMENT Offices</option>
                            <option value="EMERGENCY">EMERGENCY Helpline</option>
                            <option value="BANK">BANKS & Finance</option>
                            <option value="HOTEL">HOTELS & Restaurants</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Subcategory Modal -->
<div class="modal fade" id="addSubcategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-diagram-3-fill text-primary me-2"></i>Add New Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="categories.php" method="POST">
                <input type="hidden" name="action_type" value="subcategory">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="sub_cat_select" class="form-label small fw-semibold">Parent Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="sub_cat_select" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo sanitizeInput($cat['name']); ?> (ID: #<?php echo $cat['id']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="sub_name" class="form-label small fw-semibold">Subcategory Name (English) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sub_name" name="name" placeholder="e.g. Kendriya Vidyalaya, ITI, Polytechnic" required>
                    </div>

                    <div class="mb-3">
                        <label for="sub_hindi_name" class="form-label small fw-semibold">Hindi Name (हिंदी नाम)</label>
                        <input type="text" class="form-control" id="sub_hindi_name" name="hindi_name" placeholder="उदा. केंद्रीय विद्यालय">
                    </div>

                    <div class="mb-3">
                        <label for="keywords" class="form-label small fw-semibold">Search Keywords (Comma Separated)</label>
                        <input type="text" class="form-control" id="keywords" name="keywords" placeholder="e.g. kv, cbse, central school">
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Create Subcategory</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

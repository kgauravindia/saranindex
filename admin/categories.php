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

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Directory Categories & Subcategories</h4>
        <p class="text-muted small mb-0">Organize all Saran District items, verticals, and subcategories (13 Categories, 121 Subcategories).</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary fw-bold px-3 py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal">
            <i class="bi bi-diagram-3 me-1"></i> Add Subcategory
        </button>
        <button type="button" class="btn btn-primary fw-bold px-3 py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg me-1"></i> Add Category
        </button>
    </div>
</div>

<!-- Categories & Subcategories Accordion Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">Icon</th>
                    <th>Category Name</th>
                    <th>Hindi Name</th>
                    <th>Subcategories Count</th>
                    <th>Section</th>
                    <th>URL Slug</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): 
                    $subs = $sub_by_cat[$cat['id']] ?? [];
                    $sub_count = count($subs);
                ?>
                    <tr>
                        <td>
                            <div class="bg-primary bg-opacity-10 text-primary rounded-2 p-2 text-center" style="width: 38px; height: 38px;">
                                <i class="bi <?php echo sanitizeInput($cat['icon']); ?> fs-5"></i>
                            </div>
                        </td>
                        <td class="fw-bold text-dark"><?php echo sanitizeInput($cat['name']); ?></td>
                        <td class="text-muted"><?php echo sanitizeInput($cat['hindi_name'] ?? ''); ?></td>
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
                        <td colspan="7" class="p-3">
                            <div class="p-3 bg-white rounded-3 border">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <span class="fw-bold text-primary small text-uppercase"><i class="bi bi-diagram-3-fill me-1"></i>Subcategories in <?php echo sanitizeInput($cat['name']); ?>:</span>
                                    <button class="btn btn-sm btn-primary py-1 px-2.5 rounded-2 text-white fw-medium" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal" onclick="document.getElementById('sub_cat_select').value='<?php echo $cat['id']; ?>';">
                                        <i class="bi bi-plus-lg me-1"></i>Add Subcategory
                                    </button>
                                </div>
                                <?php if (!empty($subs)): ?>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php foreach ($subs as $s): ?>
                                            <span class="badge bg-light text-dark border p-2 d-inline-flex align-items-center gap-2 rounded-2 font-body font-normal">
                                                <span><strong><?php echo sanitizeInput($s['name']); ?></strong> <?php if (!empty($s['hindi_name'])): ?><span class="text-muted">(<?php echo sanitizeInput($s['hindi_name']); ?>)</span><?php endif; ?></span>
                                                <a href="categories.php?action=delete_sub&sub_id=<?php echo $s['id']; ?>" class="text-danger text-decoration-none" onclick="return confirm('Delete subcategory <?php echo sanitizeInput($s['name']); ?>?');"><i class="bi bi-x-circle-fill"></i></a>
                                            </span>
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
                                <option value="<?php echo $cat['id']; ?>"><?php echo sanitizeInput($cat['name']); ?></option>
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

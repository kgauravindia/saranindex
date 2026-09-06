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
        $type = sanitizeInput($_POST['type'] ?? 'PROFESSIONAL');

        if (!empty($name) && $cat_id > 0) {
            if (saveSubcategory($name, $hindi_name, $cat_id, $keywords, $sub_id, $type)) {
                $msg = $sub_id ? "Subcategory #{$sub_id} updated successfully!" : "New subcategory added successfully!";
            } else {
                $msg = "Could not save subcategory. Please check database connection.";
                $msg_type = "danger";
            }
        } else {
            $msg = "Subcategory Name and Parent Category are required.";
            $msg_type = "danger";
        }
    } else {
        $cat_id = !empty($_POST['cat_id']) ? intval($_POST['cat_id']) : null;
        $name = sanitizeInput($_POST['name'] ?? '');
        $hindi_name = sanitizeInput($_POST['hindi_name'] ?? '');
        $icon = sanitizeInput($_POST['icon'] ?? 'bi-folder');
        $section = sanitizeInput($_POST['section'] ?? 'BUSINESS');

        if (!empty($name)) {
            if (saveCategory($name, $hindi_name, $icon, $section, $cat_id)) {
                $msg = $cat_id ? "Category #{$cat_id} updated successfully!" : "New category added successfully!";
            } else {
                $msg = "Could not save category.";
                $msg_type = "danger";
            }
        } else {
            $msg = "Category Name is required.";
            $msg_type = "danger";
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
        <i class="bi <?php echo $msg_type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'; ?> me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Stats & Action Header Row -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Directory Taxonomy Management</h4>
        <p class="text-muted small mb-0">Organize, edit and optimize categories and detailed subcategories across Saran Index</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <button class="btn btn-outline-primary fw-bold rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1.5" onclick="openAddCategoryModal()">
            <i class="bi bi-folder-plus"></i> Add Category
        </button>
        <button class="btn btn-primary fw-bold rounded-pill px-3.5 shadow-sm d-inline-flex align-items-center gap-1.5" onclick="openAddSubcategoryModal()">
            <i class="bi bi-diagram-3-fill"></i> Add Subcategory
        </button>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="stat-card p-3 h-100">
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
        <div class="stat-card p-3 h-100">
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
        <div class="stat-card p-3 h-100">
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

<!-- Tabs Navigation -->
<ul class="nav nav-pills mb-3 gap-2" id="categoryTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill fw-semibold px-3 py-2" id="cat-view-tab" data-bs-toggle="pill" data-bs-target="#cat-view" type="button" role="tab">
            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Categories & Nested Subcategories (<?php echo count($categories); ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill fw-semibold px-3 py-2" id="all-subs-tab" data-bs-toggle="pill" data-bs-target="#all-subs" type="button" role="tab">
            <i class="bi bi-diagram-3-fill me-1"></i> All Subcategories Table (<?php echo count($all_subcategories); ?>)
        </button>
    </li>
</ul>

<div class="tab-content" id="categoryTabContent">

    <!-- Tab 1: Categories with Nested Subcategories Accordion -->
    <div class="tab-pane fade show active" id="cat-view" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom py-3 px-3.5 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-list-nested text-primary me-2"></i>Main Categories Hierarchy</h6>
                <div style="max-width: 280px; width: 100%;">
                    <input type="text" id="categorySearchInput" class="form-control form-control-sm rounded-pill" placeholder="🔍 Filter categories...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle mb-0" id="categoriesTable">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 65px;">#ID</th>
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
                            <tr class="category-row">
                                <td class="fw-bold text-muted">#<?php echo $cat['id']; ?></td>
                                <td>
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-2 p-2 text-center" style="width: 38px; height: 38px;">
                                        <i class="bi <?php echo sanitizeInput($cat['icon']); ?> fs-5"></i>
                                    </div>
                                </td>
                                <td class="fw-bold text-dark cat-name-cell">
                                    <?php echo sanitizeInput($cat['name']); ?>
                                </td>
                                <td class="text-muted cat-hindi-cell"><?php echo sanitizeInput($cat['hindi_name'] ?? '—'); ?></td>
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
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Edit Category" onclick='openEditCategoryModal(<?php echo json_encode($cat); ?>)'>
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-outline-success" title="Add Subcategory to this Category" onclick="openAddSubcategoryModal(<?php echo $cat['id']; ?>)">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                        <a href="categories.php?action=delete&id=<?php echo $cat['id']; ?>" class="btn btn-outline-danger" title="Delete Category" onclick="return confirm('Are you sure you want to delete category <?php echo sanitizeInput($cat['name']); ?>?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <!-- Expandable Subcategory List Row -->
                            <tr class="collapse bg-light" id="subs-cat-<?php echo $cat['id']; ?>">
                                <td colspan="9" class="p-3">
                                    <div class="p-3 bg-white rounded-3 border">
                                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                            <span class="fw-bold text-primary small text-uppercase">
                                                <i class="bi bi-diagram-3-fill me-1"></i>Subcategories in <?php echo sanitizeInput($cat['name']); ?> (#<?php echo $cat['id']; ?>):
                                            </span>
                                            <button class="btn btn-sm btn-primary py-1 px-2.5 rounded-pill text-white fw-medium" onclick="openAddSubcategoryModal(<?php echo $cat['id']; ?>)">
                                                <i class="bi bi-plus-lg me-1"></i>Add Subcategory
                                            </button>
                                        </div>
                                        <?php if (!empty($subs)): ?>
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php foreach ($subs as $s): 
                                                    $sub_listings = intval($s['listing_count'] ?? 0);
                                                ?>
                                                    <div class="badge bg-light text-dark border p-2 d-inline-flex align-items-center gap-2 rounded-3 shadow-xs font-body font-normal">
                                                        <span class="badge bg-primary text-white" style="font-size: 0.75rem;">#<?php echo $s['id']; ?></span>
                                                        <span class="fw-bold text-dark"><?php echo sanitizeInput($s['name']); ?></span>
                                                        <?php if (!empty($s['hindi_name'])): ?>
                                                            <span class="text-secondary small">(<?php echo sanitizeInput($s['hindi_name']); ?>)</span>
                                                        <?php endif; ?>
                                                        <?php if (isset($s['type']) && $s['type'] === 'BUSINESS'): ?>
                                                            <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;">Business</span>
                                                        <?php endif; ?>
                                                        <a href="listings.php?subcategory=<?php echo $s['id']; ?>&search=<?php echo urlencode($s['name']); ?>" class="badge bg-warning text-dark text-decoration-none fw-bold px-2.5 py-1 shadow-sm border border-warning" style="font-size: 0.95rem; line-height: 1.2;" title="View listings in this subcategory">
                                                            <i class="bi bi-collection me-1"></i><?php echo number_format($sub_listings); ?>
                                                        </a>
                                                        <button type="button" class="btn btn-xs btn-outline-primary py-0 px-1.5 rounded-pill" title="Edit Subcategory" onclick='openEditSubcategoryModal(<?php echo json_encode($s); ?>)'>
                                                            <i class="bi bi-pencil-fill" style="font-size: 0.7rem;"></i> Edit
                                                        </button>
                                                        <a href="categories.php?action=delete_sub&sub_id=<?php echo $s['id']; ?>" class="text-danger text-decoration-none ms-0.5" title="Delete Subcategory" onclick="return confirm('Delete subcategory <?php echo sanitizeInput($s['name']); ?>?');">
                                                            <i class="bi bi-x-circle-fill"></i>
                                                        </a>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-muted small py-2">
                                                <i class="bi bi-info-circle me-1"></i>No subcategories created yet for this category. Click <strong>"Add Subcategory"</strong> to create one.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab 2: All Subcategories Table (Searchable & Editable) -->
    <div class="tab-pane fade" id="all-subs" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom py-3 px-3.5 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-diagram-3-fill text-info me-2"></i>All Subcategories (<?php echo count($all_subcategories); ?> Total)</h6>
                <div style="max-width: 320px; width: 100%;">
                    <input type="text" id="subSearchInput" class="form-control form-control-sm rounded-pill" placeholder="🔍 Search subcategories...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle mb-0" id="allSubTable">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 65px;">#ID</th>
                            <th>Parent Category</th>
                            <th>Subcategory (English)</th>
                            <th>Hindi Name</th>
                            <th>Type</th>
                            <th>Listings</th>
                            <th>Keywords</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_subcategories as $s): 
                            $sub_listings = intval($s['listing_count'] ?? 0);
                        ?>
                            <tr class="sub-row">
                                <td class="fw-bold text-muted">#<?php echo $s['id']; ?></td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold px-2.5 py-1 rounded-pill">
                                        <?php echo sanitizeInput($s['category_name'] ?? 'Uncategorized'); ?>
                                    </span>
                                </td>
                                <td class="fw-bold text-dark sub-name-cell">
                                    <?php echo sanitizeInput($s['name']); ?>
                                </td>
                                <td class="text-muted sub-hindi-cell"><?php echo sanitizeInput($s['hindi_name'] ?? '—'); ?></td>
                                <td>
                                    <span class="badge <?php echo ($s['type'] ?? '') === 'BUSINESS' ? 'bg-secondary-subtle text-secondary' : 'bg-info-subtle text-info'; ?> fw-semibold">
                                        <?php echo sanitizeInput($s['type'] ?? 'PROFESSIONAL'); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="listings.php?subcategory=<?php echo $s['id']; ?>&search=<?php echo urlencode($s['name']); ?>" class="badge bg-warning text-dark text-decoration-none fw-bold px-2.5 py-1.5 shadow-sm border border-warning" style="font-size: 0.95rem;" title="View listings">
                                        <i class="bi bi-collection me-1"></i><?php echo number_format($sub_listings); ?>
                                    </a>
                                </td>
                                <td><small class="text-muted"><?php echo sanitizeInput($s['keywords'] ?? '—'); ?></small></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Edit Subcategory" onclick='openEditSubcategoryModal(<?php echo json_encode($s); ?>)'>
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <a href="categories.php?action=delete_sub&sub_id=<?php echo $s['id']; ?>" class="btn btn-outline-danger" title="Delete Subcategory" onclick="return confirm('Delete subcategory <?php echo sanitizeInput($s['name']); ?>?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Category Modal (Add & Edit) -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-bottom py-3 px-4">
                <h5 class="modal-title fw-bold text-white mb-0" id="categoryModalTitle">
                    <i class="bi bi-folder-plus text-warning me-2"></i>Add New Category
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="categories.php" method="POST">
                <input type="hidden" name="action_type" value="category">
                <input type="hidden" name="cat_id" id="cat_id" value="">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="cat_name" class="form-label small fw-semibold">Category Name (English) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cat_name" name="name" placeholder="e.g. Real Estate & Builders" required>
                    </div>

                    <div class="mb-3">
                        <label for="cat_hindi_name" class="form-label small fw-semibold">Hindi Name (हिंदी नाम)</label>
                        <input type="text" class="form-control" id="cat_hindi_name" name="hindi_name" placeholder="उदा. प्रॉपर्टी एवं बिल्डर्स">
                    </div>

                    <div class="mb-3">
                        <label for="cat_icon" class="form-label small fw-semibold">Bootstrap Icon Class</label>
                        <input type="text" class="form-control" id="cat_icon" name="icon" value="bi-shop" placeholder="e.g. bi-house, bi-hospital">
                    </div>

                    <div class="mb-3">
                        <label for="cat_section" class="form-label small fw-semibold">Directory Section</label>
                        <select class="form-select" id="cat_section" name="section">
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
                <div class="modal-footer border-top bg-light py-3 px-4">
                    <button type="button" class="btn btn-light border rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold px-4" id="catSubmitBtn">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Subcategory Modal (Add & Edit) -->
<div class="modal fade" id="subcategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-bottom py-3 px-4">
                <h5 class="modal-title fw-bold text-white mb-0" id="subcategoryModalTitle">
                    <i class="bi bi-diagram-3-fill text-warning me-2"></i>Add New Subcategory
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="categories.php" method="POST">
                <input type="hidden" name="action_type" value="subcategory">
                <input type="hidden" name="sub_id" id="sub_id" value="">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="sub_cat_select" class="form-label small fw-semibold">Parent Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="sub_cat_select" name="category_id" required>
                            <option value="">Select Parent Category</option>
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
                        <label for="sub_type" class="form-label small fw-semibold">Subcategory Classification / Type</label>
                        <select class="form-select" id="sub_type" name="type">
                            <option value="PROFESSIONAL">PROFESSIONAL (Doctors, Advocates, CAs, Teachers, Engineers)</option>
                            <option value="BUSINESS">BUSINESS (Shops, Showrooms, Retailers, Agencies, Wholesalers)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="sub_keywords" class="form-label small fw-semibold">Search Keywords (Comma Separated)</label>
                        <input type="text" class="form-control" id="sub_keywords" name="keywords" placeholder="e.g. kv, cbse, central school">
                        <small class="text-muted">Keywords help search users find this subcategory quickly.</small>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-3 px-4">
                    <button type="button" class="btn btn-light border rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold px-4" id="subSubmitBtn">Save Subcategory</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Category Modal Handlers
function openAddCategoryModal() {
    document.getElementById('categoryModalTitle').innerHTML = '<i class="bi bi-folder-plus text-warning me-2"></i>Add New Category';
    document.getElementById('cat_id').value = '';
    document.getElementById('cat_name').value = '';
    document.getElementById('cat_hindi_name').value = '';
    document.getElementById('cat_icon').value = 'bi-shop';
    document.getElementById('cat_section').value = 'BUSINESS';
    document.getElementById('catSubmitBtn').textContent = 'Create Category';
    
    var modal = new bootstrap.Modal(document.getElementById('categoryModal'));
    modal.show();
}

function openEditCategoryModal(cat) {
    document.getElementById('categoryModalTitle').innerHTML = '<i class="bi bi-pencil-square text-warning me-2"></i>Edit Category #' + cat.id;
    document.getElementById('cat_id').value = cat.id;
    document.getElementById('cat_name').value = cat.name || '';
    document.getElementById('cat_hindi_name').value = cat.hindi_name || '';
    document.getElementById('cat_icon').value = cat.icon || 'bi-shop';
    document.getElementById('cat_section').value = cat.section || 'BUSINESS';
    document.getElementById('catSubmitBtn').textContent = 'Update Category';
    
    var modal = new bootstrap.Modal(document.getElementById('categoryModal'));
    modal.show();
}

// Subcategory Modal Handlers
function openAddSubcategoryModal(categoryId) {
    document.getElementById('subcategoryModalTitle').innerHTML = '<i class="bi bi-diagram-3-fill text-warning me-2"></i>Add New Subcategory';
    document.getElementById('sub_id').value = '';
    document.getElementById('sub_name').value = '';
    document.getElementById('sub_hindi_name').value = '';
    document.getElementById('sub_keywords').value = '';
    document.getElementById('sub_type').value = 'PROFESSIONAL';
    if (categoryId) {
        document.getElementById('sub_cat_select').value = categoryId;
    } else {
        document.getElementById('sub_cat_select').value = '';
    }
    document.getElementById('subSubmitBtn').textContent = 'Create Subcategory';
    
    var modal = new bootstrap.Modal(document.getElementById('subcategoryModal'));
    modal.show();
}

function openEditSubcategoryModal(sub) {
    document.getElementById('subcategoryModalTitle').innerHTML = '<i class="bi bi-pencil-square text-warning me-2"></i>Edit Subcategory #' + sub.id;
    document.getElementById('sub_id').value = sub.id;
    document.getElementById('sub_cat_select').value = sub.category_id || '';
    document.getElementById('sub_name').value = sub.name || '';
    document.getElementById('sub_hindi_name').value = sub.hindi_name || '';
    document.getElementById('sub_type').value = sub.type || 'PROFESSIONAL';
    document.getElementById('sub_keywords').value = sub.keywords || '';
    document.getElementById('subSubmitBtn').textContent = 'Update Subcategory';
    
    var modal = new bootstrap.Modal(document.getElementById('subcategoryModal'));
    modal.show();
}

// Quick Search Filtering for Categories Tab
document.getElementById('categorySearchInput')?.addEventListener('input', function() {
    var query = this.value.toLowerCase().trim();
    var rows = document.querySelectorAll('#categoriesTable tbody .category-row');
    rows.forEach(function(row) {
        var name = row.querySelector('.cat-name-cell')?.textContent.toLowerCase() || '';
        var hindi = row.querySelector('.cat-hindi-cell')?.textContent.toLowerCase() || '';
        if (name.includes(query) || hindi.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Quick Search Filtering for Subcategories Table Tab
document.getElementById('subSearchInput')?.addEventListener('input', function() {
    var query = this.value.toLowerCase().trim();
    var rows = document.querySelectorAll('#allSubTable tbody .sub-row');
    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        if (text.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


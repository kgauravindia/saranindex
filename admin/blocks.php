<?php
$header_title = "Manage Saran District Blocks";
require_once __DIR__ . '/includes/header.php';

$msg = '';
$msg_type = 'success';

// Handle Block Save / Edit Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_block'])) {
    $block_id = !empty($_POST['block_id']) ? intval($_POST['block_id']) : null;
    $name = sanitizeInput($_POST['name'] ?? '');
    $hindi_name = sanitizeInput($_POST['hindi_name'] ?? '');
    $name_english = sanitizeInput($_POST['name_english'] ?? '');
    $slug = sanitizeInput($_POST['slug'] ?? '');
    $pincode = sanitizeInput($_POST['pincode'] ?? '');
    $total_panchayats = intval($_POST['total_panchayats'] ?? 0);

    if (!empty($name)) {
        if (saveBlock($name, $hindi_name, $name_english, $slug, $pincode, $total_panchayats, $block_id)) {
            $msg = $block_id ? "Block updated successfully!" : "New Saran Block added successfully!";
        } else {
            $msg = "Could not save block details.";
            $msg_type = "danger";
        }
    } else {
        $msg = "Block name is required.";
        $msg_type = "danger";
    }
}

// Handle Block Delete Action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $del_id = intval($_GET['id']);
    if (deleteBlock($del_id)) {
        $msg = "Block #{$del_id} deleted successfully!";
        $msg_type = "danger";
    } else {
        $msg = "Failed to delete block #{$del_id}.";
        $msg_type = "danger";
    }
}

$search = trim($_GET['search'] ?? '');
$blocks = getAllAdminBlocks($search);

// Calculate totals
$total_blocks_count = count($blocks);
$total_panchayats_sum = array_sum(array_column($blocks, 'total_panchayats'));
$total_listings_sum = array_sum(array_column($blocks, 'listing_count'));
?>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Header Actions Bar -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Saran District Blocks Management</h4>
        <p class="text-muted small mb-0">View, search, create, and manage all 20 Administrative CD Blocks in Saran District (Bihar).</p>
    </div>
    <div class="d-flex gap-2">
        <a href="../blocks" target="_blank" class="btn btn-outline-secondary fw-medium btn-sm rounded-3 py-2 px-3">
            <i class="bi bi-box-arrow-up-right me-1"></i> Public Directory View
        </a>
        <button type="button" class="btn btn-primary fw-bold btn-sm rounded-3 py-2 px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#blockModal" onclick="resetBlockForm()">
            <i class="bi bi-plus-lg me-1"></i> Add New Block
        </button>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Total District Blocks</div>
                    <h3 class="fw-bold text-dark my-1"><?php echo number_format($total_blocks_count); ?></h3>
                    <small class="text-muted">CD Blocks in Saran</small>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-geo-alt"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Total Panchayats</div>
                    <h3 class="fw-bold text-success my-1"><?php echo number_format($total_panchayats_sum); ?></h3>
                    <small class="text-success fw-medium"><i class="bi bi-houses me-1"></i>Gram Panchayats</small>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-diagram-2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Directory Listings</div>
                    <h3 class="fw-bold text-warning my-1"><?php echo number_format($total_listings_sum); ?></h3>
                    <small class="text-muted"><i class="bi bi-collection me-1"></i>Listings mapped to blocks</small>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-pin-map-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="blocks.php" class="row g-2 align-items-center">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search block by name, Hindi name, slug, or pincode..." value="<?php echo sanitizeInput($search); ?>">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold">Search</button>
                <?php if (!empty($search)): ?>
                    <a href="blocks.php" class="btn btn-outline-secondary">Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Blocks Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">#ID</th>
                    <th>Block Name (English & Hindi)</th>
                    <th>URL Slug</th>
                    <th>Pincode</th>
                    <th>Total Panchayats</th>
                    <th>Directory Listings</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($blocks)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No blocks found matching your criteria.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($blocks as $blk): ?>
                        <tr>
                            <td class="fw-bold text-muted">#<?php echo $blk['id']; ?></td>
                            <td>
                                <div class="fw-bold text-dark fs-6"><?php echo sanitizeInput($blk['name']); ?></div>
                                <?php if (!empty($blk['hindi_name'])): ?>
                                    <small class="text-primary fw-medium"><?php echo sanitizeInput($blk['hindi_name']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded text-primary small"><?php echo sanitizeInput($blk['slug']); ?></code>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><i class="bi bi-geo me-1"></i><?php echo sanitizeInput($blk['pincode'] ?? 'N/A'); ?></span>
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1 rounded-pill"><i class="bi bi-houses me-1"></i><?php echo number_format($blk['total_panchayats']); ?> Panchayats</span>
                            </td>
                            <td>
                                <a href="listings.php?search=<?php echo urlencode($blk['name']); ?>" class="badge bg-primary text-decoration-none px-2.5 py-1">
                                    <i class="bi bi-collection me-1"></i><?php echo number_format($blk['listing_count']); ?> Listings
                                </a>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary" title="Edit Block" 
                                            onclick='editBlock(<?php echo json_encode($blk, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <a href="../block/<?php echo $blk['slug']; ?>" target="_blank" class="btn btn-outline-info" title="View Public Block Page">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="blocks.php?action=delete&id=<?php echo $blk['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this block?');" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add / Edit Block Modal -->
<div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="blocks.php">
                <input type="hidden" name="save_block" value="1">
                <input type="hidden" name="block_id" id="block_id" value="">
                
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="blockModalLabel">Add New Saran Block</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Block Name (Primary / English) <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="block_name" class="form-control" placeholder="e.g. Chapra Sadar" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hindi Name (हिंदी नाम)</label>
                        <input type="text" name="hindi_name" id="block_hindi_name" class="form-control" placeholder="उदा. छपरा सदर">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">English Name Alias</label>
                        <input type="text" name="name_english" id="block_name_english" class="form-control" placeholder="e.g. Chapra Sadar">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">URL Slug</label>
                            <input type="text" name="slug" id="block_slug" class="form-control" placeholder="chapra-sadar">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Pincode</label>
                            <input type="text" name="pincode" id="block_pincode" class="form-control" placeholder="841301">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Total Gram Panchayats</label>
                        <input type="number" name="total_panchayats" id="block_total_panchayats" class="form-control" placeholder="18" min="0" value="0">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Save Block</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetBlockForm() {
    document.getElementById('block_id').value = '';
    document.getElementById('block_name').value = '';
    document.getElementById('block_hindi_name').value = '';
    document.getElementById('block_name_english').value = '';
    document.getElementById('block_slug').value = '';
    document.getElementById('block_pincode').value = '';
    document.getElementById('block_total_panchayats').value = '0';
    document.getElementById('blockModalLabel').innerText = 'Add New Saran Block';
}

function editBlock(blk) {
    document.getElementById('block_id').value = blk.id || '';
    document.getElementById('block_name').value = blk.name || '';
    document.getElementById('block_hindi_name').value = blk.hindi_name || '';
    document.getElementById('block_name_english').value = blk.name_english || '';
    document.getElementById('block_slug').value = blk.slug || '';
    document.getElementById('block_pincode').value = blk.pincode || '';
    document.getElementById('block_total_panchayats').value = blk.total_panchayats || 0;
    document.getElementById('blockModalLabel').innerText = 'Edit Block: ' + (blk.name || '');
    
    var modal = new bootstrap.Modal(document.getElementById('blockModal'));
    modal.show();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

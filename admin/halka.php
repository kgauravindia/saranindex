<?php
$header_title = "Manage Revenue Halka & Mouzas";
require_once __DIR__ . '/includes/header.php';

$msg = '';
$msg_type = 'success';

// Handle Halka Save / Edit Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_halka'])) {
    $halka_id = !empty($_POST['halka_id']) ? intval($_POST['halka_id']) : null;
    $block = sanitizeInput($_POST['block'] ?? '');
    $halka_code = sanitizeInput($_POST['halka_code'] ?? '');
    $halka_name = sanitizeInput($_POST['halka_name'] ?? '');
    $halka_english = sanitizeInput($_POST['halka_english'] ?? '');
    $mauja_code = sanitizeInput($_POST['mauja_code'] ?? '');
    $mauja_name = sanitizeInput($_POST['mauja_name'] ?? '');
    $mauja_english = sanitizeInput($_POST['mauja_english'] ?? '');

    if (!empty($block) && !empty($halka_name)) {
        if (saveHalka($block, $halka_code, $halka_name, $halka_english, $mauja_code, $mauja_name, $mauja_english, $halka_id)) {
            $msg = $halka_id ? "Revenue Halka Mouza record updated successfully!" : "New Halka Mouza added successfully!";
        } else {
            $msg = "Could not save Halka Mouza record.";
            $msg_type = "danger";
        }
    } else {
        $msg = "Block and Halka Name are required.";
        $msg_type = "danger";
    }
}

// Handle Halka Delete Action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $del_id = intval($_GET['id']);
    if (deleteHalka($del_id)) {
        $msg = "Halka Mouza record #{$del_id} deleted successfully!";
        $msg_type = "danger";
    } else {
        $msg = "Failed to delete Halka Mouza record #{$del_id}.";
        $msg_type = "danger";
    }
}

$block_filter = trim($_GET['block'] ?? '');
$search = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 24;
$offset = ($page - 1) * $limit;

$halka_records = getHalkaRecords($block_filter, $search, $limit, $offset);
$total_halka_count = getTotalHalkaCount($block_filter, $search);
$total_pages = ceil($total_halka_count / $limit);

$all_blocks = getBlocks();
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
        <h4 class="fw-bold mb-1"><i class="bi bi-houses-fill text-primary me-2"></i>Revenue Halka & Mouza Directory Management</h4>
        <p class="text-muted small mb-0">View, search, create, and manage Revenue Halka numbers and Mouza village records across Saran District.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary fw-bold btn-sm rounded-3 py-2 px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#halkaModal" onclick="resetHalkaForm()">
            <i class="bi bi-plus-lg me-1"></i> Add Halka / Mouza Record
        </button>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="halka.php" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="block" class="form-select">
                    <option value="">-- All Saran Blocks --</option>
                    <?php foreach ($all_blocks as $b): ?>
                        <option value="<?php echo sanitizeInput($b['name']); ?>" <?php echo ($block_filter === $b['name']) ? 'selected' : ''; ?>>
                            <?php echo sanitizeInput($b['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by Halka name, Mouza name, code..." value="<?php echo sanitizeInput($search); ?>">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold">Filter</button>
                <?php if (!empty($block_filter) || !empty($search)): ?>
                    <a href="halka.php" class="btn btn-outline-secondary">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Halka Records Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-list-nested me-2 text-primary"></i>Halka & Mouza Records (Total: <?php echo number_format($total_halka_count); ?>)</h6>
        <span class="badge bg-light text-secondary border">Page <?php echo $page; ?> of <?php echo max(1, $total_pages); ?></span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">#ID</th>
                    <th>CD Block</th>
                    <th>Halka Code & Name</th>
                    <th>Revenue Mouza Name</th>
                    <th>Mouza Code</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($halka_records)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No Halka Mouza records found matching your criteria.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($halka_records as $hk): ?>
                        <tr>
                            <td class="fw-bold text-muted">#<?php echo $hk['id']; ?></td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold">
                                    <i class="bi bi-pin-map me-1"></i><?php echo sanitizeInput($hk['block']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">
                                    <span class="badge bg-warning text-dark me-1">Halka #<?php echo sanitizeInput($hk['halka_code']); ?></span>
                                    <?php echo sanitizeInput($hk['halka_name']); ?>
                                </div>
                                <?php if (!empty($hk['halka_english'])): ?>
                                    <small class="text-muted"><?php echo sanitizeInput($hk['halka_english']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-6"><?php echo sanitizeInput($hk['mauja_name']); ?></div>
                                <?php if (!empty($hk['mauja_english'])): ?>
                                    <small class="text-muted"><?php echo sanitizeInput($hk['mauja_english']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded text-secondary small"><?php echo sanitizeInput($hk['mauja_code'] ?? 'N/A'); ?></code>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary" title="Edit Record" 
                                            onclick='editHalka(<?php echo json_encode($hk, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <a href="halka.php?action=delete&id=<?php echo $hk['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this Halka record?');" title="Delete">
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

    <!-- Pagination Footer -->
    <?php if ($total_pages > 1): ?>
        <div class="card-footer bg-white border-top py-3">
            <nav aria-label="Halka Pagination">
                <ul class="pagination pagination-sm justify-content-center mb-0">
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="halka.php?block=<?php echo urlencode($block_filter); ?>&search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>">Previous</a>
                    </li>
                    <?php
                    $start_p = max(1, $page - 2);
                    $end_p = min($total_pages, $page + 2);
                    for ($p = $start_p; $p <= $end_p; $p++):
                    ?>
                        <li class="page-item <?php echo ($p === $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="halka.php?block=<?php echo urlencode($block_filter); ?>&search=<?php echo urlencode($search); ?>&page=<?php echo $p; ?>"><?php echo $p; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="halka.php?block=<?php echo urlencode($block_filter); ?>&search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Add / Edit Halka Modal -->
<div class="modal fade" id="halkaModal" tabindex="-1" aria-labelledby="halkaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="halka.php">
                <input type="hidden" name="save_halka" value="1">
                <input type="hidden" name="halka_id" id="halka_id" value="">
                
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="halkaModalLabel">Add Halka Mouza Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">CD Block Name <span class="text-danger">*</span></label>
                        <select name="block" id="halka_block" class="form-select" required>
                            <option value="">-- Select Block --</option>
                            <?php foreach ($all_blocks as $b): ?>
                                <option value="<?php echo sanitizeInput($b['name']); ?>"><?php echo sanitizeInput($b['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label fw-semibold">Halka Code</label>
                            <input type="text" name="halka_code" id="halka_code" class="form-control" placeholder="e.g. 1">
                        </div>
                        <div class="col-8">
                            <label class="form-label fw-semibold">Halka Name (Hindi) <span class="text-danger">*</span></label>
                            <input type="text" name="halka_name" id="halka_name" class="form-control" placeholder="उदा. हल्का - 01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Halka Name (English)</label>
                        <input type="text" name="halka_english" id="halka_english" class="form-control" placeholder="e.g. Halka 01">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label fw-semibold">Mouza Code</label>
                            <input type="text" name="mauja_code" id="mauja_code" class="form-control" placeholder="234850">
                        </div>
                        <div class="col-8">
                            <label class="form-label fw-semibold">Mouza Village Name (Hindi)</label>
                            <input type="text" name="mauja_name" id="mauja_name" class="form-control" placeholder="उदा. मौजा का नाम">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mouza Village Name (English)</label>
                        <input type="text" name="mauja_english" id="mauja_english" class="form-control" placeholder="e.g. Mouza Name English">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetHalkaForm() {
    document.getElementById('halka_id').value = '';
    document.getElementById('halka_block').value = '';
    document.getElementById('halka_code').value = '';
    document.getElementById('halka_name').value = '';
    document.getElementById('halka_english').value = '';
    document.getElementById('mauja_code').value = '';
    document.getElementById('mauja_name').value = '';
    document.getElementById('mauja_english').value = '';
    document.getElementById('halkaModalLabel').innerText = 'Add Halka Mouza Record';
}

function editHalka(hk) {
    document.getElementById('halka_id').value = hk.id || '';
    document.getElementById('halka_block').value = hk.block || '';
    document.getElementById('halka_code').value = hk.halka_code || '';
    document.getElementById('halka_name').value = hk.halka_name || '';
    document.getElementById('halka_english').value = hk.halka_english || '';
    document.getElementById('mauja_code').value = hk.mauja_code || '';
    document.getElementById('mauja_name').value = hk.mauja_name || '';
    document.getElementById('mauja_english').value = hk.mauja_english || '';
    document.getElementById('halkaModalLabel').innerText = 'Edit Halka Mouza Record #' + (hk.id || '');
    
    var modal = new bootstrap.Modal(document.getElementById('halkaModal'));
    modal.show();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

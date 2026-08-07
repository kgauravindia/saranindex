<?php
$header_title = "Duplicate Listings";
require_once __DIR__ . '/includes/header.php';

$msg = '';
$msg_type = 'success';

// --- Handle Delete Action ---
if (isset($_POST['action']) && $_POST['action'] === 'delete_selected') {
    $ids = isset($_POST['delete_ids']) ? (array)$_POST['delete_ids'] : [];
    $deleted = 0;
    foreach ($ids as $del_id) {
        $del_id = intval($del_id);
        if ($del_id > 0 && deleteListing($del_id)) {
            $deleted++;
        }
    }
    $msg = "Deleted {$deleted} duplicate listing(s) successfully.";
    $msg_type = $deleted > 0 ? 'success' : 'warning';
}

// --- Handle Single Delete ---
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $del_id = intval($_GET['id']);
    if ($del_id > 0 && deleteListing($del_id)) {
        $msg = "Listing #{$del_id} deleted successfully.";
        $msg_type = 'success';
    } else {
        $msg = "Failed to delete listing #{$del_id}.";
        $msg_type = 'danger';
    }
}

// --- Find Duplicates: same LOWER(title) + last 10 digits of mobile ---
$db = getDB();
$duplicate_groups = [];

if ($db) {
    // Find groups with title + mobile that appear more than once
    $sql = "
        SELECT 
            LOWER(TRIM(title)) AS norm_title,
            RIGHT(REPLACE(REPLACE(REPLACE(mobile, ' ', ''), '-', ''), '+91', ''), 10) AS norm_mobile,
            COUNT(*) AS total_count,
            GROUP_CONCAT(id ORDER BY id ASC SEPARATOR ',') AS ids
        FROM listings
        GROUP BY norm_title, norm_mobile
        HAVING COUNT(*) > 1
        ORDER BY total_count DESC, norm_title ASC
    ";

    $groupRows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    foreach ($groupRows as $group) {
        $id_list = array_map('intval', explode(',', $group['ids']));
        // Fetch full details for each ID in the group
        $placeholders = implode(',', array_fill(0, count($id_list), '?'));
        $stmt = $db->prepare("
            SELECT l.id, l.title, l.mobile, l.status, l.slug, l.created_at,
                   l.is_verified, l.is_featured, l.plan_type, l.block_id,
                   b.name AS block_name
            FROM listings l
            LEFT JOIN blocks b ON l.block_id = b.id
            WHERE l.id IN ({$placeholders})
            ORDER BY l.id ASC
        ");
        $stmt->execute($id_list);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) > 1) {
            $duplicate_groups[] = [
                'norm_title'   => $group['norm_title'],
                'norm_mobile'  => $group['norm_mobile'],
                'total'        => intval($group['total_count']),
                'listings'     => $rows,
            ];
        }
    }
}

$total_duplicate_groups = count($duplicate_groups);
$total_extra_entries = array_sum(array_column($duplicate_groups, 'total')) - $total_duplicate_groups;
?>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-<?php echo $msg_type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'; ?> me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Header Bar -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Admin</a></li>
                <li class="breadcrumb-item"><a href="listings.php" class="text-decoration-none text-muted">Directory Listings</a></li>
                <li class="breadcrumb-item active fw-semibold text-danger" aria-current="page">Duplicate Listings</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-1 text-dark">
            <i class="bi bi-layers text-danger me-2"></i>Duplicate Listings
        </h4>
        <p class="text-muted small mb-0">
            Listings with identical <strong>Title + Mobile Number</strong> grouped together for easy review and cleanup.
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-danger fw-bold px-3 py-2 rounded-3 shadow-sm" onclick="deleteAllDuplicates()" <?php echo $total_duplicate_groups === 0 ? 'disabled' : ''; ?>>
            <i class="bi bi-trash3-fill me-1"></i> Delete All Duplicates
        </button>
        <a href="listings.php" class="btn btn-outline-secondary px-3 py-2 rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Listings
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm rounded-3 text-center py-3">
            <div class="fs-2 fw-bold text-danger"><?php echo $total_duplicate_groups; ?></div>
            <div class="small text-muted">Duplicate Groups Found</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm rounded-3 text-center py-3">
            <div class="fs-2 fw-bold text-warning"><?php echo $total_extra_entries; ?></div>
            <div class="small text-muted">Extra Entries to Remove</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm rounded-3 text-center py-3">
            <div class="fs-2 fw-bold text-success"><?php echo $total_duplicate_groups; ?></div>
            <div class="small text-muted">Unique Listings to Keep</div>
        </div>
    </div>
</div>

<?php if (empty($duplicate_groups)): ?>
    <div class="card border-0 shadow-sm rounded-3 text-center py-5">
        <i class="bi bi-check-circle-fill text-success fs-1 mb-3 d-block"></i>
        <h5 class="fw-bold text-dark mb-1">No Duplicate Listings Found!</h5>
        <p class="text-muted mb-3">All listings in the directory have unique Title + Mobile combinations.</p>
        <a href="listings.php" class="btn btn-outline-primary rounded-pill px-4">Back to Listings</a>
    </div>
<?php else: ?>

<form method="POST" action="duplicates.php" id="batchDeleteForm">
    <input type="hidden" name="action" value="delete_selected">

    <!-- Bulk Action Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-3 px-3 py-2 d-flex flex-row align-items-center justify-content-between gap-2 flex-wrap">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="selectAllDuplicates()">
                <i class="bi bi-check-all me-1"></i>Select All Extras
            </button>
            <button type="button" class="btn btn-sm btn-outline-light text-muted border rounded-pill px-3" onclick="deselectAll()">
                <i class="bi bi-x-circle me-1"></i>Deselect All
            </button>
            <span id="selectionBadge" class="badge bg-danger rounded-pill px-3 py-2" style="display:none;">0 selected</span>
        </div>
        <button type="submit" class="btn btn-danger btn-sm fw-bold px-4 rounded-pill shadow-sm" id="batchDeleteBtn" style="display:none;"
            onclick="return confirm('Are you sure you want to delete all selected duplicate listings? This cannot be undone.')">
            <i class="bi bi-trash3-fill me-1"></i>Delete Selected
        </button>
    </div>

    <?php foreach ($duplicate_groups as $gi => $group): ?>
        <?php $listings = $group['listings']; ?>
        <?php $keepId = $listings[0]['id']; // Oldest (lowest ID) is the "keep" entry ?>

        <div class="card border-0 shadow mb-4 rounded-4 overflow-hidden dup-group-card">
            <!-- Group Header -->
            <div class="card-header bg-danger bg-opacity-10 border-bottom border-danger border-opacity-25 d-flex align-items-center justify-content-between py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger rounded-circle p-2" style="width:28px;height:28px;line-height:12px;"><?php echo $group['total']; ?></span>
                    <div>
                        <div class="fw-bold text-dark"><?php echo htmlspecialchars(ucwords($group['norm_title'])); ?></div>
                        <div class="small text-muted"><i class="bi bi-telephone me-1"></i><?php echo htmlspecialchars($group['norm_mobile']); ?></div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">
                        <i class="bi bi-layers me-1"></i><?php echo $group['total']; ?> Entries — <?php echo $group['total'] - 1; ?> Extra
                    </span>
                    <!-- Quick: delete all except first -->
                    <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 fw-semibold"
                        onclick="selectGroupExtras(<?php echo htmlspecialchars(json_encode(array_slice(array_column($listings, 'id'), 1))); ?>)">
                        <i class="bi bi-trash me-1"></i>Select Extras
                    </button>
                </div>
            </div>

            <!-- Listings in group -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-light">
                        <tr>
                            <th style="width:40px;"><input type="checkbox" class="form-check-input group-master-cb" data-group="<?php echo $gi; ?>" title="Select all extras in group" onchange="toggleGroupExtras(this, <?php echo $gi; ?>)"></th>
                            <th>#ID</th>
                            <th>Title</th>
                            <th>Mobile</th>
                            <th>Block</th>
                            <th>Status</th>
                            <th>Plan</th>
                            <th>Created</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listings as $idx => $item): ?>
                            <?php $isKeep = ($item['id'] === $keepId); ?>
                            <tr class="<?php echo $isKeep ? 'table-success' : 'table-danger bg-opacity-25'; ?>">
                                <td>
                                    <?php if (!$isKeep): ?>
                                        <input type="checkbox" name="delete_ids[]" value="<?php echo $item['id']; ?>"
                                            class="form-check-input dup-cb group-cb-<?php echo $gi; ?>"
                                            onchange="updateSelectionCount()">
                                    <?php else: ?>
                                        <i class="bi bi-shield-check text-success" title="Keep (oldest entry)"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold <?php echo $isKeep ? 'text-success' : 'text-danger'; ?>">
                                    #<?php echo $item['id']; ?>
                                    <?php if ($isKeep): ?>
                                        <span class="badge bg-success ms-1" style="font-size:0.6rem;">KEEP</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger ms-1" style="font-size:0.6rem;">DUP</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="../profile.php?slug=<?php echo $item['slug']; ?>" target="_blank"
                                        class="text-dark text-decoration-none fw-semibold small">
                                        <?php echo htmlspecialchars($item['title']); ?>
                                        <i class="bi bi-box-arrow-up-right ms-1 text-muted" style="font-size:0.7rem;"></i>
                                    </a>
                                </td>
                                <td class="text-muted"><?php echo htmlspecialchars($item['mobile']); ?></td>
                                <td><?php echo htmlspecialchars($item['block_name'] ?? '—'); ?></td>
                                <td>
                                    <?php
                                        $st = strtoupper($item['status']);
                                        $stBadge = ['ACTIVE' => 'success', 'PENDING' => 'warning', 'REJECTED' => 'danger'];
                                        $badge = $stBadge[$st] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $badge; ?>-subtle text-<?php echo $badge; ?> border border-<?php echo $badge; ?>-subtle rounded-pill">
                                        <?php echo ucfirst(strtolower($st)); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                        $plan = strtoupper($item['plan_type'] ?? 'FREE');
                                        $planBadge = ['PLATINUM' => 'dark', 'GOLD' => 'warning', 'FREE' => 'secondary'];
                                        $planBadgeType = $planBadge[$plan] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $planBadgeType; ?>"><?php echo $plan; ?></span>
                                </td>
                                <td class="text-muted">
                                    <?php echo !empty($item['created_at']) ? date('d M Y', strtotime($item['created_at'])) : '—'; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="listing_edit.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if (!$isKeep): ?>
                                            <a href="duplicates.php?action=delete&id=<?php echo $item['id']; ?>"
                                                class="btn btn-outline-danger" title="Delete this duplicate"
                                                onclick="return confirm('Delete Listing #<?php echo $item['id']; ?> (<?php echo htmlspecialchars(addslashes($item['title'])); ?>)? This cannot be undone.')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-outline-success btn-sm" disabled title="This entry will be kept">
                                                <i class="bi bi-shield-check"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Bottom Submit -->
    <div class="text-end mb-5">
        <button type="submit" class="btn btn-danger fw-bold px-5 py-2 rounded-pill shadow"
            onclick="return confirm('Delete ALL selected duplicate entries? This action cannot be undone.')">
            <i class="bi bi-trash3-fill me-1"></i> Delete All Selected Duplicates
        </button>
    </div>
</form>
<?php endif; ?>

<script>
function updateSelectionCount() {
    const cbs = document.querySelectorAll('.dup-cb:checked');
    const badge = document.getElementById('selectionBadge');
    const btn = document.getElementById('batchDeleteBtn');
    const n = cbs.length;
    if (n > 0) {
        badge.style.display = '';
        badge.textContent = n + ' selected';
        btn.style.display = '';
    } else {
        badge.style.display = 'none';
        btn.style.display = 'none';
    }
}

function selectAllDuplicates() {
    document.querySelectorAll('.dup-cb').forEach(cb => cb.checked = true);
    updateSelectionCount();
}

function deselectAll() {
    document.querySelectorAll('.dup-cb, .group-master-cb').forEach(cb => cb.checked = false);
    updateSelectionCount();
}

function toggleGroupExtras(masterCb, groupIdx) {
    document.querySelectorAll('.group-cb-' + groupIdx).forEach(cb => {
        cb.checked = masterCb.checked;
    });
    updateSelectionCount();
}

function selectGroupExtras(ids) {
    ids.forEach(id => {
        const cb = document.querySelector('input[name="delete_ids[]"][value="' + id + '"]');
        if (cb) cb.checked = true;
    });
    updateSelectionCount();
}

function deleteAllDuplicates() {
    selectAllDuplicates();
    if (document.querySelectorAll('.dup-cb:checked').length === 0) {
        alert('No duplicates to delete.');
        return;
    }
    if (confirm('This will delete ALL extra duplicate entries (keeping the oldest of each group). Are you sure?')) {
        document.getElementById('batchDeleteForm').submit();
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

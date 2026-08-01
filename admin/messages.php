<?php
$header_title = "Contact Messages & Inquiries";
require_once __DIR__ . '/includes/header.php';

$msg = '';
$msg_type = 'success';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $target_id = intval($_GET['id']);

    if ($action === 'mark_read') {
        if (updateContactMessageStatus($target_id, 'READ')) {
            $msg = "Message #{$target_id} marked as READ.";
        }
    } elseif ($action === 'mark_replied') {
        if (updateContactMessageStatus($target_id, 'REPLIED')) {
            $msg = "Message #{$target_id} marked as REPLIED.";
        }
    } elseif ($action === 'delete') {
        if (deleteContactMessage($target_id)) {
            $msg = "Message #{$target_id} deleted successfully.";
            $msg_type = "danger";
        }
    }
}

$messages = getAllContactMessages();
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
        <h4 class="fw-bold mb-1"><i class="bi bi-envelope-fill text-primary me-2"></i>Contact Messages & Inquiries</h4>
        <p class="text-muted small mb-0">View and respond to inquiries submitted via the Contact Us form.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">#ID</th>
                    <th>Sender Info</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($messages)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                            No contact inquiries submitted yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($messages as $m): ?>
                        <tr class="<?php echo $m['status'] === 'UNREAD' ? 'table-warning bg-opacity-10' : ''; ?>">
                            <td class="fw-bold text-muted">#<?php echo $m['id']; ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo sanitizeInput($m['name']); ?></div>
                                <div class="small text-muted"><i class="bi bi-telephone me-1"></i><a href="tel:<?php echo sanitizeInput($m['mobile']); ?>" class="text-decoration-none"><?php echo sanitizeInput($m['mobile']); ?></a></div>
                                <?php if (!empty($m['email'])): ?>
                                    <div class="small text-muted"><i class="bi bi-envelope me-1"></i><a href="mailto:<?php echo sanitizeInput($m['email']); ?>" class="text-decoration-none"><?php echo sanitizeInput($m['email']); ?></a></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1"><?php echo sanitizeInput($m['subject']); ?></span>
                            </td>
                            <td style="max-width: 320px; white-space: normal;">
                                <div class="small text-dark" style="line-height: 1.5;"><?php echo nl2br(sanitizeInput($m['message'])); ?></div>
                            </td>
                            <td class="small text-muted" style="white-space: nowrap;">
                                <?php echo date('d M Y, h:i A', strtotime($m['created_at'])); ?>
                            </td>
                            <td>
                                <?php if ($m['status'] === 'UNREAD'): ?>
                                    <span class="badge bg-danger bg-opacity-15 text-danger font-weight-semibold">Unread</span>
                                <?php elseif ($m['status'] === 'READ'): ?>
                                    <span class="badge bg-warning bg-opacity-15 text-warning font-weight-semibold">Read</span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-15 text-success font-weight-semibold">Replied</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end" style="white-space: nowrap;">
                                <?php if ($m['status'] === 'UNREAD'): ?>
                                    <a href="messages.php?action=mark_read&id=<?php echo $m['id']; ?>" class="btn btn-outline-warning btn-sm me-1" title="Mark as Read">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if ($m['status'] !== 'REPLIED'): ?>
                                    <a href="messages.php?action=mark_replied&id=<?php echo $m['id']; ?>" class="btn btn-outline-success btn-sm me-1" title="Mark as Replied">
                                        <i class="bi bi-check2-circle"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="messages.php?action=delete&id=<?php echo $m['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this message?');" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

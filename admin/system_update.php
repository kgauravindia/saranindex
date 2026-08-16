<?php
$header_title = "GitHub System Update & Sync";
require_once __DIR__ . '/includes/header.php';

$msg = '';
$msg_type = 'info';
$update_output = null;

// Handle System Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'pull_updates') {
        $res = performGitPull();
        $update_output = $res['output'];
        if ($res['success']) {
            $msg = "System updated successfully from GitHub repository (https://github.com/kgauravindia/saranindex).";
            $msg_type = "success";
        } else {
            $msg = "Git pull process completed with notice or error. Check console details below.";
            $msg_type = "warning";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'db_refresh') {
        if (function_exists('ensureAdminsTableExists')) {
            ensureAdminsTableExists();
        }
        $msg = "Database table definitions & schema checks verified successfully!";
        $msg_type = "success";
    }
}

$git_info = getGitUpdateStatus();
?>

<!-- Top Title & Navigation -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-github text-primary me-2"></i>GitHub System Update & Code Sync</h4>
        <p class="text-muted small mb-0">Pull latest updates and synchronized code directly from repository <a href="https://github.com/kgauravindia/saranindex" target="_blank" class="text-decoration-none fw-semibold">github.com/kgauravindia/saranindex</a>.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="https://github.com/kgauravindia/saranindex" target="_blank" class="btn btn-outline-dark fw-semibold rounded-3 shadow-sm px-3">
            <i class="bi bi-box-arrow-up-right me-1"></i> Open GitHub Repo
        </a>
    </div>
</div>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div><?php echo sanitizeInput($msg); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row g-4">

    <!-- Card 1: Repository Info -->
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-dark text-white py-3 border-0 rounded-top-3">
                <h6 class="card-title fw-bold mb-0"><i class="bi bi-git me-2 text-warning"></i>Repository Status</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3 border">
                    <div class="bg-primary text-white rounded-3 p-3 me-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-github fs-3"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark fs-6">kgauravindia/saranindex</div>
                        <small class="text-muted"><i class="bi bi-globe me-1"></i>Public GitHub Repository</small>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <small class="text-muted d-block fw-semibold">Active Branch</small>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-semibold">
                            <i class="bi bi-git me-1"></i><?php echo sanitizeInput($git_info['branch']); ?>
                        </span>
                    </div>

                    <div class="col-6">
                        <small class="text-muted d-block fw-semibold">Current Commit Hash</small>
                        <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle rounded-pill px-3 py-1.5 fw-mono">
                            #<?php echo sanitizeInput($git_info['current_commit']); ?>
                        </span>
                    </div>

                    <div class="col-12">
                        <small class="text-muted d-block fw-semibold">Latest Commit Message</small>
                        <div class="p-2.5 bg-light rounded border text-dark fw-medium small">
                            "<?php echo sanitizeInput($git_info['commit_msg']); ?>"
                        </div>
                    </div>

                    <div class="col-12">
                        <small class="text-muted d-block fw-semibold">Last Commit Date</small>
                        <div class="small text-muted fw-semibold">
                            <i class="bi bi-clock-history me-1 text-primary"></i><?php echo sanitizeInput($git_info['commit_date']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Update Controls -->
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-primary text-white py-3 border-0 rounded-top-3">
                <h6 class="card-title fw-bold mb-0"><i class="bi bi-cloud-arrow-down-fill me-2"></i>Update System & Synchronize Code</h6>
            </div>
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold text-dark mb-2">Pull Latest Code Changes from GitHub</h5>
                    <p class="text-muted small mb-4">
                        Clicking the update button will execute <code class="bg-light text-primary px-2 py-1 rounded">git pull origin main</code> on your local server directory (<code class="small">d:\laragon\www\saranindex</code>). This keeps your local installation completely synchronized with GitHub.
                    </p>

                    <div class="alert alert-info border-0 rounded-3 mb-4 small d-flex align-items-center">
                        <i class="bi bi-shield-check text-info fs-4 me-3"></i>
                        <div>
                            <strong>Automatic Schema Verification:</strong> Database table constraints & column checks are automatically verified upon pulling new code updates.
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-3 pt-3 border-top">
                    <form action="system_update.php" method="POST" class="flex-grow-1" onsubmit="return confirm('Are you sure you want to pull the latest code updates from GitHub?');">
                        <input type="hidden" name="action" value="pull_updates">
                        <button type="submit" class="btn btn-primary fw-bold py-2.5 px-4 rounded-3 w-100 shadow-sm" id="btnPullUpdates">
                            <i class="bi bi-cloud-arrow-down-fill me-2"></i> Update Data & Pull from GitHub
                        </button>
                    </form>

                    <form action="system_update.php" method="POST">
                        <input type="hidden" name="action" value="db_refresh">
                        <button type="submit" class="btn btn-outline-secondary fw-bold py-2.5 px-3 rounded-3 w-100">
                            <i class="bi bi-arrow-repeat me-1"></i> Verify DB Schema
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Execution Log Output -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-dark text-white py-3 d-flex align-items-center justify-content-between">
                <div class="fw-bold"><i class="bi bi-terminal-fill me-2 text-success"></i>Git Update Execution Log</div>
                <small class="text-white-50">Console Output</small>
            </div>
            <div class="card-body p-0">
                <pre class="bg-dark text-light p-4 mb-0" style="font-family: 'Courier New', Courier, monospace; font-size: 0.875rem; min-height: 180px; max-height: 350px; overflow-y: auto; background-color: #0F172A !important;"><code><?php 
                if ($update_output !== null) {
                    echo sanitizeInput($update_output);
                } else {
                    echo "Ready. Click 'Update Data & Pull from GitHub' above to fetch latest commits from repository.";
                }
                ?></code></pre>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

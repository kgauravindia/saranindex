<?php
?>
    </main>

    <!-- Admin Footer -->
    <footer class="bg-white border-top py-3 px-4 text-center text-md-start">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between text-muted small">
            <div>
                &copy; <?php echo date('Y'); ?> <strong><?php echo APP_NAME; ?> Admin Panel</strong>. All rights reserved. Developed by <strong><?php echo PARENT_COMPANY; ?></strong>.
            </div>
            <div class="mt-2 mt-md-0">
                <span class="badge bg-light text-dark border me-2">v<?php echo APP_VERSION; ?></span>
                <span>System Status: <span class="text-success font-weight-bold"><i class="bi bi-circle-fill" style="font-size: 0.6rem;"></i> Operational</span></span>
            </div>
        </div>
    </footer>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sidebar Minimise Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtns = [
            document.getElementById('headerSidebarToggle'),
            document.getElementById('sidebarCollapseBtn')
        ];

        toggleBtns.forEach(function(btn) {
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.documentElement.classList.toggle('sidebar-collapsed');
                    document.body.classList.toggle('sidebar-collapsed');
                    const isCollapsed = document.documentElement.classList.contains('sidebar-collapsed');
                    localStorage.setItem('adminSidebarCollapsed', isCollapsed ? 'true' : 'false');
                });
            }
        });
    });
</script>

<!-- Quick Git Push & Sync Modal (Global for all Admin Pages) -->
<div class="modal fade" id="quickGitPushModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white py-3 px-4 border-0">
                <h5 class="modal-title font-heading fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-git text-danger"></i> Git Push & Repository Sync
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                <p class="text-muted small mb-3">
                    Stage, commit, and push changes to <strong>origin/main</strong> directly from this panel.
                </p>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark mb-1">Commit Message</label>
                    <input type="text" id="globalCommitMsg" class="form-control form-control-sm border-secondary-subtle" value="Admin update - <?php echo date('d M Y, H:i'); ?>">
                </div>

                <div class="d-flex gap-2 flex-wrap mb-3">
                    <button type="button" id="globalDeployBtn" class="btn btn-success btn-sm rounded-pill px-3 fw-bold flex-grow-1" onclick="executeGlobalGit('deploy')" title="Deploy latest updates from GitHub">
                        <i class="bi bi-cloud-arrow-down-fill me-1"></i> Deploy
                    </button>
                    <button type="button" id="globalPushBtn" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold flex-grow-1" onclick="executeGlobalGit('commit_push')">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Commit & Push
                    </button>
                    <button type="button" id="globalSyncBtn" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold flex-grow-1" onclick="executeGlobalGit('sync')">
                        <i class="bi bi-arrow-left-right me-1"></i> Smart Sync
                    </button>
                    <button type="button" id="globalPullBtn" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 fw-semibold" onclick="executeGlobalGit('pull')">
                        <i class="bi bi-download me-1"></i> Pull
                    </button>
                </div>

                <!-- Live Command Progress & Terminal Box -->
                <div id="globalGitResult" class="p-3 bg-dark text-white font-monospace small rounded-3 d-none" style="max-height: 160px; overflow-y: auto; font-size: 0.78rem;"></div>
            </div>
            <div class="modal-footer bg-light border-0 py-2.5 px-4 d-flex justify-content-between align-items-center">
                <a href="git.php" class="text-decoration-none small fw-semibold text-primary">
                    <i class="bi bi-terminal-split me-1"></i> Open Full Git Manager &rarr;
                </a>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function executeGlobalGit(action) {
    const resBox = document.getElementById('globalGitResult');
    const msg = document.getElementById('globalCommitMsg').value;
    const btns = [document.getElementById('globalDeployBtn'), document.getElementById('globalPushBtn'), document.getElementById('globalSyncBtn'), document.getElementById('globalPullBtn')];
    
    btns.forEach(b => { if(b) b.disabled = true; });
    resBox.classList.remove('d-none');
    resBox.innerHTML = '<span class="spinner-border spinner-border-sm text-warning me-2"></span> Executing ' + action + ' on origin...';

    const formData = new FormData();
    formData.append('action', action);
    formData.append('commit_message', msg);
    formData.append('ajax', '1');

    fetch('git.php', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        btns.forEach(b => { if(b) b.disabled = false; });
        resBox.innerHTML = '<div class="' + (data.success ? 'text-success' : 'text-danger') + ' fw-bold mb-1">' + (data.success ? '✓ ' : '✗ ') + data.message + '</div><pre class="mb-0 text-white-50" style="white-space:pre-wrap;font-size:0.75rem;">' + (data.output || '') + '</pre>';
        if (data.success) {
            setTimeout(() => { window.location.reload(); }, 2000);
        }
    })
    .catch(err => {
        btns.forEach(b => { if(b) b.disabled = false; });
        resBox.innerHTML = '<div class="text-danger fw-bold">Error: ' + err + '</div>';
    });
}
</script>

</body>
</html>

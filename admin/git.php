<?php
/**
 * Saran Index - Admin Git Manager & Deployment Tool
 * Enables authorized administrators to monitor git status, pull changes, commit, and push directly to GitHub/Remote.
 */
$page_title = "Git Manager & Deployment";
require_once __DIR__ . '/includes/header.php';

// Safe Git command executor
function runGitCmd($cmd, $workingDir) {
    $descriptor = [
        0 => ["pipe", "r"], // stdin
        1 => ["pipe", "w"], // stdout
        2 => ["pipe", "w"]  // stderr
    ];

    $startTime = microtime(true);
    $proc = proc_open($cmd, $descriptor, $pipes, $workingDir);
    $stdout = '';
    $stderr = '';
    $exitCode = -1;

    if (is_resource($proc)) {
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $exitCode = proc_close($proc);
    }
    $duration = round((microtime(true) - $startTime) * 1000, 2);

    return [
        'cmd' => $cmd,
        'stdout' => trim($stdout),
        'stderr' => trim($stderr),
        'code' => $exitCode,
        'duration_ms' => $duration
    ];
}

$repoPath = dirname(__DIR__);

// Detect git binary
$gitBin = 'git';

// Process Actions
$action = $_POST['action'] ?? ($_GET['action'] ?? '');
$cmdOutput = null;
$flashMsg = '';
$flashType = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'push') {
        // Determine current branch
        $branchRes = runGitCmd("{$gitBin} rev-parse --abbrev-ref HEAD", $repoPath);
        $branch = !empty($branchRes['stdout']) ? trim($branchRes['stdout']) : 'main';

        $cmdOutput = runGitCmd("{$gitBin} push origin {$branch} 2>&1", $repoPath);
        if ($cmdOutput['code'] === 0) {
            $flashMsg = "Successfully pushed branch '{$branch}' to origin!";
            $flashType = 'success';
        } else {
            $flashMsg = "Git push encountered an error or was rejected. Review the terminal console below.";
            $flashType = 'danger';
        }
    } elseif ($action === 'pull') {
        $branchRes = runGitCmd("{$gitBin} rev-parse --abbrev-ref HEAD", $repoPath);
        $branch = !empty($branchRes['stdout']) ? trim($branchRes['stdout']) : 'main';

        $cmdOutput = runGitCmd("{$gitBin} pull origin {$branch} 2>&1", $repoPath);
        if ($cmdOutput['code'] === 0) {
            $flashMsg = "Successfully pulled latest updates from origin/{$branch}!";
            $flashType = 'success';
        } else {
            $flashMsg = "Git pull failed. Check console output below for conflicts or errors.";
            $flashType = 'danger';
        }
    } elseif ($action === 'commit_push') {
        $commitMsg = trim($_POST['commit_message'] ?? '');
        if (empty($commitMsg)) {
            $commitMsg = 'Admin panel update - ' . date('Y-m-d H:i:s');
        }

        $branchRes = runGitCmd("{$gitBin} rev-parse --abbrev-ref HEAD", $repoPath);
        $branch = !empty($branchRes['stdout']) ? trim($branchRes['stdout']) : 'main';

        // Stage all files
        $addRes = runGitCmd("{$gitBin} add -A 2>&1", $repoPath);
        
        // Escape commit message
        $safeMsg = escapeshellarg($commitMsg);
        $commitRes = runGitCmd("{$gitBin} commit -m {$safeMsg} 2>&1", $repoPath);
        
        // Push
        $pushRes = runGitCmd("{$gitBin} push origin {$branch} 2>&1", $repoPath);

        $combinedOutput = "=== GIT ADD ===\n" . ($addRes['stdout'] ?: 'No output') . "\n\n" .
                         "=== GIT COMMIT ===\n" . ($commitRes['stdout'] ?: $commitRes['stderr'] ?: 'Nothing to commit') . "\n\n" .
                         "=== GIT PUSH ===\n" . ($pushRes['stdout'] ?: $pushRes['stderr'] ?: 'Push finished');

        $cmdOutput = [
            'cmd' => "git add -A && git commit -m \"...\" && git push origin {$branch}",
            'stdout' => $combinedOutput,
            'stderr' => $pushRes['stderr'],
            'code' => $pushRes['code'],
            'duration_ms' => $addRes['duration_ms'] + $commitRes['duration_ms'] + $pushRes['duration_ms']
        ];

        if ($pushRes['code'] === 0) {
            $flashMsg = "Changes successfully committed and pushed to origin/{$branch}!";
            $flashType = 'success';
        } else {
            $flashMsg = "Commit succeeded or partial, but push failed. Review terminal log below.";
            $flashType = 'warning';
        }
    } elseif ($action === 'fetch') {
        $cmdOutput = runGitCmd("{$gitBin} fetch origin 2>&1", $repoPath);
        if ($cmdOutput['code'] === 0) {
            $flashMsg = "Origin fetch completed. Remote references are up to date.";
            $flashType = 'success';
        } else {
            $flashMsg = "Git fetch failed. Review console output.";
            $flashType = 'danger';
        }
    }
}

// Current Git Information
$branchInfo = runGitCmd("{$gitBin} rev-parse --abbrev-ref HEAD", $repoPath);
$currentBranch = !empty($branchInfo['stdout']) ? trim($branchInfo['stdout']) : 'unknown';

$remoteInfo = runGitCmd("{$gitBin} config --get remote.origin.url", $repoPath);
$remoteUrl = !empty($remoteInfo['stdout']) ? trim($remoteInfo['stdout']) : 'Not configured';

$statusPorcelain = runGitCmd("{$gitBin} status --porcelain", $repoPath);
$changedFiles = array_filter(explode("\n", trim($statusPorcelain['stdout'] ?? '')));
$isWorkingTreeClean = empty($changedFiles);

$unpushedInfo = runGitCmd("{$gitBin} log origin/{$currentBranch}..HEAD --oneline", $repoPath);
$unpushedCommits = array_filter(explode("\n", trim($unpushedInfo['stdout'] ?? '')));

$behindInfo = runGitCmd("{$gitBin} log HEAD..origin/{$currentBranch} --oneline", $repoPath);
$behindCommits = array_filter(explode("\n", trim($behindInfo['stdout'] ?? '')));

// Recent 10 commits
$recentCommitsRaw = runGitCmd("{$gitBin} log -n 10 --pretty=format:\"%h|%an|%ar|%s\"", $repoPath);
$recentCommits = [];
if (!empty($recentCommitsRaw['stdout'])) {
    foreach (explode("\n", trim($recentCommitsRaw['stdout'])) as $line) {
        $parts = explode("|", $line, 4);
        if (count($parts) === 4) {
            $recentCommits[] = [
                'hash' => $parts[0],
                'author' => $parts[1],
                'time' => $parts[2],
                'msg' => $parts[3]
            ];
        }
    }
}

// Extract GitHub repo path for direct linking
$githubRepoLink = '';
if (preg_match('#github\.com[:/]([^/]+/[^/.]+)(\.git)?#i', $remoteUrl, $m)) {
    $githubRepoLink = 'https://github.com/' . $m[1];
}
?>

<div class="container-fluid px-4 py-4">

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Git Push & Deployment</li>
                </ol>
            </nav>
            <h2 class="h3 fw-bold font-heading mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-git text-danger"></i> Git Manager & Deployment
            </h2>
            <p class="text-muted small mb-0">Push, pull, and synchronize Saran Index code with your GitHub repository directly from the Admin Control Panel.</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <form method="POST" class="d-inline">
                <input type="hidden" name="action" value="fetch">
                <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Origin
                </button>
            </form>
            <form method="POST" class="d-inline">
                <input type="hidden" name="action" value="pull">
                <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                    <i class="bi bi-cloud-arrow-down-fill me-1"></i> Git Pull
                </button>
            </form>
            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to push current branch commits to origin/<?php echo htmlspecialchars($currentBranch); ?>?');">
                <input type="hidden" name="action" value="push">
                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3.5 fw-bold shadow-sm">
                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> Git Push
                </button>
            </form>
        </div>
    </div>

    <?php if (!empty($flashMsg)): ?>
        <div class="alert alert-<?php echo $flashType; ?> alert-dismissible fade show rounded-3 p-3 shadow-sm mb-4 small border-0" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi <?php echo $flashType === 'success' ? 'bi-check-circle-fill' : ($flashType === 'danger' ? 'bi-x-circle-fill' : 'bi-info-circle-fill'); ?> fs-5 me-2"></i>
                <div class="flex-grow-1 fw-medium"><?php echo htmlspecialchars($flashMsg); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Status Metric Cards -->
    <div class="row g-3 mb-4">
        
        <!-- Active Branch -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Current Branch</span>
                    <span class="badge bg-primary-subtle text-primary rounded-pill"><i class="bi bi-diagram-3-fill me-1"></i>Git</span>
                </div>
                <div class="fs-4 fw-bold font-monospace text-dark mb-1">
                    <i class="bi bi-bezier2 text-primary me-1"></i><?php echo htmlspecialchars($currentBranch); ?>
                </div>
                <div class="small text-muted">
                    Tracking: <code class="text-primary">origin/<?php echo htmlspecialchars($currentBranch); ?></code>
                </div>
            </div>
        </div>

        <!-- Working Tree Status -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Working Tree</span>
                    <span class="badge <?php echo $isWorkingTreeClean ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark'; ?> rounded-pill">
                        <?php echo $isWorkingTreeClean ? 'Clean' : count($changedFiles) . ' modified'; ?>
                    </span>
                </div>
                <div class="fs-4 fw-bold text-dark mb-1">
                    <?php if ($isWorkingTreeClean): ?>
                        <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>All Committed</span>
                    <?php else: ?>
                        <span class="text-warning text-dark"><i class="bi bi-pencil-square me-1"></i>Uncommitted</span>
                    <?php endif; ?>
                </div>
                <div class="small text-muted">
                    <?php echo $isWorkingTreeClean ? 'No uncommitted local changes.' : count($changedFiles) . ' file(s) awaiting commit/push.'; ?>
                </div>
            </div>
        </div>

        <!-- Unpushed Commits -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Push Status</span>
                    <span class="badge <?php echo empty($unpushedCommits) ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'; ?> rounded-pill">
                        <?php echo count($unpushedCommits); ?> Ahead
                    </span>
                </div>
                <div class="fs-4 fw-bold text-dark mb-1">
                    <?php if (empty($unpushedCommits)): ?>
                        <span class="text-success"><i class="bi bi-check-all me-1"></i>In Sync</span>
                    <?php else: ?>
                        <span class="text-danger"><i class="bi bi-arrow-up-circle-fill me-1"></i><?php echo count($unpushedCommits); ?> to Push</span>
                    <?php endif; ?>
                </div>
                <div class="small text-muted">
                    <?php echo empty($unpushedCommits) ? 'Local branch is up to date with remote.' : count($unpushedCommits) . ' commit(s) ready to push to origin.'; ?>
                </div>
            </div>
        </div>

        <!-- Pull Status -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Pull Status</span>
                    <span class="badge <?php echo empty($behindCommits) ? 'bg-success-subtle text-success' : 'bg-info-subtle text-info'; ?> rounded-pill">
                        <?php echo count($behindCommits); ?> Behind
                    </span>
                </div>
                <div class="fs-4 fw-bold text-dark mb-1">
                    <?php if (empty($behindCommits)): ?>
                        <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Up to date</span>
                    <?php else: ?>
                        <span class="text-info"><i class="bi bi-arrow-down-circle-fill me-1"></i><?php echo count($behindCommits); ?> Behind</span>
                    <?php endif; ?>
                </div>
                <div class="small text-muted">
                    <?php echo empty($behindCommits) ? 'No pending updates from remote.' : count($behindCommits) . ' remote commit(s) available to pull.'; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- Live Execution Console (Visible when action performed) -->
    <?php if ($cmdOutput !== null): ?>
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between py-2.5 px-3.5">
                <div class="d-flex align-items-center gap-2">
                    <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                    <span class="fw-bold font-monospace small"><i class="bi bi-terminal-fill me-1.5 text-warning"></i>Command Output</span>
                </div>
                <div class="d-flex align-items-center gap-2 font-monospace small">
                    <span class="badge <?php echo $cmdOutput['code'] === 0 ? 'bg-success' : 'bg-danger'; ?>">
                        Exit: <?php echo $cmdOutput['code']; ?>
                    </span>
                    <span class="text-white-50 small"><?php echo $cmdOutput['duration_ms']; ?>ms</span>
                </div>
            </div>
            <div class="card-body p-3 bg-black text-light font-monospace small" style="max-height: 280px; overflow-y: auto;">
                <div class="text-warning mb-2">$ <?php echo htmlspecialchars($cmdOutput['cmd']); ?></div>
                <pre class="mb-0 text-white" style="white-space: pre-wrap; font-family: inherit; font-size: 0.85rem;"><?php echo htmlspecialchars($cmdOutput['stdout'] ?: ($cmdOutput['stderr'] ?: '(Command completed with no standard output)')); ?></pre>
                <?php if (!empty($cmdOutput['stderr']) && $cmdOutput['stdout']): ?>
                    <div class="text-danger mt-2"><strong>Stderr:</strong><br><?php echo htmlspecialchars($cmdOutput['stderr']); ?></div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <!-- Left Column: Commit & Push Form + Remote Config -->
        <div class="col-lg-5">
            
            <!-- Quick Commit & Push Action Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold font-heading mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-cloud-arrow-up-fill text-primary"></i> Commit & Git Push
                    </h5>
                    <small class="text-muted">Stage all changes, commit with message, and push to origin in 1 click.</small>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <input type="hidden" name="action" value="commit_push">

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small mb-1">
                                Commit Message <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-chat-text"></i></span>
                                <input type="text" name="commit_message" class="form-control border-secondary-subtle" placeholder="e.g., Fix add-listing and update admin panel" value="Update from Saran Index Admin Panel (<?php echo date('d M Y'); ?>)" required>
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">Clear commit notes help you track changes in GitHub history.</small>
                        </div>

                        <!-- Uncommitted files list preview -->
                        <?php if (!$isWorkingTreeClean): ?>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small mb-1">
                                    Files to be Committed (<?php echo count($changedFiles); ?>)
                                </label>
                                <div class="p-2.5 rounded-3 bg-light border border-secondary-subtle font-monospace small" style="max-height: 140px; overflow-y: auto; font-size: 0.78rem;">
                                    <?php foreach ($changedFiles as $cf): ?>
                                        <div class="d-flex align-items-center gap-2 py-0.5">
                                            <span class="badge bg-secondary-subtle text-dark px-1.5 py-0.5"><?php echo htmlspecialchars(substr($cf, 0, 2)); ?></span>
                                            <span class="text-truncate"><?php echo htmlspecialchars(substr($cf, 3)); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success py-2 px-3 small rounded-3 mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                <span>No uncommitted file changes. Any unpushed local commits will be pushed.</span>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-rocket-takeoff-fill"></i>
                            <span>Commit & Push to GitHub</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Remote Repository Details Card -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h6 class="fw-bold font-heading mb-3 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-github text-dark"></i> GitHub Repository Details
                </h6>
                <div class="mb-2.5">
                    <div class="text-muted small">Origin Remote URL:</div>
                    <div class="font-monospace fw-semibold text-break small text-primary">
                        <?php echo htmlspecialchars($remoteUrl); ?>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Local Project Path:</div>
                    <div class="font-monospace fw-semibold text-break small text-secondary">
                        <?php echo htmlspecialchars($repoPath); ?>
                    </div>
                </div>
                <?php if (!empty($githubRepoLink)): ?>
                    <a href="<?php echo htmlspecialchars($githubRepoLink); ?>" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill w-100 fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-up-right"></i> Open Repository on GitHub
                    </a>
                <?php endif; ?>
            </div>

        </div>

        <!-- Right Column: Recent Commits & Log -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold font-heading mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-secondary"></i> Recent Git Commits
                        </h5>
                        <small class="text-muted">Last 10 commits on branch <code><?php echo htmlspecialchars($currentBranch); ?></code></small>
                    </div>
                    <?php if (!empty($githubRepoLink)): ?>
                        <a href="<?php echo htmlspecialchars($githubRepoLink); ?>/commits/<?php echo htmlspecialchars($currentBranch); ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold small">
                            Full History <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 font-sans" style="font-size: 0.88rem;">
                        <thead class="table-light text-uppercase small text-muted" style="font-size: 0.72rem; letter-spacing: 0.04em;">
                            <tr>
                                <th class="ps-4">Hash</th>
                                <th>Commit Message</th>
                                <th>Author</th>
                                <th class="pe-4 text-end">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentCommits)): ?>
                                <?php foreach ($recentCommits as $c): ?>
                                    <tr>
                                        <td class="ps-4 font-monospace">
                                            <?php if (!empty($githubRepoLink)): ?>
                                                <a href="<?php echo htmlspecialchars($githubRepoLink); ?>/commit/<?php echo htmlspecialchars($c['hash']); ?>" target="_blank" class="badge bg-light text-primary border font-monospace text-decoration-none">
                                                    <code><?php echo htmlspecialchars($c['hash']); ?></code>
                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark border font-monospace"><?php echo htmlspecialchars($c['hash']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-semibold text-dark">
                                            <?php echo htmlspecialchars($c['msg']); ?>
                                        </td>
                                        <td class="text-muted small">
                                            <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($c['author']); ?>
                                        </td>
                                        <td class="pe-4 text-end text-muted small">
                                            <?php echo htmlspecialchars($c['time']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">
                                        No recent commits found or git log unavailable.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Unpushed commits section if any -->
                <?php if (!empty($unpushedCommits)): ?>
                    <div class="p-3 bg-danger-subtle border-top border-danger-subtle">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold text-danger small">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Unpushed Local Commits (<?php echo count($unpushedCommits); ?>)
                            </span>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="push">
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> Push Now
                                </button>
                            </form>
                        </div>
                        <ul class="list-unstyled mb-0 font-monospace small">
                            <?php foreach ($unpushedCommits as $up): ?>
                                <li class="text-danger-emphasis py-0.5"><i class="bi bi-arrow-right-short"></i> <?php echo htmlspecialchars($up); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

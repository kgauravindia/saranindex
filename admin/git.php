<?php
/**
 * Saran Index - Admin Git Manager & Deployment Tool
 * Enables authorized administrators to monitor git status, inspect diffs, pull, commit, and push directly to GitHub/Remote.
 */
require_once __DIR__ . '/includes/auth.php';
checkAdminAuth();
require_once __DIR__ . '/../includes/functions.php';

$repoPath = realpath(dirname(__DIR__));

// Safe Git command executor with environment preservation
if (!function_exists('runGitCmd')) {
    function runGitCmd($cmd, $workingDir) {
        // Preserve essential system environment for Git, SSH, and Credential Manager
        $env = array_merge($_ENV, [
            'PATH' => getenv('PATH') ?: 'C:\\Program Files\\Git\\cmd;C:\\Windows\\system32;C:\\Windows',
            'SYSTEMROOT' => getenv('SYSTEMROOT') ?: 'C:\\Windows',
            'USERPROFILE' => getenv('USERPROFILE') ?: 'C:\\Users\\' . get_current_user(),
            'HOME' => getenv('USERPROFILE') ?: (getenv('HOME') ?: 'C:\\Users\\' . get_current_user()),
            'GIT_TERMINAL_PROMPT' => '0' // Prevent hanging on interactive credential prompts
        ]);

        $descriptor = [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"]  // stderr
        ];

        $startTime = microtime(true);
        $proc = proc_open($cmd, $descriptor, $pipes, $workingDir, $env);
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
}

// Auto-detect git binary
if (!function_exists('getGitBinary')) {
    function getGitBinary() {
        static $gitBin = null;
        if ($gitBin !== null) return $gitBin;

        // Check if git in PATH is responsive
        $check = runGitCmd("git --version", sys_get_temp_dir());
        if ($check['code'] === 0) {
            $gitBin = 'git';
            return $gitBin;
        }

        // Windows fallback paths
        $commonPaths = [
            'C:\\Program Files\\Git\\cmd\\git.exe',
            'C:\\Program Files\\Git\\bin\\git.exe',
            'C:\\Program Files (x86)\\Git\\cmd\\git.exe',
            'D:\\laragon\\bin\\git\\bin\\git.exe',
            'D:\\laragon\\bin\\git\\cmd\\git.exe'
        ];
        foreach ($commonPaths as $p) {
            if (file_exists($p)) {
                $gitBin = '"' . $p . '"';
                return $gitBin;
            }
        }
        $gitBin = 'git';
        return $gitBin;
    }
}

$gitBin = getGitBinary();

// Helper to parse git status porcelain line
if (!function_exists('parseGitStatusLine')) {
    function parseGitStatusLine($line) {
        if (strlen($line) < 3) return null;
        $x = substr($line, 0, 1);
        $y = substr($line, 1, 1);
        $file = trim(substr($line, 3));

        $status = 'Modified';
        $badgeClass = 'bg-warning-subtle text-warning-emphasis border border-warning';
        $type = 'modified';

        if ($x === '?' && $y === '?') {
            $status = 'Untracked';
            $badgeClass = 'bg-secondary-subtle text-secondary border border-secondary';
            $type = 'untracked';
        } elseif ($x === 'A' || $y === 'A') {
            $status = 'Added';
            $badgeClass = 'bg-success-subtle text-success border border-success';
            $type = 'added';
        } elseif ($x === 'D' || $y === 'D') {
            $status = 'Deleted';
            $badgeClass = 'bg-danger-subtle text-danger border border-danger';
            $type = 'deleted';
        } elseif ($x === 'R' || $y === 'R') {
            $status = 'Renamed';
            $badgeClass = 'bg-info-subtle text-info border border-info';
            $type = 'renamed';
        }

        return [
            'raw' => $line,
            'index' => $x,
            'worktree' => $y,
            'file' => $file,
            'status' => $status,
            'badge' => $badgeClass,
            'type' => $type
        ];
    }
}

// Convert unified diff text to syntax-colored HTML
if (!function_exists('formatGitDiffToHtml')) {
    function formatGitDiffToHtml($diffText) {
        if (empty($diffText)) {
            return '<div class="p-3 text-muted fst-italic">No differences found, or file is untracked/binary.</div>';
        }
        $lines = explode("\n", $diffText);
        $html = '<div class="diff-viewer font-monospace small" style="background:#0d1117; color:#c9d1d9; border-radius:8px; overflow-x:auto; line-height:1.45; font-size:12.5px; padding:10px;">';
        foreach ($lines as $line) {
            $escaped = htmlspecialchars($line);
            if (str_starts_with($line, '+++') || str_starts_with($line, '---')) {
                $html .= '<div style="background:#161b22; font-weight:bold; color:#8b949e; padding:2px 8px;">' . $escaped . '</div>';
            } elseif (str_starts_with($line, '+')) {
                $html .= '<div style="background:rgba(46,160,67,0.15); color:#7ee787; padding:2px 8px;">' . $escaped . '</div>';
            } elseif (str_starts_with($line, '-')) {
                $html .= '<div style="background:rgba(248,81,73,0.15); color:#ffa198; padding:2px 8px;">' . $escaped . '</div>';
            } elseif (str_starts_with($line, '@@')) {
                $html .= '<div style="background:rgba(56,139,253,0.15); color:#79c0ff; font-weight:600; padding:2px 8px;">' . $escaped . '</div>';
            } else {
                $html .= '<div style="color:#c9d1d9; padding:2px 8px;">' . $escaped . '</div>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}

// Process Incoming Actions (AJAX or Standard POST)
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || isset($_REQUEST['ajax']);
$action = $_POST['action'] ?? ($_GET['action'] ?? '');
$cmdOutput = null;
$flashMsg = $_SESSION['git_flash_msg'] ?? '';
$flashType = $_SESSION['git_flash_type'] ?? 'info';
unset($_SESSION['git_flash_msg'], $_SESSION['git_flash_type']);

// Handle actions
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if (!empty($action) && ($requestMethod === 'POST' || $action === 'diff' || $action === 'commit_detail' || $action === 'run_cmd')) {
    $response = ['success' => false, 'message' => '', 'cmd' => '', 'output' => '', 'code' => -1, 'duration_ms' => 0];

    // Get current branch
    $branchRes = runGitCmd("{$gitBin} rev-parse --abbrev-ref HEAD", $repoPath);
    $branch = !empty($branchRes['stdout']) ? trim($branchRes['stdout']) : 'main';

    if ($action === 'push') {
        $cmdOutput = runGitCmd("{$gitBin} push origin {$branch} 2>&1", $repoPath);
        $success = ($cmdOutput['code'] === 0);
        $response = [
            'success' => $success,
            'message' => $success ? "Successfully pushed branch '{$branch}' to origin!" : "Git push failed or was rejected. Review terminal log.",
            'cmd' => $cmdOutput['cmd'],
            'output' => $cmdOutput['stdout'] ?: $cmdOutput['stderr'],
            'code' => $cmdOutput['code'],
            'duration_ms' => $cmdOutput['duration_ms']
        ];
    } elseif ($action === 'pull') {
        $autoStash = !empty($_POST['autostash']) ? '--autostash' : '';
        $cmdOutput = runGitCmd("{$gitBin} pull {$autoStash} origin {$branch} 2>&1", $repoPath);
        $success = ($cmdOutput['code'] === 0);
        $response = [
            'success' => $success,
            'message' => $success ? "Successfully pulled latest updates from origin/{$branch}!" : "Git pull encountered issues. Review terminal log.",
            'cmd' => $cmdOutput['cmd'],
            'output' => $cmdOutput['stdout'] ?: $cmdOutput['stderr'],
            'code' => $cmdOutput['code'],
            'duration_ms' => $cmdOutput['duration_ms']
        ];
    } elseif ($action === 'fetch') {
        $cmdOutput = runGitCmd("{$gitBin} fetch origin --prune 2>&1", $repoPath);
        $success = ($cmdOutput['code'] === 0);
        $response = [
            'success' => $success,
            'message' => $success ? "Origin fetch completed. Remote references are up to date." : "Git fetch failed. Review console output.",
            'cmd' => $cmdOutput['cmd'],
            'output' => $cmdOutput['stdout'] ?: ($cmdOutput['stderr'] ?: 'Fetch completed with no changes.'),
            'code' => $cmdOutput['code'],
            'duration_ms' => $cmdOutput['duration_ms']
        ];
    } elseif ($action === 'sync') {
        // Smart Sync: Fetch -> Pull with autostash -> Push
        $fRes = runGitCmd("{$gitBin} fetch origin --prune 2>&1", $repoPath);
        $pRes = runGitCmd("{$gitBin} pull --autostash origin {$branch} 2>&1", $repoPath);
        $uRes = runGitCmd("{$gitBin} push origin {$branch} 2>&1", $repoPath);

        $totalCode = $fRes['code'] | $pRes['code'] | $uRes['code'];
        $combinedLog = "=== 1. GIT FETCH ===\n" . ($fRes['stdout'] ?: ($fRes['stderr'] ?: 'Done')) . "\n\n" .
                       "=== 2. GIT PULL (--autostash) ===\n" . ($pRes['stdout'] ?: ($pRes['stderr'] ?: 'Done')) . "\n\n" .
                       "=== 3. GIT PUSH ===\n" . ($uRes['stdout'] ?: ($uRes['stderr'] ?: 'Done'));

        $cmdOutput = [
            'cmd' => "git fetch && git pull --autostash origin {$branch} && git push origin {$branch}",
            'stdout' => $combinedLog,
            'stderr' => $uRes['stderr'],
            'code' => $uRes['code'],
            'duration_ms' => $fRes['duration_ms'] + $pRes['duration_ms'] + $uRes['duration_ms']
        ];
        $success = ($uRes['code'] === 0 && $pRes['code'] === 0);
        $response = [
            'success' => $success,
            'message' => $success ? "Smart Sync complete: Successfully synchronized branch '{$branch}' with origin!" : "Sync completed with warnings or partial errors. Check terminal output.",
            'cmd' => $cmdOutput['cmd'],
            'output' => $combinedLog,
            'code' => $cmdOutput['code'],
            'duration_ms' => $cmdOutput['duration_ms']
        ];
    } elseif ($action === 'commit_push') {
        $commitMsg = trim($_POST['commit_message'] ?? '');
        if (empty($commitMsg)) {
            $commitMsg = 'Admin panel update - ' . date('Y-m-d H:i');
        }

        // Selected files or all
        $selectedFiles = $_POST['files'] ?? [];
        if (!empty($selectedFiles) && is_array($selectedFiles)) {
            // Stage specifically selected files
            $safeFileList = array_map(function($f) {
                return escapeshellarg(trim($f));
            }, $selectedFiles);
            $stageCmd = "{$gitBin} add -- " . implode(' ', $safeFileList) . " 2>&1";
        } else {
            // Stage all files
            $stageCmd = "{$gitBin} add -A 2>&1";
        }

        $addRes = runGitCmd($stageCmd, $repoPath);
        $safeMsg = escapeshellarg($commitMsg);
        $commitRes = runGitCmd("{$gitBin} commit -m {$safeMsg} 2>&1", $repoPath);
        $pushRes = runGitCmd("{$gitBin} push origin {$branch} 2>&1", $repoPath);

        $combinedLog = "=== 1. GIT STAGE ===\n" . ($addRes['stdout'] ?: ($addRes['stderr'] ?: 'Files staged.')) . "\n\n" .
                       "=== 2. GIT COMMIT ===\n" . ($commitRes['stdout'] ?: ($commitRes['stderr'] ?: 'Nothing to commit.')) . "\n\n" .
                       "=== 3. GIT PUSH ===\n" . ($pushRes['stdout'] ?: ($pushRes['stderr'] ?: 'Push finished.'));

        $cmdOutput = [
            'cmd' => "{$stageCmd} && git commit -m ... && git push origin {$branch}",
            'stdout' => $combinedLog,
            'stderr' => $pushRes['stderr'],
            'code' => $pushRes['code'],
            'duration_ms' => $addRes['duration_ms'] + $commitRes['duration_ms'] + $pushRes['duration_ms']
        ];
        $success = ($pushRes['code'] === 0);
        $response = [
            'success' => $success,
            'message' => $success ? "Changes committed and successfully pushed to origin/{$branch}!" : "Commit completed, but push encountered an issue. Review terminal log.",
            'cmd' => $cmdOutput['cmd'],
            'output' => $combinedLog,
            'code' => $pushRes['code'],
            'duration_ms' => $cmdOutput['duration_ms']
        ];
    } elseif ($action === 'stash_save') {
        $stashNote = trim($_POST['stash_note'] ?? ('Admin stash ' . date('Y-m-d H:i')));
        $safeNote = escapeshellarg($stashNote);
        $cmdOutput = runGitCmd("{$gitBin} stash push -m {$safeNote} 2>&1", $repoPath);
        $success = ($cmdOutput['code'] === 0);
        $response = [
            'success' => $success,
            'message' => $success ? "Working directory stashed successfully!" : "Git stash failed.",
            'cmd' => $cmdOutput['cmd'],
            'output' => $cmdOutput['stdout'] ?: $cmdOutput['stderr'],
            'code' => $cmdOutput['code'],
            'duration_ms' => $cmdOutput['duration_ms']
        ];
    } elseif ($action === 'stash_pop') {
        $cmdOutput = runGitCmd("{$gitBin} stash pop 2>&1", $repoPath);
        $success = ($cmdOutput['code'] === 0);
        $response = [
            'success' => $success,
            'message' => $success ? "Stash restored to working directory!" : "Stash pop encountered conflicts or no stash exists.",
            'cmd' => $cmdOutput['cmd'],
            'output' => $cmdOutput['stdout'] ?: $cmdOutput['stderr'],
            'code' => $cmdOutput['code'],
            'duration_ms' => $cmdOutput['duration_ms']
        ];
    } elseif ($action === 'diff') {
        $targetFile = trim($_GET['file'] ?? ($_POST['file'] ?? ''));
        if (!empty($targetFile)) {
            // Diff single file
            $safeFile = escapeshellarg($targetFile);
            $diffRes = runGitCmd("{$gitBin} diff HEAD -- {$safeFile} 2>&1", $repoPath);
            // If empty, check if untracked
            if (empty($diffRes['stdout'])) {
                $fullPath = $repoPath . DIRECTORY_SEPARATOR . $targetFile;
                if (file_exists($fullPath) && is_file($fullPath)) {
                    $content = file_get_contents($fullPath);
                    $diffRes['stdout'] = "--- /dev/null\n+++ b/{$targetFile}\n@@ -0,0 +1," . substr_count($content, "\n") . " @@\n" . preg_replace('/^/m', '+', $content);
                }
            }
        } else {
            // Diff entire working tree
            $diffRes = runGitCmd("{$gitBin} diff HEAD 2>&1", $repoPath);
        }
        $formattedHtml = formatGitDiffToHtml($diffRes['stdout']);
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => true,
                'target' => $targetFile ?: 'All Files',
                'raw_diff' => $diffRes['stdout'],
                'html' => $formattedHtml
            ]);
            exit;
        }
    } elseif ($action === 'discard') {
        $targetFile = trim($_POST['file'] ?? '');
        if (!empty($targetFile)) {
            $safeFile = escapeshellarg($targetFile);
            $fullPath = $repoPath . DIRECTORY_SEPARATOR . $targetFile;
            
            // Check status to determine if tracked or untracked
            $stCheck = runGitCmd("{$gitBin} status --porcelain -- {$safeFile}", $repoPath);
            if (str_starts_with($stCheck['stdout'], '??')) {
                // Untracked file: safely remove if inside repo
                if (file_exists($fullPath) && is_file($fullPath) && str_starts_with(realpath($fullPath), $repoPath)) {
                    unlink($fullPath);
                    $cmdOutput = ['cmd' => "rm {$targetFile}", 'stdout' => "Deleted untracked file {$targetFile}", 'stderr' => '', 'code' => 0, 'duration_ms' => 1];
                }
            } else {
                // Tracked file: restore to HEAD
                $cmdOutput = runGitCmd("{$gitBin} checkout HEAD -- {$safeFile} 2>&1", $repoPath);
            }
            $success = ($cmdOutput['code'] === 0);
            $response = [
                'success' => $success,
                'message' => $success ? "Discarded changes to '{$targetFile}'." : "Could not discard changes to file.",
                'cmd' => $cmdOutput['cmd'] ?? '',
                'output' => $cmdOutput['stdout'] ?? '',
                'code' => $cmdOutput['code'] ?? -1,
                'duration_ms' => $cmdOutput['duration_ms'] ?? 0
            ];
        }
    } elseif ($action === 'commit_detail') {
        $hash = preg_replace('/[^a-f0-9]/i', '', $_GET['hash'] ?? ($_POST['hash'] ?? ''));
        if (!empty($hash)) {
            $detailRes = runGitCmd("{$gitBin} show --stat -p {$hash} 2>&1", $repoPath);
            $formattedHtml = formatGitDiffToHtml($detailRes['stdout']);
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => true,
                    'hash' => $hash,
                    'raw' => $detailRes['stdout'],
                    'html' => $formattedHtml
                ]);
                exit;
            }
        }
    } elseif ($action === 'run_cmd') {
        $allowedCmds = [
            'status' => "{$gitBin} status",
            'diff_stat' => "{$gitBin} diff --stat",
            'log_5' => "{$gitBin} log -n 5 --stat",
            'branch' => "{$gitBin} branch -a",
            'remote' => "{$gitBin} remote -v",
            'config' => "{$gitBin} config -l --local"
        ];
        $targetCmd = $_POST['cmd_key'] ?? ($_GET['cmd_key'] ?? 'status');
        if (isset($allowedCmds[$targetCmd])) {
            $cmdOutput = runGitCmd($allowedCmds[$targetCmd], $repoPath);
            $response = [
                'success' => ($cmdOutput['code'] === 0),
                'message' => "Executed: {$allowedCmds[$targetCmd]}",
                'cmd' => $allowedCmds[$targetCmd],
                'output' => $cmdOutput['stdout'] ?: $cmdOutput['stderr'],
                'code' => $cmdOutput['code'],
                'duration_ms' => $cmdOutput['duration_ms']
            ];
        }
    }

    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        exit;
    } else {
        $_SESSION['git_flash_msg'] = $response['message'];
        $_SESSION['git_flash_type'] = $response['success'] ? 'success' : 'danger';
        // Post-Redirect-Get to avoid form resubmission
        header("Location: git.php");
        exit;
    }
}

// -------------------------------------------------------------
// Read Current Repository State for Page Display
// -------------------------------------------------------------
$branchInfo = runGitCmd("{$gitBin} rev-parse --abbrev-ref HEAD", $repoPath);
$currentBranch = !empty($branchInfo['stdout']) ? trim($branchInfo['stdout']) : 'main';

$remoteInfo = runGitCmd("{$gitBin} config --get remote.origin.url", $repoPath);
$remoteUrl = !empty($remoteInfo['stdout']) ? trim($remoteInfo['stdout']) : 'Not configured';

$statusPorcelain = runGitCmd("{$gitBin} status --porcelain", $repoPath);
$rawStatusLines = array_filter(explode("\n", trim($statusPorcelain['stdout'] ?? '')));
$parsedChanges = [];
foreach ($rawStatusLines as $rLine) {
    $p = parseGitStatusLine($rLine);
    if ($p) $parsedChanges[] = $p;
}
$isWorkingTreeClean = empty($parsedChanges);

// Ahead & Behind Tracking
$unpushedInfo = runGitCmd("{$gitBin} log origin/{$currentBranch}..HEAD --oneline", $repoPath);
$unpushedCommits = array_filter(explode("\n", trim($unpushedInfo['stdout'] ?? '')));

$behindInfo = runGitCmd("{$gitBin} log HEAD..origin/{$currentBranch} --oneline", $repoPath);
$behindCommits = array_filter(explode("\n", trim($behindInfo['stdout'] ?? '')));

// Stash count
$stashInfo = runGitCmd("{$gitBin} stash list", $repoPath);
$stashes = array_filter(explode("\n", trim($stashInfo['stdout'] ?? '')));

// Git Author & Version
$gitVersionInfo = runGitCmd("{$gitBin} --version", $repoPath);
$gitVersion = $gitVersionInfo['stdout'] ?: 'Git';

$gitUserName = runGitCmd("{$gitBin} config --get user.name", $repoPath)['stdout'] ?: 'Not set';
$gitUserEmail = runGitCmd("{$gitBin} config --get user.email", $repoPath)['stdout'] ?: 'Not set';

// Recent 15 commits
$recentCommitsRaw = runGitCmd("{$gitBin} log -n 15 --pretty=format:\"%h|%an|%ar|%s\"", $repoPath);
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

// GitHub repo path for direct linking
$githubRepoLink = '';
if (preg_match('#github\.com[:/]([^/]+/[^/.]+)(\.git)?#i', $remoteUrl, $m)) {
    $githubRepoLink = 'https://github.com/' . $m[1];
}

$page_title = "Git Manager & Deployment";
$current_page = "git.php";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container-fluid px-4 py-4">

    <!-- Page Header Section -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
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
            <p class="text-muted small mb-0">Push, pull, stage, inspect diffs, and synchronize Saran Index code with your GitHub repository in real time.</p>
        </div>

        <!-- Quick Deployment Action Controls -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-primary btn-sm rounded-pill px-3.5 fw-bold shadow-sm d-flex align-items-center gap-1.5" onclick="triggerGitAction('sync', this)" title="Fetch, Pull with Auto-stash, and Push in 1 Click">
                <i class="bi bi-arrow-left-right"></i>
                <span>Smart Sync</span>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold d-flex align-items-center gap-1" onclick="triggerGitAction('fetch', this)" title="Fetch latest remote branches and refs">
                <i class="bi bi-arrow-repeat"></i>
                <span>Fetch</span>
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold d-flex align-items-center gap-1" onclick="triggerGitAction('pull', this)" title="Pull latest changes from origin/<?php echo htmlspecialchars($currentBranch); ?>">
                <i class="bi bi-cloud-arrow-down-fill"></i>
                <span>Pull</span>
            </button>
            <button type="button" class="btn btn-danger btn-sm rounded-pill px-3.5 fw-bold shadow-sm d-flex align-items-center gap-1" onclick="triggerGitAction('push', this)" title="Push committed changes to origin/<?php echo htmlspecialchars($currentBranch); ?>">
                <i class="bi bi-cloud-arrow-up-fill"></i>
                <span>Push</span>
            </button>
            <?php if (!empty($githubRepoLink)): ?>
                <a href="<?php echo htmlspecialchars($githubRepoLink); ?>" target="_blank" class="btn btn-dark btn-sm rounded-pill px-3 fw-semibold" title="View on GitHub">
                    <i class="bi bi-github"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Live Flash Message -->
    <div id="flashContainer">
        <?php if (!empty($flashMsg)): ?>
            <div class="alert alert-<?php echo $flashType; ?> alert-dismissible fade show rounded-3 p-3 shadow-sm mb-4 small border-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi <?php echo $flashType === 'success' ? 'bi-check-circle-fill' : ($flashType === 'danger' ? 'bi-x-circle-fill' : 'bi-info-circle-fill'); ?> fs-5 me-2"></i>
                    <div class="flex-grow-1 fw-medium"><?php echo htmlspecialchars($flashMsg); ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Status Metric KPI Cards -->
    <div class="row g-3 mb-4">
        
        <!-- Active Branch -->
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Current Branch</span>
                    <span class="badge bg-primary-subtle text-primary rounded-pill"><i class="bi bi-diagram-3-fill me-1"></i>Git</span>
                </div>
                <div class="fs-4 fw-bold font-monospace text-dark mb-1 d-flex align-items-center gap-1.5">
                    <i class="bi bi-bezier2 text-primary"></i>
                    <span class="text-truncate"><?php echo htmlspecialchars($currentBranch); ?></span>
                </div>
                <div class="small text-muted text-truncate">
                    Upstream: <code class="text-primary">origin/<?php echo htmlspecialchars($currentBranch); ?></code>
                </div>
            </div>
        </div>

        <!-- Working Tree Status -->
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Working Tree</span>
                    <span class="badge <?php echo $isWorkingTreeClean ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark'; ?> rounded-pill">
                        <?php echo $isWorkingTreeClean ? 'Clean' : count($parsedChanges) . ' changed'; ?>
                    </span>
                </div>
                <div class="fs-4 fw-bold text-dark mb-1">
                    <?php if ($isWorkingTreeClean): ?>
                        <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>All Committed</span>
                    <?php else: ?>
                        <span class="text-warning text-dark"><i class="bi bi-pencil-square me-1"></i><?php echo count($parsedChanges); ?> Uncommitted</span>
                    <?php endif; ?>
                </div>
                <div class="small text-muted">
                    <?php echo $isWorkingTreeClean ? 'Working directory is completely clean.' : count($parsedChanges) . ' file(s) waiting for commit/push.'; ?>
                </div>
            </div>
        </div>

        <!-- Unpushed Commits (Ahead) -->
        <div class="col-6 col-md-3">
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
                    <?php echo empty($unpushedCommits) ? 'Local branch matches remote origin.' : count($unpushedCommits) . ' commit(s) ready to push to GitHub.'; ?>
                </div>
            </div>
        </div>

        <!-- Pull Status (Behind) -->
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Pull Status</span>
                    <span class="badge <?php echo empty($behindCommits) ? 'bg-success-subtle text-success' : 'bg-info-subtle text-info'; ?> rounded-pill">
                        <?php echo count($behindCommits); ?> Behind
                    </span>
                </div>
                <div class="fs-4 fw-bold text-dark mb-1">
                    <?php if (empty($behindCommits)): ?>
                        <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Up to Date</span>
                    <?php else: ?>
                        <span class="text-info"><i class="bi bi-arrow-down-circle-fill me-1"></i><?php echo count($behindCommits); ?> to Pull</span>
                    <?php endif; ?>
                </div>
                <div class="small text-muted">
                    <?php echo empty($behindCommits) ? 'No pending updates on remote.' : count($behindCommits) . ' commit(s) waiting on remote origin.'; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- Interactive Terminal Console -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" id="terminalCard">
        <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between py-2.5 px-3.5">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center gap-1.5 me-1">
                    <span class="rounded-circle bg-danger d-inline-block" style="width: 10px; height: 10px;"></span>
                    <span class="rounded-circle bg-warning d-inline-block" style="width: 10px; height: 10px;"></span>
                    <span class="rounded-circle bg-success d-inline-block" style="width: 10px; height: 10px;"></span>
                </div>
                <span class="fw-bold font-monospace small"><i class="bi bi-terminal-fill me-1 text-warning"></i>Git Terminal Console</span>
                <span id="termSpinner" class="spinner-border spinner-border-sm text-warning d-none" role="status"></span>
            </div>

            <!-- Terminal Tools & Diagnostics -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-outline-light btn-sm py-0 px-2 font-monospace" style="font-size: 0.72rem;" onclick="runDiagnostics('status')" title="Run git status">status</button>
                <button type="button" class="btn btn-outline-light btn-sm py-0 px-2 font-monospace" style="font-size: 0.72rem;" onclick="runDiagnostics('diff_stat')" title="Run git diff --stat">diff --stat</button>
                <button type="button" class="btn btn-outline-light btn-sm py-0 px-2 font-monospace" style="font-size: 0.72rem;" onclick="runDiagnostics('log_5')" title="Run git log -n 5 --stat">log -5</button>
                <button type="button" class="btn btn-outline-light btn-sm py-0 px-2 font-monospace" style="font-size: 0.72rem;" onclick="runDiagnostics('branch')" title="Run git branch -a">branches</button>
                <button type="button" class="btn btn-outline-light btn-sm py-0 px-2 font-monospace" style="font-size: 0.72rem;" onclick="runDiagnostics('remote')" title="Run git remote -v">remotes</button>
                <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2 text-white-50" style="font-size: 0.72rem;" onclick="copyTerminalOutput()" title="Copy output"><i class="bi bi-clipboard"></i></button>
                <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2 text-white-50" style="font-size: 0.72rem;" onclick="clearTerminal()" title="Clear console"><i class="bi bi-trash"></i></button>
            </div>
        </div>
        <div class="card-body p-3 bg-black text-light font-monospace small" id="termBody" style="max-height: 280px; min-height: 120px; overflow-y: auto;">
            <div class="text-success mb-1">$ git status</div>
            <pre id="termOutput" class="mb-0 text-white" style="white-space: pre-wrap; font-family: inherit; font-size: 0.85rem;"><?php 
                echo htmlspecialchars($statusPorcelain['stdout'] ?: "On branch {$currentBranch}\nYour branch is up to date with 'origin/{$currentBranch}'.\n\nnothing to commit, working tree clean"); 
            ?></pre>
        </div>
    </div>

    <!-- Main Workspace Layout -->
    <div class="row g-4">
        
        <!-- Left Column: Commit & Push Form + Stash + Remote Details -->
        <div class="col-lg-5">
            
            <!-- Quick Commit & Push Action Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold font-heading mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-cloud-arrow-up-fill text-primary"></i> Commit & Git Push
                        </h5>
                        <small class="text-muted">Stage changes, commit with custom notes, and push to origin.</small>
                    </div>
                    <?php if (!$isWorkingTreeClean): ?>
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill py-0.5 px-2.5 small" onclick="openDiffModal('')" title="View overall diff">
                            <i class="bi bi-file-earmark-diff me-1"></i> Full Diff
                        </button>
                    <?php endif; ?>
                </div>

                <div class="card-body p-4">
                    <form id="commitPushForm" method="POST" onsubmit="handleCommitPush(event)">
                        <input type="hidden" name="action" value="commit_push">

                        <!-- Commit Message Field with Template Suggestions -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label fw-semibold text-dark small mb-0">
                                    Commit Message <span class="text-danger">*</span>
                                </label>
                                <div class="dropdown">
                                    <button class="btn btn-link btn-sm text-decoration-none text-muted p-0 small dropdown-toggle" type="button" data-bs-toggle="dropdown" style="font-size: 0.75rem;">
                                        Templates
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end small shadow-sm">
                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="setCommitMsg('feat: Update listings and admin panel records')">feat: Update listings & records</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="setCommitMsg('fix: Bug fixes, UI adjustments, and performance improvements')">fix: Bug fixes & UI improvements</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="setCommitMsg('content: Directory data update and contact verification')">content: Directory data update</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="setCommitMsg('chore: Regenerate XML sitemaps and update configuration')">chore: Sitemap & config refresh</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-secondary-subtle text-muted"><i class="bi bi-chat-text"></i></span>
                                <input type="text" id="commitMsgInput" name="commit_message" class="form-control border-secondary-subtle" placeholder="e.g., Update listing records and admin tools" value="Update from Saran Index Admin Panel (<?php echo date('d M Y'); ?>)" required>
                            </div>
                        </div>

                        <!-- Uncommitted files list preview with stage selection & diff -->
                        <?php if (!$isWorkingTreeClean): ?>
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-1.5">
                                    <label class="form-label fw-semibold text-dark small mb-0">
                                        Files to Stage & Commit (<?php echo count($parsedChanges); ?>)
                                    </label>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 text-primary small" style="font-size: 0.75rem;" onclick="toggleAllFiles(true)">Select All</button>
                                        <span class="text-muted small">|</span>
                                        <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 text-muted small" style="font-size: 0.75rem;" onclick="toggleAllFiles(false)">Deselect</button>
                                    </div>
                                </div>

                                <div class="rounded-3 bg-light border border-secondary-subtle p-2 font-monospace small" style="max-height: 200px; overflow-y: auto;">
                                    <?php foreach ($parsedChanges as $idx => $cf): ?>
                                        <div class="d-flex align-items-center justify-content-between py-1 px-1.5 border-bottom border-light-subtle file-item-row">
                                            <div class="form-check mb-0 d-flex align-items-center gap-1.5 text-truncate flex-grow-1">
                                                <input class="form-check-input file-checkbox mt-0" type="checkbox" name="files[]" value="<?php echo htmlspecialchars($cf['file']); ?>" id="cf_<?php echo $idx; ?>" checked>
                                                <label class="form-check-label text-truncate small cursor-pointer" for="cf_<?php echo $idx; ?>" title="<?php echo htmlspecialchars($cf['file']); ?>">
                                                    <span class="badge <?php echo $cf['badge']; ?> px-1 py-0 me-1" style="font-size: 0.68rem;"><?php echo htmlspecialchars($cf['status']); ?></span>
                                                    <span><?php echo htmlspecialchars($cf['file']); ?></span>
                                                </label>
                                            </div>
                                            <div class="d-flex align-items-center gap-1 ms-2">
                                                <button type="button" class="btn btn-outline-dark btn-sm py-0 px-1.5 text-muted" style="font-size: 0.7rem;" onclick="openDiffModal('<?php echo htmlspecialchars(addslashes($cf['file'])); ?>')" title="Inspect Diff">
                                                    <i class="bi bi-file-earmark-diff"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm py-0 px-1.5 text-danger" style="font-size: 0.7rem;" onclick="discardFileChanges('<?php echo htmlspecialchars(addslashes($cf['file'])); ?>')" title="Discard changes to this file">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success py-2.5 px-3 small rounded-3 mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                <div>
                                    <div class="fw-bold">No uncommitted file changes.</div>
                                    <div class="text-muted small">Any unpushed local commits will be pushed directly to origin/<?php echo htmlspecialchars($currentBranch); ?>.</div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <button type="submit" id="commitBtn" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-rocket-takeoff-fill"></i>
                            <span id="commitBtnText">Commit & Push to GitHub</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Stash Management Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-3.5">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold font-heading mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-archive-fill text-warning"></i> Git Stash (<?php echo count($stashes); ?>)
                    </h6>
                    <div class="d-flex align-items-center gap-1.5">
                        <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-2.5 py-0.5 text-dark fw-semibold" style="font-size: 0.78rem;" onclick="triggerGitAction('stash_save', this)">
                            <i class="bi bi-box-arrow-in-down me-1"></i> Stash Now
                        </button>
                        <?php if (!empty($stashes)): ?>
                            <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-2.5 py-0.5 fw-semibold" style="font-size: 0.78rem;" onclick="triggerGitAction('stash_pop', this)">
                                <i class="bi bi-box-arrow-up me-1"></i> Pop Latest
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <p class="text-muted small mb-0">Temporarily shelve dirty local changes without committing, allowing clean pulls from GitHub.</p>
                <?php if (!empty($stashes)): ?>
                    <div class="mt-2.5 p-2 bg-light rounded-3 font-monospace small" style="max-height: 90px; overflow-y: auto; font-size: 0.75rem;">
                        <?php foreach ($stashes as $st): ?>
                            <div class="text-truncate text-secondary py-0.5"><i class="bi bi-tag-fill me-1 text-warning"></i><?php echo htmlspecialchars($st); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Repository Environment Details Card -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h6 class="fw-bold font-heading mb-3 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-gear-wide-connected text-primary"></i> Environment & Remote Details
                </h6>
                <div class="mb-2.5">
                    <div class="text-muted small">Origin Remote URL:</div>
                    <div class="font-monospace fw-semibold text-break small text-primary">
                        <?php echo htmlspecialchars($remoteUrl); ?>
                    </div>
                </div>
                <div class="mb-2.5">
                    <div class="text-muted small">Git Author Identity:</div>
                    <div class="font-monospace small text-dark">
                        <?php echo htmlspecialchars($gitUserName); ?> &lt;<?php echo htmlspecialchars($gitUserEmail); ?>&gt;
                    </div>
                </div>
                <div class="mb-2.5">
                    <div class="text-muted small">Git Executable:</div>
                    <div class="font-monospace small text-secondary">
                        <?php echo htmlspecialchars($gitVersion); ?>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Repository Path:</div>
                    <div class="font-monospace small text-secondary text-break">
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
                        <small class="text-muted">History of branch <code><?php echo htmlspecialchars($currentBranch); ?></code></small>
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
                                            <a href="javascript:void(0)" onclick="openCommitDetailModal('<?php echo htmlspecialchars($c['hash']); ?>')" class="badge bg-light text-primary border font-monospace text-decoration-none" title="View commit diff">
                                                <code><?php echo htmlspecialchars($c['hash']); ?></code>
                                            </a>
                                        </td>
                                        <td class="fw-semibold text-dark">
                                            <div class="text-break"><?php echo htmlspecialchars($c['msg']); ?></div>
                                        </td>
                                        <td class="text-muted small text-nowrap">
                                            <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($c['author']); ?>
                                        </td>
                                        <td class="pe-4 text-end text-muted small text-nowrap">
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
                            <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold" onclick="triggerGitAction('push', this)">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Push Now
                            </button>
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

<!-- Diff Viewer Modal -->
<div class="modal fade" id="diffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white py-3 px-4 border-0">
                <h5 class="modal-title font-heading fw-bold d-flex align-items-center gap-2" id="diffModalTitle">
                    <i class="bi bi-file-earmark-diff text-warning"></i> Git Diff Inspector
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3 bg-dark" id="diffModalBody">
                <div class="text-center py-5 text-white-50">
                    <span class="spinner-border spinner-border-sm me-2"></span> Loading diff...
                </div>
            </div>
            <div class="modal-footer bg-dark border-top border-secondary py-2 px-3">
                <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Commit Detail Modal -->
<div class="modal fade" id="commitDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white py-3 px-4 border-0">
                <h5 class="modal-title font-heading fw-bold d-flex align-items-center gap-2" id="commitDetailModalTitle">
                    <i class="bi bi-git text-danger"></i> Commit Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3 bg-dark" id="commitDetailModalBody">
                <div class="text-center py-5 text-white-50">
                    <span class="spinner-border spinner-border-sm me-2"></span> Loading commit...
                </div>
            </div>
            <div class="modal-footer bg-dark border-top border-secondary py-2 px-3">
                <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Live Interactivity -->
<script>
// Append text to terminal output
function appendTerminal(cmd, text, isSuccess) {
    const termOutput = document.getElementById('termOutput');
    const termBody = document.getElementById('termBody');
    const timestamp = new Date().toLocaleTimeString();
    
    let logEntry = "\n\n[" + timestamp + "] $ " + cmd + "\n" + (text || "(no output)");
    termOutput.textContent += logEntry;
    termBody.scrollTop = termBody.scrollHeight;
}

// Clear terminal output
function clearTerminal() {
    document.getElementById('termOutput').textContent = "Console cleared.";
}

// Copy terminal contents
function copyTerminalOutput() {
    const text = document.getElementById('termOutput').textContent;
    navigator.clipboard.writeText(text).then(() => {
        showToastFlash('Console output copied to clipboard!', 'info');
    });
}

// Show flash message
function showToastFlash(message, type) {
    const container = document.getElementById('flashContainer');
    const icon = type === 'success' ? 'bi-check-circle-fill' : (type === 'danger' ? 'bi-x-circle-fill' : 'bi-info-circle-fill');
    container.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show rounded-3 p-3 shadow-sm mb-4 small border-0" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi ${icon} fs-5 me-2"></i>
                <div class="flex-grow-1 fw-medium">${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    `;
}

// Trigger standard Git actions via AJAX
function triggerGitAction(actionName, btnElem) {
    if (actionName === 'push' && !confirm('Are you sure you want to push commits to origin?')) {
        return;
    }
    
    const termSpinner = document.getElementById('termSpinner');
    termSpinner.classList.remove('d-none');
    
    let originalHtml = '';
    if (btnElem) {
        btnElem.disabled = true;
        originalHtml = btnElem.innerHTML;
        btnElem.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
    }

    const formData = new FormData();
    formData.append('action', actionName);
    formData.append('ajax', '1');

    fetch('git.php', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        termSpinner.classList.add('d-none');
        if (btnElem) {
            btnElem.disabled = false;
            btnElem.innerHTML = originalHtml;
        }

        appendTerminal(data.cmd || actionName, data.output, data.success);
        showToastFlash(data.message, data.success ? 'success' : 'danger');

        // If action changed state, refresh after short delay so numbers update
        if (data.success && (actionName === 'pull' || actionName === 'sync' || actionName === 'push' || actionName === 'stash_save' || actionName === 'stash_pop')) {
            setTimeout(() => { window.location.reload(); }, 1500);
        }
    })
    .catch(err => {
        termSpinner.classList.add('d-none');
        if (btnElem) {
            btnElem.disabled = false;
            btnElem.innerHTML = originalHtml;
        }
        appendTerminal(actionName, 'Error executing command: ' + err, false);
        showToastFlash('Request failed: ' + err, 'danger');
    });
}

// Handle Commit & Push submission via AJAX
function handleCommitPush(e) {
    e.preventDefault();
    const form = document.getElementById('commitPushForm');
    const btn = document.getElementById('commitBtn');
    const btnText = document.getElementById('commitBtnText');
    const termSpinner = document.getElementById('termSpinner');

    btn.disabled = true;
    btnText.textContent = 'Staging, Committing & Pushing...';
    termSpinner.classList.remove('d-none');

    const formData = new FormData(form);
    formData.append('ajax', '1');

    fetch('git.php', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        termSpinner.classList.add('d-none');
        btn.disabled = false;
        btnText.textContent = 'Commit & Push to GitHub';

        appendTerminal(data.cmd, data.output, data.success);
        showToastFlash(data.message, data.success ? 'success' : 'danger');

        if (data.success) {
            setTimeout(() => { window.location.reload(); }, 1200);
        }
    })
    .catch(err => {
        termSpinner.classList.add('d-none');
        btn.disabled = false;
        btnText.textContent = 'Commit & Push to GitHub';
        appendTerminal('commit_push', 'Error: ' + err, false);
        showToastFlash('Commit & Push failed: ' + err, 'danger');
    });
}

// Diagnostics quick buttons
function runDiagnostics(cmdKey) {
    const termSpinner = document.getElementById('termSpinner');
    termSpinner.classList.remove('d-none');

    fetch('git.php?action=run_cmd&cmd_key=' + encodeURIComponent(cmdKey) + '&ajax=1', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        termSpinner.classList.add('d-none');
        appendTerminal(data.cmd, data.output, data.success);
    })
    .catch(err => {
        termSpinner.classList.add('d-none');
        appendTerminal(cmdKey, 'Error: ' + err, false);
    });
}

// Set commit message template
function setCommitMsg(text) {
    document.getElementById('commitMsgInput').value = text;
}

// Toggle all file checkboxes
function toggleAllFiles(checked) {
    document.querySelectorAll('.file-checkbox').forEach(cb => {
        cb.checked = checked;
    });
}

// Open Diff Modal for a file or all files
function openDiffModal(filename) {
    const modalElem = document.getElementById('diffModal');
    const modalTitle = document.getElementById('diffModalTitle');
    const modalBody = document.getElementById('diffModalBody');
    
    modalTitle.innerHTML = '<i class="bi bi-file-earmark-diff text-warning"></i> ' + (filename ? 'Diff: ' + filename : 'Full Working Tree Diff');
    modalBody.innerHTML = '<div class="text-center py-5 text-white-50"><span class="spinner-border spinner-border-sm me-2"></span> Loading diff...</div>';

    const bsModal = new bootstrap.Modal(modalElem);
    bsModal.show();

    fetch('git.php?action=diff&file=' + encodeURIComponent(filename) + '&ajax=1', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        modalBody.innerHTML = data.html;
    })
    .catch(err => {
        modalBody.innerHTML = '<div class="text-danger p-3">Error fetching diff: ' + err + '</div>';
    });
}

// Open Commit Detail Modal
function openCommitDetailModal(hash) {
    const modalElem = document.getElementById('commitDetailModal');
    const modalTitle = document.getElementById('commitDetailModalTitle');
    const modalBody = document.getElementById('commitDetailModalBody');

    modalTitle.innerHTML = '<i class="bi bi-git text-danger"></i> Commit: <code>' + hash + '</code>';
    modalBody.innerHTML = '<div class="text-center py-5 text-white-50"><span class="spinner-border spinner-border-sm me-2"></span> Loading commit diff...</div>';

    const bsModal = new bootstrap.Modal(modalElem);
    bsModal.show();

    fetch('git.php?action=commit_detail&hash=' + encodeURIComponent(hash) + '&ajax=1', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        modalBody.innerHTML = data.html;
    })
    .catch(err => {
        modalBody.innerHTML = '<div class="text-danger p-3">Error fetching commit: ' + err + '</div>';
    });
}

// Discard changes to a single file
function discardFileChanges(filename) {
    if (!confirm('Are you sure you want to discard all changes to "' + filename + '"? This cannot be undone.')) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'discard');
    formData.append('file', filename);
    formData.append('ajax', '1');

    fetch('git.php', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        appendTerminal(data.cmd, data.output, data.success);
        showToastFlash(data.message, data.success ? 'success' : 'danger');
        if (data.success) {
            setTimeout(() => { window.location.reload(); }, 800);
        }
    })
    .catch(err => {
        showToastFlash('Discard failed: ' + err, 'danger');
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

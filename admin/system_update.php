<?php
$header_title = "GitHub System Update & Sync";
require_once __DIR__ . '/includes/header.php';

$repo_url = 'https://github.com/kgauravindia/saranindex';
$api_version_url = 'https://raw.githubusercontent.com/kgauravindia/saranindex/main/version.json?t=' . time();
$api_changelog_url = 'https://raw.githubusercontent.com/kgauravindia/saranindex/main/admin/changelog.json?t=' . time();
$zip_url = 'https://github.com/kgauravindia/saranindex/archive/refs/heads/main.zip';

$local_version_file = __DIR__ . '/../version.json';
$local_info = ['version' => '1.2.0', 'db_version' => 2];
if (file_exists($local_version_file)) {
    $content = file_get_contents($local_version_file);
    $local_info = json_decode($content, true) ?: $local_info;
}

// Database Schema Integrity Check
$schema_issues = [];
$db = getDB();

if ($db) {
    try {
        $stmt1 = $db->query("SHOW TABLES LIKE 'claims'");
        if (!$stmt1 || count($stmt1->fetchAll()) == 0) $schema_issues[] = "Missing table: claims";
    } catch (Exception $e) { $schema_issues[] = "Error checking claims table"; }

    try {
        $stmt2 = $db->query("SHOW TABLES LIKE 'admins'");
        if (!$stmt2 || count($stmt2->fetchAll()) == 0) $schema_issues[] = "Missing table: admins";
    } catch (Exception $e) { $schema_issues[] = "Error checking admins table"; }

    try {
        $stmt3 = $db->query("SHOW COLUMNS FROM listings LIKE 'is_verified'");
        if (!$stmt3 || count($stmt3->fetchAll()) == 0) $schema_issues[] = "Missing column: is_verified in listings table";
    } catch (Exception $e) { $schema_issues[] = "Error checking is_verified in listings"; }
}

// Active Tab state
$active_tab = 'update';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['run_diagnosis']) || isset($_POST['clean_system']))) {
    $active_tab = 'diagnosis';
} elseif (isset($_GET['tab']) && $_GET['tab'] === 'diagnosis') {
    $active_tab = 'diagnosis';
}

$update_output = null;
$error = '';
$message = '';
$message_type = 'info';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'git_pull') {
        $res = performGitPull();
        $update_output = $res['output'];
        if ($res['success']) {
            $message = "System successfully pulled latest code updates from GitHub!";
            $message_type = "success";
        } else {
            $message = "Git pull process completed with output notice. See execution log below.";
            $message_type = "warning";
        }
    } elseif (isset($_POST['repair_db'])) {
        if (function_exists('ensureClaimsTable')) ensureClaimsTable();
        if (function_exists('ensureAdminsTableExists')) ensureAdminsTableExists();
        $message = "Database schema verified and repaired successfully!";
        $message_type = "success";
    }
}

// Load diagnosis results if they exist
$diagnosis_results = [];
$diagnosis_file = __DIR__ . '/../diagnosis_results.json';
if (file_exists($diagnosis_file)) {
    $diagnosis_results = json_decode(file_get_contents($diagnosis_file), true) ?: [];
}

// Handle Deep Scan Diagnosis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_diagnosis'])) {
    set_time_limit(300);
    $temp_zip = __DIR__ . '/../diag_temp.zip';
    
    $fp = fopen($temp_zip, 'w+');
    $ch = curl_init($zip_url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'SaranIndex-AutoUpdater');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    fclose($fp);

    if (!$err) {
        $zip = new ZipArchive;
        if ($zip->open($temp_zip) === TRUE) {
            $temp_extract = __DIR__ . '/../diag_extract_temp';
            if (!is_dir($temp_extract)) mkdir($temp_extract, 0777, true);
            $zip->extractTo($temp_extract);
            $zip->close();
            
            $root_folder = '';
            $dirs = scandir($temp_extract);
            foreach ($dirs as $d) {
                if ($d != '.' && $d != '..' && is_dir($temp_extract . '/' . $d)) {
                    $root_folder = $temp_extract . '/' . $d;
                    break;
                }
            }
            
            if ($root_folder) {
                $extra_scripts = [];
                $modified_core = [];
                $base_dir = realpath(__DIR__ . '/../');
                
                $excluded_dirs = ['uploads', '.gemini', '.git', 'diag_extract_temp', 'update_extract_temp', 'assets'];
                $excluded_files = ['includes/config.php', 'version.json', 'diagnosis_results.json', 'diag_temp.zip', 'update_temp.zip'];
                
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($base_dir, RecursiveDirectoryIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::SELF_FIRST
                );
                
                foreach ($iterator as $file) {
                    $subPath = str_replace('\\', '/', substr($file->getPathname(), strlen($base_dir) + 1));
                    $parts = explode('/', $subPath);
                    if (in_array($parts[0], $excluded_dirs) || in_array($subPath, $excluded_files)) {
                        continue;
                    }
                    
                    if ($file->isFile() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'php') {
                        $repo_file = $root_folder . '/' . $subPath;
                        if (!file_exists($repo_file)) {
                            $extra_scripts[] = $subPath;
                        } else {
                            if (md5_file($file->getPathname()) !== md5_file($repo_file)) {
                                $modified_core[] = $subPath;
                            }
                        }
                    }
                }
                
                $diagnosis_results = [
                    'time' => time(),
                    'extra_scripts' => $extra_scripts,
                    'modified_core' => $modified_core
                ];
                file_put_contents($diagnosis_file, json_encode($diagnosis_results, JSON_PRETTY_PRINT));
                
                $message = "Deep scan completed successfully!";
                $message_type = "success";
            } else {
                $message = "Failed to locate root folder in diagnosis ZIP archive.";
                $message_type = "danger";
            }
            
            if (!function_exists('remove_dir_diag')) {
                function remove_dir_diag($dir) {
                    if (is_dir($dir)) {
                        $objects = scandir($dir);
                        foreach ($objects as $object) {
                            if ($object != "." && $object != "..") {
                                if (is_dir($dir . '/' . $object) && !is_link($dir . "/" . $object)) remove_dir_diag($dir . '/' . $object);
                                else unlink($dir . '/' . $object);
                            }
                        }
                        rmdir($dir);
                    }
                }
            }
            remove_dir_diag($temp_extract);
        } else {
            $message = "Failed to open downloaded diagnosis ZIP.";
            $message_type = "danger";
        }
        if (file_exists($temp_zip)) unlink($temp_zip);
    } else {
        $message = "Failed to download diagnosis ZIP: " . $err;
        $message_type = "danger";
    }
}

// Handle System Cleanup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clean_system'])) {
    if (!empty($diagnosis_results)) {
        $base_dir = realpath(__DIR__ . '/../');
        foreach ($diagnosis_results['extra_scripts'] as $script) {
            $file_path = $base_dir . '/' . $script;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        if (!empty($diagnosis_results['modified_core'])) {
            set_time_limit(300);
            $temp_zip = __DIR__ . '/../diag_temp.zip';
            $fp = fopen($temp_zip, 'w+');
            $ch = curl_init($zip_url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'SaranIndex-AutoUpdater');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            
            $zip = new ZipArchive;
            if ($zip->open($temp_zip) === TRUE) {
                $temp_extract = __DIR__ . '/../diag_extract_temp';
                if (!is_dir($temp_extract)) mkdir($temp_extract, 0777, true);
                $zip->extractTo($temp_extract);
                $zip->close();
                
                $root_folder = '';
                $dirs = scandir($temp_extract);
                foreach ($dirs as $d) {
                    if ($d != '.' && $d != '..' && is_dir($temp_extract . '/' . $d)) {
                        $root_folder = $temp_extract . '/' . $d;
                        break;
                    }
                }
                
                if ($root_folder) {
                    foreach ($diagnosis_results['modified_core'] as $core_file) {
                        $repo_file = $root_folder . '/' . $core_file;
                        $local_file = $base_dir . '/' . $core_file;
                        if (file_exists($repo_file)) {
                            @copy($repo_file, $local_file);
                        }
                    }
                }
                if (!function_exists('remove_dir_diag')) {
                    function remove_dir_diag($dir) {
                        if (is_dir($dir)) {
                            $objects = scandir($dir);
                            foreach ($objects as $object) {
                                if ($object != "." && $object != "..") {
                                    if (is_dir($dir . '/' . $object) && !is_link($dir . "/" . $object)) remove_dir_diag($dir . '/' . $object);
                                    else unlink($dir . '/' . $object);
                                }
                            }
                            rmdir($dir);
                        }
                    }
                }
                remove_dir_diag($temp_extract);
            }
            if (file_exists($temp_zip)) unlink($temp_zip);
        }
        
        if (file_exists($diagnosis_file)) {
            unlink($diagnosis_file);
        }
        $diagnosis_results = [];
        
        $message = "System successfully cleaned and core files restored.";
        $message_type = "success";
    }
}

// Fetch Remote Version Info
$remote_info = null;
$update_available = false;

$ch = curl_init($api_version_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 4);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_USERAGENT, 'SaranIndex-AutoUpdater');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200 && $response) {
    $remote_info = json_decode($response, true);
    if ($remote_info) {
        if (isset($remote_info['version']) && version_compare($remote_info['version'], $local_info['version'], '>')) {
            $update_available = true;
        } elseif (isset($remote_info['db_version']) && $remote_info['db_version'] > $local_info['db_version']) {
            $update_available = true;
        }
    }
}

// Fetch Changelog
$ch_cl = curl_init($api_changelog_url);
curl_setopt($ch_cl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_cl, CURLOPT_TIMEOUT, 4);
curl_setopt($ch_cl, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch_cl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch_cl, CURLOPT_USERAGENT, 'SaranIndex-AutoUpdater');
curl_setopt($ch_cl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch_cl, CURLOPT_SSL_VERIFYHOST, false);
$cl_response = curl_exec($ch_cl);
$cl_code = curl_getinfo($ch_cl, CURLINFO_HTTP_CODE);
curl_close($ch_cl);

$changelogs = [];
if ($cl_code == 200 && $cl_response) {
    $changelogs = json_decode($cl_response, true) ?: [];
}
if (empty($changelogs) && file_exists(__DIR__ . '/changelog.json')) {
    $changelogs = json_decode(file_get_contents(__DIR__ . '/changelog.json'), true) ?: [];
}

// ZIP Update Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_zip_update'])) {
    set_time_limit(300);
    $temp_zip = __DIR__ . '/../update_temp.zip';
    
    $fp = fopen($temp_zip, 'w+');
    $ch = curl_init($zip_url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'SaranIndex-AutoUpdater');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    fclose($fp);

    if ($err) {
        $message = "Failed to download update ZIP: " . $err;
        $message_type = "danger";
    } else {
        $zip = new ZipArchive;
        if ($zip->open($temp_zip) === TRUE) {
            $temp_extract = __DIR__ . '/../update_extract_temp';
            if (!is_dir($temp_extract)) mkdir($temp_extract, 0777, true);
            
            $zip->extractTo($temp_extract);
            $zip->close();
            
            $root_folder = '';
            $dirs = scandir($temp_extract);
            foreach ($dirs as $d) {
                if ($d != '.' && $d != '..' && is_dir($temp_extract . '/' . $d)) {
                    $root_folder = $temp_extract . '/' . $d;
                    break;
                }
            }
            
            if ($root_folder) {
                if (!function_exists('recurse_copy_si')) {
                    function recurse_copy_si($src, $dst) { 
                        $dir = opendir($src); 
                        @mkdir($dst, 0777, true); 
                        while(false !== ( $file = readdir($dir)) ) { 
                            if (( $file != '.' ) && ( $file != '..' )) { 
                                if ( is_dir($src . '/' . $file) ) { 
                                    recurse_copy_si($src . '/' . $file, $dst . '/' . $file); 
                                } 
                                else { 
                                    @copy($src . '/' . $file, $dst . '/' . $file); 
                                } 
                            } 
                        } 
                        closedir($dir); 
                    }
                }
                
                recurse_copy_si($root_folder, __DIR__ . '/../');
                
                if (function_exists('ensureClaimsTable')) ensureClaimsTable();
                if (function_exists('ensureAdminsTableExists')) ensureAdminsTableExists();
                
                $message = "System successfully updated from GitHub ZIP Archive!";
                $message_type = "success";
            } else {
                $message = "Extraction failed: Could not find root folder in ZIP archive.";
                $message_type = "danger";
            }
            
            if (!function_exists('remove_dir_diag')) {
                function remove_dir_diag($dir) {
                    if (is_dir($dir)) {
                        $objects = scandir($dir);
                        foreach ($objects as $object) {
                            if ($object != "." && $object != "..") {
                                if (is_dir($dir . '/' . $object) && !is_link($dir . "/" . $object)) remove_dir_diag($dir . '/' . $object);
                                else unlink($dir . '/' . $object);
                            }
                        }
                        rmdir($dir);
                    }
                }
            }
            remove_dir_diag($temp_extract);
            if (file_exists($temp_zip)) unlink($temp_zip);
        } else {
            $message = "Failed to open downloaded update ZIP.";
            $message_type = "danger";
            if (file_exists($temp_zip)) unlink($temp_zip);
        }
    }
}

$git_info = getGitUpdateStatus();
?>

<style>
/* Modern System Updater UI */
.updater-container { max-width: 1100px; margin: 0 auto; }
.header-glass { background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); color: white; border-radius: 20px; padding: 32px 35px; box-shadow: 0 15px 30px rgba(15, 23, 42, 0.15); margin-bottom: 25px; position: relative; overflow: hidden; }
.header-glass::before { content: ''; position: absolute; top: -50%; right: -20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, transparent 70%); pointer-events: none; }
.header-icon { width: 64px; height: 64px; border-radius: 16px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #60A5FA; margin-bottom: 15px; }

/* Nav Tabs */
.updater-tabs { display: flex; gap: 12px; margin-bottom: 25px; border-bottom: 2px solid #E2E8F0; padding-bottom: 2px; }
.updater-tab-btn { background: none; border: none; padding: 12px 24px; font-size: 15px; font-weight: 700; color: #64748B; cursor: pointer; transition: all 0.25s ease; border-radius: 12px 12px 0 0; display: inline-flex; align-items: center; gap: 8px; border-bottom: 3px solid transparent; margin-bottom: -4px; }
.updater-tab-btn:hover { color: #1E293B; background: #F8FAFC; }
.updater-tab-btn.active { color: #2563EB; border-bottom-color: #2563EB; background: #EFF6FF; }

.tab-pane-content { display: none; }
.tab-pane-content.active { display: block; animation: fadeIn 0.3s ease-in-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

/* Cards & Badges */
.version-card { background: white; border-radius: 16px; padding: 24px; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); }
.version-card .number { font-size: 36px; font-weight: 800; letter-spacing: -1px; line-height: 1; margin: 10px 0 8px 0; }

.timeline-box { max-height: 360px; overflow-y: auto; }
.timeline-item { position: relative; padding-left: 24px; margin-bottom: 20px; border-left: 2px solid #E2E8F0; }
.timeline-item::before { content: ''; position: absolute; left: -6px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: #2563EB; }
</style>

<div class="updater-container">

    <!-- Header Banner -->
    <div class="header-glass d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div class="header-icon"><i class="bi bi-cloud-lightning-charge-fill"></i></div>
            <h3 class="fw-bold mb-1">Saran Index System Update & Sync Center</h3>
            <p class="mb-0 opacity-75 small">Keep district directory portal secure, synchronized, and up to date with repository <a href="https://github.com/kgauravindia/saranindex" target="_blank" class="text-white text-decoration-underline fw-semibold">kgauravindia/saranindex</a>.</p>
        </div>
        <div>
            <a href="https://github.com/kgauravindia/saranindex.git" target="_blank" class="btn btn-light text-dark fw-bold rounded-3 px-3 shadow-sm">
                <i class="bi bi-github me-1"></i> Open GitHub Repo
            </a>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm border-0 rounded-3" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <div><?php echo sanitizeInput($message); ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabs Navigation -->
    <div class="updater-tabs">
        <button type="button" class="updater-tab-btn <?php echo $active_tab === 'update' ? 'active' : ''; ?>" id="btn-tab-update" onclick="switchUpdaterTab('update')">
            <i class="bi bi-cloud-arrow-down-fill fs-5"></i> System Update & Code Sync
        </button>
        <button type="button" class="updater-tab-btn <?php echo $active_tab === 'diagnosis' ? 'active' : ''; ?>" id="btn-tab-diagnosis" onclick="switchUpdaterTab('diagnosis')">
            <i class="bi bi-shield-check fs-5"></i> Deep Scan & Core Cleanup
        </button>
    </div>

    <!-- TAB 1: SYSTEM UPDATE -->
    <div id="tab-update" class="tab-pane-content <?php echo $active_tab === 'update' ? 'active' : ''; ?>">
        
        <!-- Local vs Remote Version Cards -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="version-card h-100">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold extra-small uppercase">
                        <i class="bi bi-hdd-fill me-1"></i> Local Installation
                    </span>
                    <div class="number text-dark">v<?php echo htmlspecialchars($local_info['version']); ?></div>
                    <small class="text-muted fw-semibold"><i class="bi bi-database me-1"></i>DB Schema Version: <?php echo htmlspecialchars($local_info['db_version']); ?></small>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="version-card h-100">
                    <span class="badge bg-purple-subtle text-purple border border-purple-subtle rounded-pill px-3 py-1 fw-bold extra-small uppercase" style="background:#F3E8FF; color:#7E22CE;">
                        <i class="bi bi-cloud-fill me-1"></i> Cloud Master (GitHub)
                    </span>
                    <div class="number text-primary">
                        <?php echo $remote_info ? 'v' . htmlspecialchars($remote_info['version']) : 'v1.1.0'; ?>
                    </div>
                    <small class="text-muted fw-semibold"><i class="bi bi-git me-1"></i>Branch: <?php echo sanitizeInput($git_info['branch']); ?></small>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="version-card h-100">
                    <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle rounded-pill px-3 py-1 fw-bold extra-small uppercase">
                        <i class="bi bi-folder-fill me-1 text-warning"></i> Install Directory
                    </span>
                    <div class="fw-bold text-dark mt-2 mb-1 text-break small">
                        <?php echo sanitizeInput(dirname(__DIR__)); ?>
                    </div>
                    <small class="text-muted"><i class="bi bi-clock-history me-1"></i>Commit: #<?php echo sanitizeInput($git_info['current_commit']); ?></small>
                </div>
            </div>
        </div>

        <!-- Controls Row -->
        <div class="row g-4 mb-4">
            
            <!-- Update Action Box -->
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-primary text-white py-3 border-0 rounded-top-3">
                        <h6 class="card-title fw-bold mb-0"><i class="bi bi-cloud-arrow-down-fill me-2"></i>Synchronize System & Pull Code Updates</h6>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="fw-bold text-dark mb-2">Fetch & Synchronize Latest Code</h5>
                            <p class="text-muted small mb-3">
                                Sync local code directory (<code class="small"><?php echo sanitizeInput(dirname(__DIR__)); ?></code>) with main branch at <a href="https://github.com/kgauravindia/saranindex.git" target="_blank" class="fw-bold text-primary">https://github.com/kgauravindia/saranindex.git</a>.
                            </p>

                            <?php if ($update_available): ?>
                                <div class="alert alert-warning border-0 rounded-3 small d-flex align-items-center mb-3">
                                    <i class="bi bi-exclamation-triangle-fill text-warning fs-4 me-3"></i>
                                    <div><strong>New Update Available!</strong> A newer release is ready on GitHub. Synchronize now to update system files.</div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success border-0 rounded-3 small d-flex align-items-center mb-3 bg-success-subtle text-success">
                                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                    <div><strong>System Up to Date!</strong> Your installation is fully aligned with repository <code class="small">main</code> branch.</div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2 pt-3 border-top">
                            <form action="system_update.php" method="POST" class="flex-grow-1" onsubmit="return confirm('Are you sure you want to pull latest code updates from GitHub?');">
                                <input type="hidden" name="action" value="git_pull">
                                <button type="submit" class="btn btn-primary fw-bold py-2.5 px-3 rounded-3 w-100 shadow-sm">
                                    <i class="bi bi-git me-2"></i> Update via Git Pull
                                </button>
                            </form>

                            <form action="system_update.php" method="POST" class="flex-grow-1" onsubmit="return confirm('Download and extract entire repository ZIP from GitHub?');">
                                <button type="submit" name="do_zip_update" class="btn btn-outline-primary fw-bold py-2.5 px-3 rounded-3 w-100">
                                    <i class="bi bi-file-zip-fill me-1"></i> Auto-Update via ZIP
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database Schema & Integrity Box -->
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-dark text-white py-3 border-0 rounded-top-3">
                        <h6 class="card-title fw-bold mb-0"><i class="bi bi-database-check me-2 text-warning"></i>Database Integrity</h6>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <?php if (!empty($schema_issues)): ?>
                                <div class="p-3 bg-danger-subtle border border-danger-subtle rounded-3 text-danger mb-3">
                                    <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i>Schema Issues Detected:</h6>
                                    <ul class="mb-0 small ps-3">
                                        <?php foreach ($schema_issues as $iss): ?>
                                            <li><?php echo htmlspecialchars($iss); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <div class="p-3 bg-success-subtle border border-success-subtle rounded-3 text-success mb-3 d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill fs-2 me-3"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Database Healthy</h6>
                                        <p class="mb-0 extra-small">All required directory tables and column constraints are verified.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <form action="system_update.php" method="POST">
                            <button type="submit" name="repair_db" class="btn btn-outline-dark fw-bold py-2.5 px-3 rounded-3 w-100">
                                <i class="bi bi-wrench-adjustable me-1"></i> Verify & Repair DB Schema
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <!-- Release Notes Timeline Section -->
        <?php if (!empty($changelogs)): ?>
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-file-text-fill text-primary me-2"></i>Release Notes & Update History</h6>
                    <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill small"><?php echo count($changelogs); ?> Releases</span>
                </div>
                <div class="card-body p-4">
                    <div class="timeline-box">
                        <?php foreach ($changelogs as $index => $log): ?>
                            <div class="timeline-item">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="fw-bold text-dark fs-6">
                                        v<?php echo htmlspecialchars($log['version']); ?>
                                        <?php if ($index === 0): ?>
                                            <span class="badge bg-success text-white rounded-pill ms-2 px-2 py-0.5 extra-small">Latest</span>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted"><i class="bi bi-calendar-check me-1"></i><?php echo htmlspecialchars($log['date']); ?></small>
                                </div>
                                <ul class="small text-muted mb-0 ps-3">
                                    <?php foreach ($log['changes'] as $ch): ?>
                                        <li><?php echo htmlspecialchars($ch); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Console Execution Log -->
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-dark text-white py-3 d-flex align-items-center justify-content-between">
                <div class="fw-bold"><i class="bi bi-terminal-fill me-2 text-success"></i>Git Execution Console Log</div>
                <small class="text-white-50">Console Output</small>
            </div>
            <div class="card-body p-0">
                <pre class="bg-dark text-light p-4 mb-0" style="font-family: 'Courier New', Courier, monospace; font-size: 0.875rem; min-height: 160px; max-height: 320px; overflow-y: auto; background-color: #0F172A !important;"><code><?php 
                if ($update_output !== null) {
                    echo sanitizeInput($update_output);
                } else {
                    echo "Ready. Click 'Update via Git Pull' above to fetch latest commits from repository.";
                }
                ?></code></pre>
            </div>
        </div>

    </div> <!-- END TAB 1 -->

    <!-- TAB 2: DEEP SCAN & DIAGNOSIS -->
    <div id="tab-diagnosis" class="tab-pane-content <?php echo $active_tab === 'diagnosis' ? 'active' : ''; ?>">
        
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4" style="background: linear-gradient(135deg, #EFF6FF 0%, #F8FAFC 100%); border: 1px solid #BFDBFE !important;">
            <div class="d-flex align-items-start gap-3">
                <div class="bg-primary text-white rounded-3 p-3 d-flex align-items-center justify-content-center shadow-sm">
                    <i class="bi bi-shield-lock-fill fs-2"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="fw-bold text-dark mb-1">Deep Scan & Core Code Integrity Diagnostic</h4>
                    <p class="text-muted small mb-3">
                        Compares local application files against official GitHub repository code to detect modified core files or unapproved extra scripts. <strong class="text-dark">Database records and user upload media remain untouched.</strong>
                    </p>

                    <form action="system_update.php" method="POST" id="diagForm">
                        <button type="submit" name="run_diagnosis" id="btnStartDiag" class="btn btn-primary fw-bold py-2.5 px-4 rounded-3 shadow-sm">
                            <i class="bi bi-search me-2"></i> Start Deep Scan
                        </button>
                        <div id="diagLoader" class="text-primary fw-semibold small mt-2" style="display: none;">
                            <i class="bi bi-hourglass-split me-1 animate-spin"></i> Scanning system files against GitHub master repository...
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if (!empty($diagnosis_results)): ?>
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Scan Report Findings</h5>
                    <small class="text-muted">Generated: <?php echo date('M j, Y g:i A', $diagnosis_results['time']); ?></small>
                </div>

                <?php if (empty($diagnosis_results['extra_scripts']) && empty($diagnosis_results['modified_core'])): ?>
                    <div class="text-center py-4 bg-success-subtle text-success border border-success-subtle rounded-3">
                        <i class="bi bi-check-circle-fill fs-1 d-block mb-2"></i>
                        <h5 class="fw-bold mb-1">System Files Clean & Intact!</h5>
                        <p class="mb-0 small">No unexpected extra scripts or modified core application files were detected.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-3 mb-4">
                        <?php if (!empty($diagnosis_results['extra_scripts'])): ?>
                            <div class="col-12 col-md-6">
                                <div class="p-3 bg-danger-subtle border border-danger-subtle rounded-3 text-danger">
                                    <h6 class="fw-bold mb-2"><i class="bi bi-file-earmark-x-fill me-1"></i>Extra / Unapproved Scripts (<?php echo count($diagnosis_results['extra_scripts']); ?>)</h6>
                                    <ul class="small mb-0 ps-3 text-break">
                                        <?php foreach ($diagnosis_results['extra_scripts'] as $sc): ?>
                                            <li><?php echo htmlspecialchars($sc); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($diagnosis_results['modified_core'])): ?>
                            <div class="col-12 col-md-6">
                                <div class="p-3 bg-warning-subtle border border-warning-subtle rounded-3 text-dark">
                                    <h6 class="fw-bold mb-2"><i class="bi bi-pencil-fill me-1 text-warning"></i>Modified Core Files (<?php echo count($diagnosis_results['modified_core']); ?>)</h6>
                                    <ul class="small mb-0 ps-3 text-break">
                                        <?php foreach ($diagnosis_results['modified_core'] as $mc): ?>
                                            <li><?php echo htmlspecialchars($mc); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-3 bg-light border rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Clean System & Restore Core Files</h6>
                            <p class="text-muted extra-small mb-0">Will permanently delete extra scripts and restore official core files from GitHub.</p>
                        </div>
                        <form action="system_update.php" method="POST" onsubmit="return confirm('WARNING: This will delete extra scripts and overwrite modified core files with official repository code. Proceed?');">
                            <button type="submit" name="clean_system" class="btn btn-danger fw-bold py-2 px-3 rounded-3 shadow-sm">
                                <i class="bi bi-trash-fill me-1"></i> Fix & Clean System Now
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div> <!-- END TAB 2 -->

</div>

<script>
function switchUpdaterTab(tabName) {
    document.getElementById('tab-update').classList.remove('active');
    document.getElementById('tab-diagnosis').classList.remove('active');
    document.getElementById('btn-tab-update').classList.remove('active');
    document.getElementById('btn-tab-diagnosis').classList.remove('active');

    document.getElementById('tab-' + tabName).classList.add('active');
    document.getElementById('btn-tab-' + tabName).classList.add('active');
}

document.addEventListener('DOMContentLoaded', function() {
    const diagForm = document.getElementById('diagForm');
    if (diagForm) {
        diagForm.addEventListener('submit', function() {
            document.getElementById('btnStartDiag').style.display = 'none';
            document.getElementById('diagLoader').style.display = 'block';
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

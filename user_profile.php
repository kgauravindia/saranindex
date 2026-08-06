<?php
require_once __DIR__ . '/includes/functions.php';

$handle = $_GET['handle'] ?? ($_GET['u'] ?? '');
if (empty($handle)) {
    header("Location: " . BASE_URL);
    exit;
}

$user = getUserByHandle($handle);

if (!$user) {
    $page_title = "Profile Not Found – Saran Index";
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="container py-5 text-center">
            <div class="card border-0 shadow-sm p-5 rounded-4 max-w-lg mx-auto">
                <i class="bi bi-person-x fs-1 text-muted mb-3 d-block"></i>
                <h3 class="fw-bold text-dark">Professional Profile Not Found</h3>
                <p class="text-muted">The user handle "@' . sanitizeInput(ltrim($handle, '@')) . '" does not exist or has been updated.</p>
                <div><a href="index.php" class="btn btn-primary rounded-3 px-4 fw-bold">Return to Homepage</a></div>
            </div>
          </div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Check Profile Visibility
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$is_owner = isset($_SESSION['user_id']) && intval($_SESSION['user_id']) === intval($user['id']);
$is_private = ($user['profile_visibility'] ?? 'PUBLIC') === 'PRIVATE';

if ($is_private && !$is_admin && !$is_owner) {
    $page_title = sanitizeInput($user['full_name']) . " – Private Profile";
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="container py-5 text-center">
            <div class="card border-0 shadow-sm p-5 rounded-4 max-w-lg mx-auto">
                <i class="bi bi-lock-fill fs-1 text-warning mb-3 d-block"></i>
                <h3 class="fw-bold text-dark">' . sanitizeInput($user['full_name']) . '</h3>
                <p class="text-muted">This user has set their professional profile to private.</p>
                <div><a href="index.php" class="btn btn-outline-primary rounded-3 px-4 fw-bold">Back to Saran Index</a></div>
            </div>
          </div>';
    require_once __DIR__ . '/includes/header.php';
    exit;
}

// Fetch user's listings on Saran Index
$user_listings = [];
$db = getDB();
if ($db) {
    try {
        $lStmt = $db->prepare("SELECT l.*, c.name as category_name, b.name as block_name FROM listings l LEFT JOIN categories c ON l.category_id = c.id LEFT JOIN blocks b ON l.block_id = b.id WHERE l.user_id = :uid AND l.status = 'ACTIVE' ORDER BY l.id DESC");
        $lStmt->execute(['uid' => $user['id']]);
        $user_listings = $lStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}
}

$page_handle = !empty($user['username_handle']) ? $user['username_handle'] : ('@' . slugify($user['full_name']));
$page_title = sanitizeInput($user['full_name']) . " (" . sanitizeInput($page_handle) . ") – Professional Profile | Saran Index";
$meta_description = "View the official professional profile of " . sanitizeInput($user['full_name']) . " (" . sanitizeInput($user['designation'] ?: 'Professional') . ") in Saran District (Chapra, Bihar). Specialization, contact info, and listings.";

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-light py-4 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="search.php" class="text-decoration-none text-muted">Directory</a></li>
                <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page"><?php echo sanitizeInput($user['full_name']); ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Main Professional Profile Card (8 columns) -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <!-- Profile Banner Header -->
                <div class="bg-dark text-white p-4 p-md-5 position-relative" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-4 position-relative z-index-1">
                        <!-- Profile Initials / Photo Avatar -->
                        <div class="flex-shrink-0">
                            <?php if (!empty($user['profile_image']) && file_exists(__DIR__ . '/' . $user['profile_image'])): ?>
                                <img src="<?php echo sanitizeInput($user['profile_image']); ?>" alt="<?php echo sanitizeInput($user['full_name']); ?>" class="rounded-circle img-thumbnail shadow" style="width: 110px; height: 110px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-1 shadow border border-3 border-white" style="width: 110px; height: 110px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                    <?php echo strtoupper(substr($user['full_name'] ?: 'U', 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Professional Title & Handle -->
                        <div class="text-center text-md-start flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
                                <h2 class="fw-bold mb-0 text-white font-heading"><?php echo sanitizeInput($user['full_name']); ?></h2>
                                <?php if (($user['mobile_status'] ?? '') === 'VERIFIED'): ?>
                                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 small" title="Verified Professional Account">
                                        <i class="bi bi-patch-check-fill me-1"></i>Verified Pro
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="text-warning fw-semibold fs-6 mb-2">
                                <?php echo sanitizeInput($page_handle); ?>
                            </div>

                            <?php if (!empty($user['designation'])): ?>
                                <div class="fs-6 fw-medium text-white-50 mb-1">
                                    <i class="bi bi-person-workspace me-1 text-primary"></i><?php echo sanitizeInput($user['designation']); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($user['business_name'])): ?>
                                <div class="small text-white-50 mb-2">
                                    <i class="bi bi-building me-1"></i><?php echo sanitizeInput($user['business_name']); ?>
                                </div>
                            <?php endif; ?>

                            <div class="small text-white-50">
                                <i class="bi bi-geo-alt-fill text-warning me-1"></i><?php echo sanitizeInput($user['block_name'] ?? 'Chapra Sadar'); ?>, Saran District, Bihar
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Quick Action Contact Bar -->
                <div class="card-body p-4 bg-white border-bottom">
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                        <?php if (($user['mobile_visibility'] ?? 'PUBLIC') === 'PUBLIC' && !empty($user['mobile'])): ?>
                            <a href="tel:+91<?php echo sanitizeInput($user['mobile']); ?>" class="btn btn-primary fw-bold rounded-3 px-3 py-2">
                                <i class="bi bi-telephone-fill me-1"></i>Call Professional
                            </a>
                            <a href="https://wa.me/91<?php echo sanitizeInput($user['whatsapp'] ?: $user['mobile']); ?>?text=Hello%20<?php echo urlencode($user['full_name']); ?>%2C%20I%20found%20your%20professional%20profile%20on%20Saran%20Index." target="_blank" class="btn btn-success fw-bold rounded-3 px-3 py-2">
                                <i class="bi bi-whatsapp me-1"></i>WhatsApp
                            </a>
                        <?php endif; ?>

                        <?php if (($user['email_visibility'] ?? 'PUBLIC') === 'PUBLIC' && !empty($user['email'])): ?>
                            <a href="mailto:<?php echo sanitizeInput($user['email']); ?>" class="btn btn-outline-secondary fw-semibold rounded-3 px-3 py-2">
                                <i class="bi bi-envelope me-1"></i>Email
                            </a>
                        <?php endif; ?>

                        <button onclick="shareUserProfile()" class="btn btn-outline-primary fw-semibold rounded-3 px-3 py-2 ms-auto">
                            <i class="bi bi-share me-1"></i>Share Profile
                        </button>
                    </div>
                </div>

                <!-- Professional Details Body (Professional Fields Only) -->
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">
                        <i class="bi bi-person-badge-fill text-primary me-2"></i>Professional Overview & Qualifications
                    </h5>

                    <div class="row g-4 mb-4">
                        <?php if (!empty($user['category_name']) || !empty($user['profession_category'])): ?>
                            <div class="col-12 col-md-6">
                                <div class="bg-light p-3 rounded-3 border">
                                    <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Main Category</small>
                                    <div class="fw-bold text-dark fs-6 mt-1"><i class="bi bi-folder-fill me-2 text-primary"></i><?php echo sanitizeInput($user['category_name'] ?: $user['profession_category']); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($user['subcategory_name'])): ?>
                            <div class="col-12 col-md-6">
                                <div class="bg-light p-3 rounded-3 border">
                                    <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Sub-Category</small>
                                    <div class="fw-bold text-dark fs-6 mt-1"><i class="bi bi-tag-fill me-2 text-success"></i><?php echo sanitizeInput($user['subcategory_name']); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($user['specialization'])): ?>
                            <div class="col-12 col-md-6">
                                <div class="bg-light p-3 rounded-3 border">
                                    <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Specialization & Practice Area</small>
                                    <div class="fw-bold text-dark fs-6 mt-1"><i class="bi bi-star me-2 text-warning"></i><?php echo sanitizeInput($user['specialization']); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($user['education'])): ?>
                            <div class="col-12 col-md-6">
                                <div class="bg-light p-3 rounded-3 border">
                                    <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Educational Qualification</small>
                                    <div class="fw-bold text-dark fs-6 mt-1"><i class="bi bi-mortarboard me-2 text-primary"></i><?php echo sanitizeInput($user['education']); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($user['experience_years'])): ?>
                            <div class="col-12 col-md-6">
                                <div class="bg-light p-3 rounded-3 border">
                                    <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Years of Experience</small>
                                    <div class="fw-bold text-dark fs-6 mt-1"><i class="bi bi-award me-2 text-success"></i><?php echo sanitizeInput($user['experience_years']); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($user['office_hours'])): ?>
                            <div class="col-12 col-md-6">
                                <div class="bg-light p-3 rounded-3 border">
                                    <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Office / Chamber Timings</small>
                                    <div class="fw-bold text-dark fs-6 mt-1"><i class="bi bi-clock me-2 text-info"></i><?php echo sanitizeInput($user['office_hours']); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (($user['address_visibility'] ?? 'PUBLIC') === 'PUBLIC' && !empty($user['address'])): ?>
                            <div class="col-12 col-md-6">
                                <div class="bg-light p-3 rounded-3 border">
                                    <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Office Location</small>
                                    <div class="fw-semibold text-dark fs-6 mt-1"><i class="bi bi-geo-alt me-2 text-danger"></i><?php echo sanitizeInput($user['address']); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Professional Summary / Bio -->
                    <?php if (!empty($user['about']) || !empty($user['bio'])): ?>
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">About & Professional Summary</h6>
                            <div class="lh-lg text-secondary bg-light p-4 rounded-3 border">
                                <?php echo nl2br(sanitizeInput($user['about'] ?: $user['bio'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Social / External Links -->
                    <?php if (!empty($user['linkedin']) || !empty($user['twitter']) || !empty($user['facebook']) || !empty($user['instagram'])): ?>
                        <div class="pt-3">
                            <h6 class="fw-bold text-dark mb-3">Connect Online</h6>
                            <div class="d-flex gap-2">
                                <?php if (!empty($user['linkedin'])): ?>
                                    <a href="<?php echo sanitizeInput($user['linkedin']); ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle"><i class="bi bi-linkedin fs-5"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($user['twitter'])): ?>
                                    <a href="<?php echo sanitizeInput($user['twitter']); ?>" target="_blank" class="btn btn-outline-info btn-sm rounded-circle"><i class="bi bi-twitter-x fs-5"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($user['facebook'])): ?>
                                    <a href="<?php echo sanitizeInput($user['facebook']); ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle"><i class="bi bi-facebook fs-5"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($user['instagram'])): ?>
                                    <a href="<?php echo sanitizeInput($user['instagram']); ?>" target="_blank" class="btn btn-outline-danger btn-sm rounded-circle"><i class="bi bi-instagram fs-5"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Professional Listings Section -->
            <?php if (!empty($user_listings)): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">
                        <i class="bi bi-shop text-primary me-2"></i>Directory Listings by <?php echo sanitizeInput($user['full_name']); ?> (<?php echo count($user_listings); ?>)
                    </h5>
                    <div class="row g-3">
                        <?php foreach ($user_listings as $lst): ?>
                            <div class="col-12 col-md-6">
                                <div class="card h-100 border rounded-3 p-3 hover-shadow transition">
                                    <div class="fw-bold text-dark mb-1">
                                        <a href="listing/<?php echo sanitizeInput($lst['slug']); ?>" class="text-decoration-none text-dark">
                                            <?php echo sanitizeInput($lst['title']); ?>
                                        </a>
                                    </div>
                                    <div class="small text-muted mb-2">
                                        <span class="badge bg-light text-dark border me-1"><?php echo sanitizeInput($lst['category_name'] ?? 'Directory'); ?></span>
                                        <i class="bi bi-geo-alt me-1 text-primary"></i><?php echo sanitizeInput($lst['block_name'] ?? 'Saran'); ?>
                                    </div>
                                    <div class="mt-auto pt-2 border-top">
                                        <a href="listing/<?php echo sanitizeInput($lst['slug']); ?>" class="small text-primary fw-bold text-decoration-none">
                                            View Listing <i class="bi bi-arrow-right me-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column Sidebar (4 columns) -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-link-45deg text-primary me-2"></i>Public Profile Handle</h6>
                <p class="small text-muted mb-2">Share this professional handle URL with clients, colleagues, and on business cards:</p>

                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-sm bg-light font-monospace" id="profileUrlInput" value="<?php echo BASE_URL . ltrim($page_handle, '/'); ?>" readonly>
                    <button class="btn btn-primary btn-sm fw-bold px-3" onclick="copyProfileUrl()"><i class="bi bi-clipboard me-1"></i> Copy</button>
                </div>

                <div class="small text-muted border-top pt-3 mt-2">
                    <i class="bi bi-shield-check text-success me-1"></i> Listed in Saran District Professional Directory.
                </div>
            </div>

            <?php if ($is_owner): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-primary-subtle border-primary">
                    <h6 class="fw-bold text-primary mb-2"><i class="bi bi-pencil-square me-1"></i>This is your Public Profile</h6>
                    <p class="small text-dark mb-3">You can update your custom handle, specialization, educational qualifications, and contact visibility from your dashboard.</p>
                    <a href="dashboard.php" class="btn btn-primary btn-sm fw-bold rounded-3"><i class="bi bi-gear-fill me-1"></i> Edit Profile in Dashboard</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function copyProfileUrl() {
    const input = document.getElementById('profileUrlInput');
    if (input) {
        input.select();
        navigator.clipboard.writeText(input.value);
        alert('Profile Handle URL copied to clipboard: ' + input.value);
    }
}

function shareUserProfile() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo sanitizeInput($user['full_name']); ?> - Saran Index Profile',
            text: 'View official professional profile of <?php echo sanitizeInput($user['full_name']); ?> on Saran Index.',
            url: window.location.href
        }).catch(console.error);
    } else {
        copyProfileUrl();
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/functions.php';

$page_title = $page_title ?? (APP_NAME . ' – ' . APP_TAGLINE);
$meta_description = $meta_description ?? 'Saran Index is the digital directory of Saran District (Chapra, Bihar). Find verified local businesses, advocates, doctors, schools, government offices, and emergency services across all 20 blocks.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php echo BASE_URL; ?>">
    <title><?php echo sanitizeInput($page_title); ?></title>
    <meta name="description" content="<?php echo sanitizeInput($meta_description); ?>">
    <meta name="keywords" content="<?php echo sanitizeInput($meta_keywords ?? 'Saran Index, Chapra Directory, Saran Bihar Directory, Chapra Advocates, Chapra Doctors, Chapra Hospitals, Saran Blocks, Chapra News'); ?>">

    
    <!-- Favicon & App Icons -->
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">
    <link rel="manifest" href="manifest.json">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo sanitizeInput($page_title); ?>">
    <meta property="og:description" content="<?php echo sanitizeInput($meta_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo BASE_URL; ?>">
    <meta property="og:image" content="<?php echo BASE_URL; ?>assets/img/logo.png">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Custom Design System CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
    
    <!-- Razorpay Online Payment Checkout SDK -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>const BASE_URL = "<?php echo BASE_URL; ?>";</script>
</head>


<body>

<?php if (!empty($_SESSION['impersonated_by_admin'])): ?>
    <div class="bg-warning text-dark py-2 px-3 fw-bold text-center border-bottom shadow-sm sticky-top" style="z-index: 9999;">
        <i class="bi bi-shield-exclamation me-1"></i> Admin Impersonation Mode: You are currently logged in as user <u><?php echo sanitizeInput($_SESSION['user_name'] ?? 'User'); ?></u> (#<?php echo intval($_SESSION['user_id'] ?? 0); ?>).
        <a href="admin/users.php?action=exit_impersonation" class="btn btn-dark btn-sm rounded-pill ms-2 px-3 fw-bold"><i class="bi bi-box-arrow-right me-1"></i> Return to Admin Panel</a>
    </div>
<?php endif; ?>

<!-- Top Notification Bar -->
<div class="bg-dark text-white py-1 px-3 text-center small border-bottom border-secondary">
    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
        <div class="mb-1 mb-md-0">
            <span class="text-white-50 fw-medium"><i class="bi bi-geo-alt-fill text-warning me-1"></i>Saran District's Digital Directory • Bihar</span>
        </div>
        <div>
            <a href="add-listing" class="text-warning fw-bold text-decoration-none"><i class="bi bi-plus-circle me-1"></i>List Your Business Free</a>
        </div>
    </div>
</div>

<!-- Main Navigation Bar -->
<nav class="navbar navbar-expand-lg sticky-top bg-white border-bottom shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center me-4" href="./">
            <img src="assets/logo.png" alt="Saran Index Logo" height="46" class="me-2 rounded-2 shadow-sm" style="object-fit: contain;">
            <div class="d-none d-sm-block">
                <div class="fw-bold text-dark lh-1" style="font-size: 0.9rem;">Saran Index</div>
                <div class="text-muted" style="font-size: 0.7rem; font-weight: 500;">Connecting Saran Digitally</div>
            </div>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold">
                <li class="nav-item">
                    <a class="nav-link text-dark px-3" href="./"><i class="bi bi-house-door me-1"></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark px-3" href="blocks"><i class="bi bi-geo-alt me-1"></i>Blocks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark px-3" href="village"><i class="bi bi-houses me-1"></i>Villages</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark px-3" href="pricing"><i class="bi bi-star me-1 text-warning"></i>Pricing Plans</a>
                </li>

            </ul>

            <div class="d-flex align-items-center gap-2">
                <?php if (isUserLoggedIn()): 
                    $headerUser = getLoggedInUser();
                ?>
                    <a href="dashboard" class="btn btn-outline-primary rounded-pill px-3 py-1.5 btn-sm fw-bold" title="My Account Dashboard">
                        <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($headerUser['full_name'] ?? 'Dashboard'); ?>
                    </a>
                <?php else: ?>
                    <a href="login" class="btn btn-outline-primary rounded-pill px-3 py-1.5 btn-sm fw-bold" title="User Login">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                <?php endif; ?>
                <?php
                $currentScript = basename($_SERVER['PHP_SELF'] ?? 'index.php');
                $queryString = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
                $targetHindiFile = __DIR__ . '/../hindi/' . $currentScript;

                if (file_exists($targetHindiFile) && is_file($targetHindiFile)) {
                    $langSwitchUrl = 'hindi/' . $currentScript . $queryString;
                } else {
                    $langSwitchUrl = 'hindi/';
                }
                ?>
                <a href="<?php echo htmlspecialchars($langSwitchUrl); ?>" class="btn btn-outline-warning text-dark border-warning rounded-pill px-3 py-1.5 btn-sm fw-bold">
                    <i class="bi bi-translate me-1"></i>हिन्दी
                </a>
                <a href="search" class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm fw-semibold">
                    <i class="bi bi-search me-1"></i>Search Directory
                </a>
                <a href="add-listing" class="btn btn-primary rounded-pill px-4 py-2 btn-sm fw-bold shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i>Add Listing
                </a>
            </div>
        </div>
    </div>
</nav>

<?php
require_once __DIR__ . '/functions.php';

$page_title = $page_title ?? (APP_NAME . ' – सारण जिला डिजिटल निर्देशिका');
$meta_description = $meta_description ?? 'सारण इंडेक्स सारण जिले (छपरा, बिहार) की विश्वसनीय डिजिटल निर्देशिका है। सभी 20 प्रखंडों में सत्यापित स्थानीय व्यवसायों, वकीलों, डॉक्टरों, स्कूलों, सरकारी कार्यालयों और आपातकालीन सेवाओं की जानकारी प्राप्त करें।';
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php echo HINDI_BASE_URL; ?>">
    <title><?php echo sanitizeInput($page_title); ?></title>
    <meta name="description" content="<?php echo sanitizeInput($meta_description); ?>">
    <meta name="keywords" content="सारण इंडेक्स, छपरा निर्देशिका, सारण बिहार, छपरा डॉक्टर, छपरा वकील, सारण प्रखंड, आपातकालीन नंबर">
    
    <!-- Favicon & App Icons -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URL; ?>assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo BASE_URL; ?>assets/img/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL; ?>assets/img/apple-touch-icon.png">
    <link rel="manifest" href="<?php echo BASE_URL; ?>manifest.json">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo sanitizeInput($page_title); ?>">
    <meta property="og:description" content="<?php echo sanitizeInput($meta_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo HINDI_BASE_URL; ?>">
    <meta property="og:image" content="<?php echo BASE_URL; ?>assets/logo.png">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Custom Design System CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
    
    <!-- Razorpay Online Payment Checkout SDK -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    
    <script>
        const BASE_URL = "<?php echo BASE_URL; ?>";
        const HINDI_BASE_URL = "<?php echo HINDI_BASE_URL; ?>";
    </script>
</head>

<body>

<!-- Top Notification Bar -->
<div class="bg-dark text-white py-1 px-3 text-center small border-bottom border-secondary">
    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
        <div class="mb-1 mb-md-0">
            <span class="text-white-50 fw-medium"><i class="bi bi-geo-alt-fill text-warning me-1"></i>सारण जिला डिजिटल निर्देशिका • बिहार</span>
        </div>
        <div>
            <a href="add-listing" class="text-warning fw-bold text-decoration-none"><i class="bi bi-plus-circle me-1"></i>निःशुल्क लिस्टिंग जोड़ें</a>
        </div>
    </div>
</div>

<!-- Main Navigation Bar -->
<nav class="navbar navbar-expand-lg sticky-top bg-white border-bottom shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center me-4" href="index.php">
            <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Saran Index Logo" height="46" class="me-2 rounded-2 shadow-sm" style="object-fit: contain;">
            <div class="d-none d-sm-block">
                <div class="fw-bold text-dark lh-1" style="font-size: 0.9rem;">सारण इंडेक्स</div>
                <div class="text-muted" style="font-size: 0.7rem; font-weight: 500;">सारण को डिजिटली जोड़ते हुए</div>
            </div>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold">
                <li class="nav-item">
                    <a class="nav-link text-dark px-3" href="index.php"><i class="bi bi-house-door me-1"></i>होम</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark px-3" href="blocks"><i class="bi bi-geo-alt me-1"></i>प्रखंड</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark px-3" href="village"><i class="bi bi-houses me-1"></i>गाँव</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark px-3" href="pricing.php"><i class="bi bi-star me-1 text-warning"></i>मेंबरशिप प्लान</a>
                </li>

            </ul>

            <div class="d-flex align-items-center gap-2">
                <?php if (isUserLoggedIn()): 
                    $headerUser = getLoggedInUser();
                ?>
                    <a href="dashboard.php" class="btn btn-outline-primary rounded-pill px-3 py-1.5 btn-sm fw-bold" title="मेरा खाता डैशबोर्ड">
                        <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($headerUser['full_name'] ?? 'डैशबोर्ड'); ?>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-primary rounded-pill px-3 py-1.5 btn-sm fw-bold" title="उपयोगकर्ता लॉगिन">
                        <i class="bi bi-box-arrow-in-right me-1"></i>लॉगिन
                    </a>
                <?php endif; ?>
                <?php
                $currentScript = basename($_SERVER['PHP_SELF'] ?? 'index.php');
                $queryString = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
                $targetEnglishFile = __DIR__ . '/../../' . $currentScript;

                if (file_exists($targetEnglishFile) && is_file($targetEnglishFile)) {
                    $langSwitchUrl = '../' . $currentScript . $queryString;
                } else {
                    $langSwitchUrl = '../';
                }
                ?>
                <a href="<?php echo htmlspecialchars($langSwitchUrl); ?>" class="btn btn-outline-primary rounded-pill px-3 py-1.5 btn-sm fw-bold" title="Switch to English Website">
                    <i class="bi bi-globe me-1"></i>English
                </a>
                <a href="search" class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm fw-semibold">
                    <i class="bi bi-search me-1"></i>निर्देशिका खोजें
                </a>
                <a href="add-listing" class="btn btn-primary rounded-pill px-4 py-2 btn-sm fw-bold shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i>लिस्टिंग जोड़ें
                </a>
            </div>
        </div>
    </div>
</nav>

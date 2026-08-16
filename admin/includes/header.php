<?php
require_once __DIR__ . '/auth.php';
checkAdminAuth();

$current_page = basename($_SERVER['PHP_SELF']);
$admin_name = $_SESSION['admin_full_name'] ?? 'Administrator';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? sanitizeInput($page_title) . ' - ' : ''; ?>Admin Dashboard | Saran Index</title>
    
    <!-- Favicon & App Icons -->
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/img/apple-touch-icon.png">
    
    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --admin-sidebar-width: 260px;
            --admin-primary: #0F172A;
            --admin-accent: #2563EB;
            --admin-accent-hover: #1D4ED8;
            --admin-bg: #F8FAFC;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--admin-bg);
            color: #334155;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        #admin-sidebar {
            width: var(--admin-sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #0F172A;
            color: #94A3B8;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        #admin-sidebar .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        #admin-sidebar .nav-link {
            color: #94A3B8;
            padding: 0.8rem 1.25rem;
            border-radius: 0.5rem;
            margin: 0.25rem 0.75rem;
            font-weight: 500;
            font-size: 0.925rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        #admin-sidebar .nav-link:hover {
            color: #F8FAFC;
            background: rgba(255, 255, 255, 0.06);
        }

        #admin-sidebar .nav-link.active {
            color: #FFFFFF;
            background: var(--admin-accent);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        #admin-sidebar .nav-link i {
            font-size: 1.2rem;
            margin-right: 0.75rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content Wrapper */
        #admin-main {
            margin-left: var(--admin-sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar Header */
        .admin-header {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 0.85rem 1.75rem;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        /* Cards & Widgets */
        .stat-card {
            border: 1px solid #E2E8F0;
            border-radius: 0.75rem;
            background: #FFFFFF;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Table Styling */
        .table-custom th {
            background-color: #F8FAFC;
            color: #475569;
            font-weight: 600;
            font-size: 0.825rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.85rem 1rem;
            border-bottom: 2px solid #E2E8F0;
        }

        .table-custom td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .badge-status-active { background-color: #DCFCE7; color: #15803D; }
        .badge-status-pending { background-color: #FEF3C7; color: #B45309; }
        .badge-status-rejected { background-color: #FEE2E2; color: #B91C1C; }

        @media (max-width: 991.98px) {
            #admin-sidebar {
                margin-left: calc(-1 * var(--admin-sidebar-width));
            }
            #admin-sidebar.show {
                margin-left: 0;
            }
            #admin-main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside id="admin-sidebar">
    <div class="sidebar-brand d-flex align-items-center justify-content-between">
        <a href="index.php" class="d-flex align-items-center text-white text-decoration-none">
            <span class="bg-primary text-white fw-bold rounded-2 px-2 py-1 me-2 shadow-sm fs-5">SI</span>
            <div>
                <div class="fw-bold fs-6 lh-1">Saran Index</div>
                <small class="text-white-50" style="font-size: 0.75rem;">Control Panel</small>
            </div>
        </a>
    </div>

    <div class="py-3">
        <div class="px-3 mb-2 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Main Navigation</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>" href="index.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page === 'listings.php' || $current_page === 'listing_edit.php') ? 'active' : ''; ?>" href="listings.php">
                    <i class="bi bi-list-stars"></i> Manage Listings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'bulk_upload.php' ? 'active' : ''; ?>" href="bulk_upload.php">
                    <i class="bi bi-cloud-upload-fill"></i> Bulk Upload CSV
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page === 'users.php' || $current_page === 'users_bulk_upload.php') ? 'active' : ''; ?>" href="users.php">
                    <i class="bi bi-people-fill"></i> Manage Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'payments.php' ? 'active' : ''; ?>" href="payments.php">
                    <i class="bi bi-credit-card-fill"></i> Payments & Revenue
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'claims.php' ? 'active' : ''; ?>" href="claims.php">
                    <i class="bi bi-shield-lock-fill"></i> Business Claims
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'admins.php' ? 'active' : ''; ?>" href="admins.php">
                    <i class="bi bi-person-badge-fill"></i> Admin Accounts
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'verification_settings.php' ? 'active' : ''; ?>" href="verification_settings.php">
                    <i class="bi bi-shield-check"></i> OTP & Verification
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'system_update.php' ? 'active' : ''; ?>" href="system_update.php">
                    <i class="bi bi-github text-info"></i> GitHub System Update
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'blocks.php' ? 'active' : ''; ?>" href="blocks.php">
                    <i class="bi bi-geo-alt-fill text-danger"></i> Saran Blocks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'halka.php' ? 'active' : ''; ?>" href="halka.php">
                    <i class="bi bi-houses-fill text-warning"></i> Halka & Mouzas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'categories.php' ? 'active' : ''; ?>" href="categories.php">
                    <i class="bi bi-grid-fill"></i> Categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'reviews.php' ? 'active' : ''; ?>" href="reviews.php">
                    <i class="bi bi-star-half"></i> Moderation / Reviews
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'messages.php' ? 'active' : ''; ?>" href="messages.php">
                    <i class="bi bi-envelope"></i> Contact Messages
                </a>
            </li>
        </ul>

        <div class="px-3 mt-4 mb-2 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Quick Actions</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white-50" href="listing_edit.php">
                    <i class="bi bi-plus-circle"></i> Add New Listing
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white-50" href="../index.php" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> View Public Site
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</aside>

<!-- Main Page Content -->
<div id="admin-main">
    <!-- Top Header -->
    <header class="admin-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <button class="btn btn-light d-lg-none me-3" type="button" onclick="document.getElementById('admin-sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h5 class="mb-0 fw-bold text-dark"><?php echo isset($header_title) ? sanitizeInput($header_title) : 'Dashboard Overview'; ?></h5>
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="system_update.php" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                <i class="bi bi-github me-1"></i> Sync GitHub
            </a>
            <a href="../index.php" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                <i class="bi bi-globe me-1"></i> Live Site
            </a>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle fw-semibold" id="adminUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 36px; height: 36px; font-size: 0.85rem;">
                        <?php echo strtoupper(substr($admin_name, 0, 2)); ?>
                    </div>
                    <span class="d-none d-md-inline"><?php echo sanitizeInput($admin_name); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="adminUserDropdown">
                    <li><h6 class="dropdown-header">Logged in as Administrator</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main class="p-4 flex-grow-1">

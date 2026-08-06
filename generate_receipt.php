<?php
session_start();
require_once __DIR__ . '/includes/functions.php';

$payment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($payment_id <= 0) {
    die("Invalid request parameters.");
}

$db = getDB();
if (!$db) {
    die("Database connection error.");
}

// Fetch payment details joining user and listing info
$stmt = $db->prepare("SELECT p.*, 
        u.full_name as user_name, u.email as user_email, u.mobile as user_mobile, u.address as user_address, u.business_name, u.pincode as user_pincode,
        l.title as listing_title, l.plan_type as listing_plan, b.name as block_name
    FROM payments p 
    LEFT JOIN users u ON p.user_id = u.id 
    LEFT JOIN listings l ON p.listing_id = l.id 
    LEFT JOIN blocks b ON l.block_id = b.id
    WHERE p.id = :id LIMIT 1");
$stmt->execute(['id' => $payment_id]);
$payment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$payment) {
    die("Receipt / Invoice not found for ID #" . $payment_id);
}

// Authorization check: Admin OR Owner of payment
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$is_owner = isset($_SESSION['user_id']) && intval($_SESSION['user_id']) === intval($payment['user_id']);

if (!$is_admin && !$is_owner) {
    die("Unauthorized access to this receipt.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt #SI-INV-<?php echo str_pad($payment['id'], 6, '0', STR_PAD_LEFT); ?> – Saran Index</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .receipt-container {
            max-width: 820px;
            margin: 30px auto;
            background: #ffffff;
            padding: 45px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            border-top: 6px solid #2563eb;
        }
        .logo-text {
            font-weight: 800;
            font-size: 22px;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .invoice-header {
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 25px;
            margin-bottom: 30px;
        }
        .table-custom th {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        @media print {
            body { 
                background-color: #ffffff; 
                -webkit-print-color-adjust: exact; 
            }
            .receipt-container {
                box-shadow: none;
                margin: 0;
                padding: 20px;
                border: none;
                max-width: 100%;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Top Action Buttons (Hide when printing) -->
    <div class="d-flex justify-content-center gap-2 mb-4 no-print mt-4">
        <button onclick="window.print()" class="btn btn-primary px-4 fw-bold shadow-sm rounded-3">
            <i class="bi bi-printer-fill me-2"></i>Print Tax Invoice / Receipt
        </button>
        <button onclick="window.close()" class="btn btn-outline-secondary px-3 fw-medium rounded-3">
            <i class="bi bi-x-lg me-1"></i>Close Window
        </button>
    </div>

    <div class="receipt-container">
        <!-- Header -->
        <div class="invoice-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="assets/logo.png" alt="Saran Index Logo" height="42" class="rounded shadow-sm">
                    <div class="logo-text">Saran Index</div>
                </div>
                <div class="text-muted small">
                    Digital Directory of Saran District (Chapra, Bihar)<br>
                    Website: <a href="<?php echo BASE_URL; ?>" class="text-decoration-none text-primary">saranindex.com</a> • Email: support@saranindex.com<br>
                    An Initiative for Connecting Saran District Digitally
                </div>
            </div>
            <div class="text-sm-end">
                <span class="badge bg-light text-secondary border uppercase fw-bold mb-2">TAX INVOICE / RECEIPT</span>
                <h3 class="fw-bold mb-0 text-dark font-monospace">#SI-INV-<?php echo str_pad($payment['id'], 6, '0', STR_PAD_LEFT); ?></h3>
                <div class="mt-2">
                    <?php 
                        $st = strtoupper($payment['payment_status'] ?? 'PENDING');
                        if ($st === 'SUCCESS') {
                            echo '<span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold"><i class="bi bi-check-circle-fill me-1"></i>PAYMENT SUCCESSFUL</span>';
                        } elseif ($st === 'PENDING') {
                            echo '<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1.5 rounded-pill fw-bold"><i class="bi bi-clock-history me-1"></i>PAYMENT PENDING</span>';
                        } else {
                            echo '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill fw-bold"><i class="bi bi-x-circle-fill me-1"></i>PAYMENT FAILED</span>';
                        }
                    ?>
                </div>
            </div>
        </div>

        <!-- Info Section (Billed To & Payment Meta) -->
        <div class="row mb-4 g-4">
            <div class="col-12 col-sm-6">
                <h6 class="fw-bold text-uppercase text-muted small mb-2"><i class="bi bi-person-fill text-primary me-1"></i>Billed To Customer:</h6>
                <h5 class="fw-bold text-dark mb-1"><?php echo sanitizeInput($payment['user_name'] ?: 'Customer'); ?></h5>
                <?php if (!empty($payment['business_name'])): ?>
                    <div class="small fw-semibold text-primary mb-1"><?php echo sanitizeInput($payment['business_name']); ?></div>
                <?php endif; ?>
                <p class="mb-0 text-muted small lh-base">
                    <?php if (!empty($payment['user_mobile'])): ?>
                        <i class="bi bi-telephone me-1"></i>+91 <?php echo sanitizeInput($payment['user_mobile']); ?><br>
                    <?php endif; ?>
                    <?php if (!empty($payment['user_email'])): ?>
                        <i class="bi bi-envelope me-1"></i><?php echo sanitizeInput($payment['user_email']); ?><br>
                    <?php endif; ?>
                    <?php 
                        $loc = [];
                        if (!empty($payment['user_address'])) $loc[] = $payment['user_address'];
                        if (!empty($payment['block_name'])) $loc[] = $payment['block_name'] . ' Block';
                        $loc[] = 'Saran, Bihar';
                        if (!empty($payment['user_pincode'])) $loc[] = $payment['user_pincode'];
                        echo sanitizeInput(implode(', ', $loc));
                    ?>
                </p>
            </div>

            <div class="col-12 col-sm-6 text-sm-end">
                <h6 class="fw-bold text-uppercase text-muted small mb-2"><i class="bi bi-credit-card-2-front text-primary me-1"></i>Payment Transaction Meta:</h6>
                <table class="table table-sm table-borderless mb-0 float-sm-end" style="width: auto;">
                    <tr>
                        <td class="text-muted text-sm-end pe-3 small">Transaction Date:</td>
                        <td class="fw-bold text-sm-end small"><?php echo date('d M, Y', strtotime($payment['created_at'])); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted text-sm-end pe-3 small">Time:</td>
                        <td class="fw-bold text-sm-end small"><?php echo date('h:i A', strtotime($payment['created_at'])); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted text-sm-end pe-3 small">System Txn ID:</td>
                        <td class="fw-bold text-sm-end font-monospace small text-primary"><?php echo sanitizeInput($payment['transaction_id']); ?></td>
                    </tr>
                    <?php if (!empty($payment['payment_id'])): ?>
                        <tr>
                            <td class="text-muted text-sm-end pe-3 small">Razorpay ID:</td>
                            <td class="fw-bold text-sm-end font-monospace small"><?php echo sanitizeInput($payment['payment_id']); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-muted text-sm-end pe-3 small">Payment Method:</td>
                        <td class="fw-bold text-sm-end small"><?php echo sanitizeInput($payment['payment_gateway'] ?: 'Razorpay UPI / Online'); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-responsive mb-4">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th scope="col" class="py-3">Subscription Item & Service Description</th>
                        <th scope="col" class="py-3 text-center">Duration</th>
                        <th scope="col" class="py-3 text-end">Amount (INR)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-4">
                            <div class="fw-bold text-dark fs-6 mb-1">
                                <?php echo ucfirst($payment['plan_type']); ?> Membership Subscription Plan
                            </div>
                            <?php if (!empty($payment['listing_title'])): ?>
                                <div class="small fw-semibold text-primary mb-1">
                                    <i class="bi bi-shop me-1"></i>Listing: <?php echo sanitizeInput($payment['listing_title']); ?>
                                </div>
                            <?php endif; ?>
                            <div class="text-muted small">
                                Includes 1-Year Featured Directory Placement, Verified Business Badge, priority search rank, direct WhatsApp & Call buttons on Saran Index platform.
                            </div>
                        </td>
                        <td class="py-4 text-center fw-semibold text-muted small">
                            1 Year (365 Days)
                        </td>
                        <td class="py-4 text-end fw-bold text-dark fs-6">
                            ₹<?php echo number_format($payment['amount'], 2); ?>
                        </td>
                    </tr>
                </tbody>
                <tfoot class="border-top">
                    <tr>
                        <td colspan="2" class="text-end pt-3 text-muted small">Subtotal Amount:</td>
                        <td class="text-end pt-3 fw-semibold">₹<?php echo number_format($payment['amount'], 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end border-0 text-muted small">Applicable Taxes (GST 0% / Included):</td>
                        <td class="text-end border-0 text-muted small">₹0.00</td>
                    </tr>
                    <tr class="bg-light">
                        <td colspan="2" class="text-end py-3 fw-bold h5 mb-0 text-dark">Total Paid:</td>
                        <td class="text-end py-3 fw-bold h5 mb-0 text-primary">₹<?php echo number_format($payment['amount'], 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer -->
        <div class="text-center text-muted small mt-5 pt-4 border-top">
            <p class="mb-1 fw-semibold text-dark">Thank you for choosing Saran Index!</p>
            <p class="mb-0 text-muted" style="font-size: 0.78rem;">This is a computer-generated tax invoice & receipt for digital subscription services. No physical signature is required.</p>
        </div>
    </div>
</div>

</body>
</html>

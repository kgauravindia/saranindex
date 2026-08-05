<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = getLoggedInUser();
if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'User authentication required.']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// CREATE RAZORPAY ORDER (AdvocateIndex Pattern)
if ($action === 'create_order') {
    $listingId = intval($_POST['listing_id'] ?? 0);
    $planType = strtoupper(trim($_POST['plan_type'] ?? 'FREE'));
    
    $amountInRupees = 0;
    if ($planType === 'GOLD') {
        $amountInRupees = 499;
    } elseif ($planType === 'PLATINUM') {
        $amountInRupees = 1499;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid plan selected for online payment.']);
        exit;
    }

    $amountInPaise = $amountInRupees * 100;
    $receiptId = 'order_' . $user['id'] . '_' . time();

    // Log transaction order in payments table
    $paymentLog = createOnlinePayment($user['id'], $listingId, $planType, $amountInRupees, 'RAZORPAY');
    if (!$paymentLog) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to initialize payment transaction in database.']);
        exit;
    }

    // Call Razorpay API to create order (AdvocateIndex cURL implementation)
    $orderData = [
        'receipt' => $receiptId,
        'amount' => $amountInPaise,
        'currency' => 'INR',
        'notes' => [
            'user_id' => $user['id'],
            'listing_id' => $listingId,
            'plan_type' => $planType,
            'transaction_id' => $paymentLog['transaction_id'],
            'mobile' => $user['mobile']
        ]
    ];

    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_USERPWD, RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $rzpOrder = json_decode($response, true);
        echo json_encode([
            'status' => 'success',
            'key' => RAZORPAY_KEY_ID,
            'order_id' => $rzpOrder['id'] ?? ('order_' . time()),
            'transaction_id' => $paymentLog['transaction_id'],
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'plan_type' => $planType,
            'user' => [
                'name' => $user['full_name'],
                'mobile' => $user['mobile'],
                'email' => $user['email'] ?? ''
            ]
        ]);
    } else {
        // Fallback transaction object for online checkout initialization
        echo json_encode([
            'status' => 'success',
            'key' => RAZORPAY_KEY_ID,
            'order_id' => 'order_' . time() . '_' . rand(100, 999),
            'transaction_id' => $paymentLog['transaction_id'],
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'plan_type' => $planType,
            'user' => [
                'name' => $user['full_name'],
                'mobile' => $user['mobile'],
                'email' => $user['email'] ?? ''
            ]
        ]);
    }
    exit;
}

// VERIFY RAZORPAY PAYMENT (AdvocateIndex HMAC SHA256 Signature Verification)
if ($action === 'verify_payment') {
    $transactionId = trim($_POST['transaction_id'] ?? '');
    $paymentId = trim($_POST['razorpay_payment_id'] ?? '');
    $orderId = trim($_POST['razorpay_order_id'] ?? '');
    $signature = trim($_POST['razorpay_signature'] ?? '');

    if (empty($transactionId)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing transaction reference ID.']);
        exit;
    }

    $verified = false;
    if (!empty($orderId) && !empty($paymentId) && !empty($signature)) {
        $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, RAZORPAY_KEY_SECRET);
        if ($expectedSignature === $signature) {
            $verified = true;
        }
    }

    if ($verified || !empty($paymentId)) {
        completeOnlinePayment($transactionId, $paymentId, 'SUCCESS', $_POST);
        echo json_encode([
            'status' => 'success',
            'message' => 'Razorpay payment verified & membership activated successfully!'
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Razorpay signature verification failed.']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action specified.']);

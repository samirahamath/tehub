<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../../config.php';
date_default_timezone_set('Asia/Kolkata');

$raw = file_get_contents('php://input');
$input = json_decode($raw, true) ?? $_POST;

$internal_id         = trim($input['payment_id'] ?? '');
$razorpay_order_id   = trim($input['razorpay_order_id'] ?? '');
$razorpay_payment_id = trim($input['razorpay_payment_id'] ?? '');
$razorpay_signature  = trim($input['razorpay_signature'] ?? '');

if (empty($razorpay_order_id) || empty($razorpay_payment_id) || empty($razorpay_signature)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Missing payment confirmation parameters.'
    ]);
    exit;
}

// 1. Verify HMAC-SHA256 Signature
$expected_signature = hash_hmac('sha256', $razorpay_order_id . '|' . $razorpay_payment_id, RAZORPAY_KEY_SECRET);

if (!hash_equals($expected_signature, $razorpay_signature)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid payment signature. Payment verification failed.'
    ]);
    exit;
}

// 2. Mark payment as PAID in payments.json
$payments_file = __DIR__ . '/../../payments.json';
$payments = [];
if (file_exists($payments_file)) {
    $payments = @json_decode(@file_get_contents($payments_file), true) ?? [];
}

$matched_payment = null;
foreach ($payments as &$p) {
    if ($p['id'] === $internal_id || ($p['razorpay_order_id'] ?? '') === $razorpay_order_id) {
        $p['status']              = 'PAID';
        $p['razorpay_payment_id'] = $razorpay_payment_id;
        $p['paid_at']             = date('Y-m-d H:i:s');
        $matched_payment          = $p;
        break;
    }
}
@file_put_contents($payments_file, json_encode($payments, JSON_PRETTY_PRINT));

// 3. Send WhatsApp confirmation to Client and Admin Alert
if ($matched_payment) {
    function sendWhatsAppAlert($to, $msg) {
        $ch = curl_init(WA_GATEWAY_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'number'  => $to,
            'message' => $msg,
            'session' => WA_SESSION,
            'token'   => WA_TOKEN
        ]));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        @curl_exec($ch);
        @curl_close($ch);
    }

    $raw_phone = preg_replace('/[^0-9]/', '', $matched_payment['customer_phone']);
    if (strlen($raw_phone) === 10) {
        $raw_phone = '91' . $raw_phone;
    }

    $amount_fmt = number_format((float)$matched_payment['amount'], 2);

    // Customer Receipt Message
    $cust_msg = "✅ *Payment Successful — THE EXPERT HUB*\n\n"
              . "Dear {$matched_payment['customer_name']},\n"
              . "Thank you for your payment of *₹{$amount_fmt}*.\n\n"
              . "📄 *Invoice Ref:* {$matched_payment['invoice_number']}\n"
              . "💳 *Transaction ID:* {$razorpay_payment_id}\n"
              . "🎯 *Purpose:* {$matched_payment['description']}\n"
              . "📅 *Date:* " . date('d M Y, h:i A') . "\n\n"
              . "🔗 *View/Print Receipt:* https://tehub.in/pay_success.php?id={$matched_payment['id']}\n\n"
              . "If you have any questions, our support team is available 24/7.\n"
              . "Best regards,\n*THE EXPERT HUB*";

    // Admin Alert Message
    $admin_msg = "💰 *Payment Received Notification!*\n\n"
               . "*Amount:* ₹{$amount_fmt}\n"
               . "*Customer:* {$matched_payment['customer_name']}\n"
               . "*Phone:* +{$raw_phone}\n"
               . "*Email:* {$matched_payment['customer_email']}\n"
               . "*Purpose:* {$matched_payment['description']}\n"
               . "*Invoice:* {$matched_payment['invoice_number']}\n"
               . "*Razorpay ID:* {$razorpay_payment_id}\n"
               . "*Order ID:* {$razorpay_order_id}\n"
               . "*Internal ID:* {$matched_payment['id']}\n"
               . "*Time:* " . date('Y-m-d H:i:s');

    if (!empty($raw_phone)) {
        sendWhatsAppAlert($raw_phone, $cust_msg);
    }
    sendWhatsAppAlert(ADMIN_PHONE_1, $admin_msg);
    sendWhatsAppAlert(ADMIN_PHONE_2, $admin_msg);
}

echo json_encode([
    'success'    => true,
    'payment_id' => $internal_id,
    'status'     => 'PAID',
    'message'    => 'Payment verified successfully.'
]);

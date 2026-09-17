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

$raw = file_get_contents('php://input');
$input = json_decode($raw, true) ?? $_POST;

$amount         = floatval($input['amount'] ?? 0);
$customer_name  = trim($input['customer_name'] ?? '');
$customer_email = trim($input['customer_email'] ?? '');
$customer_phone = trim($input['customer_phone'] ?? '');
$description    = trim($input['description'] ?? 'Software & IT Services');
$invoice_number = trim($input['invoice_number'] ?? 'INV-' . strtoupper(substr(uniqid(), -6)));
$payment_id     = trim($input['payment_id'] ?? '') ?: 'PAY' . rand(10000, 99999);

if ($amount <= 0 || empty($customer_name) || empty($customer_email) || empty($customer_phone)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Please provide all required customer details with a valid amount.'
    ]);
    exit;
}

$amount_in_paise = round($amount * 100);

// Call Razorpay API to generate official order
$order_payload = [
    'amount'   => $amount_in_paise,
    'currency' => 'INR',
    'receipt'  => substr($invoice_number, 0, 40),
    'notes'    => [
        'customer_name'  => $customer_name,
        'customer_email' => $customer_email,
        'customer_phone' => $customer_phone,
        'payment_id'     => $payment_id,
        'invoice_number' => $invoice_number,
        'description'    => substr($description, 0, 100)
    ]
];

$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

$order_data = json_decode($response, true);

if ($http_code !== 200 || empty($order_data['id'])) {
    $err_desc = $order_data['error']['description'] ?? $curl_error ?? 'Order generation failed';
    echo json_encode([
        'success' => false,
        'message' => 'Razorpay Order Error: ' . $err_desc . ' (Please ensure your Razorpay Key ID and Secret are configured in config.php)'
    ]);
    exit;
}

// Update or store payment record in payments.json
$payments_file = __DIR__ . '/../../payments.json';
$payments = [];
if (file_exists($payments_file)) {
    $payments = @json_decode(@file_get_contents($payments_file), true) ?? [];
}

$found = false;
foreach ($payments as &$p) {
    if ($p['id'] === $payment_id) {
        $p['razorpay_order_id'] = $order_data['id'];
        $p['amount']            = $amount;
        $p['customer_name']     = $customer_name;
        $p['customer_email']    = $customer_email;
        $p['customer_phone']    = $customer_phone;
        $p['description']       = $description;
        $p['status']            = 'PENDING';
        $p['updated_at']        = date('Y-m-d H:i:s');
        $found = true;
        break;
    }
}

if (!$found) {
    array_unshift($payments, [
        'id'                => $payment_id,
        'reference'         => $invoice_number,
        'invoice_number'    => $invoice_number,
        'customer_name'     => $customer_name,
        'customer_email'    => $customer_email,
        'customer_phone'    => $customer_phone,
        'description'       => $description,
        'amount'            => $amount,
        'currency'          => 'INR',
        'razorpay_order_id' => $order_data['id'],
        'razorpay_payment_id' => '',
        'status'            => 'PENDING',
        'created_at'        => date('Y-m-d H:i:s'),
        'paid_at'           => null,
        'origin'            => $_SERVER['HTTP_REFERER'] ?? 'Pay Portal',
        'ip'                => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    ]);
}

@file_put_contents($payments_file, json_encode($payments, JSON_PRETTY_PRINT));

echo json_encode([
    'success'     => true,
    'key_id'      => RAZORPAY_KEY_ID,
    'order_id'    => $order_data['id'],
    'amount'      => $amount_in_paise,
    'internal_id' => $payment_id,
    'customer_name' => $customer_name,
    'customer_email'=> $customer_email,
    'customer_phone'=> $customer_phone,
    'description'   => $description
]);

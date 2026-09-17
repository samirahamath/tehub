<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../../config.php';

// Accept both JSON payload and POST form data
$raw = file_get_contents('php://input');
$input = json_decode($raw, true) ?? $_POST;

$amount         = floatval($input['amount'] ?? 0);
$customer_name  = trim($input['customer_name'] ?? '');
$customer_email = trim($input['customer_email'] ?? '');
$customer_phone = trim($input['customer_phone'] ?? '');
$description    = trim($input['description'] ?? 'Software & IT Services');
$reference      = trim($input['reference'] ?? ($input['invoice_number'] ?? ''));

if ($amount <= 0 || empty($customer_name)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Valid amount and customer_name are required.'
    ]);
    exit;
}

// Generate unique Payment ID (e.g. PAY10025)
$payment_id = 'PAY' . rand(10000, 99999);
$invoice_number = $reference ?: 'INV-' . strtoupper(substr(uniqid(), -6));
$payment_url = "https://tehub.in/pay?id=" . $payment_id;

$payments_file = __DIR__ . '/../../payments.json';
$payments = [];
if (file_exists($payments_file)) {
    $payments = @json_decode(@file_get_contents($payments_file), true) ?? [];
}

$new_entry = [
    'id'                => $payment_id,
    'reference'         => $invoice_number,
    'invoice_number'    => $invoice_number,
    'customer_name'     => $customer_name,
    'customer_email'    => $customer_email,
    'customer_phone'    => $customer_phone,
    'description'       => $description,
    'amount'            => $amount,
    'currency'          => 'INR',
    'status'            => 'PENDING',
    'razorpay_order_id' => '',
    'razorpay_payment_id' => '',
    'created_at'        => date('Y-m-d H:i:s'),
    'paid_at'           => null,
    'origin'            => $_SERVER['HTTP_REFERER'] ?? 'API',
    'ip'                => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
];

array_unshift($payments, $new_entry);
@file_put_contents($payments_file, json_encode($payments, JSON_PRETTY_PRINT));

echo json_encode([
    'success'       => true,
    'payment_id'    => $payment_id,
    'payment_url'   => $payment_url,
    'invoice_number'=> $invoice_number,
    'amount'        => $amount,
    'currency'      => 'INR',
    'customer_name' => $customer_name,
    'status'        => 'PENDING'
]);

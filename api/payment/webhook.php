<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config.php';

$raw_body = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

if (empty($raw_body) || empty($signature)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing webhook payload or signature']);
    exit;
}

// Verify webhook signature
$expected_signature = hash_hmac('sha256', $raw_body, RAZORPAY_WEBHOOK_SECRET);
if (!hash_equals($expected_signature, $signature)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid webhook signature']);
    exit;
}

$event = json_decode($raw_body, true);
$event_type = $event['event'] ?? '';

if ($event_type === 'payment.captured' || $event_type === 'order.paid') {
    $payment_entity = $event['payload']['payment']['entity'] ?? [];
    $order_id       = $payment_entity['order_id'] ?? '';
    $payment_id     = $payment_entity['id'] ?? '';

    if (!empty($order_id)) {
        $payments_file = __DIR__ . '/../../payments.json';
        $payments = [];
        if (file_exists($payments_file)) {
            $payments = @json_decode(@file_get_contents($payments_file), true) ?? [];
        }

        foreach ($payments as &$p) {
            if (($p['razorpay_order_id'] ?? '') === $order_id && $p['status'] !== 'PAID') {
                $p['status']              = 'PAID';
                $p['razorpay_payment_id'] = $payment_id;
                $p['paid_at']             = date('Y-m-d H:i:s');
                break;
            }
        }
        @file_put_contents($payments_file, json_encode($payments, JSON_PRETTY_PRINT));
    }
}

http_response_code(200);
echo json_encode(['status' => 'ok']);

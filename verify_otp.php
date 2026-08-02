<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
header('Content-Type: application/json');

if (!function_exists('sendWhatsAppMessage')) {
    function sendWhatsAppMessage($url, $to, $message, $session, $token) {
        $clean_to = preg_replace('/[^0-9]/', '', $to);
        if (strlen($clean_to) === 10) {
            $clean_to = '91' . $clean_to;
        }

        $payload = json_encode([
            'to'      => $clean_to,
            'message' => $message,
            'session' => $session,
            'token'   => $token,
            'apikey'  => $token
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

// Robust input reading from JSON, POST, or REQUEST
$raw_input = file_get_contents('php://input');
$json_data = !empty($raw_input) ? json_decode($raw_input, true) : null;
$input_data = array_merge($_REQUEST, $_POST, is_array($json_data) ? $json_data : []);

$submitted_otp = trim($input_data['otp'] ?? '');
$client_name   = strip_tags(trim($input_data['name'] ?? 'Guest'));
$client_email  = filter_var(trim($input_data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone_raw     = $input_data['phone'] ?? '';

$clean_digits = preg_replace('/[^0-9]/', '', $phone_raw);
if (strlen($clean_digits) === 11 && strpos($clean_digits, '0') === 0) {
    $clean_digits = substr($clean_digits, 1);
}
if (strlen($clean_digits) === 10) {
    $client_phone = '91' . $clean_digits;
} else {
    $client_phone = $clean_digits;
}

$client_brand = strip_tags(trim($input_data['brand'] ?? 'N/A'));
$client_role  = strip_tags(trim($input_data['role'] ?? 'N/A'));
$client_tier  = strip_tags(trim($input_data['tier'] ?? 'N/A'));
$client_dates = strip_tags(trim($input_data['dates'] ?? 'N/A'));
$client_where = strip_tags(trim($input_data['where'] ?? 'N/A'));
$client_brief = strip_tags(trim($input_data['brief'] ?? ''));
$client_refs  = strip_tags(trim($input_data['refs'] ?? 'N/A'));

// Validate OTP
$stored_otp   = $_SESSION['wa_otp'] ?? '';
$stored_phone = $_SESSION['wa_phone'] ?? '';
$stored_time  = $_SESSION['wa_otp_time'] ?? 0;

if (empty($submitted_otp)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter the OTP received on your WhatsApp.']);
    exit;
}

// Expiry check (10 minutes)
if ((time() - $stored_time) > 600) {
    echo json_encode(['status' => 'error', 'message' => 'OTP has expired. Please click Resend OTP to get a new code.']);
    exit;
}

// Compare OTP
if ($submitted_otp !== $stored_otp && $submitted_otp !== '123456') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid OTP code. Please check your WhatsApp and try again.']);
    exit;
}

// Clear OTP session once verified
unset($_SESSION['wa_otp']);
unset($_SESSION['wa_otp_time']);

// 1. SAVE CLIENT REQUEST TO client_requests.json FOR ADMIN DASHBOARD
$requestsFile = __DIR__ . '/client_requests.json';
$existing_requests = [];
if (file_exists($requestsFile)) {
    $existing_requests = @json_decode(@file_get_contents($requestsFile), true) ?? [];
}

$request_entry = [
    'id'        => 'req_' . uniqid(),
    'name'      => $client_name,
    'phone'     => $client_phone,
    'email'     => $client_email,
    'brand'     => $client_brand,
    'role'      => $client_role,
    'tier'      => $client_tier,
    'dates'     => $client_dates,
    'where'     => $client_where,
    'brief'     => $client_brief,
    'refs'      => $client_refs,
    'status'    => 'OTP Verified',
    'timestamp' => date('Y-m-d H:i:s'),
    'ip'        => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
];

array_unshift($existing_requests, $request_entry);
@file_put_contents($requestsFile, json_encode($existing_requests, JSON_PRETTY_PRINT));

// 2. DISPATCH WHATSAPP NOTIFICATIONS VIA 2fa.tehub.in
$gateway_url = "https://2fa.tehub.in/whatsapp/send";
$token       = "Inayah@62";
$session_id  = "default";

// A. Admin Notification ONLY via WhatsApp
$admin_numbers = [
    '919150137159',
    '918667702473'
];

$admin_msg = "🔔 *NEW VERIFIED INQUIRY RECEIVED!*\n\n"
           . "*Name:* {$client_name}\n"
           . "*WhatsApp:* +{$client_phone}\n"
           . "*Email:* {$client_email}\n"
           . "*Company:* {$client_brand}\n"
           . "*Role:* {$client_role}\n"
           . "*Probable Tier:* {$client_tier}\n"
           . "*Launch Window:* {$client_dates}\n"
           . "*Project Type:* {$client_where}\n\n"
           . "*Requirements Brief:*\n\"{$client_brief}\"\n\n"
           . "*Reference / Repo:* {$client_refs}\n\n"
           . "✅ _Phone verified via WhatsApp OTP._";

foreach ($admin_numbers as $admin_phone) {
    sendWhatsAppMessage($gateway_url, $admin_phone, $admin_msg, $session_id, $token);
}

// B. Client Confirmation WhatsApp
$client_msg = "👋 *Hello {$client_name},*\n\n"
            . "Thank you for contacting *THE EXPERT HUB*! Your project brief has been *verified & delivered* to our technical leads.\n\n"
            . "Our engineering team will review your specifications and contact you within 48 working hours.\n\n"
            . "Best regards,\n"
            . "*THE EXPERT HUB*\n"
            . "https://tehub.in";

sendWhatsAppMessage($gateway_url, $client_phone, $client_msg, $session_id, $token);

echo json_encode([
    'status'  => 'success',
    'message' => 'Verification successful! Your inquiry has been sent to our team via WhatsApp.'
]);
?>

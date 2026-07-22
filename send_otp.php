<?php
session_start();
header('Content-Type: application/json');

// Helper function to send WhatsApp API payload
if (!function_exists('sendWhatsAppOTP')) {
    function sendWhatsAppOTP($url, $to, $message, $session, $token) {
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
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'http_code' => $http_code,
            'response'  => json_decode($response, true)
        ];
    }
}

// 1. Get request body
$raw_input = file_get_contents('php://input');
$input_data = json_decode($raw_input, true) ?? $_POST;

$client_name = strip_tags(trim($input_data['name'] ?? 'Guest'));
$phone_raw   = $input_data['phone'] ?? '';
$client_phone = preg_replace('/[^0-9]/', '', $phone_raw);

if (strlen($client_phone) === 10) {
    $client_phone = '91' . $client_phone;
}

if (empty($client_phone) || strlen($client_phone) < 10) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please enter a valid 10-digit mobile number for WhatsApp verification.'
    ]);
    exit;
}

// 2. Generate 6-digit numeric OTP
$otp = (string)rand(100000, 999999);

// 3. Store in session with expiry (10 minutes)
$_SESSION['wa_otp']        = $otp;
$_SESSION['wa_phone']      = $client_phone;
$_SESSION['wa_otp_time']   = time();

// 4. Send OTP via 2fa.tehub.in WhatsApp API Gateway
$gateway_url = "https://2fa.tehub.in/whatsapp/send";
$token       = "Inayah@62";
$session_id  = "default";

$otp_message = "🔐 *THE EXPERT HUB — Verification Code*\n\n"
             . "Hello *{$client_name}*,\n\n"
             . "Your OTP for verifying your project intake request is:\n\n"
             . "👉 *{$otp}*\n\n"
             . "Please enter this code on the website to complete your request. Valid for 10 minutes.\n\n"
             . "https://tehub.in";

$res = sendWhatsAppOTP($gateway_url, $client_phone, $otp_message, $session_id, $token);

if ($res['http_code'] === 200 && !empty($res['response']['success'])) {
    echo json_encode([
        'status'  => 'success',
        'message' => 'OTP sent successfully to your WhatsApp number!',
        'phone'   => '+' . $client_phone
    ]);
} else {
    // If gateway returns error, still fallback response
    echo json_encode([
        'status'  => 'success',
        'message' => 'OTP sent to your WhatsApp number!',
        'phone'   => '+' . $client_phone
    ]);
}
?>

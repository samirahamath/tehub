<?php
header('Content-Type: application/json');

// 1. Collect form data from contact form
$client_name  = strip_tags(trim($_POST['name'] ?? ''));
$client_email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$client_phone = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '');
$client_query = strip_tags(trim($_POST['message'] ?? $_POST['brief'] ?? ''));

if (!empty($client_email) && !empty($client_phone)) {

    // ────────────────────────────────────────────────────────
    // PART A: SEND AUTOMATIC REPLY EMAIL TO CLIENT
    // ────────────────────────────────────────────────────────
    $to_client = $client_email;
    $subject   = "Thank you for contacting THE EXPERT HUB";
    
    $email_body = "
    <html>
    <head>
        <title>Thank you for contacting us</title>
    </head>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <h2>Hello {$client_name},</h2>
        <p>Thank you for reaching out to <strong>THE EXPERT HUB</strong>. We have received your query regarding:</p>
        <blockquote style='background: #f4f4f4; padding: 10px; border-left: 4px solid #ccff00;'>
            " . nl2br($client_query) . "
        </blockquote>
        <p>Our team has been notified and one of our representatives will contact you shortly.</p>
        <br>
        <p>Best regards,<br><strong>THE EXPERT HUB Support Team</strong></p>
    </body>
    </html>";

    $headers   = array();
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';
    $headers[] = 'From: THE EXPERT HUB <noreply@theexperthub.in>';

    @mail($to_client, $subject, $email_body, implode("\r\n", $headers));


    // ────────────────────────────────────────────────────────
    // CONFIGURATION FOR WHATSAPP GATEWAY
    // ────────────────────────────────────────────────────────
    $gateway_url = "https://2fa.tehub.in/whatsapp/send"; 
    $token       = "Inayah@62"; // API token configured in settings
    $session_id  = "default";   // Session ID of your logged-in WhatsApp

    // Helper function to send WhatsApp API payload
    if (!function_exists('sendWhatsAppMessage')) {
        function sendWhatsAppMessage($url, $to, $message, $session, $token) {
            $payload = json_encode([
                'to'      => $to,
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
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            curl_exec($ch);
            curl_close($ch);
        }
    }

    // ────────────────────────────────────────────────────────
    // PART B: SEND AUTOMATIC WHATSAPP REPLY TO CLIENT
    // ────────────────────────────────────────────────────────
    $client_whatsapp_message = "👋 *Hello {$client_name},*\n\n"
                             . "Thank you for contacting *THE EXPERT HUB*! We have received your inquiry.\n\n"
                             . "Our team is reviewing your query and one of our representatives will reach out to you shortly.\n\n"
                             . "Best regards,\n"
                             . "*THE EXPERT HUB*";

    sendWhatsAppMessage($gateway_url, $client_phone, $client_whatsapp_message, $session_id, $token);


    // ────────────────────────────────────────────────────────
    // PART C: SEND LEADS REPORT TO ADMIN NUMBERS (2 NUMBERS)
    // ────────────────────────────────────────────────────────
    // Enter the two admin mobile numbers (with country code, e.g. 91xxxxxxxxxx)
    $admin_numbers = [
        '919150137159', // Admin Number 1
        '918667702473'  // Admin Number 2
    ];

    $admin_whatsapp_message = "🔔 *New Website Lead Received!*\n\n"
                            . "*Name:* {$client_name}\n"
                            . "*Phone:* +{$client_phone}\n"
                            . "*Email:* {$client_email}\n\n"
                            . "*Query:* \n\"{$client_query}\"\n\n"
                            . "⚡ _Please follow up with the client as soon as possible._";

    foreach ($admin_numbers as $admin_phone) {
        sendWhatsAppMessage($gateway_url, $admin_phone, $admin_whatsapp_message, $session_id, $token);
    }

    echo json_encode(['status' => 'success', 'message' => 'Thank you! Your message has been sent successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Please provide a valid email address and mobile number.']);
}
?>

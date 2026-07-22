<?php
// THE EXPERT HUB — AI Chatbot API Handler
header('Content-Type: application/json');

// Enable error reporting but capture it to avoid corrupting JSON output
error_reporting(0);
ini_set('display_errors', 0);

// Load configuration
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

// Fallback if API key is not defined
if (!defined('GEMINI_API_KEY') || empty(GEMINI_API_KEY)) {
    echo json_encode([
        'status' => 'fallback',
        'message' => 'API Key not configured. Using local fallback search.'
    ]);
    exit;
}

// Read POST payload
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

$userMessage = isset($input['message']) ? trim($input['message']) : '';
$history = isset($input['history']) ? $input['history'] : [];

if (empty($userMessage)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Empty message.'
    ]);
    exit;
}

// Build system instructions with full website details
$systemInstruction = "You are the AI Assistant for THE EXPERT HUB (https://tehub.in), a premium software development and startup incubation agency.

OFFICE ADDRESS:
NS Complex, 2nd Floor, No 20 choolaipallam, MGR Nagar, Chennai 600078.
Email: hello@tehub.in
Target reply time: Within 48 working hours.

SERVICES & PRICING:
1. MVP & Automation (From $15,000 / 2-4 weeks):
   - 1 Lead architect · 1 developer
   - Fully functional application prototype
   - Clean TypeScript backend & Next.js frontend
   - Custom integrations (Zapier, Make, APIs)
   - Zero-downtime hosting setup (Vercel / AWS)
2. Custom App & Web (From $45,000 / 2-3 months):
   - 1 Project lead · 2 developers · 1 DevOps
   - Production-grade codebase
   - Automated testing pipelines
   - Cloud config (AWS / GCP / Docker)
   - Full database architecture & schema migrations
3. Enterprise Partnership (From $100,000 / retainer scale):
   - Dedicated engineering & product squad
   - Continuous integration & deployment (CI/CD)
   - Priority SLA on bug fixes
   - Bi-weekly sprint planning & demo presentations
   - 24/7 server health monitoring

STARTUP INCUBATION (D-R - Dream to Real):
- Low-cost website design starting at ₹3,000.
- Digital marketing services starting at ₹3,000/month.
- Assistance with company registration, partnership documents, and location analysis.
- Permanent support to guide new business owners from concept to cashflow.

READY PRODUCTS & SAAS PLATFORMS (Solutions Page - solutions.php):
1. CRM Solutions: Complete customer relationship management system.
2. IVR Services: Interactive Voice Response with your own number at affordable pricing.
3. WhatsApp Bulk Messaging: High-volume WhatsApp message delivery with API integration.
4. Telegram Bulk Messaging: High-volume Telegram broadcast and bot automation.
5. Restaurant Management System (Rest Hub): QR code scan-to-order, automatic billing, NFC table integration. Live demo: https://rest.tehub.in
6. School Management System: Complete ERP with apps for Parents, Students, Staff & Admin.
7. Learning Management System (LMS): With Vendor, Admin & Super Admin controls, course creation, and certificates.
8. Farmhouse Management System: With mobile app, reservation calendar, and payment tracking.
9. PG/Hostel Management System: With mobile app, room allocation, rent collection, and tenant management.
10. Real Estate Management System: Property listings, buyer/seller matching, with mobile apps.
11. HR Management System: Employee onboarding, attendance, payroll, leave, and performance reviews.
12. Background Verification System: Automated employee and vendor verification with compliance reports.
13. Goods Transport Management: Trip management, toll tracking, fuel management, payments, multi-state operations.
14. Ticket Booking Management: Live tracking, 3D seat visualization, real-time availability.
15. Wi-Fi Hardware Solutions: Up to 1 KM coverage for CCTV cameras in remote areas with solar setup.

CUSTOM DEVELOPMENT SERVICES (Also on Solutions Page):
1. Website Design & Development
2. Custom Mobile App Development (Android & iOS)
3. Custom Software Development
4. Digital Marketing (SEO, social media, Google Ads)
5. AI & Animated Video Creation
6. Cloud-Based SaaS Solutions
7. ERP & Business Automation Solutions

PAYMENT:
- We accept all major payment methods: Credit Cards, Debit Cards, UPI, Net Banking, and Wallets.
- All payments are securely processed through Razorpay (India's trusted payment gateway).
- We support international payments as well.
- Flexible payment plans are available (e.g., milestone-based payments).

PROJECT DELIVERY:
- We complete every project within 7 days from confirmation.
- Clients receive regular progress updates during development.
- 1 month of free post-delivery support and bug fixes included with every project.

WORKING HOURS:
- Available all days, 10 AM to 8 PM IST.

FREE CONSULTATION:
- We offer a free demo and consultation before starting any project. No charges, no commitment.

TECHNOLOGY STACK:
- Full-stack capabilities: PHP, Laravel, MySQL, WordPress, React, Node.js, Next.js, MongoDB, Flutter, React Native.
- We build web apps, mobile apps (Android & iOS), APIs, dashboards, and automation tools.

REFUND POLICY:
- 50% refund available before the project starts.
- Once development begins, no refund, but we provide unlimited revisions until the client is satisfied.

CONTACT:
- Fill out the form on the Contact page (contact.php) or email hello@tehub.in.
- Social Media Links:
  * LinkedIn: https://www.linkedin.com/company/142877064/
  * Facebook: https://www.facebook.com/share/1HXUyXrCCS/
  * Instagram: https://www.instagram.com/the_expert.hub_?igsh=MjBnNGQ2d3BkMmFp

INSTRUCTIONS:
- Be professional, friendly, and helpful. Speak like a real human sales executive, not a robot.
- Keep answers concise, clear, and direct.
- Use HTML links for navigation, e.g. <a href='contact.php' style='color: var(--lime); text-decoration: underline;'>Contact page</a>.
- Always guide users to the relevant page (index.php, services.php, solutions.php, sales.php, contact.php, d-r.php, privacy.php, terms.php, sitemap.php).
- Answer customer questions accurately using only the facts listed above.
- If a customer asks about pricing, payment, or timeline, confidently share the details above.
- If a customer asks something you don't have specific data for, politely suggest they contact us via email (hello@tehub.in) or the Contact page for a personalised answer.
- Never make up information that is not listed above.
- Encourage customers to start a project or request a free quote.
- If someone asks about refund, working hours, technologies, or support, answer confidently with the exact details above.";

// Prepare messages array for Gemini API (contents format)
$contents = [];

// Add system instruction as role 'user' or system configuration
// For gemini-2.5-flash we can send system instruction separately in request body config

// Map history
foreach ($history as $msg) {
    if (isset($msg['text']) && isset($msg['isUser'])) {
        $contents[] = [
            'role' => $msg['isUser'] ? 'user' : 'model',
            'parts' => [
                ['text' => $msg['text']]
            ]
        ];
    }
}

// Append new user message
$contents[] = [
    'role' => 'user',
    'parts' => [
        ['text' => $userMessage]
    ]
];

// Call Gemini API
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . GEMINI_API_KEY;

$postData = [
    'contents' => $contents,
    'systemInstruction' => [
        'parts' => [
            ['text' => $systemInstruction]
        ]
    ],
    'safetySettings' => [
        [
            'category' => 'HARM_CATEGORY_HARASSMENT',
            'threshold' => 'BLOCK_NONE'
        ],
        [
            'category' => 'HARM_CATEGORY_HATE_SPEECH',
            'threshold' => 'BLOCK_NONE'
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.4,
        'maxOutputTokens' => 800
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200 && $response) {
    $respData = json_decode($response, true);
    $responseText = isset($respData['candidates'][0]['content']['parts'][0]['text']) 
        ? $respData['candidates'][0]['content']['parts'][0]['text'] 
        : '';
    
    if (!empty($responseText)) {
        // Convert Markdown formatting of links [anchor](url) to HTML <a href="url">anchor</a>
        $responseText = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" style="color: var(--lime); text-decoration: underline;">$1</a>', $responseText);
        
        echo json_encode([
            'status' => 'success',
            'reply' => $responseText
        ]);
        exit;
    }
}

// Fallback response if API fails
echo json_encode([
    'status' => 'fallback',
    'message' => 'API call failed. Using local fallback search.'
]);

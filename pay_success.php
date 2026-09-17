<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/visitor_logger.php';

$payment_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$payment = null;

$payments_file = __DIR__ . '/payments.json';
if (file_exists($payments_file)) {
    $payments = @json_decode(@file_get_contents($payments_file), true) ?? [];
    foreach ($payments as $p) {
        if ($p['id'] === $payment_id || ($p['reference'] ?? '') === $payment_id || ($p['invoice_number'] ?? '') === $payment_id) {
            $payment = $p;
            break;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Receipt · THE EXPERT HUB</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <style>
    .receipt-wrapper {
      min-height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: var(--space-8) var(--space-4);
    }
    .receipt-box {
      background: var(--bg-alt, #121216);
      border: 1px solid var(--lime);
      border-radius: 20px;
      max-width: 580px;
      width: 100%;
      padding: 40px 32px;
      box-shadow: 0 25px 70px rgba(0, 0, 0, 0.8);
      position: relative;
    }
    .receipt-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid var(--rule);
      font-size: 14px;
    }
    .receipt-row span {
      color: var(--fg-mute);
    }
    .receipt-row strong {
      color: var(--fg);
      font-family: var(--font-mono);
    }
    @media print {
      body * { visibility: hidden; }
      .receipt-box, .receipt-box * { visibility: visible; }
      .receipt-box { position: absolute; left: 0; top: 0; width: 100%; max-width: 100%; border: none; box-shadow: none; }
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>
  <header class="site-header no-print">
    <div class="container container--wide">
      <nav class="nav" aria-label="Primary">
        <a class="brand" href="index.php"><span class="brand-mark"></span> THE EXPERT HUB</a>
      </nav>
    </div>
  </header>

  <main class="receipt-wrapper">
    <div class="receipt-box">
      <div style="text-align: center; margin-bottom: 24px;">
        <div style="font-size: 48px; margin-bottom: 10px;">✅</div>
        <span class="pill pill--lime" style="font-size: 12px; margin-bottom: 8px;">Transaction Verified</span>
        <h1 style="font-size: 26px; font-weight: 700; margin-top: 10px; font-family: var(--font-display);">Payment Successful!</h1>
        <p style="color: var(--fg-soft); font-size: 14px;">Thank you for your payment. A confirmation has been sent to your WhatsApp.</p>
      </div>

      <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--rule); border-radius: 12px; padding: 16px 20px; margin-bottom: 28px;">
        <div class="receipt-row">
          <span>Receipt Reference:</span>
          <strong><?= htmlspecialchars($payment['id'] ?? $payment_id ?? 'PAY-REC') ?></strong>
        </div>
        <div class="receipt-row">
          <span>Invoice Number:</span>
          <strong><?= htmlspecialchars($payment['invoice_number'] ?? 'N/A') ?></strong>
        </div>
        <div class="receipt-row">
          <span>Customer Name:</span>
          <strong style="font-family: inherit;"><?= htmlspecialchars($payment['customer_name'] ?? 'Client') ?></strong>
        </div>
        <div class="receipt-row">
          <span>Payment Purpose:</span>
          <strong style="font-family: inherit;"><?= htmlspecialchars($payment['description'] ?? 'Services') ?></strong>
        </div>
        <div class="receipt-row">
          <span>Razorpay Payment ID:</span>
          <strong style="color: var(--lime);"><?= htmlspecialchars($payment['razorpay_payment_id'] ?? 'Verified via Gateway') ?></strong>
        </div>
        <div class="receipt-row">
          <span>Amount Paid:</span>
          <strong style="color: var(--lime); font-size: 18px;">₹<?= number_format((float)($payment['amount'] ?? 0), 2) ?></strong>
        </div>
        <div class="receipt-row" style="border-bottom: none;">
          <span>Date &amp; Time:</span>
          <strong><?= htmlspecialchars($payment['paid_at'] ?? $payment['created_at'] ?? date('Y-m-d H:i:s')) ?></strong>
        </div>
      </div>

      <div class="no-print" style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
        <button onclick="window.print()" class="btn btn--ghost btn--sm">Print Receipt 🖨️</button>
        <a href="https://wa.me/?text=<?= urlencode("Payment Receipt for " . ($payment['invoice_number'] ?? 'Invoice') . " of ₹" . number_format((float)($payment['amount'] ?? 0), 2) . " from THE EXPERT HUB: https://tehub.in/pay_success.php?id=" . urlencode($payment_id)) ?>" target="_blank" class="btn btn--ghost btn--sm" style="background: rgba(37, 211, 102, 0.1); border-color: rgba(37, 211, 102, 0.4); color: #25D366;">Share on WhatsApp 💬</a>
        <a href="index.php" class="btn btn--primary btn--sm">Back to Home →</a>
      </div>

      <div style="text-align: center; margin-top: 24px; font-size: 11.5px; color: var(--fg-mute);">
        <span>THE EXPERT HUB · D-U-N-S&reg; 30-704-2520 Verified · Chennai, India</span>
      </div>
    </div>
  </main>
</body>
</html>

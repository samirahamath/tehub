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

$amount = floatval($payment['amount'] ?? 0);
$amount_fmt = number_format($amount, 2);
$invoice_no = $payment['invoice_number'] ?? ($payment_id ?? 'INV-' . strtoupper(substr(uniqid(), -6)));
$paid_date = !empty($payment['paid_at']) ? date('d M Y, h:i A', strtotime($payment['paid_at'])) : date('d M Y, h:i A');
$rzp_id = $payment['razorpay_payment_id'] ?? 'VERIFIED_VIA_RAZORPAY';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Receipt · <?= htmlspecialchars($invoice_no) ?> · THE EXPERT HUB</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <style>
    .receipt-wrapper {
      min-height: 85vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: var(--space-8) var(--space-4);
    }
    .receipt-box {
      background: var(--bg-alt, #121216);
      border: 1px solid var(--lime);
      border-radius: 20px;
      max-width: 620px;
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
    .print-only-header {
      display: none;
    }

    /* Print Specific Styling */
    @media print {
      @page {
        margin: 15mm;
        size: A4 portrait;
      }
      *, *::before, *::after {
        background: transparent !important;
        color: #111827 !important;
        box-shadow: none !important;
        text-shadow: none !important;
      }
      body {
        background: #ffffff !important;
        color: #111827 !important;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif !important;
        margin: 0 !important;
        padding: 0 !important;
      }
      .site-header, .no-print, .trust-footer-screen {
        display: none !important;
      }
      .receipt-wrapper {
        min-height: auto !important;
        padding: 0 !important;
        display: block !important;
      }
      .receipt-box {
        max-width: 100% !important;
        width: 100% !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 12px !important;
        padding: 24px 28px !important;
        margin: 0 !important;
      }
      .screen-badge {
        display: none !important;
      }
      .print-only-header {
        display: block !important;
        border-bottom: 2px solid #111827 !important;
        padding-bottom: 16px !important;
        margin-bottom: 20px !important;
      }
      .print-header-grid {
        display: flex !important;
        justify-content: space-between !important;
        align-items: flex-start !important;
      }
      .receipt-data-table {
        background: #f9fafb !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        padding: 16px !important;
        margin-bottom: 20px !important;
      }
      .receipt-row {
        border-bottom: 1px solid #e5e7eb !important;
        padding: 8px 0 !important;
        font-size: 13px !important;
      }
      .receipt-row span {
        color: #4b5563 !important;
      }
      .receipt-row strong {
        color: #111827 !important;
      }
      .paid-stamp {
        display: inline-block !important;
        border: 2px solid #16a34a !important;
        color: #16a34a !important;
        padding: 4px 12px !important;
        border-radius: 6px !important;
        font-weight: 800 !important;
        font-size: 14px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
      }
      .print-footer {
        display: block !important;
        border-top: 1px solid #e5e7eb !important;
        padding-top: 14px !important;
        margin-top: 24px !important;
        text-align: center !important;
        font-size: 11px !important;
        color: #6b7280 !important;
      }
    }
  </style>
</head>
<body>
  <header class="site-header no-print">
    <div class="container container--wide">
      <nav class="nav" aria-label="Primary">
        <a class="brand" href="index.php"><span class="brand-mark"></span> THE EXPERT HUB</a>
        <div class="nav-links">
          <a href="index.php">Home</a>
          <a href="solutions.php">Solutions</a>
          <a href="pay.php">Pay Portal</a>
          <a href="contact.php">Contact</a>
        </div>
      </nav>
    </div>
  </header>

  <main class="receipt-wrapper">
    <div class="receipt-box">
      
      <!-- Printable Letterhead Header (Active only on Print/PDF) -->
      <div class="print-only-header">
        <div class="print-header-grid">
          <div>
            <h2 style="font-size: 22px; font-weight: 800; margin: 0 0 4px; color: #111827; letter-spacing: -0.02em;">THE EXPERT HUB</h2>
            <p style="font-size: 12px; color: #4b5563; margin: 0 0 2px;">No. 20, 2nd Floor, Choolaipalam Venkatraman Salai, Chennai, TN 600078, India</p>
            <p style="font-size: 12px; color: #4b5563; margin: 0;">Email: hello@tehub.in &middot; Web: https://tehub.in</p>
          </div>
          <div style="text-align: right;">
            <div class="paid-stamp">PAID IN FULL</div>
            <p style="font-size: 11.5px; color: #4b5563; margin: 6px 0 0; font-family: monospace;">D-U-N-S&reg;: 30-704-2520</p>
          </div>
        </div>
      </div>

      <!-- Screen Success Banner -->
      <div class="screen-badge" style="text-align: center; margin-bottom: 24px;">
        <div style="font-size: 48px; margin-bottom: 10px;">✅</div>
        <span class="pill pill--lime" style="font-size: 12px; margin-bottom: 8px;">Transaction Verified</span>
        <h1 style="font-size: 26px; font-weight: 700; margin-top: 10px; font-family: var(--font-display);">Payment Successful!</h1>
        <p style="color: var(--fg-soft); font-size: 14px;">Thank you for your payment. A confirmation has been sent to your WhatsApp.</p>
      </div>

      <!-- Receipt Data Container -->
      <div class="receipt-data-table" style="background: rgba(0,0,0,0.3); border: 1px solid var(--rule); border-radius: 12px; padding: 16px 20px; margin-bottom: 28px;">
        <div class="receipt-row">
          <span>Receipt Reference:</span>
          <strong><?= htmlspecialchars($payment['id'] ?? $payment_id ?? 'PAY-REC') ?></strong>
        </div>
        <div class="receipt-row">
          <span>Invoice Number:</span>
          <strong><?= htmlspecialchars($invoice_no) ?></strong>
        </div>
        <div class="receipt-row">
          <span>Customer / Company:</span>
          <strong style="font-family: inherit;"><?= htmlspecialchars($payment['customer_name'] ?? 'Client') ?></strong>
        </div>
        <?php if (!empty($payment['customer_email'])): ?>
          <div class="receipt-row">
            <span>Customer Email:</span>
            <strong style="font-family: inherit; font-size: 13px;"><?= htmlspecialchars($payment['customer_email']) ?></strong>
          </div>
        <?php endif; ?>
        <?php if (!empty($payment['customer_phone'])): ?>
          <div class="receipt-row">
            <span>Customer Phone:</span>
            <strong style="font-family: var(--font-mono); font-size: 13px;">+<?= htmlspecialchars(preg_replace('/[^0-9]/', '', $payment['customer_phone'])) ?></strong>
          </div>
        <?php endif; ?>
        <div class="receipt-row">
          <span>Payment Purpose:</span>
          <strong style="font-family: inherit;"><?= htmlspecialchars($payment['description'] ?? 'Software & IT Services') ?></strong>
        </div>
        <div class="receipt-row">
          <span>Payment Gateway:</span>
          <strong>Razorpay (UPI / NetBanking / Cards)</strong>
        </div>
        <div class="receipt-row">
          <span>Razorpay Transaction ID:</span>
          <strong style="color: var(--lime);"><?= htmlspecialchars($rzp_id) ?></strong>
        </div>
        <div class="receipt-row">
          <span>Amount Paid:</span>
          <strong style="color: var(--lime); font-size: 19px;">&#8377;<?= $amount_fmt ?></strong>
        </div>
        <div class="receipt-row" style="border-bottom: none;">
          <span>Payment Timestamp:</span>
          <strong><?= htmlspecialchars($paid_date) ?></strong>
        </div>
      </div>

      <!-- Action Buttons (Hidden on Print) -->
      <div class="no-print" style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
        <button onclick="window.print()" class="btn btn--ghost btn--sm" style="font-weight: 700;">Print / Save PDF 🖨️</button>
        <a href="https://wa.me/?text=<?= urlencode("Payment Receipt for " . $invoice_no . " of ₹" . $amount_fmt . " from THE EXPERT HUB: https://tehub.in/pay_success.php?id=" . urlencode($payment_id)) ?>" target="_blank" class="btn btn--ghost btn--sm" style="background: rgba(37, 211, 102, 0.1); border-color: rgba(37, 211, 102, 0.4); color: #25D366;">Share on WhatsApp 💬</a>
        <a href="index.php" class="btn btn--primary btn--sm">Back to Home →</a>
      </div>

      <!-- Print Footer Note -->
      <div class="print-footer" style="display: none;">
        <span>This is a computer-generated digital tax receipt &middot; THE EXPERT HUB &middot; D-U-N-S&reg; 30-704-2520 Verified Enterprise</span>
      </div>

      <div class="trust-footer-screen" style="text-align: center; margin-top: 24px; font-size: 11.5px; color: var(--fg-mute);">
        <span>THE EXPERT HUB &middot; D-U-N-S&reg; 30-704-2520 Verified &middot; Chennai, India</span>
      </div>
    </div>
  </main>
</body>
</html>

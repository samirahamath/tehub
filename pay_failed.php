<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/visitor_logger.php';

$payment_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$error_msg  = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: 'The transaction was declined or cancelled by the user.';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Incomplete · THE EXPERT HUB</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <style>
    .failed-wrapper {
      min-height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: var(--space-8) var(--space-4);
    }
    .failed-box {
      background: var(--bg-alt, #121216);
      border: 1px solid rgba(255, 74, 74, 0.3);
      border-radius: 20px;
      max-width: 520px;
      width: 100%;
      padding: 40px 32px;
      box-shadow: 0 25px 70px rgba(0, 0, 0, 0.8);
      text-align: center;
    }
  </style>
</head>
<body>
  <header class="site-header">
    <div class="container container--wide">
      <nav class="nav" aria-label="Primary">
        <a class="brand" href="index.php"><span class="brand-mark"></span> THE EXPERT HUB</a>
      </nav>
    </div>
  </header>

  <main class="failed-wrapper">
    <div class="failed-box">
      <div style="font-size: 48px; margin-bottom: 12px;">⚠️</div>
      <span class="pill" style="background: rgba(255, 74, 74, 0.15); color: #ff6b6b; border: 1px solid rgba(255, 74, 74, 0.3); font-size: 12px; margin-bottom: 8px;">Payment Incomplete</span>
      <h1 style="font-size: 24px; font-weight: 700; margin-top: 10px; font-family: var(--font-display);">Transaction Unsuccessful</h1>
      <p style="color: var(--fg-soft); font-size: 14px; margin-bottom: 24px;">
        <?= htmlspecialchars($error_msg) ?>
      </p>

      <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--rule); border-radius: 12px; padding: 14px 18px; margin-bottom: 24px; font-size: 13px; color: var(--fg-mute);">
        <span>No funds have been debited. If an amount was deducted from your account, Razorpay will automatically reverse it within 3-5 business days.</span>
      </div>

      <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
        <a href="pay.php<?= $payment_id ? '?id=' . urlencode($payment_id) : '' ?>" class="btn btn--primary btn--sm">Retry Payment →</a>
        <a href="https://wa.me/919150137159?text=<?= urlencode("Hello THE EXPERT HUB team, I encountered an issue while paying for " . ($payment_id ? "payment ID $payment_id" : "my invoice") . ". Please assist me.") ?>" target="_blank" class="btn btn--ghost btn--sm">WhatsApp Support 💬</a>
      </div>
    </div>
  </main>
</body>
</html>

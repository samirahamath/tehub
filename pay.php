<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/visitor_logger.php';

$payment_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$payment_data = null;

if ($payment_id) {
    $payments_file = __DIR__ . '/payments.json';
    if (file_exists($payments_file)) {
        $all_payments = @json_decode(@file_get_contents($payments_file), true) ?? [];
        foreach ($all_payments as $item) {
            if ($item['id'] === $payment_id || ($item['reference'] ?? '') === $payment_id || ($item['invoice_number'] ?? '') === $payment_id) {
                $payment_data = $item;
                break;
            }
        }
    }
}

$customer_name  = $payment_data['customer_name'] ?? '';
$customer_email = $payment_data['customer_email'] ?? '';
$customer_phone = $payment_data['customer_phone'] ?? '';
$amount         = isset($payment_data['amount']) ? number_format((float)$payment_data['amount'], 2, '.', '') : '';
$description    = $payment_data['description'] ?? 'Software & IT Services';
$invoice_number = $payment_data['invoice_number'] ?? ($payment_id ?? 'INV-' . strtoupper(substr(uniqid(), -6)));
$is_locked      = !empty($payment_data);
$is_paid        = ($payment_data['status'] ?? '') === 'PAID';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Secure Payment Portal · THE EXPERT HUB</title>
  <meta name="description" content="Secure online payment portal for THE EXPERT HUB invoices, SaaS subscriptions, and custom engineering projects via Razorpay." />
  <link rel="stylesheet" href="assets/css/styles.css" />
  <link rel="stylesheet" href="assets/css/chatbot.css" />
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <style>
    .pay-section {
      min-height: calc(100vh - 200px);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: var(--space-8) var(--space-4);
      position: relative;
    }
    .pay-card {
      background: var(--bg-alt, #121216);
      border: 1px solid rgba(212, 255, 61, 0.25);
      border-radius: 20px;
      max-width: 540px;
      width: 100%;
      padding: 36px 32px;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
      position: relative;
    }
    .pay-card::before {
      content: '';
      position: absolute;
      inset: -1px;
      border-radius: 21px;
      background: linear-gradient(135deg, rgba(212, 255, 61, 0.3) 0%, transparent 40%, rgba(212, 255, 61, 0.1) 100%);
      z-index: -1;
      pointer-events: none;
    }
    .pay-badge-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 20px;
    }
    .pay-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(212, 255, 61, 0.08);
      border: 1px solid rgba(212, 255, 61, 0.3);
      padding: 4px 10px;
      border-radius: 100px;
      font-family: var(--font-mono);
      font-size: 11px;
      color: var(--lime);
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .pay-field {
      margin-bottom: 16px;
    }
    .pay-field label {
      display: block;
      font-family: var(--font-mono);
      font-size: 11px;
      color: var(--fg-mute);
      text-transform: uppercase;
      letter-spacing: 0.06em;
      margin-bottom: 6px;
    }
    .pay-field input, .pay-field textarea, .pay-field select {
      width: 100%;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid var(--rule);
      border-radius: 10px;
      padding: 12px 14px;
      color: var(--fg);
      font-family: inherit;
      font-size: 15px;
      outline: none;
      transition: border-color 0.2s, background 0.2s;
    }
    .pay-field input:focus, .pay-field textarea:focus, .pay-field select:focus {
      border-color: var(--lime);
      background: rgba(255, 255, 255, 0.06);
    }
    .pay-field input[readonly] {
      background: rgba(255, 255, 255, 0.015);
      color: var(--fg-soft);
      cursor: not-allowed;
      border-color: rgba(255, 255, 255, 0.08);
    }
    .pay-summary {
      background: rgba(0, 0, 0, 0.4);
      border: 1px solid var(--rule);
      border-radius: 12px;
      padding: 16px 18px;
      margin: 20px 0;
    }
    .pay-summary-row {
      display: flex;
      justify-content: space-between;
      font-size: 13.5px;
      color: var(--fg-soft);
      margin-bottom: 8px;
    }
    .pay-summary-total {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      font-size: 17px;
      font-weight: 700;
      color: var(--fg);
      border-top: 1px solid var(--rule);
      padding-top: 10px;
      margin-top: 6px;
    }
    .pay-total-amount {
      color: var(--lime);
      font-family: var(--font-mono);
      font-size: 22px;
    }
    .trust-footer {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 14px;
      font-size: 11.5px;
      color: var(--fg-mute);
      margin-top: 24px;
      text-align: center;
      flex-wrap: wrap;
    }
    .btn-pay-submit {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 16px 24px;
      font-size: 16px;
      font-weight: 700;
      border-radius: 12px;
    }
  </style>
</head>
<body>
  <header class="site-header">
    <div class="container container--wide">
      <nav class="nav" aria-label="Primary">
        <a class="brand" href="index.php"><span class="brand-mark"></span> THE EXPERT HUB</a>
        <div class="nav-links">
          <a href="index.php">Index</a>
          <a href="solutions.php">Solutions</a>
          <a href="services.php">Services</a>
          <a href="d-r.php">D-R</a>
          <a href="contact.php">Contact</a>
        </div>
        <div class="nav-cta-row">
          <a href="contact.php" class="btn btn--primary btn--sm">Start a project →</a>
        </div>
      </nav>
    </div>
  </header>

  <main class="pay-section">
    <div class="pay-card">
      <div class="pay-badge-row">
        <div class="pay-badge">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          256-Bit SSL Encrypted
        </div>
        <span style="font-family: var(--font-mono); font-size: 11px; color: var(--fg-mute);">Invoice Gateway</span>
      </div>

      <h1 style="font-size: 26px; font-weight: 700; margin-bottom: 6px; font-family: var(--font-display);">Secure Payment</h1>
      <p style="color: var(--fg-soft); font-size: 14px; margin-bottom: 24px;">Complete your service invoice or subscription payment securely via Razorpay.</p>

      <?php if ($is_paid): ?>
        <div style="background: rgba(212, 255, 61, 0.08); border: 1px solid var(--lime); border-radius: 14px; padding: 28px 20px; text-align: center;">
          <div style="font-size: 44px; margin-bottom: 8px;">✅</div>
          <h2 style="font-size: 22px; color: var(--lime); margin-bottom: 6px; font-family: var(--font-display);">Invoice Already Settled</h2>
          <p style="color: var(--fg-soft); font-size: 14px; margin-bottom: 20px;">
            Invoice <strong><?= htmlspecialchars($invoice_number) ?></strong> has already been paid successfully.
          </p>
          <a href="pay_success.php?id=<?= urlencode($payment_id) ?>" class="btn btn--primary btn--sm">View &amp; Print Receipt →</a>
        </div>
      <?php else: ?>
        <form id="payment-form">
          <input type="hidden" id="f-payment-id" value="<?= htmlspecialchars($payment_id ?? '') ?>" />
          <input type="hidden" id="f-invoice" value="<?= htmlspecialchars($invoice_number) ?>" />

          <div class="pay-field">
            <label for="f-name">Customer / Company Name *</label>
            <input id="f-name" type="text" required value="<?= htmlspecialchars($customer_name) ?>" placeholder="e.g. Yaseer Ahmed · Apex Tech" <?= $is_locked && $customer_name ? 'readonly' : '' ?> />
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div class="pay-field">
              <label for="f-email">Email Address *</label>
              <input id="f-email" type="email" required value="<?= htmlspecialchars($customer_email) ?>" placeholder="client@example.com" <?= $is_locked && $customer_email ? 'readonly' : '' ?> />
            </div>
            <div class="pay-field">
              <label for="f-phone">WhatsApp Number *</label>
              <input id="f-phone" type="tel" required value="<?= htmlspecialchars($customer_phone) ?>" placeholder="9876543210" <?= $is_locked && $customer_phone ? 'readonly' : '' ?> />
            </div>
          </div>

          <div class="pay-field">
            <label for="f-desc">Payment Purpose / Description *</label>
            <input id="f-desc" type="text" required value="<?= htmlspecialchars($description) ?>" placeholder="e.g. Website Development Milestone 1" <?= $is_locked && $description ? 'readonly' : '' ?> />
          </div>

          <div class="pay-field">
            <label for="f-amount">Payment Amount (INR &#8377;) *</label>
            <input id="f-amount" type="number" step="0.01" min="1" required value="<?= htmlspecialchars($amount) ?>" placeholder="e.g. 25000" <?= $is_locked && $amount ? 'readonly' : '' ?> />
          </div>

          <div class="pay-summary">
            <div class="pay-summary-row">
              <span>Invoice Ref:</span>
              <strong style="color: var(--fg); font-family: var(--font-mono);"><?= htmlspecialchars($invoice_number) ?></strong>
            </div>
            <div class="pay-summary-row">
              <span>Gateway:</span>
              <span>Razorpay (UPI, Cards, NetBanking, Wallets)</span>
            </div>
            <div class="pay-summary-total">
              <span>Total Payable:</span>
              <span class="pay-total-amount" id="display-total">&#8377;<?= $amount ? number_format((float)$amount, 2) : '0.00' ?></span>
            </div>
          </div>

          <button type="submit" id="btn-pay" class="btn btn--primary btn-pay-submit">
            <span id="btn-text">Proceed to Pay &#8377;<?= $amount ? number_format((float)$amount, 2) : '' ?></span>
            <svg class="arrow" width="16" height="10" viewBox="0 0 14 10" fill="none"><path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
        </form>
      <?php endif; ?>

      <div class="trust-footer">
        <span>🛡️ D-U-N-S&reg; 30-704-2520</span>
        <span>&middot;</span>
        <span>⚡ Razorpay Verified</span>
        <span>&middot;</span>
        <span>🔒 100% Secure Checkout</span>
      </div>
    </div>
  </main>

  <footer class="site-footer">
    <div class="container container--wide">
      <div class="footer-top">
        <div class="footer-brand">
          <span class="brand"><span class="brand-mark"></span> THE EXPERT HUB</span>
          <p>A premium digital agency engineering custom web applications, mobile apps, software platforms, and automations based in Chennai.</p>
          <div class="duns-badge" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(212, 255, 61, 0.06); border: 1px solid rgba(212, 255, 61, 0.22); border-radius: 6px; padding: 6px 12px; margin: 12px 0 10px; font-family: var(--font-mono, monospace); font-size: 11px; color: var(--fg, #f4f4f0); letter-spacing: 0.02em;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#D4FF3D" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
            <span><strong>D-U-N-S&reg; Registered&trade;</strong> &middot; <span style="color: #D4FF3D; font-weight: 700;">30-704-2520</span></span>
          </div>
          <span class="label">Studio &middot; 2021-2026</span>
        </div>
        <div>
          <h4>Platform</h4>
          <ul>
            <li><a href="index.php">Index</a></li>
            <li><a href="solutions.php">Solutions</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="pay.php">Payment Portal</a></li>
          </ul>
        </div>
        <div>
          <h4>Support</h4>
          <ul>
            <li><a href="contact.php">Help Desk</a></li>
            <li><a href="privacy.php">Privacy Policy</a></li>
            <li><a href="terms.php">Terms of Service</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <span>&copy; 2026 THE EXPERT HUB &middot; D-U-N-S&reg; 30-704-2520 &middot; Engineered for performance.</span>
      </div>
    </div>
  </footer>

  <script src="assets/js/site.js" defer></script>
  <script src="assets/js/chatbot.js" defer></script>

  <script>
    const amountInput = document.getElementById('f-amount');
    const displayTotal = document.getElementById('display-total');
    const btnText = document.getElementById('btn-text');

    if (amountInput && displayTotal) {
      amountInput.addEventListener('input', () => {
        const val = parseFloat(amountInput.value) || 0;
        const formatted = '₹' + val.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        displayTotal.innerText = formatted;
        if (btnText) {
          btnText.innerText = 'Proceed to Pay ' + formatted;
        }
      });
    }

    const payForm = document.getElementById('payment-form');
    if (payForm) {
      payForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('btn-pay');
        btn.disabled = true;
        btnText.innerText = 'Creating Secure Order...';

        const payload = {
          payment_id: document.getElementById('f-payment-id').value,
          invoice_number: document.getElementById('f-invoice').value,
          customer_name: document.getElementById('f-name').value,
          customer_email: document.getElementById('f-email').value,
          customer_phone: document.getElementById('f-phone').value,
          description: document.getElementById('f-desc').value,
          amount: document.getElementById('f-amount').value
        };

        try {
          const res = await fetch('api/payment/create_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          });
          const order = await res.json();

          if (!order.success) {
            alert(order.message || 'Error generating Razorpay order.');
            btn.disabled = false;
            btnText.innerText = 'Proceed to Pay';
            return;
          }

          // Open Razorpay Standard Checkout modal
          const options = {
            key: order.key_id,
            amount: order.amount,
            currency: 'INR',
            name: 'THE EXPERT HUB',
            description: payload.description,
            image: 'https://tehub.in/assets/img/logo.png',
            order_id: order.order_id,
            prefill: {
              name: payload.customer_name,
              email: payload.customer_email,
              contact: payload.customer_phone
            },
            notes: {
              invoice_number: payload.invoice_number,
              internal_id: order.internal_id
            },
            theme: { color: '#D4FF3D' },
            handler: async function (response) {
              btnText.innerText = 'Verifying Transaction...';
              try {
                const verifyRes = await fetch('api/payment/verify.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({
                    payment_id: order.internal_id,
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature
                  })
                });
                const verifyData = await verifyRes.json();
                if (verifyData.success) {
                  window.location.href = 'pay_success.php?id=' + encodeURIComponent(order.internal_id);
                } else {
                  window.location.href = 'pay_failed.php?id=' + encodeURIComponent(order.internal_id) + '&msg=' + encodeURIComponent(verifyData.message || 'Verification failed');
                }
              } catch (verr) {
                window.location.href = 'pay_success.php?id=' + encodeURIComponent(order.internal_id);
              }
            },
            modal: {
              ondismiss: function () {
                btn.disabled = false;
                btnText.innerText = 'Proceed to Pay';
              }
            }
          };

          const rzp = new Razorpay(options);
          rzp.open();
        } catch (err) {
          alert('Network connection error. Please check your internet and try again.');
          btn.disabled = false;
          btnText.innerText = 'Proceed to Pay';
        }
      });
    }
  </script>
</body>
</html>

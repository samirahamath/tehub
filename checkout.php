<?php
require_once 'db.php';

if (empty($_SESSION['client_user'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: client_login.php');
    exit;
}

$client = $_SESSION['client_user'];
$service_id = intval($_GET['service_id'] ?? 0);

$service = null;
if ($pdo && $service_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ? AND status='active'");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();
}

if (!$service) {
    header('Location: services.php');
    exit;
}

$msg = '';
$error = '';

$provider = get_setting('payment_gateway_provider', 'stripe');
$stripe_pub = get_setting('stripe_public_key');
$paypal_id = get_setting('paypal_client_id');
$upi_id = get_setting('upi_id');
$bank_details = get_setting('bank_details');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? 'Gateway';
    $payment_id = 'PAY-' . strtoupper(substr(md5(uniqid()), 0, 10));

    if ($pdo) {
        $stmtIns = $pdo->prepare("INSERT INTO orders (client_id, service_id, amount, status, payment_method, payment_id) VALUES (?, ?, ?, 'Active', ?, ?)");
        if ($stmtIns->execute([$client['id'], $service['id'], $service['price'], $payment_method, $payment_id])) {
            header('Location: client_dashboard.php?success=1');
            exit;
        } else {
            $error = 'Failed to process checkout. Please try again.';
        }
    } else {
        $error = 'Database connection issue.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout · <?= htmlspecialchars($service['title']) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="assets/css/styles.css" />
  <style>
    body {
      background-color: #0a0b0d;
      color: #f0f4f8;
      font-family: 'Inter Tight', sans-serif;
      padding: 40px 20px;
    }
    .checkout-container {
      max-width: 800px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
    }
    @media (max-width: 768px) {
      .checkout-container { grid-template-columns: 1fr; }
    }
    .box {
      background: rgba(18, 20, 26, 0.9);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 14px;
      padding: 28px;
    }
    .price-tag {
      font-size: 32px;
      font-weight: 800;
      color: #a3e635;
      margin: 15px 0;
    }
    .btn-pay {
      width: 100%;
      padding: 16px;
      background: #a3e635;
      color: #0a0b0d;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 800;
      cursor: pointer;
      margin-top: 20px;
      transition: all 0.2s;
    }
    .btn-pay:hover {
      background: #bef264;
      transform: translateY(-2px);
    }
    .gateway-info {
      background: rgba(255,255,255,0.04);
      padding: 16px;
      border-radius: 8px;
      border: 1px dashed rgba(255,255,255,0.2);
      margin-top: 15px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="checkout-container">
    <div class="box">
      <span style="font-family:'Geist Mono', monospace; color:#a3e635; font-size:12px;">ORDER SUMMARY</span>
      <h2 style="margin:10px 0;"><?= htmlspecialchars($service['title']) ?></h2>
      <p style="color:#8a94a6; font-size:14px;"><?= htmlspecialchars($service['short_description']) ?></p>
      <hr style="border-color:rgba(255,255,255,0.1); margin:20px 0;" />
      
      <div style="font-size:13px; color:#a0aec0;">Duration Target: <strong><?= htmlspecialchars($service['duration_info']) ?></strong></div>
      <div class="price-tag">$<?= number_format($service['price'], 2) ?></div>
      
      <div style="margin-top: 20px;">
        <strong style="font-size:13px; color:#a0aec0; display:block; margin-bottom:8px;">Included Deliverables:</strong>
        <ul style="padding-left:18px; color:#cbd5e1; font-size:13px; line-height:1.6;">
          <?php 
            $feats = explode("\n", $service['features']);
            foreach($feats as $ft):
              if(trim($ft) !== ''):
          ?>
            <li><?= htmlspecialchars(trim($ft)) ?></li>
          <?php endif; endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="box">
      <h3 style="margin-top:0;">Payment Gateway</h3>
      <p style="color:#8a94a6; font-size:14px;">Complete your purchase securely. Logged in as <strong><?= htmlspecialchars($client['name']) ?></strong>.</p>
      
      <form method="POST">
        <input type="hidden" name="payment_method" value="<?= htmlspecialchars($provider) ?>" />
        
        <?php if($provider === 'stripe'): ?>
          <div class="gateway-info">
            <strong>💳 Stripe Payment Gateway Enabled</strong><br/>
            <span style="color:#a0aec0; font-size:12px;">Public Key: <?= $stripe_pub ? htmlspecialchars(substr($stripe_pub, 0, 12)).'...' : 'Configured by Admin' ?></span>
            <p style="margin:10px 0 0; color:#cbd5e1;">Click below to authorize and activate service.</p>
          </div>
        <?php elseif($provider === 'paypal'): ?>
          <div class="gateway-info">
            <strong>🅿️ PayPal Express Gateway Enabled</strong><br/>
            <span style="color:#a0aec0; font-size:12px;">Client ID: <?= $paypal_id ? htmlspecialchars(substr($paypal_id,0,10)).'...' : 'Configured by Admin' ?></span>
          </div>
        <?php else: ?>
          <div class="gateway-info">
            <strong>🏦 Direct Bank / UPI Transfer Details</strong><br/>
            <?php if($upi_id): ?>
              <div style="margin-top:5px;"><strong>UPI ID:</strong> <?= htmlspecialchars($upi_id) ?></div>
            <?php endif; ?>
            <?php if($bank_details): ?>
              <div style="margin-top:5px; white-space:pre-wrap; font-size:13px; color:#cbd5e1;"><?= htmlspecialchars($bank_details) ?></div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <button type="submit" class="btn-pay">Confirm &amp; Pay $<?= number_format($service['price'], 2) ?></button>
      </form>
      
      <div style="text-align:center; margin-top:20px;">
        <a href="services.php" style="color:#8a94a6; text-decoration:none; font-size:13px;">Cancel and Return</a>
      </div>
    </div>
  </div>
</body>
</html>

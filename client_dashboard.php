<?php
require_once 'db.php';

if (empty($_SESSION['client_user'])) {
    header('Location: client_login.php');
    exit;
}

$client = $_SESSION['client_user'];
$success_msg = isset($_GET['success']) ? 'Your order was successfully placed! Our technical team will reach out within 24 hours.' : '';

// Fetch client purchases
$my_orders = [];
if ($pdo) {
    $stmt = $pdo->prepare("SELECT o.*, s.title as service_title, s.duration_info 
                           FROM orders o 
                           LEFT JOIN services s ON o.service_id = s.id 
                           WHERE o.client_id = ? 
                           ORDER BY o.id DESC");
    $stmt->execute([$client['id']]);
    $my_orders = $stmt->fetchAll();
}

// Fetch all available active services
$all_services = [];
if ($pdo) {
    $all_services = $pdo->query("SELECT * FROM services WHERE status='active' ORDER BY id ASC")->fetchAll();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Client Portal · THE EXPERT HUB Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="assets/css/styles.css" />
  <style>
    body {
      background-color: #0a0b0d;
      color: #f0f4f8;
      font-family: 'Inter Tight', sans-serif;
      margin: 0;
      padding-bottom: 50px;
    }
    .client-header {
      background: rgba(18, 20, 26, 0.95);
      border-bottom: 1px solid rgba(255,255,255,0.12);
      padding: 18px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .client-brand {
      font-weight: 800;
      font-size: 20px;
      color: #a3e635;
      text-decoration: none;
      letter-spacing: 1.5px;
    }
    .container {
      max-width: 1100px;
      margin: 30px auto;
      padding: 0 20px;
    }
    .welcome-card {
      background: linear-gradient(135deg, rgba(163, 230, 53, 0.1) 0%, rgba(18, 20, 26, 0.9) 100%);
      border: 1px solid rgba(163, 230, 53, 0.3);
      border-radius: 14px;
      padding: 28px;
      margin-bottom: 30px;
    }
    .welcome-card h1 {
      margin: 0 0 8px;
      font-size: 24px;
    }
    .welcome-card p {
      margin: 0;
      color: #a0aec0;
      font-size: 14px;
    }
    .alert-success {
      background: rgba(163, 230, 53, 0.15);
      border: 1px solid rgba(163, 230, 53, 0.4);
      color: #a3e635;
      padding: 16px;
      border-radius: 8px;
      margin-bottom: 25px;
      font-weight: 500;
    }
    .section-title {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 18px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 24px;
      margin-bottom: 40px;
    }
    .svc-card {
      background: rgba(18, 20, 26, 0.9);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 12px;
      padding: 24px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: transform 0.2s;
    }
    .svc-card:hover {
      transform: translateY(-3px);
      border-color: rgba(163, 230, 53, 0.4);
    }
    .svc-price {
      font-size: 26px;
      font-weight: 800;
      color: #a3e635;
      margin: 12px 0;
    }
    .btn-buy {
      display: block;
      text-align: center;
      background: #a3e635;
      color: #0a0b0d;
      padding: 12px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 700;
      margin-top: 15px;
    }
    .table-custom {
      width: 100%;
      border-collapse: collapse;
      background: rgba(18, 20, 26, 0.9);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 12px;
      overflow: hidden;
      margin-bottom: 40px;
    }
    .table-custom th, .table-custom td {
      padding: 16px 20px;
      text-align: left;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      font-size: 14px;
    }
    .table-custom th {
      background: rgba(255,255,255,0.03);
      color: #8a94a6;
    }
    .badge-status {
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
    }
    .status-Pending { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
    .status-Active { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
    .status-Completed { background: rgba(163, 230, 53, 0.2); color: #a3e635; }
  </style>
</head>
<body>

  <header class="client-header">
    <a href="client_dashboard.php" class="client-brand">THE EXPERT HUB CLIENT PORTAL</a>
    <div style="display:flex; align-items:center; gap:20px;">
      <a href="services.php" style="color:#a0aec0; text-decoration:none; font-size:14px;">Browse Services</a>
      <span style="font-size:13px; color:#8a94a6;"><?= htmlspecialchars($client['name']) ?> (<?= htmlspecialchars($client['email']) ?>)</span>
      <a href="client_logout.php" style="color:#ef4444; text-decoration:none; font-size:13px; font-weight:600;">Sign Out</a>
    </div>
  </header>

  <main class="container">
    <?php if($success_msg): ?>
      <div class="alert-success"><?= htmlspecialchars($success_msg) ?></div>
    <?php endif; ?>

    <div class="welcome-card">
      <h1>Welcome back, <?= htmlspecialchars($client['name']) ?> 👋</h1>
      <p>Manage your active development briefs, order custom upgrades, and review project status.</p>
    </div>

    <!-- SECTION 1: PURCHASES -->
    <div class="section-title">My Purchases &amp; Active Services</div>
    <table class="table-custom">
      <thead>
        <tr>
          <th>Order Reference</th>
          <th>Service Package</th>
          <th>Amount Paid</th>
          <th>Order Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($my_orders)): ?>
          <tr>
            <td colspan="5" style="text-align:center; color:#8a94a6; padding:35px;">
              You have not ordered any services yet. Check out available packages below!
            </td>
          </tr>
        <?php else: ?>
          <?php foreach($my_orders as $ord): ?>
            <tr>
              <td><strong>#ORD-<?= str_pad($ord['id'], 4, '0', STR_PAD_LEFT) ?></strong><br/><small style="color:#8a94a6;"><?= htmlspecialchars($ord['payment_id'] ?? '') ?></small></td>
              <td><?= htmlspecialchars($ord['service_title']) ?></td>
              <td><strong>₹<?= number_format($ord['amount'], 2) ?></strong></td>
              <td><?= date('M d, Y', strtotime($ord['created_at'])) ?></td>
              <td><span class="badge-status status-<?= $ord['status'] ?>"><?= $ord['status'] ?></span></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- SECTION 2: AVAILABLE SERVICES -->
    <div class="section-title">Available Service Packages</div>
    <div class="card-grid">
      <?php foreach($all_services as $s): ?>
        <div class="svc-card">
          <div>
            <h3 style="margin:0 0 8px; font-size:18px;"><?= htmlspecialchars($s['title']) ?></h3>
            <p style="color:#8a94a6; font-size:13px; margin:0 0 15px; min-height:40px;"><?= htmlspecialchars($s['short_description']) ?></p>
            <div class="svc-price"><?= htmlspecialchars($s['price_prefix']) ?><?= number_format($s['price']) ?></div>
            <div style="font-size:12px; color:#cbd5e1; margin-bottom:15px;">Target: <?= htmlspecialchars($s['duration_info']) ?></div>
          </div>
          <a href="checkout.php?service_id=<?= $s['id'] ?>" class="btn-buy">Order <?= htmlspecialchars($s['title']) ?></a>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

</body>
</html>

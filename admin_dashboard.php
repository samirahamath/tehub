<?php
require_once 'db.php';

if (empty($_SESSION['admin_user'])) {
    header('Location: admin_login.php');
    exit;
}

$msg = '';
$error = '';
$tab = $_GET['tab'] ?? 'services';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_service') {
        $id = intval($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $short_description = trim($_POST['short_description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $price_prefix = trim($_POST['price_prefix'] ?? 'From $');
        $duration_info = trim($_POST['duration_info'] ?? '');
        $features = trim($_POST['features'] ?? '');
        $status = $_POST['status'] ?? 'active';

        if ($pdo) {
            if ($id > 0) {
                $stmt = $pdo->prepare("UPDATE services SET title=?, short_description=?, price=?, price_prefix=?, duration_info=?, features=?, status=? WHERE id=?");
                $stmt->execute([$title, $short_description, $price, $price_prefix, $duration_info, $features, $status, $id]);
                $msg = "Service updated successfully!";
            } else {
                $stmt = $pdo->prepare("INSERT INTO services (title, short_description, price, price_prefix, duration_info, features, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $short_description, $price, $price_prefix, $duration_info, $features, $status]);
                $msg = "New service created successfully!";
            }
        }
    } elseif ($action === 'delete_service') {
        $id = intval($_POST['id'] ?? 0);
        if ($pdo && $id > 0) {
            $stmt = $pdo->prepare("DELETE FROM services WHERE id=?");
            $stmt->execute([$id]);
            $msg = "Service deleted!";
        }
    } elseif ($action === 'update_order_status') {
        $order_id = intval($_POST['order_id'] ?? 0);
        $status = $_POST['status'] ?? 'Pending';
        if ($pdo && $order_id > 0) {
            $stmt = $pdo->prepare("UPDATE orders SET status=? WHERE id=?");
            $stmt->execute([$status, $order_id]);
            $msg = "Order status updated to '{$status}'!";
        }
    } elseif ($action === 'save_payment_settings') {
        set_setting('payment_gateway_provider', $_POST['payment_gateway_provider'] ?? 'stripe');
        set_setting('stripe_public_key', $_POST['stripe_public_key'] ?? '');
        set_setting('stripe_secret_key', $_POST['stripe_secret_key'] ?? '');
        set_setting('paypal_client_id', $_POST['paypal_client_id'] ?? '');
        set_setting('upi_id', $_POST['upi_id'] ?? '');
        set_setting('bank_details', $_POST['bank_details'] ?? '');
        $msg = "Payment Gateway settings updated!";
        $tab = 'payment';
    } elseif ($action === 'save_smtp_settings') {
        set_setting('smtp_host', $_POST['smtp_host'] ?? '');
        set_setting('smtp_port', $_POST['smtp_port'] ?? '587');
        set_setting('smtp_user', $_POST['smtp_user'] ?? '');
        set_setting('smtp_pass', $_POST['smtp_pass'] ?? '');
        set_setting('smtp_crypto', $_POST['smtp_crypto'] ?? 'tls');
        set_setting('smtp_from_email', $_POST['smtp_from_email'] ?? '');
        set_setting('smtp_from_name', $_POST['smtp_from_name'] ?? '');
        $msg = "SMTP Mailer settings updated!";
        $tab = 'smtp';
    }
}

// Fetch Services
$services_list = [];
if ($pdo) {
    $services_list = $pdo->query("SELECT * FROM services ORDER BY id ASC")->fetchAll();
}

// Fetch Orders
$orders_list = [];
if ($pdo) {
    $stmtO = $pdo->query("SELECT o.*, u.name as client_name, u.email as client_email, s.title as service_title 
                          FROM orders o 
                          LEFT JOIN users u ON o.client_id = u.id 
                          LEFT JOIN services s ON o.service_id = s.id 
                          ORDER BY o.id DESC");
    $orders_list = $stmtO->fetchAll();
}

// Fetch Clients
$clients_list = [];
if ($pdo) {
    $clients_list = $pdo->query("SELECT * FROM users WHERE role='client' ORDER BY id DESC")->fetchAll();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard · TEHUB Management</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="assets/css/styles.css" />
  <style>
    :root {
      --bg-dark: #0a0b0d;
      --panel-bg: rgba(18, 20, 26, 0.95);
      --accent: #a3e635;
      --border-color: rgba(255,255,255,0.12);
    }
    body {
      background-color: var(--bg-dark);
      color: #f0f4f8;
      font-family: 'Inter Tight', sans-serif;
      margin: 0;
      padding-bottom: 50px;
    }
    .admin-header {
      background: rgba(13, 15, 20, 0.9);
      border-bottom: 1px solid var(--border-color);
      padding: 16px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .admin-brand {
      font-weight: 800;
      font-size: 20px;
      letter-spacing: 1.5px;
      color: var(--accent);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .badge-admin {
      background: rgba(163, 230, 53, 0.15);
      color: var(--accent);
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 11px;
      font-family: 'Geist Mono', monospace;
      border: 1px solid rgba(163, 230, 53, 0.3);
    }
    .admin-nav {
      display: flex;
      gap: 20px;
    }
    .admin-nav a {
      color: #a0aec0;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      padding: 8px 12px;
      border-radius: 6px;
      transition: all 0.2s;
    }
    .admin-nav a:hover, .admin-nav a.active {
      color: #fff;
      background: rgba(255,255,255,0.08);
    }
    .admin-container {
      max-width: 1200px;
      margin: 30px auto;
      padding: 0 20px;
    }
    .panel {
      background: var(--panel-bg);
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 28px;
      margin-bottom: 30px;
    }
    .panel-title {
      font-size: 20px;
      font-weight: 700;
      margin-top: 0;
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .alert-success {
      background: rgba(163, 230, 53, 0.15);
      border: 1px solid rgba(163, 230, 53, 0.3);
      color: var(--accent);
      padding: 14px;
      border-radius: 8px;
      margin-bottom: 25px;
    }
    .table-custom {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    .table-custom th, .table-custom td {
      padding: 14px 16px;
      text-align: left;
      border-bottom: 1px solid var(--border-color);
      font-size: 14px;
    }
    .table-custom th {
      color: #8a94a6;
      font-weight: 600;
      background: rgba(255,255,255,0.02);
    }
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    .form-group-full {
      grid-column: span 2;
    }
    .form-label {
      display: block;
      font-size: 13px;
      color: #a0aec0;
      margin-bottom: 6px;
    }
    .form-input, .form-textarea, .form-select {
      width: 100%;
      padding: 10px 14px;
      background: rgba(255,255,255,0.05);
      border: 1px solid var(--border-color);
      border-radius: 6px;
      color: #fff;
      font-family: inherit;
      font-size: 14px;
      box-sizing: border-box;
    }
    .form-textarea {
      resize: vertical;
      min-height: 90px;
    }
    .btn-action {
      background: var(--accent);
      color: #0a0b0d;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      font-weight: 700;
      cursor: pointer;
      font-size: 14px;
      transition: opacity 0.2s;
    }
    .btn-action:hover {
      opacity: 0.9;
    }
    .btn-sm {
      padding: 6px 12px;
      font-size: 12px;
    }
    .btn-danger {
      background: #ef4444;
      color: #fff;
    }
    .badge-status {
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
      display: inline-block;
    }
    .status-Pending { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
    .status-Active { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
    .status-Completed { background: rgba(163, 230, 53, 0.2); color: var(--accent); }
    .status-Cancelled { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }
    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.8);
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }
    .modal-content {
      background: #12141a;
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 30px;
      width: 100%;
      max-width: 600px;
      max-height: 90vh;
      overflow-y: auto;
    }
  </style>
</head>
<body>

  <header class="admin-header">
    <a href="admin_dashboard.php" class="admin-brand">
      TEHUB <span class="badge-admin">CONTROL PANEL</span>
    </a>
    <nav class="admin-nav">
      <a href="?tab=services" class="<?= $tab==='services'?'active':'' ?>">Services &amp; Pricing</a>
      <a href="?tab=orders" class="<?= $tab==='orders'?'active':'' ?>">Orders &amp; Purchases</a>
      <a href="?tab=clients" class="<?= $tab==='clients'?'active':'' ?>">Clients</a>
      <a href="?tab=payment" class="<?= $tab==='payment'?'active':'' ?>">Payment Gateway</a>
      <a href="?tab=smtp" class="<?= $tab==='smtp'?'active':'' ?>">SMTP Settings</a>
    </nav>
    <div>
      <span style="font-size: 13px; color: #a0aec0; margin-right: 15px;"><?= htmlspecialchars($_SESSION['admin_user']['email']) ?></span>
      <a href="admin_logout.php" style="color: #ef4444; text-decoration: none; font-size: 13px; font-weight: 600;">Sign Out</a>
    </div>
  </header>

  <main class="admin-container">
    <?php if(!empty($msg)): ?>
      <div class="alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- TAB 1: SERVICES & PRICING -->
    <?php if($tab === 'services'): ?>
      <div class="panel">
        <div class="panel-title">
          <span>Manage Services &amp; Pricing</span>
          <button onclick="openServiceModal()" class="btn-action btn-sm">+ Add New Service</button>
        </div>
        <p style="color: #8a94a6; font-size: 14px; margin-bottom: 20px;">
          Changes made here instantly update the rates displayed on the main <a href="services.php" target="_blank" style="color: var(--accent);">Services Page</a>.
        </p>

        <table class="table-custom">
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Starting Price</th>
              <th>Duration</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($services_list as $s): ?>
              <tr>
                <td>#<?= $s['id'] ?></td>
                <td><strong><?= htmlspecialchars($s['title']) ?></strong></td>
                <td><span style="color: var(--accent); font-weight: 700;"><?= htmlspecialchars($s['price_prefix']) ?><?= number_format($s['price']) ?></span></td>
                <td><?= htmlspecialchars($s['duration_info']) ?></td>
                <td>
                  <span class="badge-status status-<?= ucfirst($s['status']) === 'Active'?'Completed':'Cancelled' ?>">
                    <?= ucfirst($s['status']) ?>
                  </span>
                </td>
                <td>
                  <button onclick='editService(<?= json_encode($s, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)' class="btn-action btn-sm">Edit</button>
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this service?');">
                    <input type="hidden" name="action" value="delete_service" />
                    <input type="hidden" name="id" value="<?= $s['id'] ?>" />
                    <button type="submit" class="btn-action btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <!-- TAB 2: ORDERS & PURCHASES -->
    <?php if($tab === 'orders'): ?>
      <div class="panel">
        <div class="panel-title">Client Orders &amp; Subscriptions</div>
        <table class="table-custom">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Client</th>
              <th>Service</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($orders_list)): ?>
              <tr><td colspan="7" style="text-align:center; color:#8a94a6; padding: 30px;">No orders recorded yet.</td></tr>
            <?php else: ?>
              <?php foreach($orders_list as $o): ?>
                <tr>
                  <td>#ORD-<?= str_pad($o['id'], 4, '0', STR_PAD_LEFT) ?></td>
                  <td>
                    <strong><?= htmlspecialchars($o['client_name'] ?? 'Guest/Unknown') ?></strong><br/>
                    <small style="color: #8a94a6;"><?= htmlspecialchars($o['client_email'] ?? '') ?></small>
                  </td>
                  <td><?= htmlspecialchars($o['service_title'] ?? 'Custom Package') ?></td>
                  <td><strong>$<?= number_format($o['amount'], 2) ?></strong></td>
                  <td><?= date('M d, Y', strtotime($o['created_at'])) ?></td>
                  <td>
                    <span class="badge-status status-<?= $o['status'] ?>"><?= $o['status'] ?></span>
                  </td>
                  <td>
                    <form method="POST" style="display:flex; gap:6px;">
                      <input type="hidden" name="action" value="update_order_status" />
                      <input type="hidden" name="order_id" value="<?= $o['id'] ?>" />
                      <select name="status" class="form-select" style="padding:4px 8px; font-size:12px;">
                        <option value="Pending" <?= $o['status']==='Pending'?'selected':'' ?>>Pending</option>
                        <option value="Active" <?= $o['status']==='Active'?'selected':'' ?>>Active</option>
                        <option value="Completed" <?= $o['status']==='Completed'?'selected':'' ?>>Completed</option>
                        <option value="Cancelled" <?= $o['status']==='Cancelled'?'selected':'' ?>>Cancelled</option>
                      </select>
                      <button type="submit" class="btn-action btn-sm">Save</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <!-- TAB 3: CLIENTS -->
    <?php if($tab === 'clients'): ?>
      <div class="panel">
        <div class="panel-title">Registered Clients</div>
        <table class="table-custom">
          <thead>
            <tr>
              <th>Client ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Registered Date</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($clients_list)): ?>
              <tr><td colspan="4" style="text-align:center; color:#8a94a6; padding: 30px;">No registered clients found.</td></tr>
            <?php else: ?>
              <?php foreach($clients_list as $c): ?>
                <tr>
                  <td>#CL-<?= $c['id'] ?></td>
                  <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
                  <td><?= htmlspecialchars($c['email']) ?></td>
                  <td><?= date('M d, Y H:i', strtotime($c['created_at'])) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <!-- TAB 4: PAYMENT GATEWAY -->
    <?php if($tab === 'payment'): ?>
      <div class="panel">
        <div class="panel-title">Payment Gateway Settings</div>
        <form method="POST">
          <input type="hidden" name="action" value="save_payment_settings" />
          <div class="form-grid">
            <div class="form-group-full">
              <label class="form-label">Active Gateway Provider</label>
              <select name="payment_gateway_provider" class="form-select">
                <option value="stripe" <?= get_setting('payment_gateway_provider')==='stripe'?'selected':'' ?>>Stripe Payments</option>
                <option value="paypal" <?= get_setting('payment_gateway_provider')==='paypal'?'selected':'' ?>>PayPal Express</option>
                <option value="bank" <?= get_setting('payment_gateway_provider')==='bank'?'selected':'' ?>>Manual Wire Transfer / UPI</option>
              </select>
            </div>
            <div>
              <label class="form-label">Stripe Public Key</label>
              <input type="text" name="stripe_public_key" class="form-input" value="<?= htmlspecialchars(get_setting('stripe_public_key')) ?>" placeholder="pk_test_..." />
            </div>
            <div>
              <label class="form-label">Stripe Secret Key</label>
              <input type="password" name="stripe_secret_key" class="form-input" value="<?= htmlspecialchars(get_setting('stripe_secret_key')) ?>" placeholder="sk_test_..." />
            </div>
            <div class="form-group-full">
              <label class="form-label">PayPal Client ID</label>
              <input type="text" name="paypal_client_id" class="form-input" value="<?= htmlspecialchars(get_setting('paypal_client_id')) ?>" placeholder="Client ID string..." />
            </div>
            <div>
              <label class="form-label">UPI Payment ID (Optional)</label>
              <input type="text" name="upi_id" class="form-input" value="<?= htmlspecialchars(get_setting('upi_id')) ?>" placeholder="tehub@upi" />
            </div>
            <div>
              <label class="form-label">Bank Details / Instructions</label>
              <textarea name="bank_details" class="form-textarea" placeholder="Account Number, Swift Code, Bank Name..."><?= htmlspecialchars(get_setting('bank_details')) ?></textarea>
            </div>
          </div>
          <div style="margin-top:20px;">
            <button type="submit" class="btn-action">Save Payment Gateway Settings</button>
          </div>
        </form>
      </div>
    <?php endif; ?>

    <!-- TAB 5: SMTP SETTINGS -->
    <?php if($tab === 'smtp'): ?>
      <div class="panel">
        <div class="panel-title">SMTP Mailer Configuration</div>
        <form method="POST">
          <input type="hidden" name="action" value="save_smtp_settings" />
          <div class="form-grid">
            <div>
              <label class="form-label">SMTP Host</label>
              <input type="text" name="smtp_host" class="form-input" value="<?= htmlspecialchars(get_setting('smtp_host', 'smtp.gmail.com')) ?>" placeholder="smtp.gmail.com" />
            </div>
            <div>
              <label class="form-label">SMTP Port</label>
              <input type="text" name="smtp_port" class="form-input" value="<?= htmlspecialchars(get_setting('smtp_port', '587')) ?>" placeholder="587" />
            </div>
            <div>
              <label class="form-label">Encryption Protocol</label>
              <select name="smtp_crypto" class="form-select">
                <option value="tls" <?= get_setting('smtp_crypto','tls')==='tls'?'selected':'' ?>>TLS</option>
                <option value="ssl" <?= get_setting('smtp_crypto')==='ssl'?'selected':'' ?>>SSL</option>
                <option value="none" <?= get_setting('smtp_crypto')==='none'?'selected':'' ?>>None</option>
              </select>
            </div>
            <div>
              <label class="form-label">SMTP Username</label>
              <input type="text" name="smtp_user" class="form-input" value="<?= htmlspecialchars(get_setting('smtp_user')) ?>" placeholder="user@domain.com" />
            </div>
            <div>
              <label class="form-label">SMTP Password</label>
              <input type="password" name="smtp_pass" class="form-input" value="<?= htmlspecialchars(get_setting('smtp_pass')) ?>" placeholder="••••••••" />
            </div>
            <div>
              <label class="form-label">Sender From Email</label>
              <input type="email" name="smtp_from_email" class="form-input" value="<?= htmlspecialchars(get_setting('smtp_from_email', 'noreply@tehub.com')) ?>" placeholder="noreply@tehub.com" />
            </div>
          </div>
          <div style="margin-top:20px;">
            <button type="submit" class="btn-action">Save SMTP Configuration</button>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </main>

  <!-- Service Modal -->
  <div id="serviceModal" class="modal">
    <div class="modal-content">
      <h3 style="margin-top:0;" id="modalTitle">Add Service Tier</h3>
      <form method="POST">
        <input type="hidden" name="action" value="save_service" />
        <input type="hidden" name="id" id="svc_id" value="0" />
        <div class="form-group" style="margin-bottom:12px;">
          <label class="form-label">Service Title</label>
          <input type="text" name="title" id="svc_title" class="form-input" required placeholder="MVP & Automation" />
        </div>
        <div class="form-group" style="margin-bottom:12px;">
          <label class="form-label">Short Description</label>
          <textarea name="short_description" id="svc_short_description" class="form-textarea" placeholder="Describe the target audience and purpose..."></textarea>
        </div>
        <div style="display:flex; gap:12px; margin-bottom:12px;">
          <div style="flex:1;">
            <label class="form-label">Price ($)</label>
            <input type="number" step="0.01" name="price" id="svc_price" class="form-input" required placeholder="15000" />
          </div>
          <div style="flex:1;">
            <label class="form-label">Price Prefix</label>
            <input type="text" name="price_prefix" id="svc_price_prefix" class="form-input" value="From $" />
          </div>
        </div>
        <div style="display:flex; gap:12px; margin-bottom:12px;">
          <div style="flex:1;">
            <label class="form-label">Duration Info</label>
            <input type="text" name="duration_info" id="svc_duration_info" class="form-input" placeholder="2 — 4 weeks" />
          </div>
          <div style="flex:1;">
            <label class="form-label">Status</label>
            <select name="status" id="svc_status" class="form-select">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="form-group" style="margin-bottom:20px;">
          <label class="form-label">Features List (1 item per line)</label>
          <textarea name="features" id="svc_features" class="form-textarea" style="min-height:120px;" placeholder="1 Lead architect&#10;Clean Next.js frontend&#10;3-month warranty"></textarea>
        </div>
        <div style="display:flex; justify-content:flex-end; gap:10px;">
          <button type="button" onclick="closeServiceModal()" class="btn-action btn-danger" style="background:#4b5563;">Cancel</button>
          <button type="submit" class="btn-action">Save Service</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openServiceModal() {
      document.getElementById('modalTitle').innerText = "Add Service Tier";
      document.getElementById('svc_id').value = "0";
      document.getElementById('svc_title').value = "";
      document.getElementById('svc_short_description').value = "";
      document.getElementById('svc_price').value = "";
      document.getElementById('svc_price_prefix').value = "From $";
      document.getElementById('svc_duration_info').value = "";
      document.getElementById('svc_features').value = "";
      document.getElementById('svc_status').value = "active";
      document.getElementById('serviceModal').style.display = 'flex';
    }
    function closeServiceModal() {
      document.getElementById('serviceModal').style.display = 'none';
    }
    function editService(s) {
      document.getElementById('modalTitle').innerText = "Edit Service Tier #" + s.id;
      document.getElementById('svc_id').value = s.id;
      document.getElementById('svc_title').value = s.title;
      document.getElementById('svc_short_description').value = s.short_description;
      document.getElementById('svc_price').value = s.price;
      document.getElementById('svc_price_prefix').value = s.price_prefix;
      document.getElementById('svc_duration_info').value = s.duration_info;
      document.getElementById('svc_features').value = s.features;
      document.getElementById('svc_status').value = s.status;
      document.getElementById('serviceModal').style.display = 'flex';
    }
  </script>
</body>
</html>

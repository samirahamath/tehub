<?php
session_start();
require_once __DIR__ . '/config.php';
date_default_timezone_set('Asia/Kolkata');

$auth = false;
$pass_error = '';
$admin_pass = defined('ADMIN_PASSWORD') ? ADMIN_PASSWORD : 'Inayah@62';

if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_pass) {
        $_SESSION['pay_admin_auth'] = true;
    } else {
        $pass_error = 'Incorrect admin password. Please try again.';
    }
}

if (isset($_GET['logout'])) {
    unset($_SESSION['pay_admin_auth']);
    header('Location: pay_admin.php');
    exit;
}

if (!empty($_SESSION['pay_admin_auth'])) {
    $auth = true;
}

$payments_file = __DIR__ . '/payments.json';
$payments = [];
if (file_exists($payments_file)) {
    $payments = @json_decode(@file_get_contents($payments_file), true) ?? [];
}

// Handle deletion
if ($auth && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $del_id = $_POST['delete_id'] ?? '';
    $payments = array_values(array_filter($payments, function ($item) use ($del_id) {
        return ($item['id'] ?? '') !== $del_id;
    }));
    @file_put_contents($payments_file, json_encode($payments, JSON_PRETTY_PRINT));
    header('Location: pay_admin.php?deleted=1');
    exit;
}

// Handle CSV Export
if ($auth && isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=tehub_payments_' . date('Ymd_His') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Invoice Number', 'Customer Name', 'Phone', 'Email', 'Amount (INR)', 'Purpose', 'Status', 'Razorpay Order ID', 'Razorpay Payment ID', 'Created At', 'Paid At', 'IP']);
    foreach ($payments as $p) {
        fputcsv($output, [
            $p['id'] ?? '',
            $p['invoice_number'] ?? '',
            $p['customer_name'] ?? '',
            $p['customer_phone'] ?? '',
            $p['customer_email'] ?? '',
            $p['amount'] ?? 0,
            $p['description'] ?? '',
            $p['status'] ?? 'PENDING',
            $p['razorpay_order_id'] ?? '',
            $p['razorpay_payment_id'] ?? '',
            $p['created_at'] ?? '',
            $p['paid_at'] ?? '',
            $p['ip'] ?? ''
        ]);
    }
    fclose($output);
    exit;
}

// Calculate Dashboard Stats
$total_revenue = 0;
$paid_count = 0;
$pending_count = 0;

foreach ($payments as $p) {
    if (($p['status'] ?? '') === 'PAID') {
        $total_revenue += floatval($p['amount'] ?? 0);
        $paid_count++;
    } else {
        $pending_count++;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Admin Dashboard · THE EXPERT HUB</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <style>
    body {
      background-color: #0A0A0C;
      color: #F4F4F0;
      min-height: 100vh;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }
    .admin-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 32px 20px;
    }
    .admin-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 16px;
      margin-bottom: 28px;
      border-bottom: 1px solid var(--rule);
      padding-bottom: 20px;
    }
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
      margin-bottom: 32px;
    }
    .stat-card {
      background: var(--bg-alt, #121216);
      border: 1px solid var(--rule);
      border-radius: 14px;
      padding: 20px;
      position: relative;
    }
    .stat-card strong {
      display: block;
      font-size: 26px;
      font-family: var(--font-mono);
      color: var(--lime);
      margin-top: 4px;
    }
    .stat-card span {
      font-size: 12px;
      color: var(--fg-mute);
      text-transform: uppercase;
      font-family: var(--font-mono);
      letter-spacing: 0.05em;
    }
    .creator-box {
      background: var(--bg-alt, #121216);
      border: 1px solid rgba(212, 255, 61, 0.25);
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 32px;
    }
    .creator-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 14px;
      margin-bottom: 16px;
    }
    .creator-grid input, .creator-grid select {
      width: 100%;
      background: rgba(255,255,255,0.04);
      border: 1px solid var(--rule);
      border-radius: 8px;
      padding: 10px 12px;
      color: #fff;
      font-size: 14px;
      outline: none;
    }
    .creator-grid input:focus, .creator-grid select:focus {
      border-color: var(--lime);
    }
    .table-box {
      background: var(--bg-alt, #121216);
      border: 1px solid var(--rule);
      border-radius: 16px;
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 13.5px;
    }
    th {
      background: rgba(255,255,255,0.02);
      padding: 14px 16px;
      border-bottom: 1px solid var(--rule);
      color: var(--fg-mute);
      font-family: var(--font-mono);
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    td {
      padding: 14px 16px;
      border-bottom: 1px solid rgba(255,255,255,0.04);
      color: var(--fg);
    }
    tr:hover td {
      background: rgba(255,255,255,0.015);
    }
    .badge-paid {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      background: rgba(212, 255, 61, 0.12);
      color: var(--lime);
      border: 1px solid rgba(212, 255, 61, 0.3);
      padding: 3px 8px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 700;
      font-family: var(--font-mono);
    }
    .badge-pending {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      background: rgba(255, 193, 7, 0.12);
      color: #ffc107;
      border: 1px solid rgba(255, 193, 7, 0.3);
      padding: 3px 8px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 700;
      font-family: var(--font-mono);
    }
    .action-btn {
      background: rgba(255,255,255,0.06);
      border: 1px solid var(--rule);
      color: var(--fg);
      padding: 5px 9px;
      border-radius: 6px;
      font-size: 12px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }
    .action-btn:hover {
      background: rgba(212, 255, 61, 0.15);
      border-color: var(--lime);
      color: var(--lime);
    }
  </style>
</head>
<body>

<?php if (!$auth): ?>
  <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: var(--bg-alt, #121216); border: 1px solid rgba(212, 255, 61, 0.25); border-radius: 20px; max-width: 400px; width: 100%; padding: 36px 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.8); text-align: center;">
      <div style="font-size: 40px; margin-bottom: 12px;">🔐</div>
      <h1 style="font-size: 22px; font-weight: 700; margin-bottom: 6px;">Payment Admin</h1>
      <p style="color: var(--fg-soft); font-size: 13px; margin-bottom: 24px;">Enter your master administrative password.</p>

      <?php if ($pass_error): ?>
        <div style="background: rgba(255, 74, 74, 0.1); border: 1px solid #ff4a4a; color: #ff6b6b; padding: 10px; border-radius: 8px; font-size: 13px; margin-bottom: 16px;">
          <?= htmlspecialchars($pass_error) ?>
        </div>
      <?php endif; ?>

      <form method="POST">
        <input type="password" name="password" required autofocus placeholder="Admin password..." style="width: 100%; background: rgba(255,255,255,0.04); border: 1px solid var(--rule); border-radius: 10px; padding: 12px 14px; color: #fff; font-size: 15px; margin-bottom: 16px; outline: none;" />
        <button type="submit" class="btn btn--primary btn--md" style="width: 100%; justify-content: center;">Authenticate →</button>
      </form>
    </div>
  </div>
<?php else: ?>

  <div class="admin-container">
    <div class="admin-header">
      <div>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
          <span class="brand-mark"></span>
          <h1 style="font-size: 24px; font-weight: 700; margin: 0; font-family: var(--font-display);">Payment Management Hub</h1>
        </div>
        <p style="color: var(--fg-soft); font-size: 13px; margin: 0;">Centralized Razorpay gateway &amp; invoice tracking for all TEHUB subdomains.</p>
      </div>
      <div style="display: flex; gap: 10px; align-items: center;">
        <a href="pay_admin.php?export=csv" class="btn btn--ghost btn--sm">Export CSV 📥</a>
        <a href="visitors.php" class="btn btn--ghost btn--sm">Visitor Dashboard 📊</a>
        <a href="pay_admin.php?logout=1" class="btn btn--ghost btn--sm" style="color: #ff6b6b; border-color: rgba(255,74,74,0.3);">Logout 🚪</a>
      </div>
    </div>

    <!-- Financial Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <span>Total Revenue Collected</span>
        <strong>₹<?= number_format($total_revenue, 2) ?></strong>
      </div>
      <div class="stat-card">
        <span>Paid Transactions</span>
        <strong style="color: var(--lime);"><?= $paid_count ?></strong>
      </div>
      <div class="stat-card">
        <span>Pending Invoices</span>
        <strong style="color: #ffc107;"><?= $pending_count ?></strong>
      </div>
      <div class="stat-card">
        <span>Total Records</span>
        <strong style="color: #64b5f6;"><?= count($payments) ?></strong>
      </div>
    </div>

    <!-- Quick Payment Link Creator -->
    <div class="creator-box">
      <h3 style="font-size: 17px; margin-top: 0; margin-bottom: 14px; color: var(--lime); font-family: var(--font-display);">⚡ Generate New Custom Payment Link</h3>
      <form id="quick-create-form">
        <div class="creator-grid">
          <div>
            <label style="font-size: 11px; color: var(--fg-mute); display: block; margin-bottom: 4px;">Customer / Company *</label>
            <input type="text" id="qc-name" required placeholder="e.g. John Doe · Acme Corp" />
          </div>
          <div>
            <label style="font-size: 11px; color: var(--fg-mute); display: block; margin-bottom: 4px;">WhatsApp Number *</label>
            <input type="tel" id="qc-phone" required placeholder="9876543210" />
          </div>
          <div>
            <label style="font-size: 11px; color: var(--fg-mute); display: block; margin-bottom: 4px;">Email Address</label>
            <input type="email" id="qc-email" placeholder="john@example.com" />
          </div>
          <div>
            <label style="font-size: 11px; color: var(--fg-mute); display: block; margin-bottom: 4px;">Amount (INR ₹) *</label>
            <input type="number" id="qc-amount" step="0.01" min="1" required placeholder="e.g. 50000" />
          </div>
        </div>
        <div class="creator-grid" style="grid-template-columns: 2fr 1fr 1fr;">
          <div>
            <label style="font-size: 11px; color: var(--fg-mute); display: block; margin-bottom: 4px;">Payment Purpose / Milestone *</label>
            <input type="text" id="qc-desc" required placeholder="e.g. SaaS Web App Milestone 1" />
          </div>
          <div>
            <label style="font-size: 11px; color: var(--fg-mute); display: block; margin-bottom: 4px;">Invoice Reference (Optional)</label>
            <input type="text" id="qc-ref" placeholder="INV-<?= rand(1000,9999) ?>" />
          </div>
          <div style="display: flex; align-items: flex-end;">
            <button type="submit" id="btn-create-link" class="btn btn--primary btn--md" style="width: 100%; justify-content: center;">Generate Link 🔗</button>
          </div>
        </div>
      </form>

      <!-- Link Result Modal Box -->
      <div id="link-result-box" style="display: none; background: rgba(0,0,0,0.4); border: 1px solid var(--lime); border-radius: 12px; padding: 16px; margin-top: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
          <div>
            <span style="font-size: 12px; color: var(--lime); font-weight: 700;">✅ Payment Link Generated Successfully!</span>
            <div style="font-family: var(--font-mono); font-size: 14px; margin-top: 4px;" id="result-url-text"></div>
          </div>
          <div style="display: flex; gap: 8px;">
            <button onclick="copyPaymentLink()" class="btn btn--ghost btn--sm">Copy URL 📋</button>
            <a id="result-wa-btn" href="#" target="_blank" class="btn btn--ghost btn--sm" style="background: rgba(37, 211, 102, 0.15); border-color: rgba(37, 211, 102, 0.5); color: #25D366;">Send on WhatsApp 💬</a>
            <a id="result-visit-btn" href="#" target="_blank" class="btn btn--primary btn--sm">Open Page ↗</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Search & Filter Controls -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
      <div style="display: flex; gap: 8px;">
        <button onclick="filterStatus('ALL')" class="action-btn" id="filter-all" style="background: rgba(212, 255, 61, 0.15); border-color: var(--lime); color: var(--lime);">All (<?= count($payments) ?>)</button>
        <button onclick="filterStatus('PAID')" class="action-btn" id="filter-paid">Paid (<?= $paid_count ?>)</button>
        <button onclick="filterStatus('PENDING')" class="action-btn" id="filter-pending">Pending (<?= $pending_count ?>)</button>
      </div>
      <div>
        <input type="text" id="table-search" placeholder="Search customer, invoice, phone..." onkeyup="searchTable()" style="background: rgba(255,255,255,0.04); border: 1px solid var(--rule); border-radius: 8px; padding: 8px 14px; color: #fff; font-size: 13px; width: 280px; outline: none;" />
      </div>
    </div>

    <!-- Transactions Table -->
    <div class="table-box">
      <table id="payments-table">
        <thead>
          <tr>
            <th>Status</th>
            <th>ID / Invoice</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Purpose</th>
            <th>Created</th>
            <th>Paid Date</th>
            <th>Gateway ID</th>
            <th style="text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($payments)): ?>
            <tr>
              <td colspan="9" style="text-align: center; padding: 32px; color: var(--fg-mute);">
                No payment transactions recorded yet. Use the generator above or trigger via API.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($payments as $p): ?>
              <?php
                $status = strtoupper($p['status'] ?? 'PENDING');
                $is_p = $status === 'PAID';
                $pay_url = "https://tehub.in/pay?id=" . urlencode($p['id']);
                $clean_phone = preg_replace('/[^0-9]/', '', $p['customer_phone'] ?? '');
                if (strlen($clean_phone) === 10) $clean_phone = '91' . $clean_phone;
                $wa_msg = "Hello " . ($p['customer_name'] ?? 'Client') . ", here is your payment link for " . ($p['description'] ?? 'Services') . " (Amount: ₹" . number_format((float)($p['amount'] ?? 0), 2) . "): " . $pay_url;
                $wa_url = "https://wa.me/" . $clean_phone . "?text=" . urlencode($wa_msg);
              ?>
              <tr data-status="<?= $status ?>" data-search="<?= strtolower(($p['customer_name'] ?? '') . ' ' . ($p['customer_phone'] ?? '') . ' ' . ($p['customer_email'] ?? '') . ' ' . ($p['invoice_number'] ?? '') . ' ' . ($p['id'] ?? '')) ?>">
                <td>
                  <?php if ($is_p): ?>
                    <span class="badge-paid">● PAID</span>
                  <?php else: ?>
                    <span class="badge-pending">○ PENDING</span>
                  <?php endif; ?>
                </td>
                <td>
                  <strong style="font-family: var(--font-mono); color: var(--fg);"><?= htmlspecialchars($p['id'] ?? '') ?></strong>
                  <div style="font-size: 11px; color: var(--fg-mute);"><?= htmlspecialchars($p['invoice_number'] ?? '') ?></div>
                </td>
                <td>
                  <strong style="color: var(--fg);"><?= htmlspecialchars($p['customer_name'] ?? 'Client') ?></strong>
                  <div style="font-size: 11.5px; color: var(--fg-soft);">
                    <?php if (!empty($p['customer_phone'])): ?>
                      <a href="https://wa.me/<?= $clean_phone ?>" target="_blank" style="color: #25D366; text-decoration: none;">+<?= htmlspecialchars($clean_phone) ?></a> &middot;
                    <?php endif; ?>
                    <?= htmlspecialchars($p['customer_email'] ?? '') ?>
                  </div>
                </td>
                <td>
                  <strong style="font-family: var(--font-mono); color: var(--lime); font-size: 15px;">₹<?= number_format((float)($p['amount'] ?? 0), 2) ?></strong>
                </td>
                <td>
                  <div style="max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($p['description'] ?? '') ?>">
                    <?= htmlspecialchars($p['description'] ?? 'Services') ?>
                  </div>
                </td>
                <td style="font-size: 12px; color: var(--fg-mute); font-family: var(--font-mono);">
                  <?= htmlspecialchars(substr($p['created_at'] ?? '', 0, 16)) ?>
                </td>
                <td style="font-size: 12px; color: var(--fg-mute); font-family: var(--font-mono);">
                  <?= !empty($p['paid_at']) ? htmlspecialchars(substr($p['paid_at'], 0, 16)) : '—' ?>
                </td>
                <td style="font-size: 11.5px; font-family: var(--font-mono); color: var(--fg-mute);">
                  <?= htmlspecialchars($p['razorpay_payment_id'] ?? '—') ?>
                </td>
                <td style="text-align: right; white-space: nowrap;">
                  <?php if ($is_p): ?>
                    <a href="pay_success.php?id=<?= urlencode($p['id']) ?>" target="_blank" class="action-btn" title="View Receipt">📄 Receipt</a>
                  <?php else: ?>
                    <button onclick="copyText('<?= $pay_url ?>')" class="action-btn" title="Copy Link">📋 Copy</button>
                    <?php if (!empty($clean_phone)): ?>
                      <a href="<?= $wa_url ?>" target="_blank" class="action-btn" style="color: #25D366;" title="Send WhatsApp">💬 WA</a>
                    <?php endif; ?>
                    <a href="<?= $pay_url ?>" target="_blank" class="action-btn" style="color: var(--lime);" title="Open Checkout">↗ Pay</a>
                  <?php endif; ?>

                  <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this payment record?');">
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" name="delete_id" value="<?= htmlspecialchars($p['id']) ?>" />
                    <button type="submit" class="action-btn" style="color: #ff6b6b;" title="Delete">🗑️</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    let currentGeneratedUrl = '';

    document.getElementById('quick-create-form').addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = document.getElementById('btn-create-link');
      btn.disabled = true;
      btn.innerText = 'Creating...';

      const payload = {
        customer_name: document.getElementById('qc-name').value,
        customer_phone: document.getElementById('qc-phone').value,
        customer_email: document.getElementById('qc-email').value,
        amount: document.getElementById('qc-amount').value,
        description: document.getElementById('qc-desc').value,
        reference: document.getElementById('qc-ref').value
      };

      try {
        const res = await fetch('api/payment/create.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
          currentGeneratedUrl = data.payment_url;
          document.getElementById('result-url-text').innerText = data.payment_url;
          document.getElementById('result-visit-btn').href = data.payment_url;

          let rawPhone = payload.customer_phone.replace(/[^0-9]/g, '');
          if (rawPhone.length === 10) rawPhone = '91' + rawPhone;
          const waMsg = `Hello ${payload.customer_name}, please find your payment link for ${payload.description} (Amount: ₹${parseFloat(payload.amount).toLocaleString('en-IN')}): ${data.payment_url}`;
          document.getElementById('result-wa-btn').href = `https://wa.me/${rawPhone}?text=${encodeURIComponent(waMsg)}`;

          document.getElementById('link-result-box').style.display = 'block';
          btn.disabled = false;
          btn.innerText = 'Generate Link 🔗';
        } else {
          alert(data.message || 'Error creating link');
          btn.disabled = false;
          btn.innerText = 'Generate Link 🔗';
        }
      } catch (err) {
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.innerText = 'Generate Link 🔗';
      }
    });

    function copyPaymentLink() {
      if (currentGeneratedUrl) {
        copyText(currentGeneratedUrl);
      }
    }

    function copyText(text) {
      navigator.clipboard.writeText(text).then(() => {
        alert('Payment link copied to clipboard:\n' + text);
      });
    }

    function filterStatus(status) {
      const rows = document.querySelectorAll('#payments-table tbody tr');
      rows.forEach(r => {
        const rStatus = r.getAttribute('data-status');
        if (status === 'ALL' || rStatus === status) {
          r.style.display = '';
        } else {
          r.style.display = 'none';
        }
      });
      document.querySelectorAll('.action-btn').forEach(b => {
        if (b.id && b.id.startsWith('filter-')) {
          b.style.background = 'rgba(255,255,255,0.06)';
          b.style.borderColor = 'var(--rule)';
          b.style.color = 'var(--fg)';
        }
      });
      const activeBtn = document.getElementById('filter-' + status.toLowerCase());
      if (activeBtn) {
        activeBtn.style.background = 'rgba(212, 255, 61, 0.15)';
        activeBtn.style.borderColor = 'var(--lime)';
        activeBtn.style.color = 'var(--lime)';
      }
    }

    function searchTable() {
      const q = document.getElementById('table-search').value.toLowerCase().trim();
      const rows = document.querySelectorAll('#payments-table tbody tr');
      rows.forEach(r => {
        const searchData = r.getAttribute('data-search') || '';
        if (!q || searchData.includes(q)) {
          r.style.display = '';
        } else {
          r.style.display = 'none';
        }
      });
    }
  </script>

<?php endif; ?>
</body>
</html>

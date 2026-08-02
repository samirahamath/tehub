<?php
session_start();
date_default_timezone_set('Asia/Kolkata');

// Simple Password Protection
$admin_pass = 'Inayah@62';

if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $login_error = "Invalid password. Please try again.";
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['admin_logged_in']);
    header("Location: visitors");
    exit;
}

$is_logged_in = $_SESSION['admin_logged_in'] ?? false;

// Handle Delete Single Request
if ($is_logged_in && isset($_POST['delete_request_id'])) {
    $del_id = $_POST['delete_request_id'];
    $reqFile = __DIR__ . '/client_requests.json';
    if (file_exists($reqFile)) {
        $reqs = @json_decode(@file_get_contents($reqFile), true) ?? [];
        $reqs = array_values(array_filter($reqs, function($r) use ($del_id) {
            return ($r['id'] ?? '') !== $del_id;
        }));
        @file_put_contents($reqFile, json_encode($reqs, JSON_PRETTY_PRINT));
        $request_deleted_msg = "Client inquiry deleted successfully!";
    }
}

// Handle Clear Visitor Logs
if ($is_logged_in && isset($_POST['clear_logs'])) {
    @file_put_contents(__DIR__ . '/visitor_logs.json', json_encode([]));
    $clear_success = "Visitor logs cleared successfully!";
}

// Load Client Requests
$requestsFile = __DIR__ . '/client_requests.json';
$client_requests = [];
if (file_exists($requestsFile)) {
    $client_requests = @json_decode(@file_get_contents($requestsFile), true) ?? [];
}

// Load Visitor Logs
$logFile = __DIR__ . '/visitor_logs.json';
$logs = [];
if (file_exists($logFile)) {
    $json_content = @file_get_contents($logFile);
    $logs = @json_decode($json_content, true) ?? [];
}

// Helper function to format duration
function formatDuration($seconds) {
    if ($seconds <= 0) return '0s (Just Landed)';
    if ($seconds < 60) return $seconds . 's';
    $m = floor($seconds / 60);
    $s = $seconds % 60;
    return $m . 'm ' . $s . 's';
}

// Calculate Stats
$total_requests = count($client_requests);
$total_views = count($logs);
$unique_ips = count(array_unique(array_column($logs, 'ip')));
$mobile_count = 0;
$desktop_count = 0;
$today_count = 0;
$active_now_count = 0;
$today_str = date('Y-m-d');
$now_time = time();

foreach ($logs as $l) {
    if (($l['device'] ?? '') === 'Mobile') $mobile_count++;
    else $desktop_count++;

    if (strpos($l['timestamp'] ?? '', $today_str) === 0) {
        $today_count++;
    }

    $last_active = $l['last_active'] ?? 0;
    if (($now_time - $last_active) <= 15) {
        $active_now_count++;
    }
}

// Export Inquiries CSV
if ($is_logged_in && isset($_GET['action']) && $_GET['action'] === 'export_requests_csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="client_inquiries_' . date('Ymd_His') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID', 'Date/Time (IST)', 'Client Name', 'WhatsApp Phone', 'Email', 'Company', 'Role', 'Probable Tier', 'Launch Window', 'Project Type', 'Requirements Brief', 'Reference Link', 'Status']);
    foreach ($client_requests as $r) {
        fputcsv($out, [
            $r['id'] ?? '',
            $r['timestamp'] ?? '',
            $r['name'] ?? '',
            $r['phone'] ?? '',
            $r['email'] ?? '',
            $r['brand'] ?? '',
            $r['role'] ?? '',
            $r['tier'] ?? '',
            $r['dates'] ?? '',
            $r['where'] ?? '',
            $r['brief'] ?? '',
            $r['refs'] ?? '',
            $r['status'] ?? ''
        ]);
    }
    fclose($out);
    exit;
}

// Export Visitor Logs CSV
if ($is_logged_in && isset($_GET['action']) && $_GET['action'] === 'export_csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="visitor_analytics_' . date('Ymd_His') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID', 'IP Address', 'Location Name', 'Page Visited', 'Time Spent (Sec)', 'Device', 'User Agent', 'Referrer', 'Timestamp (IST)']);
    foreach ($logs as $row) {
        fputcsv($out, [
            $row['id'] ?? '',
            $row['ip'] ?? '',
            $row['location'] ?? 'Unknown Location',
            $row['page'] ?? '',
            $row['duration'] ?? 0,
            $row['device'] ?? '',
            $row['user_agent'] ?? '',
            $row['referrer'] ?? '',
            $row['timestamp'] ?? ''
        ]);
    }
    fclose($out);
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard — Client Inquiries & Visitor Analytics | THE EXPERT HUB</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="assets/css/styles.css?v=1.5" />
  <style>
    body {
      background: #0a0a0c;
      color: #f4f4f0;
      font-family: 'Inter Tight', sans-serif;
      padding: 0;
      margin: 0;
    }
    .admin-container {
      max-width: 1440px;
      margin: 0 auto;
      padding: 40px 20px;
    }
    .admin-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      padding-bottom: 20px;
    }
    .admin-title h1 {
      font-size: 28px;
      margin: 0 0 6px 0;
      color: #d4ff3d;
    }
    .admin-title p {
      margin: 0;
      color: #888;
      font-size: 14px;
    }
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }
    .stat-card {
      background: #141418;
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 12px;
      padding: 18px;
    }
    .stat-card strong {
      display: block;
      font-size: 28px;
      font-family: 'Geist Mono', monospace;
      color: #d4ff3d;
      margin-bottom: 4px;
    }
    .stat-card span {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: #888;
    }
    .active-pulse {
      display: inline-block;
      width: 8px;
      height: 8px;
      background: #22c55e;
      border-radius: 50%;
      margin-right: 6px;
      box-shadow: 0 0 10px #22c55e;
      animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
      70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }
    
    /* Navigation Tabs */
    .tab-navigation {
      display: flex;
      gap: 12px;
      margin-bottom: 24px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      padding-bottom: 12px;
    }
    .tab-btn {
      background: #141418;
      border: 1px solid rgba(255,255,255,0.12);
      color: #aaa;
      padding: 10px 20px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
    }
    .tab-btn.active {
      background: #d4ff3d;
      color: #000;
      border-color: #d4ff3d;
    }
    .badge-count {
      background: rgba(0,0,0,0.2);
      padding: 2px 8px;
      border-radius: 100px;
      font-size: 11px;
      font-family: 'Geist Mono', monospace;
    }

    .table-card {
      background: #141418;
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 16px;
      overflow: hidden;
    }
    .table-toolbar {
      padding: 16px 20px;
      background: #18181f;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
    }
    .search-input {
      background: #0a0a0c;
      border: 1px solid rgba(255,255,255,0.15);
      color: #fff;
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 14px;
      width: 320px;
      outline: none;
    }
    .search-input:focus { border-color: #d4ff3d; }
    .logs-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    .logs-table th {
      background: #101014;
      text-align: left;
      padding: 12px 16px;
      font-family: 'Geist Mono', monospace;
      font-size: 11px;
      text-transform: uppercase;
      color: #888;
      letter-spacing: 0.08em;
      border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .logs-table td {
      padding: 14px 16px;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      color: #ccc;
      vertical-align: top;
    }
    .logs-table tr:hover td { background: rgba(212, 255, 61, 0.03); }
    .btn-admin {
      background: #d4ff3d;
      color: #000;
      border: none;
      padding: 8px 16px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-admin:hover { opacity: 0.9; }
    .btn-danger { background: #ff4a4a; color: #fff; }
    .btn-wa {
      background: #25D366;
      color: #fff;
      padding: 4px 10px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      font-size: 12px;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }
    .btn-wa:hover { opacity: 0.9; }
    .verified-badge {
      background: rgba(34, 197, 94, 0.15);
      color: #22c55e;
      padding: 2px 8px;
      border-radius: 100px;
      font-size: 11px;
      font-family: 'Geist Mono', monospace;
    }
    .login-modal {
      max-width: 380px;
      margin: 100px auto;
      background: #141418;
      border: 1px solid rgba(212, 255, 61, 0.3);
      border-radius: 16px;
      padding: 32px;
      text-align: center;
    }
    .login-modal h2 { margin-top: 0; color: #d4ff3d; }
    .login-modal input[type=password] {
      width: 100%;
      box-sizing: border-box;
      padding: 12px;
      background: #0a0a0c;
      border: 1px solid rgba(255,255,255,0.15);
      color: #fff;
      border-radius: 8px;
      margin: 16px 0;
      text-align: center;
      font-size: 16px;
    }
  </style>
</head>
<body>

<?php if (!$is_logged_in): ?>
  <div class="login-modal">
    <h2>🔒 Admin Dashboard</h2>
    <p style="color: #888; font-size: 13px;">Enter admin password to view client requests &amp; live analytics.</p>
    <?php if (isset($login_error)): ?>
      <div style="color: #ff4a4a; font-size: 13px; margin-bottom: 10px;"><?php echo $login_error; ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="password" name="password" placeholder="Admin Password" required autofocus />
      <button type="submit" class="btn-admin" style="width: 100%; justify-content: center;">Login to Dashboard →</button>
    </form>
  </div>
<?php else: ?>

  <div class="admin-container">
    <div class="admin-header">
      <div class="admin-title">
        <h1>⚡ THE EXPERT HUB — Admin Master Control</h1>
        <p>Manage client inquiries, project briefs, visitor IPs, location &amp; live session metrics</p>
      </div>
      <div style="display: flex; gap: 10px;">
        <a href="visitors?action=export_requests_csv" class="btn-admin">📥 Export Client Inquiries</a>
        <a href="visitors?action=logout" class="btn-admin" style="background: rgba(255,255,255,0.1); color: #fff;">Logout</a>
      </div>
    </div>

    <?php if (isset($request_deleted_msg)): ?>
      <div style="background: rgba(212, 255, 61, 0.1); border: 1px solid #d4ff3d; color: #d4ff3d; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
        <?php echo $request_deleted_msg; ?>
      </div>
    <?php endif; ?>

    <div class="stats-grid">
      <div class="stat-card" style="border-color: rgba(212, 255, 61, 0.4);">
        <strong style="color: #d4ff3d;"><?php echo number_format($total_requests); ?></strong>
        <span>Verified Client Requests</span>
      </div>
      <div class="stat-card">
        <strong style="color: #22c55e;"><span class="active-pulse"></span><?php echo number_format($active_now_count); ?></strong>
        <span>Active Visitors Now</span>
      </div>
      <div class="stat-card">
        <strong><?php echo number_format($total_views); ?></strong>
        <span>Total Pageviews</span>
      </div>
      <div class="stat-card">
        <strong><?php echo number_format($unique_ips); ?></strong>
        <span>Unique Visitor IPs</span>
      </div>
      <div class="stat-card">
        <strong><?php echo $mobile_count; ?> / <?php echo $desktop_count; ?></strong>
        <span>Mobile / Desktop</span>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="tab-navigation">
      <button class="tab-btn active" id="tab-inquiries-btn" onclick="switchTab('inquiries')">
        📥 Client Inquiries &amp; Briefs <span class="badge-count"><?php echo $total_requests; ?></span>
      </button>
      <button class="tab-btn" id="tab-visitors-btn" onclick="switchTab('visitors')">
        👁️ Visitor IPs, Location &amp; Time Spent <span class="badge-count"><?php echo $total_views; ?></span>
      </button>
    </div>

    <!-- TAB 1: CLIENT INQUIRIES & BRIEFS -->
    <div id="tab-inquiries" class="table-card">
      <div class="table-toolbar">
        <input type="text" id="reqSearch" class="search-input" placeholder="Search client name, phone, email, brief..." onkeyup="filterRequests()" />
        <span style="color: #888; font-size: 12px;">Showing <?php echo count($client_requests); ?> verified client project briefs</span>
      </div>

      <div style="overflow-x: auto;">
        <table class="logs-table" id="reqTable">
          <thead>
            <tr>
              <th>Date / Time</th>
              <th>Client Details</th>
              <th>WhatsApp / Contact</th>
              <th>Tier &amp; Launch</th>
              <th>Project Type &amp; Brief</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($client_requests)): ?>
              <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #666;">No client requests received yet. Tested submissions will appear here automatically!</td>
              </tr>
            <?php else: ?>
              <?php foreach ($client_requests as $req): ?>
                <?php 
                  $clean_ph = preg_replace('/[^0-9]/', '', $req['phone'] ?? '');
                  if (strlen($clean_ph) === 10) $clean_ph = '91' . $clean_ph;
                ?>
                <tr>
                  <td style="font-family: 'Geist Mono', monospace; white-space: nowrap; color: #888;">
                    <?php echo htmlspecialchars($req['timestamp'] ?? ''); ?>
                  </td>
                  <td>
                    <strong style="color: #fff; font-size: 14px; display: block;"><?php echo htmlspecialchars($req['name'] ?? 'Guest'); ?></strong>
                    <span style="color: #888; font-size: 12px;"><?php echo htmlspecialchars($req['role'] ?? ''); ?> <?php echo !empty($req['brand']) ? '· ' . htmlspecialchars($req['brand']) : ''; ?></span>
                  </td>
                  <td>
                    <div style="margin-bottom: 6px;">
                      <a href="https://wa.me/<?php echo $clean_ph; ?>?text=<?php echo urlencode('Hi ' . ($req['name'] ?? '') . ', thank you for contacting THE EXPERT HUB regarding your project brief.'); ?>" target="_blank" class="btn-wa">
                        💬 WhatsApp +<?php echo htmlspecialchars($clean_ph); ?>
                      </a>
                    </div>
                    <a href="mailto:<?php echo htmlspecialchars($req['email'] ?? ''); ?>" style="color: #00b4ff; font-size: 12px; text-decoration: none;">
                      ✉️ <?php echo htmlspecialchars($req['email'] ?? ''); ?>
                    </a>
                  </td>
                  <td>
                    <div style="color: #d4ff3d; font-weight: 600; font-size: 12px; margin-bottom: 4px;"><?php echo htmlspecialchars($req['tier'] ?? 'Custom'); ?></div>
                    <div style="color: #888; font-size: 11px;">📅 Window: <?php echo htmlspecialchars($req['dates'] ?? 'N/A'); ?></div>
                  </td>
                  <td style="max-width: 320px;">
                    <?php if (!empty($req['where'])): ?>
                      <div style="color: #fff; font-weight: 500; margin-bottom: 4px; font-size: 12px;">🎯 <?php echo htmlspecialchars($req['where']); ?></div>
                    <?php endif; ?>
                    <div style="color: #ccc; font-size: 12px; line-height: 1.5; background: #0a0a0c; padding: 10px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08);">
                      "<?php echo nl2br(htmlspecialchars($req['brief'] ?? 'No brief details')); ?>"
                    </div>
                    <?php if (!empty($req['refs']) && $req['refs'] !== 'N/A'): ?>
                      <div style="margin-top: 6px;">
                        <a href="<?php echo htmlspecialchars($req['refs']); ?>" target="_blank" rel="noopener" style="color: #d4ff3d; font-size: 11px; text-decoration: underline;">🔗 View Reference Link</a>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span class="verified-badge">✅ <?php echo htmlspecialchars($req['status'] ?? 'OTP Verified'); ?></span>
                  </td>
                  <td>
                    <form method="POST" onsubmit="return confirm('Delete this client inquiry?');" style="margin: 0;">
                      <input type="hidden" name="delete_request_id" value="<?php echo htmlspecialchars($req['id'] ?? ''); ?>" />
                      <button type="submit" style="background: none; border: none; color: #ff4a4a; cursor: pointer; font-size: 12px;">🗑️ Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- TAB 2: VISITOR IPS, LOCATION & TIME SPENT -->
    <div id="tab-visitors" class="table-card" style="display: none;">
      <div class="table-toolbar">
        <input type="text" id="logSearch" class="search-input" placeholder="Search location, IP, page, or device..." onkeyup="filterLogs()" />
        <div style="display: flex; gap: 10px; align-items: center;">
          <a href="visitors?action=export_csv" class="btn-admin" style="background: rgba(255,255,255,0.1); color: #fff;">📥 Export Visitor CSV</a>
          <form method="POST" onsubmit="return confirm('Are you sure you want to clear all visitor logs?');" style="margin: 0;">
            <button type="submit" name="clear_logs" class="btn-admin btn-danger">🗑️ Clear Visitor Logs</button>
          </form>
        </div>
      </div>

      <div style="overflow-x: auto;">
        <table class="logs-table" id="logsTable">
          <thead>
            <tr>
              <th>Timestamp (IST)</th>
              <th>Location Name</th>
              <th>IP Address</th>
              <th>Page Visited</th>
              <th>Time Spent</th>
              <th>Device</th>
              <th>Referrer</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($logs)): ?>
              <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #666;">No visitor logs recorded yet. Browse the website to log activity!</td>
              </tr>
            <?php else: ?>
              <?php foreach ($logs as $row): ?>
                <?php 
                  $last_act = $row['last_active'] ?? 0;
                  $is_active = ($now_time - $last_act) <= 15;
                  $dur_sec = $row['duration'] ?? 0;
                ?>
                <tr>
                  <td style="font-family: 'Geist Mono', monospace; white-space: nowrap; color: #888;">
                    <?php echo htmlspecialchars($row['timestamp'] ?? ''); ?>
                  </td>
                  <td>
                    <span style="color: #fff; font-weight: 500;">📍 <?php echo htmlspecialchars($row['location'] ?? 'Unknown Location'); ?></span>
                  </td>
                  <td>
                    <span style="font-family: 'Geist Mono', monospace; color: #d4ff3d; font-weight: 600;"><?php echo htmlspecialchars($row['ip'] ?? ''); ?></span>
                  </td>
                  <td style="font-weight: 500; color: #fff;">
                    <a href="<?php echo htmlspecialchars($row['page'] ?? '#'); ?>" target="_blank" style="color: #fff; text-decoration: none;">
                      <?php echo htmlspecialchars($row['page'] ?? '/'); ?>
                    </a>
                  </td>
                  <td>
                    <?php if ($is_active): ?>
                      <span style="color: #22c55e; font-weight: 600; font-family: 'Geist Mono', monospace;">
                        <span class="active-pulse"></span>Active Now (<?php echo formatDuration($dur_sec); ?>)
                      </span>
                    <?php else: ?>
                      <span style="font-family: 'Geist Mono', monospace; font-weight: 600; color: #d4ff3d;">⏱️ <?php echo formatDuration($dur_sec); ?></span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php 
                      $dev = $row['device'] ?? 'Desktop';
                      $class = ($dev === 'Mobile') ? 'device-mobile' : (($dev === 'Bot/Crawler') ? 'device-bot' : 'device-desktop');
                    ?>
                    <span class="device-badge <?php echo $class; ?>"><?php echo htmlspecialchars($dev); ?></span>
                  </td>
                  <td style="color: #888; font-size: 12px; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <?php echo htmlspecialchars($row['referrer'] ?? 'Direct'); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
  function switchTab(tabName) {
    document.getElementById('tab-inquiries').style.display = tabName === 'inquiries' ? 'block' : 'none';
    document.getElementById('tab-visitors').style.display = tabName === 'visitors' ? 'block' : 'none';
    
    document.getElementById('tab-inquiries-btn').classList.toggle('active', tabName === 'inquiries');
    document.getElementById('tab-visitors-btn').classList.toggle('active', tabName === 'visitors');
  }

  function filterRequests() {
    var input = document.getElementById("reqSearch");
    var filter = input.value.toLowerCase();
    var table = document.getElementById("reqTable");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
      var text = tr[i].textContent || tr[i].innerText;
      if (text.toLowerCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }

  function filterLogs() {
    var input = document.getElementById("logSearch");
    var filter = input.value.toLowerCase();
    var table = document.getElementById("logsTable");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
      var text = tr[i].textContent || tr[i].innerText;
      if (text.toLowerCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
  </script>

<?php endif; ?>
</body>
</html>

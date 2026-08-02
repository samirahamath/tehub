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

// Handle Clear Logs
if ($is_logged_in && isset($_POST['clear_logs'])) {
    @file_put_contents(__DIR__ . '/visitor_logs.json', json_encode([]));
    $clear_success = "Visitor logs cleared successfully!";
}

// Load Visitor Logs
$logFile = __DIR__ . '/visitor_logs.json';
$logs = [];
if (file_exists($logFile)) {
    $json_content = @file_get_contents($logFile);
    $logs = @json_decode($json_content, true) ?? [];
}

// Helper function to format duration seconds to human-readable string
function formatDuration($seconds) {
    if ($seconds <= 0) return '0s (Just Landed)';
    if ($seconds < 60) return $seconds . 's';
    $m = floor($seconds / 60);
    $s = $seconds % 60;
    return $m . 'm ' . $s . 's';
}

// Calculate Stats
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

// Handle Export CSV
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
  <title>Visitor Analytics & IP Tracker — THE EXPERT HUB</title>
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
      max-width: 1380px;
      margin: 0 auto;
      padding: 40px 20px;
    }
    .admin-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
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
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 30px;
    }
    .stat-card {
      background: #141418;
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 12px;
      padding: 20px;
    }
    .stat-card strong {
      display: block;
      font-size: 30px;
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
    }
    .logs-table tr:hover td { background: rgba(212, 255, 61, 0.03); }
    .ip-badge { font-family: 'Geist Mono', monospace; color: #d4ff3d; font-weight: 600; }
    .location-badge { color: #fff; font-weight: 500; }
    .duration-badge { font-family: 'Geist Mono', monospace; font-weight: 600; color: #d4ff3d; }
    .device-badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 100px;
      font-size: 11px;
      font-family: 'Geist Mono', monospace;
    }
    .device-mobile { background: rgba(255, 180, 0, 0.15); color: #ffb400; }
    .device-desktop { background: rgba(0, 180, 255, 0.15); color: #00b4ff; }
    .device-bot { background: rgba(255, 74, 74, 0.15); color: #ff4a4a; }
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
    <h2>🔒 Visitor Analytics Login</h2>
    <p style="color: #888; font-size: 13px;">Enter admin password to view live visitor IP, location & time spent.</p>
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
        <h1>👁️ Visitor Location, IP &amp; Time Analytics</h1>
        <p>Live tracking of visitor location, page visited, IP address &amp; exact time spent on <strong>tehub.in</strong></p>
      </div>
      <div style="display: flex; gap: 10px;">
        <a href="visitors?action=export_csv" class="btn-admin">📥 Export CSV</a>
        <a href="visitors?action=logout" class="btn-admin" style="background: rgba(255,255,255,0.1); color: #fff;">Logout</a>
      </div>
    </div>

    <?php if (isset($clear_success)): ?>
      <div style="background: rgba(212, 255, 61, 0.1); border: 1px solid #d4ff3d; color: #d4ff3d; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
        <?php echo $clear_success; ?>
      </div>
    <?php endif; ?>

    <div class="stats-grid">
      <div class="stat-card">
        <strong style="color: #22c55e;"><span class="active-pulse"></span><?php echo number_format($active_now_count); ?></strong>
        <span>Active Now (Online)</span>
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
        <strong><?php echo number_format($today_count); ?></strong>
        <span>Today's Visits</span>
      </div>
      <div class="stat-card">
        <strong><?php echo $mobile_count; ?> / <?php echo $desktop_count; ?></strong>
        <span>Mobile / Desktop</span>
      </div>
    </div>

    <div class="table-card">
      <div class="table-toolbar">
        <input type="text" id="logSearch" class="search-input" placeholder="Search location, IP, page, or device..." onkeyup="filterLogs()" />
        <form method="POST" onsubmit="return confirm('Are you sure you want to clear all visitor logs?');" style="margin: 0;">
          <button type="submit" name="clear_logs" class="btn-admin btn-danger">🗑️ Clear All Logs</button>
        </form>
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
                <td colspan="7" style="text-align: center; padding: 40px; color: #666;">No visitor logs recorded yet. Browse the website to log your activity!</td>
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
                    <span class="location-badge">📍 <?php echo htmlspecialchars($row['location'] ?? 'Unknown Location'); ?></span>
                  </td>
                  <td>
                    <span class="ip-badge"><?php echo htmlspecialchars($row['ip'] ?? ''); ?></span>
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
                      <span class="duration-badge">⏱️ <?php echo formatDuration($dur_sec); ?></span>
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

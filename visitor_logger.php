<?php
// Visitor IP Logger for THE EXPERT HUB
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getVisitorIP() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return trim($_SERVER['HTTP_CF_CONNECTING_IP']);
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }
    if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        return trim($_SERVER['HTTP_X_REAL_IP']);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

function getDeviceType($user_agent) {
    $ua = strtolower($user_agent);
    if (strpos($ua, 'mobile') !== false || strpos($ua, 'android') !== false || strpos($ua, 'iphone') !== false) {
        return 'Mobile';
    }
    if (strpos($ua, 'tablet') !== false || strpos($ua, 'ipad') !== false) {
        return 'Tablet';
    }
    if (strpos($ua, 'bot') !== false || strpos($ua, 'crawler') !== false || strpos($ua, 'spider') !== false) {
        return 'Bot/Crawler';
    }
    return 'Desktop';
}

function logVisitor() {
    // Avoid duplicate logging within the same session for identical page inside 5 seconds
    $ip = getVisitorIP();
    $page = $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF'] ?? '/';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $referrer = $_SERVER['HTTP_REFERER'] ?? 'Direct / None';
    
    // Ignore internal admin panel visits
    if (strpos($page, 'visitors.php') !== false) {
        return;
    }

    $last_logged_page = $_SESSION['last_logged_page'] ?? '';
    $last_logged_time = $_SESSION['last_logged_time'] ?? 0;

    if ($last_logged_page === $page && (time() - $last_logged_time) < 5) {
        return;
    }

    $_SESSION['last_logged_page'] = $page;
    $_SESSION['last_logged_time'] = time();

    date_default_timezone_set('Asia/Kolkata');
    $timestamp = date('Y-m-d H:i:s');

    $logEntry = [
        'id'         => uniqid(),
        'ip'         => $ip,
        'page'       => $page,
        'device'     => getDeviceType($user_agent),
        'user_agent' => $user_agent,
        'referrer'   => $referrer,
        'timestamp'  => $timestamp
    ];

    $logFile = __DIR__ . '/visitor_logs.json';

    $logs = [];
    if (file_exists($logFile)) {
        $json_content = @file_get_contents($logFile);
        $logs = @json_decode($json_content, true) ?? [];
    }

    // Prepend new entry so newest visitors are at the top
    array_unshift($logs, $logEntry);

    // Keep maximum 2000 recent entries
    if (count($logs) > 2000) {
        $logs = array_slice($logs, 0, 2000);
    }

    @file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
}

// Execute logger
try {
    logVisitor();
} catch (Exception $e) {
    // Silently continue if file write fails
}
?>

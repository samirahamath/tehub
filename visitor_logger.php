<?php
// Visitor IP Logger for THE EXPERT HUB with Geolocation & Time Tracker
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

function getIPLocation($ip) {
    if ($ip === '127.0.0.1' || $ip === '::1' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
        return 'Local Server / Internal';
    }

    $cacheFile = __DIR__ . '/ip_cache.json';
    $cache = [];
    if (file_exists($cacheFile)) {
        $cache = @json_decode(@file_get_contents($cacheFile), true) ?? [];
    }

    if (isset($cache[$ip])) {
        return $cache[$ip];
    }

    // Fetch from free IP API
    $locString = 'Unknown Location';
    try {
        $ch = curl_init("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $res = curl_exec($ch);
        curl_close($ch);

        if ($res) {
            $data = json_decode($res, true);
            if (($data['status'] ?? '') === 'success') {
                $parts = array_filter([$data['city'] ?? '', $data['regionName'] ?? '', $data['country'] ?? '']);
                if (!empty($parts)) {
                    $locString = implode(', ', $parts);
                }
            }
        }
    } catch (Exception $e) {
        // Fallback to unknown
    }

    $cache[$ip] = $locString;
    @file_put_contents($cacheFile, json_encode($cache, JSON_PRETTY_PRINT));

    return $locString;
}

function logVisitor() {
    $ip = getVisitorIP();
    $page = $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF'] ?? '/';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $referrer = $_SERVER['HTTP_REFERER'] ?? 'Direct / None';
    
    // Ignore internal admin panel visits and API scripts
    if (strpos($page, 'visitors.php') !== false || strpos($page, 'update_duration.php') !== false) {
        return;
    }

    $last_logged_page = $_SESSION['last_logged_page'] ?? '';
    $last_logged_time = $_SESSION['last_logged_time'] ?? 0;

    // Prevent double logging on immediate refresh within 4 seconds
    if ($last_logged_page === $page && (time() - $last_logged_time) < 4) {
        return;
    }

    $visit_id = 'v_' . uniqid() . '_' . rand(100, 999);

    $_SESSION['last_logged_page'] = $page;
    $_SESSION['last_logged_time'] = time();
    $_SESSION['current_visit_id'] = $visit_id;

    date_default_timezone_set('Asia/Kolkata');
    $timestamp = date('Y-m-d H:i:s');
    $location = getIPLocation($ip);

    $logEntry = [
        'id'          => $visit_id,
        'ip'          => $ip,
        'location'    => $location,
        'page'        => $page,
        'device'      => getDeviceType($user_agent),
        'user_agent'  => $user_agent,
        'referrer'    => $referrer,
        'timestamp'   => $timestamp,
        'duration'    => 0,
        'last_active' => time()
    ];

    $logFile = __DIR__ . '/visitor_logs.json';

    $logs = [];
    if (file_exists($logFile)) {
        $json_content = @file_get_contents($logFile);
        $logs = @json_decode($json_content, true) ?? [];
    }

    // Prepend new entry
    array_unshift($logs, $logEntry);

    // Limit to 2000 entries max
    if (count($logs) > 2000) {
        $logs = array_slice($logs, 0, 2000);
    }

    @file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));

    // Output client-side duration tracker JS script
    register_shutdown_function(function() use ($visit_id) {
        echo "<script>
        (function(){
          var vid = " . json_encode($visit_id) . ";
          var startTime = Date.now();
          function sendDuration() {
            var dur = Math.round((Date.now() - startTime) / 1000);
            if (navigator.sendBeacon) {
              var fd = new FormData();
              fd.append('visit_id', vid);
              fd.append('duration', dur);
              navigator.sendBeacon('update_duration', fd);
            } else {
              var xhr = new XMLHttpRequest();
              xhr.open('POST', 'update_duration', true);
              xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
              xhr.send('visit_id=' + encodeURIComponent(vid) + '&duration=' + dur);
            }
          }
          setInterval(sendDuration, 5000);
          document.addEventListener('visibilitychange', function(){ if (document.visibilityState === 'hidden') sendDuration(); });
          window.addEventListener('pagehide', sendDuration);
        })();
        </script>";
    });
}

// Execute logger
try {
    logVisitor();
} catch (Exception $e) {
    // Silently handle exceptions
}
?>

<?php
// Endpoint to update visitor page stay duration in real time
ignore_user_abort(true);
header('Content-Type: application/json');

$visit_id = trim($_POST['visit_id'] ?? $_GET['visit_id'] ?? '');
$duration = intval($_POST['duration'] ?? $_GET['duration'] ?? 0);

if (empty($visit_id) || $duration < 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
    exit;
}

$logFile = __DIR__ . '/visitor_logs.json';

if (file_exists($logFile)) {
    $json_content = @file_get_contents($logFile);
    $logs = @json_decode($json_content, true);

    if (is_array($logs)) {
        $updated = false;
        foreach ($logs as &$entry) {
            if (($entry['id'] ?? '') === $visit_id) {
                $entry['duration'] = $duration;
                $entry['last_active'] = time();
                $updated = true;
                break;
            }
        }
        unset($entry);

        if ($updated) {
            @file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
            echo json_encode(['status' => 'success', 'duration' => $duration]);
            exit;
        }
    }
}

echo json_encode(['status' => 'not_found']);
?>

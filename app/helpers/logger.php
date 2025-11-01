<?php
require_once APP . '/models/SystemLog.php';

function log_activity($user_id, $action, $details = '') {
    $systemLogModel = new SystemLog();
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    return $systemLogModel->log($user_id, $action, json_encode($details), $ip_address);
}

// Function to auto-cleanup logs (can be called by a cron job or periodically)
function cleanup_logs() {
    $systemLogModel = new SystemLog();
    // Assuming a method exists in SystemLog model to delete old logs
    // $systemLogModel->deleteOldLogs(6, 'months');
}

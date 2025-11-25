<?php
// Debug runner: simple script to test send_email() and verify logging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Require config and helpers
require_once 'app/config/config.php';
require_once 'app/helpers/email_helper.php';

// Start a session so log_activity/get_session can work if needed
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set a test user id (optional) so DB log attaches a user
$_SESSION['user_id'] = $_SESSION['user_id'] ?? 1;

$to = defined('TEST_EMAIL') ? TEST_EMAIL : SMTP_USER;
$subject = 'Test Email - StudentAttendance ' . date('Y-m-d H:i:s');
$body = '<p>This is a test email sent by <strong>run_debug_email_test.php</strong> to verify sending and logging.</p>';

echo "Sending test email to: $to\n";
$ok = send_email($to, $subject, $body);
if ($ok) {
    echo "Result: SUCCESS\n";
} else {
    echo "Result: FAILED - check logs/email.log and PHP error log for details\n";
}

// Show last few lines of file log if present
$logFile = __DIR__ . '/logs/email.log';
if (file_exists($logFile)) {
    echo "\nLast log lines from logs/email.log:\n";
    $lines = array_slice(file($logFile), -10);
    echo implode('', $lines);
} else {
    echo "\nNo file log found at logs/email.log\n";
}

?>

<?php
// Debug runner: simulate POST /admin/reports/attendance_report as an admin to generate report
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simulate request
$_SERVER['REQUEST_URI'] = '/admin/reports/attendance_report';
$_GET['url'] = 'admin/reports/attendance_report';
$_SERVER['REQUEST_METHOD'] = 'POST';

// Ensure environment
if (!isset($_ENV['APP_ENV'])) {
    $_ENV['APP_ENV'] = 'development';
}

// Start session and set admin role
session_start();
$_SESSION['user_id'] = 1; // adjust to a valid admin user id if needed
$_SESSION['user_role'] = 'admin';

// Provide POST data
$_POST['start_date'] = date('Y-m-d', strtotime('-30 days'));
$_POST['end_date'] = date('Y-m-d');
// Pick first course id from DB if possible, otherwise leave blank

require_once 'index.php';

echo "\n-- DEBUG: Generated report page should have been rendered above --\n";

?>

<?php
// Debug runner: simulate POST to mark attendance as a lecturer
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simulate request
$_SERVER['REQUEST_URI'] = '/lecturer/attendance/save';
$_GET['url'] = 'lecturer/attendance/save';
$_SERVER['REQUEST_METHOD'] = 'POST';

// Ensure environment
if (!isset($_ENV['APP_ENV'])) {
    $_ENV['APP_ENV'] = 'development';
}

// Start session and set lecturer role
session_start();
$_SESSION['user_id'] = 3; // adjust to a valid lecturer id
$_SESSION['user_role'] = 'lecturer';

// Prepare POST data
// Set schedule_id to an existing schedule id in your DB
$_POST['schedule_id'] = 1;
$_POST['date'] = date('Y-m-d');

// Mark first two students: use user ids that exist in your DB
// This associative array should use student user ids as keys
$_POST['attendance'] = [
    2 => 'present',
    4 => 'absent'
];
$_POST['notes'] = [
    2 => '',
    4 => 'Missed due to illness'
];

require_once 'index.php';

echo "\n-- DEBUG: Attendance save simulated --\n";

?>

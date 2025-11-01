<?php
// Debug runner: simulate GET /student/myAttendance/trend/{unit_id} as a logged-in student
error_reporting(E_ALL);
ini_set('display_errors', 1);

$unit_id = 1; // adjust to a valid unit id

// Simulate request
$_SERVER['REQUEST_URI'] = '/student/myAttendance/trend/' . $unit_id;
$_GET['url'] = 'student/myAttendance/trend/' . $unit_id;
$_SERVER['REQUEST_METHOD'] = 'GET';

// Ensure environment
if (!isset($_ENV['APP_ENV'])) {
    $_ENV['APP_ENV'] = 'development';
}

// Start session
session_start();
$_SESSION['user_id'] = 2; // set to a valid student id in your DB if needed
$_SESSION['user_role'] = 'student';

require 'index.php';

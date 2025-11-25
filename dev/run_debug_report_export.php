<?php
// Debug runner: simulate GET /admin/reports/exportAttendanceCsv to download CSV for a course
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session and set admin role
session_start();
$_SESSION['user_id'] = 1; // adjust as needed
$_SESSION['user_role'] = 'admin';

// Choose parameters - set course_id to a valid course id in your DB
// If you don't know a course id, set it to 1 and adjust in the DB if needed
$_GET['course_id'] = $_GET['course_id'] ?? 1;
$_GET['start_date'] = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$_GET['end_date'] = $_GET['end_date'] ?? date('Y-m-d');

// Simulate request URI and routing
$_SERVER['REQUEST_URI'] = '/admin/reports/exportAttendanceCsv';
$_GET['url'] = 'admin/reports/exportAttendanceCsv';
$_SERVER['REQUEST_METHOD'] = 'GET';

@file_put_contents(__DIR__ . '/debug_export.log', "DEBUG: before including index.php\n", FILE_APPEND);
require_once 'index.php';
@file_put_contents(__DIR__ . '/debug_export.log', "DEBUG: after including index.php\n", FILE_APPEND);

?>

<?php
// Simulate POST submission of a new excuse request by a logged-in student
$_SERVER['REQUEST_URI'] = '/student/excuseRequests/create';
$_GET['url'] = 'student/excuseRequests/create';
$_SERVER['REQUEST_METHOD'] = 'POST';

// Ensure environment
if (!isset($_ENV['APP_ENV'])) {
    $_ENV['APP_ENV'] = 'development';
}

// Start session and set user as student
session_start();
$_SESSION['user_id'] = 2; // adjust to a real user id if needed
$_SESSION['user_role'] = 'student';

// Provide POST data
$_POST['schedule_id'] = 1; // adjust to an existing schedule id
$_POST['date'] = date('Y-m-d');
$_POST['reason'] = 'Test excuse request submitted via runner.';

require 'index.php';

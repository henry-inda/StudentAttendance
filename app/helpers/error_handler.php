<?php

require_once 'app/models/SystemLog.php';
// require_once 'app/helpers/session_helper.php'; // Removed this line

function custom_error_handler($errno, $errstr, $errfile, $errline) {
    // Log the error
    $systemLog = new SystemLog();
    $user_id = is_logged_in() ? get_session('user_id') : null;
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    $details = "Error: [$errno] $errstr in $errfile on line $errline";
    $systemLog->log($user_id, 'PHP Error', $details, $ip_address);

    // Display user-friendly error page based on error type
    // For now, we'll just show a generic 500 error page for all PHP errors
    http_response_code(500);
    require_once 'views/errors/500.php';
    exit();
}

function custom_exception_handler($exception) {
    // Log the exception
    $systemLog = new SystemLog();
    $user_id = is_logged_in() ? get_session('user_id') : null;
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    $details = "Uncaught Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
    $systemLog->log($user_id, 'PHP Exception', $details, $ip_address);

    // Display user-friendly error page
    http_response_code(500);
    require_once 'views/errors/500.php';
    exit();
}

// Set custom error and exception handlers only in non-development environments
// In development we prefer the native PHP error display to see full traces
if (!isset($_ENV['APP_ENV']) || $_ENV['APP_ENV'] !== 'development') {
    set_error_handler('custom_error_handler');
    set_exception_handler('custom_exception_handler');
}

function show_404() {
    http_response_code(404);
    require_once 'views/errors/404.php';
    exit();
}

function show_403() {
    http_response_code(403);
    require_once 'views/errors/403.php';
    exit();
}

function show_500() {
    http_response_code(500);
    require_once 'views/errors/500.php';
    exit();
}
<?php
// Custom Error Handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $log_file = __DIR__ . '/logs/error.log';
    $timestamp = date('Y-m-d H:i:s');
    $error_message = "[$timestamp] Error: [$errno] $errstr in $errfile on line $errline\n";
    file_put_contents($log_file, $error_message, FILE_APPEND);
    // Return true to prevent the default PHP error handler from running
    return true;
});

ob_start();
require_once 'app/config/config.php'; // Include config.php first
require_once 'app/core/Database.php'; // Include Database.php early

// If APP_ENV is not provided by the server, default to development for local debugging
if (!isset($_ENV['APP_ENV'])) {
    $_ENV['APP_ENV'] = 'development';
}

// Define paths
define('ROOT', dirname(__FILE__));
define('APP', ROOT . '/app');
define('VIEWS', ROOT . '/views');
define('ASSETS', ROOT . '/assets');
define('UPLOADS', ROOT . '/uploads');

// Set timezone (default)
date_default_timezone_set('Africa/Nairobi');

// Error reporting (development mode)
if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Force display of errors for debugging when running under a local dev server / Apache.
// REMOVE or revert this in production.
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Bootstrap helpers (loads session_helper first). Error handler is included after session start.
require_once 'app/helpers/helpers_bootstrap.php';

// Start session (session_helper provides start_session())
start_session(); // Start session immediately

// Include error handler after session is started
require_once 'app/helpers/error_handler.php';

// Set $_GET['url'] for routing if not already set (e.g., for PHP built-in server)
if (!isset($_GET['url'])) {
    $requestUri = $_SERVER['REQUEST_URI'];
    // Normalize multiple leading slashes to a single slash
    $requestUri = preg_replace('#/+#', '/', $requestUri); // Add this line
    error_log('index.php: $requestUri (normalized) = ' . $requestUri); // Debugging line
    $parsedUrl = parse_url($requestUri); // Parse the URL
    error_log('index.php: $parsedUrl[path] = ' . ($parsedUrl['path'] ?? 'NULL')); // Debugging line
    $_GET['url'] = ltrim($parsedUrl['path'] ?? '', '/'); // Get only the path component
}

// If root is requested, redirect to login page to provide a friendly default
$cleanUrl = trim($_GET['url'] ?? '', '/');
if ($cleanUrl === '') {
    header('Location: /auth/login');
    exit();
}

require_once 'app/core/App.php';
require_once 'app/core/Controller.php';

// Instantiate App
$app = new App();
ob_end_flush();

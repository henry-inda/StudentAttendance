<?php
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function validate_role($role) {
    return in_array($role, ['admin', 'lecturer', 'student']);
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function check_csrf_token() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed.');
    }
}

function rate_limit_check($action, $limit, $period = 300) { // 300 seconds = 5 minutes
    $ip = $_SERVER['REMOTE_ADDR'];
    $key = 'rate_limit_' . $action . '_' . $ip;

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [];
    }

    // Remove old requests
    $_SESSION[$key] = array_filter($_SESSION[$key], function($timestamp) use ($period) {
        return (time() - $timestamp) < $period;
    });

    if (count($_SESSION[$key]) >= $limit) {
        return false; // Rate limit exceeded
    }

    $_SESSION[$key][] = time();
    return true; // Request allowed
}

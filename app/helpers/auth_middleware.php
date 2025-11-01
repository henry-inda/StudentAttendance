<?php
// auth_middleware.php - rely on `auth_helper.php` for authentication functions

function require_auth() {
    if (!is_logged_in()) {
        redirect('auth/login');
    }
}

function check_role($allowed_roles) {
    if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], (array)$allowed_roles)) {
        // Redirect to a generic dashboard or an error page
        show_403(); // Use show_403 for unauthorized access
    }
}

function redirect($url) {
    header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/') . $url);
    exit();
}
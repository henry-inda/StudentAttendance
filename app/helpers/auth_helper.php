<?php

// app/helpers/auth_helper.php

/**
 * Check if a user is currently logged in
 * @return bool True if user is logged in, false otherwise
 */
function is_logged_in()
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get the current logged in user's ID
 * @return int|null User ID if logged in, null otherwise
 */
function get_current_user_id()
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get the current user's role
 * @return string|null User role if logged in, null otherwise
 */
function get_user_role()
{
    return $_SESSION['user_role'] ?? null;
}

/**
 * Check if current user has specific role
 * @param string $role Role to check
 * @return bool True if user has role, false otherwise
 */
function has_role($role)
{
    return get_user_role() === $role;
}

/**
 * Require user to be logged in to access page
 * Redirects to login if not authenticated
 */
function require_login()
{
    if (!is_logged_in()) {
        $_SESSION['error'] = "Please log in to access this page";
        header('Location: /auth/login');
        exit;
    }
}

/**
 * Require specific role to access page
 * Redirects to appropriate page if unauthorized
 * @param string|array $allowed_roles Single role or array of roles
 */
function require_role($allowed_roles)
{
    require_login();
    
    $allowed_roles = (array)$allowed_roles;
    if (!in_array(get_user_role(), $allowed_roles)) {
        $_SESSION['error'] = "You do not have permission to access this page";
        header('Location: /' . get_user_role() . '/dashboard');
        exit;
    }
}

/**
 * Set user session data after successful login
 * @param array $user User data from database
 */
function login_user($user)
{
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
}

/**
 * Clear all session data and destroy session
 */
function logout_user()
{
    session_unset();
    session_destroy();
    header('Location: /auth/login');
    exit;
}

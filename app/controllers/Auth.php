<?php
class Auth extends Controller {
    public function __construct() {
        // Helpers are not automatically loaded, so we need to require them
        require_once 'app/helpers/session_helper.php';
        require_once 'app/helpers/auth_middleware.php';
        $this->userModel = $this->model('User');
    }

    public function login() {
        // Display login form
        $this->view('auth/login');
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Removed filter_input_array for debugging purposes
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];

            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }

            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/email
            $userFound = $this->userModel->findByEmail($data['email']);
            if ($userFound) {
                // User found
            } else {
                $data['email_err'] = 'No user found';
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    // Create session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }
        } else {
            // Not a POST request
            $this->view('auth/login');
        }
    }

    public function createUserSession($user) {
        start_session();
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->full_name;
        $_SESSION['user_role'] = $user->role;
        
        // Redirect based on role
        switch($user->role) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'lecturer':
                redirect('lecturer/dashboard');
                break;
            case 'student':
                redirect('student/dashboard');
                break;
            default:
                redirect('auth/login');
        }
    }

    public function logout() {
        start_session();
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        destroy_session();
        redirect('auth/login');
    }

    public function forgot_password() {
        // For future enhancement
        $this->view('auth/forgot_password');
    }
}
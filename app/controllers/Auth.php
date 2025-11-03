<?php
class Auth extends Controller {
    public function __construct() {
        // Helpers are not automatically loaded, so we need to require them
        require_once 'app/helpers/session_helper.php';
        require_once 'app/helpers/auth_middleware.php';
        require_once 'app/helpers/email_helper.php';
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
                header('Location: ' . BASE_URL . 'admin/dashboard');
                exit;
                break;
            case 'lecturer':
                header('Location: ' . BASE_URL . 'lecturer/dashboard');
                exit;
                break;
            case 'student':
                header('Location: ' . BASE_URL . 'student/dashboard');
                exit;
                break;
            default:
                header('Location: ' . BASE_URL . 'auth/login');
                exit;
        }
    }

    public function logout() {
        start_session();
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        destroy_session();
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }

    public function forgot_password() {
        // For future enhancement
        $this->view('auth/forgot_password');
    }

    public function requestPasswordReset() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $token = $this->userModel->generatePasswordResetToken($email);

            if ($token) {
                $reset_link = BASE_URL . 'auth/resetPassword/' . $token;
                $subject = 'Password Reset Request';
                $body = 'Please click on the following link to reset your password: <a href="' . $reset_link . '">' . $reset_link . '</a>';

                if (send_email($email, $subject, $body)) {
                    flash_message('success', 'A password reset link has been sent to your email address.');
                    header('Location: ' . BASE_URL . 'auth/login');
                    exit;
                } else {
                    flash_message('forgot_password_fail', 'Failed to send password reset email. Please try again.');
                    header('Location: ' . BASE_URL . 'auth/forgot_password');
                    exit;
                }
            } else {
                flash_message('forgot_password_fail', 'No user found with that email address.');
                header('Location: ' . BASE_URL . 'auth/login');
                exit;
            }
        }
    }



    public function resetPassword($token = '') {
        if (empty($token)) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        $user = $this->userModel->findUserByPasswordResetToken($token);

        if ($user) {
            $data = [
                'token' => $token
            ];
            $this->view('auth/reset_password', $data);
        } else {
            flash_message('forgot_password_fail', 'Invalid or expired password reset token.');
            header('Location: ' . BASE_URL . 'auth/forgot_password');
            exit;
        }
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = $_POST['token'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

                        if ($password !== $confirm_password) {

                            flash_message('reset_password_fail', 'Passwords do not match.');

                            header('Location: ' . BASE_URL . 'auth/resetPassword/' . $token);

                            exit;

                        }

            

                        if ($this->userModel->resetPasswordWithToken($token, $password)) {

                            flash_message('success', 'Your password has been reset successfully. You can now login.');

                            header('Location: ' . BASE_URL . 'auth/login');

                            exit;

                        } else {

                            flash_message('reset_password_fail', 'Failed to reset password. Please try again.');

                            header('Location: ' . BASE_URL . 'auth/resetPassword/' . $token);

                            exit;

                        }
        }
    }
}
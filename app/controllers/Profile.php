<?php
class Profile extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        require_once 'app/helpers/session_helper.php';
        if (!is_logged_in()) {
            redirect('auth/login');
        }
        $this->userModel = $this->model('User');
    }

    public function index() {
        $user = $this->userModel->findById(get_session('user_id'));
        $data = [
            'title' => 'My Profile',
            'user' => $user
        ];
        $this->view('shared/profile/index', $data);
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'full_name' => trim($_POST['full_name']),
                'phone' => trim($_POST['phone']),
            ];

            if ($this->userModel->updateProfile(get_session('user_id'), $data)) {
                // Update session with new name
                $user = $this->userModel->findById(get_session('user_id'));
                set_session('user_name', $user->full_name);

                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'user' => $user]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false]);
            }
        } else {
            $user = $this->userModel->findById(get_session('user_id'));
            $data = [
                'title' => 'Edit Profile',
                'user' => $user
            ];
            $this->view('shared/profile/edit', $data);
        }
    }

    public function change_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $current_password = trim($_POST['current_password']);
            $new_password = trim($_POST['new_password']);
            $confirm_password = trim($_POST['confirm_password']);
            
            // Get current user
            $user = $this->userModel->findById(get_session('user_id'));
            
            // Verify current password
            if (!password_verify($current_password, $user->password)) {
                flash_message('password_error', 'Current password is incorrect');
                redirect('profile/change_password');
                return;
            }
            
            // Validate new password
            if ($new_password !== $confirm_password) {
                flash_message('password_error', 'New passwords do not match');
                redirect('profile/change_password');
                return;
            }
            
            // Update password
            if ($this->userModel->updatePassword(get_session('user_id'), $new_password)) {
                flash_message('success', 'Password updated successfully');
                redirect('profile');
            } else {
                flash_message('password_error', 'Failed to update password. Please ensure your new password meets the strength requirements.');
                redirect('profile/change_password');
            }
        } else {
            $this->view('shared/profile/change_password');
        }
    }

    public function upload_picture() {
        // Logic to upload profile picture
        redirect('profile');
    }
}

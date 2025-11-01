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
                'profile_picture' => '' // To be handled by upload_picture
            ];
            if ($this->userModel->updateProfile(get_session('user_id'), $data)) {
                redirect('profile');
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
            // Logic to change password
            redirect('profile');
        } else { // Correctly placed else
            $this->view('shared/profile/change_password');
        }
    }

    public function upload_picture() {
        // Logic to upload profile picture
        redirect('profile');
    }
}

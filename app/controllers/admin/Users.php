<?php
class Users extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['admin']);
        $this->userModel = $this->model('User');
    }

    public function index() {
        $users = $this->userModel->getByRole('student'); // Default to students
        $data = [
            'title' => 'Users Management',
            'users' => $users
        ];
        $this->view('admin/users/index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $data = [
                'full_name' => trim($_POST['full_name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'role' => trim($_POST['role']),
                'department_id' => trim($_POST['department_id']),
                'phone' => trim($_POST['phone'])
            ];

            if ($this->userModel->create($data)) {
                redirect('admin/users');
            } else {
                // Handle error
                $this->view('admin/users/add', $data);
            }
        } else {
            // Display form
            $this->view('admin/users/add');
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Update user
        } else {
            // Display form
            $user = $this->userModel->findById($id);
            $data = ['user' => $user];
            $this->view('admin/users/edit', $data);
        }
    }

    public function delete($id) {
        // Soft delete user
        if ($this->userModel->delete($id)) {
            // Redirect
        }
    }

    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process CSV
        } else {
            // Display form
            $this->view('admin/users/upload');
        }
    }

    public function downloadTemplate() {
        // Download CSV template
    }
}

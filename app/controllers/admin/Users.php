<?php
class Users extends Controller {
    private $userModel;
    
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['admin']);
        $this->userModel = $this->model('User');
    }

    public function index() {
        $students = $this->userModel->getByRole('student');
        $lecturers = $this->userModel->getByRole('lecturer');
        $data = [
            'title' => 'Users Management',
            'students' => $students,
            'lecturers' => $lecturers
        ];
        $this->view('admin/users/index', $data);
    }

    public function add() {
        require_once APP . '/helpers/validation_helper.php';

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

            // Server-side validation
            $required = ['full_name','email','password','role'];
            $errors = validate_required($required, $data);

            if (!vh_validate_email($data['email'])) {
                $errors['email'] = 'Please provide a valid email address.';
            } elseif (!$this->userModel->findByEmail($data['email'])) {
                // ok
            } else {
                $errors['email'] = 'Email already exists.';
            }

            if (!vh_validate_password_strength($data['password'])) {
                $errors['password'] = 'Password does not meet strength requirements.';
            }

            if (!empty($errors)) {
                $data['errors'] = $errors;
                $this->view('admin/users/add', $data);
                return;
            }

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
        require_once APP . '/helpers/validation_helper.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $data = [
                'id' => $id,
                'full_name' => trim($_POST['full_name']),
                'email' => trim($_POST['email']),
                'role' => trim($_POST['role']),
                'phone' => isset($_POST['phone']) ? trim($_POST['phone']) : null,
                'status' => trim($_POST['status'])
            ];

            // Conditionally set department_id or course_id based on role
            if ($data['role'] == 'student') {
                $data['course_id'] = isset($_POST['course_id']) ? trim($_POST['course_id']) : null;
                $data['department_id'] = null;
            } else {
                $data['department_id'] = isset($_POST['department_id']) ? trim($_POST['department_id']) : null;
                $data['course_id'] = null;
            }

            // Optional password update
            if (!empty($_POST['password'])) {
                $data['password'] = trim($_POST['password']);
            }

            // Server-side validation
            $required = ['full_name', 'email', 'role'];
            $errors = validate_required($required, $data);

            // Email validation
            if (!vh_validate_email($data['email'])) {
                $errors['email'] = 'Please provide a valid email address.';
            } else {
                // Check if email exists but belongs to a different user
                $existingUser = $this->userModel->findByEmail($data['email']);
                if ($existingUser && $existingUser->id != $id) {
                    $errors['email'] = 'Email already exists for another user.';
                }
            }

            // Password validation if provided
            if (!empty($data['password'])) {
                if (!vh_validate_password_strength($data['password'])) {
                    $errors['password'] = 'Password does not meet strength requirements.';
                }
            }

            if (!empty($errors)) {
                $data['errors'] = $errors;
                $data['user'] = (object)$data; // Convert to object for view compatibility
                $this->view('admin/users/edit', $data);
                return;
            }

            if ($this->userModel->update($data)) {
                flash_message('success', 'User updated successfully');
                redirect('admin/users');
            } else {
                flash_message('error', 'Error updating user');
                $data['user'] = (object)$data; // Convert to object for view compatibility
                $this->view('admin/users/edit', $data);
            }
        } else {
            // Display form
            $user = $this->userModel->findById($id);
            if (!$user) {
                flash_message('error', 'User not found');
                redirect('admin/users');
                return;
            }

            // Load departments and courses
            $deptModel = $this->model('Department');
            $departments = $deptModel->getAll();
            
            $courseModel = $this->model('Course');
            $courses = $courseModel->getAll();
            
            $data = [
                'user' => $user,
                'departments' => $departments,
                'courses' => $courses
            ];
            $this->view('admin/users/edit', $data);
        }
    }

    public function delete($id) {
        // Soft delete user
        if ($this->userModel->delete($id)) {
            flash_message('success', 'User deleted successfully');
            redirect('admin/users');
        } else {
            flash_message('error', 'Error deleting user');
            redirect('admin/users');
        }
    }

    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
                $file = $_FILES['csv_file']['tmp_name'];
                $handle = fopen($file, "r");
                
                // Skip the header row
                fgetcsv($handle);

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $userData = [
                        'email' => $data[0],
                        'password' => $data[1],
                        'full_name' => $data[2],
                        'role' => $data[3],
                        'department_id' => $data[4],
                        'course_id' => $data[5],
                        'phone' => $data[6],
                        'status' => $data[7]
                    ];
                    $this->userModel->createFromCsv($userData);
                }
                fclose($handle);
                flash_message('upload_success', 'Users uploaded successfully.');
                redirect('admin/users');
            } else {
                flash_message('upload_error', 'Error uploading file.', 'alert-danger');
                redirect('admin/users/upload');
            }
        } else {
            // Display form
            $this->view('admin/users/upload');
        }
    }

    public function downloadTemplate() {
        // Download CSV template
    }
}

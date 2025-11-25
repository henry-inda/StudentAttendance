<?php
class Profile extends Controller {
    private $userModel;
    
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
        require_once APP . '/helpers/validation_helper.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'full_name' => trim($_POST['full_name']),
                'phone' => trim($_POST['phone']),
                'profile_picture' => null
            ];

            // Validate inputs
            $errors = [];
            if (empty($data['full_name'])) {
                $errors['full_name'] = 'Name is required.';
            }
            
            if (!empty($_POST['phone']) && !vh_validate_phone($_POST['phone'])) {
                $errors['phone'] = 'Please enter a valid phone number.';
            }

            // Handle profile picture upload if present
            if (!empty($_FILES['profile_picture']['name'])) {
                if (!vh_validate_uploaded_file($_FILES['profile_picture'], ['image/jpeg', 'image/png'])) {
                    $errors['profile_picture'] = 'Invalid image file. Please upload a JPG or PNG file under 2MB.';
                } else {
                    // Generate unique filename
                    $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                    $filename = 'profile_' . get_session('user_id') . '_' . time() . '.' . $ext;
                    $target = UPLOADS_PATH . '/profiles/' . $filename;

                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
                        $data['profile_picture'] = 'profiles/' . $filename;
                    } else {
                        $errors['profile_picture'] = 'Failed to upload image. Please try again.';
                    }
                }
            }

            // If requested as AJAX, send JSON response
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

            if (!empty($errors)) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'errors' => $errors]);
                    exit;
                } else {
                    $data['errors'] = $errors;
                    $data['user'] = (object)$data;
                    $this->view('shared/profile/edit', $data);
                    return;
                }
            }

            if ($this->userModel->updateProfile(get_session('user_id'), $data)) {
                // Update session with new name
                $user = $this->userModel->findById(get_session('user_id'));
                set_session('user_name', $user->full_name);

                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'message' => 'Profile updated successfully',
                        'redirect' => BASE_URL . 'profile'
                    ]);
                    exit;
                } else {
                    flash_message('success', 'Profile updated successfully');
                    redirect('profile');
                }
            } else {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to update profile'
                    ]);
                    exit;
                } else {
                    flash_message('error', 'Failed to update profile');
                    redirect('profile/edit');
                }
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

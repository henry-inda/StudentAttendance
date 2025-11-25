<?php
class Requests extends Controller {
    private $accountRequestModel;
    private $userModel;

    public function __construct() {
        $this->accountRequestModel = $this->model('AccountRequest');
        $this->userModel = $this->model('User');
    }

    public function index() {
        $requests = $this->accountRequestModel->getPendingRequests();
        $data = [
            'requests' => $requests
        ];
        $this->view('admin/requests/index', $data);
    }

    public function show($notification_id, $request_id) {
        $notificationModel = $this->model('Notification');
        $notificationModel->markAsRead($notification_id);

        $request = $this->accountRequestModel->getRequestById($request_id);

        if ($request) {
            $data = [
                'request' => $request
            ];
            $this->view('admin/requests/view', $data);
        } else {
            redirect('admin/requests');
        }
    }

    public function approve($id) {
        $request = $this->accountRequestModel->getRequestById($id);

        if ($request) {
            // Check if user already exists
            if ($this->userModel->findByEmail($request->email)) {
                flash_message('request_approve_fail', 'A user with this email address already exists.');
                header('Location: ' . BASE_URL . 'admin/requests');
                exit;
            }

            $password = 'Attendance123'; // Default password
            $data = [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => $password,
                'role' => $request->role,
                'department_id' => null,
                'course_id' => null,
                'phone' => ''
            ];

            if ($request->role == 'lecturer') {
                $department_id = $this->getDepartmentId($request->department);
                if (is_null($department_id)) {
                    flash_message('request_approve_fail', 'Invalid department name provided in the request.');
                    header('Location: ' . BASE_URL . 'admin/requests');
                    exit;
                }
                $data['department_id'] = $department_id;
            }

            if ($request->role == 'student') {
                $course_id = $this->getCourseId($request->course);
                if (is_null($course_id)) {
                    flash_message('request_approve_fail', 'Invalid course name provided in the request.');
                    header('Location: ' . BASE_URL . 'admin/requests');
                    exit;
                }
                $data['course_id'] = $course_id;
            }

            if ($this->userModel->create($data)) {
                $this->accountRequestModel->approveRequest($id);

                // Log activity
                $this->model('SystemLog')->logActivity($_SESSION['user_id'], 'Account Approved', 'Approved account for ' . $request->email, $_SERVER['REMOTE_ADDR']);

                // Send email to user
                $subject = 'Account Approved';
                $login_link = BASE_URL . 'auth/login';
                $body = 'Your account has been approved. You can now <a href="' . $login_link . '">LOGIN</a> with the following credentials:<br>Email: ' . $request->email . '<br>Password: ' . $password . '<br>Please change your password after logging in.';
                send_email($request->email, $subject, $body);

                flash_message('request_approve_success', 'Account request approved successfully.');
                header('Location: ' . BASE_URL . 'admin/requests');
                exit;
            }
        }

        flash_message('request_approve_fail', 'Failed to approve account request.');
        header('Location: ' . BASE_URL . 'admin/requests');
        exit;
    }

    public function reject($id) {
        $request = $this->accountRequestModel->getRequestById($id);
        if ($this->accountRequestModel->rejectRequest($id)) {
            // Log activity
            $this->model('SystemLog')->logActivity($_SESSION['user_id'], 'Account Rejected', 'Rejected account for ' . $request->email, $_SERVER['REMOTE_ADDR']);

            // Send email to user
            $subject = 'Account Rejected';
            $body = 'Your account request has been rejected.';
            send_email($request->email, $subject, $body);

            flash_message('request_reject_success', 'Account request rejected successfully.');
            header('Location: ' . BASE_URL . 'admin/requests');
            exit;
        }

        flash_message('request_reject_fail', 'Failed to reject account request.');
        header('Location: ' . BASE_URL . 'admin/requests');
        exit;
    }

    private function getCourseId($course_name) {
        $courseModel = $this->model('Course');
        $course = $courseModel->findByName($course_name);
        if ($course) {
            return $course->id;
        }
        return null;
    }

    private function getDepartmentId($department_name) {
        // This is a placeholder. You should implement a proper way to get department id from department name.
        // For now, let's assume department name is the id.
        $departmentModel = $this->model('Department');
        $department = $departmentModel->findByName($department_name);
        if ($department) {
            return $department->id;
        }
        return null;
    }
}

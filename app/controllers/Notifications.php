<?php
class Notifications extends Controller {
    private $notificationModel;

    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        require_once 'app/helpers/session_helper.php';
        if (!is_logged_in()) {
            redirect('auth/login');
        }
        $this->notificationModel = $this->model('Notification');
    }

    public function index() {
        $notifications = $this->notificationModel->getByUser(get_session('user_id'));
        $data = [
            'title' => 'My Notifications',
            'notifications' => $notifications
        ];
        $this->view('shared/notifications', $data);
    }

    public function mark_read($id) {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if ($this->notificationModel->markAsRead($id)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                return;
            } else {
                redirect('notifications');
            }
        } else {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false]);
                return;
            } else {
                redirect('notifications');
            }
        }
    }

    public function mark_all_read() {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if ($this->notificationModel->markAllAsRead(get_session('user_id'))) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                return;
            } else {
                redirect('notifications');
            }
        } else {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false]);
                return;
            } else {
                redirect('notifications');
            }
        }
    }

    public function get_new() {
        header('Content-Type: application/json');
        $response = ['success' => false, 'notifications' => [], 'error' => null];

        if (!is_logged_in()) {
            $response['error'] = 'Not authenticated';
        } else {
            try {
                $notifications = $this->notificationModel->getByUser(get_session('user_id'), true);
                $response['success'] = true;
                $response['notifications'] = $notifications;
            } catch (Exception $e) {
                $response['error'] = $e->getMessage();
            }
        }

        echo json_encode($response);
    }

    public function show($id) {
        if (!is_logged_in()) {
            redirect('auth/login');
        }

        $notification = $this->notificationModel->getById($id);

        if ($notification && $notification->user_id == get_session('user_id')) {
            // Mark as read
            $this->notificationModel->markAsRead($id);

            // Determine redirect URL
            $redirect_url = '';
            switch ($notification->type) {
                case 'account_request':
                    $redirect_url = 'admin/requests/show/' . $id . '/' . $notification->related_id;
                    break;
                case 'excuse_request':
                    $redirect_url = 'lecturer/excuse_requests/show/' . $notification->related_id;
                    break;
                case 'new_unit_enrolment':
                    $redirect_url = 'student/myschedule/list';
                    break;
                default:
                    $redirect_url = 'notifications'; // Fallback to notifications list
                    break;
            }
            redirect($redirect_url);
        } else {
            // Notification not found or doesn't belong to the user
            flash_message('error', 'Notification not found.');
            redirect('notifications');
        }
    }
}
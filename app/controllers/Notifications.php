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
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        // Default response
        $response = ['success' => false, 'notifications' => [], 'error' => null];

        // Check session
        if (!is_logged_in()) {
            $response['error'] = 'Not authenticated';
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }

        try {
            $notifications = $this->notificationModel->getByUser(get_session('user_id'), true);
            $response['success'] = true;
            $response['notifications'] = $notifications;
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
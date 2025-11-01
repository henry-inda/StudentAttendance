<?php
class Notifications extends Controller {
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
        if ($this->notificationModel->markAsRead($id)) {
            redirect('notifications');
        }
    }

    public function mark_all_read() {
        if ($this->notificationModel->markAllAsRead(get_session('user_id'))) {
            redirect('notifications');
        }
    }
}
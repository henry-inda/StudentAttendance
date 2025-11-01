<?php
class Controller {
    public function model($model) {
        require_once 'app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        // Include Notification model and session helper
        require_once 'app/models/Notification.php';
        require_once 'app/helpers/session_helper.php';

        // Get unread notification count and recent notifications if user is logged in
        $unreadNotificationCount = 0;
        $recentNotifications = [];
        if (is_logged_in()) {
            $notificationModel = new Notification();
            $unreadNotificationCount = $notificationModel->getUnreadCount(get_session('user_id'));
            // Assuming getByUser can take a limit parameter or we can slice the result
            $allNotifications = $notificationModel->getByUser(get_session('user_id'));
            $recentNotifications = array_slice($allNotifications, 0, 5); // Get top 5
        }

        // Merge common data into the view data
        $data['unread_notification_count'] = $unreadNotificationCount;
        $data['recent_notifications'] = $recentNotifications;

        if (file_exists('views/' . $view . '.php')) {
            require_once 'views/' . $view . '.php';
        } else {
            die('View does not exist.');
        }
    }
}

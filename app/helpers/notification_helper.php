<?php
require_once APP . '/models/Notification.php';

function notify_user($user_id, $type, $title, $message) {
    $notificationModel = new Notification();
    return $notificationModel->create($user_id, $type, $title, $message);
}

function notify_role($role, $type, $title, $message) {
    $userModel = new User(); // Assuming User model is available
    $users = $userModel->getByRole($role);
    $notificationModel = new Notification();
    $success = true;
    foreach ($users as $user) {
        if (!$notificationModel->create($user->id, $type, $title, $message)) {
            $success = false;
        }
    }
    return $success;
}

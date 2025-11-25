<?php
require_once APP . '/models/Notification.php';
require_once APP . '/models/User.php';

// Notification types
define('NOTIFY_EXCUSE_RESPONSE', 'excuse_response');    // Excuse request approved/rejected
define('NOTIFY_EXCUSE_REQUEST', 'excuse_request');      // New excuse request
define('NOTIFY_UPCOMING_CLASS', 'upcoming_class');      // Upcoming class reminder
define('NOTIFY_VENUE_CHANGE', 'venue_change');         // Class venue changed
define('NOTIFY_MARKED_ABSENT', 'marked_absent');       // Student marked absent
define('NOTIFY_CLASS_CANCELLED', 'class_cancelled');   // Class cancelled
define('NOTIFY_GENERAL', 'general');                   // General announcements

/**
 * Send notification to a specific user
 */
function notify_user($user_id, $type, $title, $message, $related_id = null) {
    $notificationModel = new Notification();
    return $notificationModel->create($user_id, $type, $title, $message, $related_id);
}

/**
 * Send notification to all users with a specific role
 */
function notify_role($role, $type, $title, $message, $related_id = null) {
    $userModel = new User();
    $users = $userModel->getByRole($role);
    $notificationModel = new Notification();
    $success = true;
    foreach ($users as $user) {
        if (!$notificationModel->create($user->id, $type, $title, $message, $related_id)) {
            $success = false;
        }
    }
    return $success;
}

/**
 * Notify student about excuse request response
 */
function notify_excuse_response($student_id, $status, $reason, $unit_name, $excuse_id) {
    $title = "Excuse Request " . ucfirst($status);
    $message = "Your excuse request for {$unit_name} has been {$status}." . 
               ($reason ? " Reason: {$reason}" : "");
    return notify_user($student_id, NOTIFY_EXCUSE_RESPONSE, $title, $message, $excuse_id);
}

/**
 * Notify lecturer about new excuse request
 */
function notify_excuse_request($lecturer_id, $student_name, $unit_name, $date, $excuse_id) {
    $title = "New Excuse Request";
    $message = "{$student_name} has submitted an excuse request for {$unit_name} on {$date}";
    return notify_user($lecturer_id, NOTIFY_EXCUSE_REQUEST, $title, $message, $excuse_id);
}

/**
 * Notify about upcoming class
 */
function notify_upcoming_class($user_id, $unit_name, $time, $venue, $schedule_id) {
    $title = "Upcoming Class Reminder";
    $message = "You have {$unit_name} class at {$time} in {$venue}";
    return notify_user($user_id, NOTIFY_UPCOMING_CLASS, $title, $message, $schedule_id);
}

/**
 * Notify about venue change
 */
function notify_venue_change($user_id, $unit_name, $new_venue, $date, $schedule_id) {
    $title = "Class Venue Changed";
    $message = "The venue for {$unit_name} on {$date} has been changed to {$new_venue}";
    return notify_user($user_id, NOTIFY_VENUE_CHANGE, $title, $message, $schedule_id);
}

/**
 * Notify student about being marked absent
 */
function notify_marked_absent($student_id, $unit_name, $date, $notes, $attendance_id) {
    $title = "Marked Absent";
    $message = "You have been marked absent for {$unit_name} on {$date}" . 
               ($notes ? ". Notes: {$notes}" : "");
    return notify_user($student_id, NOTIFY_MARKED_ABSENT, $title, $message, $attendance_id);
}

/**
 * Notify about cancelled class
 */
function notify_class_cancelled($user_id, $unit_name, $date, $reason, $schedule_id) {
    $title = "Class Cancelled";
    $message = "{$unit_name} class on {$date} has been cancelled" . 
               ($reason ? ". Reason: {$reason}" : "");
    return notify_user($user_id, NOTIFY_CLASS_CANCELLED, $title, $message, $schedule_id);
}

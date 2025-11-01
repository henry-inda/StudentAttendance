<?php
require_once 'email_helper.php';
require_once 'notification_helper.php';
require_once APP . '/models/User.php';
require_once APP . '/models/Unit.php';
require_once APP . '/models/StudentEnrollment.php';
require_once APP . '/models/SystemSetting.php';
require_once APP . '/models/ScheduleModel.php'; // Updated to ScheduleModel

function check_low_attendance($student_id, $unit_id) {
    global $userModel, $unitModel, $systemSettingModel; // Use global if models are not instantiated within the function directly
    if (!isset($userModel)) $userModel = new User();
    if (!isset($unitModel)) $unitModel = new Unit();
    if (!isset($systemSettingModel)) $systemSettingModel = new SystemSetting();

    $threshold = $systemSettingModel->get('attendance_threshold');
    // Placeholder for actual attendance calculation
    $attendance_percentage = 70; // Dummy value

    if ($attendance_percentage < $threshold) {
        return $attendance_percentage;
    }
    return false;
}

function send_absence_notification($student_id, $unit_id, $date) {
    global $userModel, $unitModel;
    if (!isset($userModel)) $userModel = new User();
    if (!isset($unitModel)) $unitModel = new Unit();

    $student = $userModel->findById($student_id);
    $unit = $unitModel->findById($unit_id);

    if ($student && $unit) {
        $to = $student->email;
        $subject = 'Absence Recorded - ' . $unit->unit_name;
        $body = file_get_contents(BASE_URL . 'assets/templates/emails/absence_notification.html');
        $body = str_replace('[Unit]', $unit->unit_name, $body);
        $body = str_replace('[Date]', $date, $body);
        send_email($to, $subject, $body);
    }
}

function send_low_attendance_warning($student_id, $unit_id, $percentage) {
    global $userModel, $unitModel, $systemSettingModel;
    if (!isset($userModel)) $userModel = new User();
    if (!isset($unitModel)) $unitModel = new Unit();
    if (!isset($systemSettingModel)) $systemSettingModel = new SystemSetting();

    $student = $userModel->findById($student_id);
    $unit = $unitModel->findById($unit_id);
    $threshold = $systemSettingModel->get('attendance_threshold');

    if ($student && $unit) {
        $to = $student->email;
        $subject = 'Attendance Warning - ' . $unit->unit_name;
        $body = file_get_contents(BASE_URL . 'assets/templates/emails/low_attendance.html');
        $body = str_replace('[Unit]', $unit->unit_name, $body);
        $body = str_replace('[X]', $percentage, $body);
        $body = str_replace('[Threshold]', $threshold, $body);
        send_email($to, $subject, $body);
    }
}

function notify_schedule_change($unit_id, $old_data, $new_data) {
    global $unitModel, $studentEnrollmentModel, $userModel;
    if (!isset($unitModel)) $unitModel = new Unit();
    if (!isset($studentEnrollmentModel)) $studentEnrollmentModel = new StudentEnrollment();
    if (!isset($userModel)) $userModel = new User();

    $unit = $unitModel->findById($unit_id);
    // This needs to fetch students enrolled in this unit, not just by course
    // Assuming a method like getEnrolledStudentsByUnit exists in StudentEnrollment model
    $enrolledStudents = []; // Placeholder

    if ($unit && $enrolledStudents) {
        foreach ($enrolledStudents as $enrollment) {
            $student = $userModel->findById($enrollment->student_id);
            if ($student) {
                $to = $student->email;
                $subject = 'Schedule Change for ' . $unit->unit_name;
                $body = file_get_contents(BASE_URL . 'assets/templates/emails/schedule_change.html');
                $body = str_replace('[Unit Name]', $unit->unit_name, $body);
                // Replace other placeholders for old_data and new_data
                send_email($to, $subject, $body);
            }
        }
    }
}

function notify_excuse_request_lecturer($student_id, $schedule_id, $date, $reason) {
    global $userModel, $scheduleModel, $unitModel;
    if (!isset($userModel)) $userModel = new User();
    if (!isset($scheduleModel)) $scheduleModel = new ScheduleModel(); // Updated to ScheduleModel
    if (!isset($unitModel)) $unitModel = new Unit();

    $student = $userModel->findById($student_id);
    $schedule = $scheduleModel->findById($schedule_id);
    $lecturer = $userModel->findById($schedule->lecturer_id);
    $unit = $unitModel->findById($schedule->unit_id);

    if ($student && $lecturer && $schedule && $unit) {
        $to = $lecturer->email;
        $subject = 'New Excuse Request from ' . $student->full_name;
        $body = file_get_contents(BASE_URL . 'assets/templates/emails/new_excuse_request.html');
        $body = str_replace('[Student Name]', $student->full_name, $body);
        $body = str_replace('[Unit Name]', $unit->unit_name, $body);
        $body = str_replace('[Date]', $date, $body);
        $body = str_replace('[Reason Preview]', substr($reason, 0, 100) . '...', $body);
        send_email($to, $subject, $body);
    }
}

function notify_excuse_request_student_response($student_id, $status, $unit_name, $date) {
    global $userModel;
    if (!isset($userModel)) $userModel = new User();

    $student = $userModel->findById($student_id);

    if ($student) {
        $to = $student->email;
        $subject = 'Your Excuse Request for ' . $unit_name . ' has been ' . $status;
        $body = '<p>Dear ' . $student->full_name . ',</p>';
        $body .= '<p>Your excuse request for <strong>' . $unit_name . '</strong> on <strong>' . $date . '</strong> has been <strong>' . $status . '</strong>.</p>';
        $body .= '<p>Regards,<br>Student Attendance System</p>';
        send_email($to, $subject, $body);
    }
}

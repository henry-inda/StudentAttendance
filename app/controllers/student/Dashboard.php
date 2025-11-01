<?php
class Dashboard extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['student']);
        $this->enrollmentModel = $this->model('StudentEnrollment');
        $this->unitModel = $this->model('Unit');
        $this->attendanceModel = $this->model('AttendanceModel');
        $this->scheduleModel = $this->model('ScheduleModel');
        $this->excuseRequestModel = $this->model('ExcuseRequest');
        $this->notificationModel = $this->model('Notification');
        $this->systemSettingModel = $this->model('SystemSetting');
    }

    public function index() {
        $student_id = get_session('user_id');

        // Fetch enrolled units and calculate attendance data
        $enrolledUnitsData = [];
        $overallPresent = 0;
        $overallTotal = 0;
        $unitsBelowThreshold = [];

        $enrolledCourses = $this->enrollmentModel->getByStudent($student_id);
        $attendanceThreshold = $this->systemSettingModel->get('attendance_threshold');

        foreach ($enrolledCourses as $enrollment) {
            $courseUnits = $this->unitModel->getByCourse($enrollment->course_id);
            foreach ($courseUnits as $unit) {
                $attendanceStats = $this->attendanceModel->getUnitAttendanceStatsForStudent($student_id, $unit->id);
                $unitAttendancePercentage = $attendanceStats->percentage;

                $enrolledUnitsData[] = [
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->unit_name,
                    'percentage' => $unitAttendancePercentage,
                ];

                $overallPresent += $attendanceStats->present_count;
                $overallTotal += $attendanceStats->total_classes;

                if ($unitAttendancePercentage < $attendanceThreshold) {
                    $unitsBelowThreshold[] = $unit->unit_name;
                }
            }
        }

        $overallAttendancePercentage = ($overallTotal > 0) ? round(($overallPresent / $overallTotal) * 100, 2) : 0;

        // Fetch upcoming classes
        $upcomingClasses = $this->scheduleModel->getUpcomingScheduleForStudent($student_id); // Needs implementation

        // Fetch recent notifications
        $recentNotifications = $this->notificationModel->getByUser($student_id, true, 5); // Use updated getByUser with limit

        $data = [
            'title' => 'Student Dashboard',
            'enrolled_units' => $enrolledUnitsData,
            'overall_attendance_percentage' => $overallAttendancePercentage,
            'units_below_threshold' => $unitsBelowThreshold,
            'upcoming_classes' => $upcomingClasses,
            'recent_notifications' => $recentNotifications
        ];

        $this->view('student/dashboard', $data);
    }
}

<?php
class Dashboard extends Controller {
    private $userModel;
    private $attendanceModel;
    private $scheduleModel;
    private $reportModel;
    private $systemSettingModel;
    private $notificationModel;

    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['student']);
        $this->userModel = $this->model('User');
        $this->attendanceModel = $this->model('AttendanceModel');
        $this->scheduleModel = $this->model('ScheduleModel');
        $this->reportModel = $this->model('Report');
        $this->systemSettingModel = $this->model('SystemSetting');
        $this->notificationModel = $this->model('Notification');
    }

    public function index() {
        $student_id = get_session('user_id');
        $attendanceThreshold = $this->systemSettingModel->get('attendance_threshold');

        $overallAttendance = $this->attendanceModel->getOverallAttendancePercentage($student_id);
        $unitsBelowThreshold = $this->attendanceModel->getUnitsBelowThreshold($student_id, $attendanceThreshold);
        $upcomingClasses = $this->scheduleModel->getUpcomingClassesForStudent($student_id);
        $enrolledUnits = $this->attendanceModel->getEnrolledUnitsWithAttendance($student_id);
        $recentNotifications = $this->notificationModel->getRecentNotifications($student_id, 5);

        $data = [
            'title' => 'Student Dashboard',
            'overall_attendance_percentage' => $overallAttendance,
            'units_below_threshold' => $unitsBelowThreshold,
            'upcoming_classes' => $upcomingClasses,
            'enrolled_units' => $enrolledUnits,
            'recent_notifications' => $recentNotifications,
        ];

        $this->view('student/dashboard', $data);
    }

    public function getAttendanceData() {
        $student_id = get_session('user_id');
        $start_date = date('Y-m-d', strtotime('-30 days'));
        $end_date = date('Y-m-d');

        $attendanceData = $this->reportModel->getStudentAttendanceOverview($student_id, $start_date, $end_date);

        header('Content-Type: application/json');
        echo json_encode($attendanceData);
    }

    public function getUnitAttendanceChartData() {
        $student_id = get_session('user_id');
        $enrolledUnits = $this->attendanceModel->getEnrolledUnitsWithAttendance($student_id);

        $labels = [];
        $data = [];

        foreach ($enrolledUnits as $unit) {
            $labels[] = $unit->unit_code; // Or unit_name, depending on preference
            $data[] = $unit->attendance_percentage;
        }

        header('Content-Type: application/json');
        echo json_encode(['labels' => $labels, 'data' => $data]);
    }
}
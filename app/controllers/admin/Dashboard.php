<?php
class Dashboard extends Controller {
    private $userModel;
    private $departmentModel;
    private $courseModel;
    private $unitModel;
    private $enrollmentModel;
    private $attendanceModel;
    private $reportModel;
    private $systemSettingModel;

    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['admin']);
        $this->userModel = $this->model('User');
        $this->departmentModel = $this->model('Department');
        $this->courseModel = $this->model('Course');
        $this->unitModel = $this->model('Unit');
        $this->enrollmentModel = $this->model('StudentEnrollment');
        $this->attendanceModel = $this->model('AttendanceModel');
        $this->reportModel = $this->model('Report'); // Include Report model
        $this->systemSettingModel = $this->model('SystemSetting'); // Include SystemSetting model
    }

    public function index() {
        $totalStudents = count($this->userModel->getByRole('student'));
        $totalLecturers = count($this->userModel->getByRole('lecturer'));
        $totalCourses = count($this->courseModel->getAll());
        $totalUnits = count($this->unitModel->getAll());
        
        // Placeholder for recent enrollments (needs a method in StudentEnrollmentModel)
        $recentEnrollments = []; 

        // Fetch low attendance students
        $attendanceThreshold = $this->systemSettingModel->get('attendance_threshold');
        $lowAttendanceStudents = $this->reportModel->getLowAttendanceStudents($attendanceThreshold);

        // Placeholder for today's attendance rate
        $attendanceRate = 0;

        // Get attendance overview for the chart
        $start_date = date('Y-m-d', strtotime('-7 days'));
        $end_date = date('Y-m-d');
        $attendanceOverview = $this->reportModel->getAttendanceOverview($start_date, $end_date);

        $data = [
            'title' => 'Admin Dashboard',
            'total_students' => $totalStudents,
            'total_lecturers' => $totalLecturers,
            'total_courses' => $totalCourses,
            'total_units' => $totalUnits,
            'recent_enrollments' => $recentEnrollments,
            'attendance_rate' => $attendanceRate,
            'low_attendance_students' => $lowAttendanceStudents,
            'attendance_overview' => $attendanceOverview
        ];

        $this->view('admin/dashboard', $data);
    }

    public function getAttendanceOverviewData() {
        $start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
        $end_date = $_GET['end_date'] ?? date('Y-m-d');

        $attendanceOverview = $this->reportModel->getAttendanceOverview($start_date, $end_date);
        header('Content-Type: application/json');
        echo json_encode($attendanceOverview);
    }
}
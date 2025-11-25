<?php
class Dashboard extends Controller {
    private $scheduleModel;
    private $unitModel;
    private $excuseRequestModel;

    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['lecturer']);
        $this->scheduleModel = $this->model('ScheduleModel');
        $this->unitModel = $this->model('Unit');
        $this->excuseRequestModel = $this->model('ExcuseRequest');
    }

    public function index() {
        $lecturer_id = get_session('user_id');
        $today = date('Y-m-d');

        // Fetch today's classes
        $todaysClasses = $this->scheduleModel->getTodaysSchedule($lecturer_id, $today);

        // Fetch this week's schedule
        $weekStartDate = date('Y-m-d', strtotime('monday this week'));
        $weeklySchedule = $this->scheduleModel->getWeeklySchedule($lecturer_id, $weekStartDate);

        // Fetch pending excuse requests count
        $pendingExcuseRequests = $this->excuseRequestModel->getPendingCountByLecturer($lecturer_id);

        $data = [
            'title' => 'Lecturer Dashboard',
            'todays_classes' => $todaysClasses,
            'weekly_schedule' => $weeklySchedule,
            'attendance_stats' => [], // Placeholder for now
            'pending_excuse_requests' => $pendingExcuseRequests
        ];

        $this->view('lecturer/dashboard', $data);
    }
}

<?php
class MySchedule extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['student']);
        $this->scheduleModel = $this->model('ScheduleModel'); // Updated to ScheduleModel
        $this->enrollmentModel = $this->model('StudentEnrollment');
        $this->unitModel = $this->model('Unit');
    }

    public function index() {
        $this->view('student/schedule/index');
    }

    public function list() {
        // Get enrolled units for the student
        $enrolledUnits = $this->enrollmentModel->getByStudent(get_session('user_id'));
        $schedules = [];

        foreach ($enrolledUnits as $enrollment) {
            // Assuming unit_id is available in enrollment
            $unitSchedules = $this->scheduleModel->getByUnit($enrollment->unit_id);
            $schedules = array_merge($schedules, $unitSchedules);
        }

        $data = [
            'title' => 'My Schedule List',
            'schedules' => $schedules
        ];
        $this->view('student/schedule/list', $data);
    }

    public function upcoming() {
        $student_id = get_session('user_id');
        $upcomingClasses = $this->scheduleModel->getUpcomingScheduleForStudent($student_id);

        $data = [
            'title' => 'Upcoming Classes',
            'upcoming_classes' => $upcomingClasses
        ];
        $this->view('student/schedule/upcoming', $data);
    }
}

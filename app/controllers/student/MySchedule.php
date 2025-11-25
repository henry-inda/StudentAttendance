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
        $student_id = get_session('user_id');
        $enrolledCourses = $this->enrollmentModel->getByStudent($student_id);
        $schedules = [];

        foreach ($enrolledCourses as $enrollment) {
            $unitsInCourse = $this->unitModel->getByCourse($enrollment->course_id);
            foreach ($unitsInCourse as $unit) {
                $unitSchedules = $this->scheduleModel->getByUnit($unit->id);
                $schedules = array_merge($schedules, $unitSchedules);
            }
        }

        // Sort schedules by day of week and then by start time
        usort($schedules, function($a, $b) {
            $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $dayA = array_search($a->day_of_week, $daysOfWeek);
            $dayB = array_search($b->day_of_week, $daysOfWeek);

            if ($dayA == $dayB) {
                return strtotime($a->start_time) - strtotime($b->start_time);
            }
            return $dayA - $dayB;
        });

        $data = [
            'title' => 'My Schedule List',
            'schedules' => $schedules
        ];
        $this->view('student/schedule/list', $data);
    }

    public function upcoming() {
        $student_id = get_session('user_id');
        $upcomingClasses = $this->scheduleModel->getUpcomingClassesForStudent($student_id);

        $data = [
            'title' => 'Upcoming Classes',
            'upcoming_classes' => $upcomingClasses
        ];
        $this->view('student/schedule/upcoming', $data);
    }
}

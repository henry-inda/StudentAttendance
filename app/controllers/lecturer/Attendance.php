<?php
class Attendance extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['lecturer']);
        $this->attendanceModel = $this->model('AttendanceModel'); // Updated to AttendanceModel
        $this->scheduleModel = $this->model('ScheduleModel'); // Updated to ScheduleModel
        $this->enrollmentModel = $this->model('StudentEnrollment');
        $this->userModel = $this->model('User');
    }

    public function index() {
        $schedules = $this->scheduleModel->getByLecturer(get_session('user_id'));
        $data = [
            'title' => 'Mark Attendance',
            'schedules' => $schedules
        ];
        $this->view('lecturer/attendance/index', $data);
    }

    public function mark($schedule_id) {
        // Fetch schedule and enrolled students
        $schedule = $this->scheduleModel->findById($schedule_id);
        $students = $this->enrollmentModel->getByCourse($schedule->course_id); // Assuming course_id in schedule
        
        $data = [
            'title' => 'Mark Attendance',
            'schedule' => $schedule,
            'students' => $students
        ];
        $this->view('lecturer/attendance/mark', $data);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $schedule_id = $_POST['schedule_id'];
            $date = $_POST['date'];
            $marked_by = get_session('user_id');

            foreach ($_POST['attendance'] as $student_id => $status) {
                $notes = $_POST['notes'][$student_id] ?? null;
                $data = [
                    'schedule_id' => $schedule_id,
                    'student_id' => $student_id,
                    'date' => $date,
                    'status' => $status,
                    'marked_by' => $marked_by,
                    'notes' => $notes
                ];
                $this->attendanceModel->markAttendance($data);
            }
            redirect('lecturer/attendance');
        }
    }

    public function view_history($unit_id) {
        // To be implemented
        $this->view('lecturer/attendance/history');
    }
}
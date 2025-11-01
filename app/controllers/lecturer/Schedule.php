<?php
class Schedule extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['lecturer']);
        $this->scheduleModel = $this->model('ScheduleModel'); // Updated to ScheduleModel
        $this->unitModel = $this->model('Unit');
    }

    public function index() {
        $schedules = $this->scheduleModel->getByLecturer(get_session('user_id'));
        $data = [
            'title' => 'My Schedule',
            'schedules' => $schedules
        ];
        $this->view('lecturer/schedule/index', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'unit_id' => trim($_POST['unit_id']),
                'lecturer_id' => get_session('user_id'),
                'day_of_week' => trim($_POST['day_of_week']),
                'start_time' => trim($_POST['start_time']),
                'end_time' => trim($_POST['end_time']),
                'venue' => trim($_POST['venue']),
                'semester' => trim($_POST['semester'])
            ];
            if ($this->scheduleModel->create($data)) {
                redirect('lecturer/schedule');
            }
        } else {
            $units = $this->unitModel->getByLecturer(get_session('user_id'));
            $data = ['units' => $units];
            $this->view('lecturer/schedule/create', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'unit_id' => trim($_POST['unit_id']),
                'day_of_week' => trim($_POST['day_of_week']),
                'start_time' => trim($_POST['start_time']),
                'end_time' => trim($_POST['end_time']),
                'venue' => trim($_POST['venue']),
                'semester' => trim($_POST['semester']),
                'status' => trim($_POST['status'])
            ];
            if ($this->scheduleModel->update($id, $data)) {
                redirect('lecturer/schedule');
            }
        } else {
            $schedule = $this->scheduleModel->findById($id);
            $units = $this->unitModel->getByLecturer(get_session('user_id'));
            $data = [
                'schedule' => $schedule,
                'units' => $units
            ];
            $this->view('lecturer/schedule/edit', $data);
        }
    }

    public function weekly() {
        $lecturer_id = get_session('user_id');
        $weekStartDate = date('Y-m-d', strtotime('monday this week'));
        $weeklySchedule = $this->scheduleModel->getWeeklySchedule($lecturer_id, $weekStartDate);

        $data = [
            'title' => 'Weekly Schedule',
            'weekly_schedule' => $weeklySchedule
        ];
        $this->view('lecturer/schedule/weekly', $data);
    }

    public function delete($id) {
        if ($this->scheduleModel->delete($id)) {
            redirect('lecturer/schedule');
        }
    }
}
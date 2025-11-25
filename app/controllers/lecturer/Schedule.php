<?php
class Schedule extends Controller {
    private $scheduleModel;
    private $unitModel;

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

            // Validate day_of_week
            if (in_array($data['day_of_week'], ['Saturday', 'Sunday'])) {
                flash_message('schedule_error', 'Schedules cannot be created for Saturdays or Sundays.', 'alert-danger');
                $units = $this->unitModel->getByLecturer(get_session('user_id'));
                $this->view('lecturer/schedule/create', ['units' => $units, 'schedule' => (object)$data]);
                return;
            }

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

            // Validate day_of_week
            if (in_array($data['day_of_week'], ['Saturday', 'Sunday'])) {
                flash_message('schedule_error', 'Schedules cannot be updated to Saturdays or Sundays.', 'alert-danger');
                $schedule = $this->scheduleModel->findById($id);
                $units = $this->unitModel->getByLecturer(get_session('user_id'));
                $this->view('lecturer/schedule/edit', ['schedule' => $schedule, 'units' => $units]);
                return;
            }

            if ($this->scheduleModel->update($id, $data)) {
                // Notify all enrolled students of venue change
                require_once APP . '/models/StudentEnrollment.php';
                require_once APP . '/models/Unit.php';
                require_once APP . '/models/Notification.php';
                require_once APP . '/helpers/email_helper.php';
                require_once APP . '/helpers/websocket_helper.php';

                $schedule = $this->scheduleModel->findById($id);
                $unit = $this->unitModel->findById($data['unit_id']);
                $students = (new StudentEnrollment())->getByCourse($unit->course_id);
                $notificationModel = new Notification();

                $venue = $data['venue'];
                $unitName = $unit->unit_name;
                $day = $data['day_of_week'];
                $start = $data['start_time'];
                $end = $data['end_time'];
                $message = "Your class ($unitName) was updated to:\n$day $start to $end\nvenue:$venue";
                $title = "Class Venue Updated";

                foreach ($students as $student) {
                    // Create notification
                    $notificationModel->create($student->id, 'venue_update', $title, $message, $id);
                    // Send email
                    send_email($student->email, $title, $message);
                    // Send WebSocket notification
                    WebSocketNotifier::getInstance()->notify([
                        'type' => 'venue_update',
                        'userId' => $student->id,
                        'title' => $title,
                        'message' => $message,
                        'scheduleId' => $id
                    ]);
                }
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
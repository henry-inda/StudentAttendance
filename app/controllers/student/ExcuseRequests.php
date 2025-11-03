<?php
class ExcuseRequests extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['student']);
        $this->excuseRequestModel = $this->model('ExcuseRequest');
        $this->enrollmentModel = $this->model('StudentEnrollment');
        $this->scheduleModel = $this->model('ScheduleModel'); // Updated to ScheduleModel
        $this->unitModel = $this->model('Unit');
    }

    public function index() {
        $excuseRequests = $this->excuseRequestModel->getByStudent(get_session('user_id'));
        $data = [
            'title' => 'My Excuse Requests',
            'excuseRequests' => $excuseRequests
        ];
        $this->view('student/excuse_requests/index', $data);
    }

    public function create() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $student_id = get_session('user_id');
            $schedule_id = isset($_POST['schedule_id']) ? trim($_POST['schedule_id']) : '';
            $date = isset($_POST['date']) ? trim($_POST['date']) : '';
            $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';

            $errors = [];
            // Basic validation
            if (empty($schedule_id) || !ctype_digit((string)$schedule_id)) {
                $errors['schedule_id'] = 'Please select a valid schedule.';
            }
            if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $errors['date'] = 'Please provide a valid date (YYYY-MM-DD).';
            }
            if (empty($reason)) {
                $errors['reason'] = 'Please provide a reason for your excuse.';
            }

            // Ensure the schedule is one of the upcoming schedules for the student
            $upcomingSchedules = $this->scheduleModel->getUpcomingScheduleForStudent($student_id);
            $validSchedule = false;
            foreach ($upcomingSchedules as $us) {
                if ($us->id == $schedule_id) {
                    $validSchedule = true;
                    break;
                }
            }
            if (!$validSchedule) {
                $errors['schedule_id'] = 'Selected class is not an upcoming class you can request an excuse for.';
            }

            // Duplicate check
            if (empty($errors) && $this->excuseRequestModel->exists($student_id, $schedule_id, $date)) {
                $errors['duplicate'] = 'An excuse request for that schedule and date already exists.';
            }

            if (empty($errors)) {
                $data = [
                    'student_id' => $student_id,
                    'schedule_id' => $schedule_id,
                    'date' => $date,
                    'reason' => $reason
                ];
                if ($this->excuseRequestModel->create($data)) {
                    flash_message('success', 'Excuse request submitted successfully.');
                    redirect('student/excuseRequests');
                } else {
                    $errors['save'] = 'Failed to submit excuse request. Please try again.';
                }
            }

            // On validation errors or save failure, reload the create view with errors and previous input
            // Re-populate schedules from the upcoming schedules we fetched earlier
            $schedules = isset($upcomingSchedules) ? $upcomingSchedules : $this->scheduleModel->getUpcomingScheduleForStudent(get_session('user_id'));
            $data = [
                'schedules' => $schedules,
                'errors' => $errors,
                'old' => [
                    'schedule_id' => $schedule_id,
                    'date' => $date,
                    'reason' => $reason
                ]
            ];
            $this->view('student/excuse_requests/create', $data);
        } else {
            // Get enrolled units to select schedule from
            // Only allow upcoming schedules for this student
            $schedules = $this->scheduleModel->getUpcomingScheduleForStudent(get_session('user_id'));
            $data = [
                'schedules' => $schedules
            ];
            $this->view('student/excuse_requests/create', $data);
        }
    }

    public function viewRequest($id) {
        // Verify that the request belongs to the current student
        $excuseRequest = $this->excuseRequestModel->findById($id);
        
        if (!$excuseRequest || $excuseRequest->student_id != get_session('user_id')) {
            flash_message('error', 'You are not authorized to view this excuse request');
            redirect('student/excuseRequests');
            return;
        }

        $data = [
            'title' => 'Excuse Request Details',
            'excuseRequest' => $excuseRequest
        ];
        $this->view('student/excuse_requests/view', $data);
    }

    public function getStatus($id) {
        $excuseRequest = $this->excuseRequestModel->findById($id);
        header('Content-Type: application/json');
        echo json_encode(['status' => $excuseRequest->status]);
    }
}
<?php
class Attendance extends Controller {
    protected $attendanceModel;
    protected $scheduleModel;
    protected $enrollmentModel;
    protected $userModel;
    protected $excuseRequestModel;

    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['lecturer']);
        require_once 'app/helpers/email_helper.php';
        $this->attendanceModel = $this->model('AttendanceModel'); // Updated to AttendanceModel
        $this->scheduleModel = $this->model('ScheduleModel'); // Updated to ScheduleModel
        $this->enrollmentModel = $this->model('StudentEnrollment');
        $this->userModel = $this->model('User');
        $this->excuseRequestModel = $this->model('ExcuseRequest');
    }

    public function index() {
        $schedules = $this->scheduleModel->getClassesInSession(get_session('user_id'));
        $data = [
            'title' => 'Mark Attendance',
            'schedules' => $schedules
        ];
        if (empty($schedules)) {
            flash_message('info', 'No classes are currently in session for you to mark attendance.');
        }
        $this->view('lecturer/attendance/index', $data);
    }

    public function mark($schedule_id) {
        // Fetch schedule and enrolled students
        $schedule = $this->scheduleModel->findById($schedule_id);

        if (!$schedule) {
            flash_message('error', 'Schedule not found.');
            redirect('lecturer/attendance');
            return;
        }

        $date = date('Y-m-d');
        $existing_attendance = $this->attendanceModel->getBySchedule($schedule_id, $date);
        
        $attendance_map = [];
        foreach ($existing_attendance as $att) {
            $attendance_map[$att->student_id] = $att->status;
        }

        $students = $this->enrollmentModel->getStudentsBySchedule($schedule_id);

        // Fetch approved excuse requests for this schedule and date
        $approvedExcuseRequests = $this->excuseRequestModel->getApprovedRequestsByScheduleAndDate($schedule_id, $date);
        $preselected_statuses = [];
        foreach ($approvedExcuseRequests as $req) {
            $preselected_statuses[$req->student_id] = 'excused';
        }
        
        $data = [
            'title' => 'Mark Attendance',
            'schedule' => $schedule,
            'students' => $students,
            'is_submitted' => false, // Always allow marking until fully submitted
            'attendance_map' => $attendance_map, // Pass existing attendance map
            'preselected_statuses' => $preselected_statuses // Pass preselected statuses for excused students
        ];
        $this->view('lecturer/attendance/mark', $data);
    }

    public function save() {
        require_once APP . '/helpers/validation_helper.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $schedule_id = $_POST['schedule_id'] ?? null;
            $date = $_POST['date'] ?? null;
            $marked_by = get_session('user_id');
            

            // --- NEW: Check if the class is currently in session ---
            $current_schedules = $this->scheduleModel->getClassesInSession(get_session('user_id'));
            $is_schedule_in_session = false;
            foreach ($current_schedules as $s) {
                if ($s->id == $schedule_id) {
                    $is_schedule_in_session = true;
                    break;
                }
            }

            if (!$is_schedule_in_session) {
                flash_message('error', 'Attendance can only be marked during the scheduled class session.');
                redirect('lecturer/attendance');
                return;
            }
            // --- END NEW ---

            // Validate inputs
            $errors = [];
            if (!ctype_digit((string)$schedule_id)) {
                $errors[] = 'Invalid schedule selected.';
            }
            if (!vh_validate_date_format($date)) {
                $errors[] = 'Invalid date format (required format: YYYY-MM-DD).';
            }
            if (!isset($_POST['attendance']) || !is_array($_POST['attendance'])) {
                $errors[] = 'Attendance data is required.';
            }

            if (!empty($errors)) {
                flash_message('error', implode(' ', $errors));
                redirect('lecturer/attendance');
                return;
            }

            $all_marked_successfully = true;
            foreach ($_POST['attendance'] as $student_id => $status) {
                // Basic validation of student id and status
                if (!ctype_digit((string)$student_id)) continue; // skip invalid keys
                $status = in_array($status, ['present','absent','excused']) ? $status : 'present';
                $notes = $_POST['notes'][$student_id] ?? null;

                $attendanceData = [
                    'schedule_id' => $schedule_id,
                    'student_id' => $student_id,
                    'date' => $date,
                    'status' => $status,
                    'marked_by' => $marked_by,
                    'notes' => $notes
                ];

                if (!$this->attendanceModel->upsertAttendance($attendanceData)) {
                    $all_marked_successfully = false;
                    // Log error or handle failure for individual student attendance marking
                    error_log("Failed to upsert attendance for student {$student_id} in schedule {$schedule_id}");
                } else {
                    // Send WebSocket notification to the affected student
                    require_once APP . '/helpers/websocket_helper.php';
                    WebSocketNotifier::getInstance()->notify([
                        'type' => 'attendance_marked',
                        'studentIds' => [$student_id],
                        'scheduleId' => $schedule_id,
                        'date' => $date,
                        'status' => $status
                    ]);

                    // If student marked absent, send notification email
                    if ($status === 'absent') {
                        try {
                            $student = $this->userModel->findById($student_id);
                            $scheduleRow = $this->scheduleModel->findById($schedule_id);
                            $unitName = $scheduleRow->unit_name ?? 'your unit';
                            $subject = "Marked absent: {$unitName} on {$date}";
                            $body = "<p>Dear {$student->full_name},</p>" .
                                    "<p>You have been marked <strong>absent</strong> for <em>{$unitName}</em> on <strong>{$date}</strong> by your lecturer.</p>" .
                                    (!empty($notes) ? "<p>Notes: {$notes}</p>" : '') .
                                    "<p>If this is a mistake or you have an excuse, please submit an excuse request via your student dashboard.</p>" .
                                    "<p>Regards,<br/>" . (defined('SYSTEM_NAME') ? SYSTEM_NAME : 'Student Attendance System') . "</p>";

                            // Ensure email looks valid before sending
                            if (!empty($student->email) && filter_var($student->email, FILTER_VALIDATE_EMAIL)) {
                                send_email($student->email, $subject, $body);
                            } else {
                                // Log missing/invalid email for troubleshooting
                                error_log("Skipping absence notification: invalid or missing email for user id {$student_id}");
                            }
                        } catch (Exception $e) {
                            // Swallow exceptions from notification to avoid breaking attendance save flow
                            error_log('Failed to send absence notification: ' . $e->getMessage());
                        }
                    }
                }
            }

            if ($all_marked_successfully) {
                flash_message('success', 'Attendance marked and submitted successfully!');
            } else {
                flash_message('error', 'There was an issue marking attendance for some students.');
            }
            redirect('lecturer/attendance');
        } 
    }

    public function view_history($unit_id) {
        $unitModel = $this->model('Unit');
        $unit = $unitModel->findById($unit_id);

        if (!$unit) {
            flash_message('error', 'Unit not found.');
            redirect('lecturer/attendance');
            return;
        }

        $attendance_history = $this->attendanceModel->getAttendanceByUnit($unit_id);

        $data = [
            'title' => 'Attendance History for ' . $unit->unit_name,
            'unit' => $unit,
            'attendance_history' => $attendance_history
        ];

        $this->view('lecturer/attendance/history', $data);
    }

    public function generate_qr($schedule_id) {
        require_once 'vendor/autoload.php';
        
        $schedule = $this->scheduleModel->findById($schedule_id);
        if (!$schedule) {
            flash_message('error', 'Schedule not found.');
            redirect('lecturer/attendance');
            return;
        }

        // Generate a short-lived JWT
        $payload = [
            'iss' => BASE_URL,
            'aud' => BASE_URL,
            'iat' => time(),
            'exp' => time() + 300, // Token valid for 5 minutes
            'data' => [
                'schedule_id' => $schedule_id,
                'date' => date('Y-m-d')
            ]
        ];
        $jwt = Firebase\JWT\JWT::encode($payload, JWT_SECRET, 'HS256');

        $qr_url = BASE_URL . 'student/myattendance/mark_by_qr?token=' . $jwt;

        // Generate QR code
        $result = Endroid\QrCode\Builder\Builder::create()
            ->writer(new Endroid\QrCode\Writer\PngWriter())
            ->data($qr_url)
            ->size(300)
            ->margin(10)
            ->build();

        $data = [
            'title' => 'QR Code for ' . $schedule->unit_name,
            'qr_code' => $result->getString(),
            'schedule' => $schedule
        ];

        $this->view('lecturer/attendance/generate_qr', $data);
    }
}

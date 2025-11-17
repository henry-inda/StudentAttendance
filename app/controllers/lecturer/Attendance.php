<?php
class Attendance extends Controller {
    protected $attendanceModel;
    protected $scheduleModel;
    protected $enrollmentModel;
    protected $userModel;

    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['lecturer']);
        require_once 'app/helpers/email_helper.php';
        $this->attendanceModel = $this->model('AttendanceModel'); // Updated to AttendanceModel
        $this->scheduleModel = $this->model('ScheduleModel'); // Updated to ScheduleModel
        $this->enrollmentModel = $this->model('StudentEnrollment');
        $this->userModel = $this->model('User');
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
        $is_submitted = !empty($existing_attendance);

        if ($is_submitted) {
            flash_message('info', 'Attendance for this session has already been submitted. You can only review it.');
        }

        $students = $this->enrollmentModel->getStudentsBySchedule($schedule_id);
        
        $data = [
            'title' => 'Mark Attendance',
            'schedule' => $schedule,
            'students' => $students,
            'is_submitted' => $is_submitted, // Pass submission status to the view
            'existing_attendance' => $existing_attendance // Pass existing attendance for review
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

            // Check if attendance has already been submitted for this date
            $existing_attendance = $this->attendanceModel->getBySchedule($schedule_id, $date);
            if (!empty($existing_attendance)) {
                flash_message('error', 'Attendance for this session on this date has already been submitted and cannot be modified.');
                redirect('lecturer/attendance');
                return;
            }

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

                if (!$this->attendanceModel->markAttendance($attendanceData)) {
                    $all_marked_successfully = false;
                    // Log error or handle failure for individual student attendance marking
                    error_log("Failed to mark attendance for student {$student_id} in schedule {$schedule_id}");
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
        // To be implemented
        $this->view('lecturer/attendance/history');
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
        $key = 'your_secret_key'; // Should be stored securely in config
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
        $jwt = Firebase\JWT\JWT::encode($payload, $key, 'HS256');

        // Generate QR code
        $result = Endroid\QrCode\Builder\Builder::create()
            ->writer(new Endroid\QrCode\Writer\PngWriter())
            ->data($jwt)
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
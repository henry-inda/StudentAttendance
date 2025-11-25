<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Myattendance extends Controller {
    private $attendanceModel;

    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        require_once 'app/helpers/session_helper.php';

        if (!is_logged_in()) {
            redirect('auth/login');
        }
        $this->attendanceModel = $this->model('AttendanceModel');
    }

    public function mark_by_qr($token = '') {
        if (empty($token)) {
            flash_message('error', 'QR code token is missing.');
            redirect('student/dashboard');
            return;
        }

        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));

            // Ensure the token has the expected data structure
            if (!isset($decoded->data->schedule_id) || !isset($decoded->data->date)) {
                flash_message('error', 'Invalid QR code data.');
                redirect('student/dashboard');
                return;
            }

            $schedule_id = $decoded->data->schedule_id;
            $date = $decoded->data->date;
            $student_id = get_session('user_id');
            error_log("Marking attendance for student_id: {$student_id}, schedule_id: {$schedule_id}, date: {$date}");

            // Check if attendance is already marked for this student, schedule, and date
            $existing_attendance = $this->attendanceModel->getByScheduleAndStudent($schedule_id, $student_id, $date);

            if ($existing_attendance) {
                error_log("Attendance already marked for student_id: {$student_id}, schedule_id: {$schedule_id}, date: {$date}");
                flash_message('info', 'Attendance for this session has already been marked.');
                redirect('student/dashboard');
                return;
            }

            $data = [
                'schedule_id' => $schedule_id,
                'student_id' => $student_id,
                'date' => $date,
                'status' => 'present',
                'marked_by' => 'QR',
                'notes' => 'Marked via QR code scan'
            ];

            $result = $this->attendanceModel->markAttendance($data);
            error_log("markAttendance result: " . ($result ? 'Success' : 'Failure'));

            if ($result) {
                flash_message('success', 'Attendance marked successfully!');

                // Send WebSocket notification to the lecturer
                require_once APP . '/helpers/websocket_helper.php';
                WebSocketNotifier::getInstance()->notify([
                    'type' => 'attendance_update',
                    'scheduleId' => $schedule_id,
                    'date' => $date,
                    'studentId' => $student_id,
                    'status' => 'present'
                ]);

                redirect('student/dashboard');
            } else {
                flash_message('error', 'Failed to mark attendance. Please try again.');
                redirect('student/dashboard');
            }

        } catch (\Firebase\JWT\ExpiredException $e) {
            flash_message('error', 'QR code has expired. Please get a new one.');
            redirect('student/dashboard');
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            flash_message('error', 'Invalid QR code signature. Please ensure the QR code is valid.');
            redirect('student/dashboard');
        } catch (\Exception $e) {
            // Log the error for debugging
            error_log("JWT Decode Error: " . $e->getMessage());
            flash_message('error', 'An error occurred while processing the QR code. ' . $e->getMessage());
            redirect('student/dashboard');
        }
    }

    public function scan_qr() {
        $data = [
            'title' => 'Scan QR Code'
        ];
        $this->view('student/attendance/scan_qr', $data);
    }

    public function index() {
        $student_id = get_session('user_id');
        $attendanceData = $this->attendanceModel->getEnrolledUnitsWithAttendance($student_id);

        $data = [
            'title' => 'My Attendance',
            'attendanceData' => $attendanceData
        ];
        $this->view('student/attendance/index', $data);
    }

    public function unit_details($unit_id) {
        $student_id = get_session('user_id');

        // Fetch unit details
        $unitModel = $this->model('Unit');
        $unit = $unitModel->findById($unit_id);

        if (!$unit) {
            flash_message('error', 'Unit not found.');
            redirect('student/myattendance');
            return;
        }

        // Fetch attendance history for the student and unit
        $attendance_history = $this->attendanceModel->getByStudentAndUnit($student_id, $unit_id);

        $data = [
            'title' => 'Attendance Details for ' . $unit->unit_name,
            'unit' => $unit,
            'attendance_history' => $attendance_history
        ];

        $this->view('student/attendance/unit_details', $data);
    }
}

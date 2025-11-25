<?php
class Reports extends Controller {
    private $reportModel;
    private $courseModel;
    private $scheduleModel;
    private $userModel;
    private $unitModel;

    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['admin']);
        $this->reportModel = $this->model('Report');
        $this->courseModel = $this->model('Course');
        $this->scheduleModel = $this->model('ScheduleModel'); // Load ScheduleModel
        $this->userModel = $this->model('User'); // Load User model
        $this->unitModel = $this->model('Unit'); // Load Unit model
    }

    public function index() {
        $semesters = array_column($this->scheduleModel->getSemesters(), 'semester');
        $users = $this->userModel->getAllUsers(); // Assuming getAllUsers() exists and returns all users
        $courses = $this->courseModel->getAll(); // Fetch all courses for attendance by course report
        $units = $this->unitModel->getAll(); // Fetch all units for unit attendance report

        $data = [
            'title' => 'Reports Overview',
            'report_types' => [
                'course_report' => 'Course Report',
                'unit_report' => 'Unit Report',
                'user_report' => 'User Report',
                'overall_system_report' => 'Overall System Report'
            ],
            'semesters' => $semesters,
            'users' => $users,
            'courses' => $courses, // Add courses to data
            'units' => $units, // Add units to data
            'generated_report' => null, // To store the report data after generation
            'report_title' => 'Report Preview', // Default title for the generated report panel
            'filters' => [ // To store the submitted filter values
                'report_type' => '',
                'semester' => '',
                'start_date' => '',
                'end_date' => '',
                'name_filter' => '',
                'user_id' => '', // For single user attendance
                'course_id' => '', // For attendance by course
                'unit_id' => '', // For unit attendance
                'user_role' => '', // For user report (student/lecturer)
                'lecturer_id' => '', // For lecturer activity
                'student_id' => '' // For student activity
            ]
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and get filter values
            $reportType = isset($_POST['report_type']) ? htmlspecialchars($_POST['report_type'], ENT_QUOTES, 'UTF-8') : null;
            $semester = isset($_POST['semester']) ? htmlspecialchars($_POST['semester'], ENT_QUOTES, 'UTF-8') : null;
            $startDate = isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date'], ENT_QUOTES, 'UTF-8') : null;
            $endDate = isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date'], ENT_QUOTES, 'UTF-8') : null;
            $nameFilter = isset($_POST['name_filter']) ? htmlspecialchars($_POST['name_filter'], ENT_QUOTES, 'UTF-8') : null;
            $userId = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
            $courseId = filter_input(INPUT_POST, 'course_id', FILTER_SANITIZE_NUMBER_INT);
            $unitId = filter_input(INPUT_POST, 'unit_id', FILTER_SANITIZE_NUMBER_INT); // Retrieve unit_id
            $userRole = isset($_POST['user_role']) ? htmlspecialchars($_POST['user_role'], ENT_QUOTES, 'UTF-8') : null; // Retrieve user_role
            $lecturerId = filter_input(INPUT_POST, 'lecturer_id', FILTER_SANITIZE_NUMBER_INT);
            $studentId = filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_NUMBER_INT);


            // Store filters for repopulating the form
            $data['filters'] = [
                'report_type' => $reportType,
                'semester' => $semester,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'name_filter' => $nameFilter,
                'user_id' => $userId,
                'course_id' => $courseId,
                'unit_id' => $unitId, // Add unit_id to filters
                'lecturer_id' => $lecturerId,
                'student_id' => $studentId
            ];

            // Generate report based on selected type and filters
            if (!empty($reportType)) {
                $data['generated_report'] = $this->_generateReport(
                    $reportType,
                    $semester,
                    $startDate,
                    $endDate,
                    $nameFilter,
                    $userId,
                    $courseId,
                    $unitId,
                    $lecturerId,
                    $studentId
                );
                error_log('Generated Report Data: ' . print_r($data['generated_report'], true));
                $data['report_title'] = $data['report_types'][$reportType] ?? 'Generated Report';
            } else {
                flash_message('error', 'Please select a report type to generate a report.');
            }
        }

        $this->view('admin/reports/index', $data);
    }

    private function _generateReport($reportType, $semester, $startDate, $endDate, $nameFilter, $userId, $courseId, $unitId, $lecturerId, $studentId) {
        $reportData = [];
        // Set sane defaults for dates if not provided
        $startDate = $startDate ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $endDate ?? date('Y-m-d');

        switch ($reportType) {
            case 'course_report':
                $reportData = $this->_getCourseReportData($courseId, $startDate, $endDate);
                break;
            case 'unit_report':
                $reportData = $this->_getUnitReportData($unitId, $startDate, $endDate);
                break;
            case 'user_report':
                $reportData = $this->_getUserReportData($userId, $startDate, $endDate);
                break;
            case 'overall_system_report':
                $reportData = $this->_getOverallSystemReportData($startDate, $endDate);
                break;
            default:
                // Handle unknown report type or no selection
                break;
        }
        return $reportData;
    }

    private function _getAccountRequestReportData($start_date, $end_date, $status) {
        return $this->reportModel->getAccountRequestReport($start_date, $end_date, $status);
    }

    private function _getStudentActivityReportData($student_id, $start_date, $end_date, $semester) {
        // The existing method uses $student_id, $start_date, $end_date.
        // Semester is not currently used in getStudentCoursesAndUnits or getStudentClassesAttended.
        // If needed, the model methods would need to be updated to accept and filter by semester.
        $courses_units = $this->reportModel->getStudentCoursesAndUnits($student_id);
        $classes_attended = $this->reportModel->getStudentClassesAttended($student_id, $start_date, $end_date);
        return ['courses_units' => $courses_units, 'classes_attended' => $classes_attended];
    }

    private function _getLecturerActivityReportData($lecturer_id, $start_date, $end_date, $semester) {
        // Similar to student_activity, semester is not currently used.
        $units_taught = $this->reportModel->getLecturerUnits($lecturer_id);
        $classes_attendance = $this->reportModel->getLecturerClassesAndAttendance($lecturer_id, $start_date, $end_date);
        return ['units_taught' => $units_taught, 'classes_attendance' => $classes_attendance];
    }

    private function _getAllUsersAttendanceSummaryReportData($start_date, $end_date, $semester) {
        // Semester is not currently used.
        return $this->reportModel->getAllUsersAttendanceSummary($start_date, $end_date);
    }

    private function _getSingleUserAttendanceReportData($user_id, $start_date, $end_date, $semester) {
        // Semester is not currently used.
        return $this->reportModel->getAttendanceReportForUser($user_id, $start_date, $end_date);
    }

    private function _getAttendanceByCourseReportData($course_id, $start_date, $end_date, $semester) {
        // Semester is not currently used.
        return $this->reportModel->getAttendanceByCourse($course_id, $start_date, $end_date);
    }

    private function _getCourseReportData($courseId, $startDate, $endDate) {
        // This method will fetch data for the Course Report
        // It needs to show units in the course and when students were added to them.
        // This will require a new method in Report.php
        return $this->reportModel->getCourseReport($courseId, $startDate, $endDate);
    }

    private function _getUnitReportData($unitId, $startDate, $endDate) {
        // This method will fetch data for the Unit Report
        // It needs to show details of the units, what lecturers teach them.
        // This will require a new method in Report.php
        return $this->reportModel->getUnitReport($unitId, $startDate, $endDate);
    }

    private function _getUserReportData($userId, $startDate, $endDate) {
        // This method will fetch data for the User Report
        // For students: it shows their course, attendance progress per unit
        // For lecturers: it shows what units they teach
        $user = $this->userModel->findById($userId);
        if (!$user) {
            return [];
        }

        if ($user->role === 'student') {
            return $this->reportModel->getStudentUserReport($userId, $startDate, $endDate);
        } elseif ($user->role === 'lecturer') {
            return $this->reportModel->getLecturerUserReport($userId, $startDate, $endDate);
        }
        return [];
    }

    private function _getOverallSystemReportData($startDate, $endDate) {
        // This method will fetch data for the Overall System Report
        // It needs to show how many classes are taught in the given range,
        // the active number of students and lecturers in session.
        // This will require a new method in Report.php
        return $this->reportModel->getOverallSystemReport($startDate, $endDate);
    }

    public function exportAttendanceCsv() {
        error_log('exportAttendanceCsv: $_REQUEST = ' . print_r($_REQUEST, true)); // Debugging line
        $input = $_REQUEST;
        $reportType = !empty($input['report_type']) ? $input['report_type'] : null;
        $semester = !empty($input['semester']) ? $input['semester'] : null;
        $startDate = !empty($input['start_date']) ? $input['start_date'] : date('Y-m-d', strtotime('-30 days'));
        $endDate = !empty($input['end_date']) ? $input['end_date'] : date('Y-m-d');
        $userId = !empty($input['user_id']) ? $input['user_id'] : null;
        $courseId = !empty($input['course_id']) ? $input['course_id'] : null;
        $unitId = !empty($input['unit_id']) ? $input['unit_id'] : null;
        $lecturerId = !empty($input['lecturer_id']) ? $input['lecturer_id'] : null;
        $studentId = !empty($input['student_id']) ? $input['student_id'] : null;

        if (!$reportType) {
            flash_message('error', 'No report type selected for export');
            redirect('admin/reports');
            return;
        }

        $rows = $this->_generateReport(
            $reportType,
            $semester,
            $startDate,
            $endDate,
            null, // nameFilter is not directly used for data fetching in _generateReport
            $userId,
            $courseId,
            $unitId,
            $lecturerId,
            $studentId
        );
        error_log('exportAttendanceCsv: $rows = ' . print_r($rows, true)); // Debugging line

        // Build CSV
        $filename = str_replace(' ', '_', strtolower($reportType)) . '_report_' . date('Ymd_His') . '.csv';
        
        if (ob_get_level()) ob_end_clean();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for Excel

        // Dynamically set headers and write data based on report type
        switch ($reportType) {
            case 'course_report':
                fputcsv($out, ['Course Name', 'Unit Name', 'Unit Code', 'Unit Semester', 'Lecturer Name', 'Student Name', 'Enrollment Date']);
                foreach ($rows as $r) {
                    fputcsv($out, [$r->course_name, $r->unit_name, $r->unit_code, $r->unit_semester, $r->lecturer_name ?? 'N/A', $r->student_name ?? 'N/A', $r->enrollment_date ?? 'N/A']);
                }
                break;
            case 'unit_report':
                fputcsv($out, ['Unit Name', 'Unit Code', 'Semester', 'Course Name', 'Lecturer Name', 'Lecturer Email']);
                foreach ($rows as $r) {
                    fputcsv($out, [$r->unit_name, $r->unit_code, $r->semester, $r->course_name, $r->lecturer_name ?? 'N/A', $r->lecturer_email ?? 'N/A']);
                }
                break;
            case 'user_report':
                if (!empty($rows['courses_units_summary'])) {
                    fputcsv($out, ['Course', 'Unit', 'Total Classes', 'Present', 'Absent', 'Excused', 'Attendance %']);
                    foreach ($rows['courses_units_summary'] as $r) {
                        fputcsv($out, [$r->course_name, $r->unit_name, $r->total_classes, $r->present_count, $r->absent_count, $r->excused_count, $r->attendance_percentage]);
                    }
                } elseif (!empty($rows['units_taught'])) {
                    fputcsv($out, ['Unit Name', 'Unit Code']);
                    foreach ($rows['units_taught'] as $r) {
                        fputcsv($out, [$r->unit_name, $r->unit_code]);
                    }
                }
                break;
            case 'overall_system_report':
                fputcsv($out, ['Metric', 'Value']);
                fputcsv($out, ['Total Classes Taught (in range)', $rows['total_classes_taught'] ?? 0]);
                fputcsv($out, ['Active Students (in range)', $rows['active_students'] ?? 0]);
                fputcsv($out, ['Active Lecturers (in range)', $rows['active_lecturers'] ?? 0]);
                break;
            default:
                // No specific export logic for other types yet
                break;
        }
        
        fclose($out);
        exit();
    }

    public function exportAttendancePdf() {
        require_once 'app/helpers/pdf_helper.php';
        $input = $_REQUEST;
        $reportType = !empty($input['report_type']) ? $input['report_type'] : null;
        $semester = !empty($input['semester']) ? $input['semester'] : null;
        $startDate = !empty($input['start_date']) ? $input['start_date'] : date('Y-m-d', strtotime('-30 days'));
        $endDate = !empty($input['end_date']) ? $input['end_date'] : date('Y-m-d');
        $userId = !empty($input['user_id']) ? $input['user_id'] : null;
        $courseId = !empty($input['course_id']) ? $input['course_id'] : null;
        $unitId = !empty($input['unit_id']) ? $input['unit_id'] : null;
        $lecturerId = !empty($input['lecturer_id']) ? $input['lecturer_id'] : null;
        $studentId = !empty($input['student_id']) ? $input['student_id'] : null;

        if (!$reportType) {
            flash_message('error', 'No report type selected for export');
            redirect('admin/reports');
            return;
        }

        $rows = $this->_generateReport(
            $reportType,
            $semester,
            $startDate,
            $endDate,
            null, // nameFilter is not directly used for data fetching in _generateReport
            $userId,
            $courseId,
            $unitId,
            $lecturerId,
            $studentId
        );

        $title = str_replace(' ', '_', strtolower($reportType)) . '_report_' . date('Ymd_His');
        $headers = [];
        $data = [];

        switch ($reportType) {
            case 'course_report':
                $headers = ['Course Name', 'Unit Name', 'Unit Code', 'Unit Semester', 'Lecturer Name', 'Student Name', 'Enrollment Date'];
                $data = $rows;
                break;
            case 'unit_report':
                $headers = ['Unit Name', 'Unit Code', 'Semester', 'Course Name', 'Lecturer Name', 'Lecturer Email'];
                $data = $rows;
                break;
            case 'user_report':
                if (!empty($rows['courses_units_summary'])) {
                    $headers = ['Course', 'Unit', 'Total Classes', 'Present', 'Absent', 'Excused', 'Attendance %'];
                    $data = $rows['courses_units_summary'];
                } elseif (!empty($rows['units_taught'])) {
                    $headers = ['Unit Name', 'Unit Code'];
                    $data = $rows['units_taught'];
                }
                break;
            case 'overall_system_report':
                $headers = ['Metric', 'Value'];
                $data = [
                    ['Total Classes Taught (in range)', $rows['total_classes_taught'] ?? 0],
                    ['Active Students (in range)', $rows['active_students'] ?? 0],
                    ['Active Lecturers (in range)', $rows['active_lecturers'] ?? 0]
                ];
                break;
        }

        generate_pdf($title, $headers, $data, $reportType);
        exit();
    }
}

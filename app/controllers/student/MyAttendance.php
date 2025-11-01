<?php
class MyAttendance extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['student']);
        $this->attendanceModel = $this->model('AttendanceModel');
        $this->enrollmentModel = $this->model('StudentEnrollment');
        $this->unitModel = $this->model('Unit');
        $this->courseModel = $this->model('Course'); // Need Course model to get course details
    }

    public function index() {
        // Get enrolled courses for the student
        $enrolledCourses = $this->enrollmentModel->getByStudent(get_session('user_id'));
        $attendanceData = [];

        foreach ($enrolledCourses as $enrollment) {
            $course = $this->courseModel->findById($enrollment->course_id); // Fetch actual course details
            if ($course) {
                $unitsInCourse = $this->unitModel->getByCourse($course->id); // Get all units for this course

                foreach ($unitsInCourse as $unit) {
                    // Get actual attendance percentage for each unit
                    $attendancePercentage = $this->attendanceModel->getAttendancePercentageForStudentAndUnit(get_session('user_id'), $unit->id);

                    $attendanceData[] = [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->unit_name,
                        'percentage' => $attendancePercentage
                    ];
                }
            }
        }

        $data = [
            'title' => 'My Attendance',
            'attendanceData' => $attendanceData
        ];
        $this->view('student/attendance/index', $data);
    }

    public function unit_details($unit_id) {
        $student_id = get_session('user_id');
        $unit = $this->unitModel->findById($unit_id);

        if (!$unit) {
            // Handle unit not found error
            redirect('student/myAttendance');
        }

        $attendanceRecords = $this->attendanceModel->getByStudentAndUnit($student_id, $unit_id);
        $attendanceStats = $this->attendanceModel->getUnitAttendanceStatsForStudent($student_id, $unit_id);

        $data = [
            'title' => 'Unit Attendance Details',
            'unit' => $unit,
            'attendance_records' => $attendanceRecords,
            'attendance_stats' => $attendanceStats
        ];
        $this->view('student/attendance/unit_details', $data);
    }

    public function trend($unit_id) {
        $unit = $this->unitModel->findById($unit_id);

        if (!$unit) {
            // Handle unit not found error
            redirect('student/myAttendance');
        }

        // Use real attendance records to compute a weekly trend for the past N weeks
        $weeks = 8; // number of weeks to show
        $trendData = $this->attendanceModel->getAttendanceTrendForStudentAndUnit(get_session('user_id'), $unit_id, $weeks);

        $data = [
            'title' => 'Attendance Trend',
            'unit' => $unit, // Pass unit details to the view
            'trend_data' => $trendData
        ];
        $this->view('student/attendance/trend', $data);
    }
}
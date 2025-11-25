<?php
class Report {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAttendanceByUnit($unit_id, $start_date, $end_date) {
        $this->db->query("SELECT
                u.full_name as student_name,
                un.unit_name,
                COUNT(DISTINCT s.id) as total_classes,
                SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN a.status = 'excused' THEN 1 ELSE 0 END) as excused_count,
                CASE
                    WHEN COUNT(DISTINCT s.id) > 0 THEN (SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) / COUNT(DISTINCT s.id)) * 100
                    ELSE 0
                END as attendance_percentage
            FROM attendance a
            JOIN schedules s ON a.schedule_id = s.id
            JOIN units un ON s.unit_id = un.id
            JOIN users u ON a.student_id = u.id
            WHERE un.id = :unit_id
            AND a.date BETWEEN :start_date AND :end_date
            GROUP BY u.id, un.id");
        $this->db->bind(':unit_id', $unit_id);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        return $this->db->resultSet();
    }

    public function getAttendanceByStudent($student_id, $start_date, $end_date) {
        $this->db->query("SELECT
                u.full_name as student_name,
                un.unit_name,
                COUNT(a.id) as total_classes,
                SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN a.status = 'excused' THEN 1 ELSE 0 END) as excused_count
            FROM attendance a
            JOIN schedules s ON a.schedule_id = s.id
            JOIN units un ON s.unit_id = un.id
            JOIN users u ON a.student_id = u.id
            WHERE a.student_id = :student_id
            AND a.date BETWEEN :start_date AND :end_date
            GROUP BY u.id, un.id");
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        return $this->db->resultSet();
    }

    public function getAttendanceByCourse($course_id, $start_date, $end_date) {
        $this->db->query("
            SELECT
                u.full_name as student_name,
                un.unit_name,
                COUNT(a.id) as total_classes,
                SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN a.status = 'excused' THEN 1 ELSE 0 END) as excused_count
            FROM attendance a
            JOIN schedules s ON a.schedule_id = s.id
            JOIN units un ON s.unit_id = un.id
            JOIN student_enrollments se ON a.student_id = se.student_id AND un.course_id = se.course_id
            JOIN users u ON a.student_id = u.id
            WHERE se.course_id = :course_id
            AND a.date BETWEEN :start_date AND :end_date
            GROUP BY u.id, un.id
        ");
        $this->db->bind(':course_id', $course_id);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        return $this->db->resultSet();
    }

    public function getAccountRequestReport($start_date, $end_date, $status = null) {
        $sql = "SELECT * FROM account_requests WHERE created_at BETWEEN :start_date AND :end_date";
        if ($status) {
            $sql .= " AND status = :status";
        }
        $sql .= " ORDER BY created_at DESC";
        $this->db->query($sql);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        if ($status) {
            $this->db->bind(':status', $status);
        }
        return $this->db->resultSet();
    }

    public function getStudentClassesAttended($student_id, $start_date, $end_date) {
        $this->db->query("SELECT
                un.unit_name,
                a.date as class_date,
                s.start_time,
                s.end_time,
                a.status as attendance_status
            FROM attendance a
            JOIN schedules s ON a.schedule_id = s.id
            JOIN units un ON s.unit_id = un.id
            WHERE a.student_id = :student_id
            AND a.date BETWEEN :start_date AND :end_date
            ORDER BY a.date DESC, s.start_time DESC");
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        return $this->db->resultSet();
    }

    public function getStudentCoursesAndUnits($student_id, $start_date, $end_date) {
        $this->db->query("SELECT
                c.course_name,
                un.unit_name,
                (SELECT COUNT(DISTINCT a.schedule_id, a.date) FROM attendance a JOIN schedules s ON a.schedule_id = s.id WHERE s.unit_id = un.id AND a.student_id = :student_id AND a.date BETWEEN :start_date AND :end_date) as total_classes,
                COUNT(DISTINCT CASE WHEN a.status = 'present' THEN a.id END) as present_count,
                COUNT(DISTINCT CASE WHEN a.status = 'absent' THEN a.id END) as absent_count,
                COUNT(DISTINCT CASE WHEN a.status = 'excused' THEN a.id END) as excused_count,
                (
                    CAST(COUNT(DISTINCT CASE WHEN a.status = 'present' THEN a.id END) AS DECIMAL(10, 2)) /
                    NULLIF((SELECT COUNT(DISTINCT a.schedule_id, a.date) FROM attendance a JOIN schedules s ON a.schedule_id = s.id WHERE s.unit_id = un.id AND a.student_id = :student_id AND a.date BETWEEN :start_date AND :end_date), 0)
                ) * 100 as attendance_percentage
            FROM student_enrollments se
            JOIN courses c ON se.course_id = c.id
            JOIN units un ON c.id = un.course_id
            LEFT JOIN schedules s ON un.id = s.unit_id
            LEFT JOIN attendance a ON s.id = a.schedule_id AND a.student_id = se.student_id AND a.date BETWEEN :start_date AND :end_date
            WHERE se.student_id = :student_id
            GROUP BY c.course_name, un.unit_name
            ORDER BY c.course_name, un.unit_name");
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        return $this->db->resultSet();
    }

    public function getLecturerClassesAndAttendance($lecturer_id, $start_date, $end_date) {
        $this->db->query("SELECT
                un.unit_name,
                a.date as class_date,
                s.start_time,
                s.end_time,
                COUNT(a.id) as total_students_marked,
                SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN a.status = 'excused' THEN 1 ELSE 0 END) as excused_count
            FROM schedules s
            JOIN units un ON s.unit_id = un.id
            LEFT JOIN attendance a ON s.id = a.schedule_id
            WHERE un.lecturer_id = :lecturer_id
            AND a.date BETWEEN :start_date AND :end_date
            GROUP BY s.id, un.unit_name, a.date, s.start_time, s.end_time
            ORDER BY a.date DESC, s.start_time DESC");
        $this->db->bind(':lecturer_id', $lecturer_id);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        return $this->db->resultSet();
    }

    public function getLecturerUnits($lecturer_id) {
        $this->db->query("SELECT
                un.id as unit_id,
                un.unit_name,
                un.unit_code
            FROM units un
            JOIN users u ON un.lecturer_id = u.id
            WHERE u.id = :lecturer_id
            ORDER BY un.unit_name");
        $this->db->bind(':lecturer_id', $lecturer_id);
        return $this->db->resultSet();
    }

    public function getAllUsersAttendanceSummary($start_date, $end_date) {
        $this->db->query("SELECT
                u.id as user_id,
                u.full_name,
                u.role,
                COUNT(a.id) as total_classes,
                SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN a.status = 'excused' THEN 1 ELSE 0 END) as excused_count
            FROM users u
            LEFT JOIN attendance a ON u.id = a.student_id
            LEFT JOIN schedules s ON a.schedule_id = s.id
            WHERE a.date BETWEEN :start_date AND :end_date
            GROUP BY u.id, u.full_name, u.role
            ORDER BY u.full_name");
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        return $this->db->resultSet();
    }

    public function getAttendanceReportForUser($user_id, $start_date, $end_date) {
        $this->db->query("SELECT
                u.full_name as user_name,
                u.role,
                un.unit_name,
                s.start_time,
                s.end_time,
                a.date as class_date,
                a.status as attendance_status
            FROM attendance a
            JOIN schedules s ON a.schedule_id = s.id
            JOIN units un ON s.unit_id = un.id
            JOIN users u ON a.student_id = u.id
            WHERE u.id = :user_id
            AND a.date BETWEEN :start_date AND :end_date
            ORDER BY a.date DESC, s.start_time DESC");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        return $this->db->resultSet();
    }

    public function getCourseReport($course_id, $start_date, $end_date) {
        $this->db->query("
            SELECT
                c.course_name,
                un.id as unit_id,
                un.unit_name,
                un.unit_code,
                un.semester as unit_semester,
                us.full_name as lecturer_name,
                se.student_id,
                u.full_name as student_name,
                se.enrollment_date
            FROM courses c
            JOIN units un ON c.id = un.course_id
            LEFT JOIN users us ON un.lecturer_id = us.id
            LEFT JOIN student_enrollments se ON c.id = se.course_id
            LEFT JOIN users u ON se.student_id = u.id
            WHERE c.id = :course_id
            AND se.enrollment_date BETWEEN :start_date AND :end_date
            ORDER BY un.unit_name, u.full_name
        ");
        $this->db->bind(':course_id', $course_id);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        return $this->db->resultSet();
    }

    public function getUnitReport($unit_id, $start_date, $end_date) {
        $this->db->query("
            SELECT
                un.id as unit_id,
                un.unit_name,
                un.unit_code,
                un.semester,
                c.course_name,
                us.full_name as lecturer_name,
                us.email as lecturer_email
            FROM units un
            JOIN courses c ON un.course_id = c.id
            LEFT JOIN users us ON un.lecturer_id = us.id
            WHERE un.id = :unit_id
            ORDER BY un.unit_name
        ");
        $this->db->bind(':unit_id', $unit_id);
        return $this->db->resultSet();
    }

    public function getStudentUserReport($student_id, $start_date, $end_date) {
        // Get student's courses and units with attendance summary
        $courses_units_summary = $this->getStudentCoursesAndUnits($student_id, $start_date, $end_date);

        // Get detailed classes attended by the student
        $classes_attended = $this->getStudentClassesAttended($student_id, $start_date, $end_date);

        return [
            'courses_units_summary' => $courses_units_summary,
            'classes_attended' => $classes_attended
        ];
    }

    public function getLecturerUserReport($lecturer_id, $start_date, $end_date) {
        // Get units taught by the lecturer
        $units_taught = $this->getLecturerUnits($lecturer_id);

        // Optionally, get classes and attendance marked by the lecturer within the date range
        $classes_and_attendance = $this->getLecturerClassesAndAttendance($lecturer_id, $start_date, $end_date);

        return [
            'units_taught' => $units_taught,
            'classes_and_attendance' => $classes_and_attendance
        ];
    }

    public function getOverallSystemReport($start_date, $end_date) {
        // Total classes taught within the date range
        $this->db->query("SELECT COUNT(DISTINCT schedule_id) as total_classes_taught FROM attendance WHERE date BETWEEN :start_date AND :end_date");
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        $totalClassesTaught = $this->db->single()->total_classes_taught;

        // Active students (students who have attended at least one class in the range)
        $this->db->query("SELECT COUNT(DISTINCT student_id) as active_students FROM attendance WHERE date BETWEEN :start_date AND :end_date");
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        $activeStudents = $this->db->single()->active_students;

        // Active lecturers (lecturers who have marked at least one attendance in the range)
        $this->db->query("SELECT COUNT(DISTINCT marked_by) as active_lecturers FROM attendance WHERE date BETWEEN :start_date AND :end_date");
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        $activeLecturers = $this->db->single()->active_lecturers;

        return [
            'total_classes_taught' => $totalClassesTaught,
            'active_students' => $activeStudents,
            'active_lecturers' => $activeLecturers
        ];
    }

    public function getLowAttendanceStudents($threshold) {
        // Implementation needed
    }

    public function getAttendanceOverview($start_date, $end_date) {
        $this->db->query("SELECT
                date,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN status = 'excused' THEN 1 ELSE 0 END) as excused_count,
                COUNT(*) as total_records
            FROM attendance
            WHERE date BETWEEN :start_date AND :end_date
            AND DAYOFWEEK(date) NOT IN (1, 7) -- 1 = Sunday, 7 = Saturday
            GROUP BY date
            ORDER BY date");
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        $results = $this->db->resultSet();

        // Calculate rates
        foreach ($results as $row) {
            if ($row->total_records > 0) {
                $row->present_rate = ($row->present_count / $row->total_records) * 100;
                $row->absent_rate = ($row->absent_count / $row->total_records) * 100;
                $row->excused_rate = ($row->excused_count / $row->total_records) * 100;
            } else {
                $row->present_rate = 0;
                $row->absent_rate = 0;
                $row->excused_rate = 0;
            }
        }

        return $results;
    }

    public function getStudentAttendanceOverview($student_id, $start_date, $end_date) {
        $this->db->query("SELECT
                date,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN status = 'excused' THEN 1 ELSE 0 END) as excused_count,
                COUNT(*) as total_records
            FROM attendance
            WHERE student_id = :student_id
            AND date BETWEEN :start_date AND :end_date
            AND DAYOFWEEK(date) NOT IN (1, 7) -- 1 = Sunday, 7 = Saturday
            GROUP BY date
            ORDER BY date");
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        $results = $this->db->resultSet();

        // Calculate rates
        foreach ($results as $row) {
            if ($row->total_records > 0) {
                $row->present_rate = ($row->present_count / $row->total_records) * 100;
                $row->absent_rate = ($row->absent_count / $row->total_records) * 100;
                $row->excused_rate = ($row->excused_count / $row->total_records) * 100;
            } else {
                $row->present_rate = 0;
                $row->absent_rate = 0;
                $row->excused_rate = 0;
            }
        }

        return $results;
    }
}
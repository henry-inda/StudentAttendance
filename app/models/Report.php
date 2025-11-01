<?php
class Report {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAttendanceByUnit($unit_id, $start_date, $end_date) {
        // Implementation needed
    }

    public function getAttendanceByStudent($student_id, $start_date, $end_date) {
        // Implementation needed
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

    public function getLowAttendanceStudents($threshold) {
        // Implementation needed
    }
}
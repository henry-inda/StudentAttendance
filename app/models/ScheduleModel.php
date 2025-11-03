<?php
class ScheduleModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getByLecturer($lecturer_id) {
        $this->db->query("SELECT schedules.*, units.unit_name FROM schedules JOIN units ON schedules.unit_id = units.id WHERE schedules.lecturer_id = :lecturer_id");
        $this->db->bind(':lecturer_id', $lecturer_id);
        return $this->db->resultSet();
    }

    public function getByUnit($unit_id) {
        $this->db->query("SELECT * FROM schedules WHERE unit_id = :unit_id");
        $this->db->bind(':unit_id', $unit_id);
        return $this->db->resultSet();
    }

    public function findById($id) {
        $this->db->query("SELECT schedules.*, units.unit_name, units.course_id FROM schedules JOIN units ON schedules.unit_id = units.id WHERE schedules.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query("INSERT INTO schedules (unit_id, lecturer_id, day_of_week, start_time, end_time, venue, semester) VALUES (:unit_id, :lecturer_id, :day_of_week, :start_time, :end_time, :venue, :semester)");
        $this->db->bind(':unit_id', $data['unit_id']);
        $this->db->bind(':lecturer_id', $data['lecturer_id']);
        $this->db->bind(':day_of_week', $data['day_of_week']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        $this->db->bind(':venue', $data['venue']);
        $this->db->bind(':semester', $data['semester']);
        return $this->db->execute();
    }

    public function update($id, $data) {
        $this->db->query("UPDATE schedules SET unit_id = :unit_id, day_of_week = :day_of_week, start_time = :start_time, end_time = :end_time, venue = :venue, semester = :semester, status = :status WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':unit_id', $data['unit_id']);
        $this->db->bind(':day_of_week', $data['day_of_week']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        $this->db->bind(':venue', $data['venue']);
        $this->db->bind(':semester', $data['semester']);
        $this->db->bind(':status', $data['status']);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM schedules WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getTodaysSchedule($lecturer_id, $date) {
        $dayOfWeek = date('l', strtotime($date)); // Get full day name (e.g., Monday)
        $this->db->query("SELECT schedules.*, units.unit_name FROM schedules JOIN units ON schedules.unit_id = units.id WHERE schedules.lecturer_id = :lecturer_id AND schedules.day_of_week = :day_of_week AND schedules.status = 'active' ORDER BY schedules.start_time ASC");
        $this->db->bind(':lecturer_id', $lecturer_id);
        $this->db->bind(':day_of_week', $dayOfWeek);
        return $this->db->resultSet();
    }

    public function getWeeklySchedule($lecturer_id, $week_start) {
        // This is a more complex query to get all schedules for a week
        // For simplicity, we'll just return all schedules for the lecturer for now
        return $this->getByLecturer($lecturer_id);
    }

    public function getAll() {
        $this->db->query("SELECT s.*, u.unit_name, us.full_name as lecturer_name FROM schedules s JOIN units u ON s.unit_id = u.id LEFT JOIN users us ON s.lecturer_id = us.id WHERE s.status = 'active' ORDER BY u.unit_name, s.day_of_week, s.start_time");
        return $this->db->resultSet();
    }

    public function getUpcomingScheduleForStudent($student_id) {
        $today = date('Y-m-d');
        $currentDayOfWeek = date('l');

        $this->db->query("
            SELECT s.*, u.id as unit_id, u.unit_name, us.full_name as lecturer_name
            FROM schedules s
            JOIN units u ON s.unit_id = u.id
            JOIN student_enrollments se ON u.course_id = se.course_id
            JOIN users us ON s.lecturer_id = us.id
            WHERE se.student_id = :student_id
            AND s.status = 'active'
            AND (
                (s.day_of_week = :current_day_of_week AND s.start_time >= CURTIME()) OR
                (FIELD(s.day_of_week, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') > FIELD(:current_day_of_week, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'))
            )
            ORDER BY FIELD(s.day_of_week, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), s.start_time
            LIMIT 5
        ");
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':current_day_of_week', $currentDayOfWeek);
        return $this->db->resultSet();
    }
}

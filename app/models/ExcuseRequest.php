<?php
class ExcuseRequest {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getByLecturer($lecturer_id) {
        $this->db->query("SELECT er.*, u.full_name as student_name, un.unit_name FROM excuse_requests er JOIN users u ON er.student_id = u.id JOIN schedules s ON er.schedule_id = s.id JOIN units un ON s.unit_id = un.id WHERE s.lecturer_id = :lecturer_id ORDER BY er.created_at DESC");
        $this->db->bind(':lecturer_id', $lecturer_id);
        return $this->db->resultSet();
    }

    public function getByStudent($student_id) {
        $this->db->query("SELECT er.*, un.unit_name, s.day_of_week, s.start_time, s.end_time, s.venue 
                         FROM excuse_requests er 
                         JOIN schedules s ON er.schedule_id = s.id 
                         JOIN units un ON s.unit_id = un.id 
                         WHERE er.student_id = :student_id 
                         ORDER BY er.created_at DESC");
        $this->db->bind(':student_id', $student_id);
        return $this->db->resultSet();
    }

    public function findById($id) {
        $this->db->query("SELECT er.*, u.full_name as student_name, un.unit_name, s.day_of_week, s.start_time, s.end_time, s.venue, responder.full_name as responder_name FROM excuse_requests er JOIN users u ON er.student_id = u.id JOIN schedules s ON er.schedule_id = s.id JOIN units un ON s.unit_id = un.id LEFT JOIN users responder ON er.responded_by = responder.id WHERE er.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query("INSERT INTO excuse_requests (student_id, schedule_id, date, reason) VALUES (:student_id, :schedule_id, :date, :reason)");
        $this->db->bind(':student_id', $data['student_id']);
        $this->db->bind(':schedule_id', $data['schedule_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':reason', $data['reason']);
        return $this->db->execute();
    }

    /**
     * Check if an excuse request already exists for the same student, schedule and date
     * Returns true if exists, false otherwise
     */
    public function exists($student_id, $schedule_id, $date) {
        $this->db->query("SELECT id FROM excuse_requests WHERE student_id = :student_id AND schedule_id = :schedule_id AND date = :date LIMIT 1");
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':schedule_id', $schedule_id);
        $this->db->bind(':date', $date);
        $res = $this->db->single();
        return $res ? true : false;
    }

    public function updateStatus($id, $status, $responded_by) {
        $this->db->query("UPDATE excuse_requests SET status = :status, responded_at = NOW(), responded_by = :responded_by WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        $this->db->bind(':responded_by', $responded_by);
        return $this->db->execute();
    }

    public function getPendingCountByLecturer($lecturer_id) {
        $this->db->query("SELECT COUNT(er.id) as count FROM excuse_requests er JOIN schedules s ON er.schedule_id = s.id WHERE s.lecturer_id = :lecturer_id AND er.status = 'pending'");
        $this->db->bind(':lecturer_id', $lecturer_id);
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    public function getApprovedRequestsByScheduleAndDate($schedule_id, $date) {
        $this->db->query("SELECT er.student_id FROM excuse_requests er WHERE er.schedule_id = :schedule_id AND er.date = :date AND er.status = 'approved'");
        $this->db->bind(':schedule_id', $schedule_id);
        $this->db->bind(':date', $date);
        return $this->db->resultSet();
    }
}
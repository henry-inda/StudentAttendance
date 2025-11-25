<?php
class Unit {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $this->db->query("SELECT units.*, courses.course_name, users.full_name as lecturer_name FROM units JOIN courses ON units.course_id = courses.id LEFT JOIN users ON units.lecturer_id = users.id");
        return $this->db->resultSet();
    }

    public function getByCourse($course_id) {
        $this->db->query("SELECT * FROM units WHERE course_id = :course_id");
        $this->db->bind(':course_id', $course_id);
        return $this->db->resultSet();
    }

    public function getByLecturer($lecturer_id) {
        $this->db->query("SELECT * FROM units WHERE lecturer_id = :lecturer_id");
        $this->db->bind(':lecturer_id', $lecturer_id);
        return $this->db->resultSet();
    }

    public function findById($id) {
        $this->db->query("SELECT * FROM units WHERE id = :id");
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        return $result;
    }

    public function create($data) {
        $this->db->query("INSERT INTO units (course_id, unit_name, unit_code, semester) VALUES (:course_id, :unit_name, :unit_code, :semester)");
        $this->db->bind(':course_id', $data['course_id']);
        $this->db->bind(':unit_name', $data['unit_name']);
        $this->db->bind(':unit_code', $data['unit_code']);
        $this->db->bind(':semester', $data['semester']);
        return $this->db->execute();
    }

    public function update($id, $data) {
        $this->db->query("UPDATE units SET course_id = :course_id, unit_name = :unit_name, unit_code = :unit_code, semester = :semester, status = :status WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':course_id', $data['course_id']);
        $this->db->bind(':unit_name', $data['unit_name']);
        $this->db->bind(':unit_code', $data['unit_code']);
        $this->db->bind(':semester', $data['semester']);
        $this->db->bind(':status', $data['status']);
        return $this->db->execute();
    }

    public function assignLecturer($unit_id, $lecturer_id) {
        $this->db->query("UPDATE units SET lecturer_id = :lecturer_id WHERE id = :unit_id");
        $this->db->bind(':unit_id', $unit_id);
        $this->db->bind(':lecturer_id', $lecturer_id);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM units WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function createFromCsv($data) {
        $this->db->query("INSERT INTO units (course_id, unit_name, unit_code, lecturer_id, semester) VALUES (:course_id, :unit_name, :unit_code, :lecturer_id, :semester)");
        $this->db->bind(':course_id', $data['course_id']);
        $this->db->bind(':unit_name', $data['unit_name']);
        $this->db->bind(':unit_code', $data['unit_code']);
        $this->db->bind(':lecturer_id', $data['lecturer_id']);
        $this->db->bind(':semester', $data['semester']);
        return $this->db->execute();
    }
}

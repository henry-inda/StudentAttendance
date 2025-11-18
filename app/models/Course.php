<?php
class Course {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $this->db->query("SELECT courses.*, departments.name as department_name FROM courses JOIN departments ON courses.department_id = departments.id");
        return $this->db->resultSet();
    }

    public function getByDepartment($dept_id) {
        $this->db->query("SELECT * FROM courses WHERE department_id = :dept_id");
        $this->db->bind(':dept_id', $dept_id);
        return $this->db->resultSet();
    }

    public function findById($id) {
        $this->db->query("SELECT * FROM courses WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query("INSERT INTO courses (department_id, course_name, course_code) VALUES (:department_id, :course_name, :course_code)");
        $this->db->bind(':department_id', $data['department_id']);
        $this->db->bind(':course_name', $data['course_name']);
        $this->db->bind(':course_code', $data['course_code']);
        return $this->db->execute();
    }

    public function update($id, $data) {
        $this->db->query("UPDATE courses SET department_id = :department_id, course_name = :course_name, course_code = :course_code, status = :status WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':department_id', $data['department_id']);
        $this->db->bind(':course_name', $data['course_name']);
        $this->db->bind(':course_code', $data['course_code']);
        $this->db->bind(':status', $data['status']);
        return $this->db->execute();
    }

    public function findByName($name) {
        $this->db->query("SELECT * FROM courses WHERE course_name = :name");
        $this->db->bind(':name', $name);
        return $this->db->single();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM courses WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getUnitsByCourseAndSemester($course_id, $semester) {
        $this->db->query("SELECT * FROM units WHERE course_id = :course_id AND semester = :semester");
        $this->db->bind(':course_id', $course_id);
        $this->db->bind(':semester', $semester);
        return $this->db->resultSet();
    }
}

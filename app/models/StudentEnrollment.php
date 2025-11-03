<?php
class StudentEnrollment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $this->db->query("SELECT student_enrollments.*, users.full_name as student_name, users.email as student_email, courses.course_name FROM student_enrollments JOIN users ON student_enrollments.student_id = users.id JOIN courses ON student_enrollments.course_id = courses.id");
        return $this->db->resultSet();
    }

    public function getByStudent($student_id) {
        $this->db->query("SELECT * FROM student_enrollments WHERE student_id = :student_id");
        $this->db->bind(':student_id', $student_id);
        return $this->db->resultSet();
    }

    public function getByCourse($course_id) {
        $this->db->query("SELECT student_enrollments.*, users.full_name FROM student_enrollments JOIN users ON student_enrollments.student_id = users.id WHERE course_id = :course_id");
        $this->db->bind(':course_id', $course_id);
        return $this->db->resultSet();
    }

    public function create($data) {
        $this->db->query("INSERT INTO student_enrollments (student_id, course_id, enrollment_date) VALUES (:student_id, :course_id, :enrollment_date)");
        $this->db->bind(':student_id', $data['student_id']);
        $this->db->bind(':course_id', $data['course_id']);
        $this->db->bind(':enrollment_date', $data['enrollment_date']);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM student_enrollments WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function bulkInsert($data) {
        // This would require a more complex implementation to handle multiple inserts efficiently and safely.
        // For now, it will just loop and call create.
        $count = 0;
        foreach ($data as $row) {
            if ($this->create($row)) {
                $count++;
            }
        }
        return $count;
    }
}

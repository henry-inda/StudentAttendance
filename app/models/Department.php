<?php
class Department {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $this->db->query("SELECT * FROM departments");
        return $this->db->resultSet();
    }

    public function findById($id) {
        $this->db->query("SELECT * FROM departments WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query("INSERT INTO departments (name, code, description) VALUES (:name, :code, :description)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':description', $data['description']);
        return $this->db->execute();
    }

    public function update($id, $data) {
        $this->db->query("UPDATE departments SET name = :name, code = :code, description = :description, status = :status WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':status', $data['status']);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM departments WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}

<?php
class AccountRequest {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createRequest($data) {
        $this->db->query('INSERT INTO account_requests (full_name, email, role, reg_number, course, employee_id, department) VALUES (:full_name, :email, :role, :reg_number, :course, :employee_id, :department)');
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':reg_number', $data['reg_number']);
        $this->db->bind(':course', $data['course']);
        $this->db->bind(':employee_id', $data['employee_id']);
        $this->db->bind(':department', $data['department']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getLastInsertedId() {
        return $this->db->lastInsertId();
    }

    public function getPendingRequests() {
        $this->db->query('SELECT * FROM account_requests WHERE status = :status');
        $this->db->bind(':status', 'pending');
        return $this->db->resultSet();
    }

    public function getRequestById($id) {
        $this->db->query('SELECT * FROM account_requests WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function findPendingByEmail($email) {
        $this->db->query('SELECT * FROM account_requests WHERE email = :email AND status = :status');
        $this->db->bind(':email', $email);
        $this->db->bind(':status', 'pending');
        return $this->db->single();
    }

    public function approveRequest($id) {
        $this->db->query('UPDATE account_requests SET status = :status WHERE id = :id');
        $this->db->bind(':status', 'approved');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function rejectRequest($id) {
        $this->db->query('UPDATE account_requests SET status = :status WHERE id = :id');
        $this->db->bind(':status', 'rejected');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

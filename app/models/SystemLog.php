<?php
class SystemLog {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll($filters = []) {
        $this->db->query("SELECT system_logs.*, users.full_name as user_name FROM system_logs LEFT JOIN users ON system_logs.user_id = users.id ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function logActivity($user_id, $action, $details, $ip) {
        return $this->log($user_id, $action, $details, $ip);
    }

    public function log($user_id, $action, $details, $ip) {
        $this->db->query("INSERT INTO system_logs (user_id, action, details, ip_address) VALUES (:user_id, :action, :details, :ip_address)");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':action', $action);
        $this->db->bind(':details', $details);
        $this->db->bind(':ip_address', $ip);
        return $this->db->execute();
    }
}

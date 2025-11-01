<?php
class SystemSetting {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function get($key) {
        $this->db->query("SELECT setting_value FROM system_settings WHERE setting_key = :key");
        $this->db->bind(':key', $key);
        $row = $this->db->single();
        return $row ? $row->setting_value : null;
    }

    public function set($key, $value) {
        $this->db->query("UPDATE system_settings SET setting_value = :value WHERE setting_key = :key");
        $this->db->bind(':key', $key);
        $this->db->bind(':value', $value);
        return $this->db->execute();
    }

    public function getAll() {
        $this->db->query("SELECT * FROM system_settings");
        $results = $this->db->resultSet();
        $settings = [];
        foreach ($results as $row) {
            $settings[$row->setting_key] = $row->setting_value;
        }
        return $settings;
    }
}

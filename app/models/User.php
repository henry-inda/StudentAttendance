<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        return $row;
    }

    public function login($email, $password) {
        $row = $this->findByEmail($email);

        if ($row) {
            $hashed_password = $row->password;
            if (password_verify($password, $hashed_password)) {
                return $row;
            }
        }

        return false;
    }

    public function findById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        return $row;
    }

    public function create($data) {
        // Validate password strength before creating user
        if (!self::validatePasswordStrength($data['password'])) {
            error_log('Password validation failed');
            return false; // Or throw an exception
        }

        error_log('Creating user with data: ' . print_r($data, true));

        $this->db->query('INSERT INTO users (full_name, email, password, role, department_id, course_id, phone) VALUES (:full_name, :email, :password, :role, :department_id, :course_id, :phone)');
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':department_id', $data['department_id']);
        $this->db->bind(':course_id', $data['course_id']);
        $this->db->bind(':phone', $data['phone']);

        $result = $this->db->execute();
        
        if ($result) {
            $user_id = $this->db->lastInsertId();

            // If the created user is a student and has a course_id, enroll them in units
            if ($data['role'] === 'student' && !empty($data['course_id'])) {
                // Load necessary models
                $systemSettingModel = new SystemSetting();
                $courseModel = new Course();
                $studentEnrollmentModel = new StudentEnrollment();

                // Get current semester using the new method
                $current_semester = $systemSettingModel->getCurrentSemester();

                if ($current_semester) {
                    // Get all units for the student's course in the current semester
                    $units = $courseModel->getUnitsByCourseAndSemester($data['course_id'], $current_semester);

                    foreach ($units as $unit) {
                        // Enroll student in each unit
                        if (!$studentEnrollmentModel->isStudentEnrolledInUnit($user_id, $unit->id)) {
                            $studentEnrollmentModel->enrollStudentInUnit($user_id, $unit->id);
                        }
                    }
                } else {
                    error_log('System setting "current_semester" not found. Automatic unit enrollment skipped.');
                }
            }
            return true;
        }
        return false;
    }

    public function createFromCsv($data) {
        $this->db->query('INSERT INTO users (full_name, email, password, role, department_id, course_id, phone, status) VALUES (:full_name, :email, :password, :role, :department_id, :course_id, :phone, :status)');
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':department_id', $data['department_id']);
        $this->db->bind(':course_id', $data['course_id']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    public function getLastInsertedId() {
        return $this->db->lastInsertId();
    }

    public function update($data) {
        // Start building the query
        $sql = 'UPDATE users SET full_name = :full_name, email = :email, role = :role, 
                department_id = :department_id, phone = :phone, status = :status';
        
        // Add password to query if it's being updated
        if (!empty($data['password'])) {
            $sql .= ', password = :password';
        }
        
        $sql .= ' WHERE id = :id';
        
        $this->db->query($sql);
        
        // Bind the basic parameters
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':department_id', $data['department_id']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':status', $data['status']);
        
        // Bind password if it's being updated
        if (!empty($data['password'])) {
            $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        }

        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    public function getAllUsers() {
        $this->db->query('SELECT * FROM users');
        return $this->db->resultSet();
    }

    public function getByRole($role) {
        $this->db->query('SELECT * FROM users WHERE role = :role AND status = \'active\'');
        $this->db->bind(':role', $role);
        $results = $this->db->resultSet();

        return $results;
    }

    public function updatePassword($id, $new_password) {
        // Validate password strength before updating
        if (!self::validatePasswordStrength($new_password)) {
            return false; // Or throw an exception
        }

        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':password', password_hash($new_password, PASSWORD_DEFAULT));

        return $this->db->execute();
    }

    public function updateProfile($id, $data) {
        $this->db->query('UPDATE users SET full_name = :full_name, phone = :phone, profile_picture = :profile_picture WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':profile_picture', $data['profile_picture']);

        return $this->db->execute();
    }

    public static function validatePasswordStrength($password) {
        // Minimum 8 characters
        if (strlen($password) < 8) {
            return false;
        }
        // At least 1 uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        // At least 1 lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        // At least 1 number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        return true;
    }

    public function generatePasswordResetToken($email) {
        $user = $this->findByEmail($email);
        if (!$user) {
            return false;
        }

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // 1 hour expiry

        $this->db->query('UPDATE users SET password_reset_token = :token, password_reset_expires_at = :expires WHERE id = :id');
        $this->db->bind(':token', $token);
        $this->db->bind(':expires', $expires);
        $this->db->bind(':id', $user->id);

        if ($this->db->execute()) {
            return $token;
        }
        return false;
    }

    public function findUserByPasswordResetToken($token) {
        $this->db->query('SELECT * FROM users WHERE password_reset_token = :token AND password_reset_expires_at > NOW()');
        $this->db->bind(':token', $token);
        $row = $this->db->single();
        return $row;
    }

    public function resetPasswordWithToken($token, $new_password) {
        $user = $this->findUserByPasswordResetToken($token);
        if (!$user) {
            return false;
        }

        // Validate password strength before updating
        if (!self::validatePasswordStrength($new_password)) {
            return false;
        }

        $this->db->query('UPDATE users SET password = :password, password_reset_token = NULL, password_reset_expires_at = NULL WHERE id = :id');
        $this->db->bind(':password', password_hash($new_password, PASSWORD_DEFAULT));
        $this->db->bind(':id', $user->id);

        return $this->db->execute();
    }
}

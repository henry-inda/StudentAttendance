<?php
class Notification {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getByUser($user_id, $unread_only = false, $limit = null) {
        $sql = "SELECT * FROM notifications WHERE user_id = :user_id";
        if ($unread_only) {
            $sql .= " AND is_read = 0";
        }
        $sql .= " ORDER BY created_at DESC";
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }
        $this->db->query($sql);
        $this->db->bind(':user_id', $user_id);
        if ($limit !== null) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        }
        
        $results = $this->db->resultSet();
        // Add the generated link to each notification
        foreach ($results as $row) {
            $row->link = $this->generateLink($row);
        }
        return $results;
    }

    public function generateLink($notification) {
        $link = '#'; // Default link
        $related_id = isset($notification->related_id) ? $notification->related_id : null;

        switch ($notification->type) {
            case 'account_request':
                // Link to the admin page for viewing a specific request
                $link = 'admin/requests/show/' . $notification->id . '/' . $related_id;
                break;
            case 'excuse_request':
                // Link to the lecturer page for viewing a specific excuse request
                $link = 'lecturer/excuse_requests/view/' . $related_id;
                break;
            case 'new_unit_enrolment':
                // Link to the course details page
                $link = 'student/courses/view/' . $related_id;
                break;
            // Add other cases as needed
        }

        // Return a full URL
        return BASE_URL . $link;
    }


    public function createNotification($user_id, $type, $title, $message, $related_id = null) {
        return $this->create($user_id, $type, $title, $message, $related_id);
    }

    public function create($user_id, $type, $title, $message, $related_id = null) {
        $this->db->query("INSERT INTO notifications (user_id, type, title, message, related_id) 
                         VALUES (:user_id, :type, :title, :message, :related_id)");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':type', $type);
        $this->db->bind(':title', $title);
        $this->db->bind(':message', $message);
        $this->db->bind(':related_id', $related_id);
        return $this->db->execute();
    }

    public function markAsRead($id) {
        $this->db->query("UPDATE notifications SET is_read = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function markAllAsRead($user_id) {
        $this->db->query("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function getUnreadCount($user_id) {
        $this->db->query("SELECT COUNT(*) as count FROM notifications WHERE user_id = :user_id AND is_read = 0");
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        return $row ? $row->count : 0;
    }

    public function getRecentNotifications($user_id, $limit = 5) {
        return $this->getByUser($user_id, false, $limit);
    }
}
<?php
class AttendanceModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getBySchedule($schedule_id, $date) {
        $this->db->query("SELECT attendance.*, users.full_name as student_name FROM attendance JOIN users ON attendance.student_id = users.id WHERE schedule_id = :schedule_id AND date = :date");
        $this->db->bind(':schedule_id', $schedule_id);
        $this->db->bind(':date', $date);
        return $this->db->resultSet();
    }

    public function markAttendance($data) {
        $this->db->query("INSERT INTO attendance (schedule_id, student_id, date, status, marked_by, notes) VALUES (:schedule_id, :student_id, :date, :status, :marked_by, :notes)");
        $this->db->bind(':schedule_id', $data['schedule_id']);
        $this->db->bind(':student_id', $data['student_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':marked_by', $data['marked_by']);
        $this->db->bind(':notes', $data['notes']);
        return $this->db->execute();
    }

    public function getByStudentAndUnit($student_id, $unit_id) {
        $this->db->query("SELECT a.*, s.day_of_week, s.start_time, s.end_time, s.venue FROM attendance a JOIN schedules s ON a.schedule_id = s.id WHERE a.student_id = :student_id AND s.unit_id = :unit_id ORDER BY a.date DESC");
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':unit_id', $unit_id);
        return $this->db->resultSet();
    }

    public function getUnitAttendanceStatsForStudent($student_id, $unit_id) {
        $this->db->query("
            SELECT 
                COUNT(a.id) as total_classes,
                SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN a.status = 'excused' THEN 1 ELSE 0 END) as excused_count
            FROM attendance a
            JOIN schedules s ON a.schedule_id = s.id
            WHERE a.student_id = :student_id AND s.unit_id = :unit_id
        ");
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':unit_id', $unit_id);
        $stats = $this->db->single();

        if ($stats->total_classes > 0) {
            $stats->percentage = round(($stats->present_count / $stats->total_classes) * 100, 2);
        } else {
            $stats->percentage = 0;
        }
        return $stats;
    }

    public function getAttendancePercentageForStudentAndUnit($student_id, $unit_id) {
        $stats = $this->getUnitAttendanceStatsForStudent($student_id, $unit_id);
        return $stats->percentage;
    }

    public function updateAttendance($id, $status) {
        $this->db->query("UPDATE attendance SET status = :status WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    /**
     * Return attendance trend (percentage present) for a student in a specific unit over the last $weeks weeks.
     * Returns array with 'labels' and 'data' numeric percentages (0-100) for each week (oldest first).
     */
    public function getAttendanceTrendForStudentAndUnit($student_id, $unit_id, $weeks = 8) {
        // Calculate date window
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-" . ($weeks - 1) . " weeks", strtotime($endDate)));

        // Get aggregated counts grouped by YEAR-WEEK
        $this->db->query("SELECT YEAR(date) as yy, WEEK(date, 1) as ww,
            SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_count,
            COUNT(a.id) as total_count
            FROM attendance a
            JOIN schedules s ON a.schedule_id = s.id
            WHERE a.student_id = :student_id
            AND s.unit_id = :unit_id
            AND a.date BETWEEN :start_date AND :end_date
            GROUP BY yy, ww
            ORDER BY yy, ww");
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':unit_id', $unit_id);
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        $rows = $this->db->resultSet();

        // Convert rows to map by YEAR-WEEK key (e.g., 2025-40)
        $map = [];
        foreach ($rows as $r) {
            $yy = (int)$r->yy;
            $ww = str_pad((int)$r->ww, 2, '0', STR_PAD_LEFT);
            $key = $yy . '-' . $ww;
            $map[$key] = [
                'present' => (int)$r->present_count,
                'total' => (int)$r->total_count
            ];
        }

        // Build labels (week numbers) and data for each week in the window.
        // Use null for weeks with no data so Chart.js will render gaps.
        $labels = [];
        $data = [];
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $weekStart = strtotime("-" . $i . " weeks", strtotime($endDate));
            // ISO week year and number
            $year = date('o', $weekStart);
            $weekNum = date('W', $weekStart);
            $key = $year . '-' . $weekNum;

            // Label format: Week {WW} (use numeric week without leading zero)
            $labels[] = 'Week ' . intval($weekNum);

            if (isset($map[$key]) && $map[$key]['total'] > 0) {
                $pct = round(($map[$key]['present'] / $map[$key]['total']) * 100, 2);
                $data[] = $pct;
            } else {
                // Use null to indicate missing data for that week
                $data[] = null;
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
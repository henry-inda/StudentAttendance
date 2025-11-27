<?php
class AttendanceModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getOverallAttendancePercentage($student_id) {
        $this->db->query("
            SELECT 
                COUNT(id) as total_classes,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count
            FROM attendance
            WHERE student_id = :student_id
        ");
        $this->db->bind(':student_id', $student_id);
        $stats = $this->db->single();

        if ($stats && $stats->total_classes > 0) {
            return round(($stats->present_count / $stats->total_classes) * 100, 2);
        } else {
            return 0;
        }
    }

    public function getUnitsBelowThreshold($student_id, $threshold) {
        $this->db->query("
            SELECT u.id AS unit_id, u.unit_name, u.unit_code
            FROM units u
            JOIN courses c ON u.course_id = c.id
            JOIN student_enrollments se ON c.id = se.course_id
            WHERE se.student_id = :student_id AND se.status = 'enrolled'
        ");
        $this->db->bind(':student_id', $student_id);
        $units = $this->db->resultSet();

        $unitsBelowThreshold = [];
        foreach ($units as $unit) {
            $attendancePercentage = $this->getAttendancePercentageForStudentAndUnit($student_id, $unit->unit_id);
            if ($attendancePercentage < $threshold) {
                $unit->attendance_percentage = $attendancePercentage;
                $unitsBelowThreshold[] = $unit;
            }
        }

        return $unitsBelowThreshold;
    }

    public function getEnrolledUnitsWithAttendance($student_id) {
        $this->db->query("
            SELECT u.id AS unit_id, u.unit_name, u.unit_code
            FROM units u
            JOIN student_unit_enrollments sue ON u.id = sue.unit_id
            WHERE sue.student_id = :student_id
        ");
        $this->db->bind(':student_id', $student_id);
        $units = $this->db->resultSet();

        foreach ($units as $unit) {
            $stats = $this->getUnitAttendanceStatsForStudent($student_id, $unit->unit_id);
            $unit->attendance_percentage = $stats->percentage;
            $unit->present_count = $stats->present_count;
            $unit->total_classes = $stats->total_classes;
        }

        return $units;
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

    public function upsertAttendance($data) {
        $existingRecord = $this->getByScheduleAndStudent($data['schedule_id'], $data['student_id'], $data['date']);

        if ($existingRecord) {
            // Update existing record
            $this->db->query("UPDATE attendance SET status = :status, notes = :notes, marked_by = :marked_by WHERE id = :id");
            $this->db->bind(':id', $existingRecord->id);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':notes', $data['notes']);
            $this->db->bind(':marked_by', $data['marked_by']);
            return $this->db->execute();
        } else {
            // Insert new record
            return $this->markAttendance($data);
        }
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

    public function getByScheduleAndStudent($schedule_id, $student_id, $date) {
        $this->db->query("SELECT * FROM attendance WHERE schedule_id = :schedule_id AND student_id = :student_id AND date = :date");
        $this->db->bind(':schedule_id', $schedule_id);
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':date', $date);
        return $this->db->single();
    }

    public function markAsExcused($student_id, $schedule_id, $date, $marked_by) {
        $attendanceRecord = $this->getByScheduleAndStudent($schedule_id, $student_id, $date);

        if ($attendanceRecord) {
            return $this->updateAttendance($attendanceRecord->id, 'excused');
        } else {
            // If no record exists, create one with 'excused' status.
            $data = [
                'schedule_id' => $schedule_id,
                'student_id' => $student_id,
                'date' => $date,
                'status' => 'excused',
                'marked_by' => $marked_by,
                'notes' => 'Excused due to approved request'
            ];
            return $this->markAttendance($data);
        }
    }

    public function getAttendanceByUnit($unit_id) {
        $this->db->query("
            SELECT
                a.id,
                a.date,
                a.status,
                a.notes,
                u.full_name AS student_name,
                s.day_of_week,
                s.start_time,
                s.end_time,
                s.venue
            FROM
                attendance a
            JOIN
                users u ON a.student_id = u.id
            JOIN
                schedules s ON a.schedule_id = s.id
            WHERE
                s.unit_id = :unit_id
            ORDER BY
                a.date DESC, s.start_time ASC, u.full_name ASC;
        ");
        $this->db->bind(':unit_id', $unit_id);
        return $this->db->resultSet();
    }
}
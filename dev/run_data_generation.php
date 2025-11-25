<?php
// dev/run_data_generation.php

require_once 'app/config/config.php';
require_once 'app/core/Database.php';

class DataGenerator {
    private $db;
    private $users;
    private $courses;
    private $units;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->loadInitialData();
    }

    private function loadInitialData() {
        $this->db->query("SELECT * FROM users");
        $this->users = $this->db->resultSet();

        $this->db->query("SELECT * FROM courses");
        $this->courses = $this->db->resultSet();

        $this->db->query("SELECT * FROM units");
        $this->units = $this->db->resultSet();
    }

    public function run() {
        $this->truncateTables();
        $this->generateSchedules();
        $this->generateAttendance();
        $this->generateExcuseRequest();
    }

    private function truncateTables() {
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        $this->db->execute();
        $this->db->query("TRUNCATE TABLE schedules");
        $this->db->execute();
        $this->db->query("TRUNCATE TABLE attendance");
        $this->db->execute();
        $this->db->query("TRUNCATE TABLE excuse_requests");
        $this->db->execute();
        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
        $this->db->execute();
    }

    private function generateSchedules() {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $times = ['08:00:00', '10:00:00', '12:00:00', '14:00:00', '16:00:00'];

        $lecturers = array_filter($this->users, function($user) {
            return $user->role == 'lecturer';
        });

        foreach ($this->units as $unit) {
            $day = $days[array_rand($days)];
            $time = $times[array_rand($times)];
            $endTime = date('H:i:s', strtotime($time) + 2 * 60 * 60); // 2 hour class

            $lecturer_id = $unit->lecturer_id;
            if (empty($lecturer_id)) {
                $lecturer_id = $lecturers[array_rand($lecturers)]->id;
            }

            $this->db->query("INSERT INTO schedules (unit_id, lecturer_id, day_of_week, start_time, end_time, venue, semester) VALUES (:unit_id, :lecturer_id, :day_of_week, :start_time, :end_time, :venue, :semester)");
            $this->db->bind(':unit_id', $unit->id);
            $this->db->bind(':lecturer_id', $lecturer_id);
            $this->db->bind(':day_of_week', $day);
            $this->db->bind(':start_time', $time);
            $this->db->bind(':end_time', $endTime);
            $this->db->bind(':venue', 'Room ' . rand(1, 10));
            $this->db->bind(':semester', $unit->semester);
            $this->db->execute();
        }
    }

    private function generateAttendance() {
        $this->db->query("SELECT * FROM schedules");
        $schedules = $this->db->resultSet();

        $students = array_filter($this->users, function($user) {
            return $user->role == 'student';
        });

        $absentStudents = array_slice($students, 0, 3);
        $absentStudentIds = array_map(function($student) {
            return $student->id;
        }, $absentStudents);

        $startDate = new DateTime('2025-10-01');
        $endDate = new DateTime('2025-10-31');

        for ($date = $startDate; $date <= $endDate; $date->modify('+1 day')) {
            $dayOfWeek = $date->format('l');
            foreach ($schedules as $schedule) {
                if ($schedule->day_of_week == $dayOfWeek) {
                    foreach ($students as $student) {
                        $status = 'present';
                        // Make the first 3 students absent for the first two weeks
                        if (in_array($student->id, $absentStudentIds) && $date->format('W') <= (new DateTime('2025-10-14'))->format('W')) {
                            $status = 'absent';
                        }

                        $this->db->query("INSERT INTO attendance (schedule_id, student_id, date, status, marked_by) VALUES (:schedule_id, :student_id, :date, :status, :marked_by)");
                        $this->db->bind(':schedule_id', $schedule->id);
                        $this->db->bind(':student_id', $student->id);
                        $this->db->bind(':date', $date->format('Y-m-d'));
                        $this->db->bind(':status', $status);
                        $this->db->bind(':marked_by', $schedule->lecturer_id);
                        $this->db->execute();
                    }
                }
            }
        }
    }

    private function generateExcuseRequest() {
        $this->db->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $student = $this->db->single();

        $this->db->query("SELECT * FROM schedules LIMIT 1");
        $schedule = $this->db->single();

        $this->db->query("INSERT INTO excuse_requests (student_id, schedule_id, date, reason, status) VALUES (:student_id, :schedule_id, :date, :reason, :status)");
        $this->db->bind(':student_id', $student->id);
        $this->db->bind(':schedule_id', $schedule->id);
        $this->db->bind(':date', '2025-10-06');
        $this->db->bind(':reason', 'Attended a funeral home');
        $this->db->bind(':status', 'pending');
        $this->db->execute();
    }
}

$generator = new DataGenerator();
$generator->run();

echo "Data generation complete.\n";


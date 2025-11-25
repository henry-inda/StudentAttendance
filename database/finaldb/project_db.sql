-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2025 at 06:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','excused') NOT NULL,
  `marked_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `marked_by` int(11) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `schedule_id`, `student_id`, `date`, `status`, `marked_at`, `marked_by`, `notes`) VALUES
(1, 1, 1, '2025-11-03', 'absent', '2025-11-03 00:12:25', 2, ''),
(2, 1, 2, '2025-11-03', 'present', '2025-11-03 00:38:45', 3, ''),
(4, 1, 3, '2025-11-03', 'absent', '2025-11-03 00:40:09', 2, ''),
(5, 1, 3, '2025-11-03', 'absent', '2025-11-03 15:19:13', 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `department_id`, `course_name`, `course_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bachelor of Science in Computer Science', 'BSC-CS', 'active', '2025-10-30 07:52:27', '2025-10-30 07:52:27'),
(2, 2, 'Bachelor of Business Information Technology', 'BBIT', 'active', '2025-10-30 07:52:27', '2025-10-30 07:52:27');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Computer Science', 'CS', 'Department of Computer Science', 'active', '2025-10-30 07:52:27', '2025-10-30 07:52:27'),
(2, 'Business IT', 'BIT', 'Department of Business Information Technology', 'active', '2025-10-30 07:52:27', '2025-10-30 07:52:27');

-- --------------------------------------------------------

--
-- Table structure for table `excuse_requests`
--

CREATE TABLE `excuse_requests` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `responded_at` timestamp NULL DEFAULT NULL,
  `responded_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `excuse_requests`
--

INSERT INTO `excuse_requests` (`id`, `student_id`, `schedule_id`, `date`, `reason`, `status`, `created_at`, `responded_at`, `responded_by`) VALUES
(1, 2, 1, '2025-11-01', 'Test excuse request submitted via runner.', 'rejected', '2025-11-01 10:57:22', '2025-11-01 12:08:13', 2),
(2, 2, 1, '2025-11-01', 'Test excuse request submitted via runner.', 'rejected', '2025-11-01 10:57:42', '2025-11-01 12:08:09', 2),
(3, 2, 1, '2025-11-01', 'Runner test: excuse request inserted via direct model test.', 'rejected', '2025-11-01 11:09:20', '2025-11-01 12:08:06', 2),
(4, 3, 1, '2025-11-07', 'ill be going home for deathroll', 'approved', '2025-11-01 12:07:16', '2025-11-01 12:08:21', 2),
(5, 3, 1, '2025-11-03', 'going home some family function', 'approved', '2025-11-02 18:19:20', '2025-11-02 18:20:10', 2),
(6, 3, 1, '2025-11-10', '3fgwrhethjj,kjk.lk;l;ljkhmjghfgdssaadfk,gjl.hkl,jgkmhfjgdhfbgdvfscD', 'rejected', '2025-11-02 18:43:25', '2025-11-02 18:43:50', 2),
(7, 3, 1, '2025-11-17', 'dfgh\';afDSfeetqtb2ebt32t3btv3t3', 'approved', '2025-11-02 23:44:41', '2025-11-03 01:20:43', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `related_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `related_id`, `is_read`, `created_at`) VALUES
(1, 3, 'venue_update', 'Class Venue Updated', 'The venue for your class (Data Structures and Algorithms) on Monday from 14:30 to 15:00:00 has been updated to: BBIT R1.', 3, 1, '2025-11-03 11:12:11');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `venue` varchar(50) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `status` enum('active','cancelled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `unit_id`, `lecturer_id`, `day_of_week`, `start_time`, `end_time`, `venue`, `semester`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Monday', '07:00:00', '09:00:00', 'LH2', 'SEP/DEC', 'active', '2025-10-30 18:31:57', '2025-11-02 17:11:09'),
(3, 2, 2, 'Monday', '14:30:00', '15:00:00', 'BBIT R1', 'SEP/DEC', 'active', '2025-11-03 10:40:31', '2025-11-03 11:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `student_enrollments`
--

CREATE TABLE `student_enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enrollment_date` date NOT NULL,
  `status` enum('enrolled','unenrolled') NOT NULL DEFAULT 'enrolled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_enrollments`
--

INSERT INTO `student_enrollments` (`id`, `student_id`, `course_id`, `enrollment_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 1, '2025-10-29', 'enrolled', '2025-10-30 18:43:19', '2025-10-30 18:43:19');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 1, 'PHP Exception', 'Uncaught Exception: syntax error, unexpected token \"else\" in C:\\xampp\\htdocs\\StudentAttendance\\app\\core\\App.php on line 43', '::1', '2025-11-01 09:01:46'),
(2, 1, 'PHP Exception', 'Uncaught Exception: syntax error, unexpected token \"else\" in C:\\xampp\\htdocs\\StudentAttendance\\app\\core\\App.php on line 43', '::1', '2025-11-01 09:01:49'),
(3, 1, 'PHP Exception', 'Uncaught Exception: syntax error, unexpected token \"else\" in C:\\xampp\\htdocs\\StudentAttendance\\app\\core\\App.php on line 43', '::1', '2025-11-01 09:01:51'),
(4, 1, 'PHP Exception', 'Uncaught Exception: syntax error, unexpected token \"else\" in C:\\xampp\\htdocs\\StudentAttendance\\app\\core\\App.php on line 43', '::1', '2025-11-01 09:01:52'),
(5, NULL, 'PHP Exception', 'Uncaught Exception: syntax error, unexpected token \"else\" in C:\\xampp\\htdocs\\StudentAttendance\\app\\core\\App.php on line 43', '::1', '2025-11-01 09:01:57'),
(6, 3, 'PHP Error', 'Error: [2] Undefined property: stdClass::$unit_id in C:\\xampp\\htdocs\\StudentAttendance\\app\\controllers\\student\\ExcuseRequests.php on line 36', '::1', '2025-11-01 10:40:56'),
(7, 3, 'PHP Error', 'Error: [2] Undefined property: stdClass::$unit_id in C:\\xampp\\htdocs\\StudentAttendance\\app\\controllers\\student\\ExcuseRequests.php on line 81', '::1', '2025-11-01 11:14:15'),
(8, 3, 'PHP Error', 'Error: [2] Undefined property: stdClass::$unit_id in C:\\xampp\\htdocs\\StudentAttendance\\app\\controllers\\student\\ExcuseRequests.php on line 81', '::1', '2025-11-01 11:14:17'),
(9, 3, 'PHP Error', 'Error: [2] Undefined property: stdClass::$unit_id in C:\\xampp\\htdocs\\StudentAttendance\\app\\controllers\\student\\ExcuseRequests.php on line 81', '::1', '2025-11-01 11:14:34'),
(10, 3, 'PHP Error', 'Error: [2] Undefined property: stdClass::$unit_id in C:\\xampp\\htdocs\\StudentAttendance\\app\\controllers\\student\\ExcuseRequests.php on line 81', '::1', '2025-11-01 11:14:50'),
(11, 3, 'PHP Error', 'Error: [2] Undefined property: stdClass::$unit_id in C:\\xampp\\htdocs\\StudentAttendance\\app\\controllers\\student\\ExcuseRequests.php on line 81', '::1', '2025-11-01 11:15:29'),
(12, 3, 'PHP Error', 'Error: [2] Undefined property: stdClass::$unit_id in C:\\xampp\\htdocs\\StudentAttendance\\app\\controllers\\student\\ExcuseRequests.php on line 81', '::1', '2025-11-01 11:15:30'),
(13, 3, 'PHP Error', 'Error: [2] Undefined property: stdClass::$unit_id in C:\\xampp\\htdocs\\StudentAttendance\\app\\controllers\\student\\MySchedule.php on line 22', '::1', '2025-11-01 11:15:35'),
(14, 3, 'PHP Error', 'Error: [2] Undefined property: stdClass::$unit_id in C:\\xampp\\htdocs\\StudentAttendance\\app\\controllers\\student\\ExcuseRequests.php on line 81', '::1', '2025-11-01 11:19:35'),
(15, 3, 'PHP Error', 'Error: [2] Undefined property: stdClass::$unit_id in C:\\xampp\\htdocs\\StudentAttendance\\app\\controllers\\student\\ExcuseRequests.php on line 81', '::1', '2025-11-01 11:19:37'),
(16, 2, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"Marked absent: Introduction to Programming on 2025-11-03\",\"message_id\":\"<zAykt6ZelMO0BSmCbDYBnfHZMaccZgIqCblhaFy1M@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-03 00:12:29'),
(17, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Marked absent: Introduction to Programming on 2025-11-03\",\"message_id\":\"<Yk7sFuzpdQmuZNX0dOUhstaN6B2JEAKWRB3YwY37iY@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-03 00:40:13'),
(18, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<kLHTAdbQqhSjRABiYMAypXOjUwXOXMrhLH3dpRtFkgs@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-03 11:12:15'),
(19, 0, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Password Reset Request\",\"message_id\":\"<LO3bMT4BGy6fTh3cYrkm77CRSnxZowU1zkRLtZINY@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-03 15:17:49'),
(20, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Marked absent: Introduction to Programming on 2025-11-03\",\"message_id\":\"<8rOR5vt7tujvOSb5ufJgPv61v1eUH6ZUQUhjpNgI@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-03 15:19:15');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'system_name', 'kabarak', '2025-11-01 16:52:04'),
(2, 'system_logo', 'default_logo.png', '2025-10-30 07:52:27'),
(3, 'attendance_threshold', '75', '2025-10-30 07:52:27'),
(4, 'app_email', 'howino810@gmail.com', '2025-10-30 07:52:27'),
(5, 'app_password', 'svcp kbva rhez wckv', '2025-10-30 07:52:27');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `unit_name` varchar(100) NOT NULL,
  `unit_code` varchar(20) NOT NULL,
  `lecturer_id` int(11) DEFAULT NULL,
  `semester` varchar(20) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `course_id`, `unit_name`, `unit_code`, `lecturer_id`, `semester`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Introduction to Programming', 'CS101', 2, 'JAN/APR', 'active', '2025-10-30 07:52:27', '2025-10-30 07:52:27'),
(2, 1, 'Data Structures and Algorithms', 'CS201', 2, 'MAY/AUG', 'active', '2025-10-30 07:52:27', '2025-10-30 07:52:27'),
(3, 2, 'Introduction to Business', 'BIT101', 2, 'JAN/APR', 'active', '2025-10-30 07:52:27', '2025-10-30 07:52:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','lecturer','student') NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `password_reset_expires_at` datetime DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `full_name`, `role`, `department_id`, `phone`, `profile_picture`, `password_reset_token`, `password_reset_expires_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 'howino810@gmail.com', '$2y$10$taTbE3p/jGOAMKW74ZBYJ.kFzlXeep8OCByTlVq0gOa0J42YONnPy', 'Admin User', 'admin', NULL, NULL, NULL, '0bb26ece8591ae3560cafc1a939198d65f56abf6c9d4a546e1cf990c35545de8', '2025-11-03 00:09:05', 'active', '2025-10-30 07:52:27', '2025-11-02 20:09:05'),
(2, 'henry@kabarak.ac.ke', '$2y$10$taTbE3p/jGOAMKW74ZBYJ.kFzlXeep8OCByTlVq0gOa0J42YONnPy', 'Henry Lecturer', 'lecturer', NULL, '0758832294', '', NULL, NULL, 'active', '2025-10-30 07:52:27', '2025-11-01 16:50:27'),
(3, 'lilflicky254@gmail.com', '$2y$10$X6DlgDLfDEJ3ATGo7zjbw.tOaJDKLNH5ISiXPaDzocmEEp8ItqqO.', 'Flick', 'student', 0, '', NULL, '17c3e618e520c9ad2de4f067e51c68c45ceeb6d0398c7ef7eaa063271d3797a6', '2025-11-03 19:17:42', 'active', '2025-10-30 07:52:27', '2025-11-03 15:17:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `excuse_requests`
--
ALTER TABLE `excuse_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `student_enrollments`
--
ALTER TABLE `student_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `excuse_requests`
--
ALTER TABLE `excuse_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_enrollments`
--
ALTER TABLE `student_enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `excuse_requests`
--
ALTER TABLE `excuse_requests`
  ADD CONSTRAINT `excuse_requests_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `excuse_requests_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_enrollments`
--
ALTER TABLE `student_enrollments`
  ADD CONSTRAINT `student_enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `units_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `units_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

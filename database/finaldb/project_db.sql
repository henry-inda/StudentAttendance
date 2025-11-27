-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2025 at 06:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `account_requests`
--

CREATE TABLE `account_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `reg_number` varchar(100) DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL,
  `employee_id` varchar(100) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_requests`
--

INSERT INTO `account_requests` (`id`, `full_name`, `email`, `role`, `reg_number`, `course`, `employee_id`, `department`, `status`, `created_at`) VALUES
(1, 'Owino', 'howino569@gmail.com', 'student', 'bscsf/mg/1390/09/22', 'Bachelor of Science in Computer Science', '', '', 'approved', '2025-11-25 15:57:01'),
(2, 'Test User 1764093110', 'testuser1764093110@example.com', 'student', 'TEST/1764093110', 'Computer Science', NULL, NULL, 'rejected', '2025-11-25 17:51:50'),
(5, 'Owino', 'howino569@gmail.com', 'student', 'bscsf/mg/1390/09/22', 'Bachelor of Science in Computer Science', '', '', 'rejected', '2025-11-25 21:35:02'),
(6, 'owino', 'howino569@gmail.com', 'student', 'bscsf/mg/1390/09/22', 'Bachelor of Science in Computer Science', '', '', 'approved', '2025-11-25 21:58:35'),
(7, 'Henry Inda', 'h1serverside@gmail.com', 'student', 'bscsf/mg/3050/09/22', 'Bachelor of Business Information Technology', '', '', 'approved', '2025-11-25 22:03:50'),
(8, 'Henry Inda', 'howino569@gmail.com', 'student', 'bscsf/mg/1390/09/22', 'Bachelor of Science in Computer Science', '', '', 'approved', '2025-11-25 22:18:45'),
(9, 'Henry Inda', 'howino569@gmail.com', 'student', 'bscsf/mg/1390/09/22', 'Bachelor of Science in Computer Science', '', '', 'approved', '2025-11-25 22:39:26'),
(10, 'Henry Inda', 'howino569@gmail.com', 'student', 'bscsf/mg/1390/09/22', 'Bachelor of Science in Computer Science', '', '', 'approved', '2025-11-25 22:43:25'),
(11, 'Henry Inda', 'howino569@gmail.com', 'student', 'bscsf/mg/1390/09/22', 'Bachelor of Science in Computer Science', '', '', 'approved', '2025-11-25 22:47:28'),
(12, 'Henry Inda', 'howino569@gmail.com', 'student', 'bscsf/mg/1390/09/22', 'Bachelor of Science in Computer Science', '', '', 'approved', '2025-11-25 22:51:04'),
(13, 'Henry Inda', 'howino569@gmail.com', 'student', 'bscsf/mg/1390/09/22', 'Bachelor of Science in Computer Science', '', '', 'approved', '2025-11-25 22:54:39'),
(14, 'Henry Inda', 'howino569@gmail.com', 'student', 'bscsf/mg/1390/09/22', 'Bachelor of Science in Computer Science', '', '', 'approved', '2025-11-25 23:23:20');

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
(11, 1, 3, '2025-11-26', 'present', '2025-11-26 06:28:25', 0, 'Marked via QR code scan');

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
(2, 'Business IT', 'BIT', 'Department of Business Information Technology', 'active', '2025-10-30 07:52:27', '2025-10-30 07:52:27'),
(4, 'School of Computing and Informatics', 'SCI', 'Fostering innovation in computing and informatics.', 'active', '2025-11-26 07:52:41', '2025-11-26 07:52:41'),
(5, 'School of Business and Economics', 'SBE', 'Developing future business leaders and economists.', 'active', '2025-11-26 07:52:41', '2025-11-26 07:52:41'),
(6, 'School of Health Sciences', 'SHS', 'Dedicated to improving health and well-being.', 'active', '2025-11-26 07:52:41', '2025-11-26 07:52:41'),
(7, 'School of Engineering', 'SOE', 'Advancing engineering knowledge and practice.', 'active', '2025-11-26 07:52:41', '2025-11-26 07:52:41'),
(8, 'School of Education', 'SED', 'Training the next generation of educators.', 'active', '2025-11-26 07:52:41', '2025-11-26 07:52:41');

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
(7, 3, 1, '2025-11-17', 'dfgh\';afDSfeetqtb2ebt32t3btv3t3', 'approved', '2025-11-02 23:44:41', '2025-11-03 01:20:43', 2),
(8, 13, 3, '2025-11-26', 'going home , for rest', 'approved', '2025-11-25 23:46:14', '2025-11-25 23:50:05', 2),
(9, 13, 1, '2025-11-26', 'birthday coming, maisha london', 'approved', '2025-11-26 01:09:53', '2025-11-26 01:10:08', 2);

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
(1, 3, 'venue_update', 'Class Venue Updated', 'The venue for your class (Data Structures and Algorithms) on Monday from 14:30 to 15:00:00 has been updated to: BBIT R1.', 3, 1, '2025-11-03 11:12:11'),
(2, 3, 'venue_update', 'Class Venue Updated', 'Your class (Introduction to Programming) was updated to:\nTuesday 07:00:00 to 09:00:00\nvenue:LH2', 1, 1, '2025-11-25 05:30:06'),
(3, 3, 'venue_update', 'Class Venue Updated', 'Your class (Data Structures and Algorithms) was updated to:\nTuesday 09:30 to 15:00:00\nvenue:BBIT R1', 3, 1, '2025-11-25 06:49:36'),
(4, 1, 'new_request', 'New Account Request', 'A new account request has been submitted.', 1, 1, '2025-11-25 15:57:07'),
(5, 3, 'venue_update', 'Class Venue Updated', 'Your class (Introduction to Programming) was updated to:\nWednesday 07:00:00 to 09:00:00\nvenue:LH2', 1, 1, '2025-11-25 17:32:09'),
(6, 3, 'venue_update', 'Class Venue Updated', 'Your class (Data Structures and Algorithms) was updated to:\nTuesday 09:30:00 to 15:30\nvenue:BBIT R1', 3, 1, '2025-11-25 21:06:53'),
(7, 1, 'account_request', 'New Account Request', 'A new account request has been submitted.', 6, 1, '2025-11-25 21:58:39'),
(8, 1, 'account_request', 'New Account Request', 'A new account request has been submitted.', 7, 1, '2025-11-25 22:03:54'),
(9, 1, 'account_request', 'New Account Request', 'A new account request has been submitted.', 8, 1, '2025-11-25 22:18:49'),
(10, 1, 'account_request', 'New Account Request', 'A new account request has been submitted.', 9, 1, '2025-11-25 22:39:30'),
(11, 1, 'account_request', 'New Account Request', 'A new account request has been submitted.', 10, 1, '2025-11-25 22:43:28'),
(12, 1, 'account_request', 'New Account Request', 'A new account request has been submitted.', 11, 1, '2025-11-25 22:47:32'),
(13, 1, 'account_request', 'New Account Request', 'A new account request has been submitted.', 12, 1, '2025-11-25 22:51:08'),
(14, 1, 'account_request', 'New Account Request', 'A new account request has been submitted.', 13, 1, '2025-11-25 22:54:43'),
(15, 1, 'account_request', 'New Account Request', 'A new account request has been submitted.', 14, 1, '2025-11-25 23:23:25'),
(16, 3, 'venue_update', 'Class Venue Updated', 'Your class (Data Structures and Algorithms) was updated to:\nWednesday 02:41 to 03:30\nvenue:BBIT R1', 3, 1, '2025-11-25 23:40:02'),
(17, 13, 'venue_update', 'Class Venue Updated', 'Your class (Data Structures and Algorithms) was updated to:\nWednesday 02:41 to 03:30\nvenue:BBIT R1', 3, 1, '2025-11-25 23:40:07'),
(18, 3, 'venue_update', 'Class Venue Updated', 'Your class (Data Structures and Algorithms) was updated to:\nWednesday 02:50 to 03:30:00\nvenue:BBIT R1', 3, 1, '2025-11-25 23:41:28'),
(19, 13, 'venue_update', 'Class Venue Updated', 'Your class (Data Structures and Algorithms) was updated to:\nWednesday 02:50 to 03:30:00\nvenue:BBIT R1', 3, 1, '2025-11-25 23:41:32'),
(20, 3, 'venue_update', 'Class Venue Updated', 'Your class (Introduction to Programming) was updated to:\nWednesday 04:15 to 06:00\nvenue:LH2', 1, 1, '2025-11-26 01:08:46'),
(21, 13, 'venue_update', 'Class Venue Updated', 'Your class (Introduction to Programming) was updated to:\nWednesday 04:15 to 06:00\nvenue:LH2', 1, 1, '2025-11-26 01:08:51'),
(22, 3, 'venue_update', 'Class Venue Updated', 'Your class (Introduction to Programming) was updated to:\nWednesday 04:15:00 to 07:00\nvenue:LH2', 1, 1, '2025-11-26 03:11:38'),
(23, 13, 'venue_update', 'Class Venue Updated', 'Your class (Introduction to Programming) was updated to:\nWednesday 04:15:00 to 07:00\nvenue:LH2', 1, 0, '2025-11-26 03:11:43'),
(24, 3, 'venue_update', 'Class Venue Updated', 'Your class (Introduction to Programming) was updated to:\nWednesday 04:15:00 to 07:30\nvenue:LH2', 1, 1, '2025-11-26 03:59:49'),
(25, 13, 'venue_update', 'Class Venue Updated', 'Your class (Introduction to Programming) was updated to:\nWednesday 04:15:00 to 07:30\nvenue:LH2', 1, 0, '2025-11-26 03:59:54'),
(26, 3, 'venue_update', 'Class Venue Updated', 'Your class (Introduction to Programming) was updated to:\nWednesday 04:15:00 to 08:30\nvenue:LH2', 1, 1, '2025-11-26 04:45:05'),
(27, 13, 'venue_update', 'Class Venue Updated', 'Your class (Introduction to Programming) was updated to:\nWednesday 04:15:00 to 08:30\nvenue:LH2', 1, 0, '2025-11-26 04:45:09'),
(28, 3, 'venue_update', 'Class Venue Updated', 'Your class (Introduction to Programming) was updated to:\nWednesday 08:15 to 09:30\nvenue:LH2', 1, 1, '2025-11-26 05:33:45'),
(29, 13, 'venue_update', 'Class Venue Updated', 'Your class (Introduction to Programming) was updated to:\nWednesday 08:15 to 09:30\nvenue:LH2', 1, 0, '2025-11-26 05:33:50');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `attendance_submitted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `unit_id`, `lecturer_id`, `day_of_week`, `start_time`, `end_time`, `venue`, `semester`, `status`, `created_at`, `updated_at`, `attendance_submitted`) VALUES
(1, 1, 2, 'Wednesday', '08:15:00', '09:30:00', 'LH2', 'SEP/DEC', 'active', '2025-10-30 18:31:57', '2025-11-26 05:33:45', 0),
(3, 2, 2, 'Wednesday', '02:50:00', '03:30:00', 'BBIT R1', 'SEP/DEC', 'active', '2025-11-03 10:40:31', '2025-11-25 23:41:28', 0);

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
(7, 3, 1, '2025-09-01', 'enrolled', '2025-11-25 23:37:19', '2025-11-25 23:37:19'),
(8, 13, 1, '2025-09-11', 'enrolled', '2025-11-25 23:38:36', '2025-11-25 23:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `student_unit_enrollments`
--

CREATE TABLE `student_unit_enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `enrollment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_unit_enrollments`
--

INSERT INTO `student_unit_enrollments` (`id`, `student_id`, `unit_id`, `enrollment_date`) VALUES
(9, 13, 1, '2025-11-26'),
(10, 3, 1, '2025-11-26'),
(11, 3, 2, '2025-11-26'),
(12, 13, 2, '2025-11-26');

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
(20, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Marked absent: Introduction to Programming on 2025-11-03\",\"message_id\":\"<8rOR5vt7tujvOSb5ufJgPv61v1eUH6ZUQUhjpNgI@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-03 15:19:15'),
(21, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"Password Reset Request\",\"message_id\":\"<vWLi6pYOAgk39GhMMcXw1IFUQZnjYMTVInnWBGKbNoI@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-24 16:50:26'),
(22, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"Password Reset Request\",\"message_id\":\"<gLS0QHNi18QCWaECeQfcGWkZZZmgP9s1hXLnqmCOE@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 03:52:36'),
(23, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"Password Reset Request\",\"message_id\":\"<nR4D6P3h59ncNm4jR5Sju6tM4Jf3zd5Ij87v0q5fvE@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 03:52:40'),
(24, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"Password Reset Request\",\"message_id\":\"<VsO4WutQNfXTF6okBcHrTfRYYIqMfbPhJC3LicI@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 04:06:27'),
(25, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<EhhYdjk2wHCm687KkU7r318xVxAlZtw9CC6Xk0QQk@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 05:30:10'),
(26, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Marked absent: Introduction to Programming on 2025-11-25\",\"message_id\":\"<ZDew4IVh6rR6UcS4YWBEwg5wsKGytd5GYzMvN3r4@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 05:31:11'),
(27, 0, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Password Reset Request\",\"message_id\":\"<OCqFWEVopPQbcmHJfHGksG4XrgJbZA97qEpbQ7y4g@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 05:32:56'),
(28, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<XA6CKzLMejCamXpCNaRRFZSZU44ZSrRsq6212gRnhc@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 06:49:41'),
(29, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"New Account Request\",\"message_id\":\"<qh6b5ppuoaHiAy3xbgR4EdhOVqsYcq3QNiNpIXuE4@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 15:57:07'),
(30, 1, 'Account Approved', 'Approved account for howino569@gmail.com', '::1', '2025-11-25 17:23:49'),
(31, 1, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Account Approved\",\"message_id\":\"<retiuNWgnyzyXCLe962X3E9Vnd1JYtV6SeK61KmcU@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 17:23:53'),
(32, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<0zxLif2k0UvqGvBUM9Ot54Rs9qrlQefDxgLyGjUfodI@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 17:32:13'),
(33, 1, 'PHP Exception', 'Uncaught Exception: Class \"AccountRequest\" not found in C:\\xampp\\htdocs\\StudentAttendance\\dev\\run_notification_test.php on line 28', 'CLI', '2025-11-25 17:34:11'),
(34, 1, 'PHP Exception', 'Uncaught Exception: Class \"AccountRequest\" not found in C:\\xampp\\htdocs\\StudentAttendance\\dev\\run_notification_test.php on line 31', 'CLI', '2025-11-25 17:37:03'),
(35, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<piNhJjGjxl3bdSnPuEBPZo307R9ZtsnhH4vbwZYDE@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 21:06:57'),
(36, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"New Account Request\",\"message_id\":\"<4AfbtbTIxxRbklCv4oqFImS1bpP2eaZUJgQHAyMls8@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 21:35:07'),
(37, 1, 'Account Rejected', 'Rejected account for testuser1764093110@example.com', '::1', '2025-11-25 21:44:41'),
(38, 1, 'email_sent', '{\"to\":\"testuser1764093110@example.com\",\"subject\":\"Account Rejected\",\"message_id\":\"<5RtIY5LnNtxLIcISnR4NQhrynMqq92SdYQslvztNM@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 21:44:44'),
(39, 1, 'Account Rejected', 'Rejected account for howino569@gmail.com', '::1', '2025-11-25 21:44:49'),
(40, 1, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Account Rejected\",\"message_id\":\"<TQ2I4opYnc3Wo4tGTrzlxqDJ1G3iBkFMjObXXyYj0AI@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 21:44:53'),
(41, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"New Account Request\",\"message_id\":\"<Aj37L1YStHLafjruyB95V1Run6RL3e8vNdqNNW3jclU@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 21:58:39'),
(42, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"New Account Request\",\"message_id\":\"<mAhTJTvrU1Sax1qqxBjWIJdFhxVningoiuqkUyIjS8@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:03:54'),
(43, 1, 'Account Approved', 'Approved account for howino569@gmail.com', '::1', '2025-11-25 22:04:28'),
(44, 1, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Account Approved\",\"message_id\":\"<9pJA9neJlgbX1qG7i8KVF56AU3Q5B5Td6z4lVxy9dJM@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:04:32'),
(45, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"New Account Request\",\"message_id\":\"<DigdM64BwwuFDc0iSGL8j4kdOVVb87a0d0IPUvpk@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:18:49'),
(46, 1, 'Account Approved', 'Approved account for howino569@gmail.com', '::1', '2025-11-25 22:19:14'),
(47, 1, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Account Approved\",\"message_id\":\"<fcLrAjQIwuyudegfzVx2UvPq7HXUEdvZcgVj4p0X3zQ@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:19:18'),
(48, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"New Account Request\",\"message_id\":\"<1GOeYfU9G51nsKUdxs40OO2yGr9kgPwKymZRqGZSc@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:39:30'),
(49, 1, 'Account Approved', 'Approved account for howino569@gmail.com', '::1', '2025-11-25 22:39:40'),
(50, 1, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Account Approved\",\"message_id\":\"<f70b8n55OTZjVubJ3NUSvjSIqWww1gRfzin9LEtCNc@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:39:44'),
(51, 1, 'Account Approved', 'Approved account for h1serverside@gmail.com', '::1', '2025-11-25 22:39:49'),
(52, 1, 'email_sent', '{\"to\":\"h1serverside@gmail.com\",\"subject\":\"Account Approved\",\"message_id\":\"<kWV8SzHLCvCr44cUc1duYpQr8EqbeVequvYpQ2Muo@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:39:52'),
(53, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"New Account Request\",\"message_id\":\"<mfpw39Bzwww51imWbGjfCwKQxL75nJeabVszEit5ws@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:43:28'),
(54, 1, 'Account Approved', 'Approved account for howino569@gmail.com', '::1', '2025-11-25 22:43:45'),
(55, 1, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Account Approved\",\"message_id\":\"<NN9d957xKOq72YSsFChNhvtemRoi28Lk8G1syehcg@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:43:48'),
(56, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"New Account Request\",\"message_id\":\"<T7fwjsp4dyAUkK9PIPxozgAQPu6tsauskgIcYzyPw8@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:47:32'),
(57, 1, 'Account Approved', 'Approved account for howino569@gmail.com', '::1', '2025-11-25 22:48:12'),
(58, 1, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Account Approved\",\"message_id\":\"<CwqGxGtBHTkwfhaByYyrXVpm6N2AlVzBge1nY28Pw@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:48:17'),
(59, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"New Account Request\",\"message_id\":\"<ckSGLF0cwzguXPLIWVEnrRc1TGH31MBCJe9azg6XE@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:51:08'),
(60, 1, 'Account Approved', 'Approved account for howino569@gmail.com', '::1', '2025-11-25 22:51:25'),
(61, 1, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Account Approved\",\"message_id\":\"<OKr6GGfziKLPFGhXLUttKId5JOjJH4nJB5hqu2eY@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:51:28'),
(62, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"New Account Request\",\"message_id\":\"<FKPbAQ1yauA1Q3Cpzlx4rCHwCEKGEFMNkl5A6SUnBc@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:54:43'),
(63, 1, 'Account Approved', 'Approved account for howino569@gmail.com', '::1', '2025-11-25 22:54:53'),
(64, 1, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Account Approved\",\"message_id\":\"<G1bNYnajWDIT7XoocCa3q4OPVlpSB9Ug7z6d77o7Vk@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 22:54:56'),
(65, 0, 'email_sent', '{\"to\":\"howino810@gmail.com\",\"subject\":\"New Account Request\",\"message_id\":\"<AGtW5x1tq8IWsPxMKr37sMo1nmryw5bqnmhzlJ9KkI@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 23:23:25'),
(66, 1, 'Account Approved', 'Approved account for howino569@gmail.com', '::1', '2025-11-25 23:23:52'),
(67, 1, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Account Approved\",\"message_id\":\"<J4vbJ2CLBa96EqfY5lUL6xIndvN78JsWsx1eyUna0@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 23:23:55'),
(68, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<NFCv8sdpB9jy9yPN8Bh8SvniQnElojb3sZdF0L0HQys@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 23:40:06'),
(69, 2, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<9kJDOJmbcdJRVWzjWDP8kMc5B7DFrBa2y70eZ2uJA5I@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 23:40:10'),
(70, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<3k6gIy9td4q5KZrZAnlT8JRqvXyiMVGgTA9xhvff58@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 23:41:32'),
(71, 2, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<pd6x0qRCGq0zbSHMJyjTFffMw5Rrm75BWV2W5WVh3A@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-25 23:41:35'),
(72, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<43lf6vdb6WhnX4ETEOuhblpr5pvIqL6LAXPkVGZ4m0M@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-26 01:08:50'),
(73, 2, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<hhJ2EU2sdvYGCVluPliHnNBQddYNhSQYBzmc8tuVLw@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-26 01:08:54'),
(74, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<31mhkcyjhSzg0TUnj1zI8IwcL7FcjUL6VVFjvfaWE@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-26 03:11:43'),
(75, 2, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<17v2yJRQ5agN94UNX5DvNEkI3hbGgUjDuZOWjGFRhkE@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-26 03:11:47'),
(76, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<DZ5oBBkJs3MXxcqDZP6mj8huN8s2GuuArDdqZiSS0@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-26 03:59:54'),
(77, 2, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<GUivcVGTgnFUYWquPfICEHSqEiSKN8YwTODqsNNYE@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-26 03:59:58'),
(78, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<id4yNfMec1Rqewsp0du9DbLbh3XaOrO8NsBh6yDjhDk@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-26 04:45:09'),
(79, 2, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<tN9OMQy9VaO39el149IEOBjqCtFihvbHY0i8n7nPIg@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-26 04:45:13'),
(80, 2, 'email_sent', '{\"to\":\"lilflicky254@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<0xDMFeK2FI0H18gkmxqlUzbmrfKPHqfgmRdT8@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-26 05:33:49'),
(81, 2, 'email_sent', '{\"to\":\"howino569@gmail.com\",\"subject\":\"Class Venue Updated\",\"message_id\":\"<LekBeXNGZle02FLIsiXudS8gwSGZ3R0yLhnbWG6fNFc@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-26 05:33:53'),
(82, 0, 'email_sent', '{\"to\":\"Howino810@gmail.com\",\"subject\":\"Password Reset Request\",\"message_id\":\"<dqAsECnM6CgcGsUjq881IvdSDQiCEyB8dIHjO9csQSk@localhost>\",\"status\":\"sent\"}', '::1', '2025-11-26 06:57:17');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_default` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `updated_at`, `is_default`) VALUES
(1, 'system_name', 'kabarak', '2025-11-25 03:52:21', 1),
(2, 'system_logo', 'default_logo.png', '2025-10-30 07:52:27', 1),
(3, 'attendance_threshold', '75', '2025-10-30 07:52:27', 1),
(4, 'app_email', 'attendancesystem79@gmail.com', '2025-11-25 03:52:21', 1),
(5, 'app_password', 'pkww lgtk igpn rzzz', '2025-11-25 03:52:21', 1);

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
(1, 1, 'Introduction to Programming', 'CS101', 2, 'SEP/DEC', 'active', '2025-10-30 07:52:27', '2025-11-25 05:59:08'),
(2, 1, 'Data Structures and Algorithms', 'CS201', 2, 'SEP/DEC', 'active', '2025-10-30 07:52:27', '2025-11-25 23:36:15'),
(3, 2, 'Introduction to Business', 'BIT101', 2, 'JAN/APR', 'active', '2025-10-30 07:52:27', '2025-10-30 07:52:27'),
(4, 1, 'Introduction to Programming', 'CS101', NULL, 'SEP/DEC', 'active', '2025-11-26 07:53:13', '2025-11-26 07:53:13'),
(5, 1, 'Data Structures and Algorithms', 'CS102', NULL, 'SEP/DEC', 'active', '2025-11-26 07:53:13', '2025-11-26 07:53:13'),
(6, 1, 'Database Systems', 'CS103', NULL, 'SEP/DEC', 'active', '2025-11-26 07:53:13', '2025-11-26 07:53:13'),
(7, 1, 'Operating Systems', 'CS104', NULL, 'SEP/DEC', 'active', '2025-11-26 07:53:13', '2025-11-26 07:53:13'),
(8, 1, 'Computer Networks', 'CS105', NULL, 'SEP/DEC', 'active', '2025-11-26 07:53:13', '2025-11-26 07:53:13'),
(9, 2, 'Introduction to IT', 'IT101', NULL, 'SEP/DEC', 'active', '2025-11-26 07:53:14', '2025-11-26 07:53:14'),
(10, 2, 'Web Development Technologies', 'IT102', NULL, 'SEP/DEC', 'active', '2025-11-26 07:53:14', '2025-11-26 07:53:14'),
(11, 2, 'Systems Analysis and Design', 'IT103', NULL, 'SEP/DEC', 'active', '2025-11-26 07:53:14', '2025-11-26 07:53:14'),
(12, 2, 'Information Security', 'IT104', NULL, 'SEP/DEC', 'active', '2025-11-26 07:53:14', '2025-11-26 07:53:14'),
(13, 2, 'Mobile Application Development', 'IT105', NULL, 'SEP/DEC', 'active', '2025-11-26 07:53:14', '2025-11-26 07:53:14');

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
  `course_id` int(11) DEFAULT NULL,
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

INSERT INTO `users` (`id`, `email`, `password`, `full_name`, `role`, `department_id`, `course_id`, `phone`, `profile_picture`, `password_reset_token`, `password_reset_expires_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 'howino810@gmail.com', '$2y$10$taTbE3p/jGOAMKW74ZBYJ.kFzlXeep8OCByTlVq0gOa0J42YONnPy', 'Admin User', 'admin', NULL, NULL, NULL, NULL, '1e60875e1eebbcbc73a1911dfc24331a399cc0add57ac856c2cfc10d8272f694', '2025-11-26 10:57:13', 'active', '2025-10-30 07:52:27', '2025-11-26 06:57:13'),
(2, 'henry@kabarak.ac.ke', '$2y$10$taTbE3p/jGOAMKW74ZBYJ.kFzlXeep8OCByTlVq0gOa0J42YONnPy', 'Henry Lecturer', 'lecturer', 1, NULL, '0758832294', '', NULL, NULL, 'active', '2025-10-30 07:52:27', '2025-11-25 19:48:11'),
(3, 'lilflicky254@gmail.com', '$2y$10$FoXKF2x4aEBNFtNr6HMyzOKr2G/rn8nE9UQ5SSt0Q8ILmRY0/Twcy', 'Flick', 'student', NULL, 1, '', NULL, NULL, NULL, 'active', '2025-10-30 07:52:27', '2025-11-25 19:47:51'),
(13, 'howino569@gmail.com', '$2y$10$UM97vLyXvsH7.jIlfoVs7.M6.JkaqUwLtvjph.uekb6.UPQ5zBNc.', 'Henry Inda', 'student', NULL, 1, '', NULL, NULL, NULL, 'active', '2025-11-25 23:23:52', '2025-11-25 23:23:52'),
(14, 'Dr. John Okello', '$2y$10$NBFAMp0KIF8maTKDn9e1I.ZjKomXEgmw64qQO2taUizMIQx2NxRg.', 'Password123', 'lecturer', 1, 0, '+254712345678', NULL, NULL, NULL, 'active', '2025-11-26 07:54:09', '2025-11-26 07:54:09'),
(15, 'Dr. Jane Wanjiru', '$2y$10$K.uIlG5W0v71LJTJDtxzQ.XRSsvwjXetEmca7kCduptkX7O/lXFAC', 'Password123', 'lecturer', 1, 0, '+254712345679', NULL, NULL, NULL, 'active', '2025-11-26 07:54:10', '2025-11-26 07:54:10'),
(16, 'Prof. David Kimani', '$2y$10$IMmNF5WZh0b2nVNdd09i2uCznikQtQPwp700cZuoONV1I4LX9yRJ6', 'Password123', 'lecturer', 1, 0, '+254712345680', NULL, NULL, NULL, 'active', '2025-11-26 07:54:10', '2025-11-26 07:54:10'),
(17, 'Dr. Mary Achieng', '$2y$10$jhVB1XU72.yjta8qaShOYeTx1Re0y.oNzNRYMzc93DlJID19JUsZm', 'Password123', 'lecturer', 2, 0, '+254712345681', NULL, NULL, NULL, 'active', '2025-11-26 07:54:10', '2025-11-26 07:54:10'),
(18, 'Dr. Peter Otieno', '$2y$10$gIVPoMOCcyY6S9p5jlz6OeJbKCdopOh/PuQNZLpZk/Ujay2fPtwoi', 'Password123', 'lecturer', 2, 0, '+254712345682', NULL, NULL, NULL, 'active', '2025-11-26 07:54:10', '2025-11-26 07:54:10'),
(19, 'Prof. Ann Njeri', '$2y$10$BQjWp.H6uZ.yIfmlYe.mv.uO/nhUhpwAPQ5DfdyMEYY0EBd9IQjnK', 'Password123', 'lecturer', 2, 0, '+254712345683', NULL, NULL, NULL, 'active', '2025-11-26 07:54:10', '2025-11-26 07:54:10'),
(20, 'Dr. Susan Cherono', '$2y$10$FfzOIdhrYk5jLAgsz.CA8.tBg3GpZicCp/nvgy0yhLd8XTPEOeZpO', 'Password123', 'lecturer', 3, 0, '+254712345684', NULL, NULL, NULL, 'active', '2025-11-26 07:54:10', '2025-11-26 07:54:10'),
(21, 'Dr. Michael Kariuki', '$2y$10$kfpKtGzM9sBOozwZMIvLT.OOAcsbLyHOhtjh1HIhjyZruOFm8I5/.', 'Password123', 'lecturer', 3, 0, '+254712345685', NULL, NULL, NULL, 'active', '2025-11-26 07:54:11', '2025-11-26 07:54:11'),
(22, 'Prof. Fatuma Ali', '$2y$10$rcNs4/u8s14ERn4hZ9iMjuxWwXhwHTuugQ.AdgMJvApA7W6qHjrY2', 'Password123', 'lecturer', 3, 0, '+254712345686', NULL, NULL, NULL, 'active', '2025-11-26 07:54:11', '2025-11-26 07:54:11'),
(23, 'Dr. Joseph Maina', '$2y$10$yb5Y.ydjvLE.dWlKTiAzSu2Ju1sl64AXf5DS3Pi4Qt3hbNSRfJu4m', 'Password123', 'lecturer', 4, 0, '+254712345687', NULL, NULL, NULL, 'active', '2025-11-26 07:54:11', '2025-11-26 07:54:11'),
(24, 'Dr. Lucy Muthoni', '$2y$10$d9gbL/uLbCxMOf1nysVShuQKR3ug.8IbE3PMwp3KcXMSYF.clP1ai', 'Password123', 'lecturer', 4, 0, '+254712345688', NULL, NULL, NULL, 'active', '2025-11-26 07:54:11', '2025-11-26 07:54:11'),
(25, 'Prof. James Omondi', '$2y$10$SGoxhjqq/YsGKqOhSIjYceBa2barG9LQQ1CW.HkF/xo8Wx8pxO/i6', 'Password123', 'lecturer', 4, 0, '+254712345689', NULL, NULL, NULL, 'active', '2025-11-26 07:54:11', '2025-11-26 07:54:11'),
(26, 'Dr. Esther Wangui', '$2y$10$2fRQasIonbRoM7I1.HNoA.GF3YJJI5wfhyyEPKEIQ1mxxNIerpt5e', 'Password123', 'lecturer', 5, 0, '+254712345690', NULL, NULL, NULL, 'active', '2025-11-26 07:54:11', '2025-11-26 07:54:11'),
(27, 'Dr. Robert Mwangi', '$2y$10$N7c/xI/w4gtEFWfVP4u8Lu0VDFtUPuuaiZhUw7y0Gq1NidQdTBGEe', 'Password123', 'lecturer', 5, 0, '+254712345691', NULL, NULL, NULL, 'active', '2025-11-26 07:54:12', '2025-11-26 07:54:12'),
(28, 'Prof. Grace Adhiambo', '$2y$10$3Ade7cTnks/lJQiR4FwOW.1r/PHyv9rm59/RcE2VBw.t0/eKNh3x2', 'Password123', 'lecturer', 5, 0, '+254712345692', NULL, NULL, NULL, 'active', '2025-11-26 07:54:12', '2025-11-26 07:54:12'),
(29, 'Kevin Omondi', '$2y$10$GZFbYqRIlEYj3ITRG4oz8OnQBL/Us7017SKI6ev.TwDgNKSE/y1oe', 'Password123', 'student', 0, 1, '+254723456789', NULL, NULL, NULL, 'active', '2025-11-26 07:54:31', '2025-11-26 07:54:31'),
(30, 'Brian Kipchoge', '$2y$10$e/gF1GDOWdeHEPczbmmMVeUfCv1mHxBtJVk.KI5yYXFbrLNoGAsPW', 'Password123', 'student', 0, 1, '+254723456790', NULL, NULL, NULL, 'active', '2025-11-26 07:54:31', '2025-11-26 07:54:31'),
(31, 'Cynthia Achieng', '$2y$10$Ur27gjZxZZCAD0j4oOm83uGylbA3moSE3aP3iTRBrPuF9CyRe/r8e', 'Password123', 'student', 0, 1, '+254723456791', NULL, NULL, NULL, 'active', '2025-11-26 07:54:31', '2025-11-26 07:54:31'),
(32, 'Dennis Murithi', '$2y$10$hpiPDrqiyg/vfsbNm55/e.1m69EWaXrmr3z7nK5ys84P//zJZwqPa', 'Password123', 'student', 0, 1, '+254723456792', NULL, NULL, NULL, 'active', '2025-11-26 07:54:31', '2025-11-26 07:54:31'),
(33, 'Faith Njoki', '$2y$10$mGloa.iABMf29i.djDlP6uO7s4b9OLzYFvXv3FbgKmTIGd3Ijssaq', 'Password123', 'student', 0, 1, '+254723456793', NULL, NULL, NULL, 'active', '2025-11-26 07:54:31', '2025-11-26 07:54:31'),
(34, 'Ian Mwangi', '$2y$10$rHZgpFoTXw4gEUEGXGZBAOBHa6/yzm27yDYGH2eg.h3EeMEchKjLW', 'Password123', 'student', 0, 1, '+254723456794', NULL, NULL, NULL, 'active', '2025-11-26 07:54:32', '2025-11-26 07:54:32'),
(35, 'Grace Wangari', '$2y$10$6SG0Om0F60QpvF/mVwxm0O0MLRwdZcHLqILbAfBGVIazjn2I6mnLW', 'Password123', 'student', 0, 2, '+254723456795', NULL, NULL, NULL, 'active', '2025-11-26 07:54:32', '2025-11-26 07:54:32'),
(36, 'Hillary Clinton', '$2y$10$WxvNPzVVB8XLJ8bwxzJXp.AYBXKrsQvCuAgJCRcvk9BcN2fGIBJf.', 'Password123', 'student', 0, 2, '+254723456796', NULL, NULL, NULL, 'active', '2025-11-26 07:54:32', '2025-11-26 07:54:32'),
(37, 'Ivy Cherop', '$2y$10$g4l99jHr2PeNnKJBd.PBQ.O.W5pUZJyRlzlDP9QPM/AB.Su1kaYa2', 'Password123', 'student', 0, 2, '+254723456797', NULL, NULL, NULL, 'active', '2025-11-26 07:54:32', '2025-11-26 07:54:32'),
(38, 'Jack Ochieng', '$2y$10$IFIsO/dj2/Z9VZyXlnWwMOchp92QGZu834ThJODI03KeEGQ9H0Bhy', 'Password123', 'student', 0, 2, '+254723456798', NULL, NULL, NULL, 'active', '2025-11-26 07:54:32', '2025-11-26 07:54:32'),
(39, 'Kennedy Maina', '$2y$10$q5O/costXTrvRhffT0ZLTO8srDGyXWbhEof1VsV3UyEy1JFjy83TO', 'Password123', 'student', 0, 2, '+254723456799', NULL, NULL, NULL, 'active', '2025-11-26 07:54:32', '2025-11-26 07:54:32'),
(40, 'Linda Auma', '$2y$10$eha3QECwqUADrHYPGc/6V.AQLX6mFG/Zdc07IcaJQiTko7Q2yFY9O', 'Password123', 'student', 0, 2, '+254723456800', NULL, NULL, NULL, 'active', '2025-11-26 07:54:32', '2025-11-26 07:54:32'),
(41, 'Michael Kimani', '$2y$10$SMSpvpY76dgGj9xCeDC6weKHZuu5pgJvL58cVxJv.N9qWMi4TkqY2', 'Password123', 'student', 0, 3, '+254723456801', NULL, NULL, NULL, 'active', '2025-11-26 07:54:33', '2025-11-26 07:54:33'),
(42, 'Nancy Wairimu', '$2y$10$a3jZSctcxln/JSatYbMFcuWsl6s2.9w3l5Egkj66CDXUBe60Ts90m', 'Password123', 'student', 0, 3, '+254723456802', NULL, NULL, NULL, 'active', '2025-11-26 07:54:33', '2025-11-26 07:54:33'),
(43, 'Oscar Omondi', '$2y$10$bN9Eak8KH3W6lRh49uEHluW0iF1CDMw/fXXRVilWb9dZV33xOGGya', 'Password123', 'student', 0, 3, '+254723456803', NULL, NULL, NULL, 'active', '2025-11-26 07:54:33', '2025-11-26 07:54:33'),
(44, 'Peter Kamau', '$2y$10$vMg1xKB7PqoxBmO9DNwPUe8Gao6krqjO/ALurySmhiVcShz4G9FEm', 'Password123', 'student', 0, 3, '+254723456804', NULL, NULL, NULL, 'active', '2025-11-26 07:54:33', '2025-11-26 07:54:33'),
(45, 'Queenter Adhiambo', '$2y$10$kjPZXMNFY0fvjv7tUvaV9e5oe0.q.dvNKeGKhb44ZSlTC27hOA9qu', 'Password123', 'student', 0, 3, '+254723456805', NULL, NULL, NULL, 'active', '2025-11-26 07:54:33', '2025-11-26 07:54:33'),
(46, 'Robert Mutua', '$2y$10$Q9.sTxMIhuTKeBPNsCmG3OdVxa4L/LjCJdGFOWFMcCFuh8vgnnZaq', 'Password123', 'student', 0, 4, '+254723456806', NULL, NULL, NULL, 'active', '2025-11-26 07:54:33', '2025-11-26 07:54:33'),
(47, 'Susan Njeri', '$2y$10$QM1OF65wUwui/ShJkRnuLu9UDTrEJaDz/OJ8ZkcWPfxSnUT/OYTPW', 'Password123', 'student', 0, 4, '+254723456807', NULL, NULL, NULL, 'active', '2025-11-26 07:54:34', '2025-11-26 07:54:34'),
(48, 'Thomas Wasike', '$2y$10$nss49voO.9SonB1UhGOjUeM2v3Rj8URFGORpyzLWdEY.sUW9KCjsO', 'Password123', 'student', 0, 4, '+254723456808', NULL, NULL, NULL, 'active', '2025-11-26 07:54:34', '2025-11-26 07:54:34'),
(49, 'Ursula Owino', '$2y$10$vsHuC867C8oBdz5wOPWz1eeWUsFU2.bM10GkiNXC4buhOtjRiuagu', 'Password123', 'student', 0, 4, '+254723456809', NULL, NULL, NULL, 'active', '2025-11-26 07:54:34', '2025-11-26 07:54:34'),
(50, 'Victor Kipruto', '$2y$10$JLVVEeV/GkpeKX5lR.tcW.dzvDqcbeSQeg0VI.T3IGDpTtXb3tIb6', 'Password123', 'student', 0, 4, '+254723456810', NULL, NULL, NULL, 'active', '2025-11-26 07:54:34', '2025-11-26 07:54:34'),
(51, 'William Ruto', '$2y$10$aa4vXHL.u6y0pMVSpXSo3ugZVL008cZdYKtzxFhw5EYrfhk.wAZ4G', 'Password123', 'student', 0, 5, '+254723456811', NULL, NULL, NULL, 'active', '2025-11-26 07:54:34', '2025-11-26 07:54:34'),
(52, 'Xavier Njoroge', '$2y$10$FVj..7RB7NAoC025r6MwQ.SVe6r2zmYHXzUFtUoWwk6N/TxOOxHYa', 'Password123', 'student', 0, 5, '+254723456812', NULL, NULL, NULL, 'active', '2025-11-26 07:54:34', '2025-11-26 07:54:34'),
(53, 'Yvonne Atieno', '$2y$10$vDAKk627b850XgGPhScU8.OEckyHr5bBoUqGxLiVVsjpEr.Asm4Mi', 'Password123', 'student', 0, 5, '+254723456813', NULL, NULL, NULL, 'active', '2025-11-26 07:54:34', '2025-11-26 07:54:34'),
(54, 'Zachary Koech', '$2y$10$r/XHjZf0Z9vZfX1NQpTIQ.tmkSeTOlMTcxCvbQESueAKa5/9xZmXe', 'Password123', 'student', 0, 5, '+254723456814', NULL, NULL, NULL, 'active', '2025-11-26 07:54:35', '2025-11-26 07:54:35'),
(55, 'Alice Wambui', '$2y$10$hHiT1ARSO5c82fu4jnk8d.pz4JVatu7F8wKqcvjKSQWUMPCfOxUvW', 'Password123', 'student', 0, 5, '+254723456815', NULL, NULL, NULL, 'active', '2025-11-26 07:54:35', '2025-11-26 07:54:35'),
(56, 'Benard Kipkorir', '$2y$10$Z3cdF3ZMkCM4PoalBRMytOmOdKlckLiapXoAxVZa5QogcWUhB7.ca', 'Password123', 'student', 0, 6, '+254723456816', NULL, NULL, NULL, 'active', '2025-11-26 07:54:35', '2025-11-26 07:54:35'),
(57, 'Catherine Mueni', '$2y$10$47F2Wanvzsj2SAAJi5.IRu5MooNcw4QYG2Ux5KDBhNT/PHJ7OoeGG', 'Password123', 'student', 0, 6, '+254723456817', NULL, NULL, NULL, 'active', '2025-11-26 07:54:35', '2025-11-26 07:54:35'),
(58, 'Daniel Otieno', '$2y$10$DanIa6E3Rj0TEgi5Hk5tL.koHE3wWDuXPg5vxo0OB4o9sunLER82S', 'Password123', 'student', 0, 6, '+254723456818', NULL, NULL, NULL, 'active', '2025-11-26 07:54:35', '2025-11-26 07:54:35'),
(59, 'Evelyn Anyango', '$2y$10$goujZgWi.3cf/Ll1sGNuNOCbUQu8yURcjUdNxFKWrIktC4/SUZm1i', 'Password123', 'student', 0, 6, '+254723456819', NULL, NULL, NULL, 'active', '2025-11-26 07:54:35', '2025-11-26 07:54:35'),
(60, 'Frankline Omondi', '$2y$10$rhMPU2jBRv1JUb0TKiWHd.P2DL9LFlANYHdFV.i5LwWrdYz6qxujG', 'Password123', 'student', 0, 6, '+254723456820', NULL, NULL, NULL, 'active', '2025-11-26 07:54:35', '2025-11-26 07:54:35'),
(61, 'Gideon Moi', '$2y$10$JIqU0eH2rMBbbc9bj59yS.IKOW81gPQWbmpDDsznRgWKmiHBOv2/O', 'Password123', 'student', 0, 6, '+254723456821', NULL, NULL, NULL, 'active', '2025-11-26 07:54:36', '2025-11-26 07:54:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_requests`
--
ALTER TABLE `account_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `idx_attendance_date` (`date`),
  ADD KEY `idx_attendance_status` (`status`),
  ADD KEY `idx_attendance_marked_at` (`marked_at`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_departments_code` (`code`);

--
-- Indexes for table `excuse_requests`
--
ALTER TABLE `excuse_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `idx_excuse_requests_date` (`date`),
  ADD KEY `idx_excuse_requests_status` (`status`),
  ADD KEY `idx_excuse_requests_created_at` (`created_at`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_notifications_is_read` (`is_read`),
  ADD KEY `idx_notifications_created_at` (`created_at`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `lecturer_id` (`lecturer_id`),
  ADD KEY `idx_schedules_day_time` (`day_of_week`,`start_time`,`end_time`),
  ADD KEY `idx_schedules_semester` (`semester`),
  ADD KEY `idx_schedules_status` (`status`);

--
-- Indexes for table `student_enrollments`
--
ALTER TABLE `student_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `idx_student_enrollments_enrollment_date` (`enrollment_date`),
  ADD KEY `idx_student_enrollments_status` (`status`);

--
-- Indexes for table `student_unit_enrollments`
--
ALTER TABLE `student_unit_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_system_logs_created_at` (`created_at`),
  ADD KEY `idx_system_logs_action` (`action`),
  ADD KEY `idx_system_logs_user_id` (`user_id`);

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
  ADD KEY `lecturer_id` (`lecturer_id`),
  ADD KEY `idx_units_semester` (`semester`),
  ADD KEY `idx_units_status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_department_id` (`department_id`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_requests`
--
ALTER TABLE `account_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `excuse_requests`
--
ALTER TABLE `excuse_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_enrollments`
--
ALTER TABLE `student_enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `student_unit_enrollments`
--
ALTER TABLE `student_unit_enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

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
-- Constraints for table `student_unit_enrollments`
--
ALTER TABLE `student_unit_enrollments`
  ADD CONSTRAINT `student_unit_enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_unit_enrollments_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

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

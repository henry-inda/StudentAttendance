
USE `project_db`;

-- Default admin user
INSERT INTO `users` (`email`, `password`, `full_name`, `role`, `status`) VALUES
('howino810@gmail.com', '$2y$10$taTbE3p/jGOAMKW74ZBYJ.kFzlXeep8OCByTlVq0gOa0J42YONnPy', 'Admin User', 'admin', 'active'); -- password: test

-- Sample lecturer
INSERT INTO `users` (`email`, `password`, `full_name`, `role`, `status`) VALUES
('henry@kabarak.ac.ke', '$2y$10$taTbE3p/jGOAMKW74ZBYJ.kFzlXeep8OCByTlVq0gOa0J42YONnPy', 'Henry Lecturer', 'lecturer', 'active'); -- password: test

-- Sample student
INSERT INTO `users` (`email`, `password`, `full_name`, `role`, `status`) VALUES
('lilflicky254@gmail.com', '$2y$10$taTbE3p/jGOAMKW74ZBYJ.kFzlXeep8OCByTlVq0gOa0J42YONnPy', 'Lil Flicky', 'student', 'active'); -- password: test

-- Sample departments
INSERT INTO `departments` (`name`, `code`, `description`) VALUES
('Computer Science', 'CS', 'Department of Computer Science'),
('Business IT', 'BIT', 'Department of Business Information Technology');

-- Sample courses
INSERT INTO `courses` (`department_id`, `course_name`, `course_code`) VALUES
(1, 'Bachelor of Science in Computer Science', 'BSC-CS'),
(2, 'Bachelor of Business Information Technology', 'BBIT');

-- Sample units
INSERT INTO `units` (`course_id`, `unit_name`, `unit_code`, `lecturer_id`, `semester`) VALUES
(1, 'Introduction to Programming', 'CS101', 2, 'JAN/APR'),
(1, 'Data Structures and Algorithms', 'CS201', 2, 'MAY/AUG'),
(2, 'Introduction to Business', 'BIT101', 2, 'JAN/APR');

-- Default system settings
INSERT INTO `system_settings` (`setting_key`, `setting_value`) VALUES
('system_name', 'Attendance System'),
('system_logo', 'default_logo.png'),
('attendance_threshold', '75'),
('app_email', 'howino810@gmail.com'),
('app_password', 'svcp kbva rhez wckv');

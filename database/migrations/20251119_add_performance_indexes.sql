-- Migration: 20251119_add_performance_indexes.sql

-- Indexes for users table
ALTER TABLE `users` ADD INDEX `idx_users_department_id` (`department_id`);
ALTER TABLE `users` ADD INDEX `idx_users_role` (`role`);
ALTER TABLE `users` ADD INDEX `idx_users_status` (`status`);

-- Indexes for departments table
ALTER TABLE `departments` ADD UNIQUE INDEX `uq_departments_code` (`code`); -- Assuming code should be unique

-- Indexes for units table
ALTER TABLE `units` ADD INDEX `idx_units_semester` (`semester`);
ALTER TABLE `units` ADD INDEX `idx_units_status` (`status`);

-- Indexes for student_enrollments table
ALTER TABLE `student_enrollments` ADD INDEX `idx_student_enrollments_enrollment_date` (`enrollment_date`);
ALTER TABLE `student_enrollments` ADD INDEX `idx_student_enrollments_status` (`status`);

-- Indexes for schedules table
ALTER TABLE `schedules` ADD INDEX `idx_schedules_day_time` (`day_of_week`, `start_time`, `end_time`);
ALTER TABLE `schedules` ADD INDEX `idx_schedules_semester` (`semester`);
ALTER TABLE `schedules` ADD INDEX `idx_schedules_status` (`status`);

-- Indexes for attendance table
ALTER TABLE `attendance` ADD INDEX `idx_attendance_date` (`date`);
ALTER TABLE `attendance` ADD INDEX `idx_attendance_status` (`status`);
ALTER TABLE `attendance` ADD INDEX `idx_attendance_marked_at` (`marked_at`);

-- Indexes for excuse_requests table
ALTER TABLE `excuse_requests` ADD INDEX `idx_excuse_requests_date` (`date`);
ALTER TABLE `excuse_requests` ADD INDEX `idx_excuse_requests_status` (`status`);
ALTER TABLE `excuse_requests` ADD INDEX `idx_excuse_requests_created_at` (`created_at`);

-- Indexes for system_logs table
ALTER TABLE `system_logs` ADD INDEX `idx_system_logs_created_at` (`created_at`);
ALTER TABLE `system_logs` ADD INDEX `idx_system_logs_action` (`action`);
ALTER TABLE `system_logs` ADD INDEX `idx_system_logs_user_id` (`user_id`);

-- Indexes for notifications table
ALTER TABLE `notifications` ADD INDEX `idx_notifications_is_read` (`is_read`);
ALTER TABLE `notifications` ADD INDEX `idx_notifications_created_at` (`created_at`);

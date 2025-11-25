-- Migration: Add is_default column to system_settings if missing
ALTER TABLE `system_settings`
  ADD COLUMN `is_default` TINYINT(1) NOT NULL DEFAULT 1;

-- Ensure a system_name row exists
INSERT INTO system_settings (setting_key, setting_value, is_default)
SELECT 'system_name', 'Students Attendance', 1
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM system_settings WHERE setting_key = 'system_name');

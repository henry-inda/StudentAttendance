<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'project_db');

// Base URL
define('BASE_URL', 'http://localhost:8000/');

// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'howino810@gmail.com');
define('SMTP_PASS', 'svcp kbva rhez wckv');
define('SMTP_PORT', 587);

// System Name
define('SYSTEM_NAME', 'Student Attendance System');

// Upload Paths
define('UPLOADS_PATH', 'uploads/');
define('IMAGES_PATH', 'uploads/images/');
define('CSV_PATH', 'uploads/csv/');

// Session Configuration
define('SESSION_NAME', 'student_attendance');

// Timezone
date_default_timezone_set('Africa/Nairobi');

// JWT Secret
define('JWT_SECRET', '62f50ec288db76a72bf88d286178128641ba260218e8bd465979bb20fdbabbfb');
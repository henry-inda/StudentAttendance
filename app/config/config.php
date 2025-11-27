<?php
// Load Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Database Configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'project_db');

// Base URL
define('BASE_URL', $_ENV['BASE_URL'] ?? 'http://localhost:8000/');

// Email Configuration
define('SMTP_HOST', $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com');
define('SMTP_USER', $_ENV['SMTP_USER'] ?? '');
define('SMTP_PASS', $_ENV['SMTP_PASS'] ?? '');
define('SMTP_PORT', $_ENV['SMTP_PORT'] ?? 587);

// System Name
define('SYSTEM_NAME', $_ENV['SYSTEM_NAME'] ?? 'Student Attendance System');

// Upload Paths
define('UPLOADS_PATH', 'uploads/');
define('IMAGES_PATH', 'uploads/images/');
define('CSV_PATH', 'uploads/csv/');

// Session Configuration
define('SESSION_NAME', 'student_attendance');

// Timezone
date_default_timezone_set('Africa/Nairobi');

// JWT Secret
define('JWT_SECRET', $_ENV['JWT_SECRET'] ?? '');
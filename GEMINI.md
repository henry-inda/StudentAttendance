# Student Attendance Monitoring System

## Project Overview

This project is a full-stack Student Attendance Monitoring System built with PHP, MySQL, Bootstrap, JavaScript, and CSS. It features role-based access for Admin, Lecturer, and Student users. The system includes modules for user management, department, course, and unit management, student enrollments, attendance marking, excuse requests, system settings, logging, and reporting. It utilizes a custom MVC-like framework, PDO for database interaction, and PHPMailer for email notifications.

## Building and Running

1.  **Database Setup:**
    *   Create a MySQL database named `project_db`.
    *   Import the schema: `mysql -u root project_db < database/schema/final_db.sql`
    *   Import seed data: `mysql -u root project_db < database/seeder/seed_data.sql`
    *   *(Note: Ensure `final_db.sql` and `seed_data.sql` are up-to-date with the latest changes, especially password hashes and correct SQL syntax.)*

2.  **PHP Dependencies:**
    *   Install Composer dependencies: `composer install`

3.  **Configuration:**
    *   Edit `app/config/config.php` to set database credentials, `BASE_URL` (e.g., `http://localhost:8000/`), and email configuration.
    *   Edit `.env` to set database and email credentials.

4.  **Run Development Server:**
    *   From the project root, run: `php -S localhost:8000 router.php`

## Development Conventions

*   **MVC Pattern**: The application follows a custom MVC-like architecture with `app/controllers`, `app/models`, and `views` directories.
*   **Database Interaction**: Uses a custom `Database` class with PDO for secure database operations (prepared statements).
*   **Authentication/Authorization**: Implemented via `auth_middleware.php` and `Auth` controller, supporting role-based access.
*   **Session Management**: Custom helper functions in `session_helper.php` are used for session handling.
*   **Emailing**: PHPMailer is used for sending emails, configured via `config.php`.
*   **Routing**: A custom `App.php` class handles URL parsing and dispatching requests to the appropriate controller and method.
*   **Error Handling**: Basic `die()` statements are used for critical errors (e.g., database connection, missing views).
*   **Security**: Includes basic input sanitization (though `filter_input_array` was removed for debugging), password hashing, and placeholders for CSRF and rate limiting.

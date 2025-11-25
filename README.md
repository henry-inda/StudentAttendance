# Student Attendance Monitoring System

## Project Overview

This project is a full-stack Student Attendance Monitoring System built with PHP, MySQL, Bootstrap, JavaScript, and CSS. It features role-based access for Admin, Lecturer, and Student users. The system includes modules for user management, department, course, and unit management, student enrollments, attendance marking, excuse requests, system settings, logging, and reporting. It utilizes a custom MVC-like framework, PDO for database interaction, and PHPMailer for email notifications.

## Getting Started

### Starting the Application

The application requires two services to run:
1. PHP Built-in Server (main application)
2. WebSocket Server (for real-time notifications)

To start both services, you can use one of the following scripts from the project root:

#### Windows PowerShell
```powershell
.\scripts\start_services.ps1
```

#### Windows Command Prompt
```cmd
scripts\start_services.bat
```

This will start:
- PHP Development Server at http://localhost:8000
- WebSocket Server at ws://localhost:8080

### Troubleshooting

If you encounter any issues:

1. Make sure XAMPP is running (Apache and MySQL services)
2. Check that ports 8000 and 8080 are not in use
3. If windows don't open:
   - Try running the script as administrator
   - Check Windows PowerShell execution policy
   - Verify PHP is in your system PATH
4. For WebSocket connection issues:
   - Ensure your browser supports WebSocket connections
   - Check if any firewall is blocking the connection
   - Verify the WebSocket server is running (check the separate window)

@echo off
REM Start WebSocket server and PHP built-in server in separate windows
REM Usage: Double-click this file or run from PowerShell/CMD: scripts\start_services.bat

REM Start WebSocket server (assumes run_websocket_server.bat is in project root)
start "WebSocketServer" "%~dp0\..\run_websocket_server.bat"

REM Start PHP built-in server with document root set to project root
start "PHP Server" cmd /k "cd /d %~dp0\.. && php -S localhost:8000"

exit /b 0
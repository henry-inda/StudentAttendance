# Start both WebSocket server and PHP built-in server in separate windows
# Usage: Right-click -> Run with PowerShell, or run in PowerShell:
#   ./scripts/start_services.ps1

$ErrorActionPreference = "Stop"
$projectRoot = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)
$wsServerPath = Join-Path $projectRoot "run_websocket_server.bat"

Write-Host "Starting WebSocket server from $wsServerPath..."
Start-Process "cmd.exe" -ArgumentList @("/k", $wsServerPath) -WindowStyle Normal

Write-Host "Starting PHP built-in server on http://localhost:8000..."
$phpCmd = "php -S localhost:8000"
Start-Process "cmd.exe" -ArgumentList @("/k", $phpCmd) -WorkingDirectory $projectRoot -WindowStyle Normal

Write-Host "Services started. Check the new windows for server output."
Write-Host "WebSocket server: ws://localhost:8080"
Write-Host "PHP server: http://localhost:8000"
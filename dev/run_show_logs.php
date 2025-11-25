<?php
// dev/run_show_logs.php

// Bootstrap the application
require_once 'app/config/config.php';
require_once 'app/core/Database.php';
require_once 'app/models/SystemLog.php';

// Instantiate the SystemLog model
$systemLog = new SystemLog();

// Get all logs
$logs = $systemLog->getAll();

// Display the logs
foreach ($logs as $log) {
    echo "ID: {$log->id}\n";
    echo "User ID: {$log->user_id}\n";
    echo "Action: {$log->action}\n";
    echo "Details: {$log->details}\n";
    echo "IP Address: {$log->ip_address}\n";
    echo "Timestamp: {$log->created_at}\n";
    echo "------------------------\n";
}


<?php
require_once 'app/config/config.php';

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$sql = file_get_contents('database/migrations/20251112_add_attendance_submitted_to_schedules.sql');

if ($db->multi_query($sql)) {
    echo "Migration ran successfully!";
} else {
    echo "Error running migration: " . $db->error;
}

$db->close();
?>

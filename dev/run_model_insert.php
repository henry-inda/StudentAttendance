<?php
// Direct model test: attempt to insert an excuse request using the model directly.
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../app/config/config.php';
require __DIR__ . '/../app/core/Database.php';
require __DIR__ . '/../app/models/ExcuseRequest.php';

// Use realistic test values; adjust IDs per your DB
$data = [
    'student_id' => 2,
    'schedule_id' => 1,
    'date' => date('Y-m-d'),
    'reason' => 'Runner test: excuse request inserted via direct model test.'
];

try {
    $er = new ExcuseRequest();
    $ok = $er->create($data);
    echo "Model create returned: ";
    var_export($ok);
    echo PHP_EOL;
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . PHP_EOL;
}

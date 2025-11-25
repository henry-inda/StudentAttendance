<?php

// Auto-load all helper files in this directory in a safe order.
// Keep `error_handler.php` excluded so the error handler can be included
// after the session is started in index.php.

$dir = __DIR__;

// Files that must be required first (if present)
$priority = [
	'session_helper.php',
    'security_helper.php', // Include security helper early for CSRF
];

foreach ($priority as $p) {
	$path = $dir . DIRECTORY_SEPARATOR . $p;
	if (file_exists($path)) {
		require_once $path;
	}
}

// Generate CSRF token after session is started and security helper is loaded
generate_csrf_token();

// Require remaining helper files (alphabetical) except this bootstrap and error handler
$files = glob($dir . DIRECTORY_SEPARATOR . '*.php');
sort($files, SORT_STRING);
foreach ($files as $file) {
	$base = basename($file);
	if ($base === basename(__FILE__) || $base === 'error_handler.php') {
		continue;
	}
	// Skip files already required in $priority
	if (in_array($base, $priority)) {
		continue;
	}
	// Only include snake_case helper filenames to avoid loading legacy PascalCase files
	if (preg_match('/^[a-z0-9_]+\.php$/', $base)) {
		require_once $file;
	}
}

if (!function_exists('get_semester_name')) {
    function get_semester_name($semester_name_from_db) {
        switch ($semester_name_from_db) {
            case 'JAN/APR':
                return 'Jan/Apr';
            case 'MAY/AUG':
                return 'May/Aug';
            case 'SEP/DEC':
                return 'Sep/Dec';
            case '1': // Also handle numeric if they exist in old data
                return 'Jan/Apr';
            case '2':
                return 'May/Aug';
            case '3':
                return 'Sep/Dec';
            default:
                return 'Unknown';
        }
    }
}
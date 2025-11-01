<?php

// Auto-load all helper files in this directory in a safe order.
// Keep `error_handler.php` excluded so the error handler can be included
// after the session is started in index.php.

$dir = __DIR__;

// Files that must be required first (if present)
$priority = [
	'session_helper.php',
];

foreach ($priority as $p) {
	$path = $dir . DIRECTORY_SEPARATOR . $p;
	if (file_exists($path)) {
		require_once $path;
	}
}

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


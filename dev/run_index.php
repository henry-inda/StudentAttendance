<?php
// Temporary runner to simulate a web request to index.php from CLI
$_SERVER['REQUEST_URI'] = '/';
$_GET['url'] = '';
// Ensure environment variables exist
if (!isset($_ENV['APP_ENV'])) {
    $_ENV['APP_ENV'] = 'development';
}
require 'index.php';

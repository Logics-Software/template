<?php
/**
 * Fallback Router for Shared Hosting
 * Use this if .htaccess doesn't work
 */

// Get the path from URL
$path = $_GET['path'] ?? '';

// Include the main application
$_SERVER['REQUEST_URI'] = '/' . $path;
require_once 'index.php';
?>

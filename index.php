<?php
/**
 * Logics PHP APP Template
 * Main entry point for the application
 */

// Start output buffering to prevent headers already sent issues
ob_start();

// Define application constants
define('APP_PATH', __DIR__);

// Build APP_URL with proper sub-directory support (same as BASE_URL logic)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptDir = dirname($scriptName);

// Build APP_URL to match BASE_URL structure
if ($scriptDir === '/' || $scriptDir === '.' || $scriptDir === '\\') {
    // Root directory
    define('APP_URL', $protocol . '://' . $host);
} else {
    // Subdirectory - include the path
    define('APP_URL', $protocol . '://' . $host . $scriptDir);
}

// Set error reporting (but suppress warnings that cause headers issues)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable display_errors to prevent output

// Include configuration
require_once 'app/config/config.php';

// Include autoloader
require_once 'app/core/Autoloader.php';

// Initialize autoloader
$autoloader = new Autoloader();
$autoloader->register();

// Start the application
try {
    
    $app = App::getInstance();
    $app->run();
} catch (Exception $e) {
    error_log($e->getMessage());
    if (APP_DEBUG) {
        echo '<h1>Application Error</h1><p>' . $e->getMessage() . '</p>';
    } else {
        echo '<h1>Something went wrong</h1>';
    }
}

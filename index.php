<?php
/**
 * Logics PHP APP Template
 * Main entry point for the application
 */

// Define application constants
define('APP_PATH', __DIR__);
define('APP_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost:8000'));

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include configuration
require_once 'app/config/config.php';

// Include autoloader
require_once 'app/core/Autoloader.php';

// Initialize autoloader
$autoloader = new Autoloader();
$autoloader->register();

// Start the application
try {
    // Debug routing for troubleshooting
    if (APP_DEBUG && isset($_GET['debug'])) {
        echo '<h3>Debug Info:</h3>';
        echo 'REQUEST_URI: ' . ($_SERVER['REQUEST_URI'] ?? 'not set') . '<br>';
        echo 'SCRIPT_NAME: ' . ($_SERVER['SCRIPT_NAME'] ?? 'not set') . '<br>';
        echo 'REQUEST_METHOD: ' . ($_SERVER['REQUEST_METHOD'] ?? 'not set') . '<br>';
        
        $request = new Request();
        echo 'Processed URI: ' . $request->uri() . '<br>';
        echo 'Method: ' . $request->method() . '<br>';
        exit;
    }
    
    $app = new App();
    $app->run();
} catch (Exception $e) {
    error_log($e->getMessage());
    if (APP_DEBUG) {
        echo '<h1>Application Error</h1><p>' . $e->getMessage() . '</p>';
    } else {
        echo '<h1>Something went wrong</h1>';
    }
}

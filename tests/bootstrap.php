<?php
/**
 * PHPUnit Bootstrap File
 * Setup test environment and autoloading
 */

// Define test constants
define('APP_PATH', dirname(__DIR__));
define('APP_URL', 'http://localhost:8000');
define('APP_DEBUG', true);

// Define database constants for testing
define('DB_TYPE', 'sqlite');
define('DB_HOST', '');
define('DB_PORT', '');
define('DB_NAME', ':memory:');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Define session constants
define('SESSION_NAME', 'LOGICS_SESSION');
define('SESSION_LIFETIME', 3600);

// Define page size constant
define('DEFAULT_PAGE_SIZE', 10);

// Include autoloader
require_once APP_PATH . '/app/core/Autoloader.php';

// Initialize autoloader
$autoloader = new Autoloader();
$autoloader->register();

// Set error reporting for tests
error_reporting(E_ALL);
ini_set('display_errors', 1);

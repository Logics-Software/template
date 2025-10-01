<?php
/**
 * Application Configuration
 */

// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return [];
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }
        
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Remove quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            $env[$name] = $value;
        }
    }
    
    return $env;
}

// Load .env file
$env = loadEnv(__DIR__ . '/../../.env');

// Application settings
define('APP_NAME', $env['APP_NAME'] ?? 'Logics Template Application');
define('APP_VERSION', $env['APP_VERSION'] ?? '1.0.0');
define('APP_DEBUG', filter_var($env['APP_DEBUG'] ?? 'false', FILTER_VALIDATE_BOOLEAN));
define('APP_TIMEZONE', $env['APP_TIMEZONE'] ?? 'Asia/Jakarta');

// Database configuration
define('DB_TYPE', $env['DB_TYPE'] ?? 'mysql'); // mysql, sqlsrv, pgsql
define('DB_HOST', $env['DB_HOST'] ?? 'localhost');
define('DB_NAME', $env['DB_NAME'] ?? 'template');
define('DB_USER', $env['DB_USERNAME'] ?? 'root');
define('DB_PASS', $env['DB_PASSWORD'] ?? '');
define('DB_PORT', (int)($env['DB_PORT'] ?? 3306));
define('DB_CHARSET', $env['DB_CHARSET'] ?? 'utf8mb4');

// Session configuration
define('SESSION_LIFETIME', (int)($env['SESSION_LIFETIME'] ?? 8 * 3600)); // 8 hours for work sessions
define('SESSION_NAME', $env['SESSION_NAME'] ?? 'LOGICS_SESSION');
define('SESSION_REFRESH_INTERVAL', (int)($env['SESSION_REFRESH_INTERVAL'] ?? 30 * 60)); // 30 minutes - auto refresh interval
define('SESSION_WARNING_TIME', (int)($env['SESSION_WARNING_TIME'] ?? 5 * 60)); // 5 minutes - warning before expiry

// Security
define('ENCRYPTION_KEY', $env['ENCRYPTION_KEY'] ?? 'your-secret-key-here');
define('CSRF_TOKEN_NAME', $env['CSRF_TOKEN_NAME'] ?? '_token');

// Pagination
define('DEFAULT_PAGE_SIZE', (int)($env['DEFAULT_PAGE_SIZE'] ?? 10));
define('MAX_PAGE_SIZE', (int)($env['MAX_PAGE_SIZE'] ?? 100));

// File upload
define('UPLOAD_PATH', $env['UPLOAD_PATH'] ?? 'uploads/');
define('MAX_FILE_SIZE', (int)($env['MAX_FILE_SIZE'] ?? 5242880)); // 5MB
define('ALLOWED_EXTENSIONS', explode(',', $env['ALLOWED_EXTENSIONS'] ?? 'jpg,jpeg,png,gif,pdf,doc,docx'));

// Theme settings
define('DEFAULT_THEME', $env['DEFAULT_THEME'] ?? 'light');
define('THEME_COOKIE_NAME', $env['THEME_COOKIE_NAME'] ?? 'logics_theme');

// Base URL configuration for sub-folder deployment
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptDir = dirname($scriptName);

// Build base URL dynamically for /app/template/ sub-folder
if ($scriptDir === '/' || $scriptDir === '.') {
    // Root directory
    define('BASE_URL', $protocol . '://' . $host . '/');
} else {
    // Subdirectory - for /app/template/
    define('BASE_URL', $protocol . '://' . $host . $scriptDir . '/');
}

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

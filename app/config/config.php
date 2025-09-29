<?php
/**
 * Application Configuration
 */

// Application settings
define('APP_NAME', 'Logics Template Application');
define('APP_VERSION', '1.0.0');
define('APP_DEBUG', false);
define('APP_TIMEZONE', 'Asia/Jakarta');

// Database configuration
define('DB_TYPE', 'mysql'); // mysql, sqlsrv, pgsql
define('DB_HOST', 'localhost');
define('DB_NAME', 'template');
define('DB_USER', 'root');
define('DB_PASS', '051199');
define('DB_PORT', 3306);
define('DB_CHARSET', 'utf8mb4');

// Session configuration
define('SESSION_LIFETIME', 8 * 3600); // 8 hours for work sessions
define('SESSION_NAME', 'LOGICS_SESSION');
define('SESSION_REFRESH_INTERVAL', 30 * 60); // 30 minutes - auto refresh interval
define('SESSION_WARNING_TIME', 5 * 60); // 5 minutes - warning before expiry

// Security
define('ENCRYPTION_KEY', 'your-secret-key-here');
define('CSRF_TOKEN_NAME', '_token');

// Pagination
define('DEFAULT_PAGE_SIZE', 10);
define('MAX_PAGE_SIZE', 100);

// File upload
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// Theme settings
define('DEFAULT_THEME', 'light');
define('THEME_COOKIE_NAME', 'logics_theme');

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

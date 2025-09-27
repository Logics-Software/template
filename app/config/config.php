<?php
/**
 * Application Configuration
 */

// Application settings
define('APP_NAME', 'Logics Template Application');
define('APP_VERSION', '1.0.0');
define('APP_DEBUG', true);
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
define('SESSION_LIFETIME', 3600); // 1 hour
define('SESSION_NAME', 'HANDO_SESSION');

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
define('THEME_COOKIE_NAME', 'hando_theme');

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

<?php
/**
 * Script to generate secure password hash for admin user
 * Run this script to generate a new password hash
 */

// Generate hash for password "admin123"
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: {$password}\n";
echo "Hash: {$hash}\n\n";

// Generate SQL query
$sql = "INSERT INTO users (username, namalengkap, email, password, role, picture, status, lastlogin, created_at, updated_at) 
VALUES (
    'admin',
    'Administrator',
    'admin@example.com',
    '{$hash}', -- password: '{$password}'
    'admin',
    NULL,
    'aktif',
    NULL,
    NOW(),
    NOW()
);";

echo "SQL Query:\n";
echo $sql . "\n\n";

// Verify the hash
if (password_verify($password, $hash)) {
    echo "✅ Password hash verification successful!\n";
} else {
    echo "❌ Password hash verification failed!\n";
}
?>

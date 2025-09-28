-- Update users table for registration improvements
-- Add registration_reason column
ALTER TABLE users ADD COLUMN registration_reason TEXT NULL AFTER role;

-- Update role enum to include 'sales'
ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manajemen', 'user', 'marketing', 'customer', 'sales') DEFAULT 'user';

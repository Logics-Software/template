-- Insert default admin user
INSERT INTO users (username, namalengkap, email, password, role, picture, status, lastlogin, created_at, updated_at) 
VALUES (
    'admin',
    'Administrator',
    'admin@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: "password"
    'admin',
    NULL,
    'aktif',
    NULL,
    NOW(),
    NOW()
);

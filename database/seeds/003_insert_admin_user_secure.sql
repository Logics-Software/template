-- Insert default admin user with secure password
-- Password: admin123 (hashed with PHP password_hash)
INSERT INTO users (username, namalengkap, email, password, role, picture, status, lastlogin, created_at, updated_at) 
VALUES (
    'admin',
    'Administrator',
    'admin@example.com',
    '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', -- password: "admin123"
    'admin',
    NULL,
    'aktif',
    NULL,
    NOW(),
    NOW()
);

-- Insert additional admin user with different credentials
INSERT INTO users (username, namalengkap, email, password, role, picture, status, lastlogin, created_at, updated_at) 
VALUES (
    'superadmin',
    'Super Administrator',
    'superadmin@example.com',
    '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', -- password: "admin123"
    'admin',
    NULL,
    'aktif',
    NULL,
    NOW(),
    NOW()
);

-- Insert sample users for testing
-- Admin Users
INSERT INTO users (username, namalengkap, email, password, role, picture, status, lastlogin, created_at, updated_at) 
VALUES 
(
    'admin',
    'Administrator',
    'admin@example.com',
    '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', -- password: "admin123"
    'admin',
    NULL,
    'aktif',
    NOW(),
    NOW(),
    NOW()
),
(
    'manajer',
    'Manager',
    'manager@example.com',
    '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', -- password: "admin123"
    'manajemen',
    NULL,
    'aktif',
    NULL,
    NOW(),
    NOW()
);

-- Regular Users
INSERT INTO users (username, namalengkap, email, password, role, picture, status, lastlogin, created_at, updated_at) 
VALUES 
(
    'john_doe',
    'John Doe',
    'john@example.com',
    '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', -- password: "admin123"
    'user',
    NULL,
    'aktif',
    NULL,
    NOW(),
    NOW()
),
(
    'jane_smith',
    'Jane Smith',
    'jane@example.com',
    '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', -- password: "admin123"
    'marketing',
    NULL,
    'aktif',
    NULL,
    NOW(),
    NOW()
);

-- Customer Users
INSERT INTO users (username, namalengkap, email, password, role, picture, status, lastlogin, created_at, updated_at) 
VALUES 
(
    'customer1',
    'Customer One',
    'customer1@example.com',
    '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', -- password: "admin123"
    'customer',
    NULL,
    'aktif',
    NULL,
    NOW(),
    NOW()
);

-- Registration Users (pending activation)
INSERT INTO users (username, namalengkap, email, password, role, picture, status, lastlogin, created_at, updated_at) 
VALUES 
(
    'pending_user',
    'Pending User',
    'pending@example.com',
    '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', -- password: "admin123"
    'user',
    NULL,
    'register',
    NULL,
    NOW(),
    NOW()
);

-- Non Active User
INSERT INTO users (username, namalengkap, email, password, role, picture, status, lastlogin, created_at, updated_at) 
VALUES 
(
    'inactive_user',
    'Inactive User',
    'inactive@example.com',
    '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', -- password: "admin123"
    'user',
    NULL,
    'non_aktif',
    NULL,
    NOW(),
    NOW()
);

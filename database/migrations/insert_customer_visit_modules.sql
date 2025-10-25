-- ================================================================
-- Insert Modules untuk Customer Visit Tracking System
-- ================================================================

-- Insert Module: Customer Visit Tracking (untuk Marketing)
INSERT INTO modules (name, link, icon, role, visible, urutan, created_at) VALUES
('Customer Visit Tracking', '/customer-visits', 'fas fa-map-marked-alt', 'marketing', 1, 1, NOW()),
('Customer List', '/customers', 'fas fa-users', 'marketing', 1, 2, NOW());

-- Insert Module: Customer Visit Management (untuk Admin & Manajemen)
INSERT INTO modules (name, link, icon, role, visible, urutan, created_at) VALUES
('Customer Management', '/customers', 'fas fa-users', 'admin', 1, 11, NOW()),
('Customer Management', '/customers', 'fas fa-users', 'manajemen', 1, 11, NOW()),
('Visit Monitoring', '/customer-visits-monitoring', 'fas fa-chart-line', 'admin', 1, 12, NOW()),
('Visit Monitoring', '/customer-visits-monitoring', 'fas fa-chart-line', 'manajemen', 1, 12, NOW());

-- ================================================================
-- Insert Menu Groups (Optional - jika ingin group tersendiri)
-- ================================================================

-- Insert Menu Group untuk Marketing
INSERT INTO menu_groups (slug, menu_name, icon, urutan, is_active, created_at) VALUES
('marketing-activities', 'Marketing Activities', 'fas fa-briefcase', 5, 1, NOW());

-- ================================================================
-- Insert Menu Items untuk Marketing
-- ================================================================

-- Get menu_group_id untuk "marketing-activities"
SET @marketing_group_id = (SELECT id FROM menu_groups WHERE slug = 'marketing-activities' LIMIT 1);

-- Insert menu items
INSERT INTO menu_items (menu_group_id, parent_id, title, url, icon, urutan, is_active, created_at) VALUES
-- Parent Menu: Customer Visits
(@marketing_group_id, NULL, 'Customer Visits', '#', 'fas fa-map-marked-alt', 1, 1, NOW());

-- Get parent_id for Customer Visits
SET @customer_visits_parent = LAST_INSERT_ID();

-- Child Menu Items
INSERT INTO menu_items (menu_group_id, parent_id, title, url, icon, urutan, is_active, created_at) VALUES
(@marketing_group_id, @customer_visits_parent, 'Dashboard', '/customer-visits', 'fas fa-tachometer-alt', 1, 1, NOW()),
(@marketing_group_id, @customer_visits_parent, 'Mulai Kunjungan', '/customer-visits/select-customer', 'fas fa-plus-circle', 2, 1, NOW()),
(@marketing_group_id, @customer_visits_parent, 'Riwayat', '/customer-visits/history', 'fas fa-history', 3, 1, NOW()),
(@marketing_group_id, @customer_visits_parent, 'Customer List', '/customers', 'fas fa-users', 4, 1, NOW());

-- ================================================================
-- Insert Menu Items untuk Admin/Manajemen
-- ================================================================

-- Get menu_group_id untuk "admin-menu" atau "management-menu"
SET @admin_group_id = (SELECT id FROM menu_groups WHERE slug = 'admin-menu' LIMIT 1);

-- Insert menu items untuk Admin
INSERT INTO menu_items (menu_group_id, parent_id, title, url, icon, urutan, is_active, created_at) VALUES
-- Parent Menu: Customer Management
(@admin_group_id, NULL, 'Customer Management', '#', 'fas fa-users-cog', 10, 1, NOW());

SET @customer_mgmt_parent = LAST_INSERT_ID();

-- Child Menu Items
INSERT INTO menu_items (menu_group_id, parent_id, title, url, icon, urutan, is_active, created_at) VALUES
(@admin_group_id, @customer_mgmt_parent, 'Customer Data', '/customers', 'fas fa-database', 1, 1, NOW()),
(@admin_group_id, @customer_mgmt_parent, 'Visit Monitoring', '/customer-visits-monitoring', 'fas fa-chart-line', 2, 1, NOW()),
(@admin_group_id, @customer_mgmt_parent, 'Visit Report', '/customer-visits-monitoring/report', 'fas fa-file-alt', 3, 1, NOW());

-- ================================================================
-- Assign Default Menu untuk Role Marketing
-- ================================================================

-- Insert users_menu untuk semua user dengan role marketing
INSERT INTO users_menu (user_id, menu_group_id, created_at)
SELECT u.id, @marketing_group_id, NOW()
FROM users u
WHERE u.role = 'marketing'
AND NOT EXISTS (
    SELECT 1 FROM users_menu um 
    WHERE um.user_id = u.id AND um.menu_group_id = @marketing_group_id
);

-- ================================================================
-- DONE! Module dan Menu berhasil ditambahkan
-- ================================================================

-- Verifikasi hasil
SELECT '=== MODULES ===' as info;
SELECT * FROM modules WHERE link LIKE '%customer%' OR link LIKE '%visit%';

SELECT '=== MENU GROUPS ===' as info;
SELECT * FROM menu_groups WHERE slug = 'marketing-activities';

SELECT '=== MENU ITEMS ===' as info;
SELECT * FROM menu_items WHERE menu_group_id = @marketing_group_id OR menu_group_id = @admin_group_id;


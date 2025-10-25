-- ============================================
-- CUSTOMER VISIT TRACKING SYSTEM - DATABASE MIGRATION
-- Created: 2025-10-22
-- Description: Tables for Marketing Customer Visit Tracking
-- ============================================

-- Tabel Master Customer
CREATE TABLE IF NOT EXISTS customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_code VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    owner_name VARCHAR(255) NULL,
    phone VARCHAR(20) NULL,
    email VARCHAR(255) NULL,
    address TEXT NOT NULL,
    
    -- GPS Coordinate customer
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    
    customer_type ENUM('retail', 'wholesale', 'distributor', 'other') DEFAULT 'retail',
    customer_category VARCHAR(100) NULL COMMENT 'Toko/Apotek/Rumah Sakit/dll',
    
    -- Assignment
    assigned_marketing_id INT NULL COMMENT 'Marketing yang handle',
    
    status ENUM('active', 'inactive', 'prospect') DEFAULT 'active',
    
    -- Additional info
    notes TEXT NULL,
    last_visit_date DATE NULL,
    total_visits INT DEFAULT 0,
    total_orders INT DEFAULT 0,
    
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (assigned_marketing_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_customer_code (customer_code),
    INDEX idx_customer_name (customer_name),
    INDEX idx_assigned_marketing (assigned_marketing_id),
    INDEX idx_status (status),
    INDEX idx_last_visit (last_visit_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Visit/Kunjungan
CREATE TABLE IF NOT EXISTS customer_visits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    visit_code VARCHAR(50) UNIQUE NOT NULL,
    customer_id INT NOT NULL,
    marketing_id INT NOT NULL,
    
    visit_date DATE NOT NULL,
    check_in_time DATETIME NOT NULL,
    check_out_time DATETIME NULL,
    duration_minutes INT NULL,
    
    -- GPS saat check in
    check_in_latitude DECIMAL(10, 8) NOT NULL,
    check_in_longitude DECIMAL(11, 8) NOT NULL,
    check_in_address TEXT NULL,
    check_in_accuracy DECIMAL(8, 2) NULL COMMENT 'GPS accuracy dalam meter',
    
    -- GPS saat check out
    check_out_latitude DECIMAL(10, 8) NULL,
    check_out_longitude DECIMAL(11, 8) NULL,
    check_out_address TEXT NULL,
    
    -- Distance validation
    distance_from_customer DECIMAL(10, 2) NULL COMMENT 'jarak dari lokasi customer (meter)',
    is_location_valid TINYINT(1) DEFAULT 1,
    
    -- Dokumentasi
    photos TEXT NULL COMMENT 'JSON array foto-foto',
    
    -- Hasil kunjungan
    visit_purpose ENUM('sales', 'follow_up', 'complaint', 'delivery', 'survey', 'other') NOT NULL,
    visit_result ENUM('order_success', 'follow_up_needed', 'rejected', 'no_decision', 'other') NULL,
    
    -- Order information (jika ada)
    has_order TINYINT(1) DEFAULT 0,
    order_amount DECIMAL(15, 2) NULL,
    order_notes TEXT NULL,
    
    -- Feedback & Notes
    customer_feedback TEXT NULL,
    visit_notes TEXT NOT NULL,
    problems TEXT NULL,
    next_action TEXT NULL,
    next_visit_plan DATE NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (marketing_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_visit_code (visit_code),
    INDEX idx_customer (customer_id),
    INDEX idx_marketing (marketing_id),
    INDEX idx_visit_date (visit_date),
    INDEX idx_marketing_date (marketing_id, visit_date),
    INDEX idx_check_in_time (check_in_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk target kunjungan bulanan
CREATE TABLE IF NOT EXISTS visit_targets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    marketing_id INT NOT NULL,
    target_month DATE NOT NULL COMMENT 'YYYY-MM-01',
    target_visits INT NOT NULL DEFAULT 0,
    target_orders INT NOT NULL DEFAULT 0,
    target_amount DECIMAL(15, 2) NOT NULL DEFAULT 0,
    
    actual_visits INT DEFAULT 0,
    actual_orders INT DEFAULT 0,
    actual_amount DECIMAL(15, 2) DEFAULT 0,
    
    achievement_percentage DECIMAL(5, 2) DEFAULT 0,
    
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (marketing_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    
    UNIQUE KEY unique_marketing_month (marketing_id, target_month),
    INDEX idx_target_month (target_month)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SAMPLE DATA - Customers
-- ============================================
INSERT INTO customers (customer_code, customer_name, owner_name, phone, email, address, latitude, longitude, customer_type, customer_category, assigned_marketing_id, status, notes) VALUES
('CUST001', 'Toko Sejahtera', 'Pak Budi Santoso', '081234567890', 'toko.sejahtera@email.com', 'Jl. Merdeka No. 123, Surakarta', -7.5755620, 110.8243310, 'retail', 'Toko', 28, 'active', 'Customer setia, langganan rutin'),
('CUST002', 'Apotek Sehat Sentosa', 'Ibu Siti Aminah', '081234567891', 'apotek.sehat@email.com', 'Jl. Slamet Riyadi No. 456, Surakarta', -7.5655620, 110.8143310, 'retail', 'Apotek', 28, 'active', 'Apotek besar, potensial order besar'),
('CUST003', 'RS Kasih Ibu', 'Dr. Ahmad', '081234567892', 'rs.kasihibu@email.com', 'Jl. Dr. Radjiman No. 789, Surakarta', -7.5855620, 110.8343310, 'wholesale', 'Rumah Sakit', 28, 'active', 'Rumah sakit type C'),
('CUST004', 'Toko Berkah Jaya', 'Pak Joko', '081234567893', 'berkah.jaya@email.com', 'Jl. Veteran No. 321, Surakarta', -7.5955620, 110.8443310, 'retail', 'Toko', 28, 'active', NULL),
('CUST005', 'Apotek Medika Farma', 'Ibu Ratna', '081234567894', 'medika.farma@email.com', 'Jl. Gatot Subroto No. 654, Surakarta', -7.5555620, 110.8043310, 'retail', 'Apotek', 28, 'active', 'Buka 24 jam'),
('CUST006', 'Distributor Maju Bersama', 'Pak Hendra', '081234567895', 'maju.bersama@email.com', 'Jl. Ahmad Yani No. 111, Surakarta', -7.5655620, 110.8543310, 'distributor', 'Distributor', 28, 'active', 'Distributor wilayah Solo'),
('CUST007', 'Klinik Pratama Harapan', 'Dr. Sinta', '081234567896', 'klinik.harapan@email.com', 'Jl. Diponegoro No. 222, Surakarta', -7.5755620, 110.8643310, 'wholesale', 'Klinik', 28, 'prospect', 'Masih prospek, belum deal'),
('CUST008', 'Toko Makmur Sentosa', 'Pak Rudi', '081234567897', 'makmur.sentosa@email.com', 'Jl. Sudirman No. 333, Surakarta', -7.5855620, 110.8743310, 'retail', 'Toko', 28, 'active', NULL);

-- ============================================
-- SAMPLE DATA - Visit Targets (untuk testing)
-- ============================================
INSERT INTO visit_targets (marketing_id, target_month, target_visits, target_orders, target_amount, actual_visits, actual_orders, actual_amount, created_by) VALUES
(28, '2025-10-01', 20, 15, 50000000.00, 0, 0, 0.00, 2);


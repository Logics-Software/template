-- Create konfigurasi table
CREATE TABLE IF NOT EXISTS konfigurasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    namaperusahaan VARCHAR(255) NOT NULL,
    alamatperusahaan TEXT NOT NULL,
    npwp VARCHAR(50) NOT NULL DEFAULT '',
    noijin VARCHAR(100) NOT NULL DEFAULT '',
    penanggungjawab VARCHAR(255) NOT NULL,
    logo VARCHAR(255) NOT NULL DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default configuration record
INSERT INTO konfigurasi (
    namaperusahaan, 
    alamatperusahaan, 
    npwp, 
    noijin, 
    penanggungjawab, 
    logo
) VALUES (
    'Logics Template Application',
    'Jl. Contoh Alamat No. 123, Kota Contoh, 12345',
    '12.345.678.9-012.000',
    'SIUP-123456789',
    'Admin Sistem',
    ''
);

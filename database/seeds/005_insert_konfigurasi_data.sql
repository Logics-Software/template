-- Insert default configuration data
INSERT INTO konfigurasi (
    namaperusahaan, 
    alamatperusahaan, 
    npwp, 
    noijin, 
    penanggungjawab, 
    logo,
    created_at,
    updated_at
) VALUES (
    'Logics Template Application',
    'Jl. Contoh Alamat No. 123, Kota Contoh, 12345',
    '12.345.678.9-012.000',
    'SIUP-123456789',
    'Admin Sistem',
    '',
    NOW(),
    NOW()
);

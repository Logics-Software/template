<?php
// Get app information
$configFile = APP_PATH . '/app/config/config.php';
$configFileDate = file_exists($configFile) ? filemtime($configFile) : null;
$configFileFormatted = $configFileDate ? date('Y-m-d H:i:s', $configFileDate) : 'Unknown';

// Get PHP version
$phpVersion = PHP_VERSION;

// Get server info
$serverInfo = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
$serverName = $_SERVER['SERVER_NAME'] ?? 'Unknown';

// Get database info (if available)
try {
    require_once APP_PATH . '/app/core/Database.php';
    $db = Database::getInstance();
    $dbVersion = $db->getVersion();
} catch (Exception $e) {
    $dbVersion = 'Not connected';
}
?>
<!-- Footer -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="footer-left">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> - Developed by <a href="https://www.logics-ti.com" target="_blank">Logics Software</a></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="footer-right">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="footer-links">
                            <a href="#" class="text-muted" data-bs-toggle="modal" data-bs-target="#helpModal">
                                <i class="fas fa-question-circle"></i>
                            </a>
                            <a href="#" class="text-muted" data-bs-toggle="modal" data-bs-target="#appInfoModal">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>


<!-- App Information Modal -->
<div class="modal fade" id="appInfoModal" tabindex="-1" aria-labelledby="appInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appInfoModalLabel">
                    <i class="fas fa-info-circle text-primary me-2"></i>Informasi Aplikasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Application Information -->
                    <div class="col-md-12">
                        <div class="info-section">
                            <div class="info-list">
                                <div class="info-item">
                                    <span class="info-label">Nama:</span>
                                    <span class="info-value"><?php echo htmlspecialchars(APP_NAME); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Versi:</span>
                                    <span class="info-value"><?php echo htmlspecialchars(APP_VERSION); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Timezone:</span>
                                    <span class="info-value"><?php echo htmlspecialchars(APP_TIMEZONE); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Last Update:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($configFileFormatted); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Dikembangkan oleh:</span>
                                    <span class="info-value">
                                        <a href="https://www.logics-ti.com" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-external-link-alt me-1"></i>Logics Software
                                        </a>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">© Copyright:</span>
                                    <span class="info-value"><?php echo date('Y'); ?> Logics Software. All rights reserved.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="window.open('https://www.logics-ti.com', '_blank')">
                    <i class="fas fa-globe me-1"></i>Kunjungi Website
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">
                    <i class="fas fa-question-circle text-primary me-2"></i>Bantuan Aplikasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body help-modal-body">
                <div class="help-content">
                    <!-- Halaman Depan -->
                    <div class="help-header text-center mb-4">
                        <div class="help-subtitle mb-2">
                            <i class="fas fa-pills text-success me-2"></i>
                            Distribusi Farmasi yang Cepat dan Terpercaya
                        </div>
                        <h2 class="help-title">
                            <i class="fas fa-hospital text-primary me-2"></i>
                            Farmalogic — Solusi Terpadu Distribusi Farmasi Digital Anda
                        </h2>
                        <!-- Banner Visual Alur Distribusi -->
                        <div class="distribution-flow-banner mt-4">
                            <div class="flow-container">
                                <div class="flow-item">
                                    <i class="fas fa-industry text-primary"></i>
                                    <span>Pabrik</span>
                                </div>
                                <div class="flow-arrow">→</div>
                                <div class="flow-item">
                                    <i class="fas fa-warehouse text-warning"></i>
                                    <span>PBF</span>
                                </div>
                                <div class="flow-arrow">→</div>
                                <div class="flow-item">
                                    <i class="fas fa-store text-success"></i>
                                    <span>Apotek</span>
                                </div>
                                <div class="flow-arrow">→</div>
                                <div class="flow-item">
                                    <i class="fas fa-user-md text-info"></i>
                                    <span>Pasien</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div class="help-section">
                        <h4 class="help-section-title">
                            <i class="fas fa-info-circle text-info me-2"></i>Tentang Farmalogic
                        </h4>
                        <div class="help-section-content">
                            <p class="lead">Farmalogic adalah aplikasi B2B yang dirancang khusus untuk mempermudah dan mempercepat seluruh proses distribusi dan penjualan produk farmasi.</p>
                            <p>Hubungkan produsen, Pedagang Besar Farmasi (PBF), apotek, dan klinik dalam satu platform digital yang efisien, transparan, dan terjamin keamanannya.</p>
                        </div>
                    </div>

                    <!-- Tantangan yang Diatasi -->
                    <div class="help-section">
                        <h4 class="help-section-title">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>Tantangan Kami Atasi
                        </h4>
                        <div class="help-section-content">
                            <div class="challenges-list">
                                <div class="challenge-item">
                                    <i class="fas fa-clipboard-list text-danger me-2"></i>
                                    <span>Manajemen stok yang manual dan sering tidak akurat.</span>
                                </div>
                                <div class="challenge-item">
                                    <i class="fas fa-clock text-danger me-2"></i>
                                    <span>Proses pemesanan dan pengadaan obat yang lambat dan rumit.</span>
                                </div>
                                <div class="challenge-item">
                                    <i class="fas fa-bug text-danger me-2"></i>
                                    <span>Risiko kesalahan dalam pencatatan dan distribusi barang.</span>
                                </div>
                                <div class="challenge-item">
                                    <i class="fas fa-search text-danger me-2"></i>
                                    <span>Sulitnya memantau status pesanan secara real-time.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Manfaat Utama -->
                    <div class="help-section">
                        <h4 class="help-section-title">
                            <i class="fas fa-star text-success me-2"></i>Manfaat Utama
                        </h4>
                        <div class="help-section-content">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="benefit-card">
                                        <div class="benefit-icon">
                                            <i class="fas fa-cogs text-primary"></i>
                                        </div>
                                        <h6 class="benefit-title">Efisiensi dan Akurasi</h6>
                                        <p class="benefit-description">Otomatisasi pemesanan, pengecekan stok, dan pelacakan pengiriman mengurangi kesalahan manusia dan menghemat waktu.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="benefit-card">
                                        <div class="benefit-icon">
                                            <i class="fas fa-eye text-info"></i>
                                        </div>
                                        <h6 class="benefit-title">Transparansi Penuh</h6>
                                        <p class="benefit-description">Pantau status pesanan dan pergerakan produk secara real-time dari mana saja.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="benefit-card">
                                        <div class="benefit-icon">
                                            <i class="fas fa-shield-alt text-success"></i>
                                        </div>
                                        <h6 class="benefit-title">Kepatuhan Regulasi</h6>
                                        <p class="benefit-description">Fitur pencatatan nomor batch dan tanggal kedaluwarsa memudahkan pelaporan kepada pihak berwenang seperti BPOM.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="benefit-card">
                                        <div class="benefit-icon">
                                            <i class="fas fa-brain text-warning"></i>
                                        </div>
                                        <h6 class="benefit-title">Pengambilan Keputusan Cerdas</h6>
                                        <p class="benefit-description">Laporan analitik penjualan dan stok membantu Anda merencanakan pengadaan barang dengan lebih baik.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fitur Unggulan -->
                    <div class="help-section">
                        <h4 class="help-section-title">
                            <i class="fas fa-rocket text-primary me-2"></i>Fitur Unggulan Farmalogic
                        </h4>
                        <div class="help-section-content">
                            <div class="features-container">
                                <!-- 1. Manajemen Stok Otomatis -->
                                <div class="feature-section">
                                    <h5 class="feature-title">
                                        <i class="fas fa-boxes text-primary me-2"></i>1. Manajemen Stok Otomatis
                                    </h5>
                                    <div class="feature-details">
                                        <div class="feature-detail-item">
                                            <i class="fas fa-sync text-success me-2"></i>
                                            <strong>Sinkronisasi Stok:</strong> Stok diperbarui secara otomatis setiap kali ada penjualan atau pengiriman.
                                        </div>
                                        <div class="feature-detail-item">
                                            <i class="fas fa-bell text-warning me-2"></i>
                                            <strong>Notifikasi Stok Minimum:</strong> Aplikasi akan memberikan peringatan jika ada produk yang mencapai batas minimum stok.
                                        </div>
                                        <div class="feature-detail-item">
                                            <i class="fas fa-sort-numeric-down text-info me-2"></i>
                                            <strong>Sistem FIFO dan FEFO:</strong> Mengatur produk berdasarkan tanggal kedaluwarsa, memastikan produk yang lebih awal kedaluwarsa akan didistribusikan lebih dulu.
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Pemesanan dan Pengadaan Barang Digital -->
                                <div class="feature-section">
                                    <h5 class="feature-title">
                                        <i class="fas fa-shopping-cart text-success me-2"></i>2. Pemesanan dan Pengadaan Barang Digital
                                    </h5>
                                    <div class="feature-details">
                                        <div class="feature-detail-item">
                                            <i class="fas fa-list text-primary me-2"></i>
                                            <strong>Katalog Produk Online:</strong> Jelajahi ribuan produk farmasi dari berbagai PBF dalam satu katalog terpusat.
                                        </div>
                                        <div class="feature-detail-item">
                                            <i class="fas fa-chart-line text-success me-2"></i>
                                            <strong>Pesanan Berbasis Data:</strong> Buat pesanan pembelian berdasarkan riwayat penjualan dan stok yang tersedia untuk menghindari kelebihan atau kekurangan stok.
                                        </div>
                                        <div class="feature-detail-item">
                                            <i class="fas fa-link text-info me-2"></i>
                                            <strong>Integrasi PBF:</strong> Terhubung langsung dengan Pedagang Besar Farmasi yang terdaftar untuk mempercepat proses pemesanan.
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. Pelacakan Pengiriman Real-Time -->
                                <div class="feature-section">
                                    <h5 class="feature-title">
                                        <i class="fas fa-truck text-warning me-2"></i>3. Pelacakan Pengiriman Real-Time
                                    </h5>
                                    <div class="feature-details">
                                        <div class="feature-detail-item">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            <strong>Status Pengiriman:</strong> Pantau setiap pesanan dari gudang hingga sampai di tujuan.
                                        </div>
                                        <div class="feature-detail-item">
                                            <i class="fas fa-route text-success me-2"></i>
                                            <strong>Rute Pengiriman Optimal:</strong> Algoritma cerdas membantu menentukan rute terbaik untuk pengiriman, menghemat waktu dan biaya operasional.
                                        </div>
                                    </div>
                                </div>

                                <!-- 4. Laporan dan Analitik Bisnis -->
                                <div class="feature-section">
                                    <h5 class="feature-title">
                                        <i class="fas fa-chart-bar text-info me-2"></i>4. Laporan dan Analitik Bisnis
                                    </h5>
                                    <div class="feature-details">
                                        <div class="feature-detail-item">
                                            <i class="fas fa-tachometer-alt text-primary me-2"></i>
                                            <strong>Dashboard Interaktif:</strong> Lihat data penjualan, stok, dan keuntungan secara visual dalam satu dasbor yang mudah dipahami.
                                        </div>
                                        <div class="feature-detail-item">
                                            <i class="fas fa-file-alt text-success me-2"></i>
                                            <strong>Laporan Periodik:</strong> Buat laporan penjualan, pembelian, dan stok opname secara otomatis untuk kebutuhan audit atau perencanaan.
                                        </div>
                                        <div class="feature-detail-item">
                                            <i class="fas fa-trending-up text-warning me-2"></i>
                                            <strong>Analisis Tren:</strong> Identifikasi produk-produk terlaris dan pola pembelian pelanggan.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Target Pengguna -->
                    <div class="help-section">
                        <h4 class="help-section-title">
                            <i class="fas fa-users text-primary me-2"></i>Untuk Siapa Farmalogic Dibuat?
                        </h4>
                        <div class="help-section-content">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="user-card">
                                        <div class="user-icon">
                                            <i class="fas fa-warehouse text-primary"></i>
                                        </div>
                                        <h6 class="user-title">Pedagang Besar Farmasi (PBF)</h6>
                                        <ul class="user-features">
                                            <li><i class="fas fa-check text-success me-1"></i>Kelola Aliran Barang</li>
                                            <li><i class="fas fa-check text-success me-1"></i>Perluas Jangkauan Pasar</li>
                                            <li><i class="fas fa-check text-success me-1"></i>Otomatisasi Penagihan</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="user-card">
                                        <div class="user-icon">
                                            <i class="fas fa-store text-success"></i>
                                        </div>
                                        <h6 class="user-title">Apotek dan Klinik</h6>
                                        <ul class="user-features">
                                            <li><i class="fas fa-check text-success me-1"></i>Kemudahan Pemesanan</li>
                                            <li><i class="fas fa-check text-success me-1"></i>Ketersediaan Produk Terjamin</li>
                                            <li><i class="fas fa-check text-success me-1"></i>Efisiensi Operasional</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="user-card">
                                        <div class="user-icon">
                                            <i class="fas fa-industry text-warning"></i>
                                        </div>
                                        <h6 class="user-title">Produsen Farmasi</h6>
                                        <ul class="user-features">
                                            <li><i class="fas fa-check text-success me-1"></i>Visibilitas Penjualan</li>
                                            <li><i class="fas fa-check text-success me-1"></i>Manajemen Hubungan PBF</li>
                                            <li><i class="fas fa-check text-success me-1"></i>Optimalkan Rantai Pasok</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kontak -->
                    <div class="help-section help-contact">
                        <h4 class="help-section-title">
                            <i class="fas fa-phone text-primary me-2"></i>Hubungi Kami
                        </h4>
                        <div class="help-section-content">
                            <div class="contact-info">
                                <p class="lead text-center mb-4">Mari Mulai Digitalisasi Bisnis Farmasi Anda</p>
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="contact-card">
                                            <h6><i class="fas fa-envelope text-primary me-2"></i>Informasi Kontak</h6>
                                            <div class="contact-details">
                                                <p><i class="fas fa-phone text-success me-2"></i><strong>Telepon:</strong> (021) 1234-5678</p>
                                                <p><i class="fas fa-envelope text-primary me-2"></i><strong>Email:</strong> info@farmalogic.com</p>
                                                <p><i class="fas fa-map-marker-alt text-danger me-2"></i><strong>Alamat:</strong> Jl. Farmasi Digital No. 123, Jakarta</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="contact-card">
                                            <h6><i class="fas fa-calendar text-success me-2"></i>Jadwalkan Demo</h6>
                                            <p class="mb-3">Lihat langsung bagaimana Farmalogic dapat meningkatkan efisiensi bisnis farmasi Anda.</p>
                                            <button class="btn btn-primary w-100">
                                                <i class="fas fa-play me-1"></i>Jadwalkan Demo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print me-1"></i>Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<style>

/* App Info Modal Styles */
.info-section {
    margin-bottom: 0;
}

.info-section-title {
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--border-color);
    color: var(--text-primary);
}

.info-list {
    background: var(--bg-secondary);
    border-radius: var(--border-radius-md);
    padding: 1rem;
    border: 1px solid var(--border-color);
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 500;
    color: var(--text-secondary);
    min-width: 120px;
}

.info-value {
    color: var(--text-primary);
    text-align: right;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
}

.info-value a {
    color: var(--primary-color);
    transition: color 0.3s ease;
}

.info-value a:hover {
    color: var(--primary-color);
    text-decoration: underline !important;
}

/* Modal responsive adjustments */
@media (max-width: 768px) {
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .info-value {
        text-align: left;
    }
    
    .info-label {
        min-width: auto;
    }
}

/* Dark theme support for App Info Modal */
[data-bs-theme="dark"] .info-section-title {
    color: var(--text-primary);
    border-bottom-color: var(--border-dark);
}

[data-bs-theme="dark"] .info-list {
    background: var(--bg-secondary);
    border-color: var(--border-dark);
}

[data-bs-theme="dark"] .info-item {
    border-bottom-color: var(--border-dark);
}

[data-bs-theme="dark"] .info-label {
    color: var(--text-secondary);
}

[data-bs-theme="dark"] .info-value {
    color: var(--text-primary);
}

[data-bs-theme="dark"] .info-value a {
    color: var(--primary-color);
}

[data-bs-theme="dark"] .info-value a:hover {
    color: var(--primary-color);
}

/* Farmalogic Help Modal Styles */
.help-modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

.help-content {
    line-height: 1.6;
}

.help-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 2rem;
    border-radius: var(--border-radius-lg);
    margin-bottom: 2rem;
}

.help-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 500;
}

.help-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

/* Distribution Flow Banner */
.distribution-flow-banner {
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    backdrop-filter: blur(10px);
}

.flow-container {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.flow-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius-md);
    min-width: 100px;
    transition: all 0.3s ease;
}

.flow-item:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.flow-item i {
    font-size: 1.5rem;
}

.flow-item span {
    font-size: 0.9rem;
    font-weight: 500;
}

.flow-arrow {
    font-size: 1.5rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: bold;
}

.help-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-color);
}

.help-section-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--border-color);
}

.help-section-content {
    color: var(--text-primary);
}

/* Challenges List */
.challenges-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.challenge-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    background: var(--bg-primary);
    border-left: 4px solid var(--danger-color);
    border-radius: var(--border-radius-md);
    color: var(--text-primary);
}

.challenge-item i {
    font-size: 1.2rem;
    margin-top: 0.1rem;
}

/* Benefit Cards */
.benefit-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-md);
    padding: 1.5rem;
    height: 100%;
    transition: all 0.3s ease;
}

.benefit-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.benefit-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.benefit-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.benefit-description {
    color: var(--text-secondary);
    margin-bottom: 0;
    font-size: 0.9rem;
}

/* Feature Sections */
.features-container {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.feature-section {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
}

.feature-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--text-primary);
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border-color);
}

.feature-details {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.feature-detail-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--bg-secondary);
    border-radius: var(--border-radius-md);
    color: var(--text-primary);
}

.feature-detail-item i {
    font-size: 1.1rem;
    margin-top: 0.1rem;
}

/* User Cards */
.user-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    height: 100%;
    transition: all 0.3s ease;
}

.user-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.user-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    text-align: center;
}

.user-title {
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--text-primary);
    text-align: center;
}

.user-features {
    list-style: none;
    padding: 0;
    margin: 0;
}

.user-features li {
    padding: 0.25rem 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Contact Section */
.help-contact {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.help-contact .help-section-title {
    color: white;
    border-bottom-color: rgba(255, 255, 255, 0.3);
}

.help-contact .help-section-content {
    color: white;
}

.contact-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    backdrop-filter: blur(10px);
    height: 100%;
}

.contact-card h6 {
    color: white;
    font-weight: 600;
    margin-bottom: 1rem;
}

.contact-details p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 0.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .help-title {
        font-size: 1.4rem;
    }
    
    .help-header {
        padding: 1.5rem;
    }
    
    .help-section {
        padding: 1rem;
    }
    
    .flow-container {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .flow-arrow {
        transform: rotate(90deg);
    }
    
    .feature-detail-item {
        flex-direction: column;
        text-align: center;
    }
    
    .challenge-item {
        flex-direction: column;
        text-align: center;
    }
}

/* Dark theme support for Farmalogic Help Modal */
[data-bs-theme="dark"] .help-section {
    background: var(--bg-secondary);
    border-color: var(--border-dark);
}

[data-bs-theme="dark"] .help-section-title {
    color: var(--text-primary);
    border-bottom-color: var(--border-dark);
}

[data-bs-theme="dark"] .help-section-content {
    color: var(--text-primary);
}

[data-bs-theme="dark"] .challenge-item {
    background: var(--bg-primary);
    color: var(--text-primary);
}

[data-bs-theme="dark"] .benefit-card {
    background: var(--bg-primary);
    border-color: var(--border-dark);
}

[data-bs-theme="dark"] .benefit-title {
    color: var(--text-primary);
}

[data-bs-theme="dark"] .benefit-description {
    color: var(--text-secondary);
}

[data-bs-theme="dark"] .feature-section {
    background: var(--bg-primary);
    border-color: var(--border-dark);
}

[data-bs-theme="dark"] .feature-title {
    color: var(--text-primary);
    border-bottom-color: var(--border-dark);
}

[data-bs-theme="dark"] .feature-detail-item {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

[data-bs-theme="dark"] .user-card {
    background: var(--bg-primary);
    border-color: var(--border-dark);
}

[data-bs-theme="dark"] .user-title {
    color: var(--text-primary);
}

[data-bs-theme="dark"] .user-features li {
    color: var(--text-secondary);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // App Info Modal functionality
    const appInfoModal = document.getElementById('appInfoModal');
    if (appInfoModal) {
        appInfoModal.addEventListener('show.bs.modal', function () {
            // Add any dynamic content loading here if needed
        });

        appInfoModal.addEventListener('hide.bs.modal', function () {
        });
    }

    // Help Modal functionality
    const helpModal = document.getElementById('helpModal');
    if (helpModal) {
        helpModal.addEventListener('show.bs.modal', function () {
            // Scroll to top when modal opens
            setTimeout(() => {
                helpModal.querySelector('.help-modal-body').scrollTop = 0;
            }, 100);
        });

        helpModal.addEventListener('hide.bs.modal', function () {
        });
    }
});
</script>

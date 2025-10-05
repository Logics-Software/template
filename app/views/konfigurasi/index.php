<?php
// Get current configuration
$konfigurasi = $data['konfigurasi'] ?? null;

// Check if configuration exists
if (!$konfigurasi) {
    echo '<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Konfigurasi Belum Ada</h5>
                        <p class="card-text">Silakan buat konfigurasi sistem terlebih dahulu.</p>
                        <a href="' . APP_URL . '/konfigurasi/create" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Buat Konfigurasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>';
    return;
}
?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                Konfigurasi Sistem
            </h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?php echo APP_URL; ?>/dashboard">
                            Home
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Konfigurasi
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Logo Section -->
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <label class="form-label fw-bold">Logo Perusahaan</label>
                    <div>
                        <?php if (!empty($konfigurasi['logo'])): ?>
                            <img src="<?php echo APP_URL; ?>/assets/images/konfigurasi/<?php echo htmlspecialchars($konfigurasi['logo']); ?>" 
                                    alt="Logo Perusahaan" 
                                    class="img-fluid" 
                                    class="max-h-200-max-w-100">
                        <?php else: ?>
                            <div class="text-muted">
                                <i class="fas fa-image fa-3x mb-2"></i>
                                <p>Logo belum diupload</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            <div class="col-md-8">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Nama Perusahaan</label>
                        <div class="form-control-plaintext bg-light p-3 rounded border">
                            <?php echo htmlspecialchars($konfigurasi['namaperusahaan'] ?? 'Belum diisi'); ?>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Alamat Perusahaan</label>
                        <div class="form-control-plaintext bg-light p-3 rounded border">
                            <?php echo nl2br(htmlspecialchars($konfigurasi['alamatperusahaan'] ?? 'Belum diisi')); ?>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">NPWP</label>
                        <div class="form-control-plaintext bg-light p-3 rounded border">
                            <?php echo htmlspecialchars($konfigurasi['npwp'] ?? 'Belum diisi'); ?>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nomor Ijin</label>
                        <div class="form-control-plaintext bg-light p-3 rounded border">
                            <?php echo htmlspecialchars($konfigurasi['noijin'] ?? 'Belum diisi'); ?>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Penanggung Jawab</label>
                        <div class="form-control-plaintext bg-light p-3 rounded border">
                            <?php echo htmlspecialchars($konfigurasi['penanggungjawab'] ?? 'Belum diisi'); ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?php echo APP_URL; ?>/dashboard" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Dashboard
            </a>
            <div>
                <a href="<?php echo APP_URL; ?>/konfigurasi/edit" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Edit Konfigurasi
                </a>
            </div>
        </div>
    </div>
</div>

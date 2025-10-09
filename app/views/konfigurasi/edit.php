<?php
$title = $data['title'] ?? 'Edit Konfigurasi Sistem';
$konfigurasi = $data['konfigurasi'] ?? [];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="form-container">
                <div class="form-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            Edit Konfigurasi Sistem
                        </h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo APP_URL; ?>/dashboard">
                                        Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="<?php echo APP_URL; ?>/konfigurasi">
                                        Konfigurasi
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Edit Konfigurasi
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="form-body">
                    <form id="konfigurasiForm" action="<?php echo APP_URL; ?>/konfigurasi/update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo Session::generateCSRF(); ?>">
                        
                        <div class="row">
                            <!-- Form Fields -->
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="namaperusahaan" name="namaperusahaan" 
                                                   placeholder="Nama Perusahaan" 
                                                   value="<?php echo htmlspecialchars($konfigurasi['namaperusahaan'] ?? ''); ?>" required>
                                            <label for="namaperusahaan">Nama Perusahaan *</label>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="alamatperusahaan" name="alamatperusahaan" 
                                                      placeholder="Alamat Perusahaan" class="textarea-100" required><?php echo htmlspecialchars($konfigurasi['alamatperusahaan'] ?? ''); ?></textarea>
                                            <label for="alamatperusahaan">Alamat Perusahaan *</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="npwp" name="npwp" 
                                                   placeholder="NPWP"
                                                   value="<?php echo htmlspecialchars($konfigurasi['npwp'] ?? ''); ?>">
                                            <label for="npwp">NPWP</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="noijin" name="noijin" 
                                                   placeholder="Nomor Ijin"
                                                   value="<?php echo htmlspecialchars($konfigurasi['noijin'] ?? ''); ?>">
                                            <label for="noijin">Nomor Ijin</label>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="penanggungjawab" name="penanggungjawab" 
                                                   placeholder="Penanggung Jawab"
                                                   value="<?php echo htmlspecialchars($konfigurasi['penanggungjawab'] ?? ''); ?>" required>
                                            <label for="penanggungjawab">Penanggung Jawab *</label>
                                        </div>
                                    </div>

                                    <!-- Logo Upload Section -->
                                    <div class="col-12 mb-3">
                                        <div class="form-group">
                                            <label for="logo" class="form-label fw-bold">Logo Perusahaan</label>
                                            
                                            <!-- Current Logo Display -->
                                            <?php if (!empty($konfigurasi['logo'])): ?>
                                                <div class="mb-3">
                                                    <div class="text-center">
                                                        <img src="<?php echo APP_URL; ?>/assets/images/konfigurasi/<?php echo htmlspecialchars($konfigurasi['logo']); ?>" 
                                                             alt="Logo Perusahaan" 
                                                             class="img-fluid mb-2 max-h-150">
                                                        <div class="text-muted small"><?php echo htmlspecialchars($konfigurasi['logo']); ?></div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*" onchange="handleFileSelect(this)">
                                            <div class="form-text">Format: JPG, PNG, GIF, WebP. Maksimal 5MB. Kosongkan jika tidak ingin mengubah logo.</div>
                                            
                                            <!-- File Preview -->
                                            <div id="file-preview" class="mt-3 d-none">
                                                <div class="text-center">
                                                    <label class="form-label text-muted">Preview Logo Baru:</label>
                                                    <img id="preview-image" src="" alt="Preview" class="img-fluid mb-2 max-h-150">
                                                    <div id="preview-filename" class="text-muted small"></div>
                                                    <div id="preview-size" class="text-muted small"></div>
                                                    <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removePreview()">
                                                        <i class="fas fa-times me-1"></i>Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="form-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?php echo APP_URL; ?>/konfigurasi" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Batal
                        </a>
                        <div>
                            <button type="submit" form="konfigurasiForm" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Konfigurasi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// File upload handling
function handleFileSelect(input) {
    const file = input.files[0];
    
    if (file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            window.Notify.warning('Tipe file tidak didukung. Gunakan JPG, PNG, GIF, atau WebP');
            input.value = '';
            return;
        }

        // Validate file size (5MB max)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            window.Notify.warning('Ukuran file terlalu besar. Maksimal 5MB');
            input.value = '';
            return;
        }
        
        showPreview(file);
    }
}

function showPreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        // Full preview
        const preview = document.getElementById('file-preview');
        const previewImage = document.getElementById('preview-image');
        const previewFilename = document.getElementById('preview-filename');
        const previewSize = document.getElementById('preview-size');
        
        if (previewImage) {
            previewImage.src = e.target.result;
        }
        if (previewFilename) previewFilename.textContent = file.name;
        if (previewSize) previewSize.textContent = formatFileSize(file.size);
        
        if (preview) {
            preview.classList.remove('d-none');
        }
    };
    reader.readAsDataURL(file);
}

function removePreview() {
    const logoInput = document.getElementById('logo');
    if (logoInput) logoInput.value = '';
    
    const preview = document.getElementById('file-preview');
    if (preview) preview.classList.add('d-none');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Form submission handling
document.getElementById('konfigurasiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Show loading state - button is outside the form
    const submitBtn = document.querySelector('button[form="konfigurasiForm"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
    submitBtn.disabled = true;
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error('Server returned invalid response');
            }
        });
    })
    .then(data => {
        if (data.success) {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                window.location.reload();
            }
        } else {
            showToast('error', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        showToast('error', 'Terjadi kesalahan saat menyimpan: ' + error.message);
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>

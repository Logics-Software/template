<div class="row">
    <div class="col-12">
        <div class="form-container">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Call Center</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/callcenter" class="text-decoration-none">Call Center</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="form-body">
                <form method="POST" action="<?php echo APP_URL; ?>/callcenter" id="createCallCenterForm">
                    <input type="hidden" name="_token" value="<?php echo Session::generateCSRF(); ?>">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul" required>
                                <label for="judul">Judul <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="nomorwa" name="nomorwa" placeholder="Nomor WhatsApp" required>
                                <label for="nomorwa">Nomor WhatsApp <span class="text-danger">*</span></label>
                                <div class="form-text">Masukkan nomor WhatsApp (contoh, +6281234567890)</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi" style="height: 120px;"></textarea>
                                <label for="deskripsi">Deskripsi</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Form Footer -->
            <div class="form-footer d-flex justify-content-between align-items-center">
                <a href="<?php echo APP_URL; ?>/callcenter" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Call Center
                </a>
                <button type="submit" form="createCallCenterForm" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Tambah Call Center
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Form submission with AJAX
document.getElementById("createCallCenterForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.querySelector('button[form="createCallCenterForm"]');
    const originalText = submitBtn ? submitBtn.innerHTML : '';
    
    if (submitBtn) {
        submitBtn.innerHTML = "<i class=\"fas fa-hourglass-split me-1\" tabindex=\"-1\"></i>Creating...";
        submitBtn.disabled = true;
    }
    
    fetch("<?php echo APP_URL; ?>/callcenter", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-Token": window.csrfToken,
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showToast('success', data.message);
            
            // Redirect after 2 seconds
            setTimeout(() => {
                window.location.href = "<?php echo APP_URL; ?>/callcenter";
            }, 2000);
        } else {
            // Show error message
            const alertDiv = document.createElement("div");
            alertDiv.className = "alert alert-danger alert-dismissible fade show";
            alertDiv.innerHTML = `
                ${data.error || "An error occurred"}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            const form = document.getElementById("createCallCenterForm");
            form.parentElement.insertBefore(alertDiv, form);
        }
    })
    .catch(error => {
        const alertDiv = document.createElement("div");
        alertDiv.className = "alert alert-danger alert-dismissible fade show";
        alertDiv.innerHTML = `
            An error occurred while creating the call center entry
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        const form = document.getElementById("createCallCenterForm");
        form.parentElement.insertBefore(alertDiv, form);
    })
    .finally(() => {
        if (submitBtn) {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });
});
</script>

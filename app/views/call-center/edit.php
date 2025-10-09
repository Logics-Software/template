<div class="row">
    <div class="col-12">
        <div class="form-container">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Call Center</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/call-center" class="text-decoration-none">Call Center</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="form-body">
                <form method="POST" action="<?php echo APP_URL; ?>/call-center/<?php echo $callCenter['id']; ?>" id="editCallCenterForm">
                    <input type="hidden" name="_token" value="<?php echo Session::generateCSRF(); ?>">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul" value="<?php echo htmlspecialchars($callCenter['judul']); ?>" required>
                                <label for="judul">Judul <span class="text-danger">*</span></label>
                                <div class="form-text">Enter a descriptive title for this call center entry</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="nomorwa" name="nomorwa" placeholder="Nomor WhatsApp" value="<?php echo htmlspecialchars($callCenter['nomorwa']); ?>" required>
                                <label for="nomorwa">Nomor WhatsApp <span class="text-danger">*</span></label>
                                <div class="form-text">Enter WhatsApp number (e.g., +6281234567890)</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi" style="height: 120px;"><?php echo htmlspecialchars($callCenter['deskripsi']); ?></textarea>
                                <label for="deskripsi">Deskripsi</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Form Footer -->
            <div class="form-footer d-flex justify-content-between align-items-center">
                <a href="<?php echo APP_URL; ?>/call-center" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Call Center
                </a>
                <button type="submit" form="editCallCenterForm" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Update Call Center
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Form submission with AJAX
document.getElementById("editCallCenterForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.querySelector('button[form="editCallCenterForm"]');
    const originalText = submitBtn ? submitBtn.innerHTML : '';
    
    if (submitBtn) {
        submitBtn.innerHTML = "<i class=\"fas fa-hourglass-split me-1\" tabindex=\"-1\"></i>Updating...";
        submitBtn.disabled = true;
    }
    
    fetch("<?php echo APP_URL; ?>/call-center/<?php echo $callCenter['id']; ?>", {
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
                window.location.href = "<?php echo APP_URL; ?>/call-center";
            }, 2000);
        } else {
            // Show error message
            const alertDiv = document.createElement("div");
            alertDiv.className = "alert alert-danger alert-dismissible fade show";
            alertDiv.innerHTML = `
                ${data.error || "An error occurred"}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            const form = document.getElementById("editCallCenterForm");
            form.parentElement.insertBefore(alertDiv, form);
        }
    })
    .catch(error => {
        const alertDiv = document.createElement("div");
        alertDiv.className = "alert alert-danger alert-dismissible fade show";
        alertDiv.innerHTML = `
            An error occurred while updating the call center entry
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        const form = document.getElementById("editCallCenterForm");
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

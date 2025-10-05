<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New User</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/users" class="text-decoration-none">Users</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="card-body">
                <form method="POST" action="<?php echo APP_URL; ?>/users" id="createUserForm" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                <label for="username">Username <span class="text-danger">*</span></label>
                                <div class="form-text">Username must be unique</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="namalengkap" name="namalengkap" placeholder="Nama Lengkap" required>
                                <label for="namalengkap">Nama Lengkap <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                                <label for="email">Email Address <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="new-password" required>
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <button class="btn btn-outline-secondary position-absolute top-0 end-0 h-100 d-flex align-items-center justify-content-center password-toggle-btn" type="button" id="togglePassword" class="password-toggle" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" autocomplete="new-password" required>
                                <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                <button class="btn btn-outline-secondary position-absolute top-0 end-0 h-100 d-flex align-items-center justify-content-center password-toggle-btn" type="button" id="togglePasswordConfirmation" class="password-toggle" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin">Administrator</option>
                                    <option value="manajemen">Manajemen</option>
                                    <option value="user">User</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="customer">Customer</option>
                                </select>
                                <label for="role">Role <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="status" name="status">
                                    <option value="aktif" selected>Aktif</option>
                                    <option value="non_aktif">Non Aktif</option>
                                    <option value="register">Register</option>
                                </select>
                                <label for="status">Status</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Profile Picture</label>
                                <div class="file-upload-container">
                                    <input type="file" class="form-control" id="picture" name="picture" accept="image/*" onchange="handleFileSelect(this)">
                                    <div class="form-text mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Upload a profile picture (optional) - Supports: JPG, PNG, GIF, WEBP (Max 5MB)
                                    </div>
                                </div>
                                <div id="file-preview" class="file-upload-preview d-none">
                                    <div class="preview-container">
                                        <img id="preview-image" class="preview-image" alt="Preview" class="img-preview">
                                        <div class="preview-overlay">
                                            <button type="button" class="btn btn-danger remove-preview" onclick="removePreview()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary change-preview" onclick="document.getElementById('picture').click()">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="preview-info">
                                        <div id="preview-filename" class="fw-bold"></div>
                                        <div id="preview-size" class="text-muted small"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Form Footer -->
            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="<?php echo APP_URL; ?>/users" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Users
                </a>
                <button type="submit" form="createUserForm" class="btn btn-primary">
                    <i class="fas fa-check-circle me-1"></i>Create User
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// File upload handling functions (defined early for inline handlers)
function handleFileSelect(input) {
    const file = input.files[0];
    
    if (file) {
        // Validate file type
        const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp"];
        if (!allowedTypes.includes(file.type)) {
            AlertManager.warning("Please select a valid image file (JPG, PNG, GIF, WEBP)");
            input.value = "";
            return;
        }
        
        // Validate file size (5MB max)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            AlertManager.warning("File size must be less than 5MB");
            input.value = "";
            return;
        }
        
        showPreview(file);
    }
}

function showPreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById("file-preview");
        const previewImage = document.getElementById("preview-image");
        const previewFilename = document.getElementById("preview-filename");
        const previewSize = document.getElementById("preview-size");
        
        if (previewImage) {
            previewImage.src = e.target.result;
        }
        if (previewFilename) previewFilename.textContent = file.name;
        if (previewSize) previewSize.textContent = formatFileSize(file.size);
        
        if (preview) {
            preview.classList.remove("d-none");
        }
    };
    reader.readAsDataURL(file);
}

function removePreview() {
    const pictureInput = document.getElementById("picture");
    if (pictureInput) pictureInput.value = "";
    
    const preview = document.getElementById("file-preview");
    if (preview) preview.classList.add("d-none");
}

function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
}

// Password toggle functionality
document.addEventListener("DOMContentLoaded", function() {
    // Password toggle
    const togglePassword = document.getElementById("togglePassword");
    if (togglePassword) {
        togglePassword.addEventListener("click", function() {
            const passwordField = document.getElementById("password");
            const icon = this.querySelector("i");
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.className = "fas fa-eye-slash";
            } else {
                passwordField.type = "password";
                icon.className = "fas fa-eye";
            }
        });
    }

    // Confirm password toggle
    const togglePasswordConfirmation = document.getElementById("togglePasswordConfirmation");
    if (togglePasswordConfirmation) {
        togglePasswordConfirmation.addEventListener("click", function() {
            const passwordField = document.getElementById("password_confirmation");
            const icon = this.querySelector("i");
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.className = "fas fa-eye-slash";
            } else {
                passwordField.type = "password";
                icon.className = "fas fa-eye";
            }
        });
    }
});

// Form submission with AJAX
document.getElementById("createUserForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.querySelector('button[form="createUserForm"]');
    const originalText = submitBtn ? submitBtn.innerHTML : '';
    
    if (submitBtn) {
        submitBtn.innerHTML = "<i class=\"fas fa-hourglass-split me-1\" tabindex=\"-1\"></i>Creating...";
        submitBtn.disabled = true;
    }
    
    fetch("<?php echo APP_URL; ?>/users", {
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
            const alertDiv = document.createElement("div");
            alertDiv.className = "alert alert-success alert-dismissible fade show";
            alertDiv.innerHTML = `
                ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            const form = document.getElementById("createUserForm");
            form.parentElement.insertBefore(alertDiv, form);
            
            // Reset form
            this.reset();
            
            // Redirect after 2 seconds
            setTimeout(() => {
                window.location.href = "<?php echo APP_URL; ?>/users";
            }, 2000);
        } else {
            // Show error message
            const alertDiv = document.createElement("div");
            alertDiv.className = "alert alert-danger alert-dismissible fade show";
            alertDiv.innerHTML = `
                ${data.error || "An error occurred"}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            const form = document.getElementById("createUserForm");
            form.parentElement.insertBefore(alertDiv, form);
        }
    })
    .catch(error => {
        const alertDiv = document.createElement("div");
        alertDiv.className = "alert alert-danger alert-dismissible fade show";
        alertDiv.innerHTML = `
            An error occurred while creating the user
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        const form = document.getElementById("createUserForm");
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
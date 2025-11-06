<div class="row">
    <div class="col-12">
        <div class="form-container">
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit User</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/users" class="text-decoration-none">Users</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="form-body">
                <form method="POST" action="<?php echo APP_URL; ?>/users/<?php echo $user['id']; ?>" id="editUserForm" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                <label for="username">Nama User <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="namalengkap" name="namalengkap" placeholder="Nama Lengkap" value="<?php echo htmlspecialchars($user['namalengkap']); ?>" required>
                                <label for="namalengkap">Nama Lengkap <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                <label for="email">Alamaat Email <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="new-password">
                                <label for="password">Password (abaikan untuk tetap menggunakan password)</label>
                                <button class="btn btn-outline-secondary position-absolute top-0 end-0 h-100 d-flex align-items-center justify-content-center password-toggle-btn" type="button" id="togglePassword" class="password-toggle" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" autocomplete="new-password">
                                <label for="password_confirmation">Konfirmasi Password</label>
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
                                    <option value="">Pilih Role</option>
                                    <option value="admin"<?php echo $user['role'] === 'admin' ? ' selected' : ''; ?>>Administrator</option>
                                    <option value="manajemen"<?php echo $user['role'] === 'manajemen' ? ' selected' : ''; ?>>Manajemen</option>
                                    <option value="user"<?php echo $user['role'] === 'user' ? ' selected' : ''; ?>>User</option>
                                    <option value="marketing"<?php echo $user['role'] === 'marketing' ? ' selected' : ''; ?>>Marketing</option>
                                    <option value="customer"<?php echo $user['role'] === 'customer' ? ' selected' : ''; ?>>Customer</option>
                                </select>
                                <label for="role">Role <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="status" name="status">
                                    <option value="aktif"<?php echo $user['status'] === 'aktif' ? ' selected' : ''; ?>>Aktif</option>
                                    <option value="non_aktif"<?php echo $user['status'] === 'non_aktif' ? ' selected' : ''; ?>>Non Aktif</option>
                                    <option value="register"<?php echo $user['status'] === 'register' ? ' selected' : ''; ?>>Register</option>
                                </select>
                                <label for="status">Status</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Profile Picture</label>
                                <div class="row">
                                    <!-- File Input Section -->
                                    <div class="col-md-6">
                                        <div class="file-upload-container">
                                            <input type="file" class="form-control" id="picture" name="picture" accept="image/*" onchange="handleFileSelect(this)">
                                            <div class="form-text mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                File yang didukung: JPG, PNG, GIF, WEBP (Maksimal 5MB)
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Preview Section -->
                                    <div class="col-md-6">
                                        <div id="file-preview" class="file-upload-preview d-none">
                                            <div class="d-flex align-items-center">
                                                <div class="preview-container me-3">
                                                    <img id="preview-image" class="preview-image rounded-circle object-fit-cover" width="80" height="80" alt="Preview">
                                                    <div class="preview-overlay">
                                                        <button type="button" class="btn btn-sm btn-danger remove-preview" onclick="removePreview()" title="Remove">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-primary change-preview" onclick="document.getElementById('picture').click()" title="Change">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="preview-info flex-grow-1">
                                                    <div id="preview-filename" class="fw-bold text-truncate" title=""></div>
                                                    <div id="preview-size" class="text-muted small"></div>
                                                    <div class="mt-2">
                                                        <button type="button" class="btn btn-sm btn-outline-danger me-2" onclick="removePreview()">
                                                            <i class="fas fa-trash me-1"></i>Remove
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('picture').click()">
                                                            <i class="fas fa-edit me-1"></i>Change
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Default state when no file selected -->
                                        <div id="no-file-preview" class="text-center text-muted py-3">
                                            <?php 
                                            
                                            $imageUrl = '';
                                            if (strpos($user['picture'], 'assets/images/users/') === 0) {
                                                $imageUrl = APP_URL . '/' . $user['picture'];
                                            } else {
                                                $imageUrl = APP_URL . '/assets/images/users/' . $user['picture'];
                                            }
                                            
                                            // Test: Always show photo if picture field exists
                                            if (isset($user['picture']) && $user['picture'] !== ''):
                                            ?>
                                                <!-- Show existing user photo -->
                                                <div class="preview-container">
                                                    <img src="<?php 
                                                        // Handle different path formats
                                                        $picturePath = $user['picture'];
                                                        if (strpos($picturePath, 'assets/images/users/') === 0) {
                                                            // Path already includes assets/images/users/
                                                            echo APP_URL . '/' . htmlspecialchars($picturePath);
                                                        } elseif (strpos($picturePath, 'user_') === 0) {
                                                            // Just filename, add full path
                                                            echo APP_URL . '/assets/images/users/' . htmlspecialchars($picturePath);
                                                        } else {
                                                            // Default: add full path
                                                            echo APP_URL . '/assets/images/users/' . htmlspecialchars($picturePath);
                                                        }
                                                    ?>" 
                                                         class="preview-image rounded-circle object-fit-cover" 
                                                         width="120" height="120" alt="Current Profile Picture"
                                                         onerror="this.onerror=null; this.src='<?php echo APP_URL; ?>/assets/images/users/avatar.svg';">
                                                    <div class="preview-overlay">
                                                        <button type="button" class="btn btn-sm btn-danger remove-preview" onclick="removePreview()" title="Remove">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-primary change-preview" onclick="document.getElementById('picture').click()" title="Change">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <!-- No existing photo -->
                                                <i class="fas fa-image fa-3x opacity-50"></i>
                                                <div class="small m-3">Belum ada foto</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Form Footer -->
            <div class="form-footer d-flex justify-content-between align-items-center">
                <a href="<?php echo APP_URL; ?>/users" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i>Cancel
                </a>
                <button type="submit" form="editUserForm" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Simpan
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
            window.Notify.warning("Please select a valid image file (JPG, PNG, GIF, WEBP)");
            input.value = "";
            return;
        }
        
        // Validate file size (5MB max)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            window.Notify.warning("File size must be less than 5MB");
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
        const noFilePreview = document.getElementById("no-file-preview");
        const previewImage = document.getElementById("preview-image");
        const previewFilename = document.getElementById("preview-filename");
        const previewSize = document.getElementById("preview-size");
        
        if (previewImage) {
            previewImage.src = e.target.result;
        }
        if (previewFilename) {
            previewFilename.textContent = file.name;
            previewFilename.setAttribute("title", file.name);
        }
        if (previewSize) previewSize.textContent = formatFileSize(file.size);
        
        if (preview) {
            preview.classList.remove("d-none");
        }
        if (noFilePreview) {
            noFilePreview.classList.add("d-none");
        }
    };
    reader.readAsDataURL(file);
}

function removePreview() {
    const pictureInput = document.getElementById("picture");
    if (pictureInput) pictureInput.value = "";
    
    const preview = document.getElementById("file-preview");
    const noFilePreview = document.getElementById("no-file-preview");
    
    if (preview) preview.classList.add("d-none");
    if (noFilePreview) {
        noFilePreview.classList.remove("d-none");
        // Show placeholder when removing existing photo
        noFilePreview.innerHTML = `
            <i class="fas fa-image fa-3x mb-2 opacity-50"></i>
            <div class="small">No image selected</div>
        `;
    }
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
document.getElementById("editUserForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.querySelector('button[form="editUserForm"]');
    const originalText = submitBtn ? submitBtn.innerHTML : '';
    
    if (submitBtn) {
        submitBtn.innerHTML = "<i class=\"fas fa-hourglass-split me-1\" tabindex=\"-1\"></i>Updating...";
        submitBtn.disabled = true;
    }
    
    fetch("<?php echo APP_URL; ?>/users/<?php echo $user['id']; ?>", {
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
            // Show success notification
            window.Notify.success(data.message || 'User updated successfully');
            
            // Redirect after delay to allow notification to be visible
            window.delayedRedirect("<?php echo APP_URL; ?>/users");
        } else {
            // Show error notification
            window.Notify.error(data.error || "An error occurred while updating the user");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.Notify.error('An error occurred while updating the user');
    })
    .finally(() => {
        if (submitBtn) {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });
});
</script>
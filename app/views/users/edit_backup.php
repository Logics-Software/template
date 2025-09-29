<?php
$content = '
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit User</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="' . APP_URL . '" class="text-white-50">Home</a></li>
                                <li class="breadcrumb-item"><a href="' . APP_URL . '/users" class="text-white-50">Users</a></li>
                                <li class="breadcrumb-item active text-white" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="card-body">
                    <form id="editUserForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" value="' . htmlspecialchars($user['name']) . '" required>
                                    <label for="name">Full Name <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="' . htmlspecialchars($user['email']) . '" required>
                                    <label for="email">Email Address <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="New Password" autocomplete="new-password">
                                    <label for="password">New Password</label>
                                    <button class="btn btn-outline-secondary position-absolute top-0 end-0 h-100 d-flex align-items-center justify-content-center" type="button" id="togglePassword" style="z-index: 10; border: none; background: transparent;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="form-text">Leave blank to keep current password</div>
                                    <div class="invalid-feedback" id="password-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" autocomplete="new-password">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <button class="btn btn-outline-secondary position-absolute top-0 end-0 h-100 d-flex align-items-center justify-content-center" type="button" id="togglePasswordConfirmation" style="z-index: 10; border: none; background: transparent;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="invalid-feedback" id="password_confirmation-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="admin"' . ($user['role'] === 'admin' ? ' selected' : '') . '>Admin</option>
                                        <option value="user"' . ($user['role'] === 'user' ? ' selected' : '') . '>User</option>
                                    </select>
                                    <label for="role">Role <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active"' . ($user['status'] === 'active' ? ' selected' : '') . '>Active</option>
                                        <option value="inactive"' . ($user['status'] === 'inactive' ? ' selected' : '') . '>Inactive</option>
                                    </select>
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Profile Picture</label>
                            <div class="file-upload-container">
                                <input type="file" class="form-control" id="picture" name="picture" accept="image/*" onchange="handleFileSelect(this)">
                                <div class="form-text mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Upload a new profile picture (optional) - Supports: JPG, PNG, GIF, WEBP (Max 5MB)
                                </div>
                            </div>
                            <div id="file-preview" class="file-upload-preview d-none">
                                <div class="preview-container">
                                    <img id="preview-image" class="preview-image" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 0.5rem;">
                                    <div class="preview-overlay">
                                        <button type="button" class="btn btn-danger remove-preview" onclick="removePreview()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary change-preview" onclick="document.getElementById(\'picture\').click()">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="preview-info">
                                    <div id="preview-filename" class="fw-bold"></div>
                                    <div id="preview-size" class="text-muted small"></div>
                                </div>
                            </div>';
if (!empty($user['picture'])) {
    $content .= '
                            <div class="mt-3">
                                <label class="form-label fw-bold">Current Picture:</label>
                                <div class="mt-2">
                                    <img src="' . APP_URL . '/' . htmlspecialchars($user['picture']) . '" alt="Current Profile Picture" class="rounded-circle profile-img-lg">
                                </div>
                            </div>';
}
$content .= '
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="' . APP_URL . '/users" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to Users
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
';

$content .= '<script>
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

// File upload handling
function handleFileSelect(input) {
    const file = input.files[0];
    if (file) {
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid image file (JPG, PNG, GIF, WEBP)');
            input.value = '';
            return;
        }
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            alert('File size must be less than 5MB');
            input.value = '';
            return;
        }
        showPreview(file);
    }
}

function showPreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('file-preview');
        const previewImage = document.getElementById('preview-image');
        const previewFilename = document.getElementById('preview-filename');
        const previewSize = document.getElementById('preview-size');
        
        if (previewImage) previewImage.src = e.target.result;
        if (previewFilename) previewFilename.textContent = file.name;
        if (previewSize) previewSize.textContent = formatFileSize(file.size);
        
        if (preview) preview.classList.remove('d-none');
    };
    reader.readAsDataURL(file);
}

function removePreview() {
    const pictureInput = document.getElementById('picture');
    if (pictureInput) pictureInput.value = '';
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

// Make functions globally available
window.removePreview = removePreview;
window.handleFileSelect = handleFileSelect;
</script>
';

echo $content;
?>

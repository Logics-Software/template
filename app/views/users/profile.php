<?php
// Flash messages are now handled globally in app.php layout
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">My Account</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="card-body">

                <form method="POST" action="<?php echo APP_URL; ?>/profile" id="profileForm" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="namalengkap" name="namalengkap" 
                                       placeholder="Enter your full name" value="<?php echo htmlspecialchars($user['namalengkap'] ?? ''); ?>" required>
                                <label for="namalengkap">Full Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="Enter your email address" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                                <label for="email">Email Address <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Profile Picture</label>
                                <div class="profile-picture-container">
                                    <!-- Current Picture Display -->
                                    <div class="current-picture mb-3">
                                        <div class="d-flex align-items-center">
                                            <?php if (isset($user['picture']) && !empty($user['picture'])): ?>
                                                <!-- Show actual picture if exists -->
                                                <img src="<?php echo (strpos($user['picture'], 'assets/images/users/') === 0 ? APP_URL . '/' . $user['picture'] : APP_URL . '/assets/images/users/' . $user['picture']); ?>" 
                                                     alt="Current Profile Picture" 
                                                     class="rounded-circle me-3" 
                                                     width="60" height="60"
                                                     class="object-cover"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <?php else: ?>
                                                <!-- Avatar fallback (always present, shown when no picture or image fails to load) -->
                                                <div class="avatar-fallback bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                    style="width: 60px; height: 60px; <?php echo (isset($user['picture']) && !empty($user['picture'])) ? 'display: none;' : ''; ?>">
                                                    <?php if (isset($user['namalengkap']) && !empty($user['namalengkap'])): ?>
                                                        <?php echo strtoupper(substr($user['namalengkap'], 0, 1)); ?>
                                                    <?php else: ?>
                                                        <i class="fas fa-user" class="text-2xl"></i>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                            <div>
                                                <div class="fw-bold">Current Profile Picture</div>
                                                <small class="text-muted">Click "Choose File" to update</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- File Input -->
                                    <div class="file-input-container">
                                        <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
                                        <div class="form-text mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Upload a new profile picture (JPG, PNG, GIF, WEBP - Max 2MB)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Form Footer -->
            <div class="card-footer d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-secondary" onclick="goBack()">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="submit" form="profileForm" class="btn btn-primary">
                    <span class="btn-text">
                        <i class="fas fa-save me-1"></i>Update Profile
                    </span>
                    <div class="btn-loader d-none">
                        <i class="fas fa-spinner fa-spin me-1"></i>Updating...
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const profileForm = document.getElementById("profileForm");
    const submitBtn = profileForm.querySelector("button[type='submit']");
    const btnText = submitBtn?.querySelector(".btn-text");
    const btnLoader = submitBtn?.querySelector(".btn-loader");

    // Form submission with loading state
    if (profileForm) {
        profileForm.addEventListener("submit", function(e) {
            // Show loading state
            if (btnText) btnText.style.display = "none";
            if (btnLoader) btnLoader.style.display = "block";
            if (submitBtn) submitBtn.disabled = true;
        });
    }

    // File input change handler
    const fileInput = document.getElementById("picture");
    if (fileInput) {
        fileInput.addEventListener("change", function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp"];
                if (!allowedTypes.includes(file.type)) {
                    AlertManager.warning("Please select a valid image file (JPG, PNG, GIF, WEBP)");
                    this.value = "";
                    return;
                }
                
                // Validate file size (2MB max)
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    AlertManager.warning("File size must be less than 2MB");
                    this.value = "";
                    return;
                }
                
                // Show preview
                showImagePreview(file);
            }
        });
    }
});

// Function to show image preview
function showImagePreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        // Create or update preview
        let preview = document.querySelector('.image-preview');
        if (!preview) {
            preview = document.createElement('div');
            preview.className = 'image-preview mt-3';
            document.querySelector('.file-input-container').appendChild(preview);
        }
        
        preview.innerHTML = `
            <div class="d-flex align-items-center">
                <img src="${e.target.result}" alt="Preview" class="rounded-circle me-3" width="60" height="60" class="object-cover">
                <div>
                    <div class="fw-bold">New Profile Picture Preview</div>
                    <small class="text-muted">${file.name} (${formatFileSize(file.size)})</small>
                </div>
            </div>
        `;
    };
    reader.readAsDataURL(file);
}

// Function to format file size
function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
}

// Function to go back to previous page
function goBack() {
    // Check if there's a previous page in history
    if (window.history.length > 1) {
        window.history.back();
    } else {
        // Fallback to dashboard if no history
        window.location.href = "<?php echo APP_URL; ?>/dashboard";
    }
}
</script>
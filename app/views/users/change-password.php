<?php
// Flash messages are now handled globally in app.php layout
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Change Password</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo APP_URL; ?>/dashboard" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="card-body">

                <form method="POST" action="<?php echo APP_URL; ?>/change-password" id="changePasswordForm">
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="current_password" name="current_password" 
                                       placeholder="" required>
                                <label for="current_password">Password Lama <span class="text-danger">*</span></label>
                                <button class="btn btn-outline-secondary position-absolute top-0 end-0 h-100 d-flex align-items-center justify-content-center password-toggle-btn" type="button" id="toggleCurrentPassword" class="password-toggle" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="new_password" name="new_password" 
                                       placeholder="" required>
                                <label for="new_password">Password Baru <span class="text-danger">*</span></label>
                                <button class="btn btn-outline-secondary position-absolute top-0 end-0 h-100 d-flex align-items-center justify-content-center password-toggle-btn" style="top: -10px !important;" type="button" id="toggleNewPassword" class="password-toggle" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="form-text">Password minimal terdiri dari 6 karakter</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       placeholder="" required>
                                <label for="confirm_password">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                <button class="btn btn-outline-secondary position-absolute top-0 end-0 h-100 d-flex align-items-center justify-content-center password-toggle-btn" type="button" id="toggleConfirmPassword" class="password-toggle" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="form-text text-muted" id="password-match-message"></div>
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
                <button type="submit" form="changePasswordForm" class="btn btn-primary">
                    <span class="btn-text">
                        <i class="fas fa-key me-1"></i>Update Password
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
    const changePasswordForm = document.getElementById("changePasswordForm");
    const submitBtn = changePasswordForm.querySelector("button[type='submit']");
    const btnText = submitBtn?.querySelector(".btn-text");
    const btnLoader = submitBtn?.querySelector(".btn-loader");
    const newPasswordField = document.getElementById("new_password");
    const confirmPasswordField = document.getElementById("confirm_password");
    const passwordMatchMessage = document.getElementById("password-match-message");

    // Form submission with loading state
    if (changePasswordForm && submitBtn) {
        changePasswordForm.addEventListener("submit", function() {
            if (btnText && btnLoader) {
                btnText.style.display = "none";
                btnLoader.style.display = "block";
            }
            submitBtn.disabled = true;
        });
    }

    // Password toggle functionality
    const toggleCurrentPassword = document.getElementById("toggleCurrentPassword");
    if (toggleCurrentPassword) {
        toggleCurrentPassword.addEventListener("click", function() {
            const passwordField = document.getElementById("current_password");
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

    const toggleNewPassword = document.getElementById("toggleNewPassword");
    if (toggleNewPassword) {
        toggleNewPassword.addEventListener("click", function() {
            const passwordField = document.getElementById("new_password");
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

    const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener("click", function() {
            const passwordField = document.getElementById("confirm_password");
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

    // Real-time password confirmation validation
    function validatePasswordMatch() {
        if (confirmPasswordField.value && newPasswordField.value) {
            if (newPasswordField.value === confirmPasswordField.value) {
                confirmPasswordField.setCustomValidity("");
                passwordMatchMessage.textContent = "Passwords match";
                passwordMatchMessage.className = "form-text text-success";
            } else {
                confirmPasswordField.setCustomValidity("Passwords do not match");
                passwordMatchMessage.textContent = "Passwords do not match";
                passwordMatchMessage.className = "form-text text-danger";
            }
        } else {
            confirmPasswordField.setCustomValidity("");
            passwordMatchMessage.textContent = "";
            passwordMatchMessage.className = "form-text text-muted";
        }
    }

    if (newPasswordField && confirmPasswordField) {
        newPasswordField.addEventListener("input", validatePasswordMatch);
        confirmPasswordField.addEventListener("input", validatePasswordMatch);
    }
});

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
<?php
// Get flash messages once to avoid multiple calls
$errorMessage = Session::getFlash('error');
$successMessage = Session::getFlash('success');
$validationErrors = Session::getFlash('errors');
?>

<div class="profile-wrapper">
    <div class="profile-form-section">
        <div class="profile-form-container">
            <div class="profile-header">
                <h1 class="profile-title">Change Password</h1>
                <p class="profile-subtitle">Update your account password</p>
            </div>

            <?php if ($errorMessage) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <li><?php echo htmlspecialchars($errorMessage); ?></li>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if ($successMessage) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <li><?php echo htmlspecialchars($successMessage); ?></li>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if ($validationErrors) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php foreach ($validationErrors as $error) : ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo APP_URL; ?>/change-password" id="changePasswordForm" class="profile-form">
                <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                
                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control" id="current_password" name="current_password" 
                                placeholder="Enter your current password" required>
                        <button type="button" class="btn-toggle-password" onclick="togglePassword('current_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
                        </span>
                        <input type="password" class="form-control" id="new_password" name="new_password" 
                                placeholder="Enter your new password" required>
                        <button type="button" class="btn-toggle-password" onclick="togglePassword('new_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small class="form-text text-muted">Password must be at least 6 characters long</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
                        </span>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                placeholder="Confirm your new password" required>
                        <button type="button" class="btn-toggle-password" onclick="togglePassword('confirm_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small class="form-text text-muted" id="password-match-message"></small>
                </div>

                <div class="profile-actions">
                    <button type="button" class="btn btn-secondary" onclick="goBack()">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-profile">
                        <span class="btn-text">Update Password</span>
                        <div class="btn-loader" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const changePasswordForm = document.getElementById("changePasswordForm");
    const submitBtn = changePasswordForm.querySelector(".btn-profile");
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
                btnLoader.style.display = "inline-block";
            }
            submitBtn.disabled = true;
        });
    }

    // Set tabindex for input group elements to prevent focus on icons
    const inputGroups = changePasswordForm.querySelectorAll('.input-group');
    inputGroups.forEach(function(group) {
        const inputGroupText = group.querySelector('.input-group-text');
        const toggleButton = group.querySelector('.btn-toggle-password');
        const formControl = group.querySelector('.form-control');
        
        // Set tabindex for input group text (icons)
        if (inputGroupText) {
            inputGroupText.setAttribute('tabindex', '-1');
        }
        
        // Set tabindex for toggle button
        if (toggleButton) {
            toggleButton.setAttribute('tabindex', '-1');
        }
        
        // Ensure form control is focusable
        if (formControl) {
            formControl.setAttribute('tabindex', '0');
        }
    });

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

// Function to toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Function to go back to previous page
function goBack() {
    // Check if there's a previous page in history
    if (window.history.length > 1) {
        window.history.back();
    } else {
        // Fallback to dashboard if no history
        window.location.href = '<?php echo APP_URL; ?>/dashboard';
    }
}
</script>

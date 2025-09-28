<?php
// Get flash messages once to avoid multiple calls
$errorMessage = Session::getFlash('error');
$successMessage = Session::getFlash('success');

$content = '
<!-- Lock Screen Modal Overlay -->
<div class="lock-screen-overlay" id="lockScreenOverlay">
    <div class="lock-screen-modal">
        <div class="lock-screen-content">
            <!-- Lock Screen Header -->
            <div class="lock-header">
                <div class="user-avatar">
                    <img src="' . ($user_picture ?? APP_URL . '/assets/images/users/user-1.jpg') . '" alt="User Avatar" class="avatar-img">
                </div>
                <h1 class="lock-title">Hello ' . ($user_name ?? 'User') . '!</h1>
                <p class="lock-subtitle">' . ($user_email ?? 'user@example.com') . '</p>
            </div>

            <!-- Error Messages -->
            ' . ($errorMessage ? '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>' . $errorMessage . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            ' : '') . '
            
            <!-- Success Messages -->
            ' . ($successMessage ? '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>' . $successMessage . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            ' : '') . '

            <!-- Lock Screen Form -->
            <form method="POST" action="' . APP_URL . '/unlock" id="unlockForm" class="lock-form">
                <input type="hidden" name="_token" value="' . $csrf_token . '">
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Enter your password" required>
                        <button class="btn btn-toggle-password" type="button" id="togglePassword">
                            <i class="fas fa-eye" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember_me">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    <a href="' . APP_URL . '/logout" class="forgot-password-link">Login again</a>
                </div>

                <button type="submit" class="btn btn-unlock">
                    <span class="btn-text">Unlock</span>
                    <div class="btn-loader" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            </form>


            <div class="lock-footer">
                <p class="lock-help">
                    Try unlock with <span class="highlight">Finger print</span> / <span class="highlight">Face Id</span>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Password toggle functionality
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");
    const passwordToggleIcon = document.getElementById("passwordToggleIcon");

    if (togglePassword && passwordInput && passwordToggleIcon) {
        togglePassword.addEventListener("click", function() {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            
            // Toggle icon
            if (type === "text") {
                passwordToggleIcon.classList.remove("fa-eye");
                passwordToggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordToggleIcon.classList.remove("fa-eye-slash");
                passwordToggleIcon.classList.add("fa-eye");
            }
        });
    }

    // Form submission with loading state
    const unlockForm = document.getElementById("unlockForm");
    if (unlockForm) {
        const submitBtn = unlockForm.querySelector(".btn-login");
        const btnText = submitBtn?.querySelector(".btn-text");
        const btnLoader = submitBtn?.querySelector(".btn-loader");

        unlockForm.addEventListener("submit", function(e) {
            // Show loading state
            if (btnText) btnText.style.display = "none";
            if (btnLoader) btnLoader.style.display = "block";
            if (submitBtn) submitBtn.disabled = true;
        });
    }

    // Auto focus on password input
    const passwordField = document.getElementById("password");
    if (passwordField) {
        passwordField.focus();
    }
});
</script>
';
?>
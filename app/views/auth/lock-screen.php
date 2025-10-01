<?php
// Get flash messages once to avoid multiple calls
$errorMessage = Session::getFlash('error');
$successMessage = Session::getFlash('success');

$content = '
<!-- Lock Screen Modal Overlay -->
<div class="lock-screen-overlay" id="lockScreenOverlay">
    <div class="lock-screen-modal">
        <div class="card">
            <div class="card-header text-center">
                <div class="mb-3">
                    <img src="' . ($user_picture ?? APP_URL . '/assets/images/users/avatar.svg') . '" alt="User Avatar" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 4px solid #f8f9fa; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                </div>
                <h4 class="mb-1">Hello ' . ($user_name ?? 'User') . '!</h4>
                <p class="text-muted mb-0">' . ($user_email ?? 'user@example.com') . '</p>
            </div>
            <div class="card-body">
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
            <form method="POST" action="' . APP_URL . '/unlock" id="unlockForm">
                <input type="hidden" name="_token" value="' . $csrf_token . '">
                
                <div class="form-floating mb-3 position-relative">
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="" required style="padding-right: 2.5rem;">
                    <label for="password">
                        <i class="fas fa-lock me-2"></i>Password
                    </label>
                    <button class="position-absolute top-50 end-0 translate-middle-y" 
                            type="button" id="togglePassword" 
                            style="border: none; background: transparent; z-index: 10; padding: 0; width: 2.5rem; height: calc(3.5rem + 2px); color: #6c757d; margin-right: 0;">
                        <i class="fas fa-eye" id="passwordToggleIcon"></i>
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember_me">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    <a href="' . APP_URL . '/logout" class="text-primary text-decoration-none">Login again</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3" id="unlockBtn">
                    <i class="fas fa-unlock me-2"></i>Unlock
                </button>

                <div class="text-center">
                    <p class="text-muted small mb-0">
                        Try unlock with <span class="text-primary fw-bold">Finger print</span> / <span class="text-primary fw-bold">Face Id</span>
                    </p>
                </div>
            </form>
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
    const unlockBtn = document.getElementById("unlockBtn");
    
    if (unlockForm && unlockBtn) {
        unlockForm.addEventListener("submit", function(e) {
            // Show loading state
            unlockBtn.disabled = true;
            unlockBtn.innerHTML = \'<i class="fas fa-spinner fa-spin me-2"></i>Unlocking...\';
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
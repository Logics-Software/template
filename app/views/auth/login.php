<?php
// Get flash messages once to avoid multiple calls
$errorMessage = Session::getFlash('error');
$successMessage = Session::getFlash('success');
$validationErrors = Session::getFlash('errors');

$content = '
<!-- Login Page with Centered Design -->
<div class="login-container">
    <div class="login-wrapper-single">
        <!-- Login Form -->
        <div class="login-form-section">
            <div class="login-form-container">
                <div class="login-header">
                    <h1 class="login-title">Welcome back!</h1>
                    <p class="login-subtitle">Please sign in to continue</p>
                </div>

                <!-- Error Messages -->
                ' . ($errorMessage ? '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>' . $errorMessage . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                ' : '<!-- No error message -->') . '
                
                <!-- Success Messages -->
                ' . ($successMessage ? '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>' . $successMessage . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                ' : '') . '
                
                <!-- Validation Errors -->
                ' . ($validationErrors ? '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:
                    <ul class="mb-0 mt-2">
                        ' . implode("", array_map(function($field, $errors) {
                            return "<li>" . implode(", ", $errors) . "</li>";
                        }, array_keys($validationErrors), $validationErrors)) . '
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                ' : '') . '

                <form method="POST" action="' . APP_URL . '/login" id="loginForm" class="login-form">
                    <input type="hidden" name="_token" value="' . $csrf_token . '">
                    
                    <div class="form-group">
                        <label for="username_email" class="form-label">Username or Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="username_email" name="username_email" 
                                   placeholder="Enter your username or email" required>
                        </div>
                    </div>

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
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        <a href="#" class="forgot-password-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <span class="btn-text">Log In</span>
                        <div class="btn-loader" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </form>

                <div class="signup-link">
                    <p>Don\'t have an account? <a href="' . APP_URL . '/register">Sign up</a></p>
                </div>
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
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        const submitBtn = loginForm.querySelector(".btn-login");
        const btnText = submitBtn?.querySelector(".btn-text");
        const btnLoader = submitBtn?.querySelector(".btn-loader");

        loginForm.addEventListener("submit", function(e) {
            // Show loading state
            if (btnText) btnText.style.display = "none";
            if (btnLoader) btnLoader.style.display = "block";
            if (submitBtn) submitBtn.disabled = true;
        });
    }
});
</script>
';
?>

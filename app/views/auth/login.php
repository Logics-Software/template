<?php
// Get flash messages once to avoid multiple calls
$errorMessage = Session::getFlash('error');
$successMessage = Session::getFlash('success');
$validationErrors = Session::getFlash('errors');
?>

<!-- Login Page with Centered Design -->
<div class="login-container">
    <div class="login-wrapper-single">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-1">Selamat Datang!</h4>
                <p class="text-muted mb-0">Silahkan login untuk melanjutkan</p>
            </div>
            <div class="card-body m-3">
                <!-- Error Messages -->
                <?php if ($errorMessage): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($errorMessage); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- Success Messages -->
                <?php if ($successMessage): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($successMessage); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- Validation Errors -->
                <?php if ($validationErrors): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>Harap diperbaiki kesalahan tersebut:
                    <ul class="mb-0 mt-2">
                        <?php foreach ($validationErrors as $field => $errors): ?>
                            <li><?php echo implode(", ", $errors); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo APP_URL; ?>/login" id="loginForm">
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username_email" name="username_email" 
                               placeholder="" required>
                        <label for="username_email">
                            <i class="fas fa-user me-2"></i>Username atau Email
                        </label>
                    </div>

                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="" required class="pr-10">
                        <label for="password">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <button class="position-absolute top-50 end-0 translate-middle-y password-toggle-btn" 
                                type="button" id="togglePassword" 
                                class="password-toggle" tabindex="-1">
                            <i class="fas fa-eye" id="passwordToggleIcon"></i>
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3" id="loginBtn">
                        <i class="fas fa-sign-in-alt me-2"></i>Log In
                    </button>

                    <div class="text-center">
                        <span class="text-muted">Belum punya akun?</span>
                        <a href="<?php echo APP_URL; ?>/register" class="text-primary text-decoration-none fw-bold ms-1">Daftar</a>
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
    const loginForm = document.getElementById("loginForm");
    const loginBtn = document.getElementById("loginBtn");
    
    if (loginForm && loginBtn) {
        loginForm.addEventListener("submit", function(e) {
            // Show loading state
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Logging in...';
        });
    }
});
</script>

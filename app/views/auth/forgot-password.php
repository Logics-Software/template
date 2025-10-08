<?php
// Get flash messages once to avoid multiple calls
$errorMessage = Session::getFlash('error');
$successMessage = Session::getFlash('success');
$validationErrors = Session::getFlash('errors');
?>

<!-- Forgot Password Page with Centered Design -->
<div class="login-container">
    <div class="login-wrapper-single">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-1">Lupa Password?</h4>
                <p class="text-muted mb-0">Masukkan email Anda untuk reset password</p>
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

                <form method="POST" action="<?php echo APP_URL; ?>/forgot-password" id="forgotPasswordForm">
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="" required>
                        <label for="email">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <div class="invalid-feedback" id="emailError">
                            Email harus diisi dengan format yang benar
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3" id="resetBtn">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Reset Password
                    </button>

                    <div class="text-center">
                        <span class="text-muted">Ingat password Anda?</span>
                        <a href="<?php echo APP_URL; ?>/login" class="text-primary text-decoration-none fw-bold ms-1">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Auto focus on email input
    const emailInput = document.getElementById("email");
    if (emailInput) {
        emailInput.focus();
    }

    // Form submission with loading state and validation
    const forgotPasswordForm = document.getElementById("forgotPasswordForm");
    const resetBtn = document.getElementById("resetBtn");
    const emailError = document.getElementById("emailError");
    
    if (forgotPasswordForm && resetBtn) {
        forgotPasswordForm.addEventListener("submit", function(e) {
            // Reset previous validation states
            emailInput.classList.remove('is-invalid');
            emailError.style.display = 'none';
            
            // Get email value
            const email = emailInput.value.trim();
            
            // Client-side validation
            if (!email) {
                e.preventDefault();
                emailInput.classList.add('is-invalid');
                emailError.textContent = 'Email harus diisi';
                emailError.style.display = 'block';
                emailInput.focus();
                return false;
            }
            
            // Email format validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                emailInput.classList.add('is-invalid');
                emailError.textContent = 'Format email tidak valid';
                emailError.style.display = 'block';
                emailInput.focus();
                return false;
            }
            
            // Show loading state
            resetBtn.disabled = true;
            resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
        });
    }
    
    // Real-time validation on input
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
                emailError.style.display = 'none';
            }
        });
    }
});
</script>

<?php
// Get flash messages once to avoid multiple calls
$errorMessage = Session::getFlash('error');
$successMessage = Session::getFlash('success');
$validationErrors = Session::getFlash('errors');
?>

<!-- Register Page with Modern Card Structure -->
<div class="register-container">
    <div class="register-wrapper">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-1">Pendaftaran Akun</h4>
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

                <form method="POST" action="<?php echo APP_URL; ?>/register" id="registerForm">
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                    
                    <!-- Row 1: Username & Full Name -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="" required>
                                <label for="username">
                                    <i class="fas fa-user me-2"></i>Username
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="namalengkap" name="namalengkap" 
                                       placeholder="" required>
                                <label for="namalengkap">
                                    <i class="fas fa-id-card me-2"></i>Nama Lengkap
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Email & Role Selection -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="" required>
                                <label for="email">
                                    <i class="fas fa-envelope me-2"></i>Alamat Email
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Pilih role anda</option>
                                    <option value="user">User</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="customer">Customer</option>
                                </select>
                                <label for="role">
                                    <i class="fas fa-user-tag me-2"></i>Role
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Reason -->
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="registration_reason" name="registration_reason" 
                                  placeholder="" class="textarea-60" required></textarea>
                        <label for="registration_reason">
                            <i class="fas fa-comment me-2"></i>Alasan Pendaftaran
                        </label>
                    </div>

                    <!-- Row 3: Password & Confirm Password -->
                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       placeholder="" required class="pr-10">
                                <label for="confirm_password">
                                    <i class="fas fa-lock me-2"></i>Konfirmasi Password
                                </label>
                                <button class="position-absolute top-50 end-0 translate-middle-y password-toggle-btn" 
                                        type="button" id="toggleConfirmPassword" 
                                        class="password-toggle" tabindex="-1">
                                    <i class="fas fa-eye" id="confirmPasswordToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            Saya setuju dengan <a href="#" class="text-primary">Syarta & Ketentuan</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3" id="registerBtn">
                        <i class="fas fa-user-plus me-2"></i>Daftar Akun Saya
                    </button>
                </form>

                <div class="text-center">
                    <span class="text-muted">Sudah punya akun?</span>
                    <a href="<?php echo APP_URL; ?>/login" class="text-primary text-decoration-none fw-bold ms-1">Login</a>
                </div>

                <!-- Registration Notice -->
                <div class="alert alert-info mt-3 text-secondary" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Pemberitahuan Pendaftaran :</strong> Akun Anda akan ditinjau oleh administrator sebelum akun Anda diaktifkan untuk digunakan. 
                    Anda akan mendapatkan pemberitahuan melalui email setelah akun Anda disetujui.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const registerForm = document.getElementById("registerForm");
    const registerBtn = document.getElementById("registerBtn");

    // Password toggle functionality
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");
    const passwordIcon = document.getElementById("passwordToggleIcon");

    const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
    const confirmPasswordInput = document.getElementById("confirm_password");
    const confirmPasswordIcon = document.getElementById("confirmPasswordToggleIcon");

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function() {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            passwordIcon.classList.toggle("fa-eye");
            passwordIcon.classList.toggle("fa-eye-slash");
        });
    }

    if (toggleConfirmPassword && confirmPasswordInput) {
        toggleConfirmPassword.addEventListener("click", function() {
            const type = confirmPasswordInput.getAttribute("type") === "password" ? "text" : "password";
            confirmPasswordInput.setAttribute("type", type);
            confirmPasswordIcon.classList.toggle("fa-eye");
            confirmPasswordIcon.classList.toggle("fa-eye-slash");
        });
    }

    // Form submission with loading state
    if (registerForm && registerBtn) {
        registerForm.addEventListener("submit", function() {
            registerBtn.disabled = true;
            registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...';
        });
    }

    // Real-time password confirmation validation
    const passwordField = document.getElementById("password");
    const confirmPasswordField = document.getElementById("confirm_password");

    function validatePasswordMatch() {
        if (confirmPasswordField.value && passwordField.value !== confirmPasswordField.value) {
            confirmPasswordField.setCustomValidity("Passwords do not match");
        } else {
            confirmPasswordField.setCustomValidity("");
        }
    }

    if (passwordField && confirmPasswordField) {
        passwordField.addEventListener("input", validatePasswordMatch);
        confirmPasswordField.addEventListener("input", validatePasswordMatch);
    }
});
</script>
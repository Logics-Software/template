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
                <h4 class="mb-1">Create Account</h4>
                <p class="text-muted mb-0">Join us today and get started</p>
            </div>
            <div class="card-body">
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
                    <i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:
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
                                    <i class="fas fa-id-card me-2"></i>Full Name
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
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select your role</option>
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
                                  placeholder="" style="height: 100px;" required></textarea>
                        <label for="registration_reason">
                            <i class="fas fa-comment me-2"></i>Reason for Registration
                        </label>
                    </div>

                    <!-- Row 3: Password & Confirm Password -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="" required style="padding-right: 2.5rem;">
                                <label for="password">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <button class="position-absolute top-50 end-0 translate-middle-y password-toggle-btn" 
                                        type="button" id="togglePassword" 
                                        style="border: none; background: transparent; z-index: 10; padding: 0; width: 2.5rem; height: calc(3.5rem + 2px); color: #6c757d; margin-right: 0;" tabindex="-1">
                                    <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       placeholder="" required style="padding-right: 2.5rem;">
                                <label for="confirm_password">
                                    <i class="fas fa-lock me-2"></i>Confirm Password
                                </label>
                                <button class="position-absolute top-50 end-0 translate-middle-y password-toggle-btn" 
                                        type="button" id="toggleConfirmPassword" 
                                        style="border: none; background: transparent; z-index: 10; padding: 0; width: 2.5rem; height: calc(3.5rem + 2px); color: #6c757d; margin-right: 0;" tabindex="-1">
                                    <i class="fas fa-eye" id="confirmPasswordToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#" class="text-primary">Terms and Conditions</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3" id="registerBtn">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </button>
                </form>

                <div class="text-center">
                    <span class="text-muted">Already have an account?</span>
                    <a href="<?php echo APP_URL; ?>/login" class="text-primary text-decoration-none fw-bold ms-1">Sign In</a>
                </div>

                <!-- Registration Notice -->
                <div class="alert alert-info mt-3" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Registration Notice:</strong> Your account will be reviewed by an administrator before activation. 
                    You will receive an email notification once your account is approved.
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
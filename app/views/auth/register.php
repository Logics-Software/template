<?php
// Get flash messages once to avoid multiple calls
$errorMessage = Session::getFlash('error');
$successMessage = Session::getFlash('success');
$validationErrors = Session::getFlash('errors');

$content = '
<!-- Register Page with Custom Container -->
<div class="register-container">
    <div class="register-wrapper">
        <!-- Register Form -->
        <div class="register-form-section">
            <div class="register-header">
                <h1 class="register-title">Create Account</h1>
                <p class="register-subtitle">Join us today and get started</p>
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

                <form method="POST" action="' . APP_URL . '/register" id="registerForm" class="register-form">
                    <input type="hidden" name="_token" value="' . $csrf_token . '">
                    
                    <!-- Row 1: Username & Full Name -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           placeholder="Enter your username" required>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="namalengkap" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                            </span>
                                    <input type="text" class="form-control" id="namalengkap" name="namalengkap" 
                                           placeholder="Enter your full name" required>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Email (Full Width) & Role Selection -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                        placeholder="Enter your email" required>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Row 3: Role Selection -->
                            <div class="form-group">
                                <label for="role" class="form-label">Role</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Select your role</option>
                                        <option value="user">User</option>
                                        <option value="marketing">Marketing</option>
                                        <option value="customer">Customer</option>
                                    </select>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 4: Registration Reason -->
                    <div class="form-group">
                        <label for="registration_reason" class="form-label">Reason for Registration</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-comment"></i>
                            </span>
                            <textarea class="form-control" id="registration_reason" name="registration_reason" 
                                      rows="1 placeholder="Please tell us why you want to register..." required></textarea>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Row 5: Password & Confirm Password -->
                    <div class="row">
                        <div class="col-md-6">
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
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                            </span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           placeholder="Confirm your password" required>
                                    <button class="btn btn-toggle-password" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye" id="confirmPasswordToggleIcon"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="register-form-options">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#">Terms and Conditions</a>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="register-btn">
                        <span class="btn-text">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </span>
                        <div class="btn-loader" style="display: none;">
                            <i class="fas fa-spinner fa-spin me-2"></i>Creating Account...
                        </div>
                    </button>
                </form>

                <div class="register-signup-link">
                    <p>Already have an account? <a href="' . APP_URL . '/login">Sign In</a></p>
                </div>

                <!-- Registration Notice -->
                <div class="register-notice" role="alert">
                    <i class="fas fa-info-circle alert-icon"></i>
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
    const submitBtn = registerForm.querySelector(".register-btn");
    const btnText = submitBtn.querySelector(".btn-text");
    const btnLoader = submitBtn.querySelector(".btn-loader");

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

    // Form submission
    registerForm.addEventListener("submit", function() {
        if (btnText) btnText.style.display = "none";
        if (btnLoader) btnLoader.style.display = "block";
        if (submitBtn) submitBtn.disabled = true;
    });

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
';
?>
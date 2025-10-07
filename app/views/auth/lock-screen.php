<?php
// Flash messages are now handled globally in app.php layout
?>

<!-- Lock Screen Modal Overlay -->
<div class="lock-screen-overlay" id="lockScreenOverlay">
    <div class="lock-screen-modal">
        <div class="card">
            <div class="card-header text-center">
                <div class="mb-3">
                    <img src="<?php echo $user_picture ?? APP_URL . '/assets/images/users/avatar.svg'; ?>" alt="User Avatar" class="rounded-circle profile-img-lg">
                </div>
                <h4 class="mb-1">Hello <?php echo $user_name ?? 'User'; ?>!</h4>
                <p class="text-muted mb-0"><?php echo $user_email ?? 'user@example.com'; ?></p>
            </div>
            <div class="card-body m-3">

                <!-- Lock Screen Form -->
                <form method="POST" action="<?php echo APP_URL; ?>/unlock" id="unlockForm">
                    <input type="hidden" name="_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-floating mb-5 position-relative">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="" required class="pr-10">
                        <label for="password">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <button class="position-absolute top-50 end-0 translate-middle-y password-toggle-btn" 
                                type="button" id="togglePassword" 
                                tabindex="-1">
                            <i class="fas fa-eye" id="passwordToggleIcon"></i>
                        </button>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3" id="unlockBtn">
                        <i class="fas fa-unlock me-2"></i>Unlock
                    </button>

                    <div class="text-center">
                        Masuk ulang lewat <span class="text-primary fw-bold"><a href="<?php echo APP_URL; ?>/logout" class="text-primary text-decoration-none">Login</a></span>
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
            unlockBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Unlocking...';
        });
    }

    // Auto focus on password input
    const passwordField = document.getElementById("password");
    if (passwordField) {
        passwordField.focus();
    }
});
</script>
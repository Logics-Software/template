<?php
// Get flash messages once to avoid multiple calls
$errorMessage = Session::getFlash('error');
$successMessage = Session::getFlash('success');
$validationErrors = Session::getFlash('errors');


$content = '
<!-- Profile Page with Custom Container -->
<div class="profile-wrapper">
    <!-- Profile Form -->
    <div class="profile-form-section">
        <div class="profile-form-container">
            <div class="profile-header">
                <h1 class="profile-title">My Account</h1>
                <p class="profile-subtitle">Update your profile information <strong>@' . ($user['username'] ?? '') . '</strong></p>
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
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    ' . implode("", array_map(function($error) {
                        return "<li>" . $error . "</li>";
                    }, $validationErrors)) . '
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            ' : '') . '

            <form method="POST" action="' . APP_URL . '/profile" id="profileForm" class="profile-form" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="' . $csrf_token . '">
                        
                        <div class="row">
                    <div class="form-group col-md-6">
                        <label for="namalengkap" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="namalengkap" name="namalengkap" 
                                    placeholder="Enter your full name" value="' . ($user['namalengkap'] ?? '') . '" required>
                                </div>
                            </div>

                    <div class="form-group col-md-6">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" 
                                    placeholder="Enter your email address" value="' . ($user['email'] ?? '') . '" required>
                                </div>
                            </div>
                        </div>
                        
                                <div class="form-group">
                    <label for="picture" class="form-label">Profile Picture</label>
                    <div class="profile-picture-container">
                        <div class="current-picture">
                            <div class="current-picture-preview">
                                ' . (isset($user['picture']) && !empty($user['picture']) ? '
                                <img src="' . (strpos($user['picture'], 'assets/images/users/') === 0 ? APP_URL . '/' . $user['picture'] : APP_URL . '/assets/images/users/' . $user['picture']) . '" 
                                        alt="Current Profile Picture" 
                                        class="rounded-circle"" 
                                        width="50" height="50"
                                        onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">
                                ' : '') . '
                                </div>
                            </div>
                        
                        <div class="file-input-container">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-image"></i>
                                </span>
                                <input type="file" class="form-control" id="picture" name="picture" 
                                       accept="image/*">
                            </div>
                            <small class="form-text text-muted">Upload a new profile picture (JPG, PNG, GIF - Max 2MB)</small>
                                </div>
                            </div>
                        </div>
                        
                <div class="col-md-6">
                    
                        </div>
                    
                    
                    </form>

            <div class="profile-actions">
                <button type="button" class="btn btn-secondary" onclick="goBack()">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="submit" form="profileForm" class="btn btn-profile">
                    <span class="btn-text">Update Profile</span>
                    <div class="btn-loader" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                </div>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const profileForm = document.getElementById("profileForm");
    const submitBtn = profileForm.querySelector(".btn-profile");
    const btnText = submitBtn?.querySelector(".btn-text");
    const btnLoader = submitBtn?.querySelector(".btn-loader");

    // Form submission with loading state
    if (profileForm) {
        profileForm.addEventListener("submit", function(e) {
            // Show loading state
            if (btnText) btnText.style.display = "none";
            if (btnLoader) btnLoader.style.display = "block";
            if (submitBtn) submitBtn.disabled = true;
        });
    }

    // Set tabindex for input group elements to prevent focus on icons
    const inputGroups = profileForm.querySelectorAll(".input-group");
    inputGroups.forEach(function(group) {
        const inputGroupText = group.querySelector(".input-group-text");
        const toggleButton = group.querySelector(".btn-toggle-password");
        const formControl = group.querySelector(".form-control");
        
        // Set tabindex for input group text (icons)
        if (inputGroupText) {
            inputGroupText.setAttribute("tabindex", "-1");
        }
        
        // Set tabindex for toggle button
        if (toggleButton) {
            toggleButton.setAttribute("tabindex", "-1");
        }
        
        // Ensure form control is focusable
        if (formControl) {
            formControl.setAttribute("tabindex", "0");
        }
    });

});

// Function to go back to previous page
function goBack() {
    // Check if there\'s a previous page in history
    if (window.history.length > 1) {
        window.history.back();
    } else {
        // Fallback to dashboard if no history
        window.location.href = "' . APP_URL . '/dashboard";
    }
}
</script>
';

echo $content;
?>
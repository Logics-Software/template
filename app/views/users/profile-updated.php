<?php
/**
 * Profile Updated Success Page
 * This page will automatically redirect back to the previous page using JavaScript
 */
?>

<div class="success-page-container">
    <div class="success-page-wrapper">
        <div class="success-page-content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">Profile Updated!</h1>
            <p class="success-message">Your profile has been successfully updated.</p>

            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function goBack() {
    // Check if there's enough history to go back 2 pages
    if (window.history.length > 2) {
        // Go back 2 pages
        window.history.go(-2);
    } else if (window.history.length > 1) {
        // Fallback to 1 page back if not enough history
        window.history.back();
    } else {
        // Fallback to dashboard if no history
        window.location.href = '<?php echo APP_URL; ?>/dashboard';
    }
}

// Auto redirect after 3 seconds
setTimeout(function() {
    goBack();
}, 3000);

// Also try to go back immediately if possible
document.addEventListener('DOMContentLoaded', function() {
    // Try to go back immediately
    setTimeout(function() {
        if (window.history.length > 2) {
            window.history.go(-2);
        } else if (window.history.length > 1) {
            window.history.back();
        }
    }, 1000);
});
</script>
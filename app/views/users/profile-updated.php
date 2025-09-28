<?php
/**
 * Profile Updated Success Page
 * This page will automatically redirect back to the previous page using JavaScript
 */
?>

<div class="profile-wrapper">
    <div class="profile-form-section">
        <div class="profile-form-container">
            <div class="profile-header">
                <div class="text-center mb-4">
                    <div class="success-icon mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="profile-title text-success">Profile Updated!</h1>
                    <p class="profile-subtitle">Your profile has been successfully updated.</p>
                </div>
            </div>

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

<style>
.profile-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 1rem;
}

.profile-wrapper {
    width: 100%;
    max-width: 500px;
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    min-height: 400px;
    display: flex;
    align-items: center;
}

.profile-form-section {
    flex: 1;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.profile-form-container {
    width: 100%;
}

.profile-header {
    text-align: center;
    margin-bottom: 2rem;
}

.profile-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 0.5rem;
}

.profile-subtitle {
    font-size: 1.1rem;
    color: #718096;
    margin-bottom: 0;
}

.success-icon {
    animation: bounce 1s ease-in-out;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    color: white;
}

.btn-outline-primary {
    border: 2px solid #667eea;
    color: #667eea;
    background: transparent;
}

.btn-outline-primary:hover {
    background: #667eea;
    color: white;
    transform: translateY(-1px);
}
</style>

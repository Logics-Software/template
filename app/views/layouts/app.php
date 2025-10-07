<?php
// Check if user is logged in
$isLoggedIn = Session::has('user_id');
$currentUrl = $_SERVER['REQUEST_URI'] ?? '';
$isLoginPage = (strpos($currentUrl, '/login') !== false) || 
               (strpos($currentUrl, '/auth/login') !== false) ||
               (basename($_SERVER['PHP_SELF']) === 'login.php');
$isLockScreenPage = (strpos($currentUrl, '/lock-screen') !== false) ||
                   (strpos($currentUrl, '/unlock') !== false) ||
                   (strpos($currentUrl, '/lock') !== false);
$isRegisterPage = (strpos($currentUrl, '/register') !== false);

// If not logged in and not on login page, register page, or lock screen, redirect to login
if (!$isLoggedIn && !$isLoginPage && !$isLockScreenPage && !$isRegisterPage) {
    header('Location: ' . BASE_URL . 'login');
    exit;
}

// If logged in and on login page or register page, redirect to dashboard
if ($isLoggedIn && ($isLoginPage || $isRegisterPage)) {
    header('Location: ' . BASE_URL . 'dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo $_COOKIE[THEME_COOKIE_NAME] ?? DEFAULT_THEME; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? APP_NAME; ?> - <?php echo APP_NAME; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>assets/images/favicon.png">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/images/favicon.png">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>assets/images/favicon.png">
    
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="<?php echo BASE_URL; ?>assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 7 - Latest Version -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.0.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /></noscript>
    
    <!-- Font Preloading for Chrome/Edge -->
    <link rel="preload" href="<?php echo BASE_URL; ?>assets/fonts/inter/inter-regular.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?php echo BASE_URL; ?>assets/fonts/inter/inter-medium.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?php echo BASE_URL; ?>assets/fonts/inter/inter-semibold.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?php echo BASE_URL; ?>assets/fonts/inter/inter-bold.ttf" as="font" type="font/ttf" crossorigin>
    
    <!-- Local Fonts - Inter -->
    <link href="<?php echo BASE_URL; ?>assets/css/fonts.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="<?php echo BASE_URL; ?>assets/js/chart.js"></script>
    
    <!-- Prevent sidebar flash effect -->
    <!-- Sidebar styles moved to complete.css -->
    
    <!-- Optimized CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/complete-optimized.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>assets/images/favicon.ico">
</head>
<body<?php 
$bodyClass = 'bg-light';
if ($isLoginPage) {
    $bodyClass = 'login-page auth-page';
} elseif ($isRegisterPage) {
    $bodyClass = 'register-page auth-page';
} elseif ($isLockScreenPage) {
    $bodyClass = 'lock-screen-page auth-page';
}
echo ' class="' . $bodyClass . '"';
?>>
    <?php if ($isLoggedIn): ?>
    <!-- Main Layout with Sidebar -->
    <div class="main-wrapper">
        <?php 
        // Include Sidebar Component
        include APP_PATH . '/app/views/components/sidebar.php';
        ?>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Header -->
            <?php 
            // Include Header Component
            include APP_PATH . '/app/views/components/header.php';
            ?>

            <div class="page-content">
                <!-- Flash Messages -->
                <?php 
                $successMessage = Session::getFlash('success');
                if ($successMessage): 
                ?>
                <?php echo AlertHelper::getFlashHtml('success', $successMessage); ?>
                <?php endif; ?>

                <?php 
                $errorMessage = Session::getFlash('error');
                if ($errorMessage): 
                ?>
                <?php echo AlertHelper::getFlashHtml('error', $errorMessage); ?>
                <?php endif; ?>

                <?php 
                $validationErrors = Session::getFlash('errors');
                if ($validationErrors): 
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        <?php foreach ($validationErrors as $field => $errors): ?>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Page Content -->
                <?php echo $content; ?>
            </div>

            <!-- Footer -->
            <?php 
            // Include Footer Component
            include APP_PATH . '/app/views/components/footer.php';
            ?>
        </div>
    </div>
    <?php elseif ($isLockScreenPage): ?>
    <!-- Lock Screen Page - Show normal layout with modal overlay -->
    <!-- Main Layout with Sidebar -->
    <div class="main-wrapper">
        <?php 
        // Include Sidebar Component
        include APP_PATH . '/app/views/components/sidebar.php';
        ?>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Header -->
            <?php 
            // Include Header Component
            include APP_PATH . '/app/views/components/header.php';
            ?>

            <div class="page-content">
                <!-- Flash Messages -->
                <?php 
                $successMessage = Session::getFlash('success');
                if ($successMessage): 
                ?>
                <?php echo AlertHelper::getFlashHtml('success', $successMessage); ?>
                <?php endif; ?>

                <?php 
                $errorMessage = Session::getFlash('error');
                if ($errorMessage): 
                ?>
                <?php echo AlertHelper::getFlashHtml('error', $errorMessage); ?>
                <?php endif; ?>

                <?php 
                $validationErrors = Session::getFlash('errors');
                if ($validationErrors): 
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        <?php foreach ($validationErrors as $field => $errors): ?>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Page Content -->
                <?php echo $content; ?>
            </div>

            <!-- Footer -->
            <?php 
            // Include Footer Component
            include APP_PATH . '/app/views/components/footer.php';
            ?>
        </div>
    </div>
    <?php else: ?>
    <!-- Login Page - No Header, Sidebar, or Footer -->
    <div class="login-page-content">
        <!-- Flash Messages -->
        <?php if (Session::has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo Session::getFlash('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (Session::has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo Session::getFlash('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (Session::has('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                <?php foreach (Session::getFlash('errors') as $field => $errors): ?>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Page Content -->
        <?php echo $content; ?>
    </div>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="<?php echo APP_URL; ?>/assets/js/bootstrap/bootstrap.min.js"></script>
        
    <!-- Alert Manager -->
    <script src="<?php echo APP_URL; ?>/assets/js/modules/AlertManager.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo APP_URL; ?>/assets/js/app.js"></script>
    
    <!-- CSRF Token for AJAX -->
    <script>
        window.csrfToken = '<?php echo $csrf_token ?? ''; ?>';
        window.appUrl = '<?php echo APP_URL; ?>';
        
        // Prevent sidebar flash - apply state immediately
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = document.cookie.split('; ').find(row => row.startsWith('sidebar_collapsed='))?.split('=')[1] === 'true';
            
            if (isCollapsed) {
                const sidebar = document.querySelector('.sidebar');
                const mainContent = document.querySelector('.main-content');
                const topHeader = document.querySelector('.top-header');
                
                if (sidebar) {
                    sidebar.classList.add('collapsed');
                }
                if (mainContent) mainContent.classList.add('sidebar-collapsed');
                if (topHeader) topHeader.classList.add('sidebar-collapsed');
            }
        });
        
        // Initialize theme on page load (will be handled by app.js)
        document.addEventListener('DOMContentLoaded', function() {
            // Theme initialization will be handled by app.js
            // This prevents conflicts with duplicate theme management
        });
    </script>
</body>
</html>

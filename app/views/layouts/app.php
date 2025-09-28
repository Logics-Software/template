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
    header('Location: ' . APP_URL . '/login');
    exit;
}

// If logged in and on login page or register page, redirect to dashboard
if ($isLoggedIn && ($isLoginPage || $isRegisterPage)) {
    header('Location: ' . APP_URL . '/dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo $_COOKIE[THEME_COOKIE_NAME] ?? DEFAULT_THEME; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Hando PHP MVC'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo APP_URL; ?>/assets/images/favicon.png">
    <link rel="shortcut icon" href="<?php echo APP_URL; ?>/assets/images/favicon.png">
    <link rel="apple-touch-icon" href="<?php echo APP_URL; ?>/assets/images/favicon.png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 7 - Latest Version -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <link href="<?php echo APP_URL; ?>/assets/css/style.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo APP_URL; ?>/assets/images/favicon.ico">
</head>
<body<?php 
$bodyClass = 'bg-light';
if ($isLoginPage) {
    $bodyClass = 'login-page';
} elseif ($isRegisterPage) {
    $bodyClass = 'register-page';
} elseif ($isLockScreenPage) {
    $bodyClass = 'lock-screen-page';
}
echo ' class="' . $bodyClass . '"';
?>>
    <?php if ($isLoggedIn): ?>
    <!-- Main Layout with Sidebar -->
    <div class="main-wrapper">
        <?php 
        // Include Sidebar Component
        include APP_PATH . '/app/views/components/sidebar.php';
        echo $sidebar;
        ?>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Header -->
            <?php 
            // Include Header Component
            include APP_PATH . '/app/views/components/header.php';
            echo $header;
            ?>

            <div class="page-content">
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

            <!-- Footer -->
            <?php 
            // Include Footer Component
            include APP_PATH . '/app/views/components/footer.php';
            echo $footer;
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
        echo $sidebar;
        ?>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Header -->
            <?php 
            // Include Header Component
            include APP_PATH . '/app/views/components/header.php';
            echo $header;
            ?>

            <div class="page-content">
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

            <!-- Footer -->
            <?php 
            // Include Footer Component
            include APP_PATH . '/app/views/components/footer.php';
            echo $footer;
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo APP_URL; ?>/assets/js/app.js"></script>
    
    <!-- CSRF Token for AJAX -->
    <script>
        window.csrfToken = '<?php echo $csrf_token ?? ''; ?>';
        window.appUrl = '<?php echo APP_URL; ?>';
        
        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Theme management functions
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(";").shift();
                return null;
            }
            
            function setCookie(name, value, days = 365) {
                const expires = new Date(Date.now() + days * 864e5).toUTCString();
                document.cookie = `${name}=${value}; expires=${expires}; path=/`;
            }
            
            function applyTheme(theme) {
                document.documentElement.setAttribute("data-bs-theme", theme);
                setCookie("hando_theme", theme);
            }
            
            function toggleTheme(theme) {
                applyTheme(theme);
                
                // Update theme toggle icon
                const themeIcon = document.getElementById("themeIcon");
                if (themeIcon) {
                    if (theme === "dark") {
                        themeIcon.className = "fas fa-moon";
                    } else {
                        themeIcon.className = "fas fa-sun";
                    }
                }
            }
            
            // Initialize theme
            const savedTheme = getCookie("hando_theme") || "light";
            applyTheme(savedTheme);
            
            // Initialize theme icon
            const themeIcon = document.getElementById("themeIcon");
            if (themeIcon) {
                if (savedTheme === "dark") {
                    themeIcon.className = "fas fa-moon";
                } else {
                    themeIcon.className = "fas fa-sun";
                }
            }
            
            // Theme toggle event listener
            const themeToggle = document.getElementById("themeToggle");
            if (themeToggle) {
                themeToggle.addEventListener("click", function (e) {
                    e.preventDefault();
                    const currentTheme = document.documentElement.getAttribute("data-bs-theme") || "light";
                    const newTheme = currentTheme === "light" ? "dark" : "light";
                    toggleTheme(newTheme);
                });
            }
        });
    </script>
</body>
</html>

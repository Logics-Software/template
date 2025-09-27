<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo $_COOKIE[THEME_COOKIE_NAME] ?? DEFAULT_THEME; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Hando PHP MVC'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <!-- Font Awesome already included in main.php -->
    
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <link href="<?php echo APP_URL; ?>/assets/css/style.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo APP_URL; ?>/assets/images/favicon.ico">
</head>
<body class="bg-light">
    <?php if (Session::has('user_id')): ?>
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
    <!-- Auth Layout without Sidebar -->
    <div class="auth-wrapper">
        <?php 
        // Include Header Component
        include APP_PATH . '/app/views/components/header.php';
        echo $header;
        ?>

        <!-- Main Content -->
        <div class="auth-content">
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

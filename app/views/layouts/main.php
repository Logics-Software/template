<?php
// Check if user is logged in
$isLoggedIn = Session::has('user_id');
$currentUrl = $_SERVER['REQUEST_URI'] ?? '';
$isLoginPage = (strpos($currentUrl, '/login') !== false) || 
               (strpos($currentUrl, '/auth/login') !== false) ||
               (basename($_SERVER['PHP_SELF']) === 'login.php');

// If not logged in and not on login page, redirect to login
if (!$isLoggedIn && !$isLoginPage) {
    header('Location: ' . APP_URL . '/login');
    exit;
}

// If logged in and on login page, redirect to dashboard
if ($isLoggedIn && $isLoginPage) {
    header('Location: ' . APP_URL . '/dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo $_COOKIE[THEME_COOKIE_NAME] ?? DEFAULT_THEME; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logics Template Application - <?php echo $title ?? ''; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo APP_URL; ?>/assets/images/favicon.png">
    <link rel="shortcut icon" href="<?php echo APP_URL; ?>/assets/images/favicon.png">
    <link rel="apple-touch-icon" href="<?php echo APP_URL; ?>/assets/images/favicon.png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 7 - Latest Version -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Font Awesome 7 - Fallback CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v7.0.0/css/all.css" crossorigin="anonymous" onerror="this.onerror=null;this.href='https://pro.fontawesome.com/releases/v7.0.0/css/all.css';">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <link href="<?php echo APP_URL; ?>/assets/css/style.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo APP_URL; ?>/assets/images/favicon.ico">
</head>
<body<?php echo $isLoginPage ? ' class="login-page"' : ''; ?>>
<?php if ($isLoggedIn): ?>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo APP_URL; ?>">
                <i class="fas fa-home me-2"></i><i class="fas fa-star me-1" style="color: yellow;"></i><i class="fas fa-heart me-1" style="color: red;"></i>Hando
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>/dashboard">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>/users">
                            <i class="fas fa-users me-1"></i>Users
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <!-- Theme Toggle -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-palette"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item theme-toggle" href="#" data-theme="light">
                                <i class="fas fa-sun me-2"></i>Light
                            </a></li>
                            <li><a class="dropdown-item theme-toggle" href="#" data-theme="dark">
                                <i class="fas fa-moon me-2"></i>Dark
                            </a></li>
                            <li><a class="dropdown-item theme-toggle" href="#" data-theme="auto">
                                <i class="fas fa-adjust me-2"></i>Auto
                            </a></li>
                        </ul>
                    </li>
                    
                    <!-- User Menu -->
                    <?php if (Session::has('user_id')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i><?php echo Session::get('user_name'); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/profile">
                                <i class="fas fa-user me-2"></i>Profile
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/settings">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>/login">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
<?php endif; ?>

<?php if ($isLoggedIn): ?>
    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'users' ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/users">
                                <i class="fas fa-users me-2"></i>Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-chart-line me-2"></i>Analytics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-shopping-cart me-2"></i>eCommerce
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-folder me-2"></i>Projects
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Flash Messages -->
                <?php if (Session::has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo Session::getFlash('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (Session::has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo Session::getFlash('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (Session::has('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <span class="text-muted">&copy; <?php echo date('Y'); ?> Hando PHP MVC. All rights reserved.</span>
                </div>
                <div class="col-md-6 text-end">
                    <span class="text-muted">Version <?php echo APP_VERSION; ?></span>
                </div>
            </div>
        </div>
    </footer>
<?php else: ?>
    <!-- Login Page - No Navigation, Sidebar, or Footer -->
    <div class="login-page-content">
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
    </script>
</body>
</html>

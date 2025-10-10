<?php
// Check if user is logged in
$isLoggedIn = Session::has('user_id');
$currentUrl = $_SERVER['REQUEST_URI'] ?? '';
$isLoginPage = (strpos($currentUrl, '/login') !== false) || 
               (strpos($currentUrl, '/auth/login') !== false) ||
               (strpos($currentUrl, '/forgot-password') !== false) ||
               (basename($_SERVER['PHP_SELF']) === 'login.php');
$isLockScreenPage = (strpos($currentUrl, '/lock-screen') !== false) ||
                   (strpos($currentUrl, '/unlock') !== false) ||
                   (strpos($currentUrl, '/lock') !== false);
$isRegisterPage = (strpos($currentUrl, '/register') !== false) ||
                  (strpos($currentUrl, '/auth/register') !== false) ||
                  (basename($_SERVER['PHP_SELF']) === 'register.php');

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
    <link href="<?php echo BASE_URL; ?>assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome - Local Version -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/fontawesome/css/all.min.css?v=6.5.1">
    
    <!-- Font Preloading for Chrome/Edge - Prioritize bold for brand text -->
    <link rel="preload" href="<?php echo BASE_URL; ?>assets/fonts/inter/inter-bold.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?php echo BASE_URL; ?>assets/fonts/inter/inter-semibold.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?php echo BASE_URL; ?>assets/fonts/inter/inter-medium.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="<?php echo BASE_URL; ?>assets/fonts/inter/inter-regular.ttf" as="font" type="font/ttf" crossorigin>
    
    <!-- Local Fonts - Inter -->
    <link href="<?php echo BASE_URL; ?>assets/css/fonts.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="<?php echo BASE_URL; ?>assets/js/chart.js"></script>
        
    <!-- Optimized CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/complete-optimized.css" rel="stylesheet">
    
    <!-- Custom Tooltips CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/components/tooltips.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>assets/images/favicon.ico">
    
    <!-- Prevent sidebar flash on page load - Must be in head before body renders -->
    <script>
        // Immediately apply sidebar state from cookie before DOM renders
        (function() {
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
                return null;
            }
            
            // Check if sidebar should be collapsed
            const isCollapsed = getCookie('sidebar_collapsed') === 'true';
            
            if (isCollapsed) {
                // Add style to prevent flash - will be applied immediately
                document.documentElement.classList.add('sidebar-collapsed-init');
            }
        })();
    </script>
    
    <!-- Critical CSS: Must be inline to prevent sidebar flash -->
    <style>
        /* CRITICAL: Prevent sidebar flash when collapsed - Must execute before render */
        html.sidebar-collapsed-init .sidebar {
            width: 0;
            transform: translateX(-100%);
            overflow: hidden;
        }
        
        html.sidebar-collapsed-init .main-content {
            margin-left: 0;
            width: 100%;
            max-width: 100%;
        }
        
        html.sidebar-collapsed-init .top-header {
            margin-left: 0;
            width: 100%;
            max-width: 100%;
        }
    </style>
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
                <!-- Unified Notifications -->
                <?php echo Notify::render(); ?>

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
                <!-- Unified Notifications -->
                <?php echo Notify::render(); ?>

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
    <?php elseif ($isRegisterPage): ?>
    <!-- Register Page - No Header, Sidebar, or Footer -->
    <div class="register-page-content">
        <?php echo $content; ?>
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

    <!-- Menu Group Selection Fullscreen Modal -->
    <?php if (Session::get('pending_menu_selection')): ?>
    <?php 
    $menuGroups = Session::get('available_menu_groups'); 
    
    // Get logo from konfigurasi
    require_once APP_PATH . '/app/models/Konfigurasi.php';
    $konfigurasiModel = new Konfigurasi();
    $konfigurasi = $konfigurasiModel->getConfiguration();
    $logo = $konfigurasi['logo'] ?? null;
    
    // Build full logo path
    $logoPath = null;
    if ($logo) {
        $logoPath = 'assets/images/konfigurasi/' . $logo;
        $logoFullPath = APP_PATH . '/' . $logoPath;
    }
    ?>
    <div class="menu-selection-overlay" id="menuSelectionOverlay">
        <div class="menu-selection-container">
            <div class="menu-selection-card">
                <div class="menu-selection-header">
                    <div class="d-flex align-items-center justify-content-center">
                        <?php if ($logo && isset($logoFullPath) && file_exists($logoFullPath)): ?>
                            <img src="<?php echo APP_URL . '/' . $logoPath; ?>" alt="Logo" class="menu-header-logo me-3">
                        <?php else: ?>
                            <i class="fas fa-sitemap fa-2x text-white me-3"></i>
                        <?php endif; ?>
                        <h4 class="mb-0 text-white">Menu Sistem Aplikasi</h4>
                    </div>
                </div>
                <div class="menu-selection-body">
                    <form id="menuGroupSelectionForm">
                        <div class="mb-4">
                            <select class="form-select form-select-lg" id="menu_group_select" name="group_id" required>
                                <?php foreach ($menuGroups as $group): ?>
                                    <option value="<?php echo $group['id']; ?>" 
                                            data-icon="<?php echo htmlspecialchars($group['icon'] ?? 'fas fa-folder'); ?>"
                                            data-description="<?php echo htmlspecialchars($group['description'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($group['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                Silakan pilih menu group
                            </div>
                        </div>
                        
                        <!-- Selected Menu Preview -->
                        <div id="selected_menu_preview" class="menu-preview-card d-none">
                            <div class="d-flex align-items-center">
                                <div class="me-3 preview-icon-wrapper">
                                    <i id="preview_icon" class="fas fa-folder fa-2x"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 id="preview_name" class="mb-1"></h6>
                                    <p id="preview_description" class="mb-0 text-muted small"></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="menu-selection-footer">
                    <button type="button" class="btn btn-secondary" onclick="location.href='<?php echo APP_URL; ?>/logout'">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmMenuGroupBtn">
                        <i class="fas fa-check-circle me-2"></i>Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        /* Hide background content when menu selection is pending */
        body:has(.menu-selection-overlay) .main-wrapper,
        body:has(.menu-selection-overlay) .login-page-content {
            filter: blur(10px);
            pointer-events: none;
            user-select: none;
        }
        
        /* Menu Selection Fullscreen Overlay - Similar to Login Page */
        .menu-selection-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
            overflow-y: auto;
        }
        
        .menu-selection-container {
            width: 100%;
            max-width: 450px;
            animation: fadeInUp 0.5s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .menu-selection-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .menu-selection-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .menu-header-logo {
            height: 40px;
            width: auto;
            max-width: 80px;
            object-fit: contain;
        }
        
        .menu-selection-body {
            padding: 30px 30px;
        }
        
        .menu-selection-footer {
            padding: 20px 20px;
            background: #f8f9fa;
            display: flex;
            gap: 15px;
            justify-content: space-between;
            border-top: 1px solid #e9ecef;
        }
        
        .menu-selection-footer .btn {
            flex: 1;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 5px;
        }
        
        .menu-preview-card {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border: 2px solid var(--border-color-secondary);
            border-radius: 10px;
            padding: 10px;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .preview-icon-wrapper {
            width: 60px;
            height: 60px;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .preview-icon-wrapper i {
            color: #667eea;
        }
        
        #menu_group_select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        #menu_group_select:focus {
            border-color: var(--border-color-secondary);
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
        }
        
        #menu_group_select.is-invalid {
            border-color: #dc3545;
        }
        
        .menu-selection-footer .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .menu-selection-footer .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        .menu-selection-footer .btn-secondary {
            background: #6c757d;
            border: none;
        }
        
        .menu-selection-footer .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .menu-selection-header {
                padding: 20px;
            }
            
            .menu-header-logo {
                height: 35px;
                max-width: 60px;
            }
            
            .menu-selection-header .fa-sitemap {
                font-size: 1.5rem !important;
            }
            
            .menu-selection-header h4 {
                font-size: 1.25rem;
            }
            
            .menu-selection-body {
                padding: 30px 20px;
            }
            
            .menu-selection-footer {
                flex-direction: column;
            }
            
            .preview-icon-wrapper {
                width: 60px;
                height: 60px;
            }
            
            .preview-icon-wrapper i {
                font-size: 1.5rem !important;
            }
        }
    </style>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <!-- Popper.js required for Bootstrap dropdowns (local file) -->
    <script src="<?php echo APP_URL; ?>/assets/js/popper.min.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/bootstrap/bootstrap.min.js"></script>
        
    <!-- Alert Manager -->
    <script src="<?php echo APP_URL; ?>/assets/js/modules/Notify.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo APP_URL; ?>/assets/js/app.js"></script>
    
    <!-- CSRF Token for AJAX -->
    <script>
        window.csrfToken = '<?php echo $csrf_token ?? ''; ?>';
        window.appUrl = '<?php echo APP_URL; ?>';
        
        // Menu Group Selection Handler
        <?php if (Session::get('pending_menu_selection')): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const menuGroupSelect = document.getElementById('menu_group_select');
            const confirmBtn = document.getElementById('confirmMenuGroupBtn');
            const previewDiv = document.getElementById('selected_menu_preview');
            const previewIcon = document.getElementById('preview_icon');
            const previewName = document.getElementById('preview_name');
            const previewDescription = document.getElementById('preview_description');
            
            // Auto select first menu group (index 1, because 0 is placeholder)
            if (menuGroupSelect.options.length > 1) {
                menuGroupSelect.selectedIndex = 0;
                
                // Show preview immediately for auto-selected option
                const selectedOption = menuGroupSelect.options[0];
                const icon = selectedOption.getAttribute('data-icon');
                const description = selectedOption.getAttribute('data-description');
                const name = selectedOption.text;
                
                previewIcon.className = icon + ' fa-3x';
                previewName.textContent = name;
                previewDescription.textContent = description || 'Tidak ada deskripsi';
                previewDiv.classList.remove('d-none');
            }
            
            // Auto focus to dropdown
            setTimeout(() => {
                menuGroupSelect.focus();
            }, 300);
            
            // Handle dropdown change - show preview
            menuGroupSelect.addEventListener('change', function() {
                if (this.value) {
                    const selectedOption = this.options[this.selectedIndex];
                    const icon = selectedOption.getAttribute('data-icon');
                    const description = selectedOption.getAttribute('data-description');
                    const name = selectedOption.text;
                    
                    // Update preview
                    previewIcon.className = icon + ' fa-3x';
                    previewName.textContent = name;
                    previewDescription.textContent = description || 'Tidak ada deskripsi';
                    
                    previewDiv.classList.remove('d-none');
                    this.classList.remove('is-invalid');
                } else {
                    previewDiv.classList.add('d-none');
                }
            });
            
            // Handle confirm button
            confirmBtn.addEventListener('click', function() {
                const groupId = menuGroupSelect.value;
                
                if (!groupId) {
                    menuGroupSelect.classList.add('is-invalid');
                    window.Notify.warning('Silakan pilih menu group terlebih dahulu');
                    return;
                }
                
                // Show loading state
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                
                // Submit via AJAX
                fetch('<?php echo APP_URL; ?>/select-menu-group', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': window.csrfToken
                    },
                    body: new URLSearchParams({
                        group_id: groupId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success notification
                        window.Notify.success(data.message || 'Menu group berhasil dipilih');
                        
                        // Reload page to refresh menu and hide overlay
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else {
                        window.Notify.error(data.error || 'Gagal memilih menu group');
                        confirmBtn.disabled = false;
                        confirmBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Konfirmasi';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.Notify.error('Terjadi kesalahan saat memproses permintaan');
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Konfirmasi';
                });
            });
            
            // Allow Enter key to submit
            menuGroupSelect.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    confirmBtn.click();
                }
            });
        });
        <?php endif; ?>

        // Global Keyboard Shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+F11 untuk Logout
            if (e.ctrlKey && e.key === 'F11') {
                e.preventDefault(); // Prevent default F11 behavior (fullscreen)
                
                window.location.href = '<?php echo APP_URL; ?>/logout';
            }
        });
    </script>
</body>
</html>

<?php
// Get logo and company name from konfigurasi table
if (!function_exists('getSidebarLogo')) {
    function getSidebarLogo() {
        try {
            // Include the Konfigurasi model
            require_once __DIR__ . '/../../models/Konfigurasi.php';
            
            $konfigurasiModel = new Konfigurasi();
            $konfigurasi = $konfigurasiModel->getConfiguration();
            
            if ($konfigurasi && !empty($konfigurasi['logo'])) {
                $logoPath = APP_URL . '/assets/images/konfigurasi/' . htmlspecialchars($konfigurasi['logo']);
                $logoAlt = htmlspecialchars($konfigurasi['namaperusahaan'] ?? 'Logo');
                $companyName = htmlspecialchars($konfigurasi['namaperusahaan'] ?? APP_NAME);
            } else {
                $logoPath = APP_URL . '/assets/images/logo.png';
                $logoAlt = APP_NAME;
                $companyName = APP_NAME;
            }
            
            return ['path' => $logoPath, 'alt' => $logoAlt, 'company_name' => $companyName];
        } catch (Exception $e) {
            // Fallback to default logo if error occurs
            return ['path' => APP_URL . '/assets/images/logo.png', 'alt' => APP_NAME, 'company_name' => APP_NAME];
        }
    }
}

$logo = getSidebarLogo();
?>

<!-- Sidebar Navigation -->
<nav class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <a href="<?php echo APP_URL; ?>" class="d-flex align-items-center">
                <img src="<?php echo $logo['path']; ?>" alt="<?php echo $logo['alt']; ?>" height="32" class="me-2">
                <span class="brand-text"><?php echo htmlspecialchars($logo['company_name'] ?? APP_NAME); ?></span>
            </a>
        </div>
    </div>
    
    <div class="sidebar-body">
        <ul class="nav nav-pills flex-column sidebar-nav">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false) ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/dashboard">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            
            <!-- Users Management -->
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/users') !== false && strpos($_SERVER['REQUEST_URI'], '/menuakses') === false) ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/users">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Users</span>
                </a>
            </li>
            
            <!-- Modules Management -->
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/modules') !== false) ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/modules">
                    <i class="fas fa-cube"></i>
                    <span class="nav-text">Modules</span>
                </a>
            </li>
            
            <!-- Menu Management -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#menuManagement" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                    <span class="nav-text">Setting Menu</span>
                </a>
                <div class="collapse" id="menuManagement">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/menu') !== false && strpos($_SERVER['REQUEST_URI'], '/menuakses') === false) ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/menu">
                            <span class="nav-text">Setting Menu Aplikasi</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Call Center -->
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/callcenter') !== false) ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/callcenter">
                    <i class="fas fa-phone"></i>
                    <span class="nav-text">Call Center</span>
                </a>
            </li>
            
            <!-- Messages -->
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/messages') !== false) ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/messages">
                    <i class="fas fa-envelope"></i>
                    <span class="nav-text">Messages</span>
                </a>
            </li>
            
            <!-- Configuration -->
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/konfigurasi') !== false) ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/konfigurasi">
                    <i class="fas fa-cog"></i>
                    <span class="nav-text">Configuration</span>
                </a>
            </li>

            <!-- Menu Akses -->
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/menuakses') !== false) ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/menuakses">
                    <i class="fas fa-bars"></i>                    
                    <span class="nav-text">Akses Menu</span>
                </a>
            </li>

        </ul>
    </div>
</nav>

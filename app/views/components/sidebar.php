<?php
// Get logo and company name from konfigurasi table
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

$logo = getSidebarLogo();
?>

<!-- Sidebar Navigation -->
<nav class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <a href="<?php echo APP_URL; ?>" class="d-flex align-items-center">
                <img src="<?php echo $logo['path']; ?>" alt="<?php echo $logo['alt']; ?>" height="32" class="me-2">
                <span class="sidebar-brand-text"><?php echo htmlspecialchars($logo['company_name'] ?? APP_NAME); ?></span>
            </a>
        </div>
    </div>
    
    <div class="sidebar-body">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo (($current_page ?? '') === 'dashboard') ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/dashboard" title="Dashboard">
                    <i class="fa-regular fa-house"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Separator -->
            <li class="nav-item">
                <hr class="sidebar-divider">
            </li>
            
            
            <!-- Settings Dropdown -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle <?php echo ((($current_page ?? '') === 'users' || ($current_page ?? '') === 'konfigurasi' || ($current_page ?? '') === 'call-center' || ($current_page ?? '') === 'modules')) ? 'parent-active' : ''; ?>" href="#" data-bs-toggle="collapse" data-bs-target="#settingsMenu" aria-expanded="<?php echo ((($current_page ?? '') === 'users' || ($current_page ?? '') === 'konfigurasi' || ($current_page ?? '') === 'call-center' || ($current_page ?? '') === 'modules')) ? 'true' : 'false'; ?>" aria-controls="settingsMenu" title="Pengaturan">
                    <i class="fas fa-cog"></i>
                    <span>Setting</span>
                    <i class="fa-chevron-down fa-chevron-down"></i>
                </a>
                <div class="collapse <?php echo ((($current_page ?? '') === 'users' || ($current_page ?? '') === 'konfigurasi' || ($current_page ?? '') === 'call-center' || ($current_page ?? '') === 'modules')) ? 'show' : ''; ?>" id="settingsMenu">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link <?php echo (($current_page ?? '') === 'users') ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/users" title="Manajemen Users">
                                <i class="fas fa-users"></i>
                                <span>Manajemen Users</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (($current_page ?? '') === 'modules') ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/modules" title="Manajemen Modul">
                                <i class="fas fa-puzzle-piece"></i>
                                <span>Modules</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (($current_page ?? '') === 'konfigurasi') ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/konfigurasi" title="Konfigurasi Aplikasi">
                                <i class="fas fa-cog"></i>
                                <span>Konfigurasi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (($current_page ?? '') === 'call-center') ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/call-center" title="Manajemen Call Center">
                                <i class="fab fa-whatsapp"></i>
                                <span>Call Center</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>

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
            $companyName = htmlspecialchars($konfigurasi['namaperusahaan'] ?? 'Logics Template');
        } else {
            $logoPath = APP_URL . '/assets/images/logo.png';
            $logoAlt = 'Logics';
            $companyName = 'Logics Template';
        }
        
        return ['path' => $logoPath, 'alt' => $logoAlt, 'company_name' => $companyName];
    } catch (Exception $e) {
        // Fallback to default logo if error occurs
        return ['path' => APP_URL . '/assets/images/logo.png', 'alt' => 'Logics', 'company_name' => 'Logics Template'];
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
                <span class="sidebar-brand-text"><?php echo htmlspecialchars($logo['company_name'] ?? 'Logics Template'); ?></span>
            </a>
        </div>
    </div>
    
    <div class="sidebar-body">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo (($current_page ?? '') === 'dashboard') ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/dashboard">
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
                <a class="nav-link dropdown-toggle <?php echo ((($current_page ?? '') === 'users' || ($current_page ?? '') === 'konfigurasi')) ? 'parent-active' : ''; ?>" href="#" data-bs-toggle="collapse" data-bs-target="#settingsMenu" aria-expanded="<?php echo ((($current_page ?? '') === 'users' || ($current_page ?? '') === 'konfigurasi')) ? 'true' : 'false'; ?>" aria-controls="settingsMenu">
                    <i class="fas fa-cog"></i>
                    <span>Setting</span>
                    <i class="fa-chevron-down fa-chevron-down"></i>
                </a>
                <div class="collapse <?php echo ((($current_page ?? '') === 'users' || ($current_page ?? '') === 'konfigurasi')) ? 'show' : ''; ?>" id="settingsMenu">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link <?php echo (($current_page ?? '') === 'users') ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/users">
                                <i class="fas fa-users"></i>
                                <span>Manajemen Users</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (($current_page ?? '') === 'konfigurasi') ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/konfigurasi">
                                <i class="fas fa-cog"></i>
                                <span>Konfigurasi</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>

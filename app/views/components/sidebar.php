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

// Build dynamic menu
if (!function_exists('buildDynamicMenu')) {
function buildDynamicMenu() {
    try {
        // Include required models and services
        require_once __DIR__ . '/../../models/Module.php';
        require_once __DIR__ . '/../../models/MenuGroup.php';
        require_once __DIR__ . '/../../models/MenuPermission.php';
        require_once __DIR__ . '/../../services/MenuService.php';
        
        $menuService = new MenuService();
        $userId = Session::has('user_id') ? Session::get('user_id') : null;
        $currentPage = $current_page ?? '';
        
        // Get user's menu items
        $menuItems = $menuService->buildUserMenu($userId);
        
        // Render menu HTML
        return $menuService->renderMenu($menuItems, $currentPage);
        
    } catch (Exception $e) {
        // Fallback to static menu if dynamic menu fails
        return buildStaticMenu();
    }
}
}

// Fallback static menu
if (!function_exists('buildStaticMenu')) {
function buildStaticMenu() {
    $currentPage = $current_page ?? '';
    $html = '';
    
    // Dashboard
    $html .= '<li class="nav-item">';
    $html .= '<a class="nav-link ' . (($currentPage === 'dashboard') ? 'active' : '') . '" href="' . APP_URL . '/dashboard" title="Dashboard">';
    $html .= '<i class="fa-regular fa-house"></i>';
    $html .= '<span>Dashboard</span>';
    $html .= '</a>';
    $html .= '</li>';
    
    // Separator
    $html .= '<li class="nav-item"><hr class="sidebar-divider"></li>';
    
    // Settings Dropdown
    $isSettingsActive = in_array($currentPage, ['users', 'konfigurasi', 'call-center', 'modules']);
    $html .= '<li class="nav-item">';
    $html .= '<a class="nav-link dropdown-toggle ' . ($isSettingsActive ? 'parent-active' : '') . '" href="#" data-bs-toggle="collapse" data-bs-target="#settingsMenu" aria-expanded="' . ($isSettingsActive ? 'true' : 'false') . '" aria-controls="settingsMenu" title="Pengaturan">';
    $html .= '<i class="fas fa-cog"></i>';
    $html .= '<span>Setting</span>';
    $html .= '<i class="fa-chevron-down fa-chevron-down"></i>';
    $html .= '</a>';
    $html .= '<div class="collapse ' . ($isSettingsActive ? 'show' : '') . '" id="settingsMenu">';
    $html .= '<ul class="nav nav-pills flex-column ms-3">';
    
    // Settings menu items
    $settingsItems = [
        ['page' => 'users', 'url' => '/users', 'icon' => 'fas fa-users', 'title' => 'Manajemen Users', 'label' => 'Manajemen Users'],
        ['page' => 'modules', 'url' => '/modules', 'icon' => 'fas fa-puzzle-piece', 'title' => 'Manajemen Modul', 'label' => 'Modules'],
        ['page' => 'menu', 'url' => '/menu', 'icon' => 'fas fa-bars', 'title' => 'Manajemen Menu', 'label' => 'Menu'],
        ['page' => 'konfigurasi', 'url' => '/konfigurasi', 'icon' => 'fas fa-cog', 'title' => 'Konfigurasi Aplikasi', 'label' => 'Konfigurasi'],
        ['page' => 'call-center', 'url' => '/call-center', 'icon' => 'fab fa-whatsapp', 'title' => 'Manajemen Call Center', 'label' => 'Call Center']
    ];
    
    foreach ($settingsItems as $item) {
        $html .= '<li class="nav-item">';
        $html .= '<a class="nav-link ' . (($currentPage === $item['page']) ? 'active' : '') . '" href="' . APP_URL . $item['url'] . '" title="' . $item['title'] . '">';
        $html .= '<i class="' . $item['icon'] . '"></i>';
        $html .= '<span>' . $item['label'] . '</span>';
        $html .= '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    $html .= '</div>';
    $html .= '</li>';
    
    return $html;
}
}

$logo = getSidebarLogo();
$dynamicMenu = buildDynamicMenu();
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
            <?php echo $dynamicMenu; ?>
        </ul>
    </div>
</nav>

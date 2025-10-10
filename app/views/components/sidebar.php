<?php
// Get logo and company name from konfigurasi table (with caching)
if (!function_exists('getSidebarLogo')) {
    function getSidebarLogo() {
        // Use static variable for caching within same request
        static $cachedLogo = null;
        
        if ($cachedLogo !== null) {
            return $cachedLogo;
        }
        
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
            
            $cachedLogo = ['path' => $logoPath, 'alt' => $logoAlt, 'company_name' => $companyName];
            return $cachedLogo;
        } catch (Exception $e) {
            // Fallback to default logo if error occurs
            $cachedLogo = ['path' => APP_URL . '/assets/images/logo.png', 'alt' => APP_NAME, 'company_name' => APP_NAME];
            return $cachedLogo;
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
            <?php
            // Always show Dashboard first (based on role)
            $userRole = Session::get('user_role');
            $dashboardIcon = 'fas fa-home';
            $dashboardLabel = 'Dashboard';
            
            // Customize dashboard based on role
            if ($userRole === 'admin') {
                $dashboardLabel = 'Dashboard Admin';
            } elseif ($userRole === 'manajemen') {
                $dashboardLabel = 'Dashboard Manajemen';
            } elseif ($userRole === 'user') {
                $dashboardLabel = 'Dashboard User';
            } elseif ($userRole === 'marketing') {
                $dashboardLabel = 'Dashboard Marketing';
            } elseif ($userRole === 'customer') {
                $dashboardLabel = 'Dashboard Customer';
            }
            ?>
            
            <!-- Dashboard - Always shown first -->
            <li class="nav-item">
                <?php
                // Exact matching for dashboard
                $currentUri = $_SERVER['REQUEST_URI'];
                $currentPath = parse_url($currentUri, PHP_URL_PATH);
                $isDashboardActive = ($currentPath === '/dashboard' || strpos($currentPath, '/dashboard/') === 0) ? 'active' : '';
                ?>
                <a class="nav-link mb-3 <?php echo $isDashboardActive; ?>" href="<?php echo APP_URL; ?>/dashboard">
                    <i class="<?php echo $dashboardIcon; ?>"></i>
                    <span class="nav-text"><?php echo $dashboardLabel; ?></span>
                </a>
                <span class="text-muted m-4 fw-bold">Menu</span>
            </li>
            
            <?php
            // Load dynamic menu from selected group
            $activeGroupId = Session::get('active_menu_group_id');
            
            if ($activeGroupId) {
                // Load menu items for the active group
                require_once __DIR__ . '/../../models/MenuItem.php';
                require_once __DIR__ . '/../../models/Module.php';
                
                $menuItemModel = new MenuItem();
                $moduleModel = new Module();
                
                // Get all menu items for this group (only parent items, no parent_id)
                $menuItems = $menuItemModel->getItemsByGroup($activeGroupId);
                
                // Filter to get only parent items (parent_id IS NULL)
                $parentItems = array_filter($menuItems, function($item) {
                    return empty($item['parent_id']);
                });
                
                foreach ($parentItems as $menuItem) {
                    $isParent = !empty($menuItem['is_parent']);
                    $module = null;
                    
                    // Get module details if module_id exists
                    if (!empty($menuItem['module_id'])) {
                        $module = $moduleModel->find($menuItem['module_id']);
                    }
                    
                    // Determine link and label
                    $menuLink = $module ? $module['link'] : '#';
                    $menuLabel = $menuItem['name'];
                    $menuIcon = $menuItem['icon'] ?? 'fas fa-circle';
                    
                    if ($isParent) {
                        // Parent menu with children
                        $collapseId = 'menu-' . $menuItem['id'];
                        $children = $menuItemModel->getChildren($menuItem['id']);
                        ?>
                        <li class="nav-item">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>" aria-expanded="false">
                                <i class="<?php echo htmlspecialchars($menuIcon); ?>"></i>
                                <span class="nav-text"><?php echo htmlspecialchars($menuLabel); ?></span>
                            </a>
                            <div class="collapse" id="<?php echo $collapseId; ?>">
                                <ul class="nav nav-pills flex-column submenu">
                                    <?php foreach ($children as $child) {
                                        $childModule = null;
                                        if (!empty($child['module_id'])) {
                                            $childModule = $moduleModel->find($child['module_id']);
                                        }
                                        $childLink = $childModule ? $childModule['link'] : '#';
                                        $childLabel = $child['name'];
                                        $childIcon = $child['icon'] ?? 'fas fa-angle-right';
                                        
                                        // Exact matching with boundary check to avoid substring matches
                                        $currentUri = $_SERVER['REQUEST_URI'];
                                        $currentPath = parse_url($currentUri, PHP_URL_PATH);
                                        $isActive = ($childLink !== '#' && ($currentPath === $childLink || strpos($currentPath, $childLink . '/') === 0)) ? 'active' : '';
                                    ?>
                                    <li class="nav-item">
                                        <a class="nav-link submenu-link <?php echo $isActive; ?>" href="<?php echo APP_URL . $childLink; ?>">
                                            <i class="<?php echo htmlspecialchars($childIcon); ?>"></i>
                                            <span class="nav-text"><?php echo htmlspecialchars($childLabel); ?></span>
                            </a>
                        </li>
                                    <?php } ?>
                    </ul>
                </div>
            </li>
                        <?php
                    } else {
                        // Single menu item
                        // Exact matching with boundary check to avoid substring matches
                        $currentUri = $_SERVER['REQUEST_URI'];
                        $currentPath = parse_url($currentUri, PHP_URL_PATH);
                        $isActive = ($menuLink !== '#' && ($currentPath === $menuLink || strpos($currentPath, $menuLink . '/') === 0)) ? 'active' : '';
                        ?>
            <li class="nav-item">
                            <a class="nav-link <?php echo $isActive; ?>" href="<?php echo APP_URL . $menuLink; ?>">
                                <i class="<?php echo htmlspecialchars($menuIcon); ?>"></i>
                                <span class="nav-text"><?php echo htmlspecialchars($menuLabel); ?></span>
                </a>
            </li>
                        <?php
                    }
                }
            }
            // If no active_menu_group_id, only Dashboard will be shown (already rendered above)
            ?>

        </ul>
    </div>
</nav>

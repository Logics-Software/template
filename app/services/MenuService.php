<?php

/**
 * MenuService
 * Handles menu building logic and user permissions
 */
class MenuService
{
    private $moduleModel;
    private $menuGroupModel;
    private $menuPermissionModel;

    public function __construct()
    {
        $this->moduleModel = new Module();
        $this->menuGroupModel = new MenuGroup();
        $this->menuPermissionModel = new MenuPermission();
    }

    /**
     * Build menu for specific user
     */
    public function buildUserMenu($userId)
    {
        if (!$userId) {
            return $this->getDefaultMenu();
        }

        // Get user's role
        $userRole = $this->getUserRole($userId);
        if (!$userRole) {
            return $this->getDefaultMenu();
        }

        // Get allowed menu items and groups
        $allowedModules = $this->menuPermissionModel->getAccessibleModules($userRole);
        $allowedGroups = $this->menuPermissionModel->getAccessibleGroups($userRole);

        // Build menu structure
        $menuStructure = $this->buildMenuStructure($allowedModules, $allowedGroups);

        return $menuStructure;
    }

    /**
     * Get user role
     */
    private function getUserRole($userId)
    {
        $database = Database::getInstance();
        $sql = "SELECT role FROM users WHERE id = ?";
        $result = $database->fetch($sql, [$userId]);
        return $result ? $result['role'] : null;
    }

    /**
     * Get default menu for unauthenticated users
     */
    private function getDefaultMenu()
    {
        return [
            [
                'type' => 'link',
                'name' => 'Login',
                'url' => '/login',
                'icon' => 'fas fa-sign-in-alt',
                'children' => []
            ]
        ];
    }

    /**
     * Build menu structure from modules and groups
     */
    private function buildMenuStructure($modules, $groups)
    {
        $menuStructure = [];

        // First, add standalone modules (not in groups)
        $standaloneModules = array_filter($modules, function($module) {
            return empty($module['parent_id']);
        });

        foreach ($standaloneModules as $module) {
            $menuStructure[] = $this->buildMenuItem($module);
        }

        // Then, add groups with their modules
        foreach ($groups as $group) {
            $groupModules = array_filter($modules, function($module) use ($group) {
                return $module['parent_id'] == $group['id'];
            });

            if (!empty($groupModules)) {
                $menuStructure[] = $this->buildGroupItem($group, $groupModules);
            }
        }

        // Sort by sort_order
        usort($menuStructure, function($a, $b) {
            return ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0);
        });

        return $menuStructure;
    }

    /**
     * Build single menu item
     */
    private function buildMenuItem($module)
    {
        return [
            'type' => 'link',
            'id' => $module['id'],
            'name' => $module['caption'],
            'url' => $module['link'],
            'icon' => $module['menu_icon'] ?? 'fas fa-circle',
            'description' => $module['menu_description'] ?? '',
            'is_external' => $module['is_external'] ?? false,
            'open_in_new_tab' => $module['open_in_new_tab'] ?? false,
            'sort_order' => $module['sort_order'] ?? 0,
            'children' => []
        ];
    }

    /**
     * Build group menu item with children
     */
    private function buildGroupItem($group, $modules)
    {
        $children = [];
        foreach ($modules as $module) {
            $children[] = $this->buildMenuItem($module);
        }

        // Sort children by sort_order
        usort($children, function($a, $b) {
            return ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0);
        });

        return [
            'type' => 'group',
            'id' => $group['id'],
            'name' => $group['name'],
            'icon' => $group['icon'] ?? 'fas fa-folder',
            'description' => $group['description'] ?? '',
            'is_collapsible' => $group['is_collapsible'] ?? true,
            'sort_order' => $group['sort_order'] ?? 0,
            'children' => $children
        ];
    }

    /**
     * Render menu as HTML
     */
    public function renderMenu($menuItems, $currentPage = '')
    {
        $html = '';
        
        foreach ($menuItems as $item) {
            if ($item['type'] === 'group') {
                $html .= $this->renderGroupMenu($item, $currentPage);
            } else {
                $html .= $this->renderLinkMenu($item, $currentPage);
            }
        }

        return $html;
    }

    /**
     * Render group menu item
     */
    private function renderGroupMenu($group, $currentPage)
    {
        $isActive = $this->isGroupActive($group, $currentPage);
        $groupId = 'group-' . $group['id'];
        
        $html = '<li class="nav-item">';
        $html .= '<a class="nav-link dropdown-toggle ' . ($isActive ? 'parent-active' : '') . '" ';
        $html .= 'href="#" data-bs-toggle="collapse" data-bs-target="#' . $groupId . '" ';
        $html .= 'aria-expanded="' . ($isActive ? 'true' : 'false') . '" ';
        $html .= 'aria-controls="' . $groupId . '">';
        $html .= '<i class="' . $group['icon'] . '"></i>';
        $html .= '<span>' . htmlspecialchars($group['name']) . '</span>';
        $html .= '<i class="fa-chevron-down fa-chevron-down"></i>';
        $html .= '</a>';
        
        $html .= '<div class="collapse ' . ($isActive ? 'show' : '') . '" id="' . $groupId . '">';
        $html .= '<ul class="nav nav-pills flex-column ms-3">';
        
        foreach ($group['children'] as $child) {
            $html .= $this->renderLinkMenu($child, $currentPage, true);
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</li>';

        return $html;
    }

    /**
     * Render link menu item
     */
    private function renderLinkMenu($item, $currentPage, $isChild = false)
    {
        $isActive = $this->isMenuItemActive($item, $currentPage);
        $target = (isset($item['open_in_new_tab']) && $item['open_in_new_tab']) ? ' target="_blank"' : '';
        $isExternal = isset($item['is_external']) && $item['is_external'];
        $appUrl = defined('APP_URL') ? APP_URL : '';
        $url = $isExternal ? $item['url'] : $appUrl . $item['url'];
        
        $html = '<li class="nav-item">';
        $html .= '<a class="nav-link ' . ($isActive ? 'active' : '') . '" ';
        $html .= 'href="' . htmlspecialchars($url) . '"' . $target . '>';
        $html .= '<i class="' . $item['icon'] . '"></i>';
        $html .= '<span>' . htmlspecialchars($item['name']) . '</span>';
        $html .= '</a>';
        $html .= '</li>';

        return $html;
    }

    /**
     * Check if group is active
     */
    private function isGroupActive($group, $currentPage)
    {
        foreach ($group['children'] as $child) {
            if ($this->isMenuItemActive($child, $currentPage)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if menu item is active
     */
    private function isMenuItemActive($item, $currentPage)
    {
        if (!$currentPage) {
            return false;
        }

        // Remove leading slash for comparison
        $itemUrl = ltrim($item['url'], '/');
        $currentUrl = ltrim($currentPage, '/');

        // Exact match
        if ($itemUrl === $currentUrl) {
            return true;
        }

        // Check if current page starts with menu item URL (for nested routes)
        if ($itemUrl && strpos($currentUrl, $itemUrl) === 0) {
            return true;
        }

        return false;
    }

    /**
     * Get menu statistics
     */
    public function getMenuStats()
    {
        $database = Database::getInstance();
        
        $totalModules = $database->fetch("SELECT COUNT(*) as count FROM modules")['count'];
        $totalGroups = $database->fetch("SELECT COUNT(*) as count FROM menu_groups WHERE is_active = 1")['count'];
        $totalPermissions = $database->fetch("SELECT COUNT(*) as count FROM role_menu_permissions")['count'];

        return [
            'total_modules' => $totalModules,
            'total_groups' => $totalGroups,
            'total_permissions' => $totalPermissions
        ];
    }

    /**
     * Update menu sort order
     */
    public function updateMenuSortOrder($menuItems)
    {
        try {
            $database = Database::getInstance();
            $database->beginTransaction();

            foreach ($menuItems as $item) {
                if (isset($item['id']) && isset($item['sort_order'])) {
                    // Update sort order directly in database
                    $sql = "UPDATE modules SET sort_order = ? WHERE id = ?";
                    $database->execute($sql, [$item['sort_order'], $item['id']]);
                }
            }

            $database->commit();
            return true;
        } catch (Exception $e) {
            if (isset($database)) {
                $database->rollback();
            }
            return false;
        }
    }

    /**
     * Get menu builder data
     */
    public function getMenuBuilderData()
    {
        $modules = $this->moduleModel->findAll();
        $groups = $this->menuGroupModel->getAllActive();
        $permissions = $this->menuPermissionModel->getPermissionMatrix();

        return [
            'modules' => $modules,
            'groups' => $groups,
            'permissions' => $permissions
        ];
    }

    /**
     * Validate menu structure
     */
    public function validateMenuStructure($menuItems)
    {
        $errors = [];

        foreach ($menuItems as $item) {
            if (empty($item['name'])) {
                $errors[] = 'Menu item name is required';
            }

            if ($item['type'] === 'link' && empty($item['url'])) {
                $errors[] = 'Menu item URL is required for link type';
            }

            if (!empty($item['children'])) {
                $childErrors = $this->validateMenuStructure($item['children']);
                $errors = array_merge($errors, $childErrors);
            }
        }

        return $errors;
    }

    /**
     * Clear menu cache (if implemented)
     */
    public function clearMenuCache()
    {
        // Implementation for clearing menu cache
        // This can be extended when caching is implemented
        return true;
    }

    /**
     * Export menu configuration
     */
    public function exportMenuConfig()
    {
        $data = $this->getMenuBuilderData();
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * Import menu configuration
     */
    public function importMenuConfig($configJson)
    {
        try {
            $data = json_decode($configJson, true);
            
            if (!$data) {
                throw new Exception('Invalid JSON configuration');
            }

            // Implementation for importing menu configuration
            // This would involve updating modules, groups, and permissions
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

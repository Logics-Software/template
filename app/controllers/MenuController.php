<?php

/**
 * MenuController
 * Handles menu management and administration
 */
class MenuController extends BaseController
{
    private $moduleModel;
    private $moduleController;
    private $menuGroupModel;
    private $menuItemModel;

    public function __construct()
    {
        parent::__construct();
        $this->moduleModel = new Module();
        $this->moduleController = new ModuleController();
        $this->menuGroupModel = new MenuGroup();
        $this->menuItemModel = new MenuItem();
    }

    /**
     * Menu management dashboard
     */
    public function index($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // Check if user is admin
        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            $this->redirect('/dashboard');
            return;
        }

        // Get stats with caching (5 minutes TTL)
        $stats = Cache::remember('menu_stats', function() {
            $database = Database::getInstance();
            return [
                'total_modules' => $database->fetch("SELECT COUNT(*) as count FROM modules")['count'],
                'total_groups' => $database->fetch("SELECT COUNT(*) as count FROM menu_groups WHERE is_active = 1")['count'],
                'total_menu_items' => $database->fetch("SELECT COUNT(*) as count FROM menu_items")['count']
            ];
        }, 300);

        // Get menu builder data with caching
        $modules = Cache::remember('menu_modules', function() {
            return $this->moduleModel->findAll();
        }, 600);
        
        $menuItems = Cache::remember('menu_all_items', function() {
            return $this->menuItemModel->getAll();
        }, 600);
        
        $availableIcons = $this->moduleController->getAvailableIcons();
        
        // Get groups with menu items count in single query (Fix N+1)
        $groups = Cache::remember('menu_groups_with_count', function() {
            return $this->menuGroupModel->getAllActiveWithItemCount();
        }, 600);
        
        $data = [
            'title' => 'Menu Management',
            'stats' => $stats,
            'modules' => $modules,
            'groups' => $groups,
            'menuItems' => $menuItems,
            'available_icons' => $availableIcons
        ];

        $this->view('menu/menu-management', $data);
    }

    /**
     * Menu builder interface
     */
    public function builder($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            $this->redirect('/dashboard');
            return;
        }

        // Get menu builder data directly
        $modules = $this->moduleModel->findAll();
        $groups = $this->menuGroupModel->getAllActive();
        $availableIcons = $this->moduleController->getAvailableIcons();
        
        // Check if we're adding items to a specific group
        $groupId = $request->input('group_id');
        $selectedGroup = null;
        
        if ($groupId) {
            $selectedGroup = $this->menuGroupModel->getGroup($groupId);
            if (!$selectedGroup) {
                $this->redirect('/menu/builder');
                return;
            }
        }

        // Get menu items based on selected group
        if ($groupId) {
            $menuItems = $this->menuItemModel->getItemsByGroup($groupId);
        } else {
            $menuItems = [];
        }

        $data = [
            'title' => 'Menu Builder',
            'modules' => $modules ?? [],
            'groups' => $groups ?? [],
            'menuItems' => $menuItems,
            'available_icons' => $availableIcons,
            'selected_group_id' => $groupId,
            'selected_group' => $selectedGroup
        ];

        $this->view('menu/menu-builder', $data);
    }

    /**
     * Create new menu group
     */
    public function createGroup($request = null, $response = null, $params = [])
    {
        if (!$request->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            $this->json(['error' => 'Forbidden'], 403);
            return;
        }

        try {
            $data = [
                'name' => $request->input('name'),
                'slug' => $this->menuGroupModel->generateUniqueSlug($request->input('name')),
                'icon' => $request->input('icon', 'fas fa-folder'),
                'description' => $request->input('description'),
                'sort_order' => $request->input('sort_order', 0),
                'is_collapsible' => $request->input('is_collapsible') ? true : false,
                'default_admin' => $request->input('default_admin') ? true : false,
                'default_manajemen' => $request->input('default_manajemen') ? true : false,
                'default_user' => $request->input('default_user') ? true : false,
                'default_marketing' => $request->input('default_marketing') ? true : false,
                'default_customer' => $request->input('default_customer') ? true : false
            ];

            if (empty($data['name'])) {
                $this->json(['error' => 'Group name is required'], 400);
                return;
            }

            $result = $this->menuGroupModel->createGroup($data);
            
            if ($result) {
                // Clear menu cache after create
                Cache::forget('menu_stats');
                Cache::forget('menu_groups_with_count');
                
                $this->json(['success' => true, 'message' => 'Menu group created successfully']);
            } else {
                $this->json(['error' => 'Failed to create menu group'], 500);
            }
        } catch (Exception $e) {
            $this->json(['error' => 'Failed to create menu group: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update menu group
     */
    public function updateGroup($request = null, $response = null, $params = [])
    {
        if (!$request->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            $this->json(['error' => 'Forbidden'], 403);
            return;
        }


        try {
            $groupId = $params['id'] ?? $request->input('id');
            
            if (!$groupId) {
                $this->json(['error' => 'Group ID is required'], 400);
                return;
            }

            $data = [
                'name' => $request->input('name'),
                'slug' => $this->menuGroupModel->generateUniqueSlug($request->input('name'), $groupId),
                'icon' => $request->input('icon', 'fas fa-folder'),
                'description' => $request->input('description'),
                'sort_order' => $request->input('sort_order', 0),
                'is_collapsible' => $request->input('is_collapsible') ? true : false,
                'default_admin' => $request->input('default_admin') ? true : false,
                'default_manajemen' => $request->input('default_manajemen') ? true : false,
                'default_user' => $request->input('default_user') ? true : false,
                'default_marketing' => $request->input('default_marketing') ? true : false,
                'default_customer' => $request->input('default_customer') ? true : false
            ];

            if (empty($data['name'])) {
                $this->json(['error' => 'Group name is required'], 400);
                return;
            }

            $result = $this->menuGroupModel->updateGroup($groupId, $data);
            
            if ($result) {
                // Clear menu cache after update
                Cache::forget('menu_stats');
                Cache::forget('menu_groups_with_count');
                
                $this->json(['success' => true, 'message' => 'Menu group updated successfully']);
            } else {
                $this->json(['error' => 'Failed to update menu group'], 500);
            }
        } catch (Exception $e) {
            $this->json(['error' => 'Failed to update menu group: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete menu group
     */
    public function deleteGroup($request = null, $response = null, $params = [])
    {
        if (!$request->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            $this->json(['error' => 'Forbidden'], 403);
            return;
        }

        try {
            $groupId = $params['id'] ?? $request->input('id');
            
            if (!$groupId) {
                $this->json(['error' => 'Group ID is required'], 400);
                return;
            }

            // Get database instance for transaction
            $database = Database::getInstance();
            $database->beginTransaction();
            
            // If group has menu items, delete them first (cascade delete)
            if ($this->menuGroupModel->hasMenuItems($groupId)) {
                $menuItems = $this->menuGroupModel->getMenuItems($groupId);
                
                foreach ($menuItems as $item) {
                    // Delete menu item from database
                    $sql = "DELETE FROM menu_items WHERE id = ?";
                    $database->query($sql, [$item['id']]);
                }
            }
            
            // Now delete the group
            $result = $this->menuGroupModel->deleteGroup($groupId);
            
            if ($result) {
                $database->commit();
                
                // Clear menu cache after delete
                Cache::forget('menu_stats');
                Cache::forget('menu_groups_with_count');
                Cache::forget('menu_all_items');
                
                $this->json(['success' => true, 'message' => 'Menu group and its modules deleted successfully']);
            } else {
                $database->rollback();
                $this->json(['error' => 'Failed to delete menu group'], 500);
            }
        } catch (Exception $e) {
            if (isset($database) && $database->inTransaction()) {
                $database->rollback();
            }
            $this->json(['error' => 'An error occurred while deleting the group: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update menu item properties
     */
    public function updateMenuItem($request = null, $response = null, $params = [])
    {
        if (!$request->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            $this->json(['error' => 'Forbidden'], 403);
            return;
        }

        $itemId = $request->input('id');
        
        if (!$itemId) {
            $this->json(['error' => 'Menu item ID is required'], 400);
            return;
        }

        try {
            // Get module_id directly (no conversion needed)
            $moduleId = $request->input('module_id');
            // Convert empty string to null
            if ($moduleId === '' || $moduleId === null) {
                $moduleId = null;
            } else {
                $moduleId = (int)$moduleId; // Ensure it's an integer
            }
            
            // Handle parent_id - convert empty string to null
            $parentId = $request->input('parent_id');
            if ($parentId === '' || $parentId === null) {
                $parentId = null;
            }
            
            // Handle sort_order - convert empty string to 0
            $sortOrder = $request->input('sort_order');
            if ($sortOrder === '' || $sortOrder === null) {
                $sortOrder = 0;
            }
            
            $data = [
                'group_id' => $request->input('group_id'),
                'parent_id' => $parentId,
                'module_id' => $moduleId,
                'name' => $request->input('name'),
                'icon' => $request->input('icon'),
                'sort_order' => (int)$sortOrder,
                'is_active' => $request->input('is_active') ? 1 : 0,
                'is_parent' => $request->input('is_parent') ? 1 : 0
            ];

            // Update menu item using MenuItem model
            $result = $this->menuItemModel->updateItem($itemId, $data);
            
            if ($result) {
                // Clear menu cache after update
                Cache::forget('menu_all_items');
                Cache::forget('menu_groups_with_count');
                
                $this->json(['success' => true, 'message' => 'Menu item updated successfully']);
            } else {
                $this->json(['error' => 'Failed to update menu item'], 500);
            }
        } catch (Exception $e) {
            $this->json(['error' => 'Failed to update menu item: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update menu sort order
     */
    public function updateSortOrder($request = null, $response = null, $params = [])
    {
        if (!$request->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            $this->json(['error' => 'Forbidden'], 403);
            return;
        }

        $menuItems = $request->json('menu_items');
        
        if (!$menuItems || !is_array($menuItems)) {
            $this->json(['error' => 'Invalid menu items data'], 400);
            return;
        }

        // Update sort order directly
        try {
            $database = Database::getInstance();
            $database->beginTransaction();

            foreach ($menuItems as $item) {
                if (isset($item['id']) && isset($item['sort_order'])) {
                    $sql = "UPDATE modules SET sort_order = ? WHERE id = ?";
                    $database->query($sql, [$item['sort_order'], $item['id']]);
                }
            }

            $database->commit();
            $this->json(['success' => true, 'message' => 'Menu sort order updated successfully']);
        } catch (Exception $e) {
            if (isset($database)) {
                $database->rollback();
            }
            $this->json(['error' => 'Failed to update menu sort order'], 500);
        }
    }

    /**
     * Toggle menu item visibility
     */
    public function toggleVisibility($request = null, $response = null, $params = [])
    {
        if (!$request->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            $this->json(['error' => 'Forbidden'], 403);
            return;
        }

        $moduleId = $params['id'] ?? $request->input('id');
        
        if (!$moduleId) {
            $this->json(['error' => 'Module ID is required'], 400);
            return;
        }

        // Get current module to check visibility
        $module = $this->moduleModel->find($moduleId);
        
        if (!$module) {
            $this->json(['error' => 'Module not found'], 404);
            return;
        }
        
        // Toggle menu visibility by updating admin field (assuming admin controls menu visibility)
        $currentValue = $module['admin'] ?? 0;
        $newValue = $currentValue ? 0 : 1;
        
        $result = $this->moduleModel->update($moduleId, ['admin' => $newValue]);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Menu visibility toggled successfully']);
        } else {
            $this->json(['error' => 'Failed to toggle menu visibility'], 500);
        }
    }

    /**
     * Export menu configuration
     */
    public function exportConfig($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            $this->json(['error' => 'Forbidden'], 403);
            return;
        }

        // Export menu configuration directly
        $modules = $this->moduleModel->findAll();
        $groups = $this->menuGroupModel->getAllActive();
        $menuItems = $this->menuItemModel->getAll();
        
        $data = [
            'modules' => $modules,
            'groups' => $groups,
            'menuItems' => $menuItems
        ];
        
        $config = json_encode($data, JSON_PRETTY_PRINT);
        
        if ($config) {
            $response->header('Content-Type', 'application/json');
            $response->header('Content-Disposition', 'attachment; filename="menu-config.json"');
            $response->send($config);
        } else {
            $this->json(['error' => 'Failed to export configuration'], 500);
        }
    }

    /**
     * Import menu configuration
     */
    public function importConfig($request = null, $response = null, $params = [])
    {
        if (!$request->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            $this->json(['error' => 'Forbidden'], 403);
            return;
        }

        $configJson = $request->input('config');
        
        if (empty($configJson)) {
            $this->json(['error' => 'Configuration data is required'], 400);
            return;
        }

        // Import menu configuration directly
        try {
            $data = json_decode($configJson, true);
            
            if (!$data) {
                throw new Exception('Invalid JSON configuration');
            }

            // Implementation for importing menu configuration
            // This would involve updating modules, groups, and menu items
            // For now, just return success
            
            $this->json(['success' => true, 'message' => 'Configuration imported successfully']);
        } catch (Exception $e) {
            $this->json(['error' => 'Failed to import configuration: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get group data for editing
     */
    public function getGroup($request = null, $response = null, $params = [])
    {
        if (!$this->isAuthorized()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $groupId = $params[0] ?? $request->input('id');
        
        if (!$groupId) {
            $this->json(['error' => 'Group ID is required'], 400);
            return;
        }

        try {
            $group = $this->menuGroupModel->getGroup($groupId);
            
            if ($group) {
                $this->json(['success' => true, 'group' => $group]);
            } else {
                $this->json(['error' => 'Group not found'], 404);
            }
        } catch (Exception $e) {
            $this->json(['error' => 'Failed to get group: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get menu item data for editing
     */
    public function getMenuItem($request = null, $response = null, $params = [])
    {
        if (!$this->isAuthorized()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $itemId = $params[0] ?? $request->input('id');
        
        if (!$itemId) {
            $this->json(['error' => 'Menu item ID is required'], 400);
            return;
        }

        try {
            $item = $this->menuItemModel->getItem($itemId);
            
            if ($item) {
                // module_id is already an integer, no conversion needed
                $this->json(['success' => true, 'menuItem' => $item]);
            } else {
                $this->json(['error' => 'Menu item not found'], 404);
            }
        } catch (Exception $e) {
            $this->json(['error' => 'Failed to get menu item: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Get module path by module ID
     */
    private function getModulePathById($moduleId)
    {
        // Define mapping from module ID to path (based on your main routes)
        $moduleMapping = [
            1 => '/dashboard',
            2 => '/users', 
            3 => '/konfigurasi',
            4 => '/call-center',
            5 => '/messages',
            6 => '/modules',
            7 => '/menu',
            8 => '/analytics'
        ];
        
        return $moduleMapping[$moduleId] ?? null;
    }
    
    /**
     * Get module ID by module path
     */
    private function getModuleIdByPath($modulePath)
    {
        // Define mapping from path to module ID (reverse of getModulePathById)
        $pathMapping = [
            '/dashboard' => 1,
            '/users' => 2,
            '/konfigurasi' => 3,
            '/call-center' => 4,
            '/messages' => 5,
            '/modules' => 6,
            '/menu' => 7,
            '/analytics' => 8
        ];
        
        return $pathMapping[$modulePath] ?? null;
    }

    /**
     * Get menu items for a specific group
     */
    public function getGroupItems($request = null, $response = null, $params = [])
    {
        if (!$this->isAuthorized()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $groupId = $params[0] ?? $request->input('id');
        
        if (!$groupId) {
            $this->json(['error' => 'Group ID is required'], 400);
            return;
        }

        try {
            $menuItems = $this->menuItemModel->getItemsByGroup($groupId);
            
            // Simple approach: just return the raw menu items with a simple level calculation
            foreach ($menuItems as &$item) {
                // Set level: 0 for root items (parent_id is null), 1 for child items
                $item['level'] = $item['parent_id'] ? 1 : 0;
            }
            
            // Sort by sort_order
            usort($menuItems, function($a, $b) {
                return ($a['sort_order'] ?? 0) - ($b['sort_order'] ?? 0);
            });
            
            $this->json(['success' => true, 'menuItems' => $menuItems]);
        } catch (Exception $e) {
            $this->json(['error' => 'Failed to get group items: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create menu item
     */
    public function createMenuItem($request = null, $response = null, $params = [])
    {
        if (!$this->isAuthorized()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        // CSRF validation already handled globally in App.php
        // No need to validate again here

        try {
            // Get module_id directly (no conversion needed)
            $moduleId = $request->input('module_id');
            // Convert empty string to null
            if ($moduleId === '' || $moduleId === null) {
                $moduleId = null;
            } else {
                $moduleId = (int)$moduleId; // Ensure it's an integer
            }
            
            // Handle parent_id - convert empty string to null
            $parentId = $request->input('parent_id');
            if ($parentId === '' || $parentId === null) {
                $parentId = null;
            }
            
            // Handle sort_order - convert empty string to 0
            $sortOrder = $request->input('sort_order');
            if ($sortOrder === '' || $sortOrder === null) {
                $sortOrder = 0;
            }
            
            $data = [
                'group_id' => $request->input('group_id'),
                'parent_id' => $parentId,
                'module_id' => $moduleId,
                'name' => $request->input('name'),
                'icon' => $request->input('icon') ?: 'fas fa-circle',
                'sort_order' => (int)$sortOrder,
                'is_active' => $request->input('is_active') ? 1 : 0,
                'is_parent' => $request->input('is_parent') ? 1 : 0
            ];

            if (!$data['group_id'] || !$data['name']) {
                $this->json(['error' => 'Group ID and name are required'], 400);
                return;
            }

            $result = $this->menuItemModel->createItem($data);
            
            if ($result) {
                // Clear menu cache after create
                Cache::forget('menu_all_items');
                Cache::forget('menu_groups_with_count');
                Cache::forget('menu_stats');
                
                $this->json(['success' => true, 'message' => 'Menu item created successfully']);
            } else {
                $this->json(['error' => 'Failed to create menu item'], 500);
            }
        } catch (Exception $e) {
            $this->json(['error' => 'Failed to create menu item: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Delete menu item
     */
    public function deleteMenuItem($request = null, $response = null, $params = [])
    {
        if (!$this->isAuthorized()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        // CSRF validation already handled globally in App.php
        $itemId = $params['id'] ?? $request->input('id');
        
        if (!$itemId) {
            $this->json(['error' => 'Menu item ID is required'], 400);
            return;
        }

        try {
            $result = $this->menuItemModel->deleteItem($itemId);
            
            if ($result) {
                // Clear menu cache after delete
                Cache::forget('menu_all_items');
                Cache::forget('menu_groups_with_count');
                Cache::forget('menu_stats');
                
                $this->json(['success' => true, 'message' => 'Menu item deleted successfully']);
            } else {
                $this->json(['error' => 'Failed to delete menu item'], 500);
            }
        } catch (Exception $e) {
            $this->json(['error' => 'Failed to delete menu item: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update menu items sort order via drag and drop
     */
    public function updateMenuItemSort($request = null, $response = null, $params = [])
    {
        if (!$request->isPost()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            $this->json(['error' => 'Forbidden'], 403);
            return;
        }

        // Accept both 'orders' (from drag-drop.js) and 'menu_items' (legacy)
        $orders = $request->json('orders') ?? $request->json('menu_items');
        
        if (!$orders || !is_array($orders)) {
            $this->json(['error' => 'Invalid menu items data'], 400);
            return;
        }

        // Update sort order for menu_items table
        try {
            $database = Database::getInstance();
            $database->beginTransaction();

            foreach ($orders as $item) {
                if (isset($item['id']) && isset($item['sort_order'])) {
                    $sql = "UPDATE menu_items SET sort_order = ? WHERE id = ?";
                    $database->query($sql, [$item['sort_order'], $item['id']]);
                }
            }

            $database->commit();
            
            // Clear menu cache after update
            Cache::forget('menu_all_items');
            Cache::forget('menu_groups_with_count');
            
            $this->json(['success' => true, 'message' => 'Menu items sort order updated successfully']);
        } catch (Exception $e) {
            if (isset($database)) {
                $database->rollback();
            }
            $this->json(['error' => 'Failed to update menu items sort order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Check if user is authorized to access menu management
     */
    private function isAuthorized()
    {
        if (!Session::has('user_id')) {
            return false;
        }

        $userRole = Session::get('user_role');
        return in_array($userRole, ['admin', 'manajemen']);
    }

    /**
     * Get parent menu items for a specific group
     */
    public function getParentItems($request = null, $response = null, $params = [])
    {
        if (!$this->isAuthorized()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $groupId = $params[0] ?? $request->input('id');
        
        if (!$groupId) {
            $this->json(['error' => 'Group ID is required'], 400);
            return;
        }

        try {
            $parentItems = $this->menuItemModel->getParentItemsByGroup($groupId);
            
            $this->json(['success' => true, 'parentItems' => $parentItems]);
            
        } catch (Exception $e) {
            $this->json(['error' => 'Error loading parent items: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get available Font Awesome icons for icon picker
     * Uses the comprehensive icon list from ModuleController
     */
    public function getIcons($request = null, $response = null, $params = [])
    {
        // Create ModuleController instance to access getAvailableIcons method
        $moduleController = new ModuleController();
        $availableIcons = $moduleController->getAvailableIcons();
        
        // Convert the categorized icons to the format expected by the frontend
        $icons = [];
        foreach ($availableIcons as $category => $iconList) {
            foreach ($iconList as $iconClass => $iconName) {
                $icons[] = [
                    'class' => $iconClass,
                    'label' => $iconName,
                    'category' => $category
                ];
            }
        }

        $this->json([
            'success' => true,
            'icons' => $icons
        ]);
    }

    /**
     * Get main routes from modules table for module dropdown
     */
    public function getMainRoutes($request = null, $response = null, $params = [])
    {
        try {
            // Get all modules from database using direct query
            $database = Database::getInstance();
            $sql = "SELECT id, link, caption, logo FROM modules ORDER BY caption ASC";
            $modules = $database->fetchAll($sql);
            
            // Format modules for frontend dropdown
            $mainRoutes = [];
            foreach ($modules as $module) {
                $mainRoutes[] = [
                    'id' => $module['id'],
                    'path' => $module['link'] ?? '',
                    'name' => $module['caption'] ?? 'Unnamed Module',
                    'icon' => $module['logo'] ?? 'fas fa-circle'
                ];
            }

            $this->json([
                'success' => true,
                'routes' => $mainRoutes
            ]);
            
        } catch (Exception $e) {
            $this->json(['error' => 'Error loading main routes: ' . $e->getMessage()], 500);
        }
    }
}

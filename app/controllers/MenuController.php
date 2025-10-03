<?php

/**
 * MenuController
 * Handles menu management and administration
 */
class MenuController extends BaseController
{
    private $menuService;
    private $moduleModel;
    private $menuGroupModel;
    private $menuItemModel;
    private $menuPermissionModel;

    public function __construct()
    {
        parent::__construct();
        $this->menuService = new MenuService();
        $this->moduleModel = new Module();
        $this->menuGroupModel = new MenuGroup();
        $this->menuItemModel = new MenuItem();
        $this->menuPermissionModel = new MenuPermission();
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

        $stats = $this->menuService->getMenuStats();
        $menuData = $this->menuService->getMenuBuilderData();

        $data = [
            'title' => 'Menu Management',
            'stats' => $stats,
            'modules' => $menuData['modules'],
            'groups' => $menuData['groups'],
            'permissions' => $menuData['permissions']
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

        $menuData = $this->menuService->getMenuBuilderData();
        $availableIcons = $this->moduleModel->getAvailableIcons();

        $data = [
            'title' => 'Menu Builder',
            'modules' => $menuData['modules'],
            'groups' => $menuData['groups'],
            'available_icons' => $availableIcons
        ];

        $this->view('menu/menu-builder', $data);
    }

    /**
     * Permission management
     */
    public function permissions($request = null, $response = null, $params = [])
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

        $menuData = $this->menuService->getMenuBuilderData();
        $roles = ['admin', 'manager', 'staff', 'user', 'marketing', 'customer'];

        $data = [
            'title' => 'Menu Permissions',
            'modules' => $menuData['modules'],
            'groups' => $menuData['groups'],
            'permissions' => $menuData['permissions'],
            'roles' => $roles
        ];

        $this->view('menu/menu-permissions', $data);
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

        $data = [
            'name' => $request->input('name'),
            'slug' => $this->menuGroupModel->generateUniqueSlug($request->input('name')),
            'icon' => $request->input('icon', 'fas fa-folder'),
            'description' => $request->input('description'),
            'sort_order' => $request->input('sort_order', 0),
            'is_collapsible' => $request->input('is_collapsible', true)
        ];

        if (empty($data['name'])) {
            $this->json(['error' => 'Group name is required'], 400);
            return;
        }

        $result = $this->menuGroupModel->createGroup($data);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Menu group created successfully']);
        } else {
            $this->json(['error' => 'Failed to create menu group'], 500);
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

        $groupId = $params['id'] ?? $request->input('id');
        
        if (!$groupId) {
            $this->json(['error' => 'Group ID is required'], 400);
            return;
        }

        $data = [
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'icon' => $request->input('icon', 'fas fa-folder'),
            'description' => $request->input('description'),
            'sort_order' => $request->input('sort_order', 0),
            'is_collapsible' => $request->input('is_collapsible', true)
        ];

        if (empty($data['name'])) {
            $this->json(['error' => 'Group name is required'], 400);
            return;
        }

        $result = $this->menuGroupModel->updateGroup($groupId, $data);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Menu group updated successfully']);
        } else {
            $this->json(['error' => 'Failed to update menu group'], 500);
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

        $groupId = $params['id'] ?? $request->input('id');
        
        if (!$groupId) {
            $this->json(['error' => 'Group ID is required'], 400);
            return;
        }

        // Get database instance for transaction
        $database = Database::getInstance();
        $database->beginTransaction();
        
        try {
        // If group has menu items, delete them first (cascade delete)
        if ($this->menuGroupModel->hasMenuItems($groupId)) {
            $menuItems = $this->menuGroupModel->getMenuItems($groupId);
            foreach ($menuItems as $item) {
                // Delete menu item from database
                $sql = "DELETE FROM menu_items WHERE id = ?";
                $database->query($sql, [$item['id']]);
                
                // Delete menu item permissions
                $sql = "DELETE FROM role_menu_permissions WHERE menu_item_id = ?";
                $database->query($sql, [$item['id']]);
            }
        }
            
            // Now delete the group
            $result = $this->menuGroupModel->deleteGroup($groupId);
            
            if ($result) {
                $database->commit();
                $this->json(['success' => true, 'message' => 'Menu group and its modules deleted successfully']);
            } else {
                $database->rollback();
                $this->json(['error' => 'Failed to delete menu group'], 500);
            }
        } catch (Exception $e) {
            $database->rollback();
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

        $moduleId = $params['id'] ?? $request->input('id');
        
        if (!$moduleId) {
            $this->json(['error' => 'Module ID is required'], 400);
            return;
        }

        $data = [
            'menu_type' => $request->input('menu_type', 'link'),
            'parent_id' => $request->input('parent_id'),
            'sort_order' => $request->input('sort_order', 0),
            'menu_icon' => $request->input('menu_icon', 'fas fa-circle'),
            'menu_description' => $request->input('menu_description'),
            'is_external' => $request->input('is_external', false),
            'open_in_new_tab' => $request->input('open_in_new_tab', false)
        ];

        // Update module properties directly in database
        $result = $this->moduleModel->update($moduleId, $data);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Menu item updated successfully']);
        } else {
            $this->json(['error' => 'Failed to update menu item'], 500);
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

        $result = $this->menuService->updateMenuSortOrder($menuItems);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Menu sort order updated successfully']);
        } else {
            $this->json(['error' => 'Failed to update menu sort order'], 500);
        }
    }

    /**
     * Update role permissions
     */
    public function updatePermissions($request = null, $response = null, $params = [])
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

        $roleId = $request->input('role_id');
        $permissions = $request->input('permissions', []);

        if (!$roleId) {
            $this->json(['error' => 'Role ID is required'], 400);
            return;
        }

        try {
            // Clear existing permissions for this role
            $database = Database::getInstance();
            $sql = "DELETE FROM role_menu_permissions WHERE role_id = ?";
            $database->query($sql, [$roleId]);

            // Add new permissions
            if (!empty($permissions)) {
                $result = $this->menuPermissionModel->bulkGrantPermissions($roleId, $permissions, Session::get('user_id'));
                
                if (!$result) {
                    throw new Exception('Failed to grant permissions');
                }
            }

            $this->json(['success' => true, 'message' => 'Permissions updated successfully']);
        } catch (Exception $e) {
            $this->json(['error' => 'Failed to update permissions'], 500);
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

        $config = $this->menuService->exportMenuConfig();
        
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

        $result = $this->menuService->importMenuConfig($configJson);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Configuration imported successfully']);
        } else {
            $this->json(['error' => 'Failed to import configuration'], 500);
        }
    }

    /**
     * Get menu preview for specific user
     */
    public function preview($request = null, $response = null, $params = [])
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

        $userId = $params['user_id'] ?? Session::get('user_id');
        $menuItems = $this->menuService->buildUserMenu($userId);
        $currentPage = $request ? $request->input('current_page', '') : '';

        $menuHtml = $this->menuService->renderMenu($menuItems, $currentPage);

        $data = [
            'title' => 'Menu Preview',
            'menuItems' => $menuItems,
            'menuHtml' => $menuHtml,
            'userId' => $userId,
            'currentPage' => $currentPage
        ];

        $this->view('menu/menu-preview', $data);
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

        $groupId = $params['id'] ?? $request->input('id');
        
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

        $itemId = $params['id'] ?? $request->input('id');
        
        if (!$itemId) {
            $this->json(['error' => 'Menu item ID is required'], 400);
            return;
        }

        try {
            $item = $this->menuItemModel->getItem($itemId);
            
            if ($item) {
                $this->json(['success' => true, 'item' => $item]);
            } else {
                $this->json(['error' => 'Menu item not found'], 404);
            }
        } catch (Exception $e) {
            $this->json(['error' => 'Failed to get menu item: ' . $e->getMessage()], 500);
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

        $this->validateCSRF($request);

        $data = [
            'group_id' => $request->input('group_id'),
            'parent_id' => $request->input('parent_id') ?: null,
            'module_id' => $request->input('module_id') ?: null,
            'name' => $request->input('name'),
            'icon' => $request->input('icon') ?: 'fas fa-circle',
            'sort_order' => $request->input('sort_order') ?: 0,
            'is_active' => $request->input('is_active') ? 1 : 0,
            'is_parent' => $request->input('is_parent') ? 1 : 0
        ];

        if (!$data['group_id'] || !$data['name']) {
            $this->json(['error' => 'Group ID and name are required'], 400);
            return;
        }

        try {
            $result = $this->menuItemModel->createItem($data);
            
            if ($result) {
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

        $this->validateCSRF($request);

        $itemId = $params['id'] ?? $request->input('id');
        
        if (!$itemId) {
            $this->json(['error' => 'Menu item ID is required'], 400);
            return;
        }

        try {
            $result = $this->menuItemModel->deleteItem($itemId);
            
            if ($result) {
                $this->json(['success' => true, 'message' => 'Menu item deleted successfully']);
            } else {
                $this->json(['error' => 'Failed to delete menu item'], 500);
            }
        } catch (Exception $e) {
            $this->json(['error' => 'Failed to delete menu item: ' . $e->getMessage()], 500);
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
}

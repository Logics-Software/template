<?php

/**
 * Users Menu Access Controller
 * Manages user access to menu groups
 */
class UsersMenuController extends BaseController
{
    private $usersMenuModel;

    public function __construct()
    {
        parent::__construct();
        $this->usersMenuModel = new UsersMenu();
    }

    /**
     * Display list of users with their menu group access
     */
    public function index($request = null, $response = null, $params = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        try {
            $page = (int) ($request->input('page') ?? 1);
            $search = $request->input('search') ?? '';
            $status = $request->input('status') ?? '';
            $role = $request->input('role') ?? '';
            $perPage = (int) ($request->input('per_page') ?? DEFAULT_PAGE_SIZE);
            $sort = $request->input('sort') ?? 'username';
            $order = $request->input('order') ?? 'asc';

            // Build filters
            $filters = [
                'page' => $page,
                'per_page' => $perPage,
                'search' => $search,
                'status' => $status,
                'role' => $role,
                'sort' => $sort,
                'order' => $order
            ];

            // Get users with their menu groups
            $users = $this->usersMenuModel->getAllWithDetails($filters);

            $this->view('menuakses/index', [
                'title' => 'Setting Akses Menu',
                'current_page' => 'menuakses',
                'users' => $users,
                'search' => $search,
                'status' => $status,
                'role' => $role,
                'csrf_token' => $this->csrfToken()
            ]);
        } catch (Exception $e) {
            error_log("UsersMenuController index error: " . $e->getMessage());
            
            $this->withError('An error occurred while loading user menu access. Please try again.');
            $this->redirect('/menuakses');
        }
    }

    /**
     * Show edit form for user menu access
     */
    public function edit($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $userId = $params[0] ?? null;
        
        if (!$userId) {
            $this->withError('User ID is required');
            $this->redirect('/menuakses');
            return;
        }

        try {
            // Get user data
            $userModel = new User();
            $user = $userModel->find($userId);
            
            if (!$user) {
                $this->withError('User not found');
                $this->redirect('/menuakses');
                return;
            }

            // Get all menu groups
            $allMenuGroups = $this->usersMenuModel->getAllMenuGroups();
            
            // Get user's current menu groups
            $userMenuGroups = $this->usersMenuModel->getUserMenuGroups($userId);
            $userGroupIds = array_column($userMenuGroups, 'id');

            $this->view('menuakses/edit', [
                'title' => 'Setting Akses Menu',
                'current_page' => 'menuakses',
                'user' => $user,
                'allMenuGroups' => $allMenuGroups,
                'userGroupIds' => $userGroupIds,
                'csrf_token' => $this->csrfToken()
            ]);
        } catch (Exception $e) {
            error_log("UsersMenuController edit error: " . $e->getMessage());
            $this->withError('An error occurred while loading user menu access.');
            $this->redirect('/menuakses');
        }
    }

    /**
     * Update user menu access
     */
    public function update($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $userId = $params[0] ?? $request->input('user_id');
        
        if (!$userId) {
            $this->json(['error' => 'User ID is required'], 400);
            return;
        }

        try {
            // Get selected group IDs from request
            $groupIds = $request->input('group_ids') ?? [];
            
            // If groupIds is a string (from form), convert to array
            if (is_string($groupIds)) {
                $groupIds = !empty($groupIds) ? explode(',', $groupIds) : [];
            }
            
            // Filter and convert to integers
            $groupIds = array_filter(array_map('intval', $groupIds));

            // Sync user menu groups
            $result = $this->usersMenuModel->syncUserMenuGroups($userId, $groupIds);
            
            if ($result) {
                // Clear cache if exists
                Cache::forget('user_menu_' . $userId);
                
                $this->json(['success' => true, 'message' => 'User menu access updated successfully']);
            } else {
                $this->json(['error' => 'Failed to update user menu access'], 500);
            }
        } catch (Exception $e) {
            error_log("UsersMenuController update error: " . $e->getMessage());
            $this->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Add menu group access to user
     */
    public function addMenuAccess($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        try {
            $userId = $request->input('user_id');
            $groupId = $request->input('group_id');
            
            if (!$userId || !$groupId) {
                $this->json(['error' => 'User ID and Group ID are required'], 400);
                return;
            }

            $result = $this->usersMenuModel->addUserMenuAccess($userId, $groupId);
            
            if ($result) {
                Cache::forget('user_menu_' . $userId);
                $this->json(['success' => true, 'message' => 'Menu access added successfully']);
            } else {
                $this->json(['error' => 'Failed to add menu access'], 500);
            }
        } catch (Exception $e) {
            error_log("UsersMenuController addMenuAccess error: " . $e->getMessage());
            $this->json(['error' => 'An error occurred while adding menu access'], 500);
        }
    }

    /**
     * Remove menu group access from user
     */
    public function removeMenuAccess($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        try {
            $userId = $request->input('user_id');
            $groupId = $request->input('group_id');
            
            if (!$userId || !$groupId) {
                $this->json(['error' => 'User ID and Group ID are required'], 400);
                return;
            }

            $result = $this->usersMenuModel->removeUserMenuAccess($userId, $groupId);
            
            if ($result) {
                Cache::forget('user_menu_' . $userId);
                $this->json(['success' => true, 'message' => 'Menu access removed successfully']);
            } else {
                $this->json(['error' => 'Failed to remove menu access'], 500);
            }
        } catch (Exception $e) {
            error_log("UsersMenuController removeMenuAccess error: " . $e->getMessage());
            $this->json(['error' => 'An error occurred while removing menu access'], 500);
        }
    }

    /**
     * Get user's menu groups (AJAX)
     */
    public function getUserMenuGroups($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $userId = $params[0] ?? $request->input('user_id');
        
        if (!$userId) {
            $this->json(['error' => 'User ID is required'], 400);
            return;
        }

        try {
            $menuGroups = $this->usersMenuModel->getUserMenuGroups($userId);
            
            $this->json([
                'success' => true,
                'menuGroups' => $menuGroups
            ]);
        } catch (Exception $e) {
            error_log("UsersMenuController getUserMenuGroups error: " . $e->getMessage());
            $this->json(['error' => 'An error occurred while loading menu groups'], 500);
        }
    }
}


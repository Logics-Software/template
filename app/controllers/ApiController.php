<?php
/**
 * API Controller for AJAX requests
 */
class ApiController extends BaseController
{
    public function getTheme()
    {
        $theme = $_COOKIE[THEME_COOKIE_NAME] ?? DEFAULT_THEME;
        $this->json(['theme' => $theme]);
    }

    public function setTheme()
    {
        $theme = $this->input('theme', DEFAULT_THEME);
        
        if (!in_array($theme, ['light', 'dark', 'auto'])) {
            $this->json(['error' => 'Invalid theme'], 400);
        }

        setcookie(THEME_COOKIE_NAME, $theme, time() + (365 * 24 * 60 * 60), '/');
        $this->json(['success' => true, 'theme' => $theme]);
    }

    public function searchUsers()
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $query = $this->input('q', '');
        $page = (int) $this->input('page', 1);
        $perPage = (int) $this->input('per_page', DEFAULT_PAGE_SIZE);

        $userModel = new User();
        $result = $userModel->search($query, ['name', 'email'], 'status = :status', ['status' => 'active']);
        
        $this->json([
            'data' => $result,
            'total' => count($result),
            'page' => $page,
            'per_page' => $perPage
        ]);
    }

    /**
     * Check session validity (READ-ONLY - does not update activity)
     */
    public function checkSession()
    {
        // Don't update activity - this is just a check
        $isValid = Session::isValid(false);
        $timeRemaining = 0;
        
        if ($isValid) {
            $lastActivity = Session::get('_last_activity', time());
            $sessionLifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600;
            $timeRemaining = $sessionLifetime - (time() - $lastActivity);
        }
        
        $this->json([
            'valid' => $isValid,
            'timeRemaining' => max(0, $timeRemaining),
            'user' => $isValid ? [
                'id' => Session::get('user_id'),
                'name' => Session::get('user_name'),
                'email' => Session::get('user_email'),
                'role' => Session::get('user_role')
            ] : null
        ]);
    }
    
    /**
     * Update session activity (for real user interactions)
     */
    public function updateActivity()
    {
        if (Session::updateActivity()) {
            $lastActivity = Session::get('_last_activity', time());
            $sessionLifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600;
            $timeRemaining = $sessionLifetime - (time() - $lastActivity);
            
            $this->json([
                'success' => true,
                'timeRemaining' => max(0, $timeRemaining)
            ]);
        } else {
            $this->json(['success' => false, 'error' => 'Not authenticated'], 401);
        }
    }

    /**
     * Extend session lifetime
     */
    public function extendSession()
    {
        if (Session::extendSession()) {
            // Update last login when session is extended
            $userId = Session::get('user_id');
            if ($userId) {
                $userModel = new User();
                $userModel->updateLastLogin($userId);
            }
            
            $this->json([
                'success' => true,
                'message' => 'Session extended successfully',
                'timeRemaining' => defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600
            ]);
        } else {
            $this->json([
                'success' => false,
                'error' => 'Unable to extend session'
            ], 401);
        }
    }

    /**
     * Get session warning info (READ-ONLY - does not update activity)
     */
    public function getSessionWarning()
    {
        // Don't update activity - this is just a check
        if (!Session::isValid(false)) {
            $this->json(['warning' => false]);
            return;
        }
        
        $lastActivity = Session::get('_last_activity', time());
        $sessionLifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600;
        $warningTime = defined('SESSION_WARNING_TIME') ? SESSION_WARNING_TIME : 300; // 5 minutes
        $timeRemaining = $sessionLifetime - (time() - $lastActivity);
        
        $this->json([
            'warning' => $timeRemaining <= $warningTime,
            'timeRemaining' => max(0, $timeRemaining),
            'warningTime' => $warningTime
        ]);
    }

    /**
     * Get unread message count
     */
    public function getUnreadMessageCount()
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $messageModel = new Message();
        $count = $messageModel->getUnreadCount(Session::get('user_id'));
        
        $this->json(['count' => $count]);
    }

    /**
     * Get recent unread messages for header dropdown
     */
    public function getRecentMessages()
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $messageModel = new Message();
        $messages = $messageModel->getInboxMessages(Session::get('user_id'), 10, 0); // Get more messages to filter
        
        // Filter only unread messages and format for display
        $formattedMessages = [];
        foreach ($messages as $message) {
            // Only include unread messages
            if (!$message['is_read']) {
                $formattedMessages[] = [
                    'id' => $message['id'],
                    'subject' => $message['subject'],
                    'sender_name' => $message['sender_name'],
                    'sender_picture' => $message['sender_picture'],
                    'created_at' => $message['created_at'],
                    'is_read' => $message['is_read'],
                    'url' => APP_URL . '/messages/' . $message['id']
                ];
                
                // Limit to 5 unread messages
                if (count($formattedMessages) >= 5) {
                    break;
                }
            }
        }
        
        $this->json([
            'success' => true,
            'messages' => $formattedMessages,
            'count' => count($formattedMessages)
        ]);
    }

    /**
     * Get unread message count for header badge
     */
    public function getUnreadCount()
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $messageModel = new Message();
        $unreadCount = $messageModel->getUnreadCount(Session::get('user_id'));
        
        $this->json([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Validate module access based on user role
     */
    public function validateModuleAccess()
    {
        // Check if user is logged in
        if (!Session::has('user_id') || !Session::has('user_role')) {
            $this->json([
                'success' => false,
                'error' => 'Unauthorized',
                'message' => 'User not logged in'
            ], 401);
            return;
        }

        // Get the requested link/route from POST data
        $link = $this->input('link');
        
        if (!$link) {
            $this->json([
                'success' => false,
                'error' => 'Bad Request',
                'message' => 'Link parameter is required'
            ], 400);
            return;
        }

        // Get user role from session
        $userRole = Session::get('user_role');

        try {
            // Get module data by link
            $moduleModel = new Module();
            $module = $moduleModel->getByLink($link);
            
            // If module not found in database, allow access (backward compatibility)
            if (!$module) {
                $this->json([
                    'success' => true,
                    'allowed' => true,
                    'message' => 'Module not found in database, access allowed'
                ]);
                return;
            }
            
            // Check if user's role has access to this module
            $hasAccess = false;
            
            if (isset($module[$userRole])) {
                $hasAccess = (bool) $module[$userRole];
            }
            
            if ($hasAccess) {
                // User has access
                $this->json([
                    'success' => true,
                    'allowed' => true,
                    'message' => 'Access granted',
                    'module' => [
                        'caption' => $module['caption'] ?? 'Unknown',
                        'link' => $module['link'] ?? $link
                    ]
                ]);
            } else {
                // User does NOT have access
                $this->json([
                    'success' => true,
                    'allowed' => false,
                    'message' => 'Access denied',
                    'module' => [
                        'caption' => $module['caption'] ?? 'Unknown',
                        'link' => $module['link'] ?? $link
                    ]
                ]);
            }
            
        } catch (Exception $e) {
            // Error occurred
            $this->json([
                'success' => false,
                'error' => 'Internal Server Error',
                'message' => 'Failed to validate module access: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all menu items for search
     * Returns all active menu items with their module info
     * Filtered by user's active menu group
     */
    public function getMenuItems()
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
        }
        
        // Get user's active menu group from session
        $activeGroupId = Session::get('active_menu_group_id');
        
        if (!$activeGroupId) {
            $this->json([
                'success' => true,
                'data' => [],
                'total' => 0,
                'message' => 'No active menu group selected'
            ]);
            return;
        }
        
        try {
            require_once APP_PATH . '/app/models/MenuItem.php';
            require_once APP_PATH . '/app/models/Module.php';
            require_once APP_PATH . '/app/models/MenuGroup.php';
            
            $menuItemModel = new MenuItem();
            
            // Get menu items ONLY from user's active group
            $sql = "SELECT 
                        mi.id,
                        mi.name,
                        mi.icon,
                        mi.parent_id,
                        mi.is_parent,
                        mi.group_id,
                        m.link as url,
                        m.caption as module_name,
                        mg.name as group_name
                    FROM menu_items mi
                    LEFT JOIN modules m ON mi.module_id = m.id
                    LEFT JOIN menu_groups mg ON mi.group_id = mg.id
                    WHERE mi.is_active = 1 
                    AND mi.group_id = ?
                    ORDER BY mi.sort_order ASC";
            
            $db = Database::getInstance();
            $items = $db->fetchAll($sql, [$activeGroupId]);
            
            // Build menu structure with parent-child relationships
            $menuItems = [];
            $parentMap = [];
            
            foreach ($items as $item) {
                $menuItem = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'icon' => $item['icon'] ?? 'fas fa-circle',
                    'url' => $item['url'] ?? '#',
                    'group' => $item['group_name'] ?? 'Menu',
                    'is_parent' => (bool) $item['is_parent'],
                    'parent_id' => $item['parent_id']
                ];
                
                // Build breadcrumb path
                $path = [];
                
                if ($item['parent_id']) {
                    // This is a child item, find parent name
                    if (!isset($parentMap[$item['parent_id']])) {
                        // Load parent from database
                        $parentSql = "SELECT name FROM menu_items WHERE id = ?";
                        $parent = $db->fetch($parentSql, [$item['parent_id']]);
                        $parentMap[$item['parent_id']] = $parent['name'] ?? 'Parent';
                    }
                    $path[] = $parentMap[$item['parent_id']];
                }
                
                $path[] = $item['name'];
                $menuItem['path'] = implode(' > ', $path);
                
                // Only include items that have URLs (not just parent containers)
                if ($item['url'] && $item['url'] !== '#') {
                    $menuItems[] = $menuItem;
                }
            }
            
            $this->json([
                'success' => true,
                'data' => $menuItems,
                'total' => count($menuItems)
            ]);
            
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'error' => 'Failed to fetch menu items',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

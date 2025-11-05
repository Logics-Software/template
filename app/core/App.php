<?php
/**
 * Main Application Class
 */
class App
{
    private static $instance = null;
    private $router;
    private $request;
    private $response;

    private function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();
        $this->setupRoutes();
    }

    /**
     * Get singleton instance of App
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get router instance
     */
    public function getRouter()
    {
        return $this->router;
    }

    public function run()
    {
        try {
        // Start session
        Session::start();
        
        // Generate CSRF token if not exists (ONE TOKEN PER SESSION)
        if (!Session::get('_csrf_token')) {
            Session::generateCSRF();
        }
            
            // Check remember me cookie for auto-login
            Session::checkRememberMe();
            
            // Check and auto-refresh session if needed
            Session::checkAndRegenerate();
            
            // Handle CSRF protection
            // OPTION: Skip CSRF for AJAX requests (same-origin is safe) - More flexible
            // Set to false if you want strict CSRF for all requests
            $skipCSRFForAjax = true;
            
            if ($this->request->isPost()) {
                $uri = $this->request->uri();
                $isAjax = $this->request->isAjax();
                
                // Skip CSRF validation for:
                // 1. Public endpoints (login, register, etc)
                // 2. All API endpoints (usually have their own auth)
                // 3. All AJAX requests if skipCSRFForAjax is true (same-origin is safe)
                $skipCSRF = [
                    '/login',
                    '/register',
                    '/forgot-password',
                    '/select-menu-group',
                ];
                
                // Skip CSRF for all /api/* endpoints
                $isApiEndpoint = strpos($uri, '/api/') === 0;
                
                // Skip CSRF for all AJAX requests (same-origin protection is enough)
                $skipForAjax = $skipCSRFForAjax && $isAjax;
                
                // Validate CSRF only if not skipped
                if (!in_array($uri, $skipCSRF) && !$isApiEndpoint && !$skipForAjax) {
                    if (!$this->validateCSRF()) {
                        if ($isAjax) {
                            $this->response->json(['error' => 'CSRF token mismatch. Please refresh the page.'], 403);
                        } else {
                            Session::flash('error', 'CSRF token mismatch. Please try again.');
                            $this->response->redirect($this->request->uri());
                        }
                        return;
                    }
                }
            }

            // Route the request
            $this->router->dispatch($this->request, $this->response);
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    private function setupRoutes()
    {
        // Authentication routes
        $this->router->get('/', 'AuthController@login');
        $this->router->get('/login', 'AuthController@login');
        $this->router->post('/login', 'AuthController@authenticate');
        $this->router->get('/logout', 'AuthController@logout');
        $this->router->get('/register', 'AuthController@register');
        $this->router->post('/register', 'AuthController@store');
        $this->router->get('/forgot-password', 'AuthController@forgotPassword');
        $this->router->post('/forgot-password', 'AuthController@sendPasswordReset');
        $this->router->post('/select-menu-group', 'AuthController@setMenuGroup');
        
        // Lock Screen routes
        $this->router->get('/lock-screen', 'LockScreenController@index');
        $this->router->post('/unlock', 'LockScreenController@unlock');
        $this->router->get('/lock', 'LockScreenController@lock');
        
        // Dashboard routes
        $this->router->get('/dashboard', 'DashboardController@index');
        
        // API routes
        $this->router->get('/api/theme', 'ApiController@getTheme');
        $this->router->post('/api/theme', 'ApiController@setTheme');
        $this->router->get('/api/session-check', 'ApiController@checkSession');
        $this->router->post('/api/update-activity', 'ApiController@updateActivity');
        $this->router->post('/api/extend-session', 'ApiController@extendSession');
        $this->router->get('/api/session-warning', 'ApiController@getSessionWarning');
        $this->router->post('/api/validate-module-access', 'ApiController@validateModuleAccess');
        $this->router->get('/api/menu-items', 'ApiController@getMenuItems');
        
        // User management routes
        $this->router->get('/users', 'UserController@index');
        $this->router->get('/users/create', 'UserController@create');
        $this->router->post('/users', 'UserController@store');
        $this->router->get('/users/{id}', 'UserController@show');
        $this->router->get('/users/{id}/edit', 'UserController@edit');
        $this->router->put('/users/{id}', 'UserController@update');
        $this->router->delete('/users/{id}', 'UserController@destroy');
        $this->router->post('/users/{id}/activate', 'UserController@activateUser');
        $this->router->post('/users/{id}/deactivate', 'UserController@deactivateUser');
        $this->router->post('/users/{id}/reject', 'UserController@rejectUser');
        
        // Users Menu Access routes
        $this->router->get('/menuakses', 'UsersMenuController@index');
        $this->router->get('/menuakses/{id}/edit', 'UsersMenuController@edit');
        $this->router->post('/menuakses/{id}', 'UsersMenuController@update');
        $this->router->get('/menuakses/{id}/menu-groups', 'UsersMenuController@getUserMenuGroups');
        $this->router->post('/menuakses/add-menu-access', 'UsersMenuController@addMenuAccess');
        $this->router->post('/menuakses/remove-menu-access', 'UsersMenuController@removeMenuAccess');
        
        // Profile routes
        $this->router->get('/profile', 'UserController@profile');
        $this->router->post('/profile', 'UserController@updateProfile');
        
        // Change Password routes
        $this->router->get('/change-password', 'UserController@changePassword');
        $this->router->post('/change-password', 'UserController@updatePassword');
        
        // Konfigurasi routes
        $this->router->get('/konfigurasi', 'KonfigurasiController@index');
        $this->router->get('/konfigurasi/create', 'KonfigurasiController@create');
        $this->router->post('/konfigurasi', 'KonfigurasiController@store');
        $this->router->get('/konfigurasi/edit', 'KonfigurasiController@edit');
        $this->router->post('/konfigurasi/update', 'KonfigurasiController@update');
        $this->router->put('/konfigurasi/update', 'KonfigurasiController@update');
        
        // Call Center routes
        $this->router->get('/callcenter', 'CallCenterController@index');
        $this->router->get('/callcenter/create', 'CallCenterController@create');
        $this->router->post('/callcenter', 'CallCenterController@store');
        $this->router->get('/callcenter/{id}', 'CallCenterController@show');
        $this->router->get('/callcenter/{id}/edit', 'CallCenterController@edit');
        $this->router->put('/callcenter/{id}', 'CallCenterController@update');
        $this->router->post('/callcenter/{id}/delete', 'CallCenterController@delete');
        $this->router->post('/callcenter/update-sort', 'CallCenterController@updateSortOrder');
        
        // Analytics routes
        $this->router->get('/analytics', 'DashboardController@analytics');
        
        // Message/Chat routes
        $this->router->get('/messages', 'MessageController@index');
        $this->router->get('/messages/sent', 'MessageController@sent');
        $this->router->get('/messages/create', 'MessageController@create');
        $this->router->post('/messages', 'MessageController@store');
        $this->router->get('/messages/{id}', 'MessageController@show');
        $this->router->delete('/messages/{id}', 'MessageController@destroy');
        $this->router->get('/messages/search', 'MessageController@search');
        $this->router->get('/api/messages/unread-count', 'MessageController@getUnreadCount');
        $this->router->post('/api/messages/mark-read', 'MessageController@markAsRead');
        $this->router->post('/api/messages/mark-all-read', 'MessageController@markAllAsRead');
        $this->router->get('/api/messages/search-users', 'MessageController@searchUsers');
        $this->router->get('/api/messages/recent', 'ApiController@getRecentMessages');
        $this->router->get('/api/messages/count', 'ApiController@getUnreadMessageCount');
        
        // Module management routes
        $this->router->get('/modules', 'ModuleController@index');
        $this->router->get('/modules/create', 'ModuleController@create');
        $this->router->post('/modules', 'ModuleController@store');
        $this->router->get('/modules/{id}', 'ModuleController@show');
        $this->router->get('/modules/{id}/edit', 'ModuleController@edit');
        $this->router->put('/modules/{id}', 'ModuleController@update');
        $this->router->delete('/modules/{id}', 'ModuleController@destroy');

        // Menu management routes
        $this->router->get('/menu', 'MenuController@index');
        $this->router->get('/menu/builder', 'MenuController@builder');
        
        // Menu group routes
        $this->router->get('/menu/get-group/{id}', 'MenuController@getGroup');
        $this->router->get('/menu/get-group-items/{id}', 'MenuController@getGroupItems');
        $this->router->post('/menu/create-group', 'MenuController@createGroup');
        $this->router->post('/menu/update-group', 'MenuController@updateGroup');
        $this->router->post('/menu/delete-group', 'MenuController@deleteGroup');
        
        // Menu item routes
        $this->router->get('/menu/get-menu-item/{id}', 'MenuController@getMenuItem');
        $this->router->get('/menu/get-parent-items/{id}', 'MenuController@getParentItems');
        $this->router->post('/menu/create-menu-item', 'MenuController@createMenuItem');
        $this->router->post('/menu/update-menu-item', 'MenuController@updateMenuItem');
        $this->router->post('/menu/delete-menu-item', 'MenuController@deleteMenuItem');
        $this->router->post('/menu/update-menu-sort', 'MenuController@updateMenuItemSort');
        
        // Legacy menu module routes (for backward compatibility)
        $this->router->post('/menu/update-module', 'MenuController@updateMenuItem');
        $this->router->post('/menu/toggle-visibility', 'MenuController@toggleVisibility');
        $this->router->post('/menu/update-sort', 'MenuController@updateSortOrder');
        
        // Configuration routes
        $this->router->get('/menu/export-config', 'MenuController@exportConfig');
        $this->router->post('/menu/import-config', 'MenuController@importConfig');
        
        // Icon picker route
        $this->router->get('/menu/get-icons', 'MenuController@getIcons');
        
        // Main routes for module dropdown
        $this->router->get('/menu/get-main-routes', 'MenuController@getMainRoutes');
    }

    private function validateCSRF()
    {
        // Simplified: Since JSON data is merged into $this->data in Request,
        // input('_token') will work for both form data and JSON body
        // Only need one source: input('_token') - covers form, JSON, and all POST data
        $token = $this->request->input('_token');
        
        return Session::validateCSRF($token);
    }

    private function handleError($e)
    {
        if (APP_DEBUG) {
            $this->response->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        } else {
            $this->response->json(['error' => 'Internal Server Error'], 500);
        }
    }
}

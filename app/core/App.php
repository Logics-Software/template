<?php
/**
 * Main Application Class
 */
class App
{
    private $router;
    private $request;
    private $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();
        $this->setupRoutes();
    }

    public function run()
    {
        try {
            // Start session
            Session::start();
            
            // Check and auto-refresh session if needed
            Session::checkAndRegenerate();
            
            // Handle CSRF protection
            if ($this->request->isPost()) {
                if (!$this->validateCSRF()) {
                    $this->response->json(['error' => 'CSRF token mismatch'], 403);
                    return;
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
        $this->router->post('/api/extend-session', 'ApiController@extendSession');
        $this->router->get('/api/session-warning', 'ApiController@getSessionWarning');
        
        // User management routes
        $this->router->get('/users', 'UserController@index');
        $this->router->get('/users/create', 'UserController@create');
        $this->router->post('/users', 'UserController@store');
        $this->router->get('/users/{id}', 'UserController@show');
        $this->router->get('/users/{id}/edit', 'UserController@edit');
        $this->router->put('/users/{id}', 'UserController@update');
        $this->router->delete('/users/{id}', 'UserController@destroy');
        $this->router->post('/users/{id}/activate', 'UserController@activateUser');
        $this->router->post('/users/{id}/reject', 'UserController@rejectUser');
        
        // Settings routes
        $this->router->get('/settings', 'UserController@settings');
        $this->router->post('/settings', 'UserController@updateSettings');
        
        // Profile routes
        $this->router->get('/profile', 'UserController@profile');
        $this->router->post('/profile', 'UserController@updateProfile');
        $this->router->get('/profile/updated', 'UserController@profileUpdated');
        
        // Change Password routes
        $this->router->get('/change-password', 'UserController@changePassword');
        $this->router->post('/change-password', 'UserController@updatePassword');
        $this->router->get('/change-password/updated', 'UserController@passwordUpdated');
        
        // Konfigurasi routes
        $this->router->get('/konfigurasi', 'KonfigurasiController@index');
        $this->router->get('/konfigurasi/create', 'KonfigurasiController@create');
        $this->router->post('/konfigurasi', 'KonfigurasiController@store');
        $this->router->get('/konfigurasi/edit', 'KonfigurasiController@edit');
        $this->router->post('/konfigurasi/update', 'KonfigurasiController@update');
        $this->router->put('/konfigurasi/update', 'KonfigurasiController@update');
        
        // Analytics routes
        $this->router->get('/analytics', 'DashboardController@analytics');
        
        // Fallback route for debugging
        $this->router->get('/debug', function($request, $response) {
            $response->json([
                'request_uri' => $_SERVER['REQUEST_URI'] ?? 'not set',
                'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'not set',
                'method' => $request->method(),
                'uri' => $request->uri(),
                'routes' => 'Check if routes are properly registered'
            ]);
        });
    }

    private function validateCSRF()
    {
        // Try multiple token names for compatibility
        $token = $this->request->input('_token') ?: $this->request->input('csrf_token');
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

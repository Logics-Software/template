<?php
/**
 * Middleware class for handling common authentication and authorization
 */
class Middleware
{
    /**
     * Check if user is authenticated
     */
    public static function auth()
    {
        // Check if user session exists
        if (!Session::has('user_id')) {
            if (self::isAjaxRequest()) {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Unauthorized', 'redirect' => BASE_URL . 'login']);
                exit;
            } else {
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
        }
        
        // Check if session is still valid (not expired)
        // Pass true to update activity on page loads (real user navigation)
        if (!Session::isValid(true)) {
            // Session expired - destroy and redirect to login
            Session::destroy();
            if (self::isAjaxRequest()) {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => 'Session expired', 
                    'redirect' => BASE_URL . 'login',
                    'message' => 'Sesi Anda telah berakhir. Silakan login kembali.'
                ]);
                exit;
            } else {
                Session::flash('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
        }
        
        return true;
    }

    /**
     * Check if user is guest (not authenticated)
     */
    public static function guest()
    {
        if (Session::has('user_id')) {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }
        return true;
    }

    /**
     * Check if user is admin
     */
    public static function admin()
    {
        self::auth(); // Ensure user is authenticated first
        
        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            if (self::isAjaxRequest()) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Forbidden', 'message' => 'Admin access required']);
                exit;
            } else {
                Session::flash('error', 'Admin access required');
                header('Location: ' . BASE_URL . 'dashboard');
                exit;
            }
        }
        return true;
    }

    /**
     * Check if user has specific permission
     */
    public static function permission($permission)
    {
        self::auth(); // Ensure user is authenticated first
        
        if (!self::hasPermission($permission)) {
            if (self::isAjaxRequest()) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Forbidden', 'message' => 'You do not have permission to access this resource']);
                exit;
            } else {
                Session::flash('error', 'You do not have permission to access this resource');
                header('Location: ' . BASE_URL . 'dashboard');
                exit;
            }
        }
        return true;
    }

    /**
     * Check if user has specific role
     */
    public static function role($role)
    {
        self::auth(); // Ensure user is authenticated first
        
        $userRole = Session::get('user_role', 'user');
        if ($userRole !== $role) {
            if (self::isAjaxRequest()) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Forbidden', 'message' => 'Access denied for your role']);
                exit;
            } else {
                Session::flash('error', 'Access denied for your role');
                header('Location: ' . BASE_URL . 'dashboard');
                exit;
            }
        }
        return true;
    }

    /**
     * Validate CSRF token
     */
    public static function csrf($token = null)
    {
        $token = $token ?: ($_POST['_token'] ?? $_GET['_token'] ?? '');
        
        if (!Session::validateCSRF($token)) {
            if (self::isAjaxRequest()) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'CSRF token mismatch']);
                exit;
            } else {
                Session::flash('error', 'Invalid security token');
                header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL);
                exit;
            }
        }
        return true;
    }

    /**
     * Rate limiting middleware
     */
    public static function rateLimit($maxRequests = 60, $timeWindow = 60)
    {
        $key = 'rate_limit_' . md5($_SERVER['REMOTE_ADDR'] . $_SERVER['REQUEST_URI']);
        $current = Session::get($key, 0);
        $windowStart = Session::get($key . '_start', time());
        
        // Reset counter if time window has passed
        if (time() - $windowStart > $timeWindow) {
            $current = 0;
            $windowStart = time();
        }
        
        if ($current >= $maxRequests) {
            if (self::isAjaxRequest()) {
                http_response_code(429);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Too many requests', 'retry_after' => $timeWindow]);
                exit;
            } else {
                Session::flash('error', 'Too many requests. Please try again later.');
                header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL);
                exit;
            }
        }
        
        // Increment counter
        Session::set($key, $current + 1);
        Session::set($key . '_start', $windowStart);
        
        return true;
    }

    /**
     * Check if request is AJAX
     */
    private static function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Check if user has specific permission
     */
    private static function hasPermission($permission)
    {
        // This would integrate with your permission system
        // For now, return true for authenticated users
        return true;
    }

    /**
     * Apply multiple middleware
     */
    public static function apply(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            if (is_string($middleware)) {
                self::$middleware();
            } elseif (is_array($middleware)) {
                $method = array_shift($middleware);
                self::$method(...$middleware);
            }
        }
        return true;
    }
}

<?php
/**
 * Session management class
 */
class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_set_cookie_params(SESSION_LIFETIME);
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        session_destroy();
    }

    public static function regenerate()
    {
        session_regenerate_id(true);
    }

    public static function generateCSRF()
    {
        $token = bin2hex(random_bytes(32));
        self::set('_csrf_token', $token);
        return $token;
    }

    public static function validateCSRF($token)
    {
        $sessionToken = self::get('_csrf_token');
        
        // Check if both tokens exist and are strings
        if (!$sessionToken || !$token || !is_string($sessionToken) || !is_string($token)) {
            return false;
        }
        
        // Validate token - allow multiple uses within the same session
        // Token will be regenerated when session is refreshed or regenerated
        return hash_equals($sessionToken, $token);
    }

    public static function flash($key, $value = null)
    {
        if ($value === null) {
            $value = self::get($key);
            self::remove($key);
            return $value;
        }
        
        self::set("_flash_{$key}", $value);
    }

    public static function getFlash($key, $default = null)
    {
        $value = self::get("_flash_{$key}", $default);
        self::remove("_flash_{$key}");
        return $value;
    }

    /**
     * Check and regenerate session if needed
     */
    public static function checkAndRegenerate()
    {
        if (self::has('user_id')) {
            $lastRegeneration = self::get('_last_regeneration', 0);
            $refreshInterval = defined('SESSION_REFRESH_INTERVAL') ? SESSION_REFRESH_INTERVAL : 1800; // 30 minutes default
            
            if ((time() - $lastRegeneration) > $refreshInterval) {
                self::regenerate();
                self::set('_last_regeneration', time());
                return true; // Session was refreshed
            }
        }
        return false; // Session was not refreshed
    }

    /**
     * Extend session lifetime
     */
    public static function extendSession()
    {
        if (self::has('user_id')) {
            // Update session cookie with new lifetime
            session_set_cookie_params(SESSION_LIFETIME);
            
            // Update last regeneration time
            self::set('_last_regeneration', time());
            
            // Regenerate session ID for security
            self::regenerate();
            
            return true;
        }
        return false;
    }

    /**
     * Check if session is valid and not expired (READ-ONLY)
     * Does NOT update last activity - use updateActivity() for that
     */
    public static function isValid($updateActivity = false)
    {
        if (!self::has('user_id')) {
            return false;
        }
        
        $lastActivity = self::get('_last_activity', time());
        $sessionLifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600;
        
        // Check if session has expired
        if ((time() - $lastActivity) > $sessionLifetime) {
            self::destroy();
            return false;
        }
        
        // Only update last activity if explicitly requested (for real user activity)
        if ($updateActivity) {
            self::set('_last_activity', time());
        }
        
        return true;
    }
    
    /**
     * Update last activity timestamp (for real user interactions)
     */
    public static function updateActivity()
    {
        if (self::has('user_id')) {
            self::set('_last_activity', time());
            return true;
        }
        return false;
    }

    /**
     * Set remember me cookie for persistent login
     */
    public static function setRememberMe($userId)
    {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + (30 * 24 * 60 * 60); // 30 days
        
        // Set cookie with secure options
        setcookie('remember_token', $token, $expiry, '/', '', true, true); // secure, httponly
        setcookie('remember_user', $userId, $expiry, '/', '', true, true); // secure, httponly
        
        // Store token hash in database for validation
        try {
            $db = Database::getInstance();
            $db->query("INSERT INTO remember_tokens (user_id, token_hash, expires_at, created_at) VALUES (?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE token_hash = VALUES(token_hash), expires_at = VALUES(expires_at), created_at = NOW()", 
                [$userId, password_hash($token, PASSWORD_DEFAULT), date('Y-m-d H:i:s', $expiry)]);
        } catch (Exception $e) {
            // If database fails, fall back to session storage
            self::set('_remember_token', password_hash($token, PASSWORD_DEFAULT));
            self::set('_remember_user', $userId);
            self::set('_remember_expiry', $expiry);
        }
    }

    /**
     * Check remember me cookie and auto-login if valid
     */
    public static function checkRememberMe()
    {
        if (self::has('user_id')) {
            return true; // Already logged in
        }

        if (!isset($_COOKIE['remember_token']) || !isset($_COOKIE['remember_user'])) {
            return false;
        }

        $token = $_COOKIE['remember_token'];
        $userId = $_COOKIE['remember_user'];

        // Verify token from database first
        try {
            $db = Database::getInstance();
            $stmt = $db->query("SELECT token_hash FROM remember_tokens WHERE user_id = ? AND expires_at > NOW()", [$userId]);
            $result = $stmt->fetch();
            
            if ($result && password_verify($token, $result['token_hash'])) {
                // Auto-login user
                return self::autoLogin($userId);
            }
        } catch (Exception $e) {
            // Fall back to session storage if database fails
            if (self::has('_remember_token') && self::has('_remember_user') && self::has('_remember_expiry')) {
                $storedToken = self::get('_remember_token');
                $storedUser = self::get('_remember_user');
                $expiry = self::get('_remember_expiry');

                // Check if token is valid and not expired
                if (password_verify($token, $storedToken) && $storedUser == $userId && time() < $expiry) {
                    // Auto-login user
                    return self::autoLogin($userId);
                }
            }
        }

        // Clear invalid remember me cookies
        self::clearRememberMe();
        return false;
    }

    /**
     * Auto-login user from remember me token
     */
    private static function autoLogin($userId)
    {
        try {
            // Load user data from database
            $userModel = new User();
            $user = $userModel->find($userId);
            
            if (!$user || !$userModel->canLogin($user)) {
                self::clearRememberMe();
                return false;
            }

            // Set session data
            self::set('user_id', $user['id']);
            self::set('user_name', $user['namalengkap']);
            self::set('user_email', $user['email']);
            self::set('user_role', $user['role']);
            self::set('user_username', $user['username']);
            self::set('user_picture', $user['picture']);
            
            // Update last activity and login time
            self::set('_last_activity', time());
            $userModel->updateLastLogin($user['id']);
            
            // Create login log for auto-login
            try {
                $loginLogModel = new LoginLog();
                $sessionToken = bin2hex(random_bytes(32)); // Generate session token using same pattern as remember token
                $ipAddress = self::getClientIp();
                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
                
                $loginLogId = $loginLogModel->createLog($user['id'], $sessionToken, $ipAddress, $userAgent);
                
                // Store session token in session for logout tracking
                self::set('_session_token', $sessionToken);
            } catch (Exception $e) {
                // Log error but don't break auto-login flow
                error_log("Auto-login log creation error: " . $e->getMessage());
            }
            
            // Regenerate session for security
            self::regenerate();
            
            return true;
        } catch (Exception $e) {
            self::clearRememberMe();
            return false;
        }
    }

    /**
     * Clear remember me cookies and session data
     */
    public static function clearRememberMe()
    {
        // Clear cookies
        setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        setcookie('remember_user', '', time() - 3600, '/', '', true, true);
        
        // Clear from database if user is logged in
        if (self::has('user_id')) {
            try {
                $db = Database::getInstance();
                $db->query("DELETE FROM remember_tokens WHERE user_id = ?", [self::get('user_id')]);
            } catch (Exception $e) {
                // Ignore database errors
            }
        }
        
        // Clear session data
        self::remove('_remember_token');
        self::remove('_remember_user');
        self::remove('_remember_expiry');
    }

    /**
     * Get client IP address (static version for Session class)
     * @return string IP address
     */
    private static function getClientIp()
    {
        $ipKeys = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($ipKeys as $key) {
            if (isset($_SERVER[$key]) && !empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                
                // Handle comma-separated IPs (from proxies)
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }
                
                // Validate IP
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        // Fallback to REMOTE_ADDR
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Logout and clear remember me
     */
    public static function logout()
    {
        // Get session token and user ID BEFORE destroying session
        $sessionToken = self::get('_session_token');
        $userId = self::get('user_id');
        
        // Update logout time in login log
        if ($sessionToken || $userId) {
            try {
                $loginLogModel = new LoginLog();
                // Pass both token and user_id for better matching
                $result = $loginLogModel->updateLogout($sessionToken, $userId);
                
                if (!$result) {
                    // Log if update failed (might be already logged out or token not found)
                    error_log("Logout log update failed. Token: " . substr($sessionToken ?? '', 0, 10) . "..., UserID: " . ($userId ?? 'N/A'));
                }
            } catch (Exception $e) {
                // Log error but don't break logout flow
                error_log("Logout log update error: " . $e->getMessage());
            }
        } else {
            // Log if both session token and user_id are missing
            error_log("Logout: Session token and user_id not found in session");
        }
        
        self::clearRememberMe();
        self::destroy();
    }
}

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
     * Check if session is valid and not expired
     */
    public static function isValid()
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
        
        // Update last activity
        self::set('_last_activity', time());
        return true;
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
     * Logout and clear remember me
     */
    public static function logout()
    {
        self::clearRememberMe();
        self::destroy();
    }
}

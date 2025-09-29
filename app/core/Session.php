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
}

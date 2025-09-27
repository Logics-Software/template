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
        return $sessionToken && hash_equals($sessionToken, $token);
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
}

<?php
/**
 * Simple caching system for improved performance
 */
class Cache
{
    private static $cache = [];
    private static $fileCache = [];
    private static $cacheDir;
    
    public static function init()
    {
        self::$cacheDir = __DIR__ . '/../../cache/';
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0755, true);
        }
    }
    
    /**
     * Store data in memory cache
     */
    public static function put($key, $value, $ttl = 3600)
    {
        self::$cache[$key] = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
    }
    
    /**
     * Get data from memory cache
     */
    public static function get($key, $default = null)
    {
        if (!isset(self::$cache[$key])) {
            return $default;
        }
        
        $item = self::$cache[$key];
        if (time() > $item['expires']) {
            unset(self::$cache[$key]);
            return $default;
        }
        
        return $item['value'];
    }
    
    /**
     * Check if cache key exists and is valid
     */
    public static function has($key)
    {
        if (!isset(self::$cache[$key])) {
            return false;
        }
        
        $item = self::$cache[$key];
        if (time() > $item['expires']) {
            unset(self::$cache[$key]);
            return false;
        }
        
        return true;
    }
    
    /**
     * Remove item from cache
     */
    public static function forget($key)
    {
        unset(self::$cache[$key]);
        $cacheFile = self::$cacheDir . md5($key) . '.cache';
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }
    
    /**
     * Clear all cache
     */
    public static function flush()
    {
        self::$cache = [];
        $files = glob(self::$cacheDir . '*.cache');
        foreach ($files as $file) {
            unlink($file);
        }
    }
    
    /**
     * Remember cache - get from cache or execute callback and store
     */
    public static function remember($key, $callback, $ttl = 3600)
    {
        $value = self::get($key);
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        self::put($key, $value, $ttl);
        
        return $value;
    }
    
    /**
     * Remember cache with file storage
     */
    public static function rememberFile($key, $callback, $ttl = 3600)
    {
        $cacheFile = self::$cacheDir . md5($key) . '.cache';
        
        // Check if file exists and is valid
        if (file_exists($cacheFile)) {
            $data = unserialize(file_get_contents($cacheFile));
            if ($data['expires'] > time()) {
                return $data['value'];
            } else {
                unlink($cacheFile);
            }
        }
        
        // Execute callback and store result
        $value = $callback();
        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
        
        file_put_contents($cacheFile, serialize($data));
        return $value;
    }
    
    /**
     * Cache database query result
     */
    public static function rememberQuery($key, $callback, $ttl = 1800)
    {
        return self::remember("query_" . $key, $callback, $ttl);
    }
    
    /**
     * Cache model data
     */
    public static function rememberModel($model, $method, $params, $callback, $ttl = 1800)
    {
        $key = $model . "_" . $method . "_" . md5(serialize($params));
        return self::remember("model_" . $key, $callback, $ttl);
    }
    
    /**
     * Invalidate cache by pattern
     */
    public static function invalidatePattern($pattern)
    {
        foreach (self::$cache as $key => $item) {
            if (self::matchPattern($pattern, $key)) {
                unset(self::$cache[$key]);
            }
        }
        
        $files = glob(self::$cacheDir . '*.cache');
        foreach ($files as $file) {
            $data = unserialize(file_get_contents($file));
            if (self::matchPattern($pattern, $data['key'] ?? '')) {
                unlink($file);
            }
        }
    }
    
    /**
     * Simple pattern matching (replacement for fnmatch)
     */
    private static function matchPattern($pattern, $string)
    {
        // Simple wildcard matching
        $pattern = str_replace('*', '.*', $pattern);
        $pattern = str_replace('?', '.', $pattern);
        return preg_match('/^' . $pattern . '$/', $string);
    }
    
    /**
     * Get cache statistics
     */
    public static function getStats()
    {
        $totalItems = count(self::$cache);
        $totalSize = 0;
        
        foreach (self::$cache as $item) {
            $totalSize += strlen(serialize($item['value']));
        }
        
        $files = glob(self::$cacheDir . '*.cache');
        $fileCount = count($files);
        $fileSize = 0;
        
        foreach ($files as $file) {
            $fileSize += filesize($file);
        }
        
        return [
            'memory_items' => $totalItems,
            'memory_size' => $totalSize,
            'file_items' => $fileCount,
            'file_size' => $fileSize,
            'total_size' => $totalSize + $fileSize
        ];
    }
}

// Initialize cache system
Cache::init();

<?php
/**
 * Autoloader class for automatic class loading
 */
class Autoloader
{
    private $directories = [
        'app/core/',
        'app/models/',
        'app/controllers/',
        'app/services/',
        'app/views/',
        'app/helpers/',
        'app/middleware/',
        'app/config/'
    ];

    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    public function loadClass($className)
    {
        foreach ($this->directories as $directory) {
            $file = APP_PATH . '/' . $directory . $className . '.php';
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
        return false;
    }
}

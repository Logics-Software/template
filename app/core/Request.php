<?php
/**
 * Request class for handling HTTP requests
 */
class Request
{
    private $data = [];

    public function __construct()
    {
        $this->data = array_merge($_GET, $_POST, $_FILES);
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function uri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = $uri ?? '/';
        
        // Normalize double slashes and trailing slashes
        $uri = preg_replace('#/+#', '/', $uri);
        $uri = rtrim($uri, '/') ?: '/';
        
        return $uri;
    }

    public function isGet()
    {
        return $this->method() === 'GET';
    }

    public function isPost()
    {
        return $this->method() === 'POST';
    }

    public function isPut()
    {
        return $this->method() === 'PUT' || ($this->isPost() && $this->input('_method') === 'PUT');
    }

    public function isDelete()
    {
        return $this->method() === 'DELETE' || ($this->isPost() && $this->input('_method') === 'DELETE');
    }

    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function input($key = null, $default = null)
    {
        if ($key === null) {
            return $this->data;
        }
        
        return $this->data[$key] ?? $default;
    }

    public function get($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        
        return $_GET[$key] ?? $default;
    }

    public function post($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        
        return $_POST[$key] ?? $default;
    }

    public function file($key = null)
    {
        if ($key === null) {
            return $_FILES;
        }
        
        return $_FILES[$key] ?? null;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function only($keys)
    {
        $result = [];
        foreach ($keys as $key) {
            if (isset($this->data[$key])) {
                $result[$key] = $this->data[$key];
            }
        }
        return $result;
    }

    public function except($keys)
    {
        $result = $this->data;
        foreach ($keys as $key) {
            unset($result[$key]);
        }
        return $result;
    }

    public function validate($rules)
    {
        $validator = new Validator($this->data, $rules);
        return $validator;
    }
}

<?php
/**
 * Request class for handling HTTP requests
 */
class Request
{
    private $data = [];
    private $jsonData = null;
    private $parsedJsonData = null;
    
    public function __construct()
    {
        $this->data = array_merge($_GET, $_POST, $_FILES);
        
        // Handle JSON requests
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $jsonData = file_get_contents('php://input');
            $this->jsonData = $jsonData; // Store raw JSON for later use
            $decodedData = json_decode($jsonData, true);
            if ($decodedData !== null) {
                $this->parsedJsonData = $decodedData; // Store parsed data
                $this->data = array_merge($this->data, $decodedData);
            }
        }
        
        // Handle PUT/DELETE requests and POST with _method
        $actualMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        if ($actualMethod === 'PUT' || $actualMethod === 'DELETE') {
            // Real PUT/DELETE request - parse from php://input
            if (strpos($contentType, 'application/json') === false) {
                parse_str(file_get_contents('php://input'), $putData);
                $this->data = array_merge($this->data, $putData);
            }
        } elseif ($actualMethod === 'POST' && isset($_POST['_method'])) {
            // POST with method spoofing - data is already in $_POST
            // No need to parse from php://input
        }
    }

    public function method()
    {
        // Support method spoofing via _method parameter
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method'])) {
            return strtoupper($_POST['_method']);
        }
        
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function uri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = $uri ?? '/';
        
        // Remove sub-folder path if exists (dynamic detection for /app/template/)
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $scriptDir = dirname($scriptName);
        
        // If script is in a subdirectory, remove that path from URI
        if ($scriptDir !== '/' && $scriptDir !== '.') {
            if (strpos($uri, $scriptDir) === 0) {
                $uri = substr($uri, strlen($scriptDir));
            }
        }
        
        // Handle query parameters
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        
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

    public function header($name)
    {
        // Normalize header name
        $name = strtoupper(str_replace('-', '_', $name));
        
        // Try different variations
        $variations = [
            'HTTP_' . $name,
            $name,
            'X_' . $name
        ];
        
        foreach ($variations as $headerName) {
            if (isset($_SERVER[$headerName]) && !empty($_SERVER[$headerName])) {
                return $_SERVER[$headerName];
            }
        }
        
        return null;
    }

    public function getAllHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headerName = str_replace('_', '-', substr($key, 5));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }

    public function all()
    {
        return $this->data;
    }

    /**
     * Get JSON data from request body
     */
    public function json($key = null, $default = null)
    {
        // Use pre-parsed JSON data if available
        if ($this->parsedJsonData !== null) {
            if ($key === null) {
                return $this->parsedJsonData;
            }
            return $this->parsedJsonData[$key] ?? $default;
        }
        
        // For JSON requests, data is already merged into $this->data
        // So we can access it directly
        if ($key === null) {
            return $this->data;
        }
        
        return $this->data[$key] ?? $default;
    }

    /**
     * Check if request contains JSON data
     */
    public function isJson()
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        return strpos($contentType, 'application/json') !== false;
    }
}

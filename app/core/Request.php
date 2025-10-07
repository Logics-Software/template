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

    public function method(): string
    {
        // Support method spoofing via _method parameter
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            return strtoupper($_POST['_method']);
        }
        
        return $requestMethod;
    }

    public function uri(): string
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

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isPut(): bool
    {
        return $this->method() === 'PUT' || ($this->isPost() && $this->input('_method') === 'PUT');
    }

    public function isDelete(): bool
    {
        return $this->method() === 'DELETE' || ($this->isPost() && $this->input('_method') === 'DELETE');
    }

    public function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function input(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->data;
        }
        
        return $this->data[$key] ?? $default;
    }

    public function get(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $_GET;
        }
        
        return $_GET[$key] ?? $default;
    }

    public function post(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $_POST;
        }
        
        return $_POST[$key] ?? $default;
    }

    public function file(?string $key = null): ?array
    {
        if ($key === null) {
            return $_FILES;
        }
        
        return $_FILES[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function only(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            if (isset($this->data[$key])) {
                $result[$key] = $this->data[$key];
            }
        }
        return $result;
    }

    public function except(array $keys): array
    {
        $result = $this->data;
        foreach ($keys as $key) {
            unset($result[$key]);
        }
        return $result;
    }

    public function validate(array $rules): Validator
    {
        $validator = new Validator($this->data, $rules);
        return $validator;
    }

    public function header(string $name): ?string
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

    public function getAllHeaders(): array
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

    public function all(): array
    {
        return $this->data;
    }

    /**
     * Get JSON data from request body
     */
    public function json(?string $key = null, mixed $default = null): mixed
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
    public function isJson(): bool
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        return strpos($contentType, 'application/json') !== false;
    }
}

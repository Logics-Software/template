<?php
/**
 * Router class for handling HTTP requests
 */
class Router
{
    private $routes = [];
    private $middleware = [];
    private $routeParams = [];

    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put($path, $handler)
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete($path, $handler)
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch(Request $request, Response $response)
    {
        $method = $request->method();
        $uri = $request->uri();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $uri)) {
                $this->executeHandler($route['handler'], $request, $response);
                return;
            }
        }

        // 404 Not Found
        $response->json(['error' => 'Route not found'], 404);
    }

    private function matchPath($pattern, $uri)
    {
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $uri, $matches)) {
            // Store route parameters for later use
            $this->routeParams = array_slice($matches, 1);
            return true;
        }
        
        return false;
    }

    public function getRouteParams()
    {
        return $this->routeParams;
    }

    private function executeHandler($handler, Request $request, Response $response)
    {
        // Apply middleware if exists
        if (isset($this->middleware[$handler])) {
            foreach ($this->middleware[$handler] as $middleware) {
                if (!$this->executeMiddleware($middleware, $request, $response)) {
                    return; // Middleware blocked the request
                }
            }
        }
        
        if (is_string($handler)) {
            list($controller, $method) = explode('@', $handler);
            $controllerClass = $controller;
            
            if (!class_exists($controllerClass)) {
                throw new Exception("Controller {$controllerClass} not found");
            }
            
            $controllerInstance = new $controllerClass();
            
            if (!method_exists($controllerInstance, $method)) {
                throw new Exception("Method {$method} not found in {$controllerClass}");
            }
            
            // Pass route parameters to controller
            $controllerInstance->$method($request, $response, $this->routeParams);
        } else {
            call_user_func($handler, $request, $response);
        }
    }
    
    private function executeMiddleware($middleware, Request $request, Response $response)
    {
        switch ($middleware) {
            case 'auth':
                Middleware::auth();
                break;
            case 'guest':
                Middleware::guest();
                break;
            case 'admin':
                Middleware::admin();
                break;
            case 'csrf':
                Middleware::csrf();
                break;
            default:
                return true;
        }
        return true;
    }
    
    public function middleware($middleware, $method, $path)
    {
        $routeKey = $method . ':' . $path;
        if (!isset($this->middleware[$routeKey])) {
            $this->middleware[$routeKey] = [];
        }
        $this->middleware[$routeKey][] = $middleware;
        return $this;
    }

    /**
     * Get all registered routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}

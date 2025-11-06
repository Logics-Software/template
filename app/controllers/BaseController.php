<?php
/**
 * Base Controller class
 */
abstract class BaseController
{
    protected $request;
    protected $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    protected function view($template, $data = [])
    {
        // AUTOMATIC CSRF TOKEN INJECTION
        // Setiap view otomatis mendapat csrf_token jika belum ada
        if (!isset($data['csrf_token'])) {
            $data['csrf_token'] = $this->csrfToken();
        }
        
        $this->response->view($template, $data);
    }

    protected function json($data, $statusCode = 200)
    {
        $this->response->json($data, $statusCode);
    }

    protected function redirect($url)
    {
        $this->response->redirect($url);
    }

    protected function validate($rules)
    {
        return $this->request->validate($rules);
    }

    protected function input($key = null, $default = null)
    {
        return $this->request->input($key, $default);
    }

    protected function isAjax()
    {
        return $this->request->isAjax();
    }

    protected function csrfToken()
    {
        // Get existing token instead of generating new one
        // This ensures same token across all views in same request
        $token = Session::get('_csrf_token');
        if (!$token) {
            $token = Session::generateCSRF();
        }
        return $token;
    }

    protected function validateCSRF($request = null)
    {
        $request = $request ?: $this->request;
        
        // Try multiple token sources for compatibility
        $token = $request->input('_token') 
                ?: $request->input('csrf_token')
                ?: $request->header('X-CSRF-Token')
                ?: $request->header('X-CSRF-TOKEN');
        
        return Session::validateCSRF($token);
    }

    protected function flash($key, $value = null)
    {
        if ($value === null) {
            return Session::getFlash($key);
        }
        Session::flash($key, $value);
    }

    protected function withErrors($errors)
    {
        Session::flash('errors', $errors);
    }

    protected function withSuccess($message, $redirect = null)
    {
        Notify::success($message, $redirect);
    }

    protected function withError($message, $redirect = null)
    {
        Notify::error($message, $redirect);
    }

    protected function withWarning($message, $redirect = null)
    {
        Notify::warning($message, $redirect);
    }

    protected function withInfo($message, $redirect = null)
    {
        Notify::info($message, $redirect);
    }

    /**
     * Get client IP address
     * @return string IP address
     */
    protected function getClientIp()
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
}

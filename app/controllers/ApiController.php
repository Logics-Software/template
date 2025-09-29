<?php
/**
 * API Controller for AJAX requests
 */
class ApiController extends BaseController
{
    public function getTheme()
    {
        $theme = $_COOKIE[THEME_COOKIE_NAME] ?? DEFAULT_THEME;
        $this->json(['theme' => $theme]);
    }

    public function setTheme()
    {
        $theme = $this->input('theme', DEFAULT_THEME);
        
        if (!in_array($theme, ['light', 'dark', 'auto'])) {
            $this->json(['error' => 'Invalid theme'], 400);
        }

        setcookie(THEME_COOKIE_NAME, $theme, time() + (365 * 24 * 60 * 60), '/');
        $this->json(['success' => true, 'theme' => $theme]);
    }

    public function getStats()
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $statsModel = new Stats();
        $stats = [
            'total_customers' => $statsModel->getTotalCustomers(),
            'total_revenue' => $statsModel->getTotalRevenue(),
            'conversion_rate' => $statsModel->getConversionRate(),
            'pending_tasks' => $statsModel->getPendingTasks()
        ];

        $this->json($stats);
    }

    public function searchUsers()
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $query = $this->input('q', '');
        $page = (int) $this->input('page', 1);
        $perPage = (int) $this->input('per_page', DEFAULT_PAGE_SIZE);

        $userModel = new User();
        $result = $userModel->search($query, ['name', 'email'], 'status = :status', ['status' => 'active']);
        
        $this->json([
            'data' => $result,
            'total' => count($result),
            'page' => $page,
            'per_page' => $perPage
        ]);
    }

    /**
     * Check session validity
     */
    public function checkSession()
    {
        $isValid = Session::isValid();
        $timeRemaining = 0;
        
        if ($isValid) {
            $lastActivity = Session::get('_last_activity', time());
            $sessionLifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600;
            $timeRemaining = $sessionLifetime - (time() - $lastActivity);
        }
        
        $this->json([
            'valid' => $isValid,
            'timeRemaining' => max(0, $timeRemaining),
            'user' => $isValid ? [
                'id' => Session::get('user_id'),
                'name' => Session::get('user_name'),
                'email' => Session::get('user_email'),
                'role' => Session::get('user_role')
            ] : null
        ]);
    }

    /**
     * Extend session lifetime
     */
    public function extendSession()
    {
        if (Session::extendSession()) {
            // Update last login when session is extended
            $userId = Session::get('user_id');
            if ($userId) {
                $userModel = new User();
                $userModel->updateLastLogin($userId);
            }
            
            $this->json([
                'success' => true,
                'message' => 'Session extended successfully',
                'timeRemaining' => defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600
            ]);
        } else {
            $this->json([
                'success' => false,
                'error' => 'Unable to extend session'
            ], 401);
        }
    }

    /**
     * Get session warning info
     */
    public function getSessionWarning()
    {
        if (!Session::isValid()) {
            $this->json(['warning' => false]);
            return;
        }
        
        $lastActivity = Session::get('_last_activity', time());
        $sessionLifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600;
        $warningTime = defined('SESSION_WARNING_TIME') ? SESSION_WARNING_TIME : 300; // 5 minutes
        $timeRemaining = $sessionLifetime - (time() - $lastActivity);
        
        $this->json([
            'warning' => $timeRemaining <= $warningTime,
            'timeRemaining' => max(0, $timeRemaining),
            'warningTime' => $warningTime
        ]);
    }
}

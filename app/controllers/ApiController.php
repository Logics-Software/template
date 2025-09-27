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
}

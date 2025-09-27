<?php
/**
 * Dashboard Controller
 */
class DashboardController extends BaseController
{
    private $userModel;
    private $statsModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->statsModel = new Stats();
    }

    public function index()
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $stats = $this->getDashboardStats();
        $recentUsers = $this->userModel->findAll('status = :status ORDER BY created_at DESC LIMIT 5', ['status' => 'active']);
        $chartData = $this->getChartData();

        // Set current page for sidebar highlighting
        $current_page = 'dashboard';

        $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'current_page' => $current_page,
            'user' => [
                'id' => Session::get('user_id'),
                'name' => Session::get('user_name'),
                'email' => Session::get('user_email')
            ],
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'chartData' => $chartData,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    private function getDashboardStats()
    {
        return [
            'total_users' => $this->userModel->count('status = :status', ['status' => 'active']),
            'total_customers' => $this->statsModel->getTotalCustomers(),
            'total_revenue' => $this->statsModel->getTotalRevenue(),
            'conversion_rate' => $this->statsModel->getConversionRate()
        ];
    }

    private function getChartData()
    {
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'datasets' => [
                [
                    'label' => 'Sales',
                    'data' => [12, 19, 3, 5, 2, 3],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    public function analytics($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $stats = $this->getAnalyticsStats();
        $chartData = $this->getAnalyticsChartData();

        $this->view('dashboard/analytics', [
            'title' => 'Analytics',
            'current_page' => 'analytics',
            'user' => [
                'id' => Session::get('user_id'),
                'name' => Session::get('user_name'),
                'email' => Session::get('user_email')
            ],
            'stats' => $stats,
            'chartData' => $chartData,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    private function getAnalyticsStats()
    {
        return [
            'website_traffic' => '91.6K',
            'conversion_rate' => '15%',
            'session_duration' => '90 Sec',
            'active_users' => '2,986',
            'earnings' => '$545.69',
            'profit' => '$256.34',
            'expense' => '$74.19'
        ];
    }

    private function getAnalyticsChartData()
    {
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'datasets' => [
                [
                    'label' => 'Earnings',
                    'data' => [1200, 1900, 3000, 5000, 2000, 3000, 4500, 3800, 4200, 5100, 4800, 5500],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.1)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true
                ],
                [
                    'label' => 'Profit',
                    'data' => [800, 1200, 2000, 3200, 1500, 2000, 3000, 2500, 2800, 3400, 3200, 3800],
                    'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                    'borderColor' => 'rgba(40, 167, 69, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true
                ]
            ]
        ];
    }
}

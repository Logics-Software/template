<?php
/**
 * Dashboard Controller
 */
class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        // Get user role
        $userRole = Session::get('user_role');
        
        // Set current page for sidebar highlighting
        $current_page = 'dashboard';

        // Common data for all roles
        $commonData = [
            'title' => 'Dashboard',
            'current_page' => $current_page,
            'user' => [
                'id' => Session::get('user_id'),
                'name' => Session::get('user_name'),
                'email' => Session::get('user_email'),
                'username' => Session::get('user_username'),
                'role' => Session::get('user_role'),
                'picture' => Session::get('user_picture')
            ],
            'csrf_token' => $this->csrfToken()
        ];

        // Route to appropriate dashboard based on role
        switch($userRole) {
            case 'admin':
                $this->view('dashboard/admin/index', $commonData);
                break;
            case 'manajemen':
                $this->view('dashboard/manajemen/index', $commonData);
                break;
            case 'user':
                $this->view('dashboard/user/index', $commonData);
                break;
            case 'marketing':
                $this->view('dashboard/marketing/index', $commonData);
                break;
            case 'sales':
                $this->view('dashboard/sales/index', $commonData);
                break;
            case 'customer':
                $this->view('dashboard/customer/index', $commonData);
                break;
            default:
                // Default fallback to user dashboard
                $this->view('dashboard/user/index', $commonData);
        }
    }

    public function analytics($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $this->view('dashboard/analytics', [
            'title' => 'Analytics',
            'current_page' => 'analytics',
            'user' => [
                'id' => Session::get('user_id'),
                'name' => Session::get('user_name'),
                'email' => Session::get('user_email'),
                'username' => Session::get('user_username'),
                'role' => Session::get('user_role'),
                'picture' => Session::get('user_picture')
            ],
            'csrf_token' => $this->csrfToken()
        ]);
    }
}

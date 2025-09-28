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

    public function index()
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        // Set current page for sidebar highlighting
        $current_page = 'dashboard';

        $this->view('dashboard/index', [
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
        ]);
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

<?php
/**
 * Dashboard Controller
 */
require_once 'app/core/Cache.php';

class DashboardController extends BaseController
{
    private $userModel;
    private $moduleModel;
    private $messageModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->moduleModel = new Module();
        $this->messageModel = new Message();
    }

    public function index($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // Get user role
        $userRole = Session::get('user_role');
        
        // Set current page for sidebar highlighting
        $current_page = 'dashboard';
        
        // Get dashboard statistics (cache temporarily disabled)
        $stats = $this->getDashboardStats($userRole);

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
            return;
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

    /**
     * Update user profile from dashboard
     */
    public function updateProfile($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // Modern validation
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'string|max:20'
        ]);

        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect('/dashboard');
            }
        }

        $userId = Session::get('user_id');
        $data = [
            'namalengkap' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone')
        ];

        try {
            $this->userModel->beginTransaction();
            
            $result = $this->userModel->update($userId, $data);
            
            if ($result) {
                // Update session data
                Session::set('user_name', $data['namalengkap']);
                Session::set('user_email', $data['email']);
                
                $this->userModel->commit();
                
                if ($request->isAjax()) {
                    $this->json(['success' => true, 'message' => 'Profile updated successfully']);
                } else {
                    $this->withSuccess('Profile updated successfully');
                    $this->redirect('/dashboard');
                }
            } else {
                $this->userModel->rollback();
                
                if ($request->isAjax()) {
                    $this->json(['error' => 'Failed to update profile'], 500);
                } else {
                    $this->withError('Failed to update profile');
                    $this->redirect('/dashboard');
                }
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            
            if ($request->isAjax()) {
                $this->json(['error' => 'An error occurred while updating profile'], 500);
            } else {
                $this->withError('An error occurred while updating profile');
                $this->redirect('/dashboard');
            }
        }
    }

    /**
     * Update user preferences
     */
    public function updatePreferences($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // Modern validation
        $validator = $request->validate([
            'theme' => 'required|in:light,dark,auto',
            'language' => 'required|in:en,id',
            'notifications' => 'boolean',
            'email_notifications' => 'boolean'
        ]);

        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect('/dashboard');
            }
        }

        $userId = Session::get('user_id');
        $data = [
            'theme' => $request->input('theme'),
            'language' => $request->input('language'),
            'notifications' => $request->input('notifications') ? 1 : 0,
            'email_notifications' => $request->input('email_notifications') ? 1 : 0
        ];

        try {
            $this->userModel->beginTransaction();
            
            $result = $this->userModel->updatePreferences($userId, $data);
            
            if ($result) {
                // Update session preferences
                Session::set('user_theme', $data['theme']);
                Session::set('user_language', $data['language']);
                
                $this->userModel->commit();
                
                if ($request->isAjax()) {
                    $this->json(['success' => true, 'message' => 'Preferences updated successfully']);
                } else {
                    $this->withSuccess('Preferences updated successfully');
                    $this->redirect('/dashboard');
                }
            } else {
                $this->userModel->rollback();
                
                if ($request->isAjax()) {
                    $this->json(['error' => 'Failed to update preferences'], 500);
                } else {
                    $this->withError('Failed to update preferences');
                    $this->redirect('/dashboard');
                }
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            
            if ($request->isAjax()) {
                $this->json(['error' => 'An error occurred while updating preferences'], 500);
            } else {
                $this->withError('An error occurred while updating preferences');
                $this->redirect('/dashboard');
            }
        }
    }

    /**
     * Get dashboard statistics
     */
    public function getStats($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        try {
            $userId = Session::get('user_id');
            $userRole = Session::get('user_role');
            
            $stats = [];
            
            // Get user-specific statistics based on role
            switch($userRole) {
                case 'admin':
                    $stats = [
                        'total_users' => $this->userModel->getTotalUsers(),
                        'active_users' => $this->userModel->getActiveUsers(),
                        'pending_users' => $this->userModel->getPendingUsers(),
                        'total_modules' => $this->getTotalModules(),
                        'total_messages' => $this->getTotalMessages()
                    ];
                    break;
                case 'manajemen':
                    $stats = [
                        'team_members' => $this->userModel->getTeamMembers($userId),
                        'pending_approvals' => $this->getPendingApprovals(),
                        'monthly_reports' => $this->getMonthlyReports()
                    ];
                    break;
                default:
                    $stats = [
                        'unread_messages' => $this->getUnreadMessages($userId),
                        'recent_activities' => $this->getRecentActivities($userId)
                    ];
            }
            
            if ($request->isAjax()) {
                $this->json(['success' => true, 'stats' => $stats]);
            } else {
                $this->view('dashboard/stats', [
                    'title' => 'Dashboard Statistics',
                    'stats' => $stats
                ]);
            }
        } catch (Exception $e) {
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to load statistics'], 500);
            } else {
                $this->withError('Failed to load statistics');
                $this->redirect('/dashboard');
            }
        }
    }

    /**
     * Helper methods for statistics
     */
    private function getTotalModules()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM modules";
            $result = $this->moduleModel->findAll();
            return count($result);
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getTotalMessages()
    {
        try {
            $result = $this->messageModel->findAll();
            return count($result);
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getPendingApprovals()
    {
        try {
            $result = $this->userModel->findAll('status = :status', ['status' => 'register']);
            return count($result);
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getMonthlyReports()
    {
        try {
            // Get current month and year
            $currentMonth = date('Y-m');
            
            // This is a placeholder - implement based on your reporting needs
            return [
                'current_month' => $currentMonth,
                'total_users' => $this->userModel->getTotalUsers(),
                'active_users' => $this->userModel->getActiveUsers(),
                'pending_users' => $this->userModel->getPendingUsers()
            ];
        } catch (Exception $e) {
            return [];
        }
    }

    private function getDashboardStats($userRole)
    {
        try {
            $stats = [];
            
            // Get user statistics
            $stats['total_users'] = $this->userModel->getTotalUsers();
            $stats['active_users'] = $this->userModel->getActiveUsers();
            $stats['pending_users'] = $this->userModel->getPendingUsers();
            
            // Get message statistics
            $stats['total_messages'] = $this->getTotalMessages();
            $stats['unread_messages'] = $this->getUnreadMessages(Session::get('user_id'));
            
            return $stats;
        } catch (Exception $e) {
            return [];
        }
    }

    private function getUnreadMessages($userId)
    {
        try {
            // Simplified unread messages count
            $messages = $this->messageModel->findAll('recipient_id = :userId AND is_read = 0', ['userId' => $userId]);
            return count($messages);
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getRecentActivities($userId)
    {
        try {
            // This is a placeholder - implement based on your activity tracking needs
            return [
                'last_login' => Session::get('user_last_login') ?? 'Never',
                'profile_updated' => 'Recently',
                'messages_sent' => 0,
                'modules_accessed' => 0
            ];
        } catch (Exception $e) {
            return [];
        }
    }
}

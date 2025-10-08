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
        
        // Get dashboard statistics with caching (5 minutes)
        $userId = Session::get('user_id');
        $cacheKey = "dashboard_stats_{$userRole}_{$userId}";
        
        $stats = Cache::remember($cacheKey, function() use ($userRole) {
            return $this->getDashboardStats($userRole);
        }, 300);

        // Common data for all roles
        $commonData = [
            'title' => 'Dashboard',
            'current_page' => $current_page,
            'stats' => $stats,
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
            
            // Get common statistics
            $stats['total_users'] = $this->userModel->getTotalUsers();
            $stats['active_users'] = $this->userModel->getActiveUsers();
            $stats['pending_users'] = $this->userModel->getPendingUsers();
            $stats['total_messages'] = $this->getTotalMessages();
            $stats['unread_messages'] = $this->getUnreadMessages(Session::get('user_id'));
            
            // Get role-specific statistics
            switch($userRole) {
                case 'admin':
                    $stats['admin_specific'] = [
                        'total_modules' => $this->getTotalModules(),
                        'system_health' => $this->getSystemHealth(),
                        'recent_activities' => $this->getRecentActivities(Session::get('user_id'))
                    ];
                    break;
                    
                case 'manajemen':
                    $stats['manajemen_specific'] = [
                        'team_performance' => $this->getTeamPerformance(),
                        'pending_approvals' => $this->getPendingApprovals(),
                        'department_stats' => $this->getDepartmentStats()
                    ];
                    break;
                    
                case 'marketing':
                    $stats['marketing_specific'] = [
                        'campaigns_active' => $this->getActiveCampaigns(),
                        'leads_generated' => $this->getLeadsGenerated(),
                        'conversion_rate' => $this->getConversionRate()
                    ];
                    break;
                    
                case 'sales':
                    $stats['sales_specific'] = [
                        'sales_target' => $this->getSalesTarget(),
                        'revenue_this_month' => $this->getRevenueThisMonth(),
                        'top_customers' => $this->getTopCustomers()
                    ];
                    break;
                    
                case 'customer':
                    $stats['customer_specific'] = [
                        'support_tickets' => $this->getSupportTickets(),
                        'satisfaction_score' => $this->getSatisfactionScore(),
                        'recent_interactions' => $this->getRecentInteractions()
                    ];
                    break;
                    
                default: // user
                    $stats['user_specific'] = [
                        'my_messages' => $this->getMyMessages(Session::get('user_id')),
                        'my_activities' => $this->getMyActivities(Session::get('user_id')),
                        'profile_completion' => $this->getProfileCompletion(Session::get('user_id'))
                    ];
            }
            
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
    
    // Role-specific methods
    
    private function getSystemHealth()
    {
        try {
            return [
                'database_status' => 'Online',
                'cache_status' => 'Active',
                'disk_usage' => '75%',
                'memory_usage' => '60%'
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getTeamPerformance()
    {
        try {
            return [
                'active_team_members' => 5,
                'completed_tasks' => 12,
                'pending_tasks' => 3,
                'team_efficiency' => '85%'
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getDepartmentStats()
    {
        try {
            return [
                'total_departments' => 3,
                'active_projects' => 8,
                'budget_utilization' => '70%',
                'department_goals' => 'On Track'
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getActiveCampaigns()
    {
        try {
            return [
                'running_campaigns' => 3,
                'campaign_reach' => 15000,
                'engagement_rate' => '12%',
                'conversion_rate' => '5%'
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getLeadsGenerated()
    {
        try {
            return [
                'total_leads' => 150,
                'qualified_leads' => 45,
                'conversion_rate' => '30%',
                'lead_score_avg' => '75'
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getConversionRate()
    {
        try {
            return '8.5%';
        } catch (Exception $e) {
            return '0%';
        }
    }
    
    private function getSalesTarget()
    {
        try {
            return [
                'monthly_target' => 100000,
                'current_sales' => 75000,
                'target_achievement' => '75%',
                'days_remaining' => 15
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getRevenueThisMonth()
    {
        try {
            return [
                'revenue' => 75000,
                'growth_rate' => '12%',
                'top_product' => 'Product A',
                'revenue_forecast' => 95000
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getTopCustomers()
    {
        try {
            return [
                'customer_1' => ['name' => 'Company A', 'revenue' => 15000],
                'customer_2' => ['name' => 'Company B', 'revenue' => 12000],
                'customer_3' => ['name' => 'Company C', 'revenue' => 10000]
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getSupportTickets()
    {
        try {
            return [
                'open_tickets' => 5,
                'resolved_today' => 3,
                'avg_response_time' => '2 hours',
                'satisfaction_rating' => '4.5/5'
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getSatisfactionScore()
    {
        try {
            return '4.5/5';
        } catch (Exception $e) {
            return '0/5';
        }
    }
    
    private function getRecentInteractions()
    {
        try {
            return [
                'last_support_call' => '2 hours ago',
                'last_email' => '1 day ago',
                'interaction_count' => 12,
                'preferred_channel' => 'Email'
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getMyMessages($userId)
    {
        try {
            $messages = $this->messageModel->findAll('recipient_id = :userId', ['userId' => $userId]);
            return [
                'total_messages' => count($messages),
                'unread_messages' => $this->getUnreadMessages($userId),
                'sent_messages' => count($this->messageModel->findAll('sender_id = :userId', ['userId' => $userId]))
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getMyActivities($userId)
    {
        try {
            return [
                'last_login' => Session::get('user_last_login') ?? 'Never',
                'profile_updated' => 'Recently',
                'messages_sent' => 5,
                'modules_accessed' => 8
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getProfileCompletion($userId)
    {
        try {
            $user = $this->userModel->find($userId);
            $completion = 0;
            
            if (!empty($user['namalengkap'])) $completion += 25;
            if (!empty($user['email'])) $completion += 25;
            if (!empty($user['picture'])) $completion += 25;
            if (!empty($user['phone'])) $completion += 25;
            
            return $completion;
        } catch (Exception $e) {
            return 0;
        }
    }
}

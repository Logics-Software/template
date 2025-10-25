<?php

class CustomerVisitMonitoringController extends BaseController
{
    private $customerVisitModel;
    private $customerModel;
    private $visitTargetModel;
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->customerVisitModel = new CustomerVisit();
        $this->customerModel = new Customer();
        $this->visitTargetModel = new VisitTarget();
        $this->userModel = new User();
    }

    /**
     * Dashboard monitoring untuk admin/manajemen
     */
    public function index()
    {
        // Get filter parameters
        $month = $_GET['month'] ?? date('Y-m');
        $marketingId = $_GET['marketing_id'] ?? null;
        
        // Statistics
        $stats = $this->getOverallStatistics($month, $marketingId);
        
        // Marketing Performance
        $marketingPerformance = $this->getMarketingPerformance($month);
        
        // Recent Visits
        $recentVisits = $this->customerVisitModel->getRecentVisits(10, $marketingId);
        
        // Top Customers
        $topCustomers = $this->getTopCustomers($month, $marketingId);
        
        // Visit Trends (last 7 days)
        $visitTrends = $this->getVisitTrends(7, $marketingId);
        
        // Marketing List for filter
        $marketingList = $this->userModel->getUsersByRole('marketing');
        
        return $this->view('customer-visits-monitoring/dashboard', [
            'title' => 'Monitoring Kunjungan Customer',
            'stats' => $stats,
            'marketingPerformance' => $marketingPerformance,
            'recentVisits' => $recentVisits,
            'topCustomers' => $topCustomers,
            'visitTrends' => $visitTrends,
            'marketingList' => $marketingList,
            'selectedMonth' => $month,
            'selectedMarketing' => $marketingId
        ]);
    }

    /**
     * Detail performance marketing specific
     */
    public function marketingDetail($id)
    {
        $month = $_GET['month'] ?? date('Y-m');
        
        // Get marketing info
        $marketing = $this->userModel->find($id);
        if (!$marketing || $marketing['role'] !== 'marketing') {
            return $this->redirect('/customer-visits-monitoring')->withError('Marketing tidak ditemukan');
        }
        
        // Get target & achievement
        $target = $this->visitTargetModel->getTargetByMarketingAndMonth($id, $month);
        
        // Get visits
        $visits = $this->customerVisitModel->getVisitsByMarketing($id, $month);
        
        // Get statistics
        $stats = $this->getMarketingStatistics($id, $month);
        
        // Get customer visit frequency
        $customerFrequency = $this->getCustomerVisitFrequency($id, $month);
        
        return $this->view('customer-visits-monitoring/marketing-detail', [
            'title' => 'Detail Performance - ' . $marketing['name'],
            'marketing' => $marketing,
            'target' => $target,
            'visits' => $visits,
            'stats' => $stats,
            'customerFrequency' => $customerFrequency,
            'selectedMonth' => $month
        ]);
    }

    /**
     * Report kunjungan (export ready)
     */
    public function report()
    {
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');
        $marketingId = $_GET['marketing_id'] ?? null;
        $resultFilter = $_GET['result'] ?? null;
        
        $visits = $this->customerVisitModel->getVisitsForReport($startDate, $endDate, $marketingId, $resultFilter);
        
        $summary = [
            'total_visits' => count($visits),
            'total_orders' => array_reduce($visits, function($carry, $item) {
                return $carry + ($item['has_order'] ? 1 : 0);
            }, 0),
            'total_order_amount' => array_reduce($visits, function($carry, $item) {
                return $carry + ($item['order_amount'] ?? 0);
            }, 0),
            'success_rate' => 0
        ];
        
        if ($summary['total_visits'] > 0) {
            $summary['success_rate'] = ($summary['total_orders'] / $summary['total_visits']) * 100;
        }
        
        $marketingList = $this->userModel->getUsersByRole('marketing');
        
        return $this->view('customer-visits-monitoring/report', [
            'title' => 'Laporan Kunjungan Customer',
            'visits' => $visits,
            'summary' => $summary,
            'marketingList' => $marketingList,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'marketing_id' => $marketingId,
                'result' => $resultFilter
            ]
        ]);
    }

    /**
     * Get overall statistics
     */
    private function getOverallStatistics($month, $marketingId = null)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                    COUNT(*) as total_visits,
                    COUNT(DISTINCT customer_id) as unique_customers,
                    COUNT(DISTINCT marketing_id) as active_marketing,
                    SUM(CASE WHEN has_order = 1 THEN 1 ELSE 0 END) as total_orders,
                    SUM(order_amount) as total_order_amount,
                    AVG(duration_minutes) as avg_duration,
                    SUM(CASE WHEN visit_result = 'order_success' THEN 1 ELSE 0 END) as successful_visits
                FROM customer_visits
                WHERE DATE_FORMAT(visit_date, '%Y-%m') = :month";
        
        if ($marketingId) {
            $sql .= " AND marketing_id = :marketing_id";
        }
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':month', $month);
        if ($marketingId) {
            $stmt->bindParam(':marketing_id', $marketingId);
        }
        $stmt->execute();
        
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Calculate success rate
        $stats['success_rate'] = $stats['total_visits'] > 0 
            ? ($stats['successful_visits'] / $stats['total_visits']) * 100 
            : 0;
        
        return $stats;
    }

    /**
     * Get marketing performance
     */
    private function getMarketingPerformance($month)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                    u.id,
                    u.name as marketing_name,
                    COUNT(cv.id) as total_visits,
                    COUNT(DISTINCT cv.customer_id) as unique_customers,
                    SUM(CASE WHEN cv.has_order = 1 THEN 1 ELSE 0 END) as total_orders,
                    SUM(cv.order_amount) as total_order_amount,
                    vt.target_visits,
                    vt.target_orders,
                    vt.target_amount,
                    CASE 
                        WHEN vt.target_visits > 0 
                        THEN (COUNT(cv.id) / vt.target_visits * 100)
                        ELSE 0 
                    END as visit_achievement
                FROM users u
                LEFT JOIN customer_visits cv ON u.id = cv.marketing_id 
                    AND DATE_FORMAT(cv.visit_date, '%Y-%m') = :month
                LEFT JOIN visit_targets vt ON u.id = vt.marketing_id 
                    AND DATE_FORMAT(vt.target_month, '%Y-%m') = :month
                WHERE u.role = 'marketing' AND u.status = 'active'
                GROUP BY u.id
                ORDER BY total_visits DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':month', $month);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get top customers
     */
    private function getTopCustomers($month, $marketingId = null)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                    c.id,
                    c.customer_code,
                    c.customer_name,
                    COUNT(cv.id) as visit_count,
                    SUM(CASE WHEN cv.has_order = 1 THEN 1 ELSE 0 END) as order_count,
                    SUM(cv.order_amount) as total_order_amount
                FROM customers c
                INNER JOIN customer_visits cv ON c.id = cv.customer_id
                WHERE DATE_FORMAT(cv.visit_date, '%Y-%m') = :month";
        
        if ($marketingId) {
            $sql .= " AND cv.marketing_id = :marketing_id";
        }
        
        $sql .= " GROUP BY c.id
                  ORDER BY total_order_amount DESC
                  LIMIT 10";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':month', $month);
        if ($marketingId) {
            $stmt->bindParam(':marketing_id', $marketingId);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get visit trends
     */
    private function getVisitTrends($days = 7, $marketingId = null)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                    DATE(visit_date) as date,
                    COUNT(*) as total_visits,
                    SUM(CASE WHEN has_order = 1 THEN 1 ELSE 0 END) as total_orders
                FROM customer_visits
                WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)";
        
        if ($marketingId) {
            $sql .= " AND marketing_id = :marketing_id";
        }
        
        $sql .= " GROUP BY DATE(visit_date)
                  ORDER BY date ASC";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        if ($marketingId) {
            $stmt->bindParam(':marketing_id', $marketingId);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get marketing statistics
     */
    private function getMarketingStatistics($marketingId, $month)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                    COUNT(*) as total_visits,
                    COUNT(DISTINCT customer_id) as unique_customers,
                    SUM(CASE WHEN has_order = 1 THEN 1 ELSE 0 END) as total_orders,
                    SUM(order_amount) as total_order_amount,
                    AVG(duration_minutes) as avg_duration,
                    SUM(CASE WHEN visit_result = 'order_success' THEN 1 ELSE 0 END) as successful_visits,
                    SUM(CASE WHEN visit_result = 'follow_up_needed' THEN 1 ELSE 0 END) as follow_up_needed,
                    SUM(CASE WHEN visit_result = 'rejected' THEN 1 ELSE 0 END) as rejected_visits
                FROM customer_visits
                WHERE marketing_id = :marketing_id
                AND DATE_FORMAT(visit_date, '%Y-%m') = :month";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':marketing_id', $marketingId);
        $stmt->bindParam(':month', $month);
        $stmt->execute();
        
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stats['success_rate'] = $stats['total_visits'] > 0 
            ? ($stats['successful_visits'] / $stats['total_visits']) * 100 
            : 0;
        
        return $stats;
    }

    /**
     * Get customer visit frequency
     */
    private function getCustomerVisitFrequency($marketingId, $month)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT 
                    c.customer_name,
                    c.customer_code,
                    COUNT(cv.id) as visit_count,
                    MAX(cv.visit_date) as last_visit
                FROM customers c
                INNER JOIN customer_visits cv ON c.id = cv.customer_id
                WHERE cv.marketing_id = :marketing_id
                AND DATE_FORMAT(cv.visit_date, '%Y-%m') = :month
                GROUP BY c.id
                ORDER BY visit_count DESC
                LIMIT 10";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':marketing_id', $marketingId);
        $stmt->bindParam(':month', $month);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


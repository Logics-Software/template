<?php
/**
 * Statistics Model
 */
class Stats extends Model
{
    protected $table = 'stats';

    public function getTotalCustomers()
    {
        return $this->db->count('customers', 'status = :status', ['status' => 'active']);
    }

    public function getTotalRevenue()
    {
        $result = $this->db->fetch("SELECT SUM(amount) as total FROM transactions WHERE status = :status", ['status' => 'completed']);
        return $result['total'] ?? 0;
    }

    public function getConversionRate()
    {
        $totalLeads = $this->db->count('leads');
        $convertedLeads = $this->db->count('leads', 'status = :status', ['status' => 'converted']);
        
        if ($totalLeads == 0) return 0;
        return round(($convertedLeads / $totalLeads) * 100, 2);
    }

    public function getPendingTasks()
    {
        return $this->db->count('tasks', 'status = :status', ['status' => 'pending']);
    }

    public function getSalesData($period = 'month')
    {
        $dateFormat = $period === 'month' ? '%Y-%m' : '%Y-%m-%d';
        $sql = "SELECT DATE_FORMAT(created_at, '{$dateFormat}') as period, COUNT(*) as count, SUM(amount) as revenue 
                FROM transactions 
                WHERE status = :status 
                GROUP BY period 
                ORDER BY period DESC 
                LIMIT 12";
        
        return $this->db->fetchAll($sql, ['status' => 'completed']);
    }

    public function getTopProducts($limit = 5)
    {
        $sql = "SELECT product_name, SUM(quantity) as total_sold, SUM(amount) as revenue 
                FROM transactions 
                WHERE status = :status 
                GROUP BY product_name 
                ORDER BY total_sold DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['status' => 'completed', 'limit' => $limit]);
    }

    public function getRecentActivity($limit = 10)
    {
        $sql = "SELECT 'transaction' as type, id, amount, created_at 
                FROM transactions 
                WHERE status = :status 
                UNION ALL 
                SELECT 'user' as type, id, 0 as amount, created_at 
                FROM users 
                WHERE status = :user_status 
                ORDER BY created_at DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [
            'status' => 'completed',
            'user_status' => 'active',
            'limit' => $limit
        ]);
    }
}

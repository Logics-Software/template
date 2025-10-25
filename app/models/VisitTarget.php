<?php
/**
 * VisitTarget Model
 * Manages monthly visit targets for marketing team
 */
class VisitTarget extends Model
{
    protected $table = 'visit_targets';
    
    protected $fillable = [
        'marketing_id',
        'target_month',
        'target_visits',
        'target_orders',
        'target_amount',
        'actual_visits',
        'actual_orders',
        'actual_amount',
        'achievement_percentage',
        'created_by'
    ];
    
    protected $casts = [
        'marketing_id' => 'integer',
        'target_visits' => 'integer',
        'target_orders' => 'integer',
        'target_amount' => 'float',
        'actual_visits' => 'integer',
        'actual_orders' => 'integer',
        'actual_amount' => 'float',
        'achievement_percentage' => 'float'
    ];
    
    /**
     * Get target for specific marketing and month
     */
    public function getTarget($marketingId, $month)
    {
        $sql = "SELECT * FROM {$this->table} WHERE marketing_id = ? AND target_month = ?";
        return $this->db->fetch($sql, [$marketingId, $month]);
    }
    
    /**
     * Get or create target for marketing
     */
    public function getOrCreateTarget($marketingId, $month)
    {
        $target = $this->getTarget($marketingId, $month);
        
        if (!$target) {
            // Create default target
            $data = [
                'marketing_id' => $marketingId,
                'target_month' => $month,
                'target_visits' => 20,
                'target_orders' => 15,
                'target_amount' => 50000000,
                'actual_visits' => 0,
                'actual_orders' => 0,
                'actual_amount' => 0,
                'achievement_percentage' => 0
            ];
            
            $this->create($data);
            $target = $this->getTarget($marketingId, $month);
        }
        
        return $target;
    }
    
    /**
     * Update actual values from visits
     */
    public function updateActuals($marketingId, $month)
    {
        // Calculate actuals from customer_visits table
        $sql = "SELECT 
                COUNT(*) as actual_visits,
                COUNT(CASE WHEN has_order = 1 THEN 1 END) as actual_orders,
                COALESCE(SUM(CASE WHEN has_order = 1 THEN order_amount ELSE 0 END), 0) as actual_amount
                FROM customer_visits
                WHERE marketing_id = ? 
                AND DATE_FORMAT(visit_date, '%Y-%m-01') = ?";
        
        $actuals = $this->db->fetch($sql, [$marketingId, $month]);
        
        if ($actuals) {
            $target = $this->getTarget($marketingId, $month);
            
            if ($target) {
                // Calculate achievement percentage based on visits
                $achievementPercentage = $target['target_visits'] > 0 
                    ? ($actuals['actual_visits'] / $target['target_visits']) * 100 
                    : 0;
                
                $updateData = [
                    'actual_visits' => $actuals['actual_visits'],
                    'actual_orders' => $actuals['actual_orders'],
                    'actual_amount' => $actuals['actual_amount'],
                    'achievement_percentage' => round($achievementPercentage, 2)
                ];
                
                $this->update($target['id'], $updateData);
            }
        }
    }
    
    /**
     * Get target with progress for marketing
     */
    public function getTargetWithProgress($marketingId, $month)
    {
        // Update actuals first
        $this->updateActuals($marketingId, $month);
        
        // Get target
        $target = $this->getTarget($marketingId, $month);
        
        if ($target) {
            // Calculate percentages
            $target['visits_percentage'] = $target['target_visits'] > 0 
                ? round(($target['actual_visits'] / $target['target_visits']) * 100, 2) 
                : 0;
            
            $target['orders_percentage'] = $target['target_orders'] > 0 
                ? round(($target['actual_orders'] / $target['target_orders']) * 100, 2) 
                : 0;
            
            $target['amount_percentage'] = $target['target_amount'] > 0 
                ? round(($target['actual_amount'] / $target['target_amount']) * 100, 2) 
                : 0;
        }
        
        return $target;
    }
    
    /**
     * Get all targets for a month (admin view)
     */
    public function getMonthlyTargets($month)
    {
        $sql = "SELECT vt.*, u.namalengkap as marketing_name, u.email
                FROM {$this->table} vt
                INNER JOIN users u ON vt.marketing_id = u.id
                WHERE vt.target_month = ?
                ORDER BY vt.achievement_percentage DESC";
        
        return $this->db->fetchAll($sql, [$month]);
    }
    
    /**
     * Set target for marketing
     */
    public function setTarget($marketingId, $month, $targetVisits, $targetOrders, $targetAmount)
    {
        $existing = $this->getTarget($marketingId, $month);
        
        if ($existing) {
            // Update existing target
            $data = [
                'target_visits' => $targetVisits,
                'target_orders' => $targetOrders,
                'target_amount' => $targetAmount
            ];
            
            return $this->update($existing['id'], $data);
        } else {
            // Create new target
            $data = [
                'marketing_id' => $marketingId,
                'target_month' => $month,
                'target_visits' => $targetVisits,
                'target_orders' => $targetOrders,
                'target_amount' => $targetAmount,
                'actual_visits' => 0,
                'actual_orders' => 0,
                'actual_amount' => 0,
                'achievement_percentage' => 0
            ];
            
            return $this->create($data);
        }
    }
    
    /**
     * Get target by marketing and month
     */
    public function getTargetByMarketingAndMonth($marketingId, $month)
    {
        // Ensure month is in YYYY-MM format
        if (strlen($month) === 7) {
            $month = $month . '-01';
        }
        
        $sql = "SELECT * FROM {$this->table} 
                WHERE marketing_id = :marketing_id 
                AND DATE_FORMAT(target_month, '%Y-%m') = :month
                LIMIT 1";
        
        return $this->db->fetch($sql, [
            'marketing_id' => $marketingId,
            'month' => substr($month, 0, 7) // Get YYYY-MM only
        ]);
    }
}


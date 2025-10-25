<?php
/**
 * Customer Model
 * Manages customer data for visit tracking
 */
class Customer extends Model
{
    protected $table = 'customers';
    
    protected $fillable = [
        'customer_code',
        'customer_name',
        'owner_name',
        'phone',
        'email',
        'address',
        'latitude',
        'longitude',
        'customer_type',
        'customer_category',
        'assigned_marketing_id',
        'status',
        'notes',
        'last_visit_date',
        'total_visits',
        'total_orders',
        'created_by'
    ];
    
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'total_visits' => 'integer',
        'total_orders' => 'integer'
    ];
    
    /**
     * Get all active customers
     */
    public function getAllActive()
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY customer_name ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get customers by marketing ID
     */
    public function getByMarketing($marketingId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE assigned_marketing_id = ? AND status = 'active' 
                ORDER BY customer_name ASC";
        return $this->db->fetchAll($sql, [$marketingId]);
    }
    
    /**
     * Search customers
     */
    public function searchCustomers($search, $marketingId = null)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 'active' 
                AND (customer_name LIKE ? OR customer_code LIKE ? OR owner_name LIKE ? OR address LIKE ?)";
        
        $params = ["%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"];
        
        if ($marketingId) {
            $sql .= " AND assigned_marketing_id = ?";
            $params[] = $marketingId;
        }
        
        $sql .= " ORDER BY customer_name ASC LIMIT 50";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get customer with visit statistics
     */
    public function getCustomerWithStats($customerId)
    {
        $sql = "SELECT c.*,
                       u.namalengkap as marketing_name,
                       (SELECT COUNT(*) FROM customer_visits WHERE customer_id = c.id) as total_visits_count,
                       (SELECT COUNT(*) FROM customer_visits WHERE customer_id = c.id AND has_order = 1) as total_orders_count,
                       (SELECT SUM(order_amount) FROM customer_visits WHERE customer_id = c.id AND has_order = 1) as total_order_amount,
                       (SELECT MAX(visit_date) FROM customer_visits WHERE customer_id = c.id) as last_visit
                FROM {$this->table} c
                LEFT JOIN users u ON c.assigned_marketing_id = u.id
                WHERE c.id = ?";
        
        return $this->db->fetch($sql, [$customerId]);
    }
    
    /**
     * Update visit statistics
     */
    public function updateVisitStats($customerId)
    {
        $sql = "UPDATE {$this->table} SET 
                total_visits = (SELECT COUNT(*) FROM customer_visits WHERE customer_id = ?),
                total_orders = (SELECT COUNT(*) FROM customer_visits WHERE customer_id = ? AND has_order = 1),
                last_visit_date = (SELECT MAX(visit_date) FROM customer_visits WHERE customer_id = ?)
                WHERE id = ?";
        
        return $this->db->query($sql, [$customerId, $customerId, $customerId, $customerId]);
    }
    
    /**
     * Get paginated customers
     */
    public function getPaginatedCustomers($page = 1, $perPage = 10, $filters = [])
    {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = 'WHERE 1=1';
        $params = [];
        
        if (!empty($filters['search'])) {
            $whereClause .= ' AND (customer_name LIKE ? OR customer_code LIKE ? OR owner_name LIKE ?)';
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($filters['status'])) {
            $whereClause .= ' AND status = ?';
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['customer_type'])) {
            $whereClause .= ' AND customer_type = ?';
            $params[] = $filters['customer_type'];
        }
        
        if (!empty($filters['marketing_id'])) {
            $whereClause .= ' AND assigned_marketing_id = ?';
            $params[] = $filters['marketing_id'];
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'] ?? 0;
        
        // Get paginated data
        $sql = "SELECT c.*, u.namalengkap as marketing_name
                FROM {$this->table} c
                LEFT JOIN users u ON c.assigned_marketing_id = u.id
                {$whereClause}
                ORDER BY c.customer_name ASC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->fetchAll($sql, $params);
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
            'has_next' => $page < ceil($total / $perPage),
            'has_prev' => $page > 1
        ];
    }
    
    /**
     * Generate unique customer code
     */
    public function generateCustomerCode()
    {
        $sql = "SELECT customer_code FROM {$this->table} ORDER BY id DESC LIMIT 1";
        $lastCustomer = $this->db->fetch($sql);
        
        if ($lastCustomer) {
            $lastCode = $lastCustomer['customer_code'];
            $number = (int) substr($lastCode, 4);
            $newNumber = $number + 1;
        } else {
            $newNumber = 1;
        }
        
        return 'CUST' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Get customers near a location (for route planning)
     */
    public function getCustomersNearLocation($latitude, $longitude, $radiusKm = 10, $marketingId = null)
    {
        // Haversine formula to calculate distance
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                sin(radians(latitude)))) AS distance 
                FROM {$this->table}
                WHERE status = 'active' 
                AND latitude IS NOT NULL 
                AND longitude IS NOT NULL";
        
        $params = [$latitude, $longitude, $latitude];
        
        if ($marketingId) {
            $sql .= " AND assigned_marketing_id = ?";
            $params[] = $marketingId;
        }
        
        $sql .= " HAVING distance < ? ORDER BY distance ASC LIMIT 20";
        $params[] = $radiusKm;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get customer statistics for dashboard
     */
    public function getCustomerStats($marketingId = null)
    {
        $where = $marketingId ? "WHERE assigned_marketing_id = {$marketingId}" : "";
        
        $sql = "SELECT 
                COUNT(*) as total_customers,
                COUNT(CASE WHEN status = 'active' THEN 1 END) as active_customers,
                COUNT(CASE WHEN status = 'prospect' THEN 1 END) as prospect_customers,
                COUNT(CASE WHEN customer_type = 'retail' THEN 1 END) as retail_count,
                COUNT(CASE WHEN customer_type = 'wholesale' THEN 1 END) as wholesale_count,
                COUNT(CASE WHEN customer_type = 'distributor' THEN 1 END) as distributor_count
                FROM {$this->table} {$where}";
        
        return $this->db->fetch($sql);
    }
    
    /**
     * Find customer by code
     */
    public function findByCode($code)
    {
        $sql = "SELECT * FROM {$this->table} WHERE customer_code = :code LIMIT 1";
        return $this->db->fetch($sql, ['code' => $code]);
    }
    
    /**
     * Find customer with details (including marketing name)
     */
    public function findWithDetails($id)
    {
        $sql = "SELECT c.*, 
                       u.name as marketing_name,
                       (SELECT COUNT(*) FROM customer_visits WHERE customer_id = c.id) as total_visits,
                       (SELECT COUNT(*) FROM customer_visits WHERE customer_id = c.id AND has_order = 1) as total_orders
                FROM {$this->table} c
                LEFT JOIN users u ON c.assigned_marketing_id = u.id
                WHERE c.id = :id
                LIMIT 1";
        
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    /**
     * Get recent visits for a customer
     */
    public function getRecentVisits($customerId, $limit = 5)
    {
        $sql = "SELECT cv.*, 
                       u.name as marketing_name
                FROM customer_visits cv
                INNER JOIN users u ON cv.marketing_id = u.id
                WHERE cv.customer_id = :customer_id
                ORDER BY cv.visit_date DESC, cv.check_in_time DESC
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, [
            'customer_id' => $customerId,
            'limit' => $limit
        ]);
    }
    
    /**
     * Check if customer has visits
     */
    public function hasVisits($customerId)
    {
        $sql = "SELECT COUNT(*) as count FROM customer_visits WHERE customer_id = :customer_id";
        $result = $this->db->fetch($sql, ['customer_id' => $customerId]);
        return $result['count'] > 0;
    }
}


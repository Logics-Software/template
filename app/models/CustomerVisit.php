<?php
/**
 * CustomerVisit Model
 * Manages customer visit tracking with GPS
 */
class CustomerVisit extends Model
{
    protected $table = 'customer_visits';
    
    protected $fillable = [
        'visit_code',
        'customer_id',
        'marketing_id',
        'visit_date',
        'check_in_time',
        'check_out_time',
        'duration_minutes',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_address',
        'check_in_accuracy',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_address',
        'distance_from_customer',
        'is_location_valid',
        'photos',
        'visit_purpose',
        'visit_result',
        'has_order',
        'order_amount',
        'order_notes',
        'customer_feedback',
        'visit_notes',
        'problems',
        'next_action',
        'next_visit_plan'
    ];
    
    protected $casts = [
        'customer_id' => 'integer',
        'marketing_id' => 'integer',
        'check_in_latitude' => 'float',
        'check_in_longitude' => 'float',
        'check_out_latitude' => 'float',
        'check_out_longitude' => 'float',
        'check_in_accuracy' => 'float',
        'distance_from_customer' => 'float',
        'is_location_valid' => 'boolean',
        'has_order' => 'boolean',
        'order_amount' => 'float',
        'duration_minutes' => 'integer'
    ];
    
    /**
     * Generate unique visit code
     */
    public function generateVisitCode()
    {
        $date = date('Ymd');
        $sql = "SELECT visit_code FROM {$this->table} WHERE visit_code LIKE ? ORDER BY id DESC LIMIT 1";
        $lastVisit = $this->db->fetch($sql, ["VST{$date}%"]);
        
        if ($lastVisit) {
            $lastCode = $lastVisit['visit_code'];
            $number = (int) substr($lastCode, -4);
            $newNumber = $number + 1;
        } else {
            $newNumber = 1;
        }
        
        return 'VST' . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Check in visit
     */
    public function checkIn($data)
    {
        try {
            $this->beginTransaction();
            
            $visitData = [
                'visit_code' => $this->generateVisitCode(),
                'customer_id' => $data['customer_id'],
                'marketing_id' => $data['marketing_id'],
                'visit_date' => date('Y-m-d'),
                'check_in_time' => date('Y-m-d H:i:s'),
                'check_in_latitude' => $data['latitude'],
                'check_in_longitude' => $data['longitude'],
                'check_in_address' => $data['address'] ?? null,
                'check_in_accuracy' => $data['accuracy'] ?? null,
                'distance_from_customer' => $data['distance'] ?? null,
                'is_location_valid' => $data['is_valid'] ?? 1,
                'visit_purpose' => $data['purpose'],
                'visit_notes' => '' // Will be filled at checkout
            ];
            
            $visitId = $this->create($visitData);
            
            $this->commit();
            return $visitId;
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
    
    /**
     * Check out visit
     */
    public function checkOut($visitId, $data)
    {
        try {
            $this->beginTransaction();
            
            // Get visit
            $visit = $this->find($visitId);
            if (!$visit) {
                throw new Exception('Visit not found');
            }
            
            // Calculate duration
            $checkInTime = new DateTime($visit['check_in_time']);
            $checkOutTime = new DateTime();
            $duration = $checkOutTime->diff($checkInTime);
            $durationMinutes = ($duration->h * 60) + $duration->i;
            
            $updateData = [
                'check_out_time' => $checkOutTime->format('Y-m-d H:i:s'),
                'duration_minutes' => $durationMinutes,
                'check_out_latitude' => $data['latitude'] ?? null,
                'check_out_longitude' => $data['longitude'] ?? null,
                'check_out_address' => $data['address'] ?? null,
                'visit_result' => $data['result'],
                'has_order' => $data['has_order'] ?? 0,
                'order_amount' => $data['order_amount'] ?? null,
                'order_notes' => $data['order_notes'] ?? null,
                'customer_feedback' => $data['customer_feedback'] ?? null,
                'visit_notes' => $data['visit_notes'],
                'problems' => $data['problems'] ?? null,
                'next_action' => $data['next_action'] ?? null,
                'next_visit_plan' => $data['next_visit_plan'] ?? null,
                'photos' => $data['photos'] ?? null
            ];
            
            $this->update($visitId, $updateData);
            
            // Update customer statistics
            $customerModel = new Customer();
            $customerModel->updateVisitStats($visit['customer_id']);
            
            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
    
    /**
     * Get active visit for marketing (not checked out yet)
     */
    public function getActiveVisit($marketingId)
    {
        $sql = "SELECT v.*, c.customer_name, c.customer_code, c.address as customer_address
                FROM {$this->table} v
                INNER JOIN customers c ON v.customer_id = c.id
                WHERE v.marketing_id = :marketing_id 
                AND v.check_out_time IS NULL
                AND DATE(v.check_in_time) = CURDATE()
                ORDER BY v.check_in_time DESC
                LIMIT 1";
        
        return $this->db->fetch($sql, ['marketing_id' => $marketingId]);
    }
    
    /**
     * Get visit history for marketing
     */
    public function getVisitHistory($marketingId, $page = 1, $perPage = 10, $filters = [])
    {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = 'WHERE v.marketing_id = ?';
        $params = [$marketingId];
        
        if (!empty($filters['customer_id'])) {
            $whereClause .= ' AND v.customer_id = ?';
            $params[] = $filters['customer_id'];
        }
        
        if (!empty($filters['start_date'])) {
            $whereClause .= ' AND v.visit_date >= ?';
            $params[] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $whereClause .= ' AND v.visit_date <= ?';
            $params[] = $filters['end_date'];
        }
        
        if (!empty($filters['visit_result'])) {
            $whereClause .= ' AND v.visit_result = ?';
            $params[] = $filters['visit_result'];
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} v {$whereClause}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'] ?? 0;
        
        // Get paginated data
        $sql = "SELECT v.*, 
                       c.customer_name, 
                       c.customer_code,
                       c.address as customer_address
                FROM {$this->table} v
                INNER JOIN customers c ON v.customer_id = c.id
                {$whereClause}
                ORDER BY v.visit_date DESC, v.check_in_time DESC
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
     * Get visit statistics for dashboard
     */
    public function getVisitStats($marketingId, $startDate = null, $endDate = null)
    {
        $whereClause = 'WHERE marketing_id = ?';
        $params = [$marketingId];
        
        if ($startDate && $endDate) {
            $whereClause .= ' AND visit_date BETWEEN ? AND ?';
            $params[] = $startDate;
            $params[] = $endDate;
        } elseif ($startDate) {
            $whereClause .= ' AND visit_date >= ?';
            $params[] = $startDate;
        }
        
        $sql = "SELECT 
                COUNT(*) as total_visits,
                COUNT(CASE WHEN has_order = 1 THEN 1 END) as total_orders,
                SUM(CASE WHEN has_order = 1 THEN order_amount ELSE 0 END) as total_order_amount,
                AVG(duration_minutes) as avg_duration,
                COUNT(CASE WHEN visit_result = 'order_success' THEN 1 END) as success_count,
                COUNT(CASE WHEN visit_result = 'follow_up_needed' THEN 1 END) as followup_count,
                COUNT(CASE WHEN visit_result = 'rejected' THEN 1 END) as rejected_count
                FROM {$this->table} 
                {$whereClause}";
        
        return $this->db->fetch($sql, $params);
    }
    
    /**
     * Get today's visits for marketing
     */
    public function getTodayVisits($marketingId)
    {
        $sql = "SELECT v.*, c.customer_name, c.customer_code
                FROM {$this->table} v
                INNER JOIN customers c ON v.customer_id = c.id
                WHERE v.marketing_id = ? 
                AND DATE(v.visit_date) = CURDATE()
                ORDER BY v.check_in_time DESC";
        
        return $this->db->fetchAll($sql, [$marketingId]);
    }
    
    /**
     * Get visit detail with customer info
     */
    public function getVisitDetail($visitId)
    {
        $sql = "SELECT v.*, 
                       c.customer_name, 
                       c.customer_code,
                       c.owner_name,
                       c.phone as customer_phone,
                       c.address as customer_address,
                       c.latitude as customer_latitude,
                       c.longitude as customer_longitude,
                       c.customer_type,
                       c.customer_category,
                       m.namalengkap as marketing_name,
                       m.email as marketing_email
                FROM {$this->table} v
                INNER JOIN customers c ON v.customer_id = c.id
                INNER JOIN users m ON v.marketing_id = m.id
                WHERE v.id = ?";
        
        return $this->db->fetch($sql, [$visitId]);
    }
    
    /**
     * Calculate distance between two GPS coordinates (Haversine formula)
     * Returns distance in meters
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meters
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        $distance = $earthRadius * $c;
        
        return round($distance, 2);
    }
    
    /**
     * Validate GPS location against customer location
     * Returns true if within 50 meters radius
     */
    public function validateLocation($customerLat, $customerLon, $currentLat, $currentLon, $radiusMeters = 50)
    {
        $distance = self::calculateDistance($customerLat, $customerLon, $currentLat, $currentLon);
        
        return [
            'valid' => $distance <= $radiusMeters,
            'distance' => $distance,
            'message' => $distance <= $radiusMeters 
                ? "Lokasi valid (dalam radius {$radiusMeters}m)" 
                : "Anda berada {$distance}m dari lokasi customer (maksimal {$radiusMeters}m)"
        ];
    }
    
    /**
     * Get all visits for admin/manager monitoring
     */
    public function getAllVisits($page = 1, $perPage = 20, $filters = [])
    {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = 'WHERE 1=1';
        $params = [];
        
        if (!empty($filters['marketing_id'])) {
            $whereClause .= ' AND v.marketing_id = ?';
            $params[] = $filters['marketing_id'];
        }
        
        if (!empty($filters['customer_id'])) {
            $whereClause .= ' AND v.customer_id = ?';
            $params[] = $filters['customer_id'];
        }
        
        if (!empty($filters['start_date'])) {
            $whereClause .= ' AND v.visit_date >= ?';
            $params[] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $whereClause .= ' AND v.visit_date <= ?';
            $params[] = $filters['end_date'];
        }
        
        if (!empty($filters['visit_result'])) {
            $whereClause .= ' AND v.visit_result = ?';
            $params[] = $filters['visit_result'];
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} v {$whereClause}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'] ?? 0;
        
        // Get paginated data
        $sql = "SELECT v.*, 
                       c.customer_name, 
                       c.customer_code,
                       m.namalengkap as marketing_name
                FROM {$this->table} v
                INNER JOIN customers c ON v.customer_id = c.id
                INNER JOIN users m ON v.marketing_id = m.id
                {$whereClause}
                ORDER BY v.visit_date DESC, v.check_in_time DESC
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
     * Get recent visits (for monitoring dashboard)
     */
    public function getRecentVisits($limit = 10, $marketingId = null)
    {
        $sql = "SELECT v.*, 
                       c.customer_name, 
                       c.customer_code,
                       m.name as marketing_name
                FROM {$this->table} v
                INNER JOIN customers c ON v.customer_id = c.id
                INNER JOIN users m ON v.marketing_id = m.id";
        
        $params = [];
        if ($marketingId) {
            $sql .= " WHERE v.marketing_id = :marketing_id";
            $params['marketing_id'] = $marketingId;
        }
        
        $sql .= " ORDER BY v.check_in_time DESC LIMIT :limit";
        $params['limit'] = $limit;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get visits by marketing and month
     */
    public function getVisitsByMarketing($marketingId, $month)
    {
        $sql = "SELECT v.*, 
                       c.customer_name, 
                       c.customer_code
                FROM {$this->table} v
                INNER JOIN customers c ON v.customer_id = c.id
                WHERE v.marketing_id = :marketing_id
                AND DATE_FORMAT(v.visit_date, '%Y-%m') = :month
                ORDER BY v.visit_date DESC, v.check_in_time DESC";
        
        return $this->db->fetchAll($sql, [
            'marketing_id' => $marketingId,
            'month' => $month
        ]);
    }
    
    /**
     * Get visits for report with filters
     */
    public function getVisitsForReport($startDate, $endDate, $marketingId = null, $resultFilter = null)
    {
        $sql = "SELECT v.*, 
                       c.customer_name, 
                       c.customer_code,
                       c.address as customer_address,
                       m.name as marketing_name
                FROM {$this->table} v
                INNER JOIN customers c ON v.customer_id = c.id
                INNER JOIN users m ON v.marketing_id = m.id
                WHERE v.visit_date BETWEEN :start_date AND :end_date";
        
        $params = [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        
        if ($marketingId) {
            $sql .= " AND v.marketing_id = :marketing_id";
            $params['marketing_id'] = $marketingId;
        }
        
        if ($resultFilter) {
            $sql .= " AND v.visit_result = :result";
            $params['result'] = $resultFilter;
        }
        
        $sql .= " ORDER BY v.visit_date DESC, v.check_in_time DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
}


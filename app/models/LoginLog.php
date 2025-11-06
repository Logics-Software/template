<?php
/**
 * LoginLog Model
 * Model untuk mengelola log history login user
 */
class LoginLog extends Model
{
    protected $table = 'login_logs';
    protected $fillable = ['user_id', 'session_token', 'ip_address', 'user_agent', 'login_at', 'logout_at', 'status'];

    /**
     * Create login log entry
     * @param int $userId User ID
     * @param string $sessionToken Session token (generated using bin2hex(random_bytes(32)))
     * @param string|null $ipAddress IP address
     * @param string|null $userAgent User agent string
     * @return int|false Login log ID or false on failure
     */
    public function createLog($userId, $sessionToken, $ipAddress = null, $userAgent = null)
    {
        $data = [
            'user_id' => $userId,
            'session_token' => $sessionToken,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'login_at' => date('Y-m-d H:i:s'),
            'status' => 'active'
        ];

        return $this->create($data);
    }

    /**
     * Update logout time for active session
     * @param string $sessionToken Session token
     * @param int|null $userId Optional user ID as fallback
     * @return bool Success status
     */
    public function updateLogout($sessionToken, $userId = null)
    {
        if (empty($sessionToken) && empty($userId)) {
            return false;
        }

        $logoutAt = date('Y-m-d H:i:s');
        
        // Build WHERE clause
        $whereConditions = [];
        $params = [
            'logout_at' => $logoutAt,
            'status' => 'logged_out',
            'updated_at' => $logoutAt,
            'status_where' => 'active'
        ];

        if (!empty($sessionToken)) {
            $whereConditions[] = 'session_token = :token';
            $params['token'] = $sessionToken;
        }
        
        if (!empty($userId)) {
            $whereConditions[] = 'user_id = :user_id';
            $params['user_id'] = $userId;
        }

        $whereConditions[] = 'status = :status_where';
        $whereClause = implode(' AND ', $whereConditions);
        
        // Use direct query to avoid parameter conflicts
        $sql = "UPDATE {$this->table} 
                SET logout_at = :logout_at, status = :status, updated_at = :updated_at 
                WHERE {$whereClause}";

        try {
            $stmt = $this->db->query($sql, $params);
            $rowCount = $stmt->rowCount();
            
            if ($rowCount > 0) {
                return true;
            } else {
                // Log if no rows were updated
                error_log("Update logout: No matching record found. Token: " . substr($sessionToken ?? '', 0, 10) . "..., UserID: " . ($userId ?? 'N/A'));
                return false;
            }
        } catch (Exception $e) {
            error_log("Update logout error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark session as expired
     * @param string $sessionToken Session token
     * @return bool Success status
     */
    public function markExpired($sessionToken)
    {
        $data = [
            'status' => 'expired'
        ];

        return $this->db->update($this->table, $data, 'session_token = :token AND status = :status', [
            'token' => $sessionToken,
            'status' => 'active'
        ]) > 0;
    }

    /**
     * Get login logs for a specific user
     * @param int $userId User ID
     * @param int $limit Limit number of records
     * @return array Login logs
     */
    public function getUserLogs($userId, $limit = 50)
    {
        $limit = intval($limit); // PDO doesn't support binding for LIMIT
        $sql = "SELECT ll.*, u.username, u.namalengkap, u.email 
                FROM {$this->table} ll 
                INNER JOIN users u ON ll.user_id = u.id 
                WHERE ll.user_id = :userId 
                ORDER BY ll.login_at DESC 
                LIMIT {$limit}";
        
        return $this->db->fetchAll($sql, ['userId' => $userId]);
    }

    /**
     * Get active sessions for a user
     * @param int $userId User ID
     * @return array Active sessions
     */
    public function getActiveSessions($userId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :userId AND status = 'active' 
                ORDER BY login_at DESC";
        
        return $this->db->fetchAll($sql, ['userId' => $userId]);
    }

    /**
     * Get login log by session token
     * @param string $sessionToken Session token
     * @return array|null Login log or null
     */
    public function findByToken($sessionToken)
    {
        $sql = "SELECT * FROM {$this->table} WHERE session_token = :token";
        return $this->db->fetch($sql, ['token' => $sessionToken]);
    }

    /**
     * Get all login logs with pagination
     * @param int $page Page number
     * @param int $perPage Records per page
     * @param string|null $search Search query
     * @return array Paginated results
     */
    public function getAllLogs($page = 1, $perPage = 20, $search = null)
    {
        $where = '';
        $params = [];

        if ($search) {
            $where = "u.username LIKE :search OR u.namalengkap LIKE :search OR u.email LIKE :search OR ll.ip_address LIKE :search";
            $params['search'] = "%{$search}%";
        }

        $sql = "SELECT ll.*, u.username, u.namalengkap, u.email, u.role 
                FROM {$this->table} ll 
                INNER JOIN users u ON ll.user_id = u.id";
        
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        
        $sql .= " ORDER BY ll.login_at DESC";

        // Manual pagination since we're using JOIN
        // PDO doesn't support binding for LIMIT/OFFSET, so we use intval for safety
        $offset = ($page - 1) * $perPage;
        $limit = intval($perPage);
        $offset = intval($offset);
        $sql .= " LIMIT {$limit} OFFSET {$offset}";

        $results = $this->db->fetchAll($sql, $params);

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} ll INNER JOIN users u ON ll.user_id = u.id";
        if ($where) {
            $countSql .= " WHERE {$where}";
        }
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'] ?? 0;

        return [
            'data' => $results,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Get login statistics
     * @param int|null $userId User ID (optional, for user-specific stats)
     * @return array Statistics
     */
    public function getStatistics($userId = null)
    {
        $where = '';
        $params = [];

        if ($userId) {
            $where = "WHERE user_id = :userId";
            $params['userId'] = $userId;
        }

        $sql = "SELECT 
                    COUNT(*) as total_logins,
                    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_sessions,
                    COUNT(CASE WHEN status = 'logged_out' THEN 1 END) as logged_out,
                    COUNT(CASE WHEN status = 'expired' THEN 1 END) as expired,
                    COUNT(DISTINCT user_id) as unique_users,
                    COUNT(DISTINCT DATE(login_at)) as unique_days
                FROM {$this->table} {$where}";

        return $this->db->fetch($sql, $params);
    }

    /**
     * Clean up expired sessions (older than specified days)
     * @param int $days Days to keep
     * @return int Number of records updated
     */
    public function cleanupExpiredSessions($days = 30)
    {
        $sql = "UPDATE {$this->table} 
                SET status = 'expired' 
                WHERE status = 'active' 
                AND login_at < DATE_SUB(NOW(), INTERVAL :days DAY)";
        
        return $this->db->query($sql, ['days' => $days])->rowCount();
    }
}


<?php

/**
 * UsersMenu Model
 * Handles user menu access management
 */
class UsersMenu extends Model
{
    protected $table = 'users_menu';
    
    protected $fillable = [
        'user_id', 'group_id'
    ];
    
    protected $casts = [
        'user_id' => 'integer',
        'group_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Get all user menu access with user details and group menu names
     * Optimized: Single query with JOINs (No N+1 problem)
     */
    public function getAllWithDetails($filters = [])
    {
        $conditions = [];
        $params = [];
        
        // Build WHERE conditions based on filters
        if (!empty($filters['search'])) {
            $conditions[] = "(u.username LIKE ? OR u.namalengkap LIKE ? OR u.email LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($filters['role'])) {
            $conditions[] = "u.role = ?";
            $params[] = $filters['role'];
        }
        
        if (!empty($filters['status'])) {
            $conditions[] = "u.status = ?";
            $params[] = $filters['status'];
        }
        
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        // Get total count
        $countSql = "SELECT COUNT(DISTINCT u.id) as total
                     FROM users u
                     LEFT JOIN {$this->table} um ON u.id = um.user_id
                     $whereClause";
        
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'] ?? 0;
        
        // Calculate pagination
        $perPage = $filters['per_page'] ?? 10;
        $currentPage = $filters['page'] ?? 1;
        $offset = ($currentPage - 1) * $perPage;
        $lastPage = ceil($total / $perPage);
        
        // Build ORDER BY clause
        $orderBy = 'u.id DESC';
        if (!empty($filters['sort'])) {
            $sortField = $filters['sort'];
            $sortOrder = strtoupper($filters['order'] ?? 'ASC');
            
            // Map sort fields to actual column names
            $sortMap = [
                'username' => 'u.username',
                'namalengkap' => 'u.namalengkap',
                'email' => 'u.email',
                'role' => 'u.role',
                'status' => 'u.status'
            ];
            
            if (isset($sortMap[$sortField])) {
                $orderBy = $sortMap[$sortField] . ' ' . $sortOrder;
            }
        }
        
        // Get users with their menu groups
        $sql = "SELECT 
                    u.id,
                    u.username,
                    u.namalengkap,
                    u.email,
                    u.role,
                    u.status,
                    u.picture,
                    GROUP_CONCAT(DISTINCT mg.id ORDER BY mg.id) as group_ids,
                    GROUP_CONCAT(DISTINCT mg.name ORDER BY mg.id) as group_names,
                    GROUP_CONCAT(DISTINCT mg.icon ORDER BY mg.id SEPARATOR '|||') as group_icons
                FROM users u
                LEFT JOIN {$this->table} um ON u.id = um.user_id
                LEFT JOIN menu_groups mg ON um.group_id = mg.id
                $whereClause
                GROUP BY u.id, u.username, u.namalengkap, u.email, u.role, u.status, u.picture
                ORDER BY $orderBy
                LIMIT ? OFFSET ?";
        
        $params[] = (int)$perPage;
        $params[] = (int)$offset;
        
        $users = $this->db->fetchAll($sql, $params);
        
        // Process the results to split group data into arrays
        foreach ($users as &$user) {
            if (!empty($user['group_ids'])) {
                $user['group_ids'] = explode(',', $user['group_ids']);
                $user['group_names'] = explode(',', $user['group_names']);
                $user['group_icons'] = explode('|||', $user['group_icons']);
            } else {
                $user['group_ids'] = [];
                $user['group_names'] = [];
                $user['group_icons'] = [];
            }
        }
        
        return [
            'data' => $users,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'last_page' => $lastPage,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }
    
    /**
     * Get menu groups for a specific user
     */
    public function getUserMenuGroups($userId)
    {
        $sql = "SELECT mg.* 
                FROM menu_groups mg
                INNER JOIN {$this->table} um ON mg.id = um.group_id
                WHERE um.user_id = ?
                ORDER BY mg.sort_order ASC";
        
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    /**
     * Add menu group access for user
     */
    public function addUserMenuAccess($userId, $groupId)
    {
        try {
            // Check if already exists
            $exists = $this->checkUserMenuAccess($userId, $groupId);
            if ($exists) {
                return true; // Already exists, no need to add
            }
            
            $sql = "INSERT INTO {$this->table} (user_id, group_id) VALUES (?, ?)";
            $this->db->query($sql, [$userId, $groupId]);
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Remove menu group access for user
     */
    public function removeUserMenuAccess($userId, $groupId)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND group_id = ?";
            $this->db->query($sql, [$userId, $groupId]);
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Check if user has access to a specific menu group
     */
    public function checkUserMenuAccess($userId, $groupId)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = ? AND group_id = ?";
        $result = $this->db->fetch($sql, [$userId, $groupId]);
        
        return $result['count'] > 0;
    }
    
    /**
     * Sync user menu groups (replace all with new ones)
     */
    public function syncUserMenuGroups($userId, $groupIds = [])
    {
        try {
            $database = Database::getInstance();
            
            $database->beginTransaction();
            
            // Remove all existing menu groups for this user
            $deleteSql = "DELETE FROM {$this->table} WHERE user_id = ?";
            $database->query($deleteSql, [$userId]);
            
            // Add new menu groups
            if (!empty($groupIds)) {
                foreach ($groupIds as $groupId) {
                    $insertSql = "INSERT INTO {$this->table} (user_id, group_id) VALUES (?, ?)";
                    $database->query($insertSql, [$userId, $groupId]);
                }
            }
            
            $database->commit();
            return true;
        } catch (Exception $e) {
            error_log("UsersMenu syncUserMenuGroups error: " . $e->getMessage());
            
            if (isset($database) && $database->inTransaction()) {
                $database->rollback();
            }
            throw $e; // Re-throw to be caught by controller
        }
    }
    
    /**
     * Get all users without pagination (for dropdown, etc)
     */
    public function getAllUsers()
    {
        $sql = "SELECT id, username, namalengkap, email, role FROM users WHERE status = 'aktif' ORDER BY namalengkap ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get all menu groups (for selection)
     */
    public function getAllMenuGroups()
    {
        $sql = "SELECT id, name, icon, description FROM menu_groups WHERE is_active = 1 ORDER BY sort_order ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Delete all menu access for a user
     */
    public function deleteUserMenuAccess($userId)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE user_id = ?";
            $this->db->query($sql, [$userId]);
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Assign default menu group to user based on their role
     * Automatically called when a new user is created/registered
     */
    public function assignDefaultMenuByRole($userId, $role)
    {
        try {
            // Get MenuGroup model to fetch default menu group for this role
            require_once 'app/models/MenuGroup.php';
            $menuGroupModel = new MenuGroup();
            
            // Get default menu group ID for this role
            $defaultGroupId = $menuGroupModel->getDefaultMenuGroupByRole($role);
            
            // If no default group found, return true (no error, just no default to assign)
            if (!$defaultGroupId) {
                return true;
            }
            
            // Assign default menu group to the user
            return $this->addUserMenuAccess($userId, $defaultGroupId);
        } catch (Exception $e) {
            error_log("UsersMenu assignDefaultMenuByRole - Error: " . $e->getMessage());
            return false;
        }
    }
}


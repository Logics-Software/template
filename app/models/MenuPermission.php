<?php

/**
 * MenuPermission Model
 * Handles role-based menu permissions
 */
class MenuPermission extends Model
{
    protected $table = 'role_menu_permissions';
    
    protected $fillable = [
        'role_id', 'module_id', 'group_id', 
        'permission_type', 'granted_by'
    ];
    
    protected $casts = [
        'granted_at' => 'datetime'
    ];
    
    /**
     * Get permissions for a specific role
     */
    public function getRolePermissions($roleId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE role_id = ?";
        return $this->db->fetchAll($sql, [$roleId]);
    }
    
    /**
     * Check if role has permission for module
     */
    public function hasModulePermission($roleId, $moduleId, $permissionType = 'view')
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE role_id = ? AND module_id = ? AND permission_type = ?";
        $result = $this->db->fetch($sql, [$roleId, $moduleId, $permissionType]);
        return $result['count'] > 0;
    }
    
    /**
     * Check if role has permission for group
     */
    public function hasGroupPermission($roleId, $groupId, $permissionType = 'view')
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE role_id = ? AND group_id = ? AND permission_type = ?";
        $result = $this->db->fetch($sql, [$roleId, $groupId, $permissionType]);
        return $result['count'] > 0;
    }
    
    /**
     * Grant permission to role
     */
    public function grantPermission($roleId, $moduleId = null, $groupId = null, $permissionType = 'view', $grantedBy = null)
    {
        try {
            $sql = "INSERT INTO {$this->table} (role_id, module_id, group_id, permission_type, granted_by) 
                    VALUES (?, ?, ?, ?, ?)";
            $this->db->query($sql, [$roleId, $moduleId, $groupId, $permissionType, $grantedBy]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Revoke permission from role
     */
    public function revokePermission($roleId, $moduleId = null, $groupId = null)
    {
        try {
            if ($moduleId) {
                $sql = "DELETE FROM {$this->table} WHERE role_id = ? AND module_id = ?";
                $this->db->query($sql, [$roleId, $moduleId]);
            } elseif ($groupId) {
                $sql = "DELETE FROM {$this->table} WHERE role_id = ? AND group_id = ?";
                $this->db->query($sql, [$roleId, $groupId]);
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Update permission type
     */
    public function updatePermission($roleId, $moduleId = null, $groupId = null, $permissionType = 'view')
    {
        try {
            if ($moduleId) {
                $sql = "UPDATE {$this->table} SET permission_type = ? WHERE role_id = ? AND module_id = ?";
                $this->db->query($sql, [$permissionType, $roleId, $moduleId]);
            } elseif ($groupId) {
                $sql = "UPDATE {$this->table} SET permission_type = ? WHERE role_id = ? AND group_id = ?";
                $this->db->query($sql, [$permissionType, $roleId, $groupId]);
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get all modules accessible by role
     */
    public function getAccessibleModules($roleId)
    {
        $sql = "SELECT DISTINCT m.*, rmp.permission_type 
                FROM modules m
                INNER JOIN {$this->table} rmp ON m.id = rmp.module_id
                WHERE rmp.role_id = ? AND m.is_menu_item = 1
                ORDER BY m.sort_order ASC";
        return $this->db->fetchAll($sql, [$roleId]);
    }
    
    /**
     * Get all groups accessible by role
     */
    public function getAccessibleGroups($roleId)
    {
        $sql = "SELECT DISTINCT mg.*, rmp.permission_type 
                FROM menu_groups mg
                INNER JOIN {$this->table} rmp ON mg.id = rmp.group_id
                WHERE rmp.role_id = ? AND mg.is_active = 1
                ORDER BY mg.sort_order ASC";
        return $this->db->fetchAll($sql, [$roleId]);
    }
    
    /**
     * Bulk grant permissions
     */
    public function bulkGrantPermissions($roleId, $permissions, $grantedBy = null)
    {
        try {
            $this->db->beginTransaction();
            
            foreach ($permissions as $permission) {
                $this->grantPermission(
                    $roleId,
                    $permission['module_id'] ?? null,
                    $permission['group_id'] ?? null,
                    $permission['permission_type'] ?? 'view',
                    $grantedBy
                );
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    /**
     * Bulk revoke permissions
     */
    public function bulkRevokePermissions($roleId, $permissions)
    {
        try {
            $this->db->beginTransaction();
            
            foreach ($permissions as $permission) {
                $this->revokePermission(
                    $roleId,
                    $permission['module_id'] ?? null,
                    $permission['group_id'] ?? null
                );
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    /**
     * Get permission matrix for all roles and modules
     */
    public function getPermissionMatrix()
    {
        $sql = "SELECT 
                    rmp.role_id,
                    rmp.module_id,
                    rmp.group_id,
                    rmp.permission_type,
                    m.caption as module_name,
                    mg.name as group_name
                FROM {$this->table} rmp
                LEFT JOIN modules m ON rmp.module_id = m.id
                LEFT JOIN menu_groups mg ON rmp.group_id = mg.id
                ORDER BY rmp.role_id, rmp.module_id, rmp.group_id";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Check if user has permission for module
     */
    public function userHasModulePermission($userId, $moduleId, $permissionType = 'view')
    {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} rmp
                INNER JOIN users u ON rmp.role_id = u.role
                WHERE u.id = ? AND rmp.module_id = ? AND rmp.permission_type = ?";
        $result = $this->db->fetch($sql, [$userId, $moduleId, $permissionType]);
        return $result['count'] > 0;
    }
    
    /**
     * Check if user has permission for group
     */
    public function userHasGroupPermission($userId, $groupId, $permissionType = 'view')
    {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} rmp
                INNER JOIN users u ON rmp.role_id = u.role
                WHERE u.id = ? AND rmp.group_id = ? AND rmp.permission_type = ?";
        $result = $this->db->fetch($sql, [$userId, $groupId, $permissionType]);
        return $result['count'] > 0;
    }
}

<?php
/**
 * Menu Item Model
 * Represents individual menu items that reference modules
 */
class MenuItem extends Model
{
    protected $table = 'menu_items';
    
    protected $fillable = [
        'group_id',
        'parent_id',
        'module_id',
        'name',
        'icon',
        'sort_order',
        'is_active',
        'is_parent'
    ];
    
    protected $casts = [
        'group_id' => 'integer',
        'parent_id' => 'integer',
        'module_id' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'is_parent' => 'boolean'
    ];
    
    // Instance properties for relationship methods
    protected $group_id;
    protected $parent_id;
    protected $module_id;
    protected $id;
    
    /**
     * Get the menu group this item belongs to
     */
    public function menuGroup()
    {
        $sql = "SELECT * FROM menu_groups WHERE id = ?";
        return $this->db->fetch($sql, [$this->group_id]);
    }
    
    /**
     * Get the module this item references
     */
    public function module()
    {
        if (!$this->module_id) {
            return null;
        }
        
        $sql = "SELECT * FROM modules WHERE id = ?";
        return $this->db->fetch($sql, [$this->module_id]);
    }
    
    /**
     * Get parent menu item (if this is a child)
     */
    public function parent()
    {
        if (!$this->parent_id) {
            return null;
        }
        
        $sql = "SELECT * FROM menu_items WHERE id = ?";
        return $this->db->fetch($sql, [$this->parent_id]);
    }
    
    /**
     * Get child menu items (if this is a parent)
     */
    public function children()
    {
        $sql = "SELECT * FROM menu_items WHERE parent_id = ? ORDER BY sort_order ASC";
        return $this->db->fetchAll($sql, [$this->id]);
    }
    
    /**
     * Get all menu items
     */
    public function getAll()
    {
        $sql = "SELECT * FROM menu_items ORDER BY group_id ASC, sort_order ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get all menu items for a group
     */
    public function getItemsByGroup($groupId)
    {
        $sql = "SELECT * FROM menu_items WHERE group_id = ? ORDER BY sort_order ASC";
        return $this->db->fetchAll($sql, [$groupId]);
    }
    
    /**
     * Count menu items for a group
     */
    public function countMenuItemsByGroup($groupId)
    {
        $sql = "SELECT COUNT(*) as count FROM menu_items WHERE group_id = ?";
        $result = $this->db->fetch($sql, [$groupId]);
        return $result['count'] ?? 0;
    }
    
    /**
     * Get all menu items with their children
     */
    public function getItemsWithChildren($groupId)
    {
        $items = $this->getItemsByGroup($groupId);
        
        foreach ($items as &$item) {
            if ($item['is_parent']) {
                $item['children'] = $this->getChildren($item['id']);
            }
        }
        
        return $items;
    }
    
    /**
     * Get children of a menu item
     */
    public function getChildren($parentId)
    {
        $sql = "SELECT * FROM menu_items WHERE parent_id = ? ORDER BY sort_order ASC";
        return $this->db->fetchAll($sql, [$parentId]);
    }
    
    /**
     * Get a specific menu item by ID
     */
    public function getItem($itemId)
    {
        $sql = "SELECT * FROM menu_items WHERE id = ?";
        return $this->db->fetch($sql, [$itemId]);
    }
    
    /**
     * Create a new menu item
     */
    public function createItem($data)
    {
        $sql = "INSERT INTO menu_items (group_id, parent_id, module_id, name, icon, sort_order, is_active, is_parent) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $this->db->query($sql, [
            $data['group_id'],
            $data['parent_id'] ?? null,
            $data['module_id'] ?? null,
            $data['name'],
            $data['icon'] ?? 'fas fa-circle',
            $data['sort_order'] ?? 0,
            $data['is_active'] ?? true,
            $data['is_parent'] ?? false
        ]);
    }
    
    /**
     * Update a menu item
     */
    public function updateItem($id, $data)
    {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $fields[] = "{$key} = ?";
                $values[] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $values[] = $id;
        $sql = "UPDATE menu_items SET " . implode(', ', $fields) . " WHERE id = ?";
        
        return $this->db->query($sql, $values);
    }
    
    /**
     * Delete a menu item and its children
     */
    public function deleteItem($id)
    {
        $database = Database::getInstance();
        $database->beginTransaction();
        
        try {
            // Delete children first
            $sql = "DELETE FROM menu_items WHERE parent_id = ?";
            $database->query($sql, [$id]);
            
            // Delete the item itself
            $sql = "DELETE FROM menu_items WHERE id = ?";
            $database->query($sql, [$id]);
            
            // Delete permissions
            $sql = "DELETE FROM role_menu_permissions WHERE menu_item_id = ?";
            $database->query($sql, [$id]);
            
            $database->commit();
            return true;
        } catch (Exception $e) {
            $database->rollback();
            return false;
        }
    }
    
    /**
     * Update sort order
     */
    public function updateSortOrder($orders)
    {
        $database = Database::getInstance();
        $database->beginTransaction();
        
        try {
            foreach ($orders as $order) {
                $sql = "UPDATE menu_items SET sort_order = ? WHERE id = ?";
                $database->query($sql, [$order['sort_order'], $order['id']]);
            }
            
            $database->commit();
            return true;
        } catch (Exception $e) {
            $database->rollback();
            return false;
        }
    }
    
    /**
     * Get available modules for selection
     */
    public function getAvailableModules()
    {
        $sql = "SELECT * FROM modules ORDER BY caption ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Check if user has permission for this menu item
     */
    public function hasPermission($roleId, $menuItemId)
    {
        $sql = "SELECT COUNT(*) as count FROM role_menu_permissions 
                WHERE role_id = ? AND menu_item_id = ?";
        $result = $this->db->fetch($sql, [$roleId, $menuItemId]);
        return $result['count'] > 0;
    }
}

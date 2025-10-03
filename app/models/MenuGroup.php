<?php

/**
 * MenuGroup Model
 * Handles menu groups management
 */
class MenuGroup extends Model
{
    protected $table = 'menu_groups';
    
    protected $fillable = [
        'name', 'slug', 'icon', 'description', 
        'sort_order', 'is_active', 'is_collapsible'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'is_collapsible' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Get all active menu groups ordered by sort_order
     */
    public function getAllActive()
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY sort_order ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get menu group by slug
     */
    public function getBySlug($slug)
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = ? AND is_active = 1";
        return $this->db->fetch($sql, [$slug]);
    }
    
    /**
     * Create new menu group
     */
    public function createGroup($data)
    {
        try {
            $sql = "INSERT INTO {$this->table} (name, slug, icon, description, sort_order, is_active, is_collapsible) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, [
                $data['name'],
                $data['slug'],
                $data['icon'] ?? 'fas fa-folder',
                $data['description'] ?? null,
                $data['sort_order'] ?? 0,
                $data['is_active'] ?? true,
                $data['is_collapsible'] ?? true
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Update menu group
     */
    public function updateGroup($id, $data)
    {
        try {
            $sql = "UPDATE {$this->table} SET name = ?, slug = ?, icon = ?, description = ?, 
                    sort_order = ?, is_active = ?, is_collapsible = ?, updated_at = CURRENT_TIMESTAMP 
                    WHERE id = ?";
            $this->db->query($sql, [
                $data['name'],
                $data['slug'],
                $data['icon'] ?? 'fas fa-folder',
                $data['description'] ?? null,
                $data['sort_order'] ?? 0,
                $data['is_active'] ?? true,
                $data['is_collapsible'] ?? true,
                $id
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Delete menu group
     */
    public function deleteGroup($id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            $this->db->query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get menu items in this group
     */
    public function getMenuItems($groupId = null)
    {
        $id = $groupId ?? $this->id ?? null;
        if (!$id) {
            return [];
        }
        
        $sql = "SELECT * FROM menu_items WHERE group_id = ? AND parent_id IS NULL ORDER BY sort_order ASC";
        return $this->db->fetchAll($sql, [$id]);
    }
    
    /**
     * Check if group has menu items
     */
    public function hasMenuItems($groupId = null)
    {
        $id = $groupId ?? $this->id ?? null;
        if (!$id) {
            return false;
        }
        
        $sql = "SELECT COUNT(*) as count FROM menu_items WHERE group_id = ?";
        $result = $this->db->fetch($sql, [$id]);
        return $result['count'] > 0;
    }
    
    /**
     * Get all menu groups
     */
    public function getAllGroups()
    {
        $sql = "SELECT * FROM menu_groups ORDER BY sort_order ASC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get a specific group by ID
     */
    public function getGroup($groupId)
    {
        $sql = "SELECT * FROM menu_groups WHERE id = ?";
        return $this->db->fetch($sql, [$groupId]);
    }
    
    /**
     * Update sort order
     */
    public function updateSortOrder($id, $newOrder)
    {
        try {
            $sql = "UPDATE {$this->table} SET sort_order = ? WHERE id = ?";
            $this->db->query($sql, [$newOrder, $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Generate unique slug
     */
    public function generateUniqueSlug($name, $excludeId = null)
    {
        $baseSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $slug = $baseSlug;
        $counter = 1;
        
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Check if slug exists
     */
    private function slugExists($slug, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE slug = ?";
        $params = [$slug];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
}

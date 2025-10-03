<?php

class CallCenter extends Model
{
    protected $table = 'call_center';
    
    protected $fillable = ['judul', 'nomorwa', 'deskripsi', 'sort_order'];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Get all call center entries
     */
    public function getAll()
    {
        $sql = "SELECT * FROM call_center ORDER BY sort_order ASC, created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get call center by ID
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM call_center WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Create new call center entry
     */
    public function createEntry($data)
    {
        try {
            $sql = "INSERT INTO call_center (judul, nomorwa, deskripsi) VALUES (?, ?, ?)";
            $this->db->query($sql, [$data['judul'], $data['nomorwa'], $data['deskripsi']]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Update call center entry
     */
    public function updateEntry($id, $data)
    {
        try {
            $sql = "UPDATE call_center SET judul = ?, nomorwa = ?, deskripsi = ? WHERE id = ?";
            $this->db->query($sql, [$data['judul'], $data['nomorwa'], $data['deskripsi'], $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Delete call center entry
     */
    public function deleteEntry($id)
    {
        try {
            $sql = "DELETE FROM call_center WHERE id = ?";
            $this->db->query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Search call center entries
     */
    public function searchEntries($query)
    {
        $sql = "SELECT * FROM call_center WHERE judul LIKE ? OR nomorwa LIKE ? OR deskripsi LIKE ? ORDER BY created_at DESC";
        $searchTerm = "%{$query}%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }
    
    /**
     * Get paginated call center entries
     */
    public function getPaginated($page = 1, $perPage = 10, $search = '')
    {
        $offset = ($page - 1) * $perPage;
        
        // Build WHERE clause for search
        $whereClause = '';
        $params = [];
        
        if (!empty($search)) {
            $whereClause = 'WHERE judul LIKE ? OR nomorwa LIKE ? OR deskripsi LIKE ?';
            $searchTerm = "%{$search}%";
            $params = [$searchTerm, $searchTerm, $searchTerm];
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM call_center {$whereClause}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'] ?? 0;
        
        
        // Get paginated data
        $sql = "SELECT * FROM call_center {$whereClause} ORDER BY sort_order ASC, created_at DESC LIMIT {$perPage} OFFSET {$offset}";
        $data = $this->db->fetchAll($sql, $params);
        
        $totalPages = ceil($total / $perPage);
        $hasNext = $page < $totalPages;
        $hasPrev = $page > 1;
        
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
            'has_next' => $hasNext,
            'has_prev' => $hasPrev
        ];
    }
}
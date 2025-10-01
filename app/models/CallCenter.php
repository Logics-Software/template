<?php

class CallCenter extends Model
{
    protected $table = 'call_center';
    
    protected $fillable = ['judul', 'nomorwa', 'deskripsi'];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Get all call center entries
     */
    public static function getAll()
    {
        $model = new self();
        $sql = "SELECT * FROM call_center ORDER BY created_at DESC";
        return $model->db->fetchAll($sql);
    }
    
    /**
     * Get call center by ID
     */
    public static function getById($id)
    {
        $model = new self();
        $sql = "SELECT * FROM call_center WHERE id = ?";
        return $model->db->fetch($sql, [$id]);
    }
    
    /**
     * Create new call center entry
     */
    public static function createEntry($data)
    {
        try {
            $model = new self();
            $sql = "INSERT INTO call_center (judul, nomorwa, deskripsi) VALUES (?, ?, ?)";
            $model->db->query($sql, [$data['judul'], $data['nomorwa'], $data['deskripsi']]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Update call center entry
     */
    public static function updateEntry($id, $data)
    {
        try {
            $model = new self();
            $sql = "UPDATE call_center SET judul = ?, nomorwa = ?, deskripsi = ? WHERE id = ?";
            $model->db->query($sql, [$data['judul'], $data['nomorwa'], $data['deskripsi'], $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Delete call center entry
     */
    public static function deleteEntry($id)
    {
        try {
            $model = new self();
            $sql = "DELETE FROM call_center WHERE id = ?";
            $model->db->query($sql, [$id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Search call center entries
     */
    public static function searchEntries($query)
    {
        $model = new self();
        $sql = "SELECT * FROM call_center WHERE judul LIKE ? OR nomorwa LIKE ? OR deskripsi LIKE ? ORDER BY created_at DESC";
        $searchTerm = "%{$query}%";
        return $model->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }
}
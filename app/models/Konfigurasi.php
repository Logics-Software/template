<?php

class Konfigurasi extends Model
{
    protected $table = 'konfigurasi';
    
    protected $fillable = [
        'namaperusahaan',
        'alamatperusahaan', 
        'npwp',
        'noijin',
        'penanggungjawab',
        'logo'
    ];

    /**
     * Get the first and only configuration record
     */
    public function getConfiguration()
    {
        $sql = "SELECT * FROM {$this->table} LIMIT 1";
        return $this->db->fetch($sql);
    }

    /**
     * Update the configuration record
     */
    public function updateConfiguration($data)
    {
        // Filter data to only include fillable fields
        $filteredData = $this->filterFillable($data);
        
        // Ensure all values are strings (not arrays)
        foreach ($filteredData as $key => $value) {
            if (is_array($value)) {
                $filteredData[$key] = '';
            } else {
                $filteredData[$key] = (string) $value;
            }
        }
        
        // Add updated_at timestamp
        $filteredData['updated_at'] = date('Y-m-d H:i:s');
        
        // Use the existing update method from base Model class
        return $this->db->update($this->table, $filteredData, 'id = :id', ['id' => 1]);
    }

    /**
     * Check if configuration exists
     */
    public function configurationExists()
    {
        $count = $this->db->count($this->table);
        return $count > 0;
    }
}

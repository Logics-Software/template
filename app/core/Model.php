<?php
/**
 * Base Model class
 */
abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $timestamps = true;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    public function findAll($where = '', $params = [])
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        return $this->db->fetchAll($sql, $params);
    }

    public function create($data)
    {
        $data = $this->filterFillable($data);
        
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $data = $this->filterFillable($data);
        
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->db->update($this->table, $data, "{$this->primaryKey} = :id", ['id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, "{$this->primaryKey} = :id", ['id' => $id]);
    }

    public function paginate($page = 1, $perPage = DEFAULT_PAGE_SIZE, $where = '', $params = [], $sort = null, $order = 'asc')
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        
        // Add sorting
        if ($sort) {
            $sql .= " ORDER BY {$sort} {$order}";
        } else {
            $sql .= " ORDER BY {$this->primaryKey} DESC";
        }
        
        return $this->db->paginate($sql, $params, $page, $perPage);
    }

    public function count($where = '', $params = [])
    {
        return $this->db->count($this->table, $where, $params);
    }

    public function search($query, $fields, $where = '', $params = [])
    {
        $searchConditions = [];
        foreach ($fields as $field) {
            $searchConditions[] = "{$field} LIKE :search";
        }
        $searchCondition = implode(' OR ', $searchConditions);
        
        $sql = "SELECT * FROM {$this->table} WHERE ({$searchCondition})";
        if ($where) {
            $sql .= " AND ({$where})";
        }
        $sql .= " ORDER BY {$this->primaryKey} DESC";
        
        $searchParams = array_merge(['search' => "%{$query}%"], $params);
        return $this->db->fetchAll($sql, $searchParams);
    }

    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollback()
    {
        $this->db->rollback();
    }
}

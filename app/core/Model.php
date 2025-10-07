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

    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    public function findAll(string $where = '', array $params = []): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        return $this->db->fetchAll($sql, $params);
    }

    public function create(array $data): int
    {
        $data = $this->filterFillable($data);
        
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->db->insert($this->table, $data);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->filterFillable($data);
        
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->db->update($this->table, $data, "{$this->primaryKey} = :id", ['id' => $id]) > 0;
    }

    public function delete(int $id): bool
    {
        return $this->db->delete($this->table, "{$this->primaryKey} = :id", ['id' => $id]) > 0;
    }

    public function paginate(int $page = 1, int $perPage = DEFAULT_PAGE_SIZE, string $where = '', array $params = [], ?string $sort = null, string $order = 'asc'): array
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

    public function count(string $where = '', array $params = []): int
    {
        return $this->db->count($this->table, $where, $params);
    }

    public function search(string $query, array $fields, string $where = '', array $params = []): array
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

    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }

    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }

    public function commit(): void
    {
        $this->db->commit();
    }

    public function rollback(): void
    {
        $this->db->rollback();
    }
}

<?php
/**
 * Database class with support for MySQL, SQL Server, and PostgreSQL
 */
class Database
{
    private static $instance = null;
    private $connection = null;
    private $transactionStarted = false;

    private function __construct()
    {
        $this->connect();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect()
    {
        try {
            $dsn = $this->getDSN();
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    private function getDSN()
    {
        switch (DB_TYPE) {
            case 'mysql':
                return "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            case 'sqlsrv':
                return "sqlsrv:Server=" . DB_HOST . "," . DB_PORT . ";Database=" . DB_NAME;
            case 'pgsql':
                return "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
            default:
                throw new Exception("Unsupported database type: " . DB_TYPE);
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }

    public function fetch($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function insert($table, $data)
    {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        
        return $this->connection->lastInsertId();
    }

    public function update($table, $data, $where, $whereParams = [])
    {
        $setClause = [];
        foreach (array_keys($data) as $column) {
            $setClause[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setClause);
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        $params = array_merge($data, $whereParams);
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function delete($table, $where, $params = [])
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function beginTransaction()
    {
        if (!$this->transactionStarted) {
            $this->connection->beginTransaction();
            $this->transactionStarted = true;
        }
    }

    public function commit()
    {
        if ($this->transactionStarted) {
            $this->connection->commit();
            $this->transactionStarted = false;
        }
    }

    public function rollback()
    {
        if ($this->transactionStarted) {
            $this->connection->rollback();
            $this->transactionStarted = false;
        }
    }

    public function inTransaction()
    {
        return $this->transactionStarted;
    }

    public function count($table, $where = '', $params = [])
    {
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        
        $result = $this->fetch($sql, $params);
        return (int) $result['count'];
    }

    public function paginate($sql, $params = [], $page = 1, $perPage = DEFAULT_PAGE_SIZE)
    {
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM ({$sql}) as count_table";
        $total = $this->fetch($countSql, $params)['total'];
        
        // Add pagination to main query
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        $data = $this->fetchAll($sql, $params);
        
        return [
            'data' => $data,
            'total' => (int) $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }

    /**
     * Get database version information
     */
    public function getVersion()
    {
        try {
            if (!$this->connection) {
                return 'Not connected';
            }

            switch (DB_TYPE) {
                case 'mysql':
                    $result = $this->query("SELECT VERSION() as version")->fetch();
                    return 'MySQL ' . $result['version'];
                case 'sqlsrv':
                    $result = $this->query("SELECT @@VERSION as version")->fetch();
                    return 'SQL Server ' . $result['version'];
                case 'pgsql':
                    $result = $this->query("SELECT version() as version")->fetch();
                    return 'PostgreSQL ' . $result['version'];
                default:
                    return DB_TYPE . ' (Unknown version)';
            }
        } catch (Exception $e) {
            return 'Error getting version: ' . $e->getMessage();
        }
    }
}

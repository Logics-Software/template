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

    public static function getInstance(): self
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

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }

    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function insert(string $table, array $data): int
    {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        
        return (int) $this->connection->lastInsertId();
    }

    public function update(string $table, array $data, string $where, array $whereParams = []): int
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

    public function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function beginTransaction(): void
    {
        if (!$this->transactionStarted) {
            $this->connection->beginTransaction();
            $this->transactionStarted = true;
        }
    }

    public function commit(): void
    {
        if ($this->transactionStarted) {
            $this->connection->commit();
            $this->transactionStarted = false;
        }
    }

    public function rollback(): void
    {
        if ($this->transactionStarted) {
            $this->connection->rollback();
            $this->transactionStarted = false;
        }
    }

    public function inTransaction(): bool
    {
        return $this->transactionStarted;
    }

    public function count(string $table, string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        
        $result = $this->fetch($sql, $params);
        return (int) ($result['count'] ?? 0);
    }

    public function paginate(string $sql, array $params = [], int $page = 1, int $perPage = DEFAULT_PAGE_SIZE): array
    {
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM ({$sql}) as count_table";
        $total = $this->fetch($countSql, $params)['total'] ?? 0;
        
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
    public function getVersion(): string
    {
        try {
            if (!$this->connection) {
                return 'Not connected';
            }

            switch (DB_TYPE) {
                case 'mysql':
                    $result = $this->query("SELECT VERSION() as version")->fetch();
                    return 'MySQL ' . ($result['version'] ?? 'Unknown');
                case 'sqlsrv':
                    $result = $this->query("SELECT @@VERSION as version")->fetch();
                    return 'SQL Server ' . ($result['version'] ?? 'Unknown');
                case 'pgsql':
                    $result = $this->query("SELECT version() as version")->fetch();
                    return 'PostgreSQL ' . ($result['version'] ?? 'Unknown');
                default:
                    return DB_TYPE . ' (Unknown version)';
            }
        } catch (Exception $e) {
            return 'Error getting version: ' . $e->getMessage();
        }
    }
}

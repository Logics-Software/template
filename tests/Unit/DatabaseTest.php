<?php
/**
 * Database Unit Tests
 */
class DatabaseTest extends PHPUnit\Framework\TestCase
{
    private Database $db;

    protected function setUp(): void
    {
        $this->db = Database::getInstance();
    }

    public function testGetInstanceReturnsSingleton(): void
    {
        $instance1 = Database::getInstance();
        $instance2 = Database::getInstance();
        
        $this->assertSame($instance1, $instance2);
    }

    public function testGetConnectionReturnsPDO(): void
    {
        $connection = $this->db->getConnection();
        
        $this->assertInstanceOf(PDO::class, $connection);
    }

    public function testQueryWithValidSQL(): void
    {
        $result = $this->db->query('SELECT 1 as test');
        
        $this->assertInstanceOf(PDOStatement::class, $result);
    }

    public function testFetchReturnsArray(): void
    {
        $result = $this->db->fetch('SELECT 1 as test');
        
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['test']);
    }

    public function testFetchAllReturnsArray(): void
    {
        $result = $this->db->fetchAll('SELECT 1 as test UNION SELECT 2 as test');
        
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testTransactionMethods(): void
    {
        $this->assertFalse($this->db->inTransaction());
        
        $this->db->beginTransaction();
        $this->assertTrue($this->db->inTransaction());
        
        $this->db->commit();
        $this->assertFalse($this->db->inTransaction());
    }
}

<?php
/**
 * Authentication Integration Tests
 */
class AuthTest extends PHPUnit\Framework\TestCase
{
    private Database $db;

    protected function setUp(): void
    {
        $this->db = Database::getInstance();
        
        // Create test users table
        $this->db->query("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(20) DEFAULT 'user',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    protected function tearDown(): void
    {
        // Clean up test data
        $this->db->query("DROP TABLE IF EXISTS users");
    }

    public function testUserRegistration(): void
    {
        $userData = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'role' => 'user'
        ];

        $userId = $this->db->insert('users', $userData);
        
        $this->assertIsInt($userId);
        $this->assertGreaterThan(0, $userId);
    }

    public function testUserLogin(): void
    {
        // Create test user
        $userData = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'role' => 'user'
        ];
        $userId = $this->db->insert('users', $userData);

        // Test login
        $user = $this->db->fetch(
            'SELECT * FROM users WHERE username = :username',
            ['username' => 'testuser']
        );

        $this->assertIsArray($user);
        $this->assertEquals('testuser', $user['username']);
        $this->assertTrue(password_verify('password123', $user['password']));
    }

    public function testUserUpdate(): void
    {
        // Create test user
        $userData = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'role' => 'user'
        ];
        $userId = $this->db->insert('users', $userData);

        // Update user
        $updateData = [
            'email' => 'updated@example.com',
            'role' => 'admin'
        ];
        $affectedRows = $this->db->update('users', $updateData, 'id = :id', ['id' => $userId]);

        $this->assertEquals(1, $affectedRows);

        // Verify update
        $user = $this->db->fetch('SELECT * FROM users WHERE id = :id', ['id' => $userId]);
        $this->assertEquals('updated@example.com', $user['email']);
        $this->assertEquals('admin', $user['role']);
    }

    public function testUserDeletion(): void
    {
        // Create test user
        $userData = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'role' => 'user'
        ];
        $userId = $this->db->insert('users', $userData);

        // Delete user
        $affectedRows = $this->db->delete('users', 'id = :id', ['id' => $userId]);

        $this->assertEquals(1, $affectedRows);

        // Verify deletion
        $user = $this->db->fetch('SELECT * FROM users WHERE id = :id', ['id' => $userId]);
        $this->assertFalse($user);
    }
}

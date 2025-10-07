<?php
/**
 * Login Feature Tests
 */

// Try to load Composer autoloader if available
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}

// Check if PHPUnit is available
if (!class_exists('PHPUnit\Framework\TestCase')) {
    // Create a minimal TestCase class for testing without PHPUnit
    abstract class TestCase
    {
        protected function setUp(): void {}
        protected function tearDown(): void {}
        
        protected function assertTrue($condition, string $message = ''): void
        {
            if (!$condition) {
                throw new AssertionError($message ?: 'Assertion failed: Expected true');
            }
        }
        
        protected function assertFalse($condition, string $message = ''): void
        {
            if ($condition) {
                throw new AssertionError($message ?: 'Assertion failed: Expected false');
            }
        }
        
        protected function assertIsArray($value, string $message = ''): void
        {
            if (!is_array($value)) {
                throw new AssertionError($message ?: 'Assertion failed: Expected array');
            }
        }
    }
} else {
    // Use PHPUnit TestCase if available
    class_alias('PHPUnit\Framework\TestCase', 'TestCase');
}

class LoginTest extends TestCase
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

        // Create test user
        $userData = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'role' => 'user'
        ];
        $this->db->insert('users', $userData);
    }

    protected function tearDown(): void
    {
        // Clean up test data
        $this->db->query("DROP TABLE IF EXISTS users");
    }

    public function testValidLogin(): void
    {
        // Simulate login request
        $_POST['username'] = 'testuser';
        $_POST['password'] = 'password123';
        $_POST['_token'] = 'test_token';

        // Mock session
        Session::set('_csrf_token', 'test_token');

        // Test login logic
        $user = $this->db->fetch(
            'SELECT * FROM users WHERE username = :username',
            ['username' => 'testuser']
        );

        $this->assertIsArray($user);
        $this->assertTrue(password_verify('password123', $user['password']));
    }

    public function testInvalidLogin(): void
    {
        // Simulate invalid login request
        $_POST['username'] = 'testuser';
        $_POST['password'] = 'wrongpassword';
        $_POST['_token'] = 'test_token';

        // Mock session
        Session::set('_csrf_token', 'test_token');

        // Test login logic
        $user = $this->db->fetch(
            'SELECT * FROM users WHERE username = :username',
            ['username' => 'testuser']
        );

        $this->assertIsArray($user);
        $this->assertFalse(password_verify('wrongpassword', $user['password']));
    }

    public function testNonExistentUser(): void
    {
        // Simulate login with non-existent user
        $_POST['username'] = 'nonexistent';
        $_POST['password'] = 'password123';
        $_POST['_token'] = 'test_token';

        // Test login logic
        $user = $this->db->fetch(
            'SELECT * FROM users WHERE username = :username',
            ['username' => 'nonexistent']
        );

        $this->assertFalse($user);
    }

    public function testCSRFProtection(): void
    {
        // Simulate login without CSRF token
        $_POST['username'] = 'testuser';
        $_POST['password'] = 'password123';

        // Test CSRF validation
        $isValidCSRF = Session::validateCSRF('invalid_token');

        $this->assertFalse($isValidCSRF);
    }
}

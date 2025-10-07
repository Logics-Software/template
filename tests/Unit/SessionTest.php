<?php
/**
 * Session Unit Tests
 */
class SessionTest extends PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        // Start session for testing
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function tearDown(): void
    {
        // Clean up session data
        $_SESSION = [];
    }

    public function testSetAndGet(): void
    {
        Session::set('test_key', 'test_value');
        
        $this->assertEquals('test_value', Session::get('test_key'));
    }

    public function testHasReturnsBoolean(): void
    {
        Session::set('test_key', 'test_value');
        
        $this->assertTrue(Session::has('test_key'));
        $this->assertFalse(Session::has('non_existent_key'));
    }

    public function testRemove(): void
    {
        Session::set('test_key', 'test_value');
        $this->assertTrue(Session::has('test_key'));
        
        Session::remove('test_key');
        $this->assertFalse(Session::has('test_key'));
    }

    public function testGenerateCSRFReturnsString(): void
    {
        $token = Session::generateCSRF();
        
        $this->assertIsString($token);
        $this->assertEquals(64, strlen($token));
    }

    public function testValidateCSRF(): void
    {
        $token = Session::generateCSRF();
        
        $this->assertTrue(Session::validateCSRF($token));
        $this->assertFalse(Session::validateCSRF('invalid_token'));
    }

    public function testFlashMessage(): void
    {
        Session::flash('success', 'Test message');
        
        $this->assertEquals('Test message', Session::getFlash('success'));
        $this->assertNull(Session::getFlash('success')); // Should be removed after first get
    }
}

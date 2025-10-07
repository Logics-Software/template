<?php
/**
 * Request Unit Tests
 */
class RequestTest extends PHPUnit\Framework\TestCase
{
    public function testMethodReturnsString(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $request = new Request();
        
        $this->assertEquals('GET', $request->method());
    }

    public function testUriReturnsString(): void
    {
        $_SERVER['REQUEST_URI'] = '/test/path';
        $request = new Request();
        
        $this->assertIsString($request->uri());
    }

    public function testIsGetReturnsBoolean(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $request = new Request();
        
        $this->assertTrue($request->isGet());
    }

    public function testIsPostReturnsBoolean(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request();
        
        $this->assertTrue($request->isPost());
    }

    public function testIsAjaxReturnsBoolean(): void
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $request = new Request();
        
        $this->assertTrue($request->isAjax());
    }
}

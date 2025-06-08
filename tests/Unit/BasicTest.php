<?php
/**
 * Basic Working Test
 * 
 * This test demonstrates that PHPUnit is working correctly
 */

use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    public function testPhpUnitIsWorking()
    {
        $this->assertTrue(true, 'PHPUnit is working correctly');
    }
    
    public function testBasicAssertions()
    {
        // Test basic assertions
        $this->assertEquals(2, 1 + 1);
        $this->assertNotEmpty('hello');
        $this->assertIsString('test');
        $this->assertIsArray([1, 2, 3]);
    }
    
    public function testArrayOperations()
    {
        $array = ['name' => 'John', 'age' => 30];
        
        $this->assertArrayHasKey('name', $array);
        $this->assertEquals('John', $array['name']);
        $this->assertCount(2, $array);
    }
    
    public function testStringOperations()
    {
        $text = 'Hello, World!';
        
        $this->assertStringContainsString('Hello', $text);
        $this->assertStringStartsWith('Hello', $text);
        $this->assertStringEndsWith('World!', $text);
    }
}

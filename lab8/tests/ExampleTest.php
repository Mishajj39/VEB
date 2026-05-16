<?php
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testTrueIsTrue()
    {
        $this->assertTrue(true);
    }
    
    public function testFalseIsFalse()
    {
        $this->assertFalse(false);
    }
}
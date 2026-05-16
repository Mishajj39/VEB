<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../www/Subscription.php';

class SubscriptionTest extends TestCase
{
    private $subscription;
    
    protected function setUp(): void
    {
        $this->subscription = new Subscription(null);
    }
    
    public function testAddSubscription()
    {
        $result = $this->subscription->add("Иван Петров", "6 месяцев", 
                                           "National Geographic", 1, "Банковская карта");
        
        $this->assertEquals("Student Иван Петров added", $result);
    }
    
    public function testGetAllReturnsArray()
    {
        $result = $this->subscription->getAll();
        
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }
    
    public function testValidateDataValid()
    {
        $result = $this->subscription->validateSubscriptionData("Иван", "Карта");
        $this->assertTrue($result);
    }
    
    public function testValidateDataThrowsExceptionOnEmptyName()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->subscription->validateSubscriptionData("", "Карта");
    }
}
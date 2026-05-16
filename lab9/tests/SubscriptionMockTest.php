<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../code/Subscription.php';

class SubscriptionMockTest extends TestCase
{
    public function testAddWithMock()
    {
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
        
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);
        
        $stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $subscription = new Subscription($pdoMock);
        
        $result = $subscription->add("Test", "3 months", "Forbes", 0, "Card");
        
        $this->assertTrue($result);
    }
}
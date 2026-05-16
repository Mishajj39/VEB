<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../www/Subscription.php';

class IntegrationTest extends TestCase
{
    private $pdo;
    private $subscription;
    
    protected function setUp(): void
    {
        try {
            $this->pdo = new PDO(
                "mysql:host=localhost;dbname=lab5_db;port=3307",
                "lab5_user",
                "lab5_pass"
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->subscription = new Subscription($this->pdo);
        } catch (PDOException $e) {
            $this->markTestSkipped("БД не доступна: " . $e->getMessage());
        }
    }
    
    public function testRealDatabaseInsert()
    {
        // Очистка тестовых данных
        $this->pdo->exec("DELETE FROM subscriptions WHERE name = 'Integration Test'");
        
        $result = $this->subscription->add(
            "Integration Test", "12 months", "Test Magazine", 1, "Card"
        );
        
        $this->assertTrue($result);
        
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM subscriptions WHERE name = 'Integration Test'");
        $count = $stmt->fetch()['count'];
        
        $this->assertEquals(1, $count);
    }
    
    public function testGetCount()
    {
        $count = $this->subscription->getCount();
        $this->assertIsInt($count);
    }
}
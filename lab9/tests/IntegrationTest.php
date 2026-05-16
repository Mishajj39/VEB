<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../code/Subscription.php';

class IntegrationTest extends TestCase
{
    private $pdo;
    private $subscription;
    
    protected function setUp(): void
    {
        try {
            $this->pdo = new PDO(
                "mysql:host=db;dbname=lab9_db;port=3306",
                "lab9_user",
                "lab9_pass"
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS subscriptions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    subscription_term VARCHAR(50) NOT NULL,
                    magazine VARCHAR(100) NOT NULL,
                    digital_version TINYINT(1) DEFAULT 0,
                    payment_format VARCHAR(50) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            
            $this->pdo->exec("TRUNCATE TABLE subscriptions");
            $this->subscription = new Subscription($this->pdo);
        } catch (PDOException $e) {
            $this->markTestSkipped("БД не доступна: " . $e->getMessage());
        }
    }
    
    public function testRealDatabaseInsert()
    {
        $result = $this->subscription->add(
            "Integration Test", "12 months", "Test Magazine", 1, "Card"
        );
        
        $this->assertTrue($result);
        
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM subscriptions");
        $count = $stmt->fetch()['count'];
        
        $this->assertEquals(1, $count);
    }
    
    public function testGetCount()
    {
        $this->subscription->add("User1", "3 months", "Mag1", 0, "Card");
        $this->subscription->add("User2", "6 months", "Mag2", 1, "Cash");
        
        $count = $this->subscription->getCount();
        
        $this->assertEquals(2, $count);
    }
}
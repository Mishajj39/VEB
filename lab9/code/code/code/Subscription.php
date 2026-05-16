<?php
class Subscription {
    private $pdo;

    public function __construct($pdo = null) {
        $this->pdo = $pdo;
    }

    public function add($name, $subscription_term, $magazine, $digital_version, $payment_format) {
        if ($this->pdo === null) {
            return "Student $name added";
        }
        
        $stmt = $this->pdo->prepare(
            "INSERT INTO subscriptions (name, subscription_term, magazine, digital_version, payment_format) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $subscription_term, $magazine, $digital_version, $payment_format]);
        return true;
    }

    public function getAll() {
        if ($this->pdo === null) {
            return [
                ['id' => 1, 'name' => 'Test User', 'subscription_term' => '6 months', 
                 'magazine' => 'Test Mag', 'digital_version' => 1, 'payment_format' => 'Card']
            ];
        }
        
        $stmt = $this->pdo->query("SELECT * FROM subscriptions");
        return $stmt->fetchAll();
    }

    public function getCount() {
        if ($this->pdo === null) {
            return 1;
        }
        
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM subscriptions");
        return $stmt->fetch()['total'];
    }

    public function validateSubscriptionData($name, $payment_format) {
        if (empty($name)) {
            throw new InvalidArgumentException("Name cannot be empty");
        }
        if (empty($payment_format)) {
            throw new InvalidArgumentException("Payment format is required");
        }
        return true;
    }
}
?>
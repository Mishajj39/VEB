<?php
class Subscription {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function add($name, $subscription_term, $magazine, $digital_version, $payment_format) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO subscriptions (name, subscription_term, magazine, digital_version, payment_format) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $subscription_term, $magazine, $digital_version, $payment_format]);
    }

    public function getAll($sort = 'created_at DESC') {
        $allowedSort = ['created_at DESC', 'created_at ASC', 'name ASC', 'name DESC'];
        if (!in_array($sort, $allowedSort)) {
            $sort = 'created_at DESC';
        }
        $stmt = $this->pdo->query("SELECT * FROM subscriptions ORDER BY $sort");
        return $stmt->fetchAll();
    }

    public function getCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM subscriptions");
        return $stmt->fetch()['total'];
    }

    public function getFiltered($minAge = null) {
        if ($minAge) {
            $stmt = $this->pdo->prepare("SELECT * FROM subscriptions WHERE ? < YEAR(CURDATE()) - YEAR(created_at)");
            $stmt->execute([$minAge]);
            return $stmt->fetchAll();
        }
        return $this->getAll();
    }

    public function update($id, $name) {
        $stmt = $this->pdo->prepare("UPDATE subscriptions SET name=? WHERE id=?");
        $stmt->execute([$name, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM subscriptions WHERE id=?");
        $stmt->execute([$id]);
    }
}
?>
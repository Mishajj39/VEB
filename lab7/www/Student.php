<?php
require_once 'db.php';

class Student {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function register($name, $email) {
        try {
            $stmt = $this->db->prepare("INSERT INTO students (name, email) VALUES (?, ?)");
            $stmt->execute([$name, $email]);
            return ['success' => true, 'id' => $this->db->lastInsertId()];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM students ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
}
?>
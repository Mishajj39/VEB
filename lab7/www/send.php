<?php
require 'QueueManager.php';
require 'Student.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($name) || empty($email)) {
        echo json_encode(['success' => false, 'error' => 'Name and email are required']);
        exit;
    }
    
    $queue = new QueueManager();
    
    $result = $queue->publish([
        'type' => 'register_student',
        'name' => $name,
        'email' => $email,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => '✅ Student registration queued for processing!']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to queue message']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Only POST method allowed']);
}
?>
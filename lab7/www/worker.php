#!/usr/bin/env php
<?php
require 'QueueManager.php';
require 'Student.php';

echo "Worker started (File-based Queue)...\n";
echo str_repeat("=", 60) . "\n";

$queue = new QueueManager();
$studentModel = new Student();

$queue->consume(function($data) use ($queue, $studentModel) {
    echo "\nReceived: " . json_encode($data) . "\n";
    
    try {
        echo "Processing...\n";
        sleep(1);
        
        if ($data['type'] === 'register_student') {
            $result = $studentModel->register($data['name'], $data['email']);
            
            if ($result['success']) {
                echo "✅ Student {$data['name']} registered (ID: {$result['id']})\n";
                
                $logData = [
                    'status' => 'success',
                    'data' => $data,
                    'processed_at' => date('Y-m-d H:i:s')
                ];
                file_put_contents(__DIR__ . '/processed.log', json_encode($logData) . PHP_EOL, FILE_APPEND);
            } else {
                throw new Exception("Database error: " . $result['error']);
            }
        } else {
            throw new Exception("Unknown message type");
        }
        
        echo "✅ Done!\n";
        
    } catch (Exception $e) {
        echo "❌ ERROR: " . $e->getMessage() . "\n";
        $queue->publishToError($data, $e->getMessage());
    }
    
    echo str_repeat("-", 60) . "\n";
});
?>
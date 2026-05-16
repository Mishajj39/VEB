<?php

class QueueManager {
    private $queueDir = '/var/www/html/queue_data/';
    private $mainQueue = 'main_queue.json';
    private $errorQueue = 'error_queue.json';
    
    public function __construct() {
        if (!file_exists($this->queueDir)) {
            mkdir($this->queueDir, 0777, true);
        }
    }
    
    public function publish($data) {
        $queue = $this->loadQueue($this->mainQueue);
        $queue[] = [
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s'),
            'id' => uniqid()
        ];
        return $this->saveQueue($this->mainQueue, $queue);
    }
    
    public function publishToError($data, $errorMessage) {
        $errorQueue = $this->loadQueue($this->errorQueue);
        $errorQueue[] = [
            'original_data' => $data,
            'error' => $errorMessage,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        return $this->saveQueue($this->errorQueue, $errorQueue);
    }
    
    public function consume($callback) {
        echo "Worker started. Waiting for messages...\n";
        
        while (true) {
            $queue = $this->loadQueue($this->mainQueue);
            
            if (!empty($queue)) {
                $message = array_shift($queue);
                $this->saveQueue($this->mainQueue, $queue);
                
                if (isset($message['data'])) {
                    $callback($message['data']);
                }
            }
            
            sleep(2);
        }
    }
    
    public function getStats() {
        $mainQueue = $this->loadQueue($this->mainQueue);
        $errorQueue = $this->loadQueue($this->errorQueue);
        
        return [
            'main_queue_size' => count($mainQueue),
            'error_queue_size' => count($errorQueue)
        ];
    }
    
    private function loadQueue($filename) {
        $filepath = $this->queueDir . $filename;
        if (file_exists($filepath)) {
            $content = file_get_contents($filepath);
            return json_decode($content, true) ?: [];
        }
        return [];
    }
    
    private function saveQueue($filename, $data) {
        $filepath = $this->queueDir . $filename;
        return file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT));
    }
}
?>
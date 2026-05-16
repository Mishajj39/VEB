<?php
require_once 'db.php';

try {
    $db = Database::getInstance();
    $db->initTables();
    echo "Tables created successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
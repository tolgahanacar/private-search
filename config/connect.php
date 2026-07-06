<?php
declare(strict_types=1);

try {
    // Database name (can be changed to match your database name, e.g., 'ara' or 'search')
    $dbName = 'ara';
    
    $db = new PDO("mysql:host=localhost;dbname={$dbName};charset=utf8mb4", "root", "");
    
    // Set PDO options for error mode and default fetch mode
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (PDOException $e) {
    // Safe output of database errors to prevent system path leaks
    exit("<b>Database Connection Error:</b> " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

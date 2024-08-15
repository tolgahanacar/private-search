<?php
try {
    $db = new PDO("mysql:host=localhost;dbname=ara;charset=UTF8", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("<b>Database Connection Error:</b> " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

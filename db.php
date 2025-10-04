<?php
$host = 'localhost';
$dbname = 'dbzdbdkzqeu8d7';
$username = 'unj7fz18q5g2c';
$password = 'sgihxhp0wgxh';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?> 

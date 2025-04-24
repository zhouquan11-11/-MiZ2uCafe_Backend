<?php
$host = 'localhost';
$db   = 'miz2u_cafe';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

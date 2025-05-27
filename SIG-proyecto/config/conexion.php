<?php
$host = 'localhost';
$dbname = 'cruba_biblioteca';
$username = 'root';
$password = 'informatica';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // No mostrar el error directamente en producción
    die("Error de conexión: " . $e->getMessage());
}
?>
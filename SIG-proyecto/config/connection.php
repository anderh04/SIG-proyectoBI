<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'informatica');
define('DB_NAME', 'cruba_biblioteca');

// Conexión a la base de datos
try {
    $conexion = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec("SET NAMES 'utf8'");
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Función para ejecutar consultas
function ejecutarConsulta($sql, $params = []) {
    global $conexion;
    try {
        $stmt = $conexion->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        die("Error en la consulta: " . $e->getMessage());
    }
}
?>
<?php
session_start();
require_once 'config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_estudiante = $conn->real_escape_string($_POST['codigo_estudiante']);
    
    $sql = "SELECT e.*, c.nombre as carrera_nombre 
            FROM estudiantes e
            JOIN carreras c ON e.id_carrera = c.id_carrera
            WHERE e.codigo_estudiante = '$codigo_estudiante'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['id_estudiante'] = $row['id_estudiante'];
        $_SESSION['codigo_estudiante'] = $row['codigo_estudiante'];
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['apellido'] = $row['apellido'];
        $_SESSION['carrera'] = $row['carrera_nombre'];
        
        header("Location: index.php");
        exit();
    } else {
        echo "Estudiante no encontrado. <a href='index.php'>Volver</a>";
    }
}

$conn->close();
?>
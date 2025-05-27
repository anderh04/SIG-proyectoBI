<?php
session_start();
require_once 'config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $codigo_estudiante = $conn->real_escape_string($_POST['codigo_estudiante']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $genero = $conn->real_escape_string($_POST['genero']);
    $id_carrera = intval($_POST['id_carrera']);
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;
    $telefono = isset($_POST['telefono']) ? $conn->real_escape_string($_POST['telefono']) : null;

    // Verificar si el estudiante ya existe
    $check_sql = "SELECT id_estudiante FROM estudiantes WHERE codigo_estudiante = '$codigo_estudiante'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        // Estudiante ya existe, iniciar sesión
        $row = $check_result->fetch_assoc();
        $_SESSION['id_estudiante'] = $row['id_estudiante'];
        $_SESSION['codigo_estudiante'] = $codigo_estudiante;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido'] = $apellido;
        
        // Obtener nombre de la carrera
        $carrera_sql = "SELECT nombre FROM carreras WHERE id_carrera = $id_carrera";
        $carrera_result = $conn->query($carrera_sql);
        $_SESSION['carrera'] = $carrera_result->fetch_assoc()['nombre'];
        
        header("Location: index.php");
        exit();
    } else {
        // Insertar nuevo estudiante
        $insert_sql = "INSERT INTO estudiantes (codigo_estudiante, nombre, apellido, genero, id_carrera, email, telefono)
                      VALUES ('$codigo_estudiante', '$nombre', '$apellido', '$genero', $id_carrera, " .
                      ($email ? "'$email'" : "NULL") . ", " . ($telefono ? "'$telefono'" : "NULL") . ")";
        
        if ($conn->query($insert_sql) === TRUE) {
            $_SESSION['id_estudiante'] = $conn->insert_id;
            $_SESSION['codigo_estudiante'] = $codigo_estudiante;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['apellido'] = $apellido;
            
            // Obtener nombre de la carrera
            $carrera_sql = "SELECT nombre FROM carreras WHERE id_carrera = $id_carrera";
            $carrera_result = $conn->query($carrera_sql);
            $_SESSION['carrera'] = $carrera_result->fetch_assoc()['nombre'];
            
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
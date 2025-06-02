<?php
session_start();
require_once 'config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Cedula = $_POST['Cedula'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $id_carrera = $_POST['id_carrera'] ?? '';
    $email = $_POST['email'] ?? null;
    $telefono = $_POST['telefono'] ?? null;

    if (empty($Cedula) || empty($nombre) || empty($apellido) || empty($genero) || empty($id_carrera)) {
        // Manejar campos requeridos faltantes
        header('Location: index.php?error=missing_fields');
        exit();
    }

    try {
        // Verificar si el estudiante ya existe por cédula
        $stmt = $conn->prepare("SELECT id_estudiante FROM estudiantes WHERE Cedula = ?");
        $stmt->execute([$Cedula]);
        if ($stmt->fetch()) {
            header('Location: index.php?error=student_exists');
            exit();
        }

        // Insertar nuevo estudiante
        $stmt = $conn->prepare("INSERT INTO estudiantes (Cedula, nombre, apellido, genero, id_carrera, email, telefono) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$Cedula, $nombre, $apellido, $genero, $id_carrera, $email, $telefono]);

        // Iniciar sesión automáticamente después del registro
        $id_estudiante = $conn->lastInsertId();
        $_SESSION['id_estudiante'] = $id_estudiante;
        $_SESSION['Cedula'] = $Cedula;
        $_SESSION['nombre'] = $nombre;

        // Obtener nombre de la carrera para mostrar en sesión
        $stmt_carrera = $conn->prepare("SELECT nombre_carrera FROM carreras WHERE id_carrera = ?");
        $stmt_carrera->execute([$id_carrera]);
        $carrera_data = $stmt_carrera->fetch(PDO::FETCH_ASSOC);
        $_SESSION['carrera'] = $carrera_data['nombre_carrera'] ?? 'Desconocida';

        header('Location: index.php?success=registration_successful');
        exit();

    } catch (PDOException $e) {
        // Manejar errores de base de datos
        error_log("Error de registro: " . $e->getMessage());
        header('Location: index.php?error=db_error');
        exit();
    }
} else {
    header('Location: index.php'); // Redirigir si no es una solicitud POST
    exit();
}
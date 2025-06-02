<?php
session_start();
require_once 'config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_estudiante = $_POST['codigo_estudiante'] ?? '';

    if (empty($codigo_estudiante)) {
        header('Location: index.php?error=empty_login_code');
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT e.id_estudiante, e.nombre, e.codigo_estudiante, c.nombre_carrera AS carrera_nombre
                                FROM estudiantes e
                                JOIN carreras c ON e.id_carrera = c.id_carrera
                                WHERE e.codigo_estudiante = ?");
        $stmt->execute([$codigo_estudiante]);
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($estudiante) {
            $_SESSION['id_estudiante'] = $estudiante['id_estudiante'];
            $_SESSION['codigo_estudiante'] = $estudiante['codigo_estudiante'];
            $_SESSION['nombre'] = $estudiante['nombre'];
            $_SESSION['carrera'] = $estudiante['carrera_nombre'];
            header('Location: index.php?success=login_successful');
            exit();
        } else {
            header('Location: index.php?error=invalid_login');
            exit();
        }
    } catch (PDOException $e) {
        error_log("Error de login: " . $e->getMessage());
        header('Location: index.php?error=db_error');
        exit();
    }
} else {
    header('Location: index.php'); // Redirect if not a POST request
    exit();
}
?>
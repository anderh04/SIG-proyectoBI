<?php
require_once 'config/connection.php';
session_start();

// Validar CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['errores'] = ['Token CSRF inválido'];
    header('Location: registro_estudiantes.php');
    exit();
}

// Validar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['errores'] = ['Método no permitido'];
    header('Location: registro_estudiantes.php');
    exit();
}

// Validar acción
if (!isset($_POST['accion']) || $_POST['accion'] !== 'agregar') {
    $_SESSION['errores'] = ['Acción no válida'];
    header('Location: registro_estudiantes.php');
    exit();
}

// Limpiar y validar datos
$errores = [];
$codigo = trim($_POST['codigo'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$genero = trim($_POST['genero'] ?? '');
$email = trim($_POST['email'] ?? '');
$carrera = trim($_POST['carrera'] ?? '');
$hora_entrada = trim($_POST['hora_entrada'] ?? '');
$hora_salida = trim($_POST['hora_salida'] ?? '');

// Validaciones
if (empty($nombre)) $errores[] = 'El nombre es obligatorio';
if (empty($apellido)) $errores[] = 'El apellido es obligatorio';
if (empty($genero)) $errores[] = 'El género es obligatorio';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El email no es válido';
}
if (empty($carrera)) $errores[] = 'La carrera es obligatoria';

// Validar código único si se proporciona
if (!empty($codigo)) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM estudiantes WHERE codigo_estudiante = ?");
        $stmt->execute([$codigo]);
        if ($stmt->fetchColumn() > 0) {
            $errores[] = 'El código de estudiante ya existe';
        }
    } catch (PDOException $e) {
        $errores[] = 'Error al verificar el código de estudiante';
    }
}

// Si hay errores, redirigir
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['datos_formulario'] = $_POST;
    header('Location: registro_estudiantes.php');
    exit();
}

// Insertar en la base de datos
try {
    $stmt = $pdo->prepare("
        INSERT INTO estudiantes (
            codigo_estudiante, nombre, apellido, genero, email, 
            id_carrera, hora_entrada, hora_salida, fecha_registro
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $codigo ?: null,
        $nombre,
        $apellido,
        $genero,
        $email,
        $carrera,
        $hora_entrada ?: null,
        $hora_salida ?: null
    ]);
    
    $_SESSION['exito'] = 'Estudiante registrado correctamente';
    header('Location: registro_estudiantes.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['errores'] = ['Error al registrar el estudiante: ' . $e->getMessage()];
    $_SESSION['datos_formulario'] = $_POST;
    header('Location: registro_estudiantes.php');
    exit();
}
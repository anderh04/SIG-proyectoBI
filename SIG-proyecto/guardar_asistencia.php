<?php
session_start();
require_once 'config/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_estudiante'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_estudiante = intval($_SESSION['id_estudiante']);
    $tipo_asistencia = $conn->real_escape_string($_POST['tipo_asistencia']);
    $accion = $conn->real_escape_string($_POST['accion']);
    $observaciones = isset($_POST['observaciones']) ? $conn->real_escape_string($_POST['observaciones']) : null;
    $equipo = isset($_POST['equipo']) ? $conn->real_escape_string($_POST['equipo']) : null;
    
    $now = date('Y-m-d H:i:s');
    
    if ($accion == 'entrada') {
        // Registrar entrada
        $sql = "INSERT INTO registro_asistencia (id_estudiante, fecha_entrada, tipo, observaciones, equipo)
                VALUES ($id_estudiante, '$now', '$tipo_asistencia', " .
                ($observaciones ? "'$observaciones'" : "NULL") . ", " .
                ($equipo ? "'$equipo'" : "NULL") . ")";
        
        if ($conn->query($sql)) {
            echo json_encode(['success' => true, 'message' => 'Entrada registrada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar entrada: ' . $conn->error]);
        }
    } else {
        // Registrar salida - actualizar registro más reciente sin salida
        $sql = "UPDATE registro_asistencia 
                SET fecha_salida = '$now' 
                WHERE id_estudiante = $id_estudiante 
                AND tipo = '$tipo_asistencia' 
                AND fecha_salida IS NULL
                ORDER BY fecha_entrada DESC LIMIT 1";
        
        if ($conn->query($sql)) {
            if ($conn->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Salida registrada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se encontró registro de entrada para actualizar']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar salida: ' . $conn->error]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

$conn->close();
?>
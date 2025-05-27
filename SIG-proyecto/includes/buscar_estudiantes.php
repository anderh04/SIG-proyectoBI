<?php
require_once 'config/connection.php';

header('Content-Type: application/json');

$codigo = $_POST['codigo'] ?? '';
$cedula = $_POST['cedula'] ?? '';

if (!$codigo && !$cedula) {
    echo json_encode(['success' => false, 'message' => 'No se proporcionó código ni cédula']);
    exit;
}

$sql = "SELECT * FROM estudiantes WHERE codigo_estudiante = ? OR cedula = ? LIMIT 1";
$stmt = ejecutarConsulta($sql, [$codigo, $cedula]);
$estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

if ($estudiante) {
    echo json_encode(['success' => true, 'data' => $estudiante]);
} else {
    echo json_encode(['success' => false, 'message' => 'Estudiante no encontrado']);
}

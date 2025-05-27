<?php
require_once 'config/connection.php';

header('Content-Type: application/json');

$codigo = $_POST['codigo'] ?? '';
$cedula = $_POST['cedula'] ?? '';

$response = [
    'success' => false,
    'data' => null,
    'message' => ''
];

try {
    $query = "SELECT e.*, c.nombre_carrera 
              FROM estudiantes e
              LEFT JOIN carreras c ON e.id_carrera = c.id_carrera
              WHERE e.codigo_estudiante = :codigo OR e.cedula = :cedula";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':codigo' => $codigo,
        ':cedula' => $cedula
    ]);
    
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($data) {
        $response['success'] = true;
        $response['data'] = $data;
    } else {
        $response['message'] = 'Estudiante no encontrado';
    }
} catch (PDOException $e) {
    $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
}

echo json_encode($response);
?>
<?php
session_start();
require_once 'config/conexion.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['id_estudiante'])) {
    $response['message'] = 'Usuario no autenticado.';
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_estudiante = $_SESSION['id_estudiante'];
    $tipo_asistencia = $_POST['tipo_asistencia'] ?? '';
    $accion = $_POST['accion'] ?? ''; // 'entrada'/'salida' or 'inicio'/'fin'
    $observaciones = $_POST['observaciones'] ?? null;
    $equipo = $_POST['equipo'] ?? null; // Only for 'computadoras'

    try {
        if ($tipo_asistencia === 'biblioteca') {
            if ($accion === 'entrada') {
                $stmt = $conn->prepare("INSERT INTO asistencia_biblioteca (id_estudiante, hora_entrada, observaciones) VALUES (?, NOW(), ?)");
                $stmt->execute([$id_estudiante, $observaciones]);
                $response['success'] = true;
                $response['message'] = 'Asistencia a biblioteca registrada con éxito (Entrada).';
            } elseif ($accion === 'salida') {
                // Find the most recent unclosed entry for this student
                $stmt_check = $conn->prepare("SELECT id_asistencia_biblioteca FROM asistencia_biblioteca WHERE id_estudiante = ? AND hora_salida IS NULL ORDER BY hora_entrada DESC LIMIT 1");
                $stmt_check->execute([$id_estudiante]);
                $last_entry = $stmt_check->fetch(PDO::FETCH_ASSOC);

                if ($last_entry) {
                    $stmt = $conn->prepare("UPDATE asistencia_biblioteca SET hora_salida = NOW(), observaciones_salida = ? WHERE id_asistencia_biblioteca = ?");
                    $stmt->execute([$observaciones, $last_entry['id_asistencia_biblioteca']]);
                    $response['success'] = true;
                    $response['message'] = 'Salida de biblioteca registrada con éxito.';
                } else {
                    $response['message'] = 'No se encontró una entrada abierta para este estudiante en la biblioteca.';
                }
            } else {
                $response['message'] = 'Acción de biblioteca no válida.';
            }
        } elseif ($tipo_asistencia === 'computadoras') {
            if (empty($equipo)) {
                $response['message'] = 'Número de equipo es requerido para el uso de computadoras.';
                echo json_encode($response);
                exit();
            }

            if ($accion === 'entrada') {
                $stmt = $conn->prepare("INSERT INTO uso_computadoras (id_estudiante, numero_equipo, hora_inicio, observaciones) VALUES (?, ?, NOW(), ?)");
                $stmt->execute([$id_estudiante, $equipo, $observaciones]);
                $response['success'] = true;
                $response['message'] = 'Registro de uso de computadora exitoso (Inicio).';
            } elseif ($accion === 'salida') {
                // Find the most recent unclosed entry for this student and equipment
                $stmt_check = $conn->prepare("SELECT id_uso_computadora FROM uso_computadoras WHERE id_estudiante = ? AND numero_equipo = ? AND hora_fin IS NULL ORDER BY hora_inicio DESC LIMIT 1");
                $stmt_check->execute([$id_estudiante, $equipo]);
                $last_entry = $stmt_check->fetch(PDO::FETCH_ASSOC);

                if ($last_entry) {
                    $stmt = $conn->prepare("UPDATE uso_computadoras SET hora_fin = NOW(), observaciones_fin = ? WHERE id_uso_computadora = ?");
                    $stmt->execute([$observaciones, $last_entry['id_uso_computadora']]);
                    $response['success'] = true;
                    $response['message'] = 'Fin de uso de computadora registrado con éxito.';
                } else {
                    $response['message'] = 'No se encontró un inicio de uso abierto para este estudiante y equipo.';
                }
            } else {
                $response['message'] = 'Acción de uso de computadoras no válida.';
            }
        } else {
            $response['message'] = 'Tipo de asistencia no válido.';
        }
    } catch (PDOException $e) {
        error_log("Error al registrar asistencia: " . $e->getMessage());
        $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método de solicitud no permitido.';
}

echo json_encode($response);
?>
logout.php (Backend Logic - Not Provided):

Problem: The logout link points to logout.php, which needs to destroy the session.
Solution: Create this file:
PHP

<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header('Location: index.php'); // Redirect to the homepage
exit();
?>
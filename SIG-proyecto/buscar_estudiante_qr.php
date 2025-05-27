<?php
require_once __DIR__ . '/../config/connection.php';

// Verificar conexión
if (!isset($conn)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Error de conexión a BD']);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
    try {
        $codigo = $_POST['codigo'];
        
        // Consulta usando PDO
        $sql = "SELECT e.*, c.nombre as carrera 
               FROM estudiantes e 
               JOIN carreras c ON e.id_carrera = c.id_carrera
               WHERE e.codigo_estudiante = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$codigo]);
        $estudiante = $stmt->fetch();
        
        // 2. Si no existe, creamos un nuevo registro
        if (!$estudiante) {
            // Asumimos que el código QR contiene datos estructurados
            $datos = parseDatosQR($codigo);
            
            // Buscamos la carrera por nombre
            $carrera_id = obtenerIdCarrera($conn, $datos['facultad'] ?? '');
            
            // Insertamos el nuevo estudiante
            $sqlInsert = "INSERT INTO estudiantes 
                         (codigo_estudiante, nombre, apellido, genero, id_carrera, email, telefono)
                         VALUES 
                         (:codigo_estudiante, :nombre, :apellido, :genero, :carrera, :email, :telefono)";
            
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bindParam(':codigo_estudiante', $datos['codigo'] ?? $codigo);
            $stmtInsert->bindParam(':nombre', $datos['nombre'] ?? '');
            $stmtInsert->bindParam(':apellido', $datos['apellido'] ?? '');
            $stmtInsert->bindParam(':genero', $datos['genero'] ?? 'Otro');
            $stmtInsert->bindParam(':carrera', $carrera_id);
            $stmtInsert->bindParam(':email', $datos['email'] ?? '');
            $stmtInsert->bindParam(':telefono', $datos['telefono'] ?? '');
            
            $stmtInsert->execute();
            
            // Obtenemos el ID del nuevo estudiante
            $nuevoId = $conn->lastInsertId();
            
            // Volvemos a buscar para obtener todos los datos
            $stmt->execute(); // Reutilizamos la consulta inicial
            $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'estudiante' => $estudiante,
                'nuevo_registro' => true,
                'message' => 'Estudiante registrado exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'estudiante' => $estudiante,
                'nuevo_registro' => false
            ]);
        }
    } catch(PDOException $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
            // 'trace' => $e->getTraceAsString() // Descomentar solo para desarrollo
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Solicitud inválida'
    ]);
}

/**
 * Parsea los datos del código QR en un array asociativo
 */
function parseDatosQR($qrData) {
    $resultado = [];
    
    // Opción 1: Formato clave:valor separado por |
    if (strpos($qrData, '|') !== false) {
        $pares = explode('|', $qrData);
        foreach ($pares as $par) {
            if (strpos($par, ':') !== false) {
                list($clave, $valor) = explode(':', $par, 2);
                $resultado[trim($clave)] = trim($valor);
            }
        }
    } 
    // Opción 2: Formato JSON
    elseif ($json = json_decode($qrData, true)) {
        $resultado = $json;
    }
    // Opción 3: Solo el código (valor por defecto)
    else {
        $resultado['codigo'] = $qrData;
    }
    
    return $resultado;
}

/**
 * Obtiene el ID de la carrera por nombre, o crea una nueva si no existe
 */
function obtenerIdCarrera($conn, $nombreCarrera) {
    if (empty($nombreCarrera)) return 1; // Valor por defecto
    
    // Buscamos la carrera
    $stmt = $conn->prepare("SELECT id_carrera FROM carreras WHERE nombre = :nombre");
    $stmt->bindParam(':nombre', $nombreCarrera);
    $stmt->execute();
    
    $carrera = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($carrera) {
        return $carrera['id_carrera'];
    } else {
        // Si no existe, la creamos
        $stmtInsert = $conn->prepare("INSERT INTO carreras (nombre) VALUES (:nombre)");
        $stmtInsert->bindParam(':nombre', $nombreCarrera);
        $stmtInsert->execute();
        
        return $conn->lastInsertId();
    }
}
?>
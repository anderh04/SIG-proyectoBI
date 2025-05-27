"# SIG-proyecto"

1. TÍTULO
   "Sistema de Información Gerencial para la Gestión de la Biblioteca del Centro Regional Universitario de Barú (CRUBA)"

2. JUSTIFICACIÓN
   Desarrollar e implementar un Sistema de Información Gerencial que optimice la gestión de los recursos bibliográficos y servicios de la biblioteca del CRUBA, mejorando la eficiencia en los procesos administrativos y la experiencia de los usuarios mediante un sistema intuitivo y facil de usar dentro de la biblioteca

3.1 Objetivo General
establecer un sistema de forma robusta e dinamica con el que contantemente se actulize, ofreciendo y producto de innvodora y de alta calidad

case 'editar':
$id_estudiante = (int)$\_POST['id_estudiante'];
$codigo = trim($\_POST['codigo']);
$nombre = trim($\_POST['nombre']);
$apellido = trim($\_POST['apellido']);
$genero = trim($\_POST['genero']);
$carrera = (int)$\_POST['carrera'];
$email = trim($\_POST['email'] ?? '');

            if (empty($codigo) || empty($nombre) || empty($apellido) || empty($genero) || empty($carrera)) {
                throw new Exception('Todos los campos obligatorios deben ser completados');
            }

            // Verificar si el código ya existe (excluyendo al estudiante actual)
            $stmt = ejecutarConsulta("SELECT COUNT(*) as total FROM estudiantes WHERE codigo_estudiante = ? AND id_estudiante != ?", [$codigo, $id_estudiante]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] > 0) {
                throw new Exception('El código de estudiante ya está registrado para otro estudiante');
            }

            // Actualizar estudiante
            $sql = "UPDATE estudiantes
                    SET codigo_estudiante = ?, nombre = ?, apellido = ?, genero = ?, id_carrera = ?, email = ?
                    WHERE id_estudiante = ?";
            $params = [$codigo, $nombre, $apellido, $genero, $carrera, $email, $id_estudiante];

            ejecutarConsulta($sql, $params);

            $response['success'] = true;
            $response['message'] = 'Estudiante actualizado correctamente';
            break;

        case 'eliminar':
            $id_estudiante = (int)$_POST['id_estudiante'];

            // Verificar si el estudiante tiene préstamos activos
            $sql = "SELECT COUNT(*) as total FROM prestamos
                    WHERE id_estudiante = ? AND (estado = 'Pendiente' OR estado = 'Atrasado')";
            $stmt = ejecutarConsulta($sql, [$id_estudiante]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] > 0) {
                throw new Exception('No se puede eliminar el estudiante porque tiene préstamos activos');
            }

            // Eliminar estudiante
            ejecutarConsulta("DELETE FROM estudiantes WHERE id_estudiante = ?", [$id_estudiante]);

            $response['success'] = true;
            $response['message'] = 'Estudiante eliminado correctamente';
            break;

        default:
            throw new Exception('Acción no válida');
    }

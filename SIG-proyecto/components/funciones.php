<?php

require_once 'config/connection.php';

// Función para obtener estadísticas generales
function obtenerEstadisticasGenerales()
{
    $sql = "SELECT 
            (SELECT COUNT(*) FROM libros) as total_libros,
            (SELECT COUNT(*) FROM estudiantes) as total_estudiantes,
            (SELECT COUNT(*) FROM prestamos WHERE estado = 'Pendiente' OR estado = 'Atrasado') as prestamos_activos,
            (SELECT COUNT(*) FROM prestamos WHERE estado = 'Devuelto') as prestamos_devueltos,
            (SELECT COUNT(*) FROM solicitudes_permiso WHERE estado = 'Pendiente') as solicitudes_pendientes";

    $stmt = ejecutarConsulta($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para obtener préstamos recientes
function obtenerPrestamosRecientes($limite = 5)
{
    $sql = "SELECT p.id_prestamo, e.nombre, e.apellido, l.titulo, 
                   DATE_FORMAT(p.fecha_prestamo, '%d/%m/%Y') as fecha_prestamo,
                   DATE_FORMAT(p.fecha_devolucion_estimada, '%d/%m/%Y') as fecha_devolucion,
                   p.estado
            FROM prestamos p
            JOIN estudiantes e ON p.id_estudiante = e.id_estudiante
            JOIN libros l ON p.id_libro = l.id_libro
            ORDER BY p.fecha_prestamo DESC
            LIMIT ?";

    $stmt = ejecutarConsulta($sql, [$limite]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener estadísticas por género
function obtenerEstadisticasGenero()
{
    $sql = "SELECT e.genero, COUNT(DISTINCT p.id_estudiante) as total_estudiantes, 
                   COUNT(p.id_prestamo) as total_prestamos
            FROM prestamos p
            JOIN estudiantes e ON p.id_estudiante = e.id_estudiante
            GROUP BY e.genero";

    $stmt = ejecutarConsulta($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener préstamos por categoría
function obtenerPrestamosPorCategoria($limite = 5)
{
    $sql = "SELECT c.nombre_categoria, COUNT(p.id_prestamo) as total
            FROM prestamos p
            JOIN libros l ON p.id_libro = l.id_libro
            JOIN categorias c ON l.id_categoria = c.id_categoria
            GROUP BY c.id_categoria
            ORDER BY total DESC
            LIMIT ?";

    $stmt = ejecutarConsulta($sql, [$limite]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener préstamos por carrera
function obtenerPrestamosPorCarrera($limite = 1000)
{
    $sql = "SELECT ca.nombre_carrera, COUNT(p.id_prestamo) as total
            FROM prestamos p
            JOIN estudiantes e ON p.id_estudiante = e.id_estudiante
            JOIN carreras ca ON e.id_carrera = ca.id_carrera
            GROUP BY ca.id_carrera
            ORDER BY total DESC
            LIMIT ?";

    $stmt = ejecutarConsulta($sql, [$limite]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener préstamos por turno
function obtenerPrestamosPorTurno()
{
    $sql = "SELECT turno, COUNT(*) as total
            FROM prestamos
            GROUP BY turno
            ORDER BY FIELD(turno, 'Mañana', 'Tarde', 'Noche')";

    $stmt = ejecutarConsulta($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para registrar un nuevo préstamo
function registrarPrestamo($id_estudiante, $id_libro, $turno, $dias_prestamo = 7)
{
    $fecha_prestamo = date('Y-m-d H:i:s');
    $fecha_devolucion = date('Y-m-d H:i:s', strtotime("+$dias_prestamo days"));

    $sql = "INSERT INTO prestamos (id_estudiante, id_libro, fecha_prestamo, fecha_devolucion_estimada, turno, estado)
            VALUES (?, ?, ?, ?, ?, 'Pendiente')";

    ejecutarConsulta($sql, [$id_estudiante, $id_libro, $fecha_prestamo, $fecha_devolucion, $turno]);

    // Marcar libro como no disponible
    ejecutarConsulta("UPDATE libros SET disponible = FALSE WHERE id_libro = ?", [$id_libro]);

    return true;
}

// Función para registrar una devolución
function registrarDevolucion($id_prestamo)
{
    $fecha_devolucion = date('Y-m-d H:i:s');

    // Obtener información del préstamo
    $sql = "SELECT id_libro FROM prestamos WHERE id_prestamo = ?";
    $stmt = ejecutarConsulta($sql, [$id_prestamo]);
    $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($prestamo) {
        // Actualizar préstamo
        $sql = "UPDATE prestamos 
                SET fecha_devolucion_real = ?, estado = 'Devuelto'
                WHERE id_prestamo = ?";
        ejecutarConsulta($sql, [$fecha_devolucion, $id_prestamo]);

        // Marcar libro como disponible
        ejecutarConsulta("UPDATE libros SET disponible = TRUE WHERE id_libro = ?", [$prestamo['id_libro']]);

        return true;
    }

    return false;
}

// Función para obtener libros disponibles
function obtenerLibrosDisponibles() {
    $sql = "SELECT l.id_libro, l.titulo, l.autor, c.nombre_categoria
            FROM libros l
            JOIN categorias c ON l.id_categoria = c.id_categoria
            WHERE l.disponible = TRUE
            ORDER BY l.titulo";
    
    $stmt = ejecutarConsulta($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener estudiantes activos
function obtenerEstudiantesActivos() {
    $sql = "SELECT e.id_estudiante, e.nombre, e.apellido, e.codigo_estudiante, c.nombre_carrera
            FROM estudiantes e
            JOIN carreras c ON e.id_carrera = c.id_carrera
            ORDER BY e.apellido, e.nombre";
    
    $stmt = ejecutarConsulta($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener préstamos activos por estudiante
function obtenerPrestamosActivos($id_estudiante) {
    $sql = "SELECT p.id_prestamo, l.titulo, p.fecha_prestamo, p.fecha_devolucion_estimada, p.estado
            FROM prestamos p
            JOIN libros l ON p.id_libro = l.id_libro
            WHERE p.id_estudiante = ? AND (p.estado = 'Pendiente' OR p.estado = 'Atrasado')
            ORDER BY p.fecha_prestamo DESC";
    
    $stmt = ejecutarConsulta($sql, [$id_estudiante]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener historial de préstamos
function obtenerHistorialPrestamos($id_estudiante = null, $id_libro = null, $limite = 10) {
    $sql = "SELECT p.*, 
                   e.nombre as estudiante_nombre, e.apellido as estudiante_apellido, e.codigo_estudiante,
                   l.titulo as libro_titulo, l.autor as libro_autor
            FROM prestamos p
            JOIN estudiantes e ON p.id_estudiante = e.id_estudiante
            JOIN libros l ON p.id_libro = l.id_libro
            WHERE 1=1";
    
    $params = [];
    
    if ($id_estudiante) {
        $sql .= " AND p.id_estudiante = ?";
        $params[] = $id_estudiante;
    }
    
    if ($id_libro) {
        $sql .= " AND p.id_libro = ?";
        $params[] = $id_libro;
    }
    
    $sql .= " ORDER BY p.fecha_prestamo DESC LIMIT ?";
    $params[] = $limite;
    
    $stmt = ejecutarConsulta($sql, $params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtenerCarreras($conn) {
    $sql = "SELECT id_carrera, nombre FROM carreras";
    $result = mysqli_query($conn, $sql);
    $carreras = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $carreras[] = $row;
        }
    }
    return $carreras;
}

function obtenerEstudiantes($conn) {
    $sql = "SELECT e.id_estudiante, e.codigo_estudiante, e.nombre, e.apellido, e.genero, c.nombre as carrera, e.email, e.telefono
            FROM estudiantes e
            LEFT JOIN carreras c ON e.id_carrera = c.id_carrera";
    $result = mysqli_query($conn, $sql);
    $estudiantes = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $estudiantes[] = $row;
        }
    }
    return $estudiantes;
}

function obtenerAsistencias($conn) {
    $sql = "SELECT a.id_asistencia, e.nombre, e.apellido, e.codigo_estudiante, a.fecha_entrada, a.fecha_salida
            FROM asistencias a
            LEFT JOIN estudiantes e ON a.id_estudiante = e.id_estudiante
            ORDER BY a.fecha_entrada DESC";
    $result = mysqli_query($conn, $sql);
    $asistencias = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $asistencias[] = $row;
        }
    }
    return $asistencias;
}
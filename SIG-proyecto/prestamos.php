<?php
$titulo_pagina = "Gestión de Préstamos";
require_once 'components/header.php';
require_once 'components/funciones.php';

// Paginación
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 10;
$inicio = ($pagina > 1) ? ($pagina * $por_pagina - $por_pagina) : 0;

// Filtros
$filtro_estado = isset($_GET['estado']) ? $_GET['estado'] : 'todos';
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

// Obtener total de préstamos
$sql_total = "SELECT COUNT(*) as total FROM prestamos p
              JOIN estudiantes e ON p.id_estudiante = e.id_estudiante
              JOIN libros l ON p.id_libro = l.id_libro
              WHERE 1=1";

$params = [];

if ($filtro_estado != 'todos') {
    $sql_total .= " AND p.estado = :estado";
    $params[':estado'] = $filtro_estado;
}

if ($busqueda) {
    $sql_total .= " AND (e.nombre LIKE :busqueda OR e.apellido LIKE :busqueda OR l.titulo LIKE :busqueda)";
    $params[':busqueda'] = "%$busqueda%";
}

$stmt_total = ejecutarConsulta($sql_total, $params);
$total_prestamos = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];
$total_paginas = ceil($total_prestamos / $por_pagina);

// Obtener préstamos
$sql = "SELECT p.*, 
               e.nombre as estudiante_nombre, e.apellido as estudiante_apellido, e.codigo_estudiante,
               l.titulo as libro_titulo, l.autor as libro_autor,
               DATEDIFF(p.fecha_devolucion_estimada, CURDATE()) as dias_restantes
        FROM prestamos p
        JOIN estudiantes e ON p.id_estudiante = e.id_estudiante
        JOIN libros l ON p.id_libro = l.id_libro
        WHERE 1=1";

if ($filtro_estado != 'todos') {
    $sql .= " AND p.estado = :estado";
}

if ($busqueda) {
    $sql .= " AND (e.nombre LIKE :busqueda OR e.apellido LIKE :busqueda OR l.titulo LIKE :busqueda)";
}

$sql .= " ORDER BY p.fecha_prestamo DESC
          LIMIT $inicio, $por_pagina";

$stmt = ejecutarConsulta($sql, $params);
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/prestamos.css">
</head>

<body>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Gestión de Préstamos</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoPrestamoModal" type="button">
                <i class="fas fa-plus"></i> Nuevo Préstamo
            </button>
        </div>

        <!-- Filtros y búsqueda -->
        <div class=" card shadow mb-4">
            <div class="card-body">
                <form method="GET" action="prestamos.php" class="form-inline">
                    <div class="form-group mr-3">
                        <label for="estado" class="mr-2">Filtrar por estado:</label>
                        <select class="form-control" id="estado" name="estado" onchange="this.form.submit()">
                            <option value="todos" <?php echo $filtro_estado == 'todos' ? 'selected' : ''; ?>>Todos
                            </option>
                            <option value="Pendiente" <?php echo $filtro_estado == 'Pendiente' ? 'selected' : ''; ?>>
                                Pendientes</option>
                            <option value="Devuelto" <?php echo $filtro_estado == 'Devuelto' ? 'selected' : ''; ?>>
                                Devueltos
                            </option>
                            <option value="Atrasado" <?php echo $filtro_estado == 'Atrasado' ? 'selected' : ''; ?>>
                                Atrasados
                            </option>
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" name="busqueda" placeholder="Buscar préstamos..."
                            value="<?php echo htmlspecialchars($busqueda); ?>">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <?php if ($busqueda): ?>
                            <a href="prestamos.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de préstamos -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Libro</th>
                                <th>Fecha Préstamo</th>
                                <th>Devolución</th>
                                <th>Días Restantes</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prestamos as $prestamo): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($prestamo['estudiante_apellido'] . ', ' . $prestamo['estudiante_nombre']); ?>
                                    <br><small
                                        class="text-muted"><?php echo htmlspecialchars($prestamo['codigo_estudiante']); ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($prestamo['libro_titulo']); ?>
                                    <br><small
                                        class="text-muted"><?php echo htmlspecialchars($prestamo['libro_autor']); ?></small>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_prestamo'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_devolucion_estimada'])); ?></td>
                                <td>
                                    <?php if ($prestamo['estado'] == 'Pendiente'): ?>
                                    <?php if ($prestamo['dias_restantes'] >= 0): ?>
                                    <span class="badge badge-info"><?php echo $prestamo['dias_restantes']; ?>
                                        días</span>
                                    <?php else: ?>
                                    <span class="badge badge-danger"><?php echo abs($prestamo['dias_restantes']); ?>
                                        días de
                                        retraso</span>
                                    <?php endif; ?>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($prestamo['estado'] == 'Pendiente'): ?>
                                    <span class="badge badge-info">Pendiente</span>
                                    <?php elseif ($prestamo['estado'] == 'Devuelto'): ?>
                                    <span class=" badge badge-success">Devuelto</span>
                                    <?php else: ?>
                                    <span class="badge badge-danger">Atrasado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($prestamo['estado'] != 'Devuelto'): ?>
                                    <button class="btn btn-sm btn-success btn-devolver" data-bs-toggle="modal"
                                        data-bs-target="#confirmarDevolucionModal"
                                        data-id="<?php echo $prestamo['id_prestamo']; ?>">
                                        <i class="fas fa-check"></i> Devolver
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-info btn-detalle" data-bs-toggle="modal"
                                        data-bs-target="#detallePrestamoModal"
                                        data-id="<?php echo $prestamo['id_prestamo']; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagina > 1): ?>
                        <li class="page-item">
                            <a class="page-link"
                                href="prestamos.php?pagina=<?php echo $pagina - 1; ?><?php echo $filtro_estado != 'todos' ? '&estado=' . $filtro_estado : ''; ?><?php echo $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>">
                                Anterior
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?php echo $i == $pagina ? 'active' : ''; ?>">
                            <a class="page-link"
                                href="prestamos.php?pagina=<?php echo $i; ?><?php echo $filtro_estado != 'todos' ? '&estado=' . $filtro_estado : ''; ?><?php echo $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($pagina < $total_paginas): ?>
                        <li class="page-item">
                            <a class="page-link"
                                href="prestamos.php?pagina=<?php echo $pagina + 1; ?><?php echo $filtro_estado != 'todos' ? '&estado=' . $filtro_estado : ''; ?><?php echo $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>">
                                Siguiente
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- Modal para nuevo préstamo -->
    <div class="modal fade" id="nuevoPrestamoModal" tabindex="-1" aria-labelledby="nuevoPrestamoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoPrestamoModalLabel">Registrar Nuevo Préstamo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formNuevoPrestamo" action="includes/acciones_prestamos.php" method="POST">
                    <input type="hidden" name="accion" value="nuevo">
                    <div class="modal-body">
                        <div class="row">
                            <!-- Estudiante -->
                            <div class="col-md-6 mb-3">
                                <label for="estudiante" class="form-label">Estudiante</label>
                                <select class="form-select" id="estudiante" name="estudiante" required>
                                    <option value="">Seleccione un estudiante</option>
                                    <?php
                                    $stmt_estudiantes = ejecutarConsulta("
                                        SELECT e.id_estudiante, e.nombre, e.apellido, e.codigo_estudiante, c.nombre_carrera
                                        FROM estudiantes e
                                        JOIN carreras c ON e.id_carrera = c.id_carrera
                                        ORDER BY e.apellido, e.nombre
                                    ");
                                    $estudiantes = $stmt_estudiantes->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($estudiantes as $estudiante):
                                    ?>
                                    <option value="<?= $estudiante['id_estudiante']; ?>">
                                        <?= htmlspecialchars($estudiante['apellido'] . ', ' . $estudiante['nombre'] . ' (' . $estudiante['codigo_estudiante'] . ') - ' . $estudiante['nombre_carrera']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Libro -->
                            <div class="col-md-6 mb-3">
                                <label for="libro" class="form-label">Libro</label>
                                <select class="form-select" id="libro" name="libro" required>
                                    <option value="">Seleccione un libro</option>
                                    <?php
                                    $stmt_libros = ejecutarConsulta("
                                        SELECT id_libro, titulo, autor, codigo
                                        FROM libros
                                        WHERE estado = 'disponible'
                                        ORDER BY titulo
                                    ");
                                    $libros = $stmt_libros->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($libros as $libro):
                                    ?>
                                    <option value="<?= $libro['id_libro']; ?>">
                                        <?= htmlspecialchars($libro['titulo'] . ' - ' . $libro['autor'] . ' (' . $libro['codigo'] . ')'); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Turno -->
                            <div class="col-md-6 mb-3">
                                <label for="turno" class="form-label">Turno</label>
                                <select class="form-select" id="turno" name="turno" required>
                                    <option value="">Seleccione un turno</option>
                                    <option value="Mañana">Mañana</option>
                                    <option value="Tarde">Tarde</option>
                                    <option value="Noche">Noche</option>
                                </select>
                            </div>

                            <!-- Días de préstamo -->
                            <div class="col-md-6 mb-3">
                                <label for="dias" class="form-label">Días de Préstamo</label>
                                <input type="number" class="form-control" id="dias" name="dias" min="1" max="30"
                                    value="7" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar Préstamo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar devolución -->
    <div class="modal fade" id="confirmarDevolucionModal" tabindex="-1" aria-labelledby="confirmarDevolucionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmarDevolucionModalLabel">Confirmar Devolución</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro que desea registrar la devolución de este libro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formDevolverPrestamo" action="includes/acciones_prestamos.php" method="POST"
                        style="display: inline;">
                        <input type="hidden" name="accion" value="devolver">
                        <input type="hidden" name="id_prestamo" id="devolver_id_prestamo">
                        <button type="submit" class="btn btn-success">Confirmar Devolución</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Mostrar modal de confirmación de devolución
        $('.btn-devolver').click(function() {
            var id = $(this).data('id');
            $('#devolver_id_prestamo').val(id);
            $('#confirmarDevolucionModal').modal('show');
        });

        // Manejar envío de formularios con AJAX
        $('#formNuevoPrestamo, #formDevolverPrestamo').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = form.serialize();
            var url = form.attr('action');

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        if (data.success) {
                            mostrarNotificacion(data.message, 'success');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            mostrarNotificacion(data.message, 'error');
                        }
                    } catch (e) {
                        mostrarNotificacion('Error al procesar la respuesta', 'error');
                    }
                },
                error: function() {
                    mostrarNotificacion('Error al enviar la solicitud', 'error');
                }
            });
        });
    });
    </script>

    <?php
    require_once 'components/footer.php';
    ?>
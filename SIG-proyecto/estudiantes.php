<?php
$titulo_pagina = "Gestión de Estudiantes";
require_once 'components/header.php';
require_once 'components/funciones.php';
require_once 'config/connection.php';
// Paginación
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 10;
$inicio = ($pagina > 1) ? ($pagina * $por_pagina - $por_pagina) : 0;

// Búsqueda
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

// Obtener total de estudiantes
if ($busqueda) {
    $sql_total = "SELECT COUNT(*) as total FROM estudiantes 
                  WHERE nombre LIKE :busqueda OR apellido LIKE :busqueda OR codigo_estudiante LIKE :busqueda";
    $stmt_total = ejecutarConsulta($sql_total, [':busqueda' => "%$busqueda%"]);
} else {
    $sql_total = "SELECT COUNT(*) as total FROM estudiantes";
    $stmt_total = ejecutarConsulta($sql_total);
}
$total_estudiantes = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];
$total_paginas = ceil($total_estudiantes / $por_pagina);

// Obtener estudiantes
if ($busqueda) {
    $sql = "SELECT e.*, c.nombre_carrera, f.nombre_facultad,
                   (
                       SELECT ra.fecha_entrada FROM registro_asistencia ra 
                       WHERE ra.id_estudiante = e.id_estudiante 
                       ORDER BY ra.fecha_entrada DESC LIMIT 1
                   ) as hora_entrada,
                   (
                       SELECT ra.fecha_salida FROM registro_asistencia ra 
                       WHERE ra.id_estudiante = e.id_estudiante 
                       ORDER BY ra.fecha_entrada DESC LIMIT 1
                   ) as hora_salida
            FROM estudiantes e
            JOIN carreras c ON e.id_carrera = c.id_carrera
            JOIN facultades f ON c.id_facultad = f.id_facultad
            WHERE e.nombre LIKE :busqueda OR e.apellido LIKE :busqueda OR e.codigo_estudiante LIKE :busqueda
            ORDER BY e.apellido, e.nombre
            LIMIT $inicio, $por_pagina";
    $stmt = ejecutarConsulta($sql, [':busqueda' => "%$busqueda%"]);
} else {
    $sql = "SELECT e.*, c.nombre_carrera, f.nombre_facultad,
                   (
                       SELECT ra.fecha_entrada FROM registro_asistencia ra 
                       WHERE ra.id_estudiante = e.id_estudiante 
                       ORDER BY ra.fecha_entrada DESC LIMIT 1
                   ) as hora_entrada,
                   (
                       SELECT ra.fecha_salida FROM registro_asistencia ra 
                       WHERE ra.id_estudiante = e.id_estudiante 
                       ORDER BY ra.fecha_entrada DESC LIMIT 1
                   ) as hora_salida
            FROM estudiantes e
            JOIN carreras c ON e.id_carrera = c.id_carrera
            JOIN facultades f ON c.id_facultad = f.id_facultad
            ORDER BY e.apellido, e.nombre
            LIMIT $inicio, $por_pagina";
    $stmt = ejecutarConsulta($sql);
}
$estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$registros_academicos = [];
foreach ($estudiantes as $est) {
    $stmt_reg = ejecutarConsulta("SELECT fecha_entrada, fecha_salida FROM registro_asistencia WHERE id_estudiante = ? ORDER BY fecha_entrada DESC LIMIT 5", [$est['id_estudiante']]);
    $registros_academicos[$est['id_estudiante']] = $stmt_reg->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener carreras para el formulario
$stmt_carreras = ejecutarConsulta("
    SELECT c.id_carrera, c.nombre_carrera, f.nombre_facultad 
    FROM carreras c
    JOIN facultades f ON c.id_facultad = f.id_facultad
    ORDER BY f.nombre_facultad, c.nombre_carrera
");
$carreras = $stmt_carreras->fetchAll(PDO::FETCH_ASSOC);

// Obtener registros académicos (asistencia) para cada estudiante

?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Estudiantes</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarEstudianteModal">
            <i class="fas fa-plus"></i> Agregar Estudiante
        </button>

    </div>


    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="estudiantes.php" class="form-inline">
                <div class="input-group">
                    <input type="text" class="form-control" name="busqueda" placeholder="Buscar estudiantes..."
                        value="<?php echo htmlspecialchars($busqueda); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php if ($busqueda): ?>
                        <a href="estudiantes.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Cedula</th>
                            <th>Nombre</th>
                            <th>Carrera</th>
                            <th>Facultad</th>
                            <th>Género</th>
                            <th>Hora de entrada</th>
                            <th>Hora de Salida</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $estudiante): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($estudiante['codigo_estudiante']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['apellido'] . ', ' . $estudiante['nombre']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($estudiante['nombre_carrera']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['nombre_facultad']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['genero']); ?></td>
                            <td><?php echo htmlspecialchars($estudiante['hora_entrada'] ? date('d/m/Y H:i', strtotime($estudiante['hora_entrada'])) : '-'); ?>
                            </td>
                            <td><?php echo htmlspecialchars($estudiante['hora_salida'] ? date('d/m/Y H:i', strtotime($estudiante['hora_salida'])) : '-'); ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-editar" data-toggle="modal"
                                    data-target="#editarEstudianteModal"
                                    data-id="<?php echo $estudiante['id_estudiante']; ?>"
                                    data-codigo="<?php echo htmlspecialchars($estudiante['codigo_estudiante']); ?>"
                                    data-nombre="<?php echo htmlspecialchars($estudiante['nombre']); ?>"
                                    data-apellido="<?php echo htmlspecialchars($estudiante['apellido']); ?>"
                                    data-genero="<?php echo $estudiante['genero']; ?>"
                                    data-carrera="<?php echo $estudiante['id_carrera']; ?>"
                                    data-email="<?php echo htmlspecialchars($estudiante['email'] ?? ''); ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar" data-toggle="modal"
                                    data-target="#confirmarEliminarModal"
                                    data-id="<?php echo $estudiante['id_estudiante']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($pagina > 1): ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="estudiantes.php?pagina=<?php echo $pagina - 1; ?><?php echo $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>">
                            Anterior
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?php echo $i == $pagina ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="estudiantes.php?pagina=<?php echo $i; ?><?php echo $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($pagina < $total_paginas): ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="estudiantes.php?pagina=<?php echo $pagina + 1; ?><?php echo $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>">
                            Siguiente
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>


<div class="modal fade" id="agregarEstudianteModal" tabindex="-1" aria-labelledby="agregarEstudianteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarEstudianteModalLabel">Agregar Nuevo Estudiante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formAgregarEstudiante" action="includes/acciones_estudiantes.php" method="POST">
                <input type="hidden" name="accion" value="agregar">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código de Estudiante</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="genero" class="form-label">Género</label>
                        <select class="form-select" id="genero" name="genero" required>
                            <option value="">Seleccione un género</option>
                            <option value="Hombre">Hombre</option>
                            <option value="Mujer">Mujer</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="carrera" class="form-label">Carrera</label>
                        <select class="form-select" id="carrera" name="carrera" required>
                            <option value="">Seleccione una carrera</option>
                            <?php foreach ($carreras as $carrera): ?>
                            <option value="<?= $carrera['id_carrera']; ?>">
                                <?= htmlspecialchars($carrera['nombre_facultad'] . ' - ' . $carrera['nombre_carrera']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Estudiante</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editarEstudianteModal" tabindex="-1" role="dialog"
    aria-labelledby="editarEstudianteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarEstudianteModalLabel">Editar Estudiante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarEstudiante" action="includes/acciones_estudiantes.php" method="POST">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id_estudiante" id="editar_id_estudiante">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="editar_codigo">Código de Estudiante</label>
                        <input type="text" class="form-control" id="editar_codigo" name="codigo" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editar_nombre">Nombre</label>
                        <input type="text" class="form-control" id="editar_nombre" name="nombre" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editar_apellido">Apellido</label>
                        <input type="text" class="form-control" id="editar_apellido" name="apellido" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editar_genero">Género</label>
                        <select class="form-control" id="editar_genero" name="genero" required>
                            <option value="">Seleccione un género</option>
                            <option value="Hombre">Hombre</option>
                            <option value="Mujer">Mujer</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editar_carrera">Carrera</label>
                        <select class="form-control" id="editar_carrera" name="carrera" required>
                            <option value="">Seleccione una carrera</option>
                            <?php foreach ($carreras as $carrera): ?>
                            <option value="<?php echo $carrera['id_carrera']; ?>">
                                <?php echo htmlspecialchars($carrera['nombre_facultad'] . ' - ' . $carrera['nombre_carrera']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editar_email">Email</label>
                        <input type="email" class="form-control" id="editar_email" name="email">
                    </div>
                    <div class="form-group mb-3">
                        <label for="editar_hora_entrada">Hora de Entrada (última)</label>
                        <input type="datetime-local" class="form-control" id="editar_hora_entrada" name="hora_entrada">
                    </div>
                    <div class="form-group mb-3">
                        <label for="editar_hora_salida">Hora de Salida (última)</label>
                        <input type="datetime-local" class="form-control" id="editar_hora_salida" name="hora_salida">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" role="dialog"
    aria-labelledby="confirmarEliminarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarEliminarModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Está seguro que desea eliminar este estudiante? Esta acción no se puede deshacer.
                <div class="alert alert-warning mt-3">
                    <strong>Advertencia:</strong> Si el estudiante tiene préstamos activos, no podrá ser eliminado.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminarEstudiante" action="includes/acciones_estudiantes.php" method="POST"
                    style="display: inline;">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id_estudiante" id="eliminar_id_estudiante">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="notificaciones"></div>

<script>
// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo) {
    var icono = tipo === 'success' ? 'check-circle' : 'exclamation-circle';
    var color = tipo === 'success' ? 'success' : 'danger';

    var notificacion = `
        <div class="alert alert-${color} alert-dismissible fade show" role="alert">
            <i class="fas fa-${icono} mr-2"></i>
            ${mensaje}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;

    // Insertar la notificación al principio del contenedor
    $('.container-fluid').prepend(notificacion);

    // Auto cerrar después de 5 segundos
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
}

$(document).ready(function() {
    // Mostrar modal de edición
    $('.btn-editar').click(function() {
        var id = $(this).data('id');
        var codigo = $(this).data('codigo');
        var nombre = $(this).data('nombre');
        var apellido = $(this).data('apellido');
        var genero = $(this).data('genero');
        var carrera = $(this).data('carrera');
        var email = $(this).data('email');
        var hora_entrada = $(this).closest('tr').find('td').eq(5).text();
        var hora_salida = $(this).closest('tr').find('td').eq(6).text();

        $('#editar_id_estudiante').val(id);
        $('#editar_codigo').val(codigo);
        $('#editar_nombre').val(nombre);
        $('#editar_apellido').val(apellido);
        $('#editar_genero').val(genero);
        $('#editar_carrera').val(carrera);
        $('#editar_email').val(email);
        $('#editar_hora_entrada').val(convertirFecha(hora_entrada));
        $('#editar_hora_salida').val(convertirFecha(hora_salida));

        $('#editarEstudianteModal').modal('show');
    });

    // Mostrar modal de confirmación de eliminación
    $('.btn-eliminar').click(function() {
        var id = $(this).data('id');
        $('#eliminar_id_estudiante').val(id);
        $('#confirmarEliminarModal').modal('show');
    });

    // Manejar envío de formularios con AJAX
    $('#formAgregarEstudiante, #formEditarEstudiante, #formEliminarEstudiante').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();
        var url = form.attr('action');
        var accion = form.find('input[name="accion"]').val();

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var mensaje = '';
                    switch (accion) {
                        case 'agregar':
                            mensaje = 'Estudiante agregado correctamente';
                            break;
                        case 'editar':
                            mensaje = 'Estudiante actualizado correctamente';
                            break;
                        case 'eliminar':
                            mensaje = 'Estudiante eliminado correctamente';
                            break;
                    }
                    mostrarNotificacion(mensaje, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarNotificacion(response.message ||
                        'Error al procesar la solicitud', 'error');
                }
            },
            error: function(xhr, status, error) {
                var mensajeError = 'Error al procesar la solicitud';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensajeError = xhr.responseJSON.message;
                }
                mostrarNotificacion(mensajeError, 'error');
            }
        });
    });

    // Cerrar modales después de enviar el formulario
    $('#formAgregarEstudiante, #formEditarEstudiante, #formEliminarEstudiante').on('submit', function() {
        $(this).closest('.modal').modal('hide');
    });

    // Convertir formato dd/mm/yyyy hh:mm a yyyy-mm-ddThh:mm para input type datetime-local
    function convertirFecha(fecha) {
        if (!fecha || fecha === '-') return '';
        var partes = fecha.split(' ');
        var fechaPartes = partes[0].split('/');
        var hora = partes[1] || '00:00';
        return fechaPartes[2] + '-' + fechaPartes[1] + '-' + fechaPartes[0] + 'T' + hora;
    }
});
</script>
<!-- Modal para editar estudiante -->
<?php
require_once 'components/footer.php';
?>
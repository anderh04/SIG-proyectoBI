<?php
$titulo_pagina = "Detalle de Estudiante";
require_once 'components/header.php';
require_once 'components/funciones.php';

$id_estudiante = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_estudiante <= 0) {
    header("Location: estudiantes.php");
    exit();
}

// Obtener información del estudiante
$sql = "SELECT e.*, c.nombre_carrera, f.nombre_facultad
        FROM estudiantes e
        JOIN carreras c ON e.id_carrera = c.id_carrera
        JOIN facultades f ON c.id_facultad = f.id_facultad
        WHERE e.id_estudiante = ?";
$stmt = ejecutarConsulta($sql, [$id_estudiante]);
$estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$estudiante) {
    header("Location: estudiantes.php");
    exit();
}

// Obtener préstamos activos del estudiante
$prestamos_activos = obtenerPrestamosActivos($id_estudiante);

// Obtener historial de préstamos
$historial_prestamos = obtenerHistorialPrestamos($id_estudiante, null, 10);
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detalle del Estudiante</h1>
        <a href="estudiantes.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Estudiantes
        </a>
    </div>

    <!-- Información del estudiante -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Información Personal</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Código:</strong> <?php echo htmlspecialchars($estudiante['codigo_estudiante']); ?></p>
                    <p><strong>Nombre:</strong>
                        <?php echo htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']); ?></p>
                    <p><strong>Género:</strong> <?php echo htmlspecialchars($estudiante['genero']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Facultad:</strong> <?php echo htmlspecialchars($estudiante['nombre_facultad']); ?></p>
                    <p><strong>Carrera:</strong> <?php echo htmlspecialchars($estudiante['nombre_carrera']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($estudiante['email'] ?? 'No registrado'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Préstamos activos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-warning">Préstamos Activos</h6>
            <a href="prestamos.php?estado=Pendiente" class="btn btn-sm btn-warning">
                <i class="fas fa-exchange-alt"></i> Ver todos
            </a>
        </div>
        <div class="card-body">
            <?php if (count($prestamos_activos) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Libro</th>
                                <th>Fecha Préstamo</th>
                                <th>Fecha Devolución</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prestamos_activos as $prestamo): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($prestamo['titulo']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_prestamo'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_devolucion_estimada'])); ?></td>
                                    <td>
                                        <?php if ($prestamo['estado'] == 'Pendiente'): ?>
                                            <span class="badge badge-warning">Pendiente</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Atrasado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success btn-devolver"
                                            data-id="<?php echo $prestamo['id_prestamo']; ?>">
                                            <i class="fas fa-check"></i> Devolver
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">El estudiante no tiene préstamos activos</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Historial de préstamos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Historial de Préstamos</h6>
            <a href="prestamos.php" class="btn btn-sm btn-primary">
                <i class="fas fa-exchange-alt"></i> Ver todos
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Libro</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Fecha Devuelto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial_prestamos as $prestamo): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($prestamo['libro_titulo']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_prestamo'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($prestamo['fecha_devolucion_estimada'])); ?></td>
                                <td>
                                    <?php if ($prestamo['fecha_devolucion_real']): ?>
                                        <?php echo date('d/m/Y', strtotime($prestamo['fecha_devolucion_real'])); ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($prestamo['estado'] == 'Pendiente'): ?>
                                        <span class="badge badge-warning">Pendiente</span>
                                    <?php elseif ($prestamo['estado'] == 'Devuelto'): ?>
                                        <span class="badge badge-success">Devuelto</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Atrasado</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar devolución -->
<div class="modal fade" id="confirmarDevolucionModal" tabindex="-1" role="dialog"
    aria-labelledby="confirmarDevolucionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarDevolucionModalLabel">Confirmar Devolución</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Está seguro que desea registrar la devolución de este libro?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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

        // Manejar envío de formulario de devolución con AJAX
        $('#formDevolverPrestamo').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = form.serialize();
            var url = form.attr('action');

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    manejarExitoAjax(response);
                },
                error: manejarErrorAjax
            });
        });
    });
</script>

<?php
require_once 'components/footer.php';
?>
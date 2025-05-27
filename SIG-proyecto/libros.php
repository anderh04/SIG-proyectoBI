<?php
$titulo_pagina = "Gestión de Libros";

require_once 'components/header.php';
require_once 'config/connection.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca CRUBA - Gestión de Libros</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/libros.css">
</head>

<?php
// Paginación
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 10;
$inicio = ($pagina > 1) ? ($pagina * $por_pagina - $por_pagina) : 0;

// Búsqueda
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

// Obtener total de libros
if ($busqueda) {
    $sql_total = "SELECT COUNT(*) as total FROM libros 
                  WHERE titulo LIKE :busqueda OR autor LIKE :busqueda OR isbn LIKE :busqueda";
    $stmt_total = ejecutarConsulta($sql_total, [':busqueda' => "%$busqueda%"]);
} else {
    $sql_total = "SELECT COUNT(*) as total FROM libros";
    $stmt_total = ejecutarConsulta($sql_total);
}
$total_libros = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];
$total_paginas = ceil($total_libros / $por_pagina);

// Obtener libros
if ($busqueda) {
    $sql = "SELECT l.*, c.nombre_categoria, 
                   (SELECT COUNT(*) FROM prestamos WHERE id_libro = l.id_libro AND (estado = 'Pendiente' OR estado = 'Atrasado')) as prestamos_activos
            FROM libros l
            JOIN categorias c ON l.id_categoria = c.id_categoria
            WHERE l.titulo LIKE :busqueda OR l.autor LIKE :busqueda OR l.isbn LIKE :busqueda
            ORDER BY l.titulo
            LIMIT $inicio, $por_pagina";
    $stmt = ejecutarConsulta($sql, [':busqueda' => "%$busqueda%"]);
} else {
    $sql = "SELECT l.*, c.nombre_categoria, 
                   (SELECT COUNT(*) FROM prestamos WHERE id_libro = l.id_libro AND (estado = 'Pendiente' OR estado = 'Atrasado')) as prestamos_activos
            FROM libros l
            JOIN categorias c ON l.id_categoria = c.id_categoria
            ORDER BY l.titulo
            LIMIT $inicio, $por_pagina";
    $stmt = ejecutarConsulta($sql);
}
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener categorías para el formulario
$stmt_categorias = ejecutarConsulta("SELECT * FROM categorias ORDER BY nombre_categoria");
$categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Libros</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarLibroModal">
            <i class="fas fa-plus"></i> Agregar Libro
        </button>
    </div>

    <!-- Barra de búsqueda -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="libros.php" class="form-inline">
                <div class="input-group">
                    <input type="text" class="form-control" name="busqueda" placeholder="Buscar libros..."
                        value="<?php echo htmlspecialchars($busqueda); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php if ($busqueda): ?>
                        <a href="libros.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de libros -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Categoría</th>
                            <th>ISBN</th>
                            <th>Disponibilidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($libro['autor']); ?></td>
                            <td><?php echo htmlspecialchars($libro['nombre_categoria']); ?></td>
                            <td><?php echo htmlspecialchars($libro['isbn'] ?? 'N/A'); ?></td>
                            <td>
                                <?php if ($libro['disponible'] && $libro['prestamos_activos'] == 0): ?>
                                <span class="badge badge-success">Disponible</span>
                                <?php else: ?>
                                <span class="badge badge-danger">Prestado</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info btn-editar"
                                    data-id="<?php echo $libro['id_libro']; ?>"
                                    data-titulo="<?php echo htmlspecialchars($libro['titulo']); ?>"
                                    data-autor="<?php echo htmlspecialchars($libro['autor']); ?>"
                                    data-categoria="<?php echo $libro['id_categoria']; ?>"
                                    data-isbn="<?php echo htmlspecialchars($libro['isbn'] ?? ''); ?>"
                                    data-anio="<?php echo $libro['año_publicacion']; ?>"
                                    data-editorial="<?php echo htmlspecialchars($libro['editorial'] ?? ''); ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar"
                                    data-id="<?php echo $libro['id_libro']; ?>">
                                    <i class="fas fa-trash"></i>
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
                            href="libros.php?pagina=<?php echo $pagina - 1; ?><?php echo $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>">
                            Anterior
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?php echo $i == $pagina ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="libros.php?pagina=<?php echo $i; ?><?php echo $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($pagina < $total_paginas): ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="libros.php?pagina=<?php echo $pagina + 1; ?><?php echo $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>">
                            Siguiente
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal para agregar libro -->
<div class="modal fade" id="agregarLibroModal" tabindex="-1" role="dialog" aria-labelledby="agregarLibroModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarLibroModalLabel">Agregar Nuevo Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAgregarLibro" action="includes/acciones_libros.php" method="POST">
                <input type="hidden" name="accion" value="agregar">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="autor">Autor</label>
                        <input type="text" class="form-control" id="autor" name="autor" required>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoría</label>
                        <select class="form-control" id="categoria" name="categoria" required>
                            <option value="">Seleccione una categoría</option>
                            <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id_categoria']; ?>">
                                <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn">
                    </div>
                    <div class="form-group">
                        <label for="anio">Año de Publicación</label>
                        <input type="number" class="form-control" id="anio" name="anio" min="1900"
                            max="<?php echo date('Y'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="editorial">Editorial</label>
                        <input type="text" class="form-control" id="editorial" name="editorial">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Libro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar libro -->
<div class="modal fade" id="editarLibroModal" tabindex="-1" role="dialog" aria-labelledby="editarLibroModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarLibroModalLabel">Editar Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarLibro" action="includes/acciones_libros.php" method="POST">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id_libro" id="editar_id_libro">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editar_titulo">Título</label>
                        <input type="text" class="form-control" id="editar_titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="editar_autor">Autor</label>
                        <input type="text" class="form-control" id="editar_autor" name="autor" required>
                    </div>
                    <div class="form-group">
                        <label for="editar_categoria">Categoría</label>
                        <select class="form-control" id="editar_categoria" name="categoria" required>
                            <option value="">Seleccione una categoría</option>
                            <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id_categoria']; ?>">
                                <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editar_isbn">ISBN</label>
                        <input type="text" class="form-control" id="editar_isbn" name="isbn">
                    </div>
                    <div class="form-group">
                        <label for="editar_anio">Año de Publicación</label>
                        <input type="number" class="form-control" id="editar_anio" name="anio" min="1900"
                            max="<?php echo date('Y'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="editar_editorial">Editorial</label>
                        <input type="text" class="form-control" id="editar_editorial" name="editorial">
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

<!-- Modal para confirmar eliminación -->
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
                ¿Está seguro que desea eliminar este libro? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminarLibro" action="includes/acciones_libros.php" method="POST"
                    style="display: inline;">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id_libro" id="eliminar_id_libro">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Modal de editar libro
    $('.btn-editar').click(function() {
        const id = $(this).data('id');
        const titulo = $(this).data('titulo');
        const autor = $(this).data('autor');
        const categoria = $(this).data('categoria');
        const isbn = $(this).data('isbn');
        const anio = $(this).data('anio');
        const editorial = $(this).data('editorial');

        $('#editar_id_libro').val(id);
        $('#editar_titulo').val(titulo);
        $('#editar_autor').val(autor);
        $('#editar_categoria').val(categoria);
        $('#editar_isbn').val(isbn);
        $('#editar_anio').val(anio);
        $('#editar_editorial').val(editorial);

        $('#editarLibroModal').modal('show');
    });

    // Modal de eliminar libro
    $('.btn-eliminar').click(function() {
        const id = $(this).data('id');
        $('#eliminar_id_libro').val(id);
        $('#confirmarEliminarModal').modal('show');
    });
});
</script>

<!-- Contenedor para notificaciones -->
<div id="notificaciones" class="position-fixed" style="top: 20px; right: 20px; z-index: 1050;"></div>

<?php
require_once 'components/footer.php';
?>
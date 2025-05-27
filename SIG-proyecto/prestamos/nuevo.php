<?php
require_once '../../config/connection.php';
require_once '../components/functions.php';
require_once '../components/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $isbn = $_POST['isbn'];
    $categoria_id = $_POST['categoria_id'];
    $editorial = $_POST['editorial'];
    $anio = $_POST['anio'];
    
    $stmt = $conn->prepare("INSERT INTO libros (titulo, autor, isbn, categoria_id, editorial, anio) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titulo, $autor, $isbn, $categoria_id, $editorial, $anio]);
    
    header("Location: listar.php");
    exit;
}

$categorias = obtenerCategorias($conn);
?>

<h2>Agregar Nuevo Libro</h2>
<form method="post">
    <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" class="form-control" name="titulo" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Autor</label>
        <input type="text" class="form-control" name="autor" required>
    </div>
    <div class="mb-3">
        <label class="form-label">ISBN</label>
        <input type="text" class="form-control" name="isbn" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Categoría</label>
        <select class="form-select" name="categoria_id" required>
            <?php foreach ($categorias as $categoria): ?>
            <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Editorial</label>
        <input type="text" class="form-control" name="editorial">
    </div>
    <div class="mb-3">
        <label class="form-label">Año de publicación</label>
        <input type="number" class="form-control" name="anio">
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>

<?php require_once '../components/footer.php'; ?>
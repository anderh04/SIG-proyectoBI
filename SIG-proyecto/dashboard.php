<?php
require_once 'config/connection.php';
$titulo_pagina = "Dashboard";

require_once 'components/header.php';
require_once 'components/funciones.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Biblioteca CRUBA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<div class="dashboard-grid">
    <!-- Tarjeta de resumen -->
    <div class="card summary-card">
        <div class="card-header">
            <h2>Resumen General</h2>
        </div>
        <div class="card-body">
            <div class="summary-item">
                <div class="summary-icon bg-primary">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="summary-info">
                    <h3>Libros Prestados</h3>
                    <?php
                    $stmt = ejecutarConsulta("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'Pendiente' OR estado = 'Atrasado'");
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo '<p class="summary-number">' . $row['total'] . '</p>';
                    ?>
                </div>
            </div>

            <div class="summary-item">
                <div class="summary-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="summary-info">
                    <h3>Libros Devueltos</h3>
                    <?php
                    $stmt = ejecutarConsulta("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'Devuelto'");
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo '<p class="summary-number">' . $row['total'] . '</p>';
                    ?>
                </div>
            </div>

            <div class="summary-item">
                <div class="summary-icon bg-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="summary-info">
                    <h3>Préstamos Atrasados</h3>
                    <?php
                    $stmt = ejecutarConsulta("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'Atrasado'");
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo '<p class="summary-number">' . $row['total'] . '</p>';
                    ?>
                </div>
            </div>

            <div class="summary-item">
                <div class="summary-icon bg-info">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="summary-info">
                    <h3>Solicitudes Pendientes</h3>
                    <?php
                    $stmt = ejecutarConsulta("SELECT COUNT(*) as total FROM solicitudes_permiso WHERE estado = 'Pendiente'");
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo '<p class="summary-number">' . $row['total'] . '</p>';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de préstamos por categoría -->
    <div class="card chart-card">
        <div class="card-header">
            <h2>Préstamos por Categoría</h2>
        </div>
        <div class="card-body">
            <canvas id="categoriaChart"></canvas>
        </div>
    </div>

    <!-- Gráfico de préstamos por carrera -->
    <div class="card chart-card">
        <div class="card-header">
            <h2>Préstamos por Carrera</h2>
        </div>
        <div class="card-body">
            <canvas id="carreraChart"></canvas>
        </div>
    </div>

    <!-- Últimos préstamos -->
    <div class="card table-card">
        <div class="card-header">
            <h2>Últimos Préstamos</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Libro</th>
                            <th>Fecha Préstamo</th>
                            <th>Devolución</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = ejecutarConsulta("
                            SELECT p.id_prestamo, e.nombre, e.apellido, l.titulo, p.fecha_prestamo, 
                                   p.fecha_devolucion_estimada, p.fecha_devolucion_real, p.estado
                            FROM prestamos p
                            JOIN estudiantes e ON p.id_estudiante = e.id_estudiante
                            JOIN libros l ON p.id_libro = l.id_libro
                            ORDER BY p.fecha_prestamo DESC
                            LIMIT 5
                        ");

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>
                                    <td>' . $row['nombre'] . ' ' . $row['apellido'] . '</td>
                                    <td>' . $row['titulo'] . '</td>
                                    <td>' . date('d/m/Y', strtotime($row['fecha_prestamo'])) . '</td>
                                    <td>' . date('d/m/Y', strtotime($row['fecha_devolucion_estimada'])) . '</td>
                                    <td><span class="status-badge ' . strtolower($row['estado']) . '">' . $row['estado'] . '</span></td>
                                  </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Estadísticas por género -->
    <div class="card stats-card">
        <div class="card-header">
            <h2>Estadísticas por Género</h2>
        </div>
        <div class="card-body">
            <div class="gender-stats">
                <?php
                $stmt = ejecutarConsulta("
                    SELECT e.genero, COUNT(DISTINCT p.id_estudiante) as total_estudiantes, 
                           COUNT(p.id_prestamo) as total_prestamos
                    FROM prestamos p
                    JOIN estudiantes e ON p.id_estudiante = e.id_estudiante
                    GROUP BY e.genero
                ");

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $color = $row['genero'] == 'Hombre' ? '#3498db' : ($row['genero'] == 'Mujer' ? '#e83e8c' : '#6c757d');
                    echo '<div class="gender-item">
                            <div class="gender-info">
                                <h3>' . $row['genero'] . '</h3>
                                <p><strong>Estudiantes:</strong> ' . $row['total_estudiantes'] . '</p>
                                <p><strong>Préstamos:</strong> ' . $row['total_prestamos'] . '</p>
                            </div>
                          </div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Préstamos por turno -->
    <div class="card chart-card">
        <div class="card-header">
            <h2>Préstamos por Turno</h2>
        </div>
        <div class="card-body">
            <canvas id="turnoChart"></canvas>
        </div>
    </div>
</div>

<script>
// Gráfico de categorías
document.addEventListener('DOMContentLoaded', function() {
    const categoriaCtx = document.getElementById('categoriaChart').getContext('2d');
    const categoriaChart = new Chart(categoriaCtx, {
        type: 'doughnut',
        data: {
            labels: [
                <?php
                    $stmt = ejecutarConsulta("
                    SELECT c.nombre_categoria, COUNT(p.id_prestamo) as total
                    FROM prestamos p
                    JOIN libros l ON p.id_libro = l.id_libro
                    JOIN categorias c ON l.id_categoria = c.id_categoria
                    GROUP BY c.id_categoria
                    ORDER BY total DESC
                    LIMIT 5
                ");

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "'" . $row['nombre_categoria'] . "',";
                    }
                    ?>
            ],
            datasets: [{
                data: [
                    <?php
                        $stmt->execute(); // Re-ejecutamos la consulta
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo $row['total'] . ",";
                        }
                        ?>
                ],
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b'
                ],
                hoverBackgroundColor: [
                    '#2e59d9',
                    '#17a673',
                    '#2c9faf',
                    '#dda20a',
                    '#be2617'
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            },
            cutout: '70%',
        }
    });

    // Gráfico de carreras
    const carreraCtx = document.getElementById('carreraChart').getContext('2d');
    const carreraChart = new Chart(carreraCtx, {
        type: 'bar',
        data: {
            labels: [
                <?php
                    $stmt = ejecutarConsulta("
                    SELECT ca.nombre_carrera, COUNT(p.id_prestamo) as total
                    FROM prestamos p
                    JOIN estudiantes e ON p.id_estudiante = e.id_estudiante
                    JOIN carreras ca ON e.id_carrera = ca.id_carrera
                    GROUP BY ca.id_carrera
                    ORDER BY total DESC
                    LIMIT 5
                ");

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "'" . $row['nombre_carrera'] . "',";
                    }
                    ?>
            ],
            datasets: [{
                label: "Préstamos",
                backgroundColor: '#4e73df',
                hoverBackgroundColor: '#2e59d9',
                borderColor: '#4e73df',
                data: [
                    <?php
                        $stmt->execute(); // Re-ejecutamos la consulta
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo $row['total'] . ",";
                        }
                        ?>
                ],
            }],
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Gráfico de turnos
    const turnoCtx = document.getElementById('turnoChart').getContext('2d');
    const turnoChart = new Chart(turnoCtx, {
        type: 'pie',
        data: {
            labels: ['Mañana', 'Tarde', 'Noche'],
            datasets: [{
                data: [
                    <?php
                        $stmt = ejecutarConsulta("
                        SELECT turno, COUNT(*) as total
                        FROM prestamos
                        GROUP BY turno
                        ORDER BY FIELD(turno, 'Mañana', 'Tarde', 'Noche')
                    ");

                        $data = ['0', '0', '0'];
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            if ($row['turno'] == 'Mañana') $data[0] = $row['total'];
                            if ($row['turno'] == 'Tarde') $data[1] = $row['total'];
                            if ($row['turno'] == 'Noche') $data[2] = $row['total'];
                        }
                        echo implode(',', $data);
                        ?>
                ],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            },
        }
    });
});
</script>

<?php
require_once 'components/footer.php';
?>
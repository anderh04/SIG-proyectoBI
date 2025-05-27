<?php

require_once 'components/header.php';
require_once 'components/funciones.php';

$titulo_pagina = "Gestión de Reportes";

?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Biblioteca CRUBA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/reportes.css">
</head>

<body>
    <div class="container">
        <div class="reportes-grid">
            <!-- Sección de Filtros -->
            <div class="filtros-card">
                <div class="card-header">
                    <h2>Filtros de Reportes</h2>
                </div>
                <div class="card-body">
                    <form id="filtrosForm" class="filtros-form">
                        <div class="form-group">-
                            <label for="tipoReporte">Tipo de Reporte</label>
                            <select id="tipoReporte" name="tipoReporte" class="form-control" fdprocessedid="pmgbir">
                                <option value="estudiantes">Reporte de Estudiantes</option>
                                <option value="libros">Reporte de Libros</option>
                                <option value="prestamos">Reporte de Préstamos</option>
                                <option value="general">Reporte General</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fechaInicio">Fecha Inicio</label>
                            <input type="date" id="fechaInicio" name="fechaInicio" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="fechaFin">Fecha Fin</label>
                            <input type="date" id="fechaFin" name="fechaFin" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary" fdprocessedid="2ry7mq">Generar
                            Reporte</button>
                    </form>
                </div>
            </div>

            <!-- Sección de Visualización de Reportes -->
            <div class="reporte-card">
                <div class="card-header">
                    <h2>Reporte Generado</h2>
                    <div class="acciones-reporte">
                        <button class="btn btn-secondary" onclick="exportarPDF()" fdprocessedid="30wuq">
                            <i class="fas fa-file-pdf"></i> Exportar PDF
                        </button>
                        <button class="btn btn-secondary" onclick="exportarExcel()" fdprocessedid="dsx83s">
                            <i class="fas fa-file-excel"></i> Exportar Excel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="contenidoReporte">
                        <!-- Aquí se cargará dinámicamente el contenido del reporte -->
                        <div class="reporte-vacio">
                            <i class="fas fa-chart-bar"></i>
                            <p>Seleccione los filtros y genere un reporte</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="assets/js/reportes.js"></script>


</body>



<?php
require_once 'components/footer.php';
?>
<?php
require_once 'config/connection.php';
require_once 'components/funciones.php';

// Redirigir al dashboard si ya está autenticado
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit();
}

// Redirigir al login si no está autenticado
header("Location: login.php");
exit();

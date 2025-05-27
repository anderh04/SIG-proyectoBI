<?php
session_start();

// Verificar si el usuario está autenticado
function estaAutenticado() {
    return isset($_SESSION['usuario']);
}

// Verificar permisos de administrador
function esAdministrador() {
    return estaAutenticado() && $_SESSION['rol'] == 'Administrador';
}

// Verificar permisos de bibliotecario
function esBibliotecario() {
    return estaAutenticado() && ($_SESSION['rol'] == 'Bibliotecario' || $_SESSION['rol'] == 'Administrador');
}

// Redirigir si no está autenticado
function requerirAutenticacion() {
    if (!estaAutenticado()) {
        header("Location: login.php");
        exit();
    }
}

// Redirigir si no es administrador
function requerirAdministrador() {
    requerirAutenticacion();
    if (!esAdministrador()) {
        header("Location: dashboard.php");
        exit();
    }
}

// Redirigir si no es bibliotecario o admin
function requerirBibliotecario() {
    requerirAutenticacion();
    if (!esBibliotecario()) {
        header("Location: dashboard.php");
        exit();
    }
}
?>
<?php
// admin/reportes.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';

if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) {
    header('Location: acceso_no_permitido.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú de Reportes</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Menú de Reportes</h1>

    <div class="report-menu">
        <a href="<?= APP_URL ?>admin/reporte_registros.php" class="report-card">
            <h2>Registros</h1>
            <p>Listado de personas registradas</p>
        </a>
        <a href="<?= APP_URL ?>admin/reporte_impresiones.php" class="report-card">
            <h2>Impresiones</h2>
            <p>Historial de impresiones realizadas</p>
        </a>
        <a href="<?= APP_URL ?>admin/reporte_accesos.php" class="report-card">
            <h2>Accesos</h2>
            <p>Historial de accesos al sistema</p>
        </a>
    </div>

    <a href="<?= APP_URL ?>panel.php" class="btn">Volver al Panel</a>
</body>
</html>
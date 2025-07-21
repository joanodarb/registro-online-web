<?php
// admin/acceso_no_permitido.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';
// No se requiere autenticación aquí, ya que es la página de acceso denegado
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso No Permitido</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Acceso No Permitido</h1>
    <p>No tienes permiso para acceder a esta sección.</p>
    <a href="<?= APP_URL ?>panel.php" class="btn">Volver al Panel</a>
</body>
</html>
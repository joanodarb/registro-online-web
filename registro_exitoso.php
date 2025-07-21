<?php
// registro_exitoso.php

define('ABSPATH', true);
require_once 'includes/init.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Exitoso</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>✅ Registro Exitoso</h1>
    <p>Tu cuenta ha sido creada. Revisa tu correo para activarla.</p>
    <a href="<?= APP_URL ?>login.php" class="btn">Iniciar Sesión</a>
</body>
</html>
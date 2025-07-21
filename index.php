<?php
define('ABSPATH', true);
require_once 'includes/init.php';

if (isset($_SESSION['user_id'])) {
    header("Location: " . APP_URL . "panel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Bienvenido al Sistema de Registro</h1>
    <p>Por favor, inicia sesión para continuar.</p>
    <a href="<?= APP_URL ?>login.php" class="btn">Iniciar Sesión</a>
</body>
</html>
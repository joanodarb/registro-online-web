<?php
define('ABSPATH', true);
require_once 'includes/init.php';
require_once 'includes/auth.php';

try {
    $stmt = $conn->query("SELECT COUNT(*) FROM personas");
    $totalPersonas = $stmt->fetchColumn();

    $stmt = $conn->query("SELECT COUNT(*) FROM registro");
    $totalRegistros = $stmt->fetchColumn();

    $stmt = $conn->query("SELECT COUNT(*) FROM usuarios");
    $totalUsuarios = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Error al obtener estadísticas: " . $e->getMessage());
    die("Ha ocurrido un error al cargar el panel.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Panel de Administración</h1>

    <div class="stats">
        <div class="card">
            <h3>Personas Registradas</h3>
            <p><?= $totalPersonas ?></p>
        </div>
        <div class="card">
            <h3>Registros Totales</h3>
            <p><?= $totalRegistros ?></p>
        </div>
        <div class="card">
            <h3>Usuarios del Sistema</h3>
            <p><?= $totalUsuarios ?></p>
        </div>
    </div>

    <div class="actions">
        <a href="<?= APP_URL ?>registro.php" class="btn">Nuevo Registro</a>
        <a href="<?= APP_URL ?>reportes.php" class="btn">Ver Reportes</a>
        <a href="<?= APP_URL ?>logout.php" class="btn btn-danger">Cerrar Sesión</a>
    </div>
</body>
</html>
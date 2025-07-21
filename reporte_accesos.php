<?php
// admin/reporte_accesos.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';

if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) {
    header('Location: acceso_no_permitido.php');
    exit();
}

try {
    $stmt = $conn->query("SELECT a.id, a.fecha_acceso, u.user_name, r.fecha_registro FROM acceso a JOIN usuarios u ON a.usuario = u.id LEFT JOIN registro r ON a.id_registro = r.id ORDER BY a.fecha_acceso DESC LIMIT 100");
    $accesos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener accesos: " . $e->getMessage());
    die("Error al cargar accesos.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Accesos</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Reporte de Accesos al Sistema</h1>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Fecha de Acceso</th>
                <th>Fecha de Registro</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accesos as $a): ?>
                <tr>
                    <td><?= $a['id'] ?></td>
                    <td><?= htmlspecialchars($a['user_name']) ?></td>
                    <td><?= htmlspecialchars($a['fecha_acceso']) ?></td>
                    <td><?= htmlspecialchars($a['fecha_registro']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?= APP_URL ?>admin/reportes.php" class="btn">Volver a Reportes</a>
</body>
</html>
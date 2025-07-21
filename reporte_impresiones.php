<?php
// admin/reporte_impresiones.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';

if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) {
    header('Location: acceso_no_permitido.php');
    exit();
}

try {
    $stmt = $conn->query("SELECT i.id, p.nombres, p.apellido_paterno, p.apellido_materno, t.nombre AS plantilla, u.user_name, i.fecha_impresion FROM registros_impresion i JOIN personas p ON i.id_registro = p.id JOIN plantillas_impresion t ON i.id_plantilla = t.id JOIN usuarios u ON i.usuario = u.id ORDER BY i.fecha_impresion DESC");
    $impresiones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener impresiones: " . $e->getMessage());
    die("Error al cargar impresiones.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Impresiones</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Reporte de Impresiones</h1>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Plantilla</th>
                <th>Usuario</th>
                <th>Fecha de Impresión</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($impresiones as $i): ?>
                <tr>
                    <td><?= $i['id'] ?></td>
                    <td><?= htmlspecialchars($i['nombres']) ?></td>
                    <td><?= htmlspecialchars($i['apellido_paterno']) ?></td>
                    <td><?= htmlspecialchars($i['apellido_materno']) ?></td>
                    <td><?= htmlspecialchars($i['plantilla']) ?></td>
                    <td><?= htmlspecialchars($i['user_name']) ?></td>
                    <td><?= htmlspecialchars($i['fecha_impresion']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?= APP_URL ?>admin/reportes.php" class="btn">Volver a Reportes</a>
</body>
</html>
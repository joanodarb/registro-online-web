<?php
// admin/admin_plantillas.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';

// Solo administradores pueden acceder
if (!isAdmin()) {
    header('Location: ' . APP_URL . 'acceso_no_permitido.php');
    exit();
}

try {
    $stmt = $conn->query("SELECT id, nombre, fecha_creacion, fecha_actualizacion FROM plantillas_impresion ORDER BY fecha_creacion DESC");
    $plantillas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener plantillas: " . $e->getMessage());
    die("Ha ocurrido un error al cargar las plantillas.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Plantillas</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Administrar Plantillas de Impresión</h1>

    <a href="<?= APP_URL ?>admin/crear_plantilla.php" class="btn">Crear Nueva Plantilla</a>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha de Creación</th>
                <th>Última Actualización</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($plantillas as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><?= htmlspecialchars($p['fecha_creacion']) ?></td>
                    <td><?= htmlspecialchars($p['fecha_actualizacion']) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>admin/editar_plantilla.php?id=<?= $p['id'] ?>" class="btn">Editar</a>
                        <a href="<?= APP_URL ?>admin/eliminar_plantilla.php?id=<?= $p['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?= APP_URL ?>panel.php" class="btn">Volver al Panel</a>
</body>
</html>
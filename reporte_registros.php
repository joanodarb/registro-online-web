<?php
// admin/reporte_registros.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';

if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 3) {
    header('Location: acceso_no_permitido.php');
    exit();
}

try {
    $stmt = $conn->query("SELECT p.id, p.nombres, p.apellido_paterno, p.apellido_materno, c.categoria, r.fecha_registro, r.impreso FROM personas p JOIN categoria c ON p.id_categoria = c.id JOIN registro r ON p.id = r.id_persona ORDER BY r.fecha_registro DESC");
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener registros: " . $e->getMessage());
    die("Error al cargar registros.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Registros</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Reporte de Registros</h1>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Categoría</th>
                <th>Fecha de Registro</th>
                <th>Impreso</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['nombres']) ?></td>
                    <td><?= htmlspecialchars($r['apellido_paterno']) ?></td>
                    <td><?= htmlspecialchars($r['apellido_materno']) ?></td>
                    <td><?= htmlspecialchars($r['categoria']) ?></td>
                    <td><?= htmlspecialchars($r['fecha_registro']) ?></td>
                    <td><?= $r['impreso'] ? 'Sí' : 'No' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?= APP_URL ?>admin/reportes.php" class="btn">Volver a Reportes</a>
</body>
</html>
<?php
// listado_eliminados.php

define('ABSPATH', true);
require_once 'includes/init.php';
require_once 'includes/auth.php';

if ($_SESSION['user_role'] != 1) {
    header('Location: acceso_no_permitido.php');
    exit();
}

try {
    $stmt = $conn->query("SELECT p.id, p.nombres, p.apellido_paterno, p.apellido_materno, c.categoria, p.email, p.telefono, p.empresa, p.cargo FROM personas p JOIN categoria c ON p.id_categoria = c.id WHERE p.estado = 0 ORDER BY p.fecha_ingreso DESC");
    $personas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al cargar listado: " . $e->getMessage());
    $personas = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Personas Eliminadas</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Personas Eliminadas Lógicamente</h1>

    <?php if (empty($personas)): ?>
        <p>No hay personas eliminadas.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Categoría</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Empresa</th>
                    <th>Cargo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($personas as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['nombres']) ?></td>
                        <td><?= htmlspecialchars($p['apellido_paterno']) ?></td>
                        <td><?= htmlspecialchars($p['apellido_materno']) ?></td>
                        <td><?= htmlspecialchars($p['categoria']) ?></td>
                        <td><?= htmlspecialchars($p['email']) ?></td>
                        <td><?= htmlspecialchars($p['telefono']) ?></td>
                        <td><?= htmlspecialchars($p['empresa']) ?></td>
                        <td><?= htmlspecialchars($p['cargo']) ?></td>
                        <td>
                            <a href="<?= APP_URL ?>recuperar_persona.php?id=<?= $p['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de recuperar esta persona?')">Recuperar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="<?= APP_URL ?>panel.php" class="btn">Volver al Panel</a>
</body>
</html>
<?php
// admin/usuarios.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';

if (!isAdmin()) {
    header('Location: ../acceso_no_permitido.php');
    exit();
}

try {
    $stmt = $conn->query("SELECT u.id, u.user_name, n.des_nivel_acceso, u.email FROM usuarios u JOIN nivel_acceso n ON u.id_nivel_acceso = n.id ORDER BY u.id DESC");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener usuarios: " . $e->getMessage());
    die("Error al cargar usuarios.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios del Sistema</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Usuarios del Sistema</h1>

    <a href="<?= APP_URL ?>admin/crear_usuario.php" class="btn">Nuevo Usuario</a>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['user_name']) ?></td>
                    <td><?= htmlspecialchars($u['des_nivel_acceso']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>admin/editar_usuario.php?id=<?= $u['id'] ?>" class="btn">Editar</a>
                        <a href="<?= APP_URL ?>admin/eliminar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?= APP_URL ?>panel.php" class="btn">Volver al Panel</a>
</body>
</html>
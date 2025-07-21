<?php
// admin/editar_usuario.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';

if (!isAdmin()) {
    header('Location: ../acceso_no_permitido.php');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID inválido.");
}

try {
    $stmt = $conn->prepare("SELECT user_name, email, id_nivel_acceso FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Usuario no encontrado.");
    }
} catch (PDOException $e) {
    error_log("Error al obtener usuario: " . $e->getMessage());
    die("Error al cargar usuario.");
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $nivel_acceso = isset($_POST['id_nivel_acceso']) ? intval($_POST['id_nivel_acceso']) : 0;

    if (empty($username) || empty($email) || empty($nivel_acceso)) {
        $response = ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['success' => false, 'message' => 'Correo electrónico inválido.'];
    } else {
        try {
            $stmt = $conn->prepare("UPDATE usuarios SET user_name = ?, email = ?, id_nivel_acceso = ? WHERE id = ?");
            $stmt->execute([$username, $email, $nivel_acceso, $id]);

            $response = ['success' => true, 'message' => 'Usuario actualizado correctamente.'];
        } catch (PDOException $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al actualizar usuario.'];
        }
    }
}

// Cargar niveles de acceso
try {
    $stmt_nivel = $conn->query("SELECT id, des_nivel_acceso FROM nivel_acceso ORDER BY id");
    $niveles = $stmt_nivel->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar niveles de acceso.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Editar Usuario: <?= htmlspecialchars($usuario['user_name']) ?></h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre de Usuario *</label>
        <input type="text" name="user_name" value="<?= htmlspecialchars($usuario['user_name']) ?>" required>

        <label>Email *</label>
        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

        <label>Nivel de Acceso *</label>
        <select name="id_nivel_acceso" required>
            <?php foreach ($niveles as $nivel): ?>
                <option value="<?= $nivel['id'] ?>" <?= $usuario['id_nivel_acceso'] == $nivel['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($nivel['des_nivel_acceso']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Actualizar Usuario</button>
    </form>

    <a href="<?= APP_URL ?>admin/usuarios.php" class="btn">Volver a Usuarios</a>
</body>
</html>
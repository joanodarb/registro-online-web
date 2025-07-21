<?php
// admin/crear_usuario.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';

if (!isAdmin()) {
    header('Location: ../acceso_no_permitido.php');
    exit();
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $nivel_acceso = isset($_POST['id_nivel_acceso']) ? intval($_POST['id_nivel_acceso']) : 0;

    if (empty($username) || empty($password) || empty($email) || empty($nivel_acceso)) {
        $response = ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['success' => false, 'message' => 'Correo electrónico inválido.'];
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO usuarios (user_name, password, email, id_nivel_acceso) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $email, $nivel_acceso]);

            $response = ['success' => true, 'message' => 'Usuario creado correctamente.'];
        } catch (PDOException $e) {
            error_log("Error al crear usuario: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al crear el usuario.'];
        }
    }
}

// Obtener niveles de acceso
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
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Crear Nuevo Usuario</h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre de Usuario *</label>
        <input type="text" name="user_name" required>

        <label>Contraseña *</label>
        <input type="password" name="password" required>

        <label>Email *</label>
        <input type="email" name="email" required>

        <label>Nivel de Acceso *</label>
        <select name="id_nivel_acceso" required>
            <?php foreach ($niveles as $nivel): ?>
                <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['des_nivel_acceso']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Crear Usuario</button>
    </form>

    <a href="<?= APP_URL ?>admin/usuarios.php" class="btn">Volver a Usuarios</a>
</body>
</html>
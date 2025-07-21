<?php
// reset_password.php

define('ABSPATH', true);
require_once 'includes/init.php';

$response = [];

$token = isset($_GET['token']) ? trim($_GET['token']) : '';

if (empty($token)) {
    die("Token inválido.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirm = isset($_POST['confirm']) ? trim($_POST['confirm']) : '';

    if (empty($password) || empty($confirm)) {
        $response = ['success' => false, 'message' => 'Contraseña y confirmación son obligatorias.'];
    } elseif ($password !== $confirm) {
        $response = ['success' => false, 'message' => 'Las contraseñas no coinciden.'];
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE reset_token = ? AND reset_expires > NOW()");
            $stmt->execute([$token]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $response = ['success' => false, 'message' => 'Token inválido o expirado.'];
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
                $stmt->execute([$hashedPassword, $user['id']]);

                $response = ['success' => true, 'message' => 'Contraseña actualizada correctamente.'];
            }
        } catch (PDOException $e) {
            error_log("Error al restablecer contraseña: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Ha ocurrido un error.'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Restablecer Contraseña</h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nueva Contraseña:</label>
        <input type="password" name="password" required>

        <label>Confirmar Contraseña:</label>
        <input type="password" name="confirm" required>

        <button type="submit">Guardar Contraseña</button>
    </form>

    <a href="<?= APP_URL ?>login.php" class="btn">Volver al Inicio de Sesión</a>
</body>
</html>
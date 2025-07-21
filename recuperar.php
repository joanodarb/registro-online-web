<?php
// recuperar.php

define('ABSPATH', true);
require_once 'includes/init.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['success' => false, 'message' => 'Correo electrónico inválido.'];
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $response = ['success' => false, 'message' => 'Correo no encontrado.'];
            } else {
                $token = bin2hex(random_bytes(50));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Guardar token en base de datos
                $stmt = $conn->prepare("UPDATE usuarios SET reset_token = ?, reset_expires = ? WHERE id = ?");
                $stmt->execute([$token, $expires, $user['id']]);

                // Generar enlace de recuperación
                $resetLink = APP_URL . "reset_password.php?token=" . $token;

                // Simular envío de correo
                // En producción, usa PHPMailer o similar
                $subject = "Recuperación de Contraseña";
                $message = "Haz clic en el siguiente enlace para restablecer tu contraseña:\n\n" . $resetLink;

                // Enviar correo (ejemplo básico)
                // mail($email, $subject, $message);

                $response = ['success' => true, 'message' => 'Se ha enviado un enlace de recuperación a tu correo.'];
            }
        } catch (PDOException $e) {
            error_log("Error al iniciar recuperación: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Ha ocurrido un error.'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Recuperar Contraseña</h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Correo Electrónico:</label>
        <input type="email" name="email" required>
        <button type="submit">Enviar Enlace de Recuperación</button>
    </form>

    <a href="<?= APP_URL ?>login.php" class="btn">Volver al Inicio de Sesión</a>
</body>
</html>
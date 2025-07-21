<?php
// registro_usuario.php

define('ABSPATH', true);
require_once 'includes/init.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirm = isset($_POST['confirm']) ? trim($_POST['confirm']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $nivel_acceso = isset($_POST['id_nivel_acceso']) ? intval($_POST['id_nivel_acceso']) : 0;

    if (empty($username) || empty($password) || empty($email) || empty($nivel_acceso)) {
        $response = ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
    } elseif ($password !== $confirm) {
        $response = ['success' => false, 'message' => 'Las contraseñas no coinciden.'];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['success' => false, 'message' => 'Correo electrónico inválido.'];
    } else {
        try {
            // Verificar si el correo ya existe
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $response = ['success' => false, 'message' => 'El correo ya está registrado.'];
            } else {
                // Insertar nuevo usuario
                $stmt = $conn->prepare("INSERT INTO usuarios (user_name, password, email, id_nivel_acceso) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $email, $nivel_acceso]);

                $response = ['success' => true, 'message' => 'Usuario registrado correctamente.'];
            }
        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al registrar usuario.'];
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
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Registrar Nuevo Usuario</h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre de Usuario:</label>
        <input type="text" name="user_name" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <label>Confirmar Contraseña:</label>
        <input type="password" name="confirm" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Nivel de Acceso:</label>
        <select name="id_nivel_acceso" required>
            <?php foreach ($niveles as $nivel): ?>
                <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['des_nivel_acceso']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Registrar</button>
    </form>

    <a href="<?= APP_URL ?>login.php" class="btn">¿Ya tienes cuenta? Inicia Sesión</a>
</body>
</html>
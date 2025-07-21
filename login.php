<?php
define('ABSPATH', true);
require_once 'includes/init.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username) || empty($password)) {
        $response = ['success' => false, 'message' => 'Usuario y contraseña son obligatorios.'];
    } else {
        try {
            $stmt = $conn->prepare("SELECT id, user_name, password, id_nivel_acceso FROM usuarios WHERE user_name = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_role'] = $user['id_nivel_acceso'];

                header("Location: " . APP_URL . "panel.php");
                exit();
            } else {
                $response = ['success' => false, 'message' => 'Credenciales inválidas.'];
            }
        } catch (PDOException $e) {
            error_log("Error al iniciar sesión: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al iniciar sesión.'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Iniciar Sesión</h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Usuario:</label>
        <input type="text" name="username" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>
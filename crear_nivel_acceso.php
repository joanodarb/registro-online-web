<?php
// admin/crear_nivel_acceso.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';

if (!isAdmin()) {
    header('Location: ../acceso_no_permitido.php');
    exit();
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nivel_acceso = isset($_POST['des_nivel_acceso']) ? trim($_POST['des_nivel_acceso']) : '';

    if (empty($nivel_acceso)) {
        $response = ['success' => false, 'message' => 'El nombre del nivel de acceso es obligatorio.'];
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO nivel_acceso (des_nivel_acceso) VALUES (?)");
            $stmt->execute([$nivel_acceso]);

            $response = ['success' => true, 'message' => 'Nivel de acceso creado correctamente.'];
        } catch (PDOException $e) {
            error_log("Error al crear nivel: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al crear el nivel.'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nivel de Acceso</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Crear Nivel de Acceso</h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre del Nivel de Acceso:</label>
        <input type="text" name="des_nivel_acceso" required>
        <button type="submit">Crear Nivel</button>
    </form>

    <a href="<?= APP_URL ?>admin/usuarios.php" class="btn">Volver a Usuarios</a>
</body>
</html>
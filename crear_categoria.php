<?php
// admin/crear_categoria.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';

if (!isAdmin()) {
    header('Location: ../acceso_no_permitido.php');
    exit();
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';

    if (empty($categoria)) {
        $response = ['success' => false, 'message' => 'El nombre de la categoría es obligatorio.'];
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO categoria (categoria) VALUES (?)");
            $stmt->execute([$categoria]);

            $response = ['success' => true, 'message' => 'Categoría creada correctamente.'];
        } catch (PDOException $e) {
            error_log("Error al crear categoría: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al crear la categoría.'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Categoría</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Crear Nueva Categoría</h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre de la Categoría:</label>
        <input type="text" name="categoria" required>
        <button type="submit">Crear Categoría</button>
    </form>

    <a href="<?= APP_URL ?>admin/admin_plantillas.php" class="btn">Volver a Plantillas</a>
</body>
</html>
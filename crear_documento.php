<?php
// admin/crear_documento.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';

if (!isAdmin()) {
    header('Location: ../acceso_no_permitido.php');
    exit();
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = isset($_POST['des_documento']) ? trim($_POST['des_documento']) : '';

    if (empty($documento)) {
        $response = ['success' => false, 'message' => 'El nombre del documento es obligatorio.'];
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO documento (des_documento) VALUES (?)");
            $stmt->execute([$documento]);

            $response = ['success' => true, 'message' => 'Documento creado correctamente.'];
        } catch (PDOException $e) {
            error_log("Error al crear documento: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al crear el documento.'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Documento</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Crear Nuevo Tipo de Documento</h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre del Documento:</label>
        <input type="text" name="des_documento" required>
        <button type="submit">Crear Documento</button>
    </form>

    <a href="<?= APP_URL ?>admin/admin_plantillas.php" class="btn">Volver a Plantillas</a>
</body>
</html>
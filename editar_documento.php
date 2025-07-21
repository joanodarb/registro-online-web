<?php
// admin/editar_documento.php

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
    $stmt = $conn->prepare("SELECT des_documento FROM documento WHERE id = ?");
    $stmt->execute([$id]);
    $documento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$documento) {
        die("Documento no encontrado.");
    }
} catch (PDOException $e) {
    error_log("Error al obtener documento: " . $e->getMessage());
    die("Error al cargar documento.");
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_documento = isset($_POST['des_documento']) ? trim($_POST['des_documento']) : '';

    if (empty($nombre_documento)) {
        $response = ['success' => false, 'message' => 'El nombre del documento es obligatorio.'];
    } else {
        try {
            $stmt = $conn->prepare("UPDATE documento SET des_documento = ? WHERE id = ?");
            $stmt->execute([$nombre_documento, $id]);

            $response = ['success' => true, 'message' => 'Documento actualizado correctamente.'];
        } catch (PDOException $e) {
            error_log("Error al actualizar documento: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al actualizar el documento.'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Documento</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Editar Tipo de Documento</h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre del Documento:</label>
        <input type="text" name="des_documento" value="<?= htmlspecialchars($documento['des_documento']) ?>" required>
        <button type="submit">Actualizar Documento</button>
    </form>

    <a href="<?= APP_URL ?>admin/admin_plantillas.php" class="btn">Volver a Plantillas</a>
</body>
</html>
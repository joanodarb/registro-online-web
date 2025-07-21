<?php
// admin/editar_categoria.php

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
    $stmt = $conn->prepare("SELECT categoria FROM categoria WHERE id = ?");
    $stmt->execute([$id]);
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$categoria) {
        die("Categoría no encontrada.");
    }
} catch (PDOException $e) {
    error_log("Error al obtener categoría: " . $e->getMessage());
    die("Error al cargar categoría.");
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';

    if (empty($nombre_categoria)) {
        $response = ['success' => false, 'message' => 'El nombre de la categoría es obligatorio.'];
    } else {
        try {
            $stmt = $conn->prepare("UPDATE categoria SET categoria = ? WHERE id = ?");
            $stmt->execute([$nombre_categoria, $id]);

            $response = ['success' => true, 'message' => 'Categoría actualizada correctamente.'];
        } catch (PDOException $e) {
            error_log("Error al actualizar categoría: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al actualizar la categoría.'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Editar Categoría: <?= htmlspecialchars($categoria['categoria']) ?></h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre de la Categoría:</label>
        <input type="text" name="categoria" value="<?= htmlspecialchars($categoria['categoria']) ?>" required>
        <button type="submit">Actualizar Categoría</button>
    </form>

    <a href="<?= APP_URL ?>admin/admin_plantillas.php" class="btn">Volver a Plantillas</a>
</body>
</html>
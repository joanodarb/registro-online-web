<?php
// admin/editar_nivel_acceso.php

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
    $stmt = $conn->prepare("SELECT des_nivel_acceso FROM nivel_acceso WHERE id = ?");
    $stmt->execute([$id]);
    $nivel = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$nivel) {
        die("Nivel no encontrado.");
    }
} catch (PDOException $e) {
    error_log("Error al obtener nivel: " . $e->getMessage());
    die("Error al cargar nivel.");
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_nivel = isset($_POST['des_nivel_acceso']) ? trim($_POST['des_nivel_acceso']) : '';

    if (empty($nombre_nivel)) {
        $response = ['success' => false, 'message' => 'El nombre del nivel es obligatorio.'];
    } else {
        try {
            $stmt = $conn->prepare("UPDATE nivel_acceso SET des_nivel_acceso = ? WHERE id = ?");
            $stmt->execute([$nombre_nivel, $id]);

            $response = ['success' => true, 'message' => 'Nivel actualizado correctamente.'];
        } catch (PDOException $e) {
            error_log("Error al actualizar nivel: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al actualizar el nivel.'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Nivel de Acceso</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Editar Nivel de Acceso</h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre del Nivel de Acceso:</label>
        <input type="text" name="des_nivel_acceso" value="<?= htmlspecialchars($nivel['des_nivel_acceso']) ?>" required>
        <button type="submit">Actualizar Nivel</button>
    </form>

    <a href="<?= APP_URL ?>admin/usuarios.php" class="btn">Volver a Usuarios</a>
</body>
</html>
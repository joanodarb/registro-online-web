<?php
// carga_masiva.php

define('ABSPATH', true);
require_once 'includes/init.php';
require_once 'includes/auth.php';

if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 2) {
    header('Location: acceso_no_permitido.php');
    exit();
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    $file = $_FILES['archivo'];

    // Validar archivo
    $allowedExtensions = ['csv'];
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        $response = ['success' => false, 'message' => 'Solo se permiten archivos CSV.'];
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $response = ['success' => false, 'message' => 'Error al subir el archivo.'];
    } else {
        $uploadDir = __DIR__ . '/uploads/';
        $newFileName = 'carga_' . date('YmdHis') . '.csv';
        $uploadFile = $uploadDir . $newFileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            $handle = fopen($uploadFile, 'r');
            $headers = fgetcsv($handle);

            $count = 0;
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($data) < 10) continue;

                try {
                    $stmt = $conn->prepare("INSERT INTO personas (id_categoria, id_documento, nro_documento, apellido_paterno, apellido_materno, nombres, pais, empresa, cargo, telefono, email, data01, data02, data03, data04, data05, fecha_ingreso) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                    $stmt->execute(array_slice($data, 0, 16));
                    $count++;
                } catch (PDOException $e) {
                    error_log("Error al insertar fila: " . $e->getMessage());
                }
            }

            fclose($handle);
            $response = ['success' => true, 'message' => "$count registros insertados."];
        } else {
            $response = ['success' => false, 'message' => 'No se pudo mover el archivo.'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carga Masiva</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Carga Masiva de Registros</h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="archivo" required>
        <button type="submit">Subir CSV</button>
    </form>

    <a href="<?= APP_URL ?>panel.php" class="btn">Volver al Panel</a>
</body>
</html>
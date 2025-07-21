<?php
// admin/editar_plantilla.php

define('ABSPATH', true);
require_once '../includes/init.php';
require_once '../includes/auth.php';

if (!isAdmin()) {
    header('Location: ../acceso_no_permitido.php');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID de plantilla inválido.");
}

try {
    $stmt = $conn->prepare("SELECT id, nombre, configuracion, fecha_creacion, fecha_actualizacion FROM plantillas_impresion WHERE id = ?");
    $stmt->execute([$id]);
    $plantilla = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$plantilla) {
        die("Plantilla no encontrada.");
    }
} catch (PDOException $e) {
    error_log("Error al obtener plantilla: " . $e->getMessage());
    die("Ha ocurrido un error al cargar la plantilla.");
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $configuracion = isset($_POST['configuracion']) ? trim($_POST['configuracion']) : '';

    if (empty($nombre) || empty($configuracion)) {
        $response = ['success' => false, 'message' => 'Nombre y configuración son obligatorios.'];
    } else {
        try {
            $stmt = $conn->prepare("UPDATE plantillas_impresion SET nombre = ?, configuracion = ?, fecha_actualizacion = NOW() WHERE id = ?");
            $stmt->execute([$nombre, $configuracion, $id]);

            $response = ['success' => true, 'message' => 'Plantilla actualizada correctamente.'];
        } catch (PDOException $e) {
            error_log("Error al actualizar plantilla: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al actualizar la plantilla.'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Plantilla</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
    <style>
        .editor, .preview {
            flex: 1;
            min-width: 400px;
        }
        .editor textarea {
            width: 100%;
            height: 400px;
            font-family: monospace;
        }
        .preview-frame {
            width: 100%;
            height: 500px;
            border: 1px solid #ccc;
            background: #fff;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Editar Plantilla: <?= htmlspecialchars($plantilla['nombre']) ?></h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($plantilla['nombre']) ?>" required>

        <label>Configuración (HTML/CSS):</label>
        <textarea id="configuracion" name="configuracion" oninput="updatePreview()" required><?= htmlspecialchars($plantilla['configuracion']) ?></textarea>

        <button type="submit">Actualizar Plantilla</button>
    </form>

    <div class="preview">
        <h3>Previsualización</h3>
        <iframe id="previewFrame" class="preview-frame"></iframe>
    </div>

    <a href="<?= APP_URL ?>admin/admin_plantillas.php" class="btn">Volver a Plantillas</a>

    <script>
        function updatePreview() {
            const content = document.getElementById('configuracion').value;
            const iframe = document.getElementById('previewFrame');
            const exampleData = {
                nombres: "Juan",
                apellido_paterno: "Pérez",
                apellido_materno: "García",
                email: "juan@ejemplo.com",
                empresa: "Empresa S.A.",
                cargo: "Gerente",
                categoria: "Staff"
            };

            let rendered = content;
            for (const [key, value] of Object.entries(exampleData)) {
                rendered = rendered.replace(new RegExp("{{" + key + "}}", "g"), value);
            }

            iframe.contentDocument.open();
            iframe.contentDocument.write("<style>body { font-family: Arial; }</style>");
            iframe.contentDocument.write(rendered);
            iframe.contentDocument.close();
        }

        window.onload = updatePreview;
    </script>
</body>
</html>
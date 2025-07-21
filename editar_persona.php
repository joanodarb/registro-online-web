<?php
// editar_persona.php

define('ABSPATH', true);
require_once 'includes/init.php';
require_once 'includes/auth.php';

if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 2) {
    header('Location: acceso_no_permitido.php');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID inválido.");
}

try {
    $stmt = $conn->prepare("SELECT p.id, p.id_categoria, p.id_documento, p.nro_documento, p.apellido_paterno, p.apellido_materno, p.nombres, p.pais, p.empresa, p.cargo, p.telefono, p.email, p.data01, p.data02, p.data03, p.data04, p.data05, p.estado FROM personas p WHERE p.id = ?");
    $stmt->execute([$id]);
    $persona = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$persona) {
        die("Persona no encontrada.");
    }
} catch (PDOException $e) {
    error_log("Error al obtener persona: " . $e->getMessage());
    die("Error al cargar persona.");
}

// Cargar categorías y documentos
try {
    $stmt_categoria = $conn->query("SELECT id, categoria FROM categoria ORDER BY categoria");
    $categorias = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);

    $stmt_documento = $conn->query("SELECT id, des_documento FROM documento ORDER BY des_documento");
    $documentos = $stmt_documento->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al cargar datos: " . $e->getMessage());
    die("Error al cargar datos adicionales.");
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apellido_paterno = isset($_POST['apellido_paterno']) ? trim($_POST['apellido_paterno']) : '';
    $apellido_materno = isset($_POST['apellido_materno']) ? trim($_POST['apellido_materno']) : '';
    $nombres = isset($_POST['nombres']) ? trim($_POST['nombres']) : '';
    $pais = isset($_POST['pais']) ? trim($_POST['pais']) : '';
    $empresa = isset($_POST['empresa']) ? trim($_POST['empresa']) : '';
    $cargo = isset($_POST['cargo']) ? trim($_POST['cargo']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $data01 = isset($_POST['data01']) ? trim($_POST['data01']) : '';
    $data02 = isset($_POST['data02']) ? trim($_POST['data02']) : '';
    $data03 = isset($_POST['data03']) ? trim($_POST['data03']) : '';
    $data04 = isset($_POST['data04']) ? trim($_POST['data04']) : '';
    $data05 = isset($_POST['data05']) ? trim($_POST['data05']) : '';
    $id_categoria = isset($_POST['id_categoria']) ? intval($_POST['id_categoria']) : 0;
    $id_documento = isset($_POST['id_documento']) ? intval($_POST['id_documento']) : 0;
    $nro_documento = isset($_POST['nro_documento']) ? trim($_POST['nro_documento']) : '';
    $estado = isset($_POST['estado']) ? intval($_POST['estado']) : 1;

    if (empty($apellido_paterno) || empty($apellido_materno) || empty($nombres)) {
        $response = ['success' => false, 'message' => 'Apellido paterno, materno y nombre son obligatorios.'];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['success' => false, 'message' => 'Correo electrónico inválido.'];
    } else {
        try {
            $stmt = $conn->prepare("UPDATE personas SET id_categoria = ?, id_documento = ?, nro_documento = ?, apellido_paterno = ?, apellido_materno = ?, nombres = ?, pais = ?, empresa = ?, cargo = ?, telefono = ?, email = ?, data01 = ?, data02 = ?, data03 = ?, data04 = ?, data05 = ?, estado = ? WHERE id = ?");
            $stmt->execute([
                $id_categoria,
                $id_documento,
                $nro_documento,
                $apellido_paterno,
                $apellido_materno,
                $nombres,
                $pais,
                $empresa,
                $cargo,
                $telefono,
                $email,
                $data01,
                $data02,
                $data03,
                $data04,
                $data05,
                $estado,
                $id
            ]);

            $response = ['success' => true, 'message' => 'Persona actualizada correctamente.'];
        } catch (PDOException $e) {
            error_log("Error al actualizar persona: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al actualizar la persona.'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Persona</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Editar Persona: <?= htmlspecialchars($persona['nombres']) ?></h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Categoría:</label>
        <select name="id_categoria" required>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $persona['id_categoria'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['categoria']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Tipo de Documento:</label>
        <select name="id_documento" required>
            <?php foreach ($documentos as $doc): ?>
                <option value="<?= $doc['id'] ?>" <?= $persona['id_documento'] == $doc['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($doc['des_documento']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Número de Documento:</label>
        <input type="text" name="nro_documento" value="<?= htmlspecialchars($persona['nro_documento']) ?>" required>

        <label>Apellido Paterno:</label>
        <input type="text" name="apellido_paterno" value="<?= htmlspecialchars($persona['apellido_paterno']) ?>" required>

        <label>Apellido Materno:</label>
        <input type="text" name="apellido_materno" value="<?= htmlspecialchars($persona['apellido_materno']) ?>" required>

        <label>Nombre(s):</label>
        <input type="text" name="nombres" value="<?= htmlspecialchars($persona['nombres']) ?>" required>

        <label>Pais:</label>
        <input type="text" name="pais" value="<?= htmlspecialchars($persona['pais']) ?>">

        <label>Empresa:</label>
        <input type="text" name="empresa" value="<?= htmlspecialchars($persona['empresa']) ?>">

        <label>Cargo:</label>
        <input type="text" name="cargo" value="<?= htmlspecialchars($persona['cargo']) ?>">

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= htmlspecialchars($persona['telefono']) ?>">

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($persona['email']) ?>">

        <label>Dato Extra 1:</label>
        <input type="text" name="data01" value="<?= htmlspecialchars($persona['data01']) ?>">

        <label>Dato Extra 2:</label>
        <input type="text" name="data02" value="<?= htmlspecialchars($persona['data02']) ?>">

        <label>Dato Extra 3:</label>
        <input type="text" name="data03" value="<?= htmlspecialchars($persona['data03']) ?>">

        <label>Dato Extra 4:</label>
        <input type="text" name="data04" value="<?= htmlspecialchars($persona['data04']) ?>">

        <label>Dato Extra 5:</label>
        <input type="text" name="data05" value="<?= htmlspecialchars($persona['data05']) ?>">

        <label>Estado:</label>
        <select name="estado">
            <option value="1" <?= $persona['estado'] == 1 ? 'selected' : '' ?>>Activo</option>
            <option value="0" <?= $persona['estado'] == 0 ? 'selected' : '' ?>>Eliminado</option>
        </select>

        <button type="submit">Actualizar Persona</button>
    </form>

    <a href="<?= APP_URL ?>panel.php" class="btn">Volver al Panel</a>
</body>
</html>
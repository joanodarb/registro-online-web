<?php
// registro.php

define('ABSPATH', true);
require_once 'includes/init.php';
require_once 'includes/auth.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Datos de persona
    $id_categoria = isset($_POST['id_categoria']) ? intval($_POST['id_categoria']) : 0;
    $id_documento = isset($_POST['id_documento']) ? intval($_POST['id_documento']) : 0;
    $nro_documento = isset($_POST['nro_documento']) ? trim($_POST['nro_documento']) : '';
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

    // Validación de campos obligatorios
    if (empty($id_categoria) || empty($id_documento) || empty($nro_documento) || empty($apellido_paterno) || empty($apellido_materno) || empty($nombres)) {
        $response = ['success' => false, 'message' => 'Todos los campos marcados con * son obligatorios.'];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['success' => false, 'message' => 'Correo electrónico inválido.'];
    } else {
        try {
            // Insertar persona
            $stmt = $conn->prepare("INSERT INTO personas (id_categoria, id_documento, nro_documento, apellido_paterno, apellido_materno, nombres, pais, empresa, cargo, telefono, email, data01, data02, data03, data04, data05, fecha_ingreso) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
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
                $data05
            ]);

            $id_persona = $conn->lastInsertId();

            // Insertar registro
            $stmt_registro = $conn->prepare("INSERT INTO registro (id_persona, fecha_registro, impreso, id_plantilla) VALUES (?, NOW(), 0, NULL)");
            $stmt_registro->execute([$id_persona]);

            $id_registro = $conn->lastInsertId();

            // Insertar acceso
            $stmt_acceso = $conn->prepare("INSERT INTO acceso (id_registro, fecha_acceso, usuario) VALUES (?, NOW(), ?)");
            $stmt_acceso->execute([$id_registro, $_SESSION['user_id']]);

            $response = ['success' => true, 'message' => 'Persona y registro creados correctamente.'];
        } catch (PDOException $e) {
            error_log("Error al registrar: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error al registrar.'];
        }
    }
}

// Obtener categorías y documentos
try {
    $stmt_categoria = $conn->query("SELECT id, categoria FROM categoria ORDER BY categoria");
    $categorias = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);

    $stmt_documento = $conn->query("SELECT id, des_documento FROM documento ORDER BY des_documento");
    $documentos = $stmt_documento->fetchAll(PDO::FETCH_ASSOC);

	// Obtener último registro para mostrar grilla
    $stmt_grilla = $conn->prepare("SELECT p.id, p.nombres, p.apellido_paterno, p.apellido_materno, c.categoria, d.des_documento, p.nro_documento, p.email, p.telefono, p.empresa, p.cargo, p.pais, p.data01, p.data02, p.data03, p.data04, p.data05, p.fecha_ingreso, r.fecha_registro, r.impreso FROM personas p JOIN categoria c ON p.id_categoria = c.id JOIN documento d ON p.id_documento = d.id JOIN registro r ON p.id = r.id_persona WHERE p.estado = 1 ORDER BY r.fecha_registro DESC LIMIT 100");
    $stmt_grilla->execute();
    $personas = $stmt_grilla->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener datos: " . $e->getMessage());
    $response = ['success' => false, 'message' => 'No se pudieron cargar las categorías y documentos.'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Persona</title>
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
</head>
<body>
    <h1>Registrar Persona</h1>

    <?php if (!empty($response)): ?>
        <div class="alert <?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['message'] ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Categoría *</label>
        <select name="id_categoria" required>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['categoria']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Tipo de Documento *</label>
        <select name="id_documento" required>
            <?php foreach ($documentos as $doc): ?>
                <option value="<?= $doc['id'] ?>"><?= htmlspecialchars($doc['des_documento']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Número de Documento *</label>
        <input type="text" name="nro_documento" required>

        <label>Apellido Paterno *</label>
        <input type="text" name="apellido_paterno" required>

        <label>Apellido Materno *</label>
        <input type="text" name="apellido_materno" required>

        <label>Nombre(s) *</label>
        <input type="text" name="nombres" required>

        <label>Pais</label>
        <input type="text" name="pais">

        <label>Empresa</label>
        <input type="text" name="empresa">

        <label>Cargo</label>
        <input type="text" name="cargo">

        <label>Teléfono</label>
        <input type="text" name="telefono">

        <label>Email</label>
        <input type="email" name="email">

        <label>Dato Extra 1</label>
        <input type="text" name="data01">

        <label>Dato Extra 2</label>
        <input type="text" name="data02">

        <label>Dato Extra 3</label>
        <input type="text" name="data03">

        <label>Dato Extra 4</label>
        <input type="text" name="data04">

        <label>Dato Extra 5</label>
        <input type="text" name="data05">

        <button type="submit">Registrar</button>
    </form>

    <h2>Últimos Registros</h2>
    <div class="grilla">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Categoría</th>
                    <th>Documento</th>
                    <th>Número</th>
                    <th>Empresa</th>
                    <th>Cargo</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Fecha Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($personas as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['nombres']) ?></td>
                        <td><?= htmlspecialchars($p['apellido_paterno']) ?></td>
                        <td><?= htmlspecialchars($p['apellido_materno']) ?></td>
                        <td><?= htmlspecialchars($p['categoria']) ?></td>
                        <td><?= htmlspecialchars($p['des_documento']) ?></td>
                        <td><?= htmlspecialchars($p['nro_documento']) ?></td>
                        <td><?= htmlspecialchars($p['empresa']) ?></td>
                        <td><?= htmlspecialchars($p['cargo']) ?></td>
                        <td><?= htmlspecialchars($p['email']) ?></td>
                        <td><?= htmlspecialchars($p['telefono']) ?></td>
                        <td><?= htmlspecialchars($p['fecha_registro']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <a href="<?= APP_URL ?>panel.php" class="btn">Volver al Panel</a>
</body>
</html>
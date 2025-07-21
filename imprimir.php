<?php
// imprimir.php

define('ABSPATH', true);
require_once 'includes/init.php';
require_once 'includes/auth.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID inválido.");
}

try {
    $stmt = $conn->prepare("SELECT p.id, p.nombres, p.apellido_paterno, p.apellido_materno, p.email, p.telefono, p.empresa, p.cargo, c.categoria, d.des_documento, p.nro_documento, p.pais, p.data01, p.data02, p.data03, p.data04, p.data05 FROM personas p JOIN categoria c ON p.id_categoria = c.id JOIN documento d ON p.id_documento = d.id WHERE p.id = ?");
    $stmt->execute([$id]);
    $persona = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$persona) {
        die("Persona no encontrada.");
    }
} catch (PDOException $e) {
    error_log("Error al obtener persona: " . $e->getMessage());
    die("Error al cargar persona.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Imprimir Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .print-button {
            display: block;
            margin: 20px 0;
            padding: 10px 20px;
            background: #007BFF;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
        .print-button:hover {
            background: #0056b3;
        }
        @media print {
            .print-button { display: none; }
        }
    </style>
</head>
<body>
    <h1>Registro de Persona</h1>
    <p><strong>Nombre(s):</strong> <?= htmlspecialchars($persona['nombres']) ?></p>
    <p><strong>Apellido Paterno:</strong> <?= htmlspecialchars($persona['apellido_paterno']) ?></p>
    <p><strong>Apellido Materno:</strong> <?= htmlspecialchars($persona['apellido_materno']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($persona['email']) ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($persona['telefono']) ?></p>
    <p><strong>Empresa:</strong> <?= htmlspecialchars($persona['empresa']) ?></p>
    <p><strong>Cargo:</strong> <?= htmlspecialchars($persona['cargo']) ?></p>
    <p><strong>Categoría:</strong> <?= htmlspecialchars($persona['categoria']) ?></p>
    <p><strong>Documento:</strong> <?= htmlspecialchars($persona['des_documento']) ?>: <?= htmlspecialchars($persona['nro_documento']) ?></p>
    <p><strong>País:</strong> <?= htmlspecialchars($persona['pais']) ?></p>
    <p><strong>Dato Extra 1:</strong> <?= htmlspecialchars($persona['data01']) ?></p>
    <p><strong>Dato Extra 2:</strong> <?= htmlspecialchars($persona['data02']) ?></p>
    <p><strong>Dato Extra 3:</strong> <?= htmlspecialchars($persona['data03']) ?></p>
    <p><strong>Dato Extra 4:</strong> <?= htmlspecialchars($persona['data04']) ?></p>
    <p><strong>Dato Extra 5:</strong> <?= htmlspecialchars($persona['data05']) ?></p>

    <a href="#" class="print-button" onclick="window.print()">Imprimir</a>
</body>
</html>
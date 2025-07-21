<?php
// recuperar_persona.php

define('ABSPATH', true);
require_once 'includes/init.php';
require_once 'includes/auth.php';

if ($_SESSION['user_role'] != 1) {
    header('Location: acceso_no_permitido.php');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('Location: panel.php?error=ID+inválido');
    exit();
}

try {
    // Recuperar persona
    $stmt = $conn->prepare("UPDATE personas SET estado = 1 WHERE id = ?");
    $stmt->execute([$id]);

    // Registrar en logs
    $stmt_log = $conn->prepare("INSERT INTO logs (id_registro, accion, detalle, fecha) VALUES (?, 'recuperar', 'Persona recuperada', NOW())");
    $stmt_log->execute([$id]);

    header('Location: panel.php?msg=Persona+recuperada');
    exit();
} catch (PDOException $e) {
    error_log("Error al recuperar persona: " . $e->getMessage());
    header('Location: panel.php?error=No+se+pudo+recuperar');
    exit();
}
<?php
// eliminar_persona.php

define('ABSPATH', true);
require_once 'includes/init.php';
require_once 'includes/auth.php';

if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 2) {
    header('Location: acceso_no_permitido.php');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('Location: panel.php?error=ID+inválido');
    exit();
}

try {
    // Marcar como eliminado lógico
    $stmt = $conn->prepare("UPDATE personas SET estado = 0 WHERE id = ?");
    $stmt->execute([$id]);

    // Opcional: Registrar en tabla `logs` o `acceso`
    $stmt_log = $conn->prepare("INSERT INTO logs (id_registro, accion, detalle, fecha) VALUES (?, 'eliminar', 'Persona eliminada lógicamente', NOW())");
    $stmt_log->execute([$id]);

    header('Location: panel.php?msg=Persona+eliminada+lógicamente');
    exit();
} catch (PDOException $e) {
    error_log("Error al eliminar persona: " . $e->getMessage());
    header('Location: panel.php?error=No+se+pudo+eliminar');
    exit();
}
<?php
// admin/eliminar_usuario.php

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
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ../usuarios.php?msg=Usuario+eliminado');
    exit();
} catch (PDOException $e) {
    error_log("Error al eliminar usuario: " . $e->getMessage());
    header('Location: ../usuarios.php?error=No+se+pudo+eliminar');
    exit();
}
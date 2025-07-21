<?php
// admin/eliminar_nivel_acceso.php

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
    $stmt = $conn->prepare("DELETE FROM nivel_acceso WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ../usuarios.php?msg=Nivel+eliminado');
    exit();
} catch (PDOException $e) {
    error_log("Error al eliminar nivel: " . $e->getMessage());
    header('Location: ../usuarios.php?error=No+se+pudo+eliminar');
    exit();
}
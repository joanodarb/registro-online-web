<?php
// admin/eliminar_documento.php

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
    $stmt = $conn->prepare("DELETE FROM documento WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ../admin_plantillas.php?msg=Documento+eliminado');
    exit();
} catch (PDOException $e) {
    error_log("Error al eliminar documento: " . $e->getMessage());
    header('Location: ../admin_plantillas.php?error=No+se+pudo+eliminar');
    exit();
}
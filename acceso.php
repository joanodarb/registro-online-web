<?php
// acceso.php

define('ABSPATH', true);
require_once 'includes/init.php';

try {
    // Registrar acceso
    $stmt = $conn->prepare("INSERT INTO acceso (id_registro, fecha_acceso, usuario) VALUES (?, NOW(), ?)");
    $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
} catch (PDOException $e) {
    error_log("Error al registrar acceso: " . $e->getMessage());
    // No es necesario mostrar mensaje al usuario
}
?>
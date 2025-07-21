<?php
// includes/auth.php

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . APP_URL . 'login.php');
    exit();
}

// Opcional: Validar que el usuario exista en la base de datos
require_once 'database.php';

try {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header('Location: ' . APP_URL . 'login.php');
        exit();
    }
} catch (PDOException $e) {
    error_log("Error al verificar usuario: " . $e->getMessage());
    die("Ha ocurrido un error interno.");
}
?>
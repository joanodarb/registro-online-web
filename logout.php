<?php
define('ABSPATH', true);
require_once 'includes/init.php';

// Destruir todas las variables de sesión
$_SESSION = [];

// Eliminar cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destruir sesión
session_destroy();

// Redirigir a login
header('Location: ' . APP_URL . 'login.php');
exit();
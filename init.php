<?php
// includes/init.php

if (!defined('ABSPATH') || !ABSPATH) {
    exit('Acceso no permitido');
}

// Mostrar errores durante desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir rutas absolutas
define('BASE_PATH', dirname(__DIR__));
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('ASSETS_PATH', BASE_PATH . '/assets');

// Verificar archivos críticos
$required_files = [
    INCLUDES_PATH . '/config.php',
    INCLUDES_PATH . '/functions.php',
    INCLUDES_PATH . '/database.php'
];

foreach ($required_files as $file) {
    if (!file_exists($file)) {
        die("Archivo requerido no encontrado: " . htmlspecialchars($file));
    }
}

// Configuración de sesión segura
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

// Cargar configuración y funciones
try {
    require_once INCLUDES_PATH . '/config.php';
    require_once INCLUDES_PATH . '/functions.php';
    require_once INCLUDES_PATH . '/database.php';
} catch (Throwable $e) {
    die("Error al cargar componentes: " . htmlspecialchars($e->getMessage()));
}
?>
<?php
// test_db.php
define('ABSPATH', true); // Esto evita el mensaje de "acceso directo"
require_once 'includes/init.php';

$tablas_requeridas = [
    'personas',
    'usuarios',
    'registro',
    'plantillas_impresion',
    'categoria',
    'acceso',
    'logs'
];

echo "<h1>🔍 Verificación de Tablas</h1>";

foreach ($tablas_requeridas as $tabla) {
    try {
        $stmt = $conn->query("SELECT COUNT(*) FROM $tabla LIMIT 1");
        echo "<p>✅ Tabla <strong>$tabla</strong> existe.</p>";
    } catch (PDOException $e) {
        echo "<p>❌ Tabla <strong>$tabla</strong> no existe o hay un error.</p>";
    }
}

echo "<a href='test_config.php' class='btn'>Siguiente: Verificar Configuración</a>";

echo "<style>
    body { font-family: Arial, sans-serif; padding: 40px; background: #f9f9f9; }
    h1 { color: #333; }
    p { margin: 10px 0; }
    .btn {
        display: inline-block;
        padding: 10px 20px;
        background: #28a745;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    .btn:hover {
        background: #218838;
    }
</style>";
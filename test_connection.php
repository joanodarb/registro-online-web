<?php
// test_connection.php
define('ABSPATH', true); // Esto evita el mensaje de "acceso directo"
require_once 'includes/init.php';

try {
    $stmt = $conn->query("SELECT VERSION()");
    $version = $stmt->fetchColumn();

    echo "<h1>✅ Conexión exitosa</h1>";
    echo "<p>Versión de MySQL: <strong>$version</strong></p>";
    echo "<p>Nombre de la base de datos: <strong>" . DB_NAME . "</strong></p>";
    echo "<a href='test_db.php' class='btn'>Siguiente: Verificar Tablas</a>";
} catch (PDOException $e) {
    echo "<h1>❌ Error de conexión</h1>";
    echo "<p>No se pudo conectar a la base de datos.</p>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
}

echo "<style>
    body { font-family: Arial, sans-serif; padding: 40px; background: #f4f4f4; }
    h1 { color: #333; }
    .btn {
        display: inline-block;
        padding: 10px 20px;
        background: #007BFF;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    .btn:hover {
        background: #0056b3;
    }
</style>";
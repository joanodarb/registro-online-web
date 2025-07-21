<?php
// test_config.php
define('ABSPATH', true); // Esto evita el mensaje de "acceso directo"
require_once 'includes/init.php';

echo "<h1>⚙️ Constantes de Configuración</h1>";

echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Constante</th><th>Valor</th></tr>";

foreach (get_defined_constants(true)['user'] as $name => $value) {
    echo "<tr><td><strong>$name</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
}

echo "</table>";

echo "<a href='panel.php' class='btn'>Volver al Panel</a>";

echo "<style>
    body { font-family: Arial, sans-serif; padding: 40px; background: #f4f4f4; }
    h1 { color: #333; }
    table { background: white; margin-bottom: 20px; }
    th { background: #007BFF; color: white; text-align: left; }
    td { word-break: break-all; }
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
<?php
echo "<h2>Prueba de rutas</h2>";
echo "Directorio actual: " . __DIR__ . "<br>";
echo "Ruta a config.php: " . __DIR__ . '/includes/config.php' . "<br>";
echo "Existe config.php? " . (file_exists(__DIR__ . '/includes/config.php') ? 'Sí' : 'No');
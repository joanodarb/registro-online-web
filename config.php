<?php
// includes/config.php

// Configuración de base de datos
define("DB_HOST", "localhost");
define("DB_USER", "ozregistro");
define("DB_PASS", "!o4VbZ^_+5{E");
define("DB_NAME", "OZ_registros");

// Configuración general
define("APP_NAME", "Registro de Evento");
define("APP_URL", "https://www.ozmerchandising.com/registro_evento/");
define("DEBUG", false);

// Configuración de sesiones
define("SESSION_LIFE", 3600);
define("REMEMBER_ME_TIME", 1209600);
define("CSRF_TOKEN_LIFE", 300);
define("MAX_LOGIN_ATTEMPTS", 5);
define("LOCKOUT_TIME", 900);
?>
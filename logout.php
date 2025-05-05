<?php
session_start();

// Destruir todas las variables de sesión
session_unset();
session_destroy();

// Prevenir caché
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Redirigir a iniciar.php
header('Location: iniciar.php');
exit;
?>
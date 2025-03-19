<?php
session_start(); // Iniciar sesión
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión activa

// Redirigir al usuario al login
header("Location: login.php");
exit();
?>
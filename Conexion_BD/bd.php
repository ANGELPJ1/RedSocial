<?php
// Archivo: db.php (Conexion a la Base de Datos)

// Datos locales
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "redsocial";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
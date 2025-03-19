<?php
// Archivo: db.php (Conexion a la Base de Datos)

// Datos locales
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fotoyvideoas";

// Datos del servidor
//$servername = "162.241.203.242";
//$username = "fotogr79_admin_FotoyVideoAS";
//$password = "~C7vWvB)a!f3";
//$dbname = "fotogr79_fotoyvideoas";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
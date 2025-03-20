<?php
session_start();
require '../Conexion_BD/bd.php'; // Asegúrate de incluir tu conexión a la base de datos

$id_pub = $_GET['id_pub'];

$sql = "SELECT c.Comentario, c.Fecha_comentario, u.Nombre_usu 
        FROM comentarios c
        INNER JOIN usuarios u ON c.Id_Usu = u.Id_Usu
        WHERE c.Id_pub = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pub);
$stmt->execute();
$result = $stmt->get_result();

$comentarios = [];
while ($row = $result->fetch_assoc()) {
    $comentarios[] = $row;
}

echo json_encode($comentarios);
?>
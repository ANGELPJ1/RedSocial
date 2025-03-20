<?php
session_start();
require '../Conexion_BD/bd.php';

$id_pub = $_POST['id_pub'];
$id_usu = $_SESSION['usuario']['id'];// Asegúrate de que el usuario está logueado
$comentario = $_POST['comentario'];

$sql = "INSERT INTO comentarios (Id_pub, Id_Usu, Comentario) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $id_pub, $id_usu, $comentario);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

echo json_encode($response);
?>
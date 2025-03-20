<?php
session_start();
require "../Conexion_BD/bd.php"; // Conexión a la BD

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "No has iniciado sesión."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$publicacion_id = isset($_POST['id_pub']) ? intval($_POST['id_pub']) : 0;

if ($publicacion_id <= 0) {
    echo json_encode(["error" => "ID de publicación inválido."]);
    exit;
}

// Verificar que la publicación le pertenece al usuario logueado
$sql = "SELECT Id_Usu FROM publicaciones WHERE Id_pub = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $publicacion_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode(["error" => "Publicación no encontrada."]);
    exit;
}

if ($row["Id_Usu"] != $user_id) {
    echo json_encode(["error" => "No puedes eliminar esta publicación."]);
    exit;
}

// Eliminar comentarios relacionados
$conn->query("DELETE FROM comentarios WHERE Id_pub = $publicacion_id");

// Eliminar reacciones relacionadas
$conn->query("DELETE FROM reacciones WHERE Id_pub = $publicacion_id");

// Eliminar la publicación
$conn->query("DELETE FROM publicaciones WHERE Id_pub = $publicacion_id");

echo json_encode(["success" => "Publicación eliminada correctamente."]);
?>
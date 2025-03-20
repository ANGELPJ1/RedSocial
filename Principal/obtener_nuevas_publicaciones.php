<?php
header("Content-Type: application/json");
require "../Conexion_BD/bd.php"; // Conexión a la BD

$ultimoId = isset($_GET['ultimoId']) ? intval($_GET['ultimoId']) : 0;

// Consulta para obtener publicaciones más recientes que el último ID, con número de comentarios
$sql = "SELECT p.Id_pub, u.Nombre_usu, u.Img_Perfil, p.Contenido_pub, p.Imagen_Pub, 
               p.Like_pub, p.Dislike_pub, 
               (SELECT COUNT(*) FROM comentarios c WHERE c.Id_pub = p.Id_pub) AS num_comentarios,
               p.Id_Usu
        FROM publicaciones p
        JOIN usuarios u ON p.Id_Usu = u.ID_usu
        WHERE p.Id_pub > ? 
        ORDER BY p.Id_pub DESC";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ultimoId);
$stmt->execute();
$result = $stmt->get_result();

$publicaciones = [];
while ($row = $result->fetch_assoc()) {
    $publicaciones[] = [
        "Id_pub" => $row["Id_pub"],
        "Nombre_usu" => $row["Nombre_usu"],
        "Perfil_Img" => base64_encode($row["Img_Perfil"]),
        "Contenido_pub" => $row["Contenido_pub"],
        "Imagen_Pub" => !empty($row["Imagen_Pub"]) ? base64_encode($row["Imagen_Pub"]) : null,
        "Like_pub" => $row["Like_pub"],
        "Dislike_pub" => $row["Dislike_pub"],
        "num_comentarios" => $row["num_comentarios"]
    ];
}

echo json_encode($publicaciones);
exit;
?>
<?php
require '../Conexion_BD/bd.php'; // Conexión a la BD

$id_pub = $_GET['id_pub'];

$sql = "SELECT c.Comentario, c.Fecha_comentario, u.Nombre_usu, u.Img_Perfil
        FROM comentarios c
        INNER JOIN usuarios u ON c.Id_Usu = u.ID_usu
        WHERE c.Id_pub = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pub);
$stmt->execute();
$result = $stmt->get_result();

$comentarios = [];
while ($row = $result->fetch_assoc()) {
    // Verificar si hay imagen y convertirla a Base64
    if (!empty($row['Img_Perfil'])) {
        $row['Img_Perfil'] = 'data:image/jpeg;base64,' . base64_encode($row['Img_Perfil']);
    } else {
        $row['Img_Perfil'] = 'default.png'; // Imagen por defecto si no hay foto
    }

    $comentarios[] = $row;
}

echo json_encode($comentarios);
?>
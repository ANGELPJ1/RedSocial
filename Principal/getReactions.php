<?php
session_start();
require_once '../Conexion_BD/bd.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $publicationId = isset($_GET['id_pub']) ? intval($_GET['id_pub']) : 0;
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

    if ($publicationId <= 0 || !in_array($tipo, ['like', 'dislike'])) {
        header('Content-Type: application/json');
        echo json_encode([]);
        exit();
    }

    // Consulta: unir reacciones con usuarios para obtener Nombre y Img_Perfil
    $sql = "SELECT r.Id_Usu_Reaction, u.Nombre_usu, u.Img_Perfil 
            FROM reacciones r
            JOIN usuarios u ON r.Id_Usu_Reaction = u.ID_usu
            WHERE r.Id_pub = ? AND r.Tipo_Reaccion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $publicationId, $tipo);
    $stmt->execute();
    $result = $stmt->get_result();
    $reactions = [];
    while ($row = $result->fetch_assoc()) {
        // Convertir la imagen a base64 si existe
        if (!empty($row['Img_Perfil'])) {
            $row['Img_Perfil'] = base64_encode($row['Img_Perfil']);
        }
        $reactions[] = $row;
    }
    $stmt->close();
    header('Content-Type: application/json');
    echo json_encode($reactions);
    exit();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit();
}
?>
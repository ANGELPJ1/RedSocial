<?php
session_start();
require_once '../Conexion_BD/bd.php';

// Verificar que el usuario está autenticado
if (!isset($_SESSION['usuario']['id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$userId = $_SESSION['usuario']['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir parámetros
    $publicationId = isset($_POST['id_pub']) ? intval($_POST['id_pub']) : 0;
    $tipoReaccion = $_POST['tipo'] ?? '';

    // Validar parámetros
    if ($publicationId <= 0 || !in_array($tipoReaccion, ['like', 'dislike'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Parámetros inválidos']);
        exit();
    }

    // Verificar si el usuario ya reaccionó a esta publicación
    $stmt = $conn->prepare("SELECT Id_reaccion, Tipo_Reaccion FROM reacciones WHERE Id_pub = ? AND Id_Usu_Reaction = ?");
    $stmt->bind_param("ii", $publicationId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $existingReaction = $result->fetch_assoc();
    $stmt->close();

    if ($existingReaction) {
        if ($existingReaction['Tipo_Reaccion'] === $tipoReaccion) {
            // Mismo tipo: quitar la reacción (toggle off)
            $stmt = $conn->prepare("DELETE FROM reacciones WHERE Id_reaccion = ?");
            $stmt->bind_param("i", $existingReaction['Id_reaccion']);
            $stmt->execute();
            $stmt->close();
        } else {
            // Diferente: actualizar a nuevo tipo
            $stmt = $conn->prepare("UPDATE reacciones SET Tipo_Reaccion = ?, Fecha_reaccion = CURRENT_TIMESTAMP WHERE Id_reaccion = ?");
            $stmt->bind_param("si", $tipoReaccion, $existingReaction['Id_reaccion']);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        // No hay reacción previa: insertar nueva
        $stmt = $conn->prepare("INSERT INTO reacciones (Id_pub, Id_Usu_Reaction, Tipo_Reaccion) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $publicationId, $userId, $tipoReaccion);
        $stmt->execute();
        $stmt->close();
    }

    // Calcular nuevos contadores de reacciones para esta publicación
    $stmt = $conn->prepare("
      SELECT 
          SUM(CASE WHEN Tipo_Reaccion = 'like' THEN 1 ELSE 0 END) AS likes,
          SUM(CASE WHEN Tipo_Reaccion = 'dislike' THEN 1 ELSE 0 END) AS dislikes
      FROM reacciones WHERE Id_pub = ?
    ");
    $stmt->bind_param("i", $publicationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $counts = $result->fetch_assoc();
    $stmt->close();

    // Actualizar contadores en la tabla publicaciones (opcional)
    $stmt = $conn->prepare("UPDATE publicaciones SET Like_pub = ?, Dislike_pub = ? WHERE Id_pub = ?");
    $stmt->bind_param("iii", $counts['likes'], $counts['dislikes'], $publicationId);
    $stmt->execute();
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode([
        'likes' => (int) $counts['likes'],
        'dislikes' => (int) $counts['dislikes']
    ]);
    exit();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit();
}
?>
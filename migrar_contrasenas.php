<?php
require_once 'Conexion_BD/bd.php'; // Conexión a la base de datos

// Seleccionar todas las contraseñas actuales
$sql = "SELECT ID_usu, Pass_usu FROM usuarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['ID_usu'];
        $contrasena_plana = $row['Pass_usu'];

        echo "ID: $id - Contraseña antes: $contrasena_plana <br>";

        // Hashear sin verificar si ya está cifrada
        $hashedPassword = password_hash($contrasena_plana, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $updateSQL = "UPDATE usuarios SET Pass_usu = ? WHERE ID_usu = ?";
        $stmt = $conn->prepare($updateSQL);
        $stmt->bind_param("si", $hashedPassword, $id);

        if ($stmt->execute()) {
            echo "ID: $id - Nueva contraseña: $hashedPassword <br>";
        } else {
            echo "Error al actualizar usuario ID $id: " . $stmt->error . "<br>";
        }

        $stmt->close();
    }
    echo "Migración completada.";
} else {
    echo "No se encontraron usuarios.";
}

$conn->close();
?>
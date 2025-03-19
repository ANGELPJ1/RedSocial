<?php
session_start();
require '../Conexion_BD/bd.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario']['id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['usuario']['id'];
    $contenido = trim($_POST['contenido']);

    // Manejo de la imagen de la publicación (opcional)
    $imagen_publicacion = null;
    if (isset($_FILES['imagen_publicacion']) && !empty($_FILES['imagen_publicacion']['tmp_name'])) {
        $imagen_publicacion = file_get_contents($_FILES['imagen_publicacion']['tmp_name']);
    }

    // Calcular el Número de Publicación para el usuario (Numero_Pub)
    $stmt = $conn->prepare("SELECT COUNT(*) AS num FROM publicaciones WHERE Id_Usu = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $numero_pub = $row['num'] + 1;
    $stmt->close();

    // Armar el Título de la publicación: por ejemplo "NombreUsuario Numero"
    $nombre_usuario = $_SESSION['usuario']['nombre'];
    $titulo_pub = $nombre_usuario . " " . $numero_pub;

    // Insertar la publicación en la tabla
    $sql = "INSERT INTO publicaciones (Id_Usu, Numero_Pub, Titulo_pub, Contenido_pub, Imagen_Pub, Like_pub, Dislike_pub) VALUES (?, ?, ?, ?, ?, 0, 0)";
    $stmt = $conn->prepare($sql);
    // Para datos BLOB con MySQLi, se utiliza un placeholder y luego send_long_data().
    // Aquí usamos "iissb" donde 'b' corresponde a Imagen_Pub.
    $dummy = ""; // Valor dummy para el BLOB
    $stmt->bind_param("iissb", $id_usuario, $numero_pub, $titulo_pub, $contenido, $dummy);

    // Si se subió imagen, enviar los datos binarios
    if ($imagen_publicacion !== null) {
        $stmt->send_long_data(4, $imagen_publicacion);
    }

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>
                alert('Publicación registrada correctamente');
                window.location.href = 'principal.php';
              </script>";
    } else {
        echo "Error al registrar la publicación: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: principal.php");
    exit();
}
?>
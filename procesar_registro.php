<?php
require 'Conexion_BD/bd.php'; // Incluye la conexión existente

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = $_POST['contrasena'];
    $confirmar_password = $_POST['confirmar_contrasena'];
    $usuario = trim($_POST['usuario']);
    $permiso = "Administrador";
    $fecha_creacion = date("Y-m-d H:i:s");

    if ($password !== $confirmar_password) {
        die("Error: Las contraseñas no coinciden.");
    }

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Manejo de la imagen
    $imagen = null;
    if (!empty($_FILES['foto_perfil']['tmp_name'])) {
        $imagen = file_get_contents($_FILES['foto_perfil']['tmp_name']);
    }

    // Preparar la consulta SQL
    $sql = "INSERT INTO usuarios (Nombre_usu, Usuario_usu, Correo_usu, Pass_usu, Permiso_usu, Fecha_creacion, Img_Perfil) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssb", $nombre, $usuario, $correo, $password_hashed, $permiso, $fecha_creacion, $imagen);

    // Enviar los datos binarios de la imagen
    if ($imagen !== null) {
        $stmt->send_long_data(6, $imagen);
    }

    if ($stmt->execute()) {
        echo "<script>
            alert('Usuario registrado correctamente');
            window.location.href='index.php';
        </script>";
    } else {
        echo "Error al registrar el usuario: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

?>
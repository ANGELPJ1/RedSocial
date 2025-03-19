<?php
session_start();
require_once 'Conexion_BD/bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

    // Buscar usuario en la BD
    $sql = "SELECT * FROM usuarios WHERE Nombre_usu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['Pass_usu'];

        if (password_verify($contrasena, $hashedPassword)) {
            $_SESSION['usuario'] = [
                'id' => $row['ID_usu'],  // Asegúrate de que el ID esté bien escrito
                'nombre' => $row['Nombre_usu'],
                'usuario' => $row['Nombre_usu'],  // Ajusta si el nombre de usuario está en otra columna
                'imagen' => $row['Imagen_usu'] ?? 'default.png', // Imagen por defecto si no tiene
                'permiso' => $row['Permiso_usu']
            ];

            header("Location: Principal/principal.php");
            exit();
        } else {
            echo '<!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Contraseña Incorrecta</title>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: "error",
                        title: "Contraseña incorrecta",
                        text: "Verifica tu contraseña e intenta nuevamente.",
                        confirmButtonText: "Reintentar",
                        confirmButtonColor: "#d33"
                    }).then(() => {
                        window.location.href = "index.php";
                    });
                </script>
            </body>
            </html>';
            exit();
        }
    } else {
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Usuario no encontrado</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Usuario no encontrado",
                    text: "El usuario ingresado no existe en el sistema.",
                    confirmButtonText: "Reintentar",
                    confirmButtonColor: "#d33"
                }).then(() => {
                    window.location.href = "index.php";
                });
            </script>
        </body>
        </html>';
        exit();
    }
}
$conn->close();
?>
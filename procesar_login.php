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
            $_SESSION['usuario'] = $usuario;
            $_SESSION['permiso'] = $row['Permiso_usu'];

            header("Location: Login/index.php");
            exit();
        } else {
            // Registrar intento fallido
            $sqlUpdateIntentos = "INSERT INTO intentos_login (usuario, ip, intentos) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE intentos = intentos + 1, ultimo_intento = NOW()";
            $stmt = $conn->prepare($sqlUpdateIntentos);
            $stmt->bind_param("ss", $usuario, $ip);
            $stmt->execute();

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
                        window.location.href = "login.php";
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
                    window.location.href = "login.php";
                });
            </script>
        </body>
        </html>';
        exit();
    }
}
$conn->close();
?>
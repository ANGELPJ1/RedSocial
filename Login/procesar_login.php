<?php
session_start();
require_once '../Conexion_BD/bd.php';

$captcha = $_POST['g-recaptcha-response'];
$secretKey = "6LeZHPQqAAAAAM9RwN6kH2xxE37wb0o5jCPpYNR5";
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
$responseKeys = json_decode($response, true);

if (!$responseKeys["success"]) {
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verificación de CAPTCHA</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: "error",
                title: "Verificación fallida",
                text: "Por favor, completa el CAPTCHA.",
                confirmButtonText: "Volver a intentar",
                confirmButtonColor: "#d33"
            }).then(() => {
                window.location.href = "login.php";
            });
        </script>
    </body>
    </html>';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

    // Verificar intentos previos
    $sqlIntentos = "SELECT intentos, TIMESTAMPDIFF(MINUTE, ultimo_intento, NOW()) AS minutos FROM intentos_login WHERE usuario = ? OR ip = ?";
    $stmt = $conn->prepare($sqlIntentos);
    $stmt->bind_param("ss", $usuario, $ip);
    $stmt->execute();
    $resultIntentos = $stmt->get_result();
    $rowIntentos = $resultIntentos->fetch_assoc();

    if ($rowIntentos && $rowIntentos['intentos'] >= 5 && $rowIntentos['minutos'] < 15) {
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Intentos Excedidos</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Demasiados intentos fallidos",
                    text: "Has excedido el número de intentos permitidos. Inténtalo más tarde.",
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#d33"
                }).then(() => {
                    window.location.href = "login.php";
                });
            </script>
        </body>
        </html>';
        exit();
    }

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

            // Resetear intentos fallidos
            $sqlReset = "DELETE FROM intentos_login WHERE usuario = ? OR ip = ?";
            $stmt = $conn->prepare($sqlReset);
            $stmt->bind_param("ss", $usuario, $ip);
            $stmt->execute();

            header("Location: ../Dash2/index.php");
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
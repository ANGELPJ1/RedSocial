<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inicio de sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estiloLogin.css">
    <link rel="stylesheet" href="barra.css">
    <link rel="icon" type="image/png" href="iconoo.ico">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-side">
            <div class="my-form__wrapper">
                <div class="login-welcome-row">
                    <h1>Bienvenido a CaptureMe!👋</h1>
                    <br>
                    <center>
                        <p>Ingresa tus datos :</p>
                    </center>
                </div>

                <!-- Formulario que envía datos a procesar_login.php -->
                <form class="my-form" method="post" action="procesar_login.php">
                    <div class="text-field">
                        <label for="usuario">Usuario:</label>
                        <input type="text" id="usuario" name="usuario" placeholder="Tu usuario" required>
                        <img alt="User Icon" title="User Icon" src="usuario.png">
                    </div>

                    <div class="text-field">
                        <label for="password">Contraseña:</label>
                        <input id="password" type="password" name="contrasena" placeholder="Tu contraseña">
                        <img alt="Password Icon" title="Password Icon" src="cerrar.png">
                    </div>
                    <button class="my-form__button" type="submit">Iniciar sesión</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>


    <!-- SDK de Google Sign-In -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        const clientId = "987904877554-0l0u2khu4rtmtenio5fiaen0v74f1t4k.apps.googleusercontent.com";

        document.getElementById("googleSignInButton").addEventListener("click", function (event) {
            event.preventDefault();
            google.accounts.id.initialize({
                client_id: clientId,
                callback: handleCredentialResponse
            });
            google.accounts.id.prompt();
        });

        function handleCredentialResponse(response) {
            console.log("Encoded JWT ID token: " + response.credential);
            const token = response.credential;
            const payload = JSON.parse(atob(token.split('.')[1]));
            console.log("Información del usuario:", payload);
        }
    </script>
</body>

</html>
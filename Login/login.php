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
    <link rel="icon" type="image/png" href="../Imagenes/logo_foto.ico">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-side">
            <a href="../index.php" title="Logo">
                <img class="logo" src="../assets/logo_AS.png" alt="Logo" width="100px" height="15px">
            </a>
            <div class="my-form__wrapper">
                <div class="login-welcome-row">
                    <h1>Bienvenido de nuevo! 👋</h1>
                    <p>Ingresa tus datos :</p>
                </div>

                <!-- Formulario que envía datos a procesar_login.php -->
                <form class="my-form" method="post" action="procesar_login.php">
                    <div class="text-field">
                        <label for="usuario">Usuario:</label>
                        <input type="text" id="usuario" name="usuario" placeholder="Tu usuario" required>
                        <img alt="User Icon" title="User Icon" src="../assets/usuario.png">
                    </div>

                    <div class="text-field">
                        <label for="password">Contraseña:</label>
                        <input id="password" type="password" name="contrasena" placeholder="Tu contraseña">
                        <img alt="Password Icon" title="Password Icon" src="../assets/password.svg">
                    </div>

                    <center>
                        <div class="g-recaptcha" data-sitekey="6LeZHPQqAAAAAD-5znzjGjJuj1raN2cLTgaqFHJP"></div>
                    </center>

                    <button class="my-form__button" type="submit">Iniciar sesión</button>

                    <!-- Divider -->
                    <div class="divider">
                        <div class="divider-line"></div>
                        O
                        <div class="divider-line"></div>
                    </div>

                    <!-- Botones sociales -->
                    <div class="socials-row">
                        <a href="#" id="googleSignInButton" title="Use Google">
                            <img src="../assets/google.png" alt="Google"> Ingresa con Google
                        </a>
                        <a href="#" title="Use Facebook">
                            <img src="../assets/facebook.png" alt="Facebook"> Ingresa con Facebook
                        </a>
                    </div>

                    <div class="my-form__actions">
                        <div class="my-form__row">
                            <span>¿Olvidaste tu contraseña?</span>
                            <a href="../Pruebas/inicio.php" title="Reset Password">Recupera tu contraseña</a>
                        </div>
                        <div class="my-form__signup">
                            <a href="../Pruebas/inicio.php" title="Create Account">Crea una cuenta</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Imagen lateral -->
        <div class="info-side">
            <img src="../Imagenes/foto_uno.jpg" height="500px" width="650px" alt="Imagen bienvenida">
            <div class="welcome-message">
                <h2>"Hay momentos que deberían ser eternos..."</h2>
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
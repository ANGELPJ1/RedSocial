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


    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap');


    body {
        background: radial-gradient(circle at bottom, #000014, #000000);
    }


    @keyframes moveStars {
        from {
            background-position: 0 0;
        }

        to {
            background-position: 1000px 1000px;
        }
    }


    .titulo {
        font-size: 24px;
        /* Tamaño ajustado */
        font-family: 'Great Vibes', cursive;
        color: #FFD700;
        /* Amarillo dorado */
        display: flex;
        flex-wrap: wrap;
        gap: 3px;
        max-width: 90%;
        justify-content: center;
    }

    /* Efecto neón en cada letra */
    .letra {
        animation: flicker 2s infinite alternate;
        text-shadow:
            0 0 5px #FFD700,
            0 0 10px #FFEA00,
            0 0 20px #FFA500,
            0 0 40px rgba(255, 215, 0, 0.8);
    }

    /* Animaciones escalonadas */
    .letra:nth-child(1) {
        animation-delay: 0s;
    }

    .letra:nth-child(2) {
        animation-delay: 0.1s;
    }

    .letra:nth-child(3) {
        animation-delay: 0.2s;
    }

    .letra:nth-child(4) {
        animation-delay: 0.3s;
    }

    .letra:nth-child(5) {
        animation-delay: 0.4s;
    }

    .letra:nth-child(6) {
        animation-delay: 0.5s;
    }

    .letra:nth-child(7) {
        animation-delay: 0.6s;
    }

    .letra:nth-child(8) {
        animation-delay: 0.7s;
    }

    .letra:nth-child(9) {
        animation-delay: 0.8s;
    }

    .letra:nth-child(10) {
        animation-delay: 0.9s;
    }

    .letra:nth-child(11) {
        animation-delay: 1s;
    }

    .letra:nth-child(12) {
        animation-delay: 1.1s;
    }

    .letra:nth-child(13) {
        animation-delay: 1.2s;
    }

    .letra:nth-child(14) {
        animation-delay: 1.3s;
    }

    @keyframes flicker {
        0% {
            opacity: 0.3;
            text-shadow: none;
        }

        50% {
            opacity: 1;
            text-shadow:
                0 0 10px #FFD700,
                0 0 20px #FFEA00,
                0 0 30px #FFA500,
                0 0 50px rgba(255, 215, 0, 0.9);
        }

        100% {
            opacity: 0.6;
            text-shadow:
                0 0 5px #FFD700,
                0 0 15px #FFEA00,
                0 0 25px #FFA500;
        }
    }

    #particles-js {
        position: fixed;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at bottom, #000014, #000000);
        z-index: 0;
        /* Mantiene las partículas interactivas */
    }

    .my-form__wrapper {
        position: relative;
        z-index: 1;
        /* Se mantiene sobre el fondo */
    }
</style>

<body>
    <div id="particles-js"></div>
    <div class="login-wrapper">
        <div class="login-side">
            <div class="my-form__wrapper">
                <div class="login-welcome-row">
                    <center>
                        <div class="titulo">
                            <span class="letra">B</span>
                            <span class="letra">i</span>
                            <span class="letra">e</span>
                            <span class="letra">n</span>
                            <span class="letra">v</span>
                            <span class="letra">e</span>
                            <span class="letra">n</span>
                            <span class="letra">i</span>
                            <span class="letra">d</span>
                            <span class="letra">o</span>
                            <span class="letra">&nbsp;</span> <!-- Espacio -->
                            <span class="letra">a</span>
                            <span class="letra">&nbsp;</span> <!-- Espacio -->
                            <span class="letra">C</span>
                            <span class="letra">a</span>
                            <span class="letra">p</span>
                            <span class="letra">t</span>
                            <span class="letra">u</span>
                            <span class="letra">r</span>
                            <span class="letra">e</span>
                            <span class="letra">M</span>
                            <span class="letra">e</span>
                            <span class="letra">!</span>
                        </div>
                        <br>
                        <img src="Resources/logo3.jpg" alt="CaptureMe !" width="100" height="100"
                            style="border-radius: 50%;">
                        <br>

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

                    <button class="my-form__button " type="submit">Iniciar sesión</button>

                    <!-- Divider -->
                    <div class="divider">
                        <div class="divider-line"></div>
                        O
                        <div class="divider-line"></div>
                    </div>

                    <div class="my-form__actions">
                        <div class="my-form__signup">
                            <a href="registro.php" title="Create Account">Crea una cuenta</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script>
        particlesJS("particles-js", {
            particles: {
                number: { value: 100 },
                size: { value: 3 },
                move: { speed: 0.5 },
                color: { value: "#ffffff" },
                opacity: { value: 0.8 },
                line_linked: {
                    enable: false,
                },
            },
        });

    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estiloLogin.css">
    <link rel="stylesheet" href="barra.css">
    <link rel="icon" type="image/png" href="iconoo.ico">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .form-row .text-field {
            flex: 1;
        }

        .text-field {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }

        .button-container {
            display: flex;
            justify-content: center;
        }

        .image-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .image-preview label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .preview-container {
            margin-top: 10px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: transparent;

        }

        #preview_img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-side">
            <div class="my-form__wrapper">
                <div class="login-welcome-row">
                    <center>
                        <h1>Regístrate en CaptureMe!</h1>
                        <br>
                        <p>Completa el formulario para crear tu cuenta</p>
                    </center>
                </div>

                <form class="my-form" method="post" action="procesar_registro.php" enctype="multipart/form-data"
                    onsubmit="return validarFormulario()">
                    <!-- Nombre y Usuario en la misma fila -->
                    <div class="form-row">
                        <div class="text-field">
                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" placeholder="Tu nombre completo" required>
                        </div>

                        <div class="text-field">
                            <label for="usuario">Usuario:</label>
                            <input type="text" id="usuario" name="usuario" placeholder="Elige un nombre de usuario"
                                required>
                        </div>
                    </div>

                    <!-- Correo y Contraseña en la misma fila -->
                    <div class="form-row">
                        <div class="text-field">
                            <label for="correo">Correo electrónico:</label>
                            <input type="email" id="correo" name="correo" placeholder="Tu correo electrónico" required>
                        </div>

                        <div class="text-field">
                            <label for="password">Contraseña:</label>
                            <input id="password" type="password" name="contrasena" placeholder="Tu contraseña" required>
                        </div>
                    </div>

                    <!-- Confirmar contraseña y Foto de perfil en la misma fila -->
                    <div class="form-row">
                        <div class="text-field">
                            <label for="confirmar_password">Confirmar contraseña:</label>
                            <input id="confirmar_password" type="password" name="confirmar_contrasena"
                                placeholder="Repite tu contraseña" required>
                        </div>

                        <div class="text-field image-preview">
                            <label for="foto_perfil">Foto de perfil:</label>
                            <input type="file" id="foto_perfil" name="foto_perfil" accept=".jpg, .jpeg, .png"
                                onchange="mostrarPrevisualizacion(event)">
                        </div>

                    </div>

                    <!-- Botón de Confirmar centrado -->
                    <div class="button-container">
                        <button class="my-form__button" type="submit">Confirmar</button>
                    </div>


                    <!-- Divider -->
                    <div class="divider">
                        <div class="divider-line"></div>
                        O
                        <div class="divider-line"></div>
                    </div>

                    <div class="my-form__actions">
                        <center>
                            ¿Ya tienes una cuenta?
                            <br><br>
                            <div class="my-form__signup">
                                <a href="index.php" title="Iniciar Sesión"> Inicia sesión aquí</a>
                            </div>
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function validarFormulario() {
            var password = document.getElementById("password").value;
            var confirmarPassword = document.getElementById("confirmar_password").value;
            var foto = document.getElementById("foto_perfil").files[0];

            if (password.length < 6) {
                Swal.fire("Error", "La contraseña debe tener al menos 6 caracteres", "error");
                return false;
            }

            if (password !== confirmarPassword) {
                Swal.fire("Error", "Las contraseñas no coinciden", "error");
                return false;
            }

            if (foto) {
                var tiposPermitidos = ["image/jpeg", "image/jpg", "image/png"];
                if (!tiposPermitidos.includes(foto.type)) {
                    Swal.fire("Error", "Solo se permiten archivos JPG, JPEG o PNG", "error");
                    return false;
                }

                if (foto.size > 2 * 1024 * 1024) {
                    Swal.fire("Error", "El archivo no debe superar los 2MB", "error");
                    return false;
                }
            }

            return true;
        }
    </script>
</body>

</html>
<?php
session_start();
require '../Conexion_BD/bd.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario']['correo'])) {
    header("Location: ../index.php");
    exit();
}

// Actualizar los datos del usuario en sesión mediante su ID (único)
$id = $_SESSION['usuario']['id'];
$sqlUser = "SELECT ID_usu, Nombre_usu, Usuario_usu, Correo_usu, Img_Perfil FROM usuarios WHERE ID_usu = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $id);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

if ($resultUser->num_rows > 0) {
    $usuarioDatos = $resultUser->fetch_assoc();
    $_SESSION['usuario'] = [
        'id' => $usuarioDatos['ID_usu'],
        'nombre' => $usuarioDatos['Nombre_usu'],
        'usuario' => $usuarioDatos['Usuario_usu'],
        'correo' => $usuarioDatos['Correo_usu'],
        'imagen' => $usuarioDatos['Img_Perfil']
    ];
} else {
    echo "Usuario no encontrado.";
    exit();
}
$stmtUser->close();

// Consulta para obtener todas las publicaciones junto con los datos del usuario publicador
$sql = "SELECT p.*, u.Nombre_usu, u.Img_Perfil AS Perfil_Img 
        FROM publicaciones p 
        JOIN usuarios u ON p.Id_Usu = u.ID_usu 
        ORDER BY p.Fecha_publicacion DESC";
$result = $conn->query($sql);
$publicaciones = [];
if ($result && $result->num_rows > 0) {
    $publicaciones = $result->fetch_all(MYSQLI_ASSOC);
}
foreach ($publicaciones as $key => $post) {
    $sqlCount = "SELECT COUNT(*) AS total FROM comentarios WHERE Id_pub = ?";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bind_param("i", $post['Id_pub']);
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
    $countData = $resultCount->fetch_assoc();
    $publicaciones[$key]['num_comentarios'] = $countData['total'];
    $stmtCount->close(); // Cierra el statement después de usarlo
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Publicaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../barra.css">
    <!-- Libreria de efectos de particulas -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <style>
        /* Fondo animado */
        .gradiente {
            width: 100%;
            max-width: 400px;
            /* Para limitar el tamaño */
            padding: 5px;
            border-radius: 15px;
            background: linear-gradient(45deg,
                    #ff0000, #ff7300, #ffeb00, #47ff00, #00ffee, #0047ff,
                    #7a00ff, #ff00c8, #ff0000, #ff7300, #ffeb00, #47ff00,
                    #00ffee, #0047ff, #7a00ff, #ff00c8, #ff0000);
            background-size: 300% 300%;
            animation: animarGradiente 8s linear infinite;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @keyframes animarGradiente {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Contenedor del perfil */
        .profile-container {
            background: radial-gradient(circle at bottom, #000014, #000000);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
        }

        .profile-container img {
            border-radius: 50%;
        }

        .profile-container h5 {
            margin-top: 10px;
        }

        /* Fondo de partículas */
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at bottom, #000014, #000000);
            z-index: 0;
            /* Mantiene las partículas interactivas */
        }

        /* Contenedor principal de la página */
        .main-content {
            position: relative;
            /* Importante para que el z-index funcione */
            z-index: 1;
            /* Se mantiene por encima del fondo */
            padding: 20px;
        }
    </style>
</head>

<body>
    <div id="particles-js"></div>

    <div class="main-content">
        <div class="container mt-4">
            <div class="row">
                <!-- Perfil Usuario -->
                <div class="col-md-3 text-white">
                    <div class="gradiente">
                        <div class="profile-container bg-dark">
                            <?php
                            // Mostrar la imagen de perfil: si se almacena como BLOB, conviértela a Base64
                            if (!empty($_SESSION['usuario']['imagen'])) {
                                $imgPerfil = 'data:image/jpeg;base64,' . base64_encode($_SESSION['usuario']['imagen']);
                            } else {
                                $imgPerfil = 'default.png';
                            }
                            ?>
                            <img src="<?php echo $imgPerfil; ?>" alt="Usuario" width="50" height="50">
                            <h5 class="fw-bold"><?php echo $_SESSION['usuario']['nombre']; ?></h5>
                            <p class="text-white">@<?php echo $_SESSION['usuario']['usuario']; ?></p>
                            <button class="btn btn-outline-warning w-100"><i class="bi bi-person-fill-gear"></i> Editar
                                perfil</button>
                            <button class="btn btn-outline-danger w-100 mt-2" data-bs-toggle="modal"
                                data-bs-target="#logoutModal">
                                <i class="bi bi-box-arrow-left"></i> Cerrar sesión
                            </button>
                        </div>
                    </div>

                    <br>
                    <br>
                    <div class="gradiente">
                        <div class="profile-container bg-dark">
                            <h4>Acerca de...</h4>
                            <img src="../Resources/logo3.jpg" alt="Usuario" width="100" height="100">
                            <h6>
                                <p></p>
                                Hola, bienvenido a CaptureMe!
                                <p></p>
                                <p>Desarrollado por BDAPJ 🤑🤠</p>
                            </h6>
                        </div>
                    </div>
                </div>



                <!-- Contenido Principal -->
                <div class="col-md-9">
                    <!-- Formulario para Publicar -->
                    <form method="post" action="publicar.php" enctype="multipart/form-data">
                        <div class="bg-white p-3 shadow-sm rounded mb-3 text-dark">
                            <h5 class="mb-2">¿Qué estás pensando?</h5>
                            <textarea name="contenido" class="form-control mb-2" rows="2"
                                placeholder="Escribe algo..."></textarea>
                            <!-- Campo para imagen (opcional) -->
                            <div class="mb-2">
                                <label class="form-label">Añadir imagen? </label>
                                <input type="file" name="imagen_publicacion" accept=".jpg, .jpeg, .png">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-cloud-arrow-up-fill"></i> Publicar
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Listado de Publicaciones -->
                    <div class="publicaciones-container">
                        <?php foreach ($publicaciones as $post): ?>
                            <div class="bg-white p-3 shadow-sm rounded mb-3 text-dark"
                                data-pub-id="<?php echo $post['Id_pub']; ?>">
                                <div class="d-flex align-items-center mb-2">
                                    <?php
                                    // Imagen de perfil del publicador
                                    if (!empty($post['Perfil_Img'])) {
                                        $imgPerfilPub = 'data:image/jpeg;base64,' . base64_encode($post['Perfil_Img']);
                                    } else {
                                        $imgPerfilPub = 'default.png';
                                    }
                                    ?>
                                    <img src="<?php echo $imgPerfilPub; ?>" alt="Foto Usuario" class="rounded-circle me-2"
                                        width="40" height="40">
                                    <strong><?php echo $post['Nombre_usu']; ?></strong>
                                </div>
                                <hr class="my-2"> <!-- Línea divisoria -->

                                <!-- Contenido de la publicación -->
                                <p class="text-black"><?php echo $post['Contenido_pub']; ?></p>
                                <?php if (!empty($post['Imagen_Pub'])): ?>
                                    <div class="mb-2">
                                        <?php
                                        $imgPub = 'data:image/jpeg;base64,' . base64_encode($post['Imagen_Pub']);
                                        ?>
                                        <img src="<?php echo $imgPub; ?>" alt="Imagen Publicación" class="img-fluid" width="80"
                                            height="80">
                                    </div>
                                <?php endif; ?>

                                <hr class="my-2"> <!-- Línea divisoria -->
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary btn-sm btn-like"
                                        data-pub-id="<?php echo $post['Id_pub']; ?>">
                                        <i class="fas fa-thumbs-up"></i> Me gusta (<span
                                            class="counter"><?php echo $post['Like_pub']; ?></span>)
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-dislike"
                                        data-pub-id="<?php echo $post['Id_pub']; ?>">
                                        <i class="fas fa-thumbs-down"></i> No me gusta (<span
                                            class="counter"><?php echo $post['Dislike_pub']; ?></span>)
                                    </button>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-sm btn-warning toggle-comments"
                                            data-pub-id="<?php echo $post['Id_pub']; ?>">
                                            <i class="bi bi-chat-left-dots"></i>
                                            Ver comentarios
                                            <?php echo ($post['num_comentarios'] > 0) ? "(" . $post['num_comentarios'] . ")" : ""; ?>
                                        </button>
                                    </div>
                                </div>

                                <!-- Línea divisoria antes de los comentarios -->
                                <hr class="my-2">

                                <!-- Sección de comentarios -->

                                <div class="comments-section mt-3" id="comments-<?php echo $post['Id_pub']; ?>"
                                    style="display: none;">
                                    <div class="comments-list"></div> <!-- Aquí se cargarán los comentarios -->

                                    <!-- Formulario para agregar comentario -->
                                    <div class="mt-2">
                                        <textarea class="form-control comment-input"
                                            placeholder="Escribe un comentario..."></textarea>
                                        <button class="btn btn-sm btn-success mt-2 add-comment"
                                            data-pub-id="<?php echo $post['Id_pub']; ?>">
                                            <i class="bi bi-chat-dots"></i> Comentar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="logoutModalLabel">¿Seguro que te quieres ir?</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dark">
                    Presiona "Cerrar sesión" si deseas salir de la sesión actual.
                </div>
                <div class="modal-footer text-dark">
                    <button class="btn btn-success" type="button" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button class="btn btn-danger" onclick="cerrarSesion()">
                        <i class="bi bi-door-closed-fill"></i> Cerrar sesión
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function cerrarSesion() {
            window.location.href = "../index.php";
        }
    </script>

    <!-- Recuperacion de nuevos post -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            function obtenerUltimoId() {
                return $(".bg-white[data-pub-id]").last().attr("data-pub-id") || 0;
            }

            function actualizarPublicaciones() {
                let ultimoId = obtenerUltimoId();
                $.ajax({
                    url: "obtener_nuevas_publicaciones.php",
                    type: "GET",
                    data: { ultimoId: ultimoId },
                    dataType: "json",
                    success: function (response) {
                        if (!Array.isArray(response)) {
                            console.error("Error: La respuesta no es un array válido.");
                            return;
                        }

                        response.forEach(publicacion => {
                            let publicacionId = publicacion.Id_pub;
                            let publicacionExistente = $(`.bg-white[data-pub-id="${publicacionId}"]`);

                            if (publicacionExistente.length > 0) {
                                // **Actualizar los datos existentes sin reemplazar elementos**
                                publicacionExistente.find(".counter-like").text(publicacion.Like_pub);
                                publicacionExistente.find(".counter-dislike").text(publicacion.Dislike_pub);
                                publicacionExistente.find(".counter-comments").text(publicacion.num_comentarios > 0 ? `(${publicacion.num_comentarios})` : "");
                            } else {
                                // **Agregar nueva publicación**
                                let nuevaPub = `
                        <div class="bg-white p-3 shadow-sm rounded mb-3 text-dark" data-pub-id="${publicacionId}">
                            <div class="d-flex align-items-center mb-2">
                                <img src="data:image/jpeg;base64,${publicacion.Perfil_Img}" alt="Foto Usuario" class="rounded-circle me-2" width="40" height="40">
                                <strong>${publicacion.Nombre_usu}</strong>
                            </div>
                            <hr class="my-2">
                            <p class="text-black">${publicacion.Contenido_pub}</p>
                            ${publicacion.Imagen_Pub ? `<img src="data:image/jpeg;base64,${publicacion.Imagen_Pub}" class="img-fluid" width="80" height="80">` : ""}
                            <hr class="my-2">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm btn-like" data-pub-id="${publicacionId}">
                                    <i class="fas fa-thumbs-up"></i> Me gusta (<span class="counter-like">${publicacion.Like_pub}</span>)
                                </button>
                                <button class="btn btn-danger btn-sm btn-dislike" data-pub-id="${publicacionId}">
                                    <i class="fas fa-thumbs-down"></i> No me gusta (<span class="counter-dislike">${publicacion.Dislike_pub}</span>)
                                </button>
                                <button class="btn btn-sm btn-warning toggle-comments" data-pub-id="${publicacionId}">
                                    <i class="bi bi-chat-left-dots"></i> Ver comentarios <span class="counter-comments">${publicacion.num_comentarios > 0 ? `(${publicacion.num_comentarios})` : ""}</span>
                                </button>
                            </div>
                            <hr class="my-2">
                        </div>
                        `;
                                $(".publicaciones-container").prepend(nuevaPub);
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("Error en la actualización de publicaciones:", error);
                    }
                });
            }

            // **Delegación de eventos para evitar problemas con elementos nuevos**
            $(document).on("click", ".btn-like", function () {
                let publicacionId = $(this).data("pub-id");
                let boton = $(this);
                $.ajax({
                    url: "procesar_like.php",
                    type: "POST",
                    data: { Id_pub: publicacionId },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            boton.find(".counter-like").text(response.nuevos_likes);
                        } else {
                            alert("Error al dar like.");
                        }
                    }
                });
            });

            $(document).on("click", ".btn-dislike", function () {
                let publicacionId = $(this).data("pub-id");
                let boton = $(this);
                $.ajax({
                    url: "procesar_dislike.php",
                    type: "POST",
                    data: { Id_pub: publicacionId },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            boton.find(".counter-dislike").text(response.nuevos_dislikes);
                        } else {
                            alert("Error al dar dislike.");
                        }
                    }
                });
            });

            $(document).on("click", ".toggle-comments", function () {
                let publicacionId = $(this).data("pub-id");
                // Aquí puedes cargar los comentarios de la publicación
            });

            // **Actualizar publicaciones cada 5 segundos sin afectar botones**
            setInterval(actualizarPublicaciones, 5000);
        });

    </script>


    <!-- Particulas de background -->
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

    <!-- Lógica de Reacciones con AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Botón Like
            document.querySelectorAll('.btn-like').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var pubId = this.getAttribute('data-pub-id');
                    enviarReaccion(pubId, 'like');
                });
            });
            // Botón Dislike
            document.querySelectorAll('.btn-dislike').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var pubId = this.getAttribute('data-pub-id');
                    enviarReaccion(pubId, 'dislike');
                });
            });

            function enviarReaccion(pubId, tipo) {
                var formData = new URLSearchParams();
                formData.append('id_pub', pubId);
                formData.append('tipo', tipo);

                fetch('reaccion.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData.toString()
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            // Actualiza los contadores en la publicación correspondiente
                            var pubDiv = document.querySelector('[data-pub-id="' + pubId + '"]');
                            if (pubDiv) {
                                pubDiv.querySelector('.btn-like .counter').textContent = data.likes;
                                pubDiv.querySelector('.btn-dislike .counter').textContent = data.dislikes;
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>

    <!-- Muestra de quien reacciona a las publicaciones -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Habilitar popovers de Bootstrap
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.forEach(function (popoverTriggerEl) {
                new bootstrap.Popover(popoverTriggerEl);
            });

            var reactionButtons = document.querySelectorAll('.btn-like, .btn-dislike');

            reactionButtons.forEach(function (btn) {
                btn.myPopover = new bootstrap.Popover(btn, {
                    container: 'body',
                    trigger: 'manual', // Evita que Bootstrap lo cierre automáticamente
                    html: true,
                    placement: 'top',
                    content: 'Cargando...'
                });

                btn.addEventListener('mouseenter', function () {
                    clearTimeout(btn.hideTimeout); // Cancela ocultado anterior
                    var pubId = btn.getAttribute('data-pub-id');
                    var tipo = btn.classList.contains('btn-like') ? 'like' : 'dislike';

                    btn.setAttribute('data-bs-content', 'Cargando...');
                    btn.myPopover.show();

                    fetch('getReactions.php?id_pub=' + pubId + '&tipo=' + tipo)
                        .then(response => response.json())
                        .then(data => {
                            var content = '<div id="popover-content">';
                            if (data.length === 0) {
                                content += '<p class="m-0">No hay reacciones.</p>';
                            } else {
                                data.forEach(function (user) {
                                    var userImg = user.Img_Perfil ?
                                        '<img src="data:image/jpeg;base64,' + user.Img_Perfil + '" width="20" height="20" class="rounded-circle me-1">' :
                                        '<img src="default.png" width="20" height="20" class="rounded-circle me-1">';

                                    content += '<div class="d-flex align-items-center mb-1">' +
                                        userImg +
                                        '<span>' + user.Nombre_usu + '</span>' +
                                        '</div>';
                                });
                            }
                            content += '</div>';

                            // Esperar a que Bootstrap renderice el popover antes de cambiar su contenido
                            setTimeout(() => {
                                var popover = document.querySelector('.popover');
                                if (popover) {
                                    popover.querySelector('.popover-body').innerHTML = content;

                                    // Evita que el popover desaparezca si el cursor entra en él
                                    popover.addEventListener('mouseenter', function () {
                                        clearTimeout(btn.hideTimeout);
                                    });

                                    popover.addEventListener('mouseleave', function () {
                                        btn.hideTimeout = setTimeout(() => {
                                            btn.myPopover.hide();
                                        }, 500);
                                    });
                                }
                            }, 100);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            btn.setAttribute('data-bs-content', 'Error al cargar');
                            btn.myPopover.setContent({ '.popover-body': 'Error al cargar' });
                        });
                });

                btn.addEventListener('mouseleave', function () {
                    btn.hideTimeout = setTimeout(() => {
                        btn.myPopover.hide();
                    }, 500); // Cierra después de 0.5s si el usuario no regresa
                });
            });
        });
    </script>

    <!-- Seccion de comentarios -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mostrar/Ocultar comentarios al hacer clic en el botón
            document.querySelectorAll('.toggle-comments').forEach(button => {
                button.addEventListener('click', function () {
                    let pubId = this.getAttribute('data-pub-id');
                    let commentSection = document.getElementById('comments-' + pubId);

                    if (commentSection.style.display === 'none') {
                        commentSection.style.display = 'block';
                        loadComments(pubId);
                    } else {
                        commentSection.style.display = 'none';
                    }
                });
            });

            // Función para cargar comentarios
            function loadComments(pubId) {
                fetch('getComments.php?id_pub=' + pubId)
                    .then(response => response.json())
                    .then(data => {
                        let commentsList = document.querySelector('#comments-' + pubId + ' .comments-list');
                        commentsList.innerHTML = ''; // Limpiar comentarios anteriores

                        if (data.length === 0) {
                            commentsList.innerHTML = '<p class="text-muted">No hay comentarios.</p>';
                        } else {
                            data.forEach(comment => {
                                let imgSrc = comment.Img_Perfil ? comment.Img_Perfil : 'default.png'; // Imagen de perfil o default

                                commentsList.innerHTML += `
                            <div class="d-flex align-items-start mb-2">
                                <img src="${imgSrc}" alt="Perfil" class="rounded-circle me-2" width="35" height="35" 
                                    onerror="this.src='default.png';">
                                <div>
                                    <strong>${comment.Nombre_usu}</strong>
                                    <small class="text-muted"> ${comment.Fecha_comentario}</small>
                                    <p class="mb-0">${comment.Comentario}</p>
                                </div>
                            </div>
                        `;
                            });
                        }

                        // Actualizar el contador de comentarios
                        updateCommentCount(pubId, data.length);
                    })
                    .catch(error => console.error('Error al cargar comentarios:', error));
            }

            // Función para actualizar el número de comentarios en el botón
            function updateCommentCount(pubId, count) {
                let commentButton = document.querySelector(`.toggle-comments[data-pub-id="${pubId}"]`);
                if (commentButton) {
                    commentButton.innerHTML = `Ver comentarios (${count})`;
                }
            }

            // Agregar nuevo comentario
            document.querySelectorAll('.add-comment').forEach(button => {
                button.addEventListener('click', function () {
                    let pubId = this.getAttribute('data-pub-id');
                    let commentInput = document.querySelector('#comments-' + pubId + ' .comment-input');
                    let comentario = commentInput.value.trim();

                    if (comentario === '') return;

                    fetch('addComment.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id_pub=${pubId}&comentario=${encodeURIComponent(comentario)}`
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                commentInput.value = ''; // Limpiar campo
                                loadComments(pubId); // Recargar comentarios y actualizar contador
                            } else {
                                alert('Error al agregar comentario.');
                            }
                        })
                        .catch(error => console.error('Error al agregar comentario:', error));
                });
            });
        });

    </script>

</body>

</html>
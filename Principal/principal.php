<?php
session_start();
require '../Conexion_BD/bd.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario']['correo'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_SESSION['usuario']['id'];


// Consulta para obtener los datos del usuario
$sql = "SELECT ID_usu, Nombre_usu, Usuario_usu, Correo_usu, Pass_usu, Img_Perfil FROM usuarios WHERE ID_usu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    $_SESSION['usuario'] = [
        'id' => $usuario['ID_usu'],
        'nombre' => $usuario['Nombre_usu'],
        'usuario' => $usuario['Usuario_usu'],
        'correo' => $usuario['Correo_usu'],
        'imagen' => $usuario['Img_Perfil']
    ];
} else {
    echo "Usuario no encontrado.";
    exit();
}

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

    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298, #4e54c8, #ff7e5f);
            background-size: cover;
            height: 97vh;
            margin: 0;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <!-- Perfil Usuario -->
            <div class="col-md-3">
                <div class="bg-white p-3 shadow-sm rounded mb-3 text-center text-dark">
                    <img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($_SESSION['usuario']['imagen']); ?>"
                        alt="Usuario" class="rounded-circle mb-2" width="50" height="50">

                    <h5 class="fw-bold"> <?php echo $_SESSION['usuario']['nombre']; ?> </h5>
                    <p class="text-muted">@<?php echo $_SESSION['usuario']['usuario']; ?></p>
                    <button class="btn btn-warning w-100"><i class="bi bi-person-fill-gear"></i> Editar perfil
                    </button>
                    <!-- Botón de Logout -->
                    <button class="btn btn-danger w-100 mt-2" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="bi bi-box-arrow-left"></i> Cerrar sesión
                    </button>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="col-md-9">
                <!-- Publicar -->
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
                            <button type="submit" class="btn btn-success"><i class="bi bi-cloud-arrow-up-fill"></i>
                                Publicar</button>
                        </div>
                    </div>
                </form>


                <!-- Listado de Publicaciones -->
                <?php foreach ($publicaciones as $post): ?>
                    <div class="bg-white p-3 shadow-sm rounded mb-3 text-dark">
                        <div class="d-flex align-items-center mb-2">
                            <?php
                            // Para la imagen de perfil del usuario que publicó:
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
                        <!-- No mostramos el Título (Titulo_pub) ya que es para uso interno -->
                        <p class="text-muted"><?php echo $post['Contenido_pub']; ?></p>
                        <?php if (!empty($post['Imagen_Pub'])): ?>
                            <div class="mb-2">
                                <?php
                                // Convertir la imagen de la publicación (BLOB) a Base64 para mostrarla
                                $imgPub = 'data:image/jpeg;base64,' . base64_encode($post['Imagen_Pub']);
                                ?>
                                <img src="<?php echo $imgPub; ?>" alt="Imagen Publicación" class="img-fluid" width="80"
                                    height="80">
                            </div>
                        <?php endif; ?>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm"><i class="fas fa-thumbs-up"></i> Like
                                (<?php echo $post['Like_pub']; ?>)</button>
                            <button class="btn btn-danger btn-sm"><i class="fas fa-thumbs-down"></i> Dislike
                                (<?php echo $post['Dislike_pub']; ?>)</button>
                        </div>
                    </div>
                <?php endforeach; ?>

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
                    <div class="modal-footer">
                        <button class="btn btn-success" type="button" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i>
                            Cancelar
                        </button>
                        <button class="btn btn-danger" onclick="cerrarSesion()">
                            <i class="bi bi-door-closed-fill"></i>
                            Cerrar sesión
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function cerrarSesion() {
            window.location.href = "../index.php";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
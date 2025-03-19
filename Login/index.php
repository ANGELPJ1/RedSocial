<?php
// Simulación de usuario (en un caso real, estos datos vendrían de la base de datos o sesión)
$usuario = [
    'imagen' => './WhatsApp Image 2024-06-11 at 10.51.07 AM.jpeg', // Ruta de la imagen del usuario
    'nombre' => 'Angel PJ',
    'usuario' => 'BDAPJ',
    'password' => '******' // No se debería mostrar en un sistema real
];

// Simulación de publicaciones
$publicaciones = [
    [
        'titulo' => 'Mi primera publicación',
        'contenido' => 'Este es el contenido de mi primera publicación.',
        'imagen_usuario' => 'WhatsApp Image 2024-06-11 at 10.51.07 AM.jpeg',
        'likes' => 10,
        'dislikes' => 2
    ],
    [
        'titulo' => 'Otra publicación',
        'contenido' => 'Aquí hay otro contenido interesante.',
        'imagen_usuario' => 'usuario2.jpg',
        'likes' => 25,
        'dislikes' => 3
    ]
];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Publicaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                    <img src="WhatsApp Image 2024-06-11 at 10.51.07 AM.jpeg" alt="Usuario" class="rounded-circle mb-2"
                        width="50" height="50">
                    <h5 class="fw-bold"> <?php echo $usuario['nombre']; ?> </h5>
                    <p class="text-muted">@<?php echo $usuario['usuario']; ?></p>
                    <button class="btn btn-outline-primary w-100"><i class="fas fa-user-edit"></i> Editar
                        perfil</button>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="col-md-9">
                <!-- Publicar -->
                <div class="bg-white p-3 shadow-sm rounded mb-3 text-dark">
                    <h5 class="mb-2">¿Qué estás pensando?</h5>
                    <textarea class="form-control mb-2" rows="2" placeholder="Escribe algo..."></textarea>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-outline-secondary"><i class="fas fa-image"></i> Foto</button>
                        <button class="btn btn-primary"><i class="fas fa-paper-plane"></i> Publicar</button>
                    </div>
                </div>

                <!-- Publicaciones -->
                <?php foreach ($publicaciones as $post): ?>
                    <div class="bg-white p-3 shadow-sm rounded mb-3 text-dark">
                        <div class="d-flex align-items-center mb-2">
                            <img src="<?php echo $post['imagen_usuario']; ?>" alt="Foto Usuario" class="rounded-circle me-2"
                                width="40" height="40">
                            <strong>Usuario</strong>
                        </div>
                        <h5 class="fw-bold"> <?php echo $post['titulo']; ?> </h5>
                        <p class="text-muted"> <?php echo $post['contenido']; ?> </p>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-success btn-sm"><i class="fas fa-thumbs-up"></i> Like
                                (<?php echo $post['likes']; ?>)</button>
                            <button class="btn btn-outline-danger btn-sm"><i class="fas fa-thumbs-down"></i> Dislike
                                (<?php echo $post['dislikes']; ?>)</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tiny.cloud/1/ot3ylpkxqs7181mw1rbulmqgqhj5d76f3nj5uu9q23e6se4i/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .placeholder-img {
            background-color: #e9ecef;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dee2e6;
        }
        .comment-box {
            border: 1px solid #dee2e6;
            padding: 10px;
            margin-bottom: 10px;
        }
        .btn-purple {
            background-color: #6f42c1;
            color: white;
        }
        .btn-purple:hover {
            background-color: #5a32a3;
            color: white;
        }
        .article-img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <?php
    require_once 'vendor/autoload.php';
    DB::$user = 'root';
    DB::$password = '';
    DB::$dbName = 'canacintra';
    DB::$host = 'localhost';
    DB::$encoding = 'utf8';

    $publicacion_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($publicacion_id <= 0) {
        header('Location: index.php');
        exit;
    }

    $publicacion = DB::queryFirstRow("
        SELECT p.id, p.titulo, p.contenido, p.created, p.fk_foto_portada, p.fk_categoria, p.fk_user,
               a.ruta AS foto_ruta, c.nombre AS categoria_nombre, u.username AS autor_nombre
        FROM publicacion p
        LEFT JOIN archivo a ON p.fk_foto_portada = a.id
        LEFT JOIN categoria c ON p.fk_categoria = c.id
        LEFT JOIN user u ON p.fk_user = u.id
        WHERE p.id = %i AND p.fk_estatu = 2", $publicacion_id
    );
    if (!$publicacion) {
        header('Location: index.php');
        exit;
    }

    $relacionados = DB::query("
        SELECT p.id, p.titulo, a.ruta AS foto_ruta
        FROM publicacion p
        LEFT JOIN archivo a ON p.fk_foto_portada = a.id
        WHERE p.fk_categoria = %i AND p.id != %i AND p.fk_estatu = 2
        LIMIT 4", $publicacion['fk_categoria'], $publicacion_id
    );

    $recomendados = DB::query("
        SELECT p.id, p.titulo, a.ruta AS foto_ruta
        FROM publicacion p
        LEFT JOIN archivo a ON p.fk_foto_portada = a.id
        WHERE p.id != %i AND p.fk_estatu = 2
        ORDER BY p.created DESC
        LIMIT 4", $publicacion_id
    );

    $comentarios = DB::query("
        SELECT c.id, c.contenido, c.created, u.username AS autor_nombre
        FROM comentario c
        LEFT JOIN user u ON c.fk_user = u.id
        WHERE c.fk_publicacion = %i AND c.fk_estatu = 1
        ORDER BY c.created DESC", $publicacion_id
    );

    $adicionales = DB::query("
        SELECT a.ruta
        FROM publicacion_archivo pa
        JOIN archivo a ON pa.fk_archivo = a.id
        WHERE pa.fk_publicacion = %i AND a.id != %i", $publicacion_id, $publicacion['fk_foto_portada']
    );
    ?>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">CANACINTRA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categorias.php">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sobrenosotros.php">Sobre nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacto.php">Contáctanos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="iniciar.php"><button class="btn btn-primary">Iniciar sesión</button></a>
                        <a class="nav-link" href="registro.php"><button class="btn btn-primary">Registrar</button></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($publicacion['titulo']) ?></li>
                    </ol>
                </nav>
                <h1><?= htmlspecialchars($publicacion['titulo']) ?></h1>
                <p class="text-muted">
                    <?= htmlspecialchars($publicacion['autor_nombre']) ?> | 
                    <a href="categorias.php?id=<?= $publicacion['fk_categoria'] ?>"><?= htmlspecialchars($publicacion['categoria_nombre']) ?></a> | 
                    <?= date('d/m/Y H:i', strtotime($publicacion['created'])) ?>
                </p>

                <?php if ($publicacion['foto_ruta']): ?>
                    <img src="http://localhost/blog/<?= htmlspecialchars($publicacion['foto_ruta']) ?>" alt="Foto principal" class="img-fluid mb-3 article-img">
                <?php endif; ?>

                <div>
                    <?= $publicacion['contenido'] ?>
                    <?php foreach ($adicionales as $adicional): ?>
                        <img src="http://localhost/blog/<?= htmlspecialchars($adicional['ruta']) ?>" alt="Imagen adicional" class="img-fluid mb-3 article-img">
                    <?php endforeach; ?>
                </div>

                <div class="d-flex align-items-center my-3">
                    <span class="me-3">Compartir</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://localhost/blog/articulo.php?id=' . $publicacion_id) ?>" class="me-2" target="_blank">
                        <i class="fab fa-facebook-f fa-2x"></i>
                    </a>
                    <a href="https://www.instagram.com/" class="me-2" target="_blank">
                        <i class="fab fa-instagram fa-2x"></i>
                    </a>
                </div>

                <h3>Más artículos</h3>
                <div class="row">
                    <?php foreach ($relacionados as $rel): ?>
                        <div class="col-md-3">
                            <?php if ($rel['foto_ruta']): ?>
                                <img src="http://localhost/blog/<?= htmlspecialchars($rel['foto_ruta']) ?>" alt="Foto" class="img-fluid mb-2 article-img">
                            <?php else: ?>
                                <div class="placeholder-img mb-2" style="height: 100px;">Imagen</div>
                            <?php endif; ?>
                            <h5><a href="articulo.php?id=<?= $rel['id'] ?>"><?= htmlspecialchars($rel['titulo']) ?></a></h5>
                        </div>
                    <?php endforeach; ?>
                </div>

                <h3>Comentarios</h3>
                <div class="mb-3">
                    <form id="commentForm" data-publicacion-id="<?= $publicacion_id ?>">
                        <textarea class="form-control" id="commentEditor" name="contenido" rows="3" placeholder="Escribe tu comentario"></textarea>
                        <button type="submit" class="btn btn-purple mt-2">Comentar</button>
                    </form>
                </div>

                <div id="commentList">
                    <?php foreach ($comentarios as $comentario): ?>
                        <div class="comment-box">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle fa-2x me-2"></i>
                                <div>
                                    <strong><?= htmlspecialchars($comentario['autor_nombre']) ?></strong>
                                    <p class="mb-0"><?= htmlspecialchars($comentario['contenido']) ?></p>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($comentario['created'])) ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-md-4">
                <h3>Artículos recomendados</h3>
                <?php foreach ($recomendados as $rec): ?>
                    <div class="mb-3">
                        <?php if ($rec['foto_ruta']): ?>
                            <img src="http://localhost/blog/<?= htmlspecialchars($rec['foto_ruta']) ?>" alt="Foto" class="img-fluid mb-2 article-img" style="height: 100px; object-fit: cover;">
                        <?php else: ?>
                            <div class="placeholder-img mb-2" style="height: 100px;">Imagen</div>
                        <?php endif; ?>
                        <h5><a href="articulo.php?id=<?= $rec['id'] ?>"><?= htmlspecialchars($rec['titulo']) ?></a></h5>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h3>CANACINTRA</h3>
                </div>
                <div class="col-md-3">
                    <a href="#" class="text-white">Enlace adicional</a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="text-white">Enlace adicional</a>
                </div>
                <div class="col-md-3 text-end">
                    <p>© CANACINTRA 2025</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        tinymce.init({
            selector: '#commentEditor',
            plugins: 'autoresize',
            toolbar: 'bold italic | link',
            menubar: false,
            statusbar: false,
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
            autoresize_bottom_margin: 10
        });

        document.getElementById('commentForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const publicacionId = form.dataset.publicacionId;
            const contenido = tinymce.get('commentEditor').getContent();
            if (!contenido) {
                alert('El comentario no puede estar vacío');
                return;
            }

            try {
                const response = await fetch('guardar_comentario.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `fk_publicacion=${publicacionId}&contenido=${encodeURIComponent(contenido)}`
                });
                const result = await response.json();
                if (result.success) {
                    const commentList = document.getElementById('commentList');
                    const newComment = document.createElement('div');
                    newComment.className = 'comment-box';
                    newComment.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle fa-2x me-2"></i>
                            <div>
                                <strong>${result.autor_nombre}</strong>
                                <p class="mb-0">${contenido}</p>
                                <small class="text-muted">${result.created}</small>
                            </div>
                        </div>
                    `;
                    commentList.prepend(newComment);
                    tinymce.get('commentEditor').setContent('');
                } else {
                    alert('Error al guardar el comentario: ' + (result.error || 'Desconocido'));
                }
            } catch (error) {
                alert('Error en la solicitud: ' + error.message);
            }
        });
    </script>
</body>
</html>
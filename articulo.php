<!DOCTYPE html>
<html lang="es">
<?php
// Prevenir caché en desarrollo
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

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

// Incrementar el contador de vistas
DB::query("UPDATE publicacion SET vistas = vistas + 1 WHERE id = %i", $publicacion_id);

$publicacion = DB::queryFirstRow("
    SELECT p.id, p.titulo, p.contenido, p.created, p.vistas, p.fk_foto_portada, p.fk_categoria, p.fk_user,
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

<?php include 'navbar.php'; ?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artículo | Canacintra</title>
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="assets/css/styles.css?v=1" as="style">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css?v=1">
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
<body class="d-flex flex-column min-vh-100 no-fouc">
    <div class="container my-4 flex-grow-1">
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
                    <?= date('d/m/Y H:i', strtotime($publicacion['created'])) ?> | 
                    <i class="fas fa-eye"></i> <?= $publicacion['vistas'] ?> vistas
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
                        <textarea class="form-control" rows="3" placeholder="Escribe tu comentario"></textarea>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button type="submit" class="btn btn-purple mt-2">Comentar</button>
                        <?php else: ?>
                            <p class="mt-2">Para comentar debes <a href="iniciar.php">iniciar sesión</a>.</p>
                        <?php endif; ?>
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

    <footer class="mt-auto">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">CANACINTRA</h3>
                </div>
                <div>
                    <a href="#" class="text-white me-3">Enlace adicional</a>
                    <a href="#" class="text-white me-3">Enlace adicional</a>
                    <a href="#" class="text-white">Enlace adicional</a>
                </div>
                <div>
                    <small>© CANACINTRA 2025</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/ot3ylpkxqs7181mw1rbulmqgqhj5d76f3nj5uu9q23e6se4i/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM completamente cargado');
            if (document.compatMode !== 'CSS1Compat') {
                console.error('El documento está en modo quirks. Asegúrate de que el DOCTYPE esté correcto: <!DOCTYPE html>');
                return;
            }
            tinymce.init({
                selector: 'textarea',
                plugins: 'autoresize',
                toolbar: 'bold italic | link',
                menubar: false,
                statusbar: false,
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
                autoresize_bottom_margin: 10,
                setup: (editor) => {
                    console.log('TinyMCE inicializado para todos los textarea');
                    editor.on('init', () => {
                        console.log('Editor TinyMCE listo');
                    });
                    editor.on('error', (error) => {
                        console.error('Error en TinyMCE:', error.message);
                    });
                }
            });
        });

        // Manejar el envío del comentario
        document.getElementById('commentForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            console.log('Formulario enviado');

            // Verificar si el usuario ha iniciado sesión
            <?php if (!isset($_SESSION['user_id'])): ?>
                console.log('Usuario no autenticado, no se puede enviar comentario');
                alert('Debes iniciar sesión para comentar.');
                return;
            <?php endif; ?>

            const form = e.target;
            const publicacionId = form.dataset.publicacionId;
            console.log('ID de publicación:', publicacionId);
            const contenido = tinymce.activeEditor.getContent();
            console.log('Contenido del comentario:', contenido);

            if (!contenido) {
                alert('El comentario no puede estar vacío');
                console.warn('Comentario vacío');
                return;
            }

            try {
                console.log('Enviando solicitud a guardar_comentario.php');
                const response = await fetch('guardar_comentario.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `fk_publicacion=${publicacionId}&contenido=${encodeURIComponent(contenido)}`
                });
                console.log('Respuesta recibida:', response.status, response.statusText);
                const result = await response.json();
                console.log('Resultado:', result);

                if (result.success) {
                    console.log('Comentario guardado exitosamente');
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
                    tinymce.activeEditor.setContent('');
                } else {
                    alert('Error al guardar el comentario: ' + (result.error || 'Desconocido'));
                    console.error('Error en el servidor:', result.error);
                }
            } catch (error) {
                alert('Error en la solicitud: ' + error.message);
                console.error('Error en fetch:', error);
            }
        });

        // Eliminar la clase no-fouc después de cargar los estilos
        window.addEventListener('load', () => {
            console.log('Estilos cargados, removiendo no-fouc');
            document.body.classList.remove('no-fouc');
        });
    </script>
</body>
</html>
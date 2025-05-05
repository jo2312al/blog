<?php
require_once 'vendor/autoload.php';

// Configuración de MeekroDB
DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

// Obtener categorías
$categorias = DB::query("SELECT id, nombre FROM categoria ORDER BY nombre");

// Obtener las 3 publicaciones más recientes para el carrusel (fk_estatu = 2)
$carrusel_publicaciones = DB::query("
    SELECT p.id, p.titulo, p.fk_foto_portada, a.ruta AS foto_ruta
    FROM publicacion p
    LEFT JOIN archivo a ON p.fk_foto_portada = a.id
    WHERE p.fk_estatu = 2
    ORDER BY p.created DESC
    LIMIT 3
");

// Obtener publicaciones para la sección de artículos (fk_estatu = 2)
$publicaciones = DB::query("
    SELECT p.id, p.titulo, p.created, p.fk_foto_portada, a.ruta AS foto_ruta, u.username AS autor_nombre
    FROM publicacion p
    LEFT JOIN archivo a ON p.fk_foto_portada = a.id
    LEFT JOIN user u ON p.fk_user = u.id
    WHERE p.fk_estatu = 2
    ORDER BY p.created DESC
    LIMIT 6
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canacintra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>

    <!-- Banner Section (Carrusel con las 3 últimas publicaciones) -->
    <div class="container my-4">
        <div class="row">
            <div class="col-12">
                <h1>Canacintra</h1>
                <p class="text-muted">Tabasco (banner)</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div id="carouselExample" class="carousel slide">
                    <div class="carousel-indicators">
                        <?php foreach ($carrusel_publicaciones as $index => $pub): ?>
                            <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="<?= $index ?>" <?= $index === 0 ? 'class="active" aria-current="true"' : '' ?> aria-label="Slide <?= $index + 1 ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="carousel-inner">
                        <?php foreach ($carrusel_publicaciones as $index => $pub): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <a href="articulo.php?id=<?= $pub['id'] ?>">
                                    <?php if ($pub['foto_ruta']): ?>
                                        <img src="http://localhost/blog/<?= htmlspecialchars($pub['foto_ruta']) ?>" class="d-block w-100" alt="<?= htmlspecialchars($pub['titulo']) ?>" style="height: 400px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="placeholder-img" style="height: 400px;">
                                            <span>Sin imagen</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5><?= htmlspecialchars($pub['titulo']) ?></h5>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="container">
        <h3>Categorías</h3>
        <div class="row">
            <?php foreach ($categorias as $categoria): ?>
                <div class="col-md-2 col-6">
                    <a href="categorias.php?id=<?= $categoria['id'] ?>" class="text-decoration-none">
                        <div class="cat-card">
                            <div class="cat-label"><?= htmlspecialchars($categoria['nombre']) ?></div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Articles Section -->
    <div class="container my-4">
        <div class="row">
            <div class="col">
                <h3>Artículos</h3>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach ($publicaciones as $publicacion): ?>
                        <div class="col">
                            <div class="custom-card">
                                <?php if ($publicacion['foto_ruta']): ?>
                                    <img src="http://localhost/blog/<?= htmlspecialchars($publicacion['foto_ruta']) ?>" alt="Foto" class="img-fluid">
                                <?php else: ?>
                                    <div class="placeholder-img">Sin imagen</div>
                                <?php endif; ?>
                                <h5 class="card-title">
                                    <a href="articulo.php?id=<?= $publicacion['id'] ?>" class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($publicacion['titulo']) ?>
                                    </a>
                                </h5>
                                <div class="card-meta">
                                    <img src="https://via.placeholder.com/30" alt="Author Avatar">
                                    <span>
                                        <?= htmlspecialchars($publicacion['autor_nombre']) ?> • 
                                        <?= date('M d, Y', strtotime($publicacion['created'])) ?> • 3 Min Read
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="lista_publicaciones.php" class="text-primary mt-3 d-block">Ver Todos Los Artículos</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">CANACINTRA</h3>
                </div>
                <div>
                    <a href="#" class="me-3">Enlace adicional</a>
                    <a href="#" class="me-3">Enlace adicional</a>
                    <a href="#">Enlace adicional</a>
                </div>
                <div>
                    <small>© CANACINTRA 2025</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
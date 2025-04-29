<?php
require_once 'vendor/autoload.php';

// Configuración de MeekroDB
DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

// Obtener el ID de la categoría desde la URL
$categoria_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Obtener el nombre de la categoría
$categoria = DB::queryFirstRow("SELECT nombre FROM categoria WHERE id = %i", $categoria_id);
if (!$categoria) {
    header('Location: index.php');
    exit;
}

// Configuración de paginación
$por_pagina = 6;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $por_pagina;

// Obtener publicaciones de la categoría
$publicaciones = DB::query("
    SELECT p.id, p.titulo, p.contenido, p.created, p.fk_foto_portada, a.ruta AS foto_ruta
    FROM publicacion p
    LEFT JOIN archivo a ON p.fk_foto_portada = a.id
    WHERE p.fk_categoria = %i AND p.fk_estatu = 2
    ORDER BY p.created DESC
    LIMIT %i OFFSET %i", $categoria_id, $por_pagina, $offset
);

// Contar el total de publicaciones para la paginación
$total_publicaciones = DB::queryFirstField("
    SELECT COUNT(*) FROM publicacion WHERE fk_categoria = %i AND fk_estatu = 2", $categoria_id
);
$total_paginas = ceil($total_publicaciones / $por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - <?= htmlspecialchars($categoria['nombre']) ?> | Canacintra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container" style="margin-left: 5%;">
            <a class="navbar-brand" href="index.php">CANACINTRA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="categorias.php">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sobrenosotros.php">Sobre nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacto.php">Contáctanos</a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a href="iniciar.php" class="btn btn-primary btn-md me-2">Iniciar sesión</a>
                        <a href="registro.php" class="btn btn-primary btn-md">Registrar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-4">
        <!-- Breadcrumb and Title -->
        <h1 class="mb-3"><?= htmlspecialchars($categoria['nombre']) ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="categorias.php">Categorías</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($categoria['nombre']) ?></li>
            </ol>
        </nav>

        <!-- Card Grid -->
        <div class="row">
            <?php foreach ($publicaciones as $publicacion): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if ($publicacion['foto_ruta']): ?>
                            <img src="http://localhost/blog/<?= htmlspecialchars($publicacion['foto_ruta']) ?>" class="card-img-top" alt="<?= htmlspecialchars($publicacion['titulo']) ?>">
                        <?php else: ?>
                            <div class="placeholder-img">Sin imagen</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="articulo.php?id=<?= $publicacion['id'] ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($publicacion['titulo']) ?>
                                </a>
                            </h5>
                            <p class="card-text">
                                <?= htmlspecialchars(substr(strip_tags($publicacion['contenido']), 0, 100)) . '...' ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($publicaciones)): ?>
                <div class="col-12">
                    <p>No hay publicaciones en esta categoría.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_paginas > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <!-- Anterior -->
                    <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="categorias.php?id=<?= $categoria_id ?>&pagina=<?= $pagina - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    <!-- Páginas -->
                    <?php
                    $rango = 2; // Mostrar 2 páginas antes y después de la actual
                    $inicio = max(1, $pagina - $rango);
                    $fin = min($total_paginas, $pagina + $rango);

                    if ($inicio > 1) {
                        echo '<li class="page-item"><a class="page-link" href="categorias.php?id=' . $categoria_id . '&pagina=1">1</a></li>';
                        if ($inicio > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }

                    for ($i = $inicio; $i <= $fin; $i++) {
                        echo '<li class="page-item ' . ($i == $pagina ? 'active' : '') . '">';
                        echo '<a class="page-link" href="categorias.php?id=' . $categoria_id . '&pagina=' . $i . '">' . $i . '</a>';
                        echo '</li>';
                    }

                    if ($fin < $total_paginas) {
                        if ($fin < $total_paginas - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="categorias.php?id=' . $categoria_id . '&pagina=' . $total_paginas . '">' . $total_paginas . '</a></li>';
                    }
                    ?>
                    <!-- Siguiente -->
                    <li class="page-item <?= $pagina >= $total_paginas ? 'disabled' : '' ?>">
                        <a class="page-link" href="categorias.php?id=<?= $categoria_id ?>&pagina=<?= $pagina + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
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
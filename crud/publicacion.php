<?php
require_once 'db.php';

$publicacion_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($publicacion_id <= 0) {
    header('Location: index.php');
    exit;
}

// Obtener las vistas usando vista.php
$vistas = include 'vista.php';

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

// Incluir la vista
include 'articulo.php';
?>
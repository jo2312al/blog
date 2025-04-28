<?php
require_once 'vendor/autoload.php';
DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$fk_publicacion = (int)$_POST['fk_publicacion'];
$contenido = trim($_POST['contenido']);
if (empty($contenido) || $fk_publicacion <= 0) {
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

DB::insert('comentario', [
    'fk_publicacion' => $fk_publicacion,
    'contenido' => $contenido,
    'fk_estatu' => 1,
    'fk_user' => 1, // ID fija
    'created' => date('Y-m-d H:i:s')
]);

$autor_nombre = DB::queryFirstField("SELECT username FROM user WHERE id = 1");
echo json_encode([
    'success' => true,
    'autor_nombre' => $autor_nombre,
    'created' => date('d/m/Y H:i')
]);
exit;
?>
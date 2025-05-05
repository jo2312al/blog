<?php
session_start();

require_once 'vendor/autoload.php';
DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

header('Content-Type: application/json');

// Verificar si hay una sesión activa
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Debes iniciar sesión para comentar']);
    exit;
}

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

// Guardar el comentario con el ID del usuario autenticado
DB::insert('comentario', [
    'fk_publicacion' => $fk_publicacion,
    'contenido' => $contenido,
    'fk_estatu' => 1,
    'fk_user' => $_SESSION['user_id'],
    'created' => date('Y-m-d H:i:s')
]);

// Obtener el nombre del usuario autenticado
$autor_nombre = DB::queryFirstField("SELECT username FROM user WHERE id = %i", $_SESSION['user_id']);
echo json_encode([
    'success' => true,
    'autor_nombre' => $autor_nombre,
    'created' => date('d/m/Y H:i')
]);
exit;
?>
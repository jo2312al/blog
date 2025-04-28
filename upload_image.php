<?php
require_once 'vendor/autoload.php';
require_once 'crud/archivo.php';

DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

// Habilitar depuración
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    if (!isset($_FILES['file']) || $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception('No se subió ningún archivo', 400);
    }

    $file = $_FILES['file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir el archivo: ' . $file['error'], 400);
    }

    // Validar tipo y tamaño
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Tipo de archivo no permitido', 400);
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('El archivo excede el tamaño máximo de 5MB', 400);
    }

    // Generar nombre único
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $random_name = bin2hex(random_bytes(8)) . '.' . $extension;
    $upload_dir = 'Uploads/';
    $upload_path = $upload_dir . $random_name;

    // Crear directorio si no existe
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Mover el archivo
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        throw new Exception('Error al mover el archivo', 500);
    }

    // Recopilar metadata
    $data_archivo = [
        'nombre' => $random_name,
        'nombre_temporal' => $file['name'], // Nombre original
        'ruta' => $upload_path, // Ruta relativa
        'tipo' => $file['type'], // MIME type (image/jpeg, etc.)
        'tamano' => $file['size'], // Tamaño en bytes
        'descripcion_corta' => '', // Vacío por defecto
        'descripcion_larga' => '', // Vacío por defecto
        'descargas' => 0, // Inicializar en 0
        'created' => date('Y-m-d H:i:s'),
        'updated' => null, // NULL al crear
        'fk_user' => 1
    ];

    // Guardar en la base de datos
    $archivo_id = archivo::create($data_archivo);
    error_log('Archivo guardado en base de datos, ID: ' . $archivo_id . ', Datos: ' . print_r($data_archivo, true));

    // Devolver respuesta para TinyMCE
    echo json_encode([
        'success' => true,
        'archivo_id' => $archivo_id,
        'location' => 'http://localhost/blog/' . $upload_path
    ]);
    exit;

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['error' => $e->getMessage()]);
    error_log('Error en upload_image.php: ' . $e->getMessage() . ' (Código: ' . $e->getCode() . ')');
    exit;
}
?>
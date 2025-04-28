<?php
require_once 'vendor/autoload.php';
require_once 'crud/archivo.php';

DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

// Establecer header JSON
header('Content-Type: application/json');

// Habilitar depuración
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Depuración: Registrar inicio
error_log('upload_dropzone.php ejecutado');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    // Depuración: Registrar datos recibidos
    error_log('FILES recibido: ' . print_r($_FILES, true));

    if (!isset($_FILES['file']) || $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception('No se recibió ningún archivo', 400);
    }

    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir el archivo: ' . $_FILES['file']['error'], 400);
    }

    $target_dir = "Uploads/";
    if (!file_exists($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            throw new Exception('No se pudo crear la carpeta Uploads', 500);
        }
    }

    // Verificar permisos
    if (!is_writable($target_dir)) {
        throw new Exception('La carpeta Uploads no tiene permisos de escritura', 500);
    }

    $original_name = $_FILES['file']['name'];
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $random_name = uniqid('img_') . '.' . $extension;
    $target_file = $target_dir . $random_name;

    // Depuración
    error_log('Intentando subir archivo: ' . $original_name . ' a ' . $target_file);

    // Validar si es una imagen
    $check = getimagesize($_FILES['file']['tmp_name']);
    if ($check === false) {
        throw new Exception('El archivo no es una imagen', 400);
    }

    // Validar tamaño (5MB)
    if ($_FILES['file']['size'] > 5000000) {
        throw new Exception('El archivo es demasiado grande', 400);
    }

    // Validar tipo de archivo
    if ($extension != 'jpg' && $extension != 'png' && $extension != 'gif') {
        throw new Exception('Solo se permiten archivos JPG, PNG y GIF', 400);
    }

    // Subir el archivo
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        throw new Exception('Error al mover el archivo', 500);
    }

    // Guardar en la tabla archivo
    $data_archivo = [
        'nombre' => $original_name,
        'nombre_temporal' => $random_name,
        'ruta' => $target_file,
        'tipo' => $_FILES['file']['type'],
        'tamano' => $_FILES['file']['size'],
        'created' => date('Y-m-d H:i:s'),
        'fk_user' => 1
    ];
    archivo::create($data_archivo);
    $archivo_id = DB::insertId();
    error_log('Archivo guardado en la base de datos, ID: ' . $archivo_id);

    // Respuesta de éxito
    echo json_encode(['success' => true, 'archivo_id' => $archivo_id]);
    exit;

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['error' => $e->getMessage()]);
    error_log('Error en upload_dropzone.php: ' . $e->getMessage() . ' (Código: ' . $e->getCode() . ')');
    exit;
}
?>
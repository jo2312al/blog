<?php
require_once 'vendor/autoload.php';
require_once 'crud/publicacion.php';
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

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    // Depuración
    error_log('POST recibido en guardar_publicacion.php: ' . print_r($_POST, true));

    // Validar campos obligatorios
    if (empty($_POST['titulo']) || empty($_POST['fk_categoria']) || empty($_POST['contenido'])) {
        throw new Exception('Todos los campos son obligatorios', 400);
    }

    // Obtener IDs de archivos (Dropzone y TinyMCE)
    $archivo_ids = !empty($_POST['archivo_ids']) ? array_filter(explode(',', $_POST['archivo_ids'])) : [];
    $tinymce_archivo_ids = !empty($_POST['tinymce_archivo_ids']) ? array_filter(explode(',', $_POST['tinymce_archivo_ids'])) : [];

    // Combinar todos los IDs para validar
    $all_archivo_ids = array_merge($archivo_ids, $tinymce_archivo_ids);
    if (empty($all_archivo_ids)) {
        throw new Exception('Debe subir al menos una imagen', 400);
    }

    // Depuración
    error_log('IDs de archivos recibidos (Dropzone): ' . print_r($archivo_ids, true));
    error_log('IDs de archivos recibidos (TinyMCE): ' . print_r($tinymce_archivo_ids, true));

    // Usar el primer archivo como foto de portada
    $foto_portada_id = $all_archivo_ids[0];
    error_log('Foto de portada ID: ' . $foto_portada_id);

    // Verificar que el archivo exista
    $archivo = archivo::read($foto_portada_id);
    if (!$archivo) {
        throw new Exception('El archivo de portada no existe en la base de datos', 400);
    }

    // Insertar la publicación
    $data_publicacion = [
        'titulo' => $_POST['titulo'],
        'resumen' => $_POST['resumen'] ?? '',
        'contenido' => $_POST['contenido'],
        'fk_foto_portada' => $foto_portada_id,
        'fk_estatu' => isset($_POST['accion']) && $_POST['accion'] === 'borrador' ? 1 : 2, // 1 = CAPTURA, 2 = PUBLICADA
        'fk_categoria' => $_POST['fk_categoria'],
        'created' => date('Y-m-d H:i:s'),
        'fk_user' => 1
    ];
    $publicacion_id = publicacion::create($data_publicacion);
    error_log('Publicación guardada, ID: ' . $publicacion_id);

    // Llamar a vincular_publicacion_archivo.php usando cURL
    $post_data = [
        'publicacion_id' => $publicacion_id,
        'archivo_ids' => implode(',', $archivo_ids),
        'tinymce_archivo_ids' => implode(',', $tinymce_archivo_ids)
    ];
    error_log('Enviando datos a vincular_publicacion_archivo.php: ' . print_r($post_data, true));
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/blog/vincular_publicacion_archivo.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $result = curl_exec($ch);
    
    if ($result === false) {
        $curl_error = curl_error($ch);
        error_log('Error cURL al llamar a vincular_publicacion_archivo.php: ' . $curl_error);
    } else {
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        error_log('Respuesta HTTP de vincular_publicacion_archivo.php: Código ' . $http_code);
        error_log('Respuesta cruda de vincular_publicacion_archivo.php: ' . $result);
        $response = json_decode($result, true);
        if ($response === null) {
            error_log('Error al decodificar JSON de vincular_publicacion_archivo.php: ' . json_last_error_msg());
        } elseif (!$response['success']) {
            error_log('Fallo al vincular archivos: ' . ($response['error'] ?? 'Error desconocido'));
        } else {
            error_log('Éxito al vincular archivos: ' . print_r($response, true));
        }
    }
    curl_close($ch);

    // Redirigir a index.php
    header('Location: index.php');
    exit;

} catch (Exception $e) {
    // En caso de error, devolver JSON
    header('Content-Type: application/json');
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['error' => $e->getMessage()]);
    error_log('Error en guardar_publicacion.php: ' . $e->getMessage() . ' (Código: ' . $e->getCode() . ')');
    exit;
}
?>
<?php
require_once 'vendor/autoload.php';
require_once 'crud/archivo.php';
require_once 'crud/publicacion_archivo.php';

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

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    // Depuración: Registrar todos los datos POST
    error_log('POST recibido en vincular_publicacion_archivo.php: ' . print_r($_POST, true));

    // Validar parámetros
    if (empty($_POST['publicacion_id'])) {
        throw new Exception('Falta el parámetro publicacion_id', 400);
    }

    $publicacion_id = (int)$_POST['publicacion_id'];
    $archivo_ids = !empty($_POST['archivo_ids']) ? array_filter(explode(',', $_POST['archivo_ids'])) : [];
    $tinymce_archivo_ids = !empty($_POST['tinymce_archivo_ids']) ? array_filter(explode(',', $_POST['tinymce_archivo_ids'])) : [];

    // Combinar IDs de Dropzone y TinyMCE
    $all_archivo_ids = array_merge($archivo_ids, $tinymce_archivo_ids);

    // Depuración: Registrar parámetros procesados
    error_log('Vinculando publicacion_id: ' . $publicacion_id . ', all_archivo_ids: ' . print_r($all_archivo_ids, true));

    // Validar que haya al menos un archivo
    if (empty($all_archivo_ids)) {
        error_log('No hay archivos para vincular');
        echo json_encode(['success' => true, 'registros_creados' => 0]);
        exit;
    }

    // Procesar todos los archivos
    $success_count = 0;
    foreach ($all_archivo_ids as $index => $archivo_id) {
        $archivo_id = (int)$archivo_id;
        if (empty($archivo_id)) {
            error_log('ID de archivo inválido en índice ' . $index . ': ' . $archivo_id);
            continue;
        }

        // Verificar que el archivo exista
        error_log('Verificando archivo ID: ' . $archivo_id);
        $archivo = archivo::read($archivo_id);
        if (!$archivo) {
            error_log('Archivo no encontrado para ID: ' . $archivo_id);
            continue;
        }

        // Crear registro en publicacion_archivo
        $data_publicacion_archivo = [
            'fk_publicacion' => $publicacion_id,
            'fk_archivo' => $archivo_id,
            'created' => date('Y-m-d H:i:s'),
            'fk_user' => 1
        ];
        error_log('Datos para publicacion_archivo: ' . print_r($data_publicacion_archivo, true));
        try {
            $insert_id = publicacion_archivo::create($data_publicacion_archivo);
            error_log('Registro en publicacion_archivo creado, ID: ' . $insert_id . ', fk_publicacion: ' . $publicacion_id . ', fk_archivo: ' . $archivo_id);
            $success_count++;
        } catch (Exception $e) {
            error_log('Error al crear registro en publicacion_archivo para ID ' . $archivo_id . ': ' . $e->getMessage());
        }
    }

    // Depuración: Resultado final
    error_log('Registros creados en publicacion_archivo: ' . $success_count);
    if ($success_count === 0 && count($all_archivo_ids) > 0) {
        throw new Exception('No se crearon registros en publicacion_archivo', 500);
    }

    // Respuesta de éxito
    echo json_encode(['success' => true, 'registros_creados' => $success_count]);
    exit;

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['error' => $e->getMessage()]);
    error_log('Error en vincular_publicacion_archivo.php: ' . $e->getMessage() . ' (Código: ' . $e->getCode() . ')');
    exit;
}
?>
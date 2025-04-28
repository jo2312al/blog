<?php
require_once 'vendor/autoload.php';
require_once 'crud/archivo.php';
require_once 'crud/publicacion_archivo.php';

DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

try {
    $publicacion_id = 1; // Cambia por un ID válido de la tabla publicacion
    $archivo_ids = '2,3'; // Cambia por IDs válidos de la tabla archivo

    $data = [
        'publicacion_id' => $publicacion_id,
        'archivo_ids' => $archivo_ids
    ];

    $post_data = http_build_query($data);
    error_log('Enviando datos a test_vincular: ' . $post_data);
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => $post_data
        ]
    ];
    $context = stream_context_create($options);
    $url = 'http://localhost/blog/vincular_publicacion_archivo.php';
    error_log('Intentando llamar a: ' . $url);
    $result = @file_get_contents($url, false, $context);
    if ($result === FALSE) {
        $error = error_get_last();
        throw new Exception('Error al llamar a vincular_publicacion_archivo.php: ' . ($error['message'] ?? 'No se recibió respuesta'));
    }
    echo $result;
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    error_log('Error en test_vincular.php: ' . $e->getMessage());
}
?>
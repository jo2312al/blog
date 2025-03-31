<?php
require_once 'vendor/autoload.php'; 

DB::$user = 'root'; 
DB::$password = '';  
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

class archivo {

    public static function create($data) {
        DB::insert('archivo', $data);
        return DB::insertId(); // Devuelve el ID del usuario creado
    }

    // Leer un usuario por ID
    public static function read($id) {
        return DB::queryFirstRow("SELECT * FROM archivo WHERE id = %i", $id);
    }

    // Actualizar un usuario por ID
    public static function update($id, $data) {
        DB::update('archivo', $data, "id = %i", $id);
    }

    // Eliminar un usuario por ID
    public static function delete($id) {
        DB::delete('archivo', "id = %i", $id);
    }


    public static function listAll() {
        return DB::query("
            SELECT pa.*, p.titulo AS publicacion, a.nombre AS archivo, u.username AS usuario
            FROM publicacion_archivo pa
            JOIN publicacion p ON pa.fk_publicacion = p.id
            JOIN archivo a ON pa.fk_archivo = a.id
            JOIN user u ON pa.fk_user = u.id
        ");
    }
}
?>
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
            SELECT a.id, a.nombre, a.ruta, a.tipo, a.tamano, 
                   a.descripcion_corta, a.descripcion_larga, 
                   a.descargas, a.created, a.updated, 
                   u.username AS usuario
            FROM archivo a
            JOIN user u ON a.fk_user = u.id
        ");
    }
}
?>
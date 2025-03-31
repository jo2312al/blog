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
            SELECT p.*, u.username AS usuario, c.nombre AS categoria, e.nombre AS estatus, a.nombre AS foto_portada
            FROM publicacion p
            JOIN user u ON p.fk_user = u.id
            JOIN categoria c ON p.fk_categoria = c.id
            JOIN estatu e ON p.fk_estatu = e.id
            JOIN archivo a ON p.fk_foto_portada = a.id
        ");
    }
}
?>
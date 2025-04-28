<?php
require_once 'vendor/autoload.php'; 

DB::$user = 'root'; 
DB::$password = '';  
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

class estatu {

    public static function create($data) {
        DB::insert('estatu', $data);
        return DB::insertId(); // Devuelve el ID del usuario creado
    }

    // Leer un usuario por ID
    public static function read($id) {
        return DB::queryFirstRow("SELECT * FROM estatu WHERE id = %i", $id);
    }

    // Actualizar un usuario por ID
    public static function update($id, $data) {
        DB::update('estatu', $data, "id = %i", $id);
    }

    // Eliminar un usuario por ID
    public static function delete($id) {
        DB::delete('estatu', "id = %i", $id);
    }


    public static function listAll() {
        return DB::query("
            SELECT e.*, u.username AS usuario
            FROM estatu e
            JOIN user u ON e.fk_user = u.id
        ");
    }
}
?>
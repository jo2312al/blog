<?php
require_once 'vendor/autoload.php'; 

DB::$user = 'root'; 
DB::$password = '';  
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

class categoria {

    public static function create($data) {
        DB::insert('categoria', $data);
        return DB::insertId(); // Devuelve el ID del usuario creado
    }

    // Leer un usuario por ID
    public static function read($id) {
        return DB::queryFirstRow("SELECT * FROM categoria WHERE id = %i", $id);
    }

    // Actualizar un usuario por ID
    public static function update($id, $data) {
        DB::update('categoria', $data, "id = %i", $id);
    }

    // Eliminar un usuario por ID
    public static function delete($id) {
        DB::delete('categoria', "id = %i", $id);
    }


        public static function listAll() {
            return DB::query("
                SELECT c.*, u.username AS usuario
                FROM categoria c
                LEFT JOIN user u ON c.fk_user = u.id
            ");
        }
}
?>
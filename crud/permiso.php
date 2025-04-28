<?php
require_once 'vendor/autoload.php'; 

DB::$user = 'root'; 
DB::$password = '';  
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

class permiso {

    public static function create($data) {
        DB::insert('permiso', $data);
        return DB::insertId(); // Devuelve el ID del usuario creado
    }

    // Leer un usuario por ID
    public static function read($id) {
        return DB::queryFirstRow("SELECT * FROM permiso WHERE id = %i", $id);
    }

    // Actualizar un usuario por ID
    public static function update($id, $data) {
        DB::update('permiso', $data, "id = %i", $id);
    }

    // Eliminar un usuario por ID
    public static function delete($id) {
        DB::delete('permiso', "id = %i", $id);
    }


        public static function listAll() {
            return DB::query("
                SELECT p.*, r.nombre AS rol
                FROM permiso p
                LEFT JOIN rol r ON p.fk_rol = r.id
            ");
        }
}
?>
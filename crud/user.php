<?php
require_once 'vendor/autoload.php'; 

DB::$user = 'root'; 
DB::$password = '';  
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

class user {

    public static function create($data) {
        DB::insert('user', $data);
        return DB::insertId(); // Devuelve el ID del usuario creado
    }

    // Leer un usuario por ID
    public static function read($id) {
        return DB::queryFirstRow("SELECT * FROM user WHERE id = %i", $id);
    }

    // Actualizar un usuario por ID
    public static function update($id, $data) {
        DB::update('user', $data, "id = %i", $id);
    }

    // Eliminar un usuario por ID
    public static function delete($id) {
        DB::delete('user', "id = %i", $id);
    }

    // Listar todos los usuarios
    public static function listAll() {
        return DB::query("SELECT u.id, u.username, u.email, u.status, r.nombre as rol 
                         FROM user u 
                         LEFT JOIN rol r ON u.fk_rol = r.id");
    }
}
?>
<?php
require_once 'vendor/autoload.php'; 

DB::$user = 'root'; 
DB::$password = '';  
DB::$dbName = 'canacintra';
DB::$host = 'localhost';
DB::$encoding = 'utf8';

class user {

    public static function create($data) {
        // Hashear la contraseña si se proporciona
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        DB::insert('user', $data);
        return DB::insertId(); // Devuelve el ID del usuario creado
    }

    // Leer un usuario por ID
    public static function read($id) {
        return DB::queryFirstRow("SELECT * FROM user WHERE id = %i", $id);
    }

    // Actualizar un usuario por ID
    public static function update($id, $data) {
        // Hashear la contraseña si se proporciona
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
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

    // Iniciar sesión con usuario o correo
    public static function login($identifier, $password) {
        // Buscar usuario por username o email
        $user = DB::queryFirstRow("SELECT * FROM user WHERE username = %s OR email = %s", $identifier, $identifier);
        
        if ($user) {
            // Comparación en texto plano para depuración (no usar en producción)
            echo "Debug: Contraseña en BD: " . $user['password'] . "<br>";
            if ($user['password'] === $password) {
                return $user; // Retorna los datos del usuario si la autenticación es exitosa
            }
        }
        return false; // Retorna false si falla
    }
}
?>
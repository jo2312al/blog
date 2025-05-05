<?php
// Asegurarse de que $publicacion_id esté definido
if (!isset($publicacion_id) || $publicacion_id <= 0) {
    return 0; // Retornar 0 si no hay un ID válido
}

// Incrementar el contador de vistas
DB::query("UPDATE publicacion SET vistas = vistas + 1 WHERE id = %i", $publicacion_id);

// Obtener el número actualizado de vistas
$vistas = DB::queryFirstField("SELECT vistas FROM publicacion WHERE id = %i", $publicacion_id);

return $vistas ?? 0; // Retornar las vistas o 0 si no se encontraron
?>
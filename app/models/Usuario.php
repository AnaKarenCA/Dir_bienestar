<?php

class Usuario extends Model
{
    public function buscarPorCorreo($correo)
    {
        $stmt = $this->db->query("
            SELECT *
            FROM Usuario
            WHERE correo = ?
            LIMIT 1
        ");

        $stmt->execute([$correo]);

        return $stmt->fetch();
    }
    public function obtenerPorId($id)
{
    $stmt = $this->db->query("
        SELECT
            u.*,
            r.tipo_rol,
            ua.nombre AS unidad
        FROM Usuario u
        INNER JOIN Rol r
            ON r.id = u.rol_id
        INNER JOIN Unidad_administrativa ua
            ON ua.id = u.unidad_administrativa_id
        WHERE u.id = ?
    ");

    $stmt->execute([$id]);

    return $stmt->fetch();
}
}
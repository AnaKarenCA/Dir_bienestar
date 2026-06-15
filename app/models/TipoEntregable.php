<?php

class TipoEntregable extends Model
{
    public function obtenerTodos()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM Tipo_entregable
            ORDER BY nombre_entregable
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
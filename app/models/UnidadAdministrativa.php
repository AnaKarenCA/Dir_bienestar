<?php

class UnidadAdministrativa extends Model
{
    public function obtenerTodas()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM Unidad_administrativa
            ORDER BY nombre
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
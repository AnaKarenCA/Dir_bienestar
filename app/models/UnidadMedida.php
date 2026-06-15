<?php

class UnidadMedida extends Model
{
    public function obtenerTodas()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM Unidad_de_medida
            ORDER BY nombre
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
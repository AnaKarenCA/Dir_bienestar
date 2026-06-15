<?php

class CodigoPostal extends Model
{
    public function obtenerPorSubdelegacion($subdelegacionId)
    {
        $stmt = $this->db->query("
            SELECT id, cp
            FROM Codigo_postal
            WHERE subdelegacion_id = ?
            ORDER BY cp
        ");
        $stmt->execute([$subdelegacionId]);
        return $stmt->fetchAll();
    }
}